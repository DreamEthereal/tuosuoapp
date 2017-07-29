<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit(' Security Violation');
}

include_once ROOT_PATH . 'License/License.xml';

$lang['MySQL_error'] = '数据库错误';
$lang['error_system'] = '系统错误';
$lang['auth_error'] = '通行证权限错误';
$lang['passport_is_permit'] = '您的通行证无法让您获得此类操作权限';
$lang['pls_register_soft'] = '';
$lang['limited_soft'] = '';
$lang['unreg_soft'] = '';
$lang['no_limited_soft'] = '无限';
$lang['error_login'] = '登录发生错误';
$lang['error_login_username'] = '错误的用户名或密码，请重试，多次错误系统将会锁定您的账户';
$lang['error_login_password'] = '错误的用户名或密码，请重试，多次错误系统将会锁定您的账户';
$lang['login_succeed'] = '登录';
$lang['error_ip_banned'] = '您的IP地址<b><font color=red>(' . _getip() . ')</font></b>已被系统屏蔽，请和系统管理员联系';
$lang['error_refresh'] = '本次显示禁止，原因：访问同一URL的刷新时间小于' . $Config['refresh_allowed'] . '秒';
$lang['error_max_refresh'] = '您多次短时间内刷新网页，您的IP地址已被屏蔽，请和系统管理员联系';
$lang['error_login_active'] = '您的用户身份已被屏蔽，请联系系统管理员';
$lang['error_login_role'] = '您的用户身份在系统安卓端系统登录时不适用，请联系系统管理员';
$lang['error_ip_allow'] = '您的IP地址<b><font color=red>(' . _getip() . ')</font></b>不在系统允许访问的列表内，请和系统管理员联系';
$lang['next_page'] = '下一页';
$lang['prev_page'] = '上一页';
$lang['first_page'] = '页首';
$lang['last_page'] = '页尾';
$lang['no_exist'] = '输入错误,不存在';
$lang['no_exist_password'] = '您输入的密码有误！';
$lang['change_password'] = '修改密码';
$lang['delete_logs'] = '删除管理日志';
$lang['clear_logs'] = '清空管理日志';
$lang['delete_invaliduser_list'] = '删除不良用户记录';
$lang['clear_invaliduser_list'] = '清空不良用户记录';
$lang['member_login_invalid'] = '会员系统';
$lang['admin_login_invalid'] = '管理界面';
$lang['upload_error'] = '文件上传错误';
$lang['upload_error_type'] = '上传的文件格式不在系统规定范围内，上传不能进行';
$lang['company_name'] = '';
$lang['product_name'] = '在线问卷调查引擎';
$lang['modify_flag_error'] = '';
$lang['ver_Advance'] = '企业版';
$lang['no_addon_license'] = '<font color=red>未包含</font>';
$lang['have_addon_license'] = '<font color=green>已包含</font>';

?>
