<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataCross1D.html');
$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']));
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'ROWS', 'rows' . $questionID);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $questionID);
$EnableQCoreClass->replace('rows' . $questionID, '');
$EnableQCoreClass->replace('cell' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'COLS', 'cols' . $questionID);
$EnableQCoreClass->replace('cols' . $questionID, '');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'TOTAL', 'total' . $questionID);
$EnableQCoreClass->replace('total' . $questionID, '');
$ObsFreq = array();
$Headings = array();
$NValue = array();
$LabelName = array();
$theColTotal = array();

foreach ($theXName as $k => $thisXName) {
	$EnableQCoreClass->replace('colName', $thisXName);
	$EnableQCoreClass->parse('cols' . $questionID, 'COLS', true);
	$Headings[] = str_replace('<span class=red>/</span>', '/', $thisXName);
	$l = 0;

	foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
		$OptionCountSQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE b.option_' . $questionID . ' =  \'' . $question_yesnoID . '\' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountRow = $DB->queryFirstRow($OptionCountSQL);
		$ObsFreq[$l][$k] = $OptionCountRow['optionResponseNum'];
		$theColTotal[$k] += $OptionCountRow['optionResponseNum'];
		$l++;
	}
}

$repTotalAnswerNum = array_sum($theColTotal);
$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
$m = 0;

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$EnableQCoreClass->replace('rowName', $theQuestionArray['optionName']);
	$LabelName[] = qcrossqtnname($theQuestionArray['optionName']);
	$rowTotalNum = 0;

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('cellNum', $ObsFreq[$m][$k]);
		$rowTotalNum += $ObsFreq[$m][$k];
		$EnableQCoreClass->replace('cellPercent', countpercent($ObsFreq[$m][$k], $theColTotal[$k]));
		$EnableQCoreClass->parse('cell' . $questionID, 'CELL', true);
	}

	$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
	$EnableQCoreClass->replace('rowTotalPercent', countpercent($rowTotalNum, $repTotalAnswerNum));
	$m++;
	$EnableQCoreClass->parse('rows' . $questionID, 'ROWS', true);
	$EnableQCoreClass->unreplace('cell' . $questionID);
}

foreach ($theXName as $p => $thisXName) {
	$colTotalNum = ($theColTotal[$p] == '' ? 0 : $theColTotal[$p]);
	$NValue[] = $colTotalNum;
	$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
	$EnableQCoreClass->replace('colTotalPercent', $colTotalNum == 0 ? '0%' : '100%');
	$EnableQCoreClass->parse('total' . $questionID, 'TOTAL', true);
}

unset($theColTotal);
$_SESSION['ObsFreq'] = $ObsFreq;
$_SESSION['Headings'] = $Headings;
$_SESSION['NValue'] = $NValue;
$_SESSION['LabelName'] = $LabelName;
$chartWidth = (isset($_POST['chartWidth']) ? $_POST['chartWidth'] : 600);
$chartHeight = (isset($_POST['chartHeight']) ? $_POST['chartHeight'] : 420);

switch ($_POST['chartType']) {
case 8:
case 9:
	$theFalshContent = '<div id="flashcontent" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
	$theFalshContent .= '<script type="text/javascript">';
	$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumns", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
	$theFalshContent .= 'so.addVariable("path", "../Chart/");';
	$theFalshContent .= 'so.addVariable("chart_id", "amcolumns");';
	$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode(qcrossqtnname($QtnListArray[$questionID]['questionName'])) . '"));';
	$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?surveyID=' . $theSurveyID . '"));';
	$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
	$theFalshContent .= 'so.addParam("wmode", "opaque");';
	$theFalshContent .= 'so.write("flashcontent");';
	$theFalshContent .= '</script>';
	break;

case 5:
case 6:
default:
	$theFalshContent = '<div id="flashcontent" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
	$theFalshContent .= '<script type="text/javascript">';
	$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumns", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
	$theFalshContent .= 'so.addVariable("path", "../Chart/");';
	$theFalshContent .= 'so.addVariable("chart_id", "amcolumns");';
	$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode(qcrossqtnname($QtnListArray[$questionID]['questionName'])) . '"));';
	$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?surveyID=' . $theSurveyID . '"));';
	$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
	$theFalshContent .= 'so.addParam("wmode", "opaque");';
	$theFalshContent .= 'so.write("flashcontent");';
	$theFalshContent .= '</script>';
	break;
}

unset($ObsFreq);
unset($Headings);
unset($NValue);
unset($LabelName);
$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
$DataCrossHTML .= $theFalshContent;

?>
