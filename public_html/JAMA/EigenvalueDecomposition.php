<?php
//dezend by http://www.yunlu99.com/
class EigenvalueDecomposition
{
	/**
  * Row and column dimension (square matrix).
  * @var int
  */
	public $n;
	/**
  * Internal symmetry flag.

  * @var int
  */
	public $issymmetric;
	/**
  * Arrays for internal storage of eigenvalues.
  * @var array
  */
	public $d = array();
	public $e = array();
	/**
  * Array for internal storage of eigenvectors.
  * @var array
  */
	public $V = array();
	/**
  * Array for internal storage of nonsymmetric Hessenberg form.
  * @var array
  */
	public $H = array();
	/**
  * Working storage for nonsymmetric algorithm.
  * @var array
  */
	public $ort;
	/**
  * Used for complex scalar division.
  * @var float
  */
	public $cdivr;
	public $cdivi;

	public function tred2()
	{
		$this->d = $this->V[$this->n - 1];
		$_obf_7w__ = $this->n - 1;

		for (; 0 < $_obf_7w__; $_obf_7w__--) {
			$_obf__y0_ = $_obf_7w__ - 1;
			$M = $_obf_f9cbhws_ = 0;
			$_obf_f9cbhws_ += array_sum(array_map(abs, $this->d));

			if ($_obf_f9cbhws_ == 0) {
				$this->e[$_obf_7w__] = $this->d[$_obf__y0_];
				$this->d = array_slice($this->V[$_obf__y0_], 0, $_obf__y0_);
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$this->V[$_obf_XA__][$_obf_7w__] = $this->V[$_obf_7w__][$_obf_XA__] = 0;
				}
			}
			else {
				$_obf_5w__ = 0;

				for (; $_obf_5w__ < $_obf_7w__; $_obf_5w__++) {
					$this->d[$_obf_5w__] /= $_obf_f9cbhws_;
					$M += pow($this->d[$_obf_5w__], 2);
				}

				$_obf_6Q__ = $this->d[$_obf__y0_];
				$_obf_1Q__ = sqrt($M);

				if (0 < $_obf_6Q__) {
					$_obf_1Q__ = 0 - $_obf_1Q__;
				}

				$this->e[$_obf_7w__] = $_obf_f9cbhws_ * $_obf_1Q__;
				$M = $M - ($_obf_6Q__ * $_obf_1Q__);
				$this->d[$_obf__y0_] = $_obf_6Q__ - $_obf_1Q__;
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$this->e[$_obf_XA__] = 0;
				}

				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$_obf_6Q__ = $this->d[$_obf_XA__];
					$this->V[$_obf_XA__][$_obf_7w__] = $_obf_6Q__;
					$_obf_1Q__ = $this->e[$_obf_XA__] + ($this->V[$_obf_XA__][$_obf_XA__] * $_obf_6Q__);
					$_obf_5w__ = $_obf_XA__ + 1;

					for (; $_obf_5w__ <= $_obf__y0_; $_obf_5w__++) {
						$_obf_1Q__ += $this->V[$_obf_5w__][$_obf_XA__] * $this->d[$_obf_5w__];
						$this->e[$_obf_5w__] += $this->V[$_obf_5w__][$_obf_XA__] * $_obf_6Q__;
					}

					$this->e[$_obf_XA__] = $_obf_1Q__;
				}

				$_obf_6Q__ = 0;
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$this->e[$_obf_XA__] /= $M;
					$_obf_6Q__ += $this->e[$_obf_XA__] * $this->d[$_obf_XA__];
				}

				$_obf_Nl4_ = $_obf_6Q__ / (2 * $M);
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$this->e[$_obf_XA__] -= $_obf_Nl4_ * $this->d[$_obf_XA__];
				}

				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $_obf_7w__; $_obf_XA__++) {
					$_obf_6Q__ = $this->d[$_obf_XA__];
					$_obf_1Q__ = $this->e[$_obf_XA__];
					$_obf_5w__ = $_obf_XA__;

					for (; $_obf_5w__ <= $_obf__y0_; $_obf_5w__++) {
						$this->V[$_obf_5w__][$_obf_XA__] -= ($_obf_6Q__ * $this->e[$_obf_5w__]) + ($_obf_1Q__ * $this->d[$_obf_5w__]);
					}

					$this->d[$_obf_XA__] = $this->V[$_obf_7w__ - 1][$_obf_XA__];
					$this->V[$_obf_7w__][$_obf_XA__] = 0;
				}
			}

			$this->d[$_obf_7w__] = $M;
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < ($this->n - 1); $_obf_7w__++) {
			$this->V[$this->n - 1][$_obf_7w__] = $this->V[$_obf_7w__][$_obf_7w__];
			$this->V[$_obf_7w__][$_obf_7w__] = 1;
			$M = $this->d[$_obf_7w__ + 1];

			if ($M != 0) {
				$_obf_5w__ = 0;

				for (; $_obf_5w__ <= $_obf_7w__; $_obf_5w__++) {
					$this->d[$_obf_5w__] = $this->V[$_obf_5w__][$_obf_7w__ + 1] / $M;
				}

				$_obf_XA__ = 0;

				for (; $_obf_XA__ <= $_obf_7w__; $_obf_XA__++) {
					$_obf_1Q__ = 0;
					$_obf_5w__ = 0;

					for (; $_obf_5w__ <= $_obf_7w__; $_obf_5w__++) {
						$_obf_1Q__ += $this->V[$_obf_5w__][$_obf_7w__ + 1] * $this->V[$_obf_5w__][$_obf_XA__];
					}

					$_obf_5w__ = 0;

					for (; $_obf_5w__ <= $_obf_7w__; $_obf_5w__++) {
						$this->V[$_obf_5w__][$_obf_XA__] -= $_obf_1Q__ * $this->d[$_obf_5w__];
					}
				}
			}

			$_obf_5w__ = 0;

			for (; $_obf_5w__ <= $_obf_7w__; $_obf_5w__++) {
				$this->V[$_obf_5w__][$_obf_7w__ + 1] = 0;
			}
		}

		$this->d = $this->V[$this->n - 1];
		$this->V[$this->n - 1] = array_fill(0, $_obf_XA__, 0);
		$this->V[$this->n - 1][$this->n - 1] = 1;
		$this->e[0] = 0;
	}

	public function tql2()
	{
		$_obf_7w__ = 1;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$this->e[$_obf_7w__ - 1] = $this->e[$_obf_7w__];
		}

		$this->e[$this->n - 1] = 0;
		$_obf_6Q__ = 0;
		$_obf_8hA6_g__ = 0;
		$_obf_q_45 = pow(2, -52);
		$A = 0;

		for (; $A < $this->n; $A++) {
			$_obf_8hA6_g__ = max($_obf_8hA6_g__, abs($this->d[$A]) + abs($this->e[$A]));
			$_obf_Ag__ = $A;

			while ($_obf_Ag__ < $this->n) {
				if (abs($this->e[$_obf_Ag__]) <= $_obf_q_45 * $_obf_8hA6_g__) {
					break;
				}

				$_obf_Ag__++;
			}

			if ($A < $_obf_Ag__) {
				$_obf_XkRJvA__ = 0;

				do {
					$_obf_XkRJvA__ += 1;
					$_obf_1Q__ = $this->d[$A];
					$_obf_8w__ = ($this->d[$A + 1] - $_obf_1Q__) / (2 * $this->e[$A]);
					$_obf_OQ__ = hypo($_obf_8w__, 1);

					if ($_obf_8w__ < 0) {
						$_obf_OQ__ *= -1;
					}

					$this->d[$A] = $this->e[$A] / ($_obf_8w__ + $_obf_OQ__);
					$this->d[$A + 1] = $this->e[$A] * ($_obf_8w__ + $_obf_OQ__);
					$_obf_F8iV = $this->d[$A + 1];
					$M = $_obf_1Q__ - $this->d[$A];
					$_obf_7w__ = $A + 2;

					for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
						$this->d[$_obf_7w__] -= $M;
					}

					$_obf_6Q__ += $M;
					$_obf_8w__ = $this->d[$_obf_Ag__];
					$_obf_KQ__ = 1;
					$_obf__YQ_ = $_obf_aQ4_ = $_obf_KQ__;
					$_obf_Cg0V = $this->e[$A + 1];
					$p = $_obf_ifs_ = 0;
					$_obf_7w__ = $_obf_Ag__ - 1;

					for (; $A <= $_obf_7w__; $_obf_7w__--) {
						$_obf_aQ4_ = $_obf__YQ_;
						$_obf__YQ_ = $_obf_KQ__;
						$_obf_ifs_ = $p;
						$_obf_1Q__ = $_obf_KQ__ * $this->e[$_obf_7w__];
						$M = $_obf_KQ__ * $_obf_8w__;
						$_obf_OQ__ = hypo($_obf_8w__, $this->e[$_obf_7w__]);
						$this->e[$_obf_7w__ + 1] = $p * $_obf_OQ__;
						$p = $this->e[$_obf_7w__] / $_obf_OQ__;
						$_obf_KQ__ = $_obf_8w__ / $_obf_OQ__;
						$_obf_8w__ = ($_obf_KQ__ * $this->d[$_obf_7w__]) - ($p * $_obf_1Q__);
						$this->d[$_obf_7w__ + 1] = $M + ($p * (($_obf_KQ__ * $_obf_1Q__) + ($p * $this->d[$_obf_7w__])));
						$_obf_5w__ = 0;

						for (; $_obf_5w__ < $this->n; $_obf_5w__++) {
							$M = $this->V[$_obf_5w__][$_obf_7w__ + 1];
							$this->V[$_obf_5w__][$_obf_7w__ + 1] = ($p * $this->V[$_obf_5w__][$_obf_7w__]) + ($_obf_KQ__ * $M);
							$this->V[$_obf_5w__][$_obf_7w__] = ($_obf_KQ__ * $this->V[$_obf_5w__][$_obf_7w__]) - ($p * $M);
						}
					}

					$_obf_8w__ = ((0 - $p) * $_obf_ifs_ * $_obf_aQ4_ * $_obf_Cg0V * $this->e[$A]) / $_obf_F8iV;
					$this->e[$A] = $p * $_obf_8w__;
					$this->d[$A] = $_obf_KQ__ * $_obf_8w__;
				} while (($_obf_q_45 * $_obf_8hA6_g__) < abs($this->e[$A]));
			}

			$this->d[$A] = $this->d[$A] + $_obf_6Q__;
			$this->e[$A] = 0;
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < ($this->n - 1); $_obf_7w__++) {
			$_obf_5w__ = $_obf_7w__;
			$_obf_8w__ = $this->d[$_obf_7w__];
			$_obf_XA__ = $_obf_7w__ + 1;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				if ($this->d[$_obf_XA__] < $_obf_8w__) {
					$_obf_5w__ = $_obf_XA__;
					$_obf_8w__ = $this->d[$_obf_XA__];
				}
			}

			if ($_obf_5w__ != $_obf_7w__) {
				$this->d[$_obf_5w__] = $this->d[$_obf_7w__];
				$this->d[$_obf_7w__] = $_obf_8w__;
				$_obf_XA__ = 0;

				for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
					$_obf_8w__ = $this->V[$_obf_XA__][$_obf_7w__];
					$this->V[$_obf_XA__][$_obf_7w__] = $this->V[$_obf_XA__][$_obf_5w__];
					$this->V[$_obf_XA__][$_obf_5w__] = $_obf_8w__;
				}
			}
		}
	}

	public function orthes()
	{
		$_obf_P6Oa = 0;
		$_obf_5f8MSg__ = $this->n - 1;
		$_obf_Ag__ = $_obf_P6Oa + 1;

		for (; $_obf_Ag__ <= $_obf_5f8MSg__ - 1; $_obf_Ag__++) {
			$_obf_f9cbhws_ = 0;
			$_obf_7w__ = $_obf_Ag__;

			for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
				$_obf_f9cbhws_ = $_obf_f9cbhws_ + abs($this->H[$_obf_7w__][$_obf_Ag__ - 1]);
			}

			if ($_obf_f9cbhws_ != 0) {
				$M = 0;
				$_obf_7w__ = $_obf_5f8MSg__;

				for (; $_obf_Ag__ <= $_obf_7w__; $_obf_7w__--) {
					$this->ort[$_obf_7w__] = $this->H[$_obf_7w__][$_obf_Ag__ - 1] / $_obf_f9cbhws_;
					$M += $this->ort[$_obf_7w__] * $this->ort[$_obf_7w__];
				}

				$_obf_1Q__ = sqrt($M);

				if (0 < $this->ort[$_obf_Ag__]) {
					$_obf_1Q__ *= -1;
				}

				$M -= $this->ort[$_obf_Ag__] * $_obf_1Q__;
				$this->ort[$_obf_Ag__] -= $_obf_1Q__;
				$_obf_XA__ = $_obf_Ag__;

				for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
					$_obf_6Q__ = 0;
					$_obf_7w__ = $_obf_5f8MSg__;

					for (; $_obf_Ag__ <= $_obf_7w__; $_obf_7w__--) {
						$_obf_6Q__ += $this->ort[$_obf_7w__] * $this->H[$_obf_7w__][$_obf_XA__];
					}

					$_obf_6Q__ /= $M;
					$_obf_7w__ = $_obf_Ag__;

					for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
						$this->H[$_obf_7w__][$_obf_XA__] -= $_obf_6Q__ * $this->ort[$_obf_7w__];
					}
				}

				$_obf_7w__ = 0;

				for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
					$_obf_6Q__ = 0;
					$_obf_XA__ = $_obf_5f8MSg__;

					for (; $_obf_Ag__ <= $_obf_XA__; $_obf_XA__--) {
						$_obf_6Q__ += $this->ort[$_obf_XA__] * $this->H[$_obf_7w__][$_obf_XA__];
					}

					$_obf_6Q__ = $_obf_6Q__ / $M;
					$_obf_XA__ = $_obf_Ag__;

					for (; $_obf_XA__ <= $_obf_5f8MSg__; $_obf_XA__++) {
						$this->H[$_obf_7w__][$_obf_XA__] -= $_obf_6Q__ * $this->ort[$_obf_XA__];
					}
				}

				$this->ort[$_obf_Ag__] = $_obf_f9cbhws_ * $this->ort[$_obf_Ag__];
				$this->H[$_obf_Ag__][$_obf_Ag__ - 1] = $_obf_f9cbhws_ * $_obf_1Q__;
			}
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $this->n; $_obf_XA__++) {
				$this->V[$_obf_7w__][$_obf_XA__] = $_obf_7w__ == $_obf_XA__ ? 1 : 0;
			}
		}

		$_obf_Ag__ = $_obf_5f8MSg__ - 1;

		for (; ($_obf_P6Oa + 1) <= $_obf_Ag__; $_obf_Ag__--) {
			if ($this->H[$_obf_Ag__][$_obf_Ag__ - 1] != 0) {
				$_obf_7w__ = $_obf_Ag__ + 1;

				for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
					$this->ort[$_obf_7w__] = $this->H[$_obf_7w__][$_obf_Ag__ - 1];
				}

				$_obf_XA__ = $_obf_Ag__;

				for (; $_obf_XA__ <= $_obf_5f8MSg__; $_obf_XA__++) {
					$_obf_1Q__ = 0;
					$_obf_7w__ = $_obf_Ag__;

					for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
						$_obf_1Q__ += $this->ort[$_obf_7w__] * $this->V[$_obf_7w__][$_obf_XA__];
					}

					$_obf_1Q__ = $_obf_1Q__ / $this->ort[$_obf_Ag__] / $this->H[$_obf_Ag__][$_obf_Ag__ - 1];
					$_obf_7w__ = $_obf_Ag__;

					for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
						$this->V[$_obf_7w__][$_obf_XA__] += $_obf_1Q__ * $this->ort[$_obf_7w__];
					}
				}
			}
		}
	}

	public function cdiv($xr, $xi, $yr, $yi)
	{
		if (abs($yi) < abs($yr)) {
			$_obf_OQ__ = $yi / $yr;
			$_obf_5g__ = $yr + ($_obf_OQ__ * $yi);
			$this->cdivr = ($xr + ($_obf_OQ__ * $xi)) / $_obf_5g__;
			$this->cdivi = ($xi - ($_obf_OQ__ * $xr)) / $_obf_5g__;
		}
		else {
			$_obf_OQ__ = $yr / $yi;
			$_obf_5g__ = $yi + ($_obf_OQ__ * $yr);
			$this->cdivr = (($_obf_OQ__ * $xr) + $xi) / $_obf_5g__;
			$this->cdivi = (($_obf_OQ__ * $xi) - $xr) / $_obf_5g__;
		}
	}

	public function hqr2()
	{
		$_obf_hNk_ = $this->n;
		$_obf_FQ__ = $_obf_hNk_ - 1;
		$_obf_P6Oa = 0;
		$_obf_5f8MSg__ = $_obf_hNk_ - 1;
		$_obf_q_45 = pow(2, -52);
		$_obf_GnSyYnt75Q__ = 0;
		$_obf_8w__ = $_obf_Bw__ = $_obf_OQ__ = $p = $_obf_gQ__ = 0;
		$_obf_Nv0HkA__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_hNk_; $_obf_7w__++) {
			if (($_obf_7w__ < $_obf_P6Oa) || ($_obf_5f8MSg__ < $_obf_7w__)) {
				$this->d[$_obf_7w__] = $this->H[$_obf_7w__][$_obf_7w__];
				$this->e[$_obf_7w__] = 0;
			}

			$_obf_XA__ = max($_obf_7w__ - 1, 0);

			for (; $_obf_XA__ < $_obf_hNk_; $_obf_XA__++) {
				$_obf_Nv0HkA__ = $_obf_Nv0HkA__ + abs($this->H[$_obf_7w__][$_obf_XA__]);
			}
		}

		$_obf_XkRJvA__ = 0;

		while ($_obf_P6Oa <= $_obf_FQ__) {
			$A = $_obf_FQ__;

			while ($_obf_P6Oa < $A) {
				$p = abs($this->H[$A - 1][$A - 1]) + abs($this->H[$A][$A]);

				if ($p == 0) {
					$p = $_obf_Nv0HkA__;
				}

				if (abs($this->H[$A][$A - 1]) < ($_obf_q_45 * $p)) {
					break;
				}

				$A--;
			}

			if ($A == $_obf_FQ__) {
				$this->H[$_obf_FQ__][$_obf_FQ__] = $this->H[$_obf_FQ__][$_obf_FQ__] + $_obf_GnSyYnt75Q__;
				$this->d[$_obf_FQ__] = $this->H[$_obf_FQ__][$_obf_FQ__];
				$this->e[$_obf_FQ__] = 0;
				$_obf_FQ__--;
				$_obf_XkRJvA__ = 0;
			}
			else if ($A == $_obf_FQ__ - 1) {
				$_obf_hg__ = $this->H[$_obf_FQ__][$_obf_FQ__ - 1] * $this->H[$_obf_FQ__ - 1][$_obf_FQ__];
				$_obf_8w__ = ($this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] - $this->H[$_obf_FQ__][$_obf_FQ__]) / 2;
				$_obf_Bw__ = ($_obf_8w__ * $_obf_8w__) + $_obf_hg__;
				$_obf_gQ__ = sqrt(abs($_obf_Bw__));
				$this->H[$_obf_FQ__][$_obf_FQ__] = $this->H[$_obf_FQ__][$_obf_FQ__] + $_obf_GnSyYnt75Q__;
				$this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] = $this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] + $_obf_GnSyYnt75Q__;
				$_obf_5Q__ = $this->H[$_obf_FQ__][$_obf_FQ__];

				if (0 <= $_obf_Bw__) {
					if (0 <= $_obf_8w__) {
						$_obf_gQ__ = $_obf_8w__ + $_obf_gQ__;
					}
					else {
						$_obf_gQ__ = $_obf_8w__ - $_obf_gQ__;
					}

					$this->d[$_obf_FQ__ - 1] = $_obf_5Q__ + $_obf_gQ__;
					$this->d[$_obf_FQ__] = $this->d[$_obf_FQ__ - 1];

					if ($_obf_gQ__ != 0) {
						$this->d[$_obf_FQ__] = $_obf_5Q__ - ($_obf_hg__ / $_obf_gQ__);
					}

					$this->e[$_obf_FQ__ - 1] = 0;
					$this->e[$_obf_FQ__] = 0;
					$_obf_5Q__ = $this->H[$_obf_FQ__][$_obf_FQ__ - 1];
					$p = abs($_obf_5Q__) + abs($_obf_gQ__);
					$_obf_8w__ = $_obf_5Q__ / $p;
					$_obf_Bw__ = $_obf_gQ__ / $p;
					$_obf_OQ__ = sqrt(($_obf_8w__ * $_obf_8w__) + ($_obf_Bw__ * $_obf_Bw__));
					$_obf_8w__ = $_obf_8w__ / $_obf_OQ__;
					$_obf_Bw__ = $_obf_Bw__ / $_obf_OQ__;
					$_obf_XA__ = $_obf_FQ__ - 1;

					for (; $_obf_XA__ < $_obf_hNk_; $_obf_XA__++) {
						$_obf_gQ__ = $this->H[$_obf_FQ__ - 1][$_obf_XA__];
						$this->H[$_obf_FQ__ - 1][$_obf_XA__] = ($_obf_Bw__ * $_obf_gQ__) + ($_obf_8w__ * $this->H[$_obf_FQ__][$_obf_XA__]);
						$this->H[$_obf_FQ__][$_obf_XA__] = ($_obf_Bw__ * $this->H[$_obf_FQ__][$_obf_XA__]) - ($_obf_8w__ * $_obf_gQ__);
					}

					$_obf_7w__ = 0;

					for (; $_obf_7w__ <= n; $_obf_7w__++) {
						$_obf_gQ__ = $this->H[$_obf_7w__][$_obf_FQ__ - 1];
						$this->H[$_obf_7w__][$_obf_FQ__ - 1] = ($_obf_Bw__ * $_obf_gQ__) + ($_obf_8w__ * $this->H[$_obf_7w__][$_obf_FQ__]);
						$this->H[$_obf_7w__][$_obf_FQ__] = ($_obf_Bw__ * $this->H[$_obf_7w__][$_obf_FQ__]) - ($_obf_8w__ * $_obf_gQ__);
					}

					$_obf_7w__ = $_obf_P6Oa;

					for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
						$_obf_gQ__ = $this->V[$_obf_7w__][$_obf_FQ__ - 1];
						$this->V[$_obf_7w__][$_obf_FQ__ - 1] = ($_obf_Bw__ * $_obf_gQ__) + ($_obf_8w__ * $this->V[$_obf_7w__][$_obf_FQ__]);
						$this->V[$_obf_7w__][$_obf_FQ__] = ($_obf_Bw__ * $this->V[$_obf_7w__][$_obf_FQ__]) - ($_obf_8w__ * $_obf_gQ__);
					}
				}
				else {
					$this->d[$_obf_FQ__ - 1] = $_obf_5Q__ + $_obf_8w__;
					$this->d[$_obf_FQ__] = $_obf_5Q__ + $_obf_8w__;
					$this->e[$_obf_FQ__ - 1] = $_obf_gQ__;
					$this->e[$_obf_FQ__] = 0 - $_obf_gQ__;
				}

				$_obf_FQ__ = $_obf_FQ__ - 2;
				$_obf_XkRJvA__ = 0;
			}
			else {
				$_obf_5Q__ = $this->H[$_obf_FQ__][$_obf_FQ__];
				$_obf_OA__ = 0;
				$_obf_hg__ = 0;

				if ($A < $_obf_FQ__) {
					$_obf_OA__ = $this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1];
					$_obf_hg__ = $this->H[$_obf_FQ__][$_obf_FQ__ - 1] * $this->H[$_obf_FQ__ - 1][$_obf_FQ__];
				}

				if ($_obf_XkRJvA__ == 10) {
					$_obf_GnSyYnt75Q__ += $_obf_5Q__;
					$_obf_7w__ = $_obf_P6Oa;

					for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
						$this->H[$_obf_7w__][$_obf_7w__] -= $_obf_5Q__;
					}

					$p = abs($this->H[$_obf_FQ__][$_obf_FQ__ - 1]) + abs($this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 2]);
					$_obf_5Q__ = $_obf_OA__ = 0.75 * $p;
					$_obf_hg__ = -0.4375 * $p * $p;
				}

				if ($_obf_XkRJvA__ == 30) {
					$p = ($_obf_OA__ - $_obf_5Q__) / 2;
					$p = ($p * $p) + $_obf_hg__;

					if (0 < $p) {
						$p = sqrt($p);

						if ($_obf_OA__ < $_obf_5Q__) {
							$p = 0 - $p;
						}

						$p = $_obf_5Q__ - ($_obf_hg__ / ((($_obf_OA__ - $_obf_5Q__) / 2) + $p));
						$_obf_7w__ = $_obf_P6Oa;

						for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
							$this->H[$_obf_7w__][$_obf_7w__] -= $p;
						}

						$_obf_GnSyYnt75Q__ += $p;
						$_obf_5Q__ = $_obf_OA__ = $_obf_hg__ = 0.964;
					}
				}

				$_obf_XkRJvA__ = $_obf_XkRJvA__ + 1;
				$_obf_Ag__ = $_obf_FQ__ - 2;

				while ($A <= $_obf_Ag__) {
					$_obf_gQ__ = $this->H[$_obf_Ag__][$_obf_Ag__];
					$_obf_OQ__ = $_obf_5Q__ - $_obf_gQ__;
					$p = $_obf_OA__ - $_obf_gQ__;
					$_obf_8w__ = ((($_obf_OQ__ * $p) - $_obf_hg__) / $this->H[$_obf_Ag__ + 1][$_obf_Ag__]) + $this->H[$_obf_Ag__][$_obf_Ag__ + 1];
					$_obf_Bw__ = $this->H[$_obf_Ag__ + 1][$_obf_Ag__ + 1] - $_obf_gQ__ - $_obf_OQ__ - $p;
					$_obf_OQ__ = $this->H[$_obf_Ag__ + 2][$_obf_Ag__ + 1];
					$p = abs($_obf_8w__) + abs($_obf_Bw__) + abs($_obf_OQ__);
					$_obf_8w__ = $_obf_8w__ / $p;
					$_obf_Bw__ = $_obf_Bw__ / $p;
					$_obf_OQ__ = $_obf_OQ__ / $p;

					if ($_obf_Ag__ == $A) {
						break;
					}

					if ((abs($this->H[$_obf_Ag__][$_obf_Ag__ - 1]) * (abs($_obf_Bw__) + abs($_obf_OQ__))) < ($_obf_q_45 * abs($_obf_8w__) * (abs($this->H[$_obf_Ag__ - 1][$_obf_Ag__ - 1]) + abs($_obf_gQ__) + abs($this->H[$_obf_Ag__ + 1][$_obf_Ag__ + 1])))) {
						break;
					}

					$_obf_Ag__--;
				}

				$_obf_7w__ = $_obf_Ag__ + 2;

				for (; $_obf_7w__ <= $_obf_FQ__; $_obf_7w__++) {
					$this->H[$_obf_7w__][$_obf_7w__ - 2] = 0;

					if (($_obf_Ag__ + 2) < $_obf_7w__) {
						$this->H[$_obf_7w__][$_obf_7w__ - 3] = 0;
					}
				}

				$_obf_5w__ = $_obf_Ag__;

				for (; $_obf_5w__ <= $_obf_FQ__ - 1; $_obf_5w__++) {
					$_obf_ejcxSOioCg__ = $_obf_5w__ != $_obf_FQ__ - 1;

					if ($_obf_5w__ != $_obf_Ag__) {
						$_obf_8w__ = $this->H[$_obf_5w__][$_obf_5w__ - 1];
						$_obf_Bw__ = $this->H[$_obf_5w__ + 1][$_obf_5w__ - 1];
						$_obf_OQ__ = ($_obf_ejcxSOioCg__ ? $this->H[$_obf_5w__ + 2][$_obf_5w__ - 1] : 0);
						$_obf_5Q__ = abs($_obf_8w__) + abs($_obf_Bw__) + abs($_obf_OQ__);

						if ($_obf_5Q__ != 0) {
							$_obf_8w__ = $_obf_8w__ / $_obf_5Q__;
							$_obf_Bw__ = $_obf_Bw__ / $_obf_5Q__;
							$_obf_OQ__ = $_obf_OQ__ / $_obf_5Q__;
						}
					}

					if ($_obf_5Q__ == 0) {
						break;
					}

					$p = sqrt(($_obf_8w__ * $_obf_8w__) + ($_obf_Bw__ * $_obf_Bw__) + ($_obf_OQ__ * $_obf_OQ__));

					if ($_obf_8w__ < 0) {
						$p = 0 - $p;
					}

					if ($p != 0) {
						if ($_obf_5w__ != $_obf_Ag__) {
							$this->H[$_obf_5w__][$_obf_5w__ - 1] = (0 - $p) * $_obf_5Q__;
						}
						else if ($A != $_obf_Ag__) {
							$this->H[$_obf_5w__][$_obf_5w__ - 1] = 0 - $this->H[$_obf_5w__][$_obf_5w__ - 1];
						}

						$_obf_8w__ = $_obf_8w__ + $p;
						$_obf_5Q__ = $_obf_8w__ / $p;
						$_obf_OA__ = $_obf_Bw__ / $p;
						$_obf_gQ__ = $_obf_OQ__ / $p;
						$_obf_Bw__ = $_obf_Bw__ / $_obf_8w__;
						$_obf_OQ__ = $_obf_OQ__ / $_obf_8w__;
						$_obf_XA__ = $_obf_5w__;

						for (; $_obf_XA__ < $_obf_hNk_; $_obf_XA__++) {
							$_obf_8w__ = $this->H[$_obf_5w__][$_obf_XA__] + ($_obf_Bw__ * $this->H[$_obf_5w__ + 1][$_obf_XA__]);

							if ($_obf_ejcxSOioCg__) {
								$_obf_8w__ = $_obf_8w__ + ($_obf_OQ__ * $this->H[$_obf_5w__ + 2][$_obf_XA__]);
								$this->H[$_obf_5w__ + 2][$_obf_XA__] = $this->H[$_obf_5w__ + 2][$_obf_XA__] - ($_obf_8w__ * $_obf_gQ__);
							}

							$this->H[$_obf_5w__][$_obf_XA__] = $this->H[$_obf_5w__][$_obf_XA__] - ($_obf_8w__ * $_obf_5Q__);
							$this->H[$_obf_5w__ + 1][$_obf_XA__] = $this->H[$_obf_5w__ + 1][$_obf_XA__] - ($_obf_8w__ * $_obf_OA__);
						}

						$_obf_7w__ = 0;

						for (; $_obf_7w__ <= min($_obf_FQ__, $_obf_5w__ + 3); $_obf_7w__++) {
							$_obf_8w__ = ($_obf_5Q__ * $this->H[$_obf_7w__][$_obf_5w__]) + ($_obf_OA__ * $this->H[$_obf_7w__][$_obf_5w__ + 1]);

							if ($_obf_ejcxSOioCg__) {
								$_obf_8w__ = $_obf_8w__ + ($_obf_gQ__ * $this->H[$_obf_7w__][$_obf_5w__ + 2]);
								$this->H[$_obf_7w__][$_obf_5w__ + 2] = $this->H[$_obf_7w__][$_obf_5w__ + 2] - ($_obf_8w__ * $_obf_OQ__);
							}

							$this->H[$_obf_7w__][$_obf_5w__] = $this->H[$_obf_7w__][$_obf_5w__] - $_obf_8w__;
							$this->H[$_obf_7w__][$_obf_5w__ + 1] = $this->H[$_obf_7w__][$_obf_5w__ + 1] - ($_obf_8w__ * $_obf_Bw__);
						}

						$_obf_7w__ = $_obf_P6Oa;

						for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
							$_obf_8w__ = ($_obf_5Q__ * $this->V[$_obf_7w__][$_obf_5w__]) + ($_obf_OA__ * $this->V[$_obf_7w__][$_obf_5w__ + 1]);

							if ($_obf_ejcxSOioCg__) {
								$_obf_8w__ = $_obf_8w__ + ($_obf_gQ__ * $this->V[$_obf_7w__][$_obf_5w__ + 2]);
								$this->V[$_obf_7w__][$_obf_5w__ + 2] = $this->V[$_obf_7w__][$_obf_5w__ + 2] - ($_obf_8w__ * $_obf_OQ__);
							}

							$this->V[$_obf_7w__][$_obf_5w__] = $this->V[$_obf_7w__][$_obf_5w__] - $_obf_8w__;
							$this->V[$_obf_7w__][$_obf_5w__ + 1] = $this->V[$_obf_7w__][$_obf_5w__ + 1] - ($_obf_8w__ * $_obf_Bw__);
						}
					}
				}
			}
		}

		if ($_obf_Nv0HkA__ == 0) {
			return NULL;
		}

		$_obf_FQ__ = $_obf_hNk_ - 1;

		for (; 0 <= $_obf_FQ__; $_obf_FQ__--) {
			$_obf_8w__ = $this->d[$_obf_FQ__];
			$_obf_Bw__ = $this->e[$_obf_FQ__];

			if ($_obf_Bw__ == 0) {
				$A = $_obf_FQ__;
				$this->H[$_obf_FQ__][$_obf_FQ__] = 1;
				$_obf_7w__ = $_obf_FQ__ - 1;

				for (; 0 <= $_obf_7w__; $_obf_7w__--) {
					$_obf_hg__ = $this->H[$_obf_7w__][$_obf_7w__] - $_obf_8w__;
					$_obf_OQ__ = 0;
					$_obf_XA__ = $A;

					for (; $_obf_XA__ <= $_obf_FQ__; $_obf_XA__++) {
						$_obf_OQ__ = $_obf_OQ__ + ($this->H[$_obf_7w__][$_obf_XA__] * $this->H[$_obf_XA__][$_obf_FQ__]);
					}

					if ($this->e[$_obf_7w__] < 0) {
						$_obf_gQ__ = $_obf_hg__;
						$p = $_obf_OQ__;
					}
					else {
						$A = $_obf_7w__;

						if ($this->e[$_obf_7w__] == 0) {
							if ($_obf_hg__ != 0) {
								$this->H[$_obf_7w__][$_obf_FQ__] = (0 - $_obf_OQ__) / $_obf_hg__;
							}
							else {
								$this->H[$_obf_7w__][$_obf_FQ__] = (0 - $_obf_OQ__) / ($_obf_q_45 * $_obf_Nv0HkA__);
							}
						}
						else {
							$_obf_5Q__ = $this->H[$_obf_7w__][$_obf_7w__ + 1];
							$_obf_OA__ = $this->H[$_obf_7w__ + 1][$_obf_7w__];
							$_obf_Bw__ = (($this->d[$_obf_7w__] - $_obf_8w__) * ($this->d[$_obf_7w__] - $_obf_8w__)) + ($this->e[$_obf_7w__] * $this->e[$_obf_7w__]);
							$_obf_lw__ = (($_obf_5Q__ * $p) - ($_obf_gQ__ * $_obf_OQ__)) / $_obf_Bw__;
							$this->H[$_obf_7w__][$_obf_FQ__] = $_obf_lw__;

							if (abs($_obf_gQ__) < abs($_obf_5Q__)) {
								$this->H[$_obf_7w__ + 1][$_obf_FQ__] = (0 - $_obf_OQ__ - ($_obf_hg__ * $_obf_lw__)) / $_obf_5Q__;
							}
							else {
								$this->H[$_obf_7w__ + 1][$_obf_FQ__] = (0 - $p - ($_obf_OA__ * $_obf_lw__)) / $_obf_gQ__;
							}
						}

						$_obf_lw__ = abs($this->H[$_obf_7w__][$_obf_FQ__]);

						if (1 < ($_obf_q_45 * $_obf_lw__ * $_obf_lw__)) {
							$_obf_XA__ = $_obf_7w__;

							for (; $_obf_XA__ <= $_obf_FQ__; $_obf_XA__++) {
								$this->H[$_obf_XA__][$_obf_FQ__] = $this->H[$_obf_XA__][$_obf_FQ__] / $_obf_lw__;
							}
						}
					}
				}
			}
			else if ($_obf_Bw__ < 0) {
				$A = $_obf_FQ__ - 1;

				if (abs($this->H[$_obf_FQ__ - 1][$_obf_FQ__]) < abs($this->H[$_obf_FQ__][$_obf_FQ__ - 1])) {
					$this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] = $_obf_Bw__ / $this->H[$_obf_FQ__][$_obf_FQ__ - 1];
					$this->H[$_obf_FQ__ - 1][$_obf_FQ__] = (0 - $this->H[$_obf_FQ__][$_obf_FQ__] - $_obf_8w__) / $this->H[$_obf_FQ__][$_obf_FQ__ - 1];
				}
				else {
					$this->cdiv(0, 0 - $this->H[$_obf_FQ__ - 1][$_obf_FQ__], $this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] - $_obf_8w__, $_obf_Bw__);
					$this->H[$_obf_FQ__ - 1][$_obf_FQ__ - 1] = $this->cdivr;
					$this->H[$_obf_FQ__ - 1][$_obf_FQ__] = $this->cdivi;
				}

				$this->H[$_obf_FQ__][$_obf_FQ__ - 1] = 0;
				$this->H[$_obf_FQ__][$_obf_FQ__] = 1;
				$_obf_7w__ = $_obf_FQ__ - 2;

				for (; 0 <= $_obf_7w__; $_obf_7w__--) {
					$_obf_qUc_ = 0;
					$_obf_sk8_ = 0;
					$_obf_XA__ = $A;

					for (; $_obf_XA__ <= $_obf_FQ__; $_obf_XA__++) {
						$_obf_qUc_ = $_obf_qUc_ + ($this->H[$_obf_7w__][$_obf_XA__] * $this->H[$_obf_XA__][$_obf_FQ__ - 1]);
						$_obf_sk8_ = $_obf_sk8_ + ($this->H[$_obf_7w__][$_obf_XA__] * $this->H[$_obf_XA__][$_obf_FQ__]);
					}

					$_obf_hg__ = $this->H[$_obf_7w__][$_obf_7w__] - $_obf_8w__;

					if ($this->e[$_obf_7w__] < 0) {
						$_obf_gQ__ = $_obf_hg__;
						$_obf_OQ__ = $_obf_qUc_;
						$p = $_obf_sk8_;
					}
					else {
						$A = $_obf_7w__;

						if ($this->e[$_obf_7w__] == 0) {
							$this->cdiv(0 - $_obf_qUc_, 0 - $_obf_sk8_, $_obf_hg__, $_obf_Bw__);
							$this->H[$_obf_7w__][$_obf_FQ__ - 1] = $this->cdivr;
							$this->H[$_obf_7w__][$_obf_FQ__] = $this->cdivi;
						}
						else {
							$_obf_5Q__ = $this->H[$_obf_7w__][$_obf_7w__ + 1];
							$_obf_OA__ = $this->H[$_obf_7w__ + 1][$_obf_7w__];
							$_obf_9Oc_ = ((($this->d[$_obf_7w__] - $_obf_8w__) * ($this->d[$_obf_7w__] - $_obf_8w__)) + ($this->e[$_obf_7w__] * $this->e[$_obf_7w__])) - ($_obf_Bw__ * $_obf_Bw__);
							$_obf_Q9o_ = ($this->d[$_obf_7w__] - $_obf_8w__) * 2 * $_obf_Bw__;

							if (($_obf_9Oc_ == 0) & ($_obf_Q9o_ == 0)) {
								$_obf_9Oc_ = $_obf_q_45 * $_obf_Nv0HkA__ * (abs($_obf_hg__) + abs($_obf_Bw__) + abs($_obf_5Q__) + abs($_obf_OA__) + abs($_obf_gQ__));
							}

							$this->cdiv((($_obf_5Q__ * $_obf_OQ__) - ($_obf_gQ__ * $_obf_qUc_)) + ($_obf_Bw__ * $_obf_sk8_), ($_obf_5Q__ * $p) - ($_obf_gQ__ * $_obf_sk8_) - ($_obf_Bw__ * $_obf_qUc_), $_obf_9Oc_, $_obf_Q9o_);
							$this->H[$_obf_7w__][$_obf_FQ__ - 1] = $this->cdivr;
							$this->H[$_obf_7w__][$_obf_FQ__] = $this->cdivi;

							if ((abs($_obf_gQ__) + abs($_obf_Bw__)) < abs($_obf_5Q__)) {
								$this->H[$_obf_7w__ + 1][$_obf_FQ__ - 1] = ((0 - $_obf_qUc_ - ($_obf_hg__ * $this->H[$_obf_7w__][$_obf_FQ__ - 1])) + ($_obf_Bw__ * $this->H[$_obf_7w__][$_obf_FQ__])) / $_obf_5Q__;
								$this->H[$_obf_7w__ + 1][$_obf_FQ__] = (0 - $_obf_sk8_ - ($_obf_hg__ * $this->H[$_obf_7w__][$_obf_FQ__]) - ($_obf_Bw__ * $this->H[$_obf_7w__][$_obf_FQ__ - 1])) / $_obf_5Q__;
							}
							else {
								$this->cdiv(0 - $_obf_OQ__ - ($_obf_OA__ * $this->H[$_obf_7w__][$_obf_FQ__ - 1]), 0 - $p - ($_obf_OA__ * $this->H[$_obf_7w__][$_obf_FQ__]), $_obf_gQ__, $_obf_Bw__);
								$this->H[$_obf_7w__ + 1][$_obf_FQ__ - 1] = $this->cdivr;
								$this->H[$_obf_7w__ + 1][$_obf_FQ__] = $this->cdivi;
							}
						}

						$_obf_lw__ = max(abs($this->H[$_obf_7w__][$_obf_FQ__ - 1]), abs($this->H[$_obf_7w__][$_obf_FQ__]));

						if (1 < ($_obf_q_45 * $_obf_lw__ * $_obf_lw__)) {
							$_obf_XA__ = $_obf_7w__;

							for (; $_obf_XA__ <= $_obf_FQ__; $_obf_XA__++) {
								$this->H[$_obf_XA__][$_obf_FQ__ - 1] = $this->H[$_obf_XA__][$_obf_FQ__ - 1] / $_obf_lw__;
								$this->H[$_obf_XA__][$_obf_FQ__] = $this->H[$_obf_XA__][$_obf_FQ__] / $_obf_lw__;
							}
						}
					}
				}
			}
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_hNk_; $_obf_7w__++) {
			if (($_obf_7w__ < $_obf_P6Oa) | ($_obf_5f8MSg__ < $_obf_7w__)) {
				$_obf_XA__ = $_obf_7w__;

				for (; $_obf_XA__ < $_obf_hNk_; $_obf_XA__++) {
					$this->V[$_obf_7w__][$_obf_XA__] = $this->H[$_obf_7w__][$_obf_XA__];
				}
			}
		}

		$_obf_XA__ = $_obf_hNk_ - 1;

		for (; $_obf_P6Oa <= $_obf_XA__; $_obf_XA__--) {
			$_obf_7w__ = $_obf_P6Oa;

			for (; $_obf_7w__ <= $_obf_5f8MSg__; $_obf_7w__++) {
				$_obf_gQ__ = 0;
				$_obf_5w__ = $_obf_P6Oa;

				for (; $_obf_5w__ <= min($_obf_XA__, $_obf_5f8MSg__); $_obf_5w__++) {
					$_obf_gQ__ = $_obf_gQ__ + ($this->V[$_obf_7w__][$_obf_5w__] * $this->H[$_obf_5w__][$_obf_XA__]);
				}

				$this->V[$_obf_7w__][$_obf_XA__] = $_obf_gQ__;
			}
		}
	}

	public function EigenvalueDecomposition($Arg)
	{
		$this->A = $Arg->getArray();
		$this->n = $Arg->getColumnDimension();
		$this->V = array();
		$this->d = array();
		$this->e = array();
		$_obf_Cogu9XsOaDNPFO0_ = true;
		$_obf_XA__ = 0;

		for (; ($_obf_XA__ < $this->n) & $_obf_Cogu9XsOaDNPFO0_; $_obf_XA__++) {
			$_obf_7w__ = 0;

			for (; ($_obf_7w__ < $this->n) & $_obf_Cogu9XsOaDNPFO0_; $_obf_7w__++) {
				$_obf_Cogu9XsOaDNPFO0_ = $this->A[$_obf_7w__][$_obf_XA__] == $this->A[$_obf_XA__][$_obf_7w__];
			}
		}

		if ($_obf_Cogu9XsOaDNPFO0_) {
			$this->V = $this->A;
			$this->tred2();
			$this->tql2();
		}
		else {
			$this->H = $this->A;
			$this->ort = array();
			$this->orthes();
			$this->hqr2();
		}
	}

	public function getV()
	{
		return new Matrix($this->V, $this->n, $this->n);
	}

	public function getRealEigenvalues()
	{
		return $this->d;
	}

	public function getImagEigenvalues()
	{
		return $this->e;
	}

	public function getD()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_sg__[$_obf_7w__] = array_fill(0, $this->n, 0);
			$_obf_sg__[$_obf_7w__][$_obf_7w__] = $this->d[$_obf_7w__];

			if ($this->e[$_obf_7w__] == 0) {
				continue;
			}

			$_obf_tg__ = (0 < $this->e[$_obf_7w__] ? $_obf_7w__ + 1 : $_obf_7w__ - 1);
			$_obf_sg__[$_obf_7w__][$_obf_tg__] = $this->e[$_obf_7w__];
		}

		return new Matrix($_obf_sg__);
	}
}

echo ' ' . "\n" . '';

?>
