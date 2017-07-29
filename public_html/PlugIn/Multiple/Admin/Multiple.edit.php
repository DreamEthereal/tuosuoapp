<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddMultipleSubmit') || ($_POST['Action'] == 'AddMultipleOver')) {
	if (!isset($_SESSION['PageToken7']) || ($_SESSION['PageToken7'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['optionNum'] != sizeof($_POST['optionID'])) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵问题数与原有矩阵问题数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

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
		}
	}

	$theOriAnswerID = substr($_POST['theOriAnswerID'], 0, -1);
	$theOriAnswerIDList = explode('|', $theOriAnswerID);
	$optionAnswer = explode("\n", $_POST['optionAnswer']);

	if (count($optionAnswer) < $_POST['answerNum']) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵选项数不能小于原有矩阵选项数(' . $_POST['answerNum'] . ')，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

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
		else {
			$j++;
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken7']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_multiple_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddMultipleSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_multiple_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_multiple_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_multiple_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('MultipleEditFile', 'MultipleModi.html');
$EnableQCoreClass->set_CycBlock('MultipleEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
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
$EnableQCoreClass->replace('optionNum', $MaxRow['theMaxOptionOptionID']);
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
$EnableQCoreClass->replace('answerNum', $AnswerCount);
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
$_SESSION['PageToken7'] = session_id();
$EnableQCoreClass->parse('MultipleEdit', 'MultipleEditFile');
$EnableQCoreClass->output('MultipleEdit');

?>
