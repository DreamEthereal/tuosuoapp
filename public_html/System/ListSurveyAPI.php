<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
$thisProg = 'ListSurveyAPI.php';
_checkroletype('1');
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE;
$LicenseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('ListSurveyAPIFile', 'ListSurveyAPI.html');
$EnableQCoreClass->replace('SecurityFlag', md5($LicenseRow['license'] . 'EnableQ'));
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
$EnableQCoreClass->replace('listURL', $All_Path);
$ListSurveyAPI = $EnableQCoreClass->parse('ListSurveyAPI', 'ListSurveyAPIFile');
echo $ListSurveyAPI;

?>
