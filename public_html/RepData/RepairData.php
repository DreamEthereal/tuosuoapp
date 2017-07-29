<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
@set_time_limit(0);
_checkroletype('1');
ob_end_clean();
$style = '<style>' . "\n" . '';
$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
$style .= '</style>' . "\n" . '';
echo $style;
flush();
$scroll = '<SCRIPT type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</SCRIPT>';
$prefix = '';
$i = 0;

for (; $i < 300; $i++) {
	$prefix .= ' ' . "\n" . '';
}

$SQL = ' SELECT surveyID,surveyTitle FROM eq_survey WHERE status != 0 AND projectType =1 ORDER BY surveyID ASC ';
$Result = $DB->query($SQL);
$surveyNum = 0;

while ($Row = $DB->queryArray($Result)) {
	$surveyNum++;
	ob_end_clean();
	$str = '<div class="tipsinfo">正在修复问卷数据：' . $Row['surveyTitle'] . '</div>' . "\n" . '';
	echo $prefix . $str . $scroll;
	flush();
	$dSQL = ' SELECT responseID,taskID FROM eq_response_' . $Row['surveyID'] . ' WHERE taskID != 0 ';
	$dResult = $DB->query($dSQL);

	while ($dRow = $DB->queryArray($dResult)) {
		$hSQL = ' SELECT userGroupName,userGroupDesc FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $dRow['taskID'] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);
		$uSQL = ' UPDATE eq_response_' . $Row['surveyID'] . ' SET administratorsName = \'' . addslashes($hRow['userGroupName']) . '\',ipAddress = \'' . addslashes($hRow['userGroupDesc']) . '\' WHERE responseID =\'' . $dRow['responseID'] . '\' ';
		$DB->query($uSQL);
	}
}

ob_end_clean();
echo '<div class="tipsinfo">共修复成功：<b>' . $surveyNum . '</b>张问卷数据</div>' . "\n" . '';
flush();

?>
