<?php
//dezend by http://www.yunlu99.com/
class PHPUnzip
{
	/**
   * Indicates if a zip file was successfully opened for reading
   *
   * @var boolean
   */
	public $open;
	/**
   * Array of {@link ZipFileEntry} objects representing the files and directories in the archive
   *
   * @var array
   */
	public $files;
	/**
   * The ZIP file comment text, if any
   *
   * @var string
   */
	public $comment;
	/**
   * The error encountered while parsing the file
   *
   * @var int
   */
	public $error;
	/**
   * The error message associated with the error
   *
   * @var unknown_type
   */
	public $error_str;
	/**
   * The ZIP file to open
   *
   * @access private
   * @var string
   */
	public $zip_file;
	/**
   * File pointer to the current location in the zip file
   *
   * @access private
   * @var resource
   */
	public $fp;
	/**
   * The current state of file processing
   *
   * @access private
   * @var int
   */
	public $state;
	/**
   * If in a break state and should break from the current operation. Means an error was encountered
   *
   * @access private
   * @var boolean
   */
	public $break;
	/**
   * Minimum version required to extract
   *
   * @access private
   * @var unknown_type
   */
	public $min_version;
	/**
   * Output contents to file or leave in file object
   *
   * @access private
   * @var boolean
   */
	public $read_to_file;
	/**
   * Overwrite existing files
   *
   * @var boolean
   */
	public $overwrite_existing_files;
	/**
   * Where to output the files
   *
   * @var string
   */
	public $output_file_path;
	/**
   * PKZip encryption keys
   *
   * @access private
   * @var unknown_type
   */
	public $key;
	/**
   * CRC polynomials
   *
   * @access private
   * @var array
   */
	public $crc_table;

	public function PHPUnzip()
	{
		$this->open = false;
		$this->files = array();
		$this->comment = '';
		$this->break = false;
		$this->error = E_NO_ERROR;
		$this->error_str = '';
		$this->key = array();
		$this->read_to_file = false;
		$this->overwrite_existing_files = false;
		$this->output_file_path = './';
	}

	public function SetOption($long_option, $value)
	{
		switch ($long_option) {
		case ZIPOPT_READSIZE:
			$this->read_chunk_size = $value;
			break;

		case ZIPOPT_FILE_OUTPUT:
			$this->read_to_file = $value == true;
			break;

		case ZIPOPT_OVERWRITE_EXISTING:
			$this->overwrite_existing_files = $value == true;
			break;

		case ZIPOPT_OUTPUT_PATH:
			$this->output_file_path = $value;
			break;

		default:
			return false;
		}

		return true;
	}

	public function Open($file)
	{
		$this->fp = @fopen($file, 'rb');

		if (!$this->fp) {
			trigger_error('Failed to open file "' . $file . '"', 512);
			$this->open = false;
			$this->error = E_NOOPEN;
			$this->error_str = 'Failed to open file';
			return false;
		}

		$this->open = true;
		return true;
	}

	public function Read()
	{
		if (!$this->open) {
			$this->error = E_NO_FILE;
			$this->error = 'No ZIP file has been opened';
			return false;
		}

		$this->state = S_FILE_HEADER;
		$_obf_8w__ = &$this->fp;
		$_obf_6hS1Rw__ = NULL;
		$_obf_HpgVSAO5XKIw5g__ = true;

		while (!feof($_obf_8w__) && ($this->state != S_EOF)) {
			switch ($this->state) {
			case S_FILE_HEADER:
				$_obf_YfrY8VEd = fread($_obf_8w__, 4);

				if (version_compare(phpversion(), '5.3.0', '<')) {
					$this->check_end($_obf_8w__, 4);

					if ($this->break == true) {
						break;
					}
				}

				if (strcmp($_obf_YfrY8VEd, 'PK') == 0) {
					$this->state = S_FILE_HEADER_DATA;
				}
				else if (strcmp($_obf_YfrY8VEd, 'PK') == 0) {
					$this->state = S_CENTRAL_DIRECTORY;
				}
				else if (strcmp($_obf_YfrY8VEd, 'PK') == 0) {
					$this->state = S_END_CENTRAL;
				}
				else {
					if ($_obf_HpgVSAO5XKIw5g__ == true) {
						$this->error = E_NOTZIP;
						$this->error_str = 'File does not appear to be a zipfile';
					}
					else {
						$this->error = E_DATA_ERROR;
						$this->error_str = 'Unexpected data encountered while reading file';
					}

					$this->state = S_EOF;
				}

				$_obf_HpgVSAO5XKIw5g__ = false;
				$_obf_6hS1Rw__ = new ZipFileEntry();
				break;

			case S_FILE_HEADER_DATA:
				$_obf_6RYLWQ__ = fread($_obf_8w__, 26);
				$this->check_end($_obf_6RYLWQ__, 26);

				if ($this->break == true) {
					break;
				}

				$_obf_tjILu7ZH = unpack('vVER/vGPF/vCM/vMTIME/vMDATE/VCRC/VCSIZE/VUSIZE/vFNLEN/vEFLEN', $_obf_6RYLWQ__);
				$_obf_6hS1Rw__->crc = sprintf('%u', $_obf_tjILu7ZH['CRC']);
				$_obf_6hS1Rw__->time = mktime(($_obf_tjILu7ZH['MTIME'] >> 11) & 15, ($_obf_tjILu7ZH['MTIME'] >> 5) & 31, $_obf_tjILu7ZH['MTIME'] & 15, ($_obf_tjILu7ZH['MDATE'] >> 5) & 15, $_obf_tjILu7ZH['MDATE'] & 31, (($_obf_tjILu7ZH['MDATE'] >> 9) & 127) + 1980);
				$_obf_6hS1Rw__->size = $_obf_tjILu7ZH['USIZE'];
				$_obf_6hS1Rw__->compressed_size = $_obf_tjILu7ZH['CSIZE'];
				$this->min_version = $_obf_tjILu7ZH['VER'];

				if ($_obf_tjILu7ZH['USIZE'] == 0) {
					$_obf_6hS1Rw__->compression_ratio = 0;
				}
				else {
					$_obf_6hS1Rw__->compression_ratio = number_format(($_obf_tjILu7ZH['CSIZE'] / $_obf_tjILu7ZH['USIZE']) * 100, 2);
				}

				$this->state = S_FILE_FILENAME;
				break;

			case S_FILE_FILENAME:
				$_obf_xbQRFgA_ = fread($_obf_8w__, $_obf_tjILu7ZH['FNLEN']);
				$this->check_end($_obf_xbQRFgA_, $_obf_tjILu7ZH['FNLEN']);

				if ($this->break == true) {
					break;
				}

				$_obf_6hS1Rw__->name = basename($_obf_xbQRFgA_);
				$_obf_6hS1Rw__->path = (dirname($_obf_xbQRFgA_) == '.') || (dirname($_obf_xbQRFgA_) == '') ? '' : dirname($_obf_xbQRFgA_);
				$this->state = S_FILE_EXTRA;
				break;

			case S_FILE_EXTRA:
				if (0 < $_obf_tjILu7ZH['EFLEN']) {
					$_obf_j_flwKw_ = fread($_obf_8w__, $_obf_tjILu7ZH['EFLEN']);
					$this->check_end($_obf_j_flwKw_, $_obf_tjILu7ZH['EFLEN']);

					if ($this->break == true) {
						break;
					}
				}
				else {
					$_obf_j_flwKw_ = '';
				}

				$this->state = S_FILE_DATA;
				break;

			case S_FILE_DATA:
				if (0 < $_obf_tjILu7ZH['CSIZE']) {
					$_obf_6hS1Rw__->data = fread($_obf_8w__, $_obf_tjILu7ZH['CSIZE']);
					$this->check_end($_obf_6hS1Rw__->data, $_obf_tjILu7ZH['CSIZE']);

					if ($_obf_tjILu7ZH['GPF'] & 1) {
						$_obf_6hS1Rw__->error = E_FILE_ENCRYPTED;
						$_obf_juwe = substr($_obf_6hS1Rw__->data, 0, 12);
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < 12; ++$_obf_7w__) {
							echo dechex(ord($_obf_juwe[$_obf_7w__])) . ' ';
						}

						echo 'encrypted buffer' . "\n" . '';
						$this->InitPassword('password');
						$this->PKEncDecryptHeader(substr($_obf_6hS1Rw__->data, 0, 12));
						echo dechex($_obf_6hS1Rw__->crc + 0) . ' file crc' . "\n" . '';
						echo "\n";
						$_obf_6hS1Rw__->data = substr($_obf_6hS1Rw__->data, 12);
						$_obf_7w__ = 0;

						for (; $_obf_7w__ < strlen($_obf_6hS1Rw__->data); ++$_obf_7w__) {
							$_obf_KQ__ = $_obf_6hS1Rw__->data[$_obf_7w__];
							$_obf_juwe = ord($_obf_KQ__) ^ $this->PKEncDecryptByte();
							$this->PKEncUpdateKeys($_obf_juwe);
							$_obf_6hS1Rw__->data[$_obf_7w__] = chr($_obf_juwe);
						}

						echo $_obf_6hS1Rw__->data;
						exit();
					}
					else if ($_obf_tjILu7ZH['CM'] == 8) {
						if (extension_loaded('zlib')) {
							$_obf_6hS1Rw__->data = @gzinflate($_obf_6hS1Rw__->data);

							if ($_obf_6hS1Rw__->data === false) {
								$_obf_6hS1Rw__->data = NULL;
								$_obf_6hS1Rw__->error = E_INFLATE_ERROR;
							}
						}
						else {
							$_obf_6hS1Rw__->error = E_METHOD_NOT_SUPPORTED;
						}
					}
					else if ($_obf_tjILu7ZH['CM'] == 12) {
						if (extension_loaded('bz2')) {
							$_obf_6hS1Rw__->data = @bzdecompress($_obf_6hS1Rw__->data);

							if (!is_string($_obf_6hS1Rw__->data)) {
								$_obf_6hS1Rw__->data == NULL;
								$_obf_6hS1Rw__->error = E_BZIP_ERROR;
							}
						}
						else {
							$_obf_6hS1Rw__->error = E_METHOD_NOT_SUPPORTED;
						}
					}

					if ($_obf_6hS1Rw__->error == E_NO_ERROR) {
						if (sprintf('%u', crc32($_obf_6hS1Rw__->data)) != sprintf('%u', $_obf_tjILu7ZH['CRC'])) {
							$_obf_6hS1Rw__->error = E_CRC_MISMATCH;
						}
					}

					if ($this->read_to_file == true) {
						$this->WriteDataToFile($_obf_6hS1Rw__->data, $_obf_6hS1Rw__->name, $_obf_6hS1Rw__->path, $_obf_6hS1Rw__->time);
						$_obf_6hS1Rw__->data = NULL;
					}
				}
				else {
					$_obf_6hS1Rw__->data = '';
				}

				if ($this->break == false) {
					$this->state = S_DATA_DESCRIPTOR;
				}

				break;

			case S_DATA_DESCRIPTOR:
				if ($_obf_tjILu7ZH['GPF'] & (0 < 3)) {
					$_obf__HthXKEwkFxB = fread($_obf_8w__, 4);
					$this->check_end($_obf__HthXKEwkFxB, 4);

					if ($this->break == true) {
						break;
					}

					if (strcmp($_obf__HthXKEwkFxB, 'PK') == 0) {
						$_obf__HthXKEwkFxB = fread($_obf_8w__, 12);
						$this->check_end($_obf__HthXKEwkFxB, 12);

						if ($this->break == true) {
							break;
						}
					}
					else {
						$_obf__HthXKEwkFxB .= fread($_obf_8w__, 8);
						$this->check_end($_obf__HthXKEwkFxB, 8);

						if ($this->break == true) {
							break;
						}
					}

					$_obf_PjlR9DOBrI9SyMw_ = unpack('VCRC/VCSIZE/VUSIZE', $_obf__HthXKEwkFxB);
				}
				else {
					$_obf__HthXKEwkFxB = '';
				}

				$this->state = S_FILE_PROCESSED;
				break;

			case S_FILE_PROCESSED:
				$this->files[] = $_obf_6hS1Rw__;
				$this->state = S_FILE_HEADER;
				break;

			case S_CENTRAL_DIRECTORY:
				$_obf_DiL9XqvbpogW = fread($_obf_8w__, 42);
				$this->check_end($_obf_DiL9XqvbpogW, 42);

				if ($this->break == true) {
					break;
				}

				$_obf_DiL9XqvbpogW = unpack('vVER/vVEXT/vGPF/vCM/vMTIME/vMDATE/VCRC/VCSIZE/VUSIZE/vFNLEN/vEFLEN/vFCLEN/vDSTART/vFATTR/vEATTR/VOFFSET', $_obf_DiL9XqvbpogW);

				if (0 < $_obf_DiL9XqvbpogW['FNLEN']) {
					$_obf_juwe = fread($_obf_8w__, $_obf_DiL9XqvbpogW['FNLEN']);
					$this->check_end($_obf_juwe, $_obf_DiL9XqvbpogW['FNLEN']);

					if ($this->break == true) {
						break;
					}
				}

				if (0 < $_obf_DiL9XqvbpogW['EFLEN']) {
					$_obf_juwe = fread($_obf_8w__, $_obf_DiL9XqvbpogW['EFLEN']);
					$this->check_end($_obf_juwe, $_obf_DiL9XqvbpogW['EFLEN']);

					if ($this->break == true) {
						break;
					}
				}

				if (0 < $_obf_DiL9XqvbpogW['FCLEN']) {
					$_obf_juwe = fread($_obf_8w__, $_obf_DiL9XqvbpogW['FCLEN']);
					$this->check_end($_obf_juwe, $_obf_DiL9XqvbpogW['FCLEN']);

					if ($this->break == true) {
						break;
					}
				}

				$this->state = S_FILE_HEADER;
				break;

			case S_END_CENTRAL:
				$_obf_28p9HhmeM4eEog__ = fread($_obf_8w__, 18);
				$this->check_end($_obf_28p9HhmeM4eEog__, 18);

				if ($this->break == true) {
					break;
				}

				$_obf_28p9HhmeM4eEog__ = unpack('vDNUM/vDNUMSCD/vNUMENTRIES/vTNUMENTRIES/VCDSIZE/VOFFSET/vCLEN', $_obf_28p9HhmeM4eEog__);

				if (0 < $_obf_28p9HhmeM4eEog__['CLEN']) {
					$this->comment = fread($_obf_8w__, $_obf_28p9HhmeM4eEog__['CLEN']);
				}

				$this->state = S_EOF;
				break;
			}
		}

		fclose($this->fp);

		if ($this->error != E_NO_ERROR) {
			return false;
		}
		else {
			return true;
		}
	}

	public function check_end($str, $length)
	{
		if (strlen($str) < $length) {
			$this->error = E_UNEXPECTED_END;
			$this->error_str = 'Unexpected end of file';
			$this->state = S_EOF;
			$this->break = true;
		}
	}

	public function WriteDataToFile($data, $name, $path, $time = NULL)
	{
		if ((substr($this->output_file_path, -1) != '/') && ($this->output_file_path != '')) {
			$this->output_file_path .= '/';
		}

		if (!is_writeable($this->output_file_path)) {
			trigger_error('Unable to write to output file path "' . $this->output_file_path . '"', 512);
			return false;
		}
		else {
			$_obf_H7rxX_x2khqDHRMDbO6svg__ = '';

			if ($path != '') {
				clearstatcache();
				$_obf_2XdBWHISnvr618g_ = explode('/', $path);

				foreach ($_obf_2XdBWHISnvr618g_ as $_obf_O_ZQ3a7OgZq2) {
					$_obf_H7rxX_x2khqDHRMDbO6svg__ .= $_obf_O_ZQ3a7OgZq2;

					if (!file_exists($this->output_file_path . $_obf_H7rxX_x2khqDHRMDbO6svg__)) {
						if (!@mkdir($this->output_file_path . $_obf_H7rxX_x2khqDHRMDbO6svg__, 509)) {
							trigger_error('Unable to create directory "' . $this->output_file_path . $_obf_H7rxX_x2khqDHRMDbO6svg__ . '"', 512);
							return false;
						}
					}

					$_obf_H7rxX_x2khqDHRMDbO6svg__ .= '/';
				}
			}

			$_obf_JTe7jJ4eGW8_ = $this->output_file_path . $_obf_H7rxX_x2khqDHRMDbO6svg__ . $name;
			if (file_exists($_obf_JTe7jJ4eGW8_) && ($this->overwrite_existing_files == false)) {
				trigger_error('File "' . $_obf_JTe7jJ4eGW8_ . '" already exists', 512);
				return false;
			}

			$_obf_YBY_ = @fopen($_obf_JTe7jJ4eGW8_, 'w+b');

			if (!$_obf_YBY_) {
				trigger_error('Failed to open "' . $this->output_file_path . $_obf_H7rxX_x2khqDHRMDbO6svg__ . '" for writing', 512);
				return false;
			}

			fwrite($_obf_YBY_, $data);
			fclose($_obf_YBY_);

			if ($time != NULL) {
				touch($_obf_JTe7jJ4eGW8_, $time);
			}

			return true;
		}
	}
}

class ZipFileEntry
{
	/**
   * The stored CRC32 from the archive in hex format
   *
   * @var string
   */
	public $crc;
	/**
   * The uncompressed size of the file
   *
   * @var int
   */
	public $size;
	/**
   * The compressed size of the file
   *
   * @var int
   */
	public $compressed_size;
	/**
   * The compression ratio for compressed files
   *
   * @var float
   */
	public $compression_ratio;
	/**
   * Any error encountered while processing the file
   *
   * @var int
   */
	public $error;
	/**
   * The decompressed file data or null if outputting to files
   *
   * @var string
   */
	public $data;
	/**
   * The file name
   *
   * @var string
   */
	public $name;
	/**
   * The path of the file
   *
   * @var string
   */
	public $path;
	/**
   * The file modification timestamp
   *
   * @var int
   */
	public $time;

	public function ZipFileEntry()
	{
		$this->crc = '';
		$this->size = 0;
		$this->compressed_size = 0;
		$this->compression_ratio = 0;
		$this->error = E_NO_ERROR;
		$this->data = '';
		$this->name = '';
		$this->path = '';
		$this->time = 0;
	}
}

define('E_NO_ERROR', -1);
define('E_NOOPEN', 0);
define('E_NOTZIP', 1);
define('E_UNEXPECTED_END', 2);
define('E_EMPTY', 3);
define('E_DATA_ERROR', 4);
define('E_FILE_ENCRYPTED', 5);
define('E_CRC_MISMATCH', 6);
define('E_METHOD_NOT_SUPPORTED', 7);
define('E_INFLATE_ERROR', 8);
define('E_BZIP_ERROR', 9);
define('E_NO_FILE', 10);
define('ZIPOPT_FILE_OUTPUT', 1);
define('ZIPOPT_OVERWRITE_EXISTING', 2);
define('ZIPOPT_OUTPUT_PATH', 3);
define('S_FILE_HEADER', 1);
define('S_FILE_HEADER_DATA', 2);
define('S_FILE_FILENAME', 3);
define('S_FILE_EXTRA', 4);
define('S_FILE_DATA', 5);
define('S_DATA_DESCRIPTOR', 6);
define('S_FILE_PROCESSED', 7);
define('S_CENTRAL_DIRECTORY', 8);
define('S_CD_FILENAME', 9);
define('S_CD_EXTRA', 10);
define('S_CD_FCOMMENT', 11);
define('S_END_CENTRAL', 12);
define('S_EOF', 13);
define('S_ERROR', 14);

?>
