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
echo '' . "\r\n" . 'function resizeTextHeight(theObj,change) {' . "\r\n" . '    var theObjId = eval("document.getElementById(\'"+theObj+"\');");' . "\r\n" . '    var newheight = parseInt(theObjId.rows, 10) + change;   ' . "\r\n" . '    if( newheight >= 3 && newheight <= 16) {   ' . "\r\n" . '        theObjId.rows = newheight;   ' . "\r\n" . '    }' . "\r\n" . '}';

?>
