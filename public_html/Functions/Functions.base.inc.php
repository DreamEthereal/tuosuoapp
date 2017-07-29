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

function _showalert($errorTitle, $errorContent, $types = 0, $url = '')
{
	global $EnableQCoreClass;
	$EnableQCoreClass->setTemplateFile('ErrorFile', 'Alert.html');
	$EnableQCoreClass->replace(array('path' => ROOT_PATH . 'Images/', 'ERROR_TITLE' => $errorTitle, 'ERROR_CONTENT' => $errorContent));
	echo $EnableQCoreClass->parse('Error', 'ErrorFile');
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

function _showtreemessage($succeedTitle, $succeedURL)
{
	global $EnableQCoreClass;

	if (!$succeedURL) {
		echo '<script>$.notification(\'' . $succeedTitle . '\');window.opener.location.reload();self.close();</script>';
		exit();
	}

	$EnableQCoreClass->setTemplateFile('SucceedFile', 'TreeMessage.html');
	$EnableQCoreClass->replace(array('successed_title' => $succeedTitle, 'path' => ROOT_PATH . 'Images/', 'url' => $succeedURL));
	echo $EnableQCoreClass->parse('Succeed', 'SucceedFile');
	exit();
}

function _showmessage($succeedTitle, $isTrue = false, $returnVal = 0)
{
	global $EnableQCoreClass;
	$EnableQCoreClass->setTemplateFile('SucceedFile', 'Message.html');
	$EnableQCoreClass->replace(array('successed_title' => $succeedTitle, 'path' => ROOT_PATH . 'Images/', 'isTrue' => $isTrue, 'returnVal' => $returnVal));
	echo $EnableQCoreClass->parse('Succeed', 'SucceedFile');
	exit();
}

function _getip()
{
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$_obf_zeUCF4yH = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$_obf_zeUCF4yH = $_SERVER['HTTP_CLIENT_IP'];
		}
		else {
			$_obf_zeUCF4yH = $_SERVER['REMOTE_ADDR'];
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

function writetolog($logTitle, $isSetup = 0)
{
	global $DB;
	$_obf_xCnI = ' INSERT INTO ' . ADMINISTRATORSLOG_TABLE . ' (administratorsID,operationTitle,operationIP,createDate) VALUES (\'' . $_SESSION['administratorsID'] . '\',\'' . addslashes($logTitle) . '\',\'' . _getip() . '\',\'' . time() . '\' ) ';
	$DB->query($_obf_xCnI);
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

function cnsubstr($str, $start = 0, $end = 0, $flag = 0)
{
	$_obf_u_c_ = chr(127);
	$_obf_8w__ = array('/[Å-˛]([Å-˛]|[@-˛])/', '/[-w]/');
	$_obf_OQ__ = array('', '');

	if (2 < func_num_args()) {
		$end = func_get_arg(2);
	}
	else {
		$end = strlen($str);
	}

	if ($start < 0) {
		$start += $end;
	}

	if (0 < $start) {
		$p = substr($str, 0, $start);

		if ($_obf_u_c_ < $p[strlen($p) - 1]) {
			$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
			$start += strlen($p);
		}
	}

	$p = substr($str, $start, $end * 2);
	$end = strlen($p);

	if ($_obf_u_c_ < $p[$end - 1]) {
		$p = preg_replace($_obf_8w__, $_obf_OQ__, $p);
		$end += strlen($p);
	}

	$_obf_4VWt = substr($str, $start, $end);
	if (($end < strlen($str)) && ($flag == 1)) {
		$_obf_4VWt .= '...';
	}

	return $_obf_4VWt;
}

function _checkroletype($roleTypeList)
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $_SESSION;
	$_obf_aNJOOXy87HkhK7wQuQ__ = explode('|', $roleTypeList);

	if (!in_array($_SESSION['adminRoleType'], $_obf_aNJOOXy87HkhK7wQuQ__)) {
		_showerror($lang['auth_error'], $lang['passport_is_permit']);
	}
}

function _checkpassport($roleTypeList, $surveyID = 0)
{
	global $DB;
	global $lang;
	global $EnableQCoreClass;
	global $_SESSION;
	$surveyID = (int) $surveyID;

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

function _getnexturl($surveyID, $questionID, $orderByID)
{
	global $DB;
	global $lastProg;
	$_obf_xCnI = ' SELECT questionID,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND questionID != \'' . $questionID . '\' AND questionType != 8 AND orderByID > \'' . $orderByID . '\' ORDER BY orderByID ASC LIMIT 0,1 ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

	if ($_obf_9WwQ) {
		$_obf_qGYKcPfxlA__ = $lastProg . '&Action=View&questionID=' . $_obf_9WwQ['questionID'] . '&questionType=' . $_obf_9WwQ['questionType'];
	}
	else {
		$_obf_qGYKcPfxlA__ = '';
	}

	return $_obf_qGYKcPfxlA__;
}

function _getsql($SQL)
{
	$SQL = trim($SQL);
	$SQL = str_replace('\\\'', '\'', $SQL);
	$SQL = str_replace('"', '"', $SQL);
	return $SQL;
}

function _getuserslist($administratorsID = 0)
{
	global $DB;
	global $EnableQCoreClass;
	global $lang;
	$_obf_xCnI = ' SELECT administratorsID,administratorsName,nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin IN (1,2,5) ORDER BY administratorsName ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_jmgX0LFC8jlhWA__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['administratorsID'] == $administratorsID) {
			$_obf_jmgX0LFC8jlhWA__ .= '<option value="' . $_obf_9WwQ['administratorsID'] . '" selected>' . $_obf_9WwQ['administratorsName'] . '(' . $_obf_9WwQ['nickName'] . ')' . '</option>' . "\n" . '';
		}
		else {
			$_obf_jmgX0LFC8jlhWA__ .= '<option value="' . $_obf_9WwQ['administratorsID'] . '">' . $_obf_9WwQ['administratorsName'] . '(' . $_obf_9WwQ['nickName'] . ')' . '</option>' . "\n" . '';
		}
	}

	return $_obf_jmgX0LFC8jlhWA__;
}

function _obf_czBqbnV6fTECQGRobjkBbA__($administratorsID = 0)
{
	global $DB;
	global $EnableQCoreClass;
	global $lang;
	$_obf_xCnI = ' SELECT administratorsID,administratorsName,nickName FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin NOT IN (0,3) ORDER BY administratorsName ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_jmgX0LFC8jlhWA__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['administratorsID'] == $administratorsID) {
			$_obf_jmgX0LFC8jlhWA__ .= '<option value="' . $_obf_9WwQ['administratorsID'] . '" selected>' . $_obf_9WwQ['administratorsName'] . '(' . $_obf_9WwQ['nickName'] . ')' . '</option>' . "\n" . '';
		}
		else {
			$_obf_jmgX0LFC8jlhWA__ .= '<option value="' . $_obf_9WwQ['administratorsID'] . '">' . $_obf_9WwQ['administratorsName'] . '(' . $_obf_9WwQ['nickName'] . ')' . '</option>' . "\n" . '';
		}
	}

	return $_obf_jmgX0LFC8jlhWA__;
}

function _getcateslist($cateID = 0)
{
	global $DB;
	global $EnableQCoreClass;
	global $lang;
	$_obf_xCnI = ' SELECT cateID,cateName,cateTag FROM ' . SURVEYCATE_TABLE . ' ORDER BY cateID DESC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_1v0S7fRzbsjZ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['cateID'] == $cateID) {
			$_obf_1v0S7fRzbsjZ .= '<option value="' . $_obf_9WwQ['cateID'] . '" selected>' . $_obf_9WwQ['cateName'] . '</option>' . "\n" . '';
		}
		else {
			$_obf_1v0S7fRzbsjZ .= '<option value="' . $_obf_9WwQ['cateID'] . '">' . $_obf_9WwQ['cateName'] . '</option>' . "\n" . '';
		}
	}

	return $_obf_1v0S7fRzbsjZ;
}

function _skipitenablechar($str)
{
	$str = str_replace('<a href=http://www.enableq.com target=_BLANK>', '', $str);
	$str = str_replace('</a>', '', $str);
	return $str;
}

function sectotime($time)
{
	if (is_numeric($time)) {
		$_obf_VgKtFeg_ = array('years' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0);

		if (31556926 <= $time) {
			$_obf_VgKtFeg_['years'] = floor($time / 31556926);
			$time = $time % 31556926;
		}

		if (86400 <= $time) {
			$_obf_VgKtFeg_['days'] = floor($time / 86400);
			$time = $time % 86400;
		}

		if (3600 <= $time) {
			$_obf_VgKtFeg_['hours'] = floor($time / 3600);
			$time = $time % 3600;
		}

		if (60 <= $time) {
			$_obf_VgKtFeg_['minutes'] = floor($time / 60);
			$time = $time % 60;
		}

		$_obf_VgKtFeg_['seconds'] = floor($time);
		return $_obf_VgKtFeg_['days'] . 'ÃÏ' . $_obf_VgKtFeg_['hours'] . '–° ±' . $_obf_VgKtFeg_['minutes'] . '∑÷' . $_obf_VgKtFeg_['seconds'] . '√Î';
	}
	else {
		return '¥ÌŒÛµƒ ±º‰∏Ò Ω';
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.string.inc.php';

?>
