<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxSurveyGrade.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'];
_checkpassport('1|2|5', $_GET['surveyID']);
$EnableQCoreClass->replace('thisURL', $thisProg);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

if ($_GET['DO'] == 'DELETE') {
	$SQL = ' DELETE FROM ' . GRADE_TABLE . ' WHERE gradeID=\'' . $_GET['gradeID'] . '\' ';
	$DB->query($SQL);
	_showsucceed($lang['dele_survey_grade'], $thisProg);
}

if ($_POST['Action'] == 'EditGradeRule') {
	$SQL = ' UPDATE ' . GRADE_TABLE . ' SET startOperator=\'' . $_POST['startOperator'] . '\',startGrade=\'' . $_POST['startGrade'] . '\',endOperator=\'' . $_POST['endOperator'] . '\',endGrade=\'' . $_POST['endGrade'] . '\',conclusion=\'' . $_POST['conclusion'] . '\' WHERE gradeID=\'' . $_POST['gradeID'] . '\' ';
	$DB->query($SQL);
	_showmessage($lang['edit_survey_grade'], true);
}

if ($_GET['DO'] == 'Edit') {
	$SQL = ' SELECT * FROM ' . GRADE_TABLE . ' WHERE gradeID=\'' . $_GET['gradeID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('GradeEditFile', 'SurveyGradeEdit.html');
	$EnableQCoreClass->replace('startGrade', $Row['startGrade']);
	$EnableQCoreClass->replace('endGrade', $Row['endGrade']);
	$EnableQCoreClass->replace('conclusion', $Row['conclusion']);
	$EnableQCoreClass->replace('gradeID', $Row['gradeID']);

	if ($Row['startOperator'] == '>') {
		$EnableQCoreClass->replace('startOperator2', 'selected');
	}
	else {
		$EnableQCoreClass->replace('startOperator2', '');
	}

	if ($Row['endOperator'] == '<=') {
		$EnableQCoreClass->replace('endOperator2', 'selected');
	}
	else {
		$EnableQCoreClass->replace('endOperator2', '');
	}

	$EnableQCoreClass->parse('GradeEdit', 'GradeEditFile');
	$EnableQCoreClass->output('GradeEdit');
	_showsucceed($lang['dele_survey_grade'], $thisProg);
}

if ($_POST['Action'] == 'AddGradeRule') {
	$SQL = ' INSERT INTO ' . GRADE_TABLE . ' SET startOperator=\'' . $_POST['startOperator'] . '\',startGrade=\'' . $_POST['startGrade'] . '\',endOperator=\'' . $_POST['endOperator'] . '\',endGrade=\'' . $_POST['endGrade'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',conclusion=\'' . $_POST['conclusion'] . '\' ';
	$DB->query($SQL);
	_showsucceed($lang['add_survey_grade'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('GradeFile', 'SurveyGrade.html');
$EnableQCoreClass->set_CycBlock('GradeFile', 'RULE', 'rule');
$EnableQCoreClass->replace('rule', '');
$SQL = ' SELECT * FROM ' . GRADE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY gradeID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$ruleText = $Row['startOperator'] . ' ' . $Row['startGrade'] . ' && ' . $Row['endOperator'] . ' ' . $Row['endGrade'];
	$EnableQCoreClass->replace('ruleText', $ruleText);
	$EnableQCoreClass->replace('conclusion', nl2br(qnohtmltag($Row['conclusion'])));
	$editURL = $thisProg . '&DO=Edit&gradeID=' . $Row['gradeID'];
	$EnableQCoreClass->replace('editURL', $editURL);
	$deleteURL = $thisProg . '&DO=DELETE&gradeID=' . $Row['gradeID'];
	$EnableQCoreClass->replace('deleteURL', $deleteURL);
	$EnableQCoreClass->parse('rule', 'RULE', true);
}

$EnableQCoreClass->parse('Grade', 'GradeFile');
$EnableQCoreClass->output('Grade');

?>
