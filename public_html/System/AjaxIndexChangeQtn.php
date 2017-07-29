<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$_GET['selectedID'] = (int) $_GET['selectedID'];
$SQL = ' SELECT a.questionID,a.questionName,a.questionType FROM ' . QUESTION_TABLE . ' a,' . SURVEYINDEXLIST_TABLE . ' b WHERE a.surveyID=\'' . $_GET['surveyID'] . '\' AND a.isPublic=\'1\' AND a.questionType NOT IN (\'8\',\'17\',\'18\',\'19\',\'20\',\'21\',\'22\',\'28\',\'29\',\'30\',\'31\') AND a.questionID=b.questionID AND b.indexID=\'' . $_GET['selectedID'] . '\' ORDER BY a.orderByID ASC ';
$Result = $DB->query($SQL);
$questionIDList = '<select name="questionID[]" id="questionID" multiple size=8 style="width:580px;"><option value="">' . $lang['pls_select'] . '</option>';

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);
	$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ') </option>' . "\n" . '';
}

$questionIDList .= '</select>';
echo $questionIDList;

?>
