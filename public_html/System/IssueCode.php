<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mgt.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyID,surveyTitle,surveyName,beginTime,endTime,projectType,projectOwner,lang,isPublic FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sure_Row = $DB->queryFirstRow($SQL);

switch ($Sure_Row['status']) {
case '0':
	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&beginTime=' . urlencode($Sure_Row['beginTime']) . '&endTime=' . urlencode($Sure_Row['endTime']) . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	$EnableQCoreClass->replace('isDeployStat', 'none');
	break;

case '1':
	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']) . '&beginTime=' . $Sure_Row['beginTime'] . '&endTime=' . $Sure_Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);

	if ($Sure_Row['projectType'] == 1) {
		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Sure_Row['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
		$EnableQCoreClass->replace('isDeployStat', 'none');
		$EnableQCoreClass->replace('isTrackCode', 'none');
	}
	else {
		$EnableQCoreClass->replace('haveTask', 'none');
		$EnableQCoreClass->replace('taskURL', '');
		$EnableQCoreClass->replace('isDeployStat', '');
		$EnableQCoreClass->replace('isTrackCode', '');
	}

	break;

case '2':
	$EnableQCoreClass->replace('havePlan', 'none');
	$EnableQCoreClass->replace('planURL', '');
	$EnableQCoreClass->replace('haveTask', 'none');
	$EnableQCoreClass->replace('taskURL', '');
	$EnableQCoreClass->replace('isDeployStat', 'none');
	$EnableQCoreClass->replace('isTrackCode', 'none');
	break;
}

$EnableQCoreClass->replace('isAdmin6', '');
$thisProg = 'IssueCode.php';
$EnableQCoreClass->replace('surveyID', $Sure_Row['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sure_Row['surveyTitle']));
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sure_Row['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('thisURL', $thisProg . '?' . $thisURLStr);

if ($_POST['Action'] == 'IssueControlSubmit') {
	$SQL = ' SELECT surveyID FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$haveCookieFile = false;

	if ($Row) {
		$SQL = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET isOpen = \'' . trim($_POST['isOpen']) . '\',issueMode = \'' . trim($_POST['issueMode']) . '\',renderingCode = \'' . trim($_POST['renderingCode']) . '\' ';

		switch ($_POST['issueMode']) {
		case '1':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate1']) . '\' ';
			break;

		case '2':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate2']) . '\',issueCookie = \'' . trim($_POST['issueCookie2']) . '\' ';

			if ($_FILES['blackCookie']['name'] != '') {
				$cookieFile = 'blackCookie';
				$haveCookieFile = true;
			}

			break;

		case '3':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate3']) . '\',issueCookie = \'' . trim($_POST['issueCookie3']) . '\' ';

			if ($_FILES['whiteCookie']['name'] != '') {
				$cookieFile = 'whiteCookie';
				$haveCookieFile = true;
			}

			break;
		}

		$SQL .= ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	}
	else {
		$SQL = ' INSERT INTO ' . COUNTGENERALINFO_TABLE . ' SET surveyID = \'' . $_POST['surveyID'] . '\',isOpen = \'' . trim($_POST['isOpen']) . '\',issueMode = \'' . trim($_POST['issueMode']) . '\',renderingCode = \'' . trim($_POST['renderingCode']) . '\' ';

		switch ($_POST['issueMode']) {
		case '1':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate1']) . '\' ';
			break;

		case '2':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate2']) . '\',issueCookie = \'' . trim($_POST['issueCookie2']) . '\' ';

			if ($_FILES['blackCookie']['name'] != '') {
				$cookieFile = 'blackCookie';
				$haveCookieFile = true;
			}

			break;

		case '3':
			$SQL .= ' ,issueRate = \'' . trim($_POST['issueRate3']) . '\',issueCookie = \'' . trim($_POST['issueCookie3']) . '\' ';

			if ($_FILES['whiteCookie']['name'] != '') {
				$cookieFile = 'whiteCookie';
				$haveCookieFile = true;
			}

			break;
		}
	}

	if ($haveCookieFile == true) {
		$tmpDirName = $Config['absolutenessPath'] . '/PerUserData/tmp/';

		if (!is_dir($tmpDirName)) {
			mkdir($tmpDirName, 511);
		}

		$tmpExt = explode('.', $_FILES[$cookieFile]['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 999) . '.csv';
		$newFullName = $tmpDirName . $newFileName;
		if (is_uploaded_file($_FILES[$cookieFile]['tmp_name']) && ($extension == 'csv')) {
			copy($_FILES[$cookieFile]['tmp_name'], $newFullName);
		}
		else {
			_showerror($lang['error_system'], $lang['csv_file_type_error']);
		}

		$theCookiePath = ROOT_PATH . 'PerUserData/cookie/';
		createdir($theCookiePath);
		$theCacheFile = $theCookiePath . md5($cookieFile . $_POST['surveyID']) . '.php';
		$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
		$cacheContent .= "\r\n";
		$cacheContent .= '$cookieKeyArray = array( ';
		setlocale(LC_ALL, 'zh_CN.GBK');
		$File = fopen($newFullName, 'r');
		$theExistKeyArray = array();

		while ($csvData = _fgetcsv($File)) {
			$thisRandNumber = trim($csvData[0]);

			if (!in_array($thisRandNumber, $theExistKeyArray)) {
				$cacheContent .= '\'' . str_replace('\'', '\\\'', $thisRandNumber) . '\',';
				$theExistKeyArray[] = $thisRandNumber;
			}
		}

		fclose($File);

		if (file_exists($newFullName)) {
			@unlink($newFullName);
		}

		$cacheContent = substr($cacheContent, 0, -1) . '' . "\r\n" . ');' . "\r\n" . '';
		$cacheContent .= chr(63) . chr(62);

		if (file_exists($theCacheFile)) {
			@unlink($theCacheFile);
		}

		write_to_file($theCacheFile, $cacheContent);
	}

	$DB->query($SQL);
	$i = 1;

	for (; $i <= sizeof($_POST['ruleValue']); $i++) {
		if (trim($_POST['ruleValue'][$i]) != '') {
			$SQL = ' SELECT ruleID FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND ruleOrderID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' UPDATE ' . ISSUERULE_TABLE . ' SET ruleOrderID=\'' . $i . '\',ruleValue=\'' . $_POST['ruleValue'][$i] . '\'';

				if ($_POST['exposureVar'][$i] == 'userdefine') {
					$SQL .= ',exposureVar=\'0\',cookieVarName=\'' . $_POST['cookieVarName'][$i] . '\' ';
				}
				else {
					$SQL .= ',exposureVar=\'' . $_POST['exposureVar'][$i] . '\',cookieVarName=\'\' ';
				}

				$SQL .= ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND ruleOrderID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
			else {
				$SQL = ' INSERT INTO ' . ISSUERULE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',ruleOrderID=\'' . $i . '\',ruleValue=\'' . $_POST['ruleValue'][$i] . '\'';

				if ($_POST['exposureVar'][$i] == 'userdefine') {
					$SQL .= ',exposureVar=\'0\',cookieVarName=\'' . $_POST['cookieVarName'][$i] . '\' ';
				}
				else {
					$SQL .= ',exposureVar=\'' . $_POST['exposureVar'][$i] . '\',cookieVarName=\'\' ';
				}

				$DB->query($SQL);
			}
		}
		else {
			$SQL = ' SELECT ruleID FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND ruleOrderID=\'' . $i . '\' ';
			$Row = $DB->queryFirstRow($SQL);

			if ($Row) {
				$SQL = ' DELETE FROM ' . ISSUERULE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND ruleOrderID=\'' . $i . '\' ';
				$DB->query($SQL);
			}
		}
	}

	$SQL = ' SELECT ruleID,ruleOrderID FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND ruleOrderID > \'' . sizeof($_POST['ruleValue']) . '\' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$SQL = ' DELETE FROM ' . ISSUERULE_TABLE . ' WHERE surveyID =\'' . $_POST['surveyID'] . '\' AND ruleOrderID =\'' . $Row['ruleOrderID'] . '\' ';
		$DB->query($SQL);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCheckIP = \'' . $_POST['isCheckIP'] . '\' ';

	switch ($_POST['isCheckIP']) {
	case '0':
		break;

	case '1':
		$SQL .= ' ,maxIpTime = \'' . trim($_POST['maxIpTime1']) . '\' ';
		break;

	case '2':
		$SQL .= ' ,maxIpTime = \'' . trim($_POST['maxIpTime2']) . '\' ';
		break;
	}

	$SQL .= ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	$surveyID = $_POST['surveyID'];
	require ROOT_PATH . 'Includes/IssueCache.php';
	writetolog('分发代码与分发控制定义:' . trim($_POST['surveyTitle']));
	_showsucceed('分发代码与分发控制定义:' . trim($_POST['surveyTitle']), $thisProg . '?surveyID=' . $_GET['surveyID']);
}

$EnableQCoreClass->setTemplateFile('SurveyListFile', 'IssueCode.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -20);

if ($Config['dataDomainName'] != '') {
	$fullPath = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$fullPath = $All_Path;
}

$EnableQCoreClass->replace('fullPath', $fullPath);
$SQL = ' SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('isOpen_' . $Row['isOpen'], 'checked');
$EnableQCoreClass->replace('issueMode_' . $Row['issueMode'], 'checked');
$EnableQCoreClass->replace('issueRate', $Row['issueRate']);
$EnableQCoreClass->replace('issueCookie', $Row['issueCookie']);
$renderingCode = '<div id="q_issue_div" style="text-align:center;position:absolute;top:50%;left:50%;width:500px;height:180px;background:#fff;z-index:100;border:2px #cacaca solid;margin:-100px 0 0 -250px;"><div style="height:40px;font-size:14px;line-height:40px;border-bottom:2px #cacaca solid;background:#f5f5f5"><b>诚挚邀请您参加我们的问卷调查</b></div><div style="height:80px;line-height:22px;padding:10px;text-align:left;font-size:12px">现诚挚邀请您参加“' . $Sure_Row['surveyTitle'] . '”的问卷调查，您的观点对于我们很重要！<br/>请点击下面的“我要参与”参加调查活动！</div><div style="clear:both"></div><div style="height:40px;font-size:14px;line-height:40px;"><a href="' . $fullPath . 'q.php?qid=' . $_GET['surveyID'] . '&qlang=' . $Sure_Row['lang'] . '"><b>我要参与</b></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:document.getElementById(\'q_issue_div\').style.display =\'none\';">没兴趣</a></div></div>';
$EnableQCoreClass->replace('renderingCode', $Row['renderingCode'] == '' ? $renderingCode : $Row['renderingCode']);
$theCookiePath = ROOT_PATH . 'PerUserData/cookie/';
$theCacheFile = $theCookiePath . md5('blackCookie' . $_GET['surveyID']) . '.php';

if (file_exists($theCacheFile)) {
	require_once $theCacheFile;
	$EnableQCoreClass->replace('haveBlackCookieNum', count($cookieKeyArray));
}
else {
	$EnableQCoreClass->replace('haveBlackCookieNum', 0);
}

$theCacheFile = $theCookiePath . md5('whiteCookie' . $_GET['surveyID']) . '.php';

if (file_exists($theCacheFile)) {
	require_once $theCacheFile;
	$EnableQCoreClass->replace('haveWhiteCookieNum', count($cookieKeyArray));
}
else {
	$EnableQCoreClass->replace('haveWhiteCookieNum', 0);
}

$SQL = ' SELECT isCheckIP,maxIpTime FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('isCheckIP_' . $Row['isCheckIP'], 'checked');
$EnableQCoreClass->replace('maxIpTime', $Row['maxIpTime']);
$SQL = ' SELECT max(ruleOrderID) as ruleMaxOrderID FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$MaxRow = $DB->queryFirstRow($SQL);

if ($MaxRow['ruleMaxOrderID'] == 0) {
	$exposure_var_list = '';
	$tSQL = ' SELECT tagID,tagName FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND tagCate =2 ORDER BY tagID DESC ';
	$tResult = $DB->query($tSQL);

	while ($tRow = $DB->queryArray($tResult)) {
		$exposure_var_list .= '<option value=\'' . $tRow['tagID'] . '\'>“' . qnohtmltag($tRow['tagName'], 1) . '”创意曝光量</option>';
	}

	$i = 1;

	for (; $i <= 2; $i++) {
		$EnableQCoreClass->replace('ruleOrderID', $i);
		$EnableQCoreClass->replace('exposureVar_0', '');
		$EnableQCoreClass->replace('ruleValue', '');
		$EnableQCoreClass->replace('cookieVarName', '');
		$EnableQCoreClass->replace('exposure_var_list', $exposure_var_list);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}
}
else {
	$i = 1;

	for (; $i <= $MaxRow['ruleMaxOrderID']; $i++) {
		$EnableQCoreClass->replace('ruleOrderID', $i);
		$SQL = ' SELECT * FROM ' . ISSUERULE_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND ruleOrderID = \'' . $i . '\' ';
		$hRow = $DB->queryFirstRow($SQL);
		$EnableQCoreClass->replace('ruleValue', $hRow['ruleValue']);
		$EnableQCoreClass->replace('cookieVarName', $hRow['cookieVarName']);
		$exposure_var_list = '';

		if ($hRow['exposureVar'] == 0) {
			if ($hRow['cookieVarName'] == '') {
				$EnableQCoreClass->replace('exposureVar_0', 'selected');
				$EnableQCoreClass->replace('exposureVar_userdefine', '');
			}
			else {
				$EnableQCoreClass->replace('exposureVar_userdefine', 'selected');
				$EnableQCoreClass->replace('exposureVar_0', '');
			}
		}
		else {
			$EnableQCoreClass->replace('exposureVar_0', '');
			$EnableQCoreClass->replace('exposureVar_userdefine', '');
		}

		$tSQL = ' SELECT tagID,tagName FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND tagCate =2 ORDER BY tagID DESC ';
		$tResult = $DB->query($tSQL);

		while ($tRow = $DB->queryArray($tResult)) {
			if ($hRow['exposureVar'] == $tRow['tagID']) {
				$exposure_var_list .= '<option value=\'' . $tRow['tagID'] . '\' selected>“' . qnohtmltag($tRow['tagName'], 1) . '”创意曝光量</option>';
			}
			else {
				$exposure_var_list .= '<option value=\'' . $tRow['tagID'] . '\'>“' . qnohtmltag($tRow['tagName'], 1) . '”创意曝光量</option>';
			}
		}

		$EnableQCoreClass->replace('exposure_var_list', $exposure_var_list);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}
}

$EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
$EnableQCoreClass->output('SurveyList');

?>
