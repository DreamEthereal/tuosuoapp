<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
_checkroletype(1);
$thisProg = 'ShowSurveyCate.php';

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . SURVEYCATE_TABLE . ' WHERE cateID=\'' . $_GET['cateID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID=\'' . $_GET['cateID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_survey_cate'] . ':' . $_GET['cateName']);
	_showsucceed($lang['dele_survey_cate'] . ':' . $_GET['cateName'], $thisProg);
}

if ($_POST['Action'] == 'SurveyCateEditSubmit') {
	$SQL = ' SELECT cateTag FROM ' . SURVEYCATE_TABLE . ' WHERE cateTag=\'' . trim($_POST['cateTag']) . '\'  AND cateTag!=\'' . $_POST['ori_cateTag'] . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['catetag_is_exist']);
	}

	$SQL = ' UPDATE ' . SURVEYCATE_TABLE . ' SET cateTag=\'' . trim($_POST['cateTag']) . '\',cateName=\'' . trim($_POST['cateName']) . '\' WHERE cateID=\'' . $_POST['cateID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['cateSurveyList'] != '') {
		$SQL = ' DELETE FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID=\'' . $_POST['cateID'] . '\' ';
		$DB->query($SQL);

		foreach ($_POST['cateSurveyList'] as $surveyID) {
			$SQL = ' INSERT INTO ' . SURVEYCATELIST_TABLE . ' SET cateID = \'' . $_POST['cateID'] . '\',surveyID=\'' . $surveyID . '\' ';
			$DB->query($SQL);
		}
	}

	writetolog($lang['edit_survey_cate'] . ':' . $_POST['cateName']);
	_showsucceed($lang['edit_survey_cate'] . ':' . $_POST['cateName'], $thisProg);
}

if ($_GET['Action'] == 'Edit') {
	$SQL = ' SELECT * FROM ' . SURVEYCATE_TABLE . ' WHERE cateID=\'' . $_GET['cateID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyCateEditPageFile', 'SurveyCateEdit.html');
	$EnableQCoreClass->replace('cateTag', $Row['cateTag']);
	$EnableQCoreClass->replace('cateName', $Row['cateName']);
	$EnableQCoreClass->replace('cateID', $Row['cateID']);
	$SQL = ' SELECT surveyName,surveyTitle,surveyID FROM ' . SURVEY_TABLE . ' WHERE status != 2 ORDER BY surveyID DESC ';
	$Result = $DB->query($SQL);
	$surveyNameList = '';
	$cateSurveyList = '';

	while ($Row = $DB->queryArray($Result)) {
		$HaveSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE cateID = \'' . $_GET['cateID'] . '\' AND surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($HaveSQL);

		if ($HaveRow) {
			$cateSurveyList .= '<option value="' . $Row['surveyID'] . '">' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		}
		else {
			$surveyNameList .= '<option value="' . $Row['surveyID'] . '">' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('surveyNameList', $surveyNameList);
	$EnableQCoreClass->replace('cateSurveyList', $cateSurveyList);
	$EnableQCoreClass->parse('SurveyCateEditPage', 'SurveyCateEditPageFile');
	$EnableQCoreClass->output('SurveyCateEditPage', false);
}

if ($_POST['Action'] == 'SurveyCateAddSubmit') {
	$SQL = ' SELECT cateTag FROM ' . SURVEYCATE_TABLE . ' WHERE cateTag=\'' . trim($_POST['cateTag']) . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		_showerror($lang['error_system'], $lang['catetag_is_exist']);
	}

	$SQL = ' INSERT INTO ' . SURVEYCATE_TABLE . ' SET cateTag=\'' . trim($_POST['cateTag']) . '\',cateName=\'' . trim($_POST['cateName']) . '\' ';
	$DB->query($SQL);
	writetolog($lang['add_survey_cate'] . ':' . $_POST['cateName']);
	_showsucceed($lang['add_survey_cate'] . ':' . $_POST['cateName'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('MainPageFile', 'SurveyCateList.html');
$EnableQCoreClass->set_CycBlock('MainPageFile', 'CATE', 'cate');
$EnableQCoreClass->replace('cate', '');
$SQL = ' SELECT * FROM ' . SURVEYCATE_TABLE . ' ';
$SQL .= ' ORDER BY cateID DESC ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('cateTag', $Row['cateTag']);
	$EnableQCoreClass->replace('cateName', $Row['cateName']);

	if ($Config['dataDomainName'] != '') {
		$cateURL = 'http://' . $Config['dataDomainName'] . '/c.php?name=' . $Row['cateTag'];
	}
	else {
		$cateURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25) . 'c.php?name=' . $Row['cateTag'];
	}

	$EnableQCoreClass->replace('cateURL', $cateURL);
	$EnableQCoreClass->replace('editURL', $thisProg . '?Action=Edit&cateID=' . $Row['cateID'] . '&cateName=' . urlencode($Row['cateName']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=Delete&cateID=' . $Row['cateID'] . '&cateName=' . urlencode($Row['cateName']));
	$EnableQCoreClass->parse('cate', 'CATE', true);
}

$MainPage = $EnableQCoreClass->parse('MainPage', 'MainPageFile');
echo $MainPage;

?>
