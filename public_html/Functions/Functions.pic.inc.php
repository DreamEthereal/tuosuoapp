<?php
//dezend by http://www.yunlu99.com/
function _picfileupload($picType, $SupportUploadFileType, $picPhyPath, $isEdit = false)
{
	global $EnableQCoreClass;
	global $_POST;
	global $lang;
	global $SiteRow;
	global $Config;

	if ($_FILES[$picType]['name'] != '') {
		$_obf_GhY_xT4LUL71z_oBs_QO = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'phtm', 'asa', 'cer');
		$_obf_A_N_tzzf = explode('.', $_FILES[$picType]['name']);
		$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
		$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
		if (!is_uploaded_file($_FILES[$picType]['tmp_name']) || in_array($_obf_MxPKhZcSxB7b, $_obf_GhY_xT4LUL71z_oBs_QO)) {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}

		$_obf_3ebc97Ag9pBjVFk_ = explode('|', $SupportUploadFileType);

		if (in_array($_obf_MxPKhZcSxB7b, $_obf_3ebc97Ag9pBjVFk_)) {
			$_obf_JTe7jJ4eGW8_ = date('YmdHis', time()) . rand(1, 999) . '.' . $_obf_MxPKhZcSxB7b;
			$_obf_pp9pYw__ = $picPhyPath . $_obf_JTe7jJ4eGW8_;

			if (file_exists($_obf_pp9pYw__)) {
				@unlink($_obf_pp9pYw__);
			}

			if (copy($_FILES[$picType]['tmp_name'], $_obf_pp9pYw__)) {
				$_obf_xCnI = ' ,' . $picType . '=\'' . $_obf_JTe7jJ4eGW8_ . '\' ';

				if ($isEdit == true) {
					$_obf_YWN3weD_Mzt5_Q__ = 'ori_' . $picType;

					if (file_exists($picPhyPath . $_POST[$_obf_YWN3weD_Mzt5_Q__])) {
						@unlink($picPhyPath . $_POST[$_obf_YWN3weD_Mzt5_Q__]);
					}
				}
			}
		}
		else {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}
	}

	return $_obf_xCnI;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
