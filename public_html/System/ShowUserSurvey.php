<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(6);
$thisProg = 'ShowUserSurvey.php';

if ($_SESSION['surveyListURL'] != '') {
	$EnableQCoreClass->replace('surveyListURL', $_SESSION['surveyListURL']);
	$surveyListURL = $_SESSION['surveyListURL'];
}
else {
	$EnableQCoreClass->replace('surveyListURL', $thisProg);
	$surveyListURL = $thisProg;
}

$EnableQCoreClass->setTemplateFile('MainPageFile', 'UserSurveyList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$EnableQCoreClass->replace('nick_Name', $_SESSION['administratorsName']);
$ConfigRow['topicNum'] = 10;
$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('cate_list', _getcateslist());
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE status != 0 ';
$EnableQCoreClass->replace('users_list', _getuserslist(0));
$Result = $DB->query($SQL);
$totalNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalNum', $totalNum);

if ($_POST['Action'] == 'querySubmit') {
	switch ($_POST['t_searchType']) {
	case '1':
	default:
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_POST['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}

	if ($_POST['t_status'] != '') {
		$SQL .= ' AND status = \'' . $_POST['t_status'] . '\' ';
		$EnableQCoreClass->replace('option_' . $_POST['t_status'], 'selected');
		$page_others .= '&t_status=' . $_POST['t_status'];
	}

	if ($_POST['t_cate'] != '') {
		$CateSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID = \'' . $_POST['t_cate'] . '\' ORDER BY surveyID ASC ';
		$CateResult = $DB->query($CateSQL);
		$CateArray = array();

		while ($CateRow = $DB->queryArray($CateResult)) {
			$CateArray[] = $CateRow['surveyID'];
		}

		if (empty($CateArray)) {
			$SQL .= ' AND surveyID= 0 ';
		}
		else {
			$CateIDList = implode(',', array_unique($CateArray));
			$SQL .= ' AND surveyID IN (' . $CateIDList . ')';
		}

		unset($CateArray);
		$EnableQCoreClass->replace('cate_list', _getcateslist($_POST['t_cate']));
		$page_others .= '&t_cate=' . $_POST['t_cate'];
	}

	if ($_POST['t_administratorsID'] != '0') {
		$SQL .= ' AND administratorsID = \'' . $_POST['t_administratorsID'] . '\' ';
		$page_others .= '&t_administratorsID=' . $_POST['t_administratorsID'];
		$EnableQCoreClass->replace('users_list', _getuserslist($_POST['t_administratorsID']));
	}
}

if (isset($_GET['t_name']) && !$_POST['Action'] && ($_GET['t_searchType'] != '')) {
	switch ($_GET['t_searchType']) {
	case '1':
	default:
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyTitle LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', '');
		$page_others = '&t_searchType=1&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;

	case '2':
		$name = trim($_GET['t_name']);
		$SQL .= ' AND surveyName LIKE BINARY \'%' . $name . '%\' ';
		$EnableQCoreClass->replace('isQName', 'selected');
		$page_others = '&t_searchType=2&t_name=' . urlencode($name);
		$EnableQCoreClass->replace('t_name', $name);
		break;
	}
}

if (isset($_GET['t_administratorsID']) && ($_GET['t_administratorsID'] != '0') && !$_POST['Action']) {
	$SQL .= ' AND administratorsID = \'' . $_GET['t_administratorsID'] . '\' ';
	$page_others .= '&t_administratorsID=' . $_GET['t_administratorsID'];
	$EnableQCoreClass->replace('users_list', _getuserslist($_GET['t_administratorsID']));
}

if (isset($_GET['t_status']) && ($_GET['t_status'] != '') && !$_POST['Action']) {
	$SQL .= ' AND status = \'' . $_GET['t_status'] . '\' ';
	$EnableQCoreClass->replace('option_' . $_GET['t_status'], 'selected');
	$page_others .= '&t_status=' . $_GET['t_status'];
}

if (isset($_GET['t_cate']) && ($_GET['t_cate'] != '') && !$_POST['Action']) {
	$CateSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID = \'' . $_GET['t_cate'] . '\' ORDER BY surveyID ASC ';
	$CateResult = $DB->query($CateSQL);
	$CateArray = array();

	while ($CateRow = $DB->queryArray($CateResult)) {
		$CateArray[] = $CateRow['surveyID'];
	}

	if (empty($CateArray)) {
		$SQL .= ' AND surveyID= 0 ';
	}
	else {
		$CateIDList = implode(',', array_unique($CateArray));
		$SQL .= ' AND surveyID IN (' . $CateIDList . ')';
	}

	unset($CateArray);
	$EnableQCoreClass->replace('cate_list', _getcateslist($_GET['t_cate']));
	$page_others .= '&t_cate=' . $_GET['t_cate'];
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$BackURL = $thisProg . '?pageID=' . $pageID . $page_others;
$_SESSION['surveyListURL'] = $BackURL;
$SQL .= ' ORDER BY surveyID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);
$isHaveCreateSite = 0;

while ($Row = $DB->queryArray($Result)) {
	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->replace('projectType', 'background:#fff url(../Images/iblue.png) repeat-y top left');
	}
	else {
		$EnableQCoreClass->replace('projectType', '');
	}

	$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', qnohtmltag($Row['surveyTitle']));
	$EnableQCoreClass->replace('endTime', substr($Row['endTime'], 2));
	$AdminSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
	$UserRow = $DB->queryFirstRow($AdminSQL);

	if (!$UserRow) {
		$EnableQCoreClass->replace('owner', $lang['deleted_user']);
	}
	else {
		$EnableQCoreClass->replace('owner', $UserRow['administratorsName']);
	}

	$EnableQCoreClass->replace('status', $lang['status_' . $Row['status']]);
	$EnableQCoreClass->replace('isPublic', $lang['isPublic_' . $Row['isPublic']]);

	switch ($Row['status']) {
	case '1':
		$EnableQCoreClass->replace('status_color', 'background:#efefef url(../Images/igreen.png) repeat-y top left');
		$EnableQCoreClass->replace('topMargin', 160);
		break;

	case '2':
		$EnableQCoreClass->replace('status_color', 'background:#efefef url(../Images/ired.png) repeat-y top left');
		$EnableQCoreClass->replace('topMargin', 160);
		break;
	}

	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);
	$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);
	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('MainPage', 'MainPageFile');
$EnableQCoreClass->output('MainPage', false);

?>
