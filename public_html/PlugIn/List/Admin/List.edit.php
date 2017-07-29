<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddListSubmit') || ($_POST['Action'] == 'AddListOver')) {
	if (!isset($_SESSION['PageToken14']) || ($_SESSION['PageToken14'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',maxSize=\'' . $_POST['maxSize'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken14']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_list_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddListSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_list_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_list_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_list_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('ListEditFile', 'ListModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

$EnableQCoreClass->replace('maxLength', $Row['length']);
$EnableQCoreClass->replace('maxRows', $Row['rows']);
$EnableQCoreClass->replace('isCheckType' . $Row['isCheckType'], 'selected');
$EnableQCoreClass->replace('isCheckType', $Row['isCheckType']);

if ($Row['maxSize'] == 0) {
	$EnableQCoreClass->replace('maxSize', '');
}
else {
	$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
}

if ($Row['minOption'] == 0) {
	if ($Row['maxOption'] == 0) {
		$EnableQCoreClass->replace('minOption', '');
	}
	else {
		$EnableQCoreClass->replace('minOption', '0');
	}
}
else {
	$EnableQCoreClass->replace('minOption', $Row['minOption']);
}

if ($Row['maxOption'] == 0) {
	$EnableQCoreClass->replace('maxOption', '');
}
else {
	$EnableQCoreClass->replace('maxOption', $Row['maxOption']);
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$_SESSION['PageToken14'] = session_id();
$EnableQCoreClass->parse('ListEdit', 'ListEditFile');
$EnableQCoreClass->output('ListEdit');

?>
