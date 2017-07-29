<?php
//dezend by http://www.yunlu99.com/
function getlocalresources($htmlString, $fSurveyID)
{
	if ((trim($htmlString) == '') || (trim($htmlString) == '0')) {
		return $htmlString;
	}

	$_obf_M_YLuDhf9Q__ = str_get_html($htmlString);

	foreach ($_obf_M_YLuDhf9Q__->find('img') as $_obf_60GquoKMPw__) {
		$_obf_gsSNQrEsM8dsv7v5 = str_replace('&quot;', '', $_obf_60GquoKMPw__->src);
		$_obf_jZJFIP3Dx6nXUx4_ = copyfile($_obf_gsSNQrEsM8dsv7v5, $fSurveyID);
		$htmlString = str_replace($_obf_60GquoKMPw__->src, '"file:///mnt/sdcard/enableq_offline/datas/' . $fSurveyID . '/' . $_obf_jZJFIP3Dx6nXUx4_ . '"', $htmlString);
	}

	foreach ($_obf_M_YLuDhf9Q__->find('embed') as $_obf_60GquoKMPw__) {
		$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);
		$_obf_IgWluZoQI73_AQ__ = str_replace($_obf_eHCKgWnq_TI_, '', $_obf_60GquoKMPw__->src);
		if ((strtolower($_obf_60GquoKMPw__->src) != 'js/flvplayer.swf') && (strtolower($_obf_IgWluZoQI73_AQ__) != 'js/flvplayer.swf')) {
			$_obf_M8rui_ds6XKi = $_obf_60GquoKMPw__->src;
			$_obf_22NQGil7DKyM19phg4A_ = copyfile($_obf_M8rui_ds6XKi, $fSurveyID);
			$htmlString = str_replace($_obf_60GquoKMPw__->src, 'file:///mnt/sdcard/enableq_offline/datas/' . $fSurveyID . '/' . $_obf_22NQGil7DKyM19phg4A_, $htmlString);
		}
		else if ($_obf_60GquoKMPw__->flashvars != '') {
			$_obf_8SzPcs_zQmya = explode('&', str_replace('&quot;', '', $_obf_60GquoKMPw__->flashvars));
			$_obf_V_hlvve1Z9ZzjMw_ = explode('=', $_obf_8SzPcs_zQmya[4]);
			$_obf_AkBg7s7bM4lbHemrLOGnKw__ = copyfile($_obf_V_hlvve1Z9ZzjMw_[1], $fSurveyID);
			$_obf_At__Qz46FmtxpssU = 'file:///mnt/sdcard/enableq_offline/datas/' . $fSurveyID . '/' . $_obf_AkBg7s7bM4lbHemrLOGnKw__;
			$_obf_nB7q6scxXuw_ = ($_obf_60GquoKMPw__->width != '' ? $_obf_60GquoKMPw__->width : 320);
			$_obf_N8ttqXVlXIiO = ($_obf_60GquoKMPw__->height != '' ? $_obf_60GquoKMPw__->height : 280);
			$_obf_wCUOaOCCwB9etA__ = '&nbsp;<a href="javascript:void(0);" onclick="rexseeVideoPlayer.start(\'' . $_obf_At__Qz46FmtxpssU . '\',\'window-align:center;window-vertical-align:middle;window-style:light;window-dim-amount:0;window-moveable:true;window-modeless:true;window-cancelable:false;width:' . $_obf_nB7q6scxXuw_ . ';height:' . $_obf_N8ttqXVlXIiO . ';border-width:0px;\',false);"><img src="resources/playvideo.png" border=0 align=absmiddle></a>';
			$_obf_wCUOaOCCwB9etA__ .= '&nbsp;<a href="javascript:void(0);" onclick="rexseeVideoPlayer.stop();"><img src="resources/stopvideo.png" border=0 align=absmiddle></a>&nbsp;';
			$_obf_wWywzA__ = '/<embed[^>]*(src=\'JS\\/flvplayer.swf\'|src=' . str_replace('/', '\\/', substr($_obf_eHCKgWnq_TI_, 0, -1)) . '\\/JS\\/flvplayer.swf)[^>]*vdo=' . str_replace('/', '\\/', $_obf_V_hlvve1Z9ZzjMw_[1]) . '[^>]*><\\/embed>/si';
			$htmlString = preg_replace($_obf_wWywzA__, $_obf_wCUOaOCCwB9etA__, $htmlString);
		}
	}

	return $htmlString;
}

function copyfile($source, $fSurveyID)
{
	global $LocalResources;
	global $Config;
	global $tmpFilePathName;
	$_obf_DfXWN0u3Qlf5yOo_ = $Config['absolutenessPath'] . '/PerUserData/tmp/' . $tmpFilePathName . '/';
	createdir($_obf_DfXWN0u3Qlf5yOo_);
	$_obf_A_N_tzzf = explode('.', @basename($source));
	$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
	$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
	$_obf_tuyW_qxvlbFGk_kcfCs_ = hash('md5', date('ymdHis') . rand(1, 99999999) . $_SESSION['administratorsID'] . session_id());
	$_obf_nLeBi91MrXfV8AS3fqg_ = $_obf_tuyW_qxvlbFGk_kcfCs_ . '.' . $_obf_MxPKhZcSxB7b;
	$thisNewFileName = $_obf_DfXWN0u3Qlf5yOo_ . $_obf_nLeBi91MrXfV8AS3fqg_;

	if (substr($source, 0, 1) == '/') {
		$source = 'http://' . $_SERVER['HTTP_HOST'] . $source;
	}

	$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);
	$source = str_replace($_obf_eHCKgWnq_TI_, '', $source);

	if (eregi('^(http|ftp)\\:\\/\\/', $source)) {
		$_obf_AeAlUSoSEAIrWrbt = @fopen($source, 'rb');

		if ($_obf_AeAlUSoSEAIrWrbt) {
			if ($_obf_9E4_ = fopen($thisNewFileName, 'w')) {
				while (!feof($_obf_AeAlUSoSEAIrWrbt)) {
					$_obf_HVjSRcHp = fread($_obf_AeAlUSoSEAIrWrbt, 40960);
					fwrite($_obf_9E4_, $_obf_HVjSRcHp);
				}

				fclose($_obf_9E4_);
				fclose($_obf_AeAlUSoSEAIrWrbt);
			}

			$LocalResources[] = $_obf_eHCKgWnq_TI_ . 'PerUserData/tmp/' . $tmpFilePathName . '/' . $_obf_nLeBi91MrXfV8AS3fqg_;
		}
	}
	else {
		$source = '../' . $source;

		if (copy($source, $thisNewFileName)) {
			$LocalResources[] = $_obf_eHCKgWnq_TI_ . 'PerUserData/tmp/' . $tmpFilePathName . '/' . $_obf_nLeBi91MrXfV8AS3fqg_;
		}
	}

	return $_obf_nLeBi91MrXfV8AS3fqg_;
}

function downloadpicfile($source, $fSurveyID, $createDate, $fOptionID, $haveLargePic = 0)
{
	global $LocalResources;
	global $Config;
	global $tmpFilePathName;
	if ((trim($source) == '') || (trim($source) == '0')) {
		return $source;
	}

	$_obf_DfXWN0u3Qlf5yOo_ = $Config['absolutenessPath'] . 'PerUserData/tmp/' . $tmpFilePathName . '/';
	createdir($_obf_DfXWN0u3Qlf5yOo_);
	$_obf_30V1vP8O9WzwAP0_ = $Config['absolutenessPath'] . 'PerUserData/p/' . date('Y-m', $createDate) . '/' . date('d', $createDate) . '/';
	$_obf_A_N_tzzf = explode('.', $source);
	$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
	$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
	$_obf_tuyW_qxvlbFGk_kcfCs_ = hash('md5', date('ymdHis', $createDate) . rand(1, 99999999) . $_SESSION['administratorsID'] . '_' . $fOptionID . session_id());
	$_obf_nLeBi91MrXfV8AS3fqg_ = $_obf_tuyW_qxvlbFGk_kcfCs_ . '.' . $_obf_MxPKhZcSxB7b;

	if ($haveLargePic == 1) {
		if (copy($_obf_30V1vP8O9WzwAP0_ . 's_' . $source, $_obf_DfXWN0u3Qlf5yOo_ . 's_' . $_obf_nLeBi91MrXfV8AS3fqg_) && copy($_obf_30V1vP8O9WzwAP0_ . $source, $_obf_DfXWN0u3Qlf5yOo_ . $_obf_nLeBi91MrXfV8AS3fqg_)) {
			$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);
			$LocalResources[] = $_obf_eHCKgWnq_TI_ . 'PerUserData/tmp/' . $tmpFilePathName . '/' . $_obf_nLeBi91MrXfV8AS3fqg_;
			$LocalResources[] = $_obf_eHCKgWnq_TI_ . 'PerUserData/tmp/' . $tmpFilePathName . '/' . 's_' . $_obf_nLeBi91MrXfV8AS3fqg_;
			return $_obf_nLeBi91MrXfV8AS3fqg_;
		}
		else {
			return '';
		}
	}
	else if (copy($_obf_30V1vP8O9WzwAP0_ . $source, $_obf_DfXWN0u3Qlf5yOo_ . $_obf_nLeBi91MrXfV8AS3fqg_)) {
		$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);
		$LocalResources[] = $_obf_eHCKgWnq_TI_ . 'PerUserData/tmp/' . $tmpFilePathName . '/' . $_obf_nLeBi91MrXfV8AS3fqg_;
		return $_obf_nLeBi91MrXfV8AS3fqg_;
	}
	else {
		return '';
	}
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

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
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Offline/SimpleHtmlDom.php';

if ($License['isOffline'] != 1) {
	header('Content-Type:text/html; charset=gbk');
	exit('false#######' . $lang['license_no_android']);
}

$tmpFilePathName = trim($_GET['filePathName']);
$LocalResources = array();

if ($_POST['Action'] == 'downloadSurveySubmit') {
	if ($_POST['theSurveyID'] != '') {
		$DSQL = ' SELECT a.surveyID,a.surveyName,a.surveyTitle,a.beginTime,a.surveyInfo,a.surveySubTitle,a.endTime,a.isRecord,a.isLowRecord,a.isPanelFlag,a.isUploadRec,a.offlineCount,a.isCheckStat0,a.isOfflineModi,a.isGpsEnable,a.isFingerDrawing,a.projectType,a.isProcessBar,a.isOfflineDele,a.isRelZero FROM ' . SURVEY_TABLE . ' a,' . INPUTUSERLIST_TABLE . ' b WHERE a.surveyID = b.surveyID AND a.status =1 AND a.beginTime <= \'' . date('Y-m-d') . '\' AND a.endTime > \'' . date('Y-m-d') . '\' AND b.administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND a.surveyID =' . $_POST['theSurveyID'] . ' ORDER BY a.beginTime DESC ';
		$DRow = $DB->queryFirstRow($DSQL);

		if (!$DRow) {
			header('Content-Type:text/html; charset=gbk');
			exit('false#######选择同步的调查问卷不符合下载条件(已删除、未开始、已结束、权限不符、状态不符等)');
		}

		$theSurvey = array();
		$theSurvey[$DRow['surveyID']]['surveyTitle'] = json_replace($DRow['surveyTitle']);
		$theSurvey[$DRow['surveyID']]['beginTime'] = json_replace($DRow['beginTime']);
		$theSurvey[$DRow['surveyID']]['endTime'] = json_replace($DRow['endTime']);
		$theSurvey[$DRow['surveyID']]['isRecord'] = $DRow['isRecord'];
		$theSurvey[$DRow['surveyID']]['isLowRecord'] = $DRow['isLowRecord'];
		$theSurvey[$DRow['surveyID']]['isPanelFlag'] = $DRow['isPanelFlag'];
		$theSurvey[$DRow['surveyID']]['isUploadRec'] = $DRow['isUploadRec'];
		$theSurvey[$DRow['surveyID']]['offlineCount'] = $DRow['offlineCount'];
		$theSurvey[$DRow['surveyID']]['isCheckStat0'] = $DRow['isCheckStat0'];
		$theSurvey[$DRow['surveyID']]['isOfflineModi'] = $DRow['isOfflineModi'];
		$theSurvey[$DRow['surveyID']]['isGpsEnable'] = $DRow['isGpsEnable'];
		$theSurvey[$DRow['surveyID']]['isFingerDrawing'] = $DRow['isFingerDrawing'];
		$theSurvey[$DRow['surveyID']]['projectType'] = $DRow['projectType'];
		$theSurvey[$DRow['surveyID']]['isProcessBar'] = $DRow['isProcessBar'];
		$theSurvey[$DRow['surveyID']]['isOfflineDele'] = $DRow['isOfflineDele'];
		$theSurvey[$DRow['surveyID']]['isRelZero'] = $DRow['isRelZero'];
		$valueLogicQtnList = array();

		if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $DRow['surveyID'] . '/' . md5('Qtn' . $DRow['surveyID']) . '.php')) {
			$theSID = $DRow['surveyID'];
			require ROOT_PATH . 'Includes/MakeCache.php';
		}

		require ROOT_PATH . $Config['cacheDirectory'] . '/' . $DRow['surveyID'] . '/' . md5('Qtn' . $DRow['surveyID']) . '.php';
		$this_fields_list = '';
		$this_fileds_type = '';
		$this_upload_list = '';

		foreach ($QtnListArray as $questionID => $theQtnArray) {
			if ($theQtnArray['questionType'] != '9') {
				if ($theQtnArray['questionType'] == '11') {
					$this_upload_list .= 'option_' . $questionID . ',';
				}

				$surveyID = $DRow['surveyID'];
				$ModuleName = $Module[$theQtnArray['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
			}
		}

		if ($DRow['isFingerDrawing'] == 1) {
			$this_upload_list .= 'fingerFile|';
		}

		if ($this_upload_list != '') {
			$this_upload_list = substr($this_upload_list, 0, -1);
		}

		$theSurvey[$DRow['surveyID']]['uploadlist'] = $this_upload_list;
		$this_fields_list = substr($this_fields_list, 0, -1);
		$survey_fields_name = explode('|', $this_fields_list);
		$this_fileds_type = substr($this_fileds_type, 0, -1);
		$survey_fields_type = explode('|', $this_fileds_type);
		$all_survey_fields = '';
		$thisSQL = ' responseID INTEGER PRIMARY KEY ASC,';
		$thisSQL .= ' administratorsName varchar(255) NOT NULL default \'\',';
		$thisSQL .= ' ipAddress varchar(255) NOT NULL default \'\',';
		$thisSQL .= ' area varchar(50) NOT NULL default \'\',';
		$thisSQL .= ' joinTime int(11) NOT NULL default \'0\',';
		$thisSQL .= ' submitTime int(11) NOT NULL default \'0\',';
		$thisSQL .= ' overTime int(4) NOT NULL default \'0\',';
		$thisSQL .= ' overFlag int(1) NOT NULL default \'0\',';
		$thisSQL .= ' overFlag0 int(1) NOT NULL default \'0\',';
		$thisSQL .= ' taskID int(30) NOT NULL default \'0\',';
		$thisSQL .= ' authStat int(1) NOT NULL default \'0\',';
		$thisSQL .= ' recordFile varchar(60) NOT NULL default \'\',';
		$thisSQL .= ' fingerFile varchar(60) NOT NULL default \'\',';
		$thisSQL .= ' replyPage int(1) NOT NULL default \'0\',';
		$i = 0;

		for (; $i < count($survey_fields_name); $i++) {
			$all_survey_fields .= $survey_fields_name[$i] . ',';

			switch (strtolower($survey_fields_type[$i])) {
			case 'int':
				$thisSQL .= ' ' . $survey_fields_name[$i] . ' int(4) NOT NULL default \'0\',';
				break;

			case 'float':
				$thisSQL .= ' ' . $survey_fields_name[$i] . ' decimal(7,2) NOT NULL default \'0.00\',';
				break;

			default:
				$thisSQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL default \'\',';
				break;
			}
		}

		$all_survey_fields = substr($all_survey_fields, 0, -1);
		$thisSQL .= 'line1Number not null default \'\',deviceId not null default \'\',brand not null default \'\',model not null default \'\',currentCity not null default \'\',simOperatorName not null default \'\',simSerialNumber not null default \'\'';
		$theSurvey[$DRow['surveyID']]['sql'] = $thisSQL;
		$theSurvey[$DRow['surveyID']]['fields'] = $all_survey_fields;
		$theSID = $DRow['surveyID'];
		require ROOT_PATH . 'Offline/BuildSurveyJson.php';
		$theSurvey[$DRow['surveyID']]['downloadlist'] = implode(',', $LocalResources);
		$cacheContent = '';
		$cacheContent .= ' var SurveyListArray = ' . json($theSurvey) . ';' . "\r\n" . '';
		$SQL = ' SELECT taskID FROM ' . TASK_TABLE . ' WHERE surveyID = \'' . $DRow['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ORDER BY taskID ASC ';
		$Result = $DB->query($SQL);
		$theAllTask = array();

		while ($Row = $DB->queryArray($Result)) {
			$theAllTask[] = $Row['taskID'];
		}

		$SQL = ' SELECT taskID FROM ' . $table_prefix . 'response_' . $DRow['surveyID'] . ' WHERE area = \'' . $_SESSION['administratorsName'] . '\' ';
		$Result = $DB->query($SQL);
		$theOverTask = array();

		while ($Row = $DB->queryArray($Result)) {
			$theOverTask[] = $Row['taskID'];
		}

		$theNoOverTask = arraydiff($theAllTask, $theOverTask);

		if (count($theNoOverTask) != 0) {
			$theTask = array();
			$tSQL = ' SELECT a.taskID,a.surveyID,b.userGroupName,b.userGroupDesc FROM ' . TASK_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.surveyID = \'' . $DRow['surveyID'] . '\' AND a.administratorsID= \'' . $_SESSION['administratorsID'] . '\' AND a.taskID = b.userGroupID AND taskID IN (' . implode(',', $theNoOverTask) . ') ORDER BY a.taskID ASC ';
			$tResult = $DB->query($tSQL);

			while ($tRow = $DB->queryArray($tResult)) {
				$theTask[$tRow['taskID']]['surveyID'] = $tRow['surveyID'];
				$theTask[$tRow['taskID']]['taskName'] = json_string($tRow['userGroupName']);
				$theTask[$tRow['taskID']]['taskDesc'] = json_string($tRow['userGroupDesc']);
			}

			$cacheContent .= ' var TaskListArray = ' . json($theTask) . ';';
		}

		header('Content-Type:text/html; charset=gbk');
		exit('true#######' . $cacheContent);
	}
	else {
		header('Content-Type:text/html; charset=gbk');
		exit('false#######未选择需要自服务器同步的调查问卷');
	}
}

?>
