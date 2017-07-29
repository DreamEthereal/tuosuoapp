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

if ($_POST['Action'] == 'AddAutoMultiTextSubmit') {
	if (!isset($_SESSION['PageToken29']) || ($_SESSION['PageToken29'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'29\',requiredMode=\'' . $_POST['requiredMode'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
				$DB->query($SQL);
				updateorderid('question_range_label');
			}
		}

		unset($_SESSION['PageToken29']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_automultitext_question'] . ':' . $questionName);
		_showmessage($lang['add_automultitext_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_LABEL_TABLE . ' SET optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\'  ';
					$DB->query($SQL);
					updateorderid('question_range_label');
				}
			}
			else {
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' ';
				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
		}

		$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' ';
		$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken29']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_automultitext_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_automultitext_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_automultitext_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddAutoMultiTextOver') {
	if (!isset($_SESSION['PageToken29']) || ($_SESSION['PageToken29'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'29\',requiredMode=\'' . $_POST['requiredMode'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
				$DB->query($SQL);
				updateorderid('question_range_label');
			}
		}

		unset($_SESSION['PageToken29']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_automultitext_question'] . ':' . $questionName);
		_showmessage($lang['add_automultitext_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_LABEL_TABLE . ' SET optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\'  ';
					$DB->query($SQL);
					updateorderid('question_range_label');
				}
			}
			else {
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' ';
				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
		}

		$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' ';
		$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken29']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_automultitext_question'] . ':' . $questionName);
		_showmessage($lang['add_automultitext_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('MultipleTextEditFile', 'AutoMultiTextEdit.html');
$EnableQCoreClass->set_CycBlock('MultipleTextEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['requiredMode'] == '1') {
		$EnableQCoreClass->replace('requiredMode_1', 'checked');
		$EnableQCoreClass->replace('requiredMode_2', '');
	}
	else {
		$EnableQCoreClass->replace('requiredMode_1', '');
		$EnableQCoreClass->replace('requiredMode_2', 'checked');
	}

	$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$MaxRow = $DB->queryFirstRow($SQL);
	$i = 1;

	for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
		$SQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
		$OptionRow = $DB->queryFirstRow($SQL);
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->replace('optionLabel', qshowquotestring($OptionRow['optionLabel']));

		if ($OptionRow['optionSize'] == 0) {
			$EnableQCoreClass->replace('optionSize', '');
		}
		else {
			$EnableQCoreClass->replace('optionSize', $OptionRow['optionSize']);
		}

		if ($OptionRow['isRequired'] == '1') {
			$EnableQCoreClass->replace('isRequired', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isRequired', '');
		}

		$EnableQCoreClass->replace('isCheckType' . $OptionRow['isCheckType'] . '_' . $i, 'selected');

		if ($OptionRow['minOption'] == 0) {
			if ($OptionRow['maxOption'] == 0) {
				$EnableQCoreClass->replace('minOption', '');
			}
			else {
				$EnableQCoreClass->replace('minOption', '0');
			}
		}
		else {
			$EnableQCoreClass->replace('minOption', $OptionRow['minOption']);
		}

		if ($OptionRow['maxOption'] == 0) {
			$EnableQCoreClass->replace('maxOption', '');
		}
		else {
			$EnableQCoreClass->replace('maxOption', $OptionRow['maxOption']);
		}

		$EnableQCoreClass->parse('option', 'OPTION', true);
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
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
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
}
else {
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('requiredMode_1', 'checked');
	$EnableQCoreClass->replace('requiredMode_2', '');
	$i = 1;

	for (; $i <= 5; $i++) {
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('optionLabel', '');
	$EnableQCoreClass->replace('optionSize', 16);
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
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

$_SESSION['PageToken29'] = session_id();
$EnableQCoreClass->parse('MultipleTextEdit', 'MultipleTextEditFile');
$EnableQCoreClass->output('MultipleTextEdit');

?>
