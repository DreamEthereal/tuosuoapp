<?php
//dezend by http://www.yunlu99.com/
function stringtohex($StringText)
{
	$_obf_XsW0wEdz4ZD7gMWe = '';
	$_obf_OA__ = 0;

	for (; $_obf_OA__ < strlen($StringText); $_obf_OA__++) {
		$_obf_XsW0wEdz4ZD7gMWe .= base_convert(ord($StringText[$_obf_OA__]), 10, 16);
	}

	return strtoupper($_obf_XsW0wEdz4ZD7gMWe);
}

function runexpcode($code)
{
	if ($code) {
		ob_start();
		eval ('echo ' . $code . ';');
		$contents = ob_get_contents();
		ob_end_clean();
	}

	return $contents;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
$surveyID = (int) $_GET['qid'];
$SQL = ' SELECT isCache,isCheckStat0,isRelZero FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . $surveyID . '\' ';
$StatRow = $DB->queryFirstRow($SQL);

if ($StatRow['isCheckStat0'] == 1) {
	$checkSQLString = ' overFlag != 2 ';
}
else {
	$checkSQLString = ' overFlag IN (1,3) ';
}

if (($StatRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Qtn' . $surveyID) . '.php')) {
	$theSID = $surveyID;
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Qtn' . $surveyID) . '.php';

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Quota' . $surveyID) . '.php')) {
	$theSID = $surveyID;
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Quota' . $surveyID) . '.php';
header('Content-Type:text/html; charset=gbk');
$thisPageCheckList = substr($_GET['hash'], 0, -1);
$thisPageChecks = explode('|', $thisPageCheckList);
$isCheckPass = true;
$fBreak = false;

foreach ($thisPageChecks as $theCheck) {
	if ($fBreak == true) {
		break;
	}

	$theQtnType = explode('*', $theCheck);

	if ($theQtnType[0] == '4') {
		$thisQtnIDStr = explode('_', $theQtnType[1]);
		$QtnRow = $QtnListArray[$thisQtnIDStr[1]];

		if ($QtnRow['isNeg'] == 1) {
			$theUnescapeString = stringtohex(iconv('UTF-8', 'gbk', unescape(trim($_GET[$theQtnType[1]]))));
			$SQL = ' SELECT count(*) as theRemainNum FROM ' . $table_prefix . 'response_' . $_GET['qid'] . ' WHERE hex(' . $theQtnType[1] . ') = \'' . $theUnescapeString . '\' AND ' . $theQtnType[1] . ' != \'\' AND ' . $checkSQLString;

			if (trim($_GET['theResponseID']) != '') {
				$SQL .= ' AND responseID !=\'' . trim($_GET['theResponseID']) . '\' ';
			}
			else if (isset($_SESSION['responseID_' . $surveyID])) {
				$SQL .= ' AND responseID !=\'' . $_SESSION['responseID_' . $surveyID] . '\' ';
			}

			$SQL .= ' LIMIT 0,1 ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if (0 < $HaveRow['theRemainNum']) {
				$thisMess = '$.notification(\'"' . qnoscriptstring($QtnRow['questionName']) . '"' . $lang['is_repeat_text'] . '\');' . "\n" . '';
				$thisMess .= 'document.Survey_Form.' . $theQtnType[1] . '.focus();' . "\n" . '';
				$isCheckPass = false;
				$fBreak = true;
			}
		}

		if ($QtnRow['isSelect'] == 1) {
			if (trim($_GET[$theQtnType[1]]) != '') {
				$theUnescapeString = stringtohex(iconv('UTF-8', 'gbk', unescape(trim($_GET[$theQtnType[1]]))));
				$SQL = ' SELECT count(*) as theRemainNum FROM ' . TEXT_OPTION_TABLE . ' WHERE hex(optionText) = \'' . $theUnescapeString . '\' AND questionID=\'' . $thisQtnIDStr[1] . '\' ';
				$SQL .= ' LIMIT 0,1 ';
				$HaveRow = $DB->queryFirstRow($SQL);

				if ($HaveRow['theRemainNum'] == 0) {
					$thisMess = '$.notification(\'"' . qnoscriptstring($QtnRow['questionName']) . '"' . $lang['is_not_allowed_text'] . '\');' . "\n" . '';
					$thisMess .= 'document.Survey_Form.' . $theQtnType[1] . '.focus();' . "\n" . '';
					$isCheckPass = false;
					$fBreak = true;
				}
			}
		}
	}

	if ($theQtnType[0] == '23') {
		$thisQtnIDStr = explode('_', $theQtnType[1]);
		$theQuestionID = $thisQtnIDStr[1];
		$theOptionID = $thisQtnIDStr[2];
		$theUnescapeString = stringtohex(iconv('UTF-8', 'gbk', unescape(trim($_GET[$theQtnType[1]]))));
		$SQL = ' SELECT count(*) as theRemainNum FROM ' . $table_prefix . 'response_' . $_GET['qid'] . ' WHERE hex(' . $theQtnType[1] . ') = \'' . $theUnescapeString . '\' AND ' . $theQtnType[1] . ' != \'\' AND ' . $checkSQLString;

		if (trim($_GET['theResponseID']) != '') {
			$SQL .= ' AND responseID !=\'' . trim($_GET['theResponseID']) . '\' ';
		}
		else if (isset($_SESSION['responseID_' . $surveyID])) {
			$SQL .= ' AND responseID !=\'' . $_SESSION['responseID_' . $surveyID] . '\' ';
		}

		$SQL .= ' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (0 < $HaveRow['theRemainNum']) {
			$thisMess = '$.notification(\'"' . qnoscriptstring($QtnListArray[$theQuestionID]['questionName']) . ' - ' . qnoscriptstring($YesNoListArray[$theQuestionID][$theOptionID]['optionName']) . '"' . $lang['is_repeat_text'] . '\');' . "\n" . '';
			$thisMess .= 'document.Survey_Form.' . $theQtnType[1] . '.focus();' . "\n" . '';
			$isCheckPass = false;
			$fBreak = true;
		}
	}

	if ($theQtnType[0] == '2') {
		$thisQtnIDStr = explode('_', $theQtnType[1]);
		$theQuestionID = $thisQtnIDStr[1];
		$theOptionID = $_GET[$theQtnType[1]];

		if ($RadioListArray[$theQuestionID][$theOptionID]['optionMargin'] != 0) {
			$SQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['qid'] . ' WHERE ' . $theQtnType[1] . ' = \'' . $theOptionID . '\' AND overFlag IN (1,3) ';

			if (trim($_GET['theResponseID']) != '') {
				$SQL .= ' AND responseID !=\'' . trim($_GET['theResponseID']) . '\' ';
			}
			else if (isset($_SESSION['responseID_' . $surveyID])) {
				$SQL .= ' AND responseID !=\'' . $_SESSION['responseID_' . $surveyID] . '\' ';
			}

			$SQL .= ' LIMIT 0,1 ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($RadioListArray[$theQuestionID][$theOptionID]['optionMargin'] <= $HaveRow['optionResponseNum']) {
				$thisMess = '$.notification(\'"' . qnoscriptstring($QtnListArray[$theQuestionID]['questionName']) . ' - ' . qnoscriptstring($RadioListArray[$theQuestionID][$theOptionID]['optionName']) . '"' . $lang['goto_margin'] . '\');' . "\n" . '';
				$thisMess .= 'document.Survey_Form.' . $theQtnType[1] . '[0].focus();' . "\n" . '';
				$isCheckPass = false;
				$fBreak = true;
			}
		}
	}

	if ($theQtnType[0] == '3') {
		$theSelectedIDList = explode(',', $_GET[$theQtnType[1]]);
		$thisQtnIDStr = explode('_', $theQtnType[1]);
		$theQuestionID = $thisQtnIDStr[1];

		foreach ($theSelectedIDList as $theSelectedID) {
			if ($CheckBoxListArray[$theQuestionID][$theSelectedID]['optionMargin'] != 0) {
				$SQL = ' SELECT COUNT(*) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $_GET['qid'] . ' WHERE FIND_IN_SET(' . $theSelectedID . ',' . $theQtnType[1] . ') AND overFlag IN (1,3) ';

				if (trim($_GET['theResponseID']) != '') {
					$SQL .= ' AND responseID !=\'' . trim($_GET['theResponseID']) . '\' ';
				}
				else if (isset($_SESSION['responseID_' . $surveyID])) {
					$SQL .= ' AND responseID !=\'' . $_SESSION['responseID_' . $surveyID] . '\' ';
				}

				$SQL .= ' LIMIT 0,1 ';
				$HaveRow = $DB->queryFirstRow($SQL);

				if ($CheckBoxListArray[$theQuestionID][$theSelectedID]['optionMargin'] <= $HaveRow['optionResponseNum']) {
					$thisQtnIDStr = explode('_', $theQtnType[1]);
					$thisMess = '$.notification(\'"' . qnoscriptstring($QtnListArray[$theQuestionID]['questionName']) . ' - ' . qnoscriptstring($CheckBoxListArray[$theQuestionID][$theSelectedID]['optionName']) . '"' . $lang['goto_margin'] . '\');' . "\n" . '';
					$thisMess .= 'document.Survey_Form.' . $theQtnType[1] . '[0].focus();' . "\n" . '';
					$isCheckPass = false;
					$fBreak = true;
					break;
				}
			}
		}
	}

	if ($theQtnType[0] == '0') {
		$theVarArray = explode('$', $theQtnType[1]);
		$qid = $surveyID;
		$theQuotaID = $theVarArray[0];
		$questionID = $theVarArray[1];
		$theMgtFunc = $theVarArray[2];
		$theResponseID = $theVarArray[3];
		require ROOT_PATH . 'JS/SurveyQuota.inc.php';

		if ($conList == '') {
			continue;
		}
		else {
			$SQL = ' SELECT COUNT(*) as theQuotaNum FROM ' . $table_prefix . 'response_' . $surveyID;

			if ($theResponseID != '') {
				$SQL .= ' WHERE responseID != \'' . $theResponseID . '\' AND ' . $checkSQLString . ' AND ' . $conList . ' LIMIT 1 ';
			}
			else if (isset($_SESSION['responseID_' . $surveyID])) {
				$SQL .= ' WHERE responseID !=\'' . $_SESSION['responseID_' . $surveyID] . '\' AND ' . $checkSQLString . ' AND ' . $conList . ' LIMIT 1 ';
			}
			else {
				$SQL .= ' WHERE ' . $checkSQLString . ' AND ' . $conList . ' LIMIT 1 ';
			}

			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow['theQuotaNum'] < $QuotaNumArray[$theQuotaID]['quotaNum']) {
				continue;
			}
			else {
				if ($QuotaNumArray[$theQuotaID]['quotaNum'] == 0) {
					$thisMess = 'document.getElementById(\'screeningFlag\').value=\'2\';' . "\n" . '';
				}
				else {
					$thisMess = 'document.getElementById(\'screeningFlag\').value=\'3\';' . "\n" . '';
				}

				$thisMess .= 'document.getElementById(\'surveyQuotaFlag\').value=\'2\';' . "\n" . '';
				$thisMess .= 'document.getElementById(\'surveyQuotaId\').value=\'' . $theQuotaID . '\';' . "\n" . '';
				$thisMess .= 'document.Survey_Form.Action.value=\'SurveyOverSubmit\';' . "\n" . '';
				$thisMess .= 'if( document.getElementById(\'SurveyNextSubmit\') != null ) document.getElementById(\'SurveyNextSubmit\').disabled = true;';
				$thisMess .= 'if( document.getElementById(\'SurveyOverSubmit\') != null ) document.getElementById(\'SurveyOverSubmit\').disabled = true;';
				$thisMess .= 'if( document.getElementById(\'SurveyPreSubmit\') != null ) document.getElementById(\'SurveyPreSubmit\').disabled = true;';
				$thisMess .= 'if( document.getElementById(\'SurveyCacheSubmit\') != null ) document.getElementById(\'SurveyCacheSubmit\').disabled = true;';
				$thisMess .= 'if( document.getElementById(\'SurveyResetSubmit\') != null ) document.getElementById(\'SurveyResetSubmit\').disabled = true;';
				$thisMess .= 'if( document.getElementById(\'SurveyCancelSubmit\') != null ) document.getElementById(\'SurveyCancelSubmit\').disabled = true;';
				$thisMess .= 'document.Survey_Form.submit();';
				$isCheckPass = false;
				$fBreak = true;
			}
		}
	}

	if ($theQtnType[0] == 's1') {
		$theFieldsList = explode('$', $theQtnType[3]);
		$theOpertionsList = explode('$', $theQtnType[4]);
		$theExpression = '';
		$theCheckSkip = false;

		if ($theQtnType[2] == 'm2') {
			$m = 1;

			for (; $m < count($theFieldsList); $m++) {
				$objFieldList = explode('-', $theFieldsList[$m]);
				$theQtnList = explode('_', $objFieldList[1]);
				$theQtnId = $theQtnList[1];

				switch ($QtnListArray[$theQtnId]['questionType']) {
				case 1:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 2:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($thisValue) {
						case '0':
							$theValue = $QtnListArray[$theQtnId]['optionValue'];
							break;

						default:
							$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
							break;
						}
					}

					break;

				case 3:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($objFieldList[2]) {
						case '0':
							$theValue = $QtnListArray[$theQtnId]['optionValue'];
							break;

						case '99999':
							$theValue = $QtnListArray[$theQtnId]['negValue'];
							break;

						default:
							$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
							break;
						}
					}

					break;

				case 4:
				case 16:
				case 22:
				case 23:
				case 27:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $thisValue;
					}

					break;

				case 6:
				case 19:
				case 26:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $AnswerListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 7:
				case 28:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $AnswerListArray[$theQtnId][$objFieldList[2]]['optionValue'];
					}

					break;

				case 15:
				case 21:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($QtnListArray[$theQtnId]['isSelect']) {
						case '0':
							switch ($thisValue) {
							case '99':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							default:
								$theValue = $thisValue * $QtnListArray[$theQtnId]['weight'];
								break;
							}

							break;

						default:
							$theValue = $thisValue;
							break;
						}
					}

					break;

				case 17:
					if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
						$thisValue = unescape($_GET[$objFieldList[1]]);

						if ($thisValue == '') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							switch ($thisValue) {
							case '99999':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							case '0':
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $QtnListArray[$theBaseID]['optionValue'];
								break;

							default:
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $CheckBoxListArray[$theBaseID][$thisValue]['optionValue'];
								break;
							}
						}
					}
					else {
						$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

						if ($thisValue == '0') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							switch ($objFieldList[2]) {
							case '0':
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $QtnListArray[$theBaseID]['optionValue'];
								break;

							case '99999':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							default:
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $CheckBoxListArray[$theBaseID][$objFieldList[2]]['optionValue'];
								break;
							}
						}
					}

					break;

				case 18:
					if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
						$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

						if ($thisValue == '0') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							$theValue = $YesNoListArray[$theQtnId][$objFieldList[2]]['optionValue'];
						}
					}
					else {
						$thisValue = unescape($_GET[$objFieldList[1]]);

						if ($thisValue == '') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}

						$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 24:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 25:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
					}

					break;
				}

				if ($m == 1) {
					$theExpression .= $theValue;
				}
				else {
					$t = $m - 2;

					switch ($theOpertionsList[$t]) {
					case 1:
						$theExpression .= '+' . $theValue;
						break;

					case 2:
						$theExpression .= '-' . $theValue;
						break;

					case 3:
						$theExpression .= '*' . $theValue;
						break;

					case 4:
						$theExpression .= '/' . $theValue;
						break;
					}
				}

				if ($theCheckSkip == true) {
					break;
				}
			}

			switch ($theQtnType[1]) {
			case 1:
				$theExpression .= '==';
				break;

			case 2:
				$theExpression .= '<';
				break;

			case 3:
				$theExpression .= '<=';
				break;

			case 4:
				$theExpression .= '>';
				break;

			case 5:
				$theExpression .= '>=';
				break;

			case 6:
				$theExpression .= '!=';
				break;
			}

			$objFieldList = explode('-', $theFieldsList[0]);
			$theQtnList = explode('_', $objFieldList[1]);
			$theQtnId = $theQtnList[1];

			switch ($QtnListArray[$theQtnId]['questionType']) {
			case 1:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
				}

				break;

			case 2:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					switch ($thisValue) {
					case '0':
						$theValue = $QtnListArray[$theQtnId]['optionValue'];
						break;

					default:
						$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
						break;
					}
				}

				break;

			case 3:
				$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

				if ($thisValue == '0') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					switch ($objFieldList[2]) {
					case '0':
						$theValue = $QtnListArray[$theQtnId]['optionValue'];
						break;

					case '99999':
						$theValue = $QtnListArray[$theQtnId]['negValue'];
						break;

					default:
						$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
						break;
					}
				}

				break;

			case 4:
			case 16:
			case 22:
			case 23:
			case 27:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $thisValue;
				}

				break;

			case 6:
			case 19:
			case 26:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $AnswerListArray[$theQtnId][$thisValue]['optionValue'];
				}

				break;

			case 7:
			case 28:
				$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

				if ($thisValue == '0') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $AnswerListArray[$theQtnId][$objFieldList[2]]['optionValue'];
				}

				break;

			case 15:
			case 21:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					switch ($QtnListArray[$theQtnId]['isSelect']) {
					case '0':
						switch ($thisValue) {
						case '99':
							$theValue = $QtnListArray[$theQtnId]['negValue'];
							break;

						default:
							$theValue = $thisValue * $QtnListArray[$theQtnId]['weight'];
							break;
						}

						break;

					default:
						$theValue = $thisValue;
						break;
					}
				}

				break;

			case 17:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($thisValue) {
						case '99999':
							$theValue = $QtnListArray[$theQtnId]['negValue'];
							break;

						case '0':
							$theBaseID = $QtnListArray[$theQtnId]['baseID'];
							$theValue = $QtnListArray[$theBaseID]['optionValue'];
							break;

						default:
							$theBaseID = $QtnListArray[$theQtnId]['baseID'];
							$theValue = $CheckBoxListArray[$theBaseID][$thisValue]['optionValue'];
							break;
						}
					}
				}
				else {
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($objFieldList[2]) {
						case '0':
							$theBaseID = $QtnListArray[$theQtnId]['baseID'];
							$theValue = $QtnListArray[$theBaseID]['optionValue'];
							break;

						case '99999':
							$theValue = $QtnListArray[$theQtnId]['negValue'];
							break;

						default:
							$theBaseID = $QtnListArray[$theQtnId]['baseID'];
							$theValue = $CheckBoxListArray[$theBaseID][$objFieldList[2]]['optionValue'];
							break;
						}
					}
				}

				break;

			case 18:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $YesNoListArray[$theQtnId][$objFieldList[2]]['optionValue'];
					}
				}
				else {
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}

					$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
				}

				break;

			case 24:
				$thisValue = unescape($_GET[$objFieldList[1]]);

				if ($thisValue == '') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
				}

				break;

			case 25:
				$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

				if ($thisValue == '0') {
					if ($StatRow['isRelZero'] == 1) {
						$theValue = 0;
					}
					else {
						$theCheckSkip = true;
						break;
					}
				}
				else {
					$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
				}

				break;
			}

			$theExpression .= $theValue;
		}
		else {
			$m = 0;

			for (; $m < count($theFieldsList); $m++) {
				$objFieldList = explode('-', $theFieldsList[$m]);
				$theQtnList = explode('_', $objFieldList[1]);
				$theQtnId = $theQtnList[1];

				switch ($QtnListArray[$theQtnId]['questionType']) {
				case 1:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 2:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($thisValue) {
						case '0':
							$theValue = $QtnListArray[$theQtnId]['optionValue'];
							break;

						default:
							$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
							break;
						}
					}

					break;

				case 3:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($objFieldList[2]) {
						case '0':
							$theValue = $QtnListArray[$theQtnId]['optionValue'];
							break;

						case '99999':
							$theValue = $QtnListArray[$theQtnId]['negValue'];
							break;

						default:
							$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
							break;
						}
					}

					break;

				case 4:
				case 16:
				case 22:
				case 23:
				case 27:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $thisValue;
					}

					break;

				case 6:
				case 19:
				case 26:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $AnswerListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 7:
				case 28:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $AnswerListArray[$theQtnId][$objFieldList[2]]['optionValue'];
					}

					break;

				case 15:
				case 21:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						switch ($QtnListArray[$theQtnId]['isSelect']) {
						case '0':
							switch ($thisValue) {
							case '99':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							default:
								$theValue = $thisValue * $QtnListArray[$theQtnId]['weight'];
								break;
							}

							break;

						default:
							$theValue = $thisValue;
							break;
						}
					}

					break;

				case 17:
					if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
						$thisValue = unescape($_GET[$objFieldList[1]]);

						if ($thisValue == '') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							switch ($thisValue) {
							case '99999':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							case '0':
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $QtnListArray[$theBaseID]['optionValue'];
								break;

							default:
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $CheckBoxListArray[$theBaseID][$thisValue]['optionValue'];
								break;
							}
						}
					}
					else {
						$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

						if ($thisValue == '0') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							switch ($objFieldList[2]) {
							case '0':
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $QtnListArray[$theBaseID]['optionValue'];
								break;

							case '99999':
								$theValue = $QtnListArray[$theQtnId]['negValue'];
								break;

							default:
								$theBaseID = $QtnListArray[$theQtnId]['baseID'];
								$theValue = $CheckBoxListArray[$theBaseID][$objFieldList[2]]['optionValue'];
								break;
							}
						}
					}

					break;

				case 18:
					if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
						$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

						if ($thisValue == '0') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}
						else {
							$theValue = $YesNoListArray[$theQtnId][$objFieldList[2]]['optionValue'];
						}
					}
					else {
						$thisValue = unescape($_GET[$objFieldList[1]]);

						if ($thisValue == '') {
							if ($StatRow['isRelZero'] == 1) {
								$theValue = 0;
							}
							else {
								$theCheckSkip = true;
								break;
							}
						}

						$theValue = $YesNoListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 24:
					$thisValue = unescape($_GET[$objFieldList[1]]);

					if ($thisValue == '') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $RadioListArray[$theQtnId][$thisValue]['optionValue'];
					}

					break;

				case 25:
					$thisValue = $_GET[$objFieldList[1] . '-' . $objFieldList[2]];

					if ($thisValue == '0') {
						if ($StatRow['isRelZero'] == 1) {
							$theValue = 0;
						}
						else {
							$theCheckSkip = true;
							break;
						}
					}
					else {
						$theValue = $CheckBoxListArray[$theQtnId][$objFieldList[2]]['optionValue'];
					}

					break;
				}

				if ($m == 0) {
					$theExpression .= $theValue;
				}
				else {
					$t = $m - 1;

					switch ($theOpertionsList[$t]) {
					case 1:
						$theExpression .= '+' . $theValue;
						break;

					case 2:
						$theExpression .= '-' . $theValue;
						break;

					case 3:
						$theExpression .= '*' . $theValue;
						break;

					case 4:
						$theExpression .= '/' . $theValue;
						break;
					}
				}

				if ($theCheckSkip == true) {
					break;
				}
			}

			switch ($theQtnType[1]) {
			case 1:
				$theExpression .= '==';
				break;

			case 2:
				$theExpression .= '<';
				break;

			case 3:
				$theExpression .= '<=';
				break;

			case 4:
				$theExpression .= '>';
				break;

			case 5:
				$theExpression .= '>=';
				break;

			case 6:
				$theExpression .= '!=';
				break;
			}

			$theExpression .= $theQtnType[2];
		}

		if ($theCheckSkip == false) {
			if (runexpcode($theExpression) != 1) {
				$thisMess = 'var theObj = document.getElementById(\'question_' . $theQtnType[5] . '\');' . "\n" . '';
				$thisMess .= 'if(theObj != null ) {' . "\n" . '';
				$thisMess .= '	theObj.scrollIntoView(true);' . "\n" . '';
				$thisMess .= '	theObj.focus();' . "\n" . '';
				$thisMess .= '}' . "\n" . '';
				$thisMess .= '$.notification(\'' . qnoscriptstring(base64_decode($theQtnType[6]) . $lang['not_established']) . '\');' . "\n" . '';
				$isCheckPass = false;
				$fBreak = true;
			}
		}
	}
}

if ($isCheckPass == false) {
	echo $thisMess;
	exit();
}
else {
	$thisMess = 'if( document.getElementById(\'SurveyNextSubmit\') != null ) document.getElementById(\'SurveyNextSubmit\').disabled = true;';
	$thisMess .= 'if( document.getElementById(\'SurveyOverSubmit\') != null ) document.getElementById(\'SurveyOverSubmit\').disabled = true;';
	$thisMess .= 'if( document.getElementById(\'SurveyPreSubmit\') != null ) document.getElementById(\'SurveyPreSubmit\').disabled = true;';
	$thisMess .= 'if( document.getElementById(\'SurveyCacheSubmit\') != null ) document.getElementById(\'SurveyCacheSubmit\').disabled = true;';
	$thisMess .= 'if( document.getElementById(\'SurveyResetSubmit\') != null ) document.getElementById(\'SurveyResetSubmit\').disabled = true;';
	$thisMess .= 'if( document.getElementById(\'SurveyCancelSubmit\') != null ) document.getElementById(\'SurveyCancelSubmit\').disabled = true;';
	$thisMess .= 'document.Survey_Form.submit();';
	echo $thisMess;
	exit();
}

?>
