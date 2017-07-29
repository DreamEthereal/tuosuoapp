<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
$_GET['stat'] = (int) $_GET['stat'];
$thisProg = 'AndroidNotes.php?stat=' . $_GET['stat'];
$ConfigRow['topicNum'] = 30;
_checkroletype('1|5|6');

if ($_POST['Action'] == 'EmailSendSubmit') {
	if ($_POST['stat'] == 1) {
		$SQL = ' INSERT INTO ' . ANDROID_PUSH_TABLE . ' SET pushTitle=\'' . trim($_POST['pushTitle']) . '\',pushInfo=\'' . $_POST['pushInfo'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',stat=1,pushTime=\'' . time() . '\',isCommon=\'' . $_POST['isCommon'] . '\' ';

		switch ($_POST['isCommon']) {
		case 1:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;

			case 3:
				$SQL .= ' ,surveyID =\'' . trim($_POST['surveyID']) . '\' ';
				break;
			}

			break;

		default:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;
			}

			break;
		}
	}
	else {
		$SQL = ' UPDATE ' . ANDROID_PUSH_TABLE . ' SET pushTitle=\'' . trim($_POST['pushTitle']) . '\',pushInfo=\'' . $_POST['pushInfo'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',stat=1,pushTime=\'' . time() . '\',isCommon=\'' . $_POST['isCommon'] . '\'  ';

		switch ($_POST['isCommon']) {
		case 1:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;

			case 3:
				$SQL .= ' ,surveyID =\'' . trim($_POST['surveyID']) . '\' ';
				break;
			}

			break;

		case 2:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;
			}

			break;
		}

		$SQL .= ' WHERE pushID =\'' . $_POST['pushID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['send_android_notes'] . trim($_POST['pushTitle']));
	echo '<script>parent.hidePopWin();parent.referIframeSrc(\'AndroidNotes.php?stat=1\');</script>';
	exit();
}

if ($_POST['Action'] == 'EmailSaveSubmit') {
	if ($_POST['stat'] == 1) {
		$SQL = ' INSERT INTO ' . ANDROID_PUSH_TABLE . ' SET pushTitle=\'' . trim($_POST['pushTitle']) . '\',pushInfo=\'' . $_POST['pushInfo'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',stat=0,pushTime=\'' . time() . '\',isCommon=\'' . $_POST['isCommon'] . '\' ';

		switch ($_POST['isCommon']) {
		case 1:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;

			case 3:
				$SQL .= ' ,surveyID =\'' . trim($_POST['surveyID']) . '\' ';
				break;
			}

			break;

		default:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;
			}

			break;
		}
	}
	else {
		$SQL = ' UPDATE ' . ANDROID_PUSH_TABLE . ' SET pushTitle=\'' . trim($_POST['pushTitle']) . '\',pushInfo=\'' . $_POST['pushInfo'] . '\',administratorsID =\'' . $_SESSION['administratorsID'] . '\',stat=0,pushTime=\'' . time() . '\',isCommon=\'' . $_POST['isCommon'] . '\' ';

		switch ($_POST['isCommon']) {
		case 1:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;

			case 3:
				$SQL .= ' ,surveyID =\'' . trim($_POST['surveyID']) . '\' ';
				break;
			}

			break;

		default:
			switch ($_POST['actionType']) {
			case 1:
				break;

			case 2:
				$SQL .= ' ,pushURL =\'' . trim($_POST['pushURL']) . '\' ';
				break;
			}

			break;
		}

		$SQL .= ' WHERE pushID =\'' . $_POST['pushID'] . '\' ';
	}

	$DB->query($SQL);
	writetolog($lang['save_android_notes'] . trim($_POST['pushTitle']));
	echo '<script>parent.hidePopWin();parent.referIframeSrc(\'AndroidNotes.php?stat=0\');</script>';
	exit();
}

if ($_POST['DeleteSubmit']) {
	$SQL = sprintf(' DELETE FROM ' . ANDROID_PUSH_TABLE . ' WHERE (pushID IN (\'%s\'))', @join('\',\'', $_POST['pushID']));
	$DB->query($SQL);
	writetolog($lang['delete_android_notes']);
	_showsucceed($lang['delete_android_notes'], $thisProg);
}

if ($_GET['Action'] == 'Delete') {
	$SQL = ' DELETE FROM ' . ANDROID_PUSH_TABLE . ' WHERE pushID=\'' . $_GET['pushID'] . '\' ';
	$DB->query($SQL);
	writetolog($lang['delete_android_notes']);
	_showsucceed($lang['delete_android_notes'], $thisProg);
}

if ($_GET['Action'] == 'View') {
	$EnableQCoreClass->setTemplateFile('NotesListFile', 'AndroidNotesView.html');
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ANDROID_PUSH_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.pushID =\'' . $_GET['pushID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('sendName', $Row['administratorsName']);
	$EnableQCoreClass->replace('pushTitle', $Row['pushTitle']);
	$EnableQCoreClass->replace('pushInfo', nl2br($Row['pushInfo']));
	$EnableQCoreClass->replace('pushTime', date('Y-m-d H:i:s', $Row['pushTime']));
	$EnableQCoreClass->replace('isCommon', $lang['isCommon_' . $Row['isCommon']]);

	switch ($Row['isCommon']) {
	case 1:
		if (($Row['pushURL'] == '') && ($Row['surveyID'] == 0)) {
			$EnableQCoreClass->replace('pushURL', $lang['android_no_action']);
		}
		else if ($Row['surveyID'] != 0) {
			$HSQL = ' SELECT surveyTitle FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $Row['surveyID'] . '\' ';
			$HRow = $DB->queryFirstRow($HSQL);
			$EnableQCoreClass->replace('pushURL', $lang['android_survey_url'] . $HRow['surveyTitle']);
		}
		else {
			$EnableQCoreClass->replace('pushURL', $Row['pushURL']);
		}

		break;

	default:
		if ($Row['pushURL'] == '') {
			$EnableQCoreClass->replace('pushURL', $lang['android_no_action']);
		}
		else {
			$EnableQCoreClass->replace('pushURL', $Row['pushURL']);
		}

		break;
	}

	$NotesListPage = $EnableQCoreClass->parse('NotesList', 'NotesListFile');
	echo $NotesListPage;
	exit();
}

if ($_GET['Action'] == 'Send') {
	$EnableQCoreClass->setTemplateFile('NotesListFile', 'AndroidNotesSend.html');
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ANDROID_PUSH_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.pushID =\'' . $_GET['pushID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$EnableQCoreClass->replace('sendName', $Row['administratorsName'] == '' ? $_SESSION['administratorsName'] : $Row['administratorsName']);
	$EnableQCoreClass->replace('pushTitle', $Row['pushTitle']);
	$EnableQCoreClass->replace('pushInfo', $Row['pushInfo']);
	$EnableQCoreClass->replace('pushID', $Row['pushID']);
	$EnableQCoreClass->replace('stat', $Row['stat'] == '' ? $_GET['stat'] : $Row['stat']);

	if ($Row['isCommon'] == '') {
		$EnableQCoreClass->replace('isCommon_1', 'checked');
	}
	else {
		$EnableQCoreClass->replace('isCommon_' . $Row['isCommon'], 'checked');
	}

	$DSQL = ' SELECT a.surveyName,a.surveyTitle,a.surveyID FROM ' . SURVEY_TABLE . ' a,' . ANDROID_LIST_TABLE . ' b WHERE a.status =1 AND a.surveyID =b.surveyID ';

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '6':
		break;

	case '5':
		$UserIDList = implode(',', array_unique($_SESSION['adminSameGroupUsers']));
		$DSQL .= ' AND a.administratorsID IN (' . $UserIDList . ') ';
		break;
	}

	$DSQL .= ' ORDER BY a.surveyID DESC ';
	$DResult = $DB->query($DSQL);
	$surveyList = '';

	while ($DRow = $DB->queryArray($DResult)) {
		if (($Row['pushURL'] == '') && ($Row['surveyID'] == 0)) {
			$surveyList .= '<option value="' . $DRow['surveyID'] . '">' . $DRow['surveyTitle'] . '(' . $DRow['surveyName'] . ')</option>' . "\n" . '';
		}
		else {
			if (($Row['surveyID'] != 0) && ($DRow['surveyID'] == $Row['surveyID'])) {
				$surveyList .= '<option value="' . $DRow['surveyID'] . '" selected>' . $DRow['surveyTitle'] . '(' . $DRow['surveyName'] . ')</option>' . "\n" . '';
			}
			else {
				$surveyList .= '<option value="' . $DRow['surveyID'] . '">' . $DRow['surveyTitle'] . '(' . $DRow['surveyName'] . ')</option>' . "\n" . '';
			}
		}
	}

	$EnableQCoreClass->replace('surveyList', $surveyList);

	switch ($Row['isCommon']) {
	case 1:
		if (($Row['pushURL'] == '') && ($Row['surveyID'] == 0)) {
			$EnableQCoreClass->replace('pushURL', '');
			$EnableQCoreClass->replace('actionType_1', 'checked');
		}
		else if ($Row['surveyID'] != 0) {
			$EnableQCoreClass->replace('pushURL', '');
			$EnableQCoreClass->replace('actionType_3', 'checked');
		}
		else {
			$EnableQCoreClass->replace('pushURL', $Row['pushURL']);
			$EnableQCoreClass->replace('actionType_2', 'checked');
		}

		break;

	default:
		if ($Row['pushURL'] == '') {
			$EnableQCoreClass->replace('pushURL', '');
			$EnableQCoreClass->replace('actionType_1', 'checked');
		}
		else {
			$EnableQCoreClass->replace('pushURL', $Row['pushURL']);
			$EnableQCoreClass->replace('actionType_2', 'checked');
		}

		break;
	}

	$NotesListPage = $EnableQCoreClass->parse('NotesList', 'NotesListFile');
	echo $NotesListPage;
	exit();
}

if ($_GET['stat'] == 0) {
	$EnableQCoreClass->setTemplateFile('NotesListFile', 'AndroidNotesStat0.html');
	$notesStat = 0;
}
else {
	$EnableQCoreClass->setTemplateFile('NotesListFile', 'AndroidNotesStat1.html');
	$notesStat = 1;
}

$EnableQCoreClass->set_CycBlock('NotesListFile', 'LIST', 'list');
$EnableQCoreClass->replace('list', '');
$EnableQCoreClass->replace('addURL', $thisProg . '&Action=Send&stat=1');

switch ($_SESSION['adminRoleType']) {
case '1':
case '6':
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ANDROID_PUSH_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.stat =\'' . $notesStat . '\' ';
	break;

case '5':
	$SQL = ' SELECT a.*,b.administratorsName FROM ' . ANDROID_PUSH_TABLE . ' a, ' . ADMINISTRATORS_TABLE . ' b WHERE a.administratorsID=b.administratorsID  AND a.stat =\'' . $notesStat . '\' AND a.administratorsID=\'' . $_SESSION['administratorsID'] . '\' ';
	break;
}

$Result = $DB->query($SQL);
$recordCount = $DB->_getNumRows($Result);
$EnableQCoreClass->replace('recNum', $recordCount);
if (!isset($_GET['pageID']) || empty($_GET['pageID'])) {
	$start = 0;
}
else {
	$_GET['pageID'] = (int) $_GET['pageID'];
	$start = ($_GET['pageID'] - 1) * $ConfigRow['topicNum'];
	$start = ($start < 0 ? 0 : $start);
}

$SQL .= ' ORDER BY a.pushTime DESC  ';
$SQL .= ' LIMIT ' . $start . ',' . $ConfigRow['topicNum'] . ' ';


while ($Row = $DB->queryArray($Result)) {
	$EnableQCoreClass->replace('pushTime', date('Y-m-d H:i:s', $Row['pushTime']));
	$EnableQCoreClass->replace('pushID', $Row['pushID']);
	$EnableQCoreClass->replace('sendName', $Row['administratorsName']);
	$EnableQCoreClass->replace('pushTitle', $Row['pushTitle']);
	$EnableQCoreClass->replace('isCommon', $lang['isCommon_' . $Row['isCommon']]);
	$EnableQCoreClass->replace('viewURL', $thisProg . '&Action=View&pushID=' . $Row['pushID']);
	$EnableQCoreClass->replace('sendURL', $thisProg . '&Action=Send&pushID=' . $Row['pushID']);
	$EnableQCoreClass->replace('deleteURL', $thisProg . '&Action=Delete&pushID=' . $Row['pushID'] . ' ');
	$EnableQCoreClass->parse('list', 'LIST', true);
}

include_once ROOT_PATH . 'Includes/Pages.class.php';
$PAGES = new PageBar($recordCount, $ConfigRow['topicNum']);
$pagebar = $PAGES->whole_num_bar('', '', $page_others);
$EnableQCoreClass->replace('pagesList', $pagebar);
$EnableQCoreClass->parse('NotesList', 'NotesListFile');
$EnableQCoreClass->output('NotesList');

?>
