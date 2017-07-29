<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$hSQL = ' SELECT isPanelCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
$hRow = $DB->queryFirstRow($hSQL);
if (($hRow['isPanelCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Panel' . $surveyID) . '.php')) {
	require ROOT_PATH . 'Includes/PanelRegCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Panel' . $surveyID) . '.php';

?>
