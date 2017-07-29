<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5');

if ($_POST['Action'] == 'SelectSubmit') {
	echo '<script>parent.AjaxCallBack(\'[Answer_' . $_POST['questionID'] . ']\');</script>';
	exit();
}

echo '<html>' . "\r\n" . '<head>' . "\r\n" . '<meta http-equiv=content-language content=zh-cn>' . "\r\n" . '<meta http-equiv=content-type content="text/html; charset=gbk">' . "\r\n" . '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">' . "\r\n" . '<LINK href="../CSS/Base.css" rel=stylesheet>' . "\r\n" . '<script type="text/javascript" src="../JS/Jquery.min.js.php"></script>' . "\r\n" . '<script type="text/javascript" src="../JS/Jquery.notification.js.php"></script>' . "\r\n" . '<link type="text/css" rel="stylesheet" href="../CSS/Notification.css" />' . "\r\n" . '<SCRIPT language=javascript src="../JS/CheckQuestion.js.php"></SCRIPT>' . "\r\n" . '<style>' . "\r\n" . ' td { padding:2px 5px 2px 5px;}' . "\r\n" . ' #jquery-notification-message {width:100%;}' . "\r\n" . '</style>' . "\r\n" . '<SCRIPT type=text/javascript>' . "\r\n" . 'function Check_Form_Validator(){' . "\r\n" . '	var ie = document.all && !window.opera;' . "\r\n" . '	if( ie )' . "\r\n" . '	{' . "\r\n" . '		if (!CheckNotNull(document.QuestionForm.questionID, "前述问题")) {return false;}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		$.notification("本系统功能在非IE浏览器工作可能不正常，请方便选择使用IE浏览器。");' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function AjaxCallBack(htmlStr) {' . "\r\n" . '	parent.editTextRange.text = htmlStr ;' . "\r\n" . '	parent.hidePopWin();' . "\r\n" . '}' . "\r\n" . 'function Init()' . "\r\n" . '{' . "\r\n" . '	document.getElementById(\'questionID\').focus();' . "\r\n" . '}' . "\r\n" . '</SCRIPT>' . "\r\n" . '<meta content="mshtml 6.00.3790.0" name=generator></head>' . "\r\n" . '<body oncontextmenu="return false" onload="javascript:Init();">' . "\r\n" . '  <table class=datatable style="line-height: 150%;border-collapse:collapse;" cellspacing=0 cellpadding=0 bordercolor=#cacaca border=1 width=100%>' . "\r\n" . '  <form name="QuestionForm" id="QuestionForm" method="post" action="" onsubmit="return Check_Form_Validator()" target="hidden_frame">' . "\r\n" . '	  <DIV class=position>位置：&nbsp; 我的问卷 &raquo; 编辑问题 &raquo; 插入前述问题的答案</DIV>' . "\r\n" . '      <tr><td colspan="2" bgcolor="#f9f9f9"><b>插入前述问题的答案</b></td></tr>' . "\r\n" . '      <TR><TD>插入前述问题的答案在题目文字当前鼠标光标位置：</TD></TR>' . "\r\n" . '	  <tr><td><select name="questionID" id="questionID" style="width:670px">' . "\r\n" . '	    <option value=\'\'>请选择前述问题...</option>' . "\r\n" . '';

if ($_GET['orderByID'] != '') {
	$SQL = ' SELECT questionID,questionName,questionType,isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE orderByID < \'' . $_GET['orderByID'] . '\' AND surveyID =\'' . $_GET['surveyID'] . '\' AND questionType IN (2,3,4,17,18,23,24,25) ORDER BY orderByID ASC ';
}
else {
	$SQL = ' SELECT questionID,questionName,questionType,isSelect,isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE surveyID =\'' . $_GET['surveyID'] . '\' AND questionType IN (2,3,4,17,18,23,24,25) ORDER BY orderByID ASC ';
}

$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);

	switch ($Row['questionType']) {
	case '2':
		echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';

		if ($Row['isHaveOther'] == 1) {
			echo '<option value=\'' . $Row['questionID'] . '_0\'>' . $questionName . ' - ' . qnohtmltag($Row['otherText'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '3':
		if ($Row['isHaveOther'] == 1) {
			echo '<option value=\'' . $Row['questionID'] . '_0\'>' . $questionName . ' - ' . qnohtmltag($Row['otherText'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '17':
		if ($Row['isSelect'] == 1) {
			echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '18':
		if ($Row['isSelect'] != 1) {
			echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '23':
		$oSQL = ' SELECT question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_yesnoID ASC ';
		$oResult = $DB->query($oSQL);

		while ($oRow = $DB->queryArray($oResult)) {
			echo '<option value=\'' . $Row['questionID'] . '_' . $oRow['question_yesnoID'] . '\'>' . $questionName . ' - ' . qnohtmltag($oRow['optionName'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '24':
		echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		$oSQL = ' SELECT question_radioID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' AND isHaveText =1 ORDER BY optionOptionID ASC ';
		$oResult = $DB->query($oSQL);

		while ($oRow = $DB->queryArray($oResult)) {
			echo '<option value=\'' . $Row['questionID'] . '_' . $oRow['question_radioID'] . '\'>' . $questionName . ' - ' . qnohtmltag($oRow['optionName'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	case '25':
		$oSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' AND isHaveText =1 ORDER BY optionOptionID ASC ';
		$oResult = $DB->query($oSQL);

		while ($oRow = $DB->queryArray($oResult)) {
			echo '<option value=\'' . $Row['questionID'] . '_' . $oRow['question_checkboxID'] . '\'>' . $questionName . ' - ' . qnohtmltag($oRow['optionName'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;

	default:
		echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		break;
	}
}

echo '		</select>' . "\r\n" . '	  </td></tr>' . "\r\n" . '   <table><table width=100%>' . "\r\n" . '     <TR><TD> ' . "\r\n" . '			<input class=inputsubmit type="submit" name="submit" id="submit" value="确定">' . "\r\n" . '			<input name="Action" id="Action" value="SelectSubmit" type=hidden>' . "\r\n" . '            <input class=inputsubmit type="button" name="close" value="关闭" onClick="javascript:parent.hidePopWin();">' . "\r\n" . '         </TD>' . "\r\n" . '     </TR>' . "\r\n" . '	 <iframe name=\'hidden_frame\' id="hidden_frame" style=\'display:none\'></iframe>' . "\r\n" . '  </form>' . "\r\n" . '</table>' . "\r\n" . '</body></html>' . "\r\n" . '';

?>
