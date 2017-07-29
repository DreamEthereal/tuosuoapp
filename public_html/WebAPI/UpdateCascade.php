<?php
//dezend by http://www.yunlu99.com/
function isvalidnode($nodeID, $level = 1)
{
	global $theDataArray;

	if ($nodeID == 0) {
		return false;
	}

	if ($theDataArray[$nodeID]['nodeFatherID'] == 0) {
		$_obf__4IhkSRtZhtf = 1;
		$theDataArray[$nodeID]['depth'] = $_obf__4IhkSRtZhtf;
		return $_obf__4IhkSRtZhtf;
	}
	else {
		if (isset($theDataArray[$theDataArray[$nodeID]['nodeFatherID']]) || array_key_exists($theDataArray[$nodeID]['nodeFatherID'], $theDataArray)) {
			$_obf_staUPMSWwGo_ = $theDataArray[$nodeID]['nodeFatherID'];
			if (isset($theDataArray[$_obf_staUPMSWwGo_]['depth']) && ($theDataArray[$_obf_staUPMSWwGo_]['depth'] != '') && ($theDataArray[$_obf_staUPMSWwGo_]['depth'] != 0)) {
				$_obf__4IhkSRtZhtf = $theDataArray[$_obf_staUPMSWwGo_]['depth'] + 1;
				$theDataArray[$nodeID]['depth'] = $_obf__4IhkSRtZhtf;
				return $_obf__4IhkSRtZhtf;
			}
			else {
				return isvalidnode($_obf_staUPMSWwGo_, $level + 1);
			}
		}
		else {
			return false;
		}
	}
}

function qaddslashes($string, $force = 0)
{
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if (!MAGIC_QUOTES_GPC || $force) {
		if (is_array($string)) {
			foreach ($string as $_obf_Vwty => $_obf_TAxu) {
				$string[$_obf_Vwty] = qaddslashes($_obf_TAxu, $force);
			}
		}
		else {
			$string = addslashes($string);
		}
	}

	return $string;
}

@set_time_limit(0);
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.mysql.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
$thisProg = 'UpdateCascade.php?hash=' . $_GET['hash'] . ' ';
$SQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$SerialRow = $DB->queryFirstRow($SQL);

if (trim($_GET['hash']) != md5(trim($SerialRow['license']))) {
	exit('false|EnableQ Security Violation');
}

ob_end_clean();
$style = '<style>' . "\n" . '';
$style .= '.tipsinfo { font-size: 12px; font-family: Calibri;line-height:20px;margin:0px;padding:0px;}' . "\n" . '';
$style .= '.green{ font-size: 12px; font-family: Calibri;line-height:20px;color: green;font-weight: bold;}' . "\n" . '';
$style .= '</style>' . "\n" . '';
echo $style;
flush();
$scroll = '<SCRIPT type=text/javascript>window.scrollTo(0,document.body.scrollHeight);</SCRIPT>';
$prefix = '';
$i = 0;

for (; $i < 300; $i++) {
	$prefix .= ' ' . "\n" . '';
}

ob_end_clean();
echo '<div class="tipsinfo">开始同步级联题选项数据...</div>' . "\n" . '';
flush();
$SQL = ' SELECT questionID,surveyID,DSNSQL,DSNConnect,questionName FROM ' . QUESTION_TABLE . ' WHERE questionType =\'31\' AND requiredMode =2 ORDER BY questionID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	ob_end_clean();
	echo '<div class="tipsinfo">准备数据同步：<b>' . $Row['questionName'] . '(' . trim($Row['DSNSQL']) . '?' . trim($Row['DSNConnect']) . ')</b></div>' . "\n" . '';
	flush();
	$beginTime = time();
	$apiURL = trim($Row['DSNSQL']) . '?' . trim($Row['DSNConnect']) . '&hash=' . trim($_GET['hash']);
	ob_end_clean();
	echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开始获取接口程序内容返回...</div>' . "\n" . '';
	flush();
	$apiContent = get_url_content($apiURL);
	if (($apiContent == '') || ($apiContent == false)) {
		continue;
	}

	ob_end_clean();
	echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开始接口数据转JSON...</div>' . "\n" . '';
	flush();
	$apiDataArray = php_json_decode($apiContent);
	ob_end_clean();
	echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开始接口数据合法性处理...</div>' . "\n" . '';
	flush();
	$theDataArray = array();

	foreach ($apiDataArray as $theData) {
		$nodeID = (int) trim($theData[0]);
		$nodeName = qaddslashes(trim($theData[1]), 1);
		$nodeFatherID = (int) trim($theData[2]);
		if (($nodeID == '') || ($nodeID == 0) || ($nodeName == '')) {
			continue;
		}

		if (isset($theDataArray[$nodeID]) || array_key_exists($nodeID, $theDataArray)) {
			continue;
		}
		else {
			$theDataArray[$nodeID]['nodeName'] = $nodeName;
			$theDataArray[$nodeID]['nodeFatherID'] = $nodeFatherID;
		}
	}

	ob_end_clean();
	echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;接口数据的长度:' . count($theDataArray) . '...</div>' . "\n" . '';
	flush();
	$validNum = 0;
	$theExistArray = array();

	foreach ($theDataArray as $nodeID => $thisDataArray) {
		if (!in_array($nodeID, $theExistArray)) {
			$depth = isvalidnode($nodeID);
			if (($nodeID != '') && ($nodeID != 0) && ($depth != false)) {
				$validNum++;
				ob_end_clean();
				echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;处理Id：' . $nodeID . '完，总计' . $validNum . '条...</div>' . "\n" . '';
				flush();
				$theExistArray[] = $nodeID;
				$SQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID=\'' . $Row['surveyID'] . '\',questionID = \'' . $Row['questionID'] . '\',nodeID =\'' . $nodeID . '\',nodeName=\'' . $thisDataArray['nodeName'] . '\',nodeFatherID=\'' . $thisDataArray['nodeFatherID'] . '\',level=\'' . $depth . '\',flag =1 ';
				$DB->query($SQL);
			}
		}
	}

	ob_end_clean();
	echo '<div class="tipsinfo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;合法数据总数：' . $validNum . '...</div>' . "\n" . '';
	flush();

	if ($validNum == 0) {
		continue;
	}
	else {
		$SQL = ' DELETE FROM ' . CASCADE_TABLE . ' WHERE questionID=\'' . $Row['questionID'] . '\' AND flag = 0 ';
		$DB->query($SQL);
		$SQL = ' UPDATE ' . CASCADE_TABLE . ' SET flag = 0 WHERE questionID=\'' . $Row['questionID'] . '\' ';
		$DB->query($SQL);
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $Row['surveyID'] . '\' ';
	$DB->query($SQL);
	$endTime = time();
	ob_end_clean();
	echo '<div class="tipsinfo">数据同步完成：<b>' . $Row['questionName'] . '(' . trim($Row['DSNSQL']) . '?' . trim($Row['DSNConnect']) . ')</b></div>' . "\n" . '';
	flush();
	$runTime = $endTime - $beginTime;
	ob_end_clean();
	echo '<div class="tipsinfo">同步耗费时间：<b>' . $runTime . '</b></div>' . "\n" . '';
	flush();
}

ob_end_clean();
echo '<div class="green">同步级联题选项数据全部完成</div>' . "\n" . '';
flush();
exit();

?>
