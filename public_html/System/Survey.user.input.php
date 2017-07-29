<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($_POST['Action'] == 'EditInputUserSubmit') {
	if ($_POST['inputUsers'] != '') {
		if ($_POST['nowInputValue'] != '') {
			$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND  administratorsID IN (' . $_POST['nowInputValue'] . ') ';
			$DB->query($SQL);
		}

		$i = 0;

		for (; $i < count($_POST['inputUsers']); $i++) {
			$SQL = ' INSERT INTO ' . INPUTUSERLIST_TABLE . ' SET administratorsID=\'' . $_POST['inputUsers'][$i] . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
			$DB->query($SQL);
		}

		if ($_POST['nowInputValue'] != '') {
			$the_data_diff = arraydiff(explode(',', $_POST['nowInputValue']), $_POST['inputUsers']);

			if (count($the_data_diff) != 0) {
				$SQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE administratorsID IN (' . implode(',', $the_data_diff) . ') AND surveyID=\'' . $_POST['surveyID'] . '\' ';
				$DB->query($SQL);
			}
		}
	}
	else if ($_POST['nowInputValue'] != '') {
		$SQL = ' DELETE FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND  administratorsID IN (' . $_POST['nowInputValue'] . ') ';
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
$EnableQCoreClass->setTemplateFile('SurveyUserEditPageFile', 'SurveyInputUserEdit.html');
$SQL = ' SELECT isAdmin,userGroupID,administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
$AuthRow = $DB->queryFirstRow($SQL);

switch ($AuthRow['isAdmin']) {
case '1':
	$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=4 ORDER BY administratorsID ASC ';
	break;

case '2':
case '5':
	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '6':
		$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=4 ORDER BY administratorsID ASC ';
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

			$UserSQL = ' SELECT administratorsID,administratorsName,nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin=4 AND userGroupID IN (' . implode(',', $theSonGroup) . ') AND groupType =1 ORDER BY administratorsID ASC ';
		}
		else {
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $AuthRow['userGroupID'] . '-%\' OR b.userGroupID = \'' . $AuthRow['userGroupID'] . '\') ';
			$UserSQL = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =4 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND b.groupType =1 ORDER BY b.absPath ASC,a.administratorsID ASC ';
		}

		break;
	}

	break;
}

$inputList = $inputNameList = '';
$nowInputValue = '';
$UserResult = $DB->query($UserSQL);

while ($UserRow = $DB->queryArray($UserResult)) {
	$HaveSQL = ' SELECT administratorsID FROM ' . INPUTUSERLIST_TABLE . ' WHERE administratorsID=\'' . $UserRow['administratorsID'] . '\' AND surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);
	$administrators_Name = _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']);

	if ($HaveRow) {
		$inputList .= '<option value=\'' . $UserRow['administratorsID'] . '\'>' . $administrators_Name . '(' . $UserRow['nickName'] . ')</option>';
		$nowInputValue .= $UserRow['administratorsID'] . ',';
	}
	else {
		$inputNameList .= '<option value=\'' . $UserRow['administratorsID'] . '\'>' . $administrators_Name . '(' . $UserRow['nickName'] . ')</option>';
	}
}

$EnableQCoreClass->replace('inputList', $inputList);
$EnableQCoreClass->replace('inputNameList', $inputNameList);
$EnableQCoreClass->replace('nowInputValue', substr($nowInputValue, 0, -1));
$EnableQCoreClass->parse('SurveyUserEditPage', 'SurveyUserEditPageFile');
$EnableQCoreClass->output('SurveyUserEditPage', false);

?>
