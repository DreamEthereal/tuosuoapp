<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$Hidden_SQL = '';

if (qhtmlspecialchars($_POST['allHidden']) != '') {
	$thisPageHiddenList = substr(qhtmlspecialchars($_POST['allHidden']), 0, -1);
	$thisPageHidden = explode('|', $thisPageHiddenList);
	$j = 0;

	for (; $j < count($thisPageHidden); $j++) {
		$question_Hidden_ID = explode('_', $thisPageHidden[$j]);
		$the_hidden_qtn_id = $question_Hidden_ID[1];

		switch ($QtnListArray[$the_hidden_qtn_id]['hiddenFromSession']) {
		case '1':
			$the_var_value = qhtmlspecialchars($_SESSION[$QtnListArray[$the_hidden_qtn_id]['hiddenVarName']]);
			$Hidden_SQL .= ' ' . $thisPageHidden[$j] . ' = \'' . $the_var_value . '\',';
			break;

		case '2':
			$the_var_value = qhtmlspecialchars($_COOKIE[$QtnListArray[$the_hidden_qtn_id]['hiddenVarName']]);
			$Hidden_SQL .= ' ' . $thisPageHidden[$j] . ' = \'' . $the_var_value . '\',';
			break;

		case '3':
			$the_var_value = qhtmlspecialchars($_POST[$QtnListArray[$the_hidden_qtn_id]['hiddenVarName']]);
			$Hidden_SQL .= ' ' . $thisPageHidden[$j] . ' = \'' . $the_var_value . '\',';
			break;

		case '4':
			$the_var_value = qhtmlspecialchars($_GET[$QtnListArray[$the_hidden_qtn_id]['hiddenVarName']]);
			$Hidden_SQL .= ' ' . $thisPageHidden[$j] . ' = \'' . $the_var_value . '\',';
			break;

		case '5':
		default:
			$Hidden_SQL .= ' ' . $thisPageHidden[$j] . ' = \'' . $_SESSION['referer_' . $S_Row['surveyID']] . '\',';
			break;
		}

		switch ($QtnListArray[$the_hidden_qtn_id]['hiddenFromSession']) {
		case '1':
		case '2':
		case '3':
		case '4':
			if (($QtnListArray[$the_hidden_qtn_id]['isRequired'] == 1) && (trim($the_var_value) == '')) {
				_shownotes($lang['status_error'], $lang['hidden_null_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
			}

			if ($QtnListArray[$the_hidden_qtn_id]['isNeg'] == 1) {
				$hSQL = ' SELECT administratorsName FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE ' . $thisPageHidden[$j] . ' = \'' . $the_var_value . '\' AND overFlag !=0 LIMIT 0,1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if ($hRow) {
					_shownotes($lang['status_error'], $lang['hidden_permit_survey'], $lang['survey_gname'] . $S_Row['surveyTitle']);
				}
			}

			break;
		}
	}
}

?>
