<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$nodesName = _getnodeallname($Row['absPath'], $Row['userGroupName'], $Row['groupType']);
$EnableQCoreClass->replace('userGroupName', $nodesName);

if ($Row['isLeaf'] == 1) {
	$SQL = ' SELECT indexID,indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND taskID = \'' . $Row['userGroupID'] . '\' ORDER BY indexID ASC ';
	$rResult = $DB->query($SQL);
	$theIndexValue = array();

	while ($rRow = $DB->queryArray($rResult)) {
		$theIndexValue[$rRow['indexID']] = $rRow['indexValue'];
	}

	if (($Sur_G_Row['isRateIndex'] == 1) && ($theIndexValue[0] != '')) {
		$EnableQCoreClass->replace('surveyGrade', $theIndexValue[0] . '%');
	}
	else {
		$EnableQCoreClass->replace('surveyGrade', $theIndexValue[0]);
	}

	$indexValueList = '';

	foreach ($surveyIndexName as $indexID) {
		if ($theIndexValue[$indexID] != '-999') {
			if (($Sur_G_Row['isRateIndex'] == 1) && ($theIndexValue[$indexID] != '')) {
				$thisIndexValue = $theIndexValue[$indexID] . '%';
			}
			else {
				$thisIndexValue = $theIndexValue[$indexID];
			}
		}
		else {
			$thisIndexValue = 'NA';
		}

		if (in_array($indexID, $theTwoLevelIndex)) {
			$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;<a href="javascript:void(0)" onclick="javascript:showPopWin(\'' . $thisProg . '&Does=ViewIndexDetail&indexID=' . $indexID . '&taskID=' . $Row['userGroupID'] . '\', 860, 470, null, null,\'查看对应指标数据详细\')">' . $thisIndexValue . '</a></b></td>';
		}
		else {
			$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;' . $thisIndexValue . '</b></td>';
		}
	}

	$EnableQCoreClass->replace('indexValueList', $indexValueList);
}
else {
	$theSonSQL = '( concat(\'-\',absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR userGroupID = \'' . $Row['userGroupID'] . '\') ';
	$cSQL = ' SELECT userGroupID FROM ' . USERGROUP_TABLE . ' WHERE ' . $theSonSQL . ' ORDER BY userGroupID ASC ';
	$cResult = $DB->query($cSQL);
	$theTaskArray = array();

	while ($cRow = $DB->queryArray($cResult)) {
		$theTaskArray[] = $cRow['userGroupID'];
	}

	$SQL = ' SELECT indexID,indexValue FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND taskID IN(' . implode(',', $theTaskArray) . ') ORDER BY indexID ASC ';
	$rResult = $DB->query($SQL);
	$theIndexValue = array();
	$theIndexNA = array();

	while ($rRow = $DB->queryArray($rResult)) {
		if ($rRow['indexValue'] == '-999') {
			$theIndexNA[$rRow['indexID']] += 1;
		}
		else {
			$theIndexValue[$rRow['indexID']] += $rRow['indexValue'];
		}
	}

	$hSQL = ' SELECT COUNT(*) as theDataNum FROM ' . SURVEYINDEXRESULT_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' AND taskID IN(' . implode(',', $theTaskArray) . ') AND indexID =0 ';
	$hRow = $DB->queryFirstRow($hSQL);

	if ($hRow['theDataNum'] == 0) {
		$EnableQCoreClass->replace('surveyGrade', '');
		$indexValueList = '';

		foreach ($surveyIndexName as $indexID) {
			$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;</b></td>';
		}

		$EnableQCoreClass->replace('indexValueList', $indexValueList);
	}
	else {
		$surveyGrade = number_format($theIndexValue[0] / $hRow['theDataNum'], 2);
		if (($Sur_G_Row['isRateIndex'] == 1) && ($surveyGrade != '')) {
			$surveyGrade .= '%';
		}

		$EnableQCoreClass->replace('surveyGrade', $surveyGrade);
		$indexValueList = '';

		foreach ($surveyIndexName as $indexID) {
			$validLeaf = $hRow['theDataNum'] - $theIndexNA[$indexID];
			$thisIndexValue = number_format($theIndexValue[$indexID] / $validLeaf, 2);
			if (($Sur_G_Row['isRateIndex'] == 1) && ($thisIndexValue != '')) {
				$thisIndexValue .= '%';
			}

			$indexValueList .= '<td align=center valign=center width=70 class=classtd><b>&nbsp;' . $thisIndexValue . '</b></td>';
		}

		$EnableQCoreClass->replace('indexValueList', $indexValueList);
	}
}

$EnableQCoreClass->parse('dim', 'DIM', true);

?>
