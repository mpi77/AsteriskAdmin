<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>

<?php
	if($_GET["action"] == "edit" || $_GET["action"] == "create"){
		$a = ($_GET["action"] == "create") ? sprintf("%s/%s/", $_GET["route"], $_GET["action"]) : sprintf("%s/%s/%d/", $_GET["route"], $_GET["action"], $_GET["id"]);
		$page_name = ($_GET["action"] == "create") ? Translate::get(Translator::PAGE_NAME_LINE_ADD) : Translate::get(Translator::PAGE_NAME_LINE_EDIT);
		?>
<div id="page_name">
	<h1><?php echo htmlspecialchars($page_name)?></h1>
	<a href="line/lst/"><?php Translate::display(Translator::PAGE_NAME_LINE_LIST);?></a>
</div>
<div>
	<div id="tabs">
		<ul>
			<li><a href="<?php echo htmlspecialchars($a);?>#tab-1"><?php Translate::display(Translator::LINE_TAB_1);?></a></li>
			<?php if($_GET["action"] == "edit"){?>
			<li><a href="<?php echo htmlspecialchars($a);?>#tab-2"><?php Translate::display(Translator::LINE_TAB_2);?></a></li>
			<li><a href="<?php echo htmlspecialchars($a);?>#tab-3"><?php Translate::display(Translator::LINE_TAB_3);?></a></li>
			<li><a href="<?php echo htmlspecialchars($a);?>#tab-4"><?php Translate::display(Translator::LINE_TAB_4);?></a></li>
			<li><a href="<?php echo htmlspecialchars($a);?>#tab-5"><?php Translate::display(Translator::LINE_TAB_5);?></a></li>
			<?php }?>
			</ul>
		<div id="tab-1">
			<form class="line_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded" id="line_edit_tab_1">
				<?php if($_GET["action"] == "create"){?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_NUMBER);?></span> <input type="text" name="line_number" id="spinner" class="value"
						value="<?php echo htmlspecialchars(!empty($_POST["line_number"]) ? $_POST["line_number"] : "");?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_NUMBER);?>"><?php Translate::display(Translator::LINE_HELP_NUMBER);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php }else{?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_NUMBER);?></span> <span class="value"><?php echo htmlspecialchars($out[2]);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php }?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_TITLE);?></span> <input type="text" name="line_name" class="value"
						value="<?php echo htmlspecialchars(!empty($_POST["line_name"]) ? $_POST["line_name"] : (!empty($out[3]) ? $out[3] : ""));?>" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span> <span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_TITLE);?>"><?php Translate::display(Translator::LINE_HELP_TITLE);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php if($_GET["action"] == "create"){?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_SECRET);?></span> <input type="password" name="line_secret_1" class="value" value="" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_SECRET);?>"><?php Translate::display(Translator::LINE_HELP_SECRET);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_SECRET_AGAIN);?></span> <input type="password" name="line_secret_2" class="value" value="" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_SECRET_AGAIN);?>"><?php Translate::display(Translator::LINE_HELP_SECRET_AGAIN);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php }?>
				<div class="cleaner_micro">&nbsp;</div>
				<div class="btn_row">
					<?php System::printAuthInput();?>
					<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
					<div class="cleaner_micro">&nbsp;</div>
				</div>
			</form>
		</div>
		<?php if($_GET["action"] == "edit"){
			$voicemail = (!empty($vm) && $vm != Database::EMPTY_RESULT && is_array($vm));
			$callfwd = (!empty($out[25]) && $out[25] == LineController::CONST_YES);
			$nat = (!empty($out[15]) && $out[15] == LineController::CONST_YES);
			$permit = (!empty($out[17]));
		?>
		<div id="tab-2">
			<form class="line_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded" id="line_edit_tab_2">
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_VOICEMAIL);?></span>
					<div id="radio_1">
						<input type="radio" id="radio1" name="line_voicemail" value="<?php echo LineController::CONST_ON;?>"<?php echo ($voicemail === true) ? " checked=\"checked\"" : ""; ?>><label for="radio1">Ano</label> 
						<input type="radio" id="radio2" name="line_voicemail" value="<?php echo LineController::CONST_OFF;?>"<?php echo ($voicemail === false) ? " checked=\"checked\"" : ""; ?>><label for="radio2">Ne</label>
					</div>
					<div id="voicemail_pass_box"<?php echo ($voicemail === true) ? " style=\"display:block;\"" : " style=\"display:none;\""; ?>>
						<span class="title"><?php Translate::display(Translator::LINE_VOICEMAIL_PASSWORD);?></span> <input type="text" name="line_voicemail_pass" class="value"
							value="<?php echo htmlspecialchars(!empty($_POST["line_voicemail_pass"]) ? $_POST["line_voicemail_pass"] : (!empty($vm[4]) ? $vm[4] : "") );?>"
							title="<?php Translate::display(Translator::LINE_TITLE_VOICEMAIL_PASSWORD);?>" /><span class="req">&nbsp;</span>
						<div class="cleaner_micro">&nbsp;</div>
					</div>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CALL_FWD);?></span>
					<div id="radio_3">
						<input type="radio" id="radio5" name="line_fwd" value="<?php echo LineController::CONST_ON;?>"<?php echo ($callfwd === true) ? " checked=\"checked\"" : ""; ?>><label for="radio5">Ano</label> 
						<input type="radio" id="radio6" name="line_fwd" value="<?php echo LineController::CONST_OFF;?>"<?php echo ($callfwd === false) ? " checked=\"checked\"" : ""; ?>><label for="radio6">Ne</label>
					</div>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_NAT);?></span>
					<div id="radio_2">
						<input type="radio" id="radio3" name="line_nat" value="<?php echo LineController::CONST_ON;?>"<?php echo ($nat === true) ? " checked=\"checked\"" : ""; ?>><label for="radio3">Ano</label> 
						<input type="radio" id="radio4" name="line_nat" value="<?php echo LineController::CONST_OFF;?>"<?php echo ($nat === false) ? " checked=\"checked\"" : ""; ?>><label for="radio4">Ne</label>
					</div>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_WATCH_IP);?></span>
					<div id="radio_4">
						<input type="radio" id="radio7" name="line_permit_ip" value="<?php echo LineController::CONST_ON;?>"<?php echo ($permit === true) ? " checked=\"checked\"" : ""; ?>><label for="radio7">Ano</label> 
						<input type="radio" id="radio8" name="line_permit_ip" value="<?php echo LineController::CONST_OFF;?>"<?php echo ($permit === false) ? " checked=\"checked\"" : ""; ?>><label for="radio8">Ne</label>
					</div>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div id="permit_ip_box"<?php echo ($permit === true) ? " style=\"display:block;\"" : " style=\"display:none;\""; ?>>
					<span class="title"><?php Translate::display(Translator::LINE_IP);?></span> <input type="text" name="line_ip" class="value"
						value="<?php echo htmlspecialchars(!empty($_POST["line_ip"]) ? $_POST["line_ip"] : (!empty($out[17]) ? substr($out[17], 0, strrpos($out[17],"/")) : "") );?>"
						title="<?php Translate::display(Translator::LINE_TITLE_IP);?>" /> <span class="req">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div id="permit_sm_box"<?php echo ($permit === true) ? " style=\"display:block;\"" : " style=\"display:none;\""; ?>>
					<span class="title"><?php Translate::display(Translator::LINE_SM);?></span> <input type="text" name="line_sm" class="value"
						value="<?php echo htmlspecialchars(!empty($_POST["line_sm"]) ? $_POST["line_sm"] : (!empty($out[17]) ? substr($out[17], strrpos($out[17],"/")+1, strlen($out[17])) : "") );?>"
						title="<?php Translate::display(Translator::LINE_TITLE_SM);?>" /> <span class="req">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div class="cleaner_micro">&nbsp;</div>
				<div class="btn_row">
					<?php System::printAuthInput();?>
					<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn">
					<div class="cleaner_micro">&nbsp;</div>
				</div>
			</form>
		</div>
		<div id="tab-3">
			<form class="line_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded" id="line_edit_tab_3">
				<?php if($has_pstn !== true){?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_PSTN_AVAILABLE);?></span> 
					<select class="value" name="selected_pstn_number"><?php echo $pstn;?></select>
					<span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span class="help" title="">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div class="cleaner_micro">&nbsp;</div>
				<div class="btn_row">
					<?php System::printAuthInput();?>
					<input type="submit" value="<?php Translate::display(Translator::BTN_ASSIGN);?>" class="send_btn" />
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php } else {?>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_PSTN_ASSIGNED);?></span> 
					<span class="value"><?php echo htmlspecialchars(substr($registered_pstn[2], 6));?></span>
					<span class="req">&nbsp;</span><span class="help" title="">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div class="cleaner_micro">&nbsp;</div>
				<div class="btn_row">
					<?php System::printAuthInput();?>
					<input type="hidden" name="confirm_pstncancel" value="yes" />
					<input type="submit" value="<?php Translate::display(Translator::BTN_CANCEL);?>" class="send_btn" />
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<?php }?>
			</form>
		</div>
		<div id="tab-4">
			<form class="line_edit" action="" method="post" enctype="application/x-www-form-urlencoded" id="line_edit_tab_4">
				<div class="server_info_header"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE);?></div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_IP);?></span><span class="value"><?php echo htmlspecialchars(!empty($out[24]) ? $out[24] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_PORT);?></span><span class="value" ><?php echo htmlspecialchars(!empty($out[18]) ? $out[18] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_UA);?></span><span class="value" title=""><?php echo htmlspecialchars(!empty($out[27]) ? $out[27] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_LAT);?></span><span class="value"><?php echo htmlspecialchars(!empty($out[26]) ? $out[26] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_REGSEC);?></span><span class="value"><?php echo htmlspecialchars(!empty($out[23]) ? $out[23] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_CONNECTED_DEVICE_FULLCONTACT);?></span><span class="value"><?php echo htmlspecialchars(!empty($out[11]) ? $out[11] : "");?></span>
					<span class="req">&nbsp;</span><span class="help">&nbsp;</span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div class="cleaner_micro">&nbsp;</div>
			</form>
		</div>
		<div id="tab-5">
			<form class="line_edit" action="<?php echo htmlspecialchars($a);?>" method="post" enctype="application/x-www-form-urlencoded" id="line_edit_tab_5">
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_SECRET);?></span> <input type="password" name="line_secret_1" class="value" value="" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_SECRET);?>"><?php Translate::display(Translator::LINE_HELP_SECRET);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div>
					<span class="title"><?php Translate::display(Translator::LINE_SECRET_AGAIN);?></span> <input type="password" name="line_secret_2" class="value" value="" /> <span class="req" title="<?php Translate::display(Translator::REQUIRED);?>">*</span><span
						class="help" title="<?php Translate::display(Translator::LINE_TITLE_SECRET_AGAIN);?>"><?php Translate::display(Translator::LINE_HELP_SECRET_AGAIN);?></span>
					<div class="cleaner_micro">&nbsp;</div>
				</div>
				<div class="cleaner_micro">&nbsp;</div>
				<div class="btn_row">
					<?php System::printAuthInput();?>
					<input type="submit" value="<?php Translate::display(Translator::BTN_SAVE);?>" class="send_btn" />
					<div class="cleaner_micro">&nbsp;</div>
				</div>
			</form>
		</div>
		<?php }?>

		<script type="text/javascript">
		<!--		
		var spinner = $("#spinner").spinner({
			spin: function(event, ui) {
				if (ui.value > 9999) {
					$(this).spinner("value", 9999);
					return false;
				} else if (ui.value < 1000) {
					$(this).spinner("value", 1000);
					return false;
				} 
			},
			create: function(event, ui) {
				if (ui.value == undefined || ui.value == "") {
					$(this).spinner("value", 1000);
					return false;
				}
			}
		});
		$("#tabs").tabs();
		$("#radio_1, #radio_2, #radio_3, #radio_4").buttonset();
		$("input[type=radio][name=line_permit_ip]").change(function() {
			if (this.value == 'on') {
				$("#permit_ip_box").show();
				$("#permit_sm_box").show();
		    }
		    else {
		    	$("#permit_ip_box").hide();
		    	$("#permit_sm_box").hide();
		    }
		});
		$("input[type=radio][name=line_voicemail]").change(function() {
			if (this.value == 'on') {
				$("#voicemail_pass_box").show();
		    }
		    else {
		    	$("#voicemail_pass_box").hide();
		    }
		});
		//-->
		</script>
		<script type="text/javascript">
		<!-- 
			VALIDATOR = "line"; 
		//-->
		</script>
		<script type="text/javascript" src="<?php echo sprintf("%s%s", Config::SITE_PATH, "gui/scripts/validator.js")?>"></script>
	</div>
</div>
<?php }?>
<?php if($_GET["action"] == "delete"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_LINE_DELETE);?></h1>
	<a href="line/lst/"><?php Translate::display(Translator::PAGE_NAME_LINE_LIST);?></a>
</div>
<div>
	<form id="line_delete" action="<?php echo htmlspecialchars(sprintf("%s/%s/%d/", $_GET["route"], $_GET["action"], $_GET["id"]));?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="value"><?php echo htmlspecialchars(sprintf(Translate::get(Translator::LINE_CONFIRM_DELETE), $out[2], $out[3]));?></span>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
		<div class="btn_row">
			<?php System::printAuthInput();?>
			<input type="hidden" name="confirm_delete" value="yes" />
			<input type="submit" value="<?php Translate::display(Translator::BTN_DELETE);?>" class="send_btn" />
			<a href="line/lst/"><?php Translate::display(Translator::BTN_BACK);?></a>
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
</div>
<?php }?>
<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_LINE_LIST);?></h1>
	<a href="line/create/"><?php Translate::display(Translator::PAGE_NAME_LINE_ADD);?></a>
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

<?php /* end of loggedin user*/ }?>