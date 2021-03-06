<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$ConSQL = ' SELECT DISTINCT condOnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $theQtnID . '\' AND qtnID =\'' . $theQtnAssID . '\' AND assType=1 ORDER BY condOnID ASC ';
$ConResult = $DB->query($ConSQL);
$ConArray = array();

while ($ConRow = $DB->queryArray($ConResult)) {
	$ConArray[] = $ConRow['condOnID'];
}

$j = 0;

foreach ($ConArray as $condOnID) {
	$baseSQL = ' SELECT questionName,questionType,otherText,allowType,baseID,unitText FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $condOnID . '\' ';
	$baseQtnRow = $DB->queryFirstRow($baseSQL);
	$base_questionName = qnohtmltag($baseQtnRow['questionName'], 1);

	switch ($baseQtnRow['questionType']) {
	case '1':
	case '2':
	case '3':
	case '4':
	case '24':
	case '25':
	case '30':
	case '17':
		$ConListSQL = ' SELECT condOptionID,condQtnID,opertion,logicValueIsAnd,logicMode FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $condOnID . '\' AND questionID=\'' . $theQtnID . '\' AND qtnID =\'' . $theQtnAssID . '\' AND assType=1 ORDER BY condQtnID ASC, condOptionID ASC ';
		$ConListResult = $DB->query($ConListSQL);
		$ConListTotal = $DB->_getNumRows($ConListResult);
		$i = 0;

		if (2 <= $ConListTotal) {
			$conList .= '<font color=red><b>(</b></font><br/>';
		}

		while ($ConListRow = $DB->queryArray($ConListResult)) {
			switch ($baseQtnRow['questionType']) {
			case '1':
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConListRow['condOptionID'] . '\' ';
				break;

			case '2':
			case '24':
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID=\'' . $ConListRow['condOptionID'] . '\' ';
				break;

			case '3':
			case '25':
				if ($ConListRow['logicMode'] == 1) {
					$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConListRow['condOptionID'] . '\' ';
				}
				else {
					$OptionSQL = ' SELECT 1=1';
				}

				break;

			case '17':
				switch ($ConListRow['condOptionID']) {
				case '0':
					$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $baseQtnRow['baseID'] . '\' ';
					break;

				case '99999':
					$OptionSQL = ' SELECT allowType as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $condOnID . '\' ';
					break;

				default:
					$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConListRow['condOptionID'] . '\' AND questionID = \'' . $baseQtnRow['baseID'] . '\' ';
					break;
				}

				break;

			case '4':
			case '30':
				$OptionSQL = ' SELECT 1=1';
				break;
			}

			$OptionRow = $DB->queryFirstRow($OptionSQL);
			$i++;

			switch ($baseQtnRow['questionType']) {
			case '4':
				switch ($ConListRow['opertion']) {
				case 1:
					$opertion = '==';
					break;

				case 2:
					$opertion = '<';
					break;

				case 3:
					$opertion = '<=';
					break;

				case 4:
					$opertion = '>';
					break;

				case 5:
					$opertion = '>=';
					break;

				case 6:
					$opertion = '!=';
					break;
				}

				if ($i == $ConListTotal) {
					$opertionRelation = '';
				}
				else {
					$opertionRelation = $lang['and'];
				}

				$conList .= '<font color=blue>( </font>' . $base_questionName . '&nbsp;<font color=brown><b>' . $opertion . '</b></font>&nbsp;' . $ConListRow['condOptionID'] . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
				break;

			case '1':
			case '24':
			case '17':
				switch ($ConListRow['opertion']) {
				case 1:
					$opertion = $lang['selected'];
					break;

				case 2:
					$opertion = $lang['unselected'];
					break;
				}

				if ($i == $ConListTotal) {
					$opertionRelation = '';
				}
				else if ($ConListRow['logicValueIsAnd'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . qnohtmltag($OptionRow['optionName']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
				break;

			case '2':
				switch ($ConListRow['opertion']) {
				case 1:
					$opertion = $lang['selected'];
					break;

				case 2:
					$opertion = $lang['unselected'];
					break;
				}

				if ($i == $ConListTotal) {
					$opertionRelation = '';
				}
				else if ($ConListRow['logicValueIsAnd'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				switch ($ConListRow['condOptionID']) {
				case '0':
					$otherText = qnohtmltag($baseQtnRow['otherText']);
					$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . $otherText . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
					break;

				default:
					$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . qnohtmltag($OptionRow['optionName']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
					break;
				}

				break;

			case '3':
			case '25':
				if ($ConListRow['logicMode'] == 1) {
					switch ($ConListRow['opertion']) {
					case 1:
						$opertion = $lang['selected'];
						break;

					case 2:
						$opertion = $lang['unselected'];
						break;
					}

					if ($i == $ConListTotal) {
						$opertionRelation = '';
					}
					else if ($ConListRow['logicValueIsAnd'] == 1) {
						$opertionRelation = $lang['and'];
					}
					else {
						$opertionRelation = $lang['or'];
					}

					if ($baseQtnRow['questionType'] == '3') {
						switch ($ConListRow['condOptionID']) {
						case '0':
							$otherText = qnohtmltag($baseQtnRow['otherText']);
							$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . $otherText . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
							break;

						case '99999':
							$negText = ($baseQtnRow['allowType'] != '' ? $baseQtnRow['allowType'] : $lang['neg_text']);
							$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . qnohtmltag($negText) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
							break;

						default:
							$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . qnohtmltag($OptionRow['optionName']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
							break;
						}
					}
					else {
						$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . qnohtmltag($OptionRow['optionName']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
					}
				}
				else {
					switch ($ConListRow['opertion']) {
					case 1:
						$opertion = '==';
						break;

					case 2:
						$opertion = '<';
						break;

					case 3:
						$opertion = '<=';
						break;

					case 4:
						$opertion = '>';
						break;

					case 5:
						$opertion = '>=';
						break;

					case 6:
						$opertion = '!=';
						break;
					}

					if ($i == $ConListTotal) {
						$opertionRelation = '';
					}
					else {
						$opertionRelation = $lang['and'];
					}

					$conList .= '<font color=blue>( </font>' . $base_questionName . ' - 回复选项数量' . '&nbsp;<font color=brown><b>' . $opertion . '</b></font>&nbsp;' . $ConListRow['condOptionID'] . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
				}

				break;

			case '30':
				switch ($ConListRow['opertion']) {
				case 1:
					$opertion = $lang['logicEqual'];
					break;

				case 2:
					$opertion = $lang['logicUnEqual'];
					break;
				}

				switch ($ConListRow['condOptionID']) {
				case 1:
					$opertionResult = 'True';
					break;

				default:
					$opertionResult = 'False';
					break;
				}

				$conList .= '<font color=blue>( </font>' . $base_questionName . $opertion . $opertionResult . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
				break;
			}
		}

		if (2 <= $ConListTotal) {
			$conList .= ' <font color=red><b>)</b></font><br/>';
		}

		break;

	case '6':
	case '7':
	case '19':
	case '28':
	case '23':
	case '10':
	case '15':
	case '16':
	case '20':
	case '21':
	case '22':
	case '31':
		$RangeSQL = ' SELECT DISTINCT condQtnID FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $condOnID . '\' AND questionID=\'' . $theQtnID . '\' AND qtnID =\'' . $theQtnAssID . '\' AND assType=1 ORDER BY condQtnID ASC ';
		$RangeResult = $DB->query($RangeSQL);
		$RangeTotal = $DB->_getNumRows($RangeResult);

		if (2 <= $RangeTotal) {
			$conList .= '<font color=red><b>{</b></font><br/>';
		}

		$k = 0;

		while ($RangeRow = $DB->queryArray($RangeResult)) {
			$ConListSQL = ' SELECT condQtnID,condOptionID,opertion,logicValueIsAnd FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $condOnID . '\' AND condQtnID = \'' . $RangeRow['condQtnID'] . '\' AND questionID=\'' . $theQtnID . '\' AND qtnID =\'' . $theQtnAssID . '\' AND assType=1 ORDER BY condQtnID ASC, condOptionID ASC ';
			$ConListResult = $DB->query($ConListSQL);
			$ConListTotal = $DB->_getNumRows($ConListResult);
			$i = 0;

			if (2 <= $ConListTotal) {
				$conList .= '<font color=brown><b>(</b></font><br/>';
			}

			while ($ConListRow = $DB->queryArray($ConListResult)) {
				switch ($baseQtnRow['questionType']) {
				case '6':
				case '7':
					$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID=\'' . $ConListRow['condQtnID'] . '\' ';
					break;

				case '23':
					$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConListRow['condQtnID'] . '\' ';
					break;

				case '10':
					if ($ConListRow['condQtnID'] == 0) {
						$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $condOnID . '\' ';
					}
					else {
						$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConListRow['condQtnID'] . '\' ';
					}

					break;

				case '15':
				case '16':
					$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConListRow['condQtnID'] . '\' ';
					break;

				case '19':
				case '28':
				case '20':
				case '21':
				case '22':
					switch ($ConListRow['condQtnID']) {
					case '0':
						$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $baseQtnRow['baseID'] . '\' ';
						break;

					default:
						$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConListRow['condQtnID'] . '\' AND questionID = \'' . $baseQtnRow['baseID'] . '\' ';
						break;
					}

					break;

				case '31':
					$theUnitText = explode('#', $baseQtnRow['unitText']);
					$theOptionName = $theUnitText[$ConListRow['condQtnID'] - 1];
					$OptionSQL = ' SELECT 1=1';
					break;
				}

				$OptionRow = $DB->queryFirstRow($OptionSQL);
				$i++;

				switch ($baseQtnRow['questionType']) {
				case '23':
				case '10':
				case '15':
				case '16':
				case '20':
				case '21':
				case '22':
					if ($i == $ConListTotal) {
						$opertionRelation = '';
					}
					else {
						$opertionRelation = $lang['and'];
					}

					switch ($ConListRow['opertion']) {
					case 1:
						$opertion = '==';
						break;

					case 2:
						$opertion = '<';
						break;

					case 3:
						$opertion = '<=';
						break;

					case 4:
						$opertion = '>';
						break;

					case 5:
						$opertion = '>=';
						break;

					case 6:
						$opertion = '!=';
						break;
					}

					$conList .= '<font color=blue>( </font>' . $base_questionName . ' - ' . qnohtmltag($OptionRow['optionName']) . '&nbsp;<font color=brown><b>' . $opertion . '</b></font>&nbsp;' . $ConListRow['condOptionID'] . '<font color=blue> )</font>&nbsp;' . $opertionRelation . '<br/>';
					break;

				case '6':
				case '7':
				case '19':
				case '28':
					if ($i == $ConListTotal) {
						$opertionRelation = '';
					}
					else if ($ConListRow['logicValueIsAnd'] == 1) {
						$opertionRelation = $lang['and'];
					}
					else {
						$opertionRelation = $lang['or'];
					}

					switch ($ConListRow['opertion']) {
					case 1:
						$opertion = $lang['selected'];
						break;

					case 2:
						$opertion = $lang['unselected'];
						break;
					}

					$AnswerSQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $ConListRow['condOptionID'] . '\' ';
					$AnswerRow = $DB->queryFirstRow($AnswerSQL);
					$conList .= '<font color=blue>( </font>' . $base_questionName . ' - ' . qnohtmltag($OptionRow['optionName']) . $opertion . qnohtmltag($AnswerRow['optionAnswer']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
					break;

				case '31':
					if ($i == $ConListTotal) {
						$opertionRelation = '';
					}
					else if ($ConListRow['logicValueIsAnd'] == 1) {
						$opertionRelation = $lang['and'];
					}
					else {
						$opertionRelation = $lang['or'];
					}

					switch ($ConListRow['opertion']) {
					case 1:
						$opertion = $lang['selected'];
						break;

					case 2:
						$opertion = $lang['unselected'];
						break;
					}

					$AnswerSQL = ' SELECT nodeName FROM ' . CASCADE_TABLE . ' WHERE nodeID=\'' . $ConListRow['condOptionID'] . '\' AND questionID=\'' . $condOnID . '\' ';
					$AnswerRow = $DB->queryFirstRow($AnswerSQL);
					$conList .= '<font color=blue>( </font>' . $base_questionName . ' - ' . qnohtmltag($theOptionName) . $opertion . qnohtmltag($AnswerRow['nodeName']) . '<font color=blue> )</font>' . $opertionRelation . '<br/>';
					break;
				}
			}

			if (2 <= $ConListTotal) {
				$conList .= ' <font color=brown><b>)</b></font><br/>';
			}

			$k++;

			if ($k != $RangeTotal) {
				if ($theIsLogicAnd == '1') {
					$conList .= $lang['and'] . '<br/>';
				}
				else {
					$conList .= $lang['or'] . '<br/>';
				}
			}
		}

		if (2 <= $RangeTotal) {
			$conList .= ' <font color=red><b>}</b></font><br/>';
		}

		break;
	}

	$j++;

	if ($j != count($ConArray)) {
		if ($theIsLogicAnd == '1') {
			$conList .= $lang['and'] . '<br/>';
		}
		else {
			$conList .= $lang['or'] . '<br/>';
		}
	}
}

?>
