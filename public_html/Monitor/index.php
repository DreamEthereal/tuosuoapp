<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.monitor.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|3|5|7');
$thisProg = 'index.php';

if ($License['isMonitor'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uMonitorIndex.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('siteTitle', $Config['siteName']);
$EnableQCoreClass->replace('nickName', $_SESSION['administratorsName']);

switch ($_SESSION['adminRoleType']) {
case 1:
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) ';
	break;

case 2:
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND status IN ( 1,2) ';
	break;

case 5:
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ') AND status IN ( 1,2) ';
	break;

case 3:
	switch ($_SESSION['adminRoleGroupType']) {
	case '1':
	default:
		$AuthSQL = ' SELECT surveyID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND isAuth = 0 ';
		$AuthResult = $DB->query($AuthSQL);

		while ($AuthRow = $DB->queryArray($AuthResult)) {
			$SurveyArray[] = $AuthRow['surveyID'];
		}

		if (!empty($SurveyArray)) {
			$SurveyList = implode(',', $SurveyArray);
			$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND surveyID IN (' . $SurveyList . ') ';
		}
		else {
			$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
		}

		break;

	case '2':
		$AuthSQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
		$AuthRow = $DB->queryFirstRow($AuthSQL);
		$theAbsPath = explode('-', $AuthRow['absPath']);

		if (count($theAbsPath) == 1) {
			$theOwnerId = $_SESSION['adminRoleGroupID'];
		}
		else {
			$theOwnerId = $theAbsPath[1];
		}

		if ($theOwnerId != 0) {
			$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND projectType = 1 AND projectOwner = \'' . $theOwnerId . '\' ';
		}
		else {
			$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
		}

		break;
	}

	break;

case 7:
	$AuthSQL = ' SELECT surveyID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND isAuth = 1 ';
	$AuthResult = $DB->query($AuthSQL);
	$theSurveyArray = array();

	while ($AuthRow = $DB->queryArray($AuthResult)) {
		$theSurveyArray[] = $AuthRow['surveyID'];
	}

	$AuthSQL = ' SELECT surveyID FROM ' . APPEALUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND isAuth = 1 ';
	$AuthResult = $DB->query($AuthSQL);

	while ($AuthRow = $DB->queryArray($AuthResult)) {
		$theSurveyArray[] = $AuthRow['surveyID'];
	}

	$SurveyArray = array_unique($theSurveyArray);

	if (!empty($SurveyArray)) {
		$SurveyList = implode(',', $SurveyArray);
		$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND surveyID IN (' . $SurveyList . ') ';
	}
	else {
		$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
	}

	break;
}

$SQL .= ' ORDER BY surveyID DESC ';
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);
	$EnableQCoreClass->replace('replyNum', $R_Row['resultNum']);
	$EnableQCoreClass->replace('beginTime', $Row['beginTime']);
	$EnableQCoreClass->replace('surveyURL', '../Analytics/DataOverview.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$EnableQCoreClass->parse('list', 'LIST', true);
}

$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;
exit();

?>
