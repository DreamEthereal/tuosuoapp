<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$option_order_array = array();

foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
	$option_order_array[$question_yesnoID] = $theQuestionArray['optionName'];
}

$option_tran_array[$questionID] = $option_order_array;
$this_fields_list .= '1#' . $questionID . '#option_' . $questionID . '|';

?>
