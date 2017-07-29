<?php
//dezend by http://www.yunlu99.com/
echo 'function combrange_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for( var question_range_optionID in OptionListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		for( var question_range_labelID in LabelListArray[questionID] )' . "\r\n" . '		{' . "\r\n" . '			this_fields_list += \'option_\'+questionID+\'_\'+question_range_optionID+\'_\'+question_range_labelID+\'|\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}';

?>
