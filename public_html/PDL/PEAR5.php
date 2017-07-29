<?php
//dezend by http://www.yunlu99.com/
class PEAR5
{
	static public function getStaticProperty($class, $var)
	{
		static $properties;

		if (!isset($properties[$class])) {
			$properties[$class] = array();
		}

		if (!array_key_exists($var, $properties[$class])) {
			$properties[$class][$var] = NULL;
		}

		return $properties[$class][$var];
	}
}


?>
