# ************************************************************
# Sequel Pro SQL dump
# Version 4529
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.10)
# Database: wxappr
# Generation Time: 2016-12-10 12:39:18 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table activities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activities`;

CREATE TABLE `activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `type` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `posts_id` int(10) unsigned DEFAULT NULL,
  `posts_replies_id` int(10) unsigned DEFAULT NULL,
  `questions_id` int(10) unsigned DEFAULT NULL,
  `questions_answers_id` int(10) unsigned DEFAULT NULL,
  `follow_user_id` int(10) unsigned DEFAULT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `users_id` (`users_id`),
  KEY `posts_id` (`posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table activity_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activity_notifications`;

CREATE TABLE `activity_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `posts_id` int(10) unsigned DEFAULT NULL,
  `posts_replies_id` int(10) unsigned DEFAULT NULL,
  `questions_id` int(10) unsigned DEFAULT NULL,
  `questions_answers_id` int(10) unsigned DEFAULT NULL,
  `type` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `users_origin_id` int(10) unsigned DEFAULT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  `was_read` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`,`was_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table email_confirmations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email_confirmations`;

CREATE TABLE `email_confirmations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `code` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` int(10) unsigned NOT NULL,
  `modifiedAt` int(10) unsigned DEFAULT NULL,
  `confirmed` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table failed_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_logins`;

CREATE TABLE `failed_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned DEFAULT NULL,
  `ipAddress` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempted` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table feeds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feeds`;

CREATE TABLE `feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodes_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `posts_id` int(10) unsigned DEFAULT NULL,
  `questions_id` int(10) unsigned DEFAULT NULL,
  `number_responses` int(11) unsigned DEFAULT '0',
  `created_at` int(10) unsigned DEFAULT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `feeds` WRITE;
/*!40000 ALTER TABLE `feeds` DISABLE KEYS */;

INSERT INTO `feeds` (`id`, `nodes_id`, `users_id`, `type`, `posts_id`, `questions_id`, `number_responses`, `created_at`, `modified_at`)
VALUES
	(1,1003,1,'P',1,NULL,NULL,1480554252,NULL),
	(2,1003,1,'P',2,NULL,NULL,1480590824,NULL),
	(3,1019,1,'P',3,NULL,NULL,1480590836,NULL),
	(4,1024,1,'P',4,NULL,NULL,1480590846,NULL),
	(5,1010,1,'P',5,NULL,NULL,1480590858,NULL),
	(6,1031,1,'P',6,NULL,NULL,1480590867,NULL),
	(7,1019,1,'P',7,NULL,NULL,1480590883,NULL),
	(8,1010,1,'P',8,NULL,NULL,1480590900,NULL),
	(9,1010,1,'P',9,NULL,NULL,1480590913,NULL),
	(10,1010,1,'P',10,NULL,NULL,1480590927,NULL),
	(11,1010,1,'P',11,NULL,NULL,1480590946,NULL),
	(12,1010,1,'P',12,NULL,NULL,1480590960,NULL),
	(13,1024,1,'P',13,NULL,NULL,1480590968,NULL),
	(14,1003,1,'P',14,NULL,NULL,1480590974,NULL),
	(15,1003,1,'P',15,NULL,NULL,1480590983,NULL),
	(16,1001,1,'P',16,NULL,NULL,1480649040,NULL),
	(17,1001,1,'P',17,NULL,8,1480687159,1481008216),
	(18,1001,1,'P',18,NULL,NULL,1480687335,NULL),
	(19,1001,1,'P',19,NULL,NULL,1480687644,NULL),
	(20,1003,1,'P',20,NULL,NULL,1480687670,NULL),
	(21,1015,2,'P',21,NULL,NULL,1480687740,NULL),
	(22,1001,1,'P',22,NULL,NULL,1480736979,NULL),
	(23,1001,1,'P',23,NULL,NULL,1480737938,NULL),
	(24,1001,1,'P',24,NULL,NULL,1480737948,NULL),
	(25,1001,1,'P',25,NULL,NULL,1480756637,NULL),
	(26,1001,1,'P',26,NULL,NULL,1480760896,NULL),
	(27,1024,1,'P',27,NULL,NULL,1480760914,NULL),
	(28,1001,1,'P',28,NULL,NULL,1480760955,NULL),
	(29,1001,1,'P',29,NULL,NULL,1480761088,NULL),
	(30,1009,1,'P',30,NULL,NULL,1480761219,NULL),
	(31,1019,1,'Q',NULL,1,NULL,1480554484,NULL),
	(32,1003,1,'Q',NULL,2,NULL,1480575601,NULL),
	(33,1001,1,'Q',NULL,3,NULL,1480687296,NULL),
	(34,1001,1,'Q',NULL,4,NULL,1480741036,NULL),
	(35,1025,1,'Q',NULL,5,NULL,1480760924,NULL),
	(36,1024,1,'Q',NULL,6,NULL,1480760934,NULL),
	(37,1001,1,'Q',NULL,7,NULL,1480760941,NULL),
	(38,1001,1,'Q',NULL,8,NULL,1480761050,NULL),
	(39,1001,1,'Q',NULL,9,NULL,1480761080,NULL),
	(40,1001,1,'Q',NULL,10,NULL,1480761096,NULL),
	(41,1009,1,'Q',NULL,11,NULL,1480761231,NULL),
	(42,1001,2,'Q',NULL,12,2,1480764535,1481013286),
	(43,1001,1,'Q',NULL,13,2,1480764716,1481008077),
	(44,1001,1,'Q',NULL,14,1,1481007668,1481013336),
	(45,1004,2,'Q',NULL,15,0,1481007695,1481007721),
	(46,1001,3,'Q',NULL,16,1,1481017200,1481018076),
	(47,1001,1,'P',31,NULL,0,1481027400,1481027400),
	(48,1001,1,'P',32,NULL,0,1481081132,1481081132),
	(49,1004,1,'P',33,NULL,0,1481081505,1481081505),
	(50,1027,1,'P',34,NULL,0,1481081518,1481081518),
	(51,1037,1,'P',35,NULL,0,1481081848,1481081848),
	(52,1004,1,'Q',NULL,17,0,1481081852,1481081852),
	(53,1004,1,'Q',NULL,18,0,1481081870,1481081870),
	(54,1004,1,'P',36,NULL,5,1481093079,1481093576),
	(55,1003,1,'P',37,NULL,1,1481094325,1481207908),
	(56,1033,1,'P',38,NULL,12,1481095188,1481207923),
	(57,1004,1,'P',39,NULL,0,1481163815,1481163815),
	(58,1004,1,'P',40,NULL,1,1481163857,1481177185),
	(59,1004,1,'P',41,NULL,0,1481164628,1481164628),
	(60,1032,1,'Q',NULL,19,1,1481177220,1481207894),
	(61,1004,1,'P',42,NULL,0,1481177350,1481177350),
	(62,1020,1,'P',43,NULL,0,1481178226,1481207860),
	(63,1004,1,'P',44,NULL,1,1481178685,1481207402),
	(64,1005,1,'Q',NULL,20,0,1481183378,1481207876),
	(65,1001,1,'Q',NULL,21,0,1481207024,1481207024),
	(66,1004,1,'Q',NULL,22,1,1481259601,1481288098),
	(67,1004,1,'Q',NULL,23,1,1481293852,1481294208),
	(68,1001,2,'Q',NULL,24,1,1481298426,1481298444);

/*!40000 ALTER TABLE `feeds` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table login_changes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_changes`;

CREATE TABLE `login_changes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `ipAddress` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userAgent` varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `createdAt` int(10) unsigned NOT NULL,
  `modifiedAt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table nickname_changes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nickname_changes`;

CREATE TABLE `nickname_changes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `ipAddress` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userAgent` varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `createdAt` int(10) unsigned NOT NULL,
  `modifiedAt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table nodes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nodes`;

CREATE TABLE `nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT '0',
  `creator_id` int(10) unsigned NOT NULL,
  `name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_version` tinyint(4) unsigned DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci,
  `wiki` text COLLATE utf8mb4_unicode_ci,
  `number_questions` int(10) unsigned DEFAULT '0',
  `number_posts` int(10) unsigned DEFAULT '0',
  `number_followers` int(10) unsigned DEFAULT '0',
  `created_at` int(10) unsigned DEFAULT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table nodes_managers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nodes_managers`;

CREATE TABLE `nodes_managers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodes_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nodes_id` (`nodes_id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `posts_id` int(10) unsigned NOT NULL,
  `posts_replies_id` int(10) unsigned DEFAULT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  `modified_at` int(18) unsigned DEFAULT NULL,
  `message_id` char(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `posts_id` (`posts_id`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table notifications_bounces
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications_bounces`;

CREATE TABLE `notifications_bounces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diagnostic` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `reported` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`,`reported`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table password_changes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_changes`;

CREATE TABLE `password_changes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `ipAddress` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userAgent` varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `createdAt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rolesId` int(10) unsigned NOT NULL,
  `resource` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rolesId` (`rolesId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `nodes_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `number_views` int(3) unsigned NOT NULL,
  `number_replies` int(3) unsigned NOT NULL,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `votes_down` int(10) unsigned DEFAULT NULL,
  `sticked` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `created_at` int(18) unsigned DEFAULT NULL,
  `modified_at` int(18) unsigned DEFAULT NULL,
  `edited_at` int(18) unsigned DEFAULT NULL,
  `status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'A',
  `locked` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `deleted` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `title` (`title`),
  KEY `number_replies` (`number_replies`),
  KEY `modified_at` (`modified_at`),
  KEY `created_at` (`created_at`),
  KEY `sticked` (`sticked`,`created_at`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='CREATE TABLE `posts` (\n  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,\n  `users_id` int(10) unsigned NOT NULL,\n  `nodes_id` int(10) unsigned DEFAULT NULL,\n  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '''',\n  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,\n  `content` text COLLATE utf8mb4_unicode_ci,\n  `number_views` int(3) unsigned NOT NULL,\n  `number_replies` int(3) unsigned NOT NULL,\n  `votes_up` int(10) unsigned DEFAULT NULL,\n  `votes_down` int(10) unsigned DEFAULT NULL,\n  `sticked` char(1) COLLATE utf8mb4_unicode_ci DEFAULT ''N'',\n  `created_at` int(18) unsigned DEFAULT NULL,\n  `modified_at` int(18) unsigned DEFAULT NULL,\n  `edited_at` int(18) unsigned DEFAULT NULL,\n  `status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT ''A'',\n  `locked` char(1) COLLATE utf8mb4_unicode_ci DEFAULT ''N'',\n  `deleted` int(3) DEFAULT ''0'',\n  PRIMARY KEY (`id`),\n  KEY `users_id` (`users_id`),\n  KEY `title` (`title`),\n  KEY `number_replies` (`number_replies`),\n  KEY `modified_at` (`modified_at`),\n  KEY `created_at` (`created_at`),\n  KEY `sticked` (`sticked`,`created_at`),\n  KEY `deleted` (`deleted`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';



# Dump of table posts_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_history`;

CREATE TABLE `posts_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `posts_id` (`posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_notifications`;

CREATE TABLE `posts_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `posts_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`,`posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table posts_poll_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_poll_options`;

CREATE TABLE `posts_poll_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_poll_options_post_id` (`posts_id`),
  CONSTRAINT `posts_poll_options_ibfk_1` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_poll_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_poll_votes`;

CREATE TABLE `posts_poll_votes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `options_id` bigint(20) unsigned NOT NULL,
  `posts_id` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_poll_votes_post_id_user_id` (`posts_id`,`users_id`),
  KEY `posts_poll_votes_user_id` (`users_id`),
  KEY `options_id` (`options_id`),
  CONSTRAINT `posts_poll_votes_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_poll_votes_ibfk_2` FOREIGN KEY (`options_id`) REFERENCES `posts_poll_options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_poll_votes_ibfk_3` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_replies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_replies`;

CREATE TABLE `posts_replies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `in_reply_to_id` int(10) unsigned DEFAULT NULL,
  `in_reply_to_user` int(11) unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` int(18) unsigned DEFAULT NULL,
  `modified_at` int(18) unsigned DEFAULT NULL,
  `edited_at` int(18) unsigned DEFAULT NULL,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `votes_down` int(10) unsigned DEFAULT NULL,
  `accepted` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`id`),
  KEY `posts_id` (`posts_id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='CREATE TABLE `topics_replies` (\n  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,\n  `topics_id` int(10) unsigned NOT NULL,\n  `users_id` int(10) unsigned NOT NULL,\n  `in_reply_to_id` int(10) unsigned DEFAULT ''0'',\n  `content` text COLLATE utf8mb4_unicode_ci,\n  `created_at` int(18) unsigned DEFAULT NULL,\n  `modified_at` int(18) unsigned DEFAULT NULL,\n  `edited_at` int(18) unsigned DEFAULT NULL,\n  `votes_up` int(10) unsigned DEFAULT NULL,\n  `votes_down` int(10) unsigned DEFAULT NULL,\n  PRIMARY KEY (`id`),\n  KEY `topics_id ` (`topics_id `),\n  KEY `users_id` (`users_id`)\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';



# Dump of table posts_replies_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_replies_history`;

CREATE TABLE `posts_replies_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_replies_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `posts_replies_id` (`posts_replies_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_replies_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_replies_votes`;

CREATE TABLE `posts_replies_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_replies_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `vote` int(3) NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_subscribers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_subscribers`;

CREATE TABLE `posts_subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_id` (`posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_views
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_views`;

CREATE TABLE `posts_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `ipaddress` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_id` (`posts_id`,`ipaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_votes`;

CREATE TABLE `posts_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `posts_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `vote` int(3) NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table questions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions`;

CREATE TABLE `questions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `nodes_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `number_views` int(4) unsigned NOT NULL,
  `number_answers` int(4) unsigned NOT NULL,
  `number_comments` int(4) DEFAULT NULL,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `votes_down` int(10) unsigned DEFAULT NULL,
  `sticked` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `status` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'A',
  `bounty` int(11) unsigned DEFAULT '0',
  `time_start` int(11) unsigned DEFAULT NULL,
  `time_end` int(11) unsigned DEFAULT NULL,
  `deleted` int(3) DEFAULT '0',
  `created_at` int(18) unsigned DEFAULT NULL,
  `modified_at` int(18) unsigned DEFAULT NULL,
  `edited_at` int(18) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `title` (`title`),
  KEY `number_answers` (`number_answers`),
  KEY `modified_at` (`modified_at`),
  KEY `created_at` (`created_at`),
  KEY `sticked` (`sticked`,`created_at`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_answers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_answers`;

CREATE TABLE `questions_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `questions_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `votes_down` int(10) unsigned DEFAULT NULL,
  `number_comments` int(11) unsigned DEFAULT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  `edited_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_id` (`questions_id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_answers_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_answers_comments`;

CREATE TABLE `questions_answers_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `questions_id` int(10) unsigned DEFAULT NULL,
  `questions_answers_id` int(10) unsigned DEFAULT NULL,
  `reply_to_users_id` int(10) unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_answers_id` (`questions_answers_id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_answers_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_answers_history`;

CREATE TABLE `questions_answers_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_answers_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `questions_answers_id` (`questions_answers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_answers_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_answers_votes`;

CREATE TABLE `questions_answers_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_answers_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `vote` int(3) NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_bounties
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_bounties`;

CREATE TABLE `questions_bounties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `questions_answers_id` int(10) unsigned NOT NULL,
  `points` int(10) unsigned NOT NULL,
  `created_at` int(18) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`,`questions_answers_id`),
  KEY `questions_id` (`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table questions_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_comments`;

CREATE TABLE `questions_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned DEFAULT NULL,
  `questions_id` int(10) unsigned NOT NULL,
  `reply_to_users_id` int(10) unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `votes_up` int(10) unsigned DEFAULT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_id` (`questions_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_history`;

CREATE TABLE `questions_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `questions_id` (`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_notifications`;

CREATE TABLE `questions_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `questions_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_id` (`users_id`,`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table questions_views
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_views`;

CREATE TABLE `questions_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned DEFAULT NULL,
  `ipaddress` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_id` (`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table questions_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questions_votes`;

CREATE TABLE `questions_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questions_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned NOT NULL,
  `vote` int(3) NOT NULL,
  `created_at` int(18) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table remember_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `remember_tokens`;

CREATE TABLE `remember_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `token` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userAgent` varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `createdAt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table reset_passwords
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reset_passwords`;

CREATE TABLE `reset_passwords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `code` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` int(10) unsigned NOT NULL,
  `modifiedAt` int(10) unsigned DEFAULT NULL,
  `reset` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table success_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `success_logins`;

CREATE TABLE `success_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `ipAddress` char(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userAgent` varchar(8192) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table topic_tracking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `topic_tracking`;

CREATE TABLE `topic_tracking` (
  `user_id` int(11) NOT NULL,
  `topic_id` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(72) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_login` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_source` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_version` tinyint(4) unsigned DEFAULT '0',
  `token_type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` char(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` int(18) unsigned DEFAULT NULL,
  `modified_at` int(18) unsigned DEFAULT NULL,
  `notifications` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `digest` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'Y',
  `timezone` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moderator` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `karma` int(11) DEFAULT NULL,
  `karma_date` date DEFAULT NULL,
  `today_income` int(11) DEFAULT '0',
  `today_spend` int(11) DEFAULT '0',
  `votes_receive` int(11) unsigned DEFAULT '0',
  `votes_send` int(11) unsigned DEFAULT '0',
  `number_followers` int(11) unsigned DEFAULT NULL,
  `number_followings` int(11) unsigned DEFAULT NULL,
  `banned` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `theme` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'D',
  `password` char(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rolesId` tinyint(10) unsigned DEFAULT NULL,
  `suspended` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` char(1) COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `access_token` (`access_token`),
  KEY `email` (`email`),
  KEY `karma` (`karma`),
  KEY `notifications` (`notifications`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users_activities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_activities`;

CREATE TABLE `users_activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `page_views` int(10) unsigned DEFAULT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table users_badges
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_badges`;

CREATE TABLE `users_badges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `badge` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code1` int(10) unsigned DEFAULT NULL,
  `code2` int(10) unsigned DEFAULT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`,`badge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users_followers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_followers`;

CREATE TABLE `users_followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `followers_id` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`),
  KEY `followers_id` (`followers_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table users_nodes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_nodes`;

CREATE TABLE `users_nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `nodes_id` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table users_social
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_social`;

CREATE TABLE `users_social` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(10) unsigned NOT NULL,
  `gender` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weibo` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gzhao` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zhihu` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` int(10) unsigned DEFAULT NULL,
  `modified_at` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
