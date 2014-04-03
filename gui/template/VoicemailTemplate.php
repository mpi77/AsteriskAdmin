<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>

<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_VOICEMAIL_LIST);?></h1>
</div>
<div>
	<form id="voicemail_line" action="<?php echo htmlspecialchars(sprintf("%s/%s/", $_GET["route"], $_GET["action"]));?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::CHOOSE_LINE);?></span>
			<span><select name="select_voicemail_line"><?php echo $out2;?></select></span>
			<input type="submit" value="<?php Translate::display(Translator::BTN_SEND);?>" class="send_btn" />
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
</div>
<div><?php echo $out;?></div>
<script type="text/javascript">
<!--
$("#voicemail_line select[name=select_voicemail_line]").on("change", function() {
    $("#voicemail_line").submit();
});
$("#voicemail_line input[type=submit]").hide();
$(".download a").button({
	icons: {
		primary: "ui-icon-arrowthickstop-1-s"
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