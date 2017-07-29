<?php
//dezend by http://www.yunlu99.com/
echo 'function text_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	if( QtnListArray[questionID].isHaveUnkown == 2 )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'isHaveUnkown_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
