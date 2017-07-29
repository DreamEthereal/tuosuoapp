<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowQtnAssociate.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$SQL = ' SELECT surveyTitle,status,surveyName FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->replace('isDesignMode', 'none');
	$EnableQCoreClass->replace('logicURL', 'ShowSurveyLogic.php' . $thisURLStr);
	$EnableQCoreClass->replace('optAssURL', 'ShowOptAssociateList.php' . $thisURLStr);
	$EnableQCoreClass->replace('qtnAssURL', 'ShowQtnAssociateList.php' . $thisURLStr);
}
else {
	$EnableQCoreClass->replace('isDesignMode', '');
	$EnableQCoreClass->replace('logicURL', 'DesignSurvey.php' . $thisURLStr . '&DO=Logic');
	$EnableQCoreClass->replace('optAssURL', 'ShowOptAssociate.php' . $thisURLStr);
	$EnableQCoreClass->replace('qtnAssURL', 'ShowQtnAssociate.php' . $thisURLStr);
}

$EnableQCoreClass->replace('questionListURL', 'DesignSurvey.php' . $thisURLStr);
$EnableQCoreClass->replace('quotaURL', 'ShowSurveyQuota.php' . $thisURLStr);
$EnableQCoreClass->replace('relationURL', 'ShowValueRelation.php' . $thisURLStr);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);

if ($_GET['DO'] == 'ImportQtnAss') {
	require 'Survey.import.qtnass.php';
}

if ($_GET['DOes'] == 'DeleLogic') {
	if (isset($_GET['questionID']) && ($_GET['questionID'] != 0) && ($_GET['questionID'] != '')) {
		$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID = \'' . $_GET['questionID'] . '\' AND assType=1 AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$DB->query($SQL);
	}

	$questionName = qnohtmltag(stripslashes($_GET['questionName']), 1);
	writetolog($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName);
	_showsucceed($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName, $thisProg);
}

if ($_POST['DeleteSurveyLogicSubmit']) {
	if (is_array($_POST['questionID']) && !empty($_POST['questionID'])) {
		$questionIDLists = join(',', $_POST['questionID']);
		if (($questionIDLists != '0') && ($questionIDLists != '')) {
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID IN (' . $questionIDLists . ') AND assType=1 AND surveyID=\'' . $_POST['surveyID'] . '\' ';
			$DB->query($SQL);
			writetolog($lang['delete_logic_list'] . ':' . $Sur_G_Row['surveyTitle']);
		}
	}

	_showsucceed($lang['delete_logic_list'] . ':' . $Sur_G_Row['surveyTitle'], $thisProg);
}

if ($_GET['DO'] == 'DeleQLogic') {
	$SQL = ' DELETE FROM  ' . ASSOCIATE_TABLE . ' WHERE associateID = \'' . $_GET['associateID'] . '\' ';
	$DB->query($SQL);
	$questionName = qnohtmltag(stripslashes($_GET['questionName']), 1);
	writetolog($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName);
	_showsucceed($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName, 'DesignSurvey.php' . $thisURLStr . '&DOes=EditLogic&questionID=' . $_GET['questionID'] . '&questionName=' . $_GET['questionName']);
}

if (($_POST['Action'] == 'AddLogicSubmit') || ($_POST['Action'] == 'EditLogicSubmit')) {
	if (isset($_POST['logicMode']) && ($_POST['logicMode'] != 0)) {
		switch ($_POST['logicMode']) {
		case '1':
			foreach ($_POST['base_qtnID'] as $qtnID) {
				foreach ($_POST['optionID_1'] as $condOptionID) {
					$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND condOptionID=\'' . $condOptionID . '\' AND assType=1 LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condOptionID=\'' . $condOptionID . '\',opertion=\'' . $_POST['opertion_1'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\',logicMode=1 ';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '2':
			foreach ($_POST['base_qtnID'] as $qtnID) {
				$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND condOptionID=\'' . $_POST['optionID_2'] . '\' AND assType=1 LIMIT 0,1 ';
				$isHaveRow = $DB->queryFirstRow($SQL);

				if (!$isHaveRow) {
					$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condOptionID=\'' . $_POST['optionID_2'] . '\',opertion=\'' . $_POST['opertion_2'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'0\',logicMode=2 ';
					$DB->query($SQL);
				}
			}

			break;
		}
	}
	else {
		if (isset($_POST['questionID']) && is_array($_POST['base_qtnID'])) {
			foreach ($_POST['base_qtnID'] as $qtnID) {
				if (is_array($_POST['optionID']) && !empty($_POST['optionID'])) {
					foreach ($_POST['optionID'] as $condOptionID) {
						if (is_array($_POST['qtnID']) && !empty($_POST['qtnID'])) {
							foreach ($_POST['qtnID'] as $condQtnID) {
								$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND condQtnID=\'' . $condQtnID . '\' AND condOptionID=\'' . $condOptionID . '\' AND assType=1 LIMIT 0,1 ';
								$isHaveRow = $DB->queryFirstRow($SQL);

								if (!$isHaveRow) {
									$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condQtnID=\'' . $condQtnID . '\',condOptionID=\'' . $condOptionID . '\',opertion=\'' . $_POST['opertion'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
									$DB->query($SQL);
								}
							}
						}
						else {
							$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND condOptionID=\'' . $condOptionID . '\' AND assType=1 LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condOptionID=\'' . $condOptionID . '\',opertion=\'' . $_POST['opertion'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
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
							$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND condQtnID=\'' . $_POST['qtnID'] . '\' AND condOptionID=\'' . $nodeID . '\' AND assType=1 LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condQtnID=\'' . $_POST['qtnID'] . '\',condOptionID=\'' . $nodeID . '\',opertion=\'' . $_POST['opertion'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
								$DB->query($SQL);
							}
						}
					}
				}
				else {
					$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND qtnID = \'' . $qtnID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND assType=1 LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',qtnID = \'' . $qtnID . '\',condOnID=\'' . $_POST['condOnID'] . '\',condOptionID=\'' . $_POST['optionID'] . '\',opertion=\'' . $_POST['opertion'] . '\',assType=1,administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
		}
	}

	if ($_POST['Action'] == 'AddLogicSubmit') {
		writetolog($lang['setting_logic'] . ':' . $Sur_G_Row['surveyTitle']);
		_showmessage($lang['setting_logic'] . ':' . $Sur_G_Row['surveyTitle'], true);
	}

	if ($_POST['Action'] == 'EditLogicSubmit') {
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['edit_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName);
		_showmessage($lang['edit_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName, true);
	}
}

if ($_GET['DO'] == 'AddSingleQtnAss') {
	$EnableQCoreClass->setTemplateFile('SurveyLogicNewFile', 'SurveyQtnAssociateNew.html');
	$SQL = ' SELECT questionID,questionType,orderByID,questionName FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$questionName = qnohtmltag($Row['questionName'], 1);
	$EnableQCoreClass->replace('questionName', $questionName);
	$EnableQCoreClass->replace('questionID', $_GET['questionID']);
	$base_qtnID_list = '';

	switch ($Row['questionType']) {
	case '6':
	case '7':
	case '26':
	case '27':
		$OptionSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_optionID ASC ';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$optionName = qnohtmltag($OptionRow['optionName'], 1);
			$base_qtnID_list .= '<option value=' . $OptionRow['question_range_optionID'] . '>' . $optionName . '</option>';
		}

		break;

	case '10':
	case '15':
	case '16':
		$OptionSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_rankID ASC ';
		$OptionResult = $DB->query($OptionSQL);

		while ($OptionRow = $DB->queryArray($OptionResult)) {
			$optionName = qnohtmltag($OptionRow['optionName'], 1);
			$base_qtnID_list .= '<option value=' . $OptionRow['question_rankID'] . '>' . $optionName . '</option>';
		}

		break;
	}

	$EnableQCoreClass->replace('base_qtnID_list', $base_qtnID_list);
	$BaseSQL = ' SELECT questionID,questionName,questionType,isCheckType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND orderByID < \'' . $Row['orderByID'] . '\' AND questionType IN (1,2,3,6,7,24,25,30,19,28,17,4,23,10,15,16,20,21,22,31) ORDER BY orderByID ASC  ';
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
	$EnableQCoreClass->replace('Action', 'EditLogicSubmit');
	$EnableQCoreClass->parse('SurveyLogicNew', 'SurveyLogicNewFile');
	$EnableQCoreClass->output('SurveyLogicNew');
}

if ($_GET['DO'] == 'AddQtnAssNew') {
	$EnableQCoreClass->setTemplateFile('SurveyLogicAddFile', 'SurveyQtnAssociateAdd.html');
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionType IN (6,7,10,15,16,26,27) ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$questionIDList = '';

	while ($Row = $DB->queryArray($Result)) {
		$questionName = qnohtmltag($Row['questionName'], 1);
		$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('questionIDList', $questionIDList);
	$EnableQCoreClass->replace('m_optionID', '');
	$EnableQCoreClass->replace('m_optionName', $lang['pls_select']);
	$EnableQCoreClass->replace('m_condOnID', '');
	$EnableQCoreClass->replace('m_condOnName', $lang['pls_select']);
	$EnableQCoreClass->replace('Action', 'AddLogicSubmit');
	$EnableQCoreClass->parse('SurveyLogicAdd', 'SurveyLogicAddFile');
	$EnableQCoreClass->output('SurveyLogicAdd');
}

$EnableQCoreClass->setTemplateFile('SurveyAssFile', 'SurveyQtnAssociate.html');
$EnableQCoreClass->set_CycBlock('SurveyAssFile', 'LOGIC', 'logic');
$EnableQCoreClass->replace('logic', '');
$EnableQCoreClass->replace('newLogicURL', $thisProg . '&DO=AddQtnAssNew');
$EnableQCoreClass->replace('importLogicURL', $thisProg . '&DO=ImportQtnAss');
$theLogicNum = 0;
$Question = array();
$SQL = ' SELECT questionID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND assType=1 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$Question[] = $Row['questionID'];
}

if (!empty($Question)) {
	$QuestionList = implode(',', $Question);
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND questionID IN (' . $QuestionList . ') ORDER BY orderByID ASC ';
}
else {
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID = 0 ';
}

$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$questionName = qnohtmltag($Row['questionName'], 1);
	$EnableQCoreClass->replace('questionName', $questionName . '&nbsp;[' . $lang['question_type_' . $Row['questionType']] . ']');
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$theQtnID = $Row['questionID'];
	$theQuestionType = $Row['questionType'];
	require 'ShowQtnAssociate.inc.php';
	$EnableQCoreClass->replace('editURL', 'DesignSurvey.php' . $thisURLStr . '&DOes=EditLogic&questionID=' . $Row['questionID'] . '&questionName=' . urlencode($Row['questionName']));
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&DOes=DeleLogic&questionID=' . $Row['questionID'] . '&questionName=' . urlencode($Row['questionName']));
	$EnableQCoreClass->parse('logic', 'LOGIC', true);
}

$EnableQCoreClass->replace('recNum', $theLogicNum);
$EnableQCoreClass->parse('SurveyAss', 'SurveyAssFile');
$EnableQCoreClass->output('SurveyAss');

?>
