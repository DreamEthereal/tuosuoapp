<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.monitor.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,surveyTitle,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isOnline0View,isOnline0Auth,isViewAuthData,isViewAuthInfo,isCache,isRecord,forbidViewId FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'QuotaResult.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->setTemplateFile('QuotaReCheckFile', 'uMonitorQuotaResult.html');
$EnableQCoreClass->set_CycBlock('QuotaReCheckFile', 'QUOTA', 'quota');
$EnableQCoreClass->replace('quota', '');
$valueLogicQtnList = array();
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/' . md5('Quota' . $_GET['surveyID']) . '.php')) {
	$theSID = $_GET['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/' . md5('Quota' . $_GET['surveyID']) . '.php';
$theLogicNum = 0;
$SQL = ' SELECT * FROM ' . QUOTA_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND quotaNum !=0 ORDER BY quotaID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$EnableQCoreClass->replace('quotaID', $Row['quotaID']);
	$EnableQCoreClass->replace('quotaName', $Row['quotaName']);
	$EnableQCoreClass->replace('quotaNum', $Row['quotaNum']);
	$theQuotaID = $Row['quotaID'];
	require ROOT_PATH . 'System/ShowQuota.inc.php';
	$conList = '';
	$qid = $_GET['surveyID'];
	require ROOT_PATH . 'JS/SurveyQuota.inc.php';

	if ($conList == '') {
		$EnableQCoreClass->replace('quotaNowNum', '-');
		$EnableQCoreClass->replace('quotaStat', '-');
	}
	else {
		$cSQL = ' SELECT COUNT(*) as quotaNowNum FROM ' . $table_prefix . 'response_' . $qid . ' WHERE overFlag IN (1,3) AND ' . $conList . ' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$EnableQCoreClass->replace('quotaNowNum', $cRow['quotaNowNum']);

		if ($Row['quotaNum'] == 0) {
			$EnableQCoreClass->replace('quotaStat', '-');
		}
		else {
			if ($cRow['quotaNowNum'] < $Row['quotaNum']) {
				$quotaStat = '<font color=red>未满</font>';
			}
			else if ($cRow['quotaNowNum'] == $Row['quotaNum']) {
				$quotaStat = '<font color=green>已满</font>';
			}
			else if ($Row['quotaNum'] < $cRow['quotaNowNum']) {
				$quotaStat = '<font color=red>超配额</font>';
			}

			$EnableQCoreClass->replace('quotaStat', $quotaStat);
		}
	}

	$EnableQCoreClass->parse('quota', 'QUOTA', true);
}

if ($theLogicNum == 0) {
	$EnableQCoreClass->replace('isHaveQuotaText', '<tr><td colspan=4 class="classtd" style="padding-left:10px"><span class=red>当前调查问卷未有配额定义</span></td></tr>');
}
else {
	$EnableQCoreClass->replace('isHaveQuotaText', '');
}

$EnableQCoreClass->parse('QuotaReCheck', 'QuotaReCheckFile');
$EnableQCoreClass->output('QuotaReCheck');

?>
