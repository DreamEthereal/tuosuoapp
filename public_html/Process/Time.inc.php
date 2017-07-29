<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if (isset($_SESSION['sBeginTime_' . $S_Row['surveyID']]) && ($_SESSION['sBeginTime_' . $S_Row['surveyID']] != '')) {
	$time = $_SESSION['sBeginTime_' . $S_Row['surveyID']];
}
else {
	$time = time();
}

if (isset($_SESSION['overTime_' . $S_Row['surveyID']]) && ($_SESSION['overTime_' . $S_Row['surveyID']] != '')) {
	$over_time = time() - $_SESSION['overTime_' . $S_Row['surveyID']];
}
else {
	$over_time = 21600;
}

?>
