<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|3|5|7');
$SQL = ' SELECT surveyID,status,surveyName,isPublic,ajaxRtnValue,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	echo $lang['design_survey_now'];
}

if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
$theQtnArray = $QtnListArray[$_GET['thisId']];

if (in_array($theQtnArray['questionType'], array(1, 2, 3, 4, 6, 7, 10, 12, 13, 15, 17, 18, 19, 20, 21, 23, 24, 25, 26, 28, 30, 31))) {
	$surveyID = (int) $_GET['surveyID'];
	$questionID = (int) $_GET['thisId'];
	$thisNo = $_GET['thisNo'];
	$ModuleName = $Module[$theQtnArray['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.search.inc.php';
}

exit();

?>
