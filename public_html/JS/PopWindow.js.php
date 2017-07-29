<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Config/DBConfig.inc.php';
$All_Path = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0, -19);

if ($Config['dataDomainName'] != '') {
	$fullPath = 'http://' . $Config['dataDomainName'] . '/';
}
else {
	$fullPath = $All_Path;
}

echo 'document.write(\'<link href="';
echo $fullPath;
echo 'CSS/Window.css" rel="stylesheet" type="text/css"/>\');' . "\r\n" . 'document.write("<scr"+"ipt src=\\"';
echo $fullPath;
echo 'JS/Common.js.php\\"></sc"+"ript>");' . "\r\n" . 'document.write("<scr"+"ipt src=\\"';
echo $fullPath;
echo 'JS/Window.js.php?style=10\\"></sc"+"ript>");';

?>
