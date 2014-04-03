<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>

<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_CDR_LIST);?></h1>
</div>
<div>
	<form id="cdr_line" action="<?php echo htmlspecialchars(sprintf("%s/%s/", $_GET["route"], $_GET["action"]));?>" method="post" enctype="application/x-www-form-urlencoded">
		<div>
			<span class="title"><?php Translate::display(Translator::CHOOSE_LINE);?></span>
			<span><select name="select_cdr_line"><?php echo $out2;?></select></span>
			<input type="submit" value="<?php Translate::display(Translator::BTN_SEND);?>" class="send_btn" />
			<div class="cleaner_micro">&nbsp;</div>
		</div>
	</form>
</div>
<div><?php echo $out;?></div>
<script type="text/javascript">
<!--
$("#cdr_line select[name=select_cdr_line]").on("change", function() {
    $("#cdr_line").submit();
});
$("#cdr_line input[type=submit]").hide();
//-->
</script>
<?php }?>

<?php /* end of loggedin user*/ }?>