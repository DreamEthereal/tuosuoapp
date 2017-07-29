<?php
//dezend by http://www.yunlu99.com/
class Wxapi
{
	private $app_id = '';
	private $app_mchid = '';
	private $nick_name = '';
	private $send_name = '';
	private $min_value = 0;
	private $max_value = 0;
	private $wishing = '';
	private $act_name = '';

	public function __construct()
	{
	}

	public function pay($openid, $remark = '', $total_amount = 0)
	{
		global $WeChat;
		$this->app_mchid = $WeChat['mch_id'];
		$this->app_id = $WeChat['wxappid'];
		$this->nick_name = iconv('gbk', 'UTF-8', $WeChat['nick_name']);
		$this->send_name = iconv('gbk', 'UTF-8', $WeChat['send_name']);
		$this->min_value = $WeChat['min_value'];
		$this->max_value = $WeChat['max_value'];
		$this->wishing = iconv('gbk', 'UTF-8', $WeChat['wishing']);
		$this->act_name = iconv('gbk', 'UTF-8', $WeChat['act_name']);
		include_once 'WeChatHongBaoIn.php';
		$commonUtil = new CommonUtil();
		$WeChatHongBaoIn = new WeChatHongBaoIn();
		$WeChatHongBaoIn->setParameter('nonce_str', $this->great_rand());
		$WeChatHongBaoIn->setParameter('mch_billno', $this->app_mchid . date('YmdHis') . rand(1000, 9999));
		$WeChatHongBaoIn->setParameter('mch_id', $this->app_mchid);
		$WeChatHongBaoIn->setParameter('wxappid', $this->app_id);
		$WeChatHongBaoIn->setParameter('nick_name', $this->nick_name);
		$WeChatHongBaoIn->setParameter('send_name', $this->send_name);
		$WeChatHongBaoIn->setParameter('re_openid', $openid);
		$WeChatHongBaoIn->setParameter('total_amount', $total_amount);
		$WeChatHongBaoIn->setParameter('min_value', $total_amount);
		$WeChatHongBaoIn->setParameter('max_value', $total_amount);
		$WeChatHongBaoIn->setParameter('total_num', 1);
		$WeChatHongBaoIn->setParameter('wishing', $this->wishing);
		$WeChatHongBaoIn->setParameter('client_ip', $this->get_client_ip());
		$WeChatHongBaoIn->setParameter('act_name', $this->act_name);
		$WeChatHongBaoIn->setParameter('remark', iconv('gbk', 'UTF-8', $remark));
		$postXml = $WeChatHongBaoIn->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $WeChatHongBaoIn->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		return $responseObj->return_code;
	}

	public function great_rand()
	{
		$_obf_R2_b = '1234567890abcdefghijklmnopqrstuvwxyz';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < 30; $_obf_7w__++) {
			$_obf_XA__ = rand(0, 35);
			$_obf_98A_ .= $_obf_R2_b[$_obf_XA__];
		}

		return $_obf_98A_;
	}

	public function get_client_ip()
	{
		if (isset($_SERVER)) {
			if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) {
				$_obf_zeUCF4yH = $_SERVER[HTTP_X_FORWARDED_FOR];
			}
			else if (isset($_SERVER[HTTP_CLIENT_IP])) {
				$_obf_zeUCF4yH = $_SERVER[HTTP_CLIENT_IP];
			}
			else {
				$_obf_zeUCF4yH = $_SERVER[REMOTE_ADDR];
			}
		}
		else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$_obf_zeUCF4yH = getenv('HTTP_X_FORWARDED_FOR');
		}
		else if (getenv('HTTP_CLIENT_IP')) {
			$_obf_zeUCF4yH = getenv('HTTP_CLIENT_IP');
		}
		else {
			$_obf_zeUCF4yH = getenv('REMOTE_ADDR');
		}

		return preg_replace('/&amp;((#(\\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), preg_replace('/%27/', '\\\'', addslashes($_obf_zeUCF4yH))));
	}
}


?>
