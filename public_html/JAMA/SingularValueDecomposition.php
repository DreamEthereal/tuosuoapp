<?php
//dezend by http://www.yunlu99.com/
class SingularValueDecomposition
{
	/**
  * Internal storage of U.
  * @var array
  */
	public $U = array();
	/**
  * Internal storage of V.
  * @var array
  */
	public $V = array();
	/**
  * Internal storage of singular values.
  * @var array
  */
	public $s = array();
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

	public function SingularValueDecomposition($Arg)
	{
		$_obf_Pg__ = $Arg->getArrayCopy();
		$this->m = $Arg->getRowDimension();
		$this->n = $Arg->getColumnDimension();
		$_obf_bP0_ = min($this->m, $this->n);
		$_obf_hA__ = array();
		$_obf_EIZe7A__ = array();
		$_obf_1OnVhGw_ = true;
		$_obf_K6TznFk_ = true;
		$_obf_fW4V = min($this->m - 1, $this->n);
		$_obf_zf_o = max(0, min($this->n - 2, $this->m));
		$_obf_5w__ = 0;

		for (; $_obf_5w__ < max($_obf_fW4V, $_obf_zf_o); $_obf_5w__++) {
			if ($_obf_5w__ < $_obf_fW4V) {
				$this->s[$_obf_5w__] = 0;
				$_obf_7w__ = $_obf_5w__;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$this->s[$_obf_5w__] = hypo($this->s[$_obf_5w__], $_obf_Pg__[$_obf_7w__][$_obf_5w__]);
				}

				if ($this->s[$_obf_5w__] != 0) {
					if ($_obf_Pg__[$_obf_5w__][$_obf_5w__] < 0) {
						$this->s[$_obf_5w__] = 0 - $this->s[$_obf_5w__];
					}

					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_Pg__[$_obf_7w__][$_obf_5w__] /= $this->s[$_obf_5w__];
					}

					$_obf_Pg__[$_obf_5w__][$_obf_5w__] += 1;
				}

				$this->s[$_obf_5w__] = 0 - $this->s[$_obf_5w__];
			}

			$_obf_XA__ = $_obf_5w__ + 1;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if (($_obf_5w__ < $_obf_fW4V) & ($this->s[$_obf_5w__] != 0)) {
					$_obf_lw__ = 0;
					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_lw__ += $_obf_Pg__[$_obf_7w__][$_obf_5w__] * $_obf_Pg__[$_obf_7w__][$_obf_XA__];
					}

					$_obf_lw__ = (0 - $_obf_lw__) / $_obf_Pg__[$_obf_5w__][$_obf_5w__];
					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_Pg__[$_obf_7w__][$_obf_XA__] += $_obf_lw__ * $_obf_Pg__[$_obf_7w__][$_obf_5w__];
					}

					$_obf_hA__[$_obf_XA__] = $_obf_Pg__[$_obf_5w__][$_obf_XA__];
				}
			}

			if ($_obf_1OnVhGw_ && ($_obf_5w__ < $_obf_fW4V)) {
				$_obf_7w__ = $_obf_5w__;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$this->U[$_obf_7w__][$_obf_5w__] = $_obf_Pg__[$_obf_7w__][$_obf_5w__];
				}
			}

			if ($_obf_5w__ < $_obf_zf_o) {
				$_obf_hA__[$_obf_5w__] = 0;
				$_obf_7w__ = $_obf_5w__ + 1;

				for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
					$_obf_hA__[$_obf_5w__] = hypo($_obf_hA__[$_obf_5w__], $_obf_hA__[$_obf_7w__]);
				}

				if ($_obf_hA__[$_obf_5w__] != 0) {
					if ($_obf_hA__[$_obf_5w__ + 1] < 0) {
						$_obf_hA__[$_obf_5w__] = 0 - $_obf_hA__[$_obf_5w__];
					}

					$_obf_7w__ = $_obf_5w__ + 1;

					for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
						$_obf_hA__[$_obf_7w__] /= $_obf_hA__[$_obf_5w__];
					}

					$_obf_hA__[$_obf_5w__ + 1] += 1;
				}

				$_obf_hA__[$_obf_5w__] = 0 - $_obf_hA__[$_obf_5w__];
				if ((($_obf_5w__ + 1) < $this->m) && ($_obf_hA__[$_obf_5w__] != 0)) {
					$_obf_7w__ = $_obf_5w__ + 1;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$_obf_EIZe7A__[$_obf_7w__] = 0;
					}

					$_obf_XA__ = $_obf_5w__ + 1;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_7w__ = $_obf_5w__ + 1;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_EIZe7A__[$_obf_7w__] += $_obf_hA__[$_obf_XA__] * $_obf_Pg__[$_obf_7w__][$_obf_XA__];
						}
					}

					$_obf_XA__ = $_obf_5w__ + 1;

					for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
						$_obf_lw__ = (0 - $_obf_hA__[$_obf_XA__]) / $_obf_hA__[$_obf_5w__ + 1];
						$_obf_7w__ = $_obf_5w__ + 1;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_Pg__[$_obf_7w__][$_obf_XA__] += $_obf_lw__ * $_obf_EIZe7A__[$_obf_7w__];
						}
					}
				}

				if ($_obf_K6TznFk_) {
					$_obf_7w__ = $_obf_5w__ + 1;

					for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
						$this->V[$_obf_7w__][$_obf_5w__] = $_obf_hA__[$_obf_7w__];
					}
				}
			}
		}

		$_obf_8w__ = min($this->n, $this->m + 1);

		if ($_obf_fW4V < $this->n) {
			$this->s[$_obf_fW4V] = $_obf_Pg__[$_obf_fW4V][$_obf_fW4V];
		}

		if ($this->m < $_obf_8w__) {
			$this->s[$_obf_8w__ - 1] = 0;
		}

		if (($_obf_zf_o + 1) < $_obf_8w__) {
			$_obf_hA__[$_obf_zf_o] = $_obf_Pg__[$_obf_zf_o][$_obf_8w__ - 1];
		}

		$_obf_hA__[$_obf_8w__ - 1] = 0;

		if ($_obf_1OnVhGw_) {
			$_obf_XA__ = $_obf_fW4V;

			for (; $_obf_XA__ < $_obf_bP0_; $_obf_XA__++) {
				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
					$this->U[$_obf_7w__][$_obf_XA__] = 0;
				}

				$this->U[$_obf_XA__][$_obf_XA__] = 1;
			}

			$_obf_5w__ = $_obf_fW4V - 1;

			for (; 0 <= $_obf_5w__; $_obf_5w__--) {
				if ($this->s[$_obf_5w__] != 0) {
					$_obf_XA__ = $_obf_5w__ + 1;

					for (; $_obf_XA__ < $_obf_bP0_; $_obf_XA__++) {
						$_obf_lw__ = 0;
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_lw__ += $this->U[$_obf_7w__][$_obf_5w__] * $this->U[$_obf_7w__][$_obf_XA__];
						}

						$_obf_lw__ = (0 - $_obf_lw__) / $this->U[$_obf_5w__][$_obf_5w__];
						$_obf_7w__ = $_obf_5w__;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$this->U[$_obf_7w__][$_obf_XA__] += $_obf_lw__ * $this->U[$_obf_7w__][$_obf_5w__];
						}
					}

					$_obf_7w__ = $_obf_5w__;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$this->U[$_obf_7w__][$_obf_5w__] = 0 - $this->U[$_obf_7w__][$_obf_5w__];
					}

					$this->U[$_obf_5w__][$_obf_5w__] = 1 + $this->U[$_obf_5w__][$_obf_5w__];
					$_obf_7w__ = 0;

					for (; $_obf_7w__ < ($_obf_5w__ - 1); $_obf_7w__++) {
						$this->U[$_obf_7w__][$_obf_5w__] = 0;
					}
				}
				else {
					$_obf_7w__ = 0;

					for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
						$this->U[$_obf_7w__][$_obf_5w__] = 0;
					}

					$this->U[$_obf_5w__][$_obf_5w__] = 1;
				}
			}
		}

		if ($_obf_K6TznFk_) {
			$_obf_5w__ = $this->n - 1;

			for (; 0 <= $_obf_5w__; $_obf_5w__--) {
				if (($_obf_5w__ < $_obf_zf_o) && ($_obf_hA__[$_obf_5w__] != 0)) {
					$_obf_XA__ = $_obf_5w__ + 1;

					for (; $_obf_XA__ < $_obf_bP0_; $_obf_XA__++) {
						$_obf_lw__ = 0;
						$_obf_7w__ = $_obf_5w__ + 1;

						for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
							$_obf_lw__ += $this->V[$_obf_7w__][$_obf_5w__] * $this->V[$_obf_7w__][$_obf_XA__];
						}

						$_obf_lw__ = (0 - $_obf_lw__) / $this->V[$_obf_5w__ + 1][$_obf_5w__];
						$_obf_7w__ = $_obf_5w__ + 1;

						for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
							$this->V[$_obf_7w__][$_obf_XA__] += $_obf_lw__ * $this->V[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				$_obf_7w__ = 0;

				for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
					$this->V[$_obf_7w__][$_obf_5w__] = 0;
				}

				$this->V[$_obf_5w__][$_obf_5w__] = 1;
			}
		}

		$_obf_tPM_ = $_obf_8w__ - 1;
		$_obf_XkRJvA__ = 0;
		$_obf_q_45 = pow(2, -52);

		while (0 < $_obf_8w__) {
			$_obf_5w__ = $_obf_8w__ - 2;

			for (; -1 <= $_obf_5w__; $_obf_5w__--) {
				if ($_obf_5w__ == -1) {
					break;
				}

				if (abs($_obf_hA__[$_obf_5w__]) <= $_obf_q_45 * (abs($this->s[$_obf_5w__]) + abs($this->s[$_obf_5w__ + 1]))) {
					$_obf_hA__[$_obf_5w__] = 0;
					break;
				}
			}

			if ($_obf_5w__ == $_obf_8w__ - 2) {
				$_obf_fwPqwg__ = 4;
			}
			else {
				$_obf_boA_ = $_obf_8w__ - 1;

				for (; $_obf_5w__ <= $_obf_boA_; $_obf_boA_--) {
					if ($_obf_boA_ == $_obf_5w__) {
						break;
					}

					$_obf_lw__ = ($_obf_boA_ != $_obf_8w__ ? abs($_obf_hA__[$_obf_boA_]) : 0) + ($_obf_boA_ != $_obf_5w__ + 1 ? abs($_obf_hA__[$_obf_boA_ - 1]) : 0);

					if (abs($this->s[$_obf_boA_]) <= $_obf_q_45 * $_obf_lw__) {
						$this->s[$_obf_boA_] = 0;
						break;
					}
				}

				if ($_obf_boA_ == $_obf_5w__) {
					$_obf_fwPqwg__ = 3;
				}
				else if ($_obf_boA_ == $_obf_8w__ - 1) {
					$_obf_fwPqwg__ = 1;
				}
				else {
					$_obf_fwPqwg__ = 2;
					$_obf_5w__ = $_obf_boA_;
				}
			}

			$_obf_5w__++;

			switch ($_obf_fwPqwg__) {
			case 1:
				$_obf_6Q__ = $_obf_hA__[$_obf_8w__ - 2];
				$_obf_hA__[$_obf_8w__ - 2] = 0;
				$_obf_XA__ = $_obf_8w__ - 2;

				for (; $_obf_5w__ <= $_obf_XA__; $_obf_XA__--) {
					$_obf_lw__ = hypo($this->s[$_obf_XA__], $_obf_6Q__);
					$_obf_9r8_ = $this->s[$_obf_XA__] / $_obf_lw__;
					$_obf_3NA_ = $_obf_6Q__ / $_obf_lw__;
					$this->s[$_obf_XA__] = $_obf_lw__;

					if ($_obf_XA__ != $_obf_5w__) {
						$_obf_6Q__ = (0 - $_obf_3NA_) * $_obf_hA__[$_obf_XA__ - 1];
						$_obf_hA__[$_obf_XA__ - 1] = $_obf_9r8_ * $_obf_hA__[$_obf_XA__ - 1];
					}

					if ($_obf_K6TznFk_) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
							$_obf_lw__ = ($_obf_9r8_ * $this->V[$_obf_7w__][$_obf_XA__]) + ($_obf_3NA_ * $this->V[$_obf_7w__][$_obf_8w__ - 1]);
							$this->V[$_obf_7w__][$_obf_8w__ - 1] = ((0 - $_obf_3NA_) * $this->V[$_obf_7w__][$_obf_XA__]) + ($_obf_9r8_ * $this->V[$_obf_7w__][$_obf_8w__ - 1]);
							$this->V[$_obf_7w__][$_obf_XA__] = $_obf_lw__;
						}
					}
				}

				break;

			case 2:
				$_obf_6Q__ = $_obf_hA__[$_obf_5w__ - 1];
				$_obf_hA__[$_obf_5w__ - 1] = 0;
				$_obf_XA__ = $_obf_5w__;

				for (; $_obf_XA__ < $_obf_8w__; $_obf_XA__++) {
					$_obf_lw__ = hypo($this->s[$_obf_XA__], $_obf_6Q__);
					$_obf_9r8_ = $this->s[$_obf_XA__] / $_obf_lw__;
					$_obf_3NA_ = $_obf_6Q__ / $_obf_lw__;
					$this->s[$_obf_XA__] = $_obf_lw__;
					$_obf_6Q__ = (0 - $_obf_3NA_) * $_obf_hA__[$_obf_XA__];
					$_obf_hA__[$_obf_XA__] = $_obf_9r8_ * $_obf_hA__[$_obf_XA__];

					if ($_obf_1OnVhGw_) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_lw__ = ($_obf_9r8_ * $this->U[$_obf_7w__][$_obf_XA__]) + ($_obf_3NA_ * $this->U[$_obf_7w__][$_obf_5w__ - 1]);
							$this->U[$_obf_7w__][$_obf_5w__ - 1] = ((0 - $_obf_3NA_) * $this->U[$_obf_7w__][$_obf_XA__]) + ($_obf_9r8_ * $this->U[$_obf_7w__][$_obf_5w__ - 1]);
							$this->U[$_obf_7w__][$_obf_XA__] = $_obf_lw__;
						}
					}
				}

				break;

			case 3:
				$_obf_f9cbhws_ = max(max(max(max(abs($this->s[$_obf_8w__ - 1]), abs($this->s[$_obf_8w__ - 2])), abs($_obf_hA__[$_obf_8w__ - 2])), abs($this->s[$_obf_5w__])), abs($_obf_hA__[$_obf_5w__]));
				$_obf_aiI_ = $this->s[$_obf_8w__ - 1] / $_obf_f9cbhws_;
				$_obf_uO5R9w__ = $this->s[$_obf_8w__ - 2] / $_obf_f9cbhws_;
				$_obf_O0p_qA__ = $_obf_hA__[$_obf_8w__ - 2] / $_obf_f9cbhws_;
				$_obf_Mr0_ = $this->s[$_obf_5w__] / $_obf_f9cbhws_;
				$_obf_R_8_ = $_obf_hA__[$_obf_5w__] / $_obf_f9cbhws_;
				$_obf_8A__ = ((($_obf_uO5R9w__ + $_obf_aiI_) * ($_obf_uO5R9w__ - $_obf_aiI_)) + ($_obf_O0p_qA__ * $_obf_O0p_qA__)) / 2;
				$_obf_KQ__ = $_obf_aiI_ * $_obf_O0p_qA__ * $_obf_aiI_ * $_obf_O0p_qA__;
				$_obf_Wd4mKJY_ = 0;
				if (($_obf_8A__ != 0) || ($_obf_KQ__ != 0)) {
					$_obf_Wd4mKJY_ = sqrt(($_obf_8A__ * $_obf_8A__) + $_obf_KQ__);

					if ($_obf_8A__ < 0) {
						$_obf_Wd4mKJY_ = 0 - $_obf_Wd4mKJY_;
					}

					$_obf_Wd4mKJY_ = $_obf_KQ__ / ($_obf_8A__ + $_obf_Wd4mKJY_);
				}

				$_obf_6Q__ = (($_obf_Mr0_ + $_obf_aiI_) * ($_obf_Mr0_ - $_obf_aiI_)) + $_obf_Wd4mKJY_;
				$_obf_1Q__ = $_obf_Mr0_ * $_obf_R_8_;
				$_obf_XA__ = $_obf_5w__;

				for (; $_obf_XA__ < ($_obf_8w__ - 1); $_obf_XA__++) {
					$_obf_lw__ = hypo($_obf_6Q__, $_obf_1Q__);
					$_obf_9r8_ = $_obf_6Q__ / $_obf_lw__;
					$_obf_3NA_ = $_obf_1Q__ / $_obf_lw__;

					if ($_obf_XA__ != $_obf_5w__) {
						$_obf_hA__[$_obf_XA__ - 1] = $_obf_lw__;
					}

					$_obf_6Q__ = ($_obf_9r8_ * $this->s[$_obf_XA__]) + ($_obf_3NA_ * $_obf_hA__[$_obf_XA__]);
					$_obf_hA__[$_obf_XA__] = ($_obf_9r8_ * $_obf_hA__[$_obf_XA__]) - ($_obf_3NA_ * $this->s[$_obf_XA__]);
					$_obf_1Q__ = $_obf_3NA_ * $this->s[$_obf_XA__ + 1];
					$this->s[$_obf_XA__ + 1] = $_obf_9r8_ * $this->s[$_obf_XA__ + 1];

					if ($_obf_K6TznFk_) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
							$_obf_lw__ = ($_obf_9r8_ * $this->V[$_obf_7w__][$_obf_XA__]) + ($_obf_3NA_ * $this->V[$_obf_7w__][$_obf_XA__ + 1]);
							$this->V[$_obf_7w__][$_obf_XA__ + 1] = ((0 - $_obf_3NA_) * $this->V[$_obf_7w__][$_obf_XA__]) + ($_obf_9r8_ * $this->V[$_obf_7w__][$_obf_XA__ + 1]);
							$this->V[$_obf_7w__][$_obf_XA__] = $_obf_lw__;
						}
					}

					$_obf_lw__ = hypo($_obf_6Q__, $_obf_1Q__);
					$_obf_9r8_ = $_obf_6Q__ / $_obf_lw__;
					$_obf_3NA_ = $_obf_1Q__ / $_obf_lw__;
					$this->s[$_obf_XA__] = $_obf_lw__;
					$_obf_6Q__ = ($_obf_9r8_ * $_obf_hA__[$_obf_XA__]) + ($_obf_3NA_ * $this->s[$_obf_XA__ + 1]);
					$this->s[$_obf_XA__ + 1] = ((0 - $_obf_3NA_) * $_obf_hA__[$_obf_XA__]) + ($_obf_9r8_ * $this->s[$_obf_XA__ + 1]);
					$_obf_1Q__ = $_obf_3NA_ * $_obf_hA__[$_obf_XA__ + 1];
					$_obf_hA__[$_obf_XA__ + 1] = $_obf_9r8_ * $_obf_hA__[$_obf_XA__ + 1];
					if ($_obf_1OnVhGw_ && ($_obf_XA__ < ($this->m - 1))) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_lw__ = ($_obf_9r8_ * $this->U[$_obf_7w__][$_obf_XA__]) + ($_obf_3NA_ * $this->U[$_obf_7w__][$_obf_XA__ + 1]);
							$this->U[$_obf_7w__][$_obf_XA__ + 1] = ((0 - $_obf_3NA_) * $this->U[$_obf_7w__][$_obf_XA__]) + ($_obf_9r8_ * $this->U[$_obf_7w__][$_obf_XA__ + 1]);
							$this->U[$_obf_7w__][$_obf_XA__] = $_obf_lw__;
						}
					}
				}

				$_obf_hA__[$_obf_8w__ - 2] = $_obf_6Q__;
				$_obf_XkRJvA__ = $_obf_XkRJvA__ + 1;
				break;

			case 4:
				if ($this->s[$_obf_5w__] <= 0) {
					$this->s[$_obf_5w__] = $this->s[$_obf_5w__] < 0 ? 0 - $this->s[$_obf_5w__] : 0;

					if ($_obf_K6TznFk_) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ <= $_obf_tPM_; $_obf_7w__++) {
							$this->V[$_obf_7w__][$_obf_5w__] = 0 - $this->V[$_obf_7w__][$_obf_5w__];
						}
					}
				}

				while ($_obf_5w__ < $_obf_tPM_) {
					if ($this->s[$_obf_5w__ + 1] <= $this->s[$_obf_5w__]) {
						break;
					}

					$_obf_lw__ = $this->s[$_obf_5w__];
					$this->s[$_obf_5w__] = $this->s[$_obf_5w__ + 1];
					$this->s[$_obf_5w__ + 1] = $_obf_lw__;
					if ($_obf_K6TznFk_ && ($_obf_5w__ < ($this->n - 1))) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
							$_obf_lw__ = $this->V[$_obf_7w__][$_obf_5w__ + 1];
							$this->V[$_obf_7w__][$_obf_5w__ + 1] = $this->V[$_obf_7w__][$_obf_5w__];
							$this->V[$_obf_7w__][$_obf_5w__] = $_obf_lw__;
						}
					}

					if ($_obf_1OnVhGw_ && ($_obf_5w__ < ($this->m - 1))) {
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < $this->m; $_obf_7w__++) {
							$_obf_lw__ = $this->U[$_obf_7w__][$_obf_5w__ + 1];
							$this->U[$_obf_7w__][$_obf_5w__ + 1] = $this->U[$_obf_7w__][$_obf_5w__];
							$this->U[$_obf_7w__][$_obf_5w__] = $_obf_lw__;
						}
					}

					$_obf_5w__++;
				}

				$_obf_XkRJvA__ = 0;
				$_obf_8w__--;
				break;
			}
		}
	}

	public function getU()
	{
		return new Matrix($this->U, $this->m, min($this->m + 1, $this->n));
	}

	public function getV()
	{
		return new Matrix($this->V);
	}

	public function getSingularValues()
	{
		return $this->s;
	}

	public function getS()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$_obf_Dg__[$_obf_7w__][$_obf_XA__] = 0;
			}

			$_obf_Dg__[$_obf_7w__][$_obf_7w__] = $this->s[$_obf_7w__];
		}

		return new Matrix($_obf_Dg__);
	}

	public function norm2()
	{
		return $this->s[0];
	}

	public function cond()
	{
		return $this->s[0] / $this->s[min($this->m, $this->n) - 1];
	}

	public function rank()
	{
		$_obf_q_45 = pow(2, -52);
		$_obf_rRwr = max($this->m, $this->n) * $this->s[0] * $_obf_q_45;
		$_obf_OQ__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < count($this->s); $_obf_7w__++) {
			if ($_obf_rRwr < $this->s[$_obf_7w__]) {
				$_obf_OQ__++;
			}
		}

		return $_obf_OQ__;
	}
}

echo ' ' . "\n" . '';

?>
