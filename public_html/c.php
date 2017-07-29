<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', './');
require_once ROOT_PATH . 'Entry/Global.fore.php';
include_once ROOT_PATH . 'Functions/Functions.tpl.inc.php';
$thisProg = 'c.php?name=' . $_GET['name'];
if (($_GET['name'] == '') && ($_GET['cid'] == '')) {
	_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
}
else {
	$SQL = ' SELECT cateID,cateName FROM ' . SURVEYCATE_TABLE . ' WHERE cateTag=\'' . trim($_GET['name']) . '\' OR cateID =\'' . trim($_GET['cid']) . '\' LIMIT 0,1 ';
	$C_Row = $DB->queryFirstRow($SQL);

	if (!$C_Row) {
		_shownotes($lang['system_error'], $lang['no_survey'], $lang['no_survey']);
	}
}

$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/CN');
$EnableQCoreClass->setTemplateFile('SurveyListFile', 'uSurveyCate.html');
$EnableQCoreClass->set_CycBlock('SurveyListFile', 'SURVEY', 'survey');
$EnableQCoreClass->replace('survey', '');
$EnableQCoreClass->replace('cateName', $C_Row['cateName']);
$SQL = ' SELECT a.surveyID,a.surveyName,a.surveyTitle,a.surveySubTitle,a.surveyInfo,a.lang,a.isCheckIP,a.maxIpTime,b.cateID FROM ' . SURVEY_TABLE . ' a,' . SURVEYCATELIST_TABLE . ' b WHERE a.status =1 AND a.surveyID = b.surveyID AND b.cateID = \'' . $C_Row['cateID'] . '\'  ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$_SESSION['cateID_' . $Row['surveyID']] = $Row['cateID'];
	$EnableQCoreClass->replace('surveyTitle', $Row['surveyTitle']);
	$EnableQCoreClass->replace('surveyURL', 'q.php?qname=' . $Row['surveyName'] . '&qlang=' . $Row['lang']);

	switch ($Row['isCheckIP']) {
	case '1':
	default:
		$SQL = ' SELECT submitTime FROM ' . $table_prefix . 'response_' . $Row['surveyID'] . ' WHERE ipAddress=\'' . _getip() . '\' AND overFlag !=0 ORDER BY responseID DESC LIMIT 0,1 ';
		$HaveRow = $DB->queryFirstRow($SQL);

		if (datediff('n', $HaveRow['submitTime'], time()) < $Row['maxIpTime']) {
			$EnableQCoreClass->replace('haveAnswer', 'none');
		}
		else {
			$EnableQCoreClass->replace('haveAnswer', '');
		}

		break;

	case '2':
		if (($_COOKIE['enableqcheck' . $Row['surveyID']] != '') && ($_COOKIE['enableqcheck' . $Row['surveyID']] == md5($Row['surveyName'] . $Row['surveyID']))) {
			$EnableQCoreClass->replace('haveAnswer', 'none');
		}
		else {
			$EnableQCoreClass->replace('haveAnswer', '');
		}

		break;

	case '0':
		$EnableQCoreClass->replace('haveAnswer', '');
		break;
	}

	$EnableQCoreClass->parse('survey', 'SURVEY', true);
}

$commonReplace = $EnableQCoreClass->parse('SurveyList', 'SurveyListFile');
echo $commonReplace;

?>
