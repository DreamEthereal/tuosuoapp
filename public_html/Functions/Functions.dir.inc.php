<?php
//dezend by http://www.yunlu99.com/
function createDir($dir, $mode = 511)
{
	if (is_dir($dir)) {
		return true;
	}

	$dir = dirname($dir . '/a');
	$_obf_Byrh3A__ = str_replace('\\', '/', $dir);
	$_obf_jXiWt4lZNw__ = explode('/', $_obf_Byrh3A__);
	$_obf_gftfagw_ = count($_obf_jXiWt4lZNw__);
	$_obf_7w__ = count($_obf_jXiWt4lZNw__) - 1;

	for (; 0 <= $_obf_7w__; $_obf_7w__--) {
		$_obf_Byrh3A__ = dirname($_obf_Byrh3A__);

		if (is_dir($_obf_Byrh3A__)) {
			$_obf_XA__ = $_obf_7w__;

			for (; $_obf_XA__ < $_obf_gftfagw_; $_obf_XA__++) {
				$_obf_Byrh3A__ .= '/' . $_obf_jXiWt4lZNw__[$_obf_XA__];

				if (is_dir($_obf_Byrh3A__)) {
					continue;
				}

				$_obf_eSsQSg__ = @mkdir($_obf_Byrh3A__, $mode);

				if (!$_obf_eSsQSg__) {
					return false;
				}
			}

			return true;
		}
	}

	return false;
}

function deletedir($destination)
{
	if (file_exists($destination)) {
		if (is_file($destination)) {
			unlink($destination);
		}
		else {
			$_obf_iUdm8Jn7 = opendir($destination);

			while ($_obf_6hS1Rw__ = readdir($_obf_iUdm8Jn7)) {
				if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
					continue;
				}

				if (is_dir($destination . '/' . $_obf_6hS1Rw__)) {
					deletedir($destination . '/' . $_obf_6hS1Rw__);
				}
				else {
					unlink($destination . '/' . $_obf_6hS1Rw__);
				}
			}

			closedir($_obf_iUdm8Jn7);
			rmdir($destination . '/' . $_obf_6hS1Rw__);
		}
	}
}

function deleteemptydir($destination)
{
	if (file_exists($destination)) {
		if (is_file($destination)) {
		}
		else {
			$_obf_iUdm8Jn7 = opendir($destination);

			while ($_obf_6hS1Rw__ = readdir($_obf_iUdm8Jn7)) {
				if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
					continue;
				}

				if (is_dir($destination . '/' . $_obf_6hS1Rw__)) {
					deleteemptydir($destination . '/' . $_obf_6hS1Rw__);
				}
			}

			closedir($_obf_iUdm8Jn7);
			rmdir($destination . '/' . $_obf_6hS1Rw__);
		}
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
