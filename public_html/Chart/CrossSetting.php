<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$ColorJoin = array('DA3600', '0F84D4', 'F9A308', '62D038', 'FE670F', '2C9232', '7F0B80', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');

switch ($_GET['chartType']) {
case 5:
	$EnableQCoreClass->setTemplateFile('CrossSettingFile', 'AmStackSetting.xml');
	$EnableQCoreClass->replace('lineType', 'column');
	$EnableQCoreClass->replace('lineTypeLeft', 50);
	$EnableQCoreClass->replace('lineTypeRight', 10);
	$EnableQCoreClass->replace('lineTypeBottom', 100);
	break;

case 6:
	$EnableQCoreClass->setTemplateFile('CrossSettingFile', 'AmStackSetting.xml');
	$EnableQCoreClass->replace('lineType', 'bar');
	$EnableQCoreClass->replace('lineTypeLeft', 150);
	$EnableQCoreClass->replace('lineTypeRight', 20);
	$EnableQCoreClass->replace('lineTypeBottom', 20);
	break;

case 8:
	$EnableQCoreClass->setTemplateFile('CrossSettingFile', 'AmColumnsSetting.xml');
	$EnableQCoreClass->replace('lineType', 'column');
	$EnableQCoreClass->replace('lineTypeLeft', 40);
	$EnableQCoreClass->replace('lineTypeRight', 20);
	break;

case 9:
	$EnableQCoreClass->setTemplateFile('CrossSettingFile', 'AmColumnsSetting.xml');
	$EnableQCoreClass->replace('lineType', 'bar');
	$EnableQCoreClass->replace('lineTypeLeft', 150);
	$EnableQCoreClass->replace('lineTypeRight', 20);
	break;
}

$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', stripslashes($_GET['questionName'])));
$EnableQCoreClass->set_CycBlock('CrossSettingFile', 'LABEL', 'label');
$EnableQCoreClass->replace('label', '');

if ($_GET['type'] == '2') {
	$theLabel = $_SESSION['LabelName'][$_GET['dataID']];
}
else {
	$theLabel = $_SESSION['LabelName'];
}

$temp = 0;

foreach ($theLabel as $theLabelName) {
	$EnableQCoreClass->replace('labelName', iconv('gbk', 'UTF-8', $theLabelName));

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

$CrossSetting = $EnableQCoreClass->parse('CrossSetting', 'CrossSettingFile');
unset($theLabel);

if ($_GET['type'] == '2') {
	unset($_SESSION['LabelName'][$_GET['dataID']]);
}
else {
	unset($_SESSION['LabelName']);
}

echo $CrossSetting;
exit();

?>
