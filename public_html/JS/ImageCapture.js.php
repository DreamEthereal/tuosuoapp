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
echo '' . "\r\n" . '//Image' . "\r\n" . 'function onTakePictureSuccessed(path){' . "\r\n" . '	disableAction();' . "\r\n" . '    //将文件加入上传队列' . "\r\n" . '    rexseeUpload.clear();' . "\r\n" . '    rexseeCamera.prepareUpload();' . "\r\n" . '    rexseeUpload.upload( uploadURL + \'?optionID=\'+uploadFlag,\'uploadedfile_\'+uploadFlag);' . "\r\n" . '	rexseeProgressDialog.show(\'正在上传，请稍候......\');' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function onTakePictureFailed(){' . "\r\n" . '	alert("拍摄照片失败！");' . "\r\n" . '}' . "\r\n" . '		' . "\r\n" . 'function onUploadFinished(path,response){' . "\r\n" . '  ' . "\r\n" . '	var theResponseText = response.split(\'|\');' . "\r\n" . '	if( theResponseText[0] == \'false\' )' . "\r\n" . '	{' . "\r\n" . '		eval("document.getElementById(\'fsUploadProgress_"+theResponseText[1]+"\')").innerHTML = "文件上传失败：" + theResponseText[2];' . "\r\n" . '	}' . "\r\n" . '	if( theResponseText[0] == \'true\' )' . "\r\n" . '	{' . "\r\n" . '	    uploadFlag = \'\';' . "\r\n" . '		eval("document.getElementById(\'fsUploadProgress_"+theResponseText[1]+"\')").innerHTML = "文件已成功上传";' . "\r\n" . '		eval("document.getElementById(\'"+theResponseText[1]+"\')").value = theResponseText[2];' . "\r\n" . '' . "\r\n" . '		if( eval("document.getElementById(\'takePic"+theResponseText[1]+"\')") != null )' . "\r\n" . '		{' . "\r\n" . '			eval("document.getElementById(\'takePic"+theResponseText[1]+"\')").disabled = true;' . "\r\n" . '		}' . "\r\n" . '		if( eval("document.getElementById(\'takeViedo"+theResponseText[1]+"\')") != null )' . "\r\n" . '		{' . "\r\n" . '			eval("document.getElementById(\'takeViedo"+theResponseText[1]+"\')").disabled = true;' . "\r\n" . '		}' . "\r\n" . '		if( eval("document.getElementById(\'takeAudio"+theResponseText[1]+"\')") != null )' . "\r\n" . '		{' . "\r\n" . '			eval("document.getElementById(\'takeAudio"+theResponseText[1]+"\')").disabled = true;' . "\r\n" . '		}' . "\r\n" . '		if( rexseeFile.exists(path) )' . "\r\n" . '		{' . "\r\n" . '			rexseeFile.remove(path);' . "\r\n" . '		}' . "\r\n" . '	}	' . "\r\n" . '	enableAction();' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//Viedo' . "\r\n" . 'function onTakeVideoFailed(){' . "\r\n" . '	alert("录像失败！");' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function onTakeVideoSuccessed(path){' . "\r\n" . '	disableAction();' . "\r\n" . '	rexseeCamera.upload( uploadURL + \'?optionID=\'+uploadFlag,\'uploadedfile_\'+uploadFlag);' . "\r\n" . '	rexseeProgressDialog.show(\'正在上传，请稍候......\');' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//Audio' . "\r\n" . 'function onRecordAudioSuccessed(path) {' . "\r\n" . '	disableAction();' . "\r\n" . '	//将文件加入上传队列' . "\r\n" . '    rexseeUpload.clear();' . "\r\n" . '    rexseeAudioRecorder.prepareUpload();' . "\r\n" . '    rexseeUpload.upload( uploadURL + \'?optionID=\'+uploadFlag,\'uploadedfile_\'+uploadFlag);' . "\r\n" . '	rexseeProgressDialog.show(\'正在上传，请稍候......\');' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function onRecordAudioFailed() {' . "\r\n" . '	alert("录音失败！");' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function disableAction() {' . "\r\n" . '	if( document.getElementById(\'SurveyNextSubmit\') != null )  {  document.getElementById(\'SurveyNextSubmit\').disabled = true;  }' . "\r\n" . '	if( document.getElementById(\'SurveyCacheSubmit\') != null ) {  document.getElementById(\'SurveyCacheSubmit\').disabled = true; }' . "\r\n" . '	if( document.getElementById(\'SurveyPreSubmit\') != null )   {  document.getElementById(\'SurveyPreSubmit\').disabled = true;   }' . "\r\n" . '	if( document.getElementById(\'SurveyOverSubmit\') != null )  {  document.getElementById(\'SurveyOverSubmit\').disabled = true;  }' . "\r\n" . '}' . "\r\n" . 'function enableAction() {' . "\r\n" . '	if( document.getElementById(\'SurveyNextSubmit\') != null )  {  document.getElementById(\'SurveyNextSubmit\').disabled = false;  }' . "\r\n" . '	if( document.getElementById(\'SurveyCacheSubmit\') != null ) {  document.getElementById(\'SurveyCacheSubmit\').disabled = false; }' . "\r\n" . '	if( document.getElementById(\'SurveyPreSubmit\') != null )   {  document.getElementById(\'SurveyPreSubmit\').disabled = false;   }' . "\r\n" . '	if( document.getElementById(\'SurveyOverSubmit\') != null )  {  document.getElementById(\'SurveyOverSubmit\').disabled = false;  }' . "\r\n" . '}';

?>
