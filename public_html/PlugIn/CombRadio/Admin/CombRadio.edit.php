<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddCombRadioSubmit') || ($_POST['Action'] == 'AddCombRadioOver')) {
	if (!isset($_SESSION['PageToken24']) || ($_SESSION['PageToken24'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired0'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isHaveOther=0,isSelect=0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['modiMode'] == 1) {
		if ($_POST['optionNum'] != sizeof($_POST['optionID'])) {
			_showerror('一致性错误', '一致性错误：问卷内有后续问题基于此问题，修改后的问题选项数与原有问题选项数(' . $_POST['optionNum'] . ')必须一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
		}
	}
	else if (sizeof($_POST['optionID']) < $_POST['optionNum']) {
		_showerror('一致性错误', '一致性错误：修改后的问题选项数不能小于原有问题选项数(' . $_POST['optionNum'] . ')，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$i = 1;

	for (; $i <= sizeof($_POST['optionID']); $i++) {
		if (trim($_POST['optionID'][$i]) != '') {
			$SQL = ' SELECT questionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
			else {
				$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isRetain=\'' . $_POST['isRetain'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\',isHaveText=0 ';
				$DB->query($SQL);
				updateorderid('question_radio');
			}
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken24']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_combradio_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddCombRadioSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_combradio_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_combradio_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_combradio_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CombRadioEditFile', 'CombRadioModi.html');
$EnableQCoreClass->set_CycBlock('CombRadioEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
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
$EnableQCoreClass->replace('optionNum', $MaxRow['theMaxOptionOptionID']);
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
$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID =\'' . $_GET['questionID'] . '\' LIMIT 0,1 ';
$HaveRow = $DB->queryFirstRow($SQL);

if ($HaveRow) {
	$EnableQCoreClass->replace('modiMode', 1);
}
else {
	$EnableQCoreClass->replace('modiMode', 2);
}

$_SESSION['PageToken24'] = session_id();
$EnableQCoreClass->parse('CombRadioEdit', 'CombRadioEditFile');
$EnableQCoreClass->output('CombRadioEdit');

?>
