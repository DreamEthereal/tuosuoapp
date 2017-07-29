<?php
//dezend by http://www.yunlu99.com/
class WeChatHongBaoIn
{
	public $parameters;

	public function __construct()
	{
	}

	public function setParameter($parameter, $parameterValue)
	{
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}

	public function getParameter($parameter)
	{
		return $this->parameters[$parameter];
	}

	public function check_sign_parameters()
	{
		if (($this->parameters['nonce_str'] == NULL) || ($this->parameters['mch_billno'] == NULL) || ($this->parameters['mch_id'] == NULL) || ($this->parameters['wxappid'] == NULL) || ($this->parameters['nick_name'] == NULL) || ($this->parameters['send_name'] == NULL) || ($this->parameters['re_openid'] == NULL) || ($this->parameters['total_amount'] == NULL) || ($this->parameters['max_value'] == NULL) || ($this->parameters['total_num'] == NULL) || ($this->parameters['wishing'] == NULL) || ($this->parameters['client_ip'] == NULL) || ($this->parameters['act_name'] == NULL) || ($this->parameters['remark'] == NULL) || ($this->parameters['min_value'] == NULL)) {
			return false;
		}

		return true;
	}

	protected function get_sign()
	{
		global $WeChat;
		define('PARTNERKEY', $WeChat['apikey']);

		try {
			if ((NULL == PARTNERKEY) || (PARTNERKEY == '')) {
				throw new SDKRuntimeException('密钥不能为空！' . '<br>');
			}

			if ($this->check_sign_parameters() == false) {
				throw new SDKRuntimeException('生成签名参数缺失！' . '<br>');
			}

			$_obf_JPzHMO6e7uwLyQ__ = new CommonUtil();
			ksort($this->parameters);
			$_obf_eus2AhKigbveQmwVbf_4tA__ = $_obf_JPzHMO6e7uwLyQ__->formatQueryParaMap($this->parameters, false);
			$_obf_2Z_7HmoQwsZgroA_ = new MD5SignUtil();
			return $_obf_2Z_7HmoQwsZgroA_->sign($_obf_eus2AhKigbveQmwVbf_4tA__, $_obf_JPzHMO6e7uwLyQ__->trimString(PARTNERKEY));
		}
		catch (SDKRuntimeException $_obf_hA__) {
			exit($_obf_hA__->errorMessage());
		}
	}

	public function create_hongbao_xml($retcode = 0, $reterrmsg = 'ok')
	{
		try {
			$this->setParameter('sign', $this->get_sign());
			$_obf_JPzHMO6e7uwLyQ__ = new CommonUtil();
			return $_obf_JPzHMO6e7uwLyQ__->arrayToXml($this->parameters);
		}
		catch (SDKRuntimeException $_obf_hA__) {
			exit($_obf_hA__->errorMessage());
		}
	}

	public function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
	{
		global $Config;
		global $WeChat;
		$_obf_u_c_ = curl_init();
		curl_setopt($_obf_u_c_, CURLOPT_TIMEOUT, $second);
		curl_setopt($_obf_u_c_, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($_obf_u_c_, CURLOPT_URL, $url);
		curl_setopt($_obf_u_c_, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($_obf_u_c_, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($_obf_u_c_, CURLOPT_SSLCERT, $Config['absolutenessPath'] . 'PerUserData/hongbao/' . $WeChat['apiclient_cert_name']);
		curl_setopt($_obf_u_c_, CURLOPT_SSLKEY, $Config['absolutenessPath'] . 'PerUserData/hongbao/' . $WeChat['apiclient_key_name']);
		curl_setopt($_obf_u_c_, CURLOPT_CAINFO, $Config['absolutenessPath'] . 'PerUserData/hongbao/' . $WeChat['rootca_name']);

		if (1 <= count($aHeader)) {
			curl_setopt($_obf_u_c_, CURLOPT_HTTPHEADER, $aHeader);
		}

		curl_setopt($_obf_u_c_, CURLOPT_POST, 1);
		curl_setopt($_obf_u_c_, CURLOPT_POSTFIELDS, $vars);
		$_obf_6RYLWQ__ = curl_exec($_obf_u_c_);

		if ($_obf_6RYLWQ__) {
			curl_close($_obf_u_c_);
			return $_obf_6RYLWQ__;
		}
		else {
			$_obf_rixiYSg_ = curl_errno($_obf_u_c_);
			curl_close($_obf_u_c_);
			return false;
		}
	}
}

include_once 'Utilities.php';
include_once 'RunTime.php';
include_once 'Sign.php';

?>
