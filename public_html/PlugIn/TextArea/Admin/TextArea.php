<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'DesignSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->replace('addURL', $lastProg . '&DO=Add');
$EnableQCoreClass->replace('listURL', $lastProg);
$EnableQCoreClass->replace('questionListURL', $lastProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

if ($_POST['Action'] == 'AddTextAreaSubmit') {
	if (!isset($_SESSION['PageToken5']) || ($_SESSION['PageToken5'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'5\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',rows=\'' . $_POST['rows'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken5']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_textarea_question'] . ':' . $questionName);
		_showmessage($lang['add_textarea_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',rows=\'' . $_POST['rows'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken5']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_textarea_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_textarea_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_textarea_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddTextAreaOver') {
	if (!isset($_SESSION['PageToken5']) || ($_SESSION['PageToken5'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'5\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',rows=\'' . $_POST['rows'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken5']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_textarea_question'] . ':' . $questionName);
		_showmessage($lang['add_textarea_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',rows=\'' . $_POST['rows'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken5']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_textarea_question'] . ':' . $questionName);
		_showmessage($lang['add_textarea_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('TextAreaEditFile', 'TextAreaEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	$EnableQCoreClass->replace('maxLength', $Row['length']);
	$EnableQCoreClass->replace('rows', $Row['rows']);
	$EnableQCoreClass->replace('isCheckType' . $Row['isCheckType'], 'selected');
	$EnableQCoreClass->replace('isCheckType', $Row['isCheckType']);

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
}
else {
	$EnableQCoreClass->replace('isRequired', 'checked');
	$EnableQCoreClass->replace('maxLength', '70');
	$EnableQCoreClass->replace('rows', '4');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('isCheckType0', 'selected');
	$EnableQCoreClass->replace('isCheckType2', '');
	$EnableQCoreClass->replace('isCheckType', 0);
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
}

$_SESSION['PageToken5'] = session_id();
$EnableQCoreClass->parse('TextAreaEdit', 'TextAreaEditFile');
$EnableQCoreClass->output('TextAreaEdit');

?>
