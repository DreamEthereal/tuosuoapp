<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
if (!isset($isSurveyPage) || ($isSurveyPage != 1)) {
	include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
}

$surveyID = (int) $surveyID;

if ($surveyID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$cacheContent .= '$issueArray = array( ';
$SQL = ' SELECT isOpen,issueMode,issueRate,issueCookie,exposureControl,renderingCode FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ';
$tRow = $DB->queryFirstRow($SQL);
$cacheContent .= '\'isOpen\' => \'' . $tRow['isOpen'] . '\',\'issueMode\' => \'' . $tRow['issueMode'] . '\',\'issueRate\' => \'' . $tRow['issueRate'] . '\',\'issueCookie\' => \'' . $tRow['issueCookie'] . '\',\'renderingCode\' => "' . str_replace('"', '\\"', $tRow['renderingCode']) . '" ';
$SQL = ' SELECT isCheckIP,maxIpTime,beginTime,endTime,surveyName,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ';
$sRow = $DB->queryFirstRow($SQL);
$cacheContent .= ',\'isCheckIP\' => \'' . $sRow['isCheckIP'] . '\',\'maxIpTime\' => \'' . $sRow['maxIpTime'] . '\',\'beginTime\' => \'' . $sRow['beginTime'] . '\',\'endTime\' => \'' . $sRow['endTime'] . '\' ,\'surveyName\' => \'' . $sRow['surveyName'] . '\',\'surveyTitle\' => \'' . $sRow['surveyTitle'] . '\' ';
$cacheContent .= ',\'rule\' => "';
$cacheRule = '';
$SQL = ' SELECT * FROM ' . ISSUERULE_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ORDER BY ruleOrderID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	if ($Row['ruleValue'] != 0) {
		if ($Row['exposureVar'] == 0) {
			if ($Row['cookieVarName'] != '') {
				$cacheRule .= '_COOKIE[\'' . $Row['cookieVarName'] . '\'] >= ' . $Row['ruleValue'] . ' && ';
			}
			else {
				$cacheRule .= '_COOKIE[\'t_' . $surveyID . '_' . $tRow['exposureControl'] . '\'] >= ' . $Row['ruleValue'] . ' && ';
			}
		}
		else {
			$cSQL = ' SELECT exposure FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' AND tagID = \'' . $Row['exposureVar'] . '\' ';
			$cRow = $DB->queryFirstRow($cSQL);
			$cacheRule .= '_COOKIE[\'c_' . $surveyID . '_' . $cRow['exposure'] . '\'] >= ' . $Row['ruleValue'] . ' && ';
		}
	}
}

if ($cacheRule == '') {
	$cacheContent .= '" ';
}
else {
	$cacheContent .= substr($cacheRule, 0, -4) . '" ';
}

$cacheContent .= ');' . "\r\n" . '';
$cacheContent .= chr(63) . chr(62);
createdir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/');
write_to_file(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('issue' . $surveyID) . '.php', $cacheContent);

?>
