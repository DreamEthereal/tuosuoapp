<?php
//dezend by http://www.yunlu99.com/
class CholeskyDecomposition
{
	/**
  * Decomposition storage
  * @var array
  * @access private
  */
	public $L = array();
	/**
  * Matrix row and column dimension
  * @var int
  * @access private
  */
	public $m;
	/**
  * Symmetric positive definite flag
  * @var boolean
  * @access private
  */
	public $isspd = true;

	public function CholeskyDecomposition($A = NULL)
	{
		if (is_a($A, 'Matrix')) {
			$this->L = $A->getArray();
			$this->m = $A->getRowDimension();
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
				$_obf_XA__ = $_obf_7w__;

				for (; $_obf_XA__ < $this->m; $_obf_XA__++) {
					$_obf_bhdW = $this->L[$_obf_7w__][$_obf_XA__];
					$_obf_5w__ = $_obf_7w__ - 1;

					for (; 0 <= $_obf_5w__; $_obf_5w__--) {
						$_obf_bhdW -= $this->L[$_obf_7w__][$_obf_5w__] * $this->L[$_obf_XA__][$_obf_5w__];
					}

					if ($_obf_7w__ == $_obf_XA__) {
						if (0 <= $_obf_bhdW) {
							$this->L[$_obf_7w__][$_obf_7w__] = sqrt($_obf_bhdW);
						}
						else {
							$this->isspd = false;
						}
					}
					else if ($this->L[$_obf_7w__][$_obf_7w__] != 0) {
						$this->L[$_obf_XA__][$_obf_7w__] = $_obf_bhdW / $this->L[$_obf_7w__][$_obf_7w__];
					}
				}

				$_obf_5w__ = $_obf_7w__ + 1;

				for (; $_obf_5w__ < $this->m; $_obf_5w__++) {
					$this->L[$_obf_7w__][$_obf_5w__] = 0;
				}
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function isSPD()
	{
		return $this->isspd;
	}

	public function getL()
	{
		return new Matrix($this->L);
	}

	public function solve($B = NULL)
	{
		if (is_a($B, 'Matrix')) {
			if ($B->getRowDimension() == $this->m) {
				if ($this->isspd) {
					$Z = $B->getArrayCopy();
					$_obf__fE_ = $B->getColumnDimension();
					$_obf_5w__ = 0;

					for (; $_obf_5w__ < $this->m; $_obf_5w__++) {
						$_obf_7w__ = $_obf_5w__ + 1;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_XA__ = 0;

							for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
								$Z[$_obf_7w__][$_obf_XA__] -= $Z[$_obf_5w__][$_obf_XA__] * $this->L[$_obf_7w__][$_obf_5w__];
							}
						}

						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
							$Z[$_obf_5w__][$_obf_XA__] /= $this->L[$_obf_5w__][$_obf_5w__];
						}
					}

					$_obf_5w__ = $this->m - 1;

					for (; 0 <= $_obf_5w__; $_obf_5w__--) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
							$Z[$_obf_5w__][$_obf_XA__] /= $this->L[$_obf_5w__][$_obf_5w__];
						}

						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $_obf_5w__; $_obf_7w__++) {
							$_obf_XA__ = 0;

							for (; $_obf_XA__ < $_obf__fE_; $_obf_XA__++) {
								$Z[$_obf_7w__][$_obf_XA__] -= $Z[$_obf_5w__][$_obf_XA__] * $this->L[$_obf_5w__][$_obf_7w__];
							}
						}
					}

					return new Matrix($Z, $this->m, $_obf__fE_);
				}
				else {
					trigger_error(MatrixSPDException, ERROR);
				}
			}
			else {
				trigger_error(MatrixDimensionException, ERROR);
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}
}


?>
