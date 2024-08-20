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

// 引入公共脚本
require_once 'public.php';

// 设置超时时间为20分钟
set_time_limit(20*60);

// 设置时间格式
define('TIME_FORMAT', "[y-m-d H:i:s]");

// 日志记录函数
function logMessage(&$log_messages, $message) {
    $log_messages[] = date(TIME_FORMAT) . " " . $message;
}

// 删除过期数据和日志
function deleteOldData($db, $keep_days, &$log_messages) {
    global $Config;

    // 删除 t.xml 和 t.xml.gz 文件
    if (($Config['gen_xml'] == 0 || $Config['gen_xml'] == 1) && file_exists('./t.xml')) {
        unlink('./t.xml');
    }
    if (($Config['gen_xml'] == 0 || $Config['gen_xml'] == 2) && file_exists('./t.xml.gz')) {
        unlink('./t.xml.gz');
    }

    // 循环清理过期数据
    $threshold_date = date('Y-m-d', strtotime("-$keep_days days + 1 day"));
    $tables = [
        'epg_data' => ['date', '清理EPG数据'],
        'update_log' => ['timestamp', '清理更新日志'],
        'cron_log' => ['timestamp', '清理定时日志']
    ];
    foreach ($tables as $table => $values) {
        list($column, $logMessage) = $values;
        $stmt = $db->prepare("DELETE FROM $table WHERE $column < :threshold_date");
        $stmt->bindValue(':threshold_date', $threshold_date, PDO::PARAM_STR);
        $stmt->execute();
        logMessage($log_messages, "【{$logMessage}】 共 {$stmt->rowCount()} 条。");
    }
}

// 格式化时间函数
function getFormatTime($time) {
    return [
        'date' => substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2),
        'time' => strlen($time) >= 12 ? substr($time, 8, 2) . ':' . substr($time, 10, 2) : ''
    ];
}

// 下载数据并存入数据库
function downloadData($xml_url, $db, &$log_messages, $gen_list) {
    $json_file = 'arecord.json';
    $hashes = loadHashesFromJson($json_file);

    $xml_data = downloadXmlData($xml_url);
    if ($xml_data !== false && stripos($xml_data, 'not found') === false) {
        logMessage($log_messages, "【下载】 成功");

        $new_hash = hash('sha256', $xml_data);
        if (isset($hashes[$xml_url]) && $hashes[$xml_url] === $new_hash) {
            logMessage($log_messages, "【跳过】 文件未改变");
            return;
        }

        if (strtoupper(substr($xml_url, -3)) === '.GZ') {
            $xml_data = gzdecode($xml_data);
            if ($xml_data === false) {
                logMessage($log_messages, ' 【解压缩失败！！！】');
                return;
            }
        }
        $db->beginTransaction();
        try {
            processXmlData($xml_data, date('Y-m-d'), $db, $gen_list);
            $db->commit();
            logMessage($log_messages, "【更新】 成功");

            $hashes[$xml_url] = $new_hash; // 更新哈希值
            saveHashesToJson($json_file, $hashes); // 保存到JSON文件
        } catch (Exception $e) {
            $db->rollBack();
            logMessage($log_messages, "【处理数据出错！！！】 " . $e->getMessage());
        }
    } else {
        logMessage($log_messages, "【下载】 失败！！！");
    }
}

function loadHashesFromJson($json_file) {
    if (file_exists($json_file)) {
        $json_data = file_get_contents($json_file);
        return json_decode($json_data, true);
    }
    return [];
}

function saveHashesToJson($json_file, $hashes) {
    $json_data = json_encode($hashes, JSON_PRETTY_PRINT);
    file_put_contents($json_file, $json_data);
}

function downloadXmlData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        return false;
    }
    curl_close($ch);
    return $data;
}

// 获取限定节目列表
function getGenList($db) {
    $channels = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($channels)) {
        return [];
    }
    $channelsString = implode("\n", $channels);
    $channelsSimplified = t2s($channelsString);
    return array_unique(array_map('cleanChannelName', explode("\n", $channelsSimplified)));
}

// 从 epg_data 表生成 XML 数据
function generateXmlFromEpgData($db, $include_future_only) {
    $currentDate = date('Y-m-d'); // 获取当前日期
    $dateCondition = $include_future_only ? "WHERE date >= '$currentDate'" : '';

    // 合并查询
    $query = "SELECT date, channel, epg_diyp FROM epg_data $dateCondition ORDER BY channel ASC";
    $stmt = $db->query($query);

    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><tv info-name="by Tak" info-url="https://github.com/TakcC/PHP-EPG-Docker-Server"></tv>');
    
    $channels = [];    
    while ($program = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // 仅在需要时添加频道
        if (!in_array($program['channel'], $channels)) {
            $channels[] = $program['channel'];
            $channelElement = $xml->addChild('channel');
            $channelElement->addAttribute('id', htmlspecialchars($program['channel'], ENT_XML1, 'UTF-8'));
            $channelElement->addChild('display-name', htmlspecialchars($program['channel'], ENT_XML1, 'UTF-8'))->addAttribute('lang', 'zh');
        }
        
        $data = json_decode($program['epg_diyp'], true);
        foreach ($data['epg_data'] as $item) {
            $programme = $xml->addChild('programme');
            $programme->addAttribute('channel', htmlspecialchars($data['channel_name'], ENT_XML1, 'UTF-8'));
            $programme->addAttribute('start', formatTime($program['date'], $item['start']));
            $programme->addAttribute('stop', formatTime($program['date'], $item['end']));
            $programme->addChild('title', htmlspecialchars($item['title'], ENT_XML1, 'UTF-8'))->addAttribute('lang', 'zh');
            if (!empty($item['desc'])) {
                $programme->addChild('desc', htmlspecialchars($item['desc'], ENT_XML1, 'UTF-8'))->addAttribute('lang', 'zh');
            }
        }
    }

    // 使用 DOMDocument 格式化输出
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    return $dom->saveXML();
}

// 辅助函数：将日期和时间格式化为 XMLTV 格式
function formatTime($date, $time) {
    return date('YmdHis O', strtotime("$date $time"));
}

// 压缩 XML 内容
function compressXmlContent($xmlContent) {
    return gzencode($xmlContent);
}

// 处理 XML 数据并逐步存入数据库
function processXmlData($xml_data, $date, $db, $gen_list) {
    global $Config;
    $reader = new XMLReader();
    if (!$reader->XML($xml_data)) {
        throw new Exception("无法解析 XML 数据");
    }

    $oriChannelNames = [];
    $cleanChannelNames = [];

    // 读取频道数据
    while ($reader->read() && $reader->name !== 'channel');
    while ($reader->name === 'channel') {
        $channel = new SimpleXMLElement($reader->readOuterXML());
        $channelId = (string)$channel['id'];
        $cleanChannelNames[$channelId] = cleanChannelName((string)$channel->{'display-name'});
        $oriChannelNames[$channelId] = (string)$channel->{'display-name'};
        $reader->next('channel');
    }

    // 繁简转换和频道筛选
    $simplifiedChannelNames = explode("\n", t2s(implode("\n", $cleanChannelNames)));
    $channelNamesMap = [];
    foreach ($cleanChannelNames as $channelId => $channelName) {
        $channelNameSimplified = array_shift($simplifiedChannelNames);
        // 当 gen_list 为空时，插入所有数据
        if (empty($gen_list) || in_array($channelNameSimplified, $gen_list, true)) {
            $channel_name = !isset($Config['proc_chname']) || $Config['proc_chname'] ?
                            $channelNameSimplified : $oriChannelNames[$channelId];
            $channelNamesMap[$channelId] = $channel_name;
        }
    }

    $reader->close();
    $reader->XML($xml_data); // 重置 XMLReader
    while ($reader->read() && $reader->name !== 'programme');

    $currentChannelProgrammes = [];

    // 遍历节目并按批插入数据库
    while ($reader->name === 'programme') {
        $programme = new SimpleXMLElement($reader->readOuterXML());
        $start = getFormatTime((string)$programme['start']);
        $channelId = (string)$programme['channel'];

        if (isset($channelNamesMap[$channelId])) {
            $diypProgrammeArray = [
                'start' => $start['time'],
                'end' => getFormatTime((string)$programme['stop'])['time'],
                'title' => (string)$programme->title,
                'desc' => isset($programme->desc) ? (string)$programme->desc : ''
            ];

            // 将 programme 添加到对应的 channel 和日期
            $currentChannelProgrammes[$channelId]['channel_name'] = $channelNamesMap[$channelId];
            $currentChannelProgrammes[$channelId]['diyp_data'][$start['date']][] = $diypProgrammeArray;

            // 每次达到 50 条时，插入数据并保留最后一条
            if (count($currentChannelProgrammes) >= 50) {
                $lastProgramme = array_pop($currentChannelProgrammes); // 取出最后一条
                insertDataToDatabase($currentChannelProgrammes, $db); // 插入前 49 条
                $currentChannelProgrammes = [$channelId => $lastProgramme]; // 清空并重新赋值最后一条
            }
        }

        $reader->next('programme');
    }

    // 插入剩余的节目数据
    if (!empty($currentChannelProgrammes)) {
        insertDataToDatabase($currentChannelProgrammes, $db);
    }

    // 关闭 XMLReader
    $reader->close();
}

// 插入数据到数据库
function insertDataToDatabase($channelsData, $db) {
    foreach ($channelsData as $channelId => $channelData) {
        $channelName = $channelData['channel_name'];
        foreach ($channelData['diyp_data'] as $date => $diypProgrammes) {
            // 生成 epg_diyp 数据内容
            $diypContent = json_encode([
                'channel_name' => $channelName,
                'date' => $date,
                'url' => 'https://github.com/TakcC/PHP-EPG-Docker-Server',
                'epg_data' => $diypProgrammes
            ], JSON_UNESCAPED_UNICODE);

            // 当天及未来数据覆盖，其他日期数据忽略
            $action = $date >= date('Y-m-d') ? 'REPLACE' : 'IGNORE';
            $stmt = $db->prepare("INSERT OR $action INTO epg_data (date, channel, epg_diyp)
                                VALUES (:date, :channel, :epg_diyp)");
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':channel', $channelName, PDO::PARAM_STR);
            $stmt->bindValue(':epg_diyp', $diypContent, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}

// 记录开始时间
$startTime = microtime(true);

// 统计更新前数据条数
$initialCount = $db->query("SELECT COUNT(*) FROM epg_data")->fetchColumn();

// 删除过期数据
deleteOldData($db, $Config['days_to_keep'], $log_messages);

// 更新数据
$gen_list = getGenList($db); // 获取限定频道列表
foreach ($Config['xml_urls'] as $xml_url) {
    // 去掉空白字符，忽略空行和以 # 开头的 URL
    $xml_url = trim($xml_url);
    if (empty($xml_url) || strpos($xml_url, '#') === 0) {
        continue;
    }
    // 去除 URL 后的注释部分
    $url_parts = explode('#', $xml_url);
    $cleaned_url = trim($url_parts[0]);

    logMessage($log_messages, "【更新地址】 $cleaned_url");
    downloadData($cleaned_url, $db, $log_messages, $gen_list);
}

// 判断是否生成 xmltv 文件
if ($Config['gen_xml']) {
    $xml_content = generateXmlFromEpgData($db, $Config['include_future_only']);
    if ($Config['gen_xml'] == 1 || $Config['gen_xml'] == 3) {
        $gz_content = compressXmlContent($xml_content);
        file_put_contents('./t.xml.gz', $gz_content);
        logMessage($log_messages, "【t.xml.gz文件】 已生成");
    }
    if ($Config['gen_xml'] == 2 || $Config['gen_xml'] == 3) {
        file_put_contents('./t.xml', $xml_content);
        logMessage($log_messages, "【t.xml文件】 已生成");
    }
}

// 统计更新后数据条数
$finalCount = $db->query("SELECT COUNT(*) FROM epg_data")->fetchColumn();
$dif = $finalCount - $initialCount;
$msg = $dif > 0 ? " 增加 {$dif} 条。" : ($dif < 0 ? " 减少 " . abs($dif) . " 条。" : "");
// 记录结束时间
$endTime = microtime(true);
// 计算运行时间（以秒为单位）
$executionTime = round($endTime - $startTime, 1);
logMessage($log_messages, "【更新完成】 {$executionTime} 秒。 更新前：{$initialCount} 条，更新后：{$finalCount} 条。" . $msg);

// 将日志信息写入数据库
$log_message_str = implode("<br>", $log_messages);
$timestamp = date('Y-m-d H:i:s'); // 使用设定的时区时间
$stmt = $db->prepare('INSERT INTO update_log (timestamp, log_message) VALUES (:timestamp, :log_message)');
$stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
$stmt->bindValue(':log_message', $log_message_str, PDO::PARAM_STR);
$stmt->execute();

echo implode("<br>", $log_messages);

?>
