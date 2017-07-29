<?php
//dezend by http://www.yunlu99.com/
class LUDecomposition
{
	/**
  * Decomposition storage
  * @var array
  */
	public $LU = array();
	/**
  * Row dimension.
  * @var int  
  */
	public $m;
	/**
  * Column dimension.
  * @var int    
  */
	public $n;
	/**
  * Pivot sign.
  * @var int    
  */
	public $pivsign;
	/**
  * Internal storage of pivot vector.
  * @var array  
  */
	public $piv = array();

	public function LUDecomposition($A)
	{
		if (is_a($A, 'Matrix')) {
			$this->LU = $A->getArrayCopy();
			$this->m = $A->getRowDimension();
			$this->n = $A->getColumnDimension();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
				$this->piv[$_obf_7w__] = $_obf_7w__;
			}

			$this->pivsign = 1;
			$_obf_8yP_z2i0 = array();
			$_obf_SsAEGdhX = array();
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_SsAEGdhX[$_obf_7w__] = &$this->LU[$_obf_7w__][$_obf_XA__];
				}

				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_8yP_z2i0 = $this->LU[$_obf_7w__];
					$_obf_VITpWw__ = min($_obf_7w__, $_obf_XA__);
					$p = 0;
					$_obf_5w__ = 0;

					for (; $_obf_5w__ < $_obf_VITpWw__; $_obf_5w__++) {
						$p += $_obf_8yP_z2i0[$_obf_5w__] * $_obf_SsAEGdhX[$_obf_5w__];
					}

					$_obf_8yP_z2i0[$_obf_XA__] = $_obf_SsAEGdhX[$_obf_7w__] -= $p;
				}

				$_obf_8w__ = $_obf_XA__;
				$_obf_7w__ = $_obf_XA__ + 1;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					if (abs($_obf_SsAEGdhX[$_obf_8w__]) < abs($_obf_SsAEGdhX[$_obf_7w__])) {
						$_obf_8w__ = $_obf_7w__;
					}
				}

				if ($_obf_8w__ != $_obf_XA__) {
					$_obf_5w__ = 0;

					for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
						$_obf_lw__ = $this->LU[$_obf_8w__][$_obf_5w__];
						$this->LU[$_obf_8w__][$_obf_5w__] = $this->LU[$_obf_XA__][$_obf_5w__];
						$this->LU[$_obf_XA__][$_obf_5w__] = $_obf_lw__;
					}

					$_obf_5w__ = $this->piv[$_obf_8w__];
					$this->piv[$_obf_8w__] = $this->piv[$_obf_XA__];
					$this->piv[$_obf_XA__] = $_obf_5w__;
					$this->pivsign = $this->pivsign * -1;
				}

				if (($_obf_XA__ < $this->m) && ($this->LU[$_obf_XA__][$_obf_XA__] != 0)) {
					$_obf_7w__ = $_obf_XA__ + 1;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$this->LU[$_obf_7w__][$_obf_XA__] /= $this->LU[$_obf_XA__][$_obf_XA__];
					}
				}
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function getL()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($_obf_XA__ < $_obf_7w__) {
					$_obf_ng__[$_obf_7w__][$_obf_XA__] = $this->LU[$_obf_7w__][$_obf_XA__];
				}
				else if ($_obf_7w__ == $_obf_XA__) {
					$_obf_ng__[$_obf_7w__][$_obf_XA__] = 1;
				}
				else {
					$_obf_ng__[$_obf_7w__][$_obf_XA__] = 0;
				}
			}
		}

		return new Matrix($_obf_ng__);
	}

	public function getU()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($_obf_7w__ <= $_obf_XA__) {
					$_obf_GQ__[$_obf_7w__][$_obf_XA__] = $this->LU[$_obf_7w__][$_obf_XA__];
				}
				else {
					$_obf_GQ__[$_obf_7w__][$_obf_XA__] = 0;
				}
			}
		}

		return new Matrix($_obf_GQ__);
	}

	public function getPivot()
	{
		return $this->piv;
	}

	public function getDoublePivot()
	{
		return $this->getPivot();
	}

	public function isNonsingular()
	{
		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
			if ($this->LU[$_obf_XA__][$_obf_XA__] == 0) {
				return false;
			}
		}

		return true;
	}

	public function det()
	{
		if ($this->m == $this->n) {
			$_obf_5g__ = $this->pivsign;
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$_obf_5g__ *= $this->LU[$_obf_XA__][$_obf_XA__];
			}

			return $_obf_5g__;
		}
		else {
			trigger_error(MatrixDimensionException, ERROR);
		}
	}

	public function solve($B)
	{
		if ($B->getRowDimension() == $this->m) {
			if ($this->isNonsingular()) {
				$_obf__fE_ = $B->getColumnDimension();
				$Z = $B->getMatrix($this->piv, 0, $_obf__fE_ - 1);
				$_obf_5w__ = 0;

				for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
					$_obf_7w__ = $_obf_5w__ + 1;

					for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
							$Z->A[$_obf_7w__][$_obf_XA__] -= $Z->A[$_obf_5w__][$_obf_XA__] * $this->LU[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				$_obf_5w__ = $this->n - 1;

				for (; 0 <= $_obf_5w__; $_obf_5w__--) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
						$Z->A[$_obf_5w__][$_obf_XA__] /= $this->LU[$_obf_5w__][$_obf_5w__];
					}

					$_obf_7w__ = 0;

					for (; $_obf_7w__ < $_obf_5w__; $_obf_7w__++) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
							$Z->A[$_obf_7w__][$_obf_XA__] -= $Z->A[$_obf_5w__][$_obf_XA__] * $this->LU[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				return $Z;
			}
			else {
				trigger_error(MatrixSingularException, ERROR);
			}
		}
		else {
			trigger_error(MatrixSquareException, ERROR);
		}
	}
}


?>
