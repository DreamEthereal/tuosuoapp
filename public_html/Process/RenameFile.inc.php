<?php
//dezend by http://www.yunlu99.com/
if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

$phyFilePath = $Config['absolutenessPath'];

if (file_exists($phyFilePath . 'Install/index.php')) {
	@rename($phyFilePath . 'Install/index.php', $phyFilePath . 'Install/index.lock');
}

$upFilePath = $phyFilePath . 'Upgrade/';

if (is_dir($upFilePath)) {
	if ($upFileDir = opendir($upFilePath)) {
		while (($upFile = readdir($upFileDir)) !== false) {
			if (($upFile == '.') || ($upFile == '..')) {
				continue;
			}
			else {
				$tmpExt = explode('.', $upFile);
				$tmpNum = count($tmpExt) - 1;
				$extension = strtolower($tmpExt[$tmpNum]);
				$upFileName = basename($upFile, '.' . $extension);

				if ($extension == 'php') {
					@rename($upFilePath . $upFile, $upFilePath . $upFileName . '.lock');
				}
			}
		}

		closedir($upFileDir);
	}
}

?>
