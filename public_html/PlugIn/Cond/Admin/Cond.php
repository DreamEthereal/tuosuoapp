<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Functions/Functions.check.inc.php';
$lastProg = 'DesignSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->replace('addURL', $lastProg . '&DO=Add');
$EnableQCoreClass->replace('listURL', $lastProg);
$EnableQCoreClass->replace('questionListURL', $lastProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

if ($_POST['Action'] == 'AddCondSubmit') {
	if (!isset($_SESSION['PageToken18']) || ($_SESSION['PageToken18'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'18\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$theBaseOption = explode('|', $_POST['baseOptionID']);

		foreach ($theBaseOption as $theBaseOptionID) {
			$optionName = explode("\n", $_POST['optionName_' . $theBaseOptionID]);
			$i = 0;

			for (; $i < count($optionName); $i++) {
				$optionName[$i] = str_replace("\r", '', $optionName[$i]);

				if ($optionName[$i] != '') {
					$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
					$lastOptionID = $DB->_GetInsertID();
					$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theBaseOptionID . '\',sonID=\'' . $lastOptionID . '\',questionID=\'' . $lastQuestionID . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken18']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_cond_question'] . ':' . $questionName);
		_showmessage($lang['add_cond_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$RelSQL = ' DELETE FROM ' . CONDREL_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
		$DB->query($RelSQL);
		$SQL = ' DELETE FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
		$DB->query($SQL);
		checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 18, 0, 0, 0);
		$theBaseOption = explode('|', $_POST['baseOptionID']);

		foreach ($theBaseOption as $theBaseOptionID) {
			$optionName = explode("\n", $_POST['optionName_' . $theBaseOptionID]);
			$i = 0;

			for (; $i < count($optionName); $i++) {
				$optionName[$i] = str_replace("\r", '', $optionName[$i]);

				if ($optionName[$i] != '') {
					$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
					$lastOptionID = $DB->_GetInsertID();
					$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theBaseOptionID . '\',sonID=\'' . $lastOptionID . '\',questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken18']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_cond_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_cond_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_cond_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddCondOver') {
	if (!isset($_SESSION['PageToken18']) || ($_SESSION['PageToken18'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'18\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$theBaseOption = explode('|', $_POST['baseOptionID']);

		foreach ($theBaseOption as $theBaseOptionID) {
			$optionName = explode("\n", $_POST['optionName_' . $theBaseOptionID]);
			$i = 0;

			for (; $i < count($optionName); $i++) {
				$optionName[$i] = str_replace("\r", '', $optionName[$i]);

				if ($optionName[$i] != '') {
					$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
					$lastOptionID = $DB->_GetInsertID();
					$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theBaseOptionID . '\',sonID=\'' . $lastOptionID . '\',questionID=\'' . $lastQuestionID . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken18']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_cond_question'] . ':' . $questionName);
		_showmessage($lang['add_cond_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$RelSQL = ' DELETE FROM ' . CONDREL_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
		$DB->query($RelSQL);
		$SQL = ' DELETE FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
		$DB->query($SQL);
		checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 18, 0, 0, 0);
		$theBaseOption = explode('|', $_POST['baseOptionID']);

		foreach ($theBaseOption as $theBaseOptionID) {
			$optionName = explode("\n", $_POST['optionName_' . $theBaseOptionID]);
			$i = 0;

			for (; $i < count($optionName); $i++) {
				$optionName[$i] = str_replace("\r", '', $optionName[$i]);

				if ($optionName[$i] != '') {
					$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
					$lastOptionID = $DB->_GetInsertID();
					$SQL = ' INSERT INTO ' . CONDREL_TABLE . ' SET fatherID = \'' . $theBaseOptionID . '\',sonID=\'' . $lastOptionID . '\',questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken18']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_cond_question'] . ':' . $questionName);
		_showmessage($lang['add_cond_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CondEditFile', 'CondEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	if ($Row['isSelect'] == '1') {
		$EnableQCoreClass->replace('isSelect', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isSelect', '');
	}

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
	$EnableQCoreClass->replace('baseID', $Row['baseID']);
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=2 OR questionType=24) AND surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID < \'' . $Row['orderByID'] . '\' ORDER BY orderByID ASC ';
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
	$EnableQCoreClass->replace('isSelect', '');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('baseID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=2 OR questionType=24) AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken18'] = session_id();
$EnableQCoreClass->parse('CondEdit', 'CondEditFile');
$EnableQCoreClass->output('CondEdit');

?>
