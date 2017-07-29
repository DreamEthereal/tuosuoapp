<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mysql.php';
$thisProg = 'UpdateRtnValue.php?surveyID=' . $_GET['surveyID'] . '&username=' . $_GET['username'] . '&pos=' . $_GET['pos'] . '&value=' . $_GET['value'] . '&hash=' . $_GET['hash'] . ' ';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash']) != md5(trim($SerialRow['license']))) {
	exit('false|EnableQ Security Violation');
}

if (trim($_GET['username']) == '') {
	exit('false|Empty UserName');
}

if (($_GET['pos'] < 1) || (6 < $_GET['pos'])) {
	exit('false|Incorrect data field');
}

if ($_GET['surveyID'] == '') {
	exit('false|Incorrect surveyID');
}
else {
	$SQL = ' SELECT isPublic,status FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . trim($_GET['surveyID']) . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);

	if (!$S_Row) {
		exit('false|Incorrect surveyID');
	}
}

if ($S_Row['status'] == 0) {
	exit('false|Incorrect survey status');
}

if ($S_Row['isPublic'] != 0) {
	exit('false|Incorrect survey type');
}

$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET ajaxRtnValue_' . trim($_GET['pos']) . ' = \'' . trim($_GET['value']) . '\' WHERE administratorsName=\'' . trim($_GET['username']) . '\' ';
$DB->query($SQL);
exit('true');

?>
