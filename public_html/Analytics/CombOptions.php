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
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$thisProg = 'CombOptions.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'ExportDataSubmit') {
	include_once ROOT_PATH . 'Includes/HTML2DOC.class.php';
	$htmltodoc = new HTML_TO_DOC();
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
	$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
	$SerialRow = $DB->queryFirstRow($SQL);
	$PrintURL = $All_Path . 'Archive/CombResultArchive.php?qname=' . trim($_GET['surveyName']) . '&hash_code=' . md5(trim($SerialRow['license']));
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

	$htmltodoc->createDocFromURL($PrintURL, 'Survey_Comb_Result_' . trim($_GET['surveyName']), $download = true);
	exit();
}

if (($S_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php')) {
	$theSID = $S_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $S_Row['surveyID'] . '/' . md5('Qtn' . $S_Row['surveyID']) . '.php';

if ($_GET['DO'] == 'PrintWord') {
	$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'PrintVariable.html');
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('exportQtnList', '');
	$EnableQCoreClass->replace('dataSourceId', $_GET['dataSourceId']);
	$questionList = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12') && ($theQtnArray['questionType'] != '30')) {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
	$EnableQCoreClass->output('ExportVariablePage');
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . COMBLIST_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . COMBNAME_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$DB->query($SQL);
	$questionName = qnospecialchar(stripslashes($_GET['questionName']), 1);
	writetolog($lang['dele_survey_comb'] . ':' . $questionName);
	_showsucceed($lang['dele_survey_comb'] . ':' . $questionName, $thisProg . '&DO=SettingComb');
}

if ($_POST['Action'] == 'CombNewSubmit') {
	if (is_array($_POST['optionID'])) {
		$SQL = ' SELECT combNameID FROM ' . COMBNAME_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND combName=\'' . trim($_POST['combName']) . '\' LIMIT 0,1 ';
		$CombNameRow = $DB->queryFirstRow($SQL);

		if (!$CombNameRow) {
			$SQL = ' INSERT INTO ' . COMBNAME_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',combName=\'' . trim($_POST['combName']) . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
			$DB->query($SQL);
			$newCombNameID = $DB->_GetInsertID();
		}
		else {
			$newCombNameID = $CombNameRow['combNameID'];
		}

		foreach ($_POST['optionID'] as $optionID) {
			$SQL = ' SELECT combListID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
			$isHaveRow = $DB->queryFirstRow($SQL);

			if (!$isHaveRow) {
				$SQL = ' INSERT INTO ' . COMBLIST_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',optionID=\'' . $optionID . '\',combNameID=\'' . $newCombNameID . '\' ';
				$DB->query($SQL);
			}
		}
	}

	writetolog($lang['new_survey_comb'] . ':' . $_GET['surveyTitle']);
	_showsucceed($lang['new_survey_comb'] . ':' . $_GET['surveyTitle'], $thisProg . '&DO=SettingComb');
}

if ($_GET['DO'] == 'SettingComb') {
	$EnableQCoreClass->setTemplateFile('SettingCombFile', 'CombOptionNew.html');
	$EnableQCoreClass->replace('questionListURL', $thisProg);
	$EnableQCoreClass->set_CycBlock('SettingCombFile', 'COMB', 'comb');
	$EnableQCoreClass->replace('comb', '');
	$SQL = ' SELECT questionID FROM ' . COMBLIST_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$Question[] = $Row['questionID'];
	}

	$questionIDList = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (!empty($Question) && in_array($questionID, $Question)) {
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']');
			$NameSQL = ' SELECT combNameID,combName FROM ' . COMBNAME_TABLE . ' WHERE questionID = \'' . $questionID . '\' ORDER BY questionID ASC ';
			$NameResult = $DB->query($NameSQL);
			$NameArray = array();

			while ($NameRow = $DB->queryArray($NameResult)) {
				$NameArray[] = $NameRow['combNameID'];
			}

			$combList = '';

			foreach ($NameArray as $combNameID) {
				$CombSQL = ' SELECT a.optionID,a.combNameID,b.combName FROM ' . COMBLIST_TABLE . ' a, ' . COMBNAME_TABLE . ' b WHERE a.questionID = \'' . $questionID . '\' AND a.combNameID=b.combNameID AND a.combNameID =\'' . $combNameID . '\' ORDER BY a.optionID ASC ';
				$CombResult = $DB->query($CombSQL);
				$CombTotal = $DB->_getNumRows($CombResult);

				if (2 <= $CombTotal) {
					$combList .= '<font color=blue><b>(</b></font><br/>';
				}

				while ($CombRow = $DB->queryArray($CombResult)) {
					switch ($theQtnArray['questionType']) {
					case '2':
					case '24':
						$combList .= qnospecialchar($RadioListArray[$questionID][$CombRow['optionID']]['optionName']) . '<br/>';
						break;

					case '3':
					case '25':
						$combList .= qnospecialchar($CheckBoxListArray[$questionID][$CombRow['optionID']]['optionName']) . '<br/>';
						break;

					case '18':
						$combList .= qnospecialchar($YesNoListArray[$questionID][$CombRow['optionID']]['optionName']) . '<br/>';
						break;

					case '6':
					case '7':
					case '19':
					case '26':
					case '28':
						$combList .= qnospecialchar($AnswerListArray[$questionID][$CombRow['optionID']]['optionAnswer']) . '<br/>';
						break;
					}

					$CombName = $CombRow['combName'];
				}

				if (2 <= $CombTotal) {
					$combList .= ' <font color=blue><b>)</b></font><br/>';
				}

				$combList .= '<b><font color=red>' . $lang['comb'] . '£º' . $CombName . '</font></b><br/>';
				$EnableQCoreClass->replace('combList', $combList);
				$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=Delete&questionID=' . $questionID . '&questionName=' . urlencode($theQtnArray['questionName']));
			}

			$EnableQCoreClass->parse('comb', 'COMB', true);
		}

		if (in_array($theQtnArray['questionType'], array(2, 3, 6, 7, 18, 19, 24, 25, 26, 28))) {
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$questionIDList .= '<option value=\'' . $questionID . '\'>' . $questionName . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ')</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('questionList', $questionIDList);
	$EnableQCoreClass->replace('m_optionID', '');
	$EnableQCoreClass->replace('m_optionName', $lang['pls_select']);
	$EnableQCoreClass->replace('Action', 'CombNewSubmit');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		$EnableQCoreClass->replace('isViewUser', 'none');
	}
	else {
		$EnableQCoreClass->replace('isViewUser', '');
	}

	$EnableQCoreClass->parse('SettingComb', 'SettingCombFile');
	$EnableQCoreClass->output('SettingComb');
}

@set_time_limit(0);
$EnableQCoreClass->setTemplateFile('QuestionListFile', 'CombOptionCount.html');
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

$EnableQCoreClass->replace('printURL', $thisProg . '&surveyName=' . $S_Row['surveyName'] . '&DO=PrintWord&dataSourceId=' . $dataSourceId);
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

	switch ($theQtnArray['questionType']) {
	case '1':
	case '4':
	case '5':
	case '10':
	case '11':
	case '13':
	case '14':
	case '15':
	case '16':
	case '20':
	case '21':
	case '22':
	case '23':
	case '27':
	case '29':
	case '31':
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
		break;

	case '2':
	case '3':
	case '6':
	case '7':
	case '18':
	case '19':
	case '24':
	case '25':
	case '26':
	case '28':
		$SQL = ' SELECT combListID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $questionID . '\' LIMIT 0,1 ';
		$isHaveRow = $DB->queryFirstRow($SQL);

		if (!$isHaveRow) {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
		}
		else {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.comb.inc.php';
		}

		break;

	case '17':
		$SQL = ' SELECT combListID FROM ' . COMBLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $theQtnArray['baseID'] . '\' LIMIT 0,1 ';
		$isHaveRow = $DB->queryFirstRow($SQL);

		if (!$isHaveRow) {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.count.inc.php';
		}
		else {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.comb.inc.php';
		}

		break;

	case '9':
		require ROOT_PATH . 'PlugIn/Info/Admin/Info.page.inc.php';
		break;
	}

	if (($theQtnArray['questionType'] != '12') && ($theQtnArray['questionType'] != '30')) {
		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}

$EnableQCoreClass->replace('isComb', 1);
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
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
$QuestionList = str_replace($All_Path, '', $QuestionList);
$QuestionList = str_replace('PerUserData/', '../PerUserData/', $QuestionList);
$QuestionList = str_replace('JS/', '../JS/', $QuestionList);
echo $QuestionList;
exit();

?>
