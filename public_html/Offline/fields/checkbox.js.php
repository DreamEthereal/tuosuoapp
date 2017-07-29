<?php
//dezend by http://www.yunlu99.com/
echo 'function checkbox_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	if( QtnListArray[questionID].isSelect != 1 && QtnListArray[questionID].isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'TextOtherValue_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
