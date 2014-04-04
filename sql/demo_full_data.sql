-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2014 at 04:07 PM
-- Server version: 5.6.12
-- PHP Version: 5.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aa_demo`
--
CREATE DATABASE IF NOT EXISTS `aa_demo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `aa_demo`;

-- --------------------------------------------------------

--
-- Table structure for table `cdr`
--

CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `userfield` varchar(255) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `linkedid` varchar(32) NOT NULL DEFAULT '',
  `sequence` varchar(32) NOT NULL DEFAULT '',
  `peeraccount` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

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
  `context` varchar(20) NOT NULL DEFAULT '',
  `exten` varchar(30) NOT NULL DEFAULT '',
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `app` varchar(20) NOT NULL DEFAULT '',
  `appdata` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `context` (`context`,`exten`,`priority`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

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
(50, 'outgoing', '_00420[23456789]XXXXXXXX', 4, 'Hangup', ''),
(51, 'incoming', '_00420488588286', 1, 'Dial', 'SIP/1001'),
(52, 'outgoing', '_00420[23456789]XXXXXXXX', 5, 'GotoIf', '$["${CALLERID(num)}" == "1001"]?6:8'),
(53, 'outgoing', '_00420[23456789]XXXXXXXX', 6, 'Set', 'CALLERID(num)=00420488588286'),
(54, 'outgoing', '_00420[23456789]XXXXXXXX', 7, 'Goto', '3'),
(55, 'incoming', '_00420499599759', 1, 'Dial', 'SIP/1005'),
(55, 'outgoing', '_00420[23456789]XXXXXXXX', 8, 'GotoIf', '$["${CALLERID(num)}" == "1005"]?9:11'),
(56, 'outgoing', '_00420[23456789]XXXXXXXX', 9, 'Set', 'CALLERID(num)=00420499599759'),
(57, 'outgoing', '_00420[23456789]XXXXXXXX', 10, 'Goto', '3');

-- --------------------------------------------------------

--
-- Table structure for table `meetme`
--

CREATE TABLE IF NOT EXISTS `meetme` (
  `confno` varchar(80) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `domain` varchar(128) NOT NULL DEFAULT '',
  `pin` varchar(20) DEFAULT NULL,
  `adminpin` varchar(20) DEFAULT NULL,
  `members` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`confno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `queue_member_table`
--

CREATE TABLE IF NOT EXISTS `queue_member_table` (
  `uniqueid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `membername` varchar(40) DEFAULT NULL,
  `queue_name` varchar(128) DEFAULT NULL,
  `interface` varchar(128) DEFAULT NULL,
  `penalty` int(11) DEFAULT NULL,
  `paused` int(11) DEFAULT NULL,
  PRIMARY KEY (`uniqueid`),
  UNIQUE KEY `queue_interface` (`queue_name`,`interface`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `queue_table`
--

CREATE TABLE IF NOT EXISTS `queue_table` (
  `name` varchar(128) NOT NULL,
  `musiconhold` varchar(128) DEFAULT NULL,
  `announce` varchar(128) DEFAULT NULL,
  `context` varchar(128) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `monitor_join` tinyint(1) DEFAULT NULL,
  `monitor_format` varchar(128) DEFAULT NULL,
  `queue_youarenext` varchar(128) DEFAULT NULL,
  `queue_thereare` varchar(128) DEFAULT NULL,
  `queue_callswaiting` varchar(128) DEFAULT NULL,
  `queue_holdtime` varchar(128) DEFAULT NULL,
  `queue_minutes` varchar(128) DEFAULT NULL,
  `queue_seconds` varchar(128) DEFAULT NULL,
  `queue_lessthan` varchar(128) DEFAULT NULL,
  `queue_thankyou` varchar(128) DEFAULT NULL,
  `queue_reporthold` varchar(128) DEFAULT NULL,
  `announce_frequency` int(11) DEFAULT NULL,
  `announce_round_seconds` int(11) DEFAULT NULL,
  `announce_holdtime` varchar(128) DEFAULT NULL,
  `retry` int(11) DEFAULT NULL,
  `wrapuptime` int(11) DEFAULT NULL,
  `maxlen` int(11) DEFAULT NULL,
  `servicelevel` int(11) DEFAULT NULL,
  `strategy` varchar(128) DEFAULT NULL,
  `joinempty` varchar(128) DEFAULT NULL,
  `leavewhenempty` varchar(128) DEFAULT NULL,
  `eventmemberstatus` tinyint(1) DEFAULT NULL,
  `eventwhencalled` tinyint(1) DEFAULT NULL,
  `reportholdtime` tinyint(1) DEFAULT NULL,
  `memberdelay` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `timeoutrestart` tinyint(1) DEFAULT NULL,
  `periodic_announce` varchar(50) DEFAULT NULL,
  `periodic_announce_frequency` int(11) DEFAULT NULL,
  `ringinuse` tinyint(1) DEFAULT NULL,
  `setinterfacevar` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sip`
--

CREATE TABLE IF NOT EXISTS `sip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `callerid` varchar(80) DEFAULT NULL,
  `defaultuser` varchar(80) NOT NULL,
  `regexten` varchar(80) NOT NULL,
  `secret` varchar(80) DEFAULT NULL,
  `mailbox` varchar(50) DEFAULT NULL,
  `accountcode` varchar(20) DEFAULT NULL,
  `context` varchar(80) DEFAULT NULL,
  `amaflags` varchar(7) DEFAULT NULL,
  `callgroup` varchar(10) DEFAULT NULL,
  `canreinvite` char(3) DEFAULT 'yes',
  `defaultip` varchar(15) DEFAULT NULL,
  `dtmfmode` varchar(7) DEFAULT NULL,
  `fromuser` varchar(80) DEFAULT NULL,
  `fromdomain` varchar(80) DEFAULT NULL,
  `fullcontact` varchar(80) DEFAULT NULL,
  `host` varchar(31) NOT NULL,
  `insecure` varchar(25) DEFAULT NULL,
  `language` char(2) DEFAULT NULL,
  `md5secret` varchar(80) DEFAULT NULL,
  `nat` varchar(5) NOT NULL DEFAULT 'no',
  `deny` varchar(95) DEFAULT NULL,
  `permit` varchar(95) DEFAULT NULL,
  `mask` varchar(95) DEFAULT NULL,
  `pickupgroup` varchar(10) DEFAULT NULL,
  `port` varchar(5) DEFAULT NULL,
  `qualify` char(3) DEFAULT NULL,
  `restrictcid` char(1) DEFAULT NULL,
  `rtptimeout` char(3) DEFAULT NULL,
  `rtpholdtimeout` char(3) DEFAULT NULL,
  `type` varchar(6) NOT NULL DEFAULT 'friend',
  `disallow` varchar(100) DEFAULT 'all',
  `allow` varchar(100) DEFAULT 'g729;ilbc;gsm;ulaw;alaw',
  `musiconhold` varchar(100) DEFAULT NULL,
  `regseconds` int(11) NOT NULL DEFAULT '0',
  `ipaddr` varchar(45) DEFAULT NULL,
  `cancallforward` char(3) DEFAULT 'yes',
  `lastms` int(11) NOT NULL DEFAULT '0',
  `useragent` char(255) DEFAULT NULL,
  `regserver` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sip`
--

INSERT INTO `sip` (`id`, `uid`, `name`, `callerid`, `defaultuser`, `regexten`, `secret`, `mailbox`, `accountcode`, `context`, `amaflags`, `callgroup`, `canreinvite`, `defaultip`, `dtmfmode`, `fromuser`, `fromdomain`, `fullcontact`, `host`, `insecure`, `language`, `md5secret`, `nat`, `deny`, `permit`, `mask`, `pickupgroup`, `port`, `qualify`, `restrictcid`, `rtptimeout`, `rtpholdtimeout`, `type`, `disallow`, `allow`, `musiconhold`, `regseconds`, `ipaddr`, `cancallforward`, `lastms`, `useragent`, `regserver`) VALUES
(1, 1, '1000', 'John', '1000', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1000', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, 'c9f0233ad06b3a5f6b9eec7254b6c5a7', 'no', NULL, '', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL),
(2, 1, '1006', 'John-work', '1006', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1006', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, '9fee9338854d1ca1de558510d521898e', 'no', NULL, '', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL),
(3, 2, '1001', 'Emily', '1001', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1001', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, 'c3037913f05d39f7fbfff7f7d91967a4', 'yes', NULL, '', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, '', NULL),
(4, 3, '1002', 'Sophie', '1002', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1002', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, 'aa1e57af7a4752d6ee0f4767d6989584', 'yes', '0.0.0.0/0.0.0.0', '192.168.0.15/255.255.255.0', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL),
(5, 4, '1003', 'Zoe', '1003', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1003', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, '713225b475f1271205011d5760e509db', 'no', NULL, '', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL),
(6, 5, '1004', 'Jack', '1004', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1004', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, 'f6011afbae8eb35130096958adb2ea2f', 'no', NULL, '', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL),
(7, 6, '1005', 'Ryan', '1005', '', '', NULL, NULL, 'internal', NULL, NULL, 'no', NULL, NULL, '1005', 'sip.sd2.cz', NULL, 'dynamic', NULL, NULL, '1e841634fa8333bf59dc69fa3fd60f08', 'yes', '0.0.0.0/0.0.0.0', '192.168.0.14/255.255.255.0', NULL, NULL, NULL, 'yes', NULL, NULL, NULL, 'friend', 'all', 'alaw;ulaw;g722;g729;gsm', NULL, 0, NULL, 'yes', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_log_activity`
--

CREATE TABLE IF NOT EXISTS `system_log_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `system_log_internal`
--

CREATE TABLE IF NOT EXISTS `system_log_internal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(50) DEFAULT NULL,
  `code` int(10) unsigned DEFAULT '0',
  `stack` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ucr`
--

CREATE TABLE IF NOT EXISTS `ucr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `token` char(64) NOT NULL,
  `valid_to` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_type` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `token_UNIQUE` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `ts_insert` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `renew_token` char(64) DEFAULT NULL,
  `renew_valid_to` timestamp NULL DEFAULT NULL,
  `language` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username_UNIQUE` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `email`, `password`, `type`, `status`, `first_name`, `last_name`, `phone`, `ts_insert`, `last_login`, `renew_token`, `renew_valid_to`, `language`) VALUES
(1, 'john@black.com', '875086c9fc3eedf9b9bbabf5b813745463d2f026c8f012252356d5cdd0932485', 1, 1, 'John', 'Black', '+420123654321', '2014-01-16 15:04:53', '2014-04-04 17:50:48', NULL, NULL, 1),
(2, 'emily@black.com', '854d0dc6d8417b37b02ef46b7586dd42231e787666ec6f2bd1f4feb1ee1565fa', 2, 1, 'Emily', 'Black', '', '2013-12-31 23:00:00', '2014-04-04 17:41:54', NULL, NULL, 1),
(3, 'sophie@black.com', '3ce64049c4ce4d5e8e9fdaaca3d94c6b9b7a0c1b8086beb52253ad64afcb7753', 2, 1, 'Sophie', 'Black', '', '2013-12-31 23:00:00', '2014-04-04 17:47:23', NULL, NULL, 1),
(4, 'zoe@black.com', '881749bd3662da0aba0e581d1cbc7575a254812c5e14a63fa43fac0b2b865b19', 2, 1, 'Zoe', 'Black', '', '2013-12-31 23:00:00', '2014-04-04 17:48:45', NULL, NULL, 1),
(5, 'jack@black.com', 'dd6083961774c9b536f1dd7c3c6ad049b0eeb85d76893f05975019479571f9f9', 2, 1, 'Jack', 'Black', '', '2013-12-31 23:00:00', '2014-04-04 17:49:12', NULL, NULL, 1),
(6, 'ryan@black.com', '96ec2e2865ab981cbd6aff71567d091359b793d82fd42a700dfa90197fd0ec06', 2, 1, 'Ryan', 'Black', '', '2013-12-31 23:00:00', '2014-04-04 17:49:40', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `voicemail_users`
--

CREATE TABLE IF NOT EXISTS `voicemail_users` (
  `uniqueid` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(11) NOT NULL DEFAULT '0',
  `context` varchar(50) NOT NULL,
  `mailbox` varchar(11) NOT NULL DEFAULT '0',
  `password` varchar(10) NOT NULL DEFAULT '0',
  `fullname` varchar(150) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pager` varchar(50) NOT NULL,
  `tz` varchar(10) NOT NULL DEFAULT 'central',
  `attach` varchar(4) NOT NULL DEFAULT 'yes',
  `saycid` varchar(4) NOT NULL DEFAULT 'yes',
  `dialout` varchar(10) NOT NULL,
  `callback` varchar(10) NOT NULL,
  `review` varchar(4) NOT NULL DEFAULT 'no',
  `operator` varchar(4) NOT NULL DEFAULT 'no',
  `envelope` varchar(4) NOT NULL DEFAULT 'no',
  `sayduration` varchar(4) NOT NULL DEFAULT 'no',
  `saydurationm` tinyint(4) NOT NULL DEFAULT '1',
  `sendvoicemail` varchar(4) NOT NULL DEFAULT 'no',
  `delete` varchar(4) NOT NULL DEFAULT 'no',
  `nextaftercmd` varchar(4) NOT NULL DEFAULT 'yes',
  `forcename` varchar(4) NOT NULL DEFAULT 'no',
  `forcegreetings` varchar(4) NOT NULL DEFAULT 'no',
  `hidefromdir` varchar(4) NOT NULL DEFAULT 'yes',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uniqueid`),
  UNIQUE KEY `mailbox_context` (`mailbox`,`context`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `voicemail_users`
--

INSERT INTO `voicemail_users` (`uniqueid`, `customer_id`, `context`, `mailbox`, `password`, `fullname`, `email`, `pager`, `tz`, `attach`, `saycid`, `dialout`, `callback`, `review`, `operator`, `envelope`, `sayduration`, `saydurationm`, `sendvoicemail`, `delete`, `nextaftercmd`, `forcename`, `forcegreetings`, `hidefromdir`, `stamp`) VALUES
(1, '1001', 'internal', '1001', '456789', '1001', '', '', 'central', 'yes', 'yes', '', '', 'no', 'no', 'no', 'no', 1, 'no', 'no', 'yes', 'no', 'no', 'yes', '2014-04-04 15:43:04'),
(2, '1002', 'internal', '1002', '99896', '1002', '', '', 'central', 'yes', 'yes', '', '', 'no', 'no', 'no', 'no', 1, 'no', 'no', 'yes', 'no', 'no', 'yes', '2014-04-04 15:48:01'),
(3, '1005', 'internal', '1005', '989898', '1005', '', '', 'central', 'yes', 'yes', '', '', 'no', 'no', 'no', 'no', 1, 'no', 'no', 'yes', 'no', 'no', 'yes', '2014-04-04 15:50:10');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
