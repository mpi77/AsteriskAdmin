<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>

<?php if($_GET["action"] == "lst"){?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_PHONEBOOK_LIST);?></h1>
</div>
<div><?php echo $out;?></div>
<?php }?>

<?php /* end of loggedin user*/ }?>