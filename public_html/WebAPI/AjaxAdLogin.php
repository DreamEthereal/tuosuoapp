<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'License/License.xml';
if (($_GET['Task'] == 'CheckLogin') && ($License['ADPassport'] == 1)) {
	$administrators_Name = strtolower(trim($_GET['Username']));
	$password = trim($_GET['Hash']);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT domainControllers,adUsername,accountSuffix,adPassword,baseDN FROM ' . BASESETTING_TABLE . ' ';
	$baseRow = $DB->queryFirstRow($SQL);
	$options['account_suffix'] = trim($baseRow['accountSuffix']);
	$domain_controllers = explode(',', trim($baseRow['domainControllers']));
	$options['domain_controllers'] = $domain_controllers;
	$options['ad_username'] = trim($baseRow['adUsername']);
	$options['ad_password'] = trim($baseRow['adPassword']);
	$options['base_dn'] = trim($baseRow['baseDN']);
	include_once ROOT_PATH . 'Includes/LDAP.class.php';
	$LDAP = new LDAPCLASS($options);
	$theUserNameUTF8 = iconv('gbk', 'UTF-8', $administrators_Name);

	if (!$LDAP->authenticate($theUserNameUTF8, $password)) {
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
			$haveKownGroup = false;

			while ($Row = $DB->queryArray($Result)) {
				$this_ad_user_name = trim($Row['adUserName']);

				if ($this_ad_user_name != '') {
					$groupFlag = substr($this_ad_user_name, 0, 6);

					if ($groupFlag == 'group/') {
						$this_user_name = $administrators_Name;
						$this_group_name = str_replace('group/', '', trim($Row['adUserName']));
						$this_group_all_users = $LDAP->all_group_users($this_group_name);
						$the_group_all_users = array();

						foreach ($this_group_all_users as $this_all_users) {
							$the_group_all_users[] = strtolower($this_all_users);
						}

						if (in_array($this_user_name, $the_group_all_users)) {
							$haveKownGroup = true;
							unset($this_group_all_users);
							unset($the_group_all_users);
							break;
						}
					}
				}
			}

			if ($haveKownGroup == false) {
				exit('false|' . $lang['error_login_auth']);
			}
			else {
				exit('true|' . $administrators_Name);
			}
		}
	}
}

?>
