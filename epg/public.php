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

// 引入配置文件
require_once 'config.php';

// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

// 创建或打开数据库
try {
    $db_file = __DIR__ . '/adata.db';
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
            // 检查是否为一对一映射或多对一映射，忽略所有空格
            $search = str_replace(' ', '', $search);
            $channelNoSpaces = str_replace(' ', '', $channel);
            $channels = strpos($search, ',') !== false ? explode(',', trim($search, '[]')) : [$search];
            foreach ($channels as $singleChannel) {
                if ($channelNoSpaces === str_replace(' ', '', trim($singleChannel))) {
                    return $replace;
    }}}}
    // 默认不进行繁简转换
    if ($t2s) {
        $channel = t2s($channel);
    }
    // 如果配置中包含 '\\s'，则替换空格；否则不替换
    $channel = str_ireplace($Config['channel_replacements'], '', $channel);
    if (in_array('\\s', $Config['channel_replacements'])) {
        $channel = str_replace(' ', '', $channel);
    }
    return $channel;
}

// 繁体转简体
function t2s($channel) {
    return OpenCC::convert($channel, 'TRADITIONAL_TO_SIMPLIFIED');
}

?>