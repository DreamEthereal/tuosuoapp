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

if ($_POST['Action'] == 'AddCombRangeSubmit') {
	if (!isset($_SESSION['PageToken26']) || ($_SESSION['PageToken26'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'26\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionName = explode("\n", $_POST['optionName']);
		$i = 0;

		for (; $i < count($optionName); $i++) {
			$optionName[$i] = str_replace("\r", '', $optionName[$i]);

			if ($optionName[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		$optionLabel = explode("\n", $_POST['optionLabel']);
		$i = 0;

		for (; $i < count($optionLabel); $i++) {
			$optionLabel[$i] = str_replace("\r", '', $optionLabel[$i]);

			if ($optionLabel[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionLabel=\'' . qnoreturnchar($optionLabel[$i]) . '\' ';
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

		unset($_SESSION['PageToken26']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combrange_question'] . ':' . $questionName);
		_showmessage($lang['add_combrange_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
		$theOriOptionIDList = explode('|', $theOriOptionID);
		$optionName = explode("\n", $_POST['optionName']);
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
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriOptionIDList)) {
			$i = $j;

			for (; $i < count($theOriOptionIDList); $i++) {
				if (($theOriOptionIDList[$i] != '') && ($theOriOptionIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\'';
					$DB->query($SQL);
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 26, $theOriOptionIDList[$i], 0, 0, 1);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $theOriOptionIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$theOriLabelID = substr($_POST['theOriLabelID'], 0, -1);
		$theOriLabelIDList = explode('|', $theOriLabelID);
		$optionLabel = explode("\n", $_POST['optionLabel']);
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
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionLabel=\'' . qnoreturnchar($optionLabel[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriLabelIDList)) {
			$i = $j;

			for (; $i < count($theOriLabelIDList); $i++) {
				if (($theOriLabelIDList[$i] != '') && ($theOriLabelIDList[$i] != 0)) {
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 26, 0, $theOriLabelIDList[$i], 0, 2);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID =\'' . $theOriLabelIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

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
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken26']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combrange_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_combrange_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_combrange_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddCombRangeOver') {
	if (!isset($_SESSION['PageToken26']) || ($_SESSION['PageToken26'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'26\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionName = explode("\n", $_POST['optionName']);
		$i = 0;

		for (; $i < count($optionName); $i++) {
			$optionName[$i] = str_replace("\r", '', $optionName[$i]);

			if ($optionName[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		$optionLabel = explode("\n", $_POST['optionLabel']);
		$i = 0;

		for (; $i < count($optionLabel); $i++) {
			$optionLabel[$i] = str_replace("\r", '', $optionLabel[$i]);

			if ($optionLabel[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionLabel=\'' . qnoreturnchar($optionLabel[$i]) . '\' ';
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

		unset($_SESSION['PageToken26']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combrange_question'] . ':' . $questionName);
		_showmessage($lang['add_combrange_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
		$theOriOptionIDList = explode('|', $theOriOptionID);
		$optionName = explode("\n", $_POST['optionName']);
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
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriOptionIDList)) {
			$i = $j;

			for (; $i < count($theOriOptionIDList); $i++) {
				if (($theOriOptionIDList[$i] != '') && ($theOriOptionIDList[$i] != 0)) {
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\'';
					$DB->query($SQL);
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 26, $theOriOptionIDList[$i], 0, 0, 1);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $theOriOptionIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$theOriLabelID = substr($_POST['theOriLabelID'], 0, -1);
		$theOriLabelIDList = explode('|', $theOriLabelID);
		$optionLabel = explode("\n", $_POST['optionLabel']);
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
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionLabel=\'' . qnoreturnchar($optionLabel[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriLabelIDList)) {
			$i = $j;

			for (; $i < count($theOriLabelIDList); $i++) {
				if (($theOriLabelIDList[$i] != '') && ($theOriLabelIDList[$i] != 0)) {
					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 26, 0, $theOriLabelIDList[$i], 0, 2);
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID =\'' . $theOriLabelIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

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
					$SQL = ' DELETE FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $theOriAnswerIDList[$i] . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken26']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_combrange_question'] . ':' . $questionName);
		_showmessage($lang['add_combrange_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CombRangeEditFile', 'CombRangeEdit.html');

if ($_GET['questionID'] != '') {
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
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('isRandOptions', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('optionLabel', '');
	$EnableQCoreClass->replace('optionAnswer', '');
	$EnableQCoreClass->replace('theOriOptionID', '');
	$EnableQCoreClass->replace('theOriAnswerID', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
}

$_SESSION['PageToken26'] = session_id();
$EnableQCoreClass->parse('CombRangeEdit', 'CombRangeEditFile');
$EnableQCoreClass->output('CombRangeEdit');

?>
