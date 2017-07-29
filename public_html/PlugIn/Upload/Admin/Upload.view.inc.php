<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'CommonDetail.html');
$questionName = '';

if ($theQtnArray['isRequired'] == '1') {
	$questionName = '<span class=red>*</span>';
}

$questionName .= qnospecialchar($theQtnArray['questionName']);
$questionName .= '[' . $lang['question_type_11'] . ']';
$EnableQCoreClass->replace('questionName', $questionName);

if ($R_Row['option_' . $questionID] != '') {
	$fileType = explode('.', $R_Row['option_' . $questionID]);

	if ($Sur_G_Row['custDataPath'] == '') {
		$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $joinTime) . '/' . date('d', $joinTime) . '/';
	}
	else {
		$filePath = $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
	}

	$tmpNum = count($fileType) - 1;
	$extension = strtolower($fileType[$tmpNum]);

	switch ($extension) {
	case 'jpg':
	case 'jpeg':
	case 'gif':
	case 'bmp':
	case 'png':
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$imgSize = @getimagesize($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID]);

			if (400 < $imgSize[0]) {
				$picStr = '<IMG SRC=\'' . $filePath . $R_Row['option_' . $questionID] . '\' border=0 onmousewheel="return dynimgsize(this)" width=400>';
			}
			else {
				$picStr = '<IMG SRC=\'' . $filePath . $R_Row['option_' . $questionID] . '\' border=0 onmousewheel="return dynimgsize(this)">';
			}

			$htmlStr = $picStr;

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的图片不存在或已被删除</font>';
		}

		break;

	case 'swf':
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$htmlStr = '<embed src="' . $filePath . $R_Row['option_' . $questionID] . '" quality=high width=400 height=300  pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash type=application/x-shockwave-flash></embed>';

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的Flash文件不存在或已被删除</font>';
		}

		break;

	case 'mpg':
	case 'mpeg':
	case 'wmv':
	case 'asf':
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$htmlStr = '<embed src="' . $filePath . $R_Row['option_' . $questionID] . '" width="400" height="300" type="audio/x-pn-realaudio-plugin" autostart=1 loop=1 controls="IMAGEWINDOW,ControlPanel,StatusBar" console="Clip1"></embed>';

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的视频文件不存在或已被删除</font>';
		}

		break;

	case 'flv':
	case 'mp4':
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$htmlStr = '<embed src="../JS/flvplayer.swf" flashvars="?&autoplay=true&sound=70&buffer=2&vdo=' . $filePath . $R_Row['option_' . $questionID] . '" width=400 height=300 allowFullScreen="true" quality="best" wmode="transparent" allowScriptAccess="sameDomain" pluginspage="http://www.macromedia.com/go/getflashplayer"  type="application/x-shockwave-flash"></embed>';

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的视频文件不存在或已被删除</font>';
		}

		break;

	case 'mp3':
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$htmlStr = '<object type="application/x-shockwave-flash" data="../JS/mp3player.swf" width="200" height="20"><param name="movie" value="../JS/mp3player.swf"/><param name="flashvars" value="mp3=' . $filePath . $R_Row['option_' . $questionID] . '&autoplay=1&autoreplay=0"/><param name="wmode" value="transparent"/></object>';

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的MP3音频文件不存在或已被删除</font>';
		}

		break;

	default:
		if (file_exists($Config['absolutenessPath'] . $filePath . $R_Row['option_' . $questionID])) {
			$htmlStr = $R_Row['option_' . $questionID];

			if ($isAndroidView != 1) {
				$htmlStr .= '<br/><a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $surveyID . '&responseID=' . $R_Row['responseID'] . '&flag=' . $joinTime . '&option=option_' . $questionID . '"><font color=red>下载' . $R_Row['option_' . $questionID] . '</font></a>';
			}
		}
		else {
			$htmlStr = $R_Row['option_' . $questionID];
			$htmlStr .= '<br/><font color=red>上传的附属文件不存在或已被删除</font>';
		}

		break;
	}

	if (($haveDeleDataAuth == 1) && ($isAndroidView != 1)) {
		$deleURL = $thisProg . '&Does=DeleFile&surveyID=' . $surveyID . '&fields=option_' . $questionID . '&fileTime=' . $joinTime . '&fileName=' . $R_Row['option_' . $questionID] . '&area=' . $R_Row['area'] . '&responseID=' . $R_Row['responseID'];
		$htmlStr .= '<br/>&nbsp;<a href=\'' . $deleURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['action_dele_file'] . $R_Row['option_' . $questionID] . '</a>';
	}

	if ($isAndroidView != 1) {
		if ($Sur_G_Row['custDataPath'] == '') {
			$htmlStr .= '<br/><font color=blue>* 注意该变量对应的回复文件名以字符[<font color=red>' . $questionID . '_</font>]开头，以便下载时备查</font>';
		}

		$SQL = ' SELECT gpsTime,longitude,latitude FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND responseID = \'' . $R_Row['responseID'] . '\' AND qtnID = \'' . $questionID . '\' AND isCell =0 ORDER BY gpsTime DESC LIMIT 1 ';
		$gRow = $DB->queryFirstRow($SQL);

		if ($gRow) {
			$htmlStr .= '<br/><a href="javascript:void(0);" onclick="javascript:showPopWin(\'../Android/BaiduMap.php?gps_longitude=' . $gRow['longitude'] . '&gps_latitude=' . $gRow['latitude'] . '\', 700, 450, null, null,\'百度地图定位地理位置(GPS)\')"><font color=red>* 百度地图定位地理位置(GPS)</font></a>';
			$htmlStr .= '<br/><font color=blue>* 拍照时间：' . date('Y-m-d H:i:s', $gRow['gpsTime'] / 1000) . '</font>';
		}
		else if ($License['isCell'] == 1) {
			$SQL = ' SELECT traceID FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND responseID = \'' . $R_Row['responseID'] . '\' AND qtnID = \'' . $questionID . '\' AND isCell =1 ORDER BY gpsTime DESC LIMIT 1 ';
			$gRow = $DB->queryFirstRow($SQL);

			if ($gRow) {
				$htmlStr .= '<br/><a href="javascript:void(0);" onclick="javascript:showPopWin(\'../Android/BaiduMapJump.php?traceID=' . $gRow['traceID'] . '\', 700, 450, null, null,\'百度地图定位地理位置(基站)\')"><font color=red>* 百度地图定位地理位置(基站)</font></a>';
				$htmlStr .= '<br/><font color=blue>* 拍照时间：' . date('Y-m-d H:i:s', $gRow['gpsTime']) . '(参考)</font>';
			}
		}
	}

	$EnableQCoreClass->replace('optionName', $htmlStr);
}
else {
	$EnableQCoreClass->replace('optionName', $lang['skip_answer']);
}

$EnableQCoreClass->replace('questionList', $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File'));
$haveCondRel = getqtnconsucc($questionID);
if (($haveCondRel != '') && !runcode($haveCondRel)) {
	$check_survey_conditions_list .= '	$("#question_' . $questionID . '").hide();' . "\n" . '';
}

if (($isViewAuthInfo == 1) || ($isAppSurveyData == 1)) {
	if ($isViewAuthInfo == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' ORDER BY b.traceTime DESC ';
	}

	if ($isAppSurveyData == 1) {
		$aSQL = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.evidence,b.reason FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $questionID . '\' AND b.responseID =\'' . $theResponseID . '\' AND isAppData =1 ORDER BY b.traceTime DESC ';
	}

	$aResult = $DB->query($aSQL);
	$aRecNum = $DB->_getNumRows($aResult);

	if ($aRecNum == 0) {
		$EnableQCoreClass->replace('authList', '');
	}
	else {
		$EnableQCoreClass->setTemplateFile('ShowAuth' . $questionID . 'File', 'uAuthList.html');
		$EnableQCoreClass->set_CycBlock('ShowAuth' . $questionID . 'File', 'AUTHLIST', 'authList' . $questionID);
		$EnableQCoreClass->replace('authList' . $questionID, '');
		$tmp = 0;

		if ($Sur_G_Row['custDataPath'] == '') {
			$filePath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
		}
		else {
			$filePath = $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
		}

		while ($aRow = $DB->queryArray($aResult)) {
			$tmp++;

			if ($aRow['isAppData'] != 1) {
				if ($aRow['isAdmin'] == '4') {
					$modiLang = '修改';
				}
				else {
					$modiLang = '审核';
				}
			}
			else {
				$modiLang = '申诉';
			}

			$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['nickName'], $aRow['userGroupID'], $aRow['groupType']);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['traceTime']) . $modiLang;
			$authInfoList .= '<span class=red>[该题]</span>自<span class=red>[';

			if ($aRow['oriValue'] == '') {
				$authInfoList .= '跳过]</span>至<span class=red>[';
			}
			else {
				$authInfoList .= '<a href=\'' . $filePath . $aRow['oriValue'] . '\' target=_blank>' . $aRow['oriValue'] . '</a>]</span>至<span class=red>[';
			}

			if ($aRow['updateValue'] == '') {
				$authInfoList .= '跳过]</span>';
			}
			else {
				$authInfoList .= '<a href=\'' . $filePath . $aRow['updateValue'] . '\' target=_blank>' . $aRow['updateValue'] . '</a>]</span>';
			}

			if ($isAppSurveyData == 1) {
				$authInfoList .= '；理由为：<span class=red>[' . $aRow['reason'] . ']</span>';

				if ($aRow['evidence'] != '') {
					$authInfoList .= '；证据为：<a href=\'' . $evidencePhyPath . $aRow['evidence'] . '\' target=_blank><span class=red>[' . $aRow['evidence'] . ']</span></a>';
				}
			}

			$EnableQCoreClass->replace('authInfoList', $authInfoList);
			$EnableQCoreClass->parse('authList' . $questionID, 'AUTHLIST', true);
		}

		$EnableQCoreClass->replace('authList', $EnableQCoreClass->parse('ShowAuth' . $questionID, 'ShowAuth' . $questionID . 'File'));
	}
}
else {
	$EnableQCoreClass->replace('authList', '');
}

?>
