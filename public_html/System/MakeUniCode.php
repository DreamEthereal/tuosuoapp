<?php
//dezend by http://www.yunlu99.com/
function unique_rand($min, $max, $num)
{
	$_obf_gftfagw_ = 0;
	$_obf_lWk5hHye = array();

	while ($_obf_gftfagw_ < $num) {
		$_obf_lWk5hHye[] = mt_rand($min, $max);
		$_obf_lWk5hHye = array_flip(array_flip($_obf_lWk5hHye));
		$_obf_gftfagw_ = count($_obf_lWk5hHye);
	}

	return $_obf_lWk5hHye;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['qid'] = (int) $_GET['qid'];
$_GET['uniCodeNum'] = (int) $_GET['uniCodeNum'];
$thisProg = 'MakeUniCode.php?qid=' . $_GET['qid'] . '&uniCodeNum=' . $_GET['uniCodeNum'];
_checkpassport('1|2|5', $_GET['qid']);
@set_time_limit(0);
$SQL = ' SELECT status,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '2') {
	_showerror($lang['system_error'], $lang['close_survey_now']);
}

$theUniCodePath = ROOT_PATH . 'PerUserData/unicode/';
createdir($theUniCodePath);
$theCacheFile = $theUniCodePath . md5('uniCode' . $_GET['qid']) . '.php';
$uniCodeQKeyArray = array();

if (file_exists($theCacheFile)) {
	require_once $theCacheFile;
}

$theExistKeyArray = array();
$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$cacheContent .= '$uniCodeQKeyArray = array( ';

if ($Config['dataDomainName'] != '') {
	$All_Path = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
}

$csvFileContent = '';

foreach ($uniCodeQKeyArray as $thisRandNumber) {
	if (!in_array($thisRandNumber, $theExistKeyArray)) {
		$theRandStr = $thisRandNumber;
		$csvFileContent .= '"' . $All_Path . 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '&qkey=' . $theRandStr;
		$csvFileContent .= '"' . "\r\n" . '';
		$cacheContent .= '\'' . $theRandStr . '\',';
		$theExistKeyArray[] = $theRandStr;
	}
}

if (!isset($_GET['uniCodeNum'])) {
	$_GET['uniCodeNum'] = 0;
}

$theRandNumber = unique_rand(1000000, 30000000, $_GET['uniCodeNum'] * 5);
$theNum = 0;

foreach ($theRandNumber as $thisRandNumber) {
	if ($theNum == $_GET['uniCodeNum']) {
		break;
	}

	if (!in_array($thisRandNumber, $theExistKeyArray)) {
		$theRandStr = 'k' . $_GET['qid'] . sprintf('%09s', $thisRandNumber);
		$csvFileContent .= '"' . $All_Path . 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'] . '&qkey=' . $theRandStr;
		$csvFileContent .= '"' . "\r\n" . '';
		$cacheContent .= '\'' . $theRandStr . '\',';
		$theExistKeyArray[] = $theRandStr;
		$theNum++;
	}
}

$cacheContent = substr($cacheContent, 0, -1) . '' . "\r\n" . ');' . "\r\n" . '';
$cacheContent .= chr(63) . chr(62);

if (file_exists($theCacheFile)) {
	@unlink($theCacheFile);
}

write_to_file($theCacheFile, $cacheContent);
ob_start();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=UniURL_' . $Row['surveyName'] . '_List_' . date('Y-m-d') . '.csv');
echo $csvFileContent;
exit();

?>
