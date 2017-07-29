<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
if (isset($_GET['surveyID']) && ($_GET['surveyID'] != '') && ($_GET['surveyID'] != '0')) {
	_checkpassport('1|2|3|5|7', $_GET['surveyID']);
	$inputUserList = array();
	$InSQL = ' SELECT a.administratorsName FROM ' . ADMINISTRATORS_TABLE . ' a, ' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' ORDER BY a.administratorsID ASC ';
	$InResult = $DB->query($InSQL);

	while ($InRow = $DB->queryArray($InResult)) {
		$inputUserList[] = '\'' . $InRow['administratorsName'] . '\'';
	}

	if (count($inputUserList) == 0) {
		_showerror('数据检查错误', '数据检查错误：该问卷尚未分配访员');
	}
}
else {
	_checkroletype('1');
}

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

if ($License['isRealGps'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$EnableQCoreClass->setTemplateFile('RoadMapFile', 'BaiduDevicesMap.html');
$EnableQCoreClass->replace('siteName', $Config['siteName']);
if (isset($_GET['surveyID']) && ($_GET['surveyID'] != '') && ($_GET['surveyID'] != '0')) {
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle'] . ' - ');
	$SQL = ' SELECT traceID,deviceId,isCell FROM ' . DEVICE_TRACE_TABLE . ' WHERE nickUserName IN (' . implode(',', $inputUserList) . ') ORDER BY traceID ASC ';
}
else {
	$EnableQCoreClass->replace('surveyTitle', '');
	$SQL = ' SELECT traceID,deviceId,isCell FROM ' . DEVICE_TRACE_TABLE . ' ORDER BY traceID ASC ';
}

$Result = $DB->query($SQL);
$gpsArray = array();
$cellArray = array();

while ($Row = $DB->queryArray($Result)) {
	switch ($Row['isCell']) {
	case '0':
	default:
		if (!array_key_exists($Row['deviceId'], $gpsArray)) {
			$gpsArray[$Row['deviceId']] = $Row['traceID'];
		}

		break;

	case '1':
		if (!array_key_exists($Row['deviceId'], $gpsArray)) {
			$cellArray[$Row['deviceId']] = $Row['traceID'];
		}

		break;
	}
}

if (count($gpsArray) != 0) {
	$SQL = ' SELECT * FROM ' . DEVICE_TRACE_TABLE . ' WHERE traceID IN (' . implode(',', $gpsArray) . ') ORDER BY gpsTime DESC ';
}
else {
	$SQL = ' SELECT * FROM ' . DEVICE_TRACE_TABLE . ' WHERE traceID =0 ORDER BY gpsTime DESC ';
}

$Result = $DB->query($SQL);
$gpsPoints = $theDevicesInfo = '';
$gpsRoadArray = array();

while ($Row = $DB->queryArray($Result)) {
	$theGpsStr = '{"lat":"' . $Row['latitude'] . '","lng":"' . $Row['longitude'] . '"},';

	if (!in_array($theGpsStr, $gpsRoadArray)) {
		$gpsRoadArray[] = $theGpsStr;
		$gpsPoints .= $theGpsStr;
		$theDevicesInfo .= '{"nickUserName":"' . $Row['nickUserName'] . '(GPS)' . '","gpsTime":"' . date('Y-m-d H:i:s', $Row['gpsTime']) . '","brand":"' . $Row['brand'] . '","model":"' . $Row['model'] . '","deviceId":"' . $Row['deviceId'] . '","isCell":"0"},';
	}
}

unset($gpsRoadArray);

if ($License['isCell'] == 1) {
	if (count($cellArray) != 0) {
		$SQL = ' SELECT * FROM ' . DEVICE_TRACE_TABLE . ' WHERE traceID IN (' . implode(',', $cellArray) . ') ORDER BY gpsTime DESC ';
	}
	else {
		$SQL = ' SELECT * FROM ' . DEVICE_TRACE_TABLE . ' WHERE traceID =0 ORDER BY gpsTime DESC ';
	}

	$Result = $DB->query($SQL);
	$theCellData = '';
	$cellRoadArray = array();

	while ($Row = $DB->queryArray($Result)) {
		$theCellStr = $Row['traceID'] . '*' . $Row['accuracy'] . '*' . $Row['latitude'] . '*' . $Row['longitude'] . '*' . $Row['altitude'];

		if (!in_array($theCellStr, $cellRoadArray)) {
			$cellRoadArray[] = $theCellStr;
			$theCellData .= $theCellStr . '$$$$$$';
		}
	}

	unset($cellRoadArray);
	$postData['key'] = md5('enableq');
	$postData['cellDatas'] = str_replace('+', '%2B', base64_encode(substr($theCellData, 0, -6)));
	$lbsContent = post_data_to_host($Config['lbsCellURL'], $postData);
	$lbsData = substr(base64_decode($lbsContent), 0, -6);

	if (trim($lbsData) != '') {
		$gpsData = explode('$$$$$$', $lbsData);

		foreach ($gpsData as $theGpsData) {
			$thisGpsData = explode('*', $theGpsData);
			$gpsPoints .= $thisGpsData[1];
			$SQL = ' SELECT * FROM ' . DEVICE_TRACE_TABLE . ' WHERE traceID =\'' . $thisGpsData[0] . '\' ORDER BY gpsTime DESC ';
			$Row = $DB->queryFirstRow($SQL);
			$theDevicesInfo .= '{"nickUserName":"' . $Row['nickUserName'] . '(基站)' . '","gpsTime":"' . date('Y-m-d H:i:s', $Row['gpsTime']) . '","brand":"' . $Row['brand'] . '","model":"' . $Row['model'] . '","deviceId":"' . $Row['deviceId'] . '","isCell":"0"},';
		}
	}
}

$gpsPoints = substr($gpsPoints, 0, -1);

if (trim($gpsPoints) == '') {
	_showerror('数据检查错误', '数据检查错误：尚无访员的实时定位数据，或者因为网络问题尚无转换后的实时定位数据');
}

$EnableQCoreClass->replace('gpsPoints', $gpsPoints);
$theDevicesInfo = substr($theDevicesInfo, 0, -1);
$EnableQCoreClass->replace('theDevicesInfo', $theDevicesInfo);
$EnableQCoreClass->parse('RoadMapPage', 'RoadMapFile');
$EnableQCoreClass->output('RoadMapPage');

?>
