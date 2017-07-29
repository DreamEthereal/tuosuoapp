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

if ($_POST['Action'] == 'AddTextSubmit') {
	if (!isset($_SESSION['PageToken4']) || ($_SESSION['PageToken4'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$isHaveUnkown = ($_POST['isHaveUnkown'] == '' ? 1 : $_POST['isHaveUnkown']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'4\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isHaveUnkown=\'' . $isHaveUnkown . '\',unitText=\'' . trim($_POST['unitText']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');

		if ($_POST['isSelect'] == 1) {
			$optionText = explode('######', $_POST['optionText']);
			$i = 0;

			for (; $i < count($optionText); $i++) {
				if (trim($optionText[$i]) != '') {
					$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $lastQuestionID . '\',optionText=\'' . qnoreturnchar(trim($optionText[$i])) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken4']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_text_question'] . ':' . $questionName);
		_showmessage($lang['add_text_question'] . ':' . $questionName, true, 1);
	}
	else {
		if (($_POST['modiMode'] == 1) && ($_POST['isCheckType'] != '4')) {
			_showerror('一致性错误', '一致性错误：问卷内有后续问题基于此问题，输入检查规则必须是数值!');
		}

		$questionName = qnoreturnchar($_POST['questionName']);
		$isHaveUnkown = ($_POST['isHaveUnkown'] == '' ? 1 : $_POST['isHaveUnkown']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isHaveUnkown=\'' . $isHaveUnkown . '\',unitText=\'' . trim($_POST['unitText']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['isSelect'] == 1) {
			$SQL = ' DELETE FROM ' . TEXT_OPTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
			$DB->query($SQL);
			$optionText = explode('######', $_POST['optionText']);
			$i = 0;

			for (; $i < count($optionText); $i++) {
				if (trim($optionText[$i]) != '') {
					$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $_POST['questionID'] . '\',optionText=\'' . qnoreturnchar(trim($optionText[$i])) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($_POST['isCheckType'] != '4') {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$HResult = $DB->query($HSQL);

			while ($HRow = $DB->queryArray($HResult)) {
				$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
				$DB->query($SQL);
			}

			$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			require ROOT_PATH . 'System/CheckValueRelation.php';
		}

		unset($_SESSION['PageToken4']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_text_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_text_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_text_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddTextOver') {
	if (!isset($_SESSION['PageToken4']) || ($_SESSION['PageToken4'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$isHaveUnkown = ($_POST['isHaveUnkown'] == '' ? 1 : $_POST['isHaveUnkown']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'4\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isHaveUnkown=\'' . $isHaveUnkown . '\',unitText=\'' . trim($_POST['unitText']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');

		if ($_POST['isSelect'] == 1) {
			$optionText = explode('######', $_POST['optionText']);
			$i = 0;

			for (; $i < count($optionText); $i++) {
				if (trim($optionText[$i]) != '') {
					$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $lastQuestionID . '\',optionText=\'' . qnoreturnchar(trim($optionText[$i])) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		unset($_SESSION['PageToken4']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_text_question'] . ':' . $questionName);
		_showmessage($lang['add_text_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		if (($_POST['modiMode'] == 1) && ($_POST['isCheckType'] != '4')) {
			_showerror('一致性错误', '一致性错误：问卷内有后续问题基于此问题，输入检查规则必须是数值!');
		}

		$questionName = qnoreturnchar($_POST['questionName']);
		$isHaveUnkown = ($_POST['isHaveUnkown'] == '' ? 1 : $_POST['isHaveUnkown']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isHaveUnkown=\'' . $isHaveUnkown . '\',unitText=\'' . trim($_POST['unitText']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);

		if ($_POST['isSelect'] == 1) {
			$SQL = ' DELETE FROM ' . TEXT_OPTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\'';
			$DB->query($SQL);
			$optionText = explode('######', $_POST['optionText']);
			$i = 0;

			for (; $i < count($optionText); $i++) {
				if (trim($optionText[$i]) != '') {
					$SQL = ' INSERT INTO ' . TEXT_OPTION_TABLE . ' SET questionID=\'' . $_POST['questionID'] . '\',optionText=\'' . qnoreturnchar(trim($optionText[$i])) . '\' ';
					$DB->query($SQL);
				}
			}
		}

		if ($_POST['isCheckType'] != '4') {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$HResult = $DB->query($HSQL);

			while ($HRow = $DB->queryArray($HResult)) {
				$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $HRow['relationID'] . '\' ';
				$DB->query($SQL);
			}

			$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			require ROOT_PATH . 'System/CheckValueRelation.php';
		}

		unset($_SESSION['PageToken4']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_text_question'] . ':' . $questionName);
		_showmessage($lang['add_text_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('TextEditFile', 'TextEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	if ($Row['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isNeg', '');
	}

	if ($Row['isSelect'] == '1') {
		$EnableQCoreClass->replace('isSelect', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isSelect', '');
	}

	if ($Row['isHaveUnkown'] == '2') {
		$EnableQCoreClass->replace('isHaveUnkown', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isHaveUnkown', '');
	}

	$OptSQL = ' SELECT optionText FROM ' . TEXT_OPTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ORDER BY optionID ASC ';
	$OptResult = $DB->query($OptSQL);
	$totalRecNum = $DB->_getNumRows($OptResult);
	$optionText = '';
	$i = 0;

	while ($OptRow = $DB->queryArray($OptResult)) {
		$i++;

		if ($i == $totalRecNum) {
			$optionText .= $OptRow['optionText'];
		}
		else {
			$optionText .= $OptRow['optionText'] . '######';
		}
	}

	$EnableQCoreClass->replace('optionText', $optionText);
	$EnableQCoreClass->replace('maxLength', $Row['length']);
	$EnableQCoreClass->replace('isCheckType' . $Row['isCheckType'], 'selected');
	$EnableQCoreClass->replace('isCheckType', $Row['isCheckType']);

	if ($Row['minOption'] == 0) {
		if ($Row['maxOption'] == 0) {
			$EnableQCoreClass->replace('minOption', '');
		}
		else {
			$EnableQCoreClass->replace('minOption', '0');
		}
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

	$EnableQCoreClass->replace('unitText', $Row['unitText']);
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
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
}
else {
	$EnableQCoreClass->replace('isRequired', 'checked');
	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('isSelect', '');
	$EnableQCoreClass->replace('isHaveUnkown', '');
	$EnableQCoreClass->replace('optionText', '');
	$EnableQCoreClass->replace('maxLength', '30');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('unitText', '');
	$EnableQCoreClass->replace('isCheckType0', 'selected');
	$EnableQCoreClass->replace('isCheckType1', '');
	$EnableQCoreClass->replace('isCheckType2', '');
	$EnableQCoreClass->replace('isCheckType3', '');
	$EnableQCoreClass->replace('isCheckType4', '');
	$EnableQCoreClass->replace('isCheckType5', '');
	$EnableQCoreClass->replace('isCheckType', 0);
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('modiMode', 2);
}

$_SESSION['PageToken4'] = session_id();
$EnableQCoreClass->parse('TextEdit', 'TextEditFile');
$EnableQCoreClass->output('TextEdit');

?>
