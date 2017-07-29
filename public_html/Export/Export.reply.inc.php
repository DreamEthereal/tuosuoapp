<?php
//dezend by http://www.yunlu99.com/
function export($type, $E_SQL)
{
	global $DB;
	global $lang;
	global $table_prefix;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd .= '"' . $lang['export_member_name'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_nickName'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_group'] . '"';
	$_obf_xCnI = ' SELECT surveyID,surveyTitle,surveyName FROM ' . SURVEY_TABLE . ' WHERE status IN (1,2) ORDER BY surveyID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_5YhVRT6Qjxc6WFOSqQ__ = array();

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_YfrY8VEd .= ',"' . $_obf_9WwQ['surveyTitle'] . '(' . $_obf_9WwQ['surveyName'] . ')' . '"';
		$_obf_5YhVRT6Qjxc6WFOSqQ__[] = $_obf_9WwQ['surveyID'];
	}

	$_obf_YfrY8VEd .= ',"' . $lang['result_have_all'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['result_no_all'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['result_to_quota'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['result_in_export'] . '"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;

	if ($E_SQL == '') {
		$_obf_xCnI = ' SELECT administratorsName,nickName,administratorsGroupID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 ';
		$_obf_xCnI .= ' ORDER BY administratorsID DESC ';
	}
	else {
		$_obf_xCnI = $E_SQL;
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf__WwKzYz1wA__ .= '"' . $_obf_9WwQ['administratorsName'] . '"';
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['nickName'] . '"';

		if ($_obf_9WwQ['administratorsGroupID'] == '0') {
			$_obf__WwKzYz1wA__ .= ',"' . $lang['no_group'] . '"';
		}
		else {
			$_obf_xCnI = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $_obf_9WwQ['administratorsGroupID'] . '\' LIMIT 0,1 ';
			$_obf_U1yvEFe_72Q_ = $DB->queryFirstRow($_obf_xCnI);
			$_obf__WwKzYz1wA__ .= ',"' . $_obf_U1yvEFe_72Q_['administratorsGroupName'] . '"';
		}

		$_obf_js2TxhxfDhRUcJCB = $_obf_t3oTCr5mdoBSCiaL = $_obf_dBmMiRt0YNqQuIbn = $_obf_rhmQ_r4z5GTJRjzO = 0;

		foreach ($_obf_5YhVRT6Qjxc6WFOSqQ__ as $_obf_cGLQvxNxOi0_) {
			$_obf_dQ5UL7C73g__ = ' SELECT joinTime,submitTime,overFlag FROM ' . $table_prefix . 'response_' . $_obf_cGLQvxNxOi0_ . ' WHERE LCASE(administratorsName) = \'' . strtolower(trim($_obf_9WwQ['administratorsName'])) . '\' LIMIT 1 ';
			$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_dQ5UL7C73g__);

			if ($_obf_vI9iIiW3Qg__) {
				$_obf_8zj7ej4Q3_fe_A__ = ($_obf_vI9iIiW3Qg__['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $_obf_vI9iIiW3Qg__['submitTime']));
				$_obf_RakCwzILwuY_ = date('Y-m-d H:i:s', $_obf_vI9iIiW3Qg__['joinTime']) . ' - ' . $_obf_8zj7ej4Q3_fe_A__;

				switch ($_obf_vI9iIiW3Qg__['overFlag']) {
				case '0':
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_RakCwzILwuY_ . ',' . $lang['result_no_all'] . '"';
					$_obf_js2TxhxfDhRUcJCB++;
					break;

				case '1':
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_RakCwzILwuY_ . ',' . $lang['result_have_all'] . '"';
					$_obf_t3oTCr5mdoBSCiaL++;
					break;

				case '2':
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_RakCwzILwuY_ . ',' . $lang['result_to_quota'] . '"';
					$_obf_dBmMiRt0YNqQuIbn++;
					break;

				case '3':
					$_obf__WwKzYz1wA__ .= ',"' . $_obf_RakCwzILwuY_ . ',' . $lang['result_in_export'] . '"';
					$_obf_rhmQ_r4z5GTJRjzO++;
					break;
				}
			}
			else {
				$_obf__WwKzYz1wA__ .= ',""';
			}
		}

		$_obf__WwKzYz1wA__ .= ',"' . $_obf_t3oTCr5mdoBSCiaL . '"';
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_js2TxhxfDhRUcJCB . '"';
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_dBmMiRt0YNqQuIbn . '"';
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_rhmQ_r4z5GTJRjzO . '"';
		$_obf__WwKzYz1wA__ .= "\r\n";
	}

	return $_obf__WwKzYz1wA__;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
require_once ROOT_PATH . 'Functions/Functions.fields.inc.php';
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5|6');
ob_start();
$MembersList = export('Members', base64_decode($_SESSION['mSQL']));
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=MembersRespondedList_' . date('Y-m-d') . '.csv');
echo $MembersList;
exit();

?>
