<?php
//dezend by http://www.yunlu99.com/
require_once ROOT_PATH . 'PDL/ProbabilityDistribution.php';
require_once ROOT_PATH . 'PDL/GammaDistribution.php';
require_once ROOT_PATH . 'PDL/SpecialMath.php';
class BetaDistribution extends ProbabilityDistribution
{
	/**
  * @int degrees of freedom p.
  */
	public $p;
	/**
  * @int degrees of freedom q.
  */
	public $q;
	/**
  * @object  gamma object for RNG method
  */
	public $gamma1;
	/**
  * @object gamma object for RNG method
  */
	public $gamma2;

	public function BetaDistribution($dgrP, $dgrQ)
	{
		if (($dgrP <= 0) || ($dgrQ <= 0)) {
			return PEAR::raiseError('Paramters must be greater than zero.');
		}

		$this->p = $dgrP;
		$this->q = $dgrQ;
		$this->gamma1 = new GammaDistribution($this->p, 1);
		$this->gamma2 = new GammaDistribution($this->q, 1);
	}

	public function getDegreesOfFreedomP()
	{
		return $this->p;
	}

	public function getDegreesOfFreedomQ()
	{
		return $this->q;
	}

	public function getMean()
	{
		return $this->p / ($this->p + $this->q);
	}

	public function getStandardDeviation()
	{
		return sqrt(($this->p * $this->q) / (pow($this->p + $this->q, 2) * ($this->p + $this->q + 1)));
	}

	public function _getPDF($x)
	{
		$this->checkRange($x);
		if (($x == 0) || ($x == 1)) {
			return 0;
		}
		else {
			return exp((0 - logbeta($this->p, $this->q)) + (($this->p - 1) * log($x)) + (($this->q - 1) * log(1 - $x)));
		}
	}

	public function _getCDF($x)
	{
		$this->checkRange($x);
		return incompletebeta($x, $this->p, $this->q);
	}

	public function _getICDF($p)
	{
		$this->checkRange($p);

		if ($p == 0) {
			return 0;
		}

		if ($p == 1) {
			return 1;
		}

		return $this->findRoot($p, 0.5, 0, 1);
	}

	public function _getRNG()
	{
		$_obf_5Q__ = $this->gamma1->RNG();
		$_obf_OA__ = $this->gamma2->RNG();
		return $_obf_5Q__ / ($_obf_5Q__ + $_obf_OA__);
	}
}
echo ' ';

?>
