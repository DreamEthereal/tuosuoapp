<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Entry/Global.entry.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.fore.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$language = 'cn';
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($_SESSION['ViewBackURL'] != '') {
	$lastURL = $_SESSION['ViewBackURL'];
}
else {
	$lastURL = $thisProg;
}

$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE responseID =\'' . $_GET['responseID'] . '\' LIMIT 0,1 ';
$R_Row = $DB->queryFirstRow($theSQL);
$isMobile = 1;
$isModiDataFlag = 1;
if (($_POST['Action'] == 'SurveyOverSubmit') || ($_POST['Action'] == 'SurveyCacheSubmit')) {
	$this_fields_list = '';
	$this_fileds_type = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12')) {
			$surveyID = $_POST['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
		}
	}

	$survey_fields_name = explode('|', substr($this_fields_list, 0, -1));
	$survey_fields_type = explode('|', substr($this_fileds_type, 0, -1));
	require ROOT_PATH . 'Process/AndroidModi.inc.php';
	require ROOT_PATH . 'Process/File.inc.php';
	$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisSurveyFields = explode('|', $thisSurveyFieldsList);
	if ((base64_decode($_POST['thisFields']) == '') && ($_POST['thisFiles'] == '')) {
	}
	else {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
		$SQL .= $response_PassportSQL;
		$SQL .= ' responseID =\'' . $_POST['responseID'] . '\',';
		if (($R_Row['replyPage'] < $_POST['thisStep']) && ($R_Row['replyPage'] != 0)) {
			$submitTime = time();
			$overTime = $submitTime - $R_Row['joinTime'];
			$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',submitTime=\'' . $submitTime . '\',overTime=\'' . $overTime . '\',';
		}

		if (base64_decode($_POST['thisFields']) != '') {
			foreach ($thisSurveyFields as $surveyFields) {
				if (is_array($_POST[$surveyFields])) {
					asort($_POST[$surveyFields]);
					$qtnUpdateValue = implode(',', qhtmlspecialchars($_POST[$surveyFields]));
					$SQL .= ' ' . $surveyFields . ' = \'' . $qtnUpdateValue . '\',';
				}
				else {
					$qtnUpdateValue = qhtmlspecialchars($_POST[$surveyFields]);
					$SQL .= ' ' . $surveyFields . ' = \'' . $qtnUpdateValue . '\',';
				}

				if ($_POST[$surveyFields] == '') {
					switch ($survey_fields_type[array_search($surveyFields, $survey_fields_name)]) {
					case 'int':
						$qtnUpdateValue = '0';
						break;

					case 'float':
						$qtnUpdateValue = '0.00';
						break;

					default:
						$qtnUpdateValue = '';
						break;
					}
				}

				if ($qtnUpdateValue != taddslashes($R_Row[$surveyFields])) {
					$theFieldsArray = explode('_', $surveyFields);
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =0,traceTime=\'' . time() . '\' ';
					$DB->query($uSQL);
					unset($theFieldsArray);
				}
			}
		}

		$InsertSQL = substr($SQL, 0, -1);
		$InsertSQL .= $File_SQL;
		$InsertSQL .= ' WHERE responseID =\'' . $_POST['responseID'] . '\' ';
		$DB->query($InsertSQL);
	}

	$new_responseID = $_POST['responseID'];
	if (($_POST['Action'] == 'SurveyOverSubmit') && ($_POST['area'] == $_SESSION['administratorsName'])) {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET overFlag =1 ';
		if (($R_Row['authStat'] == 2) && ($R_Row['isReAuth'] == 1)) {
			$SQL .= ',authStat=0 ';
			$uSQL = ' INSERT INTO ' . DATA_TASK_TABLE . ' SET surveyID = \'' . $_POST['surveyID'] . '\',responseID=\'' . $_POST['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',taskTime=\'' . time() . '\',authStat=\'0\',reason=\'数据修改后重新提交审核\' ';
			$DB->query($uSQL);
		}

		$SQL .= ' WHERE responseID =\'' . $_POST['responseID'] . '\' ';
		$DB->query($SQL);
	}

	if (($_POST['Action'] == 'SurveyOverSubmit') || ($_POST['surveyQuotaFlag'] == 2)) {
		$thisSurveyFieldsList = substr(base64_decode($_POST['allFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);
		$this_diff_fields_list = arraydiff($survey_fields_name, $thisSurveyFields);
		if (!empty($this_diff_fields_list) && (count($this_diff_fields_list) != 0)) {
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';

			foreach ($this_diff_fields_list as $surveyFields) {
				$SQL .= ' ' . $surveyFields . ' = \'\',';

				switch ($survey_fields_type[array_search($surveyFields, $survey_fields_name)]) {
				case 'int':
					$qtnUpdateValue = '0';
					break;

				case 'float':
					$qtnUpdateValue = '0.00';
					break;

				default:
					$qtnUpdateValue = '';
					break;
				}

				if (taddslashes($R_Row[$surveyFields]) != $qtnUpdateValue) {
					$theFieldsArray = explode('_', $surveyFields);
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =0,traceTime=\'' . time() . '\' ';
					$DB->query($uSQL);
					unset($theFieldsArray);
				}
			}

			$updateSQL = substr($SQL, 0, -1);
			$updateSQL .= ' WHERE responseID =\'' . $_POST['responseID'] . '\' ';
			$DB->query($updateSQL);
		}

		unset($this_diff_fields_list);
	}

	if ($_POST['surveyQuotaFlag'] == 2) {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET overFlag = 2,overFlag0 = \'' . $R_Row['overFlag'] . '\' WHERE responseID = \'' . $new_responseID . '\' ';
		$DB->query($SQL);
	}

	if (($_POST['overFlag'] == 0) && ($_POST['area'] == $_SESSION['administratorsName']) && ($_POST['surveyQuotaFlag'] != 2)) {
		dealcountinfo($_POST['surveyID'], $R_Row['joinTime']);
	}

	if (($_POST['overFlag'] == 1) && ($_POST['surveyQuotaFlag'] == 2)) {
		delcountinfo($_POST['surveyID'], $_POST['createDate']);
	}

	unset($survey_fields_name);
	unset($survey_fields_type);

	if ($_POST['surveyQuotaFlag'] == 2) {
		if ($QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'] == '') {
			_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $lang['num_to_quota'], 3, $lastURL);
		}
		else {
			_showerror($lang['result_to_quota'], $S_Row['surveyTitle'] . ': ' . $QuotaNumArray[$_POST['surveyQuotaId']]['quotaText'], 3, $lastURL);
		}
	}

	_showsucceed($lang['survey_submit'], $lastURL);
}

$EnableQCoreClass->replace('lastURL', $lastURL);
$thePageSurveyID = $S_Row['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = 0;
$surveyID = $S_Row['surveyID'];
$theActionPage = 1;
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
$theMgtFunc = 0;
$theResponseID = $_GET['responseID'];
if (($_POST['Action'] == 'SurveyPreSubmit') || ($_POST['Action'] == 'SurveyNextSubmit')) {
	if ($_POST['Action'] == 'SurveyPreSubmit') {
		$thisLastPageStep = $_POST['thisStep'] - 1;

		if ($thisLastPageStep <= 0) {
			$thisLastPageStep = 0;
		}

		$thisPageStep = isskippage($thisLastPageStep, 2);
	}

	if ($_POST['Action'] == 'SurveyNextSubmit') {
		$this_fields_list = '';
		$this_fileds_type = '';

		foreach ($QtnListArray as $questionID => $theQtnArray) {
			if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12')) {
				$surveyID = $_POST['surveyID'];
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
			}
		}

		$survey_fields_name = explode('|', substr($this_fields_list, 0, -1));
		$survey_fields_type = explode('|', substr($this_fileds_type, 0, -1));
		require ROOT_PATH . 'Process/File.inc.php';
		require ROOT_PATH . 'Process/AndroidModi.inc.php';
		$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);
		if ((base64_decode($_POST['thisFields']) == '') && ($_POST['thisFiles'] == '')) {
		}
		else {
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
			$SQL .= $response_PassportSQL;
			$SQL .= ' responseID =\'' . $_POST['responseID'] . '\',';
			if (($R_Row['replyPage'] < $_POST['thisStep']) && ($R_Row['replyPage'] != 0)) {
				$submitTime = time();
				$overTime = $submitTime - $R_Row['joinTime'];
				$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',submitTime=\'' . $submitTime . '\',overTime=\'' . $overTime . '\',';
			}

			if (base64_decode($_POST['thisFields']) != '') {
				foreach ($thisSurveyFields as $surveyFields) {
					if (is_array($_POST[$surveyFields])) {
						asort($_POST[$surveyFields]);
						$qtnUpdateValue = implode(',', qhtmlspecialchars($_POST[$surveyFields]));
						$SQL .= ' ' . $surveyFields . ' = \'' . $qtnUpdateValue . '\',';
					}
					else {
						$qtnUpdateValue = qhtmlspecialchars($_POST[$surveyFields]);
						$SQL .= ' ' . $surveyFields . ' = \'' . $qtnUpdateValue . '\',';
					}

					if ($_POST[$surveyFields] == '') {
						switch ($survey_fields_type[array_search($surveyFields, $survey_fields_name)]) {
						case 'int':
							$qtnUpdateValue = '0';
							break;

						case 'float':
							$qtnUpdateValue = '0.00';
							break;

						default:
							$qtnUpdateValue = '';
							break;
						}
					}

					if ($qtnUpdateValue != taddslashes($R_Row[$surveyFields])) {
						$theFieldsArray = explode('_', $surveyFields);
						$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =0,traceTime=\'' . time() . '\' ';
						$DB->query($uSQL);
						unset($theFieldsArray);
					}
				}
			}

			$InsertSQL = substr($SQL, 0, -1);
			$InsertSQL .= $File_SQL;
			$InsertSQL .= ' WHERE responseID =\'' . $_POST['responseID'] . '\' ';
			$DB->query($InsertSQL);
		}

		$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE responseID =\'' . $_POST['responseID'] . '\' LIMIT 0,1 ';
		$R_Row = $DB->queryFirstRow($theSQL);
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
if ((($_GET['firstQtnId'] != '') || (($R_Row['replyPage'] != 0) && ($R_Row['overFlag'] == 0))) && !isset($_POST['Action'])) {
	if ($_GET['firstQtnId'] != '') {
		$isHavePageId = false;

		foreach ($pageQtnList as $tmp => $thePageQtnList) {
			foreach ($thePageQtnList as $qtnID) {
				if ($qtnID == $_GET['firstQtnId']) {
					$isHavePageId = true;
					break;
				}
			}

			if ($isHavePageId == true) {
				$thisPageStep = $tmp;
				break;
			}
		}
	}
	else {
		$thisPageStep = $R_Row['replyPage'];
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
	$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, 'uAndroidDataModi.html');
	$EnableQCoreClass->set_CycBlock($ShowSurveyPageFile, 'QUESTION', $question);
	$EnableQCoreClass->replace($question, '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('surveySubTitle', '');
	$EnableQCoreClass->replace('surveyInfo', '');
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
	$EnableQCoreClass->replace('overFlag', $R_Row['overFlag']);
	$EnableQCoreClass->replace('area', $R_Row['area']);
	$EnableQCoreClass->replace('createDate', $R_Row['joinTime']);
	$EnableQCoreClass->replace('paperFlagInfo', '');
	$EnableQCoreClass->replace('paperFlagInfoCheck', '');
	$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
	$processBarValue = $processValue * 2;
	$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;margin-bottom:5px;padding:0;height:19px"><span style="width:' . $processBarValue . 'px;color:#FFF;float:left;background-color:#FF8D40;height:19px;font-size:16px;text-align:center;overflow:hidden;font-weight:bold;line-height:19px">' . $processValue . '%</span><span style="width:*;float:right;"></span></div>';
	$EnableQCoreClass->replace('processBar', $processBar);
	$EnableQCoreClass->replace('processValue', $processValue . '%');
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
				if (is_array($R_Row[$lastFields]) && !empty($R_Row[$lastFields])) {
					$lastFieldsValue = implode(',', $R_Row[$lastFields]);
				}
				else {
					$lastFieldsValue = $R_Row[$lastFields];
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
				if (is_array($R_Row[$lastFields]) && !empty($R_Row[$lastFields])) {
					$lastFieldsValue = implode(',', $R_Row[$lastFields]);
				}
				else {
					$lastFieldsValue = $R_Row[$lastFields];
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
				if (is_array($R_Row[$lastFields]) && !empty($R_Row[$lastFields])) {
					$lastFieldsValue = implode(',', $R_Row[$lastFields]);
				}
				else {
					$lastFieldsValue = $R_Row[$lastFields];
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
		$actionButton = '<input class=btn type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=btn type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=btn type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
	}
	else {
		$actionButton = '<input class=btn type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '&nbsp;<input class=btn type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=btn type=button value="' . $lang['submit_survey_over'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('Android/', '../Android/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);
	$SurveyPage = str_replace('按住Ctrl键点击鼠标进行多重选择', '多重选择', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#ffffff"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#f5f5f5"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#ffffff"', '<input type="checkbox"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#f5f5f5"', '<input type="checkbox"', $SurveyPage);
	echo $SurveyPage;
	exit();
}

if ($thisPageStep == 0) {
	$theDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $_GET['responseID'], $R_Row, $S_Row));
	$haveEditDataAuth = $theDataAuthArray[1];

	if ($haveEditDataAuth != 1) {
		_showerror($lang['auth_error'], $lang['passport_is_permit'] . ':' . $lang['no_auth_edit_data']);
	}

	$EnableQCoreClass->setTemplateFile('ShowSurveyFile', 'uAndroidDataModi.html');
	$EnableQCoreClass->set_CycBlock('ShowSurveyFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
	$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
	$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
	$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
	$EnableQCoreClass->replace('surveyLang', $language);
	$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
	$EnableQCoreClass->replace('overFlag', $R_Row['overFlag']);
	$EnableQCoreClass->replace('area', $R_Row['area']);
	$EnableQCoreClass->replace('createDate', $R_Row['joinTime']);

	if ($S_Row['isPanelFlag'] == 1) {
		if ($R_Row['taskID'] != '0') {
			$paperFlagInfo = '<tr><td height=5></td></tr><tr><td class="surveybegin">&nbsp;<span class=red>*</span>任务名称：<input name="biaoshi" id="biaoshi" type="text" value="' . $R_Row['ipAddress'] . '" size=40 readonly>&nbsp;&nbsp;</td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;任务描述：<input name="name" id="name" type="text" size=40 value="' . $R_Row['administratorsName'] . '" readonly></td></tr>' . "\n" . '<tr><td height=5 style="border-bottom:1px dashed #cccccc;"></td></tr>' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfo', $paperFlagInfo);
			$paperFlagInfoCheck = 'if (!CheckNotNull(document.Survey_Form.biaoshi, \'' . $lang['and_flag_check'] . '\')){return false;}' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfoCheck', $paperFlagInfoCheck);
		}
		else {
			$paperFlagInfo = '<tr><td height=5></td></tr><tr><td class="surveybegin">&nbsp;<span class=red>*</span>' . $lang['and_flag'] . '<input name="biaoshi" id="biaoshi" maxlength=15 onkeypress="checkIsAlphabetValid();" style="ime-mode:disabled" onPaste="return false;" type="text" value="' . $R_Row['ipAddress'] . '" size=30>&nbsp;&nbsp;</td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;' . $lang['and_panel_flag'] . '<input name="name" id="name" type="text" size=30 value="' . $R_Row['administratorsName'] . '"></td></tr>' . "\n" . '<tr><td height=5 style="border-bottom:1px dashed #cccccc;"></td></tr>' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfo', $paperFlagInfo);
			$paperFlagInfoCheck = 'if (!CheckNotNull(document.Survey_Form.biaoshi, \'' . $lang['and_flag_check'] . '\')){return false;}' . "\n" . '';
			$EnableQCoreClass->replace('paperFlagInfoCheck', $paperFlagInfoCheck);
		}
	}
	else {
		$EnableQCoreClass->replace('paperFlagInfo', '');
		$EnableQCoreClass->replace('paperFlagInfoCheck', '');
	}

	if (1 < count($pageBreak)) {
		$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
		$processBarValue = $processValue * 2;
		$processBar = '<div id=\'processBar\' style="border:1 solid #e3e3e3;width:200px;margin-top:0;margin-bottom:5px;padding:0;height:19px"><span style="width:' . $processBarValue . 'px;color:#FFF;float:left;background-color:#FF8D40;height:19px;font-size:16px;text-align:center;overflow:hidden;font-weight:bold;line-height:19px">' . $processValue . '%</span><span style="width:*;float:right;"></span></div>';
		$EnableQCoreClass->replace('processBar', $processBar);
		$EnableQCoreClass->replace('processValue', $processValue . '%');
	}
	else {
		$EnableQCoreClass->replace('processBar', '');
		$EnableQCoreClass->replace('processValue', '');
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
	$actionButton = '';

	if (1 < count($pageBreak)) {
		$actionButton .= '<input class=btn type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=btn type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=btn type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=btn type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
	}
	else {
		$actionButton .= '<input class=btn type=button value="&nbsp;&nbsp;' . $lang['modify_survey_submit'] . '&nbsp;&nbsp;" name="SurveyOverSubmit"  id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=btn type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=btn type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Android/', '../Android/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);
	$SurveyPage = str_replace('按住Ctrl键点击鼠标进行多重选择', '多重选择', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#ffffff"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="radio" style="background-color:#f5f5f5"', '<input type="radio"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#ffffff"', '<input type="checkbox"', $SurveyPage);
	$SurveyPage = str_replace('<input type="checkbox" style="background-color:#f5f5f5"', '<input type="checkbox"', $SurveyPage);
	echo $SurveyPage;
	exit();
}

?>
