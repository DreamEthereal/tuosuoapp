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

if ($_POST['Action'] == 'AddEmptySubmit') {
	if (!isset($_SESSION['PageToken30']) || ($_SESSION['PageToken30'] != session_id())) {
		_showerror('��ȫ����', '��ȫ����ϵͳ��鵽���ı������Ѿ��ύ��������Ҫ��β�����');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'30\',requiredMode=\'' . $_POST['requiredMode'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken30']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_empty_question'] . ':' . $questionName);
		_showmessage($lang['add_empty_question'] . ':' . $questionName, true, 1);
	}
	else {
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
				_showerror('һ���Դ���', 'һ���Լ����󣺴��������Ѵ����߼���ϵ������ת���ɴ洢��ֵ�������ʽ�߼��������Ŀ�������');
			}
		}

		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' ';

		if ($_POST['requiredMode'] == 1) {
			$SQL .= ',weight=0 ';
		}

		$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken30']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_empty_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_empty_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_empty_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddEmptyOver') {
	if (!isset($_SESSION['PageToken30']) || ($_SESSION['PageToken30'] != session_id())) {
		_showerror('��ȫ����', '��ȫ����ϵͳ��鵽���ı������Ѿ��ύ��������Ҫ��β�����');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'30\',requiredMode=\'' . $_POST['requiredMode'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken30']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_empty_question'] . ':' . $questionName);
		_showmessage($lang['add_empty_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
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
				_showerror('һ���Դ���', 'һ���Լ����󣺴��������Ѵ����߼���ϵ������ת���ɴ洢��ֵ�������ʽ�߼��������Ŀ�������');
			}
		}

		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' ';

		if ($_POST['requiredMode'] == 1) {
			$SQL .= ',weight=0 ';
		}

		$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken30']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_empty_question'] . ':' . $questionName);
		_showmessage($lang['add_empty_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('EmptyEditFile', 'EmptyEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('requiredMode_' . $Row['requiredMode'], 'checked');
	if (($Row['requiredMode'] == 2) && ($Row['weight'] != 0)) {
		$EnableQCoreClass->replace('relationName', '<font color=green>������ֵ�������ʽ</font><font color=red><b>(' . $Row['weight'] . ')</b></font><font color=green>��Ӧ</font>');
	}
	else {
		$EnableQCoreClass->replace('relationName', '<font color=red>��δ��Ӧ��ֵ������ϵ����</font>');
	}
}
else {
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', '');
	$EnableQCoreClass->replace('requiredMode_1', 'checked');
	$EnableQCoreClass->replace('requiredMode_2', '');
	$EnableQCoreClass->replace('relationName', '<font color=red>��δ��Ӧ��ֵ������ϵ����</font>');
}

$_SESSION['PageToken30'] = session_id();
$EnableQCoreClass->parse('EmptyEdit', 'EmptyEditFile');
$EnableQCoreClass->output('EmptyEdit');

?>
