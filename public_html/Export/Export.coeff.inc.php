<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$SQL = ' SELECT status,surveyID,surveyName,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$theDownLoadFileName = 'Survey_Coeff_Result_' . $Row['surveyName'] . '_' . date('Y-m-d') . '.csv';
$theSID = $surveyID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_POST['Action'] == 'ExportDataSubmit') {
	if (isset($_POST['exportSQL']) && ($_POST['exportSQL'] != '')) {
		$dataSource = getdatasourcesql($_POST['exportSQL'], $_GET['surveyID']);
		$dataSourceId = $_POST['exportSQL'];
	}
	else {
		$dataSource = getdatasourcesql(0, $_GET['surveyID']);
		$dataSourceId = 0;
	}

	$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $dataSource;
	$CountRow = $DB->queryFirstRow($SQL);
	$totalResponseNum = $CountRow['totalResponseNum'];
	ob_start();
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=' . $theDownLoadFileName . '');
	$content = '"' . 'N=' . $totalResponseNum . '"';
	$header = ',""';
	$header .= "\r\n";
	$header .= '"' . $lang['export_coeff_var'] . '"';
	$header .= ',"' . $lang['export_coeff_vaule'] . '"';
	$header .= "\r\n";
	$content .= $header;

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (in_array($theQtnArray['questionType'], array('1', '2', '3', '4', '6', '7', '10', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '28'))) {
			switch ($_POST['exportType']) {
			case 1:
			default:
				$PlugInName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.export.coeff.php';
				break;

			case 2:
				if (0 < count($_POST['exportQtnList'])) {
					if (in_array($questionID, $_POST['exportQtnList'])) {
						$PlugInName = $Module[$theQtnArray['questionType']];
						require ROOT_PATH . 'PlugIn/' . $PlugInName . '/Admin/' . $PlugInName . '.export.coeff.php';
					}
				}

				break;
			}
		}
	}

	echo $content;
	exit();
}

$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'DataVariable.html');
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('exportQtnList', '');
$_GET['dataSourceId'] = (int) $_GET['dataSourceId'];
$EnableQCoreClass->replace('exportSQL', $_GET['dataSourceId']);
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (in_array($theQtnArray['questionType'], array('1', '2', '3', '4', '6', '7', '10', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '28'))) {
		if ($theQtnArray['questionType'] == '4') {
			if ($theQtnArray['isCheckType'] == '4') {
				$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}
		else if ($theQtnArray['questionType'] == '23') {
			$isHaveNumber = false;

			foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
				if ($theQuestionArray['isCheckType'] == 4) {
					$isHaveNumber = true;
					break;
				}
			}

			if ($isHaveNumber == true) {
				$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}
		else {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
$EnableQCoreClass->output('ExportVariablePage');

?>
