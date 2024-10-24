<?php
/**
 * @file manage.php
 * @brief 管理页面部分
 * 
 * 管理界面脚本，用于处理会话管理、密码更改、登录验证、配置更新、更新日志展示等功能。
 * 修复原作者一点小小的语法错误和增加一个退出按钮方便操作，使用php的session_destroy();
 * 
 * 作者: Tak
 * GitHub: https://github.com/taksssss/PHP-EPG-Docker-Server
 */

// 引入公共脚本，初始化数据库
require_once 'public.php';
initialDB();

session_start();

// 读取 configUpdated 状态
$configUpdated = isset($_SESSION['configUpdated']) && $_SESSION['configUpdated'];
if ($configUpdated) {
    unset($_SESSION['configUpdated']);
}

if (isset($_SESSION['import_message'])) {
    $importMessage = $_SESSION['import_message'];
    unset($_SESSION['import_message']); // 清除消息以防再次显示
} else {
    $importMessage = '';
}

// 处理密码更新请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

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
    $password = $_POST['password'];

    // 验证密码
    if ($password === $Config['manage_password']) {
        // 密码正确，设置会话变量
        $_SESSION['loggedin'] = true;

        // 设置会话变量，表明用户可以访问 phpliteadmin.php
        $_SESSION['can_access_phpliteadmin'] = true;
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
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>登录</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        <div class="container">
            <h2>登录</h2>
            <form method="POST">
                <label for="password">管理密码:</label><br><br>
                <input type="password" id="password" name="password"><br><br>
                <input type="hidden" name="login" value="1">
                <input type="submit" value="登录">
            </form>
            <div class="button-container">
                <button type="button" onclick="showChangePasswordForm()">更改密码</button>
            </div>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <?php echo $passwordChangedMessage; ?>
            <?php echo $passwordChangeErrorMessage; ?>
        </div>
        <!-- 底部显示 -->
        <div class="footer">
            <a href="https://github.com/taksssss/PHP-EPG-Docker-Server" style="color: #888; text-decoration: none;">https://github.com/taksssss/PHP-EPG-Docker-Server</a>
        </div>
    
    <!-- 修改密码模态框 -->
    <div id="changePasswordModal" class="modal">
        <div class="passwd-modal-content">
            <span class="close-password">&times;</span>
            <h2>更改登录密码</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="old_password">原密码</label>
                    <input type="password" id="old_password" name="old_password">
                </div>
                <div class="form-group">
                    <label for="new_password">新密码</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <input type="hidden" name="change_password" value="1">
                <input type="submit" value="更改密码">
            </form>
        </div>
    </div>
    <script>
        function showChangePasswordForm() {
            var changePasswordModal = document.getElementById("changePasswordModal");
            var changePasswordSpan = document.getElementsByClassName("close-password")[0];

            changePasswordModal.style.display = "block";

            changePasswordSpan.onclick = function() {
                changePasswordModal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == changePasswordModal) {
                    changePasswordModal.style.display = "none";
                }
            }
        }
    </script>
    </body>
    </html>
    <?php
    exit;
}

// 检查是否提交配置表单
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

    // 获取表单数据并去除每个 URL 末尾的换行符
    $xml_urls = array_map('trim', explode("\n", str_replace(["，", "："], [",", ":"], trim($_POST['xml_urls']))));

    // 过滤和规范化 xml_urls
    $xml_urls = array_values(array_map(function($url) {
        return preg_replace('/^#\s*(?:#\s*)*(\S+)(\s*#.*)?$/', '# $1$2', trim($url));
    }, $xml_urls));
    
    $days_to_keep = intval($_POST['days_to_keep']);
    $gen_xml = isset($_POST['gen_xml']) ? intval($_POST['gen_xml']) : $Config['gen_xml'];
    $include_future_only = isset($_POST['include_future_only']) ? intval($_POST['include_future_only']) : $Config['include_future_only'];
    $ret_default = isset($_POST['ret_default']) ? intval($_POST['ret_default']) : $Config['ret_default'];
    $gen_list_enable = isset($_POST['gen_list_enable']) ? intval($_POST['gen_list_enable']) : $Config['gen_list_enable'];
    $cache_time = intval($_POST['cache_time']) * 3600;
    $db_type = isset($_POST['db_type']) ? $_POST['db_type'] : $Config['db_type'];
    $mysql_host = isset($_POST['mysql_host']) ? $_POST['mysql_host'] : $Config['mysql_host'];
    $mysql_dbname = isset($_POST['mysql_dbname']) ? $_POST['mysql_dbname'] : $Config['mysql_dbname'];
    $mysql_username = isset($_POST['mysql_username']) ? $_POST['mysql_username'] : $Config['mysql_username'];
    $mysql_password = isset($_POST['mysql_password']) ? $_POST['mysql_password'] : $Config['mysql_password'];
    $mysql = ["host" => $mysql_host, "dbname" => $mysql_dbname, "username" => $mysql_username, "password" => $mysql_password];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    // 处理间隔时间
    $interval_hour = intval($_POST['interval_hour']);
    $interval_minute = intval($_POST['interval_minute']);
    $interval_time = $interval_hour * 3600 + $interval_minute * 60;

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
        ) : [];

    // 获取旧的配置
    $oldConfig = $Config;

    // 更新配置
    $newConfig = [
        'xml_urls' => $xml_urls,
        'days_to_keep' => $days_to_keep,
        'gen_xml' => $gen_xml,
        'include_future_only' => $include_future_only,
        'ret_default' => $ret_default,
        'gen_list_enable' => $gen_list_enable,
        'cache_time' => $cache_time,
        'db_type' => $db_type,
        'mysql' => $mysql,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'interval_time' => $interval_time,
        'manage_password' => $Config['manage_password'], // 保留密码
        'channel_bind_epg' => $channel_bind_epg,
        'channel_mappings' => $channel_mappings
    ];

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

// 连接数据库并获取日志表中的数据
$logData = [];
$cronLogData = [];
$channels = [];

try {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $dbResponse = null;

    if ($requestMethod == 'GET') {
        $action = '';

        // 确定操作类型
        if (isset($_GET['get_update_logs'])) {
            $action = 'get_update_logs';
        } elseif (isset($_GET['get_cron_logs'])) {
            $action = 'get_cron_logs';
        } elseif (isset($_GET['get_channel'])) {
            $action = 'get_channel';
        } elseif (isset($_GET['get_icon'])) {
            $action = 'get_icon';
        } elseif (isset($_GET['get_channel_bind_epg'])) {
            $action = 'get_channel_bind_epg';
        } elseif (isset($_GET['get_channel_match'])) {
            $action = 'get_channel_match';
        } elseif (isset($_GET['get_gen_list'])) {
            $action = 'get_gen_list';
        } elseif (isset($_GET['url'])) {
            $action = 'download_data';
        } elseif (isset($_GET['delete_unused_icons'])) {
            $action = 'delete_unused_icons';
        }

        // 根据操作类型执行不同的逻辑
        switch ($action) {
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

            case 'get_icon':
                // 是否显示无节目表的内置台标
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
                $url = filter_var($_GET['url'], FILTER_VALIDATE_URL);
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
        $action = '';

        if (isset($_GET['set_gen_list'])) {
            $action = 'set_gen_list';
        } elseif (isset($_POST['importExport']) && !empty($_FILES['importFile']['tmp_name'])) {
            $action = 'import_config';
        } elseif (isset($_POST['importExport']) && empty($_FILES['importFile']['tmp_name'])) {
            $action = 'export_config';
        } elseif (isset($_FILES['iconFile'])) {
            $action = 'upload_icon';
        } elseif (isset($_POST['update_icon_list'])) {
            $action = 'update_icon_list';
        } elseif (isset($_FILES['m3utxtFile'])) {
            $action = 'm3u_match_icons';
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
                $_SESSION['import_message'] = $message;
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
                $epgUrl = $serverUrl . dirname($_SERVER['SCRIPT_NAME']) . "/t.xml.gz";
                $newFileContent = "#EXTM3U x-tvg-url=\"{$epgUrl}\"\n";
                $lines = explode("\n", $fileContent);
                $groupTitle = '';
            
                foreach ($lines as $line) {
                    $line = trim($line);

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
                    } elseif (filter_var($line, FILTER_VALIDATE_URL)) {
                        $newFileContent .= "$line\n";
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
            
                                if (filter_var($streamUrl, FILTER_VALIDATE_URL)) {
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
?>
<!DOCTYPE html>
<html>
<head>
    <title>管理配置</title>
    <link rel="stylesheet" type="text/css" href="css/manage.css">
</head>
<body>
<div class="container">
    <h2>管理配置</h2>
    <form method="POST" id="settingsForm">

        <label for="xml_urls">【EPG源地址】（支持 xml 跟 .xml.gz 格式， # 为注释，支持获取 猫 数据）</label><span id="channelbind" onclick="showModal('channelbindepg')" style="color: blue; cursor: pointer;">（频道指定EPG源）</span><br><br>
        <textarea placeholder="一行一个，地址前面加 # 可以临时停用，后面加 # 可以备注。快捷键： Ctrl+/  。
猫示例：tvmao, 广东卫视, 珠江频道" id="xml_urls" name="xml_urls" style="height: 122px;"><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

        <div class="form-row">
            <label for="days_to_keep" class="label-days-to-keep">数据保存天数</label>
            <label for="start_time" class="label-time custom-margin1">【定时任务】： 开始时间</label>
            <label for="end_time" class="label-time2 custom-margin2">结束时间</label>
            <label for="interval_time" class="label-time3 custom-margin3">间隔周期（选0小时0分钟取消）</label>
        </div>

        <div class="form-row">
            <select id="days_to_keep" name="days_to_keep" required>
                <?php for ($i = 1; $i <= 30; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $Config['days_to_keep'] == $i ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <input type="time" id="start_time" name="start_time" value="<?php echo $Config['start_time']; ?>" required>
            <input type="time" id="end_time" name="end_time" value="<?php echo $Config['end_time']; ?>" required>
            
            <!-- Interval Time Controls -->
            <select id="interval_hour" name="interval_hour" required>
                <?php for ($h = 0; $h < 24; $h++): ?>
                    <option value="<?php echo $h; ?>" <?php echo floor($Config['interval_time'] / 3600) == $h ? 'selected' : ''; ?>>
                        <?php echo $h; ?>
                    </option>
                <?php endfor; ?>
            </select> 小时
            <select id="interval_minute" name="interval_minute" required>
                <?php for ($m = 0; $m < 60; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo (intval($Config['interval_time']) % 3600) / 60 == $m ? 'selected' : ''; ?>>
                        <?php echo $m; ?>
                    </option>
                <?php endfor; ?>
            </select> 分钟
        </div><br>
        
        <div class="flex-container">
            <div class="flex-item" style="width: 100%;">
                <label>
                    【频道别名】（数据库频道名 => 频道别名1, 频道别名2, ...）<span id="dbChannelName" onclick="showModal('channel')" style="color: blue; cursor: pointer;">（编辑别名）</span><span id="dbChannelName" onclick="showModal('icon')" style="color: blue; cursor: pointer;">（编辑台标）</span>
                </label><br><br>
                <textarea id="channel_mappings" name="channel_mappings" style="height: 142px;"><?php echo implode("\n", array_map(function($search, $replace) { return $search . ' => ' . $replace; }, array_keys($Config['channel_mappings']), $Config['channel_mappings'])); ?></textarea><br><br>
            </div>
        </div>
        <div class="tooltip">
            <input id="updateConfig" type="submit" name="update" value="更新配置">
            <span class="tooltiptext">快捷键：Ctrl+S</span>
        </div>
        <br><br>
        <div class="button-container">
            <a href="update.php" target="_blank">更新数据</a>
            <a href="phpliteadmin.php" target="_blank" onclick="return handleDbManagement();">管理数据</a>
            <button type="button" onclick="showModal('cron')">定时日志</button>
            <button type="button" onclick="showModal('update')">更新日志</button>
            <button type="button" onclick="showModal('moresetting')">更多设置</button>
            <button type="button" name="logoutbtn" onclick="logout()">退出</button>
        </div>
    </form>
</div>

<!-- 底部显示 -->
<div class="footer">
    <a href="https://github.com/taksssss/PHP-EPG-Docker-Server" style="color: #888; text-decoration: none;">https://github.com/taksssss/PHP-EPG-Docker-Server</a>
</div>

<!-- 配置更新模态框 -->
<div id="myModal" class="modal">
    <div class="modal-content config-modal-content">
        <span class="close">&times;</span>
        <p id="modalMessage"></p>
    </div>
</div>

<!-- 更新日志模态框 -->
<div id="updatelogModal" class="modal">
    <div class="modal-content update-log-modal-content">
        <span class="close">&times;</span>
        <h2>数据库更新日志</h2>
        <div class="table-container" id="log-table-container">
            <table id="logTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>时间</th>
                        <th>描述</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据由 JavaScript 动态生成 -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 定时任务日志模态框 -->
<div id="cronlogModal" class="modal">
    <div class="modal-content cron-log-modal-content">
        <span class="close">&times;</span>
        <h2>定时任务日志</h2>
        <textarea id="cronLogContent" readonly style="width: 100%; height: 440px;"></textarea>
    </div>
</div>

<!-- 频道列表模态框 -->
<div id="channelModal" class="modal">
    <div class="modal-content channel-modal-content">
        <span class="close">&times;</span>
        <h2 id="channelModalTitle">频道列表</h2>
        <input type="text" id="channelSearchInput" placeholder="搜索频道名..." onkeyup="filterChannels('channel')">
        <div class="table-container" id="channel-table-container">
            <table id="channelTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>数据库频道名</th>
                        <th>频道别名</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据由 JavaScript 动态生成 -->
                </tbody>
            </table>
        </div>
        <br>
        <button id="saveConfig" type="button" onclick="updateChannelMapping();">保存配置</button>
    </div>
</div>

<!-- 台标列表模态框 -->
<div id="iconModal" class="modal">
    <div class="modal-content icon-modal-content">
        <span class="close">&times;</span>
        <h2 id="iconModalTitle">频道列表</h2>
        <div style="display: flex;">
            <input type="text" id="iconSearchInput" placeholder="搜索频道名..." onkeyup="filterChannels('icon')" style="flex: 1; margin-right: 10px;">
            <div class="tooltip" style="width:auto; margin-right: 10px;">
                <input type="file" name="m3utxtFile" id="m3utxtFile" style="display: none;" accept=".m3u, .txt">
                <button id="m3uMatchIcons" type="button" onclick="document.getElementById('m3utxtFile').click()">M3U</button>
                <span class="tooltiptext">上传 m3u/txt 文件<br>匹配 EPG 及台标</span>
            </div>
            <div class="tooltip" style="width:auto; margin-right: 10px;">
                <button id="deleteUnusedIcons" type="button" onclick="deleteUnusedIcons()">清理</button>
                <span class="tooltiptext">清理未在列表中<br>使用的台标文件</span>
            </div>
            <div class="tooltip" style="width:auto; margin-right: 10px;">
                <button id="showAllIcons" type="button" onclick="showModal('allicon')">全显</button>
                <span class="tooltiptext">同时显示<br>无节目表内置台标</span>
            </div>
            <div class="tooltip" style="width:auto;">
                <button id="uploadAllIcons" type="button" onclick="uploadAllIcons();">转存</button>
                <span class="tooltiptext">将远程台标<br>转存到服务器</span>
            </div>
        </div>
        <div class="table-container" id="icon-table-container">
            <table id="iconTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>数据库频道名</th>
                        <th>台标地址</th>
                        <th>台标</th>
                        <th>上传</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据由 JavaScript 动态生成 -->
                </tbody>
            </table>
        </div>
        <br>
        <button id="saveConfig" type="button" onclick="saveAndUpdateConfig();">保存配置</button>
    </div>
</div>

<!-- 频道指定EPG模态框 -->
<div id="channelBindEPGModal" class="modal">
    <div class="modal-content channel-bind-epg-modal-content">
        <span class="close">&times;</span>
        <h2>频道指定EPG源<span style="font-size: 14px;">（无指定则按靠前的源更新）</span></h2>
        <div class="table-container" id="channel-bind-epg-table-container">
            <table id="channelBindEPGTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>指定EPG源</th>
                        <th>频道（可 , 分隔）</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据由 JavaScript 动态生成 -->
                </tbody>
            </table>
        </div>
        <br>
        <button id="saveConfig" type="button" onclick="saveAndUpdateConfig();">保存配置</button>
    </div>
</div>

<!-- 频道匹配结果模态框 -->
<div id="channelMatchModal" class="modal">
    <div class="modal-content channel-match-modal-content">
        <span class="close">&times;</span>
        <h2>频道匹配结果</h2>
        <div class="table-container" id="channel-match-table-container">
            <table id="channelMatchTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>原频道名</th>
                        <th>处理后频道名</th>
                        <th>匹配结果</th>
                        <th>备注</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据由 JavaScript 动态生成 -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 更多设置模态框 -->
<div id="moreSettingModal" class="modal">
    <div class="modal-content more-setting-modal-content">
        <span class="close">&times;</span>
        <h2>更多设置</h2>
        <label for="gen_xml">生成 xmltv 文件：</label>
        <select id="gen_xml" name="gen_xml" required>
            <option value="1" <?php if ($Config['gen_xml'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if ($Config['gen_xml'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <label for="include_future_only">生成方式：</label>
        <select id="include_future_only" name="include_future_only" required>
            <option value="1" <?php if ($Config['include_future_only'] == 1) echo 'selected'; ?>>仅预告数据</option>
            <option value="0" <?php if ($Config['include_future_only'] == 0) echo 'selected'; ?>>所有数据</option>
        </select>
        <br><br>
        <label for="ret_default">默认返回“精彩节目”：</label>
        <select id="ret_default" name="ret_default" required>
            <option value="1" <?php if (!isset($Config['ret_default']) || $Config['ret_default'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if (isset($Config['ret_default']) && $Config['ret_default'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <form id="importForm" method="post" enctype="multipart/form-data" style="display: inline-block;">
            <input type="file" name="importFile" id="importFile" style="display: none;" accept=".gz" onchange="document.getElementById('importForm').submit();">
            <input type="hidden" name="importExport" id="formImportExport" value="">
            <span id="import" onclick="document.getElementById('importFile').click()" style="color: blue; cursor: pointer; margin-right: 20px;">数据导入</span>
            <span id="export" onclick="document.getElementById('importForm').submit()" style="color: blue; cursor: pointer;">数据导出</span>
        </form>
        <br><br>
        <label for="cache_time">缓存时间：</label>
        <select id="cache_time" name="cache_time" required>
            <?php for ($h = 0; $h < 24; $h++): ?>
                <option value="<?php echo $h; ?>" <?php echo floor($Config['cache_time'] / 3600) == $h ? 'selected' : ''; ?>>
                    <?php echo $h; ?>
                </option>
            <?php endfor; ?>
        </select> 小时
        <label for="db_type" style="margin-left: 20px;">数据库：</label>
        <select id="db_type" name="db_type" required>
            <option value="sqlite" <?php if (!isset($Config['db_type']) || $Config['db_type'] == 'sqlite') echo 'selected'; ?>>SQLite</option>
            <option value="mysql" <?php if (isset($Config['db_type']) && $Config['db_type'] == 'mysql') echo 'selected'; ?>>MySQL</option>
        </select>
        <label for="mysql_host">地址：</label>
        <textarea id="mysql_host"><?php echo htmlspecialchars($Config['mysql']['host'] ?? ''); ?></textarea>
        <br><br>
        <label for="mysql_dbname">数据库名：</label>
        <textarea id="mysql_dbname"><?php echo htmlspecialchars($Config['mysql']['dbname'] ?? ''); ?></textarea>
        <label for="mysql_username">用户名：</label>
        <textarea id="mysql_username"><?php echo htmlspecialchars($Config['mysql']['username'] ?? ''); ?></textarea>
        <label for="mysql_password">密码：</label>
        <textarea id="mysql_password"><?php echo htmlspecialchars($Config['mysql']['password'] ?? ''); ?></textarea>
        <br><br>
        <label for="gen_list_text">仅生成以下频道：</label>
        <select id="gen_list_enable" name="gen_list_enable" style="width: 48px; margin-right: 0px;" required>
            <option value="1" <?php if (isset($Config['gen_list_enable']) && $Config['gen_list_enable'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if (!isset($Config['gen_list_enable']) || $Config['gen_list_enable'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <span>
            （粘贴m3u、txt地址或内容，<span onclick="parseSource()" style="color: blue; cursor: pointer; text-decoration: underline;">解析</span> 后
            <span onclick="showModal('channelmatch')" style="color: blue; cursor: pointer; text-decoration: underline;">查看匹配</span>）
        </span><br><br>
        <textarea id="gen_list_text"></textarea><br><br>
        <button id="saveConfig" type="button" onclick="saveAndUpdateConfig();">保存配置</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 页面加载时执行，预加载数据，减少等待时间
        showModal('channelbindepg', $popup = false); // 这一行必须有，否则保存时丢失数据
        showModal('moresetting', $popup = false); // 这一行必须有，否则保存时丢失数据
        showModal('update', $popup = false);
        showModal('cron', $popup = false);
        showModal('channel', $popup = false);
    });

    function handleDbManagement() {
        if (document.getElementById('db_type').value === 'mysql') {
            var img = new Image();
            var timeout = setTimeout(function() {img.onerror();}, 1000); // 设置 1 秒超时
            img.onload = function() {
                clearTimeout(timeout); // 清除超时
                window.open('http://' + window.location.hostname + ':8080', '_blank');
            };
            img.onerror = function() {
                clearTimeout(timeout); // 清除超时
                alert('无法访问 phpMyAdmin 8080 端口，请自行使用 MySQL 管理工具进行管理。');
            };
            img.src = 'http://' + window.location.hostname + ':8080/favicon.ico'; // 测试 8080 端口
            return false;
        }
        return true; // 如果不是 MySQL，正常跳转
    }

    // 退出登录
    function logout() {
        // 清除所有cookies
        document.cookie.split(";").forEach(function(cookie) {
            var name = cookie.split("=")[0].trim();
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
        });
        // 清除本地存储
        sessionStorage.clear();
        // 重定向到登录页面
        window.location.href = 'manage.php';
    }

    // Ctrl+S 保存设置
    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && event.key === "s") {
            event.preventDefault(); // 阻止默认行为，如保存页面
            saveAndUpdateConfig();
        }
    });

    // Ctrl+/ 设置（取消）注释
    document.getElementById('xml_urls').addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.key === '/') {
            event.preventDefault();
            const textarea = this;
            const { selectionStart, selectionEnd, value } = textarea;
            const lines = value.split('\n');
            // 计算当前选中的行
            const startLine = value.slice(0, selectionStart).split('\n').length - 1;
            const endLine = value.slice(0, selectionEnd).split('\n').length - 1;
            // 判断选中的行是否都已注释
            const allCommented = lines.slice(startLine, endLine + 1).every(line => line.trim().startsWith('#'));
            const newLines = lines.map((line, index) => {
                if (index >= startLine && index <= endLine) {
                    return allCommented ? line.replace(/^#\s*/, '') : '# ' + line;
                }
                return line;
            });
            // 更新 textarea 的内容
            textarea.value = newLines.join('\n');
            // 检查光标开始位置是否在行首
            const startLineStartIndex = value.lastIndexOf('\n', selectionStart - 1) + 1;
            const isStartInLineStart = (selectionStart - startLineStartIndex < 2);
            // 检查光标结束位置是否在行首
            const endLineStartIndex = value.lastIndexOf('\n', selectionEnd - 1) + 1;
            const isEndInLineStart = (selectionEnd - endLineStartIndex < 2);
            // 计算光标新的开始位置
            const newSelectionStart = isStartInLineStart 
                ? startLineStartIndex
                : selectionStart + newLines[startLine].length - lines[startLine].length;
            // 计算光标新的结束位置
            const lengthDiff = newLines.join('').length - lines.join('').length;
            const endLineDiff = newLines[endLine].length - lines[endLine].length;
            const newSelectionEnd = isEndInLineStart
                ? (endLineDiff > 0 ? endLineStartIndex + lengthDiff : endLineStartIndex + lengthDiff - endLineDiff)
                : selectionEnd + lengthDiff;
            // 恢复光标位置
            textarea.setSelectionRange(newSelectionStart, newSelectionEnd);
        }
    });

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        return `${hours}小时${minutes}分钟`;
    }

    function updateMySQLFields() {
        var dbType = document.getElementById('db_type').value;
        var isSQLite = (dbType === 'sqlite');
        document.getElementById('mysql_host').disabled = isSQLite;
        document.getElementById('mysql_dbname').disabled = isSQLite;
        document.getElementById('mysql_username').disabled = isSQLite;
        document.getElementById('mysql_password').disabled = isSQLite;
    }

    var configUpdated = <?php echo json_encode($configUpdated); ?>;
    var intervalTime = "<?php echo $Config['interval_time']; ?>";
    var startTime = "<?php echo $Config['start_time']; ?>";
    var endTime = "<?php echo $Config['end_time']; ?>";
    var importMessage = <?php echo json_encode($importMessage); ?>;

    function displayModal(message) {
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var modalMessage = document.getElementById("modalMessage");

        modalMessage.innerHTML = message;
        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    }

    if (configUpdated) {
        var message;
        if (intervalTime === "0") {
            message = "配置已更新<br><br>已取消定时任务";
        } else {
            message = `配置已更新<br><br>已设置定时任务<br>开始时间：${startTime}<br>结束时间：${endTime}<br>间隔周期：${formatTime(intervalTime)}`;
        }
        displayModal(message);
    }

    if (importMessage) {
        displayModal(importMessage);
    }

    function showModal(type, $popup = true) {
        var modal, logSpan, logContent;
        switch (type) {
            case 'update':
                modal = document.getElementById("updatelogModal");
                logSpan = document.getElementsByClassName("close")[1];
                fetchData('manage.php?get_update_logs=true', updateLogTable);
                break;
            case 'cron':
                modal = document.getElementById("cronlogModal");
                logSpan = document.getElementsByClassName("close")[2];
                fetchData('manage.php?get_cron_logs=true', updateCronLogContent);
                break;
            case 'channel':
                modal = document.getElementById("channelModal");
                logSpan = document.getElementsByClassName("close")[3];
                fetchData('manage.php?get_channel=true', updateChannelList);
                break;
            case 'icon':
                modal = document.getElementById("iconModal");
                logSpan = document.getElementsByClassName("close")[4];
                fetchData('manage.php?get_icon=true', updateIconList);
                break;
            case 'allicon':
                modal = document.getElementById("iconModal");
                logSpan = document.getElementsByClassName("close")[4];
                fetchData('manage.php?get_icon=true&get_all_icon=true', updateIconList);
                break;
            case 'channelbindepg':
                modal = document.getElementById("channelBindEPGModal");
                logSpan = document.getElementsByClassName("close")[5];
                fetchData('manage.php?get_channel_bind_epg=true', updateChannelBindEPGList);
                break;
            case 'channelmatch':
                modal = document.getElementById("channelMatchModal");
                logSpan = document.getElementsByClassName("close")[6];
                fetchData('manage.php?get_channel_match=true', updateChannelMatchList);
                document.getElementById("moreSettingModal").style.display = "none";
                break;
            case 'moresetting':
                // 设置 MySQL 相关输入框状态
                updateMySQLFields();
                document.getElementById('db_type').addEventListener('change', updateMySQLFields);
                modal = document.getElementById("moreSettingModal");
                logSpan = document.getElementsByClassName("close")[7];
                fetchData('manage.php?get_gen_list=true', updateGenList);
                break;
            default:
                console.error('Unknown type:', type);
                break;
        }
        if (!$popup) {
            return;
        }
        modal.style.display = "block";
        logSpan.onclick = function() {
            modal.style.display = "none";
            if (type === 'channelmatch') {
                showModal('moresetting');
            }
        }
        window.onmousedown = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
                if (type === 'channelmatch') {
                    showModal('moresetting');
                }
            }
        }
    }

    function fetchData(endpoint, callback) {
        fetch(endpoint)
            .then(response => response.json())
            .then(data => callback(data))
            .catch(error => {
                console.error('Error fetching log:', error);
                callback([]);
            });
    }

    function updateLogTable(logData) {
        var logTableBody = document.querySelector("#logTable tbody");
        logTableBody.innerHTML = '';

        logData.forEach(log => {
            var row = document.createElement("tr");
            row.innerHTML = `
                <td>${new Date(log.timestamp).toLocaleString('zh-CN', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false })}</td>
                <td>${log.log_message}</td>
            `;
            logTableBody.appendChild(row);
        });
        var logTableContainer = document.getElementById("log-table-container");
        logTableContainer.scrollTop = logTableContainer.scrollHeight;
    }

    function updateCronLogContent(logData) {
        var logContent = document.getElementById("cronLogContent");
        logContent.value = logData.map(log => `[${new Date(log.timestamp).toLocaleString('zh-CN', {month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false })}] ${log.log_message}`).join('\n');
        logContent.scrollTop = logContent.scrollHeight;
    }

    // 显示频道别名列表
    function updateChannelList(channelsData) {
        const channelTitle = document.getElementById('channelModalTitle');
        channelTitle.innerHTML = `频道列表<span style="font-size: 18px;">（总数：${channelsData.count}）</span>`; // 更新频道总数
        document.getElementById('channelTable').dataset.allChannels = JSON.stringify(channelsData.channels); // 将原始频道和映射后的频道数据存储到 dataset 中
        filterChannels('channel'); // 生成数据
    }

    // 显示台标列表
    function updateIconList(iconsData) {
        const channelTitle = document.getElementById('iconModalTitle');
        channelTitle.innerHTML = `频道列表<span style="font-size: 18px;">（总数：${iconsData.count}）</span>`; // 更新频道总数
        document.getElementById('iconTable').dataset.allIcons = JSON.stringify(iconsData.channels); // 将频道名和台标地址存储到 dataset 中
        filterChannels('icon'); // 生成数据
    }
    
    // 显示频道绑定 EPG 列表
    function updateChannelBindEPGList(channelBindEPGData) {
        // 创建并添加隐藏字段
        const channelBindEPGInput = document.createElement('input');
        channelBindEPGInput.type = 'hidden';
        channelBindEPGInput.name = 'channel_bind_epg';
        document.getElementById('settingsForm').appendChild(channelBindEPGInput);

        document.getElementById('channelBindEPGTable').dataset.allChannelBindEPG = JSON.stringify(channelBindEPGData);
        var channelBindEPGTableBody = document.querySelector("#channelBindEPGTable tbody");
        var allChannelBindEPG = JSON.parse(document.getElementById('channelBindEPGTable').dataset.allChannelBindEPG);
        channelBindEPGInput.value = JSON.stringify(allChannelBindEPG);

        // 清空现有表格
        channelBindEPGTableBody.innerHTML = '';

        allChannelBindEPG.forEach(channelbindepg => {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${String(channelbindepg.epg_src)}</td>
                <td contenteditable="true">${channelbindepg.channels}</td>
            `;

            row.querySelector('td[contenteditable]').addEventListener('input', function() {
                channelbindepg.channels = this.textContent;
                document.getElementById('channelBindEPGTable').dataset.allChannelBindEPG = JSON.stringify(allChannelBindEPG);
                channelBindEPGInput.value = JSON.stringify(allChannelBindEPG);
            });

            channelBindEPGTableBody.appendChild(row);
        });
    }

    // 显示频道匹配结果
    function updateChannelMatchList(channelMatchdata) {
        const channelMatchTableBody = document.querySelector("#channelMatchTable tbody");
        channelMatchTableBody.innerHTML = '';

        const typeOrder = { '未匹配': 1, '反向模糊': 2, '正向模糊': 3, '别名/忽略': 4, '精确匹配': 5 };

        // 处理并排序匹配数据
        const sortedMatches = Object.values(channelMatchdata)
            .flat()
            .sort((a, b) => typeOrder[a.type] - typeOrder[b.type]);

        // 创建表格行
        sortedMatches.forEach(({ ori_channel, clean_channel, match, type }) => {
            const matchType = type === '精确匹配' ? '' : type;
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${ori_channel}</td>
                <td>${clean_channel}</td>
                <td>${match || ''}</td>
                <td>${matchType}</td>
            `;
            channelMatchTableBody.appendChild(row);
        });

        document.getElementById("channel-match-table-container").style.display = 'block';
    }

    function updateGenList(genData) {
        const gen_list_text = document.getElementById('gen_list_text');
        if(!gen_list_text.value) {
            gen_list_text.value = genData.join('\n');
        }
    }

    // 搜索频道
    function filterChannels(type) {
        const tableId = type === 'channel' ? 'channelTable' : 'iconTable';
        const dataAttr = type === 'channel' ? 'allChannels' : 'allIcons';
        const input = document.getElementById(type === 'channel' ? 'channelSearchInput' : 'iconSearchInput').value.toUpperCase();
        const tableBody = document.querySelector(`#${tableId} tbody`);
        const allData = JSON.parse(document.getElementById(tableId).dataset[dataAttr]);

        tableBody.innerHTML = ''; // 清空表格

        // 创建行的通用函数
        function createEditableRow(item, itemIndex, insertAfterRow = null) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td name="channel" contenteditable="true" onclick="this.innerText='';"><span style="color: #aaa;">创建自定义频道</span>${item.channel || ''}</td>
                <td name="icon" contenteditable="true">${item.icon || ''}</td>
                <td></td>
                <td>
                    <input type="file" accept="image/png" style="display:none;" id="icon_new_${itemIndex}">
                    <button onclick="document.getElementById('icon_new_${itemIndex}').click()" style="font-size: 14px; width: 50px;">上传</button>
                </td>
            `;
            
            // 动态更新 allData
            row.querySelectorAll('td[contenteditable]').forEach(cell => {
                cell.addEventListener('input', () => {
                    allData[itemIndex][cell.getAttribute('name')] = cell.textContent.trim();
                    document.getElementById(tableId).dataset[dataAttr] = JSON.stringify(allData);
                    if (cell.getAttribute('name') === 'channel' && item.channel && !allData.some(e => !e.channel)) {
                        allData.push({ channel: '', icon: '' });
                        createEditableRow(allData[allData.length - 1], allData.length - 1, row); // 插入新行到当前行后
                    }
                });
            });

            // 上传文件
            row.querySelector(`#icon_new_${itemIndex}`).addEventListener('change', event => handleIconFileUpload(event, item, row, allData));

            // 如果指定了插入位置，则插入到该行之后，否则追加到表格末尾
            if (insertAfterRow) {
                insertAfterRow.insertAdjacentElement('afterend', row);
            } else {
                tableBody.appendChild(row);
            }
        }

        // 创建初始空行（仅用于 icon）
        if (!input && type === 'icon') {
            allData.push({ channel: '', icon: '' });
            createEditableRow(allData[allData.length - 1], allData.length - 1);
        }

        // 筛选并显示行的逻辑
        allData.forEach((item, index) => {
            const searchText = type === 'channel' ? item.original : item.channel;
            if (String(searchText).toUpperCase().includes(input)) {
                const row = document.createElement('tr');
                if (type === 'channel') {
                    row.innerHTML = `<td>${item.original}</td><td contenteditable="true">${item.mapped || ''}</td>`;
                    row.querySelector('td[contenteditable]').addEventListener('input', function() {
                        item.mapped = this.textContent.trim();
                        document.getElementById(tableId).dataset[dataAttr] = JSON.stringify(allData);
                    });
                } else if (type === 'icon' && searchText) {
                    row.innerHTML = `
                        <td contenteditable="true">${item.channel}</td>
                        <td contenteditable="true">${item.icon || ''}</td>
                        <td>${item.icon ? `<a href="${item.icon}" target="_blank"><img src="${item.icon}" style="max-width: 80px; max-height: 50px; background-color: #ccc;"></a>` : ''}</td>
                        <td>
                            <input type="file" accept="image/png" style="display:none;" id="file_${index}">
                            <button onclick="document.getElementById('file_${index}').click()" style="font-size: 14px; width: 50px;">上传</button>
                        </td>
                    `;
                    row.querySelectorAll('td[contenteditable]').forEach((cell, idx) => {
                        cell.addEventListener('input', function() {
                            if (idx === 0) item.channel = this.textContent.trim();  // 第一个可编辑单元格更新 channel
                            else item.icon = this.textContent.trim();  // 第二个可编辑单元格更新 icon
                            document.getElementById(tableId).dataset[dataAttr] = JSON.stringify(allData);
                        });
                    });
                    row.querySelector(`#file_${index}`).addEventListener('change', event => handleIconFileUpload(event, item, row, allData));
                }
                tableBody.appendChild(row);
            }
        });
    }

    // 台标上传
    function handleIconFileUpload(event, item, row, allData) {
        const file = event.target.files[0];
        if (file && file.type === 'image/png') {
            const formData = new FormData();
            formData.append('iconFile', file);

            fetch('manage.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const iconUrl = data.iconUrl;
                        row.cells[1].innerText = iconUrl;
                        item.icon = iconUrl;
                        row.cells[2].innerHTML = `
                            <a href="${iconUrl}?${new Date().getTime()}" target="_blank">
                                <img src="${iconUrl}?${new Date().getTime()}" style="max-width: 80px; max-height: 50px; background-color: #ccc;">
                            </a>
                        `;
                        document.getElementById('iconTable').dataset.allIcons = JSON.stringify(allData);
                    } else {
                        alert('上传失败：' + data.message);
                    }
                })
                .catch(error => alert('上传过程中发生错误：' + error));
        } else {
            alert('请选择PNG文件上传');
        }
    }

    // 转存所有台标到服务器
    function uploadAllIcons() {
        const serverUrl = window.location.origin;
        const iconTable = document.getElementById('iconTable');
        const allIcons = JSON.parse(iconTable.dataset.allIcons);
        const rows = Array.from(document.querySelectorAll('#iconTable tbody tr'));

        let totalIcons = 0;
        let uploadedIcons = 0;
        const rowsToUpload = rows.filter(row => {
            const iconUrl = row.cells[1]?.innerText.trim();
            if (iconUrl) {
                totalIcons++;
                if (!iconUrl.startsWith(serverUrl)) {
                    return true;
                } else {
                    uploadedIcons++;
                }
            }
            return false;
        });

        const progressDisplay = document.getElementById('progressDisplay') || document.createElement('div');
        progressDisplay.id = 'progressDisplay';
        progressDisplay.style.cssText = 'margin: 10px 0; text-align: right;';
        progressDisplay.textContent = `已转存 ${uploadedIcons}/${totalIcons}`;
        iconTable.before(progressDisplay);

        const uploadPromises = rowsToUpload.map(row => {
            const [channelCell, iconCell, previewCell] = row.cells;
            const iconUrl = iconCell?.innerText.trim();
            const fileName = decodeURIComponent(iconUrl.split('/').pop().split('?')[0]);

            return fetch(iconUrl)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('iconFile', new File([blob], fileName, { type: 'image/png' }));

                    return fetch('manage.php', { method: 'POST', body: formData });
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const iconUrl = data.iconUrl;
                        const channelName = channelCell.innerText.trim();
                        iconCell.innerText = iconUrl;
                        previewCell.innerHTML = `
                            <a href="${iconUrl}?${Date.now()}" target="_blank">
                                <img src="${iconUrl}?${Date.now()}" style="max-width: 80px; max-height: 50px; background-color: #ccc;">
                            </a>
                        `;

                        allIcons.forEach(item => {
                            if (item.channel === channelName) item.icon = iconUrl;
                        });
                        iconTable.dataset.allIcons = JSON.stringify(allIcons);
                        uploadedIcons++;
                        progressDisplay.textContent = `已转存 ${uploadedIcons}/${totalIcons}`;
                    } else {
                        previewCell.innerHTML = `上传失败: ${data.message}`;
                    }
                })
                .catch(() => {
                    previewCell.innerHTML = '上传出错';
                });
        });

        Promise.all(uploadPromises).then(() => {
            if (uploadedIcons !== totalIcons) {
                uploadAllIcons(); // 继续上传
            }
            else {
                progressDisplay.textContent = "全部转存成功，点击“保存配置”！";
            }
        });
    }

    // 清理未使用的台标文件
    function deleteUnusedIcons() {
        fetch('manage.php?delete_unused_icons=true')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('清理失败');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // 上传 m3u/txt 文件匹配台标
    document.getElementById('m3utxtFile').addEventListener('change', function() {
        const file = this.files[0];
        const allowedExtensions = ['m3u', 'txt'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        // 检查文件类型
        if (!allowedExtensions.includes(fileExtension)) {
            alert('只接受 .m3u 和 .txt 文件');
            return;
        }

        // 创建 FormData 并发送 AJAX 请求
        const formData = new FormData();
        formData.append('m3utxtFile', file);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'manage.php', true);
        xhr.responseType = 'blob';

        xhr.onload = function() {
            if (xhr.status === 200) {
                // 创建下载链接并自动触发下载
                const url = window.URL.createObjectURL(xhr.response);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'tv.m3u'; // 生成文件名
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            } else {
                alert('文件处理失败');
            }
        };

        xhr.send(formData);
    });

    // 更新频道别名
    function updateChannelMapping() {
        var allChannels = JSON.parse(document.getElementById('channelTable').dataset.allChannels);
        var existingMappings = document.getElementById('channel_mappings').value.split('\n');

        // 过滤出现有映射中的正则表达式映射
        var regexMappings = existingMappings.filter(line => line.includes('regex:'));

        // 生成新的频道别名映射
        var newMappings = allChannels
            .filter(channel => channel.mapped.trim() !== '')
            .map(channel => `${channel.original} => ${channel.mapped}`);

        // 更新映射文本框并保存配置
        document.getElementById('channel_mappings').value = [...newMappings, ...regexMappings].join('\n');
        saveAndUpdateConfig();
    }

    // 解析 txt、m3u 直播源，并生成频道列表
    async function parseSource() {
        const textarea = document.getElementById('gen_list_text');
        let text = textarea.value.trim();
        const channels = new Set();

        // 拆分输入的内容，可能包含多个 URL 或文本
        if(!text.includes('#EXTM3U')) {
            let lines = text.split('\n').map(line => line.trim());
            let urls = lines.filter(line => line.startsWith('http'));

            // 如果存在 URL，则清空原本的 text 内容并逐个请求获取数据
            if (urls.length > 0) {
                text = '';
                for (let url of urls) {
                    try {
                        const response = await fetch('manage.php?url=' + encodeURIComponent(url));
                        const result = await response.json(); // 解析 JSON 响应
                        
                        if (result.success && !/not found/i.test(result.data)) {
                            text += '\n' + result.data;
                        } else {
                            alert(/not found/i.test(result.data) ? `Error: ${result.data}` : `${result.message}：\n${url}`);
                        }
                    } catch (error) {
                        alert(`无法获取URL内容: ${url}\n错误信息: ${error.message}`); // 显示网络错误信息
                    }
                }
            }
        }

        // 处理 m3u 、 txt 文件内容
        text.split('\n').forEach(line => {
            if (line && !/^http/i.test(line) && !/#genre#/i.test(line) && !/#extm3u/i.test(line)) {
                if (/^#extinf:/i.test(line)) {
                    const tvgIdMatch = line.match(/tvg-id="([^"]+)"/i);
                    const tvgNameMatch = line.match(/tvg-name="([^"]+)"/i);

                    name = (tvgIdMatch && /\D/.test(tvgIdMatch[1]) ? tvgIdMatch[1] : tvgNameMatch ? tvgNameMatch[1] : line.split(',').slice(-1)[0]).trim();
                } else {
                    name = line.split(',')[0].trim();
                }
                if (name) channels.add(name);
            }
        });

        // 将解析后的频道列表放回文本区域
        textarea.value = Array.from(channels).join('\n');
        
        // 保存到数据库
        saveAndUpdateConfig($doUpdate = false);
    }

    // 保存数据并更新配置
    function saveAndUpdateConfig($doUpdate = true) {
        var iconTableElement = document.getElementById('iconTable');
        var allIcons = iconTableElement && iconTableElement.dataset.allIcons ? JSON.parse(iconTableElement.dataset.allIcons) : null;
        if(allIcons) {
            fetch('manage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    update_icon_list: true,
                    updatedIcons: JSON.stringify(allIcons) // 传递更新后的图标数据
                })
            });
        }

        const textAreaContent = document.getElementById('gen_list_text').value;
        fetch('manage.php?set_gen_list=true', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ data: textAreaContent })
        })
        .then(response => response.text())
        .then(responseText => {
            if (responseText.trim() === 'success') {
                if($doUpdate){
                    document.getElementById('updateConfig').click();
                }
            } else {
                console.error('服务器响应错误:', responseText);
            }
        })
        .catch(error => {
            console.error('请求失败:', error);
        });
    }

    // 在提交表单时，将更多设置中的数据包括在表单数据中
    document.getElementById('settingsForm').addEventListener('submit', function() {
        const fields = ['gen_xml', 'include_future_only', 'ret_default', 'gen_list_enable', 
                        'cache_time', 'db_type', 'mysql_host', 'mysql_dbname', 'mysql_username', 'mysql_password'];
        fields.forEach(function(field) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = field;
            hiddenInput.value = document.getElementById(field).value;
            this.appendChild(hiddenInput);
        }, this);
    });
    
</script>
</body>
</html>