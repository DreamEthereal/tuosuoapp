<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddUploadSubmit') || ($_POST['Action'] == 'AddUploadOver')) {
	if (!isset($_SESSION['PageToken11']) || ($_SESSION['PageToken11'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',allowType=\'' . $_POST['allowType'] . '\',hiddenVarName=\'' . implode(',', $_POST['hiddenVarName']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken11']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_upload_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddUploadSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_upload_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_upload_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_upload_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('UploadEditFile', 'UploadEdit.html');
$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

if ($POST_MAX_SIZE) {
	$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
	$EnableQCoreClass->replace('postMaxSize', $thePostMaxSize);
}
else {
	$EnableQCoreClass->replace('postMaxSize', 2);
}

$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

$EnableQCoreClass->replace('maxLength', $Row['length']);
$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
$EnableQCoreClass->replace('allowType', $Row['allowType']);
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$theHiddenVarName = explode(',', $Row['hiddenVarName']);

foreach ($theHiddenVarName as $thisHiddenVarName) {
	$EnableQCoreClass->replace('hiddenVarName_' . $thisHiddenVarName, 'checked');
}

$_SESSION['PageToken11'] = session_id();
$EnableQCoreClass->parse('UploadEdit', 'UploadEditFile');
$EnableQCoreClass->output('UploadEdit');

?>
