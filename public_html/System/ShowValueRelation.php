<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowValueRelation.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$SQL = ' SELECT surveyTitle,status,surveyName,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->replace('isDesignMode', 'none');
	$EnableQCoreClass->replace('logicURL', 'ShowSurveyLogic.php' . $thisURLStr);
	$EnableQCoreClass->replace('questionListURL', 'ModiSurvey.php' . $thisURLStr);
}
else {
	$EnableQCoreClass->replace('isDesignMode', '');
	$EnableQCoreClass->replace('logicURL', 'DesignSurvey.php' . $thisURLStr . '&DO=Logic');
	$EnableQCoreClass->replace('questionListURL', 'DesignSurvey.php' . $thisURLStr);
}

$EnableQCoreClass->replace('quotaURL', 'ShowSurveyQuota.php' . $thisURLStr);
$EnableQCoreClass->replace('relationURL', $thisProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);

if ($_GET['DOes'] == 'Delete') {
	if (isset($_GET['relationID']) && ($_GET['relationID'] != 0) && ($_GET['relationID'] != '')) {
		$_GET['relationID'] = (int) $_GET['relationID'];
		$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_GET['relationID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $_GET['relationID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET requiredMode=1,weight=0 WHERE weight = \'' . $_GET['relationID'] . '\' AND surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
		$DB->query($SQL);

		if ($Sur_G_Row['status'] != '0') {
			$theSID = $Sur_G_Row['surveyID'];
			require ROOT_PATH . 'Includes/MakeCache.php';
		}
	}

	writetolog($lang['delete_value_rel'] . ':' . $Sur_G_Row['surveyTitle']);
	_showsucceed($lang['delete_value_rel'] . ':' . $Sur_G_Row['surveyTitle'], $thisProg);
}

if ($_POST['Action'] == 'EditRelSubmit') {
	if ($_POST['relationDefine'] == 2) {
		$theEmptyIDValue = explode('*', $_POST['emptyId']);
		$hSQL = ' SELECT weight FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $theEmptyIDValue[1] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);
		if (($hRow['weight'] != 0) && ($hRow['weight'] != $_POST['relationID0'])) {
			_showerror('检查错误', '一致性检查错误：您选择的空题目前已有数值关联关系与其关联');
		}
	}

	$SQL = ' UPDATE ' . RELATION_TABLE . ' SET relationDefine=\'' . $_POST['relationDefine'] . '\',relationMode = \'' . $_POST['relationMode'] . '\',opertion=\'' . $_POST['opertion0'] . '\' ';

	if ($_POST['relationMode'] == 1) {
		$SQL .= ' ,relationNum =\'' . $_POST['relationNum'] . '\' ';
	}
	else {
		$theQtnIDValue0 = explode('*', $_POST['relationID']);
		$SQL .= ' ,questionID =\'' . $theQtnIDValue0[1] . '\',optionID =\'' . $theQtnIDValue0[2] . '\',labelID =\'' . $theQtnIDValue0[3] . '\',qtnID =\'' . $theQtnIDValue0[4] . '\' ';
	}

	$SQL .= ' WHERE relationID = \'' . $_POST['relationID0'] . '\' ';
	$DB->query($SQL);

	if (trim($_POST['fieldsID'][1]) != '') {
		$SQL = ' UPDATE ' . RELATION_LIST_TABLE . ' SET opertion=0 ';
		$theQtnIDValue = explode('*', $_POST['fieldsID'][1]);
		$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=1 ';
		$DB->query($SQL);
	}

	$i = 2;

	for (; $i <= sizeof($_POST['fieldsID']); $i++) {
		if ((trim($_POST['opertion'][$i]) != '') && (trim($_POST['fieldsID'][$i]) != '')) {
			$HSQL = ' SELECT listID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$HRow = $DB->queryFirstRow($HSQL);

			if ($HRow) {
				$theQtnIDValue = explode('*', $_POST['fieldsID'][$i]);

				if ($_POST['relationMode'] == 1) {
					$SQL = ' UPDATE ' . RELATION_LIST_TABLE . ' SET opertion=\'' . $_POST['opertion'][$i] . '\' ';
					$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else if ($theQtnIDValue[0] <= $theQtnIDValue0[0]) {
					$SQL = ' UPDATE ' . RELATION_LIST_TABLE . ' SET opertion=\'' . $_POST['opertion'][$i] . '\' ';
					$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
			else {
				$theQtnIDValue = explode('*', $_POST['fieldsID'][$i]);

				if ($_POST['relationMode'] == 1) {
					$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $_POST['relationID0'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=\'' . $_POST['opertion'][$i] . '\' ';
					$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else if ($theQtnIDValue[0] <= $theQtnIDValue0[0]) {
					$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $_POST['relationID0'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=\'' . $_POST['opertion'][$i] . '\' ';
					$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
		}
		else {
			$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID=\'' . $i . '\' ';
			$DB->query($SQL);
		}
	}

	$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_POST['relationID0'] . '\' AND optionOptionID > \'' . sizeof($_POST['fieldsID']) . '\' ';
	$DB->query($SQL);

	if ($_POST['relationDefine'] == 2) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET weight = \'' . $_POST['relationID0'] . '\',requiredMode = 2 WHERE questionID = \'' . $theEmptyIDValue[1] . '\' ';
		$DB->query($SQL);

		if ($_POST['qtnId'] != $theEmptyIDValue[1]) {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET weight = 0,requiredMode = 1 WHERE questionID = \'' . $_POST['qtnId'] . '\' ';
			$DB->query($SQL);
		}
	}
	else {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET weight = 0,requiredMode = 1 WHERE weight = \'' . $_POST['relationID0'] . '\' AND surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
		$DB->query($SQL);
	}

	if ($Sur_G_Row['status'] != '0') {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	writetolog($lang['modi_value_rel'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['modi_value_rel'] . ':' . $Sur_G_Row['surveyTitle'], true);
}

if ($_GET['DOes'] == 'EditRelNew') {
	$EnableQCoreClass->setTemplateFile('ValueRelationEditFile', 'ValueRelationEdit.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$_GET['relationID'] = (int) $_GET['relationID'];
	$EnableQCoreClass->replace('relationID', $_GET['relationID']);
	$emptyNameList = '';
	$SQL = ' SELECT questionID,questionName,orderByID,weight FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND requiredMode = 2 AND questionType=\'30\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$qtnId = 0;

	while ($Row = $DB->queryArray($Result)) {
		if ($Row['weight'] == $_GET['relationID']) {
			$emptyNameList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\' selected>' . qnohtmltag($Row['questionName'], 1) . '</option>' . "\n" . '';
			$qtnId = $Row['questionID'];
		}
		else {
			$emptyNameList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('emptyNameList', $emptyNameList);
	$EnableQCoreClass->replace('qtnId', $qtnId);
	$RSQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $_GET['relationID'] . '\' ';
	$RRow = $DB->queryFirstRow($RSQL);
	$EnableQCoreClass->replace('relationDefine_' . $RRow['relationDefine'], 'checked');
	$EnableQCoreClass->replace('opertion0_' . $RRow['opertion'], 'selected');

	if ($RRow['relationMode'] == 1) {
		$EnableQCoreClass->replace('relationMode_1', 'checked');
		$EnableQCoreClass->replace('relationMode_2', '');
		$EnableQCoreClass->replace('relationNum', $RRow['relationNum']);
	}
	else {
		$EnableQCoreClass->replace('relationMode_2', 'checked');
		$EnableQCoreClass->replace('relationMode_1', '');
		$EnableQCoreClass->replace('relationNum', '');
	}

	$SQL = ' SELECT questionID,questionName,questionType,isCheckType,orderByID,isSelect,isHaveOther,isNeg,otherText,allowType,baseID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType IN (1,2,3,4,6,7,15,16,17,18,19,21,22,23,24,25,26,27,28) ORDER BY orderByID ASC  ';
	$Result = $DB->query($SQL);
	$releationList = array();

	while ($Row = $DB->queryArray($Result)) {
		switch ($Row['questionType']) {
		case 1:
		case 2:
		case 24:
			$questionName = qnohtmltag($Row['questionName'], 1);
			$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			break;

		case 3:
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			if (($Row['isSelect'] != '1') && ($Row['isHaveOther'] == '1')) {
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($Row['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			if ($Row['isNeg'] == '1') {
				$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*99999*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			break;

		case 4:
			if ($Row['isCheckType'] == 4) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}
			else {
				continue;
			}

			break;

		case 6:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $OptionRow['question_range_optionID']] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			break;

		case 7:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $OptionRow['question_range_optionID']] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
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
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ZRow['question_rankID'] . '*0*0'] = $questionName . ' - ' . $optionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			break;

		case 17:
			if ($Row['isSelect'] == 1) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}
			else {
				$bSQL = ' SELECT isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}

				if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}

				if ($Row['isCheckType'] == '1') {
					$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*99999*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}
			}

			break;

		case 18:
			if ($Row['isSelect'] == 1) {
				$cSQL = ' SELECT question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_yesnoID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_yesnoID'] . '*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}
			}
			else {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
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
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $cRow['question_checkboxID']] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			break;

		case 23:
			$ZSQL = ' SELECT question_yesnoID,optionName,isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$ZResult = $DB->query($ZSQL);

			while ($ZRow = $DB->queryArray($ZResult)) {
				if ($ZRow['isCheckType'] == 4) {
					$questionName = qnohtmltag($Row['questionName'], 1);
					$optionName = qnohtmltag($ZRow['optionName'], 1);
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ZRow['question_yesnoID'] . '*0*0'] = $questionName . ' - ' . $optionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
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
				$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
			}

			break;

		case 26:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_labelID,optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_labelID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_labelID'] . '*0*' . $OptionRow['question_range_optionID']] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionLabel'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
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
						$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ORow['question_range_optionID'] . '*' . $ZRow['question_range_labelID'] . '*0'] = $questionName . ' - ' . $optionName . ' - ' . $optionLabel . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
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
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $cRow['question_checkboxID']] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList[$Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*0'] = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')';
				}
			}

			break;
		}
	}

	$releationListText = '';

	foreach ($releationList as $theQtnId => $theQtnName) {
		$theQtnIdArray = explode('*', $theQtnId);

		if (($RRow['questionID'] . '*' . $RRow['optionID'] . '*' . $RRow['labelID'] . '*' . $RRow['qtnID']) == $theQtnIdArray[1] . '*' . $theQtnIdArray[2] . '*' . $theQtnIdArray[3] . '*' . $theQtnIdArray[4]) {
			$releationListText .= '<option value=\'' . $theQtnId . '\' selected>' . $theQtnName . '</option>' . "\n" . '';
		}
		else {
			$releationListText .= '<option value=\'' . $theQtnId . '\'>' . $theQtnName . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('releationList', $releationListText);
	$EnableQCoreClass->set_CycBlock('ValueRelationEditFile', 'OPTION', 'option');
	$EnableQCoreClass->replace('option', '');
	$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID=\'' . $_GET['relationID'] . '\' ';
	$MaxRow = $DB->queryFirstRow($SQL);
	$i = 1;

	for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
		$EnableQCoreClass->replace('optionOrderID', $i);

		if ($i == 1) {
			$EnableQCoreClass->replace('isFristOne', 'none');
		}
		else {
			$EnableQCoreClass->replace('isFristOne', '');
		}

		$TSQL = ' SELECT * FROM ' . RELATION_LIST_TABLE . ' WHERE relationID=\'' . $_GET['relationID'] . '\' AND optionOptionID = \'' . $i . '\' ';
		$TRow = $DB->queryFirstRow($TSQL);
		$fieldsIDListText = '';

		foreach ($releationList as $theQtnId => $theQtnName) {
			$theQtnIdArray = explode('*', $theQtnId);

			if (($TRow['questionID'] . '*' . $TRow['optionID'] . '*' . $TRow['labelID'] . '*' . $TRow['qtnID']) == $theQtnIdArray[1] . '*' . $theQtnIdArray[2] . '*' . $theQtnIdArray[3] . '*' . $theQtnIdArray[4]) {
				$fieldsIDListText .= '<option value=\'' . $theQtnId . '\' selected>' . $theQtnName . '</option>' . "\n" . '';
			}
			else {
				$fieldsIDListText .= '<option value=\'' . $theQtnId . '\'>' . $theQtnName . '</option>' . "\n" . '';
			}
		}

		$EnableQCoreClass->replace('fieldsIDList', $fieldsIDListText);
		$j = 1;

		for (; $j <= 4; $j++) {
			$EnableQCoreClass->replace('opertion_' . $j, '');
		}

		$EnableQCoreClass->replace('opertion_' . $TRow['opertion'], 'selected');
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->parse('ValueRelationEdit', 'ValueRelationEditFile');
	$EnableQCoreClass->output('ValueRelationEdit');
}

if ($_POST['Action'] == 'AddRelSubmit') {
	if ($_POST['relationDefine'] == 2) {
		$theEmptyIDValue = explode('*', $_POST['emptyId']);
		$hSQL = ' SELECT weight FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $theEmptyIDValue[1] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);

		if ($hRow['weight'] != 0) {
			_showerror('检查错误', '一致性检查错误：您选择的空题目前已有数值关联关系与其关联');
		}
	}

	$SQL = ' INSERT INTO ' . RELATION_TABLE . ' SET relationDefine=\'' . $_POST['relationDefine'] . '\',relationMode = \'' . $_POST['relationMode'] . '\',opertion=\'' . $_POST['opertion0'] . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';

	if ($_POST['relationMode'] == 1) {
		$SQL .= ' ,relationNum =\'' . $_POST['relationNum'] . '\' ';
	}
	else {
		$theQtnIDValue0 = explode('*', $_POST['relationID']);
		$SQL .= ' ,questionID =\'' . $theQtnIDValue0[1] . '\',optionID =\'' . $theQtnIDValue0[2] . '\',labelID =\'' . $theQtnIDValue0[3] . '\',qtnID =\'' . $theQtnIDValue0[4] . '\' ';

		if ($_POST['relationDefine'] == 2) {
			if ($theQtnIDValue0[0] < $theEmptyIDValue[0]) {
				_showerror('检查错误', '检查错误：运算至空题条件：空题不能在运算结果的回复值之后');
			}
		}
	}

	$DB->query($SQL);
	$lastRelationID = $DB->_GetInsertID();
	$theOpertionArray = array();

	if (trim($_POST['fieldsID'][1]) != '') {
		$theQtnIDValue = explode('*', $_POST['fieldsID'][1]);

		if ($_POST['relationMode'] == 1) {
			$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $lastRelationID . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=0 ';
			$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=1 ';
			$DB->query($SQL);
			$theOpertionArray[] = $theQtnIDValue[0];
		}
		else if ($theQtnIDValue[0] <= $theQtnIDValue0[0]) {
			$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $lastRelationID . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=0 ';
			$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=1 ';
			$DB->query($SQL);
		}
		else {
			$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $lastRelationID . '\' ';
			$DB->query($SQL);
			_showerror('检查错误', '检查错误：数值运算条件：第1个条件不能在运算结果的回复值之后');
		}
	}

	$i = 2;

	for (; $i <= sizeof($_POST['fieldsID']); $i++) {
		if ((trim($_POST['opertion'][$i]) != '') && (trim($_POST['fieldsID'][$i]) != '')) {
			$theQtnIDValue = explode('*', $_POST['fieldsID'][$i]);

			if ($_POST['relationMode'] == 1) {
				$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $lastRelationID . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=\'' . $_POST['opertion'][$i] . '\' ';
				$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
				$theOpertionArray[] = $theQtnIDValue[0];
			}
			else if ($theQtnIDValue[0] <= $theQtnIDValue0[0]) {
				$SQL = ' INSERT INTO ' . RELATION_LIST_TABLE . ' SET relationID =\'' . $lastRelationID . '\',surveyID=\'' . $_POST['surveyID'] . '\',opertion=\'' . $_POST['opertion'][$i] . '\' ';
				$SQL .= ' ,questionID =\'' . $theQtnIDValue[1] . '\',optionID =\'' . $theQtnIDValue[2] . '\',labelID =\'' . $theQtnIDValue[3] . '\',qtnID =\'' . $theQtnIDValue[4] . '\',optionOptionID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
		}
	}

	if (($_POST['relationDefine'] == 2) && ($_POST['relationMode'] == 1)) {
		rsort($theOpertionArray);

		if ($theOpertionArray[0] < $theEmptyIDValue[0]) {
			$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $lastRelationID . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $lastRelationID . '\' ';
			$DB->query($SQL);
			_showerror('检查错误', '检查错误：运算至空题条件：空题不能在所有的数值运算条件之后');
		}
	}

	if ($_POST['relationDefine'] == 2) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET weight = \'' . $lastRelationID . '\' WHERE questionID = \'' . $theEmptyIDValue[1] . '\' ';
		$DB->query($SQL);
	}

	if ($Sur_G_Row['status'] != '0') {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	writetolog($lang['setting_value_rel'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['setting_value_rel'] . ':' . $Sur_G_Row['surveyTitle'], true);
}

if ($_GET['DO'] == 'AddRelNew') {
	$EnableQCoreClass->setTemplateFile('ValueRelationAddFile', 'ValueRelationAdd.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('optionOrderID', 1);
	$emptyNameList = '';
	$SQL = ' SELECT questionID,questionName,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND requiredMode = 2 AND questionType=\'30\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$emptyNameList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('emptyNameList', $emptyNameList);
	$SQL = ' SELECT questionID,questionName,questionType,isCheckType,orderByID,isSelect,isHaveOther,isNeg,otherText,allowType,baseID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType IN (1,2,3,4,6,7,15,16,17,18,19,21,22,23,24,25,26,27,28) ORDER BY orderByID ASC  ';
	$Result = $DB->query($SQL);
	$releationList = '';

	while ($Row = $DB->queryArray($Result)) {
		switch ($Row['questionType']) {
		case 1:
		case 2:
		case 24:
			$questionName = qnohtmltag($Row['questionName'], 1);
			$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			break;

		case 3:
			$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$cResult = $DB->query($cSQL);

			while ($cRow = $DB->queryArray($cResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			if (($Row['isSelect'] != '1') && ($Row['isHaveOther'] == '1')) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($Row['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			if ($Row['isNeg'] == '1') {
				$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*99999\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		case 4:
			if ($Row['isCheckType'] == 4) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}
			else {
				continue;
			}

			break;

		case 6:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		case 7:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}
			}

			break;

		case 15:
		case 16:
			$OptionSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_rankID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $OptionRow['question_rankID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		case 17:
			if ($Row['isSelect'] == 1) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}
			else {
				$bSQL = ' SELECT isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$cSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}

				if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}

				if ($Row['isCheckType'] == '1') {
					$negText = ($Row['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($Row['allowType'], 1));
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*99999\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . $negText . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}
			}

			break;

		case 18:
			if ($Row['isSelect'] == 1) {
				$cSQL = ' SELECT question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_yesnoID ASC ';
				$cResult = $DB->query($cSQL);

				while ($cRow = $DB->queryArray($cResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_yesnoID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}
			}
			else {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
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
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*0*0*0\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		case 23:
			$ZSQL = ' SELECT question_yesnoID,optionName,isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID = \'' . $Row['questionID'] . '\' ORDER BY optionOptionID ASC ';
			$ZResult = $DB->query($ZSQL);

			while ($ZRow = $DB->queryArray($ZResult)) {
				if ($ZRow['isCheckType'] == 4) {
					$questionName = qnohtmltag($Row['questionName'], 1);
					$optionName = qnohtmltag($ZRow['optionName'], 1);
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ZRow['question_yesnoID'] . '\'>' . $questionName . ' - ' . $optionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
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
				$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		case 26:
			$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
			$OptionResult = $DB->query($OptionSQL);

			while ($OptionRow = $DB->queryArray($OptionResult)) {
				$AnswerSQL = ' SELECT question_range_labelID,optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_labelID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_labelID'] . '*0*' . $OptionRow['question_range_optionID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptionRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionLabel'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
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
						$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $ORow['question_range_optionID'] . '*' . $ZRow['question_range_labelID'] . '\'>' . $questionName . ' - ' . $optionName . ' - ' . $optionLabel . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
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
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*' . $cRow['question_checkboxID'] . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($cRow['optionName'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}
			}

			if (($bRow['isSelect'] != '1') && ($bRow['isHaveOther'] == '1')) {
				$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$releationList .= '<option value=\'' . $Row['orderByID'] . '*' . $Row['questionID'] . '*' . $AnswerRow['question_range_answerID'] . '*0*0' . '\'>' . qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($bRow['otherText'], 1) . ' - ' . qnohtmltag($AnswerRow['optionAnswer'], 1) . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
				}
			}

			break;
		}
	}

	$EnableQCoreClass->replace('releationList', $releationList);
	$EnableQCoreClass->replace('fieldsIDList', $releationList);
	$EnableQCoreClass->parse('ValueRelationAdd', 'ValueRelationAddFile');
	$EnableQCoreClass->output('ValueRelationAdd');
}

$EnableQCoreClass->setTemplateFile('ValueRelationFile', 'ValueRelation.html');
$EnableQCoreClass->set_CycBlock('ValueRelationFile', 'REL0', 'rel0');
$EnableQCoreClass->replace('rel0', '');
$EnableQCoreClass->replace('newRelationURL', $thisProg . '&DO=AddRelNew');
$theLogicNum = 0;
$SQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY relationID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$EnableQCoreClass->replace('relationID', $Row['relationID']);
	$EnableQCoreClass->replace('relationDefine', $Row['relationDefine'] != 1 ? '空题' : '标准');

	switch ($Row['opertion']) {
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

	if ($Row['relationMode'] == 1) {
		$EnableQCoreClass->replace('relationList', $opertion . '&nbsp;&nbsp;' . $Row['relationNum']);
	}
	else {
		$QSQL = ' SELECT questionName,questionType,allowType,otherText,baseID,isSelect FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $Row['questionID'] . '\' ';
		$QRow = $DB->queryFirstRow($QSQL);

		switch ($QRow['questionType']) {
		case 1:
		case 2:
		case 24:
		case 4:
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 3:
			switch ($Row['optionID']) {
			case '0':
				$optionName = qnohtmltag($QRow['otherText'], 1);
				break;

			case '99999':
				$optionName = ($QRow['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($QRow['allowType'], 1));
				break;

			default:
				$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $Row['optionID'] . '\' ';
				$ZRow = $DB->queryFirstRow($ZSQL);
				$optionName = qnohtmltag($ZRow['optionName'], 1);
				break;
			}

			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
			break;

		case 6:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $Row['qtnID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 7:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $Row['qtnID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$ASQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID = \'' . $Row['optionID'] . '\' ';
			$ARow = $DB->queryFirstRow($ASQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($ARow['optionAnswer'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 15:
		case 16:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID = \'' . $Row['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 17:
			if ($QRow['isSelect'] == 1) {
				$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
			}
			else {
				switch ($Row['optionID']) {
				case '0':
					$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
					$bRow = $DB->queryFirstRow($bSQL);
					$optionName = qnohtmltag($bRow['otherText'], 1);
					break;

				case '99999':
					$optionName = ($QRow['allowType'] == '' ? $lang['neg_text'] : qnohtmltag($QRow['allowType'], 1));
					break;

				default:
					$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $Row['optionID'] . '\' ';
					$ZRow = $DB->queryFirstRow($ZSQL);
					$optionName = qnohtmltag($ZRow['optionName'], 1);
					break;
				}

				$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
			}

			break;

		case 18:
			if ($QRow['isSelect'] == 1) {
				$ZSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID =\'' . $Row['optionID'] . '\' ';
				$ZRow = $DB->queryFirstRow($ZSQL);
				$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
			}
			else {
				$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . '<font color=blue> )</font><br/>';
			}

			break;

		case 19:
		case 21:
		case 22:
			switch ($Row['qtnID']) {
			case '0':
				$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$optionName = qnohtmltag($bRow['otherText'], 1);
				break;

			default:
				$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $Row['qtnID'] . '\' ';
				$ZRow = $DB->queryFirstRow($ZSQL);
				$optionName = qnohtmltag($ZRow['optionName'], 1);
				break;
			}

			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . '<font color=blue> )</font><br/>';
			break;

		case 23:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID = \'' . $Row['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 25:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $Row['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$optionName = qnohtmltag($ZRow['optionName'], 1);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($optionName, 1) . '<font color=blue> )</font><br/>';
			break;

		case 26:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $Row['qtnID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$ASQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID = \'' . $Row['optionID'] . '\' ';
			$ARow = $DB->queryFirstRow($ASQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($ARow['optionLabel'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 27:
			$ZSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID = \'' . $Row['optionID'] . '\' ';
			$ZRow = $DB->queryFirstRow($ZSQL);
			$LSQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID = \'' . $Row['labelID'] . '\' ';
			$LRow = $DB->queryFirstRow($LSQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($ZRow['optionName'], 1) . ' - ' . qnohtmltag($LRow['optionLabel'], 1) . '<font color=blue> )</font><br/>';
			break;

		case 28:
			switch ($Row['qtnID']) {
			case '0':
				$bSQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $QRow['baseID'] . '\' ';
				$bRow = $DB->queryFirstRow($bSQL);
				$optionName = qnohtmltag($bRow['otherText'], 1);
				break;

			default:
				$ZSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $Row['qtnID'] . '\' ';
				$ZRow = $DB->queryFirstRow($ZSQL);
				$optionName = qnohtmltag($ZRow['optionName'], 1);
				break;
			}

			$ASQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID = \'' . $Row['optionID'] . '\' ';
			$ARow = $DB->queryFirstRow($ASQL);
			$qtnList = '<font color=blue>( </font>' . qnohtmltag($QRow['questionName'], 1) . ' - ' . $optionName . ' - ' . qnohtmltag($ARow['optionAnswer'], 1) . '<font color=blue> )</font><br/>';
			break;
		}

		$EnableQCoreClass->replace('relationList', $opertion . '&nbsp;&nbsp;' . $qtnList);
	}

	$theRelID = $Row['relationID'];
	require 'ShowValueRelation.inc.php';
	$EnableQCoreClass->replace('editURL', $thisProg . '&DOes=EditRelNew&relationID=' . $Row['relationID']);
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=Delete&relationID=' . $Row['relationID']);
	$EnableQCoreClass->parse('rel0', 'REL0', true);
}

$EnableQCoreClass->replace('recNum', $theLogicNum);
$sSQL = ' SELECT surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
$sResult = $DB->query($sSQL);
$isHaveSurveyID = array();

while ($sRow = $DB->queryArray($sResult)) {
	$isHaveSurveyID[] = $sRow['surveyID'];
}

if (count($isHaveSurveyID) == 0) {
	$EnableQCoreClass->replace('recHaveNum', '0');
}
else {
	$hSQL = ' SELECT * FROM ' . RELATION_TABLE . ' WHERE surveyID IN (' . implode(',', $isHaveSurveyID) . ') LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow) {
		$EnableQCoreClass->replace('recHaveNum', '1');
	}
	else {
		$EnableQCoreClass->replace('recHaveNum', '0');
	}
}

$EnableQCoreClass->parse('ValueRelation', 'ValueRelationFile');
$EnableQCoreClass->output('ValueRelation');
exit();

?>
