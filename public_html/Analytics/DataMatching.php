<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';

if ($_POST['Action'] == 'DataMatchingSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theSID = $Row['surveyID'];
	if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
	$dataSource = getdatasourcesql($_POST['dataSource'], $_POST['surveyID']);
	$dataSourceId = $_POST['dataSource'];

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	if ($_POST['matchingType'] == 1) {
		$questionTypeArray = array();
		$theOptionNum = array();
		$theAnswerNum = array();
		$theDim = array();
		$theCascadeLevel = array();
		$theDimLabel = 1;

		for (; $theDimLabel <= 2; $theDimLabel++) {
			if ($_POST['questionID' . $theDimLabel] != '') {
				$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID' . $theDimLabel] . '\' ';
				$theSRow = $DB->queryFirstRow($SQL);
				$theSID = $theSRow['surveyID'];
				if (($theSRow['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php')) {
					require ROOT_PATH . 'Includes/MakeCache.php';
				}

				require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
				$theSurveyID = $theSID;
				$questionID = $_POST['questionID' . $theDimLabel];
				$questionTypeArray[$theDimLabel] = $QtnListArray[$questionID]['questionType'];

				switch ($QtnListArray[$questionID]['questionType']) {
				case '1':
					$theOptionNum[$theDimLabel] = count($YesNoListArray[$questionID]);
					break;

				case '2':
					$optionNum = count($RadioListArray[$questionID]);

					if ($QtnListArray[$questionID]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					break;

				case '3':
					$optionNum = count($CheckBoxListArray[$questionID]);

					if ($QtnListArray[$questionID]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					break;

				case '13':
					$Conn = odbc_connect(trim($QtnListArray[$questionID]['DSNConnect']), trim($QtnListArray[$questionID]['DSNUser']), trim($QtnListArray[$questionID]['DSNPassword']));
					$ODBC_Result = odbc_exec($Conn, _getsql($QtnListArray[$questionID]['DSNSQL']));
					$theOptionNum[$theDimLabel] = odbc_num_rows($ODBC_Result);
					break;

				case '17':
					$optionNum = count($CheckBoxListArray[$QtnListArray[$questionID]['baseID']]);

					if ($QtnListArray[$QtnListArray[$questionID]['baseID']]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					break;

				case '18':
					$theOptionNum[$theDimLabel] = count($YesNoListArray[$questionID]);
					break;

				case '24':
					$theOptionNum[$theDimLabel] = count($RadioListArray[$questionID]);
					break;

				case '25':
					$theOptionNum[$theDimLabel] = count($CheckBoxListArray[$questionID]);
					break;

				case '6':
				case '7':
					$theOptionNum[$theDimLabel] = count($OptionListArray[$questionID]);
					$theAnswerNum[$theDimLabel] = count($AnswerListArray[$questionID]);
					break;

				case '10':
					$optionNum = count($RankListArray[$questionID]);

					if ($QtnListArray[$questionID]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					break;

				case '15':
					$theOptionNum[$theDimLabel] = count($RankListArray[$questionID]);
					$theAnswerNum[$theDimLabel] = ($QtnListArray[$questionID]['endScale'] - $QtnListArray[$questionID]['startScale']) + 2;
					break;

				case '19':
				case '28':
					$optionNum = count($CheckBoxListArray[$QtnListArray[$questionID]['baseID']]);

					if ($QtnListArray[$QtnListArray[$questionID]['baseID']]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					$theAnswerNum[$theDimLabel] = count($AnswerListArray[$questionID]);
					break;

				case '20':
					$optionNum = count($CheckBoxListArray[$QtnListArray[$questionID]['baseID']]);

					if ($QtnListArray[$QtnListArray[$questionID]['baseID']]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					break;

				case '21':
					$optionNum = count($CheckBoxListArray[$QtnListArray[$questionID]['baseID']]);

					if ($QtnListArray[$QtnListArray[$questionID]['baseID']]['isHaveOther'] == 1) {
						$optionNum++;
					}

					$theOptionNum[$theDimLabel] = $optionNum;
					$theAnswerNum[$theDimLabel] = ($QtnListArray[$questionID]['endScale'] - $QtnListArray[$questionID]['startScale']) + 2;
					break;

				case '31':
					$theCascadeLevel[$theDimLabel] = $QtnListArray[$questionID]['maxSize'];
					$theOptionNum[$theDimLabel] = count($CascadeArray[$questionID]);
					break;
				}

				$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.diff.inc.php';
			}
		}

		if ($questionTypeArray[1] != $questionTypeArray[2]) {
			exit('false######' . $lang['match_no_same_qtntype']);
		}

		if ($theOptionNum[1] != $theOptionNum[2]) {
			exit('false######' . $lang['match_no_same_optionnum']);
		}

		switch ($questionTypeArray[1]) {
		case '1':
		case '2':
		case '3':
		case '13':
		case '17':
		case '18':
		case '24':
		case '25':
			$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'DataMatchingDiff1D.html');
			$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'DIM', 'dim');
			$EnableQCoreClass->replace('dim', '');
			$EnableQCoreClass->replace('surveyName1', $theDim[1]['dimSName']);
			$EnableQCoreClass->replace('surveyName2', $theDim[2]['dimSName']);
			$EnableQCoreClass->replace('questionName1', $theDim[1]['dimQtnName']);
			$EnableQCoreClass->replace('questionName2', $theDim[2]['dimQtnName']);
			$EnableQCoreClass->replace('dimSum1', $theDim[1]['dimSum']);
			$EnableQCoreClass->replace('dimSum2', $theDim[2]['dimSum']);
			$k = 0;

			for (; $k < count($theDim[1]['dimName']); $k++) {
				$EnableQCoreClass->replace('optionName1', $theDim[1]['dimName'][$k]);
				$EnableQCoreClass->replace('optionName2', $theDim[2]['dimName'][$k]);
				$EnableQCoreClass->replace('dimNum1', $theDim[1]['dimNum'][$k]);
				$EnableQCoreClass->replace('dimNum2', $theDim[2]['dimNum'][$k]);
				$EnableQCoreClass->replace('dimPercent1', $theDim[1]['dimPercent'][$k]);
				$EnableQCoreClass->replace('dimPercent2', $theDim[2]['dimPercent'][$k]);
				$EnableQCoreClass->parse('dim', 'DIM', true);
			}

			unset($theDim);
			$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
			break;

		case '6':
		case '7':
		case '15':
		case '19':
		case '21':
		case '28':
			if ($theAnswerNum[1] != $theAnswerNum[2]) {
				exit('false######' . $lang['match_no_same_answernum']);
			}

			$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'DataMatchingDiff2D.html');
			$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'OPTION', 'option');
			$EnableQCoreClass->set_CycBlock('OPTION', 'DIM', 'dim');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('dim', '');
			$EnableQCoreClass->replace('surveyName1', $theDim[1]['dimSName']);
			$EnableQCoreClass->replace('surveyName2', $theDim[2]['dimSName']);
			$m = 0;

			for (; $m < count($theDim[1]['dimQtnName']); $m++) {
				$EnableQCoreClass->replace('questionName1', $theDim[1]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('questionName2', $theDim[2]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('dimSum1', $theDim[1]['dimSum'][$m]);
				$EnableQCoreClass->replace('dimSum2', $theDim[2]['dimSum'][$m]);
				$n = 0;

				for (; $n < count($theDim[1]['dimName'][$m]); $n++) {
					$EnableQCoreClass->replace('optionName1', $theDim[1]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('optionName2', $theDim[2]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('dimNum1', $theDim[1]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimNum2', $theDim[2]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent1', $theDim[1]['dimPercent'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent2', $theDim[2]['dimPercent'][$m][$n]);
					$EnableQCoreClass->parse('dim', 'DIM', true);
				}

				$EnableQCoreClass->parse('option', 'OPTION', true);
				$EnableQCoreClass->unreplace('dim');
			}

			unset($theDim);
			$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
			break;

		case '31':
			if ($theCascadeLevel[1] != $theCascadeLevel[2]) {
				exit('false######需对比数据的问卷级联问题中级联最多层数的数量不一致');
			}

			$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'DataMatchingDiff2D.html');
			$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'OPTION', 'option');
			$EnableQCoreClass->set_CycBlock('OPTION', 'DIM', 'dim');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('dim', '');
			$EnableQCoreClass->replace('surveyName1', $theDim[1]['dimSName']);
			$EnableQCoreClass->replace('surveyName2', $theDim[2]['dimSName']);
			$m = 0;

			for (; $m < count($theDim[1]['dimQtnName']); $m++) {
				$EnableQCoreClass->replace('questionName1', $theDim[1]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('questionName2', $theDim[2]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('dimSum1', $theDim[1]['dimSum'][$m]);
				$EnableQCoreClass->replace('dimSum2', $theDim[2]['dimSum'][$m]);
				$n = 0;

				for (; $n < count($theDim[1]['dimName'][$m]); $n++) {
					$EnableQCoreClass->replace('optionName1', $theDim[1]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('optionName2', $theDim[2]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('dimNum1', $theDim[1]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimNum2', $theDim[2]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent1', $theDim[1]['dimPercent'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent2', $theDim[2]['dimPercent'][$m][$n]);
					$EnableQCoreClass->parse('dim', 'DIM', true);
				}

				$EnableQCoreClass->parse('option', 'OPTION', true);
				$EnableQCoreClass->unreplace('dim');
			}

			unset($theDim);
			$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
			break;

		case '10':
		case '20':
			$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'DataMatchingDiff2D.html');
			$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'OPTION', 'option');
			$EnableQCoreClass->set_CycBlock('OPTION', 'DIM', 'dim');
			$EnableQCoreClass->replace('option', '');
			$EnableQCoreClass->replace('dim', '');
			$EnableQCoreClass->replace('surveyName1', $theDim[1]['dimSName']);
			$EnableQCoreClass->replace('surveyName2', $theDim[2]['dimSName']);
			$m = 0;

			for (; $m < count($theDim[1]['dimQtnName']); $m++) {
				$EnableQCoreClass->replace('questionName1', $theDim[1]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('questionName2', $theDim[2]['dimQtnName'][$m]);
				$EnableQCoreClass->replace('dimSum1', $theDim[1]['dimSum'][$m]);
				$EnableQCoreClass->replace('dimSum2', $theDim[2]['dimSum'][$m]);
				$n = 0;

				for (; $n < count($theDim[1]['dimName'][$m]); $n++) {
					$EnableQCoreClass->replace('optionName1', $theDim[1]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('optionName2', $theDim[2]['dimName'][$m][$n]);
					$EnableQCoreClass->replace('dimNum1', $theDim[1]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimNum2', $theDim[2]['dimNum'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent1', $theDim[1]['dimPercent'][$m][$n]);
					$EnableQCoreClass->replace('dimPercent2', $theDim[2]['dimPercent'][$m][$n]);
					$EnableQCoreClass->parse('dim', 'DIM', true);
				}

				$EnableQCoreClass->parse('option', 'OPTION', true);
				$EnableQCoreClass->unreplace('dim');
			}

			unset($theDim);
			$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
			break;
		}

		exit('true######' . $DataMatchingHTML);
	}

	if ($_POST['matchingType'] == 2) {
		$theTimeCharText = '';
		$theDimLabel = 1;

		for (; $theDimLabel <= 7; $theDimLabel++) {
			if (($_POST['matchingDate' . $theDimLabel] != '') && ($_POST['matchingEndDate' . $theDimLabel] != '')) {
				$beginTime = explode('-', $_POST['matchingDate' . $theDimLabel]);
				$beginJoinTime = mktime($beginTime[3], $beginTime[4], 0, $beginTime[1], $beginTime[2], $beginTime[0]);
				$endTime = explode('-', $_POST['matchingEndDate' . $theDimLabel]);
				$endJoinTime = mktime($endTime[3], $endTime[4], 0, $endTime[1], $endTime[2], $endTime[0]);
				$theTimeCharText .= $beginJoinTime . '*' . $endJoinTime . '^';
			}
		}

		$theTimeCharText = substr($theTimeCharText, 0, -1);
		$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID6'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$theSID = $Row['surveyID'];
		if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
			require ROOT_PATH . 'Includes/MakeCache.php';
		}

		require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
		$questionID = $_POST['questionID6'];
		$theSurveyID = $_POST['surveyID6'];

		switch ($QtnListArray[$questionID]['questionType']) {
		case '1':
		case '2':
		case '3':
		case '13':
		case '17':
		case '18':
		case '24':
		case '25':
		case '6':
		case '7':
		case '10':
		case '15':
		case '19':
		case '20':
		case '21':
		case '28':
		case '31':
			$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.same.inc.php';
			break;
		}

		exit('true######' . $DataMatchingHTML);
	}
}

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

if ($_GET['Type'] == 1) {
	$EnableQCoreClass->setTemplateFile('DataMatchingFile', 'DataMatchingSame.html');
}
else {
	$EnableQCoreClass->setTemplateFile('DataMatchingFile', 'DataMatchingDiff.html');
}

$theSurveyID = ($_GET['surveyID'] == '' ? 0 : (int) $_GET['surveyID']);
$EnableQCoreClass->replace('surveyID', $theSurveyID);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

switch ($_SESSION['adminRoleType']) {
case '1':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) ORDER BY surveyID DESC ';
	break;

case '2':
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID= \'' . $_SESSION['administratorsID'] . '\' ORDER BY surveyID DESC ';
	break;

case '3':
case '7':
	$AuthSQL = ' SELECT surveyID FROM ' . VIEWUSERLIST_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
	$AuthResult = $DB->query($AuthSQL);
	$SurveyArray = array();

	while ($AuthRow = $DB->queryArray($AuthResult)) {
		$SurveyArray[] = $AuthRow['surveyID'];
	}

	if (!empty($SurveyArray)) {
		$SurveyList = implode(',', $SurveyArray);
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND surveyID IN (' . $SurveyList . ') ORDER BY surveyID DESC ';
	}
	else {
		$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ';
	}

	break;

case '5':
	$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE status IN ( 1,2) AND administratorsID IN (' . $UserIDList . ')';
	break;

default:
	$SQL = ' SELECT surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID=0 ORDER BY surveyID DESC ';
	break;
}

$Result = $DB->query($SQL);
$surveyIDList = '';
$surveyIDList2 = '';

while ($SuRow = $DB->queryArray($Result)) {
	if ($SuRow['surveyID'] == $_GET['surveyID']) {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '" selected>' . $SuRow['surveyTitle'] . '</option>';
	}
	else {
		$surveyIDList .= '<option value="' . $SuRow['surveyID'] . '">' . $SuRow['surveyTitle'] . '</option>';
	}

	$surveyIDList2 .= '<option value="' . $SuRow['surveyID'] . '">' . $SuRow['surveyTitle'] . '</option>';
}

$EnableQCoreClass->replace('surveyIDList', $surveyIDList);
$EnableQCoreClass->replace('surveyIDList2', $surveyIDList2);
$EnableQCoreClass->replace('m_questionID', '');
$EnableQCoreClass->replace('m_questionName', $lang['pls_select']);
$DataMatching = $EnableQCoreClass->parse('DataMatching', 'DataMatchingFile');
echo $DataMatching;
exit();

?>
