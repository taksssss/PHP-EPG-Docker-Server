<?php
/**
 * @file manage.php
 * @brief 管理页面部分
 * 
 * 管理界面脚本，用于处理会话管理、密码更改、登录验证、配置更新、更新日志展示等功能。
 * 
 * 作者: Tak
 * GitHub: https://github.com/TakcC/PHP-EPG-Docker-Server
 */

// 设置时区为亚洲/上海
date_default_timezone_set("Asia/Shanghai");

session_start();

// 设置会话变量，表明用户可以访问 phpliteadmin.php
$_SESSION['can_access_phpliteadmin'] = true;

// 读取 configUpdated 状态
$configUpdated = isset($_SESSION['configUpdated']) && $_SESSION['configUpdated'];
if ($configUpdated) {
    unset($_SESSION['configUpdated']);
}

// 引入配置文件
require_once 'config.php';

// 处理密码更新请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

    // 验证原密码是否正确
    if ($oldPassword === $Config['manage_password']) {
        // 原密码正确，更新配置中的密码
        $newConfig = $Config;
        $newConfig['manage_password'] = $newPassword;

        // 将新配置写回 config.php
        file_put_contents('config.php', '<?php' . "\n\n" . '$Config = ' . var_export($newConfig, true) . ';' . "\n\n" . '?>');

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
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
            }
            .container {
                background: white;
                padding: 30px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                width: 200px;
            }
            input[type="password"] {
                width: calc(100%);
                flex: 1;
                padding: 7px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                width: 100%;
                padding: 8px;
                background-color: #FF9800;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                box-sizing: border-box;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .button-container button {
                width: 100%;
                padding: 8px;
                background-color: #2196F3;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                box-sizing: border-box;
                font-size: 15px;
                margin-top: 5px;
            }
            .form-group {
                display: flex;
                align-items: center;
            }
            .form-group label {
                width: 48px;
                margin-right: 10px;
                margin-bottom: 10px;
            }
            .form-group input[type="password"] {
                flex: 1;
                padding: 5px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                margin-bottom: 10px;
            }
            .modal {
                display: none; /* 默认隐藏 */
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.4);
                text-align: center;
            }
            .passwd-modal-content {
                background-color: #fefefe;
                display: inline-block;
                padding: 20px;
                border: 1px solid #888;
                max-width: 200px;
                width: auto;
                text-align: left;
                border-radius: 10px;
                position: relative;
                top: 50%;
                transform: translateY(-50%);
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            }
            .footer {
                position: fixed;
                bottom: 10px;
                width: 100%;
                text-align: center;
            }
        </style>
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
    $days_to_keep = intval($_POST['days_to_keep']);
    $gen_xml = $_POST['gen_xml'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    // 处理间隔时间
    $interval_hour = intval($_POST['interval_hour']);
    $interval_minute = intval($_POST['interval_minute']);
    $interval_time = $interval_hour * 3600 + $interval_minute * 60;

    // 处理频道替换
    $channel_replacements = array_map('trim', explode("\n", trim($_POST['channel_replacements'])));

    // 处理频道映射
    $channel_mappings_lines = array_map('trim', explode("\n", trim($_POST['channel_mappings'])));
    $channel_mappings = [];
    foreach ($channel_mappings_lines as $line) {
        list($search, $replace) = explode('=>', $line);
        $channel_mappings[trim(str_replace("，", ",", $search))] = trim($replace);
    }

    // 获取旧的配置
    $oldConfig = $Config;

    // 更新配置
    $newConfig = [
        'xml_urls' => $xml_urls,
        'days_to_keep' => $days_to_keep,
        'gen_xml' => $gen_xml,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'interval_time' => $interval_time,
        'manage_password' => $Config['manage_password'], // 保留密码
        'channel_replacements' => $channel_replacements,
        'channel_mappings' => $channel_mappings
    ];

    // 将新配置写回config.php
    file_put_contents('config.php', '<?php' . "\n\n" . '$Config = ' . var_export($newConfig, true) . ';' . "\n\n" . '?>');

    // 设置标志变量以显示弹窗
    $_SESSION['configUpdated'] = true;

    // 重新加载配置以确保页面显示更新的数据
    require 'config.php';

    // 重新启动 cron.php ，设置新的定时任务
    if ($oldConfig['start_time'] !== $start_time || $oldConfig['end_time'] !== $end_time || $oldConfig['interval_time'] !== $interval_time) {
        exec('php cron.php > /dev/null 2>/dev/null &');
    }
    header('Location: manage.php');
    exit;
} else {
    // 首次进入界面，检查 cron.php 是否运行正常
    // $output = [];
    // exec("ps aux | grep '[c]ron.php'", $output);
    // if(!$output) {
    //     exec('php cron.php > /dev/null 2>/dev/null &');
    // }
}

// 连接数据库并获取日志表中的数据
$logData = [];
$cronLogData = [];
$channels = [];
try {
    $db = new PDO('sqlite:adata.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 初始化数据库表
    $db->exec("CREATE TABLE IF NOT EXISTS epg_data (
        date TEXT NOT NULL,
        channel TEXT NOT NULL,
        epg_diyp TEXT,
        PRIMARY KEY (date, channel)
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

    // 处理 AJAX 请求，返回更新日志数据
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['get_update_logs'])) {
        $stmt = $db->query("SELECT * FROM update_log");
        $logData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($logData);
        exit;
    }

    // 处理 AJAX 请求，返回定时任务日志数据
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['get_cron_logs'])) {
        $stmt = $db->query("SELECT * FROM cron_log");
        $cronLogData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($cronLogData);
        exit;
    }

    // 处理 AJAX 请求，返回频道数据
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['get_channel'])) {
        $stmt = $db->query("SELECT DISTINCT channel FROM epg_data ORDER BY channel ASC");
        $channels = $stmt->fetchAll(PDO::FETCH_COLUMN);
        header('Content-Type: application/json');
        echo json_encode($channels);
        exit;
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
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 850px;
            margin: auto;
        }
        textarea {
            width: 100%;
            padding: 5px;
            line-height: 1.5;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            resize: none;
            white-space: nowrap;
        }
        textarea[id="cronLogContent"], textarea[id="channelList"] {
            white-space: pre-wrap; // 自动换行
        }
        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .label-days-to-keep {
            width: 98px;
        }
        .custom-margin0 {
            margin-left: 20px;
        }
        .custom-margin1 {
            margin-left: 20px;
        }
        .custom-margin2 {
            margin-left: 60px;
        }
        .custom-margin3 {
            margin-left: 65px;
        }
        .form-row select {
            padding: 6px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            width: 98px;
            text-align: center;
        }
        .form-row select[id="gen_xml"] {
            width: 90px;
            margin-left: 54px;
        }
        .form-row input[type="time"] {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            width: 97px;
        }
        .form-row input[id="start_time"] {
            margin-left: 133px;
        }
        .form-row input[id="end_time"] {
            margin-left: 27px;
        }
        .form-row select[id="interval_hour"], select[id="interval_minute"] {
            width: 55px;
            margin-right: 0px;
            text-align: center;
        }
        .form-row select[id="interval_hour"] {
            margin-left: 33px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #FF9800;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-sizing: border-box;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #e68900;
        }
        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
        }
        .button-container {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }
        .button-container a, .button-container button {
            padding: 10px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            box-sizing: border-box;
            width: 32%;
            font-size: 16px;
        }
        .button-container a:hover, .button-container button:hover {
            background-color: #0b7dda;
        }
        .flex-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .flex-item {
            width: 48%;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
        }
        .modal {
            display: none; /* 默认隐藏 */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            text-align: center;
        }
        .modal-content {
            background-color: #fefefe;
            display: inline-block;
            padding: 20px;
            border: 1px solid #888;
            text-align: left;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            top: 50%;
            transform: translateY(-50%);
        }
        .update-log-modal-content {
            max-width: 830px;
            width: auto;
        }
        .cron-log-modal-content,
        .config-modal-content {
            max-width: 65%;
            width: 500px;
        }
        .config-modal-content {
            width: 200px;
        }
        .channel-modal-content {
            width: 300px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .table-container {
            height: 425px; /* 固定高度 */
            overflow-y: scroll; /* 启用垂直滚动条 */
        }
        #logTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 5px; /* 调整行之间的垂直间距 */
            table-layout: fixed; /* 固定表格布局，防止内容超出 */
        }
        #logTable th, #logTable td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        #logTable th:nth-child(1), 
        #logTable td:nth-child(1) {
            width: 4%; /* 第一列宽度 */
            text-align: center;
        }

        #logTable th:nth-child(2), 
        #logTable td:nth-child(2) {
            width: 9%; /* 第二列宽度 */
            text-align: center;
        }

        #logTable th:nth-child(3), 
        #logTable td:nth-child(3) {
            width: 88%; /* 第三列宽度 */
            word-wrap: break-word; /* 自动换行 */
            overflow-wrap: break-word; /* 自动换行 */
        }
        #logTable th:nth-child(3) {
            text-align: center; /* 第三列表头居中 */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>管理配置</h2>
    <form method="POST">

        <label for="xml_urls">EPG源地址 (支持 xml 跟 .xml.gz 格式， # 为注释)</label><br><br>
        <textarea placeholder="一行一个，地址前面加 # 可以临时停用，后面加 # 可以备注。" id="xml_urls" name="xml_urls" rows="5" required><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

        <div class="form-row">
            <label for="days_to_keep" class="label-days-to-keep">数据保存天数</label>
            <label for="gen_xml" class="label-gen-xml custom-margin0" >生成 .xml.gz 文件</label>
            <label for="start_time" class="label-time custom-margin1">【定时任务】： 开始时间</label>
            <label for="end_time" class="label-time2 custom-margin2">结束时间</label>
            <label for="interval_time" class="label-time3 custom-margin3">间隔周期 (选0小时0分钟取消)</label>
        </div>

        <div class="form-row">
            <select id="days_to_keep" name="days_to_keep" required>
                <?php for ($i = 1; $i <= 30; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $Config['days_to_keep'] == $i ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <select id="gen_xml" name="gen_xml" required>
                <option value="1" <?php if ($Config['gen_xml'] == 1) echo 'selected'; ?>>是</option>
                <option value="0" <?php if ($Config['gen_xml'] == 0) echo 'selected'; ?>>否</option>
            </select>
            <input type="time" id="start_time" name="start_time" value="<?php echo $Config['start_time']; ?>" required>
            <input type="time" id="end_time" name="end_time" value="<?php echo $Config['end_time']; ?>" required>
            
            <!-- Interval Time Controls -->
            <select id="interval_hour" name="interval_hour" required>
                <?php for ($h = 0; $h < 24; $h++): ?>
                    <option value="<?php echo $h; ?>" <?php echo intval($Config['interval_time']) / 3600 == $h ? 'selected' : ''; ?>>
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
            <div class="flex-item">
                <label for="channel_replacements">频道忽略字符串<br>(匹配时按先后顺序清除)</label><br><br>
                <textarea id="channel_replacements" name="channel_replacements" rows="5" required><?php echo implode("\n", array_map('trim', $Config['channel_replacements'])); ?></textarea><br><br>
            </div>
            <div class="flex-item">
                <label for="channel_mappings">
                    频道映射<br>[自定1, 自定2, ...] => 
                    <span id="dbChannelName" onclick="showModal('channel')" style="color: blue; text-decoration: underline; cursor: pointer;">数据库频道名</span>
                    (正则表达式regex:)
                </label><br><br>
                <textarea id="channel_mappings" name="channel_mappings" rows="5" required><?php echo implode("\n", array_map(function($search, $replace) { return $search . ' => ' . $replace; }, array_keys($Config['channel_mappings']), $Config['channel_mappings'])); ?></textarea><br><br>
            </div>
        </div>

        <input id="updateConfig" type="submit" name="update" value="更新配置"><br><br>
        <div class="button-container">
            <a href="update.php" target="_blank">更新数据库</a>
            <a href="phpliteadmin.php" target="_blank">管理数据库</a>
            <button type="button" onclick="showModal('cron')">定时任务日志</button>
            <button type="button" onclick="showModal('update')">数据库更新日志</button>
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
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>日期</th>
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
        <textarea id="cronLogContent" readonly style="width: 100%; height: 420px;"></textarea>
    </div>
</div>

<!-- 频道列表模态框 -->
<div id="channelModal" class="modal">
    <div class="modal-content channel-modal-content">
        <span class="close">&times;</span>
        <h2>数据库频道列表</h2>
        <input type="text" id="searchInput" placeholder="搜索频道名..." onkeyup="filterChannels()">
        <textarea id="channelList" readonly style="width: 100%; height: 370px;"></textarea>
    </div>
</div>

<script>
    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && event.key === "s") {
            event.preventDefault(); // 阻止默认行为，如保存页面
            document.getElementById("updateConfig").click();
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

    if (configUpdated) {
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var modalMessage = document.getElementById("modalMessage");

        if (intervalTime === "0") {
            modalMessage.innerHTML = "配置已更新<br><br>已取消定时任务";
        } else {
            modalMessage.innerHTML = `配置已更新<br><br>已设置定时任务<br>开始时间：${startTime}<br>结束时间：${endTime}<br>间隔周期：${formatTime(intervalTime)}`;
        }

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        $configUpdated = false;
    }

    function showModal(type) {
        var modal, logSpan, logContent;

        if (type === 'update') {
            modal = document.getElementById("updatelogModal");
            logSpan = document.getElementsByClassName("close")[1];
            fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_update_logs=true', updateLogTable);
        } else if (type === 'cron') {
            modal = document.getElementById("cronlogModal");
            logSpan = document.getElementsByClassName("close")[2];
            fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_cron_logs=true', updateCronLogContent);
        } else if (type === 'channel') {
            modal = document.getElementById("channelModal");
            logSpan = document.getElementsByClassName("close")[3];
            fetchLogs('<?php echo $_SERVER['PHP_SELF']; ?>?get_channel=true', updateChannelList);
            document.getElementById('searchInput').value = "";
        }

        modal.style.display = "block";

        logSpan.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
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
                <td>${log.id}</td>
                <td>${new Date(log.timestamp).toLocaleString()}</td>
                <td>${log.log_message}</td>
            `;
            logTableBody.appendChild(row);
        });
        var logTableContainer = document.getElementById("log-table-container");
        logTableContainer.scrollTop = logTableContainer.scrollHeight;
    }

    function updateCronLogContent(logData) {
        var logContent = document.getElementById("cronLogContent");
        logContent.value = logData.map(log => `[${new Date(log.timestamp).toLocaleString()}] ${log.log_message}`).join('\n');
        logContent.scrollTop = logContent.scrollHeight;
    }

    function updateChannelList(channels) {
        var channelList = document.getElementById('channelList');
        channelList.dataset.allChannels = channels.join('\n'); // 保存所有频道数据
        channelList.value = channelList.dataset.allChannels;
    }

    function filterChannels() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var channelList = document.getElementById('channelList');
        var allChannels = channelList.dataset.allChannels.split('\n');
        var filteredChannels = allChannels.filter(channel => channel.toLowerCase().includes(input));
        channelList.value = filteredChannels.join('\n');
    }
</script>
</body>
</html>
