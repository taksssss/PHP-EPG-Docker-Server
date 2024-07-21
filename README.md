# PHP-EPG-Docker-Server 📺

PHP-EPG-Docker-Server 是一个用 PHP 实现的 EPG（电子节目指南）服务端， `Docker` 部署，自带设置界面，支持 `DIYP & 百川` 、 `超级直播` 以及 `xmltv` 格式。

## 主要功能 ℹ️
- **使用 Docker🐳 部署，提供 `amd64` 跟 `arm64` 架构镜像**
- **基镜像采用 `alpine-apache-php` ，压缩后大小仅 `23M`**
- **采用先构建再存数据库的策略，占用空间稍大，但是能实现秒读取**
- 支持 `DIYP & 百川` 以及 `超级直播` 格式，支持缓存 `xmltv` 格式 📡
- 兼容多种 `xmltv` 格式
- 内置定时任务，支持设置定时拉取数据 ⏳
- 使用 `SQLite` 数据库存储 🗃️
- 包含网页设置页面 🌐
- 支持多个 EPG 源 📡
- 可配置数据保存天数 📅
- 支持设置频道忽略字符串 🔇
- 支持频道映射，支持**正则表达式** 🔄
- 内置 `phpLiteAdmin` 方便管理数据库 🛠️

![设置页面](/pic/management.png)

![定时任务日志](/pic/cronLog.png)

![更新日志](/pic/updateLog.png)

> **内置正则表达式说明：**
> 
> - 以 `regex:` 作为前缀
> 
> - 示例：
> 
>   - `regex:/^CCTV[-\s]*(\p{Han})/iu, $1` ：将 `CCTV风云足球`、`cctv-风云音乐` 等替换成 `风云足球`、`风云音乐`
> 
>   - `regex:/^CCTV[-\s]*(\d+[K\+]?)(?!美洲|欧洲).*/i, CCTV$1` ：将 `CCTV 1综合`、`CCTV-4K频道`、`CCTV - 5+频道` 等替换成 `CCTV1`、`CCTV4K`、`CCTV5+`（排除 `CCTV4美洲` 和 `CCTV4欧洲`）
> 
>   - `regex:/^(深圳.*?)频道$/i, $1` ：将 `深圳xx频道` 替换成 `深圳xx`

## 更新日志 📝

### 2024-7-21更新：

1. **支持 `超级直播` 格式**
2. **重构代码，基镜像改为 `alpine-apache-php` ，镜像大小从 155M 下降到 23M**
3. 支持解析 M3U4U 等非 .xml/.gz 结尾 EPG 地址
4. 数据分批插入，降低内存占用
5. 修复部分界面显示异常问题
6. 修复设置页面刷新，提示“是否重新提交表单”问题

- ⚠️ 该版本容器内端口从 `8080` 修正为 `80`，自行部署的小伙伴注意这一点。


#### TODO：

- 支持繁体频道匹配（ opencc4php 在 alpine 里面还没跑起来……）
- 整合生成 xml 文件（现在只返回第一个）
- 支持多对一频道映射

### 2024-7-18更新：

1. 提供 Docker🐳 镜像（基于 php:7.4-apache ，支持 x86-64 跟 arm64 ）
2. 支持定时更新数据库
3. EPG 源支持添加注释
4. 支持更改登录密码 【默认为空！！！】
5. 支持查看定时任务日志
6. 支持查看数据库更新日志
7. 配置页面支持 Ctrl+S 保存
8. 更新部署流程


### 2024-7-14更新：

1. 改用 Docker Compose🐳 部署
2. 更新部署流程

### 2024-7-13更新：

1. 优化自带正则表达式
2. 更新默认返回数据，供回放使用
3. 增加 TiviMate 示例图片

### 2024-7-13初始版本：

1. 支持标准 xmltv 和 DIYP&百川 格式
2. 包含网页设置页面
3. 支持多个 EPG 源
4. 可配置数据保存天数
5. 支持设置频道忽略字符串
6. 支持频道映射，支持正则表达式
7. 内置 phpLiteAdmin 方便管理数据库


## 部署步骤 🚀

1. 配置 `Docker` 环境

2. 若已安装过，先删除旧版本（注意备份数据）

   ```bash
   docker rm php-epg -f
   ```

3. 拉取镜像并运行：

   ```bash
   docker run -d \
     --name php-epg \
     -p 5678:80 \
     --restart always \
     taksss/php-epg:latest
   ```

      >
      > 默认端口为 `5678` ，根据需要自行修改。
      > 


## 使用步骤 🛠️

1. 在浏览器中打开 `http://{服务器IP地址}:5678/epg/manage.php`

2. **默认密码为空**，根据需要自行设置

3. 添加 `EPG 源地址`， GitHub 源确保能够访问，点击 `更新配置` 保存

4. 点击 `更新数据库` 拉取数据，点击 `数据库更新日志` 查看日志，点击 `查看数据库` 查看具体条目

5. 设置 `定时任务` ，点击 `更新配置` 保存，点击 `定时任务日志` 查看定时任务时间表

6. 用浏览器测试各个接口的返回结果是否正确：

    - `xmltv` 接口： `http://{服务器IP地址}:5678/epg/index.php`
  
    - `DIYP&百川` 接口： `http://{服务器IP地址}:5678/epg/index.php?ch=CCTV1`
  
    - `超级直播` 接口： `http://{服务器IP地址}:5678/epg/index.php?channel=CCTV1`

7. 将 **`http://{服务器IP地址}:5678/epg/index.php`** 填入 `DIYP`、`TiviMate` 等软件的 `EPG 地址栏`

    - ⚠️ 直接使用 `docker run` 拉取镜像的话，可以将 `http://{服务器IP地址}:5678/epg/index.php` 替换为 `http://{服务器IP地址}:5678/epg`。

![设置定时任务](/pic/cronSet.png)

![更新数据库](/pic/update.png)

![phpLiteAdmin](/pic/phpliteadmin.png)

## 效果示例 🖼️

**DIYP**

![DIYP 示例](/pic/DIYP.png)

**TiviMate**

![TiviMate](/pic/TiviMate.jpg)

**超级直播**
![超级直播](/pic/LoveTV.jpg)

## 特别鸣谢 🙏
- [celetor/epg](https://github.com/celetor/epg)
- [sparkssssssssss/epg](https://github.com/sparkssssssssss/epg)
- [Black_crow/xmlgz](https://gitee.com/Black_crow/xmlgz)
- [DIYP](https://diyp.112114.xyz/)
- [EPG 51zmt](http://epg.51zmt.top:8000/)
