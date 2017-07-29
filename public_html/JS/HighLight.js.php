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
header('Content-Type: text/javascrīpt; charset: UTF-8');
echo '' . "\r\n" . 'var currentlyActiveInputRef = false;' . "\r\n" . 'var currentlyActiveInputClassName = false;' . "\r\n" . '' . "\r\n" . 'function highlightActiveInput()' . "\r\n" . '{' . "\r\n" . '	if(currentlyActiveInputRef){' . "\r\n" . '		currentlyActiveInputRef.className = currentlyActiveInputClassName;' . "\r\n" . '	}' . "\r\n" . '	currentlyActiveInputClassName = this.className;' . "\r\n" . '	this.className = \'inputHighlighted\';' . "\r\n" . '	if(	this.value == "[在此输入问题题目]" || this.value == "[段落小节标题]")' . "\r\n" . '	this.value = \'\';' . "\r\n" . '	currentlyActiveInputRef = this;' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function blurActiveInput()' . "\r\n" . '{' . "\r\n" . '	this.className = currentlyActiveInputClassName;	' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function initInputHighlightScript()' . "\r\n" . '{' . "\r\n" . '	var tags = [\'INPUT\',\'TEXTAREA\'];' . "\r\n" . '	' . "\r\n" . '	for(tagCounter=0;tagCounter < tags.length;tagCounter++){' . "\r\n" . '		var inputs = document.getElementsByTagName(tags[tagCounter]);' . "\r\n" . '		for(var no=0;no < inputs.length;no++){' . "\r\n" . '			if(inputs[no].className && inputs[no].className==\'doNotHighlightThisInput\') continue;' . "\r\n" . '			' . "\r\n" . '			if(inputs[no].tagName.toLowerCase()==\'textarea\' || (inputs[no].tagName.toLowerCase()==\'input\' && inputs[no].type.toLowerCase()==\'text\') || (inputs[no].tagName.toLowerCase()==\'input\' && inputs[no].type.toLowerCase()==\'password\'))' . "\r\n" . '			{' . "\r\n" . '				inputs[no].onfocus = highlightActiveInput;' . "\r\n" . '				inputs[no].onblur = blurActiveInput;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '';

?>
