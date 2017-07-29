<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}

_checkroletype(1);
$thisProg = 'SQLQuery.php';

if ($_POST['Action'] == 'SQLQuerySubmit') {
	if (substr(strtolower(trim($_POST['sqlQuery'])), 0, 6) != 'update') {
		_showerror('SQL语句语法错误', 'SQL语句语法错误：目前仅开放数据表更新操作！');
	}

	if (strpos(strtolower(trim($_POST['sqlQuery'])), 'where') === false) {
		_showerror('SQL语句语法错误', 'SQL语句语法错误：数据表更新操作不包含特定条件！');
	}

	$SQL = stripslashes(trim($_POST['sqlQuery']));
	$DB->query($SQL);
	writetolog('SQL语句执行:' . $SQL);
	_showsucceed('SQL语句执行', $thisProg);
}

$EnableQCoreClass->setTemplateFile('DataListFile', 'SQLQuery.html');
$EnableQCoreClass->parse('DataList', 'DataListFile');
$EnableQCoreClass->output('DataList');

?>
