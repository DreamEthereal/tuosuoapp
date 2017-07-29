<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';

if ($_POST['Action'] == 'LogicImportSubmit') {
	$File_DIR_Name = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if (!is_dir($File_DIR_Name)) {
		mkdir($File_DIR_Name, 511);
	}

	$tmpExt = explode('.', $_FILES['csvFile']['name']);
	$tmpNum = count($tmpExt) - 1;
	$extension = strtolower($tmpExt[$tmpNum]);
	$newFileName = 'CSV_' . date('YmdHis', time()) . rand(1, 999) . '.csv';
	$newFullName = $File_DIR_Name . $newFileName;
	if (is_uploaded_file($_FILES['csvFile']['tmp_name']) && ($extension == 'csv')) {
		copy($_FILES['csvFile']['tmp_name'], $newFullName);
	}
	else {
		_showerror($lang['error_system'], $lang['csv_file_type_error']);
	}

	$the_allow_qtn_type = array('1', '2', '3', '6', '7', '24', '25', '30', '19', '28', '17', '4', '23', '10', '15', '16', '20', '21', '22', '31');
	$the_ass_qtn_type = array('2', '3', '6', '7', '19', '24', '25', '28');
	setlocale(LC_ALL, 'zh_CN.GBK');
	$File = fopen($newFullName, 'r');
	$recNum = 0;
	$csvLineNum = 0;

	while ($csvData = _fgetcsv($File)) {
		if ($csvLineNum != 0) {
			$csvData = qaddslashes($csvData, 1);
			$the_question_id = trim($csvData[0]);
			$the_ass_id = trim($csvData[1]);
			$the_base_id = trim($csvData[2]);
			$the_qtn_id = trim($csvData[3]);
			$the_opt_id = trim($csvData[4]);
			$the_option_id = trim($csvData[5]);
			$the_logic_and = trim($csvData[6]);
			$the_logic_mode = trim($csvData[7]);
			if (($the_question_id == '') || ($the_ass_id == '') || ($the_base_id == '') || ($the_option_id == '')) {
				continue;
			}

			$bSQL = ' SELECT questionID,questionType,orderByID,isHaveOther,isNeg,baseID,isCheckType,isSelect,maxSize FROM ' . QUESTION_TABLE . ' WHERE (questionID = \'' . $the_base_id . '\' OR alias = \'' . $the_base_id . '\') AND surveyID=\'' . $_POST['surveyID'] . '\' LIMIT 1 ';
			$bRow = $DB->queryFirstRow($bSQL);

			if (!$bRow) {
				continue;
			}

			if (!in_array($bRow['questionType'], $the_allow_qtn_type)) {
				continue;
			}

			$this_base_id = $bRow['questionID'];
			$hSQL = ' SELECT questionID,questionType,orderByID FROM ' . QUESTION_TABLE . ' WHERE (questionID = \'' . $the_question_id . '\' OR alias = \'' . $the_question_id . '\') AND surveyID=\'' . $_POST['surveyID'] . '\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if (!$hRow) {
				continue;
			}

			if ($hRow['orderByID'] <= $bRow['orderByID']) {
				continue;
			}

			if (!in_array($hRow['questionType'], $the_ass_qtn_type)) {
				continue;
			}

			$this_question_id = $hRow['questionID'];
			$the_ass_option_id_array = array();

			switch ($hRow['questionType']) {
			case '2':
			case '24':
				$RadioSQL = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $this_question_id . '\' ORDER BY optionOptionID ASC ';
				$RadioResult = $DB->query($RadioSQL);
				$tmp = 1;

				while ($RadioRow = $DB->queryArray($RadioResult)) {
					$the_ass_option_id_array[$tmp] = $RadioRow['question_radioID'];
					$tmp++;
				}

				break;

			case '3':
			case '25':
				$CheckBoxSQL = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $this_question_id . '\' ORDER BY optionOptionID ASC ';
				$CheckBoxResult = $DB->query($CheckBoxSQL);
				$tmp = 1;

				while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
					$the_ass_option_id_array[$tmp] = $CheckBoxRow['question_checkboxID'];
					$tmp++;
				}

				break;

			case '6':
			case '7':
			case '19':
			case '28':
				$AnswerSQL = ' SELECT question_range_answerID FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $this_question_id . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);
				$tmp = 1;

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$the_ass_option_id_array[$tmp] = $AnswerRow['question_range_answerID'];
					$tmp++;
				}

				break;
			}

			$the_ass_id_array = explode(',', $the_ass_id);
			$this_ass_id = array();

			foreach ($the_ass_id_array as $ass_id) {
				if (array_key_exists($ass_id, $the_ass_option_id_array)) {
					$this_ass_id[] = $the_ass_option_id_array[$ass_id];
				}
			}

			unset($the_ass_id_array);
			unset($the_ass_option_id_array);
			$the_base_qtn_id_array = array();
			$the_base_option_id_array = array();

			switch ($bRow['questionType']) {
			case '1':
				$YesNoSQL = ' SELECT question_yesnoID FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY question_yesnoID ASC ';
				$YesNoResult = $DB->query($YesNoSQL);
				$tmp = 1;

				while ($YesNoRow = $DB->queryArray($YesNoResult)) {
					$the_base_option_id_array[$tmp] = $YesNoRow['question_yesnoID'];
					$tmp++;
				}

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$the_option_id_array = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($the_option_id_array as $option_id) {
					if (array_key_exists($option_id, $the_base_option_id_array)) {
						$this_option_id[] = $the_base_option_id_array[$option_id];
					}
				}

				unset($the_option_id_array);
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_option_id as $optionID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '2':
			case '24':
				if (($bRow['questionType'] == '2') && ($bRow['isHaveOther'] == '1')) {
					$the_base_option_id_array[0] = 0;
					$the_base_option_id_array[99] = 0;
				}

				$RadioSQL = ' SELECT question_radioID FROM ' . QUESTION_RADIO_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY optionOptionID ASC ';
				$RadioResult = $DB->query($RadioSQL);
				$tmp = 1;

				while ($RadioRow = $DB->queryArray($RadioResult)) {
					$the_base_option_id_array[$tmp] = $RadioRow['question_radioID'];
					$tmp++;
				}

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$the_option_id_array = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($the_option_id_array as $option_id) {
					if (array_key_exists($option_id, $the_base_option_id_array)) {
						$this_option_id[] = $the_base_option_id_array[$option_id];
					}
				}

				unset($the_option_id_array);
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_option_id as $optionID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '3':
			case '25':
				switch ($the_logic_mode) {
				case '2':
					switch ($the_opt_id) {
					case '=':
					case '==':
					default:
						$this_opt_id = 1;
						break;

					case '<':
						$this_opt_id = 2;
						break;

					case '<=':
						$this_opt_id = 3;
						break;

					case '>':
						$this_opt_id = 4;
						break;

					case '>=':
						$this_opt_id = 5;
						break;

					case '!=':
						$this_opt_id = 6;
						break;
					}

					$this_option_id = (int) $the_option_id;
					$this_logic_and = 0;

					foreach ($this_ass_id as $assID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $this_option_id . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\',logicMode=2 ';
							$DB->query($SQL);
							$recNum++;
						}
					}

					break;

				default:
					if ($bRow['questionType'] == '3') {
						if ($bRow['isHaveOther'] == '1') {
							$the_base_option_id_array[0] = 0;
							$the_base_option_id_array[99] = 0;
						}

						if ($bRow['isNeg'] == '1') {
							$the_base_option_id_array[99999] = 99999;
						}
					}

					$CheckBoxSQL = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY optionOptionID ASC ';
					$CheckBoxResult = $DB->query($CheckBoxSQL);
					$tmp = 1;

					while ($CheckBoxRow = $DB->queryArray($CheckBoxResult)) {
						$the_base_option_id_array[$tmp] = $CheckBoxRow['question_checkboxID'];
						$tmp++;
					}

					switch ($the_opt_id) {
					case '=':
					case '==':
					default:
						$this_opt_id = 1;
						break;

					case '!=':
						$this_opt_id = 2;
						break;
					}

					$the_option_id_array = explode(',', $the_option_id);
					$this_option_id = array();

					foreach ($the_option_id_array as $option_id) {
						if (array_key_exists($option_id, $the_base_option_id_array)) {
							$this_option_id[] = $the_base_option_id_array[$option_id];
						}
					}

					unset($the_option_id_array);

					switch ($the_logic_and) {
					case '1':
						$this_logic_and = 1;
						break;

					default:
						$this_logic_and = 0;
						break;
					}

					foreach ($this_ass_id as $assID) {
						foreach ($this_option_id as $optionID) {
							$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\',logicMode=1 ';
								$DB->query($SQL);
								$recNum++;
							}
						}
					}

					break;
				}

				break;

			case '6':
			case '7':
				$OptionSQL = ' SELECT question_range_optionID FROM ' . QUESTION_RANGE_OPTION_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY question_range_optionID ASC ';
				$OptionResult = $DB->query($OptionSQL);
				$tmp = 1;

				while ($OptionRow = $DB->queryArray($OptionResult)) {
					$the_base_qtn_id_array[$tmp] = $OptionRow['question_range_optionID'];
					$tmp++;
				}

				$the_qtn_id_array = explode(',', $the_qtn_id);
				$this_qtn_id = array();

				foreach ($the_qtn_id_array as $qtn_id) {
					if (array_key_exists($qtn_id, $the_base_qtn_id_array)) {
						$this_qtn_id[] = $the_base_qtn_id_array[$qtn_id];
					}
				}

				unset($the_qtn_id_array);

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$AnswerSQL = ' SELECT question_range_answerID FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);
				$tmp = 1;

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$the_base_option_id_array[$tmp] = $AnswerRow['question_range_answerID'];
					$tmp++;
				}

				$the_option_id_array = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($the_option_id_array as $option_id) {
					if (array_key_exists($option_id, $the_base_option_id_array)) {
						$this_option_id[] = $the_base_option_id_array[$option_id];
					}
				}

				unset($the_option_id_array);

				if ($bRow['questionType'] == '7') {
					switch ($the_logic_and) {
					case '1':
						$this_logic_and = 1;
						break;

					default:
						$this_logic_and = 0;
						break;
					}
				}
				else {
					$this_logic_and = 0;
				}

				foreach ($this_ass_id as $assID) {
					foreach ($this_qtn_id as $qtnID) {
						foreach ($this_option_id as $optionID) {
							$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $qtnID . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $qtnID . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
								$DB->query($SQL);
								$recNum++;
							}
						}
					}
				}

				break;

			case '30':
				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				switch ($the_option_id) {
				case '1':
				default:
					$this_option_id = 1;
					break;

				case '0':
					$this_option_id = 2;
					break;
				}

				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND assType=2 LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
						$DB->query($SQL);
						$recNum++;
					}
				}

				break;

			case '31':
				$the_qtn_id = (int) $the_qtn_id;
				if (($the_qtn_id <= 0) || ($bRow['maxSize'] < $the_qtn_id)) {
					continue;
				}

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$theQueryValue = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($theQueryValue as $theValue) {
					$theValue = trim($theValue);
					$hSQL = ' SELECT nodeID FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $the_base_id . '\' AND nodeID = \'' . $theValue . '\' AND level = \'' . $the_qtn_id . '\' AND flag = 0 LIMIT 1 ';
					$hRow = $DB->queryFirstRow($hSQL);

					if ($hRow) {
						$this_option_id[] = $theValue;
					}
				}

				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_option_id as $optionID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $the_qtn_id . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $the_qtn_id . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '19':
			case '28':
				$qSQL = ' SELECT isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $bRow['baseID'] . '\' ';
				$qRow = $DB->queryFirstRow($qSQL);

				if ($qRow['isHaveOther'] == '1') {
					$the_base_qtn_id_array[0] = 0;
					$the_base_qtn_id_array[99] = 0;
				}

				$RangeSQL = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $bRow['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$RangeResult = $DB->query($RangeSQL);
				$tmp = 1;

				while ($RangeRow = $DB->queryArray($RangeResult)) {
					$the_base_qtn_id_array[$tmp] = $RangeRow['question_checkboxID'];
					$tmp++;
				}

				$the_qtn_id_array = explode(',', $the_qtn_id);
				$this_qtn_id = array();

				foreach ($the_qtn_id_array as $qtn_id) {
					if (array_key_exists($qtn_id, $the_base_qtn_id_array)) {
						$this_qtn_id[] = $the_base_qtn_id_array[$qtn_id];
					}
				}

				unset($the_qtn_id_array);

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$AnswerSQL = ' SELECT question_range_answerID FROM ' . QUESTION_RANGE_ANSWER_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY question_range_answerID ASC ';
				$AnswerResult = $DB->query($AnswerSQL);
				$tmp = 1;

				while ($AnswerRow = $DB->queryArray($AnswerResult)) {
					$the_base_option_id_array[$tmp] = $AnswerRow['question_range_answerID'];
					$tmp++;
				}

				$the_option_id_array = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($the_option_id_array as $option_id) {
					if (array_key_exists($option_id, $the_base_option_id_array)) {
						$this_option_id[] = $the_base_option_id_array[$option_id];
					}
				}

				unset($the_option_id_array);

				if ($bRow['questionType'] == '28') {
					switch ($the_logic_and) {
					case '1':
						$this_logic_and = 1;
						break;

					default:
						$this_logic_and = 0;
						break;
					}
				}
				else {
					$this_logic_and = 0;
				}

				foreach ($this_ass_id as $assID) {
					foreach ($this_qtn_id as $qtnID) {
						foreach ($this_option_id as $optionID) {
							$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $qtnID . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $qtnID . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
								$DB->query($SQL);
								$recNum++;
							}
						}
					}
				}

				break;

			case '17':
				$qSQL = ' SELECT isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $bRow['baseID'] . '\' ';
				$qRow = $DB->queryFirstRow($qSQL);

				if ($qRow['isHaveOther'] == '1') {
					$the_base_option_id_array[0] = 0;
					$the_base_option_id_array[99] = 0;
				}

				if ($bRow['isCheckType'] == '1') {
					$the_base_option_id_array[99999] = 99999;
				}

				$RangeSQL = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $bRow['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$RangeResult = $DB->query($RangeSQL);
				$tmp = 1;

				while ($RangeRow = $DB->queryArray($RangeResult)) {
					$the_base_option_id_array[$tmp] = $RangeRow['question_checkboxID'];
					$tmp++;
				}

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '!=':
					$this_opt_id = 2;
					break;
				}

				$the_option_id_array = explode(',', $the_option_id);
				$this_option_id = array();

				foreach ($the_option_id_array as $option_id) {
					if (array_key_exists($option_id, $the_base_option_id_array)) {
						$this_option_id[] = $the_base_option_id_array[$option_id];
					}
				}

				unset($the_option_id_array);

				if ($bRow['isSelect'] == '0') {
					switch ($the_logic_and) {
					case '1':
						$this_logic_and = 1;
						break;

					default:
						$this_logic_and = 0;
						break;
					}
				}
				else {
					$this_logic_and = 0;
				}

				foreach ($this_ass_id as $assID) {
					foreach ($this_option_id as $optionID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $optionID . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $optionID . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '4':
				if ($bRow['isCheckType'] != '4') {
					continue;
				}

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '<':
					$this_opt_id = 2;
					break;

				case '<=':
					$this_opt_id = 3;
					break;

				case '>':
					$this_opt_id = 4;
					break;

				case '>=':
					$this_opt_id = 5;
					break;

				case '!=':
					$this_opt_id = 6;
					break;
				}

				$this_option_id = (int) $the_option_id;
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condOptionID=\'' . $this_option_id . '\' AND assType=2 LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
						$DB->query($SQL);
						$recNum++;
					}
				}

				break;

			case '23':
				$YesNoSQL = ' SELECT question_yesnoID,isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY optionOptionID ASC ';
				$YesNoResult = $DB->query($YesNoSQL);
				$tmp = 1;

				while ($YesNoRow = $DB->queryArray($YesNoResult)) {
					if ($YesNoRow['isCheckType'] == '4') {
						$the_base_qtn_id_array[$tmp] = $YesNoRow['question_yesnoID'];
					}

					$tmp++;
				}

				$the_qtn_id_array = explode(',', $the_qtn_id);
				$this_qtn_id = array();

				foreach ($the_qtn_id_array as $qtn_id) {
					if (array_key_exists($qtn_id, $the_base_qtn_id_array)) {
						$this_qtn_id[] = $the_base_qtn_id_array[$qtn_id];
					}
				}

				unset($the_qtn_id_array);

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '<':
					$this_opt_id = 2;
					break;

				case '<=':
					$this_opt_id = 3;
					break;

				case '>':
					$this_opt_id = 4;
					break;

				case '>=':
					$this_opt_id = 5;
					break;

				case '!=':
					$this_opt_id = 6;
					break;
				}

				$this_option_id = (int) $the_option_id;
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_qtn_id as $qtnID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $qtnID . '\' AND condOptionID=\'' . $this_option_id . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $qtnID . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '10':
			case '15':
			case '16':
				if (($bRow['questionType'] == '10') && ($bRow['isHaveOther'] == '1')) {
					$the_base_qtn_id_array[0] = 0;
					$the_base_qtn_id_array[99] = 0;
				}

				$RankSQL = ' SELECT question_rankID FROM ' . QUESTION_RANK_TABLE . ' WHERE questionID =\'' . $this_base_id . '\' ORDER BY question_rankID ASC ';
				$RankResult = $DB->query($RankSQL);
				$tmp = 1;

				while ($RankRow = $DB->queryArray($RankResult)) {
					$the_base_qtn_id_array[$tmp] = $RankRow['question_rankID'];
					$tmp++;
				}

				$the_qtn_id_array = explode(',', $the_qtn_id);
				$this_qtn_id = array();

				foreach ($the_qtn_id_array as $qtn_id) {
					if (array_key_exists($qtn_id, $the_base_qtn_id_array)) {
						$this_qtn_id[] = $the_base_qtn_id_array[$qtn_id];
					}
				}

				unset($the_qtn_id_array);

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '<':
					$this_opt_id = 2;
					break;

				case '<=':
					$this_opt_id = 3;
					break;

				case '>':
					$this_opt_id = 4;
					break;

				case '>=':
					$this_opt_id = 5;
					break;

				case '!=':
					$this_opt_id = 6;
					break;
				}

				$this_option_id = (int) $the_option_id;
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_qtn_id as $qtnID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $qtnID . '\' AND condOptionID=\'' . $this_option_id . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $qtnID . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;

			case '20':
			case '21':
			case '22':
				$qSQL = ' SELECT isHaveOther FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $bRow['baseID'] . '\' ';
				$qRow = $DB->queryFirstRow($qSQL);

				if ($qRow['isHaveOther'] == '1') {
					$the_base_qtn_id_array[0] = 0;
					$the_base_qtn_id_array[99] = 0;
				}

				$RangeSQL = ' SELECT question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID =\'' . $bRow['baseID'] . '\' ORDER BY optionOptionID ASC ';
				$RangeResult = $DB->query($RangeSQL);
				$tmp = 1;

				while ($RangeRow = $DB->queryArray($RangeResult)) {
					$the_base_qtn_id_array[$tmp] = $RangeRow['question_checkboxID'];
					$tmp++;
				}

				$the_qtn_id_array = explode(',', $the_qtn_id);
				$this_qtn_id = array();

				foreach ($the_qtn_id_array as $qtn_id) {
					if (array_key_exists($qtn_id, $the_base_qtn_id_array)) {
						$this_qtn_id[] = $the_base_qtn_id_array[$qtn_id];
					}
				}

				unset($the_qtn_id_array);

				switch ($the_opt_id) {
				case '=':
				case '==':
				default:
					$this_opt_id = 1;
					break;

				case '<':
					$this_opt_id = 2;
					break;

				case '<=':
					$this_opt_id = 3;
					break;

				case '>':
					$this_opt_id = 4;
					break;

				case '>=':
					$this_opt_id = 5;
					break;

				case '!=':
					$this_opt_id = 6;
					break;
				}

				$this_option_id = (int) $the_option_id;
				$this_logic_and = 0;

				foreach ($this_ass_id as $assID) {
					foreach ($this_qtn_id as $qtnID) {
						$SQL = ' SELECT associateID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $this_question_id . '\' AND optionID = \'' . $assID . '\' AND condOnID=\'' . $this_base_id . '\' AND condQtnID=\'' . $qtnID . '\' AND condOptionID=\'' . $this_option_id . '\' AND assType=2 LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . ASSOCIATE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $this_question_id . '\',optionID = \'' . $assID . '\',condOnID=\'' . $this_base_id . '\',condQtnID=\'' . $qtnID . '\',condOptionID=\'' . $this_option_id . '\',opertion=\'' . $this_opt_id . '\',assType=2,administratorsID=\'' . $_SESSION['administratorsID'] . '\',logicValueIsAnd = \'' . $this_logic_and . '\' ';
							$DB->query($SQL);
							$recNum++;
						}
					}
				}

				break;
			}
		}

		$csvLineNum++;
	}

	fclose($File);

	if (file_exists($newFullName)) {
		@unlink($newFullName);
	}

	writetolog('导入选项关联:' . $Sur_G_Row['surveyTitle'] . ':' . $lang['new_import'] . $recNum . $lang['import_num']);
	_showmessage('导入选项关联:' . $Sur_G_Row['surveyTitle'] . ':' . $lang['new_import'] . $recNum . $lang['import_num'], true);
}

if ($_GET['DO'] == 'ImportOptAss') {
	$EnableQCoreClass->setTemplateFile('LogicImportFile', 'SurveyLogicImport.html');
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$EnableQCoreClass->replace('actionType', '选项关联关系');
	$EnableQCoreClass->replace('actionName', 'LogicImportSubmit');
	$EnableQCoreClass->replace('helpFileName', str_replace('+', '%2B', base64_encode('CSV_Sample_OptAss.csv')));
	$EnableQCoreClass->parse('LogicImport', 'LogicImportFile');
	$EnableQCoreClass->output('LogicImport');
}

?>
