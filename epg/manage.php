<?php
/**
 * @file manage.php
 * @brief 管理页面部分
 *
 * 管理界面脚本，用于处理会话管理、密码更改、登录验证、配置更新、更新日志展示等功能。
 *
 * 作者: Tak
 * GitHub: https://github.com/taksssss/EPG-Server
 */

// 引入公共脚本，初始化数据库
require_once 'public.php';
initialDB();

session_start();

// 读取 configUpdated 状态
$configUpdated = isset($_SESSION['configUpdated']) && $_SESSION['configUpdated'];
if ($configUpdated) {
    unset($_SESSION['configUpdated']);
} else {
    // 首次进入界面，检查 cron.php 是否运行正常
    if($Config['interval_time']!=0) {
        $output = [];
        exec("ps aux | grep '[c]ron.php'", $output);
        if(!$output) {
            exec('php cron.php > /dev/null 2>/dev/null &');
        }
    }
}

if (isset($_SESSION['display_message'])) {
    $displayMessage = $_SESSION['display_message'];
    unset($_SESSION['display_message']); // 清除消息以防再次显示
} else {
    $displayMessage = '';
}

// 过渡到新的 md5 密码
if (!preg_match('/^[a-f0-9]{32}$/i', $Config['manage_password'])) {
    $Config['manage_password'] = md5($Config['manage_password']);
    file_put_contents($config_path, json_encode($Config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// 处理密码更新请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $oldPassword = md5($_POST['old_password']);
    $newPassword = md5($_POST['new_password']);

    // 验证原密码是否正确
    if ($oldPassword === $Config['manage_password']) {
        // 原密码正确，更新配置中的密码
        $Config['manage_password'] = $newPassword;

        // 将新配置写回 config.json
        file_put_contents($config_path, json_encode($Config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 设置密码更改成功的标志变量
        $passwordChanged = true;
    } else {
        $passwordChangeError = "原密码错误";
    }
}

// 检查是否提交登录表单
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $password = md5($_POST['password']);

    // 验证密码
    if ($password === $Config['manage_password']) {
        // 密码正确，设置会话变量
        $_SESSION['loggedin'] = true;

        // 设置会话变量，表明用户可以访问 phpliteadmin.php 、 tinyfilemanager.php
        $_SESSION['can_access_phpliteadmin'] = true;
        $_SESSION['can_access_tinyfilemanager'] = true;
    } else {
        $error = "密码错误";
    }
}

// 处理密码更改成功后的提示
$passwordChangedMessage = isset($passwordChanged) ? "<p style='color:green;'>密码已更改</p>" : '';
$passwordChangeErrorMessage = isset($passwordChangeError) ? "<p style='color:red;'>$passwordChangeError</p>" : '';

// 检查是否已登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 显示登录表单
    include 'assets/html/login_page.php';
    exit;
}

// 检查是否提交配置表单
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

    // 获取 $_POST 中除了 'update' 以外的所有键
    $config_keys = array_keys(array_filter($_POST, function($key) {
        return !in_array($key, ['update']); // 排除 'update' 和 'xml_urls' 键
    }, ARRAY_FILTER_USE_KEY));
    
    foreach ($config_keys as $key) {
        ${$key} = (is_numeric($_POST[$key]) ? intval($_POST[$key]) : $_POST[$key]);
    }
    
    // 获取表单数据并去除每个 URL 末尾的换行符
    $xml_urls = array_map('trim', explode("\n", str_replace(["，", "："], [",", ":"], trim($xml_urls))));

    // 过滤和规范化 xml_urls
    $xml_urls = array_values(array_map(function($url) {
        return preg_replace('/^#\s*(?:#\s*)*(\S+)(\s*#.*)?$/', '# $1$2', trim($url));
    }, $xml_urls));
    
    $cache_time *= 3600;
    $interval_time = $interval_hour * 3600 + $interval_minute * 60;    
    $mysql = ["host" => $mysql_host, "dbname" => $mysql_dbname, "username" => $mysql_username, "password" => $mysql_password];

    // 处理频道别名
    $channel_mappings = [];
    if ($mappings = trim($_POST['channel_mappings'] ?? '')) {
        foreach (array_filter(array_map('trim', explode("\n", $mappings))) as $line) {
            list($search, $replace) = preg_split('/=》|=>/', $line);
            $channel_mappings[trim($search)] = trim(str_replace("，", ",", trim($replace)), '[]');
        }
    }

    // 处理频道指定 EPG 数据，去掉 epg_src 前面的【已停用】
    $channel_bind_epg = isset($_POST['channel_bind_epg']) ?
        array_filter(
            array_reduce(json_decode($_POST['channel_bind_epg'], true), function($result, $item) {
                $epgSrc = preg_replace('/^【已停用】/', '', $item['epg_src']);
                if (!empty($item['channels'])) {
                    $result[$epgSrc] = trim(str_replace("，", ",", trim($item['channels'])), '[]');
                }
                return $result;
            }, [])
        ) : $Config['channel_bind_epg'];

    // 获取旧的配置
    $oldConfig = $Config;

    // 移除以 mysql_ 和 interval_ 开头的键
    $config_keys_filtered = array_filter($config_keys, function($key) {
        return !preg_match('/^(mysql_|interval_)/', $key);
    });

    // 需要包含在新配置中的变量
    $config_keys_new = ['channel_bind_epg', 'interval_time', 'mysql'];
    $config_keys_save = array_merge($config_keys_filtered, $config_keys_new);

    // 使用 compact 创建新配置数组
    $newConfig = array_merge(compact($config_keys_save), ['manage_password' => $Config['manage_password']]); // 保留密码

    // 将新配置写回 config.json
    file_put_contents($config_path, json_encode($newConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // 设置标志变量以显示弹窗
    $_SESSION['configUpdated'] = true;

    // 重新加载配置以确保页面显示更新的数据
    $Config = json_decode(file_get_contents($config_path), true);

    // 重新启动 cron.php ，设置新的定时任务
    if ($oldConfig['start_time'] !== $start_time || $oldConfig['end_time'] !== $end_time || $oldConfig['interval_time'] !== $interval_time) {
        exec('php cron.php > /dev/null 2>/dev/null &');
    }
    header('Location: manage.php');
    exit;
}

// 连接数据库并获取日志表中的数据
$logData = [];
$cronLogData = [];
$channels = [];

try {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $dbResponse = null;

    if ($requestMethod == 'GET') {

        // 确定操作类型
        $action_map = [
            'get_version_update_info', 'update_version', 'get_update_logs', 'get_cron_logs',
            'get_channel', 'get_epg_by_channel', 'get_icon', 'get_channel_bind_epg',
            'get_channel_match', 'get_gen_list', 'download_data', 'delete_unused_icons'
        ];
        $action = key(array_intersect_key($_GET, array_flip($action_map))) ?: '';

        // 根据操作类型执行不同的逻辑
        switch ($action) {
            case 'get_version_update_info':
                if ($Config['check_update'] ?? true) {
                    // 尝试读取远程版本信息
                    $versionUrl = 'https://gitee.com/taksssss/EPG-Server/raw/main/epg/assets/version.txt';
                    $lines = @file($versionUrl, FILE_IGNORE_NEW_LINES);
                
                    // 返回结果，若读取失败则返回错误信息
                    $dbResponse = $lines ? [
                        'hasUpdate' => version_compare($currentVersion, $lines[0], '<'),
                        'updateVersion' => $lines[0],
                        'updateInfo' => array_slice($lines, 1)
                    ] : '';
                } else {
                    $dbResponse = ['hasUpdate' => false];
                }
                break;

            case 'update_version':
                // 下载文件并保存到临时文件
                $updateUrl = 'https://gitee.com/taksssss/EPG-Server/raw/main/codes.zip';
                if ($zipContent = downloadData($updateUrl)) {
                    file_put_contents('tmp.zip', $zipContent);
            
                    // 解压并删除临时文件
                    $zip = new ZipArchive();
                    if ($zip->open('tmp.zip') === TRUE) {
                        $zip->extractTo('.');
                        $zip->close();
                        unlink('tmp.zip');
                        $dbResponse = ['updated' => true];
                    }
                }
                break;

            case 'get_update_logs':
                // 获取更新日志
                $dbResponse = $db->query("SELECT * FROM update_log")->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'get_cron_logs':
                // 获取 cron 日志
                $dbResponse = $db->query("SELECT * FROM cron_log")->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'get_channel':
                // 获取频道
                $channels = $db->query("SELECT DISTINCT channel FROM epg_data ORDER BY channel ASC")->fetchAll(PDO::FETCH_COLUMN);
                $channelMappings = $Config['channel_mappings'];
                $mappedChannels = [];
                foreach ($channelMappings as $mapped => $original) {
                    if (($index = array_search(strtoupper($mapped), $channels)) !== false) {
                        $mappedChannels[] = [
                            'original' => $mapped,
                            'mapped' => $original
                        ];
                        unset($channels[$index]); // 从剩余频道中移除
                    }
                }
                foreach ($channels as $channel) {
                    $mappedChannels[] = [
                        'original' => $channel,
                        'mapped' => ''
                    ];
                }
                $dbResponse = [
                    'channels' => $mappedChannels,
                    'count' => count($mappedChannels)
                ];
                break;

            case 'get_epg_by_channel':
                // 查询
                $channel = urldecode($_GET['channel']);
                $date = urldecode($_GET['date']);
                $stmt = $db->prepare("SELECT epg_diyp FROM epg_data WHERE channel = :channel AND date = :date");
                $stmt->execute([':channel' => $channel, ':date' => $date]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC); // 获取单条结果            
                if ($result) {
                    $epgOutput = "";
                    $epgData = json_decode($result['epg_diyp'], true);                    
                    foreach ($epgData['epg_data'] as $epgItem) {
                        $epgOutput .= "{$epgItem['start']} {$epgItem['title']}\n";
                    }            
                    $dbResponse = ['channel' => $channel, 'date' => $date, 'epg' => trim($epgOutput)];
                } else {
                    $dbResponse = ['channel' => $channel, 'date' => $date, 'epg' => '无节目信息'];
                }
                break;

            case 'get_icon':
                // 是否显示无节目单的内置台标
                if(isset($_GET['get_all_icon'])) {
                    $iconList = $iconListMerged;
                }
                // 获取并合并数据库中的频道和 $iconList 中的频道，去重后按字母排序
                $allChannels = array_unique(array_merge(
                    $db->query("SELECT DISTINCT channel FROM epg_data ORDER BY channel ASC")->fetchAll(PDO::FETCH_COLUMN),
                    array_keys($iconList)
                ));
                sort($allChannels);
                $channelsInfo = array_map(function($channel) use ($iconList) {
                    return ['channel' => $channel, 'icon' => $iconList[$channel] ?? ''];
                }, $allChannels);
                $withIcons = array_filter($channelsInfo, function($c) { return !empty($c['icon']);});
                $withoutIcons = array_filter($channelsInfo, function($c) { return empty($c['icon']);});
                $dbResponse = [
                    'channels' => array_merge($withIcons, $withoutIcons),
                    'count' => count($allChannels)
                ];
                break;

            case 'get_channel_bind_epg':
                // 获取频道绑定的 EPG
                $channels = $db->query("SELECT DISTINCT channel FROM epg_data ORDER BY channel ASC")->fetchAll(PDO::FETCH_COLUMN);
                $channelBindEpg = $Config['channel_bind_epg'] ?? [];
                $xmlUrls = $Config['xml_urls'];
                $filteredUrls = array_values(array_filter(array_map(function($url) {
                    $url = trim($url);
                    if (preg_match('/^#?\s*tvmao/', $url)) { return '';}
                    $url = (strpos($url, '#') === 0)
                        ? preg_replace('/^([^#]*#[^#]*)#.*$/', '$1', $url)
                        : preg_replace('/#.*$/', '', $url);
                    return trim($url);
                }, $xmlUrls)));
                $dbResponse = array_map(function($epgSrc) use ($channelBindEpg) {
                    $cleanEpgSrc = trim(preg_replace('/^\s*#\s*/', '', $epgSrc));
                    $isInactive = strpos(trim($epgSrc), '#') === 0;
                    return [
                        'epg_src' => ($isInactive ? '【已停用】' : '') . $cleanEpgSrc,
                        'channels' => $channelBindEpg[$cleanEpgSrc] ?? ''
                    ];
                }, $filteredUrls);
                $dbResponse = array_merge(
                    array_filter($dbResponse, function($item) { return strpos($item['epg_src'], '【已停用】') === false; }),
                    array_filter($dbResponse, function($item) { return strpos($item['epg_src'], '【已停用】') !== false; })
                );
                break;

            case 'get_channel_match':
                // 获取频道匹配
                $channels = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
                if (empty($channels)) {
                    echo json_encode(['ori_channels' => [], 'clean_channels' => [], 'match' => [], 'type' => []]);
                    exit;
                }
                $cleanChannels = explode("\n", t2s(implode("\n", array_map('cleanChannelName', $channels))));
                $epgData = $db->query("SELECT channel FROM epg_data")->fetchAll(PDO::FETCH_COLUMN);
                $channelMap = array_combine($cleanChannels, $channels);
                $matches = [];
                foreach ($cleanChannels as $cleanChannel) {
                    $originalChannel = $channelMap[$cleanChannel];
                    $matchResult = null;
                    $matchType = '未匹配';
                    if (in_array($cleanChannel, $epgData)) {
                        $matchResult = $cleanChannel;
                        $matchType = '精确匹配';
                        if ($cleanChannel !== $originalChannel) {
                            $matchType = '别名/忽略';
                        }
                    } else {
                        foreach ($epgData as $epgChannel) {
                            if (stripos($epgChannel, $cleanChannel) !== false) {
                                if (!isset($matchResult) || strlen($epgChannel) < strlen($matchResult)) {
                                    $matchResult = $epgChannel;
                                    $matchType = '正向模糊';
                                }
                            } elseif (stripos($cleanChannel, $epgChannel) !== false) {
                                if (!isset($matchResult) || strlen($epgChannel) > strlen($matchResult)) {
                                    $matchResult = $epgChannel;
                                    $matchType = '反向模糊';
                                }
                            }
                        }
                    }
                    $matches[$cleanChannel] = [
                        'ori_channel' => $originalChannel,
                        'clean_channel' => $cleanChannel,
                        'match' => $matchResult,
                        'type' => $matchType
                    ];
                }
                $dbResponse = $matches;
                break;

            case 'get_gen_list':
                // 获取生成列表
                $dbResponse = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
                break;

            case 'download_data':
                // 下载数据
                $url = filter_var(($_GET['url']), FILTER_VALIDATE_URL);
                if ($url) {
                    $data = downloadData($url, 5);
                    if ($data !== false) {
                        $dbResponse = ['success' => true, 'data' => $data];
                    } else {
                        $dbResponse = ['success' => false, 'message' => '无法获取URL内容'];
                    }
                } else {
                    $dbResponse = ['success' => false, 'message' => '无效的URL'];
                }
                break;

            case 'delete_unused_icons':
                // 清除未在使用的台标
                $iconUrls = array_map(function($url) {
                    return parse_url($url, PHP_URL_PATH);
                }, array_values($iconList));
                $iconPath = __DIR__ . '/data/icon';
                $parentRltPath = '/' . basename(__DIR__) . '/data/icon/';
                $deletedCount = 0;
                foreach (scandir($iconPath) as $file) {
                    if ($file === '.' || $file === '..') continue;
                    $iconRltPath = $parentRltPath . $file;
                    if (!in_array($iconRltPath, $iconUrls)) {
                        if (@unlink($iconPath . '/' . $file)) {
                            $deletedCount++;
                        }
                    }
                }
                $dbResponse = ['success' => true, 'message' => "共清理了 $deletedCount 个台标"];
                break;

            default:
                $dbResponse = null;
                break;
        }

        if ($dbResponse !== null) {
            header('Content-Type: application/json');
            echo json_encode($dbResponse);
            exit;
        }
    }

    // 处理 POST 请求
    if ($requestMethod === 'POST') {
        // 定义操作类型和对应的条件
        $actions = [
            'set_gen_list' => isset($_GET['set_gen_list']),
            'import_config' => isset($_POST['importExport']) && !empty($_FILES['importFile']['tmp_name']),
            'export_config' => isset($_POST['importExport']) && empty($_FILES['importFile']['tmp_name']),
            'upload_icon' => isset($_FILES['iconFile']),
            'update_icon_list' => isset($_POST['update_icon_list']),
            'm3u_match_icons' => isset($_FILES['m3utxtFile']),
        ];

        // 确定操作类型
        $action = '';
        foreach ($actions as $key => $condition) {
            if ($condition) { $action = $key; break; }
        }

        switch ($action) {
            case 'set_gen_list':
                // 设置生成列表
                $data = json_decode(file_get_contents("php://input"), true)['data'] ?? '';
                try {
                    $db->beginTransaction();
                    $db->exec("DELETE FROM gen_list");
                    $lines = array_filter(array_map('trim', explode("\n", $data)));
                    foreach ($lines as $line) {
                        $stmt = $db->prepare("INSERT INTO gen_list (channel) VALUES (:channel)");
                        $stmt->bindValue(':channel', $line, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                    $db->commit();
                    echo 'success';
                } catch (PDOException $e) {
                    $db->rollBack();
                    echo "数据库操作失败: " . $e->getMessage();
                }
                exit;

            case 'import_config':
                // 导入配置
                $zip = new ZipArchive();
                $importFile = $_FILES['importFile']['tmp_name'];
                $message = "";
                if ($zip->open($importFile) === TRUE) {
                    if ($zip->extractTo('.')) {
                        $message = "导入成功！";
                    } else {
                        $message = "导入失败！解压过程中发生问题。";
                    }
                    $zip->close();
                } else {
                    $message = "导入失败！无法打开压缩文件。";
                }
                $_SESSION['display_message'] = $message;
                header('Location: manage.php');
                exit;

            case 'export_config':
                $zip = new ZipArchive();
                $zipFileName = 't.gz';
                if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $dataDir = __DIR__ . '/data';
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($dataDir),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files as $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = 'data/' . substr($filePath, strlen($dataDir) + 1);
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                    $zip->close();
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename=' . $zipFileName);
                    readfile($zipFileName);
                    unlink($zipFileName);
                }
                exit;

            case 'upload_icon':
                // 上传图标
                $file = $_FILES['iconFile'];
                $fileName = $file['name'];
                $uploadFile = $iconDir . $fileName;
                if ($file['type'] === 'image/png' && move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $iconUrl = $serverUrl . dirname($_SERVER['SCRIPT_NAME']) . '/data/icon/' . basename($fileName);
                    echo json_encode(['success' => true, 'iconUrl' => $iconUrl]);
                } else {
                    echo json_encode(['success' => false, 'message' => '文件上传失败']);
                }
                exit;

            case 'update_icon_list':
                // 更新图标
                $iconList = [];
                $updatedIcons = json_decode($_POST['updatedIcons'], true);
                foreach ($updatedIcons as $channelData) {
                    $channelName = strtoupper(trim($channelData['channel']));
                    $iconList[$channelName] = $channelData['icon'];
                }

                // 过滤掉图标值为空和频道名为空的条目
                $iconList = array_filter($iconList, function($icon, $channel) {
                    return !empty($icon) && !empty($channel);
                }, ARRAY_FILTER_USE_BOTH);

                if (file_put_contents($iconList_path, json_encode($iconList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) === false) {
                    echo json_encode(['success' => false, 'message' => '更新 iconList.json 时发生错误']);
                } else {
                    echo json_encode(['success' => true]);
                }
                exit;

            case 'm3u_match_icons':
                // 频道数据模糊匹配
                function dbChNameMatch($channelName) {
                    global $db;
                    // 获取数据库类型（mysql 或 sqlite）
                    $concat = $db->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
                        ? "CONCAT('%', channel, '%')"
                        : "'%' || channel || '%'";

                    $stmt = $db->prepare("
                        SELECT channel
                        FROM epg_data
                        WHERE date = :date
                        AND (
                            channel = :channel
                            OR channel LIKE :like_channel
                            OR :channel LIKE $concat
                        )
                        ORDER BY
                            CASE
                                WHEN channel = :channel THEN 1
                                WHEN channel LIKE :like_channel THEN 2
                                ELSE 3
                            END,
                            LENGTH(channel) DESC
                        LIMIT 1
                    ");
                    $stmt->execute([
                        ':date' => date('Y-m-d'),
                        ':channel' => $channelName,
                        ':like_channel' => $channelName . '%'
                    ]);
                    return $stmt->fetchColumn();
                }

                $fileTmpPath = $_FILES['m3utxtFile']['tmp_name'];
                $fileContent = file_get_contents($fileTmpPath);
                $m3uFileType = stripos($fileContent, '#EXTM3U') !== false;
                $epgUrl = $serverUrl . dirname($_SERVER['SCRIPT_NAME']) . "/t.xml.gz";
                $newFileContent = "#EXTM3U x-tvg-url=\"{$epgUrl}\"\n";
                $lines = explode("\n", $fileContent);
                $groupTitle = '';

                foreach ($lines as $line) {
                    $line = trim($line);
                    
                    if ($m3uFileType) {
                        // 处理 M3U 格式
                        if (strpos($line, '#EXTM3U') === 0) {
                            $line = preg_replace('/x-tvg-url="[^"]+"/', 'x-tvg-url="' . $epgUrl . '"', $line);
                            $newFileContent = "$line" . (strpos($line, 'x-tvg-url=') === false ? ' x-tvg-url="' . $epgUrl . '"' : '') . "\n";
                            continue;
                        }

                        if (strpos($line, '#EXTINF') !== false) {
                            if (preg_match('/#EXTINF:-1(.*),(.+)/', $line, $matches)) {
                                $channelInfo = $matches[1]; // 提取 tvg-id, tvg-name 等信息
                                $originalChannelName = trim($matches[2]); // 提取频道名称

                                // 尝试从数据库中匹配频道
                                $cleanChName = cleanChannelName($originalChannelName);
                                $channelName = dbChNameMatch($cleanChName) ?: $originalChannelName;
                                $tvgId = $tvgName = $channelName;

                                // 从 EXTINF 提取额外信息
                                if (preg_match('/tvg-id="([^"]+)"/', $channelInfo, $tvgIdMatch)) {
                                    $tvgId = $tvgName = $tvgIdMatch[1];
                                }
                                if (preg_match('/tvg-name="([^"]+)"/', $channelInfo, $tvgNameMatch)) {
                                    $tvgId = $tvgName = $tvgNameMatch[1];
                                }
                                if (preg_match('/group-title="([^"]+)"/', $channelInfo, $groupTitleMatch)) {
                                    $groupTitle = $groupTitleMatch[1];
                                }

                                // 模糊匹配台标
                                $iconUrl = iconUrlMatch($channelName);
                                $newFileContent .= "#EXTINF:-1,tvg-id=\"$tvgId\" tvg-name=\"$tvgName\"" .
                                                    (!empty($iconUrl) ? " tvg-logo=\"$iconUrl\"" : "") .
                                                    (!empty($groupTitle) ? " group-title=\"$groupTitle\"" : "") .
                                                    ",$originalChannelName\n";
                            }
                        } else {
                            $newFileContent .= "$line\n";
                        }
                    } else {
                        // 处理 TXT 格式
                        $parts = explode(',', $line);
                        if (count($parts) == 2) {
                            if ($parts[1] === '#genre#') {
                                $groupTitle = trim($parts[0]); // 更新 group-title
                            } else {
                                $originalChannelName = trim($parts[0]);
                                $cleanChName = cleanChannelName($originalChannelName);
                                $channelName = dbChNameMatch($cleanChName) ?: $originalChannelName;
                                $streamUrl = $parts[1];

                                // 模糊匹配台标
                                $iconUrl = iconUrlMatch($channelName);
                                $newFileContent .= "#EXTINF:-1,tvg-id=\"$channelName\" tvg-name=\"$channelName\"" .
                                                    (!empty($iconUrl) ? " tvg-logo=\"$iconUrl\"" : "") .
                                                    (!empty($groupTitle) ? " group-title=\"$groupTitle\"" : "") .
                                                    ",$originalChannelName\n";
                                $newFileContent .= "$streamUrl\n";
                            }
                        }
                    }
                }

                echo $newFileContent;
                exit;
        }
    }
} catch (Exception $e) {
    // 处理数据库连接错误
    $logData = [];
    $cronLogData = [];
    $channels = [];
}

// 生成配置管理表单
include 'assets/html/manage_page.php';
?>