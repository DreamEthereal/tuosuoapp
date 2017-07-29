<?php
//dezend by http://www.yunlu99.com/
function export($type, $E_SQL)
{
	
	global $DB;
	global $lang;
	$_obf__WwKzYz1wA__ = '';
	$_obf_UrXeBQ9svomn = ' SELECT administratorsoptionID,optionFieldName FROM ' . ADMINISTRATORSOPTION_TABLE . ' ORDER BY administratorsoptionID ASC ';
	$_obf_wYzGeBYGltIy8kCO = $DB->query($_obf_UrXeBQ9svomn);
	$_obf_YfrY8VEd = '"' . $lang['export_member_isActive'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_name'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_nickName'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_joinTime'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_group'] . '"';

	while ($_obf_d2_Ii7CmoqkA = $DB->queryArray($_obf_wYzGeBYGltIy8kCO)) {
		$_obf_YfrY8VEd .= ',"' . $_obf_d2_Ii7CmoqkA['optionFieldName'] . '"';
	}

	$_obf_YfrY8VEd .= ',"' . $lang['export_member_loginNum'] . '"';
	$_obf_YfrY8VEd .= ',"' . $lang['export_member_lastVisitTime'] . '"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;

	if ($E_SQL == '') {
		$_obf_xCnI = ' SELECT * FROM ' . ADMINISTRATORS_TABLE . ' WHERE isAdmin =0 ';
		$_obf_xCnI .= ' ORDER BY administratorsID DESC ';
	}
	else {
		$_obf_xCnI = $E_SQL;
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['isActive'] == 1) {
			$_obf__WwKzYz1wA__ .= '"' . $lang['active'] . '"';
		}
		else {
			$_obf__WwKzYz1wA__ .= '"' . $lang['stop'] . '"';
		}

		$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['administratorsName'] . '"';
		$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['nickName'] . '"';
		$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d', $_obf_9WwQ['createDate']) . '"';

		if ($_obf_9WwQ['administratorsGroupID'] == '0') {
			$_obf__WwKzYz1wA__ .= ',"' . $lang['no_group'] . '"';
		}
		else {
			$_obf_xCnI = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $_obf_9WwQ['administratorsGroupID'] . '\' LIMIT 0,1 ';
			$_obf_U1yvEFe_72Q_ = $DB->queryFirstRow($_obf_xCnI);
			$_obf__WwKzYz1wA__ .= ',"' . $_obf_U1yvEFe_72Q_['administratorsGroupName'] . '"';
		}

		$_obf_UrXeBQ9svomn = ' SELECT administratorsoptionID FROM ' . ADMINISTRATORSOPTION_TABLE . ' ORDER BY administratorsoptionID ASC ';
		$_obf_wYzGeBYGltIy8kCO = $DB->query($_obf_UrXeBQ9svomn);

		while ($_obf_d2_Ii7CmoqkA = $DB->queryArray($_obf_wYzGeBYGltIy8kCO)) {
			$_obf_R_8PIOcWIbU_ = ' SELECT value FROM ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' WHERE  administratorsID=\'' . $_obf_9WwQ['administratorsID'] . '\' AND administratorsoptionID = \'' . $_obf_d2_Ii7CmoqkA['administratorsoptionID'] . '\'  LIMIT 0,1 ';
			$_obf_JbjOqnGuJlU_ = $DB->queryFirstRow($_obf_R_8PIOcWIbU_);
			$_obf__WwKzYz1wA__ .= ',"' . qshowexportquotechar($_obf_JbjOqnGuJlU_['value']) . '"';
		}

		$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['loginNum'] . '"';
		$_obf__WwKzYz1wA__ .= ',"' . date('Y-m-d H:m', $_obf_9WwQ['lastVisitTime']) . '"';
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
require_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5|6');
ob_start();
$MembersList = export('Members', base64_decode($_SESSION['mSQL']));
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=Members_List_' . date('Y-m-d') . '.csv');
echo $MembersList;
exit();

?>
