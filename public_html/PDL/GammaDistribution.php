<?php
//dezend by http://www.yunlu99.com/
require_once ROOT_PATH . 'PDL/ProbabilityDistribution.php';
require_once ROOT_PATH . 'PDL/SpecialMath.php';
class GammaDistribution extends ProbabilityDistribution
{
	/**
  * @float stores the shape parameter
  */
	public $shape;

	public function GammaDistribution($s)
	{
		if ($s <= 0) {
			return PEAR::raiseError('The shape parameter should be (strictly) positive.');
		}

		$this->shape = $s;
	}

	public function getShapeParameter()
	{
		return $this->shape;
	}

	public function getMean()
	{
		return $this->shape;
	}

	public function getVariance()
	{
		return $this->shape;
	}

	public function _getPDF($x)
	{
		$this->checkRange($x, 0, MAX_VALUE);

		if ($x == 0) {
			return 0;
		}
		else {
			return exp((0 - loggamma($this->shape) - $x) + (($this->shape - 1) * log($x)));
		}
	}

	public function _getCDF($x)
	{
		$this->checkRange($x, 0, MAX_VALUE);
		return incompletegamma($this->shape, $x);
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

		return $this->findRoot($p, $this->shape, 0, MAX_VALUE);
	}

	public function RNOR($mean = 0, $variance = 1)
	{
		static $use_last;
		static $last;

		if ($use_last) {
			$_obf_UAc_ = $last;
			$use_last = false;
		}
		else {
			do {
				$_obf_DqM_ = mt_rand() / mt_getrandmax();
				$_obf_oEs_ = mt_rand() / mt_getrandmax();
				$_obf_FY4_ = (2 * $_obf_DqM_) - 1;
				$_obf_9lo_ = (2 * $_obf_oEs_) - 1;
				$_obf_hg__ = ($_obf_FY4_ * $_obf_FY4_) + ($_obf_9lo_ * $_obf_9lo_);
			} while (1 <= $_obf_hg__);

			$_obf_hg__ = sqrt((-2 * log($_obf_hg__)) / $_obf_hg__);
			$_obf_UAc_ = $_obf_FY4_ * $_obf_hg__;
			$last = $_obf_9lo_ * $_obf_hg__;
			$use_last = true;
		}

		return $mean + ($_obf_UAc_ * sqrt($variance));
	}

	public function UNI()
	{
		return mt_rand() / mt_getrandmax();
	}

	public function _getRNG($scale = 1)
	{
		if ($this->shape < 1) {
			return _getrng($this->shape + 1, $scale) * pow($this->UNI(), 1 / $this->shape);
		}

		$_obf_5g__ = $this->shape - (1 / 3);
		$_obf_KQ__ = 1 / sqrt(9 * $_obf_5g__);

		for (; ; ) {
			do {
				$_obf_5Q__ = $this->RNOR();
				$_obf_6A__ = 1 + ($_obf_KQ__ * $_obf_5Q__);
			} while ($_obf_6A__ <= 0);

			$_obf_6A__ = $_obf_6A__ * $_obf_6A__ * $_obf_6A__;
			$_obf_Dg__ = $this->UNI();

			if ($_obf_Dg__ < (1 - (0.0331 * $_obf_5Q__ * $_obf_5Q__ * $_obf_5Q__ * $_obf_5Q__))) {
				return ($_obf_5g__ * $_obf_6A__) / $scale;
			}

			if (log($_obf_Dg__) < ((0.5 * $_obf_5Q__ * $_obf_5Q__) + ($_obf_5g__ * ((1 - $_obf_6A__) + log($_obf_6A__))))) {
				return ($_obf_5g__ * $_obf_6A__) / $scale;
			}
		}
	}
}

?>
