<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != '1') {
	_showerror($lang['system_error'], $lang['no_exe_survey']);
}

if ($S_Row['isPublic'] != '0') {
	_showerror($lang['system_error'], $lang['no_private_survey']);
}

$nowTime = date('Y-m-d', time());

if ($nowTime < $S_Row['beginTime']) {
	_showerror($lang['system_error'], $lang['no_start_survey']);
}

if ($S_Row['endTime'] < $nowTime) {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
	$DB->query($SQL);
	_showerror($lang['system_error'], $lang['end_survey_now']);
}

if ($S_Row['maxResponseNum'] != 0) {
	$SQL = ' SELECT COUNT(*) AS maxResponseNum FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag IN (1,3) ';
	$CountRow = $DB->queryFirstRow($SQL);

	if ($S_Row['maxResponseNum'] <= $CountRow['maxResponseNum']) {
		$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET status=2,endTime=\'' . date('Y-m-d') . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\'';
		$DB->query($SQL);
		_showerror($lang['system_error'], $lang['max_num_survey']);
	}
}

$thisProg = 'PrivateNoCommit.php?surveyID=' . $S_Row['surveyID'] . '&surveyTitle=' . urlencode($S_Row['surveyTitle']);
$EnableQCoreClass->replace('thisURL', $thisProg);

if ($_POST['Action'] == 'EmailSendSubmit') {
	@set_time_limit(0);
	$SendMembersEmail = explode(';', $_POST['panelUserName']);
	ob_end_clean();
	$style = '<style>' . "\n" . '';
	$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
	$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
	$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
	$style .= '</style>' . "\n" . '';
	echo $style;
	flush();
	$scroll = '<SCRIPT type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</SCRIPT>';
	$prefix = '';
	$i = 0;

	for (; $i < 300; $i++) {
		$prefix .= ' ' . "\n" . '';
	}

	$theSuccMail = $theFailMail = array();
	if ((0 < count($SendMembersEmail)) && !empty($SendMembersEmail)) {
		$theSendMailContent = stripslashes($_POST['emailContent']);
		$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);
		$theSendMailContent = str_replace($ProgURL, '', $theSendMailContent);

		if ($Config['dataDomainName'] != '') {
			$dataURL = 'http://' . $Config['dataDomainName'] . '/';
			$theSendMailContent = str_replace($dataURL, '', $theSendMailContent);
		}
		else {
			$dataURL = $ProgURL;
		}

		$theSendMailContent = str_replace('Images/', $dataURL . 'Images/', $theSendMailContent);
		$theSendMailContent = str_replace('q.php?qname=', $dataURL . 'q.php?qname=', $theSendMailContent);
		$theSendMailContent = str_replace('PerUserData/', $dataURL . 'PerUserData/', $theSendMailContent);
		$SQL = ' SELECT surveyTitle,surveySubTitle,surveyInfo,surveyName,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$SendMailTitle = $lang['send_survey_title_' . $Row['lang']] . ':' . $Row['surveyTitle'];
		_sendmailinit();
		$theSuccNum = $theFailNum = 0;

		foreach ($SendMembersEmail as $sendMailNameList) {
			$sendMailNameArray = explode('***', $sendMailNameList);
			$sendMailName = $sendMailNameArray[0];
			ob_end_clean();

			if (trim($sendMailName) != '') {
				if (checkemail($sendMailName)) {
					switch ($_POST['passPort']) {
					case '1':
						$theSQL = ' SELECT nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . trim($sendMailName) . '\' LIMIT 1 ';
						$theRow = $DB->queryFirstRow($theSQL);

						if ($theRow) {
							$SendMailContent = str_replace('{userName}', $theRow['nickName'], $theSendMailContent);
						}
						else {
							$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
						}

						break;

					default:
						$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
						break;
					}

					switch ($_POST['passPort']) {
					case 3:
						$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName)))) . '&userAdName=' . escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailNameArray[1]))));
						$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);
						break;

					case 5:
						$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName)))) . '&userAdName=' . escape(str_replace('+', '%2B', base64_encode($sendMailNameArray[1])));
						$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);
						break;

					default:
						$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName))));
						$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);
						break;
					}

					if (_sendmailcontent(strtolower($sendMailName), $SendMailTitle, $SendMailContent, '', '', 1)) {
						$str = '<div class="tipsinfo">' . $lang['send_email_succ'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
						$theSuccNum++;
						$theSuccMail[] = strtolower($sendMailName);
						echo $prefix . $str . $scroll;
					}
					else {
						$str = '<div class="tipsinfo">' . $lang['send_email_fail'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
						$theFailNum++;
						$theFailMail[] = strtolower($sendMailName);
						echo $prefix . $str . $scroll;
					}
				}
				else {
					$str = '<div class="tipsinfo">' . $lang['send_email_fail'] . '<b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
					$theFailNum++;
					$theFailMail[] = strtolower($sendMailName);
					echo $prefix . $str . $scroll;
				}
			}

			flush();
		}

		ob_end_clean();
		echo '<div class="tipsinfo">' . $lang['send_email_succ'] . '<b>' . $theSuccNum . '</b>&nbsp;&nbsp;' . $lang['send_email_fail'] . '<b>' . $theFailNum . '</b></div>' . "\n" . '';
		flush();
		unset($sendmail);
		$SQL = ' INSERT INTO ' . MAILLIST_TABLE . ' SET mailTitle=\'' . $SendMailTitle . '\',sendMailName=\'' . implode(';', $theSuccMail) . '\',sendFailName=\'' . implode(';', $theFailMail) . '\',sendMailContent=\'' . $_POST['emailContent'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',mailType=1,createDate=\'' . time() . '\' ';
		$DB->query($SQL);
	}
	else {
		ob_end_clean();
		$str = '<div class="tipsinfo">' . $lang['no_send_email'] . '</div>' . "\n" . '';
		echo $str;
		flush();
	}

	writetolog($lang['send_email_survey'] . ':' . $_GET['surveyTitle']);
	unset($SendMembersEmail);
	echo '<script>parent._showCloseWindowButton();</script>';
	exit();
}

if ($_GET['Action'] == 'SendEmail') {
	$EnableQCoreClass->setTemplateFile('SendSurveyFile', 'SurveyNoCommMail.html');

	if ($_GET['isSendAll'] == 1) {
		$EnableQCoreClass->replace('isSendAll', 1);
	}
	else {
		$EnableQCoreClass->replace('isSendAll', 0);
	}

	$SQL = ' SELECT surveyTitle,surveySubTitle,surveyInfo,surveyName,tokenCode,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	switch (strtolower(trim($Row['lang']))) {
	case 'cn':
	default:
		$qlang = 'CN';
		break;

	case 'en':
		$qlang = 'EN';
		break;
	}

	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('panelUserName', trim($_GET['administratorsName']));
	$EnableQCoreClass->replace('passportType', $_GET['passportType']);
	$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/' . $qlang);
	$EnableQCoreClass->setTemplateFile('SendSurveyMailFile', 'SurveyNotesMail.html');
	$EnableQCoreClass->replace('mail_date', date('Y-m-d', time()));

	if (strtolower(trim($Row['lang'])) == 'en') {
		$EnableQCoreClass->replace('siteName', 'EnableQ Survey System');
	}
	else {
		$EnableQCoreClass->replace('siteName', $Config['siteName']);
	}

	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveySubTitle', $Row['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $Row['surveyInfo']);
	$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);
	$surveyURL = $ProgURL . 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'];
	$EnableQCoreClass->replace('surveyURL', $surveyURL);
	$SurveyEmailPage = $EnableQCoreClass->parse('Mail', 'SendSurveyMailFile');
	$SurveyEmailPage = str_replace($ProgURL, '', $SurveyEmailPage);

	if ($Config['dataDomainName'] != '') {
		$dataURL = 'http://' . $Config['dataDomainName'] . '/';
		$SurveyEmailPage = str_replace($dataURL, '', $SurveyEmailPage);
	}
	else {
		$dataURL = $ProgURL;
	}

	$SurveyEmailPage = str_replace('Images/', $dataURL . 'Images/', $SurveyEmailPage);
	$SurveyEmailPage = str_replace('q.php?qname=', $dataURL . 'q.php?qname=', $SurveyEmailPage);
	$SurveyEmailPage = str_replace('PerUserData/', $dataURL . 'PerUserData/', $SurveyEmailPage);
	$EnableQCoreClass->replace('emailContent', $SurveyEmailPage);
	$EnableQCoreClass->parse('SendSurvey', 'SendSurveyFile');
	$EnableQCoreClass->output('SendSurvey');
}

if (!isset($_GET['Action'])) {
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('thisURL', $thisProg);
	$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
	$SQL = ' SELECT isUseOriPassport,domainControllers,adUsername,accountSuffix,adPassword,baseDN FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (!$BaseRow || ($BaseRow['isUseOriPassport'] == '1')) {
		$EnableQCoreClass->setTemplateFile('UserListFile', 'SurveyNoCommitList.html');
		$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
		$EnableQCoreClass->replace('user', '');
		$EnableQCoreClass->replace('members_group_list', _getmembergroupslist('all'));
		$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
		$ConfigRow['topicNum'] = 50;
		$surveyID = $_GET['surveyID'];
		require ROOT_PATH . 'System/PanelReg.inc.php';

		if (trim($administratorsIDList) != '') {
			$MemberSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $administratorsIDList . ')  AND isAdmin=0 ORDER BY administratorsName ASC ';
		}
		else {
			$MemberSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =0  AND isAdmin=0 ORDER BY administratorsName ASC ';
		}

		$MembersResult = $DB->query($MemberSQL);
		$ResIDArray = array();

		while ($MemberRow = $DB->queryArray($MembersResult)) {
			$ResIDArray[] = $MemberRow['administratorsName'];
		}

		$SQL = ' SELECT DISTINCT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE overFlag !=0 ORDER BY administratorsName ASC ';
		$MemResult = $DB->query($SQL);
		$ComIDArray = array();

		while ($MemRow = $DB->queryArray($MemResult)) {
			$ComIDArray[] = $MemRow['administratorsName'];
		}

		$NoCommitNameArray = arraydiff($ResIDArray, $ComIDArray);

		if (!empty($NoCommitNameArray)) {
			$NoCommitIDArray = array();

			foreach ($NoCommitNameArray as $User_Name) {
				$SQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName=\'' . $User_Name . '\' ';
				$Row = $DB->queryFirstRow($SQL);
				$NoCommitIDArray[] = $Row['administratorsID'];
			}

			$NoCommitIDList = implode(',', $NoCommitIDArray);
			$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $NoCommitIDList . ') AND isAdmin=0 ';
			$EnableQCoreClass->replace('noUserList', '');
		}
		else {
			$SQL = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName =\'\' AND isAdmin=0 ';
			$EnableQCoreClass->replace('noUserList', 'disabled');
		}

		if ($_POST['Action'] == 'querySubmit') {
			switch ($_POST['searchType']) {
			case '1':
			default:
				$name = trim($_POST['name']);
				$SQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
				$EnableQCoreClass->replace('isNickName', '');
				$page_others = '&searchType=1&name=' . urlencode($name);
				$EnableQCoreClass->replace('name', $name);
				break;

			case '2':
				$name = trim($_POST['name']);
				$SQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
				$EnableQCoreClass->replace('isNickName', 'selected');
				$page_others = '&searchType=2&name=' . urlencode($name);
				$EnableQCoreClass->replace('name', $name);
				break;
			}

			if ($_POST['administratorsGroupID'] != 'all') {
				$SQL .= ' AND administratorsGroupID = \'' . $_POST['administratorsGroupID'] . '\' ';
				$page_others .= '&administratorsGroupID=' . $_POST['administratorsGroupID'];
				$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($_POST['administratorsGroupID']));
			}
		}
		else {
			$EnableQCoreClass->replace('name', '');
		}

		if (isset($_GET['name']) && !$_POST['Action'] && ($_GET['searchType'] != '')) {
			switch ($_GET['searchType']) {
			case '1':
			default:
				$name = trim($_GET['name']);
				$SQL .= ' AND administratorsName LIKE BINARY \'%' . $name . '%\' ';
				$EnableQCoreClass->replace('isNickName', '');
				$page_others = '&searchType=1&name=' . urlencode($name);
				$EnableQCoreClass->replace('name', $name);
				break;

			case '2':
				$name = trim($_GET['name']);
				$SQL .= ' AND nickName LIKE BINARY \'%' . $name . '%\' ';
				$EnableQCoreClass->replace('isNickName', 'selected');
				$page_others = '&searchType=2&name=' . urlencode($name);
				$EnableQCoreClass->replace('name', $name);
				break;
			}
		}

		if (isset($_GET['administratorsGroupID']) && ($_GET['administratorsGroupID'] != 'all') && !$_POST['Action']) {
			$SQL .= ' AND administratorsGroupID = \'' . $_GET['administratorsGroupID'] . '\' ';
			$page_others .= '&administratorsGroupID=' . $_GET['administratorsGroupID'];
			$EnableQCoreClass->replace('members_group_list', _getmembergroupslist($_GET['administratorsGroupID']));
		}

		$Result = $DB->query($SQL);
		$recordCount = $DB->_getNumRows($Result);
		$EnableQCoreClass->replace('recNum', $recordCount);
		if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
			$start = 0;
		}
		else {
			$_GET['pageID'] = (int) $_GET['pageID'];
			$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
			$start = ($start < 0 ? 0 : $start);
		}

		$ExportSQL = $SQL . ' ORDER BY administratorsGroupID ASC ';
		$SQL .= ' ORDER BY administratorsGroupID ASC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$EnableQCoreClass->replace('nickName', $Row['nickName']);
			$SQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $Row['administratorsGroupID'] . '\' ';
			$GroupRow = $DB->queryFirstRow($SQL);

			if ($GroupRow['administratorsGroupName'] != '') {
				$EnableQCoreClass->replace('administratorsGroupName', $GroupRow['administratorsGroupName']);
			}
			else {
				$EnableQCoreClass->replace('administratorsGroupName', $lang['no_group']);
			}

			$EnableQCoreClass->replace('userName', $Row['administratorsName']);

			if ($Row['isActive'] == 1) {
				$EnableQCoreClass->replace('stat', $lang['active']);
			}
			else {
				$EnableQCoreClass->replace('stat', $lang['stop']);
			}

			$EnableQCoreClass->replace('lastVisitTime', date('Y-m-d H:m', $Row['lastVisitTime']));
			$EnableQCoreClass->replace('emailURL', $thisProg . '&Action=SendEmail&passportType=1&administratorsName=' . urlencode($Row['administratorsName']));
			$EnableQCoreClass->parse('user', 'USER', true);
		}

		$EnableQCoreClass->replace('exportSQL', base64_encode($ExportSQL));
		$_SESSION['mSQL'] = base64_encode($ExportSQL);
		include_once ROOT_PATH . 'Includes/Pages.class.php';
		$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
		$pagebar = $PAGES->whole_num_bar('', '', $page_others);
		$EnableQCoreClass->replace('pagesList', $pagebar);
		$AllName = array();
		$AllResult = $DB->query($ExportSQL);

		while ($AllRow = $DB->queryArray($AllResult)) {
			$AllName[] = trim($AllRow['administratorsName']);
		}

		$exportName = implode(';', $AllName);
		unset($AllName);
		$EnableQCoreClass->replace('exportName', $exportName);
		$EnableQCoreClass->replace('emailAllURL', $thisProg . '&Action=SendEmail&isSendAll=1');
		$EnableQCoreClass->parse('UserList', 'UserListFile');
		$EnableQCoreClass->output('UserList');
	}

	if ($BaseRow && ($BaseRow['isUseOriPassport'] == '3')) {
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
		$ResIDArray = array();
		$RepSQL = ' SELECT DISTINCT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
		$RepResult = $DB->query($RepSQL);

		while ($RepRow = $DB->queryArray($RepResult)) {
			$this_ad_user_name = trim($RepRow['adUserName']);

			if ($this_ad_user_name != '') {
				$groupFlag = substr($this_ad_user_name, 0, 6);

				switch ($groupFlag) {
				case 'group/':
					$this_group_name = str_replace('group/', '', trim($this_ad_user_name));
					$this_group_all_users = $LDAP->all_group_users($this_group_name);

					foreach ($this_group_all_users as $this_users) {
						$this_users = strtolower($this_users);
						if (($this_users != '') && !in_array($this_users, $ResIDArray)) {
							$ResIDArray[] = trim($this_users);
						}
					}

					break;

				default:
					$this_ad_user_name = strtolower($this_ad_user_name);
					if (($this_ad_user_name != '') && !in_array($this_ad_user_name, $ResIDArray)) {
						$ResIDArray[] = trim($this_ad_user_name);
					}

					break;
				}
			}
		}

		$SQL = ' SELECT DISTINCT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE overFlag !=0 ORDER BY administratorsName ASC ';
		$MemResult = $DB->query($SQL);
		$ComIDArray = array();

		while ($MemRow = $DB->queryArray($MemResult)) {
			$ComIDArray[] = strtolower($MemRow['administratorsName']);
		}

		$NoCommitNameArray = arraydiff($ResIDArray, $ComIDArray);
		$EnableQCoreClass->replace('recNum', count($NoCommitNameArray));
		$EnableQCoreClass->setTemplateFile('UserListFile', 'SurveyNoComAdList.html');
		$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
		$EnableQCoreClass->replace('user', '');
		$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

		if (count($NoCommitNameArray) == 0) {
			$EnableQCoreClass->replace('noUserList', 'disabled');
		}
		else {
			$EnableQCoreClass->replace('noUserList', '');
		}

		foreach ($NoCommitNameArray as $user_name) {
			$this_user_name = iconv('gbk', 'UTF-8', trim($user_name));
			$justthese = array('sn', 'givenname', 'samaccountname', 'mail', 'title', 'department', 'useraccountcontrol');
			$info = $LDAP->user_info($this_user_name, $justthese);
			$FirstName = $info[0]['givenname'][0];
			$LastName = $info[0]['sn'][0];
			$Account = $info[0]['samaccountname'][0];
			$Mail = $info[0]['mail'][0];
			$Title = $info[0]['title'][0];
			$Department = $info[0]['department'][0];
			$EnableQCoreClass->replace('xingming', iconv('UTF-8', 'gbk', $LastName . $FirstName));
			$EnableQCoreClass->replace('dengluming', iconv('UTF-8', 'gbk', $Account));
			$EnableQCoreClass->replace('email', $Mail);
			$EnableQCoreClass->replace('title', $Title);
			$EnableQCoreClass->replace('department', $Department);
			$EnableQCoreClass->replace('userEmail', $Mail);
			$EnableQCoreClass->replace('userName', trim($user_name));

			if ($Mail == '') {
				$EnableQCoreClass->replace('isNoEmail', 'none');
				$EnableQCoreClass->replace('emailURL', '');
			}
			else {
				$EnableQCoreClass->replace('isNoEmail', '');
				$emailURL = $thisProg . '&Action=SendEmail&passportType=3&administratorsName=' . urlencode($Mail) . '***' . trim(strtolower($user_name));
				$EnableQCoreClass->replace('emailURL', $emailURL);
			}

			$EnableQCoreClass->parse('user', 'USER', true);
		}

		$EnableQCoreClass->parse('UserList', 'UserListFile');
		$EnableQCoreClass->output('UserList');
	}

	if ($BaseRow && ($BaseRow['isUseOriPassport'] == '5')) {
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
		$ResIDArray = array();
		$RepSQL = ' SELECT DISTINCT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
		$RepResult = $DB->query($RepSQL);

		while ($RepRow = $DB->queryArray($RepResult)) {
			$this_ad_user_name = trim($RepRow['adUserName']);

			if ($this_ad_user_name != '') {
				$groupFlag = substr($this_ad_user_name, 0, 6);

				switch ($groupFlag) {
				case 'group/':
					$this_group_name = str_replace('group/', '', trim($this_ad_user_name));
					$this_group_all_users = $ldap->all_user_ou($this_group_name, 'dn');

					foreach ($this_group_all_users as $this_users) {
						$this_users = iconv('UTF-8', 'gbk', strtolower($this_users));
						if (($this_users != '') && !in_array($this_users, $ResIDArray)) {
							$ResIDArray[] = trim($this_users);
						}
					}

					break;

				default:
					$this_ad_user_name = strtolower($this_ad_user_name);
					if (($this_ad_user_name != '') && !in_array($this_ad_user_name, $ResIDArray)) {
						$ResIDArray[] = trim($this_ad_user_name);
					}

					break;
				}
			}
		}

		$SQL = ' SELECT DISTINCT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE overFlag !=0 ORDER BY administratorsName ASC ';
		$MemResult = $DB->query($SQL);
		$ComIDArray = array();

		while ($MemRow = $DB->queryArray($MemResult)) {
			$ComIDArray[] = strtolower($MemRow['administratorsName']);
		}

		$NoCommitNameArray = arraydiff($ResIDArray, $ComIDArray);
		$EnableQCoreClass->replace('recNum', count($NoCommitNameArray));
		$EnableQCoreClass->setTemplateFile('UserListFile', 'SurveyNoComLDAPList.html');
		$EnableQCoreClass->set_CycBlock('UserListFile', 'USER', 'user');
		$EnableQCoreClass->replace('user', '');
		$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

		if (count($NoCommitNameArray) == 0) {
			$EnableQCoreClass->replace('noUserList', 'disabled');
		}
		else {
			$EnableQCoreClass->replace('noUserList', '');
		}

		$userNamei = 0;

		foreach ($NoCommitNameArray as $user_name) {
			$this_user_name = iconv('gbk', 'UTF-8', $user_name);
			$info = $ldap->getUserInfo($this_user_name);
			$FirstName = $info[0]['givenname'][0];
			$LastName = $info[0]['sn'][0];
			$Account = explode(',', $info[0]['dn']);
			$Mail = $info[0]['mail'][0];
			$Department = $info[0]['dn'];
			$EnableQCoreClass->replace('xingming', iconv('UTF-8', 'gbk', $LastName . $FirstName));
			$EnableQCoreClass->replace('dengluming', iconv('UTF-8', 'gbk', $Account[0]));
			$EnableQCoreClass->replace('email', $Mail);
			$EnableQCoreClass->replace('department', iconv('UTF-8', 'gbk', $Department));
			$EnableQCoreClass->replace('userEmail', $Mail);
			$EnableQCoreClass->replace('userName', trim($user_name));
			$EnableQCoreClass->replace('userNamei', $userNamei);
			$userNamei++;

			if (trim($Mail) == '') {
				$EnableQCoreClass->replace('isNoEmail', 'none');
				$EnableQCoreClass->replace('emailURL', '');
			}
			else {
				$EnableQCoreClass->replace('isNoEmail', '');
				$emailURL = $thisProg . '&Action=SendEmail&passportType=5&administratorsName=' . urlencode($Mail) . '***' . trim(strtolower($user_name));
				$EnableQCoreClass->replace('emailURL', $emailURL);
			}

			$EnableQCoreClass->parse('user', 'USER', true);
		}

		$EnableQCoreClass->parse('UserList', 'UserListFile');
		$EnableQCoreClass->output('UserList');
	}
}

?>
