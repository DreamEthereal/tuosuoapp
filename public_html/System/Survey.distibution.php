<?php
//dezend by http://www.yunlu99.com/
function qcopyqtnnewname($qtnName)
{
	global $DB;
	global $newQtnArray;
	global $combTextValueArray;
	global $radioArray;
	global $checkboxArray;
	global $multiTextLabelArray;

	if (preg_match_all('/\\[Answer_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];
			$_obf_0_s6Dw__ = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '\' ';
			$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
			if (!$_obf_wwEsIQ__ || !in_array($_obf_wwEsIQ__['questionType'], array(2, 3, 4, 17, 18, 23, 24, 25))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				if (!isset($newQtnArray[$_obf_3MDEGZq_8RQ_]) || ($newQtnArray[$_obf_3MDEGZq_8RQ_] == '')) {
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
				}
				else if (count($_obf_uFZJM35XMJTetkA_) == 2) {
					if (!in_array($_obf_wwEsIQ__['questionType'], array(2, 4, 17, 18, 24))) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
				else {
					switch ($_obf_wwEsIQ__['questionType']) {
					case '2':
					case '3':
						if ($_obf_uFZJM35XMJTetkA_[2] != 0) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_0]';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '23':
						if (!isset($combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $combTextValueArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '24':
						if (!isset($radioArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($radioArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $radioArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;

					case '25':
						if (!isset($checkboxArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($checkboxArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
						}
						else {
							$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Answer_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $checkboxArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
							$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
						}

						break;
					}
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
	}
	else if (preg_match_all('/\\[Kish_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];
			$_obf_0_s6Dw__ = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '\' ';
			$_obf_wwEsIQ__ = $DB->queryFirstRow($_obf_0_s6Dw__);
			if (!$_obf_wwEsIQ__ || !in_array($_obf_wwEsIQ__['questionType'], array(23, 27))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				if (!isset($newQtnArray[$_obf_3MDEGZq_8RQ_]) || ($newQtnArray[$_obf_3MDEGZq_8RQ_] == '')) {
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
				}
				else if (count($_obf_uFZJM35XMJTetkA_) == 2) {
					if ($_obf_wwEsIQ__['questionType'] != '23') {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Kish_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
				else if ($_obf_wwEsIQ__['questionType'] == '27') {
					if (!isset($multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]]) || ($multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]] == '')) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$_obf_gGwcWrlK_u1IYJjEi2Y_ = '[Kish_' . $newQtnArray[$_obf_3MDEGZq_8RQ_] . '_' . $multiTextLabelArray[$_obf_uFZJM35XMJTetkA_[2]] . ']';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], $_obf_gGwcWrlK_u1IYJjEi2Y_, $_obf_fvhCn51TdKIfNw__);
					}
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
	}
	else {
		return $qtnName;
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theme = ($SurveyListArray['theme'] == '' ? 'Standard' : $SurveyListArray['theme']);
$uitheme = ($SurveyListArray['uitheme'] == '' ? 'Phone' : $SurveyListArray['uitheme']);
$SurSQL = ' INSERT INTO ' . SURVEY_TABLE . ' SET surveyTitle=\'The New Survey\',lang=\'' . $SurveyListArray['lang'] . '\',isPublic=\'' . $SurveyListArray['isPublic'] . '\',status=0,theme=\'' . $theme . '\',panelID=\'1\' ';
$DB->query($SurSQL);
$newSurveyID = $DB->_GetInsertID();
$oldSurveyID = $SurveyListArray['surveyID'];
$SurveyListArray = qbr2nl(qquoteconvertstring($SurveyListArray));
$SurveyListArray = qaddslashes($SurveyListArray, 1);
$SurSQL = ' UPDATE ' . SURVEY_TABLE . ' SET lang=\'' . $SurveyListArray['lang'] . '\',isPublic=\'' . $SurveyListArray['isPublic'] . '\',tokenCode=\'' . $SurveyListArray['tokenCode'] . '\',status=0,theme=\'' . $theme . '\',uitheme=\'' . $uitheme . '\',panelID=\'1\',surveyTitle=\'' . $SurveyListArray['surveyTitle'] . '\',surveySubTitle=\'' . $SurveyListArray['surveySubTitle'] . '\',surveyMaxOption=\'' . $SurveyListArray['surveyMaxOption'] . '\',surveyInfo=\'' . $SurveyListArray['surveyInfo'] . '\',exitMode=\'2\',exitPage=\'' . $SurveyListArray['exitPage'] . '\',exitTitleHead=\'' . $SurveyListArray['exitTitleHead'] . '\',exitTextBody=\'' . $SurveyListArray['exitTextBody'] . '\',apiURL=\'' . $SurveyListArray['apiURL'] . '\',apiVarName=\'' . $SurveyListArray['apiVarName'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',isCheckIP=\'' . $SurveyListArray['isCheckIP'] . '\',maxIpTime=\'' . $SurveyListArray['maxIpTime'] . '\',isAllowIP = \'0\',maxResponseNum=\'' . $SurveyListArray['maxResponseNum'] . '\',isProperty=\'' . $SurveyListArray['isProperty'] . '\',isPreStep=\'' . $SurveyListArray['isPreStep'] . '\',isProcessBar=\'' . $SurveyListArray['isProcessBar'] . '\',isViewResult=\'' . $SurveyListArray['isViewResult'] . '\',isShowResultBut=\'' . $SurveyListArray['isShowResultBut'] . '\',isDisRefresh=\'' . $SurveyListArray['isDisRefresh'] . '\',isAllData=\'' . $SurveyListArray['isAllData'] . '\',mainAttribute=\'\',isViewAuthData=\'' . $SurveyListArray['isViewAuthData'] . '\',isViewAuthInfo=\'' . $SurveyListArray['isViewAuthInfo'] . '\',isFailReApp=\'' . $SurveyListArray['isFailReApp'] . '\',isExportData=\'' . $SurveyListArray['isExportData'] . '\',isImportData=\'' . $SurveyListArray['isImportData'] . '\',isDeleteData=\'' . $SurveyListArray['isDeleteData'] . '\',isModiData=\'' . $SurveyListArray['isModiData'] . '\',isOneData=\'' . $SurveyListArray['isOneData'] . '\',isGeolocation=\'' . $SurveyListArray['isGeolocation'] . '\',isOnline0Auth=\'' . $SurveyListArray['isOnline0Auth'] . '\',isOnline0View=\'' . $SurveyListArray['isOnline0View'] . '\',isSecureImage=\'' . $SurveyListArray['isSecureImage'] . '\',isWaiting=\'' . $SurveyListArray['isWaiting'] . '\',waitingTime=\'' . $SurveyListArray['waitingTime'] . '\',isRecord=\'' . $SurveyListArray['isRecord'] . '\',isLowRecord=\'' . $SurveyListArray['isLowRecord'] . '\',isUploadRec=\'' . $SurveyListArray['isUploadRec'] . '\',isPanelFlag=\'' . $SurveyListArray['isPanelFlag'] . '\',isLimited=\'' . $SurveyListArray['isLimited'] . '\',limitedTime=\'' . $SurveyListArray['limitedTime'] . '\',isCheckStat0=\'' . $SurveyListArray['isCheckStat0'] . '\',isRelZero=\'' . $SurveyListArray['isRelZero'] . '\',isRateIndex=\'' . $SurveyListArray['isRateIndex'] . '\',isOfflineModi=\'' . $SurveyListArray['isOfflineModi'] . '\',isOfflineDele=\'' . $SurveyListArray['isOfflineDele'] . '\',isGpsEnable=\'' . $SurveyListArray['isGpsEnable'] . '\',isFingerDrawing=\'' . $SurveyListArray['isFingerDrawing'] . '\',dbSize=\'' . $SurveyListArray['dbSize'] . '\',beginTime=\'' . $SurveyListArray['beginTime'] . '\',endTime=\'' . $SurveyListArray['endTime'] . '\',joinTime=\'' . time() . '\',updateTime=\'\',projectType=2,projectOwner=0,surveyName=\'diaocha_' . $newSurveyID . '\',AppId=\'' . $SurveyListArray['AppId'] . '\',AppSecret=\'' . $SurveyListArray['AppSecret'] . '\',isOnlyWeChat=\'' . $SurveyListArray['isOnlyWeChat'] . '\',getChatUserInfo=\'' . $SurveyListArray['getChatUserInfo'] . '\',getChatUserMode=\'' . $SurveyListArray['getChatUserMode'] . '\' ';

if (trim($SurveyListArray['msgImage']) != '') {
	$destinationPath = $Config['absolutenessPath'] . '/PerUserData/logo/';
	createdir($destinationPath);

	if (file_exists($tmpDataFilePath . trim($SurveyListArray['msgImage']))) {
		$newFileNameList = explode('.', trim($SurveyListArray['msgImage']));
		$newFileName = $newFileNameList[0] . $newSurveyID . '.' . $newFileNameList[1];

		if (copy($tmpDataFilePath . trim($SurveyListArray['msgImage']), $destinationPath . $newFileName)) {
			$SurSQL .= ' ,msgImage = \'' . $newFileName . '\' ';
		}
	}
}

$SurSQL .= ' WHERE surveyID=\'' . $newSurveyID . '\' ';
$DB->query($SurSQL);
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

foreach ($QtnListArray as $questionID => $QutRow) {
	$QutRow = qbr2nl(qquoteconvertstring($QutRow));
	$QutRow = qaddslashes($QutRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . qcopyqtnnewname($QutRow['questionName']) . '\',questionNotes=\'' . qcopyqtnnewname(trim($QutRow['questionNotes'])) . '\',surveyID=\'' . $newSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'' . $QutRow['questionType'] . '\',isPublic=\'' . $QutRow['isPublic'] . '\',isRequired=\'' . $QutRow['isRequired'] . '\',isRandOptions=\'' . $QutRow['isRandOptions'] . '\',isCheckType=\'' . $QutRow['isCheckType'] . '\',isSelect=\'' . $QutRow['isSelect'] . '\',isLogicAnd=\'' . $QutRow['isLogicAnd'] . '\',isColArrange=\'' . $QutRow['isColArrange'] . '\',perRowCol=\'' . $QutRow['perRowCol'] . '\',isHaveOther=\'' . $QutRow['isHaveOther'] . '\',isHaveUnkown=\'' . $QutRow['isHaveUnkown'] . '\',requiredMode=\'' . $QutRow['requiredMode'] . '\',otherText=\'' . $QutRow['otherText'] . '\',isHaveWhy=\'' . $QutRow['isHaveWhy'] . '\',isContInvalid=\'' . $QutRow['isContInvalid'] . '\',contInvalidValue=\'' . $QutRow['contInvalidValue'] . '\',minOption=\'' . $QutRow['minOption'] . '\',maxOption=\'' . $QutRow['maxOption'] . '\',unitText=\'' . $QutRow['unitText'] . '\',rows=\'' . $QutRow['rows'] . '\',length=\'' . $QutRow['length'] . '\',minSize=\'' . $QutRow['minSize'] . '\',maxSize=\'' . $QutRow['maxSize'] . '\',allowType=\'' . $QutRow['allowType'] . '\',optionCoeff=\'' . $QutRow['optionCoeff'] . '\',optionValue=\'' . $QutRow['optionValue'] . '\',otherCode=\'' . $QutRow['otherCode'] . '\',isUnkown=\'' . $QutRow['isUnkown'] . '\',isNA=\'' . $QutRow['isNA'] . '\',negCode=\'' . $QutRow['negCode'] . '\',isNeg=\'' . $QutRow['isNeg'] . '\',DSNConnect=\'' . $QutRow['DSNConnect'] . '\',DSNSQL=\'' . $QutRow['DSNSQL'] . '\',DSNUser=\'' . $QutRow['DSNUser'] . '\',DSNPassword=\'' . $QutRow['DSNPassword'] . '\',hiddenVarName=\'' . $QutRow['hiddenVarName'] . '\',hiddenFromSession=\'' . $QutRow['hiddenFromSession'] . '\',weight=\'' . $QutRow['weight'] . '\',startScale=\'' . $QutRow['startScale'] . '\',endScale=\'' . $QutRow['endScale'] . '\',baseID=\'' . $QutRow['baseID'] . '\',alias=\'' . $QutRow['alias'] . '\',coeffMode=\'' . $QutRow['coeffMode'] . '\',coeffTotal=\'' . $QutRow['coeffTotal'] . '\',coeffZeroMargin=\'' . $QutRow['coeffZeroMargin'] . '\',coeffFullMargin=\'' . $QutRow['coeffFullMargin'] . '\',skipMode=\'' . $QutRow['skipMode'] . '\',negCoeff=\'' . $QutRow['negCoeff'] . '\',negValue=\'' . $QutRow['negValue'] . '\',orderByID=\'' . $QutRow['orderByID'] . '\' ';
	$DB->query($SQL);
	$newQuestionID = $DB->_GetInsertID();
	$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID = \'' . $newQuestionID . '\' WHERE questionID = \'' . $newQuestionID . '\' ';
	$DB->query($SQL);
	$newQtnArray[$QutRow['questionID']] = $newQuestionID;

	if ($QutRow['questionType'] != '8') {
		$MoudleName = $Module[$QutRow['questionType']];
		$theNewSurveyID = $newSurveyID;

		switch ($QutRow['questionType']) {
		case '5':
		case '11':
		case '12':
		case '13':
		case '14':
		case '17':
		case '20':
		case '21':
		case '22':
		case '30':
			require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.copy.inc.php';
			break;

		default:
			require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.distibution.inc.php';
			break;
		}
	}
}

foreach ($CondListArray as $questionID => $baseRow) {
	$theNewQtnID = $newQtnArray[$questionID];

	foreach ($baseRow as $condOnID => $condRow) {
		$theNewCondOnID = $newQtnArray[$condOnID];

		foreach ($condRow as $listRow) {
			$newOptionID = $newQtnID = 0;
			$opertion = $listRow[2];
			$logicValueIsAnd = $listRow[3];
			$logicMode = ($listRow[4] == 0 ? 1 : $listRow[4]);

			switch ($QtnListArray[$condOnID]['questionType']) {
			case '1':
				$newOptionID = $yesNoArray[$listRow[1]];
				break;

			case '2':
			case '24':
				if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else {
					$newOptionID = $radioArray[$listRow[1]];
				}

				break;

			case '3':
				if ($logicMode == '2') {
					$newOptionID = $listRow[1];
					$logicValueIsAnd = 0;
				}
				else if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else if ($listRow[1] == 99999) {
					$newOptionID = 99999;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '17':
				if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else if ($listRow[1] == 99999) {
					$newOptionID = 99999;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '25':
				if ($logicMode == '2') {
					$newOptionID = $listRow[1];
					$logicValueIsAnd = 0;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '6':
			case '7':
				$newQtnID = $rangeArray[$listRow[0]];
				$newOptionID = $answerArray[$listRow[1]];
				break;

			case '19':
			case '28':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $checkboxArray[$listRow[0]];
				}

				$newOptionID = $answerArray[$listRow[1]];
				break;

			case '30':
			case '4':
				$newOptionID = $listRow[1];
				break;

			case '16':
				$newQtnID = $weightValueArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '23':
				$newQtnID = $combTextValueArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '10':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $rankArray[$listRow[0]];
				}

				$newOptionID = $listRow[1];
				break;

			case '15':
				$newQtnID = $rankArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '20':
			case '21':
			case '22':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $checkboxArray[$listRow[0]];
				}

				$newOptionID = $listRow[1];
				break;

			case '31':
				$newQtnID = $listRow[0];
				$newOptionID = $listRow[1];
				break;
			}

			$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',questionID=\'' . $theNewQtnID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $theNewCondOnID . '\',optionID=\'' . $newOptionID . '\',qtnID=\'' . $newQtnID . '\',opertion=\'' . $opertion . '\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\' ';
			$DB->query($SQL);
		}
	}
}

foreach ($OassListArray as $questionID => $theOassListArray) {
	$thisNewQtnID = $newQtnArray[$questionID];

	foreach ($theOassListArray as $optionID => $theOptionOassListArray) {
		$theNewQtnID = $theNewOptionID = 0;

		switch ($QtnListArray[$questionID]['questionType']) {
		case '2':
		case '24':
			if ($optionID == 0) {
				$theNewOptionID = 0;
			}
			else {
				$theNewOptionID = $radioArray[$optionID];
			}

			break;

		case '3':
			if ($optionID == 0) {
				$theNewOptionID = 0;
			}
			else if ($optionID == 99999) {
				$theNewOptionID = 99999;
			}
			else {
				$theNewOptionID = $checkboxArray[$optionID];
			}

			break;

		case '25':
			$theNewOptionID = $checkboxArray[$optionID];
			break;

		case '6':
		case '7':
		case '19':
		case '28':
			$theNewOptionID = $answerArray[$optionID];
			break;
		}

		foreach ($theOptionOassListArray as $condOnID => $condRow) {
			$theNewCondOnID = $newQtnArray[$condOnID];

			foreach ($condRow as $listRow) {
				$newOptionID = $newQtnID = 0;
				$opertion = $listRow[2];
				$logicValueIsAnd = $listRow[3];
				$logicMode = ($listRow[4] == 0 ? 1 : $listRow[4]);

				switch ($QtnListArray[$condOnID]['questionType']) {
				case '1':
					$newOptionID = $yesNoArray[$listRow[1]];
					break;

				case '2':
				case '24':
					if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else {
						$newOptionID = $radioArray[$listRow[1]];
					}

					break;

				case '3':
					if ($logicMode == '2') {
						$newOptionID = $listRow[1];
						$logicValueIsAnd = 0;
					}
					else if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else if ($listRow[1] == 99999) {
						$newOptionID = 99999;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '17':
					if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else if ($listRow[1] == 99999) {
						$newOptionID = 99999;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '25':
					if ($logicMode == '2') {
						$newOptionID = $listRow[1];
						$logicValueIsAnd = 0;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '6':
				case '7':
					$newQtnID = $rangeArray[$listRow[0]];
					$newOptionID = $answerArray[$listRow[1]];
					break;

				case '19':
				case '28':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $checkboxArray[$listRow[0]];
					}

					$newOptionID = $answerArray[$listRow[1]];
					break;

				case '30':
				case '4':
					$newOptionID = $listRow[1];
					break;

				case '16':
					$newQtnID = $weightValueArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '23':
					$newQtnID = $combTextValueArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '10':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $rankArray[$listRow[0]];
					}

					$newOptionID = $listRow[1];
					break;

				case '15':
					$newQtnID = $rankArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '20':
				case '21':
				case '22':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $checkboxArray[$listRow[0]];
					}

					$newOptionID = $listRow[1];
					break;

				case '31':
					$newQtnID = $listRow[0];
					$newOptionID = $listRow[1];
					break;
				}

				$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $thisNewQtnID . '\',qtnID=\'' . $theNewQtnID . '\',optionID=\'' . $theNewOptionID . '\',condOnID=\'' . $theNewCondOnID . '\',condOptionID=\'' . $newOptionID . '\',condQtnID=\'' . $newQtnID . '\',opertion=\'' . $opertion . '\',assType=\'2\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\'';
				$DB->query($SQL);
			}
		}
	}
}

foreach ($QassListArray as $questionID => $theQassListArray) {
	$thisNewQtnID = $newQtnArray[$questionID];

	foreach ($theQassListArray as $thisQtnID => $theQtnQassListArray) {
		$theNewQtnID = $theNewOptionID = 0;

		switch ($QtnListArray[$questionID]['questionType']) {
		case '6':
		case '7':
		case '26':
			$theNewQtnID = $rangeArray[$thisQtnID];
			break;

		case '27':
			$theNewQtnID = $multiTextOptionArray[$thisQtnID];
			break;

		case '10':
			if ($thisQtnID == 0) {
				$theNewQtnID = 0;
			}
			else {
				$theNewQtnID = $rankArray[$thisQtnID];
			}

			break;

		case '15':
			$theNewQtnID = $rankArray[$thisQtnID];
			break;

		case '16':
			$theNewQtnID = $weightValueArray[$thisQtnID];
			break;
		}

		foreach ($theQtnQassListArray as $condOnID => $condRow) {
			$theNewCondOnID = $newQtnArray[$condOnID];

			foreach ($condRow as $listRow) {
				$newOptionID = $newQtnID = 0;
				$opertion = $listRow[2];
				$logicValueIsAnd = $listRow[3];
				$logicMode = ($listRow[4] == 0 ? 1 : $listRow[4]);

				switch ($QtnListArray[$condOnID]['questionType']) {
				case '1':
					$newOptionID = $yesNoArray[$listRow[1]];
					break;

				case '2':
				case '24':
					if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else {
						$newOptionID = $radioArray[$listRow[1]];
					}

					break;

				case '3':
					if ($logicMode == '2') {
						$newOptionID = $listRow[1];
						$logicValueIsAnd = 0;
					}
					else if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else if ($listRow[1] == 99999) {
						$newOptionID = 99999;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '17':
					if ($listRow[1] == 0) {
						$newOptionID = 0;
					}
					else if ($listRow[1] == 99999) {
						$newOptionID = 99999;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '25':
					if ($logicMode == '2') {
						$newOptionID = $listRow[1];
						$logicValueIsAnd = 0;
					}
					else {
						$newOptionID = $checkboxArray[$listRow[1]];
					}

					break;

				case '6':
				case '7':
					$newQtnID = $rangeArray[$listRow[0]];
					$newOptionID = $answerArray[$listRow[1]];
					break;

				case '19':
				case '28':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $checkboxArray[$listRow[0]];
					}

					$newOptionID = $answerArray[$listRow[1]];
					break;

				case '30':
				case '4':
					$newOptionID = $listRow[1];
					break;

				case '16':
					$newQtnID = $weightValueArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '23':
					$newQtnID = $combTextValueArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '10':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $rankArray[$listRow[0]];
					}

					$newOptionID = $listRow[1];
					break;

				case '15':
					$newQtnID = $rankArray[$listRow[0]];
					$newOptionID = $listRow[1];
					break;

				case '20':
				case '21':
				case '22':
					if ($listRow[0] == 0) {
						$newQtnID = 0;
					}
					else {
						$newQtnID = $checkboxArray[$listRow[0]];
					}

					$newOptionID = $listRow[1];
					break;

				case '31':
					$newQtnID = $listRow[0];
					$newOptionID = $listRow[1];
					break;
				}

				$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $thisNewQtnID . '\',qtnID=\'' . $theNewQtnID . '\',optionID=\'' . $theNewOptionID . '\',condOnID=\'' . $theNewCondOnID . '\',condOptionID=\'' . $newOptionID . '\',condQtnID=\'' . $newQtnID . '\',opertion=\'' . $opertion . '\',assType=\'1\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\'';
				$DB->query($SQL);
			}
		}
	}
}

foreach ($QuotaNumArray as $quotaID => $QuotaRow) {
	$QuotaRow = qbr2nl(qquoteconvertstring($QuotaRow));
	$QuotaRow = qaddslashes($QuotaRow, 1);
	$SQL = ' INSERT INTO ' . QUOTA_TABLE . ' SET quotaName=\'' . $QuotaRow['quotaName'] . '\',quotaText=\'' . $QuotaRow['quotaText'] . '\',quotaNum=\'' . $QuotaRow['quotaNum'] . '\',surveyID=\'' . $newSurveyID . '\' ';
	$DB->query($SQL);
	$newQuotaID = $DB->_GetInsertID();

	foreach ($QuotaListArray[$quotaID] as $condOnID => $baseRow) {
		$theNewCondOnID = $newQtnArray[$condOnID];

		foreach ($baseRow as $listRow) {
			$newOptionID = $newQtnID = 0;
			$opertion = $listRow[2];
			$logicValueIsAnd = $listRow[3];
			$logicMode = ($listRow[4] == 0 ? 1 : $listRow[4]);

			switch ($QtnListArray[$condOnID]['questionType']) {
			case '1':
				$newOptionID = $yesNoArray[$listRow[1]];
				break;

			case '2':
			case '24':
				if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else {
					$newOptionID = $radioArray[$listRow[1]];
				}

				break;

			case '3':
				if ($logicMode == 2) {
					$newOptionID = $listRow[1];
					$logicValueIsAnd = 0;
				}
				else if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else if ($listRow[1] == 99999) {
					$newOptionID = 99999;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '17':
				if ($listRow[1] == 0) {
					$newOptionID = 0;
				}
				else if ($listRow[1] == 99999) {
					$newOptionID = 99999;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '25':
				if ($logicMode == 2) {
					$newOptionID = $listRow[1];
					$logicValueIsAnd = 0;
				}
				else {
					$newOptionID = $checkboxArray[$listRow[1]];
				}

				break;

			case '6':
			case '7':
				$newQtnID = $rangeArray[$listRow[0]];
				$newOptionID = $answerArray[$listRow[1]];
				break;

			case '19':
			case '28':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $checkboxArray[$listRow[0]];
				}

				$newOptionID = $answerArray[$listRow[1]];
				break;

			case '30':
			case '4':
				$newOptionID = $listRow[1];
				break;

			case '16':
				$newQtnID = $weightValueArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '23':
				$newQtnID = $combTextValueArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '10':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $rankArray[$listRow[0]];
				}

				$newOptionID = $listRow[1];
				break;

			case '15':
				$newQtnID = $rankArray[$listRow[0]];
				$newOptionID = $listRow[1];
				break;

			case '20':
			case '21':
			case '22':
				if ($listRow[0] == 0) {
					$newQtnID = 0;
				}
				else {
					$newQtnID = $checkboxArray[$listRow[0]];
				}

				$newOptionID = $listRow[1];
				break;

			case '31':
				$newQtnID = $listRow[0];
				$newOptionID = $listRow[1];
				break;
			}

			$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $newSurveyID . '\',questionID=0,administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $theNewCondOnID . '\',optionID=\'' . $newOptionID . '\',qtnID=\'' . $newQtnID . '\',quotaID=\'' . $newQuotaID . '\',opertion=\'' . $opertion . '\',logicValueIsAnd=\'' . $logicValueIsAnd . '\',logicMode=\'' . $logicMode . '\' ';
			$DB->query($SQL);
		}
	}
}

$newRelArray = array();

foreach ($ValueRelArray as $RRow) {
	if ($RRow['relationMode'] == 1) {
		$SQL = ' INSERT INTO ' . RELATION_TABLE . ' SET relationDefine=\'' . $RRow['relationDefine'] . '\',relationMode=\'' . $RRow['relationMode'] . '\',relationNum=\'' . $RRow['relationNum'] . '\',opertion=\'' . $RRow['opertion'] . '\',surveyID=\'' . $newSurveyID . '\' ';
		$DB->query($SQL);
		$newRelationID = $DB->_GetInsertID();
		$newRelArray[$RRow['relationID']] = $newRelationID;
	}
	else {
		$theRelQtnId = $newQtnArray[$RRow['questionID']];
		$theRelOptionId = $theRelLabelId = $theRelSubQtId = 0;

		switch ($QtnListArray[$RRow['questionID']]['questionType']) {
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

	foreach ($RRow['list'] as $Row) {
		$theRelQtnId = $newQtnArray[$Row['questionID']];
		$theRelOptionId = $theRelLabelId = $theRelSubQtId = 0;

		switch ($QtnListArray[$Row['questionID']]['questionType']) {
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
