<?php
//dezend by http://www.yunlu99.com/
echo "\r\n";
$questionList = '';
if (isset($_GET['type']) && ($_GET['type'] == 1)) {
	define('ROOT_PATH', '../');
	require_once ROOT_PATH . 'Entry/Global.setup.php';
	include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
	include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
	_checkroletype('1|2|3|5|7');
	$SQL = ' SELECT surveyID,status,surveyName,isPublic,ajaxRtnValue,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
	$Sur_G_Row = $DB->queryFirstRow($SQL);

	if ($Sur_G_Row['status'] == '0') {
		_showerror($lang['system_error'], $lang['design_survey_now']);
	}

	if (($Sur_G_Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php')) {
		$theSID = $Sur_G_Row['surveyID'];
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $Sur_G_Row['surveyID'] . '/' . md5('Qtn' . $Sur_G_Row['surveyID']) . '.php';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (in_array($theQtnArray['questionType'], array(1, 2, 3, 4, 6, 7, 10, 12, 13, 15, 17, 18, 19, 20, 21, 23, 24, 25, 26, 28, 30, 31))) {
			$questionName = qnospecialchar($theQtnArray['questionName']) . '&nbsp;[' . $lang['question_type_' . $theQtnArray['questionType']] . ']';
			$questionList .= '<option value=\'' . $questionID . '\'>' . addslashes($questionName) . '</option>';
		}
	}
}

echo '' . "\r\n" . 'var dataRequest = false;' . "\r\n" . 'function AjaxDataRequest(url)' . "\r\n" . '{' . "\r\n" . '	dataRequest = false;' . "\r\n" . '	if(window.ActiveXObject)' . "\r\n" . '	{' . "\r\n" . '		try  // IE' . "\r\n" . '		{' . "\r\n" . '			dataRequest = new ActiveXObject("Msxml2.XMLHTTP");' . "\r\n" . '		}' . "\r\n" . '		catch (e)' . "\r\n" . '		{' . "\r\n" . '			try' . "\r\n" . '			{' . "\r\n" . '				dataRequest = new ActiveXObject("Microsoft.XMLHTTP");' . "\r\n" . '			}' . "\r\n" . '			catch (e) {}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else if(window.XMLHttpRequest)' . "\r\n" . '	{' . "\r\n" . '		// Mozilla, Safari,...' . "\r\n" . '		dataRequest = new XMLHttpRequest();' . "\r\n" . '		if (dataRequest.overrideMimeType)' . "\r\n" . '		{' . "\r\n" . '			dataRequest.overrideMimeType(\'text/xml\');' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	if (!dataRequest)' . "\r\n" . '	{' . "\r\n" . '		alert("Cannot create an XMLHTTP instance");' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '	dataRequest.onreadystatechange = ChangeDataSourceTable;' . "\r\n" . '	dataRequest.open(\'GET\', url, true);' . "\r\n" . '	dataRequest.send(null);' . "\r\n" . '}' . "\r\n" . 'function ChangeDataSourceTable()' . "\r\n" . '{' . "\r\n" . '	if (dataRequest.readyState == 4)' . "\r\n" . '	{' . "\r\n" . '		if(dataRequest.status == 200)' . "\r\n" . '		{' . "\r\n" . '			document.getElementById(\'dataSourceTable\').innerHTML = dataRequest.responseText;' . "\r\n" . '			//数据源' . "\r\n" . '			$(\'.analyse_change\').click(function(e){' . "\r\n" . '				e.preventDefault();' . "\r\n" . '				$(\'.analyse_change_list\').show();' . "\r\n" . '			});' . "\r\n" . '			$(document).click(function(e){' . "\r\n" . '				var target = e.target;' . "\r\n" . '				if(!$(target).hasClass(\'analyse_change\') && !$(target).parents().hasClass(\'analyse_change_list\')){' . "\r\n" . '					$(\'.analyse_change_list\').hide();' . "\r\n" . '				}' . "\r\n" . '			});' . "\r\n" . '		}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			alert(\'网络传输问题\');' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function AjaxShowDataSource(surveyID,type)' . "\r\n" . '{' . "\r\n" . '	var url = "../DataSource/AjaxShowDataSource.php?surveyID="+surveyID+"&type="+type;' . "\r\n" . '	AjaxDataRequest(url);' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'var intRowIndex = 0;' . "\r\n" . 'function insertRow(tbIndex)' . "\r\n" . '{' . "\r\n" . '	 var theTd1Text = "&nbsp;" + tbIndex + "：";' . "\r\n" . '	 var theTd2Text = "<select name=\'fieldsID["+tbIndex+"]\' id=\'fieldsID_"+tbIndex+"\' onchange=\'javascript:ShowQuestionCond(this,"+tbIndex+")\' style=\'width:700px\'><option value=\'\'>请选择...</option>';
echo $questionList;
echo '</select><br/><span id=\'QuestionCond_"+tbIndex+"\'></span>";' . "\r\n" . '	 var objRow = document.getElementById("theOptionTable").insertRow(tbIndex);' . "\r\n" . '	 var objCel = objRow.insertCell(0);' . "\r\n" . '	 objCel.innerHTML =theTd1Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(1);' . "\r\n" . '	 objCel.innerHTML = theTd2Text;' . "\r\n" . '}' . "\r\n" . 'function deleteRow()' . "\r\n" . '{' . "\r\n" . '	 var theObj = document.getElementById("theOptionTable");' . "\r\n" . '	 U = theObj.rows.length-1;' . "\r\n" . '	 if( U > 1 ) {' . "\r\n" . '		theObj.deleteRow(U);' . "\r\n" . '	 }' . "\r\n" . '	 else {' . "\r\n" . '		$.notification("扩展数据源条件不能全部删除") ;' . "\r\n" . '	 }' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'var delRequest = false;' . "\r\n" . 'function AjaxDelRequest(url,surveyID,type)' . "\r\n" . '{' . "\r\n" . '	delRequest = false;' . "\r\n" . '	if(window.ActiveXObject)' . "\r\n" . '	{' . "\r\n" . '		try  // IE' . "\r\n" . '		{' . "\r\n" . '			delRequest = new ActiveXObject("Msxml2.XMLHTTP");' . "\r\n" . '		}' . "\r\n" . '		catch (e)' . "\r\n" . '		{' . "\r\n" . '			try' . "\r\n" . '			{' . "\r\n" . '				delRequest = new ActiveXObject("Microsoft.XMLHTTP");' . "\r\n" . '			}' . "\r\n" . '			catch (e) {}' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	else if(window.XMLHttpRequest)' . "\r\n" . '	{' . "\r\n" . '		// Mozilla, Safari,...' . "\r\n" . '		delRequest = new XMLHttpRequest();' . "\r\n" . '		if (delRequest.overrideMimeType)' . "\r\n" . '		{' . "\r\n" . '			delRequest.overrideMimeType(\'text/xml\');' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '	' . "\r\n" . '	if (!delRequest)' . "\r\n" . '	{' . "\r\n" . '		alert("Cannot create an XMLHTTP instance");' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '	delRequest.onreadystatechange = function () {' . "\r\n" . '		if (dataRequest.readyState == 4)' . "\r\n" . '		{' . "\r\n" . '			if(dataRequest.status == 200)' . "\r\n" . '			{' . "\r\n" . '				$.notification("删除数据源定义成功");' . "\r\n" . '				AjaxShowDataSource(surveyID,type);' . "\r\n" . '			}' . "\r\n" . '		}			' . "\r\n" . '	};' . "\r\n" . '	delRequest.open(\'GET\', url, true);' . "\r\n" . '	delRequest.send(null);' . "\r\n" . '}' . "\r\n" . '';

?>
