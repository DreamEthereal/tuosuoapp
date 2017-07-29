<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($theQtnArray['isHaveUnkown'] == 2) {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$this_fields_list .= '4#option_' . $questionID . '#isHaveUnkown_' . $questionID . '|';
}
else {
	$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
	$this_fields_list .= 'option_' . $questionID . '|';
}

?>
