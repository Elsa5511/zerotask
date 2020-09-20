/*
SQLyog Ultimate v9.02 
MySQL - 5.1.69 : Database - vidum-apps_test2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vidum-apps_test2` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `vidum-apps_test2`;

/*Table structure for table `attachment` */

DROP TABLE IF EXISTS `attachment`;

CREATE TABLE `attachment` (
  `attachment_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `filename` varchar(45) DEFAULT NULL,
  `mimetype` varchar(45) DEFAULT NULL,
  `date_add` varchar(45) DEFAULT NULL,
  `date_update` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `attachment` */

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `iso_code` varchar(3) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`country_id`),
  KEY `country_iso_code` (`iso_code`)
) ENGINE=MyISAM AUTO_INCREMENT=245 DEFAULT CHARSET=utf8;

/*Data for the table `country` */

insert  into `country`(`country_id`,`name`,`iso_code`,`status`) values (1,'Deutschland','DE','active'),(2,'Austria','AT','active'),(3,'Belgium','BE','active'),(4,'Canada','CA','active'),(5,'China','CN','active'),(6,'España','ES','active'),(7,'Finland','FI','active'),(8,'France','FR','active'),(9,'Greece','GR','active'),(10,'Italia','IT','active'),(11,'Japan','JP','active'),(12,'Luxemburg','LU','active'),(13,'Nederland','NL','active'),(14,'Polska','PL','active'),(15,'Portugal','PT','active'),(16,'Czech Republic','CZ','active'),(17,'United Kingdom','GB','active'),(18,'Sweden','SE','active'),(19,'Switzerland','CH','active'),(20,'Denmark','DK','active'),(21,'USA','US','active'),(22,'HongKong','HK','active'),(23,'Norway','NO','active'),(24,'Australia','AU','active'),(25,'Singapore','SG','active'),(26,'Éire','IE','active'),(27,'New Zealand','NZ','active'),(28,'South Korea','KR','active'),(29,'מדינת ישראל','IL','active'),(30,'South Africa','ZA','active'),(31,'Nigeria','NG','active'),(32,'Ivory Coast','CI','active'),(33,'Togo','TG','active'),(34,'Bolivia','BO','active'),(35,'Mauritius','MU','active'),(143,'Hungary','HU','active'),(36,'Romania','RO','active'),(37,'Slovensko','SK','active'),(38,'Algeria','DZ','active'),(39,'American Samoa','AS','active'),(40,'Andorra','AD','active'),(41,'Angola','AO','active'),(42,'Anguilla','AI','active'),(43,'Antigua and Barbuda','AG','active'),(44,'Argentina','AR','active'),(45,'Armenia','AM','active'),(46,'Aruba','AW','active'),(47,'Azerbaijan','AZ','active'),(48,'Bahamas','BS','active'),(49,'Bahrain','BH','active'),(50,'Bangladesh','BD','active'),(51,'Barbados','BB','active'),(52,'Belarus','BY','active'),(53,'Belize','BZ','active'),(54,'Benin','BJ','active'),(55,'Bermuda','BM','active'),(56,'Bhutan','BT','active'),(57,'Botswana','BW','active'),(58,'Brazil','BR','active'),(59,'Brunei','BN','active'),(60,'Burkina Faso','BF','active'),(61,'Burma (Myanmar)','MM','active'),(62,'Burundi','BI','active'),(63,'Cambodia','KH','active'),(64,'Cameroon','CM','active'),(65,'Cape Verde','CV','active'),(66,'Central African Republic','CF','active'),(67,'Chad','TD','active'),(68,'Chile','CL','active'),(69,'Colombia','CO','active'),(70,'Comoros','KM','active'),(71,'Congo, Dem. Republic','CD','active'),(72,'Congo, Republic','CG','active'),(73,'Costa Rica','CR','active'),(74,'Croatia','HR','active'),(75,'Cuba','CU','active'),(76,'Cyprus','CY','active'),(77,'Djibouti','DJ','active'),(78,'Dominica','DM','active'),(79,'Dominican Republic','DO','active'),(80,'East Timor','TL','active'),(81,'Ecuador','EC','active'),(82,'Egypt','EG','active'),(83,'El Salvador','SV','active'),(84,'Equatorial Guinea','GQ','active'),(85,'Eritrea','ER','active'),(86,'Estonia','EE','active'),(87,'Ethiopia','ET','active'),(88,'Falkland Islands','FK','active'),(89,'Faroe Islands','FO','active'),(90,'Fiji','FJ','active'),(91,'Gabon','GA','active'),(92,'Gambia','GM','active'),(93,'Georgia','GE','active'),(94,'Ghana','GH','active'),(95,'Grenada','GD','active'),(96,'Greenland','GL','active'),(97,'Gibraltar','GI','active'),(98,'Guadeloupe','GP','active'),(99,'Guam','GU','active'),(100,'Guatemala','GT','active'),(101,'Guernsey','GG','active'),(102,'Guinea','GN','active'),(103,'Guinea-Bissau','GW','active'),(104,'Guyana','GY','active'),(105,'Haiti','HT','active'),(106,'Heard Island and McDonald Islands','HM','active'),(107,'Vatican City State','VA','active'),(108,'Honduras','HN','active'),(109,'Iceland','IS','active'),(110,'India','IN','active'),(111,'Indonesia','ID','active'),(112,'Iran','IR','active'),(113,'العراق','IQ','active'),(114,'Isle of Man','IM','active'),(115,'Jamaica','JM','active'),(116,'Jersey','JE','active'),(117,'Jordan','JO','active'),(118,'Kazakhstan','KZ','active'),(119,'Kenya','KE','active'),(120,'Kiribati','KI','active'),(121,'Korea, Dem. Republic of','KP','active'),(122,'Kuwait','KW','active'),(123,'Kyrgyzstan','KG','active'),(124,'Laos','LA','active'),(125,'Latvia','LV','active'),(126,'Lebanon','LB','active'),(127,'Lesotho','LS','active'),(128,'Liberia','LR','active'),(129,'Libya','LY','active'),(130,'Liechtenstein','LI','active'),(131,'Lithuania','LT','active'),(132,'Macau','MO','active'),(133,'Република Македонија','MK','active'),(134,'Madagascar','MG','active'),(135,'Malawi','MW','active'),(136,'Malaysia','MY','active'),(137,'Maldives','MV','active'),(138,'Mali','ML','active'),(139,'Malta','MT','active'),(140,'Marshall Islands','MH','active'),(141,'Martinique','MQ','active'),(142,'Mauritania','MR','active'),(144,'Mayotte','YT','active'),(145,'Mexico','MX','active'),(146,'Micronesia','FM','active'),(147,'Moldova','MD','active'),(148,'Monaco','MC','active'),(149,'Mongolia','MN','active'),(150,'Montenegro','ME','active'),(151,'Montserrat','MS','active'),(152,'Morocco','MA','active'),(153,'Mozambique','MZ','active'),(154,'Namibia','NA','active'),(155,'Nauru','NR','active'),(156,'Nepal','NP','active'),(157,'Netherlands Antilles','AN','active'),(158,'New Caledonia','NC','active'),(159,'Nicaragua','NI','active'),(160,'Niger','NE','active'),(161,'Niue','NU','active'),(162,'Norfolk Island','NF','active'),(163,'Northern Mariana Islands','MP','active'),(164,'Oman','OM','active'),(165,'Pakistan','PK','active'),(166,'Palau','PW','active'),(167,'Palestinian Territories','PS','active'),(168,'Panama','PA','active'),(169,'Papua New Guinea','PG','active'),(170,'Paraguay','PY','active'),(171,'Perú','PE','active'),(172,'Philippines','PH','active'),(173,'Pitcairn','PN','active'),(174,'Puerto Rico','PR','active'),(175,'Qatar','QA','active'),(176,'Réunion','RE','active'),(177,'Russian Federation','RU','active'),(178,'Rwanda','RW','active'),(179,'Saint Barthélemy','BL','active'),(180,'Saint Kitts and Nevis','KN','active'),(181,'Saint Lucia','LC','active'),(182,'Saint Martin','MF','active'),(183,'Saint Pierre and Miquelon','PM','active'),(184,'Saint Vincent and the Grenadines','VC','active'),(185,'Samoa','WS','active'),(186,'San Marino','SM','active'),(187,'São Tomé and Príncipe','ST','active'),(188,'Saudi Arabia','SA','active'),(189,'Senegal','SN','active'),(190,'Serbia','RS','active'),(191,'Seychelles','SC','active'),(192,'Sierra Leone','SL','active'),(193,'Slovenia','SI','active'),(194,'Solomon Islands','SB','active'),(195,'Somalia','SO','active'),(196,'South Georgia','GS','active'),(197,'Sri Lanka','LK','active'),(198,'Sudan','SD','active'),(199,'Suriname','SR','active'),(200,'Svalbard and Jan Mayen','SJ','active'),(201,'Swaziland','SZ','active'),(202,'Syria','SY','active'),(203,'Taiwan','TW','active'),(204,'Tajikistan','TJ','active'),(205,'Tanzania','TZ','active'),(206,'Thailand','TH','active'),(207,'Tokelau','TK','active'),(208,'Tonga','TO','active'),(209,'Trinidad and Tobago','TT','active'),(210,'Tunisia','TN','active'),(211,'Turkey','TR','active'),(212,'Turkmenistan','TM','active'),(213,'Turks and Caicos Islands','TC','active'),(214,'Tuvalu','TV','active'),(215,'Uganda','UG','active'),(216,'Ukraine','UA','active'),(217,'United Arab Emirates','AE','active'),(218,'Uruguay','UY','active'),(219,'Uzbekistan','UZ','active'),(220,'Vanuatu','VU','active'),(221,'Venezuela','VE','active'),(222,'Vietnam','VN','active'),(223,'Virgin Islands (British)','VG','active'),(224,'Virgin Islands (U.S.)','VI','active'),(225,'Wallis and Futuna','WF','active'),(226,'Western Sahara','EH','active'),(227,'Yemen','YE','active'),(228,'Zambia','ZM','active'),(229,'Zimbabwe','ZW','active'),(230,'Albania','AL','active'),(231,'Afghanistan','AF','active'),(232,'Antarctica','AQ','active'),(233,'Bosnia and Herzegovina','BA','active'),(234,'Bouvet Island','BV','active'),(235,'British Indian Ocean Territory','IO','active'),(236,'Bulgaria','BG','active'),(237,'Cayman Islands','KY','active'),(238,'Christmas Island','CX','active'),(239,'Cocos (Keeling) Islands','CC','active'),(240,'Cook Islands','CK','active'),(241,'French Guiana','GF','active'),(242,'French Polynesia','PF','active'),(243,'French Southern Territories','TF','active'),(244,'Åland Islands','AX','active');

/*Table structure for table `equipment` */

DROP TABLE IF EXISTS `equipment`;

CREATE TABLE `equipment` (
  `equipment_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `code` varchar(45) DEFAULT NULL,
  `description` text,
  `order` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'active',
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `feature_image` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`equipment_id`),
  KEY `fk_equipment_users1_idx` (`user_id`) USING BTREE,
  KEY `fk_equipment_organization1_idx` (`vendor_id`) USING BTREE,
  KEY `fk_equipment_organization2_idx` (`manufacturer_id`) USING BTREE,
  KEY `fk_equipment_organization3_idx` (`owner_id`) USING BTREE,
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipment` */

/*Table structure for table `equipment_stock` */

DROP TABLE IF EXISTS `equipment_stock`;

CREATE TABLE `equipment_stock` (
  `equipment_stock_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `code` varchar(45) NOT NULL,
  `date_add` varchar(45) NOT NULL,
  `date_update` varchar(45) NOT NULL,
  PRIMARY KEY (`equipment_stock_id`),
  KEY `fk_equipment_stock_equipment1_idx` (`equipment_id`),
  CONSTRAINT `equipment_stock_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipment_stock` */

/*Table structure for table `equipment_taxonomy` */

DROP TABLE IF EXISTS `equipment_taxonomy`;

CREATE TABLE `equipment_taxonomy` (
  `equipment_taxonomy_id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` text,
  `path` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `children_count` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipment_taxonomy` */

/*Table structure for table `equipment_taxonomy_relationship` */

DROP TABLE IF EXISTS `equipment_taxonomy_relationship`;

CREATE TABLE `equipment_taxonomy_relationship` (
  `equipment_id` int(11) NOT NULL,
  `equipment_taxonomy_id` int(11) NOT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_id`,`equipment_taxonomy_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment1_idx` (`equipment_id`) USING BTREE,
  KEY `fk_equipment_taxonomy_relationship_equipment_taxonomy1_idx` (`equipment_taxonomy_id`) USING BTREE,
  CONSTRAINT `equipment_taxonomy_relationship_ibfk_1` FOREIGN KEY (`equipment_taxonomy_id`) REFERENCES `equipment_taxonomy` (`equipment_taxonomy_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `equipment_taxonomy_relationship_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipment_taxonomy_relationship` */

/*Table structure for table `equipmentmeta` */

DROP TABLE IF EXISTS `equipmentmeta`;

CREATE TABLE `equipmentmeta` (
  `equipmentmeta_id` int(11) NOT NULL AUTO_INCREMENT,
  `equipment_id` int(11) NOT NULL,
  `key` varchar(50) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1 NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`equipmentmeta_id`),
  KEY `fk_equipment_equipmentmeta` (`equipment_id`),
  CONSTRAINT `equipmentmeta_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipmentmeta` */

/*Table structure for table `language` */

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isocode` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `language` */

insert  into `language`(`language_id`,`name`,`isocode`,`status`,`date_add`,`date_update`) values (1,'English','en','active','2013-09-03 23:43:38','2013-09-03 23:43:38'),(2,'Norwegian','no','active','0000-00-00 00:00:00','0000-00-00 00:00:00'),(5,'Spanish','es','active','0000-00-00 00:00:00','2013-09-05 17:19:17'),(9,'Chinese','CH','inactive','2013-09-05 17:19:53','2013-09-05 17:19:53'),(10,'Italian','IT','active','2013-09-05 17:20:12','2013-09-05 17:20:12'),(11,'Portuguese','PO','active','2013-09-05 17:20:51','2013-09-05 17:20:51'),(12,'*-/-*/*-/-*/-','**','active','2013-09-05 17:30:48','2013-09-05 17:30:48'),(13,'8988989899898989898','56','active','2013-09-05 17:31:15','2013-09-05 17:31:15'),(14,'fkldjfsdklfjdslkfjdslkfjsdklfjsdklfjsdklfjsdklfjsdlkfjsdklfjsdklfjsdklfjsdklfjdsklfjsdlkfjdslkfj','45','active','2013-09-05 17:31:55','2013-09-05 17:31:55'),(15,'alfa','ag','active','2013-09-05 18:07:46','2013-09-05 18:19:20'),(16,'alfita','aa','inactive','2013-09-05 18:07:52','2013-09-05 18:19:35'),(17,'english','en','active','2013-09-05 18:08:34','2013-09-05 18:08:34'),(18,'English','en','active','2013-09-05 18:09:14','2013-09-05 18:09:14'),(19,'a','aa','active','2013-09-05 18:18:49','2013-09-05 18:18:49'),(20,'a','aa','active','2013-09-05 18:18:54','2013-09-05 18:18:54'),(21,'a','aa','active','2013-09-05 18:19:01','2013-09-05 18:19:01'),(22,'a','aa','active','2013-09-05 18:19:48','2013-09-05 18:19:48'),(23,'a','aa','active','2013-09-05 18:19:54','2013-09-05 18:19:54'),(24,'aa','aa','active','2013-09-05 18:20:28','2013-09-05 18:20:28'),(27,'cc','cc','active','2013-09-05 18:21:33','2013-09-05 18:21:33'),(28,'ccc','cc','active','2013-09-05 18:21:38','2013-09-05 18:21:38');

/*Table structure for table `organization` */

DROP TABLE IF EXISTS `organization`;

CREATE TABLE `organization` (
  `organization_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`organization_id`),
  KEY `fk_organization_country1_idx` (`country_id`) USING BTREE,
  CONSTRAINT `organization_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `organization` */

insert  into `organization`(`organization_id`,`type`,`name`,`slug`,`description`,`address`,`city`,`state`,`phone`,`email`,`fax`,`url`,`date_add`,`date_update`,`country_id`) values (1,'','org1',NULL,'descrp1','add1','lima','lima','123456','email@email.com','123456',NULL,'2013-08-02 16:25:59','2013-08-02 16:26:02',NULL),(2,'','org2',NULL,'desc2','add2','oslo','oslo','654321','email2@email.com','6543210',NULL,'2013-08-12 10:37:32','2013-08-12 10:37:34',NULL);

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `roleId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_57698A6AB8C2FD88` (`roleId`),
  KEY `IDX_57698A6A727ACA70` (`parent_id`),
  CONSTRAINT `role_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `role` */

/*Table structure for table `section` */

DROP TABLE IF EXISTS `section`;

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `path` varchar(45) DEFAULT NULL,
  `title` text,
  `content` text,
  `order` int(11) DEFAULT NULL,
  `date_add` varchar(45) DEFAULT NULL,
  `date_upd` varchar(45) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `fk_section_section1_idx` (`parent_id`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `section` (`section_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `section` */

/*Table structure for table `section_taxonomy` */

DROP TABLE IF EXISTS `section_taxonomy`;

CREATE TABLE `section_taxonomy` (
  `equipment_taxonomy_id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` text,
  `path` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `children_count` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `section_taxonomy` */

/*Table structure for table `section_taxonomy_relationship` */

DROP TABLE IF EXISTS `section_taxonomy_relationship`;

CREATE TABLE `section_taxonomy_relationship` (
  `equipment_id` int(11) NOT NULL,
  `equipment_taxonomy_id` int(11) NOT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_id`,`equipment_taxonomy_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment1_idx` (`equipment_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment_taxonomy1_idx` (`equipment_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `section_taxonomy_relationship` */

/*Table structure for table `test` */

DROP TABLE IF EXISTS `test`;

CREATE TABLE `test` (
  `test_id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `description` text,
  `max_time` int(11) DEFAULT NULL,
  `random_questions` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `equipment_id` int(11) NOT NULL,
  PRIMARY KEY (`test_id`),
  KEY `fk_test_equipment1_idx` (`equipment_id`),
  CONSTRAINT `test_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `test` */

/*Table structure for table `test_question` */

DROP TABLE IF EXISTS `test_question`;

CREATE TABLE `test_question` (
  `test_question_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `statement` text NOT NULL,
  `status` varchar(45) NOT NULL,
  `order` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`test_question_id`),
  KEY `fk_test_question_test1_idx` (`test_id`),
  CONSTRAINT `test_question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `test_question` */

/*Table structure for table `test_question_option` */

DROP TABLE IF EXISTS `test_question_option`;

CREATE TABLE `test_question_option` (
  `test_question_option_id` int(11) NOT NULL,
  `statement` text,
  `status` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `test_question_id` int(11) NOT NULL,
  PRIMARY KEY (`test_question_option_id`,`test_question_id`),
  KEY `fk_test_question_option_test_question1_idx` (`test_question_id`),
  CONSTRAINT `test_question_option_ibfk_1` FOREIGN KEY (`test_question_id`) REFERENCES `test_question` (`test_question_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `test_question_option` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `display_name` varchar(45) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `zip` varchar(25) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `phone1` varchar(25) DEFAULT NULL,
  `phone2` varchar(25) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `date_last_login` datetime DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `superior_id` int(11) DEFAULT NULL,
  `organization_id` int(11) NOT NULL,
  `state` int(2) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive, 9=deleted',
  `security_key` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_language_idx` (`language_id`),
  KEY `fk_user_organization1_idx` (`organization_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`email`,`password`,`first_name`,`last_name`,`display_name`,`address`,`zip`,`city`,`phone1`,`phone2`,`date_add`,`date_update`,`date_last_login`,`language_id`,`superior_id`,`organization_id`,`state`,`security_key`) values (1,'monday','sysco@sysco.no','$2y$14$JvCwHGsfS7RoAzZmOKJL8uXgxmQkhu59V3hEBHlkYhAOZpDxRKeVm','abcdddddsfsdfsdfs','defdsdsdsdsddsadasd','abcdddddsfsdfsdfs defdsdsdsdsddsadasd','asdasd','asadas','','','','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',2,0,1,1,NULL),(2,'100cgc','100cgc@sysco.no','$2y$14$voVd5b3J5kM.2WMtws1HMetB3A7yy1Lq2hj/5I3ER2K.ZYLrnkJJW','Cristhian','Gonzales','Cristhian Gonzales','','','','','','2013-09-05 21:30:23',NULL,NULL,1,0,1,1,NULL);

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `role_id` varchar(255) NOT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role` */

insert  into `user_role`(`role_id`,`is_default`,`parent_id`) values ('admin',0,'user'),('guest',0,NULL),('user',1,NULL);

/*Table structure for table `user_role_linker` */

DROP TABLE IF EXISTS `user_role_linker`;

CREATE TABLE `user_role_linker` (
  `user_id` int(11) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_linker_rol_id` (`role_id`),
  CONSTRAINT `fk_linker_rol_id` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_linker_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role_linker` */

insert  into `user_role_linker`(`user_id`,`role_id`) values (1,'admin'),(2,'user');

/*Table structure for table `usermeta` */

DROP TABLE IF EXISTS `usermeta`;

CREATE TABLE `usermeta` (
  `usermeta_id` int(11) NOT NULL,
  `key` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`usermeta_id`),
  KEY `fk_user_meta_users1_idx` (`user_id`),
  CONSTRAINT `usermeta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `usermeta` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
