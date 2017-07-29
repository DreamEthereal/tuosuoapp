<?php
//dezend by http://www.yunlu99.com/
function JAMAError($type = NULL, $num = NULL, $file = NULL, $line = NULL, $context = NULL)
{
	global $error;
	$lang = LANG;
	if (isset($type) && isset($num) && isset($file) && isset($line)) {
		switch ($type) {
		case ERROR:
			echo '<div class="errror"><b>Error:</b> ' . $error[$lang][$num] . '</div>';
			exit();
			break;

		case WARNING:
			echo '<div class="warning"><b>Warning:</b> ' . $error[$lang][$num] . '</div>';
			break;

		case NOTICE:
			break;

		case 8:
			break;

		case 2048:
			break;

		case 2:
			break;

		default:
			echo '<div class="error"><b>Unknown Error Type:</b> ' . $type . ' - ' . $file . ' @ L' . $line . '</div>';
			exit();
			break;
		}
	}
	else {
		exit('Invalid arguments to JAMAError()');
	}
}

define('LANG', 'EN');
define('ERROR', 256);
define('WARNING', 512);
define('NOTICE', 1024);
$error = array();
define('PolymorphicArgumentException', -1);
$error['EN'][-1] = 'Invalid argument pattern for polymorphic function.';
define('ArgumentTypeException', -2);
$error['EN'][-2] = 'Invalid argument type.';
define('ArgumentBoundsException', -3);
$error['EN'][-3] = 'Invalid argument range.';
define('MatrixDimensionException', -4);
$error['EN'][-4] = 'Matrix dimensions are not equal.';
define('PrecisionLossException', -5);
$error['EN'][-5] = 'Significant precision loss detected.';
define('MatrixSPDException', -6);
$error['EN'][-6] = 'Can only perform operation on symmetric positive definite matrix.';
define('MatrixSingularException', -7);
$error['EN'][-7] = 'Can only perform operation on singular matrix.';
define('MatrixRankException', -8);
$error['EN'][-8] = 'Can only perform operation on full-rank matrix.';
define('ArrayLengthException', -9);
$error['EN'][-9] = 'Array length must be a multiple of m.';
define('RowLengthException', -10);
$error['EN'][-10] = 'All rows must have the same length.';
set_error_handler('JAMAError');
error_reporting(ERROR | WARNING);

?>
