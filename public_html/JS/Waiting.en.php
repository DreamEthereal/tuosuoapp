<?php
//dezend by http://www.yunlu99.com/
echo 'var wait = secs * 1000;' . "\r\n" . '' . "\r\n" . 'function initWaiting()' . "\r\n" . '{' . "\r\n" . '   if( document.getElementById("SurveyOverSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '       document.getElementById("SurveyOverSubmit").disabled = true;' . "\r\n" . '	   document.getElementById("SurveyOverSubmit").value = "Submit(" + secs + ")";' . "\r\n" . '   }' . "\r\n" . '' . "\r\n" . '   if( document.getElementById("SurveyNextSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '       document.getElementById("SurveyNextSubmit").disabled = true;' . "\r\n" . '	   document.getElementById("SurveyNextSubmit").value = " Next(" + secs + ") ";' . "\r\n" . '   }' . "\r\n" . '   for( tmp = 1; tmp <= secs; tmp++) {' . "\r\n" . '		   window.setTimeout("Update(" + tmp + ")", tmp * 1000);' . "\r\n" . '   }' . "\r\n" . '   window.setTimeout("Timer()", wait);' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function Update(num, value) ' . "\r\n" . '{' . "\r\n" . '   if( document.getElementById("SurveyOverSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '		if(num == (wait/1000)) {' . "\r\n" . '		   document.getElementById("SurveyOverSubmit").value = " Submit ";' . "\r\n" . '		} else {' . "\r\n" . '		   printnr = (wait / 1000) - num;' . "\r\n" . '		   document.getElementById("SurveyOverSubmit").value = "Submit(" + printnr + ")";' . "\r\n" . '	    }' . "\r\n" . '   }' . "\r\n" . '' . "\r\n" . '   if( document.getElementById("SurveyNextSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '		if(num == (wait/1000)) {' . "\r\n" . '		   document.getElementById("SurveyNextSubmit").value = " Next>> ";' . "\r\n" . '		} else {' . "\r\n" . '		   printnr = (wait / 1000) - num;' . "\r\n" . '		   document.getElementById("SurveyNextSubmit").value = " Next(" + printnr + ") ";' . "\r\n" . '	    }' . "\r\n" . '   }' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function Timer() {' . "\r\n" . '   if( document.getElementById("SurveyOverSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById("SurveyOverSubmit").disabled = false;' . "\r\n" . '   }' . "\r\n" . '   if( document.getElementById("SurveyNextSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById("SurveyNextSubmit").disabled = false;' . "\r\n" . '   }' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'var limitedTime = limitedSecs * 1000;' . "\r\n" . 'function initLimitedTime()' . "\r\n" . '{' . "\r\n" . '   for( tmp = limitedSecs; tmp >=0 ; tmp--) {' . "\r\n" . '	  window.setTimeout("limitedUpdate(" + tmp + ")", tmp * 1000);' . "\r\n" . '   }' . "\r\n" . '   window.setTimeout("limitedTimer()", limitedTime);' . "\r\n" . '}' . "\r\n" . 'function limitedUpdate(num, value) ' . "\r\n" . '{' . "\r\n" . '	var lprintnr = (limitedTime / 1000) - num;' . "\r\n" . '    document.getElementById("limitedBar").innerHTML = "&nbsp;Time remaining:<b><font color=red>" + lprintnr + "</font></b>s";' . "\r\n" . '}' . "\r\n" . 'function limitedTimer(){' . "\r\n" . '   document.getElementById("limitedBar").innerHTML = "&nbsp;Time remaining:<b><font color=red>0</font></b>s";' . "\r\n" . '   if( document.getElementById("SurveyOverSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '       document.getElementById("SurveyOverSubmit").disabled = true;' . "\r\n" . '   }' . "\r\n" . '' . "\r\n" . '   if( document.getElementById("SurveyNextSubmit") != null )' . "\r\n" . '   {' . "\r\n" . '       document.getElementById("SurveyNextSubmit").disabled = true;' . "\r\n" . '   }' . "\r\n" . '}' . "\r\n" . '';

?>
