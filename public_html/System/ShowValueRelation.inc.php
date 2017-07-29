<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$RSQL = ' SELECT * FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $theRelID . '\' ORDER BY optionOptionID ASC ';
$RResult = $DB->query($RSQL);
$j = 0;
$conList = '';
$RTotal = $DB->_getNumRows($RResult);

if (2 <= $RTotal) {
	$conList .= '<font color=red><b>(</b></font><br/>';
}

while ($RRow = $DB->queryArray($RResult)) {
	if ($j != 0) {
		switch ($RRow['opertion']) {
		case 1:
			$conList .= '<font color=brown style="font-size:14px"><b>+</b></font><br/>';
			break;

		case 2:
			$conList .= '<font color=brown style="font-size:18px"><b>-</b></font><br/>';
			break;

		case 3:
			$conList .= '<font color=brown style="font-size:14px"><b>*</b></font><br/>';
			break;

		case 4:
			$conList .= '<font color=brown style="font-size:14px"><b>/</b></font><br/>';
			break;
		}
	}

	$QSQL = ' SELECT questionName,questionType,allowType,otherText,baseID,isSelect FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $RRow['questionID'] . '\' ';
	$QRow = $DB->queryFirstRow($QSQL);

	switch ($QRow['questionType']) {
	case 1:
	case 2:
	case 24:
	case 4:
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 3:
		switch ($RRow['optionID']) {
		case '0':
			$optionName = qnohtmltag($QRow['otherText'], 1);
			break;

		case '99999':
			$optionName = ($QRow['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($QRow['allowType'], 1));
			break;

		default:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $RRow['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$optionName = qnohtmltag($ZRow['optionName'], 1);
			break;
		}

		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
		break;

	case 6:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $RRow['qtnID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 7:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $RRow['qtnID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$ASQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID = \'' . $RRow['optionID'] . '\' ';
		$ARow = $DB->queryFirstRow($ASQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($ARow['optionAnswer'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 15:
	case 16:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID = \'' . $RRow['optionID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 17:
		if ($QRow['isSelect'] == 1) {
			$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
		}
		else {
			switch ($RRow['optionID']) {
			case '0':
				$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$optionName = qnohtmltag($bRow['otherText'], 1);
				break;

			case '99999':
				$optionName = ($QRow['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($QRow['allowType'], 1));
				break;

			default:
				$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $RRow['optionID'] . '\' ';
				$ZRow = $DB->queryFirstRow($ZSQL);
				$optionName = qnohtmltag($ZRow['optionName'], 1);
				break;
			}

			$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
		}

		break;

	case 18:
		if ($QRow['isSelect'] == 1) {
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID =\'' . $RRow['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
		}
		else {
			$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
		}

		break;

	case 19:
	case 21:
	case 22:
		switch ($RRow['qtnID']) {
		case '0':
			$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
			$bRow = $DB->queryFirstRow($bSQL);
			$optionName = qnohtmltag($bRow['otherText'], 1);
			break;

		default:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $RRow['qtnID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$optionName = qnohtmltag($ZRow['optionName'], 1);
			break;
		}

		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
		break;

	case 23:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID = \'' . $RRow['optionID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 25:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $RRow['optionID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$optionName = qnohtmltag($ZRow['optionName'], 1);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($optionName, 1) . '<font color=blue> )</font><br/>';
		break;

	case 26:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $RRow['qtnID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$ASQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID = \'' . $RRow['optionID'] . '\' ';
		$ARow = $DB->queryFirstRow($ASQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($ARow['optionLabel'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 27:
		$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $RRow['optionID'] . '\' ';
		$ZRow = $DB->queryFirstRow($ZSQL);
		$LSQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID = \'' . $RRow['labelID'] . '\' ';
		$LRow = $DB->queryFirstRow($LSQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($LRow['optionLabel'], 1) . '<font color=blue> )</font><br/>';
		break;

	case 28:
		switch ($RRow['qtnID']) {
		case '0':
			$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
			$bRow = $DB->queryFirstRow($bSQL);
			$optionName = qnohtmltag($bRow['otherText'], 1);
			break;

		default:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $RRow['qtnID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$optionName = qnohtmltag($ZRow['optionName'], 1);
			break;
		}

		$ASQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID = \'' . $RRow['optionID'] . '\' ';
		$ARow = $DB->queryFirstRow($ASQL);
		$conList .= '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . ' - ' . qnohtmltag($ARow['optionAnswer'], 1) . '<font color=blue> )</font><br/>';
		break;
	}

	$j++;
}

if (2 <= $RTotal) {
	$conList .= ' <font color=red><b>)</b></font><br/>';
}

$EnableQCoreClass->replace('conList', $conList);

?>
