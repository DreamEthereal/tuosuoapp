<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCascade.html');
$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_31'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$cascadeContent = 'Cascade_' . $questionID;
$EnableQCoreClass->replace('cascadeContent', $$cascadeContent);
$EnableQCoreClass->replace('maxLevel', $QtnListArray[$questionID]['maxSize']);
$EnableQCoreClass->replace('rootPath', ROOT_PATH);

if ($language == 'EN') {
	$EnableQCoreClass->replace('headText', 'Pls Select...');
}
else {
	$EnableQCoreClass->replace('headText', '请选择...');
}

if ($theHaveFileCascade == false) {
	$fileCascadeIncludeFile = '<script type="text/javascript" src="JS/LinkSelect.js.php"></script>';
	$EnableQCoreClass->replace('fileCascadeIncludeFile', $fileCascadeIncludeFile);
	$theHaveFileCascade = true;
}
else {
	$EnableQCoreClass->replace('fileCascadeIncludeFile', '');
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$check_survey_form_no_con_list = '';
$remove_value_list = '';
$optionName = '';
$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
$theUrlVarName = explode('#', $QtnListArray[$questionID]['allowType']);
$defVal = array();
$i = 1;

for (; $i <= $QtnListArray[$questionID]['maxSize']; $i++) {
	$tmp = $i - 1;
	$theVarName = 'option_' . $questionID . '_' . $i;
	$this_fields_list .= $theVarName . '|';
	$EnableQCoreClass->replace('optionID', $theVarName);
	$optionName .= '\'#' . $theVarName . '\',';

	if ($QtnListArray[$questionID]['isRequired'] == '1') {
		$check_survey_form_no_con_list .= '	if (!CheckListNoSelect(document.Survey_Form.' . $theVarName . ', \'' . qnoscriptstring($theUnitText[$tmp]) . '\')){return false;} ' . "\n" . '';
	}

	$remove_value_list .= '	ListUnSelect(document.Survey_Form.' . $theVarName . ');' . "\n" . '';

	if ($isModiDataFlag == 1) {
		$defVal[] = (int) $R_Row[$theVarName];
	}
	else if ($_SESSION[$theVarName] != 0) {
		$defVal[] = (int) $_SESSION[$theVarName];
	}
	else if (isset($_GET[$theUrlVarName[$tmp]])) {
		$defVal[] = (int) $_GET[$theUrlVarName[$tmp]];
	}
	else {
		$defVal[] = 0;
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

$EnableQCoreClass->replace('optionName', substr($optionName, 0, -1));
$EnableQCoreClass->replace('defaultValue', implode(',', $defVal));
$QuestionCon = _getquestioncond($questionID, $surveyID);

if ($QuestionCon != '') {
	$check_survey_conditions_list .= '	if(' . $QuestionCon . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';
	$check_form_list = '	if(' . $QuestionCon . ')' . "\n" . '	{' . "\n" . '';
	$check_form_list .= $check_survey_form_no_con_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_form_list .= '	else{' . "\n" . '';
	$check_form_list .= $remove_value_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
}

$EndSurveyCon = _getsurveyquotacond($questionID, $surveyID, strtolower($language));

if ($EndSurveyCon != '') {
	$survey_quota_list .= $EndSurveyCon;
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
if (($isAuthDataFlag == 1) || ($isAuthAppDataFlag == 1)) {
	if ($isAuthDataFlag == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND b.isAppData =0 ORDER BY b.traceTime DESC ';
	}

	if ($isAuthAppDataFlag == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.evidence,b.reason FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND b.isAppData !=0 ORDER BY b.traceTime DESC ';
	}

	$aResult = $DB->query($aSQL);
	$aRecNum = $DB->_getNumRows($aResult);

	if ($aRecNum == 0) {
		$EnableQCoreClass->replace('authList', '');
	}
	else {
		$EnableQCoreClass->setTemplateFile('ShowAuth' . $questionID . 'File', 'uAuthList.html');
		$EnableQCoreClass->set_CycBlock('ShowAuth' . $questionID . 'File', 'AUTHLIST', 'authList' . $questionID);
		$EnableQCoreClass->replace('authList' . $questionID, '');
		$tmp = 0;

		while ($aRow = $DB->queryArray($aResult)) {
			$tmp++;

			if ($aRow['isAppData'] != 1) {
				if ($aRow['isAdmin'] == '4') {
					$modiLang = '修改';
				}
				else {
					$modiLang = '审核';
				}
			}
			else {
				$modiLang = '申诉';
			}

			$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['nickName'], $aRow['userGroupID'], $aRow['groupType']);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['traceTime']) . $modiLang;
			$theVarName = explode('_', $aRow['varName']);
			$tmpl = $theVarName[2] - 1;
			$authInfoList .= '<span class=red>[本题 - ' . $theUnitText[$tmpl] . ']</span>自<span class=red>[';

			switch ($aRow['oriValue']) {
			case '0':
				$authInfoList .= $lang['skip_answer'];
				break;

			default:
				$authInfoList .= $CascadeArray[$questionID][$aRow['oriValue']]['nodeName'];
				break;
			}

			$authInfoList .= ']</span>至<span class=red>[';

			switch ($aRow['updateValue']) {
			case '0':
				$authInfoList .= $lang['skip_answer'];
				break;

			default:
				$authInfoList .= $CascadeArray[$questionID][$aRow['updateValue']]['nodeName'];
				break;
			}

			$authInfoList .= ']</span>';

			if ($aRow['isAppData'] == 1) {
				$authInfoList .= '；理由为：<span class=red>[' . $aRow['reason'] . ']</span>';

				if ($aRow['evidence'] != '') {
					$authInfoList .= '；证据为：<a href=\'' . $evidencePhyPath . $aRow['evidence'] . '\' target=_blank><span class=red>[' . $aRow['evidence'] . ']</span></a>';
				}
			}

			$EnableQCoreClass->replace('authInfoList', $authInfoList);
			$EnableQCoreClass->parse('authList' . $questionID, 'AUTHLIST', true);
		}

		$EnableQCoreClass->replace('authList', $EnableQCoreClass->parse('ShowAuth' . $questionID, 'ShowAuth' . $questionID . 'File'));
	}
}

?>
