<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

header('Cache-Control: public');
header('Pragma: cache');
$offset = 2592000;
$ExpStr = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
$LmStr = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT';
header($ExpStr);
header($LmStr);
header('Content-Type: text/javascr¨©pt; charset: UTF-8');
echo '' . "\r\n" . 'function selRadioCheckRows(objIdStr,objOrder)' . "\r\n" . '{' . "\r\n" . '	var objId = eval("document.form."+objIdStr);' . "\r\n" . '	if( objId.length == null ) ' . "\r\n" . '	{' . "\r\n" . '		if( objId.disabled != true )' . "\r\n" . '		{' . "\r\n" . '			objId.checked = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		if( objId[objOrder].disabled != true )' . "\r\n" . '		{' . "\r\n" . '			objId[objOrder].checked = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function selCheckBoxCheckRows(objIdStr,objOrder)' . "\r\n" . '{' . "\r\n" . '	var objId = eval("document.form."+objIdStr);' . "\r\n" . '	if( objId.length == null ) ' . "\r\n" . '	{' . "\r\n" . '		if( objId.disabled != true )' . "\r\n" . '		{' . "\r\n" . '			objId.checked = ! objId.checked;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		if( objId[objOrder].disabled != true )' . "\r\n" . '		{' . "\r\n" . '			objId[objOrder].checked = ! objId[objOrder].checked;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '';

?>
