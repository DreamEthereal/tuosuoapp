<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
_checkroletype('1|2|3|4|5|7');
$EnableQCoreClass->setTemplateFile('RoadMapFile', 'BaiduRoadMap.html');

switch ($_GET['isCell']) {
case '0':
default:
	$SQL = ' SELECT longitude,latitude FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID = \'' . $_GET['gpsSurveyId'] . '\' AND responseID = \'' . $_GET['gpsResponseId'] . '\' AND isCell =\'0\' ORDER BY gpsTime ASC,traceID ASC ';
	$Result = $DB->query($SQL);
	$gpsPoints = '';
	$gpsRoadArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theGpsStr = '{"lat":"' . $Row['latitude'] . '","lng":"' . $Row['longitude'] . '"},';

		if (!in_array($theGpsStr, $gpsRoadArray)) {
			$gpsRoadArray[] = $theGpsStr;
			$gpsPoints .= $theGpsStr;
		}
	}

	$gpsPoints = substr($gpsPoints, 0, -1);
	$EnableQCoreClass->replace('gpsPoints', $gpsPoints);
	unset($gpsRoadArray);
	break;

case '1':
	if ($License['isCell'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_android']);
	}

	$SQL = ' SELECT * FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID = \'' . $_GET['gpsSurveyId'] . '\' AND responseID = \'' . $_GET['gpsResponseId'] . '\' AND isCell =\'1\' ORDER BY gpsTime ASC,traceID ASC ';
	$Result = $DB->query($SQL);
	$gpsRoadArray = array();
	$theCellData = '';

	while ($Row = $DB->queryArray($Result)) {
		$theCellStr = $Row['accuracy'] . '*' . $Row['latitude'] . '*' . $Row['longitude'] . '*' . $Row['altitude'];

		if (!in_array($theCellStr, $gpsRoadArray)) {
			$gpsRoadArray[] = $theCellStr;
			$theCellData .= $theCellStr . '$$$$$$';
		}
	}

	$postData['key'] = md5('enableq');
	$postData['cellDatas'] = str_replace('+', '%2B', base64_encode(substr($theCellData, 0, -6)));
	$lbsContent = post_data_to_host($Config['lbsBatchURL'], $postData);
	$lbsData = base64_decode($lbsContent);

	if (trim($lbsData) != '') {
		$gpsPoints = substr($lbsData, 0, -1);
		$EnableQCoreClass->replace('gpsPoints', $gpsPoints);
	}
	else {
		_showerror('基站定位数据转换错误', '基站定位数据转换错误：目前已有的基站信息数据库无法对您取得的基站定位数据进行转换');
	}

	break;
}

$EnableQCoreClass->parse('RoadMapPage', 'RoadMapFile');
$EnableQCoreClass->output('RoadMapPage');

?>
