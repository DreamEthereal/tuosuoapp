<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddHiddenSubmit') || ($_POST['Action'] == 'AddHiddenOver')) {
	if (!isset($_SESSION['PageToken12']) || ($_SESSION['PageToken12'] != session_id())) {
		_showerror('��ȫ����', '��ȫ����ϵͳ��鵽���ı������Ѿ��ύ��������Ҫ��β�����');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',hiddenVarName=\'' . trim($_POST['hiddenVarName']) . '\',hiddenFromSession=\'' . trim($_POST['hiddenFromSession']) . '\',isNeg = \'' . $_POST['isNeg'] . '\',isRequired=\'' . $_POST['isRequired'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken12']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_Hidden_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddHiddenSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_Hidden_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_Hidden_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_Hidden_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('HiddenEditFile', 'HiddenEdit.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('hiddenVarName', qshowquotestring($Row['hiddenVarName']));
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('hiddenFromSession_' . $Row['hiddenFromSession'], 'selected');

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['isNeg'] == '1') {
	$EnableQCoreClass->replace('isNeg', 'checked');
}
else {
	$EnableQCoreClass->replace('isNeg', '');
}

$_SESSION['PageToken12'] = session_id();
$EnableQCoreClass->parse('HiddenEdit', 'HiddenEditFile');
$EnableQCoreClass->output('HiddenEdit');

?>
