<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
set_time_limit(0);
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.api.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$thisData = php_json_decode(base64_decode(trim($_POST['datas'])));
$thisIndex = array();

foreach ($thisData['columns'] as $thisIndexId => $thisFileds) {
	$thisIndex[$thisFileds] = $thisIndexId;
}

if ($_POST['uploadList'] == '') {
	$theUploadArray = array('recordFile');
}
else {
	$theUploadList = 'recordFile,' . $_POST['uploadList'];
	$theUploadArray = explode(',', $theUploadList);
}

if (!isset($_POST['surveyID']) || ($_POST['surveyID'] == '') || ($_POST['surveyID'] == 0)) {
	exit('The surveyId is empty');
}

$SQL = ' INSERT INTO ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET uploadTime =\'' . time() . '\',dataSource = \'6\',';

foreach ($thisData['columns'] as $thisIndexId => $thisFileds) {
	if ($thisFileds != '') {
		if (!in_array($thisFileds, array('responseID', 'line1Number', 'deviceId', 'brand', 'model', 'currentCity', 'simOperatorName', 'simSerialNumber', 'gpsTime', 'accuracy', 'longitude', 'latitude', 'speed', 'bearing', 'altitude'))) {
			if (in_array($thisFileds, $theUploadArray)) {
				$theFileNameArray = explode('.', $thisData['rows'][0][$thisIndex[$thisFileds]]);

				if (count($theFileNameArray) == 3) {
					$thisFile = substr($thisData['rows'][0][$thisIndex[$thisFileds]], 0, -3);
				}
				else {
					$thisFile = $thisData['rows'][0][$thisIndex[$thisFileds]];
				}

				if ($thisFile != '') {
					if ($thisFileds == 'recordFile') {
						if ($_POST['isUploadRec'] != 1) {
							$thisFileName = $thisFile;
						}
						else {
							$tmpExt = explode('.', $thisFile);
							$tmpNum = count($tmpExt) - 1;
							$extension = strtolower($tmpExt[$tmpNum]);
							$tmpFileName = basename($thisFile, '.' . $extension);
							$thisFileName = $tmpFileName . '_' . date('ymdHis', $thisData['rows'][0][$thisIndex['joinTime']]) . '.' . $extension;
						}
					}
					else {
						$tmpExt = explode('.', $thisFile);
						$tmpNum = count($tmpExt) - 1;
						$extension = strtolower($tmpExt[$tmpNum]);
						$tmpFileName = basename($thisFile, '.' . $extension);
						$thisFileName = $tmpFileName . '_' . date('ymdHis', $thisData['rows'][0][$thisIndex['joinTime']]) . '.' . $extension;
					}
				}
				else {
					$thisFileName = '';
				}

				$SQL .= $thisFileds . '="' . $thisFileName . '",';
			}
			else {
				$thisValue = $thisData['rows'][0][$thisIndex[$thisFileds]];
				$SQL .= $thisFileds . '=\'' . qhtmlspecialchars(getdbstring(addslashes(iconv('UTF-8', 'GBK', $thisValue)))) . '\',';
			}
		}
	}
}

$SQL = substr($SQL, 0, -1);
$DB->query($SQL);
$_SESSION['new_responseID'] = $DB->_GetInsertID();
$GSSQL = ' INSERT INTO ' . ANDROID_INFO_TABLE . ' SET  surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_SESSION['new_responseID'] . '\',brand=\'' . $thisData['rows'][0][$thisIndex['brand']] . '\',model=\'' . $thisData['rows'][0][$thisIndex['model']] . '\',deviceId=\'' . $thisData['rows'][0][$thisIndex['deviceId']] . '\',simOperatorName=\'' . addslashes(iconv('UTF-8', 'GBK', $thisData['rows'][0][$thisIndex['simOperatorName']])) . '\',simSerialNumber=\'' . $thisData['rows'][0][$thisIndex['simSerialNumber']] . '\',line1Number=\'' . $thisData['rows'][0][$thisIndex['line1Number']] . '\' ';
$DB->query($GSSQL);
$thisGpsData = php_json_decode(base64_decode(trim($_POST['gpsDatas'])));
$thisGpsIndex = array();

foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
	$thisGpsIndex[$theFileds] = $theIndexId;
}

foreach ($thisGpsData['rows'] as $theGpsDataRow) {
	$SQL = ' INSERT INTO ' . GPS_TRACE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_SESSION['new_responseID'] . '\',isCell=0,';

	foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
		if ($theFileds != '') {
			if (!in_array($theFileds, array('responseID', 'surveyID', 'flag', 'isCell'))) {
				$theValue = $theGpsDataRow[$thisGpsIndex[$theFileds]];
				$SQL .= $theFileds . '=\'' . qhtmlspecialchars(getdbstring(addslashes(iconv('UTF-8', 'GBK', $theValue)))) . '\',';
			}
		}
	}

	$SQL = substr($SQL, 0, -1);
	$DB->query($SQL);
}

$thisGpsData = php_json_decode(base64_decode(trim($_POST['gpsUploadDatas'])));
$thisGpsIndex = array();

foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
	$thisGpsIndex[$theFileds] = $theIndexId;
}

foreach ($thisGpsData['rows'] as $theGpsDataRow) {
	$SQL = ' INSERT INTO ' . GPS_TRACE_UPLOAD_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_SESSION['new_responseID'] . '\',isCell=0,';

	foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
		if ($theFileds != '') {
			if (!in_array($theFileds, array('responseID', 'surveyID', 'flag', 'isCell'))) {
				$theValue = $theGpsDataRow[$thisGpsIndex[$theFileds]];
				$SQL .= $theFileds . '=\'' . qhtmlspecialchars(getdbstring(addslashes(iconv('UTF-8', 'GBK', $theValue)))) . '\',';
			}
		}
	}

	$SQL = substr($SQL, 0, -1);
	$DB->query($SQL);
}

$thisGpsData = php_json_decode(base64_decode(trim($_POST['cellDatas'])));
$thisGpsIndex = array();

foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
	$thisGpsIndex[$theFileds] = $theIndexId;
}

foreach ($thisGpsData['rows'] as $theGpsDataRow) {
	$gpsTime = trim($theGpsDataRow[$thisGpsIndex['gpsTime']]);
	$mcc = (int) trim($theGpsDataRow[$thisGpsIndex['accuracy']]);
	$mnc = (int) trim($theGpsDataRow[$thisGpsIndex['latitude']]);
	$cid = trim($theGpsDataRow[$thisGpsIndex['longitude']]);
	$lac = trim($theGpsDataRow[$thisGpsIndex['altitude']]);
	$SQL = ' INSERT INTO ' . GPS_TRACE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_SESSION['new_responseID'] . '\',isCell=1,gpsTime=\'' . $gpsTime . '\',accuracy=\'' . $mcc . '\',latitude=\'' . $mnc . '\',longitude=\'' . $cid . '\',altitude=\'' . $lac . '\'';
	$DB->query($SQL);
}

$thisGpsData = php_json_decode(base64_decode(trim($_POST['cellUploadDatas'])));
$thisGpsIndex = array();

foreach ($thisGpsData['columns'] as $theIndexId => $theFileds) {
	$thisGpsIndex[$theFileds] = $theIndexId;
}

foreach ($thisGpsData['rows'] as $theGpsDataRow) {
	$gpsTime = trim($theGpsDataRow[$thisGpsIndex['gpsTime']]);
	$mcc = (int) trim($theGpsDataRow[$thisGpsIndex['accuracy']]);
	$mnc = (int) trim($theGpsDataRow[$thisGpsIndex['latitude']]);
	$cid = trim($theGpsDataRow[$thisGpsIndex['longitude']]);
	$lac = trim($theGpsDataRow[$thisGpsIndex['altitude']]);
	$qtnID = trim($theGpsDataRow[$thisGpsIndex['qtnID']]);
	$SQL = ' INSERT INTO ' . GPS_TRACE_UPLOAD_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_SESSION['new_responseID'] . '\',qtnID=\'' . $qtnID . '\',isCell=1,gpsTime=\'' . $gpsTime . '\',accuracy=\'' . $mcc . '\',latitude=\'' . $mnc . '\',longitude=\'' . $cid . '\',altitude=\'' . $lac . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT apiURL,apiVarName,surveyID,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' LIMIT 1 ';
$S_Row = $DB->queryFirstRow($SQL);

if ($thisData['rows'][0][$thisIndex['overFlag']] == 1) {
	_writeapidata($S_Row, $_SESSION['new_responseID'], 1, $thisData['rows'][0][$thisIndex['joinTime']], $thisData['rows'][0][$thisIndex['submitTime']]);
}

if ($thisData['rows'][0][$thisIndex['overFlag']] == 2) {
	_writeapidata($S_Row, $_SESSION['new_responseID'], 3, $thisData['rows'][0][$thisIndex['joinTime']], $thisData['rows'][0][$thisIndex['submitTime']]);
}

if ($thisData['rows'][0][$thisIndex['overFlag']] == 1) {
	dealcountinfo($_POST['surveyID'], $thisData['rows'][0][$thisIndex['joinTime']]);
}

unset($_SESSION['new_responseID']);
echo 'true';
exit();

?>
