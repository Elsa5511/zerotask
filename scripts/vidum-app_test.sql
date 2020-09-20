/*
SQLyog Ultimate v9.02 
MySQL - 5.1.69 : Database - vidum-apps_test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`vidum-apps_test` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `vidum-apps_test`;

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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

/*Data for the table `equipment` */

insert  into `equipment`(`equipment_id`,`title`,`code`,`description`,`order`,`status`,`date_add`,`date_update`,`user_id`,`vendor_id`,`manufacturer_id`,`owner_id`,`feature_image`) values (1,'bbbbbbb',NULL,'',NULL,NULL,NULL,'2013-09-05 17:07:03',1,2,2,1,''),(7,'abc',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(10,'Equipment01',NULL,'test',NULL,NULL,NULL,'2013-09-05 23:58:51',1,2,1,1,'Hydrangeas.gif'),(11,'equipo 2',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(12,'Equipment04/*',NULL,'*/*/*/*/*/*/*/*/*/',NULL,NULL,NULL,'2013-09-06 00:01:10',1,1,1,1,'Penguins.jpg'),(13,'Equitment05',NULL,'Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data FIN',NULL,NULL,NULL,NULL,1,1,2,2,''),(14,'X',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(16,'Equipment05',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(17,'Equipment06',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,'art_gall01.jpg'),(18,'Equipment07',NULL,'chicho',NULL,NULL,NULL,NULL,1,2,2,2,'art_gall01.jpg'),(19,'Equipment08',NULL,'CARITA FELIZ',NULL,NULL,NULL,NULL,1,1,1,1,'Jellyfish.bmp'),(20,'Equipment10',NULL,'HORTENSIAS.GIF',NULL,NULL,NULL,NULL,1,1,1,1,'Hydrangeas.gif'),(21,'Equipment11',NULL,'gif',NULL,NULL,NULL,NULL,1,1,1,1,'Hydrangeas.gif'),(22,'demo',NULL,'Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data. Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data. Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data. Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data. Application shows a message that indicates the required fields. If you do click on Ok, return to the window to enter the data fin',NULL,NULL,NULL,NULL,1,1,1,1,''),(23,'img',NULL,'ssss',NULL,NULL,NULL,NULL,1,1,1,1,'carita feliz.png'),(24,'CEquitment01',NULL,'flkdjfdsfjsdl lkvjldjfdlsfjlsdk lskdjfsklfjsdklfjsdklvn  lkdsjfdklsjflskfjlksfls cdldskfjlksjfdklsjfdklsjfdkls sdlkfjsdlkfjdslkfjsdklfjdslkfjdslkfjdslkfjdslfkdjsfkljsdlfkjdsklfj',NULL,NULL,NULL,NULL,1,2,2,2,''),(25,'CEquitment02',NULL,'ssdsdsdsdsdsdsd',NULL,NULL,NULL,NULL,1,1,1,1,''),(26,'CEquitment03',NULL,'kjhkjhkjhkjhkjhj',NULL,NULL,NULL,NULL,1,1,1,1,'Hydrangeas.gif'),(28,'I10Equitment02',NULL,'mbmnbmbmb',NULL,NULL,NULL,NULL,1,1,1,1,'Chrysanthemum.jpg'),(29,'I10Equitment03',NULL,'sasasasa',NULL,NULL,NULL,NULL,1,1,1,1,'Hydrangeas.gif'),(30,'I10Equitment04',NULL,'fffff',NULL,NULL,NULL,NULL,1,2,1,1,'carita feliz.png'),(31,'I10Equitment05',NULL,'vffgfgfdgfdgd',NULL,NULL,NULL,NULL,1,1,1,1,'Condor3.jpg'),(32,'eddel',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(33,'testdel2',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(34,'a1',NULL,'',NULL,NULL,NULL,'2013-09-05 21:59:11',1,1,1,1,'descarga.bmp'),(35,'a22',NULL,'',NULL,NULL,NULL,'2013-09-05 17:01:42',1,1,1,1,''),(36,'Equi10',NULL,'',NULL,NULL,NULL,NULL,1,1,1,1,''),(37,'Leopard 2',NULL,'',NULL,NULL,'2013-09-05 16:20:17',NULL,1,1,1,1,'VS_LADOK - ButtonPhoto_ArmoredTracked - M113.jpg'),(38,'Equipment04/*',NULL,'*/*/*/*/*/*/*/*/*/',NULL,NULL,'2013-09-05 16:47:39','2013-09-05 23:59:52',1,1,1,1,'Ã­ndice6.jpg'),(39,'Equipment04/*',NULL,'*/*/*/*/*/*/*/*/*/',NULL,NULL,'2013-09-05 16:49:01','2013-09-05 23:59:32',1,1,1,1,'Ã­ndice2.jpg'),(40,'prueba100',NULL,'',NULL,NULL,'2013-09-05 21:30:27',NULL,1,1,1,1,'descarga.bmp'),(41,'test10',NULL,'hiuyuiyiuy',NULL,NULL,'2013-09-05 21:33:46',NULL,1,1,1,1,'Chrysanthemum.jpg'),(42,'aeiou',NULL,'',NULL,NULL,'2013-09-05 21:35:17',NULL,1,1,1,1,'images7.jpg'),(43,'ad',NULL,'',NULL,NULL,'2013-09-05 21:36:35',NULL,1,1,1,1,'225A1B4B36BE56A6B75071C3986C14_h498_w598_m2.jpg'),(44,'img',NULL,'',NULL,NULL,'2013-09-05 21:38:22','2013-09-05 21:38:38',1,1,1,1,'Chrysanthemum.jpg'),(45,'chicho',NULL,'',NULL,NULL,'2013-09-05 21:39:25','2013-09-05 21:39:48',1,1,1,1,'images4.jpg'),(46,'er',NULL,'',NULL,NULL,'2013-09-05 21:40:11',NULL,1,1,1,1,'images4.jpg'),(47,'Ademomo',NULL,'',NULL,NULL,'2013-09-05 21:41:34',NULL,1,1,1,1,'imagesCACDEN5A.jpg');

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

insert  into `equipment_taxonomy`(`equipment_taxonomy_id`,`type`,`parent_id`,`name`,`slug`,`description`,`path`,`order`,`status`,`children_count`,`date_add`,`date_update`) values (0,'category',1,'dssf',NULL,'fesdfdsfdsfs','/0',0,'active',0,'2013-09-05 16:31:41','2013-09-05 16:31:41'),(1,'category',0,'category 1',NULL,'descripcion 1',NULL,1,'active',1,'2013-08-02 17:01:26','2013-08-02 17:01:28'),(2,'category',0,'categoria 2',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(3,'category',0,'categoria 3',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(4,'category',0,'categoria 4',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(5,'category',0,'category 5',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL),(6,'othertype',0,'othertype',NULL,NULL,NULL,NULL,'active',NULL,NULL,NULL);

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

insert  into `equipment_taxonomy_relationship`(`equipment_id`,`equipment_taxonomy_id`,`date_add`) values (1,3,NULL),(1,4,NULL),(7,3,NULL),(10,5,NULL),(11,2,NULL),(12,1,NULL),(12,2,NULL),(12,3,NULL),(12,4,NULL),(12,5,NULL),(13,1,NULL),(13,3,NULL),(14,2,NULL),(16,2,NULL),(17,5,NULL),(18,1,NULL),(18,2,NULL),(18,3,NULL),(18,4,NULL),(18,5,NULL),(19,1,NULL),(19,2,NULL),(19,3,NULL),(19,5,NULL),(20,2,NULL),(20,5,NULL),(21,2,NULL),(21,5,NULL),(22,2,NULL),(23,2,NULL),(24,2,NULL),(25,4,NULL),(26,1,NULL),(26,2,NULL),(28,2,NULL),(29,2,NULL),(30,3,NULL),(31,4,NULL),(32,2,NULL),(33,2,NULL),(34,2,NULL),(35,3,NULL),(36,2,NULL),(37,1,NULL),(38,1,NULL),(38,2,NULL),(38,3,NULL),(38,4,NULL),(38,5,NULL),(39,1,NULL),(39,2,NULL),(39,3,NULL),(39,4,NULL),(39,5,NULL),(40,4,NULL),(40,5,NULL),(41,4,NULL),(41,5,NULL),(42,3,NULL),(42,4,NULL),(43,1,NULL),(44,2,NULL),(45,3,NULL),(46,2,NULL),(47,3,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;

/*Data for the table `equipmentmeta` */

insert  into `equipmentmeta`(`equipmentmeta_id`,`equipment_id`,`key`,`value`,`date_add`) values (20,7,'nsn','sdfdf','2013-08-29 13:41:50'),(26,11,'nsn','1234567890123','2013-09-02 10:58:59'),(32,14,'nsn','232323232323323232323','2013-09-02 21:06:31'),(34,16,'nsn','0123456789012','2013-09-02 21:26:41'),(35,17,'nsn','1234567890129','2013-09-02 21:27:41'),(36,17,'sap','12345673','2013-09-02 21:27:41'),(41,20,'nsn','1134567890133','2013-09-02 22:00:44'),(42,20,'sap','52345678','2013-09-02 22:00:44'),(43,21,'nsn','1134567890156','2013-09-02 22:27:06'),(44,21,'sap','52345634','2013-09-02 22:27:06'),(45,22,'nsn','5678978978978','2013-09-02 22:31:53'),(46,22,'sap','89798789','2013-09-02 22:31:53'),(47,23,'nsn','0156465465465','2013-09-02 22:49:12'),(52,26,'nsn','1564878998798','2013-09-02 23:04:13'),(53,26,'sap','71564564','2013-09-02 23:04:13'),(56,28,'nsn','1234567890156','2013-09-02 23:17:26'),(57,28,'sap','12345699','2013-09-02 23:17:26'),(58,29,'nsn','6664567890124','2013-09-02 23:20:15'),(59,29,'sap','55345645','2013-09-02 23:20:15'),(60,30,'nsn','0564654564564','2013-09-02 23:24:42'),(61,30,'sap','65465456','2013-09-02 23:24:42'),(62,31,'nsn','0545645645645','2013-09-02 23:31:54'),(63,31,'sap','56456456','2013-09-02 23:31:54'),(75,13,'nsn','1234567890125','2013-09-03 16:46:52'),(76,13,'sap','12345679','2013-09-03 16:46:52'),(77,13,'vendor_part','/*/*/*/*/*/*','2013-09-03 16:46:52'),(78,18,'nsn','1134567890125','2013-09-03 17:00:17'),(79,18,'sap','12345633','2013-09-03 17:00:17'),(82,19,'nsn','1134567890155','2013-09-03 17:05:57'),(83,19,'sap','72345678','2013-09-03 17:05:57'),(92,24,'nsn','1234567890186','2013-09-03 17:40:19'),(93,24,'sap','12395678','2013-09-03 17:40:19'),(96,25,'nsn','1234567890179','2013-09-03 17:41:16'),(97,25,'sap','55345678','2013-09-03 17:41:16'),(98,32,'nsn','1234567890705','2013-09-03 18:14:47'),(99,32,'sap','48465456','2013-09-03 18:14:47'),(100,33,'nsn','1234567777777','2013-09-03 18:16:45'),(101,33,'sap','12345677','2013-09-03 18:16:46'),(106,36,'nsn','1564564568789','2013-09-03 21:00:41'),(107,36,'sap','87564564','2013-09-03 21:00:41'),(108,37,'nsn','1231231231231','2013-09-05 16:20:17'),(121,35,'nsn','7836793419317','2013-09-05 17:01:42'),(122,35,'sap','87687687','2013-09-05 17:01:42'),(143,1,'nsn','1234124124124','2013-09-05 17:07:03'),(144,1,'sap','14124124','2013-09-05 17:07:03'),(145,1,'vendor_part','sad','2013-09-05 17:07:03'),(146,40,'nsn','0564564165456','2013-09-05 21:30:27'),(147,40,'sap','07787787','2013-09-05 21:30:27'),(148,41,'nsn','0566535626254','2013-09-05 21:33:46'),(149,41,'sap','84567654','2013-09-05 21:33:46'),(150,42,'nsn','0564654654564','2013-09-05 21:35:17'),(151,42,'sap','87978778','2013-09-05 21:35:17'),(152,43,'nsn','5646546546546','2013-09-05 21:36:35'),(153,43,'sap','56456454','2013-09-05 21:36:35'),(155,44,'nsn','5456465465465','2013-09-05 21:38:38'),(157,45,'nsn','0564564564564','2013-09-05 21:39:48'),(158,46,'nsn','4343243242342','2013-09-05 21:40:12'),(159,47,'nsn','0156456465456','2013-09-05 21:41:34'),(181,34,'nsn','2051546456456','2013-09-05 21:59:11'),(182,34,'sap','56456458','2013-09-05 21:59:11'),(183,34,'vendor_part','ERERERER','2013-09-05 21:59:11'),(190,10,'nsn','1234568888889','2013-09-05 23:58:51'),(191,10,'sap','65432181','2013-09-05 23:58:51'),(192,39,'nsn','1234567890154','2013-09-05 23:59:32'),(193,39,'sap','12345545','2013-09-05 23:59:33'),(194,38,'nsn','1234567895454','2013-09-05 23:59:52'),(195,38,'sap','12345454','2013-09-05 23:59:52'),(196,12,'nsn','1234567890124','2013-09-06 00:01:10'),(197,12,'sap','12345678','2013-09-06 00:01:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`email`,`password`,`first_name`,`last_name`,`display_name`,`address`,`zip`,`city`,`phone1`,`phone2`,`date_add`,`date_update`,`date_last_login`,`language_id`,`superior_id`,`organization_id`,`state`,`security_key`) values (1,'carlos','carlos_alva@usmp.pe','$2a$14$kCjJ9D/zWbN7iEzj3tXl9.q9CS6w8SN2im2s0FAn0r9yefy2g6Q/u','carlos','alva','carlos alva','some address','12345','Lima','787-44-48','457-852','2013-08-02 16:30:43','2013-09-03 23:55:39','2013-08-02 16:30:37',1,0,2,1,NULL),(2,'cristhiangc','cristhian.gonzales@sysco.no','$2y$14$pevO77ZNCbX6jK6gT6Ipq.KRbRXwFq9dDImqfTJ.f64yTJWgXTUVi','Cristhian','Gonzales','Cristhian Gonzales','hevia 29','2948-lim','Lima','9995555','457-852','2013-08-02 16:30:43','2013-08-02 16:30:40','2013-08-02 16:30:37',2,8,2,1,NULL),(8,'sysco','sysco@test.no','$2y$14$vkk9B1PZTvre9aVE425Ci.altKYSlAaU4GnVA.2lc4sONbeqzeGKi','Testing','Test','Testing Test','some address','12345-no','lima','787-44-48','457-852','2013-08-10 01:02:04',NULL,NULL,1,8,2,9,NULL),(13,'jcchavezs','jose.carlos.chavez@sysco.no','$2y$14$roTLkFXWclVGhtI.WoSATuryizPBp4XdC7q8z3jmSQQkWAB9yHDTu','JC','ChÃ¡vez','JC ChÃ¡vez','some address','12345','Lima','787-44-48','7897897','2013-08-12 21:40:38',NULL,NULL,1,8,1,1,NULL),(21,'kjetils','kjetil.sorbo@sysco.no','$2y$14$8jsllfHThgetZiHGTi4iHOdxLa19FuhPUUaC12D88JztGd0k.Cm3C','Kjetil','Sorbo','Kjetil Sorbo','','','','','','2013-08-19 13:34:19','2013-08-28 10:34:39',NULL,1,0,1,1,NULL),(39,'cjgc','cj@sys.co','$2y$14$W2aaDy25A5qPeHxknycNtuiU.klGRTXfHN4aZH7H4qYO.JfmcLG2C','Cristhian','Gonzales','Cristhian Gonzales','some address','12345','lima','7894567','454545','2013-08-23 15:53:43',NULL,NULL,1,2,1,1,NULL),(40,'friday','friday@fri.de','$2y$14$vRofj1KHqvuRZVc.XJzQgOKlS8zYp7kggT8jKA1FcNlXS0EPfLQuK','Friday','Frideee','Friday Frideee','address friday','12345','Lima','787-44-48','457-852','2013-08-23 18:01:04',NULL,NULL,1,8,1,9,NULL),(41,'SAAA','sasa@udan.no','$2y$14$doQPO0UxQbA.N7dmoZjdu.ItPxauElBvQTCxHhxlnbSuCusLsGLku','sdasa','SAAA','sdasa SAAA','','','','','','2013-08-23 22:38:24',NULL,NULL,1,0,1,9,NULL),(42,'friday2','ddsadas@hotsa.co','$2y$14$wUdiRhisA5o08iB.QS.OLuha/TT7un6CxsXYAh0cPkWBTMZ4c6oSG','ufasfa-friday','fdasdas','ufasfa-friday fdasdas','','','','','','2013-08-23 22:45:47',NULL,NULL,2,0,1,1,NULL),(43,'monday','monday@test.no','$2a$14$gM20buBreXxN94WrqWjpLuagihSGaruHJKfwoly9etR3D20S37AN6','Mondaydd','Tuesdaydddd','Mondaydd Tuesdaydddd','some address','12345','Lima','787-44-486','7897897','2013-08-26 16:05:53',NULL,NULL,1,0,2,1,NULL),(50,'lillian','lillian.sotomayor@sysco.no','$2a$14$9CCXfeEISuf6JIGCVAIjw.j4c/fW3wcAWzflG4NZD4b2WW3ZiO8su','lilian','sotomayor',NULL,NULL,NULL,NULL,NULL,NULL,'2013-09-03 13:54:28','2013-09-03 23:24:05',NULL,1,0,1,1,'2cb993e1c43e5f8fd656ebf6334ca903fc04485a'),(51,'demo','demo@hotmail.com','$2a$14$bW/IH7TkwJZeDME7H0ow8.ikNZ33hbaY2/gmwL1ZnUkG3eku4Ps96','demo','demo','demo demo','','','','','','2013-09-04 16:44:20',NULL,NULL,1,0,1,9,NULL),(52,'demito','demito@hotmail.com','$2a$14$o9zY.T6vX0eeKpQho3TpHetCQ1QidrPbevE9NhdGATwkffBUKxSHC','demito','demito','demito demito','','','','','','2013-09-04 16:54:39',NULL,NULL,1,0,1,1,NULL),(53,'Prueba01prueba02prueba03prueba04prueba05','sotomayorlillian@gmail.com','$2a$14$XBN6VJRHOMHS0IW7T5og8.MrV3uMrDbfcKJNHq4rremMkd7vOqpny','A','A','A A','','','','','','2013-09-04 20:49:17',NULL,NULL,1,0,1,9,NULL),(54,'test01','u201014410@upc.edu.pe','$2a$14$8bpkkwYPeJVqk6ZexV62nuTgQohJku/S1jT6.jxaMe9oKSesaLb3u','test','test','test test','','','','','','2013-09-04 20:52:51',NULL,NULL,1,0,1,1,NULL),(55,'Prueba01prueba02prueba03prueba04prueba06','lillian_sotomayor@hotmail.com','$2a$14$620V7PIp5E3ehaMGPevsJOPkvsj/ppvvqlRyq31pRBLxYe5O7gw1.','prueba','prueba','prueba prueba','','','','','','2013-09-04 20:59:42',NULL,NULL,1,0,1,1,NULL),(56,'/*/*/*/*','mitest@hotmail.com','$2a$14$tTtFJWYFI/nDE7/Fd1nCDOvyaBR2OMYzRmrYduT24edWC7Cbmh4FS','/*','/*','/* /*','','','','','','2013-09-04 21:03:59',NULL,NULL,1,0,1,1,NULL),(57,'cccc','demote@gmail.com','$2a$14$1uPj3WzDEHS.zHd7FxRcFODvksTpTehg5K3.fjGD6asyXm13AYIfi','c','c','c c','','','','','','2013-09-04 21:39:03',NULL,NULL,1,0,1,9,NULL),(58,'Test1','test123@hotmail.com','$2a$14$KYvbPI4gjCZEZ7zVeOBKpuF7XL30IvoOqL6mm6BJBNRORTy6Obqqu','Unod','rewrewrewr','Unod rewrewrewr','*/*/*/*/*/*/*/*/*','12345','Lima','00 51 1 9928','','2013-09-04 22:14:08',NULL,NULL,2,0,1,1,NULL),(59,'123456','demo123@gmail.com','$2a$14$OUBVuDoKLOIQm1YJtPsz..FcHLOTrIaxaYCgI1ryopdby0TAaWgDC','dos','dos','dos dos','32312312312312313','00012','123','1234567890123456','1234567890123456','2013-09-04 22:39:10',NULL,NULL,5,58,2,9,NULL),(60,'/*/*/**/*/*','demo23@gmail.com','$2a$14$1wdVmQk/RTzm.dnS8rVcHeGxoDCVdZwEn0rMFfRTbJYrISVyKhiZG','3','3','3 3','Victoriano Castilla 123','test','/','123456','123456','2013-09-04 22:49:43',NULL,NULL,2,59,1,9,NULL),(61,'wwww','sdksdskj@hotmail.com','$2a$14$s5K7DeUAutDw3QzXluL6CuFrbmfl562th9tji4pF2JNfhYZAFhyWu','w','w','w w','*-/-*/*-*-/-*/-*/-','*-/*-98bb','uihiu\'-*/*-/-*545465','312313213jkhjhj','kjhkjhkjh21212**','2013-09-04 22:58:33',NULL,NULL,1,0,1,9,NULL),(62,'zuzu','z@hotmail.com','$2a$14$J3s1v2Q.CLheyUoqqxwjkuMU/YurxvsqcqhiBuqJH94e7BDQz.ABa','z','z','z z','','','','','','2013-09-04 23:40:28',NULL,NULL,2,0,1,9,NULL),(63,'xaxx','xaxx@gmail.com','$2a$14$b9CLzD8CYUZgGf1MlznOduCC0XsZ79vBuoQTPAzIuhEv3Ni8q/Zci','x','x','x x','','','','','','2013-09-04 23:42:03',NULL,NULL,2,0,1,1,NULL),(64,'yoyo','yoyo@hotmail.com','$2a$14$bAt843GVNydRaFF8qdleeOXxeGMzWlI59wkLeZrOCFrrX8j.wqnqW','yoyo','yoyo','yoyo yoyo','','','','','','2013-09-04 23:42:49',NULL,NULL,2,0,1,9,NULL),(65,'soso','soso@hotmail.com','$2a$14$hz0UhZP8cMFqqGX33R.WNeeR5kiMLC9uGyBOJpJWmZnHOrFvAbU.2','s','s','s s','','','','','','2013-09-04 23:55:49',NULL,NULL,1,0,2,1,NULL),(66,'aaaa','a@gmail.com','$2a$14$aDdooM/2/K.W9Gkew26j3uHsgXjrniM6Mrao0jZ86SCAQj7xo17ya','a','a','a a','AAb','','','','','2013-09-04 23:59:07',NULL,NULL,1,54,2,1,NULL),(67,'zulu','zulu@gmail.com','$2a$14$qRw2bn2yenqU0AdTNgISBuAFcf0DASv.B3uPRtyjCJnn1iXQ5NO.i','zulu','zulu','zulu zulu','','','','','','2013-09-05 00:02:11',NULL,NULL,1,0,1,1,NULL),(68,'bebe','bebe@hotmail.com','$2a$14$DpKOvIsNz.X2YlRV6PhknefXKQ7CYqStxVyaDcrbOUZUDwG8Qd.1m','bebe','bebe','bebe bebe','','','','','','2013-09-05 00:03:34',NULL,NULL,2,64,1,1,NULL),(69,'TEST2','TEST2@GMAIL.COM','$2a$14$6ie0.miUAgebjUtXvOlROuxX3joSW6qi2uDhX4pZwiln86JxgVVsC','TEST2','TEST2','TEST2 TEST2','','','','','','2013-09-05 00:08:36',NULL,NULL,1,0,1,1,NULL),(70,'test3','test3@gmail.com','$2a$14$ypn6zkieEdob/.LJIE.nxOo.VTC890oLNivQKn3pYSKC.kqMZfJ5u','test3','test3','test3 test3','','','','','','2013-09-05 00:09:58',NULL,NULL,2,58,2,1,NULL),(71,'TEST4','TEST4@GMAIL.COM','$2a$14$YLlbQbH95LUhWvv2hhlAze/Xb4/YWqROSeyuJOowkfBaP8uly9Lj6','TEST4','TEST4','TEST4 TEST4','','','','','','2013-09-05 00:10:45',NULL,NULL,1,0,1,1,NULL),(72,'q878787','q@yahoo.es','$2a$14$gYdAcLuOGw7CrFryQp0ulegDsxQAA0pnlAfe2gKDbkwOvmWVp.Xmm','qqqq','qqqqq','qqqq qqqqq','','','','','','2013-09-05 17:33:22',NULL,NULL,14,0,1,1,NULL);

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

insert  into `user_role`(`id`,`role_id`,`is_default`,`parent_id`) values (1,'admin',0,'user'),(2,'guest',1,NULL),(3,'user',0,NULL);

/*Table structure for table `user_role_linker` */

DROP TABLE IF EXISTS `user_role_linker`;

CREATE TABLE `user_role_linker` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_role_linker` */

insert  into `user_role_linker`(`user_id`,`role_id`) values (1,3),(2,3),(8,3),(13,3),(21,3),(39,1),(40,1),(41,1),(42,1),(43,1),(44,2),(50,1),(51,1),(52,3),(53,1),(54,1),(55,1),(56,1),(57,1),(58,2),(59,2),(60,3),(61,1),(62,3),(63,3),(64,1),(65,1),(66,3),(67,1),(68,1),(69,3),(70,2),(71,1),(72,1);

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
