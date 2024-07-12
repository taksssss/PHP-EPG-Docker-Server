<?php
// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

// 引入配置文件
require_once 'config.php';

// 创建或打开数据库
$db_file = __DIR__ . '/adata.db';
$db = new SQLite3($db_file);

// 创建表格（如果不存在）
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

// 删除过期数据
function deleteOldData($db, $keep_days) {
    $threshold_date = date('Y-m-d', strtotime("-$keep_days days + 1 day"));
    $db->exec("DELETE FROM epg_diyp WHERE date < '$threshold_date'");
    echo " 共 {$db->changes()} 条。<br>";
}

// 全局变量，用于标记是否已经清除当天数据
$cleared_today = false;

// 清除当天数据，仅在第一次调用时执行
function clearTodayData($date, $db) {
    global $cleared_today;
    if (!$cleared_today) {
        $db->exec("DELETE FROM epg_diyp WHERE date = '$date'");
        $db->exec("DELETE FROM epg_xml WHERE date = '$date'");
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
function downloadData($xml_url, $db) {
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

        $db->exec('BEGIN');
        try {
            processXmlData($xml_data, date('Y-m-d'), $db);
            $db->exec('COMMIT');
        } catch (Exception $e) {
            $db->exec('ROLLBACK');
            echo "处理数据时出错: " . $e->getMessage() . "<br>";
        }
        echo date("H:i:s") . " 【更新成功】<br>";
    } else {
        echo "下载失败: $xml_url<br>";
    }
}

// 处理 XML 数据并存入数据库
function processXmlData($xml_data, $date, $db) {
    // 插入数据到 epg_xml 表，如果有冲突则忽略
    $stmt = $db->prepare('INSERT OR IGNORE INTO epg_xml (date, content) VALUES (:date, :content)');
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':content', $xml_data, SQLITE3_TEXT);
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
                'url' => 'https://github.com/TakcC/PHP-EPG-Server',
                'epg_data' => $programmes
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare('INSERT OR IGNORE INTO epg_diyp (date, channel, epg) VALUES (:date, :channel, :epg)');
            $stmt->bindValue(':date', $date, SQLITE3_TEXT);
            $stmt->bindValue(':channel', $channelName, SQLITE3_TEXT);
            $stmt->bindValue(':epg', $content, SQLITE3_TEXT);
            $stmt->execute();
        }
    }
}

// 统计更新前数据条数
$initialCount = $db->querySingle("SELECT COUNT(*) FROM epg_diyp");

// 删除过期数据
echo date("H:i:s") . " 【删除过期数据】 ";
deleteOldData($db, $Config['days_to_keep']);

// 更新数据
foreach ($Config['xml_urls'] as $xml_url) {
    echo "<br>" . date("H:i:s") . " 【更新源】$xml_url<br>";
    downloadData(trim($xml_url), $db);
}

// 统计更新后数据条数
$finalCount = $db->querySingle("SELECT COUNT(*) FROM epg_diyp");


echo "<br>" . date("H:i:s") . " 【数据更新完成】 更新前：{$initialCount} 条，更新后：{$finalCount} 条。";
?>
