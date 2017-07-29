<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mysql.php';
$surveyID = (int) $_GET['qid'];
$SQL = ' SELECT status FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . $surveyID . '\' LIMIT 0,1 ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != 1) {
	exit();
}

$SQL = ' SELECT COUNT(*) FROM ' . $table_prefix . 'response_' . $surveyID . ' ';

if ((int) $_GET['type'] == 1) {
	$SQL .= ' WHERE overFlag = 1 ';
}

$Row = $DB->queryFirstRow($SQL);
exit($Row[0]);

?>
