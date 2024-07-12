<?php
session_start();

// 设置会话变量，表明用户可以访问 phpliteadmin.php
$_SESSION['can_access_phpliteadmin'] = true;

// 引入配置文件
require_once 'config.php';

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
                width: calc(100% - 12px);
                padding: 5px;
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
                <input type="password" id="password" name="password" required><br><br>
                <input type="hidden" name="login" value="1">
                <input type="submit" value="登录">
            </form>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </div>
        <!-- 底部显示 -->
        <div class="footer">
            <a href="https://github.com/TakcC/PHP-EPG-Server" style="color: #888; text-decoration: none;">https://github.com/TakcC/PHP-EPG-Server</a>
        </div>
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

    // 处理频道替换
    $channel_replacements = array_map('trim', explode("\n", trim($_POST['channel_replacements'])));
    
    // 处理频道映射
    $channel_mappings_lines = array_map('trim', explode("\n", trim($_POST['channel_mappings'])));
    $channel_mappings = [];
    foreach ($channel_mappings_lines as $line) {
        list($search, $replace) = explode(',', $line);
        $channel_mappings[trim($search)] = trim($replace);
    }

    // 更新配置
    $newConfig = [
        'xml_urls' => $xml_urls,
        'days_to_keep' => $days_to_keep,
        'manage_password' => $Config['manage_password'], // 保留密码
        'channel_replacements' => $channel_replacements,
        'channel_mappings' => $channel_mappings
    ];

    // 将新配置写回config.php
    file_put_contents('config.php', '<?php' . "\n\n" . '$Config = ' . var_export($newConfig, true) . ';' . "\n\n" . '?>');

    // 输出 JavaScript 弹窗
    echo "<script>alert('配置已更新');</script>";

    // 重新加载配置以确保页面显示更新的数据
    require 'config.php';
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
            width: 700px;
            margin: auto;
        }
        textarea {
            width: calc(100% - 12px);
            line-height: 1.5;
        }
        input[type="number"], textarea {
            width: calc(100% - 12px);
            padding: 5px;
            font-size: 15px;
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
            font-size: 15px;
        }
        input[type="submit"]:hover {
            background-color: #e68900;
        }
        .button-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .button-container a {
            padding: 10px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            box-sizing: border-box;
            width: 50%;
        }
        .button-container a:hover {
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
    </style>
</head>
<body>
<div class="container">
    <h2>管理配置</h2>
    <form method="POST">
        <label for="xml_urls">EPG源地址 (支持 xml 跟 xml.gz 格式)</label><br><br>
        <textarea id="xml_urls" name="xml_urls" rows="4" required><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

        <label for="days_to_keep">数据保存天数</label><br><br>
        <input type="number" id="days_to_keep" name="days_to_keep" value="<?php echo $Config['days_to_keep']; ?>" required><br><br>

        <div class="flex-container">
            <div class="flex-item">
                <label for="channel_replacements">频道忽略字符串 (匹配时按先后顺序清除)</label><br><br>
                <textarea id="channel_replacements" name="channel_replacements" rows="6" required><?php echo implode("\n", array_map('trim', $Config['channel_replacements'])); ?></textarea><br><br>
                <input type="submit" name="update" value="更新配置">
            </div>
            <div class="flex-item">
                <label for="channel_mappings">频道映射 (格式：显示频道名,数据库频道名)</label><br><br>
                <textarea id="channel_mappings" name="channel_mappings" rows="6" required><?php echo implode("\n", array_map(function($search, $replace) { return $search . ',' . $replace; }, array_keys($Config['channel_mappings']), $Config['channel_mappings'])); ?></textarea><br><br>
                <div class="button-container">
                    <a href="update.php" target="_blank">更新数据库</a>
                    <a href="phpliteadmin.php" target="_blank">查看数据库</a>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- 底部显示 -->
<div class="footer">
    <a href="https://github.com/TakcC/PHP-EPG-Server" style="color: #888; text-decoration: none;">https://github.com/TakcC/PHP-EPG-Server</a>
</div>
</body>
</html>
