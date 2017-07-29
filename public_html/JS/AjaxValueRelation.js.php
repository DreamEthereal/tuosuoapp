<?php
//dezend by http://www.yunlu99.com/
echo "\r\n";
$releationList = '';
if (isset($_GET['type']) && ($_GET['type'] == 1)) {
	define('ROOT_PATH', '../');
	require_once ROOT_PATH . 'Entry/Global.setup.php';
	include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
	include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
	_checkroletype('1|2|5');
	$SQL = ' SELECT questionID,questionName,questionType,isCheckType,orderByID,isSelect,isHaveOther,isNeg,otherText,allowType,baseID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType IN (1,2,3,4,6,7,15,16,17,18,19,21,22,23,24,25,26,27,28) ORDER BY orderByID ASC  ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		switch ($Row['questionType']) {
		case 1:
		case 2:
		case 24:
			$questionName = qnohtmltag($Row['questionName'], 1);
			$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			break;

		case 3:
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			if (($Row['isSelect'] != '1') && ($Row['isHaveOther'] == '1')) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($Row['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			if ($Row['isNeg'] == '1') {
				$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*99999\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 4:
			if ($Row['isCheckType'] == 4) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}
			else {
				continue;
			}

			break;

		case 6:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 7:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}

			break;

		case 15:
		case 16:
			$ZSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' ORDER BY question_rankID ASC ';
			$ZResult = $DB->query($ZSQL);

			while ($ZRow = $DB->queryArray($ZResult)) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$optionName = qnohtmltag($ZRow['optionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ZRow['question_rankID'] . '\'>' . $questionName . ' - ' . $optionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 17:
			if ($Row['isSelect'] == 1) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}
			else {
				$bSQL = ' SELECT isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}

				if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}

				if ($Row['isCheckType'] == '1') {
					$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*99999\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}

			break;

		case 18:
			if ($Row['isSelect'] == 1) {
				$cSQL = ' SELECT question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_yesnoID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_yesnoID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}
			else {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 19:
		case 21:
		case 22:
			$bSQL = ' SELECT isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
			$bRow = $DB->queryFirstRow($bSQL);
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 23:
			$ZSQL = ' SELECT question_yesnoID,optionName,isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$ZResult = $DB->query($ZSQL);

			while ($ZRow = $DB->queryArray($ZResult)) {
				if ($ZRow['isCheckType'] == 4) {
					$questionName = qnohtmltag($Row['questionName'], 1);
					$optionName = qnohtmltag($ZRow['optionName'], 1);
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ZRow['question_yesnoID'] . '\'>' . $questionName . ' - ' . $optionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
				else {
					continue;
				}
			}

			break;

		case 25:
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
			}

			break;

		case 26:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_labelID,optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_labelID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_labelID'] . '*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionLabel'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}

			break;

		case 27:
			$OSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $Row['questionID'] . '\'  ORDER BY question_range_optionID ASC ';
			$OResult = $DB->query($OSQL);

			while ($ORow = $DB->queryArray($OResult)) {
				$ZSQL = ' SELECT question_range_labelID,optionLabel,isCheckType FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID=\'' . $Row['questionID'] . '\'  ORDER BY optionOptionID ASC ';
				$ZResult = $DB->query($ZSQL);

				while ($ZRow = $DB->queryArray($ZResult)) {
					if ($ZRow['isCheckType'] == 4) {
						$questionName = qnohtmltag($Row['questionName'], 1);
						$optionName = qnohtmltag($ORow['optionName'], 1);
						$optionLabel = qnohtmltag($ZRow['optionLabel'], 1);
						$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ORow['question_range_optionID'] . '*' . $ZRow['question_range_labelID'] . '\'>' . $questionName . ' - ' . $optionName . ' - ' . $optionLabel . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
					}
					else {
						continue;
					}
				}
			}

			break;

		case 28:
			$bSQL = ' SELECT isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
			$bRow = $DB->queryFirstRow($bSQL);
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*0' . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>';
				}
			}

			break;
		}
	}
}

echo 'var intRowIndex = 0;' . "\r\n" . 'function insertRow(tbIndex)' . "\r\n" . '{' . "\r\n" . '	 var theTd1Text = "&nbsp;" + tbIndex + "：";' . "\r\n" . '	 var theTd2Text = "<select name=\'opertion["+tbIndex+"]\' id=\'opertion_"+tbIndex+"\'><option value=\'\'>请选择...</option><option value=1>加 +</option><option value=2>减 -</option><option value=3>乘 *</option><option value=4>除 /</option></select>";' . "\r\n" . '	 var theTd3Text = "<select name=\'fieldsID["+tbIndex+"]\' id=\'fieldsID_"+tbIndex+"\' style=\'width:604px;*width:614px\'><option value=\'\'>请选择...</option>';
echo $releationList;
echo '</select>";' . "\r\n" . '	 var objRow = document.getElementById("theOptionTable").insertRow(tbIndex);' . "\r\n" . '	 var objCel = objRow.insertCell(0);' . "\r\n" . '	 objCel.innerHTML =theTd1Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(1);' . "\r\n" . '	 objCel.innerHTML = theTd2Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(2);' . "\r\n" . '	 objCel.innerHTML = theTd3Text;' . "\r\n" . '}' . "\r\n" . 'function deleteRow()' . "\r\n" . '{' . "\r\n" . '	 var theObj = document.getElementById("theOptionTable");' . "\r\n" . '	 U = theObj.rows.length-1;' . "\r\n" . '	 if( U > 1 ) {' . "\r\n" . '		theObj.deleteRow(U);' . "\r\n" . '	 }' . "\r\n" . '	 else {' . "\r\n" . '		$.notification("数值运算条件不能全部删除") ;' . "\r\n" . '	 }' . "\r\n" . '}' . "\r\n" . '';

?>
