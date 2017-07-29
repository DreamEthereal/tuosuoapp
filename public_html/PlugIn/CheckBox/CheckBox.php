<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.relation.inc.php';
include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$theInfoQtnName = qnoscriptstring($QtnListArray[$questionID]['questionName']);
$this_fields_list .= 'option_' . $questionID . '|';
$optionTotalNum = count($CheckBoxListArray[$questionID]);
if (($QtnListArray[$questionID]['isSelect'] == '1') && ($_GET['isPrint'] != '1')) {
	if ($isMobile) {
		$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'mCheckBoxSelect.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCheckBoxSelect.html');
	}
}
else {
	if ($isMobile) {
		$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'mCheckBox.html');
	}
	else {
		$isColArrange = false;
		if (($QtnListArray[$questionID]['isColArrange'] == '1') && ($QtnListArray[$questionID]['perRowCol'] != 1)) {
			$isColArrange = true;
		}

		if ($isColArrange == false) {
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCheckBox.html');
		}
		else {
			$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'uCheckBoxCol.html');
		}
	}

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$EnableQCoreClass->replace('isHaveOther', '');
		$EnableQCoreClass->replace('theOptionOdNum', $optionTotalNum);
		$optionTotalNum++;
		$EnableQCoreClass->replace('otherText', qshowquotechar($QtnListArray[$questionID]['otherText']));

		switch ($QtnListArray[$questionID]['isCheckType']) {
		case '4':
			$EnableQCoreClass->replace('unitText', trim($QtnListArray[$questionID]['unitText']));
			break;

		default:
			$EnableQCoreClass->replace('unitText', '');
			break;
		}

		$this_fields_list .= 'TextOtherValue_' . $questionID . '|';

		if ($isModiDataFlag == 1) {
			if ($R_Row['TextOtherValue_' . $questionID] != '') {
				$EnableQCoreClass->replace('value', $R_Row['TextOtherValue_' . $questionID]);
			}
			else {
				$EnableQCoreClass->replace('value', '');
			}
		}
		else if ($_SESSION['TextOtherValue_' . $questionID] != '') {
			$EnableQCoreClass->replace('value', qhtmlspecialchars($_SESSION['TextOtherValue_' . $questionID]));
		}
		else {
			$EnableQCoreClass->replace('value', '');
		}

		if ($isMobile) {
			$EnableQCoreClass->replace('length', '20');

			switch ($QtnListArray[$questionID]['isCheckType']) {
			case 4:
			case 8:
			case 9:
				$EnableQCoreClass->replace('inputPrompt', 'number');
				break;

			case 5:
			case 11:
				$EnableQCoreClass->replace('inputPrompt', 'tel');
				break;

			default:
				$EnableQCoreClass->replace('inputPrompt', 'text');
				break;
			}
		}
		else {
			$EnableQCoreClass->replace('length', $QtnListArray[$questionID]['length']);

			switch ($QtnListArray[$questionID]['isCheckType']) {
			case '6':
				if ($theHaveDatePicker == false) {
					if (strtolower($language) == 'cn') {
						$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.js.php"></script>' . "\n" . '');
					}
					else {
						$EnableQCoreClass->replace('dateIncludeFile', '<script type="text/javascript" src="JS/Calendar.en.php"></script>' . "\n" . '');
					}

					$theHaveDatePicker = true;
				}
				else {
					$EnableQCoreClass->replace('dateIncludeFile', '');
				}

				$EnableQCoreClass->replace('dateTextAction', 'onfocus="if(this.value==\'0000-00-00\') this.value=\'\'" onclick="javascript:SelectDate(this,\'yyyy-MM-dd\')"');
				break;

			default:
				$EnableQCoreClass->replace('dateIncludeFile', '');
				$EnableQCoreClass->replace('dateTextAction', '');
				break;
			}
		}

		if (ischeckboxselect('0', $questionID)) {
			$EnableQCoreClass->replace('isCheck0', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isCheck0', '');
		}
	}
	else {
		$EnableQCoreClass->replace('isHaveOther', 'none');
		$EnableQCoreClass->replace('theOptionOdNum', $optionTotalNum);
		$EnableQCoreClass->replace('otherText', '');
		$EnableQCoreClass->replace('unitText', '');
		$EnableQCoreClass->replace('value', '');
		$EnableQCoreClass->replace('length', '20');
		$EnableQCoreClass->replace('isCheck0', '');
		$EnableQCoreClass->replace('inputPrompt', 'text');
		$EnableQCoreClass->replace('dateIncludeFile', '');
		$EnableQCoreClass->replace('dateTextAction', '');
	}

	$EnableQCoreClass->replace('optionID', 'option_' . $questionID);
	$EnableQCoreClass->replace('questionID', $questionID);
	$EnableQCoreClass->replace('theOptionOdNum99999', $optionTotalNum);

	if ($QtnListArray[$questionID]['isNeg'] == '1') {
		$EnableQCoreClass->replace('isHaveNeg', '');

		if ($QtnListArray[$questionID]['allowType'] == '') {
			$EnableQCoreClass->replace('negText', $lang['neg_text']);
		}
		else {
			$EnableQCoreClass->replace('negText', qshowquotechar($QtnListArray[$questionID]['allowType']));
		}

		if (ischeckboxselect('99999', $questionID)) {
			$EnableQCoreClass->replace('isCheck99999', 'checked');
		}
		else {
			$EnableQCoreClass->replace('isCheck99999', '');
		}
	}
	else {
		$EnableQCoreClass->replace('isHaveNeg', 'none');
		$EnableQCoreClass->replace('negText', '');
		$EnableQCoreClass->replace('isCheck99999', '');
	}
}

$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$questionRequire = '';
$questionName = '';
$questionNotes = '';
$minOption = $maxOption = '';
$check_survey_form_no_con_list = '';

if ($QtnListArray[$questionID]['isRequired'] == '1') {
	$questionRequire = '<span class=red>*</span>';

	if ($QtnListArray[$questionID]['minOption'] != 0) {
		$minOption = '[' . $lang['minOption'] . $QtnListArray[$questionID]['minOption'] . $lang['option'] . ']';
	}

	if ($QtnListArray[$questionID]['maxOption'] != 0) {
		$maxOption = '[' . $lang['maxOption'] . $QtnListArray[$questionID]['maxOption'] . $lang['option'] . ']';
	}

	if ($QtnListArray[$questionID]['isSelect'] == '1') {
		$check_survey_form_no_con_list .= '	if (!CheckListNoSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';

		if ($QtnListArray[$questionID]['minOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckListMinSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['maxOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckListMaxSelect(document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		}
	}
	else {
		$check_survey_form_no_con_list .= '	if (!CheckRadioNoClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\')){return false;} ' . "\n" . '';

		if ($QtnListArray[$questionID]['minOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckCheckMinClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['minOption'] . ')){return false;} ' . "\n" . '';
		}

		if ($QtnListArray[$questionID]['maxOption'] != 0) {
			$check_survey_form_no_con_list .= '	if (!CheckCheckMaxClickInAssMode(' . $questionID . ',document.Survey_Form.' . 'option_' . $questionID . ', \'' . $theInfoQtnName . '\',' . $QtnListArray[$questionID]['maxOption'] . ')){return false;} ' . "\n" . '';
		}
	}
}

$questionName = qshowquotechar($QtnListArray[$questionID]['questionName']);

if ($S_Row['isProperty'] == '1') {
	$questionNotes .= '[' . $lang['question_type_3'] . ']';
}

$questionNotes .= $minOption;
$questionNotes .= $maxOption;
$EnableQCoreClass->replace('questionRequire', $questionRequire);
$EnableQCoreClass->replace('questionName', qshowqtnname($questionName, $questionID));
$EnableQCoreClass->replace('questionNotes', $questionNotes);
$questionTips = qshowquotechar($QtnListArray[$questionID]['questionNotes']);
$EnableQCoreClass->replace('questionTips', qshowqtnname($questionTips, $questionID, 2));
$perLine = 0;
$haveOptionMargin = false;
$theCheckBoxListArray = array();

if ($QtnListArray[$questionID]['isRandOptions'] == '1') {
	$theRandListArray = php_array_rand($CheckBoxListArray[$questionID], $optionTotalNum);

	foreach ($theRandListArray as $theRandCheckBoxID) {
		$theCheckBoxListArray[$theRandCheckBoxID] = $CheckBoxListArray[$questionID][$theRandCheckBoxID];
	}
}
else {
	$theCheckBoxListArray = $CheckBoxListArray[$questionID];
}

$theOptionOdNum = 0;

foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
	if ($theQuestionArray['optionMargin'] != 0) {
		$haveOptionMargin = true;
	}

	if (($QtnListArray[$questionID]['isSelect'] == '1') && ($_GET['isPrint'] != '1')) {
		$Pop_questionName = str_replace('&amp;quot;', '"', qnohtmltag($theQuestionArray['optionName'], 1));
		$EnableQCoreClass->replace('optionName', $Pop_questionName);
		$EnableQCoreClass->replace('optionValue', $question_checkboxID);
		$EnableQCoreClass->replace('optionID', 'option_' . $questionID);

		if (ischeckboxselect($question_checkboxID, $questionID)) {
			$EnableQCoreClass->replace('isCheck', 'selected');
		}
		else {
			$EnableQCoreClass->replace('isCheck', '');
		}

		$OptAssCon = _getoptasscond($questionID, $question_checkboxID);

		if ($OptAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#option_' . $questionID . ' option[value=\'' . $question_checkboxID . '\']").remove();';
			$check_survey_conditions_list .= '' . "\n" . '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		if(!selectIsExitItem(document.Survey_Form.option_' . $questionID . ',' . $question_checkboxID . ')){' . "\n" . '';
			$check_survey_conditions_list .= '		$("#option_' . $questionID . '").append("<option value=\'' . $question_checkboxID . '\'>' . qnoscriptstring($theQuestionArray['optionName']) . '</option>");}' . "\n" . '	} ' . "\n" . '';
		}
	}
	else {
		if ($isMobile) {
			if ($theQuestionArray['optionNameFile'] == '') {
				$optionList = '<tr class=tdheight id="option_checkbox_' . $question_checkboxID . '_container"><td class=answer align=center valign=center width=1%><input type="checkbox" value="' . $question_checkboxID . '" name="' . 'option_' . $questionID . '[]" id="' . 'option_' . $questionID . '" onclick="Check_Survey_Conditions()" ';

				if (ischeckboxselect($question_checkboxID, $questionID)) {
					$optionList .= ' checked ';
				}

				if (($theActionPage == 1) && ($theQuestionArray['optionMargin'] != 0)) {
					if ($isModiDataFlag != 1) {
						$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE FIND_IN_SET(' . $question_checkboxID . ',option_' . $questionID . ') AND overFlag IN (1,3) ';
						$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

						if ($theQuestionArray['optionMargin'] <= $OptionCountRow['optionResponseNum']) {
							$optionList .= ' disabled ';
						}
					}
				}

				$optionList .= '></td><td align=left valign=center class=answer nowrap><a href="javascript:void(0)"  onclick=javascript:selCheckBoxCheckRows("option_' . $questionID . '",' . $theOptionOdNum . ');><div style="white-space:nowrap;" class=tdlineheight><span class="textEdit" id="optionName_3_' . $questionID . '_' . $question_checkboxID . '">' . qshowquotechar($theQuestionArray['optionName']) . '</span></div></a></td></tr>' . "\n" . '';
			}
			else {
				$picFilePath = 'PerUserData/p/' . date('Y-m', $theQuestionArray['createDate']) . '/' . date('d', $theQuestionArray['createDate']) . '/';
				$optionList = '<tr class=tdheight id="option_checkbox_' . $question_checkboxID . '_container"><td class=answer align=center valign=center width=1%><input type="checkbox" value="' . $question_checkboxID . '" name="' . 'option_' . $questionID . '[]" id="' . 'option_' . $questionID . '" onclick="Check_Survey_Conditions()" ';

				if (ischeckboxselect($question_checkboxID, $questionID)) {
					$optionList .= ' checked ';
				}

				if (($theActionPage == 1) && ($theQuestionArray['optionMargin'] != 0)) {
					if ($isModiDataFlag != 1) {
						$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE FIND_IN_SET(' . $question_checkboxID . ',option_' . $questionID . ') AND overFlag IN (1,3) ';
						$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

						if ($theQuestionArray['optionMargin'] <= $OptionCountRow['optionResponseNum']) {
							$optionList .= ' disabled ';
						}
					}
				}

				$optionList .= '></td><td valign=center class=answer align=left><a href="javascript:void(0)" onclick=javascript:selCheckBoxCheckRows("option_' . $questionID . '",' . $theOptionOdNum . ');><div style="white-space:nowrap;" class=tdlineheight><IMG SRC="' . $picFilePath . 's_' . $theQuestionArray['optionNameFile'] . '" border=0 align=absmiddle>&nbsp;<span class="textEdit" id="optionName_3_' . $questionID . '_' . $question_checkboxID . '">' . qshowquotechar($theQuestionArray['optionName']) . '</span></div></a></td></tr>' . "\n" . '';
			}

			$theOptionOdNum++;
		}
		else if ($theQuestionArray['optionNameFile'] == '') {
			$optionList = '<tr id="option_checkbox_' . $question_checkboxID . '_container"><td class=answer><input type="checkbox" value="' . $question_checkboxID . '" name="' . 'option_' . $questionID . '[]" id="' . 'option_' . $questionID . '" onclick="Check_Survey_Conditions()" ';

			if (ischeckboxselect($question_checkboxID, $questionID)) {
				$optionList .= ' checked ';
			}

			if (($theActionPage == 1) && ($theQuestionArray['optionMargin'] != 0)) {
				if ($isModiDataFlag != 1) {
					$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE FIND_IN_SET(' . $question_checkboxID . ',option_' . $questionID . ') AND overFlag IN (1,3) ';
					$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

					if ($theQuestionArray['optionMargin'] <= $OptionCountRow['optionResponseNum']) {
						$optionList .= ' disabled ';
					}
				}
			}

			$optionList .= '><span class="textEdit" id="optionName_3_' . $questionID . '_' . $question_checkboxID . '">' . qshowquotechar($theQuestionArray['optionName']) . '</span></td></tr>' . "\n" . '';
		}
		else {
			$picFilePath = 'PerUserData/p/' . date('Y-m', $theQuestionArray['createDate']) . '/' . date('d', $theQuestionArray['createDate']) . '/';

			if ($QtnListArray[$questionID]['isColArrange'] == '1') {
				$optionList = '<tr id="option_checkbox_' . $question_checkboxID . '_container"><td>' . "\n" . '<table cellSpacing=0 cellPadding=0 align=center valign="center">' . "\n" . '<tr><td align=center><input type="checkbox" value="' . $question_checkboxID . '" name="' . 'option_' . $questionID . '[]" id="' . 'option_' . $questionID . '"  onclick="Check_Survey_Conditions()" ';

				if (ischeckboxselect($question_checkboxID, $questionID)) {
					$optionList .= ' checked ';
				}

				if (($theActionPage == 1) && ($theQuestionArray['optionMargin'] != 0)) {
					if ($isModiDataFlag != 1) {
						$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE FIND_IN_SET(' . $question_checkboxID . ',option_' . $questionID . ') AND overFlag IN (1,3) ';
						$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

						if ($theQuestionArray['optionMargin'] <= $OptionCountRow['optionResponseNum']) {
							$optionList .= ' disabled ';
						}
					}
				}

				$optionList .= '></td>' . "\n" . '<td align=center><table style="LINE-HEIGHT: 100%" cellSpacing=0 borderColorDark=#ffffff cellPadding=0 width=120 borderColorLight=#cccccc border=0 align=center valign="center">' . "\n" . '<tr><td><a href="' . $picFilePath . $theQuestionArray['optionNameFile'] . '" title="' . qhtmlspecialchars($theQuestionArray['optionName']) . '" rel="picbox"><IMG SRC="' . $picFilePath . 's_' . $theQuestionArray['optionNameFile'] . '" border=0></a></td></tr>' . "\n" . '';
				$optionList .= '<tr><td align=center  class=answer><span class="textEdit" id="optionName_3_' . $questionID . '_' . $question_checkboxID . '">' . qshowquotechar($theQuestionArray['optionName']) . '</span></td></tr></table>' . "\n" . '</td></tr></table>' . "\n" . '</td></tr>';
			}
			else {
				$optionList = '<tr id="option_checkbox_' . $question_checkboxID . '_container"><td>' . "\n" . '<table style="LINE-HEIGHT: 100%" cellSpacing=0 borderColorDark=#ffffff cellPadding=0 borderColorLight=#cccccc border=0 align=left valign="center">' . "\n" . '<tr><td align=left><input type="checkbox" value="' . $question_checkboxID . '" name="' . 'option_' . $questionID . '[]" id="' . 'option_' . $questionID . '" onclick="Check_Survey_Conditions()" ';

				if (ischeckboxselect($question_checkboxID, $questionID)) {
					$optionList .= ' checked ';
				}

				if (($theActionPage == 1) && ($theQuestionArray['optionMargin'] != 0)) {
					if ($isModiDataFlag != 1) {
						$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE FIND_IN_SET(' . $question_checkboxID . ',option_' . $questionID . ') AND overFlag IN (1,3) ';
						$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);

						if ($theQuestionArray['optionMargin'] <= $OptionCountRow['optionResponseNum']) {
							$optionList .= ' disabled ';
						}
					}
				}

				$optionList .= '></td>' . "\n" . '<td valign=center><a href="' . $picFilePath . $theQuestionArray['optionNameFile'] . '" title="' . qhtmlspecialchars($theQuestionArray['optionName']) . '" rel="picbox"><IMG SRC="' . $picFilePath . 's_' . $theQuestionArray['optionNameFile'] . '" border=0></a></td>' . "\n" . '';
				$optionList .= '<td valign=center class=answer><span class="textEdit" id="optionName_3_' . $questionID . '_' . $question_checkboxID . '">' . qshowquotechar($theQuestionArray['optionName']) . '</span></td></tr></table>' . "\n" . '</td></tr>';
			}
		}

		$EnableQCoreClass->replace('optionList', $optionList);
		$OptAssCon = _getoptasscond($questionID, $question_checkboxID);

		if ($OptAssCon != '') {
			$check_survey_conditions_list .= '	if(' . $OptAssCon . ')' . "\n" . '	{' . "\n" . '';
			$check_survey_conditions_list .= '		$("#option_checkbox_' . $question_checkboxID . '_container").hide();' . "\n" . '';
			$check_survey_conditions_list .= '		$("input[id=\'option_' . $questionID . '\'][value=\'' . $question_checkboxID . '\']").attr("checked",false);';
			$check_survey_conditions_list .= '' . "\n" . '	} ' . "\n" . '';
			$check_survey_conditions_list .= '	else { ' . "\n" . '';
			$check_survey_conditions_list .= '		$("#option_checkbox_' . $question_checkboxID . '_container").show();' . "\n" . '	} ' . "\n" . '';
		}
	}

	$perLine++;

	if ($QtnListArray[$questionID]['isColArrange'] == '1') {
		if (($perLine % $QtnListArray[$questionID]['perRowCol']) == 0) {
			$EnableQCoreClass->replace('perLine', '</tr><tr>');
		}
		else {
			$EnableQCoreClass->replace('perLine', '');
		}
	}
	else if ($perLine == $optionTotalNum) {
		$EnableQCoreClass->replace('perLine', '');
	}
	else {
		$EnableQCoreClass->replace('perLine', '</tr><tr>');
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if (($QtnListArray[$questionID]['isSelect'] == '1') && ($QtnListArray[$questionID]['isNeg'] == '1')) {
	if ($QtnListArray[$questionID]['allowType'] == '') {
		$Pop_questionName = $lang['neg_text'];
	}
	else {
		$Pop_questionName = str_replace('&amp;quot;', '"', qnohtmltag($QtnListArray[$questionID]['allowType'], 1));
	}

	$EnableQCoreClass->replace('optionName', $Pop_questionName);
	$EnableQCoreClass->replace('optionValue', '99999');
	$EnableQCoreClass->replace('optionID', 'option_' . $questionID);

	if (ischeckboxselect('99999', $questionID)) {
		$EnableQCoreClass->replace('isCheck', 'selected');
	}
	else {
		$EnableQCoreClass->replace('isCheck', '');
	}

	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
}

if ($haveOptionMargin == true) {
	$this_check_list .= '3*option_' . $questionID . '|';
}

if (($QtnListArray[$questionID]['isSelect'] != 1) && $isMobile) {
	$check_survey_conditions_list .= '	changeMaskingSingleBgColor(' . $questionID . ');' . "\n" . '';
}

if (($QtnListArray[$questionID]['isSelect'] != '1') && ($QtnListArray[$questionID]['isHaveOther'] == '1')) {
	$check_survey_form_no_con_list .= '	if (document.Survey_Form.' . 'option_' . $questionID . '[' . $perLine . '].checked )' . "\n" . '	{' . "\n" . '		if (!CheckNotNull(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;}' . "\n" . '';

	switch ($QtnListArray[$questionID]['isCheckType']) {
	case '1':
		$check_survey_form_no_con_list .= '		if (!CheckEmail(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '2':
		$check_survey_form_no_con_list .= '		if (!CheckStringLength(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\',' . $QtnListArray[$questionID]['minSize'] . ',' . $QtnListArray[$questionID]['maxSize'] . ')){return false;} ' . "\n" . '';
		break;

	case '3':
		$check_survey_form_no_con_list .= '		if (!CheckNoChinese(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '4':
		$check_survey_form_no_con_list .= '		if (!CheckIsValue(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\',' . $QtnListArray[$questionID]['minSize'] . ',' . $QtnListArray[$questionID]['maxSize'] . ')){return false;} ' . "\n" . '';
		break;

	case '5':
		$check_survey_form_no_con_list .= '		if (!CheckPhone(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '6':
		$check_survey_form_no_con_list .= '		if (!CheckDate(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '7':
		$check_survey_form_no_con_list .= '		if (!CheckIDCardNo(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '8':
		$check_survey_form_no_con_list .= '		if (!CheckCorpCode(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '9':
		$check_survey_form_no_con_list .= '		if (!CheckPostalCode(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '10':
		$check_survey_form_no_con_list .= '		if (!CheckURL(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '11':
		$check_survey_form_no_con_list .= '		if (!CheckMobile(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;

	case '12':
		$check_survey_form_no_con_list .= '		if (!CheckChinese(document.Survey_Form.' . 'TextOtherValue_' . $questionID . ', \'' . $theInfoQtnName . ' - ' . qnoscriptstring($QtnListArray[$questionID]['otherText']) . '\')){return false;} ' . "\n" . '';
		break;
	}

	$check_survey_form_no_con_list .= '	}' . "\n" . '';
	$check_survey_form_no_con_list .= '	else' . "\n" . '	{' . "\n" . '		TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '	}' . "\n" . '';
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

	if ($QtnListArray[$questionID]['isSelect'] == '1') {
		$check_form_list .= '	ListUnSelect(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';
	}
	else {
		$check_form_list .= '	RadioUnClick(document.Survey_Form.option_' . $questionID . ');' . "\n" . '';

		if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
			$check_form_list .= '	TextUnInput(document.Survey_Form.TextOtherValue_' . $questionID . ');' . "\n" . '';
		}
	}

	$check_form_list .= '	}' . "\n" . '';
	$check_survey_form_list .= $check_form_list;
}
else {
	$check_survey_form_list .= $check_survey_form_no_con_list;
}

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$theQtnIsSelect = ($QtnListArray[$questionID]['isSelect'] == 1 ? 1 : 0);
	$check_survey_conditions_list .= '	theExcludeItemInAssMode(document.Survey_Form.option_' . $questionID . ',' . $theQtnIsSelect . ',99999);' . "\n" . '';
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

$checkboxPage = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
	$checkboxPage = preg_replace('/<!--DELETE_OTHER_CHECKBOX_BEGIN-->/s', '', $checkboxPage);
	$checkboxPage = preg_replace('/<!--DELETE_OTHER_CHECKBOX_END-->/s', '', $checkboxPage);
}
else {
	$checkboxPage = preg_replace('/<!--DELETE_OTHER_CHECKBOX_BEGIN-->(.*)<!--DELETE_OTHER_CHECKBOX_END-->/s', '', $checkboxPage);
}

if ($QtnListArray[$questionID]['isNeg'] == '1') {
	$checkboxPage = preg_replace('/<!--DELETE_NEG_CHECKBOX_BEGIN-->/s', '', $checkboxPage);
	$checkboxPage = preg_replace('/<!--DELETE_NEG_CHECKBOX_END-->/s', '', $checkboxPage);
}
else {
	$checkboxPage = preg_replace('/<!--DELETE_NEG_CHECKBOX_BEGIN-->(.*)<!--DELETE_NEG_CHECKBOX_END-->/s', '', $checkboxPage);
}

$EnableQCoreClass->replace('questionList', $checkboxPage);
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

			if ($theVarName[0] == 'option') {
				$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

				if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
					if ($aRow['oriValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>至<span class=red>[';
					}
					else {
						$theOriValue = explode(',', $aRow['oriValue']);
						$theOriValueList = '';

						foreach ($theOriValue as $thisOriValue) {
							switch ($thisOriValue) {
							case '0':
								$theOriValueList .= qnospecialchar($QtnListArray[$questionID]['otherText']) . ',';
								break;

							default:
								$theOriValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisOriValue]['optionName']) . ',';
								break;
							}
						}

						$authInfoList .= substr($theOriValueList, 0, -1) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>';
					}
					else {
						$theUpdateValue = explode(',', $aRow['updateValue']);
						$thisUpdateValueList = '';

						foreach ($theUpdateValue as $thisUpdateValue) {
							switch ($thisOriValue) {
							case '0':
								$thisUpdateValueList .= qnospecialchar($QtnListArray[$questionID]['otherText']) . ',';
								break;

							default:
								$thisUpdateValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
								break;
							}
						}

						$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
					}
				}
				else {
					if ($aRow['oriValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>至<span class=red>[';
					}
					else if ($aRow['oriValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>至<span class=red>[';
					}
					else {
						$theOriValue = explode(',', $aRow['oriValue']);
						$theOriValueList = '';

						foreach ($theOriValue as $thisOriValue) {
							$theOriValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisOriValue]['optionName']) . ',';
						}

						$authInfoList .= substr($theOriValueList, 0, -1) . ']</span>至<span class=red>[';
					}

					if ($aRow['updateValue'] == '') {
						$authInfoList .= $lang['skip_answer'] . ']</span>';
					}
					else if ($aRow['updateValue'] == '99999') {
						$authInfoList .= qnospecialchar($QtnListArray[$questionID]['allowType']) . ']</span>';
					}
					else {
						$theUpdateValue = explode(',', $aRow['updateValue']);
						$thisUpdateValueList = '';

						foreach ($theUpdateValue as $thisUpdateValue) {
							$thisUpdateValueList .= qnospecialchar($CheckBoxListArray[$questionID][$thisUpdateValue]['optionName']) . ',';
						}

						$authInfoList .= substr($thisUpdateValueList, 0, -1) . ']</span>';
					}
				}
			}
			else {
				$authInfoList .= '<span class=red>[' . qnospecialchar($QtnListArray[$questionID]['otherText']) . ']</span>自<span class=red>[';

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
