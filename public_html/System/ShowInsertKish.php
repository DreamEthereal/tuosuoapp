<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5');

if ($_POST['Action'] == 'SelectSubmit') {
	echo '<script>parent.AjaxCallBack(\'[Kish_' . $_POST['questionID'] . ']\');</script>';
	exit();
}

echo '<html>' . "\r\n" . '<head>' . "\r\n" . '<meta http-equiv=content-language content=zh-cn>' . "\r\n" . '<meta http-equiv=content-type content="text/html; charset=gbk">' . "\r\n" . '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">' . "\r\n" . '<LINK href="../CSS/Base.css" rel=stylesheet>' . "\r\n" . '<script type="text/javascript" src="../JS/Jquery.min.js.php"></script>' . "\r\n" . '<script type="text/javascript" src="../JS/Jquery.notification.js.php"></script>' . "\r\n" . '<link type="text/css" rel="stylesheet" href="../CSS/Notification.css" />' . "\r\n" . '<SCRIPT language=javascript src="../JS/CheckQuestion.js.php"></SCRIPT>' . "\r\n" . '<style>' . "\r\n" . ' td { padding:2px 5px 2px 5px;}' . "\r\n" . ' #jquery-notification-message {width:100%;}' . "\r\n" . '</style>' . "\r\n" . '<SCRIPT type=text/javascript>' . "\r\n" . 'function Check_Form_Validator(){' . "\r\n" . '	var ie = document.all && !window.opera;' . "\r\n" . '	if( ie )' . "\r\n" . '	{' . "\r\n" . '		if (!CheckNotNull(document.QuestionForm.questionID, "ǰ������")) {return false;}' . "\r\n" . '	}' . "\r\n" . '	else' . "\r\n" . '	{' . "\r\n" . '		$.notification("��ϵͳ�����ڷ�IE������������ܲ��������뷽��ѡ��ʹ��IE�������");' . "\r\n" . '		return false;' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function AjaxCallBack(htmlStr) {' . "\r\n" . '	parent.editTextRange.text = htmlStr ;' . "\r\n" . '	parent.hidePopWin();' . "\r\n" . '}' . "\r\n" . 'function Init()' . "\r\n" . '{' . "\r\n" . '	document.getElementById(\'questionID\').focus();' . "\r\n" . '}' . "\r\n" . '</SCRIPT>' . "\r\n" . '<meta content="mshtml 6.00.3790.0" name=generator></head>' . "\r\n" . '<body oncontextmenu="return false" onload="javascript:Init();">' . "\r\n" . '  <table class=datatable style="line-height: 150%;border-collapse:collapse;" cellspacing=0 cellpadding=0 bordercolor=#cacaca border=1 width=100%>' . "\r\n" . '  <form name="QuestionForm" id="QuestionForm" method="post" action="" onsubmit="return Check_Form_Validator()" target="hidden_frame">' . "\r\n" . '	  <DIV class=position>λ�ã�&nbsp; �ҵ��ʾ� &raquo; �༭���� &raquo; �������ǰ�������Kish�������</DIV>' . "\r\n" . '      <tr><td colspan="2" bgcolor="#f9f9f9"><b>�������ǰ�������Kish�������</b></td></tr>' . "\r\n" . '      <TR><TD>����Ŀ���ֵ�ǰ�����λ�ò������ǰ�������Kish���������</TD></TR>' . "\r\n" . '	  <tr><td><select name="questionID" id="questionID" style="width:670px">' . "\r\n" . '	    <option value=\'\'>��ѡ��ǰ������...</option>' . "\r\n" . '';

if ($_GET['orderByID'] != '') {
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE orderByID < \'' . $_GET['orderByID'] . '\' AND surveyID =\'' . $_GET['surveyID'] . '\' AND questionType IN (23,27) ORDER BY orderByID ASC ';
}
else {
	$SQL = ' SELECT questionID,questionName,questionType FROM ' . QUESTION_TABLE . ' WHERE surveyID =\'' . $_GET['surveyID'] . '\' AND questionType IN (23,27) ORDER BY orderByID ASC ';
}

$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$questionName = qnohtmltag($Row['questionName'], 1);

	switch ($Row['questionType']) {
	case '23':
		echo '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		break;

	case '27':
		$lSQL = ' SELECT question_range_labelID,optionLabel FROM ' . QUESTION_RANGE_LABEL_TABLE . ' WHERE questionID =\'' . $Row['questionID'] . '\' ORDER BY question_range_labelID ASC ';
		$lResult = $DB->query($lSQL);

		while ($lRow = $DB->queryArray($lResult)) {
			echo '<option value=\'' . $Row['questionID'] . '_' . $lRow['question_range_labelID'] . '\'>' . $questionName . ' - ' . qnohtmltag($lRow['optionLabel'], 1) . '(' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
		}

		break;
	}
}

echo '		</select>' . "\r\n" . '	  </td></tr>' . "\r\n" . '   <table><table width=100%>' . "\r\n" . '     <TR><TD> ' . "\r\n" . '			<input class=inputsubmit type="submit" name="submit" id="submit" value="ȷ��">' . "\r\n" . '			<input name="Action" id="Action" value="SelectSubmit" type=hidden>' . "\r\n" . '            <input class=inputsubmit type="button" name="close" value="�ر�" onClick="javascript:parent.hidePopWin();">' . "\r\n" . '         </TD>' . "\r\n" . '     </TR>' . "\r\n" . '	 <iframe name=\'hidden_frame\' id="hidden_frame" style=\'display:none\'></iframe>' . "\r\n" . '  </form>' . "\r\n" . '</table>' . "\r\n" . '</body></html>' . "\r\n" . '';

?>
