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

if ($_POST['Action'] == 'AddAutoWeightSubmit') {
	if (!isset($_SESSION['PageToken22']) || ($_SESSION['PageToken22'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'22\',isRequired=\'' . $_POST['isRequired'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		updateorderid('question');
		unset($_SESSION['PageToken22']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autoweight_question'] . ':' . $questionName);
		_showmessage($lang['add_autoweight_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken22']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autoweight_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_autoweight_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_autoweight_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddAutoWeightOver') {
	if (!isset($_SESSION['PageToken22']) || ($_SESSION['PageToken22'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'22\',isRequired=\'' . $_POST['isRequired'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken22']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autoweight_question'] . ':' . $questionName);
		_showmessage($lang['add_autoweight_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken22']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autoweight_question'] . ':' . $questionName);
		_showmessage($lang['add_autoweight_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoWeightEditFile', 'AutoWeightEdit.html');

if ($_GET['questionID'] != '') {
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

	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID < \'' . $Row['orderByID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);

		if ($Row['baseID'] == $baseRow['questionID']) {
			$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\' selected>' . $baseQuestionName . '</option>';
		}
		else {
			$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
		}
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}
else {
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('maxSize', '100');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken22'] = session_id();
$EnableQCoreClass->parse('AutoWeightEdit', 'AutoWeightEditFile');
$EnableQCoreClass->output('AutoWeightEdit');

?>
