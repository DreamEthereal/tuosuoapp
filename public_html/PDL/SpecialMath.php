<?php
//dezend by http://www.yunlu99.com/
function logbeta($p, $q)
{
	global $logBetaCache_res;
	global $logBetaCache_p;
	global $logBetaCache_q;
	if (($p != $logBetaCache_p) || ($q != $logBetaCache_q)) {
		$logBetaCache_p = $p;
		$logBetaCache_q = $q;
		if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
			$logBetaCache_res = 0;
		}
		else {
			$logBetaCache_res = (loggamma($p) + loggamma($q)) - loggamma($p + $q);
		}
	}

	return $logBetaCache_res;
}

function _obf_dyk7aW0_($x)
{
	$P1B = array(-1.7161851388655, 24.765650805576, -379.80425647095, 629.33115531282, 866.96620279041, -31451.272968848, -36144.413418691, 66456.143820241);
	$_obf_Q9Rw = array(-30.840230011974, 315.3506269796, -1015.1563674902, -3107.7716715723, 22538.11842098, 4755.8462775279, -134659.95986497, -115132.25967555);
	$_obf_Cj3_ = array(-0.001910444077728, 0.00084171387781295, -0.0005952379913043, 0.00079365079350035, -0.0027777777777777, 0.083333333333333, 0.0057083835261);
	$_obf_3k3ylg__ = 1;
	$_obf_7w__ = 0;
	$_obf_FQ__ = 0;
	$_obf_OA__ = $x;
	$_obf_2tuumXI5 = false;

	if ($_obf_OA__ <= 0) {
		$_obf_OA__ = 0 - $x;
		$_obf_UAc_ = (int) $_obf_OA__;
		$_obf_6UUC = $_obf_OA__ - $_obf_UAc_;

		if ($_obf_6UUC != 0) {
			if ($_obf_UAc_ != (int) $_obf_UAc_ * 0.5 * 2) {
				$_obf_2tuumXI5 = true;
			}

			$_obf_3k3ylg__ = (0 - M_PI) / sin(M_PI * $_obf_6UUC);
			$_obf_OA__++;
		}
		else {
			return MAX_VALUE;
		}
	}

	if ($_obf_OA__ < EPS) {
		if (XMININ <= $_obf_OA__) {
			$_obf_6UUC = 1 / $_obf_OA__;
		}
		else {
			return MAX_VALUE;
		}
	}
	else if ($_obf_OA__ < 12) {
		$_obf_UAc_ = $_obf_OA__;

		if ($_obf_OA__ < 1) {
			$_obf_gQ__ = $_obf_OA__;
			$_obf_OA__++;
		}
		else {
			$_obf_FQ__ = (int) $_obf_OA__ - 1;
			$_obf_OA__ -= (double) $_obf_FQ__;
			$_obf_gQ__ = $_obf_OA__ - 1;
		}

		$_obf_ar1MtA__ = 0;
		$_obf_nKkrmA__ = 1;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < 8; ++$_obf_7w__) {
			$_obf_ar1MtA__ = ($_obf_ar1MtA__ + $P1B[$_obf_7w__]) * $_obf_gQ__;
			$_obf_nKkrmA__ = ($_obf_nKkrmA__ * $_obf_gQ__) + $_obf_Q9Rw[$_obf_7w__];
		}

		$_obf_6UUC = ($_obf_ar1MtA__ / $_obf_nKkrmA__) + 1;

		if ($_obf_UAc_ < $_obf_OA__) {
			$_obf_6UUC /= $_obf_UAc_;
		}
		else if ($_obf_OA__ < $_obf_UAc_) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_FQ__; ++$_obf_7w__) {
				$_obf_6UUC *= $_obf_OA__;
				$_obf_OA__++;
			}
		}
	}
	else if ($_obf_OA__ <= GAMMA_X_MAX_VALUE) {
		$_obf_C9jl = $_obf_OA__ * $_obf_OA__;
		$_obf_bhdW = $_obf_Cj3_[6];
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < 6; ++$_obf_7w__) {
			$_obf_bhdW = ($_obf_bhdW / $_obf_C9jl) + $_obf_Cj3_[$_obf_7w__];
		}

		$_obf_bhdW = (($_obf_bhdW / $_obf_OA__) - $_obf_OA__) + log(SQRT2PI);
		$_obf_bhdW += ($_obf_OA__ - 0.5) * log($_obf_OA__);
		$_obf_6UUC = exp($_obf_bhdW);
	}
	else {
		return MAX_VALUE;
	}

	if ($_obf_2tuumXI5) {
		$_obf_6UUC = 0 - $_obf_6UUC;
	}

	if ($_obf_3k3ylg__ != 1) {
		$_obf_6UUC = $_obf_3k3ylg__ / $_obf_6UUC;
	}

	return $_obf_6UUC;
}

function loggamma($x)
{
	global $logGammaCache_res;
	global $logGammaCache_x;
	$_obf_usGYam4_ = -0.57721566490153;
	$_obf_LWLvE_U_ = 0.42278433509847;
	$_obf_ozBod_0_ = 1.7917594692281;
	$_obf_KznpC9E_ = array(4.9452353592967, 201.81126208568, 2290.8383738313, 11319.672059034, 28557.246356716, 38484.962284438, 26377.487876242, 7225.8139797003);
	$_obf_5CdZ5PE_ = array(4.9746078455689, 542.41385998911, 15506.938649784, 184793.29044456, 1088204.7694688, 3338152.967987, 5106661.6789274, 3074109.0548505);
	$_obf_1aibSNs_ = array(14745.021660599, 2426813.3694867, 121475557.40451, 2663432449.631, 29403789566.346, 170266573776.54, 492612579337.74, 560625185622.4);
	$_obf_Otibljo_ = array(67.482125503038, 1113.3323938572, 7738.7570569354, 27639.870744033, 54993.102062262, 61611.22180066, 36351.275915019, 8785.536302431);
	$_obf_19xyR1E_ = array(183.03283993706, 7765.049321445, 133190.38279661, 1136705.821322, 5267964.1174379, 13467014.543111, 17827365.303533, 9533095.5918444);
	$_obf_3Zyz6Oc_ = array(2690.5301758709, 639388.56543001, 41355999.302414, 1120872109.6161, 14886137286.788, 101680358627.24, 341747634550.74, 446315818741.97);
	$_obf_X3PuUQ__ = array(-0.001910444077728, 0.00084171387781295, -0.0005952379913043, 0.00079365079350035, -0.0027777777777777, 0.083333333333333, 0.0057083835261);
	$_obf_zKf8WwtBivhR = 2.25E+76;
	$_obf__aQxAmk_ = 0.6796875;

	if ($x == $logGammaCache_x) {
		return $logGammaCache_res;
	}

	$_obf_OA__ = $x;
	if ((0 < $_obf_OA__) && ($_obf_OA__ <= LOG_GAMMA_X_MAX_VALUE)) {
		if ($_obf_OA__ <= EPS) {
			$_obf_6UUC = 0 - log(y);
		}
		else if ($_obf_OA__ <= 1.5) {
			if ($_obf_OA__ < $_obf__aQxAmk_) {
				$_obf_Cn5VFA__ = 0 - log($_obf_OA__);
				$_obf__cfA = $_obf_OA__;
			}
			else {
				$_obf_Cn5VFA__ = 0;
				$_obf__cfA = $_obf_OA__ - 1;
			}

			if (($_obf_OA__ <= 0.5) || ($_obf__aQxAmk_ <= $_obf_OA__)) {
				$_obf_nKkrmA__ = 1;
				$_obf_ar1MtA__ = 0;
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < 8; $_obf_7w__++) {
					$_obf_ar1MtA__ = ($_obf_ar1MtA__ * $_obf__cfA) + $_obf_KznpC9E_[$_obf_7w__];
					$_obf_nKkrmA__ = ($_obf_nKkrmA__ * $_obf__cfA) + $_obf_Otibljo_[$_obf_7w__];
				}

				$_obf_6UUC = $_obf_Cn5VFA__ + ($_obf__cfA * ($_obf_usGYam4_ + ($_obf__cfA * ($_obf_ar1MtA__ / $_obf_nKkrmA__))));
			}
			else {
				$_obf_heBj = $_obf_OA__ - 1;
				$_obf_nKkrmA__ = 1;
				$_obf_ar1MtA__ = 0;
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < 8; $_obf_7w__++) {
					$_obf_ar1MtA__ = ($_obf_ar1MtA__ * $_obf_heBj) + $_obf_5CdZ5PE_[$_obf_7w__];
					$_obf_nKkrmA__ = ($_obf_nKkrmA__ * $_obf_heBj) + $_obf_19xyR1E_[$_obf_7w__];
				}

				$_obf_6UUC = $_obf_Cn5VFA__ + ($_obf_heBj * ($_obf_LWLvE_U_ + ($_obf_heBj * ($_obf_ar1MtA__ / $_obf_nKkrmA__))));
			}
		}
		else if ($_obf_OA__ <= 4) {
			$_obf_heBj = $_obf_OA__ - 2;
			$_obf_nKkrmA__ = 1;
			$_obf_ar1MtA__ = 0;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < 8; $_obf_7w__++) {
				$_obf_ar1MtA__ = ($_obf_ar1MtA__ * $_obf_heBj) + $_obf_5CdZ5PE_[$_obf_7w__];
				$_obf_nKkrmA__ = ($_obf_nKkrmA__ * $_obf_heBj) + $_obf_19xyR1E_[$_obf_7w__];
			}

			$_obf_6UUC = $_obf_heBj * ($_obf_LWLvE_U_ + ($_obf_heBj * ($_obf_ar1MtA__ / $_obf_nKkrmA__)));
		}
		else if ($_obf_OA__ <= 12) {
			$_obf_lEPz = $_obf_OA__ - 4;
			$_obf_nKkrmA__ = -1;
			$_obf_ar1MtA__ = 0;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < 8; $_obf_7w__++) {
				$_obf_ar1MtA__ = ($_obf_ar1MtA__ * $_obf_lEPz) + $_obf_1aibSNs_[$_obf_7w__];
				$_obf_nKkrmA__ = ($_obf_nKkrmA__ * $_obf_lEPz) + $_obf_3Zyz6Oc_[$_obf_7w__];
			}

			$_obf_6UUC = $_obf_ozBod_0_ + ($_obf_lEPz * ($_obf_ar1MtA__ / $_obf_nKkrmA__));
		}
		else {
			$_obf_6UUC = 0;

			if ($_obf_OA__ <= $_obf_zKf8WwtBivhR) {
				$_obf_6UUC = $_obf_X3PuUQ__[6];
				$_obf_C9jl = $_obf_OA__ * $_obf_OA__;
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < 6; $_obf_7w__++) {
					$_obf_6UUC = ($_obf_6UUC / $_obf_C9jl) + $_obf_X3PuUQ__[$_obf_7w__];
				}
			}

			$_obf_6UUC /= $_obf_OA__;
			$_obf_Cn5VFA__ = log($_obf_OA__);
			$_obf_6UUC = ($_obf_6UUC + log(SQRT2PI)) - (0.5 * $_obf_Cn5VFA__);
			$_obf_6UUC += $_obf_OA__ * ($_obf_Cn5VFA__ - 1);
		}
	}
	else {
		$_obf_6UUC = MAX_VALUE;
	}

	$logGammaCache_x = $x;
	$logGammaCache_res = $_obf_6UUC;
	return $_obf_6UUC;
}

function incompletegamma($a, $x)
{
	if (($x <= 0) || ($a <= 0) || (LOG_GAMMA_X_MAX_VALUE < $a)) {
		return 0;
	}

	if ($x < ($a + 1)) {
		return gammaseriesexpansion($a, $x);
	}
	else {
		return 1 - gammafraction($a, $x);
	}
}

function gammaseriesexpansion($a, $x)
{
	$_obf_A7Q_ = $a;
	$_obf_ttmu = 1 / $a;
	$_obf_bhdW = $_obf_ttmu;
	$_obf_FQ__ = 1;

	for (; $_obf_FQ__ < MAX_ITERATIONS; $_obf_FQ__++) {
		++$_obf_A7Q_;
		$_obf_ttmu *= $x / $_obf_A7Q_;
		$_obf_bhdW += $_obf_ttmu;

		if ($_obf_ttmu < ($_obf_bhdW * PRECISION)) {
			return $_obf_bhdW * exp(((0 - $x) + ($a * log($x))) - loggamma($a));
		}
	}

	return PEAR::raiseError('Maximum iterations exceeded: please file a bug report.');
}

function gammafraction($a, $x)
{
	$_obf_8A__ = ($x + 1) - $a;
	$_obf_KQ__ = 1 / XMININ;
	$_obf_5g__ = 1 / $_obf_8A__;
	$M = $_obf_5g__;
	$_obf_ttmu = 0;
	$_obf_7w__ = 1;

	for (; PRECISION < abs($_obf_ttmu - 1); $_obf_7w__++) {
		$_obf_ed4_ = (0 - $_obf_7w__) * ($_obf_7w__ - $a);
		$_obf_8A__ += 2;
		$_obf_5g__ = ($_obf_ed4_ * $_obf_5g__) + $_obf_8A__;
		$_obf_KQ__ = $_obf_8A__ + ($_obf_ed4_ / $_obf_KQ__);

		if (abs($_obf_KQ__) < XMININ) {
			$_obf_KQ__ = XMININ;
		}

		if (abs($_obf_5g__) < XMININ) {
			$_obf_KQ__ = XMININ;
		}

		$_obf_5g__ = 1 / $_obf_5g__;
		$_obf_ttmu = $_obf_5g__ * $_obf_KQ__;
		$M *= $_obf_ttmu;
	}

	return exp(((0 - $x) + ($a * log($x))) - loggamma($a)) * $M;
}

function beta($p, $q)
{
	if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
		return 0;
	}
	else {
		return exp(logbeta($p, $q));
	}
}

function incompletebeta($x, $p, $q)
{
	if ($x <= 0) {
		return 0;
	}
	else if (1 <= $x) {
		return 1;
	}
	else {
		if (($p <= 0) || ($q <= 0) || (LOG_GAMMA_X_MAX_VALUE < ($p + $q))) {
			return 0;
		}
		else {
			$_obf_wabgHeGyi0U_ = exp((0 - logbeta($p, $q)) + ($p * log($x)) + ($q * log(1 - $x)));

			if ($x < (($p + 1) / ($p + $q + 2))) {
				return ($_obf_wabgHeGyi0U_ * betafraction($x, $p, $q)) / $p;
			}
			else {
				return 1 - (($_obf_wabgHeGyi0U_ * betafraction(1 - $x, $q, $p)) / $q);
			}
		}
	}
}

function betafraction($x, $p, $q)
{
	$_obf_KQ__ = 1;
	$_obf_5URUHe8B = $p + $q;
	$_obf_uTi9K_iP = $p + 1;
	$_obf_nFRWaLtwug__ = $p - 1;
	$M = 1 - (($_obf_5URUHe8B * $x) / $_obf_uTi9K_iP);

	if (abs($M) < XMININ) {
		$M = XMININ;
	}

	$M = 1 / $M;
	$_obf_7EnKyg__ = $M;
	$_obf_Ag__ = 1;
	$_obf_B9nHjAs_ = 0;

	while (($_obf_Ag__ <= MAX_ITERATIONS) && (PRECISION < abs($_obf_B9nHjAs_ - 1))) {
		$_obf_x8A_ = 2 * $_obf_Ag__;
		$_obf_5g__ = ($_obf_Ag__ * ($q - $_obf_Ag__) * $x) / (($_obf_nFRWaLtwug__ + $_obf_x8A_) * ($p + $_obf_x8A_));
		$M = 1 + ($_obf_5g__ * $M);

		if (abs($M) < XMININ) {
			$M = XMININ;
		}

		$M = 1 / $M;
		$_obf_KQ__ = 1 + ($_obf_5g__ / $_obf_KQ__);

		if (abs($_obf_KQ__) < XMININ) {
			$_obf_KQ__ = XMININ;
		}

		$_obf_7EnKyg__ *= $M * $_obf_KQ__;
		$_obf_5g__ = ((0 - ($p + $_obf_Ag__)) * ($_obf_5URUHe8B + $_obf_Ag__) * $x) / (($p + $_obf_x8A_) * ($_obf_uTi9K_iP + $_obf_x8A_));
		$M = 1 + ($_obf_5g__ * $M);

		if (abs($M) < XMININ) {
			$M = XMININ;
		}

		$M = 1 / $M;
		$_obf_KQ__ = 1 + ($_obf_5g__ / $_obf_KQ__);

		if (abs($_obf_KQ__) < XMININ) {
			$_obf_KQ__ = XMININ;
		}

		$_obf_B9nHjAs_ = $M * $_obf_KQ__;
		$_obf_7EnKyg__ *= $_obf_B9nHjAs_;
		$_obf_Ag__++;
	}

	return $_obf_7EnKyg__;
}

function error($x)
{
	$_obf_2cD__70_ = 0.12837916709551;
	$_obf_vA2K = array(0.12837916709551, -0.325042107247, -0.028481749575599, -0.0057702702964894, -2.376301665665E-5);
	$_obf_n4es = array(0.39791722395916, 0.065022249988767, 0.0050813062818758, 0.00013249473800432, -3.9602282787754E-6);
	$_obf_jTH8 = array(-0.0023621185607527, 0.41485611868375, -0.3722078760357, 0.31834661990116, -0.1108946942824, 0.035478304325618, -0.0021663755948688);
	$_obf_M7Mf = array(0.10642088040084, 0.54039791770217, 0.071828654414196, 0.12617121980876, 0.013637083912029, 0.011984499846799);
	$_obf_HA2LJ7k_ = 0.84506291151047;
	$_obf_JB9JyHU_ = (0 <= $x ? $x : 0 - $x);

	if ($_obf_JB9JyHU_ < 0.84375) {
		if ($_obf_JB9JyHU_ < 3.7252902984619E-9) {
			$_obf_cG4B7L_a = $_obf_JB9JyHU_ + ($_obf_JB9JyHU_ * $_obf_2cD__70_);
		}
		else {
			$p = $x * $x;
			$_obf_FA__ = $_obf_vA2K[0] + ($p * ($_obf_vA2K[1] + ($p * ($_obf_vA2K[2] + ($p * ($_obf_vA2K[3] + ($p * $_obf_vA2K[4])))))));
			$_obf_oQ__ = 1 + ($p * ($_obf_n4es[0] + ($p * ($_obf_n4es[1] + ($p * ($_obf_n4es[2] + ($p * ($_obf_n4es[3] + ($p * $_obf_n4es[4])))))))));
			$_obf_cG4B7L_a = $_obf_JB9JyHU_ + ($_obf_JB9JyHU_ * ($_obf_FA__ / $_obf_oQ__));
		}
	}
	else if ($_obf_JB9JyHU_ < 1.25) {
		$p = $_obf_JB9JyHU_ - 1;
		$_obf_FA__ = $_obf_jTH8[0] + ($p * ($_obf_jTH8[1] + ($p * ($_obf_jTH8[2] + ($p * ($_obf_jTH8[3] + ($p * ($_obf_jTH8[4] + ($p * ($_obf_jTH8[5] + ($p * $_obf_jTH8[6])))))))))));
		$_obf_oQ__ = 1 + ($p * ($_obf_M7Mf[0] + ($p * ($_obf_M7Mf[1] + ($p * ($_obf_M7Mf[2] + ($p * ($_obf_M7Mf[3] + ($p * ($_obf_M7Mf[4] + ($p * $_obf_M7Mf[5])))))))))));
		$_obf_cG4B7L_a = $_obf_HA2LJ7k_ + ($_obf_FA__ / $_obf_oQ__);
	}
	else if (6 <= $_obf_JB9JyHU_) {
		$_obf_cG4B7L_a = 1;
	}
	else {
		$_obf_cG4B7L_a = 1 - complementaryerror($_obf_JB9JyHU_);
	}

	return 0 <= $x ? $_obf_cG4B7L_a : 0 - $_obf_cG4B7L_a;
}

function complementaryerror($x)
{
	$_obf_bSel = array(-0.0098649440348471, -0.69385857270718, -10.558626225323, -62.375332450326, -162.39666946257, -184.60509290671, -81.287435506307, -9.8143293441691);
	$_obf_8ZU0 = array(19.651271667439, 137.65775414352, 434.56587747523, 645.38727173327, 429.00814002757, 108.63500554178, 6.5702497703193, -0.060424415214858);
	$_obf_wRuh = array(-0.0098649429247001, -0.79928323768052, -17.757954917755, -160.63638485582, -637.56644336839, -1025.0951316111, -483.51919160865);
	$_obf_UNfh = array(30.338060743482, 325.79251299657, 1536.7295860844, 3199.8582195086, 2553.0504064332, 474.52854120696, -22.440952446586);
	$_obf_JB9JyHU_ = (0 <= $x ? $x : 0 - $x);

	if ($_obf_JB9JyHU_ < 1.25) {
		$_obf_cG4B7L_a = 1 - error($_obf_JB9JyHU_);
	}
	else if (28 < $_obf_JB9JyHU_) {
		$_obf_cG4B7L_a = 0;
	}
	else {
		$p = 1 / ($_obf_JB9JyHU_ * $_obf_JB9JyHU_);

		if ($_obf_JB9JyHU_ < 2.8571428) {
			$_obf_sw__ = $_obf_bSel[0] + ($p * ($_obf_bSel[1] + ($p * ($_obf_bSel[2] + ($p * ($_obf_bSel[3] + ($p * ($_obf_bSel[4] + ($p * ($_obf_bSel[5] + ($p * ($_obf_bSel[6] + ($p * $_obf_bSel[7])))))))))))));
			$_obf_Dg__ = 1 + ($p * ($_obf_8ZU0[0] + ($p * ($_obf_8ZU0[1] + ($p * ($_obf_8ZU0[2] + ($p * ($_obf_8ZU0[3] + ($p * ($_obf_8ZU0[4] + ($p * ($_obf_8ZU0[5] + ($p * ($_obf_8ZU0[6] + ($p * $_obf_8ZU0[7])))))))))))))));
		}
		else {
			$_obf_sw__ = $_obf_wRuh[0] + ($p * ($_obf_wRuh[1] + ($p * ($_obf_wRuh[2] + ($p * ($_obf_wRuh[3] + ($p * ($_obf_wRuh[4] + ($p * ($_obf_wRuh[5] + ($p * $_obf_wRuh[6])))))))))));
			$_obf_Dg__ = 1 + ($p * ($_obf_UNfh[0] + ($p * ($_obf_UNfh[1] + ($p * ($_obf_UNfh[2] + ($p * ($_obf_UNfh[3] + ($p * ($_obf_UNfh[4] + ($p * ($_obf_UNfh[5] + ($p * $_obf_UNfh[6])))))))))))));
		}

		$_obf_cG4B7L_a = exp((((0 - $x) * $x) - 0.5625) + ($_obf_sw__ / $_obf_Dg__)) / $_obf_JB9JyHU_;
	}

	return 0 <= $x ? $_obf_cG4B7L_a : 2 - $_obf_cG4B7L_a;
}

$logBetaCache_res = 0;
$logBetaCache_p = 0;
$logBetaCache_q = 0;
$logGammaCache_res = 0;
$logGammaCache_x = 0;
echo ' ' . "\n" . '';

?>
