<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddAutoWeightSubmit') || ($_POST['Action'] == 'AddAutoWeightOver')) {
	if (!isset($_SESSION['PageToken22']) || ($_SESSION['PageToken22'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken22']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_autoweight_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddAutoWeightSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_autoweight_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_autoweight_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_autoweight_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoWeightEditFile', 'AutoWeightModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
$EnableQCoreClass->replace('alias', $Row['alias']);

switch ($Row['isNeg']) {
case 0:
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	break;

case 1:
	$EnableQCoreClass->replace('isNeg1', 'checked');
	$EnableQCoreClass->replace('isNeg0', '');
	break;
}

$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' AND questionID = \'' . $Row['baseID'] . '\' ';
$baseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('baseQuestionList', qnohtmltag($baseRow['questionName'], 1));
$_SESSION['PageToken22'] = session_id();
$EnableQCoreClass->parse('AutoWeightEdit', 'AutoWeightEditFile');
$EnableQCoreClass->output('AutoWeightEdit');

?>
