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
		_showerror('SQL����﷨����', 'SQL����﷨����Ŀǰ���������ݱ���²�����');
	}

	if (strpos(strtolower(trim($_POST['sqlQuery'])), 'where') === false) {
		_showerror('SQL����﷨����', 'SQL����﷨�������ݱ���²����������ض�������');
	}

	$SQL = stripslashes(trim($_POST['sqlQuery']));
	$DB->query($SQL);
	writetolog('SQL���ִ��:' . $SQL);
	_showsucceed('SQL���ִ��', $thisProg);
}

$EnableQCoreClass->setTemplateFile('DataListFile', 'SQLQuery.html');
$EnableQCoreClass->parse('DataList', 'DataListFile');
$EnableQCoreClass->output('DataList');

?>
