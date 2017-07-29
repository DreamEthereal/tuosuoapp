<?php
//dezend by http://www.yunlu99.com/
echo 'document.writeln("<style>#preloading{position: absolute;top:expression(Math.max(document.body.clientHeight/2-150,80));left:expression(Math.max(document.body.clientWidth/2-120,150));border:#336699 2px solid;padding:5px;width:240px;text-align:center;z-index: 900;background-color:#f0f0f0;font-size:12px;}</style><div id=preloading>&nbsp;<img src=\'..\\/Images\\/wait.gif\' width=16 height=16 align=absmiddle>&nbsp;&nbsp;正在努力为您加载数据...</div>");' . "\r\n" . '' . "\r\n" . 'function closeDialog() {' . "\r\n" . '   document.getElementById(\'preloading\').style.display=\'none\';' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function adjustLodingStyle() {' . "\r\n" . '	document.getElementById(\'preloading\').style.top = Math.max(document.body.clientHeight/2-150,80);' . "\r\n" . '	document.getElementById(\'preloading\').style.left = Math.max(document.body.clientWidth/2-120,150);' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'adjustLodingStyle();';

?>
