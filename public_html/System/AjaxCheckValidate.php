<?php
//dezend by http://www.yunlu99.com/
function out($length = 6)
{
	$_obf_3aJ0IRazgVcC9qtlF9k_ = '';
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $length; $_obf_7w__++) {
		$_obf_3aJ0IRazgVcC9qtlF9k_ .= rand(0, 9);
	}

	return $_obf_3aJ0IRazgVcC9qtlF9k_;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'License/License.xml';
$RE29199C6C5DC97F47564201E7E599AC9 = 1;

if (!f9391164fhdd20582371460dbe4fbae86fghk20100930()) {
	if ($isLoginCheck == 1) {
		exit('false######' . _skipitenablechar($lang['R82353783517DA1951018F2CE07568E40']));
	}
	else {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}
}

if ($License['MasterAddress'] != 'N/A') {
	$IpAddress = _getserveripaddress();

	if (!in_array($IpAddress, array($License['MasterAddress'], $License['MinorAddress']))) {
		if ($isLoginCheck == 1) {
			exit('false######' . _skipitenablechar($lang['D82353783517EA0051018F2CE07569F45']));
		}
		else {
			_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['D82353783517EA0051018F2CE07569F45']);
		}
	}
}

$SQL = ' SELECT license,licensetime FROM ' . BASESETTING_TABLE;
$LicenseRow = $DB->queryFirstRow($SQL);
if ($LicenseRow['license'] && $LicenseRow['licensetime']) {
	if ($License['LimitedTime'] != 'N/A') {
		if ($License['isEvalUsers'] == 1) {
			$AfterDecTime = base64_decode(base64_decode($LicenseRow['licensetime']));
			$thisTryTime = time() - $AfterDecTime;
			if (($License['LimitedTime'] < @round($thisTryTime / 86400)) || (@round($thisTryTime / 86400) < 0)) {
				if ($isLoginCheck == 1) {
					exit('false######' . _skipitenablechar($lang['R82353783517DA1951018F2CE07569F45']));
				}
				else {
					_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07569F45']);
				}
			}
		}
		else {
			$AfterDecTime = base64_decode(base64_decode($LicenseRow['licensetime']));
			$thisTryTime = time() - $AfterDecTime;
			if (($License['LimitedTime'] < date('Y-m-d')) || (@round($thisTryTime / 86400) < 0)) {
				if ($isLoginCheck == 1) {
					exit('false######' . _skipitenablechar($lang['R82353783517DA1951018F2CE07569F45']));
				}
				else {
					_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07569F45']);
				}
			}
		}
	}
}
else {
	$LicenseStrText = $TempText = '';
	$i = 0;

	for (; $i < 6; $i++) {
		$LicenseStrText .= $TempText . out();
		$TempText = '-';
	}

	$SQL = ' SELECT joinTime FROM ' . SURVEY_TABLE . ' ORDER BY joinTime ASC LIMIT 0,1 ';
	$TimeRow = $DB->queryFirstRow($SQL);

	if (!$TimeRow) {
		$TimeNow = time();
	}
	else {
		$TimeNow = $TimeRow['joinTime'];
	}

	$AfterEncTime = base64_encode(base64_encode($TimeNow));

	if (!$LicenseRow) {
		$SQL = ' INSERT INTO ' . BASESETTING_TABLE . ' SET isUseOriPassport =1,license=\'' . $LicenseStrText . '\',licensetime=\'' . $AfterEncTime . '\' ';
	}
	else {
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET license=\'' . $LicenseStrText . '\',licensetime=\'' . $AfterEncTime . '\' ';
	}

	$DB->query($SQL);
}

?>
