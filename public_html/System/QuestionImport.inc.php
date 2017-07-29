<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

if (!is_dir($File_DIR_Name)) {
	mkdir($File_DIR_Name, 511);
}

$tmpExt = explode('.', $_FILES['localFile']['name']);
$tmpNum = count($tmpExt) - 1;
$extension = strtolower($tmpExt[$tmpNum]);
$newFileName = 'TXT_' . date('YmdHis', time()) . rand(1, 999) . '.txt';
$newFullName = $File_DIR_Name . $newFileName;
if (is_uploaded_file($_FILES['localFile']['tmp_name']) && ($extension == 'txt')) {
	copy($_FILES['localFile']['tmp_name'], $newFullName);
}
else {
	_showerror($lang['error_system'], $lang['txt_file_type_error']);
}

setlocale(LC_ALL, 'zh_CN.GBK');
$File = fopen($newFullName, 'rb');
$recNum = 0;
$theImportQtnArray = array();
$theQtnCount = 0;

while ($txtData = fgets($File)) {
	$txtData = trim($txtData);

	if (preg_match_all('\'\\[.*?\\]\'si', $txtData, $Matches, PREG_SET_ORDER)) {
		$theQtnName = addslashes(trim(str_replace($Matches[0][0], '', $txtData)));
		$isValidQtn = true;

		switch (trim($Matches[0][0])) {
		case $lang['txt_yesno']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'1\',isRequired=1,isSelect=1 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 1;
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $lang['yesno_1_1'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $lang['yesno_1_2'] . '\' ';
			$DB->query($SQL);
			$recNum++;
			break;

		case $lang['txt_radio']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'2\',isPublic=\'1\',isRequired=\'1\',length=30 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 2;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_combradio']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'24\',isPublic=\'1\',isRequired=\'1\',isHaveOther=0,isSelect=0 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 24;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_checkbox']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'3\',isPublic=\'1\',isRequired=\'1\',length=30 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 3;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_combcheckbox']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'25\',isPublic=\'1\',isRequired=\'1\',isHaveOther=0,isSelect=0 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 25;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_text']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'4\',isPublic=\'1\',isRequired=\'1\',rows=\'30\',isHaveUnkown=1 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 4;
			$recNum++;
			break;

		case $lang['txt_textarea']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'5\',isPublic=\'1\',isRequired=\'1\',rows=\'4\',length=\'70\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 5;
			$recNum++;
			break;

		case $lang['txt_combtext']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'23\',isPublic=\'1\',isHaveUnkown=1 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 23;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_multipletext']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'27\',isPublic=\'1\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 27;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_list']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'14\',isPublic=\'1\',isRequired=\'0\',length=\'30\',rows=\'3\',maxSize=0 ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 14;
			$recNum++;
			break;

		case $lang['txt_rank']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'10\',isPublic=\'1\',isRequired=\'0\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 10;
			$recNum++;
			break;

		case $lang['txt_weight']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'16\',isRequired=\'0\',maxSize=\'100\',isPublic=\'1\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 16;
			$recNum++;
			break;

		case $lang['txt_rating']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'15\',isRequired=\'0\',startScale=\'1\',endScale=\'5\',weight=\'1\',isPublic=\'1\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 15;
			$recNum++;
			break;

		case $lang['txt_range']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'6\',isRequired=\'0\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 6;
			$recNum++;
			break;

		case $lang['txt_combrange']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'26\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 26;
			$recNum++;
			break;

		case $lang['txt_multi']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'7\',isRequired=\'0\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 7;
			$i = 1;
			$recNum++;
			break;

		case $lang['txt_upload']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'11\',isRequired=\'0\',length=\'60\',maxSize=\'2\',allowType=\'.rar|.zip|.gzip|.pdf|.doc|.jpg|.jpeg|.gif|.bmp|.png|.txt|.xls|\',hiddenVarName=\'1\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 11;
			$recNum++;
			break;

		case $lang['txt_info']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'9\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 9;
			$recNum++;
			break;

		case $lang['txt_page']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['page_break'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'8\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 8;
			$recNum++;
			break;

		case $lang['txt_empty']:
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $theQtnName . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'30\' ';
			$DB->query($SQL);
			$newQuestionID = $DB->_GetInsertID();
			updateorderid('question');
			$questionType = 30;
			$recNum++;
			break;

		default:
			$isValidQtn = false;
			break;
		}

		if ($isValidQtn == true) {
			$theImportQtnArray[$theQtnCount] = $newQuestionID;
			$theQtnCount++;
		}
	}
	else {
		if (($newQuestionID != '') && ($newQuestionID != 0) && (trim($txtData) != '')) {
			$txtData = trim($txtData);

			switch ($questionType) {
			case '2':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_other_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_other_2'])) {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\',otherText=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' WHERE questionID=\'' . $newQuestionID . '\' ';
					$DB->query($SQL);
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(trim($txtData)) . '\',optionNameFile=\'\',createDate=\'' . time() . '\' ';
					$DB->query($SQL);
					updateorderid('question_radio');
				}

				$i++;
				break;

			case '24':
				$SQL = ' INSERT INTO ' . QUESTION_RADIO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(trim($txtData)) . '\',createDate=\'' . time() . '\',isHaveText=1 ';
				$DB->query($SQL);
				updateorderid('question_radio');
				$i++;
				break;

			case '3':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_other_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_other_2'])) {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\',otherText=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' WHERE questionID=\'' . $newQuestionID . '\' ';
					$DB->query($SQL);
				}
				else {
					if ((cnsubstr($txtData, 0, 3) == $lang['txt_neg_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_neg_2'])) {
						$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isNeg=\'1\',allowType=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' WHERE questionID=\'' . $newQuestionID . '\' ';
						$DB->query($SQL);
					}
					else {
						$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(trim($txtData)) . '\',optionNameFile=\'\',createDate=\'' . time() . '\' ';
						$DB->query($SQL);
						updateorderid('question_checkbox');
					}
				}

				$i++;
				break;

			case '25':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_neg_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_neg_2'])) {
					$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\',createDate=\'' . time() . '\',isExclusive=1,isHaveText=0,isRequired=0 ';
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(trim($txtData)) . '\',createDate=\'' . time() . '\',isHaveText=1 ';
				}

				$DB->query($SQL);
				updateorderid('question_checkbox');
				$i++;
				break;

			case '23':
				$SQL = ' INSERT INTO ' . QUESTION_YESNO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
				$DB->query($SQL);
				updateorderid('question_yesno');
				$i++;
				break;

			case '6':
				if (preg_match_all('\'\\{.*?\\}\'si', $txtData, $OptMatches, PREG_SET_ORDER)) {
					$theOptName = addslashes(trim(str_replace($OptMatches[0][0], '', $txtData)));

					switch (trim($OptMatches[0][0])) {
					case $lang['txt_row']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 1;
						break;

					case $lang['txt_col']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 2;
						break;

					case $lang['txt_other_1']:
					case $lang['txt_other_2']:
						$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\' WHERE questionID=\'' . $newQuestionID . '\' ';
						$DB->query($SQL);
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 1;
						break;
					}
				}
				else {
					switch ($theSubType) {
					case 1:
					default:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;

					case 2:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;
					}
				}

				break;

			case '7':
				if (preg_match_all('\'\\{.*?\\}\'si', $txtData, $OptMatches, PREG_SET_ORDER)) {
					$theOptName = addslashes(trim(str_replace($OptMatches[0][0], '', $txtData)));

					switch (trim($OptMatches[0][0])) {
					case $lang['txt_row']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\',optionOptionID=\'' . $i . '\' ';
						$DB->query($SQL);
						$i++;
						$theSubType = 1;
						break;

					case $lang['txt_col']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 2;
						break;

					case $lang['txt_other_1']:
					case $lang['txt_other_2']:
						$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\' WHERE questionID=\'' . $newQuestionID . '\' ';
						$DB->query($SQL);
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 1;
						break;
					}
				}
				else {
					switch ($theSubType) {
					case 1:
					default:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\',optionOptionID=\'' . $i . '\' ';
						$DB->query($SQL);
						$i++;
						break;

					case 2:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;
					}
				}

				break;

			case '27':
				if (preg_match_all('\'\\{.*?\\}\'si', $txtData, $OptMatches, PREG_SET_ORDER)) {
					$theOptName = addslashes(trim(str_replace($OptMatches[0][0], '', $txtData)));

					switch (trim($OptMatches[0][0])) {
					case $lang['txt_row']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 1;
						break;

					case $lang['txt_col']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$i++;
						$theSubType = 2;
						break;
					}
				}
				else {
					switch ($theSubType) {
					case 1:
					default:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;

					case 2:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionOptionID=\'' . $i . '\',optionLabel=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						$i++;
						break;
					}
				}

				break;

			case '26':
				if (preg_match_all('\'\\{.*?\\}\'si', $txtData, $OptMatches, PREG_SET_ORDER)) {
					$theOptName = addslashes(trim(str_replace($OptMatches[0][0], '', $txtData)));

					switch (trim($OptMatches[0][0])) {
					case $lang['txt_row']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 1;
						break;

					case $lang['txt_col']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionLabel=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 2;
						break;

					case $lang['txt_cell']:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . $theOptName . '\' ';
						$DB->query($SQL);
						$theSubType = 3;
						break;
					}
				}
				else {
					switch ($theSubType) {
					case 1:
					default:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_OPTION_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;

					case 2:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_LABEL_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionLabel=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;

					case 3:
						$SQL = ' INSERT INTO ' . QUESTION_RANGE_ANSWER_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionAnswer=\'' . addslashes(trim($txtData)) . '\' ';
						$DB->query($SQL);
						break;
					}
				}

				break;

			case '10':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_other_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_other_2'])) {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\',otherText=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' WHERE questionID=\'' . $newQuestionID . '\' ';
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
				}

				$DB->query($SQL);
				$i++;
				break;

			case '15':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_other_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_other_2'])) {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isCheckType=\'1\' WHERE questionID=\'' . $newQuestionID . '\' ';
					$DB->query($SQL);
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' ';
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
				}

				$DB->query($SQL);
				$i++;
				break;

			case '16':
				if ((cnsubstr($txtData, 0, 3) == $lang['txt_other_1']) || (cnsubstr($txtData, 0, 3) == $lang['txt_other_2'])) {
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isHaveOther=\'1\' WHERE questionID=\'' . $newQuestionID . '\' ';
					$DB->query($SQL);
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(cnsubstr(trim($txtData), 4)) . '\' ';
				}
				else {
					$SQL = ' INSERT INTO ' . QUESTION_RANK_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
				}

				$DB->query($SQL);
				$i++;
				break;

			case '9':
				$SQL = ' INSERT INTO ' . QUESTION_INFO_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $newQuestionID . '\',optionName=\'' . addslashes(trim($txtData)) . '\' ';
				$DB->query($SQL);
				$i++;
				break;

			case '1':
			case '4':
			case '5':
			case '8':
			case '11':
			case '14':
			case '30':
				break;
			}
		}
	}
}

fclose($File);

if (file_exists($newFullName)) {
	@unlink($newFullName);
}

if (isset($_POST['theBaseId']) && ($_POST['theBaseId'] != '') && ($_POST['theBaseId'] != 'afterOfAll')) {
	$theInsertAfterQtn = explode('*', $_POST['theBaseId']);
	$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND orderByID > \'' . $theInsertAfterQtn[1] . '\' AND questionID !=\'' . $theInsertAfterQtn[0] . '\' ORDER BY orderByID ASC LIMIT 0,1';
	$nRow = $DB->queryFirstRow($SQL);

	if ($nRow) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+' . $theQtnCount . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND orderByID >=\'' . $nRow['orderByID'] . '\' ';
		$DB->query($SQL);
		$tmp = 0;

		foreach ($theImportQtnArray as $theNewQuestionID) {
			$theNewOrderById = $nRow['orderByID'] + $tmp;
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID = \'' . $theNewOrderById . '\' WHERE questionID =\'' . $theNewQuestionID . '\' ';
			$DB->query($SQL);
			$tmp++;
		}
	}
}

unset($theImportQtnArray);
writetolog($lang['new_import'] . $recNum . $lang['question_num']);
_showmessage($lang['new_import'] . $recNum . $lang['question_num'], true);

?>
