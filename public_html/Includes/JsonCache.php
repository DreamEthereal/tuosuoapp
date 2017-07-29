<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theSID = (int) $theSID;

if ($theSID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$qtnCacheContent = '$QtnListArray = array( ';
$yesnoCacheContent = '$YesNoListArray = array( ';
$radioCacheContent = '$RadioListArray = array( ';
$checkboxCacheContent = '$CheckBoxListArray = array( ';
$infoCacheContent = '$InfoListArray = array( ';
$answerCacheContent = '$AnswerListArray = array( ';
$optionCacheContent = '$OptionListArray = array( ';
$labelCacheContent = '$LabelListArray = array( ';
$rankCacheContent = '$RankListArray = array( ';
$condCacheContent = '$CondListArray = array( ';
$cascadeCacheContent = '$CascadeArray = array( ';
$relCacheContent = '$ValueRelArray = array( ';
$qassCacheContent = '$QassListArray = array( ';
$oassCacheContent = '$OassListArray = array( ';
$condRadioCacheContent = '$CondRadioListArray = array( ';
$textCheckCacheContent = '$TextCheckArray = array( ';
$logicQtnList = '';
$cascadeQtnCache = '';
$cascadeQtnList = array();
$haveLogicQtnList = array();
$SQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE surveyID =\'' . $theSID . '\' ORDER BY relationID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$relCacheContent .= "\r\n" . '    ' . $Row['relationID'] . ' => ' . 'array(' . "\r\n" . '' . '	 \'relationMode\' => ' . $Row['relationMode'] . ',' . "\r\n" . '     \'relationDefine\' => ' . $Row['relationDefine'] . ',' . "\r\n" . '	 \'relationNum\' => ' . $Row['relationNum'] . ',' . "\r\n" . '	 \'questionID\' => ' . $Row['questionID'] . ',' . "\r\n" . '	 \'optionID\' => ' . $Row['optionID'] . ',' . "\r\n" . '	 \'qtnID\' => ' . $Row['qtnID'] . ',' . "\r\n" . '	 \'labelID\' => ' . $Row['labelID'] . ',' . "\r\n" . '	 \'opertion\' => ' . $Row['opertion'] . ',' . "\r\n" . '';

	if ($Row['relationDefine'] == 2) {
		$relCacheContent .= '     \'defineList\' => ';
		$tSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE weight=\'' . $Row['relationID'] . '\' AND questionType=30 AND requiredMode = 2 ';
		$tRow = $DB->queryfirstRow($tSQL);
		$relCacheContent .= $tRow['questionID'] . ',' . "\r\n" . '';
	}

	$relCacheContent .= '     \'list\' => array( ';
	$RSQL = ' SELECT a.* FROM ' . RELATION_LIST_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.relationID =\'' . $Row['relationID'] . '\' AND a.questionID = b.questionID ORDER BY a.optionOptionID ASC ';
	$RResult = $DB->query($RSQL);

	while ($RRow = $DB->queryArray($RResult)) {
		$relCacheContent .= "\r\n" . '          ' . $RRow['listID'] . ' => array(' . "\r\n" . '              \'questionID\' => ' . $RRow['questionID'] . ',' . "\r\n" . '		      \'optionID\' => ' . $RRow['optionID'] . ',' . "\r\n" . '		      \'qtnID\' => ' . $RRow['qtnID'] . ',' . "\r\n" . '		      \'labelID\' => ' . $RRow['labelID'] . ',' . "\r\n" . '		      \'opertion\' => ' . $RRow['opertion'] . '),';
		$tSQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $RRow['questionID'] . '\' ';
		$tRow = $DB->queryFirstRow($tSQL);

		switch ($tRow['questionType']) {
		case 1:
		case 2:
		case 3:
		case 4:
		case 17:
		case 18:
		case 24:
		case 25:
			$logicVarName = 'option_' . $RRow['questionID'];
			break;

		case 6:
		case 7:
		case 19:
		case 21:
		case 22:
			$logicVarName = 'option_' . $RRow['questionID'] . '_' . $RRow['qtnID'];
			break;

		case 15:
		case 16:
		case 23:
			$logicVarName = 'option_' . $RRow['questionID'] . '_' . $RRow['optionID'];
			break;

		case 26:
		case 28:
			$logicVarName = 'option_' . $RRow['questionID'] . '_' . $RRow['qtnID'] . '_' . $RRow['optionID'];
			break;

		case 27:
			$logicVarName = 'option_' . $RRow['questionID'] . '_' . $RRow['optionID'] . '_' . $RRow['labelID'];
			break;
		}

		if (!in_array($logicVarName, $haveLogicQtnList)) {
			$haveLogicQtnList[] = $logicVarName;
			$logicQtnList .= '$valueLogicQtnList[] = \'' . $logicVarName . '\';' . "\r\n" . '';
		}
	}

	$relCacheContent = substr($relCacheContent, 0, -1) . '' . "\r\n" . '      )' . "\r\n" . '    ),';
}

$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE surveyID =\'' . $theSID . '\' AND questionType!=8 ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$Row = qquotetostring(qnl2br($Row), 1);
	$qtnCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => ' . 'array(\'questionID\' => ' . $Row['questionID'] . ',' . "\r\n" . '         \'questionName\' => "' . json_replace($Row['questionName']) . '",         ' . "\r\n" . '         \'questionNotes\' => "' . json_replace($Row['questionNotes']) . '",      ' . "\r\n" . '		 \'questionType\' => ' . $Row['questionType'] . ',' . "\r\n" . '   		 \'isRequired\' => ' . $Row['isRequired'] . ',' . "\r\n" . '   		 \'requiredMode\' => ' . $Row['requiredMode'] . ',' . "\r\n" . '   		 \'isRandOptions\' => ' . $Row['isRandOptions'] . ',' . "\r\n" . '   		 \'isCheckType\' => ' . $Row['isCheckType'] . ',' . "\r\n" . '   		 \'isSelect\' => ' . $Row['isSelect'] . ',' . "\r\n" . '   		 \'isLogicAnd\' => ' . $Row['isLogicAnd'] . ',' . "\r\n" . '   		 \'baseID\' => ' . $Row['baseID'] . ',' . "\r\n" . '   		 \'isColArrange\' => ' . $Row['isColArrange'] . ',' . "\r\n" . '   		 \'perRowCol\' => ' . $Row['perRowCol'] . ',' . "\r\n" . '   		 \'isHaveOther\' => ' . $Row['isHaveOther'] . ',' . "\r\n" . '         \'otherText\' => "' . json_replace($Row['otherText']) . '",              ' . "\r\n" . '   		 \'isNeg\' => ' . $Row['isNeg'] . ',' . "\r\n" . '   		 \'negValue\' => ' . $Row['negValue'] . ',' . "\r\n" . '		 \'optionValue\' => ' . $Row['optionValue'] . ',' . "\r\n" . '   		 \'isHaveWhy\' => ' . $Row['isHaveWhy'] . ',' . "\r\n" . '   		 \'isContInvalid\' => ' . $Row['isContInvalid'] . ',' . "\r\n" . '   		 \'contInvalidValue\' => ' . $Row['contInvalidValue'] . ',' . "\r\n" . '   		 \'minOption\' => ' . $Row['minOption'] . ',' . "\r\n" . '   		 \'maxOption\' => ' . $Row['maxOption'] . ',' . "\r\n" . '         \'unitText\' => "' . json_replace($Row['unitText']) . '",              ' . "\r\n" . '   		 \'rows\' => ' . $Row['rows'] . ',' . "\r\n" . '   		 \'length\' => ' . $Row['length'] . ',' . "\r\n" . '   		 \'minSize\' => ' . $Row['minSize'] . ',' . "\r\n" . '   		 \'maxSize\' => ' . $Row['maxSize'] . ',' . "\r\n" . '         \'allowType\' => "' . $Row['allowType'] . '",              ' . "\r\n" . '   		 \'startScale\' => ' . $Row['startScale'] . ',' . "\r\n" . '   		 \'endScale\' => ' . $Row['endScale'] . ',' . "\r\n" . '   		 \'weight\' => ' . $Row['weight'] . ',' . "\r\n" . '   		 \'isHaveUnkown\' => ' . $Row['isHaveUnkown'] . ',' . "\r\n" . '   		 \'hiddenVarName\' => \'' . $Row['hiddenVarName'] . '\',' . "\r\n" . '   		 \'orderByID\' => ' . $Row['orderByID'] . '),';
	$CondSQL = ' SELECT DISTINCT condOnID FROM ' . CONDITIONS_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\'  ORDER BY condOnID ASC ';
	$CondResult = $DB->query($CondSQL);
	$recordCount = $DB->_getNumRows($CondResult);

	if ($recordCount == 0) {
		$condCacheContent .= '';
	}
	else {
		$condCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
	}

	while ($CondRow = $DB->queryArray($CondResult)) {
		$condCacheContent .= "\r\n" . '      ' . $CondRow['condOnID'] . ' => array(';
		$RangeSQL = ' SELECT DISTINCT qtnID FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' ORDER BY qtnID ASC ';
		$RangeResult = $DB->query($RangeSQL);

		while ($RangeRow = $DB->queryArray($RangeResult)) {
			$condCacheContent .= "\r\n" . '            ' . $RangeRow['qtnID'] . ' => array(';
			$Con_SQL = ' SELECT optionID,opertion,logicValueIsAnd,logicMode FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND qtnID = \'' . $RangeRow['qtnID'] . '\' ORDER BY optionID ASC ';
			$Con_Result = $DB->query($Con_SQL);
			$m = 0;

			while ($Con_Row = $DB->queryArray($Con_Result)) {
				$condCacheContent .= '' . "\r\n" . '                  ' . $m . ' => ' . 'array(' . $Con_Row['optionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
				$m++;
			}

			$condCacheContent = substr($condCacheContent, 0, -1) . '' . "\r\n" . '            ),';
		}

		$condCacheContent = substr($condCacheContent, 0, -1) . '' . "\r\n" . '     ),';
	}

	if ($recordCount == 0) {
		$condCacheContent .= '';
	}
	else {
		$condCacheContent = substr($condCacheContent, 0, -1) . '' . "\r\n" . '   ),';
	}

	switch ($Row['questionType']) {
	case '6':
	case '7':
	case '26':
	case '27':
	case '10':
	case '15':
	case '16':
		$qassSQL = ' SELECT DISTINCT qtnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' AND assType=1 ORDER BY qtnID ASC ';
		$qassResult = $DB->query($qassSQL);
		$recordCount = $DB->_getNumRows($qassResult);

		if ($recordCount == 0) {
			$qassCacheContent .= '';
		}
		else {
			$qassCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($qassRow = $DB->queryArray($qassResult)) {
			$qassCacheContent .= "\r\n" . '      ' . $qassRow['qtnID'] . ' => array(';
			$CondSQL = ' SELECT DISTINCT condOnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' AND qtnID = \'' . $qassRow['qtnID'] . '\' AND assType=1 ORDER BY condOnID ASC ';
			$CondResult = $DB->query($CondSQL);

			while ($CondRow = $DB->queryArray($CondResult)) {
				$qassCacheContent .= "\r\n" . '            ' . $CondRow['condOnID'] . ' => array(';
				$RangeSQL = ' SELECT DISTINCT condQtnID FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND qtnID = \'' . $qassRow['qtnID'] . '\' AND assType=1 ORDER BY condQtnID ASC ';
				$RangeResult = $DB->query($RangeSQL);

				while ($RangeRow = $DB->queryArray($RangeResult)) {
					$qassCacheContent .= "\r\n" . '                  ' . $RangeRow['condQtnID'] . ' => array(';
					$Con_SQL = ' SELECT condOptionID,opertion,logicValueIsAnd,logicMode FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND qtnID = \'' . $qassRow['qtnID'] . '\' AND assType=1 AND condQtnID = \'' . $RangeRow['condQtnID'] . '\' ORDER BY condOptionID ASC ';
					$Con_Result = $DB->query($Con_SQL);
					$m = 0;

					while ($Con_Row = $DB->queryArray($Con_Result)) {
						$qassCacheContent .= '' . "\r\n" . '                        ' . $m . ' => ' . 'array(' . $Con_Row['condOptionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
						$m++;
					}

					$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . '                  ),';
				}

				$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . '           ),';
			}

			$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . '      ),';
		}

		if ($recordCount == 0) {
			$qassCacheContent .= '';
		}
		else {
			$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . '   ),';
		}

		break;
	}

	switch ($Row['questionType']) {
	case '2':
	case '24':
	case '3':
	case '25':
	case '6':
	case '7':
	case '19':
	case '28':
		$oassSQL = ' SELECT DISTINCT optionID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' AND assType=2 ORDER BY optionID ASC ';
		$oassResult = $DB->query($oassSQL);
		$recordCount = $DB->_getNumRows($oassResult);

		if ($recordCount == 0) {
			$oassCacheContent .= '';
		}
		else {
			$oassCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($oassRow = $DB->queryArray($oassResult)) {
			$oassCacheContent .= "\r\n" . '      ' . $oassRow['optionID'] . ' => array(';
			$CondSQL = ' SELECT DISTINCT condOnID FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' AND optionID = \'' . $oassRow['optionID'] . '\' AND assType=2 ORDER BY condOnID ASC ';
			$CondResult = $DB->query($CondSQL);

			while ($CondRow = $DB->queryArray($CondResult)) {
				$oassCacheContent .= "\r\n" . '            ' . $CondRow['condOnID'] . ' => array(';
				$RangeSQL = ' SELECT DISTINCT condQtnID FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND optionID = \'' . $oassRow['optionID'] . '\' AND assType=2 ORDER BY condQtnID ASC ';
				$RangeResult = $DB->query($RangeSQL);

				while ($RangeRow = $DB->queryArray($RangeResult)) {
					$oassCacheContent .= "\r\n" . '                  ' . $RangeRow['condQtnID'] . ' => array(';
					$Con_SQL = ' SELECT condOptionID,opertion,logicValueIsAnd,logicMode FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND optionID = \'' . $oassRow['optionID'] . '\' AND assType=2 AND condQtnID = \'' . $RangeRow['condQtnID'] . '\' ORDER BY condOptionID ASC ';
					$Con_Result = $DB->query($Con_SQL);
					$m = 0;

					while ($Con_Row = $DB->queryArray($Con_Result)) {
						$oassCacheContent .= '' . "\r\n" . '                        ' . $m . ' => ' . 'array(' . $Con_Row['condOptionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
						$m++;
					}

					$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . '                  ),';
				}

				$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . '           ),';
			}

			$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . '      ),';
		}

		if ($recordCount == 0) {
			$oassCacheContent .= '';
		}
		else {
			$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . '   ),';
		}

		break;
	}

	switch ($Row['questionType']) {
	case '1':
	case '23':
	case '18':
		if (($Row['questionType'] == '1') || ($Row['questionType'] == '18')) {
			$YesNoSQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_yesnoID ASC ';
		}

		if ($Row['questionType'] == '23') {
			$YesNoSQL = ' SELECT * FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
		}

		$YesNoResult = $DB->query($YesNoSQL);
		$recordCount = $DB->_getNumRows($YesNoResult);

		if ($recordCount == 0) {
			$yesnoCacheContent .= '';
		}
		else {
			$yesnoCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($YesNoRow = $DB->queryArray($YesNoResult)) {
			$YesNoRow = qquotetostring(qnl2br($YesNoRow), 1);
			$yesnoCacheContent .= '' . "\r\n" . '    ' . $YesNoRow['question_yesnoID'] . ' => ' . 'array(\'question_yesnoID\' => ' . $YesNoRow['question_yesnoID'] . ',' . "\r\n" . '						 \'optionName\' => "' . json_replace($YesNoRow['optionName']) . '",        ' . "\r\n" . '						 \'optionValue\' => ' . $YesNoRow['optionValue'] . ',' . "\r\n" . '						 \'optionOptionID\' => ' . $YesNoRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $YesNoRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $YesNoRow['isRequired'] . ',' . "\r\n" . '						 \'isNeg\' => ' . $YesNoRow['isNeg'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $YesNoRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $YesNoRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $YesNoRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $YesNoRow['unitText'] . '",          ' . "\r\n" . '						 \'orderByID\' => ' . $YesNoRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$yesnoCacheContent .= '';
		}
		else {
			$yesnoCacheContent = substr($yesnoCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		if ($Row['questionType'] == '18') {
			$optionAutoArray = array();
			$BSQL = ' SELECT questionID,isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ';
			$BRow = $DB->queryFirstRow($BSQL);

			if ($BRow['isHaveOther'] == 1) {
				$optionAutoArray[] = 0;
			}

			$RSQL = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $BRow['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$RResult = $DB->query($RSQL);

			while ($RRow = $DB->queryArray($RResult)) {
				$optionAutoArray[] = $RRow['question_radioID'];
			}

			$condRadioCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';

			foreach ($optionAutoArray as $optionAutoID) {
				$condRadioCacheContent .= '' . "\r\n" . '    ' . $optionAutoID . ' => array(';
				$RelSQL = ' SELECT a.sonID FROM ' . CONDREL_TABLE . ' a,' . QUESTION_YESNO_TABLE . ' b WHERE a.fatherID =\'' . $optionAutoID . '\' AND a.questionID=\'' . $Row['questionID'] . '\' AND a.sonID = b.question_yesnoID ORDER BY a.sonID ASC ';
				$RelResult = $DB->query($RelSQL);

				while ($RelRow = $DB->queryArray($RelResult)) {
					$condRadioCacheContent .= '\'' . $RelRow['sonID'] . '\',';
				}

				$condRadioCacheContent = substr($condRadioCacheContent, 0, -1) . '),';
			}

			$condRadioCacheContent = substr($condRadioCacheContent, 0, -1) . '),';
		}

		break;

	case '2':
	case '24':
		$RadioSQL = ' SELECT * FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
		$RadioResult = $DB->query($RadioSQL);
		$recordCount = $DB->_getNumRows($RadioResult);

		if ($recordCount == 0) {
			$radioCacheContent .= '';
		}
		else {
			$radioCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($RadioRow = $DB->queryArray($RadioResult)) {
			$RadioRow = qquotetostring(qnl2br($RadioRow), 1);
			$radioCacheContent .= '' . "\r\n" . '    ' . $RadioRow['question_radioID'] . ' => ' . 'array(\'question_radioID\' => ' . $RadioRow['question_radioID'] . ',' . "\r\n" . '						 \'optionName\' => "' . json_replace($RadioRow['optionName']) . '",        ' . "\r\n" . '						 \'optionOptionID\' => ' . $RadioRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionMargin\' => ' . $RadioRow['optionMargin'] . ',' . "\r\n" . '						 \'optionNameFile\' => "' . downloadpicfile($RadioRow['optionNameFile'], $theSID, $RadioRow['createDate'], $RadioRow['question_radioID'], 1) . '", ' . "\r\n" . '						 \'optionValue\' => ' . $RadioRow['optionValue'] . ',' . "\r\n" . '						 \'isHaveText\' => ' . $RadioRow['isHaveText'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $RadioRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $RadioRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $RadioRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $RadioRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $RadioRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $RadioRow['unitText'] . '",          ' . "\r\n" . '						 \'isLogicAnd\' => ' . $RadioRow['isLogicAnd'] . ',' . "\r\n" . '						 \'isRetain\' => ' . $RadioRow['isRetain'] . ',							 ' . "\r\n" . '						 \'createDate\' => ' . $RadioRow['createDate'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $RadioRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$radioCacheContent .= '';
		}
		else {
			$radioCacheContent = substr($radioCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '3':
	case '25':
		$CheckBoxSQL = ' SELECT * FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
		$CheckBoxResult = $DB->query($CheckBoxSQL);
		$recordCount = $DB->_getNumRows($CheckBoxResult);

		if ($recordCount == 0) {
			$checkboxCacheContent .= '';
		}
		else {
			$checkboxCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
			$CheckBoxRow = qquotetostring(qnl2br($CheckBoxRow), 1);
			$checkboxCacheContent .= '' . "\r\n" . '    ' . $CheckBoxRow['question_checkboxID'] . ' => ' . 'array(\'question_checkboxID\' => ' . $CheckBoxRow['question_checkboxID'] . ',' . "\r\n" . '						 \'optionName\' => "' . json_replace($CheckBoxRow['optionName']) . '",     ' . "\r\n" . '						 \'optionOptionID\' => ' . $CheckBoxRow['optionOptionID'] . ',' . "\r\n" . '						 \'groupNum\' => ' . $CheckBoxRow['groupNum'] . ',' . "\r\n" . '						 \'optionMargin\' => ' . $CheckBoxRow['optionMargin'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $CheckBoxRow['optionValue'] . ',' . "\r\n" . '						 \'isExclusive\' => ' . $CheckBoxRow['isExclusive'] . ',' . "\r\n" . '						 \'optionNameFile\' => "' . downloadpicfile($CheckBoxRow['optionNameFile'], $theSID, $CheckBoxRow['createDate'], $CheckBoxRow['question_checkboxID'], 1) . '", ' . "\r\n" . '						 \'isHaveText\' => ' . $CheckBoxRow['isHaveText'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $CheckBoxRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $CheckBoxRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $CheckBoxRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $CheckBoxRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $CheckBoxRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $CheckBoxRow['unitText'] . '",       ' . "\r\n" . '						 \'isLogicAnd\' => ' . $CheckBoxRow['isLogicAnd'] . ',' . "\r\n" . '						 \'isRetain\' => ' . $CheckBoxRow['isRetain'] . ',							 ' . "\r\n" . '						 \'createDate\' => ' . $CheckBoxRow['createDate'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $CheckBoxRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$checkboxCacheContent .= '';
		}
		else {
			$checkboxCacheContent = substr($checkboxCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '31':
		$cSQL = ' SELECT * FROM ' . CASCADE_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' AND flag = 0 ORDER BY cascadeID ASC ';
		$cResult = $DB->query($cSQL);
		$recordCount = $DB->_getNumRows($cResult);

		if ($recordCount == 0) {
			$cascadeCacheContent .= '';
		}
		else {
			$cascadeCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		$theCascadeArray = array();

		while ($cRow = $DB->queryArray($cResult)) {
			$theCascadeArray[$cRow['nodeID']]['nodeID'] = $cRow['nodeID'];
			$theCascadeArray[$cRow['nodeID']]['nodeName'] = addslashes($cRow['nodeName']);
			$theCascadeArray[$cRow['nodeID']]['nodeFatherID'] = $cRow['nodeFatherID'];
			$cRow = qquotetostring(qnl2br($cRow), 1);
			$cascadeCacheContent .= '' . "\r\n" . '    ' . $cRow['nodeID'] . ' => ' . 'array(\'nodeID\' => ' . $cRow['nodeID'] . ',' . "\r\n" . '						 \'nodeName\' => "' . $cRow['nodeName'] . '",   ' . "\r\n" . '						 \'level\' => "' . $cRow['level'] . '",   ' . "\r\n" . '						 \'nodeFatherID\' => ' . $cRow['nodeFatherID'] . '),';
		}

		if ($recordCount == 0) {
			$cascadeCacheContent .= '';
		}
		else {
			$cascadeCacheContent = substr($cascadeCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		$cascadeQtnList[] = $Row['questionID'];
		$cascadeQtnCache .= '$Cascade_' . $Row['questionID'] . ' = \'' . json(findtreechild($theCascadeArray, 'nodeID', 'nodeFatherID')) . '\';' . "\r\n" . '';
		break;

	case '9':
		$InfoSQL = ' SELECT * FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' LIMIT 1 ';
		$InfoRow = $DB->queryFirstRow($InfoSQL);

		if (!$InfoRow) {
			$infoCacheContent .= '';
		}
		else {
			$optionName = qquotetostring($InfoRow['optionName'], 1);
			$infoCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(' . "\r\n" . '						 \'optionName\' => "' . json_replace($optionName) . '"),';
		}

		break;

	case '6':
	case '7':
		$AnswerSQL = ' SELECT * FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
		$AnswerResult = $DB->query($AnswerSQL);
		$recordCount = $DB->_getNumRows($AnswerResult);

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($AnswerRow = $DB->queryArray($AnswerResult)) {
			$AnswerRow = qquotetostring(qnl2br($AnswerRow), 1);
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '						 \'optionAnswer\' => "' . json_replace($AnswerRow['optionAnswer']) . '",     ' . "\r\n" . '						 \'isLogicAnd\' => ' . $AnswerRow['isLogicAnd'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						 \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . '),';
		}

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent = substr($answerCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		$OptionSQL = ' SELECT * FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
		$OptionResult = $DB->query($OptionSQL);
		$recordCount = $DB->_getNumRows($OptionResult);

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$OptionRow = qquotetostring(qnl2br($OptionRow), 1);
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '						 \'isRequired\' => "' . $OptionRow['isRequired'] . '",     ' . "\r\n" . '						 \'minOption\' => "' . $OptionRow['minOption'] . '",       ' . "\r\n" . '						 \'maxOption\' => "' . $OptionRow['maxOption'] . '",       ' . "\r\n" . '						 \'isLogicAnd\' => "' . $OptionRow['isLogicAnd'] . '",     ' . "\r\n" . '						 \'optionName\' => "' . json_replace($OptionRow['optionName']) . '"),';
		}

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent = substr($optionCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '19':
	case '28':
		$AnswerSQL = ' SELECT * FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
		$AnswerResult = $DB->query($AnswerSQL);
		$recordCount = $DB->_getNumRows($AnswerResult);

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($AnswerRow = $DB->queryArray($AnswerResult)) {
			$AnswerRow = qquotetostring(qnl2br($AnswerRow), 1);
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '						 \'optionAnswer\' => "' . json_replace($AnswerRow['optionAnswer']) . '",     ' . "\r\n" . '						 \'isLogicAnd\' => ' . $AnswerRow['isLogicAnd'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						 \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . '),';
		}

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent = substr($answerCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '26':
		$AnswerSQL = ' SELECT * FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
		$AnswerResult = $DB->query($AnswerSQL);
		$recordCount = $DB->_getNumRows($AnswerResult);

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($AnswerRow = $DB->queryArray($AnswerResult)) {
			$AnswerRow = qquotetostring(qnl2br($AnswerRow), 1);
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '						 \'optionAnswer\' => "' . json_replace($AnswerRow['optionAnswer']) . '",     ' . "\r\n" . '						 \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						 \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . '),';
		}

		if ($recordCount == 0) {
			$answerCacheContent .= '';
		}
		else {
			$answerCacheContent = substr($answerCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		$OptionSQL = ' SELECT * FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
		$OptionResult = $DB->query($OptionSQL);
		$recordCount = $DB->_getNumRows($OptionResult);

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$OptionRow = qquotetostring(qnl2br($OptionRow), 1);
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '						 \'isLogicAnd\' => ' . $OptionRow['isLogicAnd'] . ', ' . "\r\n" . '						 \'optionName\' => "' . json_replace($OptionRow['optionName']) . '"),';
		}

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent = substr($optionCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		$LabelSQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_labelID ASC ';
		$LabelResult = $DB->query($LabelSQL);
		$recordCount = $DB->_getNumRows($LabelResult);

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($LabelRow = $DB->queryArray($LabelResult)) {
			$LabelRow = qquotetostring(qnl2br($LabelRow), 1);
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '						 \'optionLabel\' => "' . json_replace($LabelRow['optionLabel']) . '",' . "\r\n" . '						 \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent = substr($labelCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '27':
		$OptionSQL = ' SELECT * FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
		$OptionResult = $DB->query($OptionSQL);
		$recordCount = $DB->_getNumRows($OptionResult);

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$OptionRow = qquotetostring(qnl2br($OptionRow), 1);
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '						 \'isLogicAnd\' => ' . $OptionRow['isLogicAnd'] . ', ' . "\r\n" . '						 \'optionName\' => "' . json_replace($OptionRow['optionName']) . '"),';
		}

		if ($recordCount == 0) {
			$optionCacheContent .= '';
		}
		else {
			$optionCacheContent = substr($optionCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		$LabelSQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
		$LabelResult = $DB->query($LabelSQL);
		$recordCount = $DB->_getNumRows($LabelResult);

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($LabelRow = $DB->queryArray($LabelResult)) {
			$LabelRow = qquotetostring(qnl2br($LabelRow), 1);
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '						 \'optionLabel\' => "' . json_replace($LabelRow['optionLabel']) . '",' . "\r\n" . '						 \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent = substr($labelCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '29':
		$LabelSQL = ' SELECT * FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
		$LabelResult = $DB->query($LabelSQL);
		$recordCount = $DB->_getNumRows($LabelResult);

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($LabelRow = $DB->queryArray($LabelResult)) {
			$LabelRow = qquotetostring(qnl2br($LabelRow), 1);
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '						 \'optionLabel\' => "' . json_replace($LabelRow['optionLabel']) . '",' . "\r\n" . '						 \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
		}

		if ($recordCount == 0) {
			$labelCacheContent .= '';
		}
		else {
			$labelCacheContent = substr($labelCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '10':
	case '15':
	case '16':
		$RankSQL = ' SELECT * FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_rankID ASC ';
		$RankResult = $DB->query($RankSQL);
		$recordCount = $DB->_getNumRows($RankResult);

		if ($recordCount == 0) {
			$rankCacheContent .= '';
		}
		else {
			$rankCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(';
		}

		while ($RankRow = $DB->queryArray($RankResult)) {
			$RankRow = qquotetostring(qnl2br($RankRow), 1);
			$rankCacheContent .= '' . "\r\n" . '    ' . $RankRow['question_rankID'] . ' => ' . 'array(\'question_rankID\' => ' . $RankRow['question_rankID'] . ',' . "\r\n" . '						 \'isLogicAnd\' => ' . $RankRow['isLogicAnd'] . ', ' . "\r\n" . '						 \'optionName\' => "' . json_replace($RankRow['optionName']) . '"),';
		}

		if ($recordCount == 0) {
			$rankCacheContent .= '';
		}
		else {
			$rankCacheContent = substr($rankCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '4':
		if ($Row['isSelect'] == 1) {
			$OptSQL = ' SELECT optionText FROM ' . TEXT_OPTION_TABLE . ' WHERE questionID=\'' . $Row['questionID'] . '\' ORDER BY optionID ASC ';
			$OptResult = $DB->query($OptSQL);
			$totalRecNum = $DB->_getNumRows($OptResult);
			$i = 0;
			$optionText = '';

			while ($OptRow = $DB->queryArray($OptResult)) {
				$i++;

				if ($i == $totalRecNum) {
					$optionText .= $OptRow['optionText'];
				}
				else {
					$optionText .= $OptRow['optionText'] . '######';
				}
			}

			$textCheckCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => "' . qquotetostring(qnl2br($optionText), 1) . '",';
		}

		break;

	case '5':
	case '11':
	case '12':
	case '13':
	case '14':
	case '20':
	case '21':
	case '22':
	case '17':
		break;

	case '30':
		if (($Row['requiredMode'] == 2) && !in_array('option_' . $Row['questionID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['questionID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['questionID'] . '\';' . "\r\n" . '';
		}

		break;
	}
}

$yesnoCacheContent = substr($yesnoCacheContent, 0, -1) . '' . "\r\n" . ');';
$radioCacheContent = substr($radioCacheContent, 0, -1) . '' . "\r\n" . ');';
$checkboxCacheContent = substr($checkboxCacheContent, 0, -1) . '' . "\r\n" . ');';
$infoCacheContent = substr($infoCacheContent, 0, -1) . '' . "\r\n" . ');';
$answerCacheContent = substr($answerCacheContent, 0, -1) . '' . "\r\n" . ');';
$optionCacheContent = substr($optionCacheContent, 0, -1) . '' . "\r\n" . ');';
$labelCacheContent = substr($labelCacheContent, 0, -1) . '' . "\r\n" . ');';
$rankCacheContent = substr($rankCacheContent, 0, -1) . '' . "\r\n" . ');';
$qtnCacheContent = substr($qtnCacheContent, 0, -1) . '' . "\r\n" . ');';
$condCacheContent = substr($condCacheContent, 0, -1) . '' . "\r\n" . ');';
$cascadeCacheContent = substr($cascadeCacheContent, 0, -1) . '' . "\r\n" . ');';
$relCacheContent = substr($relCacheContent, 0, -1) . '' . "\r\n" . ');';
$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . ');';
$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . ');';
$condRadioCacheContent = substr($condRadioCacheContent, 0, -1) . '' . "\r\n" . ');';
$textCheckCacheContent = substr($textCheckCacheContent, 0, -1) . '' . "\r\n" . ');';
$cacheContent .= $qtnCacheContent . "\r\n" . $yesnoCacheContent . "\r\n";
$cacheContent .= $radioCacheContent . "\r\n" . $checkboxCacheContent . "\r\n";
$cacheContent .= $infoCacheContent . "\r\n" . $answerCacheContent . "\r\n";
$cacheContent .= $optionCacheContent . "\r\n" . $labelCacheContent . "\r\n";
$cacheContent .= $rankCacheContent . "\r\n";
$cacheContent .= $condCacheContent . "\r\n" . $cascadeCacheContent . "\r\n";
$cacheContent .= $relCacheContent . "\r\n";
$cacheContent .= $qassCacheContent . "\r\n";
$cacheContent .= $oassCacheContent . "\r\n" . $cascadeQtnCache . "\r\n";
$cacheContent .= $condRadioCacheContent . "\r\n" . $textCheckCacheContent . "\r\n";
$SQL = ' SELECT condOnID,qtnID,optionID FROM ' . CONDITIONS_TABLE . ' WHERE quotaID =0 AND surveyID =\'' . $theSID . '\' ORDER BY questionID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$bSQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['condOnID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);

	switch ($bRow['questionType']) {
	case '1':
	case '2':
	case '3':
	case '4':
	case '24':
	case '25':
	case '30':
	case '17':
		if (!in_array('option_' . $Row['condOnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '\';' . "\r\n" . '';
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
		if (!in_array('option_' . $Row['condOnID'] . '_' . $Row['qtnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'] . '_' . $Row['qtnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '_' . $Row['qtnID'] . '\';' . "\r\n" . '';
		}

		break;
	}
}

$SQL = ' SELECT condOnID,condQtnID,condOptionID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID =\'' . $theSID . '\' ORDER BY questionID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$bSQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['condOnID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);

	switch ($bRow['questionType']) {
	case '1':
	case '2':
	case '3':
	case '4':
	case '24':
	case '25':
	case '30':
	case '17':
		if (!in_array('option_' . $Row['condOnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '\';' . "\r\n" . '';
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
		if (!in_array('option_' . $Row['condOnID'] . '_' . $Row['condQtnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'] . '_' . $Row['condQtnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '_' . $Row['condQtnID'] . '\';' . "\r\n" . '';
		}

		break;
	}
}

$cacheContent .= $logicQtnList . "\r\n";
$cacheContent .= chr(63) . chr(62);
unset($haveLogicQtnList);
$destination = $Config['absolutenessPath'] . 'PerUserData/tmp/' . $tmpFilePathName . '/';
createdir($destination);
write_to_file($destination . '/' . $tmpFilePathName . '.php', $cacheContent);

?>
