<?php
//dezend by http://www.yunlu99.com/
class Crypt_Hash
{
	public $b;
	public $l = false;
	public $hash;
	public $key = '';
	public $opad;
	public $ipad;

	public function Crypt_Hash($hash = 'sha1')
	{
		if (!defined('CRYPT_HASH_MODE')) {
			switch (true) {
			case extension_loaded('hash'):
				define('CRYPT_HASH_MODE', CRYPT_HASH_MODE_HASH);
				break;

			case extension_loaded('mhash'):
				define('CRYPT_HASH_MODE', CRYPT_HASH_MODE_MHASH);
				break;

			default:
				define('CRYPT_HASH_MODE', CRYPT_HASH_MODE_INTERNAL);
			}
		}

		$this->setHash($hash);
	}

	public function setKey($key)
	{
		$this->key = $key;
	}

	public function setHash($hash)
	{
		switch ($hash) {
		case 'md5-96':
		case 'sha1-96':
			$this->l = 12;
			break;

		case 'md2':
		case 'md5':
			$this->l = 16;
			break;

		case 'sha1':
			$this->l = 20;
			break;

		case 'sha256':
			$this->l = 32;
			break;

		case 'sha384':
			$this->l = 48;
			break;

		case 'sha512':
			$this->l = 64;
		}

		switch ($hash) {
		case 'md2':
			$_obf_eLlzdw__ = CRYPT_HASH_MODE_INTERNAL;
			break;

		case 'sha384':
		case 'sha512':
			$_obf_eLlzdw__ = (CRYPT_HASH_MODE == CRYPT_HASH_MODE_MHASH ? CRYPT_HASH_MODE_INTERNAL : CRYPT_HASH_MODE);
			break;

		default:
			$_obf_eLlzdw__ = CRYPT_HASH_MODE;
		}

		switch ($_obf_eLlzdw__) {
		case CRYPT_HASH_MODE_MHASH:
			switch ($hash) {
			case 'md5':
			case 'md5-96':
				$this->hash = MHASH_MD5;
				break;

			case 'sha256':
				$this->hash = MHASH_SHA256;
				break;

			case 'sha1':
			case 'sha1-96':
			default:
				$this->hash = MHASH_SHA1;
			}

			return NULL;
		case CRYPT_HASH_MODE_HASH:
			switch ($hash) {
			case 'md5':
			case 'md5-96':
				$this->hash = 'md5';
				return NULL;
			case 'sha256':
			case 'sha384':
			case 'sha512':
				$this->hash = $hash;
				return NULL;
			case 'sha1':
			case 'sha1-96':
			default:
				$this->hash = 'sha1';
			}

			return NULL;
		}

		switch ($hash) {
		case 'md2':
			$this->b = 16;
			$this->hash = array($this, '_md2');
			break;

		case 'md5':
		case 'md5-96':
			$this->b = 64;
			$this->hash = array($this, '_md5');
			break;

		case 'sha256':
			$this->b = 64;
			$this->hash = array($this, '_sha256');
			break;

		case 'sha384':
		case 'sha512':
			$this->b = 128;
			$this->hash = array($this, '_sha512');
			break;

		case 'sha1':
		case 'sha1-96':
		default:
			$this->b = 64;
			$this->hash = array($this, '_sha1');
		}

		$this->ipad = str_repeat(chr(54), $this->b);
		$this->opad = str_repeat(chr(92), $this->b);
	}

	public function hash($text)
	{
		$_obf_eLlzdw__ = (is_array($this->hash) ? CRYPT_HASH_MODE_INTERNAL : CRYPT_HASH_MODE);

		if (!empty($this->key)) {
			switch ($_obf_eLlzdw__) {
			case CRYPT_HASH_MODE_MHASH:
				$_obf_F5NWbxbn = mhash($this->hash, $text, $this->key);
				break;

			case CRYPT_HASH_MODE_HASH:
				$_obf_F5NWbxbn = hash_hmac($this->hash, $text, $this->key, true);
				break;

			case CRYPT_HASH_MODE_INTERNAL:
				$_obf_Vwty = ($this->b < strlen($this->key) ? call_user_func($this->$_obf_YGGPPw__, $this->key) : $this->key);
				$_obf_Vwty = str_pad($_obf_Vwty, $this->b, chr(0));
				$_obf_SeV31Q__ = $this->ipad ^ $_obf_Vwty;
				$_obf_SeV31Q__ .= $text;
				$_obf_SeV31Q__ = call_user_func($this->hash, $_obf_SeV31Q__);
				$_obf_F5NWbxbn = $this->opad ^ $_obf_Vwty;
				$_obf_F5NWbxbn .= $_obf_SeV31Q__;
				$_obf_F5NWbxbn = call_user_func($this->hash, $_obf_F5NWbxbn);
			}
		}
		else {
			switch ($_obf_eLlzdw__) {
			case CRYPT_HASH_MODE_MHASH:
				$_obf_F5NWbxbn = mhash($this->hash, $text);
				break;

			case CRYPT_HASH_MODE_HASH:
				$_obf_F5NWbxbn = hash($this->hash, $text, true);
				break;

			case CRYPT_HASH_MODE_INTERNAL:
				$_obf_F5NWbxbn = call_user_func($this->hash, $text);
			}
		}

		return substr($_obf_F5NWbxbn, 0, $this->l);
	}

	public function getLength()
	{
		return $this->l;
	}

	public function _md5($m)
	{
		return pack('H*', md5($m));
	}

	public function _sha1($m)
	{
		return pack('H*', sha1($m));
	}

	public function _md2($m)
	{
		static $s = array(41, 46, 67, 201, 162, 216, 124, 1, 61, 54, 84, 161, 236, 240, 6, 19, 98, 167, 5, 243, 192, 199, 115, 140, 152, 147, 43, 217, 188, 76, 130, 202, 30, 155, 87, 60, 253, 212, 224, 22, 103, 66, 111, 24, 138, 23, 229, 18, 190, 78, 196, 214, 218, 158, 222, 73, 160, 251, 245, 142, 187, 47, 238, 122, 169, 104, 121, 145, 21, 178, 7, 63, 148, 194, 16, 137, 11, 34, 95, 33, 128, 127, 93, 154, 90, 144, 50, 39, 53, 62, 204, 231, 191, 247, 151, 3, 255, 25, 48, 179, 72, 165, 181, 209, 215, 94, 146, 42, 172, 86, 170, 198, 79, 184, 56, 210, 150, 164, 125, 182, 118, 252, 107, 226, 156, 116, 4, 241, 69, 157, 112, 89, 100, 113, 135, 32, 134, 91, 207, 101, 230, 45, 168, 2, 27, 96, 37, 173, 174, 176, 185, 246, 28, 70, 97, 105, 52, 64, 126, 15, 85, 71, 163, 35, 221, 81, 175, 58, 195, 92, 249, 206, 186, 197, 234, 38, 44, 83, 13, 110, 133, 40, 132, 9, 211, 223, 205, 244, 65, 129, 77, 82, 106, 220, 55, 200, 108, 193, 171, 250, 36, 225, 123, 8, 12, 189, 177, 74, 120, 136, 149, 139, 227, 99, 232, 109, 233, 203, 213, 254, 59, 0, 29, 57, 242, 239, 183, 14, 102, 88, 208, 228, 166, 119, 114, 248, 235, 117, 75, 10, 49, 68, 80, 180, 143, 237, 31, 26, 219, 153, 141, 51, 159, 17, 131, 20);
		$_obf_Patk = 16 - (strlen($m) & 15);
		$m .= str_repeat(chr($_obf_Patk), $_obf_Patk);
		$_obf_Q8ERGxGW = strlen($m);
		$_obf_KQ__ = str_repeat(chr(0), 16);
		$A = chr(0);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Q8ERGxGW; $_obf_7w__ += 16) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < 16; $_obf_XA__++) {
				$_obf_KQ__[$_obf_XA__] = chr($s[ord($m[$_obf_7w__ + $_obf_XA__] ^ $A)]);
				$A = $_obf_KQ__[$_obf_XA__];
			}
		}

		$m .= $_obf_KQ__;
		$_obf_Q8ERGxGW += 16;
		$_obf_5Q__ = str_repeat(chr(0), 48);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Q8ERGxGW; $_obf_7w__ += 16) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < 16; $_obf_XA__++) {
				$_obf_5Q__[$_obf_XA__ + 16] = $m[$_obf_7w__ + $_obf_XA__];
				$_obf_5Q__[$_obf_XA__ + 32] = $_obf_5Q__[$_obf_XA__ + 16] ^ $_obf_5Q__[$_obf_XA__];
			}

			$_obf_lw__ = chr(0);
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < 18; $_obf_XA__++) {
				$_obf_5w__ = 0;

				for (; $_obf_5w__ < 48; $_obf_5w__++) {
					$_obf_5Q__[$_obf_5w__] = $_obf_lw__ = $_obf_5Q__[$_obf_5w__] ^ chr($s[ord($_obf_lw__)]);
				}

				$_obf_lw__ = chr(ord($_obf_lw__) + $_obf_XA__);
			}
		}

		return substr($_obf_5Q__, 0, 16);
	}

	public function _sha256($m)
	{
		if (extension_loaded('suhosin')) {
			return pack('H*', sha256($m));
		}

		$_obf_YGGPPw__ = array(1779033703, 3144134277, 1013904242, 2773480762, 1359893119, 2600822924, 528734635, 1541459225);
		static $k = array(1116352408, 1899447441, 3049323471, 3921009573, 961987163, 1508970993, 2453635748, 2870763221, 3624381080, 310598401, 607225278, 1426881987, 1925078388, 2162078206, 2614888103, 3248222580, 3835390401, 4022224774, 264347078, 604807628, 770255983, 1249150122, 1555081692, 1996064986, 2554220882, 2821834349, 2952996808, 3210313671, 3336571891, 3584528711, 113926993, 338241895, 666307205, 773529912, 1294757372, 1396182291, 1695183700, 1986661051, 2177026350, 2456956037, 2730485921, 2820302411, 3259730800, 3345764771, 3516065817, 3600352804, 4094571909, 275423344, 430227734, 506948616, 659060556, 883997877, 958139571, 1322822218, 1537002063, 1747873779, 1955562222, 2024104815, 2227730452, 2361852424, 2428436474, 2756734187, 3204031479, 3329325298);
		$_obf_Q8ERGxGW = strlen($m);
		$m .= str_repeat(chr(0), 64 - (($_obf_Q8ERGxGW + 8) & 63));
		$m[$_obf_Q8ERGxGW] = chr(128);
		$m .= pack('N2', 0, $_obf_Q8ERGxGW << 3);
		$_obf_w47fUTp6 = php_compat_str_split($m, 64);

		foreach ($_obf_w47fUTp6 as $_obf_Oec6zMU_) {
			$_obf_hg__ = array();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < 16; $_obf_7w__++) {
				extract(unpack('Ntemp', $this->_string_shift($_obf_Oec6zMU_, 4)));
				$_obf_hg__[] = $_obf_SeV31Q__;
			}

			$_obf_7w__ = 16;

			for (; $_obf_7w__ < 64; $_obf_7w__++) {
				$_obf_eCw_ = $this->_rightRotate($_obf_hg__[$_obf_7w__ - 15], 7) ^ $this->_rightRotate($_obf_hg__[$_obf_7w__ - 15], 18) ^ $this->_rightShift($_obf_hg__[$_obf_7w__ - 15], 3);
				$_obf__u4_ = $this->_rightRotate($_obf_hg__[$_obf_7w__ - 2], 17) ^ $this->_rightRotate($_obf_hg__[$_obf_7w__ - 2], 19) ^ $this->_rightShift($_obf_hg__[$_obf_7w__ - 2], 10);
				$_obf_hg__[$_obf_7w__] = $this->_add($_obf_hg__[$_obf_7w__ - 16], $_obf_eCw_, $_obf_hg__[$_obf_7w__ - 7], $_obf__u4_);
			}

			list($m, $_obf_8A__, $_obf_KQ__, $_obf_5g__, $_obf_hA__, $_obf_6Q__, $_obf_1Q__, $M) = $_obf_YGGPPw__;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < 64; $_obf_7w__++) {
				$_obf_eCw_ = $this->_rightRotate($m, 2) ^ $this->_rightRotate($m, 13) ^ $this->_rightRotate($m, 22);
				$_obf_BPDZ = ($m & $_obf_8A__) ^ ($m & $_obf_KQ__) ^ ($_obf_8A__ & $_obf_KQ__);
				$_obf_e7A_ = $this->_add($_obf_eCw_, $_obf_BPDZ);
				$_obf__u4_ = $this->_rightRotate($_obf_hA__, 6) ^ $this->_rightRotate($_obf_hA__, 11) ^ $this->_rightRotate($_obf_hA__, 25);
				$_obf_u_c_ = ($_obf_hA__ & $_obf_6Q__) ^ ($this->_not($_obf_hA__) & $_obf_1Q__);
				$_obf_98A_ = $this->_add($M, $_obf__u4_, $_obf_u_c_, $k[$_obf_7w__], $_obf_hg__[$_obf_7w__]);
				$M = $_obf_1Q__;
				$_obf_1Q__ = $_obf_6Q__;
				$_obf_6Q__ = $_obf_hA__;
				$_obf_hA__ = $this->_add($_obf_5g__, $_obf_98A_);
				$_obf_5g__ = $_obf_KQ__;
				$_obf_KQ__ = $_obf_8A__;
				$_obf_8A__ = $m;
				$m = $this->_add($_obf_98A_, $_obf_e7A_);
			}

			$_obf_YGGPPw__ = array($this->_add($_obf_YGGPPw__[0], $m), $this->_add($_obf_YGGPPw__[1], $_obf_8A__), $this->_add($_obf_YGGPPw__[2], $_obf_KQ__), $this->_add($_obf_YGGPPw__[3], $_obf_5g__), $this->_add($_obf_YGGPPw__[4], $_obf_hA__), $this->_add($_obf_YGGPPw__[5], $_obf_6Q__), $this->_add($_obf_YGGPPw__[6], $_obf_1Q__), $this->_add($_obf_YGGPPw__[7], $M));
		}

		return pack('N8', $_obf_YGGPPw__[0], $_obf_YGGPPw__[1], $_obf_YGGPPw__[2], $_obf_YGGPPw__[3], $_obf_YGGPPw__[4], $_obf_YGGPPw__[5], $_obf_YGGPPw__[6], $_obf_YGGPPw__[7]);
	}

	public function _sha512($m)
	{
		require_once ROOT_PATH . 'RSA/BigInteger.php';
		static $init384;
		static $init512;
		static $k;

		if (!isset($k)) {
			$init384 = array('cbbb9d5dc1059ed8', '629a292a367cd507', '9159015a3070dd17', '152fecd8f70e5939', '67332667ffc00b31', '8eb44a8768581511', 'db0c2e0d64f98fa7', '47b5481dbefa4fa4');
			$init512 = array('6a09e667f3bcc908', 'bb67ae8584caa73b', '3c6ef372fe94f82b', 'a54ff53a5f1d36f1', '510e527fade682d1', '9b05688c2b3e6c1f', '1f83d9abfb41bd6b', '5be0cd19137e2179');
			$i = 0;

			for (; $i < 8; $i++) {
				$init384[$i] = new Class_Math_BigInteger($init384[$i], 16);
				$init384[$i]->setPrecision(64);
				$init512[$i] = new Class_Math_BigInteger($init512[$i], 16);
				$init512[$i]->setPrecision(64);
			}

			$k = array('428a2f98d728ae22', '7137449123ef65cd', 'b5c0fbcfec4d3b2f', 'e9b5dba58189dbbc', '3956c25bf348b538', '59f111f1b605d019', '923f82a4af194f9b', 'ab1c5ed5da6d8118', 'd807aa98a3030242', '12835b0145706fbe', '243185be4ee4b28c', '550c7dc3d5ffb4e2', '72be5d74f27b896f', '80deb1fe3b1696b1', '9bdc06a725c71235', 'c19bf174cf692694', 'e49b69c19ef14ad2', 'efbe4786384f25e3', '0fc19dc68b8cd5b5', '240ca1cc77ac9c65', '2de92c6f592b0275', '4a7484aa6ea6e483', '5cb0a9dcbd41fbd4', '76f988da831153b5', '983e5152ee66dfab', 'a831c66d2db43210', 'b00327c898fb213f', 'bf597fc7beef0ee4', 'c6e00bf33da88fc2', 'd5a79147930aa725', '06ca6351e003826f', '142929670a0e6e70', '27b70a8546d22ffc', '2e1b21385c26c926', '4d2c6dfc5ac42aed', '53380d139d95b3df', '650a73548baf63de', '766a0abb3c77b2a8', '81c2c92e47edaee6', '92722c851482353b', 'a2bfe8a14cf10364', 'a81a664bbc423001', 'c24b8b70d0f89791', 'c76c51a30654be30', 'd192e819d6ef5218', 'd69906245565a910', 'f40e35855771202a', '106aa07032bbd1b8', '19a4c116b8d2d0c8', '1e376c085141ab53', '2748774cdf8eeb99', '34b0bcb5e19b48a8', '391c0cb3c5c95a63', '4ed8aa4ae3418acb', '5b9cca4f7763e373', '682e6ff3d6b2b8a3', '748f82ee5defb2fc', '78a5636f43172f60', '84c87814a1f0ab72', '8cc702081a6439ec', '90befffa23631e28', 'a4506cebde82bde9', 'bef9a3f7b2c67915', 'c67178f2e372532b', 'ca273eceea26619c', 'd186b8c721c0c207', 'eada7dd6cde0eb1e', 'f57d4f7fee6ed178', '06f067aa72176fba', '0a637dc5a2c898a6', '113f9804bef90dae', '1b710b35131c471b', '28db77f523047d84', '32caab7b40c72493', '3c9ebe0a15c9bebc', '431d67c49c100d4c', '4cc5d4becb3e42b6', '597f299cfc657e2a', '5fcb6fab3ad6faec', '6c44198c4a475817');
			$i = 0;

			for (; $i < 80; $i++) {
				$k[$i] = new Class_Math_BigInteger($k[$i], 16);
			}
		}

		$hash = ($this->l == 48 ? $init384 : $init512);
		$length = strlen($m);
		$m .= str_repeat(chr(0), 128 - (($length + 16) & 127));
		$m[$length] = chr(128);
		$m .= pack('N4', 0, 0, 0, $length << 3);
		$chunks = php_compat_str_split($m, 128);

		foreach ($chunks as $chunk) {
			$w = array();
			$i = 0;

			for (; $i < 16; $i++) {
				$temp = new Class_Math_BigInteger($this->_string_shift($chunk, 8), 256);
				$temp->setPrecision(64);
				$w[] = $temp;
			}

			$i = 16;

			for (; $i < 80; $i++) {
				$temp = array($w[$i - 15]->bitwise_rightRotate(1), $w[$i - 15]->bitwise_rightRotate(8), $w[$i - 15]->bitwise_rightShift(7));
				$s0 = $temp[0]->bitwise_xor($temp[1]);
				$s0 = $s0->bitwise_xor($temp[2]);
				$temp = array($w[$i - 2]->bitwise_rightRotate(19), $w[$i - 2]->bitwise_rightRotate(61), $w[$i - 2]->bitwise_rightShift(6));
				$s1 = $temp[0]->bitwise_xor($temp[1]);
				$s1 = $s1->bitwise_xor($temp[2]);
				$w[$i] = $w[$i - 16]->copy();
				$w[$i] = $w[$i]->add($s0);
				$w[$i] = $w[$i]->add($w[$i - 7]);
				$w[$i] = $w[$i]->add($s1);
			}

			$a = $hash[0]->copy();
			$b = $hash[1]->copy();
			$c = $hash[2]->copy();
			$d = $hash[3]->copy();
			$e = $hash[4]->copy();
			$f = $hash[5]->copy();
			$g = $hash[6]->copy();
			$h = $hash[7]->copy();
			$i = 0;

			for (; $i < 80; $i++) {
				$temp = array($a->bitwise_rightRotate(28), $a->bitwise_rightRotate(34), $a->bitwise_rightRotate(39));
				$s0 = $temp[0]->bitwise_xor($temp[1]);
				$s0 = $s0->bitwise_xor($temp[2]);
				$temp = array($a->bitwise_and($b), $a->bitwise_and($c), $b->bitwise_and($c));
				$maj = $temp[0]->bitwise_xor($temp[1]);
				$maj = $maj->bitwise_xor($temp[2]);
				$t2 = $s0->add($maj);
				$temp = array($e->bitwise_rightRotate(14), $e->bitwise_rightRotate(18), $e->bitwise_rightRotate(41));
				$s1 = $temp[0]->bitwise_xor($temp[1]);
				$s1 = $s1->bitwise_xor($temp[2]);
				$temp = array($e->bitwise_and($f), $g->bitwise_and($e->bitwise_not()));
				$ch = $temp[0]->bitwise_xor($temp[1]);
				$t1 = $h->add($s1);
				$t1 = $t1->add($ch);
				$t1 = $t1->add($k[$i]);
				$t1 = $t1->add($w[$i]);
				$h = $g->copy();
				$g = $f->copy();
				$f = $e->copy();
				$e = $d->add($t1);
				$d = $c->copy();
				$c = $b->copy();
				$b = $a->copy();
				$a = $t1->add($t2);
			}

			$hash = array($hash[0]->add($a), $hash[1]->add($b), $hash[2]->add($c), $hash[3]->add($d), $hash[4]->add($e), $hash[5]->add($f), $hash[6]->add($g), $hash[7]->add($h));
		}

		$temp = $hash[0]->toBytes() . $hash[1]->toBytes() . $hash[2]->toBytes() . $hash[3]->toBytes() . $hash[4]->toBytes() . $hash[5]->toBytes();

		if ($this->l != 48) {
			$temp .= $hash[6]->toBytes() . $hash[7]->toBytes();
		}

		return $temp;
	}

	public function _rightRotate($int, $amt)
	{
		$_obf_JQG6vp8i = 32 - $amt;
		$_obf_n69igA__ = (1 << $_obf_JQG6vp8i) - 1;
		return (($int << $_obf_JQG6vp8i) & 4294967295) | (($int >> $amt) & $_obf_n69igA__);
	}

	public function _rightShift($int, $amt)
	{
		$_obf_n69igA__ = (1 << (32 - $amt)) - 1;
		return ($int >> $amt) & $_obf_n69igA__;
	}

	public function _not($int)
	{
		return ~$int & 4294967295;
	}

	public function _add()
	{
		static $mod;

		if (!isset($mod)) {
			$mod = pow(2, 32);
		}

		$result = 0;
		$arguments = func_get_args();

		foreach ($arguments as $argument) {
			$result += ($argument < 0 ? ($argument & 2147483647) + 2147483648 : $argument);
		}

		return fmod($result, $mod);
	}

	public function _string_shift(&$string, $index = 1)
	{
		$_obf_dHVcvY16 = substr($string, 0, $index);
		$string = substr($string, $index);
		return $_obf_dHVcvY16;
	}
}

define('CRYPT_HASH_MODE_INTERNAL', 1);
define('CRYPT_HASH_MODE_MHASH', 2);
define('CRYPT_HASH_MODE_HASH', 3);

?>
