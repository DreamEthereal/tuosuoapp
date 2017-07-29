<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
@set_time_limit(0);
_checkroletype('1');
if (!isset($_GET['qid']) || ($_GET['qid'] == '') || ($_GET['qid'] == 0)) {
	_showerror('参数错误', '参数错误：错误的问卷参数');
}

if (!isset($_GET['rid']) || ($_GET['rid'] == '') || ($_GET['rid'] == 0)) {
	_showerror('参数错误', '参数错误：错误的数据序号参数');
}

$SQL = ' SELECT surveyTitle,status FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if (!$Row) {
	_showerror('参数错误', '参数错误：不存在的问卷参数');
}

if ($Row['status'] == 0) {
	_showerror('参数错误', '参数错误：错误的问卷状态');
}

$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['qid'] . ' SET version = 0 WHERE responseID=\'' . $_GET['rid'] . '\' ';
$DB->query($SQL);
$style = '<style>' . "\n" . '';
$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
$style .= '</style>' . "\n" . '';
echo $style;
echo '<div class="tipsinfo">时间：<b>' . date('Y-m-d H:i:s') . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo">问卷：<b>' . $Row['surveyTitle'] . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo">数据序号：<b>' . $_GET['rid'] . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo"><span class=green>安全锁解锁成功</span></div>' . "\n" . '';
exit();

?>
