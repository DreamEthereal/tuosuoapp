<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require ROOT_PATH . 'Config/DBSizeConfig.inc.php';
$SQL = ' DROP TABLE IF EXISTS ' . $table_prefix . 'response_' . $theDeploySurveyID . ' ';
$DB->query($SQL);
$SizeSQL = ' SELECT dbSize,isRecord FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' ';
$SizeRow = $DB->queryFirstRow($SizeSQL);

if ($SizeRow['isRecord'] == 2) {
	$hSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' AND questionType = 11 LIMIT 1 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow) {
		_showerror('一致性检查错误', '一致性检查错误：全程录像模式下问卷内存在文件上传题型！');
	}
}

$hSQL = ' SELECT b.questionName FROM ' . QUESTION_CHECKBOX_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.question_checkboxID = \'99999\' AND b.surveyID =\'' . $theDeploySurveyID . '\' AND a.questionID = b.questionID AND b.isNeg = 1 LIMIT 1 ';
$hRow = $DB->queryFirstRow($hSQL);

if ($hRow) {
	$errMsg = $lang['changestatus_survey'] . ':' . $lang['checkbox_error_99999'] . ':' . qshowmessajax(stripslashes($hRow['questionName']), 1);

	if ($isAjaxActionFlag == 1) {
		exit($errMsg);
	}
	else {
		_showerror($lang['changestatus_survey'], $errMsg);
	}
}

$theSID = $theDeploySurveyID;
require ROOT_PATH . 'Includes/MakeCache.php';
require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
$lSQL = ' SELECT DISTINCT questionID,condOnID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' AND quotaID = 0 ORDER BY conditionsID ASC ';
$lResult = $DB->query($lSQL);

while ($lRow = $DB->queryArray($lResult)) {
	if ($QtnListArray[$lRow['questionID']]['orderByID'] < $QtnListArray[$lRow['condOnID']]['orderByID']) {
		$errMsg = '一致性检查错误：问卷内逻辑关系的问题关联表达式依赖的问题(' . $lRow['condOnID'] . ')不能在运算问题(' . $lRow['questionID'] . ')之后';

		if ($isAjaxActionFlag == 1) {
			exit($errMsg);
		}
		else {
			_showerror('一致性检查错误', $errMsg);
		}
	}
}

$lSQL = ' SELECT DISTINCT questionID,condOnID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' ORDER BY associateID ASC ';
$lResult = $DB->query($lSQL);

while ($lRow = $DB->queryArray($lResult)) {
	if ($QtnListArray[$lRow['questionID']]['orderByID'] < $QtnListArray[$lRow['condOnID']]['orderByID']) {
		$errMsg = '一致性检查错误：问卷内逻辑关系的选项关联或矩阵行关联表达式依赖的问题(' . $lRow['condOnID'] . ')不能在运算问题(' . $lRow['questionID'] . ')之后';

		if ($isAjaxActionFlag == 1) {
			exit($errMsg);
		}
		else {
			_showerror('一致性检查错误', $errMsg);
		}
	}
}

$lSQL = ' SELECT relationID,relationMode,questionID,relationDefine FROM ' . RELATION_TABLE . ' WHERE relationDefine = 2 ORDER BY relationID ASC ';
$lResult = $DB->query($lSQL);

while ($lRow = $DB->queryArray($lResult)) {
	switch ($lRow['relationDefine']) {
	case 2:
		$tSQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE weight = \'' . $lRow['relationID'] . '\' ';
		$tRow = $DB->queryFirstRow($tSQL);
		$theBaseEmptyLogicId = array();
		$hSQL = ' SELECT DISTINCT questionID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' AND condOnID = \'' . $tRow['questionID'] . '\' AND quotaID = 0 ORDER BY conditionsID ASC ';
		$hResult = $DB->query($hSQL);

		while ($hRow = $DB->queryArray($hResult)) {
			if (!in_array($hRow['questionID'], $theBaseEmptyLogicId)) {
				$theBaseEmptyLogicId[] = $hRow['questionID'];
			}
		}

		$hSQL = ' SELECT DISTINCT questionID FROM ' . ASSOCIATE_TABLE . ' WHERE surveyID = \'' . $theDeploySurveyID . '\' AND condOnID = \'' . $tRow['questionID'] . '\'  ORDER BY associateID ASC ';
		$hResult = $DB->query($hSQL);

		while ($hRow = $DB->queryArray($hResult)) {
			if (!in_array($hRow['questionID'], $theBaseEmptyLogicId)) {
				$theBaseEmptyLogicId[] = $hRow['questionID'];
			}
		}

		if ($lRow['relationMode'] == 2) {
			if ($QtnListArray[$lRow['questionID']]['orderByID'] < $QtnListArray[$tRow['questionID']]['orderByID']) {
				$errMsg = '一致性检查错误：问卷内数值关联表达式(' . $lRow['relationID'] . ')运算到的空题(' . $tRow['questionID'] . ')不能在运算结果问题(' . $lRow['questionID'] . ')之后';

				if ($isAjaxActionFlag == 1) {
					exit($errMsg);
				}
				else {
					_showerror('一致性检查错误', $errMsg);
				}
			}

			$oSQL = ' SELECT DISTINCT questionID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $lRow['relationID'] . '\' ORDER BY listID ASC ';
			$oResult = $DB->query($oSQL);

			while ($oRow = $DB->queryArray($oResult)) {
				if ($QtnListArray[$lRow['questionID']]['orderByID'] < $QtnListArray[$oRow['questionID']]['orderByID']) {
					$errMsg = '一致性检查错误：问卷内数值关联表达式(' . $lRow['relationID'] . ')运算的条件问题(' . $oRow['questionID'] . ')不能在运算结果问题(' . $lRow['questionID'] . ')之后';

					if ($isAjaxActionFlag == 1) {
						exit($errMsg);
					}
					else {
						_showerror('一致性检查错误', $errMsg);
					}
				}
			}

			foreach ($theBaseEmptyLogicId as $theQtnId) {
				if ($QtnListArray[$theQtnId]['orderByID'] < $QtnListArray[$lRow['questionID']]['orderByID']) {
					$errMsg = '一致性检查错误：基于空题创建逻辑关系的问题(' . $theQtnId . ')不能在运算到该空题(' . $tRow['questionID'] . ')的数值关联表达式(' . $lRow['relationID'] . ')的运算结果问题(' . $lRow['questionID'] . ')之前';

					if ($isAjaxActionFlag == 1) {
						exit($errMsg);
					}
					else {
						_showerror('一致性检查错误', $errMsg);
					}
				}
			}
		}
		else {
			$oSQL = ' SELECT DISTINCT questionID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $lRow['relationID'] . '\' ORDER BY listID ASC ';
			$oResult = $DB->query($oSQL);
			$oOrderByIdArray = array();

			while ($oRow = $DB->queryArray($oResult)) {
				$oOrderByIdArray[] = $QtnListArray[$oRow['questionID']]['orderByID'];
			}

			$theLastQtnOrder = max($oOrderByIdArray);

			if ($theLastQtnOrder < $QtnListArray[$tRow['questionID']]['orderByID']) {
				$errMsg = '一致性检查错误：问卷内数值关联表达式(' . $lRow['relationID'] . ')运算到的空题(' . $tRow['questionID'] . ')不能在所有的运算条件问题之后';

				if ($isAjaxActionFlag == 1) {
					exit($errMsg);
				}
				else {
					_showerror('一致性检查错误', $errMsg);
				}
			}

			foreach ($theBaseEmptyLogicId as $theQtnId) {
				if ($QtnListArray[$theQtnId]['orderByID'] < $theLastQtnOrder) {
					$errMsg = '一致性检查错误：基于空题创建逻辑关系的问题(' . $theQtnId . ')不能在运算到该空题(' . $tRow['questionID'] . ')的数值关联表达式(' . $lRow['relationID'] . ')的所有运算条件问题之前';

					if ($isAjaxActionFlag == 1) {
						exit($errMsg);
					}
					else {
						_showerror('一致性检查错误', $errMsg);
					}
				}
			}
		}

		break;

	default:
		if ($lRow['relationMode'] == 2) {
			$oSQL = ' SELECT DISTINCT questionID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $lRow['relationID'] . '\' ORDER BY listID ASC ';
			$oResult = $DB->query($oSQL);

			while ($oRow = $DB->queryArray($oResult)) {
				if ($QtnListArray[$lRow['questionID']]['orderByID'] < $QtnListArray[$oRow['questionID']]['orderByID']) {
					$errMsg = '一致性检查错误：问卷内数值关联表达式(' . $lRow['relationID'] . ')运算的条件问题(' . $oRow['questionID'] . ')不能在运算结果问题(' . $lRow['questionID'] . ')之后';

					if ($isAjaxActionFlag == 1) {
						exit($errMsg);
					}
					else {
						_showerror('一致性检查错误', $errMsg);
					}
				}
			}
		}

		break;
	}
}

$this_fields_list = '';
$this_fileds_type = '';
$this_index_fields = '';
$recordCount = count($QtnListArray);
if (($recordCount == 0) || (count($InfoListArray) == $recordCount)) {
	if ($isAjaxActionFlag == 1) {
		exit($lang['changestatus_survey'] . ':' . $lang['no_question_now']);
	}
	else {
		_showerror($lang['changestatus_survey'], $lang['changestatus_survey'] . ':' . $lang['no_question_now']);
	}
}

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if ($theQtnArray['questionType'] != '9') {
		$surveyID = $theDeploySurveyID;
		$ModuleName = $Module[$theQtnArray['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
	}
}

$this_fields_list = substr($this_fields_list, 0, -1);
$survey_fields_name = explode('|', $this_fields_list);
$this_fileds_type = substr($this_fileds_type, 0, -1);
$survey_fields_type = explode('|', $this_fileds_type);
$SQL = 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . 'response_' . $theDeploySurveyID . '( ';
$SQL .= ' responseID int(30) unsigned NOT NULL auto_increment,';
$SQL .= ' recordFile varchar(60) BINARY NOT NULL default \'\',';
$SQL .= ' fingerFile varchar(60) BINARY NOT NULL default \'\',';
$SQL .= ' administratorsName varchar(255) BINARY NOT NULL default \'\',';
$SQL .= ' administratorsGroupID int(4) unsigned NOT NULL default \'0\',';
$SQL .= ' ajaxRtnValue_1 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ajaxRtnValue_2 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ajaxRtnValue_3 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ajaxRtnValue_4 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ajaxRtnValue_5 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ajaxRtnValue_6 varchar(120) BINARY NOT NULL default \'\',';
$SQL .= ' ipAddress varchar(255) BINARY NOT NULL default \'\',';
$SQL .= ' area varchar(50) BINARY NOT NULL default \'\',';
$SQL .= ' joinTime int(11) unsigned NOT NULL default \'0\',';
$SQL .= ' submitTime int(11) unsigned NOT NULL default \'0\',';
$SQL .= ' uploadTime int(11) unsigned NOT NULL default \'0\',';
$SQL .= ' overTime int(4) unsigned NOT NULL default \'0\',';
$SQL .= ' cateID int(4) unsigned NOT NULL default \'0\',';
$SQL .= ' taskID int(30) unsigned NOT NULL default \'0\',';
$SQL .= ' overFlag int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' overFlag0 int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' authStat int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' version int(20) unsigned NOT NULL default \'0\',';
$SQL .= ' adminID int(20) unsigned NOT NULL default \'0\',';
$SQL .= ' appStat int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' isReAuth int(1) unsigned NOT NULL default \'1\',';
$SQL .= ' dataSource int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' replyPage int(1) unsigned NOT NULL default \'0\',';
$SQL .= ' uniDataCode varchar(255) BINARY NOT NULL default \'\',';
$SQL .= ' uniCode varchar(20) BINARY NOT NULL default \'\',';
$varCharLength = 1675;
$textLength = 0;
$intLength = 17;
$colNum = 26 + count($survey_fields_name);

if (trim($SizeRow['dbSize']) == '') {
	$i = 0;

	for (; $i < count($survey_fields_name); $i++) {
		switch (strtolower($survey_fields_type[$i])) {
		case 'int':
			$SQL .= ' ' . $survey_fields_name[$i] . ' int(4) unsigned NOT NULL default \'0\',';
			$intLength++;
			break;

		case 'float':
			$SQL .= ' ' . $survey_fields_name[$i] . ' float(7,2) unsigned NOT NULL default \'0.00\',';
			$intLength++;
			break;

		case 'text':
			$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
			$textLength++;
			break;

		case 'multichar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['multichar'] . ' NOT NULL default \'\',';
			$varCharLength += 120;
			break;

		case 'otherchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['otherchar'] . ' NOT NULL default \'\',';
			$varCharLength += 180;
			break;

		case 'optionchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['optionchar'] . ' NOT NULL default \'\',';
			$varCharLength += 140;
			break;

		case 'whychar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['whychar'] . ' NOT NULL default \'\',';
			$varCharLength += 180;
			break;

		case 'hiddenchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['hiddenchar'] . ' NOT NULL default \'\',';
			$varCharLength += 120;
			break;

		case 'dsnchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['dsnchar'] . ' NOT NULL default \'\',';
			$varCharLength += 255;
			break;

		case 'mtchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['mtchar'] . ' NOT NULL default \'\',';
			$varCharLength += 100;
			break;

		case 'char':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['char'] . ' NOT NULL default \'\',';
			$varCharLength += 255;
			break;

		case 'varchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['varchar'] . ' NOT NULL default \'\',';
			$varCharLength += 200;
			break;

		case 'listchar':
			$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['listchar'] . ' NOT NULL default \'\',';
			$varCharLength += 120;
			break;
		}
	}
}
else {
	$theDbSize = explode(',', $SizeRow['dbSize']);
	$i = 0;

	for (; $i < count($survey_fields_name); $i++) {
		switch (strtolower($survey_fields_type[$i])) {
		case 'int':
			$SQL .= ' ' . $survey_fields_name[$i] . ' int(4) unsigned NOT NULL default \'0\',';
			$intLength++;
			break;

		case 'float':
			$SQL .= ' ' . $survey_fields_name[$i] . ' float(7,2) unsigned NOT NULL default \'0.00\',';
			$intLength++;
			break;

		case 'text':
			$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
			$textLength++;
			break;

		case 'multichar':
			if ($theDbSize[6] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[6] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[6] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[6];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['multichar'] . ' NOT NULL default \'\',';
				$varCharLength += 120;
			}

			break;

		case 'otherchar':
			if ($theDbSize[0] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[0] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[0] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[0];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['otherchar'] . ' NOT NULL default \'\',';
				$varCharLength += 180;
			}

			break;

		case 'optionchar':
			if ($theDbSize[1] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[1] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[1] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[1];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['optionchar'] . ' NOT NULL default \'\',';
				$varCharLength += 140;
			}

			break;

		case 'whychar':
			if ($theDbSize[2] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[2] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[2] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[2];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['whychar'] . ' NOT NULL default \'\',';
				$varCharLength += 180;
			}

			break;

		case 'hiddenchar':
			if ($theDbSize[7] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[7] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[7] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[7];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['hiddenchar'] . ' NOT NULL default \'\',';
				$varCharLength += 120;
			}

			break;

		case 'dsnchar':
			if ($theDbSize[8] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[8] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[8] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[8];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['dsnchar'] . ' NOT NULL default \'\',';
				$varCharLength += 255;
			}

			break;

		case 'mtchar':
			if ($theDbSize[3] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[3] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[3] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[3];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['mtchar'] . ' NOT NULL default \'\',';
				$varCharLength += 100;
			}

			break;

		case 'char':
			if ($theDbSize[9] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[9] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[9] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[9];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['char'] . ' NOT NULL default \'\',';
				$varCharLength += 255;
			}

			break;

		case 'varchar':
			if ($theDbSize[4] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[4] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[4] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[4];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['varchar'] . ' NOT NULL default \'\',';
				$varCharLength += 200;
			}

			break;

		case 'listchar':
			if ($theDbSize[5] == '0') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' text NOT NULL,';
				$textLength++;
			}
			else if ($theDbSize[5] != '') {
				$SQL .= ' ' . $survey_fields_name[$i] . ' varchar(' . $theDbSize[5] . ') BINARY NOT NULL default \'\',';
				$varCharLength += $theDbSize[5];
			}
			else {
				$SQL .= ' ' . $survey_fields_name[$i] . ' ' . $Size['listchar'] . ' NOT NULL default \'\',';
				$varCharLength += 120;
			}

			break;
		}
	}
}

$SQL .= ' PRIMARY KEY (responseID),';
$SQL .= ' KEY administratorsGroupID (administratorsGroupID),';

if ($Config['is_mysql_cluster'] == 1) {
}
else {
	$SQL .= ' KEY ajaxRtnValue_1 (ajaxRtnValue_1),';
	$SQL .= ' KEY ajaxRtnValue_2 (ajaxRtnValue_2),';
	$SQL .= ' KEY ajaxRtnValue_3 (ajaxRtnValue_3),';
	$SQL .= ' KEY ajaxRtnValue_4 (ajaxRtnValue_4),';
	$SQL .= ' KEY ajaxRtnValue_5 (ajaxRtnValue_5),';
	$SQL .= ' KEY ajaxRtnValue_6 (ajaxRtnValue_6),';
}

$SQL .= ' KEY cateID (cateID),';
$SQL .= ' KEY overFlag (overFlag),';
$this_index_fields = substr($this_index_fields, 0, -1);
$survey_index_name = explode('|', $this_index_fields);

if ($Config['is_mysql_cluster'] == 1) {
	$theMaxIndexNum = 28;
}
else if ('4.1.2' <= mysql_get_server_info()) {
	$theMaxIndexNum = 54;
}
else {
	$theMaxIndexNum = 22;
}

if ($theMaxIndexNum <= count($survey_index_name)) {
	$index_key_num = $theMaxIndexNum;
}
else {
	$index_key_num = count($survey_index_name);
}

$i = 0;

for (; $i < $index_key_num; $i++) {
	if (!empty($survey_index_name[$i])) {
		$SQL .= ' KEY ' . $survey_index_name[$i] . ' (' . $survey_index_name[$i] . '),';
	}
}

$SQL = substr($SQL, 0, -1);

if ($Config['is_mysql_proxy'] == 1) {
	$databaseCharset = $db_rw_server['db_lang'];
}
else {
	$databaseCharset = $DB_lang;
}

if ($Config['is_mysql_cluster'] == 1) {
	$SQL .= ' ) ENGINE=ndbcluster DEFAULT CHARSET=' . $databaseCharset . '; ';
}
else if ('4.1' <= mysql_get_server_info()) {
	$SQL .= ' ) ENGINE=MyISAM DEFAULT CHARSET=' . $databaseCharset . '; ';
}
else {
	$SQL .= ' ) TYPE=MyISAM; ';
}

if ($Config['is_mysql_cluster'] == 1) {
	$colNumLimit = 512;
}
else {
	$colNumLimit = 4096;
}

if ($colNumLimit < $colNum) {
	$errMsg = '数据库错误：您设计的问卷内含的变量总数超出EnableQ系统的限制(' . $colNumLimit . ')，建议您在该张问卷中删除部分问题以减少变量总数！';

	if ($isAjaxActionFlag == 1) {
		exit($errMsg);
	}
	else {
		_showerror('数据库错误', $errMsg);
	}
}

$intLengthTotal = $intLength * 4;

if ($Config['is_mysql_cluster'] == 1) {
	if (strtolower($databaseCharset) == 'gbk') {
		$varCharLengthTotal = $varCharLength * 2;
	}
	else {
		$varCharLengthTotal = $varCharLength;
	}

	$textLengthTotal = $textLength * 264;
}
else if ('4.1' <= mysql_get_server_info()) {
	if (strtolower($databaseCharset) == 'gbk') {
		$varCharLengthTotal = $varCharLength * 2;
		$textLengthTotal = $textLength * 10;
	}
	else {
		$varCharLengthTotal = $varCharLength;
		$textLengthTotal = $textLength * 9;
	}
}
else {
	$varCharLengthTotal = $varCharLength;
	$textLengthTotal = $textLength * 9;
}

$rowSize = $intLengthTotal + $varCharLengthTotal + $textLengthTotal + 3;

if ($Config['is_mysql_cluster'] == 1) {
	$rowSizeLimit = 14000;
}
else {
	$rowSizeLimit = 65535;
}

if ($rowSizeLimit < $rowSize) {
	$errMsg = '数据库错误：您设计的问卷内含的变量占用的行长度超出EnableQ系统的限制(' . $rowSizeLimit . ')，您可以利用系统提供的‘存储尺寸’功能将所有值调整为零再行尝试！';

	if ($isAjaxActionFlag == 1) {
		exit($errMsg);
	}
	else {
		_showerror('数据库错误', $errMsg);
	}
}

$DB->query($SQL);
$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isDataSource=1 WHERE surveyID=\'' . $theDeploySurveyID . '\' ';
$DB->query($SQL);

?>
