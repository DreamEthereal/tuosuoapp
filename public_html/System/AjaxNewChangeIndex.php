<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$_GET['selectedID'] = (int) $_GET['selectedID'];
$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID =\'' . $_GET['selectedID'] . '\' AND fatherId != 0 ORDER BY indexID ASC ';
$Result = $DB->query($SQL);
$indexIDList = '<select name="indexID" id="indexID" size=6 onChange="javascript:ChangeQtn();" style="width:580px;"><option value="">' . $lang['pls_select'] . '</option>';

while ($Row = $DB->queryArray($Result)) {
	$indexName = qnohtmltag($Row['indexName'], 1);
	$SQL = ' SELECT count(*) as qtnNum FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE indexID=\'' . $Row['indexID'] . '\' LIMIT 0,1 ';
	$QRow = $DB->queryFirstRow($SQL);
	$indexIDList .= '<option value=\'' . $Row['indexID'] . '\'>' . $indexName . ' (' . $QRow['qtnNum'] . ') </option>' . "\n" . '';
}

$indexIDList .= '</select>';
echo $indexIDList;

?>
