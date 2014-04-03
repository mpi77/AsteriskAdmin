<?php if(Acl::isLoggedin() === true){ /* loggedin user */?>
<div id="page_name">
	<h1><?php Translate::display(Translator::PAGE_NAME_INDEX);?></h1>
</div>
<div id="about_info">
	<div class="about_info_header"><?php Translate::display(Translator::INDEX_PBX_INFO);?></div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_EXTERNAL_NAME);?></span><span class="value"><?php echo htmlspecialchars(Config::SERVER_FQDN);?></span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_EXTERNAL_NAME);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_INTERNAL_NAME);?></span><span class="value"><?php echo htmlspecialchars(Config::SERVER_INTERNAL_IP);?></span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_INTERNAL_NAME);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_PORT);?></span><span class="value"><?php echo htmlspecialchars(Config::SERVER_PORT);?></span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_PORT);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_PROTOCOLS);?></span><span class="value"><?php echo htmlspecialchars(Config::SERVER_PROTOCOL);?></span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_PROTOCOLS);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div class="about_info_header"><?php Translate::display(Translator::INDEX_PBX_SPECIAL_LINE);?></div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_ECHO);?></span><span class="value">*0</span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_ECHO);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_TIME);?></span><span class="value">*1</span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_TIME);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_VOICEMAIL);?></span><span class="value">*11</span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_VOICEMAIL);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
	<div>
		<span class="title"><?php Translate::display(Translator::INDEX_PBX_WEATHER);?></span><span class="value">*17</span><span
			class="req">&nbsp;</span> <span class="help"><?php Translate::display(Translator::INDEX_PBX_HELP_WEATHER);?></span>
		<div class="cleaner_micro">&nbsp;</div>
	</div>
</div>
<?php /* end of loggedin user*/ } else {?>
<div id="page_name">
	<h1><?php echo htmlspecialchars(sprintf("%s %s",Translate::get(Translator::PAGE_NAME_INDEX_UNLOG), Config::SERVER_FQDN));?></h1>
</div>
<div><?php Translate::display(Translator::INDEX_LOGIN_FIRST);?></div>
<?php }?>