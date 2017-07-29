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
echo '' . "\r\n" . 'function _GetCookies(){' . "\r\n" . '  var membersName = getCookie("membersName");' . "\r\n" . '  if (membersName != null)' . "\r\n" . '  {' . "\r\n" . '     document.AJAXLoginForm.username.value = unescape(membersName);' . "\r\n" . '     document.AJAXLoginForm.remberme.checked = true;' . "\r\n" . '  }' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function getCookie(name) {' . "\r\n" . '    var dc = document.cookie;' . "\r\n" . '    var prefix = name + "=";' . "\r\n" . '    var begin = dc.indexOf("; " + prefix);' . "\r\n" . '    if (begin == -1) {' . "\r\n" . '        begin = dc.indexOf(prefix);' . "\r\n" . '        if (begin != 0) return null;' . "\r\n" . '    } else {' . "\r\n" . '        begin += 2;' . "\r\n" . '    }' . "\r\n" . '    var end = document.cookie.indexOf(";", begin);' . "\r\n" . '    if (end == -1) {' . "\r\n" . '        end = dc.length;' . "\r\n" . '    }' . "\r\n" . '    return unescape(dc.substring(begin + prefix.length, end));' . "\r\n" . '}' . "\r\n" . '';

?>
