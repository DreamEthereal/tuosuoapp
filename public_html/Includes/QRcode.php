<?php
//dezend by http://www.yunlu99.com/
class qrstr
{
	static public function set(&$srctab, $x, $y, $repl, $replLen = false)
	{
		$srctab[$y] = substr_replace($srctab[$y], $replLen !== false ? substr($repl, 0, $replLen) : $repl, $x, $replLen !== false ? $replLen : strlen($repl));
	}
}

class QRtools
{
	static public function binarize($frame)
	{
		$_obf_mc2H = count($frame);

		foreach ($frame as &$_obf__b6_kgtZg296) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_mc2H; $_obf_7w__++) {
				$_obf__b6_kgtZg296[$_obf_7w__] = ord($_obf__b6_kgtZg296[$_obf_7w__]) & 1 ? '1' : '0';
			}
		}

		return $frame;
	}

	static public function tcpdfBarcodeArray($code, $mode = 'QR,L', $tcPdfVersion = '4.5.037')
	{
		$_obf_BG4fDKTu0t4or7CzkA__ = array();

		if (!is_array($mode)) {
			$mode = explode(',', $mode);
		}

		$_obf_QwDGC1GrSOc_ = 'L';

		if (1 < count($mode)) {
			$_obf_QwDGC1GrSOc_ = $mode[1];
		}

		$_obf_PdPSLEI_ = QRcode::text($code, false, $_obf_QwDGC1GrSOc_);
		$_obf_hNQa0g__ = count($_obf_PdPSLEI_);
		$_obf_BG4fDKTu0t4or7CzkA__['num_rows'] = $_obf_hNQa0g__;
		$_obf_BG4fDKTu0t4or7CzkA__['num_cols'] = $_obf_hNQa0g__;
		$_obf_BG4fDKTu0t4or7CzkA__['bcode'] = array();

		foreach ($_obf_PdPSLEI_ as $_obf_CFGoDA__) {
			$_obf_7hQK9qQQ = array();

			foreach (str_split($_obf_CFGoDA__) as $_obf_yxWKcw__) {
				$_obf_7hQK9qQQ[] = $_obf_yxWKcw__ == '1' ? 1 : 0;
			}

			$_obf_BG4fDKTu0t4or7CzkA__['bcode'][] = $_obf_7hQK9qQQ;
		}

		return $_obf_BG4fDKTu0t4or7CzkA__;
	}

	static public function clearCache()
	{
		self::$frames = array();
	}

	static public function buildCache()
	{
		QRtools::markTime('before_build_cache');
		$_obf_n69igA__ = new QRmask();
		$m = 1;

		for (; $m <= QRSPEC_VERSION_MAX; $m++) {
			$_obf_uoGCA40_ = QRspec::newFrame($m);

			if (QR_IMAGE) {
				$_obf_PW9SQhMxAgA_ = QR_CACHE_DIR . 'frame_' . $m . '.png';
				QRimage::png(self::binarize($_obf_uoGCA40_), $_obf_PW9SQhMxAgA_, 1, 0);
			}

			$_obf_ncdC0pM_ = count($_obf_uoGCA40_);
			$_obf_UnTkrlnbkg__ = array_fill(0, $_obf_ncdC0pM_, array_fill(0, $_obf_ncdC0pM_, 0));
			$_obf_pE_0bsNp = 0;

			for (; $_obf_pE_0bsNp < 8; $_obf_pE_0bsNp++) {
				$_obf_n69igA__->makeMaskNo($_obf_pE_0bsNp, $_obf_ncdC0pM_, $_obf_uoGCA40_, $_obf_UnTkrlnbkg__, true);
			}
		}

		QRtools::markTime('after_build_cache');
	}

	static public function log($outfile, $err)
	{
		if (QR_LOG_DIR !== false) {
			if ($err != '') {
				if ($outfile !== false) {
					file_put_contents(QR_LOG_DIR . basename($outfile) . '-errors.txt', date('Y-m-d H:i:s') . ': ' . $err, FILE_APPEND);
				}
				else {
					file_put_contents(QR_LOG_DIR . 'errors.txt', date('Y-m-d H:i:s') . ': ' . $err, FILE_APPEND);
				}
			}
		}
	}

	static public function dumpMask($frame)
	{
		$_obf_ncdC0pM_ = count($frame);
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < $_obf_ncdC0pM_; $_obf_OA__++) {
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < $_obf_ncdC0pM_; $_obf_5Q__++) {
				echo ord($frame[$_obf_OA__][$_obf_5Q__]) . ',';
			}
		}
	}

	static public function markTime($markerId)
	{
		list($_obf_FVLV_A__, $_obf_ByD_) = explode(' ', microtime());
		$_obf_c6UELg__ = (double) $_obf_FVLV_A__ + (double) $_obf_ByD_;

		if (!isset($GLOBALS['qr_time_bench'])) {
			$GLOBALS['qr_time_bench'] = array();
		}

		$GLOBALS['qr_time_bench'][$markerId] = $_obf_c6UELg__;
	}

	static public function timeBenchmark()
	{
		self::markTime('finish');
		$_obf_Lzol4kmcHqg_ = 0;
		$_obf_KF427RZcFHqy = 0;
		$_obf_8w__ = 0;
		echo '<table cellpadding="3" cellspacing="1">' . "\r\n" . '                    <thead><tr style="border-bottom:1px solid silver"><td colspan="2" style="text-align:center">BENCHMARK</td></tr></thead>' . "\r\n" . '                    <tbody>';

		foreach ($GLOBALS['qr_time_bench'] as $_obf_Ej5QA3z27cg_ => $thisTime) {
			if (0 < $_obf_8w__) {
				echo '<tr><th style="text-align:right">till ' . $_obf_Ej5QA3z27cg_ . ': </th><td>' . number_format($thisTime - $_obf_Lzol4kmcHqg_, 6) . 's</td></tr>';
			}
			else {
				$_obf_KF427RZcFHqy = $thisTime;
			}

			$_obf_8w__++;
			$_obf_Lzol4kmcHqg_ = $thisTime;
		}

		echo '</tbody><tfoot>' . "\r\n" . '                <tr style="border-top:2px solid black"><th style="text-align:right">TOTAL: </th><td>' . number_format($_obf_Lzol4kmcHqg_ - $_obf_KF427RZcFHqy, 6) . 's</td></tr>' . "\r\n" . '            </tfoot>' . "\r\n" . '            </table>';
	}
}

class QRspec
{
	static public $capacity = array(
		array(
			0,
			0,
			0,
			array(0, 0, 0, 0)
			),
		array(
			21,
			26,
			0,
			array(7, 10, 13, 17)
			),
		array(
			25,
			44,
			7,
			array(10, 16, 22, 28)
			),
		array(
			29,
			70,
			7,
			array(15, 26, 36, 44)
			),
		array(
			33,
			100,
			7,
			array(20, 36, 52, 64)
			),
		array(
			37,
			134,
			7,
			array(26, 48, 72, 88)
			),
		array(
			41,
			172,
			7,
			array(36, 64, 96, 112)
			),
		array(
			45,
			196,
			0,
			array(40, 72, 108, 130)
			),
		array(
			49,
			242,
			0,
			array(48, 88, 132, 156)
			),
		array(
			53,
			292,
			0,
			array(60, 110, 160, 192)
			),
		array(
			57,
			346,
			0,
			array(72, 130, 192, 224)
			),
		array(
			61,
			404,
			0,
			array(80, 150, 224, 264)
			),
		array(
			65,
			466,
			0,
			array(96, 176, 260, 308)
			),
		array(
			69,
			532,
			0,
			array(104, 198, 288, 352)
			),
		array(
			73,
			581,
			3,
			array(120, 216, 320, 384)
			),
		array(
			77,
			655,
			3,
			array(132, 240, 360, 432)
			),
		array(
			81,
			733,
			3,
			array(144, 280, 408, 480)
			),
		array(
			85,
			815,
			3,
			array(168, 308, 448, 532)
			),
		array(
			89,
			901,
			3,
			array(180, 338, 504, 588)
			),
		array(
			93,
			991,
			3,
			array(196, 364, 546, 650)
			),
		array(
			97,
			1085,
			3,
			array(224, 416, 600, 700)
			),
		array(
			101,
			1156,
			4,
			array(224, 442, 644, 750)
			),
		array(
			105,
			1258,
			4,
			array(252, 476, 690, 816)
			),
		array(
			109,
			1364,
			4,
			array(270, 504, 750, 900)
			),
		array(
			113,
			1474,
			4,
			array(300, 560, 810, 960)
			),
		array(
			117,
			1588,
			4,
			array(312, 588, 870, 1050)
			),
		array(
			121,
			1706,
			4,
			array(336, 644, 952, 1110)
			),
		array(
			125,
			1828,
			4,
			array(360, 700, 1020, 1200)
			),
		array(
			129,
			1921,
			3,
			array(390, 728, 1050, 1260)
			),
		array(
			133,
			2051,
			3,
			array(420, 784, 1140, 1350)
			),
		array(
			137,
			2185,
			3,
			array(450, 812, 1200, 1440)
			),
		array(
			141,
			2323,
			3,
			array(480, 868, 1290, 1530)
			),
		array(
			145,
			2465,
			3,
			array(510, 924, 1350, 1620)
			),
		array(
			149,
			2611,
			3,
			array(540, 980, 1440, 1710)
			),
		array(
			153,
			2761,
			3,
			array(570, 1036, 1530, 1800)
			),
		array(
			157,
			2876,
			0,
			array(570, 1064, 1590, 1890)
			),
		array(
			161,
			3034,
			0,
			array(600, 1120, 1680, 1980)
			),
		array(
			165,
			3196,
			0,
			array(630, 1204, 1770, 2100)
			),
		array(
			169,
			3362,
			0,
			array(660, 1260, 1860, 2220)
			),
		array(
			173,
			3532,
			0,
			array(720, 1316, 1950, 2310)
			),
		array(
			177,
			3706,
			0,
			array(750, 1372, 2040, 2430)
			)
		);
	static public $lengthTableBits = array(
		array(10, 12, 14),
		array(9, 11, 13),
		array(8, 16, 16),
		array(8, 10, 12)
		);
	static public $eccTable = array(
		array(
			array(0, 0),
			array(0, 0),
			array(0, 0),
			array(0, 0)
			),
		array(
			array(1, 0),
			array(1, 0),
			array(1, 0),
			array(1, 0)
			),
		array(
			array(1, 0),
			array(1, 0),
			array(1, 0),
			array(1, 0)
			),
		array(
			array(1, 0),
			array(1, 0),
			array(2, 0),
			array(2, 0)
			),
		array(
			array(1, 0),
			array(2, 0),
			array(2, 0),
			array(4, 0)
			),
		array(
			array(1, 0),
			array(2, 0),
			array(2, 2),
			array(2, 2)
			),
		array(
			array(2, 0),
			array(4, 0),
			array(4, 0),
			array(4, 0)
			),
		array(
			array(2, 0),
			array(4, 0),
			array(2, 4),
			array(4, 1)
			),
		array(
			array(2, 0),
			array(2, 2),
			array(4, 2),
			array(4, 2)
			),
		array(
			array(2, 0),
			array(3, 2),
			array(4, 4),
			array(4, 4)
			),
		array(
			array(2, 2),
			array(4, 1),
			array(6, 2),
			array(6, 2)
			),
		array(
			array(4, 0),
			array(1, 4),
			array(4, 4),
			array(3, 8)
			),
		array(
			array(2, 2),
			array(6, 2),
			array(4, 6),
			array(7, 4)
			),
		array(
			array(4, 0),
			array(8, 1),
			array(8, 4),
			array(12, 4)
			),
		array(
			array(3, 1),
			array(4, 5),
			array(11, 5),
			array(11, 5)
			),
		array(
			array(5, 1),
			array(5, 5),
			array(5, 7),
			array(11, 7)
			),
		array(
			array(5, 1),
			array(7, 3),
			array(15, 2),
			array(3, 13)
			),
		array(
			array(1, 5),
			array(10, 1),
			array(1, 15),
			array(2, 17)
			),
		array(
			array(5, 1),
			array(9, 4),
			array(17, 1),
			array(2, 19)
			),
		array(
			array(3, 4),
			array(3, 11),
			array(17, 4),
			array(9, 16)
			),
		array(
			array(3, 5),
			array(3, 13),
			array(15, 5),
			array(15, 10)
			),
		array(
			array(4, 4),
			array(17, 0),
			array(17, 6),
			array(19, 6)
			),
		array(
			array(2, 7),
			array(17, 0),
			array(7, 16),
			array(34, 0)
			),
		array(
			array(4, 5),
			array(4, 14),
			array(11, 14),
			array(16, 14)
			),
		array(
			array(6, 4),
			array(6, 14),
			array(11, 16),
			array(30, 2)
			),
		array(
			array(8, 4),
			array(8, 13),
			array(7, 22),
			array(22, 13)
			),
		array(
			array(10, 2),
			array(19, 4),
			array(28, 6),
			array(33, 4)
			),
		array(
			array(8, 4),
			array(22, 3),
			array(8, 26),
			array(12, 28)
			),
		array(
			array(3, 10),
			array(3, 23),
			array(4, 31),
			array(11, 31)
			),
		array(
			array(7, 7),
			array(21, 7),
			array(1, 37),
			array(19, 26)
			),
		array(
			array(5, 10),
			array(19, 10),
			array(15, 25),
			array(23, 25)
			),
		array(
			array(13, 3),
			array(2, 29),
			array(42, 1),
			array(23, 28)
			),
		array(
			array(17, 0),
			array(10, 23),
			array(10, 35),
			array(19, 35)
			),
		array(
			array(17, 1),
			array(14, 21),
			array(29, 19),
			array(11, 46)
			),
		array(
			array(13, 6),
			array(14, 23),
			array(44, 7),
			array(59, 1)
			),
		array(
			array(12, 7),
			array(12, 26),
			array(39, 14),
			array(22, 41)
			),
		array(
			array(6, 14),
			array(6, 34),
			array(46, 10),
			array(2, 64)
			),
		array(
			array(17, 4),
			array(29, 14),
			array(49, 10),
			array(24, 46)
			),
		array(
			array(4, 18),
			array(13, 32),
			array(48, 14),
			array(42, 32)
			),
		array(
			array(20, 4),
			array(40, 7),
			array(43, 22),
			array(10, 67)
			),
		array(
			array(19, 6),
			array(18, 31),
			array(34, 34),
			array(20, 61)
			)
		);
	static public $alignmentPattern = array(
		array(0, 0),
		array(0, 0),
		array(18, 0),
		array(22, 0),
		array(26, 0),
		array(30, 0),
		array(34, 0),
		array(22, 38),
		array(24, 42),
		array(26, 46),
		array(28, 50),
		array(30, 54),
		array(32, 58),
		array(34, 62),
		array(26, 46),
		array(26, 48),
		array(26, 50),
		array(30, 54),
		array(30, 56),
		array(30, 58),
		array(34, 62),
		array(28, 50),
		array(26, 50),
		array(30, 54),
		array(28, 54),
		array(32, 58),
		array(30, 58),
		array(34, 62),
		array(26, 50),
		array(30, 54),
		array(26, 52),
		array(30, 56),
		array(34, 60),
		array(30, 58),
		array(34, 62),
		array(30, 54),
		array(24, 50),
		array(28, 54),
		array(32, 58),
		array(26, 54),
		array(30, 58)
		);
	static public $versionPattern = array(31892, 34236, 39577, 42195, 48118, 51042, 55367, 58893, 63784, 68472, 70749, 76311, 79154, 84390, 87683, 92361, 96236, 102084, 102881, 110507, 110734, 117786, 119615, 126325, 127568, 133589, 136944, 141498, 145311, 150283, 152622, 158308, 161089, 167017);
	static public $formatInfo = array(
		array(30660, 29427, 32170, 30877, 26159, 25368, 27713, 26998),
		array(21522, 20773, 24188, 23371, 17913, 16590, 20375, 19104),
		array(13663, 12392, 16177, 14854, 9396, 8579, 11994, 11245),
		array(5769, 5054, 7399, 6608, 1890, 597, 3340, 2107)
		);
	static public $frames = array();

	static public function getDataLength($version, $level)
	{
		return self::$capacity[$version][QRCAP_WORDS] - self::$capacity[$version][QRCAP_EC][$level];
	}

	static public function getECCLength($version, $level)
	{
		return self::$capacity[$version][QRCAP_EC][$level];
	}

	static public function getWidth($version)
	{
		return self::$capacity[$version][QRCAP_WIDTH];
	}

	static public function getRemainder($version)
	{
		return self::$capacity[$version][QRCAP_REMINDER];
	}

	static public function getMinimumVersion($size, $level)
	{
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= QRSPEC_VERSION_MAX; $_obf_7w__++) {
			$_obf__hrsdvc_ = self::$capacity[$_obf_7w__][QRCAP_WORDS] - self::$capacity[$_obf_7w__][QRCAP_EC][$level];

			if ($size <= $_obf__hrsdvc_) {
				return $_obf_7w__;
			}
		}

		return -1;
	}

	static public function lengthIndicator($mode, $version)
	{
		if ($mode == QR_MODE_STRUCTURE) {
			return 0;
		}

		if ($version <= 9) {
			$A = 0;
		}
		else if ($version <= 26) {
			$A = 1;
		}
		else {
			$A = 2;
		}

		return self::$lengthTableBits[$mode][$A];
	}

	static public function maximumWords($mode, $version)
	{
		if ($mode == QR_MODE_STRUCTURE) {
			return 3;
		}

		if ($version <= 9) {
			$A = 0;
		}
		else if ($version <= 26) {
			$A = 1;
		}
		else {
			$A = 2;
		}

		$_obf_rihqHw__ = self::$lengthTableBits[$mode][$A];
		$_obf__hrsdvc_ = (1 << $_obf_rihqHw__) - 1;

		if ($mode == QR_MODE_KANJI) {
			$_obf__hrsdvc_ *= 2;
		}

		return $_obf__hrsdvc_;
	}

	static public function getEccSpec($version, $level, array &$spec)
	{
		if (count($spec) < 5) {
			$spec = array(0, 0, 0, 0, 0);
		}

		$_obf_j4s_ = self::$eccTable[$version][$level][0];
		$_obf_mck_ = self::$eccTable[$version][$level][1];
		$_obf_6RYLWQ__ = self::getDataLength($version, $level);
		$_obf_hZLH = self::getECCLength($version, $level);

		if ($_obf_mck_ == 0) {
			$spec[0] = $_obf_j4s_;
			$spec[1] = (int) $_obf_6RYLWQ__ / $_obf_j4s_;
			$spec[2] = (int) $_obf_hZLH / $_obf_j4s_;
			$spec[3] = 0;
			$spec[4] = 0;
		}
		else {
			$spec[0] = $_obf_j4s_;
			$spec[1] = (int) $_obf_6RYLWQ__ / ($_obf_j4s_ + $_obf_mck_);
			$spec[2] = (int) $_obf_hZLH / ($_obf_j4s_ + $_obf_mck_);
			$spec[3] = $_obf_mck_;
			$spec[4] = $spec[1] + 1;
		}
	}

	static public function putAlignmentMarker(array &$frame, $ox, $oy)
	{
		$_obf_PfK9HoTm = array('°°°°°', '°†††°', '°†°†°', '°†††°', '°°°°°');
		$_obf_YAXHNPMp = $oy - 2;
		$_obf_oE8Whwyr = $ox - 2;
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < 5; $_obf_OA__++) {
			QRstr::set($frame, $_obf_oE8Whwyr, $_obf_YAXHNPMp + $_obf_OA__, $_obf_PfK9HoTm[$_obf_OA__]);
		}
	}

	static public function putAlignmentPattern($version, &$frame, $width)
	{
		if ($version < 2) {
			return NULL;
		}

		$_obf_5g__ = self::$alignmentPattern[$version][1] - self::$alignmentPattern[$version][0];

		if ($_obf_5g__ < 0) {
			$_obf_hg__ = 2;
		}
		else {
			$_obf_hg__ = (int) (($width - self::$alignmentPattern[$version][0]) / $_obf_5g__) + 2;
		}

		if ((($_obf_hg__ * $_obf_hg__) - 3) == 1) {
			$_obf_5Q__ = self::$alignmentPattern[$version][0];
			$_obf_OA__ = self::$alignmentPattern[$version][0];
			self::putAlignmentMarker($frame, $_obf_5Q__, $_obf_OA__);
			return NULL;
		}

		$_obf_aKc_ = self::$alignmentPattern[$version][0];
		$_obf_5Q__ = 1;

		for (; $_obf_5Q__ < ($_obf_hg__ - 1); $_obf_5Q__++) {
			self::putAlignmentMarker($frame, 6, $_obf_aKc_);
			self::putAlignmentMarker($frame, $_obf_aKc_, 6);
			$_obf_aKc_ += $_obf_5g__;
		}

		$_obf_JGU_ = self::$alignmentPattern[$version][0];
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < ($_obf_hg__ - 1); $_obf_OA__++) {
			$_obf_aKc_ = self::$alignmentPattern[$version][0];
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < ($_obf_hg__ - 1); $_obf_5Q__++) {
				self::putAlignmentMarker($frame, $_obf_aKc_, $_obf_JGU_);
				$_obf_aKc_ += $_obf_5g__;
			}

			$_obf_JGU_ += $_obf_5g__;
		}
	}

	static public function getVersionPattern($version)
	{
		if (($version < 7) || (QRSPEC_VERSION_MAX < $version)) {
			return 0;
		}

		return self::$versionPattern[$version - 7];
	}

	static public function getFormatInfo($mask, $level)
	{
		if (($mask < 0) || (7 < $mask)) {
			return 0;
		}

		if (($level < 0) || (3 < $level)) {
			return 0;
		}

		return self::$formatInfo[$level][$mask];
	}

	static public function putFinderPattern(&$frame, $ox, $oy)
	{
		$_obf_PfK9HoTm = array('¡¡¡¡¡¡¡', '¡¿¿¿¿¿¡', '¡¿¡¡¡¿¡', '¡¿¡¡¡¿¡', '¡¿¡¡¡¿¡', '¡¿¿¿¿¿¡', '¡¡¡¡¡¡¡');
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < 7; $_obf_OA__++) {
			QRstr::set($frame, $ox, $oy + $_obf_OA__, $_obf_PfK9HoTm[$_obf_OA__]);
		}
	}

	static public function createFrame($version)
	{
		$_obf_ncdC0pM_ = self::$capacity[$version][QRCAP_WIDTH];
		$_obf__b6_kgtZg296 = str_repeat('' . "\0" . '', $_obf_ncdC0pM_);
		$_obf_uoGCA40_ = array_fill(0, $_obf_ncdC0pM_, $_obf__b6_kgtZg296);
		self::putFinderPattern($_obf_uoGCA40_, 0, 0);
		self::putFinderPattern($_obf_uoGCA40_, $_obf_ncdC0pM_ - 7, 0);
		self::putFinderPattern($_obf_uoGCA40_, 0, $_obf_ncdC0pM_ - 7);
		$_obf_XFgDtl2XnQ__ = $_obf_ncdC0pM_ - 7;
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < 7; $_obf_OA__++) {
			$_obf_uoGCA40_[$_obf_OA__][7] = '¿';
			$_obf_uoGCA40_[$_obf_OA__][$_obf_ncdC0pM_ - 8] = '¿';
			$_obf_uoGCA40_[$_obf_XFgDtl2XnQ__][7] = '¿';
			$_obf_XFgDtl2XnQ__++;
		}

		$_obf_oQxZK9hko15Kdg__ = str_repeat('¿', 8);
		QRstr::set($_obf_uoGCA40_, 0, 7, $_obf_oQxZK9hko15Kdg__);
		QRstr::set($_obf_uoGCA40_, $_obf_ncdC0pM_ - 8, 7, $_obf_oQxZK9hko15Kdg__);
		QRstr::set($_obf_uoGCA40_, 0, $_obf_ncdC0pM_ - 8, $_obf_oQxZK9hko15Kdg__);
		$_obf_oQxZK9hko15Kdg__ = str_repeat('Ñ', 9);
		QRstr::set($_obf_uoGCA40_, 0, 8, $_obf_oQxZK9hko15Kdg__);
		QRstr::set($_obf_uoGCA40_, $_obf_ncdC0pM_ - 8, 8, $_obf_oQxZK9hko15Kdg__, 8);
		$_obf_XFgDtl2XnQ__ = $_obf_ncdC0pM_ - 8;
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < 8; $_obf_OA__++, $_obf_XFgDtl2XnQ__++) {
			$_obf_uoGCA40_[$_obf_OA__][8] = 'Ñ';
			$_obf_uoGCA40_[$_obf_XFgDtl2XnQ__][8] = 'Ñ';
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ < ($_obf_ncdC0pM_ - 15); $_obf_7w__++) {
			$_obf_uoGCA40_[6][7 + $_obf_7w__] = chr(144 | ($_obf_7w__ & 1));
			$_obf_uoGCA40_[7 + $_obf_7w__][6] = chr(144 | ($_obf_7w__ & 1));
		}

		self::putAlignmentPattern($version, $_obf_uoGCA40_, $_obf_ncdC0pM_);

		if (7 <= $version) {
			$_obf_R9NrtQ__ = self::getVersionPattern($version);
			$_obf_6A__ = $_obf_R9NrtQ__;
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < 6; $_obf_5Q__++) {
				$_obf_OA__ = 0;

				for (; $_obf_OA__ < 3; $_obf_OA__++) {
					$_obf_uoGCA40_[($_obf_ncdC0pM_ - 11) + $_obf_OA__][$_obf_5Q__] = chr(136 | ($_obf_6A__ & 1));
					$_obf_6A__ = $_obf_6A__ >> 1;
				}
			}

			$_obf_6A__ = $_obf_R9NrtQ__;
			$_obf_OA__ = 0;

			for (; $_obf_OA__ < 6; $_obf_OA__++) {
				$_obf_5Q__ = 0;

				for (; $_obf_5Q__ < 3; $_obf_5Q__++) {
					$_obf_uoGCA40_[$_obf_OA__][$_obf_5Q__ + ($_obf_ncdC0pM_ - 11)] = chr(136 | ($_obf_6A__ & 1));
					$_obf_6A__ = $_obf_6A__ >> 1;
				}
			}
		}

		$_obf_uoGCA40_[$_obf_ncdC0pM_ - 8][8] = 'Å';
		return $_obf_uoGCA40_;
	}

	static public function debug($frame, $binary_mode = false)
	{
		if ($binary_mode) {
			foreach ($frame as &$_obf__b6_kgtZg296) {
				$_obf__b6_kgtZg296 = join('<span class="m">&nbsp;&nbsp;</span>', explode('0', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('&#9608;&#9608;', explode('1', $_obf__b6_kgtZg296));
			}

			echo '                <style>' . "\r\n" . '                    .m { background-color: white; }' . "\r\n" . '                </style>' . "\r\n" . '                ';
			echo '<pre><tt><br/ ><br/ ><br/ >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			echo join('<br/ >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $frame);
			echo '</tt></pre><br/ ><br/ ><br/ ><br/ ><br/ ><br/ >';
		}
		else {
			foreach ($frame as &$_obf__b6_kgtZg296) {
				$_obf__b6_kgtZg296 = join('<span class="m">&nbsp;</span>', explode('¿', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="m">&#9618;</span>', explode('¡', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="p">&nbsp;</span>', explode('†', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="p">&#9618;</span>', explode('°', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="s">&#9671;</span>', explode('Ñ', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="s">&#9670;</span>', explode('Ö', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="x">&#9762;</span>', explode('Å', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="c">&nbsp;</span>', explode('ê', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="c">&#9719;</span>', explode('ë', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="f">&nbsp;</span>', explode('à', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('<span class="f">&#9618;</span>', explode('â', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('&#9830;', explode('', $_obf__b6_kgtZg296));
				$_obf__b6_kgtZg296 = join('&#8901;', explode('' . "\0" . '', $_obf__b6_kgtZg296));
			}

			echo '                <style>' . "\r\n" . '                    .p { background-color: yellow; }' . "\r\n" . '                    .m { background-color: #00FF00; }' . "\r\n" . '                    .s { background-color: #FF0000; }' . "\r\n" . '                    .c { background-color: aqua; }' . "\r\n" . '                    .x { background-color: pink; }' . "\r\n" . '                    .f { background-color: gold; }' . "\r\n" . '                </style>' . "\r\n" . '                ';
			echo '<pre><tt>';
			echo join('<br/ >', $frame);
			echo '</tt></pre>';
		}
	}

	static public function serial($frame)
	{
		return gzcompress(join("\n", $frame), 9);
	}

	static public function unserial($code)
	{
		return explode("\n", gzuncompress($code));
	}

	static public function newFrame($version)
	{
		if (($version < 1) || (QRSPEC_VERSION_MAX < $version)) {
			return NULL;
		}

		if (!isset(self::$frames[$version])) {
			$_obf_PW9SQhMxAgA_ = QR_CACHE_DIR . 'frame_' . $version . '.dat';

			if (QR_CACHEABLE) {
				if (file_exists($_obf_PW9SQhMxAgA_)) {
					self::$frames[$version] = self::unserial(file_get_contents($_obf_PW9SQhMxAgA_));
				}
				else {
					self::$frames[$version] = self::createFrame($version);
					file_put_contents($_obf_PW9SQhMxAgA_, self::serial(self::$frames[$version]));
				}
			}
			else {
				self::$frames[$version] = self::createFrame($version);
			}
		}

		if (is_null(self::$frames[$version])) {
			return NULL;
		}

		return self::$frames[$version];
	}

	static public function rsBlockNum($spec)
	{
		return $spec[0] + $spec[3];
	}

	static public function rsBlockNum1($spec)
	{
		return $spec[0];
	}

	static public function rsDataCodes1($spec)
	{
		return $spec[1];
	}

	static public function rsEccCodes1($spec)
	{
		return $spec[2];
	}

	static public function rsBlockNum2($spec)
	{
		return $spec[3];
	}

	static public function rsDataCodes2($spec)
	{
		return $spec[4];
	}

	static public function rsEccCodes2($spec)
	{
		return $spec[2];
	}

	static public function rsDataLength($spec)
	{
		return ($spec[0] * $spec[1]) + ($spec[3] * $spec[4]);
	}

	static public function rsEccLength($spec)
	{
		return ($spec[0] + $spec[3]) * $spec[2];
	}
}

class QRimage
{
	static public function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint = false)
	{
		$_obf_Ee07RX8_ = self::image($frame, $pixelPerPoint, $outerFrame);

		if ($filename === false) {
			header('Content-type: image/png');
			imagepng($_obf_Ee07RX8_);
		}
		else if ($saveandprint === true) {
			imagepng($_obf_Ee07RX8_, $filename);
			header('Content-type: image/png');
			imagepng($_obf_Ee07RX8_);
		}
		else {
			imagepng($_obf_Ee07RX8_, $filename);
		}

		imagedestroy($_obf_Ee07RX8_);
	}

	static public function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85)
	{
		$_obf_Ee07RX8_ = self::image($frame, $pixelPerPoint, $outerFrame);

		if ($filename === false) {
			header('Content-type: image/jpeg');
			imagejpeg($_obf_Ee07RX8_, NULL, $q);
		}
		else {
			imagejpeg($_obf_Ee07RX8_, $filename, $q);
		}

		imagedestroy($_obf_Ee07RX8_);
	}

	static private function image($frame, $pixelPerPoint = 4, $outerFrame = 4)
	{
		$M = count($frame);
		$_obf_hg__ = strlen($frame[0]);
		$_obf_RxgOlA__ = $_obf_hg__ + (2 * $outerFrame);
		$_obf_Z5kyxg__ = $M + (2 * $outerFrame);
		$_obf_h65JM9DWOtDsQQ__ = imagecreate($_obf_RxgOlA__, $_obf_Z5kyxg__);
		$_obf_u_FB[0] = imagecolorallocate($_obf_h65JM9DWOtDsQQ__, 255, 255, 255);
		$_obf_u_FB[1] = imagecolorallocate($_obf_h65JM9DWOtDsQQ__, 0, 0, 0);
		imagefill($_obf_h65JM9DWOtDsQQ__, 0, 0, $_obf_u_FB[0]);
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < $M; $_obf_OA__++) {
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < $_obf_hg__; $_obf_5Q__++) {
				if ($frame[$_obf_OA__][$_obf_5Q__] == '1') {
					imagesetpixel($_obf_h65JM9DWOtDsQQ__, $_obf_5Q__ + $outerFrame, $_obf_OA__ + $outerFrame, $_obf_u_FB[1]);
				}
			}
		}

		$_obf_tQNIw0h1zjOw24_N = imagecreate($_obf_RxgOlA__ * $pixelPerPoint, $_obf_Z5kyxg__ * $pixelPerPoint);
		imagecopyresized($_obf_tQNIw0h1zjOw24_N, $_obf_h65JM9DWOtDsQQ__, 0, 0, 0, 0, $_obf_RxgOlA__ * $pixelPerPoint, $_obf_Z5kyxg__ * $pixelPerPoint, $_obf_RxgOlA__, $_obf_Z5kyxg__);
		imagedestroy($_obf_h65JM9DWOtDsQQ__);
		return $_obf_tQNIw0h1zjOw24_N;
	}
}

class QRinputItem
{
	public $mode;
	public $size;
	public $data;
	public $bstream;

	public function __construct($mode, $size, $data, $bstream = NULL)
	{
		$_obf__I_RSMNXnA__ = array_slice($data, 0, $size);

		if (count($_obf__I_RSMNXnA__) < $size) {
			$_obf__I_RSMNXnA__ = array_merge($_obf__I_RSMNXnA__, array_fill(0, $size - count($_obf__I_RSMNXnA__), 0));
		}

		if (!QRinput::check($mode, $size, $_obf__I_RSMNXnA__)) {
			throw new Exception('Error m:' . $mode . ',s:' . $size . ',d:' . join(',', $_obf__I_RSMNXnA__));
			return NULL;
		}

		$this->mode = $mode;
		$this->size = $size;
		$this->data = $_obf__I_RSMNXnA__;
		$this->bstream = $bstream;
	}

	public function encodeModeNum($version)
	{
		try {
			$_obf__hrsdvc_ = (int) $this->size / 3;
			$_obf_Hu4_ = new QRbitstream();
			$_obf_TAxu = 1;
			$_obf_Hu4_->appendNum(4, $_obf_TAxu);
			$_obf_Hu4_->appendNum(QRspec::lengthIndicator(QR_MODE_NUM, $version), $this->size);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf__hrsdvc_; $_obf_7w__++) {
				$_obf_TAxu = (ord($this->data[$_obf_7w__ * 3]) - ord('0')) * 100;
				$_obf_TAxu += (ord($this->data[($_obf_7w__ * 3) + 1]) - ord('0')) * 10;
				$_obf_TAxu += ord($this->data[($_obf_7w__ * 3) + 2]) - ord('0');
				$_obf_Hu4_->appendNum(10, $_obf_TAxu);
			}

			if (($this->size - ($_obf__hrsdvc_ * 3)) == 1) {
				$_obf_TAxu = ord($this->data[$_obf__hrsdvc_ * 3]) - ord('0');
				$_obf_Hu4_->appendNum(4, $_obf_TAxu);
			}
			else if (($this->size - ($_obf__hrsdvc_ * 3)) == 2) {
				$_obf_TAxu = (ord($this->data[$_obf__hrsdvc_ * 3]) - ord('0')) * 10;
				$_obf_TAxu += ord($this->data[($_obf__hrsdvc_ * 3) + 1]) - ord('0');
				$_obf_Hu4_->appendNum(7, $_obf_TAxu);
			}

			$this->bstream = $_obf_Hu4_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function encodeModeAn($version)
	{
		try {
			$_obf__hrsdvc_ = (int) $this->size / 2;
			$_obf_Hu4_ = new QRbitstream();
			$_obf_Hu4_->appendNum(4, 2);
			$_obf_Hu4_->appendNum(QRspec::lengthIndicator(QR_MODE_AN, $version), $this->size);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf__hrsdvc_; $_obf_7w__++) {
				$_obf_TAxu = (int) QRinput::lookAnTable(ord($this->data[$_obf_7w__ * 2])) * 45;
				$_obf_TAxu += (int) QRinput::lookAnTable(ord($this->data[($_obf_7w__ * 2) + 1]));
				$_obf_Hu4_->appendNum(11, $_obf_TAxu);
			}

			if ($this->size & 1) {
				$_obf_TAxu = QRinput::lookAnTable(ord($this->data[$_obf__hrsdvc_ * 2]));
				$_obf_Hu4_->appendNum(6, $_obf_TAxu);
			}

			$this->bstream = $_obf_Hu4_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function encodeMode8($version)
	{
		try {
			$_obf_Hu4_ = new QRbitstream();
			$_obf_Hu4_->appendNum(4, 4);
			$_obf_Hu4_->appendNum(QRspec::lengthIndicator(QR_MODE_8, $version), $this->size);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->size; $_obf_7w__++) {
				$_obf_Hu4_->appendNum(8, ord($this->data[$_obf_7w__]));
			}

			$this->bstream = $_obf_Hu4_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function encodeModeKanji($version)
	{
		try {
			$_obf_Hu4_ = new QRbitrtream();
			$_obf_Hu4_->appendNum(4, 8);
			$_obf_Hu4_->appendNum(QRspec::lengthIndicator(QR_MODE_KANJI, $version), (int) $this->size / 2);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->size; $_obf_7w__ += 2) {
				$_obf_TAxu = (ord($this->data[$_obf_7w__]) << 8) | ord($this->data[$_obf_7w__ + 1]);

				if ($_obf_TAxu <= 40956) {
					$_obf_TAxu -= 33088;
				}
				else {
					$_obf_TAxu -= 49472;
				}

				$M = ($_obf_TAxu >> 8) * 192;
				$_obf_TAxu = ($_obf_TAxu & 255) + $M;
				$_obf_Hu4_->appendNum(13, $_obf_TAxu);
			}

			$this->bstream = $_obf_Hu4_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function encodeModeStructure()
	{
		try {
			$_obf_Hu4_ = new QRbitstream();
			$_obf_Hu4_->appendNum(4, 3);
			$_obf_Hu4_->appendNum(4, ord($this->data[1]) - 1);
			$_obf_Hu4_->appendNum(4, ord($this->data[0]) - 1);
			$_obf_Hu4_->appendNum(8, ord($this->data[2]));
			$this->bstream = $_obf_Hu4_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function estimateBitStreamSizeOfEntry($version)
	{
		$_obf_rihqHw__ = 0;

		if ($version == 0) {
			$version = 1;
		}

		switch ($this->mode) {
		case QR_MODE_NUM:
			$_obf_rihqHw__ = QRinput::estimateBitsModeNum($this->size);
			break;

		case QR_MODE_AN:
			$_obf_rihqHw__ = QRinput::estimateBitsModeAn($this->size);
			break;

		case QR_MODE_8:
			$_obf_rihqHw__ = QRinput::estimateBitsMode8($this->size);
			break;

		case QR_MODE_KANJI:
			$_obf_rihqHw__ = QRinput::estimateBitsModeKanji($this->size);
			break;

		case QR_MODE_STRUCTURE:
			return STRUCTURE_HEADER_BITS;
		default:
			return 0;
		}

		$A = QRspec::lengthIndicator($this->mode, $version);
		$_obf_Ag__ = 1 << $A;
		$_obf_Ybai = (int) (($this->size + $_obf_Ag__) - 1) / $_obf_Ag__;
		$_obf_rihqHw__ += $_obf_Ybai * (4 + $A);
		return $_obf_rihqHw__;
	}

	public function encodeBitStream($version)
	{
		try {
			unset($this->bstream);
			$words = QRspec::maximumWords($this->mode, $version);

			if ($words < $this->size) {
				$st1 = new QRinputItem($this->mode, $words, $this->data);
				$st2 = new QRinputItem($this->mode, $this->size - $words, array_slice($this->data, $words));
				$st1->encodeBitStream($version);
				$st2->encodeBitStream($version);
				$this->bstream = new QRbitstream();
				$this->bstream->append($st1->bstream);
				$this->bstream->append($st2->bstream);
				unset($st1);
				unset($st2);
			}
			else {
				$ret = 0;

				switch ($this->mode) {
				case QR_MODE_NUM:
					$ret = $this->encodeModeNum($version);
					break;

				case QR_MODE_AN:
					$ret = $this->encodeModeAn($version);
					break;

				case QR_MODE_8:
					$ret = $this->encodeMode8($version);
					break;

				case QR_MODE_KANJI:
					$ret = $this->encodeModeKanji($version);
					break;

				case QR_MODE_STRUCTURE:
					$ret = $this->encodeModeStructure();
					break;

				default:
					break;
				}

				if ($ret < 0) {
					return -1;
				}
			}

			return $this->bstream->size();
		}
		catch (Exception $e) {
			return -1;
		}
	}
}

class QRinput
{
	public $items;
	private $version;
	private $level;
	static public $anTable = array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 36, -1, -1, -1, 37, 38, -1, -1, -1, -1, 39, 40, -1, 41, 42, 43, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 44, -1, -1, -1, -1, -1, -1, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1);

	public function __construct($version = 0, $level = QR_ECLEVEL_L)
	{
		if (($version < 0) || (QRSPEC_VERSION_MAX < $version) || (QR_ECLEVEL_H < $level)) {
			throw new Exception('Invalid version no');
			return NULL;
		}

		$this->version = $version;
		$this->level = $level;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function setVersion($version)
	{
		if (($version < 0) || (QRSPEC_VERSION_MAX < $version)) {
			throw new Exception('Invalid version no');
			return -1;
		}

		$this->version = $version;
		return 0;
	}

	public function getErrorCorrectionLevel()
	{
		return $this->level;
	}

	public function setErrorCorrectionLevel($level)
	{
		if (QR_ECLEVEL_H < $level) {
			throw new Exception('Invalid ECLEVEL');
			return -1;
		}

		$this->level = $level;
		return 0;
	}

	public function appendEntry(QRinputItem $entry)
	{
		$this->items[] = $entry;
	}

	public function append($mode, $size, $data)
	{
		try {
			$_obf_dS3LtQY_ = new QRinputItem($mode, $size, $data);
			$this->items[] = $_obf_dS3LtQY_;
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function insertStructuredAppendHeader($size, $index, $parity)
	{
		if (MAX_STRUCTURED_SYMBOLS < $size) {
			throw new Exception('insertStructuredAppendHeader wrong size');
		}

		if (($index <= 0) || (MAX_STRUCTURED_SYMBOLS < $index)) {
			throw new Exception('insertStructuredAppendHeader wrong index');
		}

		$_obf_qQs0 = array($size, $index, $parity);

		try {
			$_obf_dS3LtQY_ = new QRinputItem(QR_MODE_STRUCTURE, 3, buf);
			array_unshift($this->items, $_obf_dS3LtQY_);
			return 0;
		}
		catch (Exception $_obf_hA__) {
			return -1;
		}
	}

	public function calcParity()
	{
		$_obf_2tuumXI5 = 0;

		foreach ($this->items as $_obf_LQ8UKg__) {
			if ($_obf_LQ8UKg__->mode != QR_MODE_STRUCTURE) {
				$_obf_7w__ = $_obf_LQ8UKg__->size - 1;

				for (; 0 <= $_obf_7w__; $_obf_7w__--) {
					$_obf_2tuumXI5 ^= $_obf_LQ8UKg__->data[$_obf_7w__];
				}
			}
		}

		return $_obf_2tuumXI5;
	}

	static public function checkModeNum($size, $data)
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $size; $_obf_7w__++) {
			if ((ord($data[$_obf_7w__]) < ord('0')) || (ord('9') < ord($data[$_obf_7w__]))) {
				return false;
			}
		}

		return true;
	}

	static public function estimateBitsModeNum($size)
	{
		$_obf_hg__ = (int) $size / 3;
		$_obf_rihqHw__ = $_obf_hg__ * 10;

		switch ($size - ($_obf_hg__ * 3)) {
		case 1:
			$_obf_rihqHw__ += 4;
			break;

		case 2:
			$_obf_rihqHw__ += 7;
			break;

		default:
			break;
		}

		return $_obf_rihqHw__;
	}

	static public function lookAnTable($c)
	{
		return 127 < $c ? -1 : self::$anTable[$c];
	}

	static public function checkModeAn($size, $data)
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $size; $_obf_7w__++) {
			if (self::lookAnTable(ord($data[$_obf_7w__])) == -1) {
				return false;
			}
		}

		return true;
	}

	static public function estimateBitsModeAn($size)
	{
		$_obf_hg__ = (int) $size / 2;
		$_obf_rihqHw__ = $_obf_hg__ * 11;

		if ($size & 1) {
			$_obf_rihqHw__ += 6;
		}

		return $_obf_rihqHw__;
	}

	static public function estimateBitsMode8($size)
	{
		return $size * 8;
	}

	public function estimateBitsModeKanji($size)
	{
		return (int) ($size / 2) * 13;
	}

	static public function checkModeKanji($size, $data)
	{
		if ($size & 1) {
			return false;
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $size; $_obf_7w__ += 2) {
			$_obf_TAxu = (ord($data[$_obf_7w__]) << 8) | ord($data[$_obf_7w__ + 1]);
			if (($_obf_TAxu < 33088) || ((40956 < $_obf_TAxu) && ($_obf_TAxu < 57408)) || (60351 < $_obf_TAxu)) {
				return false;
			}
		}

		return true;
	}

	static public function check($mode, $size, $data)
	{
		if ($size <= 0) {
			return false;
		}

		switch ($mode) {
		case QR_MODE_NUM:
			return self::checkModeNum($size, $data);
			break;

		case QR_MODE_AN:
			return self::checkModeAn($size, $data);
			break;

		case QR_MODE_KANJI:
			return self::checkModeKanji($size, $data);
			break;

		case QR_MODE_8:
			return true;
			break;

		case QR_MODE_STRUCTURE:
			return true;
			break;

		default:
			break;
		}

		return false;
	}

	public function estimateBitStreamSize($version)
	{
		$_obf_rihqHw__ = 0;

		foreach ($this->items as $_obf_LQ8UKg__) {
			$_obf_rihqHw__ += $_obf_LQ8UKg__->estimateBitStreamSizeOfEntry($version);
		}

		return $_obf_rihqHw__;
	}

	public function estimateVersion()
	{
		$_obf_XJJdY2VaDA__ = 0;
		$_obf_jMLuYA__ = 0;

		do {
			$_obf_jMLuYA__ = $_obf_XJJdY2VaDA__;
			$_obf_rihqHw__ = $this->estimateBitStreamSize($_obf_jMLuYA__);
			$_obf_XJJdY2VaDA__ = QRspec::getMinimumVersion((int) ($_obf_rihqHw__ + 7) / 8, $this->level);

			if ($_obf_XJJdY2VaDA__ < 0) {
				return -1;
			}
		} while ($_obf_jMLuYA__ < $_obf_XJJdY2VaDA__);

		return $_obf_XJJdY2VaDA__;
	}

	static public function lengthOfCode($mode, $version, $bits)
	{
		$_obf_Qn1FmOmMNA__ = $bits - 4 - QRspec::lengthIndicator($mode, $version);

		switch ($mode) {
		case QR_MODE_NUM:
			$_obf_w47fUTp6 = (int) $_obf_Qn1FmOmMNA__ / 10;
			$_obf_1L_ATkCw = $_obf_Qn1FmOmMNA__ - ($_obf_w47fUTp6 * 10);
			$_obf_hNQa0g__ = $_obf_w47fUTp6 * 3;

			if (7 <= $_obf_1L_ATkCw) {
				$_obf_hNQa0g__ += 2;
			}
			else if (4 <= $_obf_1L_ATkCw) {
				$_obf_hNQa0g__ += 1;
			}

			break;

		case QR_MODE_AN:
			$_obf_w47fUTp6 = (int) $_obf_Qn1FmOmMNA__ / 11;
			$_obf_1L_ATkCw = $_obf_Qn1FmOmMNA__ - ($_obf_w47fUTp6 * 11);
			$_obf_hNQa0g__ = $_obf_w47fUTp6 * 2;

			if (6 <= $_obf_1L_ATkCw) {
				$_obf_hNQa0g__++;
			}

			break;

		case QR_MODE_8:
			$_obf_hNQa0g__ = (int) $_obf_Qn1FmOmMNA__ / 8;
			break;

		case QR_MODE_KANJI:
			$_obf_hNQa0g__ = (int) ($_obf_Qn1FmOmMNA__ / 13) * 2;
			break;

		case QR_MODE_STRUCTURE:
			$_obf_hNQa0g__ = (int) $_obf_Qn1FmOmMNA__ / 8;
			break;

		default:
			$_obf_hNQa0g__ = 0;
			break;
		}

		$_obf_xmRneRGXbg__ = QRspec::maximumWords($mode, $version);

		if ($_obf_hNQa0g__ < 0) {
			$_obf_hNQa0g__ = 0;
		}

		if ($_obf_xmRneRGXbg__ < $_obf_hNQa0g__) {
			$_obf_hNQa0g__ = $_obf_xmRneRGXbg__;
		}

		return $_obf_hNQa0g__;
	}

	public function createBitStream()
	{
		$_obf_j9s_Jes_ = 0;

		foreach ($this->items as $_obf_LQ8UKg__) {
			$_obf_rihqHw__ = $_obf_LQ8UKg__->encodeBitStream($this->version);

			if ($_obf_rihqHw__ < 0) {
				return -1;
			}

			$_obf_j9s_Jes_ += $_obf_rihqHw__;
		}

		return $_obf_j9s_Jes_;
	}

	public function convertData()
	{
		$_obf_fneD = $this->estimateVersion();

		if ($this->getVersion() < $_obf_fneD) {
			$this->setVersion($_obf_fneD);
		}

		for (; ; ) {
			$_obf_rihqHw__ = $this->createBitStream();

			if ($_obf_rihqHw__ < 0) {
				return -1;
			}

			$_obf_fneD = QRspec::getMinimumVersion((int) ($_obf_rihqHw__ + 7) / 8, $this->level);

			if ($_obf_fneD < 0) {
				throw new Exception('WRONG VERSION');
				return -1;
			}
			else if ($this->getVersion() < $_obf_fneD) {
				$this->setVersion($_obf_fneD);
			}
			else {
				break;
			}
		}

		return 0;
	}

	public function appendPaddingBit(&$bstream)
	{
		$_obf_rihqHw__ = $bstream->size();
		$_obf_70VDUdKvVuk_ = QRspec::getDataLength($this->version, $this->level);
		$_obf_M5MufBYedw__ = $_obf_70VDUdKvVuk_ * 8;

		if ($_obf_M5MufBYedw__ == $_obf_rihqHw__) {
			return 0;
		}

		if (($_obf_M5MufBYedw__ - $_obf_rihqHw__) < 5) {
			return $bstream->appendNum($_obf_M5MufBYedw__ - $_obf_rihqHw__, 0);
		}

		$_obf_rihqHw__ += 4;
		$_obf__hrsdvc_ = (int) ($_obf_rihqHw__ + 7) / 8;
		$_obf_x22_4_NOKA__ = new QRbitstream();
		$_obf_Xtyr = $_obf_x22_4_NOKA__->appendNum((($_obf__hrsdvc_ * 8) - $_obf_rihqHw__) + 4, 0);

		if ($_obf_Xtyr < 0) {
			return $_obf_Xtyr;
		}

		$_obf_PIZnfR2E = $_obf_70VDUdKvVuk_ - $_obf__hrsdvc_;

		if (0 < $_obf_PIZnfR2E) {
			$_obf_JW21833G = array();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_PIZnfR2E; $_obf_7w__++) {
				$_obf_JW21833G[$_obf_7w__] = $_obf_7w__ & 1 ? 17 : 236;
			}

			$_obf_Xtyr = $_obf_x22_4_NOKA__->appendBytes($_obf_PIZnfR2E, $_obf_JW21833G);

			if ($_obf_Xtyr < 0) {
				return $_obf_Xtyr;
			}
		}

		$_obf_Xtyr = $bstream->append($_obf_x22_4_NOKA__);
		return $_obf_Xtyr;
	}

	public function mergeBitStream()
	{
		if ($this->convertData() < 0) {
			return NULL;
		}

		$_obf_OWZzvX6aiQ__ = new QRbitstream();

		foreach ($this->items as $_obf_LQ8UKg__) {
			$_obf_Xtyr = $_obf_OWZzvX6aiQ__->append($_obf_LQ8UKg__->bstream);

			if ($_obf_Xtyr < 0) {
				return NULL;
			}
		}

		return $_obf_OWZzvX6aiQ__;
	}

	public function getBitStream()
	{
		$_obf_OWZzvX6aiQ__ = $this->mergeBitStream();

		if ($_obf_OWZzvX6aiQ__ == NULL) {
			return NULL;
		}

		$_obf_Xtyr = $this->appendPaddingBit($_obf_OWZzvX6aiQ__);

		if ($_obf_Xtyr < 0) {
			return NULL;
		}

		return $_obf_OWZzvX6aiQ__;
	}

	public function getByteStream()
	{
		$_obf_OWZzvX6aiQ__ = $this->getBitStream();

		if ($_obf_OWZzvX6aiQ__ == NULL) {
			return NULL;
		}

		return $_obf_OWZzvX6aiQ__->toByte();
	}
}

class QRbitstream
{
	public $data = array();

	public function size()
	{
		return count($this->data);
	}

	public function allocate($setLength)
	{
		$this->data = array_fill(0, $setLength, 0);
		return 0;
	}

	static public function newFromNum($bits, $num)
	{
		$_obf_OWZzvX6aiQ__ = new QRbitstream();
		$_obf_OWZzvX6aiQ__->allocate($bits);
		$_obf_n69igA__ = 1 << ($bits - 1);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $bits; $_obf_7w__++) {
			if ($num & $_obf_n69igA__) {
				$_obf_OWZzvX6aiQ__->data[$_obf_7w__] = 1;
			}
			else {
				$_obf_OWZzvX6aiQ__->data[$_obf_7w__] = 0;
			}

			$_obf_n69igA__ = $_obf_n69igA__ >> 1;
		}

		return $_obf_OWZzvX6aiQ__;
	}

	static public function newFromBytes($size, $data)
	{
		$_obf_OWZzvX6aiQ__ = new QRbitstream();
		$_obf_OWZzvX6aiQ__->allocate($size * 8);
		$_obf_8w__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $size; $_obf_7w__++) {
			$_obf_n69igA__ = 128;
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < 8; $_obf_XA__++) {
				if ($data[$_obf_7w__] & $_obf_n69igA__) {
					$_obf_OWZzvX6aiQ__->data[$_obf_8w__] = 1;
				}
				else {
					$_obf_OWZzvX6aiQ__->data[$_obf_8w__] = 0;
				}

				$_obf_8w__++;
				$_obf_n69igA__ = $_obf_n69igA__ >> 1;
			}
		}

		return $_obf_OWZzvX6aiQ__;
	}

	public function append(QRbitstream $arg)
	{
		if (is_null($arg)) {
			return -1;
		}

		if ($arg->size() == 0) {
			return 0;
		}

		if ($this->size() == 0) {
			$this->data = $arg->data;
			return 0;
		}

		$this->data = array_values(array_merge($this->data, $arg->data));
		return 0;
	}

	public function appendNum($bits, $num)
	{
		if ($bits == 0) {
			return 0;
		}

		$b = QRbitstream::newFromNum($bits, $num);

		if (is_null($b)) {
			return -1;
		}

		$ret = $this->append($b);
		unset($b);
		return $ret;
	}

	public function appendBytes($size, $data)
	{
		if ($size == 0) {
			return 0;
		}

		$b = QRbitstream::newFromBytes($size, $data);

		if (is_null($b)) {
			return -1;
		}

		$ret = $this->append($b);
		unset($b);
		return $ret;
	}

	public function toByte()
	{
		$_obf_hNQa0g__ = $this->size();

		if ($_obf_hNQa0g__ == 0) {
			return array();
		}

		$_obf_6RYLWQ__ = array_fill(0, (int) ($_obf_hNQa0g__ + 7) / 8, 0);
		$_obf_KUMWfcg_ = (int) $_obf_hNQa0g__ / 8;
		$_obf_8w__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_KUMWfcg_; $_obf_7w__++) {
			$_obf_6A__ = 0;
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < 8; $_obf_XA__++) {
				$_obf_6A__ = $_obf_6A__ << 1;
				$_obf_6A__ |= $this->data[$_obf_8w__];
				$_obf_8w__++;
			}

			$_obf_6RYLWQ__[$_obf_7w__] = $_obf_6A__;
		}

		if ($_obf_hNQa0g__ & 7) {
			$_obf_6A__ = 0;
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < ($_obf_hNQa0g__ & 7); $_obf_XA__++) {
				$_obf_6A__ = $_obf_6A__ << 1;
				$_obf_6A__ |= $this->data[$_obf_8w__];
				$_obf_8w__++;
			}

			$_obf_6RYLWQ__[$_obf_KUMWfcg_] = $_obf_6A__;
		}

		return $_obf_6RYLWQ__;
	}
}

class QRsplit
{
	public $dataStr = '';
	public $input;
	public $modeHint;

	public function __construct($dataStr, $input, $modeHint)
	{
		$this->dataStr = $dataStr;
		$this->input = $input;
		$this->modeHint = $modeHint;
	}

	static public function isdigitat($str, $pos)
	{
		if (strlen($str) <= $pos) {
			return false;
		}

		return (ord('0') <= ord($str[$pos])) && (ord($str[$pos]) <= ord('9'));
	}

	static public function isalnumat($str, $pos)
	{
		if (strlen($str) <= $pos) {
			return false;
		}

		return 0 <= QRinput::lookAnTable(ord($str[$pos]));
	}

	public function identifyMode($pos)
	{
		if (strlen($this->dataStr) <= $pos) {
			return QR_MODE_NUL;
		}

		$_obf_KQ__ = $this->dataStr[$pos];

		if (self::isdigitat($this->dataStr, $pos)) {
			return QR_MODE_NUM;
		}
		else if (self::isalnumat($this->dataStr, $pos)) {
			return QR_MODE_AN;
		}
		else if ($this->modeHint == QR_MODE_KANJI) {
			if (($pos + 1) < strlen($this->dataStr)) {
				$_obf_5g__ = $this->dataStr[$pos + 1];
				$_obf_sxJqFA__ = (ord($_obf_KQ__) << 8) | ord($_obf_5g__);
				if (((33088 <= $_obf_sxJqFA__) && ($_obf_sxJqFA__ <= 40956)) || ((57408 <= $_obf_sxJqFA__) && ($_obf_sxJqFA__ <= 60351))) {
					return QR_MODE_KANJI;
				}
			}
		}

		return QR_MODE_8;
	}

	public function eatNum()
	{
		$_obf_lI8_ = QRspec::lengthIndicator(QR_MODE_NUM, $this->input->getVersion());
		$_obf_8w__ = 0;

		while (self::isdigitat($this->dataStr, $_obf_8w__)) {
			$_obf_8w__++;
		}

		$_obf_10Rm = $_obf_8w__;
		$_obf_eLlzdw__ = $this->identifyMode($_obf_8w__);

		if ($_obf_eLlzdw__ == QR_MODE_8) {
			$_obf_We9a = (QRinput::estimateBitsModeNum($_obf_10Rm) + 4 + $_obf_lI8_ + QRinput::estimateBitsMode8(1)) - QRinput::estimateBitsMode8($_obf_10Rm + 1);

			if (0 < $_obf_We9a) {
				return $this->eat8();
			}
		}

		if ($_obf_eLlzdw__ == QR_MODE_AN) {
			$_obf_We9a = (QRinput::estimateBitsModeNum($_obf_10Rm) + 4 + $_obf_lI8_ + QRinput::estimateBitsModeAn(1)) - QRinput::estimateBitsModeAn($_obf_10Rm + 1);

			if (0 < $_obf_We9a) {
				return $this->eatAn();
			}
		}

		$_obf_Xtyr = $this->input->append(QR_MODE_NUM, $_obf_10Rm, str_split($this->dataStr));

		if ($_obf_Xtyr < 0) {
			return -1;
		}

		return $_obf_10Rm;
	}

	public function eatAn()
	{
		$_obf_pWk_ = QRspec::lengthIndicator(QR_MODE_AN, $this->input->getVersion());
		$_obf_lI8_ = QRspec::lengthIndicator(QR_MODE_NUM, $this->input->getVersion());
		$_obf_8w__ = 0;

		while (self::isalnumat($this->dataStr, $_obf_8w__)) {
			if (self::isdigitat($this->dataStr, $_obf_8w__)) {
				$_obf_Bw__ = $_obf_8w__;

				while (self::isdigitat($this->dataStr, $_obf_Bw__)) {
					$_obf_Bw__++;
				}

				$_obf_We9a = (QRinput::estimateBitsModeAn($_obf_8w__) + QRinput::estimateBitsModeNum($_obf_Bw__ - $_obf_8w__) + 4 + $_obf_lI8_) - QRinput::estimateBitsModeAn($_obf_Bw__);

				if ($_obf_We9a < 0) {
					break;
				}
				else {
					$_obf_8w__ = $_obf_Bw__;
				}
			}
			else {
				$_obf_8w__++;
			}
		}

		$_obf_10Rm = $_obf_8w__;

		if (!self::isalnumat($this->dataStr, $_obf_8w__)) {
			$_obf_We9a = (QRinput::estimateBitsModeAn($_obf_10Rm) + 4 + $_obf_pWk_ + QRinput::estimateBitsMode8(1)) - QRinput::estimateBitsMode8($_obf_10Rm + 1);

			if (0 < $_obf_We9a) {
				return $this->eat8();
			}
		}

		$_obf_Xtyr = $this->input->append(QR_MODE_AN, $_obf_10Rm, str_split($this->dataStr));

		if ($_obf_Xtyr < 0) {
			return -1;
		}

		return $_obf_10Rm;
	}

	public function eatKanji()
	{
		$_obf_8w__ = 0;

		while ($this->identifyMode($_obf_8w__) == QR_MODE_KANJI) {
			$_obf_8w__ += 2;
		}

		$_obf_Xtyr = $this->input->append(QR_MODE_KANJI, $_obf_8w__, str_split($this->dataStr));

		if ($_obf_Xtyr < 0) {
			return -1;
		}

		return $_obf_10Rm;
	}

	public function eat8()
	{
		$_obf_pWk_ = QRspec::lengthIndicator(QR_MODE_AN, $this->input->getVersion());
		$_obf_lI8_ = QRspec::lengthIndicator(QR_MODE_NUM, $this->input->getVersion());
		$_obf_8w__ = 1;
		$_obf_X69KB0Tve5Ca8g__ = strlen($this->dataStr);

		while ($_obf_8w__ < $_obf_X69KB0Tve5Ca8g__) {
			$_obf_eLlzdw__ = $this->identifyMode($_obf_8w__);

			if ($_obf_eLlzdw__ == QR_MODE_KANJI) {
				break;
			}

			if ($_obf_eLlzdw__ == QR_MODE_NUM) {
				$_obf_Bw__ = $_obf_8w__;

				while (self::isdigitat($this->dataStr, $_obf_Bw__)) {
					$_obf_Bw__++;
				}

				$_obf_We9a = (QRinput::estimateBitsMode8($_obf_8w__) + QRinput::estimateBitsModeNum($_obf_Bw__ - $_obf_8w__) + 4 + $_obf_lI8_) - QRinput::estimateBitsMode8($_obf_Bw__);

				if ($_obf_We9a < 0) {
					break;
				}
				else {
					$_obf_8w__ = $_obf_Bw__;
				}
			}
			else if ($_obf_eLlzdw__ == QR_MODE_AN) {
				$_obf_Bw__ = $_obf_8w__;

				while (self::isalnumat($this->dataStr, $_obf_Bw__)) {
					$_obf_Bw__++;
				}

				$_obf_We9a = (QRinput::estimateBitsMode8($_obf_8w__) + QRinput::estimateBitsModeAn($_obf_Bw__ - $_obf_8w__) + 4 + $_obf_pWk_) - QRinput::estimateBitsMode8($_obf_Bw__);

				if ($_obf_We9a < 0) {
					break;
				}
				else {
					$_obf_8w__ = $_obf_Bw__;
				}
			}
			else {
				$_obf_8w__++;
			}
		}

		$_obf_10Rm = $_obf_8w__;
		$_obf_Xtyr = $this->input->append(QR_MODE_8, $_obf_10Rm, str_split($this->dataStr));

		if ($_obf_Xtyr < 0) {
			return -1;
		}

		return $_obf_10Rm;
	}

	public function splitString()
	{
		while (0 < strlen($this->dataStr)) {
			if ($this->dataStr == '') {
				return 0;
			}

			$_obf_eLlzdw__ = $this->identifyMode(0);

			switch ($_obf_eLlzdw__) {
			case QR_MODE_NUM:
				$_obf_Q8ERGxGW = $this->eatNum();
				break;

			case QR_MODE_AN:
				$_obf_Q8ERGxGW = $this->eatAn();
				break;

			case QR_MODE_KANJI:
				if ($_obf_hkbAmQ__ == QR_MODE_KANJI) {
					$_obf_Q8ERGxGW = $this->eatKanji();
				}
				else {
					$_obf_Q8ERGxGW = $this->eat8();
				}

				break;

			default:
				$_obf_Q8ERGxGW = $this->eat8();
				break;
			}

			if ($_obf_Q8ERGxGW == 0) {
				return 0;
			}

			if ($_obf_Q8ERGxGW < 0) {
				return -1;
			}

			$this->dataStr = substr($this->dataStr, $_obf_Q8ERGxGW);
		}
	}

	public function toUpper()
	{
		$_obf_UCtfINu64v0O = strlen($this->dataStr);
		$_obf_8w__ = 0;

		while ($_obf_8w__ < $_obf_UCtfINu64v0O) {
			$_obf_eLlzdw__ = self::identifyMode(substr($this->dataStr, $_obf_8w__), $this->modeHint);

			if ($_obf_eLlzdw__ == QR_MODE_KANJI) {
				$_obf_8w__ += 2;
			}
			else {
				if ((ord('a') <= ord($this->dataStr[$_obf_8w__])) && (ord($this->dataStr[$_obf_8w__]) <= ord('z'))) {
					$this->dataStr[$_obf_8w__] = chr(ord($this->dataStr[$_obf_8w__]) - 32);
				}

				$_obf_8w__++;
			}
		}

		return $this->dataStr;
	}

	static public function splitStringToQRinput($string, QRinput $input, $modeHint, $casesensitive = true)
	{
		if (is_null($string) || ($string == '\\0') || ($string == '')) {
			throw new Exception('empty string!!!');
		}

		$_obf_nY6vanM_ = new QRsplit($string, $input, $modeHint);

		if (!$casesensitive) {
			$_obf_nY6vanM_->toUpper();
		}

		return $_obf_nY6vanM_->splitString();
	}
}

class QRrsItem
{
	public $mm;
	public $nn;
	public $alpha_to = array();
	public $index_of = array();
	public $genpoly = array();
	public $nroots;
	public $fcr;
	public $prim;
	public $iprim;
	public $pad;
	public $gfpoly;

	public function modnn($x)
	{
		while ($this->nn <= $x) {
			$x -= $this->nn;
			$x = ($x >> $this->mm) + ($x & $this->nn);
		}

		return $x;
	}

	static public function init_rs_char($symsize, $gfpoly, $fcr, $prim, $nroots, $pad)
	{
		$_obf_SF4_ = NULL;
		if (($symsize < 0) || (8 < $symsize)) {
			return $_obf_SF4_;
		}

		if (($fcr < 0) || ((1 << $symsize) <= $fcr)) {
			return $_obf_SF4_;
		}

		if (($prim <= 0) || ((1 << $symsize) <= $prim)) {
			return $_obf_SF4_;
		}

		if (($nroots < 0) || ((1 << $symsize) <= $nroots)) {
			return $_obf_SF4_;
		}

		if (($pad < 0) || (((1 << $symsize) - 1 - $nroots) <= $pad)) {
			return $_obf_SF4_;
		}

		$_obf_SF4_ = new QRrsItem();
		$_obf_SF4_->mm = $symsize;
		$_obf_SF4_->nn = (1 << $symsize) - 1;
		$_obf_SF4_->pad = $pad;
		$_obf_SF4_->alpha_to = array_fill(0, $_obf_SF4_->nn + 1, 0);
		$_obf_SF4_->index_of = array_fill(0, $_obf_SF4_->nn + 1, 0);
		$_obf_wow_ = &$_obf_SF4_->nn;
		$_obf_mbw_ = &$_obf_wow_;
		$_obf_SF4_->index_of[0] = $_obf_mbw_;
		$_obf_SF4_->alpha_to[$_obf_mbw_] = 0;
		$_obf_kVY_ = 1;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_SF4_->nn; $_obf_7w__++) {
			$_obf_SF4_->index_of[$_obf_kVY_] = $_obf_7w__;
			$_obf_SF4_->alpha_to[$_obf_7w__] = $_obf_kVY_;
			$_obf_kVY_ <<= 1;

			if ($_obf_kVY_ & (1 << $symsize)) {
				$_obf_kVY_ ^= $gfpoly;
			}

			$_obf_kVY_ &= $_obf_SF4_->nn;
		}

		if ($_obf_kVY_ != 1) {
			$_obf_SF4_ = NULL;
			return $_obf_SF4_;
		}

		$_obf_SF4_->genpoly = array_fill(0, $nroots + 1, 0);
		$_obf_SF4_->fcr = $fcr;
		$_obf_SF4_->prim = $prim;
		$_obf_SF4_->nroots = $nroots;
		$_obf_SF4_->gfpoly = $gfpoly;
		$_obf_fSLyzRY_ = 1;

		for (; ($_obf_fSLyzRY_ % $prim) != 0; $_obf_fSLyzRY_ += $_obf_SF4_->nn) {
		}

		$_obf_SF4_->iprim = (int) $_obf_fSLyzRY_ / $prim;
		$_obf_SF4_->genpoly[0] = 1;
		$_obf_7w__ = 0;
		$_obf_Ecafng__ = $fcr * $prim;

		for (; $_obf_7w__ < $nroots; $_obf_7w__++, $_obf_Ecafng__ += $prim) {
			$_obf_SF4_->genpoly[$_obf_7w__ + 1] = 1;
			$_obf_XA__ = $_obf_7w__;

			for (; 0 < $_obf_XA__; $_obf_XA__--) {
				if ($_obf_SF4_->genpoly[$_obf_XA__] != 0) {
					$_obf_SF4_->genpoly[$_obf_XA__] = $_obf_SF4_->genpoly[$_obf_XA__ - 1] ^ $_obf_SF4_->alpha_to[$_obf_SF4_->modnn($_obf_SF4_->index_of[$_obf_SF4_->genpoly[$_obf_XA__]] + $_obf_Ecafng__)];
				}
				else {
					$_obf_SF4_->genpoly[$_obf_XA__] = $_obf_SF4_->genpoly[$_obf_XA__ - 1];
				}
			}

			$_obf_SF4_->genpoly[0] = $_obf_SF4_->alpha_to[$_obf_SF4_->modnn($_obf_SF4_->index_of[$_obf_SF4_->genpoly[0]] + $_obf_Ecafng__)];
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ <= $nroots; $_obf_7w__++) {
			$_obf_SF4_->genpoly[$_obf_7w__] = $_obf_SF4_->index_of[$_obf_SF4_->genpoly[$_obf_7w__]];
		}

		return $_obf_SF4_;
	}

	public function encode_rs_char($data, &$parity)
	{
		$_obf_4Eg_ = &$this->mm;
		$_obf_wow_ = &$this->nn;
		$_obf_ZRmHnZZTrZU_ = &$this->alpha_to;
		$_obf__pY0nevJYSE_ = &$this->index_of;
		$_obf_aF_tq7HyHA__ = &$this->genpoly;
		$_obf_YM_fOZiR = &$this->nroots;
		$_obf_Sy09 = &$this->fcr;
		$_obf_xRnfFg__ = &$this->prim;
		$_obf_xYViS9Q_ = &$this->iprim;
		$_obf_ytCg = &$this->pad;
		$_obf_mbw_ = &$_obf_wow_;
		$parity = array_fill(0, $_obf_YM_fOZiR, 0);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < ($_obf_wow_ - $_obf_YM_fOZiR - $_obf_ytCg); $_obf_7w__++) {
			$_obf_XwBFEBqHxuQ_ = $_obf__pY0nevJYSE_[$data[$_obf_7w__] ^ $parity[0]];

			if ($_obf_XwBFEBqHxuQ_ != $_obf_mbw_) {
				$_obf_XwBFEBqHxuQ_ = $this->modnn(($_obf_wow_ - $_obf_aF_tq7HyHA__[$_obf_YM_fOZiR]) + $_obf_XwBFEBqHxuQ_);
				$_obf_XA__ = 1;

				for (; $_obf_XA__ < $_obf_YM_fOZiR; $_obf_XA__++) {
					$parity[$_obf_XA__] ^= $_obf_ZRmHnZZTrZU_[$this->modnn($_obf_XwBFEBqHxuQ_ + $_obf_aF_tq7HyHA__[$_obf_YM_fOZiR - $_obf_XA__])];
				}
			}

			array_shift($parity);

			if ($_obf_XwBFEBqHxuQ_ != $_obf_mbw_) {
				array_push($parity, $_obf_ZRmHnZZTrZU_[$this->modnn($_obf_XwBFEBqHxuQ_ + $_obf_aF_tq7HyHA__[0])]);
			}
			else {
				array_push($parity, 0);
			}
		}
	}
}

class QRrs
{
	static public $items = array();

	static public function init_rs($symsize, $gfpoly, $fcr, $prim, $nroots, $pad)
	{
		foreach (self::$items as $_obf_SF4_) {
			if ($_obf_SF4_->pad != $pad) {
				continue;
			}

			if ($_obf_SF4_->nroots != $nroots) {
				continue;
			}

			if ($_obf_SF4_->mm != $symsize) {
				continue;
			}

			if ($_obf_SF4_->gfpoly != $gfpoly) {
				continue;
			}

			if ($_obf_SF4_->fcr != $fcr) {
				continue;
			}

			if ($_obf_SF4_->prim != $prim) {
				continue;
			}

			return $_obf_SF4_;
		}

		$_obf_SF4_ = QRrsItem::init_rs_char($symsize, $gfpoly, $fcr, $prim, $nroots, $pad);
		array_unshift(self::$items, $_obf_SF4_);
		return $_obf_SF4_;
	}
}

class QRmask
{
	public $runLength = array();

	public function __construct()
	{
		$this->runLength = array_fill(0, QRSPEC_WIDTH_MAX + 1, 0);
	}

	public function writeFormatInformation($width, &$frame, $mask, $level)
	{
		$_obf_e_UmWWmK = 0;
		$_obf_e7PLR79F = QRspec::getFormatInfo($mask, $level);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < 8; $_obf_7w__++) {
			if ($_obf_e7PLR79F & 1) {
				$_obf_e_UmWWmK += 2;
				$_obf_6A__ = 133;
			}
			else {
				$_obf_6A__ = 132;
			}

			$frame[8][$width - 1 - $_obf_7w__] = chr($_obf_6A__);

			if ($_obf_7w__ < 6) {
				$frame[$_obf_7w__][8] = chr($_obf_6A__);
			}
			else {
				$frame[$_obf_7w__ + 1][8] = chr($_obf_6A__);
			}

			$_obf_e7PLR79F = $_obf_e7PLR79F >> 1;
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < 7; $_obf_7w__++) {
			if ($_obf_e7PLR79F & 1) {
				$_obf_e_UmWWmK += 2;
				$_obf_6A__ = 133;
			}
			else {
				$_obf_6A__ = 132;
			}

			$frame[($width - 7) + $_obf_7w__][8] = chr($_obf_6A__);

			if ($_obf_7w__ == 0) {
				$frame[8][7] = chr($_obf_6A__);
			}
			else {
				$frame[8][6 - $_obf_7w__] = chr($_obf_6A__);
			}

			$_obf_e7PLR79F = $_obf_e7PLR79F >> 1;
		}

		return $_obf_e_UmWWmK;
	}

	public function mask0($x, $y)
	{
		return ($x + $y) & 1;
	}

	public function mask1($x, $y)
	{
		return $y & 1;
	}

	public function mask2($x, $y)
	{
		return $x % 3;
	}

	public function mask3($x, $y)
	{
		return ($x + $y) % 3;
	}

	public function mask4($x, $y)
	{
		return ((int) $y / 2 + (int) $x / 3) & 1;
	}

	public function mask5($x, $y)
	{
		return (($x * $y) & 1) + (($x * $y) % 3);
	}

	public function mask6($x, $y)
	{
		return ((($x * $y) & 1) + (($x * $y) % 3)) & 1;
	}

	public function mask7($x, $y)
	{
		return ((($x * $y) % 3) + (($x + $y) & 1)) & 1;
	}

	private function generateMaskNo($maskNo, $width, $frame)
	{
		$_obf_UnTkrlnbkg__ = array_fill(0, $width, array_fill(0, $width, 0));
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < $width; $_obf_OA__++) {
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < $width; $_obf_5Q__++) {
				if (ord($frame[$_obf_OA__][$_obf_5Q__]) & 128) {
					$_obf_UnTkrlnbkg__[$_obf_OA__][$_obf_5Q__] = 0;
				}
				else {
					$_obf_6dSVHlIZ_LM_ = call_user_func(array($this, 'mask' . $maskNo), $_obf_5Q__, $_obf_OA__);
					$_obf_UnTkrlnbkg__[$_obf_OA__][$_obf_5Q__] = $_obf_6dSVHlIZ_LM_ == 0 ? 1 : 0;
				}
			}
		}

		return $_obf_UnTkrlnbkg__;
	}

	static public function serial($bitFrame)
	{
		$_obf_x07omPvMqA__ = array();

		foreach ($bitFrame as $_obf_CFGoDA__) {
			$_obf_x07omPvMqA__[] = join('', $_obf_CFGoDA__);
		}

		return gzcompress(join("\n", $_obf_x07omPvMqA__), 9);
	}

	static public function unserial($code)
	{
		$_obf_x07omPvMqA__ = array();
		$_obf_B4NO3KRdnmh_ = explode("\n", gzuncompress($code));

		foreach ($_obf_B4NO3KRdnmh_ as $_obf_CFGoDA__) {
			$_obf_x07omPvMqA__[] = str_split($_obf_CFGoDA__);
		}

		return $_obf_x07omPvMqA__;
	}

	public function makeMaskNo($maskNo, $width, $s, &$d, $maskGenOnly = false)
	{
		$_obf_8A__ = 0;
		$_obf_UnTkrlnbkg__ = array();
		$_obf_PW9SQhMxAgA_ = QR_CACHE_DIR . 'mask_' . $maskNo . DIRECTORY_SEPARATOR . 'mask_' . $width . '_' . $maskNo . '.dat';

		if (QR_CACHEABLE) {
			if (file_exists($_obf_PW9SQhMxAgA_)) {
				$_obf_UnTkrlnbkg__ = self::unserial(file_get_contents($_obf_PW9SQhMxAgA_));
			}
			else {
				$_obf_UnTkrlnbkg__ = $this->generateMaskNo($maskNo, $width, $s, $d);

				if (!file_exists(QR_CACHE_DIR . 'mask_' . $maskNo)) {
					mkdir(QR_CACHE_DIR . 'mask_' . $maskNo);
				}

				file_put_contents($_obf_PW9SQhMxAgA_, self::serial($_obf_UnTkrlnbkg__));
			}
		}
		else {
			$_obf_UnTkrlnbkg__ = $this->generateMaskNo($maskNo, $width, $s, $d);
		}

		if ($maskGenOnly) {
			return NULL;
		}

		$d = $s;
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < $width; $_obf_OA__++) {
			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < $width; $_obf_5Q__++) {
				if ($_obf_UnTkrlnbkg__[$_obf_OA__][$_obf_5Q__] == 1) {
					$d[$_obf_OA__][$_obf_5Q__] = chr(ord($s[$_obf_OA__][$_obf_5Q__]) ^ (int) $_obf_UnTkrlnbkg__[$_obf_OA__][$_obf_5Q__]);
				}

				$_obf_8A__ += (int) ord($d[$_obf_OA__][$_obf_5Q__]) & 1;
			}
		}

		return $_obf_8A__;
	}

	public function makeMask($width, $frame, $maskNo, $level)
	{
		$_obf_zoDVb5Ad = array_fill(0, $width, str_repeat('' . "\0" . '', $width));
		$this->makeMaskNo($maskNo, $width, $frame, $_obf_zoDVb5Ad);
		$this->writeFormatInformation($width, $_obf_zoDVb5Ad, $maskNo, $level);
		return $_obf_zoDVb5Ad;
	}

	public function calcN1N3($length)
	{
		$_obf_GqI93BQ21g__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $length; $_obf_7w__++) {
			if (5 <= $this->runLength[$_obf_7w__]) {
				$_obf_GqI93BQ21g__ += N1 + ($this->runLength[$_obf_7w__] - 5);
			}

			if ($_obf_7w__ & 1) {
				if ((3 <= $_obf_7w__) && ($_obf_7w__ < ($length - 2)) && (($this->runLength[$_obf_7w__] % 3) == 0)) {
					$_obf_3k3ylg__ = (int) $this->runLength[$_obf_7w__] / 3;
					if (($this->runLength[$_obf_7w__ - 2] == $_obf_3k3ylg__) && ($this->runLength[$_obf_7w__ - 1] == $_obf_3k3ylg__) && ($this->runLength[$_obf_7w__ + 1] == $_obf_3k3ylg__) && ($this->runLength[$_obf_7w__ + 2] == $_obf_3k3ylg__)) {
						if (($this->runLength[$_obf_7w__ - 3] < 0) || ((4 * $_obf_3k3ylg__) <= $this->runLength[$_obf_7w__ - 3])) {
							$_obf_GqI93BQ21g__ += N3;
						}
						else {
							if (($length <= $_obf_7w__ + 3) || ((4 * $_obf_3k3ylg__) <= $this->runLength[$_obf_7w__ + 3])) {
								$_obf_GqI93BQ21g__ += N3;
							}
						}
					}
				}
			}
		}

		return $_obf_GqI93BQ21g__;
	}

	public function evaluateSymbol($width, $frame)
	{
		$_obf__o37TQ__ = 0;
		$_obf_GqI93BQ21g__ = 0;
		$_obf_OA__ = 0;

		for (; $_obf_OA__ < $width; $_obf_OA__++) {
			$_obf__o37TQ__ = 0;
			$this->runLength[0] = 1;
			$_obf_7jP9RDOy = $frame[$_obf_OA__];

			if (0 < $_obf_OA__) {
				$_obf_bklNV7FvBQ__ = $frame[$_obf_OA__ - 1];
			}

			$_obf_5Q__ = 0;

			for (; $_obf_5Q__ < $width; $_obf_5Q__++) {
				if ((0 < $_obf_5Q__) && (0 < $_obf_OA__)) {
					$_obf_h765 = ord($_obf_7jP9RDOy[$_obf_5Q__]) & ord($_obf_7jP9RDOy[$_obf_5Q__ - 1]) & ord($_obf_bklNV7FvBQ__[$_obf_5Q__]) & ord($_obf_bklNV7FvBQ__[$_obf_5Q__ - 1]);
					$_obf_RSLn = ord($_obf_7jP9RDOy[$_obf_5Q__]) | ord($_obf_7jP9RDOy[$_obf_5Q__ - 1]) | ord($_obf_bklNV7FvBQ__[$_obf_5Q__]) | ord($_obf_bklNV7FvBQ__[$_obf_5Q__ - 1]);

					if (($_obf_h765 | ($_obf_RSLn ^ 1)) & 1) {
						$_obf_GqI93BQ21g__ += N2;
					}
				}

				if (($_obf_5Q__ == 0) && (ord($_obf_7jP9RDOy[$_obf_5Q__]) & 1)) {
					$this->runLength[0] = -1;
					$_obf__o37TQ__ = 1;
					$this->runLength[$_obf__o37TQ__] = 1;
				}
				else if (0 < $_obf_5Q__) {
					if ((ord($_obf_7jP9RDOy[$_obf_5Q__]) ^ ord($_obf_7jP9RDOy[$_obf_5Q__ - 1])) & 1) {
						$_obf__o37TQ__++;
						$this->runLength[$_obf__o37TQ__] = 1;
					}
					else {
						$this->runLength[$_obf__o37TQ__]++;
					}
				}
			}

			$_obf_GqI93BQ21g__ += $this->calcN1N3($_obf__o37TQ__ + 1);
		}

		$_obf_5Q__ = 0;

		for (; $_obf_5Q__ < $width; $_obf_5Q__++) {
			$_obf__o37TQ__ = 0;
			$this->runLength[0] = 1;
			$_obf_OA__ = 0;

			for (; $_obf_OA__ < $width; $_obf_OA__++) {
				if (($_obf_OA__ == 0) && (ord($frame[$_obf_OA__][$_obf_5Q__]) & 1)) {
					$this->runLength[0] = -1;
					$_obf__o37TQ__ = 1;
					$this->runLength[$_obf__o37TQ__] = 1;
				}
				else if (0 < $_obf_OA__) {
					if ((ord($frame[$_obf_OA__][$_obf_5Q__]) ^ ord($frame[$_obf_OA__ - 1][$_obf_5Q__])) & 1) {
						$_obf__o37TQ__++;
						$this->runLength[$_obf__o37TQ__] = 1;
					}
					else {
						$this->runLength[$_obf__o37TQ__]++;
					}
				}
			}

			$_obf_GqI93BQ21g__ += $this->calcN1N3($_obf__o37TQ__ + 1);
		}

		return $_obf_GqI93BQ21g__;
	}

	public function mask($width, $frame, $level)
	{
		$_obf_dsyjYhoM1YoA2g__ = PHP_INT_MAX;
		$_obf_kkRpvaa_gLojPIQ_ = 0;
		$_obf_DvnKWt7bgGc_ = array();
		$_obf_ZzVnbxOC079_WFqE5Q__ = array(0, 1, 2, 3, 4, 5, 6, 7);

		if (QR_FIND_FROM_RANDOM !== false) {
			$_obf_skD_Bx0CKiwJEg__ = 8 - (QR_FIND_FROM_RANDOM % 9);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_skD_Bx0CKiwJEg__; $_obf_7w__++) {
				$_obf_sLsr_nry = rand(0, count($_obf_ZzVnbxOC079_WFqE5Q__) - 1);
				unset($_obf_ZzVnbxOC079_WFqE5Q__[$_obf_sLsr_nry]);
				$_obf_ZzVnbxOC079_WFqE5Q__ = array_values($_obf_ZzVnbxOC079_WFqE5Q__);
			}
		}

		$_obf_DvnKWt7bgGc_ = $frame;

		foreach ($_obf_ZzVnbxOC079_WFqE5Q__ as $_obf_7w__) {
			$_obf_n69igA__ = array_fill(0, $width, str_repeat('' . "\0" . '', $width));
			$_obf_GqI93BQ21g__ = 0;
			$_obf_e_UmWWmK = 0;
			$_obf_e_UmWWmK = $this->makeMaskNo($_obf_7w__, $width, $frame, $_obf_n69igA__);
			$_obf_e_UmWWmK += $this->writeFormatInformation($width, $_obf_n69igA__, $_obf_7w__, $level);
			$_obf_e_UmWWmK = (int) (100 * $_obf_e_UmWWmK) / ($width * $width);
			$_obf_GqI93BQ21g__ = (int) (int) abs($_obf_e_UmWWmK - 50) / 5 * N4;
			$_obf_GqI93BQ21g__ += $this->evaluateSymbol($width, $_obf_n69igA__);

			if ($_obf_GqI93BQ21g__ < $_obf_dsyjYhoM1YoA2g__) {
				$_obf_dsyjYhoM1YoA2g__ = $_obf_GqI93BQ21g__;
				$_obf_DvnKWt7bgGc_ = $_obf_n69igA__;
				$_obf_kkRpvaa_gLojPIQ_ = $_obf_7w__;
			}
		}

		return $_obf_DvnKWt7bgGc_;
	}
}

class QRrsblock
{
	public $dataLength;
	public $data = array();
	public $eccLength;
	public $ecc = array();

	public function __construct($dl, $data, $el, &$ecc, QRrsItem $rs)
	{
		$rs->encode_rs_char($data, $ecc);
		$this->dataLength = $dl;
		$this->data = $data;
		$this->eccLength = $el;
		$this->ecc = $ecc;
	}
}

class QRrawcode
{
	public $version;
	public $datacode = array();
	public $ecccode = array();
	public $blocks;
	public $rsblocks = array();
	public $count;
	public $dataLength;
	public $eccLength;
	public $b1;

	public function __construct(QRinput $input)
	{
		$_obf_ygmn8A__ = array(0, 0, 0, 0, 0);
		$this->datacode = $input->getByteStream();

		if (is_null($this->datacode)) {
			throw new Exception('null imput string');
		}

		QRspec::getEccSpec($input->getVersion(), $input->getErrorCorrectionLevel(), $_obf_ygmn8A__);
		$this->version = $input->getVersion();
		$this->b1 = QRspec::rsBlockNum1($_obf_ygmn8A__);
		$this->dataLength = QRspec::rsDataLength($_obf_ygmn8A__);
		$this->eccLength = QRspec::rsEccLength($_obf_ygmn8A__);
		$this->ecccode = array_fill(0, $this->eccLength, 0);
		$this->blocks = QRspec::rsBlockNum($_obf_ygmn8A__);
		$_obf_Xtyr = $this->init($_obf_ygmn8A__);

		if ($_obf_Xtyr < 0) {
			throw new Exception('block alloc error');
			return NULL;
		}

		$this->count = 0;
	}

	public function init(array $spec)
	{
		$_obf_Zc0_ = QRspec::rsDataCodes1($spec);
		$_obf_AK0_ = QRspec::rsEccCodes1($spec);
		$_obf_SF4_ = QRrs::init_rs(8, 285, 0, 1, $_obf_AK0_, 255 - $_obf_Zc0_ - $_obf_AK0_);
		$_obf_YHJsxq5ykg__ = 0;
		$_obf_D6mGkoNYMw__ = 0;
		$_obf_6GHJOTRJ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < QRspec::rsBlockNum1($spec); $_obf_7w__++) {
			$_obf_hZLH = array_slice($this->ecccode, $_obf_6GHJOTRJ);
			$this->rsblocks[$_obf_YHJsxq5ykg__] = new QRrsblock($_obf_Zc0_, array_slice($this->datacode, $_obf_D6mGkoNYMw__), $_obf_AK0_, $_obf_hZLH, $_obf_SF4_);
			$this->ecccode = array_merge(array_slice($this->ecccode, 0, $_obf_6GHJOTRJ), $_obf_hZLH);
			$_obf_D6mGkoNYMw__ += $_obf_Zc0_;
			$_obf_6GHJOTRJ += $_obf_AK0_;
			$_obf_YHJsxq5ykg__++;
		}

		if (QRspec::rsBlockNum2($spec) == 0) {
			return 0;
		}

		$_obf_Zc0_ = QRspec::rsDataCodes2($spec);
		$_obf_AK0_ = QRspec::rsEccCodes2($spec);
		$_obf_SF4_ = QRrs::init_rs(8, 285, 0, 1, $_obf_AK0_, 255 - $_obf_Zc0_ - $_obf_AK0_);

		if ($_obf_SF4_ == NULL) {
			return -1;
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < QRspec::rsBlockNum2($spec); $_obf_7w__++) {
			$_obf_hZLH = array_slice($this->ecccode, $_obf_6GHJOTRJ);
			$this->rsblocks[$_obf_YHJsxq5ykg__] = new QRrsblock($_obf_Zc0_, array_slice($this->datacode, $_obf_D6mGkoNYMw__), $_obf_AK0_, $_obf_hZLH, $_obf_SF4_);
			$this->ecccode = array_merge(array_slice($this->ecccode, 0, $_obf_6GHJOTRJ), $_obf_hZLH);
			$_obf_D6mGkoNYMw__ += $_obf_Zc0_;
			$_obf_6GHJOTRJ += $_obf_AK0_;
			$_obf_YHJsxq5ykg__++;
		}

		return 0;
	}

	public function getCode()
	{
		if ($this->count < $this->dataLength) {
			$_obf_g_kt = $this->count % $this->blocks;
			$_obf_u_FB = $this->count / $this->blocks;

			if ($this->rsblocks[0]->dataLength <= $_obf_u_FB) {
				$_obf_g_kt += $this->b1;
			}

			$_obf_Xtyr = $this->rsblocks[$_obf_g_kt]->data[$_obf_u_FB];
		}
		else if ($this->count < ($this->dataLength + $this->eccLength)) {
			$_obf_g_kt = ($this->count - $this->dataLength) % $this->blocks;
			$_obf_u_FB = ($this->count - $this->dataLength) / $this->blocks;
			$_obf_Xtyr = $this->rsblocks[$_obf_g_kt]->ecc[$_obf_u_FB];
		}
		else {
			return 0;
		}

		$this->count++;
		return $_obf_Xtyr;
	}
}

class QRcode
{
	public $version;
	public $width;
	public $data;

	public function encodeMask(QRinput $input, $mask)
	{
		if (($input->getVersion() < 0) || (QRSPEC_VERSION_MAX < $input->getVersion())) {
			throw new Exception('wrong version');
		}

		if (QR_ECLEVEL_H < $input->getErrorCorrectionLevel()) {
			throw new Exception('wrong level');
		}

		$raw = new QRrawcode($input);
		QRtools::markTime('after_raw');
		$version = $raw->version;
		$width = QRspec::getWidth($version);
		$frame = QRspec::newFrame($version);
		$filler = new FrameFiller($width, $frame);

		if (is_null($filler)) {
			return NULL;
		}

		$i = 0;

		for (; $i < ($raw->dataLength + $raw->eccLength); $i++) {
			$code = $raw->getCode();
			$bit = 128;
			$j = 0;

			for (; $j < 8; $j++) {
				$addr = $filler->next();
				$filler->setFrameAt($addr, 2 | (($bit & $code) != 0));
				$bit = $bit >> 1;
			}
		}

		QRtools::markTime('after_filler');
		unset($raw);
		$j = QRspec::getRemainder($version);
		$i = 0;

		for (; $i < $j; $i++) {
			$addr = $filler->next();
			$filler->setFrameAt($addr, 2);
		}

		$frame = $filler->frame;
		unset($filler);
		$maskObj = new QRmask();

		if ($mask < 0) {
			if (QR_FIND_BEST_MASK) {
				$masked = $maskObj->mask($width, $frame, $input->getErrorCorrectionLevel());
			}
			else {
				$masked = $maskObj->makeMask($width, $frame, intval(QR_DEFAULT_MASK) % 8, $input->getErrorCorrectionLevel());
			}
		}
		else {
			$masked = $maskObj->makeMask($width, $frame, $mask, $input->getErrorCorrectionLevel());
		}

		if ($masked == NULL) {
			return NULL;
		}

		QRtools::markTime('after_mask');
		$this->version = $version;
		$this->width = $width;
		$this->data = $masked;
		return $this;
	}

	public function encodeInput(QRinput $input)
	{
		return $this->encodeMask($input, -1);
	}

	public function encodeString8bit($string, $version, $level)
	{
		if (string == NULL) {
			throw new Exception('empty string!');
			return NULL;
		}

		$input = new QRinput($version, $level);

		if ($input == NULL) {
			return NULL;
		}

		$ret = $input->append($input, QR_MODE_8, strlen($string), str_split($string));

		if ($ret < 0) {
			unset($input);
			return NULL;
		}

		return $this->encodeInput($input);
	}

	public function encodeString($string, $version, $level, $hint, $casesensitive)
	{
		if (($hint != QR_MODE_8) && ($hint != QR_MODE_KANJI)) {
			throw new Exception('bad hint');
			return NULL;
		}

		$_obf_zVJrf9E_ = new QRinput($version, $level);

		if ($_obf_zVJrf9E_ == NULL) {
			return NULL;
		}

		$_obf_Xtyr = QRsplit::splitStringToQRinput($string, $_obf_zVJrf9E_, $hint, $casesensitive);

		if ($_obf_Xtyr < 0) {
			return NULL;
		}

		return $this->encodeInput($_obf_zVJrf9E_);
	}

	static public function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint = false)
	{
		$_obf_JNVy = QRencode::factory($level, $size, $margin);
		return $_obf_JNVy->encodePNG($text, $outfile, $saveandprint = false);
	}

	static public function text($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
	{
		$_obf_JNVy = QRencode::factory($level, $size, $margin);
		return $_obf_JNVy->encode($text, $outfile);
	}

	static public function raw($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4)
	{
		$_obf_JNVy = QRencode::factory($level, $size, $margin);
		return $_obf_JNVy->encodeRAW($text, $outfile);
	}

	public function img($text, $outfile = false)
	{
		return QRcode::png($text, $outfile);
	}
}

class FrameFiller
{
	public $width;
	public $frame;
	public $x;
	public $y;
	public $dir;
	public $bit;

	public function __construct($width, &$frame)
	{
		$this->width = $width;
		$this->frame = $frame;
		$this->x = $width - 1;
		$this->y = $width - 1;
		$this->dir = -1;
		$this->bit = -1;
	}

	public function setFrameAt($at, $val)
	{
		$this->frame[$at['y']][$at['x']] = chr($val);
	}

	public function getFrameAt($at)
	{
		return ord($this->frame[$at['y']][$at['x']]);
	}

	public function next()
	{
		do {
			if ($this->bit == -1) {
				$this->bit = 0;
				return array('x' => $this->x, 'y' => $this->y);
			}

			$_obf_5Q__ = $this->x;
			$_obf_OA__ = $this->y;
			$_obf_hg__ = $this->width;

			if ($this->bit == 0) {
				$_obf_5Q__--;
				$this->bit++;
			}
			else {
				$_obf_5Q__++;
				$_obf_OA__ += $this->dir;
				$this->bit--;
			}

			if ($this->dir < 0) {
				if ($_obf_OA__ < 0) {
					$_obf_OA__ = 0;
					$_obf_5Q__ -= 2;
					$this->dir = 1;

					if ($_obf_5Q__ == 6) {
						$_obf_5Q__--;
						$_obf_OA__ = 9;
					}
				}
			}
			else if ($_obf_OA__ == $_obf_hg__) {
				$_obf_OA__ = $_obf_hg__ - 1;
				$_obf_5Q__ -= 2;
				$this->dir = -1;

				if ($_obf_5Q__ == 6) {
					$_obf_5Q__--;
					$_obf_OA__ -= 8;
				}
			}

			if (($_obf_5Q__ < 0) || ($_obf_OA__ < 0)) {
				return NULL;
			}

			$this->x = $_obf_5Q__;
			$this->y = $_obf_OA__;
		} while (ord($this->frame[$_obf_OA__][$_obf_5Q__]) & 128);

		return array('x' => $_obf_5Q__, 'y' => $_obf_OA__);
	}
}

class QRencode
{
	public $casesensitive = true;
	public $eightbit = false;
	public $version = 0;
	public $size = 3;
	public $margin = 4;
	public $structured = 0;
	public $level = QR_ECLEVEL_L;
	public $hint = QR_MODE_8;

	static public function factory($level = QR_ECLEVEL_L, $size = 3, $margin = 4)
	{
		$_obf_JNVy = new QRencode();
		$_obf_JNVy->size = $size;
		$_obf_JNVy->margin = $margin;

		switch ($level . '') {
		case '0':
		case '1':
		case '2':
		case '3':
			$_obf_JNVy->level = $level;
			break;

		case 'l':
		case 'L':
			$_obf_JNVy->level = QR_ECLEVEL_L;
			break;

		case 'm':
		case 'M':
			$_obf_JNVy->level = QR_ECLEVEL_M;
			break;

		case 'q':
		case 'Q':
			$_obf_JNVy->level = QR_ECLEVEL_Q;
			break;

		case 'h':
		case 'H':
			$_obf_JNVy->level = QR_ECLEVEL_H;
			break;
		}

		return $_obf_JNVy;
	}

	public function encodeRAW($intext, $outfile = false)
	{
		$_obf_olwD8Q__ = new QRcode();

		if ($this->eightbit) {
			$_obf_olwD8Q__->encodeString8bit($intext, $this->version, $this->level);
		}
		else {
			$_obf_olwD8Q__->encodeString($intext, $this->version, $this->level, $this->hint, $this->casesensitive);
		}

		return $_obf_olwD8Q__->data;
	}

	public function encode($intext, $outfile = false)
	{
		$_obf_olwD8Q__ = new QRcode();

		if ($this->eightbit) {
			$_obf_olwD8Q__->encodeString8bit($intext, $this->version, $this->level);
		}
		else {
			$_obf_olwD8Q__->encodeString($intext, $this->version, $this->level, $this->hint, $this->casesensitive);
		}

		QRtools::markTime('after_encode');

		if ($outfile !== false) {
			file_put_contents($outfile, join("\n", QRtools::binarize($_obf_olwD8Q__->data)));
		}
		else {
			return QRtools::binarize($_obf_olwD8Q__->data);
		}
	}

	public function encodePNG($intext, $outfile = false, $saveandprint = false)
	{
		try {
			ob_start();
			$_obf_k5mp = $this->encode($intext);
			$_obf_M88D = ob_get_contents();
			ob_end_clean();

			if ($_obf_M88D != '') {
				QRtools::log($outfile, $_obf_M88D);
			}

			$_obf_2u9pc9hQ2A__ = (int) QR_PNG_MAXIMUM_SIZE / (count($_obf_k5mp) + (2 * $this->margin));
			QRimage::png($_obf_k5mp, $outfile, min(max(1, $this->size), $_obf_2u9pc9hQ2A__), $this->margin, $saveandprint);
		}
		catch (Exception $_obf_hA__) {
			QRtools::log($outfile, $_obf_hA__->getMessage());
		}
	}
}

define('QR_MODE_NUL', -1);
define('QR_MODE_NUM', 0);
define('QR_MODE_AN', 1);
define('QR_MODE_8', 2);
define('QR_MODE_KANJI', 3);
define('QR_MODE_STRUCTURE', 4);
define('QR_ECLEVEL_L', 0);
define('QR_ECLEVEL_M', 1);
define('QR_ECLEVEL_Q', 2);
define('QR_ECLEVEL_H', 3);
define('QR_FORMAT_TEXT', 0);
define('QR_FORMAT_PNG', 1);
define('QR_CACHEABLE', false);
define('QR_CACHE_DIR', false);
define('QR_LOG_DIR', false);
define('QR_FIND_BEST_MASK', true);
define('QR_FIND_FROM_RANDOM', 2);
define('QR_DEFAULT_MASK', 2);
define('QR_PNG_MAXIMUM_SIZE', 1024);
QRtools::markTime('start');
define('QRSPEC_VERSION_MAX', 40);
define('QRSPEC_WIDTH_MAX', 177);
define('QRCAP_WIDTH', 0);
define('QRCAP_WORDS', 1);
define('QRCAP_REMINDER', 2);
define('QRCAP_EC', 3);
define('QR_IMAGE', true);
define('STRUCTURE_HEADER_BITS', 20);
define('MAX_STRUCTURED_SYMBOLS', 16);
define('N1', 3);
define('N2', 3);
define('N3', 40);
define('N4', 10);

?>
