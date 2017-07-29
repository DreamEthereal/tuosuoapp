<?php
//dezend by http://www.yunlu99.com/
class Packet
{
	private $wxapi;

	public function _route($fun, $param = '', $remark = '', $amount = 0)
	{
		@require_once 'WeChatAuth.php';
		$this->wxapi = new Wxapi();

		switch ($fun) {
		case 'wxpacket':
			return $this->wxpacket($param, $remark, $amount);
			break;

		default:
			exit('EnableQ Security Violation');
		}
	}

	private function wxpacket($param, $remark = '', $amount = 0)
	{
		return $this->wxapi->pay($param['openid'], $remark, $amount);
	}
}


?>
