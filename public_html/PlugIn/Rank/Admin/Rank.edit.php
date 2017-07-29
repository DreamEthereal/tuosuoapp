<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddRankSubmit') || ($_POST['Action'] == 'AddRankOver')) {
	if (!isset($_SESSION['PageToken10']) || ($_SESSION['PageToken10'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',otherText=\'' . $_POST['otherText'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
	$theOriOptionIDList = explode('|', $theOriOptionID);
	$optionName = explode("\n", $_POST['optionName']);

	if ($_POST['optionNum'] != count($optionName)) {
		_showerror('一致性错误', '一致性错误：修改后的排序问题数与原有排序问题数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
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

	unset($_SESSION['PageToken10']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_rank_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddRankSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_rank_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_rank_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_rank_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('RankEditFile', 'RankModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['minOption'] == '0') {
	$EnableQCoreClass->replace('minOption', '');
}
else {
	$EnableQCoreClass->replace('minOption', $Row['minOption']);
}

if ($Row['maxOption'] == '0') {
	$EnableQCoreClass->replace('maxOption', '');
}
else {
	$EnableQCoreClass->replace('maxOption', $Row['maxOption']);
}

if ($Row['isRandOptions'] == '1') {
	$EnableQCoreClass->replace('isRandOptions', 'checked');
}
else {
	$EnableQCoreClass->replace('isRandOptions', '');
}

if ($Row['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', 'checked');
	$EnableQCoreClass->replace('otherText', qshowquotestring($Row['otherText']));
}
else {
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('otherText', '');
}

if ($Row['isHaveWhy'] == '1') {
	$EnableQCoreClass->replace('isHaveWhy', 'checked');
}
else {
	$EnableQCoreClass->replace('isHaveWhy', '');
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$OptionSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ORDER BY question_rankID ASC ';
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

	$theOriOptionID .= $OptionRow['question_rankID'] . '|';
}

$EnableQCoreClass->replace('optionName', $optionName);
$EnableQCoreClass->replace('theOriOptionID', $theOriOptionID);
$_SESSION['PageToken10'] = session_id();
$EnableQCoreClass->parse('RankEdit', 'RankEditFile');
$EnableQCoreClass->output('RankEdit');

?>
