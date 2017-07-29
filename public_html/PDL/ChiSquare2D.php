<?php
//dezend by http://www.yunlu99.com/
class ChiSquare2D
{
	public $Total = 0;
	public $NumRows = 0;
	public $NumCols = 0;
	public $NumCells = 0;
	public $RowMarginals = array();
	public $ColMarginals = array();
	public $ObsFreq = array();
	public $ExpFreq = array();
	public $Alpha = 0.05;
	public $ChiSqObt = 0;
	public $ChiSqProb = 0;
	public $ChiSqCrit = 0;

	public function ChiSquare2D($ObsFreq, $Alpha = 0.05, $ExpProb = false)
	{
		$this->ObsFreq = $ObsFreq;
		$this->Alpha = $Alpha;
		$this->NumRows = count($this->ObsFreq);
		$this->NumCols = count($this->ObsFreq[0]);
		$this->NumCells = $this->NumRows * $this->NumCols;
		$this->RowMarginals = $this->getRowMarginals($this->ObsFreq, $this->NumRows, $this->NumCols);
		$this->ColMarginals = $this->getColMarginals($this->ObsFreq, $this->NumRows, $this->NumCols);
		$this->Total = array_sum($this->RowMarginals);

		if ($ExpProb == false) {
			$this->ExpFreq = $this->getExpFreq($this->Total, $this->RowMarginals, $this->ColMarginals, $this->NumRows, $this->NumCols);
		}
		else if (count($ObsFreq) == count($ExpProb)) {
			$this->ExpFreq = $this->getExpFreq($this->Total, $this->NumCells, $ExpProb);
		}
		else {
			exit('<b>Error:</b> Array Size Mismatch');
		}

		$this->ChiSqObt = $this->getChiSqObt($this->ObsFreq, $this->ExpFreq, $this->NumRows, $this->NumCols);
		$this->DF = ($this->NumRows - 1) * ($this->NumCols - 1);
		$this->ChiSqCrit = $this->getChiSqCrit($this->Alpha, $this->DF);
		$this->ChiSqProb = $this->getChiSqProb($this->ChiSqObt, $this->DF);
		return true;
	}

	public function getRowMarginals($Freq, $NumRows, $NumCols)
	{
		$_obf_Ad656oaWjYAhSGy4 = array();
		$_obf_8w__ = 0;

		for (; $_obf_8w__ < $NumRows; $_obf_8w__++) {
			$_obf_ma3EDytJhqgaRqA_ = 0;
			$_obf_Bw__ = 0;

			for (; $_obf_Bw__ < $NumCols; $_obf_Bw__++) {
				$_obf_ma3EDytJhqgaRqA_ += $Freq[$_obf_8w__][$_obf_Bw__];
			}

			$_obf_Ad656oaWjYAhSGy4[$_obf_8w__] = $_obf_ma3EDytJhqgaRqA_;
		}

		return $_obf_Ad656oaWjYAhSGy4;
	}

	public function getColMarginals($Freq, $NumRows, $NumCols)
	{
		$_obf_4T_jFuJ_W_gxJyiy = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $NumCols; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $NumRows; $_obf_XA__++) {
				$_obf_4T_jFuJ_W_gxJyiy[$_obf_7w__] += $Freq[$_obf_XA__][$_obf_7w__];
			}
		}

		return $_obf_4T_jFuJ_W_gxJyiy;
	}

	public function getExpFreq($Total, $RowMarginals, $ColMarginals, $NumRows, $NumCols, $ExpProb = false)
	{
		$_obf_v3oxF_Z78w__ = array();

		if ($ExpProb == false) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $NumRows; $_obf_7w__++) {
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $NumCols; $_obf_XA__++) {
					if ($Total != 0) {
						$_obf_v3oxF_Z78w__[$_obf_7w__][$_obf_XA__] = ($RowMarginals[$_obf_7w__] * $ColMarginals[$_obf_XA__]) / $Total;
					}
				}
			}
		}
		else {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $NumRows; $_obf_7w__++) {
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $NumCols; $_obf_XA__++) {
					$_obf_v3oxF_Z78w__[$_obf_7w__][$_obf_XA__] = $ExpProb[$_obf_7w__][$_obf_XA__] * $Total;
				}
			}
		}

		return $_obf_v3oxF_Z78w__;
	}

	public function getChiSqObt($ObsFreq, $ExpFreq, $NumRows, $NumCols)
	{
		$_obf_TINP_KU9FfM_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $NumRows; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $NumCols; $_obf_XA__++) {
				if ($ExpFreq[$_obf_7w__][$_obf_XA__] != 0) {
					$_obf_TINP_KU9FfM_ += pow($ObsFreq[$_obf_7w__][$_obf_XA__] - $ExpFreq[$_obf_7w__][$_obf_XA__], 2) / $ExpFreq[$_obf_7w__][$_obf_XA__];
				}
			}
		}

		return $_obf_TINP_KU9FfM_;
	}

	public function getChiSqCrit($Alpha, $DF)
	{
		$_obf_TgR0mg__ = new Distribution();
		$_obf_Ij86sMhf_wS5 = $_obf_TgR0mg__->getInverseChiSquare($Alpha, $DF);
		return $_obf_Ij86sMhf_wS5;
	}

	public function getChiSqProb($ChiSqObt, $DF)
	{
		$_obf_TgR0mg__ = new Distribution();
		$_obf_AM_YZRvvR9An = $_obf_TgR0mg__->getChiSquare($ChiSqObt, $DF);
		return $_obf_AM_YZRvvR9An;
	}
}

require_once PHP_MATH . 'Distribution.php';

?>
