<?php
/**
 * @file index.php
 * @brief 主页处理脚本
 *
 * 该脚本处理来自客户端的请求，根据查询参数获取指定日期和频道的节目信息，
 * 并从 SQLite 数据库中提取或返回默认数据。
 *
 * 作者: Tak
 * GitHub: https://github.com/taksssss/EPG-Server
 */

// 引入公共脚本
require_once 'public.php';

// 禁止输出错误提示
error_reporting(0);

// 初始化响应头信息
$init = [
    'status' => 200,
    'headers' => [
        'content-type' => 'application/json'
    ]
];

// 生成响应
function makeRes($body, $status = 200, $headers = []) {
    $headers['Access-Control-Allow-Origin'] = '*';
    http_response_code($status);
    foreach ($headers as $key => $value) {
        header("$key: $value");
    }
    echo $body;
}

// 获取当前日期
function getNowDate() {
    return date('Y-m-d');
}

// 格式化时间
function getFormatTime($time) {
    if (strlen($time) < 8) {
        return ['date' => getNowDate(), 'time' => ''];
    }

    $date = substr($time, 0, 4) . '-' . substr($time, 4, 2) . '-' . substr($time, 6, 2);
    $time = strlen($time) >= 12 ? substr($time, 8, 2) . ':' . substr($time, 10, 2) : '';

    return ['date' => $date, 'time' => $time];
}

// 从数据库读取 diyp、lovetv 数据，兼容未安装 memcached 的情况
function readEPGData($date, $oriChName, $cleanChName, $db, $type) {
    global $Config;
    global $iconList;

    // 如果传入的日期小于当前日期，设置 cache_time 为 7 天
    $cache_time = ($date < date('Y-m-d')) ? 7 * 24 * 3600 : $Config['cache_time'];

    // 检查是否开启缓存并安装了 Memcached 类
    $memcached_enabled = $Config['cache_time'] && class_exists('Memcached')
        && ($memcached = new Memcached())->addServer('localhost', 11211);
    $cache_key = base64_encode("{$date}_{$cleanChName}_{$type}");

    if ($memcached_enabled) {
        // 从缓存中读取数据
        $cached_data = $memcached->get($cache_key);
        if ($cached_data) {
            return $cached_data;
        }
    }

    // 获取数据库类型（mysql 或 sqlite）
    $concat = $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
        ? "CONCAT('%', channel, '%')"
        : "'%' || channel || '%'";

    // 优先精准匹配，其次正向模糊匹配，最后反向模糊匹配
    $stmt = $db->prepare("
        SELECT epg_diyp
        FROM epg_data
        WHERE (
            channel = :channel
            OR channel LIKE :like_channel
            OR :channel LIKE $concat
        )
        ORDER BY
            CASE
                WHEN date = :date THEN 1
                ELSE 2
            END,
            CASE
                WHEN channel = :channel THEN 1
                WHEN channel LIKE :like_channel THEN 2
                ELSE 3
            END,
            CASE
                WHEN channel = :channel THEN NULL
                WHEN channel LIKE :like_channel THEN LENGTH(channel)
                ELSE -LENGTH(channel)
            END
        LIMIT 1
    ");
    $stmt->execute([
        ':date' => $date,
        ':channel' => $cleanChName,
        ':like_channel' => $cleanChName . '%'
    ]);
    $row = $stmt->fetchColumn();

    if (!$row) {
        return false;
    }

    // 在解码和添加 icon 后再编码为 JSON
    $rowArray = json_decode($row, true);
    $iconUrl = iconUrlMatch($rowArray['channel_name']) ?? iconUrlMatch($cleanChName) ?? iconUrlMatch($oriChName);
    $rowArray = array_merge(
        array_slice($rowArray, 0, array_search('source', array_keys($rowArray)) + 1),
        ['icon' => $iconUrl],
        array_slice($rowArray, array_search('source', array_keys($rowArray)) + 1)
    );
    $row = json_encode($rowArray, JSON_UNESCAPED_UNICODE);

    if ($type === 'diyp') {
        // 如果 Memcached 可用，将结果存储到缓存中
        if ($memcached_enabled) {
            $memcached->set($cache_key, $row, $cache_time);
        }
        return $row;
    }

    if ($type === 'lovetv') {
        $diyp_data = $rowArray;
        $date = $diyp_data['date'];
        $program = array_map(function($epg) use ($date) {
            $start_time = strtotime($date . ' ' . $epg['start']);
            $end_time = strtotime($date . ' ' . $epg['end']);
            $duration = $end_time - $start_time;
            return [
                'st' => $start_time,
                'et' => $end_time,
                'eventType' => '',
                'eventId' => '',
                't' => $epg['title'],
                'showTime' => gmdate('H:i', $duration),
                'duration' => $duration
            ];
        }, $diyp_data['epg_data']);

        // 查找当前节目
        $current_programme = $date === date('Y-m-d') ? findCurrentProgramme($program) : null;

        // 生成 lovetv 数据
        $lovetv_data = [
            $oriChName => [
                'isLive' => $current_programme ? $current_programme['t'] : '',
                'liveSt' => $current_programme ? $current_programme['st'] : 0,
                'channelName' => $diyp_data['channel_name'],
                'lvUrl' => $diyp_data['url'],
                'srcUrl' => $diyp_data['source'],
                'icon' => $diyp_data['icon'],
                'program' => $program
            ]
        ];

        $response = json_encode($lovetv_data, JSON_UNESCAPED_UNICODE);

        // 如果 Memcached 可用，将结果存储到缓存中
        if ($memcached_enabled) {
            $memcached->set($cache_key, $response, $cache_time);
        }

        return $response;
    }

    return false;
}

// 查找当前节目
function findCurrentProgramme($programmes) {
    $now = time();
    foreach ($programmes as $programme) {
        if ($programme['st'] <= $now && $programme['et'] >= $now) {
            return $programme;
        }
    }
    return null;
}

// 处理直播源请求
function liveFetchHandler($query_params) {
    global $Config, $liveDir, $serverUrl, $liveFileDir;

    if ($query_params['token'] === $Config['live_token']) {
        header('Content-Type: text/plain');

        // 如果存在 'url' 参数
        if (!empty($query_params['url'])) {
            $url = $query_params['url'];
            $filePath = (stripos($url, '/data/live/file/') !== false) 
                ? $liveFileDir . basename($url)
                : $liveFileDir . '/' . md5(urlencode($url)) . '.txt';

            echo file_exists($filePath) ? file_get_contents($filePath) : "文件不存在";
            exit;
        }

        // 处理 'live' 参数
        $filePath = $liveDir . (($query_params['live'] === 'txt') ? 'tv.txt' : ($query_params['live'] === 'm3u' ? 'tv.m3u' : ''));

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $tvgUrl = $serverUrl . ($query_params['live'] === 'm3u' ? '/t.xml.gz' : '/');
            if ($query_params['live'] === 'm3u') {
                $content = preg_replace('/(#EXTM3U x-tvg-url=")(.*?)(")/', '$1' . $tvgUrl . '$3', $content, 1);
            } elseif ($query_params['live'] === 'txt') {
                $content = preg_replace('/#genre#/', '#genre#,' . $tvgUrl, $content, 1);
            }
            echo $content;
        } else {
            echo "文件不存在或无效的 live 类型";
        }        
    } else {
        echo "无效的 token";
    }
    exit;
}

// 处理请求
function fetchHandler() {
    global $init, $db, $Config;

    $uri = parse_url($_SERVER['REQUEST_URI']);
    $query_params = [];
    if (isset($uri['query'])) {
        parse_str($uri['query'], $query_params);
    }

    // 处理直播源请求    
    if (isset($query_params['live'])) {
        liveFetchHandler($query_params);
    }

    // 获取并清理频道名称，繁体转换成简体
    $oriChName = $query_params['ch'] ?? $query_params['channel'] ?? '';
    $cleanChName = cleanChannelName($oriChName, $t2s = true);

    $date = isset($query_params['date']) ? getFormatTime(preg_replace('/\D+/', '', $query_params['date']))['date'] : getNowDate();

    // 频道参数为空时，直接重定向到 t.xml 文件
    if (empty($cleanChName)) {
        if ($Config['gen_xml'] === 1) {
            header('Content-Type: application/xml');
            header('Content-Disposition: attachment; filename="t.xml"');
            readfile('./t.xml');
        } else {
            // 输出消息并设置404状态码
            echo "404 Not Found. <br>未生成 xmltv 文件";
            http_response_code(404);
        }
        exit;
    }

    // 返回 diyp、lovetv 数据
    if (isset($query_params['ch']) || isset($query_params['channel'])) {
        function processResponse($response, $oriChName, $date, $type, $init) {
            $responseData = json_decode($response, true);
            $resDate = ($type === 'diyp') ? $responseData['date'] : date('Y-m-d', $responseData[$oriChName]['program'][0]['st']);
            if ($resDate === $date) {
                makeRes($response, $init['status'], $init['headers']);
                exit;
            }
            return false;
        }

        $type = isset($query_params['ch']) ? 'diyp' : 'lovetv';
        $response = readEPGData($date, $oriChName, $cleanChName, $db, $type);

        // 频道在列表中但无当天数据，尝试通过 tvmao 接口获取数据
        $retry = $response && !processResponse($response, $oriChName, $date, $type, $init);
        if ($retry && $Config['tvmao_default'] === 1 && $date >= date('Y-m-d')) {
            $matchChannelName = json_decode($response, true)['channel_name'] ?? $oriChName;
            $json_url = "https://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=$matchChannelName&resource_id=12520&format=json";
            downloadJSONData($json_url, $db, $log_messages, $matchChannelName, $replaceFlag = false); // 只更新无数据的日期
            $newResponse = readEPGData($date, $oriChName, $matchChannelName, $db, $type);
            processResponse($newResponse, $oriChName, $date, $type, $init);
        }

        // 返回默认数据
        $ret_default = !isset($Config['ret_default']) || $Config['ret_default'];
        $iconUrl = iconUrlMatch($cleanChName) ?? iconUrlMatch($oriChName);
        if ($type === 'diyp') {
            // 无法获取到数据时返回默认 diyp 数据
            $default_diyp_program_info = [
                'channel_name' => $cleanChName,
                'date' => $date,
                'url' => "https://github.com/taksssss/EPG-Server",
                'icon' => $iconUrl,
                'epg_data' => !$ret_default ? '' : array_map(function($hour) {
                    return [
                        'start' => sprintf('%02d:00', $hour),
                        'end' => sprintf('%02d:00', ($hour + 1) % 24),
                        'title' => '精彩节目',
                        'desc' => ''
                    ];
                }, range(0, 23, 1))
            ];
            $response = json_encode($default_diyp_program_info, JSON_UNESCAPED_UNICODE);
        } else {
            // 无法获取到数据时返回默认 lovetv 数据
            $default_lovetv_program_info = [
                $cleanChName => [
                    'isLive' => '',
                    'liveSt' => 0,
                    'channelName' => $cleanChName,
                    'lvUrl' => 'https://github.com/taksssss/EPG-Server',
                    'icon' => $iconUrl,
                    'program' => !$ret_default ? '' : array_map(function($hour) {
                        return [
                            'st' => strtotime(sprintf('%02d:00', $hour)),
                            'et' => strtotime(sprintf('%02d:00', ($hour + 1) % 24)),
                            't' => '精彩节目',
                            'd' => ''
                        ];
                    }, range(0, 23, 1))
                ]
            ];
            $response = json_encode($default_lovetv_program_info, JSON_UNESCAPED_UNICODE);
        }
        makeRes($response, $init['status'], $init['headers']);
    }
}

// 执行请求处理
fetchHandler();

?>
