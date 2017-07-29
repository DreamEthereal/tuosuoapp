<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$response_PassportSQL = '';

if (0 < $_POST['thisStep']) {
	$ipAddress = $R_Row['ipAddress'];
}
else if (trim($_POST['biaoshi']) != '') {
	$ipAddress = qhtmlspecialchars(clearstring(trim($_POST['biaoshi'])));
}
else {
	$ipAddress = $R_Row['ipAddress'];
}

$response_PassportSQL .= ' ipAddress =\'' . $ipAddress . '\',';

if (0 < $_POST['thisStep']) {
	$administrators_Name = $R_Row['administratorsName'];
}
else if (trim($_POST['name']) != '') {
	$administrators_Name = qhtmlspecialchars(clearstring(trim($_POST['name'])));
}
else {
	$administrators_Name = $R_Row['administratorsName'];
}

$response_PassportSQL .= ' administratorsName = \'' . $administrators_Name . '\',';
$Area = $_SESSION['administratorsName'];
$response_PassportSQL .= ' area =\'' . $Area . '\',';

?>
