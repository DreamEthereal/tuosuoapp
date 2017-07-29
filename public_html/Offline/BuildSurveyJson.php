<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$valueLogicQtnList = array();
require ROOT_PATH . 'Includes/JsonCache.php';
require ROOT_PATH . 'PerUserData/tmp/' . $tmpFilePathName . '/' . $tmpFilePathName . '.php';
require ROOT_PATH . 'Includes/QuotaCache.php';
require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Quota' . $theSID) . '.php';
$thePageSurveyID = $theSID;
require ROOT_PATH . 'Process/Page.inc.php';
$cacheContent = '';
$cacheContent .= ' var QtnListArray = ' . json($QtnListArray) . ';';
$cacheContent .= ' var YesNoListArray = ' . json($YesNoListArray) . ';';
$cacheContent .= ' var RadioListArray = ' . json($RadioListArray) . ';';
$cacheContent .= ' var CheckBoxListArray = ' . json($CheckBoxListArray) . ';';
$cacheContent .= ' var InfoListArray = ' . json($InfoListArray) . ';';
$cacheContent .= ' var AnswerListArray = ' . json($AnswerListArray) . ';';
$cacheContent .= ' var OptionListArray = ' . json($OptionListArray) . ';';
$cacheContent .= ' var LabelListArray = ' . json($LabelListArray) . ';';
$cacheContent .= ' var RankListArray = ' . json($RankListArray) . ';';
$cacheContent .= ' var CondListArray = ' . json($CondListArray) . ';';
$cacheContent .= ' var CascadeArray = ' . json($CascadeArray) . ';';
$cacheContent .= ' var QassListArray = ' . json($QassListArray) . ';';
$cacheContent .= ' var OassListArray = ' . json($OassListArray) . ';';
$cacheContent .= ' var ValueRelArray = ' . json($ValueRelArray) . ';';
$cacheContent .= ' var valueLogicQtnList = ' . json($valueLogicQtnList) . ';';
$cacheContent .= ' var QuotaListArray = ' . json($QuotaListArray) . ';';
$cacheContent .= ' var QuotaNumArray = ' . json($QuotaNumArray) . ';';
$cacheContent .= ' var PagesListArray = ' . json($pageQtnList) . ';';
$cacheContent .= ' var PagesBreakArray = ' . json($pageBreak) . ';';
$cacheContent .= ' var CondRadioListArray = ' . json($CondRadioListArray) . ';';
$cacheContent .= ' var textCheckArray = ' . json($TextCheckArray) . ';';

foreach ($cascadeQtnList as $cascadeID) {
	$CascadeData = 'Cascade_' . $cascadeID;
	$cacheContent .= ' var Cascade_' . $cascadeID . ' = \'' . $$CascadeData . '\';';
}

$destination = $Config['absolutenessPath'] . 'PerUserData/tmp/' . $tmpFilePathName . '/';
createdir($destination);
write_to_file($destination . '/jsondata.js', $cacheContent);

?>
