<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('Security Violation');
}

$lang['passport_is_permit'] = 'Do you confirm that you has the authority to do something?';
$lang['administratorsname_is_exist'] = 'Usename is existed';
$lang['register_succeed'] = 'Register';
$lang['no_get_passport'] = 'The system does not accept new passport through by online registration, Please contact your system administrator.';
$lang['no_exist_username'] = 'The Username does not exist';
$lang['no_fit_hintpass_username'] = 'The Username does not accord with the safeguard of the password';
$lang['send_pass_succ'] = 'Your password has been sent to your mailbox,and please check it';
$lang['send_pass_title'] = 'Forget the password and sent it again';
$lang['chanage_password_content'] = 'Your password has been modified,and the new password is:';
$lang['error_login_username'] = 'The username or password is wrong, please try again';
$lang['error_login_password'] = 'The username or password is wrong, please try again';
$lang['error_multi_nickname'] = 'In the system,there are multiple nicknames you have entered, unable to accurately identify your identity, please use your email accounts to log in';
$lang['error_login_active'] = 'Your ID has been shielded,and please contact the administrator';
$lang['error_login_auth'] = 'You do not have the authority to fill in the survey,and please contact the administrator';
$lang['login_succeed'] = 'Logining in';
$lang['survey_gname'] = 'Project Name:';
$lang['no_design_survey'] = 'The survey you requested is not designing mode';
$lang['no_edit_survey'] = 'The survey you requested is not deploy mode';
$lang['no_survey'] = 'The survey you requested does not exist';
$lang['design_survey'] = 'The survey you requested is still designing';
$lang['end_survey'] = 'The survey you requested is over time';
$lang['file_survey'] = 'The survey you requested has been archived';
$lang['max_num_survey'] = 'The survey you requested is over the max limitation number';
$lang['ip_permit_survey'] = 'Access forbidden! Your IP address <b><font color=red>(' . _getip() . ')</font></b> is not allowed. Please contact your administrator';
$lang['cookie_permit_survey'] = 'Your IP address has been used to fill in the survey';
$lang['time_permit_survey'] = 'Your IP address <b><font color=red>(' . _getip() . ')</font></b> has been used to fill in the survey';
$lang['no_start_survey'] = 'The survey you requested has not reached the beginning time of the enactment yet';
$lang['no_auth_view_survey'] = 'Do you confirm that you has the authority to preview the survey?';
$lang['no_view_survey'] = 'You can not check the result statistic of the survey';
$lang['members_permit_survey'] = 'You have filled in the survey';
$lang['error_lost_session'] = 'Time out, please reload the survey.';
$lang['wechat_permit_survey'] = 'The survey you requested only within the WeiXin';
$lang['issue_permit_survey'] = 'You do not have the authority to fill in the survey';
$lang['hidden_null_survey'] = 'You do not have the authority to fill in the survey';
$lang['hidden_permit_survey'] = 'You have filled in the survey';
$lang['error_lost_pagenum'] = 'Error page number of the survey, please reload the survey.';
$lang['survey_limit_time'] = 'Time remaining:';
$lang['survey_limit_time_sec'] = 's';
$lang['survey_limit_time_over'] = 'The time you spent have been exceeded the system sets the maximum amount of time:';
$lang['question_type_1'] = 'Choice - True/False';
$lang['question_type_2'] = 'Choice - One Answer';
$lang['question_type_3'] = 'Choice - Multiple Answers';
$lang['question_type_4'] = 'Open Ended - One Line';
$lang['question_type_4_4'] = 'Open Ended - Number';
$lang['question_type_4_6'] = 'Open Ended - Date';
$lang['question_type_5'] = 'Open Ended - Comments Box';
$lang['question_type_6'] = 'Matrix - One Answer per Row';
$lang['question_type_7'] = 'Matrix - Multiple Answers per Row';
$lang['question_type_10'] = 'Open Ended - Ranking';
$lang['question_type_11'] = 'Files Uploading';
$lang['question_type_13'] = 'Choice - Database Dropdown';
$lang['question_type_14'] = 'Open Ended - One or More Lines';
$lang['question_type_15'] = 'Matrix - Rating Scale';
$lang['question_type_16'] = 'Open Ended - Constant Sum';
$lang['question_type_17'] = 'Choice - Automatic Options';
$lang['question_type_18'] = 'Choice - Dropdown List';
$lang['question_type_19'] = 'Matrix - One Answer per Row';
$lang['question_type_20'] = 'Open Ended - Ranking';
$lang['question_type_21'] = 'Matrix - Rating Scale';
$lang['question_type_22'] = 'Open Ended - Constant Sum';
$lang['question_type_23'] = 'Open Ended - Multiple Line';
$lang['question_type_24'] = 'Choice - One Answer';
$lang['question_type_25'] = 'Choice - Multiple Answers';
$lang['question_type_26'] = 'Matrix - One Answer per Cell';
$lang['question_type_27'] = 'Matrix - Open Ended per Cell';
$lang['question_type_28'] = 'Matrix - Multiple Answers per Row';
$lang['question_type_29'] = 'Matrix - Open Ended per Cell';
$lang['question_type_30'] = 'Hidden variables';
$lang['question_type_31'] = 'Choice - Cascade';
$lang['minOption'] = 'Min ';
$lang['maxOption'] = 'Max ';
$lang['option'] = ' Options';
$lang['neg_text'] = 'None of the above';
$lang['why_your_order'] = 'Why do you sort them like this';
$lang['no_less_list'] = ',Listing incompletion,does not less than ';
$lang['under_margin'] = ' - Why do you rating them like this';
$lang['rating_unknow'] = 'Unknow';
$lang['no_all_weight'] = ',Some value has not been given.';
$lang['base_qtn_no_exist'] = ',the based question was not exsit or has been deleted';
$lang['order_up'] = 'Up';
$lang['order_down'] = 'Down';
$lang['survey_next_page'] = ' Next>> ';
$lang['survey_pre_page'] = ' <<Pre  ';
$lang['submit_survey'] = 'Submit';
$lang['view_result'] = 'Results';
$lang['survey_start'] = ' Start ';
$lang['system_error'] = 'System Error';
$lang['status_error'] = 'State Error';
$lang['auth_error'] = 'Authority Error';
$lang['unknow_area'] = '未明地区';
$lang['no_text_question'] = 'The survey you requested was not exsit or has been deleted';
$lang['text_question_error'] = 'The status of survey that you requested is abnormal, or something wrong with the question';
$lang['next_page'] = '>';
$lang['prev_page'] = '<';
$lang['first_page'] = '<<';
$lang['last_page'] = '>>';
$lang['upload_error'] = 'Upload Error';
$lang['upload_error_size'] = 'Upload file size less than:';
$lang['no_group'] = '尚未分组';
$lang['survey_submit'] = 'Thanks for your answering';
$lang['error_token_code'] = 'Error token code';
$lang['input_token_code'] = 'Correct token code';
$lang['submit_false'] = '这是预览状态下的模拟数据提交，该数据将不被记录<pre>&nbsp;需要选择“执行”问卷操作<br/>&nbsp;系统才会记录填写问卷的回复数据<br/>&nbsp;执行状态的问卷将不再允许进行问题或选项的添加等操作<br/>&nbsp;但对问题或选项的文字修订操作是开放的</pre>';
$lang['submit_modi_false'] = '这是问卷修改状态下的模拟数据提交，该数据将不被记录<pre>&nbsp;执行状态的问卷将不再允许进行问题或选项的添加等操作<br/>&nbsp;但对问题或选项的文字修订操作是开放的<br/>&nbsp;您可直接点击对应问题或选项的文字直接进行编辑或修改</pre>';
$lang['sure_passport'] = 'Corrent Passport';
$lang['ad_passport'] = 'Incorrect userID or userName';
$lang['get_hash_error'] = '数据通信安全识别码错误';
$lang['get_username_error'] = '要求下行的用户数据不存在';
$lang['get_survey_error'] = '要求下行的问卷数据不存在';
$lang['get_status_error'] = '要求下行的问卷尚在设计中';
$lang['get_type_error'] = '要求下行的问卷不是私有类型';
$lang['get_license_error'] = '标准版系统不允许通过此方法下行数据';
$lang['export_username'] = '用户名';
$lang['export_time'] = '填写时间';
$lang['is_repeat_text'] = ',the value is existed';
$lang['is_not_allowed_text'] = ',the value is not allowed';
$lang['goto_margin'] = ',the responses received has reached the threshold';
$lang['survey_grade'] = 'Score:';
$lang['grade_title'] = 'Survey Conclusion';
$lang['survey_index_title'] = 'Survey Conclusion';
$lang['num_to_quota'] = 'Sorry your responses have exceeded a quota on the survey.';
$lang['not_established'] = ':the expression is not established';
$lang['verifyCode'] = 'Verify Code';
$lang['reloadImage'] = 'Reload';
$lang['error_verifycode'] = 'The verifying code you input is wrong.';
$lang['license_error'] = 'License Error';
$lang['license_no_android'] = '';
$lang['android_error'] = 'Android Client Error';
$lang['private_cookie_survey'] = '';
$lang['proof_title'] = 'Thanks for your support and participation, we issued to you the following awards:';
$lang['proof_name'] = ',No.:';
$lang['proof_pass'] = ',Password:';
$lang['weixin_hongbao'] = 'WeChat red envelope, the amount:';

?>
