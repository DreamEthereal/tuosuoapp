<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,surveyTitle,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isOnline0View,isOnline0Auth,isViewAuthData,isViewAuthInfo,isCache,isRecord,forbidViewId FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'QuotaReCheck.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$valueLogicQtnList = array();
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if ($_POST['Action'] == 'OverFlagSubmit') {
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3')) {
		exit('<span class=red>&nbsp;&nbsp;&nbsp;' . $lang['auth_error'] . ':' . $lang['passport_is_permit'] . '</span>');
	}

	if ($_POST['responseID'] == '') {
		exit('&nbsp;&nbsp;&nbsp;<span class=red>操作要求必须先选择一条或多条数据！</span>');
	}

	$theResponseIDValue = explode(',', $_POST['responseID']);
	$theCreateDateValue = explode(',', $_POST['createDate']);
	$theOverFlagValue = explode(',', $_POST['overFlag']);
	$theOverFlag0Value = explode(',', $_POST['overFlag0']);
	$tmp = 0;

	foreach ($theResponseIDValue as $theResponseID) {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' SET overFlag0=\'' . $theOverFlagValue[$tmp] . '\',overFlag=2 WHERE responseID =\'' . $theResponseID . '\' ';
		$DB->query($SQL);
		if (($theCreateDateValue[$tmp] != '') && ($theOverFlagValue[$tmp] == 1)) {
			delcountinfo($Sur_G_Row['surveyID'], $theCreateDateValue[$tmp]);
		}

		$tmp++;
	}

	writetolog($lang['open_close_falg']);
	exit();
}

if ($_POST['Action'] == 'QuotaReCheckSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');

	if ($_POST['quotaID'] == 0) {
		exit('&nbsp;&nbsp;&nbsp;<span class=red>“需要查看的配额定义”须先选择一个配额定义方能进行</span>');
	}

	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Quota' . $_POST['surveyID']) . '.php')) {
		$theSID = $_POST['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $_POST['surveyID'] . '/' . md5('Quota' . $_POST['surveyID']) . '.php';
	$conList = '';
	$qid = $_POST['surveyID'];
	$theQuotaID = $_POST['quotaID'];
	require ROOT_PATH . 'JS/SurveyQuota.inc.php';

	if ($conList == '') {
		exit('&nbsp;&nbsp;&nbsp;<span class=red>选择查看的问卷配额未为其定义运算条件</span>');
	}

	$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_POST['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
	$MainRow = $DB->queryFirstRow($SQL);

	if (!$MainRow) {
		$EnableQCoreClass->replace('qtnNameList', '');
		$EnableQCoreClass->replace('isMainShowQtn', 'none');
		$mainShowField = '';
	}
	else {
		$EnableQCoreClass->replace('isMainShowQtn', '');

		if ($Sur_G_Row['mainShowQtn'] == 0) {
			$mainShowField = 'option_' . $MainRow['questionID'];
			$EnableQCoreClass->replace('qtnNameList', $MainRow['questionName']);
		}
		else {
			$FHSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' LIKE \'option_' . $Sur_G_Row['mainShowQtn'] . '\' ';
			$FHRow = $DB->queryFirstRow($FHSQL);

			if ($FHRow['Field'] == '') {
				$mainShowField = 'option_' . $MainRow['questionID'];
				$EnableQCoreClass->replace('qtnNameList', $MainRow['questionName']);
			}
			else {
				$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Sur_G_Row['mainShowQtn'] . '\' ';
				$QtnNameRow = $DB->queryFirstRow($SQL);
				$EnableQCoreClass->replace('qtnNameList', $QtnNameRow['questionName']);
				$mainShowField = 'option_' . $Sur_G_Row['mainShowQtn'];
			}
		}
	}

	if ($mainShowField == '') {
		$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,overFlag0,authStat FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' ';
	}
	else {
		$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,overFlag0,authStat,' . $mainShowField . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . '  ';
	}

	$SQL .= ' WHERE overFlag !=2 AND ' . $conList . ' ';
	$SQL .= ' ORDER BY responseID DESC ';
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'QuotaReCheckList.html');
	$EnableQCoreClass->set_CycBlock('ResultListFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$Result = $DB->query($SQL);
	$totalRepNum = $overFlagNum1 = $overFlagNum0 = $overFlagNum3 = 0;

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('responseID', $Row['responseID']);
		$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('joinTime', date('y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('createDate', $Row['joinTime']);
		$EnableQCoreClass->replace('overFlag', $Row['overFlag']);
		$EnableQCoreClass->replace('overFlag0', $Row['overFlag0']);

		switch ($Row['overFlag']) {
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

		switch ($Row['authStat']) {
		case '0':
			$EnableQCoreClass->replace('authColor', '#ffffff');
			break;

		case '1':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/igreen.png) repeat-y top left');
			break;

		case '2':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/ired.png) repeat-y top left');
			break;

		case '3':
			$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iyellow.png) repeat-y top left');
			break;
		}

		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);

		if ($mainShowField == '') {
			$EnableQCoreClass->replace('qtnValue', '');
			$EnableQCoreClass->replace('isMainShowQtnValue', 'none');
		}
		else {
			$EnableQCoreClass->replace('qtnValue', qlisttag($Row[$mainShowField], 1));
			$EnableQCoreClass->replace('isMainShowQtnValue', '');
		}

		$theDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $Row['responseID'], $Row, $Sur_G_Row));
		$haveViewDataAuth = $theDataAuthArray[0];
		$haveEditDataAuth = $theDataAuthArray[1];
		$haveDeleDataAuth = $theDataAuthArray[2];
		$action = '';
		$haveListData = false;

		if ($haveViewDataAuth == 1) {
			$viewURL = '../Analytics/DataList.php?surveyID=' . $Sur_G_Row['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href="' . $viewURL . '" target=_blank>' . $lang['list_action_view'] . '</a>';
			$haveListData = true;
		}

		$EnableQCoreClass->replace('action', $action);

		if ($haveListData == true) {
			$totalRepNum++;

			switch ($Row['overFlag']) {
			case '0':
				$overFlagNum0++;
				break;

			case '1':
				$overFlagNum1++;
				break;

			case '2':
				break;

			case '3':
				$overFlagNum3++;
				break;
			}

			$EnableQCoreClass->parse('list', 'LIST', true);
		}
		else {
			continue;
		}
	}

	$EnableQCoreClass->replace('totalRepNum', $totalRepNum);
	$EnableQCoreClass->replace('overFlagNum1', $overFlagNum1);
	$EnableQCoreClass->replace('overFlagNum0', $overFlagNum0);
	$EnableQCoreClass->replace('overFlagNum3', $overFlagNum3);
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		$EnableQCoreClass->replace('allowDeleted', 'disabled');
	}
	else {
		$EnableQCoreClass->replace('allowDeleted', '');
	}

	$EnableQCoreClass->parse('ResultList', 'ResultListFile');
	$ResultListPage = $EnableQCoreClass->parse('ResultList', 'ResultList');
	echo $ResultListPage;
	exit();
}

$EnableQCoreClass->setTemplateFile('QuotaReCheckFile', 'QuotaReCheck.html');
$EnableQCoreClass->set_CycBlock('QuotaReCheckFile', 'QUOTA', 'quota');
$EnableQCoreClass->replace('quota', '');

if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/' . md5('Quota' . $_GET['surveyID']) . '.php')) {
	$theSID = $_GET['surveyID'];
	require ROOT_PATH . 'Includes/QuotaCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $_GET['surveyID'] . '/' . md5('Quota' . $_GET['surveyID']) . '.php';
$theLogicNum = 0;
$SQL = ' SELECT * FROM ' . QUOTA_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY quotaID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$theLogicNum++;
	$EnableQCoreClass->replace('quotaID', $Row['quotaID']);
	$EnableQCoreClass->replace('quotaName', $Row['quotaName']);
	$EnableQCoreClass->replace('quotaNum', $Row['quotaNum']);
	$theQuotaID = $Row['quotaID'];
	require ROOT_PATH . 'System/ShowQuota.inc.php';
	$conList = '';
	$qid = $_GET['surveyID'];
	require ROOT_PATH . 'JS/SurveyQuota.inc.php';

	if ($conList == '') {
		$EnableQCoreClass->replace('quotaNowNum', '-');
		$EnableQCoreClass->replace('quotaStat', '-');
	}
	else {
		$cSQL = ' SELECT COUNT(*) as quotaNowNum FROM ' . $table_prefix . 'response_' . $qid . ' WHERE overFlag IN (1,3) AND ' . $conList . ' ';
		$cRow = $DB->queryFirstRow($cSQL);
		$EnableQCoreClass->replace('quotaNowNum', $cRow['quotaNowNum']);

		if ($Row['quotaNum'] == 0) {
			$EnableQCoreClass->replace('quotaStat', '-');
		}
		else {
			if ($cRow['quotaNowNum'] < $Row['quotaNum']) {
				$quotaStat = '<font color=red>未满</font>';
			}
			else if ($cRow['quotaNowNum'] == $Row['quotaNum']) {
				$quotaStat = '<font color=green>已满</font>';
			}
			else if ($Row['quotaNum'] < $cRow['quotaNowNum']) {
				$quotaStat = '<font color=red>超配额</font>';
			}

			$EnableQCoreClass->replace('quotaStat', $quotaStat);
		}
	}

	$EnableQCoreClass->parse('quota', 'QUOTA', true);
}

$EnableQCoreClass->replace('totalResponseNum', $theLogicNum);

if ($theLogicNum == 0) {
	$EnableQCoreClass->replace('isHaveQuota', 'disabled');
	$EnableQCoreClass->replace('isHaveQuotaText', '<tr><td colspan=5 style="padding-left:10px"><span class=red>当前调查问卷未有配额定义</span></td></tr>');
}
else {
	$EnableQCoreClass->replace('isHaveQuota', '');
	$EnableQCoreClass->replace('isHaveQuotaText', '');
}

$EnableQCoreClass->parse('QuotaReCheck', 'QuotaReCheckFile');
$EnableQCoreClass->output('QuotaReCheck');

?>
