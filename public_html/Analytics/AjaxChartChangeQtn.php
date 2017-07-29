<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['selectedID'] = (int) $_GET['selectedID'];
_checkpassport('1|2|3|5|7', $_GET['selectedID']);

switch ($_GET['chartType']) {
case 1:
case 3:
default:
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND questionType IN (\'1\',\'2\',\'3\',\'13\',\'17\',\'18\',\'24\',\'25\',\'31\') ORDER BY orderByID ASC ';
	break;

case 2:
case 4:
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND (questionType IN (\'1\',\'2\',\'6\',\'10\',\'13\',\'19\',\'20\',\'24\',\'26\',\'31\') OR (questionType IN (\'15\',\'21\',\'18\') AND isSelect=0) OR (questionType=\'17\' AND isSelect=1) ) ORDER BY orderByID ASC ';
	break;

case 5:
case 6:
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND (questionType IN (\'6\',\'10\',\'19\',\'20\',\'26\') OR (questionType IN (\'15\',\'21\') AND isSelect=0)) ORDER BY orderByID ASC ';
	break;

case 7:
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND questionType IN (\'15\',\'21\',\'10\',\'20\',\'16\',\'22\') ORDER BY orderByID ASC ';
	break;

case 8:
case 9:
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['selectedID'] . '\' AND isPublic=\'1\' AND (questionType IN (\'6\',\'7\',\'10\',\'19\',\'20\',\'26\',\'28\') OR (questionType IN (\'15\',\'21\') AND isSelect=0)) ORDER BY orderByID ASC ';
	break;
}

$Result = $DB->query($SQL);
$questionIDList = '<select name="questionID" id="questionID" style="width:810px"><option value="">' . $lang['pls_select'] . '</option>';

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);
	$questionIDList .= '<option value=\'' . $Row['questionID'] . '###' . $Row['questionType'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ') </option>' . "\n" . '';
}

$questionIDList .= '</select>';
header('Content-Type:text/html; charset=gbk');
echo $questionIDList;

?>
