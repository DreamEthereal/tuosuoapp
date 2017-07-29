<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype(1);
require ROOT_PATH . 'Export/Database.opti.sql.php';
writetolog($lang['opti_table']);
_showsucceed($lang['opti_table'], 'DataBackup.php');

?>
