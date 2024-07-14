# PHP-EPG-Docker-Server 📺

PHP-EPG-Docker-Server 是一个用 PHP 实现的 EPG（电子节目指南）服务端， `Docker Compose` 部署，带设置界面，支持 `xmltv` 和 `DIYP & 百川` 格式。

## 主要功能 ℹ️
- **使用 Docker Compose 部署** 🐳
- 支持标准的 `xmltv` 和 `DIYP & 百川` 格式
- 使用 `SQLite` 数据库存储 🗃️
- 包含网页设置页面 🌐
- 支持多个 EPG 源 📡
- 可配置数据保存天数 📅
- 支持设置频道忽略字符串 🔇
- 支持频道映射，支持**正则表达式** 🔄
- 内置 `phpLiteAdmin` 方便管理数据库 🛠️

![设置页面](/pic/management.png)

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


## 部署步骤 🚀

1. 配置 `Docker Compose` 环境
2. 拉取源码：

   ```bash
   git clone https://github.com/TakcC/PHP-EPG-Docker-Server.git    # 拉取源码
   cd PHP-EPG-Docker-Server    # 进入文件夹
   docker-compose up -d    # 部署并运行
   ```
   >
   > 或 `git clone https://gitee.com/takcheung/PHP-EPG-Docker-Server.git`
   >
   > 默认端口为 `5678` ，根据需要自行修改 `docker-compose.yml`
   >
3. 在浏览器中打开 `http://{服务器IP地址}:5678/epg/manage.php`
4. 使用默认密码 `admin123` 登录
5. 添加 `EPG 源地址`， GitHub 源确保能够访问
6. 点击 `更新配置` 按钮
7. 点击 `更新数据库` 按钮
8. 点击 `查看数据库` 按钮

![更新数据库](/pic/update.png)

![phpLiteAdmin](/pic/phpliteadmin.png)

## 使用步骤 🛠️
- 将 `http://{服务器IP地址}:5678/epg` 填入 `DIYP`、`TiviMate` 等软件的 `EPG 地址栏`
- 建议设置定时任务，定时访问 `http://{服务器IP地址}:5678/epg/update.php` 更新数据

## 效果示例 🖼️

**DIYP**

![DIYP 示例](/pic/DIYP.png)

**TiviMate**

![TiviMate](/pic/TiviMate.jpg)

## 特别鸣谢 🙏
- [celetor/epg](https://github.com/celetor/epg)
- [sparkssssssssss/epg](https://github.com/sparkssssssssss/epg)
- [Black_crow/xmlgz](https://gitee.com/Black_crow/xmlgz)
- [DIYP](https://diyp.112114.xyz/)
- [EPG 51zmt](http://epg.51zmt.top:8000/)
