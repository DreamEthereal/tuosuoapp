<?php
//dezend by http://www.yunlu99.com/
echo 'function ajax_Session_Submit(url,postStr) {' . "\r\n" . '	var ajax=false; ' . "\r\n" . '	try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '	catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '	if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '	ajax.open("POST", url, true); ' . "\r\n" . '	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '	ajax.send(postStr);' . "\r\n" . '	ajax.onreadystatechange = function(){} ' . "\r\n" . '}' . "\r\n" . '';

?>
