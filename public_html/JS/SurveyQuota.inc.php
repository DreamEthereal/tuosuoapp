<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$j = 0;
$conList = '';

foreach ($QuotaListArray[$theQuotaID] as $condOnID => $theCondOnIDArray) {
	$condOnID_QuestionType = $QtnListArray[$condOnID]['questionType'];

	switch ($condOnID_QuestionType) {
	case '1':
	case '2':
	case '3':
	case '4':
	case '24':
	case '25':
	case '30':
	case '17':
		$Con_Total = count($theCondOnIDArray[0]);
		$i = 0;

		if (2 <= $Con_Total) {
			$conList .= '(';
		}

		foreach ($theCondOnIDArray[0] as $theOptionArray) {
			$i++;

			if ($condOnID_QuestionType == '4') {
				switch ($theOptionArray[1]) {
				case 1:
					$opertion = '=';
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

				if ($i == $Con_Total) {
					$opertionRelation = '';
				}
				else {
					$opertionRelation = ' AND ';
				}

				$conList .= '(option_' . $condOnID . ' != \'\' AND option_' . $condOnID . $opertion . $theOptionArray[0] . ') ' . $opertionRelation;
			}
			else {
				if ($i == $Con_Total) {
					$opertionRelation = '';
				}
				else if ($theOptionArray[2] == 1) {
					$opertionRelation = ' AND ';
				}
				else {
					$opertionRelation = ' OR ';
				}

				switch ($condOnID_QuestionType) {
				case '1':
				case '2':
				case '24':
					if ($theOptionArray[1] == 1) {
						$conList .= 'option_' . $condOnID . ' = ' . $theOptionArray[0] . ' ' . $opertionRelation;
					}
					else {
						$conList .= 'option_' . $condOnID . ' != ' . $theOptionArray[0] . ' ' . $opertionRelation;
					}

					break;

				case '3':
				case '25':
					if ($theOptionArray[3] == 2) {
						switch ($theOptionArray[1]) {
						case 1:
							$opertion = '=';
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

						if ($i == $Con_Total) {
							$opertionRelation = '';
						}
						else {
							$opertionRelation = ' AND ';
						}

						$conList .= '(LENGTH(option_' . $condOnID . ')-LENGTH(REPLACE(option_' . $condOnID . ',\',\',\'\'))+1' . $opertion . $theOptionArray[0] . ')' . $opertionRelation;
					}
					else if ($theOptionArray[1] == 1) {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . ') ' . $opertionRelation;
					}
					else {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . ')=0 ' . $opertionRelation;
					}

					break;

				case '17':
					if ($QtnListArray[$condOnID]['isSelect'] == 1) {
						if ($theOptionArray[1] == 1) {
							$conList .= 'option_' . $condOnID . ' = ' . $theOptionArray[0] . ' ' . $opertionRelation;
						}
						else {
							$conList .= 'option_' . $condOnID . ' != ' . $theOptionArray[0] . ' ' . $opertionRelation;
						}
					}
					else if ($theOptionArray[1] == 1) {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . ') ' . $opertionRelation;
					}
					else {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . ')=0 ' . $opertionRelation;
					}

					break;

				case '30':
					if ($theOptionArray[1] == 1) {
						if ($theOptionArray[0] == 1) {
							$conList .= 'option_' . $condOnID . '="1" ' . $opertionRelation;
						}
						else {
							$conList .= 'option_' . $condOnID . '="0" ' . $opertionRelation;
						}
					}
					else if ($theOptionArray[0] == 1) {
						$conList .= 'option_' . $condOnID . '="0" ' . $opertionRelation;
					}
					else {
						$conList .= 'option_' . $condOnID . '="1" ' . $opertionRelation;
					}

					break;
				}
			}
		}

		if (2 <= $Con_Total) {
			$conList .= ')';
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
		$RangeTotal = count($theCondOnIDArray);
		$k = 0;

		if (2 <= $RangeTotal) {
			$conList .= '(';
		}

		foreach ($theCondOnIDArray as $qtnID => $theRangeArray) {
			$i = 0;
			$Con_Total = count($theRangeArray);

			if (2 <= $Con_Total) {
				$conList .= '(';
			}

			foreach ($theRangeArray as $theOptionArray) {
				$i++;

				switch ($condOnID_QuestionType) {
				case '23':
					switch ($theOptionArray[1]) {
					case 1:
						$opertion = '=';
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

					if ($i == $Con_Total) {
						$opertionRelation = '';
					}
					else {
						$opertionRelation = ' AND ';
					}

					$conList .= '(option_' . $condOnID . '_' . $qtnID . ' != \'\' AND option_' . $condOnID . '_' . $qtnID . $opertion . $theOptionArray[0] . ') ' . $opertionRelation;
					break;

				case '10':
				case '16':
				case '20':
				case '22':
					switch ($theOptionArray[1]) {
					case 1:
						$opertion = '=';
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

					if ($i == $Con_Total) {
						$opertionRelation = '';
					}
					else {
						$opertionRelation = ' AND ';
					}

					$conList .= '(option_' . $condOnID . '_' . $qtnID . ' != \'0\' AND option_' . $condOnID . '_' . $qtnID . $opertion . $theOptionArray[0] . ') ' . $opertionRelation;
					break;

				case '15':
				case '21':
					switch ($theOptionArray[1]) {
					case 1:
						$opertion = '=';
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

					if ($i == $Con_Total) {
						$opertionRelation = '';
					}
					else {
						$opertionRelation = ' AND ';
					}

					switch ($QtnListArray[$condOnID]['isSelect']) {
					case '0':
						$conList .= '(option_' . $condOnID . '_' . $qtnID . ' != \'0\' AND option_' . $condOnID . '_' . $qtnID . ' != \'99\' AND option_' . $condOnID . '_' . $qtnID . ' * ' . $QtnListArray[$condOnID]['weight'] . $opertion . $theOptionArray[0] . ') ' . $opertionRelation;
						break;

					case '1':
					case '2':
						$conList .= '(option_' . $condOnID . '_' . $qtnID . ' != \'0\' AND option_' . $condOnID . '_' . $qtnID . $opertion . $theOptionArray[0] . ') ' . $opertionRelation;
						break;
					}

					break;

				case '6':
				case '19':
				case '31':
					if ($i == $Con_Total) {
						$opertionRelation = '';
					}
					else if ($theOptionArray[2] == 1) {
						$opertionRelation = ' AND ';
					}
					else {
						$opertionRelation = ' OR ';
					}

					if ($theOptionArray[1] == 1) {
						$conList .= 'option_' . $condOnID . '_' . $qtnID . ' = ' . $theOptionArray[0] . ' ' . $opertionRelation;
					}
					else {
						$conList .= 'option_' . $condOnID . '_' . $qtnID . ' != ' . $theOptionArray[0] . ' ' . $opertionRelation;
					}

					break;

				case '7':
				case '28':
					if ($i == $Con_Total) {
						$opertionRelation = '';
					}
					else if ($theOptionArray[2] == 1) {
						$opertionRelation = ' AND ';
					}
					else {
						$opertionRelation = ' OR ';
					}

					if ($theOptionArray[1] == 1) {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . '_' . $qtnID . ') ' . $opertionRelation;
					}
					else {
						$conList .= 'FIND_IN_SET(' . $theOptionArray[0] . ',option_' . $condOnID . '_' . $qtnID . ')=0 ' . $opertionRelation;
					}

					break;
				}
			}

			if (2 <= $Con_Total) {
				$conList .= ')';
			}

			$k++;

			if ($k != $RangeTotal) {
				$conList .= ' AND ';
			}
		}

		if (2 <= $RangeTotal) {
			$conList .= ')';
		}

		break;
	}

	$j++;

	if ($j != count($QuotaListArray[$theQuotaID])) {
		$conList .= ' AND ';
	}
}

?>
