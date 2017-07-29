<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddRatingSubmit') || ($_POST['Action'] == 'AddRatingOver')) {
	if (!isset($_SESSION['PageToken15']) || ($_SESSION['PageToken15'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isHaveUnkown=\'' . $_POST['isHaveUnkown'] . '\' ';
	if ((trim($_POST['unitText0']) != '') && (trim($_POST['unitText1']) != '')) {
		$SQL .= ',unitText=\'' . $_POST['unitText0'] . '###' . $_POST['unitText1'] . '\' ';
	}
	else {
		$SQL .= ',unitText=\'\' ';
	}

	switch ($_POST['isSelectOri']) {
	case '0':
		$SQL .= ' ,startScale=\'' . $_POST['startScale'] . '\',endScale=\'' . $_POST['endScale'] . '\',weight=\'' . $_POST['weight'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';

		if ($_POST['isRandOptions'] != 1) {
			$SQL .= ' ,isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' ';
		}

		break;

	case '1':
		$SQL .= ' ,startScale=\'' . $_POST['startScale1'] . '\',endScale=\'' . $_POST['endScale1'] . '\',weight=1 ';
		break;

	case '2':
		$SQL .= ' ,startScale=\'' . $_POST['startScale2'] . '\',endScale=\'' . $_POST['endScale2'] . '\',weight=1,isNeg=\'' . $_POST['isNeg0'] . '\' ';
		break;
	}

	$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$theOriOptionID = substr($_POST['theOriOptionID'], 0, -1);
	$theOriOptionIDList = explode('|', $theOriOptionID);
	$optionName = explode("\n", $_POST['optionName']);

	if ($_POST['optionNum'] != count($optionName)) {
		_showerror('一致性错误', '一致性错误：修改后的评分项目数与原有评分项目数(' . $_POST['optionNum'] . ')不一致，可能会造成原有回复数据的一致性错误，您的操作无法继续！');
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
	unset($_SESSION['PageToken15']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_rating_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddRatingSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_rating_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_rating_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_rating_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('RatingEditFile', 'RatingModi.html');
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

if ($Row['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', 'checked');
}
else {
	$EnableQCoreClass->replace('isHaveOther', '');
}

if ($Row['isCheckType'] == '1') {
	$EnableQCoreClass->replace('isHaveOther0', 'checked');
}
else {
	$EnableQCoreClass->replace('isHaveOther0', '');
}

if ($Row['isHaveUnkown'] == '1') {
	$EnableQCoreClass->replace('isHaveUnkown', 'checked');
}
else {
	$EnableQCoreClass->replace('isHaveUnkown', '');
}

$theUnitText = explode('###', $Row['unitText']);
$EnableQCoreClass->replace('unitText0', $theUnitText[0]);
$EnableQCoreClass->replace('unitText1', $theUnitText[1]);

if ($Row['maxOption'] == '0') {
	$EnableQCoreClass->replace('maxOption', '');
}
else {
	$EnableQCoreClass->replace('maxOption', $Row['maxOption']);
}

$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$EnableQCoreClass->replace('isSelectOri', $Row['isSelect']);

switch ($Row['isSelect']) {
case '0':
	$EnableQCoreClass->replace('isSelect_1', '');
	$EnableQCoreClass->replace('startScale1', '0');
	$EnableQCoreClass->replace('endScale1', '10');
	$EnableQCoreClass->replace('isSelect_2', '');
	$EnableQCoreClass->replace('startScale2', '0');
	$EnableQCoreClass->replace('endScale2', '10');
	$EnableQCoreClass->replace('isSelect_0', 'checked');
	$EnableQCoreClass->replace('startScale', $Row['startScale']);
	$EnableQCoreClass->replace('endScale', $Row['endScale']);
	$EnableQCoreClass->replace('weight', $Row['weight']);

	if ($Row['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isNeg', '');
	}

	$EnableQCoreClass->replace('isNeg0', '');

	if ($Row['isContInvalid'] == '1') {
		$EnableQCoreClass->replace('isContInvalid', 'checked');
		$EnableQCoreClass->replace('contInvalidValue', $Row['contInvalidValue']);
	}
	else {
		$EnableQCoreClass->replace('isContInvalid', '');
		$EnableQCoreClass->replace('contInvalidValue', '');
	}

	break;

case '1':
	$EnableQCoreClass->replace('isSelect_1', 'checked');
	$EnableQCoreClass->replace('startScale1', $Row['startScale']);
	$EnableQCoreClass->replace('endScale1', $Row['endScale']);
	$EnableQCoreClass->replace('isSelect_2', '');
	$EnableQCoreClass->replace('startScale2', '0');
	$EnableQCoreClass->replace('endScale2', '10');
	$EnableQCoreClass->replace('isSelect_0', '');
	$EnableQCoreClass->replace('startScale', 1);
	$EnableQCoreClass->replace('endScale', 5);
	$EnableQCoreClass->replace('weight', 1);
	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('isNeg0', '');
	$EnableQCoreClass->replace('isContInvalid', '');
	$EnableQCoreClass->replace('contInvalidValue', '');
	break;

case '2':
	$EnableQCoreClass->replace('isSelect_1', '');
	$EnableQCoreClass->replace('startScale1', '0');
	$EnableQCoreClass->replace('endScale1', '10');
	$EnableQCoreClass->replace('isSelect_2', 'checked');
	$EnableQCoreClass->replace('startScale2', $Row['startScale']);
	$EnableQCoreClass->replace('endScale2', $Row['endScale']);
	$EnableQCoreClass->replace('isSelect_0', '');
	$EnableQCoreClass->replace('startScale', 1);
	$EnableQCoreClass->replace('endScale', 5);
	$EnableQCoreClass->replace('weight', 1);

	if ($Row['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg0', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isNeg0', '');
	}

	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('isContInvalid', '');
	$EnableQCoreClass->replace('contInvalidValue', '');
	break;
}

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
$_SESSION['PageToken15'] = session_id();
$EnableQCoreClass->parse('RatingEdit', 'RatingEditFile');
$EnableQCoreClass->output('RatingEdit');

?>
