/*
SQLyog Ultimate v9.51 
MySQL - 5.1.49-1ubuntu8 : Database - gstyle
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `gs_auth_assignment` */

DROP TABLE IF EXISTS `gs_auth_assignment`;

CREATE TABLE `gs_auth_assignment` (
  `itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(12) unsigned NOT NULL,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `fk_auth_assignment_auth_item_idx` (`itemname`),
  KEY `userid` (`userid`),
  CONSTRAINT `fk_auth_assignment_auth_item` FOREIGN KEY (`itemname`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_user` FOREIGN KEY (`userid`) REFERENCES `gs_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `gs_auth_item` */

DROP TABLE IF EXISTS `gs_auth_item`;

CREATE TABLE `gs_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `bizrule` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `gs_auth_item_child` */

DROP TABLE IF EXISTS `gs_auth_item_child`;

CREATE TABLE `gs_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `fk_auth_item_child_parent_auth_item_idx` (`parent`),
  KEY `fk_auth_item_child_child_auth_item_idx` (`child`),
  CONSTRAINT `fk_auth_item_child_child_auth_item` FOREIGN KEY (`child`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_child_parent_auth_item` FOREIGN KEY (`parent`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `gs_auth_log` */

DROP TABLE IF EXISTS `gs_auth_log`;

CREATE TABLE `gs_auth_log` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned DEFAULT NULL,
  `login` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` int(10) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_auth_log_user` (`user_id`),
  KEY `ip` (`ip`),
  KEY `success_time` (`success`,`time`),
  CONSTRAINT `fk_auth_log_user` FOREIGN KEY (`user_id`) REFERENCES `gs_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `gs_rights` */

DROP TABLE IF EXISTS `gs_rights`;

CREATE TABLE `gs_rights` (
  `itemname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `fk_rights_auth_item` FOREIGN KEY (`itemname`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `gs_user` */

DROP TABLE IF EXISTS `gs_user`;

CREATE TABLE `gs_user` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_type` (`email`),
  UNIQUE KEY `login` (`login`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
