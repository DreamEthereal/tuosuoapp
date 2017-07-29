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
$SQL = ' SELECT surveyID,status,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isOnline0View,isOnline0Auth,isViewAuthData,isExportData,isCache,isRecord,forbidViewId,isAllData,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'EventAward.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$thisURL = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);

if ($_POST['Action'] == 'AwardRandSubmit') {
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$SQL = ' INSERT INTO ' . AWARDLIST_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',awardListID=\'' . $_POST['awardListID'] . '\',responseID=\'' . $_POST['responseID'] . '\',ipAddress=\'' . $_POST['ipAddress'] . '\',administratorsName=\'' . $_POST['administratorsName'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['award_rand']);
	_showsucceed($lang['award_rand'], $thisProg . '&ActionDO=AwardRand&awardType=' . $_POST['awardListID']);
}

if ($_GET['ActionDO'] == 'AwardRand') {
	if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
	include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
	$EnableQCoreClass->setTemplateFile('ResultAwardRandFile', 'ResultAwardRand.html');
	$EnableQCoreClass->replace('productURL', $thisProg . '&ActionDO=AwardProduct');
	$EnableQCoreClass->replace('awardURL', $thisProg . '&ActionDO=AwardRand');

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
	}

	if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
		$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
	}
	else {
		$dataSource = getdatasourcesql(0, $_GET['surveyID']);
	}

	$SQL = ' SELECT COUNT(responseID) AS totalResponseNum FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ' . $dataSource;
	$Row = $DB->queryFirstRow($SQL);
	$totalResponseNum = $Row['totalResponseNum'];
	$EnableQCoreClass->replace('totalResponseNum', $Row['totalResponseNum']);
	$EnableQCoreClass->set_CycBlock('ResultAwardRandFile', 'AWARDS', 'awards');
	$EnableQCoreClass->replace('awards', '');
	$SQL = ' SELECT * FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY awardListID ASC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('awardListID', $Row['awardListID']);
		$EnableQCoreClass->replace('awardType', $Row['awardType']);
		$EnableQCoreClass->replace('awardProduct', $Row['awardProduct']);
		$EnableQCoreClass->replace('awardNum', $Row['awardNum']);
		$CountSQL = ' SELECT COUNT(*) AS awardOverNum FROM ' . AWARDLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND awardListID=\'' . $Row['awardListID'] . '\' ';
		$CountRow = $DB->queryFirstRow($CountSQL);
		$EnableQCoreClass->replace('awardOverNum', $CountRow['awardOverNum']);
		$awardTypeID = ($_POST['awardType'] ? $_POST['awardType'] : $_GET['awardType']);

		if ($awardTypeID == $Row['awardListID']) {
			$EnableQCoreClass->replace('awardType_selected', 'selected');

			if ($CountRow['awardOverNum'] == $Row['awardNum']) {
				$isOverRand = true;
			}
		}
		else {
			$EnableQCoreClass->replace('awardType_selected', '');
		}

		$EnableQCoreClass->parse('awards', 'AWARDS', true);
	}

	$SQL = ' SELECT DISTINCT administratorsName,ipAddress FROM ' . AWARDLIST_TABLE . ' ';
	$Result = $DB->query($SQL);
	$Ori_Name_List = array();
	$Ori_IP_List = array();

	while ($Row = $DB->queryArray($Result)) {
		if ($Row['administratorsName'] != '') {
			$Ori_Name_List[] = '\'' . $Row['administratorsName'] . '\'';
		}

		if ($Row['ipAddress'] != '') {
			$Ori_IP_List[] = '\'' . $Row['ipAddress'] . '\'';
		}
	}

	if (!empty($Ori_Name_List)) {
		$LimitNameList = implode(',', $Ori_Name_List);
	}

	if (!empty($Ori_IP_List)) {
		$LimitIPList = implode(',', $Ori_IP_List);
	}

	$SQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.overFlag IN (1,3) and ' . $dataSource;
	if (!empty($Ori_Name_List) && ($Sur_G_Row['isPublic'] == '0')) {
		$SQL .= ' AND b.administratorsName NOT IN (' . $LimitNameList . ') ';
	}

	if (!empty($Ori_IP_List) && ($Sur_G_Row['isPublic'] != '0')) {
		$SQL .= ' AND b.ipAddress NOT IN (' . $LimitIPList . ') ';
	}

	$SQL .= ' ORDER BY RAND() LIMIT 0, 1 ';
	$Row = $DB->queryFirstRow($SQL);
	$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID = \'' . $Row['responseID'] . '\' ';
	$R_Row = $DB->queryFirstRow($SQL);

	if (!$R_Row) {
		$EnableQCoreClass->replace('awardNoResult', 'disabled');
		$EnableQCoreClass->replace('isOver', 'none');
		$EnableQCoreClass->replace('isNoRecord', '');
	}
	else {
		$EnableQCoreClass->replace('awardNoResult', '');
		if (($_POST['awardType'] != '') || (($_GET['awardType'] != '') && !isset($_POST['awardType']))) {
			if ($isOverRand == false) {
				$EnableQCoreClass->replace('isOver', '');
				$EnableQCoreClass->replace('isNoRecord', 'none');
			}
			else {
				$EnableQCoreClass->replace('isOver', 'none');
				$EnableQCoreClass->replace('isNoRecord', '');
			}

			$awardTypeID = ($_POST['awardType'] ? $_POST['awardType'] : $_GET['awardType']);
			$EnableQCoreClass->replace('awardTypeID', $awardTypeID);
			$EnableQCoreClass->replace('reCallURL', $thisProg . '&ActionDO=AwardRand&awardType=' . $awardTypeID);
		}
		else {
			$EnableQCoreClass->replace('isOver', 'none');
			$EnableQCoreClass->replace('isNoRecord', 'none');
			$EnableQCoreClass->replace('awardTypeID', 'none');
			$EnableQCoreClass->replace('reCallURL', '');
		}
	}

	if ($Sur_G_Row['isPublic'] == '0') {
		$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
		$BaseRow = $DB->queryFirstRow($SQL);

		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			$EnableQCoreClass->replace('isHaveGroup', '');

			if ($R_Row['administratorsGroupID'] == '0') {
				$administratorsGroupName = $lang['no_group'];
			}
			else {
				$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $R_Row['administratorsGroupID'] . '\' ';
				$GroupRow = $DB->queryFirstRow($GroupSQL);
				$administratorsGroupName = $GroupRow['administratorsGroupName'];
			}

			$EnableQCoreClass->replace('administratorsGroupName', $administratorsGroupName);
			$EnableQCoreClass->replace('ajaxRtnValue', '');
			break;

		case '2':
			$EnableQCoreClass->replace('isHaveGroup', 'none');
			$EnableQCoreClass->replace('administratorsGroupName', '');
			$EnableQCoreClass->setTemplateFile('ResultAjaxDetailFile', 'ResultAjaxDetail.html');
			$EnableQCoreClass->set_CycBlock('ResultAjaxDetailFile', 'AJAX', 'ajax');
			$EnableQCoreClass->replace('ajax', '');

			if ($Sur_G_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
					$j = $i + 1;
					$EnableQCoreClass->replace('ajaxRtnValue', $R_Row['ajaxRtnValue_' . $j]);
					$EnableQCoreClass->parse('ajax', 'AJAX', true);
				}
			}

			$EnableQCoreClass->replace('ajaxRtnValue', $EnableQCoreClass->parse('ResultAjaxDetail', 'ResultAjaxDetailFile'));
			break;

		case '4':
		case '3':
		case '5':
			$EnableQCoreClass->replace('isHaveGroup', 'none');
			$EnableQCoreClass->replace('administratorsGroupName', '');
			$EnableQCoreClass->replace('ajaxRtnValue', '');
			break;
		}
	}
	else {
		$EnableQCoreClass->replace('isHaveGroup', 'none');
		$EnableQCoreClass->replace('administratorsGroupName', '');
		$EnableQCoreClass->replace('ajaxRtnValue', '');
	}

	$EnableQCoreClass->set_CycBlock('ResultAwardRandFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$isViewAuthInfo = 0;
	$theResponseID = $Row['responseID'];
	$thePageSurveyID = $Row['surveyID'];
	require ROOT_PATH . 'Process/Page.inc.php';
	$thisPageStep = ($R_Row['replyPage'] == 0 ? count($pageBreak) + 1 : $R_Row['replyPage']);

	if ($Sur_G_Row['isAllData'] == 1) {
		$check_survey_conditions_list = '';

		foreach ($QtnListArray as $questionID => $theQtnArray) {
			$surveyID = $_GET['surveyID'];
			$joinTime = $R_Row['joinTime'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			$EnableQCoreClass->replace('questionID', $questionID);
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

	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
	$ResultDetail = $EnableQCoreClass->parse('ResultAwardRand', 'ResultAwardRandFile');
	$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -24);
	$ResultDetail = str_replace($All_Path, '', $ResultDetail);
	$ResultDetail = str_replace('PerUserData', '../PerUserData', $ResultDetail);

	if ($Config['dataDirectory'] != 'PerUserData') {
		$ResultDetail = str_replace($Config['dataDirectory'], '../' . $Config['dataDirectory'], $ResultDetail);
	}

	echo $ResultDetail;
	exit();
}

if ($_POST['AwardProductSubmit']) {
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	if (is_array($_POST['awardListID'])) {
		$SQL = sprintf(' DELETE FROM ' . AWARDPRODUCT_TABLE . ' WHERE (awardListID IN (\'%s\'))', @join('\',\'', $_POST['awardListID']));
		$DB->query($SQL);
		$SQL = sprintf(' DELETE FROM ' . AWARDLIST_TABLE . ' WHERE (awardListID IN (\'%s\'))', @join('\',\'', $_POST['awardListID']));
		$DB->query($SQL);
		writetolog($lang['dele_award_product']);
	}

	if (($_POST['awardType_new'] != '') && ($_POST['awardProduct_new'] != '')) {
		$SQL = ' INSERT INTO ' . AWARDPRODUCT_TABLE . ' SET awardType=\'' . $_POST['awardType_new'] . '\',awardProduct=\'' . $_POST['awardProduct_new'] . '\',awardNum=\'' . $_POST['awardNum_new'] . '\',surveyID=\'' . $_POST['surveyID'] . '\' ';
		$DB->query($SQL);
		writetolog($lang['add_award_product'] . ':' . $_POST['awardProduct_new']);
	}

	if (is_array($_POST['awardType'])) {
		foreach ($_POST['awardType'] as $ID => $awardType) {
			$SQL = ' UPDATE ' . AWARDPRODUCT_TABLE . ' SET awardType=\'' . $awardType . '\',awardProduct=\'' . $_POST['awardProduct'][$ID] . '\',awardNum=\'' . $_POST['awardNum'][$ID] . '\' WHERE awardListID =\'' . $ID . '\' ';
			$DB->query($SQL);
		}
	}

	_showsucceed($lang['edit_award_product'], $thisProg . '&ActionDO=AwardProduct');
}

if ($_GET['ActionDO'] == 'AwardProduct') {
	$EnableQCoreClass->setTemplateFile('ResultAwardProductFile', 'ResultAwardProduct.html');
	$EnableQCoreClass->replace('productURL', $thisProg . '&ActionDO=AwardProduct');
	$EnableQCoreClass->replace('awardURL', $thisProg . '&ActionDO=AwardRand');
	$EnableQCoreClass->set_CycBlock('ResultAwardProductFile', 'AWARDS', 'awards');
	$EnableQCoreClass->replace('awards', '');
	$SQL = ' SELECT * FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY awardListID ASC ';
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	$EnableQCoreClass->replace('totalResponseNum', $recordCount);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('awardListID', $Row['awardListID']);
		$EnableQCoreClass->replace('awardType', $Row['awardType']);
		$EnableQCoreClass->replace('awardProduct', $Row['awardProduct']);
		$EnableQCoreClass->replace('awardNum', $Row['awardNum']);
		$EnableQCoreClass->parse('awards', 'AWARDS', true);
	}

	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
		$EnableQCoreClass->replace('awardURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['passport_is_permit'] . '\');');
		$EnableQCoreClass->replace('isViewUserAction', 'disabled');
	}
	else {
		$EnableQCoreClass->replace('isViewUserAction', '');
		$EnableQCoreClass->replace('awardURL', $thisProg . '&ActionDO=AwardRand');
	}

	$EnableQCoreClass->parse('ResultAwardProduct', 'ResultAwardProductFile');
	$EnableQCoreClass->output('ResultAwardProduct');
}

if ($_GET['Action'] == 'DeleteAward') {
	$SQL = ' DELETE FROM ' . AWARDLIST_TABLE . ' WHERE awardID=\'' . $_GET['awardID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_award_result']);
	_showsucceed($lang['delete_award_result'], $thisProg);
}

$EnableQCoreClass->setTemplateFile('ResultListFile', 'ResultAward.html');
$EnableQCoreClass->replace('productURL', $thisProg . '&ActionDO=AwardProduct');

switch ($_SESSION['adminRoleType']) {
case '3':
	if ($Sur_G_Row['isExportData'] == 1) {
		$EnableQCoreClass->replace('isExportData', 'none');
		$EnableQCoreClass->replace('excelURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	}
	else {
		$EnableQCoreClass->replace('isExportData', '');
		$EnableQCoreClass->replace('excelURL', '"../Export/Export.award.inc.php?surveyID=' . $_GET['surveyID'] . '"');
	}

	$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);

	if (in_array('t1', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t1_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t1_show', '');
	}

	if (in_array('t2', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t2_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t2_show', '');
	}

	if ($Sur_G_Row['mainShowQtn'] == 0) {
		$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
		$Row = $DB->queryFirstRow($SQL);

		if (!$Row) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$mainShowField = '';
		}
		else if (in_array($Row['questionID'], $forbidViewIdValue)) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$mainShowField = '';
		}
		else {
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Row['questionID'];
		}
	}
	else {
		$FHSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' LIKE \'option_' . $Sur_G_Row['mainShowQtn'] . '\' ';
		$FHRow = $DB->queryFirstRow($FHSQL);

		if ($FHRow['Field'] == '') {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				$EnableQCoreClass->replace('qtnNameList', '');
				$EnableQCoreClass->replace('isMainShowQtn', 'none');
				$mainShowField = '';
			}
			else if (in_array($Row['questionID'], $forbidViewIdValue)) {
				$EnableQCoreClass->replace('qtnNameList', '');
				$EnableQCoreClass->replace('isMainShowQtn', 'none');
				$mainShowField = '';
			}
			else {
				$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
				$EnableQCoreClass->replace('isMainShowQtn', '');
				$mainShowField = 'option_' . $Row['questionID'];
			}
		}
		else if (in_array($Sur_G_Row['mainShowQtn'], $forbidViewIdValue)) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$mainShowField = '';
		}
		else {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $Sur_G_Row['mainShowQtn'] . '\' ORDER BY questionID ASC ';
			$Row = $DB->queryFirstRow($SQL);
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Sur_G_Row['mainShowQtn'];
		}
	}

	break;

default:
	$EnableQCoreClass->replace('isExportData', '');
	$EnableQCoreClass->replace('excelURL', '"../Export/Export.award.inc.php?surveyID=' . $_GET['surveyID'] . '"');
	$EnableQCoreClass->replace('t1_show', '');
	$EnableQCoreClass->replace('t2_show', '');

	if ($Sur_G_Row['mainShowQtn'] == 0) {
		$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
		$Row = $DB->queryFirstRow($SQL);

		if (!$Row) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$mainShowField = '';
		}
		else {
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Row['questionID'];
		}
	}
	else {
		$FHSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' LIKE \'option_' . $Sur_G_Row['mainShowQtn'] . '\' ';
		$FHRow = $DB->queryFirstRow($FHSQL);

		if ($FHRow['Field'] == '') {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				$EnableQCoreClass->replace('qtnNameList', '');
				$EnableQCoreClass->replace('isMainShowQtn', 'none');
				$mainShowField = '';
			}
			else {
				$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
				$EnableQCoreClass->replace('isMainShowQtn', '');
				$mainShowField = 'option_' . $Row['questionID'];
			}
		}
		else {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $Sur_G_Row['mainShowQtn'] . '\' ORDER BY questionID ASC ';
			$Row = $DB->queryFirstRow($SQL);
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 25, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Sur_G_Row['mainShowQtn'];
		}
	}

	break;
}

if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('awardURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['passport_is_permit'] . '\');');
}
else {
	$EnableQCoreClass->replace('awardURL', $thisProg . '&ActionDO=AwardRand');
}

$EnableQCoreClass->set_CycBlock('ResultListFile', 'AWARD', 'award');
$EnableQCoreClass->set_CycBlock('AWARD', 'LIST', 'list');
$EnableQCoreClass->replace('award', '');
$ViewBackURL = $thisProg . '&DO=Award';
$_SESSION['ViewBackURL'] = $ViewBackURL;
$SQL = ' SELECT * FROM ' . AWARDPRODUCT_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY awardListID ASC ';
$Result = $DB->query($SQL);
$totalResponseNum = 0;

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('list', '');
	$EnableQCoreClass->replace('awardType', $Row['awardType']);
	$EnableQCoreClass->replace('awardProduct', $Row['awardProduct']);
	$EnableQCoreClass->replace('awardNum', $Row['awardNum']);
	$CountSQL = ' SELECT COUNT(*) AS awardOverNum FROM ' . AWARDLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND awardListID=\'' . $Row['awardListID'] . '\' ';
	$CountRow = $DB->queryFirstRow($CountSQL);
	$EnableQCoreClass->replace('awardOverNum', $CountRow['awardOverNum']);

	if ($mainShowField == '') {
		$ListSQL = ' SELECT a.responseID,a.awardID,b.administratorsName,b.ipAddress,b.joinTime,b.area,b.authStat,b.overFlag FROM ' . AWARDLIST_TABLE . ' a, ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.awardListID=\'' . $Row['awardListID'] . '\' AND a.surveyID=\'' . $_GET['surveyID'] . '\'  AND a.responseID = b.responseID ORDER BY awardID ASC ';
	}
	else {
		$ListSQL = ' SELECT a.responseID,a.awardID,b.administratorsName,b.ipAddress,b.joinTime,b.area,b.authStat,b.overFlag,b.' . $mainShowField . ' FROM ' . AWARDLIST_TABLE . ' a, ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE a.awardListID=\'' . $Row['awardListID'] . '\' AND a.surveyID=\'' . $_GET['surveyID'] . '\'  AND a.responseID = b.responseID ORDER BY awardID ASC ';
	}

	$ListResult = $DB->query($ListSQL);

	while ($ListRow = $DB->queryArray($ListResult)) {
		$totalResponseNum++;
		$EnableQCoreClass->replace('administratorsName', $ListRow['administratorsName']);

		switch ($ListRow['overFlag']) {
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

		switch ($ListRow['authStat']) {
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

		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $ListRow['authStat']]);

		if ($mainShowField == '') {
			$EnableQCoreClass->replace('qtnValue', '');
			$EnableQCoreClass->replace('isMainShowQtnValue', 'none');
		}
		else {
			$EnableQCoreClass->replace('qtnValue', qlisttag($ListRow[$mainShowField], 1));
			$EnableQCoreClass->replace('isMainShowQtnValue', '');
		}

		$EnableQCoreClass->replace('responseID', $ListRow['responseID']);
		$EnableQCoreClass->replace('ipAddress', $ListRow['ipAddress']);
		$EnableQCoreClass->replace('area', $ListRow['area']);
		$EnableQCoreClass->replace('joinTime', date('y-m-d H:i:s', $ListRow['joinTime']));
		$EnableQCoreClass->replace('viewURL', 'DataList.php?' . $thisURL . '&Does=View&responseID=' . $ListRow['responseID'] . '&isAwardView=1');
		if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
			$EnableQCoreClass->replace('deleteURL', '');
			$EnableQCoreClass->replace('isDelete', 'none');
		}
		else {
			$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=DeleteAward&awardID=' . $ListRow['awardID']);
			$EnableQCoreClass->replace('isDelete', '');
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	$EnableQCoreClass->parse('award', 'AWARD', true);
	$EnableQCoreClass->unreplace('list');
}

$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

if ($totalResponseNum == 0) {
	$EnableQCoreClass->replace('isHaveAwardText', '<tr><td colspan=7 style="padding-left:10px"><span class=red>当前调查问卷未有已分配的奖励清单，或者您先需要进行奖励奖品设定</span></td></tr>');
}
else {
	$EnableQCoreClass->replace('isHaveAwardText', '');
}

$EnableQCoreClass->parse('ResultList', 'ResultListFile');
$EnableQCoreClass->output('ResultList');

?>
