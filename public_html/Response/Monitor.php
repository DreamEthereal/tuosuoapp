<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
$fileName = '../PerUserData/monitor/monitor.txt';
$theFileContent = file_get_contents($fileName);
$fp = fopen($fileName, 'w+');
$theFileContents = $theFileContent . $_SERVER['QUERY_STRING'] . "\r\n";
fwrite($fp, $theFileContents);
fclose($fp);

?>
