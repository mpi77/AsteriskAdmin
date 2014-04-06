<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>

<?php
	if($_GET["action"] == "edit" || $_GET["action"] == "create"){
		$a = ($_GET["action"] == "create") ? sprintf("%s/%s/", $_GET["route"], $_GET["action"]) : sprintf("%s/%s/%d/", $_GET["route"], $_GET["action"], $_GET["id"]);
		$page_name = ($_GET["action"] == "create") ? Translate::get(Translator::PAGE_NAME_EXTENSION_CREATE) : Translate::get(Translator::PAGE_NAME_EXTENSION_EDIT);
		?>
<div id="page_name">
	<h1><?php echo htmlspecialchars($page_name)?></h1>
	<a href="extension/lst/"><?php Translate::display(Translator::PAGE_NAME_EXTENSION_LIST);?></a>
</div>
<div>
	<form id="ext_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded">
		<?php if($_GET["action"] == "edit"){?>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_ID);?></span> <span class="value"><?php echo htmlspecialchars($out[0]);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<?php }?>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_CONTEXT);?></span> <input type="text" name="ext_context" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["ext_context"]) ? $_POST["ext_context"] : (!empty($out[1]) ? $out[1] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::EXTENSION_TITLE_CONTEXT);?>"><?php Translate::display(Translator::EXTENSION_HELP_CONTEXT);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_LINE);?></span> <input type="text" name="ext_line" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["ext_line"]) ? $_POST["ext_line"] : (!empty($out[2]) ? $out[2] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::EXTENSION_TITLE_LINE);?>"><?php Translate::display(Translator::EXTENSION_HELP_LINE);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_PRIORITY);?></span> <input type="text" name="ext_priority" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["ext_priority"]) ? $_POST["ext_priority"] : (!empty($out[3]) ? $out[3] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::EXTENSION_TITLE_PRIORITY);?>"><?php Translate::display(Translator::EXTENSION_HELP_PRIORITY);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_APP);?></span> <input type="text" name="ext_app" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["ext_app"]) ? $_POST["ext_app"] : (!empty($out[4]) ? $out[4] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::EXTENSION_TITLE_APP);?>"><?php Translate::display(Translator::EXTENSION_HELP_APP);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div>
			<span class="title"><?php Translate::display(Translator::EXTENSION_APPDATA);?></span> <input type="text" name="ext_appdata" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["ext_appdata"]) ? $_POST["ext_appdata"] : (!empty($out[5]) ? $out[5] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::EXTENSION_TITLE_APPDATA);?>"><?php Translate::display(Translator::EXTENSION_HELP_APPDATA);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="cleaner_micro">&nbsp;</div>
		<div class="btn_row">
			<?php System::printAuthInput();?>
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "extension"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>

<?php if($_GET["action"] == "delete"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_EXTENSION_DELETE);?></h1>
	<a href="extension/lst/"><?php Translate::display(Translator::PAGE_NAME_EXTENSION_LIST);?></a>
</div>
<div>
	<form id="ext_delete" action="<?php echo htmlspecialchars(sprintf("%s/%s/%d/", $_GET["route"], $_GET["action"], $_GET["id"]));?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="value"><?php echo htmlspecialchars(sprintf(Translate::get(Translator::EXTENSION_CONFIRM_DELETE), $out[0]));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<?php System::printAuthInput();?>
			<input type="hidden" name="confirm_delete" value="yes" />
			<input type="submit" value="<?php Translate::display(Translator::BTN_DELETE);?>" class="send_btn" />
			<a href="extension/lst/"><?php Translate::display(Translator::BTN_BACK);?></a>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
</div>
<?php }?>

<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_EXTENSION_LIST);?></h1>
	<a href="extension/create/"><?php Translate::display(Translator::PAGE_NAME_EXTENSION_CREATE);?></a>
</div>
<div><?php echo $out;?></div>
<script type="text/javascript">
<!--
	$(".edit a").button({
		icons: {
			primary: "ui-icon-pencil"
		},
		text: false
	});
	$(".delete a").button({
		icons: {
			primary: "ui-icon-trash"
		},
		text: false
	});
//-->
</script>
<?php }?>

<?php
	if($_GET["action"] == "pstncreate"){
		$a = sprintf("%s/%s/", $_GET["route"], $_GET["action"]);
		$page_name = Translate::get(Translator::PAGE_NAME_PSTN_CREATE);
?>
<div id="page_name">
	<h1><?php echo htmlspecialchars($page_name)?></h1>
	<a href="extension/pstnlst/"><?php Translate::display(Translator::PAGE_NAME_PSTN_LIST);?></a>
</div>
<div>
	<form id="ext_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::PSTN_LINE);?></span> <input type="text" name="pstn_number" class="value"
				value="<?php echo htmlspecialchars(!empty($_POST["pstn_number"]) ? $_POST["pstn_number"] : "");?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span>
			<span class="help" title="<?php Translate::display(Translator::PSTN_TITLE_LINE);?>"><?php Translate::display(Translator::PSTN_HELP_LINE);?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="cleaner_micro">&nbsp;</div>
		<div class="btn_row">
			<?php System::printAuthInput();?>
			<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
	<script type="text/javascript">
	<!-- 
		VALIDATOR = "pstn"; 
	//-->
	</script>
	<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
</div>
<?php }?>

<?php if($_GET["action"] == "pstndelete"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_PSTN_DELETE);?></h1>
	<a href="extension/pstnlst/"><?php Translate::display(Translator::PAGE_NAME_PSTN_LIST);?></a>
</div>
<div>
	<form id="ext_delete" action="<?php echo htmlspecialchars(sprintf("%s/%s/%d/", $_GET["route"], $_GET["action"], $_GET["id"]));?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="value"><?php echo htmlspecialchars(sprintf(Translate::get(Translator::PSTN_CONFIRM_DELETE), substr($out[2],6)));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<?php System::printAuthInput();?>
			<input type="hidden" name="confirm_delete" value="yes" />
			<input type="submit" value="<?php Translate::display(Translator::BTN_DELETE);?>" class="send_btn" />
			<a href="extension/pstnlst/"><?php Translate::display(Translator::BTN_BACK);?></a>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
</div>
<?php }?>

<?php if($_GET["action"] == "pstnlst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_PSTN_LIST);?></h1>
	<a href="extension/pstncreate/"><?php Translate::display(Translator::PAGE_NAME_PSTN_CREATE);?></a>
</div>
<div><?php echo $out;?></div>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$(".edit a").button({
		icons: {
			primary: "ui-icon-pencil"
		},
		text: false
	});
	$(".cancel a").button({
		icons: {
			primary: "ui-icon-link"
		},
		text: false
	});
	$(".delete a").button({
		icons: {
			primary: "ui-icon-trash"
		},
		text: false
	});
});
//-->
</script>
<?php }?>


<?php /* end of loggedin user*/ }?>