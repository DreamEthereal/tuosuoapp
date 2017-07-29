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
echo '' . "\r\n" . '//判断排序' . "\r\n" . 'function CheckRank(questionID,strText,isRequired,minOption,maxOption)' . "\r\n" . '{' . "\r\n" . '	if( isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		var isOrder = 0;' . "\r\n" . '		$("#qtn_table_"+questionID).find("td.optcont").each(function(){' . "\r\n" . '			var v = $(this).find("input.sortinput").val();' . "\r\n" . '			if(	v != \'\' ){' . "\r\n" . '				isOrder++;' . "\r\n" . '			}' . "\r\n" . '		});' . "\r\n" . '        if( minOption != 0 )' . "\r\n" . '		{		' . "\r\n" . '			if( isOrder < minOption)' . "\r\n" . '			{' . "\r\n" . '				$.notification( strText + "最少必须进行" + minOption + "项排序");' . "\r\n" . '				return false;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '		   if( isOrder < 1 )' . "\r\n" . '		   {' . "\r\n" . '				$.notification( strText + "最少必须进行1项排序");' . "\r\n" . '				return false;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '        if( maxOption != 0 )' . "\r\n" . '		{		' . "\r\n" . '			if( isOrder > maxOption)' . "\r\n" . '			{' . "\r\n" . '				$.notification( strText + "最多只能进行" + maxOption + "项排序");' . "\r\n" . '				return false;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	return true;' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//移除排序题的值' . "\r\n" . 'function RankUnInput(questionID)' . "\r\n" . '{' . "\r\n" . '	$("#qtn_table_"+questionID).find("td.optcont").each(function(){' . "\r\n" . '		$(this).find("input.sortinput").val(\'\');' . "\r\n" . '	});' . "\r\n" . '}' . "\r\n" . '';

?>
