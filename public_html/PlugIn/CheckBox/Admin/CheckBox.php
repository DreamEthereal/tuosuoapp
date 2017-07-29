<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
require_once ROOT_PATH . 'Functions/Functions.check.inc.php';
$lastProg = 'DesignSurvey.php?surveyID=' . $_GET['surveyID'] . '&surveyTitle=' . urlencode($_GET['surveyTitle']) . '&isPre=' . $_GET['isPre'];
$EnableQCoreClass->replace('addURL', $lastProg . '&DO=Add');
$EnableQCoreClass->replace('listURL', $lastProg);
$EnableQCoreClass->replace('questionListURL', $lastProg);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$SupportUploadFileType = 'jpg|gif|png|JPG|GIF|PNG|';
$picClrProg = $lastProg . '&Action=View&questionID=' . $_GET['questionID'] . '&questionType=3';

if ($_GET['DOes'] == 'Default') {
	$SQL = ' SELECT createDate,optionNameFile FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $_GET['optionOptionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row) {
		$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

		if (file_exists($optionPicPath . $Row['optionNameFile'])) {
			@unlink($optionPicPath . $Row['optionNameFile']);
		}

		if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
			@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
		}

		$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionNameFile=\'\' ';
		$SQL .= ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $_GET['optionOptionID'] . '\' ';
		$DB->query($SQL);
	}

	_showsucceed($lang['clear_picture'], $picClrProg);
}

if ($_POST['Action'] == 'AddCheckBoxSubmit') {
	if (!isset($_SESSION['PageToken3']) || ($_SESSION['PageToken3'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'3\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minSize=\'' . $_POST['minSize'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',allowType=\'' . $_POST['negText'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\',otherText=\'' . $_POST['otherText'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',unitText=\'' . trim($_POST['unitText']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$time = time();
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\',createDate=\'' . $time . '\' ';

				if ($_FILES['optionFile_' . $i]['name'] != '') {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $time) . '/' . date('d', $time) . '/';
					createdir($optionPicPath);
					$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = false, date('ymdHis', $time) . $i, $isThumb = true, $width = 120, $height = 160);
					$SQL .= $optionPictureSQL;
				}

				$DB->query($SQL);
				updateorderid('question_checkbox');
			}
		}

		unset($_SESSION['PageToken3']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_checkbox_question'] . ':' . $questionName);
		_showmessage($lang['add_checkbox_question'] . ':' . $questionName, true, 1);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minSize=\'' . $_POST['minSize'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',allowType=\'' . $_POST['negText'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\',otherText=\'' . $_POST['otherText'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',unitText=\'' . trim($_POST['unitText']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$theBaseItQtnRangeIdList = _getbaseitqtnrange($_POST['questionID']);
		$theBaseItQtnAutoIdList = _getbaseitqtnauto($_POST['questionID']);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT createDate,optionNameFile FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\' ';

					if ($_FILES['optionFile_' . $i]['name'] != '') {
						$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';
						createdir($optionPicPath);
						$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = true, date('ymdHis', time()) . $i, $isThumb = true, $width = 120, $height = 160, $Row['optionNameFile']);
						$SQL .= $optionPictureSQL;
					}

					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$time = time();
					$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\',createDate=\'' . $time . '\' ';

					if ($_FILES['optionFile_' . $i]['name'] != '') {
						$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $time) . '/' . date('d', $time) . '/';
						createdir($optionPicPath);
						$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = false, date('ymdHis', $time) . $i, $isThumb = true, $width = 120, $height = 160);
						$SQL .= $optionPictureSQL;
					}

					$DB->query($SQL);
					updateorderid('question_checkbox');
				}
			}
			else {
				$SQL = ' SELECT createDate,optionNameFile,question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

					if (file_exists($optionPicPath . $Row['optionNameFile'])) {
						@unlink($optionPicPath . $Row['optionNameFile']);
					}

					if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
						@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
					}

					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_checkboxID'] . '\') ';
					$DB->query($SQL);

					if ($theBaseItQtnRangeIdList != false) {
						$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
						$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
					}

					if ($theBaseItQtnAutoIdList != false) {
						$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
						$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
					}

					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, $Row['question_checkboxID'], 0);
					$SQL = ' DELETE FROM ' . QUESTION_CHECKBOX_TABLE . ' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$SQL = ' SELECT createDate,optionNameFile,question_checkboxID,optionOptionID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

			if (file_exists($optionPicPath . $Row['optionNameFile'])) {
				@unlink($optionPicPath . $Row['optionNameFile']);
			}

			if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
				@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
			}

			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_checkboxID'] . '\') ';
			$DB->query($SQL);

			if ($theBaseItQtnRangeIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
			}

			if ($theBaseItQtnAutoIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'' . $Row['question_checkboxID'] . '\'  ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
			}

			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, $Row['question_checkboxID'], 0);
			$SQL = ' DELETE FROM ' . QUESTION_CHECKBOX_TABLE . ' ';
			$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $Row['optionOptionID'] . '\' ';
			$DB->query($SQL);
		}

		if ($_POST['isHaveOther'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'0\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'0\' ';
			$DB->query($SQL);

			if ($theBaseItQtnRangeIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'0\'  ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'0\' ';
				$DB->query($SQL);
			}

			if ($theBaseItQtnAutoIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'0\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'0\' ';
				$DB->query($SQL);
			}

			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, 0, 0);
		}

		if ($_POST['isNeg'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'99999\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'99999\' ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, 99999, 0);
		}

		unset($_SESSION['PageToken3']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_checkbox_question'] . ':' . $questionName);
		$nextURL = _getnexturl($_POST['surveyID'], $_POST['questionID'], $_POST['orderByID']);

		if ($nextURL == '') {
			_showmessage($lang['add_checkbox_question'] . ':' . $questionName, true, $_POST['questionID']);
		}
		else {
			_showsucceed($lang['add_checkbox_question'] . ':' . $questionName, $nextURL);
		}
	}
}

if ($_POST['Action'] == 'AddCheckBoxOver') {
	if (!isset($_SESSION['PageToken3']) || ($_SESSION['PageToken3'] != session_id())) {
		_showerror('安全错误', '安全错误：系统检查到您的表单数据已经提交，不再需要多次操作！');
	}

	if ($_POST['questionID'] == '') {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' INSERT INTO ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionType=\'3\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minSize=\'' . $_POST['minSize'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',allowType=\'' . $_POST['negText'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\',otherText=\'' . $_POST['otherText'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',unitText=\'' . trim($_POST['unitText']) . '\' ';
		$DB->query($SQL);
		$lastQuestionID = $DB->_GetInsertID();
		updateorderid('question');
		$time = time();
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $lastQuestionID . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\',createDate=\'' . $time . '\' ';

				if ($_FILES['optionFile_' . $i]['name'] != '') {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $time) . '/' . date('d', $time) . '/';
					createdir($optionPicPath);
					$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = false, date('ymdHis', $time) . $i, $isThumb = true, $width = 120, $height = 160);
					$SQL .= $optionPictureSQL;
				}

				$DB->query($SQL);
				updateorderid('question_checkbox');
			}
		}

		unset($_SESSION['PageToken3']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_checkbox_question'] . ':' . $questionName);
		_showmessage($lang['add_checkbox_question'] . ':' . $questionName, true, $lastQuestionID);
	}
	else {
		$questionName = qnoreturnchar($_POST['questionName']);
		$SQL = ' UPDATE ' . QUESTION_TABLE . ' SET questionName =\'' . $questionName . '\',questionNotes=\'' . trim($_POST['questionNotes']) . '\',alias=\'' . trim($_POST['alias']) . '\',isRequired=\'' . $_POST['isRequired'] . '\',length=\'' . $_POST['maxLength'] . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minSize=\'' . $_POST['minSize'] . '\',maxSize=\'' . $_POST['maxSize'] . '\',isRandOptions=\'' . $_POST['isRandOptions'] . '\',isSelect=\'' . $_POST['isSelect'] . '\',isNeg=\'' . $_POST['isNeg'] . '\',allowType=\'' . $_POST['negText'] . '\',isColArrange=\'' . $_POST['isColArrange'] . '\',perRowCol=\'' . $_POST['perRowCol'] . '\',isHaveOther=\'' . $_POST['isHaveOther'] . '\',otherText=\'' . $_POST['otherText'] . '\',minOption=\'' . $_POST['minOption'] . '\',maxOption=\'' . $_POST['maxOption'] . '\',unitText=\'' . trim($_POST['unitText']) . '\' WHERE questionID=\'' . $_POST['questionID'] . '\' ';
		$DB->query($SQL);
		$theBaseItQtnRangeIdList = _getbaseitqtnrange($_POST['questionID']);
		$theBaseItQtnAutoIdList = _getbaseitqtnauto($_POST['questionID']);
		$i = 1;

		for (; $i <= sizeof($_POST['optionID']); $i++) {
			if (trim($_POST['optionID'][$i]) != '') {
				$SQL = ' SELECT createDate,optionNameFile FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$SQL = ' UPDATE ' . QUESTION_CHECKBOX_TABLE . ' SET optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\' ';

					if ($_FILES['optionFile_' . $i]['name'] != '') {
						$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';
						createdir($optionPicPath);
						$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = true, date('ymdHis', time()) . $i, $isThumb = true, $width = 120, $height = 160, $Row['optionNameFile']);
						$SQL .= $optionPictureSQL;
					}

					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
				else {
					$time = time();
					$SQL = ' INSERT INTO ' . QUESTION_CHECKBOX_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $_POST['questionID'] . '\',optionOptionID=\'' . $i . '\',optionName=\'' . qnoreturnchar($_POST['optionID'][$i]) . '\',optionMargin=\'' . $_POST['optionMargin'][$i] . '\',createDate=\'' . $time . '\' ';

					if ($_FILES['optionFile_' . $i]['name'] != '') {
						$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $time) . '/' . date('d', $time) . '/';
						createdir($optionPicPath);
						$optionPictureSQL = _thumbpicfileupload('optionFile_' . $i, 'optionNameFile', $SupportUploadFileType, $optionPicPath, $isEdit = false, date('ymdHis', $time) . $i, $isThumb = true, $width = 120, $height = 160);
						$SQL .= $optionPictureSQL;
					}

					$DB->query($SQL);
					updateorderid('question_checkbox');
				}
			}
			else {
				$SQL = ' SELECT createDate,optionNameFile,question_checkboxID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
				$Row = $DB->queryFirstRow($SQL);

				if ($Row) {
					$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

					if (file_exists($optionPicPath . $Row['optionNameFile'])) {
						@unlink($optionPicPath . $Row['optionNameFile']);
					}

					if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
						@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
					}

					$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
					$DB->query($SQL);
					$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_checkboxID'] . '\') ';
					$DB->query($SQL);

					if ($theBaseItQtnRangeIdList != false) {
						$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
						$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
					}

					if ($theBaseItQtnAutoIdList != false) {
						$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
						$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'' . $Row['question_checkboxID'] . '\' ';
						$DB->query($SQL);
					}

					checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, $Row['question_checkboxID'], 0);
					$SQL = ' DELETE FROM ' . QUESTION_CHECKBOX_TABLE . ' ';
					$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
					$DB->query($SQL);
				}
			}
		}

		$SQL = ' SELECT createDate,optionNameFile,question_checkboxID,optionOptionID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_POST['questionID'] . '\' AND optionOptionID > \'' . sizeof($_POST['optionID']) . '\' ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$optionPicPath = $Config['absolutenessPath'] . '/PerUserData/p/' . date('Y-m', $Row['createDate']) . '/' . date('d', $Row['createDate']) . '/';

			if (file_exists($optionPicPath . $Row['optionNameFile'])) {
				@unlink($optionPicPath . $Row['optionNameFile']);
			}

			if (file_exists($optionPicPath . 's_' . $Row['optionNameFile'])) {
				@unlink($optionPicPath . 's_' . $Row['optionNameFile']);
			}

			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE ( questionID=\'' . $_POST['questionID'] . '\' AND optionID =\'' . $Row['question_checkboxID'] . '\') OR (condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'' . $Row['question_checkboxID'] . '\') ';
			$DB->query($SQL);

			if ($theBaseItQtnRangeIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
			}

			if ($theBaseItQtnAutoIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'' . $Row['question_checkboxID'] . '\' ';
				$DB->query($SQL);
			}

			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, $Row['question_checkboxID'], 0);
			$SQL = ' DELETE FROM ' . QUESTION_CHECKBOX_TABLE . ' ';
			$SQL .= ' WHERE questionID =\'' . $_POST['questionID'] . '\' AND optionOptionID=\'' . $Row['optionOptionID'] . '\' ';
			$DB->query($SQL);
		}

		if ($_POST['isHaveOther'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'0\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'0\' ';
			$DB->query($SQL);

			if ($theBaseItQtnRangeIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND qtnID =\'0\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnRangeIdList . ') AND condQtnID =\'0\' ';
				$DB->query($SQL);
			}

			if ($theBaseItQtnAutoIdList != false) {
				$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND optionID =\'0\' ';
				$DB->query($SQL);
				$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID IN (' . $theBaseItQtnAutoIdList . ') AND condOptionID =\'0\' ';
				$DB->query($SQL);
			}

			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, 0, 0);
		}

		if ($_POST['isNeg'] != 1) {
			$SQL = ' DELETE FROM ' . CONDITIONS_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND optionID =\'99999\' ';
			$DB->query($SQL);
			$SQL = ' DELETE FROM ' . ASSOCIATE_TABLE . ' WHERE condOnID=\'' . $_POST['questionID'] . '\' AND condOptionID =\'99999\' ';
			$DB->query($SQL);
			checkvaluerelation($_POST['surveyID'], $_POST['questionID'], 3, 0, 99999, 0);
		}

		unset($_SESSION['PageToken3']);
		$questionName = qnohtmltag(stripslashes($_POST['questionName']), 1);
		writetolog($lang['add_checkbox_question'] . ':' . $questionName);
		_showmessage($lang['add_checkbox_question'] . ':' . $questionName, true, $_POST['questionID']);
	}
}

$EnableQCoreClass->setTemplateFile('CheckBoxEditFile', 'CheckBoxEdit.html');
$EnableQCoreClass->set_CycBlock('CheckBoxEditFile', 'OPTION', 'option');
$EnableQCoreClass->replace('option', '');
$suggestAction = '';
$suggestCSS = '';

if ($_GET['questionID'] != '') {
	$SQL = ' SELECT * FROM ' . QUESTION_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);

	if ($Row['isRequired'] == '1') {
		$EnableQCoreClass->replace('isRequired', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRequired', '');
	}

	if ($Row['isRandOptions'] == '1') {
		$EnableQCoreClass->replace('isRandOptions', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isRandOptions', '');
	}

	if ($Row['isSelect'] == '1') {
		$EnableQCoreClass->replace('isSelect', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isSelect', '');
	}

	if ($Row['isNeg'] == '1') {
		$EnableQCoreClass->replace('isNeg', 'checked');
		$EnableQCoreClass->replace('negText', qshowquotestring($Row['allowType']));
	}
	else {
		$EnableQCoreClass->replace('isNeg', '');
		$EnableQCoreClass->replace('negText', '');
	}

	if ($Row['minOption'] == 0) {
		$EnableQCoreClass->replace('minOption', '');
	}
	else {
		$EnableQCoreClass->replace('minOption', $Row['minOption']);
	}

	if ($Row['maxOption'] == 0) {
		$EnableQCoreClass->replace('maxOption', '');
	}
	else {
		$EnableQCoreClass->replace('maxOption', $Row['maxOption']);
	}

	if ($Row['isColArrange'] == '1') {
		$EnableQCoreClass->replace('isColArrange', 'checked');
		$EnableQCoreClass->replace('perRowCol', $Row['perRowCol']);
	}
	else {
		$EnableQCoreClass->replace('isColArrange', '');
		$EnableQCoreClass->replace('perRowCol', '');
	}

	if ($Row['isHaveOther'] == '1') {
		$EnableQCoreClass->replace('isHaveOther', 'checked');
		$EnableQCoreClass->replace('otherText', qshowquotestring($Row['otherText']));
		$EnableQCoreClass->replace('maxLength', $Row['length']);
		$EnableQCoreClass->replace('isCheckType' . $Row['isCheckType'], 'selected');
		$EnableQCoreClass->replace('isCheckType', $Row['isCheckType']);

		if ($Row['minSize'] == 0) {
			if ($Row['maxSize'] == 0) {
				$EnableQCoreClass->replace('minSize', '');
			}
			else {
				$EnableQCoreClass->replace('minSize', '0');
			}
		}
		else {
			$EnableQCoreClass->replace('minSize', $Row['minSize']);
		}

		if ($Row['maxSize'] == 0) {
			$EnableQCoreClass->replace('maxSize', '');
		}
		else {
			$EnableQCoreClass->replace('maxSize', $Row['maxSize']);
		}

		$EnableQCoreClass->replace('unitText', $Row['unitText']);
	}
	else {
		$EnableQCoreClass->replace('isHaveOther', '');
		$EnableQCoreClass->replace('otherText', '');
		$EnableQCoreClass->replace('maxLength', '30');
		$EnableQCoreClass->replace('minSize', '');
		$EnableQCoreClass->replace('maxSize', '');
		$EnableQCoreClass->replace('unitText', '');
		$EnableQCoreClass->replace('isCheckType0', 'selected');
		$EnableQCoreClass->replace('isCheckType', 0);
	}

	$SQL = ' SELECT max(optionOptionID) as theMaxOptionOptionID FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' ';
	$MaxRow = $DB->queryFirstRow($SQL);
	$i = 1;

	for (; $i <= $MaxRow['theMaxOptionOptionID']; $i++) {
		$EnableQCoreClass->replace('tableIndex', $i + 1);
		$SQL = ' SELECT * FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE questionID=\'' . $_GET['questionID'] . '\' AND optionOptionID=\'' . $i . '\' ';
		$OptionRow = $DB->queryFirstRow($SQL);
		$EnableQCoreClass->replace('optionOrderID', $i);
		$suggestAction .= ' new autoComplete(aNames,document.getElementById(\'optionID_' . $i . '\'),document.getElementById(\'optionSuggest_' . $i . '\'),10);' . "\n" . ' ';
		$suggestCSS .= '#optionSuggest_' . $i . '{position:absolute;background:#DBEDB5;width:228px;}' . "\n" . ' #optionSuggest_' . $i . ' div{background:#fff;color:#000;padding-left:4px;cursor:hand;text-align:left;} ' . "\n" . ' #optionSuggest_' . $i . ' div.over{color:#fff;	background:#3366CC;}' . "\n" . '';

		if ($OptionRow['optionNameFile'] != '') {
			$EnableQCoreClass->replace('picFileName', '<a href="' . $picClrProg . '&DOes=Default&optionOptionID=' . $i . '">' . $lang['clr_pic'] . '</a>&nbsp;&nbsp;<a href=\'../PerUserData/p/' . date('Y-m', $OptionRow['createDate']) . '/' . date('d', $OptionRow['createDate']) . '/' . $OptionRow['optionNameFile'] . '\' rel="picbox" title="' . $OptionRow['optionNameFile'] . '">' . $OptionRow['optionNameFile'] . '</a>&nbsp;&nbsp;');
		}
		else {
			$EnableQCoreClass->replace('picFileName', '');
		}

		$EnableQCoreClass->replace('optionName', qshowquotestring($OptionRow['optionName']));

		if ($OptionRow['optionMargin'] == 0) {
			$EnableQCoreClass->replace('optionMargin', '');
		}
		else {
			$EnableQCoreClass->replace('optionMargin', $OptionRow['optionMargin']);
		}

		$EnableQCoreClass->replace('createDate', $OptionRow['createDate']);
		$EnableQCoreClass->replace('optionNameFile', $OptionRow['optionNameFile']);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('questionID', $Row['questionID']);
	$EnableQCoreClass->replace('orderByID', $Row['orderByID']);
	$EnableQCoreClass->replace('questionName', $Row['questionName']);
	$EnableQCoreClass->replace('question_Name', urlencode($Row['questionName']));
	$EnableQCoreClass->replace('questionNotes', $Row['questionNotes']);
	$EnableQCoreClass->replace('alias', $Row['alias']);
}
else {
	$EnableQCoreClass->replace('isRequired', 'checked');
	$EnableQCoreClass->replace('isRandOptions', '');
	$EnableQCoreClass->replace('isSelect', '');
	$EnableQCoreClass->replace('minOption', '');
	$EnableQCoreClass->replace('maxOption', '');
	$EnableQCoreClass->replace('isColArrange', '');
	$EnableQCoreClass->replace('perRowCol', '');
	$EnableQCoreClass->replace('isHaveOther', '');
	$EnableQCoreClass->replace('otherText', $lang['other_text']);
	$EnableQCoreClass->replace('isNeg', '');
	$EnableQCoreClass->replace('negText', $lang['neg_text']);
	$EnableQCoreClass->replace('maxLength', '30');
	$EnableQCoreClass->replace('minSize', '');
	$EnableQCoreClass->replace('maxSize', '');
	$EnableQCoreClass->replace('unitText', '');
	$EnableQCoreClass->replace('isCheckType0', 'selected');
	$EnableQCoreClass->replace('isCheckType', 0);
	$EnableQCoreClass->replace('questionID', '');
	$EnableQCoreClass->replace('orderByID', '');
	$EnableQCoreClass->replace('questionName', $lang['default_questionname']);
	$EnableQCoreClass->replace('question_Name', $lang['default_questionname']);
	$EnableQCoreClass->replace('alias', '');
	$EnableQCoreClass->replace('questionNotes', '');
	$i = 1;

	for (; $i <= 5; $i++) {
		$EnableQCoreClass->replace('tableIndex', $i + 1);
		$suggestAction .= ' new autoComplete(aNames,document.getElementById(\'optionID_' . $i . '\'),document.getElementById(\'optionSuggest_' . $i . '\'),10);' . "\n" . ' ';
		$suggestCSS .= '#optionSuggest_' . $i . '{position:absolute;background:#DBEDB5;width:228px;}' . "\n" . ' #optionSuggest_' . $i . ' div{background:#fff;color:#000;padding-left:4px;cursor:hand;text-align:left;} ' . "\n" . ' #optionSuggest_' . $i . ' div.over{color:#fff;	background:#3366CC;}' . "\n" . '';
		$EnableQCoreClass->replace('optionOrderID', $i);
		$EnableQCoreClass->parse('option', 'OPTION', true);
	}

	$EnableQCoreClass->replace('picFileName', '');
	$EnableQCoreClass->replace('optionName', '');
	$EnableQCoreClass->replace('optionMargin', '');
	$EnableQCoreClass->replace('createDate', '');
	$EnableQCoreClass->replace('optionNameFile', '');
}

$SQL = ' SELECT DISTINCT optionName FROM ' . QUESTION_CHECKBOX_TABLE . ' WHERE administratorsID=\'' . $_SESSION['administratorsID'] . '\' ORDER BY optionOptionID DESC LIMIT 0,100 ';
$Result = $DB->query($SQL);
$suggestWords = '';

while ($Row = $DB->queryArray($Result)) {
	$suggestWords .= '"' . qhtmlspecialchars($Row['optionName']) . '",';
}

$suggestWords = substr($suggestWords, 0, -1);
$EnableQCoreClass->replace('suggestWords', $suggestWords);
$EnableQCoreClass->replace('suggestCSS', $suggestCSS);
$EnableQCoreClass->replace('suggestAction', $suggestAction);
$_SESSION['PageToken3'] = session_id();
$EnableQCoreClass->parse('CheckBoxEdit', 'CheckBoxEditFile');
$EnableQCoreClass->output('CheckBoxEdit');

?>
