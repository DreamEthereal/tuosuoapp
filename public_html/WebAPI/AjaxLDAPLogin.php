<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'License/License.xml';
if (($_GET['Task'] == 'CheckLogin') && ($License['ADPassport'] == 1)) {
	$administrators_Name = strtolower(trim($_GET['Username']));
	$password = trim($_GET['Hash']);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT domainControllers,adUsername,adPassword,baseDN FROM ' . BASESETTING_TABLE . ' ';
	$baseRow = $DB->queryFirstRow($SQL);
	$baseDN = trim($baseRow['baseDN']);
	$domain_controllers = explode(',', trim($baseRow['domainControllers']));
	$options['domain_controllers'] = $domain_controllers;
	$options['ad_username'] = trim($baseRow['adUsername']);
	$options['ad_password'] = trim($baseRow['adPassword']);
	$options['base_dn'] = trim($baseRow['baseDN']);
	include_once ROOT_PATH . 'Includes/LDAPAuth.class.php';
	$ldap = new AuthLdap($options);

	if (!strstr($administrators_Name, strtolower($baseDN))) {
		$administrators_Name .= ',' . strtolower($baseDN);
	}

	$theUserNameUTF8 = $theUserDn = iconv('gbk', 'UTF-8', $administrators_Name);

	if (!$ldap->authBind(trim($theUserDn), $password)) {
		exit('false|' . $lang['ad_passport']);
	}
	else {
		$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE LCASE(administratorsName) = \'' . $administrators_Name . '\' AND overFlag !=0 LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($HaveSQL);

		if ($HaveRow) {
			exit('false|' . $lang['members_permit_survey']);
		}

		$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND LCASE(adUserName) = \'' . $administrators_Name . '\' LIMIT 0,1 ';
		$haveRow = $DB->queryFirstRow($RepSQL);

		if ($haveRow) {
			exit('true|' . $administrators_Name);
		}
		else {
			$RepSQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND adUserName like \'group/%\' ';
			$Result = $DB->query($RepSQL);
			$haveKownUnit = false;

			while ($Row = $DB->queryArray($Result)) {
				$theUnitFlage = explode('/', trim($Row['adUserName']));

				if ($ldap->checkInOU($theUserNameUTF8, $theUnitFlage[1])) {
					$haveKownUnit = true;
					break;
				}
			}

			if ($haveKownUnit == false) {
				exit('false|' . $lang['error_login_auth']);
			}
			else {
				exit('true|' . $administrators_Name);
			}
		}
	}
}

?>
