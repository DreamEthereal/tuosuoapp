<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$EnableQCoreClass->setTemplateFile('SurveyEditLogicFile', 'SurveyLogicEdit.html');
$EnableQCoreClass->set_CycBlock('SurveyEditLogicFile', 'LOGIC', 'logic');
$EnableQCoreClass->replace('logic', '');
$theQtnID = $_GET['questionID'];
$SQL = ' SELECT isLogicAnd,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $theQtnID . '\' LIMIT 1 ';
$QtnLogicRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('questionName', qnohtmltag($QtnLogicRow['questionName'], 1) . '&nbsp;[' . $lang['question_type_' . $QtnLogicRow['questionType']] . ']');
$EnableQCoreClass->replace('questionID', $theQtnID);

if ($QtnLogicRow['isLogicAnd'] == 1) {
	$EnableQCoreClass->replace('isLogicAnd1', 'checked');
	$EnableQCoreClass->replace('isLogicAnd0', '');
}
else {
	$EnableQCoreClass->replace('isLogicAnd0', 'checked');
	$EnableQCoreClass->replace('isLogicAnd1', '');
}

$ConSQL = ' SELECT a.*,b.questionName,b.questionType,b.otherText,b.allowType,b.baseID,b.unitText FROM ' . CONDITIONS_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.questionID = \'' . $_GET['questionID'] . '\' AND a.condOnID=b.questionID AND a.surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY a.condOnID ASC,a.qtnID ASC, a.optionID ASC ';
$ConResult = $DB->query($ConSQL);
$recordCount = $DB->_getNumRows($ConResult);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($ConRow = $DB->queryArray($ConResult)) {
	$conName = qnohtmltag($ConRow['questionName'], 1);

	switch ($ConRow['questionType']) {
	case '1':
		$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['optionID'] . '\' ';
		break;

	case '2':
	case '24':
		$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID=\'' . $ConRow['optionID'] . '\' ';
		break;

	case '3':
	case '25':
		if ($ConRow['logicMode'] == 1) {
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['optionID'] . '\' ';
		}
		else {
			$OptionSQL = ' SELECT 1=1';
		}

		break;

	case '6':
	case '7':
		$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID=\'' . $ConRow['qtnID'] . '\' ';
		break;

	case '19':
	case '28':
	case '20':
	case '21':
	case '22':
		if ($ConRow['qtnID'] == 0) {
			$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
		}
		else {
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['qtnID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
		}

		break;

	case '17':
		switch ($ConRow['optionID']) {
		case '0':
			$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
			break;

		case '99999':
			$OptionSQL = ' SELECT allowType as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
			break;

		default:
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['optionID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
			break;
		}

		break;

	case '4':
	case '30':
		$OptionSQL = ' SELECT 1=1';
		break;

	case '10':
		if ($ConRow['qtnID'] == 0) {
			$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
		}
		else {
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['qtnID'] . '\' ';
		}

		break;

	case '15':
	case '16':
		$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['qtnID'] . '\' ';
		break;

	case '23':
		$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['qtnID'] . '\' ';
		break;

	case '31':
		$theUnitText = explode('#', $ConRow['unitText']);
		$theOptionName = $theUnitText[$ConRow['qtnID'] - 1];
		$OptionSQL = ' SELECT 1=1';
		break;
	}

	$OptionRow = $DB->queryFirstRow($OptionSQL);

	if (in_array($ConRow['questionType'], array(4, 23, 10, 15, 16, 20, 21, 22))) {
		switch ($ConRow['opertion']) {
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

		switch ($ConRow['questionType']) {
		case '4':
			$EnableQCoreClass->replace('conName', $conName);
			$EnableQCoreClass->replace('opertion', $opertion);
			$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
			break;

		case '10':
		case '15':
		case '16':
		case '20':
		case '21':
		case '22':
		case '23':
			$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
			$EnableQCoreClass->replace('opertion', $opertion);
			$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
			break;
		}
	}
	else {
		switch ($ConRow['questionType']) {
		case '1':
		case '24':
		case '17':
			$EnableQCoreClass->replace('conName', $conName);
			$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
			$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
			break;

		case '2':
			$EnableQCoreClass->replace('conName', $conName);
			$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

			if ($ConRow['optionID'] != 0) {
				$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
			}
			else {
				$EnableQCoreClass->replace('optionName', qnohtmltag($ConRow['otherText']));
			}

			break;

		case '3':
		case '25':
			if ($ConRow['logicMode'] == 1) {
				$EnableQCoreClass->replace('conName', $conName);
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

				switch ($ConRow['optionID']) {
				case '0':
					$EnableQCoreClass->replace('optionName', qnohtmltag($ConRow['otherText']));
					break;

				case '99999':
					$negText = ($ConRow['allowType'] != '' ? $ConRow['allowType'] : $lang['neg_text']);
					$EnableQCoreClass->replace('optionName', qnohtmltag($negText));
					break;

				default:
					$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
					break;
				}
			}
			else {
				switch ($ConRow['opertion']) {
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

				$EnableQCoreClass->replace('conName', $conName . ' - 回复选项数量');
				$EnableQCoreClass->replace('opertion', $opertion);
				$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
			}

			break;

		case '6':
		case '7':
		case '19':
		case '28':
			$AnswerSQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $ConRow['optionID'] . '\' ';
			$AnswerRow = $DB->queryFirstRow($AnswerSQL);
			$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
			$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
			$EnableQCoreClass->replace('optionName', qnohtmltag($AnswerRow['optionAnswer']));
			break;

		case '30':
			$EnableQCoreClass->replace('conName', $conName);
			$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['logicEqual'] : $lang['logicUnEqual']);
			$EnableQCoreClass->replace('optionName', $ConRow['optionID'] == 1 ? 'True' : 'False');
			break;

		case '31':
			$AnswerSQL = ' SELECT nodeName FROM ' . CASCADE_TABLE . ' WHERE nodeID=\'' . $ConRow['optionID'] . '\' AND questionID = \'' . $ConRow['condOnID'] . '\' ';
			$AnswerRow = $DB->queryFirstRow($AnswerSQL);
			$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($theOptionName));
			$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
			$EnableQCoreClass->replace('optionName', qnohtmltag($AnswerRow['nodeName']));
			break;
		}
	}

	$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=DeleQLogic&conditionsID=' . $ConRow['conditionsID'] . '&questionID=' . $ConRow['questionID'] . '&questionName=' . urlencode($ConRow['questionName']));
	$EnableQCoreClass->parse('logic', 'LOGIC', true);
}

if ($recordCount == 0) {
	$EnableQCoreClass->replace('isHaveLogic', 'none');
	$EnableQCoreClass->replace('isNoHaveLogic', '');
}
else {
	$EnableQCoreClass->replace('isHaveLogic', '');
	$EnableQCoreClass->replace('isNoHaveLogic', 'none');
	$theIsLogicAnd = $QtnLogicRow['isLogicAnd'];
	require 'ShowQtnLogic.inc.php';
}

$EnableQCoreClass->replace('newQtnLogicURL', $thisProg . '&DOes=AddQtnLogicNew&questionID=' . $_GET['questionID']);
$EnableQCoreClass->replace('surveyLogicURL', $thisProg . '&DOes=EditLogic&questionID=' . $_GET['questionID']);

if (in_array($QtnLogicRow['questionType'], array(2, 3, 6, 7, 19, 24, 25, 28))) {
	$EnableQCoreClass->replace('isHaveOptAssLogic', '');
}
else {
	$EnableQCoreClass->replace('isHaveOptAssLogic', 'none');
}

$EnableQCoreClass->replace('newOptAssURL', 'ShowOptAssociate.php' . $thisURLStr . '&DO=AddSingleQtnAss&questionID=' . $_GET['questionID']);
$EnableQCoreClass->set_CycBlock('SurveyEditLogicFile', 'OPTASSLOGIC', 'optasslogic');
$EnableQCoreClass->set_CycBlock('OPTASSLOGIC', 'OPTLOGIC', 'optlogic');
$EnableQCoreClass->replace('optlogic', '');
$EnableQCoreClass->replace('optasslogic', '');
$qSQL = ' SELECT DISTINCT optionID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $_GET['questionID'] . '\' AND assType=2 ORDER BY optionID ASC ';
$qResult = $DB->query($qSQL);
$rcdNum = $DB->_getNumRows($qResult);

if ($rcdNum == 0) {
	$EnableQCoreClass->replace('isNoHaveOptAssLogic', '');
}
else {
	$EnableQCoreClass->replace('isNoHaveOptAssLogic', 'none');
}

while ($qRow = $DB->queryArray($qResult)) {
	switch ($QtnLogicRow['questionType']) {
	case '2':
	case '24':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID =\'' . $qRow['optionID'] . '\'  ';
		break;

	case '3':
	case '25':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $qRow['optionID'] . '\'  ';
		break;

	case '6':
	case '7':
	case '19':
	case '28':
		$rSQL = ' SELECT optionAnswer as optionName,isLogicAnd FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $qRow['optionID'] . '\'  ';
		break;
	}

	$rRow = $DB->queryFirstRow($rSQL);
	$EnableQCoreClass->replace('oas_optionID', $qRow['optionID']);
	$EnableQCoreClass->replace('oas_questionType', $QtnLogicRow['questionType']);
	$EnableQCoreClass->replace('oas_optName', qnohtmltag($rRow['optionName'], 1));

	if ($rRow['isLogicAnd'] == 1) {
		$EnableQCoreClass->replace('oas_isLogicAnd1', 'checked');
		$EnableQCoreClass->replace('oas_isLogicAnd0', '');
	}
	else {
		$EnableQCoreClass->replace('oas_isLogicAnd0', 'checked');
		$EnableQCoreClass->replace('oas_isLogicAnd1', '');
	}

	$theOptAssID = $qRow['optionID'];
	$theIsLogicAnd = $rRow['isLogicAnd'];
	$conList = '';
	require 'ShowOptAssociateSingle.inc.php';
	$EnableQCoreClass->replace('oas_conList', $conList);
	$ConSQL = ' SELECT a.*,b.questionName,b.questionType,b.otherText,b.allowType,b.baseID,b.unitText FROM ' . ASSOCIATE_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.questionID = \'' . $_GET['questionID'] . '\' AND a.optionID =\'' . $qRow['optionID'] . '\' AND a.assType=2 AND a.condOnID=b.questionID AND a.surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY a.condOnID ASC,a.condQtnID ASC, a.condOptionID ASC ';
	$ConResult = $DB->query($ConSQL);
	$recordCount = $DB->_getNumRows($ConResult);
	$EnableQCoreClass->replace('oas_recNum', $recordCount);

	while ($ConRow = $DB->queryArray($ConResult)) {
		$conName = qnohtmltag($ConRow['questionName'], 1);

		switch ($ConRow['questionType']) {
		case '1':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['condOptionID'] . '\' ';
			break;

		case '2':
		case '24':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID=\'' . $ConRow['condOptionID'] . '\' ';
			break;

		case '3':
		case '25':
			if ($ConRow['logicMode'] == 1) {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condOptionID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT 1=1';
			}

			break;

		case '6':
		case '7':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '19':
		case '28':
		case '20':
		case '21':
		case '22':
			if ($ConRow['condQtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condQtnID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
			}

			break;

		case '17':
			switch ($ConRow['condOptionID']) {
			case '0':
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
				break;

			case '99999':
				$OptionSQL = ' SELECT allowType as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
				break;

			default:
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condOptionID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
				break;
			}

			break;

		case '4':
		case '30':
			$OptionSQL = ' SELECT 1=1';
			break;

		case '10':
			if ($ConRow['condQtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['condQtnID'] . '\' ';
			}

			break;

		case '15':
		case '16':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '23':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '31':
			$theUnitText = explode('#', $ConRow['unitText']);
			$theOptionName = $theUnitText[$ConRow['condQtnID'] - 1];
			$OptionSQL = ' SELECT 1=1';
			break;
		}

		$OptionRow = $DB->queryFirstRow($OptionSQL);

		if (in_array($ConRow['questionType'], array(4, 23, 10, 15, 16, 20, 21, 22))) {
			switch ($ConRow['opertion']) {
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

			switch ($ConRow['questionType']) {
			case '4':
				$EnableQCoreClass->replace('oas_conName', $conName);
				$EnableQCoreClass->replace('oas_opertion', $opertion);
				$EnableQCoreClass->replace('oas_optionName', $ConRow['condOptionID']);
				break;

			case '10':
			case '15':
			case '16':
			case '20':
			case '21':
			case '22':
			case '23':
				$EnableQCoreClass->replace('oas_conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('oas_opertion', $opertion);
				$EnableQCoreClass->replace('oas_optionName', $ConRow['condOptionID']);
				break;
			}
		}
		else {
			switch ($ConRow['questionType']) {
			case '1':
			case '24':
			case '17':
				$EnableQCoreClass->replace('oas_conName', $conName);
				$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('oas_optionName', qnohtmltag($OptionRow['optionName']));
				break;

			case '2':
				$EnableQCoreClass->replace('oas_conName', $conName);
				$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

				if ($ConRow['condOptionID'] != 0) {
					$EnableQCoreClass->replace('oas_optionName', qnohtmltag($OptionRow['optionName']));
				}
				else {
					$EnableQCoreClass->replace('oas_optionName', qnohtmltag($ConRow['otherText']));
				}

				break;

			case '3':
			case '25':
				if ($ConRow['logicMode'] == 1) {
					$EnableQCoreClass->replace('oas_conName', $conName);
					$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

					switch ($ConRow['condOptionID']) {
					case '0':
						$EnableQCoreClass->replace('oas_optionName', qnohtmltag($ConRow['otherText']));
						break;

					case '99999':
						$negText = ($ConRow['allowType'] != '' ? $ConRow['allowType'] : $lang['neg_text']);
						$EnableQCoreClass->replace('oas_optionName', qnohtmltag($negText));
						break;

					default:
						$EnableQCoreClass->replace('oas_optionName', qnohtmltag($OptionRow['optionName']));
						break;
					}
				}
				else {
					$EnableQCoreClass->replace('oas_conName', $conName . ' - 回复选项数量');

					switch ($ConRow['opertion']) {
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

					$EnableQCoreClass->replace('oas_opertion', $opertion);
					$EnableQCoreClass->replace('oas_optionName', $ConRow['condOptionID']);
				}

				break;

			case '6':
			case '7':
			case '19':
			case '28':
				$AnswerSQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $ConRow['condOptionID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('oas_conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('oas_optionName', qnohtmltag($AnswerRow['optionAnswer']));
				break;

			case '30':
				$EnableQCoreClass->replace('oas_conName', $conName);
				$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['logicEqual'] : $lang['logicUnEqual']);
				$EnableQCoreClass->replace('oas_optionName', $ConRow['condOptionID'] == 1 ? 'True' : 'False');
				break;

			case '31':
				$AnswerSQL = ' SELECT nodeName FROM ' . CASCADE_TABLE . ' WHERE nodeID=\'' . $ConRow['condOptionID'] . '\' AND questionID = \'' . $ConRow['condOnID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('oas_conName', $conName . ' - ' . qnohtmltag($theOptionName));
				$EnableQCoreClass->replace('oas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('oas_optionName', qnohtmltag($AnswerRow['nodeName']));
				break;
			}
		}

		$EnableQCoreClass->replace('oas_deleteURL', 'ShowOptAssociate.php' . $thisURLStr . '&DO=DeleQLogic&associateID=' . $ConRow['associateID'] . '&questionID=' . $ConRow['questionID'] . '&questionName=' . urlencode($ConRow['questionName']));
		$EnableQCoreClass->parse('optlogic', 'OPTLOGIC', true);
	}

	$EnableQCoreClass->parse('optasslogic', 'OPTASSLOGIC', true);
	$EnableQCoreClass->unreplace('optlogic');
}

if (in_array($QtnLogicRow['questionType'], array(6, 7, 10, 15, 16, 26, 27))) {
	$EnableQCoreClass->replace('isHaveQtnAssLogic', '');
}
else {
	$EnableQCoreClass->replace('isHaveQtnAssLogic', 'none');
}

$EnableQCoreClass->replace('newQtnAssURL', 'ShowQtnAssociate.php' . $thisURLStr . '&DO=AddSingleQtnAss&questionID=' . $_GET['questionID']);
$EnableQCoreClass->set_CycBlock('SurveyEditLogicFile', 'QTNASSLOGIC', 'qtnasslogic');
$EnableQCoreClass->set_CycBlock('QTNASSLOGIC', 'QTNLOGIC', 'qtnlogic');
$EnableQCoreClass->replace('qtnlogic', '');
$EnableQCoreClass->replace('qtnasslogic', '');
$qSQL = ' SELECT DISTINCT qtnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $_GET['questionID'] . '\' AND assType=1 ORDER BY qtnID ASC ';
$qResult = $DB->query($qSQL);
$rcdNum = $DB->_getNumRows($qResult);

if ($rcdNum == 0) {
	$EnableQCoreClass->replace('isNoHaveQtnAssLogic', '');
}
else {
	$EnableQCoreClass->replace('isNoHaveQtnAssLogic', 'none');
}

while ($qRow = $DB->queryArray($qResult)) {
	switch ($QtnLogicRow['questionType']) {
	case '6':
	case '7':
	case '26':
	case '27':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $qRow['qtnID'] . '\'';
		break;

	case '10':
	case '15':
	case '16':
		$rSQL = ' SELECT optionName,isLogicAnd FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID =\'' . $qRow['qtnID'] . '\'';
		break;
	}

	$rRow = $DB->queryFirstRow($rSQL);
	$EnableQCoreClass->replace('qas_qtnID', $qRow['qtnID']);
	$EnableQCoreClass->replace('qas_questionType', $QtnLogicRow['questionType']);
	$EnableQCoreClass->replace('qas_qtnRangeName', qnohtmltag($rRow['optionName'], 1));

	if ($rRow['isLogicAnd'] == 1) {
		$EnableQCoreClass->replace('qas_isLogicAnd1', 'checked');
		$EnableQCoreClass->replace('qas_isLogicAnd0', '');
	}
	else {
		$EnableQCoreClass->replace('qas_isLogicAnd0', 'checked');
		$EnableQCoreClass->replace('qas_isLogicAnd1', '');
	}

	$theQtnAssID = $qRow['qtnID'];
	$theIsLogicAnd = $rRow['isLogicAnd'];
	$conList = '';
	require 'ShowQtnAssociateSingle.inc.php';
	$EnableQCoreClass->replace('qas_conList', $conList);
	$ConSQL = ' SELECT a.*,b.questionName,b.questionType,b.otherText,b.allowType,b.baseID,b.unitText FROM ' . ASSOCIATE_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.questionID = \'' . $_GET['questionID'] . '\' AND a.qtnID =\'' . $qRow['qtnID'] . '\' AND a.assType=1 AND a.condOnID=b.questionID AND a.surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY a.condOnID ASC,a.condQtnID ASC, a.condOptionID ASC ';
	$ConResult = $DB->query($ConSQL);
	$recordCount = $DB->_getNumRows($ConResult);
	$EnableQCoreClass->replace('qas_recNum', $recordCount);

	while ($ConRow = $DB->queryArray($ConResult)) {
		$conName = qnohtmltag($ConRow['questionName'], 1);

		switch ($ConRow['questionType']) {
		case '1':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['condOptionID'] . '\' ';
			break;

		case '2':
		case '24':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID=\'' . $ConRow['condOptionID'] . '\' ';
			break;

		case '3':
		case '25':
			if ($ConRow['logicMode'] == 1) {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condOptionID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT 1=1';
			}

			break;

		case '6':
		case '7':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '19':
		case '28':
		case '20':
		case '21':
		case '22':
			if ($ConRow['condQtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condQtnID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
			}

			break;

		case '17':
			switch ($ConRow['condOptionID']) {
			case '0':
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
				break;

			case '99999':
				$OptionSQL = ' SELECT allowType as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
				break;

			default:
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['condOptionID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
				break;
			}

			break;

		case '4':
		case '30':
			$OptionSQL = ' SELECT 1=1';
			break;

		case '10':
			if ($ConRow['condQtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['condQtnID'] . '\' ';
			}

			break;

		case '15':
		case '16':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '23':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['condQtnID'] . '\' ';
			break;

		case '31':
			$theUnitText = explode('#', $ConRow['unitText']);
			$theOptionName = $theUnitText[$ConRow['condQtnID'] - 1];
			$OptionSQL = ' SELECT 1=1';
			break;
		}

		$OptionRow = $DB->queryFirstRow($OptionSQL);

		if (in_array($ConRow['questionType'], array(4, 23, 10, 15, 16, 20, 21, 22))) {
			switch ($ConRow['opertion']) {
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

			switch ($ConRow['questionType']) {
			case '4':
				$EnableQCoreClass->replace('qas_conName', $conName);
				$EnableQCoreClass->replace('qas_opertion', $opertion);
				$EnableQCoreClass->replace('qas_optionName', $ConRow['condOptionID']);
				break;

			case '10':
			case '15':
			case '16':
			case '20':
			case '21':
			case '22':
			case '23':
				$EnableQCoreClass->replace('qas_conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('qas_opertion', $opertion);
				$EnableQCoreClass->replace('qas_optionName', $ConRow['condOptionID']);
				break;
			}
		}
		else {
			switch ($ConRow['questionType']) {
			case '1':
			case '24':
			case '17':
				$EnableQCoreClass->replace('qas_conName', $conName);
				$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('qas_optionName', qnohtmltag($OptionRow['optionName']));
				break;

			case '2':
				$EnableQCoreClass->replace('qas_conName', $conName);
				$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

				if ($ConRow['condOptionID'] != 0) {
					$EnableQCoreClass->replace('qas_optionName', qnohtmltag($OptionRow['optionName']));
				}
				else {
					$EnableQCoreClass->replace('qas_optionName', qnohtmltag($ConRow['otherText']));
				}

				break;

			case '3':
				if ($ConRow['logicMode'] == 1) {
					$EnableQCoreClass->replace('qas_conName', $conName);
					$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

					switch ($ConRow['condOptionID']) {
					case '0':
						$EnableQCoreClass->replace('qas_optionName', qnohtmltag($ConRow['otherText']));
						break;

					case '99999':
						$negText = ($ConRow['allowType'] != '' ? $ConRow['allowType'] : $lang['neg_text']);
						$EnableQCoreClass->replace('qas_optionName', qnohtmltag($negText));
						break;

					default:
						$EnableQCoreClass->replace('qas_optionName', qnohtmltag($OptionRow['optionName']));
						break;
					}
				}
				else {
					$EnableQCoreClass->replace('qas_conName', $conName . ' - 回复选项数量');

					switch ($ConRow['opertion']) {
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

					$EnableQCoreClass->replace('qas_opertion', $opertion);
					$EnableQCoreClass->replace('qas_optionName', $ConRow['condOptionID']);
				}

				break;

			case '6':
			case '7':
			case '19':
			case '28':
				$AnswerSQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $ConRow['condOptionID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('qas_conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('qas_optionName', qnohtmltag($AnswerRow['optionAnswer']));
				break;

			case '30':
				$EnableQCoreClass->replace('qas_conName', $conName);
				$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['logicEqual'] : $lang['logicUnEqual']);
				$EnableQCoreClass->replace('qas_optionName', $ConRow['condOptionID'] == 1 ? 'True' : 'False');
				break;

			case '31':
				$AnswerSQL = ' SELECT nodeName FROM ' . CASCADE_TABLE . ' WHERE nodeID=\'' . $ConRow['condOptionID'] . '\' AND questionID = \'' . $ConRow['condOnID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('qas_conName', $conName . ' - ' . qnohtmltag($theOptionName));
				$EnableQCoreClass->replace('qas_opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('qas_optionName', qnohtmltag($AnswerRow['nodeName']));
				break;
			}
		}

		$EnableQCoreClass->replace('qas_deleteURL', 'ShowQtnAssociate.php' . $thisURLStr . '&DO=DeleQLogic&associateID=' . $ConRow['associateID'] . '&questionID=' . $ConRow['questionID'] . '&questionName=' . urlencode($ConRow['questionName']));
		$EnableQCoreClass->parse('qtnlogic', 'QTNLOGIC', true);
	}

	$EnableQCoreClass->parse('qtnasslogic', 'QTNASSLOGIC', true);
	$EnableQCoreClass->unreplace('qtnlogic');
}

$EnableQCoreClass->parse('SurveyEditLogic', 'SurveyEditLogicFile');
$EnableQCoreClass->output('SurveyEditLogic');

?>
