<?php
//dezend by http://www.yunlu99.com/
echo 'function ajaxSubmit(url,postStr) {' . "\r\n" . '	var ajax=false; ' . "\r\n" . '	try { ajax = new ActiveXObject("Msxml2.XMLHTTP"); }' . "\r\n" . '	catch (e) { try { ajax = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { ajax = false; } }' . "\r\n" . '	if (!ajax && typeof XMLHttpRequest!=\'undefined\') ajax = new XMLHttpRequest(); ' . "\r\n" . '' . "\r\n" . '	ajax.open("POST", url, true); ' . "\r\n" . '	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); ' . "\r\n" . '	ajax.send(postStr);' . "\r\n" . '	ajax.onreadystatechange = function(){' . "\r\n" . '	  if( ajax.readyState == 4 && ajax.status == 200)' . "\r\n" . '	  {' . "\r\n" . '		  if(ajax.responseText != \'true\')' . "\r\n" . '		  {' . "\r\n" . '			  ajaxRtnTxt = ajax.responseText.split(\'|\');' . "\r\n" . '			  alert(ajaxRtnTxt[1]);' . "\r\n" . '		  }' . "\r\n" . '	  }' . "\r\n" . '	} ' . "\r\n" . '}';

?>
