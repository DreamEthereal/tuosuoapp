<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$thisProg = 'l.php?uname=' . $_GET['uname'] . '&pub=' . $_GET['pub'] . '&onlyname=' . $_GET['onlyname'] . '&pnum=' . $_GET['pnum'];
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);
$hash_Code = md5(trim($SerialRow['license']));

if (trim($_GET['hash']) != md5(trim($SerialRow['license']) . 'EnableQ')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uSurveyList.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');

if ($_GET['pnum'] == '') {
	$pageNum = 20;
}
else {
	$pageNum = (int) $_GET['pnum'];
	$pageNum = ($pageNum < 0 ? 20 : $pageNum);
}

$SQL = ' SELECT a.surveyName,a.surveyTitle,a.surveySubTitle,a.surveyInfo,a.lang FROM ' . SURVEY_TABLE . ' a,' . ADMINISTRATORS_TABLE . ' b WHERE a.status =1 AND a.administratorsID = b.administratorsID AND a.beginTime <= \'' . date('Y-m-d') . '\' ';

if ($_GET['uname'] != '') {
	$SQL .= ' AND b.administratorsName = \'' . $_GET['uname'] . '\' ';
}

if ($_GET['pub'] != '') {
	$SQL .= ' AND a.isPublic = \'' . $_GET['pub'] . '\' ';
}

if (($_GET['task'] == '2') && ($_GET['url'] != '') && ($_GET['username'] != '')) {
	if (strpos(trim($_GET['url']), '?') === false) {
		$ajaxURL = trim($_GET['url']) . '?username=' . trim($_GET['username']) . '&hash=' . $hash_Code;
	}
	else {
		$allURL = str_replace('*', '&', trim($_GET['url']));
		$ajaxURL = $allURL . '&username=' . trim($_GET['username']) . '&hash=' . $hash_Code;
	}

	$File = fopen($ajaxURL, 'r');
	$buffer = '';
	$AjaxRtnContent = '';

	while ($buffer = fgets($File, 4096)) {
		$AjaxRtnContent = $AjaxRtnContent . $buffer;
	}

	fclose($File);

	if ($AjaxRtnContent != '') {
		$SQL .= ' AND a.surveyID IN (' . trim($AjaxRtnContent) . ') ';
	}
	else {
		$SQL .= ' AND a.surveyID =0 ';
	}
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $pageNum;
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY a.beginTime DESC,a.surveyID DESC LIMIT ' . $start . ',' . $pageNum . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	if (!isset($_GET['onlyname']) || ($_GET['onlyname'] == 1)) {
		$EnableQCoreClass->replace('isHaveInfo', 'none');
		$EnableQCoreClass->replace('surveyInfo', '');
	}
	else {
		$EnableQCoreClass->replace('isHaveInfo', 'block');

		if ($Row['surveyInfo'] != '') {
			$EnableQCoreClass->replace('surveyInfo', $Row['surveyInfo']);
		}
		else {
			$EnableQCoreClass->replace('surveyInfo', $Row['surveySubTitle']);
		}
	}

	if (($_GET['task'] == '2') && ($_GET['url'] != '') && ($_GET['username'] != '')) {
		$_SESSION['hash_Code'] = $hash_Code;
		$EnableQCoreClass->replace('surveyURL', 'x.php?qname=' . $Row['surveyName'] . '&username=' . trim($_GET['username']) . '&hash=' . $hash_Code);
	}

	if ($_GET['task'] == 'n4') {
		$_SESSION['hash_Code'] = $hash_Code;
		$EnableQCoreClass->replace('surveyURL', 'x.php?task=n4&qname=' . $Row['surveyName'] . '&username=' . urlencode(trim($_SESSION['membersName'])) . '&hash=' . $hash_Code);
	}
	else {
		$EnableQCoreClass->replace('surveyURL', 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang']);
	}

	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

$EnableQCoreClass->replace('totalNum', $recordCount);
$EnableQCoreClass->replace('numPerPage', $pageNum);

if ($pageNum == 0) {
	$EnableQCoreClass->replace('numPage', 0);
}
else {
	$EnableQCoreClass->replace('numPage', ceil($recordCount / $pageNum));
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $pageNum);
$pagebar = $PAGES->whole_num_bar();
$EnableQCoreClass->replace('pagesList', $pagebar);
$SurveyList = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $SurveyList;

?>
