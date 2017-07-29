<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$thisProg = 'AjaxSurveyAllowIP.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT isAllowIP,isAllowIPMode FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$BaseRow = $DB->queryFirstRow($SQL);

if ($_POST['UpdateSubmit']) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isAllowIP = \'' . $_POST['isAllowIP'] . '\',isAllowIPMode = \'' . $_POST['isAllowIPMode'] . '\' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);

	if ($_POST['isAllowIP'] == 1) {
		if (is_array($_POST['ID'])) {
			$SQL = sprintf(' DELETE FROM ' . ALLOWIP_TABLE . ' WHERE (allowIpID IN (\'%s\'))', @join('\',\'', $_POST['ID']));
			$DB->query($SQL);
			writetolog($lang['dele_survey_ip']);
		}

		if ($_POST['startIP_new'] && $_POST['endIP_new']) {
			$start_IP = explode('.', $_POST['startIP_new']);
			$userStart = sprintf('%03s', $start_IP[0]) . '.' . sprintf('%03s', $start_IP[1]) . '.' . sprintf('%03s', $start_IP[2]) . '.' . sprintf('%03s', $start_IP[3]);
			$end_IP = explode('.', $_POST['endIP_new']);
			$userEnd = sprintf('%03s', $end_IP[0]) . '.' . sprintf('%03s', $end_IP[1]) . '.' . sprintf('%03s', $end_IP[2]) . '.' . sprintf('%03s', $end_IP[3]);
			$SQL = ' SELECT startIP FROM ' . ALLOWIP_TABLE . ' WHERE startIP=\'' . $userStart . '\' AND endIP=\'' . $userEnd . '\' AND surveyID=\'' . $_POST['surveyID'] . '\' ORDER BY startIP ASC LIMIT 0,1 ';
			$IPRow = $DB->queryFirstRow($SQL);

			if (!$IPRow) {
				$SQL = ' INSERT INTO ' . ALLOWIP_TABLE . ' SET startIP=\'' . $userStart . '\',endIP=\'' . $userEnd . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
				$DB->query($SQL);
			}

			writetolog($lang['add_survey_ip']);
		}

		if (is_array($_POST['startIP'])) {
			foreach ($_POST['startIP'] as $ID => $startIP) {
				$start_IP = explode('.', $startIP);
				$userStart = sprintf('%03s', $start_IP[0]) . '.' . sprintf('%03s', $start_IP[1]) . '.' . sprintf('%03s', $start_IP[2]) . '.' . sprintf('%03s', $start_IP[3]);
				$SQL = ' UPDATE ' . ALLOWIP_TABLE . ' SET startIP=\'' . $userStart . '\' WHERE allowIpID =\'' . $ID . '\' ';
				$DB->query($SQL);
			}

			foreach ($_POST['endIP'] as $ID => $endIP) {
				$end_IP = explode('.', $endIP);
				$userEnd = sprintf('%03s', $end_IP[0]) . '.' . sprintf('%03s', $end_IP[1]) . '.' . sprintf('%03s', $end_IP[2]) . '.' . sprintf('%03s', $end_IP[3]);
				$SQL = ' UPDATE ' . ALLOWIP_TABLE . ' SET endIP=\'' . $userEnd . '\' WHERE allowIpID =\'' . $ID . '\' ';
				$DB->query($SQL);
			}
		}
	}

	_showsucceed($lang['edit_survey_ip'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('AllowIpLoginFile', 'SurveyAllowIp.html');
$EnableQCoreClass->set_CycBlock('AllowIpLoginFile', 'LOGINIP', 'LoginIp');
$EnableQCoreClass->replace('LoginIp', '');

if ($BaseRow['isAllowIP'] == 1) {
	$EnableQCoreClass->replace('isAllowIP', 'selected');
	$EnableQCoreClass->replace('isAllowIP_Add', '');
}
else {
	$EnableQCoreClass->replace('isAllowIP', '');
	$EnableQCoreClass->replace('isAllowIP_Add', 'none');
}

$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('isAllowIPMode_' . $BaseRow['isAllowIPMode'], 'checked');
$SQL = ' SELECT * FROM ' . ALLOWIP_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY allowIpID ASC ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('ID', $Row['allowIpID']);
	$start_IP = explode('.', $Row['startIP']);
	$startIP = intval($start_IP[0]) . '.' . intval($start_IP[1]) . '.' . intval($start_IP[2]) . '.' . intval($start_IP[3]);
	$EnableQCoreClass->replace('startIP', $startIP);
	$end_IP = explode('.', $Row['endIP']);
	$endIP = intval($end_IP[0]) . '.' . intval($end_IP[1]) . '.' . intval($end_IP[2]) . '.' . intval($end_IP[3]);
	$EnableQCoreClass->replace('endIP', $endIP);
	$EnableQCoreClass->parse('LoginIp', 'LOGINIP', true);
}

$EnableQCoreClass->parse('AllowIpLogin', 'AllowIpLoginFile');
$EnableQCoreClass->output('AllowIpLogin');

?>
