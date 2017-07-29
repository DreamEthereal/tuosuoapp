<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddCombRangeSubmit') || ($_POST['Action'] == 'AddCombRangeOver')) {
	if (!isset($_SESSION['PageToken26']) || ($_SESSION['PageToken26'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
	$theOriOptionIDList = explode('|', $theOriOptionID);
	$optionName = explode("\n", $_POST['optionName']);

	if ($_POST['optionNum'] != count($optionName)) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵行问题数与原有矩阵行问题数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$j = 0;
	$i = 0;

	for (; $i < count($optionName); $i++) {
		$optionName[$i] = str_replace("\r", '', $optionName[$i]);

		if ($optionName[$i] != '') {
			if (($j < count($theOriOptionIDList)) && !empty($theOriOptionIDList[$j])) {
				$SQL = ' UPDATE ' . QUESTION_RANGE_OPTION_TABLE . ' SET optionName=\'' . qnoreturnchar($optionName[$i]) . '\' WHERE question_range_optionID =\'' . $theOriOptionIDList[$j] . '\' ';
				$DB->query($SQL);
				$j++;
			}
		}
		else {
			$j++;
		}
	}

	$theOriLabelID = substr($_POST['theOriLabelID'], 0, -1);
	$theOriLabelIDList = explode('|', $theOriLabelID);
	$optionLabel = explode("\n", $_POST['optionLabel']);

	if ($_POST['labelNum'] != count($optionLabel)) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵列问题数与原有矩阵列问题数(' . $_POST['labelNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$j = 0;
	$i = 0;

	for (; $i < count($optionLabel); $i++) {
		$optionLabel[$i] = str_replace("\r", '', $optionLabel[$i]);

		if ($optionLabel[$i] != '') {
			if (($j < count($theOriLabelIDList)) && !empty($theOriLabelIDList[$j])) {
				$SQL = ' UPDATE ' . QUESTION_RANGE_LABEL_TABLE . ' SET optionLabel=\'' . qnoreturnchar($optionLabel[$i]) . '\' WHERE question_range_labelID =\'' . $theOriLabelIDList[$j] . '\' ';
				$DB->query($SQL);
				$j++;
			}
		}
		else {
			$j++;
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
	unset($_SESSION['PageToken26']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_combrange_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddCombRangeSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_combrange_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_combrange_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_combrange_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CombRangeEditFile', 'CombRangeModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['isRandOptions'] == '1') {
	$EnableQCoreClass->replace('isRandOptions', 'checked');
}
else {
	$EnableQCoreClass->replace('isRandOptions', '');
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'  ORDER BY question_range_optionID ASC ';
$OptionResult = $DB->query($OptionSQL);
$OptionCount = $DB->_getNumRows($OptionResult);
$EnableQCoreClass->replace('optionNum', $OptionCount);
$optionName = '';
$i = 0;
$theOriOptionID = '';

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$i++;

	if ($i == $OptionCount) {
		$optionName .= $OptionRow['optionName'];
	}
	else {
		$optionName .= $OptionRow['optionName'] . "\n";
	}

	$theOriOptionID .= $OptionRow['question_range_optionID'] . '|';
}

$EnableQCoreClass->replace('optionName', $optionName);
$EnableQCoreClass->replace('theOriOptionID', $theOriOptionID);
$LabelSQL = ' SELECT question_range_labelID,optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'  ORDER BY question_range_labelID ASC ';
$LabelResult = $DB->query($LabelSQL);
$LabelCount = $DB->_getNumRows($LabelResult);
$EnableQCoreClass->replace('labelNum', $LabelCount);
$optionLabel = '';
$j = 0;
$theOriLabelID = '';

while ($LabelRow = $DB->queryArray($LabelResult)) {
	$j++;

	if ($j == $LabelCount) {
		$optionLabel .= $LabelRow['optionLabel'];
	}
	else {
		$optionLabel .= $LabelRow['optionLabel'] . "\n";
	}

	$theOriLabelID .= $LabelRow['question_range_labelID'] . '|';
}

$EnableQCoreClass->replace('optionLabel', $optionLabel);
$EnableQCoreClass->replace('theOriLabelID', $theOriLabelID);
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
$_SESSION['PageToken26'] = session_id();
$EnableQCoreClass->parse('CombRangeEdit', 'CombRangeEditFile');
$EnableQCoreClass->output('CombRangeEdit');

?>
