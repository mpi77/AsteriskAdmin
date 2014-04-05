# AsteriskAdmin #

Web based application to manage Asterisk PBX. It is designed for small office or home office use where is typically needed simple managing tool for user accounts, internal and external phone lines, voicemails, etc. For more info look at wiki for this project.

## Requirements ##

- Asterisk PBX with realtime configuration
- database server (it is written for MySQL, recommended MySQL 5.6 and above)
- Apache web server with mod_rewrite (recommended Apache 2.2 and above)
- PHP 5.4 and above

## Install ##

- create database from scripts in ./sql directory
- copy all files to webroot directory
- modify ./app/Config.class.php to your values
- modify constant ASTERISK_VOICEMAIL_PATH in ./app/controller/VoicemailController.class.php
- modify constant SIP_REALM in ./app/controller/LineController.class.php

## License ##

The source code of AsteriskAdmin is licensed under BSD license. For more details look at file LICENSE.
