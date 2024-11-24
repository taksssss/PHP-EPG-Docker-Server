<?php
/**
 * @file public.php
 * @brief 公共脚本
 *
 * 该脚本包含公共设置、公共函数。
 *
 * 作者: Tak
 * GitHub: https://github.com/taksssss/EPG-Server
 */

require 'assets/opencc/vendor/autoload.php'; // 引入 Composer 自动加载器
use Overtrue\PHPOpenCC\OpenCC; // 使用 OpenCC 库

// 检查并解析配置文件和图标列表文件
@mkdir(__DIR__ . '/data', 0755, true);
$iconDir = __DIR__ . '/data/icon/'; @mkdir($iconDir, 0755, true);
$liveDir = __DIR__ . '/data/live/'; @mkdir($liveDir, 0755, true);
$liveFileDir = __DIR__ . '/data/live/file/'; @mkdir($liveFileDir, 0755, true);
file_exists($config_path = __DIR__ . '/data/config.json') || copy(__DIR__ . '/assets/defaultConfig.json', $config_path);
file_exists($iconList_path = __DIR__ . '/data/iconList.json') || file_put_contents($iconList_path, json_encode(new stdClass(), JSON_PRETTY_PRINT));
($iconList = json_decode(file_get_contents($iconList_path), true)) !== null || die("图标列表文件解析失败: " . json_last_error_msg());
$iconListDefault = json_decode(file_get_contents(__DIR__ . '/assets/defaultIconList.json'), true) or die("默认图标列表文件解析失败: " . json_last_error_msg());
$iconListMerged = array_merge($iconListDefault, $iconList); // 同一个键，以 iconList 的为准
$Config = json_decode(file_get_contents($config_path), true) or die("配置文件解析失败: " . json_last_error_msg());

// 获取 serverUrl
$protocol = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? (($_SERVER['HTTPS'] ?? '') === 'on' ? 'https' : 'http'));
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['HTTP_X_ORIGINAL_URI'] ?? $_SERVER['REQUEST_URI']), '/');
$serverUrl = $protocol . '://' . $host . $uri;

// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

// 创建或打开数据库
try {
    // 检测数据库类型
    $is_sqlite = $Config['db_type'] === 'sqlite';

    $dsn = $is_sqlite ? 'sqlite:' . __DIR__ . '/data/data.db'
        : "mysql:host={$Config['mysql']['host']};dbname={$Config['mysql']['dbname']};charset=utf8mb4";

    $db = new PDO($dsn, $Config['mysql']['username'] ?? null, $Config['mysql']['password'] ?? null);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '数据库连接失败: ' . $e->getMessage();
    if (!$is_sqlite) {
        // 如果是 MySQL 连接失败，则修改配置为 SQLite 并提示用户
        $Config['db_type'] = 'sqlite';
        file_put_contents($config_path, json_encode($Config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        echo '<p>MySQL 配置错误，已修改为 SQLite。<br>5 秒后自动刷新...</p>';
        echo '<meta http-equiv="refresh" content="5">';
    }
    exit();
}

// 初始化数据库表
function initialDB() {
    global $db;
    global $is_sqlite;
    $tables = [
        "CREATE TABLE IF NOT EXISTS epg_data (
            date " . ($is_sqlite ? 'TEXT' : 'VARCHAR(255)') . " NOT NULL,
            channel " . ($is_sqlite ? 'TEXT' : 'VARCHAR(255)') . " NOT NULL,
            epg_diyp TEXT,
            PRIMARY KEY (date, channel)
        )",
        "CREATE TABLE IF NOT EXISTS gen_list (
            id " . ($is_sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT PRIMARY KEY AUTO_INCREMENT') . ",
            channel " . ($is_sqlite ? 'TEXT' : 'VARCHAR(255)') . " NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS update_log (
            id " . ($is_sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT PRIMARY KEY AUTO_INCREMENT') . ",
            timestamp " . ($is_sqlite ? 'DATETIME DEFAULT CURRENT_TIMESTAMP' : 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP') . ",
            log_message TEXT NOT NULL
        )",
        "CREATE TABLE IF NOT EXISTS cron_log (
            id " . ($is_sqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT PRIMARY KEY AUTO_INCREMENT') . ",
            timestamp " . ($is_sqlite ? 'DATETIME DEFAULT CURRENT_TIMESTAMP' : 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP') . ",
            log_message TEXT NOT NULL
        )"
    ];

    foreach ($tables as $table) {
        $db->exec($table);
    }
}

// 获取处理后的频道名：$t2s参数表示繁简转换，默认false
function cleanChannelName($channel, $t2s = false) {
    global $Config;
    $channel_ori = $channel;
    // 默认忽略 - 跟 空格
    $channel_replacements = ['-', ' '];
    $channel = str_replace($channel_replacements, '', $channel);
    // 频道映射，优先级最高，支持正则表达式和多对一映射
    foreach ($Config['channel_mappings'] as $replace => $search) {
        if (strpos($search, 'regex:') === 0) {
            $pattern = substr($search, 6);
            if (preg_match($pattern, $channel_ori)) {
                return preg_replace($pattern, $replace, $channel_ori);
            }
        } else {
            // 普通映射，可能为多对一
            $channels = array_map('trim', explode(',', $search));
            foreach ($channels as $singleChannel) {
                if (strcasecmp($channel, str_replace($channel_replacements, '', $singleChannel)) === 0) {
                    return $replace;
    }}}}
    // 默认不进行繁简转换
    if ($t2s) {
        $channel = t2s($channel);
    }
    return strtoupper($channel);
}

// 繁体转简体
function t2s($channel) {
    return OpenCC::convert($channel, 'TRADITIONAL_TO_SIMPLIFIED');
}

// 台标模糊匹配
function iconUrlMatch($originalChannel) {
    global $iconListMerged;

    $iconUrl = null;
    // 精确匹配
    if (isset($iconListMerged[$originalChannel])) {
        $iconUrl = $iconListMerged[$originalChannel];
    } else {
        $bestMatch = null;
        // 正向模糊匹配
        foreach ($iconListMerged as $channelName => $icon) {
            if (stripos($channelName, $originalChannel) !== false) {
                if ($bestMatch === null || strlen($channelName) < strlen($bestMatch)) {
                    $bestMatch = $channelName;
                    $iconUrl = $icon;
        }}}
        if(!$iconUrl) {
        // 反向模糊匹配
            foreach ($iconListMerged as $channelName => $icon) {
                if (stripos($originalChannel, $channelName) !== false) {
                    if ($bestMatch === null || strlen($channelName) > strlen($bestMatch)) {
                        $bestMatch = $channelName;
                        $iconUrl = $icon;
    }}}}}
    return $iconUrl;
}

// 下载文件
function downloadData($url, $timeout = 30, $connectTimeout = 10, $retry = 3) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => $connectTimeout,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
            'Accept: */*',
            'Connection: keep-alive'
        ]
    ]);
    while ($retry--) {
        $data = curl_exec($ch);
        if (!curl_errno($ch)) break;
    }
    curl_close($ch);
    return $data ?: false;
}

// 日志记录函数
function logMessage(&$log_messages, $message) {
    $log_messages[] = date("[y-m-d H:i:s]") . " " . $message;
}

// 下载 JSON 数据并存入数据库
function downloadJSONData($json_url, $db, &$log_messages, $channel_name, $replaceFlag = true) {
    $json_data = downloadData($json_url);
    $json_data = mb_convert_encoding($json_data, 'UTF-8', 'GBK');
    if (!empty(json_decode($json_data, true)['data'])) {
        $db->beginTransaction();
        try {
            processJsonData($json_data, $db, $channel_name, $replaceFlag);
            $db->commit();
            logMessage($log_messages, "【tvmao】 $channel_name 更新成功");
        } catch (Exception $e) {
            $db->rollBack();
            logMessage($log_messages, "【tvmao】 " . $e->getMessage());
        }
    } else {
        logMessage($log_messages, "【tvmao】 $channel_name 下载失败！！！");
    }
}

// 处理 JSON 数据并逐步存入数据库
function processJsonData($json_data, $db, $channel_name, $replaceFlag) {
    $data = json_decode($json_data, true);
    $data = $data['data'][0]['data'];
    $channelProgrammes = [];
    // 处理 tvmao 数据格式
    $channelId = $channel_name;
    $dt = new DateTime();
    $skipTime = null;
    foreach ($data as $epg) {
        if ($time_str = $epg['times'] ?? '') {
            $starttime = DateTime::createFromFormat('Y/m/d H:i', $time_str);
            $date = $starttime->format('Y-m-d');
            // 如果第一条数据早于今天 01:00，则认为今天的数据是齐全的
            if (is_null($skipTime)) {
                $skipTime = $starttime < new DateTime("today 01:00") ? 
                            new DateTime("today 00:00") : new DateTime("tomorrow 00:00");
            }
            if ($starttime < $skipTime) continue;
            $channelProgrammes[$channelId]['diyp_data'][$date][] = [
                'start' => $starttime->format('H:i'),
                'end' => '',  // 初始为空
                'title' => trim($epg['title']),
                'desc' => ''  // 没有明确描述字段
            ];
        }
    }
    // 填充 'end' 字段
    foreach ($channelProgrammes[$channelId]['diyp_data'] as $date => &$programmes) {
        foreach ($programmes as $i => &$programme) {
            $nextStart = $programmes[$i + 1]['start'] ?? '00:00';  // 下一个节目开始时间或 00:00
            $programme['end'] = $nextStart;  // 填充下一个节目的 'start'
            if ($nextStart === '00:00') {
                // 尝试获取第二天数据并补充
                $nextDate = (new DateTime($date))->modify('+1 day')->format('Y-m-d');
                $nextDayProgrammes = $channelProgrammes[$channelId]['diyp_data'][$nextDate] ?? [];
                if (!empty($nextDayProgrammes)) {
                    if ($nextDayProgrammes[0]['start'] !== '00:00') {
                        array_unshift($channelProgrammes[$channelId]['diyp_data'][$nextDate], [
                            'start' => '00:00',
                            'end' => '',
                            'title' => $programme['title'],
                            'desc' => $programme['desc']
                        ]);
    }}}}}
    $channelProgrammes[$channelId]['channel_name'] = $channel_name;
    insertDataToDatabase($channelProgrammes, $db, 'tvmao', $replaceFlag);
}

// 插入数据到数据库
function insertDataToDatabase($channelsData, $db, $sourceUrl, $replaceFlag = true) {
    global $processedRecords;
    global $Config;

    foreach ($channelsData as $channelId => $channelData) {
        $channelName = $channelData['channel_name'];
        foreach ($channelData['diyp_data'] as $date => $diypProgrammes) {
            // 检查是否全天只有一个节目
            if (count($title = array_unique(array_column($diypProgrammes, 'title'))) === 1 
                && preg_match('/节目|節目/u', $title[0])) {
                echo basename($sourceUrl) . "，{$channelName}，{$date}：全天仅《{$title[0]}》，已跳过。<br>";
                continue; // 跳过后续处理
            }
            // 生成 epg_diyp 数据内容
            $diypContent = json_encode([
                'channel_name' => $channelName,
                'date' => $date,
                'url' => 'https://github.com/taksssss/EPG-Server',
                'source' => $sourceUrl,
                'epg_data' => $diypProgrammes
            ], JSON_UNESCAPED_UNICODE);
            // 当天及未来数据覆盖，其他日期数据忽略
            $action = $date >= date('Y-m-d') && $replaceFlag ? 'REPLACE' : 'IGNORE';
            // 检测数据库类型
            $is_sqlite = $Config['db_type'] === 'sqlite';
            // 选择 SQL 语句
            $sql = $is_sqlite
                ? "INSERT OR $action INTO epg_data (date, channel, epg_diyp) VALUES (:date, :channel, :epg_diyp)"
                : ($date >= date('Y-m-d')
                    ? "REPLACE INTO epg_data (date, channel, epg_diyp) VALUES (:date, :channel, :epg_diyp)"
                    : "INSERT IGNORE INTO epg_data (date, channel, epg_diyp) VALUES (:date, :channel, :epg_diyp)"
                );
            // 准备并执行 SQL 语句
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
            $stmt->bindValue(':channel', $channelName, PDO::PARAM_STR);
            $stmt->bindValue(':epg_diyp', $diypContent, PDO::PARAM_STR);
            $stmt->execute();
            if ($action == 'REPLACE' || $stmt->rowCount() > 0){
                $recordKey = $channelName . '-' . $date;
                $processedRecords[$recordKey] = true;
            }
        }
    }
}

// 解析 txt、m3u 直播源，并生成直播列表（包含分组、地址等信息）
function do_parse_source_info() {
    global $liveDir, $liveFileDir;

    // 频道数据模糊匹配函数
    function dbChNameMatch($channelName) {
        global $db;
        $concat = $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql' ? "CONCAT('%', channel, '%')" : "'%' || channel || '%'";
        $stmt = $db->prepare("
            SELECT channel FROM epg_data WHERE date = :date
            AND (channel = :channel OR channel LIKE :like_channel OR :channel LIKE $concat)
            ORDER BY CASE WHEN channel = :channel THEN 1 WHEN channel LIKE :like_channel THEN 2 ELSE 3 END, LENGTH(channel) DESC
            LIMIT 1
        ");
        $stmt->execute([':date' => date('Y-m-d'), ':channel' => $channelName, ':like_channel' => $channelName . '%']);
        return $stmt->fetchColumn();
    }

    // 读取 source.txt 内容，处理每行 URL
    $sourceContent = file_get_contents($liveDir . 'source.txt');
    $lines = array_filter(array_map('ltrim', explode("\n", $sourceContent)));

    // 写入表头
    $csvFilePath = $liveDir . 'channels.csv';
    $csvFile = fopen($csvFilePath, 'w');
    fputcsv($csvFile, ['频道分组', '频道名', '直播地址', '台标地址', 'tvg-id', 'tvg-name']);
    $errorLog = '';

    foreach ($lines as $line) {
        if (empty($line) || $line[0] === '#') continue;
    
        // 解析 URL 和分组前缀
        list($url, $groupPrefix) = explode('#', $line) + [1 => ''];
        $url = trim($url);
    
        // 获取 URL 内容
        $urlContent = (stripos($url, '/data/live/file/') !== false) 
            ? @file_get_contents(__DIR__ . $url) 
            : downloadData($url, 5);
        $fileName = md5(urlencode($url)) . '.txt';  // 用 MD5 对 URL 进行命名
        $localFilePath = $liveFileDir . '/' . $fileName;
        
        if (!$urlContent || stripos($urlContent, 'not found') !== false) {
            $urlContent = file_exists($localFilePath) ? file_get_contents($localFilePath) : '';
            if (!$urlContent) { $errorLog .= "$url<br>"; continue; }
        } else if (stripos($url, '/data/live/file/') === false) { // 检测是否上传的文件
            file_put_contents($localFilePath, $urlContent);
        }
        
        $lines = explode("\n", $urlContent);
    
        // 处理 M3U 格式的直播源
        if (strpos($urlContent, '#EXTM3U') !== false) {
            foreach ($lines as $i => $line) {
                $line = trim($line);
    
                // 跳过空行和 M3U 头部
                if (empty($line) || strpos($line, '#EXTM3U') === 0) continue;
    
                if (strpos($line, '#EXTINF') === 0 && isset($lines[$i + 1])) {
                    // 处理 `#EXTINF` 行，提取频道信息
                    if (preg_match('/#EXTINF:-1(.*),(.+)/', $line, $matches)) {
                        $channelInfo = $matches[1];
                        $channelName = trim($matches[2]);
                        $streamUrl = trim($lines[$i + 1]);
    
                        // 将频道信息组织成数组
                        $rowData = [
                            'groupTitle' => preg_match('/group-title="([^"]+)"/', $channelInfo, $match) ? trim($groupPrefix . $match[1]) : '',
                            'channelName' => $channelName,
                            'streamUrl' => $streamUrl,
                            'iconUrl' => iconUrlMatch(cleanChannelName($channelName)) ?? (preg_match('/tvg-logo="([^"]+)"/', $channelInfo, $match) ? $match[1] : ''),
                            'tvgId' => preg_match('/tvg-id="([^"]+)"/', $channelInfo, $match) ? $match[1] : '',
                            'tvgName' => preg_match('/tvg-name="([^"]+)"/', $channelInfo, $match) ? $match[1] : '',
                        ];
    
                        // 写入 CSV 文件
                        fputcsv($csvFile, $rowData);
                    }
                }
            }
        } else {
            // 处理 TXT 格式的直播源
            $groupTitlePart = '';
            foreach ($lines as $line) {
                $line = trim($line);
                $parts = explode(',', $line);
            
                if (count($parts) == 2) {
                    if ($parts[1] === '#genre#') {
                        $groupTitlePart = trim($parts[0]); // 更新 group-title
                        continue;
                    }
            
                    $groupTitle = trim($groupPrefix . $groupTitlePart);
                    $originalChannelName = trim($parts[0]);
                    $dbChNameMatch = dbChNameMatch(cleanChannelName($originalChannelName));
                    $channelName = $dbChNameMatch ?: $originalChannelName;
                    $iconUrl = iconUrlMatch($channelName);
                    $tvgName = $dbChNameMatch ? $channelName : "";
            
                    fputcsv($csvFile, [$groupTitle, $originalChannelName, $parts[1], $iconUrl, "", $tvgName]);
                }
            }   
        }
    }
    
    fclose($csvFile);

    // 执行文件生成
    generateLiveFiles($csvFilePath, $liveDir);

    return $errorLog;
}

// 生成 M3U 和 TXT 文件
function generateLiveFiles($csvFilePath, $liveDir) {
    $m3uContent = "#EXTM3U x-tvg-url=\"\"\n";
    $groups = [];
    $csvFile = fopen($csvFilePath, 'r');
    fgetcsv($csvFile);  // 跳过表头

    while ($row = fgetcsv($csvFile)) {
        list($groupTitle, $channelName, $streamUrl, $iconUrl, $tvgId, $tvgName) = $row;
        $extInfLine = "#EXTINF:-1" .
            ($tvgId ? " tvg-id=\"$tvgId\"" : "") .
            ($tvgName ? " tvg-name=\"$tvgName\"" : "") .
            ($iconUrl ? " tvg-logo=\"$iconUrl\"" : "") .
            ($groupTitle ? " group-title=\"$groupTitle\"" : "") .
            ",$channelName";
        
        $m3uContent .= $extInfLine . "\n" . $streamUrl . "\n";
        $groups[$groupTitle ?: "未分组"][] = "$channelName,$streamUrl";
    }
    fclose($csvFile);

    file_put_contents($liveDir . 'tv.m3u', $m3uContent);
    $txtContent = "";
    foreach ($groups as $group => $channels) {
        $txtContent .= "$group,#genre#\n" . implode("\n", $channels) . "\n\n";
    }
    file_put_contents($liveDir . 'tv.txt', trim($txtContent));
}
?>