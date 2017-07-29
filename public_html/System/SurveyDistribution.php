<?php
//dezend by http://www.yunlu99.com/
function getlocalresources($htmlString)
{
	global $theSID;
	$_obf_cGLQvxNxOi0_ = $theSID;
	if ((trim($htmlString) == '') || (trim($htmlString) == '0')) {
		return $htmlString;
	}

	$_obf_M_YLuDhf9Q__ = str_get_html($htmlString);

	foreach ($_obf_M_YLuDhf9Q__->find('img') as $_obf_60GquoKMPw__) {
		$_obf_gsSNQrEsM8dsv7v5 = str_replace('&quot;', '', $_obf_60GquoKMPw__->src);
		$_obf_jZJFIP3Dx6nXUx4_ = copyfile($_obf_gsSNQrEsM8dsv7v5, $_obf_cGLQvxNxOi0_);
		$htmlString = str_replace($_obf_60GquoKMPw__->src, 'PerUserData/p/{#d' . $_obf_cGLQvxNxOi0_ . '#}/' . $_obf_jZJFIP3Dx6nXUx4_, $htmlString);
	}

	foreach ($_obf_M_YLuDhf9Q__->find('embed') as $_obf_60GquoKMPw__) {
		$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
		$_obf_IgWluZoQI73_AQ__ = str_replace($_obf_eHCKgWnq_TI_, '', $_obf_60GquoKMPw__->src);
		$_obf_cqywhu_9egiHlQ__ = $_obf_60GquoKMPw__;

		if (strtolower($_obf_60GquoKMPw__->src) != 'js/flvplayer.swf') {
			$_obf_22NQGil7DKyM19phg4A_ = copyfile($_obf_60GquoKMPw__->src, $_obf_cGLQvxNxOi0_);
			$htmlString = str_replace($_obf_60GquoKMPw__->src, 'PerUserData/p/{#d' . $_obf_cGLQvxNxOi0_ . '#}/' . $_obf_22NQGil7DKyM19phg4A_, $htmlString);
		}

		if ($_obf_60GquoKMPw__->flashvars != '') {
			$_obf_8SzPcs_zQmya = explode('&', str_replace('&quot;', '', $_obf_60GquoKMPw__->flashvars));
			$_obf_V_hlvve1Z9ZzjMw_ = explode('=', $_obf_8SzPcs_zQmya[4]);
			$_obf_AkBg7s7bM4lbHemrLOGnKw__ = copyfile($_obf_V_hlvve1Z9ZzjMw_[1], $_obf_cGLQvxNxOi0_);
			$htmlString = str_replace($_obf_V_hlvve1Z9ZzjMw_[1], '{#PerUserData#}/p/{#d' . $_obf_cGLQvxNxOi0_ . '#}/' . $_obf_AkBg7s7bM4lbHemrLOGnKw__, $htmlString);
		}
	}

	return $htmlString;
}

function copyfile($source, $surveyID)
{
	global $LocalResources;
	global $Config;
	$_obf_DfXWN0u3Qlf5yOo_ = $Config['absolutenessPath'] . '/PerUserData/tmp/d' . $surveyID . '/';
	createdir($_obf_DfXWN0u3Qlf5yOo_);
	$_obf_A_N_tzzf = explode('.', @basename($source));
	$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
	$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
	$_obf_nLeBi91MrXfV8AS3fqg_ = date('ymdHis') . rand(1, 9999) . $_SESSION['administratorsID'] . '.' . $_obf_MxPKhZcSxB7b;
	$thisNewFileName = $_obf_DfXWN0u3Qlf5yOo_ . $_obf_nLeBi91MrXfV8AS3fqg_;

	if (substr($source, 0, 1) == '/') {
		$source = 'http://' . $_SERVER['HTTP_HOST'] . $source;
	}

	$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
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
		}
	}
	else {
		$source = '../' . $source;
		@copy($source, $thisNewFileName);
	}

	return $_obf_nLeBi91MrXfV8AS3fqg_;
}

function downloadpicfile($source, $surveyID, $createDate, $optionID)
{
	global $LocalResources;
	global $Config;
	if ((trim($source) == '') || (trim($source) == '0')) {
		return $source;
	}

	$_obf_DfXWN0u3Qlf5yOo_ = $Config['absolutenessPath'] . '/PerUserData/tmp/d' . $surveyID . '/';
	createdir($_obf_DfXWN0u3Qlf5yOo_);
	$_obf_30V1vP8O9WzwAP0_ = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $createDate) . '/' . date('d', $createDate) . '/';
	if (copy($_obf_30V1vP8O9WzwAP0_ . $source, $_obf_DfXWN0u3Qlf5yOo_ . $source) && copy($_obf_30V1vP8O9WzwAP0_ . 's_' . $source, $_obf_DfXWN0u3Qlf5yOo_ . 's_' . $source)) {
		return $source;
	}
	else {
		return '';
	}
}

function downmsgimagefile($source, $surveyID)
{
	global $Config;
	if ((trim($source) == '') || (trim($source) == '0')) {
		return $source;
	}

	$_obf_DfXWN0u3Qlf5yOo_ = $Config['absolutenessPath'] . '/PerUserData/tmp/d' . $surveyID . '/';
	createdir($_obf_DfXWN0u3Qlf5yOo_);
	$_obf_30V1vP8O9WzwAP0_ = $Config['absolutenessPath'] . '/PerUserData/logo/';

	if (copy($_obf_30V1vP8O9WzwAP0_ . $source, $_obf_DfXWN0u3Qlf5yOo_ . $source)) {
		return $source;
	}
	else {
		return '';
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Offline/SimpleHtmlDom.php';
_checkroletype('1|2|5');
$thisProg = 'SurveyDistribution.php';

if ($_POST['Action'] == 'importDataSubmit') {
	if ($License['isDistribution'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_distribution']);
	}

	if ($_POST['importData'] == '') {
		_showerror($lang['system_error'], $lang['no_sel_opt_survey']);
	}

	_checkpassport('1|2|5', $_POST['importData']);
	$theSID = $_POST['importData'];
	$DataFile_DIR_Name = '../PerUserData/tmp/';

	if (!file_exists($DataFile_DIR_Name . $_POST['importDataFile'])) {
		_showerror($lang['error_system'], $lang['no_distribution_file'] . $_POST['importDataFile']);
	}

	$theDataVersion = explode('_', $_POST['importDataFile']);

	if (str_replace('EnableQ V', '', $Config['version']) != str_replace('.zip', '', $theDataVersion[4])) {
		_showerror($lang['error_system'], $lang['error_distribution_version']);
	}

	$SQL = ' SELECT surveyID,status,surveyTitle,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $theSID . '\' ';
	$SRow = $DB->queryFirstRow($SQL);

	if (!$SRow) {
		_showerror($lang['error_system'], '调查问卷在系统中不存在，无法进行数据迁入操作，请联系系统管理员');
	}

	if ($SRow['status'] == 0) {
		_showerror($lang['error_system'], '调查问卷处在设计状态，无法进行数据迁入操作，请联系系统管理员');
	}

	$custDataPath = $SRow['custDataPath'];
	@set_time_limit(0);
	include_once ROOT_PATH . 'Includes/Unzip.class.php';
	$zip = new PHPUnzip();
	$open = $zip->Open($DataFile_DIR_Name . $_POST['importDataFile']);

	if (!$open) {
		_showerror($lang['error_system'], $lang['no_distribution_file'] . $_POST['importDataFile']);
	}

	$tmpDataFilePath = '../PerUserData/tmp/da' . time() . '/';
	createdir($tmpDataFilePath);
	$zip->SetOption(ZIPOPT_FILE_OUTPUT, false);
	$zip->SetOption(ZIPOPT_OUTPUT_PATH, $tmpDataFilePath);
	$zip->SetOption(ZIPOPT_OVERWRITE_EXISTING, true);
	$success = $zip->Read();

	if (!$success) {
		_showerror($lang['error_system'], 'Error ' . $zip->error . ' encountered: ' . $zip->error_str . '.');
	}

	if (0 < sizeof($zip->files)) {
		foreach ($zip->files as $file) {
			if ($file->error != E_NO_ERROR) {
				_showerror($lang['error_system'], 'Error ' . $file->error . ' while extracting "' . $file->name . '"');
			}
			else if (0 < $file->size) {
				$zip->WriteDataToFile($file->data, $file->name, $file->path);
			}
		}
	}

	require $tmpDataFilePath . 'surveydata.qdata';
	$all_survey_fields_this = 0;
	$theColSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $theSID . ' ';
	$theColResult = $DB->query($theColSQL);

	while ($theColRow = $DB->queryArray($theColResult)) {
		$all_survey_fields_this++;
	}

	if ($all_survey_fields_this != $all_survey_fields) {
		_showerror($lang['error_system'], $lang['error_db_no_match']);
	}

	include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
	$recNum = 0;
	require ROOT_PATH . 'System/Survey.data.php';
	@unlink($DataFile_DIR_Name . $_POST['importDataFile']);
	deletedir($tmpDataFilePath);
	writetolog($lang['import_survey_data_succ'] . ':' . $recNum . $lang['import_survey_data']);
	_showsucceed($lang['import_survey_data_succ'] . ':' . $recNum . $lang['import_survey_data'], '../Analytics/DataList.php?surveyID=' . $SRow['surveyID'] . '&surveyTitle=' . urlencode($SRow['surveyTitle']));
}

if ($_POST['Action'] == 'importSurveySubmit') {
	if ($License['isDistribution'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_distribution']);
	}

	if ($License['Limited'] == 1) {
		$SQL = ' SELECT COUNT(*) AS surveyNum FROM ' . SURVEY_TABLE . ' LIMIT 1 ';
		$Row = $DB->queryFirstRow($SQL);

		if ($License['LimitedNum'] <= $Row['surveyNum']) {
			_showerror($lang['limited_soft'], $lang['limited_soft']);
		}
	}

	@set_time_limit(0);
	$DataFile_DIR_Name = '../PerUserData/tmp/';

	if (!file_exists($DataFile_DIR_Name . $_POST['importSurveyFile'])) {
		_showerror($lang['error_system'], $lang['no_distribution_file'] . $_POST['importSurveyFile']);
	}

	$theDataVersion = explode('_', $_POST['importSurveyFile']);

	if (str_replace('EnableQ V', '', $Config['version']) != str_replace('.zip', '', $theDataVersion[3])) {
		_showerror($lang['error_system'], $lang['error_distribution_version']);
	}

	include_once ROOT_PATH . 'Includes/Unzip.class.php';
	$zip = new PHPUnzip();
	$open = $zip->Open($DataFile_DIR_Name . $_POST['importSurveyFile']);

	if (!$open) {
		_showerror($lang['error_system'], $lang['no_distribution_file'] . $_POST['importSurveyFile']);
	}

	$tmpDataFilePath = '../PerUserData/tmp/un' . time() . '/';
	createdir($tmpDataFilePath);
	$zip->SetOption(ZIPOPT_FILE_OUTPUT, false);
	$zip->SetOption(ZIPOPT_OUTPUT_PATH, $tmpDataFilePath);
	$zip->SetOption(ZIPOPT_OVERWRITE_EXISTING, true);
	$success = $zip->Read();

	if (!$success) {
		_showerror($lang['error_system'], 'Error ' . $zip->error . ' encountered: ' . $zip->error_str . '.');
	}

	if (0 < sizeof($zip->files)) {
		foreach ($zip->files as $file) {
			if ($file->error != E_NO_ERROR) {
				_showerror($lang['error_system'], 'Error ' . $file->error . ' while extracting "' . $file->name . '"');
			}
			else if (0 < $file->size) {
				$zip->WriteDataToFile($file->data, $file->name, $file->path);
			}
		}
	}

	require $tmpDataFilePath . 'surveydata.qdata';
	$FullPath = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -29);
	require ROOT_PATH . 'System/Survey.distibution.php';
	$destination = $Config['absolutenessPath'] . '/PerUserData/p/d' . $newSurveyID . '/';
	createdir($destination);

	if ($tmpFilePath = opendir($tmpDataFilePath)) {
		while (($tmpFile = readdir($tmpFilePath)) !== false) {
			if (($tmpFile == '.') || ($tmpFile == '..') || ($tmpFile == 'surveydata.qdata')) {
				continue;
			}

			@copy($tmpDataFilePath . $tmpFile, $destination . $tmpFile);
			@unlink($tmpDataFilePath . $tmpFile);
		}

		closedir($tmpFilePath);
	}

	@unlink($DataFile_DIR_Name . $_POST['importSurveyFile']);
	deletedir($tmpDataFilePath);
	writetolog($lang['import_survey_succ']);
	_showsucceed($lang['import_survey_succ'], 'ShowMain.php');
}

if ($_POST['Action'] == 'exportDataSubmit') {
	if ($License['isDistribution'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_distribution']);
	}

	if ($_POST['exportData'] == '') {
		_showerror($lang['system_error'], $lang['no_sel_opt_survey']);
	}

	_checkpassport('1|2|5', $_POST['exportData']);
	$theSID = $_POST['exportData'];
	$SQL = 'SELECT status,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $theSID . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['status'] == 0) {
		_showerror($lang['error_status'], $lang['design_survey_now']);
	}

	@set_time_limit(0);
	$custDataPath = $Row['custDataPath'];
	require ROOT_PATH . 'Includes/DistributionData.php';
	include_once ROOT_PATH . 'Includes/Zip.class.php';
	$zipFileName = 'SurveyData_' . $theSID . '_' . _getip() . '_' . date('YmdHis', time()) . '_' . str_replace('EnableQ V', '', $Config['version']) . '.zip';
	$Ziper = new zip_file('../PerUserData/tmp/' . $zipFileName);
	$Ziper->set_options(array('inmemory' => 0, 'recurse' => 1, 'storepaths' => 0, 'overwrite' => 1, 'type' => 'zip'));
	$Ziper->add_files('../PerUserData/tmp/f' . $theSID . '/');
	$ZipSucc = $Ziper->create_archive();

	if ($ZipSucc) {
		deletedir('../PerUserData/tmp/f' . $theSID . '/');
		_downloadfile('../PerUserData/tmp/', $zipFileName);
	}
	else {
		_showerror($lang['system_error'], $lang['error_create_zip_file']);
	}
}

if ($_POST['Action'] == 'exportSurveySubmit') {
	if ($License['isDistribution'] != 1) {
		_showerror($lang['license_error'], $lang['license_no_distribution']);
	}

	if ($_POST['exportSurvey'] == '') {
		_showerror($lang['system_error'], $lang['no_sel_opt_survey']);
	}

	_checkpassport('1|2|5', $_POST['exportSurvey']);
	$theSID = $_POST['exportSurvey'];
	@set_time_limit(0);
	require ROOT_PATH . 'Includes/DistributionCache.php';
	include_once ROOT_PATH . 'Includes/Zip.class.php';
	$zipFileName = 'Distribution_' . $theSID . '_' . date('YmdHis', time()) . '_' . str_replace('EnableQ V', '', $Config['version']) . '.zip';
	$Ziper = new zip_file('../PerUserData/tmp/' . $zipFileName);
	$Ziper->set_options(array('inmemory' => 0, 'recurse' => 1, 'storepaths' => 0, 'overwrite' => 1, 'type' => 'zip'));
	$Ziper->add_files('../PerUserData/tmp/d' . $theSID . '/');
	$ZipSucc = $Ziper->create_archive();

	if ($ZipSucc) {
		deletedir('../PerUserData/tmp/d' . $theSID . '/');
		_downloadfile('../PerUserData/tmp/', $zipFileName);
	}
	else {
		_showerror($lang['system_error'], $lang['error_create_zip_file']);
	}
}

$EnableQCoreClass->setTemplateFile('SurveyDistributionFile', 'SurveyDistribution.html');
$EnableQCoreClass->replace('session_id', session_id());
$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

if ($POST_MAX_SIZE) {
	$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
	$EnableQCoreClass->replace('maxSize', $thePostMaxSize);
}
else {
	$EnableQCoreClass->replace('maxSize', 2);
}

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT surveyTitle,surveyName,surveyID,status FROM ' . SURVEY_TABLE . ' WHERE 1=1 ';
	break;

case '2':
	$SQL = ' SELECT surveyTitle,surveyName,surveyID,status FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT surveyTitle,surveyName,surveyID,status FROM ' . SURVEY_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')';
	break;
}

$SQL .= ' ORDER BY surveyID DESC ';
$Result = $DB->query($SQL);
$exportSurveyList = $exportDataList = $importDataList = '';

while ($Row = $DB->queryArray($Result)) {
	switch ($Row['status']) {
	case '0':
		break;

	case '1':
		$exportSurveyList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		$exportDataList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		$importDataList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		break;

	case '2':
		$exportDataList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		$importDataList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
		break;
	}
}

$EnableQCoreClass->replace('exportSurveyList', $exportSurveyList);
$EnableQCoreClass->replace('exportDataList', $exportDataList);
$EnableQCoreClass->replace('importDataList', $importDataList);
$EnableQCoreClass->replace('allowType', '*.zip');
$SurveyDistributionPage = $EnableQCoreClass->parse('SurveyDistribution', 'SurveyDistributionFile');
echo $SurveyDistributionPage;
exit();

?>
