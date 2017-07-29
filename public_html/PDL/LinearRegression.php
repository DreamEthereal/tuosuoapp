<?php
//dezend by http://www.yunlu99.com/
class LinearRegression
{
	public $n;
	public $X = array();
	public $Y = array();
	public $ConfInt;
	public $Alpha;
	public $XMean;
	public $YMean;
	public $SumXX;
	public $SumXY;
	public $SumYY;
	public $Slope;
	public $YInt;
	public $PredictedY = array();
	public $Error = array();
	public $SquaredError = array();
	public $TotalError;
	public $SumError;
	public $SumSquaredError;
	public $ErrorVariance;
	public $StdErr;
	public $SlopeStdErr;
	public $SlopeVal;
	public $YIntStdErr;
	public $YIntTVal;
	public $R;
	public $RSquared;
	public $DF;
	public $SlopeProb;
	public $YIntProb;
	public $AlphaTVal;
	public $ConfIntOfSlope;
	public $format = '%01.2f';

	public function LinearRegression($X, $Y, $ConfidenceInterval)
	{
		$_obf_xJwdxw__ = count($X);
		$_obf_sqnC8g__ = count($Y);

		if ($_obf_xJwdxw__ != $_obf_sqnC8g__) {
			exit('<span class=red>错误：X值与Y值集合的长度必须一致</span>');
		}

		if ($_obf_xJwdxw__ <= 1) {
			exit('<span class=red>错误：X值与Y值集合的长度必须大于2</span>');
		}

		$this->n = $_obf_xJwdxw__;
		$this->X = $X;
		$this->Y = $Y;
		$this->ConfInt = $ConfidenceInterval;
		$this->Alpha = (100 - $this->ConfInt) / 100;
		$this->XMean = $this->getMean($this->X);
		$this->YMean = $this->getMean($this->Y);
		$this->SumXX = $this->getSumXX();
		$this->SumYY = $this->getSumYY();
		$this->SumXY = $this->getSumXY();
		$this->Slope = $this->getSlope();
		$this->YInt = $this->getYInt();
		$this->PredictedY = $this->getPredictedY();
		$this->Error = $this->getError();
		$this->SquaredError = $this->getSquaredError();
		$this->SumError = $this->getSumError();
		$this->TotalError = $this->getTotalError();
		$this->SumSquaredError = $this->getSumSquaredError();
		$this->ErrorVariance = $this->getErrorVariance();
		$this->StdErr = $this->getStdErr();
		$this->SlopeStdErr = $this->getSlopeStdErr();
		$this->YIntStdErr = $this->getYIntStdErr();
		$this->SlopeTVal = $this->getSlopeTVal();
		$this->YIntTVal = $this->getYIntTVal();
		$this->R = $this->getR();
		$this->RSquared = $this->getRSquared();
		$this->DF = $this->getDF();
		$_obf_TgR0mg__ = new Distribution();
		$this->SlopeProb = $_obf_TgR0mg__->getStudentT($this->SlopeTVal, $this->DF);
		$this->YIntProb = $_obf_TgR0mg__->getStudentT($this->YIntTVal, $this->DF);
		$this->AlphaTVal = $_obf_TgR0mg__->getInverseStudentT($this->Alpha, $this->DF);
		$this->ConfIntOfSlope = $this->getConfIntOfSlope();
		return true;
	}

	public function getMean($data)
	{
		$_obf_1FjQ5g__ = 0;
		$_obf_bhdW = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_bhdW += $data[$_obf_7w__];
		}

		$_obf_1FjQ5g__ = $_obf_bhdW / $this->n;
		return $_obf_1FjQ5g__;
	}

	public function getSumXX()
	{
		$_obf_7k1U5N4_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_7k1U5N4_ += ($this->X[$_obf_7w__] - $this->XMean) * ($this->X[$_obf_7w__] - $this->XMean);
		}

		return $_obf_7k1U5N4_;
	}

	public function getSumYY()
	{
		$_obf_KN4_kyA_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_KN4_kyA_ += ($this->Y[$_obf_7w__] - $this->YMean) * ($this->Y[$_obf_7w__] - $this->YMean);
		}

		return $_obf_KN4_kyA_;
	}

	public function getSumXY()
	{
		$_obf_g4ulC80_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_g4ulC80_ += ($this->X[$_obf_7w__] - $this->XMean) * ($this->Y[$_obf_7w__] - $this->YMean);
		}

		return $_obf_g4ulC80_;
	}

	public function getSlope()
	{
		$_obf_rXoadQo_ = 0;
		$_obf_rXoadQo_ = $this->SumXY / $this->SumXX;
		return $_obf_rXoadQo_;
	}

	public function getYInt()
	{
		$_obf_is0Mew__ = 0;
		$_obf_is0Mew__ = $this->YMean - ($this->Slope * $this->XMean);
		return $_obf_is0Mew__;
	}

	public function getPredictedY()
	{
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_Trezw_koY8eq6A__[$_obf_7w__] = $this->YInt + ($this->Slope * $this->X[$_obf_7w__]);
		}

		return $_obf_Trezw_koY8eq6A__;
	}

	public function getError()
	{
		$_obf_1Vl_Oo8_ = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_1Vl_Oo8_[$_obf_7w__] = $this->Y[$_obf_7w__] - $this->PredictedY[$_obf_7w__];
		}

		return $_obf_1Vl_Oo8_;
	}

	public function getTotalError()
	{
		$_obf_6NGhGojxnwMnxA__ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_6NGhGojxnwMnxA__ += pow($this->Y[$_obf_7w__] - $this->YMean, 2);
		}

		return $_obf_6NGhGojxnwMnxA__;
	}

	public function getSquaredError()
	{
		$_obf_y7Pa8la3IfwrY728 = array();
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_y7Pa8la3IfwrY728[$_obf_7w__] = pow($this->Y[$_obf_7w__] - $this->PredictedY[$_obf_7w__], 2);
		}

		return $_obf_y7Pa8la3IfwrY728;
	}

	public function getSumError()
	{
		$_obf_lTKomGQAcmw_ = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_lTKomGQAcmw_ += $this->Error[$_obf_7w__];
		}

		return $_obf_lTKomGQAcmw_;
	}

	public function getSumSquaredError()
	{
		$_obf_6QgJ4F_MfNXf_0qTJb_n = 0;
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_6QgJ4F_MfNXf_0qTJb_n += $this->SquaredError[$_obf_7w__];
		}

		return $_obf_6QgJ4F_MfNXf_0qTJb_n;
	}

	public function getErrorVariance()
	{
		$_obf_wk6r4qFvUhzMesRx3Q__ = 0;
		$_obf_wk6r4qFvUhzMesRx3Q__ = $this->SumSquaredError / ($this->n - 2);
		return $_obf_wk6r4qFvUhzMesRx3Q__;
	}

	public function getStdErr()
	{
		$_obf_9aZ4eHhB = 0;
		$_obf_9aZ4eHhB = sqrt($this->ErrorVariance);
		return $_obf_9aZ4eHhB;
	}

	public function getSlopeStdErr()
	{
		$_obf_D77HF6sboX6zDLk_ = 0;
		$_obf_D77HF6sboX6zDLk_ = $this->StdErr / sqrt($this->SumXX);
		return $_obf_D77HF6sboX6zDLk_;
	}

	public function getYIntStdErr()
	{
		$_obf_nvdfTQxsMvh1cQ__ = 0;
		$_obf_nvdfTQxsMvh1cQ__ = $this->StdErr * sqrt((1 / $this->n) + (pow($this->XMean, 2) / $this->SumXX));
		return $_obf_nvdfTQxsMvh1cQ__;
	}

	public function getSlopeTVal()
	{
		$_obf_EtZAML_fcsAm = 0;
		$_obf_EtZAML_fcsAm = $this->Slope / $this->SlopeStdErr;
		return $_obf_EtZAML_fcsAm;
	}

	public function getYIntTVal()
	{
		$_obf_GYrkt1UWxcw_ = 0;
		$_obf_GYrkt1UWxcw_ = $this->YInt / $this->YIntStdErr;
		return $_obf_GYrkt1UWxcw_;
	}

	public function getR()
	{
		$_obf_sw__ = 0;
		$_obf_sw__ = $this->SumXY / sqrt($this->SumXX * $this->SumYY);
		return $_obf_sw__;
	}

	public function getRSquared()
	{
		$_obf_1jgDKRqKFtE_ = 0;
		$_obf_1jgDKRqKFtE_ = $this->R * $this->R;
		return $_obf_1jgDKRqKFtE_;
	}

	public function getDF()
	{
		$_obf__ck_ = 0;
		$_obf__ck_ = $this->n - 2;
		return $_obf__ck_;
	}

	public function getConfIntOfSlope()
	{
		$_obf_tLUd962CPsItQrEWZbk_ = 0;
		$_obf_tLUd962CPsItQrEWZbk_ = $this->AlphaTVal * $this->SlopeStdErr;
		return $_obf_tLUd962CPsItQrEWZbk_;
	}
}

require_once ROOT_PATH . 'PDL/Distribution.php';

?>
