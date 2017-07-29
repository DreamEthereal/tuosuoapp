
			INSTALL NOTES ABOUT ENABLEQ System


有关EnableQ系统基于Mysql-Proxy下的环境安装
------------------------------------------

EnableQ系统如果利用Mysql-Proxy环境实现数据库读写分离，以实现高负载下的应用压力，那么其安装过程应该是：

1) 利用现有的Install/index.php进行系统安装，其数据库链接字符串填写负责写的Master数据库服务器；
2) 将Config/config.php文件命名为config.php.lock，并将Config/config.proxy.php文件命名为config.php;
3) 编辑新的config.php文件，配置Mysql-Proxy数据库服务器群对应的链接字符串；
4) 修改Config/MMConfig.inc.php文件中$Config['is_mysql_proxy']变量的值为1;
5) 运行系统

