<?php
//dezend by http://www.yunlu99.com/
echo '//建立一个图像对象' . "\r\n" . 'var imgCheckedObj=new Image();                    ' . "\r\n" . '//全部图片格式类型' . "\r\n" . 'var AllImgExt=".jpg|.gif|.png|";' . "\r\n" . '//全局变量 图片相关属性' . "\r\n" . 'var FileObj,ImgFileSize,ImgWidth,ImgHeight,FileExt,ErrMsg,FileMsg,HasCheked,IsImg;' . "\r\n" . '//允许上传的文件类型' . "\r\n" . 'var AllowExt=".jpg|.gif|.png|.swf|";' . "\r\n" . '//允许上传图片文件的大小 0为无限制  单位：KB ' . "\r\n" . 'var AllowImgFileSize=300;   ' . "\r\n" . '//允许上传的图片的宽度 0为无限制　单位：px' . "\r\n" . 'var AllowImgWidth= 400;' . "\r\n" . '//允许上传的图片的高度 0为无限制　单位：px' . "\r\n" . 'var AllowImgHeight= 400;     ' . "\r\n" . '' . "\r\n" . 'imgHasChecked=false;' . "\r\n" . '' . "\r\n" . 'function CheckExt(obj)' . "\r\n" . '{' . "\r\n" . '	ErrMsg = "";' . "\r\n" . '	FileMsg = "";' . "\r\n" . '	FileObj = obj;' . "\r\n" . '	IsImg = false;' . "\r\n" . '	imgHasChecked = false;' . "\r\n" . '	if(obj.value == "")return false;' . "\r\n" . '	FileExt = obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();' . "\r\n" . '	if(AllowExt != 0 && AllowExt.indexOf(FileExt + "|") == -1)  //判断文件类型是否允许上传' . "\r\n" . '	{' . "\r\n" . '		ErrMsg="文件类型不允许上传。请上传 "+AllowExt+" 类型的文件，当前文件类型为"+FileExt;' . "\r\n" . '		ShowMsg(ErrMsg,false);' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	if(AllImgExt.indexOf(FileExt+"|") != -1)    //如果图片文件，则进行图片信息处理' . "\r\n" . '	{' . "\r\n" . '		IsImg=true;' . "\r\n" . '		imgCheckedObj.src = obj.value;' . "\r\n" . '		CheckProperty(obj);' . "\r\n" . '		return true;' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . '//检测图像属性' . "\r\n" . 'function CheckProperty(obj)    ' . "\r\n" . '{' . "\r\n" . '	FileObj=obj;' . "\r\n" . '	//检测是否为正确的图像文件　返回出错信息并重置' . "\r\n" . '	if(ErrMsg!="")     ' . "\r\n" . '	{' . "\r\n" . '		ShowMsg(ErrMsg,false);' . "\r\n" . '		return false;     ' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	//如果图像是未加载完成进行循环检测' . "\r\n" . '	if(imgCheckedObj.readyState!="complete") ' . "\r\n" . '	{' . "\r\n" . '		setTimeout("CheckProperty(FileObj)",500);' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '    ' . "\r\n" . '	//取得图片文件的大小' . "\r\n" . '	ImgFileSize = Math.round(imgCheckedObj.fileSize/1024*100)/100;' . "\r\n" . '	//取得图片的宽度' . "\r\n" . '	ImgWidth=imgCheckedObj.width;' . "\r\n" . '	//取得图片的高度' . "\r\n" . '	ImgHeight=imgCheckedObj.height;    ' . "\r\n" . '' . "\r\n" . '	if(AllowImgWidth!=0	&&	AllowImgWidth < ImgWidth)' . "\r\n" . '		ErrMsg	=	ErrMsg+"\\n图片宽度超过限制。请上传宽度小于"+AllowImgWidth+"px的文件，当前图片宽度为"+ImgWidth+"px";' . "\r\n" . '' . "\r\n" . '	if(AllowImgHeight!=0 && AllowImgHeight < ImgHeight)' . "\r\n" . '		ErrMsg=ErrMsg+"\\n图片高度超过限制。请上传高度小于"+AllowImgHeight+"px的文件，当前图片高度为"+ImgHeight+"px";' . "\r\n" . '' . "\r\n" . '	if(AllowImgFileSize!=0 && AllowImgFileSize < ImgFileSize)' . "\r\n" . '		ErrMsg=ErrMsg+"\\n图片文件大小超过限制。请上传小于"+AllowImgFileSize+"KB的文件，当前文件大小为"+ImgFileSize+"KB";' . "\r\n" . '' . "\r\n" . '    if(ErrMsg!="")' . "\r\n" . '        ShowMsg(ErrMsg,false);' . "\r\n" . '    else' . "\r\n" . '        ShowMsg(FileMsg,true);' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function ShowMsg(msg,tf) ' . "\r\n" . '{' . "\r\n" . '   if(!tf)' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById("submitAction").disabled = true;' . "\r\n" . '      imgHasChecked = false;' . "\r\n" . '	  $.notification(msg);' . "\r\n" . '   }' . "\r\n" . '   else' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById("submitAction").disabled = false;' . "\r\n" . '      imgHasChecked=true;' . "\r\n" . '   }' . "\r\n" . '}' . "\r\n" . '';

?>
