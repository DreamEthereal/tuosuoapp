<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.cons.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';

if ($_GET['qname'] == '') {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}
else {
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' LIMIT 0,1 ';
	$S_Row = $DB->queryFirstRow($SQL);

	if (!$S_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

_checkpassport('1|2|5', $S_Row['surveyID']);
$thisURL = 'p.php?qname=' . $S_Row['surveyName'] . '&qlang=' . $language;
$EnableQCoreClass->replace('thisURL', $thisURL);

if ($_POST['Action'] == 'SurveyOverSubmit') {
	$thisSurveyFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
	$thisSurveyFields = explode('|', $thisSurveyFieldsList);

	if (base64_decode($_POST['allFields']) != '') {
		foreach ($thisSurveyFields as $surveyFields) {
			unset($_SESSION[$surveyFields]);
		}
	}

	switch ($S_Row['exitMode']) {
	case '1':
		_shownotes($lang['survey_submit'], $S_Row['exitPage'], $lang['survey_gname'] . $S_Row['surveyTitle'], '<ul><li><span class="m">' . $lang['submit_false'] . '</span></li></ul>');
		break;

	case '2':
		$exitMess = nl2br($S_Row['exitTextBody']);
		_shownotes($S_Row['exitTitleHead'], $exitMess, $lang['survey_gname'] . $S_Row['surveyTitle'], '<ul><li><span class="m">' . $lang['submit_false'] . '</span></li></ul>');
		break;

	case '3':
		_shownotes($lang['grade_title'], $lang['submit_false'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		break;

	case '4':
		$theViewResultPage = 'v.php?surveyID=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'];
		_shownotes($lang['survey_submit'], $theViewResultPage, $lang['survey_gname'] . $S_Row['surveyTitle'], '<ul><li><span class="m">' . $lang['submit_false'] . '</span></li></ul>');
		break;

	case '5':
		_shownotes($lang['grade_title'], $lang['submit_false'], $lang['survey_gname'] . $S_Row['surveyTitle']);
		break;
	}
}

$theSID = $S_Row['surveyID'];
$isSurveyPre = 1;
require ROOT_PATH . 'Includes/MakeCache.php';
require ROOT_PATH . 'Includes/QuotaCache.php';
$valueLogicQtnList = array();
require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Cache' . $S_Row['surveyID']) . '.php';
require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Quota' . $S_Row['surveyID']) . '.php';
$thePageSurveyID = $S_Row['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = 0;
$surveyID = $S_Row['surveyID'];
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
$EnableQCoreClass->replace('theme', $S_Row['theme']);
$EnableQCoreClass->replace('isGeolocation', $S_Row['isGeolocation']);
$EnableQCoreClass->replace('secureImage', '');
$EnableQCoreClass->replace('isCheckCode', 0);
if (($_POST['Action'] == 'SurveyPreSubmit') || ($_POST['Action'] == 'SurveyNextSubmit')) {
	if ($_POST['Action'] == 'SurveyPreSubmit') {
		$thisLastPageStep = $_POST['thisStep'] - 1;

		if ($thisLastPageStep <= 0) {
			$thisLastPageStep = 0;
		}

		$thisPageStep = isskippage($thisLastPageStep, 2);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		$thisPageFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
		$thisPageFields = explode('|', $thisPageFieldsList);

		foreach ($thisPageFields as $theFields) {
			$_SESSION[$theFields] = $_POST[$theFields];
		}

		if ($_POST['thisFiles'] != '') {
			$thisPageFileUploadList = substr($_POST['thisFiles'], 0, -1);
			$thisPageFileUpload = explode('|', $thisPageFileUploadList);

			foreach ($thisPageFileUpload as $theFiles) {
				$theUploadFileName = trim($_POST[$theFiles]);
				$_SESSION[$theFiles] = $theUploadFileName;
			}
		}

		foreach ($_SESSION as $theSessionKey => $theSessionValue) {
			if (!is_array($theSessionValue)) {
				$_SESSION[$theSessionKey] = stripslashes($theSessionValue);
			}
		}

		$thisNextPageStep = $_POST['thisStep'] + 1;

		if ((count($pageBreak) - 1) <= $thisNextPageStep) {
			$thisNextPageStep = count($pageBreak) - 1;
		}

		$thisPageStep = isskippage($thisNextPageStep, 1);
	}
}

$theHaveFileUpload = false;
$theHaveFileCascade = false;
$haveBreakFlag = 0;
if (($_GET['questionID'] != '') && !isset($_POST['Action'])) {
	$isHavePageId = false;

	foreach ($pageQtnList as $tmp => $thePageQtnList) {
		foreach ($thePageQtnList as $qtnID) {
			if ($qtnID == $_GET['questionID']) {
				$isHavePageId = true;
				break;
			}
		}

		if ($isHavePageId == true) {
			$thisPageStep = $tmp;
			break;
		}
	}

	$thisBreakAllHidden = '';
	$thisBreakAllFields = '';
	$this_fields_list = '';
	$this_hidden_list = '';

	foreach ($pageQtnList as $tmp => $thePageQtnList) {
		if ($thisPageStep <= $tmp) {
			break;
		}

		foreach ($thePageQtnList as $questionID) {
			if ($QtnListArray[$questionID]['questionType'] != '9') {
				$surveyID = $S_Row['surveyID'];
				$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
				$theQtnArray = $QtnListArray[$questionID];

				if ($QtnListArray[$questionID]['questionType'] != '12') {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
				}
				else {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';
				}
			}
		}
	}

	$thisBreakAllFields .= $this_fields_list;
	$this_fields_list = '';
	$thisBreakAllHidden .= $this_hidden_list;
	$this_hidden_list = '';
	$haveBreakFlag = 1;
}

if ($thisPageStep != 0) {
	$ShowSurveyPageFile = 'ShowSurvey' . $thisPageStep . 'PageFile';
	$ShowSurveyPage = 'ShowSurvey' . $thisPageStep . 'Page';
	$ShowSurveyFile = 'ShowSurvey' . $thisPageStep . 'File';
	$question = 'question' . $thisPageStep;
	$PageRightMenu = '';

	if ($S_Row['status'] != '0') {
		$surveyTplFile = 'uSurvey.html';

		if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
			$surveyTplFile = 'uSurveySystem.html';
		}

		$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, $surveyTplFile);
	}
	else {
		$surveyTplFile = 'uSurveyPre.html';

		if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
			$surveyTplFile = 'uSurveyPreSystem.html';
		}

		$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, $surveyTplFile);
	}

	if ($S_Row['status'] == '0') {
		$EnableQCoreClass->setTemplateFile('PageRightMenuFile', 'PageRightMenu.html');
		$StyleArray = array('Standard', 'Coffee', 'PurplePink', 'BrownBlue', 'RedPink', 'RedGray', 'PurpleYellow', 'BlueBrown', 'BlueGray', 'BlueGrayII', 'BlueGreen', 'GreenPink', 'Spring', 'Summer', 'UserDefine');

		foreach ($StyleArray as $style) {
			if ($S_Row['theme'] == $style) {
				$EnableQCoreClass->replace('theme_' . $style, '¡ñ&nbsp;');
			}
			else {
				$EnableQCoreClass->replace('theme_' . $style, '&nbsp;&nbsp;&nbsp;');
			}
		}

		$EnableQCoreClass->replace('surveyEncTitle', urlencode($S_Row['surveyTitle']));
		$EnableQCoreClass->replace('surveyB64Title', base64_encode($S_Row['surveyTitle']));
		$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
		$PageRightMenu = $EnableQCoreClass->parse('PageRightMenu', 'PageRightMenuFile');
	}

	$EnableQCoreClass->set_CycBlock($ShowSurveyPageFile, 'QUESTION', $question);
	$EnableQCoreClass->replace($question, '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyURLTitle', urlencode($S_Row['surveyTitle']));
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('waitingScript', '');
	$EnableQCoreClass->replace('waitingTime', 0);
	$EnableQCoreClass->replace('limitedScript', '');
	$EnableQCoreClass->replace('limitedTime', 0);
	$EnableQCoreClass->replace('limitedTimeBar', '');

	if ($S_Row['isProcessBar'] == 1) {
		$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
		$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;padding:0;"><div style="width:' . $processValue . '%;color:#FFF;background-color:#FF8D40;height:18px;font-size:12px;text-align:center;overflow:hidden;font-weight:bold;line-height:1.2em">' . $processValue . '%</div></div>';
		$EnableQCoreClass->replace('processBar', $processBar);
	}
	else {
		$EnableQCoreClass->replace('processBar', '');
	}

	$check_survey_form_list = '';
	$check_survey_conditions_list = '';
	$this_fields_list = '';
	$this_file_list = '';
	$this_size_list = '';
	$this_hidden_list = '';
	$this_check_list = '';
	$survey_quota_list = '';
	$survey_empty_list = '';

	foreach ($pageQtnList[$thisPageStep] as $questionID) {
		$EnableQCoreClass->replace('questionID', $questionID);

		if (!empty($CondListArray[$questionID])) {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'none');
		}
		else {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'block');
		}

		$EnableQCoreClass->replace('questionType', $QtnListArray[$questionID]['questionType']);
		$EnableQCoreClass->replace('questionID', $QtnListArray[$questionID]['questionID']);
		$isHiddenFields = true;
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

		if ($QtnListArray[$questionID]['questionType'] == '12') {
			$isHiddenFields = false;
		}

		if ($isHiddenFields == true) {
			$EnableQCoreClass->parse($question, 'QUESTION', true);
		}
	}

	$EnableQCoreClass->replace('thisFields', base64_encode($this_fields_list));
	$EnableQCoreClass->replace('thisHidden', $this_hidden_list);
	$EnableQCoreClass->replace('thisFiles', $this_file_list);
	$EnableQCoreClass->replace('thisSizes', $this_size_list);
	$EnableQCoreClass->replace('thisCheck', $this_check_list);

	if ($haveBreakFlag == 1) {
		$EnableQCoreClass->replace('allHidden', $thisBreakAllHidden . $this_hidden_list);
		$EnableQCoreClass->replace('allFields', base64_encode($thisBreakAllFields . $this_fields_list . $this_file_list));
		$hiddenFields = '';
		$lastPageFieldsList = substr($thisBreakAllFields, 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		$EnableQCoreClass->replace('allHidden', $_POST['thisHidden'] . $this_hidden_list);
		$EnableQCoreClass->replace('allFields', base64_encode(base64_decode($_POST['allFields']) . $this_fields_list . $this_file_list));
		$hiddenFields = '';
		$lastPageFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	if ($_POST['Action'] == 'SurveyPreSubmit') {
		$EnableQCoreClass->replace('allHidden', str_replace($_POST['thisHidden'], '', $_POST['allHidden']));
		$thisAllFields = str_replace(base64_decode($_POST['thisFields']), '', base64_decode($_POST['allFields']));
		$EnableQCoreClass->replace('allFields', base64_encode($thisAllFields));
		$hiddenFields = '';
		$lastPageFieldsList = substr(str_replace($this_fields_list, '', $thisAllFields), 0, -1);
		$lastPageFields = explode('|', $lastPageFieldsList);

		foreach ($lastPageFields as $lastFields) {
			if (in_array($lastFields, $valueLogicQtnList)) {
				if (is_array($_SESSION[$lastFields]) && !empty($_SESSION[$lastFields])) {
					$lastFieldsValue = implode(',', $_SESSION[$lastFields]);
				}
				else {
					$lastFieldsValue = $_SESSION[$lastFields];
				}

				$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
			}
		}

		$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
	}

	$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
	$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
	$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
	$EnableQCoreClass->replace('thisStep', $thisPageStep);

	if ($thisPageStep != count($pageBreak) - 1) {
		if ($S_Row['isPreStep'] == '1') {
			$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">';
		}
		else {
			$actionButton = '';
		}

		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		if ($S_Row['isPreStep'] == '1') {
			$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit"  onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">';
		}
		else {
			$actionButton = '';
		}

		$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\'; Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$ShowSurveyPage = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
	$ShowPage = _gettplfile($S_Row['panelID'], $S_Row['surveyTitle'], $ShowSurveyPage);
	echo $ShowPage . $PageRightMenu;
	exit();
}

if ($thisPageStep == 0) {
	$PageRightMenu = '';

	if ($S_Row['status'] != '0') {
		$surveyTplFile = 'uSurvey.html';

		if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
			$surveyTplFile = 'uSurveySystem.html';
		}

		$EnableQCoreClass->setTemplateFile('ShowSurveyFile', $surveyTplFile);
	}
	else {
		$surveyTplFile = 'uSurveyPre.html';

		if (in_array($S_Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
			$surveyTplFile = 'uSurveyPreSystem.html';
		}

		$EnableQCoreClass->setTemplateFile('ShowSurveyFile', $surveyTplFile);
	}

	if ($S_Row['status'] == '0') {
		$EnableQCoreClass->setTemplateFile('PageRightMenuFile', 'PageRightMenu.html');
		$StyleArray = array('Standard', 'Coffee', 'PurplePink', 'BrownBlue', 'RedPink', 'RedGray', 'PurpleYellow', 'BlueBrown', 'BlueGray', 'BlueGrayII', 'BlueGreen', 'GreenPink', 'Spring', 'Summer', 'UserDefine');

		foreach ($StyleArray as $style) {
			if ($S_Row['theme'] == $style) {
				$EnableQCoreClass->replace('theme_' . $style, '¡ñ&nbsp;');
			}
			else {
				$EnableQCoreClass->replace('theme_' . $style, '&nbsp;&nbsp;&nbsp;');
			}
		}

		$EnableQCoreClass->replace('surveyEncTitle', urlencode($S_Row['surveyTitle']));
		$EnableQCoreClass->replace('surveyB64Title', base64_encode($S_Row['surveyTitle']));
		$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
		$PageRightMenu = $EnableQCoreClass->parse('PageRightMenu', 'PageRightMenuFile');
	}

	$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyURLTitle', urlencode($S_Row['surveyTitle']));
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('waitingScript', '');
	$EnableQCoreClass->replace('waitingTime', 0);
	$EnableQCoreClass->replace('limitedScript', '');
	$EnableQCoreClass->replace('limitedTime', 0);
	$EnableQCoreClass->replace('limitedTimeBar', '');
	if ((1 < count($pageBreak)) && ($S_Row['isProcessBar'] == 1)) {
		$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
		$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;padding:0;"><div style="width:' . $processValue . '%;color:#FFF;background-color:#FF8D40;height:18px;font-size:12px;text-align:center;overflow:hidden;font-weight:bold;line-height:1.2em">' . $processValue . '%</div></div>';
		$EnableQCoreClass->replace('processBar', $processBar);
	}
	else {
		$EnableQCoreClass->replace('processBar', '');
	}

	$check_survey_form_list = '';
	$check_survey_conditions_list = '';
	$this_fields_list = '';
	$this_file_list = '';
	$this_size_list = '';
	$this_hidden_list = '';
	$this_check_list = '';
	$survey_quota_list = '';
	$survey_empty_list = '';

	foreach ($pageQtnList[0] as $questionID) {
		$EnableQCoreClass->replace('questionID', $questionID);

		if (!empty($CondListArray[$questionID])) {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'none');
		}
		else {
			$EnableQCoreClass->replace('isShowQuestion_' . $questionID, 'block');
		}

		$EnableQCoreClass->replace('questionType', $QtnListArray[$questionID]['questionType']);
		$EnableQCoreClass->replace('questionID', $questionID);
		$isHiddenFields = true;
		$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

		if ($QtnListArray[$questionID]['questionType'] == '12') {
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
	$EnableQCoreClass->replace('hiddenFields', '');
	$EnableQCoreClass->replace('thisCheck', $this_check_list);
	$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
	$EnableQCoreClass->replace('passPortType', $BaseRow['isUseOriPassport']);
	$EnableQCoreClass->replace('survey_quota_list', $survey_quota_list);
	$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
	$EnableQCoreClass->replace('thisStep', $thisPageStep);
	if (($S_Row['isViewResult'] == 1) && ($S_Row['isShowResultBut'] == 1)) {
		$actionButton = '<input disabled class=btn type=button value="' . $lang['view_result'] . '" name="ViewResultSubmit" id="ViewResultSubmit" onClick="window.open(\'v.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']) . '&qlang=' . $language . '\',\'\',\'width=600,height=400,resizable=yes,scrollbars=yes\');">';
	}
	else {
		$actionButton = '';
	}

	if (1 < count($pageBreak)) {
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
	}
	else {
		$actionButton .= '<input class=btn type=button value="' . $lang['submit_survey'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\'; Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$ShowSurvey = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
	$ShowPage = _gettplfile($S_Row['panelID'], $S_Row['surveyTitle'], $ShowSurvey);
	echo $ShowPage . $PageRightMenu;
	exit();
}

?>
