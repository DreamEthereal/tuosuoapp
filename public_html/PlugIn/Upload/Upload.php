<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($isMobile == 1) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uUploadAndroid.html');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uUpload.html');
}

$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_11'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('question_Name', qnoscriptstring($questionName));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$EnableQCoreClass->replace('optionID', 'option_' . $questionID);
$EnableQCoreClass->replace('length', $QtnListArray[$questionID]['length']);

if ($isMobile == 1) {
	$EnableQCoreClass->replace('hiddenVarName_1', 'none');
	$EnableQCoreClass->replace('hiddenVarName_2', 'none');
	$EnableQCoreClass->replace('hiddenVarName_3', 'none');
	$theHiddenVarName = explode(',', $QtnListArray[$questionID]['hiddenVarName']);

	foreach ($theHiddenVarName as $thisHiddenVarName) {
		$EnableQCoreClass->replace('hiddenVarName_' . $thisHiddenVarName, '');
	}

	unset($theHiddenVarName);

	if ($theHaveFileUpload == false) {
		if ($language == 'EN') {
			$fileUploadIncludeFile = '<script type="text/javascript" src="JS/ImageCapture.en.php"></script>' . "\n" . '';
		}
		else {
			$fileUploadIncludeFile = '<script type="text/javascript" src="JS/ImageCapture.js.php"></script>' . "\n" . '';
		}

		$EnableQCoreClass->replace('fileUploadIncludeFile', $fileUploadIncludeFile);
		$theHaveFileUpload = true;
	}
	else {
		$EnableQCoreClass->replace('fileUploadIncludeFile', '');
	}
}
else {
	$EnableQCoreClass->replace('allowType', str_replace('.', '*.', str_replace('|', ';', $QtnListArray[$questionID]['allowType'])));
	$EnableQCoreClass->replace('maxSize', $QtnListArray[$questionID]['maxSize']);
	$EnableQCoreClass->replace('session_id', session_id());

	if ($theHaveFileUpload == false) {
		$fileUploadIncludeFile = '<link href="CSS/FileUpload.css" rel="stylesheet" type="text/css"/>' . "\n" . '';
		$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/Swfupload.js.php"></script>' . "\n" . '';
		$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/Swfqueue.js.php"></script>' . "\n" . '';
		$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/Swfobject.js.php"></script>' . "\n" . '';
		$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/FileProgress.js.php"></script>' . "\n" . '';

		if ($language == 'EN') {
			$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/Swfhandlers.en.php"></script>';
		}
		else {
			$fileUploadIncludeFile .= '<script type="text/javascript" src="JS/Swfhandlers.js.php"></script>';
		}

		$EnableQCoreClass->replace('fileUploadIncludeFile', $fileUploadIncludeFile);
		$theHaveFileUpload = true;
	}
	else {
		$EnableQCoreClass->replace('fileUploadIncludeFile', '');
	}
}

$this_file_list .= 'option_' . $questionID . '|';

if ($isModiDataFlag == 1) {
	if ($R_Row['option_' . $questionID] != '') {
		$EnableQCoreClass->replace('value', $R_Row['option_' . $questionID]);
		$this_size_list .= $R_Row['option_' . $questionID] . '###' . $R_Row['joinTime'] . '|';
	}
	else {
		$EnableQCoreClass->replace('value', '');
		$this_size_list .= 'null###' . $R_Row['joinTime'] . '|';
	}

	$EnableQCoreClass->replace('uploadFileTime', $R_Row['joinTime']);
}
else {
	if ($_SESSION['option_' . $questionID] != '') {
		$EnableQCoreClass->replace('value', $_SESSION['option_' . $questionID]);
		$this_size_list .= $_SESSION['option_' . $questionID] . '###' . $_SESSION['joinTime_' . $surveyID] . '|';
	}
	else {
		$EnableQCoreClass->replace('value', '');
		$this_size_list .= 'null###' . $_SESSION['joinTime_' . $surveyID] . '|';
	}

	if (!isset($_SESSION['joinTime_' . $surveyID]) || ($_SESSION['joinTime_' . $surveyID] == '')) {
		$theFileTime = time();
	}
	else {
		$theFileTime = $_SESSION['joinTime_' . $surveyID];
	}

	$EnableQCoreClass->replace('uploadFileTime', $theFileTime);
}

$EnableQCoreClass->replace('theSurveyID', $surveyID);
if (($isModiDataFlag == 1) || ($isAuthDataFlag == 1)) {
	if ($R_Row['option_' . $questionID] == '') {
		$EnableQCoreClass->replace('isHaveUpFile', 'none');
		$EnableQCoreClass->replace('theFilePath', '');
	}
	else {
		$EnableQCoreClass->replace('isHaveUpFile', '');

		if ($S_Row['custDataPath'] == '') {
			$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
		}
		else {
			$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
		}

		$EnableQCoreClass->replace('theFilePath', $filePath . $R_Row['option_' . $questionID]);
	}
}
else {
	$EnableQCoreClass->replace('isHaveUpFile', 'none');
	$EnableQCoreClass->replace('theFilePath', '');
}

$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . ', \'' . qnoscriptstring($QtnListArray[$questionID]['questionName']) . '\')){return false;} ' . "\n" . '';
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

		if ($S_Row['custDataPath'] == '') {
			$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
		}
		else {
			$filePath = $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
		}

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
				$authInfoList .= '跳过]</span>至<span class=red>[';
			}
			else {
				$authInfoList .= '<a href=\'' . $filePath . $aRow['oriValue'] . '\' target=_blank>' . $aRow['oriValue'] . '</a>]</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '') {
				$authInfoList .= '跳过]</span>';
			}
			else {
				$authInfoList .= '<a href=\'' . $filePath . $aRow['updateValue'] . '\' target=_blank>' . $aRow['updateValue'] . '</a>]</span>';
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
