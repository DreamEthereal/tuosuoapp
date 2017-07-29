<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
if (($_POST['Action'] == 'AddAutoRatingSubmit') || ($_POST['Action'] == 'AddAutoRatingOver')) {
	if (!isset($_SESSION['PageToken21']) || ($_SESSION['PageToken21'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$questionName = qnoreturnchar($_POST['questionName']);
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isHaveUnkown=\'' . $_POST['isHaveUnkown'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
	if ((trim($_POST['unitText0']) != '') && (trim($_POST['unitText1']) != '')) {
		$SQL .= ',unitText=\'' . $_POST['unitText0'] . '###' . $_POST['unitText1'] . '\' ';
	}
	else {
		$SQL .= ',unitText=\'\' ';
	}

	switch ($_POST['isSelectOri']) {
	case '0':
		$SQL .= ' ,startScale=\'' . $_POST['startScale'] . '\',endScale=\'' . $_POST['endScale'] . '\',weight=\'' . $_POST['weight'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',isContInvalid=\'' . $_POST['isContInvalid'] . '\',contInvalidValue=\'' . $_POST['contInvalidValue'] . '\' ';
		break;

	case '1':
		$SQL .= ' ,startScale=\'' . $_POST['startScale1'] . '\',endScale=\'' . $_POST['endScale1'] . '\',weight=1 ';
		break;

	case '2':
		$SQL .= ' ,startScale=\'' . $_POST['startScale2'] . '\',endScale=\'' . $_POST['endScale2'] . '\',weight=1,isColArrange=\'' . $_POST['isColArrange0'] . '\' ';
		break;
	}

	$SQL .= ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken21']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_autorating_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddAutoRatingSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_autorating_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_autorating_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_autorating_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoRatingEditFile', 'AutoRatingModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

if ($Row['isHaveOther'] == '1') {
	$EnableQCoreClass->replace('isHaveOther', 'checked');
}
else {
	$EnableQCoreClass->replace('isHaveOther', '');
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

	if ($Row['isColArrange'] == '1') {
		$EnableQCoreClass->replace('isColArrange', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isColArrange', '');
	}

	$EnableQCoreClass->replace('isColArrange0', '');

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
	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('isColArrange0', '');
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

	if ($Row['isColArrange'] == '1') {
		$EnableQCoreClass->replace('isColArrange0', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isColArrange0', '');
	}

	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('isContInvalid', '');
	$EnableQCoreClass->replace('contInvalidValue', '');
	break;
}

$EnableQCoreClass->replace('alias', $Row['alias']);

switch ($Row['isNeg']) {
case 0:
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	break;

case 1:
	$EnableQCoreClass->replace('isNeg1', 'checked');
	$EnableQCoreClass->replace('isNeg0', '');
	break;
}

$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' AND questionID = \'' . $Row['baseID'] . '\' ';
$baseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('baseQuestionList', qnohtmltag($baseRow['questionName'], 1));
$_SESSION['PageToken21'] = session_id();
$EnableQCoreClass->parse('AutoRatingEdit', 'AutoRatingEditFile');
$EnableQCoreClass->output('AutoRatingEdit');

?>
