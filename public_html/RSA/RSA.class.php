<?php
//dezend by http://www.yunlu99.com/
class MATH_RSA
{
	public $one;
	public $modulus;
	public $publicExponent = false;
	public $encryptionMode;

	public function MATH_RSA()
	{
		if (!defined('CRYPT_RSA_MODE')) {
			define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
		}

		$this->zero = new Class_Math_BigInteger();
		$this->one = new Class_Math_BigInteger(1);
		$this->hash = new Crypt_Hash('sha1');
		$this->hLen = $this->hash->getLength();
		$this->hashName = 'sha1';
		$this->mgfHash = new Crypt_Hash('sha1');
		$this->mgfHLen = $this->mgfHash->getLength();
	}

	public function _parseKey($key, $type)
	{
		switch ($type) {
		case CRYPT_RSA_PUBLIC_FORMAT_RAW:
			if (!is_array($key)) {
				return false;
			}

			$components = array();

			switch (true) {
			case isset($key['e']):
				$components['publicExponent'] = $key['e']->copy();
				break;

			case isset($key['exponent']):
				$components['publicExponent'] = $key['exponent']->copy();
				break;

			case isset($key['publicExponent']):
				$components['publicExponent'] = $key['publicExponent']->copy();
				break;

			case isset($key[0]):
				$components['publicExponent'] = $key[0]->copy();
			}

			switch (true) {
			case isset($key['n']):
				$components['modulus'] = $key['n']->copy();
				break;

			case isset($key['modulo']):
				$components['modulus'] = $key['modulo']->copy();
				break;

			case isset($key['modulus']):
				$components['modulus'] = $key['modulus']->copy();
				break;

			case isset($key[1]):
				$components['modulus'] = $key[1]->copy();
			}

			return $components;
		case CRYPT_RSA_PRIVATE_FORMAT_PKCS1:
		case CRYPT_RSA_PUBLIC_FORMAT_PKCS1:
			if (preg_match('#DEK-Info: (.+),(.+)#', $key, $matches)) {
				$iv = pack('H*', trim($matches[2]));
				$symkey = pack('H*', md5($this->password . $iv));
				$symkey .= substr(pack('H*', md5($symkey . $this->password . $iv)), 0, 8);
				$ciphertext = preg_replace('#.+(\\r|\\n|\\r\\n)\\1|[\\r\\n]|-.+-#s', '', $key);
				$ciphertext = (preg_match('#^[a-zA-Z\\d/+]*={0,2}$#', $ciphertext) ? base64_decode($ciphertext) : false);

				if ($ciphertext === false) {
					$ciphertext = $key;
				}

				switch ($matches[1]) {
				case 'DES-EDE3-CBC':
					if (!class_exists('Crypt_TripleDES')) {
						require_once ROOT_PATH . 'RSA/TripleDES.php';
					}

					$crypto = new Crypt_TripleDES();
					break;

				case 'DES-CBC':
					if (!class_exists('Crypt_DES')) {
						require_once ROOT_PATH . 'RSA/DES.php';
					}

					$crypto = new Crypt_DES();
					break;

				default:
					return false;
				}

				$crypto->setKey($symkey);
				$crypto->setIV($iv);
				$decoded = $crypto->decrypt($ciphertext);
			}
			else {
				$decoded = preg_replace('#-.+-|[\\r\\n]#', '', $key);
				$decoded = (preg_match('#^[a-zA-Z\\d/+]*={0,2}$#', $decoded) ? base64_decode($decoded) : false);
			}

			if ($decoded !== false) {
				$key = $decoded;
			}

			$components = array();

			if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
				return false;
			}

			if ($this->_decodeLength($key) != strlen($key)) {
				return false;
			}

			$tag = ord($this->_string_shift($key));

			if ($tag == CRYPT_RSA_ASN1_SEQUENCE) {
				$this->_string_shift($key, $this->_decodeLength($key));
				$this->_string_shift($key);
				$this->_decodeLength($key);
				$this->_string_shift($key);

				if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
					return false;
				}

				if ($this->_decodeLength($key) != strlen($key)) {
					return false;
				}

				$tag = ord($this->_string_shift($key));
			}

			if ($tag != CRYPT_RSA_ASN1_INTEGER) {
				return false;
			}

			$length = $this->_decodeLength($key);
			$temp = $this->_string_shift($key, $length);
			if ((strlen($temp) != 1) || (2 < ord($temp))) {
				$components['modulus'] = new Class_Math_BigInteger($temp, -256);
				$this->_string_shift($key);
				$length = $this->_decodeLength($key);
				$components[$type == CRYPT_RSA_PUBLIC_FORMAT_PKCS1 ? 'publicExponent' : 'privateExponent'] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
				return $components;
			}

			if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_INTEGER) {
				return false;
			}

			$length = $this->_decodeLength($key);
			$components['modulus'] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['publicExponent'] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['privateExponent'] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['primes'] = array(1 => new Class_Math_BigInteger($this->_string_shift($key, $length), -256));
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['primes'][] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['exponents'] = array(1 => new Class_Math_BigInteger($this->_string_shift($key, $length), -256));
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['exponents'][] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			$this->_string_shift($key);
			$length = $this->_decodeLength($key);
			$components['coefficients'] = array(2 => new Class_Math_BigInteger($this->_string_shift($key, $length), -256));

			if (!empty($key)) {
				if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
					return false;
				}

				$this->_decodeLength($key);

				while (!empty($key)) {
					if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
						return false;
					}

					$this->_decodeLength($key);
					$key = substr($key, 1);
					$length = $this->_decodeLength($key);
					$components['primes'][] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
					$this->_string_shift($key);
					$length = $this->_decodeLength($key);
					$components['exponents'][] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
					$this->_string_shift($key);
					$length = $this->_decodeLength($key);
					$components['coefficients'][] = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
				}
			}

			return $components;
		case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
			$key = base64_decode(preg_replace('#^ssh-rsa | .+$#', '', $key));

			if ($key === false) {
				return false;
			}

			$cleanup = substr($key, 0, 11) == '' . "\0" . '' . "\0" . '' . "\0" . 'ssh-rsa';
			extract(unpack('Nlength', $this->_string_shift($key, 4)));
			$publicExponent = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			extract(unpack('Nlength', $this->_string_shift($key, 4)));
			$modulus = new Class_Math_BigInteger($this->_string_shift($key, $length), -256);
			if ($cleanup && strlen($key)) {
				extract(unpack('Nlength', $this->_string_shift($key, 4)));
				return array('modulus' => new Class_Math_BigInteger($this->_string_shift($key, $length), -256), 'publicExponent' => $modulus);
			}
			else {
				return array('modulus' => $modulus, 'publicExponent' => $publicExponent);
			}
		}
	}

	public function loadKey($key, $type = CRYPT_RSA_PRIVATE_FORMAT_PKCS1)
	{
		$_obf_JEqxhCnNJ58zSQ__ = $this->_parseKey($key, $type);

		if ($_obf_JEqxhCnNJ58zSQ__ === false) {
			return false;
		}

		$this->modulus = $_obf_JEqxhCnNJ58zSQ__['modulus'];
		$this->k = strlen($this->modulus->toBytes());
		$this->exponent = isset($_obf_JEqxhCnNJ58zSQ__['privateExponent']) ? $_obf_JEqxhCnNJ58zSQ__['privateExponent'] : $_obf_JEqxhCnNJ58zSQ__['publicExponent'];

		if (isset($_obf_JEqxhCnNJ58zSQ__['primes'])) {
			$this->primes = $_obf_JEqxhCnNJ58zSQ__['primes'];
			$this->exponents = $_obf_JEqxhCnNJ58zSQ__['exponents'];
			$this->coefficients = $_obf_JEqxhCnNJ58zSQ__['coefficients'];
			$this->publicExponent = $_obf_JEqxhCnNJ58zSQ__['publicExponent'];
		}
		else {
			$this->primes = array();
			$this->exponents = array();
			$this->coefficients = array();
			$this->publicExponent = false;
		}

		return true;
	}

	public function _decodeLength(&$string)
	{
		$_obf_Q8ERGxGW = ord($this->_string_shift($string));

		if ($_obf_Q8ERGxGW & 128) {
			$_obf_Q8ERGxGW &= 127;
			$_obf_SeV31Q__ = $this->_string_shift($string, $_obf_Q8ERGxGW);
			list(,$_obf_Q8ERGxGW) = unpack('N', substr(str_pad($_obf_SeV31Q__, 4, chr(0), STR_PAD_LEFT), -4));
		}

		return $_obf_Q8ERGxGW;
	}

	public function _string_shift(&$string, $index = 1)
	{
		$_obf_dHVcvY16 = substr($string, 0, $index);
		$string = substr($string, $index);
		return $_obf_dHVcvY16;
	}

	public function _i2osp($x, $xLen)
	{
		$x = $x->toBytes();

		if ($xLen < strlen($x)) {
			user_error('Integer too large', 1024);
			return false;
		}

		return str_pad($x, $xLen, chr(0), STR_PAD_LEFT);
	}

	public function _os2ip($x)
	{
		return new Class_Math_BigInteger($x, 256);
	}

	public function _exponentiate($x)
	{
		if (empty($this->primes) || empty($this->coefficients) || empty($this->exponents)) {
			return $x->modPow($this->exponent, $this->modulus);
		}

		$_obf_O4ngNZYpMzU2pQ__ = count($this->primes);

		if (defined('CRYPT_RSA_DISABLE_BLINDING')) {
			$_obf_WXEe = array(1 => $x->modPow($this->exponents[1], $this->primes[1]), 2 => $x->modPow($this->exponents[2], $this->primes[2]));
			$M = $_obf_WXEe[1]->subtract($_obf_WXEe[2]);
			$M = $M->multiply($this->coefficients[2]);
			list(,$M) = $M->divide($this->primes[1]);
			$_obf_Ag__ = $_obf_WXEe[2]->add($M->multiply($this->primes[2]));
			$_obf_OQ__ = $this->primes[1];
			$_obf_7w__ = 3;

			for (; $_obf_7w__ <= $_obf_O4ngNZYpMzU2pQ__; $_obf_7w__++) {
				$_obf_WXEe = $x->modPow($this->exponents[$_obf_7w__], $this->primes[$_obf_7w__]);
				$_obf_OQ__ = $_obf_OQ__->multiply($this->primes[$_obf_7w__ - 1]);
				$M = $_obf_WXEe->subtract($_obf_Ag__);
				$M = $M->multiply($this->coefficients[$_obf_7w__]);
				list(,$M) = $M->divide($this->primes[$_obf_7w__]);
				$_obf_Ag__ = $_obf_Ag__->add($_obf_OQ__->multiply($M));
			}
		}
		else {
			$_obf_2sRKhtSOj4U_ = $this->primes[1];
			$_obf_7w__ = 2;

			for (; $_obf_7w__ <= $_obf_O4ngNZYpMzU2pQ__; $_obf_7w__++) {
				if (0 < $_obf_2sRKhtSOj4U_->compare($this->primes[$_obf_7w__])) {
					$_obf_2sRKhtSOj4U_ = $this->primes[$_obf_7w__];
				}
			}

			$_obf_lhI4 = new Class_Math_BigInteger(1);
			$_obf_lhI4->setRandomGenerator('crypt_random');
			$_obf_OQ__ = $_obf_lhI4->random($_obf_lhI4, $_obf_2sRKhtSOj4U_->subtract($_obf_lhI4));
			$_obf_WXEe = array(1 => $this->_blind($x, $_obf_OQ__, 1), 2 => $this->_blind($x, $_obf_OQ__, 2));
			$M = $_obf_WXEe[1]->subtract($_obf_WXEe[2]);
			$M = $M->multiply($this->coefficients[2]);
			list(,$M) = $M->divide($this->primes[1]);
			$_obf_Ag__ = $_obf_WXEe[2]->add($M->multiply($this->primes[2]));
			$_obf_OQ__ = $this->primes[1];
			$_obf_7w__ = 3;

			for (; $_obf_7w__ <= $_obf_O4ngNZYpMzU2pQ__; $_obf_7w__++) {
				$_obf_WXEe = $this->_blind($x, $_obf_OQ__, $_obf_7w__);
				$_obf_OQ__ = $_obf_OQ__->multiply($this->primes[$_obf_7w__ - 1]);
				$M = $_obf_WXEe->subtract($_obf_Ag__);
				$M = $M->multiply($this->coefficients[$_obf_7w__]);
				list(,$M) = $M->divide($this->primes[$_obf_7w__]);
				$_obf_Ag__ = $_obf_Ag__->add($_obf_OQ__->multiply($M));
			}
		}

		return $_obf_Ag__;
	}

	public function _blind($x, $r, $i)
	{
		$x = $x->multiply($r->modPow($this->publicExponent, $this->primes[$i]));
		$x = $x->modPow($this->exponents[$i], $this->primes[$i]);
		$r = $r->modInverse($this->primes[$i]);
		$x = $x->multiply($r);
		list(,$x) = $x->divide($this->primes[$i]);
		return $x;
	}

	public function _rsadp($c)
	{
		if (($c->compare($this->zero) < 0) || (0 < $c->compare($this->modulus))) {
			user_error('Ciphertext representative out of range', 1024);
			return false;
		}

		return $this->_exponentiate($c);
	}

	public function _mgf1($mgfSeed, $maskLen)
	{
		$_obf_lw__ = '';
		$_obf_gftfagw_ = ceil($maskLen / $this->mgfHLen);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_gftfagw_; $_obf_7w__++) {
			$_obf_KQ__ = pack('N', $_obf_7w__);
			$_obf_lw__ .= $this->mgfHash->hash($mgfSeed . $_obf_KQ__);
		}

		return substr($_obf_lw__, 0, $maskLen);
	}

	public function _rsaes_oaep_decrypt($c, $l = '')
	{
		if ((strlen($c) != $this->k) || ($this->k < ((2 * $this->hLen) + 2))) {
			user_error('Decryption error', 1024);
			return false;
		}

		$c = $this->_os2ip($c);
		$_obf_Ag__ = $this->_rsadp($c);

		if ($_obf_Ag__ === false) {
			user_error('Decryption error', 1024);
			return false;
		}

		$_obf_Io8_ = $this->_i2osp($_obf_Ag__, $this->k);
		$_obf_oR6Cz_Q_ = $this->hash->hash($l);
		$_obf_OA__ = ord($_obf_Io8_[0]);
		$_obf_SwFjAbsbauQcZw__ = substr($_obf_Io8_, 1, $this->hLen);
		$_obf_ffhEvhhB_Ko_ = substr($_obf_Io8_, $this->hLen + 1);
		$_obf_V5zfbnvr7IE_ = $this->_mgf1($_obf_ffhEvhhB_Ko_, $this->hLen);
		$_obf_jSlqVA__ = $_obf_SwFjAbsbauQcZw__ ^ $_obf_V5zfbnvr7IE_;
		$_obf_IoNm9Ipf = $this->_mgf1($_obf_jSlqVA__, $this->k - $this->hLen - 1);
		$_obf_sx8_ = $_obf_ffhEvhhB_Ko_ ^ $_obf_IoNm9Ipf;
		$_obf_ncQkyY2r = substr($_obf_sx8_, 0, $this->hLen);
		$_obf_Ag__ = substr($_obf_sx8_, $this->hLen);

		if ($_obf_oR6Cz_Q_ != $_obf_ncQkyY2r) {
			user_error('Decryption error', 1024);
			return false;
		}

		$_obf_Ag__ = ltrim($_obf_Ag__, chr(0));

		if (ord($_obf_Ag__[0]) != 1) {
			user_error('Decryption error', 1024);
			return false;
		}

		return substr($_obf_Ag__, 1);
	}

	public function _rsaes_pkcs1_v1_5_decrypt($c)
	{
		if (strlen($c) != $this->k) {
			user_error('Decryption error', 1024);
			return false;
		}

		$c = $this->_os2ip($c);
		$_obf_Ag__ = $this->_rsadp($c);

		if ($_obf_Ag__ === false) {
			user_error('Decryption error', 1024);
			return false;
		}

		$_obf_Io8_ = $this->_i2osp($_obf_Ag__, $this->k);
		if ((ord($_obf_Io8_[0]) != 0) || (2 < ord($_obf_Io8_[1]))) {
			user_error('Decryption error', 1024);
			return false;
		}

		$_obf__GE_ = substr($_obf_Io8_, 2, strpos($_obf_Io8_, chr(0), 2) - 2);
		$_obf_Ag__ = substr($_obf_Io8_, strlen($_obf__GE_) + 3);

		if (strlen($_obf__GE_) < 8) {
			user_error('Decryption error', 1024);
			return false;
		}

		return $_obf_Ag__;
	}

	public function decrypt($ciphertext)
	{
		if ($this->k <= 0) {
			return false;
		}

		$ciphertext = php_compat_str_split($ciphertext, $this->k);
		$_obf_gtA6dOjsFa8c = '';

		switch ($this->encryptionMode) {
		case CRYPT_RSA_ENCRYPTION_PKCS1:
			$_obf__kjvkYasqw__ = '_rsaes_pkcs1_v1_5_decrypt';
			break;

		default:
			$_obf__kjvkYasqw__ = '_rsaes_oaep_decrypt';
		}

		foreach ($ciphertext as $_obf_KQ__) {
			$_obf_SeV31Q__ = $this->$_obf__kjvkYasqw__($_obf_KQ__);

			if ($_obf_SeV31Q__ === false) {
				return false;
			}

			$_obf_gtA6dOjsFa8c .= $_obf_SeV31Q__;
		}

		return $_obf_gtA6dOjsFa8c;
	}
}

require_once ROOT_PATH . 'RSA/BcPowMod.php';
require_once ROOT_PATH . 'RSA/StringSplit.php';
require_once ROOT_PATH . 'RSA/BigInteger.php';
require_once ROOT_PATH . 'RSA/Hash.php';
define('CRYPT_RSA_ENCRYPTION_OAEP', 1);
define('CRYPT_RSA_ENCRYPTION_PKCS1', 2);
define('CRYPT_RSA_ASN1_INTEGER', 2);
define('CRYPT_RSA_ASN1_SEQUENCE', 48);
define('CRYPT_RSA_MODE_INTERNAL', 1);
define('CRYPT_RSA_PRIVATE_FORMAT_PKCS1', 0);
define('CRYPT_RSA_PUBLIC_FORMAT_RAW', 1);
define('CRYPT_RSA_PUBLIC_FORMAT_PKCS1', 2);
define('CRYPT_RSA_PUBLIC_FORMAT_OPENSSH', 3);

?>
