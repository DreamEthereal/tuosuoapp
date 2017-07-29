<?php
//dezend by http://www.yunlu99.com/
echo 'function range_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for( var question_range_optionID in OptionListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+question_range_optionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '	if( QtnListArray[questionID].isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'TextOtherValue_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
