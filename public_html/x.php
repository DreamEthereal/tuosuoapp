<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
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
	$BaseSQL = ' SELECT isUseOriPassport,ajaxLoginURL,license,ajaxTokenURL,isPostMethod FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($BaseSQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '2':
		$hashCode = md5(trim($BaseRow['license']));
		if (($_GET['hashCode'] == $hashCode) || ($_SESSION['hash_Code'] == $hashCode)) {
			if ($_GET['task'] == 'n4') {
				if (trim($_GET['username']) == '') {
					if ($_SESSION['membersName'] == '') {
						header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&' . $_SERVER['QUERY_STRING']);
						exit();
					}
					else {
						$_GET['username'] = base64_encode($_SESSION['membersName']);
					}
				}
			}
			else if (trim($_GET['username']) == '') {
				header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&' . $_SERVER['QUERY_STRING']);
				exit();
			}

			if (trim($BaseRow['ajaxLoginURL']) != '') {
				$token = '';

				if (trim($BaseRow['ajaxTokenURL']) != '') {
					if ($BaseRow['isPostMethod'] == 1) {
						$postData = array();
						$postData['hash'] = $hashCode;
						$token = post_gbk_data_to_host(trim($BaseRow['ajaxTokenURL']), $postData);
					}
					else {
						if (strpos(trim($BaseRow['ajaxTokenURL']), '?') === false) {
							$ajaxURL = trim($BaseRow['ajaxTokenURL']) . '?hash=' . $hashCode;
						}
						else {
							$ajaxURL = trim($BaseRow['ajaxTokenURL']) . '&hash=' . $hashCode;
						}

						$token = get_url_content($ajaxURL);
					}
				}

				if ($BaseRow['isPostMethod'] == 1) {
					$username = trim($_GET['username']);
					$postData = array();
					$postData['surveyID'] = $S_Row['surveyID'];
					$postData['username'] = $username;
					$postData['hash'] = $hashCode;
					$sign = md5('surveyID=' . $S_Row['surveyID'] . '&username=' . $username . '&hash=' . $hashCode . '&' . $token);
					$postData['sign'] = $sign;
					$ajaxRtnContent = post_gbk_data_to_host(trim($BaseRow['ajaxLoginURL']), $postData);
				}
				else {
					if (strpos(trim($BaseRow['ajaxLoginURL']), '?') === false) {
						$ajaxURL = trim($BaseRow['ajaxLoginURL']) . '?';
					}
					else {
						$ajaxURL = trim($BaseRow['ajaxLoginURL']) . '&';
					}

					if ($Config['data_base64_enable'] == 1) {
						$username = $signname = trim($_GET['username']);
					}
					else {
						$username = urlencode(trim($_GET['username']));
						$signname = trim($_GET['username']);
					}

					$sign = md5('surveyID=' . $S_Row['surveyID'] . '&username=' . $signname . '&hash=' . $hashCode . '&' . $token);
					$ajaxURL .= 'surveyID=' . $S_Row['surveyID'] . '&username=' . $username . '&hash=' . $hashCode . '&sign=' . $sign . '&' . $_SERVER['QUERY_STRING'];
					$ajaxRtnContent = get_url_content($ajaxURL);
				}

				$AjaxRtnInfo = explode('|', $ajaxRtnContent);

				if ($AjaxRtnInfo[0] == 'false') {
					_shownotes($lang['system_error'], $AjaxRtnInfo[1], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
				else {
					$haveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE administratorsName = \'' . trim($_GET['username']) . '\' AND overFlag !=0 LIMIT 0,1 ';
					$haveRow = $DB->queryFirstRow($haveSQL);

					if ($haveRow) {
						_shownotes($lang['auth_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
					}

					$_SESSION['passPort_' . $S_Row['surveyID']] = true;

					if ($Config['data_base64_enable'] == 1) {
						$_SESSION['userName'] = base64_decode(trim($_GET['username']));
					}
					else {
						$_SESSION['userName'] = trim($_GET['username']);
					}

					$ajaxRtnValue = explode('#', $AjaxRtnInfo[1]);

					if (6 < count($ajaxRtnValue)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValue);
					}

					$_SESSION['ajaxCount'] = $ajaxCount;
					$ajaxRtnValueCate = array();
					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$ajaxRtnValueSign = explode('=', $ajaxRtnValue[$i]);
						$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
						$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
					}

					if ($S_Row['ajaxRtnValue'] == '') {
						$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
			else {
				header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&' . $_SERVER['QUERY_STRING']);
				exit();
			}
		}
		else {
			_shownotes($lang['system_error'], 'EnableQ Security Violation', $lang['survey_gname'] . $S_Row['surveyTitle']);
		}

		break;
	}

	break;
}

header('Location:q.php?qid=' . $S_Row['surveyID'] . '&qlang=' . $S_Row['lang'] . '&' . $_SERVER['QUERY_STRING']);
exit();

?>
