# PHP-EPG-Server
用 php 实现的 EPG 服务端，支持 `xmltv` 及 `DIYP & 百川` 格式。

## 支持功能
- 支持返回标准 `xmltv` 及 `DIYP & 百川` 格式
- 使用 `SQLite` 数据库
- 带 `网页设置页面`
- 支持多个 `EPG` 源
- 支持设置 `数据保存天数`
- 支持设置 `频道忽略字符串`
- 支持设置 `频道映射`
- 内置 `phpLiteAdmin`

![设置页面](https://github.com/user-attachments/assets/387adfe6-edfc-4a97-96fe-225035e02fd8)


## 部署步骤
- 部署 `php环境` ，推荐使用 `宝塔` 或者 `1Panel` 面板部署
- 下载 `epg文件夹` ，放至 `php环境` 对应目录
- 授予所有文件 `写入权限`
- 浏览器打开 `http://你的php访问路径/epg/manage.php`
- 输入默认密码 `admin123` 登录
- 添加 `EPG源地址` ，注意 `GitHub` 源的可访问性
- 点击 `更新配置` 按钮
- 点击 `更新数据库` 按钮
- 点击 `查看数据库` 按钮

![更新页面](https://github.com/user-attachments/assets/3f80c287-42f7-4766-8082-49ce57e40664)
![phpliteadmin](https://github.com/user-attachments/assets/b166eb69-d52f-42dd-aa45-388e28a82381)

## 使用步骤
- 将 `http://你的php访问路径/epg` 填到 `DIYP`、`Kodi` 等软件的 `EPG地址栏`
- 建议设置定时任务，定时访问 `http://你的php访问路径/epg/update.php` 更新数据

## 效果示例
![DIYP](https://github.com/user-attachments/assets/ef926713-f2e1-42b9-aed4-4c9f5c1af1da)

## 特别鸣谢
https://github.com/celetor/epg

https://github.com/sparkssssssssss/epg

https://gitee.com/Black_crow/xmlgz

https://diyp.112114.xyz/

http://epg.51zmt.top:8000/
