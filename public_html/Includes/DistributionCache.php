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
$surveyCacheContent = '$SurveyListArray = array( ';
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . $theSID . '\' ';
$Row = $DB->queryFirstRow($SQL);
$Row = qquotetostring(qnl2br($Row), 1);
$isPublic = ($Row['isPublic'] == 0 ? 1 : $Row['isPublic']);
$surveyCacheContent .= "\r\n" . ' \'surveyID\' => \'' . $Row['surveyID'] . '\',' . ' \'lang\' => \'' . $Row['lang'] . '\',' . ' \'isPublic\' => \'' . $isPublic . '\',' . ' \'tokenCode\' => \'' . $tokenCode . '\',' . ' \'status\' => \'0\',' . ' \'theme\' => \'' . $Row['theme'] . '\',' . ' \'uitheme\' => \'' . $Row['uitheme'] . '\',' . ' \'panelID\' => \'0\',' . ' \'surveyTitle\' => "' . getlocalresources($Row['surveyTitle']) . '",' . ' \'surveySubTitle\' => "' . getlocalresources($Row['surveySubTitle']) . '",' . ' \'surveyMaxOption\' => \'' . $Row['surveyMaxOption'] . '\',' . ' \'surveyInfo\' => "' . getlocalresources($Row['surveyInfo']) . '",' . ' \'exitMode\' => \'1\',' . ' \'exitPage\' => "' . $Row['exitPage'] . '",' . ' \'exitTitleHead\' => "' . $Row['exitTitleHead'] . '",' . ' \'exitTextBody\' => "' . getlocalresources($Row['exitTextBody']) . '",' . ' \'apiURL\' => "' . $Row['apiURL'] . '",' . ' \'apiVarName\' => "' . $Row['apiVarName'] . '",' . ' \'AppId\' => \'' . $Row['AppId'] . '\',' . ' \'AppSecret\' => \'' . $Row['AppSecret'] . '\',' . ' \'isOnlyWeChat\' => \'' . $Row['isOnlyWeChat'] . '\',' . ' \'getChatUserInfo\' => \'' . $Row['getChatUserInfo'] . '\',' . ' \'getChatUserMode\' => \'' . $Row['getChatUserMode'] . '\',' . ' \'msgImage\' => \'' . downmsgimagefile($Row['msgImage'], $theSID) . '\',' . ' \'isCheckIP\' => \'' . $Row['isCheckIP'] . '\',' . ' \'maxIpTime\' => \'' . $Row['maxIpTime'] . '\',' . ' \'isAllowIP\' => \'0\',' . ' \'isAllowIPMode\' => \'1\',' . ' \'maxResponseNum\' => \'' . $Row['maxResponseNum'] . '\',' . ' \'isProperty\' => \'' . $Row['isProperty'] . '\',' . ' \'isPreStep\' => \'' . $Row['isPreStep'] . '\',' . ' \'isProcessBar\' => \'' . $Row['isProcessBar'] . '\',' . ' \'isShowResultBut\' => \'' . $Row['isShowResultBut'] . '\',' . ' \'isViewResult\' => \'' . $Row['isViewResult'] . '\',' . ' \'isDisRefresh\' => \'' . $Row['isDisRefresh'] . '\',' . ' \'isAllData\' => \'' . $Row['isAllData'] . '\',' . ' \'isOnline0View\' => \'' . $Row['isOnline0View'] . '\',' . ' \'isViewAuthInfo\' => \'' . $Row['isViewAuthInfo'] . '\',' . ' \'isFailReApp\' => \'' . $Row['isFailReApp'] . '\',' . ' \'isViewAuthData\' => \'' . $Row['isViewAuthData'] . '\',' . ' \'isExportData\' => \'' . $Row['isExportData'] . '\',' . ' \'isImportData\' => \'' . $Row['isImportData'] . '\',' . ' \'isDeleteData\' => \'' . $Row['isDeleteData'] . '\',' . ' \'isModiData\' => \'' . $Row['isModiData'] . '\',' . ' \'isOneData\' => \'' . $Row['isOneData'] . '\',' . ' \'isGeolocation\' => \'' . $Row['isGeolocation'] . '\',' . ' \'isOnline0Auth\' => \'' . $Row['isOnline0Auth'] . '\',' . ' \'isLogicAnd\' => \'' . $Row['isLogicAnd'] . '\',' . ' \'isSecureImage\' => \'' . $Row['isSecureImage'] . '\',' . ' \'isRecord\' => \'' . $Row['isRecord'] . '\',' . ' \'isLowRecord\' => \'' . $Row['isLowRecord'] . '\',' . ' \'isUploadRec\' => \'' . $Row['isUploadRec'] . '\',' . ' \'isPanelFlag\' => \'' . $Row['isPanelFlag'] . '\',' . ' \'isWaiting\' => \'' . $Row['isWaiting'] . '\',' . ' \'waitingTime\' => \'' . $Row['waitingTime'] . '\',' . ' \'isLimited\' => \'' . $Row['isLimited'] . '\',' . ' \'limitedTime\' => \'' . $Row['limitedTime'] . '\',' . ' \'isCheckStat0\' => \'' . $Row['isCheckStat0'] . '\',' . ' \'isRelZero\' => \'' . $Row['isRelZero'] . '\',' . ' \'isRateIndex\' => \'' . $Row['isRateIndex'] . '\',' . ' \'isOfflineModi\' => \'' . $Row['isOfflineModi'] . '\',' . ' \'isOfflineDele\' => \'' . $Row['isOfflineDele'] . '\',' . ' \'isGpsEnable\' => \'' . $Row['isGpsEnable'] . '\',' . ' \'isFingerDrawing\' => \'' . $Row['isFingerDrawing'] . '\',' . ' \'mainAttribute\' => \'\',' . ' \'ajaxRtnValue\' => \'\',' . ' \'mainShowQtn\' => \'\',' . ' \'isCache\' => \'1\',' . ' \'limitedTime\' => \'' . $Row['limitedTime'] . '\',' . ' \'dbSize\' => \'' . $Row['dbSize'] . '\',' . ' \'beginTime\' => \'' . $Row['beginTime'] . '\',' . ' \'endTime\' => \'' . $Row['endTime'] . '\',' . ' \'joinTime\' => \'' . $Row['joinTime'] . '\');';
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
$SQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE surveyID =\'' . $theSID . '\' ORDER BY relationID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$relCacheContent .= "\r\n" . '    ' . $Row['relationID'] . ' => ' . 'array(' . "\r\n" . '' . '	 \'relationMode\' => ' . $Row['relationMode'] . ',' . "\r\n" . '     \'relationID\' => ' . $Row['relationID'] . ',' . "\r\n" . '     \'relationDefine\' => ' . $Row['relationDefine'] . ',' . "\r\n" . '	 \'relationNum\' => ' . $Row['relationNum'] . ',' . "\r\n" . '	 \'questionID\' => ' . $Row['questionID'] . ',' . "\r\n" . '	 \'optionID\' => ' . $Row['optionID'] . ',' . "\r\n" . '	 \'qtnID\' => ' . $Row['qtnID'] . ',' . "\r\n" . '	 \'labelID\' => ' . $Row['labelID'] . ',' . "\r\n" . '	 \'opertion\' => ' . $Row['opertion'] . ',' . "\r\n" . '	 \'list\' => array( ';
	$RSQL = ' SELECT a.* FROM ' . RELATION_LIST_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.relationID =\'' . $Row['relationID'] . '\' AND a.questionID = b.questionID ORDER BY a.optionOptionID ASC ';
	$RResult = $DB->query($RSQL);

	while ($RRow = $DB->queryArray($RResult)) {
		$relCacheContent .= "\r\n" . '          ' . $RRow['listID'] . ' => array(' . "\r\n" . '              \'questionID\' => ' . $RRow['questionID'] . ',' . "\r\n" . '		      \'optionID\' => ' . $RRow['optionID'] . ',' . "\r\n" . '		      \'qtnID\' => ' . $RRow['qtnID'] . ',' . "\r\n" . '		      \'labelID\' => ' . $RRow['labelID'] . ',' . "\r\n" . '		      \'optionOptionID\' => ' . $RRow['optionOptionID'] . ',' . "\r\n" . '		      \'opertion\' => ' . $RRow['opertion'] . '),';
	}

	$relCacheContent = substr($relCacheContent, 0, -1) . '' . "\r\n" . '      )' . "\r\n" . '    ),';
}

$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE surveyID =\'' . $theSID . '\' ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$Row = qquotetostring(qnl2br($Row), 1);
	$qtnCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => ' . 'array(\'questionID\' => ' . $Row['questionID'] . ',' . "\r\n" . '         \'questionName\' => "' . getlocalresources($Row['questionName']) . '",              ' . "\r\n" . '         \'questionNotes\' => "' . getlocalresources($Row['questionNotes']) . '",' . "\r\n" . '		 \'alias\' => "' . $Row['alias'] . '",' . "\r\n" . '		 \'questionType\' => ' . $Row['questionType'] . ',' . "\r\n" . '		 \'isPublic\' => ' . $Row['isPublic'] . ',' . "\r\n" . '		 \'isRequired\' => ' . $Row['isRequired'] . ',' . "\r\n" . '		 \'requiredMode\' => ' . $Row['requiredMode'] . ',' . "\r\n" . '		 \'isRandOptions\' => ' . $Row['isRandOptions'] . ',' . "\r\n" . '		 \'isCheckType\' => ' . $Row['isCheckType'] . ',' . "\r\n" . '		 \'isSelect\' => ' . $Row['isSelect'] . ',' . "\r\n" . '		 \'isLogicAnd\' => ' . $Row['isLogicAnd'] . ',' . "\r\n" . '		 \'baseID\' => ' . $Row['baseID'] . ',' . "\r\n" . '		 \'isColArrange\' => ' . $Row['isColArrange'] . ',' . "\r\n" . '		 \'perRowCol\' => ' . $Row['perRowCol'] . ',' . "\r\n" . '		 \'isHaveOther\' => ' . $Row['isHaveOther'] . ',' . "\r\n" . '		 \'otherText\' => "' . getlocalresources($Row['otherText']) . '",' . "\r\n" . '		 \'isNeg\' => ' . $Row['isNeg'] . ',' . "\r\n" . '   		 \'coeffMode\' => ' . $Row['coeffMode'] . ',' . "\r\n" . '   		 \'coeffTotal\' => ' . $Row['coeffTotal'] . ',' . "\r\n" . '   		 \'coeffZeroMargin\' => ' . $Row['coeffZeroMargin'] . ',' . "\r\n" . '   		 \'coeffFullMargin\' => ' . $Row['coeffFullMargin'] . ',' . "\r\n" . '   		 \'skipMode\' => ' . $Row['skipMode'] . ',' . "\r\n" . '   		 \'negCoeff\' => ' . $Row['negCoeff'] . ',' . "\r\n" . '		 \'optionCoeff\' => ' . $Row['optionCoeff'] . ',' . "\r\n" . '   		 \'negValue\' => ' . $Row['negValue'] . ',' . "\r\n" . '		 \'optionValue\' => ' . $Row['optionValue'] . ',' . "\r\n" . '		 \'otherCode\' => ' . $Row['otherCode'] . ',' . "\r\n" . '   		 \'isUnkown\' => ' . $Row['isUnkown'] . ',' . "\r\n" . '   		 \'isNA\' => ' . $Row['isNA'] . ',' . "\r\n" . '		 \'negCode\' => ' . $Row['negCode'] . ',' . "\r\n" . '		 \'isHaveWhy\' => ' . $Row['isHaveWhy'] . ',' . "\r\n" . '   		 \'isContInvalid\' => ' . $Row['isContInvalid'] . ',' . "\r\n" . '   		 \'contInvalidValue\' => ' . $Row['contInvalidValue'] . ',' . "\r\n" . '		 \'minOption\' => ' . $Row['minOption'] . ',' . "\r\n" . '		 \'maxOption\' => ' . $Row['maxOption'] . ',' . "\r\n" . '		 \'unitText\' => "' . $Row['unitText'] . '",' . "\r\n" . '		 \'rows\' => ' . $Row['rows'] . ',' . "\r\n" . '		 \'length\' => ' . $Row['length'] . ',' . "\r\n" . '		 \'minSize\' => ' . $Row['minSize'] . ',' . "\r\n" . '		 \'maxSize\' => ' . $Row['maxSize'] . ',' . "\r\n" . '		 \'allowType\' => "' . $Row['allowType'] . '",' . "\r\n" . '		 \'startScale\' => ' . $Row['startScale'] . ',' . "\r\n" . '		 \'endScale\' => ' . $Row['endScale'] . ',' . "\r\n" . '		 \'weight\' => ' . $Row['weight'] . ',' . "\r\n" . '		 \'isHaveUnkown\' => ' . $Row['isHaveUnkown'] . ',' . "\r\n" . '		 \'DSNConnect\' => "' . $Row['DSNConnect'] . '",' . "\r\n" . '		 \'DSNSQL\' => "' . $Row['DSNSQL'] . '",' . "\r\n" . '		 \'DSNUser\' => "' . $Row['DSNUser'] . '",' . "\r\n" . '		 \'DSNPassword\' => "' . $Row['DSNPassword'] . '",' . "\r\n" . '		 \'hiddenVarName\' => "' . $Row['hiddenVarName'] . '",' . "\r\n" . '		 \'hiddenFromSession\' => ' . $Row['hiddenFromSession'] . ',' . "\r\n" . '		 \'orderByID\' => ' . $Row['orderByID'] . '),';
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
		$Con_SQL = ' SELECT optionID,qtnID,opertion,logicValueIsAnd,logicMode FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' ORDER BY qtnID ASC,optionID ASC ';
		$Con_Result = $DB->query($Con_SQL);
		$m = 0;

		while ($Con_Row = $DB->queryArray($Con_Result)) {
			$condCacheContent .= '' . "\r\n" . '          ' . $m . ' => ' . 'array(' . $Con_Row['qtnID'] . ',' . $Con_Row['optionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
			$m++;
		}

		$condCacheContent = substr($condCacheContent, 0, -1) . '' . "\r\n" . '      ),';
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
				$Con_SQL = ' SELECT condQtnID,condOptionID,opertion,logicValueIsAnd,logicMode FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND qtnID = \'' . $qassRow['qtnID'] . '\' AND assType=1 ORDER BY condQtnID ASC,condOptionID ASC ';
				$Con_Result = $DB->query($Con_SQL);
				$m = 0;

				while ($Con_Row = $DB->queryArray($Con_Result)) {
					$qassCacheContent .= '' . "\r\n" . '                ' . $m . ' => ' . 'array(' . $Con_Row['condQtnID'] . ',' . $Con_Row['condOptionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
					$m++;
				}

				$qassCacheContent = substr($qassCacheContent, 0, -1) . '' . "\r\n" . '            ),';
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
				$Con_SQL = ' SELECT condQtnID,condOptionID,opertion,logicValueIsAnd,logicMode FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' AND optionID = \'' . $oassRow['optionID'] . '\' AND assType=2 ORDER BY condQtnID ASC,condOptionID ASC ';
				$Con_Result = $DB->query($Con_SQL);
				$m = 0;

				while ($Con_Row = $DB->queryArray($Con_Result)) {
					$oassCacheContent .= '' . "\r\n" . '                ' . $m . ' => ' . 'array(' . $Con_Row['condQtnID'] . ',' . $Con_Row['condOptionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
					$m++;
				}

				$oassCacheContent = substr($oassCacheContent, 0, -1) . '' . "\r\n" . '            ),';
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
			$yesnoCacheContent .= '' . "\r\n" . '    ' . $YesNoRow['question_yesnoID'] . ' => ' . 'array(\'question_yesnoID\' => ' . $YesNoRow['question_yesnoID'] . ',' . "\r\n" . '				         \'optionName\' => "' . getlocalresources($YesNoRow['optionName']) . '",' . "\r\n" . '						 \'optionCoeff\' => ' . $YesNoRow['optionCoeff'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $YesNoRow['optionValue'] . ',' . "\r\n" . '						 \'itemCode\' => ' . $YesNoRow['itemCode'] . ',' . "\r\n" . '						 \'isUnkown\' => ' . $YesNoRow['isUnkown'] . ',' . "\r\n" . '						 \'isNA\' => ' . $YesNoRow['isNA'] . ',' . "\r\n" . '						 \'optionOptionID\' => ' . $YesNoRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $YesNoRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $YesNoRow['isRequired'] . ',' . "\r\n" . '						 \'isNeg\' => ' . $YesNoRow['isNeg'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $YesNoRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $YesNoRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $YesNoRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $YesNoRow['unitText'] . '",' . "\r\n" . '						 \'orderByID\' => ' . $YesNoRow['orderByID'] . '),';
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
			$radioCacheContent .= '' . "\r\n" . '    ' . $RadioRow['question_radioID'] . ' => ' . 'array(\'question_radioID\' => ' . $RadioRow['question_radioID'] . ',' . "\r\n" . '				         \'optionName\' => "' . getlocalresources($RadioRow['optionName']) . '",' . "\r\n" . '						 \'optionOptionID\' => ' . $RadioRow['optionOptionID'] . ',' . "\r\n" . '						 \'optionMargin\' => ' . $RadioRow['optionMargin'] . ',' . "\r\n" . '						 \'optionCoeff\' => ' . $RadioRow['optionCoeff'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $RadioRow['optionValue'] . ',' . "\r\n" . '						 \'itemCode\' => ' . $RadioRow['itemCode'] . ',' . "\r\n" . '						 \'isUnkown\' => ' . $RadioRow['isUnkown'] . ',' . "\r\n" . '						 \'isNA\' => ' . $RadioRow['isNA'] . ',' . "\r\n" . '						 \'optionNameFile\' => "' . downloadpicfile($RadioRow['optionNameFile'], $theSID, $RadioRow['createDate'], $RadioRow['question_radioID']) . '",' . "\r\n" . '						 \'isHaveText\' => ' . $RadioRow['isHaveText'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $RadioRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $RadioRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $RadioRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $RadioRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $RadioRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $RadioRow['unitText'] . '",' . "\r\n" . '						 \'isLogicAnd\' => ' . $RadioRow['isLogicAnd'] . ',' . "\r\n" . '						 \'isRetain\' => ' . $RadioRow['isRetain'] . ',							 ' . "\r\n" . '						 \'createDate\' => ' . $RadioRow['createDate'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $RadioRow['orderByID'] . '),';
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
			$checkboxCacheContent .= '' . "\r\n" . '    ' . $CheckBoxRow['question_checkboxID'] . ' => ' . 'array(\'question_checkboxID\' => ' . $CheckBoxRow['question_checkboxID'] . ',' . "\r\n" . '				         \'optionName\' => "' . getlocalresources($CheckBoxRow['optionName']) . '",' . "\r\n" . '						 \'optionOptionID\' => ' . $CheckBoxRow['optionOptionID'] . ',' . "\r\n" . '						 \'groupNum\' => ' . $CheckBoxRow['groupNum'] . ',' . "\r\n" . '						 \'optionMargin\' => ' . $CheckBoxRow['optionMargin'] . ',' . "\r\n" . '						 \'optionCoeff\' => ' . $CheckBoxRow['optionCoeff'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $CheckBoxRow['optionValue'] . ',' . "\r\n" . '						 \'itemCode\' => ' . $CheckBoxRow['itemCode'] . ',' . "\r\n" . '						 \'isExclusive\' => ' . $CheckBoxRow['isExclusive'] . ',' . "\r\n" . '						 \'isNA\' => ' . $CheckBoxRow['isNA'] . ',' . "\r\n" . '						 \'optionNameFile\' => "' . downloadpicfile($CheckBoxRow['optionNameFile'], $theSID, $CheckBoxRow['createDate'], $CheckBoxRow['question_checkboxID']) . '",' . "\r\n" . '						 \'isHaveText\' => ' . $CheckBoxRow['isHaveText'] . ',' . "\r\n" . '						 \'optionSize\' => ' . $CheckBoxRow['optionSize'] . ',' . "\r\n" . '						 \'isRequired\' => ' . $CheckBoxRow['isRequired'] . ',' . "\r\n" . '						 \'isCheckType\' => ' . $CheckBoxRow['isCheckType'] . ',' . "\r\n" . '						 \'minOption\' => ' . $CheckBoxRow['minOption'] . ',' . "\r\n" . '						 \'maxOption\' => ' . $CheckBoxRow['maxOption'] . ',' . "\r\n" . '						 \'unitText\' => "' . $CheckBoxRow['unitText'] . '",' . "\r\n" . '						 \'isLogicAnd\' => ' . $CheckBoxRow['isLogicAnd'] . ',' . "\r\n" . '						 \'isRetain\' => ' . $CheckBoxRow['isRetain'] . ',							 ' . "\r\n" . '						 \'createDate\' => ' . $CheckBoxRow['createDate'] . ',' . "\r\n" . '						 \'orderByID\' => ' . $CheckBoxRow['orderByID'] . '),';
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

		while ($cRow = $DB->queryArray($cResult)) {
			$cRow = qquotetostring(qnl2br($cRow), 1);
			$cascadeCacheContent .= '' . "\r\n" . '    ' . $cRow['nodeID'] . ' => ' . 'array(\'nodeID\' => ' . $cRow['nodeID'] . ',' . "\r\n" . '						 \'nodeName\' => "' . $cRow['nodeName'] . '",   ' . "\r\n" . '						 \'level\' => "' . $cRow['level'] . '",   ' . "\r\n" . '						 \'nodeFatherID\' => ' . $cRow['nodeFatherID'] . '),';
		}

		if ($recordCount == 0) {
			$cascadeCacheContent .= '';
		}
		else {
			$cascadeCacheContent = substr($cascadeCacheContent, 0, -1) . '' . "\r\n" . '    ),';
		}

		break;

	case '8':
		break;

	case '9':
		$InfoSQL = ' SELECT * FROM ' . QUESTION_INFO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' LIMIT 1 ';
		$InfoRow = $DB->queryFirstRow($InfoSQL);

		if (!$InfoRow) {
			$infoCacheContent .= '';
		}
		else {
			$optionName = qquotetostring($InfoRow['optionName'], 1);
			$infoCacheContent .= "\r\n" . '  ' . $Row['questionID'] . ' => array(' . "\r\n" . '						 \'optionName\' => "' . getlocalresources($optionName) . '"),';
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
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '				         \'optionAnswer\' => "' . getlocalresources($AnswerRow['optionAnswer']) . '",' . "\r\n" . '						 \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . ',' . "\r\n" . '						 \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						 \'isLogicAnd\' => ' . $AnswerRow['isLogicAnd'] . ',' . "\r\n" . '						 \'isUnkown\' => ' . $AnswerRow['isUnkown'] . ',' . "\r\n" . '						 \'isNA\' => ' . $AnswerRow['isNA'] . ',' . "\r\n" . '						 \'itemCode\' => ' . $AnswerRow['itemCode'] . '),';
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
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '				          \'isRequired\' => "' . $OptionRow['isRequired'] . '",' . "\r\n" . '						  \'minOption\' => "' . $OptionRow['minOption'] . '",' . "\r\n" . '						  \'maxOption\' => "' . $OptionRow['maxOption'] . '",' . "\r\n" . '						  \'isLogicAnd\' => "' . $OptionRow['isLogicAnd'] . '",' . "\r\n" . '						  \'optionName\' => "' . getlocalresources($OptionRow['optionName']) . '"),';
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
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '				          \'optionAnswer\' => "' . getlocalresources($AnswerRow['optionAnswer']) . '",' . "\r\n" . '						  \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . ',' . "\r\n" . '						  \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						  \'isLogicAnd\' => ' . $AnswerRow['isLogicAnd'] . ',' . "\r\n" . '						  \'isUnkown\' => ' . $AnswerRow['isUnkown'] . ',' . "\r\n" . '						  \'isNA\' => ' . $AnswerRow['isNA'] . ',' . "\r\n" . '						  \'itemCode\' => ' . $AnswerRow['itemCode'] . '),';
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
			$answerCacheContent .= '' . "\r\n" . '    ' . $AnswerRow['question_range_answerID'] . ' => ' . 'array(\'question_range_answerID\' => ' . $AnswerRow['question_range_answerID'] . ',' . "\r\n" . '				          \'optionAnswer\' => "' . getlocalresources($AnswerRow['optionAnswer']) . '",' . "\r\n" . '						  \'optionCoeff\' => ' . $AnswerRow['optionCoeff'] . ',' . "\r\n" . '						  \'optionValue\' => ' . $AnswerRow['optionValue'] . ',' . "\r\n" . '						  \'isUnkown\' => ' . $AnswerRow['isUnkown'] . ',' . "\r\n" . '						  \'isNA\' => ' . $AnswerRow['isNA'] . ',' . "\r\n" . '						  \'itemCode\' => ' . $AnswerRow['itemCode'] . '),';
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
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '				          \'isLogicAnd\' => ' . $OptionRow['isLogicAnd'] . ',' . "\r\n" . '						  \'optionName\' => "' . getlocalresources($OptionRow['optionName']) . '"),';
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
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '				          \'optionLabel\' => "' . getlocalresources($LabelRow['optionLabel']) . '",' . "\r\n" . '						  \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						  \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						  \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						  \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						  \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						  \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						  \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
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
			$optionCacheContent .= '' . "\r\n" . '    ' . $OptionRow['question_range_optionID'] . ' => ' . 'array(\'question_range_optionID\' => ' . $OptionRow['question_range_optionID'] . ',' . "\r\n" . '				          \'isLogicAnd\' => ' . $OptionRow['isLogicAnd'] . ',' . "\r\n" . '						  \'optionName\' => "' . getlocalresources($OptionRow['optionName']) . '"),';
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
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '				          \'optionLabel\' => "' . getlocalresources($LabelRow['optionLabel']) . '",' . "\r\n" . '						  \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						  \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						  \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						  \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						  \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						  \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						  \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
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
			$labelCacheContent .= '' . "\r\n" . '    ' . $LabelRow['question_range_labelID'] . ' => ' . 'array(\'question_range_labelID\' => ' . $LabelRow['question_range_labelID'] . ',' . "\r\n" . '				          \'optionLabel\' => "' . getlocalresources($LabelRow['optionLabel']) . '",' . "\r\n" . '						  \'optionOptionID\' => ' . $LabelRow['optionOptionID'] . ',' . "\r\n" . '						  \'optionSize\' => ' . $LabelRow['optionSize'] . ',' . "\r\n" . '						  \'isRequired\' => ' . $LabelRow['isRequired'] . ',' . "\r\n" . '						  \'isCheckType\' => ' . $LabelRow['isCheckType'] . ',' . "\r\n" . '						  \'minOption\' => ' . $LabelRow['minOption'] . ',' . "\r\n" . '						  \'maxOption\' => ' . $LabelRow['maxOption'] . ',' . "\r\n" . '						  \'orderByID\' => ' . $LabelRow['orderByID'] . '),';
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
			$rankCacheContent .= '' . "\r\n" . '    ' . $RankRow['question_rankID'] . ' => ' . 'array(\'question_rankID\' => ' . $RankRow['question_rankID'] . ',' . "\r\n" . '				          \'isLogicAnd\' => ' . $RankRow['isLogicAnd'] . ',' . "\r\n" . '						  \'optionName\' => "' . getlocalresources($RankRow['optionName']) . '"),';
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
	case '30':
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
$cacheContent .= $oassCacheContent . "\r\n";
$cacheContent .= $condRadioCacheContent . "\r\n" . $textCheckCacheContent . "\r\n";
$cacheContent .= "\r\n";
$quotaCacheContent = '$QuotaListArray = array( ';
$quotaNameContent = '$QuotaNumArray = array( ';
$SQL = ' SELECT DISTINCT a.quotaID,a.quotaNum,a.quotaText,a.quotaName FROM ' . QUOTA_TABLE . ' a,' . CONDITIONS_TABLE . ' b WHERE a.surveyID =\'' . $theSID . '\' AND a.quotaID=b.quotaID ORDER BY a.quotaID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$CondSQL = ' SELECT DISTINCT a.condOnID FROM ' . CONDITIONS_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.quotaID = \'' . $Row['quotaID'] . '\' AND a.questionID=0 AND a.condOnID = b.questionID ORDER BY b.orderByID ASC,a.condOnID ASC ';
	$CondResult = $DB->query($CondSQL);
	$CondCount = $DB->_getNumRows($CondResult);

	if ($CondCount == 0) {
		continue;
	}

	$quotaNameContent .= "\r\n" . '  ' . $Row['quotaID'] . ' => array( ';
	$quotaNameContent .= '\'quotaNum\' => ' . $Row['quotaNum'] . ',';
	$quotaNameContent .= '\'quotaName\' => "' . qquotetostring(qnl2br($Row['quotaName']), 1) . '",';
	$quotaNameContent .= '\'quotaText\' => "' . qquotetostring(qnl2br($Row['quotaText']), 1) . '"';
	$quotaNameContent .= '),';
	$quotaCacheContent .= "\r\n" . '  ' . $Row['quotaID'] . ' => array( ';

	while ($CondRow = $DB->queryArray($CondResult)) {
		$quotaCacheContent .= "\r\n" . '      ' . $CondRow['condOnID'] . ' => array(';
		$Con_SQL = ' SELECT optionID,qtnID,opertion,logicValueIsAnd,logicMode FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND quotaID=\'' . $Row['quotaID'] . '\' ORDER BY qtnID ASC,optionID ASC ';
		$Con_Result = $DB->query($Con_SQL);
		$m = 0;

		while ($Con_Row = $DB->queryArray($Con_Result)) {
			$quotaCacheContent .= '' . "\r\n" . '          ' . $m . ' => ' . 'array(' . $Con_Row['qtnID'] . ',' . $Con_Row['optionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
			$m++;
		}

		$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . '      ),';
	}

	$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . '  ),';
}

$quotaNameContent = substr($quotaNameContent, 0, -1) . '' . "\r\n" . ');';
$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . ');';
$cacheContent .= $quotaNameContent . "\r\n" . $quotaCacheContent . "\r\n" . $surveyCacheContent . "\r\n";
$cacheContent .= chr(63) . chr(62);
$destinationPath = $Config['absolutenessPath'] . '/PerUserData/tmp/d' . $theSID . '/';
createdir($destinationPath);
write_to_file($destinationPath . 'surveydata.qdata', $cacheContent);

?>
