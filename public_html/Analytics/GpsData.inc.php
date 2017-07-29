<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($isViewGpsInfo == false) {
	$EnableQCoreClass->replace('gpsInfo', '<font color=red>无法提供GPS轨迹地图</font>');
}
else {
	$gpsInfo = '';
	$haveGpsData = false;
	$gSQL = ' SELECT COUNT(*) as gpsNum FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND responseID=\'' . $_GET['responseID'] . '\' AND isCell =0 ';
	$gRow = $DB->queryFirstRow($gSQL);

	if ($gRow['gpsNum'] != 0) {
		$tSQL = ' SELECT gpsTime FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $_GET['responseID'] . '\' AND isCell =\'0\' ORDER BY gpsTime ASC,traceID ASC ';
		$tResult = $DB->query($tSQL);
		$gpsTimeBegin = $gpsTimeEnd = 0;
		$tmp = 0;
		$tCount = $DB->_getNumRows($tResult);

		while ($tRow = $DB->queryArray($tResult)) {
			if ($tmp == 0) {
				$gpsTimeBegin = date('Y-m-d H:i:s', $tRow['gpsTime'] / 1000);
			}

			if ($tmp == $tCount - 1) {
				$gpsTimeEnd = date('Y-m-d H:i:s', $tRow['gpsTime'] / 1000);
			}

			$tmp++;
		}

		$haveGpsData = true;
		$gpsInfo .= '<a href="javascript:void(0);" onclick="javascript:showPopWin(\'../Android/BaiduRoadMap.php?gpsSurveyId=' . $_GET['surveyID'] . '&gpsResponseId=' . $_GET['responseID'] . '&isCell=0\', 700, 450, null, null,\'百度地图显示GPS定位轨迹\')"><b>百度地图显示GPS定位轨迹</b></a> (卫星时间：' . $gpsTimeBegin . ' - ' . $gpsTimeEnd . ')';
	}

	if ($License['isCell'] == 1) {
		$gSQL = ' SELECT COUNT(*) as gpsNum FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND responseID=\'' . $_GET['responseID'] . '\' AND isCell =1 ';
		$gRow = $DB->queryFirstRow($gSQL);

		if ($gRow['gpsNum'] != 0) {
			$tSQL = ' SELECT gpsTime FROM ' . GPS_TRACE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $_GET['responseID'] . '\' AND isCell =1 ORDER BY gpsTime ASC,traceID ASC ';
			$tResult = $DB->query($tSQL);
			$gpsTimeBegin = $gpsTimeEnd = 0;
			$tmp = 0;
			$tCount = $DB->_getNumRows($tResult);

			while ($tRow = $DB->queryArray($tResult)) {
				if ($tmp == 0) {
					$gpsTimeBegin = date('Y-m-d H:i:s', $tRow['gpsTime']);
				}

				if ($tmp == $tCount - 1) {
					$gpsTimeEnd = date('Y-m-d H:i:s', $tRow['gpsTime']);
				}

				$tmp++;
			}

			if ($haveGpsData == true) {
				$gpsInfo .= '<br/>&nbsp;';
			}

			$haveGpsData = true;
			$gpsInfo .= '<a href="javascript:void(0);" onclick="javascript:showPopWin(\'../Android/BaiduRoadMap.php?gpsSurveyId=' . $_GET['surveyID'] . '&gpsResponseId=' . $_GET['responseID'] . '&isCell=1\', 700, 450, null, null,\'百度地图显示基站定位轨迹\')"><b>百度地图显示基站定位轨迹</b></a> (设备时间：' . $gpsTimeBegin . ' - ' . $gpsTimeEnd . ')';
		}
	}

	if ($haveGpsData == false) {
		if (($GSRow['longitude'] != '') && ($GSRow['latitude'] != '')) {
			$gpsInfo = '';
			if (($GSRow['gpsTime'] != '') && ($GSRow['gpsTime'] != '0')) {
				$gpsInfo .= $lang['gps_gpsTime'] . date('Y-m-d H:i:s', $GSRow['gpsTime'] / 1000) . '<br/>&nbsp;';
			}

			if ($GSRow['accuracy'] != '') {
				$gpsInfo .= $lang['gps_accuracy'] . $GSRow['accuracy'] . '<br/>&nbsp;';
			}

			$gpsInfo .= $lang['gps_longitude'] . $GSRow['longitude'] . '<br/>&nbsp;';
			$gpsInfo .= $lang['gps_latitude'] . $GSRow['latitude'] . '<br/>&nbsp;';

			if ($GSRow['speed'] != '') {
				$gpsInfo .= $lang['gps_speed'] . $GSRow['speed'] . '<br/>&nbsp;';
			}

			if ($GSRow['bearing'] != '') {
				$gpsInfo .= $lang['gps_bearing'] . $GSRow['bearing'] . '<br/>&nbsp;';
			}

			if ($GSRow['altitude'] != '') {
				$gpsInfo .= $lang['gps_altitude'] . $GSRow['altitude'] . '<br/>&nbsp;';
			}

			$gpsInfo .= '<a href="javascript:void(0);" onclick="javascript:showPopWin(\'../Android/BaiduMap.php?gps_longitude=' . $GSRow['longitude'] . '&gps_latitude=' . $GSRow['latitude'] . '\', 700, 450, null, null,\'百度地图显示GPS定位坐标\')"><b>百度地图显示GPS定位坐标</b></a>';
		}
		else {
			$gpsInfo = '<font color=red>无法提供GPS轨迹地图</font>';
		}
	}

	$EnableQCoreClass->replace('gpsInfo', $gpsInfo);
}

?>
