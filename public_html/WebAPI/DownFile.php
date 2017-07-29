<?php
//dezend by http://www.yunlu99.com/
function _downmd5file($Dirname, $Filename, $theOriFileName)
{
	global $EnableQCoreClass;
	ob_start();

	if (!file_exists($Dirname . $Filename)) {
		_showerror('文件错误', '文件错误：您请求下载的文件不存在!');
	}

	$_obf_GWIH1eiKPtmJTkQ_ = file_get_contents($Dirname . $Filename);
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=' . _getshowfilename($theOriFileName));
	echo $_obf_GWIH1eiKPtmJTkQ_;
	exit();
}

function _getshowfilename($fileName)
{
	$_obf_jZJFIP3Dx6nXUx4_ = trim($fileName);
	$_obf_A_N_tzzf = explode('.', $_obf_jZJFIP3Dx6nXUx4_);
	$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
	$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
	$_obf_hk_Z__TqtsmTn3k_ = basename($_obf_jZJFIP3Dx6nXUx4_, '.' . $_obf_MxPKhZcSxB7b);
	$_obf_pL_YpAmNxhhxDXo_gfCqQw__ = explode('_', $_obf_hk_Z__TqtsmTn3k_);

	if (count($_obf_pL_YpAmNxhhxDXo_gfCqQw__) == 1) {
		return $fileName;
	}
	else {
		$_obf_nLeBi91MrXfV8AS3fqg_ = '';
		$_obf_juwe = 0;

		for (; $_obf_juwe < (count($_obf_pL_YpAmNxhhxDXo_gfCqQw__) - 1); $_obf_juwe++) {
			$_obf_nLeBi91MrXfV8AS3fqg_ .= $_obf_pL_YpAmNxhhxDXo_gfCqQw__[$_obf_juwe] . '_';
		}

		return substr($_obf_nLeBi91MrXfV8AS3fqg_, 0, -1) . '.' . $_obf_MxPKhZcSxB7b;
	}
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
_checkroletype('1|2|3|4|5|6|7');
if ((trim($_GET['file']) == '') || (trim($_GET['path']) == '')) {
	_showerror($lang['error_system'], $lang['no_download_file']);
}
else {
	clearstatcache();
	$theFilePath = base64_decode(trim($_GET['path']));
	$theFileName = md5(trim($_GET['file']));

	if (file_exists($theFilePath . $theFileName)) {
		_downmd5file($theFilePath, $theFileName, trim($_GET['file']));
	}
	else {
		_showerror($lang['error_system'], $lang['no_download_file']);
	}
}

?>
