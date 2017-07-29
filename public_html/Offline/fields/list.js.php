<?php
//dezend by http://www.yunlu99.com/
echo 'function list_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for(var i=1;i<=QtnListArray[questionID].rows;i++)' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+i+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
