<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.escape.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$SQL = ' SELECT status,beginTime,endTime,isPublic,surveyName,ajaxRtnValue,mainShowQtn,isCache,surveyID,forbidViewId,projectType,projectOwner,indexVersion,indexTime,indexAdminId,isRateIndex,custDataPath FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

$thisProg = 'SurveyIndexResult.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('thisURL', $thisProg);
if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

if ($_POST['Action'] == 'DataRankSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'IndexDataRank.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'DIM', 'dim');
	$EnableQCoreClass->replace('dim', '');
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND fatherId =0 ORDER BY indexID ASC ';
	$iResult = $DB->query($SQL);
	$surveyIndexName = array();
	$tmp = 0;
	$indexNameList = '';
	$indexName = '�ܷ�';
	$theTwoLevelIndex = array();

	while ($iRow = $DB->queryArray($iResult)) {
		$surveyIndexName[$tmp] = $iRow['indexID'];
		$tmp++;
		$indexNameList .= '<td align=center bgcolor=#cf1100 align=center><b>' . $iRow['indexName'] . '</b></td>';

		if ($iRow['indexID'] == $_POST['indexID']) {
			$indexName = $iRow['indexName'];
		}

		$sSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$theTwoLevelIndex[] = $sRow['indexID'];
			$surveyIndexName[$tmp] = $sRow['indexID'];
			$tmp++;
			$indexNameList .= '<td align=center bgcolor=#cf1100 align=center><b>' . $sRow['indexName'] . '</b></td>';

			if ($sRow['indexID'] == $_POST['indexID']) {
				$indexName = $sRow['indexName'];
			}
		}
	}

	$EnableQCoreClass->replace('indexNameList', $indexNameList);
	$theRankUserGroupID = $_POST['userGroupID'];
	$gSQL = ' SELECT absPath,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $theRankUserGroupID . '\' ';
	$gRow = $DB->queryFirstRow($gSQL);
	$nodesName = _getnodeallname($gRow['absPath'], $gRow['userGroupName'], $gRow['groupType']);
	$userGroupLabel = iconv('UTF-8', 'gbk', $_POST['userGroupLabel']);
	$EnableQCoreClass->replace('userGroupLabel', $userGroupLabel);
	$EnableQCoreClass->replace('indexName', $indexName);
	$EnableQCoreClass->replace('userGroupRoot', $nodesName);
	$SQL = ' SELECT indexID,indexValue,taskID FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ORDER BY taskID ASC,indexID ASC ';
	$rResult = $DB->query($SQL);
	$theIndexValue = array();

	while ($rRow = $DB->queryArray($rResult)) {
		$theIndexValue[$rRow['taskID']][$rRow['indexID']] = $rRow['indexValue'];
	}

	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Sur_G_Row['projectOwner'] . '-%\' OR userGroupID = \'' . $Sur_G_Row['projectOwner'] . '\' )';
	$SQL = ' SELECT userGroupID,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND userGroupLabel = \'' . $userGroupLabel . '\' ORDER BY absPath ASC ';
	$Result = $DB->query($SQL);
	$totalGrade = array();
	$indexGrade = array();
	$theLeafArray = array();

	while ($Row = $DB->queryArray($Result)) {
		if ($Row['isLeaf'] == 1) {
			$theLeafArray[] = $Row['userGroupID'];
			$totalGrade[$Row['userGroupID']] = $theIndexValue[$Row['userGroupID']][0];

			foreach ($surveyIndexName as $indexID) {
				$indexGrade[$indexID][$Row['userGroupID']] = $theIndexValue[$Row['userGroupID']][$indexID];
			}
		}
		else {
			$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR userGroupID = \'' . $Row['userGroupID'] . '\' )';
			$sSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY absPath ASC ';
			$sResult = $DB->query($sSQL);
			$theTaskArray = array();
			$totalGradeTotal = array();
			$indexGradeTotal = array();
			$theIndexNA = array();

			while ($sRow = $DB->queryArray($sResult)) {
				$theTaskArray[] = $sRow['userGroupID'];
				$totalGradeTotal[$Row['userGroupID']] += $theIndexValue[$sRow['userGroupID']][0];

				foreach ($surveyIndexName as $indexID) {
					if ($theIndexValue[$sRow['userGroupID']][$indexID] == '-999') {
						$theIndexNA[$indexID][$Row['userGroupID']] += 1;
					}
					else {
						$indexGradeTotal[$indexID][$Row['userGroupID']] += $theIndexValue[$sRow['userGroupID']][$indexID];
					}
				}
			}

			$hSQL = ' SELECT COUNT(*) as theDataNum FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND taskID IN(' . implode(',', $theTaskArray) . ') AND indexID =0 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$totalGrade[$Row['userGroupID']] = $hRow['theDataNum'] == 0 ? 0 : number_format($totalGradeTotal[$Row['userGroupID']] / $hRow['theDataNum'], 2);

			foreach ($surveyIndexName as $indexID) {
				$validLeaf = $hRow['theDataNum'] - $theIndexNA[$indexID][$Row['userGroupID']];
				$indexGrade[$indexID][$Row['userGroupID']] = $validLeaf == 0 ? 0 : number_format($indexGradeTotal[$indexID][$Row['userGroupID']] / $validLeaf, 2);
			}

			unset($theTaskArray);
			unset($totalGradeTotal);
			unset($indexGradeTotal);
		}
	}

	unset($theIndexValue);

	if ($_POST['indexID'] == 0) {
		arsort($totalGrade, SORT_NUMERIC);
	}
	else {
		arsort($indexGrade[$_POST['indexID']], SORT_NUMERIC);
	}

	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $theRankUserGroupID . '-%\' OR userGroupID = \'' . $theRankUserGroupID . '\' )';
	$SQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' AND userGroupLabel = \'' . $userGroupLabel . '\' ORDER BY absPath ASC ';
	$Result = $DB->query($SQL);
	$theRankInId = array();

	while ($Row = $DB->queryArray($Result)) {
		$theRankInId[] = $Row['userGroupID'];
	}

	if ($_POST['indexID'] == 0) {
		$totalRankNum = 1;
		$theRankNum = 1;

		foreach ($totalGrade as $userGroupID => $theTotalGrade) {
			if (in_array($userGroupID, $theRankInId)) {
				$EnableQCoreClass->replace('nowRank', $theRankNum);
				$EnableQCoreClass->replace('totalRank', $totalRankNum);
				$SQL = ' SELECT userGroupName,groupType,absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID =\'' . $userGroupID . '\' ';
				$Row = $DB->queryFirstRow($SQL);
				$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);
				$EnableQCoreClass->replace('userGroupName', $nodesName);
				if (($Sur_G_Row['isRateIndex'] == 1) && ($theTotalGrade != '')) {
					$theTotalGrade .= '%';
				}

				$EnableQCoreClass->replace('surveyGrade', $theTotalGrade);
				$indexValueList = '';

				foreach ($surveyIndexName as $indexID) {
					if ($indexGrade[$indexID][$userGroupID] != '-999') {
						$thisIndexValue = $indexGrade[$indexID][$userGroupID];
						if (($Sur_G_Row['isRateIndex'] == 1) && ($thisIndexValue != '')) {
							$thisIndexValue .= '%';
						}
					}
					else {
						$thisIndexValue = 'NA';
					}

					if (in_array($indexID, $theTwoLevelIndex) && in_array($userGroupID, $theLeafArray)) {
						$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;<a href="javascript:void(0)" onclick="javascript:showPopWin(\'' . $thisProg . '&Does=ViewIndexDetail&indexID=' . $indexID . '&taskID=' . $userGroupID . '\', 860, 470, null, null,\'�鿴��Ӧָ��������ϸ\')">' . $thisIndexValue . '</a></b></td>';
					}
					else {
						$indexValueList .= '<td align=center valign=center width=70 align=center><b>' . $thisIndexValue . '</b></td>';
					}
				}

				$EnableQCoreClass->replace('indexValueList', $indexValueList);
				$EnableQCoreClass->parse('dim', 'DIM', true);

				if ($theRankNum == 100) {
					break;
				}

				$theRankNum++;
			}

			$totalRankNum++;
		}
	}
	else {
		$totalRankNum = 1;
		$theRankNum = 1;

		foreach ($indexGrade[$_POST['indexID']] as $userGroupID => $thisGrade) {
			if (in_array($userGroupID, $theRankInId)) {
				$EnableQCoreClass->replace('nowRank', $theRankNum);
				$EnableQCoreClass->replace('totalRank', $totalRankNum);
				$SQL = ' SELECT userGroupName,groupType,absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID =\'' . $userGroupID . '\' ';
				$Row = $DB->queryFirstRow($SQL);
				$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);
				$EnableQCoreClass->replace('userGroupName', $nodesName);
				$theTotalGrade = $totalGrade[$userGroupID];
				if (($Sur_G_Row['isRateIndex'] == 1) && ($theTotalGrade != '')) {
					$theTotalGrade .= '%';
				}

				$EnableQCoreClass->replace('surveyGrade', $theTotalGrade);
				$indexValueList = '';

				foreach ($surveyIndexName as $indexID) {
					if ($indexGrade[$indexID][$userGroupID] != '-999') {
						$thisIndexValue = $indexGrade[$indexID][$userGroupID];
						if (($Sur_G_Row['isRateIndex'] == 1) && ($thisIndexValue != '')) {
							$thisIndexValue .= '%';
						}
					}
					else {
						$thisIndexValue = 'NA';
					}

					if (in_array($indexID, $theTwoLevelIndex) && in_array($userGroupID, $theLeafArray)) {
						$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;<a href="javascript:void(0)" onclick="javascript:showPopWin(\'' . $thisProg . '&Does=ViewIndexDetail&indexID=' . $indexID . '&taskID=' . $userGroupID . '\', 860, 470, null, null,\'�鿴��Ӧָ��������ϸ\')">' . $thisIndexValue . '</a></b></td>';
					}
					else {
						$indexValueList .= '<td align=center valign=center width=70 align=center><b>' . $thisIndexValue . '</b></td>';
					}
				}

				$EnableQCoreClass->replace('indexValueList', $indexValueList);
				$EnableQCoreClass->parse('dim', 'DIM', true);

				if ($theRankNum == 100) {
					break;
				}

				$theRankNum++;
			}

			$totalRankNum++;
		}
	}

	unset($surveyIndexName);
	unset($theRankInId);
	unset($totalGrade);
	unset($indexGrade);
	$DataRankHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
	exit('true######' . $DataRankHTML);
}

if ($_GET['Does'] == 'Rank') {
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'IndexResultRank.html');
	$EnableQCoreClass->replace('projectOwner', $Sur_G_Row['projectOwner']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
	$iResult = $DB->query($SQL);
	$index_name_list = '';

	while ($iRow = $DB->queryArray($iResult)) {
		$index_name_list .= '<option value=\'' . $iRow['indexID'] . '\'>' . qnohtmltag($iRow['indexName'], 1) . '</option>' . "\n" . '';
		$sSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$index_name_list .= '<option value=\'' . $sRow['indexID'] . '\'>&nbsp;&nbsp;-&nbsp;&nbsp;' . qnohtmltag($sRow['indexName'], 1) . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('index_name_list', $index_name_list);
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Sur_G_Row['projectOwner'] . '-%\' OR userGroupID = \'' . $Sur_G_Row['projectOwner'] . '\') ';
	$lSQL = ' SELECT DISTINCT userGroupLabel FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY absPath ASC ';
	$lResult = $DB->query($lSQL);
	$userGroupLabel_list = '';

	while ($lRow = $DB->queryArray($lResult)) {
		$userGroupLabel = str_replace('"', '', trim($lRow['userGroupLabel']));

		if ($userGroupLabel != '') {
			$userGroupLabel_list .= '<option value=\'' . $userGroupLabel . '\'>' . $userGroupLabel . '</option>';
		}
	}

	$EnableQCoreClass->replace('userGroupLabel_list', $userGroupLabel_list);

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '7':
		$rootUserId = $Sur_G_Row['projectOwner'];
		$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
		$uRow = $DB->queryFirstRow($uSQL);
		$viewFlag = 1;
		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case 1:
			$rootUserId = $Sur_G_Row['projectOwner'];
			$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);
			$viewFlag = 1;
			break;

		case 2:
			$rootUserId = $_SESSION['adminRoleGroupID'];
			$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);

			if ($uRow['isLeaf'] == 1) {
				$viewFlag = 2;
			}
			else {
				$viewFlag = 1;
			}

			break;
		}

		break;
	}

	$bz_userGroupID_List = '<option value=\'' . $uRow['userGroupID'] . '\'>' . qnohtmltag($uRow['userGroupName'], 1) . '</option>';

	if ($viewFlag == 1) {
		$SQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND fatherId = \'' . $rootUserId . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$bz_userGroupID_List .= '<option value=\'' . $Row['userGroupID'] . '\'>' . qnohtmltag($Row['userGroupName'], 1) . '</option>';
		}
	}

	$EnableQCoreClass->replace('bz_userGroupID_List', $bz_userGroupID_List);
	$EnableQCoreClass->parse('ResultList', 'ResultListFile');
	$EnableQCoreClass->output('ResultList');
}

if ($_GET['Does'] == 'ViewIndexDetail') {
	include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
	$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'IndexDataDetail.html');

	switch ($_SESSION['adminRoleType']) {
	case '3':
		$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);

		if (in_array('t1', $forbidViewIdValue)) {
			$EnableQCoreClass->replace('t1_show', 'none');
		}
		else {
			$EnableQCoreClass->replace('t1_show', '');
		}

		if (in_array('t2', $forbidViewIdValue)) {
			$EnableQCoreClass->replace('t2_show', 'none');
			$isViewPanelInfo = false;
		}
		else {
			$EnableQCoreClass->replace('t2_show', '');
		}

		break;

	default:
		$EnableQCoreClass->replace('t1_show', '');
		$EnableQCoreClass->replace('t2_show', '');
		break;
	}

	$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE taskID=\'' . $_GET['taskID'] . '\' ';
	$R_Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
	$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName']);
	$EnableQCoreClass->replace('ipAddress', $R_Row['ipAddress']);
	$EnableQCoreClass->replace('area', $R_Row['area']);
	$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $R_Row['joinTime']));
	$EnableQCoreClass->replace('submitTime', $R_Row['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['submitTime']));
	$EnableQCoreClass->replace('uploadTime', $R_Row['uploadTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['uploadTime']));
	$EnableQCoreClass->replace('overTime', sectotime($R_Row['overTime']));

	switch ($R_Row['overFlag']) {
	case '0':
	default:
		$EnableQCoreClass->replace('overFlag', $lang['result_no_all']);
		break;

	case '1':
		$EnableQCoreClass->replace('overFlag', $lang['result_have_all']);
		break;

	case '2':
		$EnableQCoreClass->replace('overFlag', $lang['result_to_quota']);
		break;

	case '3':
		$EnableQCoreClass->replace('overFlag', $lang['result_in_export']);
		break;
	}

	switch ($R_Row['dataSource']) {
	case '0':
	default:
		$dataForm = 'δ֪������Դ';
		break;

	case '1':
		$dataForm = 'PC�����';
		break;

	case '2':
		$dataForm = '�ƶ������';
		break;

	case '3':
		$dataForm = '��׿����App';
		break;

	case '4':
		$dataForm = 'PC��Ա¼��';
		break;

	case '5':
		$dataForm = '���߷�ԱApp';
		break;

	case '6':
		$dataForm = '���߷�ԱApp';
		break;

	case '7':
		$dataForm = 'Excel���ݵ���';
		break;

	case '8':
		$dataForm = '�ʾ�����Ǩ��';
		break;
	}

	if ($R_Row['uniDataCode'] != '') {
		$this_uniDataCode = explode('######', base64_decode($R_Row['uniDataCode']));
		$EnableQCoreClass->replace('uniDataCode', $this_uniDataCode[0] . ' (' . $dataForm . ')');
	}
	else {
		$EnableQCoreClass->replace('uniDataCode', $dataForm);
	}

	$SQL = ' SELECT indexID,indexName,indexDesc FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND indexID = \'' . $_GET['indexID'] . '\'';
	$iRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('indexName', $iRow['indexName']);
	$EnableQCoreClass->replace('indexDesc', $iRow['indexDesc']);
	$cSQL = ' SELECT indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND indexID = \'' . $_GET['indexID'] . '\' AND responseID = \'' . $R_Row['responseID'] . '\' ';
	$cRow = $DB->queryFirstRow($cSQL);

	if ($Sur_G_Row['isRateIndex'] == 1) {
		$indexCoeff = '�ϸ��ʣ�';

		if ($cRow['indexValue'] == '-999') {
			$indexCoeff .= 'NA';
		}
		else {
			$indexCoeff .= $cRow['indexValue'];

			if ($cRow['indexValue'] != '') {
				$indexCoeff .= '%';
			}
		}
	}
	else {
		$indexCoeff = '�÷֣�';
		$indexCoeff .= ($cRow['indexValue'] == '-999' ? 'NA' : $cRow['indexValue']);
	}

	$EnableQCoreClass->replace('indexCoeff', $indexCoeff);
	$isHaveViewCoeff = 1;
	$EnableQCoreClass->set_CycBlock('ResultDetailFile', 'QUESTION', 'question');
	$EnableQCoreClass->replace('question', '');
	$SQL = ' SELECT a.questionID FROM ' . SURVEYINDEXLIST_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.indexID = \'' . $_GET['indexID'] . '\' AND a.questionID = b.questionID ORDER BY b.orderByID ASC ';
	$qResult = $DB->query($SQL);

	while ($qRow = $DB->queryArray($qResult)) {
		$surveyID = $_GET['surveyID'];
		$questionID = $qRow['questionID'];
		$theQtnArray = $QtnListArray[$questionID];
		$joinTime = $R_Row['joinTime'];
		$ModuleName = $Module[$theQtnArray['questionType']];

		if ($theQtnArray['questionType'] != '8') {
			switch ($_SESSION['adminRoleType']) {
			case '3':
				if (!in_array($questionID, $forbidViewIdValue)) {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				}

				break;

			default:
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				break;
			}

			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}

	$EnableQCoreClass->parse('ResultDetail', 'ResultDetailFile');
	$EnableQCoreClass->output('ResultDetail');
}

if ($_POST['Action'] == 'DataMatchingSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$EnableQCoreClass->setTemplateFile('ShowOptionFile', 'IndexDataMatching.html');
	$EnableQCoreClass->set_CycBlock('ShowOptionFile', 'DIM', 'dim');
	$EnableQCoreClass->replace('dim', '');
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
	$iResult = $DB->query($SQL);
	$surveyIndexName = array();
	$tmp = 0;
	$indexNameList = '';
	$theTwoLevelIndex = array();

	while ($iRow = $DB->queryArray($iResult)) {
		$surveyIndexName[$tmp] = $iRow['indexID'];
		$tmp++;
		$indexNameList .= '<td align=center bgcolor=#cf1100><b>' . $iRow['indexName'] . '</b></td>';
		$sSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$surveyIndexName[$tmp] = $sRow['indexID'];
			$tmp++;
			$theTwoLevelIndex[] = $sRow['indexID'];
			$indexNameList .= '<td align=center bgcolor=#cf1100><b>' . $sRow['indexName'] . '</b></td>';
		}
	}

	$EnableQCoreClass->replace('indexNameList', $indexNameList);
	$SQL = ' SELECT absPath,userGroupID,userGroupName,isLeaf,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['t_userGroupID_1'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	require 'IndexDataMatching.inc.php';
	$SQL = ' SELECT absPath,userGroupID,userGroupName,isLeaf,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['t_userGroupID_2'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	require 'IndexDataMatching.inc.php';
	if (isset($_POST['t_userGroupID_3']) && ($_POST['t_userGroupID_3'] != '')) {
		$SQL = ' SELECT absPath,userGroupID,userGroupName,isLeaf,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['t_userGroupID_3'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		require 'IndexDataMatching.inc.php';
	}

	if (isset($_POST['t_userGroupID_4']) && ($_POST['t_userGroupID_4'] != '')) {
		$SQL = ' SELECT absPath,userGroupID,userGroupName,isLeaf,groupType FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_POST['t_userGroupID_4'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		require 'IndexDataMatching.inc.php';
	}

	$DataMatchingHTML = $EnableQCoreClass->parse('ShowOption', 'ShowOptionFile');
	exit('true######' . $DataMatchingHTML);
}

if ($_GET['Does'] == 'Match') {
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'IndexResultMatch.html');
	$EnableQCoreClass->replace('projectOwner', $Sur_G_Row['projectOwner']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '7':
		$rootUserId = $Sur_G_Row['projectOwner'];
		$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
		$uRow = $DB->queryFirstRow($uSQL);
		$viewFlag = 1;
		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case 1:
			$rootUserId = $Sur_G_Row['projectOwner'];
			$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);
			$viewFlag = 1;
			break;

		case 2:
			$rootUserId = $_SESSION['adminRoleGroupID'];
			$uSQL = ' SELECT userGroupID,userGroupName,isLeaf FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND userGroupID = \'' . $rootUserId . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);

			if ($uRow['isLeaf'] == 1) {
				$viewFlag = 2;
			}
			else {
				$viewFlag = 1;
			}

			break;
		}

		break;
	}

	$bz_userGroupID_List = '<option value=\'' . $uRow['userGroupID'] . '\'>' . qnohtmltag($uRow['userGroupName'], 1) . '</option>';

	if ($viewFlag == 1) {
		$SQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND fatherId = \'' . $rootUserId . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$bz_userGroupID_List .= '<option value=\'' . $Row['userGroupID'] . '\'>' . qnohtmltag($Row['userGroupName'], 1) . '</option>';
		}
	}

	$EnableQCoreClass->replace('bz_userGroupID_List', $bz_userGroupID_List);
	$EnableQCoreClass->parse('ResultList', 'ResultListFile');
	$EnableQCoreClass->output('ResultList');
}

if ($_GET['Does'] == 'View') {
	@set_time_limit(0);

	if ($Sur_G_Row['projectType'] == 1) {
		$EnableQCoreClass->replace('isProjectType1', '');
	}
	else {
		$EnableQCoreClass->replace('isProjectType1', 'none');
	}

	$isHaveViewCoeff = 1;
	require 'ViewIndexData.php';
}

if ($_GET['Does'] == 'List') {
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'IndexResultList.html');
	$EnableQCoreClass->set_CycBlock('ResultListFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$ConfigRow['topicNum'] = 60;

	if ($Sur_G_Row['projectType'] == 1) {
		$EnableQCoreClass->replace('isProjectType1', '');
		$EnableQCoreClass->replace('isProjectType2', 'none');
	}
	else {
		$EnableQCoreClass->replace('isProjectType1', 'none');
		$EnableQCoreClass->replace('isProjectType2', 'none');
	}

	$EnableQCoreClass->replace('projectOwner', $Sur_G_Row['projectOwner']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId =0 ORDER BY indexID ASC ';
	$iResult = $DB->query($SQL);
	$surveyIndexName = array();
	$tmp = 0;
	$indexNameList = '';

	while ($iRow = $DB->queryArray($iResult)) {
		$surveyIndexName[$tmp] = $iRow['indexID'];
		$tmp++;
		$indexNameList .= '<th class=\'classtd\' width=5% align=center nowrap><b>' . $iRow['indexName'] . '</b></th>';
		$sSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$surveyIndexName[$tmp] = $sRow['indexID'];
			$tmp++;
			$indexNameList .= '<th class=\'classtd\' width=5% align=center nowrap><b>' . $sRow['indexName'] . '</b></th>';
		}
	}

	$EnableQCoreClass->replace('indexNameList', $indexNameList);
	$SQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ORDER BY area ASC ';
	$Result = $DB->query($SQL);
	$areaList = '';

	while ($AreaRow = $DB->queryArray($Result)) {
		$ResultArea = ($AreaRow['area'] == '' ? $lang['unknow_area'] : $AreaRow['area']);
		$areaList .= '<option value="' . $AreaRow['area'] . '">' . $ResultArea . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('area_list', $areaList);
	$EnableQCoreClass->replace('t_name', '');
	$EnableQCoreClass->replace('t_responseID', '');
	$EnableQCoreClass->replace('t_userGroupID', '\'\'');
	$SQL = ' SELECT responseID,taskID,administratorsName,ipAddress,joinTime,area,overFlag,authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b ';

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
	}

	if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
		$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
	}
	else {
		$dataSource = getdatasourcesql(0, $_GET['surveyID']);
	}

	$SQL .= ' WHERE overFlag!=2 AND ' . $dataSource;

	if ($_POST['Action'] == 'querySubmit') {

		//echo "asdf";die();
		$page_others = '';

		if (trim($_POST['t_name']) != '') {
			$t_name = trim($_POST['t_name']);
			$SQL .= ' AND ( b.administratorsName LIKE BINARY \'%' . $t_name . '%\' OR b.ipAddress LIKE BINARY \'%' . $t_name . '%\') ';
			$page_others .= '&t_name=' . urlencode($t_name);
			$EnableQCoreClass->replace('t_name', $t_name);
		}

		if (trim($_POST['t_responseID']) != '') {
			$t_responseID = trim($_POST['t_responseID']);
			$SQL .= ' AND b.responseID = \'' . $t_responseID . '\' ';
			$page_others .= '&t_responseID=' . urlencode($t_responseID);
			$EnableQCoreClass->replace('t_responseID', $t_responseID);
		}

		if ($_POST['area'] != '') {
			$SQL .= ' AND b.area = \'' . $_POST['area'] . '\' ';
			$SearchSQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ORDER BY area ASC ';
			$Result = $DB->query($SearchSQL);
			$areaList = '';

			while ($AreaRow = $DB->queryArray($Result)) {
				$ResultArea = ($AreaRow['area'] == '' ? $lang['unknow_area'] : $AreaRow['area']);

				if ($_POST['area'] == $AreaRow['area']) {
					$areaList .= '<option value="' . $AreaRow['area'] . '" selected>' . $ResultArea . '</option>' . "\n" . '';
				}
				else {
					$areaList .= '<option value="' . $AreaRow['area'] . '">' . $ResultArea . '</option>' . "\n" . '';
				}
			}

			$EnableQCoreClass->replace('area_list', $areaList);
			$page_others .= '&area=' . $_POST['area'];
		}

		if (($_POST['t_userGroupID'] != '') && ($Sur_G_Row['projectType'] == '1')) {
			$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_POST['t_userGroupID'] . '-%\' OR userGroupID = \'' . $_POST['t_userGroupID'] . '\') ';
			$cSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY userGroupID ASC ';
			$cResult = $DB->query($cSQL);
			$theTaskArray = array();

			while ($cRow = $DB->queryArray($cResult)) {
				$theTaskArray[] = $cRow['userGroupID'];
			}

			if (count($theTaskArray) == 0) {
				$SQL .= ' AND b.responseID=0 ';
			}
			else {
				$SQL .= ' AND b.taskID IN (' . implode(',', $theTaskArray) . ') ';
			}

			$page_others .= '&t_userGroupID=' . $_POST['t_userGroupID'];
			$EnableQCoreClass->replace('t_userGroupID', $_POST['t_userGroupID']);
		}
	}

	if (isset($_GET['t_name']) && !$_POST['Action'] && ($_GET['t_name'] != '')) {
		$t_name = trim($_GET['t_name']);
		$SQL .= ' AND ( b.administratorsName LIKE BINARY \'%' . $t_name . '%\' OR b.ipAddress LIKE BINARY \'%' . $t_name . '%\') ';
		$page_others .= '&t_name=' . urlencode($t_name);
		$EnableQCoreClass->replace('t_name', $t_name);
	}

	if (isset($_GET['t_responseID']) && !$_POST['Action'] && ($_GET['t_responseID'] != '')) {
		$t_responseID = trim($_GET['t_responseID']);
		$SQL .= ' AND b.responseID = \'' . $t_responseID . '\' ';
		$page_others .= '&t_responseID=' . urlencode($t_responseID);
		$EnableQCoreClass->replace('t_responseID', $t_responseID);
	}

	if (isset($_GET['area']) && ($_GET['area'] != '') && !$_POST['Action']) {
		$SQL .= ' AND b.area = \'' . $_GET['area'] . '\' ';
		$SearchSQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ORDER BY area ASC ';
		$Result = $DB->query($SearchSQL);
		$areaList = '';

		while ($AreaRow = $DB->queryArray($Result)) {
			$ResultArea = ($AreaRow['area'] == '' ? $lang['unknow_area'] : $AreaRow['area']);

			if ($_GET['area'] == $AreaRow['area']) {
				$areaList .= '<option value="' . $AreaRow['area'] . '" selected>' . $ResultArea . '</option>' . "\n" . '';
			}
			else {
				$areaList .= '<option value="' . $AreaRow['area'] . '">' . $ResultArea . '</option>' . "\n" . '';
			}
		}

		$EnableQCoreClass->replace('area_list', $areaList);
		$page_others .= '&area=' . $_GET['area'];
	}

	if (isset($_GET['t_userGroupID']) && ($_GET['t_userGroupID'] != '') && ($Sur_G_Row['projectType'] == '1') && !$_POST['Action']) {
		$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['t_userGroupID'] . '-%\' OR userGroupID = \'' . $_GET['t_userGroupID'] . '\') ';
		$cSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY userGroupID ASC ';
		$cResult = $DB->query($cSQL);
		$theTaskArray = array();

		while ($cRow = $DB->queryArray($cResult)) {
			$theTaskArray[] = $cRow['userGroupID'];
		}

		if (count($theTaskArray) == 0) {
			$SQL .= ' AND b.responseID=0 ';
		}
		else {
			$SQL .= ' AND b.taskID IN (' . implode(',', $theTaskArray) . ') ';
		}

		$page_others .= '&t_userGroupID=' . $_GET['t_userGroupID'];
		$EnableQCoreClass->replace('t_userGroupID', $_GET['t_userGroupID']);
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	$EnableQCoreClass->replace('totalResponseNum', $recordCount);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
		$start = ($start < 0 ? 0 : $start);
	}

	$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
	$ViewBackURL = $thisProg . '&Does=List&pageID=' . $pageID . $page_others;
	$_SESSION['ViewBackURL'] = $ViewBackURL;
	$ExportSQL = trim(preg_replace('\'SELECT[^>]*?WHERE\'si', '', $SQL));
	$_SESSION['exportDataSQL'] = urlencode($ExportSQL);
	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('responseID', $Row['responseID']);
		$EnableQCoreClass->replace('taskID', $Row['taskID']);

		switch ($Row['overFlag']) {
		case '0':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/iyellow.png) repeat-y top left');
			break;

		case '1':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff');
			break;

		case '2':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/ired.png) repeat-y top left');
			break;

		case '3':
			$EnableQCoreClass->replace('noHaveAll', '#ffffff url(../Images/igreen.png) repeat-y top left');
			break;
		}

		$EnableQCoreClass->replace('administratorsName', $Row['administratorsName'] != '' ? $Row['administratorsName'] : $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$SQL = ' SELECT indexID,indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $Row['responseID'] . '\' ORDER BY indexID ASC ';
		$rResult = $DB->query($SQL);
		$theIndexValue = array();

		while ($rRow = $DB->queryArray($rResult)) {
			$theIndexValue[$rRow['indexID']] = $rRow['indexValue'];
		}

		if ($Sur_G_Row['isRateIndex'] == 1) {
			$EnableQCoreClass->replace('surveyGrade', $theIndexValue[0] . '%');
		}
		else {
			$EnableQCoreClass->replace('surveyGrade', $theIndexValue[0]);
		}

		$indexValueList = '';

		foreach ($surveyIndexName as $indexID) {
			$theValue = ($theIndexValue[$indexID] == '-999' ? 'NA' : $theIndexValue[$indexID]);

			if ($Sur_G_Row['isRateIndex'] == 1) {
				if (($theValue != 'NA') && ($theValue != '')) {
					$indexValueList .= '<td class=\'classtd\' align=center nowrap>&nbsp;' . $theValue . '%</td>';
				}
				else {
					$indexValueList .= '<td class=\'classtd\' align=center nowrap>&nbsp;' . $theValue . '</td>';
				}
			}
			else {
				$indexValueList .= '<td class=\'classtd\' align=center nowrap>&nbsp;' . $theValue . '</td>';
			}
		}

		$EnableQCoreClass->replace('indexValueList', $indexValueList);
		$EnableQCoreClass->replace('viewURL', $thisProg . '&Does=View&responseID=' . $Row['responseID']);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	include_once ROOT_PATH . 'Includes/Pages.class.php';
	$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
	$pagebar = $PAGES->whole_num_bar('', '', $page_others);
	$EnableQCoreClass->replace('pagesList', $pagebar);
	if (($_SESSION['adminRoleType'] == '3') && ($Sur_G_Row['isExportData'] == 1)) {
		$EnableQCoreClass->replace('exportExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	}
	else if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('exportExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
	}
	else {
		$exportURL = '../Export/Export.index.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
		$EnableQCoreClass->replace('exportExportURL', $exportURL);
	}

	$EnableQCoreClass->replace('isEvalUsers', $License['isEvalUsers']);
	$EnableQCoreClass->parse('ResultList', 'ResultListFile');
	$EnableQCoreClass->output('ResultList');
}

if ($_POST['Action'] == 'MakeDataSubmit') {
	if ((time() - $Sur_G_Row['indexTime']) < 300) {
		_showalert('ϵͳ����', '���ϴ�����ָ��������ݵ�ʱ����С��5���ӣ����Ժ����ԣ�');
	}

	if ($Sur_G_Row['indexVersion'] != 0) {
		if ((time() - $Sur_G_Row['indexTime']) < 900) {
			_showalert('ϵͳ����', '��ǰ���������û���������ָ����������ݣ���������ֹ����ȴ�15���Ӻ����ԣ�');
		}
	}

	@set_time_limit(0);
	ob_end_clean();
	$style = '<style>' . "\n" . '';
	$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height: 20px;margin:0px;padding:0px;}' . "\n" . '';
	$style .= '.red{ color: #cf1100;font-weight: bold;}' . "\n" . '';
	$style .= '.green{ color: green;font-weight: bold;}' . "\n" . '';
	$style .= '</style>' . "\n" . '';
	echo $style;
	flush();
	$scroll = '<script type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</script>';
	$prefix = '';
	$i = 0;

	for (; $i < 300; $i++) {
		$prefix .= ' ' . "\n" . '';
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET indexVersion = \'' . $_SESSION['administratorsID'] . '\' WHERE surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' SELECT indexID,questionID FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE surveyID=\'' . $Sur_G_Row['surveyID'] . '\' ORDER BY indexID ASC ';
	$QtnResult = $DB->query($SQL);
	$QtnList = array();

	while ($QtnRow = $DB->queryArray($QtnResult)) {
		if (!in_array($QtnRow['questionID'], $QtnList[$QtnRow['indexID']])) {
			$QtnList[$QtnRow['indexID']][] = $QtnRow['questionID'];
		}
	}

	$SQL = ' SELECT indexID,isMinZero,isMaxFull,fullValue,fatherId FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $Sur_G_Row['surveyID'] . '\' ORDER BY indexID ASC ';
	$zResult = $DB->query($SQL);
	$isMinZero = array();
	$isMaxFull = array();
	$fullValue = array();
	$fatherId = array();
	$theIndexFather = array();
	$theOneTierIndex = array();

	while ($zRow = $DB->queryArray($zResult)) {
		$isMinZero[$zRow['indexID']] = $zRow['isMinZero'];
		$isMaxFull[$zRow['indexID']] = $zRow['isMaxFull'];
		$fullValue[$zRow['indexID']] = $zRow['fullValue'];

		if ($zRow['fatherId'] != 0) {
			$theIndexFather[$zRow['indexID']] = $zRow['fatherId'];
			$fatherId[$zRow['fatherId']][] = $zRow['indexID'];
		}
		else {
			$theOneTierIndex[] = $zRow['indexID'];
		}
	}

	$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $Sur_G_Row['surveyID'] . ' ORDER BY responseID ASC ';
	$Result = $DB->query($SQL);
	$theSuccNum = 0;

	while ($R_Row = $DB->queryArray($Result)) {
		$isRecResult = 1;
		require ROOT_PATH . 'System/SurveyIndexGrade.inc.php';
		$SQL = ' INSERT INTO ' . SURVEYINDEXRESULT_TABLE . ' SET surveyID = \'' . $Sur_G_Row['surveyID'] . '\',responseID = \'' . $R_Row['responseID'] . '\',taskID = \'' . $R_Row['taskID'] . '\',indexID = \'0\',indexValue = \'' . $totalValue . '\' ';
		$DB->query($SQL);
		ob_end_clean();

		if ($R_Row['administratorsName'] != '') {
			$str = '<div class="tipsinfo"><font color=green><b>�������ݳɹ�</b></font>�����Ϊ<span class=red>' . $R_Row['responseID'] . '(' . $R_Row['administratorsName'] . ')</span></div>' . "\n" . '';
		}
		else {
			$str = '<div class="tipsinfo"><font color=green><b>�������ݳɹ�</b></font>�����Ϊ<span class=red>' . $R_Row['responseID'] . '(' . $R_Row['ipAddress'] . ')</span></div>' . "\n" . '';
		}

		echo $prefix . $str . $scroll;
		flush();
		$theSuccNum++;
	}

	ob_end_clean();
	echo '<div class="tipsinfo">�ܼ����ɣ�<b><font color=green>' . $theSuccNum . '</font></b>&nbsp;��ָ���������</div>' . "\n" . '';
	flush();
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET indexVersion = \'0\',indexTime=\'' . time() . '\',indexAdminId=\'' . $_SESSION['administratorsID'] . '\' WHERE surveyID = \'' . $Sur_G_Row['surveyID'] . '\' ';
	$DB->query($SQL);
	writetolog('����ָ������м�����');
	unset($isMinZero);
	unset($isMaxFull);
	unset($fullValue);
	unset($fatherId);
	unset($theIndexFather);
	unset($theOneTierIndex);
	echo '<script>parent._showCloseWindowButton();</script>';
	exit();
}

$EnableQCoreClass->setTemplateFile('ResultListFile', 'IndexResultMake.html');

if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('isProjectType1', '');
}
else {
	$EnableQCoreClass->replace('isProjectType1', 'none');
}

if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '3') || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('defbtn', 'none');
}
else {
	$EnableQCoreClass->replace('defbtn', '');
}

if ($Sur_G_Row['indexTime'] == 0) {
	$resultInfo = '<font color=red><b>Ŀǰ��δ���ɵ�ָ���������</b></font>';
}
else {
	$resultInfo = '<font color=red><b>Ŀǰ���ɵ�ָ�����������';
	$AdminSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Sur_G_Row['indexAdminId'] . '\' ';
	$UserRow = $DB->queryFirstRow($AdminSQL);

	if (!$UserRow) {
		$resultInfo .= $lang['deleted_user'];
	}
	else {
		$resultInfo .= _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']);
	}

	$resultInfo .= '��' . date('Y-m-d H:i:s', $Sur_G_Row['indexTime']) . '����';
	$hSQL = ' SELECT COUNT(*) as theDataNum FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND indexID =0 ';
	$hRow = $DB->queryFirstRow($hSQL);
	$resultInfo .= $hRow['theDataNum'] . '���м�����</b></font>';
}

$EnableQCoreClass->replace('resultInfo', $resultInfo);
$EnableQCoreClass->parse('ResultList', 'ResultListFile');
$EnableQCoreClass->output('ResultList');

?>
