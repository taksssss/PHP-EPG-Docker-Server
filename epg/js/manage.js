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

                chName = (tvgIdMatch && /\D/.test(tvgIdMatch[1]) ? tvgIdMatch[1] : tvgNameMatch ? tvgNameMatch[1] : line.split(',').slice(-1)[0]).trim();
            } else {
                chName = line.split(',')[0].trim();
            }
            if (chName) channels.add(chName);
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
    const fields = ['gen_xml', 'include_future_only', 'ret_default', 'tvmao_default', 'gen_list_enable', 
                    'cache_time', 'db_type', 'mysql_host', 'mysql_dbname', 'mysql_username', 'mysql_password'];
    fields.forEach(function(field) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = field;
        hiddenInput.value = document.getElementById(field).value;
        this.appendChild(hiddenInput);
    }, this);
});