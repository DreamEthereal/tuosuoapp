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

if ($_POST['Action'] == 'AddWeightSubmit') {
	if (!isset($_SESSION['PageToken16']) || ($_SESSION['PageToken16'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'16\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',baseID=\'' . $_POST['baseID'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionName = explode("\n", $_POST['optionName']);
		$i = 0;

		for (; $i < count($optionName); $i++) {
			$optionName[$i] = str_replace("\r", '', $optionName[$i]);

			if ($optionName[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken16']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_weight_question'] . ':' . $questionName);
		_showmessage($lang['add_weight_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',baseID=\'' . $_POST['baseID'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
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
					$SQL = ' UPDATE ' . QUESTION_RANK_TABLE . ' SET optionName=\'' . qnoreturnchar($optionName[$i]) . '\' WHERE question_rankID =\'' . $theOriOptionIDList[$j] . '\' ';
					$DB->query($SQL);
					$j++;
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriOptionIDList)) {
			$i = $j;

			for (; $i < count($theOriOptionIDList); $i++) {
				$SQL = ' DELETE FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID =\'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $theOriOptionIDList[$i] . '\') ';
				$DB->query($SQL);
				$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE  questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$HResult = $DB->query($HSQL);

				while ($HRow = $DB->queryArray($HResult)) {
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
					$DB->query($SQL);
				}

				$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				require ROOT_PATH . 'System/CheckValueRelation.php';
			}
		}

		unset($_SESSION['PageToken16']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_weight_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_weight_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_weight_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddWeightOver') {
	if (!isset($_SESSION['PageToken16']) || ($_SESSION['PageToken16'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'16\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',baseID=\'' . $_POST['baseID'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$optionName = explode("\n", $_POST['optionName']);
		$i = 0;

		for (; $i < count($optionName); $i++) {
			$optionName[$i] = str_replace("\r", '', $optionName[$i]);

			if ($optionName[$i] != '') {
				$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
				$DB->query($SQL);
			}
		}

		unset($_SESSION['PageToken16']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_weight_question'] . ':' . $questionName);
		_showmessage($lang['add_weight_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',baseID=\'' . $_POST['baseID'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
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
					$SQL = ' UPDATE ' . QUESTION_RANK_TABLE . ' SET optionName=\'' . qnoreturnchar($optionName[$i]) . '\' WHERE question_rankID =\'' . $theOriOptionIDList[$j] . '\' ';
					$DB->query($SQL);
					$j++;
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionName=\'' . qnoreturnchar($optionName[$i]) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($j < count($theOriOptionIDList)) {
			$i = $j;

			for (; $i < count($theOriOptionIDList); $i++) {
				$SQL = ' DELETE FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID =\'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $theOriOptionIDList[$i] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $theOriOptionIDList[$i] . '\') ';
				$DB->query($SQL);
				$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE  questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$HResult = $DB->query($HSQL);

				while ($HRow = $DB->queryArray($HResult)) {
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
					$DB->query($SQL);
				}

				$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $theOriOptionIDList[$i] . '\' ';
				$DB->query($SQL);
				require ROOT_PATH . 'System/CheckValueRelation.php';
			}
		}

		unset($_SESSION['PageToken16']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_weight_question'] . ':' . $questionName);
		_showmessage($lang['add_weight_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('WeightEditFile', 'WeightEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch ($Row['isSelect']) {
	case '1':
	default:
		$EnableQCoreClass->replace('isSelect_1', 'checked');
		$EnableQCoreClass->replace('isSelect_2', '');
		break;

	case '2':
		$EnableQCoreClass->replace('isSelect_1', '');
		$EnableQCoreClass->replace('isSelect_2', 'checked');
		break;
	}

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
	$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
	$OptionSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ORDER BY question_rankID ASC ';
	$OptionResult = $DB->query($OptionSQL);
	$OptionCount = $DB->_getNumRows($OptionResult);
	$optionName = '';
	$theOriOptionID = '';
	$i = 0;

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$i++;

		if ($i == $OptionCount) {
			$optionName .= $OptionRow['optionName'];
		}
		else {
			$optionName .= $OptionRow['optionName'] . "\n";
		}

		$theOriOptionID .= $OptionRow['question_rankID'] . '|';
	}

	$EnableQCoreClass->replace('optionName', $optionName);
	$EnableQCoreClass->replace('theOriOptionID', $theOriOptionID);
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE questionType=4 AND isCheckType=4 AND isRequired=1 AND surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID < \'' . $Row['orderByID'] . '\' ORDER BY orderByID ASC ';
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
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('maxSize', '100');
	$EnableQCoreClass->replace('isSelect_1', 'checked');
	$EnableQCoreClass->replace('isSelect_2', '');
	$EnableQCoreClass->replace('isRandOptions', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('theOriOptionID', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE questionType=4 AND isCheckType=4 AND isRequired=1 AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken16'] = session_id();
$EnableQCoreClass->parse('WeightEdit', 'WeightEditFile');
$EnableQCoreClass->output('WeightEdit');

?>
