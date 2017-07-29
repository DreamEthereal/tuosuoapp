<?php
//dezend by http://www.yunlu99.com/
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
$SQL = ' SELECT isCache,isRelZero FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . $surveyID . '\' ';
$StatRow = $DB->queryFirstRow($SQL);
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
$thisMess = '';
$thisPageCheckList = substr($_GET['hash'], 0, -1);
$thisPageChecks = explode('|', $thisPageCheckList);

foreach ($thisPageChecks as $theCheck) {
	$theQtnType = explode('*', $theCheck);

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

		$thisEmptyId = base64_decode($theQtnType[7]);

		if ($theCheckSkip == false) {
			$theResult = runexpcode($theExpression);
			$theResult = ($theResult == 1 ? 1 : 0);
			$thisMess .= 'var theObj = document.getElementById(\'option_' . $thisEmptyId . '\');' . "\n" . '';
			$thisMess .= 'if(theObj != null ) {' . "\n" . '';
			$thisMess .= '	theObj.value = ' . $theResult . ';' . "\n" . '';
			$thisMess .= '	Run_Survey_Conditions();' . "\n" . '';
			$thisMess .= '}' . "\n" . '';
		}
		else {
			$thisMess .= 'var theObj = document.getElementById(\'option_' . $thisEmptyId . '\');' . "\n" . '';
			$thisMess .= 'if(theObj != null ) {' . "\n" . '';
			$thisMess .= '	theObj.value = 0;' . "\n" . '';
			$thisMess .= '	Run_Survey_Conditions();' . "\n" . '';
			$thisMess .= '}' . "\n" . '';
		}
	}

	echo $thisMess;
}

exit();

?>
