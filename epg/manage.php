<?php
/**
 * @file manage.php
 * @brief 管理页面部分
 * 
 * 管理界面脚本，用于处理会话管理、密码更改、登录验证、配置更新、更新日志展示等功能。
 * 修复原作者一点小小的语法错误和增加一个退出按钮方便操作，使用php的session_destroy();
 * 
 * 作者: Tak
 * GitHub: https://github.com/TakcC/PHP-EPG-Docker-Server
 * 修改: mxdabc
 * Github: https://github.com/mxdabc/epgphp
 */

// 引入公共脚本
require_once 'public.php';

session_start();

// 设置会话变量，表明用户可以访问 phpliteadmin.php
$_SESSION['can_access_phpliteadmin'] = true;

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
            <a href="https://github.com/TakcC/PHP-EPG-Docker-Server" style="color: #888; text-decoration: none;">https://github.com/TakcC/PHP-EPG-Docker-Server</a>
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
    $xml_urls = array_map('trim', explode("\n", trim($_POST['xml_urls'])));

    // 过滤和规范化 xml_urls
    $xml_urls = array_values(array_map(function($url) {
        return preg_replace('/^#\s*(?:#\s*)*(\S+)(\s*#.*)?$/', '# $1$2', trim($url));
    }, $xml_urls));
    
    $days_to_keep = intval($_POST['days_to_keep']);
    $gen_xml = isset($_POST['gen_xml']) ? intval($_POST['gen_xml']) : $Config['gen_xml'];
    $include_future_only = isset($_POST['include_future_only']) ? intval($_POST['include_future_only']) : $Config['include_future_only'];
    $ret_default = isset($_POST['ret_default']) ? intval($_POST['ret_default']) : $Config['ret_default'];
    $gen_list_enable = isset($_POST['gen_list_enable']) ? intval($_POST['gen_list_enable']) : $Config['gen_list_enable'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    
    // 处理间隔时间
    $interval_hour = intval($_POST['interval_hour']);
    $interval_minute = intval($_POST['interval_minute']);
    $interval_time = $interval_hour * 3600 + $interval_minute * 60;

    // 处理频道替换
    $channel_replacements = array_map('trim', explode("\n", trim($_POST['channel_replacements'])));

    // 处理频道映射
    $channel_mappings = [];
    if ($mappings = trim($_POST['channel_mappings'] ?? '')) {
        foreach (array_filter(array_map('trim', explode("\n", $mappings))) as $line) {
            list($search, $replace) = preg_split('/=》|=>/', $line);
            $channel_mappings[trim(str_replace("，", ",", trim($search)), '[]')] = trim($replace);
        }
    }

    // 处理频道指定 EPG 数据，去掉 epg_src 前面的【已停用】
    $channel_bind_epg = isset($_POST['channel_bind_epg']) ? 
        array_filter(
            array_reduce(json_decode($_POST['channel_bind_epg'], true), function($result, $item) {
                $epgSrc = preg_replace('/^【已停用】/', '', $item['epg_src']);
                if (!empty($item['channels'])) {
                    $result[$epgSrc] = $item['channels'];
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
        'start_time' => $start_time,
        'end_time' => $end_time,
        'interval_time' => $interval_time,
        'manage_password' => $Config['manage_password'], // 保留密码
        'channel_bind_epg' => $channel_bind_epg,
        'channel_replacements' => $channel_replacements,
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
        // 返回更新日志数据
        if (isset($_GET['get_update_logs'])) {
            $dbResponse = $db->query("SELECT * FROM update_log")->fetchAll(PDO::FETCH_ASSOC);
        }

        // 返回定时任务日志数据
        elseif (isset($_GET['get_cron_logs'])) {
            $dbResponse = $db->query("SELECT * FROM cron_log")->fetchAll(PDO::FETCH_ASSOC);
        }

        // 返回所有频道数据和频道数量
        elseif (isset($_GET['get_channel'])) {
            // 从数据库中获取频道
            $channels = $db->query("SELECT DISTINCT UPPER(channel) FROM epg_data ORDER BY UPPER(channel) ASC")->fetchAll(PDO::FETCH_COLUMN);

            // 创建反向映射关系，排除正则表达式映射
            $channelMappings = $Config['channel_mappings'];
            $reverseMappings = [];
            foreach ($channelMappings as $search => $replace) {
                if (strpos($search, 'regex:') === 0) {
                    continue; // 跳过正则表达式映射
                }
                $reverseMappings[$replace] = $search;
            }

            // 反转频道数组以便快速查找
            $remainingChannels = array_flip($channels);
            
            // 按照 reverseMappings 的顺序处理频道
            $mappedChannels = [];
            foreach ($reverseMappings as $mapped => $original) {
                if (isset($remainingChannels[$mapped])) {
                    $mappedChannels[] = [
                        'original' => $mapped,
                        'mapped' => $original
                    ];
                    unset($remainingChannels[$mapped]); // 从剩余频道中移除
                }
            }

            // 添加剩下的频道，mapped 为空
            foreach ($remainingChannels as $channel => $_) {
                $mappedChannels[] = [
                    'original' => $channel,
                    'mapped' => ''
                ];
            }

            $dbResponse = [
                'channels' => $mappedChannels,
                'count' => count($mappedChannels)
            ];
        }
        
        // 返回频道指定 EPG 数据
        elseif (isset($_GET['get_channel_bind_epg'])) {
            // 从数据库中获取频道
            $channels = $db->query("SELECT DISTINCT UPPER(channel) FROM epg_data ORDER BY UPPER(channel) ASC")->fetchAll(PDO::FETCH_COLUMN);

            $channelBindEpg = $Config['channel_bind_epg'] ?? [];
            $xmlUrls = $Config['xml_urls'];

            // 过滤 xml_urls，同时重置下标
            $filteredUrls = array_values(array_map(function($url) {
                $url = trim($url);
                // 如果以 # 开头，去掉第二个 # 后的内容；否则，去掉第一个 # 后的内容，并修剪空格
                $url = (strpos($url, '#') === 0) 
                    ? preg_replace('/^([^#]*#[^#]*)#.*$/', '$1', $url) 
                    : preg_replace('/#.*$/', '', $url);
                return trim($url); // 去掉多余空格
            }, $xmlUrls));

            // 生成 $dbResponse
            $dbResponse = array_map(function($epgSrc) use ($channelBindEpg) {
                $cleanEpgSrc = trim(preg_replace('/^\s*#\s*/', '', $epgSrc));
                $isInactive = strpos(trim($epgSrc), '#') === 0;
                return [
                    'epg_src' => ($isInactive ? '【已停用】' : '') . $cleanEpgSrc,
                    'channels' => $channelBindEpg[$cleanEpgSrc] ?? ''
                ];
            }, $filteredUrls);

            // 将已停用的放到后面
            $dbResponse = array_merge(
                array_filter($dbResponse, function($item) { return strpos($item['epg_src'], '【已停用】') === false; }),
                array_filter($dbResponse, function($item) { return strpos($item['epg_src'], '【已停用】') !== false; })
            );
        }

        // 返回频道匹配数据
        elseif (isset($_GET['get_channel_match'])) {
            // 从数据库中获取原始频道列表
            $channels = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
            if (empty($channels)) return ['ori_channels' => [], 'clean_channels' => [], 'match' => []];

            // 清理频道名并获取 EPG 数据
            $cleanChannels = explode("\n", t2s(implode("\n", array_map('cleanChannelName', $channels))));
            $epgData = $db->query("SELECT channel FROM epg_data")->fetchAll(PDO::FETCH_COLUMN);

            // 建立干净频道名和原始频道的映射
            $channelMap = array_combine($cleanChannels, $channels);

            $matches = [];

            foreach ($cleanChannels as $cleanChannel) {
                $originalChannel = $channelMap[$cleanChannel];
                $matchResult = null;
                $matchType = '未匹配';

                // 精确匹配
                if (in_array($cleanChannel, $epgData)) {
                    $matchResult = $cleanChannel;
                    $matchType = '精确匹配';
                    if($cleanChannel !== $originalChannel) {
                        $matchType = '映射/忽略';
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
                }}}}

                $matches[$cleanChannel] = [
                    'ori_channel' => $originalChannel,
                    'clean_channel' => $cleanChannel,
                    'match' => $matchResult,
                    'type' => $matchType
                ];
            }

            // 构建返回的响应数据
            $dbResponse = $matches;
        }

        // 返回限定频道列表数据
        elseif (isset($_GET['get_gen_list'])) {
            $dbResponse = $db->query("SELECT channel FROM gen_list")->fetchAll(PDO::FETCH_COLUMN);
        }

        if ($dbResponse !== null) {
            header('Content-Type: application/json');
            echo json_encode($dbResponse);
            exit;
        }
    }

    // 将频道数据写入数据库
    elseif ($requestMethod === 'POST' && isset($_GET['set_gen_list'])) {
        $data = json_decode(file_get_contents("php://input"), true)['data'] ?? '';
        try {
            // 启动事务
            $db->beginTransaction();
            // 清空表中的数据
            $db->exec("DELETE FROM gen_list");
            // 插入新数据
            $lines = array_filter(array_map('trim', explode("\n", $data)));
            $stmt = $db->prepare("INSERT INTO gen_list (channel) VALUES (:channel)");
            foreach ($lines as $line) {
                $stmt->bindValue(':channel', $line, PDO::PARAM_STR);
                $stmt->execute(); // 执行插入操作
            }
            // 提交事务
            $db->commit();
            echo 'success';
        } catch (PDOException $e) {
            // 回滚事务
            $db->rollBack();
            echo "数据库操作失败: " . $e->getMessage();
        }
        exit;
    }

    // 导入配置
    elseif (!empty($_FILES['importFile']['tmp_name'])) {
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
    }

    // 导出配置
    elseif (isset($_POST['action']) && empty($_FILES['importFile']['tmp_name'])) {
        $zip = new ZipArchive();
        $zipFileName = 't.gz';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFile('data/config.json');
            $zip->addFile('data/data.db');
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename=' . $zipFileName);
            readfile($zipFileName);
            unlink($zipFileName);
        }
        exit;
    }
} catch (Exception $e) {
    // 处理数据库连接错误
    $logData = [];
    $cronLogData = [];
    $channels = [];
}

// 根据请求下载数据
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['url'])) {
    $url = filter_var($_GET['url'], FILTER_VALIDATE_URL);

    if ($url) {
        // 调用 downloadData 函数下载数据
        $data = downloadData($url, 5); // 将超时时间作为参数传递

        if ($data !== false) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => '无法获取URL内容']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '无效的URL']);
    }
    exit;
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

        <label for="xml_urls">EPG源地址（支持 xml 跟 .xml.gz 格式， # 为注释）</label><span id="channelbind" onclick="showModal('channelbindepg')" style="color: blue; cursor: pointer;">（频道指定EPG源）</span><br><br>
        <textarea placeholder="一行一个，地址前面加 # 可以临时停用，后面加 # 可以备注。快捷键： Ctrl+/  。" id="xml_urls" name="xml_urls" style="height: 122px;"><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

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
            <div class="flex-item" style="width: 40%;">
                <label for="channel_replacements">频道忽略字符串：（按顺序， \s 空格）</label><br><br>
                <textarea id="channel_replacements" name="channel_replacements" style="height: 142px;"><?php echo implode("\n", array_map('trim', $Config['channel_replacements'])); ?></textarea><br><br>
            </div>
            <div class="flex-item" style="width: 60%;">
            <label for="channel_mappings">
                    频道映射： 自定1, 自定2, ... => 数据库频道名 
                    <span id="dbChannelName" onclick="showModal('channel')" style="color: blue; cursor: pointer;">（点击编辑）</span>
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
            <a href="phpliteadmin.php" target="_blank">管理数据</a>
            <button type="button" onclick="showModal('cron')">定时日志</button>
            <button type="button" onclick="showModal('update')">更新日志</button>
            <button type="button" onclick="showModal('moresetting')">更多设置</button>
            <button type="button" name="logoutbtn" onclick="logout()">退出</button>
        </div>
    </form>
</div>

<!-- 底部显示 -->
<div class="footer">
    <a href="https://github.com/TakcC/PHP-EPG-Docker-Server" style="color: #888; text-decoration: none;">https://github.com/TakcC/PHP-EPG-Docker-Server</a>
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
        <input type="text" id="searchInput" placeholder="搜索频道名..." onkeyup="filterChannels()">
        <div class="table-container" id="channel-table-container">
            <table id="channelTable">
                <thead style="position: sticky; top: 0; background-color: white;">
                    <tr>
                        <th>数据库频道名</th>
                        <th>自定频道名</th>
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
            <input type="hidden" name="action" id="formAction" value="">
            <span id="import" onclick="document.getElementById('importFile').click()" style="color: blue; cursor: pointer; margin-right: 20px;">数据导入</span>
            <span id="export" onclick="document.getElementById('importForm').submit()" style="color: blue; cursor: pointer;">数据导出</span>
        </form>
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
        showModal('update', $popup = false);
        showModal('cron', $popup = false);
        showModal('channel', $popup = false);
        showModal('channelmatch', $popup = false);
        showModal('moresetting', $popup = false);
    });

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

    let genListLoaded = false; // 用于跟踪数据是否已加载

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
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_update_logs=true', updateLogTable);
                break;
            case 'cron':
                modal = document.getElementById("cronlogModal");
                logSpan = document.getElementsByClassName("close")[2];
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_cron_logs=true', updateCronLogContent);
                break;
            case 'channel':
                modal = document.getElementById("channelModal");
                logSpan = document.getElementsByClassName("close")[3];
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_channel=true', updateChannelList);
                break;
            case 'channelbindepg':
                modal = document.getElementById("channelBindEPGModal");
                logSpan = document.getElementsByClassName("close")[4];
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_channel_bind_epg=true', updateChannelBindEPGList);
                break;
            case 'channelmatch':
                modal = document.getElementById("channelMatchModal");
                logSpan = document.getElementsByClassName("close")[5];
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_channel_match=true', updateChannelMatchList);
                document.getElementById("moreSettingModal").style.display = "none";
                break;
            case 'moresetting':
                modal = document.getElementById("moreSettingModal");
                logSpan = document.getElementsByClassName("close")[6];
                fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_gen_list=true', updateGenList);
                genListLoaded = true; // 数据已加载
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

    function fetchLogs(endpoint, callback) {
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

    function updateChannelList(channelsData) {
        const channelTitle = document.getElementById('channelModalTitle');
        channelTitle.innerHTML = `频道列表<span style="font-size: 18px;">（总数：${channelsData.count}）</span>`; // 更新频道总数
        document.getElementById('channelTable').dataset.allChannels = JSON.stringify(channelsData.channels); // 将原始频道和映射后的频道数据存储到 dataset 中
        filterChannels(); // 生成数据
    }

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

    function updateChannelMatchList(channelMatchdata) {
        const channelMatchTableBody = document.querySelector("#channelMatchTable tbody");
        channelMatchTableBody.innerHTML = '';

        const typeOrder = { '未匹配': 1, '反向模糊': 2, '正向模糊': 3, '映射/忽略': 4, '精确匹配': 5 };

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

    function filterChannels() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var channelTableBody = document.querySelector("#channelTable tbody");
        var allChannels = JSON.parse(document.getElementById('channelTable').dataset.allChannels);

        // 清空现有表格
        channelTableBody.innerHTML = '';

        allChannels.forEach(channel => {
            if (String(channel.original).toLowerCase().includes(input)) {
                var row = document.createElement('tr');

                row.innerHTML = `
                    <td>${String(channel.original)}</td>
                    <td contenteditable="true">${channel.mapped}</td>
                `;

                row.querySelector('td[contenteditable]').addEventListener('input', function() {
                    channel.mapped = this.textContent;
                    document.getElementById('channelTable').dataset.allChannels = JSON.stringify(allChannels);
                });

                channelTableBody.appendChild(row);
            }
        });
    }

    function updateChannelMapping() {
        var allChannels = JSON.parse(document.getElementById('channelTable').dataset.allChannels);
        var existingMappings = document.getElementById('channel_mappings').value.split('\n');
        var newMappings = [];

        // 保留正则表达式映射关系
        var regexMappings = existingMappings.filter(line => line.startsWith('regex:'));

        // 处理表格数据，生成新的映射关系
        allChannels.forEach(channel => {
            if (channel.mapped.trim() !== '') {
                newMappings.push(`${channel.mapped} => ${channel.original}`);
            }
        });

        // 合并新映射和正则表达式映射
        var updatedMappings = [...newMappings, ...regexMappings].join('\n');
        document.getElementById('channel_mappings').value = updatedMappings;

        // 保存更新后的配置
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
        if (!genListLoaded) {
            document.getElementById('updateConfig').click();
            return;
        }
        const textAreaContent = document.getElementById('gen_list_text').value;
        fetch('<?php echo $_SERVER['PHP_SELF']; ?>?set_gen_list=true', {
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
        const fields = ['gen_xml', 'include_future_only', 'ret_default', 'gen_list_enable'];
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