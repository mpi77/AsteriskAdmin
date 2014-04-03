<?php 
/**
 * Show error page.
 *
 * @version 1.1
 * @author MPI
 * */
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);

$codes = array(
		400 => array('400 Bad Request', 'Špatná syntaxe požadavku.'),
		401 => array('401 Unauthorized', 'Požadavek vyžaduje ověření.'),
		403 => array('403 Forbidden', 'Požadavek byl zamítnut.'),
		404 => array('404 Not Found', 'Požadovaná stránka nebyla nalezena.'),
		405 => array('405 Method Not Allowed', 'Metoda specifikovaná v požadavku není povolená.'),
		408 => array('408 Request Timeout', 'Při čekání na požadavek vypršel časový limit serveru.'),
		500 => array('500 Internal Server Error', 'Na serveru došlo k chybě, a proto nelze požadavek splnit.'),
		501 => array('501 Not Implemented', 'Server není schopen požadavek zpracovat.'),
		502 => array('502 Bad Gateway', 'Nesprávná odpověď vzdáleného serveru.'),
		503 => array('503 Service Unavailable', 'Server je momentálně nedostupný.'),
		504 => array('504 Gateway Timeout', 'Nesprávná odpověď vzdáleného serveru.'),
		505 => array('505 HTTP Version Not Supported', 'Server nepodporuje verzi protokolu HTTP použitou v požadavku.')
);
$i = 404; // default error id
if(isset($_GET["code"]) && is_numeric($_GET["code"]) && array_key_exists($_GET["code"], $codes)) {
	$i = $_GET["code"];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<base href="/~martin/asterisk_admin/">
<title><?php echo htmlspecialchars("Error ".$i);?></title>
<link href="error/error.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="cont">
	<div id="header"><span><?php echo htmlspecialchars("Chyba ".$i);?></span></div>
	<div id="message"><span><?php echo htmlspecialchars($codes[$i][1]);?></span></div>
	<div><span><a href="index.php">Try</a> it again later.</span></div>
</div>

</body>
</html>