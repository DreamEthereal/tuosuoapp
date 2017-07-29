<?php
//dezend by http://www.yunlu99.com/
class Class_Math_BigInteger
{
	public $value;
	public $is_negative = false;
	public $generator = 'mt_rand';
	public $precision = -1;
	public $bitmask = false;
	public $hex;

	public function Class_Math_BigInteger($x = 0, $base = 10)
	{
		if (!defined('MATH_BIGINTEGER_MODE')) {
			switch (true) {
			case extension_loaded('gmp'):
				define('MATH_BIGINTEGER_MODE', MATH_BIGINTEGER_MODE_GMP);
				break;

			case extension_loaded('bcmath'):
				define('MATH_BIGINTEGER_MODE', MATH_BIGINTEGER_MODE_BCMATH);
				break;

			default:
				define('MATH_BIGINTEGER_MODE', MATH_BIGINTEGER_MODE_INTERNAL);
			}
		}

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			if (is_resource($x) && (get_resource_type($x) == 'GMP integer')) {
				$this->value = $x;
				return NULL;
			}

			$this->value = gmp_init(0);
			break;

		case MATH_BIGINTEGER_MODE_BCMATH:
			$this->value = '0';
			break;

		default:
			$this->value = array();
		}

		if (empty($x)) {
			return NULL;
		}

		switch ($base) {
		case -256:
			if (ord($x[0]) & 128) {
				$x = ~$x;
				$this->is_negative = true;
			}
		case 256:
			switch (MATH_BIGINTEGER_MODE) {
			case MATH_BIGINTEGER_MODE_GMP:
				$sign = ($this->is_negative ? '-' : '');
				$this->value = gmp_init($sign . '0x' . bin2hex($x));
				break;

			case MATH_BIGINTEGER_MODE_BCMATH:
				$len = (strlen($x) + 3) & 4294967292;
				$x = str_pad($x, $len, chr(0), STR_PAD_LEFT);
				$i = 0;

				for (; $i < $len; $i += 4) {
					$this->value = bcmul($this->value, '4294967296', 0);
					$this->value = bcadd($this->value, (16777216 * ord($x[$i])) + ((ord($x[$i + 1]) << 16) | (ord($x[$i + 2]) << 8) | ord($x[$i + 3])), 0);
				}

				if ($this->is_negative) {
					$this->value = '-' . $this->value;
				}

				break;

			default:
				while (strlen($x)) {
					$this->value[] = $this->_bytes2int($this->_base256_rshift($x, 26));
				}
			}

			if ($this->is_negative) {
				if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
					$this->is_negative = false;
				}

				$temp = $this->add(new Class_Math_BigInteger('-1'));
				$this->value = $temp->value;
			}

			break;

		case 16:
		case -16:
			if ((0 < $base) && ($x[0] == '-')) {
				$this->is_negative = true;
				$x = substr($x, 1);
			}

			$x = preg_replace('#^(?:0x)?([A-Fa-f0-9]*).*#', '$1', $x);
			$is_negative = false;
			if (($base < 0) && (8 <= hexdec($x[0]))) {
				$this->is_negative = $is_negative = true;
				$x = bin2hex(~pack('H*', $x));
			}

			switch (MATH_BIGINTEGER_MODE) {
			case MATH_BIGINTEGER_MODE_GMP:
				$temp = ($this->is_negative ? '-0x' . $x : '0x' . $x);
				$this->value = gmp_init($temp);
				$this->is_negative = false;
				break;

			case MATH_BIGINTEGER_MODE_BCMATH:
				$x = (strlen($x) & 1 ? '0' . $x : $x);
				$temp = new Class_Math_BigInteger(pack('H*', $x), 256);
				$this->value = $this->is_negative ? '-' . $temp->value : $temp->value;
				$this->is_negative = false;
				break;

			default:
				$x = (strlen($x) & 1 ? '0' . $x : $x);
				$temp = new Class_Math_BigInteger(pack('H*', $x), 256);
				$this->value = $temp->value;
			}

			if ($is_negative) {
				$temp = $this->add(new Class_Math_BigInteger('-1'));
				$this->value = $temp->value;
			}

			break;

		case 10:
		case -10:
			$x = preg_replace('#^(-?[0-9]*).*#', '$1', $x);

			switch (MATH_BIGINTEGER_MODE) {
			case MATH_BIGINTEGER_MODE_GMP:
				$this->value = gmp_init($x);
				break;

			case MATH_BIGINTEGER_MODE_BCMATH:
				$this->value = (string) $x;
				break;

			default:
				$temp = new Class_Math_BigInteger();
				$multiplier = new Class_Math_BigInteger();
				$multiplier->value = array(10000000);

				if ($x[0] == '-') {
					$this->is_negative = true;
					$x = substr($x, 1);
				}

				$x = str_pad($x, strlen($x) + ((6 * strlen($x)) % 7), 0, STR_PAD_LEFT);

				while (strlen($x)) {
					$temp = $temp->multiply($multiplier);
					$temp = $temp->add(new Class_Math_BigInteger($this->_int2bytes(substr($x, 0, 7)), 256));
					$x = substr($x, 7);
				}

				$this->value = $temp->value;
			}

			break;

		case 2:
		case -2:
			if ((0 < $base) && ($x[0] == '-')) {
				$this->is_negative = true;
				$x = substr($x, 1);
			}

			$x = preg_replace('#^([01]*).*#', '$1', $x);
			$x = str_pad($x, strlen($x) + ((3 * strlen($x)) % 4), 0, STR_PAD_LEFT);
			$str = '0x';

			while (strlen($x)) {
				$part = substr($x, 0, 4);
				$str .= dechex(bindec($part));
				$x = substr($x, 4);
			}

			if ($this->is_negative) {
				$str = '-' . $str;
			}

			$temp = new Class_Math_BigInteger($str, 8 * $base);
			$this->value = $temp->value;
			$this->is_negative = $temp->is_negative;
			break;

		default:
		}
	}

	public function toBytes($twos_compliment = false)
	{
		if ($twos_compliment) {
			$comparison = $this->compare(new Class_Math_BigInteger());

			if ($comparison == 0) {
				return 0 < $this->precision ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
			}

			$temp = ($comparison < 0 ? $this->add(new Class_Math_BigInteger(1)) : $this->copy());
			$bytes = $temp->toBytes();

			if (empty($bytes)) {
				$bytes = chr(0);
			}

			if (ord($bytes[0]) & 128) {
				$bytes = chr(0) . $bytes;
			}

			return $comparison < 0 ? ~$bytes : $bytes;
		}

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			if (gmp_cmp($this->value, gmp_init(0)) == 0) {
				return 0 < $this->precision ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
			}

			$temp = gmp_strval(gmp_abs($this->value), 16);
			$temp = (strlen($temp) & 1 ? '0' . $temp : $temp);
			$temp = pack('H*', $temp);
			return 0 < $this->precision ? substr(str_pad($temp, $this->precision >> 3, chr(0), STR_PAD_LEFT), 0 - ($this->precision >> 3)) : ltrim($temp, chr(0));
		case MATH_BIGINTEGER_MODE_BCMATH:
			if ($this->value === '0') {
				return 0 < $this->precision ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
			}

			$value = '';
			$current = $this->value;

			if ($current[0] == '-') {
				$current = substr($current, 1);
			}

			while (0 < bccomp($current, '0', 0)) {
				$temp = bcmod($current, '16777216');
				$value = chr($temp >> 16) . chr($temp >> 8) . chr($temp) . $value;
				$current = bcdiv($current, '16777216', 0);
			}

			return 0 < $this->precision ? substr(str_pad($value, $this->precision >> 3, chr(0), STR_PAD_LEFT), 0 - ($this->precision >> 3)) : ltrim($value, chr(0));
		}

		if (!count($this->value)) {
			return 0 < $this->precision ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
		}

		$result = $this->_int2bytes($this->value[count($this->value) - 1]);
		$temp = $this->copy();
		$i = count($temp->value) - 2;

		for (; 0 <= $i; --$i) {
			$temp->_base256_lshift($result, 26);
			$result = $result | str_pad($temp->_int2bytes($temp->value[$i]), strlen($result), chr(0), STR_PAD_LEFT);
		}

		return 0 < $this->precision ? str_pad(substr($result, 0 - (($this->precision + 7) >> 3)), ($this->precision + 7) >> 3, chr(0), STR_PAD_LEFT) : $result;
	}

	public function toHex($twos_compliment = false)
	{
		return bin2hex($this->toBytes($twos_compliment));
	}

	public function toBits($twos_compliment = false)
	{
		$_obf_0LTM = $this->toHex($twos_compliment);
		$_obf_rihqHw__ = '';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < strlen($_obf_0LTM); $_obf_7w__ += 8) {
			$_obf_rihqHw__ .= str_pad(decbin(hexdec(substr($_obf_0LTM, $_obf_7w__, 8))), 32, '0', STR_PAD_LEFT);
		}

		return 0 < $this->precision ? substr($_obf_rihqHw__, 0 - $this->precision) : ltrim($_obf_rihqHw__, '0');
	}

	public function toString()
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			return gmp_strval($this->value);
		case MATH_BIGINTEGER_MODE_BCMATH:
			if ($this->value === '0') {
				return '0';
			}

			return ltrim($this->value, '0');
		}

		if (!count($this->value)) {
			return '0';
		}

		$temp = $this->copy();
		$temp->is_negative = false;
		$divisor = new Class_Math_BigInteger();
		$divisor->value = array(10000000);
		$result = '';

		while (count($temp->value)) {
			list($temp, $mod) = $temp->divide($divisor);
			$result = str_pad(isset($mod->value[0]) ? $mod->value[0] : '', 7, '0', STR_PAD_LEFT) . $result;
		}

		$result = ltrim($result, '0');

		if (empty($result)) {
			$result = '0';
		}

		if ($this->is_negative) {
			$result = '-' . $result;
		}

		return $result;
	}

	public function copy()
	{
		$_obf_SeV31Q__ = new Class_Math_BigInteger();
		$_obf_SeV31Q__->value = $this->value;
		$_obf_SeV31Q__->is_negative = $this->is_negative;
		$_obf_SeV31Q__->generator = $this->generator;
		$_obf_SeV31Q__->precision = $this->precision;
		$_obf_SeV31Q__->bitmask = $this->bitmask;
		return $_obf_SeV31Q__;
	}

	public function __toString()
	{
		return $this->toString();
	}

	public function __clone()
	{
		return $this->copy();
	}

	public function __sleep()
	{
		$this->hex = $this->toHex(true);
		$_obf_wFBrqQ__ = array('hex');

		if ($this->generator != 'mt_rand') {
			$_obf_wFBrqQ__[] = 'generator';
		}

		if (0 < $this->precision) {
			$_obf_wFBrqQ__[] = 'precision';
		}

		return $_obf_wFBrqQ__;
	}

	public function __wakeup()
	{
		$_obf_SeV31Q__ = new Class_Math_BigInteger($this->hex, -16);
		$this->value = $_obf_SeV31Q__->value;
		$this->is_negative = $_obf_SeV31Q__->is_negative;
		$this->setRandomGenerator($this->generator);

		if (0 < $this->precision) {
			$this->setPrecision($this->precision);
		}
	}

	public function add($y)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_add($this->value, $y->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = bcadd($this->value, $y->value, 0);
			return $this->_normalize($_obf_SeV31Q__);
		}

		$_obf_SeV31Q__ = $this->_add($this->value, $this->is_negative, $y->value, $y->is_negative);
		$_obf_xs33Yt_k = new Class_Math_BigInteger();
		$_obf_xs33Yt_k->value = $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE];
		$_obf_xs33Yt_k->is_negative = $_obf_SeV31Q__[MATH_BIGINTEGER_SIGN];
		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function _add($x_value, $x_negative, $y_value, $y_negative)
	{
		$_obf_iLIyqZZE = count($x_value);
		$_obf_FtVDlvLn = count($y_value);

		if ($_obf_iLIyqZZE == 0) {
			return array(MATH_BIGINTEGER_VALUE => $y_value, MATH_BIGINTEGER_SIGN => $y_negative);
		}
		else if ($_obf_FtVDlvLn == 0) {
			return array(MATH_BIGINTEGER_VALUE => $x_value, MATH_BIGINTEGER_SIGN => $x_negative);
		}

		if ($x_negative != $y_negative) {
			if ($x_value == $y_value) {
				return array(
	MATH_BIGINTEGER_VALUE => array(),
	MATH_BIGINTEGER_SIGN  => false
	);
			}

			$_obf_SeV31Q__ = $this->_subtract($x_value, false, $y_value, false);
			$_obf_SeV31Q__[MATH_BIGINTEGER_SIGN] = 0 < $this->_compare($x_value, false, $y_value, false) ? $x_negative : $y_negative;
			return $_obf_SeV31Q__;
		}

		if ($_obf_iLIyqZZE < $_obf_FtVDlvLn) {
			$_obf_hNQa0g__ = $_obf_iLIyqZZE;
			$_obf_VgKtFeg_ = $y_value;
		}
		else {
			$_obf_hNQa0g__ = $_obf_FtVDlvLn;
			$_obf_VgKtFeg_ = $x_value;
		}

		$_obf_VgKtFeg_[] = 0;
		$_obf_mqEmcU0_ = 0;
		$_obf_7w__ = 0;
		$_obf_XA__ = 1;

		for (; $_obf_XA__ < $_obf_hNQa0g__; $_obf_7w__ += 2, $_obf_XA__ += 2) {
			$_obf_bhdW = ($x_value[$_obf_XA__] * 67108864) + $x_value[$_obf_7w__] + ($y_value[$_obf_XA__] * 67108864) + $y_value[$_obf_7w__] + $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = MATH_BIGINTEGER_MAX_DIGIT52 <= $_obf_bhdW;
			$_obf_bhdW = ($_obf_mqEmcU0_ ? $_obf_bhdW - MATH_BIGINTEGER_MAX_DIGIT52 : $_obf_bhdW);
			$_obf_SeV31Q__ = (int) $_obf_bhdW / 67108864;
			$_obf_VgKtFeg_[$_obf_7w__] = (int) $_obf_bhdW - (67108864 * $_obf_SeV31Q__);
			$_obf_VgKtFeg_[$_obf_XA__] = $_obf_SeV31Q__;
		}

		if ($_obf_XA__ == $_obf_hNQa0g__) {
			$_obf_bhdW = $x_value[$_obf_7w__] + $y_value[$_obf_7w__] + $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = 67108864 <= $_obf_bhdW;
			$_obf_VgKtFeg_[$_obf_7w__] = $_obf_mqEmcU0_ ? $_obf_bhdW - 67108864 : $_obf_bhdW;
			++$_obf_7w__;
		}

		if ($_obf_mqEmcU0_) {
			for (; $_obf_VgKtFeg_[$_obf_7w__] == 67108863; ++$_obf_7w__) {
				$_obf_VgKtFeg_[$_obf_7w__] = 0;
			}

			++$_obf_VgKtFeg_[$_obf_7w__];
		}

		return array(MATH_BIGINTEGER_VALUE => $this->_trim($_obf_VgKtFeg_), MATH_BIGINTEGER_SIGN => $x_negative);
	}

	public function subtract($y)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_sub($this->value, $y->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = bcsub($this->value, $y->value, 0);
			return $this->_normalize($_obf_SeV31Q__);
		}

		$_obf_SeV31Q__ = $this->_subtract($this->value, $this->is_negative, $y->value, $y->is_negative);
		$_obf_xs33Yt_k = new Class_Math_BigInteger();
		$_obf_xs33Yt_k->value = $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE];
		$_obf_xs33Yt_k->is_negative = $_obf_SeV31Q__[MATH_BIGINTEGER_SIGN];
		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function _subtract($x_value, $x_negative, $y_value, $y_negative)
	{
		$_obf_iLIyqZZE = count($x_value);
		$_obf_FtVDlvLn = count($y_value);

		if ($_obf_iLIyqZZE == 0) {
			return array(MATH_BIGINTEGER_VALUE => $y_value, MATH_BIGINTEGER_SIGN => !$y_negative);
		}
		else if ($_obf_FtVDlvLn == 0) {
			return array(MATH_BIGINTEGER_VALUE => $x_value, MATH_BIGINTEGER_SIGN => $x_negative);
		}

		if ($x_negative != $y_negative) {
			$_obf_SeV31Q__ = $this->_add($x_value, false, $y_value, false);
			$_obf_SeV31Q__[MATH_BIGINTEGER_SIGN] = $x_negative;
			return $_obf_SeV31Q__;
		}

		$_obf_SUSIHA__ = $this->_compare($x_value, $x_negative, $y_value, $y_negative);

		if (!$_obf_SUSIHA__) {
			return array(
	MATH_BIGINTEGER_VALUE => array(),
	MATH_BIGINTEGER_SIGN  => false
	);
		}

		if ((!$x_negative && ($_obf_SUSIHA__ < 0)) || ($x_negative && (0 < $_obf_SUSIHA__))) {
			$_obf_SeV31Q__ = $x_value;
			$x_value = $y_value;
			$y_value = $_obf_SeV31Q__;
			$x_negative = !$x_negative;
			$_obf_iLIyqZZE = count($x_value);
			$_obf_FtVDlvLn = count($y_value);
		}

		$_obf_mqEmcU0_ = 0;
		$_obf_7w__ = 0;
		$_obf_XA__ = 1;

		for (; $_obf_XA__ < $_obf_FtVDlvLn; $_obf_7w__ += 2, $_obf_XA__ += 2) {
			$_obf_bhdW = (($x_value[$_obf_XA__] * 67108864) + $x_value[$_obf_7w__]) - ($y_value[$_obf_XA__] * 67108864) - $y_value[$_obf_7w__] - $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = $_obf_bhdW < 0;
			$_obf_bhdW = ($_obf_mqEmcU0_ ? $_obf_bhdW + MATH_BIGINTEGER_MAX_DIGIT52 : $_obf_bhdW);
			$_obf_SeV31Q__ = (int) $_obf_bhdW / 67108864;
			$x_value[$_obf_7w__] = (int) $_obf_bhdW - (67108864 * $_obf_SeV31Q__);
			$x_value[$_obf_XA__] = $_obf_SeV31Q__;
		}

		if ($_obf_XA__ == $_obf_FtVDlvLn) {
			$_obf_bhdW = $x_value[$_obf_7w__] - $y_value[$_obf_7w__] - $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = $_obf_bhdW < 0;
			$x_value[$_obf_7w__] = $_obf_mqEmcU0_ ? $_obf_bhdW + 67108864 : $_obf_bhdW;
			++$_obf_7w__;
		}

		if ($_obf_mqEmcU0_) {
			for (; !$x_value[$_obf_7w__]; ++$_obf_7w__) {
				$x_value[$_obf_7w__] = 67108863;
			}

			--$x_value[$_obf_7w__];
		}

		return array(MATH_BIGINTEGER_VALUE => $this->_trim($x_value), MATH_BIGINTEGER_SIGN => $x_negative);
	}

	public function multiply($x)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_mul($this->value, $x->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = bcmul($this->value, $x->value, 0);
			return $this->_normalize($_obf_SeV31Q__);
		}

		$_obf_SeV31Q__ = $this->_multiply($this->value, $this->is_negative, $x->value, $x->is_negative);
		$_obf_hc0nzjWpow__ = new Class_Math_BigInteger();
		$_obf_hc0nzjWpow__->value = $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE];
		$_obf_hc0nzjWpow__->is_negative = $_obf_SeV31Q__[MATH_BIGINTEGER_SIGN];
		return $this->_normalize($_obf_hc0nzjWpow__);
	}

	public function _multiply($x_value, $x_negative, $y_value, $y_negative)
	{
		$_obf_B7wml3m8_TM_ = count($x_value);
		$_obf_At1nFV__ZcE_ = count($y_value);
		if (!$_obf_B7wml3m8_TM_ || !$_obf_At1nFV__ZcE_) {
			return array(
	MATH_BIGINTEGER_VALUE => array(),
	MATH_BIGINTEGER_SIGN  => false
	);
		}

		return array(MATH_BIGINTEGER_VALUE => min($_obf_B7wml3m8_TM_, $_obf_At1nFV__ZcE_) < (2 * MATH_BIGINTEGER_KARATSUBA_CUTOFF) ? $this->_trim($this->_regularMultiply($x_value, $y_value)) : $this->_trim($this->_karatsuba($x_value, $y_value)), MATH_BIGINTEGER_SIGN => $x_negative != $y_negative);
	}

	public function _regularMultiply($x_value, $y_value)
	{
		$_obf_B7wml3m8_TM_ = count($x_value);
		$_obf_At1nFV__ZcE_ = count($y_value);
		if (!$_obf_B7wml3m8_TM_ || !$_obf_At1nFV__ZcE_) {
			return array();
		}

		if ($_obf_B7wml3m8_TM_ < $_obf_At1nFV__ZcE_) {
			$_obf_SeV31Q__ = $x_value;
			$x_value = $y_value;
			$y_value = $_obf_SeV31Q__;
			$_obf_B7wml3m8_TM_ = count($x_value);
			$_obf_At1nFV__ZcE_ = count($y_value);
		}

		$_obf_y9msWRrjDyt_TBMzOQ__ = $this->_array_repeat(0, $_obf_B7wml3m8_TM_ + $_obf_At1nFV__ZcE_);
		$_obf_mqEmcU0_ = 0;
		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $_obf_B7wml3m8_TM_; ++$_obf_XA__) {
			$_obf_SeV31Q__ = ($x_value[$_obf_XA__] * $y_value[0]) + $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ / 67108864;
			$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_XA__] = (int) $_obf_SeV31Q__ - (67108864 * $_obf_mqEmcU0_);
		}

		$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_XA__] = $_obf_mqEmcU0_;
		$_obf_7w__ = 1;

		for (; $_obf_7w__ < $_obf_At1nFV__ZcE_; ++$_obf_7w__) {
			$_obf_mqEmcU0_ = 0;
			$_obf_XA__ = 0;
			$_obf_5w__ = $_obf_7w__;

			for (; $_obf_XA__ < $_obf_B7wml3m8_TM_; ++$_obf_XA__, ++$_obf_5w__) {
				$_obf_SeV31Q__ = $_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] + ($x_value[$_obf_XA__] * $y_value[$_obf_7w__]) + $_obf_mqEmcU0_;
				$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ / 67108864;
				$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] = (int) $_obf_SeV31Q__ - (67108864 * $_obf_mqEmcU0_);
			}

			$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] = $_obf_mqEmcU0_;
		}

		return $_obf_y9msWRrjDyt_TBMzOQ__;
	}

	public function _karatsuba($x_value, $y_value)
	{
		$_obf_Ag__ = min(count($x_value) >> 1, count($y_value) >> 1);

		if ($_obf_Ag__ < MATH_BIGINTEGER_KARATSUBA_CUTOFF) {
			return $this->_regularMultiply($x_value, $y_value);
		}

		$_obf_FY4_ = array_slice($x_value, $_obf_Ag__);
		$_obf_nT4_ = array_slice($x_value, 0, $_obf_Ag__);
		$_obf_UAc_ = array_slice($y_value, $_obf_Ag__);
		$_obf_iW4_ = array_slice($y_value, 0, $_obf_Ag__);
		$_obf_JoU_ = $this->_karatsuba($_obf_FY4_, $_obf_UAc_);
		$_obf_6dM_ = $this->_karatsuba($_obf_nT4_, $_obf_iW4_);
		$AF = $this->_add($_obf_FY4_, false, $_obf_nT4_, false);
		$_obf_SeV31Q__ = $this->_add($_obf_UAc_, false, $_obf_iW4_, false);
		$AF = $this->_karatsuba($AF[MATH_BIGINTEGER_VALUE], $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE]);
		$_obf_SeV31Q__ = $this->_add($_obf_JoU_, false, $_obf_6dM_, false);
		$AF = $this->_subtract($AF, false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], false);
		$_obf_JoU_ = array_merge(array_fill(0, 2 * $_obf_Ag__, 0), $_obf_JoU_);
		$AF[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $_obf_Ag__, 0), $AF[MATH_BIGINTEGER_VALUE]);
		$_obf_Rj0_ = $this->_add($_obf_JoU_, false, $AF[MATH_BIGINTEGER_VALUE], $AF[MATH_BIGINTEGER_SIGN]);
		$_obf_Rj0_ = $this->_add($_obf_Rj0_[MATH_BIGINTEGER_VALUE], $_obf_Rj0_[MATH_BIGINTEGER_SIGN], $_obf_6dM_, false);
		return $_obf_Rj0_[MATH_BIGINTEGER_VALUE];
	}

	public function _square($x = false)
	{
		return count($x) < (2 * MATH_BIGINTEGER_KARATSUBA_CUTOFF) ? $this->_trim($this->_baseSquare($x)) : $this->_trim($this->_karatsubaSquare($x));
	}

	public function _baseSquare($value)
	{
		if (empty($value)) {
			return array();
		}

		$square_value = $this->_array_repeat(0, 2 * count($value));
		$i = 0;
		$max_index = count($value) - 1;

		for (; $i <= $max_index; ++$i) {
			$i2 = $i << 1;
			$temp = $square_value[$i2] + ($value[$i] * $value[$i]);
			$carry = (int) $temp / 67108864;
			$square_value[$i2] = (int) $temp - (67108864 * $carry);
			$j = $i + 1;
			$k = $i2 + 1;

			for (; $j <= $max_index; ++$j, ++$k) {
				$temp = $square_value[$k] + (2 * $value[$j] * $value[$i]) + $carry;
				$carry = (int) $temp / 67108864;
				$square_value[$k] = (int) $temp - (67108864 * $carry);
			}

			$square_value[$i + $max_index + 1] = $carry;
		}

		return $square_value;
	}

	public function _karatsubaSquare($value)
	{
		$_obf_Ag__ = count($value) >> 1;

		if ($_obf_Ag__ < MATH_BIGINTEGER_KARATSUBA_CUTOFF) {
			return $this->_baseSquare($value);
		}

		$_obf_FY4_ = array_slice($value, $_obf_Ag__);
		$_obf_nT4_ = array_slice($value, 0, $_obf_Ag__);
		$_obf_JoU_ = $this->_karatsubaSquare($_obf_FY4_);
		$_obf_6dM_ = $this->_karatsubaSquare($_obf_nT4_);
		$AF = $this->_add($_obf_FY4_, false, $_obf_nT4_, false);
		$AF = $this->_karatsubaSquare($AF[MATH_BIGINTEGER_VALUE]);
		$_obf_SeV31Q__ = $this->_add($_obf_JoU_, false, $_obf_6dM_, false);
		$AF = $this->_subtract($AF, false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], false);
		$_obf_JoU_ = array_merge(array_fill(0, 2 * $_obf_Ag__, 0), $_obf_JoU_);
		$AF[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $_obf_Ag__, 0), $AF[MATH_BIGINTEGER_VALUE]);
		$_obf_604_ = $this->_add($_obf_JoU_, false, $AF[MATH_BIGINTEGER_VALUE], $AF[MATH_BIGINTEGER_SIGN]);
		$_obf_604_ = $this->_add($_obf_604_[MATH_BIGINTEGER_VALUE], $_obf_604_[MATH_BIGINTEGER_SIGN], $_obf_6dM_, false);
		return $_obf_604_[MATH_BIGINTEGER_VALUE];
	}

	public function divide($y)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$quotient = new Class_Math_BigInteger();
			$remainder = new Class_Math_BigInteger();
			list($quotient->value, $remainder->value) = gmp_div_qr($this->value, $y->value);

			if (gmp_sign($remainder->value) < 0) {
				$remainder->value = gmp_add($remainder->value, gmp_abs($y->value));
			}

			return array($this->_normalize($quotient), $this->_normalize($remainder));
		case MATH_BIGINTEGER_MODE_BCMATH:
			$quotient = new Class_Math_BigInteger();
			$remainder = new Class_Math_BigInteger();
			$quotient->value = bcdiv($this->value, $y->value, 0);
			$remainder->value = bcmod($this->value, $y->value);

			if ($remainder->value[0] == '-') {
				$remainder->value = bcadd($remainder->value, $y->value[0] == '-' ? substr($y->value, 1) : $y->value, 0);
			}

			return array($this->_normalize($quotient), $this->_normalize($remainder));
		}

		if (count($y->value) == 1) {
			list($q, $r) = $this->_divide_digit($this->value, $y->value[0]);
			$quotient = new Class_Math_BigInteger();
			$remainder = new Class_Math_BigInteger();
			$quotient->value = $q;
			$remainder->value = array($r);
			$quotient->is_negative = $this->is_negative != $y->is_negative;
			return array($this->_normalize($quotient), $this->_normalize($remainder));
		}

		static $zero;

		if (!isset($zero)) {
			$zero = new Class_Math_BigInteger();
		}

		$x = $this->copy();
		$y = $y->copy();
		$x_sign = $x->is_negative;
		$y_sign = $y->is_negative;
		$x->is_negative = $y->is_negative = false;
		$diff = $x->compare($y);

		if (!$diff) {
			$temp = new Class_Math_BigInteger();
			$temp->value = array(1);
			$temp->is_negative = $x_sign != $y_sign;
			return array($this->_normalize($temp), $this->_normalize(new Class_Math_BigInteger()));
		}

		if ($diff < 0) {
			if ($x_sign) {
				$x = $y->subtract($x);
			}

			return array($this->_normalize(new Class_Math_BigInteger()), $this->_normalize($x));
		}

		$msb = $y->value[count($y->value) - 1];
		$shift = 0;

		for (; !($msb & 33554432); ++$shift) {
			$msb <<= 1;
		}

		$x->_lshift($shift);
		$y->_lshift($shift);
		$y_value = &$y->value;
		$x_max = count($x->value) - 1;
		$y_max = count($y->value) - 1;
		$quotient = new Class_Math_BigInteger();
		$quotient_value = &$quotient->value;
		$quotient_value = $this->_array_repeat(0, ($x_max - $y_max) + 1);
		static $temp;
		static $lhs;
		static $rhs;

		if (!isset($temp)) {
			$temp = new Class_Math_BigInteger();
			$lhs = new Class_Math_BigInteger();
			$rhs = new Class_Math_BigInteger();
		}

		$temp_value = &$temp->value;
		$rhs_value = &$rhs->value;
		$temp_value = array_merge($this->_array_repeat(0, $x_max - $y_max), $y_value);

		while (0 <= $x->compare($temp)) {
			++$quotient_value[$x_max - $y_max];
			$x = $x->subtract($temp);
			$x_max = count($x->value) - 1;
		}

		$i = $x_max;

		for (; ($y_max + 1) <= $i; --$i) {
			$x_value = &$x->value;
			$x_window = array(isset($x_value[$i]) ? $x_value[$i] : 0, isset($x_value[$i - 1]) ? $x_value[$i - 1] : 0, isset($x_value[$i - 2]) ? $x_value[$i - 2] : 0);
			$y_window = array($y_value[$y_max], 0 < $y_max ? $y_value[$y_max - 1] : 0);
			$q_index = $i - $y_max - 1;

			if ($x_window[0] == $y_window[0]) {
				$quotient_value[$q_index] = 67108863;
			}
			else {
				$quotient_value[$q_index] = (int) (($x_window[0] * 67108864) + $x_window[1]) / $y_window[0];
			}

			$temp_value = array($y_window[1], $y_window[0]);
			$lhs->value = array($quotient_value[$q_index]);
			$lhs = $lhs->multiply($temp);
			$rhs_value = array($x_window[2], $x_window[1], $x_window[0]);

			while (0 < $lhs->compare($rhs)) {
				--$quotient_value[$q_index];
				$lhs->value = array($quotient_value[$q_index]);
				$lhs = $lhs->multiply($temp);
			}

			$adjust = $this->_array_repeat(0, $q_index);
			$temp_value = array($quotient_value[$q_index]);
			$temp = $temp->multiply($y);
			$temp_value = &$temp->value;
			$temp_value = array_merge($adjust, $temp_value);
			$x = $x->subtract($temp);

			if ($x->compare($zero) < 0) {
				$temp_value = array_merge($adjust, $y_value);
				$x = $x->add($temp);
				--$quotient_value[$q_index];
			}

			$x_max = count($x_value) - 1;
		}

		$x->_rshift($shift);
		$quotient->is_negative = $x_sign != $y_sign;

		if ($x_sign) {
			$y->_rshift($shift);
			$x = $y->subtract($x);
		}

		return array($this->_normalize($quotient), $this->_normalize($x));
	}

	public function _divide_digit($dividend, $divisor)
	{
		$_obf_mqEmcU0_ = 0;
		$_obf_xs33Yt_k = array();
		$_obf_7w__ = count($dividend) - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			$_obf_SeV31Q__ = (67108864 * $_obf_mqEmcU0_) + $dividend[$_obf_7w__];
			$_obf_xs33Yt_k[$_obf_7w__] = (int) $_obf_SeV31Q__ / $divisor;
			$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ - ($divisor * $_obf_xs33Yt_k[$_obf_7w__]);
		}

		return array($_obf_xs33Yt_k, $_obf_mqEmcU0_);
	}

	public function modPow($e, $n)
	{
		$n = (($this->bitmask !== false) && ($this->bitmask->compare($n) < 0) ? $this->bitmask : $n->abs());

		if ($e->compare(new Class_Math_BigInteger()) < 0) {
			$e = $e->abs();
			$_obf_SeV31Q__ = $this->modInverse($n);

			if ($_obf_SeV31Q__ === false) {
				return false;
			}

			return $this->_normalize($_obf_SeV31Q__->modPow($e, $n));
		}

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_powm($this->value, $e->value, $n->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = php_compat_bc_pow_mod($this->value, $e->value, $n->value, 0);
			return $this->_normalize($_obf_SeV31Q__);
		}

		if (empty($e->value)) {
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = array(1);
			return $this->_normalize($_obf_SeV31Q__);
		}

		if ($e->value == array(1)) {
			list(,$_obf_SeV31Q__) = $this->divide($n);
			return $this->_normalize($_obf_SeV31Q__);
		}

		if ($e->value == array(2)) {
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = $this->_square($this->value);
			list(,$_obf_SeV31Q__) = $_obf_SeV31Q__->divide($n);
			return $this->_normalize($_obf_SeV31Q__);
		}

		return $this->_normalize($this->_slidingWindow($e, $n, MATH_BIGINTEGER_BARRETT));

		if ($n->value[0] & 1) {
			return $this->_normalize($this->_slidingWindow($e, $n, MATH_BIGINTEGER_MONTGOMERY));
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($n->value); ++$_obf_7w__) {
			if ($n->value[$_obf_7w__]) {
				$_obf_SeV31Q__ = decbin($n->value[$_obf_7w__]);
				$_obf_XA__ = strlen($_obf_SeV31Q__) - strrpos($_obf_SeV31Q__, '1') - 1;
				$_obf_XA__ += 26 * $_obf_7w__;
				break;
			}
		}

		$_obf_fi1b_w__ = $n->copy();
		$_obf_fi1b_w__->_rshift($_obf_XA__);
		$_obf_c6VvXw__ = new Class_Math_BigInteger();
		$_obf_c6VvXw__->value = array(1);
		$_obf_c6VvXw__->_lshift($_obf_XA__);
		$_obf_j6n5OJg_ = ($_obf_fi1b_w__->value != array(1) ? $this->_slidingWindow($e, $_obf_fi1b_w__, MATH_BIGINTEGER_MONTGOMERY) : new Class_Math_BigInteger());
		$_obf_oAbSjq8_ = $this->_slidingWindow($e, $_obf_c6VvXw__, MATH_BIGINTEGER_POWEROF2);
		$_obf_UAc_ = $_obf_c6VvXw__->modInverse($_obf_fi1b_w__);
		$_obf_eBY_ = $_obf_fi1b_w__->modInverse($_obf_c6VvXw__);
		$_obf_xs33Yt_k = $_obf_j6n5OJg_->multiply($_obf_c6VvXw__);
		$_obf_xs33Yt_k = $_obf_xs33Yt_k->multiply($_obf_UAc_);
		$_obf_SeV31Q__ = $_obf_oAbSjq8_->multiply($_obf_fi1b_w__);
		$_obf_SeV31Q__ = $_obf_SeV31Q__->multiply($_obf_eBY_);
		$_obf_xs33Yt_k = $_obf_xs33Yt_k->add($_obf_SeV31Q__);
		list(,$_obf_xs33Yt_k) = $_obf_xs33Yt_k->divide($n);
		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function powMod($e, $n)
	{
		return $this->modPow($e, $n);
	}

	public function _slidingWindow($e, $n, $mode)
	{
		static $window_ranges = array(7, 25, 81, 241, 673, 1793);
		$_obf_kRItr58Zug__ = $e->value;
		$_obf_7ShGPngbCCw_ = count($_obf_kRItr58Zug__) - 1;
		$_obf_nhWsPW_S = decbin($_obf_kRItr58Zug__[$_obf_7ShGPngbCCw_]);
		$_obf_7w__ = $_obf_7ShGPngbCCw_ - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			$_obf_nhWsPW_S .= str_pad(decbin($_obf_kRItr58Zug__[$_obf_7w__]), 26, '0', STR_PAD_LEFT);
		}

		$_obf_7ShGPngbCCw_ = strlen($_obf_nhWsPW_S);
		$_obf_7w__ = 0;
		$_obf_hk1Oiiyh8RUNG_U_ = 1;

		for (; $_obf_7w__ < count($window_ranges); ++$_obf_hk1Oiiyh8RUNG_U_, ++$_obf_7w__) {
		}

		$_obf_bbbvpSwSlA__ = $n->value;
		$_obf_XqAZ_T2G = array();
		$_obf_XqAZ_T2G[1] = $this->_prepareReduce($this->value, $_obf_bbbvpSwSlA__, $mode);
		$_obf_XqAZ_T2G[2] = $this->_squareReduce($_obf_XqAZ_T2G[1], $_obf_bbbvpSwSlA__, $mode);
		$_obf_SeV31Q__ = 1 << ($_obf_hk1Oiiyh8RUNG_U_ - 1);
		$_obf_7w__ = 1;

		for (; $_obf_7w__ < $_obf_SeV31Q__; ++$_obf_7w__) {
			$_obf_lXQ_ = $_obf_7w__ << 1;
			$_obf_XqAZ_T2G[$_obf_lXQ_ + 1] = $this->_multiplyReduce($_obf_XqAZ_T2G[$_obf_lXQ_ - 1], $_obf_XqAZ_T2G[2], $_obf_bbbvpSwSlA__, $mode);
		}

		$_obf_xs33Yt_k = array(1);
		$_obf_xs33Yt_k = $this->_prepareReduce($_obf_xs33Yt_k, $_obf_bbbvpSwSlA__, $mode);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_7ShGPngbCCw_; ) {
			if (!$_obf_nhWsPW_S[$_obf_7w__]) {
				$_obf_xs33Yt_k = $this->_squareReduce($_obf_xs33Yt_k, $_obf_bbbvpSwSlA__, $mode);
				++$_obf_7w__;
			}
			else {
				$_obf_XA__ = $_obf_hk1Oiiyh8RUNG_U_ - 1;

				for (; 0 < $_obf_XA__; --$_obf_XA__) {
					if (!empty($_obf_nhWsPW_S[$_obf_7w__ + $_obf_XA__])) {
						break;
					}
				}

				$_obf_5w__ = 0;

				for (; $_obf_5w__ <= $_obf_XA__; ++$_obf_5w__) {
					$_obf_xs33Yt_k = $this->_squareReduce($_obf_xs33Yt_k, $_obf_bbbvpSwSlA__, $mode);
				}

				$_obf_xs33Yt_k = $this->_multiplyReduce($_obf_xs33Yt_k, $_obf_XqAZ_T2G[bindec(substr($_obf_nhWsPW_S, $_obf_7w__, $_obf_XA__ + 1))], $_obf_bbbvpSwSlA__, $mode);
				$_obf_7w__ += $_obf_XA__ + 1;
			}
		}

		$_obf_SeV31Q__ = new Class_Math_BigInteger();
		$_obf_SeV31Q__->value = $this->_reduce($_obf_xs33Yt_k, $_obf_bbbvpSwSlA__, $mode);
		return $_obf_SeV31Q__;
	}

	public function _reduce($x, $n, $mode)
	{
		switch ($mode) {
		case MATH_BIGINTEGER_MONTGOMERY:
			return $this->_montgomery($x, $n);
		case MATH_BIGINTEGER_BARRETT:
			return $this->_barrett($x, $n);
		case MATH_BIGINTEGER_POWEROF2:
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_iOMI->value = $x;
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_8Zfs->value = $n;
			return $x->_mod2($n);
		case MATH_BIGINTEGER_CLASSIC:
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_iOMI->value = $x;
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_8Zfs->value = $n;
			list(,$_obf_SeV31Q__) = $_obf_iOMI->divide($_obf_8Zfs);
			return $_obf_SeV31Q__->value;
		case MATH_BIGINTEGER_NONE:
			return $x;
		default:
		}
	}

	public function _prepareReduce($x, $n, $mode)
	{
		if ($mode == MATH_BIGINTEGER_MONTGOMERY) {
			return $this->_prepMontgomery($x, $n);
		}

		return $this->_reduce($x, $n, $mode);
	}

	public function _multiplyReduce($x, $y, $n, $mode)
	{
		if ($mode == MATH_BIGINTEGER_MONTGOMERY) {
			return $this->_montgomeryMultiply($x, $y, $n);
		}

		$_obf_SeV31Q__ = $this->_multiply($x, false, $y, false);
		return $this->_reduce($_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], $n, $mode);
	}

	public function _squareReduce($x, $n, $mode)
	{
		if ($mode == MATH_BIGINTEGER_MONTGOMERY) {
			return $this->_montgomeryMultiply($x, $x, $n);
		}

		return $this->_reduce($this->_square($x), $n, $mode);
	}

	public function _mod2($n)
	{
		$_obf_SeV31Q__ = new Class_Math_BigInteger();
		$_obf_SeV31Q__->value = array(1);
		return $this->bitwise_and($n->subtract($_obf_SeV31Q__));
	}

	public function _barrett($n, $m)
	{
		static $cache = array(
			MATH_BIGINTEGER_VARIABLE => array(),
			MATH_BIGINTEGER_DATA     => array()
			);
		$_obf_o5QgtfTtqzI_ = count($m);

		if ((2 * $_obf_o5QgtfTtqzI_) < count($n)) {
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_iOMI->value = $n;
			$_obf_8Zfs->value = $m;
			list(,$_obf_SeV31Q__) = $_obf_iOMI->divide($_obf_8Zfs);
			return $_obf_SeV31Q__->value;
		}

		if ($_obf_o5QgtfTtqzI_ < 5) {
			return $this->_regularBarrett($n, $m);
		}

		if (($_obf_Vwty = array_search($m, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
			$_obf_Vwty = count($cache[MATH_BIGINTEGER_VARIABLE]);
			$cache[MATH_BIGINTEGER_VARIABLE][] = $m;
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_hFnS9xE17pxM = &$_obf_iOMI->value;
			$_obf_hFnS9xE17pxM = $this->_array_repeat(0, $_obf_o5QgtfTtqzI_ + ($_obf_o5QgtfTtqzI_ >> 1));
			$_obf_hFnS9xE17pxM[] = 1;
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_8Zfs->value = $m;
			list($_obf_Dg__, $_obf_w0o_) = $_obf_iOMI->divide($_obf_8Zfs);
			$_obf_Dg__ = $_obf_Dg__->value;
			$_obf_w0o_ = $_obf_w0o_->value;
			$cache[MATH_BIGINTEGER_DATA][] = array('u' => $_obf_Dg__, 'm1' => $_obf_w0o_);
		}
		else {
			extract($cache[MATH_BIGINTEGER_DATA][$_obf_Vwty]);
		}

		$_obf_8ErXzw_S = $_obf_o5QgtfTtqzI_ + ($_obf_o5QgtfTtqzI_ >> 1);
		$_obf_fAYN = array_slice($n, 0, $_obf_8ErXzw_S);
		$_obf_3YFy = array_slice($n, $_obf_8ErXzw_S);
		$_obf_fAYN = $this->_trim($_obf_fAYN);
		$_obf_SeV31Q__ = $this->_multiply($_obf_3YFy, false, $_obf_w0o_, false);
		$n = $this->_add($_obf_fAYN, false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], false);

		if ($_obf_o5QgtfTtqzI_ & 1) {
			return $this->_regularBarrett($n[MATH_BIGINTEGER_VALUE], $m);
		}

		$_obf_SeV31Q__ = array_slice($n[MATH_BIGINTEGER_VALUE], $_obf_o5QgtfTtqzI_ - 1);
		$_obf_SeV31Q__ = $this->_multiply($_obf_SeV31Q__, false, $_obf_Dg__, false);
		$_obf_SeV31Q__ = array_slice($_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], ($_obf_o5QgtfTtqzI_ >> 1) + 1);
		$_obf_SeV31Q__ = $this->_multiply($_obf_SeV31Q__, false, $m, false);
		$_obf_xs33Yt_k = $this->_subtract($n[MATH_BIGINTEGER_VALUE], false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], false);

		while (0 <= $this->_compare($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], $_obf_xs33Yt_k[MATH_BIGINTEGER_SIGN], $m, false)) {
			$_obf_xs33Yt_k = $this->_subtract($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], $_obf_xs33Yt_k[MATH_BIGINTEGER_SIGN], $m, false);
		}

		return $_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE];
	}

	public function _regularBarrett($x, $n)
	{
		static $cache = array(
			MATH_BIGINTEGER_VARIABLE => array(),
			MATH_BIGINTEGER_DATA     => array()
			);
		$_obf_EhjjTqZIjxI_ = count($n);

		if ((2 * $_obf_EhjjTqZIjxI_) < count($x)) {
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_iOMI->value = $x;
			$_obf_8Zfs->value = $n;
			list(,$_obf_SeV31Q__) = $_obf_iOMI->divide($_obf_8Zfs);
			return $_obf_SeV31Q__->value;
		}

		if (($_obf_Vwty = array_search($n, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
			$_obf_Vwty = count($cache[MATH_BIGINTEGER_VARIABLE]);
			$cache[MATH_BIGINTEGER_VARIABLE][] = $n;
			$_obf_iOMI = new Class_Math_BigInteger();
			$_obf_hFnS9xE17pxM = &$_obf_iOMI->value;
			$_obf_hFnS9xE17pxM = $this->_array_repeat(0, 2 * $_obf_EhjjTqZIjxI_);
			$_obf_hFnS9xE17pxM[] = 1;
			$_obf_8Zfs = new Class_Math_BigInteger();
			$_obf_8Zfs->value = $n;
			list($_obf_SeV31Q__) = $_obf_iOMI->divide($_obf_8Zfs);
			$cache[MATH_BIGINTEGER_DATA][] = $_obf_SeV31Q__->value;
		}

		$_obf_SeV31Q__ = array_slice($x, $_obf_EhjjTqZIjxI_ - 1);
		$_obf_SeV31Q__ = $this->_multiply($_obf_SeV31Q__, false, $cache[MATH_BIGINTEGER_DATA][$_obf_Vwty], false);
		$_obf_SeV31Q__ = array_slice($_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], $_obf_EhjjTqZIjxI_ + 1);
		$_obf_xs33Yt_k = array_slice($x, 0, $_obf_EhjjTqZIjxI_ + 1);
		$_obf_SeV31Q__ = $this->_multiplyLower($_obf_SeV31Q__, false, $n, false, $_obf_EhjjTqZIjxI_ + 1);

		if ($this->_compare($_obf_xs33Yt_k, false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], $_obf_SeV31Q__[MATH_BIGINTEGER_SIGN]) < 0) {
			$_obf_WfHh5j7Ul_fQkksBrFlO = $this->_array_repeat(0, $_obf_EhjjTqZIjxI_ + 1);
			$_obf_WfHh5j7Ul_fQkksBrFlO[] = 1;
			$_obf_xs33Yt_k = $this->_add($_obf_xs33Yt_k, false, $_obf_jzBPJJ24IA05, false);
			$_obf_xs33Yt_k = $_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE];
		}

		$_obf_xs33Yt_k = $this->_subtract($_obf_xs33Yt_k, false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], $_obf_SeV31Q__[MATH_BIGINTEGER_SIGN]);

		while (0 < $this->_compare($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], $_obf_xs33Yt_k[MATH_BIGINTEGER_SIGN], $n, false)) {
			$_obf_xs33Yt_k = $this->_subtract($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], $_obf_xs33Yt_k[MATH_BIGINTEGER_SIGN], $n, false);
		}

		return $_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE];
	}

	public function _multiplyLower($x_value, $x_negative, $y_value, $y_negative, $stop)
	{
		$_obf_B7wml3m8_TM_ = count($x_value);
		$_obf_At1nFV__ZcE_ = count($y_value);
		if (!$_obf_B7wml3m8_TM_ || !$_obf_At1nFV__ZcE_) {
			return array(
	MATH_BIGINTEGER_VALUE => array(),
	MATH_BIGINTEGER_SIGN  => false
	);
		}

		if ($_obf_B7wml3m8_TM_ < $_obf_At1nFV__ZcE_) {
			$_obf_SeV31Q__ = $x_value;
			$x_value = $y_value;
			$y_value = $_obf_SeV31Q__;
			$_obf_B7wml3m8_TM_ = count($x_value);
			$_obf_At1nFV__ZcE_ = count($y_value);
		}

		$_obf_y9msWRrjDyt_TBMzOQ__ = $this->_array_repeat(0, $_obf_B7wml3m8_TM_ + $_obf_At1nFV__ZcE_);
		$_obf_mqEmcU0_ = 0;
		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $_obf_B7wml3m8_TM_; ++$_obf_XA__) {
			$_obf_SeV31Q__ = ($x_value[$_obf_XA__] * $y_value[0]) + $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ / 67108864;
			$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_XA__] = (int) $_obf_SeV31Q__ - (67108864 * $_obf_mqEmcU0_);
		}

		if ($_obf_XA__ < $stop) {
			$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_XA__] = $_obf_mqEmcU0_;
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ < $_obf_At1nFV__ZcE_; ++$_obf_7w__) {
			$_obf_mqEmcU0_ = 0;
			$_obf_XA__ = 0;
			$_obf_5w__ = $_obf_7w__;

			for (; $_obf_5w__ < $stop; ++$_obf_XA__, ++$_obf_5w__) {
				$_obf_SeV31Q__ = $_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] + ($x_value[$_obf_XA__] * $y_value[$_obf_7w__]) + $_obf_mqEmcU0_;
				$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ / 67108864;
				$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] = (int) $_obf_SeV31Q__ - (67108864 * $_obf_mqEmcU0_);
			}

			if ($_obf_5w__ < $stop) {
				$_obf_y9msWRrjDyt_TBMzOQ__[$_obf_5w__] = $_obf_mqEmcU0_;
			}
		}

		return array(MATH_BIGINTEGER_VALUE => $this->_trim($_obf_y9msWRrjDyt_TBMzOQ__), MATH_BIGINTEGER_SIGN => $x_negative != $y_negative);
	}

	public function _montgomery($x, $n)
	{
		static $cache = array(
			MATH_BIGINTEGER_VARIABLE => array(),
			MATH_BIGINTEGER_DATA     => array()
			);

		if (($_obf_Vwty = array_search($n, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
			$_obf_Vwty = count($cache[MATH_BIGINTEGER_VARIABLE]);
			$cache[MATH_BIGINTEGER_VARIABLE][] = $x;
			$cache[MATH_BIGINTEGER_DATA][] = $this->_modInverse67108864($n);
		}

		$_obf_5w__ = count($n);
		$_obf_xs33Yt_k = array(MATH_BIGINTEGER_VALUE => $x);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_5w__; ++$_obf_7w__) {
			$_obf_SeV31Q__ = $_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE][$_obf_7w__] * $cache[MATH_BIGINTEGER_DATA][$_obf_Vwty];
			$_obf_SeV31Q__ = (int) $_obf_SeV31Q__ - (67108864 * (int) $_obf_SeV31Q__ / 67108864);
			$_obf_SeV31Q__ = $this->_regularMultiply(array($_obf_SeV31Q__), $n);
			$_obf_SeV31Q__ = array_merge($this->_array_repeat(0, $_obf_7w__), $_obf_SeV31Q__);
			$_obf_xs33Yt_k = $this->_add($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], false, $_obf_SeV31Q__, false);
		}

		$_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE] = array_slice($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], $_obf_5w__);

		if (0 <= $this->_compare($_obf_xs33Yt_k, false, $n, false)) {
			$_obf_xs33Yt_k = $this->_subtract($_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE], false, $n, false);
		}

		return $_obf_xs33Yt_k[MATH_BIGINTEGER_VALUE];
	}

	public function _montgomeryMultiply($x, $y, $m)
	{
		$_obf_SeV31Q__ = $this->_multiply($x, false, $y, false);
		return $this->_montgomery($_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], $m);
		static $cache = array(
			MATH_BIGINTEGER_VARIABLE => array(),
			MATH_BIGINTEGER_DATA     => array()
			);

		if (($_obf_Vwty = array_search($m, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
			$_obf_Vwty = count($cache[MATH_BIGINTEGER_VARIABLE]);
			$cache[MATH_BIGINTEGER_VARIABLE][] = $m;
			$cache[MATH_BIGINTEGER_DATA][] = $this->_modInverse67108864($m);
		}

		$_obf_FQ__ = max(count($x), count($y), count($m));
		$x = array_pad($x, $_obf_FQ__, 0);
		$y = array_pad($y, $_obf_FQ__, 0);
		$m = array_pad($m, $_obf_FQ__, 0);
		$m = array(MATH_BIGINTEGER_VALUE => $this->_array_repeat(0, $_obf_FQ__ + 1));
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_FQ__; ++$_obf_7w__) {
			$_obf_SeV31Q__ = $m[MATH_BIGINTEGER_VALUE][0] + ($x[$_obf_7w__] * $y[0]);
			$_obf_SeV31Q__ = (int) $_obf_SeV31Q__ - (67108864 * (int) $_obf_SeV31Q__ / 67108864);
			$_obf_SeV31Q__ = $_obf_SeV31Q__ * $cache[MATH_BIGINTEGER_DATA][$_obf_Vwty];
			$_obf_SeV31Q__ = (int) $_obf_SeV31Q__ - (67108864 * (int) $_obf_SeV31Q__ / 67108864);
			$_obf_SeV31Q__ = $this->_add($this->_regularMultiply(array($x[$_obf_7w__]), $y), false, $this->_regularMultiply(array($_obf_SeV31Q__), $m), false);
			$m = $this->_add($m[MATH_BIGINTEGER_VALUE], false, $_obf_SeV31Q__[MATH_BIGINTEGER_VALUE], false);
			$m[MATH_BIGINTEGER_VALUE] = array_slice($m[MATH_BIGINTEGER_VALUE], 1);
		}

		if (0 <= $this->_compare($m[MATH_BIGINTEGER_VALUE], false, $m, false)) {
			$m = $this->_subtract($m[MATH_BIGINTEGER_VALUE], false, $m, false);
		}

		return $m[MATH_BIGINTEGER_VALUE];
	}

	public function _prepMontgomery($x, $n)
	{
		$_obf_iOMI = new Class_Math_BigInteger();
		$_obf_iOMI->value = array_merge($this->_array_repeat(0, count($n)), $x);
		$_obf_8Zfs = new Class_Math_BigInteger();
		$_obf_8Zfs->value = $n;
		list(,$_obf_SeV31Q__) = $_obf_iOMI->divide($_obf_8Zfs);
		return $_obf_SeV31Q__->value;
	}

	public function _modInverse67108864($x)
	{
		$x = 0 - $x[0];
		$_obf_xs33Yt_k = $x & 3;
		$_obf_xs33Yt_k = ($_obf_xs33Yt_k * (2 - ($x * $_obf_xs33Yt_k))) & 15;
		$_obf_xs33Yt_k = ($_obf_xs33Yt_k * (2 - (($x & 255) * $_obf_xs33Yt_k))) & 255;
		$_obf_xs33Yt_k = ($_obf_xs33Yt_k * ((2 - (($x & 65535) * $_obf_xs33Yt_k)) & 65535)) & 65535;
		$_obf_xs33Yt_k = fmod($_obf_xs33Yt_k * (2 - fmod($x * $_obf_xs33Yt_k, 67108864)), 67108864);
		return $_obf_xs33Yt_k & 67108863;
	}

	public function modInverse($n)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$temp = new Class_Math_BigInteger();
			$temp->value = gmp_invert($this->value, $n->value);
			return $temp->value === false ? false : $this->_normalize($temp);
		}

		static $zero;
		static $one;

		if (!isset($zero)) {
			$zero = new Class_Math_BigInteger();
			$one = new Class_Math_BigInteger(1);
		}

		$n = $n->abs();

		if ($this->compare($zero) < 0) {
			$temp = $this->abs();
			$temp = $temp->modInverse($n);
			return $negated === false ? false : $this->_normalize($n->subtract($temp));
		}

		extract($this->extendedGCD($n));

		if (!$gcd->equals($one)) {
			return false;
		}

		$x = ($x->compare($zero) < 0 ? $x->add($n) : $x);
		return $this->compare($zero) < 0 ? $this->_normalize($n->subtract($x)) : $this->_normalize($x);
	}

	public function extendedGCD($n)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			extract(gmp_gcdext($this->value, $n->value));
			return array('gcd' => $this->_normalize(new Class_Math_BigInteger($_obf_1Q__)), 'x' => $this->_normalize(new Class_Math_BigInteger($p)), 'y' => $this->_normalize(new Class_Math_BigInteger($_obf_lw__)));
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_Dg__ = $this->value;
			$_obf_6A__ = $n->value;
			$m = '1';
			$_obf_8A__ = '0';
			$_obf_KQ__ = '0';
			$_obf_5g__ = '1';

			while (bccomp($_obf_6A__, '0', 0) != 0) {
				$_obf_Bw__ = bcdiv($_obf_Dg__, $_obf_6A__, 0);
				$_obf_SeV31Q__ = $_obf_Dg__;
				$_obf_Dg__ = $_obf_6A__;
				$_obf_6A__ = bcsub($_obf_SeV31Q__, bcmul($_obf_6A__, $_obf_Bw__, 0), 0);
				$_obf_SeV31Q__ = $m;
				$m = $_obf_KQ__;
				$_obf_KQ__ = bcsub($_obf_SeV31Q__, bcmul($m, $_obf_Bw__, 0), 0);
				$_obf_SeV31Q__ = $_obf_8A__;
				$_obf_8A__ = $_obf_5g__;
				$_obf_5g__ = bcsub($_obf_SeV31Q__, bcmul($_obf_8A__, $_obf_Bw__, 0), 0);
			}

			return array('gcd' => $this->_normalize(new Class_Math_BigInteger($_obf_Dg__)), 'x' => $this->_normalize(new Class_Math_BigInteger($m)), 'y' => $this->_normalize(new Class_Math_BigInteger($_obf_8A__)));
		}

		$_obf_OA__ = $n->copy();
		$_obf_5Q__ = $this->copy();
		$_obf_1Q__ = new Class_Math_BigInteger();
		$_obf_1Q__->value = array(1);

		while (!(($_obf_5Q__->value[0] & 1) || ($_obf_OA__->value[0] & 1))) {
			$_obf_5Q__->_rshift(1);
			$_obf_OA__->_rshift(1);
			$_obf_1Q__->_lshift(1);
		}

		$_obf_Dg__ = $_obf_5Q__->copy();
		$_obf_6A__ = $_obf_OA__->copy();
		$m = new Class_Math_BigInteger();
		$_obf_8A__ = new Class_Math_BigInteger();
		$_obf_KQ__ = new Class_Math_BigInteger();
		$_obf_5g__ = new Class_Math_BigInteger();
		$m->value = $_obf_5g__->value = $_obf_1Q__->value = array(1);
		$_obf_8A__->value = $_obf_KQ__->value = array();

		while (!empty($_obf_Dg__->value)) {
			while (!($_obf_Dg__->value[0] & 1)) {
				$_obf_Dg__->_rshift(1);
				if ((!empty($m->value) && ($m->value[0] & 1)) || (!empty($_obf_8A__->value) && ($_obf_8A__->value[0] & 1))) {
					$m = $m->add($_obf_OA__);
					$_obf_8A__ = $_obf_8A__->subtract($_obf_5Q__);
				}

				$m->_rshift(1);
				$_obf_8A__->_rshift(1);
			}

			while (!($_obf_6A__->value[0] & 1)) {
				$_obf_6A__->_rshift(1);
				if ((!empty($_obf_5g__->value) && ($_obf_5g__->value[0] & 1)) || (!empty($_obf_KQ__->value) && ($_obf_KQ__->value[0] & 1))) {
					$_obf_KQ__ = $_obf_KQ__->add($_obf_OA__);
					$_obf_5g__ = $_obf_5g__->subtract($_obf_5Q__);
				}

				$_obf_KQ__->_rshift(1);
				$_obf_5g__->_rshift(1);
			}

			if (0 <= $_obf_Dg__->compare($_obf_6A__)) {
				$_obf_Dg__ = $_obf_Dg__->subtract($_obf_6A__);
				$m = $m->subtract($_obf_KQ__);
				$_obf_8A__ = $_obf_8A__->subtract($_obf_5g__);
			}
			else {
				$_obf_6A__ = $_obf_6A__->subtract($_obf_Dg__);
				$_obf_KQ__ = $_obf_KQ__->subtract($m);
				$_obf_5g__ = $_obf_5g__->subtract($_obf_8A__);
			}
		}

		return array('gcd' => $this->_normalize($_obf_1Q__->multiply($_obf_6A__)), 'x' => $this->_normalize($_obf_KQ__), 'y' => $this->_normalize($_obf_5g__));
	}

	public function gcd($n)
	{
		extract($this->extendedGCD($n));
		return $Ty9;
	}

	public function abs()
	{
		$_obf_SeV31Q__ = new Class_Math_BigInteger();

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__->value = gmp_abs($this->value);
			break;

		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_SeV31Q__->value = bccomp($this->value, '0', 0) < 0 ? substr($this->value, 1) : $this->value;
			break;

		default:
			$_obf_SeV31Q__->value = $this->value;
		}

		return $_obf_SeV31Q__;
	}

	public function compare($y)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			return gmp_cmp($this->value, $y->value);
		case MATH_BIGINTEGER_MODE_BCMATH:
			return bccomp($this->value, $y->value, 0);
		}

		return $this->_compare($this->value, $this->is_negative, $y->value, $y->is_negative);
	}

	public function _compare($x_value, $x_negative, $y_value, $y_negative)
	{
		if ($x_negative != $y_negative) {
			return !$x_negative && $y_negative ? 1 : -1;
		}

		$_obf_xs33Yt_k = ($x_negative ? -1 : 1);

		if (count($x_value) != count($y_value)) {
			return count($y_value) < count($x_value) ? $_obf_xs33Yt_k : 0 - $_obf_xs33Yt_k;
		}

		$_obf_hNQa0g__ = max(count($x_value), count($y_value));
		$x_value = array_pad($x_value, $_obf_hNQa0g__, 0);
		$y_value = array_pad($y_value, $_obf_hNQa0g__, 0);
		$_obf_7w__ = count($x_value) - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			if ($x_value[$_obf_7w__] != $y_value[$_obf_7w__]) {
				return $y_value[$_obf_7w__] < $x_value[$_obf_7w__] ? $_obf_xs33Yt_k : 0 - $_obf_xs33Yt_k;
			}
		}

		return 0;
	}

	public function equals($x)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			return gmp_cmp($this->value, $x->value) == 0;
		default:
			return ($this->value === $x->value) && ($this->is_negative == $x->is_negative);
		}
	}

	public function setPrecision($bits)
	{
		$this->precision = $bits;

		if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_BCMATH) {
			$this->bitmask = new Class_Math_BigInteger(chr((1 << ($bits & 7)) - 1) . str_repeat(chr(255), $bits >> 3), 256);
		}
		else {
			$this->bitmask = new Class_Math_BigInteger(bcpow('2', $bits, 0));
		}

		$_obf_SeV31Q__ = $this->_normalize($this);
		$this->value = $_obf_SeV31Q__->value;
	}

	public function bitwise_and($x)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_and($this->value, $x->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_7X3kig__ = $this->toBytes();
			$_obf_DiZOKAw_ = $x->toBytes();
			$_obf_Q8ERGxGW = max(strlen($_obf_7X3kig__), strlen($_obf_DiZOKAw_));
			$_obf_7X3kig__ = str_pad($_obf_7X3kig__, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			$_obf_DiZOKAw_ = str_pad($_obf_DiZOKAw_, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			return $this->_normalize(new Class_Math_BigInteger($_obf_7X3kig__ & $_obf_DiZOKAw_, 256));
		}

		$_obf_xs33Yt_k = $this->copy();
		$_obf_Q8ERGxGW = min(count($x->value), count($this->value));
		$_obf_xs33Yt_k->value = array_slice($_obf_xs33Yt_k->value, 0, $_obf_Q8ERGxGW);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Q8ERGxGW; ++$_obf_7w__) {
			$_obf_xs33Yt_k->value[$_obf_7w__] = $_obf_xs33Yt_k->value[$_obf_7w__] & $x->value[$_obf_7w__];
		}

		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function bitwise_or($x)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_or($this->value, $x->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_7X3kig__ = $this->toBytes();
			$_obf_DiZOKAw_ = $x->toBytes();
			$_obf_Q8ERGxGW = max(strlen($_obf_7X3kig__), strlen($_obf_DiZOKAw_));
			$_obf_7X3kig__ = str_pad($_obf_7X3kig__, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			$_obf_DiZOKAw_ = str_pad($_obf_DiZOKAw_, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			return $this->_normalize(new Class_Math_BigInteger($_obf_7X3kig__ | $_obf_DiZOKAw_, 256));
		}

		$_obf_Q8ERGxGW = max(count($this->value), count($x->value));
		$_obf_xs33Yt_k = $this->copy();
		$_obf_xs33Yt_k->value = array_pad($_obf_xs33Yt_k->value, 0, $_obf_Q8ERGxGW);
		$x->value = array_pad($x->value, 0, $_obf_Q8ERGxGW);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Q8ERGxGW; ++$_obf_7w__) {
			$_obf_xs33Yt_k->value[$_obf_7w__] = $this->value[$_obf_7w__] | $x->value[$_obf_7w__];
		}

		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function bitwise_xor($x)
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			$_obf_SeV31Q__ = new Class_Math_BigInteger();
			$_obf_SeV31Q__->value = gmp_xor($this->value, $x->value);
			return $this->_normalize($_obf_SeV31Q__);
		case MATH_BIGINTEGER_MODE_BCMATH:
			$_obf_7X3kig__ = $this->toBytes();
			$_obf_DiZOKAw_ = $x->toBytes();
			$_obf_Q8ERGxGW = max(strlen($_obf_7X3kig__), strlen($_obf_DiZOKAw_));
			$_obf_7X3kig__ = str_pad($_obf_7X3kig__, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			$_obf_DiZOKAw_ = str_pad($_obf_DiZOKAw_, $_obf_Q8ERGxGW, chr(0), STR_PAD_LEFT);
			return $this->_normalize(new Class_Math_BigInteger($_obf_7X3kig__ ^ $_obf_DiZOKAw_, 256));
		}

		$_obf_Q8ERGxGW = max(count($this->value), count($x->value));
		$_obf_xs33Yt_k = $this->copy();
		$_obf_xs33Yt_k->value = array_pad($_obf_xs33Yt_k->value, 0, $_obf_Q8ERGxGW);
		$x->value = array_pad($x->value, 0, $_obf_Q8ERGxGW);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Q8ERGxGW; ++$_obf_7w__) {
			$_obf_xs33Yt_k->value[$_obf_7w__] = $this->value[$_obf_7w__] ^ $x->value[$_obf_7w__];
		}

		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function bitwise_not()
	{
		$_obf_SeV31Q__ = $this->toBytes();
		$_obf_bCYBNPlyxw__ = decbin(ord($_obf_SeV31Q__[0]));
		$_obf_SeV31Q__ = ~$_obf_SeV31Q__;
		$_obf_qQt8 = decbin(ord($_obf_SeV31Q__[0]));

		if (strlen($_obf_qQt8) == 8) {
			$_obf_qQt8 = substr($_obf_qQt8, strpos($_obf_qQt8, '0'));
		}

		$_obf_SeV31Q__[0] = chr(bindec($_obf_qQt8));
		$_obf_vkPucTPhcoSfshUT = (strlen($_obf_bCYBNPlyxw__) + (8 * strlen($_obf_SeV31Q__))) - 8;
		$_obf_HJuu5HgTFVc_ = $this->precision - $_obf_vkPucTPhcoSfshUT;

		if ($_obf_HJuu5HgTFVc_ <= 0) {
			return $this->_normalize(new Class_Math_BigInteger($_obf_SeV31Q__, 256));
		}

		$_obf_yYyItYpEdVV_c8Ry = chr((1 << ($_obf_HJuu5HgTFVc_ & 7)) - 1) . str_repeat(chr(255), $_obf_HJuu5HgTFVc_ >> 3);
		$this->_base256_lshift($_obf_yYyItYpEdVV_c8Ry, $_obf_vkPucTPhcoSfshUT);
		$_obf_SeV31Q__ = str_pad($_obf_SeV31Q__, ceil($this->bits / 8), chr(0), STR_PAD_LEFT);
		return $this->_normalize(new Class_Math_BigInteger($_obf_yYyItYpEdVV_c8Ry | $_obf_SeV31Q__, 256));
	}

	public function bitwise_rightShift($shift)
	{
		$temp = new Class_Math_BigInteger();

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			static $two;

			if (!isset($two)) {
				$two = gmp_init('2');
			}

			$temp->value = gmp_div_q($this->value, gmp_pow($two, $shift));
			break;

		case MATH_BIGINTEGER_MODE_BCMATH:
			$temp->value = bcdiv($this->value, bcpow('2', $shift, 0), 0);
			break;

		default:
			$temp->value = $this->value;
			$temp->_rshift($shift);
		}

		return $this->_normalize($temp);
	}

	public function bitwise_leftShift($shift)
	{
		$temp = new Class_Math_BigInteger();

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			static $two;

			if (!isset($two)) {
				$two = gmp_init('2');
			}

			$temp->value = gmp_mul($this->value, gmp_pow($two, $shift));
			break;

		case MATH_BIGINTEGER_MODE_BCMATH:
			$temp->value = bcmul($this->value, bcpow('2', $shift, 0), 0);
			break;

		default:
			$temp->value = $this->value;
			$temp->_lshift($shift);
		}

		return $this->_normalize($temp);
	}

	public function bitwise_leftRotate($shift)
	{
		$_obf_rihqHw__ = $this->toBytes();

		if (0 < $this->precision) {
			$_obf_kgso7HsC__6B = $this->precision;

			if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
				$_obf_n69igA__ = $this->bitmask->subtract(new Class_Math_BigInteger(1));
				$_obf_n69igA__ = $_obf_n69igA__->toBytes();
			}
			else {
				$_obf_n69igA__ = $this->bitmask->toBytes();
			}
		}
		else {
			$_obf_SeV31Q__ = ord($_obf_rihqHw__[0]);
			$_obf_7w__ = 0;

			for (; $_obf_SeV31Q__ >> $_obf_7w__; ++$_obf_7w__) {
			}

			$_obf_kgso7HsC__6B = ((8 * strlen($_obf_rihqHw__)) - 8) + $_obf_7w__;
			$_obf_n69igA__ = chr((1 << ($_obf_kgso7HsC__6B & 7)) - 1) . str_repeat(chr(255), $_obf_kgso7HsC__6B >> 3);
		}

		if ($shift < 0) {
			$shift += $_obf_kgso7HsC__6B;
		}

		$shift %= $_obf_kgso7HsC__6B;

		if (!$shift) {
			return $this->copy();
		}

		$_obf_7X3kig__ = $this->bitwise_leftShift($shift);
		$_obf_7X3kig__ = $_obf_7X3kig__->bitwise_and(new Class_Math_BigInteger($_obf_n69igA__, 256));
		$_obf_DiZOKAw_ = $this->bitwise_rightShift($_obf_kgso7HsC__6B - $shift);
		$_obf_xs33Yt_k = (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_BCMATH ? $_obf_7X3kig__->bitwise_or($_obf_DiZOKAw_) : $_obf_7X3kig__->add($_obf_DiZOKAw_));
		return $this->_normalize($_obf_xs33Yt_k);
	}

	public function bitwise_rightRotate($shift)
	{
		return $this->bitwise_leftRotate(0 - $shift);
	}

	public function setRandomGenerator($generator)
	{
		$this->generator = $generator;
	}

	public function random($min = false, $max = false)
	{
		if ($min === false) {
			$min = new Class_Math_BigInteger(0);
		}

		if ($max === false) {
			$max = new Class_Math_BigInteger(2147483647);
		}

		$_obf_fOzCPiB3Sg__ = $max->compare($min);

		if (!$_obf_fOzCPiB3Sg__) {
			return $this->_normalize($min);
		}
		else if ($_obf_fOzCPiB3Sg__ < 0) {
			$_obf_SeV31Q__ = $max;
			$max = $min;
			$min = $_obf_SeV31Q__;
		}

		$_obf_vawNb54FXi3_ = $this->generator;
		$max = $max->subtract($min);
		$max = ltrim($max->toBytes(), chr(0));
		$_obf_hNQa0g__ = strlen($max) - 1;
		$_obf_D75chRUH = '';
		$_obf_KUMWfcg_ = $_obf_hNQa0g__ & 1;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_KUMWfcg_; ++$_obf_7w__) {
			$_obf_D75chRUH .= chr($_obf_vawNb54FXi3_(0, 255));
		}

		$_obf_hVj5KjiL = $_obf_hNQa0g__ >> 1;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_hVj5KjiL; ++$_obf_7w__) {
			$_obf_D75chRUH .= pack('n', $_obf_vawNb54FXi3_(0, 65535));
		}

		$_obf_SeV31Q__ = new Class_Math_BigInteger($_obf_D75chRUH, 256);

		if (0 < $_obf_SeV31Q__->compare(new Class_Math_BigInteger(substr($max, 1), 256))) {
			$_obf_D75chRUH = chr($_obf_vawNb54FXi3_(0, ord($max[0]) - 1)) . $_obf_D75chRUH;
		}
		else {
			$_obf_D75chRUH = chr($_obf_vawNb54FXi3_(0, ord($max[0]))) . $_obf_D75chRUH;
		}

		$_obf_D75chRUH = new Class_Math_BigInteger($_obf_D75chRUH, 256);
		return $this->_normalize($_obf_D75chRUH->add($min));
	}

	public function randomPrime($min = false, $max = false, $timeout = false)
	{
		$compare = $max->compare($min);

		if (!$compare) {
			return $min;
		}
		else if ($compare < 0) {
			$temp = $max;
			$max = $min;
			$min = $temp;
		}

		if ((MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_GMP) && function_exists('gmp_nextprime')) {
			if ($min === false) {
				$min = new Class_Math_BigInteger(0);
			}

			if ($max === false) {
				$max = new Class_Math_BigInteger(2147483647);
			}

			$x = $this->random($min, $max);
			$x->value = gmp_nextprime($x->value);

			if ($x->compare($max) <= 0) {
				return $x;
			}

			$x->value = gmp_nextprime($min->value);

			if ($x->compare($max) <= 0) {
				return $x;
			}

			return false;
		}

		static $one;
		static $two;

		if (!isset($one)) {
			$one = new Class_Math_BigInteger(1);
			$two = new Class_Math_BigInteger(2);
		}

		$start = time();
		$x = $this->random($min, $max);

		if ($x->equals($two)) {
			return $x;
		}

		$x->_make_odd();

		if (0 < $x->compare($max)) {
			if ($min->equals($max)) {
				return false;
			}

			$x = $min->copy();
			$x->_make_odd();
		}

		$initial_x = $x->copy();

		while (true) {
			if (($timeout !== false) && ($timeout < (time() - $start))) {
				return false;
			}

			if ($x->isPrime()) {
				return $x;
			}

			$x = $x->add($two);

			if (0 < $x->compare($max)) {
				$x = $min->copy();

				if ($x->equals($two)) {
					return $x;
				}

				$x->_make_odd();
			}

			if ($x->equals($initial_x)) {
				return false;
			}
		}
	}

	public function _make_odd()
	{
		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			gmp_setbit($this->value, 0);
			break;

		case MATH_BIGINTEGER_MODE_BCMATH:
			if (($this->value[strlen($this->value) - 1] % 2) == 0) {
				$this->value = bcadd($this->value, '1');
			}

			break;

		default:
			$this->value[0] |= 1;
		}
	}

	public function isPrime($t = false)
	{
		$length = strlen($this->toBytes());

		if (!$t) {
			if (163 <= $length) {
				$t = 2;
			}
			else if (106 <= $length) {
				$t = 3;
			}
			else if (81 <= $length) {
				$t = 4;
			}
			else if (68 <= $length) {
				$t = 5;
			}
			else if (56 <= $length) {
				$t = 6;
			}
			else if (50 <= $length) {
				$t = 7;
			}
			else if (43 <= $length) {
				$t = 8;
			}
			else if (37 <= $length) {
				$t = 9;
			}
			else if (31 <= $length) {
				$t = 12;
			}
			else if (25 <= $length) {
				$t = 15;
			}
			else if (18 <= $length) {
				$t = 18;
			}
			else {
				$t = 27;
			}
		}

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			return gmp_prob_prime($this->value, $t) != 0;
		case MATH_BIGINTEGER_MODE_BCMATH:
			if ($this->value === '2') {
				return true;
			}

			if (($this->value[strlen($this->value) - 1] % 2) == 0) {
				return false;
			}

			break;

		default:
			if ($this->value == array(2)) {
				return true;
			}

			if (~$this->value[0] & 1) {
				return false;
			}
		}

		static $primes;
		static $zero;
		static $one;
		static $two;

		if (!isset($primes)) {
			$primes = array(3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199, 211, 223, 227, 229, 233, 239, 241, 251, 257, 263, 269, 271, 277, 281, 283, 293, 307, 311, 313, 317, 331, 337, 347, 349, 353, 359, 367, 373, 379, 383, 389, 397, 401, 409, 419, 421, 431, 433, 439, 443, 449, 457, 461, 463, 467, 479, 487, 491, 499, 503, 509, 521, 523, 541, 547, 557, 563, 569, 571, 577, 587, 593, 599, 601, 607, 613, 617, 619, 631, 641, 643, 647, 653, 659, 661, 673, 677, 683, 691, 701, 709, 719, 727, 733, 739, 743, 751, 757, 761, 769, 773, 787, 797, 809, 811, 821, 823, 827, 829, 839, 853, 857, 859, 863, 877, 881, 883, 887, 907, 911, 919, 929, 937, 941, 947, 953, 967, 971, 977, 983, 991, 997);

			if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
				$i = 0;

				for (; $i < count($primes); ++$i) {
					$primes[$i] = new Class_Math_BigInteger($primes[$i]);
				}
			}

			$zero = new Class_Math_BigInteger();
			$one = new Class_Math_BigInteger(1);
			$two = new Class_Math_BigInteger(2);
		}

		if ($this->equals($one)) {
			return false;
		}

		if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
			foreach ($primes as $prime) {
				list(,$r) = $this->divide($prime);

				if ($r->equals($zero)) {
					return $this->equals($prime);
				}
			}
		}
		else {
			$value = $this->value;

			foreach ($primes as $prime) {
				list(,$r) = $this->_divide_digit($value, $prime);

				if (!$r) {
					return (count($value) == 1) && ($value[0] == $prime);
				}
			}
		}

		$n = $this->copy();
		$n_1 = $n->subtract($one);
		$n_2 = $n->subtract($two);
		$r = $n_1->copy();
		$r_value = $r->value;

		if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
			$s = 0;

			while (($r->value[strlen($r->value) - 1] % 2) == 0) {
				$r->value = bcdiv($r->value, '2', 0);
				++$s;
			}
		}
		else {
			$i = 0;
			$r_length = count($r_value);

			for (; $i < $r_length; ++$i) {
				$temp = ~$r_value[$i] & 16777215;
				$j = 1;

				for (; ($temp >> $j) & 1; ++$j) {
				}

				if ($j != 25) {
					break;
				}
			}

			$s = ((26 * $i) + $j) - 1;
			$r->_rshift($s);
		}

		$i = 0;

		for (; $i < $t; ++$i) {
			$a = $this->random($two, $n_2);
			$y = $a->modPow($r, $n);
			if (!$y->equals($one) && !$y->equals($n_1)) {
				$j = 1;

				for (; !$y->equals($n_1); ++$j) {
					$y = $y->modPow($two, $n);

					if ($y->equals($one)) {
						return false;
					}
				}

				if (!$y->equals($n_1)) {
					return false;
				}
			}
		}

		return true;
	}

	public function _lshift($shift)
	{
		if ($shift == 0) {
			return NULL;
		}

		$_obf_MSXnkS8YyxMbGg__ = (int) $shift / 26;
		$shift %= 26;
		$shift = 1 << $shift;
		$_obf_mqEmcU0_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($this->value); ++$_obf_7w__) {
			$_obf_SeV31Q__ = ($this->value[$_obf_7w__] * $shift) + $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = (int) $_obf_SeV31Q__ / 67108864;
			$this->value[$_obf_7w__] = (int) $_obf_SeV31Q__ - ($_obf_mqEmcU0_ * 67108864);
		}

		if ($_obf_mqEmcU0_) {
			$this->value[] = $_obf_mqEmcU0_;
		}

		while ($_obf_MSXnkS8YyxMbGg__--) {
			array_unshift($this->value, 0);
		}
	}

	public function _rshift($shift)
	{
		if ($shift == 0) {
			return NULL;
		}

		$_obf_MSXnkS8YyxMbGg__ = (int) $shift / 26;
		$shift %= 26;
		$_obf_dp65yayQeVvW5_g_ = 26 - $shift;
		$_obf_xYAu2McVjlS2tw__ = (1 << $shift) - 1;

		if ($_obf_MSXnkS8YyxMbGg__) {
			$this->value = array_slice($this->value, $_obf_MSXnkS8YyxMbGg__);
		}

		$_obf_mqEmcU0_ = 0;
		$_obf_7w__ = count($this->value) - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			$_obf_SeV31Q__ = ($this->value[$_obf_7w__] >> $shift) | $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = ($this->value[$_obf_7w__] & $_obf_xYAu2McVjlS2tw__) << $_obf_dp65yayQeVvW5_g_;
			$this->value[$_obf_7w__] = $_obf_SeV31Q__;
		}

		$this->value = $this->_trim($this->value);
	}

	public function _normalize($result)
	{
		$result->precision = $this->precision;
		$result->bitmask = $this->bitmask;

		switch (MATH_BIGINTEGER_MODE) {
		case MATH_BIGINTEGER_MODE_GMP:
			if (!empty($result->bitmask->value)) {
				$result->value = gmp_and($result->value, $result->bitmask->value);
			}

			return $result;
		case MATH_BIGINTEGER_MODE_BCMATH:
			if (!empty($result->bitmask->value)) {
				$result->value = bcmod($result->value, $result->bitmask->value);
			}

			return $result;
		}

		$_obf_VgKtFeg_ = &$result->value;

		if (!count($_obf_VgKtFeg_)) {
			return $result;
		}

		$_obf_VgKtFeg_ = $this->_trim($_obf_VgKtFeg_);

		if (!empty($result->bitmask->value)) {
			$_obf_Q8ERGxGW = min(count($_obf_VgKtFeg_), count($this->bitmask->value));
			$_obf_VgKtFeg_ = array_slice($_obf_VgKtFeg_, 0, $_obf_Q8ERGxGW);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_Q8ERGxGW; ++$_obf_7w__) {
				$_obf_VgKtFeg_[$_obf_7w__] = $_obf_VgKtFeg_[$_obf_7w__] & $this->bitmask->value[$_obf_7w__];
			}
		}

		return $result;
	}

	public function _trim($value)
	{
		$_obf_7w__ = count($value) - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			if ($value[$_obf_7w__]) {
				break;
			}

			unset($value[$_obf_7w__]);
		}

		return $value;
	}

	public function _array_repeat($input, $multiplier)
	{
		return $multiplier ? array_fill(0, $multiplier, $input) : array();
	}

	public function _base256_lshift(&$x, $shift)
	{
		if ($shift == 0) {
			return NULL;
		}

		$_obf_YfWNG6L101cY = $shift >> 3;
		$shift &= 7;
		$_obf_mqEmcU0_ = 0;
		$_obf_7w__ = strlen($x) - 1;

		for (; 0 <= $_obf_7w__; --$_obf_7w__) {
			$_obf_SeV31Q__ = (ord($x[$_obf_7w__]) << $shift) | $_obf_mqEmcU0_;
			$x[$_obf_7w__] = chr($_obf_SeV31Q__);
			$_obf_mqEmcU0_ = $_obf_SeV31Q__ >> 8;
		}

		$_obf_mqEmcU0_ = ($_obf_mqEmcU0_ != 0 ? chr($_obf_mqEmcU0_) : '');
		$x = $_obf_mqEmcU0_ . $x . str_repeat(chr(0), $_obf_YfWNG6L101cY);
	}

	public function _base256_rshift(&$x, $shift)
	{
		if ($shift == 0) {
			$x = ltrim($x, chr(0));
			return '';
		}

		$_obf_YfWNG6L101cY = $shift >> 3;
		$shift &= 7;
		$_obf__E7U4JjV95Yk = '';

		if ($_obf_YfWNG6L101cY) {
			$_obf_mV9HBLY_ = (strlen($x) < $_obf_YfWNG6L101cY ? 0 - strlen($x) : 0 - $_obf_YfWNG6L101cY);
			$_obf__E7U4JjV95Yk = substr($x, $_obf_mV9HBLY_);
			$x = substr($x, 0, 0 - $_obf_YfWNG6L101cY);
		}

		$_obf_mqEmcU0_ = 0;
		$_obf_dp65yayQeVvW5_g_ = 8 - $shift;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < strlen($x); ++$_obf_7w__) {
			$_obf_SeV31Q__ = (ord($x[$_obf_7w__]) >> $shift) | $_obf_mqEmcU0_;
			$_obf_mqEmcU0_ = (ord($x[$_obf_7w__]) << $_obf_dp65yayQeVvW5_g_) & 255;
			$x[$_obf_7w__] = chr($_obf_SeV31Q__);
		}

		$x = ltrim($x, chr(0));
		$_obf__E7U4JjV95Yk = chr($_obf_mqEmcU0_ >> $_obf_dp65yayQeVvW5_g_) . $_obf__E7U4JjV95Yk;
		return ltrim($_obf__E7U4JjV95Yk, chr(0));
	}

	public function _int2bytes($x)
	{
		return ltrim(pack('N', $x), chr(0));
	}

	public function _bytes2int($x)
	{
		$_obf_SeV31Q__ = unpack('Nint', str_pad($x, 4, chr(0), STR_PAD_LEFT));
		return $_obf_SeV31Q__['int'];
	}
}

define('MATH_BIGINTEGER_MONTGOMERY', 0);
define('MATH_BIGINTEGER_BARRETT', 1);
define('MATH_BIGINTEGER_POWEROF2', 2);
define('MATH_BIGINTEGER_CLASSIC', 3);
define('MATH_BIGINTEGER_NONE', 4);
define('MATH_BIGINTEGER_VALUE', 0);
define('MATH_BIGINTEGER_SIGN', 1);
define('MATH_BIGINTEGER_VARIABLE', 0);
define('MATH_BIGINTEGER_DATA', 1);
define('MATH_BIGINTEGER_MODE_INTERNAL', 1);
define('MATH_BIGINTEGER_MODE_BCMATH', 2);
define('MATH_BIGINTEGER_MODE_GMP', 3);
define('MATH_BIGINTEGER_MAX_DIGIT52', pow(2, 52));
define('MATH_BIGINTEGER_KARATSUBA_CUTOFF', 25);

?>
