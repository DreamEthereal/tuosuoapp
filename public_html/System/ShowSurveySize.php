<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Config/DBSizeConfig.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyTitle,dbSize FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	_showerror($lang['system_error'], $lang['no_design_survey']);
}

if ($_POST['Action'] == 'DbSizeSubmit') {
	$theDbSizeList = '';

	if ($_POST['dbSize0'] != '') {
		$theDbSizeList .= $_POST['dbSize0'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['otherchar'])) . ',';
	}

	if ($_POST['dbSize1'] != '') {
		$theDbSizeList .= $_POST['dbSize1'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['optionchar'])) . ',';
	}

	if ($_POST['dbSize2'] != '') {
		$theDbSizeList .= $_POST['dbSize2'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['whychar'])) . ',';
	}

	if ($_POST['dbSize3'] != '') {
		$theDbSizeList .= $_POST['dbSize3'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['mtchar'])) . ',';
	}

	if ($_POST['dbSize4'] != '') {
		$theDbSizeList .= $_POST['dbSize4'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['varchar'])) . ',';
	}

	if ($_POST['dbSize5'] != '') {
		$theDbSizeList .= $_POST['dbSize5'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['listchar'])) . ',';
	}

	if ($_POST['dbSize6'] != '') {
		$theDbSizeList .= $_POST['dbSize6'] . ',';
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['multichar'])) . ',';
	}

	$theDbSizeList .= '120,255,';

	if ($_POST['dbSize9'] != '') {
		$theDbSizeList .= $_POST['dbSize9'];
	}
	else {
		$theDbSizeList .= str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['char']));
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET dbSize = \'' . $theDbSizeList . '\' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['size_survey'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['size_survey'] . ':' . $Sur_G_Row['surveyTitle'], false);
}

$EnableQCoreClass->setTemplateFile('SurveyDbSizeFile', 'SurveyDbSize.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);

if (trim($Sur_G_Row['dbSize']) == '') {
	$EnableQCoreClass->replace('dbSize0', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['otherchar'])));
	$EnableQCoreClass->replace('dbSize1', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['optionchar'])));
	$EnableQCoreClass->replace('dbSize2', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['whychar'])));
	$EnableQCoreClass->replace('dbSize3', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['mtchar'])));
	$EnableQCoreClass->replace('dbSize4', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['varchar'])));
	$EnableQCoreClass->replace('dbSize5', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['listchar'])));
	$EnableQCoreClass->replace('dbSize6', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['multichar'])));
	$EnableQCoreClass->replace('dbSize9', str_replace('varchar(', '', str_replace(') BINARY ', '', $Size['char'])));
}
else {
	$theDbSize = explode(',', $Sur_G_Row['dbSize']);
	$EnableQCoreClass->replace('dbSize0', $theDbSize[0]);
	$EnableQCoreClass->replace('dbSize1', $theDbSize[1]);
	$EnableQCoreClass->replace('dbSize2', $theDbSize[2]);
	$EnableQCoreClass->replace('dbSize3', $theDbSize[3]);
	$EnableQCoreClass->replace('dbSize4', $theDbSize[4]);
	$EnableQCoreClass->replace('dbSize5', $theDbSize[5]);
	$EnableQCoreClass->replace('dbSize6', $theDbSize[6]);
	$EnableQCoreClass->replace('dbSize9', $theDbSize[9]);
}

$SurveyDbSize = $EnableQCoreClass->parse('SurveyDbSize', 'SurveyDbSizeFile');
echo $SurveyDbSize;
exit();

?>
