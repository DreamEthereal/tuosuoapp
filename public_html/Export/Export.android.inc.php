<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
@set_time_limit(0);
_checkroletype('1|6');

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

ob_start();
$content = '';
$header = '"' . str_replace('£º', '', $lang['info_line1Number']) . '"';
$header .= ',"' . str_replace('£º', '', $lang['info_simOperatorName']) . '"';
$header .= ',"' . 'ICCID' . '"';
$header .= ',"' . str_replace('£º', '', $lang['info_brand']) . '"';
$header .= ',"' . str_replace('£º', '', $lang['info_model']) . '"';
$header .= ',"' . 'IMEI' . '"';
$header .= ',"' . $lang['info_survey'] . '"';
$header .= ',"' . 'Êý¾ÝÐòºÅ"';
$header .= "\r\n";
$content .= $header;
$SQL = ' SELECT line1Number,simOperatorName,simSerialNumber,brand,model,deviceId,surveyID,responseID FROM ' . ANDROID_INFO_TABLE . ' ORDER BY line1Number ASC,surveyID DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$content .= '"' . $Row['line1Number'] . '"';
	$content .= ',"' . $Row['simOperatorName'] . '"';
	$content .= ',"' . $Row['simSerialNumber'] . '"';
	$content .= ',"' . $Row['brand'] . '"';
	$content .= ',"' . $Row['model'] . '"';
	$content .= ',"' . $Row['deviceId'] . '"';
	$HSQL = ' SELECT surveyTitle,surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $Row['surveyID'] . '\' ';
	$HRow = $DB->queryFirstRow($HSQL);
	$content .= ',"' . $HRow['surveyTitle'] . '(' . $HRow['surveyName'] . ')' . '"';
	$content .= ',"' . $Row['responseID'] . '"';
	$content .= "\r\n";
}

header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=Android_Info_List_' . date('Y-m-d') . '.csv');
echo $content;
exit();

?>
