<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$response_PassportSQL = '';
if (!isset($_SESSION['adminRoleType']) || ($_SESSION['adminRoleType'] == '4')) {
	if (0 < $_POST['thisStep']) {
		$ipAddress = $R_Row['ipAddress'];
	}
	else {
		$ipAddress = qhtmlspecialchars(clearstring(trim($_POST['biaoshi'])));
	}

	$response_PassportSQL .= ' ipAddress =\'' . $ipAddress . '\',';

	if (0 < $_POST['thisStep']) {
		$administrators_Name = $R_Row['administratorsName'];
	}
	else {
		$administrators_Name = qhtmlspecialchars(clearstring(trim($_POST['name'])));
	}

	$response_PassportSQL .= ' administratorsName = \'' . $administrators_Name . '\',';

	if ($S_Row['projectType'] != 1) {
		$SQL = ' SELECT ipAddress FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE LCASE(ipAddress)=\'' . strtolower($ipAddress) . '\' AND responseID != ' . $_POST['responseID'] . ' AND overFlag !=0 LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			_showerror($lang['system_error'], $lang['exist_paper_survey'] . ':' . $ipAddress);
		}
	}
	else {
		$SQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE LCASE(administratorsName)=\'' . strtolower($administrators_Name) . '\' AND responseID != ' . $_POST['responseID'] . ' AND overFlag !=0 LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			_showerror($lang['system_error'], $lang['exist_paper_survey'] . ':' . $administrators_Name);
		}
	}

	$Area = $_SESSION['administratorsName'];
	$response_PassportSQL .= ' area =\'' . $Area . '\',';
}

if (($administrators_Name != '') && ($_POST['thisStep'] == 0) && ($S_Row['isPublic'] == '0')) {
	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$theUserName = strtolower($administrators_Name);
		$RepSQL = ' SELECT administratorsName,administratorsID,passWord,isActive,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE  LCASE(administratorsName) =\'' . $theUserName . '\' AND isAdmin =0 LIMIT 0,1 ';
		$RepRow = $DB->queryFirstRow($RepSQL);
		$administratorsGroupID = $RepRow['administratorsGroupID'];
		$response_PassportSQL .= ' administratorsGroupID = \'' . $administratorsGroupID . '\',';

		if ($RepRow) {
			$mainAttribute = explode(',', $S_Row['mainAttribute']);
			$theMainAttributeList = '';
			$ajaxRtnValueCate = array();
			$temp = 0;

			foreach ($mainAttribute as $theMainAttribute) {
				$SQL = ' SELECT optionFieldName,administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE administratorsoptionID =\'' . $theMainAttribute . '\' ';
				$OptionRow = $DB->queryFirstRow($SQL);

				if ($OptionRow['optionFieldName'] != '') {
					$SQL = ' SELECT value FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsoptionID =\'' . $OptionRow['administratorsoptionID'] . '\' AND administratorsID = \'' . $RepRow['administratorsID'] . '\' ';
					$ValueRow = $DB->queryFirstRow($SQL);
					$ajaxRtnValueCate[] = $OptionRow['optionFieldName'];
					$temp++;
					$response_PassportSQL .= ' ajaxRtnValue_' . $temp . ' = \'' . $ValueRow['value'] . '\',';
				}
			}

			if ($S_Row['ajaxRtnValue'] == '') {
				$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
				$DB->query($SQL);
			}

			unset($ajaxRtnValueCate);
		}

		break;

	case '2':
	case '3':
	case '4':
	case '5':
		break;
	}
}

?>
