<?php
//dezend by http://www.yunlu99.com/
function clearstring($string)
{
	$string = str_replace('\\\'', '', $string);
	$string = str_replace('\\"', '', $string);
	$string = str_replace('\\', '', $string);
	$string = str_replace('&', '', $string);
	return $string;
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.entry.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';

if ($License['isOnline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

_checkpassport('4', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$thisProg = 'ShowInputData.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$valueLogicQtnList = array();
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
$isMobile = 1;

if ($_POST['DeleteSubmit']) {
	if ($Sur_G_Row['isDeleteData'] == 1) {
		_showerror('权限错误', '权限错误：本问卷回复数据已被系统管理员禁止删除操作，您的操作无法继续！');
	}

	require ROOT_PATH . 'Analytics/DeleSurveyDataBat.php';
}

if ($_GET['Does'] == 'Delete') {
	$SQL = ' SELECT authStat FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch ($Row['authStat']) {
	case '0':
	case '2':
		if ($Sur_G_Row['isDeleteData'] == 1) {
			_showerror('权限错误', '权限错误：本问卷回复数据已被系统管理员禁止删除操作，您的操作无法继续！');
		}

		break;

	default:
		_showerror('状态错误', '状态错误：该回复数据目前已审核通过或正在审核中，您的操作无法继续！');
		break;
	}

	require ROOT_PATH . 'Analytics/DeleSurveyData.php';
}

if ($_GET['Does'] == 'DeleFile') {
	require ROOT_PATH . 'Analytics/DeleUploadFile.php';
}

if ($_GET['Does'] == 'ModiData') {
	if ($Sur_G_Row['isModiData'] == 1) {
		_showerror('权限错误', '权限错误：本问卷回复数据已被系统管理员禁止修改操作，您的操作无法继续！');
	}

	$SQL = ' SELECT authStat,overFlag,isReAuth FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch ($Row['authStat']) {
	case '0':
	case '2':
		if ($Row['overFlag'] == 2) {
			_showerror('状态错误', '状态错误：无效回复的数据无法进行修改操作，您的操作无法继续！');
		}
		else {
			if (($Row['authStat'] == 2) && ($Row['isReAuth'] == 0)) {
				_showerror('状态错误', '状态错误：本条数据已被审核员作废卷处理，您的操作无法继续！');
			}
		}

		break;

	default:
		_showerror('状态错误', '状态错误：该回复数据目前已审核通过或正在审核中，您的操作无法继续！');
		break;
	}

	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php';
	require ROOT_PATH . 'Android/ModiSurveyData.php';
}

if ($_GET['Does'] == 'View') {
	require ROOT_PATH . 'Android/ViewSurveyData.php';
}

$EnableQCoreClass->setTemplateFile('ResultListFile', 'uAndroidDataList.html');
$EnableQCoreClass->set_CycBlock('ResultListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$ConfigRow['topicNum'] = 20;
$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,authStat,taskID,isReAuth FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ';
$SQL .= ' WHERE area = \'' . $_SESSION['administratorsName'] . '\' ';
$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalResponseNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$ViewBackURL = $thisProg . '&pageID=' . $pageID;
$_SESSION['ViewBackURL'] = $ViewBackURL;
$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('responseID', $Row['responseID']);

	if (trim($Row['administratorsName']) == '') {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
	}
	else {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']) . '<br/><span style="font-size:20px">(' . trim($Row['administratorsName']) . ')</span>');
	}

	$EnableQCoreClass->replace('overFlag', $Row['overFlag']);
	$EnableQCoreClass->replace('createDate', $Row['joinTime']);

	switch ($Row['overFlag']) {
	case '0':
	default:
		$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/iyellow.png) repeat-y top left');
		$EnableQCoreClass->replace('stat', $lang['result_no_all']);
		break;

	case '1':
		$EnableQCoreClass->replace('noHaveAll', '#ffffff');
		$EnableQCoreClass->replace('stat', $lang['result_have_all']);
		break;

	case '2':
		$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/ired.png) repeat-y top left');
		$EnableQCoreClass->replace('stat', $lang['result_to_quota']);
		break;

	case '3':
		$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/igreen.png) repeat-y top left');
		$EnableQCoreClass->replace('stat', $lang['result_in_export']);
		break;
	}

	switch ($Row['authStat']) {
	case '0':
		$EnableQCoreClass->replace('authColor', '#ffffff');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;

	case '1':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/igreen.png) repeat-y top left');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;

	case '2':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/ired.png) repeat-y top left');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;

	case '3':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iyellow.png) repeat-y top left');
		$EnableQCoreClass->replace('authStat', $lang['authStat_3']);
		break;
	}

	$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
	$action = '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a>&nbsp;&nbsp;';

	if (in_array($Row['authStat'], array(0, 2))) {
		if ($Row['overFlag'] != 2) {
			if ($Sur_G_Row['isModiData'] != 1) {
				if ($Row['authStat'] == 2) {
					if ($Row['isReAuth'] == 1) {
						$modiURL = $thisProg . '&Does=ModiData&responseID=' . $Row['responseID'];
						$action .= '<a href=\'' . $modiURL . '\'>' . $lang['list_action_modi'] . '</a> ';
					}
				}
				else {
					$modiURL = $thisProg . '&Does=ModiData&responseID=' . $Row['responseID'];
					$action .= '<a href=\'' . $modiURL . '\'>' . $lang['list_action_modi'] . '</a> ';
				}
			}
		}

		if ($Sur_G_Row['isDeleteData'] == 1) {
			$EnableQCoreClass->replace('isDelete', 'none');
			$EnableQCoreClass->replace('isDeleAllowed', 'disabled');
			$EnableQCoreClass->replace('deleteURL', '');
		}
		else {
			$EnableQCoreClass->replace('isDelete', '');
			$EnableQCoreClass->replace('isDeleAllowed', '');
			$EnableQCoreClass->replace('deleteURL', $thisProg . '&overFlag=' . $Row['overFlag'] . '&Does=Delete&responseID=' . $Row['responseID'] . '&createDate=' . $Row['joinTime'] . '&area=' . $Row['area']);
		}
	}
	else {
		$EnableQCoreClass->replace('isDelete', 'none');
		$EnableQCoreClass->replace('isDeleAllowed', 'disabled');
		$EnableQCoreClass->replace('deleteURL', '');
	}

	$EnableQCoreClass->replace('action', $action);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('ResultList', 'ResultListFile');
$EnableQCoreClass->output('ResultList');

?>
