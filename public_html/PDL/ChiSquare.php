<?php
//dezend by http://www.yunlu99.com/
class ChiSquare1D
{
	public $Total;
	public $ObsFreq = array();
	public $ExpFreq = array();
	public $ExpProb = array();
	public $NumCells;
	public $ChiSqObt;
	public $DF;
	public $Alpha;
	public $ChiSqProb;
	public $ChiSqCrit;

	public function ChiSquare1D($ObsFreq, $Alpha = 0.05, $ExpProb = false)
	{
		$this->ObsFreq = $ObsFreq;
		$this->ExpProb = $ExpProb;
		$this->Alpha = $Alpha;
		$this->NumCells = count($this->ObsFreq);
		$this->DF = $this->NumCells - 1;
		$this->Total = $this->getTotal();
		$this->ExpFreq = $this->getExpFreq();
		$this->ChiSqObt = $this->getChiSqObt();
		$this->ChiSqCrit = $this->getChiSqCrit();
		$this->ChiSqProb = $this->getChiSqProb();
		return true;
	}

	public function getTotal()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
			$_obf_wt9gcx8_ += $this->ObsFreq[$_obf_7w__];
		}

		return $_obf_wt9gcx8_;
	}

	public function getExpFreq()
	{
		$_obf_v3oxF_Z78w__ = array();

		if ($this->ExpProb == false) {
			$_obf_UoEzRTGVEw7YMA__ = $this->Total / $this->NumCells;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
				$_obf_v3oxF_Z78w__[$_obf_7w__] = $_obf_UoEzRTGVEw7YMA__;
			}

			return $_obf_v3oxF_Z78w__;
		}
		else if (count($this->ObsFreq) == count($this->ExpProb)) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
				$_obf_v3oxF_Z78w__[$_obf_7w__] = $this->ExpProb[$_obf_7w__] * $this->Total;
			}

			return $_obf_v3oxF_Z78w__;
		}
		else {
			exit('<b>Error:</b> Array Size Mismatch');
		}
	}

	public function getChiSqObt()
	{
		$_obf_TINP_KU9FfM_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
			$_obf_TINP_KU9FfM_ += pow($this->ObsFreq[$_obf_7w__] - $this->ExpFreq[$_obf_7w__], 2) / $this->ExpFreq[$_obf_7w__];
		}

		return $_obf_TINP_KU9FfM_;
	}

	public function getChiSqCrit()
	{
		$_obf_TgR0mg__ = new Distribution();
		$_obf_Ij86sMhf_wS5 = $_obf_TgR0mg__->getInverseChiSquare($this->Alpha, $this->DF);
		return $_obf_Ij86sMhf_wS5;
	}

	public function getChiSqProb()
	{
		$_obf_TgR0mg__ = new Distribution();
		$_obf_AM_YZRvvR9An = $_obf_TgR0mg__->getChiSquare($this->ChiSqObt, $this->DF);
		return $_obf_AM_YZRvvR9An;
	}
}

require_once PHP_MATH . 'Distribution.php';

?>
