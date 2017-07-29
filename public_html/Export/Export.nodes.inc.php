<?php
//dezend by http://www.yunlu99.com/
function export_nodes()
{
	global $DB;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '"任务名称"';
	$_obf_YfrY8VEd .= ',"任务代号"';
	$_obf_YfrY8VEd .= ',"执行人"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;
	$_obf_9NV_lJS2xgHk = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $_GET['projectOwner'] . '-%\' OR userGroupID = \'' . $_GET['projectOwner'] . '\') ';
	$_obf_xCnI = ' SELECT * FROM ' . USERGROUP_TABLE . ' WHERE groupType = 2 AND ' . $_obf_9NV_lJS2xgHk . ' AND isLeaf=1 ORDER BY absPath ASC,userGroupID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf__WwKzYz1wA__ .= '"' . trim($_obf_9WwQ['userGroupName']) . '"';
		$_obf__WwKzYz1wA__ .= ',"' . trim($_obf_9WwQ['userGroupID']) . '"';
		$_obf_OWpxVw__ = ' SELECT b.administratorsName FROM ' . TASK_TABLE . ' a,' . ADMINISTRATORS_TABLE . ' b WHERE a.surveyID=\'' . $_GET['surveyID'] . '\' AND a.taskID = \'' . $_obf_9WwQ['userGroupID'] . '\' AND a.administratorsID = b.administratorsID LIMIT 1 ';
		$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

		if ($_obf__aTmJQ__) {
			$_obf__WwKzYz1wA__ .= ',"' . $_obf__aTmJQ__['administratorsName'] . '"';
		}
		else {
			$_obf__WwKzYz1wA__ .= ',""';
		}

		$_obf__WwKzYz1wA__ .= "\r\n";
	}

	return $_obf__WwKzYz1wA__;
}

if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5|6');
if (!isset($_GET['projectOwner']) || ($_GET['projectOwner'] == '') || ($_GET['projectOwner'] == 0)) {
	_showerror('参数错误', '参数错误：不存在的关联客户对象');
}

$hSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $_GET['projectOwner'] . '\' ';
$hRow = $DB->queryFirstRow($hSQL);

if (!$hRow) {
	_showerror('参数错误', '参数错误：不存在的关联客户对象');
}

ob_start();
$ResultList = export_nodes();
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=Nodes_List_' . date('Y-m-d') . '.csv');
echo $ResultList;
exit();

?>
