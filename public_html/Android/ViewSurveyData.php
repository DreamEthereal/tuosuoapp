<?php
//dezend by http://www.yunlu99.com/
function getnextdataid($responseID, $flag)
{
	global $DB;
	global $_GET;
	global $table_prefix;

	if ($flag == 1) {
		$_obf_dzT6Sg__ = ' SELECT responseID,joinTime,ipAddress,area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID > \'' . $responseID . '\' AND area = \'' . $_SESSION['administratorsName'] . '\' ORDER BY responseID ASC LIMIT 1 ';
	}
	else {
		$_obf_dzT6Sg__ = ' SELECT responseID,joinTime,ipAddress,area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID < \'' . $responseID . '\' AND area = \'' . $_SESSION['administratorsName'] . '\' ORDER BY responseID DESC LIMIT 1 ';
	}

	$_obf_oNcPDA__ = $DB->queryFirstRow($_obf_dzT6Sg__);
	if (($_obf_oNcPDA__['responseID'] == '') || ($_obf_oNcPDA__['responseID'] == 0)) {
		return 0;
	}

	return $_obf_oNcPDA__['responseID'];
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$isAndroidView = 1;
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
$R_Row = $DB->queryFirstRow($SQL);
$theDataAuthArray = explode('$$$', getdataauth($_GET['surveyID'], $_GET['responseID'], $R_Row, $Sur_G_Row));
$haveViewDataAuth = $theDataAuthArray[0];
$haveEditDataAuth = $theDataAuthArray[1];
$haveDeleDataAuth = $theDataAuthArray[2];
$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'uAndroidDataDetail.html');
$EnableQCoreClass->set_CycBlock('ResultDetailFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');

if ($haveViewDataAuth == 0) {
	_showerror($lang['auth_error'], $lang['passport_is_permit'] . ':' . $lang['no_auth_view_data']);
}

if ($_SESSION['ViewBackURL'] != '') {
	$EnableQCoreClass->replace('lastURL', $_SESSION['ViewBackURL']);
}
else {
	$EnableQCoreClass->replace('lastURL', $thisProg);
}

$isViewAuthInfo = 0;
$theResponseID = $_GET['responseID'];
$thePageSurveyID = $_GET['surveyID'];
require ROOT_PATH . 'Process/Page.inc.php';
$thisPageStep = ($R_Row['replyPage'] == 0 ? count($pageBreak) + 1 : $R_Row['replyPage']);

if ($Sur_G_Row['isAllData'] == 1) {
	foreach ($QtnListArray as $questionID => $theQtnArray) {
		$surveyID = $_GET['surveyID'];
		$joinTime = $R_Row['joinTime'];
		$ModuleName = $Module[$theQtnArray['questionType']];
		$EnableQCoreClass->replace('questionID', $questionID);

		if ($haveEditDataAuth == 1) {
			if (in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
				$EnableQCoreClass->replace('isSingleDataModi', 'none');
			}
			else {
				$EnableQCoreClass->replace('isSingleDataModi', '');
			}
		}
		else {
			$EnableQCoreClass->replace('isSingleDataModi', 'none');
		}

		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
		$EnableQCoreClass->parse('question', 'QUESTION', true);
	}
}
else {
	$check_survey_conditions_list = '';

	foreach ($pageQtnList as $tmp => $thePageQtnList) {
		if ($thisPageStep < $tmp) {
			break;
		}

		foreach ($thePageQtnList as $questionID) {
			$theQtnArray = $QtnListArray[$questionID];
			$surveyID = $_GET['surveyID'];
			$joinTime = $R_Row['joinTime'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			$EnableQCoreClass->replace('questionID', $questionID);

			if ($haveEditDataAuth == 1) {
				if (in_array($theQtnArray['questionType'], array('8', '9', '12', '30'))) {
					$EnableQCoreClass->replace('isSingleDataModi', 'none');
				}
				else {
					$EnableQCoreClass->replace('isSingleDataModi', '');
				}
			}
			else {
				$EnableQCoreClass->replace('isSingleDataModi', 'none');
			}

			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}
}

if ($Sur_G_Row['isAllData'] == 1) {
	$EnableQCoreClass->replace('check_survey_conditions_list', '');
}
else {
	$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
}

$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName']);
$EnableQCoreClass->replace('ipAddress', $R_Row['ipAddress']);
$EnableQCoreClass->replace('area', $R_Row['area']);
$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $R_Row['joinTime']));
$EnableQCoreClass->replace('submitTime', $R_Row['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['submitTime']));
$EnableQCoreClass->replace('overTime', sectotime($R_Row['overTime']));

switch ($R_Row['overFlag']) {
case '0':
default:
	$EnableQCoreClass->replace('overFlag', $lang['result_no_all']);
	break;

case '1':
	$EnableQCoreClass->replace('overFlag', $lang['result_have_all']);
	break;

case '2':
	$EnableQCoreClass->replace('overFlag', $lang['result_to_quota']);
	break;

case '3':
	$EnableQCoreClass->replace('overFlag', $lang['result_in_export']);
	break;
}

switch ($R_Row['dataSource']) {
case '0':
default:
	$dataForm = '未知数据来源';
	break;

case '1':
	$dataForm = 'PC浏览器';
	break;

case '2':
	$dataForm = '移动浏览器';
	break;

case '3':
	$dataForm = '安卓样本App';
	break;

case '4':
	$dataForm = 'PC访员录入';
	break;

case '5':
	$dataForm = '在线访员App';
	break;

case '6':
	$dataForm = '离线访员App';
	break;

case '7':
	$dataForm = 'Excel数据导入';
	break;

case '8':
	$dataForm = '问卷数据迁移';
	break;
}

if ($R_Row['uniDataCode'] != '') {
	$this_uniDataCode = explode('######', base64_decode($R_Row['uniDataCode']));
	$EnableQCoreClass->replace('uniDataCode', $this_uniDataCode[0] . ' (' . $dataForm . ')');
}
else {
	$EnableQCoreClass->replace('uniDataCode', $dataForm);
}

switch ($R_Row['authStat']) {
case '0':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;

case '1':
	switch ($R_Row['appStat']) {
	case '0':
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
		break;

	default:
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat'] . '_' . $R_Row['appStat']]);
		break;
	}

	break;

case '2':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;

case '3':
	$EnableQCoreClass->replace('authStat', $lang['authStat_' . $R_Row['authStat']]);
	break;
}

$aSQL = ' SELECT a.administratorsID,a.nickName,a.userGroupID,a.groupType,b.taskTime,b.authStat,b.appStat,b.reason,b.nextUserId FROM ' . ADMINISTRATORS_TABLE . ' a,' . DATA_TASK_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID=\'' . $_GET['surveyID'] . '\' AND b.responseID =\'' . $_GET['responseID'] . '\' ORDER BY b.taskTime DESC ';
$aResult = $DB->query($aSQL);
$aRecNum = $DB->_getNumRows($aResult);

if ($aRecNum == 0) {
	$EnableQCoreClass->replace('authInfoList', '');
}
else {
	$EnableQCoreClass->setTemplateFile('ShowAuthInfoFile', 'uAuthInfoList.html');
	$EnableQCoreClass->set_CycBlock('ShowAuthInfoFile', 'AUTHINFO', 'authinfo');
	$EnableQCoreClass->replace('authinfo', '');
	$tmp = 0;

	while ($aRow = $DB->queryArray($aResult)) {
		$tmp++;
		$authInfoList = '(' . $tmp . ')&nbsp;' . _getuserallname($aRow['nickName'], $aRow['userGroupID'], $aRow['groupType']);

		switch ($aRow['authStat']) {
		case '0':
		case '2':
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']];
			$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
			break;

		case '3':
			$nSQL = ' SELECT nickName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $aRow['nextUserId'] . '\' ';
			$nRow = $DB->queryFirstRow($nSQL);
			$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']] . '，并提交给：' . _getuserallname($nRow['nickName'], $nRow['userGroupID'], $nRow['groupType']) . '再审核';
			$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
			break;

		case '1':
			switch ($aRow['appStat']) {
			case '0':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat']];
				$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
				break;

			case '3':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据状态为：' . $lang['authStat_' . $aRow['authStat'] . '_' . $aRow['appStat']];
				break;

			case '1':
			case '2':
				$authInfoList .= '于' . date('Y-m-d H:i:s', $aRow['taskTime']) . '改变该数据审核状态为：' . $lang['authStat_' . $aRow['authStat'] . '_' . $aRow['appStat']];
				$authInfoList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;批注信息为：' . $aRow['reason'];
				break;
			}

			break;
		}

		$EnableQCoreClass->replace('authInfoContent', $authInfoList);
		$EnableQCoreClass->parse('authinfo', 'AUTHINFO', true);
	}

	$EnableQCoreClass->replace('authInfoList', $EnableQCoreClass->parse('ShowAuthPage', 'ShowAuthInfoFile'));
}

$thisURL = $thisProg . '&Does=View&responseID=';
$theNextId = getnextdataid($_GET['responseID'], 1);

if ($theNextId == 0) {
	$EnableQCoreClass->replace('next1URL', $thisProg);
}
else {
	$EnableQCoreClass->replace('next1URL', $thisURL . $theNextId);
}

$theLastId = getnextdataid($_GET['responseID'], 2);

if ($theLastId == 0) {
	$EnableQCoreClass->replace('last1URL', $thisProg);
}
else {
	$EnableQCoreClass->replace('last1URL', $thisURL . $theLastId);
}

if ($haveEditDataAuth == 1) {
	$modiDataURL = $thisProg . '&Does=ModiData&responseID=' . $_GET['responseID'];
	$EnableQCoreClass->replace('modiDataURL', $modiDataURL);
}
else {
	$EnableQCoreClass->replace('isAuthDataModi', 'none');
	$EnableQCoreClass->replace('modiDataURL', '');
}

$ResultDetail = $EnableQCoreClass->parse('ResultDetail', 'ResultDetailFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
$ResultDetail = str_replace($All_Path, '', $ResultDetail);
$ResultDetail = str_replace('PerUserData', '../PerUserData', $ResultDetail);

if ($Config['dataDirectory'] != 'PerUserData') {
	$ResultDetail = str_replace($Config['dataDirectory'], '../' . $Config['dataDirectory'], $ResultDetail);
}

$ResultDetail = str_replace('borderColor=#e5e5e5', 'borderColor=#ffffff', $ResultDetail);
echo $ResultDetail;
exit();

?>
