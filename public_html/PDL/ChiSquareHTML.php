<?php
//dezend by http://www.yunlu99.com/
require_once PHP_MATH . 'ChiSquare.php';
class ChiSquareHTML extends ChiSquare1D
{
	public $format = '%01.2f';

	public function ChiSquareHTML($ObsFreq, $Alpha, $ExpProb = false)
	{
		ChiSquare1D::ChiSquare1D($ObsFreq, $Alpha, $ExpProb);
	}

	public function showTableSummary($TitleName, $Headings = false)
	{
		echo '    ' . "\n" . '    <table width=100%>' . "\n" . '    <tr><td height=25 class="question" valign=center><b>';
		echo $TitleName;
		echo '</b></td></tr>' . "\n" . '	<tr><td>' . "\n" . '	 <table style="border:1px solid #cacaca;">' . "\n" . '      <tr style="color:white;">' . "\n" . '        <td bgcolor=#cf1100 width=100>&nbsp;</td>' . "\n" . '        ';

		if ($Headings == false) {
			echo '<td colspan=\'' . $this->NumCells . '\'>Table Summary</td>';
		}
		else {
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
				echo '<td align=\'center\' bgcolor=#cf1100 style=\'color:white\' nowrap><b>' . $Headings[$_obf_7w__] . '</b></td>';
			}
		}

		echo '<td align=center bgcolor=#cf1100 style=\'color:white\'><b>合计</b></td>';
		echo '          ' . "\n" . '      </tr>' . "\n" . '      <tr style="background-color:#e4e0ea;border-bottom:1px solid #cacaca">' . "\n" . '        <td align=\'right\' nowrap>观察值</td>' . "\n" . '        ';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
			echo '<td align=\'center\' width=50>&nbsp;' . $this->ObsFreq[$_obf_7w__] . '&nbsp;</td>';
		}

		echo '<td align=\'center\' width=50>&nbsp;' . $this->Total . '&nbsp;</td>';
		echo '      </tr>' . "\n" . '      <tr style="background-color:#e4e0ea;border-bottom:1px solid #cacaca">' . "\n" . '        <td align=\'right\' nowrap>期望值</td>' . "\n" . '        ';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
			echo '<td align=\'center\' width=50>&nbsp;' . round($this->ExpFreq[$_obf_7w__], 2) . '&nbsp;</td>';
		}

		echo '<td align=\'center\' width=50>&nbsp;' . $this->Total . '&nbsp;</td>';
		echo '      </tr>' . "\n" . '      <tr style="background-color:#e4e0ea;border-bottom:1px solid #cacaca">' . "\n" . '        <td align=\'right\' nowrap>方差</td>' . "\n" . '        ';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $this->NumCells; $_obf_7w__++) {
			$_obf_TAxu = sprintf($this->format, pow($this->ObsFreq[$_obf_7w__] - $this->ExpFreq[$_obf_7w__], 2) / $this->ExpFreq[$_obf_7w__]);
			echo '<td align=\'center\' width=50>&nbsp;' . $_obf_TAxu . '&nbsp;</td>';
		}

		$_obf_TINP_KU9FfM_ = sprintf($this->format, $this->ChiSqObt);
		echo '<td align=\'center\' width=50><b>&nbsp;' . $_obf_TINP_KU9FfM_ . '&nbsp;</b></td>';
		echo '      </tr></table>' . "\n" . '    ';
	}

	public function showChiSquareStats()
	{
		$_obf_TINP_KU9FfM_ = sprintf($this->format, $this->ChiSqObt);
		$_obf_AM_YZRvvR9An = sprintf($this->format, $this->ChiSqProb);
		$_obf_Ij86sMhf_wS5 = sprintf($this->format, $this->ChiSqCrit);
		echo ' ' . "\n" . '	<table style="margin:0px;padding:0px;margin-bottom:10px">' . "\n" . '      <tr>' . "\n" . '        <td align=right nowrap><b>X平方：';
		echo $_obf_TINP_KU9FfM_;
		echo '&nbsp;&nbsp;&nbsp;自由度DF：';
		echo $this->DF;
		echo '&nbsp;&nbsp;&nbsp;概率：';
		echo $_obf_AM_YZRvvR9An;
		echo '&nbsp;&nbsp;&nbsp;临界值：';
		echo $_obf_Ij86sMhf_wS5;
		echo '</b></td>' . "\n" . '      </tr>' . "\n" . '    </table></td></tr></table>' . "\n" . '    ';
	}
}

?>
