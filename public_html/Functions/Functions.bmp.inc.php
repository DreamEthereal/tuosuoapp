<?php
//dezend by http://www.yunlu99.com/
function imagecreatefrombmp($filename)
{
	if (!($_obf_2yg_ = fopen($filename, 'rb'))) {
		return false;
	}

	$_obf_2YzEOQ__ = unpack('vfile_type/Vfile_size/Vreserved/Vbitmap_offset', fread($_obf_2yg_, 14));

	if ($_obf_2YzEOQ__['file_type'] != 19778) {
		return false;
	}

	$_obf_55ia = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($_obf_2yg_, 40));
	$_obf_55ia['colors'] = pow(2, $_obf_55ia['bits_per_pixel']);

	if ($_obf_55ia['size_bitmap'] == 0) {
		$_obf_55ia['size_bitmap'] = $_obf_2YzEOQ__['file_size'] - $_obf_2YzEOQ__['bitmap_offset'];
	}

	$_obf_55ia['bytes_per_pixel'] = $_obf_55ia['bits_per_pixel'] / 8;
	$_obf_55ia['bytes_per_pixel2'] = ceil($_obf_55ia['bytes_per_pixel']);
	$_obf_55ia['decal'] = ($_obf_55ia['width'] * $_obf_55ia['bytes_per_pixel']) / 4;
	$_obf_55ia['decal'] -= floor(($_obf_55ia['width'] * $_obf_55ia['bytes_per_pixel']) / 4);
	$_obf_55ia['decal'] = 4 - (4 * $_obf_55ia['decal']);

	if ($_obf_55ia['decal'] == 4) {
		$_obf_55ia['decal'] = 0;
	}

	$_obf_B_tOjZ0_Tg__ = array();

	if ($_obf_55ia['colors'] < 16777216) {
		$_obf_B_tOjZ0_Tg__ = unpack('V' . $_obf_55ia['colors'], fread($_obf_2yg_, $_obf_55ia['colors'] * 4));
	}

	$_obf__XDa = fread($_obf_2yg_, $_obf_55ia['size_bitmap']);
	$_obf_Gi7_eA__ = chr(0);
	$_obf_6UUC = imagecreatetruecolor($_obf_55ia['width'], $_obf_55ia['height']);
	$_obf_FA__ = 0;
	$_obf_Dg__ = $_obf_55ia['height'] - 1;

	while (0 <= $_obf_Dg__) {
		$Z = 0;

		while ($Z < $_obf_55ia['width']) {
			if ($_obf_55ia['bits_per_pixel'] == 32) {
				$_obf_R_LPD2A_ = unpack('V', substr($_obf__XDa, $_obf_FA__, 3));
				$_obf_3w__ = ord(substr($_obf__XDa, $_obf_FA__, 1));
				$_obf_mA__ = ord(substr($_obf__XDa, $_obf_FA__ + 1, 1));
				$_obf_sw__ = ord(substr($_obf__XDa, $_obf_FA__ + 2, 1));
				$_obf_E7LJsMo_ = imagecolorexact($_obf_6UUC, $_obf_sw__, $_obf_mA__, $_obf_3w__);

				if ($_obf_E7LJsMo_ == -1) {
					$_obf_E7LJsMo_ = imagecolorallocate($_obf_6UUC, $_obf_sw__, $_obf_mA__, $_obf_3w__);
				}

				$_obf_R_LPD2A_[0] = ($_obf_sw__ * 256 * 256) + ($_obf_mA__ * 256) + $_obf_3w__;
				$_obf_R_LPD2A_[1] = $_obf_E7LJsMo_;
			}
			else if ($_obf_55ia['bits_per_pixel'] == 24) {
				$_obf_R_LPD2A_ = unpack('V', substr($_obf__XDa, $_obf_FA__, 3) . $_obf_Gi7_eA__);
			}
			else if ($_obf_55ia['bits_per_pixel'] == 16) {
				$_obf_R_LPD2A_ = unpack('n', substr($_obf__XDa, $_obf_FA__, 2));
				$_obf_R_LPD2A_[1] = $_obf_B_tOjZ0_Tg__[$_obf_R_LPD2A_[1] + 1];
			}
			else if ($_obf_55ia['bits_per_pixel'] == 8) {
				$_obf_R_LPD2A_ = unpack('n', $_obf_Gi7_eA__ . substr($_obf__XDa, $_obf_FA__, 1));
				$_obf_R_LPD2A_[1] = $_obf_B_tOjZ0_Tg__[$_obf_R_LPD2A_[1] + 1];
			}
			else if ($_obf_55ia['bits_per_pixel'] == 4) {
				$_obf_R_LPD2A_ = unpack('n', $_obf_Gi7_eA__ . substr($_obf__XDa, floor($_obf_FA__), 1));

				if ((($_obf_FA__ * 2) % 2) == 0) {
					$_obf_R_LPD2A_[1] = $_obf_R_LPD2A_[1] >> 4;
				}
				else {
					$_obf_R_LPD2A_[1] = $_obf_R_LPD2A_[1] & 15;
				}

				$_obf_R_LPD2A_[1] = $_obf_B_tOjZ0_Tg__[$_obf_R_LPD2A_[1] + 1];
			}
			else if ($_obf_55ia['bits_per_pixel'] == 1) {
				$_obf_R_LPD2A_ = unpack('n', $_obf_Gi7_eA__ . substr($_obf__XDa, floor($_obf_FA__), 1));

				if ((($_obf_FA__ * 8) % 8) == 0) {
					$_obf_R_LPD2A_[1] = $_obf_R_LPD2A_[1] >> 7;
				}
				else if ((($_obf_FA__ * 8) % 8) == 1) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 64) >> 6;
				}
				else if ((($_obf_FA__ * 8) % 8) == 2) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 32) >> 5;
				}
				else if ((($_obf_FA__ * 8) % 8) == 3) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 16) >> 4;
				}
				else if ((($_obf_FA__ * 8) % 8) == 4) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 8) >> 3;
				}
				else if ((($_obf_FA__ * 8) % 8) == 5) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 4) >> 2;
				}
				else if ((($_obf_FA__ * 8) % 8) == 6) {
					$_obf_R_LPD2A_[1] = ($_obf_R_LPD2A_[1] & 2) >> 1;
				}
				else if ((($_obf_FA__ * 8) % 8) == 7) {
					$_obf_R_LPD2A_[1] = $_obf_R_LPD2A_[1] & 1;
				}

				$_obf_R_LPD2A_[1] = $_obf_B_tOjZ0_Tg__[$_obf_R_LPD2A_[1] + 1];
			}
			else {
				return false;
			}

			imagesetpixel($_obf_6UUC, $Z, $_obf_Dg__, $_obf_R_LPD2A_[1]);
			$Z++;
			$_obf_FA__ += $_obf_55ia['bytes_per_pixel'];
		}

		$_obf_Dg__--;
		$_obf_FA__ += $_obf_55ia['decal'];
	}

	fclose($_obf_2yg_);
	return $_obf_6UUC;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
