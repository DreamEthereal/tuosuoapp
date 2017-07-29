<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Config/RealConfig.inc.php';
$mySettingMenu = 0;
$realGPSMenu = 0;
$realSurveyGpsMenu = 0;
$batchSurveyActionMenu = 0;
$listSurveyApiMenu = 0;
$researchReportMenu = 0;
$awardAllocationMenu = 0;

switch ($_SESSION['adminRoleType']) {
case '1':
	$mySettingHTML = '<a href="javascript:void(0);">设置</a>';
	$mySettingHTML .= '<span class="head_menu_icon"></span>\\n';
	$mySettingHTML .= '<ul class="head_menu_list">';
	$mySettingHTML .= '<span class="head_menu_top"></span>';
	$mySettingHTML .= '<li><a href="../System/AdminUserList.php">管理系统用户</a></li>';
	$mySettingHTML .= '<li><a href="../System/ShowMembersList.php">管理调查样本</a></li>';
	$mySettingHTML .= '<li><a href="../System/BaseSetting.php">系统基础设置</a></li>';
	$mySettingHTML .= '<li><a href="../System/MgtTplFile.php">辅助问卷管理</a></li>';
	$mySettingHTML .= '<li><a href="../System/ShowMailList.php?stat=1">管理系统邮件</a></li>';
	$mySettingHTML .= '<li><a href="../Offline/OfflineActionLog.php?type=1">管理安卓应用</a></li>';
	$mySettingHTML .= '<li class="last"><a href="../System/DataBackup.php">数据备份恢复</a></li>';
	$mySettingMenu = 1;
	$realGPSMenu = 1;
	$realSurveyGpsMenu = 1;
	$batchSurveyActionMenu = 1;
	$listSurveyApiMenu = 1;
	$researchReportMenu = 1;
	$awardAllocationMenu = 1;
	break;

case '2':
	$mySettingHTML = '<a href="javascript:void(0);">设置</a>';
	$mySettingHTML .= '<span class="head_menu_icon"></span>\\n';
	$mySettingHTML .= '<ul class="head_menu_list">';
	$mySettingHTML .= '<span class="head_menu_top"></span>';
	$mySettingHTML .= '<li><a href="../System/ShowMembersList.php">管理调查样本</a></li>';
	$mySettingHTML .= '<li><a href="../System/MgtTplFile.php">辅助问卷管理</a></li>';
	$mySettingHTML .= '<li class="last"><a href="../System/ShowMailList.php?stat=1">管理系统邮件</a></li>';
	$mySettingMenu = 1;
	$realGPSMenu = 0;
	$realSurveyGpsMenu = 1;
	$batchSurveyActionMenu = 0;
	$listSurveyApiMenu = 0;
	$researchReportMenu = 1;
	$awardAllocationMenu = 1;
	break;

case '5':
	$mySettingHTML = '<a href="javascript:void(0);">设置</a>';
	$mySettingHTML .= '<span class="head_menu_icon"></span>\\n';
	$mySettingHTML .= '<ul class="head_menu_list">';
	$mySettingHTML .= '<span class="head_menu_top"></span>';
	$mySettingHTML .= '<li><a href="../System/AdminUserList.php">管理系统用户</a></li>';
	$mySettingHTML .= '<li><a href="../System/ShowMembersList.php">管理调查样本</a></li>';
	$mySettingHTML .= '<li><a href="../System/MgtTplFile.php">辅助问卷管理</a></li>';
	$mySettingHTML .= '<li><a href="../System/ShowMailList.php?stat=1">管理系统邮件</a></li>';
	$mySettingHTML .= '<li class="last"><a href="../Offline/OfflineActionLog.php?type=1">管理安卓应用</a></li>';
	$mySettingMenu = 1;
	$realGPSMenu = 0;
	$realSurveyGpsMenu = 1;
	$batchSurveyActionMenu = 0;
	$listSurveyApiMenu = 0;
	$researchReportMenu = 1;
	$awardAllocationMenu = 1;
	break;

case '6':
	$mySettingHTML = '<a href="javascript:void(0);">设置</a>';
	$mySettingHTML .= '<span class="head_menu_icon"></span>\\n';
	$mySettingHTML .= '<ul class="head_menu_list">';
	$mySettingHTML .= '<span class="head_menu_top"></span>';
	$mySettingHTML .= '<li><a href="../System/AdminUserList.php">管理系统用户</a></li>';
	$mySettingHTML .= '<li><a href="../System/ShowMembersList.php">管理调查样本</a></li>';
	$mySettingHTML .= '<li class="last"><a href="../Offline/OfflineActionLog.php?type=1">管理安卓应用</a></li>';
	$mySettingMenu = 1;
	$realGPSMenu = 0;
	$realSurveyGpsMenu = 0;
	$batchSurveyActionMenu = 0;
	$listSurveyApiMenu = 0;
	$researchReportMenu = 0;
	$awardAllocationMenu = 0;
	break;

case '3':
case '4':
case '7':
	$mySettingMenu = 0;
	$realGPSMenu = 0;
	$batchSurveyActionMenu = 0;
	$listSurveyApiMenu = 0;
	$researchReportMenu = 0;
	$awardAllocationMenu = 0;

	switch ($_SESSION['adminRoleType']) {
	case '4':
		$realSurveyGpsMenu = 0;
		break;

	case '7':
		if ($Config['realSurveyGps'] == 1) {
			$realSurveyGpsMenu = 1;
		}
		else {
			$realSurveyGpsMenu = 0;
		}

		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case 1:
			if ($Config['realSurveyGps'] == 1) {
				$realSurveyGpsMenu = 1;
			}
			else {
				$realSurveyGpsMenu = 0;
			}

			break;

		case 2:
			if ($Config['cViewerRealSurveyGps'] == 1) {
				$realSurveyGpsMenu = 1;
			}
			else {
				$realSurveyGpsMenu = 0;
			}

			break;
		}

		break;
	}

	break;
}

$mySettingHTML .= '</ul>';
$nickName = ($_SESSION['administratorsNickName'] == '' ? $_SESSION['administratorsName'] : $_SESSION['administratorsNickName']);
$myTaskMenu = 1;
$privateNoCommitMenu = 1;
$indexMenu = 1;
if (isset($_GET['surveyID']) && (trim($_GET['surveyID']) != '')) {
	$SQL = ' SELECT projectType,isPublic,surveyID,surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['projectType'] == 1) {
		$myTaskMenu = 1;
		$privateNoCommitMenu = 0;
	}
	else {
		$myTaskMenu = 0;
		$indexMenu = 0;
		$SettingSQL = ' SELECT isUseOriPassport FROM ' . BASESETTING_TABLE . ' ';
		$SettingRow = $DB->queryFirstRow($SettingSQL);

		if ($Row['isPublic'] == 0) {
			if ($SettingRow && (($SettingRow['isUseOriPassport'] == '2') || ($SettingRow['isUseOriPassport'] == '4'))) {
				$privateNoCommitMenu = 0;
			}
			else {
				$privateNoCommitMenu = 1;
			}
		}
		else {
			$privateNoCommitMenu = 0;
		}
	}
}

header('Content-Type: text/javascrīpt; charset: UTF-8');
echo '' . "\r\n" . 'if( document.getElementById(\'nick_Name_cont\') != null) document.getElementById(\'nick_Name_cont\').innerHTML = \'';
echo $nickName;
echo '\';' . "\r\n" . 'if( ';
echo $mySettingMenu;
echo ' == 1 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'mySetting\') != null) document.getElementById(\'mySetting\').innerHTML = \'';
echo $mySettingHTML;
echo '\';' . "\r\n" . '}' . "\r\n" . 'else' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'mySetting\') != null) {' . "\r\n" . '		$(\'#mySetting\').remove();' . "\r\n" . '		$(\'.header_m_3\').css("margin-left",\'30px\');' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $realGPSMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'realGPS\') != null) $(\'#realGPS\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $batchSurveyActionMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'batchSurveyAction\') != null) $(\'#batchSurveyAction\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $listSurveyApiMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'listSurveyApi\') != null) $(\'#listSurveyApi\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $researchReportMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'researchReport\') != null) $(\'#researchReport\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $awardAllocationMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'awardAllocation\') != null) $(\'#awardAllocation\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $myTaskMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'myTask0\') != null) $(\'#myTask0\').remove();' . "\r\n" . '	if( document.getElementById(\'myTask1\') != null) $(\'#myTask1\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $privateNoCommitMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'privateNoCommit0\') != null ) $(\'#privateNoCommit0\').remove();' . "\r\n" . '	if( document.getElementById(\'privateNoCommit1\') != null ) $(\'#privateNoCommit1\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $realSurveyGpsMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'realSurveyGps\') != null) $(\'#realSurveyGps\').remove();' . "\r\n" . '}' . "\r\n" . 'if( ';
echo $indexMenu;
echo ' == 0 )' . "\r\n" . '{' . "\r\n" . '	if( document.getElementById(\'surveyIndexRank0\') != null ) $(\'#surveyIndexRank0\').remove();' . "\r\n" . '	if( document.getElementById(\'surveyIndexRank1\') != null ) $(\'#surveyIndexRank1\').remove();' . "\r\n" . '	if( document.getElementById(\'surveyIndexMatch0\') != null ) $(\'#surveyIndexMatch0\').remove();' . "\r\n" . '	if( document.getElementById(\'surveyIndexMatch1\') != null ) $(\'#surveyIndexMatch1\').remove();' . "\r\n" . '}' . "\r\n" . '';

?>
