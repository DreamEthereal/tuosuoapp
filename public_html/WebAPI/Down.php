<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
_checkroletype('1|2|3|4|5|6|7');
if ((trim($_GET['file']) == '') || (trim($_GET['path']) == '')) {
	_showerror($lang['error_system'], $lang['no_download_file']);
}
else {
	$theFilePath = base64_decode(trim($_GET['path']));
	$theFileName = base64_decode(trim($_GET['file']));

	if ($theFilePath != '../Help/') {
		if (substr($theFilePath, 0, 15) != '../PerUserData/') {
			_showerror('ϵͳ����', 'ϵͳ����:��ȷ�����ص��ļ�����EnableQϵͳ�Ϸ����ļ��洢·��?!');
		}
	}

	if (strpos($theFileName, '/') === false) {
	}
	else {
		_showerror('ϵͳ����', 'ϵͳ����:��ȷ�����ص��ļ�����EnableQϵͳ�Ϸ����ļ�����������?!');
	}

	clearstatcache();

	if (file_exists($theFilePath . $theFileName)) {
		_downloadfile($theFilePath, $theFileName);
	}
	else {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
}

?>
