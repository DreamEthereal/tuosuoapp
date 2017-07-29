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

if ($_POST['Action'] == 'AddDSNSubmit') {
	if (!isset($_SESSION['PageToken13']) || ($_SESSION['PageToken13'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$Conn = odbc_connect(trim($_POST['DSNConnect']), trim($_POST['DSNUser']), trim($_POST['DSNPassword']));

	if (!$Conn) {
		_showerror('System Error', 'Connection Failed:' . trim($_POST['DSNConnect']) . '-' . trim($_POST['DSNUser']) . '-' . trim($_POST['DSNPassword']));
	}

	$ODBC_Result = odbc_exec($Conn, _getsql($_POST['DSNSQL']));

	if (!$ODBC_Result) {
		_showerror('System Error', 'Error in SQL:' . trim($_POST['DSNSQL']));
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'13\',isRequired=\'' . $_POST['isRequired'] . '\',DSNConnect=\'' . trim($_POST['DSNConnect']) . '\',DSNSQL=\'' . trim($_POST['DSNSQL']) . '\',DSNUser=\'' . trim($_POST['DSNUser']) . '\',DSNPassword=\'' . trim($_POST['DSNPassword']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken13']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_DSN_question'] . ':' . $questionName);
		_showmessage($lang['add_DSN_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',DSNConnect=\'' . trim($_POST['DSNConnect']) . '\',DSNSQL=\'' . trim($_POST['DSNSQL']) . '\',DSNUser=\'' . trim($_POST['DSNUser']) . '\',DSNPassword=\'' . trim($_POST['DSNPassword']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken13']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_DSN_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_DSN_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_DSN_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddDSNOver') {
	if (!isset($_SESSION['PageToken13']) || ($_SESSION['PageToken13'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	$Conn = odbc_connect(trim($_POST['DSNConnect']), trim($_POST['DSNUser']), trim($_POST['DSNPassword']));

	if (!$Conn) {
		_showerror('System Error', 'Connection Failed:' . trim($_POST['DSNConnect']) . '-' . trim($_POST['DSNUser']) . '-' . trim($_POST['DSNPassword']));
	}

	$ODBC_Result = odbc_exec($Conn, _getsql($_POST['DSNSQL']));

	if (!$ODBC_Result) {
		_showerror('System Error', 'Error in SQL:' . trim($_POST['DSNSQL']));
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'13\',isRequired=\'' . $_POST['isRequired'] . '\',DSNConnect=\'' . trim($_POST['DSNConnect']) . '\',DSNSQL=\'' . trim($_POST['DSNSQL']) . '\',DSNUser=\'' . trim($_POST['DSNUser']) . '\',DSNPassword=\'' . trim($_POST['DSNPassword']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		unset($_SESSION['PageToken13']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_DSN_question'] . ':' . $questionName);
		_showmessage($lang['add_DSN_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',DSNConnect=\'' . trim($_POST['DSNConnect']) . '\',DSNSQL=\'' . trim($_POST['DSNSQL']) . '\',DSNUser=\'' . trim($_POST['DSNUser']) . '\',DSNPassword=\'' . trim($_POST['DSNPassword']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		unset($_SESSION['PageToken13']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_DSN_question'] . ':' . $questionName);
		_showmessage($lang['add_DSN_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('DSNEditFile', 'DSNEdit.html');

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	$EnableQCoreClass->replace('DSNConnect', qshowquotestring($Row['DSNConnect']));
	$EnableQCoreClass->replace('DSNSQL', $Row['DSNSQL']);
	$EnableQCoreClass->replace('DSNUser', qshowquotestring($Row['DSNUser']));
	$EnableQCoreClass->replace('DSNPassword', qshowquotestring($Row['DSNPassword']));
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
}
else {
	$EnableQCoreClass->replace('isRequired', '');
	$EnableQCoreClass->replace('DSNConnect', '');
	$EnableQCoreClass->replace('DSNUser', '');
	$EnableQCoreClass->replace('DSNPassword', '');
	$EnableQCoreClass->replace('DSNSQL', 'SELECT state_code AS ItemValue,state_name AS ItemDisplay FROM tblState ORDER BY state_name ASC ');
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('questionNotes', '');
	$EnableQCoreClass->replace('alias', '');
}

$_SESSION['PageToken13'] = session_id();
$EnableQCoreClass->parse('DSNEdit', 'DSNEditFile');
$EnableQCoreClass->output('DSNEdit');

?>
