<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit(' Security Violation');
}

if (is_array($_POST['responseID'])) {
	$responseIDLists = join(',', $_POST['responseID']);
	$RSQL = ' SELECT recordFile,fingerFile,joinTime,authStat FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID IN (' . $responseIDLists . ') ';
	$RResult = $DB->query($RSQL);

	while ($RRow = $DB->queryArray($RResult)) {
		if ($_SESSION['adminRoleType'] == 4) {
			switch ($RRow['authStat']) {
			case '0':
			case '2':
				if ($Sur_G_Row['isDeleteData'] == 1) {
					continue;
				}

				break;

			default:
				continue;
				break;
			}
		}

		if ($Sur_G_Row['custDataPath'] == '') {
			$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/';
			$vFilePhyPath = 'response_' . $_GET['surveyID'] . '/';
		}
		else {
			$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
			$vFilePhyPath = 'user/' . $Sur_G_Row['custDataPath'] . '/';
		}

		if (($RRow['recordFile'] != '') && file_exists($filePhyPath . $RRow['recordFile'])) {
			require_remote_service(1, base64_encode($vFilePhyPath . $RRow['recordFile']));
			@unlink($filePhyPath . $RRow['recordFile']);
		}

		if ($Sur_G_Row['custDataPath'] == '') {
			$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/' . date('Y-m', $RRow['joinTime']) . '/' . date('d', $RRow['joinTime']) . '/';
			$vFilePhyPath = 'response_' . $_GET['surveyID'] . '/' . date('Y-m', $RRow['joinTime']) . '/' . date('d', $RRow['joinTime']) . '/';
		}
		else {
			$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
			$vFilePhyPath = 'user/' . $Sur_G_Row['custDataPath'] . '/';
		}

		if (($RRow['fingerFile'] != '') && file_exists($filePhyPath . $RRow['fingerFile'])) {
			require_remote_service(1, base64_encode($vFilePhyPath . $RRow['fingerFile']));
			@unlink($filePhyPath . $RRow['fingerFile']);
		}
	}

	$SQL = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND questionType =11 ';
	$Result = $DB->query($SQL);

	while ($Row = $DB->queryArray($Result)) {
		$SQL = ' SELECT option_' . $Row['questionID'] . ',joinTime FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID IN (' . $responseIDLists . ') ';
		$FileResult = $DB->query($SQL);

		while ($FileRow = $DB->queryArray($FileResult)) {
			if ($Sur_G_Row['custDataPath'] == '') {
				$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_GET['surveyID'] . '/' . date('Y-m', $FileRow['joinTime']) . '/' . date('d', $FileRow['joinTime']) . '/';
				$vFilePhyPath = 'response_' . $_GET['surveyID'] . '/' . date('Y-m', $FileRow['joinTime']) . '/' . date('d', $FileRow['joinTime']) . '/';
			}
			else {
				$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $Sur_G_Row['custDataPath'] . '/';
				$vFilePhyPath = 'user/' . $Sur_G_Row['custDataPath'] . '/';
			}

			if (($FileRow['option_' . $Row['questionID']] != '') && file_exists($filePhyPath . $FileRow['option_' . $Row['questionID']])) {
				require_remote_service(1, base64_encode($vFilePhyPath . $FileRow['option_' . $Row['questionID']]));
				@unlink($filePhyPath . $FileRow['option_' . $Row['questionID']]);
			}

			$uSQL = ' SELECT oriValue,updateValue,evidence FROM ' . DATA_TRACE_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND questionID =\'' . $Row['questionID'] . '\' ';
			$uResult = $DB->query($uSQL);

			while ($uRow = $DB->queryArray($uResult)) {
				if (($uRow['oriValue'] != '') && file_exists($filePhyPath . $uRow['oriValue'])) {
					@unlink($filePhyPath . $uRow['oriValue']);
				}

				if (($uRow['updateValue'] != '') && file_exists($filePhyPath . $uRow['updateValue'])) {
					@unlink($filePhyPath . $uRow['updateValue']);
				}

				if (($uRow['evidence'] != '') && file_exists($filePhyPath . $uRow['evidence'])) {
					@unlink($filePhyPath . $uRow['evidence']);
				}
			}
		}
	}

	$SQL = ' DELETE FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' WHERE responseID IN ( ' . $responseIDLists . ') ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . AWARDLIST_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . ANDROID_INFO_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . DATA_TRACE_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . DATA_TASK_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . GPS_TRACE_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . GPS_TRACE_UPLOAD_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);
	$SQL = ' DELETE FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE responseID IN (' . $responseIDLists . ') AND surveyID =\'' . $_GET['surveyID'] . '\' ';
	$DB->query($SQL);

	foreach ($_POST['responseID'] as $theResponseID) {
		if (($_POST['createDate'][$theResponseID] != '') && ($_POST['overFlag'][$theResponseID] == 1)) {
			delcountinfo($_GET['surveyID'], $_POST['createDate'][$theResponseID]);
		}
	}

	writetolog($lang['delete_response_list'] . ',ÐòºÅ:' . $responseIDLists . ':' . $_GET['surveyTitle']);
}

_showsucceed($lang['delete_response_list'] . ':' . $_GET['surveyTitle'], $thisProg);

?>
