<?php
//dezend by http://www.yunlu99.com/
require_once ROOT_PATH . 'PDL/ProbabilityDistribution.php';
require_once ROOT_PATH . 'PDL/SpecialMath.php';
class TDistribution extends ProbabilityDistribution
{
	/**
  * @int degrees of freedom
  */
	public $df;
	/**
  * @int log degrees of freedom
  */
	public $log_df;

	public function TDistribution($r)
	{
		if ($r <= 0) {
			return PEAR::raiseError('The degrees of freedom must be greater than zero.');
		}

		$this->df = $r;
		$this->log_df = 0 - logbeta(0.5 * $this->df, 0.5) - (0.5 * log($this->df));
	}

	public function getDF()
	{
		return $this->df;
	}

	public function _getPDF($x)
	{
		$_obf__09JNwg7 = $this->log_df;
		$_obf__09JNwg7 -= 0.5 * ($this->df + 1) * log(1 + (($x * $x) / $this->df));
		return exp($_obf__09JNwg7);
	}

	public function _getCDF($x)
	{
		$m = 0.5 * incompletebeta($this->df / ($this->df + ($x * $x)), 0.5 * $this->df, 0.5);
		return 0 < $x ? 1 - $m : $m;
	}

	public function _getICDF($p)
	{
		$this->checkRange($p);

		if ($p == 0) {
			return 0 - MAX_VALUE;
		}

		if ($p == 1) {
			return MAX_VALUE;
		}

		if ($p == 0.5) {
			return 0;
		}

		return $this->findRoot($p, 0, -0.5 * MAX_VALUE, 0.5 * MAX_VALUE);
	}

	public function _getRNG()
	{
		$_obf_6A__ = 0;
		$_obf_7w__ = 1;

		for (; $_obf_7w__ <= $this->df; $_obf_7w__++) {
			$_obf_apweEqZl = mt_rand() / mt_getrandmax();
			$_obf_RiU5Psjc = mt_rand() / mt_getrandmax();
			$_obf_OQ__ = sqrt(-2 * log($_obf_apweEqZl));
			$_obf_Fc8ukmw_ = 2 * pi() * $_obf_RiU5Psjc;
			$_obf_gQ__ = $_obf_OQ__ * cos($_obf_Fc8ukmw_);
			$_obf_6A__ = $_obf_6A__ + ($_obf_gQ__ * $_obf_gQ__);
		}

		$_obf_apweEqZl = mt_rand() / mt_getrandmax();
		$_obf_RiU5Psjc = mt_rand() / mt_getrandmax();
		$_obf_OQ__ = sqrt(-2 * log($_obf_apweEqZl));
		$_obf_Fc8ukmw_ = 2 * pi() * $_obf_RiU5Psjc;
		$_obf_gQ__ = $_obf_OQ__ * cos($_obf_Fc8ukmw_);
		return $_obf_gQ__ / sqrt($_obf_6A__ / $this->df);
	}
}

?>
