<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('4', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isCache,surveyID,projectType,projectOwner FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$thisURL = 'ShowInputerTask.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisProg = $thisURL . '&stat=' . $_GET['stat'];
$EnableQCoreClass->replace('listURL', $thisURL);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$listDataURL = 'ShowInputData.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('listDataURL', $listDataURL);

if ($_GET['stat'] == 1) {
	$EnableQCoreClass->setTemplateFile('MainPageFile', 'InputTaskList1.html');
}
else {
	$EnableQCoreClass->setTemplateFile('MainPageFile', 'InputTaskList0.html');
}

$EnableQCoreClass->set_CycBlock('MainPageFile', 'TASK', 'task');
$EnableQCoreClass->replace('task', '');
$SQL = ' SELECT a.taskID,b.userGroupName,b.userGroupDesc FROM ' . TASK_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.surveyID = \'' . $_GET['surveyID'] . '\' AND a.administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND a.taskID = b.userGroupID ORDER BY a.taskID ASC ';
$Result = $DB->query($SQL);
$recNum = 0;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('taskName', $Row['userGroupName']);
	$EnableQCoreClass->replace('taskDesc', $Row['userGroupDesc']);
	$EnableQCoreClass->replace('taskID', $Row['taskID']);
	$hSQL = ' SELECT joinTime,overFlag,submitTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE taskID = \'' . $Row['taskID'] . '\' AND area = \'' . $_SESSION['administratorsName'] . '\' LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($_GET['stat'] == 1) {
		$EnableQCoreClass->replace('stat1', 'class="cur"');
		$EnableQCoreClass->replace('stat0', '');
		$EnableQCoreClass->replace('taskTime', date('Y-m-d H:i:s', $hRow['submitTime']));
		if ($hRow && (($hRow['overFlag'] == 1) || ($hRow['overFlag'] == 3))) {
			$recNum++;
			$EnableQCoreClass->parse('task', 'TASK', true);
		}
	}
	else {
		$EnableQCoreClass->replace('stat0', 'class="cur"');
		$EnableQCoreClass->replace('stat1', '');
		$inputURL = 'InputSurveyAnswer.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&taskID=' . $Row['taskID'];

		if (!$hRow) {
			$actionTitle = '<a href=\'' . $inputURL . '\'>录入数据</a>';
			$EnableQCoreClass->replace('actionTitle', $actionTitle);
			$recNum++;
			$EnableQCoreClass->parse('task', 'TASK', true);
		}
		else {
			if (($hRow['overFlag'] == 0) || ($hRow['overFlag'] == 2)) {
				$actionTitle = '<a href=\'' . $inputURL . '\'>继续录入</a>';
				$EnableQCoreClass->replace('actionTitle', $actionTitle);
				$recNum++;
				$EnableQCoreClass->parse('task', 'TASK', true);
			}
		}
	}
}

$EnableQCoreClass->replace('recNum', $recNum);

if ($recNum == 0) {
	if ($_GET['stat'] == 1) {
		$EnableQCoreClass->replace('stat1', 'class="cur"');
		$EnableQCoreClass->replace('stat0', '');
	}
	else {
		$EnableQCoreClass->replace('stat0', 'class="cur"');
		$EnableQCoreClass->replace('stat1', '');
	}
}

$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>
