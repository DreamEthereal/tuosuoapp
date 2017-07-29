<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart/');
$EnableQCoreClass->setTemplateFile('DataFile', 'AmAreaData.xml');
$EnableQCoreClass->set_CycBlock('DataFile', 'SERIES', 'series');
$EnableQCoreClass->replace('series', '');
$EnableQCoreClass->set_CycBlock('DataFile', 'GRAPHS', 'graphs');
$EnableQCoreClass->set_CycBlock('GRAPHS', 'DATA', 'data');
$EnableQCoreClass->replace('graphs', '');
$EnableQCoreClass->replace('data', '');
$ColorJoin = array('DA3600', '0F84D4', 'ffcc33', '62D038', '7F0B80', 'FE670F', '2C9232', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');
$i = 0;

foreach ($_SESSION['seriesvalue0'] as $thisPrice) {
	$EnableQCoreClass->replace('xid', $i);
	$EnableQCoreClass->replace('seriesvalue', iconv('gbk', 'UTF-8', $thisPrice));
	$i++;
	$EnableQCoreClass->parse('series', 'SERIES', true);
}

$j = 0;

for (; $j <= 2; $j++) {
	$EnableQCoreClass->replace('color', $ColorJoin[$j]);

	switch ($j) {
	case 0:
		$EnableQCoreClass->replace('title', iconv('gbk', 'UTF-8', '可接受区域'));
		$k = 0;

		foreach ($_SESSION['theAcceptable'] as $thisPrice => $theAcceptableRate) {
			$EnableQCoreClass->replace('order', $k);
			$EnableQCoreClass->replace('datavalue', $theAcceptableRate);
			$k++;
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		break;

	case 1:
		$EnableQCoreClass->replace('title', iconv('gbk', 'UTF-8', '有保留接受区域'));
		$k = 0;

		foreach ($_SESSION['theRetention'] as $thisPrice => $theRetentionRate) {
			$EnableQCoreClass->replace('order', $k);
			$EnableQCoreClass->replace('datavalue', $theRetentionRate);
			$k++;
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		break;

	case 2:
		$EnableQCoreClass->replace('title', iconv('gbk', 'UTF-8', '不可接受区域'));
		$k = 0;

		foreach ($_SESSION['theUnacceptable'] as $thisPrice => $theUnacceptableRate) {
			$EnableQCoreClass->replace('order', $k);
			$EnableQCoreClass->replace('datavalue', $theUnacceptableRate);
			$k++;
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		break;
	}

	$EnableQCoreClass->parse('graphs', 'GRAPHS', true);
	$EnableQCoreClass->unreplace('data');
}

unset($_SESSION['seriesvalue0']);
unset($_SESSION['theAcceptable']);
unset($_SESSION['theRetention']);
unset($_SESSION['theUnacceptable']);
$Data = $EnableQCoreClass->parse('Data', 'DataFile');
echo $Data;
exit();

?>
