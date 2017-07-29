<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart/');
$EnableQCoreClass->setTemplateFile('DataFile', 'AmLineData.xml');
$EnableQCoreClass->set_CycBlock('DataFile', 'SERIES', 'series');
$EnableQCoreClass->replace('series', '');
$EnableQCoreClass->set_CycBlock('DataFile', 'GRAPHS', 'graphs');
$EnableQCoreClass->set_CycBlock('GRAPHS', 'DATA', 'data');
$EnableQCoreClass->replace('graphs', '');
$EnableQCoreClass->replace('data', '');
$ColorJoin = array('DA3600', '0F84D4', '62D038', '7F0B80', '9F9F9F', 'FE670F', '2C9232', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');
$i = 0;

foreach ($_SESSION['pricevalue'] as $thisPrice) {
	$EnableQCoreClass->replace('xid', $i);
	$EnableQCoreClass->replace('seriesvalue', iconv('gbk', 'UTF-8', qcrossqtnname($thisPrice)));
	$i++;
	$EnableQCoreClass->parse('series', 'SERIES', true);
}

$j = 0;

foreach ($_SESSION['titlename'] as $question_range_answerID => $thistitlename) {
	$EnableQCoreClass->replace('color', $ColorJoin[$j]);
	$j++;
	$EnableQCoreClass->replace('title', iconv('gbk', 'UTF-8', qcrossqtnname($thistitlename)));
	$k = 0;

	foreach ($_SESSION['thedatavalue'][$question_range_answerID] as $thePriceRate) {
		$EnableQCoreClass->replace('order', $k);
		$EnableQCoreClass->replace('datavalue', $thePriceRate);
		$k++;
		$EnableQCoreClass->parse('data', 'DATA', true);
	}

	$EnableQCoreClass->parse('graphs', 'GRAPHS', true);
	$EnableQCoreClass->unreplace('data');
}

unset($_SESSION['pricevalue']);
unset($_SESSION['thedatavalue']);
unset($_SESSION['titlename']);
$Data = $EnableQCoreClass->parse('Data', 'DataFile');
echo $Data;
exit();

?>
