<?php
//dezend by http://www.yunlu99.com/
echo "\r\n";
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
_checkroletype('1|2|5');
$_GET['surveyID'] = (int) $_GET['surveyID'];
$exposure_var_list = '';
$SQL = ' SELECT tagID,tagName FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND tagCate =2 ORDER BY tagID DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$exposure_var_list .= '<option value=\'' . $Row['tagID'] . '\'>“' . qnohtmltag($Row['tagName'], 1) . '”创意曝光量</option>';
}

echo '' . "\r\n" . 'function insertIssueRow(tbIndex)' . "\r\n" . '{' . "\r\n" . '	 var theTd1Text = "&nbsp;" + tbIndex + "：";' . "\r\n" . '	 var theTd2Text = "<select name=\'exposureVar["+tbIndex+"]\' id=\'exposureVar_"+tbIndex+"\' onchange=\'checkIsUserDefine(document.Check_Form.exposureVar_"+tbIndex+".options[document.Check_Form.exposureVar_"+tbIndex+".selectedIndex].value,"+tbIndex+");\'><option value=\'\'>请选择...</option><option value=\'0\'>整个控制组曝光量</option><option value=\'userdefine\'>自定义Cookie变量</option>';
echo $exposure_var_list;
echo '</select>";' . "\r\n" . '	 var theTd5Text = "<input size=12 name=\'cookieVarName["+tbIndex+"]\' id=\'cookieVarName_"+tbIndex+"\'>";' . "\r\n" . '	 var theTd3Text = ">=";' . "\r\n" . '	 var theTd4Text = "<input type=\'text\' name=\'ruleValue["+tbIndex+"]\' id=\'ruleValue_"+tbIndex+"\' size=8>";' . "\r\n" . '	 var objRow = document.getElementById("theOptionTable").insertRow(tbIndex);' . "\r\n" . '	 var objCel = objRow.insertCell(0);' . "\r\n" . '	 objCel.innerHTML =theTd1Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(1);' . "\r\n" . '	 objCel.innerHTML = theTd2Text;' . "\r\n" . '	 var objCel = objRow.insertCell(2);' . "\r\n" . '	 objCel.innerHTML = theTd5Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(3);' . "\r\n" . '	 objCel.innerHTML = theTd3Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '	 var objCel = objRow.insertCell(4);' . "\r\n" . '	 objCel.innerHTML = theTd4Text;' . "\r\n" . '	 objCel.align = "center";' . "\r\n" . '}' . "\r\n" . 'function deleteIssueRow()' . "\r\n" . '{' . "\r\n" . '	 var theObj = document.getElementById("theOptionTable");' . "\r\n" . '	 U = theObj.rows.length-1;' . "\r\n" . '	 if( U > 1 ) {' . "\r\n" . '		theObj.deleteRow(U);' . "\r\n" . '	 }' . "\r\n" . '	 else {' . "\r\n" . '		$.notification("曝光量控制规则设置不能全部删除") ;' . "\r\n" . '	 }' . "\r\n" . '}';

?>
