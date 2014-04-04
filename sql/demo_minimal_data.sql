-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2014 at 01:48 PM
-- Server version: 5.6.12
-- PHP Version: 5.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `asterisk_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cdr`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `cdr`
--

INSERT INTO `cdr` (`id`, `calldate`, `clid`, `src`, `dst`, `dcontext`, `channel`, `dstchannel`, `lastapp`, `lastdata`, `duration`, `billsec`, `disposition`, `amaflags`, `accountcode`, `userfield`, `uniqueid`, `linkedid`, `sequence`, `peeraccount`) VALUES
(1, '2013-04-16 21:31:01', '"John" <1000>', '1000', '1001', 'internal', 'SIP/1000-00000000', 'SIP/1001-00000001', 'Dial', 'SIP/1001,30', 2, 0, 'NO ANSWER', 3, '', '', '1366140661.0', '1366140661.0', '0', ''),
(2, '2014-01-01 09:31:22', '"Emily" <1001>', '1001', '1000', 'internal', 'SIP/1001-00000018', 'SIP/1000-00000019', 'Dial', 'SIP/1000,30', 241, 237, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(3, '2014-01-01 09:36:28', '"Zoe" <1003>', '1003', '1005', 'internal', 'SIP/1003-00000018', 'SIP/1005-00000019', 'Dial', 'SIP/1005,30', 15, 0, 'NO ANSWER', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(4, '2014-01-01 09:37:12', '"Jack" <1004>', '1004', '1002', 'internal', 'SIP/1004-00000018', 'SIP/1002-00000019', 'Dial', 'SIP/1002,30', 15, 10, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(5, '2014-01-02 17:12:25', '"Jack" <1004>', '1004', '1002', 'internal', 'SIP/1004-00000018', 'SIP/1002-00000019', 'Dial', 'SIP/1002,30', 152, 123, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(6, '2014-01-05 09:51:42', '"John" <1000>', '1000', '1005', 'internal', 'SIP/1000-00000018', 'SIP/1005-00000019', 'Dial', 'SIP/1005,30', 155, 153, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(7, '2014-01-05 10:22:56', '"Zoe" <1003>', '1003', '1006', 'internal', 'SIP/1003-00000018', 'SIP/1006-00000019', 'Dial', 'SIP/1006,30', 345, 341, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(8, '2014-01-05 15:31:55', '"Sophie" <1002>', '1002', '1004', 'internal', 'SIP/1002-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 112, 110, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(9, '2014-01-07 10:31:52', '"Emily" <1001>', '1001', '1005', 'internal', 'SIP/1001-00000018', 'SIP/1005-00000019', 'Dial', 'SIP/1005,30', 178, 170, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(10, '2014-01-07 19:00:21', '"Sophie" <1002>', '1002', '1005', 'internal', 'SIP/1002-00000018', 'SIP/1005-00000019', 'Dial', 'SIP/1005,30', 14, 0, 'NO ANSWER', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(11, '2014-01-12 08:25:22', '"Ryan" <1005>', '1005', '1006', 'internal', 'SIP/1005-00000018', 'SIP/1006-00000019', 'Dial', 'SIP/1006,30', 58, 52, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(12, '2014-01-16 08:35:12', '"Jack" <1004>', '1004', '1005', 'internal', 'SIP/1004-00000018', 'SIP/1005-00000019', 'Dial', 'SIP/1005,30', 45, 36, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(14, '2014-01-16 08:35:33', '"Zoe" <1003>', '1003', '1004', 'internal', 'SIP/1003-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 5, 0, 'BUSY', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(15, '2014-01-16 09:11:12', '"Zoe" <1003>', '1003', '1004', 'internal', 'SIP/1003-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 20, 17, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(16, '2014-01-16 12:51:42', '"Emily" <1001>', '1001', '1000', 'internal', 'SIP/1001-00000018', 'SIP/1000-00000019', 'Dial', 'SIP/1000,30', 241, 237, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(17, '2014-01-16 17:22:21', '"Emily" <1001>', '1001', '1004', 'internal', 'SIP/1001-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 35, 32, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(18, '2014-01-16 17:22:41', '"Zoe" <1003>', '1003', '1004', 'internal', 'SIP/1003-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 7, 0, 'BUSY', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(19, '2014-01-18 07:51:52', '"John" <1000>', '1000', '1003', 'internal', 'SIP/1000-00000018', 'SIP/1003-00000019', 'Dial', 'SIP/1003,30', 12, 0, 'NO ANSWER', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(20, '2014-01-18 08:11:42', '"John" <1000>', '1000', '1003', 'internal', 'SIP/1000-00000018', 'SIP/1003-00000019', 'Dial', 'SIP/1003,30', 22, 15, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(21, '2014-01-19 10:10:12', '"Ryan" <1005>', '1005', '1006', 'internal', 'SIP/1005-00000018', 'SIP/1006-00000019', 'Dial', 'SIP/1006,30', 23, 19, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(22, '2014-01-19 15:33:25', '"Emily" <1001>', '1001', '1004', 'internal', 'SIP/1001-00000018', 'SIP/1004-00000019', 'Dial', 'SIP/1004,30', 43, 40, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(23, '2014-01-19 16:23:42', '737036097', '737036097', '1001', 'incoming', 'SIP/from-odorik-00000002', 'SIP/1001-00000019', 'Dial', 'SIP/1001,30', 42, 21, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(24, '2014-01-20 09:41:52', '"Sophie" <1002>', '1002', '1006', 'internal', 'SIP/1002-00000018', 'SIP/1006-00000019', 'Dial', 'SIP/1006,30', 32, 18, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(25, '2014-01-20 09:55:15', '737036097', '737036097', '1001', 'incoming', 'SIP/from-odorik-00000018', 'SIP/1001-00000019', 'Dial', 'SIP/1001,30', 18, 14, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(26, '2014-01-20 12:02:44', '"Ryan" <1005>', '1005', '737036097', 'outgoing', 'SIP/1005-00000018', 'SIP/to-odorik-00000019', 'Dial', 'SIP/737036097@to-odorik,30', 19, 11, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', ''),
(27, '2014-01-21 11:11:16', '"Ryan" <1005>', '1005', '737036097', 'outgoing', 'SIP/1005-00000018', 'SIP/to-odorik-00000019', 'Dial', 'SIP/737036097,30', 77, 75, 'ANSWERED', 3, '', '', '1393270282.24', '1393270282.24', '30', '');
-- --------------------------------------------------------

--
-- Table structure for table `extensions`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=51 ;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `context`, `exten`, `priority`, `app`, `appdata`) VALUES
(1, 'internal', '_ZXXX', 1, 'Dial', 'SIP/${EXTEN},30'),
(2, 'internal', '_ZXXX', 2, 'VoiceMail', '${EXTEN}@internal'),
(3, 'internal', '_ZXXX', 3, 'Hangup', ''),
(4, 'internal', '_*0', 1, 'Answer', ''),
(5, 'internal', '_*0', 2, 'Playback', 'demo-echotest'),
(6, 'internal', '_*0', 3, 'Echo', ''),
(7, 'internal', '_*0', 4, 'Hangup', ''),
(8, 'internal', '_*1', 1, 'SayUnixTime', '"ABdY ''digits/at'' IMp"'),
(9, 'internal', '_*1', 2, 'Hangup', ''),
(10, 'internal', '_*11', 1, 'VoiceMailMain', '${CALLERID(num)}@internal'),
(11, 'internal', '_*11', 2, 'Hangup', ''),
(12, 'internal', '_*17', 1, 'Goto', 'weather,s,1'),
(13, 'internal', '_1XX', 1, 'Dial', 'SIP/${EXTEN}@to-odorik'),
(14, 'internal', 'i', 1, 'Hangup', ''),
(15, 'weather', 's', 1, 'Answer', ''),
(16, 'weather', 's', 2, 'Background', 'for&weather&in-the&atlanta&press-1'),
(17, 'weather', 's', 3, 'Background', 'or&for&weather&in-the&boston&press-2'),
(18, 'weather', 's', 4, 'Background', 'or&for&weather&in-the&chicago&press-3'),
(19, 'weather', 's', 5, 'Background', 'or&for&weather&in-the&houston&press-4'),
(20, 'weather', 's', 6, 'Background', 'or&for&weather&in-the&iowa&press-5'),
(21, 'weather', 's', 7, 'WaitExten', '5'),
(22, 'weather', 's', 8, 'Goto', '2'),
(23, 'weather', 'i', 1, 'Playback', 'sorry'),
(24, 'weather', 'i', 2, 'Hangup', ''),
(25, 'weather', '_1', 1, 'Playback', 'wind&and'),
(26, 'weather', '_1', 2, 'SayNumber', '21'),
(27, 'weather', '_1', 3, 'Playback', 'celsius'),
(28, 'weather', '_1', 4, 'Hangup', ''),
(29, 'weather', '_2', 1, 'Playback', 'rain&and'),
(30, 'weather', '_2', 2, 'SayNumber', '22'),
(31, 'weather', '_2', 3, 'Playback', 'celsius'),
(32, 'weather', '_2', 4, 'Hangup', ''),
(33, 'weather', '_3', 1, 'Playback', 'wind&and'),
(34, 'weather', '_3', 2, 'SayNumber', '23'),
(35, 'weather', '_3', 3, 'Playback', 'celsius'),
(36, 'weather', '_3', 4, 'Hangup', ''),
(37, 'weather', '_4', 1, 'Playback', 'rain&and'),
(38, 'weather', '_4', 2, 'SayNumber', '21'),
(39, 'weather', '_4', 3, 'Playback', 'celsius'),
(40, 'weather', '_4', 4, 'Hangup', ''),
(41, 'weather', '_5', 1, 'Playback', 'wind&and'),
(42, 'weather', '_5', 2, 'SayNumber', '20'),
(43, 'weather', '_5', 3, 'Playback', 'celsius'),
(44, 'weather', '_5', 4, 'Hangup', ''),
(45, 'outgoing', 'i', 1, 'Hangup', ''),
(46, 'outgoing', '_[23456789]XXXXXXXX', 1, 'Goto', '00420${EXTEN},1'),
(47, 'outgoing', '_00420[23456789]XXXXXXXX', 1, 'Noop', '${CALLERID(all)}'),
(48, 'outgoing', '_00420[23456789]XXXXXXXX', 2, 'Goto', '5'),
(49, 'outgoing', '_00420[23456789]XXXXXXXX', 3, 'Dial', 'SIP/${EXTEN}@to-odorik'),
(50, 'outgoing', '_00420[23456789]XXXXXXXX', 4, 'Hangup', '');

-- --------------------------------------------------------

--
-- Table structure for table `meetme`
--

CREATE TABLE IF NOT EXISTS `meetme` (
  `confno` varchar(80) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `username` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `domain` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `pin` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `adminpin` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `members` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`confno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_member_table`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queue_table`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sip`
--

CREATE TABLE IF NOT EXISTS `sip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `callerid` varchar(80) COLLATE utf8_general_ci DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_log_activity`
--

CREATE TABLE IF NOT EXISTS `system_log_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_log_internal`
--

CREATE TABLE IF NOT EXISTS `system_log_internal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(50) COLLATE utf8_general_ci DEFAULT NULL,
  `code` int(10) unsigned DEFAULT '0',
  `stack` text COLLATE utf8_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ucr`
--

CREATE TABLE IF NOT EXISTS `ucr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `token` char(64) COLLATE utf8_general_ci NOT NULL,
  `valid_to` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `token_UNIQUE` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `password` char(64) COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(50) COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_general_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8_general_ci DEFAULT NULL,
  `ts_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `renew_token` char(64) COLLATE utf8_general_ci DEFAULT NULL,
  `renew_valid_to` timestamp NULL DEFAULT NULL,
  `language` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username_UNIQUE` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `email`, `password`, `type`, `status`, `first_name`, `last_name`, `phone`, `ts_insert`, `last_login`, `renew_token`, `renew_valid_to`, `language`) VALUES
(1, 'john@black.com', '875086c9fc3eedf9b9bbabf5b813745463d2f026c8f012252356d5cdd0932485', 1, 1, 'John', 'Black', '+420123654321', '2014-01-01 00:00:00', '2014-01-01 00:00:00', NULL, NULL, 2);
-- --------------------------------------------------------

--
-- Table structure for table `voicemail_users`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
