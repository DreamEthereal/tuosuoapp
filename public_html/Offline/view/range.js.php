<?php
//dezend by http://www.yunlu99.com/
echo 'function view_range_data(questionID)' . "\r\n" . '{' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionName += \'<span class=red>*</span>\';' . "\r\n" . '	}' . "\r\n" . '	questionName += qQuotaChar(QtnListArray[questionID].questionName);' . "\r\n" . '	questionName += \'&nbsp;<span class=notes>[矩阵单选题]</span>\';' . "\r\n" . '	dataqtn = "{";' . "\r\n" . '	dataqtn += " questionName:\'"+qJsonCharFilter(questionName)+"\',";' . "\r\n" . '' . "\r\n" . '	//显示题目选项' . "\r\n" . '	var optionTotalNum = count(OptionListArray[questionID]);' . "\r\n" . '	var tmp = 0 ;' . "\r\n" . '	var lastOptionId = optionTotalNum - 1;' . "\r\n" . '	dataqtn += " qtns:[ ";' . "\r\n" . '	for( var question_range_optionID in OptionListArray[questionID] )' . "\r\n" . '	{' . "\r\n" . '		dataqtn += "{ ";' . "\r\n" . '		if( QtnListArray[questionID].isHaveOther != 1 )' . "\r\n" . '		{' . "\r\n" . '			dataqtn += "optionName:\'"+qJsonCharFilter(OptionListArray[questionID][question_range_optionID].optionName)+"\',";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			if( tmp != lastOptionId )' . "\r\n" . '			{' . "\r\n" . '				dataqtn += "optionName:\'"+qJsonCharFilter(OptionListArray[questionID][question_range_optionID].optionName)+"\',";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				var theOptionValue = dataRow.rows[0][dataIndex[\'TextOtherValue_\'+questionID]];' . "\r\n" . '				if( theOptionValue != \'\' )' . "\r\n" . '				{' . "\r\n" . '					dataqtn += "optionName:\'"+qJsonCharFilter(OptionListArray[questionID][question_range_optionID].optionName)+"<font color=red>("+theOptionValue+")</font>\',";' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					dataqtn += "optionName:\'"+qJsonCharFilter(OptionListArray[questionID][question_range_optionID].optionName)+"\',";' . "\r\n" . '				}			' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		tmp++;' . "\r\n" . '		var the_question_range_answerID = \'option_\'+questionID+\'_\'+question_range_optionID;' . "\r\n" . '		var the_question_range_answer_value = dataRow.rows[0][dataIndex[the_question_range_answerID]];' . "\r\n" . '		if( the_question_range_answer_value != \'0\' && the_question_range_answer_value != \'\' )' . "\r\n" . '		{' . "\r\n" . '			dataqtn += "optionAnswer:\'"+qJsonCharFilter(AnswerListArray[questionID][the_question_range_answer_value].optionAnswer)+"\'";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			dataqtn += "optionAnswer:\'跳过\'";' . "\r\n" . '		}' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '	}' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '' . "\r\n" . '	var tplText = \'<table width="100%"><tr><td class="question">{$questionName}</td></tr><tr><td><table cellSpacing=0 cellPadding=0>{foreach $qtns as $i => $qtn}<tr><td class="option">&nbsp;<span class="option">{$qtn.optionName}：</span><font color=red>{$qtn.optionAnswer}</font></td></tr>{/foreach}</table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '    var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '	var t = new jSmart(tplText);' . "\r\n" . '    survey_content_data_html += t.fetch(jsondata);' . "\r\n" . '	jsondata = null;' . "\r\n" . '}';

?>
