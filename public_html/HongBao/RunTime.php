<?php
//dezend by http://www.yunlu99.com/
class SDKRuntimeException extends Exception
{
	public function errorMessage()
	{
		return $this->getMessage();
	}
}

?>
