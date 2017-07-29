<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uTextArea.html');
$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_5'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$EnableQCoreClass->replace('optionID', 'option_' . $questionID);
$EnableQCoreClass->replace('length', $QtnListArray[$questionID]['length']);
$EnableQCoreClass->replace('rows', $QtnListArray[$questionID]['rows']);

if ($isModiDataFlag == 1) {
	if ($R_Row['option_' . $questionID] != '') {
		$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID]);
	}
	else {
		$EnableQCoreClass->replace('value', '');
	}
}
else if ($_SESSION['option_' . $questionID] != '') {
	$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION['option_' . $questionID]));
}
else {
	$EnableQCoreClass->replace('value', '');
}

$this_fields_list .= 'option_' . $questionID . '|';
$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';
}

switch ($QtnListArray[$questionID]['isCheckType']) {
case '2':
	$check_survey_form_no_con_list .= '	if (!CheckStringLength(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
	break;
}

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
	$check_form_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
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
			$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

			if ($aRow['oriValue'] == '') {
				$authInfoList .= '空]</span>至<span class=red>[';
			}
			else {
				$authInfoList .= qnospecialchar($aRow['oriValue']) . ']</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '') {
				$authInfoList .= '空]</span>';
			}
			else {
				$authInfoList .= qnospecialchar($aRow['updateValue']) . ']</span>';
			}

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
