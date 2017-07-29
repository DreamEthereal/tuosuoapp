<?php
//dezend by http://www.yunlu99.com/
class Matrix
{
	/**
  * Matrix storage
  * @var array
  * @access private
  */
	public $A = array();
	/**
  * Matrix row dimension
  * @var int
  * @access private
  */
	public $m;
	/**
  * Matrix column dimension
  * @var int
  * @access private
  */
	public $n;

	public function Matrix()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'integer':
				$this->m = $_obf_yCTIeg__[0];
				$this->n = $_obf_yCTIeg__[0];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'integer,integer':
				$this->m = $_obf_yCTIeg__[0];
				$this->n = $_obf_yCTIeg__[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, 0));
				break;

			case 'integer,integer,integer':
				$this->m = $_obf_yCTIeg__[0];
				$this->n = $_obf_yCTIeg__[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, $_obf_yCTIeg__[2]));
				break;

			case 'integer,integer,double':
				$this->m = $_obf_yCTIeg__[0];
				$this->n = $_obf_yCTIeg__[1];
				$this->A = array_fill(0, $this->m, array_fill(0, $this->n, $_obf_yCTIeg__[2]));
				break;

			case 'array':
				$this->m = count($_obf_yCTIeg__[0]);
				$this->n = count($_obf_yCTIeg__[0][0]);
				$this->A = $_obf_yCTIeg__[0];
				break;

			case 'array,integer,integer':
				$this->m = $_obf_yCTIeg__[1];
				$this->n = $_obf_yCTIeg__[2];
				$this->A = $_obf_yCTIeg__[0];
				break;

			case 'array,integer':
				$this->m = $_obf_yCTIeg__[1];

				if ($this->m != 0) {
					$this->n = count($_obf_yCTIeg__[0]) / $this->m;
				}
				else {
					$this->n = 0;
				}

				if (($this->m * $this->n) == count($_obf_yCTIeg__[0])) {
					$_obf_7w__ = 0;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
							$this->A[$_obf_7w__][$_obf_XA__] = $_obf_yCTIeg__[0][$_obf_7w__ + ($_obf_XA__ * $this->m)];
						}
					}
				}
				else {
					trigger_error(ArrayLengthException, ERROR);
				}

				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function getArray()
	{
		return $this->A;
	}

	public function getArrayCopy()
	{
		return $this->A;
	}

	public function constructWithCopy($A)
	{
		$this->m = count($A);
		$this->n = count($A[0]);
		$Z = new Matrix($this->m, $this->n);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			if (count($A[$_obf_7w__]) != $this->n) {
				trigger_error(RowLengthException, ERROR);
			}

			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$Z->A[$_obf_7w__][$_obf_XA__] = $A[$_obf_7w__][$_obf_XA__];
			}
		}

		return $Z;
	}

	public function getColumnPackedCopy()
	{
		$_obf_FA__ = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				array_push($_obf_FA__, $this->A[$_obf_XA__][$_obf_7w__]);
			}
		}

		return $_obf_FA__;
	}

	public function getRowPackedCopy()
	{
		$_obf_FA__ = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				array_push($_obf_FA__, $this->A[$_obf_7w__][$_obf_XA__]);
			}
		}

		return $_obf_FA__;
	}

	public function getRowDimension()
	{
		return $this->m;
	}

	public function getColumnDimension()
	{
		return $this->n;
	}

	public function get($i = NULL, $j = NULL)
	{
		return $this->A[$i][$j];
	}

	public function getMatrix()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'integer,integer':
				list($_obf_wNI_, $_obf_ffs_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (0 <= $_obf_wNI_ ? $this->m - $_obf_wNI_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (0 <= $_obf_ffs_ ? $this->n - $_obf_ffs_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__, $_obf_FQ__);
				$_obf_7w__ = $_obf_wNI_;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = $_obf_ffs_;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__, $_obf_XA__, $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_sw__;
				break;

			case 'integer,integer,integer,integer':
				list($_obf_wNI_, $_obf_SRg_, $_obf_ffs_, $_obf_5ig_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (($_obf_wNI_ < $_obf_SRg_) && ($_obf_SRg_ <= $this->m) && (0 <= $_obf_wNI_) ? $_obf_SRg_ - $_obf_wNI_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (($_obf_ffs_ < $_obf_5ig_) && ($_obf_5ig_ <= $this->n) && (0 <= $_obf_ffs_) ? $_obf_5ig_ - $_obf_ffs_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__ + 1, $_obf_FQ__ + 1);
				$_obf_7w__ = $_obf_wNI_;

				for (; $_obf_7w__ <= $_obf_SRg_; $_obf_7w__++) {
					$_obf_XA__ = $_obf_ffs_;

					for (; $_obf_XA__ <= $_obf_5ig_; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__ - $_obf_wNI_, $_obf_XA__ - $_obf_ffs_, $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_sw__;
				break;

			case 'array,array':
				list($_obf_LQU_, $_obf_GIk_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (0 < count($_obf_LQU_) ? count($_obf_LQU_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (0 < count($_obf_GIk_) ? count($_obf_GIk_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__, $_obf_FQ__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $_obf_Ag__; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_FQ__; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__ - $_obf_wNI_, $_obf_XA__ - $_obf_ffs_, $this->A[$_obf_LQU_[$_obf_7w__]][$_obf_GIk_[$_obf_XA__]]);
					}
				}

				return $_obf_sw__;
				break;

			case 'array,array':
				list($_obf_LQU_, $_obf_GIk_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (0 < count($_obf_LQU_) ? count($_obf_LQU_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (0 < count($_obf_GIk_) ? count($_obf_GIk_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__, $_obf_FQ__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $_obf_Ag__; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_FQ__; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__, $_obf_XA__, $this->A[$_obf_LQU_[$_obf_7w__]][$_obf_GIk_[$_obf_XA__]]);
					}
				}

				return $_obf_sw__;
				break;

			case 'integer,integer,array':
				list($_obf_wNI_, $_obf_SRg_, $_obf_GIk_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (($_obf_wNI_ < $_obf_SRg_) && ($_obf_SRg_ <= $this->m) && (0 <= $_obf_wNI_) ? $_obf_SRg_ - $_obf_wNI_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (0 < count($_obf_GIk_) ? count($_obf_GIk_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__, $_obf_FQ__);
				$_obf_7w__ = $_obf_wNI_;

				for (; $_obf_7w__ < $_obf_SRg_; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_FQ__; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__ - $_obf_wNI_, $_obf_XA__, $this->A[$_obf_LQU_[$_obf_7w__]][$_obf_XA__]);
					}
				}

				return $_obf_sw__;
				break;

			case 'array,integer,integer':
				list($_obf_LQU_, $_obf_ffs_, $_obf_5ig_) = $_obf_yCTIeg__;
				$_obf_Ag__ = (0 < count($_obf_LQU_) ? count($_obf_LQU_) : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_FQ__ = (($_obf_ffs_ <= $_obf_5ig_) && ($_obf_5ig_ <= $this->n) && (0 <= $_obf_ffs_) ? $_obf_5ig_ - $_obf_ffs_ : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_sw__ = new Matrix($_obf_Ag__, $_obf_FQ__ + 1);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $_obf_Ag__; $_obf_7w__++) {
					$_obf_XA__ = $_obf_ffs_;

					for (; $_obf_XA__ <= $_obf_5ig_; $_obf_XA__++) {
						$_obf_sw__->set($_obf_7w__, $_obf_XA__ - $_obf_ffs_, $this->A[$_obf_LQU_[$_obf_7w__]][$_obf_XA__]);
					}
				}

				return $_obf_sw__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function setMatrix()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'integer,integer,object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[2], 'Matrix') ? $_obf_yCTIeg__[2] : trigger_error(ArgumentTypeException, ERROR));
				$_obf_wNI_ = (($_obf_yCTIeg__[0] + $_obf_JA__->m) <= $this->m ? $_obf_yCTIeg__[0] : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_ffs_ = (($_obf_yCTIeg__[1] + $_obf_JA__->n) <= $this->n ? $_obf_yCTIeg__[1] : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_7w__ = $_obf_wNI_;

				for (; $_obf_7w__ < ($_obf_wNI_ + $_obf_JA__->m); $_obf_7w__++) {
					$_obf_XA__ = $_obf_ffs_;

					for (; $_obf_XA__ < ($_obf_ffs_ + $_obf_JA__->n); $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $_obf_JA__->get($_obf_7w__ - $_obf_wNI_, $_obf_XA__ - $_obf_ffs_);
					}
				}

				break;

			case 'integer,integer,array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[2]);
				$_obf_wNI_ = (($_obf_yCTIeg__[0] + $_obf_JA__->m) <= $this->m ? $_obf_yCTIeg__[0] : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_ffs_ = (($_obf_yCTIeg__[1] + $_obf_JA__->n) <= $this->n ? $_obf_yCTIeg__[1] : trigger_error(ArgumentBoundsException, ERROR));
				$_obf_7w__ = $_obf_wNI_;

				for (; $_obf_7w__ < ($_obf_wNI_ + $_obf_JA__->m); $_obf_7w__++) {
					$_obf_XA__ = $_obf_ffs_;

					for (; $_obf_XA__ < ($_obf_ffs_ + $_obf_JA__->n); $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $_obf_JA__->get($_obf_7w__ - $_obf_wNI_, $_obf_XA__ - $_obf_ffs_);
					}
				}

				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function checkMatrixDimensions($B = NULL)
	{
		if (is_a($B, 'Matrix')) {
			if (($this->m == $B->m) && ($this->n == $B->n)) {
				return true;
			}
			else {
				trigger_error(MatrixDimensionException, ERROR);
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function set($i = NULL, $j = NULL, $c = NULL)
	{
		$this->A[$i][$j] = $c;
	}

	public function identity($m = NULL, $n = NULL)
	{
		return Matrix::diagonal($m, $n, 1);
	}

	public function diagonal($m = NULL, $n = NULL, $c = 1)
	{
		$_obf_sw__ = new Matrix($m, $n);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $m; $_obf_7w__++) {
			$_obf_sw__->set($_obf_7w__, $_obf_7w__, $c);
		}

		return $_obf_sw__;
	}

	public function filled($m = NULL, $n = NULL, $c = 0)
	{
		if (is_int($m) && is_int($n) && is_numeric($c)) {
			$_obf_sw__ = new Matrix($m, $n, $c);
			return $_obf_sw__;
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function random($m = NULL, $n = NULL, $a = RAND_MIN, $b = RAND_MAX)
	{
		if (is_int($m) && is_int($n) && is_numeric($a) && is_numeric($b)) {
			$_obf_sw__ = new Matrix($m, $n);
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $m; $_obf_7w__++) {
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $n; $_obf_XA__++) {
					$_obf_sw__->set($_obf_7w__, $_obf_XA__, mt_rand($a, $b));
				}
			}

			return $_obf_sw__;
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function packed()
	{
		return $this->getRowPacked();
	}

	public function getMatrixByRow($i0 = NULL, $iF = NULL)
	{
		if (is_int($i0)) {
			if (is_int($iF)) {
				return $this->getMatrix($i0, 0, $iF + 1, $this->n);
			}
			else {
				return $this->getMatrix($i0, 0, $i0 + 1, $this->n);
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function getMatrixByCol($j0 = NULL, $jF = NULL)
	{
		if (is_int($j0)) {
			if (is_int($jF)) {
				return $this->getMatrix(0, $j0, $this->m, $jF + 1);
			}
			else {
				return $this->getMatrix(0, $j0, $this->m, $j0 + 1);
			}
		}
		else {
			trigger_error(ArgumentTypeException, ERROR);
		}
	}

	public function transpose()
	{
		$_obf_sw__ = new Matrix($this->n, $this->m);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$_obf_sw__->set($_obf_XA__, $_obf_7w__, $this->A[$_obf_7w__][$_obf_XA__]);
			}
		}

		return $_obf_sw__;
	}

	public function norm1()
	{
		$_obf_OQ__ = 0;
		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
			$p = 0;
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
				$p += abs($this->A[$_obf_7w__][$_obf_XA__]);
			}

			$_obf_OQ__ = ($p < $_obf_OQ__ ? $_obf_OQ__ : $p);
		}

		return $_obf_OQ__;
	}

	public function norm2()
	{
	}

	public function normInf()
	{
		$_obf_OQ__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$p = 0;
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$p += abs($this->A[$_obf_7w__][$_obf_XA__]);
			}

			$_obf_OQ__ = ($p < $_obf_OQ__ ? $_obf_OQ__ : $p);
		}

		return $_obf_OQ__;
	}

	public function normF()
	{
		$_obf_6Q__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$_obf_6Q__ = hypo($_obf_6Q__, $this->A[$_obf_7w__][$_obf_XA__]);
			}
		}

		return $_obf_6Q__;
	}

	public function rank()
	{
		$_obf_Q95_ = new SingularValueDecomposition($this);
		return $_obf_Q95_->rank();
	}

	public function cond()
	{
		$_obf_Q95_ = new SingularValueDecomposition($this);
		return $_obf_Q95_->cond();
	}

	public function trace()
	{
		$p = 0;
		$_obf_FQ__ = min($this->m, $this->n);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_FQ__; $_obf_7w__++) {
			$p += $this->A[$_obf_7w__][$_obf_7w__];
		}

		return $p;
	}

	public function uminus()
	{
	}

	public function plus()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) + $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) + $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function plusEquals()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] += $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] += $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function minus()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) - $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) - $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function minusEquals()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] -= $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] -= $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayTimes()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) * $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) * $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayTimesEquals()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] *= $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] *= $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $this;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayRightDivide()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $this->A[$_obf_7w__][$_obf_XA__] / $_obf_JA__->get($_obf_7w__, $_obf_XA__));
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $this->A[$_obf_7w__][$_obf_XA__] / $_obf_JA__->get($_obf_7w__, $_obf_XA__));
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayRightDivideEquals()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $this->A[$_obf_7w__][$_obf_XA__] / $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $this->A[$_obf_7w__][$_obf_XA__] / $_obf_JA__->get($_obf_7w__, $_obf_XA__);
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayLeftDivide()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) / $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_JA__->set($_obf_7w__, $_obf_XA__, $_obf_JA__->get($_obf_7w__, $_obf_XA__) / $this->A[$_obf_7w__][$_obf_XA__]);
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function arrayLeftDivideEquals()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_JA__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $_obf_JA__->get($_obf_7w__, $_obf_XA__) / $this->A[$_obf_7w__][$_obf_XA__];
					}
				}

				return $_obf_JA__;
				break;

			case 'array':
				$_obf_JA__ = new Matrix($_obf_yCTIeg__[0]);
				$this->checkMatrixDimensions($_obf_JA__);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$this->A[$_obf_7w__][$_obf_XA__] = $_obf_JA__->get($_obf_7w__, $_obf_XA__) / $this->A[$_obf_7w__][$_obf_XA__];
					}
				}

				return $_obf_JA__;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function times()
	{
		if (0 < func_num_args()) {
			$_obf_yCTIeg__ = func_get_args();
			$_obf_jq3moiI_ = implode(',', array_map('gettype', $_obf_yCTIeg__));

			switch ($_obf_jq3moiI_) {
			case 'object':
				$_obf_3w__ = (is_a($_obf_yCTIeg__[0], 'Matrix') ? $_obf_yCTIeg__[0] : trigger_error(ArgumentTypeException, ERROR));

				if ($this->n == $_obf_3w__->m) {
					$N = new Matrix($this->m, $_obf_3w__->n);
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $_obf_3w__->n; $_obf_XA__++) {
						$_obf_5w__ = 0;

						for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
							$_obf_J_uxrow_[$_obf_5w__] = $_obf_3w__->A[$_obf_5w__][$_obf_XA__];
						}

						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_LAQoNeQ_ = $this->A[$_obf_7w__];
							$p = 0;
							$_obf_5w__ = 0;

							for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
								$p += $_obf_LAQoNeQ_[$_obf_5w__] * $_obf_J_uxrow_[$_obf_5w__];
							}

							$N->A[$_obf_7w__][$_obf_XA__] = $p;
						}
					}

					return $N;
				}
				else {
					trigger_error(MatrixDimensionMismatch, FATAL);
				}

				break;

			case 'array':
				$_obf_3w__ = new Matrix($_obf_yCTIeg__[0]);

				if ($this->n == $_obf_3w__->m) {
					$N = new Matrix($this->m, $_obf_3w__->n);
					$_obf_7w__ = 0;

					for (; $_obf_7w__ < $N->m; $_obf_7w__++) {
						$_obf_XA__ = 0;

						for (; $_obf_XA__ < $N->n; $_obf_XA__++) {
							$p = '0';
							$_obf_5w__ = 0;

							for (; $_obf_5w__ < $N->n; $_obf_5w__++) {
								$p += $this->A[$_obf_7w__][$_obf_5w__] * $_obf_3w__->A[$_obf_5w__][$_obf_XA__];
							}

							$N->A[$_obf_7w__][$_obf_XA__] = $p;
						}
					}

					return $N;
				}
				else {
					trigger_error(MatrixDimensionMismatch, FATAL);
				}

				return $_obf_JA__;
				break;

			case 'integer':
				$N = new Matrix($this->A);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $N->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $N->n; $_obf_XA__++) {
						$N->A[$_obf_7w__][$_obf_XA__] *= $_obf_yCTIeg__[0];
					}
				}

				return $N;
				break;

			case 'double':
				$N = new Matrix($this->m, $this->n);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $N->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $N->n; $_obf_XA__++) {
						$N->A[$_obf_7w__][$_obf_XA__] = $_obf_yCTIeg__[0] * $this->A[$_obf_7w__][$_obf_XA__];
					}
				}

				return $N;
				break;

			case 'float':
				$N = new Matrix($this->A);
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $N->m; $_obf_7w__++) {
					$_obf_XA__ = 0;

					for (; $_obf_XA__ < $N->n; $_obf_XA__++) {
						$N->A[$_obf_7w__][$_obf_XA__] *= $_obf_yCTIeg__[0];
					}
				}

				return $N;
				break;

			default:
				trigger_error(PolymorphicArgumentException, ERROR);
				break;
			}
		}
		else {
			trigger_error(PolymorphicArgumentException, ERROR);
		}
	}

	public function chol()
	{
		return new CholeskyDecomposition($this);
	}

	public function lu()
	{
		return new LUDecomposition($this);
	}

	public function qr()
	{
		return new QRDecomposition($this);
	}

	public function eig()
	{
		return new EigenvalueDecomposition($this);
	}

	public function svd()
	{
		return new SingularValueDecomposition($this);
	}

	public function solve($B)
	{
		if ($this->m == $this->n) {
			$_obf_3lg_ = new LUDecomposition($this);
			return $_obf_3lg_->solve($B);
		}
		else {
			$_obf_N5E_ = new QRDecomposition($this);
			return $_obf_N5E_->solve($B);
		}
	}

	public function inverse()
	{
		return $this->solve($this->identity($this->m, $this->m));
	}

	public function det()
	{
		$_obf_ng__ = new LUDecomposition($this);
		return $_obf_ng__->det();
	}

	public function mprint($A, $format = '%01.2f', $width = 2)
	{
		$_obf_R_tWLV3YIQ__ = '';
		$_obf_Ag__ = count($A);
		$_obf_FQ__ = count($A[0]);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $width; $_obf_7w__++) {
			$_obf_R_tWLV3YIQ__ .= '&nbsp;';
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_Ag__; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $_obf_FQ__; $_obf_XA__++) {
				$_obf_sAlSKonNCEv0 = sprintf($format, $A[$_obf_7w__][$_obf_XA__]);
				echo $_obf_sAlSKonNCEv0 . $_obf_R_tWLV3YIQ__;
			}

			echo '<br />';
		}
	}

	public function toHTML($width = 2)
	{
		print('<table style="background-color:#eee;">');
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
			print('<tr>');
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				print('<td style="background-color:#fff;border:1px solid #000;padding:2px;text-align:center;vertical-align:middle;">' . $this->A[$_obf_7w__][$_obf_XA__] . '</td>');
			}

			print('</tr>');
		}

		print('</table>');
	}
}

define('RAND_MAX', mt_getrandmax());
define('RAND_MIN', 0);
require_once ROOT_PATH . 'JAMA/Error.php';
require_once ROOT_PATH . 'JAMA/Maths.php';
require_once ROOT_PATH . 'JAMA/CholeskyDecomposition.php';
require_once ROOT_PATH . 'JAMA/LUDecomposition.php';
require_once ROOT_PATH . 'JAMA/QRDecomposition.php';
require_once ROOT_PATH . 'JAMA/EigenvalueDecomposition.php';
require_once ROOT_PATH . 'JAMA/SingularValueDecomposition.php';

?>
