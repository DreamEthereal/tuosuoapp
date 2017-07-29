<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|3|5|7');
if (!isset($_GET['type']) || ($_GET['type'] == 1)) {
	$EnableQCoreClass->setTemplateFile('DataSourceFile', 'DataSource.html');
}
else {
	$EnableQCoreClass->setTemplateFile('DataSourceFile', 'DataSource0.html');
}

$EnableQCoreClass->set_CycBlock('DataSourceFile', 'DATA', 'data');
$EnableQCoreClass->replace('data', '');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$nowDataSource = '';
if (!isset($_SESSION['dataSource' . $_GET['surveyID']]) || ($_SESSION['dataSource' . $_GET['surveyID']] == 0)) {
	$nowDataSource = '全部数据 (除去无效回复与审核未通过)';
	$EnableQCoreClass->replace('dataSource0Check', 'checked');
}
else {
	$EnableQCoreClass->replace('dataSource0Check', '');
}

$SQL = ' SELECT * FROM ' . QUERY_LIST_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND ( defineShare = 1 OR ( administratorsID =\'' . $_SESSION['administratorsID'] . '\' AND defineShare = 0 ) ) ORDER BY queryID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('dataSourceValue', $Row['queryID']);

	if ($Row['defineShare'] == 0) {
		$EnableQCoreClass->replace('dataSourceName', $Row['queryName'] . $lang['report_private']);
	}
	else {
		$EnableQCoreClass->replace('dataSourceName', $Row['queryName']);
	}

	$EnableQCoreClass->replace('dataSourceName_URL', urlencode($Row['queryName']));
	if (isset($_SESSION['dataSource' . $_GET['surveyID']]) && ($_SESSION['dataSource' . $_GET['surveyID']] == $Row['queryID'])) {
		if ($Row['defineShare'] == 0) {
			$nowDataSource = $Row['queryName'] . $lang['report_private'];
		}
		else {
			$nowDataSource = $Row['queryName'];
		}

		$EnableQCoreClass->replace('dataSourceCheck', 'checked');
		$EnableQCoreClass->replace('haveDelete', 'none');
		if (($Row['administratorsID'] == $_SESSION['administratorsID']) || ($_SESSION['adminRoleType'] == '1')) {
			$EnableQCoreClass->replace('haveEdit', '');
		}
		else {
			$EnableQCoreClass->replace('haveEdit', 'none');
		}
	}
	else {
		$EnableQCoreClass->replace('dataSourceCheck', '');
		if (($Row['administratorsID'] == $_SESSION['administratorsID']) || ($_SESSION['adminRoleType'] == '1')) {
			$EnableQCoreClass->replace('haveDelete', '');
			$EnableQCoreClass->replace('haveEdit', '');
		}
		else {
			$EnableQCoreClass->replace('haveDelete', 'none');
			$EnableQCoreClass->replace('haveEdit', 'none');
		}
	}

	$EnableQCoreClass->parse('data', 'DATA', true);
}

$EnableQCoreClass->replace('nowDataSource', $nowDataSource);
$DataSource = $EnableQCoreClass->parse('DataSource', 'DataSourceFile');
echo $DataSource;
exit();

?>
