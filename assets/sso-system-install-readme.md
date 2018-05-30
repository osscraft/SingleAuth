# Mysql数据库权限、主从复制
```
grant select,insert,update,delete on 'transfer'.'users' to 'transfer' identified by '123456';
mysql grant sync:
GRANT REPLICATION SLAVE ON *.* TO sync_user@'%' IDENTIFIED BY '123456';
GRANT FILE,SELECT,REPLICATION SLAVE ON *.* TO sync_user@'%' IDENTIFIED BY '123456';

// 主从复制配置项
log-bin=mysql-bin
log-slave-updates
server-id=2
#master-host=192.168.100.1
#master-user=sync_user
#master-pass=123456
#master-port=3306
#master-connect-retry=60
#binlog-do-db=sso
#binlog-do-db=transfer
#binlog-ignore-db=mysql
replicate-do-db=sso
replicate-do-db=transfer
replicate-ignore-db=mysql
slave-skip-errors=all
sync_binlog=1
binlog_format=mixed
#bind-address           = 127.0.0.1
```
  
# iptables防火墙
```
iptables -A INPUT -p tcp --dport 8347 -j ACCEPT  
iptables -A OUTPUT -p tcp --sport 8347 -j ACCEPT
```

# SSH互信
```
ssh-keygen  -t rsa -f ~/.ssh/id_rsa  -P ''
ssh-copy-id -i ~/.ssh/id_rsa.pub root@demo-server
```

# 安装软件
```
apt-get install ssh // SSH
apt-get install debian-keyring debian-archive-keyring // debian keyring
apt-get install apache2 php5 php5-ldap php5-mysql mysql-server php5-memcache php5-memcached memcached php5-gd php5-curl php5-xsl php5-intl// php、apache2服务器, 
apt-get install slapd ldap-utils // openldap
apt-get install chkconfig // 开机自启动
apt-get install ntpdate // 时间同步
apt-get install php-pear php5-dev gcc make libevent-dev // libevent前置软件环境
pecl install libevent #pecl install "channel://pecl.php.net/libevent-0.1.0" // 安装libevent
apt-get install strace  // 其他软件
```

# apache2配置项
```
a2enmod rewrite
```
# PHP配置项
```
browscap = /var/www/SSO/config/lite_php_browscap.ini
```

# LDAP
```
ldapadd -x -D "cn=admin,dc=ldap,dc=dcux,dc=com" -W -f /var/www/SSO/base.ldif -c # 添加根节点
ldapadd -x -D "cn=admin,dc=ldap,dc=dcux,dc=com" -W -f /var/www/SSO/data.ldif -c # 添加数据
ldapsearch -x -b "o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn" "uid=100001" # 查询
```

# 数据共享服务
```
// 挂载
mount -t cifs -o username=nobody //192.168.0.19/dcux_data /dcux_data
```

# Socket服务
```
nohup php ./bin/server/sso.php start >> ./log/sso.workerman.log 2>&1 & #其他选项：start|stop|restart|reload|status
```
