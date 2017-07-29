<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'License/License.xml';
_checkroletype(1);
$thisProg = 'License.php';

if ($_POST['Action'] == 'UploadLicenseSubmit') {
	$tmpExt = explode('.', $_FILES['xmlFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	if (is_uploaded_file($_FILES['xmlFile']['tmp_name']) && ($extension == 'xml')) {
		copy($_FILES['xmlFile']['tmp_name'], $Config['absolutenessPath'] . 'License/License.xml');
		writetolog($lang['upload_license_file']);
		_showsucceed($lang['upload_license_file'], $thisProg);
	}
	else {
		_showerror($lang['error_system'], $lang['license_file_type_error']);
	}
}

$EnableQCoreClass->setTemplateFile('LicenseFile', 'License.html');

if ($License['Limited'] == 0) {
	$EnableQCoreClass->replace('licenseNum', $lang['no_limited_soft']);
}
else {
	$EnableQCoreClass->replace('licenseNum', $License['LimitedNum']);
}

$EnableQCoreClass->replace('MasterAddress', $License['MasterAddress']);
$EnableQCoreClass->replace('MinorAddress', $License['MinorAddress']);
$EnableQCoreClass->replace('UserCompany', $License['UserCompany']);

if ($License['LimitedTime'] == 'N/A') {
	$EnableQCoreClass->replace('LimitedTime', $lang['no_limited_soft']);
}
else if ($License['isEvalUsers'] == 1) {
	$SQL = ' SELECT licensetime FROM ' . BASESETTING_TABLE . ' LIMIT 1 ';
	$SerialRow = $DB->queryFirstRow($SQL);

	if (!$SerialRow) {
		$SQL = ' SELECT joinTime FROM ' . SURVEY_TABLE . ' ORDER BY joinTime ASC LIMIT 0,1 ';
		$TimeRow = $DB->queryFirstRow($SQL);

		if (!$TimeRow) {
			$TimeNow = time();
		}
		else {
			$TimeNow = $TimeRow['joinTime'];
		}

		$AfterEncTime = base64_encode(base64_encode($TimeNow));
		$SQL = ' UPDATE ' . BASESETTING_TABLE . ' SET licensetime = \'' . $AfterEncTime . '\' ';
		$DB->query($SQL);
	}
	else {
		$TimeNow = base64_decode(base64_decode($SerialRow['licensetime']));
	}

	$EnableQCoreClass->replace('LimitedTime', date('Y-m-d', $TimeNow + (86400 * $License['LimitedTime'])));
}
else {
	$EnableQCoreClass->replace('LimitedTime', $License['LimitedTime']);
}

$LicensePage = $EnableQCoreClass->parse('LicensePage', 'LicenseFile');
echo $LicensePage;

?>
