<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
$_POST['surveyID'] = (int) $_POST['surveyID'];
_checkpassport('1|2|5', $_POST['surveyID']);
$SQL = ' SELECT surveyTitle,surveyName,surveyID,lang,joinTime,isPublic FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('ActionPageFile', 'QuestionAction.html');
$EnableQCoreClass->replace('survey_Title', $Row['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$EnableQCoreClass->replace('surveyLang', $lang['lang_' . $Row['lang']]);
$EnableQCoreClass->replace('qlang', $Row['lang']);
$EnableQCoreClass->replace('surveyName', $Row['surveyName']);
$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['joinTime']));
$EnableQCoreClass->replace('surveyStatus', $lang['isPublic_' . $Row['isPublic']]);
$SQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' LIMIT 0,1';
$QtnHaveRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('qtnNum', $QtnHaveRow['qtnNum']);
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$questionName = qnohtmltag($Row['questionName'], 1);
$EnableQCoreClass->replace('qtnName', $questionName);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isQtnRequire', $lang['qtn_check_yes']);
}
else {
	$EnableQCoreClass->replace('isQtnRequire', $lang['qtn_check_no']);
}

$CSQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND ( questionID=\'' . $Row['questionID'] . '\' OR condOnID = \'' . $Row['questionID'] . '\') LIMIT 0,1 ';
$CRow = $DB->queryFirstRow($CSQL);
$ASQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND ( questionID=\'' . $Row['questionID'] . '\' OR condOnID = \'' . $Row['questionID'] . '\') LIMIT 0,1 ';
$ARow = $DB->queryFirstRow($ASQL);
if ($CRow || $ARow) {
	$EnableQCoreClass->replace('isQtnLogic', $lang['qtn_check_yes']);
}
else {
	$EnableQCoreClass->replace('isQtnLogic', $lang['qtn_check_no']);
}

$HSQL = ' SELECT questionID FROM ' . RELATION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' LIMIT 1 ';
$HRow = $DB->queryFirstRow($HSQL);
$TSQL = ' SELECT questionID FROM ' . RELATION_LIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' LIMIT 1 ';
$TRow = $DB->queryFirstRow($TSQL);
if ($HRow || $TRow) {
	$EnableQCoreClass->replace('isQtnRel', $lang['qtn_check_yes']);
}
else {
	$EnableQCoreClass->replace('isQtnRel', $lang['qtn_check_no']);
}

$thisProg = 'DesignSurvey.php?surveyID=' . $_POST['surveyID'] . '&surveyTitle=' . urlencode($_POST['surveyTitle']);
$copyURL = $thisProg . '&Actiones=Copy&questionID=' . $Row['questionID'];
$deleteURL = $thisProg . '&Action=Delete&questionID=' . $Row['questionID'];
$logicURL = $thisProg . '&DOes=EditLogic&questionID=' . $Row['questionID'];
$editURL = $thisProg . '&Action=View&questionID=' . $Row['questionID'] . '&questionType=' . $Row['questionType'];
$inURL = $thisProg . '&Action=Insert&questionID=' . $Row['questionID'] . '&questionName=' . urlencode($Row['questionName']) . '&questionType=' . $Row['questionType'] . '&orderByID=' . $Row['orderByID'];
$EnableQCoreClass->replace('copyURL', $copyURL);
$EnableQCoreClass->replace('deleteURL', $deleteURL);
$EnableQCoreClass->replace('logicURL', $logicURL);
$EnableQCoreClass->replace('editURL', $editURL);
$EnableQCoreClass->replace('inURL', $inURL);

if ($Row['questionType'] != '8') {
	$EnableQCoreClass->replace('qtnTypes', $lang['type_' . $Module[$Row['questionType']]]);

	if ($Row['questionType'] != '12') {
		if ($Row['questionType'] != '30') {
			$EnableQCoreClass->replace('isEdit', '');
			$EnableQCoreClass->replace('isHaveLogicQtn', '');
		}
		else {
			$EnableQCoreClass->replace('isEdit', '');

			if ($Row['requiredMode'] != 2) {
				$EnableQCoreClass->replace('isHaveLogicQtn', '');
			}
			else {
				$EnableQCoreClass->replace('isHaveLogicQtn', 'none');
			}
		}
	}
	else {
		$EnableQCoreClass->replace('isHaveLogicQtn', 'none');
		$EnableQCoreClass->replace('isEdit', '');
	}

	if (($Row['questionType'] != '12') && ($Row['questionType'] != '30')) {
		$EnableQCoreClass->replace('isPreQtn', '');
	}
	else {
		$EnableQCoreClass->replace('isPreQtn', 'none');
	}
}
else {
	$EnableQCoreClass->replace('qtnTypes', $lang['page_break']);
	$EnableQCoreClass->replace('isHaveLogicQtn', 'none');
	$EnableQCoreClass->replace('isEdit', 'none');
	$EnableQCoreClass->replace('isPreQtn', 'none');
}

$EnableQCoreClass->replace('preFirstQtnId', $Row['questionID']);
$ActionPage = $EnableQCoreClass->parse('ActionPage', 'ActionPageFile');
echo $ActionPage;

?>
