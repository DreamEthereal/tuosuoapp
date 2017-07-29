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
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT surveyID,status,administratorsID,surveyName,isPublic,ajaxRtnValue,mainShowQtn,isOnline0View,isOnline0Auth,isViewAuthData,isViewAuthInfo,isExportData,isFailReApp,isCache,isRecord,forbidViewId,forbidAppId,projectType,projectOwner,isDataSource,isDeleteData,isModiData,isAllData,isRateIndex,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$thisProg = 'DataList.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&authStat=' . $_GET['authStat'];
$valueLogicQtnList = array();
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if ($_POST['formAction'] == 'YesAuthSubmit') {
	echo 123;
	_checkroletype('1|2|5');

	if (count($_POST['responseID']) != 0) {
		foreach ($_POST['responseID'] as $theResponseID) {
			$hSQL = ' SELECT authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID = \'' . $theResponseID . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow['authStat'] != '1') {
				$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET version =\'0\',authStat=\'1\',adminID=\'0\',appStat=\'0\' WHERE responseID = \'' . $theResponseID . '\' ';
				$DB->query($SQL);
				$SQL = ' INSERT INTO ' . DATA_TASK_TABLE . ' SET surveyID = \'' . $_GET['surveyID'] . '\',responseID=\'' . $theResponseID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',taskTime=\'' . time() . '\',authStat=\'1\',reason=\'' . $lang['yes_auth_list'] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	writetolog($lang['yes_auth_list']);
	_showsucceed($lang['yes_auth_list'], $thisProg);
}

if ($_POST['formAction'] == 'DeleteSubmit') {
	_checkroletype('1|2|5');
	require ROOT_PATH . 'Analytics/DeleSurveyDataBat.php';
}

if ($_POST['formAction'] == 'OverFlagSubmit') {
	_checkroletype('1|2|5');

	if (is_array($_POST['responseID'])) {
		foreach ($_POST['responseID'] as $theResponseID) {
			if ($_POST['overFlag'][$theResponseID] == 2) {
				$SQL = ' UPDATE ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' SET overFlag=\'' . $_POST['overFlag0'][$theResponseID] . '\' WHERE responseID =\'' . $theResponseID . '\' ';
				$DB->query($SQL);

				if ($_POST['overFlag0'][$theResponseID] == 1) {
					docountinfo($Sur_G_Row['surveyID'], $_POST['createDate'][$theResponseID]);
				}
			}
			else {
				$SQL = ' UPDATE ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' SET overFlag0=\'' . $_POST['overFlag'][$theResponseID] . '\',overFlag=2 WHERE responseID =\'' . $theResponseID . '\' ';
				$DB->query($SQL);
				if (($_POST['createDate'][$theResponseID] != '') && ($_POST['overFlag'][$theResponseID] == 1)) {
					delcountinfo($Sur_G_Row['surveyID'], $_POST['createDate'][$theResponseID]);
				}
			}
		}
	}

	writetolog($lang['open_close_falg']);
	_showsucceed($lang['open_close_falg'], $thisProg);
}

if ($_GET['Does'] == 'CancelAuthData') {
	_checkroletype('1|2|5|7');
	$SQL = ' SELECT authStat,appStat,version,overFlag FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch ($Row['authStat']) {
	case '0':
		_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰδ������˴������Ĳ����޷�������');
		break;

	case '1':
		if ($Row['appStat'] == 3) {
			_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ���������У����Ĳ����޷�������');
		}
		else if ($Row['version'] != 0) {
			_showerror($lang['auth_error'], $lang['con_action_error']);
		}

		break;

	case '2':
		break;

	case '3':
		if ($Row['version'] != 0) {
			_showerror($lang['auth_error'], $lang['con_action_error']);
		}

		break;
	}

	$cSQL = ' SELECT taskID FROM ' . DATA_TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $_GET['responseID'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
	$cRow = $DB->queryFirstRow($cSQL);
	$SQL = ' DELETE FROM ' . DATA_TASK_TABLE . ' WHERE taskID = \'' . $cRow['taskID'] . '\' ';
	$DB->query($SQL);
	$cSQL = ' SELECT authStat,nextUserId FROM ' . DATA_TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $_GET['responseID'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
	$cRow = $DB->queryFirstRow($cSQL); 


	if ($cRow) {
		switch ($cRow['authStat']) {
		case '0':
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=\'0\',adminID=\'0\',appStat=\'0\',version=\'0\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
			break;

		case '1':
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=\'1\',adminID=\'0\',appStat=\'3\',version=\'0\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
			break;

		case '2':
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=\'2\',adminID=\'0\',appStat=\'0\',version=\'0\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
			break;

		case '3':
			$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=\'3\',adminID=\'' . $cRow['nextUserId'] . '\',appStat=\'0\',version=\'0\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
			break;
		}
	}
	else {
		$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=\'0\',adminID=\'0\',appStat=\'0\',version=\'0\' WHERE responseID = \'' . $_GET['responseID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['cancel_auth_stat']);
	_showsucceed($lang['cancel_auth_stat'], $thisProg);
}

if ($_POST['Action'] == 'DataAuthQuickSubmit') {
	$SQL = ' SELECT authStat,appStat,version,overFlag,adminID FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE responseID=\'' . $_POST['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	if (($Row['version'] != 0) && ($Row['version'] != $_SESSION['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['con_action_error']);
	}

	if (($Row['authStat'] != 0) && ($Row['authStat'] != 3)) {
		_showerror('״̬����', '״̬���󣺼�⵽����������Ա�ѶԸ������ݽ��й���˴������Ĳ����޷�������');
	}

	if (!in_array($Row['overFlag'], array(1, 3))) {
		_showerror('״̬����', '״̬���󣺼�⵽����������Ա�ѶԸ������ݵ���ɱ�ǽ��й��޸ģ����Ĳ����޷�������');
	}

	if (($Row['authStat'] == 3) && ($Row['adminID'] != $_SESSION['administratorsID'])) {
		_showerror('״̬����', '״̬���󣺼�⵽�������ݵĵ�ǰ�������������������Ա�����Ĳ����޷�������');
	}

	$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET version=0,appStat=0 ';

	switch ($_POST['authStat']) {
	case 1:
		$SQL .= ' ,authStat =\'' . $_POST['authStat'] . '\' ';
		break;

	case 2:
		$SQL .= ' ,authStat =\'2\' ';
		break;

	case 3:
		$SQL .= ' ,authStat =\'' . $_POST['authStat'] . '\',adminID = \'' . $_POST['nextUserId'] . '\' ';
		break;

	case 4:
		$SQL .= ' ,authStat =\'2\',isReAuth=0 ';
		break;
	}

	$SQL .= ' WHERE responseID = \'' . $_POST['responseID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . DATA_TASK_TABLE . ' SET surveyID = \'' . $_POST['surveyID'] . '\',responseID=\'' . $_POST['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',taskTime=\'' . time() . '\',appStat=0 ';

	switch ($_POST['authStat']) {
	case 1:
		$SQL .= ' ,authStat =\'' . $_POST['authStat'] . '\',reason=\'' . $_POST['reason'] . '\' ';
		break;

	case 2:
		$SQL .= ' ,authStat =\'2\',reason=\'(����¼��Ա�޸����ݺ��ٽ����������)' . "\r\n" . '' . $_POST['reason'] . '\' ';
		break;

	case 3:
		$SQL .= ' ,authStat =\'' . $_POST['authStat'] . '\',reason=\'' . $_POST['reason'] . '\',nextUserId = \'' . $_POST['nextUserId'] . '\' ';
		break;

	case 4:
		$SQL .= ' ,authStat =\'2\',reason=\'(�Ͼ���)' . "\r\n" . '' . $_POST['reason'] . '\' ';
		break;
	}

	$DB->query($SQL);
	writetolog('�Իظ�������������������');
	_showsucceed('�Իظ�������������������', $_SESSION['ViewBackURL'] != '' ? $_SESSION['ViewBackURL'] : $thisProg);
}

if ($_GET['Does'] == 'View') {
	require ROOT_PATH . 'Analytics/ViewSurveyData.php';
}

if ($_GET['Does'] == 'AppDataSubmit') {
	_checkroletype('3');
	$SQL = ' SELECT authStat,appStat,version FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	if (($Row['version'] != 0) && ($Row['version'] != $_SESSION['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['con_action_error']);
	}

	if ($Row['authStat'] != 1) {
		_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ��Ȼδ�����ͨ�����в��ܽ�������');
	}

	if (($Row['appStat'] != 0) && ($Row['appStat'] != 2)) {
		_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ�����Ѿ������߻����ߴ�����������ٽ�������');
	}

	if ($Row['version'] != 0) {
		$hSQL = ' SELECT traceTime FROM ' . DATA_TRACE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND responseID=\'' . $_GET['responseID'] . '\' AND isAppData =1 ORDER BY traceTime DESC LIMIT 1 ';
		$hRow = $DB->queryFirstRow($hSQL);

		if (!$hRow) {
			$hRow = false;
		}
		else {
			$nSQL = ' SELECT taskTime FROM ' . DATA_TASK_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND responseID=\'' . $_GET['responseID'] . '\' AND authStat =1 AND appStat= 2 AND taskTime >= \'' . $hRow['traceTime'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
			$nRow = $DB->queryFirstRow($nSQL);

			if ($nRow) {
				$hRow = false;
			}
			else {
				$hRow = true;
			}
		}
	}
	else {
		$hRow = false;
	}

	if (!$hRow) {
		_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ�����������ϵ���ϸ���߼�¼��������Ҫѡ���Ӧ����������Ͳ��������ύ���ߣ�');
	}

	$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' SET authStat=1,appStat=3,version=0,adminID=0 WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . DATA_TASK_TABLE . ' SET surveyID=\'' . $_GET['surveyID'] . '\',responseID=\'' . $_GET['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',taskTime=\'' . time() . '\',authStat=1,appStat=3 ';
	$DB->query($SQL);
	writetolog('�ύ�ظ���������');
	_showsucceed('�ύ�ظ���������', $_SESSION['ViewBackURL'] != '' ? $_SESSION['ViewBackURL'] : $thisProg);
}

if ($_GET['Does'] == 'AppData') {
	require ROOT_PATH . 'Analytics/AppSurveyData.php';
}

if ($_GET['Does'] == 'AppQtnData') {
	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php';
	require ROOT_PATH . 'System/AppQtnData.php';
}

if ($_GET['Does'] == 'Delete') {
	require ROOT_PATH . 'Analytics/DeleSurveyData.php';
}

if ($_GET['Does'] == 'DeleFile') {
	require ROOT_PATH . 'Analytics/DeleUploadFile.php';
}

if ($_GET['Does'] == 'ModiData') {
	$SQL = ' SELECT authStat,appStat,overFlag FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch ($Row['authStat']) {
	case '0':
	case '2':
		if ($Row['overFlag'] == 2) {
			_showerror('״̬����', '״̬������Ч�ظ��������޷������޸Ĳ��������Ĳ����޷�������');
		}

		break;

	case '1':
		if ($Row['appStat'] == 3) {
			_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ���������У����Ĳ����޷�������');
		}
		else if ($Row['overFlag'] == 2) {
			_showerror('״̬����', '״̬������Ч�ظ��������޷������޸Ĳ��������Ĳ����޷�������');
		}

		break;

	case '3':
		_showerror('״̬����', '״̬���󣺸ûظ�����Ŀǰ��������У����Ĳ����޷�������');
		break;
	}

	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php';
	require ROOT_PATH . 'System/ModiSurveyData.php';
}

if ($_GET['Does'] == 'AuthData') {
	_checkpassport('1|2|5|7', $Sur_G_Row['surveyID']);
	$SQL = ' SELECT authStat,appStat,version,overFlag,adminID FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	if (($Row['version'] != 0) && ($Row['version'] != $_SESSION['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['con_action_error']);
	}

	if (($Row['authStat'] != 0) && ($Row['authStat'] != 3)) {
		_showerror('״̬����', '״̬���󣺼�⵽����������Ա�ѶԸ������ݽ��й���˴������Ĳ����޷�������');
	}

	if (!in_array($Row['overFlag'], array(1, 3))) {
		_showerror('״̬����', '״̬���󣺼�⵽����������Ա�ѶԸ������ݵ���ɱ�ǽ��й��޸ģ����Ĳ����޷�������');
	}

	if (($Row['authStat'] == 3) && ($Row['adminID'] != $_SESSION['administratorsID'])) {
		_showerror('״̬����', '״̬���󣺼�⵽�������ݵĵ�ǰ�������������������Ա�����Ĳ����޷�������');
	}

	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php';
	require ROOT_PATH . 'System/AuthSurveyData.php';
}

if ($_GET['Does'] == 'AuthAppData') {
	_checkpassport('1|2|5|7', $Sur_G_Row['surveyID']);
	$SQL = ' SELECT authStat,appStat,version,overFlag FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	if (($Row['version'] != 0) && ($Row['version'] != $_SESSION['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['con_action_error']);
	}

	if (($Row['authStat'] == 1) && ($Row['appStat'] == 3)) {
	}
	else {
		_showerror('״̬����', '״̬���󣺼�⵽����������Ա�ѶԸ������ݽ��й����ߺ�׼�������Ĳ����޷�������');
	}

	if (!file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/QuotaCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Quota' . $Sur_G_Row['surveyID']) . '.php';
	require ROOT_PATH . 'System/AuthSurveyAppData.php';
}

require ROOT_PATH . 'Analytics/ListSurveyData.php';

?>
