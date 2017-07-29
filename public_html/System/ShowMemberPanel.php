<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
_checkroletype('1|2|5');

if ($_POST['Action'] == 'MailPanelRegSubmit') {
	$thisPageFieldsList = explode('|', $_POST['this_fields_list']);
	$thisPageFieldsType = explode('|', $_POST['this_fields_type']);
	$PanelArray = array();
	$labelk = 0;
	$thisFields = 0;

	for (; $thisFields < count($thisPageFieldsType); $thisFields++) {
		switch ($thisPageFieldsType[$thisFields]) {
		case 'group':
			if (!empty($_POST['option_0'])) {
				if (count($_POST['option_0']) == 1) {
					$GroupSQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isActive=1 AND isAdmin =0 AND administratorsGroupID =\'' . $_POST['option_0'][0] . '\' ORDER BY administratorsID ASC ';
				}
				else {
					$GroupSQL = ' SELECT administratorsID FROM ' . ADMINISTRATORS_TABLE . ' WHERE isActive=1 AND isAdmin =0 AND administratorsGroupID IN (' . implode(',', $_POST['option_0']) . ') ORDER BY administratorsID ASC ';
				}

				$GroupResult = $DB->query($GroupSQL);
				$GroupArray = array();

				while ($GroupRow = $DB->queryArray($GroupResult)) {
					$GroupArray[] = $GroupRow['administratorsID'];
				}
			}

			break;

		case 'radio':
		case 'select':
			if (!empty($_POST['option_' . $thisPageFieldsList[$thisFields]])) {
				$conList = '';
				$i = 0;
				$PanelTotal = count($_POST['option_' . $thisPageFieldsList[$thisFields]]);

				if (2 <= $PanelTotal) {
					$conList .= '(';
				}

				foreach ($_POST['option_' . $thisPageFieldsList[$thisFields]] as $theOptionID) {
					$i++;

					if ($i == $PanelTotal) {
						$conList .= ' (b.value = \'' . $theOptionID . '\' AND b.administratorsoptionID=' . $thisPageFieldsList[$thisFields] . ') ';
					}
					else {
						$conList .= ' (b.value = \'' . $theOptionID . '\' AND b.administratorsoptionID=' . $thisPageFieldsList[$thisFields] . ') OR ';
					}
				}

				if (2 <= $PanelTotal) {
					$conList .= ')';
				}

				$PanelSQL = ' SELECT DISTINCT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a, ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' b WHERE a.administratorsID =b.administratorsID AND a.isActive=1 AND a.isAdmin =0 AND ' . $conList . ' ORDER BY a.administratorsID ASC ';
				$PanelResult = $DB->query($PanelSQL);

				while ($PanelRow = $DB->queryArray($PanelResult)) {
					$PanelArray[$labelk][] = $PanelRow['administratorsID'];
				}

				$labelk++;
			}

			break;

		case 'checkbox':
			if (!empty($_POST['option_' . $thisPageFieldsList[$thisFields]])) {
				$conList = '';
				$i = 0;
				$PanelTotal = count($_POST['option_' . $thisPageFieldsList[$thisFields]]);

				if (2 <= $PanelTotal) {
					$conList .= '(';
				}

				foreach ($_POST['option_' . $thisPageFieldsList[$thisFields]] as $theOptionID) {
					$i++;

					if ($i == $PanelTotal) {
						$conList .= ' ( FIND_IN_SET(\'' . $theOptionID . '\',b.value) AND b.administratorsoptionID=' . $thisPageFieldsList[$thisFields] . ') ';
					}
					else {
						$conList .= ' ( FIND_IN_SET(\'' . $theOptionID . '\',b.value) AND b.administratorsoptionID=' . $thisPageFieldsList[$thisFields] . ') OR ';
					}
				}

				if (2 <= $PanelTotal) {
					$conList .= ')';
				}

				$PanelSQL = ' SELECT DISTINCT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a, ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' b WHERE a.administratorsID =b.administratorsID AND a.isActive=1 AND a.isAdmin =0 AND ' . $conList . ' ORDER BY a.administratorsID ASC ';
				$PanelResult = $DB->query($PanelSQL);

				while ($PanelRow = $DB->queryArray($PanelResult)) {
					$PanelArray[$labelk][] = $PanelRow['administratorsID'];
				}

				$labelk++;
			}

			break;

		case 'text':
			if (!empty($_POST['option_' . $thisPageFieldsList[$thisFields]])) {
				$conList = ' (b.value like \'%' . $_POST['option_' . $thisPageFieldsList[$thisFields]] . '%\'  AND b.administratorsoptionID=' . $thisPageFieldsList[$thisFields] . ') ';
				$PanelSQL = ' SELECT DISTINCT a.administratorsID FROM ' . ADMINISTRATORS_TABLE . ' a, ' . ADMINISTRATORSOPTIONVALUE_TABLE . ' b WHERE a.administratorsID =b.administratorsID AND a.isActive=1 AND a.isAdmin =0 AND ' . $conList . ' ORDER BY a.administratorsID ASC ';
				$PanelResult = $DB->query($PanelSQL);

				while ($PanelRow = $DB->queryArray($PanelResult)) {
					$PanelArray[$labelk][] = $PanelRow['administratorsID'];
				}

				$labelk++;
			}

			break;
		}
	}

	$theChangeArray = $PanelArray[0];
	$k = 1;

	for (; $k < count($PanelArray); $k++) {
		$theChangeArray = array_intersect($PanelArray[$k], $theChangeArray);
	}

	if (!empty($theChangeArray) && !empty($GroupArray)) {
		$adminIDArray = array_intersect($theChangeArray, $GroupArray);
	}

	if (!empty($theChangeArray) && empty($GroupArray)) {
		$adminIDArray = $theChangeArray;
	}

	if (empty($theChangeArray) && !empty($GroupArray)) {
		$adminIDArray = $GroupArray;
	}

	if (!empty($adminIDArray)) {
		$administratorsIDList = implode(',', $adminIDArray);
	}
	else {
		$administratorsIDList = '';
	}

	unset($PanelArray);
	unset($GroupArray);
	unset($adminIDArray);
	unset($theChangeArray);

	if (trim($administratorsIDList) != '') {
		$MemberSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID IN (' . $administratorsIDList . ')  AND isAdmin=0 ORDER BY administratorsName ASC ';
	}
	else {
		$MemberSQL = ' SELECT administratorsName FROM ' . ADMINISTRATORS_TABLE . ' WHERE administratorsID =0 AND isAdmin=0 ORDER BY administratorsName ASC ';
	}

	$MembersResult = $DB->query($MemberSQL);
	$ResIDArray = array();

	while ($MemberRow = $DB->queryArray($MembersResult)) {
		$ResIDArray[] = $MemberRow['administratorsName'];
	}

	if (!empty($ResIDArray)) {
		$panelUserName = implode(';', $ResIDArray);
	}
	else {
		$panelUserName = '';
	}

	_showmessage($lang['set_panel_reg'] . ':' . $_GET['surveyTitle'], true, '\'' . $panelUserName . '\'');
}

$EnableQCoreClass->setTemplateFile('PanelRegPageFile', 'MemberPanel.html');
$PanelsGroupList = '<option value=\'0\'>' . $lang['no_group'] . '</option>';
$PanelSQL = ' SELECT administratorsGroupID,administratorsGroupName FROM ' . ADMINISTRATORSGROUP_TABLE . ' ORDER BY administratorsGroupID ASC ';
$PanelsResult = $DB->query($PanelSQL);

while ($PanelRow = $DB->queryArray($PanelsResult)) {
	$PanelsGroupList .= '<option value=\'' . $PanelRow['administratorsGroupID'] . '\'>' . $PanelRow['administratorsGroupName'] . '</option>';
}

$EnableQCoreClass->replace('panel_groups_list', $PanelsGroupList);
$this_fields_list = '0|';
$this_fields_type = 'group|';
$EnableQCoreClass->set_CycBlock('PanelRegPageFile', 'QUESTION', 'question');
$EnableQCoreClass->replace('question', '');
$SQL = ' SELECT administratorsoptionID ,optionFieldName,types,content FROM ' . ADMINISTRATORSOPTION_TABLE . ' WHERE types IN (\'radio\',\'text\',\'checkbox\',\'select\') ORDER BY orderByID ASC ';
$Result = $DB->query($SQL);

while ($Row = $DB->queryArray($Result)) {
	$this_fields_list .= $Row['administratorsoptionID'] . '|';
	$this_fields_type .= $Row['types'] . '|';
	$EnableQCoreClass->replace('panelname', $Row['optionFieldName']);

	switch ($Row['types']) {
	case 'radio':
	case 'select':
	case 'checkbox':
		$option = explode("\n", $Row['content']);
		$selectList = '<select name=\'option_' . $Row['administratorsoptionID'] . '[]\' id=\'option_' . $Row['administratorsoptionID'] . '\' multiple size=5>';
		$i = 0;

		for (; $i < count($option); $i++) {
			$option[$i] = str_replace("\r", '', $option[$i]);

			if (trim($option[$i]) != '') {
				$selectList .= '<option value="' . trim($option[$i]) . '">' . trim($option[$i]) . '</option>' . "\n" . '';
			}
		}

		$selectList .= '</select>';
		$EnableQCoreClass->replace('panelopt', $selectList);
		break;

	case 'text':
		$panelopt = '<input name=\'option_' . $Row['administratorsoptionID'] . '\' id=\'option_' . $Row['administratorsoptionID'] . '\' size =25>';
		$EnableQCoreClass->replace('panelopt', $panelopt);
		break;
	}

	$EnableQCoreClass->parse('question', 'QUESTION', true);
}

$EnableQCoreClass->replace('this_fields_list', substr($this_fields_list, 0, -1));
$EnableQCoreClass->replace('this_fields_type', substr($this_fields_type, 0, -1));
$EnableQCoreClass->replace('Action', 'MailPanelRegSubmit');
$EnableQCoreClass->parse('PanelRegPage', 'PanelRegPageFile');
$EnableQCoreClass->output('PanelRegPage', false);

?>
