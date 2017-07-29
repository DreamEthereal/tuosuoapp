<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';

if ($isMobile) {
	switch ($QtnListArray[$questionID]['isSelect']) {
	case '0':
		$theRatingType = '0';
		break;

	default:
		$theRatingType = '1';
		break;
	}
}
else {
	$theRatingType = $QtnListArray[$questionID]['isSelect'];
}

$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);

switch ($theRatingType) {
case '1':
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRatingOpen.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
	break;

case '0':
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRating.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->set_CycBlock('OPTION', 'SUBRATING', 'subrating' . $questionID);
	$EnableQCoreClass->replace('subrating' . $questionID, '');
	$EnableQCoreClass->replace('option' . $questionID, '');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'RATING', 'rating' . $questionID);
	$EnableQCoreClass->replace('rating' . $questionID, '');
	break;

case '2':
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uRatingSlider.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
	break;
}

$check_survey_form_no_con_list = '';
$questionRequire = '';
$questionName = '';
$questionNotes = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes = '[' . $lang['question_type_15'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$remove_value_list = '';

switch ($theRatingType) {
case '1':
	$optionTotalNum = count($RankListArray[$questionID]);
	$theRankListArray = array();

	if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$theRandListArray = php_array_rand($RankListArray[$questionID], $optionTotalNum);

			foreach ($theRandListArray as $theRandRankID) {
				$theRankListArray[$theRandRankID] = $RankListArray[$questionID][$theRandRankID];
			}
		}
		else {
			$theRandIDArray = array_slice($RankListArray[$questionID], 0, $optionTotalNum - 1);
			$theRandOptionIDArray = array();

			foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
				$theRandOptionIDArray[$theOptionIDArray['question_rankID']] = $theOptionIDArray['question_rankID'];
			}

			$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRankListArray[$theRandRadioID] = $RankListArray[$questionID][$theRandRadioID];
			}

			$theLastArray = array_slice($RankListArray[$questionID], -1, 1);
			$theRankListArray[$theLastArray[0]['question_rankID']] = $theLastArray[0];
		}
	}
	else {
		$theRankListArray = $RankListArray[$questionID];
	}

	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else {
			$optionName = qshowquotechar($theQuestionArray['optionName']) . '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

			if ($isModiDataFlag == 1) {
				$optionName .= $R_Row['TextOtherValue_' . $questionID];
			}
			else {
				$optionName .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
			}

			$optionName .= '"></span>';
			$EnableQCoreClass->replace('optionName', $optionName);
		}

		$remove_value_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
		}

		$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_rankID);
		$EnableQCoreClass->replace('optionNameID', $question_rankID);

		if ($isModiDataFlag == 1) {
			if (($R_Row['option_' . $questionID . '_' . $question_rankID] != '0.00') && ($R_Row['option_' . $questionID . '_' . $question_rankID] != '0')) {
				$EnableQCoreClass->replace('optionValue', $R_Row['option_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '');
			}
		}
		else {
			if (($_SESSION['option_' . $questionID . '_' . $question_rankID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0.00') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0')) {
				$EnableQCoreClass->replace('optionValue', $_SESSION['option_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '');
			}
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_rankID . '|';
		}

		if ($isModiDataFlag == 1) {
			if ($R_Row['TextOtherValue_' . $questionID . '_' . $question_rankID] != '') {
				$EnableQCoreClass->replace('otherValue', $R_Row['TextOtherValue_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('otherValue', '');
			}
		}
		else if ($_SESSION['TextOtherValue_' . $questionID . '_' . $question_rankID] != '') {
			$EnableQCoreClass->replace('otherValue', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID . '_' . $question_rankID]));
		}
		else {
			$EnableQCoreClass->replace('otherValue', '');
		}

		$QtnAssCon = _getqtnasscond($questionID, $question_rankID);
		$theInfoOptName = qnoscriptstring($theQuestionArray['optionName']);

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isCheckType'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isCheckType'] != '1') {
				$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '';
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '';
			}
		}

		$check_survey_form_no_con_list .= '	if (!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
		if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$theMarginValue = $QtnListArray[$questionID]['maxOption'];
			$check_survey_form_no_con_list .= '	if ( Number(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) <= ' . $theMarginValue . ' && Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) != \'\' )' . "\n" . '';
			$check_survey_form_no_con_list .= '	{' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNotNull(document.Survey_Form.' . 'TextOtherValue_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . ' - ' . $QtnListArray[$questionID]['maxOption'] . $lang['under_margin'] . '\')){return false;}' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '	}' . "\n" . '';
			$check_survey_conditions_list .= '	if( Number(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) <= ' . $theMarginValue . ' && Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) != \'\' )' . "\n" . '';
			$check_survey_conditions_list .= '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#TextInput_' . $questionID . '_' . $question_rankID . '\').show();' . "\n" . '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#TextInput_' . $questionID . '_' . $question_rankID . '\').hide();' . "\n" . '	} ' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);

		if ($QtnAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").hide();' . "\n" . '';
			$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
			if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
			}

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}

		$tmp++;
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	if ($isMobile) {
		$EnableQCoreClass->replace('inputPrompt', 'number');
		$check_survey_conditions_list .= '	changeMaskingSingleBgColor(' . $questionID . ');' . "\n" . '';
	}
	else {
		$EnableQCoreClass->replace('inputPrompt', 'text');
	}

	break;

case '0':
	$Answer = array();
	$tmp = 0;

	if ($QtnListArray[$questionID]['isNeg'] == 1) {
		$i = $QtnListArray[$questionID]['startScale'];

		for (; $i <= $QtnListArray[$questionID]['endScale']; $i++) {
			$RatingWeight = $QtnListArray[$questionID]['weight'] * $i;
			$EnableQCoreClass->replace('ratingName', $RatingWeight);
			$Answer[] = $i;
			$tmp++;
			$EnableQCoreClass->parse('rating' . $questionID, 'RATING', true);
		}
	}
	else {
		$i = $QtnListArray[$questionID]['endScale'];

		for (; $QtnListArray[$questionID]['startScale'] <= $i; $i--) {
			$RatingWeight = $QtnListArray[$questionID]['weight'] * $i;
			$EnableQCoreClass->replace('ratingName', $RatingWeight);
			$Answer[] = $i;
			$tmp++;
			$EnableQCoreClass->parse('rating' . $questionID, 'RATING', true);
		}
	}

	$theUnitText = explode('###', $QtnListArray[$questionID]['unitText']);
	$EnableQCoreClass->replace('unitTextLeft', $theUnitText[0]);
	$EnableQCoreClass->replace('unitTextRight', $theUnitText[1]);
	$EnableQCoreClass->replace('colWidth', $tmp * 2);

	if (trim($QtnListArray[$questionID]['unitText']) == '') {
		$EnableQCoreClass->replace('isHaveUnitText', 'none');
	}
	else {
		$EnableQCoreClass->replace('isHaveUnitText', '');
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$EnableQCoreClass->replace('isHaveUnkown', '');
		$Answer[] = 99;
	}
	else {
		$EnableQCoreClass->replace('isHaveUnkown', 'none');
	}

	$optionTotalNum = count($RankListArray[$questionID]);
	$theRankListArray = array();

	if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$theRandListArray = php_array_rand($RankListArray[$questionID], $optionTotalNum);

			foreach ($theRandListArray as $theRandRankID) {
				$theRankListArray[$theRandRankID] = $RankListArray[$questionID][$theRandRankID];
			}
		}
		else {
			$theRandIDArray = array_slice($RankListArray[$questionID], 0, $optionTotalNum - 1);
			$theRandOptionIDArray = array();

			foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
				$theRandOptionIDArray[$theOptionIDArray['question_rankID']] = $theOptionIDArray['question_rankID'];
			}

			$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRankListArray[$theRandRadioID] = $RankListArray[$questionID][$theRandRadioID];
			}

			$theLastArray = array_slice($RankListArray[$questionID], -1, 1);
			$theRankListArray[$theLastArray[0]['question_rankID']] = $theLastArray[0];
		}
	}
	else {
		$theRankListArray = $RankListArray[$questionID];
	}

	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;
	$theOptionIdArray = array();

	foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
		$theOptionIdArray[] = $question_rankID;

		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else {
			$optionName = qshowquotechar($theQuestionArray['optionName']) . '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" type=text value="';

			if ($isModiDataFlag == 1) {
				$optionName .= $R_Row['TextOtherValue_' . $questionID];
			}
			else {
				$optionName .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
			}

			$optionName .= '"></span>';
			$EnableQCoreClass->replace('optionName', $optionName);
		}

		$remove_value_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
		}

		foreach ($Answer as $optionValue) {
			$EnableQCoreClass->replace('optionValue', $optionValue);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_rankID);
			$EnableQCoreClass->replace('optionNameID', $question_rankID);

			if ($isModiDataFlag == 1) {
				if (($R_Row['option_' . $questionID . '_' . $question_rankID] != '0') && ($R_Row['option_' . $questionID . '_' . $question_rankID] == $optionValue)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}
			else {
				if (($_SESSION['option_' . $questionID . '_' . $question_rankID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] == $optionValue)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}

			$EnableQCoreClass->parse('subrating' . $questionID, 'SUBRATING', true);
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $question_rankID . '|';
		}

		if ($isModiDataFlag == 1) {
			if ($R_Row['TextOtherValue_' . $questionID . '_' . $question_rankID] != '') {
				$EnableQCoreClass->replace('otherValue', $R_Row['TextOtherValue_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('otherValue', '');
			}
		}
		else if ($_SESSION['TextOtherValue_' . $questionID . '_' . $question_rankID] != '') {
			$EnableQCoreClass->replace('otherValue', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID . '_' . $question_rankID]));
		}
		else {
			$EnableQCoreClass->replace('otherValue', '');
		}

		$QtnAssCon = _getqtnasscond($questionID, $question_rankID);

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isCheckType'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isCheckType'] != '1') {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= '	if (!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '';
			}
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$theMarginValue = $QtnListArray[$questionID]['maxOption'] / $QtnListArray[$questionID]['weight'];
			$check_survey_form_no_con_list .= '	if ( getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ') <= ' . $theMarginValue . ' && getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ') != 0 )' . "\n" . '';
			$check_survey_form_no_con_list .= '	{' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNotNull(document.Survey_Form.' . 'TextOtherValue_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . ' - ' . $QtnListArray[$questionID]['maxOption'] . $lang['under_margin'] . '\')){return false;}' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '	}' . "\n" . '';
			$check_survey_conditions_list .= '	if( getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ') <= ' . $theMarginValue . ' && getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ') != 0 )' . "\n" . '';
			$check_survey_conditions_list .= '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#TextInput_' . $questionID . '_' . $question_rankID . '\').show();' . "\n" . '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#TextInput_' . $questionID . '_' . $question_rankID . '\').hide();' . "\n" . '	} ' . "\n" . '';
		}

		if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (getRadioCheckBoxValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ') != \'\' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . qnoscriptstring($theQuestionArray['optionName']) . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		$EnableQCoreClass->unreplace('subrating' . $questionID);

		if ($QtnAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").hide();' . "\n" . '';
			$check_survey_conditions_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
			if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
			}

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}

		$tmp++;
	}

	if (($QtnListArray[$questionID]['isContInvalid'] == '1') && ($QtnListArray[$questionID]['contInvalidValue'] != '0')) {
		$check_survey_form_no_con_list .= '	if (!CheckRangeIsContInvalid(' . $questionID . ',' . min($theOptionIdArray) . ',' . max($theOptionIdArray) . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['contInvalidValue'] . ')){return false;} ' . "\n" . '';
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

	unset($Answer);
	$check_survey_conditions_list .= '	changeMaskingRatingBgColor(' . $questionID . ');' . "\n" . '';
	break;

case '2':
	if ($theHaveRatingSlider == false) {
		$ratingIncludeFile = '<script type="text/javascript" src="JS/Slider.dependClass.js.php" charset="utf-8"></script>' . "\n" . '';
		$ratingIncludeFile .= '<script type="text/javascript" src="JS/Slider.js.php" charset="utf-8"></script>' . "\n" . '';
		$ratingIncludeFile .= '<link rel="stylesheet" href="CSS/Slider.css" type="text/css">' . "\n" . '';
		$ratingIncludeFile .= '<style type="text/css" media="screen">.layout-slider { margin-bottom: 50px; width:100%; }</style>' . "\n" . '';
		$EnableQCoreClass->replace('ratingIncludeFile', $ratingIncludeFile);
		$theHaveRatingSlider = true;
	}
	else {
		$EnableQCoreClass->replace('ratingIncludeFile', '');
	}

	$optionTotalNum = count($RankListArray[$questionID]);
	$theRankListArray = array();

	if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$theRandListArray = php_array_rand($RankListArray[$questionID], $optionTotalNum);

			foreach ($theRandListArray as $theRandRankID) {
				$theRankListArray[$theRandRankID] = $RankListArray[$questionID][$theRandRankID];
			}
		}
		else {
			$theRandIDArray = array_slice($RankListArray[$questionID], 0, $optionTotalNum - 1);
			$theRandOptionIDArray = array();

			foreach ($theRandIDArray as $theRandID => $theOptionIDArray) {
				$theRandOptionIDArray[$theOptionIDArray['question_rankID']] = $theOptionIDArray['question_rankID'];
			}

			$theRandListArray = php_array_rand($theRandOptionIDArray, $optionTotalNum - 1);

			foreach ($theRandListArray as $theRandRadioID) {
				$theRankListArray[$theRandRadioID] = $RankListArray[$questionID][$theRandRadioID];
			}

			$theLastArray = array_slice($RankListArray[$questionID], -1, 1);
			$theRankListArray[$theLastArray[0]['question_rankID']] = $theLastArray[0];
		}
	}
	else {
		$theRankListArray = $RankListArray[$questionID];
	}

	$tmp = 0;
	$lastOptionId = $optionTotalNum - 1;

	foreach ($theRankListArray as $question_rankID => $theQuestionArray) {
		if ($QtnListArray[$questionID]['isCheckType'] != '1') {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else if ($tmp != $lastOptionId) {
			$EnableQCoreClass->replace('optionName', qshowquotechar($theQuestionArray['optionName']));
		}
		else {
			$optionName = qshowquotechar($theQuestionArray['optionName']) . '</span>:&nbsp;&nbsp;<span style="vertical-align:middle"><input name=\'TextOtherValue_' . $questionID . '\' id=\'TextOtherValue_' . $questionID . '\' size=20 class="answer" value="';

			if ($isModiDataFlag == 1) {
				$optionName .= $R_Row['TextOtherValue_' . $questionID];
			}
			else {
				$optionName .= qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]);
			}

			$optionName .= '"></span>';
			$EnableQCoreClass->replace('optionName', $optionName);
		}

		$remove_value_list .= '	TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
		$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $question_rankID);
		$EnableQCoreClass->replace('optionNameID', $question_rankID);

		if ($isModiDataFlag == 1) {
			if ($R_Row['option_' . $questionID . '_' . $question_rankID] != '0') {
				$EnableQCoreClass->replace('optionValue', $R_Row['option_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', 0);
			}
		}
		else {
			if (($_SESSION['option_' . $questionID . '_' . $question_rankID] != '') && ($_SESSION['option_' . $questionID . '_' . $question_rankID] != '0')) {
				$EnableQCoreClass->replace('optionValue', $_SESSION['option_' . $questionID . '_' . $question_rankID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', 0);
			}
		}

		$EnableQCoreClass->replace('startScale', $QtnListArray[$questionID]['startScale']);
		$EnableQCoreClass->replace('endScale', $QtnListArray[$questionID]['endScale']);
		$EnableQCoreClass->replace('step', '1');
		$theUnitText = explode('###', $QtnListArray[$questionID]['unitText']);
		$EnableQCoreClass->replace('unitTextLeft', $theUnitText[0]);
		$EnableQCoreClass->replace('unitTextRight', $theUnitText[1]);
		$theStep = number_format(($QtnListArray[$questionID]['endScale'] - $QtnListArray[$questionID]['startScale']) / 5, 0);
		$scale = $QtnListArray[$questionID]['startScale'] . ',\'|\',';
		$temp = 1;

		for (; $temp <= 4; $temp++) {
			$scale .= ($theStep * $temp) . ',\'|\',';
		}

		$scale .= $QtnListArray[$questionID]['endScale'];
		$EnableQCoreClass->replace('scale', $scale);
		$this_fields_list .= 'option_' . $questionID . '_' . $question_rankID . '|';
		$QtnAssCon = _getqtnasscond($questionID, $question_rankID);
		$theInfoOptName = qnoscriptstring($theQuestionArray['optionName']);

		if ($QtnAssCon != '') {
			if ($QtnListArray[$questionID]['isRequired'] == '1') {
				if ($QtnListArray[$questionID]['isCheckType'] != '1') {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '	}' . "\n" . '';
				}
				else if ($tmp != $lastOptionId) {
					$check_survey_form_no_con_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
					$check_survey_form_no_con_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
					$check_survey_form_no_con_list .= '	} ' . "\n" . '';
					$check_survey_form_no_con_list .= '	else { ' . "\n" . '';
					$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '	} ' . "\n" . '';
				}
			}
		}
		else if ($QtnListArray[$questionID]['isRequired'] == '1') {
			if ($QtnListArray[$questionID]['isCheckType'] != '1') {
				$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '';
			}
			else if ($tmp != $lastOptionId) {
				$check_survey_form_no_con_list .= '	if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '';
			}
		}

		$check_survey_form_no_con_list .= '	if (!CheckNumber(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
		if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
			$check_survey_form_no_con_list .= '	if (Trim(document.Survey_Form.TextOtherValue_' . $questionID . '.value) != \'\' )' . "\n" . '	{' . "\n" . '';
			$check_survey_form_no_con_list .= '		if (!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;} ' . "\n" . '';
			$theMinScale = $QtnListArray[$questionID]['startScale'] + 1;
			$check_survey_form_no_con_list .= '		if (!CheckNumber(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . ', \'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\',' . $theMinScale . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	if (Number(document.Survey_Form.' . 'option_' . $questionID . '_' . $question_rankID . '.value) != ' . $QtnListArray[$questionID]['startScale'] . ' )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.TextOtherValue_' . $questionID . ',\'' . $theInfoQtnName . ' - ' . $theInfoOptName . '\')){return false;}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);

		if ($QtnAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $QtnAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").hide();' . "\n" . '';
			$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $question_rankID . ');' . "\n" . '';
			$check_survey_conditions_list .= '		$(\'#option_' . $questionID . '_' . $question_rankID . '\').slider(\'value\', 0 );' . "\n" . '';
			if (($QtnListArray[$questionID]['isCheckType'] == '1') && ($tmp == $lastOptionId)) {
				$check_survey_conditions_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
			}

			$check_survey_conditions_list .= '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#range_rating_' . $question_rankID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}

		$tmp++;
	}

	if ($QtnListArray[$questionID]['isCheckType'] == '1') {
		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
		$remove_value_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
	}

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
	$check_form_list .= $remove_value_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
}

$RelSurveyCon = _getsurveyvaluerelationcond($questionID, $surveyID, strtolower($language));

if ($RelSurveyCon != '') {
	$theRelSurveyCon = explode('$$$$$$', $RelSurveyCon);

	foreach ($theRelSurveyCon as $thisRelSurveyCon) {
		$tRelSurveyCon = explode('######', $thisRelSurveyCon);

		if ($tRelSurveyCon[0] == 2) {
			$survey_empty_list .= $tRelSurveyCon[1];
			$theEmptyList = explode('*', $tRelSurveyCon[1]);
			$theEmptyId = base64_decode($theEmptyList[7]);

			if (!issamepage($theEmptyId, $questionID)) {
				$this_fields_list .= 'option_' . $theEmptyId . '|';
				$theEmptyEndSurveyCon = _getsurveyquotacond($theEmptyId, $surveyID, strtolower($language));

				if ($theEmptyEndSurveyCon != '') {
					$survey_quota_list .= $theEmptyEndSurveyCon;
				}
			}

			unset($theEmptyList);
		}
		else {
			$survey_quota_list .= $tRelSurveyCon[1];
		}

		unset($tRelSurveyCon);
	}

	unset($theRelSurveyCon);
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

			switch ($theVarName[0]) {
			case 'option':
				$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
				$flag = 1;
				break;

			case 'TextOtherValue':
				if (count($theVarName) == 3) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theVarName[2]]['optionName']) . ' - 理由]</span>自<span class=red>[';
				}
				else {
					$optionTotalNum = count($RankListArray[$questionID]);
					$tmp = 0;
					$theTextId = 0;

					foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
						$tmp++;

						if ($tmp != $optionTotalNum) {
							continue;
						}
						else {
							$theTextId = $question_rankID;
						}
					}

					$authInfoList .= '<span class=red>[' . qnospecialchar($RankListArray[$questionID][$theTextId]['optionName']) . ']</span>自<span class=red>[';
				}

				$flag = 2;
				break;
			}

			if ($flag == 1) {
				switch ($QtnListArray[$questionID]['isSelect']) {
				case '0':
					if ($aRow['oriValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99') {
						$authInfoList .= $lang['rating_unknow'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= ($aRow['oriValue'] * $QtnListArray[$questionID]['weight']) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99') {
						$authInfoList .= $lang['rating_unknow'] . ']</span>';
					}
					else {
						$authInfoList .= ($aRow['updateValue'] * $QtnListArray[$questionID]['weight']) . ']</span>';
					}

					break;

				case '1':
					if (($aRow['oriValue'] == '0.00') || ($aRow['oriValue'] == '0')) {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
					}

					if (($aRow['updateValue'] == '0.00') || ($aRow['updateValue'] == '0')) {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else {
						$authInfoList .= $aRow['updateValue'] . ']</span>';
					}

					break;

				case '2':
					if ($aRow['oriValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '0') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else {
						$authInfoList .= $aRow['updateValue'] . ']</span>';
					}

					break;
				}
			}
			else {
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
