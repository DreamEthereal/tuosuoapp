<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit(' Security Violation');
}

include_once ROOT_PATH . 'License/License.xml';

$lang['MySQL_error'] = '���ݿ����';
$lang['error_system'] = 'ϵͳ����';
$lang['auth_error'] = 'ͨ��֤Ȩ�޴���';
$lang['passport_is_permit'] = '����ͨ��֤�޷�������ô������Ȩ��';
$lang['pls_register_soft'] = '';
$lang['limited_soft'] = '';
$lang['unreg_soft'] = '';
$lang['no_limited_soft'] = '����';
$lang['error_login'] = '��¼��������';
$lang['error_login_username'] = '������û��������룬�����ԣ���δ���ϵͳ�������������˻�';
$lang['error_login_password'] = '������û��������룬�����ԣ���δ���ϵͳ�������������˻�';
$lang['login_succeed'] = '��¼';
$lang['error_ip_banned'] = '����IP��ַ<b><font color=red>(' . _getip() . ')</font></b>�ѱ�ϵͳ���Σ����ϵͳ����Ա��ϵ';
$lang['error_refresh'] = '������ʾ��ֹ��ԭ�򣺷���ͬһURL��ˢ��ʱ��С��' . $Config['refresh_allowed'] . '��';
$lang['error_max_refresh'] = '����ζ�ʱ����ˢ����ҳ������IP��ַ�ѱ����Σ����ϵͳ����Ա��ϵ';
$lang['error_login_active'] = '�����û�����ѱ����Σ�����ϵϵͳ����Ա';
$lang['error_login_role'] = '�����û������ϵͳ��׿��ϵͳ��¼ʱ�����ã�����ϵϵͳ����Ա';
$lang['error_ip_allow'] = '����IP��ַ<b><font color=red>(' . _getip() . ')</font></b>����ϵͳ������ʵ��б��ڣ����ϵͳ����Ա��ϵ';
$lang['next_page'] = '��һҳ';
$lang['prev_page'] = '��һҳ';
$lang['first_page'] = 'ҳ��';
$lang['last_page'] = 'ҳβ';
$lang['no_exist'] = '�������,������';
$lang['no_exist_password'] = '���������������';
$lang['change_password'] = '�޸�����';
$lang['delete_logs'] = 'ɾ��������־';
$lang['clear_logs'] = '��չ�����־';
$lang['delete_invaliduser_list'] = 'ɾ�������û���¼';
$lang['clear_invaliduser_list'] = '��ղ����û���¼';
$lang['member_login_invalid'] = '��Աϵͳ';
$lang['admin_login_invalid'] = '�������';
$lang['upload_error'] = '�ļ��ϴ�����';
$lang['upload_error_type'] = '�ϴ����ļ���ʽ����ϵͳ�涨��Χ�ڣ��ϴ����ܽ���';
$lang['company_name'] = '';
$lang['product_name'] = '�����ʾ��������';
$lang['modify_flag_error'] = '';
$lang['ver_Advance'] = '��ҵ��';
$lang['no_addon_license'] = '<font color=red>δ����</font>';
$lang['have_addon_license'] = '<font color=green>�Ѱ���</font>';

?>
