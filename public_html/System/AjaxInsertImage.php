<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
_checkroletype('1|2|5');

if ($_POST['picType'] == 1) {
	$SupportUploadFileType = 'jpg|gif|png|swf';

	if ($_FILES['picImage']['name'] != '') {
		$ImageFile_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/' . $_SESSION['administratorsID'] . '/';
		createdir($ImageFile_DIR_Name);
		$tmpExt = explode('.', $_FILES['picImage']['name']);
		$tmpNum = count($tmpExt) - 1;
		$extension = strtolower($tmpExt[$tmpNum]);
		$FileTypeExt = explode('|', $SupportUploadFileType);
		if (is_uploaded_file($_FILES['picImage']['tmp_name']) && in_array($extension, $FileTypeExt)) {
			$picName = date('ymdHis', time()) . rand(1, 999);
			$newFilename = $picName . '.' . $extension;
			$FullPhyFileName = $ImageFile_DIR_Name . $newFilename;
			copy($_FILES['picImage']['tmp_name'], $FullPhyFileName);
			$picFileName = 'PerUserData/' . $_SESSION['administratorsID'] . '/' . $newFilename;

			if ($extension == 'swf') {
				$FlashSize = getimagesize($FullPhyFileName);
				$FlashWidth = $FlashSize[0];
				$FlashHeight = $FlashSize[1];
				echo '<script>parent.AjaxCallBack(\'' . $picFileName . '\',2,' . $FlashWidth . ',' . $FlashHeight . ');</script>';
			}
			else {
				echo '<script>parent.AjaxCallBack(\'' . $picFileName . '\',1,0,0);</script>';
			}
		}
		else {
			echo '<script>parent.alert(\'' . $lang['upload_error_type'] . '\');</script>';
		}
	}
	else {
		echo '<script>parent.alert(\'' . $lang['upload_error_type'] . '\');</script>';
	}
}
else {
	$theFileURL = trim($_POST['wmvpath']);
	$theFileName = explode('.', basename($theFileURL));
	$tmpNum = count($theFileName) - 1;
	$extension = strtolower($theFileName[$tmpNum]);

	switch ($extension) {
	case 'flv':
	case 'mp4':
		echo '<script>parent.AjaxCallBack(\'' . $theFileURL . '\',4,' . $_POST['wmvwidth'] . ',' . $_POST['wmvheight'] . ');</script>';
		break;
	}
}

?>
