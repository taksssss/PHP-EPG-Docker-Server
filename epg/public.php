<?php
/**
 * @file public.php
 * @brief 公共脚本
 * 
 * 该脚本包含公共设置、公共函数。
 * 
 * 作者: Tak
 * GitHub: https://github.com/TakcC/PHP-EPG-Docker-Server
 */

require 'opencc/vendor/autoload.php'; // 引入 Composer 自动加载器
use Overtrue\PHPOpenCC\OpenCC; // 使用 OpenCC 库

// 引入并解析 JSON 配置文件，不存在则创建默认配置文件
$config_path = __DIR__ . '/data/config.json';
@mkdir(dirname($config_path), 0755, true);
file_exists($config_path) || copy(__DIR__ . '/config_default.json', $config_path);
$Config = json_decode(file_get_contents($config_path), true) 
    or die("配置文件解析失败: " . json_last_error_msg());

// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

// 创建或打开数据库
try {
    $db_file = __DIR__ . '/data/data.db';
    $dsn = 'sqlite:' . $db_file;
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 初始化数据库表
    $db->exec("CREATE TABLE IF NOT EXISTS epg_data (
        date TEXT NOT NULL,
        channel TEXT NOT NULL,
        epg_diyp TEXT,
        PRIMARY KEY (date, channel)
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS gen_list (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        channel TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS update_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        timestamp TEXT DEFAULT CURRENT_TIMESTAMP,
        log_message TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS cron_log (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        timestamp TEXT DEFAULT CURRENT_TIMESTAMP,
        log_message TEXT NOT NULL
    )");
} catch (PDOException $e) {
    echo '数据库连接失败: ' . $e->getMessage();
    exit();
}

// 获取处理后的频道名：$t2s参数表示繁简转换，默认false
function cleanChannelName($channel, $t2s = false) {
    global $Config;
    // 频道映射，优先级最高，匹配后直接返回，支持正则表达式映射，以 regex: 开头
    foreach ($Config['channel_mappings'] as $search => $replace) {
        if (strpos($search, 'regex:') === 0) {
            $pattern = substr($search, 6);
            if (preg_match($pattern, $channel)) {
                return preg_replace($pattern, $replace, $channel);
            }
        } else {
            // 检查是否为一对一映射或多对一映射，忽略所有空格和大小写
            $search = str_replace(' ', '', $search);
            $channelNoSpaces = str_replace(' ', '', $channel);
            $channels = strpos($search, ',') !== false ? explode(',', trim($search, '[]')) : [$search];
            foreach ($channels as $singleChannel) {
                if (strcasecmp($channelNoSpaces, str_replace(' ', '', trim($singleChannel))) === 0) {
                    return $replace;
    }}}}
    // 默认不进行繁简转换
    if ($t2s) {
        $channel = t2s($channel);
    }
    // 如果配置中包含 '\\s'，则替换空格；否则不替换
    if (in_array('\\s', $Config['channel_replacements'])) {
        $channel = str_replace(' ', '', $channel);
    }
    $channel = str_ireplace($Config['channel_replacements'], '', $channel);
    return $channel;
}

// 繁体转简体
function t2s($channel) {
    return OpenCC::convert($channel, 'TRADITIONAL_TO_SIMPLIFIED');
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