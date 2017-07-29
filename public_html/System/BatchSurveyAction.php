<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
_checkroletype(1);
$thisProg = 'BatchSurveyAction.php';
$EnableQCoreClass->replace('thisProg', $thisProg);

if ($_POST['Action'] == 'BatchCopySubmit') {
	if ($License['Limited'] == 1) {
		$SQL = ' SELECT COUNT(*) AS surveyNum FROM ' . SURVEY_TABLE . ' LIMIT 1 ';
		$Row = $DB->queryFirstRow($SQL);
		$theRemainNum = $License['LimitedNum'] - $Row['surveyNum'];

		if ($theRemainNum < $_POST['copyNum']) {
			_showerror($lang['limited_soft'], $lang['limited_soft']);
		}
	}

	$m = 1;

	for (; $m <= $_POST['copyNum']; $m++) {
		$lastSurveyID = $_POST['theCopySurveyID'];
		$isCheckSurveyName = 0;
		$theCopyOrderNo = $m;
		require 'Survey.copy.php';
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['batch_copy_survey']);
	_showsucceed($lang['batch_copy_survey'], $thisProg);
}

if ($_POST['Action'] == 'BatchDeploySubmit') {
	foreach ($_POST['theDeploySurveyList'] as $theDeploySurveyID) {
		$isAjaxActionFlag = 0;
		require 'Survey.deploy.php';
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=1 WHERE surveyID =\'' . $theDeploySurveyID . '\' ';
		$DB->query($SQL);
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['batch_deploy_survey']);
	_showsucceed($lang['batch_deploy_survey'], $thisProg);
}

if ($_POST['Action'] == 'BatchDeleteSubmit') {
	foreach ($_POST['theDeleteSurveyID'] as $survey_ID) {
		require 'Survey.dele.php';
	}

	require ROOT_PATH . 'Export/Database.opti.sql.php';
	writetolog($lang['batch_delete_survey']);
	_showsucceed($lang['batch_delete_survey'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('BatchSurveyActionFile', 'BatchSurveyAction.html');
$copySurveyIDList = '';
$SQL = ' SELECT surveyTitle,surveyName,surveyID FROM ' . SURVEY_TABLE . ' ORDER BY surveyID DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$HaveSQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if (0 < $HaveRow['qtnNum']) {
		$copySurveyIDList .= '<option value=' . $Row['surveyID'] . '>' . qnohtmltag($Row['surveyTitle']) . '(' . $Row['surveyName'] . ')[' . $lang['have_qtn_num'] . $HaveRow['qtnNum'] . ']</option>' . "\n" . '';
	}
}

$EnableQCoreClass->replace('copySurveyIDList', $copySurveyIDList);
$deploySurveyIDList = '';
$deleteSurveyIDList = '';
$SQL = ' SELECT surveyTitle,surveyName,surveyID FROM ' . SURVEY_TABLE . ' WHERE status=0 ORDER BY surveyID DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$HaveSQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if (0 < $HaveRow['qtnNum']) {
		$deploySurveyIDList .= '<option value=' . $Row['surveyID'] . '>' . qnohtmltag($Row['surveyTitle']) . '(' . $Row['surveyName'] . ')[' . $lang['have_qtn_num'] . $HaveRow['qtnNum'] . ']</option>' . "\n" . '';
	}

	$deleteSurveyIDList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')[' . $lang['have_qtn_num'] . $HaveRow['qtnNum'] . ']</option>' . "\n" . '';
}

$EnableQCoreClass->replace('deploySurveyIDList', $deploySurveyIDList);
$EnableQCoreClass->replace('deleteSurveyIDList', $deleteSurveyIDList);
$BatchSurveyActionPage = $EnableQCoreClass->parse('BatchSurveyAction', 'BatchSurveyActionFile');
echo $BatchSurveyActionPage;

?>
