<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
header('Content-Type:text/html; charset=gbk');
_checkroletype('1|2|5');
$optionList2 = '<br/> <span class=red>*</span> 按住Ctrl键点击鼠标进行多重选择';
$optionList1 = '<select name="opertion" id="opertion" size=8><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';

if ($_GET['selectedID'] == '') {
	$optionList = $optionList1 . '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option></select>' . $optionList2;
	echo $optionList;
	exit();
}

$_GET['selectedID'] = (int) $_GET['selectedID'];
$SQL = ' SELECT questionType,orderByID,isHaveOther,otherText,isNeg,allowType,baseID,isCheckType,isSelect,unitText,maxSize FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$logicValueIsAnd = 0;

switch ($Row['questionType']) {
case '1':
	$YesNoSQL = ' SELECT questionID,question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_yesnoID ASC ';
	$YesNoResult = $DB->query($YesNoSQL);
	$optionList = $optionList1 . '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($YesNoRow = $DB->queryArray($YesNoResult)) {
		$optionList .= '<option value=\'' . $YesNoRow['question_yesnoID'] . '\'>' . $YesNoRow['optionName'] . '</option>';
	}

	$optionList .= '</select>' . $optionList2;
	break;

case '2':
	$RadioSQL = ' SELECT questionID,question_radioID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$RadioResult = $DB->query($RadioSQL);
	$optionList = '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	$optionList .= '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RadioRow = $DB->queryArray($RadioResult)) {
		$optionName = qnohtmltag($RadioRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RadioRow['question_radioID'] . '\'>' . $optionName . '</option>';
	}

	if ($Row['isHaveOther'] == '1') {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($Row['otherText'], 1) . '</option>';
	}

	$optionList .= '</select>' . $optionList2;
	break;

case '3':
	$optionList = '<input type=radio name=\'logicMode\' id=\'logicMode\' value=\'1\' style=\'vertical-align: middle;\' checked onclick=\'javascript:changeLogicMode(1);\'><b>选项模式</b>(按住Ctrl键点击鼠标进行多重选择)';
	$optionList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="opertion_1" id="opertion_1" size=6><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	$optionList .= '<select name="optionID_1[]" id="optionID_1" size=6 multiple><option value="">' . $lang['pls_select'] . '</option>';
	$CheckBoxSQL = ' SELECT questionID,question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$CheckBoxResult = $DB->query($CheckBoxSQL);

	while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
		$optionName = qnohtmltag($CheckBoxRow['optionName'], 1);
		$optionList .= '<option value=\'' . $CheckBoxRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	if ($Row['isHaveOther'] == '1') {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($Row['otherText'], 1) . '</option>';
	}

	if ($Row['isNeg'] == '1') {
		if ($Row['allowType'] == '') {
			$optionList .= '<option value=\'99999\'>' . $lang['neg_text'] . '</option>';
		}
		else {
			$optionList .= '<option value=\'99999\'>' . qnohtmltag($Row['allowType'], 1) . '</option>';
		}
	}

	$optionList .= '</select>';
	$optionList .= '<br/><input type=radio name=\'logicMode\' id=\'logicMode\' value=\'2\' style=\'vertical-align: middle;\' onclick=\'javascript:changeLogicMode(2);\'><b>计算模式</b>(回复选项的数量)';
	$optionList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="opertion_2" id="opertion_2" style="vertical-align: middle;"><option value="">请选择...</option><option value=1>等于 =</option><option value=2>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5 selected>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= '<input style=\'vertical-align:middle\' name=\'optionID_2\' id=\'optionID_2\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">';
	$logicValueIsAnd = 1;
	break;

case '4':
	$optionList = '<select name="opertion" id="opertion" size=9><option value="">请选择...</option><option value=1>等于 =</option><option value=2 selected>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= '<input style=\'vertical-align:top\' name=\'optionID[]\' id=\'optionID\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">';
	break;

case '23':
	$RangeSQL = ' SELECT question_yesnoID,optionName FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' AND isCheckType =\'4\' ORDER BY optionOptionID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_yesnoID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$optionList1 = '<select name="opertion" id="opertion" size=8><option value="">请选择...</option><option value=1>等于 =</option><option value=2 selected>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= $optionList1 . '<input style=\'vertical-align:top\' name=\'optionID[]\' id=\'optionID\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">' . $optionList2;
	break;

case '10':
	$RangeSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_rankID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_rankID'] . '\'>' . $optionName . '</option>';
	}

	if ($Row['isHaveOther'] == '1') {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($Row['otherText'], 1) . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$optionList1 = '<select name="opertion" id="opertion" size=8><option value="">请选择...</option><option value=1>等于 =</option><option value=2 selected>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= $optionList1 . '<input style=\'vertical-align:top\' name=\'optionID[]\' id=\'optionID\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">' . $optionList2;
	break;

case '15':
case '16':
	$RangeSQL = ' SELECT question_rankID,optionName FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_rankID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_rankID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$optionList1 = '<select name="opertion" id="opertion" size=8><option value="">请选择...</option><option value=1>等于 =</option><option value=2 selected>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= $optionList1 . '<input style=\'vertical-align:top\' name=\'optionID[]\' id=\'optionID\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">' . $optionList2;
	break;

case '20':
case '21':
case '22':
	$bSQL = ' SELECT isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);
	$RangeSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	if ($bRow['isHaveOther'] == 1) {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($bRow['otherText'], 1) . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$optionList1 = '<select name="opertion" id="opertion" size=8><option value="">请选择...</option><option value=1>等于 =</option><option value=2 selected>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= $optionList1 . '<input style=\'vertical-align:top\' name=\'optionID[]\' id=\'optionID\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">' . $optionList2;
	break;

case '6':
case '7':
	$RangeSQL = ' SELECT question_range_optionID,optionName FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_range_optionID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_range_optionID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_range_answerID ASC ';
	$AnswerResult = $DB->query($AnswerSQL);

	if ($Row['questionType'] == '6') {
		$optionList .= '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	}
	else {
		$optionList .= $optionList1;
	}

	$optionList .= '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($AnswerRow = $DB->queryArray($AnswerResult)) {
		$optionAnswer = qnohtmltag($AnswerRow['optionAnswer'], 1);
		$optionList .= '<option value=\'' . $AnswerRow['question_range_answerID'] . '\'>' . $optionAnswer . '</option>';
	}

	$optionList .= '</select>' . $optionList2;

	if ($Row['questionType'] == '7') {
		$logicValueIsAnd = 1;
	}

	break;

case '31':
	$optionList = '<select name="qtnID" id="qtnID" size=8 align=absmiddle><option value="">' . $lang['pls_select'] . '</option>';
	$theUnitText = explode('#', $Row['unitText']);
	$i = 1;

	for (; $i <= $Row['maxSize']; $i++) {
		$tmp = $i - 1;
		$optionName = qnohtmltag($theUnitText[$tmp], 1);
		$optionList .= '<option value=\'' . $i . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$optionList .= '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	$optionList .= '<input style=\'vertical-align:top\' name=\'nodeValue\' id=\'nodeValue\' size=25>';
	$optionList .= '<br/> <span class=red>*</span> 回复值集合,若多值采用英文逗号分割';
	break;

case '19':
case '28':
	$bSQL = ' SELECT isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);
	$RangeSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
	$RangeResult = $DB->query($RangeSQL);
	$optionList = '<select name="qtnID[]" id="qtnID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	if ($bRow['isHaveOther'] == 1) {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($bRow['otherText'], 1) . '</option>';
	}

	$optionList .= '</select>&nbsp;';
	$AnswerSQL = ' SELECT question_range_answerID,optionAnswer FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY question_range_answerID ASC ';
	$AnswerResult = $DB->query($AnswerSQL);

	if ($Row['questionType'] == '19') {
		$optionList .= '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	}
	else {
		$optionList .= $optionList1;
	}

	$optionList .= '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($AnswerRow = $DB->queryArray($AnswerResult)) {
		$optionAnswer = qnohtmltag($AnswerRow['optionAnswer'], 1);
		$optionList .= '<option value=\'' . $AnswerRow['question_range_answerID'] . '\'>' . $optionAnswer . '</option>';
	}

	$optionList .= '</select>' . $optionList2;

	if ($Row['questionType'] == '28') {
		$logicValueIsAnd = 1;
	}

	break;

case '24':
	$RadioSQL = ' SELECT questionID,question_radioID,optionName FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$RadioResult = $DB->query($RadioSQL);
	$optionList = '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	$optionList .= '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RadioRow = $DB->queryArray($RadioResult)) {
		$optionName = qnohtmltag($RadioRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RadioRow['question_radioID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>' . $optionList2;
	break;

case '25':
	$optionList = '<input type=radio name=\'logicMode\' id=\'logicMode\' value=\'1\' style=\'vertical-align: middle;\' checked onclick=\'javascript:changeLogicMode(1);\'><b>选项模式</b>(按住Ctrl键点击鼠标进行多重选择)';
	$optionList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="opertion_1" id="opertion_1" size=6><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	$optionList .= '<select name="optionID_1[]" id="optionID_1" size=6 multiple><option value="">' . $lang['pls_select'] . '</option>';
	$CheckBoxSQL = ' SELECT questionID,question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['selectedID'] . '\' ORDER BY optionOptionID ASC ';
	$CheckBoxResult = $DB->query($CheckBoxSQL);

	while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
		$optionName = qnohtmltag($CheckBoxRow['optionName'], 1);
		$optionList .= '<option value=\'' . $CheckBoxRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	$optionList .= '</select>';
	$optionList .= '<br/><input type=radio name=\'logicMode\' id=\'logicMode\' value=\'2\' style=\'vertical-align: middle;\' onclick=\'javascript:changeLogicMode(2);\'><b>计算模式</b>(回复选项的数量)';
	$optionList .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="opertion_2" id="opertion_2" style="vertical-align: middle;"><option value="">请选择...</option><option value=1>等于 =</option><option value=2>小于 <</option><option value=3>小于等于 <=</option><option value=4>大于 ></option><option value=5 selected>大于等于 >=</option><option value=6>不等于 !=</option></select>&nbsp;';
	$optionList .= '<input style=\'vertical-align:middle\' name=\'optionID_2\' id=\'optionID_2\' size=12 onKeyUp="this.value=this.value.replace(/\\D/g,\'\')" onafterpaste="this.value=this.value.replace(/\\D/g,\'\')">';
	$logicValueIsAnd = 1;
	break;

case '30':
	if (!isset($_GET['isQuota'])) {
		$optionList1 = '<select name="opertion" id="opertion" size=9><option value="">请选择...</option><option value="1" selected>等于</option><option value="2">不等于</option></select>&nbsp;';
	}
	else {
		$optionList1 = '<select name="opertion" id="opertion" size=9><option value="">请选择...</option><option value="1" selected>等于</option><option value="2">不等于</option></select>&nbsp;';
	}

	$optionList = $optionList1 . '<select name="optionID" id="optionID" size=9><option value="">' . $lang['pls_select'] . '</option>';
	$optionList .= '<option value=\'1\'>True</option>';
	$optionList .= '<option value=\'2\'>False</option>';
	$optionList .= '</select>';
	break;

case '17':
	$bSQL = ' SELECT isHaveOther,otherText FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['baseID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);
	$RangeSQL = ' SELECT question_checkboxID,optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $Row['baseID'] . '\' ORDER BY optionOptionID ASC ';
	$RangeResult = $DB->query($RangeSQL);

	if ($Row['isSelect'] != '0') {
		$optionList = '<select name="opertion" id="opertion" size=8 onchange=javascript:disLogicMode("opertion","logicValueIsAnd");><option value="">请选择...</option><option value="1" selected>选择</option><option value="2">未选择</option></select>&nbsp;';
	}
	else {
		$optionList = $optionList1;
	}

	$optionList .= '<select name="optionID[]" id="optionID" size=8 multiple><option value="">' . $lang['pls_select'] . '</option>';

	while ($RangeRow = $DB->queryArray($RangeResult)) {
		$optionName = qnohtmltag($RangeRow['optionName'], 1);
		$optionList .= '<option value=\'' . $RangeRow['question_checkboxID'] . '\'>' . $optionName . '</option>';
	}

	if ($bRow['isHaveOther'] == 1) {
		$optionList .= '<option value=\'0\'>' . qnohtmltag($bRow['otherText'], 1) . '</option>';
	}

	if ($Row['isCheckType'] == '1') {
		if ($Row['allowType'] == '') {
			$optionList .= '<option value=\'99999\'>' . $lang['neg_text'] . '</option>';
		}
		else {
			$optionList .= '<option value=\'99999\'>' . qnohtmltag($Row['allowType'], 1) . '</option>';
		}
	}

	$optionList .= '</select>' . $optionList2;

	if ($Row['isSelect'] == '0') {
		$logicValueIsAnd = 1;
	}

	break;
}

if ($logicValueIsAnd == 1) {
	echo $optionList . '######1';
}
else {
	echo $optionList;
}

?>
