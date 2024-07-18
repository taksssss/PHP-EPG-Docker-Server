# 使用 PHP 7.4 Apache 基础镜像
FROM php:7.4-apache

# 拷贝本地 epg 目录到容器的 /var/www/html/epg 目录
COPY ./epg /var/www/html/epg

# 设置 /var/www/html/epg 目录的权限
RUN chmod -R 777 /var/www/html/epg

# 设置 ServerName 以避免警告
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 修改 Apache 配置以监听非特权端口 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# 切换到 www-data 用户并运行 cron.php 脚本
USER www-data

# 设置 ENTRYPOINT 以运行 PHP 脚本并启动 Apache 服务器
ENTRYPOINT ["sh", "-c", "cd /var/www/html/epg && php cron.php & exec apache2-foreground"]