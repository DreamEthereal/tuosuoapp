<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQuestionArray['optionName']) . '"';
	$this_fields_list .= '10#option_' . $questionID . '_' . $question_rankID . '|';
}

if ($theQtnArray['isHaveOther'] == '1') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQtnArray['otherText']) . '"';
	$this_fields_list .= '10#option_' . $questionID . '_0' . '|';
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . qshowexportquotechar($theQtnArray['otherText']) . ' - Text' . '"';
	$this_fields_list .= 'TextOtherValue_' . $questionID . '|';
}

if ($theQtnArray['isHaveWhy'] == '1') {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . ' - ' . $lang['why_your_order'] . '"';
	$this_fields_list .= 'TextWhyValue_' . $questionID . '|';
}

?>
