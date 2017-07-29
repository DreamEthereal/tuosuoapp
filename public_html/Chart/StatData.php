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
$ColorJoin = array('DA3600', '0F84D4', '62D038', 'FE670F', '2C9232', '7F0B80', 'DFDE29', '9F9F9F', 'EDEDED', 'BAE700', 'FFFFCC', 'FFCC33', 'FFA144', 'CCFF99', 'CCCC99', 'FFCCCC', 'FF9999', 'FF7300', '66FF99', '669999', '86B47A', 'CC99CC', 'CC9966', '33CCFF', '079ED7', 'EEEEEE', 'FF0F00', 'FF6600', 'FF9E01', 'FCD202', 'F8FF01', 'B0DE09', '04D215', '0D8ECF', '0D52D1', '2A0CD0');

switch ($_GET['type']) {
case '1':
	$i = 0;

	for (; $i < 12; $i++) {
		$EnableQCoreClass->replace('xid', $i);
		$EnableQCoreClass->replace('seriesvalue', ($i + 1) . iconv('gbk', 'UTF-8', $lang['Count_month']));
		$EnableQCoreClass->parse('series', 'SERIES', true);
	}

	$j = 0;

	for (; $j <= 1; $j++) {
		$qYear = $_GET['today'] - $j;
		$EnableQCoreClass->replace('title', $qYear);
		$EnableQCoreClass->replace('color', $ColorJoin[$j]);
		$SQL = ' SELECT * FROM ' . COUNTYEARNUM_TABLE . ' WHERE TYear=\'' . $qYear . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$k = 1;

		for (; $k <= 12; $k++) {
			$m = $k - 1;
			$EnableQCoreClass->replace('order', $m);
			$EnableQCoreClass->replace('datavalue', $Row['m' . $k] == '' ? 0 : $Row['m' . $k]);
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		$EnableQCoreClass->parse('graphs', 'GRAPHS', true);
		$EnableQCoreClass->unreplace('data');
	}

	break;

case '2':
	$i = 0;

	for (; $i < 31; $i++) {
		$EnableQCoreClass->replace('xid', $i);
		$EnableQCoreClass->replace('seriesvalue', $i + 1);
		$EnableQCoreClass->parse('series', 'SERIES', true);
	}

	$j = 0;

	for (; $j <= 2; $j++) {
		$theMonth = explode('-', $_GET['today']);

		if (($theMonth[1] - $j) <= 0) {
			$qMonth = ($theMonth[0] - 1) . '-' . (($theMonth[1] - $j) + 12);
		}
		else {
			$qMonth = $theMonth[0] . '-' . ($theMonth[1] - $j);
		}

		$EnableQCoreClass->replace('title', $qMonth);
		$EnableQCoreClass->replace('color', $ColorJoin[$j]);
		$SQL = ' SELECT * FROM ' . COUNTMONTHNUM_TABLE . ' WHERE TMonth=\'' . $qMonth . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$k = 1;

		for (; $k <= 31; $k++) {
			$m = $k - 1;
			$EnableQCoreClass->replace('order', $m);
			$EnableQCoreClass->replace('datavalue', $Row['d' . $k] == '' ? 0 : $Row['d' . $k]);
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		$EnableQCoreClass->parse('graphs', 'GRAPHS', true);
		$EnableQCoreClass->unreplace('data');
	}

	break;

case '3':
	$i = 0;

	for (; $i < 24; $i++) {
		$EnableQCoreClass->replace('xid', $i);
		$EnableQCoreClass->replace('seriesvalue', substr($i + 100, 1) . ':00-' . substr($i + 101, 1) . ':00');
		$EnableQCoreClass->parse('series', 'SERIES', true);
	}

	$j = 0;

	for (; $j <= 6; $j++) {
		$theDay = explode('-', $_GET['today']);
		$theDayTime = mktime(0, 0, 0, $theDay[1], $theDay[2], $theDay[0]);
		$qDay = date('Y-n-j', $theDayTime - ($j * 86400));
		$EnableQCoreClass->replace('title', $qDay);
		$EnableQCoreClass->replace('color', $ColorJoin[$j]);
		$SQL = ' SELECT * FROM ' . COUNTDAYNUM_TABLE . ' WHERE TDay=\'' . $qDay . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$k = 0;

		for (; $k < 23; $k++) {
			$EnableQCoreClass->replace('order', $k);
			$EnableQCoreClass->replace('datavalue', $Row['h' . $k] == '' ? 0 : $Row['h' . $k]);
			$EnableQCoreClass->parse('data', 'DATA', true);
		}

		$EnableQCoreClass->parse('graphs', 'GRAPHS', true);
		$EnableQCoreClass->unreplace('data');
	}

	break;
}

$Data = $EnableQCoreClass->parse('Data', 'DataFile');
echo $Data;
exit();

?>
