<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit(' Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
_checkroletype('3');
$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
$R_Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'ResultAppData.html');
$EnableQCoreClass->set_CycBlock('ResultDetailFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$isViewPanelInfo = $isViewRecFile = $isViewGpsInfo = true;
$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);

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

if ($_SESSION['ViewBackURL'] != '') {
	$EnableQCoreClass->replace('lastURL', $_SESSION['ViewBackURL']);
}
else {
	$EnableQCoreClass->replace('lastURL', $thisProg);
}

$EnableQCoreClass->replace('thisProg', $thisProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('responseID', $_GET['responseID']);
$isViewAuthInfo = 0;

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

		if ($haveEditDataAuth == 1) {
			$deleURL = $thisProg . '&Does=DeleFile&surveyID=' . $_GET['surveyID'] . '&fields=recordFile&fileTime=' . $R_Row['joinTime'] . '&fileName=' . $R_Row['recordFile'] . '&area=' . $R_Row['area'] . '&responseID=' . $R_Row['responseID'];
			$htmlStr .= '<br/>&nbsp;<a href=\'' . $deleURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['action_dele_file'] . $R_Row['recordFile'] . '</a>';
		}

		$EnableQCoreClass->replace('recFileInfo', $htmlStr);
	}
	else {
		$EnableQCoreClass->replace('recFileInfo', '<font color=red>' . $R_Row['recordFile'] . '<br/>(全程录音|录像文件可能未和数据同步上传)</font>');
	}
}
else {
	$EnableQCoreClass->replace('isHaveRecFile', 'none');
	$EnableQCoreClass->replace('recFileInfo', '');
}

$theResponseID = $_GET['responseID'];
$isAppSurveyData = 1;
$surveyID = $_GET['surveyID'];
$joinTime = $R_Row['joinTime'];

if ($Sur_G_Row['custDataPath'] == '') {
	$evidencePhyPath = $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $joinTime) . '/' . date('d', $joinTime) . '/';
}
else {
	$evidencePhyPath = $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
}

$hSQL = ' SELECT count(*) as countNum FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND fatherId !=0  LIMIT 0,1 ';
$hRow = $DB->queryFirstRow($hSQL);

if ($hRow['countNum'] != 0) {
	$isHaveViewCoeff = 1;
}
else {
	$isHaveViewCoeff = 0;
}

$theForbidAppId = explode(',', $Sur_G_Row['forbidAppId']);

foreach ($QtnListArray as $questionID => $theQtnArray) {
	$ModuleName = $Module[$theQtnArray['questionType']];
	$EnableQCoreClass->replace('questionID', $questionID);

	if ($R_Row['version'] != 0) {
		if ($R_Row['version'] == $_SESSION['administratorsID']) {
			$hSQL = ' SELECT traceTime FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $theResponseID . '\' AND surveyID = \'' . $surveyID . '\' AND questionID = \'' . $questionID . '\' AND isAppData =1 ORDER BY traceTime DESC LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				$hRow = false;
			}
			else {
				$nSQL = ' SELECT taskTime FROM ' . DATA_TASK_TABLE . ' WHERE responseID=\'' . $theResponseID . '\' AND surveyID = \'' . $surveyID . '\' AND authStat =1 AND appStat= 2 AND taskTime >= \'' . $hRow['traceTime'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
				$nRow = $DB->queryFirstRow($nSQL);

				if ($nRow) {
					$hRow = false;
				}
				else {
					$hRow = true;
				}
			}
		}
		else {
			$hRow = false;
		}
	}
	else {
		$hRow = false;
	}

	if (in_array($theQtnArray['questionType'], array('8', '9', '12', '30')) || $hRow || in_array($questionID, $theForbidAppId)) {
		$EnableQCoreClass->replace('isHaveApp', 'none');
	}
	else {
		$EnableQCoreClass->replace('isHaveApp', '');
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

$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName']);
$EnableQCoreClass->replace('ipAddress', $R_Row['ipAddress']);
$EnableQCoreClass->replace('area', $R_Row['area']);
$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $R_Row['joinTime']));
$EnableQCoreClass->replace('submitTime', $R_Row['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['submitTime']));
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

$aSQL = ' SELECT a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.taskTime,b.authStat,b.appStat,b.reason,b.nextUserId FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND b.responseID =\'' . $_GET['responseID'] . '\' ORDER BY b.taskTime DESC ';
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
		$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['nickName'], $aRow['userGroupID'], $aRow['groupType']);

		switch ($aRow['authStat']) {
		case '0':
		case '2':
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']];
			$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
			break;

		case '3':
			$nSQL = ' SELECT nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $aRow['nextUserId'] . '\' ';
			$nRow = $DB->queryFirstRow($nSQL);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']] . '，并提交给：' . _getuserallname($nRow['nickName'], $nRow['userGroupID'], $nRow['groupType']) . '再审核';
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
