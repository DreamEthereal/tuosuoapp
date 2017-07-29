
			INSTALL NOTES ABOUT ENABLEQ System


有关EnableQ系统基于多Web服务器下的环境安装
------------------------------------------

EnableQ系统如果利用LVS+多web服务器环境实现访问分流，以实现高负载下的应用压力，那么其对应的改变是：

1) 修改Config/MMConfig.inc.php文件中$Config['dataDirectory']变量的值为'RepData'，
   即将问卷回复的附属文件存储在‘RepData’中；
2) 修改Config/MMConfig.inc.php文件中$Config['cacheDirectory']变量的值为'PerUserData/Cache'，
   即将问卷缓存文件存储在‘PerUserData/Cache’中；
3) 利用rsync系统工具将PerUserData/目录下的文件数据自管理服务器实时同步到多个Web服务器；
4) 利用rsync系统工具将RepData/目录下的回复文件数据自多个Web服务器实时同步到管理服务器；
5) 修改Config/MMConfig.inc.php文件中$Config['dataDomainName']变量的值为问卷数据回收服务的域名(可能包含虚拟路径)；
6) 修改Config/MMConfig.inc.php文件中$Config['pubDomainName']变量的值为管理服务器的域名或IP地址(可能包含虚拟路径)；
7) 修改Config/MMConfig.inc.php文件中$Config['serverAddress']变量的值为问卷数据回收服务器的IP地址数组(可能包含虚拟路径)。

