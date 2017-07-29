<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$Config['version'] = 'EnableQ V10.20';
$Config['pubTime'] = '2015-04-30';
include_once ROOT_PATH . 'Config/config.php';
$Config['SQLerrorIsSend'] = '0';
$Config['SQLerrorIsDisplay'] = 1;
$Config['showSQLError'] = 0;
$Config['SQLerrorSendMail'] = 'services@itenable.com.cn';
$Config['gzCompress'] = true;
$Config['is_debug'] = 44;
$Config['sendFrom'] = 'enablesite@itenable.com.cn';
$Config['sendName'] = 'EnableQ';
$Config['mailServer'] = 'smtp.ym.163.com';
$Config['smtp25'] = '25';
$Config['isSmtp'] = 'y';
$Config['smtpName'] = 'enablesite@itenable.com.cn';
$Config['smtpPassword'] = 'sales9324';
$Config['refresh_allowed'] = '8';
$Config['max_refresh_num'] = '50';
$Config['max_login_num'] = '6';
$Config['socket_token'] = md5('enableq' . $Config['siteName'] . $Config['version'] . $Config['pubTime']);
$Config['turf_comb_num'] = 20000;
$Config['cascade_in_excel'] = 1;
$Config['max_excel_col'] = 10;
$Config['data_base64_enable'] = 1;
$Config['lbsURL'] = 'http://www.enableq.com/GPS/GetGPSByCell.php';
$Config['lbsBatchURL'] = 'http://www.enableq.com/GPS/BatchGetGPSByCell.php';
$Config['lbsCellURL'] = 'http://www.enableq.com/GPS/BatchGetTraceByCell.php';
$Config['isShowLicenseCheck'] = 1;
$Config['xssClean'] = 1;
$Config['xssCleanForce'] = 0;
$Config['xssForceWord'] = array('javascript', 'vbscript', 'applet', 'xml', 'script', 'object', 'alert', 'iframe', 'frame', 'frameset', 'bgsound', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onunload');
$Config['xssWord'] = array('javascript', 'script', 'iframe', 'frame');

?>
