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

if ($_POST['Action'] == 'AddYesNoSubmit') {
	if (!isset($_SESSION['PageToken1']) || ($_SESSION['PageToken1'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'1\',isRequired=\'' . $_POST['isRequired'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',isSelect=\'' . $_POST['radioType'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_1'] . '\' ';
		$DB->query($SQL);
		$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_2'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken1']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_yesno_question'] . ':' . $questionName);
		_showmessage($lang['add_yesno_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',isSelect=\'' . $_POST['radioType'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['radioType'] != $_POST['radioType_ori']) {
			$SQL = ' DELETE FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_1'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_2'] . '\' ';
			$DB->query($SQL);
		}

		unset($_SESSION['PageToken1']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_yesno_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_yesno_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_yesno_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddYesNoOver') {
	if (!isset($_SESSION['PageToken1']) || ($_SESSION['PageToken1'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'1\',isRequired=\'' . $_POST['isRequired'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',isSelect=\'' . $_POST['radioType'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_1'] . '\' ';
		$DB->query($SQL);
		$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_2'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken1']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_yesno_question'] . ':' . $questionName);
		_showmessage($lang['add_yesno_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',isSelect=\'' . $_POST['radioType'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['radioType'] != $_POST['radioType_ori']) {
			$SQL = ' DELETE FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_1'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . $lang['yesno_' . $_POST['radioType'] . '_2'] . '\' ';
			$DB->query($SQL);
		}

		unset($_SESSION['PageToken1']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_yesno_question'] . ':' . $questionName);
		_showmessage($lang['add_yesno_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('YesNoEditFile', 'YesNoEdit.html');

if ($_GET['questionID'] != '') {
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

	$EnableQCoreClass->replace('radioType' . $Row['isSelect'], 'checked');
	$EnableQCoreClass->replace('radioType', $Row['isSelect']);
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
}
else {
	$EnableQCoreClass->replace('isRequired', 'checked');
	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('radioType1', 'checked');
	$EnableQCoreClass->replace('radioType', '0');
}

$_SESSION['PageToken1'] = session_id();
$EnableQCoreClass->parse('YesNoEdit', 'YesNoEditFile');
$EnableQCoreClass->output('YesNoEdit');

?>
