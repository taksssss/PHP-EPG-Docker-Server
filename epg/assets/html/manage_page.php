<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>管理配置</title>
</head>
<body>
<div class="container">
    <h2>管理配置</h2>
    <form method="POST" id="settingsForm">

        <label for="xml_urls">【EPG源地址】（支持 xml 跟 .xml.gz 格式， # 为注释，支持获取 猫 数据）</label><span id="channelbind" onclick="showModal('channelbindepg')" style="color: blue; cursor: pointer;">（频道指定EPG源）</span><br><br>
        <textarea placeholder="一行一个，地址前面加 # 可以临时停用，后面加 # 可以备注。快捷键： Ctrl+/  。
猫示例1：tvmao, 猫频道名1, 猫频道名2,...
猫示例2：tvmao, 自定义1:猫频道名1, 自定义2:猫频道名2, ..." id="xml_urls" name="xml_urls" style="height: 122px;"><?php echo implode("\n", array_map('trim', $Config['xml_urls'])); ?></textarea><br><br>

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
                    【频道别名】（数据库频道名 => 频道别名1, 频道别名2, ...）<span id="dbChannelName" onclick="showModal('channel')" style="color: blue; cursor: pointer;">（频道信息）</span><span id="dbChannelName" onclick="showModal('icon')" style="color: blue; cursor: pointer;">（台标信息）</span>
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
            <a href="assets/phpliteadmin.php" target="_blank" onclick="return handleDbManagement();">管理数据</a>
            <a href="assets/tinyfilemanager/index.php" target="_blank">管理文件</a>
            <button type="button" onclick="showModal('cron')">定时日志</button>
            <button type="button" onclick="showModal('update')">更新日志</button>
            <button type="button" onclick="showModal('moresetting')">更多设置</button>
            <button type="button" name="logoutbtn" onclick="logout()">退出</button>
        </div>
    </form>
</div>

<!-- 底部显示 -->
<div class="footer">
    <a href="https://github.com/taksssss/EPG-Server" style="color: #888; text-decoration: none;">
        https://github.com/taksssss/EPG-Server
    </a>
</div>

<!-- 配置消息模态框 -->
<div id="messageModal" class="modal">
    <div class="modal-content message-modal-content">
        <span class="close">&times;</span>
        <p id="modalMessage"></p>
        <div class="modal-footer">
            <!-- 这里的按钮将在 JavaScript 中动态添加 -->
        </div>
    </div>
</div>

<!-- 频道 EPG 模态框 -->
<div id="epgModal" class="modal">
    <div class="modal-content epg-modal-content">
        <span class="close">&times;</span>
        <h2 id="epgTitle">频道名</h2>
        <span id="epgDate">日期</span>
        <span id="prevDate" style="cursor: pointer; color: blue; margin-left: 10px;">&#9664; 前一天</span>
        <span id="nextDate" style="cursor: pointer; color: blue; margin-left: 10px;">后一天 &#9654;</span>
        <br><br>
        <textarea id="epgContent" readonly style="width: 100%; height: 400px;"></textarea>
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
                <span class="tooltiptext">清理未使用<br>服务器台标文件</span>
            </div>
            <div class="tooltip" style="width:auto; margin-right: 10px;">
                <button id="showAllIcons" type="button" onclick="showModal('allicon')">全显</button>
                <span class="tooltiptext">同时显示<br>无节目单台标</span>
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

        <!-- 第一行 -->
        <div class="row">
            <div class="column">
                <label for="gen_xml">生成 xml 文件：</label>
                <select id="gen_xml" name="gen_xml" required>
                    <option value="1" <?php if ($Config['gen_xml'] == 1) echo 'selected'; ?>>是</option>
                    <option value="0" <?php if ($Config['gen_xml'] == 0) echo 'selected'; ?>>否</option>
                </select>
            </div>
            <div class="column">
                <label for="include_future_only">xml 内容：</label>
                <select id="include_future_only" name="include_future_only" required>
                    <option value="1" <?php if ($Config['include_future_only'] == 1) echo 'selected'; ?>>预告数据</option>
                    <option value="0" <?php if ($Config['include_future_only'] == 0) echo 'selected'; ?>>所有数据</option>
                </select>
            </div>
            <div class="column">
                <form id="importForm" method="post" enctype="multipart/form-data" style="display: inline-block;">
                    <input type="file" name="importFile" id="importFile" style="display: none;" accept=".gz" onchange="document.getElementById('importForm').submit();">
                    <input type="hidden" name="importExport" id="formImportExport" value="">
                    <span id="import" onclick="document.getElementById('importFile').click()" style="color: blue; cursor: pointer; margin-right: 20px;">数据导入</span>
                    <span id="export" onclick="document.getElementById('importForm').submit()" style="color: blue; cursor: pointer;">数据导出</span>
                </form>
            </div>
        </div>

        <!-- 第二行 -->
        <div class="row">
            <div class="column">
                <label for="ret_default">返回精彩节目：</label>
                <select id="ret_default" name="ret_default" required>
                    <option value="1" <?php if (!isset($Config['ret_default']) || $Config['ret_default'] == 1) echo 'selected'; ?>>是</option>
                    <option value="0" <?php if (isset($Config['ret_default']) && $Config['ret_default'] == 0) echo 'selected'; ?>>否</option>
                </select>
            </div>
            <div class="column">
                <div class="tooltip" style="display: flex;">
                    <label for="tvmao_default" title="">补充预告<span style="vertical-align: super;">*</span>：</label>
                    <select id="tvmao_default" name="tvmao_default" required>
                        <option value="1" <?php if (isset($Config['tvmao_default']) && $Config['tvmao_default'] == 1) echo 'selected'; ?>>是</option>
                        <option value="0" <?php if (!isset($Config['tvmao_default']) || $Config['tvmao_default'] == 0) echo 'selected'; ?>>否</option>
                    </select>
                    <span class="tooltiptext">尝试使用 猫 接口<br>补充预告数据</span>
                </div>
            </div>
            <div class="column">
                <div class="tooltip" style="display: flex;">
                    <label for="all_chs" title="">全转简中<span style="vertical-align: super;">*</span>：</label>
                    <select id="all_chs" name="all_chs" required>
                        <option value="1" <?php if (isset($Config['all_chs']) && $Config['all_chs'] == 1) echo 'selected'; ?>>是</option>
                        <option value="0" <?php if (!isset($Config['all_chs']) || $Config['all_chs'] == 0) echo 'selected'; ?>>否</option>
                    </select>
                    <span class="tooltiptext">节目单&描述<br>转简体中文</span>
                </div>
            </div>
        </div>

        <!-- 第三行 -->
        <div class="row">
            <div class="column">
                <label for="db_type">数据库：</label>
                <select id="db_type" name="db_type" required>
                    <option value="sqlite" <?php if (!isset($Config['db_type']) || $Config['db_type'] == 'sqlite') echo 'selected'; ?>>SQLite</option>
                    <option value="mysql" <?php if (isset($Config['db_type']) && $Config['db_type'] == 'mysql') echo 'selected'; ?>>MySQL</option>
                </select>
            </div>
            <div class="column">
                <label for="cache_time">缓存时间(小时)：</label>
                <select id="cache_time" name="cache_time" required>
                    <?php for ($h = 0; $h < 24; $h++): ?>
                        <option value="<?php echo $h; ?>" <?php echo floor($Config['cache_time'] / 3600) == $h ? 'selected' : ''; ?>>
                            <?php echo $h; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>            
            <div class="column">
                <!-- <span id="export" style="color: blue; cursor: pointer;">直播源管理</span> -->
            </div>
        </div>

        <!-- 第四行 -->
        <div class="row" style="gap: 10px;">
            <div class="column">
                <label for="mysql_host">地址：</label>
                <textarea id="mysql_host" style="width: 129px; margin-right: 25px;"><?php echo htmlspecialchars($Config['mysql']['host'] ?? ''); ?></textarea>
            </div>
            <div class="column">
                <label for="mysql_dbname">库名：</label>
                <textarea id="mysql_dbname"><?php echo htmlspecialchars($Config['mysql']['dbname'] ?? ''); ?></textarea>
            </div>
            <div class="column">
                <label for="mysql_username">用户：</label>
                <textarea id="mysql_username"><?php echo htmlspecialchars($Config['mysql']['username'] ?? ''); ?></textarea>
            </div>
            <div class="column">
                <label for="mysql_password">密码：</label>
                <textarea id="mysql_password"><?php echo htmlspecialchars($Config['mysql']['password'] ?? ''); ?></textarea>
            </div>
        </div>

        <!-- 其他设置 -->
        <label for="gen_list_text">仅生成以下频道：</label>
        <select id="gen_list_enable" name="gen_list_enable" style="width: 48px; margin-right: 0px;" required>
            <option value="1" <?php if (isset($Config['gen_list_enable']) && $Config['gen_list_enable'] == 1) echo 'selected'; ?>>是</option>
            <option value="0" <?php if (!isset($Config['gen_list_enable']) || $Config['gen_list_enable'] == 0) echo 'selected'; ?>>否</option>
        </select>
        <span>
            （粘贴m3u、txt地址或内容，<span onclick="parseSource()" style="color: blue; cursor: pointer; text-decoration: underline;">解析</span> 后
            <span onclick="showModal('channelmatch')" style="color: blue; cursor: pointer; text-decoration: underline;">查看匹配</span>）
        </span>
        <br><br>
        <textarea id="gen_list_text"></textarea><br><br>

        <button id="saveConfig" type="button" onclick="saveAndUpdateConfig();">保存配置</button>
    </div>
</div>

<script>
    var configUpdated = <?php echo json_encode($configUpdated); ?>;
    var intervalTime = <?php echo json_encode($Config['interval_time']); ?>;
    var startTime = <?php echo json_encode($Config['start_time']); ?>;
    var endTime = <?php echo json_encode($Config['end_time']); ?>;
    var displayMessage = <?php echo json_encode($displayMessage); ?>;

    // js、css 缓存处理
    var currentDate = new Date().toISOString().split('T')[0];
    document.head.appendChild(Object.assign(document.createElement('link'), {rel: 'stylesheet', href: 'assets/css/manage.css?date=' + currentDate}));
    document.head.appendChild(Object.assign(document.createElement('script'), {src: 'assets/js/manage.js?date=' + currentDate}));
</script>
</body>
</html>