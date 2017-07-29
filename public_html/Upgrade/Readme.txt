
			RELEASE NOTES ABOUT UPGRADE ENABLEQ
			
			
------------------------------
Readme About Upgrade EnableQ
------------------------------

使用EnableQ产品Windows集成化安装包升级请按照以下步骤进行：

(1) 登录原系统自"关于..."得到原版本号
(2) 查看EnableQ程序运行图标属性得到"起始位置"
(3) 上述"起始位置"下的enableq子目录为EnableQ系统程序目录
(4) 备份EnableQ系统程序目录下部分文件或目录至其他物理目录：
    a) Config/config.php 
    b) PerUserData/目录下全部文件 
    c) Templates/CN/目录下以“Panel_”字符串打头的全部文件 
(5) 复制新系统程序覆盖原程序目录;
(6) 原程序版本号在版本V1.50以下，请在备份的config.php文件$DB_name行下新增一行为：
    $DB_lang = 'latin1';
(7) 自按照(4)步骤备份的目录或文件复制至对应目录覆盖原文件
(8) 执行本目录下对应版本升级程序升级数据库表结构。


使用EnableQ产品PHP裸程序安装升级请按照以下步骤进行：

(1) 登录原系统自"关于..."得到原版本号
(2) 备份EnableQ系统程序安装目录下部分文件或目录至其他物理目录：
    a) Config/config.php 
    b) PerUserData/目录下全部文件 
    c) Templates/CN/目录下以“Panel_”字符串打头的全部文件 
(3) 复制新系统程序覆盖原程序目录;
(4) 原程序版本号在版本V1.50以下，请在备份的config.php文件$DB_name行下新增一行为：
    $DB_lang = 'latin1';  
    如EnableQ连接Mysql V4.1以上，请使用数据库正确的字符集替换latin1;    
(5) 自按照(2)步骤备份的目录或文件复制至对应目录覆盖原文件
(6) 执行本目录下对应版本升级程序升级数据库表结构。




