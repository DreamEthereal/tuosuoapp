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
header('Content-Type: text/javascr��pt; charset: UTF-8');
echo '' . "\n" . '/**' . "\n" . ' * COMMON DHTML FUNCTIONS' . "\n" . ' * These are handy functions I use all the time.' . "\n" . ' *' . "\n" . ' * By Seth Banks (webmaster at subimage dot com)' . "\n" . ' * http://www.subimage.com/' . "\n" . ' *' . "\n" . ' * Up to date code can be found at http://www.subimage.com/dhtml/' . "\n" . ' *' . "\n" . ' * This code is free for you to use anywhere, just keep this comment block.' . "\n" . ' */' . "\n" . '' . "\n" . '/**' . "\n" . ' * X-browser event handler attachment and detachment' . "\n" . ' * TH: Switched first true to false per http://www.onlinetools.org/articles/unobtrusivejavascript/chapter4.html' . "\n" . ' *' . "\n" . ' * @argument obj - the object to attach event to' . "\n" . ' * @argument evType - name of the event - DONT ADD "on", pass only "mouseover", etc' . "\n" . ' * @argument fn - function to call' . "\n" . ' */' . "\n" . 'function addEvent(obj, evType, fn){' . "\n" . ' if (obj.addEventListener){' . "\n" . '    obj.addEventListener(evType, fn, false);' . "\n" . '    return true;' . "\n" . ' } else if (obj.attachEvent){' . "\n" . '    var r = obj.attachEvent("on"+evType, fn);' . "\n" . '    return r;' . "\n" . ' } else {' . "\n" . '    return false;' . "\n" . ' }' . "\n" . '}' . "\n" . 'function removeEvent(obj, evType, fn, useCapture){' . "\n" . '  if (obj.removeEventListener){' . "\n" . '    obj.removeEventListener(evType, fn, useCapture);' . "\n" . '    return true;' . "\n" . '  } else if (obj.detachEvent){' . "\n" . '    var r = obj.detachEvent("on"+evType, fn);' . "\n" . '    return r;' . "\n" . '  } else {' . "\n" . '    alert("Handler could not be removed");' . "\n" . '  }' . "\n" . '}' . "\n" . '' . "\n" . '/**' . "\n" . ' * Code below taken from - http://www.evolt.org/article/document_body_doctype_switching_and_more/17/30655/' . "\n" . ' *' . "\n" . ' * Modified 4/22/04 to work with Opera/Moz (by webmaster at subimage dot com)' . "\n" . ' *' . "\n" . ' * Gets the full width/height because it\'s different for most browsers.' . "\n" . ' */' . "\n" . 'function getViewportHeight() {' . "\n" . '	if (window.innerHeight!=window.undefined) return window.innerHeight;' . "\n" . '	if (document.compatMode==\'CSS1Compat\') return document.documentElement.clientHeight;' . "\n" . '	if (document.body) return document.body.clientHeight; ' . "\n" . '' . "\n" . '	return window.undefined; ' . "\n" . '}' . "\n" . 'function getViewportWidth() {' . "\n" . '	var offset = 17;' . "\n" . '	var width = null;' . "\n" . '	if (window.innerWidth!=window.undefined) return window.innerWidth; ' . "\n" . '	if (document.compatMode==\'CSS1Compat\') return document.documentElement.clientWidth; ' . "\n" . '	if (document.body) return document.body.clientWidth; ' . "\n" . '}' . "\n" . '' . "\n" . '/**' . "\n" . ' * Gets the real scroll top' . "\n" . ' */' . "\n" . 'function getScrollTop() {' . "\n" . '	if (self.pageYOffset) // all except Explorer' . "\n" . '	{' . "\n" . '		return self.pageYOffset;' . "\n" . '	}' . "\n" . '	else if (document.documentElement && document.documentElement.scrollTop)' . "\n" . '		// Explorer 6 Strict' . "\n" . '	{' . "\n" . '		return document.documentElement.scrollTop;' . "\n" . '	}' . "\n" . '	else if (document.body) // all other Explorers' . "\n" . '	{' . "\n" . '		return document.body.scrollTop;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function getScrollLeft() {' . "\n" . '	if (self.pageXOffset) // all except Explorer' . "\n" . '	{' . "\n" . '		return self.pageXOffset;' . "\n" . '	}' . "\n" . '	else if (document.documentElement && document.documentElement.scrollLeft)' . "\n" . '		// Explorer 6 Strict' . "\n" . '	{' . "\n" . '		return document.documentElement.scrollLeft;' . "\n" . '	}' . "\n" . '	else if (document.body) // all other Explorers' . "\n" . '	{' . "\n" . '		return document.body.scrollLeft;' . "\n" . '	}' . "\n" . '}' . "\n" . '';

?>