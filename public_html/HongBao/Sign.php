<?php
//dezend by http://www.yunlu99.com/
class MD5SignUtil
{
	public function sign($content, $key)
	{
		try {
			if (NULL == $key) {
				throw new SDKRuntimeException('ǩ��key����Ϊ�գ�' . '<br>');
			}

			if (NULL == $content) {
				throw new SDKRuntimeException('ǩ�����ݲ���Ϊ��' . '<br>');
			}

			$_obf_Ru2N4ehllg__ = $content . '&key=' . $key;
			return strtoupper(md5($_obf_Ru2N4ehllg__));
		}
		catch (SDKRuntimeException $_obf_hA__) {
			exit($_obf_hA__->errorMessage());
		}
	}

	public function verifySignature($content, $sign, $md5Key)
	{
		$_obf_Ru2N4ehllg__ = $content . '&key=' . $md5Key;
		$_obf_Shj4NFh9mD7AyD9M9g__ = strtolower(md5($_obf_Ru2N4ehllg__));
		$_obf_ylN0u5Oxk2S6YA__ = strtolower($sign);
		return $_obf_Shj4NFh9mD7AyD9M9g__ == $_obf_ylN0u5Oxk2S6YA__;
	}
}


?>
