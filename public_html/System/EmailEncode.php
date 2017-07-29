<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != '1') {
	_showerror($lang['system_error'], $lang['no_exe_survey']);
}

$nowTime = date('Y-m-d', time());

if ($nowTime < $S_Row['beginTime']) {
	_showerror($lang['system_error'], $lang['no_start_survey']);
}

if ($S_Row['endTime'] < $nowTime) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
	$DB->query($SQL);
	_showerror($lang['system_error'], $lang['end_survey_now']);
}

if ($S_Row['maxResponseNum'] != 0) {
	$SQL = ' SELECT COUNT(*) AS maxResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
	$CountRow = $DB->queryFirstRow($SQL);

	if ($S_Row['maxResponseNum'] <= $CountRow['maxResponseNum']) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
		$DB->query($SQL);
		_showerror($lang['system_error'], $lang['max_num_survey']);
	}
}

if ($_POST['Action'] == 'EmailEncodeSubmit') {
	@set_time_limit(0);
	$SendMembersEmail = explode(';', $_POST['panelUserName']);
	$SendMembersEmail = array_unique($SendMembersEmail);
	if ((0 < count($SendMembersEmail)) && !empty($SendMembersEmail)) {
		$content = '';
		$header = '"邮件地址"';
		$header .= ',"转码后字符"';
		$header .= "\r\n";
		$content .= $header;

		foreach ($SendMembersEmail as $sendMailName) {
			$sendMailName = strtolower(trim($sendMailName));
			if (($sendMailName != '') && checkemail($sendMailName)) {
				$content .= '"' . $sendMailName . '"';
				$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName))));
				$content .= ',"' . $theUserEmailURL . '"';
				$content .= "\r\n";
			}
		}

		header('Pragma: no-cache');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Type: application/octet-stream;charset=utf8');
		header('Content-Disposition: attachment; filename=Email_Encode_List_' . date('Y-m-d') . '.csv');
		echo $content;
		exit();
	}
	else {
		_showerror('系统错误', '系统错误：需要转码的电子邮件地址为空！');
	}

	exit();
}

if (!isset($_POST['Action'])) {
	$EnableQCoreClass->setTemplateFile('EmailEncodeFile', 'EmailEncode.html');
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$SQL = ' SELECT surveyName,tokenCode,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$SurRow = $DB->queryFirstRow($SQL);
	$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
	$surveyURL = $ProgURL . 'q.php?qname=' . $SurRow['surveyName'] . '&qlang=' . $SurRow['lang'];
	$EnableQCoreClass->replace('surveyURL', $surveyURL);
	$EnableQCoreClass->parse('EmailEncodePage', 'EmailEncodeFile');
	$EnableQCoreClass->output('EmailEncodePage');
}

?>
