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
echo '' . "\r\n" . '//显示隐藏左栏' . "\r\n" . '$(\'.sidebar_show\').live(\'click\',function(e){' . "\r\n" . '	var ml = $(\'.sider_bar\').css(\'margin-left\');' . "\r\n" . '	if(ml == \'-217px\'){' . "\r\n" . '		$(\'.sider_bar\').css(\'margin-left\',\'35px\');' . "\r\n" . '	}else{' . "\r\n" . '		$(\'.sider_bar\').css(\'margin-left\',\'-217px\');' . "\r\n" . '	}' . "\r\n" . '});' . "\r\n" . '' . "\r\n" . '//菜单下拉' . "\r\n" . 'var length = $(\'.header_menu\').children().length;' . "\r\n" . 'for(var i = 1;i <= length;i++){' . "\r\n" . '	var menu = $(\'.header_m_\' + i);' . "\r\n" . '	var islist = $(menu).find(\'.head_menu_list\').length;' . "\r\n" . '	if(islist){		' . "\r\n" . '		$(menu).find(\'.head_menu_icon\').show();' . "\r\n" . '	}else{		' . "\r\n" . '		$(menu).find(\'.head_menu_icon\').hide();' . "\r\n" . '	}' . "\r\n" . '	$(menu).mouseover(function(e){' . "\r\n" . '		$(this).find(\'.head_menu_list\').show();' . "\r\n" . '	});' . "\r\n" . '	$(menu).mouseout(function(e){' . "\r\n" . '		$(this).find(\'.head_menu_list\').hide();' . "\r\n" . '	});' . "\r\n" . '' . "\r\n" . '}	' . "\r\n" . '' . "\r\n" . '//左侧菜单显隐' . "\r\n" . '/*$(\'.sider_menu\').each(function(e){' . "\r\n" . '	$(this).click(function(){' . "\r\n" . '		$(this).find(\'.sider_menu_list\').toggle();		' . "\r\n" . '	});' . "\r\n" . '});*/' . "\r\n" . '' . "\r\n" . 'function menuToggle(eid)' . "\r\n" . '{' . "\r\n" . '	/*var obj = document.getElementById(eid);' . "\r\n" . '	obj.style.display=(obj.style.display==\'none\')?\'\':\'none\';*/' . "\r\n" . '}' . "\r\n" . '';

?>
