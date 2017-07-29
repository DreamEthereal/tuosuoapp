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

if ($_POST['Action'] == 'AddHiddenSubmit') {
	if (!isset($_SESSION['PageToken12']) || ($_SESSION['PageToken12'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'12\',hiddenVarName=\'' . trim($_POST['hiddenVarName']) . '\',hiddenFromSession=\'' . trim($_POST['hiddenFromSession']) . '\',isNeg = \'' . $_POST['isNeg'] . '\',isRequired=\'' . $_POST['isRequired'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken12']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_Hidden_question'] . ':' . $questionName);
		_showmessage($lang['add_Hidden_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',hiddenVarName=\'' . trim($_POST['hiddenVarName']) . '\',hiddenFromSession=\'' . trim($_POST['hiddenFromSession']) . '\',isNeg = \'' . $_POST['isNeg'] . '\',isRequired=\'' . $_POST['isRequired'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken12']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_Hidden_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_Hidden_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_Hidden_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddHiddenOver') {
	if (!isset($_SESSION['PageToken12']) || ($_SESSION['PageToken12'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'12\',hiddenVarName=\'' . trim($_POST['hiddenVarName']) . '\',hiddenFromSession=\'' . trim($_POST['hiddenFromSession']) . '\',isNeg = \'' . $_POST['isNeg'] . '\',isRequired=\'' . $_POST['isRequired'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken12']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_Hidden_question'] . ':' . $questionName);
		_showmessage($lang['add_Hidden_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',hiddenVarName=\'' . trim($_POST['hiddenVarName']) . '\',hiddenFromSession=\'' . trim($_POST['hiddenFromSession']) . '\',isNeg = \'' . $_POST['isNeg'] . '\',isRequired=\'' . $_POST['isRequired'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken12']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_Hidden_question'] . ':' . $questionName);
		_showmessage($lang['add_Hidden_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('HiddenEditFile', 'HiddenEdit.html');

if ($_GET['questionID'] != '') {
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
}
else {
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('hiddenVarName', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', '');
	$EnableQCoreClass->replace('isCookie', '');
}

$_SESSION['PageToken12'] = session_id();
$EnableQCoreClass->parse('HiddenEdit', 'HiddenEditFile');
$EnableQCoreClass->output('HiddenEdit');

?>
