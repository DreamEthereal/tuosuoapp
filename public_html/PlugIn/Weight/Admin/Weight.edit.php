<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddWeightSubmit') || ($_POST['Action'] == 'AddWeightOver')) {
	if (!isset($_SESSION['PageToken16']) || ($_SESSION['PageToken16'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',baseID=\'' . $_POST['baseID'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
	$theOriOptionIDList = explode('|', $theOriOptionID);
	$optionName = explode("\n", $_POST['optionName']);

	if ($_POST['optionNum'] != count($optionName)) {
		_showerror('一致性错误', '一致性错误：修改后的比重评估项目数与原有比重评估项目数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

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
		}
		else {
			$j++;
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken16']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_weight_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddWeightSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_weight_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_weight_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_weight_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('WeightEditFile', 'WeightModi.html');
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
$EnableQCoreClass->replace('optionNum', $OptionCount);
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
$_SESSION['PageToken16'] = session_id();
$EnableQCoreClass->parse('WeightEdit', 'WeightEditFile');
$EnableQCoreClass->output('WeightEdit');

?>
