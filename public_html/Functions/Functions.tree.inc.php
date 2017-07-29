<?php
//dezend by http://www.yunlu99.com/
function getbyusername($userID)
{
	global $DB;
	global $lang;

	if ($userID == 0) {
		return 'No Data';
	}

	$_obf_zbjlCQm958tf = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =\'' . $userID . '\' ';
	$_obf_HEogMbr58fNV = $DB->queryFirstRow($_obf_zbjlCQm958tf);

	if (!$_obf_HEogMbr58fNV) {
		return $lang['deleted_user'];
	}
	else {
		return $_obf_HEogMbr58fNV['administratorsName'];
	}
}

function htmltoxml($htmlName)
{
	$htmlName = str_replace(array('&', '"', '<', '>', '\''), array('&amp;', '&quot;', '&lt;', '&gt;', '&apos;'), $htmlName);
	return trim($htmlName);
}

function _getuserallname($userName, $userGroupID, $groupType)
{
	global $DB;
	global $lang;
	$str = '';

	if ($userGroupID == 0) {
		switch ($groupType) {
		case 1:
			$str .= $lang['corp_root_node'] . '\\';
			break;

		case 2:
			$str .= $lang['cus_root_node'] . '\\';
			break;
		}
	}
	else {
		$SQL = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $userGroupID . '\' ';
		$Row = $DB->queryFirstRow($SQL);
		if (!$Row || (trim($Row['absPath']) == '')) {
			return 'User Node Error';
		}

		$theGroupFullPath = $Row['absPath'] . '-' . $userGroupID;
		$theAbsPathArray = explode('-', $theGroupFullPath);
		$theNodeName = array();
		$nSQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID IN (' . implode(',', $theAbsPathArray) . ') ';
		$nResult = $DB->query($nSQL);

		while ($nRow = $DB->queryArray($nResult)) {
			$theNodeName[$nRow['userGroupID']] = htmltoxml($nRow['userGroupName']);
		}

		foreach ($theAbsPathArray as $absPathId) {
			if ($absPathId == 0) {
				switch ($groupType) {
				case 1:
					$str .= $lang['corp_root_node'] . '\\';
					break;

				case 2:
					$str .= $lang['cus_root_node'] . '\\';
					break;
				}
			}
			else {
				$str .= $theNodeName[$absPathId] . '\\';
			}
		}

		unset($theNodeName);
	}

	return $str . htmltoxml($userName);
}

function _getnodeallname($absPath, $nodeName, $groupType)
{
	global $DB;
	global $lang;

	if (trim($absPath) == '') {
		return 'Node Path Error';
	}

	$str = '';
	$theAbsPathArray = explode('-', trim($absPath));
	$theNodeName = array();
	$nSQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID IN (' . implode(',', $theAbsPathArray) . ') ';
	$nResult = $DB->query($nSQL);

	while ($nRow = $DB->queryArray($nResult)) {
		$theNodeName[$nRow['userGroupID']] = htmltoxml($nRow['userGroupName']);
	}

	foreach ($theAbsPathArray as $absPathId) {
		if ($absPathId == 0) {
			switch ($groupType) {
			case 1:
				$str .= $lang['corp_root_node'] . '\\';
				break;

			case 2:
				$str .= $lang['cus_root_node'] . '\\';
				break;
			}
		}
		else {
			$str .= $theNodeName[$absPathId] . '\\';
		}
	}

	unset($theNodeName);
	return $str . htmltoxml($nodeName);
}

function _getupgroupnode($groupId = 0)
{
	global $DB;
	$_obf_fQ1ZXVKmwON9Kalx23k_ = array();
	$_obf_xCnI = ' SELECT absPath FROM ' . USERGROUP_TABLE . ' WHERE userGroupID = \'' . $groupId . '\' ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

	if ($_obf_9WwQ['absPath'] != '') {
		$_obf_fQ1ZXVKmwON9Kalx23k_ = explode('-', $_obf_9WwQ['absPath']);
	}

	return $_obf_fQ1ZXVKmwON9Kalx23k_;
}


?>
