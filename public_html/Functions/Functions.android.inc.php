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

	$EnableQCoreClass->setTemplateFile('ErrorFile', 'uAndroidNotes.html');
	$EnableQCoreClass->replace(array('notes_title' => $errorTitle, 'notes_content' => $errorContent));

	if ($types == '3') {
		$EnableQCoreClass->replace('ERROR_URL', $url);
	}

	echo $EnableQCoreClass->parse('Error', 'ErrorFile');
	exit();
}

function _shownotes($notes_title = '', $notes_content = '', $notes_tip = '', $thetype = 1)
{
	global $EnableQCoreClass;
	$EnableQCoreClass->setTemplateFile('NotesFile', 'uAndroidNotes.html');
	$EnableQCoreClass->replace(array('notes_title' => $notes_title, 'notes_content' => $notes_content, 'notes_tip' => $notes_tip));

	if ($thetype != 1) {
		$EnableQCoreClass->replace('boxWidth', '735');
		$EnableQCoreClass->replace('boxLeft', '145');
		$EnableQCoreClass->replace('boxTop', '');
	}
	else {
		$EnableQCoreClass->replace('boxWidth', '540');
		$EnableQCoreClass->replace('boxLeft', '240');
		$EnableQCoreClass->replace('boxTop', '<br/>');
	}

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

function _getdftplfile($panelID, $siteTitle, $SurveyPage, $titleFlag = 0)
{
	global $EnableQCoreClass;
	global $Config;
	$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/CN');
	$EnableQCoreClass->setTemplateFile('PanelPageFile', 'uAndroidPanel.html');
	$EnableQCoreClass->replace('surveyTitle', $siteTitle);
	$EnableQCoreClass->replace('surveyShortTitle', cnsubstr($siteTitle, 0, 16, 1));
	$EnableQCoreClass->replace('enableq', $SurveyPage);
	$_obf_HnMIZBevWg__ = $EnableQCoreClass->parse('PanelPage', 'PanelPageFile');
	$_obf_HnMIZBevWg__ = str_replace('<LINK href="CSS/Base.css" rel=stylesheet>', '', $_obf_HnMIZBevWg__);
	$_obf_HnMIZBevWg__ = str_replace('LoginBox.css', 'LoginBoxAndroid.css', $_obf_HnMIZBevWg__);
	$_obf_HnMIZBevWg__ = str_replace('JS/Jquery.notification.js.php', 'JS/Jquery.android.js.php', $_obf_HnMIZBevWg__);
	return $_obf_HnMIZBevWg__;
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

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.mail.inc.php';
include_once ROOT_PATH . 'Functions/Functions.string.inc.php';

?>
