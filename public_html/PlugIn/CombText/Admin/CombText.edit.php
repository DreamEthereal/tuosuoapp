<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddCombTextSubmit') || ($_POST['Action'] == 'AddCombTextOver')) {
	if (!isset($_SESSION['PageToken23']) || ($_SESSION['PageToken23'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['optionNum'] != sizeof($_POST['optionID'])) {
		_showerror('一致性错误', '一致性错误：修改后的组合问题数与原有组合问题数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$i = 1;

	for (; $i <= sizeof($_POST['optionID']); $i++) {
		if (trim($_POST['optionID'][$i]) != '') {
			$SQL = ' SELECT questionID,question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' UPDATE ' . QUESTION_YESNO_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isNeg=\'' . $_POST['isNeg'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\',unitText=\'' . trim($_POST['unitText'][$i]) . '\' ';
				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);

				if ($_POST['isCheckType_' . $i] != '4') {
					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND qtnID =\'' . $Row['question_yesnoID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condQtnID =\'' . $Row['question_yesnoID'] . '\' ';
					$DB->query($SQL);
					$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $Row['question_yesnoID'] . '\' ';
					$HResult = $DB->query($HSQL);

					while ($HRow = $DB->queryArray($HResult)) {
						$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
						$DB->query($SQL);
					}

					$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $Row['question_yesnoID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionID = \'' . $Row['question_yesnoID'] . '\' ';
					$DB->query($SQL);
					require ROOT_PATH . 'System/CheckValueRelation.php';
				}
			}
		}
	}

	$quotaCacheFile = ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Quota' . $_POST['surveyID']) . '.php';

	if (!file_exists($quotaCacheFile)) {
		@unlink($quotaCacheFile);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken23']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_combtext_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddCombTextSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_combtext_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_combtext_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_combtext_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CombTextEditFile', 'CombTextModi.html');
$EnableQCoreClass->set_CycBlock('CombTextEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$MaxRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('optionNum', $MaxRow['theMaxOptionOptionID']);
$i = 1;

for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
	$SQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
	$OptionRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('optionOrderID', $i);
	$EnableQCoreClass->replace('optionName', qshowquotestring($OptionRow['optionName']));

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

	if ($OptionRow['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isNeg', '');
	}

	if ($Row['isHaveUnkown'] == '2') {
		$EnableQCoreClass->replace('isHaveUnkown', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isHaveUnkown', '');
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
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$_SESSION['PageToken23'] = session_id();
$EnableQCoreClass->parse('CombTextEdit', 'CombTextEditFile');
$EnableQCoreClass->output('CombTextEdit');

?>
