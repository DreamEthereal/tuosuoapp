<?php
//dezend by http://www.yunlu99.com/
function percent($optionResponseNum, $totalResponseNum)
{
	if ($totalResponseNum != 0) {
		$_obf_Tg4KRli4JlEMa7GuzA__ = @round((100 / $totalResponseNum) * $optionResponseNum, 2);
	}
	else {
		$_obf_Tg4KRli4JlEMa7GuzA__ = 0;
	}

	return $_obf_Tg4KRli4JlEMa7GuzA__;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);

if ($_GET['type'] == '2') {
	$HeadingsList = $_SESSION['Headings'][$_GET['dataID']];
	$NValueList = $_SESSION['NValue'][$_GET['dataID']];
	$ObsFreqList = $_SESSION['ObsFreqs'][$_GET['dataID']];
}
else {
	$HeadingsList = $_SESSION['Headings'];
	$NValueList = $_SESSION['NValue'];
	$ObsFreqList = $_SESSION['ObsFreq'];
}

$ColumnsData = '';

foreach ($HeadingsList as $t => $theHeadings) {
	$ColumnsData .= iconv('gbk', 'UTF-8', $theHeadings) . '<br><b>N=' . $NValueList[$t] . '</b>';
	$m = 0;

	for (; $m < count($ObsFreqList); $m++) {
		$ColumnsData .= ';' . percent($ObsFreqList[$m][$t], $NValueList[$t]);
	}

	$ColumnsData .= "\r\n";
}

unset($HeadingsList);
unset($ObsFreqList);
unset($NValueList);

if ($_GET['type'] == '2') {
	unset($_SESSION['Headings'][$_GET['dataID']]);
	unset($_SESSION['NValue'][$_GET['dataID']]);
	unset($_SESSION['ObsFreqs'][$_GET['dataID']]);
}
else {
	unset($_SESSION['Headings']);
	unset($_SESSION['NValue']);
	unset($_SESSION['ObsFreq']);
}

echo $ColumnsData;
exit();

?>
