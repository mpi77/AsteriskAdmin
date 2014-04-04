<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>
<?php if($_GET["action"] == "login"){?>
<div id="page_name">
	<h1>Succesfully login.</h1>
</div>
<?php }?>
<?php if($_GET["action"] == "edit"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_EDIT);?></h1>
</div>
<div>
	<form id="user_edit" action="<?php echo sprintf("%s/%s/%d/",$_GET["route"],$_GET["action"], $_GET["id"]);?>" method="post"
		enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_EMAIL);?></span><span class="value"><?php echo htmlspecialchars($out[1]);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_ACCOUNT_TYPE);?></span><span class="value"><?php echo htmlspecialchars(UserController::getNameUserType($out[2]));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_FIRST_NAME);?></span><input type="text" name="first_name" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["first_name"]) ? $_POST["first_name"] : $out[3]);?>" /><span class="req">&nbsp;</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_FIRST_NAME);?>"><?php Translate::display(Translator::USER_HELP_FIRST_NAME);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_LAST_NAME);?></span> <input type="text" name="last_name" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["last_name"]) ? $_POST["last_name"] : $out[4]);?>" /> <span class="req">&nbsp;</span> <span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_LAST_NAME);?>"><?php Translate::display(Translator::USER_HELP_LAST_NAME);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_PHONE);?></span><input type="text" name="phone" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["phone"]) ? $_POST["phone"] : $out[5]);?>" /><span class="req">&nbsp;</span><span class="help"
				title="<?php Translate::display(Translator::USER_TITLE_PHONE);?>"><?php Translate::display(Translator::USER_HELP_PHONE);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_LANG);?></span><select name="select_lang"><?php echo $out2;?></select><span class="req">&nbsp;</span><span class="help">&nbsp;</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_LAST_LOGIN);?></span><span class="value"><?php echo htmlspecialchars(empty($out[6]) ? "none" : System::prepareDate(array("act"=>System::DATE_FORMAT_FROM_TS, "old"=>$out[6])));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<form id="user_edit_pass" action="<?php echo sprintf("%s/%s/%d/",$_GET["route"],$_GET["action"], $_GET["id"]);?>" method="post"
		enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_OLD_PASSWORD);?></span><input type="password" name="password_old" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD);?></span><input type="password" name="password_1" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD_AGAIN);?></span><input type="password" name="password_2" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD_AGAIN);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD_AGAIN);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "user_edit"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>	
<?php if($_GET["action"] == "log"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_LOG_LIST);?></h1>
</div>
<div><?php echo $out;?></div>
<?php }?>
<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_LIST);?></h1>
	<a href="user/addrequest/"><?php Translate::display(Translator::PAGE_NAME_USER_REQUEST);?></a>
</div>
<div><?php echo $out;?></div>
<?php }?>
<?php if($_GET["action"] == "addrequest"){
	$t = "";
	$form_action = sprintf("%s/%s/", $_GET["route"], $_GET["action"]);
	foreach(UserController::getUserTypeTable($_SESSION["user"]["type"] + 1) as $k => $v){
		$t .= sprintf("<option value=\"%d\">%s</option>", $k, $v);
	}
?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_REQUEST);?></h1>
	<a href="user/lst/"><?php Translate::display(Translator::PAGE_NAME_USER_LIST);?></a>
</div>
<div>
	<form id="user_edit" action="<?php echo $form_action;?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_EMAIL);?></span><input type="text" name="email" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["email"]) ? $_POST["email"] : "");?>" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_EMAIL);?>"><?php Translate::display(Translator::USER_HELP_EMAIL);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_ACCOUNT_TYPE);?></span><select name="type"><?php echo $t;?></select><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "user_request"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>	

<?php /* end of loggedin user*/ } else { /* unlogged user*/?>

<?php if($_GET["action"] == "login"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_LOGIN);?></h1>
</div>
<div>
	<form action="<?php echo sprintf("%s/%s/",$_GET["route"],$_GET["action"]);?>" method="post" enctype="application/x-www-form-urlencoded"
		id="user_login">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_EMAIL);?></span> <input type="text" name="email" class="value" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_PASSWORD);?></span><input type="password" name="password" class="value" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SEND);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="renew_link">
			<a href="user/renew/"><?php Translate::display(Translator::USER_LOST_PASSWORD);?></a>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "login"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>
<?php if($_GET["action"] == "renew"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_RENEW);?></h1>
</div>
<div>
	<form action="<?php echo sprintf("%s/%s/",$_GET["route"],$_GET["action"]);?>" method="post" enctype="application/x-www-form-urlencoded"
		id="user_renew">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_EMAIL);?></span> 
			<input type="text" name="renew_value"
				value="<?php echo htmlspecialchars(isset($_POST["renew_value"]) && !empty($_POST["renew_value"]) ? $_POST["renew_value"] : "");?>" /><span
				class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SEND);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "renew"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>
<?php if($_GET["action"] == "setpassword"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_SET_PASSWORD);?></h1>
</div>
<div>
	<form action="<?php echo sprintf("%s/%s/%s/",$_GET["route"],$_GET["action"], $_GET["token"]);?>" method="post"
		enctype="application/x-www-form-urlencoded" id="user_setpassword">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_VALID_UNTIL);?></span><span class="value"><?php echo htmlspecialchars(System::prepareDate(array("act"=>System::DATE_FORMAT_FROM_TS, "old"=>$out[2])));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD);?></span><input type="password" name="password_1" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span class="help"
				title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD_AGAIN);?></span><input type="password" name="password_2" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD_AGAIN);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD_AGAIN);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "user_setpassword"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>
<?php if($_GET["action"] == "create"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_USER_CREATE);?></h1>
</div>
<div>
	<form action="<?php echo sprintf("%s/%s/%s/",$_GET["route"],$_GET["action"], $_GET["token"]);?>" method="post"
		enctype="application/x-www-form-urlencoded" id="user_create">
		<div>
			<span class="title"><?php Translate::display(Translator::USER_EMAIL);?></span><span class="value"><?php echo htmlspecialchars($out[1]);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_VALID_UNTIL);?></span><span class="value"><?php echo htmlspecialchars(System::prepareDate(array("act"=>System::DATE_FORMAT_FROM_TS, "old"=>$out[3])));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD);?></span><input type="password" name="password_1" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span class="help"
				title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::USER_NEW_PASSWORD_AGAIN);?></span><input type="password" name="password_2" class="value" value="" /><span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
				class="help" title="<?php Translate::display(Translator::USER_TITLE_NEW_PASSWORD_AGAIN);?>"><?php Translate::display(Translator::USER_HELP_NEW_PASSWORD_AGAIN);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "user_create"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>
	<?php if($_GET["action"] == "logout"){?>
<div id="page_name">
	<h1>Succesfully logout.</h1>
</div>
<?php }?>

<?php } /* end of unlogged user */?>