<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['selectedID'] = (int) $_GET['selectedID'];
_checkpassport('1|2|3|5|7', $_GET['selectedID']);
$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND (questionType IN (\'1\',\'2\',\'3\',\'6\',\'7\',\'10\',\'13\',\'17\',\'18\',\'19\',\'20\',\'24\',\'25\',\'26\',\'28\',\'31\') OR (questionType IN (\'15\',\'21\') AND isSelect=0)) ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$questionIDList = '<select name="questionID" id="questionID" style="width:810px"><option value="">' . $lang['pls_select'] . '</option>';

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);
	$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ') </option>' . "\n" . '';
}

$questionIDList .= '</select>';
header('Content-Type:text/html; charset=gbk');
echo $questionIDList;

?>
