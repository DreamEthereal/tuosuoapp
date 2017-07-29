<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';

switch ($_SESSION['adminRoleType']) {
case '1':
case '2':
case '5':
	header('Location:ShowSurveyList.php');
	break;

case '3':
	switch ($_SESSION['adminRoleGroupType']) {
	case '1':
	default:
		header('Location:ShowViewSurvey.php');
		break;

	case '2':
		header('Location:ShowCustSurvey.php');
		break;
	}

	break;

case '4':
	header('Location:ShowInputSurvey.php');
	break;

case '6':
	header('Location:ShowUserSurvey.php');
	break;

case '7':
	header('Location:ShowAuthSurvey.php');
	break;
}

exit();

?>
