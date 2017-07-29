<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
$_GET['qid'] = (int) $_GET['qid'];
_checkpassport('1|2|5', $_GET['qid']);
$theUniCodeFile = ROOT_PATH . 'PerUserData/unicode/' . md5('uniCode' . $_GET['qid']) . '.php';

if (file_exists($theUniCodeFile)) {
	@unlink($theUniCodeFile);
}

return 1;

?>
