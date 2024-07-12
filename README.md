# PHP-EPG-Server 📺

PHP-EPG-Server 是一个用 PHP 实现的 EPG（电子节目指南）服务端，支持 `xmltv` 和 `DIYP & 百川` 格式。

## 主要功能 ℹ️
- 支持标准的 `xmltv` 和 `DIYP & 百川` 格式
- 使用 `SQLite` 数据库存储 🗃️
- 包含网页设置页面 🌐
- 支持多个 EPG 源 📡
- 可配置数据保存天数 📅
- 支持设置频道忽略字符串 🔇
- 支持频道映射 🔄
- 内置 `phpLiteAdmin` 方便管理数据库 🛠️

![设置页面](https://github.com/user-attachments/assets/387adfe6-edfc-4a97-96fe-225035e02fd8)

## 部署步骤 🚀
- 配置 PHP 环境，推荐使用 `宝塔` 或 `1Panel` 面板
- 下载 `epg 文件夹`，放置到 PHP 环境对应目录
- 赋予所有文件写入权限
- 在浏览器中打开 `http://你的php访问路径/epg/manage.php`
- 使用默认密码 `admin123` 登录
- 添加你的 `EPG 源地址`，确保能够访问 GitHub 源
- 点击 `更新配置` 按钮
- 点击 `更新数据库` 按钮
- 点击 `查看数据库` 按钮

![更新页面](https://github.com/user-attachments/assets/3f80c287-42f7-4766-8082-49ce57e40664)
![phpLiteAdmin](https://github.com/user-attachments/assets/b166eb69-d52f-42dd-aa45-388e28a82381)

## 使用步骤 🛠️
- 将 `http://你的php访问路径/epg` 填入 `DIYP`、`Kodi` 等软件的 `EPG 地址栏`
- 建议设置定时任务，定时访问 `http://你的php访问路径/epg/update.php` 更新数据

## 示例 🖼️
![DIYP 示例](https://github.com/user-attachments/assets/ef926713-f2e1-42b9-aed4-4c9f5c1af1da)

## 特别鸣谢 🙏
- [celetor/epg](https://github.com/celetor/epg)
- [sparkssssssssss/epg](https://github.com/sparkssssssssss/epg)
- [Black_crow/xmlgz](https://gitee.com/Black_crow/xmlgz)
- [DIYP](https://diyp.112114.xyz/)
- [EPG 51zmt](http://epg.51zmt.top:8000/)
