<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DataCross2D.html');
$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'OPTION', 'option' . $questionID);
$EnableQCoreClass->replace('option' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'ROWS', 'rows' . $questionID);
$EnableQCoreClass->set_CycBlock('ROWS', 'CELL', 'cell' . $questionID);
$EnableQCoreClass->replace('rows' . $questionID, '');
$EnableQCoreClass->replace('cell' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'COLS', 'cols' . $questionID);
$EnableQCoreClass->replace('cols' . $questionID, '');
$EnableQCoreClass->set_CycBlock('OPTION', 'TOTAL', 'total' . $questionID);
$EnableQCoreClass->replace('total' . $questionID, '');
$_SESSION['ObsFreq'] = $_SESSION['Headings'] = $_SESSION['NValue'] = $_SESSION['LabelName'] = array();

foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
	$optionName = qcrossqtnname($QtnListArray[$questionID]['questionName']) . ' - ' . qcrossqtnname($theQuestionArray['optionName']);
	$EnableQCoreClass->replace('questionName', qnospecialchar($QtnListArray[$questionID]['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
	$ObsFreq = array();
	$theHeadings = array();
	$theNValue = array();
	$theLabelName = array();
	$theColTotal = array();

	foreach ($theXName as $k => $thisXName) {
		$EnableQCoreClass->replace('colName', $thisXName);
		$EnableQCoreClass->parse('cols' . $questionID, 'COLS', true);
		$theHeadings[] = str_replace('<span class=red>/</span>', '/', $thisXName);
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$l = 0;
		$OptionCountSQL = ' SELECT a.question_range_answerID,count(*) as optionResponseNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' a,' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.question_range_answerID = b.option_' . $questionID . '_' . $question_range_optionID . ' AND ' . $theXCond[$k] . ' and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY b.option_' . $questionID . '_' . $question_range_optionID . ' ORDER BY optionResponseNum DESC';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow['question_range_answerID'];
			$allOptionResponseNum[$OptionCountRow['question_range_answerID']] = $OptionCountRow['optionResponseNum'];
		}

		foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
			if (in_array($question_range_answerID, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$question_range_answerID];
				$theColTotal[$k] += $ObsFreq[$l][$k];
			}
			else {
				$ObsFreq[$l][$k] = 0;
			}

			$l++;
		}
	}

	$repTotalAnswerNum = array_sum($theColTotal);
	$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
	$m = 0;

	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$EnableQCoreClass->replace('rowName', qnospecialchar($theAnswerArray['optionAnswer']));
		$theLabelName[] = qcrossqtnname($theAnswerArray['optionAnswer']);
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
		$theNValue[] = $colTotalNum;
		$EnableQCoreClass->replace('colTotalNum', $colTotalNum);
		$EnableQCoreClass->replace('colTotalPercent', $colTotalNum == 0 ? '0%' : '100%');
		$EnableQCoreClass->parse('total' . $questionID, 'TOTAL', true);
	}

	unset($theColTotal);
	unset($allResponseOptionID);
	unset($allOptionResponseNum);
	$_SESSION['ObsFreqs'][$question_range_optionID] = $ObsFreq;
	$_SESSION['Headings'][$question_range_optionID] = $theHeadings;
	$_SESSION['NValue'][$question_range_optionID] = $theNValue;
	$_SESSION['LabelName'][$question_range_optionID] = $theLabelName;
	$chartWidth = (isset($_POST['chartWidth']) ? $_POST['chartWidth'] : 600);
	$chartHeight = (isset($_POST['chartHeight']) ? $_POST['chartHeight'] : 420);

	switch ($_POST['chartType']) {
	case 8:
	case 9:
		$theFalshContent = '<div id="flashcontent' . $question_range_optionID . '" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
		$theFalshContent .= '<script type="text/javascript">';
		$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amchart' . $question_range_optionID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
		$theFalshContent .= 'so.addVariable("path", "../Chart/");';
		$theFalshContent .= 'so.addVariable("chart_id", "amchart' . $question_range_optionID . '");';
		$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&type=2&dataID=' . $question_range_optionID . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode($optionName) . '"));';
		$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?type=2&surveyID=' . $theSurveyID . '&dataID=' . $question_range_optionID . '"));';
		$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
		$theFalshContent .= 'so.addParam("wmode", "opaque");';
		$theFalshContent .= 'so.write("flashcontent' . $question_range_optionID . '");';
		$theFalshContent .= '</script>';
		break;

	case 5:
	case 6:
	default:
		$theFalshContent = '<div id="flashcontent' . $question_range_optionID . '" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
		$theFalshContent .= '<script type="text/javascript">';
		$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amchart' . $question_range_optionID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
		$theFalshContent .= 'so.addVariable("path", "../Chart/");';
		$theFalshContent .= 'so.addVariable("chart_id", "amchart' . $question_range_optionID . '");';
		$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&type=2&dataID=' . $question_range_optionID . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode($optionName) . '"));';
		$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?type=2&surveyID=' . $theSurveyID . '&dataID=' . $question_range_optionID . '"));';
		$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
		$theFalshContent .= 'so.addParam("wmode", "opaque");';
		$theFalshContent .= 'so.write("flashcontent' . $question_range_optionID . '");';
		$theFalshContent .= '</script>';
		break;
	}

	unset($ObsFreq);
	unset($theHeadings);
	unset($theNValue);
	unset($theLabelName);
	$EnableQCoreClass->replace('flashContent', $theFalshContent);
	$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
	$EnableQCoreClass->unreplace('rows' . $questionID);
	$EnableQCoreClass->unreplace('cols' . $questionID);
	$EnableQCoreClass->unreplace('total' . $questionID);
}

$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');

?>
