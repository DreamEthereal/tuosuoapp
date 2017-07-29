<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Includes/Crumb.class.php';
require_once ROOT_PATH . 'Entry/Global.fore.php';
$EnableQCoreClass->setTemplateFile('HomeIndexListFile', 'EvalIndex.html');
$EnableQCoreClass->set_CycBlock('HomeIndexListFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$SQL = ' SELECT surveyTitle,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE status = 1 ORDER BY RAND() LIMIT 0,40 ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('viewURL', 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang']);
	$EnableQCoreClass->replace('viewTitle', $Row['surveyTitle']);
	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

$EnableQCoreClass->replace('crumb', Crumb::issueCrumb(session_id()));
$EnableQCoreClass->parse('HomeIndexList', 'HomeIndexListFile');
$EnableQCoreClass->output('HomeIndexList');

?>
