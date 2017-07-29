<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddCondSubmit') || ($_POST['Action'] == 'AddCondOver')) {
	if (!isset($_SESSION['PageToken18']) || ($_SESSION['PageToken18'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken18']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_cond_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddCondSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_cond_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_cond_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_cond_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CondEditFile', 'CondModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['isSelect'] == '1') {
	$EnableQCoreClass->replace('isSelect', 'checked');
}
else {
	$EnableQCoreClass->replace('isSelect', '');
}

if ($Row['minOption'] == 0) {
	$EnableQCoreClass->replace('minOption', '');
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
$EnableQCoreClass->replace('baseID', $Row['baseID']);
$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=2 OR questionType=24) AND surveyID=\'' . $_GET['surveyID'] . '\' AND questionID = \'' . $Row['baseID'] . '\' ';
$baseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('baseQuestionList', qnohtmltag($baseRow['questionName'], 1));
$EnableQCoreClass->replace('baseID', $Row['baseID']);
$_SESSION['PageToken18'] = session_id();
$EnableQCoreClass->parse('CondEdit', 'CondEditFile');
$EnableQCoreClass->output('CondEdit');

?>
