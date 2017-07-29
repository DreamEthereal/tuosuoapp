<?php
//dezend by http://www.yunlu99.com/
include_once ROOT_PATH . 'PDL/LinearRegression.php';
class LinearRegressionHTML extends LinearRegression
{
	public function LinearRegressionHTML($X, $Y, $conf_int)
	{
		LinearRegression::LinearRegression($X, $Y, $conf_int);
	}

	public function showTableSummary($x_name, $y_name)
	{
		$_obf_AKT9JOIxNQ__ = $this->ConfInt . '%';
		echo '    ' . "\r\n" . '     <table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-top:5px" width=580 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '      <tr bgcolor=#e5e5e5>' . "\r\n" . '        <td colspan=\'7\' align=center style="font-size:14px"><b>摘要表</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr>' . "\r\n" . '        <td align=\'center\'><b>序号</b></td>' . "\r\n" . '        <td align=\'center\'><b>X值</b></td>' . "\r\n" . '        <td align=\'center\'><b>Y值</b></td>' . "\r\n" . '        <td align=\'center\'><b>预测值</b></td>' . "\r\n" . '        <td align=\'center\'><b>余数</b></td>' . "\r\n" . '        <td align=\'center\'><b>Lower ';
		echo $_obf_AKT9JOIxNQ__;
		echo '</b></td>' . "\r\n" . '        <td align=\'center\'><b>Upper ';
		echo $_obf_AKT9JOIxNQ__;
		echo '</b></td>        ' . "\r\n" . '      </tr>' . "\r\n" . '      ';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->n; $_obf_7w__++) {
			$_obf_Trezw_koY8eq6A__ = sprintf($this->format, $this->PredictedY[$_obf_7w__]);
			$_obf_1Vl_Oo8_ = sprintf($this->format, $this->Error[$_obf_7w__]);
			$_obf_y7Pa8la3IfwrY728 = sprintf($this->format, $this->SquaredError[$_obf_7w__]);

			if ($this->SumXX != 0) {
				$_obf_mAOkf60FP7U_ = $this->AlphaTVal * $this->StdErr * sqrt(1 + (1 / $this->n) + (pow($this->X[$_obf_7w__] - $this->XMean, 2) / $this->SumXX));
			}
			else {
				$_obf_mAOkf60FP7U_ = 0;
			}

			$_obf_3whIzFnOOKSIFw__ = sprintf($this->format, $this->PredictedY[$_obf_7w__] - $_obf_mAOkf60FP7U_);
			$_obf_8ZV3LHGisKebQw__ = sprintf($this->format, $this->PredictedY[$_obf_7w__] + $_obf_mAOkf60FP7U_);
			$_obf_8w__ = $_obf_7w__ + 1;
			echo '<tr>';
			echo '  <td align=\'center\'>' . $_obf_8w__ . '</td>';
			echo '  <td align=\'center\'>' . $this->X[$_obf_7w__] . '</td>';
			echo '  <td align=\'center\'>' . $this->Y[$_obf_7w__] . '</td>';
			echo '  <td align=\'center\'>' . $_obf_Trezw_koY8eq6A__ . '</td>';
			echo '  <td align=\'center\'>' . $_obf_1Vl_Oo8_ . '</td>';
			echo '  <td align=\'center\'>' . $_obf_3whIzFnOOKSIFw__ . '</td>';
			echo '  <td align=\'center\'>' . $_obf_8ZV3LHGisKebQw__ . '</td>';
			echo '</tr>';
		}

		echo '    </table>' . "\r\n" . '    ';
	}

	public function showAnalysisOfVariance()
	{
		$_obf_clL8oRgJEpZ60w__ = sprintf($this->format, $this->TotalError - $this->SumSquaredError);
		$_obf_mTBMtCCd = sprintf($this->format, ($this->TotalError - $this->SumSquaredError) / $this->ErrorVariance);
		$_obf_6QgJ4F_MfNXf_0qTJb_n = sprintf($this->format, $this->SumSquaredError);
		$_obf_wk6r4qFvUhzMesRx3Q__ = sprintf($this->format, $this->ErrorVariance);
		$_obf_6NGhGojxnwMnxA__ = sprintf($this->format, $this->TotalError);
		echo '        ' . "\r\n" . '    <table style="LINE-HEIGHT: 150%;border-collapse:collapse" width=580 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '      <tr bgcolor=\'#e5e5e5\'>' . "\r\n" . '        <td colspan=\'5\' align=center style="font-size:14px"><b>偏离值分析</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr><td align=center><b>变异来源</b></td>' . "\r\n" . '	      <td align=center><b>自由度</b></td>' . "\r\n" . '		  <td align=center><b>平方和</b></td>' . "\r\n" . '		  <td align=center><b>均方</b></td>' . "\r\n" . '		  <td align=center><b>F值</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td>回归</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo 1;
		echo '</td>          ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_clL8oRgJEpZ60w__;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_clL8oRgJEpZ60w__;
		echo '</td>        ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_mTBMtCCd;
		echo '</td>               ' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td>误差</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $this->n - 2;
		echo '</td>                   ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_6QgJ4F_MfNXf_0qTJb_n;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_wk6r4qFvUhzMesRx3Q__;
		echo '</td>        ' . "\r\n" . '         <td align=\'right\'>&nbsp;</td>                                     ' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td>总</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $this->n - 1;
		echo '</td>                            ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_6NGhGojxnwMnxA__;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>&nbsp;</td>        ' . "\r\n" . '         <td align=\'right\'>&nbsp;</td>               ' . "\r\n" . '      </tr>' . "\r\n" . '    </table>    ' . "\r\n" . '    ';
	}

	public function showParameterEstimates()
	{
		$_obf_rXoadQo_ = sprintf($this->format, $this->Slope);
		$_obf_D77HF6sboX6zDLk_ = sprintf($this->format, $this->SlopeStdErr);
		$_obf_EtZAML_fcsAm = sprintf($this->format, $this->SlopeTVal);
		$_obf_qwfzhSKa6mmv = sprintf('%01.5f', $this->SlopeProb);
		$_obf_is0Mew__ = sprintf($this->format, $this->YInt);
		$_obf_nvdfTQxsMvh1cQ__ = sprintf($this->format, $this->YIntStdErr);
		$_obf_GYrkt1UWxcw_ = sprintf($this->format, $this->YIntTVal);
		$_obf_RJvzplbToZM_ = sprintf('%01.5f', $this->YIntProb);
		echo '    ' . "\r\n" . '    <table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-top:5px" width=580 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '       <tr bgcolor=\'#e5e5e5\'>' . "\r\n" . '        <td colspan=\'5\' align=center style="font-size:14px"><b>参数估计值</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr><td align=center><b>变量</b></td>' . "\r\n" . '	      <td align=center><b>估计</b></td>' . "\r\n" . '	      <td align=center><b>标准误差</b></td>' . "\r\n" . '	      <td align=center><b>T值</b></td>' . "\r\n" . '	      <td align=center><b>T值概率</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td>斜率</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_rXoadQo_;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_D77HF6sboX6zDLk_;
		echo '</td>        ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_EtZAML_fcsAm;
		echo '</td>               ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_qwfzhSKa6mmv;
		echo '</td>                        ' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td>Y轴截距</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_is0Mew__;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_nvdfTQxsMvh1cQ__;
		echo '</td>        ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_GYrkt1UWxcw_;
		echo '</td>               ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_RJvzplbToZM_;
		echo '</td>                                             ' . "\r\n" . '      </tr>' . "\r\n" . '    </table>' . "\r\n" . '    ';
	}

	public function showFormula($x_name, $y_name)
	{
		$_obf_is0Mew__ = sprintf($this->format, $this->YInt);
		$_obf_rXoadQo_ = sprintf($this->format, $this->Slope);
		echo '<span style=\'font-weight:bold;font-size:18px;color:red;padding-top:10px;padding-bottom:10px;height:35px;line-height:35px\'>方程式：' . $y_name . ' = ' . $_obf_is0Mew__ . ' + (' . $_obf_rXoadQo_ . ' * ' . $x_name . ')</span>';
	}

	public function showRValues()
	{
		$_obf_sw__ = sprintf($this->format, $this->R);
		$_obf_1jgDKRqKFtE_ = sprintf($this->format, $this->RSquared);
		echo '    ' . "\r\n" . '    <table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-top:5px" width=580 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '       <tr bgcolor=\'#e5e5e5\'>' . "\r\n" . '        <td colspan=\'2\' align=center style="font-size:14px"><b>R值</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr><td width=50% align=center><b>R值</b></td>' . "\r\n" . '	      <td width=50% align=center><b>R值平方</b></td>' . "\r\n" . '      </tr>' . "\r\n" . '      <tr> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_sw__;
		echo '</td> ' . "\r\n" . '         <td align=\'right\'>';
		echo $_obf_1jgDKRqKFtE_;
		echo '</td>        ' . "\r\n" . '      </tr>' . "\r\n" . '    </table>' . "\r\n" . '    ';
	}
}

?>
