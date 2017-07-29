<?php
//dezend by http://www.yunlu99.com/
function getnextqtnrel($surveyID, $qtnID)
{
	global $theQtnListArray;
	global $DB;
	global $theConditionsArray;
	global $theAssociateArray;
	$_obf_vQ6QISIG7wfl = $theQtnListArray[$qtnID]['nextid'];
	$_obf_wbAlhw__ = (in_array($qtnID, $theConditionsArray[$_obf_vQ6QISIG7wfl]) ? 1 : 0);
	$_obf_lgJtRw__ = (in_array($qtnID, $theAssociateArray[$_obf_vQ6QISIG7wfl]) ? 1 : 0);
	$_obf_jztYOsWP = ($theQtnListArray[$_obf_vQ6QISIG7wfl]['baseid'] == $qtnID ? 1 : 0);
	$_obf_eqpvr0LVRnRk_Q__ = 0;
	$_obf_6bcsPw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $_obf_vQ6QISIG7wfl . '\' ';
	$_obf_VZmI6f2x_w__ = $DB->query($_obf_6bcsPw__);

	while ($_obf_Khp5jw__ = $DB->queryArray($_obf_VZmI6f2x_w__)) {
		$_obf_W3K2wg__ = ' SELECT relationID FROM ' . RELATION_LIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $qtnID . '\' AND relationID = \'' . $_obf_Khp5jw__['relationID'] . '\' LIMIT 1 ';
		$_obf_wk8__w__ = $DB->queryFirstRow($_obf_W3K2wg__);

		if ($_obf_wk8__w__) {
			$_obf_eqpvr0LVRnRk_Q__ = 1;
			break;
		}
	}

	if ($_obf_wbAlhw__ || $_obf_lgJtRw__ || $_obf_jztYOsWP || $_obf_eqpvr0LVRnRk_Q__) {
		return 1;
	}
	else {
		return 0;
	}
}

function getlastqtnrel($surveyID, $qtnID)
{
	global $theQtnListArray;
	global $DB;
	global $theConditionsArray;
	global $theAssociateArray;
	$_obf_g3qrATB5429X = $theQtnListArray[$qtnID]['lastid'];
	$_obf_wbAlhw__ = (in_array($_obf_g3qrATB5429X, $theConditionsArray[$qtnID]) ? 1 : 0);
	$_obf_lgJtRw__ = (in_array($_obf_g3qrATB5429X, $theAssociateArray[$qtnID]) ? 1 : 0);
	$_obf_jztYOsWP = ($theQtnListArray[$qtnID]['baseid'] == $_obf_g3qrATB5429X ? 1 : 0);
	$_obf_eqpvr0LVRnRk_Q__ = 0;
	$_obf_6bcsPw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $qtnID . '\' ';
	$_obf_VZmI6f2x_w__ = $DB->query($_obf_6bcsPw__);

	while ($_obf_Khp5jw__ = $DB->queryArray($_obf_VZmI6f2x_w__)) {
		$_obf_W3K2wg__ = ' SELECT relationID FROM ' . RELATION_LIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID=\'' . $_obf_g3qrATB5429X . '\' AND relationID = \'' . $_obf_Khp5jw__['relationID'] . '\' LIMIT 1 ';
		$_obf_wk8__w__ = $DB->queryFirstRow($_obf_W3K2wg__);

		if ($_obf_wk8__w__) {
			$_obf_eqpvr0LVRnRk_Q__ = 1;
			break;
		}
	}

	if ($_obf_wbAlhw__ || $_obf_lgJtRw__ || $_obf_jztYOsWP || $_obf_eqpvr0LVRnRk_Q__) {
		return 1;
	}
	else {
		return 0;
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,administratorsID,isLogicAnd,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	_showerror($lang['system_error'], $lang['no_design_survey']);
}

$thisProg = 'DesignSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->setTemplateFile('QuestionListFile', 'QuestionList.html');
$EnableQCoreClass->set_CycBlock('QuestionListFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$EnableQCoreClass->replace('addURL', $thisProg . '&DO=Add');
$EnableQCoreClass->replace('logicURL', $thisProg . '&DO=Logic');
$EnableQCoreClass->replace('listURL', $thisProg);
$EnableQCoreClass->replace('questionListURL', $thisProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$EnableQCoreClass->replace('qlang', $Sur_G_Row['lang']);
$EnableQCoreClass->replace('importURL', $thisProg . '&DO=FileImport');
$EnableQCoreClass->replace('copySurveyURL', $thisProg . '&type=1&DO=CopyNew');
$EnableQCoreClass->replace('copySurveyIndexURL', $thisProg . '&type=2&DO=CopyNew');
$theQtnArray = array();
$theBaseArray = array();
$SQL = ' SELECT questionID,baseID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);


while ($Row = $DB->queryArray($Result)) {
	$theQtnArray[] = $Row['questionID'];
	$theBaseArray[$Row['questionID']] = $Row['baseID'];
}

$theQtnListArray = array();
$i = 0;

for (; $i < count($theQtnArray); $i++) {
	$theQtnListArray[$theQtnArray[$i]]['baseid'] = $theBaseArray[$theQtnArray[$i]];
	$j = $i + 1;
	$k = $i - 1;

	if ($i == 0) {
		$theQtnListArray[$theQtnArray[$i]]['nextid'] = $theQtnArray[$j];
		$theQtnListArray[$theQtnArray[$i]]['lastid'] = 0;
	}
	else if ($i == count($theQtnArray) - 1) {
		$theQtnListArray[$theQtnArray[$i]]['nextid'] = 0;
		$theQtnListArray[$theQtnArray[$i]]['lastid'] = $theQtnArray[$k];
	}
	else {
		$theQtnListArray[$theQtnArray[$i]]['nextid'] = $theQtnArray[$j];
		$theQtnListArray[$theQtnArray[$i]]['lastid'] = $theQtnArray[$k];
	}
}

unset($theBaseArray);
unset($theQtnArray);
$theConditionsArray = array();
$SQL = ' SELECT DISTINCT questionID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC ';
$Result = $DB->query($SQL);

while ($cRow = $DB->queryArray($Result)) {
	$bSQL = ' SELECT DISTINCT condOnID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $cRow['questionID'] . '\' ORDER BY questionID ASC ';
	$bResult = $DB->query($bSQL);

	while ($bRow = $DB->queryArray($bResult)) {
		$theConditionsArray[$cRow['questionID']][] = $bRow['condOnID'];
	}
}

$theAssociateArray = array();
$SQL = ' SELECT DISTINCT questionID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC ';
$Result = $DB->query($SQL);

while ($cRow = $DB->queryArray($Result)) {
	$bSQL = ' SELECT DISTINCT condOnID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $cRow['questionID'] . '\' ORDER BY questionID ASC ';
	$bResult = $DB->query($bSQL);

	while ($bRow = $DB->queryArray($bResult)) {
		$theAssociateArray[$cRow['questionID']][] = $bRow['condOnID'];
	}
}

$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$totalRecNum = $totalPageNum = 0;
$i = 0;
$pageNum = 1;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('ID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$questionName = qnohtmltag($Row['questionName'], 1);

	if ($Row['isRequired'] == '1') {
		$questionName .= '&nbsp;<span class=red>*</span>';
	}

	if ($Row['questionType'] == 8) {
		$questionName = '&nbsp;<b>��' . $pageNum . 'ҳ����</b>';
		$pageNum++;
	}

	$EnableQCoreClass->replace('questionName', $questionName);
	$EnableQCoreClass->replace('isRequired', $Row['isRequired']);
	$EnableQCoreClass->replace('qtnType', $Row['questionType']);
	if (array_key_exists($Row['questionID'], $theConditionsArray) || array_key_exists($Row['questionID'], $theAssociateArray)) {
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

	$EnableQCoreClass->replace('actionStr', '&surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&questionID=' . $Row['questionID']);
	$i++;
	$orderAction = '';
	if (getlastqtnrel($_GET['surveyID'], $Row['questionID']) || ($i == 1)) {
		$orderAction .= '';
	}
	else {
		$upURL = $thisProg . '&DO=Order&Compositor=DESC&ID=' . $Row['questionID'] . '&OrderID=' . $Row['orderByID'];
		$orderAction .= '<A href="' . $upURL . '"><IMG src="../Images/arrow_up.gif" alt="' . $lang['order_up'] . '"border=0></A>';
	}

	if (getnextqtnrel($_GET['surveyID'], $Row['questionID']) || ($i == $recordCount)) {
		$orderAction .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	else {
		$downURL = $thisProg . '&DO=Order&Compositor=ASC&ID=' . $Row['questionID'] . '&OrderID=' . $Row['orderByID'];
		$orderAction .= '<A href="' . $downURL . '"><IMG src="../Images/arrow_down.gif" alt="' . $lang['order_down'] . '"border=0></A>';
	}

	$EnableQCoreClass->replace('orderAction', $orderAction);
	$EnableQCoreClass->parse('question', 'QUESTION', true);
}

$EnableQCoreClass->replace('totalRecNum', $totalRecNum);
$EnableQCoreClass->replace('totalPageNum', $totalPageNum + 1);

if ($recordCount == 0) {
	$EnableQCoreClass->replace('isHaveSite', 'none');
	$EnableQCoreClass->replace('noneSite', '');
}
else {
	$EnableQCoreClass->replace('isHaveSite', '');
	$EnableQCoreClass->replace('noneSite', 'none');
}

$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');
header('Content-Type:text/html; charset=gbk');
echo $QuestionList;

?>
