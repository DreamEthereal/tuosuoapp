<?php
//dezend by http://www.yunlu99.com/
error_reporting(0);
@set_time_limit(0);
$imgtype = 'jpeg';
$imgquality = 100;
$data = &$_POST;
$width = (int) $data['width'];
$height = (int) $data['height'];
$img = imagecreatetruecolor($width, $height);
$y = 0;

for (; $y < $height; $y++) {
	$x = 0;
	$row = explode(',', $data['r' . $y]);
	$cnt = sizeof($row);
	$r = 0;

	for (; $r < $cnt; $r++) {
		$pixel = explode(':', $row[$r]);
		$pixel[0] = str_pad($pixel[0], 6, '0', STR_PAD_LEFT);
		$cr = hexdec(substr($pixel[0], 0, 2));
		$cg = hexdec(substr($pixel[0], 2, 2));
		$cb = hexdec(substr($pixel[0], 4, 2));
		$color = imagecolorallocate($img, $cr, $cg, $cb);
		$repeat = (isset($pixel[1]) ? (int) $pixel[1] : 1);
		$c = 0;

		for (; $c < $repeat; $c++) {
			imagesetpixel($img, $x, $y, $color);
			$x++;
		}
	}
}

header('Content-Disposition: attachment; filename="Survey_DataChart.' . $imgtype . '"');
header('Content-type: image/' . $imgtype);
$function = 'image' . $imgtype;

if ($imgtype == 'gif') {
	$function($img);
}
else {
	$function($img, NULL, $imgquality);
}

imagedestroy($img);

?>
