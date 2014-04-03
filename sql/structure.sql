-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Čtv 03. dub 2014, 14:45
-- Verze serveru: 5.6.12
-- Verze PHP: 5.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `asterisk_admin`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `cdr`
--

CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clid` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `src` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `dst` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `dcontext` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `channel` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `dstchannel` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `lastapp` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `lastdata` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `userfield` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `uniqueid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `linkedid` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sequence` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `peeraccount` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `extensions`
--

CREATE TABLE IF NOT EXISTS `extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `exten` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `app` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `appdata` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `context` (`context`,`exten`,`priority`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `meetme`
--

CREATE TABLE IF NOT EXISTS `meetme` (
  `confno` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `username` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `domain` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pin` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `adminpin` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `members` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`confno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `queue_member_table`
--

CREATE TABLE IF NOT EXISTS `queue_member_table` (
  `uniqueid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `membername` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `queue_name` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `interface` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `penalty` int(11) DEFAULT NULL,
  `paused` int(11) DEFAULT NULL,
  PRIMARY KEY (`uniqueid`),
  UNIQUE KEY `queue_interface` (`queue_name`,`interface`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `queue_table`
--

CREATE TABLE IF NOT EXISTS `queue_table` (
  `name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `musiconhold` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `announce` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `context` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `monitor_join` tinyint(1) DEFAULT NULL,
  `monitor_format` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_youarenext` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_thereare` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_callswaiting` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_holdtime` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_minutes` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_seconds` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_lessthan` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_thankyou` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `queue_reporthold` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `announce_frequency` int(11) DEFAULT NULL,
  `announce_round_seconds` int(11) DEFAULT NULL,
  `announce_holdtime` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `retry` int(11) DEFAULT NULL,
  `wrapuptime` int(11) DEFAULT NULL,
  `maxlen` int(11) DEFAULT NULL,
  `servicelevel` int(11) DEFAULT NULL,
  `strategy` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `joinempty` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `leavewhenempty` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `eventmemberstatus` tinyint(1) DEFAULT NULL,
  `eventwhencalled` tinyint(1) DEFAULT NULL,
  `reportholdtime` tinyint(1) DEFAULT NULL,
  `memberdelay` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `timeoutrestart` tinyint(1) DEFAULT NULL,
  `periodic_announce` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `periodic_announce_frequency` int(11) DEFAULT NULL,
  `ringinuse` tinyint(1) DEFAULT NULL,
  `setinterfacevar` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `sip`
--

CREATE TABLE IF NOT EXISTS `sip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `callerid` varchar(80) COLLATE utf8_czech_ci DEFAULT NULL,
  `defaultuser` varchar(80) CHARACTER SET utf8 NOT NULL,
  `regexten` varchar(80) CHARACTER SET utf8 NOT NULL,
  `secret` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `mailbox` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `accountcode` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `context` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `amaflags` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `callgroup` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `canreinvite` char(3) CHARACTER SET utf8 DEFAULT 'yes',
  `defaultip` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `dtmfmode` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `fromuser` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `fromdomain` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `fullcontact` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `host` varchar(31) CHARACTER SET utf8 NOT NULL,
  `insecure` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `language` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `md5secret` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `nat` varchar(5) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `deny` varchar(95) CHARACTER SET utf8 DEFAULT NULL,
  `permit` varchar(95) CHARACTER SET utf8 DEFAULT NULL,
  `mask` varchar(95) CHARACTER SET utf8 DEFAULT NULL,
  `pickupgroup` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `port` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `qualify` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `restrictcid` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `rtptimeout` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `rtpholdtimeout` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT 'friend',
  `disallow` varchar(100) CHARACTER SET utf8 DEFAULT 'all',
  `allow` varchar(100) CHARACTER SET utf8 DEFAULT 'g729;ilbc;gsm;ulaw;alaw',
  `musiconhold` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `regseconds` int(11) NOT NULL DEFAULT '0',
  `ipaddr` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `cancallforward` char(3) CHARACTER SET utf8 DEFAULT 'yes',
  `lastms` int(11) NOT NULL DEFAULT '0',
  `useragent` char(255) CHARACTER SET utf8 DEFAULT NULL,
  `regserver` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `system_log_activity`
--

CREATE TABLE IF NOT EXISTS `system_log_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `system_log_internal`
--

CREATE TABLE IF NOT EXISTS `system_log_internal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `code` int(10) unsigned DEFAULT '0',
  `stack` text COLLATE utf8_czech_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `ucr`
--

CREATE TABLE IF NOT EXISTS `ucr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `token` char(64) COLLATE utf8_czech_ci NOT NULL,
  `valid_to` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `token_UNIQUE` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `password` char(64) COLLATE utf8_czech_ci NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_czech_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8_czech_ci DEFAULT NULL,
  `ts_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `renew_token` char(64) COLLATE utf8_czech_ci DEFAULT NULL,
  `renew_valid_to` timestamp NULL DEFAULT NULL,
  `language` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username_UNIQUE` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `voicemail_users`
--

CREATE TABLE IF NOT EXISTS `voicemail_users` (
  `uniqueid` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `context` varchar(50) CHARACTER SET utf8 NOT NULL,
  `mailbox` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `password` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `fullname` varchar(150) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `pager` varchar(50) CHARACTER SET utf8 NOT NULL,
  `tz` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'central',
  `attach` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `saycid` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `dialout` varchar(10) CHARACTER SET utf8 NOT NULL,
  `callback` varchar(10) CHARACTER SET utf8 NOT NULL,
  `review` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `operator` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `envelope` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `sayduration` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `saydurationm` tinyint(4) NOT NULL DEFAULT '1',
  `sendvoicemail` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `delete` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `nextaftercmd` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `forcename` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `forcegreetings` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `hidefromdir` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uniqueid`),
  UNIQUE KEY `mailbox_context` (`mailbox`,`context`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
