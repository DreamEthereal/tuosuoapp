<?php
//dezend by http://www.yunlu99.com/
echo 'function create_empty_qtn(questionID)' . "\r\n" . '{' . "\r\n" . '	//处理页面字段' . "\r\n" . '	this_fields_list += \'option_\'+questionID+\'|\';' . "\r\n" . '	this_fields_type += \'3|\';' . "\r\n" . '' . "\r\n" . '	//逻辑条件' . "\r\n" . '	var QuestionCon = _GetQuestionCond(questionID,qid);' . "\r\n" . '	if( QuestionCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		check_survey_conditions_list += "\\tif("+QuestionCon+")\\n\\t{\\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\tdocument.getElementById(\'option_"+questionID+"\').value=\'1\';\\n\\t} \\n";' . "\r\n" . '		check_survey_conditions_list += "\\telse { \\n";' . "\r\n" . '		check_survey_conditions_list += "\\t\\tdocument.getElementById(\'option_"+questionID+"\').value=\'0\';\\n\\t} \\n";' . "\r\n" . '	}' . "\r\n" . '	survey_content_container_html += \'<table width="100%" class="pertable" id="question_\'+questionID+\'"><tr><td class="question" valign=center><input type="hidden" name="option_\'+questionID+\'" id="option_\'+questionID+\'" value="0"></td></tr></table>\';' . "\r\n" . '' . "\r\n" . '	//问卷结束条件' . "\r\n" . '	var EndSurveyCon = _GetSurveyQuotaCond(questionID,qid,\'cn\');' . "\r\n" . '	if( EndSurveyCon != \'\' )' . "\r\n" . '	{' . "\r\n" . '		survey_quota_list += EndSurveyCon;' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	//不显示' . "\r\n" . '	check_survey_conditions_list += "\\t$(\'#question_"+questionID+"\').hide();\\n";' . "\r\n" . '}';

?>
