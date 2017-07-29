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

if ($_POST['Action'] == 'AddAutoRangeSubmit') {
	if (!isset($_SESSION['PageToken19']) || ($_SESSION['PageToken19'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'19\',isRequired=\'' . $_POST['isRequired'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken19']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorange_question'] . ':' . $questionName);
		_showmessage($lang['add_autorange_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
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
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
				$DB->query($SQL);
				if (($theOriAnswerIDList[$i] != '') && ($theOriAnswerIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $theOriAnswerIDList[$i] . '\') ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken19']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorange_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_autorange_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_autorange_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddAutoRangeOver') {
	if (!isset($_SESSION['PageToken19']) || ($_SESSION['PageToken19'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'19\',isRequired=\'' . $_POST['isRequired'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionAnswer = explode("\n", $_POST['optionAnswer']);
		$i = 0;

		for (; $i < count($optionAnswer); $i++) {
			$optionAnswer[$i] = str_replace("\r", '', $optionAnswer[$i]);

			if ($optionAnswer[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionAnswer=\'' . qnoreturnchar($optionAnswer[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken19']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorange_question'] . ':' . $questionName);
		_showmessage($lang['add_autorange_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
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
				$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
				$DB->query($SQL);
				if (($theOriAnswerIDList[$i] != '') && ($theOriAnswerIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $theOriAnswerIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $theOriAnswerIDList[$i] . '\') ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken19']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorange_question'] . ':' . $questionName);
		_showmessage($lang['add_autorange_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoRangeEditFile', 'AutoRangeEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	if ($Row['isContInvalid'] == '1') {
		$EnableQCoreClass->replace('isContInvalid', 'checked');
		$EnableQCoreClass->replace('contInvalidValue', $Row['contInvalidValue']);
	}
	else {
		$EnableQCoreClass->replace('isContInvalid', '');
		$EnableQCoreClass->replace('contInvalidValue', '');
	}

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
	$EnableQCoreClass->replace('optionAnswer', '');
	$EnableQCoreClass->replace('theOriAnswerID', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	$EnableQCoreClass->replace('isContInvalid', '');
	$EnableQCoreClass->replace('contInvalidValue', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken19'] = session_id();
$EnableQCoreClass->parse('AutoRangeEdit', 'AutoRangeEditFile');
$EnableQCoreClass->output('AutoRangeEdit');

?>
