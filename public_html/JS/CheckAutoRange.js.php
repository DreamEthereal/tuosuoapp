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
echo '' . "\r\n" . '//自动选项同页展现' . "\r\n" . 'function getAutoRangeSamePage(baseID,isList,questionID,startScale,endScale,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '	 var objField = eval( "document.Survey_Form.option_" + baseID );' . "\r\n" . '	 if (objField != null ){' . "\r\n" . '		 if( isList == 1 )  //列表框' . "\r\n" . '		 {' . "\r\n" . '			 for( i=0;i<objField.options.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField.options[i].selected && objField.options[i].value != \'\')' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						//移除原有选择' . "\r\n" . '						for(j=endScale;j>=startScale;j--)' . "\r\n" . '						{' . "\r\n" . '							theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+objField.options[i].value+\'_\'+j+"\')");' . "\r\n" . '							if( theInputObj != null) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '						}' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			 for( i=0;i<objField.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if(objField[i].checked )' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						//移除原有选择' . "\r\n" . '						for(j=endScale;j>=startScale;j--)' . "\r\n" . '						{' . "\r\n" . '							theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+objField[i].value+\'_\'+j+"\')");' . "\r\n" . '							if( theInputObj != null) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '						}' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		} //End for if' . "\r\n" . '		//判断评分刻度是否显示' . "\r\n" . '		var isHaveShow = false;' . "\r\n" . '		for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		   if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '				break;' . "\r\n" . '		   }' . "\r\n" . '	    }' . "\r\n" . '		//有其他项' . "\r\n" . '		if( isHaveOther == 1 )' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		   if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '		   }' . "\r\n" . '		}' . "\r\n" . '		var theTextObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '		if( isHaveShow == true )' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'\';' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'none\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function getAutoRangeNoSamePage(baseID,theAllValue,theSelectValue,questionID,startScale,endScale,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '    var theAllValueArray = theAllValue.split(\',\');' . "\r\n" . '	for(i=0;i<=theAllValueArray.length-1;i++){' . "\r\n" . '	    theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '		if( theOptionObj != null )' . "\r\n" . '		{' . "\r\n" . '			if( theSelectValue.indexOf(","+theAllValueArray[i] + ",") == -1 )' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'none\';' . "\r\n" . '				//移除原有选择' . "\r\n" . '				for(j=endScale;j>=startScale;j--)' . "\r\n" . '				{' . "\r\n" . '				   theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+\'_\'+j+"\')");' . "\r\n" . '				   if( theInputObj != null ) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '				}' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'\';' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//判断刻度是否显示' . "\r\n" . '	var isHaveShow = false;' . "\r\n" . '	for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	var theTextObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '	if( isHaveShow == true )' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'\';' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'none\';' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//自动选项同页展现Neg' . "\r\n" . 'function getAutoRangeSamePageIsNeg(baseID,isList,questionID,startScale,endScale,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '	 var objField = eval( "document.Survey_Form.option_" + baseID );' . "\r\n" . '	 if (objField != null ){' . "\r\n" . '		 if( isList == 1 )  //列表框' . "\r\n" . '		 {' . "\r\n" . '			 for( i=0;i<objField.options.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+objField.options[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField.options[i].value != \'\' && objField.options[i].selected != true )' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						//移除原有选择' . "\r\n" . '						for(j=endScale;j>=startScale;j--)' . "\r\n" . '						{' . "\r\n" . '							theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+objField.options[i].value+\'_\'+j+"\')");' . "\r\n" . '							if( theInputObj != null) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '						}' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			 for( i=0;i<objField.length;i++ ) {' . "\r\n" . '			   theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+objField[i].value+"\')");' . "\r\n" . '			   if( theOptionObj != null ){' . "\r\n" . '				   if( objField[i].checked != true )' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'\';' . "\r\n" . '				   }' . "\r\n" . '				   else' . "\r\n" . '				   {' . "\r\n" . '						theOptionObj.style.display = \'none\';' . "\r\n" . '						//移除原有选择' . "\r\n" . '						for(j=endScale;j>=startScale;j--)' . "\r\n" . '						{' . "\r\n" . '							theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+objField[i].value+\'_\'+j+"\')");' . "\r\n" . '							if( theInputObj != null) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '						}' . "\r\n" . '				   }' . "\r\n" . '			   }' . "\r\n" . '			}' . "\r\n" . '		} //End for if' . "\r\n" . '		//判断刻度是否显示' . "\r\n" . '		var isHaveShow = false;' . "\r\n" . '		for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		   if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '				break;' . "\r\n" . '		   }' . "\r\n" . '	    }' . "\r\n" . '		//有其他项' . "\r\n" . '		if( isHaveOther == 1 )' . "\r\n" . '		{' . "\r\n" . '		   theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		   if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		   {' . "\r\n" . '				isHaveShow = true;' . "\r\n" . '		   }' . "\r\n" . '		}' . "\r\n" . '		var theTextObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '		if( isHaveShow == true )' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'\';' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			theTextObj.style.display = \'none\';' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function getAutoRangeNoSamePageIsNeg(baseID,theAllValue,theSelectValue,questionID,startScale,endScale,theFirstID,theLastID,isHaveOther)' . "\r\n" . '{' . "\r\n" . '    var theAllValueArray = theAllValue.split(\',\');' . "\r\n" . '	for(i=0;i<=theAllValueArray.length-1;i++){' . "\r\n" . '	    theOptionObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+"\')");' . "\r\n" . '		if( theOptionObj != null )' . "\r\n" . '		{' . "\r\n" . '			if( theSelectValue.indexOf(","+theAllValueArray[i] + ",") != -1 )' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'none\';' . "\r\n" . '				//移除原有选择' . "\r\n" . '				for(j=endScale;j>=startScale;j--)' . "\r\n" . '				{' . "\r\n" . '				   theInputObj = eval("document.getElementById(\'rangecheck_"+baseID+"_"+questionID+"_"+theAllValueArray[i]+\'_\'+j+"\')");' . "\r\n" . '				   if( theInputObj != null ) theInputObj.getElementsByTagName("input")[0].checked = false;' . "\r\n" . '				}' . "\r\n" . '			}' . "\r\n" . '			else' . "\r\n" . '			{' . "\r\n" . '				theOptionObj.style.display = \'\';' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//判断刻度是否显示' . "\r\n" . '	var isHaveShow = false;' . "\r\n" . '	for(i=theFirstID;i<=theLastID;i++)' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+i+"\')");' . "\r\n" . '		if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '			break;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	//有其他项' . "\r\n" . '	if( isHaveOther == 1 )' . "\r\n" . '	{' . "\r\n" . '		theQtnObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_"+0+"\')");' . "\r\n" . '		if( theQtnObj != null && theQtnObj.style.display != \'none\' )' . "\r\n" . '		{' . "\r\n" . '			isHaveShow = true;' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	var theTextObj = eval("document.getElementById(\'range_"+baseID+"_"+questionID+"_text"+"\')");' . "\r\n" . '	if( isHaveShow == true )' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'\';' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		theTextObj.style.display = \'none\';' . "\r\n" . '	}' . "\r\n" . '}';

?>
