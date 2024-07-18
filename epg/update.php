<?php
/**
 * @file update.php
 * @brief 更新脚本
 * 
 * 该脚本用于定期从配置的 XML 源下载节目数据，并将其存入 SQLite 数据库中。
 * 
 * 作者: Tak
 * GitHub: https://github.com/TakcC/PHP-EPG-Docker-Server
 */

// 设置超时时间为10分钟
set_time_limit(10*60);

// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

// 设置时间格式
$timeformat = "[y-m-d H:i:s]";

// 引入配置文件
require_once 'config.php';

// 创建或打开数据库
try {
    $db_file = __DIR__ . '/adata.db';
    $dsn = 'sqlite:' . $db_file;
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 初始化数据库表
    $db->exec("CREATE TABLE IF NOT EXISTS epg_diyp (
        date TEXT NOT NULL,
        channel TEXT NOT NULL,
        epg TEXT,
        PRIMARY KEY (date, channel)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS epg_xml (
        date TEXT PRIMARY KEY,
        content TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS update_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        log_message TEXT NOT NULL,
        timestamp TEXT DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    echo '数据库连接失败: ' . $e->getMessage();
    exit();
}

// 删除过期数据和日志
function deleteOldData($db, $keep_days, &$log_messages) {
    global $timeformat;
    $threshold_date = date('Y-m-d', strtotime("-$keep_days days + 1 day"));
    $stmt = $db->prepare("DELETE FROM epg_diyp WHERE date < :threshold_date");
    $stmt->bindValue(':threshold_date', $threshold_date, PDO::PARAM_STR);
    $stmt->execute();
    $log_messages[] = date($timeformat) . " 【清理数据】 共 {$stmt->rowCount()} 条。";

    // 清除过期日志，保留最近 $keep_days 天的日志
    $stmt = $db->prepare("DELETE FROM update_log WHERE timestamp < :threshold_date");
    $stmt->bindValue(':threshold_date', $threshold_date, PDO::PARAM_STR);
    $stmt->execute();
    $log_messages[] = date($timeformat) . " 【清理日志】 共 {$stmt->rowCount()} 条。";
}

// 全局变量，用于标记是否已经清除当天数据
$cleared_today = false;

// 清除当天数据，仅在第一次调用时执行
function clearTodayData($date, $db) {
    global $cleared_today;
    if (!$cleared_today) {
        $stmt = $db->prepare("DELETE FROM epg_diyp WHERE date = :date");
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $stmt = $db->prepare("DELETE FROM epg_xml WHERE date = :date");
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $cleared_today = true;
    }
}

// 格式化时间函数
function getFormatTime($time) {
    $date = substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2);
    $time = strlen($time) >= 12 ? substr($time, 8, 2) . ':' . substr($time, 10, 2) : '';
    return ['date' => $date, 'time' => $time];
}

// 下载数据并存入数据库
function downloadData($xml_url, $db, &$log_messages) {
    global $timeformat;
    // 获取 URL 的最后三个字符来检查扩展名
    $extension = strtolower(substr($xml_url, -3));

    $xml_data = @file_get_contents($xml_url);
    if ($xml_data !== false) {
        if ($extension === '.gz') {
            // 解压缩文件内容
            $xml_data = gzdecode($xml_data);
            if ($xml_data === false) {
                throw new Exception('解压缩失败');
            }
        } else if ($extension !== 'xml') {
            throw new Exception('不支持文件格式' . $extension);
        }

        // 清除当天数据
        clearTodayData(date('Y-m-d'), $db);

        $db->beginTransaction();
        try {
            processXmlData($xml_data, date('Y-m-d'), $db);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $log_messages[] = "处理数据时出错: " . $e->getMessage();
        }
        $log_messages[] = date($timeformat) . " 【更新成功】";
    } else {
        $log_messages[] = date($timeformat) . " 【下载失败！！！】";
    }
}

// 处理 XML 数据并存入数据库
function processXmlData($xml_data, $date, $db) {
    // 插入数据到 epg_xml 表，如果有冲突则忽略
    $stmt = $db->prepare('INSERT OR IGNORE INTO epg_xml (date, content) VALUES (:date, :content)');
    $stmt->bindValue(':date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':content', $xml_data, PDO::PARAM_STR);
    $stmt->execute();

    $reader = new XMLReader();
    if (!$reader->XML($xml_data)) {
        throw new Exception("无法解析 XML 数据");
    }

    // 初始化存储转换后 channel 的数组
    $channelsData = [];

    // 读取 channel 元素到数组中
    while ($reader->read() && $reader->name !== 'channel');
    while ($reader->name === 'channel') {
        $channel = new SimpleXMLElement($reader->readOuterXML());
        $channelId = (string)$channel['id'];
        $channelsData[$channelId] = [
            'channel_name' => (string)$channel->{'display-name'},
            'epg_data' => [] // 初始化 epg_data 数组
        ];
        $reader->next('channel');
    }

    // 重置 XMLReader 到 programme 元素
    $reader->close();
    if (!$reader->XML($xml_data)) {
        throw new Exception("无法重新解析 XML 数据");
    }
    while ($reader->read() && $reader->name !== 'programme');

    // 遍历 programme 元素
    while ($reader->name === 'programme') {
        $programme = new SimpleXMLElement($reader->readOuterXML());

        // 获取时间和日期
        $start = getFormatTime((string)$programme['start']);
        $stop = getFormatTime((string)$programme['stop']);

        // 构建 programme 数组表示
        $programmeArray = [
            'start' => $start['time'],
            'end' => $stop['time'],
            'title' => (string)$programme->title,
            'desc' => '' // 可以根据需要添加描述信息
        ];

        // 将 programme 添加到对应的 channel 和日期
        $channelId = (string)$programme['channel'];
        if (isset($channelsData[$channelId])) {
            if (!isset($channelsData[$channelId]['epg_data'][$start['date']])) {
                $channelsData[$channelId]['epg_data'][$start['date']] = [];
            }
            $channelsData[$channelId]['epg_data'][$start['date']][] = $programmeArray;
        }

        // 移动到下一个 programme 元素
        $reader->next('programme');
    }

    // 关闭 XMLReader
    $reader->close();

    // 插入数据到 epg_diyp 表，如果有冲突则忽略
    foreach ($channelsData as $channelId => $channelData) {
        $channelName = $channelData['channel_name'];
        foreach ($channelData['epg_data'] as $date => $programmes) {
            $content = json_encode([
                'channel_name' => $channelName,
                'date' => $date,
                'url' => 'https://github.com/TakcC/PHP-EPG-Docker-Server',
                'epg_data' => $programmes
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare('INSERT OR IGNORE INTO epg_diyp (date, channel, epg) VALUES (:date, :channel, :epg)');
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':channel', $channelName, PDO::PARAM_STR);
            $stmt->bindValue(':epg', $content, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}

// 统计更新前数据条数
$initialCount = $db->query("SELECT COUNT(*) FROM epg_diyp")->fetchColumn();

// 删除过期数据
deleteOldData($db, $Config['days_to_keep'], $log_messages);

// 更新数据
foreach ($Config['xml_urls'] as $xml_url) {
    // 忽略以 # 开头的 URL
    if (strpos($xml_url, '#') === 0) {
        continue;
    }    
    // 去除 URL 后的注释部分
    $url_parts = explode('#', $xml_url);
    $cleaned_url = trim($url_parts[0]);

    $log_messages[] = date($timeformat) . " 【更新地址】 $cleaned_url";
    downloadData($cleaned_url, $db, $log_messages);
}

// 统计更新后数据条数
$finalCount = $db->query("SELECT COUNT(*) FROM epg_diyp")->fetchColumn();
$log_messages[] = date($timeformat) . " 【更新完成】 更新前：{$initialCount} 条，更新后：{$finalCount} 条。";

// 将日志信息写入数据库
$log_message_str = implode("<br>", $log_messages);
$timestamp = date('Y-m-d H:i:s'); // 使用设定的时区时间
$stmt = $db->prepare('INSERT INTO update_log (log_message, timestamp) VALUES (:log_message, :timestamp)');
$stmt->bindValue(':log_message', $log_message_str, PDO::PARAM_STR);
$stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
$stmt->execute();

echo implode("<br>", $log_messages);

?>
