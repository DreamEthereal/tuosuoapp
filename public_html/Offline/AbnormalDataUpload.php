<?php
//dezend by http://www.yunlu99.com/
function qconversionstring($string)
{
	$string = strip_tags($string);
	$string = str_replace('"', '""', $string);
	$string = str_replace('&quot;', '""', $string);
	$string = str_replace('&amp;', '&', $string);
	$string = str_replace("\r", '', $string);
	$string = str_replace("\n", '', $string);
	return $string;
}

function qconversionstringtext($string)
{
	$string = qhtmlspecialchars(getdbstring(iconv('UTF-8', 'GBK', $string)));
	return qconversionstring($string);
}

function write_to_file($file_name, $data, $method = 'w')
{
	$_obf_wWFgG_EIcg__ = fopen($file_name, $method);
	flock($_obf_wWFgG_EIcg__, LOCK_EX);
	$_obf_fPX93OEFX6y0 = fwrite($_obf_wWFgG_EIcg__, $data);
	fclose($_obf_wWFgG_EIcg__);
	return $_obf_fPX93OEFX6y0;
}

function preg_match_var($qvar)
{
	global $the_js_file_content;

	if (preg_match('\'var[\\s' . "\r\n" . ']*' . $qvar . '[\\s' . "\r\n" . ']*=[\\s' . "\r\n" . ']*\\{.*?\\};\'si', $the_js_file_content, $_obf_8UmnTppRcA__)) {
		return substr(preg_replace('\'var[\\s' . "\r\n" . ']*' . $qvar . '[\\s' . "\r\n" . ']*=\'si', '', $_obf_8UmnTppRcA__[0]), 0, -1);
	}

	return '{}';
}

define('ROOT_PATH', '../');
include_once ROOT_PATH . 'Config/MMConfig.inc.php';

if ($Config['is_use_mm']) {
	$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
	ini_set('session.save_handler', 'memcache');
	ini_set('session.save_path', $session_save_path);
}

session_start();
set_time_limit(0);
require_once ROOT_PATH . 'Entry/Global.offline.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';

if ($License['isOffline'] != 1) {
	_showerror($lang['license_error'], $lang['license_no_android']);
}

$thisData = php_json_decode(base64_decode(trim($_POST['datas'])));
$thisIndex = array();

foreach ($thisData['columns'] as $thisIndexId => $thisFileds) {
	$thisIndex[$thisFileds] = $thisIndexId;
}

$theSID = $_POST['surveyID'];
$the_js_file_content = file_get_contents(ROOT_PATH . 'PerUserData/tmp/jsondata_' . trim($_POST['fileNameUnit']) . '.js');
$QtnListArray = php_json_decode_main(preg_match_var('QtnListArray'));
$YesNoListArray = php_json_decode_main(preg_match_var('YesNoListArray'));
$RadioListArray = php_json_decode_main(preg_match_var('RadioListArray'));
$CheckBoxListArray = php_json_decode_main(preg_match_var('CheckBoxListArray'));
$InfoListArray = php_json_decode_main(preg_match_var('InfoListArray'));
$AnswerListArray = php_json_decode_main(preg_match_var('AnswerListArray'));
$OptionListArray = php_json_decode_main(preg_match_var('OptionListArray'));
$LabelListArray = php_json_decode_main(preg_match_var('LabelListArray'));
$RankListArray = php_json_decode_main(preg_match_var('RankListArray'));
$content = '';
$header = '"' . $lang['export_joinTime'] . '"';

if ($_POST['isRecord'] != 0) {
	$header .= ',"' . $lang['export_recFile'] . '"';
}

if ($_POST['isFingerDrawing'] == 1) {
	$header .= ',"' . $lang['export_fingerFile'] . '"';
}

$header .= ',"' . $lang['export_overFlag'] . '"';
$header .= ',"' . $lang['export_overTime'] . '"';
$header .= ',"' . $lang['export_ipAddress'] . '"';
$header .= ',"' . $lang['export_area'] . '"';
$header .= ',"' . $lang['export_administratorsName'] . '"';
$this_fields_list = '';
$option_tran_array = array();

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
		$surveyID = $theSID;
		$ModuleName = $Module[$theQtnArray['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.export.header.php';
	}
}

$header .= "\r\n";
$content .= $header;
$this_fields_list = substr($this_fields_list, 0, -1);
$thisSurveyFields = explode('|', $this_fields_list);

foreach ($thisData['rows'] as $theRowsId => $ListRow) {
	$content .= '"' . date('Y-m-d H:i:s', $ListRow[$thisIndex['joinTime']]) . '"';

	if ($_POST['isRecord'] != 0) {
		$theTmpArray = explode('.', $ListRow[$thisIndex['recordFile']]);

		if (count($theTmpArray) == 3) {
			$content .= ',"' . substr($ListRow[$thisIndex['recordFile']], 0, -3) . '"';
		}
		else {
			$content .= ',"' . $ListRow[$thisIndex['recordFile']] . '"';
		}
	}

	if ($_POST['isFingerDrawing'] == 1) {
		$theTmpArray = explode('.', $ListRow[$thisIndex['fingerFile']]);

		if (count($theTmpArray) == 3) {
			$content .= ',"' . substr($ListRow[$thisIndex['fingerFile']], 0, -3) . '"';
		}
		else {
			$content .= ',"' . $ListRow[$thisIndex['fingerFile']] . '"';
		}
	}

	switch ($ListRow[$thisIndex['overFlag']]) {
	case '0':
	default:
		$content .= ',"' . $lang['result_no_all'] . '"';
		break;

	case '1':
		$content .= ',"' . $lang['result_have_all'] . '"';
		break;

	case '2':
		$content .= ',"' . $lang['result_to_quota'] . '"';
		break;

	case '3':
		$content .= ',"' . $lang['result_in_export'] . '"';
		break;
	}

	$content .= ',"' . $ListRow[$thisIndex['overTime']] . '"';
	$content .= ',"' . qconversionstringtext($ListRow[$thisIndex['ipAddress']]) . '"';
	$content .= ',"' . qconversionstringtext($ListRow[$thisIndex['area']]) . '"';
	$content .= ',"' . qconversionstringtext($ListRow[$thisIndex['administratorsName']]) . '"';

	foreach ($thisSurveyFields as $theFields) {
		if (strpos($theFields, '#') === false) {
			$theQtnArray = explode('_', $theFields);
			$theQtnId = $theQtnArray[1];

			switch ($QtnListArray[$theQtnId]['questionType']) {
			case '23':
				$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
				break;

			case '24':
				$theCheckType = $RadioListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
				break;

			case '25':
				$theCheckType = $CheckBoxListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
				break;

			case '27':
			case '29':
				$theCheckType = $LabelListArray[$theQtnId][$theQtnArray[3]]['isCheckType'];
				break;

			default:
				$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
				break;
			}

			switch ($theCheckType) {
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '11':
				if (trim($ListRow[$thisIndex[$theFields]]) != '') {
					$content .= ',"\'' . qconversionstringtext($ListRow[$thisIndex[$theFields]]) . '"';
				}
				else {
					$content .= ',""';
				}

				break;

			default:
				$content .= ',"' . qconversionstringtext($ListRow[$thisIndex[$theFields]]) . '"';
				break;
			}
		}
		else {
			$option_array = explode('#', $theFields);

			switch ($option_array[0]) {
			case '1':
				if ($ListRow[$thisIndex[$option_array[2]]] == '0') {
					$content .= ',""';
				}
				else {
					$content .= ',"' . $option_tran_array[$option_array[1]][$ListRow[$thisIndex[$option_array[2]]]] . '"';
				}

				break;

			case '2':
				if ($ListRow[$thisIndex[$option_array[2]]] == '0') {
					if ($ListRow[$thisIndex[$option_array[3]]] == '') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($option_array[4]) . '"';
					}
				}
				else {
					$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$thisIndex[$option_array[2]]]]) . '"';
				}

				break;

			case '24':
				if ($ListRow[$thisIndex[$option_array[2]]] == 0) {
					$content .= ',""';
				}
				else {
					$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$thisIndex[$option_array[2]]]]) . '"';
				}

				break;

			case '3':
			case '25':
			case '7':
			case '28':
				if ($ListRow[$thisIndex[$option_array[1]]] == '') {
					$content .= ',""';
				}
				else {
					$option_value_array = explode(',', $ListRow[$thisIndex[$option_array[1]]]);

					if (in_array($option_array[2], $option_value_array)) {
						$content .= ',"1"';
					}
					else {
						$content .= ',"0"';
					}

					unset($option_value_array);
				}

				break;

			case '4':
			case '23':
				if ($ListRow[$thisIndex[$option_array[2]]] == '1') {
					$content .= ',"²»Çå³þ"';
				}
				else {
					$theQtnArray = explode('_', $option_array[1]);
					$theQtnId = $theQtnArray[1];

					switch ($option_array[0]) {
					case '4':
						$theCheckType = $QtnListArray[$theQtnId]['isCheckType'];
						break;

					case '23':
						$theCheckType = $YesNoListArray[$theQtnId][$theQtnArray[2]]['isCheckType'];
						break;
					}

					switch ($theCheckType) {
					case '5':
					case '6':
					case '7':
					case '8':
					case '9':
					case '11':
						if (trim($ListRow[$thisIndex[$option_array[1]]]) != '') {
							$content .= ',"\'' . qconversionstringtext($ListRow[$thisIndex[$option_array[1]]]) . '"';
						}
						else {
							$content .= ',""';
						}

						break;

					default:
						$content .= ',"' . qconversionstringtext($ListRow[$thisIndex[$option_array[1]]]) . '"';
						break;
					}
				}

				break;

			case '6':
			case '26':
			case '19':
				if ($ListRow[$thisIndex[$option_array[2]]] == '0') {
					$content .= ',""';
				}
				else {
					$content .= ',"' . qconversionstring($option_tran_array[$option_array[1]][$ListRow[$thisIndex[$option_array[2]]]]) . '"';
				}

				break;

			case '10':
			case '20':
			case '16':
			case '22':
				if ($ListRow[$thisIndex[$option_array[1]]] == '0') {
					$content .= ',""';
				}
				else {
					$content .= ',"' . $ListRow[$thisIndex[$option_array[1]]] . '"';
				}

				break;

			case '11':
				$theTmpArray = explode('.', $ListRow[$thisIndex[$option_array[1]]]);

				if (count($theTmpArray) == 3) {
					$content .= ',"' . substr($ListRow[$thisIndex[$option_array[1]]], 0, -3) . '"';
				}
				else {
					$content .= ',"' . $ListRow[$thisIndex[$option_array[1]]] . '"';
				}

				break;

			case '13':
				$content .= ',""';
				break;

			case '15':
			case '21':
				if ($ListRow[$thisIndex[$option_array[2]]] != 99) {
					if ($ListRow[$thisIndex[$option_array[2]]] == '0') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . ($ListRow[$thisIndex[$option_array[2]]] * $option_array[1]) . '"';
					}
				}
				else {
					$content .= ',"' . $ListRow[$thisIndex[$option_array[2]]] . '"';
				}

				break;

			case '17':
				if ($option_array[1] == 1) {
					if ($ListRow[$thisIndex[$option_array[3]]] == '') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$thisIndex[$option_array[3]]]]) . '"';
					}
				}
				else if ($ListRow[$thisIndex[$option_array[2]]] == '') {
					$content .= ',""';
				}
				else {
					$option_value_array = explode(',', $ListRow[$thisIndex[$option_array[2]]]);

					if (in_array($option_array[3], $option_value_array)) {
						$content .= ',"1"';
					}
					else {
						$content .= ',"0"';
					}
				}

				break;

			case '18':
				if ($option_array[1] == 0) {
					if ($ListRow[$thisIndex[$option_array[3]]] == '') {
						$content .= ',""';
					}
					else {
						$content .= ',"' . qconversionstring($option_tran_array[$option_array[2]][$ListRow[$thisIndex[$option_array[3]]]]) . '"';
					}
				}
				else if ($ListRow[$thisIndex[$option_array[2]]] == '') {
					$content .= ',""';
				}
				else {
					$option_value_array = explode(',', $ListRow[$thisIndex[$option_array[2]]]);

					if (in_array($option_array[3], $option_value_array)) {
						$content .= ',"1"';
					}
					else {
						$content .= ',"0"';
					}
				}

				break;
			}
		}
	}

	$content .= "\r\n";
}

unset($option_tran_array);
write_to_file(ROOT_PATH . 'PerUserData/tmp/Result_' . trim($_POST['fileNameUnit']) . '_Abnormal_List.csv', $content);
echo 'true';
exit();

?>
