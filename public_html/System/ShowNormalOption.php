<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$EnableQCoreClass->setTemplateFile('NormalOptionFile', 'NormalOption.html');

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT * FROM ' . OPTION_TABLE . ' ORDER BY optionID ';
	break;

case '2':
case '5':
	$SQL = ' SELECT * FROM ' . OPTION_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' OR administratorsID=0 ORDER BY optionID ';
	break;
}

$Result = $DB->query($SQL);
$optionCateList = '';

while ($Row = $DB->queryArray($Result)) {
	$optionCateList .= '<option value=' . $Row['optionID'] . '>' . $Row['optionCate'] . '</option>\\n';
}

$EnableQCoreClass->replace('optionCateList', $optionCateList);
$EnableQCoreClass->replace('isRange', (int) $_GET['isRange']);
$EnableQCoreClass->parse('NormalOption', 'NormalOptionFile');
$EnableQCoreClass->output('NormalOption');

?>
