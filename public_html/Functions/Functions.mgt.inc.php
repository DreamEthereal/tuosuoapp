<?php
//dezend by http://www.yunlu99.com/
function _getbaseitqtnrange($questionID)
{
	global $DB;
	$_obf_JuYaSU2cf0Xt = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID = \'' . $questionID . '\' AND questionType IN (19,28,20,21,22) ';
	$_obf_Vb8uRRxvBb7GMtBw = $DB->query($_obf_JuYaSU2cf0Xt);
	$_obf_PbZfMVoIa9XvHv4RWA__ = array();

	while ($_obf_f9JfQvXRnVTA = $DB->queryArray($_obf_Vb8uRRxvBb7GMtBw)) {
		$_obf_PbZfMVoIa9XvHv4RWA__[] = $_obf_f9JfQvXRnVTA['questionID'];
	}

	if (count($_obf_PbZfMVoIa9XvHv4RWA__) == 0) {
		return false;
	}

	return implode(',', $_obf_PbZfMVoIa9XvHv4RWA__);
}

function _getbaseitqtnauto($questionID)
{
	global $DB;
	$_obf_JuYaSU2cf0Xt = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID = \'' . $questionID . '\' AND questionType=17 ';
	$_obf_Vb8uRRxvBb7GMtBw = $DB->query($_obf_JuYaSU2cf0Xt);
	$_obf_PbZfMVoIa9XvHv4RWA__ = array();

	while ($_obf_f9JfQvXRnVTA = $DB->queryArray($_obf_Vb8uRRxvBb7GMtBw)) {
		$_obf_PbZfMVoIa9XvHv4RWA__[] = $_obf_f9JfQvXRnVTA['questionID'];
	}

	if (count($_obf_PbZfMVoIa9XvHv4RWA__) == 0) {
		return false;
	}

	return implode(',', $_obf_PbZfMVoIa9XvHv4RWA__);
}

function _getmembergroupslist($administratorsGroupID = 0)
{
	global $DB;
	global $EnableQCoreClass;
	global $lang;
	$_obf_xCnI = ' SELECT administratorsGroupID,administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	if ($administratorsGroupID == '0') {
		$_obf_xRpCL0C6VExjpA__ = '<OPTION value=\'0\' selected>' . $lang['no_group'] . '</OPTION>';
	}
	else {
		$_obf_xRpCL0C6VExjpA__ = '<OPTION value=\'0\'>' . $lang['no_group'] . '</OPTION>';
	}

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ['administratorsGroupID'] == $administratorsGroupID) {
			$_obf_xRpCL0C6VExjpA__ .= '<option value="' . $_obf_9WwQ['administratorsGroupID'] . '" selected>' . $_obf_9WwQ['administratorsGroupName'] . '</option>' . "\n" . '';
		}
		else {
			$_obf_xRpCL0C6VExjpA__ .= '<option value="' . $_obf_9WwQ['administratorsGroupID'] . '">' . $_obf_9WwQ['administratorsGroupName'] . '</option>' . "\n" . '';
		}
	}

	return $_obf_xRpCL0C6VExjpA__;
}

function getusergroupname($userGroupID, $groupType = 0)
{
	global $DB;
	global $EnableQCoreClass;
	global $lang;

	if ($userGroupID == 0) {
		switch ($groupType) {
		case 1:
			$_obf_xcoOpZc__Pxt = $lang['corp_root_node'];
			break;

		case 2:
			$_obf_xcoOpZc__Pxt = $lang['cus_root_node'];
			break;
		}
	}
	else {
		$_obf_xCnI = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $userGroupID . '\' ORDER BY userGroupID ASC ';
		$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

		if ($_obf_9WwQ) {
			$_obf_xcoOpZc__Pxt = $_obf_9WwQ['userGroupName'];
		}
		else {
			$_obf_xcoOpZc__Pxt = $lang['deleted_user'];
		}
	}

	return $_obf_xcoOpZc__Pxt;
}

function _uploadfile($fileType, $SupportUploadFileType, $filePhyPath)
{
	global $EnableQCoreClass;
	global $lang;

	if ($_FILES[$fileType]['name'] != '') {
		$_obf_GhY_xT4LUL71z_oBs_QO = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'phtm', 'asa', 'cer');
		$_obf_A_N_tzzf = explode('.', $_FILES[$fileType]['name']);
		$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
		$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
		if (!is_uploaded_file($_FILES[$fileType]['tmp_name']) || in_array($_obf_MxPKhZcSxB7b, $_obf_GhY_xT4LUL71z_oBs_QO)) {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}

		$_obf_hk_Z__TqtsmTn3k_ = basename($_FILES[$fileType]['name'], '.' . $_obf_MxPKhZcSxB7b);
		$_obf_3ebc97Ag9pBjVFk_ = explode('|', $SupportUploadFileType);

		if (in_array($_obf_MxPKhZcSxB7b, $_obf_3ebc97Ag9pBjVFk_)) {
			$_obf_JTe7jJ4eGW8_ = $_obf_hk_Z__TqtsmTn3k_ . '.' . $_obf_MxPKhZcSxB7b;
			$_obf_pp9pYw__ = $filePhyPath . $_obf_JTe7jJ4eGW8_;

			if (file_exists($_obf_pp9pYw__)) {
				@unlink($_obf_pp9pYw__);
			}

			copy($_FILES[$fileType]['tmp_name'], $_obf_pp9pYw__);
		}
		else {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}
	}

	return true;
}

function _DownloadFile($Dirname, $Filename)
{
	global $EnableQCoreClass;
	ob_start();

	if (!file_exists($Dirname . $Filename)) {
		_showerror('文件错误', '文件错误：您请求下载的文件不存在!');
	}

	$_obf_GWIH1eiKPtmJTkQ_ = file_get_contents($Dirname . $Filename);
	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=' . $Filename);
	echo $_obf_GWIH1eiKPtmJTkQ_;
	exit();
}

function _thumbpicfileupload($picType, $fieldName, $SupportUploadFileType, $picPhyPath, $isEdit = false, $picName = '', $isThumb = true, $width = 120, $height = 160, $theOriFileName = '')
{
	global $EnableQCoreClass;
	global $lang;

	if ($_FILES[$picType]['name'] != '') {
		$_obf_GhY_xT4LUL71z_oBs_QO = array('html', 'htm', 'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'phtml', 'pwml', 'inc', 'asp', 'apsx', 'ascx', 'jsp', 'cfm', 'cfc', 'pl', 'bat', 'exe', 'com', 'dll', 'vbs', 'js', 'reg', 'cgi', 'htaccess', 'asis', 'sh', 'shtml', 'shtm', 'phtm', 'asa', 'cer');
		$_obf_A_N_tzzf = explode('.', $_FILES[$picType]['name']);
		$_obf_1AV8rBmI = count($_obf_A_N_tzzf) - 1;
		$_obf_MxPKhZcSxB7b = strtolower($_obf_A_N_tzzf[$_obf_1AV8rBmI]);
		if (!is_uploaded_file($_FILES[$picType]['tmp_name']) || in_array($_obf_MxPKhZcSxB7b, $_obf_GhY_xT4LUL71z_oBs_QO)) {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}

		$_obf_3ebc97Ag9pBjVFk_ = explode('|', $SupportUploadFileType);

		if (in_array($_obf_MxPKhZcSxB7b, $_obf_3ebc97Ag9pBjVFk_)) {
			if ($picName == '') {
				$picName = date('ymdHis', time()) . rand(1, 999);
			}

			$_obf_JTe7jJ4eGW8_ = $picName . '.' . $_obf_MxPKhZcSxB7b;
			$_obf_pp9pYw__ = $picPhyPath . $_obf_JTe7jJ4eGW8_;

			if (copy($_FILES[$picType]['tmp_name'], $_obf_pp9pYw__)) {
				if ($isThumb == true) {
					makethumb($picPhyPath, $_obf_JTe7jJ4eGW8_, $width, $height);
				}

				$_obf_xCnI = ' ,' . $fieldName . '=\'' . $_obf_JTe7jJ4eGW8_ . '\' ';

				if ($isEdit == true) {
					if (file_exists($picPhyPath . $theOriFileName)) {
						@unlink($picPhyPath . $theOriFileName);
					}

					if (file_exists($picPhyPath . 's_' . $theOriFileName)) {
						@unlink($picPhyPath . 's_' . $theOriFileName);
					}
				}
			}
		}
		else {
			_showerror($lang['upload_error'], $lang['upload_error_type']);
		}
	}

	return $_obf_xCnI;
}

function makethumb($picPhyPath, $picName, $width = 120, $height = 160)
{
	$_obf_abEJnDhEhQ__ = $picPhyPath . 's_' . $picName;
	$_obf_6RYLWQ__ = getimagesize($picPhyPath . $picName);

	switch ($_obf_6RYLWQ__[2]) {
	case 1:
		$_obf_GtM_ = @imagecreatefromgif($picPhyPath . $picName);
		break;

	case 2:
		$_obf_GtM_ = @imagecreatefromjpeg($picPhyPath . $picName);
		break;

	case 3:
		$_obf_GtM_ = @imagecreatefrompng($picPhyPath . $picName);
		break;
	}

	$_obf_60Pb8A__ = imagesx($_obf_GtM_);
	$_obf_oip8Gg__ = imagesy($_obf_GtM_);
	if (($_obf_60Pb8A__ <= $width) && ($_obf_oip8Gg__ <= $height)) {
		$_obf_5HwtWY_v = imagecreatetruecolor($_obf_60Pb8A__, $_obf_oip8Gg__);
		imagecopyresampled($_obf_5HwtWY_v, $_obf_GtM_, 0, 0, 0, 0, $_obf_60Pb8A__, $_obf_oip8Gg__, $_obf_60Pb8A__, $_obf_oip8Gg__);
		imagejpeg($_obf_5HwtWY_v, $_obf_abEJnDhEhQ__);
	}
	else {
		$_obf_8OJ1JwE_ = $_obf_60Pb8A__ / $_obf_oip8Gg__;
		$_obf_LIH53Gpm4vcjOyAc = $width / $height;

		if ($_obf_LIH53Gpm4vcjOyAc <= $_obf_8OJ1JwE_) {
			$_obf_5HwtWY_v = imagecreatetruecolor($width, intval($width / $_obf_8OJ1JwE_));
			imagecopyresampled($_obf_5HwtWY_v, $_obf_GtM_, 0, 0, 0, 0, $width, intval($width / $_obf_8OJ1JwE_), $_obf_60Pb8A__, $_obf_oip8Gg__);
			imagejpeg($_obf_5HwtWY_v, $_obf_abEJnDhEhQ__);
		}

		if ($_obf_8OJ1JwE_ < $_obf_LIH53Gpm4vcjOyAc) {
			$_obf_5HwtWY_v = imagecreatetruecolor(intval($height * $_obf_8OJ1JwE_), $height);
			imagecopyresampled($_obf_5HwtWY_v, $_obf_GtM_, 0, 0, 0, 0, intval($height * $_obf_8OJ1JwE_), $height, $_obf_60Pb8A__, $_obf_oip8Gg__);
			imagejpeg($_obf_5HwtWY_v, $_obf_abEJnDhEhQ__);
		}
	}
}

function getdatasourcesql($dataSource, $surveyID, $roleType = '')
{
	global $DB;
	global $QtnListArray;
	global $_SESSION;
	$DSQL = '';

	if ($roleType != '') {
		$theRoleType = explode('$$$', $roleType);
		$adminRoleType = $theRoleType[0];
		$adminRoleGroupType = $theRoleType[1];
		$adminSameGroupUsers = explode('***', $theRoleType[2]);

		switch ($adminRoleType) {
		case '3':
			$SQL = ' SELECT a.isOnline0View,a.isViewAuthData,a.isDataSource FROM ' . SURVEY_TABLE . ' a WHERE a.surveyID = \'' . $surveyID . '\' ';
			$SSRow = $DB->queryFirstRow($SQL);

			if ($SSRow['isViewAuthData'] == 1) {
				$DSQL .= ' b.authStat IN (1,4) and ';
			}

			if ($SSRow['isOnline0View'] == 1) {
				if ($SSRow['isDataSource'] == 0) {
					if (count($adminSameGroupUsers) != 0) {
						if ($adminRoleGroupType == 1) {
							$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
						}
						else {
							$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.taskID,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
						}
					}
					else {
						$DSQL .= ' b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and ';
					}
				}
				else if (count($adminSameGroupUsers) != 0) {
					if ($adminRoleGroupType == 1) {
						$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
					}
					else {
						$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.taskID,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
					}
				}
				else {
					$DSQL .= ' b.dataSource IN (1,2,3) and ';
				}
			}
			else if ($SSRow['isDataSource'] == 0) {
				if (count($adminSameGroupUsers) != 0) {
					if ($adminRoleGroupType == 1) {
						$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
					}
					else {
						$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.taskID,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
					}
				}
				else {
					$DSQL .= ' 1=0 and ';
				}
			}
			else if (count($adminSameGroupUsers) != 0) {
				if ($adminRoleGroupType == 1) {
					$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
				}
				else {
					$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.taskID,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
				}
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;

		case '7':
			$SQL = ' SELECT a.isOnline0Auth,a.isDataSource FROM ' . SURVEY_TABLE . ' a WHERE a.surveyID = \'' . $surveyID . '\' ';
			$SSRow = $DB->queryFirstRow($SQL);

			if ($SSRow['isDataSource'] == 0) {
				if ($SSRow['isOnline0Auth'] == 1) {
					if (count($adminSameGroupUsers) != 0) {
						$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
					}
					else {
						$DSQL .= ' b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and ';
					}
				}
				else if (count($adminSameGroupUsers) != 0) {
					$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
				}
				else {
					$DSQL .= ' 1=0 and ';
				}
			}
			else if ($SSRow['isOnline0Auth'] == 1) {
				if (count($adminSameGroupUsers) != 0) {
					$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') ) and ';
				}
				else {
					$DSQL .= ' b.dataSource IN (1,2,3) and ';
				}
			}
			else if (count($adminSameGroupUsers) != 0) {
				$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.area,\'' . implode(',', $adminSameGroupUsers) . '\') and ';
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;

		case '1':
		case '2':
		case '5':
			break;

		default:
			if (!isset($dataSource) || ($dataSource == '0')) {
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;
		}
	}
	else {
		switch ($_SESSION['adminRoleType']) {
		case '3':
			$SQL = ' SELECT a.isOnline0View,a.isViewAuthData,a.isDataSource FROM ' . SURVEY_TABLE . ' a WHERE a.surveyID = \'' . $surveyID . '\' ';
			$SSRow = $DB->queryFirstRow($SQL);

			if ($SSRow['isViewAuthData'] == 1) {
				$DSQL .= ' b.authStat IN (1,4) and ';
			}

			if ($SSRow['isOnline0View'] == 1) {
				if ($SSRow['isDataSource'] == 0) {
					if (count($_SESSION['adminSameGroupUsers']) != 0) {
						if ($_SESSION['adminRoleGroupType'] == 1) {
							$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
						}
						else {
							$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.taskID,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
						}
					}
					else {
						$DSQL .= ' b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and ';
					}
				}
				else if (count($_SESSION['adminSameGroupUsers']) != 0) {
					if ($_SESSION['adminRoleGroupType'] == 1) {
						$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
					}
					else {
						$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.taskID,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
					}
				}
				else {
					$DSQL .= ' b.dataSource IN (1,2,3) and ';
				}
			}
			else if ($SSRow['isDataSource'] == 0) {
				if (count($_SESSION['adminSameGroupUsers']) != 0) {
					if ($_SESSION['adminRoleGroupType'] == 1) {
						$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
					}
					else {
						$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.taskID,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
					}
				}
				else {
					$DSQL .= ' 1=0 and ';
				}
			}
			else if (count($_SESSION['adminSameGroupUsers']) != 0) {
				if ($_SESSION['adminRoleGroupType'] == 1) {
					$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
				}
				else {
					$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.taskID,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
				}
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;

		case '7':
			$SQL = ' SELECT a.isOnline0Auth,a.isDataSource FROM ' . SURVEY_TABLE . ' a WHERE a.surveyID = \'' . $surveyID . '\' ';
			$SSRow = $DB->queryFirstRow($SQL);

			if ($SSRow['isDataSource'] == 0) {
				if ($SSRow['isOnline0Auth'] == 1) {
					if (count($_SESSION['adminSameGroupUsers']) != 0) {
						$DSQL .= ' ( b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' OR FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
					}
					else {
						$DSQL .= ' b.ipAddress regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and ';
					}
				}
				else if (count($_SESSION['adminSameGroupUsers']) != 0) {
					$DSQL .= ' b.ipAddress not regexp \'^[0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}[.][0-9]{1,3}$\' and FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
				}
				else {
					$DSQL .= ' 1=0 and ';
				}
			}
			else if ($SSRow['isOnline0Auth'] == 1) {
				if (count($_SESSION['adminSameGroupUsers']) != 0) {
					$DSQL .= ' ( b.dataSource IN (1,2,3) OR FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') ) and ';
				}
				else {
					$DSQL .= ' b.dataSource IN (1,2,3) and ';
				}
			}
			else if (count($_SESSION['adminSameGroupUsers']) != 0) {
				$DSQL .= ' b.dataSource NOT IN (1,2,3) and FIND_IN_SET(b.area,\'' . implode(',', $_SESSION['adminSameGroupUsers']) . '\') and ';
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;

		case '1':
		case '2':
		case '5':
			break;

		default:
			if (!isset($dataSource) || ($dataSource == '0')) {
			}
			else {
				$DSQL .= ' 1=0 and ';
			}

			break;
		}
	}

	if (!isset($dataSource) || ($dataSource == '0')) {
		$DSQL .= ' (b.overFlag != 2 and b.authStat != 2) and ';
	}
	else if ($dataSource == 'all') {
		$DSQL .= ' 1=1 and ';
	}
	else {
		$SQL = ' SELECT fieldsID,optionID,labelID,opertion,queryValue,logicOR,isRadio FROM ' . QUERY_COND_TABLE . ' WHERE queryID = \'' . $dataSource . '\' ORDER BY fieldsID ASC,optionID ASC,labelID ASC ';
		$Result = $DB->query($SQL);
		$recordCount = $DB->_getNumRows($Result);

		if ($recordCount == 0) {
			$DSQL .= ' b.overFlag != 2 ';
			return $DSQL;
		}

		while ($Row = $DB->queryArray($Result)) {
			switch (trim($Row['fieldsID'])) {
			case 't1':
				$theTime = explode(',', $Row['queryValue']);

				if ($theTime[0] != '') {
					$DSQL .= ' b.joinTime >= ' . $theTime[0] . ' and ';
				}

				if ($theTime[1] != '') {
					$DSQL .= ' b.joinTime <= ' . $theTime[1] . ' and ';
				}

				unset($theTime);
				break;

			case 't2':
				if ($Row['queryValue'] != '') {
					$DSQL .= ' b.overFlag IN ( ' . $Row['queryValue'] . ' ) and ';
				}

				break;

			case 't12':
				if ($Row['queryValue'] != '') {
					$DSQL .= ' b.authStat IN ( ' . $Row['queryValue'] . ' ) and ';
				}

				break;

			case 't3':
				$theOverTime = explode(',', $Row['queryValue']);

				if ($theOverTime[0] != '') {
					$DSQL .= ' b.overTime >= ' . $theOverTime[0] . ' and ';
				}

				if ($theOverTime[1] != '') {
					$DSQL .= ' b.overTime <= ' . $theOverTime[1] . ' and ';
				}

				unset($theOverTime);
				break;

			case 't4':
				if ($Row['queryValue'] != '') {
					$DSQL .= ' b.cateID IN ( ' . $Row['queryValue'] . ' ) and ';
				}

				break;

			case 't5':
				if ($Row['queryValue'] != '') {
					$DSQL .= ' b.administratorsGroupID IN ( ' . $Row['queryValue'] . ' ) and ';
				}

				break;

			case 't6':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_1 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			case 't7':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_2 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			case 't8':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_3 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			case 't9':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_4 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			case 't10':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_5 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			case 't11':
				if ($Row['queryValue'] != '') {
					$theValueArray = explode(',', $Row['queryValue']);

					foreach ($theValueArray as $theValue) {
						$theNewValueArray[] = '\'' . $theValue . '\'';
					}

					$DSQL .= ' b.ajaxRtnValue_6 IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
					unset($theNewValueArray);
					unset($theValueArray);
				}

				break;

			default:
				$questionID = $Row['fieldsID'];
				$questionType = $QtnListArray[$questionID]['questionType'];

				switch ($questionType) {
				case '17':
					if ($Row['isRadio'] == 1) {
						$searchQuestionType = 2;
					}
					else {
						$searchQuestionType = 3;
					}

					break;

				case '18':
					if ($Row['isRadio'] == 0) {
						$searchQuestionType = 2;
					}
					else {
						$searchQuestionType = 3;
					}

					break;

				default:
					$searchQuestionType = $questionType;
					break;
				}

				switch ($searchQuestionType) {
				case '1':
				case '2':
				case '24':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
						default:
							$DSQL .= ' b.option_' . $questionID . ' IN ( ' . $Row['queryValue'] . ' ) and ';
							break;

						case '2':
							switch ($Row['logicOR']) {
							case '1':
								$DSQL .= ' b.option_' . $questionID . ' NOT IN ( ' . $Row['queryValue'] . ' ) and ';
								break;

							default:
								$theValueArray = explode(',', $Row['queryValue']);

								if (2 <= count($theValueArray)) {
									$DSQL .= '(';
								}

								$i = 0;

								foreach ($theValueArray as $queryValue) {
									$i++;

									if ($i == count($theValueArray)) {
										$DSQL .= ' b.option_' . $questionID . ' != \'' . $queryValue . '\' ';
									}
									else {
										$DSQL .= ' b.option_' . $questionID . ' != \'' . $queryValue . '\' OR ';
									}
								}

								if (2 <= count($theValueArray)) {
									$DSQL .= ')';
								}

								$DSQL .= ' and ';
								break;
							}

							break;
						}
					}

					break;

				case '4':
				case '12':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
							$DSQL .= ' b.option_' . $questionID . ' = \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '2':
							$DSQL .= ' b.option_' . $questionID . ' < \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '3':
							$DSQL .= ' b.option_' . $questionID . ' <= \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '4':
							$DSQL .= ' b.option_' . $questionID . ' > \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '5':
							$DSQL .= ' b.option_' . $questionID . ' >= \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '6':
							$DSQL .= ' b.option_' . $questionID . ' != \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '7':
						default:
							$DSQL .= ' b.option_' . $questionID . ' LIKE binary \'%' . addslashes($Row['queryValue']) . '%\' and ';
							break;
						}
					}

					break;

				case '30':
					if ($Row['queryValue'] != '') {
						$DSQL .= ' b.option_' . $questionID . ' = \'' . $Row['queryValue'] . '\' and ';
					}

					break;

				case '23':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' = \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '6':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' != \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '7':
						default:
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' LIKE binary \'%' . addslashes($Row['queryValue']) . '%\' and ';
							break;
						}
					}

					break;

				case '13':
					if ($Row['queryValue'] != '') {
						$theValueArray = explode(',', $Row['queryValue']);

						foreach ($theValueArray as $theValue) {
							$theNewValueArray[] = '\'' . $theValue . '\'';
						}

						switch ($Row['opertion']) {
						case '1':
						default:
							$DSQL .= ' b.option_' . $questionID . ' IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
							break;

						case '2':
							switch ($Row['logicOR']) {
							case '1':
								$DSQL .= ' b.option_' . $questionID . ' NOT IN ( ' . implode(',', $theNewValueArray) . ' ) and ';
								break;

							default:
								if (2 <= count($theValueArray)) {
									$DSQL .= '(';
								}

								$i = 0;

								foreach ($theValueArray as $queryValue) {
									$i++;

									if ($i == count($theValueArray)) {
										$DSQL .= ' b.option_' . $questionID . ' != \'' . $queryValue . '\' ';
									}
									else {
										$DSQL .= ' b.option_' . $questionID . ' != \'' . $queryValue . '\' OR ';
									}
								}

								if (2 <= count($theValueArray)) {
									$DSQL .= ')';
								}

								$DSQL .= ' and ';
								break;
							}

							break;
						}

						unset($theNewValueArray);
						unset($theValueArray);
					}

					break;

				case '3':
				case '25':
					if ($Row['queryValue'] != '') {
						$theValueArray = explode(',', $Row['queryValue']);

						if (2 <= count($theValueArray)) {
							$DSQL .= '(';
						}

						$i = 0;

						foreach ($theValueArray as $queryValue) {
							$i++;

							if ($i == count($theValueArray)) {
								switch ($Row['opertion']) {
								case '1':
								default:
									$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '))';
									break;

								case '2':
									$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . ')=0)';
									break;
								}
							}
							else {
								switch ($Row['opertion']) {
								case '1':
								default:
									switch ($Row['logicOR']) {
									case '1':
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . ')) and ';
										break;

									default:
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . ')) or ';
										break;
									}

									break;

								case '2':
									switch ($Row['logicOR']) {
									case '1':
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . ')=0) and ';
										break;

									default:
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . ')=0) or ';
										break;
									}

									break;
								}
							}
						}

						if (2 <= count($theValueArray)) {
							$DSQL .= ')';
						}

						$DSQL .= ' and ';
					}

					break;

				case '7':
				case '28':
					if ($Row['queryValue'] != '') {
						$theValueArray = explode(',', $Row['queryValue']);

						if (2 <= count($theValueArray)) {
							$DSQL .= '(';
						}

						$i = 0;

						foreach ($theValueArray as $queryValue) {
							$i++;

							if ($i == count($theValueArray)) {
								switch ($Row['opertion']) {
								case '1':
								default:
									$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . '))';
									break;

								case '2':
									$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . ')=0)';
									break;
								}
							}
							else {
								switch ($Row['opertion']) {
								case '1':
								default:
									switch ($Row['logicOR']) {
									case '1':
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . ')) and ';
										break;

									default:
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . ')) or ';
										break;
									}

									break;

								case '2':
									switch ($Row['logicOR']) {
									case '1':
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . ')=0) and ';
										break;

									default:
										$DSQL .= '(find_in_set(' . $queryValue . ',b.option_' . $questionID . '_' . $Row['optionID'] . ')=0) or ';
										break;
									}

									break;
								}
							}
						}

						if (2 <= count($theValueArray)) {
							$DSQL .= ')';
						}

						$DSQL .= ' and ';
					}

					break;

				case '6':
				case '19':
				case '31':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
						default:
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' IN ( ' . $Row['queryValue'] . ' ) and ';
							break;

						case '2':
							switch ($Row['logicOR']) {
							case '1':
								$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' NOT IN ( ' . $Row['queryValue'] . ' ) and ';
								break;

							default:
								$theValueArray = explode(',', $Row['queryValue']);

								if (2 <= count($theValueArray)) {
									$DSQL .= '(';
								}

								$i = 0;

								foreach ($theValueArray as $queryValue) {
									$i++;

									if ($i == count($theValueArray)) {
										$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' != \'' . $queryValue . '\' ';
									}
									else {
										$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' != \'' . $queryValue . '\' OR ';
									}
								}

								if (2 <= count($theValueArray)) {
									$DSQL .= ')';
								}

								$DSQL .= ' and ';
								break;
							}

							break;
						}
					}

					break;

				case '10':
				case '15':
				case '20':
				case '21':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
						default:
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' = \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '2':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' < \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '3':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' <= \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '4':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' > \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '5':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' >= \'' . addslashes($Row['queryValue']) . '\' and ';
							break;

						case '6':
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . ' != \'' . addslashes($Row['queryValue']) . '\' and ';
							break;
						}
					}

					break;

				case '26':
					if ($Row['queryValue'] != '') {
						switch ($Row['opertion']) {
						case '1':
						default:
							$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . '_' . $Row['labelID'] . ' IN ( ' . $Row['queryValue'] . ' ) and ';
							break;

						case '2':
							switch ($Row['logicOR']) {
							case '1':
								$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . '_' . $Row['labelID'] . ' NOT IN ( ' . $Row['queryValue'] . ' ) and ';
								break;

							default:
								$theValueArray = explode(',', $Row['queryValue']);

								if (2 <= count($theValueArray)) {
									$DSQL .= '(';
								}

								$i = 0;

								foreach ($theValueArray as $queryValue) {
									$i++;

									if ($i == count($theValueArray)) {
										$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . '_' . $Row['labelID'] . ' != \'' . $queryValue . '\' ';
									}
									else {
										$DSQL .= ' b.option_' . $questionID . '_' . $Row['optionID'] . '_' . $Row['labelID'] . ' != \'' . $queryValue . '\' OR ';
									}
								}

								if (2 <= count($theValueArray)) {
									$DSQL .= ')';
								}

								$DSQL .= ' and ';
								break;
							}

							break;
						}
					}

					break;
				}

				break;
			}
		}
	}

	return substr($DSQL, 0, -4);
}

function getdataauth($surveyID, $responseID, $R_Row, $S_Row)
{
	global $DB;
	global $table_prefix;
	$_obf_BI5gzwoSgmAY15oUZLzKzw__ = $_obf_xANboAf2ra7q8LHNj_1n_w__ = $_obf_109gDkV2H_SAtzXL_jHnXQ__ = 0;

	if (!$S_Row) {
		$_obf_xCnI = ' SELECT isOnline0View,isOnline0Auth,isViewAuthData,isDeleteData,isModiData FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $surveyID . '\' ';
		$S_Row = $DB->queryFirstRow($_obf_xCnI);
	}

	if (!$R_Row) {
		$_obf_xCnI = ' SELECT administratorsName,ipAddress,area,taskID,overFlag,authStat,version,adminID,appStat FROM ' . $table_prefix . 'response_' . $surveyID . ' WHERE responseID =\'' . $responseID . '\' ';
		$R_Row = $DB->queryFirstRow($_obf_xCnI);
	}

	switch ($_SESSION['adminRoleType']) {
	case '4':
		if ($R_Row['area'] == $_SESSION['administratorsName']) {
			$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;

			if ($S_Row['isModiData'] == 1) {
				$_obf_xANboAf2ra7q8LHNj_1n_w__ = 0;
			}
			else if (in_array($R_Row['authStat'], array(0, 2))) {
				if ($R_Row['overFlag'] != 2) {
					$_obf_xANboAf2ra7q8LHNj_1n_w__ = 1;
				}

				if ($S_Row['isDeleteData'] != 1) {
					$_obf_109gDkV2H_SAtzXL_jHnXQ__ = 1;
				}
			}
		}

		break;

	case '3':
		if (eregi('([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})', $R_Row['ipAddress'])) {
			if ($S_Row['isOnline0View'] == 1) {
				if ($S_Row['isViewAuthData'] == 1) {
					if ($R_Row['authStat'] == 1) {
						$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
					}
				}
				else {
					$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
				}
			}
		}
		else if ($_SESSION['adminRoleGroupType'] == 1) {
			if (in_array($R_Row['area'], $_SESSION['adminSameGroupUsers'])) {
				if ($S_Row['isViewAuthData'] == 1) {
					if ($R_Row['authStat'] == 1) {
						$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
					}
				}
				else {
					$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
				}
			}
		}
		else if (in_array($R_Row['taskID'], $_SESSION['adminSameGroupUsers'])) {
			if ($S_Row['isViewAuthData'] == 1) {
				if ($R_Row['authStat'] == 1) {
					$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
				}
			}
			else {
				$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
			}
		}

		break;

	case '7':
		if (eregi('([0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3})', $R_Row['ipAddress'])) {
			if ($S_Row['isOnline0Auth'] == 1) {
				$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
			}
		}
		else if (in_array($R_Row['area'], $_SESSION['adminSameGroupUsers'])) {
			$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
		}

		break;

	default:
		$_obf_BI5gzwoSgmAY15oUZLzKzw__ = 1;
		$_obf_109gDkV2H_SAtzXL_jHnXQ__ = 1;

		switch ($R_Row['authStat']) {
		case '0':
			if ($R_Row['overFlag'] == 0) {
				$_obf_xANboAf2ra7q8LHNj_1n_w__ = 1;
			}

			break;

		case '1':
			if (($R_Row['appStat'] != 3) && ($R_Row['overFlag'] != 2)) {
				$_obf_xANboAf2ra7q8LHNj_1n_w__ = 1;
			}

			break;

		case '2':
			if ($R_Row['overFlag'] != 2) {
				$_obf_xANboAf2ra7q8LHNj_1n_w__ = 1;
			}

			break;

		case '3':
			$_obf_xANboAf2ra7q8LHNj_1n_w__ = 0;
			break;
		}

		break;
	}

	return $_obf_BI5gzwoSgmAY15oUZLzKzw__ . '$$$' . $_obf_xANboAf2ra7q8LHNj_1n_w__ . '$$$' . $_obf_109gDkV2H_SAtzXL_jHnXQ__;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.string.inc.php';

?>
