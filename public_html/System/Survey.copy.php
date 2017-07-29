<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.copy.inc.php';
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
$Row = $DB->queryFirstRow($SQL);
$Row = qaddslashes($Row, 1);

if ($isCheckSurveyName == 1) {
	if (64 < strlen($Row['surveyName'] . '_copy')) {
		$new_survey_name = substr($Row['surveyName'] . '_copy', 0, 64);
	}
	else {
		$new_survey_name = $Row['surveyName'] . '_copy';
	}

	$CheckSQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . $new_survey_name . '\' LIMIT 0,1 ';
	$CheckRow = $DB->queryFirstRow($CheckSQL);

	if ($CheckRow) {
		_showerror($lang['system_error'], $lang['exist_copy_survey']);
	}

	$new_survey_title = $Row['surveyTitle'] . '_copy';
}
else {
	$new_survey_name = date('YmdHis', time()) . rand(1, 999);
	$new_survey_title = $Row['surveyTitle'] . '_copy_' . $theCopyOrderNo;
}

$endTime = date('Y-m-d', time() + (30 * 86400));
$beginTime = date('Y-m-d', time());
$SurSQL = ' INSERT INTO ' . SURVEY_TABLE . ' SET surveyName=\'' . $new_survey_name . '\',lang=\'' . $Row['lang'] . '\',isPublic=\'' . $Row['isPublic'] . '\',tokenCode=\'' . $Row['tokenCode'] . '\',status=0,theme=\'' . $Row['theme'] . '\',uitheme=\'' . $Row['uitheme'] . '\',panelID=\'' . $Row['panelID'] . '\',surveyTitle=\'' . $new_survey_title . '\',surveySubTitle=\'' . $Row['surveySubTitle'] . '\',surveyMaxOption=\'' . $Row['surveyMaxOption'] . '\',surveyInfo=\'' . $Row['surveyInfo'] . '\',exitMode=\'' . $Row['exitMode'] . '\',exitTitleHead=\'' . $Row['exitTitleHead'] . '\',exitTextBody=\'' . $Row['exitTextBody'] . '\',apiURL=\'' . $Row['apiURL'] . '\',apiVarName=\'' . $Row['apiVarName'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',isCheckIP=\'' . $Row['isCheckIP'] . '\',maxIpTime=\'' . $Row['maxIpTime'] . '\',isAllowIP = \'' . $Row['isAllowIP'] . '\',isAllowIPMode = \'' . $Row['isAllowIPMode'] . '\',maxResponseNum=\'' . $Row['maxResponseNum'] . '\',isProperty=\'' . $Row['isProperty'] . '\',isPreStep=\'' . $Row['isPreStep'] . '\',isProcessBar=\'' . $Row['isProcessBar'] . '\',isViewResult=\'' . $Row['isViewResult'] . '\',isShowResultBut=\'' . $Row['isShowResultBut'] . '\',isDisRefresh=\'' . $Row['isDisRefresh'] . '\',isAllData=\'' . $Row['isAllData'] . '\',mainAttribute=\'' . $Row['mainAttribute'] . '\',isViewAuthData=\'' . $Row['isViewAuthData'] . '\',isViewAuthInfo=\'' . $Row['isViewAuthInfo'] . '\',isFailReApp=\'' . $Row['isFailReApp'] . '\',isExportData=\'' . $Row['isExportData'] . '\',isImportData=\'' . $Row['isImportData'] . '\',isDeleteData=\'' . $Row['isDeleteData'] . '\',isModiData=\'' . $Row['isModiData'] . '\',isOneData=\'' . $Row['isOneData'] . '\',isGeolocation=\'' . $Row['isGeolocation'] . '\',isOnline0Auth=\'' . $Row['isOnline0Auth'] . '\',isOnline0View=\'' . $Row['isOnline0View'] . '\',isSecureImage=\'' . $Row['isSecureImage'] . '\',isWaiting=\'' . $Row['isWaiting'] . '\',waitingTime=\'' . $Row['waitingTime'] . '\',isRecord=\'' . $Row['isRecord'] . '\',isLowRecord=\'' . $Row['isLowRecord'] . '\',isUploadRec=\'' . $Row['isUploadRec'] . '\',isPanelFlag=\'' . $Row['isPanelFlag'] . '\',isLimited=\'' . $Row['isLimited'] . '\',limitedTime=\'' . $Row['limitedTime'] . '\',isCheckStat0=\'' . $Row['isCheckStat0'] . '\',isRelZero=\'' . $Row['isRelZero'] . '\',isRateIndex=\'' . $Row['isRateIndex'] . '\',isOfflineModi=\'' . $Row['isOfflineModi'] . '\',isOfflineDele=\'' . $Row['isOfflineDele'] . '\',isGpsEnable=\'' . $Row['isGpsEnable'] . '\',isFingerDrawing=\'' . $Row['isFingerDrawing'] . '\',dbSize=\'' . $Row['dbSize'] . '\',beginTime=\'' . $beginTime . '\',endTime=\'' . $endTime . '\',joinTime=\'' . time() . '\',updateTime=\'\',projectType=\'' . $Row['projectType'] . '\',projectOwner=\'' . $Row['projectOwner'] . '\',AppId=\'' . $Row['AppId'] . '\',AppSecret=\'' . $Row['AppSecret'] . '\',isOnlyWeChat=\'' . $Row['isOnlyWeChat'] . '\',getChatUserInfo=\'' . $Row['getChatUserInfo'] . '\',getChatUserMode=\'' . $Row['getChatUserMode'] . '\' ';
$DB->query($SurSQL);
$newSurveyID = $DB->_GetInsertID();

if ($Row['exitPage'] != '') {
	$exitPage = str_replace('surveyID=' . $lastSurveyID, 'surveyID=' . $newSurveyID, $Row['exitPage']);
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET exitPage=\'' . $exitPage . '\' WHERE surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
}

if (trim($Row['custLogo']) != '') {
	$logoPath = $Config['absolutenessPath'] . '/PerUserData/logo/';

	if (file_exists($logoPath . trim($Row['custLogo']))) {
		$newFileNameList = explode('.', trim($Row['custLogo']));
		$newFileName = $newFileNameList[0] . $newSurveyID . '.' . $newFileNameList[1];

		if (copy($logoPath . trim($Row['custLogo']), $logoPath . $newFileName)) {
			$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET custLogo = \'' . $newFileName . '\' WHERE surveyID=\'' . $newSurveyID . '\' ';
			$DB->query($SQL);
		}
	}
}

if (trim($Row['msgImage']) != '') {
	$imgPath = $Config['absolutenessPath'] . '/PerUserData/logo/';

	if (file_exists($imgPath . trim($Row['msgImage']))) {
		$newFileNameList = explode('.', trim($Row['msgImage']));
		$newFileName = $newFileNameList[0] . $newSurveyID . '.' . $newFileNameList[1];

		if (copy($imgPath . trim($Row['msgImage']), $imgPath . $newFileName)) {
			$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET msgImage = \'' . $newFileName . '\' WHERE surveyID=\'' . $newSurveyID . '\' ';
			$DB->query($SQL);
		}
	}
}

if ($Row['isAllowIP'] == '1') {
	$SQL = ' SELECT startIP,endIP FROM ' . ALLOWIP_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ORDER BY allowIpID ASC ';
	$IPResult = $DB->query($SQL);

	while ($IPRow = $DB->queryArray($IPResult)) {
		$SQL = ' INSERT INTO ' . ALLOWIP_TABLE . ' SET startIP=\'' . $IPRow['startIP'] . '\',endIP=\'' . $IPRow['endIP'] . '\',surveyID=\'' . $newSurveyID . '\' ';
		$DB->query($SQL);
	}
}

if ($Row['exitMode'] == 3) {
	$SQL = ' SELECT * FROM ' . GRADE_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ORDER BY gradeID ASC ';
	$GradeResult = $DB->query($SQL);

	while ($GradeRow = $DB->queryArray($GradeResult)) {
		$SQL = ' INSERT INTO ' . GRADE_TABLE . ' SET startOperator=\'' . $GradeRow['startOperator'] . '\',startGrade=\'' . $GradeRow['startGrade'] . '\',endOperator=\'' . $GradeRow['endOperator'] . '\',endGrade=\'' . $GradeRow['endGrade'] . '\',conclusion=\'' . $GradeRow['conclusion'] . '\',surveyID=\'' . $newSurveyID . '\' ';
		$DB->query($SQL);
	}
}

$SQL = ' SELECT * FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ORDER BY indexID ASC ';
$IndexResult = $DB->query($SQL);
$newIndexArray = array();

while ($IndexRow = $DB->queryArray($IndexResult)) {
	$SQL = ' INSERT INTO ' . SURVEYINDEX_TABLE . ' SET indexName=\'' . $IndexRow['indexName'] . '\',indexDesc=\'' . $IndexRow['indexDesc'] . '\',fatherId=\'' . $IndexRow['fatherId'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
	$newIndexID = $DB->_GetInsertID();
	$newIndexArray[$IndexRow['indexID']] = $newIndexID;
}

$hSQL = ' SELECT indexID,fatherId FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $newSurveyID . '\' AND fatherId != 0 ORDER BY indexID ASC ';
$hResult = $DB->query($hSQL);

while ($hRow = $DB->queryArray($hResult)) {
	$uSQL = ' UPDATE ' . SURVEYINDEX_TABLE . ' SET fatherId =\'' . $newIndexArray[$hRow['fatherId']] . '\' WHERE indexID = \'' . $hRow['indexID'] . '\' ';
	$DB->query($uSQL);
}

if ($Row['isPublic'] == '0') {
	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$SQL = ' SELECT * FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
		$PubResult = $DB->query($SQL);

		while ($PubRow = $DB->queryArray($PubResult)) {
			$SQL = ' INSERT INTO ' . RESPONSEGROUPLIST_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',administratorsoptionID=\'' . $PubRow['administratorsoptionID'] . '\',value=\'' . $PubRow['value'] . '\' ';
			$DB->query($SQL);
		}

		break;

	case '3':
	case '5':
		$SQL = ' SELECT * FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
		$PubResult = $DB->query($SQL);

		while ($PubRow = $DB->queryArray($PubResult)) {
			$SQL = ' INSERT INTO ' . RESPONSEGROUPLIST_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',adUserName =\'' . $PubRow['adUserName'] . '\' ';
			$DB->query($SQL);
		}

		break;
	}
}

$SQL = ' SELECT * FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
$ViewResult = $DB->query($SQL);

while ($ViewRow = $DB->queryArray($ViewResult)) {
	$SQL = ' INSERT INTO ' . VIEWUSERLIST_TABLE . ' SET administratorsID=\'' . $ViewRow['administratorsID'] . '\',isAuth=\'' . $ViewRow['isAuth'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT * FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
$InResult = $DB->query($SQL);

while ($InRow = $DB->queryArray($InResult)) {
	$SQL = ' INSERT INTO ' . INPUTUSERLIST_TABLE . ' SET administratorsID=\'' . $InRow['administratorsID'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT * FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
$AppResult = $DB->query($SQL);

while ($AppRow = $DB->queryArray($AppResult)) {
	$SQL = ' INSERT INTO ' . APPEALUSERLIST_TABLE . ' SET administratorsID=\'' . $AppRow['administratorsID'] . '\',isAuth=\'' . $AppRow['isAuth'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT * FROM ' . SURVEYCATELIST_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ';
$CtResult = $DB->query($SQL);

while ($CtRow = $DB->queryArray($CtResult)) {
	$SQL = ' INSERT INTO ' . SURVEYCATELIST_TABLE . ' SET cateID=\'' . $CtRow['cateID'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $lastSurveyID . '\' ORDER BY orderByID ASC ';
$QutResult = $DB->query($SQL);
$newQtnArray = array();
$yesNoArray = array();
$radioArray = array();
$checkboxArray = array();
$rangeArray = array();
$answerArray = array();
$combTextValueArray = array();
$weightValueArray = array();
$multiTextOptionArray = array();
$multiTextLabelArray = array();
$rankArray = array();

while ($QutRow = $DB->queryArray($QutResult)) {
	$QutRow = qaddslashes($QutRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . qcopyqtnnewname($QutRow['questionName']) . '\',questionNotes=\'' . qcopyqtnnewname(trim($QutRow['questionNotes'])) . '\',surveyID=\'' . $newSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'' . $QutRow['questionType'] . '\',isPublic=\'' . $QutRow['isPublic'] . '\',isRequired=\'' . $QutRow['isRequired'] . '\',isRandOptions=\'' . $QutRow['isRandOptions'] . '\',isCheckType=\'' . $QutRow['isCheckType'] . '\',isSelect=\'' . $QutRow['isSelect'] . '\',isLogicAnd=\'' . $QutRow['isLogicAnd'] . '\',isColArrange=\'' . $QutRow['isColArrange'] . '\',perRowCol=\'' . $QutRow['perRowCol'] . '\',isHaveOther=\'' . $QutRow['isHaveOther'] . '\',isHaveUnkown=\'' . $QutRow['isHaveUnkown'] . '\',requiredMode=\'' . $QutRow['requiredMode'] . '\',otherText=\'' . $QutRow['otherText'] . '\',isHaveWhy=\'' . $QutRow['isHaveWhy'] . '\',isContInvalid=\'' . $QutRow['isContInvalid'] . '\',contInvalidValue=\'' . $QutRow['contInvalidValue'] . '\',minOption=\'' . $QutRow['minOption'] . '\',maxOption=\'' . $QutRow['maxOption'] . '\',unitText=\'' . $QutRow['unitText'] . '\',rows=\'' . $QutRow['rows'] . '\',length=\'' . $QutRow['length'] . '\',minSize=\'' . $QutRow['minSize'] . '\',maxSize=\'' . $QutRow['maxSize'] . '\',allowType=\'' . $QutRow['allowType'] . '\',optionCoeff=\'' . $QutRow['optionCoeff'] . '\',optionValue=\'' . $QutRow['optionValue'] . '\',otherCode=\'' . $QutRow['otherCode'] . '\',isUnkown=\'' . $QutRow['isUnkown'] . '\',isNA=\'' . $QutRow['isNA'] . '\',negCode=\'' . $QutRow['negCode'] . '\',isNeg=\'' . $QutRow['isNeg'] . '\',DSNConnect=\'' . $QutRow['DSNConnect'] . '\',DSNSQL=\'' . $QutRow['DSNSQL'] . '\',DSNUser=\'' . $QutRow['DSNUser'] . '\',DSNPassword=\'' . $QutRow['DSNPassword'] . '\',hiddenVarName=\'' . $QutRow['hiddenVarName'] . '\',hiddenFromSession=\'' . $QutRow['hiddenFromSession'] . '\',weight=\'' . $QutRow['weight'] . '\',startScale=\'' . $QutRow['startScale'] . '\',endScale=\'' . $QutRow['endScale'] . '\',baseID=\'' . $QutRow['baseID'] . '\',alias=\'' . $QutRow['alias'] . '\',coeffMode=\'' . $QutRow['coeffMode'] . '\',coeffTotal=\'' . $QutRow['coeffTotal'] . '\',coeffZeroMargin=\'' . $QutRow['coeffZeroMargin'] . '\',coeffFullMargin=\'' . $QutRow['coeffFullMargin'] . '\',skipMode=\'' . $QutRow['skipMode'] . '\',negCoeff=\'' . $QutRow['negCoeff'] . '\',negValue=\'' . $QutRow['negValue'] . '\',orderByID=\'' . $QutRow['orderByID'] . '\' ';
	$DB->query($SQL);
	$newQuestionID = $DB->_GetInsertID();
	$newQtnArray[$QutRow['questionID']] = $newQuestionID;

	if ($QutRow['questionType'] != '8') {
		$MoudleName = $Module[$QutRow['questionType']];
		$theNewSurveyID = $newSurveyID;
		require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.copy.inc.php';
	}
}

$SQL = ' SELECT * FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$iSQL = ' INSERT INTO ' . SURVEYINDEXLIST_TABLE . ' SET surveyID = \'' . $newSurveyID . '\',indexID = \'' . $newIndexArray[$Row['indexID']] . '\',questionID = \'' . $newQtnArray[$Row['questionID']] . '\' ';
	$DB->query($iSQL);
}

$SQL = ' SELECT * FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\' AND questionID != 0 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theNewQtnID = $newQtnArray[$Row['questionID']];
	$theNewCondOnID = $newQtnArray[$Row['condOnID']];
	$newOptionID = $newQtnID = 0;
	$opertion = $Row['opertion'];
	$logicValueIsAnd = $Row['logicValueIsAnd'];
	$logicMode = $Row['logicMode'];
	$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['condOnID'] . '\' ';
	$NewConQtnRow = $DB->queryFirstRow($SQL);

	switch ($NewConQtnRow['questionType']) {
	case '1':
		$newOptionID = $yesNoArray[$Row['optionID']];
		break;

	case '2':
	case '24':
		if ($Row['optionID'] == 0) {
			$newOptionID = 0;
		}
		else {
			$newOptionID = $radioArray[$Row['optionID']];
		}

		break;

	case '3':
		if ($Row['logicMode'] == '2') {
			$newOptionID = $Row['optionID'];
			$logicValueIsAnd = 0;
		}
		else if ($Row['optionID'] == 0) {
			$newOptionID = 0;
		}
		else if ($Row['optionID'] == 99999) {
			$newOptionID = 99999;
		}
		else {
			$newOptionID = $checkboxArray[$Row['optionID']];
		}

		break;

	case '17':
		if ($Row['optionID'] == 0) {
			$newOptionID = 0;
		}
		else if ($Row['optionID'] == 99999) {
			$newOptionID = 99999;
		}
		else {
			$newOptionID = $checkboxArray[$Row['optionID']];
		}

		break;

	case '25':
		if ($Row['logicMode'] == '2') {
			$newOptionID = $Row['optionID'];
			$logicValueIsAnd = 0;
		}
		else {
			$newOptionID = $checkboxArray[$Row['optionID']];
		}

		break;

	case '6':
	case '7':
		$newQtnID = $rangeArray[$Row['qtnID']];
		$newOptionID = $answerArray[$Row['optionID']];
		break;

	case '19':
	case '28':
		if ($Row['qtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $checkboxArray[$Row['qtnID']];
		}

		$newOptionID = $answerArray[$Row['optionID']];
		break;

	case '30':
	case '4':
		$newOptionID = $Row['optionID'];
		break;

	case '16':
		$newQtnID = $weightValueArray[$Row['qtnID']];
		$newOptionID = $Row['optionID'];
		break;

	case '23':
		$newQtnID = $combTextValueArray[$Row['qtnID']];
		$newOptionID = $Row['optionID'];
		break;

	case '10':
		if ($Row['qtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $rankArray[$Row['qtnID']];
		}

		$newOptionID = $Row['optionID'];
		break;

	case '15':
		$newQtnID = $rankArray[$Row['qtnID']];
		$newOptionID = $Row['optionID'];
		break;

	case '20':
	case '21':
	case '22':
		if ($Row['qtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $checkboxArray[$Row['qtnID']];
		}

		$newOptionID = $Row['optionID'];
		break;

	case '31':
		$newQtnID = $Row['qtnID'];
		$newOptionID = $Row['optionID'];
		break;
	}

	$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',questionID=\'' . $theNewQtnID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $theNewCondOnID . '\',optionID=\'' . $newOptionID . '\',qtnID=\'' . $newQtnID . '\',opertion=\'' . $opertion . '\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\' ';
	$DB->query($SQL);
}

$SQL = ' SELECT * FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$thisNewQtnID = $newQtnArray[$Row['questionID']];
	$assType = $Row['assType'];
	$theNewQtnID = $theNewOptionID = 0;
	$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ';
	$theNewQtnRow = $DB->queryFirstRow($SQL);

	switch ($assType) {
	case '1':
		switch ($theNewQtnRow['questionType']) {
		case '6':
		case '7':
		case '26':
			$theNewQtnID = $rangeArray[$Row['qtnID']];
			break;

		case '27':
			$theNewQtnID = $multiTextOptionArray[$Row['qtnID']];
			break;

		case '10':
			if ($Row['qtnID'] == 0) {
				$theNewQtnID = 0;
			}
			else {
				$theNewQtnID = $rankArray[$Row['qtnID']];
			}

			break;

		case '15':
			$theNewQtnID = $rankArray[$Row['qtnID']];
			break;

		case '16':
			$theNewQtnID = $weightValueArray[$Row['qtnID']];
			break;
		}

		break;

	case '2':
		switch ($theNewQtnRow['questionType']) {
		case '2':
		case '24':
			if ($Row['optionID'] == 0) {
				$theNewOptionID = 0;
			}
			else {
				$theNewOptionID = $radioArray[$Row['optionID']];
			}

			break;

		case '3':
			if ($Row['optionID'] == 0) {
				$theNewOptionID = 0;
			}
			else if ($Row['optionID'] == 99999) {
				$theNewOptionID = 99999;
			}
			else {
				$theNewOptionID = $checkboxArray[$Row['optionID']];
			}

			break;

		case '25':
			$theNewOptionID = $checkboxArray[$Row['optionID']];
			break;

		case '6':
		case '7':
		case '19':
		case '28':
			$theNewOptionID = $answerArray[$Row['optionID']];
			break;
		}

		break;
	}

	$theNewCondOnID = $newQtnArray[$Row['condOnID']];
	$newOptionID = $newQtnID = 0;
	$opertion = $Row['opertion'];
	$logicValueIsAnd = $Row['logicValueIsAnd'];
	$logicMode = $Row['logicMode'];
	$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['condOnID'] . '\' ';
	$NewConQtnRow = $DB->queryFirstRow($SQL);

	switch ($NewConQtnRow['questionType']) {
	case '1':
		$newOptionID = $yesNoArray[$Row['condOptionID']];
		break;

	case '2':
	case '24':
		if ($Row['condOptionID'] == 0) {
			$newOptionID = 0;
		}
		else {
			$newOptionID = $radioArray[$Row['condOptionID']];
		}

		break;

	case '3':
		if ($Row['logicMode'] == '2') {
			$newOptionID = $Row['condOptionID'];
			$logicValueIsAnd = 0;
		}
		else if ($Row['condOptionID'] == 0) {
			$newOptionID = 0;
		}
		else if ($Row['condOptionID'] == 99999) {
			$newOptionID = 99999;
		}
		else {
			$newOptionID = $checkboxArray[$Row['condOptionID']];
		}

		break;

	case '17':
		if ($Row['condOptionID'] == 0) {
			$newOptionID = 0;
		}
		else if ($Row['condOptionID'] == 99999) {
			$newOptionID = 99999;
		}
		else {
			$newOptionID = $checkboxArray[$Row['condOptionID']];
		}

		break;

	case '25':
		if ($Row['logicMode'] == '2') {
			$newOptionID = $Row['condOptionID'];
			$logicValueIsAnd = 0;
		}
		else {
			$newOptionID = $checkboxArray[$Row['condOptionID']];
		}

		break;

	case '6':
	case '7':
		$newQtnID = $rangeArray[$Row['condQtnID']];
		$newOptionID = $answerArray[$Row['condOptionID']];
		break;

	case '19':
	case '28':
		if ($Row['condQtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $checkboxArray[$Row['condQtnID']];
		}

		$newOptionID = $answerArray[$Row['condOptionID']];
		break;

	case '30':
	case '4':
		$newOptionID = $Row['condOptionID'];
		break;

	case '16':
		$newQtnID = $weightValueArray[$Row['condQtnID']];
		$newOptionID = $Row['condOptionID'];
		break;

	case '23':
		$newQtnID = $combTextValueArray[$Row['condQtnID']];
		$newOptionID = $Row['condOptionID'];
		break;

	case '10':
		if ($Row['condQtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $rankArray[$Row['condQtnID']];
		}

		$newOptionID = $Row['condOptionID'];
		break;

	case '15':
		$newQtnID = $rankArray[$Row['condQtnID']];
		$newOptionID = $Row['condOptionID'];
		break;

	case '20':
	case '21':
	case '22':
		if ($Row['condQtnID'] == 0) {
			$newQtnID = 0;
		}
		else {
			$newQtnID = $checkboxArray[$Row['condQtnID']];
		}

		$newOptionID = $Row['condOptionID'];
		break;

	case '31':
		$newQtnID = $Row['condQtnID'];
		$newOptionID = $Row['condOptionID'];
		break;
	}

	$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $thisNewQtnID . '\',qtnID=\'' . $theNewQtnID . '\',optionID=\'' . $theNewOptionID . '\',condOnID=\'' . $theNewCondOnID . '\',condOptionID=\'' . $newOptionID . '\',condQtnID=\'' . $newQtnID . '\',opertion=\'' . $opertion . '\',assType=\'' . $assType . '\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\' ';
	$DB->query($SQL);
}

$QuotaSQL = ' SELECT * FROM ' . QUOTA_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\'  ORDER BY quotaID ASC ';
$QuotaResult = $DB->query($QuotaSQL);

while ($QuotaRow = $DB->queryArray($QuotaResult)) {
	$QuotaRow = qaddslashes($QuotaRow, 1);
	$SQL = ' INSERT INTO ' . QUOTA_TABLE . ' SET quotaName=\'' . $QuotaRow['quotaName'] . '\',quotaText=\'' . $QuotaRow['quotaText'] . '\',quotaNum=\'' . $QuotaRow['quotaNum'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
	$newQuotaID = $DB->_GetInsertID();
	$SQL = ' SELECT * FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\' AND quotaID = \'' . $QuotaRow['quotaID'] . '\' AND questionID = 0 ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$theNewCondOnID = $newQtnArray[$Row['condOnID']];
		$newOptionID = $newQtnID = 0;
		$opertion = $Row['opertion'];
		$logicValueIsAnd = $Row['logicValueIsAnd'];
		$logicMode = $Row['logicMode'];
		$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['condOnID'] . '\' ';
		$NewConQtnRow = $DB->queryFirstRow($SQL);

		switch ($NewConQtnRow['questionType']) {
		case '1':
			$newOptionID = $yesNoArray[$Row['optionID']];
			break;

		case '2':
		case '24':
			if ($Row['optionID'] == 0) {
				$newOptionID = 0;
			}
			else {
				$newOptionID = $radioArray[$Row['optionID']];
			}

			break;

		case '3':
			if ($logicMode == '2') {
				$newOptionID = $Row['optionID'];
				$logicValueIsAnd = 0;
			}
			else if ($Row['optionID'] == 0) {
				$newOptionID = 0;
			}
			else if ($Row['optionID'] == 99999) {
				$newOptionID = 99999;
			}
			else {
				$newOptionID = $checkboxArray[$Row['optionID']];
			}

			break;

		case '17':
			if ($Row['optionID'] == 0) {
				$newOptionID = 0;
			}
			else if ($Row['optionID'] == 99999) {
				$newOptionID = 99999;
			}
			else {
				$newOptionID = $checkboxArray[$Row['optionID']];
			}

			break;

		case '25':
			if ($logicMode == '2') {
				$newOptionID = $Row['optionID'];
				$logicValueIsAnd = 0;
			}
			else {
				$newOptionID = $checkboxArray[$Row['optionID']];
			}

			break;

		case '6':
		case '7':
			$newQtnID = $rangeArray[$Row['qtnID']];
			$newOptionID = $answerArray[$Row['optionID']];
			break;

		case '19':
		case '28':
			if ($Row['qtnID'] == 0) {
				$newQtnID = 0;
			}
			else {
				$newQtnID = $checkboxArray[$Row['qtnID']];
			}

			$newOptionID = $answerArray[$Row['optionID']];
			break;

		case '30':
		case '4':
			$newOptionID = $Row['optionID'];
			break;

		case '16':
			$newQtnID = $weightValueArray[$Row['qtnID']];
			$newOptionID = $Row['optionID'];
			break;

		case '23':
			$newQtnID = $combTextValueArray[$Row['qtnID']];
			$newOptionID = $Row['optionID'];
			break;

		case '10':
			if ($Row['qtnID'] == 0) {
				$newQtnID = 0;
			}
			else {
				$newQtnID = $rankArray[$Row['qtnID']];
			}

			$newOptionID = $Row['optionID'];
			break;

		case '15':
			$newQtnID = $rankArray[$Row['qtnID']];
			$newOptionID = $Row['optionID'];
			break;

		case '20':
		case '21':
		case '22':
			if ($Row['qtnID'] == 0) {
				$newQtnID = 0;
			}
			else {
				$newQtnID = $checkboxArray[$Row['qtnID']];
			}

			$newOptionID = $Row['optionID'];
			break;

		case '31':
			$newQtnID = $Row['qtnID'];
			$newOptionID = $Row['optionID'];
			break;
		}

		$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',questionID=0,administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $theNewCondOnID . '\',optionID=\'' . $newOptionID . '\',qtnID=\'' . $newQtnID . '\',quotaID=\'' . $newQuotaID . '\',opertion=\'' . $opertion . '\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\' ';
		$DB->query($SQL);
	}
}

$newRelArray = array();
$RSQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\'  ORDER BY relationID ASC ';
$RResult = $DB->query($RSQL);

while ($RRow = $DB->queryArray($RResult)) {
	if ($RRow['relationMode'] == 1) {
		$SQL = ' INSERT INTO ' . RELATION_TABLE . ' SET relationDefine=\'' . $RRow['relationDefine'] . '\',relationMode=\'' . $RRow['relationMode'] . '\',relationNum=\'' . $RRow['relationNum'] . '\',opertion=\'' . $RRow['opertion'] . '\',surveyID=\'' . $newSurveyID . '\' ';
		$DB->query($SQL);
		$newRelationID = $DB->_GetInsertID();
		$newRelArray[$RRow['relationID']] = $newRelationID;
	}
	else {
		$theRelQtnId = $newQtnArray[$RRow['questionID']];
		$theRelOptionId = $theRelLabelId = $theRelSubQtId = 0;
		$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $RRow['questionID'] . '\' ';
		$QtRow = $DB->queryFirstRow($SQL);

		switch ($QtRow['questionType']) {
		case 1:
		case 18:
			$theRelOptionId = $yesNoArray[$RRow['optionID']];
			break;

		case 2:
		case 24:
			if ($RRow['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else {
				$theRelOptionId = $radioArray[$RRow['optionID']];
			}

			break;

		case 3:
			if ($RRow['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else if ($RRow['optionID'] == 99999) {
				$theRelOptionId = 99999;
			}
			else {
				$theRelOptionId = $checkboxArray[$RRow['optionID']];
			}

			break;

		case 4:
			break;

		case 6:
			$theRelSubQtId = $rangeArray[$RRow['qtnID']];
			break;

		case 7:
			$theRelSubQtId = $rangeArray[$RRow['qtnID']];
			$theRelOptionId = $answerArray[$RRow['optionID']];
			break;

		case 15:
			$theRelOptionId = $rankArray[$RRow['optionID']];
			break;

		case 16:
			$theRelOptionId = $weightValueArray[$RRow['optionID']];
			break;

		case 17:
			if ($RRow['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else if ($RRow['optionID'] == 99999) {
				$theRelOptionId = 99999;
			}
			else {
				$theRelOptionId = $checkboxArray[$RRow['optionID']];
			}

			break;

		case 19:
		case 21:
		case 22:
			if ($RRow['qtnID'] == 0) {
				$theRelSubQtId = 0;
			}
			else {
				$theRelSubQtId = $checkboxArray[$RRow['qtnID']];
			}

			break;

		case 23:
			$theRelOptionId = $combTextValueArray[$RRow['optionID']];
			break;

		case 25:
			$theRelOptionId = $checkboxArray[$RRow['optionID']];
			break;

		case 26:
			$theRelSubQtId = $rangeArray[$RRow['qtnID']];
			$theRelOptionId = $multiTextLabelArray[$RRow['optionID']];
			break;

		case 27:
			$theRelOptionId = $multiTextOptionArray[$RRow['optionID']];
			$theRelLabelId = $multiTextLabelArray[$RRow['labelID']];
			break;

		case 28:
			if ($RRow['qtnID'] == 0) {
				$theRelSubQtId = 0;
			}
			else {
				$theRelSubQtId = $checkboxArray[$RRow['qtnID']];
			}

			$theRelOptionId = $answerArray[$RRow['optionID']];
			break;
		}

		$SQL = ' INSERT INTO ' . RELATION_TABLE . ' SET relationDefine=\'' . $RRow['relationDefine'] . '\',relationMode=\'' . $RRow['relationMode'] . '\',questionID=\'' . $theRelQtnId . '\',optionID=\'' . $theRelOptionId . '\',qtnID=\'' . $theRelSubQtId . '\',labelID=\'' . $theRelLabelId . '\',opertion=\'' . $RRow['opertion'] . '\',surveyID=\'' . $newSurveyID . '\' ';
		$DB->query($SQL);
		$newRelationID = $DB->_GetInsertID();
		$newRelArray[$RRow['relationID']] = $newRelationID;
	}

	$SQL = ' SELECT * FROM ' . RELATION_LIST_TABLE . ' WHERE surveyID = \'' . $lastSurveyID . '\' AND relationID = \'' . $RRow['relationID'] . '\' ORDER BY optionOptionID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$theRelQtnId = $newQtnArray[$Row['questionID']];
		$theRelOptionId = $theRelLabelId = $theRelSubQtId = 0;
		$SQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ';
		$QtRow = $DB->queryFirstRow($SQL);

		switch ($QtRow['questionType']) {
		case 1:
		case 18:
			$theRelOptionId = $yesNoArray[$Row['optionID']];
			break;

		case 2:
		case 24:
			if ($Row['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else {
				$theRelOptionId = $radioArray[$Row['optionID']];
			}

			break;

		case 3:
			if ($Row['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else if ($Row['optionID'] == 99999) {
				$theRelOptionId = 99999;
			}
			else {
				$theRelOptionId = $checkboxArray[$Row['optionID']];
			}

			break;

		case 4:
			break;

		case 6:
			$theRelSubQtId = $rangeArray[$Row['qtnID']];
			break;

		case 7:
			$theRelSubQtId = $rangeArray[$Row['qtnID']];
			$theRelOptionId = $answerArray[$Row['optionID']];
			break;

		case 15:
			$theRelOptionId = $rankArray[$Row['optionID']];
			break;

		case 16:
			$theRelOptionId = $weightValueArray[$Row['optionID']];
			break;

		case 17:
			if ($Row['optionID'] == 0) {
				$theRelOptionId = 0;
			}
			else if ($Row['optionID'] == 99999) {
				$theRelOptionId = 99999;
			}
			else {
				$theRelOptionId = $checkboxArray[$Row['optionID']];
			}

			break;

		case 19:
		case 21:
		case 22:
			if ($Row['qtnID'] == 0) {
				$theRelSubQtId = 0;
			}
			else {
				$theRelSubQtId = $checkboxArray[$Row['qtnID']];
			}

			break;

		case 23:
			$theRelOptionId = $combTextValueArray[$Row['optionID']];
			break;

		case 25:
			$theRelOptionId = $checkboxArray[$Row['optionID']];
			break;

		case 26:
			$theRelSubQtId = $rangeArray[$Row['qtnID']];
			$theRelOptionId = $multiTextLabelArray[$Row['optionID']];
			break;

		case 27:
			$theRelOptionId = $multiTextOptionArray[$Row['optionID']];
			$theRelLabelId = $multiTextLabelArray[$Row['labelID']];
			break;

		case 28:
			if ($Row['qtnID'] == 0) {
				$theRelSubQtId = 0;
			}
			else {
				$theRelSubQtId = $checkboxArray[$Row['qtnID']];
			}

			$theRelOptionId = $answerArray[$Row['optionID']];
			break;
		}

		$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',relationID=\'' . $newRelationID . '\',questionID=\'' . $theRelQtnId . '\',optionID=\'' . $theRelOptionId . '\',qtnID=\'' . $theRelSubQtId . '\',labelID=\'' . $theRelLabelId . '\',opertion=\'' . $Row['opertion'] . '\',optionOptionID=\'' . $Row['optionOptionID'] . '\' ';
		$DB->query($SQL);
	}
}

$SQL = ' SELECT questionID,weight FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $newSurveyID . '\' AND requiredMode = 2 AND questionType=\'30\' ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$uSQL = ' UPDATE ' . QUESTION_TABLE . ' SET weight = \'' . $newRelArray[$Row['weight']] . '\' WHERE questionID = \'' . $Row['questionID'] . '\' ';
	$DB->query($uSQL);
}

unset($newQtnArray);
unset($yesNoArray);
unset($radioArray);
unset($checkboxArray);
unset($rangeArray);
unset($answerArray);
unset($newRelArray);
unset($combTextValueArray);
unset($weightValueArray);
unset($multiTextOptionArray);
unset($multiTextLabelArray);

?>
