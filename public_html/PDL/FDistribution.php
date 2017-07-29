<?php
//dezend by http://www.yunlu99.com/
require_once ROOT_PATH . 'PDL/ProbabilityDistribution.php';
require_once ROOT_PATH . 'PDL/BetaDistribution.php';
require_once ROOT_PATH . 'PDL/SpecialMath.php';
class FDistribution extends ProbabilityDistribution
{
	/**
  * @int numerator degrees of freedom.
  */
	public $numerator_df;
	/**
  * @int denominator degrees of freedom.
  */
	public $denominator_df;
	/**
  * @object beta distribution object.
  */
	public $beta;

	public function FDistribution($df1, $df2)
	{
		if (($df1 <= 0) || ($df2 <= 0)) {
			return PEAR::raiseError('The degrees of freedom must be greater than zero.');
		}

		$this->numerator_df = $df1;
		$this->denominator_df = $df2;
		$this->beta = new BetaDistribution($this->denominator_df / 2, $this->numerator_df / 2);
	}

	public function getNumeratorDF()
	{
		return $this->numerator_df;
	}

	public function getDenominatorDF()
	{
		return $this->denominator_df;
	}

	public function _getPDF($x)
	{
		$this->checkRange($x, 0, MAX_VALUE);
		$_obf_OA__ = $this->denominator_df / ($this->denominator_df + ($this->numerator_df * $x));
		return ($this->beta->PDF($_obf_OA__) * $_obf_OA__ * $_obf_OA__ * $this->numerator_df) / $this->denominator_df;
	}

	public function _getCDF($x)
	{
		$this->checkRange($x, 0, MAX_VALUE);
		return incompletebeta(1 / (1 + ($this->denominator_df / ($this->numerator_df * $x))), $this->numerator_df / 2, $this->denominator_df / 2);
	}

	public function _getICDF($p)
	{
		$this->checkRange($p);

		if ($p == 0) {
			return 0;
		}

		if ($p == 1) {
			return MAX_VALUE;
		}

		$_obf_OA__ = $this->beta->ICDF(1 - $p);

		if ($_obf_OA__ < 2.23E-308) {
			return MAX_VALUE;
		}
		else {
			return ($this->denominator_df / $this->numerator_df) * ((1 / $_obf_OA__) - 1);
		}
	}

	public function _getRNG()
	{
		$_obf_Dg__ = 0;
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $this->numerator_df; $_obf_7w__++) {
			$_obf_apweEqZl = mt_rand() / mt_getrandmax();
			$_obf_RiU5Psjc = mt_rand() / mt_getrandmax();
			$_obf_OQ__ = sqrt(-2 * log($_obf_apweEqZl));
			$_obf_Fc8ukmw_ = 2 * pi() * $_obf_RiU5Psjc;
			$_obf_gQ__ = $_obf_OQ__ * cos($_obf_Fc8ukmw_);
			$_obf_Dg__ = $_obf_Dg__ + ($_obf_gQ__ * $_obf_gQ__);
		}

		$_obf_6A__ = 0;
		$_obf_XA__ = 1;

		for (; $_obf_XA__ <= $this->denominator_df; $_obf_XA__++) {
			$_obf_apweEqZl = mt_rand() / mt_getrandmax();
			$_obf_RiU5Psjc = mt_rand() / mt_getrandmax();
			$_obf_OQ__ = sqrt(-2 * log($_obf_apweEqZl));
			$_obf_Fc8ukmw_ = 2 * pi() * $_obf_RiU5Psjc;
			$_obf_gQ__ = $_obf_OQ__ * cos($_obf_Fc8ukmw_);
			$_obf_6A__ = $_obf_6A__ + ($_obf_gQ__ * $_obf_gQ__);
		}

		return $_obf_Dg__ / $this->numerator_df / $_obf_6A__ / $this->denominator_df;
	}
}

?>
