<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isSelect'] == 0) {
	$EnableQCoreClass->setTemplateFile('ShowOption' . $questionID . 'File', 'DeCount2D.html');
	$EnableQCoreClass->set_CycBlock('ShowOption' . $questionID . 'File', 'LIST', 'list' . $questionID);
	$EnableQCoreClass->replace('list' . $questionID, '');
	$EnableQCoreClass->set_CycBlock('LIST', 'OPTION', 'option' . $questionID);
	$EnableQCoreClass->replace('option' . $questionID, '');

	foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
		$EnableQCoreClass->replace('questionName', qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']));
		$theRankOptionID = 'option_' . $questionID . '_' . $question_rankID;
		$OptionCountSQL = ' SELECT ' . $theRankOptionID . ',COUNT( * ) AS optionResponseNum FROM ' . $table_prefix . 'response_' . $surveyID . ' b WHERE 1=1 and ' . $dataSource;
		$OptionCountSQL .= ' GROUP BY ' . $theRankOptionID . ' ';
		$OptionCountResult = $DB->query($OptionCountSQL);
		$allResponseOptionID = array();
		$allOptionResponseNum = array();

		while ($OptionCountRow = $DB->queryArray($OptionCountResult)) {
			$allResponseOptionID[] = $OptionCountRow[$theRankOptionID];
			$allOptionResponseNum[$OptionCountRow[$theRankOptionID]] = $OptionCountRow['optionResponseNum'];
		}

		if ($allOptionResponseNum[0] != '') {
			$EnableQCoreClass->replace('skipAnswerNum', $allOptionResponseNum[0]);
			$skipAnswerNum = $allOptionResponseNum[0];
			$EnableQCoreClass->replace('skipAnswerPercent', countpercent($skipAnswerNum, $totalRepAnswerNum));
		}
		else {
			$EnableQCoreClass->replace('skipAnswerNum', 0);
			$skipAnswerNum = 0;
			$EnableQCoreClass->replace('skipAnswerPercent', 0);
		}

		$thisOptionResponseNum = $totalRepAnswerNum - $skipAnswerNum;
		$EnableQCoreClass->replace('repAnswerNum', $thisOptionResponseNum);
		$EnableQCoreClass->replace('repAnswerPercent', countpercent($thisOptionResponseNum, $totalRepAnswerNum));

		if ($theQtnArray['isSelect'] == 0) {
			$i = $theQtnArray['endScale'];

			for (; $theQtnArray['startScale'] <= $i; $i--) {
				$RatingWeight = $theQtnArray['weight'] * $i;
				$EnableQCoreClass->replace('optionName', $RatingWeight);

				if (in_array($i, $allResponseOptionID)) {
					$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[$i]);
					$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[$i], $totalRepAnswerNum));
					$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[$i], $thisOptionResponseNum));
				}
				else {
					$EnableQCoreClass->replace('answerNum', 0);
					$EnableQCoreClass->replace('optionPercent', 0);
					$EnableQCoreClass->replace('optionValidPercent', 0);
				}

				$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
			}

			if ($theQtnArray['isHaveUnkown'] == 1) {
				$EnableQCoreClass->replace('optionName', $lang['rating_unknow']);

				if (in_array(99, $allResponseOptionID)) {
					$EnableQCoreClass->replace('answerNum', $allOptionResponseNum[99]);
					$EnableQCoreClass->replace('optionPercent', countpercent($allOptionResponseNum[99], $totalRepAnswerNum));
					$EnableQCoreClass->replace('optionValidPercent', countpercent($allOptionResponseNum[99], $thisOptionResponseNum));
				}
				else {
					$EnableQCoreClass->replace('answerNum', 0);
					$EnableQCoreClass->replace('optionPercent', 0);
					$EnableQCoreClass->replace('optionValidPercent', 0);
				}

				$EnableQCoreClass->parse('option' . $questionID, 'OPTION', true);
			}
		}

		unset($allResponseOptionID);
		unset($allOptionResponseNum);
		$EnableQCoreClass->parse('list' . $questionID, 'LIST', true);
		$EnableQCoreClass->unreplace('option' . $questionID);
	}

	$DataCrossHTML = $EnableQCoreClass->parse('ShowOption' . $questionID, 'ShowOption' . $questionID . 'File');
}

?>
