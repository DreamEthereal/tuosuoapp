<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|3|5|7');
$theQuestionID = (int) $_GET['questionID'];
$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $theQuestionID . '\' ';
$Row = $DB->queryFirstRow($SQL);

switch ($Row['questionType']) {
case '6':
	$trueBrandStr = '<select name=\'trueBrand\' id=\'trueBrand\' align=absmiddle style=\'width:790px\'><option value=\'\'>请指定哪个是测试品牌...</option>' . "\n" . '';
	$OptionSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $theQuestionID . '\'  ORDER BY question_range_answerID ASC ';
	$AResult = $DB->query($OptionSQL);

	while ($ARow = $DB->queryArray($AResult)) {
		$trueBrandStr .= '<option value=\'' . $ARow['question_range_answerID'] . '\'>' . qnohtmltag($ARow['optionAnswer'], 1) . '</option>' . "\n" . '';
	}

	$trueBrandStr .= '</select>';
	$rtnStr = $trueBrandStr;
	break;
}

header('Content-Type:text/html; charset=gbk');
echo $rtnStr;

?>
