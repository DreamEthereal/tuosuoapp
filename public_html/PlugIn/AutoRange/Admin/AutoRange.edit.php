<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddAutoRangeSubmit') || ($_POST['Action'] == 'AddAutoRangeOver')) {
	if (!isset($_SESSION['PageToken19']) || ($_SESSION['PageToken19'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
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
	unset($_SESSION['PageToken19']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_autorange_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddAutoRangeSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_autorange_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_autorange_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_autorange_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoRangeEditFile', 'AutoRangeModi.html');
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
$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' AND questionID = \'' . $Row['baseID'] . '\' ';
$baseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('baseQuestionList', qnohtmltag($baseRow['questionName'], 1));
$_SESSION['PageToken19'] = session_id();
$EnableQCoreClass->parse('AutoRangeEdit', 'AutoRangeEditFile');
$EnableQCoreClass->output('AutoRangeEdit');

?>
