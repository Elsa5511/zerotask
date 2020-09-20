/*
SQLyog Ultimate v9.02 
MySQL - 5.1.69 : Database - vidum-apps
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vidum-apps` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `vidum-apps`;

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
  `status` varchar(45) DEFAULT NULL,
  `date_add` varchar(45) DEFAULT NULL,
  `date_update` varchar(45) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`equipment_id`),
  KEY `fk_equipment_users1_idx` (`user_id`) USING BTREE,
  KEY `fk_equipment_organization1_idx` (`vendor_id`) USING BTREE,
  KEY `fk_equipment_organization2_idx` (`manufacturer_id`) USING BTREE,
  KEY `fk_equipment_organization3_idx` (`owner_id`) USING BTREE,
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_3` FOREIGN KEY (`owner_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `equipment` */

insert  into `equipment`(`equipment_id`,`title`,`code`,`description`,`order`,`status`,`date_add`,`date_update`,`user_id`,`vendor_id`,`manufacturer_id`,`owner_id`) values (1,'eq1','cod1','desc1',1,'active',NULL,NULL,1,2,1,2),(2,'eq2','cod2','desc2',2,'active',NULL,NULL,1,1,1,1),(3,'eq3','cod3','desc3',3,'active',NULL,NULL,2,2,2,2),(10,'aaaaaaa',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1),(11,'aaaaaaa',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1),(12,'test1',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1);

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

insert  into `equipment_taxonomy`(`equipment_taxonomy_id`,`type`,`parent_id`,`name`,`slug`,`description`,`path`,`order`,`status`,`children_count`,`date_add`,`date_update`) values (1,'category',0,'category 1',NULL,'descripcion 1',NULL,1,'active',1,'2013-08-02 17:01:26','2013-08-02 17:01:28'),(2,'category',0,'categoria 2',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(3,'category',0,'categoria 3',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(4,'category',0,'categoria 4',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(5,'category',0,'category 5',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(6,'othertype',0,'othertype',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL);

/*Table structure for table `equipment_taxonomy_relationship` */

DROP TABLE IF EXISTS `equipment_taxonomy_relationship`;

CREATE TABLE `equipment_taxonomy_relationship` (
  `equipment_id` int(11) NOT NULL,
  `equipment_taxonomy_id` int(11) NOT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_id`,`equipment_taxonomy_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment1_idx` (`equipment_id`) USING BTREE,
  KEY `fk_equipment_taxonomy_relationship_equipment_taxonomy1_idx` (`equipment_taxonomy_id`) USING BTREE,
  CONSTRAINT `equipment_taxonomy_relationship_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipment_taxonomy_relationship_ibfk_2` FOREIGN KEY (`equipment_taxonomy_id`) REFERENCES `equipment_taxonomy` (`equipment_taxonomy_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `equipment_taxonomy_relationship` */

insert  into `equipment_taxonomy_relationship`(`equipment_id`,`equipment_taxonomy_id`,`date_add`) values (1,1,'2013-08-02 17:01:45'),(1,5,'2013-08-15 16:19:19'),(2,2,'2013-08-07 16:16:16'),(3,2,'2013-08-07 16:25:55');

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
  CONSTRAINT `fk_equipment_equipmentmeta` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`equipment_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `equipmentmeta` */

insert  into `equipmentmeta`(`equipmentmeta_id`,`equipment_id`,`key`,`value`,`date_add`) values (1,1,'nsn','1234567891011','2013-08-16 09:51:29'),(2,1,'sap','123456','2013-08-16 09:51:47'),(3,2,'nsn','987654321','0000-00-00 00:00:00'),(4,2,'sap','654321','0000-00-00 00:00:00'),(5,3,'nsn','3742734923749247','0000-00-00 00:00:00'),(6,3,'sap','21371827','0000-00-00 00:00:00'),(7,1,'vendor_part','v123','0000-00-00 00:00:00'),(8,2,'vendor_part','v2345','0000-00-00 00:00:00'),(9,3,'vendor_part','v98754','0000-00-00 00:00:00'),(10,10,'nsn','31312312','2013-08-22 23:44:32'),(11,11,'sap','1111111','2013-08-22 23:49:55'),(12,12,'sap','1111111','2013-08-22 23:50:50');

/*Table structure for table `language` */

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isocode` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `language` */

insert  into `language`(`language_id`,`name`,`isocode`) values (1,'English','en'),(2,'Norwegian',NULL);

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
  CONSTRAINT `FK_57698A6A727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `role` */

/*Table structure for table `section` */

DROP TABLE IF EXISTS `section`;

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `path` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `content` varchar(45) DEFAULT NULL,
  `date_add` varchar(45) DEFAULT NULL,
  `date_upd` varchar(45) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `fk_section_section1_idx` (`parent_id`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `section` (`section_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `section` */

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
  PRIMARY KEY (`user_id`),
  KEY `fk_users_language_idx` (`language_id`),
  KEY `fk_user_organization1_idx` (`organization_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`organization_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`email`,`password`,`first_name`,`last_name`,`display_name`,`address`,`zip`,`city`,`phone1`,`phone2`,`date_add`,`date_update`,`date_last_login`,`language_id`,`superior_id`,`organization_id`,`state`) values (1,'carlos','test@test.com.pe','511212','carlos','alva','carlos alva',NULL,NULL,NULL,NULL,NULL,'2013-08-02 16:30:43','2013-08-02 16:30:40','2013-08-02 16:30:37',2,NULL,2,1),(2,'cristhiangc','test@test.com.pe','12345678','Cristhian','Gonzales','Cristhian Gonzales','hevia 29','2948-lim','Lima','9995555','457-852','2013-08-02 16:30:43','2013-08-02 16:30:40','2013-08-02 16:30:37',2,8,2,1),(3,'jose',NULL,NULL,NULL,'carlos','Jose Carlos',NULL,NULL,NULL,NULL,NULL,'2013-08-02 16:30:43','2013-08-02 16:30:40','2013-08-02 16:30:37',1,NULL,1,1),(5,'test','test@test.com','123456','Test','Last','Test Last',NULL,NULL,NULL,NULL,NULL,'2013-08-08 23:09:01',NULL,NULL,1,NULL,1,9),(8,'sysco','sysco@test.no','$2y$14$eOQE6cdKInn/CDH7pwjfuOyk86/089Rq45QXVC.NmmY7FnQahrzjq','Testing','Test','Testing Test',NULL,NULL,NULL,NULL,NULL,'2013-08-10 01:02:04',NULL,NULL,1,NULL,2,1),(9,'second','test@test.com.pe','123456',NULL,NULL,' ',NULL,NULL,NULL,NULL,NULL,'2013-08-10 01:10:49',NULL,NULL,2,NULL,1,9),(13,'jcchavezs','jose.carlos.chavez@sysco.no','','JC','ChÃ¡vez','JosÃ© Carlos ChÃ¡vez',NULL,NULL,NULL,NULL,NULL,'2013-08-12 21:40:38',NULL,NULL,1,NULL,1,1),(17,'kjetil','kjetil.sorbo@gmail.com','test','Kjetil','Sorbo','Kjetil Sorbo',NULL,NULL,NULL,NULL,NULL,'2013-08-16 16:22:47',NULL,NULL,1,NULL,1,1),(21,'kjetils','kjetil.sorbo@gmail.com','test','Kjetil','Sorbo','Kjetil Sorbo',NULL,NULL,NULL,NULL,NULL,'2013-08-19 13:34:19',NULL,NULL,1,NULL,1,9),(28,'sysco667','admin@test.com','123456','Cristhian','Gonzales','Cristhian Gonzales',NULL,NULL,NULL,NULL,NULL,'2013-08-19 23:38:32',NULL,NULL,2,NULL,1,9),(29,'newuser','new@user.com','newuser','New','User','New User',NULL,NULL,NULL,NULL,NULL,'2013-08-19 21:39:57',NULL,NULL,1,NULL,1,9),(30,'newuser','new@user.com','newuser','New','User','New User',NULL,NULL,NULL,NULL,NULL,'2013-08-19 21:41:27',NULL,NULL,1,NULL,1,9),(31,'newuser','new@user.com','newuser','New','User','New User',NULL,NULL,NULL,NULL,NULL,'2013-08-19 21:42:41',NULL,NULL,1,NULL,1,9);

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(255) DEFAULT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user_role` */

insert  into `user_role`(`id`,`role_id`,`is_default`,`parent_id`) values (1,'admin',0,'user'),(2,'guest',0,NULL),(3,'user',1,NULL);

/*Table structure for table `user_role_linker` */

DROP TABLE IF EXISTS `user_role_linker`;

CREATE TABLE `user_role_linker` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role_linker` */

insert  into `user_role_linker`(`user_id`,`role_id`) values (8,1);

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
