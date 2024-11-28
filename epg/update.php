<?php
/**
 * @file update.php
 * @brief 更新脚本
 * 
 * 该脚本用于定期从配置的 XML 源下载节目数据，并将其存入 SQLite 数据库中。
 * 
 * 作者: Tak
 * GitHub: https://github.com/taksssss/EPG-Server
 */

// 显示 favicon
echo '<link rel="icon" href="assets/html/favicon.ico" type="image/x-icon">';

// 引入公共脚本
require_once 'public.php';

// 设置超时时间为20分钟
set_time_limit(20*60);

// 删除过期数据和日志
function deleteOldData($db, $keep_days, &$log_messages) {
    global $Config;

    // 删除 t.xml 和 t.xml.gz 文件
    if (!$Config['gen_xml']) {
        @unlink('./t.xml');
        @unlink('./t.xml.gz');
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

// 格式化时间函数，同时转化为 UTC+8 时间
function getFormatTime($time, $overwrite_time_zone) {
    $time = $overwrite_time_zone ? substr($time, 0, -5) . $overwrite_time_zone : $time;
    $time = str_replace(' ', '', $time);
    $date = DateTime::createFromFormat('YmdHisO', $time)->setTimezone(new DateTimeZone('+0800'));
    return [
        'date' => $date->format('Y-m-d'),
        'time' => $date->format('H:i')
    ];
}

// 辅助函数：将日期和时间格式化为 XMLTV 格式
function formatTime($date, $time) {
    return date('YmdHis O', strtotime("$date $time"));
}

// 获取限定频道列表及映射关系
function getGenList($db) {
    global $Config;
    $channels = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($channels)) {
        return ['gen_list_mapping' => [], 'gen_list' => []];
    }

    $channelsSimplified = explode("\n", t2s(implode("\n", $channels)));
    $allEpgChannels = $db->query("SELECT DISTINCT channel FROM epg_data WHERE date = DATE('now')")->fetchAll(PDO::FETCH_COLUMN); // 避免匹配只有历史 EPG 的频道

    $gen_list_mapping = [];
    $cleanedChannels = array_map('cleanChannelName', $channelsSimplified);

    foreach ($cleanedChannels as $index => $cleanedChannel) {
        $bestMatch = $cleanedChannel;  // 默认使用清理后的频道名
        $bestMatchLength = 0;  // 初始为0，表示未找到任何匹配

        foreach ($allEpgChannels as $epgChannel) {
            if (strcasecmp($cleanedChannel, $epgChannel) === 0) {
                $bestMatch = $epgChannel;
                break;  // 精确匹配，立即跳出循环
            }

            // 模糊匹配并选择最长的频道名称
            if ((stripos($epgChannel, $cleanedChannel) === 0 || stripos($cleanedChannel, $epgChannel) !== false) 
                && strlen($epgChannel) > $bestMatchLength) {
                $bestMatch = $epgChannel;
                $bestMatchLength = strlen($epgChannel);  // 更新为更长的匹配
            }
        }

        // 将原始频道名称添加到映射数组中
        $gen_list_mapping[$bestMatch][] = $channels[$index];
    }

    return [
        'gen_list_mapping' => $gen_list_mapping,
        'gen_list' => array_unique($cleanedChannels)
    ];
}

// 获取频道指定 EPG 关系
function getChannelBindEPG() {
    global $Config;
    $channelBindEPG = [];
    foreach ($Config['channel_bind_epg'] ?? [] as $epg_src => $channels) {
        foreach (array_map('trim', explode(',', $channels)) as $channel) {
            $channelBindEPG[$channel][] = $epg_src;
        }
    }
    return $channelBindEPG;
}

// 下载 XML 数据并存入数据库
function downloadXmlData($xml_url, $db, &$log_messages, $gen_list) {
    global $Config;
    $xml_data = downloadData($xml_url);
    if ($xml_data !== false && stripos($xml_data, 'not found') === false) {
        logMessage($log_messages, "【下载】 成功");
        if (substr($xml_data, 0, 2) === "\x1F\x8B") { // 通过魔数判断 .gz 文件
            $xml_data = gzdecode($xml_data);
            if ($xml_data === false) {
                logMessage($log_messages, ' 【解压缩失败！！！】');
                return;
            }
        }
        $xml_data = preg_replace('/[\x00-\x1F]/u', ' ', $xml_data); // 清除所有控制字符
        if (isset($Config['all_chs']) && $Config['all_chs']) { $xml_data = t2s($xml_data); }
        $db->beginTransaction();
        try {
            processXmlData($xml_url, $xml_data, $db, $gen_list);
            $db->commit();
            logMessage($log_messages, "【更新】 成功");
        } catch (Exception $e) {
            $db->rollBack();
            logMessage($log_messages, "【处理数据出错！！！】 " . $e->getMessage());
        }
    } else {
        logMessage($log_messages, "【下载】 失败！！！");
    }
}

// 处理 XML 数据并逐步存入数据库
function processXmlData($xml_url, $xml_data, $db, $gen_list) {
    global $Config;
    global $processedRecords;
    global $channel_bind_epg;

    $reader = new XMLReader();
    if (!$reader->XML($xml_data)) {
        throw new Exception("无法解析 XML 数据");
    }

    $cleanChannelNames = [];

    // 读取频道数据
    while ($reader->read() && $reader->name !== 'channel');
    while ($reader->name === 'channel') {
        $channel = new SimpleXMLElement($reader->readOuterXML());
        $channelId = (string)$channel['id'];
        $cleanChannelNames[$channelId] = cleanChannelName((string)$channel->{'display-name'});
        $reader->next('channel');
    }

    // 繁简转换和频道筛选
    $simplifiedChannelNames = (isset($Config['all_chs']) && $Config['all_chs']) ? $cleanChannelNames : explode("\n", t2s(implode("\n", $cleanChannelNames)));
    $channelNamesMap = [];
    foreach ($cleanChannelNames as $channelId => $channelName) {
        $channelNameSimplified = array_shift($simplifiedChannelNames);

        // 假如 channel_bind_epg 存在且频道在其中有记录，且不为当前 xml_url，直接跳过
        if (!empty($channel_bind_epg) && 
            isset($channel_bind_epg[$channelNameSimplified]) && 
            !in_array($xml_url, $channel_bind_epg[$channelNameSimplified])
        ) {
            continue; // 跳过当前循环，继续处理下一个
        }

        // 当 gen_list_enable 为 0 时，插入所有数据
        if (empty($Config['gen_list_enable'])) {
            $channelNamesMap[$channelId] = $channelNameSimplified;
            continue;
        }
        $matchFound = false;
        foreach ($gen_list as $item) {
            if (stripos($channelNameSimplified, $item) !== false || stripos($item, $channelNameSimplified) !== false) {
                $matchFound = true;
                break;
            }
        }
        if ($matchFound) {
            $channelNamesMap[$channelId] = $channelNameSimplified;
        }
    }

    $reader->close();
    $reader->XML($xml_data); // 重置 XMLReader
    while ($reader->read() && $reader->name !== 'programme');

    $currentChannelProgrammes = [];
    $crossDayProgrammes = []; // 保存跨天的节目数据
    
    // 修正 epg.pw 时区错误
    $overwrite_time_zone = strpos($xml_data, 'epg.pw') !== false ? '+0800' : '';

    while ($reader->name === 'programme') {
        $programme = new SimpleXMLElement($reader->readOuterXML());
        $start = getFormatTime((string)$programme['start'], $overwrite_time_zone);
        $end = getFormatTime((string)$programme['stop'], $overwrite_time_zone);
        $channelId = (string)$programme['channel'];
        $channelName = $channelNamesMap[$channelId] ?? null;
        $recordKey = $channelName . '-' . $start['date'];

        // 优先处理跨天数据
        if (isset($crossDayProgrammes[$channelId][$start['date']]) && !isset($processedRecords[$recordKey])) {
            $currentChannelProgrammes[$channelId]['diyp_data'][$start['date']] = array_merge(
                $currentChannelProgrammes[$channelId]['diyp_data'][$start['date']] ?? [],
                $crossDayProgrammes[$channelId][$start['date']]
            );
            $currentChannelProgrammes[$channelId]['channel_name'] = $channelName;
            unset($crossDayProgrammes[$channelId][$start['date']]);
        }
    
        if ($channelName && !isset($processedRecords[$recordKey])) {
            $programmeData = [
                'start' => $start['time'],
                'end' => $start['date'] === $end['date'] ? $end['time'] : '00:00',
                'title' => (string)$programme->title,
                'desc' => isset($programme->desc) && (string)$programme->desc !== (string)$programme->title ? (string)$programme->desc : ''
            ];
    
            $currentChannelProgrammes[$channelId]['diyp_data'][$start['date']][] = $programmeData;
    
            // 保存跨天的节目数据
            if ($start['date'] !== $end['date'] && $end['time'] !== '00:00') {
                $crossDayProgrammes[$channelId][$end['date']][] = [
                    'start' => '00:00',
                    'end' => $end['time'],
                    'title' => $programmeData['title'],
                    'desc' => $programmeData['desc']
                ];
            }
    
            $currentChannelProgrammes[$channelId]['channel_name'] = $channelName;
    
            // 每次达到 50 时，插入数据并保留最后一条
            if (count($currentChannelProgrammes) >= 50) {
                $lastProgramme = array_pop($currentChannelProgrammes); // 取出最后一条
                insertDataToDatabase($currentChannelProgrammes, $db, $xml_url); // 插入前 49 条
                $currentChannelProgrammes = [$channelId => $lastProgramme]; // 清空并重新赋值最后一条
            }
        }
    
        $reader->next('programme');
    }
    
    // 插入剩余的数据
    if ($currentChannelProgrammes) {
        insertDataToDatabase($currentChannelProgrammes, $db, $xml_url);
    }
    
    $reader->close();
}

// 从 epg_data 表生成 XML 数据并逐个频道写入 t.xml 文件
function generateXmlFromEpgData($db, $include_future_only, $gen_list_mapping, &$log_messages) {
    global $Config, $iconList, $iconList_path;

    $currentDate = date('Y-m-d'); // 获取当前日期
    $dateCondition = $include_future_only ? "WHERE date >= '$currentDate'" : '';

    // 合并查询
    $query = "SELECT date, channel, epg_diyp FROM epg_data $dateCondition ORDER BY channel ASC, date ASC";
    $stmt = $db->query($query);

    // 创建 XMLWriter 实例
    $xmlWriter = new XMLWriter();
    $xmlWriter->openUri('t.xml');
    $xmlWriter->startDocument('1.0', 'UTF-8');
    $xmlWriter->startElement('tv');
    $xmlWriter->writeAttribute('generator-info-name', 'Tak');
    $xmlWriter->writeAttribute('generator-info-url', 'https://github.com/taksssss/EPG-Server');
    $xmlWriter->setIndent(true);
    $xmlWriter->setIndentString('	'); // 设置缩进

    // 存储节目数据以按频道分组
    $channelData = [];

    while ($program = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $originalChannel = $program['channel'];

        // 确定要处理的频道列表
        $channelsToProcess = $Config['gen_list_enable']
            ? ($gen_list_mapping[$originalChannel] ?? [])
            : array_unique(array_merge([$originalChannel], $gen_list_mapping[$originalChannel] ?? []));

        // 如果不需要生成列表或映射为空或存在映射，则处理频道数据
        if (empty($Config['gen_list_enable']) || empty($gen_list_mapping) || isset($gen_list_mapping[$originalChannel])) {
            $channelData[$originalChannel][] = $program;
        }
    }

    // 将 $Config['channel_mappings'] 中的映射值转换为数组
    $channelMappings = array_map(function($mapped) {
        return strpos($mapped, 'regex:') === 0 ? [$mapped] : array_map('trim', explode(',', $mapped));
    }, $Config['channel_mappings']);

    // 逐个频道处理
    foreach ($channelData as $originalChannel => $programs) {
        // 写入频道信息
        $xmlWriter->startElement('channel');
        $xmlWriter->writeAttribute('id', htmlspecialchars($originalChannel, ENT_XML1, 'UTF-8'));

        // 为该频道生成多个 display-name ，包括原频道名、限定频道列表、频道别名
        $displayNames = array_unique(array_merge(
            [$originalChannel],
            $gen_list_mapping[$originalChannel] ?? [],
            $channelMappings[$originalChannel] ?? []
        ));
        foreach ($displayNames as $displayName) {
            $xmlWriter->startElement('display-name');
            $xmlWriter->writeAttribute('lang', 'zh');
            $xmlWriter->text(htmlspecialchars($displayName, ENT_XML1, 'UTF-8'));
            $xmlWriter->endElement(); // display-name
        }

        $iconUrl = iconUrlMatch($originalChannel);

        if ($iconUrl) {
            $iconList[strtoupper($originalChannel)] = $iconUrl;
        }
        
        $xmlWriter->startElement('icon');
        $xmlWriter->writeAttribute('src', $iconUrl);
        $xmlWriter->endElement(); // icon

        $xmlWriter->endElement(); // channel

        // 写入该频道的所有节目数据
        foreach ($programs as $programIndex => &$program) {
            $data = json_decode($program['epg_diyp'], true);
            $dataCount = count($data['epg_data']);
            $end_date = $program['date'];
        
            for ($index = 0; $index < $dataCount; $index++) {
                $item = $data['epg_data'][$index];
                $end_time = $item['end'];
        
                // 如果结束时间为 00:00，切换到第二天的日期
                if ($end_time == '00:00') {
                    $end_date = date('Ymd', strtotime($end_date . ' +1 day'));  // 切换日期
        
                    // 合并下一个节目
                    if (isset($programs[$programIndex + 1])) {
                        $nextData = json_decode($programs[$programIndex + 1]['epg_diyp'], true);
                        $nextItem = $nextData['epg_data'][0] ?? null;
    
                        if ($nextItem && $nextItem['title'] == $item['title']) {
                            $end_time = $nextItem['end'];
                            array_splice($nextData['epg_data'], 0, 1); // 删除下一个节目的第一个项目
                            $programs[$programIndex + 1]['epg_diyp'] = json_encode($nextData);
                        }
                    }
                }
        
                // 写入当前节目
                $xmlWriter->startElement('programme');
                $xmlWriter->writeAttribute('channel', htmlspecialchars($originalChannel, ENT_XML1, 'UTF-8'));
                $xmlWriter->writeAttribute('start', formatTime($program['date'], $item['start']));
                $xmlWriter->writeAttribute('stop', formatTime($end_date, $end_time));
                $xmlWriter->startElement('title');
                $xmlWriter->writeAttribute('lang', 'zh');
                $xmlWriter->text(htmlspecialchars($item['title'], ENT_XML1, 'UTF-8'));
                $xmlWriter->endElement(); // title
                if (!empty($item['desc'])) {
                    $xmlWriter->startElement('desc');
                    $xmlWriter->writeAttribute('lang', 'zh');
                    $xmlWriter->text(htmlspecialchars($item['desc'], ENT_XML1, 'UTF-8'));
                    $xmlWriter->endElement(); // desc
                }
                $xmlWriter->endElement(); // programme
            }
        }
    }

    // 结束 XML 文档
    $xmlWriter->endElement(); // tv
    $xmlWriter->endDocument();
    $xmlWriter->flush();

    // 所有频道数据写入完成后，生成 t.xml.gz 文件
    compressXmlFile('t.xml');

    // 更新 iconList.json 文件中的数据
    if (file_put_contents($iconList_path, json_encode($iconList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) === false) {
        logMessage($log_messages, "【台标列表】 更新 iconList.json 时发生错误！！！");
    } else {
        logMessage($log_messages, "【台标列表】 已更新 iconList.json");
    }
}

// 生成 t.xml.gz 压缩文件
function compressXmlFile($filePath) {
    $gzFilePath = $filePath . '.gz';

    // 打开原文件和压缩文件
    $file = fopen($filePath, 'rb');
    $gzFile = gzopen($gzFilePath, 'wb9'); // 最高压缩等级

    // 将文件内容写入到压缩文件中
    while (!feof($file)) {
        gzwrite($gzFile, fread($file, 1024 * 512));
    }

    // 关闭文件
    fclose($file);
    gzclose($gzFile);
}

// 记录开始时间
$startTime = microtime(true);

// 统计更新前数据条数
$initialCount = $db->query("SELECT COUNT(*) FROM epg_data")->fetchColumn();

// 删除过期数据
deleteOldData($db, $Config['days_to_keep'], $log_messages);

// 获取限定频道列表及映射关系
$gen_res = getGenList($db);
$gen_list = $gen_res['gen_list'];
$gen_list_mapping = $gen_res['gen_list_mapping'];

// 获取频道指定 EPG 关系
$channel_bind_epg = getChannelBindEPG();

// 全局变量，用于记录已处理的记录
$processedRecords = [];

// 更新数据
foreach ($Config['xml_urls'] as $xml_url) {
    // 去掉空白字符，忽略空行和以 # 开头的 URL
    $xml_url = trim($xml_url);
    if (empty($xml_url) || strpos($xml_url, '#') === 0) {
        continue;
    } elseif (strpos($xml_url, 'tvmao') === 0) {
        // 更新 tvmao 数据
        $tvmaostr = str_replace('tvmao,', '', $xml_url);
        foreach (explode(',', $tvmaostr) as $tvmao_info) {
            list($channel_name, $channel_id) = array_map('trim', explode(':', trim($tvmao_info)) + [null, $tvmao_info]);
            $json_url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=" . $channel_id . "&resource_id=12520&format=json";
            downloadJSONData($json_url, $db, $log_messages, $channel_name);
        }
        continue;
    }

    // 更新 XML 数据
    $url_parts = explode('#', $xml_url);
    $cleaned_url = trim($url_parts[0]);

    logMessage($log_messages, "【更新地址】 $cleaned_url");
    downloadXmlData($cleaned_url, $db, $log_messages, $gen_list);
}

// 判断是否生成 xmltv 文件
if ($Config['gen_xml']) {
    generateXmlFromEpgData($db, $Config['include_future_only'], $gen_list_mapping, $log_messages);
    logMessage($log_messages, "【xmltv文件】 已生成 t.xml、t.xml.gz");
}

// 判断是否同步更新直播源
if (isset($Config['live_source_auto_sync']) && $Config['live_source_auto_sync'] == 1) {
    $errorLog = doParseSourceInfo();
    if ($errorLog) {
        logMessage($log_messages, "【直播源】 部分更新异常：" . rtrim(str_replace('<br>', '、', $errorLog), '、'));
    } else {
        logMessage($log_messages, "【直播源】 已同步更新");
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
