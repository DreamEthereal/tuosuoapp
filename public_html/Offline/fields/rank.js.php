<?php
//dezend by http://www.yunlu99.com/
echo 'function rank_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	for( var question_rankID in RankListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+question_rankID+\'|\';' . "\r\n" . '	}' . "\r\n" . '	if( QtnListArray[questionID].isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_0\'+\'|\';' . "\r\n" . '		this_fields_list += \'TextOtherValue_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '	if( QtnListArray[questionID].isHaveWhy == 1 )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'TextWhyValue_\'+questionID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
