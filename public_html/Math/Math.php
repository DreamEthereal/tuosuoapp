<?php
//dezend by http://www.yunlu99.com/
class Math
{
	protected $values = array();
	protected $dataset = array();
	protected $precision = 3;

	public function __construct()
	{
	}

	public function __destruct()
	{
	}

	public function setPrecision($n)
	{
		$this->precision = (int) $n;
	}

	public function getPrecision()
	{
		return $this->precision;
	}

	public function mean($x, $type = 'arithmetic')
	{
		$type = strtolower($type);

		if ($type == 'arithmetic') {
			$_obf_j9s_Jes_ = 0;

			foreach ($x as $_obf_VgKtFeg_) {
				$_obf_j9s_Jes_ += $_obf_VgKtFeg_;
			}

			$_obf_1FjQ5g__ = $_obf_j9s_Jes_ / count($x);
		}
		else if ($type == 'geometric') {
			$_obf_j9s_Jes_ = 1;

			foreach ($x as $_obf_VgKtFeg_) {
				$_obf_j9s_Jes_ *= $_obf_VgKtFeg_;
			}

			$_obf_1FjQ5g__ = pow($_obf_j9s_Jes_, 1 / count($x));
		}
		else if ($type == 'harmonic') {
			$_obf_j9s_Jes_ = 0;

			foreach ($x as $_obf_VgKtFeg_) {
				$_obf_j9s_Jes_ += 1 / $_obf_VgKtFeg_;
			}

			$_obf_1FjQ5g__ = count($x) / $_obf_j9s_Jes_;
		}

		return $_obf_1FjQ5g__;
	}

	public function median($x)
	{
		sort($x);
		$_obf_gftfagw_ = count($x);

		if (($_obf_gftfagw_ % 2) == 0) {
			$_obf_hG84fVEh = ($x[($_obf_gftfagw_ / 2) - 1] + $x[$_obf_gftfagw_ / 2]) / 2;
		}
		else {
			$_obf_hG84fVEh = $x[floor($_obf_gftfagw_ / 2)];
		}

		return $_obf_hG84fVEh;
	}

	public function mode($x)
	{
		$_obf_6wTCVUUzwg__ = array();

		foreach ($x as $_obf_VgKtFeg_) {
			if (isset($_obf_6wTCVUUzwg__[$_obf_VgKtFeg_])) {
				$_obf_6wTCVUUzwg__[$_obf_VgKtFeg_]++;
			}
			else {
				$_obf_6wTCVUUzwg__[$_obf_VgKtFeg_] = 1;
			}
		}

		return array_keys($_obf_6wTCVUUzwg__, max($_obf_6wTCVUUzwg__));
	}

	public function variance($x)
	{
		$_obf_1FjQ5g__ = $this->mean($x);
		$_obf_xHUo = 0;

		foreach ($x as $_obf_VgKtFeg_) {
			$_obf_xHUo += ($_obf_VgKtFeg_ - $_obf_1FjQ5g__) * ($_obf_VgKtFeg_ - $_obf_1FjQ5g__);
		}

		$_obf_xHUo = $_obf_xHUo / (count($x) - 1);
		return $_obf_xHUo;
	}

	public function sd($x)
	{
		$_obf_EUI_ = sqrt($this->variance($x));
		return $_obf_EUI_;
	}

	public function skew($x)
	{
		$_obf_1FjQ5g__ = $this->mean($x);
		$_obf_EUI_ = $this->sd($x);
		$_obf_FQ__ = count($x);
		$_obf_SQ_gDA__ = 0;

		foreach ($x as $_obf_VgKtFeg_) {
			$_obf_SQ_gDA__ += pow(($_obf_VgKtFeg_ - $_obf_1FjQ5g__) / $_obf_EUI_, 3);
		}

		$_obf_SQ_gDA__ = ($_obf_SQ_gDA__ * $_obf_FQ__) / (($_obf_FQ__ - 1) * ($_obf_FQ__ - 2));
		return $_obf_SQ_gDA__;
	}

	public function isSkew($x)
	{
		$_obf_FQ__ = count($x);
		$_obf_SQ_gDA__ = $this->skew($x);
		$_obf_F6QBc61C = sqrt(6 / $_obf_FQ__);

		if ((2 * $_obf_F6QBc61C) < abs($_obf_SQ_gDA__)) {
			$_obf_xs33Yt_k = true;
		}
		else {
			$_obf_xs33Yt_k = false;
		}

		return $_obf_xs33Yt_k;
	}

	public function kurt($x)
	{
		$_obf_1FjQ5g__ = $this->mean($x);
		$_obf_EUI_ = $this->sd($x);
		$_obf_FQ__ = count($x);
		$_obf_Cw6rkQ__ = 0;

		foreach ($x as $_obf_VgKtFeg_) {
			$_obf_Cw6rkQ__ += pow(($_obf_VgKtFeg_ - $_obf_1FjQ5g__) / $_obf_EUI_, 4);
		}

		$_obf_Cw6rkQ__ = ($_obf_Cw6rkQ__ * $_obf_FQ__ * ($_obf_FQ__ + 1)) / (($_obf_FQ__ - 1) * ($_obf_FQ__ - 2) * ($_obf_FQ__ - 3));
		$_obf_Cw6rkQ__ = $_obf_Cw6rkQ__ - ((3 * ($_obf_FQ__ - 1) * ($_obf_FQ__ - 1)) / (($_obf_FQ__ - 2) * ($_obf_FQ__ - 3)));
		return $_obf_Cw6rkQ__;
	}

	public function isKurt($x)
	{
		$_obf_FQ__ = count($x);
		$_obf_Cw6rkQ__ = $this->kurt($x);
		$_obf__6EpNIce = sqrt(24 / $_obf_FQ__);

		if ((2 * $_obf__6EpNIce) < abs($_obf_Cw6rkQ__)) {
			$_obf_xs33Yt_k = true;
		}
		else {
			$_obf_xs33Yt_k = false;
		}

		return $_obf_xs33Yt_k;
	}

	public function cv($x)
	{
		$_obf_1FjQ5g__ = $this->mean($x);
		$_obf_EUI_ = $this->sd($x);
		$_obf_vUg_ = ($_obf_EUI_ / $_obf_1FjQ5g__) * 100;
		return $_obf_vUg_;
	}

	public function cov($x, $y)
	{
		$_obf_N5J2jxc_ = $this->mean($x);
		$_obf_wJWgKq4_ = $this->mean($y);
		$_obf_gftfagw_ = count($x);
		$_obf_j9s_Jes_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_gftfagw_; $_obf_7w__++) {
			$_obf_j9s_Jes_ += ($x[$_obf_7w__] - $_obf_N5J2jxc_) * ($y[$_obf_7w__] - $_obf_wJWgKq4_);
		}

		$_obf_aHTu = (1 / ($_obf_gftfagw_ - 1)) * $_obf_j9s_Jes_;
		return $_obf_aHTu;
	}

	public function cor($x, $y)
	{
		$_obf_aHTu = $this->cov($x, $y);
		$_obf_X1t0 = $this->sd($x);
		$_obf_8YrW = $this->sd($y);
		$_obf_rdIX = $_obf_aHTu / ($_obf_X1t0 * $_obf_8YrW);
		return $_obf_rdIX;
	}

	public function corTest($r, $n)
	{
		$_obf_lw__ = $r / sqrt((1 - ($r * $r)) / ($n - 2));
		$_obf_xs33Yt_k = $this->tDist($_obf_lw__, $n - 2);
		return $_obf_xs33Yt_k;
	}

	public function lm($y, $x1, $x2 = NULL, $origin = false)
	{
		if (is_null($x2)) {
			$_obf_ozw2zL7t3LY_ = false;
			$_obf_5w__ = 1;
		}
		else {
			$_obf_ozw2zL7t3LY_ = true;
			$_obf_5w__ = 2;
		}

		$_obf_FQ__ = count($y);
		$_obf_oLqQ = $this->mean($x1);
		$_obf_BR0_ = $this->mean($y);

		if (!$_obf_ozw2zL7t3LY_) {
			$_obf_OBqDv5cW9VCH = 0;
			$_obf_68pSSKoymrsRHyY_ = 0;
			$_obf_ZRuJ_A__ = 0;
			$_obf_eBY_ = 0;
			$_obf_CP5J = 0;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
				$_obf_OBqDv5cW9VCH += ($x1[$_obf_7w__] - $_obf_oLqQ) * ($y[$_obf_7w__] - $_obf_BR0_);
				$_obf_68pSSKoymrsRHyY_ += ($x1[$_obf_7w__] - $_obf_oLqQ) * ($x1[$_obf_7w__] - $_obf_oLqQ);
				$_obf_ZRuJ_A__ += $x1[$_obf_7w__] * $x1[$_obf_7w__];
				$_obf_eBY_ += $y[$_obf_7w__] * $y[$_obf_7w__];
				$_obf_CP5J += $x1[$_obf_7w__] * $y[$_obf_7w__];
			}

			if ($origin) {
				$_obf_8A__ = $_obf_CP5J / $_obf_ZRuJ_A__;
				$m = 0;
				$_obf_i5E_ = $_obf_FQ__ - 1;
			}
			else {
				$_obf_8A__ = $_obf_OBqDv5cW9VCH / $_obf_68pSSKoymrsRHyY_;
				$m = $_obf_BR0_ - ($_obf_8A__ * $_obf_oLqQ);
				$_obf_i5E_ = $_obf_FQ__ - 2;
			}

			$_obf_B97S5_Zy = 1;
		}
		else {
			$_obf_oP4L = $this->mean($x2);
			$_obf_Evl1rQ__ = array_sum($y);
			$_obf_INj42YY_ = array_sum($x1);
			$_obf_TUkpuo0_ = array_sum($x2);
			$_obf_ZRuJ_A__ = 0;
			$_obf_9qFWgg__ = 0;
			$_obf_DwJBww__ = 0;
			$_obf_CP5J = 0;
			$_obf_YYSk = 0;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
				$_obf_ZRuJ_A__ += $x1[$_obf_7w__] * $x1[$_obf_7w__];
				$_obf_9qFWgg__ += $x2[$_obf_7w__] * $x2[$_obf_7w__];
				$_obf_DwJBww__ += $x1[$_obf_7w__] * $x2[$_obf_7w__];
				$_obf_CP5J += $x1[$_obf_7w__] * $y[$_obf_7w__];
				$_obf_YYSk += $x2[$_obf_7w__] * $y[$_obf_7w__];
			}

			$_obf_xPikmPw_ = $_obf_ZRuJ_A__ - (($_obf_INj42YY_ * $_obf_INj42YY_) / $_obf_FQ__);
			$_obf_cmSKSQc_ = $_obf_9qFWgg__ - (($_obf_TUkpuo0_ * $_obf_TUkpuo0_) / $_obf_FQ__);
			$_obf_TH9kF7w_ = $_obf_DwJBww__ - (($_obf_INj42YY_ * $_obf_TUkpuo0_) / $_obf_FQ__);
			$_obf_mP5FxQ__ = $_obf_CP5J - (($_obf_Evl1rQ__ * $_obf_INj42YY_) / $_obf_FQ__);
			$_obf_h1Nbjw__ = $_obf_YYSk - (($_obf_Evl1rQ__ * $_obf_TUkpuo0_) / $_obf_FQ__);
			$_obf_MM4r = ($_obf_cmSKSQc_ * $_obf_mP5FxQ__) - ($_obf_TH9kF7w_ * $_obf_h1Nbjw__);
			$_obf_Yciv = ($_obf_xPikmPw_ * $_obf_cmSKSQc_) - ($_obf_TH9kF7w_ * $_obf_TH9kF7w_);
			$_obf_j4s_ = $_obf_MM4r / $_obf_Yciv;
			$_obf_EyBZ = ($_obf_xPikmPw_ * $_obf_h1Nbjw__) - ($_obf_TH9kF7w_ * $_obf_mP5FxQ__);
			$_obf__O_s = ($_obf_xPikmPw_ * $_obf_cmSKSQc_) - ($_obf_TH9kF7w_ * $_obf_TH9kF7w_);
			$_obf_mck_ = $_obf_EyBZ / $_obf__O_s;
			$m = $_obf_BR0_ - ($_obf_j4s_ * $_obf_oLqQ) - ($_obf_mck_ * $_obf_oP4L);
			$_obf_i5E_ = $_obf_FQ__ - 3;
			$_obf_B97S5_Zy = 2;
		}

		$_obf_RHe8q2bOi98_ = 0;
		$_obf_yguYJHxQJTarJm8cOA__ = 0;
		$_obf_xv88gjyy8YqE4FE_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
			if ($_obf_ozw2zL7t3LY_) {
				$_obf_eSHJ = $m + ($_obf_j4s_ * $x1[$_obf_7w__]) + ($_obf_mck_ * $x2[$_obf_7w__]);
			}
			else {
				$_obf_eSHJ = $m + ($_obf_8A__ * $x1[$_obf_7w__]);
			}

			$_obf_RHe8q2bOi98_ += pow($y[$_obf_7w__] - $_obf_BR0_, 2);
			$_obf_xv88gjyy8YqE4FE_ += ($y[$_obf_7w__] - $_obf_eSHJ) * ($y[$_obf_7w__] - $_obf_eSHJ);

			if ($origin) {
				$_obf_yguYJHxQJTarJm8cOA__ += $_obf_eSHJ * $_obf_eSHJ;
			}
			else {
				$_obf_yguYJHxQJTarJm8cOA__ += ($_obf_eSHJ - $_obf_BR0_) * ($_obf_eSHJ - $_obf_BR0_);
			}
		}

		if (!$_obf_ozw2zL7t3LY_) {
			if ($origin) {
				$_obf_oEs_ = $_obf_yguYJHxQJTarJm8cOA__ / $_obf_eBY_;
				$_obf_79DQ = 0;
				$_obf_1FVI = sqrt($_obf_xv88gjyy8YqE4FE_ / $_obf_i5E_) / sqrt($_obf_ZRuJ_A__);
			}
			else {
				$_obf_oEs_ = 1 - ($_obf_xv88gjyy8YqE4FE_ / $_obf_RHe8q2bOi98_);
				$_obf_79DQ = sqrt($_obf_xv88gjyy8YqE4FE_ / $_obf_i5E_) * sqrt($_obf_ZRuJ_A__ / ($_obf_FQ__ * $_obf_68pSSKoymrsRHyY_));
				$_obf_1FVI = sqrt($_obf_xv88gjyy8YqE4FE_ / $_obf_i5E_) / sqrt($_obf_68pSSKoymrsRHyY_);
			}
		}
		else {
			$_obf_oEs_ = 1 - ($_obf_xv88gjyy8YqE4FE_ / $_obf_RHe8q2bOi98_);
			$_obf_79DQ = 0;
			$_obf_f3Nr_g__ = 0;
			$_obf_2Aw3sg__ = 0;
		}

		$_obf_3Jm_ZzqbUc1dFAbeUA__ = $_obf_yguYJHxQJTarJm8cOA__ / $_obf_B97S5_Zy;
		$_obf_VYN5vuP5euANVq4_ = $_obf_xv88gjyy8YqE4FE_ / $_obf_i5E_;
		$_obf_PsLYpX4Ai4WWtkRf = $_obf_3Jm_ZzqbUc1dFAbeUA__ / $_obf_VYN5vuP5euANVq4_;
		$_obf_eH6XC_OVVVrq5eJp = $this->fDist($_obf_PsLYpX4Ai4WWtkRf, $_obf_B97S5_Zy, $_obf_i5E_);

		if (!$_obf_ozw2zL7t3LY_) {
			$_obf_xs33Yt_k = array('intercept' => $m, 'slope' => $_obf_8A__);
		}
		else {
			$_obf_xs33Yt_k = array('intercept' => $m, 'b1' => $_obf_j4s_, 'b2' => $_obf_mck_);
		}

		$_obf_VYN5vuP5euANVq4_ = $_obf_xv88gjyy8YqE4FE_ / ($_obf_FQ__ - $_obf_5w__ - 1);
		$_obf_7pHxgE22QY0_ = $_obf_RHe8q2bOi98_ / ($_obf_FQ__ - 1);
		$_obf_xs33Yt_k['r-square'] = $_obf_oEs_;
		$_obf_xs33Yt_k['adj-r-square'] = 1 - ($_obf_VYN5vuP5euANVq4_ / $_obf_7pHxgE22QY0_);
		$_obf_xs33Yt_k['intercept-se'] = $_obf_79DQ;
		$_obf_xs33Yt_k['intercept-2.5%'] = $m - ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_79DQ);
		$_obf_xs33Yt_k['intercept-97.5%'] = $m + ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_79DQ);

		if (!$_obf_ozw2zL7t3LY_) {
			$_obf_xs33Yt_k['slope-se'] = $_obf_1FVI;
			$_obf_xs33Yt_k['slope-2.5%'] = $_obf_8A__ - ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_1FVI);
			$_obf_xs33Yt_k['slope-97.5%'] = $_obf_8A__ + ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_1FVI);
		}
		else {
			$_obf_xs33Yt_k['b1-se'] = $_obf_f3Nr_g__;
			$_obf_xs33Yt_k['b1-2.5%'] = $_obf_j4s_ - ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_f3Nr_g__);
			$_obf_xs33Yt_k['b1-97.5%'] = $_obf_j4s_ + ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_f3Nr_g__);
			$_obf_xs33Yt_k['b2-se'] = $_obf_2Aw3sg__;
			$_obf_xs33Yt_k['b2-2.5%'] = $_obf_mck_ - ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_2Aw3sg__);
			$_obf_xs33Yt_k['b2-97.5%'] = $_obf_mck_ + ($this->inverseTCDF(0.05, $_obf_i5E_) * $_obf_2Aw3sg__);
		}

		$_obf_xs33Yt_k['F-statistic'] = $_obf_PsLYpX4Ai4WWtkRf;
		$_obf_xs33Yt_k['p-value'] = $_obf_eH6XC_OVVVrq5eJp;
		return $_obf_xs33Yt_k;
	}

	public function tTest($a, $b, $paired = false)
	{
		if ($paired == true) {
			$_obf_gftfagw_ = count($a);
			$_obf_SUSIHA__ = array();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_gftfagw_; $_obf_7w__++) {
				$_obf_SUSIHA__[$_obf_7w__] = $a[$_obf_7w__] - $b[$_obf_7w__];
			}

			$_obf_1FjQ5g__ = $this->mean($_obf_SUSIHA__);
			$_obf_xHUo = $this->variance($_obf_SUSIHA__);
			$_obf_lw__ = $_obf_1FjQ5g__ / sqrt($_obf_xHUo / $_obf_gftfagw_);
		}
		else {
			$_obf_I4s6NiQ_ = $this->mean($a);
			$_obf_fS2ni7k_ = $this->mean($b);
			$_obf_HJsg2A__ = $this->variance($a);
			$_obf_7mOjvQ__ = $this->variance($b);
			$_obf_iN3YYikY = count($a);
			$_obf_r28iBbqv = count($b);
			$_obf_lw__ = ($_obf_I4s6NiQ_ - $_obf_fS2ni7k_) / sqrt(($_obf_HJsg2A__ / $_obf_iN3YYikY) + ($_obf_7mOjvQ__ / $_obf_r28iBbqv));
		}

		return $_obf_lw__;
	}

	public function norm($x, $mean = 0, $sd = 1)
	{
		$_obf_OA__ = (1 / sqrt(2 * pi())) * exp(-0.5 * pow($x, 2));
		return $_obf_OA__;
	}

	private function _zip($q, $i, $j, $b)
	{
		$_obf_X5c_ = 1;
		$_obf_gQ__ = $_obf_X5c_;
		$_obf_5w__ = $i;

		while ($_obf_5w__ <= $j) {
			$_obf_X5c_ *= ($q * $_obf_5w__) / ($_obf_5w__ - $b);
			$_obf_gQ__ += $_obf_X5c_;
			$_obf_5w__ += 2;
		}

		return $_obf_gQ__;
	}

	public function tDist($t, $n, $tail = 1)
	{
		$_obf_DGVC = pi() / 2;
		$t = abs($t);
		$_obf_8FQ_ = $t / sqrt($n);
		$_obf_iXc_ = atan($_obf_8FQ_);

		if ($n == 1) {
			$_obf_xs33Yt_k = 1 - ($_obf_iXc_ / $_obf_DGVC);
		}
		else {
			$_obf_R_8_ = sin($_obf_iXc_);
			$_obf_aqA_ = cos($_obf_iXc_);

			if (($n % 2) == 1) {
				$_obf_xs33Yt_k = 1 - (($_obf_iXc_ + ($_obf_R_8_ * $_obf_aqA_ * $this->_zip($_obf_aqA_ * $_obf_aqA_, 2, $n - 3, -1))) / $_obf_DGVC);
			}
			else {
				$_obf_xs33Yt_k = 1 - ($_obf_R_8_ * $this->_zip($_obf_aqA_ * $_obf_aqA_, 1, $n - 3, -1));
			}
		}

		return $_obf_xs33Yt_k / $tail;
	}

	public function fDist($f, $df1, $df2)
	{
		$_obf_DGVC = pi() / 2;
		$_obf_5Q__ = $df2 / (($df1 * $f) + $df2);

		if (($df1 % 2) == 0) {
			return $this->_zip(1 - $_obf_5Q__, $df2, ($df1 + $df2) - 4, $df2 - 2) * pow($_obf_5Q__, $df2 / 2);
		}

		if (($df2 % 2) == 0) {
			return 1 - ($this->_zip($_obf_5Q__, $df1, ($df1 + $df2) - 4, $df1 - 2) * pow(1 - $_obf_5Q__, $df1 / 2));
		}

		$_obf_L0wq = atan(sqrt(($df1 * $f) / $df2));
		$m = $_obf_L0wq / $_obf_DGVC;
		$_obf_IIlR = sin($_obf_L0wq);
		$_obf_9E1L = cos($_obf_L0wq);

		if (1 < $df2) {
			$m = $m + (($_obf_IIlR * $_obf_9E1L * $this->_zip($_obf_9E1L * $_obf_9E1L, 2, $df2 - 3, -1)) / $_obf_DGVC);
		}

		if ($df1 == 1) {
			return 1 - $m;
		}

		$_obf_KQ__ = (4 * $this->_zip($_obf_IIlR * $_obf_IIlR, $df2 + 1, ($df1 + $df2) - 4, $df2 - 2) * $_obf_IIlR * pow($_obf_9E1L, $df2)) / pi();

		if ($df2 == 1) {
			return (1 - $m) + ($_obf_KQ__ / 2);
		}

		$_obf_5w__ = 2;

		while ($_obf_5w__ <= ($df2 - 1) / 2) {
			$_obf_KQ__ *= $_obf_5w__ / ($_obf_5w__ - 0.5);
			$_obf_5w__++;
		}

		return (1 - $m) + $_obf_KQ__;
	}

	public function normCDF($z)
	{
		$_obf_Qp82 = 6;

		if ($z == 0) {
			$_obf_5Q__ = 0;
		}
		else {
			$_obf_OA__ = abs($z) / 2;

			if (($_obf_Qp82 / 2) <= $_obf_OA__) {
				$_obf_5Q__ = 1;
			}
			else if ($_obf_OA__ < 1) {
				$_obf_hg__ = $_obf_OA__ * $_obf_OA__;
				$_obf_5Q__ = ((((((((((((((((0.000124818987 * $_obf_hg__) - 0.001075204047) * $_obf_hg__) + 0.005198775019) * $_obf_hg__) - 0.019198292004) * $_obf_hg__) + 0.059054035642) * $_obf_hg__) - 0.151968751364) * $_obf_hg__) + 0.319152932694) * $_obf_hg__) - 0.5319230073) * $_obf_hg__) + 0.797884560593) * $_obf_OA__ * 2;
			}
			else {
				$_obf_OA__ -= 2;
				$_obf_5Q__ = (((((((((((((((((((((((((((-4.5255659E-5 * $_obf_OA__) + 0.00015252929) * $_obf_OA__) - 1.9538132E-5) * $_obf_OA__) - 0.000676904986) * $_obf_OA__) + 0.001390604284) * $_obf_OA__) - 0.00079462082) * $_obf_OA__) - 0.002034254874) * $_obf_OA__) + 0.006549791214) * $_obf_OA__) - 0.010557625006) * $_obf_OA__) + 0.011630447319) * $_obf_OA__) - 0.009279453341) * $_obf_OA__) + 0.005353579108) * $_obf_OA__) - 0.002141268741) * $_obf_OA__) + 0.000535310849) * $_obf_OA__) + 0.999936657524;
			}
		}

		if (0 < $z) {
			$_obf_xs33Yt_k = ($_obf_5Q__ + 1) / 2;
		}
		else {
			$_obf_xs33Yt_k = (1 - $_obf_5Q__) / 2;
		}

		return $_obf_xs33Yt_k;
	}

	public function chiDist($x, $df)
	{
		define(LOG_SQRT_PI, log(sqrt(pi())));
		define(I_SQRT_PI, 1 / sqrt(pi()));
		if (($x <= 0) || ($df < 1)) {
			$_obf_xs33Yt_k = 1;
		}

		$m = $x / 2;

		if (($df % 2) == 0) {
			$_obf_qeU5MQ__ = true;
		}
		else {
			$_obf_qeU5MQ__ = false;
		}

		if (1 < $df) {
			$_obf_OA__ = exp(-1 * $m);
		}

		if ($_obf_qeU5MQ__) {
			$p = $_obf_OA__;
		}
		else {
			$p = 2 * $this->normCDF(-1 * sqrt($x));
		}

		if (2 < $df) {
			$x = ($df - 1) / 2;

			if ($_obf_qeU5MQ__) {
				$_obf_gQ__ = 1;
			}
			else {
				$_obf_gQ__ = 0.5;
			}

			if (20 < $m) {
				if ($_obf_qeU5MQ__) {
					$_obf_hA__ = 0;
				}
				else {
					$_obf_hA__ = LOG_SQRT_PI;
				}

				$_obf_KQ__ = log($m);

				while ($_obf_gQ__ <= $x) {
					$_obf_hA__ += log($_obf_gQ__);
					$p += exp(($_obf_KQ__ * $_obf_gQ__) - $m - $_obf_hA__);
					$_obf_gQ__ += 1;
				}

				$_obf_xs33Yt_k = $p;
			}
			else {
				if ($_obf_qeU5MQ__) {
					$_obf_hA__ = 1;
				}
				else {
					$_obf_hA__ = I_SQRT_PI / sqrt($m);
				}

				$_obf_KQ__ = 0;

				while ($_obf_gQ__ <= $x) {
					$_obf_hA__ *= $m / $_obf_gQ__;
					$_obf_KQ__ += $_obf_hA__;
					$_obf_gQ__ += 1;
				}

				$_obf_xs33Yt_k = ($_obf_KQ__ * $_obf_OA__) + $p;
			}
		}
		else {
			$_obf_xs33Yt_k = $p;
		}

		return $_obf_xs33Yt_k;
	}

	public function chiTest($table)
	{
		$_obf_j9s_Jes_ = 0;
		$_obf_fQjv = 0;

		foreach ($table as $_obf_p45BShOKcJY_ => $_obf_g_kt) {
			foreach ($_obf_g_kt as $_obf_Le6Uzznf => $_obf_7kIMZw__) {
				if (isset($_obf_rVsNRA__[$_obf_p45BShOKcJY_])) {
					$_obf_rVsNRA__[$_obf_p45BShOKcJY_] += $_obf_7kIMZw__;
				}
				else {
					$_obf_rVsNRA__[$_obf_p45BShOKcJY_] = $_obf_7kIMZw__;
				}

				if (isset($_obf_ZLtRwQ__[$_obf_Le6Uzznf])) {
					$_obf_ZLtRwQ__[$_obf_Le6Uzznf] += $_obf_7kIMZw__;
				}
				else {
					$_obf_ZLtRwQ__[$_obf_Le6Uzznf] = $_obf_7kIMZw__;
				}

				$_obf_j9s_Jes_ += $_obf_7kIMZw__;
			}
		}

		$_obf_OQ__ = count($_obf_rVsNRA__);
		$_obf_KQ__ = count($_obf_ZLtRwQ__);
		$_obf_i5E_ = ($_obf_OQ__ - 1) * ($_obf_KQ__ - 1);
		$_obf_qF6phG_S9Kg_ = array();

		foreach ($table as $_obf_p45BShOKcJY_ => $_obf_g_kt) {
			foreach ($_obf_g_kt as $_obf_Le6Uzznf => $_obf_7kIMZw__) {
				$_obf_iLc_ = $_obf_7kIMZw__;
				$_obf_S_Q_ = ($_obf_rVsNRA__[$_obf_p45BShOKcJY_] * $_obf_ZLtRwQ__[$_obf_Le6Uzznf]) / $_obf_j9s_Jes_;
				$_obf_fQjv += pow($_obf_iLc_ - $_obf_S_Q_, 2) / $_obf_S_Q_;
				$_obf_qF6phG_S9Kg_[$_obf_p45BShOKcJY_][$_obf_Le6Uzznf] = $_obf_S_Q_;
			}
		}

		return array('chi' => $_obf_fQjv, 'df' => $_obf_i5E_, 'expected' => $_obf_qF6phG_S9Kg_);
	}

	public function inverseNormCDF($p)
	{
		$_obf_67o_ = -39.696830286654;
		$_obf_CAg_ = 220.94609842452;
		$_obf__FM_ = -275.92851044697;
		$_obf_6Hs_ = 138.35775186727;
		$_obf_U_o_ = -30.664798066147;
		$_obf_lns_ = 2.5066282774592;
		$_obf_j4s_ = -54.476098798224;
		$_obf_mck_ = 161.58583685804;
		$_obf_GFw_ = -155.69897985989;
		$_obf_X8o_ = 66.80131188772;
		$_obf_QLY_ = -13.280681552886;
		$_obf_ysY_ = -0.0077848940024303;
		$_obf__YQ_ = -0.32239645804114;
		$_obf_aQ4_ = -2.4007582771618;
		$_obf_qEE_ = -2.5497325393437;
		$_obf_Rt8_ = 4.374664141465;
		$_obf_OQI_ = 2.9381639826988;
		$_obf__Xk_ = 0.0077846957090415;
		$_obf_1mk_ = 0.32246712907004;
		$_obf_gQI_ = 2.445134137143;
		$_obf_yiU_ = 3.7544086619074;
		$_obf_y_tIctQ_ = 0.02425;
		$_obf_1VADDFRY = 1 - $_obf_y_tIctQ_;
		if ((0 < $p) && ($p < $_obf_y_tIctQ_)) {
			$_obf_Bw__ = sqrt(-2 * log($p));
			$_obf_5Q__ = (((((((((($_obf_ysY_ * $_obf_Bw__) + $_obf__YQ_) * $_obf_Bw__) + $_obf_aQ4_) * $_obf_Bw__) + $_obf_qEE_) * $_obf_Bw__) + $_obf_Rt8_) * $_obf_Bw__) + $_obf_OQI_) / (((((((($_obf__Xk_ * $_obf_Bw__) + $_obf_1mk_) * $_obf_Bw__) + $_obf_gQI_) * $_obf_Bw__) + $_obf_yiU_) * $_obf_Bw__) + 1);
		}
		else {
			if (($_obf_y_tIctQ_ <= $p) && ($p <= $_obf_1VADDFRY)) {
				$_obf_Bw__ = $p - 0.5;
				$_obf_OQ__ = $_obf_Bw__ * $_obf_Bw__;
				$_obf_5Q__ = ((((((((((($_obf_67o_ * $_obf_OQ__) + $_obf_CAg_) * $_obf_OQ__) + $_obf__FM_) * $_obf_OQ__) + $_obf_6Hs_) * $_obf_OQ__) + $_obf_U_o_) * $_obf_OQ__) + $_obf_lns_) * $_obf_Bw__) / (((((((((($_obf_j4s_ * $_obf_OQ__) + $_obf_mck_) * $_obf_OQ__) + $_obf_GFw_) * $_obf_OQ__) + $_obf_X8o_) * $_obf_OQ__) + $_obf_QLY_) * $_obf_OQ__) + 1);
			}
			else {
				$_obf_Bw__ = sqrt(-2 * log(1 - $p));
				$_obf_5Q__ = (0 - (((((((((($_obf_ysY_ * $_obf_Bw__) + $_obf__YQ_) * $_obf_Bw__) + $_obf_aQ4_) * $_obf_Bw__) + $_obf_qEE_) * $_obf_Bw__) + $_obf_Rt8_) * $_obf_Bw__) + $_obf_OQI_)) / (((((((($_obf__Xk_ * $_obf_Bw__) + $_obf_1mk_) * $_obf_Bw__) + $_obf_gQI_) * $_obf_Bw__) + $_obf_yiU_) * $_obf_Bw__) + 1);
			}
		}

		return $_obf_5Q__;
	}

	public function inverseTCDF($p, $n)
	{
		if ($n == 1) {
			$p *= M_PI_2;
			$_obf_xs33Yt_k = cos($p) / sin($p);
		}
		else {
			$m = 1 / ($n - 0.5);
			$_obf_8A__ = 48 / ($m * $m);
			$_obf_KQ__ = ((((((20700 * $m) / $_obf_8A__) - 98) * $m) - 16) * $m) + 96.36;
			$_obf_5g__ = ((((94.5 / ($_obf_8A__ + $_obf_KQ__)) - 3) / $_obf_8A__) + 1) * sqrt($m * M_PI_2) * $n;
			$_obf_OA__ = pow(2 * $_obf_5g__ * $p, 2 / $n);

			if ((0.05 + $m) < $_obf_OA__) {
				$_obf_5Q__ = $this->inverseNormCDF($p * 0.5);
				$_obf_OA__ = $_obf_5Q__ * $_obf_5Q__;

				if ($n < 5) {
					$_obf_KQ__ += 0.3 * ($n - 4.5) * ($_obf_5Q__ + 0.6);
				}

				$_obf_KQ__ = (((((((0.05 * $_obf_5g__ * $_obf_5Q__) - 5) * $_obf_5Q__) - 7) * $_obf_5Q__) - 2) * $_obf_5Q__) + $_obf_8A__ + $_obf_KQ__;
				$_obf_OA__ = ((((((((((0.4 * $_obf_OA__) + 6.3) * $_obf_OA__) + 36) * $_obf_OA__) + 94.5) / $_obf_KQ__) - $_obf_OA__ - 3) / $_obf_8A__) + 1) * $_obf_5Q__;
				$_obf_OA__ *= $m * $_obf_OA__;

				if (0.002 < $_obf_OA__) {
					$_obf_OA__ = exp($_obf_OA__) - 1;
				}
				else {
					$_obf_OA__ += 0.5 * $_obf_OA__ * $_obf_OA__;
				}
			}
			else {
				$_obf_OA__ = ((((((1 / (((($n + 6) / ($n * $_obf_OA__)) - (0.089 * $_obf_5g__) - 0.822) * ($n + 2) * 3)) + (0.5 / ($n + 4))) * $_obf_OA__) - 1) * ($n + 1)) / ($n + 2)) + (1 / $_obf_OA__);
			}

			$_obf_xs33Yt_k = sqrt($n * $_obf_OA__);
		}

		return $_obf_xs33Yt_k;
	}

	public function diversity($abundances, $index = 'shannon', $base = M_E)
	{
		$index = strtolower($index);
		$_obf_ww__ = array_sum($abundances);

		foreach ($abundances as $_obf_Vwty => $_obf_VgKtFeg_) {
			$_obf_FA__[$_obf_Vwty] = $_obf_VgKtFeg_ / $_obf_ww__;
		}

		$_obf_xs33Yt_k = 0;

		if ($index == 'shannon') {
			foreach ($_obf_FA__ as $_obf_Vwty => $_obf_VgKtFeg_) {
				$_obf_xs33Yt_k += $_obf_VgKtFeg_ * log($_obf_VgKtFeg_, $base);
			}

			$_obf_xs33Yt_k = -1 * $_obf_xs33Yt_k;
		}
		else if ($index == 'simpson') {
			if ($_obf_ww__ < 20) {
				foreach ($abundances as $_obf_Vwty => $_obf_VgKtFeg_) {
					$_obf_xs33Yt_k += $_obf_VgKtFeg_ * ($_obf_VgKtFeg_ - 1);
				}

				$_obf_xs33Yt_k = $_obf_xs33Yt_k / ($_obf_ww__ * ($_obf_ww__ - 1));
			}
			else {
				foreach ($_obf_FA__ as $_obf_Vwty => $_obf_VgKtFeg_) {
					$_obf_xs33Yt_k += $_obf_VgKtFeg_ * $_obf_VgKtFeg_;
				}
			}
		}

		return $_obf_xs33Yt_k;
	}

	public function standardize($x, $var = true)
	{
		$_obf_1FjQ5g__ = $this->mean($x);
		$_obf_EUI_ = $this->sd($x);
		$_obf_tdQX = min($x);
		$_obf_Qp82 = max($x);

		foreach ($x as $_obf_Vwty => $_obf_VgKtFeg_) {
			if ($var) {
				$x[$_obf_Vwty] = ($_obf_VgKtFeg_ - $_obf_1FjQ5g__) / $_obf_EUI_;
			}
			else {
				$x[$_obf_Vwty] = $_obf_VgKtFeg_ - (0.5 * ($_obf_tdQX + $_obf_Qp82));
			}
		}

		return $x;
	}

	public function boxplot($x)
	{
		sort($x);
		$_obf_gftfagw_ = count($x);
		$_obf_hG84fVEh = $this->median($x);
		$_obf_EUI_ = $this->sd($x);
		$_obf_jgI_ = $x[round($_obf_gftfagw_ / 4)];
		$_obf_GLY_ = $x[round(($_obf_gftfagw_ * 3) / 4)];
		$_obf_tdQX = $x[0];
		$_obf_Qp82 = $x[$_obf_gftfagw_ - 1];
		$_obf_jsbWZTkGI4A_ = array();

		if ($_obf_tdQX < ($_obf_hG84fVEh - (2 * $_obf_EUI_))) {
			$_obf_tdQX = round($_obf_hG84fVEh - (2 * $_obf_EUI_), $this->precision);

			foreach ($x as $_obf_VgKtFeg_) {
				if ($_obf_VgKtFeg_ < $_obf_tdQX) {
					$_obf_jsbWZTkGI4A_[] = $_obf_VgKtFeg_;
				}
			}
		}

		if (($_obf_hG84fVEh + (2 * $_obf_EUI_)) < $_obf_Qp82) {
			$_obf_Qp82 = round($_obf_hG84fVEh + (2 * $_obf_EUI_), $this->precision);

			foreach ($x as $_obf_VgKtFeg_) {
				if ($_obf_Qp82 < $_obf_VgKtFeg_) {
					$_obf_jsbWZTkGI4A_[] = $_obf_VgKtFeg_;
				}
			}
		}

		return array('min' => $_obf_tdQX, 'q1' => $_obf_jgI_, 'median' => $_obf_hG84fVEh, 'q3' => $_obf_GLY_, 'max' => $_obf_Qp82, 'outliers' => $_obf_jsbWZTkGI4A_);
	}

	public function hist($x, $bins = false)
	{
		if ($bins === false) {
			$bins = ceil(sqrt(count($x)));
		}

		$_obf_Qp82 = max($x);
		$_obf_tdQX = min($x);
		$_obf_VdRv = ($_obf_Qp82 - $_obf_tdQX) / $bins;
		$_obf_X80ACw__ = array();
		$_obf___BsnD2O = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $bins; $_obf_7w__++) {
			$_obf_X80ACw__[$_obf_7w__] = 0;
			$_obf_P6Oa = round($_obf_tdQX + ($_obf_7w__ * $_obf_VdRv), $this->precision);
			$_obf_5f8MSg__ = round($_obf_tdQX + (($_obf_7w__ + 1) * $_obf_VdRv), $this->precision);
			$_obf___BsnD2O[$_obf_7w__] = $_obf_P6Oa . '-' . $_obf_5f8MSg__;
		}

		foreach ($x as $_obf_VgKtFeg_) {
			if ($_obf_VgKtFeg_ == $_obf_Qp82) {
				$_obf_X80ACw__[$bins - 1]++;
			}
			else {
				$_obf_X80ACw__[floor(($_obf_VgKtFeg_ - $_obf_tdQX) / $_obf_VdRv)]++;
			}
		}

		return array_combine($_obf___BsnD2O, $_obf_X80ACw__);
	}

	public function qqnorm($y)
	{
		asort($y);
		$_obf_Zx6j = array();
		$_obf_8c9i = array();
		$_obf_FQ__ = count($y);
		$_obf_7w__ = 1;

		foreach ($y as $_obf_5w__ => $_obf_6A__) {
			$_obf_gQ__ = $this->inverseNormCDF(($_obf_7w__ - 0.5) / $_obf_FQ__);
			$_obf_Zx6j[$_obf_5w__] = $_obf_gQ__;
			$_obf_8c9i[$_obf_5w__] = $_obf_6A__;
			$_obf_7w__++;
		}

		ksort($_obf_Zx6j);
		ksort($_obf_8c9i);
		$_obf_eOo_ = array();
		$_obf_eOo_['x'] = array_values($_obf_Zx6j);
		$_obf_eOo_['y'] = array_values($_obf_8c9i);
		return $_obf_eOo_;
	}

	public function ternary($x, $y, $z)
	{
		$_obf_FQ__ = count($x);
		$_obf_bsI3 = array();
		$_obf_Jdai = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
			$_obf_bsI3[] = round((0.5 * ((2 * $y[$_obf_7w__]) + $z[$_obf_7w__])) / ($x[$_obf_7w__] + $y[$_obf_7w__] + $z[$_obf_7w__]), $this->precision);
			$_obf_Jdai[] = round(((sqrt(3) / 2) * $z[$_obf_7w__]) / ($x[$_obf_7w__] + $y[$_obf_7w__] + $z[$_obf_7w__]), $this->precision);
		}

		$_obf_pr2P = array();
		$_obf_pr2P['x'] = $_obf_bsI3;
		$_obf_pr2P['y'] = $_obf_Jdai;
		return $_obf_pr2P;
	}

	public function movingAvg($x, $window = 3, $financial = false)
	{
		$_obf_FQ__ = count($x);
		$_obf_OA__ = array();
		$_obf_OA__[$window - 1] = array_sum(array_slice($x, 0, $window)) / $window;
		$_obf_7w__ = $window;

		for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
			$_obf_OA__[$_obf_7w__] = $_obf_OA__[$_obf_7w__ - 1] + (($x[$_obf_7w__] - $x[$_obf_7w__ - $window]) / $window);
		}

		if ($financial) {
			$_obf_0eSb = $_obf_OA__;
		}
		else {
			$_obf_Wd4mKJY_ = floor($window / 2);
			$_obf_0eSb = array();
			$_obf_7w__ = $window - 1;

			for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
				$_obf_0eSb[$_obf_7w__ - $_obf_Wd4mKJY_] = $_obf_OA__[$_obf_7w__];
			}
		}

		return $_obf_0eSb;
	}

	public function rank($list)
	{
		$_obf_oFXU8JTA = array();
		$_obf_VbG_4L1Y = $list;
		sort($_obf_VbG_4L1Y);

		foreach ($list as $_obf_5Q__) {
			$_obf_oFXU8JTA[] = array_search($_obf_5Q__, $_obf_VbG_4L1Y) + 1;
		}

		return $_obf_oFXU8JTA;
	}

	public function mAddition($a, $b)
	{
		$_obf_FQ__ = count($a);
		$_obf_Ag__ = count($a[1]);
		if (($_obf_FQ__ != count($b)) || ($_obf_Ag__ != count($b[1]))) {
			throw new MathException('The two matrices should be of the same dimensions');
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_XA__ = 1;

			for (; $_obf_XA__ <= $_obf_Ag__; $_obf_XA__++) {
				$_obf_KQ__[$_obf_7w__][$_obf_XA__] = $a[$_obf_7w__][$_obf_XA__] + $b[$_obf_7w__][$_obf_XA__];
			}
		}

		return $_obf_KQ__;
	}

	public function mSubtraction($a, $b)
	{
		$_obf_FQ__ = count($a);
		$_obf_Ag__ = count($a[1]);
		if (($_obf_FQ__ != count($b)) || ($_obf_Ag__ != count($b[1]))) {
			throw new MathException('The two matrices should be of the same dimensions');
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_XA__ = 1;

			for (; $_obf_XA__ <= $_obf_Ag__; $_obf_XA__++) {
				$_obf_KQ__[$_obf_7w__][$_obf_XA__] = $a[$_obf_7w__][$_obf_XA__] - $b[$_obf_7w__][$_obf_XA__];
			}
		}

		return $_obf_KQ__;
	}

	public function mMultiplication($a, $b)
	{
		if (is_numeric($b)) {
			$_obf_FQ__ = count($a);
			$_obf_Ag__ = count($a[1]);
			$_obf_7w__ = 1;

			for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
				$_obf_XA__ = 1;

				for (; $_obf_XA__ <= $_obf_Ag__; $_obf_XA__++) {
					$_obf_KQ__[$_obf_7w__][$_obf_XA__] = $a[$_obf_7w__][$_obf_XA__] * $b;
				}
			}
		}
		else {
			$_obf_ed4_ = count($a);
			$_obf_oSE_ = count($a[1]);
			$_obf_LDY_ = count($b);
			$_obf_ai8_ = count($b[1]);

			if ($_obf_oSE_ != $_obf_LDY_) {
				throw new MathException('The number of columns in first matrix should be equal to the number of rows in second matrix');
			}
			else {
				$_obf_FQ__ = $_obf_ed4_;
				$_obf_Ag__ = $_obf_ai8_;
				$_obf_5w__ = $_obf_oSE_;
			}

			$_obf_7w__ = 1;

			for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
				$_obf_XA__ = 1;

				for (; $_obf_XA__ <= $_obf_Ag__; $_obf_XA__++) {
					$_obf_KQ__[$_obf_7w__][$_obf_XA__] = 0;
					$p = 1;

					for (; $p <= $_obf_5w__; $p++) {
						$_obf_KQ__[$_obf_7w__][$_obf_XA__] += $a[$_obf_7w__][$p] * $b[$p][$_obf_XA__];
					}
				}
			}
		}

		return $_obf_KQ__;
	}

	public function mTranspose($a)
	{
		$_obf_FQ__ = count($a);
		$_obf_Ag__ = count($a[1]);
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_XA__ = 1;

			for (; $_obf_XA__ <= $_obf_Ag__; $_obf_XA__++) {
				$_obf_KQ__[$_obf_XA__][$_obf_7w__] = $a[$_obf_7w__][$_obf_XA__];
			}
		}

		return $_obf_KQ__;
	}

	public function mDeterminant($a)
	{
		$_obf_FQ__ = count($a);

		if ($_obf_FQ__ != count($a[1])) {
			throw new MathException('Determinat can be calculated only for squared matrix');
		}

		if ($_obf_FQ__ == 1) {
			$_obf_5g__ = $a[1][1];
		}
		else if ($_obf_FQ__ == 2) {
			$_obf_5g__ = ($a[1][1] * $a[2][2]) - ($a[1][2] * $a[2][1]);
		}
		else {
			$_obf_5g__ = 0;
			$_obf_7w__ = 1;

			for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
				if (($_obf_7w__ % 2) == 1) {
					$_obf_lw__ = $a[1][$_obf_7w__];
				}
				else {
					$_obf_lw__ = -1 * $a[1][$_obf_7w__];
				}

				$_obf_KQ__ = array();
				$_obf_5Q__ = 2;

				for (; $_obf_5Q__ <= $_obf_FQ__; $_obf_5Q__++) {
					$_obf_OA__ = 1;

					for (; $_obf_OA__ <= $_obf_FQ__; $_obf_OA__++) {
						if ($_obf_OA__ < $_obf_7w__) {
							$_obf_KQ__[$_obf_5Q__ - 1][$_obf_OA__] = $a[$_obf_5Q__][$_obf_OA__];
						}
						else if ($_obf_OA__ == $_obf_7w__) {
							continue;
						}
						else {
							$_obf_KQ__[$_obf_5Q__ - 1][$_obf_OA__ - 1] = $a[$_obf_5Q__][$_obf_OA__];
						}
					}
				}

				$_obf_5g__ += $_obf_lw__ * $this->mDeterminant($_obf_KQ__);
			}
		}

		return $_obf_5g__;
	}

	public function mCofactor($a)
	{
		$_obf_FQ__ = count($a);

		if ($_obf_FQ__ != count($a[1])) {
			throw new MathException('CoFactor matrix can be calculated only for squared matrix');
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_XA__ = 1;

			for (; $_obf_XA__ <= $_obf_FQ__; $_obf_XA__++) {
				if ((($_obf_7w__ + $_obf_XA__) % 2) == 1) {
					$_obf_8A__[$_obf_7w__][$_obf_XA__] = -1;
				}
				else {
					$_obf_8A__[$_obf_7w__][$_obf_XA__] = 1;
				}

				$_obf_KQ__ = array();
				$_obf_5Q__ = 1;

				for (; $_obf_5Q__ <= $_obf_FQ__; $_obf_5Q__++) {
					if ($_obf_5Q__ == $_obf_7w__) {
						continue;
					}

					$_obf_OA__ = 1;

					for (; $_obf_OA__ <= $_obf_FQ__; $_obf_OA__++) {
						if ($_obf_OA__ == $_obf_XA__) {
							continue;
						}

						if ($_obf_5Q__ < $_obf_7w__) {
							if ($_obf_OA__ < $_obf_XA__) {
								$_obf_KQ__[$_obf_5Q__][$_obf_OA__] = $a[$_obf_5Q__][$_obf_OA__];
							}
							else {
								$_obf_KQ__[$_obf_5Q__][$_obf_OA__ - 1] = $a[$_obf_5Q__][$_obf_OA__];
							}
						}
						else if ($_obf_OA__ < $_obf_XA__) {
							$_obf_KQ__[$_obf_5Q__ - 1][$_obf_OA__] = $a[$_obf_5Q__][$_obf_OA__];
						}
						else {
							$_obf_KQ__[$_obf_5Q__ - 1][$_obf_OA__ - 1] = $a[$_obf_5Q__][$_obf_OA__];
						}
					}
				}

				$_obf_8A__[$_obf_7w__][$_obf_XA__] *= $this->mDeterminant($_obf_KQ__);
			}
		}

		return $_obf_8A__;
	}

	public function mAdjoint($a)
	{
		$_obf_8A__ = $this->mCofactor($a);
		$_obf_KQ__ = $this->mTranspose($_obf_8A__);
		return $_obf_KQ__;
	}

	public function mInverse($a)
	{
		$_obf_P0cn = $this->mDeterminant($a);

		if ($_obf_P0cn == 0) {
			throw new MathException('It is singular matrix because its determinant is 0');
		}

		$_obf_SZ6Z = $this->mAdjoint($a);
		$_obf_8A__ = $this->mMultiplication($_obf_SZ6Z, 1 / $_obf_P0cn);
		return $_obf_8A__;
	}

	public function path($y, $x)
	{
		$_obf_FQ__ = count($x) + 1;
		$_obf_7w__ = 1;

		for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
			$_obf_OQ__[$_obf_7w__][1] = $this->cor($y, $x[$_obf_7w__]);
		}

		$_obf_7w__ = 1;

		for (; $_obf_7w__ < ($_obf_FQ__ - 1); $_obf_7w__++) {
			$_obf_sw__[$_obf_7w__][$_obf_7w__] = 1;
			$_obf_XA__ = $_obf_7w__ + 1;

			for (; $_obf_XA__ < $_obf_FQ__; $_obf_XA__++) {
				$_obf_sw__[$_obf_7w__][$_obf_XA__] = $this->cor($x[$_obf_7w__], $x[$_obf_XA__]);
				$_obf_sw__[$_obf_XA__][$_obf_7w__] = $_obf_sw__[$_obf_7w__][$_obf_XA__];
			}
		}

		$_obf_sw__[$_obf_FQ__ - 1][$_obf_FQ__ - 1] = 1;
		$_obf_Qre1gA__ = $this->mInverse($_obf_sw__);
		$_obf_8w__ = $this->mMultiplication($_obf_Qre1gA__, $_obf_OQ__);
		$_obf_FQ__ = count($_obf_8w__);
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_8w__[$_obf_7w__] = $_obf_8w__[$_obf_7w__][1];
		}

		return $_obf_8w__;
	}

	public function solve($a, $b)
	{
		if (count($a[1]) != count($b)) {
			throw new MathException('Number of X\'s should be same as number of equations!');
		}

		$_obf_FQ__ = count($b);
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$b[$_obf_7w__] = array(1 => $b[$_obf_7w__]);
		}

		$_obf_8jZcAA__ = $this->mInverse($a);
		$_obf_5Q__ = $this->mMultiplication($_obf_8jZcAA__, $b);
		$_obf_FQ__ = count($_obf_5Q__);
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
			$_obf_5Q__[$_obf_7w__] = $_obf_5Q__[$_obf_7w__][1];
		}

		return $_obf_5Q__;
	}
}

class MathException extends Exception
{
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
}

?>
