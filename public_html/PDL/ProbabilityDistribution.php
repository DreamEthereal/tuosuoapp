<?php
//dezend by http://www.yunlu99.com/
class ProbabilityDistribution
{
	public function ProbabilityDistribution()
	{
	}

	public function PDF($X)
	{
		if (is_array($X)) {
			$_obf__m_c_dEJEfY_ = array();
			$_obf_wTa_tsbBK2Y_ = count($X);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_wTa_tsbBK2Y_; $_obf_7w__++) {
				$_obf__m_c_dEJEfY_[$_obf_7w__] = $this->_getPDF($X[$_obf_7w__]);
			}

			return $_obf__m_c_dEJEfY_;
		}
		else {
			return $this->_getPDF($X);
		}
	}

	public function _getPDF($x)
	{
	}

	public function CDF($X)
	{
		if (is_array($X)) {
			$_obf_Y2VhfxCCR3g_ = array();
			$_obf_wTa_tsbBK2Y_ = count($X);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_wTa_tsbBK2Y_; $_obf_7w__++) {
				$_obf_Y2VhfxCCR3g_[$_obf_7w__] = $this->_getCDF($X[$_obf_7w__]);
			}

			return $_obf_Y2VhfxCCR3g_;
		}
		else {
			return $this->_getCDF($X);
		}
	}

	public function _getCDF($x)
	{
	}

	public function ICDF($P)
	{
		if (is_array($P)) {
			$_obf_mBHpZIoCsJs_ = array();
			$_obf_wTa_tsbBK2Y_ = count($P);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $_obf_wTa_tsbBK2Y_; $_obf_7w__++) {
				$_obf_mBHpZIoCsJs_[$_obf_7w__] = $this->_getICDF($P[$_obf_7w__]);
			}

			return $_obf_mBHpZIoCsJs_;
		}
		else {
			return $this->_getICDF($P);
		}
	}

	public function _getICDF($p)
	{
	}

	public function RNG($n = 1)
	{
		if ($n < 1) {
			return PEAR::raiseError('Number of random numbers to return must be 1 or greater');
		}

		if (1 < $n) {
			$_obf_degE2wQPp4U_ = array();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $n; $_obf_7w__++) {
				$_obf_degE2wQPp4U_[$_obf_7w__] = $this->_getRNG();
			}

			return $_obf_degE2wQPp4U_;
		}
		else {
			return $this->_getRNG();
		}
	}

	public function _getRNG()
	{
	}

	public function checkRange($x, $lo = 0, $hi = 1)
	{
		if (($x < $lo) || ($hi < $x)) {
			return PEAR::raiseError('The argument should be between ' . $lo . ' and ' . $hi . '.');
		}
	}

	public function getFactorial($n)
	{
		return $n <= 1 ? 1 : $n * $this->getFactorial($n - 1);
	}

	public function findRoot($prob, $guess, $xLo, $xHi)
	{
		$_obf_PA7a1K4TRv8_ = 1.0E-10;
		$_obf_oogf7Jso4Kkdbuun = 150;
		$_obf_5Q__ = $guess;
		$_obf_Loj9cA__ = $guess;
		$_obf_rixiYSg_ = 0;
		$_obf_MxUT = 0;
		$_obf_yKo_ = 1000;
		$_obf_7w__ = 0;

		while (($_obf_PA7a1K4TRv8_ < abs($_obf_yKo_)) && ($_obf_7w__++ < $_obf_oogf7Jso4Kkdbuun)) {
			$_obf_rixiYSg_ = $this->CDF($_obf_5Q__) - $prob;

			if ($_obf_rixiYSg_ < 0) {
				$xLo = $_obf_5Q__;
			}
			else {
				$xHi = $_obf_5Q__;
			}

			$_obf_MxUT = $this->PDF($_obf_5Q__);

			if ($_obf_MxUT != 0) {
				$_obf_yKo_ = $_obf_rixiYSg_ / $_obf_MxUT;
				$_obf_Loj9cA__ = $_obf_5Q__ - $_obf_yKo_;
			}

			if (($_obf_Loj9cA__ < $xLo) || ($xHi < $_obf_Loj9cA__) || ($_obf_MxUT == 0)) {
				$_obf_Loj9cA__ = ($xLo + $xHi) / 2;
				$_obf_yKo_ = $_obf_Loj9cA__ - $_obf_5Q__;
			}

			$_obf_5Q__ = $_obf_Loj9cA__;
		}

		return $_obf_5Q__;
	}
}

include_once ROOT_PATH . 'PDL/PEAR.php';
include_once ROOT_PATH . 'PDL/NumericalConstants.php';
echo ' ';

?>
