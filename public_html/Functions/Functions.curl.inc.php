<?php
//dezend by http://www.yunlu99.com/
function get_url_content($url)
{
	if (extension_loaded('curl')) {
		$_obf_u_c_ = curl_init(trim($url));
		curl_setopt($_obf_u_c_, CURLOPT_HEADER, 0);
		curl_setopt($_obf_u_c_, CURLOPT_RETURNTRANSFER, 1);
		$_obf__WwKzYz1wA__ = curl_exec($_obf_u_c_);
		curl_close($_obf_u_c_);
	}
	else {
		$_obf__WwKzYz1wA__ = @file_get_contents(trim($url));
	}

	return $_obf__WwKzYz1wA__;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
