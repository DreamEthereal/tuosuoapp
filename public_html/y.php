<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
$thisProg = 'y.php?qname=' . $_GET['qname'] . '&uname=' . $_GET['uname'] . '&webArea=' . $_GET['webArea'] . '&userValue=' . $_GET['userValue'] . '&otherValue=' . $_GET['otherValue'];
if (($_GET['qname'] == '') && ($_GET['qid'] == '')) {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}
else {
	$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyName=\'' . trim($_GET['qname']) . '\' OR surveyID =\'' . trim($_GET['qid']) . '\' ';
	$S_Row = $DB->queryFirstRow($SQL);

	if (!$S_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

switch ($S_Row['status']) {
case '0':
	_shownotes($lang['status_error'], $lang['design_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	break;

case '2':
	_shownotes($lang['status_error'], $lang['end_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	break;
}

$nowTime = date('Y-m-d', time());

if ($nowTime < $S_Row['beginTime']) {
	_shownotes($lang['status_error'], $lang['no_start_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

if ($S_Row['endTime'] < $nowTime) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
	$DB->query($SQL);
	_shownotes($lang['status_error'], $lang['end_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
}

if ($S_Row['maxResponseNum'] != 0) {
	$SQL = ' SELECT COUNT(*) AS maxResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
	$CountRow = $DB->queryFirstRow($SQL);

	if ($S_Row['maxResponseNum'] <= $CountRow['maxResponseNum']) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
		$DB->query($SQL);
		_shownotes($lang['status_error'], $lang['max_num_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}
}

switch ($S_Row['isPublic']) {
case '0':
	$BaseSQL = ' SELECT isUseOriPassport,license FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($BaseSQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '2':
		$hashCode = md5(trim($BaseRow['license']));
		if (($_GET['hashCode'] == $hashCode) || ($_SESSION['hash_Code'] == $hashCode)) {
			if ((trim($_GET['uname']) == '') || (trim($_GET['userValue']) == '')) {
				header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&' . $_SERVER['QUERY_STRING']);
				exit();
			}

			if ($_GET['webArea'] != '') {
				$_SESSION['webArea'] = trim($_GET['webArea']);
			}

			$_SESSION['passPort_' . $S_Row['surveyID']] = true;
			$_SESSION['userName'] = trim($_GET['uname']);
			$AjaxRtnInfo = explode('|', trim($_GET['userValue']));

			if (6 < count($AjaxRtnInfo)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($AjaxRtnInfo);
			}

			$_SESSION['ajaxCount'] = $ajaxCount;
			$ajaxRtnValueCate = array();
			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$j = $i + 1;
				$ajaxRtnValueSign = explode('*', $AjaxRtnInfo[$i]);
				$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
				$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
			}

			if ($S_Row['ajaxRtnValue'] == '') {
				$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
				$DB->query($SQL);
			}
		}
		else {
			_shownotes($lang['system_error'], 'EnableQ Security Violation', $lang['survey_gname'] . $S_Row['surveyTitle']);
		}

		break;
	}

	break;
}

header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&otherValue=' . $_GET['otherValue']);

?>
