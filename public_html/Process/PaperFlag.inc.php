<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

if (($_SESSION['paperFlag_' . $S_Row['surveyID']] == '') || (trim($_POST['biaoshi']) != '')) {
	$ipAddress = qhtmlspecialchars(clearstring(trim($_POST['biaoshi'])));
	$_SESSION['paperFlag_' . $S_Row['surveyID']] = $ipAddress;
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

if ($S_Row['projectType'] != 1) {
	$SQL = ' SELECT ipAddress FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE LCASE(ipAddress)=\'' . strtolower($ipAddress) . '\' AND overFlag !=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		_showerror($lang['system_error'], $lang['exist_paper_survey'] . ':' . $ipAddress);
	}
}

if (($administrators_Name != '') && ($S_Row['projectType'] != 1)) {
	$ajaxCount = 0;

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$theUserName = strtolower($administrators_Name);
		$RepSQL = ' SELECT administratorsName,administratorsID,passWord,isActive,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE LCASE(administratorsName) =\'' . $theUserName . '\' AND isAdmin =0 LIMIT 0,1 ';
		$RepRow = $DB->queryFirstRow($RepSQL);

		if ($_SESSION['userGroupID'] == '') {
			$administratorsGroupID = $RepRow['administratorsGroupID'];
			$_SESSION['userGroupID'] = $administratorsGroupID;
		}
		else {
			$administratorsGroupID = $_SESSION['userGroupID'];
		}

		if (($_SESSION['ajaxCount'] == '') && $RepRow) {
			$mainAttribute = explode(',', $S_Row['mainAttribute']);
			$theMainAttributeList = '';

			foreach ($mainAttribute as $theMainAttribute) {
				$SQL = ' SELECT optionFieldName,administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE administratorsoptionID =\'' . $theMainAttribute . '\' ';
				$OptionRow = $DB->queryFirstRow($SQL);

				if ($OptionRow['optionFieldName'] != '') {
					$SQL = ' SELECT value FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE administratorsoptionID =\'' . $OptionRow['administratorsoptionID'] . '\' AND administratorsID = \'' . $RepRow['administratorsID'] . '\' ';
					$ValueRow = $DB->queryFirstRow($SQL);
					$theMainAttributeList .= $OptionRow['optionFieldName'] . '=' . $ValueRow['value'] . '#';
				}
			}

			$theMainAttributeList = substr($theMainAttributeList, 0, -1);
			$ajaxRtnValue = explode('#', $theMainAttributeList);

			if (6 < count($ajaxRtnValue)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValue);
			}

			$_SESSION['ajaxCount'] = $ajaxCount;
			$ajaxRtnValueCate = array();
			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$j = $i + 1;
				$ajaxRtnValueSign = explode('=', $ajaxRtnValue[$i]);
				$ajaxRtnValueCate[] = $ajaxRtnValueSign[0];
				$_SESSION['ajaxRtnValue_' . $j] = $ajaxRtnValueSign[1];
			}

			if ($S_Row['ajaxRtnValue'] == '') {
				$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET ajaxRtnValue=\'' . implode(',', $ajaxRtnValueCate) . '\' WHERE surveyID=\'' . $S_Row['surveyID'] . '\' ';
				$DB->query($SQL);
			}
		}

		break;

	case '2':
	case '3':
	case '4':
	case '5':
		break;
	}
}

$Area = $_SESSION['administratorsName'];

?>
