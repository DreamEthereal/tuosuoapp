<?php
//dezend by http://www.yunlu99.com/
echo 'function weight_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for( var question_rankID in RankListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+question_rankID+\'|\';' . "\r\n" . '	}' . "\r\n" . '	if(QtnListArray[questionID].isHaveOther == 1)' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'TextOtherValue_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
