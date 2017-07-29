<?php
//dezend by http://www.yunlu99.com/
function exporttrace($surveyID, $E_SQL, $dataArray, $theExportQtnList = array())
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $Config;
	global $License;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	global $CascadeArray;
	$_obf_xCnI = ' SELECT forbidViewId,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
	$_obf_CJLJdLQ_ = $DB->queryFirstRow($_obf_xCnI);

	if ($_SESSION['adminRoleType'] == '3') {
		$_obf_xZj_qBCVvmL2tsH6_MQI_8Y_ = explode(',', $_obf_CJLJdLQ_['forbidViewId']);
	}

	$_obf_Y8nUEaE4gdtNtUrA_A0_ = array();

	foreach ($QtnListArray as $_obf_FMZERrJEkpuCiw__ => $_obf_uFZJM35XMJTetkA_) {
		if (($_obf_uFZJM35XMJTetkA_['questionType'] != '9') && ($_obf_uFZJM35XMJTetkA_['questionType'] != '30')) {
			if ($_SESSION['adminRoleType'] == '3') {
				if (!in_array($_obf_FMZERrJEkpuCiw__, $_obf_xZj_qBCVvmL2tsH6_MQI_8Y_)) {
					if (0 < count($theExportQtnList)) {
						if (in_array($_obf_FMZERrJEkpuCiw__, $theExportQtnList)) {
							$_obf_Y8nUEaE4gdtNtUrA_A0_[] = $_obf_FMZERrJEkpuCiw__;
						}
					}
					else {
						$_obf_Y8nUEaE4gdtNtUrA_A0_[] = $_obf_FMZERrJEkpuCiw__;
					}
				}
			}
			else if (0 < count($theExportQtnList)) {
				if (in_array($_obf_FMZERrJEkpuCiw__, $theExportQtnList)) {
					$_obf_Y8nUEaE4gdtNtUrA_A0_[] = $_obf_FMZERrJEkpuCiw__;
				}
			}
			else {
				$_obf_Y8nUEaE4gdtNtUrA_A0_[] = $_obf_FMZERrJEkpuCiw__;
			}
		}
	}

	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '" ˝æ›–Ú∫≈"';
	$_obf_YfrY8VEd .= ',"»ŒŒÒ¥˙∫≈"';
	$_obf_YfrY8VEd .= ',"»ŒŒÒ√˚≥∆"';
	$_obf_YfrY8VEd .= ',"≤Ÿ◊˜«¯∑÷"';
	$_obf_YfrY8VEd .= ',"≤Ÿ◊˜»À"';
	$_obf_YfrY8VEd .= ',"≤Ÿ◊˜ ±º‰"';
	$_obf_YfrY8VEd .= ',"µ˜≤ÈŒ Ã‚"';
	$_obf_YfrY8VEd .= ',"‘≠÷µ"';
	$_obf_YfrY8VEd .= ',"–ﬁ∂©÷µ"';
	$_obf_YfrY8VEd .= ',"¿Ì”…"';
	$_obf_YfrY8VEd .= ',"÷§æ›"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;

	if (trim($dataArray) == '') {
		$_obf_wDNcGQ__ .= ' 1=1 ';
	}
	else {
		$_obf_UHstX_uAqHAtthQ7 = explode(',', $dataArray);
		$thisDataArray = array();

		foreach ($_obf_UHstX_uAqHAtthQ7 as $thisData) {
			if (trim($thisData) != '') {
				$thisDataArray[] = $thisData;
			}
		}

		if (count($thisDataArray) == 0) {
			$_obf_wDNcGQ__ .= ' 1=0 ';
		}
		else {
			$_obf_wDNcGQ__ .= ' responseID IN (' . implode(',', $thisDataArray) . ') ';
		}
	}

	$_obf_R_8PIOcWIbU_ = ' SELECT * FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';

	if (trim($E_SQL) == '') {
		$_obf_R_8PIOcWIbU_ .= ' WHERE ' . $_obf_wDNcGQ__ . ' AND ' . getdatasourcesql('all', $surveyID);
	}
	else {
		$_obf_R_8PIOcWIbU_ .= ' WHERE ' . $_obf_wDNcGQ__ . ' AND ' . $E_SQL . ' ';
	}

	$_obf_R_8PIOcWIbU_ .= ' ORDER BY responseID DESC ';
	$_obf_Elnmd7yJd4Qf9SE_ = $DB->query($_obf_R_8PIOcWIbU_);

	while ($_obf_ZryYZkD_kA__ = $DB->queryArray($_obf_Elnmd7yJd4Qf9SE_)) {
		$_obf_EukWMmh6SL28UqEbNQ__ = $_obf_ZryYZkD_kA__['responseID'];
		$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -27);

		if ($_obf_CJLJdLQ_['custDataPath'] == '') {
			$_obf_0uHWJmxsie8GdJQeVVZs = $_obf_eHCKgWnq_TI_ . $Config['dataDirectory'] . '/response_' . $surveyID . '/' . date('Y-m', $_obf_ZryYZkD_kA__['joinTime']) . '/' . date('d', $_obf_ZryYZkD_kA__['joinTime']) . '/';
		}
		else {
			$_obf_0uHWJmxsie8GdJQeVVZs = $_obf_eHCKgWnq_TI_ . $Config['dataDirectory'] . '/user/' . $_obf_CJLJdLQ_['custDataPath'] . '/';
		}

		foreach ($_obf_Y8nUEaE4gdtNtUrA_A0_ as $_obf_FMZERrJEkpuCiw__) {
			$_obf_kTtE_lnAfzFOPQ__ = $Module[$QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionType']];

			switch ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionType']) {
			case '1':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						switch ($_obf_zB1UVA__['oriValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . $YesNoListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionName'] . '"';
							break;
						}

						switch ($_obf_zB1UVA__['updateValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . $YesNoListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionName'] . '"';
							break;
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '2':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

							if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isHaveOther'] == '1') {
								switch ($_obf_zB1UVA__['oriValue']) {
								case '0':
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'TextOtherValue_' . $_obf_FMZERrJEkpuCiw__) {
										$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';
									}
									else {
										$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
									}

									break;

								default:
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionName']) . '"';
									break;
								}

								switch ($_obf_zB1UVA__['updateValue']) {
								case '0':
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'TextOtherValue_' . $_obf_FMZERrJEkpuCiw__) {
										$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';
									}
									else {
										$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
									}

									break;

								default:
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionName']) . '"';
									break;
								}
							}
							else {
								switch ($_obf_zB1UVA__['oriValue']) {
								case '0':
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
									break;

								default:
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionName']) . '"';
									break;
								}

								switch ($_obf_zB1UVA__['updateValue']) {
								case '0':
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
									break;

								default:
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionName']) . '"';
									break;
								}
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '3':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

							if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isHaveOther'] == '1') {
								if ($_obf_zB1UVA__['oriValue'] == '') {
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								}
								else if ($_obf_zB1UVA__['oriValue'] == '99999') {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								}
								else {
									$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
									$_obf_8wTeAhD_DVJJk_eWC6lk = '';

									foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
										switch ($thisOriValue) {
										case '0':
											$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . ',';
											break;

										default:
											$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionName']) . ',';
											break;
										}
									}

									$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
								}

								if ($_obf_zB1UVA__['updateValue'] == '') {
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								}
								else if ($_obf_zB1UVA__['updateValue'] == '99999') {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								}
								else {
									$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
									$thisUpdateValueList = '';

									foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
										switch ($thisOriValue) {
										case '0':
											$thisUpdateValueList .= qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . ',';
											break;

										default:
											$thisUpdateValueList .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionName']) . ',';
											break;
										}
									}

									$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
								}
							}
							else {
								if ($_obf_zB1UVA__['oriValue'] == '') {
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								}
								else if ($_obf_zB1UVA__['oriValue'] == '99999') {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								}
								else {
									$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
									$_obf_8wTeAhD_DVJJk_eWC6lk = '';

									foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
										$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionName']) . ',';
									}

									$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
								}

								if ($_obf_zB1UVA__['updateValue'] == '') {
									$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								}
								else if ($_obf_zB1UVA__['updateValue'] == '99999') {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								}
								else {
									$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
									$thisUpdateValueList = '';

									foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
										$thisUpdateValueList .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionName']) . ',';
									}

									$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
								}
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '4':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf_cghXL4t8lyLOiuNn = '';
						$_obf_M0PvuYid_BmkHA__ = true;
						$_obf_cghXL4t8lyLOiuNn .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf_cghXL4t8lyLOiuNn .= ',""';
							$_obf_cghXL4t8lyLOiuNn .= ',""';
						}
						else {
							$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isHaveUnkown'] == 2) {
							$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

							if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
								if ($_obf_zB1UVA__['oriValue'] == '') {
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'isHaveUnkown_' . $_obf_FMZERrJEkpuCiw__) {
										$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
									}
									else {
										$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									}
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
								}

								if ($_obf_zB1UVA__['updateValue'] == '') {
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'isHaveUnkown_' . $_obf_FMZERrJEkpuCiw__) {
										$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
									}
									else {
										$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									}
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
								}
							}
							else {
								$_obf_2d1ffbRbWS_KcMvvm_k_ = $_obf_zB1UVA__['traceID'] - 1;
								$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_2d1ffbRbWS_KcMvvm_k_ . '\' ';
								$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

								if ($_obf__aTmJQ__['varName'] == 'option_' . $_obf_FMZERrJEkpuCiw__) {
									$_obf_M0PvuYid_BmkHA__ = false;
									continue;
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
								}
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',""';
							}
						}
						else {
							$_obf_cghXL4t8lyLOiuNn .= ',""';
							$_obf_cghXL4t8lyLOiuNn .= ',""';
						}

						$_obf_cghXL4t8lyLOiuNn .= "\r\n";

						if ($_obf_M0PvuYid_BmkHA__ == true) {
							$_obf__WwKzYz1wA__ .= $_obf_cghXL4t8lyLOiuNn;
						}
					}
				}

				break;

			case '5':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '6':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';

							switch ($_obf_zB1UVA__['oriValue']) {
							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionAnswer']) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionAnswer']) . '"';
								break;
							}
						}
						else {
							$_obf_d1sZhxFz0JZGUXM3fDU_ = count($OptionListArray[$_obf_FMZERrJEkpuCiw__]);
							$_obf_juwe = 0;
							$_obf_Ege0TiSSZzgh = 0;

							foreach ($OptionListArray[$_obf_FMZERrJEkpuCiw__] as $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_ => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
								$_obf_juwe++;

								if ($_obf_juwe != $_obf_d1sZhxFz0JZGUXM3fDU_) {
									continue;
								}
								else {
									$_obf_Ege0TiSSZzgh = $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_;
								}
							}

							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Ege0TiSSZzgh]['optionName']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '7':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
								$_obf_8wTeAhD_DVJJk_eWC6lk = '';

								foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
									$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionAnswer']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
								$thisUpdateValueList = '';

								foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
									$thisUpdateValueList .= qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionAnswer']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
							}
						}
						else {
							$_obf_d1sZhxFz0JZGUXM3fDU_ = count($OptionListArray[$_obf_FMZERrJEkpuCiw__]);
							$_obf_juwe = 0;
							$_obf_Ege0TiSSZzgh = 0;

							foreach ($OptionListArray[$_obf_FMZERrJEkpuCiw__] as $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_ => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
								$_obf_juwe++;

								if ($_obf_juwe != $_obf_d1sZhxFz0JZGUXM3fDU_) {
									continue;
								}
								else {
									$_obf_Ege0TiSSZzgh = $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_;
								}
							}

							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Ege0TiSSZzgh]['optionName']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '10':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						switch ($_obf_Dpo617xxFuDYCA__[0]) {
						case 'option':
							if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
							}

							$_obf_VBYNJA__ = 1;
							break;

						case 'TextWhyValue':
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-Œ™ ≤√¥’‚√¥≈≈–Ú' . '"';
							$_obf_VBYNJA__ = 2;
							break;

						case 'TextOtherValue':
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';
							$_obf_VBYNJA__ = 2;
							break;
						}

						if ($_obf_VBYNJA__ == 1) {
							if ($_obf_zB1UVA__['oriValue'] == '0') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['oriValue'] . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '0') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['updateValue'] . '"';
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '11':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['oriValue'] . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['updateValue'] . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '13':
				$_obf_4Et55w__ = odbc_connect(trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNConnect']), trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNUser']), trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNPassword']));

				if (!$_obf_4Et55w__) {
					_showerror('System Error', 'Connection Failed:' . trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNConnect']) . '-' . trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNUser']) . '-' . trim($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNPassword']));
				}

				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						switch ($_obf_zB1UVA__['oriValue']) {
						case '':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf_OtXNBmnw = false;
							$_obf_o7wNlZbwjYRGl_Y_ = odbc_exec($_obf_4Et55w__, _getsql($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNSQL']));

							while (odbc_fetch_row($_obf_o7wNlZbwjYRGl_Y_)) {
								$_obf_l6UpUmGy7zZw = odbc_result($_obf_o7wNlZbwjYRGl_Y_, 'ItemValue');
								$_obf_ju4DrHhUxpMVaNc_ = odbc_result($_obf_o7wNlZbwjYRGl_Y_, 'ItemDisplay');
								if (($_obf_l6UpUmGy7zZw == $_obf_zB1UVA__['oriValue']) && ($_obf_OtXNBmnw == false)) {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_ju4DrHhUxpMVaNc_) . '"';
									$_obf_OtXNBmnw = true;
								}
							}

							break;
						}

						switch ($_obf_zB1UVA__['updateValue']) {
						case '':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf_OtXNBmnw = false;
							$_obf_o7wNlZbwjYRGl_Y_ = odbc_exec($_obf_4Et55w__, _getsql($QtnListArray[$_obf_FMZERrJEkpuCiw__]['DSNSQL']));

							while (odbc_fetch_row($_obf_o7wNlZbwjYRGl_Y_)) {
								$_obf_l6UpUmGy7zZw = odbc_result($_obf_o7wNlZbwjYRGl_Y_, 'ItemValue');
								$_obf_ju4DrHhUxpMVaNc_ = odbc_result($_obf_o7wNlZbwjYRGl_Y_, 'ItemDisplay');
								if (($_obf_l6UpUmGy7zZw == $_obf_zB1UVA__['updateValue']) && ($_obf_OtXNBmnw == false)) {
									$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_ju4DrHhUxpMVaNc_) . '"';
									$_obf_OtXNBmnw = true;
								}
							}

							break;
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				odbc_close($_obf_4Et55w__);
				break;

			case '14':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . $_obf_Dpo617xxFuDYCA__[2] . '"';

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '15':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						switch ($_obf_Dpo617xxFuDYCA__[0]) {
						case 'option':
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
							$_obf_VBYNJA__ = 1;
							break;

						case 'TextOtherValue':
							if (count($_obf_Dpo617xxFuDYCA__) == 3) {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '-¿Ì”…' . '"';
							}
							else {
								$_obf_d1sZhxFz0JZGUXM3fDU_ = count($RankListArray[$_obf_FMZERrJEkpuCiw__]);
								$_obf_juwe = 0;
								$_obf_Ege0TiSSZzgh = 0;

								foreach ($RankListArray[$_obf_FMZERrJEkpuCiw__] as $_obf_0ADzfNsCJ1TifIP0nZ0W => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
									$_obf_juwe++;

									if ($_obf_juwe != $_obf_d1sZhxFz0JZGUXM3fDU_) {
										continue;
									}
									else {
										$_obf_Ege0TiSSZzgh = $_obf_0ADzfNsCJ1TifIP0nZ0W;
									}
								}

								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Ege0TiSSZzgh]['optionName']) . '"';
							}

							$_obf_VBYNJA__ = 2;
							break;
						}

						if ($_obf_VBYNJA__ == 1) {
							switch ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isSelect']) {
							case '0':
								if ($_obf_zB1UVA__['oriValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else if ($_obf_zB1UVA__['oriValue'] == '99') {
									$_obf_qfP__tvTvxTraFUa .= $lang['rating_unknow'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= ($_obf_zB1UVA__['oriValue'] * $QtnListArray[$_obf_FMZERrJEkpuCiw__]['weight']) . ']</span>÷¡<span class=red>[';
								}

								if ($_obf_zB1UVA__['updateValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else if ($_obf_zB1UVA__['updateValue'] == '99') {
									$_obf_qfP__tvTvxTraFUa .= $lang['rating_unknow'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= ($_obf_zB1UVA__['updateValue'] * $QtnListArray[$_obf_FMZERrJEkpuCiw__]['weight']) . ']</span>';
								}

								break;

							case '1':
								if (($_obf_zB1UVA__['oriValue'] == '0.00') || ($_obf_zB1UVA__['oriValue'] == '0')) {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['oriValue'] . ']</span>÷¡<span class=red>[';
								}

								if (($_obf_zB1UVA__['updateValue'] == '0.00') || ($_obf_zB1UVA__['updateValue'] == '0')) {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['updateValue'] . ']</span>';
								}

								break;

							case '2':
								if ($_obf_zB1UVA__['oriValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['oriValue'] . ']</span>÷¡<span class=red>[';
								}

								if ($_obf_zB1UVA__['updateValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['updateValue'] . ']</span>';
								}

								break;
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '16':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						switch ($_obf_Dpo617xxFuDYCA__[0]) {
						case 'option':
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
							$_obf_VBYNJA__ = 1;
							break;

						case 'TextOtherValue':
							$_obf_d1sZhxFz0JZGUXM3fDU_ = count($RankListArray[$_obf_FMZERrJEkpuCiw__]);
							$_obf_juwe = 0;
							$_obf_Ege0TiSSZzgh = 0;

							foreach ($RankListArray[$_obf_FMZERrJEkpuCiw__] as $_obf_0ADzfNsCJ1TifIP0nZ0W => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
								$_obf_juwe++;

								if ($_obf_juwe != $_obf_d1sZhxFz0JZGUXM3fDU_) {
									continue;
								}
								else {
									$_obf_Ege0TiSSZzgh = $_obf_0ADzfNsCJ1TifIP0nZ0W;
								}
							}

							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RankListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Ege0TiSSZzgh]['optionName']) . '"';
							$_obf_VBYNJA__ = 2;
							break;
						}

						if ($_obf_VBYNJA__ == 1) {
							if (($_obf_zB1UVA__['oriValue'] == '0') || ($_obf_zB1UVA__['oriValue'] == '0.00')) {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['oriValue'] . '"';
							}

							if (($_obf_zB1UVA__['updateValue'] == '0') || ($_obf_zB1UVA__['updateValue'] == '0.00')) {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['updateValue'] . '"';
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '17':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isSelect'] == 1) {
							switch ($_obf_zB1UVA__['oriValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
								break;

							case '99999':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_zB1UVA__['oriValue']]['optionName']) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
								break;

							case '99999':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_zB1UVA__['updateValue']]['optionName']) . '"';
								break;
							}
						}
						else {
							switch ($_obf_zB1UVA__['oriValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							case '99999':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								break;

							default:
								$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
								$_obf_8wTeAhD_DVJJk_eWC6lk = '';

								foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
									if ($thisOriValue == '0') {
										$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . ',';
									}
									else {
										$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$thisOriValue]['optionName']) . ',';
									}
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							case '99999':
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['allowType']) . '"';
								break;

							default:
								$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
								$thisUpdateValueList = '';

								foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
									if ($thisUpdateValue == '0') {
										$thisUpdateValueList .= qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . ',';
									}
									else {
										$thisUpdateValueList .= qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$thisUpdateValue]['optionName']) . ',';
									}
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
								break;
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '18':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

						if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isSelect'] == 0) {
							switch ($_obf_zB1UVA__['oriValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($YesNoListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionName']) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($YesNoListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionName']) . '"';
								break;
							}
						}
						else {
							switch ($_obf_zB1UVA__['oriValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
								$_obf_8wTeAhD_DVJJk_eWC6lk = '';

								foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
									$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($YesNoListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionName']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
								$thisUpdateValueList = '';

								foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
									$thisUpdateValueList .= qconversionstring($YesNoListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionName']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
								break;
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '19':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
						}

						switch ($_obf_zB1UVA__['oriValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionAnswer']) . '"';
							break;
						}

						switch ($_obf_zB1UVA__['updateValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionAnswer']) . '"';
							break;
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '20':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
						}

						if ($_obf_zB1UVA__['oriValue'] == '0') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['oriValue'] . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '0') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['updateValue'] . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '21':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_xkhC76nJ6aLL83uujA__ = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['isSelect'];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						switch ($_obf_Dpo617xxFuDYCA__[0]) {
						case 'option':
							if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
							}

							$_obf_VBYNJA__ = 1;
							break;

						case 'TextOtherValue':
							if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '-¿Ì”…' . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '-¿Ì”…' . '"';
							}

							$_obf_VBYNJA__ = 2;
							break;
						}

						if ($_obf_VBYNJA__ == 1) {
							switch ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isSelect']) {
							case '0':
								if ($_obf_zB1UVA__['oriValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else if ($_obf_zB1UVA__['oriValue'] == '99') {
									$_obf_qfP__tvTvxTraFUa .= $lang['rating_unknow'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= ($_obf_zB1UVA__['oriValue'] * $QtnListArray[$_obf_FMZERrJEkpuCiw__]['weight']) . ']</span>÷¡<span class=red>[';
								}

								if ($_obf_zB1UVA__['updateValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else if ($_obf_zB1UVA__['updateValue'] == '99') {
									$_obf_qfP__tvTvxTraFUa .= $lang['rating_unknow'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= ($_obf_zB1UVA__['updateValue'] * $QtnListArray[$_obf_FMZERrJEkpuCiw__]['weight']) . ']</span>';
								}

								break;

							case '1':
								if (($_obf_zB1UVA__['oriValue'] == '0.00') || ($_obf_zB1UVA__['oriValue'] == '0')) {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['oriValue'] . ']</span>÷¡<span class=red>[';
								}

								if (($_obf_zB1UVA__['updateValue'] == '0.00') || ($_obf_zB1UVA__['updateValue'] == '0')) {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['updateValue'] . ']</span>';
								}

								break;

							case '2':
								if ($_obf_zB1UVA__['oriValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>÷¡<span class=red>[';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['oriValue'] . ']</span>÷¡<span class=red>[';
								}

								if ($_obf_zB1UVA__['updateValue'] == '0') {
									$_obf_qfP__tvTvxTraFUa .= $lang['skip_answer'] . ']</span>';
								}
								else {
									$_obf_qfP__tvTvxTraFUa .= $_obf_zB1UVA__['updateValue'] . ']</span>';
								}

								break;
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '22':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
						}

						if (($_obf_zB1UVA__['oriValue'] == '0') || ($_obf_zB1UVA__['oriValue'] == '0.00')) {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['oriValue'] . '"';
						}

						if (($_obf_zB1UVA__['updateValue'] == '0') || ($_obf_zB1UVA__['updateValue'] == '0.00')) {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['updateValue'] . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '23':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf_cghXL4t8lyLOiuNn = '';
						$_obf_M0PvuYid_BmkHA__ = true;
						$_obf_cghXL4t8lyLOiuNn .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf_cghXL4t8lyLOiuNn .= ',""';
							$_obf_cghXL4t8lyLOiuNn .= ',""';
						}
						else {
							$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($YesNoListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($QtnListArray[$_obf_FMZERrJEkpuCiw__]['isHaveUnkown'] == 2) {
							if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
								if ($_obf_zB1UVA__['oriValue'] == '') {
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'isHaveUnkown_' . $_obf_FMZERrJEkpuCiw__ . '_' . $_obf_Dpo617xxFuDYCA__[2]) {
										$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
									}
									else {
										$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									}
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
								}

								if ($_obf_zB1UVA__['updateValue'] == '') {
									$_obf_pvzfJjvqE1lgVbNc53g_ = $_obf_zB1UVA__['traceID'] + 1;
									$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_pvzfJjvqE1lgVbNc53g_ . '\' ';
									$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

									if ($_obf__aTmJQ__['varName'] == 'isHaveUnkown_' . $_obf_FMZERrJEkpuCiw__ . '_' . $_obf_Dpo617xxFuDYCA__[2]) {
										$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
									}
									else {
										$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									}
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
								}
							}
							else {
								$_obf_2d1ffbRbWS_KcMvvm_k_ = $_obf_zB1UVA__['traceID'] - 1;
								$_obf_OWpxVw__ = ' SELECT varName FROM ' . DATA_TRACE_TABLE . ' WHERE traceID=\'' . $_obf_2d1ffbRbWS_KcMvvm_k_ . '\' ';
								$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

								if ($_obf__aTmJQ__['varName'] == 'option_' . $_obf_FMZERrJEkpuCiw__ . '_' . $_obf_Dpo617xxFuDYCA__[2]) {
									$_obf_M0PvuYid_BmkHA__ = false;
									continue;
								}
								else {
									$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
									$_obf_cghXL4t8lyLOiuNn .= ',"≤ª«Â≥˛"';
								}
							}
						}
						else {
							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"ø’"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf_cghXL4t8lyLOiuNn .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf_cghXL4t8lyLOiuNn .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf_cghXL4t8lyLOiuNn .= ',""';
							}
						}
						else {
							$_obf_cghXL4t8lyLOiuNn .= ',""';
							$_obf_cghXL4t8lyLOiuNn .= ',""';
						}

						$_obf_cghXL4t8lyLOiuNn .= "\r\n";

						if ($_obf_M0PvuYid_BmkHA__ == true) {
							$_obf__WwKzYz1wA__ .= $_obf_cghXL4t8lyLOiuNn;
						}
					}
				}

				break;

			case '24':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

							switch ($_obf_zB1UVA__['oriValue']) {
							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionName']) . '"';
								break;
							}

							switch ($_obf_zB1UVA__['updateValue']) {
							case '0':
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
								break;

							default:
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionName']) . '"';
								break;
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($RadioListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '25':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[0] == 'option') {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
								$_obf_8wTeAhD_DVJJk_eWC6lk = '';

								foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
									$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionName']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							}
							else {
								$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
								$thisUpdateValueList = '';

								foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
									$thisUpdateValueList .= qconversionstring($CheckBoxListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionName']) . ',';
								}

								$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['otherText']) . '"';

							if ($_obf_zB1UVA__['oriValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
							}

							if ($_obf_zB1UVA__['updateValue'] == '') {
								$_obf__WwKzYz1wA__ .= ',"ø’"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
							}
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '26':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '-' . qconversionstring($LabelListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[3]]['optionLabel']) . '"';

						if ($_obf_zB1UVA__['oriValue'] == '0') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['optionAnswer']) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '0') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['optionAnswer']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '27':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($OptionListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '-' . qconversionstring($LabelListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[3]]['optionLabel']) . '"';

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '28':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '"';
						}

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf_pfqON_GJp_ndh9k_ = explode(',', $_obf_zB1UVA__['oriValue']);
							$_obf_8wTeAhD_DVJJk_eWC6lk = '';

							foreach ($_obf_pfqON_GJp_ndh9k_ as $thisOriValue) {
								$_obf_8wTeAhD_DVJJk_eWC6lk .= qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$thisOriValue]['optionAnswer']) . ',';
							}

							$_obf__WwKzYz1wA__ .= ',"' . substr($_obf_8wTeAhD_DVJJk_eWC6lk, 0, -1) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
						}
						else {
							$_obf_I0OMOc_J3_irLmKOsCA_ = explode(',', $_obf_zB1UVA__['updateValue']);
							$thisUpdateValueList = '';

							foreach ($_obf_I0OMOc_J3_irLmKOsCA_ as $thisUpdateValue) {
								$thisUpdateValueList .= qconversionstring($AnswerListArray[$_obf_FMZERrJEkpuCiw__][$thisUpdateValue]['optionAnswer']) . ',';
							}

							$_obf__WwKzYz1wA__ .= ',"' . substr($thisUpdateValueList, 0, -1) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '29':
				$_obf_cHqc0GUgtMHg = $QtnListArray[$_obf_FMZERrJEkpuCiw__]['baseID'];
				$_obf_g7iAJQRNWdiys3hlkCQ0 = $QtnListArray[$_obf_cHqc0GUgtMHg];
				$_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_ = $CheckBoxListArray[$_obf_g7iAJQRNWdiys3hlkCQ0['questionID']];
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);

						if ($_obf_Dpo617xxFuDYCA__[2] == 0) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_g7iAJQRNWdiys3hlkCQ0['otherText']) . '-' . qconversionstring($LabelListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[3]]['optionLabel']) . '"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_RDMmjjaN970tvjosDgMVVkIpIrQ_[$_obf_Dpo617xxFuDYCA__[2]]['optionName']) . '-' . qconversionstring($LabelListArray[$_obf_FMZERrJEkpuCiw__][$_obf_Dpo617xxFuDYCA__[3]]['optionLabel']) . '"';
						}

						if ($_obf_zB1UVA__['oriValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['oriValue']) . '"';
						}

						if ($_obf_zB1UVA__['updateValue'] == '') {
							$_obf__WwKzYz1wA__ .= ',"ø’"';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['updateValue']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '30':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['oriValue'] . '"';
						$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['updateValue'] . '"';
						$_obf__WwKzYz1wA__ .= ',""';
						$_obf__WwKzYz1wA__ .= ',""';
						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;

			case '31':
				$_obf_oa3Qzw__ = ' SELECT a.isAdmin,a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.traceID,b.traceTime,b.varName,b.oriValue,b.updateValue,b.isAppData,b.reason,b.evidence FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TRACE_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.questionID=\'' . $_obf_FMZERrJEkpuCiw__ . '\' AND b.responseID =\'' . $_obf_EukWMmh6SL28UqEbNQ__ . '\' ORDER BY b.traceTime DESC ';
				$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
				$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

				if ($_obf__phgpOqfbg__ == 0) {
					continue;
				}
				else {
					$_obf_LuDIYLlzmSXaXg4_ = explode('#', $QtnListArray[$_obf_FMZERrJEkpuCiw__]['unitText']);

					while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
						$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

						if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}
						else {
							$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
							$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
							$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
						}

						if ($_obf_zB1UVA__['isAppData'] != 1) {
							if ($_obf_zB1UVA__['isAdmin'] == '4') {
								$_obf_0veEQaifjnA_ = '–ﬁ∏ƒ';
							}
							else {
								$_obf_0veEQaifjnA_ = '…Û∫À';
							}
						}
						else {
							$_obf_0veEQaifjnA_ = '…ÍÀﬂ';
						}

						$_obf__WwKzYz1wA__ .= ',"' . $_obf_0veEQaifjnA_ . '"';
						$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['nickName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
						$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['traceTime']) . '"';
						$_obf_Dpo617xxFuDYCA__ = explode('_', $_obf_zB1UVA__['varName']);
						$_obf_juwe = $_obf_Dpo617xxFuDYCA__[2] - 1;
						$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($QtnListArray[$_obf_FMZERrJEkpuCiw__]['questionName']) . '-' . qconversionstring($_obf_LuDIYLlzmSXaXg4_[$_obf_juwe]) . '"';

						switch ($_obf_zB1UVA__['oriValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($CascadeArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['oriValue']]['nodeName']) . '"';
							break;
						}

						switch ($_obf_zB1UVA__['updateValue']) {
						case '0':
							$_obf__WwKzYz1wA__ .= ',"' . $lang['skip_answer'] . '"';
							break;

						default:
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($CascadeArray[$_obf_FMZERrJEkpuCiw__][$_obf_zB1UVA__['updateValue']]['nodeName']) . '"';
							break;
						}

						if ($_obf_zB1UVA__['isAppData'] == 1) {
							$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_zB1UVA__['reason']) . '"';

							if ($_obf_zB1UVA__['evidence'] != '') {
								$_obf__WwKzYz1wA__ .= ',"' . $_obf_0uHWJmxsie8GdJQeVVZs . $_obf_zB1UVA__['evidence'] . '"';
							}
							else {
								$_obf__WwKzYz1wA__ .= ',""';
							}
						}
						else {
							$_obf__WwKzYz1wA__ .= ',""';
							$_obf__WwKzYz1wA__ .= ',""';
						}

						$_obf__WwKzYz1wA__ .= "\r\n";
					}
				}

				break;
			}
		}
	}

	return $_obf__WwKzYz1wA__;
}

function exportprocess($surveyID, $E_SQL, $dataArray)
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $table_prefix;
	global $Module;
	global $Config;
	global $License;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '" ˝æ›–Ú∫≈"';
	$_obf_YfrY8VEd .= ',"»ŒŒÒ¥˙∫≈"';
	$_obf_YfrY8VEd .= ',"»ŒŒÒ√˚≥∆"';
	$_obf_YfrY8VEd .= ',"≤Ÿ◊˜»À"';
	$_obf_YfrY8VEd .= ',"≤Ÿ◊˜ ±º‰"';
	$_obf_YfrY8VEd .= ',"¥¶¿Ì“‚º˚"';
	$_obf_YfrY8VEd .= ',"≈˙◊¢–≈œ¢"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;

	if (trim($dataArray) == '') {
		$_obf_wDNcGQ__ .= ' 1=1 ';
	}
	else {
		$_obf_UHstX_uAqHAtthQ7 = explode(',', $dataArray);
		$thisDataArray = array();

		foreach ($_obf_UHstX_uAqHAtthQ7 as $thisData) {
			if (trim($thisData) != '') {
				$thisDataArray[] = $thisData;
			}
		}

		if (count($thisDataArray) == 0) {
			$_obf_wDNcGQ__ .= ' 1=0 ';
		}
		else {
			$_obf_wDNcGQ__ .= ' responseID IN (' . implode(',', $thisDataArray) . ') ';
		}
	}

	$_obf_R_8PIOcWIbU_ = ' SELECT * FROM ' . $table_prefix . 'response_' . $surveyID . ' b ';

	if (trim($E_SQL) == '') {
		$_obf_R_8PIOcWIbU_ .= ' WHERE ' . $_obf_wDNcGQ__ . ' AND ' . getdatasourcesql('all', $surveyID);
	}
	else {
		$_obf_R_8PIOcWIbU_ .= ' WHERE ' . $_obf_wDNcGQ__ . ' AND ' . $E_SQL . ' ';
	}

	$_obf_R_8PIOcWIbU_ .= ' ORDER BY responseID DESC ';
	$_obf_Elnmd7yJd4Qf9SE_ = $DB->query($_obf_R_8PIOcWIbU_);

	while ($_obf_ZryYZkD_kA__ = $DB->queryArray($_obf_Elnmd7yJd4Qf9SE_)) {
		$_obf_oa3Qzw__ = ' SELECT a.administratorsID,a.administratorsName,a.userGroupID,a.groupType,b.taskTime,b.authStat,b.appStat,b.reason,b.nextUserId FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $surveyID . '\' AND b.responseID =\'' . $_obf_ZryYZkD_kA__['responseID'] . '\' ORDER BY b.taskTime DESC ';
		$_obf_RX6EXuMjSA__ = $DB->query($_obf_oa3Qzw__);
		$_obf__phgpOqfbg__ = $DB->_getNumRows($_obf_RX6EXuMjSA__);

		if ($_obf__phgpOqfbg__ == 0) {
			continue;
		}
		else {
			while ($_obf_zB1UVA__ = $DB->queryArray($_obf_RX6EXuMjSA__)) {
				$_obf__WwKzYz1wA__ .= '"' . $_obf_ZryYZkD_kA__['responseID'] . '"';

				if ($_obf_ZryYZkD_kA__['taskID'] == 0) {
					$_obf__WwKzYz1wA__ .= ',""';
					$_obf__WwKzYz1wA__ .= ',""';
				}
				else {
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_ZryYZkD_kA__['taskID'] . '"';
					$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_obf_ZryYZkD_kA__['taskID'] . '\' ';
					$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
					$_obf__WwKzYz1wA__ .= ',"' . qconversionstring($_obf_sThE0g__['userGroupName']) . '"';
				}

				$_obf__WwKzYz1wA__ .= ',"' . _getuserallname($_obf_zB1UVA__['administratorsName'], $_obf_zB1UVA__['userGroupID'], $_obf_zB1UVA__['groupType']) . '"';
				$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:i:s', $_obf_zB1UVA__['taskTime']) . '"';

				switch ($_obf_zB1UVA__['authStat']) {
				case '2':
					$_obf__WwKzYz1wA__ .= ',"…Û∫ÀŒ¥Õ®π˝"';
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['reason'] . '"';
					break;

				case '3':
					$_obf_KLoDeQ__ = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $_obf_zB1UVA__['nextUserId'] . '\' ';
					$_obf_gsSYdA__ = $DB->queryFirstRow($_obf_KLoDeQ__);
					$_obf__WwKzYz1wA__ .= ',"…Û∫À÷–,Ã·Ωª' . _getuserallname($_obf_gsSYdA__['administratorsName'], $_obf_gsSYdA__['userGroupID'], $_obf_gsSYdA__['groupType']) . '‘Ÿ…Û∫À' . '"';
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['reason'] . '"';
					break;

				case '1':
					switch ($_obf_zB1UVA__['appStat']) {
					case '0':
						$_obf__WwKzYz1wA__ .= ',"…Û∫ÀÕ®π˝"';
						$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['reason'] . '"';
						break;

					case '3':
						$_obf__WwKzYz1wA__ .= ',"…ÍÀﬂ÷–"';
						break;

					case '1':
						$_obf__WwKzYz1wA__ .= ',"…ÍÀﬂÕ®π˝"';
						$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['reason'] . '"';
						break;

					case '2':
						$_obf__WwKzYz1wA__ .= ',"…ÍÀﬂ ß∞‹"';
						$_obf__WwKzYz1wA__ .= ',"' . $_obf_zB1UVA__['reason'] . '"';
						break;
					}

					break;
				}

				$_obf__WwKzYz1wA__ .= "\r\n";
			}
		}
	}

	return $_obf__WwKzYz1wA__;
}


?>
