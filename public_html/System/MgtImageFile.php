<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$thisProg = 'MgtImageFile.php';
_checkroletype('1|2|5');
$ImageFile_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/' . $_SESSION['administratorsID'] . '/';

if ($_POST['Action'] == 'AddImageSubmit') {
	if (!is_dir($ImageFile_DIR_Name)) {
		mkdir($ImageFile_DIR_Name, 511);
		copy(ROOT_PATH . 'Help/index.html', $ImageFile_DIR_Name . 'index.html');
	}

	$SupportUploadFileType = 'jpg|jpeg|gif|bmp|png|swf|js|css|mp4|flv|apk';
	_uploadfile('ImageFile1', $SupportUploadFileType, $ImageFile_DIR_Name);
	_uploadfile('ImageFile2', $SupportUploadFileType, $ImageFile_DIR_Name);
	_uploadfile('ImageFile3', $SupportUploadFileType, $ImageFile_DIR_Name);
	_uploadfile('ImageFile4', $SupportUploadFileType, $ImageFile_DIR_Name);
	_uploadfile('ImageFile5', $SupportUploadFileType, $ImageFile_DIR_Name);
	writetolog($lang['add_image_file']);
	_showmessage($lang['add_image_file'], true);
}

if ($_GET['Action'] == 'DeleteImageFile') {
	$theFileName = base64_decode($_GET['Flag']);
	$tmpExt = explode('.', $theFileName);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$SupportUploadFileType = 'jpg|jpeg|gif|bmp|png|swf|js|css|mp4|flv|apk';
	$FileTypeExt = explode('|', $SupportUploadFileType);

	if (in_array($extension, $FileTypeExt)) {
		if (file_exists($ImageFile_DIR_Name . $theFileName)) {
			@unlink($ImageFile_DIR_Name . $theFileName);
			writetolog($lang['dele_image_file'] . ':' . $theFileName);
		}
	}

	_showsucceed($lang['dele_image_file'] . ':' . $theFileName, $thisProg);
}

if ($_GET['Action'] == 'ViewImageFile') {
	$theFileName = base64_decode($_GET['Flag']);
	$dimension = @getimagesize($ImageFile_DIR_Name . $theFileName);
	if ((200 < $dimension[0]) || (300 < $dimension[1])) {
		if ((300 / $dimension[1]) < (200 / $dimension[0])) {
			$rate = 300 / $dimension[1];
		}
		else {
			$rate = 200 / $dimension[0];
		}

		$width = intval($rate * $dimension[0]);
		$height = intval($rate * $dimension[1]);
	}
	else {
		$width = $dimension[0];
		$height = $dimension[1];
	}

	$tmpExt = explode('.', trim($theFileName));
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);

	if ($extension != 'swf') {
		$PicStr = '<img src=../PerUserData/' . $_SESSION['administratorsID'] . '/' . trim($theFileName) . ' border=0 width=\'' . $width . '\' height=\'' . $height . '\'> <br>' . cnsubstr($theFileName, 0, 11, 1);
	}
	else {
		$PicStr = '<embed pluginspage=http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash src=../PerUserData/' . $_SESSION['administratorsID'] . '/' . trim($theFileName) . ' width=\'' . $width . '\' height=\'' . $height . '\' type=application/x-shockwave-flash quality=high></embed>';
	}

	echo $PicStr;
	exit();
}

if ($_GET['Action'] == 'AddImageFile') {
	$EnableQCoreClass->setTemplateFile('PanelAddImage', 'ImageAdd.html');
	$EnableQCoreClass->replace('backImageURL', $thisProg);
	$EnableQCoreClass->replace('FileURL', 'PerUserData/' . $_SESSION['administratorsID']);
	$EnableQCoreClass->parse('AddImage', 'PanelAddImage');
	$EnableQCoreClass->output('AddImage');
}

$EnableQCoreClass->setTemplateFile('ImageList', 'ImageList.html');
$EnableQCoreClass->set_CycBlock('ImageList', 'IMAGEFILE', 'ImageFile');
$EnableQCoreClass->replace('ImageFile', '');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -23) . 'PerUserData/' . $_SESSION['administratorsID'] . '/';
$EnableQCoreClass->replace('fileDirName', $All_Path);
$EnableQCoreClass->replace('FileURL', 'PerUserData/' . $_SESSION['administratorsID']);
$recNum = 0;

if (is_dir($ImageFile_DIR_Name)) {
	if ($ImageDir = opendir($ImageFile_DIR_Name)) {
		while (($ImageFile = readdir($ImageDir)) !== false) {
			if (($ImageFile == '.') || ($ImageFile == '..')) {
				continue;
			}
			else {
				$EnableQCoreClass->replace('fileName', $ImageFile);
				$EnableQCoreClass->replace('fileSize', number_format(filesize($ImageFile_DIR_Name . $ImageFile) / 1024, 2));
			}

			if (filesize($ImageFile_DIR_Name . $ImageFile) == 0) {
				$EnableQCoreClass->replace('isDir', 'none');
				$EnableQCoreClass->replace('dirName', '<TD align=center>&nbsp;&nbsp;' . $lang['is_file_dir'] . '</TD>');
			}
			else {
				$EnableQCoreClass->replace('isDir', '');
				$EnableQCoreClass->replace('dirName', '');
				$PicFileType = 'jpg|jpeg|gif|bmp|png|swf';
				$tmpExt = explode('.', $ImageFile);
				$tmpNum = count($tmpExt) - 1;
				$extension = strtolower($tmpExt[$tmpNum]);
				$FileTypeExt = explode('|', $PicFileType);

				if (in_array($extension, $FileTypeExt)) {
					$EnableQCoreClass->replace('isPic', '');
				}
				else {
					$EnableQCoreClass->replace('isPic', 'none');
				}

				$recNum++;
			}

			$EnableQCoreClass->replace('time', date('y-m-d H:i:s', filemtime($ImageFile_DIR_Name . $ImageFile)));
			$EnableQCoreClass->replace('fileName', $ImageFile);
			$EnableQCoreClass->replace('deleteURL', $thisProg . '?Action=DeleteImageFile&Flag=' . str_replace('+', '%2B', base64_encode($ImageFile)));
			$EnableQCoreClass->replace('viewURL', $thisProg . '?Action=ViewImageFile&Flag=' . str_replace('+', '%2B', base64_encode($ImageFile)));
			$EnableQCoreClass->parse('ImageFile', 'IMAGEFILE', true);
		}

		closedir($ImageDir);
	}
}

$EnableQCoreClass->replace('recNum', $recNum);
$EnableQCoreClass->replace('addURL', $thisProg . '?Action=AddImageFile');
$EnableQCoreClass->replace('PicName', $lang['image_file_pre']);
$EnableQCoreClass->parse('ImageList', 'ImageList');
$EnableQCoreClass->output('ImageList');

?>
