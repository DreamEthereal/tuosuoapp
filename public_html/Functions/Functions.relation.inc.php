<?php
//dezend by http://www.yunlu99.com/
function _getsurveyvaluerelationcond($questionID, $surveyID, $surveyLang = 'cn')
{
	global $DB;
	global $Module;
	global $ValueRelArray;
	global $lang;
	global $QtnListArray;
	global $YesNoListArray;
	global $RankListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	$theQtnRelArray = array();
	$theFocusObjId = array();

	foreach ($ValueRelArray as $relationID => $theQtnRelArray) {
		if ($theQtnRelArray['relationMode'] == 1) {
			$theMaxArray = array();

			foreach ($theQtnRelArray['list'] as $listID => $theListArray) {
				$theMaxArray[$listID] = $QtnListArray[$theListArray['questionID']]['orderByID'];
			}

			$theLastListID = array_search(max($theMaxArray), $theMaxArray);
			unset($theMaxArray);
			$theLastQtnID = $theQtnRelArray['list'][$theLastListID]['questionID'];

			if ($theLastQtnID == $questionID) {
				$theQtnValueRelArray[] = $relationID;
				$theFocusObjId[$relationID] = $questionID;
			}
			else {
				continue;
			}
		}
		else if ($theQtnRelArray['questionID'] == $questionID) {
			$theQtnValueRelArray[] = $relationID;
			$theFocusObjId[$relationID] = $questionID;
		}
		else {
			continue;
		}
	}

	if (empty($theQtnValueRelArray)) {
		return '';
	}

	$endConList = '';

	foreach ($theQtnValueRelArray as $theRelID) {
		$endConList .= $ValueRelArray[$theRelID]['relationDefine'] . '######';

		if ($ValueRelArray[$theRelID]['relationDefine'] == 2) {
			$endConList .= '	emptyCheckStr += \'s1*' . $ValueRelArray[$theRelID]['opertion'];
		}
		else {
			$endConList .= '		thisCheckStr += \'s1*' . $ValueRelArray[$theRelID]['opertion'];
		}

		switch ($ValueRelArray[$theRelID]['opertion']) {
		case 1:
			$theOpertionText = ' = ';
			break;

		case 2:
			$theOpertionText = ' < ';
			break;

		case 3:
			$theOpertionText = ' <= ';
			break;

		case 4:
			$theOpertionText = ' > ';
			break;

		case 5:
			$theOpertionText = ' >= ';
			break;

		case 6:
			$theOpertionText = ' != ';
			break;
		}

		$theMainFieldsList = '';

		if ($ValueRelArray[$theRelID]['relationMode'] == 1) {
			$endConList .= '*' . $ValueRelArray[$theRelID]['relationNum'];
			$theOpertionText .= $ValueRelArray[$theRelID]['relationNum'] . ' ';
		}
		else {
			$endConList .= '*m2';
			$theQtnId = $ValueRelArray[$theRelID]['questionID'];
			$theSubQtnId = $ValueRelArray[$theRelID]['qtnID'];
			$theOptionId = $ValueRelArray[$theRelID]['optionID'];
			$theLabelId = $ValueRelArray[$theRelID]['labelID'];

			switch ($QtnListArray[$theQtnId]['questionType']) {
			case 1:
			case 24:
				$theMainFieldsList .= '1-option_' . $theQtnId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ') ';
				break;

			case 2:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$theMainFieldsList .= '3-option_' . $theQtnId . '$';
				}
				else {
					$theMainFieldsList .= '1-option_' . $theQtnId . '$';
				}

				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ') ';
				break;

			case 3:
			case 25:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$theMainFieldsList .= '3-option_' . $theQtnId . '-' . $theOptionId . '$';
				}
				else {
					$theMainFieldsList .= '2-option_' . $theQtnId . '-' . $theOptionId . '$';
				}

				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';

				switch ($theOptionId) {
				case '0':
					$optionName = $QtnListArray[$theQtnId]['otherText'];
					break;

				case '99999':
					$optionName = ($QtnListArray[$theQtnId]['allowType'] == '' ? $lang['neg_text'] : $QtnListArray[$theQtnId]['allowType']);
					break;

				default:
					$optionName = $CheckBoxListArray[$theQtnId][$theOptionId]['optionName'];
					break;
				}

				$theOpertionText .= $optionName . ') ';
				break;

			case 4:
				$theMainFieldsList .= '3-option_' . $theQtnId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ') ';
				break;

			case 17:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$theMainFieldsList .= '1-option_' . $theQtnId . '$';
					$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ') ';
				}
				else {
					$theMainFieldsList .= '2-option_' . $theQtnId . '-' . $theOptionId . '$';
					$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
					$theBaseID = $QtnListArray[$theQtnId]['baseID'];

					switch ($theOptionId) {
					case '0':
						$optionName = $QtnListArray[$theBaseID]['otherText'];
						break;

					case '99999':
						$optionName = ($QtnListArray[$theQtnId]['allowType'] == '' ? $lang['neg_text'] : $QtnListArray[$theQtnId]['allowType']);
						break;

					default:
						$optionName = $CheckBoxListArray[$theBaseID][$theOptionId]['optionName'];
						break;
					}

					$theOpertionText .= $optionName . ') ';
				}

				break;

			case 18:
				if ($QtnListArray[$theQtnId]['isSelect'] == 1) {
					$theMainFieldsList .= '3-option_' . $theQtnId . '-' . $theOptionId . '$';
					$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
					$theOpertionText .= $YesNoListArray[$theQtnId][$theOptionId]['optionName'] . ')';
				}
				else {
					$theMainFieldsList .= '3-option_' . $theQtnId . '$';
					$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ') ';
				}

				break;

			case 6:
				$theMainFieldsList .= '1-option_' . $theQtnId . '_' . $theSubQtnId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ' . $OptionListArray[$theQtnId][$theSubQtnId]['optionName'] . ') ';
				break;

			case 7:
				$theMainFieldsList .= '2-option_' . $theQtnId . '_' . $theSubQtnId . '-' . $theOptionId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ' . $OptionListArray[$theQtnId][$theSubQtnId]['optionName'] . ' - ' . $AnswerListArray[$theQtnId][$theOptionId]['optionAnswer'] . ') ';
				break;

			case 19:
			case 21:
			case 22:
				switch ($QtnListArray[$theQtnId]['questionType']) {
				case 19:
					$theMainFieldsList .= '1-option_' . $theQtnId . '_' . $theSubQtnId . '$';
					break;

				case 21:
					switch ($QtnListArray[$theQtnId]['isSelect']) {
					case '0':
						$theMainFieldsList .= '1-option_' . $theQtnId . '_' . $theSubQtnId . '$';
						break;

					case '1':
					case '2':
						$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theSubQtnId . '$';
						break;
					}

					break;

				case 22:
					$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theSubQtnId . '$';
					break;
				}

				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theBaseID = $QtnListArray[$theQtnId]['baseID'];

				switch ($theSubQtnId) {
				case '0':
					$optionName = $QtnListArray[$theBaseID]['otherText'];
					break;

				default:
					$optionName = $CheckBoxListArray[$theBaseID][$theSubQtnId]['optionName'];
					break;
				}

				$theOpertionText .= $optionName . ') ';
				break;

			case 15:
				switch ($QtnListArray[$theQtnId]['isSelect']) {
				case '0':
					$theMainFieldsList .= '1-option_' . $theQtnId . '_' . $theOptionId . '$';
					break;

				case '1':
				case '2':
					$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theOptionId . '$';
					break;
				}

				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theOpertionText .= $RankListArray[$theQtnId][$theOptionId]['optionName'] . ') ';
				break;

			case 16:
				$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theOptionId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theOpertionText .= $RankListArray[$theQtnId][$theOptionId]['optionName'] . ') ';
				break;

			case 23:
				$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theOptionId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theOpertionText .= $YesNoListArray[$theQtnId][$theOptionId]['optionName'] . ')';
				break;

			case 26:
				$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theSubQtnId . '_' . $theOptionId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theOpertionText .= $OptionListArray[$theQtnId][$theSubQtnId]['optionName'] . ' - ';
				$theOpertionText .= $LabelListArray[$theQtnId][$theOptionId]['optionLabel'] . ') ';
				break;

			case 27:
				$theMainFieldsList .= '3-option_' . $theQtnId . '_' . $theOptionId . '_' . $theLabelId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theOpertionText .= $OptionListArray[$theQtnId][$theOptionId]['optionName'] . ' - ';
				$theOpertionText .= $LabelListArray[$theQtnId][$theLabelId]['optionLabel'] . ') ';
				break;

			case 28:
				$theMainFieldsList .= '2-option_' . $theQtnId . '_' . $theSubQtnId . '-' . $theOptionId . '$';
				$theOpertionText .= '(' . $QtnListArray[$theQtnId]['questionName'] . ' - ';
				$theBaseID = $QtnListArray[$theQtnId]['baseID'];

				switch ($theSubQtnId) {
				case '0':
					$theOpertionText .= $QtnListArray[$theBaseID]['otherText'] . ' - ';
					break;

				default:
					$theOpertionText .= $CheckBoxListArray[$theBaseID][$theSubQtnId]['optionName'] . ' - ';
					break;
				}

				$theOpertionText .= $AnswerListArray[$theQtnId][$theOptionId]['optionAnswer'] . ') ';
				break;
			}
		}

		$theFieldsList = '';
		$theOpertionsList = '';
		$theTitleList = '';
		$j = 0;

		if (2 <= count($ValueRelArray[$theRelID]['list'])) {
			$theTitleList .= '(';
		}

		foreach ($ValueRelArray[$theRelID]['list'] as $listID => $theValueRelArray) {
			if ($j != 0) {
				$theOpertionsList .= $theValueRelArray['opertion'] . '$';

				switch ($theValueRelArray['opertion']) {
				case 1:
					$theTitleList .= ' + ';
					break;

				case 2:
					$theTitleList .= ' - ';
					break;

				case 3:
					$theTitleList .= ' * ';
					break;

				case 4:
					$theTitleList .= ' / ';
					break;
				}
			}

			$theOQtnId = $theValueRelArray['questionID'];
			$theOSubQtnId = $theValueRelArray['qtnID'];
			$theOOptionId = $theValueRelArray['optionID'];
			$theOLabelId = $theValueRelArray['labelID'];

			switch ($QtnListArray[$theOQtnId]['questionType']) {
			case 1:
			case 24:
				if (issamepage($questionID, $theOQtnId)) {
					$theFieldsList .= '1-option_' . $theOQtnId . '$';
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ') ';
				break;

			case 2:
				if (issamepage($questionID, $theOQtnId)) {
					if ($QtnListArray[$theOQtnId]['isSelect'] == 1) {
						$theFieldsList .= '3-option_' . $theOQtnId . '$';
					}
					else {
						$theFieldsList .= '1-option_' . $theOQtnId . '$';
					}
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ') ';
				break;

			case 3:
			case 25:
				if (issamepage($questionID, $theOQtnId)) {
					if ($QtnListArray[$theOQtnId]['isSelect'] == 1) {
						$theFieldsList .= '3-option_' . $theOQtnId . '-' . $theOOptionId . '$';
					}
					else {
						$theFieldsList .= '2-option_' . $theOQtnId . '-' . $theOOptionId . '$';
					}
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '-' . $theOOptionId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';

				switch ($theOOptionId) {
				case '0':
					$optionName = $QtnListArray[$theOQtnId]['otherText'];
					break;

				case '99999':
					$optionName = ($QtnListArray[$theOQtnId]['allowType'] == '' ? $lang['neg_text'] : $QtnListArray[$theOQtnId]['allowType']);
					break;

				default:
					$optionName = $CheckBoxListArray[$theOQtnId][$theOOptionId]['optionName'];
					break;
				}

				$theTitleList .= $optionName . ') ';
				break;

			case 4:
				$theFieldsList .= '3-option_' . $theOQtnId . '$';
				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ') ';
				break;

			case 17:
				if ($QtnListArray[$theOQtnId]['isSelect'] == 1) {
					if (issamepage($questionID, $theOQtnId)) {
						$theFieldsList .= '1-option_' . $theOQtnId . '$';
					}
					else {
						$theFieldsList .= '3-option_' . $theOQtnId . '$';
					}

					$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ') ';
				}
				else {
					if (issamepage($questionID, $theOQtnId)) {
						$theFieldsList .= '2-option_' . $theOQtnId . '-' . $theOOptionId . '$';
					}
					else {
						$theFieldsList .= '3-option_' . $theOQtnId . '-' . $theOOptionId . '$';
					}

					$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
					$theBaseID = $QtnListArray[$theOQtnId]['baseID'];

					switch ($theOOptionId) {
					case '0':
						$optionName = $QtnListArray[$theBaseID]['otherText'];
						break;

					case '99999':
						$optionName = ($QtnListArray[$theOQtnId]['allowType'] == '' ? $lang['neg_text'] : $QtnListArray[$theOQtnId]['allowType']);
						break;

					default:
						$optionName = $CheckBoxListArray[$theBaseID][$theOOptionId]['optionName'];
						break;
					}

					$theTitleList .= $optionName . ') ';
				}

				break;

			case 18:
				if ($QtnListArray[$theOQtnId]['isSelect'] == 1) {
					$theFieldsList .= '3-option_' . $theOQtnId . '-' . $theOOptionId . '$';
					$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
					$theTitleList .= $YesNoListArray[$theOQtnId][$theOOptionId]['optionName'] . ')';
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '$';
					$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ') ';
				}

				break;

			case 6:
				if (issamepage($questionID, $theOQtnId)) {
					$theFieldsList .= '1-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ' . $OptionListArray[$theOQtnId][$theOSubQtnId]['optionName'] . ') ';
				break;

			case 7:
				if (issamepage($questionID, $theOQtnId)) {
					$theFieldsList .= '2-option_' . $theOQtnId . '_' . $theOSubQtnId . '-' . $theOOptionId . '$';
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '-' . $theOOptionId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ' . $OptionListArray[$theOQtnId][$theOSubQtnId]['optionName'] . ' - ' . $AnswerListArray[$theOQtnId][$theOOptionId]['optionAnswer'] . ') ';
				break;

			case 19:
			case 21:
			case 22:
				switch ($QtnListArray[$theOQtnId]['questionType']) {
				case 19:
					if (issamepage($questionID, $theOQtnId)) {
						$theFieldsList .= '1-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
					}
					else {
						$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
					}

					break;

				case 21:
					switch ($QtnListArray[$theOQtnId]['isSelect']) {
					case '0':
						if (issamepage($questionID, $theOQtnId)) {
							$theFieldsList .= '1-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
						}
						else {
							$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
						}

						break;

					case '1':
					case '2':
						$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
						break;
					}

					break;

				case 22:
					$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '$';
					break;
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theBaseID = $QtnListArray[$theOQtnId]['baseID'];

				switch ($theOSubQtnId) {
				case '0':
					$optionName = $QtnListArray[$theBaseID]['otherText'];
					break;

				default:
					$optionName = $CheckBoxListArray[$theBaseID][$theOSubQtnId]['optionName'];
					break;
				}

				$theTitleList .= $optionName . ') ';
				break;

			case 15:
				switch ($QtnListArray[$theOQtnId]['isSelect']) {
				case '0':
					if (issamepage($questionID, $theOQtnId)) {
						$theFieldsList .= '1-option_' . $theOQtnId . '_' . $theOOptionId . '$';
					}
					else {
						$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOOptionId . '$';
					}

					break;

				case '1':
				case '2':
					$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOOptionId . '$';
					break;
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theTitleList .= $RankListArray[$theOQtnId][$theOOptionId]['optionName'] . ') ';
				break;

			case 16:
				$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOOptionId . '$';
				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theTitleList .= $RankListArray[$theOQtnId][$theOOptionId]['optionName'] . ') ';
				break;

			case 23:
				$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOOptionId . '$';
				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theTitleList .= $YesNoListArray[$theOQtnId][$theOOptionId]['optionName'] . ')';
				break;

			case 26:
				$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '_' . $theOOptionId . '$';
				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theTitleList .= $OptionListArray[$theOQtnId][$theOSubQtnId]['optionName'] . ' - ';
				$theTitleList .= $LabelListArray[$theOQtnId][$theOOptionId]['optionLabel'] . ') ';
				break;

			case 28:
				if (issamepage($questionID, $theOQtnId)) {
					$theFieldsList .= '2-option_' . $theOQtnId . '_' . $theOSubQtnId . '-' . $theOOptionId . '$';
				}
				else {
					$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOSubQtnId . '-' . $theOOptionId . '$';
				}

				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theBaseID = $QtnListArray[$theOQtnId]['baseID'];

				switch ($theOSubQtnId) {
				case '0':
					$theTitleList .= $QtnListArray[$theBaseID]['otherText'] . ' - ';
					break;

				default:
					$theTitleList .= $CheckBoxListArray[$theBaseID][$theOSubQtnId]['optionName'] . ' - ';
					break;
				}

				$theTitleList .= $AnswerListArray[$theOQtnId][$theOOptionId]['optionAnswer'] . ') ';
				break;

			case 27:
				$theFieldsList .= '3-option_' . $theOQtnId . '_' . $theOOptionId . '_' . $theOLabelId . '$';
				$theTitleList .= '(' . $QtnListArray[$theOQtnId]['questionName'] . ' - ';
				$theTitleList .= $OptionListArray[$theOQtnId][$theOOptionId]['optionName'] . ' - ';
				$theTitleList .= $LabelListArray[$theOQtnId][$theOLabelId]['optionLabel'] . ') ';
				break;
			}

			$j++;
		}

		if (2 <= count($ValueRelArray[$theRelID]['list'])) {
			$theTitleList .= ')';
		}

		$theTitleList .= $theOpertionText;
		$endConList .= '*' . substr($theMainFieldsList . $theFieldsList, 0, -1) . '*' . substr($theOpertionsList, 0, -1) . '*' . $theFocusObjId[$theRelID] . '*' . str_replace('+', '%2B', base64_encode($theTitleList));

		if ($ValueRelArray[$theRelID]['relationDefine'] == 2) {
			$endConList .= '*' . str_replace('+', '%2B', base64_encode((int) $ValueRelArray[$theRelID]['defineList']));
		}

		$endConList .= '|\';' . "\n" . '$$$$$$';
	}

	return substr($endConList, 0, -6);
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
