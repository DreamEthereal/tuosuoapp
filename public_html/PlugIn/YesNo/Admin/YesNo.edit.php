<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddYesNoSubmit') || ($_POST['Action'] == 'AddYesNoOver')) {
	if (!isset($_SESSION['PageToken1']) || ($_SESSION['PageToken1'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken1']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_yesno_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddYesNoSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_yesno_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_yesno_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_yesno_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('YesNoEditFile', 'YesNoModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['isColArrange'] == '1') {
	$EnableQCoreClass->replace('isColArrange', 'checked');
}
else {
	$EnableQCoreClass->replace('isColArrange', '');
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$SQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ORDER BY question_yesnoID ASC ';
$oResult = $DB->query($SQL);
$optionName = '';

while ($oRow = $DB->queryArray($oResult)) {
	$optionName .= $oRow['optionName'] . ',';
}

$EnableQCoreClass->replace('optionName', substr($optionName, 0, -1));
$_SESSION['PageToken1'] = session_id();
$EnableQCoreClass->parse('YesNoEdit', 'YesNoEditFile');
$EnableQCoreClass->output('YesNoEdit');

?>
