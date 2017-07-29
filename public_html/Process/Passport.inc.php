<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$response_PassportSQL = '';

if ($_POST['isPublic'] == '0') {
	switch ($_POST['passPortType']) {
	case '1':
	default:
		$response_PassportSQL .= ' administratorsName = \'' . $_SESSION['userName'] . '\',';
		$response_PassportSQL .= ' administratorsGroupID = \'' . $_SESSION['userGroupID'] . '\',';
		$i = 0;

		for (; $i < $_SESSION['ajaxCount']; $i++) {
			$j = $i + 1;
			$response_PassportSQL .= ' ajaxRtnValue_' . $j . ' = \'' . $_SESSION['ajaxRtnValue_' . $j] . '\',';
		}

		break;

	case '2':
		$response_PassportSQL .= ' administratorsName = \'' . $_SESSION['userName'] . '\',';
		$i = 0;

		for (; $i < $_SESSION['ajaxCount']; $i++) {
			$j = $i + 1;
			$response_PassportSQL .= ' ajaxRtnValue_' . $j . ' = \'' . $_SESSION['ajaxRtnValue_' . $j] . '\',';
		}

		break;

	case '3':
		$response_PassportSQL .= ' administratorsName = \'' . $_SESSION['userName'] . '\',';
		break;

	case '4':
		$response_PassportSQL .= ' administratorsName = \'' . $_SESSION['userName'] . '\',';
		break;
	}

	switch ($_POST['passPortType']) {
	case '1':
	default:
		$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE LCASE(administratorsName) = \'' . strtolower($_SESSION['userName']) . '\' AND overFlag !=0 LIMIT 0,1 ';
		break;

	case '2':
	case '3':
	case '4':
		$HaveSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE administratorsName = \'' . $_SESSION['userName'] . '\' AND overFlag !=0 LIMIT 0,1 ';
		break;
	}

	$HaveRow = $DB->queryFirstRow($HaveSQL);

	if ($HaveRow) {
		_shownotes($lang['auth_error'], $lang['members_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
	}
}
else {
	$response_PassportSQL .= ' administratorsName = \'' . $_SESSION['userName'] . '\',';
}

?>
