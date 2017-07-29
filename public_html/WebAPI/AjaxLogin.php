<?php
//dezend by http://www.yunlu99.com/
function checkstringtype($str)
{
	if (preg_match('/^[-ÿ]+$/', $str)) {
		return 2;
	}
	else if (preg_match('/[-ÿ]/', $str)) {
		return 3;
	}
	else {
		return 1;
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';

if ($_GET['Task'] == 'CheckLogin') {
	$administrators_Name = trim($_GET['Username']);
	$passWord = trim($_GET['Hash']);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT administratorsName,administratorsID,passWord,isActive,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) = \'' . strtolower($administrators_Name) . '\' AND isAdmin =0 LIMIT 0,1 ';
	$Login_Row = $DB->queryFirstRow($SQL);

	if (!$Login_Row) {
		if (checkstringtype($administrators_Name) != 1) {
			$NSQL = ' SELECT administratorsName,administratorsID,passWord,isActive,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE nickName = \'' . $administrators_Name . '\' AND isAdmin =0  ';
		}
		else {
			$NSQL = ' SELECT administratorsName,administratorsID,passWord,isActive,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(nickName) = \'' . strtolower($administrators_Name) . '\' AND isAdmin =0  ';
		}

		$NResult = $DB->query($NSQL);
		$nNum = $DB->_getNumRows($NResult);

		if ($nNum == 0) {
			exit('false|' . $lang['error_login_username']);
		}

		if (1 < $nNum) {
			exit('false|' . $lang['error_multi_nickname']);
		}

		$Login_Row = $DB->queryArray($NResult);
	}

	if ($Login_Row['isActive'] != '1') {
		exit('false|' . $lang['error_login_active']);
	}

	$surveyID = $_GET['surveyID'];
	require ROOT_PATH . 'System/PanelReg.inc.php';

	if (trim($administratorsIDList) != '') {
		if (!in_array($Login_Row['administratorsID'], explode(',', trim($administratorsIDList)))) {
			exit('false|' . $lang['error_login_auth']);
		}
	}
	else {
		exit('false|' . $lang['error_login_auth']);
	}

	$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE administratorsName = \'' . $Login_Row['administratorsName'] . '\' AND overFlag !=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if ($HaveRow) {
		exit('false|' . $lang['members_permit_survey']);
	}

	if ($passWord != $Login_Row['passWord']) {
		exit('false|' . $lang['error_login_password']);
	}
	else {
		$SQL = ' UPDATE ' . ADMINISTRATORS_TABLE . ' SET loginNum=loginNum+1,lastVisitTime=\'' . time() . '\',ipAddress = \'' . _getip() . '\' WHERE administratorsID=\'' . $Login_Row['administratorsID'] . '\' ';
		$DB->query($SQL);

		if ($Login_Row['administratorsGroupID'] == '0') {
			$administratorsGroupName = $lang['no_group'];
		}
		else {
			$SQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $Login_Row['administratorsGroupID'] . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$administratorsGroupName = $Row['administratorsGroupName'];
		}

		$SQL = ' SELECT mainAttribute FROM ' . SURVEY_TABLE . ' WHERE surveyID =\'' . $_GET['surveyID'] . '\' ';
		$AttRow = $DB->queryFirstRow($SQL);
		$mainAttribute = explode(',', $AttRow['mainAttribute']);
		$theMainAttributeList = '';

		foreach ($mainAttribute as $theMainAttribute) {
			$SQL = ' SELECT optionFieldName,administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE administratorsoptionID =\'' . $theMainAttribute . '\' ';
			$OptionRow = $DB->queryFirstRow($SQL);

			if ($OptionRow['optionFieldName'] != '') {
				$SQL = ' SELECT value FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsoptionID =\'' . $OptionRow['administratorsoptionID'] . '\' AND administratorsID = \'' . $Login_Row['administratorsID'] . '\' ';
				$ValueRow = $DB->queryFirstRow($SQL);
				$theMainAttributeList .= $OptionRow['optionFieldName'] . '=' . $ValueRow['value'] . '#';
			}
		}

		$theMainAttributeList = substr($theMainAttributeList, 0, -1);
		echo 'true|' . $Login_Row['administratorsGroupID'] . '***' . $administratorsGroupName . '***' . $theMainAttributeList . '***' . $Login_Row['administratorsID'];
	}
}

?>
