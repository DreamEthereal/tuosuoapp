<?php
//dezend by http://www.yunlu99.com/
echo '//复合多选题' . "\r\n" . 'function create_combcheckbox_qtn(questionID)' . "\r\n" . '{' . "\r\n" . '	var theInfoQtnName = qNoScriptString(QtnListArray[questionID].questionName);' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	this_fields_type += \'2|\';' . "\r\n" . '' . "\r\n" . '	var questionRequire = \'\';' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	var questionNotes = \'\';' . "\r\n" . '	var minOption =\'\',maxOption = \'\';' . "\r\n" . '	var check_survey_form_no_con_list = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionRequire = \'<span class=red>*</span>\';' . "\r\n" . '		if( QtnListArray[questionID].minOption != 0 )' . "\r\n" . '		{' . "\r\n" . '		   minOption = \'[最少\'+QtnListArray[questionID].minOption+\'项]\';' . "\r\n" . '		}' . "\r\n" . '		if( QtnListArray[questionID].maxOption != 0 )' . "\r\n" . '		{' . "\r\n" . '		   maxOption = \'[最多\'+QtnListArray[questionID].maxOption+\'项]\';' . "\r\n" . '		}' . "\r\n" . '		//检测项' . "\r\n" . '		check_survey_form_no_con_list += "\\tif (!CheckRadioNoClickInAssMode("+questionID+",document.Survey_Form."+\'option_\'+questionID+", \'"+theInfoQtnName+"\')){return false;} \\n";' . "\r\n" . '		if( QtnListArray[questionID].minOption != 0 )' . "\r\n" . '		{' . "\r\n" . '			check_survey_form_no_con_list += "\\tif (!CheckCheckMinClickInAssMode("+questionID+",document.Survey_Form."+\'option_\'+questionID+", \'"+theInfoQtnName+"\',"+QtnListArray[questionID].minOption+")){return false;} \\n";' . "\r\n" . '		}' . "\r\n" . '		if( QtnListArray[questionID].maxOption != 0 )' . "\r\n" . '		{' . "\r\n" . '			check_survey_form_no_con_list += "\\tif (!CheckCheckMaxClickInAssMode("+questionID+",document.Survey_Form."+\'option_\'+questionID+", \'"+theInfoQtnName+"\',"+QtnListArray[questionID].maxOption+")){return false;} \\n";' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	questionName = qQuotaChar(qJsonCharFilter(QtnListArray[questionID].questionName));' . "\r\n" . '	questionNotes = \'[复合多选题]\';' . "\r\n" . '	questionNotes += minOption;' . "\r\n" . '	questionNotes += maxOption;' . "\r\n" . '' . "\r\n" . '    var dataqtn = "{ ";' . "\r\n" . '    dataqtn += " questionID: "+questionID+",";' . "\r\n" . '    dataqtn += " questionRequire: \'"+questionRequire+"\',";' . "\r\n" . '	dataqtn += " questionName: \'"+qShowQtnName(questionName,questionID,1)+"\',";' . "\r\n" . '    dataqtn += " questionNotes: \'"+questionNotes+"\',";' . "\r\n" . '	questionTips = qQuotaChar(qJsonCharFilter(QtnListArray[questionID].questionNotes)); ' . "\r\n" . '	dataqtn += " questionTips: \'"+qShowQtnName(questionTips,questionID,2)+"\',";' . "\r\n" . '' . "\r\n" . '	var maxOptionNum = count(CheckBoxListArray[questionID]);' . "\r\n" . '	var theCheckBoxListArray = [];' . "\r\n" . '	var theOptionOrderID = 0;' . "\r\n" . '	var remove_value_list = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRandOptions == 1 ) //随机' . "\r\n" . '	{' . "\r\n" . '		var theRetainArray = [];' . "\r\n" . '		var theRandArray = [];' . "\r\n" . '		for(var question_checkboxID in CheckBoxListArray[questionID])' . "\r\n" . '		{' . "\r\n" . '			if( CheckBoxListArray[questionID][question_checkboxID].isRetain == 1 ) //保留' . "\r\n" . '			{' . "\r\n" . '				theRetainArray.push(question_checkboxID);' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				theRandArray.push(question_checkboxID);' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		if( count(theRandArray) != 0 )' . "\r\n" . '		{' . "\r\n" . '			var theRandListArray = array_rand(theRandArray,count(theRandArray));' . "\r\n" . '			for( var i=0;i<theRandListArray.length;i++)' . "\r\n" . '			{' . "\r\n" . '				theCheckBoxListArray.push(theRandArray[theRandListArray[i]]);' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		for( var tmp in theRetainArray )' . "\r\n" . '		{	' . "\r\n" . '		    var question_checkboxID = theRetainArray[tmp];' . "\r\n" . '		    theCheckBoxListArray.push(question_checkboxID);' . "\r\n" . '		}' . "\r\n" . '		theRetainArray = null;' . "\r\n" . '		theRandArray = null;' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '	    for( var tmp in CheckBoxListArray[questionID] )' . "\r\n" . '	    {' . "\r\n" . '		    theCheckBoxListArray.push(tmp);' . "\r\n" . '	    }' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//找出分组' . "\r\n" . '	var theGroupOption = [];' . "\r\n" . '	var t = 0;' . "\r\n" . '    for( var k in theCheckBoxListArray )' . "\r\n" . '	{' . "\r\n" . '	    var question_checkboxID = theCheckBoxListArray[k];' . "\r\n" . '	    var theQuestionArray = CheckBoxListArray[questionID][question_checkboxID];' . "\r\n" . '		var p = theQuestionArray[\'groupNum\'];' . "\r\n" . '		if( theGroupOption[p] == null) theGroupOption[p] = [];' . "\r\n" . '		theGroupOption[p].push(t);' . "\r\n" . '		t++;' . "\r\n" . '	}		' . "\r\n" . '' . "\r\n" . '	//开始' . "\r\n" . '    dataqtn += " qtns:[ ";' . "\r\n" . '	var theOptionOdNum = 0;' . "\r\n" . '	var check_survey_conditions_list_group = \'\';' . "\r\n" . '    for( var k in theCheckBoxListArray )' . "\r\n" . '    {' . "\r\n" . '	    var question_checkboxID = theCheckBoxListArray[k];' . "\r\n" . '	    var theQuestionArray = CheckBoxListArray[questionID][question_checkboxID];' . "\r\n" . '' . "\r\n" . '		dataqtn += "{";' . "\r\n" . '		dataqtn += " optionID:\'"+question_checkboxID+"\', ";' . "\r\n" . '		dataqtn += " optionName:\'"+qJsonCharFilter(theQuestionArray.optionName)+"\', ";' . "\r\n" . '        dataqtn += " theOptionOdNum:"+theOptionOdNum+",";' . "\r\n" . '		theOptionOdNum ++;' . "\r\n" . '' . "\r\n" . '		theTextID = \'TextOtherValue_\'+questionID+\'_\'+question_checkboxID;' . "\r\n" . '' . "\r\n" . '		//互斥关系' . "\r\n" . '		if( theQuestionArray.isExclusive == 1 )' . "\r\n" . '		{' . "\r\n" . '			if( theQuestionArray[\'groupNum\'] == 0 )' . "\r\n" . '			{' . "\r\n" . '				check_survey_conditions_list +="\\tisExcludeItem(document.Survey_Form.option_"+questionID+","+theOptionOrderID+","+maxOptionNum+");\\n";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				check_survey_conditions_list_group += "\\tisExcludeGroupItem(document.Survey_Form.option_"+questionID+","+theOptionOrderID+",\'"+implode(\',\',theGroupOption[theQuestionArray[\'groupNum\']])+"\');\\n";' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '' . "\r\n" . '		if( theQuestionArray.isHaveText == 1 )' . "\r\n" . '		{' . "\r\n" . '			//处理页面字段' . "\r\n" . '			this_fields_list += theTextID+\'|\';' . "\r\n" . '			this_fields_type += \'3|\';' . "\r\n" . '' . "\r\n" . '			dataqtn += " isHaveText:\'\', ";' . "\r\n" . '		    if( count(dataRow.rows) != 0 )' . "\r\n" . '		    {' . "\r\n" . '			    dataqtn += " value: \'"+qhtmlspecialchars(dataRow.rows[0][dataIndex[theTextID]])+"\',";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '			    dataqtn += " value:\'\', ";' . "\r\n" . '			}' . "\r\n" . '			switch( theQuestionArray.isCheckType )' . "\r\n" . '			{' . "\r\n" . '				case 4:' . "\r\n" . '					if( typeof theQuestionArray.unitText == \'undefined\' )' . "\r\n" . '					{' . "\r\n" . '						dataqtn += " unitText:\'\',";' . "\r\n" . '					}' . "\r\n" . '					else' . "\r\n" . '					{' . "\r\n" . '						dataqtn += " unitText:\'"+qJsonCharFilter(theQuestionArray.unitText)+"\',";' . "\r\n" . '					}' . "\r\n" . '				break;' . "\r\n" . '				default:' . "\r\n" . '					dataqtn += " unitText:\'\',";' . "\r\n" . '				break;' . "\r\n" . '			}' . "\r\n" . '			//记录移除值' . "\r\n" . '			remove_value_list +="\\tTextUnInput(document.Survey_Form."+theTextID+");\\n";' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			dataqtn += " isHaveText:\'none\', ";' . "\r\n" . '			dataqtn += " value:\'\', ";' . "\r\n" . '			dataqtn += " unitText:\'\', ";' . "\r\n" . '		}' . "\r\n" . '		switch( theQuestionArray.isCheckType )' . "\r\n" . '		{' . "\r\n" . '			case 4:  //数值' . "\r\n" . '			case 8:  //企业法人代码' . "\r\n" . '			case 9:  //邮政编码' . "\r\n" . '				dataqtn += " datePrompt:\'\',";' . "\r\n" . '				dataqtn += " inputPrompt:\'number\',";' . "\r\n" . '			break;' . "\r\n" . '			case 5:  //电话' . "\r\n" . '			case 11:  //手机' . "\r\n" . '				dataqtn += " datePrompt:\'\',";' . "\r\n" . '				dataqtn += " inputPrompt:\'tel\',";' . "\r\n" . '			break;' . "\r\n" . '			case 6:  //日期' . "\r\n" . '				var datePrompt = " onclick=\\"javascript:seldate(this)\\" ";' . "\r\n" . '				dataqtn += " datePrompt:\'"+datePrompt+"\',";' . "\r\n" . '				dataqtn += " inputPrompt:\'text\',";' . "\r\n" . '			break;' . "\r\n" . '			default:' . "\r\n" . '				dataqtn += " datePrompt:\'\',";' . "\r\n" . '				dataqtn += " inputPrompt:\'text\',";' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '		dataqtn += " length:\'"+theQuestionArray.optionSize+"\', ";' . "\r\n" . '		if( count(dataRow.rows) != 0 )' . "\r\n" . '		{' . "\r\n" . '			if( dataRow.rows[0][dataIndex[\'option_\'+questionID]] != \'\' && IsCheckBoxSelect(question_checkboxID,questionID) )' . "\r\n" . '			{' . "\r\n" . '				dataqtn += " isCheck:\'checked\' ";' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				dataqtn += " isCheck:\'\' ";' . "\r\n" . '		    }				' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			dataqtn += " isCheck:\'\' ";' . "\r\n" . '		}' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '		' . "\r\n" . '		check_survey_form_no_con_list += "\\tif ( (document.Survey_Form."+\'option_\'+questionID+".length != null && document.Survey_Form."+\'option_\'+questionID+"["+theOptionOrderID+"].checked) || (document.Survey_Form."+\'option_\'+questionID+".length == null && document.Survey_Form."+\'option_\'+questionID+".checked) )\\n\\t{\\n ";' . "\r\n" . '		if( theQuestionArray.isRequired == 1 )' . "\r\n" . '		{' . "\r\n" . '			check_survey_form_no_con_list += "\\t\\tif (!CheckNotNull(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;}\\n";' . "\r\n" . '		}' . "\r\n" . '		switch( theQuestionArray.isCheckType )' . "\r\n" . '		{' . "\r\n" . '			case 1:  //Email' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckEmail(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 2:  //长度' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckStringLength(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\',"+theQuestionArray.minOption+","+theQuestionArray.maxOption+")){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 3:  //中文' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckNoChinese(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 4:  //数值' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckIsValue(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\',"+theQuestionArray.minOption+","+theQuestionArray.maxOption+")){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 5:  //电话' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckPhone(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 6:  //日期' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckDate(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 7:  //身份证' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckIDCardNo(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 8:  //企业法人代码' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckCorpCode(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 9:  //邮政编码' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckPostalCode(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 10:  //URL' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckURL(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 11:  //手机' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckMobile(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '			case 12:  //中文' . "\r\n" . '				check_survey_form_no_con_list += "\\t\\tif (!CheckChinese(document.Survey_Form."+theTextID+", \'"+theInfoQtnName+\' - \'+qNoScriptString(theQuestionArray.optionName)+"\')){return false;} \\n";' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '		check_survey_form_no_con_list += "\\t}\\n";' . "\r\n" . '		check_survey_form_no_con_list += "\\telse\\n\\t{\\n\\t\\tTextUnInput(document.Survey_Form."+theTextID+");\\n\\t}\\n";' . "\r\n" . '		theOptionOrderID++;' . "\r\n" . '' . "\r\n" . '		//选项关联' . "\r\n" . '		var OptAssCon = _GetOptAssCond(questionID,question_checkboxID);' . "\r\n" . '		if( OptAssCon != \'\' )' . "\r\n" . '		{' . "\r\n" . '			check_survey_conditions_list += "\\tif("+OptAssCon+")\\n\\t{\\n";' . "\r\n" . '			check_survey_conditions_list += "\\t\\t$(\\"#option_checkbox_"+question_checkboxID+"_container\\").hide();\\n";			' . "\r\n" . '			check_survey_conditions_list += "\\t\\t$(\\"#option_checkbox_"+question_checkboxID+"_container_0\\").hide();\\n";			' . "\r\n" . '			check_survey_conditions_list += "\\t\\t$(\\"input[id=\'option_"+questionID+"\'][value=\'"+question_checkboxID+"\']\\").attr(\\"checked\\",false);\\n";' . "\r\n" . '			check_survey_conditions_list += "\\t\\tTextUnInput(document.Survey_Form."+theTextID+");";' . "\r\n" . '			check_survey_conditions_list += "\\n\\t} \\n";' . "\r\n" . '			check_survey_conditions_list += "\\telse { \\n";' . "\r\n" . '			check_survey_conditions_list += "\\t\\t$(\\"#option_checkbox_"+question_checkboxID+"_container\\").show();\\n";' . "\r\n" . '			check_survey_conditions_list += "\\t\\tif("+theQuestionArray.isHaveText+" == 1) $(\\"#option_checkbox_"+question_checkboxID+"_container_0\\").show();\\n";' . "\r\n" . '			check_survey_conditions_list += "\\t} \\n";' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '    var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '' . "\r\n" . '	check_survey_conditions_list += check_survey_conditions_list_group;' . "\r\n" . '' . "\r\n" . '	//逻辑条件' . "\r\n" . '	var QuestionCon = _GetQuestionCond(questionID,qid);' . "\r\n" . '	if( QuestionCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		check_survey_conditions_list += "\\tif("+QuestionCon+")\\n\\t{\\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\t$(\'#question_"+questionID+"\').show();\\n\\t} \\n";' . "\r\n" . '		check_survey_conditions_list += "\\telse { \\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\t$(\'#question_"+questionID+"\').hide();\\n\\t} \\n";' . "\r\n" . '' . "\r\n" . '		var check_form_list = "\\tif("+QuestionCon+")\\n\\t{\\n";' . "\r\n" . '		check_form_list += check_survey_form_no_con_list;' . "\r\n" . '		check_form_list += "\\t}\\n";' . "\r\n" . '		//移除已回复的值' . "\r\n" . '		check_form_list += "\\telse{\\n";' . "\r\n" . '		check_form_list +="\\tRadioUnClick(document.Survey_Form.option_"+questionID+");\\n";' . "\r\n" . '		check_form_list += remove_value_list;' . "\r\n" . '		check_form_list += "\\t}\\n";' . "\r\n" . '		check_survey_form_list += check_form_list;' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		check_survey_form_list += check_survey_form_no_con_list;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//改变背景颜色' . "\r\n" . '	check_survey_conditions_list += "\\tchangeMaskingSingleBgColor("+questionID+");";' . "\r\n" . '' . "\r\n" . '	//关联关系' . "\r\n" . '	var RelSurveyCon = _GetSurveyValueRelationCond(questionID,qid,\'cn\');' . "\r\n" . '	if( RelSurveyCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		//此题存在多个数值关联关系的检查点' . "\r\n" . '		var theRelSurveyCon = RelSurveyCon.split(\'$$$$$$\');' . "\r\n" . '		for( var t in theRelSurveyCon )' . "\r\n" . '		{' . "\r\n" . '			var tRelSurveyCon = theRelSurveyCon[t].split(\'######\');' . "\r\n" . '			if( parseInt(tRelSurveyCon[0]) == 2 ) //空题' . "\r\n" . '			{' . "\r\n" . '				survey_empty_list += tRelSurveyCon[1];' . "\r\n" . '				var theEmptyList = tRelSurveyCon[1].split(\'*\');' . "\r\n" . '				var theEmptyId = parseInt(substr(theEmptyList[7],0,-3));' . "\r\n" . '				if( !IsSamePage(theEmptyId,questionID)) //不在同页' . "\r\n" . '				{' . "\r\n" . '					//空题字段' . "\r\n" . '					this_fields_list += \'option_\'+theEmptyId+\'|\';' . "\r\n" . '					this_fields_type += \'3|\';' . "\r\n" . '					//跑一遍空题的配额表达式' . "\r\n" . '					var theEmptyEndSurveyCon = _GetSurveyQuotaCond(theEmptyId,qid,\'cn\');' . "\r\n" . '					if( theEmptyEndSurveyCon != \'\' )' . "\r\n" . '					{' . "\r\n" . '						survey_quota_list += theEmptyEndSurveyCon;' . "\r\n" . '					}' . "\r\n" . '				}' . "\r\n" . '				theEmptyList = null;' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				survey_quota_list += tRelSurveyCon[1];' . "\r\n" . '			}' . "\r\n" . '			tRelSurveyCon = null;' . "\r\n" . '		}' . "\r\n" . '		theRelSurveyCon = null;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//问卷结束条件' . "\r\n" . '	var EndSurveyCon = _GetSurveyQuotaCond(questionID,qid,\'cn\');' . "\r\n" . '	if( EndSurveyCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		survey_quota_list += EndSurveyCon;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	var tplText = \'<table width="100%" class="pertable" id="question_{$questionID}"><tr><td class="question" valign=center>&nbsp;{$questionRequire}<span class="textEdit" id="questionName_{$questionID}">{$questionName}</span>&nbsp;<span class=notes>{$questionNotes}</span></td></tr><tr><td class="tips"><span class="textEdit" id="questionNotes_{$questionID}">{$questionTips}</span></td></tr><tr><td><table cellSpacing=0 cellPadding=0 width=100% id="qtn_table_{$questionID}">{foreach $qtns as $i => $qtn}<tr class=tdheight id="option_checkbox_{$qtn.optionID}_container"><td><input onclick="jQuery.Check_Survey_Conditions()" type="checkbox" value="{$qtn.optionID}" name="option_{$questionID}" id="option_{$questionID}" {$qtn.isCheck}></td><td width=99% class="answer" nowrap><a href="javascript:void(0)" onclick=javascript:selCheckBoxCheckRows("option_{$questionID}",{$qtn.theOptionOdNum});><div style="white-space:nowrap;" class=tdlineheight><span class="textEdit" id="optionName_25_{$questionID}_{$qtn.optionID}">{$qtn.optionName}</span></div></a></td></tr><tr style="display:{$qtn.isHaveText}" id="option_checkbox_{$qtn.optionID}_container_0" class="no_color_tr"><td style="display:{$qtn.isHaveText}">&nbsp;</td><td style="display:{$qtn.isHaveText}" class="answer tdheight"><input name="TextOtherValue_{$questionID}_{$qtn.optionID}" id="TextOtherValue_{$questionID}_{$qtn.optionID}" type="{$qtn.inputPrompt}" size="{$qtn.length}" value="{$qtn.value}" {$qtn.datePrompt} onblur="jQuery.Check_Survey_Conditions();">&nbsp;{$qtn.unitText}</td></tr>{/foreach}</table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '    theCheckBoxListArray = null;' . "\r\n" . '    var t = new jSmart(tplText);' . "\r\n" . '    //$(\'#survey_content_container\').append( t.fetch(jsondata)); ' . "\r\n" . '    survey_content_container_html += t.fetch(jsondata);' . "\r\n" . '    jsondata = null;' . "\r\n" . '	theQuestionArray = null;' . "\r\n" . '}' . "\r\n" . '';

?>
