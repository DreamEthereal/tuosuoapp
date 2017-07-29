<?php
//dezend by http://www.yunlu99.com/
echo 'function combtext_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for( var question_yesnoID in YesNoListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+question_yesnoID+\'|\';' . "\r\n" . '  		if( QtnListArray[questionID].isHaveUnkown == 2 )  //²»Çå³þ' . "\r\n" . '		{' . "\r\n" . '			this_fields_list += \'isHaveUnkown_\'+questionID+\'_\'+question_yesnoID+\'|\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}';

?>
