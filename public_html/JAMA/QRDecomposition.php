<?php
//dezend by http://www.yunlu99.com/
class QRDecomposition
{
	/**
  * Array for internal storage of decomposition.
  * @var array
  */
	public $QR = array();
	/**
  * Row dimension.
  * @var integer
  */
	public $m;
	/**
  * Column dimension.
  * @var integer
  */
	public $n;
	/**
  * Array for internal storage of diagonal of R.
  * @var  array
  */
	public $Rdiag = array();

	public function QRDecomposition($A)
	{
		if (is_a($A, 'Matrix')) {
			$this->QR = $A->getArrayCopy();
			$this->m = $A->getRowDimension();
			$this->n = $A->getColumnDimension();
			$_obf_5w__ = 0;

			for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
				$_obf_nERb = 0;
				$_obf_7w__ = $_obf_5w__;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_nERb = hypo($_obf_nERb, $this->QR[$_obf_7w__][$_obf_5w__]);
				}

				if ($_obf_nERb != 0) {
					if ($this->QR[$_obf_5w__][$_obf_5w__] < 0) {
						$_obf_nERb = 0 - $_obf_nERb;
					}

					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$this->QR[$_obf_7w__][$_obf_5w__] /= $_obf_nERb;
					}

					$this->QR[$_obf_5w__][$_obf_5w__] += 1;
					$_obf_XA__ = $_obf_5w__ + 1;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$p = 0;
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$p += $this->QR[$_obf_7w__][$_obf_5w__] * $this->QR[$_obf_7w__][$_obf_XA__];
						}

						$p = (0 - $p) / $this->QR[$_obf_5w__][$_obf_5w__];
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$this->QR[$_obf_7w__][$_obf_XA__] += $p * $this->QR[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				$this->Rdiag[$_obf_5w__] = 0 - $_obf_nERb;
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function isFullRank()
	{
		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
			if ($this->Rdiag[$_obf_XA__] == 0) {
				return false;
			}
		}

		return true;
	}

	public function getH()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($_obf_XA__ <= $_obf_7w__) {
					$_obf_iQ__[$_obf_7w__][$_obf_XA__] = $this->QR[$_obf_7w__][$_obf_XA__];
				}
				else {
					$_obf_iQ__[$_obf_7w__][$_obf_XA__] = 0;
				}
			}
		}

		return new Matrix($_obf_iQ__);
	}

	public function getR()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($_obf_7w__ < $_obf_XA__) {
					$_obf_sw__[$_obf_7w__][$_obf_XA__] = $this->QR[$_obf_7w__][$_obf_XA__];
				}
				else if ($_obf_7w__ == $_obf_XA__) {
					$_obf_sw__[$_obf_7w__][$_obf_XA__] = $this->Rdiag[$_obf_7w__];
				}
				else {
					$_obf_sw__[$_obf_7w__][$_obf_XA__] = 0;
				}
			}
		}

		return new Matrix($_obf_sw__);
	}

	public function getQ()
	{
		$_obf_5w__ = $this->n - 1;

		for (; 0 <= $_obf_5w__; $_obf_5w__--) {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
				$_obf_oQ__[$_obf_7w__][$_obf_5w__] = 0;
			}

			$_obf_oQ__[$_obf_5w__][$_obf_5w__] = 1;
			$_obf_XA__ = $_obf_5w__;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($this->QR[$_obf_5w__][$_obf_5w__] != 0) {
					$p = 0;
					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$p += $this->QR[$_obf_7w__][$_obf_5w__] * $_obf_oQ__[$_obf_7w__][$_obf_XA__];
					}

					$p = (0 - $p) / $this->QR[$_obf_5w__][$_obf_5w__];
					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_oQ__[$_obf_7w__][$_obf_XA__] += $p * $this->QR[$_obf_7w__][$_obf_5w__];
					}
				}
			}
		}

		return new Matrix($_obf_oQ__);
	}

	public function solve($B)
	{
		if ($B->getRowDimension() == $this->m) {
			if ($this->isFullRank()) {
				$_obf__fE_ = $B->getColumnDimension();
				$Z = $B->getArrayCopy();
				$_obf_5w__ = 0;

				for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
						$p = 0;
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$p += $this->QR[$_obf_7w__][$_obf_5w__] * $Z[$_obf_7w__][$_obf_XA__];
						}

						$p = (0 - $p) / $this->QR[$_obf_5w__][$_obf_5w__];
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$Z[$_obf_7w__][$_obf_XA__] += $p * $this->QR[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				$_obf_5w__ = $this->n - 1;

				for (; 0 <= $_obf_5w__; $_obf_5w__--) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
						$Z[$_obf_5w__][$_obf_XA__] /= $this->Rdiag[$_obf_5w__];
					}

					$_obf_7w__ = 0;

					for (; $_obf_7w__ < $_obf_5w__; $_obf_7w__++) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
							$Z[$_obf_7w__][$_obf_XA__] -= $Z[$_obf_5w__][$_obf_XA__] * $this->QR[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				$Z = new Matrix($Z);
				return $Z->getMatrix(0, $this->n - 1, 0, $_obf__fE_);
			}
			else {
				trigger_error(MatrixRankException, ERROR);
			}
		}
		else {
			trigger_error(MatrixDimensionException, ERROR);
		}
	}
}

echo ' ' . "\n" . '';

?>
