<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID,surveyID,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_POST['Action'] == 'ExportDataSubmit') {
	ob_start();

	switch ($_POST['exportType']) {
	case 1:
	default:
		$ResultList = export_spss($_POST['surveyID'], base64_decode($_SESSION['dataSQL']));
		break;

	case 2:
		if (0 < count($_POST['exportQtnList'])) {
			$ResultList = export_spss($_POST['surveyID'], base64_decode($_SESSION['dataSQL']), $_POST['exportQtnList']);
		}

		break;
	}

	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=SPSS_Text_Data_' . $_GET['surveyID'] . '_List_' . date('Y-m-d') . '.csv');
	echo $ResultList;
	exit();
}

$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'ExportVariable.html');
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('exportQtnList', '');
$EnableQCoreClass->replace('exportSQL', base64_decode($_SESSION['dataSQL']));
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
$EnableQCoreClass->output('ExportVariablePage');

?>
