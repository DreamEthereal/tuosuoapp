<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.quota.inc.php';
$this_fields_list .= 'option_' . $questionID . '|';
$QuestionCon = _getquestioncond($questionID, $surveyID);

if ($QuestionCon != '') {
	$check_survey_conditions_list .= '	if(' . $QuestionCon . ')' . "\n" . '	{' . "\n" . '';
	$check_survey_conditions_list .= '		document.getElementById(\'option_' . $questionID . '\').value=\'1\';' . "\n" . '	} ' . "\n" . '';
	$check_survey_conditions_list .= '	else { ' . "\n" . '';
	$check_survey_conditions_list .= '		document.getElementById(\'option_' . $questionID . '\').value=\'0\';' . "\n" . '	} ' . "\n" . '';
}

$EndSurveyCon = _getsurveyquotacond($questionID, $surveyID, strtolower($language));

if ($EndSurveyCon != '') {
	$survey_quota_list .= $EndSurveyCon;
}

$EnableQCoreClass->replace('questionList', '<input type="hidden" name="option_' . $questionID . '" id="option_' . $questionID . '" value="0">');
$check_survey_conditions_list .= '$(\'#question_' . $questionID . '\').hide();' . "\n" . '';
if (($isAuthDataFlag == 1) || ($isAuthAppDataFlag == 1)) {
	$EnableQCoreClass->replace('authList', '');
}

?>
