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

if ($_POST['Action'] == 'AddUploadSubmit') {
	if (!isset($_SESSION['PageToken11']) || ($_SESSION['PageToken11'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'11\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',allowType=\'' . $_POST['allowType'] . '\',hiddenVarName=\'' . implode(',', $_POST['hiddenVarName']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken11']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_upload_question'] . ':' . $questionName);
		_showmessage($lang['add_upload_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',allowType=\'' . $_POST['allowType'] . '\',hiddenVarName=\'' . implode(',', $_POST['hiddenVarName']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken11']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_upload_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_upload_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_upload_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddUploadOver') {
	if (!isset($_SESSION['PageToken11']) || ($_SESSION['PageToken11'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'11\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',allowType=\'' . $_POST['allowType'] . '\',hiddenVarName=\'' . implode(',', $_POST['hiddenVarName']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken11']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_upload_question'] . ':' . $questionName);
		_showmessage($lang['add_upload_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',allowType=\'' . $_POST['allowType'] . '\',hiddenVarName=\'' . implode(',', $_POST['hiddenVarName']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken11']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_upload_question'] . ':' . $questionName);
		_showmessage($lang['add_upload_question'] . ':' . $questionName, true, $_POST['questionID']);
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
}
else {
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('maxLength', '25');
	$EnableQCoreClass->replace('maxSize', '2');
	$EnableQCoreClass->replace('allowType', '.rar|.zip|.gzip|.pdf|.doc|.jpg|.jpeg|.gif|.bmp|.png|.txt|.xls|');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('hiddenVarName_1', 'checked');
	$EnableQCoreClass->replace('hiddenVarName_2', '');
	$EnableQCoreClass->replace('hiddenVarName_3', '');
}

$_SESSION['PageToken11'] = session_id();
$EnableQCoreClass->parse('UploadEdit', 'UploadEditFile');
$EnableQCoreClass->output('UploadEdit');

?>
