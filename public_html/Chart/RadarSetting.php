<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $_GET['questionID'] . '\' LIMIT 1 ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Chart');
$EnableQCoreClass->setTemplateFile('AmRadarSettingFile', 'AmRadarSetting.xml');
$EnableQCoreClass->replace('questionName', iconv('gbk', 'UTF-8', qshowchartqtnname($Row['questionName'])));
$AmRadarSetting = $EnableQCoreClass->parse('AmRadarSetting', 'AmRadarSettingFile');
echo $AmRadarSetting;
exit();

?>
