<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Lang/CN/Lang.fore.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.conm.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('3');
$language = 'cn';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$_GET['responseID'] = (int) $_GET['responseID'];
$SQL = ' SELECT * FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$S_Row = $DB->queryFirstRow($SQL);
$theSQL = ' SELECT * FROM ' . $table_prefix . 'response_' . $S_Row['surveyID'] . ' WHERE responseID =\'' . $_GET['responseID'] . '\' LIMIT 0,1 ';
$R_Row = $DB->queryFirstRow($theSQL);
$isModiDataFlag = 1;

if ($_POST['Action'] == 'AddAppQtnDataSubmit') {
	$SQL = ' SELECT authStat,appStat,version FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' WHERE responseID=\'' . $_POST['responseID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	if (($Row['version'] != 0) && ($Row['version'] != $_SESSION['administratorsID'])) {
		_showerror($lang['auth_error'], $lang['con_action_error']);
	}

	if ($Row['authStat'] != 1) {
		_showerror('状态错误', '状态错误：该回复数据目前仍然未被审核通过，尚不能进行申诉');
	}

	if (($Row['appStat'] != 0) && ($Row['appStat'] != 2)) {
		_showerror('状态错误', '状态错误：该回复数据目前可能已经被申诉或被申诉处理过，不能再进行申诉');
	}

	if ($Row['version'] != 0) {
		if ($Row['version'] == $_SESSION['administratorsID']) {
			$SQL = ' SELECT traceTime FROM ' . DATA_TRACE_TABLE . ' WHERE responseID=\'' . $_POST['responseID'] . '\' AND surveyID = \'' . $_POST['surveyID'] . '\' AND questionID = \'' . $_POST['questionID'] . '\' AND isAppData =1 ORDER BY traceTime DESC LIMIT 1 ';
			$hRow = $DB->queryFirstRow($SQL);

			if (!$hRow) {
				$hRow = false;
			}
			else {
				$nSQL = ' SELECT taskTime FROM ' . DATA_TASK_TABLE . ' WHERE responseID=\'' . $_POST['responseID'] . '\' AND surveyID = \'' . $_POST['surveyID'] . '\' AND authStat =1 AND appStat= 2 AND taskTime >= \'' . $hRow['traceTime'] . '\' ORDER BY taskTime DESC LIMIT 1 ';
				$nRow = $DB->queryFirstRow($nSQL);

				if ($nRow) {
					$hRow = false;
				}
				else {
					$hRow = true;
				}
			}
		}
		else {
			$hRow = false;
		}
	}
	else {
		$hRow = false;
	}

	if ($hRow) {
		_showerror('状态错误', '状态错误：该问题目前可能已经提交过申诉信息，无法再提起申诉');
	}

	$theForbidAppId = explode(',', $S_Row['forbidAppId']);

	if (in_array($_POST['questionID'], $theForbidAppId)) {
		_showerror('权限错误', '权限错误：该问题目前已被系统管理员设定禁止提请申诉，您的申诉提请动作无法继续');
	}

	$this_fields_list = '';
	$this_fileds_type = '';

	foreach ($QtnListArray as $questionID => $theQtnArray) {
		if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '12')) {
			$surveyID = $_POST['surveyID'];
			$ModuleName = $Module[$theQtnArray['questionType']];
			require ROOT_PATH . 'PlugIn/' . $ModuleName . '/Admin/' . $ModuleName . '.fields.inc.php';
		}
	}

	$survey_fields_name = explode('|', substr($this_fields_list, 0, -1));
	$survey_fields_type = explode('|', substr($this_fileds_type, 0, -1));
	$tmpFilePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';

	if ($S_Row['custDataPath'] == '') {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/response_' . $_POST['surveyID'] . '/' . date('Y-m', $R_Row['joinTime']) . '/' . date('d', $R_Row['joinTime']) . '/';
	}
	else {
		$filePhyPath = $Config['absolutenessPath'] . '/' . $Config['dataDirectory'] . '/user/' . $S_Row['custDataPath'] . '/';
	}

	createdir($filePhyPath);

	if ($_POST['thisFiles'] != '') {
		$thisPageFileUploadList = substr($_POST['thisFiles'], 0, -1);
		$thisPageFileUpload = explode('|', $thisPageFileUploadList);

		foreach ($thisPageFileUpload as $theFiles) {
			$theUploadFileName = trim($_POST[$theFiles]);

			if ($theUploadFileName != '') {
				if (file_exists($tmpFilePhyPath . $theUploadFileName)) {
					if (copy($tmpFilePhyPath . $theUploadFileName, $filePhyPath . $theUploadFileName)) {
						@unlink($tmpFilePhyPath . $theUploadFileName);
					}
				}
			}

			if ($theUploadFileName != taddslashes($R_Row[$theFiles])) {
				if (trim($_POST['evidence']) != '') {
					if (file_exists($tmpFilePhyPath . trim($_POST['evidence']))) {
						if (copy($tmpFilePhyPath . trim($_POST['evidence']), $filePhyPath . trim($_POST['evidence']))) {
							@unlink($tmpFilePhyPath . trim($_POST['evidence']));
						}
					}
				}

				$theFieldsArray = explode('_', $theFiles);
				$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_POST['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $theFiles . '\',oriValue=\'' . addslashes($R_Row[$theFiles]) . '\',updateValue=\'' . $theUploadFileName . '\',isAppData =1,traceTime=\'' . time() . '\',evidence=\'' . $_POST['evidence'] . '\',reason=\'' . $_POST['reason'] . '\' ';
				$DB->query($uSQL);
				unset($theFieldsArray);
				$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET version = \'' . $_SESSION['administratorsID'] . '\' WHERE responseID=\'' . $_POST['responseID'] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	$thisSurveyFieldsList = substr(base64_decode($_POST['thisFields']), 0, -1);
	$thisSurveyFields = explode('|', $thisSurveyFieldsList);
	if ((base64_decode($_POST['thisFields']) == '') && ($_POST['thisFiles'] == '')) {
	}
	else if (base64_decode($_POST['thisFields']) != '') {
		foreach ($thisSurveyFields as $surveyFields) {
			if (is_array($_POST[$surveyFields])) {
				asort($_POST[$surveyFields]);
				$qtnUpdateValue = implode(',', qhtmlspecialchars($_POST[$surveyFields]));
			}
			else {
				$qtnUpdateValue = qhtmlspecialchars($_POST[$surveyFields]);
			}

			if ($_POST[$surveyFields] == '') {
				switch ($survey_fields_type[array_search($surveyFields, $survey_fields_name)]) {
				case 'int':
					$qtnUpdateValue = '0';
					break;

				case 'float':
					$qtnUpdateValue = '0.00';
					break;

				default:
					$qtnUpdateValue = '';
					break;
				}
			}

			if ($qtnUpdateValue != taddslashes($R_Row[$surveyFields])) {
				if (trim($_POST['evidence']) != '') {
					if (file_exists($tmpFilePhyPath . trim($_POST['evidence']))) {
						if (copy($tmpFilePhyPath . trim($_POST['evidence']), $filePhyPath . trim($_POST['evidence']))) {
							@unlink($tmpFilePhyPath . trim($_POST['evidence']));
						}
					}
				}

				$theFieldsArray = explode('_', $surveyFields);
				$uSQL = ' INSERT INTO ' . DATA_TRACE_TABLE . ' SET surveyID=\'' . $_POST['surveyID'] . '\',responseID=\'' . $_POST['responseID'] . '\',administratorsID=\'' . $_SESSION['administratorsID'] . '\',questionID=\'' . $theFieldsArray[1] . '\',varName=\'' . $surveyFields . '\',oriValue=\'' . addslashes($R_Row[$surveyFields]) . '\',updateValue=\'' . $qtnUpdateValue . '\',isAppData =1,traceTime=\'' . time() . '\',evidence=\'' . $_POST['evidence'] . '\',reason=\'' . $_POST['reason'] . '\' ';
				$DB->query($uSQL);
				unset($theFieldsArray);
				$SQL = ' UPDATE ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' SET version = \'' . $_SESSION['administratorsID'] . '\' WHERE responseID=\'' . $_POST['responseID'] . '\' ';
				$DB->query($SQL);
			}
		}
	}

	unset($survey_fields_name);
	unset($survey_fields_type);
	writetolog('提交回复数据申诉明细');
	_showmessage('提交回复数据申诉明细', true);
	exit();
}

$thePageSurveyID = $S_Row['surveyID'];
$surveyID = $S_Row['surveyID'];
$theActionPage = 1;
$theHaveRatingSlider = false;
$theHaveDatePicker = false;
$theMgtFunc = 0;
$theResponseID = $_GET['responseID'];
$theHaveFileUpload = false;
$theHaveFileCascade = false;
$thisPageStep = 0;
$ShowSurveyPageFile = 'ShowSurvey' . $thisPageStep . 'PageFile';
$ShowSurveyPage = 'ShowSurvey' . $thisPageStep . 'Page';
$ShowSurveyFile = 'ShowSurvey' . $thisPageStep . 'File';
$question = 'question' . $thisPageStep;
$EnableQCoreClass->setTemplateFile($ShowSurveyPageFile, 'ResultAppQtnData.html');
$EnableQCoreClass->replace('surveyID', $S_Row['surveyID']);
$EnableQCoreClass->replace('isPublic', $S_Row['isPublic']);
$EnableQCoreClass->replace('responseID', $R_Row['responseID']);
$EnableQCoreClass->replace('createDate', $R_Row['joinTime']);
$check_survey_form_list = '';
$check_survey_conditions_list = '';
$this_fields_list = '';
$this_file_list = '';
$this_size_list = '';
$this_hidden_list = '';
$this_check_list = '';
$survey_quota_list = '';
$survey_empty_list = '';
$questionID = $_GET['questionID'];
$EnableQCoreClass->replace('questionID', $questionID);
$isHiddenFields = true;
$ModuleName = $Module[$QtnListArray[$questionID]['questionType']];
require ROOT_PATH . 'PlugIn/' . $ModuleName . '/' . $ModuleName . '.php';

if ($QtnListArray[$questionID]['questionType'] == '11') {
	$EnableQCoreClass->replace('isUploadType', 'none');
	$EnableQCoreClass->replace('no_fileUpload_include', '');
}
else {
	$EnableQCoreClass->replace('isUploadType', '');
	$no_fileUpload_include = '<link href="CSS/FileUpload.css" rel="stylesheet" type="text/css"/>';
	$no_fileUpload_include .= '<script type="text/javascript" src="JS/Swfupload.js.php"></script>';
	$no_fileUpload_include .= '<script type="text/javascript" src="JS/Swfqueue.js.php"></script>';
	$no_fileUpload_include .= '<script type="text/javascript" src="JS/Swfobject.js.php"></script>';
	$no_fileUpload_include .= '<script type="text/javascript" src="JS/FileProgress.js.php"></script>';
	$no_fileUpload_include .= '<script type="text/javascript" src="JS/Swfhandlers.js.php"></script>';
	$EnableQCoreClass->replace('no_fileUpload_include', $no_fileUpload_include);
}

$EnableQCoreClass->replace('thisFields', base64_encode($this_fields_list));
$EnableQCoreClass->replace('thisFiles', $this_file_list);
$EnableQCoreClass->replace('thisSizes', $this_size_list);
$EnableQCoreClass->replace('thisHidden', $this_hidden_list);
$EnableQCoreClass->replace('allHidden', $this_hidden_list);
$EnableQCoreClass->replace('allFields', base64_encode($this_fields_list . $this_file_list));
$EnableQCoreClass->replace('thisCheck', $this_check_list);
$EnableQCoreClass->replace('check_survey_form_list', $check_survey_form_list);
$EnableQCoreClass->replace('check_survey_conditions_list', $check_survey_conditions_list);
$EnableQCoreClass->replace('survey_empty_list', $survey_empty_list);
$survey_fields_name = explode('|', substr($this_fields_list, 0, -1));
$hiddenFields = '';

foreach ($valueLogicQtnList as $lastFields) {
	if (!in_array($lastFields, $survey_fields_name)) {
		if (is_array($R_Row[$lastFields]) && !empty($R_Row[$lastFields])) {
			$lastFieldsValue = implode(',', $R_Row[$lastFields]);
		}
		else {
			$lastFieldsValue = $R_Row[$lastFields];
		}

		$hiddenFields .= '<input name="' . $lastFields . '" id="' . $lastFields . '" type="hidden" value="' . $lastFieldsValue . '">' . "\n" . '		';
	}
}

$EnableQCoreClass->replace('hiddenFields', $hiddenFields);
$EnableQCoreClass->replace('session_id', session_id());
$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

if ($POST_MAX_SIZE) {
	$thePostMaxSize = (int) ($multiplier * (int) $POST_MAX_SIZE) / 1048576;
	$EnableQCoreClass->replace('maxSize', $thePostMaxSize);
}
else {
	$EnableQCoreClass->replace('maxSize', 2);
}

$EnableQCoreClass->replace('allowType', '*.zip;*.rar;*.jpg;*.jpeg;*.png;*.gif;');
$SurveyPage = $EnableQCoreClass->parse($ShowSurveyPage, $ShowSurveyPageFile);
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -22);
$SurveyPage = str_replace($All_Path, '', $SurveyPage);
$SurveyPage = str_replace('CSS/', '../CSS/', $SurveyPage);
$SurveyPage = str_replace('JS/', '../JS/', $SurveyPage);
$SurveyPage = str_replace('Images/', '../Images/', $SurveyPage);
$SurveyPage = str_replace('PerUserData/', '../PerUserData/', $SurveyPage);

if ($S_Row['lang'] == 'en') {
	$SurveyPage = preg_replace('\'<span[^>]*?class=notes>\\[.*?\\].*?\\/span>\'si', '', $SurveyPage);
}

echo $SurveyPage;
exit();

?>
