<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$_GET['selectedID'] = (int) $_GET['selectedID'];
$_GET['surveyID'] = (int) $_GET['surveyID'];
$SQL = ' SELECT questionID,questionType,orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$rangeList = '<select name="base_optionID[]" id="base_optionID" size=6 multiple style="width:760px;*width:780px"><option value="">' . $lang['pls_select'] . '</option>';

switch ($Row['questionType']) {
case '2':
case '24':
	$OptionSQL = ' SELECT question_radioID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$optionName = qnohtmltag($OptionRow['optionName'], 1);
		$rangeList .= '<option value=' . $OptionRow['question_radioID'] . '>' . $optionName . '</option>';
	}

	break;

case '3':
case '25':
	$OptionSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$optionName = qnohtmltag($OptionRow['optionName'], 1);
		$rangeList .= '<option value=' . $OptionRow['question_checkboxID'] . '>' . $optionName . '</option>';
	}

	break;

case '6':
case '7':
case '19':
case '28':
	$OptionSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
	$OptionResult = $DB->query($OptionSQL);

	while ($OptionRow = $DB->queryArray($OptionResult)) {
		$optionName = qnohtmltag($OptionRow['optionAnswer'], 1);
		$rangeList .= '<option value=' . $OptionRow['question_range_answerID'] . '>' . $optionName . '</option>';
	}

	break;
}

$rangeList .= '</select>';
$BaseSQL = ' SELECT questionID,questionName,questionType,isCheckType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND orderByID < \'' . $Row['orderByID'] . '\' AND questionType IN (1,2,3,6,7,24,25,30,19,28,17,4,23,10,15,16,20,21,22,31) ORDER BY orderByID ASC  ';
$BaseResult = $DB->query($BaseSQL);
$BaseList = '<select name=condOnID id=condOnID onChange="javascript:ChangeQtn();" size=6 style="width:760px;*width:780px"><option value="">' . $lang['pls_select'] . '</option>';

while ($BaseRow = $DB->queryArray($BaseResult)) {
	$questionName = qnohtmltag($BaseRow['questionName'], 1);

	switch ($BaseRow['questionType']) {
	case '4':
		if ($BaseRow['isCheckType'] == '4') {
			$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
		}

		break;

	case '23':
		$hSQL = ' SELECT isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $BaseRow['questionID'] . '\' AND isCheckType =\'4\' LIMIT 1 ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow) {
			$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
		}

		break;

	default:
		$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
		break;
	}
}

$BaseList .= '</select>';
echo $rangeList . '########' . $BaseList;

?>
