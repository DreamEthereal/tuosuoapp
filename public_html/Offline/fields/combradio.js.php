<?php
//dezend by http://www.yunlu99.com/
echo 'function combradio_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	for( var question_radioID in RadioListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		if( RadioListArray[questionID][question_radioID].isHaveText == 1 )' . "\r\n" . '		{' . "\r\n" . '			this_fields_list += \'TextOtherValue_\'+questionID+\'_\'+question_radioID+\'|\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}';

?>
