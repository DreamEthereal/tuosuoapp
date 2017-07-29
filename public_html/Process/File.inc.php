<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$File_SQL = '';

if (qhtmlspecialchars($_POST['thisFiles']) != '') {
	$thisPageFileUploadList = substr(qhtmlspecialchars($_POST['thisFiles']), 0, -1);
	$thisPageFileUpload = explode('|', $thisPageFileUploadList);
	$thisPageFileOlderList = substr(qhtmlspecialchars($_POST['thisSizes']), 0, -1);
	$thisPageFileOlder = explode('|', $thisPageFileOlderList);
	$tmp = 0;

	if ($Config['dataDomainName'] != '') {
		foreach ($thisPageFileUpload as $theFiles) {
			$theUploadFileName = trim($_POST[$theFiles]);

			if ($theUploadFileName != '') {
				$File_SQL .= ' ,' . $theFiles . ' = \'' . $theUploadFileName . '\' ';
			}
			else {
				$File_SQL .= ' ,' . $theFiles . ' = \'\' ';
			}

			if ($isModiDataFlag == 1) {
			}
			else {
				$_SESSION[$theFiles] = $theUploadFileName;
			}

			if ($isAuthDataFlag == 1) {
				if ($theUploadFileName != addslashes($R_Row[$theFiles])) {
					$theFieldsArray = explode('_', $theFiles);
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $theFiles . '\',oriValue=\'' . addslashes($R_Row[$theFiles]) . '\',updateValue=\'' . $theUploadFileName . '\',isAppData =0,traceTime=\'' . time() . '\' ';
					$DB->query($uSQL);
					unset($theFieldsArray);
				}
			}
			else {
				$theOlderFile = explode('###', $thisPageFileOlder[$tmp]);

				if ($theUploadFileName != $theOlderFile[0]) {
					if ($S_Row['custDataPath'] == '') {
						$olderFilePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['surveyID'] . '/' . date('Y-m', $theOlderFile[1]) . '/' . date('d', $theOlderFile[1]) . '/';
					}
					else {
						$olderFilePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
					}

					if (($theOlderFile[0] != 'null') && file_exists($olderFilePhyPath . $theOlderFile[0])) {
						@unlink($olderFilePhyPath . $theOlderFile[0]);
					}
				}
			}

			$tmp++;
		}
	}
	else {
		$tmpFilePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
		if (!isset($_SESSION['joinTime_' . $S_Row['surveyID']]) || ($_SESSION['joinTime_' . $S_Row['surveyID']] == '')) {
			if (!isset($R_Row['joinTime']) || ($R_Row['joinTime'] == '')) {
				$theFileTime = time();
			}
			else {
				$theFileTime = $R_Row['joinTime'];
			}
		}
		else {
			$theFileTime = $_SESSION['joinTime_' . $S_Row['surveyID']];
		}

		foreach ($thisPageFileUpload as $theFiles) {
			$theUploadFileName = trim($_POST[$theFiles]);

			if ($theUploadFileName != '') {
				if ($S_Row['custDataPath'] == '') {
					$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['surveyID'] . '/' . date('Y-m', $theFileTime) . '/' . date('d', $theFileTime) . '/';
				}
				else {
					$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
				}

				createdir($filePhyPath);

				if (file_exists($tmpFilePhyPath . $theUploadFileName)) {
					if (copy($tmpFilePhyPath . $theUploadFileName, $filePhyPath . $theUploadFileName)) {
						@unlink($tmpFilePhyPath . $theUploadFileName);
						$File_SQL .= ' ,' . $theFiles . ' = \'' . $theUploadFileName . '\' ';
					}
				}
				else if (file_exists($filePhyPath . $theUploadFileName)) {
					$File_SQL .= ' ,' . $theFiles . ' = \'' . $theUploadFileName . '\' ';
				}
				else {
					$File_SQL .= ' ,' . $theFiles . ' = \'\' ';
				}
			}
			else {
				$File_SQL .= ' ,' . $theFiles . ' = \'\' ';
			}

			if ($isModiDataFlag == 1) {
			}
			else {
				$_SESSION[$theFiles] = $theUploadFileName;
			}

			if ($isAuthDataFlag == 1) {
				if ($theUploadFileName != addslashes($R_Row[$theFiles])) {
					$theFieldsArray = explode('_', $theFiles);
					$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $S_Row['surveyID'] . '\',responseID=\'' . $R_Row['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $theFiles . '\',oriValue=\'' . addslashes($R_Row[$theFiles]) . '\',updateValue=\'' . $theUploadFileName . '\',isAppData =0,traceTime=\'' . time() . '\' ';
					$DB->query($uSQL);
					unset($theFieldsArray);
				}
			}
			else {
				$theOlderFile = explode('###', $thisPageFileOlder[$tmp]);

				if ($theUploadFileName != $theOlderFile[0]) {
					if ($S_Row['custDataPath'] == '') {
						$olderFilePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['surveyID'] . '/' . date('Y-m', $theOlderFile[1]) . '/' . date('d', $theOlderFile[1]) . '/';
					}
					else {
						$olderFilePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
					}

					if (($theOlderFile[0] != 'null') && file_exists($olderFilePhyPath . $theOlderFile[0])) {
						@unlink($olderFilePhyPath . $theOlderFile[0]);
					}
				}
			}

			$tmp++;
		}
	}
}

?>
