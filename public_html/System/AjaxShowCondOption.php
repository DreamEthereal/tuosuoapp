<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
header('Content-Type:text/html; charset=gbk');
$baseID = (int) $_POST['baseID'];

if ($baseID == '') {
	echo '&nbsp;######&nbsp;';
	exit();
}

$EnableQCoreClass->setTemplateFile('CondAjaxBaseFile', 'CondAjaxBase.html');
$EnableQCoreClass->set_CycBlock('CondAjaxBaseFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
$SQL = ' SELECT questionType,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $baseID . '\' ';
$BaseHaveRow = $DB->queryFirstRow($SQL);
$OptionSQL = ' SELECT question_radioID as optionID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $baseID . '\' ORDER BY optionOptionID ASC ';
$OptionResult = $DB->query($OptionSQL);
$optionAutoArray = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$optionAutoArray[$OptionRow['optionID']] = $OptionRow['optionName'];
}

if ($BaseHaveRow['isHaveOther'] == 1) {
	$optionAutoArray[0] = $BaseHaveRow['otherText'];
}

$perLine = 0;
$theBaseOptionID = '';

foreach ($optionAutoArray as $optionAutoID => $optionAutoName) {
	$theBaseOptionID .= $optionAutoID . '|';
	$EnableQCoreClass->replace('baseOptionName', qnohtmltag($optionAutoName, 1));
	$EnableQCoreClass->replace('baseOptionID', $optionAutoID);
	$RelSQL = ' SELECT a.sonID,b.optionName FROM ' . CONDREL_TABLE . ' a,' . QUESTION_YESNO_TABLE . ' b WHERE a.fatherID =\'' . $optionAutoID . '\' AND a.questionID=\'' . $_POST['questionID'] . '\' AND a.sonID = b.question_yesnoID ORDER BY a.sonID ASC ';
	$RelResult = $DB->query($RelSQL);
	$optionName = '';
	$i = 0;

	while ($RelRow = $DB->queryArray($RelResult)) {
		$i++;

		if ($i == $OptionCount) {
			$optionName .= $RelRow['optionName'];
		}
		else {
			$optionName .= $RelRow['optionName'] . "\n";
		}
	}

	$EnableQCoreClass->replace('optionName', $optionName);
	$perLine++;

	if (($perLine % 3) == 0) {
		$EnableQCoreClass->replace('perLine', '</tr><tr>');
	}
	else {
		$EnableQCoreClass->replace('perLine', '');
	}

	if (isset($_POST['type']) && ($_POST['type'] == 1)) {
		$EnableQCoreClass->replace('isModi', 'disabled');
	}
	else {
		$EnableQCoreClass->replace('isModi', '');
	}

	$EnableQCoreClass->parse('option', 'OPTION', true);
}

$CondAjaxBase = $EnableQCoreClass->parse('CondAjaxBase', 'CondAjaxBaseFile');
$theBaseOptionID = substr($theBaseOptionID, 0, -1);
echo $theBaseOptionID . '######' . $CondAjaxBase;

?>
