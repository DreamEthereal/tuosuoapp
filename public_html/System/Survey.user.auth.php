<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($_POST['Action'] == 'EditAuthUserSubmit') {
	if ($_POST['viewUsers'] != '') {
		if ($_POST['nowViewValue'] != '') {
			$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND  administratorsID IN (' . $_POST['nowViewValue'] . ') ';
			$DB->query($SQL);
		}

		$i = 0;

		for (; $i < count($_POST['viewUsers']); $i++) {
			$SQL = ' INSERT INTO ' . VIEWUSERLIST_TABLE . ' SET administratorsID=\'' . $_POST['viewUsers'][$i] . '\',surveyID=\'' . $_POST['surveyID'] . '\',isAuth=1 ';
			$DB->query($SQL);
		}
	}
	else if ($_POST['nowViewValue'] != '') {
		$SQL = ' DELETE FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND  administratorsID IN (' . $_POST['nowViewValue'] . ') ';
		$DB->query($SQL);
	}

	writetolog($lang['edit_survey_user'] . ':' . $_POST['surveyTitle']);
	_showmessage($lang['edit_survey_user'] . ':' . $_POST['surveyTitle'], true, $_POST['surveyID']);
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
$SQL = ' SELECT surveyID,surveyTitle,administratorsID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$EnableQCoreClass->setTemplateFile('SurveyUserEditPageFile', 'SurveyAuthUserEdit.html');
$SQL = ' SELECT isAdmin,userGroupID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
$AuthRow = $DB->queryFirstRow($SQL);

switch ($AuthRow['isAdmin']) {
case '1':
	$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =7 AND groupType=1 ORDER BY administratorsID ASC ';
	break;

case '2':
case '5':
	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '6':
		$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =7 AND groupType=1 ORDER BY administratorsID ASC ';
		break;

	default:
		if ($AuthRow['userGroupID'] == 0) {
			$theSonSQL = ' concat(\'-\',absPath,\'-\') LIKE \'%-' . $AuthRow['userGroupID'] . '-%\' ';
			$theSonGroup = array();
			$theSonGroup[] = 0;
			$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND groupType =1 ';
			$sResult = $DB->query($sSQL);

			while ($sRow = $DB->queryArray($sResult)) {
				$theSonGroup[] = $sRow['userGroupID'];
			}

			$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=7 AND groupType=1 AND userGroupID IN (' . implode(',', $theSonGroup) . ') ORDER BY administratorsID ASC ';
		}
		else {
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $AuthRow['userGroupID'] . '-%\' OR b.userGroupID = \'' . $AuthRow['userGroupID'] . '\') ';
			$UserSQL = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =7 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND a.groupType =1 ORDER BY b.absPath ASC,a.administratorsID ASC ';
		}

		break;
	}

	break;
}

$UserResult = $DB->query($UserSQL);
$usersList = $viewNameList = '';
$nowViewValue = '';

while ($UserRow = $DB->queryArray($UserResult)) {
	$HaveSQL = ' SELECT administratorsID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID=\'' . $UserRow['administratorsID'] . '\' AND surveyID=\'' . $Row['surveyID'] . '\' AND isAuth=1 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);
	$administrators_Name = _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']);

	if ($HaveRow) {
		$usersList .= '<option value=\'' . $UserRow['administratorsID'] . '\'>' . $administrators_Name . '(' . $UserRow['nickName'] . ')</option>';
		$nowViewValue .= $UserRow['administratorsID'] . ',';
	}
	else {
		$viewNameList .= '<option value=\'' . $UserRow['administratorsID'] . '\'>' . $administrators_Name . '(' . $UserRow['nickName'] . ')</option>';
	}
}

$EnableQCoreClass->replace('usersList', $usersList);
$EnableQCoreClass->replace('viewNameList', $viewNameList);
$EnableQCoreClass->replace('nowViewValue', substr($nowViewValue, 0, -1));
$EnableQCoreClass->parse('SurveyUserEditPage', 'SurveyUserEditPageFile');
$EnableQCoreClass->output('SurveyUserEditPage', false);

?>
