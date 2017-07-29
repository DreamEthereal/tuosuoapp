<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
$thisProg = 'AndroidList.php';
$ConfigRow['topicNum'] = 20;
_checkroletype('1|5|6');

if ($_POST['Action'] == 'PubSurveySubmit') {
	if ($_POST['cateSurveyList'] != '') {
		if ($_POST['nowValue'] != '') {
			$SQL = ' DELETE FROM ' . ANDROID_LIST_TABLE . ' WHERE surveyID IN (' . $_POST['nowValue'] . ') ';
			$DB->query($SQL);
		}

		foreach ($_POST['cateSurveyList'] as $surveyID) {
			$SQL = ' INSERT INTO ' . ANDROID_LIST_TABLE . ' SET surveyID=\'' . $surveyID . '\' ';
			$DB->query($SQL);
		}
	}
	else if ($_POST['nowValue'] != '') {
		$SQL = ' DELETE FROM ' . ANDROID_LIST_TABLE . ' WHERE surveyID IN (' . $_POST['nowValue'] . ') ';
		$DB->query($SQL);
	}

	writetolog($lang['pub_survey_android']);
	_showsucceed($lang['pub_survey_android'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'AndroidList.html');
$BaseSQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($BaseSQL);

switch ($BaseRow['isUseOriPassport']) {
case 4:
	$SQL = ' SELECT surveyName,surveyTitle,surveyID FROM ' . SURVEY_TABLE . ' WHERE status =1 AND isPublic=1 ';
	break;

default:
	$SQL = ' SELECT surveyName,surveyTitle,surveyID FROM ' . SURVEY_TABLE . ' WHERE status =1 ';
	break;
}

switch ($_SESSION['adminRoleType']) {
case '1':
case '6':
	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL .= ' AND administratorsID IN (' . $UserIDList . ') ';
	break;
}

$SQL .= ' ORDER BY surveyID DESC ';
$Result = $DB->query($SQL);
$surveyNameList = $nowValue = '';
$surveyNameList = '';
$cateSurveyList = '';
//$surveyNameList 是 可选择的调查问卷列表 $cateSurveyList 是 已经发布的调查问卷
while ($Row = $DB->queryArray($Result)) {
	$SQL = ' SELECT surveyID FROM ' . ANDROID_LIST_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		$nowValue .= $Row['surveyID'] . ',';
		$cateSurveyList .= '<option value="' . $Row['surveyID'] . '">' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
	}
	else {
		$surveyNameList .= '<option value="' . $Row['surveyID'] . '">' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
	}
}

$EnableQCoreClass->replace('nowValue', substr($nowValue, 0, -1));
$EnableQCoreClass->replace('surveyNameList', $surveyNameList);
$EnableQCoreClass->replace('cateSurveyList', $cateSurveyList);
$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$EnableQCoreClass->output('SurveyList');

?>
