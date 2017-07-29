<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$totalValue = 0;
$surveyIndex = array();
$surveyNA = array();
$surveyNaNum = array();

foreach ($QtnList as $indexID => $theQtnIdList) {
	$surveyIndex[$indexID] = 0;
	$surveyNA[$indexID] = 0;
	$surveyNaNum[$indexID] = 0;

	foreach ($theQtnIdList as $questionID) {
		switch ($QtnListArray[$questionID]['questionType']) {
		case '1':
			if ($R_Row['option_' . $questionID] == 0) {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
				$theQtnValue = 0;
				$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
				$surveyNaNum[$indexID] += 1;
			}
			else {
				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
					break;

				case '2':
					$theQtnValue = $YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '2':
			$isSkip = false;

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				if (($R_Row['option_' . $questionID] == 0) && ($R_Row['TextOtherValue_' . $questionID] == '')) {
					$isSkip = true;
				}
			}
			else if ($R_Row['option_' . $questionID] == 0) {
				$isSkip = true;
			}

			if ($isSkip == true) {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($R_Row['option_' . $questionID] != 0) {
				if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
						break;

					case '2':
						$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
			}
			else if ($QtnListArray[$questionID]['isNA'] == 1) {
				$theQtnValue = 0;
				$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
				$surveyNaNum[$indexID] += 1;
			}
			else {
				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $QtnListArray[$questionID]['optionCoeff'];
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '3':
			$isSkip = false;

			if ($QtnListArray[$questionID]['isHaveOther'] == '1') {
				if (($R_Row['option_' . $questionID] == '') && ($R_Row['TextOtherValue_' . $questionID] == '')) {
					$isSkip = true;
				}
			}
			else if ($R_Row['option_' . $questionID] == '') {
				$isSkip = true;
			}

			if ($isSkip == true) {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($R_Row['option_' . $questionID] == '99999') {
				if ($QtnListArray[$questionID]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
			}
			else {
				$thisQtnValue = 0;
				$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

				foreach ($theSurveyFields as $surveyQtnFields) {
					switch ($surveyQtnFields) {
					case '0':
						$thisQtnValue += $QtnListArray[$questionID]['optionCoeff'];
						break;

					default:
						$thisQtnValue += $CheckBoxListArray[$questionID][$surveyQtnFields]['optionCoeff'];
						break;
					}
				}

				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $thisQtnValue;
					break;

				case '2':
					$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '4':
			if (($R_Row['option_' . $questionID] == '') || ($R_Row['option_' . $questionID] == 0)) {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else {
				$theQtnValue = $R_Row['option_' . $questionID];
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '6':
			$tmpNum = 0;

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID;

				if ($R_Row[$theOptionID] == 0) {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$tmpNum++;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
						break;

					case '2':
						$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($OptionListArray[$questionID])) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '7':
			$tmpNum = 0;
			$theTmpArray = $AnswerListArray[$questionID];
			$theLastAnswerArray = array_pop($theTmpArray);
			$theLastAnswerId = $theLastAnswerArray['question_range_answerID'];
			unset($theLastAnswerArray);

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID;

				if ($R_Row[$theOptionID] == '') {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else if ($R_Row[$theOptionID] == $theLastAnswerId) {
					if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
					}
					else {
						switch ($QtnListArray[$questionID]['coeffMode']) {
						case '1':
						default:
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
							break;

						case '2':
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
				}
				else {
					$theSurveyFields = explode(',', $R_Row[$theOptionID]);
					$thisQtnValue = 0;

					foreach ($theSurveyFields as $surveyQtnFields) {
						$thisQtnValue += $AnswerListArray[$questionID][$surveyQtnFields]['optionCoeff'];
					}

					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $thisQtnValue;
						break;

					case '2':
						$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($OptionListArray[$questionID])) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '15':
			$tmpNum = 0;

			foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
				$isSkip = false;
				$theOptionID = 'option_' . $questionID . '_' . $question_rankID;

				switch ($QtnListArray[$questionID]['isSelect']) {
				case '0':
				case '2':
					if ($R_Row[$theOptionID] == 0) {
						$isSkip = true;
					}

					break;

				case '1':
					if (($R_Row[$theOptionID] == 0) || ($R_Row[$theOptionID] == '0.00')) {
						$isSkip = true;
					}

					break;
				}

				if ($isSkip == true) {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else {
					if (($QtnListArray[$questionID]['isSelect'] == 0) && ($R_Row[$theOptionID] == 99)) {
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
					}
					else {
						$theQtnValue = $QtnListArray[$questionID]['weight'] * $R_Row[$theOptionID];
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($RankListArray[$questionID])) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '17':
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

			if ($R_Row['option_' . $questionID] == '') {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($QtnListArray[$questionID]['isSelect'] == 1) {
				if ($R_Row['option_' . $questionID] == '99999') {
					if ($QtnListArray[$questionID]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$surveyNaNum[$indexID] += 1;
					}
					else {
						switch ($QtnListArray[$questionID]['coeffMode']) {
						case '1':
						default:
							$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
							break;

						case '2':
							$theQtnValue = $QtnListArray[$questionID]['negCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						switch ($R_Row['option_' . $questionID]) {
						case '0':
							$theQtnValue = $theBaseQtnArray['optionCoeff'];
							break;

						default:
							$theQtnValue = $theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionCoeff'];
							break;
						}

						break;

					case '2':
						switch ($R_Row['option_' . $questionID]) {
						case '0':
							$theQtnValue = $theBaseQtnArray['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;

						default:
							$theQtnValue = $theCheckBoxListArray[$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}

						break;
					}
				}
			}
			else if ($R_Row['option_' . $questionID] == '99999') {
				if ($QtnListArray[$questionID]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
			}
			else {
				$thisQtnValue = 0;
				$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

				foreach ($theSurveyFields as $surveyQtnFields) {
					switch ($surveyQtnFields) {
					case '0':
						$thisQtnValue += $theBaseQtnArray['optionCoeff'];
						break;

					default:
						$thisQtnValue += $theCheckBoxListArray[$surveyQtnFields]['optionCoeff'];
						break;
					}
				}

				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $thisQtnValue;
					break;

				case '2':
					$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '18':
			if ($R_Row['option_' . $questionID] == '') {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($QtnListArray[$questionID]['isSelect'] == 0) {
				if ($YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
						break;

					case '2':
						$theQtnValue = $YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
			}
			else {
				$thisQtnValue = 0;
				$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

				foreach ($theSurveyFields as $surveyQtnFields) {
					$thisQtnValue += $YesNoListArray[$questionID][$surveyQtnFields]['optionCoeff'];
				}

				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $thisQtnValue;
					break;

				case '2':
					$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '19':
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$tmpNum = 0;

			foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
				$theOptionID = 'option_' . $questionID . '_' . $optionAutoID;

				if ($R_Row[$theOptionID] == 0) {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$tmpNum++;
				}
				else {
					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
						break;

					case '2':
						$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($optionAutoArray)) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '21':
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$tmpNum = 0;

			foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
				$isSkip = false;
				$theOptionID = 'option_' . $questionID . '_' . $optionAutoID;

				switch ($QtnListArray[$questionID]['isSelect']) {
				case '0':
				case '2':
					if ($R_Row[$theOptionID] == 0) {
						$isSkip = true;
					}

					break;

				case '1':
					if (($R_Row[$theOptionID] == 0) || ($R_Row[$theOptionID] == '0.00')) {
						$isSkip = true;
					}

					break;
				}

				if ($isSkip == true) {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else {
					if (($QtnListArray[$questionID]['isSelect'] == 0) && ($R_Row[$theOptionID] == 99)) {
						$theQtnValue = $QtnListArray[$questionID]['negCoeff'];
					}
					else {
						$theQtnValue = $QtnListArray[$questionID]['weight'] * $R_Row[$theOptionID];
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($optionAutoArray)) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '24':
			if ($R_Row['option_' . $questionID] == 0) {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
				$theQtnValue = 0;
				$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
				$surveyNaNum[$indexID] += 1;
			}
			else {
				switch ($QtnListArray[$questionID]['coeffMode']) {
				case '1':
				default:
					$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
					break;

				case '2':
					$theQtnValue = $RadioListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
					break;
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '25':
			if ($R_Row['option_' . $questionID] == '') {
				switch ($QtnListArray[$questionID]['skipMode']) {
				case '1':
				default:
					$theQtnValue = 0;
					break;

				case '2':
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					break;

				case '3':
					$theQtnValue = 0;
					$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
					$surveyNaNum[$indexID] += 1;
					break;
				}
			}
			else {
				$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

				if (count($theSurveyFields) == 1) {
					if ($CheckBoxListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$surveyNaNum[$indexID] += 1;
					}
					else {
						switch ($QtnListArray[$questionID]['coeffMode']) {
						case '1':
						default:
							$theQtnValue = $CheckBoxListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'];
							break;

						case '2':
							$theQtnValue = $CheckBoxListArray[$questionID][$R_Row['option_' . $questionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
				}
				else {
					$thisQtnValue = 0;

					foreach ($theSurveyFields as $surveyQtnFields) {
						$thisQtnValue += $CheckBoxListArray[$questionID][$surveyQtnFields]['optionCoeff'];
					}

					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $thisQtnValue;
						break;

					case '2':
						$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
			}

			if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
				$theQtnValue = 0;
			}

			if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
				$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
			}

			$surveyIndex[$indexID] += $theQtnValue;
			break;

		case '26':
			$tmpNum = 0;

			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
					$theOptionID = 'option_' . $questionID . '_' . $question_range_optionID . '_' . $question_range_labelID;

					if ($R_Row[$theOptionID] == 0) {
						switch ($QtnListArray[$questionID]['skipMode']) {
						case '1':
						default:
							$theQtnValue = 0;
							break;

						case '2':
							$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
							break;

						case '3':
							$theQtnValue = 0;
							$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
							$tmpNum++;
							break;
						}
					}
					else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
					}
					else {
						switch ($QtnListArray[$questionID]['coeffMode']) {
						case '1':
						default:
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
							break;

						case '2':
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}

					if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
						$theQtnValue = 0;
					}

					if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
					}

					$surveyIndex[$indexID] += $theQtnValue;
				}
			}

			if ($tmpNum == count($OptionListArray[$questionID]) * count($LabelListArray[$questionID])) {
				$surveyNaNum[$indexID] += 1;
			}

			break;

		case '28':
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$tmpNum = 0;
			$theTmpArray = $AnswerListArray[$questionID];
			$theLastAnswerArray = array_pop($theTmpArray);
			$theLastAnswerId = $theLastAnswerArray['question_range_answerID'];
			unset($theLastAnswerArray);

			foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
				$theOptionID = 'option_' . $questionID . '_' . $optionAutoID;

				if ($R_Row[$theOptionID] == '') {
					switch ($QtnListArray[$questionID]['skipMode']) {
					case '1':
					default:
						$theQtnValue = 0;
						break;

					case '2':
						$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
						break;

					case '3':
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
						break;
					}
				}
				else if ($R_Row[$theOptionID] == $theLastAnswerId) {
					if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA[$indexID] += $QtnListArray[$questionID]['coeffTotal'];
						$tmpNum++;
					}
					else {
						switch ($QtnListArray[$questionID]['coeffMode']) {
						case '1':
						default:
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'];
							break;

						case '2':
							$theQtnValue = $AnswerListArray[$questionID][$R_Row[$theOptionID]]['optionCoeff'] + $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
				}
				else {
					$theSurveyFields = explode(',', $R_Row[$theOptionID]);
					$thisQtnValue = 0;

					foreach ($theSurveyFields as $surveyQtnFields) {
						$thisQtnValue += $AnswerListArray[$questionID][$surveyQtnFields]['optionCoeff'];
					}

					switch ($QtnListArray[$questionID]['coeffMode']) {
					case '1':
					default:
						$theQtnValue = $thisQtnValue;
						break;

					case '2':
						$theQtnValue = $thisQtnValue + $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}

				if (($QtnListArray[$questionID]['coeffZeroMargin'] == 1) && ($theQtnValue < 0)) {
					$theQtnValue = 0;
				}

				if (($QtnListArray[$questionID]['coeffFullMargin'] == 1) && ($QtnListArray[$questionID]['coeffTotal'] < $theQtnValue)) {
					$theQtnValue = $QtnListArray[$questionID]['coeffTotal'];
				}

				$surveyIndex[$indexID] += $theQtnValue;
			}

			if ($tmpNum == count($optionAutoArray)) {
				$surveyNaNum[$indexID] += 1;
			}

			break;
		}
	}
}

switch ($Sur_G_Row['isRateIndex']) {
case 1:
	$surveyIndexNaArray = array();
	$surveyIndexNA = array();
	$newSurveyIndex = array();

	foreach ($theIndexFather as $indexID => $theFatherID) {
		$theIndexValue = (isset($surveyIndex[$indexID]) ? $surveyIndex[$indexID] : 0);

		if ($surveyNA[$indexID] != 0) {
			$surveyIndexNA[$theFatherID] += $surveyNA[$indexID];

			if ($surveyNaNum[$indexID] == count($QtnList[$indexID])) {
				$surveyIndexNaArray[$theFatherID][] = $indexID;
				$theIndexValue = '-999';
				$newSurveyIndex[$indexID] = '-999';
			}
			else if (($fullValue[$indexID] - $surveyNA[$indexID]) == 0) {
				$theIndexValue = 0;
				$newSurveyIndex[$indexID] = 0;
			}
			else {
				$newSurveyIndex[$indexID] = $theIndexValue;
				if (($isMinZero[$indexID] == 1) && ($theIndexValue < 0)) {
					$theIndexValue = 0;
				}

				if (($isMaxFull[$indexID] == 1) && ($fullValue[$indexID] < $theIndexValue)) {
					$theIndexValue = $fullValue[$indexID];
				}

				$theIndexValue = ($theIndexValue / ($fullValue[$indexID] - $surveyNA[$indexID])) * 100;
			}
		}
		else {
			$newSurveyIndex[$indexID] = $theIndexValue;
			if (($isMinZero[$indexID] == 1) && ($theIndexValue < 0)) {
				$theIndexValue = 0;
			}

			if (($isMaxFull[$indexID] == 1) && ($fullValue[$indexID] < $theIndexValue)) {
				$theIndexValue = $fullValue[$indexID];
			}

			$theIndexValue = ($theIndexValue / $fullValue[$indexID]) * 100;
		}

		if ($isRecResult == 1) {
			$SQL = ' INSERT INTO ' . SURVEYINDEXRESULT_TABLE . ' SET surveyID = \'' . $Sur_G_Row['surveyID'] . '\',responseID = \'' . $R_Row['responseID'] . '\',taskID = \'' . $R_Row['taskID'] . '\',indexID = \'' . $indexID . '\',indexValue = \'' . $theIndexValue . '\' ';
			$DB->query($SQL);
		}
	}

	$totalIndexValue = 0;
	$surveyOneTierNA = 0;
	$surveyIndexValue = array();

	foreach ($theOneTierIndex as $theOneTierIndexID) {
		$totalIndexValue += $fullValue[$theOneTierIndexID];
		$theIndexValue = 0;

		foreach ($fatherId[$theOneTierIndexID] as $theTwoTierIndexID) {
			if ($newSurveyIndex[$theTwoTierIndexID] != '-999') {
				$theIndexValue += $newSurveyIndex[$theTwoTierIndexID];
			}
		}

		if (isset($surveyIndexNA[$theOneTierIndexID]) && ($surveyIndexNA[$theOneTierIndexID] != 0)) {
			$surveyOneTierNA += $surveyIndexNA[$theOneTierIndexID];

			if (count($surveyIndexNaArray[$theOneTierIndexID]) == count($fatherId[$theOneTierIndexID])) {
				$theIndexValue = '-999';
			}
			else if (($fullValue[$theOneTierIndexID] - $surveyIndexNA[$theOneTierIndexID]) == 0) {
				$theIndexValue = 0;
			}
			else {
				$totalValue += $theIndexValue;
				if (($isMinZero[$theOneTierIndexID] == 1) && ($theIndexValue < 0) && ($theIndexValue != '-999')) {
					$theIndexValue = 0;
				}

				if (($isMaxFull[$theOneTierIndexID] == 1) && ($fullValue[$theOneTierIndexID] < $theIndexValue)) {
					$theIndexValue = $fullValue[$theOneTierIndexID];
				}

				$theIndexValue = ($theIndexValue / ($fullValue[$theOneTierIndexID] - $surveyIndexNA[$theOneTierIndexID])) * 100;
			}
		}
		else {
			$totalValue += $theIndexValue;
			if (($isMinZero[$theOneTierIndexID] == 1) && ($theIndexValue < 0) && ($theIndexValue != '-999')) {
				$theIndexValue = 0;
			}

			if (($isMaxFull[$theOneTierIndexID] == 1) && ($fullValue[$theOneTierIndexID] < $theIndexValue)) {
				$theIndexValue = $fullValue[$theOneTierIndexID];
			}

			$theIndexValue = ($theIndexValue / $fullValue[$theOneTierIndexID]) * 100;
		}

		$surveyIndexValue[$theOneTierIndexID] = $theIndexValue;

		if ($isRecResult == 1) {
			$SQL = ' INSERT INTO ' . SURVEYINDEXRESULT_TABLE . ' SET surveyID = \'' . $Sur_G_Row['surveyID'] . '\',responseID = \'' . $R_Row['responseID'] . '\',taskID = \'' . $R_Row['taskID'] . '\',indexID = \'' . $theOneTierIndexID . '\',indexValue = \'' . $theIndexValue . '\' ';
			$DB->query($SQL);
		}
	}

	if ($surveyOneTierNA != 0) {
		$totalValue = ($totalValue / ($totalIndexValue - $surveyOneTierNA)) * 100;
	}
	else {
		$totalValue = ($totalValue / $totalIndexValue) * 100;
	}

	break;

default:
	$surveyIndexNaArray = array();
	$surveyIndexNA = array();
	$newSurveyIndex = array();

	foreach ($theIndexFather as $indexID => $theFatherID) {
		$theIndexValue = (isset($surveyIndex[$indexID]) ? $surveyIndex[$indexID] : 0);

		if ($surveyNA[$indexID] != 0) {
			if ($surveyNaNum[$indexID] == count($QtnList[$indexID])) {
				$surveyIndexNaArray[$theFatherID][] = $indexID;
				$surveyIndexNA[$theFatherID] += $fullValue[$indexID];
				$theIndexValue = '-999';
			}
			else if (($fullValue[$indexID] - $surveyNA[$indexID]) == 0) {
				$theIndexValue = 0;
			}
			else {
				$theIndexValue = $theIndexValue * ($fullValue[$indexID] / ($fullValue[$indexID] - $surveyNA[$indexID]));
			}
		}

		if (($isMinZero[$indexID] == 1) && ($theIndexValue < 0) && ($theIndexValue != '-999')) {
			$theIndexValue = 0;
		}

		if (($isMaxFull[$indexID] == 1) && ($fullValue[$indexID] < $theIndexValue)) {
			$theIndexValue = $fullValue[$indexID];
		}

		$newSurveyIndex[$indexID] = $theIndexValue;

		if ($isRecResult == 1) {
			$SQL = ' INSERT INTO ' . SURVEYINDEXRESULT_TABLE . ' SET surveyID = \'' . $Sur_G_Row['surveyID'] . '\',responseID = \'' . $R_Row['responseID'] . '\',taskID = \'' . $R_Row['taskID'] . '\',indexID = \'' . $indexID . '\',indexValue = \'' . $theIndexValue . '\' ';
			$DB->query($SQL);
		}
	}

	$totalIndexValue = 0;
	$surveyOneTierNA = 0;
	$surveyIndexValue = array();

	foreach ($theOneTierIndex as $theOneTierIndexID) {
		$totalIndexValue += $fullValue[$theOneTierIndexID];
		$theIndexValue = 0;

		foreach ($fatherId[$theOneTierIndexID] as $theTwoTierIndexID) {
			if ($newSurveyIndex[$theTwoTierIndexID] != '-999') {
				$theIndexValue += $newSurveyIndex[$theTwoTierIndexID];
			}
		}

		if (isset($surveyIndexNA[$theOneTierIndexID]) && ($surveyIndexNA[$theOneTierIndexID] != 0)) {
			if (count($surveyIndexNaArray[$theOneTierIndexID]) == count($fatherId[$theOneTierIndexID])) {
				$surveyOneTierNA += $fullValue[$theOneTierIndexID];
				$theIndexValue = '-999';
			}
			else if (($fullValue[$theOneTierIndexID] - $surveyIndexNA[$theOneTierIndexID]) == 0) {
				$theIndexValue = 0;
			}
			else {
				$theIndexValue = $theIndexValue * ($fullValue[$theOneTierIndexID] / ($fullValue[$theOneTierIndexID] - $surveyIndexNA[$theOneTierIndexID]));
			}
		}

		if (($isMinZero[$theOneTierIndexID] == 1) && ($theIndexValue < 0) && ($theIndexValue != '-999')) {
			$theIndexValue = 0;
		}

		if (($isMaxFull[$theOneTierIndexID] == 1) && ($fullValue[$theOneTierIndexID] < $theIndexValue)) {
			$theIndexValue = $fullValue[$theOneTierIndexID];
		}

		if ($theIndexValue != '-999') {
			$totalValue += $theIndexValue;
		}

		$surveyIndexValue[$theOneTierIndexID] = $theIndexValue;

		if ($isRecResult == 1) {
			$SQL = ' INSERT INTO ' . SURVEYINDEXRESULT_TABLE . ' SET surveyID = \'' . $Sur_G_Row['surveyID'] . '\',responseID = \'' . $R_Row['responseID'] . '\',taskID = \'' . $R_Row['taskID'] . '\',indexID = \'' . $theOneTierIndexID . '\',indexValue = \'' . $theIndexValue . '\' ';
			$DB->query($SQL);
		}
	}

	if ($surveyOneTierNA != 0) {
		$totalValue = $totalValue * ($totalIndexValue / ($totalIndexValue - $surveyOneTierNA));
	}

	break;
}

?>
