
			INSTALL NOTES ABOUT ENABLEQ System


EnableQ ����֧�Ż��������ĸı�
-----------------------------------

[PHP.ini]
//����Ҫ����
y2k_compliance = On                       //Y2K�������
max_execution_time = 200                  //PHP�ű����ִ��ʱ��
error_reporting  =  E_ALL & ~E_NOTICE     //��ʾ�����������д���

//��Ҫ����
short_open_tag = on                       //֧��PHP�̱�ǩ
register_globals = Off                    //��EGPCS����ע��Ϊȫ�ֱ���
magic_quotes_gpc = On                     //����GET/POST/Cookie�����ַ�
file_uploads = On                         //����HTTP��ʽ�����ļ�
post_max_size = 80M                       //$_POST������������С
allow_url_fopen = On                      //�����Զ���ļ�
upload_max_filesize = 80M                 //HTTP��ʽ�����ļ��������ɴ�С
session.use_cookies = 1                   //�Ƿ�ʹ��Cookies
session.auto_start = 0                    //�ű���������ʱ��ʼ��Session  
session.use_trans_sid = 0                 //�Զ���ҳ����SessionIDֵ
extension_dir=                            //ָ����չ��·��
extension = php_gd2.dll                   //����ͼƬ����⣬Ҫ��2.0.28�汾����
extension = php_iconv.dll                 //����Iconv�⣬(Windowsƽ̨����dlls/iconv.dll������Windows/System32��)
extension = php_mbstring.dll              //����Mbstring�⣬(Windowsƽ̨����dlls/php_mbstring.dll������Windows/System32��)
extension = php_ldap.dll                  //����LDAP��(����΢��Ŀ¼ʹ��,Windowsƽ̨����dlls/libeay32.dll��ssleay32.dll������Windows/System32��)


Notes:
  . PHP Version >= 5.2.0
  . ��PHP���ñ��뷽ʽ��װ����ע�����--enable-bcmath����ѡ��
  . ���php.ini�����޶���Դ������������������������в���Ӱ��ϵͳ������Դ����
	 max_execution_time = 300
	 max_input_time = 600
	 memory_limit = 512M

[httpd.conf]
DirectoryIndex default.html index.html index.php
EnableMMAP off       //Apache 2.0.44����,��ʾͼƬ��ȫ
EnableSendfile off   //Apache 2.0.44����

Notes:
  �������������CGI��ʽ����PHP��Apache�����ӣ�
	AddType application/x-httpd-php .php
	ScriptAlias /php/ "c:/php/"
	Action application/x-httpd-php "/php/php.exe"
  �������·�ʽ��
	AddType application/x-httpd-php .php
	LoadModule php4_module c:/php/sapi/php4apache2.dll


EnableQ ����Ŀ¼Ȩ��
--------------------------------

�뷽���޸�EnableQϵͳ��װ������Ŀ¼��Templates/��Cache/��License�Լ�PerUserData/�ĸ���Ŀ¼����apache�û�����д��Ȩ��


EnableQ ��Mysql v5������
--------------------------------

EnableQ Ĭ����Mysql V4.1���°汾������
��Mysql V4.1���ϰ汾�ϵ��޶���
1)����Mysql�ͻ��˹��߽���Mysqlϵͳ������
��SET PASSWORD FOR 'root'@'localhost' = OLD_PASSWORD('12345678');
��
  Notes:
   ����Сд�ַ�����ʵ������޸ģ�ͬʱ�޸�config.php�ļ�
   ���޶�Ϊ���PHP 4.x���Ӳ���Mysql V4.1�������ݿ����
   
2) ��Mysql��װĿ¼�±༭my.ini�ļ���������һ��ע�ͣ�
����sql-mode="STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
�� �ڷ�������������Mysql����
 
  Notes:
   ���޶�Ϊ���Mysql V4.1�������ݱ������ֶβ������
   ���޶�ҲΪMysql V5�������;�ȷƥ�䵼�µ����ݲ������ 
