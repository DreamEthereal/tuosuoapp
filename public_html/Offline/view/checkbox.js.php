<?php
//dezend by http://www.yunlu99.com/
echo 'function view_checkbox_data(questionID)' . "\r\n" . '{' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	var minOption = \'\',maxOption = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionName = \'<span class=red>*</span>\';' . "\r\n" . '		if( QtnListArray[questionID].minOption != 0 )' . "\r\n" . '		{' . "\r\n" . '			 minOption += \'<span class=notes>[最少\'+QtnListArray[questionID].minOption+\'项]</span>\';' . "\r\n" . '		}' . "\r\n" . '		if( QtnListArray[questionID].maxOption != 0 )' . "\r\n" . '		{' . "\r\n" . '			 maxOption += \'<span class=notes>[最多\'+QtnListArray[questionID].maxOption+\'项]</span>\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	questionName += qQuotaChar(QtnListArray[questionID].questionName);' . "\r\n" . '	questionName += \'&nbsp;<span class=notes>[多选题]</span>\';' . "\r\n" . '	questionName += minOption;' . "\r\n" . '	questionName += maxOption;' . "\r\n" . '	dataqtn = "{";' . "\r\n" . '	dataqtn += " questionName:\'"+qJsonCharFilter(questionName)+"\',";' . "\r\n" . '' . "\r\n" . '	//显示题目选项' . "\r\n" . '	var optionAutoArray = [];' . "\r\n" . '	var optionIdArray = [];' . "\r\n" . '	for( var question_checkboxID in CheckBoxListArray[questionID])' . "\r\n" . '	{' . "\r\n" . '		optionIdArray.push(question_checkboxID);' . "\r\n" . '		optionAutoArray[question_checkboxID] = qJsonCharFilter(CheckBoxListArray[questionID][question_checkboxID].optionName);' . "\r\n" . '	}' . "\r\n" . '	var option_value_array = dataRow.rows[0][dataIndex[\'option_\'+questionID]].split(\',\')' . "\r\n" . '	var theOtherValue = \'\';' . "\r\n" . '	//有其他项' . "\r\n" . '	if( QtnListArray[questionID].isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		optionIdArray.push(0);' . "\r\n" . '		optionAutoArray[0] = qJsonCharFilter(QtnListArray[questionID].otherText);' . "\r\n" . '		var otherID = \'TextOtherValue_\'+questionID;' . "\r\n" . '		theOtherValue = dataRow.rows[0][dataIndex[otherID]];' . "\r\n" . '		if( theOtherValue != \'\' )' . "\r\n" . '		{' . "\r\n" . '			dataqtn += " isHaveOther:\'\',";' . "\r\n" . '			dataqtn += " otherText:\'"+qJsonCharFilter(QtnListArray[questionID].otherText)+"\',";' . "\r\n" . '			dataqtn += " textOtherValue:\'"+qJsonCharFilter(theOtherValue)+"\',";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			dataqtn += " isHaveOther:\'none\',";' . "\r\n" . '			dataqtn += " otherText:\'\',";' . "\r\n" . '			dataqtn += " textOtherValue:\'\',";' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		dataqtn += " isHaveOther:\'none\',";' . "\r\n" . '		dataqtn += " otherText:\'\',";' . "\r\n" . '		dataqtn += " textOtherValue:\'\',";' . "\r\n" . '	}' . "\r\n" . '	if( QtnListArray[questionID].isNeg == 1 )' . "\r\n" . '	{' . "\r\n" . '	    var negText = ( QtnListArray[questionID].allowType == \'\' ) ? \'以上都不是\' : qJsonCharFilter(QtnListArray[questionID].allowType);' . "\r\n" . '		optionIdArray.push(99999);' . "\r\n" . '		optionAutoArray[99999] = negText;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	dataqtn += " qtns:[ ";' . "\r\n" . '	for( var tmp in optionIdArray )' . "\r\n" . '	{' . "\r\n" . '		var optionAutoID = optionIdArray[tmp];' . "\r\n" . '		var optionAutoName = optionAutoArray[optionAutoID];' . "\r\n" . '	' . "\r\n" . '		dataqtn += "{ ";' . "\r\n" . '		dataqtn += "optionName:\'"+optionAutoName+"\',";' . "\r\n" . '		if( optionAutoID == 0 )  //其他' . "\r\n" . '		{' . "\r\n" . '			if( theOtherValue != \'\' && in_array(optionAutoID,option_value_array) )' . "\r\n" . '			{' . "\r\n" . '				dataqtn += "optionAnswer:\'1\'";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				dataqtn += "optionAnswer:\'0\'";' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			if( in_array(optionAutoID,option_value_array) )' . "\r\n" . '			{ ' . "\r\n" . '				dataqtn += "optionAnswer:\'1\'";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				dataqtn += "optionAnswer:\'0\'";' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '	}' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '' . "\r\n" . '	var tplText = \'<table width="100%"><tr><td class="question">{$questionName}</td></tr><tr><td><table cellSpacing=0 cellPadding=0>{foreach $qtns as $i => $qtn}<tr><td class="option">&nbsp;<span class="option">{$qtn.optionName}：</span><font color=red>{$qtn.optionAnswer}</font></td></tr>{/foreach}<tr style="display:{$isHaveOther}"><td class="option">&nbsp;<span class="option">{$otherText}(文本)：</span><font color=red>{$textOtherValue}</font></td></tr></table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '    var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '	var t = new jSmart(tplText);' . "\r\n" . '    survey_content_data_html += t.fetch(jsondata);' . "\r\n" . '	jsondata = null;' . "\r\n" . '	option_value_array = null;' . "\r\n" . '	optionIdArray = null;' . "\r\n" . '	optionAutoArray = null;' . "\r\n" . '}';

?>
