<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
include_once ROOT_PATH . 'Functions/Functions.pic.inc.php';

if ($_POST['Action'] == 'EditUserSubmit') {
	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET projectType=\'' . $_POST['projectType'] . '\',isOnline0View=\'' . $_POST['isOnline0View'] . '\',isViewAuthInfo=\'' . $_POST['isViewAuthInfo'] . '\',isOnline0Auth=\'' . $_POST['isOnline0Auth'] . '\',isViewAuthData=\'' . $_POST['isViewAuthData'] . '\',isFailReApp=\'' . $_POST['isFailReApp'] . '\',isExportData=\'' . $_POST['isExportData'] . '\',isImportData=\'' . $_POST['isImportData'] . '\',isDeleteData=\'' . $_POST['isDeleteData'] . '\',isModiData=\'' . $_POST['isModiData'] . '\',isOneData=\'' . $_POST['isOneData'] . '\',custTel=\'' . trim($_POST['custTel']) . '\' ';

	switch ($_SESSION['adminRoleType']) {
	case 6:
		break;

	default:
		$SQL .= ',custDataPath=\'' . trim($_POST['custDataPath']) . '\' ';

		if (trim($_POST['custDataPath']) != '') {
			$hSQL = ' SELECT custDataPath FROM ' . SURVEY_TABLE . ' WHERE custDataPath=\'' . trim($_POST['custDataPath']) . '\' AND surveyID != \'' . $_POST['surveyID'] . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				_showerror('���ݼ�����', '���ݼ������Զ���ĸ����ļ�����洢·�����Ѵ���');
			}

			if (trim($_POST['custDataPath']) != trim($_POST['oriDataPath'])) {
				switch ($Sure_Row['status']) {
				case 1:
				case 2:
					$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' ';
					$R_Row = $DB->queryFirstRow($R_SQL);

					if (0 < $R_Row['resultNum']) {
						_showerror('״̬����', '״̬�����޸ĸ����ļ�����洢·�����ʾ����лظ����ݵ�ǰ���½��ᵼ�����ݻ��ң����Ĳ��������ܼ�����');
					}

					break;
				}

				createdir(ROOT_PATH . 'PerUserData/user/' . trim($_POST['custDataPath']) . '/');
			}
		}
		else if (trim($_POST['custDataPath']) != trim($_POST['oriDataPath'])) {
			switch ($Sure_Row['status']) {
			case 1:
			case 2:
				$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' ';
				$R_Row = $DB->queryFirstRow($R_SQL);

				if (0 < $R_Row['resultNum']) {
					_showerror('״̬����', '״̬�����޸ĸ����ļ�����洢·�����ʾ����лظ����ݵ�ǰ���½��ᵼ�����ݻ��ң����Ĳ��������ܼ�����');
				}

				break;
			}

			deletedir(ROOT_PATH . 'PerUserData/user/' . trim($_POST['oriDataPath']) . '/');
		}

		break;
	}

	if ($_POST['projectType'] == '1') {
		$SQL .= ',projectOwner=\'' . $_POST['projectOwner'] . '\' ';
	}
	else {
		$SQL .= ',projectOwner=\'0\' ';
	}

	if ($_FILES['custLogo']['name'] != '') {
		$logoPath = $Config['absolutenessPath'] . '/PerUserData/logo/';
		createdir($logoPath);
		$SupportUploadFileType = 'jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG';
		$logoSQL = _picfileupload('custLogo', $SupportUploadFileType, $logoPath, $isEdit = true);
		$SQL .= $logoSQL;
	}

	$SQL .= ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	if (($_POST['projectOwner'] != $_POST['ori_projectOwner']) || ($_POST['projectType'] == '2')) {
		$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND isAuth=0 ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
		$DB->query($SQL);
	}

	if ($_POST['projectType'] == '2') {
		$SQL = ' DELETE FROM ' . APPEALUSERLIST_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND isAuth=1 ';
		$DB->query($SQL);
	}

	writetolog($lang['edit_survey_user'] . ':' . $_POST['surveyTitle']);
	_showsucceed($lang['edit_survey_user'] . ':' . $_POST['surveyTitle'], 'ShowSurveyList.php?Action=EditUser&surveyID=' . $_POST['surveyID']);
}

$EnableQCoreClass->setTemplateFile('SurveyUserEditPageFile', 'SurveyUserEdit.html');
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -25);
$EnableQCoreClass->replace('loginURL', $All_Path . 'Customer/index.php?qid=' . $Row['surveyID']);
$EnableQCoreClass->replace('custLogo', $Row['custLogo']);

if ($Row['custLogo'] == '') {
	$EnableQCoreClass->replace('custLogoFile', '<font color=red>��Logo�ļ�</font>');
}
else {
	$EnableQCoreClass->replace('custLogoFile', '<a href="../PerUserData/logo/' . trim($Row['custLogo']) . '" target=_blank>' . trim($Row['custLogo']) . '</a>');
}

$EnableQCoreClass->replace('projectType_' . $Row['projectType'], 'checked');
$SQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE fatherId = 0 AND groupType=2 ORDER BY createDate ASC ';
$uResult = $DB->query($SQL);
$projectOwnerList = '';



while ($uRow = $DB->queryArray($uResult)) {
	if ($uRow['userGroupID'] == $Row['projectOwner']) {
		$projectOwnerList .= '<option value=\'' . $uRow['userGroupID'] . '\' selected>' . $uRow['userGroupName'] . '</option>' . "\n" . '';
	}
	else {
		$projectOwnerList .= '<option value=\'' . $uRow['userGroupID'] . '\'>' . $uRow['userGroupName'] . '</option>' . "\n" . '';
	}
}

$EnableQCoreClass->replace('projectOwnerList', $projectOwnerList);
$EnableQCoreClass->replace('projectOwner', $Row['projectOwner']);
$EnableQCoreClass->replace('absPath', $Config['absolutenessPath']);
$EnableQCoreClass->replace('custDataPath', $Row['custDataPath']);
$EnableQCoreClass->replace('custTel', $Row['custTel']);

switch ($_SESSION['adminRoleType']) {
case 6:
	$EnableQCoreClass->replace('isAdminRole6', 'disabled');
	break;

default:
	$EnableQCoreClass->replace('isAdminRole6', '');
	break;
}

if ($Row['isOnline0View'] == '1') {
	$EnableQCoreClass->replace('isOnline0View', 'checked');
}
else {
	$EnableQCoreClass->replace('isOnline0View', '');
}

if ($Row['isOnline0Auth'] == '1') {
	$EnableQCoreClass->replace('isOnline0Auth', 'checked');
}
else {
	$EnableQCoreClass->replace('isOnline0Auth', '');
}

if ($Row['isViewAuthData'] == '1') {
	$EnableQCoreClass->replace('isViewAuthData', 'checked');
}
else {
	$EnableQCoreClass->replace('isViewAuthData', '');
}

if ($Row['isViewAuthInfo'] == '1') {
	$EnableQCoreClass->replace('isViewAuthInfo', 'checked');
}
else {
	$EnableQCoreClass->replace('isViewAuthInfo', '');
}

if ($Row['isExportData'] == '1') {
	$EnableQCoreClass->replace('isExportData', 'checked');
}
else {
	$EnableQCoreClass->replace('isExportData', '');
}

if ($Row['isImportData'] == '1') {
	$EnableQCoreClass->replace('isImportData', 'checked');
}
else {
	$EnableQCoreClass->replace('isImportData', '');
}

if ($Row['isOneData'] == '1') {
	$EnableQCoreClass->replace('isOneData', 'checked');
}
else {
	$EnableQCoreClass->replace('isOneData', '');
}

if ($Row['isModiData'] == '1') {
	$EnableQCoreClass->replace('isModiData', 'checked');
}
else {
	$EnableQCoreClass->replace('isModiData', '');
}

if ($Row['isDeleteData'] == '1') {
	$EnableQCoreClass->replace('isDeleteData', 'checked');
}
else {
	$EnableQCoreClass->replace('isDeleteData', '');
}

if ($Row['isFailReApp'] == '1') {
	$EnableQCoreClass->replace('isFailReApp', 'checked');
}
else {
	$EnableQCoreClass->replace('isFailReApp', '');
}

$SQL = ' SELECT isAdmin,userGroupID,administratorsName,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID=\'' . $Row['administratorsID'] . '\' ';
$AuthRow = $DB->queryFirstRow($SQL);

if (!$AuthRow) {
	$EnableQCoreClass->replace('ownerUser', $lang['deleted_user']);
}
else {
	$EnableQCoreClass->replace('ownerUser', _getuserallname($AuthRow['administratorsName'], $AuthRow['userGroupID'], $AuthRow['groupType']));
}

$optUserList = array();
$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =1 ORDER BY administratorsID ASC ';
$OptResult = $DB->query($OptSQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$optUserList[] = _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType']);
}

$EnableQCoreClass->replace('adminUsers', implode(' , ', array_unique($optUserList)));
$theFatherGroup = _getupgroupnode($AuthRow['userGroupID']);
$theFatherGroup[] = $AuthRow['userGroupID'];
$optUserList = array();
$OptSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =5 AND userGroupID IN (' . implode(',', $theFatherGroup) . ') ORDER BY administratorsID ASC ';
$OptResult = $DB->query($OptSQL);

while ($OptRow = $DB->queryArray($OptResult)) {
	$optUserList[] = _getuserallname($OptRow['administratorsName'], $OptRow['userGroupID'], $OptRow['groupType']);
}

$EnableQCoreClass->replace('superUsers', implode(' , ', array_unique($optUserList)));
unset($optUserList);
$inputUserList = array();
$InSQL = ' SELECT a.administratorsName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . INPUTUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' ORDER BY b.administratorsID ASC ';
$InResult = $DB->query($InSQL);

while ($InRow = $DB->queryArray($InResult)) {
	$inputUserList[] = _getuserallname($InRow['administratorsName'], $InRow['userGroupID'], $InRow['groupType']);
}

$EnableQCoreClass->replace('inputUserList', implode(' , ', array_unique($inputUserList)));
unset($inputUserList);
$viewUserList = array();
$ViewSQL = ' SELECT a.administratorsName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . VIEWUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' AND a.isAdmin=3  AND a.groupType =1 AND b.isAuth=0 ORDER BY b.administratorsID ASC ';
$ViewResult = $DB->query($ViewSQL);

while ($ViewRow = $DB->queryArray($ViewResult)) {
	$viewUserList[] = _getuserallname($ViewRow['administratorsName'], $ViewRow['userGroupID'], $ViewRow['groupType']);
}

$EnableQCoreClass->replace('viewUserList', implode(' , ', array_unique($viewUserList)));
unset($viewUserList);
$authUserList = array();
$ViewSQL = ' SELECT a.administratorsName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . VIEWUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' AND a.isAdmin = 7  AND b.isAuth=1 ORDER BY b.administratorsID ASC ';
$ViewResult = $DB->query($ViewSQL);

while ($ViewRow = $DB->queryArray($ViewResult)) {
	$authUserList[] = _getuserallname($ViewRow['administratorsName'], $ViewRow['userGroupID'], $ViewRow['groupType']);
}

$EnableQCoreClass->replace('authUserList', implode(' , ', array_unique($authUserList)));
unset($authUserList);
$appealUserList = array();
$ViewSQL = ' SELECT a.administratorsName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . APPEALUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' AND a.isAdmin=3 AND a.groupType =2 AND b.isAuth=0 ORDER BY b.administratorsID ASC ';
$ViewResult = $DB->query($ViewSQL);

while ($ViewRow = $DB->queryArray($ViewResult)) {
	$appealUserList[] = _getuserallname($ViewRow['administratorsName'], $ViewRow['userGroupID'], $ViewRow['groupType']);
}

$EnableQCoreClass->replace('appealUserList', implode(' , ', array_unique($appealUserList)));
unset($appealUserList);
$appealAuthUserList = array();
$ViewSQL = ' SELECT a.administratorsName,a.userGroupID,a.groupType FROM ' . ADMINISTRATORS_TABLE . ' a, ' . APPEALUSERLIST_TABLE . ' b WHERE a.administratorsID = b.administratorsID AND b.surveyID =\'' . $_GET['surveyID'] . '\' AND a.isAdmin=7 AND b.isAuth=1 ORDER BY b.administratorsID ASC ';
$ViewResult = $DB->query($ViewSQL);

while ($ViewRow = $DB->queryArray($ViewResult)) {
	$appealAuthUserList[] = _getuserallname($ViewRow['administratorsName'], $ViewRow['userGroupID'], $ViewRow['groupType']);
}

$EnableQCoreClass->replace('appealAuthUserList', implode(' , ', array_unique($appealAuthUserList)));
unset($appealAuthUserList);
$EnableQCoreClass->parse('SurveyUserEditPage', 'SurveyUserEditPageFile');
$EnableQCoreClass->output('SurveyUserEditPage', false);

?>
