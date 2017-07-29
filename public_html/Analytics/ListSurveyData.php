<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';

switch ($_SESSION['adminRoleType']) {
case '3':
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'ResultViewList.html');
	$EnableQCoreClass->replace('colsNum', '8');
	$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);

	if (in_array('t1', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t1_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t1_show', '');
	}

	if (in_array('t2', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t2_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t2_show', '');
	}

	if ($Sur_G_Row['mainShowQtn'] == 0) {
		$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
		$Row = $DB->queryFirstRow($SQL);

		if (!$Row) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$EnableQCoreClass->replace('colsNum', '7');
			$mainShowField = '';
		}
		else if (in_array($Row['questionID'], $forbidViewIdValue)) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$EnableQCoreClass->replace('colsNum', '7');
			$mainShowField = '';
		}
		else {
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 20, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Row['questionID'];
		}
	}
	else {
		$FHSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' LIKE \'option_' . $Sur_G_Row['mainShowQtn'] . '\' ';
		$FHRow = $DB->queryFirstRow($FHSQL);

		if ($FHRow['Field'] == '') {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC LIMIT 0,1 ';
			$Row = $DB->queryFirstRow($SQL);

			if (!$Row) {
				$EnableQCoreClass->replace('qtnNameList', '');
				$EnableQCoreClass->replace('isMainShowQtn', 'none');
				$EnableQCoreClass->replace('colsNum', '7');
				$mainShowField = '';
			}
			else if (in_array($Row['questionID'], $forbidViewIdValue)) {
				$EnableQCoreClass->replace('qtnNameList', '');
				$EnableQCoreClass->replace('isMainShowQtn', 'none');
				$EnableQCoreClass->replace('colsNum', '7');
				$mainShowField = '';
			}
			else {
				$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 20, 1));
				$EnableQCoreClass->replace('isMainShowQtn', '');
				$mainShowField = 'option_' . $Row['questionID'];
			}
		}
		else if (in_array($Sur_G_Row['mainShowQtn'], $forbidViewIdValue)) {
			$EnableQCoreClass->replace('qtnNameList', '');
			$EnableQCoreClass->replace('isMainShowQtn', 'none');
			$EnableQCoreClass->replace('colsNum', '7');
			$mainShowField = '';
		}
		else {
			$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $Sur_G_Row['mainShowQtn'] . '\' ORDER BY questionID ASC ';
			$Row = $DB->queryFirstRow($SQL);
			$EnableQCoreClass->replace('qtnNameList', cnsubstr(qlisttag($Row['questionName'], 1), 0, 20, 1));
			$EnableQCoreClass->replace('isMainShowQtn', '');
			$mainShowField = 'option_' . $Sur_G_Row['mainShowQtn'];
		}
	}

	break;

default:
	$EnableQCoreClass->setTemplateFile('ResultListFile', 'ResultList.html');
	$EnableQCoreClass->replace('colsNum', '9');
	$SQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'4\' AND surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY questionID ASC  ';
	$Result = $DB->query($SQL);
	$qtnNameList = '';
	$i = 0;

	while ($Row = $DB->queryArray($Result)) {
		if ($i == 0) {
			$firstTextQtnID = $Row['questionID'];
		}

		if ($Sur_G_Row['mainShowQtn'] == $Row['questionID']) {
			$qtnNameList .= '<option value=' . $Row['questionID'] . ' selected>' . cnsubstr(qnohtmltag($Row['questionName'], 1), 0, 20, 1) . '</option>';
		}
		else {
			$qtnNameList .= '<option value=' . $Row['questionID'] . '>' . cnsubstr(qnohtmltag($Row['questionName'], 1), 0, 20, 1) . '</option>';
		}

		$i++;
	}

	if ($i == 0) {
		$EnableQCoreClass->replace('qtnNameList', '');
		$EnableQCoreClass->replace('isMainShowQtn', 'none');
		$EnableQCoreClass->replace('colsNum', '8');
		$mainShowField = '';
	}
	else {
		$EnableQCoreClass->replace('qtnNameList', $qtnNameList);
		$EnableQCoreClass->replace('isMainShowQtn', '');

		if ($Sur_G_Row['mainShowQtn'] == 0) {
			$mainShowField = 'option_' . $firstTextQtnID;
		}
		else {
			$FHSQL = ' SHOW COLUMNS FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' LIKE \'option_' . $Sur_G_Row['mainShowQtn'] . '\' ';
			$FHRow = $DB->queryFirstRow($FHSQL);

			if ($FHRow['Field'] == '') {
				$mainShowField = 'option_' . $firstTextQtnID;
			}
			else {
				$mainShowField = 'option_' . $Sur_G_Row['mainShowQtn'];
			}
		}
	}

	break;
}

$EnableQCoreClass->set_CycBlock('ResultListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$ConfigRow['topicNum'] = 50;

if ($Sur_G_Row['projectType'] == 1) {
	$EnableQCoreClass->replace('isProjectType1', '');
}
else {
	$EnableQCoreClass->replace('isProjectType1', 'none');
}

$EnableQCoreClass->replace('projectOwner', $Sur_G_Row['projectOwner']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);

if ($Sur_G_Row['isDataSource'] == 1) {
	$EnableQCoreClass->replace('isHaveDataForm', '');
	$EnableQCoreClass->replace('isHaveSel', 'class="selectpicker" multiple');
}
else {
	$EnableQCoreClass->replace('isHaveDataForm', 'display:none');
	$EnableQCoreClass->replace('isHaveSel', 'disabled');
}

$SQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE area != \'\' ORDER BY area ASC ';
$Result = $DB->query($SQL);
$areaList = '';

while ($AreaRow = $DB->queryArray($Result)) {
	$areaList .= '<option value="' . $AreaRow['area'] . '">' . $AreaRow['area'] . '</option>' . "\n" . '';
}

$EnableQCoreClass->replace('area_list', $areaList);

if ($areaList == '') {
	$EnableQCoreClass->replace('isHaveData', '');
}
else {
	$EnableQCoreClass->replace('isHaveData', 'class="selectpicker" multiple');
}

$EnableQCoreClass->replace('t_name', '');
$EnableQCoreClass->replace('t_responseID', '');
$EnableQCoreClass->replace('t_userGroupID', '\'\'');

if ($mainShowField == '') {
	$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,overFlag0,authStat,version,adminID,appStat,taskID FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b ';
}
else {
	$SQL = ' SELECT responseID,administratorsName,ipAddress,joinTime,area,overFlag,overFlag0,authStat,version,adminID,appStat,taskID,' . $mainShowField . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' b ';
}

if (isset($_POST['dataSource'])) {
	$_SESSION['dataSource' . $_GET['surveyID']] = $_POST['dataSource'];
}

if (isset($_SESSION['dataSource' . $_GET['surveyID']])) {
	$dataSource = getdatasourcesql($_SESSION['dataSource' . $_GET['surveyID']], $_GET['surveyID']);
}
else {
	$dataSource = getdatasourcesql(0, $_GET['surveyID']);
}

$SQL .= ' WHERE ' . $dataSource;

switch ($_SESSION['adminRoleType']) {
case '1':
case '2':
case '5':
	$EnableQCoreClass->replace('authNodeText', '审核中');
	$EnableQCoreClass->replace('isAdmin7', '');

	if ($Sur_G_Row['projectType'] == 1) {
		$EnableQCoreClass->replace('isMyAdmin7', '');
	}
	else {
		$EnableQCoreClass->replace('isMyAdmin7', 'none');
	}

	break;

case '7':
	$EnableQCoreClass->replace('authNodeText', '待审核');
	$EnableQCoreClass->replace('isAdmin7', 'none');
	$EnableQCoreClass->replace('isMyAdmin7', 'none');
	$aSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE isAuth=1 AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$aRow = $DB->queryFirstRow($aSQL);
	$isAppAuthPassport = ($aRow ? 1 : 0);
	$aSQL = ' SELECT administratorsID FROM ' . VIEWUSERLIST_TABLE . ' WHERE isAuth=1 AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$aRow = $DB->queryFirstRow($aSQL);
	$isAuthPassport = ($aRow ? 1 : 0);
	break;

case '3':
	$aSQL = ' SELECT administratorsID FROM ' . APPEALUSERLIST_TABLE . ' WHERE isAuth=0 AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' AND surveyID = \'' . $_GET['surveyID'] . '\' ';
	$aRow = $DB->queryFirstRow($aSQL);
	$isAppPassport = ($aRow ? 1 : 0);
	break;
}

switch ($_GET['authStat']) {
case '0':
	$EnableQCoreClass->replace('c_0', 'class="cur"');

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '3':
		$SQL .= ' AND b.authStat =0 ';
		break;

	case '7':
		$SQL .= ' AND b.authStat =0 AND ( b.version = 0 OR b.version = \'' . $_SESSION['administratorsID'] . '\') ';
		break;
	}

	break;

case '3':
	$EnableQCoreClass->replace('c_3', 'class="cur"');

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
	case '3':
		$SQL .= ' AND b.authStat =3 ';
		break;

	case '7':
		$SQL .= ' AND (b.authStat =3 AND b.adminID = \'' . $_SESSION['administratorsID'] . '\') ';
		break;
	}

	break;

case '1000':
	$EnableQCoreClass->replace('c_1000', 'class="cur"');
	$hSQL = ' SELECT DISTINCT responseID FROM ' . DATA_TASK_TABLE . ' WHERE surveyID =\'' . $_GET['surveyID'] . '\' AND administratorsID = \'' . $_SESSION['administratorsID'] . '\' ';
	$hResult = $DB->query($hSQL);
	$myAuthArray = array();

	while ($hRow = $DB->queryArray($hResult)) {
		$myAuthArray[] = $hRow['responseID'];
	}

	if (count($myAuthArray) == 0) {
		$SQL .= ' AND b.responseID = 0 ';
	}
	else {
		$SQL .= ' AND b.responseID IN (' . implode(',', $myAuthArray) . ') ';
	}

	unset($myAuthArray);
	break;

case '1':
	$EnableQCoreClass->replace('c_1', 'class="cur"');
	$SQL .= ' AND b.authStat =1 ';
	break;

case '2':
	$EnableQCoreClass->replace('c_2', 'class="cur"');
	$SQL .= ' AND b.authStat =2 ';
	break;

case '4':
	$EnableQCoreClass->replace('c_4', 'class="cur"');
	$SQL .= ' AND b.authStat = 1 AND b.appStat != 0 ';
	break;

case 'all':
default:
	$EnableQCoreClass->replace('c_all', 'class="cur"');
	break;
}

$Result = $DB->query($SQL);
$totalResponseNum = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('totalResponseNum', $totalResponseNum);

if ($_POST['Action'] == 'querySubmit') {
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

	if (count($_POST['area']) != 0) {
		$thisAreaList = '';

		foreach ($_POST['area'] as $thisArea) {
			if (trim($thisArea) != '') {
				$thisAreaList .= '\'' . $thisArea . '\',';
			}
		}

		if ($thisAreaList != '') {
			$SQL .= ' AND b.area IN (' . substr($thisAreaList, 0, -1) . ') ';
			$SearchSQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE area != \'\' ORDER BY area ASC ';
			$Result = $DB->query($SearchSQL);
			$areaList = '';

			while ($AreaRow = $DB->queryArray($Result)) {
				if (in_array($AreaRow['area'], $_POST['area'])) {
					$areaList .= '<option value="' . $AreaRow['area'] . '" selected>' . $AreaRow['area'] . '</option>' . "\n" . '';
				}
				else {
					$areaList .= '<option value="' . $AreaRow['area'] . '">' . $AreaRow['area'] . '</option>' . "\n" . '';
				}
			}

			$EnableQCoreClass->replace('area_list', $areaList);
			$page_others .= '&area=' . str_replace('+', '%2B', base64_encode(substr($thisAreaList, 0, -1)));
		}
	}

	if (count($_POST['dataForm']) != 0) {
		$SQL .= ' AND b.dataSource IN (' . implode(',', $_POST['dataForm']) . ') ';

		foreach ($_POST['dataForm'] as $dataForm) {
			$EnableQCoreClass->replace('dataForm_' . $dataForm, 'selected');
		}

		$page_others .= '&dataForm=' . str_replace('+', '%2B', base64_encode(implode(',', $_POST['dataForm'])));
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
	$SQL .= ' AND ( b.administratorsName LIKE BINARY \'%' . $t_name . '%\' OR b.ipAddress  LIKE BINARY \'%' . $t_name . '%\') ';
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
	$theGetArea = explode(',', base64_decode($_GET['area']));
	$thisAreaList = '';

	foreach ($theGetArea as $thisArea) {
		if (trim($thisArea) != '') {
			$thisAreaList .= $thisArea . ',';
		}
	}

	if ($thisAreaList != '') {
		$SQL .= ' AND b.area IN (' . substr($thisAreaList, 0, -1) . ') ';
		$SearchSQL = ' SELECT DISTINCT area FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE area != \'\' ORDER BY area ASC ';
		$Result = $DB->query($SearchSQL);
		$areaList = '';

		while ($AreaRow = $DB->queryArray($Result)) {
			if (in_array('\'' . $AreaRow['area'] . '\'', $theGetArea)) {
				$areaList .= '<option value="' . $AreaRow['area'] . '" selected>' . $AreaRow['area'] . '</option>' . "\n" . '';
			}
			else {
				$areaList .= '<option value="' . $AreaRow['area'] . '">' . $AreaRow['area'] . '</option>' . "\n" . '';
			}
		}

		$EnableQCoreClass->replace('area_list', $areaList);
		$page_others .= '&area=' . str_replace('+', '%2B', base64_encode(substr($thisAreaList, 0, -1)));
	}
}

if (isset($_GET['dataForm']) && ($_GET['dataForm'] != '') && !$_POST['Action']) {
	$theGetDataForm = base64_decode($_GET['dataForm']);

	if ($theGetDataForm != '') {
		$SQL .= ' AND b.dataSource IN (' . $theGetDataForm . ') ';

		foreach (explode(',', $theGetDataForm) as $dataForm) {
			$EnableQCoreClass->replace('dataForm_' . $dataForm, 'selected');
		}

		$page_others .= '&dataForm=' . str_replace('+', '%2B', base64_encode($theGetDataForm));
	}
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
$EnableQCoreClass->replace('recNum', $recordCount);

if ($recordCount == 0) {
	$EnableQCoreClass->replace('isHaveRecordText', '<tr><td colspan=9 style="padding-left:10px"><span class=red>当前调查问卷未有回复数据，或者未存在查询条件的回复数据</span></td></tr>');
}
else {
	$EnableQCoreClass->replace('isHaveRecordText', '');
}

if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$pageID = (isset($_GET['pageID']) ? (int) $_GET['pageID'] : 1);
$ViewBackURL = $thisProg . '&pageID=' . $pageID . $page_others;
$_SESSION['ViewBackURL'] = $ViewBackURL;
$ExportSQL = trim(preg_replace('\'SELECT[^>]*?WHERE\'si', '', $SQL));
$_SESSION['dataSQL'] = base64_encode($ExportSQL);
$SQL .= ' ORDER BY responseID DESC LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('responseID', $Row['responseID']);

	if ($Sur_G_Row['projectType'] == 1) {
		$EnableQCoreClass->replace('administratorsName', $Row['administratorsName'] . '(' . $Row['taskID'] . ')');
	}
	else {
		$EnableQCoreClass->replace('administratorsName', $Row['administratorsName']);
	}

	$EnableQCoreClass->replace('ipAddress', $Row['ipAddress']);
	$EnableQCoreClass->replace('area', $Row['area']);
	$EnableQCoreClass->replace('joinTime', date('y-m-d H:i:s', $Row['joinTime']));
	$EnableQCoreClass->replace('createDate', $Row['joinTime']);
	$EnableQCoreClass->replace('overFlag', $Row['overFlag']);
	$EnableQCoreClass->replace('overFlag0', $Row['overFlag0']);

	if ($mainShowField == '') {
		$EnableQCoreClass->replace('qtnValue', '');
		$EnableQCoreClass->replace('isMainShowQtnValue', 'none');
	}
	else {
		$EnableQCoreClass->replace('qtnValue', cnsubstr(qlisttag($Row[$mainShowField], 1), 0, 20, 1));
		$EnableQCoreClass->replace('isMainShowQtnValue', '');
	}

	$EnableQCoreClass->replace('infoTitle', '');

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

	switch ($Row['authStat']) {
	case '0':
		$EnableQCoreClass->replace('authColor', '#ffffff');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;

	case '1':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/igreen.png) repeat-y top left');

		switch ($Row['appStat']) {
		case '0':
			$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
			break;

		default:
			switch ($Row['appStat']) {
			case '2':
				$EnableQCoreClass->replace('authStat', '<font color=red>申诉失败</font>');
				break;

			case '3':
				$EnableQCoreClass->replace('authStat', '申诉中');
				$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iorange.png) repeat-y top left');
				break;

			default:
				$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat'] . '_' . $Row['appStat']]);
				break;
			}

			break;
		}

		break;

	case '2':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/ired.png) repeat-y top left');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;

	case '3':
		$EnableQCoreClass->replace('authColor', '#ffffff url(../Images/iyellow.png) repeat-y top left');
		$EnableQCoreClass->replace('authStat', $lang['authStat_' . $Row['authStat']]);
		break;
	}

	$action = '';

	switch ($_SESSION['adminRoleType']) {
	case '3':
		$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
		$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';
		if (($Row['authStat'] == 1) && ($isAppPassport == 1) && (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID']))) {
			if ($Row['appStat'] == 0) {
				$appURL = $thisProg . '&Does=AppData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $appURL . '\'>' . $lang['list_action_app'] . '</a> ';
			}

			if (($Row['appStat'] == 2) && ($Sur_G_Row['isFailReApp'] == 1)) {
				$appURL = $thisProg . '&Does=AppData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $appURL . '\'>' . $lang['list_action_app'] . '</a> ';
			}
		}

		break;

	case '1':
	case '2':
	case '5':
		switch ($Row['authStat']) {
		case '0':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';

			if (in_array($Row['overFlag'], array(1, 3))) {
				if (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID'])) {
					$authURL = $thisProg . '&Does=AuthData&responseID=' . $Row['responseID'];
					$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_auth'] . '</a> ';
				}
				else {
					$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
					$OptRow = $DB->queryFirstRow($OptSQL);
					$noteInfo = '该数据已被[' . str_replace('\\', '\\\\', _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType'])) . ']审核锁定，等待其作出审核结论';
					$action .= '<a href=\'javascript:void(0);\' onclick="javascript:alert(\'' . $noteInfo . '\')">' . $lang['list_action_auth'] . '</a> ';
					$EnableQCoreClass->replace('infoTitle', 'title=\'' . str_replace('\\\\', '\\', $noteInfo) . '\' style=\'color:red\'');
				}
			}
			else if ($Row['overFlag'] != 2) {
				$modiURL = $thisProg . '&Does=ModiData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $modiURL . '\'>' . $lang['list_action_modi'] . '</a> ';
			}

			$deleteURL = $thisProg . '&Does=Delete&overFlag=' . $Row['overFlag'] . '&responseID=' . $Row['responseID'] . '&createDate=' . $Row['joinTime'] . '&area=' . $Row['area'];
			$action .= '<a href=\'' . $deleteURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['list_action_dele'] . '</a> ';
			break;

		case '1':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';

			if ($Row['appStat'] == 3) {
				if (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID'])) {
					$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $Row['responseID'];
					$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_appauth'] . '</a> ';
				}
				else {
					$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
					$OptRow = $DB->queryFirstRow($OptSQL);
					$noteInfo = '该数据已被[' . str_replace('\\', '\\\\', _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType'])) . ']申诉核准锁定，等待其作出申诉核准结论';
					$action .= '<a href=\'javascript:void(0);\' onclick="javascript:alert(\'' . $noteInfo . '\')">' . $lang['list_action_appauth'] . '</a> ';
					$EnableQCoreClass->replace('infoTitle', 'title=\'' . str_replace('\\\\', '\\', $noteInfo) . '\' style=\'color:red\'');
				}
			}
			else {
				if ($Row['overFlag'] != 2) {
					$modiURL = $thisProg . '&Does=ModiData&responseID=' . $Row['responseID'];
					$action .= '<a href=\'' . $modiURL . '\'>' . $lang['list_action_modi'] . '</a> ';
				}

				if ($Row['version'] == 0) {
					$cancelType = ($Row['appStat'] != 0 ? 1 : 2);
					$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=' . $cancelType;
					$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
				}
				else {
					$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
					$OptRow = $DB->queryFirstRow($OptSQL);
					$noteInfo = '该数据正在[' . str_replace('\\', '\\\\', _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType'])) . ']申诉过程中，等待其最终提交申诉';
					$action .= '<a href=\'javascript:void(0);\' onclick="javascript:alert(\'' . $noteInfo . '\')">' . $lang['list_action_cancel'] . '</a> ';
					$EnableQCoreClass->replace('infoTitle', 'title=\'' . str_replace('\\\\', '\\', $noteInfo) . '\' style=\'color:red\'');
				}
			}

			$deleteURL = $thisProg . '&Does=Delete&overFlag=' . $Row['overFlag'] . '&responseID=' . $Row['responseID'] . '&createDate=' . $Row['joinTime'] . '&area=' . $Row['area'];
			$action .= '<a href=\'' . $deleteURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['list_action_dele'] . '</a> ';
			break;

		case '2':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';

			if ($Row['overFlag'] != 2) {
				$modiURL = $thisProg . '&Does=ModiData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $modiURL . '\'>' . $lang['list_action_modi'] . '</a> ';
			}

			$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=2';
			$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
			$deleteURL = $thisProg . '&Does=Delete&overFlag=' . $Row['overFlag'] . '&responseID=' . $Row['responseID'] . '&createDate=' . $Row['joinTime'] . '&area=' . $Row['area'];
			$action .= '<a href=\'' . $deleteURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['list_action_dele'] . '</a> ';
			break;

		case '3':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';
			if (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID'])) {
				$authURL = $thisProg . '&Does=AuthData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_auth'] . '</a> ';
			}
			else {
				$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
				$OptRow = $DB->queryFirstRow($OptSQL);
				$noteInfo = '该数据已被[' . str_replace('\\', '\\\\', _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType'])) . ']审核锁定，等待其作出审核结论';
				$action .= '<a href=\'javascript:void(0);\' onclick="javascript:alert(\'' . $noteInfo . '\')">' . $lang['list_action_auth'] . '</a> ';
				$EnableQCoreClass->replace('infoTitle', 'title=\'' . str_replace('\\\\', '\\', $noteInfo) . '\' style=\'color:red\'');
			}

			if ($Row['version'] == 0) {
				$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=2';
				$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
			}
			else {
				$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
				$OptRow = $DB->queryFirstRow($OptSQL);
				$noteInfo = '该数据已被[' . str_replace('\\', '\\\\', _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType'])) . ']审核锁定，等待其作出审核结论';
				$action .= '<a href=\'javascript:void(0);\' onclick="javascript:alert(\'' . $noteInfo . '\')">' . $lang['list_action_cancel'] . '</a> ';
				$EnableQCoreClass->replace('infoTitle', 'title=\'' . str_replace('\\\\', '\\', $noteInfo) . '\' style=\'color:red\'');
			}

			$deleteURL = $thisProg . '&Does=Delete&overFlag=' . $Row['overFlag'] . '&responseID=' . $Row['responseID'] . '&createDate=' . $Row['joinTime'] . '&area=' . $Row['area'];
			$action .= '<a href=\'' . $deleteURL . '\' onclick="return window.confirm(\'' . $lang['list_action_dele_confim'] . '\')">' . $lang['list_action_dele'] . '</a> ';
			break;
		}

		break;

	case '7':
		switch ($Row['authStat']) {
		case '0':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';
			if (($isAuthPassport == 1) && in_array($Row['overFlag'], array(1, 3))) {
				if (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID'])) {
					$authURL = $thisProg . '&Does=AuthData&responseID=' . $Row['responseID'];
					$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_auth'] . '</a> ';
				}
				else {
					$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
					$OptRow = $DB->queryFirstRow($OptSQL);
					$noteInfo = '该数据已被[' . _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType']) . ']审核锁定，等待其作出审核结论';
					$EnableQCoreClass->replace('infoTitle', 'title=\'' . $noteInfo . '\' style=\'color:red\'');
				}
			}

			break;

		case '1':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';

			if ($Row['appStat'] == 3) {
				if ($isAppAuthPassport == 1) {
					if (($Row['version'] == 0) || ($Row['version'] == $_SESSION['administratorsID'])) {
						$authURL = $thisProg . '&Does=AuthAppData&responseID=' . $Row['responseID'];
						$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_appauth'] . '</a> ';
					}
					else {
						$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
						$OptRow = $DB->queryFirstRow($OptSQL);
						$noteInfo = '该数据已被[' . _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType']) . ']申诉核准锁定，等待其作出申诉核准结论';
						$EnableQCoreClass->replace('infoTitle', 'title=\'' . $noteInfo . '\' style=\'color:red\'');
					}
				}
			}
			else {
				$cSQL = ' SELECT administratorsID FROM ' . DATA_TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $Row['responseID'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
				$cRow = $DB->queryFirstRow($cSQL);
				$isCancelPassport = ($cRow['administratorsID'] == $_SESSION['administratorsID'] ? 1 : 0);

				if ($isCancelPassport == 1) {
					if ($Row['version'] == 0) {
						$cancelType = ($Row['appStat'] != 0 ? 1 : 2);
						$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=' . $cancelType;
						$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
					}
					else {
						$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID = \'' . $Row['version'] . '\' ';
						$OptRow = $DB->queryFirstRow($OptSQL);
						$noteInfo = '该数据正在[' . _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType']) . ']申诉过程中，等待其最终提交申诉';
						$EnableQCoreClass->replace('infoTitle', 'title=\'' . $noteInfo . '\' style=\'color:red\'');
					}
				}
			}

			break;

		case '2':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';
			$cSQL = ' SELECT administratorsID FROM ' . DATA_TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $Row['responseID'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
			$cRow = $DB->queryFirstRow($cSQL);
			$isCancelPassport = ($cRow['administratorsID'] == $_SESSION['administratorsID'] ? 1 : 0);

			if ($isCancelPassport == 1) {
				$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=2';
				$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
			}

			break;

		case '3':
			$viewURL = $thisProg . '&Does=View&responseID=' . $Row['responseID'];
			$action .= '<a href=\'' . $viewURL . '\'>' . $lang['list_action_view'] . '</a> ';

			if ($Row['adminID'] == $_SESSION['administratorsID']) {
				$authURL = $thisProg . '&Does=AuthData&responseID=' . $Row['responseID'];
				$action .= '<a href=\'' . $authURL . '\' onclick="return window.confirm(\'' . $lang['list_action_auth_confim'] . '\')">' . $lang['list_action_auth'] . '</a> ';
			}
			else {
				$cSQL = ' SELECT administratorsID FROM ' . DATA_TASK_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $Row['responseID'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
				$cRow = $DB->queryFirstRow($cSQL);
				$isCancelPassport = ($cRow['administratorsID'] == $_SESSION['administratorsID'] ? 1 : 0);
				if (($isCancelPassport == 1) && ($Row['version'] == 0)) {
					$cancelURL = $thisProg . '&Does=CancelAuthData&responseID=' . $Row['responseID'] . '&cancelType=2';
					$action .= '<a href=\'' . $cancelURL . '\' onclick="return window.confirm(\'' . $lang['list_action_cancel_confim'] . '\')">' . $lang['list_action_cancel'] . '</a> ';
				}
			}

			break;
		}

		break;

	default:
		$action .= '&nbsp;';
		break;
	}

	$EnableQCoreClass->replace('action', $action);
	$EnableQCoreClass->parse('list', 'LIST', true);
}

if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '7')) {
	$EnableQCoreClass->replace('dataImportURL', 'javascript:void(0);');
	$EnableQCoreClass->replace('isImport', 'none');
	$EnableQCoreClass->replace('isModiAlias', 'none');
}
else {
	$EnableQCoreClass->replace('isModiAlias', '');
	$EnableQCoreClass->replace('isImport', '');
}

if (($_SESSION['adminRoleType'] == '3') && ($Sur_G_Row['isExportData'] == 1)) {
	$EnableQCoreClass->replace('isExportData', 'none');
	$EnableQCoreClass->replace('exportTraceURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('exportExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('spssExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('labelExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('dataImportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('uploadExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('isHaveUpload', 'none');
	$EnableQCoreClass->replace('uploadUpdateURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['no_auth_export_data'] . '\');');
	$EnableQCoreClass->replace('isUploadUpdate', 'none');
}
else {
	$EnableQCoreClass->replace('isExportData', '');

	if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('exportTraceURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('exportExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('spssExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('labelExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('dataImportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('uploadExportURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('isHaveUpload', 'none');
		$EnableQCoreClass->replace('uploadUpdateURL', 'javascript:void(0); onclick=javascript:alert(\'' . $lang['pls_register_soft'] . '\');');
		$EnableQCoreClass->replace('isUploadUpdate', 'none');
	}
	else {
		$traceURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Export/Export.trace.qtn.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 480, null, null,\'' . $lang['export_variable'] . $lang['export_trace_data'] . '\')"';
		$EnableQCoreClass->replace('exportTraceURL', $traceURL);
		$exportURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Export/Export.result.qtn.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, null, null,\'' . $lang['export_variable'] . $lang['export_excel_data'] . '\')"';
		$EnableQCoreClass->replace('exportExportURL', $exportURL);
		$spssURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Export/Export.spss.qtn.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, null, null,\'' . $lang['export_variable'] . $lang['export_spss_data'] . '\')"';
		$EnableQCoreClass->replace('spssExportURL', $spssURL);
		$labelURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Export/Export.label.qtn.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, null, null,\'' . $lang['export_variable'] . $lang['export_label_text'] . '\')"';
		$EnableQCoreClass->replace('labelExportURL', $labelURL);
		$dataImportURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Import/ImportData.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, refreshParent, null,\'' . $lang['import_excel_data'] . '\')"';
		$EnableQCoreClass->replace('dataImportURL', $dataImportURL);
		$HSQL = ' SELECT questionName,questionID FROM ' . QUESTION_TABLE . ' WHERE questionType = \'11\' AND surveyID = \'' . $_GET['surveyID'] . '\' LIMIT 1 ';
		$HRow = $DB->queryFirstRow($HSQL);
		if ($HRow || ($Sur_G_Row['isRecord'] == 1) || ($Sur_G_Row['isRecord'] == 2)) {
			$uploadExportURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Export/Export.upload.qtn.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, null, null,\'' . $lang['export_variable'] . $lang['export_upload_file'] . '\')"';
			$EnableQCoreClass->replace('uploadExportURL', $uploadExportURL);
			$EnableQCoreClass->replace('isHaveUpload', '');

			if ($Sur_G_Row['custDataPath'] == '') {
				$EnableQCoreClass->replace('uploadUpdateURL', 'javascript:void(0);');
				$EnableQCoreClass->replace('isUploadUpdate', 'none');
			}
			else {
				switch ($_SESSION['adminRoleType']) {
				case '1':
				case '2':
				case '5':
					$uploadUpdateURL = '"javascript:void(0)" onclick="javascript:showPopWin(\'../Import/ImportUpload.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '\', 850, 460, refreshParent, null,\'' . $lang['import_upload_data'] . '\')"';
					$EnableQCoreClass->replace('uploadUpdateURL', $uploadUpdateURL);
					$EnableQCoreClass->replace('isUploadUpdate', '');
					break;

				default:
					$EnableQCoreClass->replace('uploadUpdateURL', 'javascript:void(0);');
					$EnableQCoreClass->replace('isUploadUpdate', 'none');
					break;
				}
			}
		}
		else {
			$EnableQCoreClass->replace('uploadExportURL', 'javascript:void(0);');
			$EnableQCoreClass->replace('isHaveUpload', 'none');
			$EnableQCoreClass->replace('uploadUpdateURL', 'javascript:void(0);');
			$EnableQCoreClass->replace('isUploadUpdate', 'none');
		}
	}
}

$EnableQCoreClass->replace('isEvalUsers', $License['isEvalUsers']);
include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('ResultList', 'ResultListFile');
$EnableQCoreClass->output('ResultList');

?>
