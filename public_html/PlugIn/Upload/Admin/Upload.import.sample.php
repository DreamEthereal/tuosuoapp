<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$theCsvColNum++;
$xlsRow++;
xlswritelabel($xlsRow, 0, $theCsvColNum);
xlswritelabel($xlsRow, 1, qimportstring($theQtnArray['questionName']));
xlswritelabel($xlsRow, 2, '附属文件名，须与压缩包内物理文件的文件名保持一致，可为空');

?>
