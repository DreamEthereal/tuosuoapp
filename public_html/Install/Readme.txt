
			INSTALL NOTES ABOUT ENABLEQ System


EnableQ 运行支撑环境参数的改变
-----------------------------------

[PHP.ini]
//非重要修正
y2k_compliance = On                       //Y2K问题兼容
max_execution_time = 200                  //PHP脚本最大执行时间
error_reporting  =  E_ALL & ~E_NOTICE     //显示除提醒外所有错误

//重要修正
short_open_tag = on                       //支持PHP短标签
register_globals = Off                    //将EGPCS变量注册为全局变量
magic_quotes_gpc = On                     //处理GET/POST/Cookie特殊字符
file_uploads = On                         //允许HTTP方式上载文件
post_max_size = 80M                       //$_POST数组数据最大大小
allow_url_fopen = On                      //允许打开远程文件
upload_max_filesize = 80M                 //HTTP方式上载文件的最大许可大小
session.use_cookies = 1                   //是否使用Cookies
session.auto_start = 0                    //脚本请求启动时初始化Session  
session.use_trans_sid = 0                 //自动跨页传递SessionID值
extension_dir=                            //指定扩展库路径
extension = php_gd2.dll                   //开启图片处理库，要求2.0.28版本以上
extension = php_iconv.dll                 //开启Iconv库，(Windows平台并把dlls/iconv.dll复制至Windows/System32下)
extension = php_mbstring.dll              //开启Mbstring库，(Windows平台并把dlls/php_mbstring.dll复制至Windows/System32下)
extension = php_ldap.dll                  //开启LDAP库(连接微软活动目录使用,Windows平台并将dlls/libeay32.dll和ssleay32.dll复制至Windows/System32下)


Notes:
  . PHP Version >= 5.2.0
  . 如PHP采用编译方式安装，请注意添加--enable-bcmath编译选项
  . 如果php.ini中有限定资源的请求，请依据情况调整，下列参数影响系统运行资源请求
	 max_execution_time = 300
	 max_input_time = 600
	 memory_limit = 512M

[httpd.conf]
DirectoryIndex default.html index.html index.php
EnableMMAP off       //Apache 2.0.44以上,显示图片不全
EnableSendfile off   //Apache 2.0.44以上

Notes:
  不建议以下面的CGI方式处理PHP与Apache的链接：
	AddType application/x-httpd-php .php
	ScriptAlias /php/ "c:/php/"
	Action application/x-httpd-php "/php/php.exe"
  建议以下方式：
	AddType application/x-httpd-php .php
	LoadModule php4_module c:/php/sapi/php4apache2.dll


EnableQ 程序目录权限
--------------------------------

请方便修改EnableQ系统安装的物理目录下Templates/、Cache/、License以及PerUserData/四个子目录对于apache用户具有写的权限


EnableQ 在Mysql v5上运行
--------------------------------

EnableQ 默认在Mysql V4.1以下版本上运行
在Mysql V4.1以上版本上的修订：
1)利用Mysql客户端工具进入Mysql系统后运行
　SET PASSWORD FOR 'root'@'localhost' = OLD_PASSWORD('12345678');
　
  Notes:
   以上小写字符依据实际情况修改，同时修改config.php文件
   此修订为解决PHP 4.x连接不上Mysql V4.1以上数据库错误
   
2) 在Mysql安装目录下编辑my.ini文件，把以下一行注释：
　　sql-mode="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
　 在服务中重新启动Mysql服务；
 
  Notes:
   此修订为解决Mysql V4.1以上数据表自增字段插入错误
   此修订也为Mysql V5数据类型精确匹配导致的数据插入错误 
