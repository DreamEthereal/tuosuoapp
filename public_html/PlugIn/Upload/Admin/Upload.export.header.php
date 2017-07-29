<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$header .= ',"' . qshowexportquotechar($theQtnArray['questionName']) . '"';
$this_fields_list .= '11#option_' . $questionID . '|';

?>
