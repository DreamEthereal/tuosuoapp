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
echo '/*' . "\n" . ' * jQuery Notifications - v1.0' . "\n" . ' *' . "\n" . ' * Copyright 2011 Cory LaViska for A Beautiful Site, LLC. (http://abeautifulsite.net/)' . "\n" . ' *' . "\n" . ' * Dual licensed under the MIT or GPL Version 2 licenses' . "\n" . ' *' . "\n" . ' */' . "\n" . '(function ($) {' . "\n" . '' . "\n" . '	$.notification = function (message, settings) {' . "\n" . '		' . "\n" . '		if (message === undefined || message === null ) return;' . "\n" . '		' . "\n" . '		// Merge settings with defaults' . "\n" . '		settings = $.extend(true, {' . "\n" . '			className: \'jquery-notification\',' . "\n" . '			duration: 2000,' . "\n" . '			freezeOnHover: false,' . "\n" . '			hideSpeed: 500,' . "\n" . '			position: \'center\',' . "\n" . '			showSpeed: 250,' . "\n" . '			zIndex: 99999' . "\n" . '		}, settings);' . "\n" . '' . "\n" . '		// Variables' . "\n" . '		var width, height, top, left, windowWidth = $(window).width(),' . "\n" . '			windowHeight = $(window).height(),' . "\n" . '			scrollTop = $(window).scrollTop(),' . "\n" . '			scrollLeft = $(window).scrollLeft(),' . "\n" . '			timeout,' . "\n" . '			notification = $(\'<div id="jquery-notification" />\');' . "\n" . '' . "\n" . '		// Skip the animation if a notification is already showing' . "\n" . '		if ($(\'#jquery-notification\').length > 0) settings.showSpeed = 0;' . "\n" . '' . "\n" . '		// Clear old notifications' . "\n" . '		$(\'#jquery-notification\').remove();' . "\n" . '' . "\n" . '		// Create it' . "\n" . '		//var is_ie6 = navigator.appVersion.indexOf(\'MSIE 6\')>-1;' . "\n" . '		var is_ie6 = !-[1,]&&!window.XMLHttpRequest;' . "\n" . '		if( is_ie6 )' . "\n" . '		{' . "\n" . '			//����Select' . "\n" . '			var obj = document.getElementsByTagName("select");' . "\n" . '			for (var i=0; i<obj.length; i++)' . "\n" . '			{' . "\n" . '				obj[i].style.visibility = "hidden";' . "\n" . '			}' . "\n" . '		}' . "\n" . '		notification.appendTo($(\'BODY\')).addClass(settings.className).text(message).css({' . "\n" . '			position: \'absolute\',' . "\n" . '			display: \'none\',' . "\n" . '			zIndex: settings.zIndex' . "\n" . '		}).mouseover(function () {' . "\n" . '			if (settings.freezeOnHover) clearTimeout(timeout);' . "\n" . '			$(this).addClass(settings.className + \'-hover\');' . "\n" . '		}).mouseout(function () {' . "\n" . '			$(this).removeClass(settings.className + \'-hover\');' . "\n" . '			if (settings.freezeOnHover) {' . "\n" . '				timeout = setTimeout(function () {' . "\n" . '					notification.trigger(\'click\');' . "\n" . '				}, settings.duration);' . "\n" . '			}' . "\n" . '		}).click(function () {' . "\n" . '			clearTimeout(timeout);' . "\n" . '			notification.fadeOut(settings.hideSpeed, function () {' . "\n" . '				if( is_ie6 )' . "\n" . '				{' . "\n" . '					//��ʾSelect' . "\n" . '					for (var i=0; i<obj.length; i++)' . "\n" . '					{' . "\n" . '						obj[i].style.visibility = "visible";' . "\n" . '					}' . "\n" . '				}' . "\n" . '				//Remove' . "\n" . '				$(this).remove();' . "\n" . '			});' . "\n" . '		}).wrapInner(\'<div id="jquery-notification-message" />\');' . "\n" . '' . "\n" . '		// Position it' . "\n" . '		width = notification.outerWidth();' . "\n" . '		height = notification.outerHeight();' . "\n" . '' . "\n" . '		switch (settings.position) {' . "\n" . '		case \'top\':' . "\n" . '			top = 0 + scrollTop;' . "\n" . '			left = windowWidth / 2 - width / 2 + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'top-left\':' . "\n" . '			top = 0 + scrollTop;' . "\n" . '			left = 0 + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'top-right\':' . "\n" . '			top = 0 + scrollTop;' . "\n" . '			left = windowWidth - width + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'bottom\':' . "\n" . '			top = windowHeight - height + scrollTop;' . "\n" . '			left = windowWidth / 2 - width / 2 + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'bottom-left\':' . "\n" . '			top = windowHeight - height + scrollTop;' . "\n" . '			left = 0 + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'bottom-right\':' . "\n" . '			top = windowHeight - height + scrollTop;' . "\n" . '			left = windowWidth - width + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'left\':' . "\n" . '			top = windowHeight / 2 - height / 2 + scrollTop;' . "\n" . '			left = 0 + scrollLeft;' . "\n" . '			break;' . "\n" . '		case \'right\':' . "\n" . '			top = windowHeight / 2 - height / 2 + scrollTop;' . "\n" . '			left = windowWidth - width + scrollLeft;' . "\n" . '			break;' . "\n" . '		default:' . "\n" . '		case \'center\':' . "\n" . '			top = windowHeight / 2 - height / 2 + scrollTop;' . "\n" . '			left = windowWidth / 2 - width / 2 + scrollLeft;' . "\n" . '			break;' . "\n" . '		}' . "\n" . '' . "\n" . '		// Show it' . "\n" . '		notification.css({' . "\n" . '			top: top,' . "\n" . '			left: left' . "\n" . '		}).fadeIn(settings.showSpeed, function () {' . "\n" . '			// Hide it' . "\n" . '			timeout = setTimeout(function () {' . "\n" . '				notification.trigger(\'click\');' . "\n" . '			}, settings.duration);' . "\n" . '		});' . "\n" . '' . "\n" . '	};' . "\n" . '})(jQuery);' . "\n" . '';

?>
