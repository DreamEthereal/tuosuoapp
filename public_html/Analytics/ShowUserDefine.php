<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$thisProg = 'ShowUserDefine.php?type=' . $_GET['type'] . '&surveyID=' . $_GET['surveyID'] . '&questionID=' . $_GET['questionID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isComb=' . $_GET['isComb'];
$thisProg .= '&dataSourceId=' . $_GET['dataSourceId'] . '&orderQtn=' . $_GET['orderQtn'];
$EnableQCoreClass->replace('thisURL', $thisProg);
$lastProg = 'Frequency.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$lastProg .= '&dataSourceId=' . $_GET['dataSourceId'] . '&pageID=' . $_GET['pageNum'];

switch ($_GET['isComb']) {
case '1':
	$EnableQCoreClass->replace('lastURL', 'CombOptions.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&pageID=' . $_GET['pageNum']);
	break;

case '2':
	$lastURL = 'OptionsCoeff.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&dataSourceId=' . $_GET['dataSourceId'] . '&pageID=' . $_GET['pageNum'];
	$EnableQCoreClass->replace('lastURL', $lastURL);
	break;

default:
	$EnableQCoreClass->replace('lastURL', $lastProg);
	break;
}

$SQL = ' SELECT status,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
	$theSID = $Sur_G_Row['surveyID'];
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$dataSource = getdatasourcesql($_GET['dataSourceId'], $_GET['surveyID']);
$EnableQCoreClass->setTemplateFile('UserDefineListFile', 'UserDefine.html');
$EnableQCoreClass->set_CycBlock('UserDefineListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$perPageNum = 50;

switch ($_GET['type']) {
case 'radio':
case 'checkbox':
	$SQL = ' SELECT questionName,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($Row['otherText'], 1));

	if ($Row['isHaveOther'] != '1') {
		_showerror($lang['system_error'], $lang['question_no_other']);
	}

	if ($_GET['type'] == 'radio') {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . ' = 0 AND b.TextOtherValue_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	}
	else {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE FIND_IN_SET(0,b.option_' . $_GET['questionID'] . ') AND b.TextOtherValue_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', $Row['TextOtherValue_' . $_GET['questionID']]);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'combradio':
case 'combcheckbox':
	$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($_GET['type'] == 'combradio') {
		$SQL = ' SELECT question_radioID,optionName,isHaveText FROM ' . QUESTION_RADIO_TABLE . ' WHERE question_radioID =\'' . $_GET['optionID'] . '\' ';
	}
	else {
		$SQL = ' SELECT question_checkboxID,optionName,isHaveText FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $_GET['optionID'] . '\' ';
	}

	$OptRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1));

	if ($OptRow['isHaveText'] != '1') {
		_showerror($lang['system_error'], $lang['option_no_text']);
	}

	if ($_GET['type'] == 'combradio') {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . ' = \'' . $_GET['optionID'] . '\' AND b.TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' != \'\' and ' . $dataSource;
	}
	else {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE FIND_IN_SET(' . $_GET['optionID'] . ',b.option_' . $_GET['questionID'] . ') AND b.TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' != \'\' and ' . $dataSource;
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', $Row['TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID']]);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'text':
	$SQL = ' SELECT questionName,isHaveUnkown FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$TRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($TRow['questionName'], 1));

	if ($TRow['isHaveUnkown'] == 2) {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . ',isHaveUnkown_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ( (b.option_' . $_GET['questionID'] . ' != \'\' AND b.isHaveUnkown_' . $_GET['questionID'] . ' = 0 ) OR b.isHaveUnkown_' . $_GET['questionID'] . ' = 1 ) and ' . $dataSource;
	}
	else {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);

		if ($TRow['isHaveUnkown'] == 2) {
			if ($Row['isHaveUnkown_' . $_GET['questionID']] == 1) {
				$EnableQCoreClass->replace('content', $lang['rating_unknow']);
			}
			else {
				$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID']]));
			}
		}
		else {
			$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID']]));
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'textarea':
	$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1));
	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID']]));
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'multipletext':
	$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$SQL = ' SELECT optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE question_range_optionID =\'' . $_GET['optionID'] . '\' ';
	$OptRow = $DB->queryFirstRow($SQL);
	$SQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID =\'' . $_GET['labelID'] . '\' ';
	$LabRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1) . ' - ' . qnohtmltag($LabRow['optionLabel'], 1));
	$theTextID = 'option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . '_' . $_GET['labelID'];
	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,' . $theTextID . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.' . $theTextID . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', nl2br($Row[$theTextID]));
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'automultitext':
	$SQL = ' SELECT questionName,baseID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$QRow = $DB->queryFirstRow($SQL);
	$SQL = ' SELECT optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE question_range_labelID =\'' . $_GET['labelID'] . '\' ';
	$LabRow = $DB->queryFirstRow($SQL);

	if ($_GET['optionID'] == 0) {
		$SQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $QRow['baseID'] . '\' ';
		$OptRow = $DB->queryFirstRow($SQL);
		$questionName = qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($OptRow['otherText'], 1) . ' - ' . qnohtmltag($LabRow['optionLabel'], 1);
	}
	else {
		$SQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $_GET['optionID'] . '\' ';
		$OptRow = $DB->queryFirstRow($SQL);
		$questionName = qnohtmltag($QRow['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1) . ' - ' . qnohtmltag($LabRow['optionLabel'], 1);
	}

	$EnableQCoreClass->replace('questionName', $questionName);
	$theTextID = 'option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . '_' . $_GET['labelID'];
	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,' . $theTextID . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.' . $theTextID . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', nl2br($Row[$theTextID]));
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'list':
	$SQL = ' SELECT questionName FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . $_GET['orderQtn']);
	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . '_' . $_GET['orderQtn'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . '_' . $_GET['orderQtn'] . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID'] . '_' . $_GET['orderQtn']]));
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'combtext':
	$SQL = ' SELECT questionName,isHaveUnkown FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$TRow = $DB->queryFirstRow($SQL);
	$SQL = ' SELECT optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE question_yesnoID =\'' . $_GET['optionID'] . '\' ';
	$OptRow = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($TRow['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1));

	if ($TRow['isHaveUnkown'] == 2) {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ',isHaveUnkown_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE ( (b.option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' != \'\' AND b.isHaveUnkown_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' = 0 ) OR b.isHaveUnkown_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' = 1 ) and ' . $dataSource;
	}
	else {
		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.option_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' != \'\' and ' . $dataSource;
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);

		if ($TRow['isHaveUnkown'] == 2) {
			if ($Row['isHaveUnkown_' . $_GET['questionID'] . '_' . $_GET['optionID']] == 1) {
				$EnableQCoreClass->replace('content', $lang['rating_unknow']);
			}
			else {
				$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID'] . '_' . $_GET['optionID']]));
			}
		}
		else {
			$EnableQCoreClass->replace('content', nl2br($Row['option_' . $_GET['questionID'] . '_' . $_GET['optionID']]));
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'rating':
	$SQL = ' SELECT questionName,baseID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($_GET['qtntype'] == 1) {
		$SQL = ' SELECT optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE question_rankID =\'' . $_GET['optionID'] . '\' ';
		$OptRow = $DB->queryFirstRow($SQL);
		$questionName = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1);
	}
	else if ($_GET['optionID'] == 0) {
		$SQL = ' SELECT otherText FROM ' . QUESTION_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ';
		$OptRow = $DB->queryFirstRow($SQL);
		$questionName = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptRow['otherText'], 1);
	}
	else {
		$SQL = ' SELECT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE question_checkboxID =\'' . $_GET['optionID'] . '\' ';
		$OptRow = $DB->queryFirstRow($SQL);
		$questionName = qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($OptRow['optionName'], 1);
	}

	$EnableQCoreClass->replace('questionName', $questionName);
	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID'] . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', nl2br($Row['TextOtherValue_' . $_GET['questionID'] . '_' . $_GET['optionID']]));
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'rank':
	$SQL = ' SELECT questionName,otherText,isHaveOther,isHaveWhy FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($_GET['content'] == '1') {
		$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . qnohtmltag($Row['otherText'], 1));

		if ($Row['isHaveOther'] != '1') {
			_showerror($lang['system_error'], $lang['question_no_other']);
		}

		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.TextOtherValue_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	}
	else {
		$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . $lang['why_your_order']);

		if ($Row['isHaveWhy'] != '1') {
			_showerror($lang['system_error'], $lang['question_no_why']);
		}

		$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextWhyValue_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.TextWhyValue_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	}

	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);

		if ($_GET['content'] == '1') {
			$EnableQCoreClass->replace('content', nl2br($Row['TextOtherValue_' . $_GET['questionID']]));
		}
		else {
			$EnableQCoreClass->replace('content', nl2br($Row['TextWhyValue_' . $_GET['questionID']]));
		}

		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;

case 'range_text':
case 'multiple_text':
case 'weight_text':
case 'rating_text':
	$SQL = ' SELECT questionName,isHaveOther,isCheckType FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('questionName', qnohtmltag($Row['questionName'], 1) . ' - ' . base64_decode($_GET['optionName']) . ' - »Ø¸´ÎÄ±¾');

	if ($_GET['type'] == 'rating_text') {
		if ($Row['isCheckType'] != '1') {
			_showerror($lang['system_error'], $lang['question_no_other']);
		}
	}
	else if ($Row['isHaveOther'] != '1') {
		_showerror($lang['system_error'], $lang['question_no_other']);
	}

	$SQL = ' SELECT administratorsName,ipAddress,area,joinTime,TextOtherValue_' . $_GET['questionID'] . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b WHERE b.TextOtherValue_' . $_GET['questionID'] . ' != \'\' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$recordCount = $DB->_getNumRows($Result);
	if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
		$start = 0;
	}
	else {
		$_GET['pageID'] = (int) $_GET['pageID'];
		$start = ($_GET['pageID'] - 1) * $perPageNum;
		$start = ($start < 0 ? 0 : $start);
	}

	$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $perPageNum . ' ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $Row['joinTime']));
		$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
		$EnableQCoreClass->replace('area', $Row['area']);
		$EnableQCoreClass->replace('membersName', $Row['administratorsName']);
		$EnableQCoreClass->replace('content', $Row['TextOtherValue_' . $_GET['questionID']]);
		$EnableQCoreClass->parse('list', 'LIST', true);
	}

	break;
}

$EnableQCoreClass->replace('totalResponseNum', $recordCount);
include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $perPageNum);
$pagebar = $PAGES->whole_num_bar();
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('UserDefineList', 'UserDefineListFile');
$EnableQCoreClass->output('UserDefineList');

?>
