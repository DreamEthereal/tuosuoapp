<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
header('Content-Type:text/html; charset=gbk');
$theQuestionArray = explode('*', $_GET['thisId']);
$theQuestionID = (int) $theQuestionArray[0];
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
$theQtnArray = $QtnListArray[$theQuestionID];

if (in_array($theQtnArray['questionType'], array(1, 2, 6, 24))) {
	$surveyID = $_GET['surveyID'];
	$questionID = $theQuestionID;
	$thisNo = $_GET['thisNo'];
	$ModuleName = $Module[$theQtnArray['questionType']];
	require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.sgg.inc.php';
}

exit();

?>
