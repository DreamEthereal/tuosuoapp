<?php
//dezend by http://www.yunlu99.com/
class MathCluster
{
	protected $data;
	protected $name;
	protected $group;
	protected $cluster;
	protected $similarityMatrix;

	public function __construct()
	{
	}

	public function __destruct()
	{
	}

	public function dataLoad($data, $standardize = true)
	{
		$this->name = array_shift($data);

		if ($standardize) {
			require_once './Math.php';
			$math = new Math();
		}

		foreach ($data as $trait => $values) {
			if ($standardize) {
				$standardizeData[] = $math->standardize($values);
			}
			else {
				$standardizeData[] = $values;
			}
		}

		$this->data = call_user_func_array('array_map', array_merge(array(NULL), $standardizeData));
	}

	public function distance($x, $y, $method = 'Euclidean')
	{
		$method = strtolower($method);
		if (($method == 'jaccard') || ($method == 'dice') || ($method == 'simple matching')) {
			$_obf__xjtkBi1 = true;
			$_obf_cxomHg5n = 0;
			$_obf_Q0KeV5TnIQ__ = 0;
			$_obf_SUSIHA__ = 0;
		}
		else {
			$_obf__xjtkBi1 = false;
		}

		$_obf_5g__ = 0;
		$_obf_Qp82 = count($x);

		if ($_obf_Qp82 != count($y)) {
			return false;
		}

		$_obf_XA__ = 0;

		for (; $_obf_XA__ < $_obf_Qp82; $_obf_XA__++) {
			switch ($method) {
			case 'euclidean':
				$_obf_5g__ += ($x[$_obf_XA__] - $y[$_obf_XA__]) * ($x[$_obf_XA__] - $y[$_obf_XA__]);
				break;

			case 'manhattan':
				$_obf_5g__ += abs($x[$_obf_XA__] - $y[$_obf_XA__]);
				break;

			default:
				if (($x[$_obf_XA__] == $y[$_obf_XA__]) && ($x[$_obf_XA__] != 0)) {
					$_obf_cxomHg5n++;
				}
				else {
					if (($x[$_obf_XA__] == $y[$_obf_XA__]) && ($x[$_obf_XA__] == 0)) {
						$_obf_Q0KeV5TnIQ__++;
					}
					else {
						$_obf_SUSIHA__++;
					}
				}
			}
		}

		if ($method == 'euclidean') {
			$_obf_5g__ = sqrt($_obf_5g__);
		}

		if ($method == 'jaccard') {
			$_obf_5g__ = $_obf_cxomHg5n / ($_obf_cxomHg5n + $_obf_SUSIHA__);
		}

		if ($method == 'dice') {
			$_obf_5g__ = (2 * $_obf_cxomHg5n) / ((2 * $_obf_cxomHg5n) + $_obf_SUSIHA__);
		}

		if ($method == 'simple matching') {
			$_obf_5g__ = ($_obf_cxomHg5n + $_obf_Q0KeV5TnIQ__) / ($_obf_cxomHg5n + $_obf_Q0KeV5TnIQ__ + $_obf_SUSIHA__);
		}

		return $_obf_5g__;
	}

	protected function kMeanRandom($n)
	{
		$this->cluster = array();

		foreach ($this->data as $_obf_7w__ => $_obf_CIE9xPs_) {
			foreach ($_obf_CIE9xPs_ as $_obf_XA__ => $_obf_VgKtFeg_) {
				if (!isset($_obf_tdQX[$_obf_XA__]) || ($_obf_VgKtFeg_ <= $_obf_tdQX[$_obf_XA__])) {
					$_obf_tdQX[$_obf_XA__] = $_obf_VgKtFeg_;
				}

				if ($_obf_Qp82[$_obf_XA__] <= $_obf_VgKtFeg_) {
					$_obf_Qp82[$_obf_XA__] = $_obf_VgKtFeg_;
				}
			}
		}

		$_obf_Ml5F = count($this->data[0]);
		$_obf_5w__ = 0;

		for (; $_obf_5w__ < $n; $_obf_5w__++) {
			$_obf_XA__ = 0;

			for (; $_obf_XA__ < $_obf_Ml5F; $_obf_XA__++) {
				$this->cluster[$_obf_5w__][$_obf_XA__] = rand($_obf_tdQX[$_obf_XA__], $_obf_Qp82[$_obf_XA__]);
			}
		}

		return $this->cluster;
	}

	protected function assignCluster()
	{
		$_obf_6h__wkjrYA__ = false;

		foreach ($this->data as $_obf_7w__ => $_obf_CIE9xPs_) {
			$_obf_tdQX = false;

			foreach ($this->cluster as $_obf_5w__ => $_obf_ztR7cfta) {
				$_obf_5g__ = $this->distance($_obf_CIE9xPs_, $_obf_ztR7cfta);
				if (($_obf_tdQX === false) || ($_obf_5g__ < $_obf_tdQX)) {
					$_obf_tdQX = $_obf_5g__;
					$_obf_thyiIycUBA__ = $_obf_5w__;
				}
			}

			if ($this->group[$_obf_7w__] !== $_obf_thyiIycUBA__) {
				$_obf_6h__wkjrYA__ = true;
				$this->group[$_obf_7w__] = $_obf_thyiIycUBA__;
			}
		}

		return $_obf_6h__wkjrYA__;
	}

	protected function updateCenters()
	{
		$_obf_ZG_4UFir = array();

		foreach ($this->group as $_obf_7w__ => $_obf_5w__) {
			if (isset($_obf_ZG_4UFir[$_obf_5w__])) {
				$_obf_ZG_4UFir[$_obf_5w__]++;
			}
			else {
				$_obf_ZG_4UFir[$_obf_5w__] = 1;
			}

			foreach ($this->data[$_obf_7w__] as $_obf_XA__ => $_obf_VgKtFeg_) {
				if ($_obf_ZG_4UFir[$_obf_5w__] == 1) {
					$this->cluster[$_obf_5w__][$_obf_XA__] = $_obf_VgKtFeg_;
				}
				else {
					$this->cluster[$_obf_5w__][$_obf_XA__] += $_obf_VgKtFeg_;
				}
			}
		}

		foreach ($this->cluster as $_obf_5w__ => $_obf_ztR7cfta) {
			if (isset($_obf_ZG_4UFir[$_obf_5w__])) {
				$_obf_FQ__ = $_obf_ZG_4UFir[$_obf_5w__];

				foreach ($_obf_ztR7cfta as $_obf_XA__ => $_obf_VgKtFeg_) {
					$this->cluster[$_obf_5w__][$_obf_XA__] = $this->cluster[$_obf_5w__][$_obf_XA__] / $_obf_FQ__;
				}
			}
		}

		return $this->cluster;
	}

	public function kMean($n)
	{
		$this->kMeanRandom($n);

		while ($this->assignCluster()) {
			$this->updateCenters();
		}

		$this->group = array_combine($this->name, $this->group);
		asort($this->group);
		return $this->group;
	}

	protected function formSimilarityMatrix($data, $test = 'Euclidean')
	{
		$_obf_Qp82 = count($data);
		$this->similarityMatrix = array();

		foreach ($data as $_obf_7w__ => $_obf_5Q__) {
			reset($data);

			foreach ($data as $_obf_XA__ => $_obf_OA__) {
				if ($_obf_7w__ == $_obf_XA__) {
					break;
				}

				$this->similarityMatrix[$_obf_7w__][$_obf_XA__] = $this->distance($data[$_obf_7w__], $data[$_obf_XA__], $test);
			}
		}

		return $this->similarityMatrix;
	}

	public function hClust($method = 'Average', $test = 'Euclidean')
	{
		$_obf_tN0Sqg__ = '<table width="100%" style="margin-bottom:5px;"><tr><td><table style="border:1px solid #cacaca;"><tr style="color:white;"><td nowrap valign=center align=center bgcolor=#cf1100>节点标识符</td><td nowrap valign=center align=center bgcolor=#cf1100>子节点标识符</td><td nowrap valign=center align=center bgcolor=#cf1100>子节点标识符</td><td align=center valign=center bgcolor=#cf1100 nowrap>节点高度</td></tr>';
		$_obf_6RYLWQ__ = $this->data;
		$this->formSimilarityMatrix($_obf_6RYLWQ__, $test);
		$_obf_S1rUnw__ = 0;

		foreach ($this->similarityMatrix as $_obf_7w__ => $_obf_F43zfw__) {
			if ($_obf_S1rUnw__ < max($_obf_F43zfw__)) {
				$_obf_S1rUnw__ = max($_obf_F43zfw__);
			}
		}

		do {
			$_obf_tdQX = $_obf_S1rUnw__;

			foreach ($this->similarityMatrix as $_obf_7w__ => $_obf_F43zfw__) {
				foreach ($_obf_F43zfw__ as $_obf_XA__ => $_obf_SUSIHA__) {
					if ($_obf_SUSIHA__ < $_obf_tdQX) {
						$_obf_tdQX = $_obf_SUSIHA__;
						$_obf_RGdd_Q__ = $_obf_7w__;
						$_obf_OIA_8g__ = $_obf_XA__;
					}
				}
			}

			end($_obf_6RYLWQ__);
			$_obf_TMoT = key($_obf_6RYLWQ__) + 1;

			foreach ($_obf_6RYLWQ__[$_obf_RGdd_Q__] as $_obf_4NBCt78_ => $_obf_VgKtFeg_) {
				$_obf_6RYLWQ__[$_obf_TMoT][$_obf_4NBCt78_] = ($_obf_VgKtFeg_ + $_obf_6RYLWQ__[$_obf_OIA_8g__][$_obf_4NBCt78_]) / 2;
			}

			unset($_obf_6RYLWQ__[$_obf_RGdd_Q__]);
			unset($_obf_6RYLWQ__[$_obf_OIA_8g__]);
			$_obf_rdIX = $_obf_tdQX / $_obf_S1rUnw__;
			$_obf_tN0Sqg__ .= '<tr style="background-color:#e4e0ea;border-bottom:1px solid #cacaca"><td nowrap valign=center align=center>&nbsp;' . $_obf_TMoT . '</td>';
			$_obf_tN0Sqg__ .= '<td nowrap valign=center align=center>&nbsp;' . $_obf_RGdd_Q__ . '</td>';
			$_obf_tN0Sqg__ .= '<td nowrap valign=center align=center>&nbsp;' . $_obf_OIA_8g__ . '</td>';
			$_obf_tN0Sqg__ .= '<td nowrap valign=center align=center>&nbsp;' . $_obf_rdIX . '</td></tr>';
			$this->formSimilarityMatrix($_obf_6RYLWQ__, $test);
		} while (0 < count($this->similarityMatrix));

		$_obf_tN0Sqg__ .= '</table></td></tr></table>';
		return $_obf_tN0Sqg__;
	}
}


?>
