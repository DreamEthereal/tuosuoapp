<?php
//dezend by http://www.yunlu99.com/
echo 'function count_yesno_qtn(questionID)' . "\r\n" . '{' . "\r\n" . '	var tplText = \'<table width="100%" class="pertable"><tr><td height=25 class="question" valign=center>{$questionRequire}{$questionName}&nbsp;<span class=notes>{$questionNotes}</span></td></tr><tr><td><table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-bottom:10px" cellSpacing=0 cellPadding=0 borderColor=#ffffff border=1><tr><td colspan=4 class="answer">&nbsp;样本数：<font color=red><b>&nbsp;{$totalResponseNum}</b></font>&nbsp;&nbsp;&nbsp;有效回复数：&nbsp;<font color=red><b>{$rep_answerNum}</b></font>&nbsp;&nbsp;&nbsp;缺失数：&nbsp;<font color=red><b>{$skip_answerNum}</b></font></td></tr><tr style="color:white;"><td bgcolor=#e51b2e class="answer" style="color:white;">&nbsp;选项名称</td><td bgcolor=#e51b2e align=center class="answer" style="color:white;">&nbsp;频数</td><td bgcolor=#e51b2e align=center class="answer" style="color:white;">&nbsp;百分比</td><td bgcolor=#e51b2e align=center class="answer" style="color:white;">&nbsp;有效百分比</td></tr>{foreach $qtns as $i => $qtn}<tr style="background-color:#e4e0ea"><td nowrap bgcolor=#a9abaf class="answer">&nbsp;{$qtn.optionName}</td><td nowrap align=center class="answer">&nbsp;<b>{$qtn.answerNum}</b></td><td nowrap align=center class="answer">&nbsp;{$qtn.optionPercent} %</td><td align=center class="answer">&nbsp;{$qtn.optionValidPercent} %</td></tr>{/foreach}</table></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '	var questionRequire = \'\';' . "\r\n" . '	var questionName = \'\';' . "\r\n" . '	var questionNotes = \'\';' . "\r\n" . '	if( QtnListArray[questionID].isRequired == 1 )' . "\r\n" . '	{' . "\r\n" . '		questionRequire = \'<span class=red>*</span>\';' . "\r\n" . '	}' . "\r\n" . '	questionName = qJsonCharFilter(QtnListArray[questionID].questionName);' . "\r\n" . '	questionNotes = \'[是非题]\';' . "\r\n" . '    ' . "\r\n" . '	//总数' . "\r\n" . '	var sql = " SELECT COUNT(*) as totalResponseNum FROM eq_response_"+qid+" where overFlag = 1;";' . "\r\n" . '	var CRow = eval(\'(\'+getDbRows(sql,\'rexsee:enableq.db\')+\')\');' . "\r\n" . '	var totalResponseNum = CRow.rows[0][0];' . "\r\n" . '	sql = " SELECT COUNT(*) AS optionResponseNum FROM eq_response_"+qid+"  WHERE option_"+questionID+" = \'0\' and overFlag = 1;";' . "\r\n" . '	var CRow = eval(\'(\'+getDbRows(sql,\'rexsee:enableq.db\')+\')\');' . "\r\n" . '	var skip_answerNum = CRow.rows[0][0];' . "\r\n" . '	var rep_answerNum = totalResponseNum - skip_answerNum;' . "\r\n" . '' . "\r\n" . '	var dataqtn = "{ ";' . "\r\n" . '    dataqtn += " totalResponseNum: \'"+totalResponseNum+"\',";' . "\r\n" . '    dataqtn += " skip_answerNum: \'"+skip_answerNum+"\',";' . "\r\n" . '    dataqtn += " rep_answerNum: \'"+rep_answerNum+"\',";' . "\r\n" . '' . "\r\n" . '    dataqtn += " questionRequire: \'"+questionRequire+"\',";' . "\r\n" . '    dataqtn += " questionName: \'"+questionName+"\',";' . "\r\n" . '    dataqtn += " questionNotes: \'"+questionNotes+"\',";' . "\r\n" . '' . "\r\n" . '	dataqtn += " qtns:[";' . "\r\n" . '	' . "\r\n" . '    for( var question_yesnoID in YesNoListArray[questionID])' . "\r\n" . '	{' . "\r\n" . '		var theQuestionArray = YesNoListArray[questionID][question_yesnoID];' . "\r\n" . '		dataqtn += "{";' . "\r\n" . '		dataqtn += " optionName:\'"+qJsonCharFilter(theQuestionArray.optionName)+"\',";' . "\r\n" . '		' . "\r\n" . '		var osql = " SELECT COUNT(*) AS optionResponseNum FROM eq_response_"+qid+" WHERE option_"+questionID+" = \'"+question_yesnoID+"\' and overFlag = 1;";' . "\r\n" . '		var ORow = eval(\'(\'+getDbRows(osql,\'rexsee:enableq.db\')+\')\');' . "\r\n" . '		var answerNum = ORow.rows[0][0];' . "\r\n" . '		var optionPercent = CountPercent(answerNum,totalResponseNum);' . "\r\n" . '		var optionValidPercent = CountPercent(answerNum,rep_answerNum);' . "\r\n" . '' . "\r\n" . '	    dataqtn += " answerNum: \'"+answerNum+"\',";' . "\r\n" . '	    dataqtn += " optionPercent: \'"+optionPercent+"\',";' . "\r\n" . '	    dataqtn += " optionValidPercent: \'"+optionValidPercent+"\' ";' . "\r\n" . '		' . "\r\n" . '		dataqtn += "},";' . "\r\n" . '	}' . "\r\n" . '	dataqtn = dataqtn.substr(0,dataqtn.length-1)+"] }";' . "\r\n" . '	var jsondata = eval(\'(\'+dataqtn+\')\');' . "\r\n" . '' . "\r\n" . '	var t = new jSmart(tplText);' . "\r\n" . '	survey_content_container_html += t.fetch(jsondata);' . "\r\n" . '}';

?>
