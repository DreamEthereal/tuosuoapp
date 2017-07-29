<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowSurveyQuota.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$SQL = ' SELECT surveyTitle,status,surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
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

$EnableQCoreClass->replace('quotaURL', $thisProg);
$EnableQCoreClass->replace('relationURL', 'ShowValueRelation.php' . $thisURLStr);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);

if ($_GET['DO'] == 'ImportQuota') {
	require 'Survey.import.quota.php';
}

if ($_GET['DOes'] == 'DeleQuota') {
	if (isset($_GET['quotaID']) && ($_GET['quotaID'] != 0) && ($_GET['quotaID'] != '')) {
		$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE quotaID = \'' . $_GET['quotaID'] . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . QUOTA_TABLE . ' WHERE quotaID = \'' . $_GET['quotaID'] . '\' ';
		$DB->query($SQL);
		$theSID = $_GET['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	$quotaName = qnohtmltag($_GET['quotaName'], 1);
	writetolog($lang['delete_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName);
	_showsucceed($lang['delete_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName, $thisProg);
}

if ($_GET['DOes'] == 'DeleQQuota') {
	$SQL = ' DELETE FROM  ' . CONDITIONS_TABLE . ' WHERE conditionsID = \'' . $_GET['conditionsID'] . '\' ';
	$DB->query($SQL);
	$theSID = $_GET['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
	$quotaName = qnohtmltag($_GET['quotaName'], 1);
	writetolog($lang['delete_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName);
	_showsucceed($lang['delete_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName, $thisProg . '&DOes=EditQuota&quotaID=' . $_GET['quotaID'] . '&quotaNum=' . $_GET['quotaNum'] . '&quotaName=' . $_GET['quotaName']);
}

if ($_POST['Action'] == 'EditQuotaSubmit') {
	if (isset($_POST['logicMode']) && ($_POST['logicMode'] != 0)) {
		switch ($_POST['logicMode']) {
		case '1':
			foreach ($_POST['optionID_1'] as $optionID) {
				$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
				$isHaveRow = $DB->queryFirstRow($SQL);

				if (!$isHaveRow) {
					$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',quotaID=\'' . $_POST['quotaID'] . '\',opertion=\'' . $_POST['opertion_1'] . '\' ,logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\',logicMode=1';
					$DB->query($SQL);
				}
			}

			break;

		case '2':
			$optionID = $_POST['optionID_2'];
			$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
			$isHaveRow = $DB->queryFirstRow($SQL);

			if (!$isHaveRow) {
				$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',quotaID=\'' . $_POST['quotaID'] . '\',opertion=\'' . $_POST['opertion_2'] . '\' ,logicValueIsAnd = \'0\',logicMode=2';
				$DB->query($SQL);
			}

			break;
		}
	}
	else {
		if (is_array($_POST['optionID']) && !empty($_POST['optionID'])) {
			foreach ($_POST['optionID'] as $optionID) {
				if (is_array($_POST['qtnID']) && !empty($_POST['qtnID'])) {
					foreach ($_POST['qtnID'] as $qtnID) {
						$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND qtnID=\'' . $qtnID . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',quotaID=\'' . $_POST['quotaID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',qtnID=\'' . $qtnID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
							$DB->query($SQL);
						}
					}
				}
				else {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',quotaID=\'' . $_POST['quotaID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
		}
		else if (isset($_POST['qtnID'])) {
			$theQueryValue = explode(',', $_POST['nodeValue']);
			$theValidValue = array();

			foreach ($theQueryValue as $theValue) {
				$theValue = trim($theValue);
				$hSQL = ' SELECT nodeID FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $_POST['condOnID'] . '\' AND nodeID = \'' . $theValue . '\' AND level = \'' . $_POST['qtnID'] . '\' AND flag = 0 LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if ($hRow) {
					$theValidValue[] = $theValue;
				}
			}

			if (count($theValidValue) != 0) {
				foreach ($theValidValue as $nodeID) {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND qtnID=\'' . $_POST['qtnID'] . '\' AND optionID=\'' . $nodeID . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',quotaID=\'' . $_POST['quotaID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',qtnID=\'' . $_POST['qtnID'] . '\',optionID=\'' . $nodeID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
		}
		else {
			$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'0\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND quotaID=\'' . $_POST['quotaID'] . '\' LIMIT 0,1 ';
			$isHaveRow = $DB->queryFirstRow($SQL);

			if (!$isHaveRow) {
				$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'0\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $_POST['optionID'] . '\',quotaID=\'' . $_POST['quotaID'] . '\',opertion=\'' . $_POST['opertion'] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	$theSID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
	$quotaName = qnohtmltag($_POST['quotaName'], 1);
	writetolog($lang['edit_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName);
	_showmessage($lang['edit_quota'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $quotaName, true);
}

if ($_GET['DOes'] == 'AddQtnQuotaNew') {
	$EnableQCoreClass->setTemplateFile('SurveyQuotaNewFile', 'SurveyQuotaNew.html');
	$BaseSQL = ' SELECT questionID,questionName,questionType,isCheckType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionType IN (1,2,3,6,7,24,25,30,19,28,17,4,23,10,15,16,20,21,22,31) ORDER BY orderByID ASC  ';
	$BaseResult = $DB->query($BaseSQL);

	while ($BaseRow = $DB->queryArray($BaseResult)) {
		$questionName = qnohtmltag($BaseRow['questionName'], 1);

		switch ($BaseRow['questionType']) {
		case '4':
			if ($BaseRow['isCheckType'] == '4') {
				$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			}

			break;

		case '23':
			$hSQL = ' SELECT isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $BaseRow['questionID'] . '\' AND isCheckType =\'4\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			}

			break;

		default:
			$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			break;
		}
	}

	$EnableQCoreClass->replace('baseList', $BaseList);
	$EnableQCoreClass->replace('m_optionID', '');
	$EnableQCoreClass->replace('m_optionName', $lang['pls_select']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$quotaName = qnohtmltag($_GET['quotaName'], 1);
	$EnableQCoreClass->replace('quotaName', $quotaName);
	$EnableQCoreClass->replace('Action', 'EditQuotaSubmit');
	$EnableQCoreClass->replace('quotaID', $_GET['quotaID']);
	$EnableQCoreClass->parse('SurveyQuotaNew', 'SurveyQuotaNewFile');
	$EnableQCoreClass->output('SurveyQuotaNew');
}

if ($_GET['DOes'] == 'EditQuota') {
	$EnableQCoreClass->setTemplateFile('SurveyEditQuotaFile', 'SurveyQuotaEdit.html');
	$EnableQCoreClass->set_CycBlock('SurveyEditQuotaFile', 'QUOTA', 'quota');
	$EnableQCoreClass->replace('quota', '');
	$quotaName = qnohtmltag($_GET['quotaName'], 1);
	$EnableQCoreClass->replace('quotaName', $lang['quota_name'] . $quotaName . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $lang['quota_num'] . $_GET['quotaNum']);
	$ConSQL = ' SELECT a.*,b.questionName,b.questionType,b.otherText,b.allowType,b.baseID,b.unitText FROM ' . CONDITIONS_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.questionID = 0 AND a.quotaID=\'' . $_GET['quotaID'] . '\' AND a.condOnID=b.questionID AND a.surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY a.condOnID ASC,a.qtnID ASC, a.optionID ASC  ';
	$ConResult = $DB->query($ConSQL);
	$recordCount = $DB->_getNumRows($ConResult);
	$EnableQCoreClass->replace('recNum', $recordCount);

	while ($ConRow = $DB->queryArray($ConResult)) {
		$conName = qnohtmltag($ConRow['questionName'], 1);

		switch ($ConRow['questionType']) {
		case '1':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['optionID'] . '\' ';
			break;

		case '2':
		case '24':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID=\'' . $ConRow['optionID'] . '\' ';
			break;

		case '3':
		case '25':
			if ($ConRow['logicMode'] == 1) {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['optionID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT 1=1';
			}

			break;

		case '6':
		case '7':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID=\'' . $ConRow['qtnID'] . '\' ';
			break;

		case '19':
		case '28':
		case '20':
		case '21':
		case '22':
			if ($ConRow['qtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['qtnID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
			}

			break;

		case '17':
			switch ($ConRow['optionID']) {
			case '0':
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['baseID'] . '\' ';
				break;

			case '99999':
				$OptionSQL = ' SELECT allowType as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
				break;

			default:
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID=\'' . $ConRow['optionID'] . '\' AND questionID = \'' . $ConRow['baseID'] . '\' ';
				break;
			}

			break;

		case '4':
		case '30':
			$OptionSQL = ' SELECT 1=1';
			break;

		case '10':
			if ($ConRow['qtnID'] == 0) {
				$OptionSQL = ' SELECT otherText as optionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $ConRow['condOnID'] . '\' ';
			}
			else {
				$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['qtnID'] . '\' ';
			}

			break;

		case '15':
		case '16':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID=\'' . $ConRow['qtnID'] . '\' ';
			break;

		case '23':
			$OptionSQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID=\'' . $ConRow['qtnID'] . '\' ';
			break;

		case '31':
			$theUnitText = explode('#', $ConRow['unitText']);
			$theOptionName = $theUnitText[$ConRow['qtnID'] - 1];
			$OptionSQL = ' SELECT 1=1';
			break;
		}

		$OptionRow = $DB->queryFirstRow($OptionSQL);

		if (in_array($ConRow['questionType'], array(4, 23, 10, 15, 16, 20, 21, 22))) {
			switch ($ConRow['opertion']) {
			case 1:
				$opertion = '==';
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

			switch ($ConRow['questionType']) {
			case '4':
				$EnableQCoreClass->replace('conName', $conName);
				$EnableQCoreClass->replace('opertion', $opertion);
				$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
				break;

			case '10':
			case '15':
			case '16':
			case '20':
			case '21':
			case '22':
			case '23':
				$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('opertion', $opertion);
				$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
				break;
			}
		}
		else {
			switch ($ConRow['questionType']) {
			case '1':
			case '24':
			case '17':
				$EnableQCoreClass->replace('conName', $conName);
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
				break;

			case '2':
				$EnableQCoreClass->replace('conName', $conName);
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

				if ($ConRow['optionID'] != 0) {
					$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
				}
				else {
					$EnableQCoreClass->replace('optionName', qnohtmltag($ConRow['otherText']));
				}

				break;

			case '3':
			case '25':
				if ($ConRow['logicMode'] == 1) {
					$EnableQCoreClass->replace('conName', $conName);
					$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);

					switch ($ConRow['optionID']) {
					case '0':
						$EnableQCoreClass->replace('optionName', qnohtmltag($ConRow['otherText']));
						break;

					case '99999':
						$negText = ($ConRow['allowType'] != '' ? $ConRow['allowType'] : $lang['neg_text']);
						$EnableQCoreClass->replace('optionName', qnohtmltag($negText));
						break;

					default:
						$EnableQCoreClass->replace('optionName', qnohtmltag($OptionRow['optionName']));
						break;
					}
				}
				else {
					switch ($ConRow['opertion']) {
					case 1:
						$opertion = '==';
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

					$EnableQCoreClass->replace('conName', $conName . ' - 回复选项数量');
					$EnableQCoreClass->replace('opertion', $opertion);
					$EnableQCoreClass->replace('optionName', $ConRow['optionID']);
				}

				break;

			case '6':
			case '7':
			case '19':
			case '28':
				$AnswerSQL = ' SELECT optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE question_range_answerID =\'' . $ConRow['optionID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($OptionRow['optionName']));
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('optionName', qnohtmltag($AnswerRow['optionAnswer']));
				break;

			case '30':
				$EnableQCoreClass->replace('conName', $conName);
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['logicEqual'] : $lang['logicUnEqual']);
				$EnableQCoreClass->replace('optionName', $ConRow['optionID'] == 1 ? 'True' : 'False');
				break;

			case '31':
				$AnswerSQL = ' SELECT nodeName FROM ' . CASCADE_TABLE . ' WHERE nodeID=\'' . $ConRow['optionID'] . '\' AND questionID = \'' . $ConRow['condOnID'] . '\' ';
				$AnswerRow = $DB->queryFirstRow($AnswerSQL);
				$EnableQCoreClass->replace('conName', $conName . ' - ' . qnohtmltag($theOptionName));
				$EnableQCoreClass->replace('opertion', $ConRow['opertion'] == 1 ? $lang['selected'] : $lang['unselected']);
				$EnableQCoreClass->replace('optionName', qnohtmltag($AnswerRow['nodeName']));
				break;
			}
		}

		$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=DeleQQuota&conditionsID=' . $ConRow['conditionsID'] . '&quotaID=' . $_GET['quotaID'] . '&quotaNum=' . $_GET['quotaNum'] . '&quotaName=' . urlencode($_GET['quotaName']));
		$EnableQCoreClass->parse('quota', 'QUOTA', true);
	}

	if ($recordCount == 0) {
		$EnableQCoreClass->replace('isHaveQuota', 'none');
	}
	else {
		$EnableQCoreClass->replace('isHaveQuota', '');
		$theQuotaID = $_GET['quotaID'];
		require 'ShowQuota.inc.php';
	}

	$EnableQCoreClass->replace('newQtnQuotaURL', $thisProg . '&DOes=AddQtnQuotaNew&quotaID=' . $_GET['quotaID'] . '&quotaName=' . urlencode($_GET['quotaName']));
	$EnableQCoreClass->replace('surveyQuotaURL', $thisProg . '&DOes=EditQuota&quotaID=' . $_GET['quotaID'] . '&quotaNum=' . $_GET['quotaNum'] . '&quotaName=' . urlencode($_GET['quotaName']));
	$EnableQCoreClass->parse('SurveyEditQuota', 'SurveyEditQuotaFile');
	$EnableQCoreClass->output('SurveyEditQuota');
}

if ($_POST['Action'] == 'AddQuotaSubmit') {
	$quotaName = str_replace('\\\'', '', $_POST['quotaName']);
	$quotaName = str_replace('\\"', '', $quotaName);
	$quotaName = str_replace('\\', '', $quotaName);
	$quotaName = str_replace('&', '', $quotaName);
	$quotaText = str_replace('\\\'', '', $_POST['quotaText']);
	$quotaText = str_replace('\\"', '', $quotaText);
	$quotaText = str_replace('\\', '', $quotaText);
	$quotaText = str_replace('&', '', $quotaText);
	$SQL = ' INSERT INTO ' . QUOTA_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',quotaName=\'' . $quotaName . '\',quotaNum=\'' . $_POST['quotaNum'] . '\',quotaText=\'' . $quotaText . '\' ';
	$DB->query($SQL);
	writetolog($lang['setting_quota'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['setting_quota'] . ':' . $Sur_G_Row['surveyTitle'], true);
}

if ($_GET['DO'] == 'AddQuotaNew') {
	$EnableQCoreClass->setTemplateFile('SurveyQuotaAddFile', 'SurveyQuotaAdd.html');
	$EnableQCoreClass->replace('Action', 'AddQuotaSubmit');
	$EnableQCoreClass->parse('SurveyQuotaAdd', 'SurveyQuotaAddFile');
	$EnableQCoreClass->output('SurveyQuotaAdd');
}

if ($_POST['Action'] == 'UpdateQuotaSubmit') {
	if (is_array($_POST['quotaName'])) {
		foreach ($_POST['quotaName'] as $quotaID => $quotaName) {
			$quotaName = str_replace('\\\'', '', $quotaName);
			$quotaName = str_replace('\\"', '', $quotaName);
			$quotaName = str_replace('\\', '', $quotaName);
			$quotaName = str_replace('&', '', $quotaName);
			$quotaText = str_replace('\\\'', '', $_POST['quotaText'][$quotaID]);
			$quotaText = str_replace('\\"', '', $quotaText);
			$quotaText = str_replace('\\', '', $quotaText);
			$quotaText = str_replace('&', '', $quotaText);
			$SQL = ' UPDATE ' . QUOTA_TABLE . ' SET quotaName =\'' . $quotaName . '\',quotaText =\'' . $quotaText . '\',quotaNum=\'' . $_POST['quotaNum'][$quotaID] . '\' WHERE quotaID=\'' . $quotaID . '\' ';
			$DB->query($SQL);
		}
	}

	$theSID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
	writetolog($lang['setting_quota'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['setting_quota'] . ':' . $Sur_G_Row['surveyTitle'], true);
}

if ($_GET['DO'] == 'UpdateQuota') {
	$EnableQCoreClass->setTemplateFile('SurveyQuotaUpdateFile', 'SurveyQuotaUpdate.html');
	$EnableQCoreClass->set_CycBlock('SurveyQuotaUpdateFile', 'QUOTA', 'quota');
	$EnableQCoreClass->replace('quota', '');
	$EnableQCoreClass->set_CycBlock('SurveyQuotaUpdateFile', 'JS', 'js');
	$EnableQCoreClass->replace('js', '');
	$SQL = ' SELECT * FROM ' . QUOTA_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY quotaID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('quotaName', $Row['quotaName']);
		$EnableQCoreClass->replace('quotaText', $Row['quotaText']);
		$EnableQCoreClass->replace('quotaNum', $Row['quotaNum']);
		$EnableQCoreClass->replace('quotaID', $Row['quotaID']);
		$EnableQCoreClass->parse('quota', 'QUOTA', true);
		$EnableQCoreClass->replace('jsQuotaID', $Row['quotaID']);
		$EnableQCoreClass->parse('js', 'JS', true);
	}

	$EnableQCoreClass->parse('SurveyQuotaUpdate', 'SurveyQuotaUpdateFile');
	$EnableQCoreClass->output('SurveyQuotaUpdate');
}

$EnableQCoreClass->setTemplateFile('SurveyQuotaFile', 'SurveyQuota.html');
$EnableQCoreClass->set_CycBlock('SurveyQuotaFile', 'QUOTA', 'quota');
$EnableQCoreClass->replace('quota', '');
$EnableQCoreClass->replace('newQuotaURL', $thisProg . '&DO=AddQuotaNew');
$EnableQCoreClass->replace('updateQuotaURL', $thisProg . '&DO=UpdateQuota');
$EnableQCoreClass->replace('importQuotaURL', $thisProg . '&DO=ImportQuota');
$theLogicNum = 0;
$SQL = ' SELECT * FROM ' . QUOTA_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY quotaID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$EnableQCoreClass->replace('quotaID', $Row['quotaID']);
	$EnableQCoreClass->replace('quotaName', $Row['quotaName']);
	$EnableQCoreClass->replace('quotaNum', $Row['quotaNum']);
	$theQuotaID = $Row['quotaID'];
	require 'ShowQuota.inc.php';
	$EnableQCoreClass->replace('editURL', $thisProg . '&DOes=EditQuota&quotaID=' . $Row['quotaID'] . '&quotaNum=' . $Row['quotaNum'] . '&quotaName=' . urlencode($Row['quotaName']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=DeleQuota&quotaID=' . $Row['quotaID'] . '&quotaName=' . urlencode($Row['quotaName']));
	$EnableQCoreClass->replace('newQtnQuotaURL', $thisProg . '&DOes=AddQtnQuotaNew&quotaID=' . $Row['quotaID'] . '&quotaName=' . urlencode($Row['quotaName']));
	$EnableQCoreClass->parse('quota', 'QUOTA', true);
}

$EnableQCoreClass->replace('recNum', $theLogicNum);
$hSQL = ' SELECT * FROM ' . CONDITIONS_TABLE . ' WHERE administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND quotaID != 0 LIMIT 1 ';
$hRow = $DB->queryFirstRow($hSQL);

if ($hRow) {
	$EnableQCoreClass->replace('recHaveNum', '1');
}
else {
	$EnableQCoreClass->replace('recHaveNum', '0');
}

$EnableQCoreClass->parse('SurveyQuota', 'SurveyQuotaFile');
$EnableQCoreClass->output('SurveyQuota');

?>
