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
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoRatingOpen.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');
	break;

case '0':
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoRating.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->set_CycBlock('OPTION', 'SUBRATING', 'subrating' . $questionID);
	$EnableQCoreClass->replace('subrating' . $questionID, '');
	$EnableQCoreClass->replace('option' . $questionID, '');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'RATING', 'rating' . $questionID);
	$EnableQCoreClass->replace('rating' . $questionID, '');
	break;

case '2':
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uAutoRatingSlider.html');
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
	$questionNotes = '[' . $lang['question_type_21'] . ']';
}

$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$remove_value_list = '';
$the_check_survey_conditions_list = '';
$theBaseID = $QtnListArray[$questionID]['baseID'];
$theBaseQtnArray = $QtnListArray[$theBaseID];
$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
if (empty($theBaseQtnArray) && ($QtnListArray[$questionID]['isRequired'] == '1')) {
	$check_survey_form_no_con_list .= '	$.notification(\'' . $theInfoQtnName . $lang['base_qtn_no_exist'] . '\');return false;' . "\n" . '';
}

switch ($theRatingType) {
case '1':
	$optionAutoArray = array();
	$optionMinMaxArray = array();
	$i = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if ($theBaseQtnArray['questionType'] == 3) {
			$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
		}

		if ($theBaseQtnArray['questionType'] == 25) {
			if (!issamepage($questionID, $theBaseID) && ($theQuestionArray['isHaveText'] == 1)) {
				if ($QtnListArray[$questionID]['isNeg'] == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
				}
				else if ($isModiDataFlag == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . $R_Row['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID] . ')';
				}
				else {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID]) . ')';
				}
			}
			else {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
			}
		}

		$optionMinMaxArray[$i] = $question_checkboxID;
		$i++;
	}

	if ($theBaseQtnArray['isHaveOther'] == 1) {
		if (!issamepage($questionID, $theBaseID)) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
			}
			else if ($isModiDataFlag == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . $R_Row['TextOtherValue_' . $theBaseID] . ')';
			}
			else {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID]) . ')';
			}
		}
		else {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
		}
	}

	$isHaveOther = ($theBaseQtnArray['isHaveOther'] != '' ? $theBaseQtnArray['isHaveOther'] : 0);
	$EnableQCoreClass->replace('baseID', $theBaseID);
	$theOptionAutoIDList = '';

	foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
		$theOptionAutoIDList .= $optionAutoID . ',';
		$EnableQCoreClass->replace('optionName', $optionAutoName);
		$theInfoAutoName = qnoscriptstring($optionAutoName);
		$remove_value_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
		$remove_value_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$remove_value_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';
		}

		$remove_value_list .= '	}' . "\n" . '';
		$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $optionAutoID);
		$EnableQCoreClass->replace('optionNameID', $optionAutoID);

		if ($isModiDataFlag == 1) {
			if (($R_Row['option_' . $questionID . '_' . $optionAutoID] != '') && ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0') && ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0.00')) {
				$EnableQCoreClass->replace('optionValue', $R_Row['option_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '');
			}
		}
		else {
			if (($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0.00')) {
				$EnableQCoreClass->replace('optionValue', $_SESSION['option_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '');
			}
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $optionAutoID . '|';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $optionAutoID . '|';
		}

		if ($isModiDataFlag == 1) {
			if ($R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID] != '') {
				$EnableQCoreClass->replace('otherValue', $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('otherValue', '');
			}
		}
		else if ($_SESSION['TextOtherValue_' . $questionID . '_' . $optionAutoID] != '') {
			$EnableQCoreClass->replace('otherValue', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID . '_' . $optionAutoID]));
		}
		else {
			$EnableQCoreClass->replace('otherValue', '');
		}

		if ($QtnListArray[$questionID]['isRequired'] == '1') {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
		}
		else {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckIsValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$theMarginValue = $QtnListArray[$questionID]['maxOption'];
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if ( Number(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . '.value) <= ' . $theMarginValue . ' && Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . '.value) != \'\' )' . "\n" . '';
			$check_survey_form_no_con_list .= '		{' . "\n" . '';
			$check_survey_form_no_con_list .= '			if(!CheckNotNull(document.Survey_Form.' . 'TextOtherValue_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($optionAutoName) . ' - ' . $QtnListArray[$questionID]['maxOption'] . $lang['under_margin'] . '\')){return false;}' . "\n" . '		}' . "\n" . '';
			$check_survey_form_no_con_list .= '		else' . "\n" . '		{' . "\n" . '			TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '		}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$the_check_survey_conditions_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$the_check_survey_conditions_list .= '		if( Number(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . '.value) <= ' . $theMarginValue . ' && Trim(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . '.value) != \'\' )' . "\n" . '';
			$the_check_survey_conditions_list .= '		{' . "\n" . '';
			$the_check_survey_conditions_list .= '			$(\'#TextInput_' . $questionID . '_' . $optionAutoID . '\').show();' . "\n" . '		} ' . "\n" . '';
			$the_check_survey_conditions_list .= '		else { ' . "\n" . '';
			$the_check_survey_conditions_list .= '			$(\'#TextInput_' . $questionID . '_' . $optionAutoID . '\').hide();' . "\n" . '		} ' . "\n" . '';
			$the_check_survey_conditions_list .= '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	break;

case '0':
	$Answer = array();
	$tmp = 0;

	if ($QtnListArray[$questionID]['isColArrange'] == 1) {
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

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$EnableQCoreClass->replace('isHaveUnkown', '');
		$Answer[] = 99;
	}
	else {
		$EnableQCoreClass->replace('isHaveUnkown', 'none');
	}

	$optionAutoArray = array();
	$optionMinMaxArray = array();
	$i = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if ($theBaseQtnArray['questionType'] == 3) {
			$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
		}

		if ($theBaseQtnArray['questionType'] == 25) {
			if (!issamepage($questionID, $theBaseID) && ($theQuestionArray['isHaveText'] == 1)) {
				if ($QtnListArray[$questionID]['isNeg'] == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
				}
				else if ($isModiDataFlag == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . $R_Row['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID] . ')';
				}
				else {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID]) . ')';
				}
			}
			else {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
			}
		}

		$optionMinMaxArray[$i] = $question_checkboxID;
		$i++;
	}

	$theFirstID = ($optionMinMaxArray[0] != '' ? $optionMinMaxArray[0] : 0);
	$theLastID = ($optionMinMaxArray[count($optionMinMaxArray) - 1] != '' ? $optionMinMaxArray[count($optionMinMaxArray) - 1] : 0);

	if ($theBaseQtnArray['isHaveOther'] == 1) {
		if (!issamepage($questionID, $theBaseID)) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
			}
			else if ($isModiDataFlag == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . $R_Row['TextOtherValue_' . $theBaseID] . ')';
			}
			else {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID]) . ')';
			}
		}
		else {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
		}
	}

	$isHaveOther = ($theBaseQtnArray['isHaveOther'] != '' ? $theBaseQtnArray['isHaveOther'] : 0);
	$EnableQCoreClass->replace('baseID', $theBaseID);
	$theOptionAutoIDList = '';

	foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
		$theOptionAutoIDList .= $optionAutoID . ',';
		$EnableQCoreClass->replace('optionName', $optionAutoName);
		$theInfoAutoName = qnoscriptstring($optionAutoName);
		$remove_value_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
		$remove_value_list .= '		RadioUnClick(document.Survey_Form.option_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$remove_value_list .= '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';
		}

		$remove_value_list .= '	}' . "\n" . '';

		foreach ($Answer as $optionValue) {
			$EnableQCoreClass->replace('optionValue', $optionValue);
			$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $optionAutoID);
			$EnableQCoreClass->replace('optionNameID', $optionAutoID);

			if ($isModiDataFlag == 1) {
				if (($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0') && ($R_Row['option_' . $questionID . '_' . $optionAutoID] == $optionValue)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}
			else {
				if (($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] == $optionValue)) {
					$EnableQCoreClass->replace('isCheck', 'checked');
				}
				else {
					$EnableQCoreClass->replace('isCheck', '');
				}
			}

			$EnableQCoreClass->parse('subrating' . $questionID, 'SUBRATING', true);
		}

		$this_fields_list .= 'option_' . $questionID . '_' . $optionAutoID . '|';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$this_fields_list .= 'TextOtherValue_' . $questionID . '_' . $optionAutoID . '|';
		}

		if ($isModiDataFlag == 1) {
			if ($R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID] != '') {
				$EnableQCoreClass->replace('otherValue', $R_Row['TextOtherValue_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('otherValue', '');
			}
		}
		else if ($_SESSION['TextOtherValue_' . $questionID . '_' . $optionAutoID] != '') {
			$EnableQCoreClass->replace('otherValue', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID . '_' . $optionAutoID]));
		}
		else {
			$EnableQCoreClass->replace('otherValue', '');
		}

		if ($QtnListArray[$questionID]['isRequired'] == '1') {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckRadioNoClick(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$theMarginValue = $QtnListArray[$questionID]['maxOption'] / $QtnListArray[$questionID]['weight'];
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if ( getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ') <= ' . $theMarginValue . ' && getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ') != 0 )' . "\n" . '';
			$check_survey_form_no_con_list .= '		{' . "\n" . '';
			$check_survey_form_no_con_list .= '			if(!CheckNotNull(document.Survey_Form.' . 'TextOtherValue_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($optionAutoName) . ' - ' . $QtnListArray[$questionID]['maxOption'] . $lang['under_margin'] . '\')){return false;}' . "\n" . '		}' . "\n" . '';
			$check_survey_form_no_con_list .= '		else' . "\n" . '		{' . "\n" . '			TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '		}' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
			$the_check_survey_conditions_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$the_check_survey_conditions_list .= '		if( getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ') <= ' . $theMarginValue . ' && getRatingValue(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ') != 0 )' . "\n" . '';
			$the_check_survey_conditions_list .= '		{' . "\n" . '';
			$the_check_survey_conditions_list .= '			$(\'#TextInput_' . $questionID . '_' . $optionAutoID . '\').show();' . "\n" . '		} ' . "\n" . '';
			$the_check_survey_conditions_list .= '		else { ' . "\n" . '';
			$the_check_survey_conditions_list .= '			$(\'#TextInput_' . $questionID . '_' . $optionAutoID . '\').hide();' . "\n" . '		} ' . "\n" . '';
			$the_check_survey_conditions_list .= '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
		$EnableQCoreClass->unreplace('subrating' . $questionID);
	}

	if (($QtnListArray[$questionID]['isContInvalid'] == '1') && ($QtnListArray[$questionID]['contInvalidValue'] != '0')) {
		$check_survey_form_no_con_list .= '	if (!CheckRangeIsContInvalid(' . $questionID . ',' . $theFirstID . ',' . $theLastID . ',\'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['contInvalidValue'] . ')){return false;} ' . "\n" . '';
	}

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

	$optionAutoArray = array();
	$optionMinMaxArray = array();
	$i = 0;

	foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
		if ($theBaseQtnArray['questionType'] == 3) {
			$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
		}

		if ($theBaseQtnArray['questionType'] == 25) {
			if (!issamepage($questionID, $theBaseID) && ($theQuestionArray['isHaveText'] == 1)) {
				if ($QtnListArray[$questionID]['isNeg'] == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
				}
				else if ($isModiDataFlag == 1) {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . $R_Row['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID] . ')';
				}
				else {
					$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID . '_' . $question_checkboxID]) . ')';
				}
			}
			else {
				$optionAutoArray[$question_checkboxID] = qshowquotechar($theQuestionArray['optionName']);
			}
		}

		$optionMinMaxArray[$i] = $question_checkboxID;
		$i++;
	}

	$theFirstID = ($optionMinMaxArray[0] != '' ? $optionMinMaxArray[0] : 0);
	$theLastID = ($optionMinMaxArray[count($optionMinMaxArray) - 1] != '' ? $optionMinMaxArray[count($optionMinMaxArray) - 1] : 0);

	if ($theBaseQtnArray['isHaveOther'] == 1) {
		if (!issamepage($questionID, $theBaseID)) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
			}
			else if ($isModiDataFlag == 1) {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . $R_Row['TextOtherValue_' . $theBaseID] . ')';
			}
			else {
				$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']) . '(' . qhtmlspecialchars($_SESSION['TextOtherValue_' . $theBaseID]) . ')';
			}
		}
		else {
			$optionAutoArray[0] = qshowquotechar($theBaseQtnArray['otherText']);
		}
	}

	$isHaveOther = ($theBaseQtnArray['isHaveOther'] != '' ? $theBaseQtnArray['isHaveOther'] : 0);
	$EnableQCoreClass->replace('baseID', $theBaseID);
	$theOptionAutoIDList = '';

	foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
		$theOptionAutoIDList .= $optionAutoID . ',';
		$EnableQCoreClass->replace('optionName', $optionAutoName);
		$theInfoAutoName = qnoscriptstring($optionAutoName);
		$remove_value_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
		$remove_value_list .= '		TextUnInput(document.Survey_Form.option_' . $questionID . '_' . $optionAutoID . ');' . "\n" . '';
		$remove_value_list .= '	}' . "\n" . '';
		$EnableQCoreClass->replace('optionID', 'option_' . $questionID . '_' . $optionAutoID);
		$EnableQCoreClass->replace('optionNameID', $optionAutoID);

		if ($isModiDataFlag == 1) {
			if (($R_Row['option_' . $questionID . '_' . $optionAutoID] != '') && ($R_Row['option_' . $questionID . '_' . $optionAutoID] != '0')) {
				$EnableQCoreClass->replace('optionValue', $R_Row['option_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '0');
			}
		}
		else {
			if (($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '') && ($_SESSION['option_' . $questionID . '_' . $optionAutoID] != '0')) {
				$EnableQCoreClass->replace('optionValue', $_SESSION['option_' . $questionID . '_' . $optionAutoID]);
			}
			else {
				$EnableQCoreClass->replace('optionValue', '0');
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
		$this_fields_list .= 'option_' . $questionID . '_' . $optionAutoID . '|';

		if ($QtnListArray[$questionID]['isRequired'] == '1') {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNotNull(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNumber(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
		}
		else {
			$check_survey_form_no_con_list .= '	if (document.getElementById(\'rating_' . $theBaseID . '_' . $questionID . '_' . $optionAutoID . '\').style.display != \'none\'){' . "\n" . '';
			$check_survey_form_no_con_list .= '		if(!CheckNumber(document.Survey_Form.' . 'option_' . $questionID . '_' . $optionAutoID . ', \'' . $theInfoQtnName . ' - ' . $theInfoAutoName . '\',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ')){return false;} ' . "\n" . '';
			$check_survey_form_no_con_list .= '	}' . "\n" . '';
		}

		$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	}

	break;
}

$QuestionCon = _getquestioncond($questionID, $surveyID);
$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);
$theIsSamePage = (issamepage($questionID, $theBaseID) ? 1 : 0);
$theOriValue = '';
if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
	if ($isModiDataFlag == 1) {
		if ($R_Row['option_' . $theBaseID] != '') {
			if (is_array($R_Row['option_' . $theBaseID])) {
				$theOriValue = implode(',', $R_Row['option_' . $theBaseID]);
			}
			else {
				$theOriValue = $R_Row['option_' . $theBaseID];
			}
		}
	}
	else if ($_SESSION['option_' . $theBaseID] != '') {
		if (is_array($_SESSION['option_' . $theBaseID])) {
			$theOriValue = implode(',', $_SESSION['option_' . $theBaseID]);
		}
		else {
			$theOriValue = $_SESSION['option_' . $theBaseID];
		}
	}
}

$getCheckBoxNoneofAbove = '_getCheckBoxNoneofAbove(' . $theBaseQtnArray['questionType'] . ',' . $theBaseID . ',' . $theBaseIsSelect . ',' . $theIsSamePage . ',' . $QtnListArray[$questionID]['isNeg'] . ',\'' . $theOriValue . '\')';

if ($QuestionCon != '') {
	$check_survey_conditions_list .= '	if(' . $QuestionCon . ' && ' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';
	$check_form_list = '	if(' . $QuestionCon . ' && ' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_form_list .= $check_survey_form_no_con_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_form_list .= '	else{' . "\n" . '';
	$check_form_list .= $remove_value_list;
	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_conditions_list .= '	if(' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").show();' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		$("#question_' . $questionID . '").hide();' . "\n" . '	} ' . "\n" . '';

	if ($check_survey_form_no_con_list != '') {
		$check_form_list = '	if(' . $getCheckBoxNoneofAbove . ')' . "\n" . '	{' . "\n" . '';
		$check_form_list .= $check_survey_form_no_con_list;
		$check_form_list .= '	}' . "\n" . '';
		$check_survey_form_list .= $check_form_list;
	}
}

if ($theRatingType != 0) {
	if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
		$theOptionAutoIDList = substr($theOptionAutoIDList, 0, -1);
		$theOriValue = ',' . $theOriValue . ',';

		if ($theRatingType == 1) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$check_survey_conditions_list .= '	getAutoRatingOpenNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ');' . "\n" . '';
			}
			else {
				$check_survey_conditions_list .= '	getAutoRatingOpenNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ');' . "\n" . '';
			}
		}
		else if ($QtnListArray[$questionID]['isNeg'] == 1) {
			$check_survey_conditions_list .= '	getAutoRatingSliderNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ');' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	getAutoRatingSliderNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ');' . "\n" . '';
		}
	}
	else {
		$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

		if ($theRatingType == 1) {
			if ($QtnListArray[$questionID]['isNeg'] == 1) {
				$check_survey_conditions_list .= '	getAutoRatingOpenSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ');' . "\n" . '';
			}
			else {
				$check_survey_conditions_list .= '	getAutoRatingOpenSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ');' . "\n" . '';
			}
		}
		else if ($QtnListArray[$questionID]['isNeg'] == 1) {
			$check_survey_conditions_list .= '	getAutoRatingSliderSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ');' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	getAutoRatingSliderSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ');' . "\n" . '';
		}
	}
}
else {
	if (!empty($theBaseQtnArray) && !issamepage($questionID, $theBaseID)) {
		$theOptionAutoIDList = substr($theOptionAutoIDList, 0, -1);
		$theOriValue = ',' . $theOriValue . ',';

		if (trim($QtnListArray[$questionID]['unitText']) == '') {
			$isHaveUnitText = 0;
		}
		else {
			$isHaveUnitText = 1;
		}

		if ($QtnListArray[$questionID]['isNeg'] == 1) {
			$check_survey_conditions_list .= '	getAutoRatingNoSamePageIsNeg(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ',' . $QtnListArray[$questionID]['isHaveUnkown'] . ',' . $isHaveUnitText . ');' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	getAutoRatingNoSamePage(' . $theBaseID . ',\'' . $theOptionAutoIDList . '\',\'' . $theOriValue . '\',' . $questionID . ',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ',' . $QtnListArray[$questionID]['isHaveUnkown'] . ',' . $isHaveUnitText . ');' . "\n" . '';
		}
	}
	else {
		if (trim($QtnListArray[$questionID]['unitText']) == '') {
			$isHaveUnitText = 0;
		}
		else {
			$isHaveUnitText = 1;
		}

		$theBaseIsSelect = (empty($theBaseQtnArray) ? 0 : $theBaseQtnArray['isSelect']);

		if ($QtnListArray[$questionID]['isNeg'] == 1) {
			$check_survey_conditions_list .= '	getAutoRatingSamePageIsNeg(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ',' . $QtnListArray[$questionID]['isHaveUnkown'] . ',' . $isHaveUnitText . ');' . "\n" . '';
		}
		else {
			$check_survey_conditions_list .= '	getAutoRatingSamePage(' . $theBaseID . ',' . $theBaseIsSelect . ',' . $questionID . ',' . $QtnListArray[$questionID]['startScale'] . ',' . $QtnListArray[$questionID]['endScale'] . ',' . $theFirstID . ',' . $theLastID . ',' . $isHaveOther . ',' . $QtnListArray[$questionID]['isHaveUnkown'] . ',' . $isHaveUnitText . ');' . "\n" . '';
		}
	}
}

switch ($theRatingType) {
case '0':
	$check_survey_conditions_list .= '	changeMaskingRatingBgColor(' . $questionID . ');' . "\n" . '';
	break;

default:
	if ($isMobile) {
		$EnableQCoreClass->replace('inputPrompt', 'number');
		$check_survey_conditions_list .= '	changeMaskingSingleQtnBgColor(' . $questionID . ');' . "\n" . '';
	}
	else {
		$EnableQCoreClass->replace('inputPrompt', 'text');
	}

	break;
}

$check_survey_conditions_list .= $the_check_survey_conditions_list;
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

$qtnPage = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$EnableQCoreClass->replace('questionList', $qtnPage);
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
				if ($theVarName[2] == 0) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theBaseQtnArray['otherText']) . ']</span>自<span class=red>[';
				}
				else {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']) . ']</span>自<span class=red>[';
				}

				$flag = 1;
				break;

			case 'TextOtherValue':
				if ($theVarName[2] == 0) {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theBaseQtnArray['otherText']) . ' - 理由]</span>自<span class=red>[';
				}
				else {
					$authInfoList .= '<span class=red>[' . qnospecialchar($theCheckBoxListArray[$theVarName[2]]['optionName']) . ' - 理由]</span>自<span class=red>[';
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
					if (($aRow['oriValue'] == '0') || ($aRow['oriValue'] == '0.00')) {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else {
						$authInfoList .= $aRow['oriValue'] . ']</span>至<span class=red>[';
					}

					if (($aRow['updateValue'] == '0') || ($aRow['updateValue'] == '0.00')) {
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
