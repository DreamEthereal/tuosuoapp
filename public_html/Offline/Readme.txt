			INSTALL NOTES ABOUT ENABLEQ ANDROID OFFLINE


有关安卓离线运行环境的改变
-----------------------------------

因离线客户端一般都采用全程录音|录像，而一般录音|录像文件都较大，为保证数据同步成功，Apache/PHP环境要修正其部分参数

Apache / httpd.conf:
   
   LimitRequestBody(设定值为字节数)：
   
   要求：
    
   LimitRequestBody > 录音|录像文件可能的最大尺寸 * 1024 * 1024
   可为0，代表无限制


Php / php.ini:

   upload_max_filesize:允许上传文件大小的最大值,默认为2M
   memory_limit:每个PHP页面所吃掉的最大内存,默认为8M
   post_max_size:指通过表单POST给PHP的所能接收的最大值,默认为8M
   max_execution_time:每个PHP页面运行的最大时间值(秒),默认为30秒
   max_input_time:每个PHP页面接收数据所需的最大时间,默认为60秒
   
   要求：
   
   memory_limit >  post_max_size > upload_max_filesize > 录音|录像文件可能的最大尺寸
   max_input_time > 录音|录像文件在正常局域/3G网络传输所需要的可能最大时间
   max_execution_time > 录音|录像文件在正常局域/3G网络传输所需要的可能最大时间


有关安卓离线程序界面显示样式
-----------------------------------

系统已准备的两种样式，在Offline/resources/
phone.css: 建议在小屏幕手机环境下使用
pad10.css: 建议在较大屏幕Pad环境下使用
系统一般会自动检测设备自动匹配


有关安卓离线程序过场效果
-----------------------------------

配置文件在：Offline/script/animation.js
可根据不同用户需求配置程序页面的过场特效
默认无过场特效


有关安卓离线程序可能需要的其他程序
-----------------------------------

确定需要的：

1) 搜狗手机输入法
2) Adobe Flash Player for Android(V8.10版本后不需要)
3) 可供其他程序调用的照相机程序
4) 可供其他程序调用的录音机程序




