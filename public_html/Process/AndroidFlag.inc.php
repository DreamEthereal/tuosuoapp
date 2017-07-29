<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if ($_SESSION['paperFlag_' . $S_Row['surveyID']] == '') {
	if (trim($_POST['biaoshi']) != '') {
		$ipAddress = qhtmlspecialchars(clearstring(trim($_POST['biaoshi'])));
		$_SESSION['paperFlag_' . $S_Row['surveyID']] = $ipAddress;
	}
	else {
		$ipAddress = _getip();
		$_SESSION['paperFlag_' . $S_Row['surveyID']] = $ipAddress;
	}
}
else {
	$ipAddress = $_SESSION['paperFlag_' . $S_Row['surveyID']];
}

if ($_SESSION['paperName_' . $S_Row['surveyID']] == '') {
	$administrators_Name = qhtmlspecialchars(clearstring(trim($_POST['name'])));
	$_SESSION['paperName_' . $S_Row['surveyID']] = $administrators_Name;
}
else {
	$administrators_Name = $_SESSION['paperName_' . $S_Row['surveyID']];
}

$Area = $_SESSION['administratorsName'];

?>
