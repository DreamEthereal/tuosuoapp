<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
$surveyID = (int) $surveyID;

if ($surveyID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$cacheContent .= '$exposureArray = array( ';
$SQL = ' SELECT exposureDomain,trackBeginTime,trackEndTime,exposureCampaign,firstExposureCampaign,lastExposureCampaign,exposureControl,firstExposureControl,lastExposureControl,exposureNormal,firstExposureNormal,lastExposureNormal FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ';
$tRow = $DB->queryFirstRow($SQL);
$SQL = ' SELECT * FROM ' . TRACKCODE_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ORDER BY tagID DESC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$cacheContent .= "\r\n" . '  ' . $Row['tagID'] . ' => ' . 'array(\'tagID\' => ' . $Row['tagID'] . ',' . "\r\n" . '		 \'tagCate\' => ' . $Row['tagCate'] . ',' . "\r\n" . '         \'exposure\' => "c_' . $surveyID . '_' . $Row['exposure'] . '",              ' . "\r\n" . '         \'firstExposure\' => "c_' . $surveyID . '_' . $Row['firstExposure'] . '",              ' . "\r\n" . '         \'lastExposure\' => "c_' . $surveyID . '_' . $Row['lastExposure'] . '",              ' . "\r\n" . '         \'exposureDomain\' => "' . $tRow['exposureDomain'] . '",              ' . "\r\n" . '         \'trackBeginTime\' => "' . $tRow['trackBeginTime'] . '",              ' . "\r\n" . '         \'trackEndTime\' => "' . $tRow['trackEndTime'] . '",              ' . "\r\n" . '         \'exposureCampaign\' => "t_' . $surveyID . '_' . $tRow['exposureCampaign'] . '",              ' . "\r\n" . '         \'firstExposureCampaign\' => "t_' . $surveyID . '_' . $tRow['firstExposureCampaign'] . '",              ' . "\r\n" . '         \'lastExposureCampaign\' => "t_' . $surveyID . '_' . $tRow['lastExposureCampaign'] . '",              ' . "\r\n" . '         \'exposureControl\' => "t_' . $surveyID . '_' . $tRow['exposureControl'] . '",              ' . "\r\n" . '         \'firstExposureControl\' => "t_' . $surveyID . '_' . $tRow['firstExposureControl'] . '",              ' . "\r\n" . '         \'lastExposureControl\' => "t_' . $surveyID . '_' . $tRow['lastExposureControl'] . '",              ' . "\r\n" . '         \'exposureNormal\' => "t_' . $surveyID . '_' . $tRow['exposureNormal'] . '",              ' . "\r\n" . '         \'firstExposureNormal\' => "t_' . $surveyID . '_' . $tRow['firstExposureNormal'] . '",              ' . "\r\n" . '         \'lastExposureNormal\' => "t_' . $surveyID . '_' . $tRow['lastExposureNormal'] . '"),';
}

$cacheContent = substr($cacheContent, 0, -1) . '' . "\r\n" . ');' . "\r\n" . '';
$cacheContent .= chr(63) . chr(62);
createdir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/');
write_to_file(ROOT_PATH . $Config['cacheDirectory'] . '/' . $surveyID . '/' . md5('exposure' . $surveyID) . '.php', $cacheContent);

?>
