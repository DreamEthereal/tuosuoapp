<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
_checkroletype('1|2|3|5|7');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$SQL = ' SELECT surveyID,status,surveyName,isPublic,ajaxRtnValue,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE queryID = \'' . $_GET['queryID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . QUERY_LIST_TABLE . ' WHERE queryID = \'' . $_GET['queryID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_data_source'] . ':' . trim($_GET['queryName']));
	exit();
}

if ($_GET['Action'] == 'DeleteItem') {
	$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE querycondID = \'' . $_GET['querycondID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['dele_data_source_item']);
	_showsucceed($lang['dele_data_source_item'], '?Action=Edit&queryID=' . $_GET['queryID'] . '&surveyID=' . $_GET['surveyID']);
}

if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if ($_POST['Action'] == 'DataEditSubmit') {
	$SQL = ' UPDATE ' . QUERY_LIST_TABLE . ' SET queryName=\'' . trim($_POST['queryName']) . '\',defineShare=\'' . $_POST['defineShare'] . '\' WHERE queryID = \'' . $_POST['queryID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . QUERY_COND_TABLE . ' WHERE queryID = \'' . $_POST['queryID'] . '\' AND fieldsID IN (\'t1\',\'t2\',\'t3\',\'t4\',\'t5\',\'t6\',\'t7\',\'t8\',\'t9\',\'t10\',\'t11\',\'t12\') ';
	$DB->query($SQL);
	$beginTime = explode('-', $_POST['beginTime']);
	$beginJoinTime = mktime($beginTime[3], $beginTime[4], 0, $beginTime[1], $beginTime[2], $beginTime[0]);
	$endTime = explode('-', $_POST['endTime']);
	$endJoinTime = mktime($endTime[3], $endTime[4], 0, $endTime[1], $endTime[2], $endTime[0]);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t1\',queryValue=\'' . $beginJoinTime . ',' . $endJoinTime . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t2\',queryValue=\'' . implode(',', $_POST['overFlag']) . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t12\',queryValue=\'' . implode(',', $_POST['authStat']) . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t3\',queryValue=\'' . $_POST['beginOverTime'] . ',' . $_POST['endOverTime'] . '\' ';
	$DB->query($SQL);

	if (count($_POST['Cates']) != 0) {
		$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t4\',queryValue=\'' . implode(',', $_POST['Cates']) . '\' ';
		$DB->query($SQL);
	}

	if ($_POST['isPublic'] == '0') {
		$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
		$BaseRow = $DB->queryFirstRow($SQL);

		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			if (count($_POST['Members']) != 0) {
				$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t5\',queryValue=\'' . implode(',', $_POST['Members']) . '\' ';
				$DB->query($SQL);
			}

			if ($Sur_G_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$j = $i + 1;
					$k = $i + 6;

					if (count($_POST['ajaxRtnValue_' . $j]) != 0) {
						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t' . $k . '\',queryValue=\'' . implode(',', $_POST['ajaxRtnValue_' . $j]) . '\' ';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '2':
			if ($Sur_G_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$j = $i + 1;
					$k = $i + 6;

					if (count($_POST['ajaxRtnValue_' . $j]) != 0) {
						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $_POST['queryID'] . '\',fieldsID=\'t' . $k . '\',queryValue=\'' . implode(',', $_POST['ajaxRtnValue_' . $j]) . '\' ';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '4':
		case '3':
		case '5':
			break;
		}
	}

	$i = 1;

	for (; $i <= sizeof($_POST['fieldsID']); $i++) {
		if ($_POST['fieldsID'][$i] != '') {
			$surveyID = $_POST['surveyID'];
			$questionID = $_POST['fieldsID'][$i];
			$optionID = $_POST['option_' . $questionID . '_' . $i];
			$opertion = $_POST['opertion_' . $questionID . '_' . $i];
			$queryValue = $_POST['queryValue_' . $questionID . '_' . $i];
			$logicOR = $_POST['cond_' . $questionID . '_' . $i];
			$isRadio = $_POST['isRadio_' . $questionID . '_' . $i];
			$lastQueryID = $_POST['queryID'];
			$questionType = $QtnListArray[$questionID]['questionType'];

			switch ($questionType) {
			case '1':
			case '2':
			case '24':
			case '13':
				if (count($queryValue) != 0) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '4':
			case '12':
				if (trim($queryValue) != '') {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '30':
				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '23':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '31':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$theQueryValue = explode(',', $queryValue);
					$theValidValue = array();

					foreach ($theQueryValue as $theValue) {
						$theValue = trim($theValue);
						if (array_key_exists($theValue, $CascadeArray[$questionID]) && ($CascadeArray[$questionID][$theValue]['level'] == $optionID)) {
							$theValidValue[] = $theValue;
						}
					}

					if (count($theValidValue) != 0) {
						if ($opertion == 1) {
							$logicOR = 0;
						}

						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $theValidValue) . '\',logicOR=\'' . $logicOR . '\' ';
						$DB->query($SQL);
					}
				}

				break;

			case '3':
			case '25':
				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '17':
			case '18':
				$isTrueRadio = 0;

				if ($questionType == '18') {
					if ($isRadio == 0) {
						$isTrueRadio = 1;
					}
				}
				else if ($isRadio == 1) {
					$isTrueRadio = 1;
				}

				if ($isTrueRadio == 1) {
					if ($opertion == 1) {
						$logicOR = 0;
					}
				}

				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\',isRadio=\'' . $isRadio . '\' ';
					$DB->query($SQL);
				}

				break;

			case '6':
			case '19':
				if ((count($queryValue) != 0) && (count($optionID) != 0)) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '7':
			case '28':
				if ((count($queryValue) != 0) && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '10':
			case '15':
			case '20':
			case '21':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '26':
				if ((count($queryValue) != 0) && (count($optionID) != 0) && (count($_POST['label_' . $questionID . '_' . $i]) != 0)) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',labelID = \'' . $_POST['label_' . $questionID . '_' . $i] . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;
			}
		}
	}

	writetolog($lang['edit_data_source'] . ':' . trim($_POST['queryName']));
	_showmessage($lang['edit_data_source'] . ':' . trim($_POST['queryName']), true);
}

if ($_POST['Action'] == 'DataAddSubmit') {
	$SQL = ' INSERT INTO ' . QUERY_LIST_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryName=\'' . trim($_POST['queryName']) . '\',defineShare=\'' . $_POST['defineShare'] . '\' ';
	$DB->query($SQL);
	$lastQueryID = $DB->_GetInsertID();
	$beginTime = explode('-', $_POST['beginTime']);
	$beginJoinTime = mktime($beginTime[3], $beginTime[4], 0, $beginTime[1], $beginTime[2], $beginTime[0]);
	$endTime = explode('-', $_POST['endTime']);
	$endJoinTime = mktime($endTime[3], $endTime[4], 0, $endTime[1], $endTime[2], $endTime[0]);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t1\',queryValue=\'' . $beginJoinTime . ',' . $endJoinTime . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t2\',queryValue=\'' . implode(',', $_POST['overFlag']) . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t12\',queryValue=\'' . implode(',', $_POST['authStat']) . '\' ';
	$DB->query($SQL);
	$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t3\',queryValue=\'' . $_POST['beginOverTime'] . ',' . $_POST['endOverTime'] . '\' ';
	$DB->query($SQL);

	if (count($_POST['Cates']) != 0) {
		$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t4\',queryValue=\'' . implode(',', $_POST['Cates']) . '\' ';
		$DB->query($SQL);
	}

	if ($_POST['isPublic'] == '0') {
		$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
		$BaseRow = $DB->queryFirstRow($SQL);

		switch ($BaseRow['isUseOriPassport']) {
		case '1':
		default:
			if (count($_POST['Members']) != 0) {
				$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t5\',queryValue=\'' . implode(',', $_POST['Members']) . '\' ';
				$DB->query($SQL);
			}

			if ($Sur_G_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$j = $i + 1;
					$k = $i + 6;

					if (count($_POST['ajaxRtnValue_' . $j]) != 0) {
						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t' . $k . '\',queryValue=\'' . implode(',', $_POST['ajaxRtnValue_' . $j]) . '\' ';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '2':
			if ($Sur_G_Row['ajaxRtnValue'] != '') {
				$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

				if (6 < count($ajaxRtnValueName)) {
					$ajaxCount = 6;
				}
				else {
					$ajaxCount = count($ajaxRtnValueName);
				}

				$i = 0;

				for (; $i < $ajaxCount; $i++) {
					$j = $i + 1;
					$k = $i + 6;

					if (count($_POST['ajaxRtnValue_' . $j]) != 0) {
						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'t' . $k . '\',queryValue=\'' . implode(',', $_POST['ajaxRtnValue_' . $j]) . '\' ';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '4':
		case '3':
		case '5':
			break;
		}
	}

	$i = 1;

	for (; $i <= sizeof($_POST['fieldsID']); $i++) {
		if ($_POST['fieldsID'][$i] != '') {
			$surveyID = $_POST['surveyID'];
			$questionID = $_POST['fieldsID'][$i];
			$optionID = $_POST['option_' . $questionID . '_' . $i];
			$opertion = $_POST['opertion_' . $questionID . '_' . $i];
			$queryValue = $_POST['queryValue_' . $questionID . '_' . $i];
			$logicOR = $_POST['cond_' . $questionID . '_' . $i];
			$isRadio = $_POST['isRadio_' . $questionID . '_' . $i];
			$questionType = $QtnListArray[$questionID]['questionType'];

			switch ($questionType) {
			case '1':
			case '2':
			case '24':
			case '13':
				if (count($queryValue) != 0) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '4':
			case '12':
				if (trim($queryValue) != '') {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '30':
				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '23':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '31':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$theQueryValue = explode(',', $queryValue);
					$theValidValue = array();

					foreach ($theQueryValue as $theValue) {
						$theValue = trim($theValue);
						if (array_key_exists($theValue, $CascadeArray[$questionID]) && ($CascadeArray[$questionID][$theValue]['level'] == $optionID)) {
							$theValidValue[] = $theValue;
						}
					}

					if (count($theValidValue) != 0) {
						if ($opertion == 1) {
							$logicOR = 0;
						}

						$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $theValidValue) . '\',logicOR=\'' . $logicOR . '\' ';
						$DB->query($SQL);
					}
				}

				break;

			case '3':
			case '25':
				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '17':
			case '18':
				$isTrueRadio = 0;

				if ($questionType == '18') {
					if ($isRadio == 0) {
						$isTrueRadio = 1;
					}
				}
				else if ($isRadio == 1) {
					$isTrueRadio = 1;
				}

				if ($isTrueRadio == 1) {
					if ($opertion == 1) {
						$logicOR = 0;
					}
				}

				if (count($queryValue) != 0) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\',isRadio=\'' . $isRadio . '\' ';
					$DB->query($SQL);
				}

				break;

			case '6':
			case '19':
				if ((count($queryValue) != 0) && (count($optionID) != 0)) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '7':
			case '28':
				if ((count($queryValue) != 0) && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;

			case '10':
			case '15':
			case '20':
			case '21':
				if ((trim($queryValue) != '') && (count($optionID) != 0)) {
					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',opertion=\'' . $opertion . '\',queryValue=\'' . qaddslashes($queryValue, 1) . '\' ';
					$DB->query($SQL);
				}

				break;

			case '26':
				if ((count($queryValue) != 0) && (count($optionID) != 0) && (count($_POST['label_' . $questionID . '_' . $i]) != 0)) {
					if ($opertion == 1) {
						$logicOR = 0;
					}

					$SQL = ' INSERT INTO ' . QUERY_COND_TABLE . ' SET surveyID =\'' . $surveyID . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',queryID=\'' . $lastQueryID . '\',fieldsID=\'' . $questionID . '\',optionID = \'' . $optionID . '\',labelID = \'' . $_POST['label_' . $questionID . '_' . $i] . '\',opertion=\'' . $opertion . '\',queryValue=\'' . implode(',', $queryValue) . '\',logicOR=\'' . $logicOR . '\' ';
					$DB->query($SQL);
				}

				break;
			}
		}
	}

	writetolog($lang['add_data_source'] . ':' . trim($_POST['queryName']));
	_showmessage($lang['add_data_source'] . ':' . trim($_POST['queryName']), true);
}

if (!isset($_GET['queryID']) || ($_GET['queryID'] == '')) {
	$EnableQCoreClass->setTemplateFile('DataSourceFile', 'DataSourceAdd.html');
	$EnableQCoreClass->replace('queryName', '');
	$EnableQCoreClass->replace('optionOrderID', 1);

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '5':
		$EnableQCoreClass->replace('defineDisabled', '');
		break;

	default:
		$EnableQCoreClass->replace('defineDisabled', 'disabled');
		break;
	}

	$TimeSQL = ' SELECT min(joinTime) as beginTime,max(joinTime) as endTime,min(overTime) as beginOverTime, max(overTime) as endOverTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'];
	$TimeRow = $DB->queryFirstRow($TimeSQL);
	$EnableQCoreClass->replace('beginTime', date('Y-m-d-H-i', $TimeRow['beginTime']));
	$EnableQCoreClass->replace('endTime', date('Y-m-d-H-i', $TimeRow['endTime'] + 60));
	$EnableQCoreClass->replace('beginOverTime', $TimeRow['beginOverTime'] == '' ? 0 : $TimeRow['beginOverTime']);
	$EnableQCoreClass->replace('endOverTime', $TimeRow['endOverTime'] == '' ? 0 : $TimeRow['endOverTime']);
	$HaveSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if ($HaveRow) {
		$SQL = ' SELECT a.cateID,a.cateName FROM ' . SURVEYCATE_TABLE . ' a,' . SURVEYCATELIST_TABLE . ' b WHERE b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.cateID=b.cateID ORDER BY a.cateID DESC ';
		$CateResult = $DB->query($SQL);
		$SurveyCatesList = '';

		while ($CateRow = $DB->queryArray($CateResult)) {
			$SurveyCatesList .= '<option value=\'' . $CateRow['cateID'] . '\'>' . $CateRow['cateName'] . '</option>\\n';
		}

		$EnableQCoreClass->replace('SurveyCatesList', $SurveyCatesList);
		$EnableQCoreClass->replace('haveCate', 'block');
		$EnableQCoreClass->replace('catesList', '');
	}
	else {
		$EnableQCoreClass->replace('haveCate', 'none');
		$EnableQCoreClass->replace('SurveyCatesList', '');
		$EnableQCoreClass->replace('catesList', '');
	}

	$EnableQCoreClass->replace('isPublic', $Sur_G_Row['isPublic']);

	if ($Sur_G_Row['isPublic'] == '0') {
		require 'PrivateCond.inc.php';
		$EnableQCoreClass->replace('havePrivate', '');
	}
	else {
		$EnableQCoreClass->replace('privateQueryCon', '');
		$EnableQCoreClass->replace('havePrivate', 'none');
	}

	$questionList = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (in_array($theQtnArray['questionType'], array(1, 2, 3, 4, 6, 7, 10, 12, 13, 15, 17, 18, 19, 20, 21, 23, 24, 25, 26, 28, 30, 31))) {
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']';
			$questionList .= '<option value=\'' . $questionID . '\'>' . $questionName . '</option>';
		}
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->replace('queryID', '');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('Action', 'DataAddSubmit');
}
else {
	$EnableQCoreClass->setTemplateFile('DataSourceFile', 'DataSourceEdit.html');
	$SQL = ' SELECT * FROM ' . QUERY_LIST_TABLE . ' WHERE queryID = \'' . $_GET['queryID'] . '\' ';
	$QRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('queryName', $QRow['queryName']);
	$EnableQCoreClass->replace('optionOrderID', 1);

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '5':
		$EnableQCoreClass->replace('defineShare' . $QRow['defineShare'], 'checked');
		break;

	default:
		$EnableQCoreClass->replace('defineShare1', 'disabled');
		$EnableQCoreClass->replace('defineShare0', 'checked');
		break;
	}

	$SQL = ' SELECT * FROM ' . QUERY_COND_TABLE . ' WHERE queryID = \'' . $_GET['queryID'] . '\' AND fieldsID IN (\'t1\',\'t2\',\'t3\',\'t4\',\'t5\',\'t6\',\'t7\',\'t8\',\'t9\',\'t10\',\'t11\',\'t12\') ';
	$Result = $DB->query($SQL);
	$optionCond = array();

	while ($CRow = $DB->queryArray($Result)) {
		$optionCond[$CRow['fieldsID']] = $CRow['queryValue'];
	}

	$timeSplit = explode(',', $optionCond['t1']);
	$overSplit = explode(',', $optionCond['t3']);
	$EnableQCoreClass->replace('beginTime', date('Y-m-d-H-i', $timeSplit[0]));
	$EnableQCoreClass->replace('endTime', date('Y-m-d-H-i', $timeSplit[1]));
	$EnableQCoreClass->replace('beginOverTime', $overSplit[0] == '' ? 0 : $overSplit[0]);
	$EnableQCoreClass->replace('endOverTime', $overSplit[1] == '' ? 0 : $overSplit[1]);
	$overSplit = explode(',', $optionCond['t2']);

	foreach ($overSplit as $overFlag) {
		$EnableQCoreClass->replace('overFlag_' . $overFlag, 'checked');
	}

	$statSplit = explode(',', $optionCond['t12']);

	foreach ($statSplit as $authStat) {
		$EnableQCoreClass->replace('authStat_' . $authStat, 'checked');
	}

	$cateSplit = explode(',', $optionCond['t4']);
	$SurveyCatesList = $catesList = '';
	if (($optionCond['t4'] != '') && in_array(0, $cateSplit)) {
		$catesList .= '<option value=\'0\'>' . $lang['survey_no_cate'] . '</option>\\n';
	}
	else {
		$SurveyCatesList .= '<option value=\'0\'>' . $lang['survey_no_cate'] . '</option>\\n';
	}

	$HaveSQL = ' SELECT surveyID FROM ' . SURVEYCATELIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if ($HaveRow) {
		$SQL = ' SELECT a.cateID,a.cateName FROM ' . SURVEYCATE_TABLE . ' a,' . SURVEYCATELIST_TABLE . ' b WHERE b.surveyID=\'' . $_GET['surveyID'] . '\' AND a.cateID=b.cateID ORDER BY a.cateID DESC ';
		$CateResult = $DB->query($SQL);

		while ($CateRow = $DB->queryArray($CateResult)) {
			if (in_array($CateRow['cateID'], $cateSplit)) {
				$catesList .= '<option value=\'' . $CateRow['cateID'] . '\'>' . $CateRow['cateName'] . '</option>\\n';
			}
			else {
				$SurveyCatesList .= '<option value=\'' . $CateRow['cateID'] . '\'>' . $CateRow['cateName'] . '</option>\\n';
			}
		}

		$EnableQCoreClass->replace('SurveyCatesList', $SurveyCatesList);
		$EnableQCoreClass->replace('catesList', $catesList);
		$EnableQCoreClass->replace('haveCate', 'block');
	}
	else {
		$EnableQCoreClass->replace('haveCate', 'none');
		$EnableQCoreClass->replace('SurveyCatesList', '');
		$EnableQCoreClass->replace('catesList', '');
	}

	$EnableQCoreClass->replace('isPublic', $Sur_G_Row['isPublic']);

	if ($Sur_G_Row['isPublic'] == '0') {
		require 'PrivateCond.inc.php';
		$EnableQCoreClass->replace('havePrivate', '');
	}
	else {
		$EnableQCoreClass->replace('privateQueryCon', '');
		$EnableQCoreClass->replace('havePrivate', 'none');
	}

	$EnableQCoreClass->set_CycBlock('DataSourceFile', 'QTN', 'qtn');
	$EnableQCoreClass->replace('qtn', '');
	$SQL = ' SELECT querycondID,fieldsID,optionID,labelID,opertion,queryValue,logicOR,isRadio FROM ' . QUERY_COND_TABLE . ' WHERE queryID = \'' . $_GET['queryID'] . '\' AND fieldsID NOT IN (\'t1\',\'t2\',\'t3\',\'t4\',\'t5\',\'t6\',\'t7\',\'t8\',\'t9\',\'t10\',\'t11\',\'t12\') ORDER BY fieldsID ASC,optionID ASC,labelID ASC ';
	$Result = $DB->query($SQL);

	while ($CRow = $DB->queryArray($Result)) {
		$questionID = $CRow['fieldsID'];
		$theQtnArray = $QtnListArray[$questionID];
		$questionType = $theQtnArray['questionType'];

		switch ($questionType) {
		case '1':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				break;

			case 2:
				$opertion = $lang['unselected'];
				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= $YesNoListArray[$questionID][$theValue]['optionName'];
				}
				else {
					$conList .= $YesNoListArray[$questionID][$theValue]['optionName'] . $lang['or'] . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '2':
		case '24':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($theValue == 0) {
					if ($i == count($theValueArray) - 1) {
						$conList .= qnospecialchar($theQtnArray['otherText']);
					}
					else {
						$conList .= qnospecialchar($theQtnArray['otherText']) . $opertionRelation . '<br/>';
					}
				}
				else if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($RadioListArray[$questionID][$theValue]['optionName']);
				}
				else {
					$conList .= qnospecialchar($RadioListArray[$questionID][$theValue]['optionName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '13':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				$Conn = odbc_connect(trim($theQtnArray['DSNConnect']), trim($theQtnArray['DSNUser']), trim($theQtnArray['DSNPassword']));

				if (!$Conn) {
					$conList = 'Connection Failed:' . trim($theQtnArray['DSNConnect']) . '-' . trim($theQtnArray['DSNUser']) . '-' . trim($theQtnArray['DSNPassword']);
					break;
				}

				$ODBC_Result = odbc_exec($Conn, _getsql($theQtnArray['DSNSQL']));

				if (!$ODBC_Result) {
					$conList = 'Error in SQL:' . trim($theQtnArray['DSNSQL']);
					break;
				}

				while (odbc_fetch_row($ODBC_Result)) {
					$ItemValue = odbc_result($ODBC_Result, 'ItemValue');
					$ItemDisplay = odbc_result($ODBC_Result, 'ItemDisplay');

					if ($ItemValue == $theValue) {
						$optionName = $ItemDisplay;
						continue;
					}
				}

				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($optionName);
				}
				else {
					$conList .= qnospecialchar($optionName) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '4':
		case '12':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);

			switch ($CRow['opertion']) {
			case '1':
				$conList = '等于';
				break;

			case '2':
				$conList = '小于';
				break;

			case '3':
				$conList = '小于等于';
				break;

			case '4':
				$conList = '大于';
				break;

			case '5':
				$conList = '大于等于';
				break;

			case '6':
				$conList = '不等于';
				break;

			case '7':
			default:
				$conList = '包含';
				break;
			}

			$EnableQCoreClass->replace('conList', ' <font color=brown><b>' . $conList . '</b></font><br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '23':
			$questionName = qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($YesNoListArray[$questionID][$CRow['optionID']]['optionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$conList = '';

			switch ($CRow['opertion']) {
			case '1':
				$conList .= ' <font color=brown><b>等于</b></font>';
				break;

			case '6':
				$conList .= ' <font color=brown><b>不等于</b></font>';
				break;

			case '7':
			default:
				$conList .= ' <font color=brown><b>包含</b></font>';
				break;
			}

			$EnableQCoreClass->replace('conList', $conList . '<br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '30':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$queryValue = ($CRow['queryValue'] == 1 ? 'True' : 'False');
			$EnableQCoreClass->replace('conList', ' <font color=brown><b>等于</b></font><br/><font color=red><b>(</b></font><br/>' . $queryValue . '<br/><font color=red><b>)</b></font>');
			break;

		case '31':
			$theUnitText = explode('#', $QtnListArray[$questionID]['unitText']);
			$questionName = qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theUnitText[$CRow['optionID'] - 1]) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$theValueArray = explode(',', $CRow['queryValue']);
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($CascadeArray[$questionID][$theValue]['nodeName']);
				}
				else {
					$conList .= qnospecialchar($CascadeArray[$questionID][$theValue]['nodeName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '3':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				break;

			case 2:
				$opertion = $lang['unselected'];
				break;
			}

			if ($CRow['logicOR'] == 1) {
				$opertionRelation = $lang['and'];
			}
			else {
				$opertionRelation = $lang['or'];
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($theValue == 0) {
					if ($i == count($theValueArray) - 1) {
						$conList .= qnospecialchar($theQtnArray['otherText']);
					}
					else {
						$conList .= qnospecialchar($theQtnArray['otherText']) . $opertionRelation . '<br/>';
					}
				}
				else if ($theValue == 99999) {
					$negText = ($theQtnArray['allowType'] != '' ? $theQtnArray['allowType'] : $lang['neg_text']);

					if ($i == count($theValueArray) - 1) {
						$conList .= qnospecialchar($negText);
					}
					else {
						$conList .= qnospecialchar($negText) . $opertionRelation . '<br/>';
					}
				}
				else if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($CheckBoxListArray[$questionID][$theValue]['optionName']);
				}
				else {
					$conList .= qnospecialchar($CheckBoxListArray[$questionID][$theValue]['optionName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '25':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				break;

			case 2:
				$opertion = $lang['unselected'];
				break;
			}

			if ($CRow['logicOR'] == 1) {
				$opertionRelation = $lang['and'];
			}
			else {
				$opertionRelation = $lang['or'];
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($CheckBoxListArray[$questionID][$theValue]['optionName']);
				}
				else {
					$conList .= qnospecialchar($CheckBoxListArray[$questionID][$theValue]['optionName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '17':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			if ($CRow['isRadio'] == 1) {
				switch ($CRow['opertion']) {
				case 1:
				default:
					$opertion = $lang['selected'];
					$opertionRelation = $lang['or'];
					break;

				case 2:
					$opertion = $lang['unselected'];

					if ($CRow['logicOR'] == 1) {
						$opertionRelation = $lang['and'];
					}
					else {
						$opertionRelation = $lang['or'];
					}

					break;
				}
			}
			else {
				switch ($CRow['opertion']) {
				case 1:
				default:
					$opertion = $lang['selected'];
					break;

				case 2:
					$opertion = $lang['unselected'];
					break;
				}

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($theValue == 0) {
					if ($i == count($theValueArray) - 1) {
						$conList .= qnospecialchar($theBaseQtnArray['otherText']);
					}
					else {
						$conList .= qnospecialchar($theBaseQtnArray['otherText']) . $opertionRelation . '<br/>';
					}
				}
				else if ($theValue == 99999) {
					$negText = ($theQtnArray['allowType'] != '' ? $theQtnArray['allowType'] : $lang['neg_text']);

					if ($i == count($theValueArray) - 1) {
						$conList .= qnospecialchar($negText);
					}
					else {
						$conList .= qnospecialchar($negText) . $opertionRelation . '<br/>';
					}
				}
				else if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($theCheckBoxListArray[$theValue]['optionName']);
				}
				else {
					$conList .= qnospecialchar($theCheckBoxListArray[$theValue]['optionName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '18':
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			if ($CRow['isRadio'] == 0) {
				switch ($CRow['opertion']) {
				case 1:
				default:
					$opertion = $lang['selected'];
					$opertionRelation = $lang['or'];
					break;

				case 2:
					$opertion = $lang['unselected'];

					if ($CRow['logicOR'] == 1) {
						$opertionRelation = $lang['and'];
					}
					else {
						$opertionRelation = $lang['or'];
					}

					break;
				}
			}
			else {
				switch ($CRow['opertion']) {
				case 1:
				default:
					$opertion = $lang['selected'];
					break;

				case 2:
					$opertion = $lang['unselected'];
					break;
				}

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($YesNoListArray[$questionID][$theValue]['optionName']);
				}
				else {
					$conList .= qnospecialchar($YesNoListArray[$questionID][$theValue]['optionName']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '6':
			$questionName = qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($OptionListArray[$questionID][$CRow['optionID']]['optionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']);
				}
				else {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '19':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$optionName = $optionAutoArray[$CRow['optionID']] . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']);
				}
				else {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '7':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$optionName = qnospecialchar($OptionListArray[$questionID][$CRow['optionID']]['optionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				break;

			case 2:
				$opertion = $lang['unselected'];
				break;
			}

			if ($CRow['logicOR'] == 1) {
				$opertionRelation = $lang['and'];
			}
			else {
				$opertionRelation = $lang['or'];
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']);
				}
				else {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '28':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$optionName = $optionAutoArray[$CRow['optionID']] . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				break;

			case 2:
				$opertion = $lang['unselected'];
				break;
			}

			if ($CRow['logicOR'] == 1) {
				$opertionRelation = $lang['and'];
			}
			else {
				$opertionRelation = $lang['or'];
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']);
				}
				else {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;

		case '10':
			$questionName = qnospecialchar($theQtnArray['questionName']);

			if ($CRow['optionID'] == 0) {
				$optionName = qnospecialchar($theQtnArray['otherText']);
			}
			else {
				$optionName = qnospecialchar($RankListArray[$questionID][$CRow['optionID']]['optionName']);
			}

			$optionName .= '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);

			switch ($CRow['opertion']) {
			case '1':
			default:
				$conList = '等于';
				break;

			case '2':
				$conList = '小于';
				break;

			case '3':
				$conList = '小于等于';
				break;

			case '4':
				$conList = '大于';
				break;

			case '5':
				$conList = '大于等于';
				break;

			case '6':
				$conList = '不等于';
				break;
			}

			$EnableQCoreClass->replace('conList', ' <font color=brown><b>' . $conList . '</b></font><br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '20':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$optionName = $optionAutoArray[$CRow['optionID']] . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);

			switch ($CRow['opertion']) {
			case '1':
			default:
				$conList = '等于';
				break;

			case '2':
				$conList = '小于';
				break;

			case '3':
				$conList = '小于等于';
				break;

			case '4':
				$conList = '大于';
				break;

			case '5':
				$conList = '大于等于';
				break;

			case '6':
				$conList = '不等于';
				break;
			}

			$EnableQCoreClass->replace('conList', ' <font color=brown><b>' . $conList . '</b></font><br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '15':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$optionName = qnospecialchar($RankListArray[$questionID][$CRow['optionID']]['optionName']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);

			switch ($CRow['opertion']) {
			case '1':
			default:
				$conList = '等于';
				break;

			case '2':
				$conList = '小于';
				break;

			case '3':
				$conList = '小于等于';
				break;

			case '4':
				$conList = '大于';
				break;

			case '5':
				$conList = '大于等于';
				break;

			case '6':
				$conList = '不等于';
				break;
			}

			$EnableQCoreClass->replace('conList', ' <font color=brown><b>' . $conList . '</b></font><br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '21':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$theBaseID = $QtnListArray[$questionID]['baseID'];
			$theBaseQtnArray = $QtnListArray[$theBaseID];
			$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
			$optionAutoArray = array();

			foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
				$optionAutoArray[$question_checkboxID] = qnospecialchar($theQuestionArray['optionName']);
			}

			if ($theBaseQtnArray['isHaveOther'] == 1) {
				$optionAutoArray[0] = qnospecialchar($theBaseQtnArray['otherText']);
			}

			$optionName = $optionAutoArray[$CRow['optionID']] . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName);

			switch ($CRow['opertion']) {
			case '1':
			default:
				$conList = '等于';
				break;

			case '2':
				$conList = '小于';
				break;

			case '3':
				$conList = '小于等于';
				break;

			case '4':
				$conList = '大于';
				break;

			case '5':
				$conList = '大于等于';
				break;

			case '6':
				$conList = '不等于';
				break;
			}

			$EnableQCoreClass->replace('conList', ' <font color=brown><b>' . $conList . '</b></font><br/><font color=red><b>(</b></font><br/>' . stripslashes($CRow['queryValue']) . '<br/><font color=red><b>)</b></font>');
			break;

		case '26':
			$questionName = qnospecialchar($theQtnArray['questionName']);
			$optionName = qnospecialchar($OptionListArray[$questionID][$CRow['optionID']]['optionName']);
			$labelName = qnospecialchar($LabelListArray[$questionID][$CRow['labelID']]['optionLabel']) . '&nbsp;[' . $lang['question_type_' . $questionType] . ']';
			$EnableQCoreClass->replace('questionName', $questionName . ' - ' . $optionName . ' - ' . $labelName);
			$theValueArray = explode(',', $CRow['queryValue']);
			$conList = '';

			switch ($CRow['opertion']) {
			case 1:
			default:
				$opertion = $lang['selected'];
				$opertionRelation = $lang['or'];
				break;

			case 2:
				$opertion = $lang['unselected'];

				if ($CRow['logicOR'] == 1) {
					$opertionRelation = $lang['and'];
				}
				else {
					$opertionRelation = $lang['or'];
				}

				break;
			}

			$conList .= $opertion . '<br/><font color=red><b>(</b></font><br/>';
			$i = 0;

			foreach ($theValueArray as $theValue) {
				if ($i == count($theValueArray) - 1) {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']);
				}
				else {
					$conList .= qnospecialchar($AnswerListArray[$questionID][$theValue]['optionAnswer']) . $opertionRelation . '<br/>';
				}

				$i++;
			}

			$conList .= '<br/><font color=red><b>)</b></font>';
			$EnableQCoreClass->replace('conList', $conList);
			break;
		}

		$deleteURL = '?Action=DeleteItem&querycondID=' . $CRow['querycondID'] . '&surveyID=' . $_GET['surveyID'] . '&queryID=' . $_GET['queryID'];
		$EnableQCoreClass->replace('deleteURL', $deleteURL);
		$EnableQCoreClass->parse('qtn', 'QTN', true);
	}

	$questionList = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (in_array($theQtnArray['questionType'], array(1, 2, 3, 4, 6, 7, 10, 12, 13, 15, 17, 18, 19, 20, 21, 23, 24, 25, 26, 28, 30, 31))) {
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']';
			$questionList .= '<option value=\'' . $questionID . '\'>' . $questionName . '</option>';
		}
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->replace('queryID', $_GET['queryID']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('Action', 'DataEditSubmit');
}

$EnableQCoreClass->parse('DataSource', 'DataSourceFile');
$EnableQCoreClass->output('DataSource');

?>
