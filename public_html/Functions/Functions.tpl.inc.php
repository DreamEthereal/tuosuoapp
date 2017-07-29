<?php
//dezend by http://www.yunlu99.com/
function _gettplfile($panelID, $siteTitle, $SurveyPage)
{
	global $DB;
	global $EnableQCoreClass;
	global $Config;
	global $S_Row;

	if (in_array($panelID, array(30001, 30002, 30003, 30004, 30005, 30006))) {
		$_obf_153cdraAOQM_ = 'DefaultPanel' . substr($panelID, 4, 1) . '.html';
		$_obf_arKgAERaZb4_ = 1;
	}
	else {
		$_obf_xCnI = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE panelID = \'' . $panelID . '\' ';
		$_obf_JE9AcaSM = $DB->queryFirstRow($_obf_xCnI);
		$_obf_153cdraAOQM_ = $_obf_JE9AcaSM['tplName'];
		if ($_obf_JE9AcaSM && file_exists(ROOT_PATH . 'Templates/CN/' . $_obf_153cdraAOQM_)) {
			$_obf_153cdraAOQM_ = $_obf_JE9AcaSM['tplName'];
		}
		else {
			$_obf_153cdraAOQM_ = 'DefaultPanel.html';
		}

		$_obf_arKgAERaZb4_ = 0;
	}

	$EnableQCoreClass->set_dirpath(ROOT_PATH . 'Templates/CN');
	$EnableQCoreClass->setTemplateFile('ResultPageFile', $_obf_153cdraAOQM_);
	$EnableQCoreClass->replace('enableq', $SurveyPage);
	$EnableQCoreClass->replace('siteTitle', $siteTitle . '-' . $Config['siteName']);
	$EnableQCoreClass->replace('siteName', $Config['siteName']);

	if ($_obf_arKgAERaZb4_) {
		$EnableQCoreClass->replace('surveyTitle', $S_Row['surveyTitle']);
		$EnableQCoreClass->replace('surveyInfo', $S_Row['surveyInfo']);
		$EnableQCoreClass->replace('haveText', $S_Row['surveyInfo'] == '' ? 'noHaveText' : 'haveText');
		$_obf_eHCKgWnq_TI_ = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -6);
		$EnableQCoreClass->replace('IMGFULLPATH', $_obf_eHCKgWnq_TI_);
		$_obf_dMqDgzn051zO = $_obf_eHCKgWnq_TI_ . '/q.php?qname=' . $S_Row['surveyName'];
		$EnableQCoreClass->replace('surveyURL', $_obf_dMqDgzn051zO);
		$EnableQCoreClass->replace('surveySubTitle', $S_Row['surveySubTitle'] == '' ? $_obf_dMqDgzn051zO : $S_Row['surveySubTitle']);
	}

	$SurveyPage = $EnableQCoreClass->parse('ResultPage', 'ResultPageFile');
	return $SurveyPage;
}

function _obf_fWYwDhl1K3YNd3NlXy56NG5k($siteTitle, $commonReplace)
{
	global $DB;
	global $EnableQCoreClass;
	global $Config;
	$_obf_xCnI = ' SELECT tplName FROM ' . TPL_TABLE . ' WHERE isDefault = 1 ';
	$_obf_JE9AcaSM = $DB->queryFirstRow($_obf_xCnI);

	if (!$_obf_JE9AcaSM) {
		$_obf_153cdraAOQM_ = 'DefaultPanel.html';
	}
	else {
		$_obf_153cdraAOQM_ = $_obf_JE9AcaSM['tplName'];
	}

	$EnableQCoreClass->setTemplateFile('CommonPageFile', $_obf_153cdraAOQM_);
	$EnableQCoreClass->replace('enableq', $commonReplace);
	$EnableQCoreClass->replace('siteTitle', $siteTitle . ' - ' . $Config['siteName']);
	$_obf_izJmU4I3QpE5bg__ = $EnableQCoreClass->parse('CommonPage', 'CommonPageFile');
	return $_obf_izJmU4I3QpE5bg__;
}


?>
