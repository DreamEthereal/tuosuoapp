<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$SQL = ' SELECT * FROM ' . BASESETTING_TABLE . ' ';
$BaseRow = $DB->queryFirstRow($SQL);

switch ($BaseRow['isUseOriPassport']) {
case '1':
default:
	$EnableQCoreClass->setTemplateFile('ResultConFile', 'ResultAdCond.html');
	$groupSplit = explode(',', $optionCond['t5']);
	$SQL = ' SELECT value FROM ' . RESPONSEGROUPLIST_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' AND administratorsoptionID=0 ORDER BY value ASC ';
	$Result = $DB->query($SQL);
	$MembersGroup = array();

	while ($R_Row = $DB->queryArray($Result)) {
		$MembersGroup[] = $R_Row['value'];
	}

	if (in_array(0, $MembersGroup)) {
		if (($optionCond['t5'] != '') && in_array(0, $groupSplit)) {
			$MembersGroupList = '<option value=\'0\' selected>' . $lang['no_group'] . '</option>' . "\n" . '';
		}
		else {
			$MembersGroupList = '<option value=\'0\'>' . $lang['no_group'] . '</option>' . "\n" . '';
		}
	}
	else {
		$MembersGroupList = '';
	}

	if (!empty($MembersGroup)) {
		$MembersGroupIDList = implode(',', $MembersGroup);
		$MemberSQL = ' SELECT administratorsGroupID,administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID IN (' . $MembersGroupIDList . ') ORDER BY administratorsGroupID ASC ';
	}
	else {
		$MemberSQL = ' SELECT administratorsGroupID,administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' WHERE administratorsGroupID =0 ORDER BY administratorsGroupID ASC ';
	}

	$MembersResult = $DB->query($MemberSQL);

	while ($MemberRow = $DB->queryArray($MembersResult)) {
		if (in_array($MemberRow['administratorsGroupID'], $groupSplit)) {
			$MembersGroupList .= '<option value=\'' . $MemberRow['administratorsGroupID'] . '\' selected>' . $MemberRow['administratorsGroupName'] . '</option>\\n';
		}
		else {
			$MembersGroupList .= '<option value=\'' . $MemberRow['administratorsGroupID'] . '\'>' . $MemberRow['administratorsGroupName'] . '</option>\\n';
		}
	}

	$EnableQCoreClass->replace('MembersGroupList', $MembersGroupList);
	$EnableQCoreClass->set_CycBlock('ResultConFile', 'AJAX', 'ajax');
	$EnableQCoreClass->replace('ajax', '');

	if ($Sur_G_Row['ajaxRtnValue'] != '') {
		$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

		if (6 < count($ajaxRtnValueName)) {
			$ajaxCount = 6;
		}
		else {
			$ajaxCount = count($ajaxRtnValueName);
		}

		$i = 0;

		for (; $i < $ajaxCount; $i++) {
			$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
			$j = $i + 1;
			$k = $i + 6;
			$ajaxSplit = explode(',', $optionCond['t' . $k]);
			$EnableQCoreClass->replace('ajaxRtnID', $j);
			$SQL = ' SELECT DISTINCT ajaxRtnValue_' . $j . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ';
			$Result = $DB->query($SQL);
			$ajaxRtnValue_List = '';

			while ($Row = $DB->queryArray($Result)) {
				if (in_array($Row['ajaxRtnValue_' . $j], $ajaxSplit)) {
					$ajaxRtnValue_List .= '<option value=\'' . $Row['ajaxRtnValue_' . $j] . '\' selected>' . $Row['ajaxRtnValue_' . $j] . '</option>\\n';
				}
				else {
					$ajaxRtnValue_List .= '<option value=\'' . $Row['ajaxRtnValue_' . $j] . '\'>' . $Row['ajaxRtnValue_' . $j] . '</option>\\n';
				}
			}

			$EnableQCoreClass->replace('ajaxRtnValue_List', $ajaxRtnValue_List);
			$EnableQCoreClass->parse('ajax', 'AJAX', true);
		}
	}

	$privateQueryCon = $EnableQCoreClass->parse('privateQueryCon', 'ResultConFile');
	break;

case '4':
case '3':
case '5':
	$privateQueryCon = '';
	break;

case '2':
	$EnableQCoreClass->setTemplateFile('ResultConFile', 'ResultAjaxCond.html');
	$EnableQCoreClass->set_CycBlock('ResultConFile', 'AJAX', 'ajax');
	$EnableQCoreClass->replace('ajax', '');

	if ($Sur_G_Row['ajaxRtnValue'] != '') {
		$ajaxRtnValueName = explode(',', trim($Sur_G_Row['ajaxRtnValue']));

		if (6 < count($ajaxRtnValueName)) {
			$ajaxCount = 6;
		}
		else {
			$ajaxCount = count($ajaxRtnValueName);
		}

		$i = 0;

		for (; $i < $ajaxCount; $i++) {
			$EnableQCoreClass->replace('ajaxRtnValueName', $ajaxRtnValueName[$i]);
			$j = $i + 1;
			$k = $i + 6;
			$ajaxSplit = explode(',', $optionCond['t' . $k]);
			$EnableQCoreClass->replace('ajaxRtnID', $j);
			$SQL = ' SELECT DISTINCT ajaxRtnValue_' . $j . ' FROM ' . $table_prefix . 'response_' . $_GET['surveyID'] . ' ';
			$Result = $DB->query($SQL);
			$ajaxRtnValue_List = '';

			while ($Row = $DB->queryArray($Result)) {
				if (in_array($Row['ajaxRtnValue_' . $j], $ajaxSplit)) {
					$ajaxRtnValue_List .= '<option value=\'' . $Row['ajaxRtnValue_' . $j] . '\' selected>' . $Row['ajaxRtnValue_' . $j] . '</option>\\n';
				}
				else {
					$ajaxRtnValue_List .= '<option value=\'' . $Row['ajaxRtnValue_' . $j] . '\'>' . $Row['ajaxRtnValue_' . $j] . '</option>\\n';
				}
			}

			$EnableQCoreClass->replace('ajaxRtnValue_List', $ajaxRtnValue_List);
			$EnableQCoreClass->parse('ajax', 'AJAX', true);
		}
	}

	$privateQueryCon = $EnableQCoreClass->parse('privateQueryCon', 'ResultConFile');
	break;
}

$EnableQCoreClass->replace('privateQueryCon', $privateQueryCon);

?>
