<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyID,surveyTitle,administratorsID,isLogicAnd,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '1') {
	_showerror($lang['status_error'], $Sur_G_Row['surveyTitle'] . ':' . $lang['no_edit_survey']);
}

$EnableQCoreClass->setTemplateFile('QuestionListFile', 'QtnModiList.html');
$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('qlang', $Sur_G_Row['lang']);
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$totalRecNum = $totalPageNum = 0;
$pageNum = 1;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('preFirstQtnId', $Row['questionID']);
	$questionName = qnohtmltag($Row['questionName'], 1);

	if ($Row['isRequired'] == '1') {
		$questionName .= '&nbsp;<span class=red>*</span>';
	}

	if ($Row['questionType'] == 8) {
		$questionName = '&nbsp;<b>µÚ' . $pageNum . 'Ò³½áÊø</b>';
		$pageNum++;
	}

	$EnableQCoreClass->replace('questionName', $questionName);
	$HaveConSQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' LIMIT 0,1 ';
	$HaveConRow = $DB->queryFirstRow($HaveConSQL);
	$HaveAssSQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $Row['questionID'] . '\' LIMIT 0,1 ';
	$HaveAssRow = $DB->queryFirstRow($HaveAssSQL);
	if ($HaveConRow || $HaveAssRow) {
		$EnableQCoreClass->replace('logic_color', 'background:#fafafa url(../Images/iorange.png) repeat-y top right');
	}
	else {
		$EnableQCoreClass->replace('logic_color', 'background:#fafafa');
	}

	$EnableQCoreClass->replace('questionType', $lang['type_' . $Module[$Row['questionType']]]);

	if ($Row['questionType'] != '8') {
		$totalRecNum++;
		if (($Row['questionType'] != '12') && ($Row['questionType'] != '9')) {
			if ($Row['questionType'] != '30') {
				$EnableQCoreClass->replace('qtn_color', 'background:#fafafa');
			}
			else {
				$EnableQCoreClass->replace('qtn_color', 'background:#fafafa url(../Images/iblue.png) repeat-y top left');
			}
		}
		else {
			$EnableQCoreClass->replace('qtn_color', 'background:#fafafa url(../Images/iyellow.png) repeat-y top left');
		}
	}
	else {
		$totalPageNum++;
		$EnableQCoreClass->replace('qtn_color', 'background:#fafafa url(../Images/ired.png) repeat-y top left');
	}

	if ($Row['questionType'] != '8') {
		$EnableQCoreClass->replace('isModiQtn', '');
		$editProg = 'ModiSurvey.php?surveyID=' . $Sur_G_Row['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']);
		$editURL = $editProg . '&Action=View&questionID=' . $Row['questionID'] . '&questionType=' . $Row['questionType'];
		$EnableQCoreClass->replace('editURL', $editURL);
	}
	else {
		$EnableQCoreClass->replace('isModiQtn', 'none');
		$EnableQCoreClass->replace('editURL', '');
	}

	$EnableQCoreClass->parse('question', 'QUESTION', true);
}

$EnableQCoreClass->replace('totalRecNum', $totalRecNum);
$EnableQCoreClass->replace('totalPageNum', $totalPageNum + 1);
$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
header('Content-Type:text/html; charset=gbk');
echo $QuestionList;

?>
