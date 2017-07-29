<?php
//dezend by http://www.yunlu99.com/
function qcopyqtnnewname($qtnName)
{
	global $DB;
	global $newQtnArray;
	global $combTextValueArray;
	global $radioArray;
	global $checkboxArray;
	global $multiTextLabelArray;

	if (preg_match_all('/\\[Answer_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];
			$_obf_0_s6Dw__ = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '\' ';
			$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
			if (!$_obf_wwEsIQ__ || !in_array($_obf_wwEsIQ__['questionType'], array(2, 3, 4, 17, 18, 23, 24, 25))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				if (!isset($newQtnArray[$_obf_3MDEGZq_8RQ_]) || ($newQtnArray[$_obf_3MDEGZq_8RQ_] == '')) {
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
				}
				else if (count($_obf_uFZJM35XMJTetkA_) == 2) {
					if (!in_array($_obf_wwEsIQ__['questionType'], array(2, 4, 17, 18, 24))) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
				else {
					switch ($_obf_wwEsIQ__['questionType']) {
					case '2':
					case '3':
						if ($_obf_uFZJM35XMJTetkA_[2] != 0) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_0]';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '23':
						if (!isset($combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '24':
						if (!isset($radioArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($radioArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $radioArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '25':
						if (!isset($checkboxArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($checkboxArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $checkboxArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;
					}
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
	}
	else if (preg_match_all('/\\[Kish_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];
			$_obf_0_s6Dw__ = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '\' ';
			$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
			if (!$_obf_wwEsIQ__ || !in_array($_obf_wwEsIQ__['questionType'], array(23, 27))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				if (!isset($newQtnArray[$_obf_3MDEGZq_8RQ_]) || ($newQtnArray[$_obf_3MDEGZq_8RQ_] == '')) {
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
				}
				else if (count($_obf_uFZJM35XMJTetkA_) == 2) {
					if ($_obf_wwEsIQ__['questionType'] != '23') {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Kish_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
				else if ($_obf_wwEsIQ__['questionType'] == '27') {
					if (!isset($multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Kish_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
	}
	else {
		return $qtnName;
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
