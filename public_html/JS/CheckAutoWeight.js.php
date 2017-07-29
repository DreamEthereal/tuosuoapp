<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

header('Cache-Control: public');
header('Pragma: cache');
$offset = 2592000;
$ExpStr = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
$LmStr = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT';
header($ExpStr);
header($LmStr);
header('Content-Type: text/javascrīpt; charset: UTF-8');
echo '' . "\r\n" . '//计算可分配的比重值' . "\r\n" . 'function AutoReCalcSum(questionID,minOptionNum,maxOptionNum,maxNum,isHaveOther)' . "\r\n" . '{' . "\r\n" . '    var obj,optionTotalNum = 0;' . "\r\n" . '	for(i=minOptionNum;i<=maxOptionNum;i++)' . "\r\n" . '	{' . "\r\n" . '	    obj = eval("document.Survey_Form.option_" + questionID + "_" + i );' . "\r\n" . '		var objValue = Trim(obj.value);' . "\r\n" . '		if( obj != null && objValue != \'\' )' . "\r\n" . '		{' . "\r\n" . '			if( !isNaN(parseFloat(objValue)) )' . "\r\n" . '			{' . "\r\n" . '				optionTotalNum = accAdd(optionTotalNum,parseFloat(objValue));' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	if(isHaveOther == 1)' . "\r\n" . '	{' . "\r\n" . '	    obj = eval("document.Survey_Form.option_" + questionID + "_0" );' . "\r\n" . '		var objValue = Trim(obj.value);' . "\r\n" . '		if( obj != null && objValue != \'\' )' . "\r\n" . '		{' . "\r\n" . '			if( !isNaN(parseFloat(objValue)) )' . "\r\n" . '			{' . "\r\n" . '				optionTotalNum = accAdd(optionTotalNum,parseFloat(objValue));' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '    var rtnValue = accSub(maxNum,optionTotalNum);' . "\r\n" . '	var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '	tarObj.value = rtnValue.toString();' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//自动选项同页展现' . "\r\n" . 'function getAutoWeightSamePage(baseID,isList,questionID,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '	 var objField = eval( "document.Survey_Form.option_" + baseID );' . "\r\n" . '	 if (objField != null ){' . "\r\n" . '		 if( isList == 1 )  //列表框' . "\r\n" . '		 {' . "\r\n" . '			 for( i=0;i<objField.options.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField.options[i].selected && objField.options[i].value != \'\')' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '						//总剩余' . "\r\n" . '						var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '						var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '						if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '						}' . "\r\n" . '						else' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = Number(tarObj.value);' . "\r\n" . '						}' . "\r\n" . '						tarObj.value = rtnValue.toString();' . "\r\n" . '						TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			 for( i=0;i<objField.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if(objField[i].checked )' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '						//总剩余' . "\r\n" . '						var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '						var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '						if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '						}' . "\r\n" . '						else' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = Number(tarObj.value);' . "\r\n" . '						}' . "\r\n" . '						tarObj.value = rtnValue.toString();' . "\r\n" . '						TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		} //End for if' . "\r\n" . '		//判断刻度是否显示' . "\r\n" . '		var isHaveShow = false;' . "\r\n" . '		for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		   if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '				break;' . "\r\n" . '		   }' . "\r\n" . '	    }' . "\r\n" . '		//有其他项' . "\r\n" . '		if( isHaveOther == 1 )' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		   if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '		   }' . "\r\n" . '		}' . "\r\n" . '		var theTextObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '		if( isHaveShow == true )' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'\';' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'none\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function getAutoWeightNoSamePage(baseID,theAllValue,theSelectValue,questionID,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '    var theAllValueArray = theAllValue.split(\',\');' . "\r\n" . '	for(i=0;i<=theAllValueArray.length-1;i++){' . "\r\n" . '	    theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '		if( theOptionObj != null )' . "\r\n" . '		{' . "\r\n" . '			if( theSelectValue.indexOf(","+theAllValueArray[i] + ",") == -1 )' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'none\';' . "\r\n" . '				theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '' . "\r\n" . '				//总剩余' . "\r\n" . ' 				var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '				var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '				if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '				{' . "\r\n" . '					var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					var rtnValue = Number(tarObj.value);' . "\r\n" . '				}' . "\r\n" . '				tarObj.value = rtnValue.toString();' . "\r\n" . '				TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'\';' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//判断刻度是否显示' . "\r\n" . '	var isHaveShow = false;' . "\r\n" . '	for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	var theTextObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '	if( isHaveShow == true )' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'\';' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'none\';' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//同页展现' . "\r\n" . 'function getAutoWeightSamePageIsNeg(baseID,isList,questionID,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '	 var objField = eval( "document.Survey_Form.option_" + baseID );' . "\r\n" . '	 if (objField != null ){' . "\r\n" . '		 if( isList == 1 )  //列表框' . "\r\n" . '		 {' . "\r\n" . '			 for( i=0;i<objField.options.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField.options[i].selected != true && objField.options[i].value != \'\')' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '						//总剩余' . "\r\n" . '						var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '						var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '						if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '						}' . "\r\n" . '						else' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = Number(tarObj.value);' . "\r\n" . '						}' . "\r\n" . '						tarObj.value = rtnValue.toString();' . "\r\n" . '						TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			 for( i=0;i<objField.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField[i].checked != true )' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '						//总剩余' . "\r\n" . '						var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '						var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '						if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '						}' . "\r\n" . '						else' . "\r\n" . '						{' . "\r\n" . '							var rtnValue = Number(tarObj.value);' . "\r\n" . '						}' . "\r\n" . '						tarObj.value = rtnValue.toString();' . "\r\n" . '						TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		} //End for if' . "\r\n" . '		//判断刻度是否显示' . "\r\n" . '		var isHaveShow = false;' . "\r\n" . '		for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		   if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '				break;' . "\r\n" . '		   }' . "\r\n" . '	    }' . "\r\n" . '		//有其他项' . "\r\n" . '		if( isHaveOther == 1 )' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		   if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '		   }' . "\r\n" . '		}' . "\r\n" . '		var theTextObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '		if( isHaveShow == true )' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'\';' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'none\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function getAutoWeightNoSamePageIsNeg(baseID,theAllValue,theSelectValue,questionID,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '    var theAllValueArray = theAllValue.split(\',\');' . "\r\n" . '	for(i=0;i<=theAllValueArray.length-1;i++){' . "\r\n" . '	    theOptionObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '		if( theOptionObj != null )' . "\r\n" . '		{' . "\r\n" . '			if( theSelectValue.indexOf(","+theAllValueArray[i] + ",") != -1 )' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'none\';' . "\r\n" . '				theInputObj = eval("document.getElementById(\'weightcheck_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '' . "\r\n" . '				//总剩余' . "\r\n" . ' 				var tarObj = eval("document.Survey_Form.option_" + questionID + "_total");' . "\r\n" . '				var theInputObjValue = Trim(theInputObj.getElementsByTagName("input")[0].value);' . "\r\n" . '				if( !isNaN(parseFloat(theInputObjValue)) )' . "\r\n" . '				{' . "\r\n" . '					var rtnValue = accAdd(Number(tarObj.value),parseFloat(theInputObjValue));' . "\r\n" . '				}' . "\r\n" . '				else' . "\r\n" . '				{' . "\r\n" . '					var rtnValue = Number(tarObj.value);' . "\r\n" . '				}' . "\r\n" . '				tarObj.value = rtnValue.toString();' . "\r\n" . '				TextUnInput(theInputObj.getElementsByTagName("input")[0]);' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'\';' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//判断刻度是否显示' . "\r\n" . '	var isHaveShow = false;' . "\r\n" . '	for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		if( theQtnObj !=null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	var theTextObj = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '	if( isHaveShow == true )' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'\';' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'none\';' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '' . "\r\n" . '//判断比重' . "\r\n" . 'function CheckWeight(questionID,baseID,minOptionNum,maxOptionNum,maxNum,isHaveOther,isRequired,strText)' . "\r\n" . '{' . "\r\n" . '    var obj,objIn,optionTotalNum = 0;' . "\r\n" . '	var theShowId = 0;' . "\r\n" . '	for(i=minOptionNum;i<=maxOptionNum;i++)' . "\r\n" . '	{' . "\r\n" . '	    obj = eval("document.Survey_Form.option_" + questionID + "_" + i );' . "\r\n" . '		var objValue = Trim(obj.value);' . "\r\n" . '		if( obj != null )' . "\r\n" . '		{' . "\r\n" . '			if( objValue != \'\' && !isNaN(parseFloat(objValue)) )' . "\r\n" . '			{' . "\r\n" . '				optionTotalNum = accAdd(optionTotalNum,parseFloat(objValue));' . "\r\n" . '			}' . "\r\n" . '			objIn = eval("document.getElementById(\'weight_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '			if( objIn != null && objIn.style.display != \'none\' && theShowId == 0 )' . "\r\n" . '			{' . "\r\n" . '				theShowId = i;' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	if(isHaveOther == 1)' . "\r\n" . '	{' . "\r\n" . '	    obj = eval("document.Survey_Form.option_" + questionID + "_0" );' . "\r\n" . '		var objValue = Trim(obj.value);' . "\r\n" . '		if( obj != null && objValue != \'\' )' . "\r\n" . '		{' . "\r\n" . '			if( !isNaN(parseFloat(objValue)) )' . "\r\n" . '			{' . "\r\n" . '				optionTotalNum = accAdd(optionTotalNum,parseFloat(objValue));' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '    var rtnValue = accSub(maxNum,optionTotalNum);' . "\r\n" . '	if( isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '	   if( rtnValue != 0 )' . "\r\n" . '	   {' . "\r\n" . '		  obj = eval("document.Survey_Form.option_" + questionID + "_" + theShowId );' . "\r\n" . '		  if( obj != null ) obj.focus();' . "\r\n" . '	      $.notification( strText + ",尚有比重值未全部分配");' . "\r\n" . '		  return false;' . "\r\n" . '	   }' . "\r\n" . '    }' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '	   if( rtnValue < 0 && rtnValue != maxNum )' . "\r\n" . '	   {' . "\r\n" . '		  obj = eval("document.Survey_Form.option_" + questionID + "_" + theShowId );' . "\r\n" . '		  if( obj != null ) obj.focus();' . "\r\n" . '	      $.notification( strText + ",尚有比重值未全部分配");' . "\r\n" . '		  return false;' . "\r\n" . '	   }' . "\r\n" . '    }' . "\r\n" . '	return true;' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '';

?>
