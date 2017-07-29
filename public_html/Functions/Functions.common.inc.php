<?php
//dezend by http://www.yunlu99.com/
function _obf_bHxoAV4tYWY_Yg__($CurData, $OldData, $OldNum, $MaxData, $MaxNum)
{
	global $DB;
	global $_POST;
	global $InfoRow;

	if ($InfoRow[$OldData] == $CurData) {
		$InfoRow[$OldNum]++;
	}
	else {
		$InfoRow[$OldData] = $CurData;
		$InfoRow[$OldNum] = 1;
	}

	if ($InfoRow[$MaxNum] < $InfoRow[$OldNum]) {
		$InfoRow[$MaxNum] = $InfoRow[$OldNum];
		$InfoRow[$MaxData] = $CurData;
	}

	$_obf_d16_A1Tp2_8_ = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET ' . $OldData . ' = \'' . $InfoRow[$OldData] . '\',' . $OldNum . ' = \'' . $InfoRow[$OldNum] . '\',' . $MaxData . ' = \'' . $InfoRow[$MaxData] . '\',' . $MaxNum . ' = \'' . $InfoRow[$MaxNum] . '\' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($_obf_d16_A1Tp2_8_);
}

function _obf_YD5mbWk_FgUNL3Y0Aik3B20_($TableName, $CompareField, $CompareFieldData, $AddNumField)
{
	global $DB;
	global $_POST;
	$_obf_d16_A1Tp2_8_ = ' SELECT * FROM ' . $TableName . ' WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $_POST['surveyID'] . '\' ';
	$_obf_EPqjHyHbOfQ_ = $DB->queryFirstRow($_obf_d16_A1Tp2_8_);

	if (!$_obf_EPqjHyHbOfQ_) {
		$_obf_d16_A1Tp2_8_ = ' INSERT INTO ' . $TableName . ' SET surveyID = \'' . $_POST['surveyID'] . '\',' . $CompareField . ' = \'' . $CompareFieldData . '\',' . $AddNumField . ' = 1 ';
		$DB->query($_obf_d16_A1Tp2_8_);
	}
	else {
		$_obf_d16_A1Tp2_8_ = ' UPDATE ' . $TableName . ' SET ' . $AddNumField . ' = ' . $AddNumField . '+1 WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $_POST['surveyID'] . '\' ';
		$DB->query($_obf_d16_A1Tp2_8_);
	}
}

function _obf_dnR2ZmxpG2V4di12DwcJK3Y_($TableName, $CompareField, $CompareFieldData, $AddNumField, $survey_ID)
{
	global $DB;
	$_obf_d16_A1Tp2_8_ = ' SELECT * FROM ' . $TableName . ' WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $survey_ID . '\' ';
	$_obf_EPqjHyHbOfQ_ = $DB->queryFirstRow($_obf_d16_A1Tp2_8_);

	if (!$_obf_EPqjHyHbOfQ_) {
		$_obf_d16_A1Tp2_8_ = ' INSERT INTO ' . $TableName . ' SET surveyID = \'' . $survey_ID . '\',' . $CompareField . ' = \'' . $CompareFieldData . '\',' . $AddNumField . ' = 1 ';
		$DB->query($_obf_d16_A1Tp2_8_);
	}
	else {
		$_obf_d16_A1Tp2_8_ = ' UPDATE ' . $TableName . ' SET ' . $AddNumField . ' = ' . $AddNumField . '+1 WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $survey_ID . '\' ';
		$DB->query($_obf_d16_A1Tp2_8_);
	}
}

function _obf_NmkJLw1_IRQxAhkCcDhjaz5o($TableName, $CompareField, $CompareFieldData, $AddNumField, $survey_ID)
{
	global $DB;
	$_obf_dQ5UL7C73g__ = ' SELECT ' . $AddNumField . ' FROM ' . $TableName . ' WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $survey_ID . '\' ';
	$_obf_vI9iIiW3Qg__ = $DB->queryFirstRow($_obf_dQ5UL7C73g__);

	if ($_obf_vI9iIiW3Qg__[$AddNumField] != 0) {
		$_obf_d16_A1Tp2_8_ = ' UPDATE ' . $TableName . ' SET ' . $AddNumField . ' = ' . $AddNumField . '-1 WHERE ' . $CompareField . ' = \'' . $CompareFieldData . '\' AND surveyID = \'' . $survey_ID . '\' ';
		$DB->query($_obf_d16_A1Tp2_8_);
	}
}

function dealcountinfo($survey_ID, $joinTime = '')
{
	global $DB;
	global $_POST;
	global $InfoRow;

	if ($joinTime == '') {
		$joinTime = time();
	}

	$_obf_d16_A1Tp2_8_ = 'SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $survey_ID . '\' ';
	$InfoRow = $DB->queryFirstRow($_obf_d16_A1Tp2_8_);
	$_obf_aiJCbAht0Q__ = date('G', $joinTime);
	$_obf_KgeeSWuL = date('j', $joinTime);
	$_obf_xkkDGt6iiJU_ = date('n', $joinTime);
	$_obf_E4M5fZ81fA__ = date('Y', $joinTime);
	$_obf_GLzYRrZbe1XKpQ__ = date('Y-n-j', $joinTime);
	$_obf_MoNKE3UPHHhCvl_L = date('Y-n', $joinTime);
	$_obf_o7psUMEbb77rWsE_ = date('Y-n-j H:00:00', $joinTime);
	$_obf_yLQ_N_a77Z8_ = $InfoRow['TotalNum'] + 1;

	if ($InfoRow['StartDate'] == '') {
		$InfoRow['StartDate'] = $_obf_GLzYRrZbe1XKpQ__;
	}

	if ($InfoRow['OldDay'] == '') {
		$InfoRow['OldDay'] = $_obf_GLzYRrZbe1XKpQ__;
	}

	$_obf_R8jBDoto = $InfoRow['OldDay'];

	if ($InfoRow['surveyID'] != '') {
		$_obf_FW9sMG_kJw__ = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET TotalNum = TotalNum+1,StartDate = \'' . $InfoRow['StartDate'] . '\',OldDay = \'' . $InfoRow['OldDay'] . '\' WHERE surveyID = \'' . $survey_ID . '\'';
	}
	else {
		$_obf_FW9sMG_kJw__ = ' INSERT INTO ' . COUNTGENERALINFO_TABLE . ' SET StartDate = \'' . date('Y-m-d', $joinTime) . '\',TotalNum =1,OldDay = \'' . $InfoRow['OldDay'] . '\',surveyID = \'' . $survey_ID . '\' ';
	}

	$DB->query($_obf_FW9sMG_kJw__);
	_obf_bHxoAV4tYWY_Yg__($_obf_MoNKE3UPHHhCvl_L, 'OldMonth', 'MonthNum', 'MonthMaxDate', 'MonthMaxNum');
	_obf_bHxoAV4tYWY_Yg__($_obf_GLzYRrZbe1XKpQ__, 'OldDay', 'DayNum', 'DayMaxDate', 'DayMaxNum');
	_obf_bHxoAV4tYWY_Yg__($_obf_o7psUMEbb77rWsE_, 'OldHour', 'HourNum', 'HourMaxTime', 'HourMaxNum');
	$_obf_3jCFRQJr_45H5Ib6 = 'h' . $_obf_aiJCbAht0Q__;
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTDAYNUM_TABLE, 'TDay', $_obf_GLzYRrZbe1XKpQ__, $_obf_3jCFRQJr_45H5Ib6);
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTDAYNUM_TABLE, 'TDay', 'Total', $_obf_3jCFRQJr_45H5Ib6);
	$_obf_75Z2c_esIU_pKiFcgA__ = 'm' . $_obf_xkkDGt6iiJU_;
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTYEARNUM_TABLE, 'TYear', $_obf_E4M5fZ81fA__, $_obf_75Z2c_esIU_pKiFcgA__);
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTYEARNUM_TABLE, 'TYear', 'Total', $_obf_75Z2c_esIU_pKiFcgA__);
	$_obf_5CN4RDNa6IYBC3U_ = 'd' . $_obf_KgeeSWuL;
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTMONTHNUM_TABLE, 'TMonth', $_obf_MoNKE3UPHHhCvl_L, $_obf_5CN4RDNa6IYBC3U_);
	_obf_YD5mbWk_FgUNL3Y0Aik3B20_(COUNTMONTHNUM_TABLE, 'TMonth', 'Total', $_obf_5CN4RDNa6IYBC3U_);
}

function docountinfo($survey_ID, $joinTime = '')
{
	global $DB;
	global $_POST;
	global $InfoRow;

	if ($joinTime == '') {
		$joinTime = time();
	}

	$_obf_d16_A1Tp2_8_ = 'SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $survey_ID . '\' ';
	$InfoRow = $DB->queryFirstRow($_obf_d16_A1Tp2_8_);
	$_obf_aiJCbAht0Q__ = date('G', $joinTime);
	$_obf_KgeeSWuL = date('j', $joinTime);
	$_obf_xkkDGt6iiJU_ = date('n', $joinTime);
	$_obf_E4M5fZ81fA__ = date('Y', $joinTime);
	$_obf_GLzYRrZbe1XKpQ__ = date('Y-n-j', $joinTime);
	$_obf_MoNKE3UPHHhCvl_L = date('Y-n', $joinTime);
	$_obf_o7psUMEbb77rWsE_ = date('Y-n-j H:00:00', $joinTime);
	$_obf_yLQ_N_a77Z8_ = $InfoRow['TotalNum'] + 1;

	if ($InfoRow['StartDate'] == '') {
		$InfoRow['StartDate'] = $_obf_GLzYRrZbe1XKpQ__;
	}

	if ($InfoRow['OldDay'] == '') {
		$InfoRow['OldDay'] = $_obf_GLzYRrZbe1XKpQ__;
	}

	$_obf_R8jBDoto = $InfoRow['OldDay'];

	if ($InfoRow['surveyID'] != '') {
		$_obf_FW9sMG_kJw__ = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET TotalNum = TotalNum+1,StartDate = \'' . $InfoRow['StartDate'] . '\',OldDay = \'' . $InfoRow['OldDay'] . '\' ';
	}
	else {
		$_obf_FW9sMG_kJw__ = ' INSERT INTO ' . COUNTGENERALINFO_TABLE . ' SET StartDate = \'' . date('Y-m-d', $joinTime) . '\',TotalNum =1,OldDay = \'' . $InfoRow['OldDay'] . '\',surveyID = \'' . $survey_ID . '\' ';
	}

	if ($InfoRow['MonthMaxDate'] == $_obf_MoNKE3UPHHhCvl_L) {
		$_obf_FW9sMG_kJw__ .= ' ,MonthMaxNum = MonthMaxNum+1 ';
	}

	if ($InfoRow['OldMonth'] == $_obf_MoNKE3UPHHhCvl_L) {
		$_obf_FW9sMG_kJw__ .= ' ,MonthNum = MonthNum+1 ';
	}

	if ($InfoRow['DayMaxDate'] == $_obf_GLzYRrZbe1XKpQ__) {
		$_obf_FW9sMG_kJw__ .= ' ,DayMaxNum = DayMaxNum+1 ';
	}

	if ($InfoRow['OldDay'] == $_obf_GLzYRrZbe1XKpQ__) {
		$_obf_FW9sMG_kJw__ .= ' ,DayNum = DayNum+1 ';
	}

	if ($InfoRow['HourMaxTime'] == $_obf_o7psUMEbb77rWsE_) {
		$_obf_FW9sMG_kJw__ .= ' ,HourMaxNum = HourMaxNum+1 ';
	}

	if ($InfoRow['OldHour'] == $_obf_o7psUMEbb77rWsE_) {
		$_obf_FW9sMG_kJw__ .= ' ,HourNum = HourNum+1 ';
	}

	if ($InfoRow['surveyID'] != '') {
		$_obf_FW9sMG_kJw__ .= ' WHERE surveyID = \'' . $survey_ID . '\'';
	}

	$DB->query($_obf_FW9sMG_kJw__);
	$_obf_3jCFRQJr_45H5Ib6 = 'h' . $_obf_aiJCbAht0Q__;
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTDAYNUM_TABLE, 'TDay', $_obf_GLzYRrZbe1XKpQ__, $_obf_3jCFRQJr_45H5Ib6, $survey_ID);
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTDAYNUM_TABLE, 'TDay', 'Total', $_obf_3jCFRQJr_45H5Ib6, $survey_ID);
	$_obf_75Z2c_esIU_pKiFcgA__ = 'm' . $_obf_xkkDGt6iiJU_;
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTYEARNUM_TABLE, 'TYear', $_obf_E4M5fZ81fA__, $_obf_75Z2c_esIU_pKiFcgA__, $survey_ID);
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTYEARNUM_TABLE, 'TYear', 'Total', $_obf_75Z2c_esIU_pKiFcgA__, $survey_ID);
	$_obf_5CN4RDNa6IYBC3U_ = 'd' . $_obf_KgeeSWuL;
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTMONTHNUM_TABLE, 'TMonth', $_obf_MoNKE3UPHHhCvl_L, $_obf_5CN4RDNa6IYBC3U_, $survey_ID);
	_obf_dnR2ZmxpG2V4di12DwcJK3Y_(COUNTMONTHNUM_TABLE, 'TMonth', 'Total', $_obf_5CN4RDNa6IYBC3U_, $survey_ID);
}

function delcountinfo($survey_ID, $joinTime)
{
	global $DB;
	$_obf_aiJCbAht0Q__ = date('G', $joinTime);
	$_obf_KgeeSWuL = date('j', $joinTime);
	$_obf_xkkDGt6iiJU_ = date('n', $joinTime);
	$_obf_E4M5fZ81fA__ = date('Y', $joinTime);
	$_obf_GLzYRrZbe1XKpQ__ = date('Y-n-j', $joinTime);
	$_obf_MoNKE3UPHHhCvl_L = date('Y-n', $joinTime);
	$_obf_o7psUMEbb77rWsE_ = date('Y-n-j H:00:00', $joinTime);
	$_obf_d16_A1Tp2_8_ = 'SELECT * FROM ' . COUNTGENERALINFO_TABLE . ' WHERE surveyID = \'' . $survey_ID . '\' ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_d16_A1Tp2_8_);
	$_obf_FW9sMG_kJw__ = ' UPDATE ' . COUNTGENERALINFO_TABLE . ' SET ';

	if ($_obf_9WwQ['TotalNum'] != 0) {
		$_obf_FW9sMG_kJw__ .= ' TotalNum = TotalNum-1 ';
	}
	else {
		$_obf_FW9sMG_kJw__ .= ' TotalNum = 0 ';
	}

	if (($_obf_9WwQ['MonthMaxDate'] == $_obf_MoNKE3UPHHhCvl_L) && ($_obf_9WwQ['MonthMaxNum'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,MonthMaxNum = MonthMaxNum-1 ';
	}

	if (($_obf_9WwQ['OldMonth'] == $_obf_MoNKE3UPHHhCvl_L) && ($_obf_9WwQ['MonthNum'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,MonthNum = MonthNum-1 ';
	}

	if (($_obf_9WwQ['DayMaxDate'] == $_obf_GLzYRrZbe1XKpQ__) && ($_obf_9WwQ['DayMaxNum'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,DayMaxNum = DayMaxNum-1 ';
	}

	if (($_obf_9WwQ['OldDay'] == $_obf_GLzYRrZbe1XKpQ__) && ($_obf_9WwQ['DayNum'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,DayNum = DayNum-1 ';
	}

	if (($_obf_9WwQ['HourMaxTime'] == $_obf_o7psUMEbb77rWsE_) && ($_obf_9WwQ['HourMaxTime'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,HourMaxNum = HourMaxNum-1 ';
	}

	if (($_obf_9WwQ['OldHour'] == $_obf_o7psUMEbb77rWsE_) && ($_obf_9WwQ['HourNum'] != 0)) {
		$_obf_FW9sMG_kJw__ .= ' ,HourNum = HourNum-1 ';
	}

	$_obf_FW9sMG_kJw__ .= ' WHERE surveyID = \'' . $survey_ID . '\'';
	$DB->query($_obf_FW9sMG_kJw__);
	$_obf_3jCFRQJr_45H5Ib6 = 'h' . $_obf_aiJCbAht0Q__;
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTDAYNUM_TABLE, 'TDay', $_obf_GLzYRrZbe1XKpQ__, $_obf_3jCFRQJr_45H5Ib6, $survey_ID);
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTDAYNUM_TABLE, 'TDay', 'Total', $_obf_3jCFRQJr_45H5Ib6, $survey_ID);
	$_obf_75Z2c_esIU_pKiFcgA__ = 'm' . $_obf_xkkDGt6iiJU_;
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTYEARNUM_TABLE, 'TYear', $_obf_E4M5fZ81fA__, $_obf_75Z2c_esIU_pKiFcgA__, $survey_ID);
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTYEARNUM_TABLE, 'TYear', 'Total', $_obf_75Z2c_esIU_pKiFcgA__, $survey_ID);
	$_obf_5CN4RDNa6IYBC3U_ = 'd' . $_obf_KgeeSWuL;
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTMONTHNUM_TABLE, 'TMonth', $_obf_MoNKE3UPHHhCvl_L, $_obf_5CN4RDNa6IYBC3U_, $survey_ID);
	_obf_NmkJLw1_IRQxAhkCcDhjaz5o(COUNTMONTHNUM_TABLE, 'TMonth', 'Total', $_obf_5CN4RDNa6IYBC3U_, $survey_ID);
}

function countpercent($optionResponseNum, $totalResponseNum)
{
	if ($totalResponseNum != 0) {
		$_obf_Tg4KRli4JlEMa7GuzA__ = @round((100 / $totalResponseNum) * $optionResponseNum, 2);
	}
	else {
		$_obf_Tg4KRli4JlEMa7GuzA__ = 0;
	}

	return $_obf_Tg4KRli4JlEMa7GuzA__;
}

function _obf_XWtjdGkGZSBvaXJd($optionCoeffNum, $totalResponseNum)
{
	if ($totalResponseNum != 0) {
		$_obf_QD_svkiO2bNxBMupuDQ_ = @round($optionCoeffNum / $totalResponseNum, 2);
	}
	else {
		$_obf_QD_svkiO2bNxBMupuDQ_ = '0.00';
	}

	return $_obf_QD_svkiO2bNxBMupuDQ_;
}

function meanaverage($optionCoeffNum, $totalResponseNum)
{
	if ($totalResponseNum != 0) {
		$_obf_QD_svkiO2bNxBMupuDQ_ = @round($optionCoeffNum / $totalResponseNum, 5);
	}
	else {
		$_obf_QD_svkiO2bNxBMupuDQ_ = '0';
	}

	return $_obf_QD_svkiO2bNxBMupuDQ_;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
