<?php
/* EMAIL TEMPLATE - LOST PASSWORD */
$subject = "AsteriskAdmin :: zapomenute heslo";
$altbody = "Zapomenuté heslo".NL."Pro nastavení nového hesla na serveru %s přejděte na následující adresu:".NL."%s".NL."Nastavení hesla proveďte během následujících %d hodin.".NL."vygenerováno: %s (server: %s)";
$body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>TOBYT :: zapomenuté heslo</title>
<style type=\"text/css\">
*{padding:0px; margin:0px;}
div#header{
	border-bottom:1px solid red;
	font-size:22px;
	font-family:Arial, Helvetica, sans-serif;
	padding:10px;
	font-weight:bold;
}
div.main{
	font-size:16px;
	padding-top:5px;
	padding-bottom:5px;
	padding-left:20px;
	font-family:\"Times New Roman\", Times, serif;
}
div#footer{
	height:30px;
	border-top:1px solid red;
	font-family:\"Times New Roman\", Times, serif;
	font-size:14px;
	text-align:center;
	padding-top:5px;
}
strong{
	color:#FF0000;
}
</style>
</head>
<body>
<div id=\"header\">Zapomenuté heslo</div>
<div class=\"main\">Pro nastavení nového hesla na serveru %s přejděte na následující adresu:</div>
<div class=\"main\"><a href=\"%s\">%s</a></div>
<div class=\"main\">Nastavení hesla proveďte během následujících %d hodin.</div>
<div id=\"footer\">vygenerováno: <strong>%s</strong> (server: %s)</div>
</body>
</html>";
$link = sprintf("%suser/setpassword/%s/", Config::SITE_PATH, $msg_data["token"]);
$body = sprintf($body, Config::SITE_PATH, $link, $link, UserController::USER_RENEW_TIME_LIMIT, $msg_data["timestamp"],$msg_data["server"]);
$altbody = sprintf($altbody, Config::SITE_PATH, $link, UserController::USER_RENEW_TIME_LIMIT, $msg_data["timestamp"],$msg_data["server"]);

?>