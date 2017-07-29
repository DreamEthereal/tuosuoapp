<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
header('Content-Type:text/html; charset=gbk');
$_POST['surveyID'] = (int) $_POST['surveyID'];
_checkpassport('1|2|5', $_POST['surveyID']);
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' ';
//查询的信息
$Row = $DB->queryFirstRow($SQL);

switch ($Row['status']) {
case '0':
	$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyDesignAction.html');
	$editURL = '?Action=Edit&surveyID=' . $Row['surveyID'];
	$EnableQCoreClass->replace('editURL', $editURL);
	$EnableQCoreClass->replace('deleteURL', '?Action=Delete&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$EnableQCoreClass->replace('copyURL', '?Action=Copy&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$statusURL = '?Action=ChangeStatus&surveyID=' . $Row['surveyID'] . '&status=1&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('statusURL', $statusURL);
	$preURL = '../d.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'];
	$EnableQCoreClass->replace('preURL', $preURL);
	$designURL = 'DesignSurvey.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('designURL', $designURL);
	$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		$EnableQCoreClass->replace('havePlan', $lang['have_survey_plan']);
	}
	else {
		$EnableQCoreClass->replace('havePlan', $lang['no_survey_plan']);
	}

	$planURL = 'ShowSurveyPlan.php?status=0&surveyID=' . $Row['surveyID'] . '&beginTime=' . urlencode($Row['beginTime']) . '&endTime=' . urlencode($Row['endTime']) . '&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('planURL', $planURL);
	$EnableQCoreClass->replace('begin_Time', $Row['beginTime']);
	$EnableQCoreClass->replace('end_Time', $Row['endTime']);

	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->replace('haveTaskSurvey', '');
		$SQL = ' SELECT * FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$EnableQCoreClass->replace('haveTask', $lang['have_survey_task']);
		}
		else {
			$EnableQCoreClass->replace('haveTask', $lang['no_survey_task']);
		}

		$taskURL = 'ShowSurveyTask.php?status=0&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
	}
	else {
		$EnableQCoreClass->replace('haveTaskSurvey', 'none');
		$EnableQCoreClass->replace('haveTask', '');
		$EnableQCoreClass->replace('taskURL', '');
	}

	$SQL = ' SELECT count(*) as countNum FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND fatherId !=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow['countNum'] != 0) {
		$EnableQCoreClass->replace('haveIndex', $lang['have_survey_index'] . '[ <span class=red>' . $HaveRow['countNum'] . '</span> ]');
	}
	else {
		$EnableQCoreClass->replace('haveIndex', $lang['no_survey_index']);
	}

	break;

case '1':
	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyDeployMyAction.html');
		$SQL = ' SELECT * FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$EnableQCoreClass->replace('haveTask', $lang['have_survey_task']);
			$EnableQCoreClass->replace('taskbtn', '');
		}
		else {
			$EnableQCoreClass->replace('haveTask', $lang['no_survey_task']);
			$EnableQCoreClass->replace('taskbtn', 'none');
		}

		$taskURL = 'ShowSurveyTask.php?status=1&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$EnableQCoreClass->replace('taskURL', $taskURL);
	}
	else {
		$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyDeployAction.html');
		$SettingSQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
		$SettingRow = $DB->queryFirstRow($SettingSQL);

		if ($Row['isPublic'] == 0) {
			if ($SettingRow && (($SettingRow['isUseOriPassport'] == '2') || ($SettingRow['isUseOriPassport'] == '4'))) {
				$EnableQCoreClass->replace('emailbtn', '');
				$EnableQCoreClass->replace('nocommitbtn', 'none');
			}
			else {
				$EnableQCoreClass->replace('emailbtn', '');
				$EnableQCoreClass->replace('nocommitbtn', '');
			}
		}
		else {
			$EnableQCoreClass->replace('emailbtn', '');
			$EnableQCoreClass->replace('nocommitbtn', 'none');
		}

		$EnableQCoreClass->replace('inputbtn', '');
		$inputURL = 'InputSurveyAnswer.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
		$EnableQCoreClass->replace('inputURL', $inputURL);
	}

	$EnableQCoreClass->replace('begin_Time', $Row['beginTime']);
	$EnableQCoreClass->replace('end_Time', $Row['endTime']);
	$EnableQCoreClass->replace('surveyName', $Row['surveyName']);
	$EnableQCoreClass->replace('qlang', $Row['lang']);
	$cacheURL = $thisProg . '?Action=ClearCache&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('cacheURL', $cacheURL);
	$designURL = 'ModiSurvey.php?surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('designURL', $designURL);
	$editURL = '?Action=Edit&surveyID=' . $Row['surveyID'];
	$EnableQCoreClass->replace('editURL', $editURL);
	$EnableQCoreClass->replace('copyURL', '?Action=Copy&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$clearURL = $thisProg . '?Action=ClearStatus&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('clearURL', $clearURL);
	$statusURL = $thisProg . '?Action=ChangeStatus&surveyID=' . $Row['surveyID'] . '&status=2&surveyTitle=' . urlencode($Row['surveyTitle']);
	$EnableQCoreClass->replace('statusURL', $statusURL);
	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);
	$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);
	$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		$EnableQCoreClass->replace('havePlan', $lang['have_survey_plan']);
		$EnableQCoreClass->replace('planbtn', '');
	}
	else {
		$EnableQCoreClass->replace('havePlan', $lang['no_survey_plan']);
		$EnableQCoreClass->replace('planbtn', 'none');
	}

	$planURL = 'ShowSurveyPlan.php?status=1&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']) . '&beginTime=' . $Row['beginTime'] . '&endTime=' . $Row['endTime'];
	$EnableQCoreClass->replace('planURL', $planURL);
	$SQL = ' SELECT count(*) as countNum FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND fatherId !=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow['countNum'] != 0) {
		$EnableQCoreClass->replace('haveIndex', $lang['have_survey_index'] . '[ <span class=red>' . $HaveRow['countNum'] . '</span> ]');
		$EnableQCoreClass->replace('indexbtn', '');
	}
	else {
		$EnableQCoreClass->replace('haveIndex', $lang['no_survey_index']);
		$EnableQCoreClass->replace('indexbtn', 'none');
	}

	if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('exportURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
		$EnableQCoreClass->replace('spssURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
		$EnableQCoreClass->replace('labelURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
	}
	else {
		$EnableQCoreClass->replace('exportURL', 'onclick="javascript:gId(\'excelData\').disabled=true;gId(\'excelData\').style.border=\'1px solid #DDD\';gId(\'excelData\').style.backgroundColor=\'#F5F5F5\';gId(\'excelData\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.result.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('spssURL', 'onclick="javascript:gId(\'spssData\').disabled=true;gId(\'spssData\').style.border=\'1px solid #DDD\';gId(\'spssData\').style.backgroundColor=\'#F5F5F5\';gId(\'spssData\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.spss.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('labelURL', 'onclick="javascript:gId(\'spssLabel\').disabled=true;gId(\'spssLabel\').style.border=\'1px solid #DDD\';gId(\'spssLabel\').style.backgroundColor=\'#F5F5F5\';gId(\'spssLabel\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.label.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
	}

	break;

case '2':
	if ($Row['projectType'] == 1) {
		$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyCloseMyAction.html');
		$SQL = ' SELECT * FROM ' . TASK_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if ($HaveRow) {
			$EnableQCoreClass->replace('haveTask', $lang['have_survey_task']);
			$EnableQCoreClass->replace('taskbtn', '');
		}
		else {
			$EnableQCoreClass->replace('haveTask', $lang['no_survey_task']);
			$EnableQCoreClass->replace('taskbtn', 'none');
		}
	}
	else {
		$EnableQCoreClass->setTemplateFile('ActionPageFile', 'SurveyCloseAction.html');
	}

	$EnableQCoreClass->replace('begin_Time', $Row['beginTime']);
	$EnableQCoreClass->replace('end_Time', $Row['endTime']);
	$preURL = '../p.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang'];
	$EnableQCoreClass->replace('preURL', $preURL);
	$EnableQCoreClass->replace('deleteURL', '?Action=Delete&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$EnableQCoreClass->replace('copyURL', '?Action=Copy&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$EnableQCoreClass->replace('statusURL', '?Action=DeployStatus&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$EnableQCoreClass->replace('archURL', 'MgtArchiving.php?Action=SurveyArchive&surveyID=' . $Row['surveyID'] . '&surveyTitle=' . urlencode($Row['surveyTitle']));
	$R_SQL = ' SELECT COUNT(*) AS resultNum FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' ';
	$R_Row = $DB->queryFirstRow($R_SQL);
	$EnableQCoreClass->replace('resultNum', $R_Row['resultNum']);

	if ($License['isEvalUsers']) {
		$EnableQCoreClass->replace('exportURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
		$EnableQCoreClass->replace('spssURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
		$EnableQCoreClass->replace('labelURL', 'onclick="javascript:alert(\'' . $lang['pls_register_soft'] . '\');"');
	}
	else {
		$EnableQCoreClass->replace('exportURL', 'onclick="javascript:gId(\'excelData\').disabled=true;gId(\'excelData\').style.border=\'1px solid #DDD\';gId(\'excelData\').style.backgroundColor=\'#F5F5F5\';gId(\'excelData\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.result.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('spssURL', 'onclick="javascript:gId(\'spssData\').disabled=true;gId(\'spssData\').style.border=\'1px solid #DDD\';gId(\'spssData\').style.backgroundColor=\'#F5F5F5\';gId(\'spssData\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.spss.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
		$EnableQCoreClass->replace('labelURL', 'onclick="javascript:gId(\'spssLabel\').disabled=true;gId(\'spssLabel\').style.border=\'1px solid #DDD\';gId(\'spssLabel\').style.backgroundColor=\'#F5F5F5\';gId(\'spssLabel\').style.color=\'#3b5888\';window.location.href=\'../Export/Export.label.inc.php?surveyID=' . $Row['surveyID'] . '\';"');
	}

	$SQL = ' SELECT * FROM ' . PLAN_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow) {
		$EnableQCoreClass->replace('havePlan', $lang['have_survey_plan']);
		$EnableQCoreClass->replace('planbtn', '');
	}
	else {
		$EnableQCoreClass->replace('havePlan', $lang['no_survey_plan']);
		$EnableQCoreClass->replace('planbtn', 'none');
	}

	$SQL = ' SELECT count(*) as countNum FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID=\'' . $Row['surveyID'] . '\' AND fatherId !=0 LIMIT 0,1 ';
	$HaveRow = $DB->queryFirstRow($SQL);

	if ($HaveRow['countNum'] != 0) {
		$EnableQCoreClass->replace('haveIndex', $lang['have_survey_index'] . '[ <span class=red>' . $HaveRow['countNum'] . '</span> ]');
		$EnableQCoreClass->replace('indexbtn', '');
	}
	else {
		$EnableQCoreClass->replace('haveIndex', $lang['no_survey_index']);
		$EnableQCoreClass->replace('indexbtn', 'none');
	}

	break;
}

$EnableQCoreClass->replace('survey_Title', qnohtmltag($Row['surveyTitle']));
$EnableQCoreClass->replace('survey_URLTitle', urlencode($Row['surveyTitle']));
$EnableQCoreClass->replace('survey_Name', $Row['surveyName']);
$EnableQCoreClass->replace('surveyID', $Row['surveyID']);
$EnableQCoreClass->replace('surveyLang', $lang['lang_' . $Row['lang']]);
$EnableQCoreClass->replace('createDate', date('Y-m-d', $Row['joinTime']));
$EnableQCoreClass->replace('projectType', $lang['projectType_' . $Row['projectType']]);
$EnableQCoreClass->replace('surveyStatus', $lang['isPublic_' . $Row['isPublic']]);
$SQL = ' SELECT COUNT(*) as qtnNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionType != 8 LIMIT 0,1';
$QtnHaveRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('qtnNum', $QtnHaveRow['qtnNum']);
$SQL = ' SELECT COUNT(*) as pagesNum FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_POST['surveyID'] . '\' AND questionType = 8 LIMIT 0,1';
$PagesHaveRow = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('pagesNum', $PagesHaveRow['pagesNum'] + 1);

if ($QtnHaveRow['qtnNum'] == 0) {
	$EnableQCoreClass->replace('isHaveQtn', 'none');
}
else {
	$EnableQCoreClass->replace('isHaveQtn', '');
}

$AdminSQL = ' SELECT administratorsName,userGroupID,groupType FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $Row['administratorsID'] . '\' ';
$UserRow = $DB->queryFirstRow($AdminSQL);

if (!$UserRow) {
	$EnableQCoreClass->replace('ownerUser', $lang['deleted_user']);
}
else {
	$EnableQCoreClass->replace('ownerUser', _getuserallname($UserRow['administratorsName'], $UserRow['userGroupID'], $UserRow['groupType']));
}

$ActionPage = $EnableQCoreClass->parse('ActionPage', 'ActionPageFile');
echo $ActionPage;

?>
