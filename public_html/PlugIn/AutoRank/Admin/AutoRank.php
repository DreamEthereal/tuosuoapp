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

if ($_POST['Action'] == 'AddAutoRankSubmit') {
	if (!isset($_SESSION['PageToken20']) || ($_SESSION['PageToken20'] != session_id())) {
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
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'20\',isRequired=\'' . $_POST['isRequired'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		updateorderid('question');
		unset($_SESSION['PageToken20']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorank_question'] . ':' . $questionName);
		_showmessage($lang['add_autorank_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken20']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorank_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_autorank_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_autorank_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddAutoRankOver') {
	if (!isset($_SESSION['PageToken20']) || ($_SESSION['PageToken20'] != session_id())) {
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
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'20\',isRequired=\'' . $_POST['isRequired'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken20']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorank_question'] . ':' . $questionName);
		_showmessage($lang['add_autorank_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',baseID=\'' . $_POST['baseID'] . '\',isNeg=\'' . $_POST['isNeg'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken20']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_autorank_question'] . ':' . $questionName);
		_showmessage($lang['add_autorank_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('AutoRankEditFile', 'AutoRankEdit.html');

if ($_GET['questionID'] != '') {
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
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('isNeg0', 'checked');
	$EnableQCoreClass->replace('isNeg1', '');
	$SQL = ' SELECT questionID,questionName FROM ' . QUESTION_TABLE . ' WHERE (questionType=3 OR questionType=25) AND surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$baseQuestionList = '';

	while ($baseRow = $DB->queryArray($Result)) {
		$baseQuestionName = qnohtmltag($baseRow['questionName'], 1);
		$baseQuestionList .= '<option value=\'' . $baseRow['questionID'] . '\'>' . $baseQuestionName . '</option>';
	}

	$EnableQCoreClass->replace('baseQuestionList', $baseQuestionList);
}

$_SESSION['PageToken20'] = session_id();
$EnableQCoreClass->parse('AutoRankEdit', 'AutoRankEditFile');
$EnableQCoreClass->output('AutoRankEdit');

?>
