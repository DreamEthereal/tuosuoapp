<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$thisProg = 'ShowSurveyIndex.php';
$SQL = ' SELECT status FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	$EnableQCoreClass->replace('isDesignMode', '');
}
else {
	$EnableQCoreClass->replace('isDesignMode', 'none');
}

$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'SurveyIndexMatchSubmit') {
	$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	$thisSurveyIndexList = substr($_POST['indexIDList'], 0, -1);
	$thisSurveyIndexFields = explode('|', $thisSurveyIndexList);

	foreach ($thisSurveyIndexFields as $thisSurveyIndex) {
		if (!empty($_POST['indexID_' . $thisSurveyIndex])) {
			foreach ($_POST['indexID_' . $thisSurveyIndex] as $questionID) {
				$SQL = ' INSERT INTO ' . SURVEYINDEXLIST_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',indexID=\'' . $thisSurveyIndex . '\',questionID=\'' . $questionID . '\' ';
				$DB->query($SQL);
			}
		}
	}

	writetolog($lang['survey_index_match'] . ':' . $_POST['surveyTitle']);
	$thisURL = $thisProg . '?Action=MatchQtn&surveyID=' . $_POST['surveyID'] . '&surveyTitle=' . urlencode($_POST['surveyTitle']);
	_showsucceed($lang['survey_index_match'] . ':' . $_POST['surveyTitle'], $thisURL);
}

if ($_GET['Action'] == 'MatchQtn') {
	$EnableQCoreClass->setTemplateFile('SurveyIndexMatchPageFile', 'SurveyIndexMatch.html');
	$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
	$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
	$EnableQCoreClass->replace('indexListURL', $thisProg . '?' . $thisURLStr);
	$EnableQCoreClass->replace('qtnURL', $thisProg . '?Action=MatchQtn&' . $thisURLStr);
	$SQL = ' SELECT questionID FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC ';
	$QtnResult = $DB->query($SQL);
	$QtnList = array();

	while ($QtnRow = $DB->queryArray($QtnResult)) {
		$QtnList[] = $QtnRow['questionID'];
	}

	if (!empty($QtnList)) {
		$QtnIDList = implode(',', $QtnList);
		$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND ( questionType IN (1,2,3,6,7,15,17,18,19,21,24,25,26,28) OR (questionType =4 AND isCheckType =4)) AND questionID NOT IN (' . $QtnIDList . ') ORDER BY orderByID ASC ';
	}
	else {
		$QtnIDList = implode(',', $QtnList);
		$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND ( questionType IN (1,2,3,6,7,15,17,18,19,21,24,25,26,28) OR (questionType =4 AND isCheckType =4)) ORDER BY orderByID ASC ';
	}

	$Result = $DB->query($SQL);
	$qtnList = '';

	while ($Row = $DB->queryArray($Result)) {
		$questionName = qnohtmltag($Row['questionName'], 1);
		$qtnList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('qtnList', $qtnList);
	$EnableQCoreClass->set_CycBlock('SurveyIndexMatchPageFile', 'INDEX', 'index');
	$EnableQCoreClass->replace('index', '');
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID =\'' . $_GET['surveyID'] . '\' AND fatherId !=0 ORDER BY indexID ASC ';
	$Result = $DB->query($SQL);
	$indexNum = $DB->_getNumRows($Result);

	switch ($indexNum) {
	case 0:
		$EnableQCoreClass->replace('qtnSize', 10);
		$EnableQCoreClass->replace('noSubmit', 'disabled');
		break;

	case 1:
		$EnableQCoreClass->replace('qtnSize', 5);
		$EnableQCoreClass->replace('noSubmit', '');
		break;

	default:
		$EnableQCoreClass->replace('qtnSize', (($indexNum - 2) * 8) + 13);
		$EnableQCoreClass->replace('noSubmit', '');
		break;
	}

	$indexIDList = '';

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('indexID', $Row['indexID']);
		$indexIDList .= $Row['indexID'] . '|';
		$EnableQCoreClass->replace('indexName', $Row['indexName']);
		$SQL = ' SELECT questionID FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE indexID=\'' . $Row['indexID'] . '\' ORDER BY questionID ASC ';
		$QtnResult = $DB->query($SQL);
		$QtnList = array();

		while ($QtnRow = $DB->queryArray($QtnResult)) {
			$QtnList[] = $QtnRow['questionID'];
		}

		$indexList = '';

		if (!empty($QtnList)) {
			$QtnIDList = implode(',', $QtnList);
			$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionID IN (' . $QtnIDList . ') ORDER BY orderByID ASC ';
			$HaveResult = $DB->query($SQL);

			while ($HaveRow = $DB->queryArray($HaveResult)) {
				$questionName = qnohtmltag($HaveRow['questionName'], 1);
				$indexList .= '<option value=\'' . $HaveRow['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $HaveRow['questionType']] . ')</option>' . "\n" . '';
			}
		}

		$EnableQCoreClass->replace('indexList', $indexList);
		$EnableQCoreClass->parse('index', 'INDEX', true);
	}

	$EnableQCoreClass->replace('indexIDList', $indexIDList);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->parse('SurveyIndexMatchPage', 'SurveyIndexMatchPageFile');
	$EnableQCoreClass->output('SurveyIndexMatchPage', false);
}

if ($_POST['DeleteIndexSubmit']) {
	if (is_array($_POST['indexID'])) {
		$indexIDLists = join(',', $_POST['indexID']);
		$SQL = 'SELECT indexID FROM ' . SURVEYINDEX_TABLE . ' WHERE fatherId IN (' . $indexIDLists . ') ';
		$Result = $DB->query($SQL);
		$theSonIndex = array();

		while ($Row = $DB->queryArray($Result)) {
			$theSonIndex[] = $Row['indexID'];
		}

		if (count($theSonIndex) != 0) {
			$theSonIndexIDLists = implode(',', $theSonIndex);
			$SQL = ' DELETE FROM ' . SURVEYINDEX_TABLE . ' WHERE indexID IN (' . $theSonIndexIDLists . ') ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE indexID IN (' . $theSonIndexIDLists . ') ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE indexID IN (' . $theSonIndexIDLists . ') ';
			$DB->query($SQL);
		}

		$SQL = ' DELETE FROM ' . SURVEYINDEX_TABLE . ' WHERE indexID IN (' . $indexIDLists . ') ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE indexID IN (' . $indexIDLists . ') ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE indexID IN (' . $indexIDLists . ') ';
		$DB->query($SQL);
	}

	writetolog($lang['edit_survey_index'] . ':' . $_POST['surveyTitle']);
}

if ($_POST['Action'] == 'IndexEditSubmit') {
	$SQL = ' SELECT indexID FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND indexID != \'' . $_POST['indexID'] . '\' AND indexName=\'' . trim($_POST['indexName']) . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		_showerror($lang['error_system'], $lang['index_is_exist']);
	}

	if ($_POST['fatherId'] == $_POST['indexID']) {
		_showerror('数据检查错误', '数据检查错误：二级指标所属的一级指标不能为自身!');
	}

	$SQL = ' UPDATE ' . SURVEYINDEX_TABLE . ' SET indexName=\'' . trim($_POST['indexName']) . '\',indexDesc=\'' . trim($_POST['indexDesc']) . '\',fatherId=\'' . $_POST['fatherId'] . '\',fullValue=\'' . $_POST['fullValue'] . '\',isMaxFull=\'' . $_POST['isMaxFull'] . '\',isMinZero=\'' . $_POST['isMinZero'] . '\' WHERE indexID=\'' . $_POST['indexID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['edit_survey_index'] . ':' . $_POST['surveyTitle']);
	_showmessage($lang['edit_survey_index'], true);
}

if ($_GET['Action'] == 'Edit') {
	$SQL = ' SELECT * FROM ' . SURVEYINDEX_TABLE . ' WHERE indexID=\'' . $_GET['indexID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->setTemplateFile('SurveyIndexEditPageFile', 'SurveyIndexEdit.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('indexName', $Row['indexName']);
	$EnableQCoreClass->replace('indexDesc', $Row['indexDesc']);
	$EnableQCoreClass->replace('indexID', $Row['indexID']);

	if ($Row['fatherId'] != 0) {
		$EnableQCoreClass->replace('indexType_2', 'selected');
	}
	else {
		$EnableQCoreClass->replace('indexType_1', 'selected');
	}

	$fSQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
	$fResult = $DB->query($fSQL);
	$indexList = '';

	while ($fRow = $DB->queryArray($fResult)) {
		if ($Row['fatherId'] == $fRow['indexID']) {
			$indexList .= '<option value=\'' . $fRow['indexID'] . '\' selected>' . $fRow['indexName'] . '</option>';
		}
		else {
			$indexList .= '<option value=\'' . $fRow['indexID'] . '\'>' . $fRow['indexName'] . '</option>';
		}
	}

	$EnableQCoreClass->replace('indexList', $indexList);
	$EnableQCoreClass->replace('fullValue', $Row['fullValue'] == '0.00' ? '' : $Row['fullValue']);
	$EnableQCoreClass->replace('isMinZero', $Row['isMinZero'] == 1 ? 'checked' : '');
	$EnableQCoreClass->replace('isMaxFull', $Row['isMaxFull'] == 1 ? 'checked' : '');
	$EnableQCoreClass->replace('Action', 'IndexEditSubmit');
	$EnableQCoreClass->parse('SurveyIndexEditPage', 'SurveyIndexEditPageFile');
	$EnableQCoreClass->output('SurveyIndexEditPage', false);
}

if ($_POST['Action'] == 'IndexAddSubmit') {
	$SQL = ' SELECT indexID FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND indexName=\'' . trim($_POST['indexName']) . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		_showerror($lang['error_system'], $lang['index_is_exist']);
	}

	$SQL = ' INSERT INTO ' . SURVEYINDEX_TABLE . ' SET indexName=\'' . trim($_POST['indexName']) . '\',indexDesc=\'' . trim($_POST['indexDesc']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',fatherId=\'' . $_POST['fatherId'] . '\',fullValue=\'' . $_POST['fullValue'] . '\',isMaxFull=\'' . $_POST['isMaxFull'] . '\',isMinZero=\'' . $_POST['isMinZero'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['edit_survey_index'] . ':' . $_POST['surveyTitle']);
	_showmessage($lang['edit_survey_index'], true);
}

if ($_GET['Action'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('SurveyIndexEditPageFile', 'SurveyIndexEdit.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->replace('indexName', '');
	$EnableQCoreClass->replace('indexDesc', '');
	$EnableQCoreClass->replace('indexID', '');
	$EnableQCoreClass->replace('indexType_1', 'selected');
	$SQL = ' SELECT indexID,indexName FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
	$Result = $DB->query($SQL);
	$indexList = '';

	while ($Row = $DB->queryArray($Result)) {
		$indexList .= '<option value=\'' . $Row['indexID'] . '\'>' . $Row['indexName'] . '</option>';
	}

	$EnableQCoreClass->replace('indexList', $indexList);
	$EnableQCoreClass->replace('fullValue', '');
	$EnableQCoreClass->replace('isMinZero', '');
	$EnableQCoreClass->replace('isMaxFull', '');
	$EnableQCoreClass->replace('Action', 'IndexAddSubmit');
	$EnableQCoreClass->parse('SurveyIndexEditPage', 'SurveyIndexEditPageFile');
	$EnableQCoreClass->output('SurveyIndexEditPage', false);
}

if ($_GET['Action'] == 'List') {
	$EnableQCoreClass->setTemplateFile('SurveyIndexListPageFile', 'SurveyIndexList.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
	$EnableQCoreClass->set_CycBlock('SurveyIndexListPageFile', 'LIST', 'list');
	$EnableQCoreClass->replace('list', '');
	$EnableQCoreClass->set_CycBlock('LIST', 'INDEX', 'index');
	$EnableQCoreClass->replace('index', '');
	$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
	$addURL = '?Action=Add&' . $thisURLStr;
	$EnableQCoreClass->replace('addURL', $addURL);
	$EnableQCoreClass->replace('qtnURL', $thisProg . '?Action=MatchQtn&' . $thisURLStr);
	$SQL = ' SELECT * FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
	$Result = $DB->query($SQL);
	$recNum = $DB->_getNumRows($Result);
	$EnableQCoreClass->replace('totalRecNum', $recNum);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('index', '');
		$EnableQCoreClass->replace('indexNameF', $Row['indexName']);
		$EnableQCoreClass->replace('indexIDF', $Row['indexID']);

		if ($Row['indexDesc'] == '') {
			$EnableQCoreClass->replace('indexDescF', '&nbsp;');
		}
		else {
			$EnableQCoreClass->replace('indexDescF', $Row['indexDesc']);
		}

		$EnableQCoreClass->replace('fullValueF', $Row['fullValue'] == '0.00' ? '' : $Row['fullValue']);
		$EnableQCoreClass->replace('isMinZeroF', $Row['isMinZero'] == 1 ? '是' : '否');
		$EnableQCoreClass->replace('isMaxFullF', $Row['isMaxFull'] == 1 ? '是' : '否');
		$editURL = '?Action=Edit&surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'] . '&indexID=' . $Row['indexID'];
		$EnableQCoreClass->replace('editURLF', $editURL);
		$qtnNumF = 0;
		$sSQL = ' SELECT * FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND fatherId = \'' . $Row['indexID'] . '\' ORDER BY indexID ASC ';
		$sResult = $DB->query($sSQL);

		while ($sRow = $DB->queryArray($sResult)) {
			$EnableQCoreClass->replace('indexName', $sRow['indexName']);
			$EnableQCoreClass->replace('indexID', $sRow['indexID']);

			if ($sRow['indexDesc'] == '') {
				$EnableQCoreClass->replace('indexDesc', '&nbsp;');
			}
			else {
				$EnableQCoreClass->replace('indexDesc', $sRow['indexDesc']);
			}

			$EnableQCoreClass->replace('fullValue', $sRow['fullValue'] == '0.00' ? '' : $sRow['fullValue']);
			$EnableQCoreClass->replace('isMinZero', $sRow['isMinZero'] == 1 ? '是' : '否');
			$EnableQCoreClass->replace('isMaxFull', $sRow['isMaxFull'] == 1 ? '是' : '否');
			$seditURL = '?Action=Edit&surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . $_GET['surveyTitle'] . '&indexID=' . $sRow['indexID'];
			$EnableQCoreClass->replace('editURL', $seditURL);
			$hSQL = ' SELECT count(*) as qtnNum FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE indexID=\'' . $sRow['indexID'] . '\' LIMIT 0,1 ';
			$QRow = $DB->queryFirstRow($hSQL);
			$qtnNumF += $QRow['qtnNum'];
			$EnableQCoreClass->replace('qtnNum', $QRow['qtnNum']);
			$EnableQCoreClass->parse('index', 'INDEX', true);
		}

		$EnableQCoreClass->replace('qtnNumF', $qtnNumF);
		$EnableQCoreClass->parse('list', 'LIST', true);
		$EnableQCoreClass->unreplace('index');
	}

	$SurveyIndexListPage = $EnableQCoreClass->parse('SurveyIndexListPage', 'SurveyIndexListPageFile');
	header('Content-Type:text/html; charset=gbk');
	echo $SurveyIndexListPage;
	exit();
}

$EnableQCoreClass->setTemplateFile('SurveyIndexPageFile', 'SurveyIndex.html');
$thisURLStr = 'surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('indexListURL', $thisProg . '?' . $thisURLStr);
$EnableQCoreClass->replace('qtnURL', $thisProg . '?Action=MatchQtn&' . $thisURLStr);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->parse('SurveyIndexPage', 'SurveyIndexPageFile');
$EnableQCoreClass->output('SurveyIndexPage', false);

?>
