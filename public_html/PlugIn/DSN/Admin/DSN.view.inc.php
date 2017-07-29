<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'RadioDetail.html');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_13'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($R_Row['option_' . $questionID] == '') {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$EnableQCoreClass->replace('optionName', $lang['skip_answer']);
	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('textOtherValue', '');
}
else {
	$EnableQCoreClass->replace('isHaveOther', 'none');
	$Conn = odbc_connect(trim($theQtnArray['DSNConnect']), trim($theQtnArray['DSNUser']), trim($theQtnArray['DSNPassword']));

	if (!$Conn) {
		_showerror('System Error', 'Connection Failed:' . trim($theQtnArray['DSNConnect']) . '-' . trim($theQtnArray['DSNUser']) . '-' . trim($theQtnArray['DSNPassword']));
	}

	$ODBC_Result = odbc_exec($Conn, _getsql($theQtnArray['DSNSQL']));

	if (!$ODBC_Result) {
		_showerror('System Error', 'Error in SQL:' . trim($theQtnArray['DSNSQL']));
	}

	$isOver = false;

	while (odbc_fetch_row($ODBC_Result)) {
		$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
		$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
		if (($ItemValue == $R_Row['option_' . $questionID]) && ($isOver == false)) {
			$EnableQCoreClass->replace('optionName', $ItemDisplay);
			$isOver = true;
		}
	}

	$EnableQCoreClass->replace('otherText', '');
	$EnableQCoreClass->replace('textOtherValue', '');
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$haveCondRel = getqtnconsucc($questionID);
if (($haveCondRel != '') && !runcode($haveCondRel)) {
	$check_survey_conditions_list .= '	$("#question_' . $questionID . '").hide();' . "\n" . '';
}

if (($isViewAuthInfo == 1) || ($isAppSurveyData == 1)) {
	if ($isViewAuthInfo == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' ORDER BY b.traceTime DESC ';
	}

	if ($isAppSurveyData == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.evidence,b.reason FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND isAppData =1 ORDER BY b.traceTime DESC ';
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

			switch ($aRow['oriValue']) {
			case '':
				$authInfoList .= $lang['skip_answer'];
				break;

			default:
				$isOver = false;
				$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$questionID]['DSNSQL']));

				while (odbc_fetch_row($ODBC_Result)) {
					$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
					$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
					if (($ItemValue == $aRow['oriValue']) && ($isOver == false)) {
						$authInfoList .= qnospecialchar($ItemDisplay);
						$isOver = true;
					}
				}

				break;
			}

			$authInfoList .= ']</span>至<span class=red>[';

			switch ($aRow['updateValue']) {
			case '':
				$authInfoList .= $lang['skip_answer'];
				break;

			default:
				$isOver = false;
				$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$questionID]['DSNSQL']));

				while (odbc_fetch_row($ODBC_Result)) {
					$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
					$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');
					if (($ItemValue == $aRow['updateValue']) && ($isOver == false)) {
						$authInfoList .= qnospecialchar($ItemDisplay);
						$isOver = true;
					}
				}

				break;
			}

			$authInfoList .= ']</span>';

			if ($isAppSurveyData == 1) {
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
else {
	$EnableQCoreClass->replace('authList', '');
}

odbc_close($Conn);

?>
