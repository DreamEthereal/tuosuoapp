<?php
//dezend by http://www.yunlu99.com/
function getnextdataid($responseID, $flag)
{
	global $DB;
	global $_GET;
	global $table_prefix;
	global $Sur_G_Row;

	if ($flag == 1) {
		$_obf_dzT6Sg__ = ' SELECT responseID,administratorsName,ipAddress,area,overFlag,authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID > \'' . $responseID . '\' ORDER BY responseID ASC LIMIT 1 ';
	}
	else {
		$_obf_dzT6Sg__ = ' SELECT responseID,administratorsName,ipAddress,area,overFlag,authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID < \'' . $responseID . '\' ORDER BY responseID DESC LIMIT 1 ';
	}

	$_obf_oNcPDA__ = $DB->queryFirstRow($_obf_dzT6Sg__);
	if (($_obf_oNcPDA__['responseID'] == '') || ($_obf_oNcPDA__['responseID'] == 0)) {
		return 0;
	}

	$thisDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $responseID, $_obf_oNcPDA__, $Sur_G_Row));
	$thisHaveViewDataAuth = $thisDataAuthArray[0];

	if ($thisHaveViewDataAuth == 1) {
		return $_obf_oNcPDA__['responseID'];
	}
	else {
		return getnextdataid($_obf_oNcPDA__['responseID'], $flag);
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
$R_Row = $DB->queryFirstRow($SQL);
$theDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $_GET['responseID'], $R_Row, $Sur_G_Row));
$haveViewDataAuth = $theDataAuthArray[0];
$haveEditDataAuth = $theDataAuthArray[1];
$haveDeleDataAuth = $theDataAuthArray[2];
$isViewPanelInfo = $isViewRecFile = $isViewGpsInfo = true;

switch ($_SESSION['adminRoleType']) {
case '4':
	$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'InputDataDetail.html');
	break;

case '3':
	$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);
	$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'ResultViewDetail.html');

	if (in_array('t1', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t1_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t1_show', '');
	}

	if (in_array('t2', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t2_show', 'none');
		$isViewPanelInfo = false;
	}
	else {
		$EnableQCoreClass->replace('t2_show', '');
	}

	if (in_array('t3', $forbidViewIdValue)) {
		$isViewRecFile = false;
	}

	if (in_array('t4', $forbidViewIdValue)) {
		$isViewGpsInfo = false;
	}

	break;

case '7':
	$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'ResultAuthDetail.html');
	$aSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE isAuth=1 AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$aRow = $DB->queryFirstRow($aSQL);
	$isAppAuthPassport = ($aRow ? 1 : 0);
	$aSQL = ' SELECT administratorsID FROM ' . VIEWUSERLIST_TABLE . ' WHERE isAuth=1 AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$aRow = $DB->queryFirstRow($aSQL);
	$isAuthPassport = ($aRow ? 1 : 0);
	break;

default:
	$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'ResultDetail.html');
	break;
}

$EnableQCoreClass->set_CycBlock('ResultDetailFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if ($haveViewDataAuth == 0) {
	_showerror($lang['auth_error'], $lang['passport_is_permit'] . ':' . $lang['no_auth_view_data']);
}

if ($_SESSION['ViewBackURL'] != '') {
	$EnableQCoreClass->replace('lastURL', $_SESSION['ViewBackURL']);
}
else {
	$EnableQCoreClass->replace('lastURL', $thisProg);
}

if ($haveEditDataAuth == 1) {
	$EnableQCoreClass->replace('isAuthDataModi', '');
	$modiDataURL = $thisProg . '&Does=ModiData&responseID=' . $_GET['responseID'];
	$EnableQCoreClass->replace('modiDataURL', $modiDataURL);
}
else {
	$EnableQCoreClass->replace('isAuthDataModi', 'none');
	$EnableQCoreClass->replace('modiDataURL', '');
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('responseID', $_GET['responseID']);
$isViewAuthInfo = 0;

switch ($_SESSION['adminRoleType']) {
case '3':
	if ($Sur_G_Row['isExportData'] == 1) {
		$EnableQCoreClass->replace('isHaveUpload', 'none');
	}
	else if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('isHaveUpload', 'none');
	}
	else {
		$HSQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $_GET['surveyID'] . '\' LIMIT 1 ';
		$HRow = $DB->queryFirstRow($HSQL);
		if ($HRow || ($Sur_G_Row['isRecord'] == 1) || ($Sur_G_Row['isRecord'] == 2)) {
			$EnableQCoreClass->replace('isHaveUpload', '');
		}
		else {
			$EnableQCoreClass->replace('isHaveUpload', 'none');
		}
	}

	if ($Sur_G_Row['isViewAuthInfo'] == 1) {
		$isViewAuthInfo = 1;
	}
	else {
		$isAppSurveyData = 1;
	}

	break;

default:
	if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('isHaveUpload', 'none');
	}
	else {
		$HSQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $_GET['surveyID'] . '\' LIMIT 1 ';
		$HRow = $DB->queryFirstRow($HSQL);
		if ($HRow || ($Sur_G_Row['isRecord'] == 1) || ($Sur_G_Row['isRecord'] == 2)) {
			$EnableQCoreClass->replace('isHaveUpload', '');
		}
		else {
			$EnableQCoreClass->replace('isHaveUpload', 'none');
		}
	}

	$isViewAuthInfo = 1;
	break;
}

$GSSQL = ' SELECT * FROM ' . ANDROID_INFO_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND responseID=\'' . $_GET['responseID'] . '\' ';
$GSRow = $DB->queryFirstRow($GSSQL);
if (!$GSRow || ($isViewGpsInfo == false)) {
	$EnableQCoreClass->replace('isHaveGps', 'none');
	$EnableQCoreClass->replace('simInfo', '');
}
else {
	$EnableQCoreClass->replace('isHaveGps', '');
	$simInfo = $lang['info_line1Number'] . $GSRow['line1Number'] . '<br/>&nbsp;';
	$simInfo .= $lang['info_brand'] . $GSRow['brand'] . '<br/>&nbsp;';
	$simInfo .= $lang['info_model'] . $GSRow['model'] . '<br/>&nbsp;';
	$simInfo .= $lang['info_deviceId'] . $GSRow['deviceId'] . '<br/>&nbsp;';
	$simInfo .= $lang['info_simOperatorName'] . $GSRow['simOperatorName'] . '<br/>&nbsp;';
	$simInfo .= $lang['info_simSerialNumber'] . $GSRow['simSerialNumber'];
	$EnableQCoreClass->replace('simInfo', $simInfo);
}

require ROOT_PATH . 'Analytics/GpsData.inc.php';
if (($Sur_G_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$EnableQCoreClass->replace('isHaveGroup', '');

		if ($R_Row['administratorsGroupID'] == '0') {
			$administratorsGroupName = $lang['no_group'];
		}
		else {
			$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $R_Row['administratorsGroupID'] . '\' ';
			$GroupRow = $DB->queryFirstRow($GroupSQL);
			$administratorsGroupName = $GroupRow['administratorsGroupName'];
		}

		$EnableQCoreClass->replace('administratorsGroupName', $administratorsGroupName);
		$EnableQCoreClass->setTemplateFile('ResultAjaxDetailFile', 'ResultAjaxDetail.html');
		$EnableQCoreClass->set_CycBlock('ResultAjaxDetailFile', 'AJAX', 'ajax');
		$EnableQCoreClass->replace('ajax', '');

		if ($Sur_G_Row['ajaxRtnValue'] != '') {
			$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

			if (6 < count($ajaxRtnValueName)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValueName);
			}

			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
				$j = $i + 1;
				$EnableQCoreClass->replace('ajaxRtnValue', $R_Row['ajaxRtnValue_' . $j]);
				$EnableQCoreClass->parse('ajax', 'AJAX', true);
			}
		}

		$EnableQCoreClass->replace('ajaxRtnValue', $EnableQCoreClass->parse('ResultAjaxDetail', 'ResultAjaxDetailFile'));
		break;

	case '2':
		$EnableQCoreClass->replace('isHaveGroup', 'none');
		$EnableQCoreClass->replace('administratorsGroupName', '');
		$EnableQCoreClass->setTemplateFile('ResultAjaxDetailFile', 'ResultAjaxDetail.html');
		$EnableQCoreClass->set_CycBlock('ResultAjaxDetailFile', 'AJAX', 'ajax');
		$EnableQCoreClass->replace('ajax', '');

		if ($Sur_G_Row['ajaxRtnValue'] != '') {
			$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

			if (6 < count($ajaxRtnValueName)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValueName);
			}

			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
				$j = $i + 1;
				$EnableQCoreClass->replace('ajaxRtnValue', $R_Row['ajaxRtnValue_' . $j]);
				$EnableQCoreClass->parse('ajax', 'AJAX', true);
			}
		}

		$EnableQCoreClass->replace('ajaxRtnValue', $EnableQCoreClass->parse('ResultAjaxDetail', 'ResultAjaxDetailFile'));
		break;

	case '4':
	case '3':
	case '5':
		$EnableQCoreClass->replace('isHaveGroup', 'none');
		$EnableQCoreClass->replace('administratorsGroupName', '');
		$EnableQCoreClass->replace('ajaxRtnValue', '');
		break;
	}
}
else {
	$EnableQCoreClass->replace('isHaveGroup', 'none');
	$EnableQCoreClass->replace('administratorsGroupName', '');
	$EnableQCoreClass->replace('ajaxRtnValue', '');
}

if (($R_Row['recordFile'] != '') && ($isViewRecFile == true)) {
	$EnableQCoreClass->replace('isHaveRecFile', '');

	if ($Sur_G_Row['custDataPath'] == '') {
		$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
		$vFilePath = $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
	}
	else {
		$filePath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
		$vFilePath = $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
	}

	$fileName = $filePath . trim($R_Row['recordFile']);
	$vFileName = $vFilePath . trim($R_Row['recordFile']);

	if (file_exists($fileName)) {
		$fileType = explode('.', $R_Row['recordFile']);
		$tmpNum = count($fileType) - 1;
		$extension = strtolower($fileType[$tmpNum]);

		switch ($extension) {
		case 'mpg':
		case 'mpeg':
		case 'wmv':
		case 'asf':
			$htmlStr = '<embed src="' . $vFileName . '" width="400" height="300" type="audio/x-pn-realaudio-plugin" autostart=1 loop=1 controls="IMAGEWINDOW,ControlPanel,StatusBar" console="Clip1"></embed><br/>部分视频格式可能不能在线播放<br/>';
			break;

		case 'flv':
		case 'mp4':
			$htmlStr = '<embed src="../JS/flvplayer.swf" flashvars="?&autoplay=true&sound=70&buffer=2&vdo=' . $vFileName . '" width=400 height=300 allowFullScreen="true" quality="best" wmode="transparent" allowScriptAccess="sameDomain" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed><br/>部分视频格式可能不能在线播放<br/>';
			break;

		case 'mp3':
			$htmlStr = '<object type="application/x-shockwave-flash" data="../JS/mp3player.swf" width="200" height="20"><param name="movie" value="../JS/mp3player.swf"/><param name="flashvars" value="mp3=' . $vFileName . '&autoplay=1&autoreplay=0"/><param name="wmode" value="transparent"/></object><br/>';
			break;

		default:
			$htmlStr = '';
			break;
		}

		$htmlStr .= '<a href="' . ROOT_PATH . 'WebAPI/Download.php?qid=' . $_GET['surveyID'] . '&responseID=' . $R_Row['responseID'] . '&flag=' . $R_Row['joinTime'] . '&option=recordFile"><font color=red>下载' . $R_Row['recordFile'] . '</font></a>';

		if ($haveDeleDataAuth == 1) {
			$deleURL = $thisProg . '&Does=DeleFile&surveyID=' . $_GET['surveyID'] . '&fields=recordFile&fileTime=' . $R_Row['joinTime'] . '&fileName=' . $R_Row['recordFile'] . '&area=' . $R_Row['area'] . '&responseID=' . $R_Row['responseID'];
			$htmlStr .= '<br/>&nbsp;<a href=\'' . $deleURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['action_dele_file'] . $R_Row['recordFile'] . '</a>';
		}

		$EnableQCoreClass->replace('recFileInfo', $htmlStr);
	}
	else {
		switch ($_SESSION['adminRoleType']) {
		case '4':
			$EnableQCoreClass->replace('recFileInfo', '<a href="javascript:void(0);" style="display:{haveMap}" onclick="javascript:showPopWin(\'../System/UploadRecFile.php?surveyID=' . $_GET['surveyID'] . '&responseID=' . $_GET['responseID'] . '\', 700, 300, refreshParent, null,\'重新上传全程录音|录像文件\')"><b><font color=red>重新上传全程录音|录像文件</font></b></a>');
			break;

		default:
			$EnableQCoreClass->replace('recFileInfo', '<font color=red>' . $R_Row['recordFile'] . '<br/>(全程录音|录像文件可能未和数据同步上传)</font>');
			break;
		}
	}
}
else {
	switch ($_SESSION['adminRoleType']) {
	case '4':
		if ($R_Row['authStat'] == 1) {
			$EnableQCoreClass->replace('isHaveRecFile', 'none');
			$EnableQCoreClass->replace('recFileInfo', '');
		}
		else {
			if (($Sur_G_Row['isRecord'] == 1) || ($Sur_G_Row['isRecord'] == 2)) {
				$EnableQCoreClass->replace('isHaveRecFile', '');
				$EnableQCoreClass->replace('recFileInfo', '<a href="javascript:void(0);" style="display:{haveMap}" onclick="javascript:showPopWin(\'../System/UploadRecFile.php?surveyID=' . $_GET['surveyID'] . '&responseID=' . $_GET['responseID'] . '\', 700, 300, refreshParent, null,\'重新上传全程录音|录像文件\')"><b><font color=red>重新上传全程录音|录像文件</font></b></a>');
			}
			else {
				$EnableQCoreClass->replace('isHaveRecFile', 'none');
				$EnableQCoreClass->replace('recFileInfo', '');
			}
		}

		break;

	default:
		$EnableQCoreClass->replace('isHaveRecFile', 'none');
		$EnableQCoreClass->replace('recFileInfo', '');
		break;
	}
}

$theResponseID = $_GET['responseID'];
$thePageSurveyID = $_GET['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = ($R_Row['replyPage'] == 0 ? count($pageBreak) + 1 : $R_Row['replyPage']);

if ($Sur_G_Row['isAllData'] == 1) {
	foreach ($QtnListArray as $questionID => $theQtnArray) {
		$surveyID = $_GET['surveyID'];
		$joinTime = $R_Row['joinTime'];
		$ModuleName = $Module[$theQtnArray['questionType']];
		$EnableQCoreClass->replace('questionID', $questionID);

		switch ($_SESSION['adminRoleType']) {
		case '4':
			if ($haveEditDataAuth == 1) {
				if (in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
					$EnableQCoreClass->replace('isSingleDataModi', 'none');
				}
				else {
					$EnableQCoreClass->replace('isSingleDataModi', '');
				}
			}
			else {
				$EnableQCoreClass->replace('isSingleDataModi', 'none');
			}

			break;

		case '7':
			$EnableQCoreClass->replace('isSingleDataAuth', 'none');
			$EnableQCoreClass->replace('authDataQtnURL', '');
			$EnableQCoreClass->replace('authActionName', '');
			$EnableQCoreClass->replace('authActionName0', '');

			if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
				switch ($R_Row['authStat']) {
				case '0':
					if (($isAuthPassport == 1) && in_array($R_Row['overFlag'], array(1, 3)) && (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID']))) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $authURL);
						$EnableQCoreClass->replace('authActionName', '审核');
						$EnableQCoreClass->replace('authActionName0', '审核回复数据');
					}

					break;

				case '1':
					if ($R_Row['appStat'] == 3) {
						$hSQL = ' SELECT questionID FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $R_Row['responseID'] . '\' AND surveyID = \'' . $surveyID . '\' AND questionID = \'' . $questionID . '\' AND isAppData =1 ';
						$hRow = $DB->queryFirstRow($hSQL);
						if (($isAppAuthPassport == 1) && (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) && $hRow) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '核准');
							$EnableQCoreClass->replace('authActionName0', '核准申诉数据');
						}
					}

					break;

				case '3':
					if ($R_Row['adminID'] == $_SESSION['administratorsID']) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $authURL);
						$EnableQCoreClass->replace('authActionName', '审核');
						$EnableQCoreClass->replace('authActionName0', '审核回复数据');
					}

					break;
				}
			}

			break;

		case '1':
		case '2':
		case '5':
			$EnableQCoreClass->replace('isSingleDataAuth', 'none');
			$EnableQCoreClass->replace('authDataQtnURL', '');
			$EnableQCoreClass->replace('authActionName', '');
			$EnableQCoreClass->replace('authActionName0', '');

			if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
				switch ($R_Row['authStat']) {
				case '0':
					if (in_array($R_Row['overFlag'], array(1, 3))) {
						if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '审核');
							$EnableQCoreClass->replace('authActionName0', '审核回复数据');
						}
					}
					else if ($R_Row['overFlag'] != 2) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
						$EnableQCoreClass->replace('authActionName', '修改');
						$EnableQCoreClass->replace('authActionName0', '修改回复数据');
					}

					break;

				case '1':
					if ($R_Row['appStat'] == 3) {
						$hSQL = ' SELECT questionID FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $R_Row['responseID'] . '\' AND surveyID = \'' . $surveyID . '\' AND questionID = \'' . $questionID . '\' AND isAppData =1 ';
						$hRow = $DB->queryFirstRow($hSQL);
						if ((($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) && $hRow) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '核准');
							$EnableQCoreClass->replace('authActionName0', '核准申诉数据');
						}
					}
					else if ($R_Row['overFlag'] != 2) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
						$EnableQCoreClass->replace('authActionName', '修改');
						$EnableQCoreClass->replace('authActionName0', '修改回复数据');
					}

					break;

				case '2':
					if ($R_Row['overFlag'] != 2) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
						$EnableQCoreClass->replace('authActionName', '修改');
						$EnableQCoreClass->replace('authActionName0', '修改回复数据');
					}

					break;

				case '3':
					if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
						$EnableQCoreClass->replace('isSingleDataAuth', '');
						$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
						$EnableQCoreClass->replace('authDataQtnURL', $authURL);
						$EnableQCoreClass->replace('authActionName', '审核');
						$EnableQCoreClass->replace('authActionName0', '审核回复数据');
					}

					break;
				}
			}

			break;
		}

		if ($_SESSION['adminRoleType'] == '3') {
			if (!in_array($questionID, $forbidViewIdValue)) {
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				$EnableQCoreClass->parse('question', 'QUESTION', true);
			}
		}
		else {
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}
}
else {
	$check_survey_conditions_list = '';

	foreach ($pageQtnList as $tmp => $thePageQtnList) {
		if ($thisPageStep < $tmp) {
			break;
		}

		foreach ($thePageQtnList as $questionID) {
			$theQtnArray = $QtnListArray[$questionID];
			$surveyID = $_GET['surveyID'];
			$joinTime = $R_Row['joinTime'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			$EnableQCoreClass->replace('questionID', $questionID);

			switch ($_SESSION['adminRoleType']) {
			case '4':
				if ($haveEditDataAuth == 1) {
					if (in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
						$EnableQCoreClass->replace('isSingleDataModi', 'none');
					}
					else {
						$EnableQCoreClass->replace('isSingleDataModi', '');
					}
				}
				else {
					$EnableQCoreClass->replace('isSingleDataModi', 'none');
				}

				break;

			case '7':
				$EnableQCoreClass->replace('isSingleDataAuth', 'none');
				$EnableQCoreClass->replace('authDataQtnURL', '');
				$EnableQCoreClass->replace('authActionName', '');
				$EnableQCoreClass->replace('authActionName0', '');

				if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
					switch ($R_Row['authStat']) {
					case '0':
						if (($isAuthPassport == 1) && in_array($R_Row['overFlag'], array(1, 3)) && (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID']))) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '审核');
							$EnableQCoreClass->replace('authActionName0', '审核回复数据');
						}

						break;

					case '1':
						if ($R_Row['appStat'] == 3) {
							$hSQL = ' SELECT questionID FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $R_Row['responseID'] . '\' AND surveyID = \'' . $surveyID . '\' AND questionID = \'' . $questionID . '\' AND isAppData =1 ';
							$hRow = $DB->queryFirstRow($hSQL);
							if (($isAppAuthPassport == 1) && (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) && $hRow) {
								$EnableQCoreClass->replace('isSingleDataAuth', '');
								$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $R_Row['responseID'];
								$EnableQCoreClass->replace('authDataQtnURL', $authURL);
								$EnableQCoreClass->replace('authActionName', '核准');
								$EnableQCoreClass->replace('authActionName0', '核准申诉数据');
							}
						}

						break;

					case '3':
						if ($R_Row['adminID'] == $_SESSION['administratorsID']) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '审核');
							$EnableQCoreClass->replace('authActionName0', '审核回复数据');
						}

						break;
					}
				}

				break;

			case '1':
			case '2':
			case '5':
				$EnableQCoreClass->replace('isSingleDataAuth', 'none');
				$EnableQCoreClass->replace('authDataQtnURL', '');
				$EnableQCoreClass->replace('authActionName', '');
				$EnableQCoreClass->replace('authActionName0', '');

				if (!in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
					switch ($R_Row['authStat']) {
					case '0':
						if (in_array($R_Row['overFlag'], array(1, 3))) {
							if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
								$EnableQCoreClass->replace('isSingleDataAuth', '');
								$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
								$EnableQCoreClass->replace('authDataQtnURL', $authURL);
								$EnableQCoreClass->replace('authActionName', '审核');
								$EnableQCoreClass->replace('authActionName0', '审核回复数据');
							}
						}
						else if ($R_Row['overFlag'] != 2) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
							$EnableQCoreClass->replace('authActionName', '修改');
							$EnableQCoreClass->replace('authActionName0', '修改回复数据');
						}

						break;

					case '1':
						if ($R_Row['appStat'] == 3) {
							$hSQL = ' SELECT questionID FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $R_Row['responseID'] . '\' AND surveyID = \'' . $surveyID . '\' AND questionID = \'' . $questionID . '\' AND isAppData =1 ';
							$hRow = $DB->queryFirstRow($hSQL);
							if ((($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) && $hRow) {
								$EnableQCoreClass->replace('isSingleDataAuth', '');
								$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $R_Row['responseID'];
								$EnableQCoreClass->replace('authDataQtnURL', $authURL);
								$EnableQCoreClass->replace('authActionName', '核准');
								$EnableQCoreClass->replace('authActionName0', '核准申诉数据');
							}
						}
						else if ($R_Row['overFlag'] != 2) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
							$EnableQCoreClass->replace('authActionName', '修改');
							$EnableQCoreClass->replace('authActionName0', '修改回复数据');
						}

						break;

					case '2':
						if ($R_Row['overFlag'] != 2) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$modiURL = $thisProg . '&Does=ModiData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $modiURL);
							$EnableQCoreClass->replace('authActionName', '修改');
							$EnableQCoreClass->replace('authActionName0', '修改回复数据');
						}

						break;

					case '3':
						if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
							$EnableQCoreClass->replace('isSingleDataAuth', '');
							$authURL = $thisProg . '&Does=AuthData&responseID=' . $R_Row['responseID'];
							$EnableQCoreClass->replace('authDataQtnURL', $authURL);
							$EnableQCoreClass->replace('authActionName', '审核');
							$EnableQCoreClass->replace('authActionName0', '审核回复数据');
						}

						break;
					}
				}

				break;
			}

			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($questionID, $forbidViewIdValue)) {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
					$EnableQCoreClass->parse('question', 'QUESTION', true);
				}
			}
			else {
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				$EnableQCoreClass->parse('question', 'QUESTION', true);
			}
		}
	}
}

if ($Sur_G_Row['isAllData'] == 1) {
	$EnableQCoreClass->replace('check_survey_conditions_list', '');
}
else {
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
}

switch ($R_Row['dataSource']) {
case '0':
default:
	$dataForm = '未知数据来源';
	break;

case '1':
	$dataForm = 'PC浏览器';
	break;

case '2':
	$dataForm = '移动浏览器';
	break;

case '3':
	$dataForm = '安卓样本App';
	break;

case '4':
	$dataForm = 'PC访员录入';
	break;

case '5':
	$dataForm = '在线访员App';
	break;

case '6':
	$dataForm = '离线访员App';
	break;

case '7':
	$dataForm = 'Excel数据导入';
	break;

case '8':
	$dataForm = '问卷数据迁移';
	break;
}

if ($R_Row['uniDataCode'] != '') {
	$this_uniDataCode = explode('######', base64_decode($R_Row['uniDataCode']));
	$EnableQCoreClass->replace('uniDataCode', $this_uniDataCode[0] . ' (' . $dataForm . ')');
}
else {
	$EnableQCoreClass->replace('uniDataCode', $dataForm);
}

if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName'] . ' (任务代号：' . $R_Row['taskID'] . ')');
}
else {
	$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName']);
}

$EnableQCoreClass->replace('ipAddress', $R_Row['ipAddress']);
$EnableQCoreClass->replace('area', $R_Row['area']);
$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $R_Row['joinTime']));
$EnableQCoreClass->replace('submitTime', $R_Row['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['submitTime']));
$EnableQCoreClass->replace('uploadTime', $R_Row['uploadTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['uploadTime']));
$EnableQCoreClass->replace('overTime', sectotime($R_Row['overTime']));

switch ($R_Row['overFlag']) {
case '0':
default:
	$EnableQCoreClass->replace('overFlag', $lang['result_no_all']);
	break;

case '1':
	$EnableQCoreClass->replace('overFlag', $lang['result_have_all']);
	break;

case '2':
	$EnableQCoreClass->replace('overFlag', $lang['result_to_quota']);
	break;

case '3':
	$EnableQCoreClass->replace('overFlag', $lang['result_in_export']);
	break;
}

if ($R_Row['fingerFile'] != '') {
	$EnableQCoreClass->replace('isFingerDrawing', '');

	if ($Sur_G_Row['custDataPath'] == '') {
		$filePath = $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
	}
	else {
		$filePath = $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
	}

	$fileName = $filePath . trim($R_Row['fingerFile']);

	if (file_exists($Config['absolutenessPath'] . $fileName)) {
		$imgSize = @getimagesize($Config['absolutenessPath'] . $fileName);

		if (400 < $imgSize[0]) {
			$picStr = '<IMG SRC=\'' . $fileName . '\' border=0 onmousewheel="return dynimgsize(this)" width=400>';
		}
		else {
			$picStr = '<IMG SRC=\'' . $fileName . '\' border=0 onmousewheel="return dynimgsize(this)">';
		}

		$htmlStr = $picStr;
	}
	else {
		$htmlStr = $R_Row['fingerFile'];
		$htmlStr .= '<font color=red>上传的签名文件不存在或已被删除</font>';
	}

	$EnableQCoreClass->replace('fingerFile', $htmlStr);
}
else {
	$EnableQCoreClass->replace('isFingerDrawing', 'none');
	$EnableQCoreClass->replace('fingerFile', '');
}

$EnableQCoreClass->replace('haveQuickAuthAction', 'none');
$haveQuickAuthAction = false;

switch ($_SESSION['adminRoleType']) {
case '1':
case '2':
case '5':
	switch ($R_Row['authStat']) {
	case '0':
		if (in_array($R_Row['overFlag'], array(1, 3))) {
			if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
				$EnableQCoreClass->replace('haveQuickAuthAction', '');
				$haveQuickAuthAction = true;
			}
		}

		break;

	case '3':
		if (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID'])) {
			$EnableQCoreClass->replace('haveQuickAuthAction', '');
			$haveQuickAuthAction = true;
		}

		break;
	}

	break;

case '7':
	switch ($R_Row['authStat']) {
	case '0':
		if (($isAuthPassport == 1) && in_array($R_Row['overFlag'], array(1, 3)) && (($R_Row['version'] == 0) || ($R_Row['version'] == $_SESSION['administratorsID']))) {
			$EnableQCoreClass->replace('haveQuickAuthAction', '');
			$haveQuickAuthAction = true;
		}

		break;

	case '3':
		if ($R_Row['adminID'] == $_SESSION['administratorsID']) {
			$EnableQCoreClass->replace('haveQuickAuthAction', '');
			$haveQuickAuthAction = true;
		}

		break;
	}

	break;
}

if ($haveQuickAuthAction == true) {
	$theFatherGroup = _getupgroupnode($_SESSION['adminRoleGroupID']);
	$theFatherGroup[] = $_SESSION['adminRoleGroupID'];
	$OptSQL = ' SELECT a.administratorsID,a.administratorsName,a.nickName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . VIEWUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' AND a.isAdmin = 7 AND b.isAuth=1 AND a.userGroupID IN (' . implode(',', $theFatherGroup) . ') AND a.administratorsID != \'' . $_SESSION['administratorsID'] . '\' ORDER BY a.administratorsID ASC ';
	$OptResult = $DB->query($OptSQL);
	$nextUserNameList = '';

	while ($OptRow = $DB->queryArray($OptResult)) {
		$nextUserNameList .= '<option value=\'' . $OptRow['administratorsID'] . '\'>' . _getuserallname($OptRow['nickName'] . '(' . $OptRow['administratorsName'] . ')', $OptRow['userGroupID'], $OptRow['groupType']) . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('nextUserNameList', $nextUserNameList);
}
else {
	$EnableQCoreClass->replace('nextUserNameList', '');
}

switch ($R_Row['authStat']) {
case '0':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;

case '1':
	switch ($R_Row['appStat']) {
	case '0':
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
		break;

	default:
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat'] . '_' . $R_Row['appStat']]);
		break;
	}

	break;

case '2':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;

case '3':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;
}

$aSQL = ' SELECT a.administratorsID,a.administratorsName,a.userGroupID,a.groupType,b.taskTime,b.authStat,b.appStat,b.reason,b.nextUserId FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND b.responseID =\'' . $_GET['responseID'] . '\' ORDER BY b.taskTime DESC ';
$aResult = $DB->query($aSQL);
$aRecNum = $DB->_getNumRows($aResult);

if ($aRecNum == 0) {
	$EnableQCoreClass->replace('authInfoList', '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowAuthInfoFile', 'uAuthInfoList.html');
	$EnableQCoreClass->set_CycBlock('ShowAuthInfoFile', 'AUTHINFO', 'authinfo');
	$EnableQCoreClass->replace('authinfo', '');
	$tmp = 0;

	while ($aRow = $DB->queryArray($aResult)) {
		$tmp++;
		$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['administratorsName'], $aRow['userGroupID'], $aRow['groupType']);

		switch ($aRow['authStat']) {
		case '0':
		case '2':
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']];
			$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
			break;

		case '3':
			$nSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $aRow['nextUserId'] . '\' ';
			$nRow = $DB->queryFirstRow($nSQL);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']] . '，并提交给：' . _getuserallname($nRow['administratorsName'], $nRow['userGroupID'], $nRow['groupType']) . '再审核';
			$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
			break;

		case '1':
			switch ($aRow['appStat']) {
			case '0':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']];
				$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
				break;

			case '3':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据状态为：' . $lang['authStat_' . $aRow['authStat'] . '_' . $aRow['appStat']];
				break;

			case '1':
			case '2':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat'] . '_' . $aRow['appStat']];
				$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
				break;
			}

			break;
		}

		$EnableQCoreClass->replace('authInfoContent', $authInfoList);
		$EnableQCoreClass->parse('authinfo', 'AUTHINFO', true);
	}

	$EnableQCoreClass->replace('authInfoList', $EnableQCoreClass->parse('ShowAuthPage', 'ShowAuthInfoFile'));
}

$thisURL = $thisProg . '&Does=View&responseID=';
$theNextId = getnextdataid($_GET['responseID'], 1);

if ($theNextId == 0) {
	$EnableQCoreClass->replace('next1URL', $thisProg);
}
else {
	$EnableQCoreClass->replace('next1URL', $thisURL . $theNextId);
}

$theLastId = getnextdataid($_GET['responseID'], 2);

if ($theLastId == 0) {
	$EnableQCoreClass->replace('last1URL', $thisProg);
}
else {
	$EnableQCoreClass->replace('last1URL', $thisURL . $theLastId);
}

if (isset($_GET['isAwardView']) && ($_GET['isAwardView'] == 1)) {
	$EnableQCoreClass->replace('isAwardView', 'none');
}
else {
	$EnableQCoreClass->replace('isAwardView', '');
}

$ResultDetail = $EnableQCoreClass->parse('ResultDetail', 'ResultDetailFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
$ResultDetail = str_replace($All_Path, '', $ResultDetail);
$ResultDetail = str_replace('PerUserData', '../PerUserData', $ResultDetail);

if ($Config['dataDirectory'] != 'PerUserData') {
	$ResultDetail = str_replace($Config['dataDirectory'], '../' . $Config['dataDirectory'], $ResultDetail);
}

echo $ResultDetail;
exit();

?>
