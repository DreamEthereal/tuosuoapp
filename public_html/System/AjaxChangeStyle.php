<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT panelID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if (in_array($Row['panelID'], array(30001, 30002, 30003, 30004, 30005, 30006))) {
	header('Content-Type:text/html; charset=gbk');
	echo $lang['changestyle_survey_fail'] . ':' . $_GET['surveyTitle'];
}
else {
	if (isset($_GET['type']) && ($_GET['type'] == 1)) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET theme=\'' . $_GET['theme'] . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
		$DB->query($SQL);
		writetolog($lang['changestyle_survey'] . ':' . base64_decode($_GET['surveyTitle']));
		header('Content-Type:text/html; charset=gbk');
		echo $lang['changestyle_survey'] . ':' . base64_decode($_GET['surveyTitle']);
	}
	else {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET theme=\'' . $_GET['theme'] . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
		$DB->query($SQL);
		writetolog($lang['changestyle_survey'] . ':' . $_GET['surveyTitle']);
		header('Content-Type:text/html; charset=gbk');
		echo $lang['changestyle_survey'] . ':' . $_GET['surveyTitle'];
	}
}

?>
