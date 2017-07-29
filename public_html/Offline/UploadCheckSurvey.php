<?php
//dezend by http://www.yunlu99.com/
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
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	header('Content-Type:text/html; charset=gbk');
	exit('false|1|' . $lang['license_no_android']);
}

$SQL = ' SELECT surveyID,status,isUploadRec FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
$SRow = $DB->queryFirstRow($SQL);
header('Content-Type:text/html; charset=gbk');

if (!$SRow) {
	exit('false|1|调查问卷在系统中不存在，无法进行数据上传，请联系系统管理员');
}

if ($SRow['status'] == 0) {
	exit('false|1|调查问卷处在设计状态，无法进行数据上传，请联系系统管理员');
}

$SQL = ' SELECT administratorsID FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' LIMIT 0,1 ';
$HaveRow = $DB->queryFirstRow($SQL);

if (!$HaveRow) {
	exit('false|1|您对调查问卷不具有操作权限，无法进行数据上传，请联系系统管理员');
}

$valueLogicQtnList = array();

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Qtn' . $_POST['surveyID']) . '.php')) {
	$theSID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Qtn' . $_POST['surveyID']) . '.php';
$this_fields_list = '';
$this_fileds_type = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if ($theQtnArray['questionType'] != '9') {
		$surveyID = $DRow['surveyID'];
		$ModuleName = $Module[$theQtnArray['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
	}
}

$this_fields_list = substr($this_fields_list, 0, -1);
$survey_fields_name = explode('|', $this_fields_list);
$all_survey_fields = '';
$i = 0;

for (; $i < count($survey_fields_name); $i++) {
	$all_survey_fields .= $survey_fields_name[$i] . ',';
}

$all_survey_fields = substr($all_survey_fields, 0, -1);

if (trim($all_survey_fields) != trim($_POST['this_survey_fields'])) {
	exit('false|1|调查问卷本地存储结构与服务器不一致，无法进行数据上传，请联系系统管理员');
}

exit('true|' . $SRow['isUploadRec']);

?>
