<?php
//dezend by http://www.yunlu99.com/
echo 'function view_autoweight_data(questionID)' . "\r\n" . '{' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionName = \'<span class=red>*</span>\';' . "\r\n" . '	}' . "\r\n" . '	questionName += qQuotaChar(QtnListArray[questionID].questionName);' . "\r\n" . '	questionName += \'&nbsp;<span class=notes>[自动比重题]</span>\';' . "\r\n" . '	dataqtn = "{";' . "\r\n" . '	dataqtn += " questionName:\'"+qJsonCharFilter(questionName)+"\',";' . "\r\n" . '' . "\r\n" . '	//来源问题及选项' . "\r\n" . '	var theBaseID = QtnListArray[questionID].baseID;' . "\r\n" . '	var theBaseQtnArray = QtnListArray[theBaseID];' . "\r\n" . '	var theCheckBoxListArray = CheckBoxListArray[theBaseQtnArray.questionID];' . "\r\n" . '' . "\r\n" . '	var optionAutoArray = [];' . "\r\n" . '	var optionMinMaxArray = [];' . "\r\n" . '	var i=0;' . "\r\n" . '	for( var question_checkboxID in theCheckBoxListArray )' . "\r\n" . '	{' . "\r\n" . '		var theQuestionArray = theCheckBoxListArray[question_checkboxID];' . "\r\n" . '		optionAutoArray[question_checkboxID] = qJsonCharFilter(theQuestionArray.optionName);' . "\r\n" . '		optionMinMaxArray[i] = question_checkboxID;' . "\r\n" . '		i++;' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( theBaseQtnArray.isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		optionMinMaxArray[i] = 0;' . "\r\n" . '		var theOtherValue = dataRow.rows[0][dataIndex[\'TextOtherValue_\'+theBaseID]];' . "\r\n" . '		if( theOtherValue != \'\' )' . "\r\n" . '		{' . "\r\n" . '			optionAutoArray[0] = qJsonCharFilter(theBaseQtnArray.otherText+"("+theOtherValue+")");' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			optionAutoArray[0] = qJsonCharFilter(theBaseQtnArray.otherText);' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//显示题目选项' . "\r\n" . '	dataqtn += " qtns:[ ";' . "\r\n" . '	for( var tmp in optionMinMaxArray )' . "\r\n" . '	{' . "\r\n" . '		var optionAutoID = optionMinMaxArray[tmp];' . "\r\n" . '		var optionAutoName = optionAutoArray[optionAutoID];' . "\r\n" . '		dataqtn += "{ ";' . "\r\n" . '		dataqtn += "optionName:\'"+optionAutoName+"\',";' . "\r\n" . '		var the_question_range_answerID = \'option_\'+questionID+\'_\'+optionAutoID;' . "\r\n" . '		var the_question_range_answer_value = dataRow.rows[0][dataIndex[the_question_range_answerID]];' . "\r\n" . '		if( the_question_range_answer_value != \'\' && the_question_range_answer_value != \'0\' && the_question_range_answer_value != \'0.00\')' . "\r\n" . '		{' . "\r\n" . '			dataqtn += "optionAnswer:\'"+the_question_range_answer_value+"\'";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			dataqtn += "optionAnswer:\'跳过\'";' . "\r\n" . '		}' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '	}' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '' . "\r\n" . '	var tplText = \'<table width="100%"><tr><td class="question">{$questionName}</td></tr><tr><td><table cellSpacing=0 cellPadding=0>{foreach $qtns as $i => $qtn}<tr><td class="option">&nbsp;<span class="option">{$qtn.optionName}：</span><font color=red>{$qtn.optionAnswer}</font></td></tr>{/foreach}</table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '	var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '	var t = new jSmart(tplText);' . "\r\n" . '    survey_content_data_html += t.fetch(jsondata);' . "\r\n" . '	jsondata = null;' . "\r\n" . '	optionMinMaxArray = null;' . "\r\n" . '	optionAutoArray = null;' . "\r\n" . '}';

?>
