![php-epg](https://socialify.git.ci/TakcC/PHP-EPG-Docker-Server/image?description=1&descriptionEditable=Docker%F0%9F%90%B3%20%E9%83%A8%E7%BD%B2%EF%BC%8C%E5%B8%A6%E8%AE%BE%E7%BD%AE%E7%95%8C%E9%9D%A2%EF%BC%8C%E6%94%AF%E6%8C%81%20DIYP%20%26%20%E7%99%BE%E5%B7%9D%20%E3%80%81%20%E8%B6%85%E7%BA%A7%E7%9B%B4%E6%92%AD%20%E4%BB%A5%E5%8F%8A%20xmltv%20%E3%80%82&font=Inter&forks=1&issues=1&language=1&name=1&owner=1&pattern=Circuit%20Board&pulls=1&stargazers=1&theme=Auto)

# 📺 PHP-EPG-Docker-Server
![Docker Pulls](https://img.shields.io/docker/pulls/taksss/php-epg) ![Image Size](https://img.shields.io/docker/image-size/taksss/php-epg)

PHP 实现的 EPG（电子节目指南）服务端， `Docker` 部署，自带设置界面，支持 **DIYP & 百川** 、 **超级直播** 以及 **xmltv** 格式。

## ℹ️ 主要功能
- 支持返回 **`DIYP & 百川`** 、 **`超级直播`** 以及 **`xmltv`** 格式 📡
- 提供 **`amd64`** 跟 **`arm64`** 、 **`armv7`** 架构镜像，支持 **电视盒子** 等设备 🐳
- 基镜像采用 **`alpine`** ，压缩后大小**仅 20 MB** 📦
- 采用 **先构建再存数据库** 策略，减少数据冗余，提高读取速度 🚀
- 支持 **`SQLite`** 及 **`MySQL`** 数据库 🗃️
- 支持 **`Memcached`** ，可设置缓存时间 ⏱️
- 支持 **台标管理** ，台标模糊匹配 🖼️
- 支持 **繁体中文** 频道匹配 🌐
- 支持 **双向模糊匹配** ✍🏻
- 支持 **频道别名** ，可用 **正则表达式** 🔄
- 支持 **频道指定 EPG 源** 🈯
- 内置 **定时任务** ⏳
- 支持生成 **指定频道节目单** 📝
- 支持生成 **匹配 M3U** 的 `xmltv` 格式文件 💯
- 支持查看 **频道匹配** 结果 🪢
- 兼容多种 `xmltv` 格式 🗂️
- 包含网页设置页面 🌐
- 支持多个 EPG 源 📡
- 可配置数据保存天数 📅
- 内置 `phpLiteAdmin` 方便管理数据库 🛠️

![设置页面](/pic/management.png)

> **内置正则表达式说明：**
> - 包含 `regex:`
> - 示例：
>   - `CCTV$1 => regex:/^CCTV[-\s]*(\d+(\s*P(LUS)?|[K\+])?)(?![\s-]*(美洲|欧洲)).*/i` ：将 `CCTV 1综合`、`CCTV-4K频道`、`CCTV - 5+频道`、`CCTV - 5PLUS频道` 等替换成 `CCTV1`、`CCTV4K`、`CCTV5+`、`CCTV5PLUS`（排除 `CCTV4美洲` 和 `CCTV4欧洲`）

## 📝 更新日志

### 2024-9-19：

1. 新增：台标管理，支持 `URL` 及 `上传`，模糊匹配，返回 `DIYP` 、 `超级直播` 及 `xmltv` 格式
2. 修复：超级直播时间戳、`showTime` 字段错误

### 2024-9-16：

1. 新增：支持 `Memcached` ，可设置缓存时间
2. 新增：`xmltv` 生成 `icon` 台标字段（感谢 https://www.fanmingming.com/ ）

### 2024-9-15：

1. 新增：生成 `xmltv` 文件时，使用多个 `display-name` 标记频道别名，减少数据冗余（感谢[snowie2000](https://github.com/snowie2000)）
2. 优化：精简界面，删除频道忽略字符串
3. 优化：`频道映射` 更名为 `频道别名` **（⚠️ 老用户注意，数据顺序更变）**

### 2024-9-13：

1. 新增：连接 `MySQL` 数据库
2. 优化：部署步骤

### 历史更新记录见[CHANGELOG.md](./CHANGELOG.md)

## TODO：

- [x] 支持返回超级直播格式
- [x] 整合更轻量的 `alpine-apache-php` 容器
- [x] 整合生成 `xml` 文件
- [x] 支持多对一频道映射
- [x] 支持繁体频道匹配
- [x] 仅保存指定频道列表节目单
- [x] 导入/导出配置
- [x] 频道指定 `EPG` 源
- [x] 生成台标信息

## 🚀 部署步骤

1. 配置 `Docker` 环境

2. 若已安装过，先删除旧版本并拉取新版本（注意备份数据）

   ```bash
   docker rm php-epg -f && docker pull taksss/php-epg:latest
   ```

3. 拉取镜像并运行：

   ```bash
   docker run -d \
     --name php-epg \
     -p 5678:80 \
     --restart always \
     taksss/php-epg:latest
   ```

   > 默认端口为 `5678` ，根据需要自行修改。
   > 无法正常拉取镜像的，可使用同步更新的 `腾讯云容器镜像`（`ccr.ccs.tencentyun.com/taksss/php-epg:latest`）

<details>

<summary>（可选）数据持久化</summary>

- 执行以下指令，`./data` 可根据自己需要更改
    ```bash
    docker run -d \
      --name php-epg \
      -v ./data:/htdocs/epg/data \
      -p 5678:80 \
      --restart always \
      taksss/php-epg:latest
    ```

 </details>

<details>

<summary>（可选）同时部署 MySQL 、 phpMyAdmin 及 php-epg</summary>

- **方法1：** 新建 [`docker-compose.yml`](./docker-compose.yml) 文件后，在同目录执行 `docker-compose up -d`
- **方法2：** 依次执行以下指令：
    ```bash
    docker run -d \
      --name mysql \
      -p 3306:3306 \
      -e MYSQL_ROOT_PASSWORD=root_password \
      -e MYSQL_DATABASE=phpepg \
      -e MYSQL_USER=phpepg \
      -e MYSQL_PASSWORD=phpepg \
      --restart always \
      mysql:8.0
    ```
    ```bash
    docker run -d \
      --name phpmyadmin \
      -p 8080:80 \
      -e PMA_HOST=mysql \
      -e PMA_PORT=3306 \
      --link mysql:mysql \
      --restart always \
      phpmyadmin/phpmyadmin:latest
    ```
    ```bash
    docker run -d \
      --name php-epg \
      -v ./data:/htdocs/epg/data \
      -p 5678:80 \
      --restart always \
      --link mysql:mysql \
      --link phpmyadmin:phpmyadmin \
      taksss/php-epg:latest
    ```
 
  </details>

## 🛠️ 使用步骤

1. 在浏览器中打开 `http://{服务器IP地址}:5678/epg/manage.php`
2. **默认密码为空**，根据需要自行设置
3. 添加 `EPG 源地址`， GitHub 源确保能够访问，点击 `更新配置` 保存
4. 点击 `更新数据库` 拉取数据，点击 `数据库更新日志` 查看日志，点击 `查看数据库` 查看具体条目
5. 设置 `定时任务` ，点击 `更新配置` 保存，点击 `定时任务日志` 查看定时任务时间表

    > 建议从 `凌晨1点` 左右开始抓，很多源 `00:00 ~ 00:30` 都是无数据。
    > 隔 `6 ~ 12` 小时抓一次即可。

6. 点击 `更多设置` ，选择是否 `生成xml文件` 、`生成方式` ，设置 `限定频道节目单`
7. 用浏览器测试各个接口的返回结果是否正确：

- `xmltv` 接口： `http://{服务器IP地址}:5678/epg/index.php`
- `DIYP&百川` 接口： `http://{服务器IP地址}:5678/epg/index.php?ch=CCTV1`
- `超级直播` 接口： `http://{服务器IP地址}:5678/epg/index.php?channel=CCTV1`

8. 将 **`http://{服务器IP地址}:5678/epg/index.php`** 填入 `DIYP`、`TiviMate` 等软件的 `EPG 地址栏`

- ⚠️ 直接使用 `docker run` 运行的话，可以将 `:5678/epg/index.php` 替换为 **`:5678/epg`**。
- ⚠️ 部分软件不支持跳转解析 `xmltv` 文件，可直接使用 **`:5678/epg/t.xml.gz`** 或 **`:5678/epg/t.xml`** 访问。

> **快捷键：**
>
> - `Ctrl + S`：保存设置
> - `Ctrl + /`：对选中 EPG 地址设置（取消）注释

## 🖼️ 效果示例

**DIYP**

![DIYP 示例](/pic/DIYP.png)

**TiviMate**

![TiviMate](/pic/TiviMate.jpg)

## 📸 系统截图

**台标管理**

![台标管理](/pic/iconList.png)

**搜索频道、编辑映射**

![编辑频道映射](/pic/channelsMapping.png)

**频道指定 `EPG` 源**

![频道指定EPG源](/pic/channelsBindEPG.png)

**更多设置**

![更多设置](/pic/moresetting.png)

**查看频道匹配**

![查看频道匹配](/pic/channelsMatch.png)

**phpLiteAdmin**

![phpLiteAdmin](/pic/phpliteadmin.png)

## 🙏 特别鸣谢
- [ChatGPT](https://chatgpt.com/)
- [celetor/epg](https://github.com/celetor/epg)
- [sparkssssssssss/epg](https://github.com/sparkssssssssss/epg)
- [Black_crow/xmlgz](https://gitee.com/Black_crow/xmlgz)
- [112114](https://diyp.112114.xyz/)
- [EPG 51zmt](http://epg.51zmt.top:8000/)
- [fanmingming/live](https://github.com/fanmingming/live)
- [wanglindl/TVlogo](http://epg.51zmt.top:8000/)
