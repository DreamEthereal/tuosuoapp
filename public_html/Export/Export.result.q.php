<?php

//$_SESSION['dataSQL']='Yi5hdXRoU3RhdCBJTiAoMSw0KSBhbmQgIGIuZGF0YVNvdXJjZSBOT1QgSU4gKDEsMiwzKSBhbmQgRklORF9JTl9TRVQoYi50YXNrSUQsJzM5MDUsMzkwNiwzOTA3LDM5MDgsMzkwOSwzOTEwLDM5MTEsMzkxMiwzOTEzLDM5MTQsMzkxNSwzOTE2LDM5MTcsMzkxOCwzOTE5LDM5MjAsMzkyMSwzOTIyLDM5MjMsMzkyNCwzOTI1LDM5MjYsMzkyNywzOTI4LDM5MjksMzkzMCwzOTMxLDM5MzIsMzkzMywzOTM0LDM5MzUsMzkzNiwzOTM3LDM5MzgsMzkzOSwzOTQwLDM5NDEsMzk0MiwzOTQzLDM5NDQsMzk0NSwzOTQ2LDM5NDcsMzk0OCwzOTQ5LDM5NTAsMzk1MSwzOTUyLDM5NTMsMzk1NCwzOTU1LDM5NTYsMzk1NywzOTU4LDM5NTksMzk2MCwzOTYxLDM5NjIsMzk2MywzOTY0LDM5NjUsMzk2NiwzOTY3LDM5NjgsMzk2OSwzOTcwLDM5NzEsMzk3MiwzOTczLDM5NzQsMzk3NSwzOTc2LDM5NzcsMzk3OCwzOTc5LDM5ODAsMzk4MSwzOTgyLDM5ODMsMzk4NCwzOTg1LDM5ODYsMzk4NywzOTg4LDM5ODksMzk5MCwzOTkxLDM5OTIsMzk5MywzOTk0LDM5OTUsMzk5NiwzOTk3LDM5OTgsMzk5OSw0MDAwLDQwMDEsNDAwMiw0MDAzLDQwMDQsNDAwNSw0MDA2LDQwMDcsNDAwOCw0MDA5LDQwMTAsNDAxMSw0MDEyLDQwMTMsNDAxNCw0MDE1LDQwMTYsNDAxNyw0MDE4LDQwMTksNDAyMCw0MDIxLDQwMjIsNDAyMyw0MDI0LDQwMjUsNDAyNiw0MDI3LDQwMjgsNDAyOSw0MDMwLDQwMzEsNDAzMiw0MDMzLDQwMzQsNDAzNSw0MDM2LDQwMzcsNDAzOCw0MDM5LDQwNDAsNDA0MSw0MDQyLDQwNDMsNDA0NCw0MDQ1LDQwNDYsNDA0Nyw0MDQ4LDQwNDksNDA1MCw0MDUxLDQwNTIsNDA1Myw0MDU0LDQwNTUsNDA1Niw0MDU3LDQwNTgsNDA1OSw0MDYwLDQwNjEsNDA2Miw0MDYzLDQwNjQsNDA2NSw0MDY2LDQwNjcsNDA2OCw0MDY5LDQwNzAsNDA3MSw0MDcyLDQwNzMsNDA3NCw0MDc1LDQwNzYsNDA3Nyw0MDc4LDQwNzksNDA4MCw0MDgxLDQwODIsNDA4Myw0MDg0LDQwODUsNDA4Niw0MDg3LDQwODgsNDA4OSw0MDkwLDQwOTEsNDA5Miw0MDkzLDQwOTQsNDA5NSw0MDk2LDQwOTcsNDA5OCw0MDk5LDQxMDAsNDEwMSw0MTAyLDQxMDMsNDEwNCw0MTA1LDQxMDYsNDEwNyw0MTA4LDQxMDksNDExMCw0MTExLDQxMTIsNDExMyw0MTE0LDQxMTUsNDExNiw0MTE3LDQxMTgsNDExOSw0MTIwLDQxMjEsNDEyMiw0MTIzLDQxMjQsNDEyNSw0MTI2LDQxMjcsNDEyOCw0MTI5LDQxMzAsNDEzMSw0MTMyLDQxMzMsNDEzNCw0MTM1LDQxMzYsNDEzNyw0MTM4LDQxMzksNDE0MCw0MTQxLDQxNDIsNDE0Myw0MTQ0LDQxNDUsNDE0Niw0MTQ3LDQxNDgsNDE0OSw0MTUwLDQxNTEsNDE1Miw0MTUzLDQxNTQsNDE1NSw0MTU2LDQxNTcsNDE1OCw0MTU5LDQxNjAsNDE2MSw0MTYyLDQxNjMsNDE2NCw0MTY1LDQxNjYsNDE2Nyw0MTY4LDQxNjksNDE3MCw0MTcxLDQxNzIsNDE3Myw0MTc0LDQxNzUsNDE3Niw0MTc3LDQxNzgsNDE3OSw0MTgwLDQxODEsNDE4Miw0MTgzLDQxODQsNDE4NSw0MTg2LDQxODcsNDE4OCw0MTg5LDQxOTAsNDE5MSw0MTkyLDQxOTMsNDE5NCw0MTk1LDQxOTYsNDE5Nyw0MTk4LDQxOTksNDIwMCw0MjAxLDQyMDIsNDIwMyw0MjA0LDQyMDUsNDIwNiw0MjA3LDQyMDgsNDIwOSw0MjEwLDQyMTEsNDIxMiw0MjEzLDQyMTQsNDIxNSw0MjE2LDQyMTcsNDIxOCw0MjE5LDQyMjAsNDIyMSw0MjIyLDQyMjMsNDIyNCw0MjI1LDQyMjYsNDIyNyw0MjI4LDQyMjksNDIzMCw0MjMxLDQyMzIsNDIzMyw0MjM0LDQyMzUsNDIzNiw0MjM3LDQyMzgsNDIzOSw0MjQwLDQyNDEsNDI0Miw0MjQzLDQyNDQsNDI0NSw0MjQ2LDQyNDcsNDI0OCw0MjQ5LDQyNTAsNDI1MSw0MjUyLDQyNTMsNDI1NCw0MjU1LDQyNTYsNDI1Nyw0MjU4LDQyNTksNDI2MCw0MjYxLDQyNjIsNDI2Myw0MjY0LDQyNjUsNDI2Niw0MjY3LDQyNjgsNDI2OSw0MjcwLDQyNzEsNDI3Miw0MjczLDQyNzQsNDI3NSw0Mjc2LDQyNzcsNDI3OCw0Mjc5LDQyODAsNDI4MSw0MjgyLDQyODMsNDI4NCw0Mjg1LDQyODYsNDI4Nyw0Mjg4LDQyODksNDI5MCw0MjkxLDQyOTIsNDI5Myw0Mjk0LDQyOTUsNDI5Niw0Mjk3LDQyOTgsNDI5OSw0MzAwLDQzMDEsNDMwMiw0MzAzLDQzMDQsNDMwNSw0MzA2LDQzMDcsNDMwOCw0MzA5LDQzMTAsNDMxMSw0MzEyLDQzMTMsNDMxNCw0MzE1LDQzMTYsNDMxNyw0MzE4LDQzMTksNDMyMCw0MzIxLDQzMjIsNDMyMyw0MzI0LDQzMjUsNDMyNiw0MzI3LDQzMjgsNDMyOSw0MzMwLDQzMzEsNDMzMiw0MzMzLDQzMzQsNDMzNSw0MzM2LDQzMzcsNDMzOCw0MzM5LDQzNDAsNDM0MSw0MzQyLDQzNDMsNDM0NCw0MzQ1LDQzNDYsNDM0Nyw0MzQ4LDQzNDksNDM1MCw0MzUxLDQzNTIsNDM1Myw0MzU0LDQzNTUsNDM1Niw0MzU3LDQzNTgsNDM1OSw0MzYwLDQzNjEsNDM2Miw0MzYzLDQzNjQsNDM2NSw0MzY2LDQzNjcsNDM2OCw0MzY5LDQzNzAsNDM3MSw0MzcyLDQzNzMsNDM3NCw0Mzc1LDQzNzYsNDM3Nyw0Mzc4LDQzNzksNDM4MCw0MzgxLDQzODIsNDM4Myw0Mzg0LDQzODUsNDM4Niw0Mzg3LDQzODgsNDM4OSw0MzkwLDQzOTEsNDM5Miw0MzkzLDQzOTQsNDM5NSw0Mzk2LDQzOTcsNDM5OCw0Mzk5LDQ0MDAsNDQwMSw0NDAyLDQ0MDMsNDQwNCw0NDA1LDQ0MDYsNDQwNyw0NDA4LDQ0MDksNDQxMCw0NDExLDQ0MTIsNDQxMyw0NDE0LDQ0MTUsNDQxNiw0NDE3LDQ0MTgsNDQxOSw0NDIwLDQ0MjEsNDQyMiw0NDIzLDQ0MjQsNDQyNSw0NDI2LDQ0MjcsNDQyOCw0NDI5LDQ0MzAsNDQzMSw0NDMyLDQ0MzMsNDQzNCw0NDM1LDQ0MzYsNDQzNyw0NDM4LDQ0MzksNDQ0MCw0NDQxLDQ0NDIsNDQ0Myw0NDQ0LDQ0NDUsNDQ0Niw0NDQ3LDQ0NDgsNDQ0OSw0NDUwLDQ0NTEsNDQ1Miw0NDUzLDQ0NTQsNDQ1NSw0NDU2LDQ0NTcsNDQ1OCw0NDU5LDQ0NjAsNDQ2MSw0NDYyLDQ0NjMsNDQ2NCw0NDY1LDQ0NjYsNDQ2Nyw0NDY4LDQ0NjksNDQ3MCw0NDcxLDQ0NzIsNDQ3Myw0NDc0LDQ0NzUsNDQ3Niw0NDc3LDQ0NzgsNDQ3OSw0NDgwLDQ0ODEsNDQ4Miw0NDgzLDQ0ODQsNDQ4NSw0NDg2LDQ0ODcsNDQ4OCw0NDg5LDQ0OTAsNDQ5MSw0NDkyLDQ0OTMsNDQ5NCw0NDk1LDQ0OTYsNDQ5Nyw0NDk4LDQ0OTksNDUwMCw0NTAxLDQ1MDIsNDUwMyw0NTA0LDQ1MDUsNDUwNiw0NTA3LDQ1MDgsNDUwOSw0NTEwLDQ1MTEsNDUxNCw0NTE1LDQ1MTYsNDUxNyw0NTE4LDQ1MTksNDUyMCw0NTIxLDQ1MjIsNDUyMyw0NTI0LDQ1MjUsNDUyNiw0NTI3LDQ1MjgsNDUyOSw0NTMwLDQ1MzEsNDUzMiw0NTMzLDQ1MzQsNDUzNSw0NTM2LDQ1MzcsNDUzOCw0NTM5LDQ1NDAsNDU0MSw0NTQyLDQ1NDMsNDU0NCw0NTQ1LDQ1NDYsNDU0Nyw0NTQ4LDQ1NDksNDU1MCw0NTUxLDQ1NTIsNDU1Myw0NTU0LDQ1NTUsNDU1Niw0NTU3LDQ1NTgsNDU1OSw0NTYwLDQ1NjEsNDU2Miw0NTYzLDQ1NjQsNDU2OSw0NTczLDQ1NzQsNDU3NSw0NTc2LDQ1NzcsNDU3OCw0NTc5LDQ1ODAsNDU4MSw0NTgyLDQ1ODMsNDU4NCw0NTg1LDQ1ODYsNDU4Nyw0NTg4LDQ1ODksNDU5MCw0NTkxLDQ1OTIsNDU5Myw0NTk0LDQ1OTUsNDU5Niw0NTk3JykgYW5kICAoYi5vdmVyRmxhZyAhPSAyIGFuZCBiLmF1dGhTdGF0ICE9IDIp';


//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.export.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
//$_SESSION['dataSQL']='Yi5hdXRoU3RhdCBJTiAoMSw0KSBhbmQgIGIuZGF0YVNvdXJjZSBOT1QgSU4gKDEsMiwzKSBhbmQgRklORF9JTl9TRVQoYi50YXNrSUQsJzM5MDUsMzkwNiwzOTA3LDM5MDgsMzkwOSwzOTEwLDM5MTEsMzkxMiwzOTEzLDM5MTQsMzkxNSwzOTE2LDM5MTcsMzkxOCwzOTE5LDM5MjAsMzkyMSwzOTIyLDM5MjMsMzkyNCwzOTI1LDM5MjYsMzkyNywzOTI4LDM5MjksMzkzMCwzOTMxLDM5MzIsMzkzMywzOTM0LDM5MzUsMzkzNiwzOTM3LDM5MzgsMzkzOSwzOTQwLDM5NDEsMzk0MiwzOTQzLDM5NDQsMzk0NSwzOTQ2LDM5NDcsMzk0OCwzOTQ5LDM5NTAsMzk1MSwzOTUyLDM5NTMsMzk1NCwzOTU1LDM5NTYsMzk1NywzOTU4LDM5NTksMzk2MCwzOTYxLDM5NjIsMzk2MywzOTY0LDM5NjUsMzk2NiwzOTY3LDM5NjgsMzk2OSwzOTcwLDM5NzEsMzk3MiwzOTczLDM5NzQsMzk3NSwzOTc2LDM5NzcsMzk3OCwzOTc5LDM5ODAsMzk4MSwzOTgyLDM5ODMsMzk4NCwzOTg1LDM5ODYsMzk4NywzOTg4LDM5ODksMzk5MCwzOTkxLDM5OTIsMzk5MywzOTk0LDM5OTUsMzk5NiwzOTk3LDM5OTgsMzk5OSw0MDAwLDQwMDEsNDAwMiw0MDAzLDQwMDQsNDAwNSw0MDA2LDQwMDcsNDAwOCw0MDA5LDQwMTAsNDAxMSw0MDEyLDQwMTMsNDAxNCw0MDE1LDQwMTYsNDAxNyw0MDE4LDQwMTksNDAyMCw0MDIxLDQwMjIsNDAyMyw0MDI0LDQwMjUsNDAyNiw0MDI3LDQwMjgsNDAyOSw0MDMwLDQwMzEsNDAzMiw0MDMzLDQwMzQsNDAzNSw0MDM2LDQwMzcsNDAzOCw0MDM5LDQwNDAsNDA0MSw0MDQyLDQwNDMsNDA0NCw0MDQ1LDQwNDYsNDA0Nyw0MDQ4LDQwNDksNDA1MCw0MDUxLDQwNTIsNDA1Myw0MDU0LDQwNTUsNDA1Niw0MDU3LDQwNTgsNDA1OSw0MDYwLDQwNjEsNDA2Miw0MDYzLDQwNjQsNDA2NSw0MDY2LDQwNjcsNDA2OCw0MDY5LDQwNzAsNDA3MSw0MDcyLDQwNzMsNDA3NCw0MDc1LDQwNzYsNDA3Nyw0MDc4LDQwNzksNDA4MCw0MDgxLDQwODIsNDA4Myw0MDg0LDQwODUsNDA4Niw0MDg3LDQwODgsNDA4OSw0MDkwLDQwOTEsNDA5Miw0MDkzLDQwOTQsNDA5NSw0MDk2LDQwOTcsNDA5OCw0MDk5LDQxMDAsNDEwMSw0MTAyLDQxMDMsNDEwNCw0MTA1LDQxMDYsNDEwNyw0MTA4LDQxMDksNDExMCw0MTExLDQxMTIsNDExMyw0MTE0LDQxMTUsNDExNiw0MTE3LDQxMTgsNDExOSw0MTIwLDQxMjEsNDEyMiw0MTIzLDQxMjQsNDEyNSw0MTI2LDQxMjcsNDEyOCw0MTI5LDQxMzAsNDEzMSw0MTMyLDQxMzMsNDEzNCw0MTM1LDQxMzYsNDEzNyw0MTM4LDQxMzksNDE0MCw0MTQxLDQxNDIsNDE0Myw0MTQ0LDQxNDUsNDE0Niw0MTQ3LDQxNDgsNDE0OSw0MTUwLDQxNTEsNDE1Miw0MTUzLDQxNTQsNDE1NSw0MTU2LDQxNTcsNDE1OCw0MTU5LDQxNjAsNDE2MSw0MTYyLDQxNjMsNDE2NCw0MTY1LDQxNjYsNDE2Nyw0MTY4LDQxNjksNDE3MCw0MTcxLDQxNzIsNDE3Myw0MTc0LDQxNzUsNDE3Niw0MTc3LDQxNzgsNDE3OSw0MTgwLDQxODEsNDE4Miw0MTgzLDQxODQsNDE4NSw0MTg2LDQxODcsNDE4OCw0MTg5LDQxOTAsNDE5MSw0MTkyLDQxOTMsNDE5NCw0MTk1LDQxOTYsNDE5Nyw0MTk4LDQxOTksNDIwMCw0MjAxLDQyMDIsNDIwMyw0MjA0LDQyMDUsNDIwNiw0MjA3LDQyMDgsNDIwOSw0MjEwLDQyMTEsNDIxMiw0MjEzLDQyMTQsNDIxNSw0MjE2LDQyMTcsNDIxOCw0MjE5LDQyMjAsNDIyMSw0MjIyLDQyMjMsNDIyNCw0MjI1LDQyMjYsNDIyNyw0MjI4LDQyMjksNDIzMCw0MjMxLDQyMzIsNDIzMyw0MjM0LDQyMzUsNDIzNiw0MjM3LDQyMzgsNDIzOSw0MjQwLDQyNDEsNDI0Miw0MjQzLDQyNDQsNDI0NSw0MjQ2LDQyNDcsNDI0OCw0MjQ5LDQyNTAsNDI1MSw0MjUyLDQyNTMsNDI1NCw0MjU1LDQyNTYsNDI1Nyw0MjU4LDQyNTksNDI2MCw0MjYxLDQyNjIsNDI2Myw0MjY0LDQyNjUsNDI2Niw0MjY3LDQyNjgsNDI2OSw0MjcwLDQyNzEsNDI3Miw0MjczLDQyNzQsNDI3NSw0Mjc2LDQyNzcsNDI3OCw0Mjc5LDQyODAsNDI4MSw0MjgyLDQyODMsNDI4NCw0Mjg1LDQyODYsNDI4Nyw0Mjg4LDQyODksNDI5MCw0MjkxLDQyOTIsNDI5Myw0Mjk0LDQyOTUsNDI5Niw0Mjk3LDQyOTgsNDI5OSw0MzAwLDQzMDEsNDMwMiw0MzAzLDQzMDQsNDMwNSw0MzA2LDQzMDcsNDMwOCw0MzA5LDQzMTAsNDMxMSw0MzEyLDQzMTMsNDMxNCw0MzE1LDQzMTYsNDMxNyw0MzE4LDQzMTksNDMyMCw0MzIxLDQzMjIsNDMyMyw0MzI0LDQzMjUsNDMyNiw0MzI3LDQzMjgsNDMyOSw0MzMwLDQzMzEsNDMzMiw0MzMzLDQzMzQsNDMzNSw0MzM2LDQzMzcsNDMzOCw0MzM5LDQzNDAsNDM0MSw0MzQyLDQzNDMsNDM0NCw0MzQ1LDQzNDYsNDM0Nyw0MzQ4LDQzNDksNDM1MCw0MzUxLDQzNTIsNDM1Myw0MzU0LDQzNTUsNDM1Niw0MzU3LDQzNTgsNDM1OSw0MzYwLDQzNjEsNDM2Miw0MzYzLDQzNjQsNDM2NSw0MzY2LDQzNjcsNDM2OCw0MzY5LDQzNzAsNDM3MSw0MzcyLDQzNzMsNDM3NCw0Mzc1LDQzNzYsNDM3Nyw0Mzc4LDQzNzksNDM4MCw0MzgxLDQzODIsNDM4Myw0Mzg0LDQzODUsNDM4Niw0Mzg3LDQzODgsNDM4OSw0MzkwLDQzOTEsNDM5Miw0MzkzLDQzOTQsNDM5NSw0Mzk2LDQzOTcsNDM5OCw0Mzk5LDQ0MDAsNDQwMSw0NDAyLDQ0MDMsNDQwNCw0NDA1LDQ0MDYsNDQwNyw0NDA4LDQ0MDksNDQxMCw0NDExLDQ0MTIsNDQxMyw0NDE0LDQ0MTUsNDQxNiw0NDE3LDQ0MTgsNDQxOSw0NDIwLDQ0MjEsNDQyMiw0NDIzLDQ0MjQsNDQyNSw0NDI2LDQ0MjcsNDQyOCw0NDI5LDQ0MzAsNDQzMSw0NDMyLDQ0MzMsNDQzNCw0NDM1LDQ0MzYsNDQzNyw0NDM4LDQ0MzksNDQ0MCw0NDQxLDQ0NDIsNDQ0Myw0NDQ0LDQ0NDUsNDQ0Niw0NDQ3LDQ0NDgsNDQ0OSw0NDUwLDQ0NTEsNDQ1Miw0NDUzLDQ0NTQsNDQ1NSw0NDU2LDQ0NTcsNDQ1OCw0NDU5LDQ0NjAsNDQ2MSw0NDYyLDQ0NjMsNDQ2NCw0NDY1LDQ0NjYsNDQ2Nyw0NDY4LDQ0NjksNDQ3MCw0NDcxLDQ0NzIsNDQ3Myw0NDc0LDQ0NzUsNDQ3Niw0NDc3LDQ0NzgsNDQ3OSw0NDgwLDQ0ODEsNDQ4Miw0NDgzLDQ0ODQsNDQ4NSw0NDg2LDQ0ODcsNDQ4OCw0NDg5LDQ0OTAsNDQ5MSw0NDkyLDQ0OTMsNDQ5NCw0NDk1LDQ0OTYsNDQ5Nyw0NDk4LDQ0OTksNDUwMCw0NTAxLDQ1MDIsNDUwMyw0NTA0LDQ1MDUsNDUwNiw0NTA3LDQ1MDgsNDUwOSw0NTEwLDQ1MTEsNDUxNCw0NTE1LDQ1MTYsNDUxNyw0NTE4LDQ1MTksNDUyMCw0NTIxLDQ1MjIsNDUyMyw0NTI0LDQ1MjUsNDUyNiw0NTI3LDQ1MjgsNDUyOSw0NTMwLDQ1MzEsNDUzMiw0NTMzLDQ1MzQsNDUzNSw0NTM2LDQ1MzcsNDUzOCw0NTM5LDQ1NDAsNDU0MSw0NTQyLDQ1NDMsNDU0NCw0NTQ1LDQ1NDYsNDU0Nyw0NTQ4LDQ1NDksNDU1MCw0NTUxLDQ1NTIsNDU1Myw0NTU0LDQ1NTUsNDU1Niw0NTU3LDQ1NTgsNDU1OSw0NTYwLDQ1NjEsNDU2Miw0NTYzLDQ1NjQsNDU2OSw0NTczLDQ1NzQsNDU3NSw0NTc2LDQ1NzcsNDU3OCw0NTc5LDQ1ODAsNDU4MSw0NTgyLDQ1ODMsNDU4NCw0NTg1LDQ1ODYsNDU4Nyw0NTg4LDQ1ODksNDU5MCw0NTkxLDQ1OTIsNDU5Myw0NTk0LDQ1OTUsNDU5Niw0NTk3JykgYW5kICAoYi5vdmVyRmxhZyAhPSAyIGFuZCBiLmF1dGhTdGF0ICE9IDIp';

require_once ROOT_PATH . 'License/License.common.inc.php';
@set_time_limit(0);
$_GET['surveyID'] = (int) $_GET['surveyID'];
_checkpassport('1|2|3|5|7', $_GET['surveyID']);

$SQL = ' SELECT status,administratorsID,surveyID,isCache FROM ' . SURVEY_TABLE . ' WHERE surveyID=\'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);

if ($Row['status'] == '0') {
	_showerror($lang['system_error'], $lang['design_survey_now']);
}

if ($License['isEvalUsers']) {
	_showerror($lang['pls_register_soft'], $lang['pls_register_soft']);
}
$Row['surveyID'] = $_GET['surveyID'];
$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if ($_GET['Action'] == 'ExportDataSubmit') {
	ob_start();
	switch ($_GET['exportType']) {
	case 1:
	default:
		$ResultList = export($_GET['surveyID'],$_GET['exportSQL']);
		break;

	case 2:
		if (0 < count($_POST['exportQtnList'])) {
			$ResultList = export($_GET['surveyID'], $_GET['exportSQL'], $_POST['exportQtnList']);
		}

		break;
	}

	header('Pragma: no-cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Content-Type: application/octet-stream;charset=utf8');
	header('Content-Disposition: attachment; filename=Result_' . $_GET['surveyID'] . '_List_' . date('Y-m-d') . '.csv');
	echo $ResultList;
	exit();
}

$EnableQCoreClass->setTemplateFile('ExportVariableFile', 'ExportVariable.html');
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('exportQtnList', '');
$EnableQCoreClass->replace('exportSQL', urldecode($_GET['exportSQL']));
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	if (($theQtnArray['questionType'] != '9') && ($theQtnArray['questionType'] != '30')) {
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
	}
}

$EnableQCoreClass->replace('questionList', $questionList);
$EnableQCoreClass->parse('ExportVariablePage', 'ExportVariableFile');
$EnableQCoreClass->output('ExportVariablePage');

?>
