<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
@set_time_limit(0);
_checkroletype('1');
if (!isset($_GET['qid']) || ($_GET['qid'] == '') || ($_GET['qid'] == 0)) {
	_showerror('��������', '�������󣺴�����ʾ����');
}

if (!isset($_GET['rid']) || ($_GET['rid'] == '') || ($_GET['rid'] == 0)) {
	_showerror('��������', '�������󣺴����������Ų���');
}

$SQL = ' SELECT surveyTitle,status FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['qid'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if (!$Row) {
	_showerror('��������', '�������󣺲����ڵ��ʾ����');
}

if ($Row['status'] == 0) {
	_showerror('��������', '�������󣺴�����ʾ�״̬');
}

$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['qid'] . ' SET version = 0 WHERE responseID=\'' . $_GET['rid'] . '\' ';
$DB->query($SQL);
$style = '<style>' . "\n" . '';
$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
$style .= '</style>' . "\n" . '';
echo $style;
echo '<div class="tipsinfo">ʱ�䣺<b>' . date('Y-m-d H:i:s') . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo">�ʾ�<b>' . $Row['surveyTitle'] . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo">������ţ�<b>' . $_GET['rid'] . '</b></div>' . "\n" . '';
echo '<div class="tipsinfo"><span class=green>��ȫ�������ɹ�</span></div>' . "\n" . '';
exit();

?>
