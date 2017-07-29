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

if ($_POST['Action'] == 'AddMultipleSubmit') {
	if (!isset($_SESSION['PageToken7']) || ($_SESSION['PageToken7'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'7\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
				$DB->query($SQL);
			}
		}

		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken7']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_multiple_question'] . ':' . $questionName);
		_showmessage($lang['add_multiple_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName=\'' . qnoreturnchar(trim($_POST['optionID'][$i])) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$DB->query($SQL);
				}
			}
			else {
				$SQL = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . '  WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$HRow = $DB->queryFirstRow($SQL);
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $HRow['question_range_optionID'] . '\') ';
				$DB->query($SQL);
				checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, $HRow['question_range_optionID'], 0, 0, 1);
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $HRow['question_range_optionID'] . '\' ';
				$DB->query($SQL);
			}
		}

		$SQL = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . '  WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$HResult = $DB->query($SQL);

		while ($HRow = $DB->queryArray($HResult)) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $HRow['question_range_optionID'] . '\') ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, $HRow['question_range_optionID'], 0, 0, 1);
		}

		$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		$theOriAnswerID = substr($_POST['theOriAnswerID'], 0, -1);
		$theOriAnswerIDList = explode('|', $theOriAnswerID);
		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$j = 0;
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				if (($j < count($theOriAnswerIDList)) && !empty($theOriAnswerIDList[$j])) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$j] . '\' ';
					$DB->query($SQL);
					$j++;
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriAnswerIDList)) {
			$i = $j;

			for (; $i < count($theOriAnswerIDList); $i++) {
				if (($theOriAnswerIDList[$i] != '') && ($theOriAnswerIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $theOriAnswerIDList[$i] . '\') ';
					$DB->query($SQL);
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, 0, $theOriAnswerIDList[$i], 0, 2);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken7']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_multiple_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_multiple_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_multiple_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddMultipleOver') {
	if (!isset($_SESSION['PageToken7']) || ($_SESSION['PageToken7'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'7\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
				$DB->query($SQL);
			}
		}

		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken7']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_multiple_question'] . ':' . $questionName);
		_showmessage($lang['add_multiple_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT questionID FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName=\'' . qnoreturnchar(trim($_POST['optionID'][$i])) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
					$DB->query($SQL);
				}
			}
			else {
				$SQL = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . '  WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$HRow = $DB->queryFirstRow($SQL);
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $HRow['question_range_optionID'] . '\') ';
				$DB->query($SQL);
				checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, $HRow['question_range_optionID'], 0, 0, 1);
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $HRow['question_range_optionID'] . '\' ';
				$DB->query($SQL);
			}
		}

		$SQL = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . '  WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$HResult = $DB->query($SQL);

		while ($HRow = $DB->queryArray($HResult)) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $HRow['question_range_optionID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $HRow['question_range_optionID'] . '\') ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, $HRow['question_range_optionID'], 0, 0, 1);
		}

		$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$DB->query($SQL);
		$theOriAnswerID = substr($_POST['theOriAnswerID'], 0, -1);
		$theOriAnswerIDList = explode('|', $theOriAnswerID);
		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$j = 0;
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				if (($j < count($theOriAnswerIDList)) && !empty($theOriAnswerIDList[$j])) {
					$SQL = ' UPDATE ' . QUESTION_RANGE_ANSWER_TABLE . ' SET optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$j] . '\' ';
					$DB->query($SQL);
					$j++;
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriAnswerIDList)) {
			$i = $j;

			for (; $i < count($theOriAnswerIDList); $i++) {
				if (($theOriAnswerIDList[$i] != '') && ($theOriAnswerIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $theOriAnswerIDList[$i] . '\') ';
					$DB->query($SQL);
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 7, 0, $theOriAnswerIDList[$i], 0, 2);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken7']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_multiple_question'] . ':' . $questionName);
		_showmessage($lang['add_multiple_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('MultipleEditFile', 'MultipleEdit.html');
$EnableQCoreClass->set_CycBlock('MultipleEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['requiredMode'] == '2') {
		$EnableQCoreClass->replace('requiredMode', 'checked');
	}
	else {
		$EnableQCoreClass->replace('requiredMode', '');
	}

	if ($Row['isCheckType'] == '1') {
		$EnableQCoreClass->replace('isCheckType', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isCheckType', '');
	}

	if ($Row['isRandOptions'] == '1') {
		$EnableQCoreClass->replace('isRandOptions', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRandOptions', '');
	}

	if ($Row['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isNeg', '');
	}

	if ($Row['isSelect'] == '1') {
		$EnableQCoreClass->replace('isSelect', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isSelect', '');
	}

	if ($Row['isHaveOther'] == '1') {
		$EnableQCoreClass->replace('isHaveOther', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isHaveOther', '');
	}

	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
	$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$MaxRow = $DB->queryFirstRow($SQL);
	$i = 1;

	for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
		$SQL = ' SELECT * FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
		$OptionRow = $DB->queryFirstRow($SQL);
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->replace('optionName', qshowquotestring($OptionRow['optionName']));

		if ($OptionRow['isRequired'] == '1') {
			$EnableQCoreClass->replace('isRequired', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isRequired', '');
		}

		if ($OptionRow['minOption'] == 0) {
			$EnableQCoreClass->replace('minOption', '');
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

	$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'  ORDER BY question_range_answerID ASC ';
	$AnswerResult = $DB->query($AnswerSQL);
	$AnswerCount = $DB->_getNumRows($AnswerResult);
	$optionAnswer = '';
	$j = 0;
	$theOriAnswerID = '';

	while ($AnswerRow = $DB->queryArray($AnswerResult)) {
		$j++;

		if ($j == $AnswerCount) {
			$optionAnswer .= $AnswerRow['optionAnswer'];
		}
		else {
			$optionAnswer .= $AnswerRow['optionAnswer'] . "\n";
		}

		$theOriAnswerID .= $AnswerRow['question_range_answerID'] . '|';
	}

	$EnableQCoreClass->replace('optionAnswer', $optionAnswer);
	$EnableQCoreClass->replace('theOriAnswerID', $theOriAnswerID);
}
else {
	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('isSelect', '');
	$EnableQCoreClass->replace('isRandOptions', '');
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('optionAnswer', '');
	$EnableQCoreClass->replace('theOriAnswerID', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$i = 1;

	for (; $i <= 5; $i++) {
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('requiredMode', '');
	$EnableQCoreClass->replace('isCheckType', '');
}

$_SESSION['PageToken7'] = session_id();
$EnableQCoreClass->parse('MultipleEdit', 'MultipleEditFile');
$EnableQCoreClass->output('MultipleEdit');

?>
