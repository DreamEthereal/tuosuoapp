<?php
//dezend by http://www.yunlu99.com/
echo 'function create_cascade_qtn(questionID)' . "\r\n" . '{' . "\r\n" . '	var theInfoQtnName = qNoScriptString(QtnListArray[questionID].questionName);' . "\r\n" . '' . "\r\n" . '	//��ʾ��Ŀ����' . "\r\n" . '	var questionRequire = \'\';' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	var questionNotes = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionRequire = \'<span class=red>*</span>\';' . "\r\n" . '	}' . "\r\n" . '	questionName = qQuotaChar(qJsonCharFilter(QtnListArray[questionID].questionName));' . "\r\n" . '	questionNotes = \'[����ѡ����]\';' . "\r\n" . '' . "\r\n" . '    var dataqtn = "{ ";' . "\r\n" . '    dataqtn += " questionID: "+questionID+",";' . "\r\n" . '    dataqtn += " questionRequire: \'"+questionRequire+"\',";' . "\r\n" . '    dataqtn += " questionName: \'"+qShowQtnName(questionName,questionID,1)+"\',";' . "\r\n" . '    dataqtn += " questionNotes: \'"+questionNotes+"\',";' . "\r\n" . '	questionTips = qQuotaChar(qJsonCharFilter(QtnListArray[questionID].questionNotes));' . "\r\n" . '    dataqtn += " questionTips: \'"+qShowQtnName(questionTips,questionID,2)+"\',";' . "\r\n" . '' . "\r\n" . '	//��ʾѡ��' . "\r\n" . '	var check_survey_form_no_con_list = \'\';' . "\r\n" . '	var remove_value_list = \'\';' . "\r\n" . '	var optionName = \'\';' . "\r\n" . '	var theUnitText = QtnListArray[questionID].unitText.split(\'#\');' . "\r\n" . '	var defVal = \'\';' . "\r\n" . '	dataqtn += " qtns:[ ";' . "\r\n" . '	for(u=1;u<=QtnListArray[questionID].maxSize;u++)' . "\r\n" . '	{' . "\r\n" . '		dataqtn += "{";' . "\r\n" . '		var tmp = u-1;' . "\r\n" . '		var theVarName = \'option_\'+questionID+\'_\'+u;' . "\r\n" . '		//����ҳ���ֶ�' . "\r\n" . '		this_fields_list += theVarName+\'|\';' . "\r\n" . '		this_fields_type += \'5|\';' . "\r\n" . '' . "\r\n" . '		dataqtn += " optionID:\'"+theVarName+"\', ";' . "\r\n" . '		optionName += "\'#"+theVarName+"\',";' . "\r\n" . '	' . "\r\n" . '		//ҳ����' . "\r\n" . '		if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '		{' . "\r\n" . '			check_survey_form_no_con_list += "\\tif (!CheckListNoSelect(document.Survey_Form."+theVarName+", \'"+theInfoQtnName+"-"+qNoScriptString(theUnitText[tmp])+"\')){return false;} \\n";' . "\r\n" . '		}' . "\r\n" . '		remove_value_list +="\\tListUnSelect(document.Survey_Form."+theVarName+");\\n";' . "\r\n" . '' . "\r\n" . '		if( count(dataRow.rows) != 0 )' . "\r\n" . '		{' . "\r\n" . '			defVal += dataRow.rows[0][dataIndex[theVarName]]+",";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			defVal += "0,";' . "\r\n" . '		}' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '    var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '' . "\r\n" . '	//�߼�����' . "\r\n" . '	var QuestionCon = _GetQuestionCond(questionID,qid);' . "\r\n" . '	if( QuestionCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		check_survey_conditions_list += "\\tif("+QuestionCon+")\\n\\t{\\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\t$(\'#question_"+questionID+"\').show();\\n\\t} \\n";' . "\r\n" . '		check_survey_conditions_list += "\\telse { \\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\t$(\'#question_"+questionID+"\').hide();\\n\\t} \\n";' . "\r\n" . '' . "\r\n" . '		var check_form_list = "\\tif("+QuestionCon+")\\n\\t{\\n";' . "\r\n" . '		check_form_list += check_survey_form_no_con_list;' . "\r\n" . '		check_form_list += "\\t}\\n";' . "\r\n" . '		//�Ƴ��ѻظ���ֵ' . "\r\n" . '		check_form_list += "\\telse{\\n";' . "\r\n" . '		check_form_list += remove_value_list;' . "\r\n" . '		check_form_list += "\\t}\\n";' . "\r\n" . '' . "\r\n" . '		check_survey_form_list += check_form_list;' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		check_survey_form_list += check_survey_form_no_con_list;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//�ʾ���������' . "\r\n" . '	var EndSurveyCon = _GetSurveyQuotaCond(questionID,qid,\'cn\');' . "\r\n" . '	if( EndSurveyCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		survey_quota_list += EndSurveyCon;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	var tplText = \'<table width="100%" class="pertable" id="question_{$questionID}"><tr><td class="question" valign=center>&nbsp;{$questionRequire}<span class="textEdit" id="questionName_{$questionID}">{$questionName}</span>&nbsp;<span class=notes>{$questionNotes}</span></td></tr><tr><td class="tips"><span class="textEdit" id="questionNotes_{$questionID}">{$questionTips}</span></td></tr><tr><td><table cellSpacing=0 cellPadding=0 width="100%"><tr><td class="answer">{foreach $qtns as $i => $qtn}<select id="{$qtn.optionID}" name="{$qtn.optionID}" style="margin-left: 3px"></select>{/foreach}</td></tr></table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '    var t = new jSmart(tplText);' . "\r\n" . '    //$(\'#survey_content_container\').append( t.fetch(jsondata)); ' . "\r\n" . '	survey_content_container_html += t.fetch(jsondata);' . "\r\n" . '	jsondata = null;' . "\r\n" . '	' . "\r\n" . '	var cascadeContent = eval(\'Cascade_\'+questionID);' . "\r\n" . '	var cascade_data = eval(\'(\'+cascadeContent+\')\');' . "\r\n" . '	cascadeOpts[questionID] = {' . "\r\n" . '		data: cascade_data ,' . "\r\n" . '		selStyle: \'margin-left: 3px;\',' . "\r\n" . '		select: eval(\'[\'+optionName.substr(0,optionName.length-1)+\']\') ,' . "\r\n" . '		level:QtnListArray[questionID].maxSize,' . "\r\n" . '		defVal: eval(\'[\'+defVal.substr(0,defVal.length-1)+\']\'),' . "\r\n" . '		head:\'��ѡ��...\',' . "\r\n" . '		loaderImg:\'resources/wait.gif\',' . "\r\n" . '		dataReader: {id: \'nodeID\', name: \'nodeName\', cell: \'child\'}' . "\r\n" . '	};' . "\r\n" . '}';

?>