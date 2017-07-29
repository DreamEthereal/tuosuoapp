<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddMultipleTextSubmit') || ($_POST['Action'] == 'AddMultipleTextOver')) {
	if (!isset($_SESSION['PageToken27']) || ($_SESSION['PageToken27'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['labelNum'] != sizeof($_POST['optionID'])) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵列标题数与原有矩阵列标题数(' . $_POST['labelNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$i = 1;

	for (; $i <= sizeof($_POST['optionID']); $i++) {
		if (trim($_POST['optionID'][$i]) != '') {
			$SQL = ' SELECT questionID,question_range_labelID FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' UPDATE ' . QUESTION_RANGE_LABEL_TABLE . ' SET optionOptionID=\'' . $i . '\',optionLabel=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionSize=\'' . $_POST['optionSize'][$i] . '\',isRequired=\'' . $_POST['isRequired'][$i] . '\',isCheckType=\'' . $_POST['isCheckType_' . $i] . '\',minOption=\'' . $_POST['minOption'][$i] . '\',maxOption=\'' . $_POST['maxOption'][$i] . '\' ';
				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);

				if ($_POST['isCheckType_' . $i] != '4') {
					$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND labelID = \'' . $Row['question_range_labelID'] . '\' ';
					$HResult = $DB->query($HSQL);

					while ($HRow = $DB->queryArray($HResult)) {
						$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
						$DB->query($SQL);
					}

					$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND labelID = \'' . $Row['question_range_labelID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND labelID = \'' . $Row['question_range_labelID'] . '\' ';
					$DB->query($SQL);
					require ROOT_PATH . 'System/CheckValueRelation.php';
				}
			}
		}
	}

	$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
	$theOriOptionIDList = explode('|', $theOriOptionID);
	$optionName = explode("\n", $_POST['optionName']);

	if ($_POST['optionNum'] != count($optionName)) {
		_showerror('一致性错误', '一致性错误：修改后的矩阵行标题数与原有矩阵行标题数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
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

	$quotaCacheFile = ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Quota' . $_POST['surveyID']) . '.php';

	if (!file_exists($quotaCacheFile)) {
		@unlink($quotaCacheFile);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken27']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_multipletext_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddMultipleTextSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_multipletext_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_multipletext_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_multipletext_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('MultipleTextEditFile', 'MultipleTextModi.html');
$EnableQCoreClass->set_CycBlock('MultipleTextEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['requiredMode'] == '1') {
	$EnableQCoreClass->replace('requiredMode_1', 'checked');
	$EnableQCoreClass->replace('requiredMode_2', '');
}
else {
	$EnableQCoreClass->replace('requiredMode_1', '');
	$EnableQCoreClass->replace('requiredMode_2', 'checked');
}

$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$MaxRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('labelNum', $MaxRow['theMaxOptionOptionID']);
$i = 1;

for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
	$SQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
	$OptionRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('optionOrderID', $i);
	$EnableQCoreClass->replace('optionLabel', qshowquotestring($OptionRow['optionLabel']));

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

	$EnableQCoreClass->parse('option', 'OPTION', true);
}

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
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('alias', $Row['alias']);
$_SESSION['PageToken27'] = session_id();
$EnableQCoreClass->parse('MultipleTextEdit', 'MultipleTextEditFile');
$EnableQCoreClass->output('MultipleTextEdit');

?>
