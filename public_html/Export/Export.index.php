<?php
session_start();
//dezend by http://www.yunlu99.com/
function qconversionstring($string)
{
	$string = strip_tags($string);
	$string = str_replace('"', '""', $string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

$_SESSION['administratorsID']=1;
// echo $_SESSION['administratorsID'];
// exit();

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
require_once ROOT_PATH . 'License/License.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID']; 
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID,surveyID,isRateIndex FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';

$SRow = $DB->queryFirstRow($SQL);

if ($SRow['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

$ResultContent = '';
$header = '"ÐòºÅ"';
$header .= ',"Ñù±¾±êÊ¶"';
$header .= ',"À´Ô´"';
$header .= ',"×Ü·Ö"';
$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';

$iResult = $DB->query($SQL);
$surveyIndexName = array();
$tmp = 0;

while ($iRow = $DB->queryArray($iResult)) {
	$surveyIndexName[$tmp] = $iRow['indexID'];
	$tmp++;
	$header .= ',"' . qconversionstring($iRow['indexName']) . '"';
	$sSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
	$sResult = $DB->query($sSQL);

	while ($sRow = $DB->queryArray($sResult)) {
		$surveyIndexName[$tmp] = $sRow['indexID'];
		$tmp++;
		$header .= ',"' . qconversionstring($sRow['indexName']) . '"';
	}
}
if($_GET['wenjunID'])
{
echo $_SESSION['exportDataSQL']='overFlag%21%3D2+AND++%28b.overFlag+%21%3D+2+and+b.authStat+%21%3D+2%29++AND+b.responseID+%3D+%27'.$_GET['wenjunID'].'%27';
}
$header .= "\r\n";
$ResultContent .= $header;
$SQL = ' SELECT responseID,administratorsName,ipAddress,area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . str_replace('\\\'', '\'', stripslashes(urldecode($_SESSION['exportDataSQL']))) . ' ORDER BY responseID DESC ';
// echo $SQL;  exit();
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$ResultContent .= '"' . $Row['responseID'] . '"';
	$panelName = ($Row['administratorsName'] != '' ? $Row['administratorsName'] : $Row['ipAddress']);
	$ResultContent .= ',"' . qconversionstring($panelName) . '"';
	$ResultContent .= ',"' . qconversionstring($Row['area']) . '"';
	$SQL = ' SELECT indexID,indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $Row['responseID'] . '\' ORDER BY indexID ASC ';
	$rResult = $DB->query($SQL);
	$theIndexValue = array();

	while ($rRow = $DB->queryArray($rResult)) {
		$theIndexValue[$rRow['indexID']] = $rRow['indexValue'];
	}

	if (($SRow['isRateIndex'] == 1) && ($theIndexValue[0] != '')) {
		$ResultContent .= ',"' . $theIndexValue[0] . '%"';
	}
	else {
		$ResultContent .= ',"' . $theIndexValue[0] . '"';
	}

	foreach ($surveyIndexName as $indexID) {
		if ($theIndexValue[$indexID] != '-999') {
			$theValue = $theIndexValue[$indexID];
			if (($SRow['isRateIndex'] == 1) && ($theIndexValue[$indexID] != '')) {
				$theValue .= '%';
			}
		}
		else {
			$theValue = 'NA';
		}

		$ResultContent .= ',"' . $theValue . '"';
	}

	$ResultContent .= "\r\n";
}

ob_start();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=Index_Result_Data_' . $_GET['surveyID'] . '_List_' . date('Y-m-d') . '.csv');
echo $ResultContent;
exit();

?>
