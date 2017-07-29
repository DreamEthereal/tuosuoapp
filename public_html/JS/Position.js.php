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
echo '' . "\r\n" . '    function SetPosCookie(sName, sValue) ' . "\r\n" . '    { ' . "\r\n" . '        date = new Date(); ' . "\r\n" . '        s = date.getDate(); ' . "\r\n" . '        date.setDate(s+1);            //expire time is one month late!, and can\'t be current date! ' . "\r\n" . '        document.cookie = sName + "=" + escape(sValue) + "; expires=" + date.toGMTString(); ' . "\r\n" . '    } ' . "\r\n" . '    function GetPosCookie(sName) ' . "\r\n" . '    { ' . "\r\n" . '        // cookies are separated by semicolons ' . "\r\n" . '        var aCookie = document.cookie.split("; "); ' . "\r\n" . '        for (var i=0; i < aCookie.length; i++) ' . "\r\n" . '        { ' . "\r\n" . '			// a name/value pair (a crumb) is separated by an equal sign ' . "\r\n" . '			var aCrumb = aCookie[i].split("="); ' . "\r\n" . '			if (sName == aCrumb[0]) { ' . "\r\n" . '				return unescape(aCrumb[1]);} ' . "\r\n" . '        } ' . "\r\n" . '         ' . "\r\n" . '        // a cookie with the requested name does not exist ' . "\r\n" . '        return null; ' . "\r\n" . '    } ' . "\r\n" . '' . "\r\n" . '    function fnLoad(page) ' . "\r\n" . '    { ' . "\r\n" . '        document.documentElement.scrollLeft = GetPosCookie("scrollLeft_"+page); ' . "\r\n" . '        document.documentElement.scrollTop = GetPosCookie("scrollTop_"+page); ' . "\r\n" . '    } ' . "\r\n" . '' . "\r\n" . '    function fnUnload(page) ' . "\r\n" . '    { ' . "\r\n" . '        SetPosCookie("scrollLeft_"+page, document.documentElement.scrollLeft);' . "\r\n" . '        SetPosCookie("scrollTop_"+page, document.documentElement.scrollTop);' . "\r\n" . '    } ' . "\r\n" . '' . "\r\n" . '	//addEvent(window, "load", fnLoad);' . "\r\n" . '	//addEvent(window, "unload", fnUnload);' . "\r\n" . '    //window.onload = fnLoad; ' . "\r\n" . '    //window.onunload = fnUnload; ';

?>
