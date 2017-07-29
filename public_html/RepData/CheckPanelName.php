<?php
//dezend by http://www.yunlu99.com/
function fetchrepeatmemberinarray($ar)
{
	$_obf_UHstX_uAqHAtthQ7 = array();
	$_obf_juwe = 0;

	foreach ($ar as $_obf_5w__ => $_obf_6A__) {
		$_obf_1kYoleJ2 = array_keys($ar, $_obf_6A__);

		if (1 < count($_obf_1kYoleJ2)) {
			foreach ($_obf_1kYoleJ2 as $_obf_8w__) {
				$_obf_UHstX_uAqHAtthQ7[$_obf_juwe][$_obf_8w__] = $ar[$_obf_8w__];
				unset($ar[$_obf_8w__]);
			}

			$_obf_juwe++;
		}
		else {
			unset($ar[$_obf_5w__]);
		}
	}

	return $_obf_UHstX_uAqHAtthQ7;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,surveyID,surveyTitle,projectType,projectOwner,isViewAuthData,isDeleteData,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
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

$thisProg = ROOT_PATH . '/RepData/CheckPanelName.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);

if ($_POST['formAction'] == 'DeleteSubmit') {
	_checkroletype('1|2|5');
	require ROOT_PATH . 'Analytics/DeleSurveyDataBat.php';
}

$EnableQCoreClass->setTemplateFile('CheckPanelNamePageFile', 'CheckPanelName.html');
$EnableQCoreClass->replace('surveyID', $Sur_G_Row['surveyID']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,overFlag0,authStat,version,adminID,appStat,taskID FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' b ';

if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
	$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
}

$SQL .= ' WHERE ' . $dataSource;
$Result = $DB->query($SQL);
$theData = array();
$theExistData = array();

while ($Row = $DB->queryArray($Result)) {
	if (trim($Row['administratorsName']) != '') {
		$theData[$Row['responseID']] = trim($Row['administratorsName']);
		$theExistData[$Row['responseID']] = $Row['taskID'] . '###' . $Row['ipAddress'] . '###' . $Row['joinTime'] . '###' . $Row['area'] . '###' . $Row['overFlag'] . '###' . $Row['overFlag0'] . '###' . $Row['authStat'] . '###' . $Row['appStat'];
	}
}

$theRepeatData = fetchrepeatmemberinarray($theData);
$EnableQCoreClass->set_CycBlock('CheckPanelNamePageFile', 'GROUP', 'group');
$EnableQCoreClass->set_CycBlock('GROUP', 'LIST', 'list');
$EnableQCoreClass->replace('group', '');

foreach ($theRepeatData as $tmp => $theDataList) {
	$EnableQCoreClass->replace('list', '');
	$EnableQCoreClass->replace('tmp', $tmp + 1);

	foreach ($theDataList as $responseID => $administratorsName) {
		$EnableQCoreClass->replace('responseID', $responseID);
		$theRow = explode('###', $theExistData[$responseID]);

		if ($Sur_G_Row['projectType'] == 1) {
			$EnableQCoreClass->replace('administratorsName', $administratorsName . '(' . $theRow[0] . ')');
		}
		else {
			$EnableQCoreClass->replace('administratorsName', $administratorsName);
		}

		$EnableQCoreClass->replace('ipAddress', $theRow[1]);
		$EnableQCoreClass->replace('area', $theRow[3]);
		$EnableQCoreClass->replace('joinTime', date('y-m-d H:i:s', $theRow[2]));
		$EnableQCoreClass->replace('createDate', $theRow[2]);
		$EnableQCoreClass->replace('overFlag', $theRow[4]);
		$EnableQCoreClass->replace('overFlag0', $theRow[5]);

		switch ($theRow[4]) {
		case '0':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/iyellow.png) repeat-y top left');
			break;

		case '1':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff');
			break;

		case '2':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/ired.png) repeat-y top left');
			break;

		case '3':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/igreen.png) repeat-y top left');
			break;
		}

		switch ($theRow[6]) {
		case '0':
			$EnableQCoreClass->replace('authColor', '#ffffff');
			$EnableQCoreClass->replace('authStat', $lang['authStat_' . $theRow[6]]);
			break;

		case '1':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/igreen.png) repeat-y top left');

			switch ($theRow[7]) {
			case '0':
				$EnableQCoreClass->replace('authStat', $lang['authStat_' . $theRow[6]]);
				break;

			default:
				switch ($theRow[7]) {
				case '2':
					$EnableQCoreClass->replace('authStat', '<font color=red>…ÍÀﬂ ß∞‹</font>');
					break;

				case '3':
					$EnableQCoreClass->replace('authStat', '…ÍÀﬂ÷–');
					$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iorange.png) repeat-y top left');
					break;

				default:
					$EnableQCoreClass->replace('authStat', $lang['authStat_' . $theRow[6] . '_' . $theRow[7]]);
					break;
				}

				break;
			}

			break;

		case '2':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/ired.png) repeat-y top left');
			$EnableQCoreClass->replace('authStat', $lang['authStat_' . $theRow[6]]);
			break;

		case '3':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iyellow.png) repeat-y top left');
			$EnableQCoreClass->replace('authStat', $lang['authStat_' . $theRow[6]]);
			break;
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	$EnableQCoreClass->parse('group', 'GROUP', true);
	$EnableQCoreClass->unreplace('list');
}

$EnableQCoreClass->parse('CheckPanelNamePage', 'CheckPanelNamePageFile');
$EnableQCoreClass->output('CheckPanelNamePage', false);

?>
