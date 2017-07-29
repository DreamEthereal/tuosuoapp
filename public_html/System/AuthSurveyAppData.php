<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.fore.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';

if ($_SESSION['ViewBackURL'] != '') {
	$lastURL = $_SESSION['ViewBackURL'];
}
else {
	$lastURL = $thisProg;
}

$language = 'cn';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$_GET['responseID'] = (int) $_GET['responseID'];
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);
$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE responseID =\'' . $_GET['responseID'] . '\' LIMIT 0,1 ';
$R_Row = $DB->queryFirstRow($theSQL);
$isModiDataFlag = 1;
$isAuthAppDataFlag = 1;

if ($S_Row['custDataPath'] == '') {
	$evidencePhyPath = $Config['dataDirectory'] . '/response_' . $S_Row['surveyID'] . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
}
else {
	$evidencePhyPath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
}

if (($_POST['Action'] == 'SurveyOverSubmit') || ($_POST['Action'] == 'SurveyCacheSubmit')) {
	$_POST['surveyID'] = (int) $_POST['surveyID'];
	$_POST['thisStep'] = (int) $_POST['thisStep'];
	if (!isset($_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']]) || ($_POST['thisStep'] != $_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']])) {
		_showerror($lang['auth_error'], $lang['error_lost_pagenum']);
	}

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
	require ROOT_PATH . 'Process/ModiData.inc.php';
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
			$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
		}

		if (($_POST['Action'] == 'SurveyOverSubmit') && ($_POST['area'] == $_SESSION['administratorsName'])) {
			$SQL .= ' overFlag =1,';
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
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =2,traceTime=\'' . time() . '\' ';
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
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =2,traceTime=\'' . time() . '\' ';
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

	if ($_POST['Action'] == 'SurveyOverSubmit') {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET authStat=1,appStat =\'' . $_POST['authStat'] . '\',version=0,adminID=0 ';
		$SQL .= ' WHERE responseID = \'' . $new_responseID . '\' ';
		$DB->query($SQL);
		$SQL = ' INSERT INTO ' . DATA_TASK_TABLE . ' SET surveyID = \'' . $_POST['surveyID'] . '\',responseID=\'' . $new_responseID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',taskTime=\'' . time() . '\',authStat =1,reason=\'' . $_POST['reason'] . '\',appStat=\'' . $_POST['authStat'] . '\' ';
		$DB->query($SQL);
	}

	unset($survey_fields_name);
	unset($survey_fields_type);
	unset($_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']]);

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
	$_POST['surveyID'] = (int) $_POST['surveyID'];
	$_POST['thisStep'] = (int) $_POST['thisStep'];
	if (!isset($_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']]) || ($_POST['thisStep'] != $_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']])) {
		_showerror($lang['auth_error'], $lang['error_lost_pagenum']);
	}

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
		require ROOT_PATH . 'Process/ModiData.inc.php';
		$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
		$thisSurveyFields = explode('|', $thisSurveyFieldsList);
		if ((base64_decode($_POST['thisFields']) == '') && ($_POST['thisFiles'] == '')) {
		}
		else {
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET ';
			$SQL .= $response_PassportSQL;
			$SQL .= ' responseID =\'' . $_POST['responseID'] . '\',';
			if (($R_Row['replyPage'] < $_POST['thisStep']) && ($R_Row['replyPage'] != 0)) {
				$SQL .= ' replyPage =\'' . $_POST['thisStep'] . '\',';
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
						$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =2,traceTime=\'' . time() . '\' ';
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

		unset($survey_fields_name);
		unset($survey_fields_type);
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
if (($_GET['firstQtnId'] != '') && !isset($_POST['Action'])) {
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
	$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, 'ResultAuthAppData.html');
	$EnableQCoreClass->replace('paperFlagInfo', '');
	$EnableQCoreClass->replace('paperFlagInfoCheck', '');
	$EnableQCoreClass->set_CycBlock($ShowSurveyPageFile, 'QUESTION', $question);
	$EnableQCoreClass->replace($question, '');
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
	$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
	$processBar = '<div id=\'processBar\' style="border:1px solid #e3e3e3;width:200px;margin-top:0;padding:0;margin-left:0px"><div style="width:' . $processValue . '%;color:#FFF;background-color:#FF8D40;height:18px;font-size:12px;text-align:center;overflow:hidden;font-weight:bold;line-height:1.2em">' . $processValue . '%</div></div>';
	$EnableQCoreClass->replace('processBar', $processBar);
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
	$_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']] = $thisPageStep;

	if ($thisPageStep != count($pageBreak) - 1) {
		$actionButton = '<input class=inputsubmit type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=inputsubmit type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '<input class=inputsubmit type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
		$EnableQCoreClass->replace('dataAuthList', '');
		$EnableQCoreClass->replace('data_auth_js_list', '');
	}
	else {
		$actionButton = '<input class=inputsubmit type=button value="' . $lang['survey_pre_page'] . '" name="SurveyPreSubmit" id="SurveyPreSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyPreSubmit\';document.Survey_Form.submit();">&nbsp;';
		$actionButton .= '&nbsp;<input class=inputsubmit type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=button value="' . $lang['submit_survey_over'] . '" name="SurveyOverSubmit" id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();">';
		$dataAuthList = '<table width="100%" class="pertable"><tr><td height=25 class="question" valign=center>&nbsp;<span class=red>*</span>请给出您对本申诉数据的审核意见：&nbsp;</td></tr><tr><td class="tips">需要对本申诉数据给出审核意见</td></tr><tr><td><table cellSpacing=0 cellPadding=0 width="100%"><tr><td class="answer"><input type="radio" value="1" name="authStat" id="authStat">申诉通过<br/><input type="radio" value="2" name="authStat" id="authStat">申诉不通过</td></tr></table></td></tr><tr><td height=5 class="surveyclear">&nbsp;</td></tr></table>';
		$data_auth_js_list = '	if (!CheckRadioNoClick(document.Survey_Form.authStat,\'您对本申诉数据的审核意见\')){return false;} ' . "\n" . '';
		$dataAuthList .= '<table width="100%" class="pertable"><tr><td height=25 class="question" valign=center>&nbsp;<span class=red>*</span>请给出您对本申诉数据的审核批注：&nbsp;</td></tr><tr><td class="tips">需要对本申诉数据给出具体的审核批注</td></tr><tr><td><table cellSpacing=0 cellPadding=0 width="100%"><tr><td class="answer"><textarea rows=6 cols=130 name="reason" id="reason"></textarea></td></tr></table></td></tr><tr><td height=5 class="surveyclear">&nbsp;</td></tr></table>';
		$data_auth_js_list .= '	if (!CheckNotNull(document.Survey_Form.reason, \'您对本申诉数据的审核批注\')) {return false;} ' . "\n" . '';
		$EnableQCoreClass->replace('dataAuthList', $dataAuthList);
		$EnableQCoreClass->replace('data_auth_js_list', $data_auth_js_list);
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);

	if ($S_Row['lang'] == 'en') {
		$SurveyPage = preg_replace('\'<span[^>]*?class=notes>\\[.*?\\].*?\\/span>\'si', '', $SurveyPage);
	}

	echo $SurveyPage;
	$SQL = ' UPDATE ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' SET version = \'' . $_SESSION['administratorsID'] . '\' WHERE responseID =\'' . $R_Row['responseID'] . '\' ';
	$DB->query($SQL);
	exit();
}

if ($thisPageStep == 0) {
	$EnableQCoreClass->setTemplateFile('ShowSurveyFile', 'ResultAuthAppData.html');
	$paperFlagInfo = '';

	if (!$License['isEvalUsers']) {
		$HSQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $S_Row['surveyID'] . '\' LIMIT 1 ';
		$HRow = $DB->queryFirstRow($HSQL);
		if ($HRow || ($S_Row['isRecord'] == 1) || ($S_Row['isRecord'] == 2)) {
			$paperFlagInfo .= '<tr><td height=10></td></tr><tr><td class="surveybegin">&nbsp;<span class=red>*</span>建议开始审核数据前，先备份该回复数据的附属文件(录音、录像、照片等)：<a href="../Export/Export.datafile.inc.php?surveyID=' . $S_Row['surveyID'] . '&surveyName=' . $S_Row['surveyName'] . '&responseID=' . $R_Row['responseID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']) . '"><span class=red>&nbsp;批量下载数据附件</span></a>&nbsp;</td></tr>' . "\n" . '';
		}
	}

	if ($R_Row['taskID'] != '0') {
		$paperFlagInfo .= '<tr><td class="surveybegin">&nbsp;<span class=red>*</span>任务名称：' . $R_Row['administratorsName'] . '</td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;任务描述：' . $R_Row['ipAddress'] . '</td></tr>' . "\n" . '';
	}
	else {
		$paperFlagInfo .= '<tr><td class="surveybegin">&nbsp;<span class=red>*</span>' . $lang['paper_flag'] . '' . $R_Row['ipAddress'] . '</td></tr>' . "\n" . '<tr><td class="surveybegin">&nbsp;&nbsp;&nbsp;' . $lang['paper_panel_flag'] . '' . $R_Row['administratorsName'] . '</td></tr>' . "\n" . '';
	}

	$paperFlagInfo .= '<tr><td height=5 style="border-bottom:1px dashed #cccccc;"></td></tr>' . "\n" . '';
	$EnableQCoreClass->replace('paperFlagInfo', $paperFlagInfo);
	$EnableQCoreClass->replace('paperFlagInfoCheck', '');
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

	if (1 < count($pageBreak)) {
		$processValue = @round((100 / count($pageBreak)) * ($thisPageStep + 1), 0);
		$processBar = '<div id=\'processBar\' style="border:1px solid #e3e3e3;width:200px;margin-top:0;padding:0;margin-left:0px"><div style="width:' . $processValue . '%;color:#FFF;background-color:#FF8D40;height:18px;font-size:12px;text-align:center;overflow:hidden;font-weight:bold;line-height:1.2em">' . $processValue . '%</div></div>';
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
	$_SESSION['thisStep_' . $S_Row['surveyID'] . '_' . $R_Row['responseID']] = $thisPageStep;
	$actionButton = '';

	if (1 < count($pageBreak)) {
		$actionButton .= '<input class=inputsubmit type=button value="' . $lang['cache_survey_over'] . '" name="SurveyCacheSubmit" id="SurveyCacheSubmit" onclick="javascript:if( window.confirm(\'' . $lang['cache_survey_over_info'] . '\')){document.Survey_Form.Action.value = \'SurveyCacheSubmit\';Survey_Form.nextStep.value = \'2\';Survey_Form_Submit();}">&nbsp;';
		$actionButton .= '<input class=inputsubmit type=button value="' . $lang['survey_next_page'] . '" name="SurveyNextSubmit" id="SurveyNextSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyNextSubmit\'; Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
		$EnableQCoreClass->replace('dataAuthList', '');
		$EnableQCoreClass->replace('data_auth_js_list', '');
	}
	else {
		$actionButton .= '<input class=inputsubmit type=button value="&nbsp;&nbsp;' . $lang['modify_survey_submit'] . '&nbsp;&nbsp;" name="SurveyOverSubmit"  id="SurveyOverSubmit" onclick="javascript:document.Survey_Form.Action.value = \'SurveyOverSubmit\';Survey_Form_Submit();">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=reset value="&nbsp;&nbsp;' . $lang['modify_survey_reset'] . '&nbsp;&nbsp;" name="SurveyResetSubmit" id="SurveyResetSubmit">';
		$actionButton .= '&nbsp;<input class=inputsubmit type=button value="&nbsp;&nbsp;' . $lang['modify_survey_cancel'] . '&nbsp;&nbsp;" name="SurveyCancelSubmit" id="SurveyCancelSubmit" onClick="javascript:window.location.href=\'' . $lastURL . '\'">';
		$dataAuthList = '<table width="100%" class="pertable"><tr><td height=25 class="question" valign=center>&nbsp;<span class=red>*</span>请给出您对本申诉数据的审核意见：&nbsp;</td></tr><tr><td class="tips">需要对本申诉数据给出审核意见</td></tr><tr><td><table cellSpacing=0 cellPadding=0 width="100%"><tr><td class="answer"><input type="radio" value="1" name="authStat" id="authStat">申诉通过<br/><input type="radio" value="2" name="authStat" id="authStat">申诉不通过</td></tr></table></td></tr><tr><td height=5 class="surveyclear">&nbsp;</td></tr></table>';
		$data_auth_js_list = '	if (!CheckRadioNoClick(document.Survey_Form.authStat,\'您对本申诉数据的审核意见\')){return false;} ' . "\n" . '';
		$dataAuthList .= '<table width="100%" class="pertable"><tr><td height=25 class="question" valign=center>&nbsp;<span class=red>*</span>请给出您对本申诉数据的审核批注：&nbsp;</td></tr><tr><td class="tips">需要对本申诉数据给出具体的审核批注</td></tr><tr><td><table cellSpacing=0 cellPadding=0 width="100%"><tr><td class="answer"><textarea rows=6 cols=130 name="reason" id="reason"></textarea></td></tr></table></td></tr><tr><td height=5 class="surveyclear">&nbsp;</td></tr></table>';
		$data_auth_js_list .= '	if (!CheckNotNull(document.Survey_Form.reason, \'您对本申诉数据的审核批注\')) {return false;} ' . "\n" . '';
		$EnableQCoreClass->replace('dataAuthList', $dataAuthList);
		$EnableQCoreClass->replace('data_auth_js_list', $data_auth_js_list);
	}

	$EnableQCoreClass->replace('actionButton', $actionButton);
	$SurveyPage = $EnableQCoreClass->parse('ShowSurvey', 'ShowSurveyFile');
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
	$SurveyPage = str_replace($All_Path, '', $SurveyPage);
	$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
	$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
	$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
	$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);

	if ($S_Row['lang'] == 'en') {
		$SurveyPage = preg_replace('\'<span[^>]*?class=notes>\\[.*?\\].*?\\/span>\'si', '', $SurveyPage);
	}

	echo $SurveyPage;
	$SQL = ' UPDATE ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' SET version = \'' . $_SESSION['administratorsID'] . '\' WHERE responseID =\'' . $R_Row['responseID'] . '\' ';
	$DB->query($SQL);
	exit();
}

?>
