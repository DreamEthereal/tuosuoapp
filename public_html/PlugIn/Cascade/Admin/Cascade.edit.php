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

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$lastProg = 'ModiSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
include_once ROOT_PATH . 'Functions/Functions.csv.inc.php';
include_once ROOT_PATH . 'Functions/Functions.json.inc.php';
include_once ROOT_PATH . 'Functions/Functions.curl.inc.php';
$BaseSQL = ' SELECT license FROM ' . BASESETTING_TABLE . ' ';
$systemRow = $DB->queryFirstRow($BaseSQL);
$hashCode = md5(trim($systemRow['license']));
if (($_POST['Action'] == 'AddCascadeSubmit') || ($_POST['Action'] == 'AddCascadeOver')) {
	if (!isset($_SESSION['PageToken31']) || ($_SESSION['PageToken31'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['requiredMode'] == 1) {
		if ($_FILES['csvFile']['name'] != '') {
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

			switch ($_POST['importMode']) {
			case 1:
			default:
				$theExistArray = array();
				$theDataArray = array();
				$SQL = ' SELECT nodeID,nodeFatherID FROM ' . CASCADE_TABLE . ' WHERE questionID = \'' . $_POST['questionID'] . '\' ORDER BY nodeID ASC ';
				$Result = $DB->query($SQL);

				while ($Row = $DB->queryArray($Result)) {
					$theExistArray[] = $Row['nodeID'];
					$theDataArray[$Row['nodeID']]['nodeFatherID'] = $Row['nodeFatherID'];
				}

				setlocale(LC_ALL, 'zh_CN.GBK');
				$File = fopen($newFullName, 'r');
				$csvLineNum = 0;

				while ($csvData = _fgetcsv($File)) {
					if ($csvLineNum != 0) {
						$nodeID = (int) trim($csvData[0]);
						$nodeName = qaddslashes(trim($csvData[1]), 1);
						$nodeFatherID = (int) trim($csvData[2]);
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

					$csvLineNum++;
				}

				fclose($File);

				if (file_exists($newFullName)) {
					@unlink($newFullName);
				}

				$validNum = 0;

				foreach ($theDataArray as $nodeID => $thisDataArray) {
					if (!in_array($nodeID, $theExistArray)) {
						$depth = isvalidnode($nodeID);
						if (($nodeID != '') && ($nodeID != 0) && ($depth != false)) {
							$validNum++;
							$theExistArray[] = $nodeID;
							$SQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID'] . '\',nodeID =\'' . $nodeID . '\',nodeName=\'' . $thisDataArray['nodeName'] . '\',nodeFatherID=\'' . $thisDataArray['nodeFatherID'] . '\',level=\'' . $depth . '\',flag =1 ';
							$DB->query($SQL);
						}
					}
				}

				if ($validNum == 0) {
					_showerror('检查错误', '检查错误：需要导入问题选项的CSV文件内合格的节点ID为空！');
				}
				else {
					$SQL = ' UPDATE ' . CASCADE_TABLE . ' SET flag = 0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
					$questionName = qnoreturnchar($_POST['questionName']);
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',unitText=\'' . $_POST['unitText'] . '\',allowType=\'' . $_POST['allowType'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
				}

				break;

			case 2:
				setlocale(LC_ALL, 'zh_CN.GBK');
				$File = fopen($newFullName, 'r');
				$csvLineNum = 0;
				$theDataArray = array();

				while ($csvData = _fgetcsv($File)) {
					if ($csvLineNum != 0) {
						$nodeID = (int) trim($csvData[0]);
						$nodeName = qaddslashes(trim($csvData[1]), 1);
						$nodeFatherID = (int) trim($csvData[2]);
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

					$csvLineNum++;
				}

				fclose($File);

				if (file_exists($newFullName)) {
					@unlink($newFullName);
				}

				$validNum = 0;
				$theExistArray = array();

				foreach ($theDataArray as $nodeID => $thisDataArray) {
					if (!in_array($nodeID, $theExistArray)) {
						$depth = isvalidnode($nodeID);
						if (($nodeID != '') && ($nodeID != 0) && ($depth != false)) {
							$validNum++;
							$theExistArray[] = $nodeID;
							$SQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID'] . '\',nodeID =\'' . $nodeID . '\',nodeName=\'' . $thisDataArray['nodeName'] . '\',nodeFatherID=\'' . $thisDataArray['nodeFatherID'] . '\',level=\'' . $depth . '\',flag =1 ';
							$DB->query($SQL);
						}
					}
				}

				if ($validNum == 0) {
					_showerror('检查错误', '检查错误：需要导入问题选项的CSV文件内合格的节点ID为空！');
				}
				else {
					$SQL = ' DELETE FROM ' . CASCADE_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND flag = 0 ';
					$DB->query($SQL);
					$SQL = ' UPDATE ' . CASCADE_TABLE . ' SET flag = 0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
					$questionName = qnoreturnchar($_POST['questionName']);
					$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',unitText=\'' . $_POST['unitText'] . '\',allowType=\'' . $_POST['allowType'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
					$DB->query($SQL);
				}

				break;
			}
		}
		else {
			$questionName = qnoreturnchar($_POST['questionName']);
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',unitText=\'' . $_POST['unitText'] . '\',allowType=\'' . $_POST['allowType'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
		}
	}
	else {
		$apiURL = trim($_POST['DSNSQL']) . '?' . trim($_POST['DSNConnect']) . '&hash=' . $hashCode;
		$apiContent = get_url_content($apiURL);
		if (($apiContent == '') || ($apiContent == false)) {
			_showerror($lang['error_system'], '关键性错误：接口程序返回的数据为空!');
		}

		$apiDataArray = php_json_decode($apiContent);
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

		$validNum = 0;
		$theExistArray = array();

		foreach ($theDataArray as $nodeID => $thisDataArray) {
			if (!in_array($nodeID, $theExistArray)) {
				$depth = isvalidnode($nodeID);
				if (($nodeID != '') && ($nodeID != 0) && ($depth != false)) {
					$validNum++;
					$theExistArray[] = $nodeID;
					$SQL = ' INSERT INTO ' . CASCADE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',questionID = \'' . $_POST['questionID'] . '\',nodeID =\'' . $nodeID . '\',nodeName=\'' . $thisDataArray['nodeName'] . '\',nodeFatherID=\'' . $thisDataArray['nodeFatherID'] . '\',level=\'' . $depth . '\',flag =1 ';
					$DB->query($SQL);
				}
			}
		}

		if ($validNum == 0) {
			_showerror('检查错误', '检查错误：接口程序返回的合格节点ID为空！');
		}
		else {
			$SQL = ' DELETE FROM ' . CASCADE_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND flag = 0 ';
			$DB->query($SQL);
			$SQL = ' UPDATE ' . CASCADE_TABLE . ' SET flag = 0 WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
			$questionName = qnoreturnchar($_POST['questionName']);
			$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',unitText=\'' . $_POST['unitText'] . '\',allowType=\'' . $_POST['allowType'] . '\',requiredMode=\'' . $_POST['requiredMode'] . '\',DSNSQL=\'' . trim($_POST['DSNSQL']) . '\',DSNConnect=\'' . trim($_POST['DSNConnect']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
			$DB->query($SQL);
		}
	}

	$SQL = ' UPDATE ' . SURVEY_TABLE . ' SET isCache = 1 WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$DB->query($SQL);
	unset($_SESSION['PageToken31']);
	$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
	writetolog($lang['modi_cascade_question'] . ':' . $questionName);

	if ($_POST['Action'] == 'AddCascadeSubmit') {
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['modi_cascade_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['modi_cascade_question'] . ':' . $questionName, $nextURL);
		}
	}
	else {
		_showmessage($lang['modi_cascade_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CascadeEditFile', 'CascadeModi.html');
$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$EnableQCoreClass->replace('questionName', $Row['questionName']);
$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
$theNameArray = explode('#', $Row['unitText']);
$nowData = '&nbsp;&nbsp;&nbsp;目前已有数据：';
$i = 1;

for (; $i <= $Row['maxSize']; $i++) {
	$hSQL = ' SELECT COUNT(*) FROM ' . CASCADE_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND level = \'' . $i . '\' ';
	$hRow = $DB->queryFirstRow($hSQL);
	$j = $i - 1;
	$theShowName = (trim($theNameArray[$j]) == '' ? '第' . $i . '级' : trim($theNameArray[$j]));
	$nowData .= $theShowName . '(<b>' . $hRow[0] . '条</b>)&nbsp;&nbsp;';
}

$nowData .= '<br/>&nbsp;&nbsp;&nbsp;<a href="../Export/Export.cascade.php?surveyID=' . $_GET['surveyID'] . '&questionID=' . $_GET['questionID'] . '"><b><font color=red>下载现有数据</font></b></a>';
$EnableQCoreClass->replace('nowData', $nowData . '<br/>');
$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
$EnableQCoreClass->replace('unitText', $Row['unitText']);

if ($Row['isRequired'] == '1') {
	$EnableQCoreClass->replace('isRequired', 'checked');
}
else {
	$EnableQCoreClass->replace('isRequired', '');
}

$EnableQCoreClass->replace('allowType', $Row['allowType']);
$EnableQCoreClass->replace('questionID', $Row['questionID']);
$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
$EnableQCoreClass->replace('actionMode', 2);
$EnableQCoreClass->replace('requiredMode_' . $Row['requiredMode'], 'checked');
$EnableQCoreClass->replace('DSNSQL', $Row['DSNSQL']);
$EnableQCoreClass->replace('DSNConnect', $Row['DSNConnect']);
$_SESSION['PageToken31'] = session_id();
$EnableQCoreClass->parse('CascadeEdit', 'CascadeEditFile');
$EnableQCoreClass->output('CascadeEdit');

?>
