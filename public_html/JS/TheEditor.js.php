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
echo '' . "\r\n" . 'function doMyExecCommand(command) {	' . "\r\n" . '    var textEdit = document.getElementById(\'questionName\');' . "\r\n" . '	textEdit.focus();' . "\r\n" . '	edit = textEdit.document.selection.createRange();' . "\r\n" . '	if( edit.text )' . "\r\n" . '	{' . "\r\n" . '		if ( arguments[1] == null )	' . "\r\n" . '		{' . "\r\n" . '			switch(command)' . "\r\n" . '			{' . "\r\n" . '				case \'Bold\':' . "\r\n" . '					edit.text = \'<b>\'+edit.text+\'</b>\';' . "\r\n" . '				break;' . "\r\n" . '				case \'Italic\':' . "\r\n" . '					edit.text = \'<i>\'+edit.text+\'</i>\';' . "\r\n" . '				break;' . "\r\n" . '				case \'Underline\':' . "\r\n" . '					edit.text = \'<u>\'+edit.text+\'</u>\';' . "\r\n" . '				break;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			switch(command)' . "\r\n" . '			{' . "\r\n" . '				case \'FontSize\':' . "\r\n" . '					edit.text = \'<font style="font-size:\'+arguments[1]+\'">\'+edit.text+\'</font>\';' . "\r\n" . '				break;' . "\r\n" . '				case \'ForeColor\':' . "\r\n" . '					edit.text = \'<font style="color:\'+arguments[1]+\'">\'+edit.text+\'</font>\';' . "\r\n" . '				break;' . "\r\n" . '				case \'BackColor\':' . "\r\n" . '					edit.text = \'<font style="background-color:\'+arguments[1]+\'">\'+edit.text+\'</font>\';' . "\r\n" . '				break;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	textEdit.document.selection.empty();' . "\r\n" . '	textEdit.focus();' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function doMySelectClick(str, el) {' . "\r\n" . '	var Index = el.selectedIndex;' . "\r\n" . '	if (Index != 0){' . "\r\n" . '		el.selectedIndex = 0;' . "\r\n" . '		doMyFormat(str,el.options[Index].value);' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function doMyFormat(what) {' . "\r\n" . '	doMyExecCommand(what, arguments[1]);' . "\r\n" . '}' . "\r\n" . '';

?>
