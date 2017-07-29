<?php
//dezend by http://www.yunlu99.com/
function exportlogic()
{
	global $DB;
	global $lang;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '"问卷中此问题(多值采用英文逗号分割)(问题Id或问题别名)"';
	$_obf_YfrY8VEd .= ',"依赖于此问题(问题Id或问题别名)"';
	$_obf_YfrY8VEd .= ',"依赖于矩阵问题的子问题(多值采用英文逗号分割)(子问题的顺序号)"';
	$_obf_YfrY8VEd .= ',"运算关系(取值:[=|<|<=|>|>=|!=])"';
	$_obf_YfrY8VEd .= ',"回复值(多值采用英文逗号分割)(选项的顺序号|数值)(99表示“其他”选项)(99999表示“互斥”选项)"';
	$_obf_YfrY8VEd .= ',"值间逻辑与运算(取值:[0|1])"';
	$_obf_YfrY8VEd .= ',"计算模式(针对多选)(取值:[选项模式:1|计算模式:2])"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;
	$_obf_xCnI = ' SELECT DISTINCT a.questionID FROM ' . QUESTION_TABLE . ' a,' . CONDITIONS_TABLE . ' b WHERE a.questionID = b.questionID AND a.surveyID = \'' . $_GET['surveyID'] . '\' AND b.quotaID = 0 ORDER BY a.orderByID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_wDNcGQ__ = ' SELECT a.questionID,a.questionType,a.isHaveOther,a.isNeg,b.* FROM ' . QUESTION_TABLE . ' a,' . CONDITIONS_TABLE . ' b WHERE a.questionID = b.condOnID AND b.questionID = \'' . $_obf_9WwQ['questionID'] . '\' AND a.surveyID = \'' . $_GET['surveyID'] . '\' AND b.quotaID = 0 ORDER BY b.condOnID ASC ';
		$_obf_xXDux6xl_A__ = $DB->query($_obf_wDNcGQ__);

		while ($_obf_e1UiKg__ = $DB->queryArray($_obf_xXDux6xl_A__)) {
			$_obf__WwKzYz1wA__ .= '"' . $_obf_9WwQ['questionID'] . '"';
			$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['condOnID'] . '"';

			switch ($_obf_e1UiKg__['questionType']) {
			case '1':
			case '2':
			case '3':
			case '4':
			case '24':
			case '25':
			case '30':
			case '17':
				$_obf__WwKzYz1wA__ .= ',""';
				break;

			case '6':
			case '7':
				$_obf_UrXeBQ9svomn = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY question_range_optionID ASC ';
				$_obf_wYzGeBYGltIy8kCO = $DB->query($_obf_UrXeBQ9svomn);
				$_obf_Z31K1jfRi36v6upgjKFW43JnXegs = array();
				$_obf_juwe = 1;

				while ($_obf_d2_Ii7CmoqkA = $DB->queryArray($_obf_wYzGeBYGltIy8kCO)) {
					$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_d2_Ii7CmoqkA['question_range_optionID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_e1UiKg__['qtnID']] . '"';
				break;

			case '19':
			case '28':
			case '20':
			case '21':
			case '22':
				$_obf_0_s6Dw__ = ' SELECT baseID FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ';
				$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
				$_obf_HgZQtw__ = ' SELECT isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_obf_wwEsIQ__['baseID'] . '\' ';
				$_obf_q9TEfw__ = $DB->queryFirstRow($_obf_HgZQtw__);
				$_obf_Z31K1jfRi36v6upgjKFW43JnXegs = array();

				if ($_obf_q9TEfw__['isHaveOther'] == '1') {
					$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[0] = 99;
				}

				$_obf_6nGvrFaWL4I_ = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $_obf_wwEsIQ__['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$_obf_AhsvoYTJpmyawRs_ = $DB->query($_obf_6nGvrFaWL4I_);
				$_obf_juwe = 1;

				while ($_obf_xhrAGwoRij0_ = $DB->queryArray($_obf_AhsvoYTJpmyawRs_)) {
					$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_xhrAGwoRij0_['question_checkboxID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_e1UiKg__['qtnID']] . '"';
				break;

			case '23':
				$_obf_vCYZ5EasBB0_ = ' SELECT question_yesnoID,isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY optionOptionID ASC ';
				$_obf_YLm2GXUDLY_qdmg_ = $DB->query($_obf_vCYZ5EasBB0_);
				$_obf_Z31K1jfRi36v6upgjKFW43JnXegs = array();
				$_obf_juwe = 1;

				while ($_obf_SuQLl5ItfbM_ = $DB->queryArray($_obf_YLm2GXUDLY_qdmg_)) {
					if ($_obf_SuQLl5ItfbM_['isCheckType'] == '4') {
						$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_SuQLl5ItfbM_['question_yesnoID']] = $_obf_juwe;
					}

					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_e1UiKg__['qtnID']] . '"';
				break;

			case '10':
			case '15':
			case '16':
				$_obf_Z31K1jfRi36v6upgjKFW43JnXegs = array();
				if (($_obf_e1UiKg__['questionType'] == '10') && ($_obf_e1UiKg__['isHaveOther'] == '1')) {
					$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[0] = 99;
				}

				$_obf_7TXXP0Id2w__ = ' SELECT question_rankID FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY question_rankID ASC ';
				$_obf_ZIsxXug6Yt5_sw__ = $DB->query($_obf_7TXXP0Id2w__);
				$_obf_juwe = 1;

				while ($_obf_sknYm7iBxw__ = $DB->queryArray($_obf_ZIsxXug6Yt5_sw__)) {
					$_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_sknYm7iBxw__['question_rankID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_Z31K1jfRi36v6upgjKFW43JnXegs[$_obf_e1UiKg__['qtnID']] . '"';
				break;

			case '31':
				$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['qtnID'] . '"';
				break;
			}

			switch ($_obf_e1UiKg__['questionType']) {
			case '1':
			case '2':
			case '24':
			case '6':
			case '7':
			case '30':
			case '31':
			case '19':
			case '28':
			case '17':
				switch ($_obf_e1UiKg__['opertion']) {
				case '2':
					$_obf__WwKzYz1wA__ .= ',"!="';
					break;

				case '1':
				default:
					$_obf__WwKzYz1wA__ .= ',"=="';
					break;
				}

				break;

			case '3':
			case '25':
				switch ($_obf_e1UiKg__['logicMode']) {
				case '2':
					switch ($_obf_e1UiKg__['opertion']) {
					case '1':
					default:
						$_obf__WwKzYz1wA__ .= ',"=="';
						break;

					case '2':
						$_obf__WwKzYz1wA__ .= ',"<"';
						break;

					case '3':
						$_obf__WwKzYz1wA__ .= ',"<="';
						break;

					case '4':
						$_obf__WwKzYz1wA__ .= ',">"';
						break;

					case '5':
						$_obf__WwKzYz1wA__ .= ',">="';
						break;

					case '6':
						$_obf__WwKzYz1wA__ .= ',"!="';
						break;
					}

					break;

				default:
					switch ($_obf_e1UiKg__['opertion']) {
					case '2':
						$_obf__WwKzYz1wA__ .= ',"!="';
						break;

					case '1':
					default:
						$_obf__WwKzYz1wA__ .= ',"=="';
						break;
					}

					break;
				}

				break;

			case '4':
			case '23':
			case '10':
			case '15':
			case '16':
			case '20':
			case '21':
			case '22':
				switch ($_obf_e1UiKg__['opertion']) {
				case '1':
				default:
					$_obf__WwKzYz1wA__ .= ',"=="';
					break;

				case '2':
					$_obf__WwKzYz1wA__ .= ',"<"';
					break;

				case '3':
					$_obf__WwKzYz1wA__ .= ',"<="';
					break;

				case '4':
					$_obf__WwKzYz1wA__ .= ',">"';
					break;

				case '5':
					$_obf__WwKzYz1wA__ .= ',">="';
					break;

				case '6':
					$_obf__WwKzYz1wA__ .= ',"!="';
					break;
				}

				break;
			}

			switch ($_obf_e1UiKg__['questionType']) {
			case '1':
				$_obf_vCYZ5EasBB0_ = ' SELECT question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY question_yesnoID ASC ';
				$_obf_YLm2GXUDLY_qdmg_ = $DB->query($_obf_vCYZ5EasBB0_);
				$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz = array();
				$_obf_juwe = 1;

				while ($_obf_SuQLl5ItfbM_ = $DB->queryArray($_obf_YLm2GXUDLY_qdmg_)) {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_SuQLl5ItfbM_['question_yesnoID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_e1UiKg__['optionID']] . '"';
				break;

			case '2':
			case '24':
				$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz = array();
				if (($_obf_e1UiKg__['questionType'] == '2') && ($_obf_e1UiKg__['isHaveOther'] == '1')) {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[0] = 99;
				}

				$_obf_0ObUPtbyXdk_ = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY optionOptionID ASC ';
				$_obf_ElTJgmvFPYkkqRk_ = $DB->query($_obf_0ObUPtbyXdk_);
				$_obf_juwe = 1;

				while ($_obf_4sSQZ7uAzyQ_ = $DB->queryArray($_obf_ElTJgmvFPYkkqRk_)) {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_4sSQZ7uAzyQ_['question_radioID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_e1UiKg__['optionID']] . '"';
				break;

			case '3':
			case '25':
				switch ($_obf_e1UiKg__['logicMode']) {
				case '2':
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['optionID'] . '"';
					break;

				default:
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz = array();

					if ($_obf_e1UiKg__['questionType'] == '3') {
						if ($_obf_e1UiKg__['isHaveOther'] == '1') {
							$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[0] = 99;
						}

						if ($_obf_e1UiKg__['isNeg'] == '1') {
							$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[99999] = 99999;
						}
					}

					$_obf_Y_4GVwEB5HC_snM_ = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY optionOptionID ASC ';
					$_obf_xkBD7PbRRERPRgWjGvU_ = $DB->query($_obf_Y_4GVwEB5HC_snM_);
					$_obf_juwe = 1;

					while ($_obf_ipkNkjXCQkeS09k_ = $DB->queryArray($_obf_xkBD7PbRRERPRgWjGvU_)) {
						$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_ipkNkjXCQkeS09k_['question_checkboxID']] = $_obf_juwe;
						$_obf_juwe++;
					}

					$_obf__WwKzYz1wA__ .= ',"' . $_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_e1UiKg__['optionID']] . '"';
					break;
				}

				break;

			case '6':
			case '7':
			case '19':
			case '28':
				$_obf_vM_gnC45po7J = ' SELECT question_range_answerID FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ORDER BY question_range_answerID ASC ';
				$_obf_XYp2i_oZ2zUcdjLB = $DB->query($_obf_vM_gnC45po7J);
				$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz = array();
				$_obf_juwe = 1;

				while ($_obf_unm31SmvHiwL = $DB->queryArray($_obf_XYp2i_oZ2zUcdjLB)) {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_unm31SmvHiwL['question_range_answerID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_e1UiKg__['optionID']] . '"';
				break;

			case '30':
				switch ($_obf_e1UiKg__['optionID']) {
				case '1':
				default:
					$_obf__WwKzYz1wA__ .= ',"1"';
					break;

				case '2':
					$_obf__WwKzYz1wA__ .= ',"0"';
					break;
				}

				break;

			case '4':
			case '10':
			case '15':
			case '16':
			case '20':
			case '21':
			case '22':
			case '23':
			case '31':
				$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['optionID'] . '"';
				break;

			case '17':
				$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz = array();
				$_obf_0_s6Dw__ = ' SELECT baseID FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $_obf_e1UiKg__['condOnID'] . '\' ';
				$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
				$_obf_HgZQtw__ = ' SELECT isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_obf_wwEsIQ__['baseID'] . '\' ';
				$_obf_q9TEfw__ = $DB->queryFirstRow($_obf_HgZQtw__);

				if ($_obf_q9TEfw__['isHaveOther'] == '1') {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[0] = 99;
				}

				if ($_obf_wwEsIQ__['isCheckType'] == '1') {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[99999] = 99999;
				}

				$_obf_6nGvrFaWL4I_ = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $_obf_wwEsIQ__['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$_obf_AhsvoYTJpmyawRs_ = $DB->query($_obf_6nGvrFaWL4I_);
				$_obf_juwe = 1;

				while ($_obf_xhrAGwoRij0_ = $DB->queryArray($_obf_AhsvoYTJpmyawRs_)) {
					$_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_xhrAGwoRij0_['question_checkboxID']] = $_obf_juwe;
					$_obf_juwe++;
				}

				$_obf__WwKzYz1wA__ .= ',"' . $_obf_3Yd4qKfBZS8EptA6UHeD38CLQpa30ncz[$_obf_e1UiKg__['optionID']] . '"';
				break;
			}

			$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['logicValueIsAnd'] . '"';
			$_obf__WwKzYz1wA__ .= ',"' . $_obf_e1UiKg__['logicMode'] . '"';
			$_obf__WwKzYz1wA__ .= "\r\n";
		}
	}

	return $_obf__WwKzYz1wA__;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
require_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
ob_start();
$logicData = exportlogic();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=QuestionLogic_' . $_GET['surveyID'] . '_List_' . date('Y-m-d') . '.csv');
echo $logicData;
exit();

?>
