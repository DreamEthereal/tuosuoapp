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

if ($_POST['Action'] == 'AddInfoSubmit') {
	if (!isset($_SESSION['PageToken9']) || ($_SESSION['PageToken9'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $_POST['optionName'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken9']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_info_question'] . ':' . $questionName);
		_showmessage($lang['add_info_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' SELECT optionName FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$SQL = ' UPDATE ' . QUESTION_INFO_TABLE . ' SET optionName=\'' . $_POST['optionName'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		}
		else {
			$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $_POST['optionName'] . '\' ';
		}

		$DB->query($SQL);
		unset($_SESSION['PageToken9']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_info_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_info_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_info_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddInfoOver') {
	if (!isset($_SESSION['PageToken9']) || ($_SESSION['PageToken9'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $_POST['optionName'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken9']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_info_question'] . ':' . $questionName);
		_showmessage($lang['add_info_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' SELECT optionName FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$SQL = ' UPDATE ' . QUESTION_INFO_TABLE . ' SET optionName=\'' . $_POST['optionName'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		}
		else {
			$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $_POST['optionName'] . '\' ';
		}

		$DB->query($SQL);
		unset($_SESSION['PageToken9']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_info_question'] . ':' . $questionName);
		_showmessage($lang['add_info_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('InfoEditFile', 'InfoEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT questionID,questionName,orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$SQL = ' SELECT optionName FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('optionName', $Row['optionName']);
}
else {
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_infoname']);
}

$EnableQCoreClass->replace('scriptPath', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23));
$_SESSION['PageToken9'] = session_id();
$EnableQCoreClass->parse('InfoEdit', 'InfoEditFile');
$EnableQCoreClass->output('InfoEdit');

?>
