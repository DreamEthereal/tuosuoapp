<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddEmptySubmit') || ($_POST['Action'] == 'AddEmptyOver')) {
	if (!isset($_SESSION['PageToken30']) || ($_SESSION['PageToken30'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);

	if ($_POST['requiredMode'] == 1) {
		$SQL = ' SELECT weight FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$hRow = $DB->queryFirstRow($SQL);

		if ($hRow['weight'] != 0) {
			$SQL = ' UPDATE ' . RELATION_TABLE . ' SET relationDefine = 1 WHERE relationID = \'' . $hRow['weight'] . '\' ';
			$DB->query($SQL);
		}
	}
	else {
		$lSQL = ' SELECT questionID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND quotaID = 0 AND questionID=\'' . $_POST['questionID'] . '\' LIMIT 1 ';
		$lRow = $DB->queryFirstRow($lSQL);

		if ($lRow) {
			_showerror('一致性错误', '一致性检查错误：此问题上已存在逻辑关系，不能转化成存储数值关联表达式逻辑运算结果的空题类型');
		}
	}

	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' ';

	if ($_POST['requiredMode'] == 1) {
		$SQL .= ',weight=0 ';
	}

	$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken30']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_empty_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddEmptySubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_empty_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_empty_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_empty_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('EmptyEditFile', 'EmptyEdit.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('requiredMode_' . $Row['requiredMode'], 'checked');
if (($Row['requiredMode'] == 2) && ($Row['weight'] != 0)) {
	$EnableQCoreClass->replace('relationName', '<font color=green>已与数值关联表达式</font><font color=red><b>(' . $Row['weight'] . ')</b></font><font color=green>对应</font>');
}
else {
	$EnableQCoreClass->replace('relationName', '<font color=red>尚未对应数值关联关系定义</font>');
}

$_SESSION['PageToken30'] = session_id();
$EnableQCoreClass->parse('EmptyEdit', 'EmptyEditFile');
$EnableQCoreClass->output('EmptyEdit');

?>
