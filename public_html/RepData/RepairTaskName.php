<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,surveyID,surveyTitle,projectType,projectOwner,isViewAuthData FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

switch ($Sur_G_Row['status']) {
case '0':
	_showerror($lang['system_error'], $lang['design_survey_now']);
	break;

case '2':
	_showerror($lang['system_error'], $lang['close_survey_now']);
	break;

case '1':
	break;
}

if ($_POST['Action'] == 'UpdateTaskNameSubmit') {
	@set_time_limit(0);
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

	ob_end_clean();
	$str = '<div class="tipsinfo">正在修复问卷数据：<b>' . $Sur_G_Row['surveyTitle'] . '</b></div>' . "\n" . '';
	echo $prefix . $str . $scroll;
	flush();
	$dSQL = ' SELECT responseID,taskID FROM eq_response_' . $Sur_G_Row['surveyID'] . ' WHERE taskID != 0 ';
	$dResult = $DB->query($dSQL);
	$theSuccNum = 0;

	while ($dRow = $DB->queryArray($dResult)) {
		$hSQL = ' SELECT userGroupName,userGroupDesc FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $dRow['taskID'] . '\' ';
		$hRow = $DB->queryFirstRow($hSQL);
		$uSQL = ' UPDATE eq_response_' . $Sur_G_Row['surveyID'] . ' SET administratorsName = \'' . addslashes($hRow['userGroupName']) . '\',ipAddress = \'' . addslashes($hRow['userGroupDesc']) . '\' WHERE responseID =\'' . $dRow['responseID'] . '\' ';
		$DB->query($uSQL);
		$theSuccNum++;
		$str = '<div class="tipsinfo"><font color=green><b>修正数据成功</b></font>：序号为<span class=red>' . $dRow['responseID'] . ' (' . $hRow['userGroupName'] . ')</span></div>' . "\n" . '';
		ob_end_clean();
		echo $prefix . $str . $scroll;
		flush();
	}

	ob_end_clean();
	echo '<div class="tipsinfo">共修复成功：<b><font color=green>' . $theSuccNum . '</font></b>条问卷数据</div>' . "\n" . '';
	echo '<script>parent.gId(\'popupControls\').style.display = \'\';</script>';
	flush();
	exit();
}

$EnableQCoreClass->setTemplateFile('RepairTaskNamePageFile', 'RepairTaskName.html');
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->parse('RepairTaskNamePage', 'RepairTaskNamePageFile');
$EnableQCoreClass->output('RepairTaskNamePage', false);

?>
