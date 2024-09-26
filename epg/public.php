<?php
/**
 * @file public.php
 * @brief 公共脚本
 * 
 * 该脚本包含公共设置、公共函数。
 * 
 * 作者: Tak
 * GitHub: https://github.com/taksssss/PHP-EPG-Docker-Server
 */

require 'opencc/vendor/autoload.php'; // 引入 Composer 自动加载器
use Overtrue\PHPOpenCC\OpenCC; // 使用 OpenCC 库

// 检查并解析配置文件和图标列表文件
@mkdir(__DIR__ . '/data', 0755, true);
$iconDir = __DIR__ . '/data/icon/'; @mkdir($iconDir, 0755, true);
file_exists($config_path = __DIR__ . '/data/config.json') || copy(__DIR__ . '/config_default.json', $config_path);
file_exists($iconList_path = __DIR__ . '/data/iconList.json') || file_put_contents($iconList_path, json_encode(new stdClass(), JSON_PRETTY_PRINT));
$Config = json_decode(file_get_contents($config_path), true) or die("配置文件解析失败: " . json_last_error_msg());
($iconList = json_decode(file_get_contents($iconList_path), true)) !== null || die("图标列表文件解析失败: " . json_last_error_msg());
$iconListDefault = json_decode(file_get_contents(__DIR__ . '/iconList_default.json'), true) or die("默认图标列表文件解析失败: " . json_last_error_msg());
$iconListMerged = array_merge($iconListDefault, $iconList); // 同一个键，以 iconList 的为准

$serverUrl = (($_SERVER['HTTPS'] ?? '') === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

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
        file_put_contents($config_path, json_encode($Config, JSON_PRETTY_PRINT));
        
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
?>