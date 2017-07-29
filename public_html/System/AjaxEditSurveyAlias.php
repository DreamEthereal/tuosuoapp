<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxEditSurveyAlias.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyTitle,status,surveyID,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($_POST['Action'] == 'EditAliasSubmit') {
	foreach ($_POST['alias'] as $theQtnID => $theAliasName) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET alias=\'' . $theAliasName . '\' WHERE questionID=\'' . $theQtnID . '\' ';
		$DB->query($SQL);
	}

	if ($Sur_G_Row['status'] != '0') {
		$theSID = $_GET['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	writetolog($lang['edit_survey_alias'] . ':' . $Sur_G_Row['surveyTitle']);
	_showsucceed($lang['edit_survey_alias'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('EditSurveyAliasFile', 'SurveyEditAlias.html');
$EnableQCoreClass->set_CycBlock('EditSurveyAliasFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DeployActionList.html');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
}
else {
	$EnableQCoreClass->setTemplateFile('SurveyActionFile', 'DesignActionList.html');
	$tmp = 1;

	for (; $tmp <= 4; $tmp++) {
		$EnableQCoreClass->replace('cur_' . $tmp, '');
	}

	$EnableQCoreClass->replace('cur_1', ' class="cur"');
	$EnableQCoreClass->replace('navActionList', $EnableQCoreClass->parse('SurveyActionPage', 'SurveyActionFile'));
}

$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyID', $Sur_G_Row['surveyID']);
if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('isViewUserAction', 'disabled');
}
else {
	$EnableQCoreClass->replace('isViewUserAction', '');
}

$SQL = ' SELECT questionID,questionName,alias,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionType NOT IN (8,9,11,12,30) ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1));
	$EnableQCoreClass->replace('questionType', $lang['type_' . $Module[$Row['questionType']]]);
	$EnableQCoreClass->replace('alias', $Row['alias']);
	$EnableQCoreClass->parse('question', 'QUESTION', true);
}

$EnableQCoreClass->parse('EditSurveyAlias', 'EditSurveyAliasFile');
$EnableQCoreClass->output('EditSurveyAlias');

?>
