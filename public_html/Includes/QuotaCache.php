<?php
//dezend by http://www.yunlu99.com/
function write_to_cache($file_name, $data, $method = 'w')
{
	$_obf_wWFgG_EIcg__ = fopen($file_name, $method);
	flock($_obf_wWFgG_EIcg__, LOCK_EX);
	$_obf_fPX93OEFX6y0 = fwrite($_obf_wWFgG_EIcg__, $data);
	fclose($_obf_wWFgG_EIcg__);
	return $_obf_fPX93OEFX6y0;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
$theSID = (int) $theSID;

if ($theSID == 0) {
	exit();
}

$cacheContent = '<?php' . "\r\n" . '/**************************************************************************' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *    EnableQ System                                                      *' . "\r\n" . ' *    ----------------------------------------------------------------    *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  ' . "\r\n" . ' *        WebSite: itenable.com.cn                                        *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' *        Last Modified: 2013/06/30                                       *' . "\r\n" . ' *        Scriptversion: 8.xx                                             *' . "\r\n" . ' *                                                                        *' . "\r\n" . ' **************************************************************************/' . "\r\n" . 'if (!defined(\'ROOT_PATH\'))' . "\r\n" . '{' . "\r\n" . '	die(\'EnableQ Security Violation\');' . "\r\n" . '}';
$cacheContent .= "\r\n";
$quotaCacheContent = '$QuotaListArray = array( ';
$quotaNameContent = '$QuotaNumArray = array( ';
$logicQtnList = '';
$SQL = ' SELECT DISTINCT a.quotaID,a.quotaNum,a.quotaText FROM ' . QUOTA_TABLE . ' a,' . CONDITIONS_TABLE . ' b WHERE a.surveyID =\'' . $theSID . '\' AND a.quotaID=b.quotaID ORDER BY a.quotaID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$CondSQL = ' SELECT DISTINCT a.condOnID FROM ' . CONDITIONS_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.quotaID = \'' . $Row['quotaID'] . '\' AND a.questionID=0 AND a.condOnID = b.questionID ORDER BY b.orderByID ASC,a.condOnID ASC ';
	$CondResult = $DB->query($CondSQL);
	$CondCount = $DB->_getNumRows($CondResult);

	if ($CondCount == 0) {
		continue;
	}

	$quotaNameContent .= "\r\n" . '  ' . $Row['quotaID'] . ' => array( ';
	$quotaNameContent .= '\'quotaNum\' => ' . $Row['quotaNum'] . ',';
	$quotaNameContent .= '\'quotaText\' => "' . qquotetostring(qnl2br($Row['quotaText']), 1) . '"';
	$quotaNameContent .= '),';
	$quotaCacheContent .= "\r\n" . '  ' . $Row['quotaID'] . ' => array( ';

	while ($CondRow = $DB->queryArray($CondResult)) {
		$quotaCacheContent .= "\r\n" . '      ' . $CondRow['condOnID'] . ' => array(';
		$RangeSQL = ' SELECT DISTINCT qtnID FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND quotaID=\'' . $Row['quotaID'] . '\' ORDER BY qtnID ASC ';
		$RangeResult = $DB->query($RangeSQL);

		while ($RangeRow = $DB->queryArray($RangeResult)) {
			$quotaCacheContent .= "\r\n" . '            ' . $RangeRow['qtnID'] . ' => array(';
			$Con_SQL = ' SELECT optionID,opertion,logicValueIsAnd,logicMode FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $CondRow['condOnID'] . '\' AND qtnID = \'' . $RangeRow['qtnID'] . '\' AND quotaID=\'' . $Row['quotaID'] . '\' ORDER BY optionID ASC ';
			$Con_Result = $DB->query($Con_SQL);
			$m = 0;

			while ($Con_Row = $DB->queryArray($Con_Result)) {
				$quotaCacheContent .= '' . "\r\n" . '                  ' . $m . ' => ' . 'array(' . $Con_Row['optionID'] . ',' . $Con_Row['opertion'] . ',' . $Con_Row['logicValueIsAnd'] . ',' . $Con_Row['logicMode'] . '),';
				$m++;
			}

			$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . '            ),';
		}

		$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . '     ),';
	}

	$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . '  ),';
}

$quotaNameContent = substr($quotaNameContent, 0, -1) . '' . "\r\n" . ');';
$quotaCacheContent = substr($quotaCacheContent, 0, -1) . '' . "\r\n" . ');';
$cacheContent .= $quotaNameContent . "\r\n" . $quotaCacheContent . "\r\n";
$SQL = ' SELECT condOnID,qtnID,optionID FROM ' . CONDITIONS_TABLE . ' WHERE quotaID !=0 AND surveyID =\'' . $theSID . '\' ORDER BY questionID ASC ';
$Result = $DB->query($SQL);
$haveLogicQtnList = array();

while ($Row = $DB->queryArray($Result)) {
	$bSQL = ' SELECT questionType FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $Row['condOnID'] . '\' ';
	$bRow = $DB->queryFirstRow($bSQL);

	switch ($bRow['questionType']) {
	case '1':
	case '2':
	case '3':
	case '4':
	case '24':
	case '25':
	case '30':
	case '17':
		if (!in_array('option_' . $Row['condOnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '\';' . "\r\n" . '';
		}

		break;

	case '6':
	case '7':
	case '19':
	case '28':
	case '23':
	case '10':
	case '15':
	case '16':
	case '20':
	case '21':
	case '22':
	case '31':
		if (!in_array('option_' . $Row['condOnID'] . '_' . $Row['qtnID'], $haveLogicQtnList)) {
			$haveLogicQtnList[] = 'option_' . $Row['condOnID'] . '_' . $Row['qtnID'];
			$logicQtnList .= '$valueLogicQtnList[] = \'option_' . $Row['condOnID'] . '_' . $Row['qtnID'] . '\';' . "\r\n" . '';
		}

		break;
	}
}

$cacheContent .= $logicQtnList . "\r\n";
$cacheContent .= chr(63) . chr(62);
unset($haveLogicQtnList);
createdir(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/');
write_to_cache(ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Quota' . $theSID) . '.php', $cacheContent);

?>
