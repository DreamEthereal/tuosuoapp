<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
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
echo '' . "\r\n" . 'var isExtended = 0;' . "\r\n" . 'var sideHeight = 350;' . "\r\n" . 'var sideWidth = 200;' . "\r\n" . 'var slideDuration = 1000;' . "\r\n" . 'var opacityDuration = 1500;' . "\r\n" . 'var userAgent = navigator.userAgent.toLowerCase();' . "\r\n" . 'var is_opera = userAgent.indexOf(\'opera\') != -1 && opera.version();' . "\r\n" . 'var is_ie = (userAgent.indexOf(\'msie\') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf(\'msie\') + 5, 3);' . "\r\n" . '' . "\r\n" . 'function extendContract(){' . "\r\n" . '	if(isExtended == 0){' . "\r\n" . '		sideBarSlide(0, sideHeight, 0, sideWidth,isExtended);		' . "\r\n" . '		//sideBarOpacity(0, 1);	' . "\r\n" . '		isExtended = 1;		' . "\r\n" . '		//$(\'sideBarTab\').childNodes[0].src = $(\'sideBarTab\').childNodes[0].src.replace(/(\\.[^.]+)$/, \'-active$1\');' . "\r\n" . '		if( is_ie && (window.navigator.userAgent.indexOf(\'MSIE 5\')>-1 || window.navigator.userAgent.indexOf(\'MSIE 6\')>-1) )' . "\r\n" . '		{' . "\r\n" . '	    }' . "\r\n" . '		else' . "\r\n" . '	    {' . "\r\n" . '			$(\'#sideBarTab\').find("img:first").attr("src", $(\'#sideBarTab\').find("img:first").attr("src").replace(/(\\.[^.]+)$/, \'-active$1\'));' . "\r\n" . '		}' . "\r\n" . '		$(\'#sideBarContents\').css(\'display\',\'block\');' . "\r\n" . '		$(\'#sideBarContentsInner\').css(\'display\',\'block\');' . "\r\n" . '	}' . "\r\n" . '	else{' . "\r\n" . '		sideBarSlide(sideHeight, 0, sideWidth, 0,isExtended);		' . "\r\n" . '		//sideBarOpacity(1, 0);		' . "\r\n" . '		isExtended = 0;' . "\r\n" . '		//$(\'sideBarTab\').childNodes[0].src = $(\'sideBarTab\').childNodes[0].src.replace(/-active(\\.[^.]+)$/, \'$1\');' . "\r\n" . '		if( is_ie && (window.navigator.userAgent.indexOf(\'MSIE 5\')>-1 || window.navigator.userAgent.indexOf(\'MSIE 6\')>-1) )' . "\r\n" . '		{' . "\r\n" . '	    }' . "\r\n" . '		else' . "\r\n" . '	    {' . "\r\n" . '		    $(\'#sideBarTab\').find("img:first").attr("src",$(\'#sideBarTab\').find("img:first").attr("src").replace(/-active(\\.[^.]+)$/, \'$1\'));' . "\r\n" . '		}' . "\r\n" . '		if( is_ie )' . "\r\n" . '		{' . "\r\n" . '			setTimeout("hideSideBar()",1050);' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			setTimeout("hideSideBar()",900);' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function hideSideBar() {' . "\r\n" . '    $(\'#sideBarContents\').css(\'display\',\'none\');' . "\r\n" . '    $(\'#sideBarContentsInner\').css(\'display\',\'none\');' . "\r\n" . '}' . "\r\n" . 'function sideBarSlide(fromHeight, toHeight, fromWidth, toWidth,isExtended){' . "\r\n" . '	/* var myEffects = new Fx.Styles(\'sideBarContents\', {duration: slideDuration, transition: Fx.Transitions.linear});' . "\r\n" . '	   myEffects.custom({' . "\r\n" . '		 \'height\': [fromHeight, toHeight],' . "\r\n" . '		 \'width\': [fromWidth, toWidth]' . "\r\n" . '	});*/' . "\r\n" . '	if( is_ie )' . "\r\n" . '	{' . "\r\n" . '		$(\'#sideBarContents\').animate({height:toHeight,width:toWidth}, 1000);' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		/*if( isExtended == 0 )' . "\r\n" . '		{' . "\r\n" . '			$(\'#sideBarContents\').animate({height:toHeight,width:toWidth,opacity:[0,1]},1000);' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			$(\'#sideBarContents\').animate({height:toHeight,width:toWidth,opacity:[1,0]},1000);' . "\r\n" . '		}*/' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function sideBarOpacity(from, to){' . "\r\n" . '	/*var myEffects = new Fx.Styles(\'sideBarContents\', {duration: opacityDuration, transition: Fx.Transitions.linear});' . "\r\n" . '	myEffects.custom({' . "\r\n" . '	 \'opacity\': [from, to]' . "\r\n" . '	});*/' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function initSideBar(){' . "\r\n" . '	$(\'#sideBarTab\').bind(\'click\', function(){extendContract()});' . "\r\n" . '}';

?>
