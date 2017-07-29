<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|3|5|7');
$theQuestionIDArray = explode('*', $_GET['questionID']);
$theQuestionID = (int) $theQuestionIDArray[0];
$theOptionID = (int) $theQuestionIDArray[1];
$rtnStr = '';
$SQL = ' SELECT questionType,isHaveOther,isSelect,baseID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $theQuestionID . '\' ';
$Row = $DB->queryFirstRow($SQL);

switch ($Row['questionType']) {
case '3':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];

	if ($Row['isHaveOther'] == '1') {
		$maxMaxLevel++;
	}

	$rtnStr .= '0###' . $maxMaxLevel;
	break;

case '6':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];
	$trueOptionsStr = '<select name=\'trueOptions[]\' id=\'trueOptions\' multiple size=6 align=absmiddle style=\'width:800px;\'><option value=\'\'>' . $lang['pls_select'] . '</option>' . "\n" . '';
	$ASQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $theQuestionID . '\'  ORDER BY question_range_answerID ASC ';
	$AResult = $DB->query($ASQL);

	while ($ARow = $DB->queryArray($AResult)) {
		$trueOptionsStr .= '<option value=\'' . $ARow['question_range_answerID'] . '\'>' . qnohtmltag($ARow['optionAnswer'], 1) . '</option>' . "\n" . '';
	}

	$trueOptionsStr .= '</select>';
	$rtnStr .= '1###' . $maxMaxLevel . '###' . $trueOptionsStr;
	break;

case '10':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_RANK_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];

	if ($Row['isHaveOther'] == '1') {
		$maxMaxLevel++;
	}

	$trueOptionsStr = '<select name=\'trueOptions[]\' id=\'trueOptions\' multiple size=6 align=absmiddle style=\'width:800px;\'><option value=\'\'>' . $lang['pls_select'] . '</option>' . "\n" . '';
	$tmp = 1;

	for (; $tmp <= $maxMaxLevel; $tmp++) {
		$trueOptionsStr .= '<option value=\'' . $tmp . '\'>' . $tmp . '</option>' . "\n" . '';
	}

	$trueOptionsStr .= '</select>';
	$rtnStr .= '1###' . $maxMaxLevel . '###' . $trueOptionsStr;
	break;

case '25':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];
	$rtnStr .= '0###' . $maxMaxLevel;
	break;

case '7':
case '28':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];
	$rtnStr .= '0###' . $maxMaxLevel;
	break;

case '17':
	if ($Row['isSelect'] == 1) {
	}
	else {
		$bSQL = ' SELECT isHaveOther,questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
		$bRow = $DB->queryFirstRow($bSQL);

		if ($bRow['questionType'] == 3) {
			$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE  questionID = \'' . $Row['baseID'] . '\' ';
			$ORow = $DB->queryFirstRow($OSQL);
			$maxMaxLevel = $ORow['optionNum'];

			if ($bRow['isHaveOther'] == '1') {
				$maxMaxLevel++;
			}
		}
		else {
			$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE  questionID = \'' . $Row['baseID'] . '\' ';
			$ORow = $DB->queryFirstRow($OSQL);
			$maxMaxLevel = $ORow['optionNum'];
		}

		$rtnStr .= '0###' . $maxMaxLevel;
	}

	break;

case '18':
	if ($Row['isSelect'] == 1) {
		$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_YESNO_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
		$ORow = $DB->queryFirstRow($OSQL);
		$maxMaxLevel = $ORow['optionNum'];
		$rtnStr .= '0###' . $maxMaxLevel;
	}

	break;

case '26':
	$OSQL = ' SELECT COUNT(*) as optionNum FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE  questionID = \'' . $theQuestionID . '\' ';
	$ORow = $DB->queryFirstRow($OSQL);
	$maxMaxLevel = $ORow['optionNum'];
	$trueOptionsStr = '<select name=\'trueOptions[]\' id=\'trueOptions\' multiple size=6 align=absmiddle style=\'width:800px;\'><option value=\'\'>' . $lang['pls_select'] . '</option>' . "\n" . '';
	$ASQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $theQuestionID . '\'  ORDER BY question_range_answerID ASC ';
	$AResult = $DB->query($ASQL);

	while ($ARow = $DB->queryArray($AResult)) {
		$trueOptionsStr .= '<option value=\'' . $ARow['question_range_answerID'] . '\'>' . qnohtmltag($ARow['optionAnswer'], 1) . '</option>' . "\n" . '';
	}

	$trueOptionsStr .= '</select>';
	$rtnStr .= '1###' . $maxMaxLevel . '###' . $trueOptionsStr;
	break;
}

header('Content-Type:text/html; charset=gbk');
echo $rtnStr;

?>
