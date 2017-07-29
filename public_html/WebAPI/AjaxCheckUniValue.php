<?php
//dezend by http://www.yunlu99.com/
@set_time_limit(0);
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mysql.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
if (($_POST['qid'] == '') || ($_POST['qid'] == 0) || ($_POST['cid'] == '') || ($_POST['cid'] == 0)) {
	exit('false');
}

$SQL = ' SELECT hiddenVarName,hiddenFromSession,isRequired,isNeg FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['cid'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

switch ($Row['hiddenFromSession']) {
case '1':
	$the_var_value = qhtmlspecialchars($_SESSION[$Row['hiddenVarName']]);
	break;

case '2':
	$the_var_value = qhtmlspecialchars($_COOKIE[$Row['hiddenVarName']]);
	break;

case '3':
	$the_var_value = qhtmlspecialchars($_POST[$Row['hiddenVarName']]);
	break;

case '4':
	$the_var_value = qhtmlspecialchars($_GET[$Row['hiddenVarName']]);
	break;

case '5':
	exit('true');
default:
}

if (($Row['isRequired'] == 1) && (trim($the_var_value) == '')) {
	exit('false');
}

if ($Row['isNeg'] == 1) {
	$hSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_POST['qid'] . ' WHERE option_' . $_POST['cid'] . ' = \'' . $the_var_value . '\' AND overFlag !=0 LIMIT 0,1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow) {
		exit('false');
	}
}

exit('true');

?>
