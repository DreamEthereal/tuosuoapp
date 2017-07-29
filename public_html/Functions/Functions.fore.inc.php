<?php
//dezend by http://www.yunlu99.com/
function _showerror($errorTitle, $errorContent, $types = 0, $url = '')
{
	global $EnableQCoreClass;

	if ($types == '1') {
		if ($url == '') {
			echo '<script>$.notification(\'' . $errorContent . '\');';
			echo 'window.history.back();';
			echo '</script>';
			exit();
		}
		else {
			echo '<script>$.notification(\'' . $errorContent . '\');';
			echo 'window.location.href=\'' . $url . '\';';
			echo '</script>';
			exit();
		}
	}

	if ($types == '2') {
		echo '<script>$.notification(\'' . $errorContent . '\');';
		echo 'window.close();';
		echo '</script>';
		exit();
	}

	$EnableQCoreClass->setTemplateFile('ErrorFile', 'Error.html');
	$EnableQCoreClass->replace(array('path' => ROOT_PATH . 'Images/', 'ERROR_TITLE' => $errorTitle, 'ERROR_CONTENT' => $errorContent, 'ERROR_URL' => 'javascript:history.back(1)'));

	if ($types == '3') {
		$EnableQCoreClass->replace('ERROR_URL', $url);
	}

	echo $EnableQCoreClass->parse('Error', 'ErrorFile');
	exit();
}

function _shownotes($notes_title = '', $notes_content = '', $notes_tip = '', $notes_extend = '')
{
	global $EnableQCoreClass;
	global $isMobile;
	global $DB;
	global $Config;

	if ($isMobile) {
		$EnableQCoreClass->setTemplateFile('NotesFile', 'mNotes.html');
		if (!isset($_SESSION['sid']) || ((int) $_SESSION['sid'] == 0)) {
			$EnableQCoreClass->replace('surveyTitle', '');
			$EnableQCoreClass->replace('surveyDesc', '');
			$EnableQCoreClass->replace('msgImage', '');
			$EnableQCoreClass->replace('surveyLink', '');
			$EnableQCoreClass->replace('uitheme', 'Phone');
		}
		else {
			$_obf_xCnI = ' SELECT surveyTitle,surveyInfo,surveyID,lang,msgImage,uitheme,AppId FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_SESSION['sid'] . '\' ';
			$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_xCnI);
			$EnableQCoreClass->replace('surveyTitle', $_obf__aTmJQ__['surveyTitle']);
			$EnableQCoreClass->replace('surveyDesc', str_replace('\'', '&#39;', strip_tags($_obf__aTmJQ__['surveyInfo'])));
			$EnableQCoreClass->replace('uitheme', $_obf__aTmJQ__['uitheme'] == '' ? 'Phone' : $_obf__aTmJQ__['uitheme']);
			$EnableQCoreClass->replace('app_id', $_obf__aTmJQ__['AppId']);
			$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -5);

			if ($Config['dataDomainName'] != '') {
				$_obf__CMIUEylUeU_ = 'http://' . $Config['dataDomainName'] . '/';
			}
			else {
				$_obf__CMIUEylUeU_ = $_obf_eHCKgWnq_TI_;
			}

			if (trim($_obf__aTmJQ__['msgImage']) != '') {
				$EnableQCoreClass->replace('msgImage', $_obf__CMIUEylUeU_ . 'PerUserData/logo/' . trim($_obf__aTmJQ__['msgImage']));
			}
			else {
				$EnableQCoreClass->replace('msgImage', '');
			}

			$_obf_QUJS_tZGW9r3Fg__ = $_obf__CMIUEylUeU_ . 'q.php?qid=' . $_obf__aTmJQ__['surveyID'] . '&qlang=' . $_obf__aTmJQ__['lang'];
			$EnableQCoreClass->replace('surveyLink', $_obf_QUJS_tZGW9r3Fg__);
		}

		$EnableQCoreClass->replace(array('path' => ROOT_PATH . 'Images/', 'notes_title' => $notes_title, 'page_title' => $notes_tip, 'notes_content' => $notes_content, 'notes_extend' => $notes_extend));
	}
	else {
		$EnableQCoreClass->setTemplateFile('NotesFile', 'Notes.html');
		$EnableQCoreClass->replace(array('path' => ROOT_PATH . 'Images/', 'notes_title' => $notes_title, 'notes_content' => $notes_content, 'notes_tip' => $notes_tip, 'notes_extend' => $notes_extend));
	}

	unset($_SESSION['sid']);
	echo $EnableQCoreClass->parse('Notes', 'NotesFile');
	exit();
}

function _showsucceed($succeedTitle, $succeedURL)
{
	global $EnableQCoreClass;

	if (!$succeedURL) {
		echo '<script>$.notification(\'' . $succeedTitle . '\');window.opener.location.reload();self.close();</script>';
		exit();
	}

	$EnableQCoreClass->setTemplateFile('SucceedFile', 'Successed.html');
	$EnableQCoreClass->replace(array('successed_title' => $succeedTitle, 'path' => ROOT_PATH . 'Images/', 'url' => $succeedURL));
	echo $EnableQCoreClass->parse('Succeed', 'SucceedFile');
	exit();
}

function _showpause($succeedTitle, $succeedURL)
{
	global $EnableQCoreClass;

	if (!$succeedURL) {
		echo '<script>$.notification(\'' . $succeedTitle . '\');window.opener.location.reload();self.close();</script>';
		exit();
	}

	$EnableQCoreClass->setTemplateFile('SucceedFile', 'Pause.html');
	$EnableQCoreClass->replace(array('successed_title' => $succeedTitle, 'path' => ROOT_PATH . 'Images/', 'url' => $succeedURL));
	echo $EnableQCoreClass->parse('Succeed', 'SucceedFile');
	exit();
}

function _getip()
{
	if (isset($_SERVER)) {
		if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
			$_obf_zeUCF4yH = $_SERVER[HTTP_X_FORWARDED_FOR];
		}
		else if (isset($_SERVER[HTTP_CLIENT_IP])) {
			$_obf_zeUCF4yH = $_SERVER[HTTP_CLIENT_IP];
		}
		else {
			$_obf_zeUCF4yH = $_SERVER[REMOTE_ADDR];
		}
	}
	else if (getenv('HTTP_X_FORWARDED_FOR')) {
		$_obf_zeUCF4yH = getenv('HTTP_X_FORWARDED_FOR');
	}
	else if (getenv('HTTP_CLIENT_IP')) {
		$_obf_zeUCF4yH = getenv('HTTP_CLIENT_IP');
	}
	else {
		$_obf_zeUCF4yH = getenv('REMOTE_ADDR');
	}

	return preg_replace('/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), preg_replace('/%27/', '\\\'', addslashes($_obf_zeUCF4yH))));
}

function updateorderid($table)
{
	global $DB;
	global $table_prefix;
	global $lastOrderID;
	$_obf_8HT7Nr1Elw__ = $table . 'ID';
	$table = $table_prefix . strtolower($table);
	$lastOrderID = $DB->_GetInsertID();
	$_obf_xCnI = ' UPDATE ' . $table . ' SET orderByID=\'' . $lastOrderID . '\' WHERE ' . $_obf_8HT7Nr1Elw__ . '=\'' . $lastOrderID . '\' ';
	$DB->query($_obf_xCnI);
}

function dateDiff($interval, $date1, $date2)
{
	$_obf_GikmP3L3bXixVsC0Ooo_ = $date2 - $date1;

	switch ($interval) {
	case 'w':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 604800);
		break;

	case 'd':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 86400);
		break;

	case 'h':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 3600);
		break;

	case 'n':
		$_obf_cG4B7L_a = @round($_obf_GikmP3L3bXixVsC0Ooo_ / 60);
		break;

	case 's':
		$_obf_cG4B7L_a = $_obf_GikmP3L3bXixVsC0Ooo_;
		break;
	}

	return $_obf_cG4B7L_a;
}

function makepassword($length = 10)
{
	$_obf_Lyy_SC3IF7I_ = '';
	$_obf_1fX6tEY_ = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z');
	$_obf_gftfagw_ = count($_obf_1fX6tEY_) - 1;
	srand((double) microtime() * 1000000);
	$_obf_7w__ = 0;

	for (; $_obf_7w__ < $length; $_obf_7w__++) {
		$_obf_Lyy_SC3IF7I_ .= $_obf_1fX6tEY_[rand(0, $_obf_gftfagw_)];
	}

	return $_obf_Lyy_SC3IF7I_;
}

function createDir($dir, $mode = 511)
{
	if (is_dir($dir)) {
		return true;
	}

	$dir = dirname($dir . '/a');
	$_obf_Byrh3A__ = str_replace('\\', '/', $dir);
	$_obf_jXiWt4lZNw__ = explode('/', $_obf_Byrh3A__);
	$_obf_gftfagw_ = count($_obf_jXiWt4lZNw__);
	$_obf_7w__ = count($_obf_jXiWt4lZNw__) - 1;

	for (; 0 <= $_obf_7w__; $_obf_7w__--) {
		$_obf_Byrh3A__ = dirname($_obf_Byrh3A__);

		if (is_dir($_obf_Byrh3A__)) {
			$_obf_XA__ = $_obf_7w__;

			for (; $_obf_XA__ < $_obf_gftfagw_; $_obf_XA__++) {
				$_obf_Byrh3A__ .= '/' . $_obf_jXiWt4lZNw__[$_obf_XA__];

				if (is_dir($_obf_Byrh3A__)) {
					continue;
				}

				$_obf_eSsQSg__ = @mkdir($_obf_Byrh3A__, $mode);

				if (!$_obf_eSsQSg__) {
					return false;
				}
			}

			return true;
		}
	}

	return false;
}

function _getsql($SQL)
{
	$SQL = trim($SQL);
	$SQL = str_replace('\\\'', '\'', $SQL);
	$SQL = str_replace('"', '"', $SQL);
	return $SQL;
}

function _checkpassport($roleTypeList, $surveyID = 0)
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $_SESSION;

	if ($_SESSION['administratorsID'] == '') {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}

	$_obf_aNJOOXy87HkhK7wQuQ__ = explode('|', $roleTypeList);

	if (in_array($_SESSION['adminRoleType'], $_obf_aNJOOXy87HkhK7wQuQ__)) {
		switch ($_SESSION['adminRoleType']) {
		case '2':
			$_obf_xCnI = ' SELECT administratorsID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
			$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_xCnI);

			if ($_obf_vI9iIiW3Qg__['administratorsID'] != $_SESSION['administratorsID']) {
				_showerror($lang['auth_error'], $lang['passport_is_permit']);
			}

			break;

		case '3':
		case '7':
			switch ($_SESSION['adminRoleGroupType']) {
			case 1:
				$_obf_xCnI = ' SELECT administratorsID FROM ' . VIEWUSERLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
				$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_xCnI);
				$_obf_OWpxVw__ = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' ';
				$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);
				if (!$_obf_vI9iIiW3Qg__ && !$_obf__aTmJQ__) {
					_showerror($lang['auth_error'], $lang['passport_is_permit']);
				}

				break;

			case 2:
				$_obf_xCnI = ' SELECT projectType,projectOwner FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
				$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_xCnI);
				$_obf_xCnI = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\' ';
				$_obf_sThE0g__ = $DB->queryFirstRow($_obf_xCnI);
				$_obf_GmthOwcH9Y_hFQ__ = explode('-', $_obf_sThE0g__['absPath']);

				if (count($_obf_GmthOwcH9Y_hFQ__) == 1) {
					if (($_obf__aTmJQ__['projectType'] != 1) || ($_obf__aTmJQ__['projectOwner'] != $_SESSION['adminRoleGroupID'])) {
						_showerror($lang['auth_error'], $lang['passport_is_permit']);
					}
				}
				else {
					if (($_obf__aTmJQ__['projectType'] != 1) || !in_array($_obf__aTmJQ__['projectOwner'], $_obf_GmthOwcH9Y_hFQ__)) {
						_showerror($lang['auth_error'], $lang['passport_is_permit']);
					}
				}

				break;
			}

			break;

		case '4':
			$_obf_xCnI = ' SELECT administratorsID FROM ' . INPUTUSERLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND administratorsID =\'' . $_SESSION['administratorsID'] . '\' LIMIT 0,1 ';
			$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_xCnI);

			if (!$_obf_vI9iIiW3Qg__) {
				_showerror($lang['auth_error'], $lang['passport_is_permit']);
			}

			break;

		case '5':
			$_obf_zo6x9JUdFNrlHQ__ = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
			$_obf_xCnI = ' SELECT administratorsID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND administratorsID IN (' . $_obf_zo6x9JUdFNrlHQ__ . ') LIMIT 0,1 ';
			$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_xCnI);

			if (!$_obf_vI9iIiW3Qg__) {
				_showerror($lang['auth_error'], $lang['passport_is_permit']);
			}

			break;
		}
	}
	else {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';

?>
