<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isOnline0View,isOnline0Auth,isViewAuthData,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
$thisProg = 'Frequency.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);

if ($_POST['Action'] == 'ExportDataSubmit') {
	include_once ROOT_PATH . 'Includes/HTML2DOC.class.php';
	$htmltodoc = new HTML_TO_DOC();
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23);
	$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
	$SerialRow = $DB->queryFirstRow($SQL);
	$PrintURL = $All_Path . 'Archive/ResultPageArchive.php?isPrint=1&qname=' . trim($_GET['surveyName']) . '&hash_code=' . md5(trim($SerialRow['license']));
	$PrintURL .= '&dataSourceId=' . $_POST['dataSourceId'];
	$PrintURL .= '&roleType=' . $_SESSION['adminRoleType'] . '$$$' . $_SESSION['adminRoleGroupType'] . '$$$' . implode('***', $_SESSION['adminSameGroupUsers']);

	switch ($_POST['exportType']) {
	case 1:
	default:
		$PrintURL .= '&printType=all';
		break;

	case 2:
		if (0 < count($_POST['exportQtnList'])) {
			$PrintURL .= '&printType=' . implode(',', $_POST['exportQtnList']);
		}

		break;
	}

	$htmltodoc->createDocFromURL($PrintURL, 'Survey_Result_' . trim($_GET['surveyName']), $download = true);
	exit();
}

if ($_GET['DO'] == 'PrintWord') {
	$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'PrintVariable.html');
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('exportQtnList', '');
	$EnableQCoreClass->replace('dataSourceId', $_GET['dataSourceId']);
	$questionList = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12')) {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
	$EnableQCoreClass->output('ExportVariablePage');
}

@set_time_limit(0);
$EnableQCoreClass->setTemplateFile('QuestionListFile', 'Frequency.html');
$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if (isset($_POST['dataSource'])) {
	$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
}

if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
	$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
	$dataSourceId = $_SESSION['dataSource' . $_GET['surveyID']];
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
	$dataSourceId = 0;
}

$EnableQCoreClass->replace('printURL', $thisProg . '&surveyName=' . $Sur_G_Row['surveyName'] . '&DO=PrintWord&dataSourceId=' . $dataSourceId);
$SQL = ' SELECT COUNT(*) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
$CountRow = $DB->queryFirstRow($SQL);
$totalResponseNum = $CountRow['totalResponseNum'];
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);
$thePageSurveyID = $_GET['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$surveyPageQtnList = array();
$pageID = 0;

for (; $pageID <= count($pageQtnList); $pageID++) {
	if (!empty($pageQtnList[$pageID]) && isset($pageQtnList[$pageID])) {
		$surveyPageQtnList[] = $pageQtnList[$pageID];
	}
}

$recordCount = count($surveyPageQtnList);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = $_GET['pageID'] - 1;
	$start = ($start < 0 ? 0 : $start);
}

foreach ($surveyPageQtnList[$start] as $questionID) {
	$theQtnArray = $QtnListArray[$questionID];
	$surveyID = $_GET['surveyID'];
	$ModuleName = $Module[$theQtnArray['questionType']];

	if ($theQtnArray['questionType'] != 12) {
		if ($theQtnArray['questionType'] != 9) {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
		}
		else {
			require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
		}

		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}

$EnableQCoreClass->replace('isComb', 0);
$EnableQCoreClass->replace('pageID', isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$EnableQCoreClass->replace('dataSourceId', $dataSourceId);

if (1 < $recordCount) {
	include_once ROOT_PATH . 'Includes/Pages.class.php';
	$PAGES = new PageBar($recordCount, 1);
	$pagebar = $PAGES->whole_num_bar('', '', $page_others);
	$EnableQCoreClass->replace('pagesList', '<div class="pages">' . $pagebar . '</div>');
	$EnableQCoreClass->replace('marginTop', 'margin-top:8px;');
}
else {
	$EnableQCoreClass->replace('pagesList', '');
	$EnableQCoreClass->replace('marginTop', '');
}

$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23);
$QuestionList = str_replace($All_Path, '', $QuestionList);
$QuestionList = str_replace('PerUserData/', '../PerUserData/', $QuestionList);
$QuestionList = str_replace('JS/', '../JS/', $QuestionList);
echo $QuestionList;
exit();

?>
