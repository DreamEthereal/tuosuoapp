<?php
//dezend by http://www.yunlu99.com/
echo 'function combcheckbox_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	for( var question_checkboxID in CheckBoxListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		if( CheckBoxListArray[questionID][question_checkboxID].isHaveText == 1 )' . "\r\n" . '		{' . "\r\n" . '			this_fields_list += \'TextOtherValue_\'+questionID+\'_\'+question_checkboxID+\'|\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}';

?>
