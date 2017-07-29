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

if ($_POST['Action'] == 'AddCombRadioSubmit') {
	if (!isset($_SESSION['PageToken24']) || ($_SESSION['PageToken24'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired0'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'24\',isHaveOther=0,isSelect=0 ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
				$DB->query($SQL);
				updateorderid('question_radio');
			}
		}

		unset($_SESSION['PageToken24']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combradio_question'] . ':' . $questionName);
		_showmessage($lang['add_combradio_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired0'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=0,isSelect=0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\'  ';
					$DB->query($SQL);
					updateorderid('question_radio');
				}
			}
			else {
				$SQL = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_radioID'] . '\') ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . QUESTION_RADIO_TABLE . ' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$SQL = ' SELECT question_radioID,optionOptionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_radioID'] . '\') ';
			$DB->query($SQL);
		}

		$SQL = ' DELETE FROM ' . QUESTION_RADIO_TABLE . ' ';
		$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken24']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combradio_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_combradio_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_combradio_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddCombRadioOver') {
	if (!isset($_SESSION['PageToken24']) || ($_SESSION['PageToken24'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired0'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'24\',isHaveOther=0,isSelect=0 ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
				$DB->query($SQL);
				updateorderid('question_radio');
			}
		}

		unset($_SESSION['PageToken24']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combradio_question'] . ':' . $questionName);
		_showmessage($lang['add_combradio_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired0'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=0,isSelect=0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isHaveText=\'' . $_POST['isHaveText'][$i] . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\'  ';
					$DB->query($SQL);
					updateorderid('question_radio');
				}
			}
			else {
				$SQL = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_radioID'] . '\') ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . QUESTION_RADIO_TABLE . ' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$SQL = ' SELECT question_radioID,optionOptionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_radioID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_radioID'] . '\') ';
			$DB->query($SQL);
		}

		$SQL = ' DELETE FROM ' . QUESTION_RADIO_TABLE . ' ';
		$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken24']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combradio_question'] . ':' . $questionName);
		_showmessage($lang['add_combradio_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CombRadioEditFile', 'CombRadioEdit.html');
$EnableQCoreClass->set_CycBlock('CombRadioEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired0', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired0', '');
	}

	if ($Row['isRandOptions'] == '1') {
		$EnableQCoreClass->replace('isRandOptions', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRandOptions', '');
	}

	$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$MaxRow = $DB->queryFirstRow($SQL);
	$i = 1;

	for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
		$SQL = ' SELECT * FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
		$OptionRow = $DB->queryFirstRow($SQL);
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->replace('optionName', qshowquotestring($OptionRow['optionName']));

		if ($OptionRow['isHaveText'] == 1) {
			$EnableQCoreClass->replace('isHaveText', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isHaveText', '');
		}

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

		if ($OptionRow['isRetain'] == '1') {
			$EnableQCoreClass->replace('isRetain', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isRetain', '');
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

		$EnableQCoreClass->replace('unitText', $OptionRow['unitText']);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('question_Name', urlencode($Row['questionName']));
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
}
else {
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('question_Name', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$i = 1;

	for (; $i <= 5; $i++) {
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('isHaveText', 'checked');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('optionSize', 20);
	$EnableQCoreClass->replace('isRequired', 'checked');
	$EnableQCoreClass->replace('isRetain', '');
	$EnableQCoreClass->replace('isRequired0', 'checked');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('unitText', '');
}

$_SESSION['PageToken24'] = session_id();
$EnableQCoreClass->parse('CombRadioEdit', 'CombRadioEditFile');
$EnableQCoreClass->output('CombRadioEdit');

?>
