<?php
//dezend by http://www.yunlu99.com/
class archive
{
	public function archive($name)
	{
		$this->options = array('basedir' => '.', 'name' => $name, 'prepend' => '', 'inmemory' => 0, 'overwrite' => 0, 'recurse' => 1, 'storepaths' => 1, 'followlinks' => 0, 'level' => 3, 'method' => 1, 'sfx' => '', 'type' => '', 'comment' => '');
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}

	public function set_options($options)
	{
		foreach ($options as $_obf_Vwty => $_obf_VgKtFeg_) {
			$this->options[$_obf_Vwty] = $_obf_VgKtFeg_;
		}

		if (!empty($this->options['basedir'])) {
			$this->options['basedir'] = str_replace('\\', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/+/', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/$/', '', $this->options['basedir']);
		}

		if (!empty($this->options['name'])) {
			$this->options['name'] = str_replace('\\', '/', $this->options['name']);
			$this->options['name'] = preg_replace('/\\/+/', '/', $this->options['name']);
		}

		if (!empty($this->options['prepend'])) {
			$this->options['prepend'] = str_replace('\\', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/^(\\.*\\/+)+/', '', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/+/', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/$/', '', $this->options['prepend']) . '/';
		}
	}

	public function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);
			if (($this->options['overwrite'] == 0) && file_exists($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''))) {
				$this->error[] = 'File ' . $this->options['name'] . ' already exists.';
				chdir($_obf_4HS1);
				return 0;
			}
			else {
				if ($this->archive = @fopen($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''), 'wb+')) {
					chdir($_obf_4HS1);
				}
				else {
					$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
					chdir($_obf_4HS1);
					return 0;
				}
			}
		}
		else {
			$this->archive = '';
		}

		switch ($this->options['type']) {
		case 'zip':
			if (!$this->create_zip()) {
				$this->error[] = 'Could not create zip file.';
				return 0;
			}

			break;

		case 'bzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_bzip()) {
				$this->error[] = 'Could not create bzip2 file.';
				return 0;
			}

			break;

		case 'gzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_gzip()) {
				$this->error[] = 'Could not create gzip file.';
				return 0;
			}

			break;

		case 'tar':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			break;
		}

		if ($this->options['inmemory'] == 0) {
			fclose($this->archive);
			if (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip')) {
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}

		return 1;
	}

	public function add_data($data)
	{
		if ($this->options['inmemory'] == 0) {
			fwrite($this->archive, $data);
		}
		else {
			$this->archive .= $data;
		}
	}

	public function make_list()
	{
		if (!empty($this->exclude)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->exclude as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						unset($this->files[$_obf_Vwty]);
					}
				}
			}
		}

		if (!empty($this->storeonly)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->storeonly as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						$this->files[$_obf_Vwty]['method'] = 0;
					}
				}
			}
		}

		unset($this->exclude);
		unset($this->storeonly);
	}

	public function add_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->files[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function exclude_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->exclude[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function store_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->storeonly[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function list_files($list)
	{
		if (!is_array($list)) {
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current) {
			$current = str_replace('\\', '/', $current);
			$current = preg_replace('/\\/+/', '/', $current);
			$current = preg_replace('/\\/$/', '', $current);

			if (strstr($current, '*')) {
				$regex = preg_replace('/([\\\\^$\\.\\[\\]\\|\\(\\)\\?\\+\\{\\}\\/])/', '\\\\\\1', $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = (strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.');
				$temp = $this->parse_dir($dir);

				foreach ($temp as $current2) {
					if (preg_match('/^' . $regex . '$/i', $current2['name'])) {
						$files[] = $current2;
					}
				}

				unset($regex);
				unset($dir);
				unset($temp);
				unset($current);
			}
			else if (@is_dir($current)) {
				$temp = $this->parse_dir($current);

				foreach ($temp as $file) {
					$files[] = $file;
				}

				unset($temp);
				unset($file);
			}
			else if (@file_exists($current)) {
				$files[] = array('name' => $current, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($current, '/') ? substr($current, strrpos($current, '/') + 1) : $current), 'type' => @is_link($current) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
			else {
				echo 'other error ';
			}
		}

		chdir($pwd);
		unset($current);
		unset($pwd);
		usort($files, array('archive', 'sort_files'));
		return $files;
	}

	public function parse_dir($dirname)
	{
		if (($this->options['storepaths'] == 1) && !preg_match('/^(\\.+\\/*)+$/', $dirname)) {
			$_obf_I9APXAk_ = array(
				array('name' => $dirname, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($dirname, '/') ? substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname))
				);
		}
		else {
			$_obf_I9APXAk_ = array();
		}

		$_obf_Fwl1 = @opendir($dirname);

		while ($_obf_6hS1Rw__ = @readdir($_obf_Fwl1)) {
			$_obf_pWCgaILc0RE_ = $dirname . '/' . $_obf_6hS1Rw__;
			if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
				continue;
			}
			else if (@is_dir($_obf_pWCgaILc0RE_)) {
				if (empty($this->options['recurse'])) {
					continue;
				}

				$_obf_SeV31Q__ = $this->parse_dir($_obf_pWCgaILc0RE_);

				foreach ($_obf_SeV31Q__ as $_obf_enX6mB4_) {
					$_obf_I9APXAk_[] = $_obf_enX6mB4_;
				}
			}
			else if (@file_exists($_obf_pWCgaILc0RE_)) {
				$_obf_I9APXAk_[] = array('name' => $_obf_pWCgaILc0RE_, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($_obf_pWCgaILc0RE_, '/') ? substr($_obf_pWCgaILc0RE_, strrpos($_obf_pWCgaILc0RE_, '/') + 1) : $_obf_pWCgaILc0RE_), 'type' => @is_link($_obf_pWCgaILc0RE_) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($_obf_6hS1Rw__, strrpos($_obf_6hS1Rw__, '.')), 'stat' => stat($_obf_pWCgaILc0RE_));
			}
		}

		@closedir($_obf_Fwl1);
		return $_obf_I9APXAk_;
	}

	public function sort_files($a, $b)
	{
		if ($a['type'] != $b['type']) {
			if (($a['type'] == 5) || ($b['type'] == 2)) {
				return -1;
			}
			else {
				if (($a['type'] == 2) || ($b['type'] == 5)) {
					return 1;
				}
				else if ($a['type'] == 5) {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
				else if ($a['ext'] != $b['ext']) {
					return strcmp($a['ext'], $b['ext']);
				}
				else if ($a['stat'][7] != $b['stat'][7]) {
					return $b['stat'][7] < $a['stat'][7] ? -1 : 1;
				}
				else {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
			}
		}

		return 0;
	}

	public function download_file()
	{
		if ($this->options['inmemory'] == 0) {
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return NULL;
		}

		switch ($this->options['type']) {
		case 'zip':
			header('Content-Type: application/zip');
			break;

		case 'bzip':
			header('Content-Type: application/x-bzip2');
			break;

		case 'gzip':
			header('Content-Type: application/x-gzip');
			break;

		case 'tar':
			header('Content-Type: application/x-tar');
		}

		$_obf_YfrY8VEd = 'Content-Disposition: attachment; filename="';
		$_obf_YfrY8VEd .= (strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name']);
		$_obf_YfrY8VEd .= '"';
		header($_obf_YfrY8VEd);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}

class tar_file extends archive
{
	public function tar_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'tar';
	}

	public function create_tar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current) {
			if ($current['name'] == $this->options['name']) {
				continue;
			}

			if (99 < strlen($current['name2'])) {
				$path = substr($current['name2'], 0, strpos($current['name2'], '/', strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'], strlen($path));
				if ((154 < strlen($path)) || (99 < strlen($current['name2']))) {
					$this->error[] = 'Could not add ' . $path . $current['name2'] . ' to archive because the filename is too long.';
					continue;
				}
			}

			$block = pack('a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12', $current['name2'], sprintf('%07o', $current['stat'][2]), sprintf('%07o', $current['stat'][4]), sprintf('%07o', $current['stat'][5]), sprintf('%011o', $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf('%011o', $current['stat'][9]), '        ', $current['type'], $current['type'] == 2 ? @readlink($current['name']) : '', 'ustar ', ' ', 'Unknown', 'Unknown', '', '', !empty($path) ? $path : '', '');
			$checksum = 0;
			$i = 0;

			for (; $i < 512; $i++) {
				$checksum += ord(substr($block, $i, 1));
			}

			$checksum = pack('a8', sprintf('%07o', $checksum));
			$block = substr_replace($block, $checksum, 148, 8);
			if (($current['type'] == 2) || ($current['stat'][7] == 0)) {
				$this->add_data($block);
			}
			else if ($fp = @fopen($current['name'], 'rb')) {
				$this->add_data($block);

				while ($temp = fread($fp, 1048576)) {
					$this->add_data($temp);
				}

				if (0 < ($current['stat'][7] % 512)) {
					$temp = '';
					$i = 0;

					for (; $i < (512 - ($current['stat'][7] % 512)); $i++) {
						$temp .= '' . "\0" . '';
					}

					$this->add_data($temp);
				}

				fclose($fp);
			}
			else {
				$this->error[] = 'Could not open file ' . $current['name'] . ' for reading. It was not added.';
			}
		}

		$this->add_data(pack('a1024', ''));
		chdir($pwd);
		return 1;
	}

	public function extract_files()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if ($fp = $this->open_archive()) {
			if ($this->options['inmemory'] == 1) {
				$this->files = array();
			}

			while ($block = fread($fp, 512)) {
				$temp = unpack('a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp', $block);
				$file = array(
					'name'     => $temp['prefix'] . $temp['name'],
					'stat'     => array(2 => $temp['mode'], 4 => octdec($temp['uid']), 5 => octdec($temp['gid']), 7 => octdec($temp['size']), 9 => octdec($temp['mtime'])),
					'checksum' => octdec($temp['checksum']),
					'type'     => $temp['type'],
					'magic'    => $temp['magic']
					);

				if ($file['checksum'] == 0) {
					break;
				}
				else if (substr($file['magic'], 0, 5) != 'ustar') {
					$this->error[] = 'This script does not support extracting this type of tar file.';
					break;
				}

				$block = substr_replace($block, '        ', 148, 8);
				$checksum = 0;
				$i = 0;

				for (; $i < 512; $i++) {
					$checksum += ord(substr($block, $i, 1));
				}

				if ($file['checksum'] != $checksum) {
					$this->error[] = 'Could not extract from ' . $this->options['name'] . ', it is corrupt.';
				}

				if ($this->options['inmemory'] == 1) {
					$file['data'] = fread($fp, $file['stat'][7]);
					fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
					unset($file['checksum']);
					unset($file['magic']);
					$this->files[] = $file;
				}
				else if ($file['type'] == 5) {
					if (!is_dir($file['name'])) {
						mkdir($file['name'], $file['stat'][2]);
					}
				}
				else {
					if (($this->options['overwrite'] == 0) && file_exists($file['name'])) {
						$this->error[] = $file['name'] . ' already exists.';
						continue;
					}
					else if ($file['type'] == 2) {
						symlink($temp['symlink'], $file['name']);
						chmod($file['name'], $file['stat'][2]);
					}
					else if ($new = @fopen($file['name'], 'wb')) {
						fwrite($new, fread($fp, $file['stat'][7]));
						fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
						fclose($new);
						chmod($file['name'], $file['stat'][2]);
					}
					else {
						$this->error[] = 'Could not open ' . $file['name'] . ' for writing.';
						continue;
					}
				}

				chown($file['name'], $file['stat'][4]);
				chgrp($file['name'], $file['stat'][5]);
				touch($file['name'], $file['stat'][9]);
				unset($file);
			}
		}
		else {
			$this->error[] = 'Could not open file ' . $this->options['name'];
		}

		chdir($pwd);
	}

	public function open_archive()
	{
		return @fopen($this->options['name'], 'rb');
	}

	public function archive($name)
	{
		$this->options = array('basedir' => '.', 'name' => $name, 'prepend' => '', 'inmemory' => 0, 'overwrite' => 0, 'recurse' => 1, 'storepaths' => 1, 'followlinks' => 0, 'level' => 3, 'method' => 1, 'sfx' => '', 'type' => '', 'comment' => '');
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}

	public function set_options($options)
	{
		foreach ($options as $_obf_Vwty => $_obf_VgKtFeg_) {
			$this->options[$_obf_Vwty] = $_obf_VgKtFeg_;
		}

		if (!empty($this->options['basedir'])) {
			$this->options['basedir'] = str_replace('\\', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/+/', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/$/', '', $this->options['basedir']);
		}

		if (!empty($this->options['name'])) {
			$this->options['name'] = str_replace('\\', '/', $this->options['name']);
			$this->options['name'] = preg_replace('/\\/+/', '/', $this->options['name']);
		}

		if (!empty($this->options['prepend'])) {
			$this->options['prepend'] = str_replace('\\', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/^(\\.*\\/+)+/', '', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/+/', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/$/', '', $this->options['prepend']) . '/';
		}
	}

	public function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);
			if (($this->options['overwrite'] == 0) && file_exists($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''))) {
				$this->error[] = 'File ' . $this->options['name'] . ' already exists.';
				chdir($_obf_4HS1);
				return 0;
			}
			else {
				if ($this->archive = @fopen($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''), 'wb+')) {
					chdir($_obf_4HS1);
				}
				else {
					$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
					chdir($_obf_4HS1);
					return 0;
				}
			}
		}
		else {
			$this->archive = '';
		}

		switch ($this->options['type']) {
		case 'zip':
			if (!$this->create_zip()) {
				$this->error[] = 'Could not create zip file.';
				return 0;
			}

			break;

		case 'bzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_bzip()) {
				$this->error[] = 'Could not create bzip2 file.';
				return 0;
			}

			break;

		case 'gzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_gzip()) {
				$this->error[] = 'Could not create gzip file.';
				return 0;
			}

			break;

		case 'tar':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			break;
		}

		if ($this->options['inmemory'] == 0) {
			fclose($this->archive);
			if (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip')) {
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}

		return 1;
	}

	public function add_data($data)
	{
		if ($this->options['inmemory'] == 0) {
			fwrite($this->archive, $data);
		}
		else {
			$this->archive .= $data;
		}
	}

	public function make_list()
	{
		if (!empty($this->exclude)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->exclude as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						unset($this->files[$_obf_Vwty]);
					}
				}
			}
		}

		if (!empty($this->storeonly)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->storeonly as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						$this->files[$_obf_Vwty]['method'] = 0;
					}
				}
			}
		}

		unset($this->exclude);
		unset($this->storeonly);
	}

	public function add_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->files[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function exclude_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->exclude[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function store_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->storeonly[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function list_files($list)
	{
		if (!is_array($list)) {
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current) {
			$current = str_replace('\\', '/', $current);
			$current = preg_replace('/\\/+/', '/', $current);
			$current = preg_replace('/\\/$/', '', $current);

			if (strstr($current, '*')) {
				$regex = preg_replace('/([\\\\^$\\.\\[\\]\\|\\(\\)\\?\\+\\{\\}\\/])/', '\\\\\\1', $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = (strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.');
				$temp = $this->parse_dir($dir);

				foreach ($temp as $current2) {
					if (preg_match('/^' . $regex . '$/i', $current2['name'])) {
						$files[] = $current2;
					}
				}

				unset($regex);
				unset($dir);
				unset($temp);
				unset($current);
			}
			else if (@is_dir($current)) {
				$temp = $this->parse_dir($current);

				foreach ($temp as $file) {
					$files[] = $file;
				}

				unset($temp);
				unset($file);
			}
			else if (@file_exists($current)) {
				$files[] = array('name' => $current, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($current, '/') ? substr($current, strrpos($current, '/') + 1) : $current), 'type' => @is_link($current) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
			else {
				echo 'other error ';
			}
		}

		chdir($pwd);
		unset($current);
		unset($pwd);
		usort($files, array('archive', 'sort_files'));
		return $files;
	}

	public function parse_dir($dirname)
	{
		if (($this->options['storepaths'] == 1) && !preg_match('/^(\\.+\\/*)+$/', $dirname)) {
			$_obf_I9APXAk_ = array(
				array('name' => $dirname, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($dirname, '/') ? substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname))
				);
		}
		else {
			$_obf_I9APXAk_ = array();
		}

		$_obf_Fwl1 = @opendir($dirname);

		while ($_obf_6hS1Rw__ = @readdir($_obf_Fwl1)) {
			$_obf_pWCgaILc0RE_ = $dirname . '/' . $_obf_6hS1Rw__;
			if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
				continue;
			}
			else if (@is_dir($_obf_pWCgaILc0RE_)) {
				if (empty($this->options['recurse'])) {
					continue;
				}

				$_obf_SeV31Q__ = $this->parse_dir($_obf_pWCgaILc0RE_);

				foreach ($_obf_SeV31Q__ as $_obf_enX6mB4_) {
					$_obf_I9APXAk_[] = $_obf_enX6mB4_;
				}
			}
			else if (@file_exists($_obf_pWCgaILc0RE_)) {
				$_obf_I9APXAk_[] = array('name' => $_obf_pWCgaILc0RE_, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($_obf_pWCgaILc0RE_, '/') ? substr($_obf_pWCgaILc0RE_, strrpos($_obf_pWCgaILc0RE_, '/') + 1) : $_obf_pWCgaILc0RE_), 'type' => @is_link($_obf_pWCgaILc0RE_) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($_obf_6hS1Rw__, strrpos($_obf_6hS1Rw__, '.')), 'stat' => stat($_obf_pWCgaILc0RE_));
			}
		}

		@closedir($_obf_Fwl1);
		return $_obf_I9APXAk_;
	}

	public function sort_files($a, $b)
	{
		if ($a['type'] != $b['type']) {
			if (($a['type'] == 5) || ($b['type'] == 2)) {
				return -1;
			}
			else {
				if (($a['type'] == 2) || ($b['type'] == 5)) {
					return 1;
				}
				else if ($a['type'] == 5) {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
				else if ($a['ext'] != $b['ext']) {
					return strcmp($a['ext'], $b['ext']);
				}
				else if ($a['stat'][7] != $b['stat'][7]) {
					return $b['stat'][7] < $a['stat'][7] ? -1 : 1;
				}
				else {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
			}
		}

		return 0;
	}

	public function download_file()
	{
		if ($this->options['inmemory'] == 0) {
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return NULL;
		}

		switch ($this->options['type']) {
		case 'zip':
			header('Content-Type: application/zip');
			break;

		case 'bzip':
			header('Content-Type: application/x-bzip2');
			break;

		case 'gzip':
			header('Content-Type: application/x-gzip');
			break;

		case 'tar':
			header('Content-Type: application/x-tar');
		}

		$_obf_YfrY8VEd = 'Content-Disposition: attachment; filename="';
		$_obf_YfrY8VEd .= (strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name']);
		$_obf_YfrY8VEd .= '"';
		header($_obf_YfrY8VEd);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}

class gzip_file extends tar_file
{
	public function gzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = 'gzip';
	}

	public function create_gzip()
	{
		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);

			if ($_obf_YBY_ = gzopen($this->options['name'], 'wb' . $this->options['level'])) {
				fseek($this->archive, 0);

				while ($_obf_SeV31Q__ = fread($this->archive, 1048576)) {
					gzwrite($_obf_YBY_, $_obf_SeV31Q__);
				}

				gzclose($_obf_YBY_);
				chdir($_obf_4HS1);
			}
			else {
				$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
				chdir($_obf_4HS1);
				return 0;
			}
		}
		else {
			$this->archive = gzencode($this->archive, $this->options['level']);
		}

		return 1;
	}

	public function open_archive()
	{
		return @gzopen($this->options['name'], 'rb');
	}

	public function tar_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'tar';
	}

	public function create_tar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current) {
			if ($current['name'] == $this->options['name']) {
				continue;
			}

			if (99 < strlen($current['name2'])) {
				$path = substr($current['name2'], 0, strpos($current['name2'], '/', strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'], strlen($path));
				if ((154 < strlen($path)) || (99 < strlen($current['name2']))) {
					$this->error[] = 'Could not add ' . $path . $current['name2'] . ' to archive because the filename is too long.';
					continue;
				}
			}

			$block = pack('a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12', $current['name2'], sprintf('%07o', $current['stat'][2]), sprintf('%07o', $current['stat'][4]), sprintf('%07o', $current['stat'][5]), sprintf('%011o', $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf('%011o', $current['stat'][9]), '        ', $current['type'], $current['type'] == 2 ? @readlink($current['name']) : '', 'ustar ', ' ', 'Unknown', 'Unknown', '', '', !empty($path) ? $path : '', '');
			$checksum = 0;
			$i = 0;

			for (; $i < 512; $i++) {
				$checksum += ord(substr($block, $i, 1));
			}

			$checksum = pack('a8', sprintf('%07o', $checksum));
			$block = substr_replace($block, $checksum, 148, 8);
			if (($current['type'] == 2) || ($current['stat'][7] == 0)) {
				$this->add_data($block);
			}
			else if ($fp = @fopen($current['name'], 'rb')) {
				$this->add_data($block);

				while ($temp = fread($fp, 1048576)) {
					$this->add_data($temp);
				}

				if (0 < ($current['stat'][7] % 512)) {
					$temp = '';
					$i = 0;

					for (; $i < (512 - ($current['stat'][7] % 512)); $i++) {
						$temp .= '' . "\0" . '';
					}

					$this->add_data($temp);
				}

				fclose($fp);
			}
			else {
				$this->error[] = 'Could not open file ' . $current['name'] . ' for reading. It was not added.';
			}
		}

		$this->add_data(pack('a1024', ''));
		chdir($pwd);
		return 1;
	}

	public function extract_files()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if ($fp = $this->open_archive()) {
			if ($this->options['inmemory'] == 1) {
				$this->files = array();
			}

			while ($block = fread($fp, 512)) {
				$temp = unpack('a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp', $block);
				$file = array(
					'name'     => $temp['prefix'] . $temp['name'],
					'stat'     => array(2 => $temp['mode'], 4 => octdec($temp['uid']), 5 => octdec($temp['gid']), 7 => octdec($temp['size']), 9 => octdec($temp['mtime'])),
					'checksum' => octdec($temp['checksum']),
					'type'     => $temp['type'],
					'magic'    => $temp['magic']
					);

				if ($file['checksum'] == 0) {
					break;
				}
				else if (substr($file['magic'], 0, 5) != 'ustar') {
					$this->error[] = 'This script does not support extracting this type of tar file.';
					break;
				}

				$block = substr_replace($block, '        ', 148, 8);
				$checksum = 0;
				$i = 0;

				for (; $i < 512; $i++) {
					$checksum += ord(substr($block, $i, 1));
				}

				if ($file['checksum'] != $checksum) {
					$this->error[] = 'Could not extract from ' . $this->options['name'] . ', it is corrupt.';
				}

				if ($this->options['inmemory'] == 1) {
					$file['data'] = fread($fp, $file['stat'][7]);
					fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
					unset($file['checksum']);
					unset($file['magic']);
					$this->files[] = $file;
				}
				else if ($file['type'] == 5) {
					if (!is_dir($file['name'])) {
						mkdir($file['name'], $file['stat'][2]);
					}
				}
				else {
					if (($this->options['overwrite'] == 0) && file_exists($file['name'])) {
						$this->error[] = $file['name'] . ' already exists.';
						continue;
					}
					else if ($file['type'] == 2) {
						symlink($temp['symlink'], $file['name']);
						chmod($file['name'], $file['stat'][2]);
					}
					else if ($new = @fopen($file['name'], 'wb')) {
						fwrite($new, fread($fp, $file['stat'][7]));
						fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
						fclose($new);
						chmod($file['name'], $file['stat'][2]);
					}
					else {
						$this->error[] = 'Could not open ' . $file['name'] . ' for writing.';
						continue;
					}
				}

				chown($file['name'], $file['stat'][4]);
				chgrp($file['name'], $file['stat'][5]);
				touch($file['name'], $file['stat'][9]);
				unset($file);
			}
		}
		else {
			$this->error[] = 'Could not open file ' . $this->options['name'];
		}

		chdir($pwd);
	}

	public function archive($name)
	{
		$this->options = array('basedir' => '.', 'name' => $name, 'prepend' => '', 'inmemory' => 0, 'overwrite' => 0, 'recurse' => 1, 'storepaths' => 1, 'followlinks' => 0, 'level' => 3, 'method' => 1, 'sfx' => '', 'type' => '', 'comment' => '');
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}

	public function set_options($options)
	{
		foreach ($options as $_obf_Vwty => $_obf_VgKtFeg_) {
			$this->options[$_obf_Vwty] = $_obf_VgKtFeg_;
		}

		if (!empty($this->options['basedir'])) {
			$this->options['basedir'] = str_replace('\\', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/+/', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/$/', '', $this->options['basedir']);
		}

		if (!empty($this->options['name'])) {
			$this->options['name'] = str_replace('\\', '/', $this->options['name']);
			$this->options['name'] = preg_replace('/\\/+/', '/', $this->options['name']);
		}

		if (!empty($this->options['prepend'])) {
			$this->options['prepend'] = str_replace('\\', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/^(\\.*\\/+)+/', '', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/+/', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/$/', '', $this->options['prepend']) . '/';
		}
	}

	public function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);
			if (($this->options['overwrite'] == 0) && file_exists($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''))) {
				$this->error[] = 'File ' . $this->options['name'] . ' already exists.';
				chdir($_obf_4HS1);
				return 0;
			}
			else {
				if ($this->archive = @fopen($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''), 'wb+')) {
					chdir($_obf_4HS1);
				}
				else {
					$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
					chdir($_obf_4HS1);
					return 0;
				}
			}
		}
		else {
			$this->archive = '';
		}

		switch ($this->options['type']) {
		case 'zip':
			if (!$this->create_zip()) {
				$this->error[] = 'Could not create zip file.';
				return 0;
			}

			break;

		case 'bzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_bzip()) {
				$this->error[] = 'Could not create bzip2 file.';
				return 0;
			}

			break;

		case 'gzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_gzip()) {
				$this->error[] = 'Could not create gzip file.';
				return 0;
			}

			break;

		case 'tar':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			break;
		}

		if ($this->options['inmemory'] == 0) {
			fclose($this->archive);
			if (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip')) {
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}

		return 1;
	}

	public function add_data($data)
	{
		if ($this->options['inmemory'] == 0) {
			fwrite($this->archive, $data);
		}
		else {
			$this->archive .= $data;
		}
	}

	public function make_list()
	{
		if (!empty($this->exclude)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->exclude as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						unset($this->files[$_obf_Vwty]);
					}
				}
			}
		}

		if (!empty($this->storeonly)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->storeonly as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						$this->files[$_obf_Vwty]['method'] = 0;
					}
				}
			}
		}

		unset($this->exclude);
		unset($this->storeonly);
	}

	public function add_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->files[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function exclude_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->exclude[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function store_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->storeonly[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function list_files($list)
	{
		if (!is_array($list)) {
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current) {
			$current = str_replace('\\', '/', $current);
			$current = preg_replace('/\\/+/', '/', $current);
			$current = preg_replace('/\\/$/', '', $current);

			if (strstr($current, '*')) {
				$regex = preg_replace('/([\\\\^$\\.\\[\\]\\|\\(\\)\\?\\+\\{\\}\\/])/', '\\\\\\1', $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = (strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.');
				$temp = $this->parse_dir($dir);

				foreach ($temp as $current2) {
					if (preg_match('/^' . $regex . '$/i', $current2['name'])) {
						$files[] = $current2;
					}
				}

				unset($regex);
				unset($dir);
				unset($temp);
				unset($current);
			}
			else if (@is_dir($current)) {
				$temp = $this->parse_dir($current);

				foreach ($temp as $file) {
					$files[] = $file;
				}

				unset($temp);
				unset($file);
			}
			else if (@file_exists($current)) {
				$files[] = array('name' => $current, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($current, '/') ? substr($current, strrpos($current, '/') + 1) : $current), 'type' => @is_link($current) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
			else {
				echo 'other error ';
			}
		}

		chdir($pwd);
		unset($current);
		unset($pwd);
		usort($files, array('archive', 'sort_files'));
		return $files;
	}

	public function parse_dir($dirname)
	{
		if (($this->options['storepaths'] == 1) && !preg_match('/^(\\.+\\/*)+$/', $dirname)) {
			$_obf_I9APXAk_ = array(
				array('name' => $dirname, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($dirname, '/') ? substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname))
				);
		}
		else {
			$_obf_I9APXAk_ = array();
		}

		$_obf_Fwl1 = @opendir($dirname);

		while ($_obf_6hS1Rw__ = @readdir($_obf_Fwl1)) {
			$_obf_pWCgaILc0RE_ = $dirname . '/' . $_obf_6hS1Rw__;
			if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
				continue;
			}
			else if (@is_dir($_obf_pWCgaILc0RE_)) {
				if (empty($this->options['recurse'])) {
					continue;
				}

				$_obf_SeV31Q__ = $this->parse_dir($_obf_pWCgaILc0RE_);

				foreach ($_obf_SeV31Q__ as $_obf_enX6mB4_) {
					$_obf_I9APXAk_[] = $_obf_enX6mB4_;
				}
			}
			else if (@file_exists($_obf_pWCgaILc0RE_)) {
				$_obf_I9APXAk_[] = array('name' => $_obf_pWCgaILc0RE_, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($_obf_pWCgaILc0RE_, '/') ? substr($_obf_pWCgaILc0RE_, strrpos($_obf_pWCgaILc0RE_, '/') + 1) : $_obf_pWCgaILc0RE_), 'type' => @is_link($_obf_pWCgaILc0RE_) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($_obf_6hS1Rw__, strrpos($_obf_6hS1Rw__, '.')), 'stat' => stat($_obf_pWCgaILc0RE_));
			}
		}

		@closedir($_obf_Fwl1);
		return $_obf_I9APXAk_;
	}

	public function sort_files($a, $b)
	{
		if ($a['type'] != $b['type']) {
			if (($a['type'] == 5) || ($b['type'] == 2)) {
				return -1;
			}
			else {
				if (($a['type'] == 2) || ($b['type'] == 5)) {
					return 1;
				}
				else if ($a['type'] == 5) {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
				else if ($a['ext'] != $b['ext']) {
					return strcmp($a['ext'], $b['ext']);
				}
				else if ($a['stat'][7] != $b['stat'][7]) {
					return $b['stat'][7] < $a['stat'][7] ? -1 : 1;
				}
				else {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
			}
		}

		return 0;
	}

	public function download_file()
	{
		if ($this->options['inmemory'] == 0) {
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return NULL;
		}

		switch ($this->options['type']) {
		case 'zip':
			header('Content-Type: application/zip');
			break;

		case 'bzip':
			header('Content-Type: application/x-bzip2');
			break;

		case 'gzip':
			header('Content-Type: application/x-gzip');
			break;

		case 'tar':
			header('Content-Type: application/x-tar');
		}

		$_obf_YfrY8VEd = 'Content-Disposition: attachment; filename="';
		$_obf_YfrY8VEd .= (strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name']);
		$_obf_YfrY8VEd .= '"';
		header($_obf_YfrY8VEd);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}

class bzip_file extends tar_file
{
	public function bzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = 'bzip';
	}

	public function create_bzip()
	{
		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);

			if ($_obf_YBY_ = bzopen($this->options['name'], 'wb')) {
				fseek($this->archive, 0);

				while ($_obf_SeV31Q__ = fread($this->archive, 1048576)) {
					bzwrite($_obf_YBY_, $_obf_SeV31Q__);
				}

				bzclose($_obf_YBY_);
				chdir($_obf_4HS1);
			}
			else {
				$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
				chdir($_obf_4HS1);
				return 0;
			}
		}
		else {
			$this->archive = bzcompress($this->archive, $this->options['level']);
		}

		return 1;
	}

	public function open_archive()
	{
		return @bzopen($this->options['name'], 'rb');
	}

	public function tar_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'tar';
	}

	public function create_tar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current) {
			if ($current['name'] == $this->options['name']) {
				continue;
			}

			if (99 < strlen($current['name2'])) {
				$path = substr($current['name2'], 0, strpos($current['name2'], '/', strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'], strlen($path));
				if ((154 < strlen($path)) || (99 < strlen($current['name2']))) {
					$this->error[] = 'Could not add ' . $path . $current['name2'] . ' to archive because the filename is too long.';
					continue;
				}
			}

			$block = pack('a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12', $current['name2'], sprintf('%07o', $current['stat'][2]), sprintf('%07o', $current['stat'][4]), sprintf('%07o', $current['stat'][5]), sprintf('%011o', $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf('%011o', $current['stat'][9]), '        ', $current['type'], $current['type'] == 2 ? @readlink($current['name']) : '', 'ustar ', ' ', 'Unknown', 'Unknown', '', '', !empty($path) ? $path : '', '');
			$checksum = 0;
			$i = 0;

			for (; $i < 512; $i++) {
				$checksum += ord(substr($block, $i, 1));
			}

			$checksum = pack('a8', sprintf('%07o', $checksum));
			$block = substr_replace($block, $checksum, 148, 8);
			if (($current['type'] == 2) || ($current['stat'][7] == 0)) {
				$this->add_data($block);
			}
			else if ($fp = @fopen($current['name'], 'rb')) {
				$this->add_data($block);

				while ($temp = fread($fp, 1048576)) {
					$this->add_data($temp);
				}

				if (0 < ($current['stat'][7] % 512)) {
					$temp = '';
					$i = 0;

					for (; $i < (512 - ($current['stat'][7] % 512)); $i++) {
						$temp .= '' . "\0" . '';
					}

					$this->add_data($temp);
				}

				fclose($fp);
			}
			else {
				$this->error[] = 'Could not open file ' . $current['name'] . ' for reading. It was not added.';
			}
		}

		$this->add_data(pack('a1024', ''));
		chdir($pwd);
		return 1;
	}

	public function extract_files()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if ($fp = $this->open_archive()) {
			if ($this->options['inmemory'] == 1) {
				$this->files = array();
			}

			while ($block = fread($fp, 512)) {
				$temp = unpack('a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp', $block);
				$file = array(
					'name'     => $temp['prefix'] . $temp['name'],
					'stat'     => array(2 => $temp['mode'], 4 => octdec($temp['uid']), 5 => octdec($temp['gid']), 7 => octdec($temp['size']), 9 => octdec($temp['mtime'])),
					'checksum' => octdec($temp['checksum']),
					'type'     => $temp['type'],
					'magic'    => $temp['magic']
					);

				if ($file['checksum'] == 0) {
					break;
				}
				else if (substr($file['magic'], 0, 5) != 'ustar') {
					$this->error[] = 'This script does not support extracting this type of tar file.';
					break;
				}

				$block = substr_replace($block, '        ', 148, 8);
				$checksum = 0;
				$i = 0;

				for (; $i < 512; $i++) {
					$checksum += ord(substr($block, $i, 1));
				}

				if ($file['checksum'] != $checksum) {
					$this->error[] = 'Could not extract from ' . $this->options['name'] . ', it is corrupt.';
				}

				if ($this->options['inmemory'] == 1) {
					$file['data'] = fread($fp, $file['stat'][7]);
					fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
					unset($file['checksum']);
					unset($file['magic']);
					$this->files[] = $file;
				}
				else if ($file['type'] == 5) {
					if (!is_dir($file['name'])) {
						mkdir($file['name'], $file['stat'][2]);
					}
				}
				else {
					if (($this->options['overwrite'] == 0) && file_exists($file['name'])) {
						$this->error[] = $file['name'] . ' already exists.';
						continue;
					}
					else if ($file['type'] == 2) {
						symlink($temp['symlink'], $file['name']);
						chmod($file['name'], $file['stat'][2]);
					}
					else if ($new = @fopen($file['name'], 'wb')) {
						fwrite($new, fread($fp, $file['stat'][7]));
						fread($fp, (512 - ($file['stat'][7] % 512)) == 512 ? 0 : 512 - ($file['stat'][7] % 512));
						fclose($new);
						chmod($file['name'], $file['stat'][2]);
					}
					else {
						$this->error[] = 'Could not open ' . $file['name'] . ' for writing.';
						continue;
					}
				}

				chown($file['name'], $file['stat'][4]);
				chgrp($file['name'], $file['stat'][5]);
				touch($file['name'], $file['stat'][9]);
				unset($file);
			}
		}
		else {
			$this->error[] = 'Could not open file ' . $this->options['name'];
		}

		chdir($pwd);
	}

	public function archive($name)
	{
		$this->options = array('basedir' => '.', 'name' => $name, 'prepend' => '', 'inmemory' => 0, 'overwrite' => 0, 'recurse' => 1, 'storepaths' => 1, 'followlinks' => 0, 'level' => 3, 'method' => 1, 'sfx' => '', 'type' => '', 'comment' => '');
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}

	public function set_options($options)
	{
		foreach ($options as $_obf_Vwty => $_obf_VgKtFeg_) {
			$this->options[$_obf_Vwty] = $_obf_VgKtFeg_;
		}

		if (!empty($this->options['basedir'])) {
			$this->options['basedir'] = str_replace('\\', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/+/', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/$/', '', $this->options['basedir']);
		}

		if (!empty($this->options['name'])) {
			$this->options['name'] = str_replace('\\', '/', $this->options['name']);
			$this->options['name'] = preg_replace('/\\/+/', '/', $this->options['name']);
		}

		if (!empty($this->options['prepend'])) {
			$this->options['prepend'] = str_replace('\\', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/^(\\.*\\/+)+/', '', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/+/', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/$/', '', $this->options['prepend']) . '/';
		}
	}

	public function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);
			if (($this->options['overwrite'] == 0) && file_exists($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''))) {
				$this->error[] = 'File ' . $this->options['name'] . ' already exists.';
				chdir($_obf_4HS1);
				return 0;
			}
			else {
				if ($this->archive = @fopen($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''), 'wb+')) {
					chdir($_obf_4HS1);
				}
				else {
					$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
					chdir($_obf_4HS1);
					return 0;
				}
			}
		}
		else {
			$this->archive = '';
		}

		switch ($this->options['type']) {
		case 'zip':
			if (!$this->create_zip()) {
				$this->error[] = 'Could not create zip file.';
				return 0;
			}

			break;

		case 'bzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_bzip()) {
				$this->error[] = 'Could not create bzip2 file.';
				return 0;
			}

			break;

		case 'gzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_gzip()) {
				$this->error[] = 'Could not create gzip file.';
				return 0;
			}

			break;

		case 'tar':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			break;
		}

		if ($this->options['inmemory'] == 0) {
			fclose($this->archive);
			if (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip')) {
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}

		return 1;
	}

	public function add_data($data)
	{
		if ($this->options['inmemory'] == 0) {
			fwrite($this->archive, $data);
		}
		else {
			$this->archive .= $data;
		}
	}

	public function make_list()
	{
		if (!empty($this->exclude)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->exclude as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						unset($this->files[$_obf_Vwty]);
					}
				}
			}
		}

		if (!empty($this->storeonly)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->storeonly as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						$this->files[$_obf_Vwty]['method'] = 0;
					}
				}
			}
		}

		unset($this->exclude);
		unset($this->storeonly);
	}

	public function add_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->files[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function exclude_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->exclude[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function store_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->storeonly[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function list_files($list)
	{
		if (!is_array($list)) {
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current) {
			$current = str_replace('\\', '/', $current);
			$current = preg_replace('/\\/+/', '/', $current);
			$current = preg_replace('/\\/$/', '', $current);

			if (strstr($current, '*')) {
				$regex = preg_replace('/([\\\\^$\\.\\[\\]\\|\\(\\)\\?\\+\\{\\}\\/])/', '\\\\\\1', $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = (strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.');
				$temp = $this->parse_dir($dir);

				foreach ($temp as $current2) {
					if (preg_match('/^' . $regex . '$/i', $current2['name'])) {
						$files[] = $current2;
					}
				}

				unset($regex);
				unset($dir);
				unset($temp);
				unset($current);
			}
			else if (@is_dir($current)) {
				$temp = $this->parse_dir($current);

				foreach ($temp as $file) {
					$files[] = $file;
				}

				unset($temp);
				unset($file);
			}
			else if (@file_exists($current)) {
				$files[] = array('name' => $current, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($current, '/') ? substr($current, strrpos($current, '/') + 1) : $current), 'type' => @is_link($current) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
			else {
				echo 'other error ';
			}
		}

		chdir($pwd);
		unset($current);
		unset($pwd);
		usort($files, array('archive', 'sort_files'));
		return $files;
	}

	public function parse_dir($dirname)
	{
		if (($this->options['storepaths'] == 1) && !preg_match('/^(\\.+\\/*)+$/', $dirname)) {
			$_obf_I9APXAk_ = array(
				array('name' => $dirname, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($dirname, '/') ? substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname))
				);
		}
		else {
			$_obf_I9APXAk_ = array();
		}

		$_obf_Fwl1 = @opendir($dirname);

		while ($_obf_6hS1Rw__ = @readdir($_obf_Fwl1)) {
			$_obf_pWCgaILc0RE_ = $dirname . '/' . $_obf_6hS1Rw__;
			if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
				continue;
			}
			else if (@is_dir($_obf_pWCgaILc0RE_)) {
				if (empty($this->options['recurse'])) {
					continue;
				}

				$_obf_SeV31Q__ = $this->parse_dir($_obf_pWCgaILc0RE_);

				foreach ($_obf_SeV31Q__ as $_obf_enX6mB4_) {
					$_obf_I9APXAk_[] = $_obf_enX6mB4_;
				}
			}
			else if (@file_exists($_obf_pWCgaILc0RE_)) {
				$_obf_I9APXAk_[] = array('name' => $_obf_pWCgaILc0RE_, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($_obf_pWCgaILc0RE_, '/') ? substr($_obf_pWCgaILc0RE_, strrpos($_obf_pWCgaILc0RE_, '/') + 1) : $_obf_pWCgaILc0RE_), 'type' => @is_link($_obf_pWCgaILc0RE_) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($_obf_6hS1Rw__, strrpos($_obf_6hS1Rw__, '.')), 'stat' => stat($_obf_pWCgaILc0RE_));
			}
		}

		@closedir($_obf_Fwl1);
		return $_obf_I9APXAk_;
	}

	public function sort_files($a, $b)
	{
		if ($a['type'] != $b['type']) {
			if (($a['type'] == 5) || ($b['type'] == 2)) {
				return -1;
			}
			else {
				if (($a['type'] == 2) || ($b['type'] == 5)) {
					return 1;
				}
				else if ($a['type'] == 5) {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
				else if ($a['ext'] != $b['ext']) {
					return strcmp($a['ext'], $b['ext']);
				}
				else if ($a['stat'][7] != $b['stat'][7]) {
					return $b['stat'][7] < $a['stat'][7] ? -1 : 1;
				}
				else {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
			}
		}

		return 0;
	}

	public function download_file()
	{
		if ($this->options['inmemory'] == 0) {
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return NULL;
		}

		switch ($this->options['type']) {
		case 'zip':
			header('Content-Type: application/zip');
			break;

		case 'bzip':
			header('Content-Type: application/x-bzip2');
			break;

		case 'gzip':
			header('Content-Type: application/x-gzip');
			break;

		case 'tar':
			header('Content-Type: application/x-tar');
		}

		$_obf_YfrY8VEd = 'Content-Disposition: attachment; filename="';
		$_obf_YfrY8VEd .= (strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name']);
		$_obf_YfrY8VEd .= '"';
		header($_obf_YfrY8VEd);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}

class zip_file extends archive
{
	public function zip_file($name)
	{
		$this->archive($name);
		$this->options['type'] = 'zip';
	}

	public function create_zip()
	{
		$files = 0;
		$offset = 0;
		$central = '';

		if (!empty($this->options['sfx'])) {
			if ($fp = @fopen($this->options['sfx'], 'rb')) {
				$temp = fread($fp, filesize($this->options['sfx']));
				fclose($fp);
				$this->add_data($temp);
				$offset += strlen($temp);
				unset($temp);
			}
			else {
				$this->error[] = 'Could not open sfx module from ' . $this->options['sfx'] . '.';
			}
		}

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($this->files as $current) {
			if ($current['name'] == $this->options['name']) {
				continue;
			}

			$timedate = explode(' ', date('Y n j G i s', $current['stat'][9]));
			$timedate = (($timedate[0] - 1980) << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) | ($timedate[3] << 11) | ($timedate[4] << 5) | $timedate[5];
			$block = pack('VvvvV', 67324752, 10, 0, isset($current['method']) || ($this->options['method'] == 0) ? 0 : 8, $timedate);
			if (($current['stat'][7] == 0) && ($current['type'] == 5)) {
				$block .= pack('VVVvv', 0, 0, 0, strlen($current['name2']) + 1, 0);
				$block .= $current['name2'] . '/';
				$this->add_data($block);
				$central .= pack('VvvvvVVVVvvvvvVV', 33639248, 20, $this->options['method'] == 0 ? 0 : 10, 0, isset($current['method']) || ($this->options['method'] == 0) ? 0 : 8, $timedate, 0, 0, 0, strlen($current['name2']) + 1, 0, 0, 0, 0, $current['type'] == 5 ? 16 : 0, $offset);
				$central .= $current['name2'] . '/';
				$files++;
				$offset += 31 + strlen($current['name2']);
			}
			else if ($current['stat'][7] == 0) {
				$block .= pack('VVVvv', 0, 0, 0, strlen($current['name2']), 0);
				$block .= $current['name2'];
				$this->add_data($block);
				$central .= pack('VvvvvVVVVvvvvvVV', 33639248, 20, $this->options['method'] == 0 ? 0 : 10, 0, isset($current['method']) || ($this->options['method'] == 0) ? 0 : 8, $timedate, 0, 0, 0, strlen($current['name2']), 0, 0, 0, 0, $current['type'] == 5 ? 16 : 0, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += 30 + strlen($current['name2']);
			}
			else if ($fp = @fopen($current['name'], 'rb')) {
				$temp = fread($fp, $current['stat'][7]);
				fclose($fp);
				$crc32 = crc32($temp);
				if (!isset($current['method']) && ($this->options['method'] == 1)) {
					$temp = gzcompress($temp, $this->options['level']);
					$size = strlen($temp) - 6;
					$temp = substr($temp, 2, $size);
				}
				else {
					$size = strlen($temp);
				}

				$block .= pack('VVVvv', $crc32, $size, $current['stat'][7], strlen($current['name2']), 0);
				$block .= $current['name2'];
				$this->add_data($block);
				$this->add_data($temp);
				unset($temp);
				$central .= pack('VvvvvVVVVvvvvvVV', 33639248, 20, $this->options['method'] == 0 ? 0 : 10, 0, isset($current['method']) || ($this->options['method'] == 0) ? 0 : 8, $timedate, $crc32, $size, $current['stat'][7], strlen($current['name2']), 0, 0, 0, 0, 0, $offset);
				$central .= $current['name2'];
				$files++;
				$offset += 30 + strlen($current['name2']) + $size;
			}
			else {
				$this->error[] = 'Could not open file ' . $current['name'] . ' for reading. It was not added.';
			}
		}

		$this->add_data($central);
		$this->add_data(pack('VvvvvVVv', 101010256, 0, 0, $files, $files, strlen($central), $offset, !empty($this->options['comment']) ? strlen($this->options['comment']) : 0));

		if (!empty($this->options['comment'])) {
			$this->add_data($this->options['comment']);
		}

		chdir($pwd);
		return 1;
	}

	public function archive($name)
	{
		$this->options = array('basedir' => '.', 'name' => $name, 'prepend' => '', 'inmemory' => 0, 'overwrite' => 0, 'recurse' => 1, 'storepaths' => 1, 'followlinks' => 0, 'level' => 3, 'method' => 1, 'sfx' => '', 'type' => '', 'comment' => '');
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}

	public function set_options($options)
	{
		foreach ($options as $_obf_Vwty => $_obf_VgKtFeg_) {
			$this->options[$_obf_Vwty] = $_obf_VgKtFeg_;
		}

		if (!empty($this->options['basedir'])) {
			$this->options['basedir'] = str_replace('\\', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/+/', '/', $this->options['basedir']);
			$this->options['basedir'] = preg_replace('/\\/$/', '', $this->options['basedir']);
		}

		if (!empty($this->options['name'])) {
			$this->options['name'] = str_replace('\\', '/', $this->options['name']);
			$this->options['name'] = preg_replace('/\\/+/', '/', $this->options['name']);
		}

		if (!empty($this->options['prepend'])) {
			$this->options['prepend'] = str_replace('\\', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/^(\\.*\\/+)+/', '', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/+/', '/', $this->options['prepend']);
			$this->options['prepend'] = preg_replace('/\\/$/', '', $this->options['prepend']) . '/';
		}
	}

	public function create_archive()
	{
		$this->make_list();

		if ($this->options['inmemory'] == 0) {
			$_obf_4HS1 = getcwd();
			chdir($this->options['basedir']);
			if (($this->options['overwrite'] == 0) && file_exists($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''))) {
				$this->error[] = 'File ' . $this->options['name'] . ' already exists.';
				chdir($_obf_4HS1);
				return 0;
			}
			else {
				if ($this->archive = @fopen($this->options['name'] . (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip') ? '.tmp' : ''), 'wb+')) {
					chdir($_obf_4HS1);
				}
				else {
					$this->error[] = 'Could not open ' . $this->options['name'] . ' for writing.';
					chdir($_obf_4HS1);
					return 0;
				}
			}
		}
		else {
			$this->archive = '';
		}

		switch ($this->options['type']) {
		case 'zip':
			if (!$this->create_zip()) {
				$this->error[] = 'Could not create zip file.';
				return 0;
			}

			break;

		case 'bzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_bzip()) {
				$this->error[] = 'Could not create bzip2 file.';
				return 0;
			}

			break;

		case 'gzip':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			if (!$this->create_gzip()) {
				$this->error[] = 'Could not create gzip file.';
				return 0;
			}

			break;

		case 'tar':
			if (!$this->create_tar()) {
				$this->error[] = 'Could not create tar file.';
				return 0;
			}

			break;
		}

		if ($this->options['inmemory'] == 0) {
			fclose($this->archive);
			if (($this->options['type'] == 'gzip') || ($this->options['type'] == 'bzip')) {
				unlink($this->options['basedir'] . '/' . $this->options['name'] . '.tmp');
			}
		}

		return 1;
	}

	public function add_data($data)
	{
		if ($this->options['inmemory'] == 0) {
			fwrite($this->archive, $data);
		}
		else {
			$this->archive .= $data;
		}
	}

	public function make_list()
	{
		if (!empty($this->exclude)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->exclude as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						unset($this->files[$_obf_Vwty]);
					}
				}
			}
		}

		if (!empty($this->storeonly)) {
			foreach ($this->files as $_obf_Vwty => $_obf_VgKtFeg_) {
				foreach ($this->storeonly as $_obf_IMC7ZTzqtA__) {
					if ($_obf_VgKtFeg_['name'] == $_obf_IMC7ZTzqtA__['name']) {
						$this->files[$_obf_Vwty]['method'] = 0;
					}
				}
			}
		}

		unset($this->exclude);
		unset($this->storeonly);
	}

	public function add_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->files[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function exclude_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->exclude[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function store_files($list)
	{
		$_obf_SeV31Q__ = $this->list_files($list);

		foreach ($_obf_SeV31Q__ as $_obf_IMC7ZTzqtA__) {
			$this->storeonly[] = $_obf_IMC7ZTzqtA__;
		}
	}

	public function list_files($list)
	{
		if (!is_array($list)) {
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach ($list as $current) {
			$current = str_replace('\\', '/', $current);
			$current = preg_replace('/\\/+/', '/', $current);
			$current = preg_replace('/\\/$/', '', $current);

			if (strstr($current, '*')) {
				$regex = preg_replace('/([\\\\^$\\.\\[\\]\\|\\(\\)\\?\\+\\{\\}\\/])/', '\\\\\\1', $current);
				$regex = str_replace('*', '.*', $regex);
				$dir = (strstr($current, '/') ? substr($current, 0, strrpos($current, '/')) : '.');
				$temp = $this->parse_dir($dir);

				foreach ($temp as $current2) {
					if (preg_match('/^' . $regex . '$/i', $current2['name'])) {
						$files[] = $current2;
					}
				}

				unset($regex);
				unset($dir);
				unset($temp);
				unset($current);
			}
			else if (@is_dir($current)) {
				$temp = $this->parse_dir($current);

				foreach ($temp as $file) {
					$files[] = $file;
				}

				unset($temp);
				unset($file);
			}
			else if (@file_exists($current)) {
				$files[] = array('name' => $current, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($current, '/') ? substr($current, strrpos($current, '/') + 1) : $current), 'type' => @is_link($current) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($current, strrpos($current, '.')), 'stat' => stat($current));
			}
			else {
				echo 'other error ';
			}
		}

		chdir($pwd);
		unset($current);
		unset($pwd);
		usort($files, array('archive', 'sort_files'));
		return $files;
	}

	public function parse_dir($dirname)
	{
		if (($this->options['storepaths'] == 1) && !preg_match('/^(\\.+\\/*)+$/', $dirname)) {
			$_obf_I9APXAk_ = array(
				array('name' => $dirname, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($dirname, '/') ? substr($dirname, strrpos($dirname, '/') + 1) : $dirname), 'type' => 5, 'stat' => stat($dirname))
				);
		}
		else {
			$_obf_I9APXAk_ = array();
		}

		$_obf_Fwl1 = @opendir($dirname);

		while ($_obf_6hS1Rw__ = @readdir($_obf_Fwl1)) {
			$_obf_pWCgaILc0RE_ = $dirname . '/' . $_obf_6hS1Rw__;
			if (($_obf_6hS1Rw__ == '.') || ($_obf_6hS1Rw__ == '..')) {
				continue;
			}
			else if (@is_dir($_obf_pWCgaILc0RE_)) {
				if (empty($this->options['recurse'])) {
					continue;
				}

				$_obf_SeV31Q__ = $this->parse_dir($_obf_pWCgaILc0RE_);

				foreach ($_obf_SeV31Q__ as $_obf_enX6mB4_) {
					$_obf_I9APXAk_[] = $_obf_enX6mB4_;
				}
			}
			else if (@file_exists($_obf_pWCgaILc0RE_)) {
				$_obf_I9APXAk_[] = array('name' => $_obf_pWCgaILc0RE_, 'name2' => $this->options['prepend'] . preg_replace('/(\\.+\\/+)+/', '', ($this->options['storepaths'] == 0) && strstr($_obf_pWCgaILc0RE_, '/') ? substr($_obf_pWCgaILc0RE_, strrpos($_obf_pWCgaILc0RE_, '/') + 1) : $_obf_pWCgaILc0RE_), 'type' => @is_link($_obf_pWCgaILc0RE_) && ($this->options['followlinks'] == 0) ? 2 : 0, 'ext' => substr($_obf_6hS1Rw__, strrpos($_obf_6hS1Rw__, '.')), 'stat' => stat($_obf_pWCgaILc0RE_));
			}
		}

		@closedir($_obf_Fwl1);
		return $_obf_I9APXAk_;
	}

	public function sort_files($a, $b)
	{
		if ($a['type'] != $b['type']) {
			if (($a['type'] == 5) || ($b['type'] == 2)) {
				return -1;
			}
			else {
				if (($a['type'] == 2) || ($b['type'] == 5)) {
					return 1;
				}
				else if ($a['type'] == 5) {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
				else if ($a['ext'] != $b['ext']) {
					return strcmp($a['ext'], $b['ext']);
				}
				else if ($a['stat'][7] != $b['stat'][7]) {
					return $b['stat'][7] < $a['stat'][7] ? -1 : 1;
				}
				else {
					return strcmp(strtolower($a['name']), strtolower($b['name']));
				}
			}
		}

		return 0;
	}

	public function download_file()
	{
		if ($this->options['inmemory'] == 0) {
			$this->error[] = 'Can only use download_file() if archive is in memory. Redirect to file otherwise, it is faster.';
			return NULL;
		}

		switch ($this->options['type']) {
		case 'zip':
			header('Content-Type: application/zip');
			break;

		case 'bzip':
			header('Content-Type: application/x-bzip2');
			break;

		case 'gzip':
			header('Content-Type: application/x-gzip');
			break;

		case 'tar':
			header('Content-Type: application/x-tar');
		}

		$_obf_YfrY8VEd = 'Content-Disposition: attachment; filename="';
		$_obf_YfrY8VEd .= (strstr($this->options['name'], '/') ? substr($this->options['name'], strrpos($this->options['name'], '/') + 1) : $this->options['name']);
		$_obf_YfrY8VEd .= '"';
		header($_obf_YfrY8VEd);
		header('Content-Length: ' . strlen($this->archive));
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: no-cache, must-revalidate, max-age=60');
		header('Expires: Sat, 01 Jan 2000 12:00:00 GMT');
		print($this->archive);
	}
}


?>
