<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';

if ($_POST['Action'] == 'DataChartSubmit') {
	@set_time_limit(0);
	$theQtnJoin = explode('###', $_POST['questionID']);
	$questionID = $theQtnJoin[0];
	$questionType = $theQtnJoin[1];
	$surveyID = $_POST['surveyID'];
	$chartWidth = (isset($_POST['chartWidth']) ? $_POST['chartWidth'] : 600);
	$chartHeight = (isset($_POST['chartHeight']) ? $_POST['chartHeight'] : 420);
	$dataSourceId = $_POST['dataSource'];

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	switch ($_POST['chartType']) {
	case 1:
	case 3:
		switch ($questionType) {
		case '1':
		case '2':
		case '3':
		case '13':
		case '17':
		case '18':
		case '24':
		case '25':
			$theFalshContent = '<script type="text/javascript">';
			$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumn", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
			$theFalshContent .= 'so.addVariable("path", "../Chart/");';
			$theFalshContent .= 'so.addVariable("chart_id", "amcolumn");';
			$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/ColumnSetting.php?chartType=' . $_POST['chartType'] . '&dataSourceId=' . $dataSourceId . '&surveyID=' . $surveyID . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/ColumnData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
			$theFalshContent .= 'so.addParam("wmode", "opaque");';
			$theFalshContent .= 'so.write("flashcontent");';
			$theFalshContent .= '</script>';
			break;

		case '31':
			$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$theSID = $theSurveyID = $Row['surveyID'];
			if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
				require ROOT_PATH . 'Includes/MakeCache.php';
			}

			require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
			$theFalshContent = $theDivContent = $theJSContent = '';
			$u = 1;

			for (; $u <= $QtnListArray[$questionID]['maxSize']; $u++) {
				$theDivContent .= '<div id="flashcontent' . $u . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumn' . $u . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "amcolumn' . $u . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/ColumnSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $u . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/ColumnData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $u . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $u . '");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;
		}
	case 8:
	case 9:
		switch ($questionType) {
		case '6':
		case '7':
		case '10':
		case '19':
		case '20':
		case '26':
		case '15':
		case '21':
		case '28':
			$theFalshContent = '<script type="text/javascript">';
			$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumns", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
			$theFalshContent .= 'so.addVariable("path", "../Chart/");';
			$theFalshContent .= 'so.addVariable("chart_id", "amcolumns");';
			$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/ColumnsSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/ColumnsData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
			$theFalshContent .= 'so.addParam("wmode", "opaque");';
			$theFalshContent .= 'so.write("flashcontent");';
			$theFalshContent .= '</script>';
			break;
		}

		break;

	case 5:
	case 6:
		switch ($questionType) {
		case '6':
		case '10':
		case '19':
		case '20':
		case '26':
		case '15':
		case '21':
			$theFalshContent = '<script type="text/javascript">';
			$theFalshContent .= 'var so = new SWFObject("../Chart/AmColumn.swf?cache=0", "amcolumns", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
			$theFalshContent .= 'so.addVariable("path", "../Chart/");';
			$theFalshContent .= 'so.addVariable("chart_id", "amcolumns");';
			$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/StackSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/ColumnsData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
			$theFalshContent .= 'so.addParam("wmode", "opaque");';
			$theFalshContent .= 'so.write("flashcontent");';
			$theFalshContent .= '</script>';
			break;
		}

		break;

	case 7:
		switch ($questionType) {
		case '10':
		case '20':
		case '15':
		case '21':
		case '16':
		case '22':
			$theFalshContent = '<script type="text/javascript">';
			$theFalshContent .= 'var so = new SWFObject("../Chart/AmRadar.swf?cache=0", "amradar", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
			$theFalshContent .= 'so.addVariable("path", "../Chart/");';
			$theFalshContent .= 'so.addVariable("chart_id", "amradar");';
			$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/RadarSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/RadarData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.write("flashcontent");';
			$theFalshContent .= '</script>';
			break;
		}

		break;

	case 2:
	case 4:
		if (in_array($questionType, array('6', '10', '19', '20', '26', '15', '21', '31'))) {
			$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$theSID = $theSurveyID = $Row['surveyID'];
			if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
				require ROOT_PATH . 'Includes/MakeCache.php';
			}

			require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
		}

		switch ($questionType) {
		case '1':
		case '2':
		case '13':
		case '17':
		case '18':
		case '24':
			$theFalshContent = '<script type="text/javascript">';
			$theFalshContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
			$theFalshContent .= 'so.addVariable("path", "../Chart/");';
			$theFalshContent .= 'so.addVariable("chart_id", "ampie");';
			$theFalshContent .= 'so.addVariable("settings_file", escape("../Chart/PieSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("data_file", escape("../Chart/PieData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '"));';
			$theFalshContent .= 'so.addVariable("preloader_color", "#999999");';
			$theFalshContent .= 'so.addParam("wmode", "opaque");';
			$theFalshContent .= 'so.write("flashcontent");';
			$theFalshContent .= '</script>';
			break;

		case '6':
			$theFalshContent = $theDivContent = $theJSContent = '';

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				$theDivContent .= '<div id="flashcontent' . $question_range_optionID . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $question_range_optionID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie' . $question_range_optionID . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_range_optionID . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_range_optionID . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $question_range_optionID . '");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;

		case '31':
			$theFalshContent = $theDivContent = $theJSContent = '';
			$u = 1;

			for (; $u <= $QtnListArray[$questionID]['maxSize']; $u++) {
				$theDivContent .= '<div id="flashcontent' . $u . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $u . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie' . $u . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $u . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $u . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $u . '");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;

		case '10':
			$theFalshContent = '';

			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$theDivContent .= '<div id="flashcontent' . $question_rankID . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $question_rankID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie' . $question_rankID . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_rankID . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_rankID . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $question_rankID . '");';
			}

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				$theDivContent .= '<div id="flashcontent0"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie0", "' . $chartWidth . '", "' . $chartHeight . '","8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie0");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=0"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=0"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent0");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;

		case '15':
			$theFalshContent = '';

			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$theDivContent .= '<div id="flashcontent' . $question_rankID . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $question_rankID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie' . $question_rankID . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_rankID . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&questionID=' . $questionID . '&dataSourceId=' . $dataSourceId . '&optionID=' . $question_rankID . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $question_rankID . '");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;

		case '19':
		case '20':
		case '21':
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionArray[] = $question_checkboxID;
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionArray[] = 0;
			}

			$theFalshContent = '';

			foreach ($optionArray as $question_checkboxID) {
				$theDivContent .= '<div id="flashcontent' . $question_checkboxID . '"><b>You need to upgrade your Flash Player</b></div>';
				$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $question_checkboxID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
				$theJSContent .= 'so.addVariable("path", "../Chart/");';
				$theJSContent .= 'so.addVariable("chart_id", "ampie' . $question_checkboxID . '");';
				$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_checkboxID . '"));';
				$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_checkboxID . '"));';
				$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
				$theJSContent .= 'so.addParam("wmode", "opaque");';
				$theJSContent .= 'so.write("flashcontent' . $question_checkboxID . '");';
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;

		case '26':
			$theFalshContent = $theDivContent = $theJSContent = '';

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
					$theDivContent .= '<div id="flashcontent' . $question_range_optionID . $question_range_labelID . '"><b>You need to upgrade your Flash Player</b></div>';
					$theJSContent .= 'var so = new SWFObject("../Chart/AmPie.swf?cache=0", "ampie' . $question_range_optionID . $question_range_labelID . '", "' . $chartWidth . '", "' . $chartHeight . '", "8", "#FFFFFF");';
					$theJSContent .= 'so.addVariable("path", "../Chart/");';
					$theJSContent .= 'so.addVariable("chart_id", "ampie' . $question_range_optionID . $question_range_labelID . '");';
					$theJSContent .= 'so.addVariable("settings_file", escape("../Chart/PiesSetting.php?chartType=' . $_POST['chartType'] . '&dataSourceId=' . $dataSourceId . '&surveyID=' . $surveyID . '&questionID=' . $questionID . '&optionID=' . $question_range_optionID . '&labelID=' . $question_range_labelID . '"));';
					$theJSContent .= 'so.addVariable("data_file", escape("../Chart/PiesData.php?surveyID=' . $surveyID . '&dataSourceId=' . $dataSourceId . '&questionID=' . $questionID . '&optionID=' . $question_range_optionID . '&labelID=' . $question_range_labelID . '"));';
					$theJSContent .= 'so.addVariable("preloader_color", "#999999");';
					$theJSContent .= 'so.addParam("wmode", "opaque");';
					$theJSContent .= 'so.write("flashcontent' . $question_range_optionID . $question_range_labelID . '");';
				}
			}

			$theFalshContent .= $theDivContent;
			$theFalshContent .= '<script type="text/javascript">';
			$theFalshContent .= $theJSContent;
			$theFalshContent .= '</script>';
			break;
		}

		break;
	}

	echo $theFalshContent;
	exit();
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$EnableQCoreClass->setTemplateFile('DataChartFile', 'DataChart.html');
$thisURL = 'DataChart.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('dataChartURL', $thisURL);
$theSurveyID = ($_GET['surveyID'] == '' ? 0 : (int) $_GET['surveyID']);
$EnableQCoreClass->replace('surveyID', $theSurveyID);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) ORDER BY surveyID DESC ';
	break;

case '2':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' ORDER BY surveyID DESC ';
	break;

case '3':
case '7':
	$AuthSQL = ' SELECT surveyID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	$AuthResult = $DB->query($AuthSQL);
	$SurveyArray = array();

	while ($AuthRow = $DB->queryArray($AuthResult)) {
		$SurveyArray[] = $AuthRow['surveyID'];
	}

	if (!empty($SurveyArray)) {
		$SurveyList = implode(',', $SurveyArray);
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND surveyID IN (' . $SurveyList . ') ORDER BY surveyID DESC ';
	}
	else {
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
	}

	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID IN (' . $UserIDList . ')';
	break;

default:
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ORDER BY surveyID DESC ';
	break;
}

$Result = $DB->query($SQL);
$surveyIDList = '';

while ($SuRow = $DB->queryArray($Result)) {
	if ($SuRow['surveyID'] == $_GET['surveyID']) {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '" selected>' . $SuRow['surveyTitle'] . '</option>';
	}
	else {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '">' . $SuRow['surveyTitle'] . '</option>';
	}
}

$EnableQCoreClass->replace('surveyIDList', $surveyIDList);
$EnableQCoreClass->replace('m_questionID', '');
$EnableQCoreClass->replace('m_questionName', $lang['pls_select']);
$DataChart = $EnableQCoreClass->parse('DataChart', 'DataChartFile');
echo $DataChart;
exit();

?>
