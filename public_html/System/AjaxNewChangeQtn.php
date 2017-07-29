<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$_GET['selectedID'] = (int) $_GET['selectedID'];
$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND ( questionType IN (\'1\',\'2\',\'3\',\'4\',\'5\',\'6\',\'7\',\'9\',\'10\',\'11\',\'12\',\'13\',\'14\',\'15\',\'23\',\'24\',\'25\',\'26\',\'27\',\'30\',\'31\') OR ( questionType= \'16\' AND baseID=0 ) ) ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$questionIDList = '<select name="questionID[]" id="questionID" multiple size=12 style="width:580px;"><option value="">' . $lang['pls_select'] . '</option>';

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);
	$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ') </option>' . "\n" . '';
}

$questionIDList .= '</select>';
echo $questionIDList;

?>
