<?php
//dezend by http://www.yunlu99.com/
function export($type)
{
	global $DB;
	global $lang;
	$_obf__WwKzYz1wA__ = '';
	$_obf_YfrY8VEd = '"ƾ֤����"';
	$_obf_YfrY8VEd .= ',"ƾ֤����"';
	$_obf_YfrY8VEd .= ',"ƾ֤����"';
	$_obf_YfrY8VEd .= ',"�������"';
	$_obf_YfrY8VEd .= ',"״̬"';
	$_obf_YfrY8VEd .= "\r\n";
	$_obf__WwKzYz1wA__ .= $_obf_YfrY8VEd;
	$_obf_xCnI = ' SELECT * FROM ' . SURVEYPROOF_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY proofID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf__WwKzYz1wA__ .= '"' . qshowexportquotechar($_obf_9WwQ['proofName']) . '"';
		$_obf__WwKzYz1wA__ .= ',"' . qshowexportquotechar($_obf_9WwQ['proofNum']) . '"';
		$_obf__WwKzYz1wA__ .= ',"' . qshowexportquotechar($_obf_9WwQ['proofPass']) . '"';

		if ($_obf_9WwQ['dataID'] != 0) {
			$_obf_OWpxVw__ = ' SELECT responseID FROM ' . $_obf_NAsaZkFieveWTPYX . 'response_' . $_GET['surveyID'] . ' WHERE responseID = \'' . $_obf_9WwQ['dataID'] . '\' ';
			$_obf__aTmJQ__ = $DB->queryFirstRow($_obf_OWpxVw__);

			if ($_obf__aTmJQ__) {
				$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['dataID'] . '"';
			}
			else {
				$_obf__WwKzYz1wA__ .= ',"' . $_obf_9WwQ['dataID'] . '(ɾ��)"';
			}

			$_obf__WwKzYz1wA__ .= ',"��ʹ��"';
		}
		else {
			$_obf__WwKzYz1wA__ .= ',"-"';
			$_obf__WwKzYz1wA__ .= ',"δʹ��"';
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
require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
require_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkroletype('1|2|5', $_GET['surveyID']);
ob_start();
$logsList = export('Logs');
header('Pragma: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: application/octet-stream;charset=utf8');
header('Content-Disposition: attachment; filename=SurveyProofList_' . $_GET['surveyID'] . '_' . date('Y-m-d') . '.csv');
echo $logsList;
exit();

?>
