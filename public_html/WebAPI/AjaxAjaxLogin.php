<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'License/License.xml';
include_once ROOT_PATH . 'Functions/Functions.socket.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
$SQL = ' SELECT license,ajaxCheckURL,ajaxTokenURL,isPostMethod,isMd5Pass FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);
$hashCode = md5(trim($SerialRow['license']));

if ($SerialRow['isPostMethod'] == 1) {
	$token = '';

	if (trim($SerialRow['ajaxTokenURL']) != '') {
		$postData = array();
		$postData['hash'] = $hashCode;
		$token = post_gbk_data_to_host(trim($SerialRow['ajaxTokenURL']), $postData);
	}

	if (($_POST['task'] == 'check') && ($License['AjaxPassport'] == 1)) {
		header('Content-Type:text/html; charset=gbk');

		if ($_SESSION['hash'] == md5(trim($SerialRow['license']))) {
			$username = base64_encode(iconv('UTF-8', 'gbk', trim($_POST['username'])));

			if ($SerialRow['isMd5Pass'] == 1) {
				$password = trim($_POST['password']);
			}
			else {
				$password = base64_decode(iconv('UTF-8', 'gbk', trim($_POST['password'])));
			}

			$postData = array();
			$postData['username'] = $username;
			$postData['password'] = $password;
			$postData['surveyID'] = $_POST['surveyID'];
			$postData['hash'] = $hashCode;
			$sign = md5('username=' . $username . '&password=' . $password . '&surveyID=' . $_POST['surveyID'] . '&hash=' . $hashCode . '&' . $token);
			$postData['sign'] = $sign;
			$ajaxRtnContent = post_gbk_data_to_host(trim($SerialRow['ajaxCheckURL']), $postData);
			$haveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE administratorsName = \'' . iconv('UTF-8', 'gbk', trim($_POST['username'])) . '\' AND overFlag !=0 LIMIT 0,1 ';
			$haveRow = $DB->queryFirstRow($haveSQL);

			if ($haveRow) {
				exit('false|' . $lang['members_permit_survey']);
			}
			else {
				$theRtnArray = explode('|', $ajaxRtnContent);

				if ($theRtnArray[0] == 'false') {
					exit($ajaxRtnContent);
				}
				else {
					$ajaxRtnValue = explode('#', $theRtnArray[1]);

					if (6 < count($ajaxRtnValue)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValue);
					}

					$_SESSION['ajaxCount'] = $ajaxCount;
					$ajaxRtnValueCate = array();
					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$ajaxRtnValueSign = explode('=', $ajaxRtnValue[$i]);
						$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
						$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
					}

					$SQL = ' SELECT ajaxRtnValue FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
					$S_Row = $DB->queryFirstRow($SQL);

					if ($S_Row['ajaxRtnValue'] == '') {
						$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
						$DB->query($SQL);
					}

					unset($ajaxRtnValue);
					unset($ajaxRtnValueCate);
					exit('true|true');
				}
			}
		}
		else {
			exit('false|EnableQ Security Violation');
		}
	}
}
else {
	$token = '';

	if (trim($SerialRow['ajaxTokenURL']) != '') {
		if (strpos(trim($SerialRow['ajaxTokenURL']), '?') === false) {
			$ajaxURL = trim($SerialRow['ajaxTokenURL']) . '?hash=' . $hashCode;
		}
		else {
			$ajaxURL = trim($SerialRow['ajaxTokenURL']) . '&hash=' . $hashCode;
		}

		$token = get_url_content($ajaxURL);
	}

	if (($_GET['task'] == 'check') && ($License['AjaxPassport'] == 1)) {
		header('Content-Type:text/html; charset=gbk');

		if ($_SESSION['hash'] == md5(trim($SerialRow['license']))) {
			if ($Config['data_base64_enable'] == 1) {
				$signname = base64_encode(trim($_GET['username']));
				$username = str_replace('+', '%2B', $signname);
			}
			else {
				$username = urlencode(trim($_GET['username']));
				$signname = trim($_GET['username']);
			}

			if ($SerialRow['isMd5Pass'] == 1) {
				$password = $signpass = trim($_GET['password']);
			}
			else if ($Config['data_base64_enable'] == 1) {
				$signpass = base64_decode(trim($_GET['password']));
				$password = str_replace('+', '%2B', $signpass);
			}
			else {
				$password = urlencode(trim($_GET['password']));
				$signpass = trim($_GET['password']);
			}

			$sign = md5('username=' . $signname . '&password=' . $signpass . '&surveyID=' . $_GET['surveyID'] . '&hash=' . $hashCode . '&' . $token);

			if (strpos(trim($SerialRow['ajaxCheckURL']), '?') === false) {
				$ajaxURL = trim($SerialRow['ajaxCheckURL']) . '?';
			}
			else {
				$ajaxURL = trim($SerialRow['ajaxCheckURL']) . '&';
			}

			$ajaxURL .= 'surveyID=' . $_GET['surveyID'] . '&username=' . $username . '&password=' . $password . '&hash=' . md5(trim($SerialRow['license'])) . '&sign=' . $sign;
			$ajaxRtnContent = get_url_content($ajaxURL);
			$haveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE administratorsName = \'' . trim($_GET['username']) . '\' AND overFlag !=0 LIMIT 0,1 ';
			$haveRow = $DB->queryFirstRow($haveSQL);

			if ($haveRow) {
				exit('false|' . $lang['members_permit_survey']);
			}
			else {
				$theRtnArray = explode('|', $ajaxRtnContent);

				if ($theRtnArray[0] == 'false') {
					exit($ajaxRtnContent);
				}
				else {
					$ajaxRtnValue = explode('#', $theRtnArray[1]);

					if (6 < count($ajaxRtnValue)) {
						$ajaxCount = 6;
					}
					else {
						$ajaxCount = count($ajaxRtnValue);
					}

					$_SESSION['ajaxCount'] = $ajaxCount;
					$ajaxRtnValueCate = array();
					$i = 0;

					for (; $i < $ajaxCount; $i++) {
						$j = $i + 1;
						$ajaxRtnValueSign = explode('=', $ajaxRtnValue[$i]);
						$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
						$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
					}

					$SQL = ' SELECT ajaxRtnValue FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
					$S_Row = $DB->queryFirstRow($SQL);

					if ($S_Row['ajaxRtnValue'] == '') {
						$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
						$DB->query($SQL);
					}

					unset($ajaxRtnValue);
					unset($ajaxRtnValueCate);
					exit('true|true');
				}
			}
		}
		else {
			exit('false|EnableQ Security Violation');
		}
	}
}

?>
