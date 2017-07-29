<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$VSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ORDER BY relationID ASC ';
$VResult = $DB->query($VSQL);

while ($VRow = $DB->queryArray($VResult)) {
	$HVSQL = ' SELECT listID FROM ' . RELATION_LIST_TABLE . ' WHERE  relationID = \'' . $VRow['relationID'] . '\' LIMIT 1 ';
	$HVRow = $DB->queryFirstRow($HVSQL);

	if (!$HVRow) {
		$DelSQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $VRow['relationID'] . '\' ';
		$DB->query($DelSQL);
	}
}

?>
