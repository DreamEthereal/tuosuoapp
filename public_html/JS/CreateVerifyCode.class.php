<?php
//dezend by http://www.yunlu99.com/
class Securimage
{
	/**
   * The desired width of the CAPTCHA image.
   *
   * @var int
   */
	public $image_width = 175;
	/**
   * The desired width of the CAPTCHA image.
   *
   * @var int
   */
	public $image_height = 45;
	/**
   * The image format for output.<br />
   * Valid options: SI_IMAGE_PNG, SI_IMAGE_JPG, SI_IMAGE_GIF
   *
   * @var int
   */
	public $image_type = SI_IMAGE_PNG;
	/**
   * The length of the code to generate.
   *
   * @var int
   */
	public $code_length = 4;
	/**
   * The character set for individual characters in the image.<br />
   * Letters are converted to uppercase.<br />
   * The font must support the letters or there may be problematic substitutions.
   *
   * @var string
   */
	public $charset = 'ABCDEFGHKLMNPRSTUVWYZ3456789';
	/**
   * Create codes using this word list
   *
   * @var string  The path to the word list to use for creating CAPTCHA codes
   */
	public $wordlist_file = '';
	/**
   * True to use a word list file instead of a random code
   *
   * @var bool
   */
	public $use_wordlist = true;
	/**
   * Whether to use a GD font instead of a TTF font.<br />
   * TTF offers more support and options, but use this if your PHP doesn't support TTF.<br />
   *
   * @var boolean
   */
	public $use_gd_font = false;
	/**
   * The GD font to use.<br />
   * Internal gd fonts can be loaded by their number.<br />
   * Alternatively, a file path can be given and the font will be loaded from file.
   *
   * @var mixed
   */
	public $gd_font_file = '';
	/**
   * The approximate size of the font in pixels.<br />
   * This does not control the size of the font because that is determined by the GD font itself.<br />
   * This is used to aid the calculations of positioning used by this class.<br />
   *
   * @var int
   */
	public $gd_font_size = 20;
	/**
   * The path to the TTF font file to load.
   *
   * @var string
   */
	public $ttf_file = '';
	/**
   * The font size.<br />
   * Depending on your version of GD, this should be specified as the pixel size (GD1) or point size (GD2)<br />
   *
   * @var int
   */
	public $font_size = 20;
	/**
   * The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
   * Higher values represent a counter-clockwise rotation.<br />
   * For example, a value of 90 would result in bottom-to-top reading text.
   *
   * @var int
   */
	public $text_angle_minimum = -20;
	/**
   * The minimum angle in degrees, with 0 degrees being left-to-right reading text.<br />
   * Higher values represent a counter-clockwise rotation.<br />
   * For example, a value of 90 would result in bottom-to-top reading text.
   *
   * @var int
   */
	public $text_angle_maximum = 20;
	/**
   * The X-Position on the image where letter drawing will begin.<br />
   * This value is in pixels from the left side of the image.
   *
   * @var int
   */
	public $text_x_start = 8;
	/**
   * Letters can be spaced apart at random distances.<br />
   * This is the minimum distance between two letters.<br />
   * This should be <i>at least</i> as wide as a font character.<br />
   * Small values can cause letters to be drawn over eachother.<br />
   *
   * @var int
   */
	public $text_minimum_distance = 30;
	/**
   * Letters can be spaced apart at random distances.<br />
   * This is the maximum distance between two letters.<br />
   * This should be <i>at least</i> as wide as a font character.<br />
   * Small values can cause letters to be drawn over eachother.<br />
   *
   * @var int
   */
	public $text_maximum_distance = 33;
	/**
   * The background color for the image.<br />
   * This should be specified in HTML hex format.<br />
   * Make sure to include the preceding # sign!
   *
   * @var string
   */
	public $image_bg_color = '#f5f5f5';
	/**
   * The text color to use for drawing characters.<br />
   * This value is ignored if $use_multi_text is set to true.<br />
   * Make sure this contrasts well with the background color.<br />
   * Specify the color in HTML hex format with preceding # sign
   *
   * @see Securimage::$use_multi_text
   * @var string
   */
	public $text_color = '#ff0000';
	/**
   * Set to true to use multiple colors for each character.
   *
   * @see Securimage::$multi_text_color
   * @var boolean
   */
	public $use_multi_text = true;
	/**
   * String of HTML hex colors to use.<br />
   * Separate each possible color with commas.<br />
   * Be sure to precede each value with the # sign.
   *
   * @var string
   */
	public $multi_text_color = '#cc0000,#339933,#0a68dd,#f65c47,#8d32fd';
	/**
   * Set to true to make the characters appear transparent.
   *
   * @see Securimage::$text_transparency_percentage
   * @var boolean
   */
	public $use_transparent_text = true;
	/**
   * The percentage of transparency, 0 to 100.<br />
   * A value of 0 is completely opaque, 100 is completely transparent (invisble)
   *
   * @see Securimage::$use_transparent_text
   * @var int
   */
	public $text_transparency_percentage = 15;
	/**
   * Draw vertical and horizontal lines on the image.
   *
   * @see Securimage::$line_color
   * @see Securimage::$line_distance
   * @see Securimage::$line_thickness
   * @see Securimage::$draw_lines_over_text
   * @var boolean
   */
	public $draw_lines = true;
	/**
   * The color of the lines drawn on the image.<br />
   * Use HTML hex format with preceding # sign.
   *
   * @see Securimage::$draw_lines
   * @var string
   */
	public $line_color = '#b5cfd9';
	/**
   * How far apart to space the lines from eachother in pixels.
   *
   * @see Securimage::$draw_lines
   * @var int
   */
	public $line_distance = 5;
	/**
   * How thick to draw the lines in pixels.<br />
   * 1-3 is ideal depending on distance
   *
   * @see Securimage::$draw_lines
   * @see Securimage::$line_distance
   * @var int
   */
	public $line_thickness = 1;
	/**
   * Set to true to draw angled lines on the image in addition to the horizontal and vertical lines.
   *
   * @see Securimage::$draw_lines
   * @var boolean
   */
	public $draw_angled_lines = false;
	/**
   * Draw the lines over the text.<br />
   * If fales lines will be drawn before putting the text on the image.<br />
   * This can make the image hard for humans to read depending on the line thickness and distance.
   *
   * @var boolean
   */
	public $draw_lines_over_text = false;
	/**
   * For added security, it is a good idea to draw arced lines over the letters to make it harder for bots to segment the letters.<br />
   * Two arced lines will be drawn over the text on each side of the image.<br />
   * This is currently expirimental and may be off in certain configurations.
   *
   * @var boolean
   */
	public $arc_linethrough = true;
	/**
   * The colors or color of the arced lines.<br />
   * Use HTML hex notation with preceding # sign, and separate each value with a comma.<br />
   * This should be similar to your font color for single color images.
   *
   * @var string
   */
	public $arc_line_colors = '#8080ff';
	/**
   * Full path to the WAV files to use to make the audio files, include trailing /.<br />
   * Name Files  [A-Z0-9].wav
   *
   * @since 1.0.1
   * @var string
   */
	public $audio_path = '';
	/**
   * The gd image resource.
   *
   * @access private
   * @var resource
   */
	public $im;
	/**
   * The background image resource
   *
   * @access private
   * @var resource
   */
	public $bgimg;
	/**
   * The code generated by the script
   *
   * @access private
   * @var string
   */
	public $code;
	/**
   * The code that was entered by the user
   *
   * @access private
   * @var string
   */
	public $code_entered;
	/**
   * Whether or not the correct code was entered
   *
   * @access private
   * @var boolean
   */
	public $correct_code;

	public function Securimage()
	{
		define('ROOT_PATH', '../');
		include_once ROOT_PATH . 'Config/MMConfig.inc.php';

		if ($Config['is_use_mm']) {
			$session_save_path = 'tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '?persistent=' . $Config['mm_persistent'] . '&weight=' . $Config['mm_weight'] . '&timeout=' . $Config['mm_timeout'] . '&retry_interval=' . $Config['mm_retry_interval'] . ',  ,tcp://' . $Config['mm_host'] . ':' . $Config['mm_port'] . '  ';
			ini_set('session.save_handler', 'memcache');
			ini_set('session.save_path', $session_save_path);
		}

		if (session_id() == '') {
			session_start();
		}
	}

	public function show($background_image = '')
	{
		if (($background_image != '') && is_readable($background_image)) {
			$this->bgimg = $background_image;
		}

		$this->doImage();
	}

	public function check($code)
	{
		$this->code_entered = $code;
		$this->validate();
		return $this->correct_code;
	}

	public function doImage()
	{
		if (($this->use_transparent_text == true) || ($this->bgimg != '')) {
			$this->im = imagecreatetruecolor($this->image_width, $this->image_height);
			$_obf_g0VHtbPDhQ__ = imagecolorallocate($this->im, hexdec(substr($this->image_bg_color, 1, 2)), hexdec(substr($this->image_bg_color, 3, 2)), hexdec(substr($this->image_bg_color, 5, 2)));
			imagefilledrectangle($this->im, 0, 0, imagesx($this->im), imagesy($this->im), $_obf_g0VHtbPDhQ__);
		}
		else {
			$this->im = imagecreate($this->image_width, $this->image_height);
			$_obf_g0VHtbPDhQ__ = imagecolorallocate($this->im, hexdec(substr($this->image_bg_color, 1, 2)), hexdec(substr($this->image_bg_color, 3, 2)), hexdec(substr($this->image_bg_color, 5, 2)));
		}

		if ($this->bgimg != '') {
			$this->setBackground();
		}

		$this->createCode();
		if (!$this->draw_lines_over_text && $this->draw_lines) {
			$this->drawLines();
		}

		$this->drawWord();

		if ($this->arc_linethrough == true) {
			$this->arcLines();
		}

		if ($this->draw_lines_over_text && $this->draw_lines) {
			$this->drawLines();
		}

		$this->output();
	}

	public function setBackground()
	{
		$_obf_hywl = @getimagesize($this->bgimg);

		if ($_obf_hywl == false) {
			return NULL;
		}

		switch ($_obf_hywl[2]) {
		case 1:
			$_obf_7ePw8WU_ = @imagecreatefromgif($this->bgimg);
			break;

		case 2:
			$_obf_7ePw8WU_ = @imagecreatefromjpeg($this->bgimg);
			break;

		case 3:
			$_obf_7ePw8WU_ = @imagecreatefrompng($this->bgimg);
			break;

		case 15:
			$_obf_7ePw8WU_ = @imagecreatefromwbmp($this->bgimg);
			break;

		case 16:
			$_obf_7ePw8WU_ = @imagecreatefromxbm($this->bgimg);
			break;

		default:
			return NULL;
		}

		if (!$_obf_7ePw8WU_) {
			return NULL;
		}

		imagecopy($this->im, $_obf_7ePw8WU_, 0, 0, 0, 0, $this->image_width, $this->image_height);
	}

	public function arcLines()
	{
		$_obf_AeviU5fz = explode(',', $this->arc_line_colors);
		imagesetthickness($this->im, 3);
		$_obf_E7LJsMo_ = $_obf_AeviU5fz[rand(0, sizeof($_obf_AeviU5fz) - 1)];
		$_obf_7oyWhuyKtsRo = imagecolorallocate($this->im, hexdec(substr($_obf_E7LJsMo_, 1, 2)), hexdec(substr($_obf_E7LJsMo_, 3, 2)), hexdec(substr($_obf_E7LJsMo_, 5, 2)));
		$_obf_bi_Mww__ = $this->text_x_start + ($this->font_size * 2) + rand(-5, 5);
		$_obf_ncdC0pM_ = ($this->image_width / 2.66) + rand(3, 10);
		$_obf_3FCLQL2p = ($this->font_size * 2.14) - rand(3, 10);

		if ((rand(0, 100) % 2) == 0) {
			$_obf_mV9HBLY_ = rand(0, 66);
			$_obf_Vt5IIw__ = ($this->image_height / 2) - rand(5, 15);
			$_obf_bi_Mww__ += rand(5, 15);
		}
		else {
			$_obf_mV9HBLY_ = rand(180, 246);
			$_obf_Vt5IIw__ = ($this->image_height / 2) + rand(5, 15);
		}

		$_obf_Gmx_ = $_obf_mV9HBLY_ + rand(75, 110);
		imagearc($this->im, $_obf_bi_Mww__, $_obf_Vt5IIw__, $_obf_ncdC0pM_, $_obf_3FCLQL2p, $_obf_mV9HBLY_, $_obf_Gmx_, $_obf_7oyWhuyKtsRo);
		$_obf_E7LJsMo_ = $_obf_AeviU5fz[rand(0, sizeof($_obf_AeviU5fz) - 1)];
		$_obf_7oyWhuyKtsRo = imagecolorallocate($this->im, hexdec(substr($_obf_E7LJsMo_, 1, 2)), hexdec(substr($_obf_E7LJsMo_, 3, 2)), hexdec(substr($_obf_E7LJsMo_, 5, 2)));

		if ((rand(1, 75) % 2) == 0) {
			$_obf_mV9HBLY_ = rand(45, 111);
			$_obf_Vt5IIw__ = ($this->image_height / 2) - rand(5, 15);
			$_obf_bi_Mww__ += rand(5, 15);
		}
		else {
			$_obf_mV9HBLY_ = rand(200, 250);
			$_obf_Vt5IIw__ = ($this->image_height / 2) + rand(5, 15);
		}

		$_obf_Gmx_ = $_obf_mV9HBLY_ + rand(75, 100);
		imagearc($this->im, $this->image_width * 0.75, $_obf_Vt5IIw__, $_obf_ncdC0pM_, $_obf_3FCLQL2p, $_obf_mV9HBLY_, $_obf_Gmx_, $_obf_7oyWhuyKtsRo);
	}

	public function drawLines()
	{
		$_obf_7oyWhuyKtsRo = imagecolorallocate($this->im, hexdec(substr($this->line_color, 1, 2)), hexdec(substr($this->line_color, 3, 2)), hexdec(substr($this->line_color, 5, 2)));
		imagesetthickness($this->im, $this->line_thickness);
		$_obf_5Q__ = 1;

		for (; $_obf_5Q__ < $this->image_width; $_obf_5Q__ += $this->line_distance) {
			imageline($this->im, $_obf_5Q__, 0, $_obf_5Q__, $this->image_height, $_obf_7oyWhuyKtsRo);
		}

		$_obf_OA__ = 11;

		for (; $_obf_OA__ < $this->image_height; $_obf_OA__ += $this->line_distance) {
			imageline($this->im, 0, $_obf_OA__, $this->image_width, $_obf_OA__, $_obf_7oyWhuyKtsRo);
		}

		if ($this->draw_angled_lines == true) {
			$_obf_5Q__ = 0 - $this->image_height;

			for (; $_obf_5Q__ < $this->image_width; $_obf_5Q__ += $this->line_distance) {
				imageline($this->im, $_obf_5Q__, 0, $_obf_5Q__ + $this->image_height, $this->image_height, $_obf_7oyWhuyKtsRo);
			}

			$_obf_5Q__ = $this->image_width + $this->image_height;

			for (; 0 < $_obf_5Q__; $_obf_5Q__ -= $this->line_distance) {
				imageline($this->im, $_obf_5Q__, 0, $_obf_5Q__ - $this->image_height, $this->image_height, $_obf_7oyWhuyKtsRo);
			}
		}
	}

	public function drawWord()
	{
		putenv('GDFONTPATH=' . realpath('./'));
		$_obf_VXjz6ikgMCM_ = 'VerifyCode.ttf';

		if ($this->use_transparent_text == true) {
			$_obf_TXszy_A_ = intval(($this->text_transparency_percentage / 100) * 127);
			$_obf_549N6EdDYlS_1A__ = imagecolorallocatealpha($this->im, hexdec(substr($this->text_color, 1, 2)), hexdec(substr($this->text_color, 3, 2)), hexdec(substr($this->text_color, 5, 2)), $_obf_TXszy_A_);
		}
		else {
			$_obf_549N6EdDYlS_1A__ = imagecolorallocate($this->im, hexdec(substr($this->text_color, 1, 2)), hexdec(substr($this->text_color, 3, 2)), hexdec(substr($this->text_color, 5, 2)));
		}

		$_obf_5Q__ = $this->text_x_start;
		$_obf_AKfVbr4p = strlen($this->code);
		$_obf_ueWwiR8_ = (($this->image_height / 2) + ($this->font_size / 2)) - 2;
		$_obf_1qRdYUs_ = ($this->image_height / 2) + ($this->font_size / 2) + 2;
		$_obf_AeviU5fz = explode(',', $this->multi_text_color);
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < $_obf_AKfVbr4p; ++$_obf_7w__) {
			$_obf_6P_hm_I_ = rand($this->text_angle_minimum, $this->text_angle_maximum);
			$_obf_OA__ = rand($_obf_ueWwiR8_, $_obf_1qRdYUs_);

			if ($this->use_multi_text == true) {
				$_obf_Fv_U = rand(0, sizeof($_obf_AeviU5fz) - 1);
				$_obf_OQ__ = substr($_obf_AeviU5fz[$_obf_Fv_U], 1, 2);
				$_obf_1Q__ = substr($_obf_AeviU5fz[$_obf_Fv_U], 3, 2);
				$_obf_8A__ = substr($_obf_AeviU5fz[$_obf_Fv_U], 5, 2);

				if ($this->use_transparent_text == true) {
					$_obf_549N6EdDYlS_1A__ = imagecolorallocatealpha($this->im, '0x' . $_obf_OQ__, '0x' . $_obf_1Q__, '0x' . $_obf_8A__, $_obf_TXszy_A_);
				}
				else {
					$_obf_549N6EdDYlS_1A__ = imagecolorallocate($this->im, '0x' . $_obf_OQ__, '0x' . $_obf_1Q__, '0x' . $_obf_8A__);
				}
			}

			@imagettftext($this->im, $this->font_size, $_obf_6P_hm_I_, $_obf_5Q__, $_obf_OA__, $_obf_549N6EdDYlS_1A__, $_obf_VXjz6ikgMCM_, $this->code[$_obf_7w__]);
			$_obf_5Q__ += rand($this->text_minimum_distance, $this->text_maximum_distance);
		}
	}

	public function createCode()
	{
		$this->code = false;
		if ($this->use_wordlist && is_readable($this->wordlist_file)) {
			$this->code = $this->readCodeFromFile();
		}

		if ($this->code == false) {
			$this->code = $this->generateCode($this->code_length);
		}

		$this->saveData();
	}

	public function generateCode($len)
	{
		$_obf_olwD8Q__ = '';
		$_obf_7w__ = 1;
		$_obf_dXDhUAU_ = strlen($this->charset);

		for (; $_obf_7w__ <= $len; ++$_obf_7w__) {
			$_obf_olwD8Q__ .= strtoupper($this->charset[rand(0, $_obf_dXDhUAU_ - 1)]);
		}

		return $_obf_olwD8Q__;
	}

	public function readCodeFromFile()
	{
		$_obf_YBY_ = @fopen($this->wordlist_file, 'rb');

		if (!$_obf_YBY_) {
			return false;
		}

		$_obf_7pmmP5A_ = filesize($this->wordlist_file);

		if ($_obf_7pmmP5A_ < 32) {
			return false;
		}

		if ($_obf_7pmmP5A_ < 128) {
			$_obf_Qp82 = $_obf_7pmmP5A_;
		}
		else {
			$_obf_Qp82 = 128;
		}

		fseek($_obf_YBY_, rand(0, $_obf_7pmmP5A_ - $_obf_Qp82), SEEK_SET);
		$_obf_6RYLWQ__ = fread($_obf_YBY_, 128);
		fclose($_obf_YBY_);
		$_obf_6RYLWQ__ = preg_replace('/' . "\r" . '?' . "\n" . '/', "\n", $_obf_6RYLWQ__);
		$_obf_mV9HBLY_ = strpos($_obf_6RYLWQ__, "\n", rand(0, 100)) + 1;
		$_obf_Gmx_ = strpos($_obf_6RYLWQ__, "\n", $_obf_mV9HBLY_);
		return strtolower(substr($_obf_6RYLWQ__, $_obf_mV9HBLY_, $_obf_Gmx_ - $_obf_mV9HBLY_));
	}

	public function output()
	{
		header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');

		switch ($this->image_type) {
		case SI_IMAGE_JPEG:
			header('Content-Type: image/jpeg');
			imagejpeg($this->im, NULL, 90);
			break;

		case SI_IMAGE_GIF:
			header('Content-Type: image/gif');
			imagegif($this->im);
			break;

		default:
			header('Content-Type: image/png');
			imagepng($this->im);
			break;
		}

		imagedestroy($this->im);
	}

	public function getAudibleCode()
	{
		$_obf_9lTiX_BPSw__ = array();
		$_obf_olwD8Q__ = $this->getCode();

		if ($_obf_olwD8Q__ == '') {
			$this->createCode();
			$_obf_olwD8Q__ = $this->getCode();
		}

		$_obf_7w__ = 0;

		for (; $_obf_7w__ < strlen($_obf_olwD8Q__); ++$_obf_7w__) {
			$_obf_9lTiX_BPSw__[] = $_obf_olwD8Q__[$_obf_7w__];
		}

		return $this->generateWAV($_obf_9lTiX_BPSw__);
	}

	public function saveData()
	{
		$_SESSION['securimage_code_value'] = strtolower($this->code);
	}

	public function validate()
	{
		if (isset($_SESSION['securimage_code_value']) && !empty($_SESSION['securimage_code_value'])) {
			if ($_SESSION['securimage_code_value'] == strtolower(trim($this->code_entered))) {
				$this->correct_code = true;
				$_SESSION['securimage_code_value'] = '';
			}
			else {
				$this->correct_code = false;
			}
		}
		else {
			$this->correct_code = false;
		}
	}

	public function getCode()
	{
		if (isset($_SESSION['securimage_code_value']) && !empty($_SESSION['securimage_code_value'])) {
			return $_SESSION['securimage_code_value'];
		}
		else {
			return '';
		}
	}

	public function checkCode()
	{
		return $this->correct_code;
	}

	public function generateWAV($letters)
	{
		$_obf_7W2nkAM_ = true;
		$_obf__DUZp_U3OjQ_ = 0;
		$_obf_I9APXAk_ = array();
		$_obf_mG5Mya7VPXI_ = '';

		foreach ($letters as $_obf_mdIkZI_4) {
			$_obf_JTe7jJ4eGW8_ = $this->audio_path . strtoupper($_obf_mdIkZI_4) . '.wav';
			$_obf_YBY_ = fopen($_obf_JTe7jJ4eGW8_, 'rb');
			$_obf_6hS1Rw__ = array();
			$_obf_6RYLWQ__ = fread($_obf_YBY_, filesize($_obf_JTe7jJ4eGW8_));
			$_obf_YfrY8VEd = substr($_obf_6RYLWQ__, 0, 36);
			$_obf_5nVJEQ__ = substr($_obf_6RYLWQ__, 44);
			$_obf_6RYLWQ__ = unpack('NChunkID/VChunkSize/NFormat/NSubChunk1ID/VSubChunk1Size/vAudioFormat/vNumChannels/VSampleRate/VByteRate/vBlockAlign/vBitsPerSample', $_obf_YfrY8VEd);
			$_obf_6hS1Rw__['sub_chunk1_id'] = $_obf_6RYLWQ__['SubChunk1ID'];
			$_obf_6hS1Rw__['bits_per_sample'] = $_obf_6RYLWQ__['BitsPerSample'];
			$_obf_6hS1Rw__['channels'] = $_obf_6RYLWQ__['NumChannels'];
			$_obf_6hS1Rw__['format'] = $_obf_6RYLWQ__['AudioFormat'];
			$_obf_6hS1Rw__['sample_rate'] = $_obf_6RYLWQ__['SampleRate'];
			$_obf_6hS1Rw__['size'] = $_obf_6RYLWQ__['ChunkSize'] + 8;
			$_obf_6hS1Rw__['data'] = $_obf_5nVJEQ__;

			if (($_obf_8w__ = strpos($_obf_6hS1Rw__['data'], 'LIST')) !== false) {
				$_obf_o5fQ1g__ = substr($_obf_6hS1Rw__['data'], $_obf_8w__ + 4, 8);
				$_obf_6RYLWQ__ = unpack('Vlength/Vjunk', $_obf_o5fQ1g__);
				$_obf_6hS1Rw__['data'] = substr($_obf_6hS1Rw__['data'], 0, $_obf_8w__);
				$_obf_6hS1Rw__['size'] = $_obf_6hS1Rw__['size'] - strlen($_obf_6hS1Rw__['data']) - $_obf_8w__;
			}

			$_obf_I9APXAk_[] = $_obf_6hS1Rw__;
			$_obf_6RYLWQ__ = NULL;
			$_obf_YfrY8VEd = NULL;
			$_obf_5nVJEQ__ = NULL;
			$_obf__DUZp_U3OjQ_ += strlen($_obf_6hS1Rw__['data']);
			fclose($_obf_YBY_);
		}

		$_obf_mG5Mya7VPXI_ = '';
		$_obf_7w__ = 0;

		for (; $_obf_7w__ < sizeof($_obf_I9APXAk_); ++$_obf_7w__) {
			if ($_obf_7w__ == 0) {
				$_obf_mG5Mya7VPXI_ .= pack('C4VC8', ord('R'), ord('I'), ord('F'), ord('F'), $_obf__DUZp_U3OjQ_ + 36, ord('W'), ord('A'), ord('V'), ord('E'), ord('f'), ord('m'), ord('t'), ord(' '));
				$_obf_mG5Mya7VPXI_ .= pack('VvvVVvv', 16, $_obf_I9APXAk_[$_obf_7w__]['format'], $_obf_I9APXAk_[$_obf_7w__]['channels'], $_obf_I9APXAk_[$_obf_7w__]['sample_rate'], $_obf_I9APXAk_[$_obf_7w__]['sample_rate'] * (($_obf_I9APXAk_[$_obf_7w__]['bits_per_sample'] * $_obf_I9APXAk_[$_obf_7w__]['channels']) / 8), ($_obf_I9APXAk_[$_obf_7w__]['bits_per_sample'] * $_obf_I9APXAk_[$_obf_7w__]['channels']) / 8, $_obf_I9APXAk_[$_obf_7w__]['bits_per_sample']);
				$_obf_mG5Mya7VPXI_ .= pack('C4', ord('d'), ord('a'), ord('t'), ord('a'));
				$_obf_mG5Mya7VPXI_ .= pack('V', $_obf__DUZp_U3OjQ_);
			}

			$_obf_mG5Mya7VPXI_ .= $_obf_I9APXAk_[$_obf_7w__]['data'];
		}

		return $_obf_mG5Mya7VPXI_;
	}
}

define('SI_IMAGE_JPEG', 1);
define('SI_IMAGE_PNG', 2);
define('SI_IMAGE_GIF', 3);

?>
