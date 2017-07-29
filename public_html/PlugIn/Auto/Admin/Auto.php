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

if ($_POST['Action'] == 'AddAutoSubmit') {
	if (!isset($_SESSION['PageToken17']) || ($_SESSION['PageToken17'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if (($_POST['isRequired'] == '1') && ($_POST['isSelect'] == '')) {
		if ($_POST['isNeg'] == 0) {
			$SQL = ' SELECT minOption FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow['minOption'] < $_POST['minOption']) {
				_showerror($lang['error_system'], $lang['error_minOption']);
			}
		}
		else {
			$SQL = ' SELECT maxOption,questionType,isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow['maxOption'] == 0) {
				_showerror($lang['error_system'], $lang['error_minOption_no_max']);
			}
			else {
				$SQL = ' SELECT COUNT(*) as theOptionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
				$CRow = $DB->queryFirstRow($SQL);
				$theOptionNum = $CRow['theOptionNum'];

				switch ($HaveRow['questionType']) {
				case '3':
					if ($HaveRow['isHaveOther'] == 1) {
						$theOptionNum++;
					}

					break;

				case '25':
					break;
				}

				$theLeftOptionNum = $theOptionNum - $HaveRow['maxOption'];

				if ($theLeftOptionNum < $_POST['minOption']) {
					_showerror($lang['error_system'], $lang['error_minOption_neg']);
				}
			}
		}
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'17\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',allowType=\'' . $_POST['negText'] . '\' ';
		$DB->query($SQL);
		updateorderid('question');
		unset($_SESSION['PageToken17']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_auto_question'] . ':' . $questionName);
		_showmessage($lang['add_auto_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',allowType=\'' . $_POST['negText'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['isCheckType'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'99999\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'99999\' ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 17, 0, 99999, 0);
		}

		unset($_SESSION['PageToken17']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_auto_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_auto_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_auto_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddAutoOver') {
	if (!isset($_SESSION['PageToken17']) || ($_SESSION['PageToken17'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if (($_POST['isRequired'] == '1') && ($_POST['isSelect'] == '')) {
		if ($_POST['isNeg'] == 0) {
			$SQL = ' SELECT minOption FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow['minOption'] < $_POST['minOption']) {
				_showerror($lang['error_system'], $lang['error_minOption']);
			}
		}
		else {
			$SQL = ' SELECT maxOption,questionType,isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow['maxOption'] == 0) {
				_showerror($lang['error_system'], $lang['error_minOption_no_max']);
			}
			else {
				$SQL = ' SELECT COUNT(*) as theOptionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['baseID'] . '\' ';
				$CRow = $DB->queryFirstRow($SQL);
				$theOptionNum = $CRow['theOptionNum'];

				switch ($HaveRow['questionType']) {
				case '3':
					if ($HaveRow['isHaveOther'] == 1) {
						$theOptionNum++;
					}

					break;

				case '25':
					break;
				}

				$theLeftOptionNum = $theOptionNum - $HaveRow['maxOption'];

				if ($theLeftOptionNum < $_POST['minOption']) {
					_showerror($lang['error_system'], $lang['error_minOption_neg']);
				}
			}
		}
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'17\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',allowType=\'' . $_POST['negText'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken17']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_auto_question'] . ':' . $questionName);
		_showmessage($lang['add_auto_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',allowType=\'' . $_POST['negText'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['isCheckType'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'99999\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'99999\' ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 17, 0, 99999, 0);
		}

		unset($_SESSION['PageToken17']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_auto_question'] . ':' . $questionName);
		_showmessage($lang['add_auto_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoEditFile', 'AutoEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	if ($Row['isSelect'] == '1') {
		$EnableQCoreClass->replace('isSelect', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isSelect', '');
	}

	if ($Row['minOption'] == 0) {
		$EnableQCoreClass->replace('minOption', '');
	}
	else {
		$EnableQCoreClass->replace('minOption', $Row['minOption']);
	}

	if ($Row['maxOption'] == 0) {
		$EnableQCoreClass->replace('maxOption', '');
	}
	else {
		$EnableQCoreClass->replace('maxOption', $Row['maxOption']);
	}

	if ($Row['isColArrange'] == '1') {
		$EnableQCoreClass->replace('isColArrange', 'checked');
		$EnableQCoreClass->replace('perRowCol', $Row['perRowCol']);
	}
	else {
		$EnableQCoreClass->replace('isColArrange', '');
		$EnableQCoreClass->replace('perRowCol', '');
	}

	if ($Row['isCheckType'] == '1') {
		$EnableQCoreClass->replace('isCheckType', 'checked');
		$EnableQCoreClass->replace('negText', qshowquotestring($Row['allowType']));
	}
	else {
		$EnableQCoreClass->replace('isCheckType', '');
		$EnableQCoreClass->replace('negText', '');
	}

	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
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

	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID < \'' . $Row['orderByID'] . '\' ORDER BY orderByID ASC ';
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
	$EnableQCoreClass->replace('isSelect', '');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('perRowCol', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	$EnableQCoreClass->replace('isCheckType', '');
	$EnableQCoreClass->replace('negText', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken17'] = session_id();
$EnableQCoreClass->parse('AutoEdit', 'AutoEditFile');
$EnableQCoreClass->output('AutoEdit');

?>
