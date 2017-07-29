<?php
//dezend by http://www.yunlu99.com/
function _gettplpagefile($panelID, $siteTitle, $SurveyPage)
{
	global $DB;
	global $EnableQCoreClass;
	global $Config;
	global $S_Row;

	if (in_array($panelID, array(30001, 30002, 30003, 30004, 30005, 30006))) {
		$_obf_153cdraAOQM_ = 'DefaultPanel' . substr($panelID, 4, 1) . '.html';
		$_obf_arKgAERaZb4_ = 1;
	}
	else {
		$_obf_xCnI = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID = \'' . $panelID . '\' ';
		$_obf_JE9AcaSM = $DB->queryFirstRow($_obf_xCnI);
		$_obf_153cdraAOQM_ = $_obf_JE9AcaSM['tplName'];
		if ($_obf_JE9AcaSM && file_exists(ROOT_PATH . 'Templates/CN/' . $_obf_153cdraAOQM_)) {
			$_obf_153cdraAOQM_ = $_obf_JE9AcaSM['tplName'];
		}
		else {
			$_obf_153cdraAOQM_ = 'DefaultPanel.html';
		}

		$_obf_arKgAERaZb4_ = 0;
	}

	$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/CN');
	$EnableQCoreClass->setTemplateFile('ResultPageFile', $_obf_153cdraAOQM_);
	$EnableQCoreClass->replace('enableq', $SurveyPage);
	$EnableQCoreClass->replace('siteTitle', $siteTitle . '-' . $Config['siteName']);
	$EnableQCoreClass->replace('siteName', $Config['siteName']);

	if ($_obf_arKgAERaZb4_) {
		$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
		$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
		$EnableQCoreClass->replace('haveText', $S_Row['surveyInfo'] == '' ? 'noHaveText' : 'haveText');
		$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle'] == '' ? '{surveyURL}' : $S_Row['surveySubTitle']);
	}

	$SurveyPage = $EnableQCoreClass->parse('ResultPage', 'ResultPageFile');
	return $SurveyPage;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.cond.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}

$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' LIMIT 0,1 ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash_code']) != md5(trim($SerialRow['license']))) {
	_shownotes($lang['system_error'], 'EnableQ Security Violation', 'EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
$S_Row = $DB->queryFirstRow($SQL);
$BaseSQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' LIMIT 0,1 ';
$BaseRow = $DB->queryFirstRow($BaseSQL);
$surveyTplFile = 'uSurvey.html';

if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
	$surveyTplFile = 'uSurveySystem.html';
}

$EnableQCoreClass->setTemplateFile('ShowSurveyFile', $surveyTplFile);
$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$EnableQCoreClass->replace('theme', $S_Row['theme']);
$EnableQCoreClass->replace('isGeolocation', $S_Row['isGeolocation']);
$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
$EnableQCoreClass->replace('surveyLang', $language);
$EnableQCoreClass->replace('waitingScript', '');
$EnableQCoreClass->replace('waitingTime', 0);
$EnableQCoreClass->replace('thisStep', 0);
$EnableQCoreClass->replace('processBar', '');
$EnableQCoreClass->replace('limitedScript', '');
$EnableQCoreClass->replace('limitedTime', 0);
$EnableQCoreClass->replace('limitedTimeBar', '');
$EnableQCoreClass->replace('secureImage', '');
$EnableQCoreClass->replace('isCheckCode', 0);
$check_survey_form_list = '';
$check_survey_conditions_list = '';
$this_fields_list = '';
$this_file_list = '';
$this_size_list = '';
$this_hidden_list = '';
$this_check_list = '';
$survey_quota_list = '';
$survey_empty_list = '';
$theHaveFileUpload = false;
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
$theHaveFileCascade = false;
$theMgtFunc = 1;
if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	$EnableQCoreClass->replace('questionID', $questionID);
	$surveyID = $S_Row['surveyID'];

	if (!empty($CondListArray[$questionID])) {
		$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'none');
	}
	else {
		$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'block');
	}

	$isHiddenFields = true;
	$ModuleName = $Module[$theQtnArray['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

	if ($theQtnArray['questionType'] == '12') {
		$isHiddenFields = false;
	}

	if ($isHiddenFields == true) {
		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}

$EnableQCoreClass->replace('thisFields', base64_encode($this_fields_list));
$EnableQCoreClass->replace('thisFiles', $this_file_list);
$EnableQCoreClass->replace('thisSizes', $this_size_list);
$EnableQCoreClass->replace('thisHidden', $this_hidden_list);
$EnableQCoreClass->replace('allHidden', $this_hidden_list);
$EnableQCoreClass->replace('allFields', base64_encode($this_fields_list . $this_file_list));
$EnableQCoreClass->replace('thisCheck', $this_check_list);
$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
$EnableQCoreClass->replace('hiddenFields', '<input type="hidden" name="isHTMLPage" id="isHTMLPage" value="1">');
$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
if (($S_Row['isViewResult'] == 1) && ($S_Row['isShowResultBut'] == 1)) {
	$actionButton .= '<input class=btn type=button value="' . $lang['view_result'] . '" name="ViewResultSubmit" id="ViewResultSubmit" onClick="window.open(\'v.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']) . '&qlang=' . $_GET['qlang'] . '\',\'\',\'width=600,height=400,resizable=yes,scrollbars=yes\');">';
}
else {
	$actionButton .= '';
}

$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form_Submit();">';
$EnableQCoreClass->replace('actionButton', $actionButton);

if ($S_Row['isSecureImage'] == 1) {
	$secureImage = '<table width="100%" class="pertable"><tr><td><table cellSpacing=0 cellPadding=0><tr><td height=25 class="question"  valign=bottom><span class=red>*</span>' . $lang['verifyCode'] . '</td><td valign=center>&nbsp;&nbsp;<input name="verifyCode" id="verifyCode" size=18>&nbsp;<img id="verifyImage" src="JS/CreateVerifyCode.js.php?sid=' . md5(uniqid(time())) . '">&nbsp;&nbsp;<span class="answer"><a href="javascript:ReloadImage();"><b>' . $lang['reloadImage'] . '</b></a></td></tr></table></td></tr><tr><td height=5 class="surveyclear">&nbsp;</td></tr></table>';

	if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
		$secureImage = '<table width="100%" cellpadding="0" cellspacing="0" class="pertable"><tr><td height=25 class="question" valign=center><span class=red>*</span>' . $lang['verifyCode'] . '</td></tr><tr><td height=2 class="surveyclear">&nbsp;</td></tr><tr><td valign=center>&nbsp;&nbsp;<input name="verifyCode" id="verifyCode" size=18>&nbsp;<img align=absmiddle id="verifyImage" src="JS/CreateVerifyCode.js.php?sid=' . md5(uniqid(time())) . '">&nbsp;&nbsp;<span class="answer"><a href="javascript:ReloadImage();"><b>' . $lang['reloadImage'] . '</b></a></td></tr><tr><td height=2 class="surveyclear">&nbsp;</td></tr></table>';
	}

	$EnableQCoreClass->replace('secureImage', $secureImage);
	$EnableQCoreClass->replace('isCheckCode', 1);
	$check_survey_form_list .= '	if (!CheckNotNull(document.Survey_Form.verifyCode, "' . $lang['verifyCode'] . '")){return false;} ' . "\n" . '';
}

$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
$SurveyPage = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
$SurveyPage = _gettplpagefile($S_Row['panelID'], $S_Row['surveyTitle'], $SurveyPage);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
$SurveyPage = str_replace($All_Path, '', $SurveyPage);

if ($Config['dataDomainName'] != '') {
	$fullPath = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$fullPath = $All_Path;
}

$SurveyPage = str_replace('CSS/', $fullPath . 'CSS/', $SurveyPage);
$SurveyPage = str_replace('JS/', $fullPath . 'JS/', $SurveyPage);
$SurveyPage = str_replace('Images/', $fullPath . 'Images/', $SurveyPage);
$SurveyPage = str_replace('PerUserData/', $fullPath . 'PerUserData/', $SurveyPage);
$SurveyPage = str_replace('v.php', $fullPath . 'v.php', $SurveyPage);
$SurveyPage = str_replace('action=""', 'action="' . $fullPath . 'q.php?qname=' . trim($_GET['qname']) . '&qlang=' . $_GET['qlang'] . '"', $SurveyPage);
$SurveyPage = preg_replace('/<!-- DELETE BEGINPART -->(.*)<!-- DELETE ENDPART -->/s', '', $SurveyPage);
$SurveyPage = str_replace('{IMGFULLPATH}/', '', $SurveyPage);
$SurveyPage = str_replace('{surveyURL}', $fullPath . 'q.php?qname=' . $S_Row['surveyName'], $SurveyPage);
echo $SurveyPage;
exit();

?>
