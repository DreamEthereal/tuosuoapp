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
		switch ($theQtnArray['questionType']) {
		case '1':
		case '2':
		case '24':
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			break;

		case '6':
			foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
				$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}

			break;
		}
	}
}

echo 'var intRowIndex = 0;' . "\r\n" . 'function insertSGGRow(tbIndex)' . "\r\n" . '{' . "\r\n" . '	 var theTd1Text = "&nbsp;" + tbIndex + "：";' . "\r\n" . '	 var theTd2Text = "<select name=\'questionID_"+tbIndex+"\' id=\'questionID_"+tbIndex+"\' onchange=\'javascript:ShowQuestionCond(this,"+tbIndex+")\' style=\'width:830px;height:22px;line-height:22px;\'><option value=\'\'>请选择...</option>';
echo $questionList;
echo '</select><br/><span id=\'QuestionCond_"+tbIndex+"\'></span>";' . "\r\n" . '	 var theTd3Text = "<input name=\'price_"+tbIndex+"\' id=\'price_"+tbIndex+"\' size=6>";' . "\r\n" . '	 var objRow = document.getElementById("theSGGDataTable").insertRow(tbIndex);' . "\r\n" . '	 objRow.style.borderBottom = "1px solid #cacaca";' . "\r\n" . '	 var objCel = objRow.insertCell(0);' . "\r\n" . '	 objCel.innerHTML =theTd1Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(1);' . "\r\n" . '	 objCel.innerHTML = theTd2Text;' . "\r\n" . '	 objCel.align = "left";' . "\r\n" . '	 //objCel.style.paddingTop = "5px";' . "\r\n" . '	 objCel.style.paddingBottom = "5px";' . "\r\n" . '	 var objCel = objRow.insertCell(2);' . "\r\n" . '	 objCel.innerHTML = theTd3Text;' . "\r\n" . '	 objCel.align = "left";' . "\r\n" . '}' . "\r\n" . 'function deleteSGGRow()' . "\r\n" . '{' . "\r\n" . '	 var theObj = document.getElementById("theSGGDataTable");' . "\r\n" . '	 U = theObj.rows.length-1;' . "\r\n" . '	 if( U > 1 ) {' . "\r\n" . '		theObj.deleteRow(U);' . "\r\n" . '	 }' . "\r\n" . '	 else {' . "\r\n" . '		$.notification("分析变量及其相关信息不能全部删除") ;' . "\r\n" . '	 }' . "\r\n" . '}' . "\r\n" . '';

?>
