<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowSurveyPlan.php';
$SQL = ' SELECT status,administratorsID,surveyID,surveyTitle,surveyName,isPublic,beginTime,endTime,lang,isCache,projectType FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sure_Row = $DB->queryFirstRow($SQL);

switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isDeployStat', 'none');
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isDeployStat', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;
}

if ($_POST['DeletePlanSubmit']) {
	if (is_array($_POST['planID'])) {
		if ($_POST['status'] != 1) {
			$planIDLists = join(',', $_POST['planID']);
			$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE planID IN (' . $planIDLists . ') ';
			$DB->query($SQL);
		}
		else {
			$i = 0;

			for (; $i < count($_POST['planID']); $i++) {
				if (date('Y-m-d', time()) <= $_POST['planDay'][$_POST['planID'][$i]]) {
					$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE planID = \'' . $_POST['planID'][$i] . '\'';
					$DB->query($SQL);
				}
			}
		}
	}

	writetolog($lang['edit_survey_plan'] . ':' . $_POST['surveyTitle']);
}

if ($_POST['UpdatePlanSubmit']) {
	if (is_array($_POST['planNum'])) {
		foreach ($_POST['planNum'] as $planID => $planNum) {
			$SQL = ' UPDATE ' . PLAN_TABLE . ' SET planNum=\'' . $planNum . '\' WHERE planID =\'' . $planID . '\' ';
			$DB->query($SQL);
		}

		writetolog($lang['edit_survey_plan'] . ':' . $_POST['surveyTitle']);
	}
}

if ($_POST['Action'] == 'PlanEditSubmit') {
	$SQL = ' SELECT planID FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND planDay=\'' . $_POST['planDay'] . '\' AND planID != \'' . $_POST['planID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		_showerror($lang['error_system'], $lang['plan_is_exist']);
	}

	$SQL = ' UPDATE ' . PLAN_TABLE . ' SET planDay=\'' . $_POST['planDay'] . '\',planNum=\'' . $_POST['planNum'] . '\',planDesc=\'' . $_POST['planDesc'] . '\' WHERE planID=\'' . $_POST['planID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['edit_survey_plan'] . ':' . $_POST['surveyTitle']);
	_showmessage($lang['edit_survey_plan'], true);
}

if ($_GET['Action'] == 'Edit') {
	$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE planID=\'' . $_GET['planID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyPlanAddPageFile', 'SurveyPlanEdit.html');
	$EnableQCoreClass->replace('planDay', $Row['planDay']);
	$EnableQCoreClass->replace('planNum', $Row['planNum']);
	$EnableQCoreClass->replace('planDesc', $Row['planDesc']);

	if ($Row['planDay'] < date('Y-m-d', time())) {
		$EnableQCoreClass->replace('isEdit', 'readonly');
	}
	else {
		$EnableQCoreClass->replace('isEdit', '');
	}

	$EnableQCoreClass->replace('planID', $Row['planID']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('beginTime', $_GET['beginTime']);
	$EnableQCoreClass->replace('endTime', $_GET['endTime']);
	$EnableQCoreClass->replace('nowTime', date('Y-m-d', time()));
	$EnableQCoreClass->replace('Action', 'PlanEditSubmit');
	$EnableQCoreClass->parse('SurveyPlanAddPage', 'SurveyPlanAddPageFile');
	$EnableQCoreClass->output('SurveyPlanAddPage', false);
}

if ($_POST['Action'] == 'PlanAddSubmit') {
	$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE planDay >= \'' . trim($_POST['beginPlanDay']) . '\' AND planDay <= \'' . trim($_POST['endPlanDay']) . '\' AND surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	$theBeginDate = explode('-', str_replace('/', '-', trim($_POST['beginPlanDay'])));
	$theBeginTime = mktime(0, 0, 0, $theBeginDate[1], $theBeginDate[2], $theBeginDate[0]);
	$theEndDate = explode('-', str_replace('/', '-', trim($_POST['endPlanDay'])));
	$theEndTime = mktime(0, 0, 0, $theEndDate[1], $theEndDate[2], $theEndDate[0]);
	$tempTime = $theBeginTime;

	while ($tempTime <= $theEndTime) {
		$planDay = date('Y-m-d', $tempTime);
		$SQL = ' INSERT INTO ' . PLAN_TABLE . ' SET planDay=\'' . $planDay . '\',planNum=\'' . $_POST['planNum'] . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
		$DB->query($SQL);
		$tempTime = $tempTime + 86400;
	}

	writetolog($lang['edit_survey_plan'] . ':' . $_POST['surveyTitle']);
	_showmessage($lang['edit_survey_plan'], true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('SurveyPlanAddPageFile', 'SurveyPlanAdd.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('beginTime', $_GET['beginTime']);
	$EnableQCoreClass->replace('endTime', $_GET['endTime']);
	$EnableQCoreClass->replace('nowTime', date('Y-m-d', time()));
	$EnableQCoreClass->replace('Action', 'PlanAddSubmit');
	$EnableQCoreClass->parse('SurveyPlanAddPage', 'SurveyPlanAddPageFile');
	$EnableQCoreClass->output('SurveyPlanAddPage', false);
}

if ($_GET['Action'] == 'List') {
	$SQL = ' DELETE FROM ' . PLAN_TABLE . ' WHERE (planDay < \'' . trim($_GET['beginTime']) . '\' OR planDay >= \'' . trim($_GET['endTime']) . '\') AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyPlanListPageFile', 'SurveyPlanList.html');
	$EnableQCoreClass->set_CycBlock('SurveyPlanListPageFile', 'PLAN', 'plan');
	$EnableQCoreClass->replace('plan', '');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$nowTime = date('Y-m-d', time());

	if ($_GET['beginTime'] != '') {
		$theBeginTime = $_GET['beginTime'];
	}
	else {
		$theBeginTime = $nowTime;
	}

	if ($_GET['endTime'] != '') {
		$theEndTime = $_GET['endTime'];
	}
	else {
		$theEndTime = $nowTime;
	}

	$addURL = '?Action=Add&beginTime=' . $theBeginTime . '&endTime=' . $theEndTime . '&surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'];
	$EnableQCoreClass->replace('addURL', $addURL);
	$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY planDay ASC ';
	$Result = $DB->query($SQL);
	$recNum = $DB->_getNumRows($Result);
	$EnableQCoreClass->replace('recNum', $recNum);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('planID', $Row['planID']);
		$EnableQCoreClass->replace('planDay', $Row['planDay']);

		if ($Row['planDay'] != '') {
			$thePlanDay = explode('-', $Row['planDay']);
			$WeekDayTime = mktime(0, 0, 0, $thePlanDay[1], $thePlanDay[2], $thePlanDay[0]);
			$WeekDay = date('w', $WeekDayTime);

			switch ($WeekDay) {
			case '0':
				$Week = $lang['day7'];
				break;

			case '1':
				$Week = $lang['day1'];
				break;

			case '2':
				$Week = $lang['day2'];
				break;

			case '3':
				$Week = $lang['day3'];
				break;

			case '4':
				$Week = $lang['day4'];
				break;

			case '5':
				$Week = $lang['day5'];
				break;

			case '6':
				$Week = $lang['day6'];
				break;
			}
		}
		else {
			$Week = $lang['day7'];
		}

		$EnableQCoreClass->replace('weekDay', $Week);

		if ($Row['planDesc'] == '') {
			$EnableQCoreClass->replace('planDesc', '&nbsp;');
		}
		else {
			$EnableQCoreClass->replace('planDesc', $Row['planDesc']);
		}

		if ($Row['planDay'] < $nowTime) {
			$EnableQCoreClass->replace('action', '&nbsp;');
			$EnableQCoreClass->replace('planNum', $Row['planNum']);
		}
		else {
			$editURL = '?Action=Edit&beginTime=' . $theBeginTime . '&endTime=' . $theEndTime . '&surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'] . '&planID=' . $Row['planID'];
			$action = '<a href=javascript:void(0) onclick="javascript:showPopWin(\'' . $editURL . '\',600, 340, Init, null,\'' . $lang['edit_survey_plan'] . '\');">' . $lang['list_action_modi'] . '</a>';
			$EnableQCoreClass->replace('action', $action);
			$EnableQCoreClass->replace('planNum', '<input onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')" align=center type=text size=7 name="planNum[' . $Row['planID'] . ']" id="planNum_' . $Row['planID'] . '" value=' . $Row['planNum'] . '>');
		}

		$EnableQCoreClass->parse('plan', 'PLAN', true);
	}

	$SQL = ' SELECT SUM(planNum) as totalPlanNum FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 1 ';
	$TotalRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('totalPlanNum', $TotalRow['totalPlanNum']);
	$thePlanListPage = $EnableQCoreClass->parse('SurveyPlanListPage', 'SurveyPlanListPageFile');
	header('Content-Type:text/html; charset=gbk');
	echo $thePlanListPage;
	exit();
}

$EnableQCoreClass->setTemplateFile('SurveyPlanPageFile', 'SurveyPlan.html');
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&beginTime=' . $_GET['beginTime'] . '&endTime=' . $_GET['endTime'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('status', $_GET['status']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->parse('SurveyPlanPage', 'SurveyPlanPageFile');
$EnableQCoreClass->output('SurveyPlanPage', false);

?>
