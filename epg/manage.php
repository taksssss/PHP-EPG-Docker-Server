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
    <html lang="zh-CN">
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
    $tvmao_default = isset($_POST['tvmao_default']) ? intval($_POST['tvmao_default']) : $Config['tvmao_default'];
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
        'tvmao_default' => $tvmao_default,
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
?>
<!DOCTYPE html>
<html lang="zh-CN">
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
猫示例：tvmao, 猫频道名1, 自定义频道名:猫频道名2, ..." id="xml_urls" name="xml_urls" style="height: 122px;"><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

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
        <label for="gen_xml">生成 xml 文件：</label>
        <select id="gen_xml" name="gen_xml" required>
            <option value="1" <?php if ($Config['gen_xml'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if ($Config['gen_xml'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <label for="include_future_only">内容：</label>
        <select id="include_future_only" name="include_future_only" required>
            <option value="1" <?php if ($Config['include_future_only'] == 1) echo 'selected'; ?>>预告数据</option>
            <option value="0" <?php if ($Config['include_future_only'] == 0) echo 'selected'; ?>>所有数据</option>
        </select>
        <form id="importForm" method="post" enctype="multipart/form-data" style="display: inline-block;">
            <input type="file" name="importFile" id="importFile" style="display: none;" accept=".gz" onchange="document.getElementById('importForm').submit();">
            <input type="hidden" name="importExport" id="formImportExport" value="">
            <span id="import" onclick="document.getElementById('importFile').click()" style="color: blue; cursor: pointer; margin-right: 20px;">数据导入</span>
            <span id="export" onclick="document.getElementById('importForm').submit()" style="color: blue; cursor: pointer;">数据导出</span>
        </form>
        <br><br>
        <label for="ret_default">返回精彩节目：</label>
        <select id="ret_default" name="ret_default" required>
            <option value="1" <?php if (!isset($Config['ret_default']) || $Config['ret_default'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if (isset($Config['ret_default']) && $Config['ret_default'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <label for="tvmao_default" title="尝试使用 猫 接口补充预告数据">补充预告：</label>
        <select id="tvmao_default" name="tvmao_default" required>
            <option value="1" <?php if (isset($Config['tvmao_default']) && $Config['tvmao_default'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if (!isset($Config['tvmao_default']) || $Config['tvmao_default'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <br><br>
        <label for="cache_time">缓存时间：</label>
        <select id="cache_time" name="cache_time" required>
            <?php for ($h = 0; $h < 24; $h++): ?>
                <option value="<?php echo $h; ?>" <?php echo floor($Config['cache_time'] / 3600) == $h ? 'selected' : ''; ?>>
                    <?php echo $h; ?>
                </option>
            <?php endfor; ?>
        </select> 小时
        <label for="db_type" style="margin-left: 12px;">数据库：</label>
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
    var configUpdated = <?php echo json_encode($configUpdated); ?>;
    var intervalTime = <?php echo json_encode($Config['interval_time']); ?>;
    var startTime = <?php echo json_encode($Config['start_time']); ?>;
    var endTime = <?php echo json_encode($Config['end_time']); ?>;
    var importMessage = <?php echo json_encode($importMessage); ?>;
</script>
<script src="js/manage.js"></script>
</body>
</html>