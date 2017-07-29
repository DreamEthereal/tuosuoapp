<?php
//dezend by http://www.yunlu99.com/
class Zip
{
	public $zipMemoryThreshold = 1048576;
	public $endOfCentralDirectory = "PK\0\0\0\0";
	public $localFileHeader = 'PK';
	public $centralFileHeader = 'PK';
	public $zipData;
	public $zipFile;
	public $zipComment;
	public $cdRec = array();
	public $offset = 0;
	public $isFinalized = false;
	public $streamChunkSize = 65536;
	public $streamFilePath;
	public $streamTimeStamp;
	public $streamComment;
	public $streamFile;
	public $streamData;
	public $streamFileLength = 0;

	public function Zip($useZipFile = false)
	{
		if ($useZipFile) {
			$this->zipFile = tmpfile();
		}
		else {
			$this->zipData = '';
		}
	}

	public function closeZip()
	{
		if (!is_null($this->zipFile)) {
			fclose($this->zipFile);
		}

		$this->zipData = NULL;
	}

	public function setComment($newComment = NULL)
	{
		$this->zipComment = $newComment;
	}

	public function setZipFile($fileName)
	{
		if (file_exists($fileName)) {
			unlink($fileName);
		}

		$_obf_UI8_ = fopen($fileName, 'x+b');

		if (!is_null($this->zipFile)) {
			rewind($this->zipFile);

			while (!feof($this->zipFile)) {
				fwrite($_obf_UI8_, fread($this->zipFile, $this->streamChunkSize));
			}

			fclose($this->zipFile);
		}
		else {
			fwrite($_obf_UI8_, $this->zipData);
			$this->zipData = NULL;
		}

		$this->zipFile = $_obf_UI8_;
	}

	public function addDirectory($directoryPath, $timestamp = 0, $fileComment = NULL)
	{
		if ($this->isFinalized) {
			return NULL;
		}

		$this->buildZipEntry($directoryPath, $fileComment, '' . "\0" . '' . "\0" . '', '' . "\0" . '' . "\0" . '', $timestamp, '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '', 0, 0, 16);
	}

	public function addFile($data, $filePath, $timestamp = 0, $fileComment = NULL)
	{
		if ($this->isFinalized) {
			return NULL;
		}

		$_obf_Ua_zf_EO = '' . "\0" . '';
		$_obf_qzz6_S6aXQ__ = '' . "\0" . '';
		$_obf_5PiCQCp6uWjFXw__ = strlen($data);
		$_obf_jVT6HLPAjA0P = pack('V', crc32($data));
		$_obf_JGA652Xh = gzcompress($data);
		$_obf_JGA652Xh = substr(substr($_obf_JGA652Xh, 0, strlen($_obf_JGA652Xh) - 4), 2);
		$_obf_yaDyhawNGCc_ = strlen($_obf_JGA652Xh);

		if ($_obf_5PiCQCp6uWjFXw__ <= $_obf_yaDyhawNGCc_) {
			$_obf_yaDyhawNGCc_ = $_obf_5PiCQCp6uWjFXw__;
			$_obf_JGA652Xh = $data;
			$_obf_Ua_zf_EO = '' . "\0" . '' . "\0" . '';
			$_obf_qzz6_S6aXQ__ = '' . "\0" . '' . "\0" . '';
		}

		if (is_null($this->zipFile) && ($this->zipMemoryThreshold < ($this->offset + $_obf_yaDyhawNGCc_))) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = NULL;
		}

		$this->buildZipEntry($filePath, $fileComment, $_obf_qzz6_S6aXQ__, $_obf_Ua_zf_EO, $timestamp, $_obf_jVT6HLPAjA0P, $_obf_yaDyhawNGCc_, $_obf_5PiCQCp6uWjFXw__, 32);

		if (is_null($this->zipFile)) {
			$this->zipData .= $_obf_JGA652Xh;
		}
		else {
			fwrite($this->zipFile, $_obf_JGA652Xh);
		}
	}

	public function addLargeFile($dataFile, $filePath, $timestamp = 0, $fileComment = NULL)
	{
		if ($this->isFinalized) {
			return NULL;
		}

		$this->openStream($filePath, $timestamp, $fileComment);
		$_obf_9E4_ = fopen($dataFile, 'rb');

		while (!feof($_obf_9E4_)) {
			$this->addStreamData(fread($_obf_9E4_, $this->streamChunkSize));
		}

		fclose($_obf_9E4_);
		$this->closeStream();
	}

	public function openStream($filePath, $timestamp = 0, $fileComment = NULL)
	{
		if ($this->isFinalized) {
			return NULL;
		}

		if (is_null($this->zipFile)) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = NULL;
		}

		if (0 < strlen($this->streamFilePath)) {
			closestream();
		}

		$this->streamFile = tempnam(sys_get_temp_dir(), 'Zip');
		$this->streamData = gzopen($this->streamFile, 'w9');
		$this->streamFilePath = $filePath;
		$this->streamTimestamp = $timestamp;
		$this->streamFileComment = $fileComment;
		$this->streamFileLength = 0;
	}

	public function addStreamData($data)
	{
		$_obf_Q8ERGxGW = gzwrite($this->streamData, $data, strlen($data));

		if ($_obf_Q8ERGxGW != strlen($data)) {
			print('<p>Length mismatch</p>' . "\n" . '');
		}

		$this->streamFileLength += $_obf_Q8ERGxGW;
		return $_obf_Q8ERGxGW;
	}

	public function closeStream()
	{
		if ($this->isFinalized || (strlen($this->streamFilePath) == 0)) {
			return NULL;
		}

		fflush($this->streamData);
		gzclose($this->streamData);
		$_obf_Ua_zf_EO = '' . "\0" . '';
		$_obf_qzz6_S6aXQ__ = '' . "\0" . '';
		$_obf_ZksWNztJA9YdDN8_ = fopen($this->streamFile, 'rb');
		$_obf_NxFgcUQ_ = fstat($_obf_ZksWNztJA9YdDN8_);
		$_obf_Sz7X = $_obf_NxFgcUQ_['size'];
		fseek($_obf_ZksWNztJA9YdDN8_, $_obf_Sz7X - 8);
		$_obf_jVT6HLPAjA0P = fread($_obf_ZksWNztJA9YdDN8_, 4);
		$_obf_5PiCQCp6uWjFXw__ = $this->streamFileLength;
		$_obf_yaDyhawNGCc_ = $_obf_Sz7X - 10;
		$_obf_Sz7X -= 9;
		fseek($_obf_ZksWNztJA9YdDN8_, 10);
		$this->buildZipEntry($this->streamFilePath, $this->streamFileComment, $_obf_qzz6_S6aXQ__, $_obf_Ua_zf_EO, $this->streamTimestamp, $_obf_jVT6HLPAjA0P, $_obf_yaDyhawNGCc_, $_obf_5PiCQCp6uWjFXw__, 32);

		while (!feof($_obf_ZksWNztJA9YdDN8_)) {
			fwrite($this->zipFile, fread($_obf_ZksWNztJA9YdDN8_, $this->streamChunkSize));
		}

		unlink($this->streamFile);
		$this->streamFile = NULL;
		$this->streamData = NULL;
		$this->streamFilePath = NULL;
		$this->streamTimestamp = NULL;
		$this->streamFileComment = NULL;
		$this->streamFileLength = 0;
	}

	public function finalize()
	{
		if (!$this->isFinalized) {
			if (0 < strlen($this->streamFilePath)) {
				$this->closeStream();
			}

			$_obf_CwE_ = implode('', $this->cdRec);
			$_obf_kI5Cp_s_ = $_obf_CwE_ . $this->endOfCentralDirectory . pack('v', sizeof($this->cdRec)) . pack('v', sizeof($this->cdRec)) . pack('V', strlen($_obf_CwE_)) . pack('V', $this->offset);

			if (!is_null($this->zipComment)) {
				$_obf_kI5Cp_s_ .= pack('v', strlen($this->zipComment)) . $this->zipComment;
			}
			else {
				$_obf_kI5Cp_s_ .= '' . "\0" . '' . "\0" . '';
			}

			if (is_null($this->zipFile)) {
				$this->zipData .= $_obf_kI5Cp_s_;
			}
			else {
				fwrite($this->zipFile, $_obf_kI5Cp_s_);
				fflush($this->zipFile);
			}

			$this->isFinalized = true;
			$_obf_CwE_ = NULL;
			$this->cdRec = NULL;
		}
	}

	public function getZipFile()
	{
		if (!$this->isFinalized) {
			$this->finalize();
		}

		if (is_null($this->zipFile)) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = NULL;
		}

		rewind($this->zipFile);
		return $this->zipFile;
	}

	public function getZipData()
	{
		if (!$this->isFinalized) {
			$this->finalize();
		}

		if (is_null($this->zipFile)) {
			return $this->zipData;
		}
		else {
			rewind($this->zipFile);
			$_obf_FqTGztdco0M_ = fstat($this->zipFile);
			return fread($this->zipFile, $_obf_FqTGztdco0M_['size']);
		}
	}

	public function sendZip($fileName)
	{
		if (!$this->isFinalized) {
			$this->finalize();
		}

		if (!headers_sent($_obf_aVGas3aqlOFv_A__, $_obf_AgzG2ST4x_b4Qw__) || exit('<p><b>Error:</b> Unable to send file ' . $fileName . '. HTML Headers have already been sent from <b>' . $_obf_aVGas3aqlOFv_A__ . '</b> in line <b>' . $_obf_AgzG2ST4x_b4Qw__ . '</b></p>')) {
			if ((ob_get_contents() === false) || exit('' . "\n" . '<p><b>Error:</b> Unable to send file <b>' . $fileName . '.epub</b>. Output buffer contains the following text (typically warnings or errors):<br>' . ob_get_contents() . '</p>')) {
				if (ini_get('zlib.output_compression')) {
					ini_set('zlib.output_compression', 'Off');
				}

				header('Pragma: public');
				header('Last-Modified: ' . date($this->headerDateFormat, $this->date));
				header('Expires: 0');
				header('Accept-Ranges: bytes');
				header('Connection: close');
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="' . $fileName . '";');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . $this->getArchiveSize());

				if (is_null($this->zipFile)) {
					echo $this->zipData;
				}
				else {
					rewind($this->zipFile);

					while (!feof($this->zipFile)) {
						echo fread($this->zipFile, $this->streamChunkSize);
					}
				}
			}
		}
	}

	public function getArchiveSize()
	{
		if (is_null($this->zipFile)) {
			return strlen($this->zipData);
		}

		$_obf_FqTGztdco0M_ = fstat($this->zipFile);
		return $_obf_FqTGztdco0M_['size'];
	}

	public function getDosTime($timestamp = 0)
	{
		$timestamp = (int) $timestamp;
		$_obf_O6ZGVA__ = ($timestamp == 0 ? getdate() : getdate($timestamp));

		if (1980 <= $_obf_O6ZGVA__['year']) {
			return pack('V', (($_obf_O6ZGVA__['mday'] + ($_obf_O6ZGVA__['mon'] << 5) + (($_obf_O6ZGVA__['year'] - 1980) << 9)) << 16) | (($_obf_O6ZGVA__['seconds'] >> 1) + ($_obf_O6ZGVA__['minutes'] << 5) + ($_obf_O6ZGVA__['hours'] << 11)));
		}

		return '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '';
	}

	public function buildZipEntry($filePath, $fileComment, $gpFlags, $gzType, $timestamp, $fileCRC32, $gzLength, $dataLength, $extFileAttr)
	{
		$filePath = str_replace('\\', '/', $filePath);
		$_obf_XYCP3kcCMDxRL9YeETxzu1M_ = (is_null($fileComment) ? 0 : strlen($fileComment));
		$_obf_wlTlPtKsDg__ = $this->getDosTime($timestamp);
		$_obf_0_2YFt_VqiQ_ = $this->localFileHeader;
		$_obf_0_2YFt_VqiQ_ .= '' . "\0" . '';
		$_obf_0_2YFt_VqiQ_ .= $gpFlags . $gzType . $_obf_wlTlPtKsDg__ . $fileCRC32;
		$_obf_0_2YFt_VqiQ_ .= pack('VV', $gzLength, $dataLength);
		$_obf_0_2YFt_VqiQ_ .= pack('v', strlen($filePath));
		$_obf_0_2YFt_VqiQ_ .= '' . "\0" . '' . "\0" . '';
		$_obf_0_2YFt_VqiQ_ .= $filePath;

		if (is_null($this->zipFile)) {
			$this->zipData .= $_obf_0_2YFt_VqiQ_;
		}
		else {
			fwrite($this->zipFile, $_obf_0_2YFt_VqiQ_);
		}

		$_obf_oC_ROgjHzA__ = $this->centralFileHeader;
		$_obf_oC_ROgjHzA__ .= '' . "\0" . '' . "\0" . '';
		$_obf_oC_ROgjHzA__ .= '' . "\0" . '';
		$_obf_oC_ROgjHzA__ .= $gpFlags . $gzType . $_obf_wlTlPtKsDg__ . $fileCRC32;
		$_obf_oC_ROgjHzA__ .= pack('VV', $gzLength, $dataLength);
		$_obf_oC_ROgjHzA__ .= pack('v', strlen($filePath));
		$_obf_oC_ROgjHzA__ .= '' . "\0" . '' . "\0" . '';
		$_obf_oC_ROgjHzA__ .= pack('v', $_obf_XYCP3kcCMDxRL9YeETxzu1M_);
		$_obf_oC_ROgjHzA__ .= '' . "\0" . '' . "\0" . '';
		$_obf_oC_ROgjHzA__ .= '' . "\0" . '' . "\0" . '';
		$_obf_oC_ROgjHzA__ .= pack('V', $extFileAttr);
		$_obf_oC_ROgjHzA__ .= pack('V', $this->offset);
		$_obf_oC_ROgjHzA__ .= $filePath;

		if (!is_null($fileComment)) {
			$_obf_oC_ROgjHzA__ .= $fileComment;
		}

		$this->cdRec[] = $_obf_oC_ROgjHzA__;
		$this->offset += strlen($_obf_0_2YFt_VqiQ_) + $gzLength;
	}
}


?>
