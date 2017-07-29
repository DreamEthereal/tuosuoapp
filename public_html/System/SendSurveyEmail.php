<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);

if ($S_Row['status'] != '1') {
	_showerror($lang['system_error'], $lang['no_exe_survey']);
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

if ($_POST['Action'] == 'EmailSendSubmit') {
	@set_time_limit(0);

	switch ($_POST['passType']) {
	case '3':
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
		$SendMembersEmail = array();
		$SendMembersName = array();
		$RepArray = explode(';', $_POST['adUserName']);

		foreach ($RepArray as $RepList) {
			if (trim($RepList) != '') {
				$groupFlag = substr(trim($RepList), 0, 6);

				switch ($groupFlag) {
				case 'group/':
					$this_group_name = str_replace('group/', '', trim($RepList));
					$this_group_all_users = $LDAP->all_group_users($this_group_name);

					foreach ($this_group_all_users as $this_users) {
						$this_user_name = iconv('gbk', 'UTF-8', $this_users);
						$userMail = $LDAP->user_info($this_user_name, array('mail'));
						$emailAddress = $userMail[0]['mail'][0];
						if (($emailAddress != '') && !in_array($emailAddress, $SendMembersEmail)) {
							$theUserEmail = trim($emailAddress);
							$SendMembersEmail[] = $theUserEmail;
							$SendMembersName[$theUserEmail] = $this_users;
						}
					}

					break;

				default:
					$this_user_name = iconv('gbk', 'UTF-8', trim($RepList));
					$userMail = $LDAP->user_info($this_user_name, array('mail'));
					$emailAddress = $userMail[0]['mail'][0];
					if (($emailAddress != '') && !in_array($emailAddress, $SendMembersEmail)) {
						$theUserEmail = trim($emailAddress);
						$SendMembersEmail[] = $theUserEmail;
						$SendMembersName[$theUserEmail] = $RepList;
					}

					break;
				}
			}
		}

		break;

	case '5':
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
		$SendMembersEmail = array();
		$SendMembersName = array();
		$RepArray = explode(';', $_POST['adUserName']);

		foreach ($RepArray as $RepList) {
			if (trim($RepList) != '') {
				$groupFlag = substr(trim($RepList), 0, 6);

				switch ($groupFlag) {
				case 'group/':
					$this_group_name = str_replace('group/', '', trim($RepList));
					$this_group_all_users_mail = $ldap->all_user_ou($this_group_name, 'mail');

					foreach ($this_group_all_users_mail as $this_user => $this_users_mail) {
						$this_users_mail = strtolower($this_users_mail);
						if (($this_users_mail != '') && !in_array($this_users_mail, $SendMembersEmail)) {
							$SendMembersEmail[] = $this_users_mail;
							$SendMembersName[$this_users_mail] = iconv('UTF-8', 'gbk', $this_user);
						}
					}

					break;

				default:
					$userMail = $ldap->getUserMail($RepList);
					$userMail = strtolower($userMail);
					if (($userMail != '') && !in_array($userMail, $SendMembersEmail)) {
						$SendMembersEmail[] = $userMail;
						$SendMembersName[$userMail] = $RepList;
					}

					break;
				}
			}
		}

		break;

	default:
		$SendMembersEmail = explode(';', $_POST['panelUserName']);
		break;
	}

	$SendMembersEmail = array_unique($SendMembersEmail);
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

		foreach ($SendMembersEmail as $sendMailName) {
			ob_end_clean();
			$isSkipFlag = false;
			$sendMailName = trim($sendMailName);

			if ($sendMailName != '') {
				if (checkemail($sendMailName)) {
					switch ($_POST['passType']) {
					case '1':
						$SQL = ' SELECT responseID FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag !=0 AND administratorsName = \'' . strtolower($sendMailName) . '\' LIMIT 1 ';
						$isHaveData = $DB->queryFirstRow($SQL);

						if ($isHaveData) {
							$isSkipFlag = true;
						}
						else {
							$theSQL = ' SELECT nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsName = \'' . trim($sendMailName) . '\' LIMIT 1 ';
							$theRow = $DB->queryFirstRow($theSQL);

							if ($theRow) {
								$SendMailContent = str_replace('{userName}', $theRow['nickName'], $theSendMailContent);
							}
							else {
								$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
							}
						}

						break;

					default:
						$SendMailContent = str_replace('{userName}', strtolower($sendMailName), $theSendMailContent);
						break;
					}

					switch ($_POST['passType']) {
					case '3':
					case '5':
						$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName)))) . '&userAdName=' . escape(str_replace('+', '%2B', base64_encode(strtolower($SendMembersName[$sendMailName]))));
						$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);
						break;

					default:
						$theUserEmailURL = escape(str_replace('+', '%2B', base64_encode(strtolower($sendMailName))));
						$SendMailContent = str_replace('{userEmail}', $theUserEmailURL, $SendMailContent);
						break;
					}

					if ($isSkipFlag == true) {
						$str = '<div class="tipsinfo"><span class=red>已收到回复：</span><b>' . strtolower($sendMailName) . '</b></div>' . "\n" . '';
						echo $prefix . $str . $scroll;
						continue;
					}

					sleep(1);

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
	unset($SendMembersName);
	echo '<script>parent._showCloseWindowButton();</script>';
	exit();
}

if (!isset($_POST['Action'])) {
	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);
	if (($BaseRow['isUseOriPassport'] == '3') || ($BaseRow['isUseOriPassport'] == '5')) {
		$EnableQCoreClass->setTemplateFile('SendSurveyFile', 'SendSurveyAdMail.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('SendSurveyFile', 'SendSurveyMail.html');
	}

	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('passType', $BaseRow['isUseOriPassport']);
	$SQL = ' SELECT isPublic,surveyTitle,surveySubTitle,surveyInfo,surveyName,tokenCode,lang FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$SurRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('isPublic', $SurRow['isPublic']);
	$EnableQCoreClass->replace('surveyType', $lang['isPublic_' . $SurRow['isPublic']]);

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		switch ($SurRow['isPublic']) {
		case '1':
		case '2':
			$EnableQCoreClass->replace('isPrivate', '');
			$EnableQCoreClass->replace('privateSurvey', '');
			$EnableQCoreClass->replace('panelUserName', '');
			break;

		case '0':
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

			$SQL = ' SELECT DISTINCT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE overFlag !=0 ORDER BY administratorsName ASC ';
			$MemResult = $DB->query($SQL);
			$ComIDArray = array();

			while ($MemRow = $DB->queryArray($MemResult)) {
				$ComIDArray[] = $MemRow['administratorsName'];
			}

			$NoCommitNameArray = arraydiff($ResIDArray, $ComIDArray);

			if (!empty($NoCommitNameArray)) {
				$EnableQCoreClass->replace('panelUserName', implode(';', $NoCommitNameArray));
			}
			else {
				$EnableQCoreClass->replace('panelUserName', '');
			}

			$EnableQCoreClass->replace('isPrivate', 'none');
			$EnableQCoreClass->replace('privateSurvey', 'readonly');
			break;
		}

		break;

	case '2':
	case '4':
		switch ($SurRow['isPublic']) {
		case '1':
		case '2':
			$EnableQCoreClass->replace('isPrivate', '');
			$EnableQCoreClass->replace('privateSurvey', '');
			$EnableQCoreClass->replace('panelUserName', '');
			break;

		case '0':
			$EnableQCoreClass->replace('isPrivate', 'none');
			$EnableQCoreClass->replace('privateSurvey', 'readonly');
			$EnableQCoreClass->replace('panelUserName', '');
			break;
		}

		break;

	case '3':
		$EnableQCoreClass->replace('LDAPURL', 'ShowSelLDAP.php');

		switch ($SurRow['isPublic']) {
		case '1':
		case '2':
			$EnableQCoreClass->replace('isPrivate', '');
			$EnableQCoreClass->replace('privateSurvey', '');
			$EnableQCoreClass->replace('adUserName', '');
			break;

		case '0':
			$EnableQCoreClass->replace('isPrivate', 'none');
			$EnableQCoreClass->replace('privateSurvey', 'readonly');
			$SQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND adUserName != \'\' ';
			$Result = $DB->query($SQL);

			while ($AdUserRow = $DB->queryArray($Result)) {
				$AdUser[] = trim($AdUserRow['adUserName']);
			}

			if (!empty($AdUser)) {
				$adUserName = implode(';', $AdUser);
			}

			if ($adUserName != '') {
				$EnableQCoreClass->replace('adUserName', $adUserName . ';');
			}
			else {
				$EnableQCoreClass->replace('adUserName', '');
			}

			break;
		}

		break;

	case '5':
		$EnableQCoreClass->replace('LDAPURL', 'ShowSelOpenLDAP.php');

		switch ($SurRow['isPublic']) {
		case '1':
		case '2':
			$EnableQCoreClass->replace('isPrivate', '');
			$EnableQCoreClass->replace('privateSurvey', '');
			$EnableQCoreClass->replace('adUserName', '');
			break;

		case '0':
			$EnableQCoreClass->replace('isPrivate', 'none');
			$EnableQCoreClass->replace('privateSurvey', 'readonly');
			$SQL = ' SELECT adUserName FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND adUserName != \'\' ';
			$Result = $DB->query($SQL);

			while ($AdUserRow = $DB->queryArray($Result)) {
				$AdUser[] = trim($AdUserRow['adUserName']);
			}

			if (!empty($AdUser)) {
				$adUserName = implode(';', $AdUser);
			}

			if ($adUserName != '') {
				$EnableQCoreClass->replace('adUserName', $adUserName . ';');
			}
			else {
				$EnableQCoreClass->replace('adUserName', '');
			}

			break;
		}

		break;
	}

	switch (strtolower(trim($SurRow['lang']))) {
	case 'cn':
	default:
		$qlang = 'CN';
		break;

	case 'en':
		$qlang = 'EN';
		break;
	}

	$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/' . $qlang);
	$EnableQCoreClass->setTemplateFile('SendSurveyMailFile', 'SurveyMail.html');
	$EnableQCoreClass->replace('mail_date', date('Y-m-d', time()));

	if (strtolower(trim($SurRow['lang'])) == 'en') {
		$EnableQCoreClass->replace('siteName', 'EnableQ Survey System');
	}
	else {
		$EnableQCoreClass->replace('siteName', $Config['siteName']);
	}

	$EnableQCoreClass->replace('surveyTitle', $SurRow['surveyTitle']);
	$EnableQCoreClass->replace('surveySubTitle', $SurRow['surveySubTitle']);
	$EnableQCoreClass->replace('surveyInfo', $SurRow['surveyInfo']);
	$EnableQCoreClass->replace('tokenCode', $SurRow['tokenCode']);
	$ProgURL = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -26);
	$surveyURL = $ProgURL . 'q.php?qname=' . $SurRow['surveyName'] . '&qlang=' . $SurRow['lang'];
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

?>
