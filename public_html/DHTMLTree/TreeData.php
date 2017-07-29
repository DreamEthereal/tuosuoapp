<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.tree.inc.php';
_checkroletype('1|5|6');
$pId = '0';

if (array_key_exists('id', $_REQUEST)) {
	$pId = $_REQUEST['id'];
}

if (($pId == NULL) || ($pId == '')) {
	$pId = '0';
}

switch ($_SESSION['adminRoleType']) {
case 1:
case 6:
	if (($pId == '0') || ($pId == 'root_0')) {
		switch ($_SESSION['adminRoleType']) {
		case 1:
			$hSQL = ' SELECT COUNT(*) as totalType1UserNum FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin !=0 AND groupType =1 LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$cSQL = ' SELECT COUNT(*) as totalType2UserNum FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin !=0 AND groupType =2 LIMIT 1 ';
			$cRow = $DB->queryFirstRow($cSQL);
			break;

		case 6:
			$hSQL = ' SELECT COUNT(*) as totalType1UserNum FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin NOT IN (0,1,6) AND groupType =1 LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$cSQL = ' SELECT COUNT(*) as totalType2UserNum FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin NOT IN (0,1,6) AND groupType =2 LIMIT 1 ';
			$cRow = $DB->queryFirstRow($cSQL);
			break;
		}

		$totalUserNum = $hRow['totalType1UserNum'] + $cRow['totalType2UserNum'];
		$treeJOSN = '[';
		$treeJOSN .= '{id:\'root_0\',pId:0,name:\'<b>' . iconv('gbk', 'UTF-8', '用户') . '</b>(' . $totalUserNum . ')\',open:true,isParent:true,url:\'ShowUserList.php\',target:\'theIframe\'},';
		$treeJOSN .= '{id:\'corp_0\',pId:\'root_0\',name:\'<b>' . iconv('gbk', 'UTF-8', '会员') . '</b>(' . $hRow['totalType1UserNum'] . ')\',open:true,isParent:true,url:\'ShowGroupUserList.php?groupType=1&groupId=0\',target:\'theIframe\'},';
		$treeJOSN .= '{id:\'customer_0\',pId:\'root_0\',name:\'<b>' . iconv('gbk', 'UTF-8', '项目') . '</b>(' . $cRow['totalType2UserNum'] . ')\',open:true,isParent:true,url:\'ShowGroupUserList.php?groupType=2&groupId=0\',target:\'theIframe\'},';
		$SQL = ' SELECT userGroupID,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE groupType=1 AND fatherId = \'0\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$hSQL = ' SELECT COUNT(*) as recNum FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['userGroupID'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$isParent = ($hRow['recNum'] != 0 ? 'true' : 'false');
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';

			switch ($_SESSION['adminRoleType']) {
			case 1:
				$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin !=0 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
				break;

			case 6:
				$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
				break;
			}

			$hRow = $DB->queryFirstRow($hSQL);
			$treeJOSN .= '{id:\'corp_' . $Row['userGroupID'] . '\',pId:\'corp_0\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=1&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
		}

		$SQL = ' SELECT userGroupID,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE groupType=2 AND fatherId = \'0\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$hSQL = ' SELECT COUNT(*) as recNum FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['userGroupID'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$isParent = ($hRow['recNum'] != 0 ? 'true' : 'false');
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';
			$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =3 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$treeJOSN .= '{id:\'customer_' . $Row['userGroupID'] . '\',pId:\'customer_0\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=2&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
		}

		$treeJOSN = substr($treeJOSN, 0, -1);
		$treeJOSN .= ']';
	}
	else {
		$theNode = explode('_', $pId);

		switch ($theNode[0]) {
		case 'corp':
		default:
			$theNodeType = 1;
			break;

		case 'customer':
			$theNodeType = 2;
			break;
		}

		$theNodeId = intval($theNode[1]);
		$SQL = ' SELECT userGroupID,userGroupName,groupType FROM ' . USERGROUP_TABLE . ' WHERE groupType =\'' . $theNodeType . '\' AND fatherId = \'' . $theNodeId . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);
		$nodeNum = $DB->_getNumRows($Result);
		$treeJOSN = '[';

		while ($Row = $DB->queryArray($Result)) {
			$hSQL = ' SELECT COUNT(*) as recNum FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['userGroupID'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$isParent = ($hRow['recNum'] != 0 ? 'true' : 'false');

			switch ($Row['groupType']) {
			case '1':
				$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';

				switch ($_SESSION['adminRoleType']) {
				case 1:
					$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin !=0 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
					break;

				case 6:
					$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
					break;
				}

				$hRow = $DB->queryFirstRow($hSQL);
				$treeJOSN .= '{id:\'corp_' . $Row['userGroupID'] . '\',pId:\'corp_' . $theNodeId . '\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=1&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
				break;

			case '2':
				$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';
				$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin =3 AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
				$hRow = $DB->queryFirstRow($hSQL);
				$treeJOSN .= '{id:\'customer_' . $Row['userGroupID'] . '\',pId:\'customer_' . $theNodeId . '\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=2&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
				break;

			default:
				break;
			}
		}

		if (0 < $nodeNum) {
			$treeJOSN = substr($treeJOSN, 0, -1);
		}

		$treeJOSN .= ']';
	}

	break;

case 5:
	if (($pId == '0') || ($pId == 'root_0')) {
		$treeJOSN = '[';

		if ($_SESSION['adminRoleGroupID'] == 0) {
			$hSQL = ' SELECT COUNT(*) as totalUserNum FROM ' . ADMINISTRATORS_TABLE . '  WHERE isAdmin NOT IN (0,1,6) AND groupType =1 LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$treeJOSN .= '{id:\'root_0\',pId:0,name:\'<b>' . iconv('gbk', 'UTF-8', '用户') . '</b>(' . $hRow['totalUserNum'] . ')\',open:true,isParent:true,url:\'ShowUserList.php\',target:\'theIframe\'},';
			$treeJOSN .= '{id:\'corp_0\',pId:\'root_0\',name:\'<b>' . iconv('gbk', 'UTF-8', '会员') . '</b>(' . $hRow['totalUserNum'] . ')\',open:true,isParent:true,url:\'ShowGroupUserList.php?groupType=1&groupId=0\',target:\'theIframe\'},';
		}
		else {
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $_SESSION['adminRoleGroupID'] . '-%\' OR b.userGroupID = \'' . $_SESSION['adminRoleGroupID'] . '\') ';
			$hSQL = ' SELECT COUNT(*) as totalUserNum FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID AND b.groupType =1 LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$uSQL = ' SELECT userGroupName FROM ' . USERGROUP_TABLE . ' WHERE userGroupID= \'' . $_SESSION['adminRoleGroupID'] . '\' ';
			$uRow = $DB->queryFirstRow($uSQL);
			$treeJOSN .= '{id:\'root_0\',pId:0,name:\'<b>' . iconv('gbk', 'UTF-8', '用户') . '</b>(' . $hRow['totalUserNum'] . ')\',open:true,isParent:true,url:\'ShowUserList.php\',target:\'theIframe\'},';
			$treeJOSN .= '{id:\'corp_' . $_SESSION['adminRoleGroupID'] . '\',pId:\'root_0\',name:\'<b>' . iconv('gbk', 'UTF-8', htmltoxml($uRow['userGroupName'])) . '</b>(' . $hRow['totalUserNum'] . ')\',open:true,isParent:true,url:\'ShowGroupUserList.php?groupType=1&groupId=' . $_SESSION['adminRoleGroupID'] . '\',target:\'theIframe\'},';
		}

		$SQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE groupType =\'1\' AND fatherId = \'' . $_SESSION['adminRoleGroupID'] . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);

		while ($Row = $DB->queryArray($Result)) {
			$hSQL = ' SELECT COUNT(*) as recNum FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['userGroupID'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$isParent = ($hRow['recNum'] != 0 ? 'true' : 'false');
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';
			$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$treeJOSN .= '{id:\'corp_' . $Row['userGroupID'] . '\',pId:\'corp_' . $_SESSION['adminRoleGroupID'] . '\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=1&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
		}

		$treeJOSN = substr($treeJOSN, 0, -1);
		$treeJOSN .= ']';
	}
	else {
		$theNode = explode('_', $pId);
		$theNodeId = intval($theNode[1]);
		$SQL = ' SELECT userGroupID,userGroupName FROM ' . USERGROUP_TABLE . ' WHERE groupType =\'1\' AND fatherId = \'' . $theNodeId . '\' ORDER BY absPath ASC,userGroupID ASC ';
		$Result = $DB->query($SQL);
		$nodeNum = $DB->_getNumRows($Result);
		$treeJOSN = '[';

		while ($Row = $DB->queryArray($Result)) {
			$hSQL = ' SELECT COUNT(*) as recNum FROM ' . USERGROUP_TABLE . ' WHERE fatherId = \'' . $Row['userGroupID'] . '\' ';
			$hRow = $DB->queryFirstRow($hSQL);
			$isParent = ($hRow['recNum'] != 0 ? 'true' : 'false');
			$theSonSQL = '( concat(\'-\',b.absPath,\'-\') LIKE \'%-' . $Row['userGroupID'] . '-%\' OR b.userGroupID = \'' . $Row['userGroupID'] . '\') ';
			$hSQL = ' SELECT COUNT(*) FROM ' . ADMINISTRATORS_TABLE . ' a,' . USERGROUP_TABLE . ' b WHERE a.isAdmin NOT IN (0,1,6) AND ' . $theSonSQL . ' AND a.userGroupID = b.userGroupID LIMIT 1 ';
			$hRow = $DB->queryFirstRow($hSQL);
			$treeJOSN .= '{id:\'corp_' . $Row['userGroupID'] . '\',pId:\'corp_' . $theNodeId . '\',name:\'' . iconv('gbk', 'UTF-8', htmltoxml($Row['userGroupName'])) . '(' . $hRow[0] . ')\',open:true,isParent:' . $isParent . ',url:\'ShowGroupUserList.php?groupType=1&groupId=' . $Row['userGroupID'] . '\',target:\'theIframe\'},';
		}

		if (0 < $nodeNum) {
			$treeJOSN = substr($treeJOSN, 0, -1);
		}

		$treeJOSN .= ']';
	}
 
	break;
}

header('Content-Type:text/html; charset=utf-8');
echo $treeJOSN;

?>
