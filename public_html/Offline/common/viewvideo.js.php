<?php
//dezend by http://www.yunlu99.com/
echo 'window.onRexseeReady = function(){' . "\r\n" . '	rexseeMenu.setOptionsMenuId("mainOptionsMenu1");' . "\r\n" . '	rexseeSpecialKey.enableBackKeyListener(false);' . "\r\n" . '' . "\r\n" . '	if( !rexseeJavaView.exists(\'rightView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'rightView\',\'LinearLayout\',\'orientation:y;background-color:transparent;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.exists(\'videoLayout\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'videoLayout\',\'FrameLayout\',\'visibility:visible;height:fillparent;weight:1;background-color:#000000;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.exists(\'videoView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'videoView\',\'VideoView\',\'visibility:visible;event-touch:true;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.hasParent(\'videoView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.setChilds(\'videoLayout\',\'videoView\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.exists(\'buttonView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'buttonView\',\'LinearLayout\',\'visibility:visible;height:wrapcontent;background-color:transparent;padding:5 0 0 0;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.exists(\'buttonPlay\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'buttonPlay\',\'Button\',\'event-touch:true;label: �� �� ;border:0; height:38px; padding-left:10px;padding-right:10px;text-align:center; font-size:18px; font-weight:bold;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.hasParent(\'buttonPlay\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.setChilds(\'buttonView\',\'buttonPlay\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.hasParent(\'videoLayout\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.setChilds(\'rightView\',\'videoLayout\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.hasParent(\'buttonView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.setChilds(\'rightView\',\'buttonView\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.exists(\'rightViewDiv\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.create(\'rightViewDiv\',\'Div\',\'border-width:0;border-left-width:8px;border-left-color:#000000+#333333/2;background-color:#222222;;weight:1;\');' . "\r\n" . '	}' . "\r\n" . '	if( !rexseeJavaView.hasParent(\'rightView\') )' . "\r\n" . '	{' . "\r\n" . '		rexseeJavaView.setChilds(\'rightViewDiv\',\'rightView\');' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function onJavaViewClick(id,x,y){' . "\r\n" . '	if ( id=="buttonPlay" ){' . "\r\n" . '		stopCurrentSong();' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function startCurrentSong(path,loop){' . "\r\n" . '	rexseeJavaView.setStyle("buttonView","visibility:visible;");			' . "\r\n" . '	rexseeJavaView.setStyle("videoView","visibility:visible;");' . "\r\n" . '	rexseeJavaView.setStyle("videoView","visibility:visible;path:"+path+";mode:"+((loop)?"loop":"normal")+";");' . "\r\n" . '	rexseeJavaView.start("videoView");' . "\r\n" . '' . "\r\n" . '}' . "\r\n" . 'function stopCurrentSong(){' . "\r\n" . '	rexseeJavaView.stop("videoView");' . "\r\n" . '    rexseeJavaDialog.dismissAll();' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function showvideo(path){' . "\r\n" . '	rexseeJavaDialog.show(\'rightViewDiv\',\'window-align:center;window-vertical-align:middle;window-style:light;width:400;height:300;border-width:0px;\');' . "\r\n" . '    startCurrentSong(path,false);' . "\r\n" . '}';

?>