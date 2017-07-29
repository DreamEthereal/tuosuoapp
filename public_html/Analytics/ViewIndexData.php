<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
$EnableQCoreClass->setTemplateFile('ResultDetailFile', 'IndexResultDetail.html');

if ($_SESSION['ViewBackURL'] != '') {
	$EnableQCoreClass->replace('lastURL', $_SESSION['ViewBackURL']);
}
else {
	$EnableQCoreClass->replace('lastURL', $thisProg . '&Does=List');
}

$isViewPanelInfo = true;

switch ($_SESSION['adminRoleType']) {
case '3':
	$forbidViewIdValue = explode(',', $Sur_G_Row['forbidViewId']);

	if (in_array('t1', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t1_show', 'none');
	}
	else {
		$EnableQCoreClass->replace('t1_show', '');
	}

	if (in_array('t2', $forbidViewIdValue)) {
		$EnableQCoreClass->replace('t2_show', 'none');
		$isViewPanelInfo = false;
	}
	else {
		$EnableQCoreClass->replace('t2_show', '');
	}

	break;

default:
	$EnableQCoreClass->replace('t1_show', '');
	$EnableQCoreClass->replace('t2_show', '');
	break;
}

$SQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID=\'' . $_GET['responseID'] . '\' ';
$R_Row = $DB->queryFirstRow($SQL);
if (($Sur_G_Row['isPublic'] == '0') && ($isViewPanelInfo == true)) {
	$SQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
	$BaseRow = $DB->queryFirstRow($SQL);

	switch ($BaseRow['isUseOriPassport']) {
	case '1':
	default:
		$EnableQCoreClass->replace('isHaveGroup', '');

		if ($R_Row['administratorsGroupID'] == '0') {
			$administratorsGroupName = $lang['no_group'];
		}
		else {
			$GroupSQL = ' SELECT administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID=\'' . $R_Row['administratorsGroupID'] . '\' ';
			$GroupRow = $DB->queryFirstRow($GroupSQL);
			$administratorsGroupName = $GroupRow['administratorsGroupName'];
		}

		$EnableQCoreClass->replace('administratorsGroupName', $administratorsGroupName);
		$EnableQCoreClass->setTemplateFile('ResultAjaxDetailFile', 'ResultAjaxDetail.html');
		$EnableQCoreClass->set_CycBlock('ResultAjaxDetailFile', 'AJAX', 'ajax');
		$EnableQCoreClass->replace('ajax', '');

		if ($Sur_G_Row['ajaxRtnValue'] != '') {
			$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

			if (6 < count($ajaxRtnValueName)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValueName);
			}

			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
				$j = $i + 1;
				$EnableQCoreClass->replace('ajaxRtnValue', $R_Row['ajaxRtnValue_' . $j]);
				$EnableQCoreClass->parse('ajax', 'AJAX', true);
			}
		}

		$EnableQCoreClass->replace('ajaxRtnValue', $EnableQCoreClass->parse('ResultAjaxDetail', 'ResultAjaxDetailFile'));
		break;

	case '2':
		$EnableQCoreClass->replace('isHaveGroup', 'none');
		$EnableQCoreClass->replace('administratorsGroupName', '');
		$EnableQCoreClass->setTemplateFile('ResultAjaxDetailFile', 'ResultAjaxDetail.html');
		$EnableQCoreClass->set_CycBlock('ResultAjaxDetailFile', 'AJAX', 'ajax');
		$EnableQCoreClass->replace('ajax', '');

		if ($Sur_G_Row['ajaxRtnValue'] != '') {
			$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

			if (6 < count($ajaxRtnValueName)) {
				$ajaxCount = 6;
			}
			else {
				$ajaxCount = count($ajaxRtnValueName);
			}

			$i = 0;

			for (; $i < $ajaxCount; $i++) {
				$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
				$j = $i + 1;
				$EnableQCoreClass->replace('ajaxRtnValue', $R_Row['ajaxRtnValue_' . $j]);
				$EnableQCoreClass->parse('ajax', 'AJAX', true);
			}
		}

		$EnableQCoreClass->replace('ajaxRtnValue', $EnableQCoreClass->parse('ResultAjaxDetail', 'ResultAjaxDetailFile'));
		break;

	case '4':
	case '3':
	case '5':
		$EnableQCoreClass->replace('isHaveGroup', 'none');
		$EnableQCoreClass->replace('administratorsGroupName', '');
		$EnableQCoreClass->replace('ajaxRtnValue', '');
		break;
	}
}
else {
	$EnableQCoreClass->replace('isHaveGroup', 'none');
	$EnableQCoreClass->replace('administratorsGroupName', '');
	$EnableQCoreClass->replace('ajaxRtnValue', '');
}

$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
$EnableQCoreClass->replace('administratorsName', $R_Row['administratorsName']);
$EnableQCoreClass->replace('ipAddress', $R_Row['ipAddress']);
$EnableQCoreClass->replace('area', $R_Row['area']);
$EnableQCoreClass->replace('joinTime', date('Y-m-d H:i:s', $R_Row['joinTime']));
$EnableQCoreClass->replace('uploadTime', $R_Row['uploadTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['uploadTime']));
$EnableQCoreClass->replace('submitTime', $R_Row['submitTime'] == 0 ? 'No data' : date('Y-m-d H:i:s', $R_Row['submitTime']));
$EnableQCoreClass->replace('overTime', sectotime($R_Row['overTime']));

switch ($R_Row['overFlag']) {
case '0':
default:
	$EnableQCoreClass->replace('overFlag', $lang['result_no_all']);
	break;

case '1':
	$EnableQCoreClass->replace('overFlag', $lang['result_have_all']);
	break;

case '2':
	$EnableQCoreClass->replace('overFlag', $lang['result_to_quota']);
	break;

case '3':
	$EnableQCoreClass->replace('overFlag', $lang['result_in_export']);
	break;
}

switch ($R_Row['dataSource']) {
case '0':
default:
	$dataForm = '未知数据来源';
	break;

case '1':
	$dataForm = 'PC浏览器';
	break;

case '2':
	$dataForm = '移动浏览器';
	break;

case '3':
	$dataForm = '安卓样本App';
	break;

case '4':
	$dataForm = 'PC访员录入';
	break;

case '5':
	$dataForm = '在线访员App';
	break;

case '6':
	$dataForm = '离线访员App';
	break;

case '7':
	$dataForm = 'Excel数据导入';
	break;

case '8':
	$dataForm = '问卷数据迁移';
	break;
}

if ($R_Row['uniDataCode'] != '') {
	$this_uniDataCode = explode('######', base64_decode($R_Row['uniDataCode']));
	$EnableQCoreClass->replace('uniDataCode', $this_uniDataCode[0] . ' (' . $dataForm . ')');
}
else {
	$EnableQCoreClass->replace('uniDataCode', $dataForm);
}

$SQL = ' SELECT indexID,indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND responseID = \'' . $_GET['responseID'] . '\' ORDER BY indexID ASC ';
$rResult = $DB->query($SQL);
$theIndexValue = array();

while ($rRow = $DB->queryArray($rResult)) {
	$theIndexValue[$rRow['indexID']] = $rRow['indexValue'];
}

if (($Sur_G_Row['isRateIndex'] == 1) && ($theIndexValue[0] != '')) {
	$surveyGradeMess = '<b>' . $lang['survey_grade'] . $theIndexValue[0] . '%</b><br/>';
}
else {
	$surveyGradeMess = '<b>' . $lang['survey_grade'] . $theIndexValue[0] . '</b><br/>';
}

$SQL = ' SELECT indexID,indexName,indexDesc FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = 0 ORDER BY indexID ASC ';
$iResult = $DB->query($SQL);
$surveyIndexName = array();
$surveyIndexDesc = array();
$surveyIndex = array();
$theSurveyIndexName = array();
$theSurveyIndexDesc = array();
$theSurveyIndex = array();

while ($iRow = $DB->queryArray($iResult)) {
	$surveyIndexName[$iRow['indexID']] = $iRow['indexName'];
	$surveyIndexDesc[$iRow['indexID']] = $iRow['indexDesc'];
	$surveyIndex[$iRow['indexID']] = $theIndexValue[$iRow['indexID']] == '-999' ? 0 : $theIndexValue[$iRow['indexID']];
	$surveyGradeMess .= '<div style="border-bottom:1px solid #cacaca;width:100%"></div>';

	if ($theIndexValue[$iRow['indexID']] != '-999') {
		$theValue = $theIndexValue[$iRow['indexID']];
		if (($Sur_G_Row['isRateIndex'] == 1) && ($theValue != '')) {
			$theValue .= '%';
		}
	}
	else {
		$theValue = 'NA';
	}

	if (trim($iRow['indexDesc']) != '') {
		$surveyGradeMess .= '<b>' . $iRow['indexName'] . '：' . $theValue . '</b><br/>' . $iRow['indexDesc'] . '<br/>';
	}
	else {
		$surveyGradeMess .= '<b>' . $iRow['indexName'] . '：' . $theValue . '</b><br/>';
	}

	$sSQL = ' SELECT indexID,indexName,indexDesc FROM ' . SURVEYINDEX_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' AND fatherId = \'' . $iRow['indexID'] . '\' ORDER BY indexID ASC ';
	$sResult = $DB->query($sSQL);

	while ($sRow = $DB->queryArray($sResult)) {
		$theSurveyIndexName[$sRow['indexID']] = $sRow['indexName'];
		$theSurveyIndexDesc[$sRow['indexID']] = $sRow['indexDesc'];

		if ($theIndexValue[$sRow['indexID']] != '-999') {
			$theTier2Value = $theIndexValue[$sRow['indexID']];
			if (($Sur_G_Row['isRateIndex'] == 1) && ($theTier2Value != '')) {
				$theTier2Value .= '%';
			}
		}
		else {
			$theTier2Value = 'NA';
		}

		$theSurveyIndex[$sRow['indexID']] = $theTier2Value;

		if (trim($sRow['indexDesc']) != '') {
			$surveyGradeMess .= '<span style="margin-left:10px"><b>-&nbsp;&nbsp;' . $sRow['indexName'] . '：' . $theTier2Value . '</b></span><br/><span style="margin-left:10px">&nbsp;&nbsp;&nbsp;' . $sRow['indexDesc'] . '</span><br/>';
		}
		else {
			$surveyGradeMess .= '<span style="margin-left:10px"><b>-&nbsp;&nbsp;' . $sRow['indexName'] . '：' . $theTier2Value . '</b></span><br/>';
		}
	}
}

$EnableQCoreClass->replace('surveyGrade', $surveyGradeMess);
$theSurveyIndexNameList = implode('***', $surveyIndexName);
$theSurveyIndexValueList = implode('***', $surveyIndex);
$theRadarImgURL = '<script language=javascript src="../Chart/Swfobject.js.php"></script>';
$theRadarImgURL .= '<script type="text/javascript">';
$theRadarImgURL .= 'var so = new SWFObject("../Chart/AmRadar.swf?cache=0", "amradar", "500", "360", "8", "#ffffff");';
$theRadarImgURL .= 'so.addVariable("path", "../Chart/");';
$theRadarImgURL .= 'so.addVariable("chart_id", "amradar");';
$theRadarImgURL .= 'so.addVariable("settings_file", escape("../Chart/uRadarSetting.xml"));';
$theRadarImgURL .= 'so.addVariable("data_file", escape("../Chart/uRadarData.php?label=' . str_replace('+', '%2B', base64_encode($theSurveyIndexNameList)) . '&data=' . $theSurveyIndexValueList . '"));';
$theRadarImgURL .= 'so.write("flashcontent");';
$theRadarImgURL .= '</script>';
$EnableQCoreClass->replace('radarImage', $theRadarImgURL);
$EnableQCoreClass->set_CycBlock('ResultDetailFile', 'INDEX', 'index');
$EnableQCoreClass->set_CycBlock('INDEX', 'QUESTION', 'question');
$EnableQCoreClass->replace('index', '');

foreach ($theSurveyIndexName as $indexID => $indexName) {
	$EnableQCoreClass->replace('question', '');
	$EnableQCoreClass->replace('indexName', $indexName);

	if ($Sur_G_Row['isRateIndex'] == 1) {
		$EnableQCoreClass->replace('indexCoeff', '合格率：<font color=red>' . $theSurveyIndex[$indexID] . '</font>');
	}
	else {
		$EnableQCoreClass->replace('indexCoeff', '得分：<font color=red>' . $theSurveyIndex[$indexID] . '</font>');
	}

	$EnableQCoreClass->replace('indexDesc', $theSurveyIndexDesc[$indexID]);
	$SQL = ' SELECT a.questionID FROM ' . SURVEYINDEXLIST_TABLE . ' a,' . QUESTION_TABLE . ' b WHERE a.indexID = \'' . $indexID . '\' AND a.questionID = b.questionID ORDER BY b.orderByID ASC ';
	$qResult = $DB->query($SQL);

	while ($qRow = $DB->queryArray($qResult)) {
		$surveyID = $_GET['surveyID'];
		$questionID = $qRow['questionID'];
		$theQtnArray = $QtnListArray[$questionID];
		$joinTime = $R_Row['joinTime'];
		$ModuleName = $Module[$theQtnArray['questionType']];

		if ($theQtnArray['questionType'] != '8') {
			switch ($_SESSION['adminRoleType']) {
			case '3':
				if (!in_array($questionID, $forbidViewIdValue)) {
					require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				}

				break;

			default:
				require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.view.inc.php';
				break;
			}

			$EnableQCoreClass->parse('question', 'QUESTION', true);
		}
	}

	$EnableQCoreClass->parse('index', 'INDEX', true);
	$EnableQCoreClass->unreplace('question');
}

unset($theIndexValue);
unset($surveyIndex);
unset($surveyIndexName);
unset($surveyIndexDesc);
unset($surveyIndexIDList);
unset($theSurveyIndexName);
unset($theSurveyIndexDesc);
unset($theSurveyIndex);
$ResultDetail = $EnableQCoreClass->parse('ResultDetail', 'ResultDetailFile');
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -31);
$ResultDetail = str_replace($All_Path, '', $ResultDetail);
$ResultDetail = str_replace('PerUserData', '../PerUserData', $ResultDetail);

if ($Config['dataDirectory'] != 'PerUserData') {
	$ResultDetail = str_replace($Config['dataDirectory'], '../' . $Config['dataDirectory'], $ResultDetail);
}

echo $ResultDetail;
exit();

?>
