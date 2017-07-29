<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $theSurveyID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
$questionID = $_GET['questionID'];
$theLabel = array();

switch ($QtnListArray[$questionID]['questionType']) {
case '6':
case '19':
case '26':
	foreach ($AnswerListArray[$questionID] as $question_range_answerID => $theAnswerArray) {
		$theLabel[] = iconv('gbk', 'UTF-8', qshowchartname($theAnswerArray['optionAnswer']));
	}

	break;

case '10':
	$optionOrderNum = count($RankListArray[$questionID]);

	if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
		$optionOrderNum++;
	}

	$l = 1;

	for (; $l <= $optionOrderNum; $l++) {
		$theLabel[] = $l;
	}

	break;

case '20':
	$theBaseID = $QtnListArray[$questionID]['baseID'];
	$theBaseQtnArray = $QtnListArray[$theBaseID];
	$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
	$optionOrderNum = count($theCheckBoxListArray);

	if ($theBaseQtnArray['isHaveOther'] == 1) {
		$optionOrderNum++;
	}

	$l = 1;

	for (; $l <= $optionOrderNum; $l++) {
		$theLabel[] = $l;
	}

	break;

case '15':
case '21':
	$l = $QtnListArray[$questionID]['endScale'];

	for (; $QtnListArray[$questionID]['startScale'] <= $l; $l--) {
		$theLabel[] = $QtnListArray[$questionID]['weight'] * $l;
	}

	if ($QtnListArray[$questionID]['isHaveUnkown'] == '1') {
		$theLabel[] = iconv('gbk', 'UTF-8', $lang['rating_unknow']);
	}

	break;
}

$ColorJoin = array('DA3600', '0F84D4', 'F9A308', '62D038', 'FE670F', '2C9232', '7F0B80', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');
$EnableQCoreClass->setTemplateFile('AmStackSettingFile', 'AmStackSetting.xml');

if ($_GET['chartType'] == 6) {
	$EnableQCoreClass->replace('lineType', 'bar');
	$EnableQCoreClass->replace('lineTypeLeft', 150);
	$EnableQCoreClass->replace('lineTypeRight', 20);
	$EnableQCoreClass->replace('lineTypeBottom', 20);
}
else {
	$EnableQCoreClass->replace('lineType', 'column');
	$EnableQCoreClass->replace('lineTypeLeft', 50);
	$EnableQCoreClass->replace('lineTypeRight', 10);
	$EnableQCoreClass->replace('lineTypeBottom', 100);
}

$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', qshowchartqtnname($QtnListArray[$questionID]['questionName'])));
$EnableQCoreClass->set_CycBlock('AmStackSettingFile', 'LABEL', 'label');
$EnableQCoreClass->replace('label', '');
$temp = 0;

foreach ($theLabel as $labelName) {
	$EnableQCoreClass->replace('labelName', $labelName);

	if ($ColorJoin[$temp] != '') {
		$EnableQCoreClass->replace('color', $ColorJoin[$temp]);
	}
	else {
		$key = array_rand($ColorJoin);
		$EnableQCoreClass->replace('color', $ColorJoin[$key]);
	}

	$EnableQCoreClass->parse('label', 'LABEL', true);
	$temp++;
}

$AmStackSetting = $EnableQCoreClass->parse('AmStackSetting', 'AmStackSettingFile');
unset($theLabel);
echo $AmStackSetting;
exit();

?>
