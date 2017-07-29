<?php
//dezend by http://www.yunlu99.com/
function createdatadir($dir, $mode = 511)
{
	if (is_dir($dir)) {
		return true;
	}

	$dir = dirname($dir . '/a');
	$_obf_Byrh3A__ = str_replace('\\', '/', $dir);
	$_obf_jXiWt4lZNw__ = explode('/', $_obf_Byrh3A__);
	$_obf_gftfagw_ = count($_obf_jXiWt4lZNw__);
	$_obf_7w__ = count($_obf_jXiWt4lZNw__) - 1;

	for (; 0 <= $_obf_7w__; $_obf_7w__--) {
		$_obf_Byrh3A__ = dirname($_obf_Byrh3A__);

		if (is_dir($_obf_Byrh3A__)) {
			$_obf_XA__ = $_obf_7w__;

			for (; $_obf_XA__ < $_obf_gftfagw_; $_obf_XA__++) {
				$_obf_Byrh3A__ .= '/' . $_obf_jXiWt4lZNw__[$_obf_XA__];

				if (is_dir($_obf_Byrh3A__)) {
					continue;
				}

				$_obf_eSsQSg__ = @mkdir($_obf_Byrh3A__, $mode);

				if (!$_obf_eSsQSg__) {
					return false;
				}
			}

			return true;
		}
	}

	return false;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';

if ($surveyID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$cacheContent .= '$administratorsIDList = \'';
$ghSQL = ' SELECT DISTINCT value FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' AND administratorsoptionID = 0 LIMIT 1 ';
$ghRow = $DB->queryFirstRow($ghSQL);
$GroupArray = array();

if ($ghRow) {
	$GroupSQL = ' SELECT b.administratorsID FROM ' . RESPONSEGROUPLIST_TABLE . ' a,' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsoptionID=0 AND b.isActive=1 AND b.isAdmin =0 AND a.surveyID=\'' . $surveyID . '\' AND b.administratorsGroupID = a.value ORDER BY b.administratorsID ASC ';
	$GroupResult = $DB->query($GroupSQL);

	while ($GroupRow = $DB->queryArray($GroupResult)) {
		$GroupArray[] = $GroupRow['administratorsID'];
	}
}

$PanelArray = array();
$OptionSQL = ' SELECT DISTINCT administratorsoptionID FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE administratorsoptionID !=0 AND surveyID=\'' . $surveyID . '\' ORDER BY responseGroupListID ASC ';
$OptionResult = $DB->query($OptionSQL);
$OptionArray = array();

while ($OptionRow = $DB->queryArray($OptionResult)) {
	$OptionArray[] = $OptionRow['administratorsoptionID'];
}

$labelk = 0;

foreach ($OptionArray as $administratorsoptionID) {
	$conList = '';
	$PanelSQL = ' SELECT a.value,a.administratorsoptionID,b.types FROM ' . RESPONSEGROUPLIST_TABLE . ' a, ' . ADMINISTRATORSOPTION_TABLE . ' b WHERE a.administratorsoptionID !=0 AND a.surveyID=\'' . $surveyID . '\' AND a.administratorsoptionID =b.administratorsoptionID AND a.administratorsoptionID =\'' . $administratorsoptionID . '\' ORDER BY a.responseGroupListID ASC ';
	$PanelResult = $DB->query($PanelSQL);
	$PanelTotal = $DB->_getNumRows($PanelResult);
	$i = 0;

	if (2 <= $PanelTotal) {
		$conList .= '(';
	}

	while ($PanelRow = $DB->queryArray($PanelResult)) {
		$i++;

		if ($i == $PanelTotal) {
			switch ($PanelRow['types']) {
			case 'radio':
			case 'select':
				$conList .= ' (b.value = \'' . $PanelRow['value'] . '\' AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') ';
				break;

			case 'checkbox':
				$conList .= ' (FIND_IN_SET(\'' . $PanelRow['value'] . '\',b.value) AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') ';
				break;

			case 'text':
				$conList .= ' (b.value like \'%' . $PanelRow['value'] . '%\' AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') ';
				break;
			}
		}
		else {
			switch ($PanelRow['types']) {
			case 'radio':
			case 'select':
				$conList .= ' (b.value = \'' . $PanelRow['value'] . '\'  AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') OR ';
				break;

			case 'checkbox':
				$conList .= ' (FIND_IN_SET(\'' . $PanelRow['value'] . '\',b.value)  AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') OR ';
				break;

			case 'text':
				$conList .= ' (b.value like \'%' . $PanelRow['value'] . '%\'  AND b.administratorsoptionID=' . $PanelRow['administratorsoptionID'] . ') OR ';
				break;
			}
		}
	}

	if (2 <= $PanelTotal) {
		$conList .= ')';
	}

	$PanelSQL = ' SELECT DISTINCT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a, ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' b WHERE a.administratorsID =b.administratorsID AND a.isActive=1 AND a.isAdmin =0 AND ' . $conList . ' ORDER BY a.administratorsID ASC ';
	$PanelResult = $DB->query($PanelSQL);

	while ($PanelRow = $DB->queryArray($PanelResult)) {
		$PanelArray[$labelk][] = $PanelRow['administratorsID'];
	}

	$labelk++;
}

$theChangeArray = $PanelArray[0];
$k = 1;

for (; $k < count($PanelArray); $k++) {
	$theChangeArray = array_intersect($PanelArray[$k], $theChangeArray);
}

$adminIDArray = array();

if ($ghRow) {
	if (count($OptionArray) != 0) {
		$adminIDArray = array_intersect($theChangeArray, $GroupArray);
	}
	else {
		$adminIDArray = $GroupArray;
	}
}
else if (count($OptionArray) != 0) {
	$adminIDArray = $theChangeArray;
}

if (!empty($adminIDArray)) {
	$administratorsIDList = implode(',', $adminIDArray);
}
else {
	$administratorsIDList = '';
}

unset($PanelArray);
unset($GroupArray);
unset($adminIDArray);
unset($OptionArray);
unset($theChangeArray);
$cacheContent .= $administratorsIDList . '\';';
$cacheContent .= "\r\n";
$cacheContent .= chr(63) . chr(62);
createdatadir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/');
write_to_file(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('Panel' . $surveyID) . '.php', $cacheContent);
$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isPanelCache =0 WHERE surveyID=\'' . $surveyID . '\' ';
$DB->query($SQL);

?>
