<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$SupportUploadFileType = 'jpg|gif|png|JPG|GIF|PNG|';
$picClrProg = $lastProg . '&Action=View&questionID=' . $_GET['questionID'] . '&questionType=2';

if ($_GET['DOes'] == 'Default') {
	$SQL = ' SELECT createDate,optionNameFile FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $_GET['optionOptionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

		if (file_exists($optionPicPath . $Row['optionNameFile'])) {
			@unlink($optionPicPath . $Row['optionNameFile']);
		}

		if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
			@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
		}

		$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionNameFile=\'\' ';
		$SQL .= ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $_GET['optionOptionID'] . '\' ';
		$DB->query($SQL);
	}

	_showsucceed($lang['clear_picture'], $picClrProg);
}

if (($_POST['Action'] == 'AddRadioSubmit') || ($_POST['Action'] == 'AddRadioOver')) {
	if (!isset($_SESSION['PageToken2']) || ($_SESSION['PageToken2'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minSize=\'' . $_POST['minSize'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\' ';

	if ($_POST['isHaveOtherOri'] == 1) {
		$SQL .= ' ,otherText=\'' . $_POST['otherText'] . '\',unitText=\'' . trim($_POST['unitText']) . '\' ';
	}

	$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['modiMode'] == 1) {
		if ($_POST['optionNum'] != sizeof($_POST['optionID'])) {
			_showerror('一致性错误', '一致性错误：问卷内有后续问题基于此问题，修改后的问题选项数与原有问题选项数(' . $_POST['optionNum'] . ')必须一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
		}
	}
	else if (sizeof($_POST['optionID']) < $_POST['optionNum']) {
		_showerror('一致性错误', '一致性错误：修改后的问题选项数不能小于原有选项数(' . $_POST['optionNum'] . ')，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
	}

	$i = 1;

	for (; $i <= sizeof($_POST['optionID']); $i++) {
		if (trim($_POST['optionID'][$i]) != '') {
			$SQL = ' SELECT createDate,optionNameFile FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' UPDATE ' . QUESTION_RADIO_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\' ';

				if ($_FILES['optionFile_' . $i]['name'] != '') {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';
					createdir($optionPicPath);
					$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = true, date('ymdHis', time()) . $i, $isThumb = true, $width = 120, $height = 160, $Row['optionNameFile']);
					$SQL .= $optionPictureSQL;
				}

				$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
			else {
				$time = time();
				$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\',createDate=\'' . $time . '\' ';

				if ($_FILES['optionFile_' . $i]['name'] != '') {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $time) . '/' . date('d', $time) . '/';
					createdir($optionPicPath);
					$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = false, date('ymdHis', $time) . $i, $isThumb = true, $width = 120, $height = 160);
					$SQL .= $optionPictureSQL;
				}

				$DB->query($SQL);
				updateorderid('question_radio');
			}
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken2']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_radio_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddRadioSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_radio_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_radio_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_radio_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('RadioEditFile', 'RadioModi.html');
$EnableQCoreClass->set_CycBlock('RadioEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
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

if ($Row['isSelect'] == '1') {
	$EnableQCoreClass->replace('isSelect', 'checked');
}
else {
	$EnableQCoreClass->replace('isSelect', '');
}

if ($Row['isColArrange'] == '1') {
	$EnableQCoreClass->replace('isColArrange', 'checked');
	$EnableQCoreClass->replace('perRowCol', $Row['perRowCol']);
}
else {
	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('perRowCol', '');
}

if ($Row['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', 'checked');
	$EnableQCoreClass->replace('isHaveOtherOri', 1);
	$EnableQCoreClass->replace('otherText', qshowquotestring($Row['otherText']));
	$EnableQCoreClass->replace('maxLength', $Row['length']);
	$EnableQCoreClass->replace('isCheckType' . $Row['isCheckType'], 'selected');
	$EnableQCoreClass->replace('isCheckType', $Row['isCheckType']);

	if ($Row['minSize'] == 0) {
		if ($Row['maxSize'] == 0) {
			$EnableQCoreClass->replace('minSize', '');
		}
		else {
			$EnableQCoreClass->replace('minSize', '0');
		}
	}
	else {
		$EnableQCoreClass->replace('minSize', $Row['minSize']);
	}

	if ($Row['maxSize'] == 0) {
		$EnableQCoreClass->replace('maxSize', '');
	}
	else {
		$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
	}

	$EnableQCoreClass->replace('unitText', $Row['unitText']);
}
else {
	$EnableQCoreClass->replace('isHaveOtherOri', 0);
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('maxLength', '30');
	$EnableQCoreClass->replace('minSize', '');
	$EnableQCoreClass->replace('maxSize', '');
	$EnableQCoreClass->replace('unitText', '');
	$EnableQCoreClass->replace('isCheckType0', 'selected');
	$EnableQCoreClass->replace('isCheckType', 0);
}

$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$MaxRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('optionNum', $MaxRow['theMaxOptionOptionID']);
$i = 1;

for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
	$EnableQCoreClass->replace('tableIndex', $i + 1);
	$SQL = ' SELECT * FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
	$OptionRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('optionOrderID', $i);

	if ($OptionRow['optionNameFile'] != '') {
		$EnableQCoreClass->replace('picFileName', '<a href="' . $picClrProg . '&DOes=Default&optionOptionID=' . $i . '">' . $lang['clr_pic'] . '</a>&nbsp;&nbsp;<a href=\'../PerUserData/p/' . date('Y-m', $OptionRow['createDate']) . '/' . date('d', $OptionRow['createDate']) . '/' . $OptionRow['optionNameFile'] . '\' rel="picbox" title="' . $OptionRow['optionNameFile'] . '">' . $OptionRow['optionNameFile'] . '</a>');
	}
	else {
		$EnableQCoreClass->replace('picFileName', '');
	}

	$EnableQCoreClass->replace('optionName', qshowquotestring($OptionRow['optionName']));

	if ($OptionRow['optionMargin'] == 0) {
		$EnableQCoreClass->replace('optionMargin', '');
	}
	else {
		$EnableQCoreClass->replace('optionMargin', $OptionRow['optionMargin']);
	}

	$EnableQCoreClass->replace('createDate', $OptionRow['createDate']);
	$EnableQCoreClass->replace('optionNameFile', $OptionRow['optionNameFile']);
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

$_SESSION['PageToken2'] = session_id();
$EnableQCoreClass->parse('RadioEdit', 'RadioEditFile');
$EnableQCoreClass->output('RadioEdit');

?>
