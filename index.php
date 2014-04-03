<?php
/**
 * Index page.
 *
 * @version 1.8
 * @author MPI
 * */
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
define("NL", "\r\n");

require 'init.php';

$f = new FrontController();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php Translate::display(Translator::SITE_TITLE);?></title>
<base href="<?php echo htmlspecialchars(Config::SITE_BASE);?>">
<link rel="stylesheet" href="include/jquery/css/dark-hive/jquery-ui-1.10.3.custom.min.css">
<script src="include/jquery/js/jquery-1.9.1.js"></script>
<script src="include/jquery/js/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="gui/style/main.css" />
<script type="text/javascript" src="gui/scripts/main.js"></script>
</head>

<body>
	<div id="header">
		<div id="header_cont">
			<div id="logo">&nbsp;</div>
			<h1><?php Translate::display(Translator::SITE_TITLE_HIDDEN);?></h1>
			<?php if(Acl::isLoggedin()){ $name = (!empty($_SESSION["user"]["first_name"]) && !empty($_SESSION["user"]["last_name"])) ? sprintf("%s %s",$_SESSION["user"]["first_name"],$_SESSION["user"]["last_name"]) : $_SESSION["user"]["email"];?>
			<div id="logged_user">
				<a href="user/edit/<?php echo htmlspecialchars($_SESSION["user"]["uid"]);?>/"><?php echo htmlspecialchars($name);?></a>
				<span>&nbsp;</span>
				<a href="user/logout/"><?php Translate::display(Translator::MENU_LOGOUT);?></a>
			</div>
			<?php }?>
			<div id="menu">
			<ul id="nav">
				<li><a href="./"><?php Translate::display(Translator::MENU_HOME);?></a></li>
				<?php if(Acl::isLoggedin()){?>
				<?php if(Acl::isRoot()){?>
				<li><a href="#"><?php Translate::display(Translator::MENU_PBX);?></a>
					<ul>
						<li><a href="user/lst/"><?php Translate::display(Translator::MENU_USERS);?></a>
							<ul>
								<li><a href="user/lst/"><?php Translate::display(Translator::MENU_LIST);?></a></li>
								<li><a href="user/addrequest/"><?php Translate::display(Translator::MENU_ADD);?></a></li>
							</ul></li>
						<li><a href="extension/lst/"><?php Translate::display(Translator::MENU_EXTENSIONS);?></a>
							<ul>
								<li><a href="extension/lst/"><?php Translate::display(Translator::MENU_LIST);?></a></li>
								<li><a href="extension/create/"><?php Translate::display(Translator::MENU_ADD);?></a></li>
							</ul>
						</li>
						<li><a href="extension/pstnlst/"><?php Translate::display(Translator::MENU_PSTN);?></a>
							<ul>
								<li><a href="extension/pstnlst/"><?php Translate::display(Translator::MENU_LIST);?></a></li>
								<li><a href="extension/pstncreate/"><?php Translate::display(Translator::MENU_ADD);?></a></li>
							</ul>
						</li>
					</ul>
				</li>
				<?php }?>
				<li><a href="line/lst/"><?php Translate::display(Translator::MENU_LINE);?></a>
					<ul>
						<li><a href="line/lst/"><?php Translate::display(Translator::MENU_LIST);?></a></li>
						<li><a href="line/create/"><?php Translate::display(Translator::MENU_ADD);?></a></li>
					</ul>
				</li>
				<li><a href="phonebook/lst/"><?php Translate::display(Translator::MENU_PHONEBOOK);?></a></li>
				<li><a href="voicemail/lst/"><?php Translate::display(Translator::MENU_VOICEMAIL);?></a></li>
				<li><a href="cdr/lst/"><?php Translate::display(Translator::MENU_CDR);?></a></li>
				<li><a href="user/edit/<?php echo htmlspecialchars($_SESSION["user"]["uid"]);?>/"><?php Translate::display(Translator::MENU_USER_MY_ACCOUNT);?></a>
					<ul>
						<li><a href="user/edit/<?php echo htmlspecialchars($_SESSION["user"]["uid"]);?>/"><?php Translate::display(Translator::MENU_USER_ACCESS_DATA);?></a></li>
						<li><a href="user/log/"><?php Translate::display(Translator::MENU_USER_LOG);?></a></li>
					</ul>
				</li>
				<?php } else {?>
				<li><a href="user/login/"><?php Translate::display(Translator::MENU_LOGIN);?></a></li>
				<?php }?>
			</ul>
			</div>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</div>
	<div id="navigation">&nbsp;</div>
	<div id="cont">
		<div id="content"> 
		<?php $f->output(); ?>
		</div>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div id="footer">&copy;2014&nbsp;MPI</div>
</body>
</html>
