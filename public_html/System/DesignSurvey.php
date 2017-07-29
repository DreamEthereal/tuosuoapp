<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|5', $_GET['surveyID']);
$SQL = ' SELECT status,surveyTitle,administratorsID,isLogicAnd,surveyName,lang,isRecord FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Sur_G_Row = $DB->queryFirstRow($SQL);

if ($Sur_G_Row['status'] != '0') {
	_showerror($lang['system_error'], $lang['no_design_survey']);
}

$thisProg = 'DesignSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$thisURLStr = '?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($Sur_G_Row['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->replace('thisURLStr', $thisURLStr);
$EnableQCoreClass->replace('addURL', $thisProg . '&DO=Add');
$EnableQCoreClass->replace('logicURL', $thisProg . '&DO=Logic');
$EnableQCoreClass->replace('optAssURL', 'ShowOptAssociate.php' . $thisURLStr);
$EnableQCoreClass->replace('qtnAssURL', 'ShowQtnAssociate.php' . $thisURLStr);
$EnableQCoreClass->replace('quotaURL', 'ShowSurveyQuota.php' . $thisURLStr);
$EnableQCoreClass->replace('listURL', $thisProg);
$EnableQCoreClass->replace('relationURL', 'ShowValueRelation.php' . $thisURLStr);
$EnableQCoreClass->replace('questionListURL', $thisProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $Sur_G_Row['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Sur_G_Row['surveyTitle']));
$EnableQCoreClass->replace('surveyName', $Sur_G_Row['surveyName']);
$EnableQCoreClass->replace('qlang', $Sur_G_Row['lang']);
if (($_GET['Action'] == 'Insert') && !isset($_POST['formAction'])) {
	$EnableQCoreClass->setTemplateFile('SurveyInsertFile', 'QuestionInsert.html');
	$EnableQCoreClass->replace('questionName', $_GET['questionName']);
	$EnableQCoreClass->replace('questionID', $_GET['questionID']);
	$EnableQCoreClass->replace('orderByID', $_GET['orderByID']);
	$EnableQCoreClass->parse('SurveyInsertPage', 'SurveyInsertFile');
	$EnableQCoreClass->output('SurveyInsertPage');
}

if (($_GET['Action'] == 'Delete') && !isset($_POST['formAction'])) {
	if (isset($_GET['questionID']) && ($_GET['questionID'] != 0) && ($_GET['questionID'] != '')) {
		$SQL = ' SELECT questionType,questionName,orderByID,requiredMode,weight FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
		$QutRow = $DB->queryFirstRow($SQL);

		if (in_array($QutRow['questionType'], array('2', '3', '4', '24', '25'))) {
			$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID =\'' . $_GET['questionID'] . '\' LIMIT 0,1 ';
			$HaveRow = $DB->queryFirstRow($SQL);

			if ($HaveRow) {
				if ($_GET['isAjax'] == 1) {
					header('Content-Type:text/html; charset=gbk');
					echo $_GET['questionID'] . '######' . $Sur_G_Row['surveyName'] . '######' . $Sur_G_Row['lang'] . '######' . $lang['error_base_qtn_delete'];
					exit();
				}
				else {
					_showerror($lang['error_system'], $lang['error_base_qtn_delete']);
				}
			}
		}

		if ($QutRow['questionType'] != '8') {
			$MoudleName = $Module[$QutRow['questionType']];
			$questionID = $_GET['questionID'];

			if ($MoudleName != '') {
				require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.delete.inc.php';
			}
		}

		if (($QutRow['questionType'] == '30') && ($QutRow['requiredMode'] == 2)) {
			$SQL = ' UPDATE ' . RELATION_TABLE . ' SET relationDefine = 1 WHERE relationID = \'' . $QutRow['weight'] . '\' ';
			$DB->query($SQL);
		}

		$SQL = ' DELETE FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' OR condOnID=\'' . $_GET['questionID'] . '\'';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' OR condOnID=\'' . $_GET['questionID'] . '\'';
		$DB->query($SQL);
		$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'';
		$HResult = $DB->query($HSQL);
		$theRelIdList = array();

		while ($HRow = $DB->queryArray($HResult)) {
			$theRelIdList[] = $HRow['relationID'];
		}

		if (count($theRelIdList) != 0) {
			$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $theRelIdList) . ') ';
			$DB->query($SQL);
		}

		$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\'';
		$DB->query($SQL);
		$vSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ORDER BY relationID ASC ';
		$vResult = $DB->query($vSQL);

		while ($vRow = $DB->queryArray($vResult)) {
			$hvSQL = ' SELECT listID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $vRow['relationID'] . '\' LIMIT 1 ';
			$hvRow = $DB->queryFirstRow($hvSQL);

			if (!$hvRow) {
				$dSQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $vRow['relationID'] . '\' ';
				$DB->query($dSQL);
			}
		}

		$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE FIND_IN_SET(' . $_GET['questionID'] . ',questionID) OR condOnID=\'' . $_GET['questionID'] . '\' OR condOnID2=\'' . $_GET['questionID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . COMBLIST_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . COMBNAME_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
		$DB->query($SQL);
		$questionName = qnohtmltag($QutRow['questionName'], 1);
		writetolog($lang['delte_question'] . ':' . $questionName . ':' . $Sur_G_Row['surveyTitle']);

		if ($_GET['isAjax'] == 1) {
			header('Content-Type:text/html; charset=gbk');
			$questionName = qshowmessajax(stripslashes($QutRow['questionName']), 1);
			$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID < \'' . $QutRow['orderByID'] . '\' AND questionType NOT IN (\'8\',\'12\',\'30\') ORDER BY orderByID DESC LIMIT 1 ';
			$QRow = $DB->queryFirstRow($SQL);
			echo $QRow['questionID'] . '######' . $Sur_G_Row['surveyName'] . '######' . $Sur_G_Row['lang'] . '######' . $lang['delte_question'] . ':' . $questionName;
			exit();
		}
		else {
			_showsucceed($lang['delte_question'] . ':' . $questionName, $thisProg);
		}
	}
	else if ($_GET['isAjax'] == 1) {
		header('Content-Type:text/html; charset=gbk');
		$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType NOT IN (\'8\',\'12\',\'30\') ORDER BY orderByID ASC LIMIT 1 ';
		$QRow = $DB->queryFirstRow($SQL);
		echo $QRow['questionID'] . '######' . $Sur_G_Row['surveyName'] . '######' . $Sur_G_Row['lang'] . '######' . $lang['delte_question'];
		exit();
	}
	else {
		_showsucceed($lang['delte_question'] . ':' . $questionName, $thisProg);
	}
}

if ($_POST['formAction'] == 'DeleteQuestionSubmit') {
	if (is_array($_POST['questionID'])) {
		$questionIDLists = join(',', $_POST['questionID']);
		$i = 0;

		for (; $i < count($_POST['questionID']); $i++) {
			if (($_POST['questionID'][$i] != '') && ($_POST['questionID'][$i] != 0)) {
				$SQL = ' SELECT questionType,requiredMode,weight FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' ';
				$QutRow = $DB->queryFirstRow($SQL);

				if (in_array($QutRow['questionType'], array('2', '3', '4', '24', '25'))) {
					$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID =\'' . $_POST['questionID'][$i] . '\' LIMIT 0,1 ';
					$HaveRow = $DB->queryFirstRow($SQL);

					if ($HaveRow) {
						continue;
					}
				}

				if ($QutRow['questionType'] != '8') {
					$MoudleName = $Module[$QutRow['questionType']];
					$questionID = $_POST['questionID'][$i];

					if ($MoudleName != '') {
						require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.delete.inc.php';
					}
				}

				if (($QutRow['questionType'] == '30') && ($QutRow['requiredMode'] == 2)) {
					$SQL = ' UPDATE ' . RELATION_TABLE . ' SET relationDefine = 1 WHERE relationID = \'' . $QutRow['weight'] . '\' ';
					$DB->query($SQL);
				}

				$SQL = ' DELETE FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' OR condOnID = \'' . $_POST['questionID'][$i] . '\'';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' OR condOnID = \'' . $_POST['questionID'][$i] . '\'';
				$DB->query($SQL);
				$theRelIdList = array();
				$HSQL = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\'';
				$HResult = $DB->query($HSQL);

				while ($HRow = $DB->queryArray($HResult)) {
					$theRelIdList[] = $HRow['relationID'];
				}

				if (count($theRelIdList) != 0) {
					$SQL = ' DELETE FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\'';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $theRelIdList) . ') ';
					$DB->query($SQL);
				}

				$SQL = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\'';
				$DB->query($SQL);
				require ROOT_PATH . 'System/CheckValueRelation.php';
				$SQL = ' DELETE FROM ' . REPORTDEFINE_TABLE . ' WHERE FIND_IN_SET(' . $_POST['questionID'][$i] . ',questionID) OR condOnID = \'' . $_POST['questionID'][$i] . '\' OR condOnID2 = \'' . $_POST['questionID'][$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . COMBLIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . COMBNAME_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . SURVEYINDEXLIST_TABLE . ' WHERE questionID=\'' . $_POST['questionID'][$i] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	writetolog($lang['delte_question_list'] . ':' . $Sur_G_Row['surveyTitle']);
}

if ($_POST['formAction'] == 'UpdateIsRequireSubmit') {
	if (is_array($_POST['questionID'])) {
		foreach ($_POST['questionID'] as $theQtnID) {
			if (!in_array($_POST['questionType'][$theQtnID], array('7', '8', '9', '12', '23', '27', '29', '30'))) {
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET isRequired = \'' . !$_POST['isRequired'][$theQtnID] . '\' WHERE questionID =\'' . $theQtnID . '\' ';
				$DB->query($SQL);
			}
		}
	}
}

if ($_POST['formAction'] == 'InsertNewOneQtnSubmit') {
	switch ($_POST['newQuestionType']) {
	case '17':
	case '18':
	case '19':
	case '20':
	case '21':
	case '22':
	case '28':
	case '29':
		if ($_POST['baseID'] != '') {
			$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['questionID'] . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$BSQL = ' SELECT questionType,orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['baseID'] . '\' ';
			$BRow = $DB->queryFirstRow($BSQL);

			switch ($_POST['newQuestionType']) {
			case '18':
				if (($BRow['questionType'] != '2') && ($BRow['questionType'] != '24')) {
					_showerror('һ���Դ���', 'һ���Դ��󣺲��������ѡ�����ͽ�����Դ(��ͨ/����)��ѡ����');
				}

				break;

			default:
				if (($BRow['questionType'] != '3') && ($BRow['questionType'] != '25')) {
					_showerror('һ���Դ���', 'һ���Դ��󣺲�����ڱ����ͽ�����Դ(��ͨ/����)��ѡ����');
				}

				break;
			}

			if ($Row['orderByID'] <= $BRow['orderByID']) {
				_showerror('һ���Դ���', 'һ���Դ��󣺲������������Դ���ⲻ�������ڵ�ǰ������������');
			}
			else {
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
				$DB->query($SQL);
				$theNewOrderByID = $Row['orderByID'];
				$ModuleName = $Module[$_POST['newQuestionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.new.inc.php';
			}

			writetolog($lang['insert_anewqtn'] . ':' . $lang['new_a_qtn'] . $lang['question_type_' . $_POST['newQuestionType']] . ':' . $Sur_G_Row['surveyTitle']);
		}

		$actionName = $lang['insert_anewqtn'] . ':' . $lang['new_a_qtn'] . $lang['question_type_' . $_POST['newQuestionType']];
		break;

	case '8':
		$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['questionID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['page_break'] . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'8\',orderByID=\'' . $Row['orderByID'] . '\' ';
		$DB->query($SQL);
		writetolog('�����ҳ���:' . $Sur_G_Row['surveyTitle']);
		$actionName = '�����ҳ���';
		break;

	default:
		$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['questionID'] . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
		$DB->query($SQL);
		$theNewOrderByID = $Row['orderByID'];
		$ModuleName = $Module[$_POST['newQuestionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.new.inc.php';
		writetolog($lang['insert_anewqtn'] . ':' . $lang['new_a_qtn'] . $lang['question_type_' . $_POST['newQuestionType']] . ':' . $Sur_G_Row['surveyTitle']);
		$actionName = $lang['insert_anewqtn'] . ':' . $lang['new_a_qtn'] . $lang['question_type_' . $_POST['newQuestionType']];
		break;
	}

	_showmessage($actionName, true);
}

if ($_POST['formAction'] == 'InsertPageBreakSubmit') {
	if (is_array($_POST['questionID'])) {
		foreach ($_POST['questionID'] as $nowQtnID) {
			$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $nowQtnID . '\' ';
			$Row = $DB->queryFirstRow($SQL);
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['page_break'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'8\',orderByID=\'' . $Row['orderByID'] . '\' ';
			$DB->query($SQL);
		}

		writetolog($lang['insert_pagebreak'] . ':' . $Sur_G_Row['surveyTitle']);
	}
}

if ($_POST['formAction'] == 'InsertNewQtnSubmit') {
	if (is_array($_POST['questionID']) && ($_POST['newQuestionType'] != '')) {
		foreach ($_POST['questionID'] as $nowQtnID) {
			switch ($_POST['newQuestionType']) {
			case '17':
			case '18':
			case '19':
			case '20':
			case '21':
			case '22':
			case '28':
			case '29':
				if ($_POST['baseID'] != '') {
					$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $nowQtnID . '\' ';
					$Row = $DB->queryFirstRow($SQL);
					$BSQL = ' SELECT questionType,orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $_POST['baseID'] . '\' ';
					$BRow = $DB->queryFirstRow($BSQL);

					switch ($_POST['newQuestionType']) {
					case '18':
						if (($BRow['questionType'] != '2') && ($BRow['questionType'] != '24')) {
							continue;
						}

						break;

					default:
						if (($BRow['questionType'] != '3') && ($BRow['questionType'] != '25')) {
							continue;
						}

						break;
					}

					if ($Row['orderByID'] <= $BRow['orderByID']) {
						continue;
					}
					else {
						$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
						$DB->query($SQL);
						$theNewOrderByID = $Row['orderByID'];
						$ModuleName = $Module[$_POST['newQuestionType']];
						require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.new.inc.php';
					}
				}

				break;

			default:
				$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE questionID = \'' . $nowQtnID . '\' ';
				$Row = $DB->queryFirstRow($SQL);
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID >=\'' . $Row['orderByID'] . '\' ';
				$DB->query($SQL);
				$theNewOrderByID = $Row['orderByID'];
				$ModuleName = $Module[$_POST['newQuestionType']];
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.new.inc.php';
				break;
			}
		}

		writetolog($lang['insert_anewqtn'] . ':' . $lang['new_a_qtn'] . $lang['question_type_' . $_POST['newQuestionType']] . ':' . $Sur_G_Row['surveyTitle']);
	}
}

if ($_POST['Action'] == 'CopySubmit') {
	if ($_POST['indexCopy'] == 1) {
		$indexSQL = ' SELECT * FROM ' . SURVEYINDEX_TABLE . ' WHERE indexID =\'' . $_POST['indexID'] . '\' LIMIT 0,1 ';
		$indexRow = $DB->queryFirstRow($indexSQL);
		$haveSQL = ' SELECT indexID FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND indexName=\'' . $indexRow['indexName'] . '\' LIMIT 0,1 ';
		$haveRow = $DB->queryFirstRow($haveSQL);

		if ($haveRow) {
			_showerror($lang['error_system'], '���ݼ������ʾ��Ѵ�����Ҫ���Ƶ�ָ�����ƣ�' . $indexRow['indexName']);
		}

		$newSQL = ' INSERT INTO ' . SURVEYINDEX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',indexName=\'' . $indexRow['indexName'] . 'һ��ָ��\',indexDesc=\'' . $indexRow['indexDesc'] . '\',fatherId =0 ';
		$DB->query($newSQL);
		$theFatherId = $DB->_GetInsertID();
		$newSQL = ' INSERT INTO ' . SURVEYINDEX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',indexName=\'' . $indexRow['indexName'] . '\',indexDesc=\'' . $indexRow['indexDesc'] . '\',fatherId =\'' . $theFatherId . '\' ';
		$DB->query($newSQL);
		$theNewIndexID = $DB->_GetInsertID();
	}

	$theCopyQtnArray = array();
	$theQtnCount = 0;

	foreach ($_POST['questionID'] as $theQuestionID) {
		$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $theQuestionID . '\' ';
		$QutRow = $DB->queryFirstRow($SQL);
		$QutRow = qaddslashes($QutRow, 1);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $QutRow['questionName'] . '\',questionNotes=\'' . trim($QutRow['questionNotes']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'' . $QutRow['questionType'] . '\',isPublic=\'' . $QutRow['isPublic'] . '\',isRequired=\'' . $QutRow['isRequired'] . '\',isRandOptions=\'' . $QutRow['isRandOptions'] . '\',isCheckType=\'' . $QutRow['isCheckType'] . '\',isSelect=\'' . $QutRow['isSelect'] . '\',isLogicAnd=\'' . $QutRow['isLogicAnd'] . '\',isColArrange=\'' . $QutRow['isColArrange'] . '\',perRowCol=\'' . $QutRow['perRowCol'] . '\',isHaveOther=\'' . $QutRow['isHaveOther'] . '\',isHaveUnkown=\'' . $QutRow['isHaveUnkown'] . '\',requiredMode=\'' . $QutRow['requiredMode'] . '\',otherText=\'' . $QutRow['otherText'] . '\',isHaveWhy=\'' . $QutRow['isHaveWhy'] . '\',isContInvalid=\'' . $QutRow['isContInvalid'] . '\',contInvalidValue=\'' . $QutRow['contInvalidValue'] . '\',minOption=\'' . $QutRow['minOption'] . '\',maxOption=\'' . $QutRow['maxOption'] . '\',unitText=\'' . $QutRow['unitText'] . '\',rows=\'' . $QutRow['rows'] . '\',length=\'' . $QutRow['length'] . '\',minSize=\'' . $QutRow['minSize'] . '\',maxSize=\'' . $QutRow['maxSize'] . '\',allowType=\'' . $QutRow['allowType'] . '\',optionCoeff=\'' . $QutRow['optionCoeff'] . '\',optionValue=\'' . $QutRow['optionValue'] . '\',otherCode=\'' . $QutRow['otherCode'] . '\',negCode=\'' . $QutRow['negCode'] . '\',isNeg=\'' . $QutRow['isNeg'] . '\',DSNConnect=\'' . $QutRow['DSNConnect'] . '\',DSNSQL=\'' . $QutRow['DSNSQL'] . '\',DSNUser=\'' . $QutRow['DSNUser'] . '\',DSNPassword=\'' . $QutRow['DSNPassword'] . '\',hiddenVarName=\'' . $QutRow['hiddenVarName'] . '\',hiddenFromSession=\'' . $QutRow['hiddenFromSession'] . '\',weight=\'' . $QutRow['weight'] . '\',startScale=\'' . $QutRow['startScale'] . '\',endScale=\'' . $QutRow['endScale'] . '\',baseID=\'' . $QutRow['baseID'] . '\',alias=\'' . $QutRow['alias'] . '\',coeffMode=\'' . $QutRow['coeffMode'] . '\',coeffTotal=\'' . $QutRow['coeffTotal'] . '\',coeffZeroMargin=\'' . $QutRow['coeffZeroMargin'] . '\',coeffFullMargin=\'' . $QutRow['coeffFullMargin'] . '\',skipMode=\'' . $QutRow['skipMode'] . '\',negCoeff=\'' . $QutRow['negCoeff'] . '\',negValue=\'' . $QutRow['negValue'] . '\',isUnkown=\'' . $QutRow['isUnkown'] . '\',isNA=\'' . $QutRow['isNA'] . '\' ';
		$DB->query($SQL);
		$newQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$theCopyQtnArray[$theQtnCount] = $newQuestionID;
		$theQtnCount++;

		if ($QutRow['questionType'] != '8') {
			$MoudleName = $Module[$QutRow['questionType']];
			$theNewSurveyID = $_POST['surveyID'];
			require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.qtn.inc.php';
		}

		if ($_POST['indexCopy'] == 1) {
			$SQL = ' INSERT INTO ' . SURVEYINDEXLIST_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',indexID=\'' . $theNewIndexID . '\',questionID =\'' . $newQuestionID . '\' ';
			$DB->query($SQL);
		}
	}

	unset($newQtnArray);
	if (isset($_POST['theBaseId']) && ($_POST['theBaseId'] != '') && ($_POST['theBaseId'] != 'afterOfAll')) {
		$theInsertAfterQtn = explode('*', $_POST['theBaseId']);
		$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND orderByID > \'' . $theInsertAfterQtn[1] . '\' AND questionID !=\'' . $theInsertAfterQtn[0] . '\' ORDER BY orderByID ASC LIMIT 0,1';
		$nRow = $DB->queryFirstRow($SQL);

		if ($nRow) {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+' . $theQtnCount . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND orderByID >=\'' . $nRow['orderByID'] . '\' ';
			$DB->query($SQL);
			$tmp = 0;

			foreach ($theCopyQtnArray as $theNewQuestionID) {
				$theNewOrderById = $nRow['orderByID'] + $tmp;
				$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID = \'' . $theNewOrderById . '\' WHERE questionID =\'' . $theNewQuestionID . '\' ';
				$DB->query($SQL);
				$tmp++;
			}
		}
	}

	unset($theCopyQtnArray);
	writetolog($lang['copy_question'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['copy_question'] . count($_POST['questionID']) . $lang['question_num'], true);
}

if ($_GET['DO'] == 'CopyNew') {
	if ($_GET['type'] == 1) {
		$EnableQCoreClass->setTemplateFile('QuestionCopyFile', 'QuestionCopy.html');
	}
	else {
		$EnableQCoreClass->setTemplateFile('QuestionCopyFile', 'QuestionIndexCopy.html');
	}

	switch ($_SESSION['adminRoleType']) {
	case '1':
		$SQL = ' SELECT surveyTitle,surveyName,surveyID FROM ' . SURVEY_TABLE . ' WHERE 1=1 ';
		break;

	case '2':
		$SQL = ' SELECT surveyTitle,surveyName,surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID= \'' . $_SESSION['administratorsID'] . '\' ';
		break;

	case '5':
		$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
		$SQL = ' SELECT surveyTitle,surveyName,surveyID FROM ' . SURVEY_TABLE . ' WHERE administratorsID IN (' . $UserIDList . ')';
		break;
	}

	$SQL .= ' ORDER BY surveyID DESC ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$surveyIDList .= '<option value=' . $Row['surveyID'] . '>' . $Row['surveyTitle'] . '(' . $Row['surveyName'] . ')</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('surveyIDList', $surveyIDList);
	$EnableQCoreClass->replace('m_questionID', '');
	$EnableQCoreClass->replace('m_questionName', $lang['pls_select']);

	if ($_GET['type'] != 1) {
		$EnableQCoreClass->replace('m_indexID', '');
		$EnableQCoreClass->replace('m_indexName', $lang['pls_select']);
	}

	$SQL = ' SELECT questionID,questionName,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$questionList = '';

	while ($Row = $DB->queryArray($Result)) {
		$questionName = qnohtmltag($Row['questionName'], 1);
		$questionList .= '<option value=\'' . $Row['questionID'] . '*' . $Row['orderByID'] . '\'>' . $questionName . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->parse('QuestionCopy', 'QuestionCopyFile');
	$EnableQCoreClass->output('QuestionCopy');
}

if ($_GET['Actiones'] == 'Copy') {
	$_GET['questionID'] = (int) $_GET['questionID'];
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$QutRow = $DB->queryFirstRow($SQL);
	$QutRow = qaddslashes($QutRow, 1);
	$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName=\'' . $QutRow['questionName'] . '_copy\',questionNotes=\'' . trim($QutRow['questionNotes']) . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'' . $QutRow['questionType'] . '\',isPublic=\'' . $QutRow['isPublic'] . '\',isRequired=\'' . $QutRow['isRequired'] . '\',isRandOptions=\'' . $QutRow['isRandOptions'] . '\',isCheckType=\'' . $QutRow['isCheckType'] . '\',isSelect=\'' . $QutRow['isSelect'] . '\',isLogicAnd=\'' . $QutRow['isLogicAnd'] . '\',isColArrange=\'' . $QutRow['isColArrange'] . '\',perRowCol=\'' . $QutRow['perRowCol'] . '\',isHaveOther=\'' . $QutRow['isHaveOther'] . '\',isHaveUnkown=\'' . $QutRow['isHaveUnkown'] . '\',requiredMode=\'' . $QutRow['requiredMode'] . '\',otherText=\'' . $QutRow['otherText'] . '\',isHaveWhy=\'' . $QutRow['isHaveWhy'] . '\',isContInvalid=\'' . $QutRow['isContInvalid'] . '\',contInvalidValue=\'' . $QutRow['contInvalidValue'] . '\',minOption=\'' . $QutRow['minOption'] . '\',maxOption=\'' . $QutRow['maxOption'] . '\',unitText=\'' . $QutRow['unitText'] . '\',rows=\'' . $QutRow['rows'] . '\',length=\'' . $QutRow['length'] . '\',minSize=\'' . $QutRow['minSize'] . '\',maxSize=\'' . $QutRow['maxSize'] . '\',allowType=\'' . $QutRow['allowType'] . '\',optionCoeff=\'' . $QutRow['optionCoeff'] . '\',optionValue=\'' . $QutRow['optionValue'] . '\',otherCode=\'' . $QutRow['otherCode'] . '\',negCode=\'' . $QutRow['negCode'] . '\',isNeg=\'' . $QutRow['isNeg'] . '\',DSNConnect=\'' . $QutRow['DSNConnect'] . '\',DSNSQL=\'' . $QutRow['DSNSQL'] . '\',DSNUser=\'' . $QutRow['DSNUser'] . '\',DSNPassword=\'' . $QutRow['DSNPassword'] . '\',hiddenVarName=\'' . $QutRow['hiddenVarName'] . '\',hiddenFromSession=\'' . $QutRow['hiddenFromSession'] . '\',weight=\'' . $QutRow['weight'] . '\',startScale=\'' . $QutRow['startScale'] . '\',endScale=\'' . $QutRow['endScale'] . '\',baseID=\'' . $QutRow['baseID'] . '\',alias=\'' . $QutRow['alias'] . '\',coeffMode=\'' . $QutRow['coeffMode'] . '\',coeffTotal=\'' . $QutRow['coeffTotal'] . '\',coeffZeroMargin=\'' . $QutRow['coeffZeroMargin'] . '\',coeffFullMargin=\'' . $QutRow['coeffFullMargin'] . '\',skipMode=\'' . $QutRow['skipMode'] . '\',negCoeff=\'' . $QutRow['negCoeff'] . '\',negValue=\'' . $QutRow['negValue'] . '\',isUnkown=\'' . $QutRow['isUnkown'] . '\',isNA=\'' . $QutRow['isNA'] . '\' ';
	$DB->query($SQL);
	$newQuestionID = $DB->_GetInsertID();
	updateorderid('question');

	if ($QutRow['questionType'] != '8') {
		$MoudleName = $Module[$QutRow['questionType']];
		$theNewSurveyID = $_GET['surveyID'];
		require ROOT_PATH . 'PlugIn/' . $MoudleName . '/Admin/' . $MoudleName . '.qtn.inc.php';
	}

	$SQL = ' SELECT orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND orderByID > \'' . $QutRow['orderByID'] . '\' AND questionID !=\'' . $_GET['questionID'] . '\' ORDER BY orderByID ASC LIMIT 0,1';
	$nRow = $DB->queryFirstRow($SQL);

	if ($nRow) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=orderByID+1 WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND orderByID >=\'' . $nRow['orderByID'] . '\' ';
		$DB->query($SQL);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID = \'' . $nRow['orderByID'] . '\' WHERE questionID =\'' . $newQuestionID . '\' ';
		$DB->query($SQL);
	}

	$questionName = qnohtmltag(stripslashes($QutRow['questionName']), 1);
	writetolog($lang['copy_question'] . ':' . $questionName . ':' . $Sur_G_Row['surveyTitle']);

	if ($_GET['isAjax'] == 1) {
		header('Content-Type:text/html; charset=gbk');
		$questionName = qshowmessajax(stripslashes($QutRow['questionName']), 1);
		echo $newQuestionID . '######' . $Sur_G_Row['surveyName'] . '######' . $Sur_G_Row['lang'] . '######' . $lang['copy_question'] . ':' . $questionName;
		exit();
	}
	else {
		_showsucceed($lang['copy_question'] . ':' . $questionName, $thisProg);
	}
}

if ($_POST['Action'] == 'FileUploadSubmit') {
	require 'QuestionImport.inc.php';
}

if ($_GET['DO'] == 'FileImport') {
	$EnableQCoreClass->setTemplateFile('QuestionImportFile', 'QuestionImport.html');
	$SQL = ' SELECT questionID,questionName,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$questionList = '';

	while ($Row = $DB->queryArray($Result)) {
		$questionName = qnohtmltag($Row['questionName'], 1);
		$questionList .= '<option value=\'' . $Row['questionID'] . '*' . $Row['orderByID'] . '\'>' . $questionName . '</option>' . "\n" . '';
	}

	$EnableQCoreClass->replace('questionList', $questionList);
	$EnableQCoreClass->parse('QuestionImport', 'QuestionImportFile');
	$EnableQCoreClass->output('QuestionImport');
}

if (($_GET['Action'] == 'View') && !isset($_POST['formAction'])) {
	$global_surveyID = $_GET['surveyID'];
	$global_surveyTitle = $Sur_G_Row['surveyTitle'];

	if ($_GET['questionType'] != '8') {
		$ModuleName = $Module[$_GET['questionType']];
		require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.php';
	}
	else {
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $lang['page_break'] . '\',surveyID=\'' . $_GET['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'8\' ';
		$DB->query($SQL);
		updateorderid('question');

		if ($_GET['isPre'] == 1) {
			echo $Sur_G_Row['surveyName'] . '######' . $Sur_G_Row['lang'];
			exit();
		}
	}
}

if ($_GET['Actiones'] == 'ChangeQtnType') {
	if (($_GET['questionID'] != '') && ($_GET['questionID'] != 0)) {
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionType=\'' . $_GET['objType'] . '\' ';
		if (($_GET['objType'] == '24') || ($_GET['objType'] == '25')) {
			$SQL .= ' ,isHaveOther=0,isSelect=0 ';
			$CondSQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_GET['questionID'] . '\' AND optionID = 0 ';
			$DB->query($CondSQL);
			$CondSQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_GET['questionID'] . '\' AND condOptionID = 0 ';
			$DB->query($CondSQL);
		}

		$SQL .= ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
		$DB->query($SQL);
		$questionName = qnohtmltag($_GET['questionName'], 0);
		writetolog($lang['change_qtn_type'] . ':' . $questionName . ':' . $Sur_G_Row['surveyTitle']);
	}

	$newURL = $thisProg . '&Action=View&questionID=' . $_GET['questionID'] . '&questionType=' . $_GET['objType'];
	_showsucceed($lang['change_qtn_type'], $newURL);
}

if ($_GET['Action'] == 'DownloadFile') {
	include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
	_downloadfile($Config['absolutenessPath'] . '/Help/', $_GET['txtFileName']);
}

if ($_GET['DO'] == 'Add') {
	$EnableQCoreClass->setTemplateFile('QuestionAddFile', 'QuestionAdd.html');
	$SQL = ' SELECT COUNT(*) AS totalQtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' LIMIT 0,1 ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('totalQtnNum', $Row['totalQtnNum']);
	$QuestionAddPage = $EnableQCoreClass->parse('QuestionAdd', 'QuestionAddFile');
	header('Content-Type:text/html; charset=gbk');
	echo $QuestionAddPage;
	exit();
}

if ($_GET['DO'] == 'ImportLogic') {
	require 'Survey.import.logic.php';
}

if ($_GET['DOes'] == 'DeleLogic') {
	if (isset($_GET['questionID']) && ($_GET['questionID'] != 0) && ($_GET['questionID'] != '')) {
		$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE questionID = \'' . $_GET['questionID'] . '\' AND surveyID=\'' . $_GET['surveyID'] . '\' ';
		$DB->query($SQL);
		$questionName = qnohtmltag(stripslashes($_GET['questionName']), 1);
		writetolog($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName);
	}

	_showsucceed($lang['delete_logic'] . ':' . $Sur_G_Row['surveyTitle'] . ':' . $questionName, $thisProg . '&DO=Logic');
}

if ($_POST['DeleteSurveyLogicSubmit']) {
	if (is_array($_POST['questionID']) && !empty($_POST['questionID'])) {
		$questionIDLists = '';

		foreach ($_POST['questionID'] as $theQtnID) {
			if (($theQtnID != 0) && ($theQtnID != '')) {
				$questionIDLists .= $theQtnID . ',';
			}
		}

		if ($questionIDLists != '') {
			$SQL = ' DELETE FROM  ' . CONDITIONS_TABLE . ' WHERE questionID IN (' . substr($questionIDLists, 0, -1) . ') AND surveyID=\'' . $_POST['surveyID'] . '\' ';
			$DB->query($SQL);
			writetolog($lang['delete_logic_list'] . ':' . $Sur_G_Row['surveyTitle']);
		}
	}

	_showsucceed($lang['delete_logic_list'] . ':' . $Sur_G_Row['surveyTitle'], $thisProg . '&DO=Logic');
}

if ($_GET['DOes'] == 'DeleQLogic') {
	$SQL = ' DELETE FROM  ' . CONDITIONS_TABLE . ' WHERE conditionsID = \'' . $_GET['conditionsID'] . '\' ';
	$DB->query($SQL);
	$questionName = qnohtmltag(stripslashes($_GET['questionName']), 1);
	writetolog($lang['delete_logic'] . ':' . $questionName . ':' . $Sur_G_Row['surveyTitle']);
	_showsucceed($lang['delete_logic'] . ':' . $questionName, $thisProg . '&DOes=EditLogic&questionID=' . $_GET['questionID'] . '&questionName=' . $_GET['questionName']);
}

if ($_POST['Action'] == 'EditLogicSubmit') {
	if (isset($_POST['logicMode']) && ($_POST['logicMode'] != 0)) {
		switch ($_POST['logicMode']) {
		case '1':
			foreach ($_POST['optionID_1'] as $optionID) {
				$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
				$isHaveRow = $DB->queryFirstRow($SQL);

				if (!$isHaveRow) {
					$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion_1'] . '\' ,logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\',logicMode=1';
					$DB->query($SQL);
				}
			}

			break;

		case '2':
			$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $_POST['optionID_2'] . '\' LIMIT 0,1 ';
			$isHaveRow = $DB->queryFirstRow($SQL);

			if (!$isHaveRow) {
				$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $_POST['optionID_2'] . '\',opertion=\'' . $_POST['opertion_2'] . '\' ,logicValueIsAnd = \'0\',logicMode=2';
				$DB->query($SQL);
			}

			break;
		}
	}
	else {
		if (is_array($_POST['optionID']) && !empty($_POST['optionID'])) {
			foreach ($_POST['optionID'] as $optionID) {
				if (is_array($_POST['qtnID']) && !empty($_POST['qtnID'])) {
					foreach ($_POST['qtnID'] as $qtnID) {
						$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND qtnID=\'' . $qtnID . '\' LIMIT 0,1 ';
						$isHaveRow = $DB->queryFirstRow($SQL);

						if (!$isHaveRow) {
							$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',qtnID=\'' . $qtnID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
							$DB->query($SQL);
						}
					}
				}
				else {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
		}
		else if (isset($_POST['qtnID'])) {
			$theQueryValue = explode(',', $_POST['nodeValue']);
			$theValidValue = array();

			foreach ($theQueryValue as $theValue) {
				$theValue = trim($theValue);
				$hSQL = ' SELECT nodeID FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $_POST['condOnID'] . '\' AND nodeID = \'' . $theValue . '\' AND level = \'' . $_POST['qtnID'] . '\' AND flag = 0 LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);

				if ($hRow) {
					$theValidValue[] = $theValue;
				}
			}

			if (count($theValidValue) != 0) {
				foreach ($theValidValue as $nodeID) {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $nodeID . '\' AND qtnID=\'' . $_POST['qtnID'] . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',qtnID=\'' . $_POST['qtnID'] . '\',optionID=\'' . $nodeID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\'';
						$DB->query($SQL);
					}
				}
			}
		}
		else {
			$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $_POST['questionID'] . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' LIMIT 0,1 ';
			$isHaveRow = $DB->queryFirstRow($SQL);

			if (!$isHaveRow) {
				$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $_POST['questionID'] . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $_POST['optionID'] . '\',opertion=\'' . $_POST['opertion'] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['edit_logic'] . ':' . $questionName . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['edit_logic'] . ':' . $questionName, true);
}

if ($_GET['DOes'] == 'AddQtnLogicNew') {
	$EnableQCoreClass->setTemplateFile('SurveyLogicNewFile', 'SurveyLogicNew.html');
	$SQL = ' SELECT questionType,orderByID,questionName FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$BaseSQL = ' SELECT questionID,questionName,questionType,isCheckType FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND orderByID < \'' . $Row['orderByID'] . '\' AND questionType IN (1,2,3,6,7,24,25,30,19,28,17,4,23,10,15,16,20,21,22,31) ORDER BY orderByID ASC  ';
	$BaseResult = $DB->query($BaseSQL);

	while ($BaseRow = $DB->queryArray($BaseResult)) {
		$questionName = qnohtmltag($BaseRow['questionName'], 1);

		switch ($BaseRow['questionType']) {
		case '4':
			if ($BaseRow['isCheckType'] == '4') {
				$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			}

			break;

		case '23':
			$hSQL = ' SELECT isCheckType FROM ' . QUESTION_YESNO_TABLE . ' WHERE questionID=\'' . $BaseRow['questionID'] . '\' AND isCheckType =\'4\' LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);

			if ($hRow) {
				$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			}

			break;

		default:
			$BaseList .= '<option value=' . $BaseRow['questionID'] . '>' . $questionName . ' (' . $lang['question_type_' . $BaseRow['questionType']] . ')</option>';
			break;
		}
	}

	$EnableQCoreClass->replace('baseList', $BaseList);
	$EnableQCoreClass->replace('m_optionID', '');
	$EnableQCoreClass->replace('m_optionName', $lang['pls_select']);
	$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
	$questionName = qnohtmltag($Row['questionName'], 1);
	$EnableQCoreClass->replace('questionName', $questionName);
	$EnableQCoreClass->replace('Action', 'EditLogicSubmit');
	$EnableQCoreClass->replace('questionID', $_GET['questionID']);
	$EnableQCoreClass->parse('SurveyLogicNew', 'SurveyLogicNewFile');
	$EnableQCoreClass->output('SurveyLogicNew');
}

if ($_GET['DOes'] == 'EditLogic') {
	require 'ShowSingleQtnLogic.inc.php';
}

if ($_POST['Action'] == 'AddLogicSubmit') {
	if (isset($_POST['logicMode']) && ($_POST['logicMode'] != 0)) {
		switch ($_POST['logicMode']) {
		case '1':
			foreach ($_POST['questionID'] as $questionID) {
				foreach ($_POST['optionID_1'] as $optionID) {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion_1'] . '\' ,logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\',logicMode=1';
						$DB->query($SQL);
					}
				}
			}

			break;

		case '2':
			foreach ($_POST['questionID'] as $questionID) {
				$optionID = $_POST['optionID_2'];
				$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
				$isHaveRow = $DB->queryFirstRow($SQL);

				if (!$isHaveRow) {
					$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion_2'] . '\' ,logicValueIsAnd = \'0\',logicMode=2';
					$DB->query($SQL);
				}
			}

			break;
		}
	}
	else {
		if (is_array($_POST['questionID']) && !empty($_POST['questionID'])) {
			foreach ($_POST['questionID'] as $questionID) {
				if (is_array($_POST['optionID']) && !empty($_POST['optionID'])) {
					foreach ($_POST['optionID'] as $optionID) {
						if (is_array($_POST['qtnID']) && !empty($_POST['qtnID'])) {
							foreach ($_POST['qtnID'] as $qtnID) {
								$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' AND qtnID=\'' . $qtnID . '\' LIMIT 0,1 ';
								$isHaveRow = $DB->queryFirstRow($SQL);

								if (!$isHaveRow) {
									$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',qtnID=\'' . $qtnID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\' ';
									$DB->query($SQL);
								}
							}
						}
						else {
							$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $optionID . '\' LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $optionID . '\',opertion=\'' . $_POST['opertion'] . '\' ,logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\'';
								$DB->query($SQL);
							}
						}
					}
				}
				else if (isset($_POST['qtnID'])) {
					$theQueryValue = explode(',', $_POST['nodeValue']);
					$theValidValue = array();

					foreach ($theQueryValue as $theValue) {
						$theValue = trim($theValue);
						$hSQL = ' SELECT nodeID FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $_POST['condOnID'] . '\' AND nodeID = \'' . $theValue . '\' AND level = \'' . $_POST['qtnID'] . '\' AND flag = 0 LIMIT 1 ';
						$hRow = $DB->queryFirstRow($hSQL);

						if ($hRow) {
							$theValidValue[] = $theValue;
						}
					}

					if (count($theValidValue) != 0) {
						foreach ($theValidValue as $nodeID) {
							$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' AND optionID=\'' . $nodeID . '\' AND qtnID=\'' . $_POST['qtnID'] . '\' LIMIT 0,1 ';
							$isHaveRow = $DB->queryFirstRow($SQL);

							if (!$isHaveRow) {
								$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',qtnID=\'' . $_POST['qtnID'] . '\',optionID=\'' . $nodeID . '\',opertion=\'' . $_POST['opertion'] . '\',logicValueIsAnd = \'' . $_POST['logicValueIsAnd'] . '\'';
								$DB->query($SQL);
							}
						}
					}
				}
				else {
					$SQL = ' SELECT conditionsID FROM ' . CONDITIONS_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionID=\'' . $questionID . '\' AND condOnID=\'' . $_POST['condOnID'] . '\' LIMIT 0,1 ';
					$isHaveRow = $DB->queryFirstRow($SQL);

					if (!$isHaveRow) {
						$SQL = ' INSERT INTO ' . CONDITIONS_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID=\'' . $questionID . '\',     administratorsID=\'' . $_SESSION['administratorsID'] . '\',condOnID=\'' . $_POST['condOnID'] . '\',optionID=\'' . $_POST['optionID'] . '\',opertion=\'' . $_POST['opertion'] . '\' ';
						$DB->query($SQL);
					}
				}
			}
		}
	}

	writetolog($lang['setting_logic'] . ':' . $Sur_G_Row['surveyTitle']);
	_showmessage($lang['setting_logic'] . ':' . $Sur_G_Row['surveyTitle'], true);
}

if ($_GET['DO'] == 'AddLogicNew') {
	$EnableQCoreClass->setTemplateFile('SurveyLogicAddFile', 'SurveyLogicAdd.html');
	$SQL = ' SELECT questionID,questionName,questionType,requiredMode FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND isPublic=\'1\' AND questionType NOT IN (8,12) ORDER BY orderByID ASC ';
	$Result = $DB->query($SQL);
	$questionIDList = '';

	while ($Row = $DB->queryArray($Result)) {
		switch ($Row['questionType']) {
		case 30:
			if ($Row['requiredMode'] != 2) {
				$questionName = qnohtmltag($Row['questionName'], 1);
				$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			}

			break;

		default:
			$questionName = qnohtmltag($Row['questionName'], 1);
			$questionIDList .= '<option value=\'' . $Row['questionID'] . '\'>' . $questionName . ' (' . $lang['question_type_' . $Row['questionType']] . ')</option>' . "\n" . '';
			break;
		}
	}

	$EnableQCoreClass->replace('questionIDList', $questionIDList);
	$EnableQCoreClass->replace('m_optionID', '');
	$EnableQCoreClass->replace('m_optionName', $lang['pls_select']);
	$EnableQCoreClass->replace('m_condOnID', '');
	$EnableQCoreClass->replace('m_condOnName', $lang['pls_select']);
	$EnableQCoreClass->replace('Action', 'AddLogicSubmit');
	$EnableQCoreClass->parse('SurveyLogicAdd', 'SurveyLogicAddFile');
	$EnableQCoreClass->output('SurveyLogicAdd');
}

if ($_GET['DO'] == 'Logic') {
	require 'ShowSurveyLogic.inc.php';
}

if (($_GET['DO'] == 'Order') && !isset($_POST['formAction']) && !isset($_POST['Action'])) {
	$_GET['OrderID'] = (int) $_GET['OrderID'];
	$_GET['ID'] = (int) $_GET['ID'];
	$OrderIDNew = $_GET['OrderID'];

	switch ($_GET['Compositor']) {
	case 'ASC':
		$SQL = ' SELECT questionID,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\'';
		$SQL .= ' AND orderByID > \'' . $_GET['OrderID'] . '\' AND questionID !=\'' . $_GET['ID'] . '\' ORDER BY orderByID ASC LIMIT 0,1';
		break;

	default:
		$SQL = ' SELECT questionID,orderByID FROM ' . QUESTION_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\'';
		$SQL .= ' AND orderByID < \'' . $_GET['OrderID'] . '\' AND questionID !=\'' . $_GET['ID'] . '\' ORDER BY orderByID DESC LIMIT 0,1 ';
		break;
	}

	if ($Row = $DB->queryFirstRow($SQL)) {
		if ($OrderIDNew != $Row['orderByID']) {
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=\'' . $OrderIDNew . '\' WHERE questionID=\'' . $Row['questionID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET orderByID=\'' . $Row['orderByID'] . '\' WHERE questionID=\'' . $_GET['ID'] . '\' ';
			$DB->query($SQL);
		}
	}
}

$EnableQCoreClass->setTemplateFile('QuestionListFile', 'DesignSurvey.html');

$QuestionList = $EnableQCoreClass->parse('QuestionList', 'QuestionListFile');

echo $QuestionList;

?>
