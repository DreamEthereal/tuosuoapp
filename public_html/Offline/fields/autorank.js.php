<?php
//dezend by http://www.yunlu99.com/
echo 'function autorank_fields(questionID)' . "\r\n" . '{' . "\r\n" . '	//来源问题及选项' . "\r\n" . '	var theBaseID = QtnListArray[questionID].baseID;' . "\r\n" . '	var theBaseQtnArray = QtnListArray[theBaseID];' . "\r\n" . '	var theCheckBoxListArray = CheckBoxListArray[theBaseQtnArray.questionID];' . "\r\n" . '	var optionAutoArray = [];' . "\r\n" . '	for( var question_checkboxID in theCheckBoxListArray )' . "\r\n" . '	{' . "\r\n" . '		optionAutoArray[question_checkboxID] = theCheckBoxListArray[question_checkboxID].optionName;' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( theBaseQtnArray.isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		optionAutoArray[0] = theBaseQtnArray.otherText;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	for( var optionAutoID in optionAutoArray )' . "\r\n" . '	{' . "\r\n" . '		this_fields_list += \'option_\'+questionID+\'_\'+optionAutoID+\'|\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
