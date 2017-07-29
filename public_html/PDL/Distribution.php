<?php
//dezend by http://www.yunlu99.com/
class Distribution
{
	public function doCommonMath($q, $i, $j, $b)
	{
		$_obf_X5c_ = 1;
		$_obf_gQ__ = $_obf_X5c_;
		$_obf_5w__ = $i;

		while ($_obf_5w__ <= $j) {
			$_obf_X5c_ = ($_obf_X5c_ * $q * $_obf_5w__) / ($_obf_5w__ - $b);
			$_obf_gQ__ = $_obf_gQ__ + $_obf_X5c_;
			$_obf_5w__ = $_obf_5w__ + 2;
		}

		return $_obf_gQ__;
	}

	public function getStudentT($t, $df)
	{
		$t = abs($t);
		$_obf_hg__ = $t / sqrt($df);
		$_obf_a7Q_ = atan($_obf_hg__);

		if ($df == 1) {
			return 1 - ($_obf_a7Q_ / pi() / 2);
		}

		$_obf_D_nR = sin($_obf_a7Q_);
		$_obf_zLP8 = cos($_obf_a7Q_);

		if (($df % 2) == 1) {
			return 1 - (($_obf_a7Q_ + ($_obf_D_nR * $_obf_zLP8 * $this->doCommonMath($_obf_zLP8 * $_obf_zLP8, 2, $df - 3, -1))) / pi() / 2);
		}
		else {
			return 1 - ($_obf_D_nR * $this->doCommonMath($_obf_zLP8 * $_obf_zLP8, 1, $df - 3, -1));
		}
	}

	public function getInverseStudentT($p, $df)
	{
		$_obf_6A__ = 0.5;
		$_obf_5DM_ = 0.5;
		$_obf_lw__ = 0;

		while (1.0E-6 < $_obf_5DM_) {
			$_obf_lw__ = (1 / $_obf_6A__) - 1;
			$_obf_5DM_ = $_obf_5DM_ / 2;

			if ($p < $this->getStudentT($_obf_lw__, $df)) {
				$_obf_6A__ = $_obf_6A__ - $_obf_5DM_;
			}
			else {
				$_obf_6A__ = $_obf_6A__ + $_obf_5DM_;
			}
		}

		return $_obf_lw__;
	}

	public function getFisherF($f, $n1, $n2)
	{
		$_obf_5Q__ = $n2 / (($n1 * $f) + $n2);

		if (($n1 % 2) == 0) {
			return $this->doCommonMath(1 - $_obf_5Q__, $n2, ($n1 + $n2) - 4, $n2 - 2) * pow($_obf_5Q__, $n2 / 2);
		}

		if (($n2 % 2) == 0) {
			return 1 - ($this->doCommonMath($_obf_5Q__, $n1, ($n1 + $n2) - 4, $n1 - 2) * pow(1 - $_obf_5Q__, $n1 / 2));
		}

		$_obf_a7Q_ = atan(sqrt(($n1 * $f) / $n2));
		$m = $_obf_a7Q_ / pi() / 2;
		$_obf_D_nR = sin($_obf_a7Q_);
		$_obf_zLP8 = cos($_obf_a7Q_);

		if (1 < $n2) {
			$m = $m + (($_obf_D_nR * $_obf_zLP8 * $this->doCommonMath($_obf_zLP8 * $_obf_zLP8, 2, $n2 - 3, -1)) / pi() / 2);
		}

		if ($n1 == 1) {
			return 1 - $m;
		}

		$_obf_KQ__ = (4 * $this->doCommonMath($_obf_D_nR * $_obf_D_nR, $n2 + 1, ($n1 + $n2) - 4, $n2 - 2) * $_obf_D_nR * pow($_obf_zLP8, $n2)) / pi();

		if ($n2 == 1) {
			return (1 - $m) + ($_obf_KQ__ / 2);
		}

		$_obf_5w__ = 2;

		while ($_obf_5w__ <= ($n2 - 1) / 2) {
			$_obf_KQ__ = ($_obf_KQ__ * $_obf_5w__) / ($_obf_5w__ - 0.5);
			$_obf_5w__ = $_obf_5w__ + 1;
		}

		return (1 - $m) + $_obf_KQ__;
	}

	public function getInverseFisherF($p, $n1, $n2)
	{
		$_obf_6A__ = 0.5;
		$_obf_5DM_ = 0.5;
		$_obf_6Q__ = 0;

		while (1.0E-10 < $_obf_5DM_) {
			$_obf_6Q__ = (1 / $_obf_6A__) - 1;
			$_obf_5DM_ = $_obf_5DM_ / 2;

			if ($p < $this->getFisherF($_obf_6Q__, $n1, $n2)) {
				$_obf_6A__ = $_obf_6A__ - $_obf_5DM_;
			}
			else {
				$_obf_6A__ = $_obf_6A__ + $_obf_5DM_;
			}
		}

		return $_obf_6Q__;
	}

	public function getChiSquare($x, $n)
	{
		if (($n == 1) && (1000 < $x)) {
			return 0;
		}

		if ((1000 < $x) || (1000 < $n)) {
			$_obf_Bw__ = $this->getChiSquare((($x - $n) * ($x - $n)) / (2 * $n), 1) / 2;

			if ($n < $x) {
				return $_obf_Bw__;
			}
			else {
				return 1 - $_obf_Bw__;
			}
		}

		$_obf_8w__ = exp(-0.5 * $x);

		if (($n % 2) == 1) {
			$_obf_8w__ = $_obf_8w__ * sqrt((2 * $x) / pi());
		}

		$_obf_5w__ = $n;

		while (2 <= $_obf_5w__) {
			$_obf_8w__ = $_obf_8w__ * ($x / $_obf_5w__);
			$_obf_5w__ = $_obf_5w__ - 2;
		}

		$_obf_lw__ = $_obf_8w__;
		$m = $n;

		while ((1.0E-10 * $_obf_8w__) < $_obf_lw__) {
			$m = $m + 2;
			$_obf_lw__ = $_obf_lw__ * ($x / $m);
			$_obf_8w__ = $_obf_8w__ + $_obf_lw__;
		}

		return 1 - $_obf_8w__;
	}

	public function getInverseChiSquare($p, $n)
	{
		$_obf_6A__ = 0.5;
		$_obf_5DM_ = 0.5;
		$_obf_5Q__ = 0;

		while (1.0E-10 < $_obf_5DM_) {
			$_obf_5Q__ = (1 / $_obf_6A__) - 1;
			$_obf_5DM_ = $_obf_5DM_ / 2;

			if ($p < $this->getChiSquare($_obf_5Q__, $n)) {
				$_obf_6A__ = $_obf_6A__ - $_obf_5DM_;
			}
			else {
				$_obf_6A__ = $_obf_6A__ + $_obf_5DM_;
			}
		}

		return $_obf_5Q__;
	}
}


?>
