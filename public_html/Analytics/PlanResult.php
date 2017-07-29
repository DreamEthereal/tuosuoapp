<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

switch ($Sur_G_Row['status']) {
case '0':
	_showerror($lang['system_error'], $lang['design_survey_now']);
	break;

case '2':
	$EnableQCoreClass->replace('planbtn', 'none');
	$EnableQCoreClass->replace('planURL', '');
	break;

case '1':
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		$EnableQCoreClass->replace('planbtn', 'none');
		$EnableQCoreClass->replace('planURL', '');
	}
	else {
		$EnableQCoreClass->replace('planbtn', '');
		$planURL = '../System/ShowSurveyPlan.php?status=' . $Sur_G_Row['status'] . '&surveyID=' . $Sur_G_Row['surveyID'] . '&beginTime=' . urlencode($Sur_G_Row['beginTime']) . '&endTime=' . urlencode($Sur_G_Row['endTime']) . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']);
		$EnableQCoreClass->replace('planURL', $planURL);
	}

	break;
}

$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE (planDay < \'' . $Sur_G_Row['beginTime'] . '\' OR planDay >= \'' . $Sur_G_Row['endTime'] . '\') AND surveyID = \'' . $_GET['surveyID'] . '\' ';
$DB->query($SQL);
$thisURL = 'PlanResult.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('thisURL', $thisURL);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->setTemplateFile('PlanResultFile', 'SurveyPlanResult.html');
$EnableQCoreClass->set_CycBlock('PlanResultFile', 'PLAN', 'plan');
$EnableQCoreClass->replace('plan', '');
$SQL = ' SELECT SUM(planNum) as totalPlanNum FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 1 ';
$TotalRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('totalPlanNum', $TotalRow['totalPlanNum']);
$totalOverNum = 0;
$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY planDay ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('planDay', $Row['planDay']);

	if ($Row['planDay'] != '') {
		$thePlanDay = explode('-', $Row['planDay']);
		$WeekDayTime = mktime(0, 0, 0, $thePlanDay[1], $thePlanDay[2], $thePlanDay[0]);
		$WeekDay = date('w', $WeekDayTime);

		switch ($WeekDay) {
		case '0':
			$Week = 7;
			break;

		case '1':
			$Week = 1;
			break;

		case '2':
			$Week = 2;
			break;

		case '3':
			$Week = 3;
			break;

		case '4':
			$Week = 4;
			break;

		case '5':
			$Week = 5;
			break;

		case '6':
			$Week = 6;
			break;
		}
	}
	else {
		$Week = 7;
	}

	$EnableQCoreClass->replace('weekDay', $Week);
	$EnableQCoreClass->replace('planNum', $Row['planNum']);

	if ($Row['planDesc'] == '') {
		$EnableQCoreClass->replace('planDesc', '&nbsp;');
	}
	else {
		$EnableQCoreClass->replace('planDesc', $Row['planDesc']);
	}

	$thePlanDay = explode('-', $Row['planDay']);
	$thePlanDayTime = mktime(0, 0, 0, $thePlanDay[1], $thePlanDay[2], $thePlanDay[0]);
	$thePlanNextDayTime = $thePlanDayTime + 86400;
	$HaveSQL = ' SELECT COUNT(*) as recNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE joinTime >= \'' . $thePlanDayTime . '\' AND joinTime < \'' . $thePlanNextDayTime . '\' AND overFlag IN (1,3) LIMIT 1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);
	$theDayNum = $HaveRow['recNum'];

	if ($theDayNum != '0') {
		$EnableQCoreClass->replace('overNum', $theDayNum);
		$totalOverNum += $theDayNum;
	}
	else {
		$EnableQCoreClass->replace('overNum', 0);
	}

	if ($theDayNum != '0') {
		$EnableQCoreClass->replace('parent', number_format(($theDayNum / $Row['planNum']) * 100, 2));
		$EnableQCoreClass->replace('parentTotal', number_format(($theDayNum / $TotalRow['totalPlanNum']) * 100, 2));
	}
	else {
		$EnableQCoreClass->replace('parent', 0);
		$EnableQCoreClass->replace('parentTotal', 0);
	}

	$EnableQCoreClass->parse('plan', 'PLAN', true);
}

$EnableQCoreClass->replace('totalOverNum', $totalOverNum);
if (($TotalRow['totalPlanNum'] != 0) && ($TotalRow['totalPlanNum'] != '')) {
	$EnableQCoreClass->replace('parentOverTotal', number_format(($totalOverNum / $TotalRow['totalPlanNum']) * 100, 2));
}
else {
	$EnableQCoreClass->replace('parentOverTotal', 0);
}

$PlanResult = $EnableQCoreClass->parse('PlanResult', 'PlanResultFile');
echo $PlanResult;
exit();

?>
