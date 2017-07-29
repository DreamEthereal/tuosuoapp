<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$totalSurveyValue = 0;
$surveyNA = 0;
$surveyGrade = 0;

if (base64_decode($_POST['allFields']) != '') {
	foreach ($thisSurveyFields as $surveyFields) {
		$thisQtnId = explode('_', $surveyFields);
		$questionID = $thisQtnId[1];
		if (in_array($QtnListArray[$questionID]['questionType'], array(1, 2, 3, 6, 7, 15, 17, 18, 19, 21, 24, 25, 26, 28)) || (($QtnListArray[$questionID]['questionType'] == '4') && ($QtnListArray[$questionID]['isCheckType'] == '4'))) {
			$totalSurveyValue += $QtnListArray[$questionID]['coeffTotal'];

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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
					$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($R_Row['option_' . $questionID] != 0) {
					if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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
					$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($R_Row['option_' . $questionID] == '99999') {
					if ($QtnListArray[$questionID]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
				break;

			case '6':
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
					else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
				}

				break;

			case '7':
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
					else if ($R_Row[$theOptionID] == $theLastAnswerId) {
						if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
							$theQtnValue = 0;
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
				}

				break;

			case '15':
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($QtnListArray[$questionID]['isSelect'] == 1) {
					if ($R_Row['option_' . $questionID] == '99999') {
						if ($QtnListArray[$questionID]['isNA'] == 1) {
							$theQtnValue = 0;
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($QtnListArray[$questionID]['isSelect'] == 0) {
					if ($YesNoListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
					else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
						$theQtnValue = 0;
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else if ($RadioListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
					$theQtnValue = 0;
					$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
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
						$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
						break;
					}
				}
				else {
					$theSurveyFields = explode(',', $R_Row['option_' . $questionID]);

					if (count($theSurveyFields) == 1) {
						if ($CheckBoxListArray[$questionID][$R_Row['option_' . $questionID]]['isNA'] == 1) {
							$theQtnValue = 0;
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

				$surveyGrade += $theQtnValue;
				break;

			case '26':
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
								$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
								break;
							}
						}
						else if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
							$theQtnValue = 0;
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

						$surveyGrade += $theQtnValue;
					}
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
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
							break;
						}
					}
					else if ($R_Row[$theOptionID] == $theLastAnswerId) {
						if ($AnswerListArray[$questionID][$R_Row[$theOptionID]]['isNA'] == 1) {
							$theQtnValue = 0;
							$surveyNA += $QtnListArray[$questionID]['coeffTotal'];
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

					$surveyGrade += $theQtnValue;
				}

				break;
			}
		}
	}
}

?>
