<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$thisProg = 'ShowSurveyList.php?Action=EditAppealUser&surveyID=' . $_GET['surveyID'] . '&projectOwner=' . $_GET['projectOwner'];

if ($_POST['formAction'] == 'AppUserSubmit') {
	if (is_array($_POST['userID'])) {
		foreach ($_POST['userID'] as $thisUserId) {
			$hSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND administratorsID = \'' . $thisUserId . '\' AND isAuth=0 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				$SQL = ' INSERT INTO ' . APPEALUSERLIST_TABLE . ' SET administratorsID=\'' . $thisUserId . '\',surveyID=\'' . $_POST['surveyID'] . '\',isAuth=0 ';
			}

			$DB->query($SQL);
		}

		writetolog($lang['edit_survey_user'] . ':' . $_POST['surveyTitle']);
	}

	_showsucceed($lang['edit_survey_user'] . ':' . $_POST['surveyTitle'], $thisProg);
}

if ($_POST['formAction'] == 'CancelUserSubmit') {
	if (is_array($_POST['userID'])) {
		$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID IN (' . implode(',', $_POST['userID']) . ') AND surveyID=\'' . $_POST['surveyID'] . '\' AND isAuth=0 ';
		$DB->query($SQL);
		writetolog($lang['edit_survey_user'] . ':' . $_POST['surveyTitle']);
	}

	_showsucceed($lang['edit_survey_user'] . ':' . $_POST['surveyTitle'], $thisProg);
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$SQL = ' SELECT surveyID,surveyTitle,projectType,projectOwner FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['projectType'] != 1) {
	_showerror('系统错误', '问卷类型错误；您要设定申诉允许用户的问卷所属项目类型尚未是\'神秘客\'类型，您的操作无法继续!');
}

$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$EnableQCoreClass->replace('projectOwner', $Row['projectOwner']);
$EnableQCoreClass->setTemplateFile('SurveyUserEditPageFile', 'SurveyAppealUserEdit.html');
$EnableQCoreClass->set_CycBlock('SurveyUserEditPageFile', 'USER', 'user');
$EnableQCoreClass->replace('user', '');
$numPerPage = 50;
$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['projectOwner'] . '-%\' OR b.userGroupID = \'' . $Row['projectOwner'] . '\') ';
$SQL = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =3 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND a.groupType =2 ';

if ($_POST['Action'] == 'querySubmit') {
	$name = trim($_POST['t_name']);
	$SQL .= ' AND a.administratorsName LIKE \'%' . $name . '%\' ';
	$page_others .= '&t_name=' . urlencode($name);
	$EnableQCoreClass->replace('t_name', $name);
}
else {
	$EnableQCoreClass->replace('t_name', '');
}

if (isset($_GET['t_name']) && !$_POST['Action']) {
	$name = trim($_GET['t_name']);
	$SQL .= ' AND a.administratorsName LIKE \'%' . $name . '%\' ';
	$page_others .= '&t_name=' . $name;
	$EnableQCoreClass->replace('t_name', $name);
}

$Result = $DB->query($SQL);
$recNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalRecNum', $recNum);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $numPerPage;
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY b.absPath ASC,a.administratorsID ASC LIMIT ' . $start . ',' . $numPerPage . ' ';
$Result = $DB->query($SQL);

while ($UserRow = $DB->queryArray($Result)) {
	$HaveSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID=\'' . $UserRow['administratorsID'] . '\' AND surveyID=\'' . $Row['surveyID'] . '\' AND isAuth=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);
	$administrators_Name = _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']);
	$EnableQCoreClass->replace('userName', $administrators_Name);
	$EnableQCoreClass->replace('userID', $UserRow['administratorsID']);

	if ($HaveRow) {
		$EnableQCoreClass->replace('isAppealUser', '<img src="../Images/check_yes.gif" border=0>');
	}
	else {
		$EnableQCoreClass->replace('isAppealUser', '&nbsp;');
	}

	$EnableQCoreClass->parse('user', 'USER', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recNum, $numPerPage);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('SurveyUserEditPage', 'SurveyUserEditPageFile');
$EnableQCoreClass->output('SurveyUserEditPage', false);

?>
