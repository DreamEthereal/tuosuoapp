<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
$thisProg = 'SecurityFlag.php';
_checkroletype('1');
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE;
$LicenseRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->setTemplateFile('SecurityFlagFile', 'SecurityFlag.html');
$EnableQCoreClass->replace('LicenseNum', $LicenseRow['license']);
$EnableQCoreClass->replace('SecurityFlag1', md5($LicenseRow['license']));
$EnableQCoreClass->replace('SecurityFlag2', md5($LicenseRow['license'] . 'EnableQ'));
$SecurityFlagPage = $EnableQCoreClass->parse('SecurityFlagPage', 'SecurityFlagFile');
echo $SecurityFlagPage;

?>
