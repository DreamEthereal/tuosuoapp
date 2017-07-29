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

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
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
		$l = 0;
		$allResponseOptionID = array();
		$allOptionResponseNum = array();
		$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
		$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $theSurveyID . ' b WHERE ' . $theXCond[$k] . ' and ' . $dataSource . ' GROUP BY ' . $theRankOptionID . ' ';
		$OptionCountResult = $DB->query($OptionCountSQL);

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
			$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
		}

		$m = $QtnListArray[$questionID]['endScale'];

		for (; $QtnListArray[$questionID]['startScale'] <= $m; $m--) {
			if (in_array($m, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[$m];
				$theColTotal[$k] += $ObsFreq[$l][$k];
			}
			else {
				$ObsFreq[$l][$k] = 0;
			}

			$l++;
		}

		if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
			if (in_array(99, $allResponseOptionID)) {
				$ObsFreq[$l][$k] = $allOptionResponseNum[99];
				$theColTotal[$k] += $ObsFreq[$l][$k];
			}
			else {
				$ObsFreq[$l][$k] = 0;
			}
		}
	}

	$repTotalAnswerNum = array_sum($theColTotal);
	$EnableQCoreClass->replace('repTotalAnswerNum', $repTotalAnswerNum);
	$m = 0;
	$p = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $p; $p--) {
		$EnableQCoreClass->replace('rowName', $QtnListArray[$questionID]['weight'] * $p);
		$theLabelName[] = $QtnListArray[$questionID]['weight'] * $p;
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

	if ($QtnListArray[$questionID]['isHaveUnkown'] == 1) {
		$EnableQCoreClass->replace('rowName', $lang['rating_unknow']);
		$theLabelName[] = $lang['rating_unknow'];
		$rowTotalNum = 0;

		foreach ($theXName as $k => $thisXName) {
			$EnableQCoreClass->replace('cellNum', $ObsFreq[$m][$k]);
			$rowTotalNum += $ObsFreq[$m][$k];
			$EnableQCoreClass->replace('cellPercent', countpercent($ObsFreq[$m][$k], $theColTotal[$k]));
			$EnableQCoreClass->parse('cell' . $questionID, 'CELL', true);
		}

		$EnableQCoreClass->replace('rowTotalNum', $rowTotalNum);
		$EnableQCoreClass->replace('rowTotalPercent', countpercent($rowTotalNum, $repTotalAnswerNum));
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
	$_SESSION['ObsFreqs'][$question_rankID] = $ObsFreq;
	$_SESSION['Headings'][$question_rankID] = $theHeadings;
	$_SESSION['NValue'][$question_rankID] = $theNValue;
	$_SESSION['LabelName'][$question_rankID] = $theLabelName;
	$chartWidth = (isset($_POST['chartWidth']) ? $_POST['chartWidth'] : 600);
	$chartHeight = (isset($_POST['chartHeight']) ? $_POST['chartHeight'] : 420);

	switch ($_POST['chartType']) {
	case 8:
	case 9:
		$theFalshContent = '<div id="flashcontent' . $question_rankID . '" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
		$theFalshContent .= '<script type="text/javascript">';
		$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amchart' . $question_rankID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
		$theFalshContent .= 'so.addVariable("path", "../Chart/");';
		$theFalshContent .= 'so.addVariable("chart_id", "amchart' . $question_rankID . '");';
		$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&type=2&dataID=' . $question_rankID . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode($optionName) . '"));';
		$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?type=2&surveyID=' . $theSurveyID . '&dataID=' . $question_rankID . '"));';
		$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
		$theFalshContent .= 'so.addParam("wmode", "opaque");';
		$theFalshContent .= 'so.write("flashcontent' . $question_rankID . '");';
		$theFalshContent .= '</script>';
		break;

	case 5:
	case 6:
	default:
		$theFalshContent = '<div id="flashcontent' . $question_rankID . '" style="margin-left:5px"><b>You need to upgrade your Flash Player</b></div>';
		$theFalshContent .= '<script type="text/javascript">';
		$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amchart' . $question_rankID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
		$theFalshContent .= 'so.addVariable("path", "../Chart/");';
		$theFalshContent .= 'so.addVariable("chart_id", "amchart' . $question_rankID . '");';
		$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/CrossSetting.php?chartType=' . $_POST['chartType'] . '&type=2&dataID=' . $question_rankID . '&surveyID=' . $theSurveyID . '&questionName=' . urlencode($optionName) . '"));';
		$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/CrossData.php?type=2&surveyID=' . $theSurveyID . '&dataID=' . $question_rankID . '"));';
		$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
		$theFalshContent .= 'so.addParam("wmode", "opaque");';
		$theFalshContent .= 'so.write("flashcontent' . $question_rankID . '");';
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
