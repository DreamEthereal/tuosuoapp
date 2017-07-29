<?php
//dezend by http://www.yunlu99.com/
class PageBar
{
	public $total;
	public $onepage;
	public $num;
	public $pageID;
	public $total_page;
	public $linkhead;
	public $others;

	public function PageBar($total, $onepage, $form_vars = '')
	{
		global $_GET;
		$pageID = $_GET['pageID'];
		$this->total = $total;
		$this->onepage = $onepage;
		$this->total_page = @ceil($total / $onepage);

		if (empty($pageID)) {
			$this->pageID = 1;
			$this->offset = 0;
		}
		else {
			$this->pageID = $pageID;
			$this->offset = ($pageID - 1) * $onepage;
		}

		if (!empty($form_vars)) {
			$vars = explode('|', $form_vars);
			$chk = $vars[0];
			$chk_value = $_POST['$chk'];

			if (empty($chk_value)) {
				$formlink = '';
			}
			else {
				$i = 0;

				for (; $i < sizeof($vars); $i++) {
					$var = $vars[$i];
					$value = $_POST['$var'];
					$addchar = $vars . '=' . $value;
					$addstr = $addstr . $addchar . '&';
				}

				$formlink = '&' . substr($addstr, 0, sizeof($addstr) - 1);
			}
		}
		else {
			$formlink = '';
		}

		$linkarr = explode('&pageID=', $_SERVER['QUERY_STRING']);
		$linkft = $linkarr[0];

		if (empty($linkft)) {
			$this->linkhead = $_SERVER['PHP_SELF'] . '?' . $formlink;
		}
		else {
			$this->linkhead = $_SERVER['PHP_SELF'] . '?' . $linkft . $formlink;
		}
	}

	public function pre_page($char = '', $others = '', $ActionHTML = '', $createTime = '')
	{
		global $_GET;
		$_obf_8Tw8_ukyKlE_ = $this->linkhead;
		$_obf_tHI_utn8 = $this->pageID;

		if (1 < $_obf_tHI_utn8) {
			$_obf_jJN2dIwrItw_ = $_obf_tHI_utn8 - 1;
			return '<a href="' . $_obf_8Tw8_ukyKlE_ . '&pageID=' . $_obf_jJN2dIwrItw_ . $others . '">' . $char . '</a>';
		}
		else {
			return '';
		}
	}

	public function next_page($char = '', $others = '', $ActionHTML = '', $createTime = '')
	{
		global $_GET;
		$_obf_8Tw8_ukyKlE_ = $this->linkhead;
		$_obf_LCLciAW9muxrjw__ = $this->total_page;
		$_obf_tHI_utn8 = $this->pageID;

		if ($_obf_tHI_utn8 < $_obf_LCLciAW9muxrjw__) {
			$_obf_s87n7sqPKxJi = $_obf_tHI_utn8 + 1;
			return '<a href="' . $_obf_8Tw8_ukyKlE_ . '&pageID=' . $_obf_s87n7sqPKxJi . $others . '">' . $char . '</a>';
		}
		else {
			return '';
		}
	}

	public function num_bar($num = '', $color = '', $others = '', $ActionHTML = '', $createTime = '', $left = '', $right = '')
	{
		global $_GET;
		$num = (empty($num) ? 10 : $num);
		$this->num = $num;
		$mid = floor($num / 2);
		$last = $num - 1;
		$pageID = $this->pageID;
		$totalpage = $this->total_page;
		$linkhead = $this->linkhead;
		$left = (empty($left) ? '' : $left);
		$right = (empty($right) ? '' : $right);
		$color = (empty($color) ? '#ff0000' : $color);
		$minpage = (($pageID - $mid) < 1 ? 1 : $pageID - $mid);
		$maxpage = $minpage + $last;

		if ($totalpage < $maxpage) {
			$maxpage = $totalpage;
			$minpage = $maxpage - $last;
			$minpage = ($minpage < 1 ? 1 : $minpage);
		}

		$i = $minpage;

		for (; $i <= $maxpage; $i++) {
			$char = $left . $i . $right;

			if ($i == $pageID) {
				$linkchar = '<strong>' . $char . '</strong>';
			}
			else {
				$linkchar = '<a href=\'' . $linkhead . '&pageID=' . $i . $others . '\'>' . $char . '</a>';
			}

			$linkbar = $linkbar . $linkchar;
		}

		return $linkbar;
	}

	public function pre_group($char = '', $others = '', $ActionHTML = '', $createTime = '')
	{
		global $_GET;
		$_obf_tHI_utn8 = $this->pageID;
		$_obf_8Tw8_ukyKlE_ = $this->linkhead;
		$_obf_Ybai = $this->num;
		$_obf_T1Ej = floor($_obf_Ybai / 2);
		$_obf_oNeAXWrVAQ__ = (($_obf_tHI_utn8 - $_obf_T1Ej) < 1 ? 1 : $_obf_tHI_utn8 - $_obf_T1Ej);
		$_obf_2nHOYyhb_f4_ = 1;

		if ($_obf_tHI_utn8 != $_obf_oNeAXWrVAQ__) {
			$_obf_vRdKH_nB_t0_ = '<a href=\'' . $_obf_8Tw8_ukyKlE_ . '&pageID=' . $_obf_2nHOYyhb_f4_ . $others . '\'>' . $char . '</a>';
		}
		else {
			$_obf_vRdKH_nB_t0_ = '<a href=javascript:void(0)>' . $char . '</a>';
		}

		return $_obf_vRdKH_nB_t0_;
	}

	public function next_group($char = '', $others = '', $ActionHTML = '', $createTime = '')
	{
		global $_GET;
		$_obf_tHI_utn8 = $this->pageID;
		$_obf_8Tw8_ukyKlE_ = $this->linkhead;
		$_obf_rOMLG2cqf8lR = $this->total_page;
		$_obf_Ybai = $this->num;
		$_obf_T1Ej = floor($_obf_Ybai / 2);
		$_obf_9NxvMQ__ = $_obf_Ybai;
		$_obf_oNeAXWrVAQ__ = (($_obf_tHI_utn8 - $_obf_T1Ej) < 1 ? 1 : $_obf_tHI_utn8 - $_obf_T1Ej);
		$_obf_QGcbNf7YwQ__ = $_obf_oNeAXWrVAQ__ + $_obf_9NxvMQ__;

		if ($_obf_rOMLG2cqf8lR < $_obf_QGcbNf7YwQ__) {
			$_obf_QGcbNf7YwQ__ = $_obf_rOMLG2cqf8lR;
			$_obf_oNeAXWrVAQ__ = $_obf_QGcbNf7YwQ__ - $_obf_9NxvMQ__;
			$_obf_oNeAXWrVAQ__ = ($_obf_oNeAXWrVAQ__ < 1 ? 1 : $_obf_oNeAXWrVAQ__);
		}

		$_obf_t1n0S5lI1P8_ = $_obf_rOMLG2cqf8lR;
		if (($_obf_tHI_utn8 == $_obf_QGcbNf7YwQ__) || ($_obf_QGcbNf7YwQ__ == 0)) {
			$_obf_vRdKH_nB_t0_ = '<a href=javascript:void(0)>' . $char . '</a>';
		}
		else {
			$_obf_vRdKH_nB_t0_ = '<a href=\'' . $_obf_8Tw8_ukyKlE_ . '&pageID=' . $_obf_t1n0S5lI1P8_ . $others . '\'>' . $char . '</a>';
		}

		return $_obf_vRdKH_nB_t0_;
	}

	public function whole_num_bar($num = '', $color = '', $others = '', $ActionHTML = '', $createTime = '')
	{
		global $lang;
		$_obf_QJXxYoQ5kA__ = $this->num_bar($num, $color, $others, $ActionHTML, $createTime);
		$_obf__s1DEdNS1_4a = $this->pre_group($lang['first_page'], $others, $ActionHTML, $createTime);
		$_obf_jJN2dIwrItw_ = $this->pre_page($lang['prev_page'], $others, $ActionHTML, $createTime);
		$_obf_s87n7sqPKxJi = $this->next_page($lang['next_page'], $others, $ActionHTML, $createTime);
		$_obf_IHRZuDT49EpGgw__ = $this->next_group($lang['last_page'], $others, $ActionHTML, $createTime);
		return $_obf__s1DEdNS1_4a . $_obf_jJN2dIwrItw_ . $_obf_QJXxYoQ5kA__ . $_obf_s87n7sqPKxJi . $_obf_IHRZuDT49EpGgw__;
	}
}


?>
