/*
Navicat MySQL Data Transfer

Source Server         : 10.10.11.6
Source Server Version : 50169
Source Host           : 10.10.11.6:3306
Source Database       : vidum-apps_dev

Target Server Type    : MYSQL
Target Server Version : 50169
File Encoding         : 65001

Date: 2013-10-18 11:56:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `attachment`
-- ----------------------------
DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `filename` varchar(45) DEFAULT NULL,
  `mimetype` varchar(45) DEFAULT NULL,
  `date_add` varchar(45) DEFAULT NULL,
  `date_update` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attachment
-- ----------------------------
INSERT INTO `attachment` VALUES ('7', 'att1', 'wqdwdq', 'Array', 'image/jpeg', '2013-09-30 18:13:33', '2013-09-30 18:20:23');

-- ----------------------------
-- Table structure for `country`
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `iso_code` varchar(3) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`country_id`),
  KEY `country_iso_code` (`iso_code`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of country
-- ----------------------------
INSERT INTO `country` VALUES ('1', 'Deutschland', 'DE', 'active');
INSERT INTO `country` VALUES ('2', 'Austria', 'AT', 'active');
INSERT INTO `country` VALUES ('3', 'Belgium', 'BE', 'active');
INSERT INTO `country` VALUES ('4', 'Canada', 'CA', 'active');
INSERT INTO `country` VALUES ('5', 'China', 'CN', 'active');
INSERT INTO `country` VALUES ('6', 'España', 'ES', 'active');
INSERT INTO `country` VALUES ('7', 'Finland', 'FI', 'active');
INSERT INTO `country` VALUES ('8', 'France', 'FR', 'active');
INSERT INTO `country` VALUES ('9', 'Greece', 'GR', 'active');
INSERT INTO `country` VALUES ('10', 'Italia', 'IT', 'active');
INSERT INTO `country` VALUES ('11', 'Japan', 'JP', 'active');
INSERT INTO `country` VALUES ('12', 'Luxemburg', 'LU', 'active');
INSERT INTO `country` VALUES ('13', 'Nederland', 'NL', 'active');
INSERT INTO `country` VALUES ('14', 'Polska', 'PL', 'active');
INSERT INTO `country` VALUES ('15', 'Portugal', 'PT', 'active');
INSERT INTO `country` VALUES ('16', 'Czech Republic', 'CZ', 'active');
INSERT INTO `country` VALUES ('17', 'United Kingdom', 'GB', 'active');
INSERT INTO `country` VALUES ('18', 'Sweden', 'SE', 'active');
INSERT INTO `country` VALUES ('19', 'Switzerland', 'CH', 'active');
INSERT INTO `country` VALUES ('20', 'Denmark', 'DK', 'active');
INSERT INTO `country` VALUES ('21', 'USA', 'US', 'active');
INSERT INTO `country` VALUES ('22', 'HongKong', 'HK', 'active');
INSERT INTO `country` VALUES ('23', 'Norway', 'NO', 'active');
INSERT INTO `country` VALUES ('24', 'Australia', 'AU', 'active');
INSERT INTO `country` VALUES ('25', 'Singapore', 'SG', 'active');
INSERT INTO `country` VALUES ('26', 'Éire', 'IE', 'active');
INSERT INTO `country` VALUES ('27', 'New Zealand', 'NZ', 'active');
INSERT INTO `country` VALUES ('28', 'South Korea', 'KR', 'active');
INSERT INTO `country` VALUES ('29', 'מדינת ישראל', 'IL', 'active');
INSERT INTO `country` VALUES ('30', 'South Africa', 'ZA', 'active');
INSERT INTO `country` VALUES ('31', 'Nigeria', 'NG', 'active');
INSERT INTO `country` VALUES ('32', 'Ivory Coast', 'CI', 'active');
INSERT INTO `country` VALUES ('33', 'Togo', 'TG', 'active');
INSERT INTO `country` VALUES ('34', 'Bolivia', 'BO', 'active');
INSERT INTO `country` VALUES ('35', 'Mauritius', 'MU', 'active');
INSERT INTO `country` VALUES ('36', 'Romania', 'RO', 'active');
INSERT INTO `country` VALUES ('37', 'Slovensko', 'SK', 'active');
INSERT INTO `country` VALUES ('38', 'Algeria', 'DZ', 'active');
INSERT INTO `country` VALUES ('39', 'American Samoa', 'AS', 'active');
INSERT INTO `country` VALUES ('40', 'Andorra', 'AD', 'active');
INSERT INTO `country` VALUES ('41', 'Angola', 'AO', 'active');
INSERT INTO `country` VALUES ('42', 'Anguilla', 'AI', 'active');
INSERT INTO `country` VALUES ('43', 'Antigua and Barbuda', 'AG', 'active');
INSERT INTO `country` VALUES ('44', 'Argentina', 'AR', 'active');
INSERT INTO `country` VALUES ('45', 'Armenia', 'AM', 'active');
INSERT INTO `country` VALUES ('46', 'Aruba', 'AW', 'active');
INSERT INTO `country` VALUES ('47', 'Azerbaijan', 'AZ', 'active');
INSERT INTO `country` VALUES ('48', 'Bahamas', 'BS', 'active');
INSERT INTO `country` VALUES ('49', 'Bahrain', 'BH', 'active');
INSERT INTO `country` VALUES ('50', 'Bangladesh', 'BD', 'active');
INSERT INTO `country` VALUES ('51', 'Barbados', 'BB', 'active');
INSERT INTO `country` VALUES ('52', 'Belarus', 'BY', 'active');
INSERT INTO `country` VALUES ('53', 'Belize', 'BZ', 'active');
INSERT INTO `country` VALUES ('54', 'Benin', 'BJ', 'active');
INSERT INTO `country` VALUES ('55', 'Bermuda', 'BM', 'active');
INSERT INTO `country` VALUES ('56', 'Bhutan', 'BT', 'active');
INSERT INTO `country` VALUES ('57', 'Botswana', 'BW', 'active');
INSERT INTO `country` VALUES ('58', 'Brazil', 'BR', 'active');
INSERT INTO `country` VALUES ('59', 'Brunei', 'BN', 'active');
INSERT INTO `country` VALUES ('60', 'Burkina Faso', 'BF', 'active');
INSERT INTO `country` VALUES ('61', 'Burma (Myanmar)', 'MM', 'active');
INSERT INTO `country` VALUES ('62', 'Burundi', 'BI', 'active');
INSERT INTO `country` VALUES ('63', 'Cambodia', 'KH', 'active');
INSERT INTO `country` VALUES ('64', 'Cameroon', 'CM', 'active');
INSERT INTO `country` VALUES ('65', 'Cape Verde', 'CV', 'active');
INSERT INTO `country` VALUES ('66', 'Central African Republic', 'CF', 'active');
INSERT INTO `country` VALUES ('67', 'Chad', 'TD', 'active');
INSERT INTO `country` VALUES ('68', 'Chile', 'CL', 'active');
INSERT INTO `country` VALUES ('69', 'Colombia', 'CO', 'active');
INSERT INTO `country` VALUES ('70', 'Comoros', 'KM', 'active');
INSERT INTO `country` VALUES ('71', 'Congo, Dem. Republic', 'CD', 'active');
INSERT INTO `country` VALUES ('72', 'Congo, Republic', 'CG', 'active');
INSERT INTO `country` VALUES ('73', 'Costa Rica', 'CR', 'active');
INSERT INTO `country` VALUES ('74', 'Croatia', 'HR', 'active');
INSERT INTO `country` VALUES ('75', 'Cuba', 'CU', 'active');
INSERT INTO `country` VALUES ('76', 'Cyprus', 'CY', 'active');
INSERT INTO `country` VALUES ('77', 'Djibouti', 'DJ', 'active');
INSERT INTO `country` VALUES ('78', 'Dominica', 'DM', 'active');
INSERT INTO `country` VALUES ('79', 'Dominican Republic', 'DO', 'active');
INSERT INTO `country` VALUES ('80', 'East Timor', 'TL', 'active');
INSERT INTO `country` VALUES ('81', 'Ecuador', 'EC', 'active');
INSERT INTO `country` VALUES ('82', 'Egypt', 'EG', 'active');
INSERT INTO `country` VALUES ('83', 'El Salvador', 'SV', 'active');
INSERT INTO `country` VALUES ('84', 'Equatorial Guinea', 'GQ', 'active');
INSERT INTO `country` VALUES ('85', 'Eritrea', 'ER', 'active');
INSERT INTO `country` VALUES ('86', 'Estonia', 'EE', 'active');
INSERT INTO `country` VALUES ('87', 'Ethiopia', 'ET', 'active');
INSERT INTO `country` VALUES ('88', 'Falkland Islands', 'FK', 'active');
INSERT INTO `country` VALUES ('89', 'Faroe Islands', 'FO', 'active');
INSERT INTO `country` VALUES ('90', 'Fiji', 'FJ', 'active');
INSERT INTO `country` VALUES ('91', 'Gabon', 'GA', 'active');
INSERT INTO `country` VALUES ('92', 'Gambia', 'GM', 'active');
INSERT INTO `country` VALUES ('93', 'Georgia', 'GE', 'active');
INSERT INTO `country` VALUES ('94', 'Ghana', 'GH', 'active');
INSERT INTO `country` VALUES ('95', 'Grenada', 'GD', 'active');
INSERT INTO `country` VALUES ('96', 'Greenland', 'GL', 'active');
INSERT INTO `country` VALUES ('97', 'Gibraltar', 'GI', 'active');
INSERT INTO `country` VALUES ('98', 'Guadeloupe', 'GP', 'active');
INSERT INTO `country` VALUES ('99', 'Guam', 'GU', 'active');
INSERT INTO `country` VALUES ('100', 'Guatemala', 'GT', 'active');
INSERT INTO `country` VALUES ('101', 'Guernsey', 'GG', 'active');
INSERT INTO `country` VALUES ('102', 'Guinea', 'GN', 'active');
INSERT INTO `country` VALUES ('103', 'Guinea-Bissau', 'GW', 'active');
INSERT INTO `country` VALUES ('104', 'Guyana', 'GY', 'active');
INSERT INTO `country` VALUES ('105', 'Haiti', 'HT', 'active');
INSERT INTO `country` VALUES ('106', 'Heard Island and McDonald Islands', 'HM', 'active');
INSERT INTO `country` VALUES ('107', 'Vatican City State', 'VA', 'active');
INSERT INTO `country` VALUES ('108', 'Honduras', 'HN', 'active');
INSERT INTO `country` VALUES ('109', 'Iceland', 'IS', 'active');
INSERT INTO `country` VALUES ('110', 'India', 'IN', 'active');
INSERT INTO `country` VALUES ('111', 'Indonesia', 'ID', 'active');
INSERT INTO `country` VALUES ('112', 'Iran', 'IR', 'active');
INSERT INTO `country` VALUES ('113', 'العراق', 'IQ', 'active');
INSERT INTO `country` VALUES ('114', 'Isle of Man', 'IM', 'active');
INSERT INTO `country` VALUES ('115', 'Jamaica', 'JM', 'active');
INSERT INTO `country` VALUES ('116', 'Jersey', 'JE', 'active');
INSERT INTO `country` VALUES ('117', 'Jordan', 'JO', 'active');
INSERT INTO `country` VALUES ('118', 'Kazakhstan', 'KZ', 'active');
INSERT INTO `country` VALUES ('119', 'Kenya', 'KE', 'active');
INSERT INTO `country` VALUES ('120', 'Kiribati', 'KI', 'active');
INSERT INTO `country` VALUES ('121', 'Korea, Dem. Republic of', 'KP', 'active');
INSERT INTO `country` VALUES ('122', 'Kuwait', 'KW', 'active');
INSERT INTO `country` VALUES ('123', 'Kyrgyzstan', 'KG', 'active');
INSERT INTO `country` VALUES ('124', 'Laos', 'LA', 'active');
INSERT INTO `country` VALUES ('125', 'Latvia', 'LV', 'active');
INSERT INTO `country` VALUES ('126', 'Lebanon', 'LB', 'active');
INSERT INTO `country` VALUES ('127', 'Lesotho', 'LS', 'active');
INSERT INTO `country` VALUES ('128', 'Liberia', 'LR', 'active');
INSERT INTO `country` VALUES ('129', 'Libya', 'LY', 'active');
INSERT INTO `country` VALUES ('130', 'Liechtenstein', 'LI', 'active');
INSERT INTO `country` VALUES ('131', 'Lithuania', 'LT', 'active');
INSERT INTO `country` VALUES ('132', 'Macau', 'MO', 'active');
INSERT INTO `country` VALUES ('133', 'Република Македонија', 'MK', 'active');
INSERT INTO `country` VALUES ('134', 'Madagascar', 'MG', 'active');
INSERT INTO `country` VALUES ('135', 'Malawi', 'MW', 'active');
INSERT INTO `country` VALUES ('136', 'Malaysia', 'MY', 'active');
INSERT INTO `country` VALUES ('137', 'Maldives', 'MV', 'active');
INSERT INTO `country` VALUES ('138', 'Mali', 'ML', 'active');
INSERT INTO `country` VALUES ('139', 'Malta', 'MT', 'active');
INSERT INTO `country` VALUES ('140', 'Marshall Islands', 'MH', 'active');
INSERT INTO `country` VALUES ('141', 'Martinique', 'MQ', 'active');
INSERT INTO `country` VALUES ('142', 'Mauritania', 'MR', 'active');
INSERT INTO `country` VALUES ('143', 'Hungary', 'HU', 'active');
INSERT INTO `country` VALUES ('144', 'Mayotte', 'YT', 'active');
INSERT INTO `country` VALUES ('145', 'Mexico', 'MX', 'active');
INSERT INTO `country` VALUES ('146', 'Micronesia', 'FM', 'active');
INSERT INTO `country` VALUES ('147', 'Moldova', 'MD', 'active');
INSERT INTO `country` VALUES ('148', 'Monaco', 'MC', 'active');
INSERT INTO `country` VALUES ('149', 'Mongolia', 'MN', 'active');
INSERT INTO `country` VALUES ('150', 'Montenegro', 'ME', 'active');
INSERT INTO `country` VALUES ('151', 'Montserrat', 'MS', 'active');
INSERT INTO `country` VALUES ('152', 'Morocco', 'MA', 'active');
INSERT INTO `country` VALUES ('153', 'Mozambique', 'MZ', 'active');
INSERT INTO `country` VALUES ('154', 'Namibia', 'NA', 'active');
INSERT INTO `country` VALUES ('155', 'Nauru', 'NR', 'active');
INSERT INTO `country` VALUES ('156', 'Nepal', 'NP', 'active');
INSERT INTO `country` VALUES ('157', 'Netherlands Antilles', 'AN', 'active');
INSERT INTO `country` VALUES ('158', 'New Caledonia', 'NC', 'active');
INSERT INTO `country` VALUES ('159', 'Nicaragua', 'NI', 'active');
INSERT INTO `country` VALUES ('160', 'Niger', 'NE', 'active');
INSERT INTO `country` VALUES ('161', 'Niue', 'NU', 'active');
INSERT INTO `country` VALUES ('162', 'Norfolk Island', 'NF', 'active');
INSERT INTO `country` VALUES ('163', 'Northern Mariana Islands', 'MP', 'active');
INSERT INTO `country` VALUES ('164', 'Oman', 'OM', 'active');
INSERT INTO `country` VALUES ('165', 'Pakistan', 'PK', 'active');
INSERT INTO `country` VALUES ('166', 'Palau', 'PW', 'active');
INSERT INTO `country` VALUES ('167', 'Palestinian Territories', 'PS', 'active');
INSERT INTO `country` VALUES ('168', 'Panama', 'PA', 'active');
INSERT INTO `country` VALUES ('169', 'Papua New Guinea', 'PG', 'active');
INSERT INTO `country` VALUES ('170', 'Paraguay', 'PY', 'active');
INSERT INTO `country` VALUES ('171', 'Perú', 'PE', 'active');
INSERT INTO `country` VALUES ('172', 'Philippines', 'PH', 'active');
INSERT INTO `country` VALUES ('173', 'Pitcairn', 'PN', 'active');
INSERT INTO `country` VALUES ('174', 'Puerto Rico', 'PR', 'active');
INSERT INTO `country` VALUES ('175', 'Qatar', 'QA', 'active');
INSERT INTO `country` VALUES ('176', 'Réunion', 'RE', 'active');
INSERT INTO `country` VALUES ('177', 'Russian Federation', 'RU', 'active');
INSERT INTO `country` VALUES ('178', 'Rwanda', 'RW', 'active');
INSERT INTO `country` VALUES ('179', 'Saint Barthélemy', 'BL', 'active');
INSERT INTO `country` VALUES ('180', 'Saint Kitts and Nevis', 'KN', 'active');
INSERT INTO `country` VALUES ('181', 'Saint Lucia', 'LC', 'active');
INSERT INTO `country` VALUES ('182', 'Saint Martin', 'MF', 'active');
INSERT INTO `country` VALUES ('183', 'Saint Pierre and Miquelon', 'PM', 'active');
INSERT INTO `country` VALUES ('184', 'Saint Vincent and the Grenadines', 'VC', 'active');
INSERT INTO `country` VALUES ('185', 'Samoa', 'WS', 'active');
INSERT INTO `country` VALUES ('186', 'San Marino', 'SM', 'active');
INSERT INTO `country` VALUES ('187', 'São Tomé and Príncipe', 'ST', 'active');
INSERT INTO `country` VALUES ('188', 'Saudi Arabia', 'SA', 'active');
INSERT INTO `country` VALUES ('189', 'Senegal', 'SN', 'active');
INSERT INTO `country` VALUES ('190', 'Serbia', 'RS', 'active');
INSERT INTO `country` VALUES ('191', 'Seychelles', 'SC', 'active');
INSERT INTO `country` VALUES ('192', 'Sierra Leone', 'SL', 'active');
INSERT INTO `country` VALUES ('193', 'Slovenia', 'SI', 'active');
INSERT INTO `country` VALUES ('194', 'Solomon Islands', 'SB', 'active');
INSERT INTO `country` VALUES ('195', 'Somalia', 'SO', 'active');
INSERT INTO `country` VALUES ('196', 'South Georgia', 'GS', 'active');
INSERT INTO `country` VALUES ('197', 'Sri Lanka', 'LK', 'active');
INSERT INTO `country` VALUES ('198', 'Sudan', 'SD', 'active');
INSERT INTO `country` VALUES ('199', 'Suriname', 'SR', 'active');
INSERT INTO `country` VALUES ('200', 'Svalbard and Jan Mayen', 'SJ', 'active');
INSERT INTO `country` VALUES ('201', 'Swaziland', 'SZ', 'active');
INSERT INTO `country` VALUES ('202', 'Syria', 'SY', 'active');
INSERT INTO `country` VALUES ('203', 'Taiwan', 'TW', 'active');
INSERT INTO `country` VALUES ('204', 'Tajikistan', 'TJ', 'active');
INSERT INTO `country` VALUES ('205', 'Tanzania', 'TZ', 'active');
INSERT INTO `country` VALUES ('206', 'Thailand', 'TH', 'active');
INSERT INTO `country` VALUES ('207', 'Tokelau', 'TK', 'active');
INSERT INTO `country` VALUES ('208', 'Tonga', 'TO', 'active');
INSERT INTO `country` VALUES ('209', 'Trinidad and Tobago', 'TT', 'active');
INSERT INTO `country` VALUES ('210', 'Tunisia', 'TN', 'active');
INSERT INTO `country` VALUES ('211', 'Turkey', 'TR', 'active');
INSERT INTO `country` VALUES ('212', 'Turkmenistan', 'TM', 'active');
INSERT INTO `country` VALUES ('213', 'Turks and Caicos Islands', 'TC', 'active');
INSERT INTO `country` VALUES ('214', 'Tuvalu', 'TV', 'active');
INSERT INTO `country` VALUES ('215', 'Uganda', 'UG', 'active');
INSERT INTO `country` VALUES ('216', 'Ukraine', 'UA', 'active');
INSERT INTO `country` VALUES ('217', 'United Arab Emirates', 'AE', 'active');
INSERT INTO `country` VALUES ('218', 'Uruguay', 'UY', 'active');
INSERT INTO `country` VALUES ('219', 'Uzbekistan', 'UZ', 'active');
INSERT INTO `country` VALUES ('220', 'Vanuatu', 'VU', 'active');
INSERT INTO `country` VALUES ('221', 'Venezuela', 'VE', 'active');
INSERT INTO `country` VALUES ('222', 'Vietnam', 'VN', 'active');
INSERT INTO `country` VALUES ('223', 'Virgin Islands (British)', 'VG', 'active');
INSERT INTO `country` VALUES ('224', 'Virgin Islands (U.S.)', 'VI', 'active');
INSERT INTO `country` VALUES ('225', 'Wallis and Futuna', 'WF', 'active');
INSERT INTO `country` VALUES ('226', 'Western Sahara', 'EH', 'active');
INSERT INTO `country` VALUES ('227', 'Yemen', 'YE', 'active');
INSERT INTO `country` VALUES ('228', 'Zambia', 'ZM', 'active');
INSERT INTO `country` VALUES ('229', 'Zimbabwe', 'ZW', 'active');
INSERT INTO `country` VALUES ('230', 'Albania', 'AL', 'active');
INSERT INTO `country` VALUES ('231', 'Afghanistan', 'AF', 'active');
INSERT INTO `country` VALUES ('232', 'Antarctica', 'AQ', 'active');
INSERT INTO `country` VALUES ('233', 'Bosnia and Herzegovina', 'BA', 'active');
INSERT INTO `country` VALUES ('234', 'Bouvet Island', 'BV', 'active');
INSERT INTO `country` VALUES ('235', 'British Indian Ocean Territory', 'IO', 'active');
INSERT INTO `country` VALUES ('236', 'Bulgaria', 'BG', 'active');
INSERT INTO `country` VALUES ('237', 'Cayman Islands', 'KY', 'active');
INSERT INTO `country` VALUES ('238', 'Christmas Island', 'CX', 'active');
INSERT INTO `country` VALUES ('239', 'Cocos (Keeling) Islands', 'CC', 'active');
INSERT INTO `country` VALUES ('240', 'Cook Islands', 'CK', 'active');
INSERT INTO `country` VALUES ('241', 'French Guiana', 'GF', 'active');
INSERT INTO `country` VALUES ('242', 'French Polynesia', 'PF', 'active');
INSERT INTO `country` VALUES ('243', 'French Southern Territories', 'TF', 'active');
INSERT INTO `country` VALUES ('244', 'Åland Islands', 'AX', 'active');

-- ----------------------------
-- Table structure for `equipment`
-- ----------------------------
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
  `vendor_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of equipment
-- ----------------------------
INSERT INTO `equipment` VALUES ('1', 'eq1', null, 'description', null, 'active', '2013-10-18 08:43:49', '2013-10-18 08:43:52', '1', '1', null, null, null);
INSERT INTO `equipment` VALUES ('2', 'eq2', null, 'description', null, 'active', '2013-10-18 08:45:11', '2013-10-18 18:49:01', '6', null, null, null, null);
INSERT INTO `equipment` VALUES ('3', 'equip3', null, '', null, null, '2013-10-18 18:25:40', '2013-10-18 18:51:30', '6', null, null, null, null);

-- ----------------------------
-- Table structure for `equipment_stock`
-- ----------------------------
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

-- ----------------------------
-- Records of equipment_stock
-- ----------------------------

-- ----------------------------
-- Table structure for `equipment_taxonomy`
-- ----------------------------
DROP TABLE IF EXISTS `equipment_taxonomy`;
CREATE TABLE `equipment_taxonomy` (
  `equipment_taxonomy_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` text,
  `featured_image` varchar(255) DEFAULT NULL,
  `path` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `children_count` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_taxonomy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of equipment_taxonomy
-- ----------------------------
INSERT INTO `equipment_taxonomy` VALUES ('1', 'category', '0', 'Category 1', null, 'Test', '6e797586d578f37ac93fbbcdf93c154c.jpg', '0', '0', 'active', '0', '2013-09-06 11:35:01', '2013-09-06 11:35:01');
INSERT INTO `equipment_taxonomy` VALUES ('2', 'category', '0', 'Test category', null, 'ss', '19679549186ea57830594a84e62dd78d.jpg', '0', '0', 'active', '0', '2013-09-06 18:17:15', '2013-09-06 18:17:15');
INSERT INTO `equipment_taxonomy` VALUES ('3', 'category', '0', 'demo cat', null, '', null, '0', '0', 'inactive', '0', '2013-09-06 15:48:01', '2013-09-06 15:48:01');
INSERT INTO `equipment_taxonomy` VALUES ('4', 'category', '1', 'demo categ', null, 'demo', '453859528b76397a5a12a9d2f36ba702.jpg', '0/1', '0', 'active', '0', '2013-09-06 15:48:41', '2013-09-06 15:48:41');
INSERT INTO `equipment_taxonomy` VALUES ('5', 'category', '1', '789789798798', null, '/-*-*/465456454456', null, '0/0', '0', 'active', '0', '2013-09-06 17:48:46', '2013-09-06 17:48:46');
INSERT INTO `equipment_taxonomy` VALUES ('6', 'category', '1', '-/-*/*-/*-/*-/*-/-*', null, 'jhgsdjkhsdjksahdkjah', null, '0/0', '0', 'active', '0', '2013-09-06 17:49:26', '2013-09-06 17:49:26');
INSERT INTO `equipment_taxonomy` VALUES ('7', 'category', '14', 'sub categ 2.2', null, 'sdsds', 'b5fd3d1021912a84a44e8c79b9ad5543.jpg', '0/13/', '0', 'active', '0', '2013-09-09 22:56:45', '2013-09-09 22:56:45');
INSERT INTO `equipment_taxonomy` VALUES ('8', 'category', '14', 'sub cat 2.1.1', null, '', '538dcf9d8b98ac9a193a01f25d975ad2.jpg', '0/13/', '0', 'active', '0', '2013-09-09 22:55:33', '2013-09-09 22:55:33');
INSERT INTO `equipment_taxonomy` VALUES ('9', 'category', '13', 'sub categ 2', null, '', 'b2c9ed7c4d57e0b345315bbb5f20c1ed.jpg', '0/', '0', 'active', '0', '2013-09-09 22:54:18', '2013-09-09 22:54:18');
INSERT INTO `equipment_taxonomy` VALUES ('10', 'category', '0', 'Category 1', null, '', null, '0', '0', 'active', '0', '2013-09-06 18:23:03', '2013-09-06 18:23:03');
INSERT INTO `equipment_taxonomy` VALUES ('11', 'category', '1', 'Cat1-1', null, 'fdsfdfsdfdsf', '538dcf9d8b98ac9a193a01f25d975ad2.jpg', '0/1', '0', 'active', '0', '2013-09-06 18:45:53', '2013-09-06 18:45:53');
INSERT INTO `equipment_taxonomy` VALUES ('12', 'category', '11', 'Cat1-1-1', null, 'dsdsada', '37855e6cae7048d5c5689b0eb9044576.jpg', '0/1/11', '0', 'active', '0', '2013-09-06 18:46:52', '2013-09-06 18:46:52');
INSERT INTO `equipment_taxonomy` VALUES ('13', 'category', '0', 'Category 2', null, 'demo', '3226e96416663496c10a13bcfa709a07.jpg', '0', '0', 'active', '0', '2013-09-06 19:01:06', '2013-09-06 19:01:06');
INSERT INTO `equipment_taxonomy` VALUES ('14', 'category', '13', 'Sub cat 2.1', null, '', '1900d7500196219c9b56e1eee4f79718.jpg', '0/13', '0', 'active', '0', '2013-09-06 19:02:35', '2013-09-06 19:02:35');
INSERT INTO `equipment_taxonomy` VALUES ('15', 'category', '14', 'Sub cat 2.1.1', null, '', '5534d7dde31ed57fd862d3b30c03fc4d.jpg', '0/13/13', '0', 'active', '0', '2013-09-06 19:03:32', '2013-09-06 19:03:32');
INSERT INTO `equipment_taxonomy` VALUES ('16', 'category', '0', 'category 3', null, 'fefsdfdsfs', 'b2c9ed7c4d57e0b345315bbb5f20c1ed.jpg', '0', '0', 'active', '0', '2013-09-06 19:04:27', '2013-09-06 19:04:27');
INSERT INTO `equipment_taxonomy` VALUES ('17', 'category', '0', 'Category 4', null, 'sdfsdfsfsfs', '87d07f90f10c40ed8075f45c042fe6f6.jpg', '0', '0', 'inactive', '0', '2013-09-06 19:05:22', '2013-09-06 19:05:22');
INSERT INTO `equipment_taxonomy` VALUES ('18', 'category', '0', 'notedesaparezcas', null, '', null, '0', '0', 'inactive', '0', '2013-09-06 19:06:58', '2013-09-06 19:06:58');
INSERT INTO `equipment_taxonomy` VALUES ('19', 'category', '0', 'category 6', null, '', 'be14a4be1ca7c47537b7c421d974414d.jpg', '0', '0', 'active', '0', '2013-09-06 19:09:24', '2013-09-06 19:09:24');
INSERT INTO `equipment_taxonomy` VALUES ('20', 'category', '0', 'Tanks', null, '', '81066bfd704a5b228b047eeee71b2ae6.jpg', '0', '0', 'active', '0', '2013-09-09 15:45:55', '2013-09-09 15:45:55');
INSERT INTO `equipment_taxonomy` VALUES ('21', 'category', '20', 'Leopard', null, '', '81066bfd704a5b228b047eeee71b2ae6.jpg', '0/20', '0', 'active', '0', '2013-09-09 15:43:44', '2013-09-09 15:43:44');
INSERT INTO `equipment_taxonomy` VALUES ('22', 'category', '0', 'cate n', null, 'sadasasas', '453859528b76397a5a12a9d2f36ba702.jpg', '0', '0', 'active', '0', '2013-09-09 23:34:21', '2013-09-09 23:34:21');
INSERT INTO `equipment_taxonomy` VALUES ('23', 'category', '22', 'sub cate n.n', null, 'dsasadasdas', '37855e6cae7048d5c5689b0eb9044576.jpg', '0/', '0', 'active', '0', '2013-09-09 23:35:21', '2013-09-09 23:35:21');
INSERT INTO `equipment_taxonomy` VALUES ('24', 'category', '0', 'a', null, '', null, '0', '0', 'active', '0', '2013-09-10 23:53:04', '2013-09-10 23:53:04');
INSERT INTO `equipment_taxonomy` VALUES ('25', 'category', '0', 'Defibrillatorer', null, '', 'b8c6266371682ba92560276142c8cc82.png', '0', '0', 'active', '0', '2013-09-11 12:42:23', '2013-09-11 12:42:23');
INSERT INTO `equipment_taxonomy` VALUES ('26', 'category', '0', 'kjhdkjfhsjkfhsjk', null, 'fdjklfhfkljskl', '8a107726b6241321c3bfc3aa1727f961.jpg', '0', '0', 'active', '0', '2013-09-11 19:35:59', '2013-09-11 19:35:59');
INSERT INTO `equipment_taxonomy` VALUES ('27', 'category', '0', 'demo demo', null, 'fdfdsfsdfsdfsdffdfd', '4f6a306c94af679657ced7273b5ad4ea.jpg', '0', '0', 'active', '0', '2013-09-11 19:31:07', '2013-09-11 19:31:07');

-- ----------------------------
-- Table structure for `equipment_taxonomy_relationship`
-- ----------------------------
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

-- ----------------------------
-- Records of equipment_taxonomy_relationship
-- ----------------------------
INSERT INTO `equipment_taxonomy_relationship` VALUES ('2', '11', null);
INSERT INTO `equipment_taxonomy_relationship` VALUES ('3', '5', null);

-- ----------------------------
-- Table structure for `equipmentmeta`
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of equipmentmeta
-- ----------------------------
INSERT INTO `equipmentmeta` VALUES ('301', '2', 'nsn', '1253423462363', '2013-10-18 18:49:01');
INSERT INTO `equipmentmeta` VALUES ('302', '3', 'nsn', '7894564123132', '2013-10-18 18:51:30');

-- ----------------------------
-- Table structure for `language`
-- ----------------------------
DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isocode` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of language
-- ----------------------------
INSERT INTO `language` VALUES ('1', 'English', 'en', 'active', '2013-09-03 23:43:38', '2013-09-03 23:43:38');
INSERT INTO `language` VALUES ('2', 'Norwegian', 'no', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `language` VALUES ('5', 'Spanish', 'sp', 'active', '0000-00-00 00:00:00', '2013-09-12 23:17:00');
INSERT INTO `language` VALUES ('9', 'Chinese', 'ch', 'inactive', '2013-09-05 17:19:53', '2013-10-16 17:15:42');
INSERT INTO `language` VALUES ('11', 'Portuguese', 'PO', 'active', '2013-09-05 17:20:51', '2013-09-05 17:20:51');
INSERT INTO `language` VALUES ('36', 'German', 'gm', 'active', '2013-09-12 22:21:45', '2013-09-12 22:21:45');

-- ----------------------------
-- Table structure for `organization`
-- ----------------------------
DROP TABLE IF EXISTS `organization`;
CREATE TABLE `organization` (
  `organization_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT 'active',
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`organization_id`),
  KEY `fk_organization_country` (`country_id`),
  CONSTRAINT `fk_organization_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of organization
-- ----------------------------
INSERT INTO `organization` VALUES ('1', '', 'org1', null, null, null, null, 'inactive', null, null, null, null, null, null, '1', null, null);
INSERT INTO `organization` VALUES ('2', '', 'org2', null, null, null, null, 'active', null, null, null, null, null, null, '1', null, null);
INSERT INTO `organization` VALUES ('3', '', 'org3', null, null, null, null, 'active', null, null, null, null, null, null, '1', null, null);
INSERT INTO `organization` VALUES ('11', null, 'org4', null, null, null, null, 'active', null, null, null, null, null, null, '1', null, null);

-- ----------------------------
-- Table structure for `organization_taxonomy`
-- ----------------------------
DROP TABLE IF EXISTS `organization_taxonomy`;
CREATE TABLE `organization_taxonomy` (
  `equipment_taxonomy_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `description` text,
  `featured_image` varchar(255) DEFAULT NULL,
  `path` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `children_count` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of organization_taxonomy
-- ----------------------------

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
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

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for `section`
-- ----------------------------
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

-- ----------------------------
-- Records of section
-- ----------------------------

-- ----------------------------
-- Table structure for `section_taxonomy`
-- ----------------------------
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

-- ----------------------------
-- Records of section_taxonomy
-- ----------------------------

-- ----------------------------
-- Table structure for `section_taxonomy_relationship`
-- ----------------------------
DROP TABLE IF EXISTS `section_taxonomy_relationship`;
CREATE TABLE `section_taxonomy_relationship` (
  `equipment_id` int(11) NOT NULL,
  `equipment_taxonomy_id` int(11) NOT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`equipment_id`,`equipment_taxonomy_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment1_idx` (`equipment_id`),
  KEY `fk_equipment_taxonomy_relationship_equipment_taxonomy1_idx` (`equipment_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of section_taxonomy_relationship
-- ----------------------------

-- ----------------------------
-- Table structure for `test`
-- ----------------------------
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

-- ----------------------------
-- Records of test
-- ----------------------------

-- ----------------------------
-- Table structure for `test_question`
-- ----------------------------
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

-- ----------------------------
-- Records of test_question
-- ----------------------------

-- ----------------------------
-- Table structure for `test_question_option`
-- ----------------------------
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

-- ----------------------------
-- Records of test_question_option
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
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
  `organization_id` int(11) DEFAULT NULL,
  `state` int(2) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive, 9=deleted',
  `security_key` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_language_idx` (`language_id`),
  KEY `fk_user_organization1_idx` (`organization_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_organization` FOREIGN KEY (`organization_id`) REFERENCES `organization` (`organization_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'monday', 'cristhianjandir@gmail.com', '$2y$14$JvCwHGsfS7RoAzZmOKJL8uXgxmQkhu59V3hEBHlkYhAOZpDxRKeVm', 'Mon', 'Day', 'Mon Day', 'address', '78945', 'city', '', '', '0000-00-00 00:00:00', '2013-10-16 22:52:43', '0000-00-00 00:00:00', '2', '2', '2', '1', 'af06694237cb13d6217a37873711f53d95c811e8');
INSERT INTO `user` VALUES ('2', '100cgc', '100cgc@sysco.no', '', 'Cristhian J', 'Gonzales C', 'Cristhian J Gonzales C', 'Hevia St 29', 'lima01', 'Lima', '0178945687', '9989137323', '2013-09-05 21:30:23', '2013-10-18 18:55:28', null, '1', '2', null, '1', 'fc69a53f73dd45f05fac73989055cd3098231930');
INSERT INTO `user` VALUES ('3', 'lillian', 'lillian.sotomayor@sysco.no', '$2a$14$6UEZLvfd4jyiZHSLVPfPOO20fi.qEVFSKwsw461bi9WHaq35jxIGK', 'lillian', 'sotomayor', 'lillian sotomayor', 'fefdsfdsf', 'dfdsfdsfsdfs', '', '', '', '2013-09-06 15:39:34', '2013-09-10 19:59:26', null, '1', '0', '1', '1', 'fe774bd89dce3a37ca2598cb4d2ab1d0359f1368');
INSERT INTO `user` VALUES ('4', 'demito', 'demito@hotmail.com', '$2a$14$BtcwpeD2bs3ol6FsB3M18.NZgs7cmpLovSL2M3zCyyAScbB9Il2Au', 'demito test', 'demito test', 'demito test demito test', '', '', '', '', '', '2013-09-06 15:40:08', '2013-09-11 20:06:35', null, '1', '0', '1', '9', 'b6897203a32c4759e82fa9fd44a44183559bbb29');
INSERT INTO `user` VALUES ('5', 'bebe123', 'bebe@hotmail.com', '$2y$14$gOh0dBlSEXrg99EyNWoGquC5heJTZgw18o0bZEcu5x4YX8kxFNsNS', 'Test', 'Bb', 'Test Bb', '', '', '', '', '', '2013-09-06 15:41:38', null, null, '2', '3', '2', '9', null);
INSERT INTO `user` VALUES ('6', 'carlos', 'carlos_alva@usmp.pe', '$2y$14$1jxN4A0VOj/UI2245aMcIen97sCL7Fltd11hKcJhEvAU2WMIP3Jp.', 'carlos', 'alva', 'carlos alva', '', '', '', '', '', '2013-09-06 08:42:13', '2013-10-09 17:43:19', null, '1', '0', '1', '1', null);
INSERT INTO `user` VALUES ('7', '5645645645646456', 'fjdhfkdhfksj@hotmail.com', '$2a$14$7YxK0U0b7YK6bMRGtTMsF.6MAalroMw4XJPXQP4s.7Bu/O/P8ZTsO', 'sdfsdfsdfsd', 'kjfhdjkfhdfjk', 'sdfsdfsdfsd kjfhdjkfhdfjk', 'ksdjfkldj -/-*/*-/-*/ lksdjflkdjfkldjlksdjfklsdfjklsdfjlksdfjsdklfjsdklfjsdklfdjsfklsdjfksdjfkldsjfkldsfjkldfjklfjsdk', '', '', '', '', '2013-09-06 16:51:15', null, null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('8', 'probando', 'vjkfhkjdvh@gmail.com', '$2a$14$G78w.ANxPQacXES0AC6K7uqR/3KQQiHK.6yzue3kk6bpYzG7xd6wK', 'probando', 'probando', 'probando probando', '', '', '', '', '', '2013-09-06 16:53:27', null, null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('9', 'superior', 'superior@gmail.com', '$2a$14$/4wh7NWtUqqGoDragJm81OzyiyKDPGMxfFgGcDydc.zyL7OYwVO3K', 'superior', 'superior', 'superior superior', '', '', '', '', '', '2013-09-06 16:54:21', null, null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('10', 'Nosuperior', 'nosuperior@gmail.com', '$2a$14$vyhEKtM2IWhZyhNdOBzaVei/QU9LiNcIpEAbLtrSKQWqN34dP/u1u', 'Nosuperior', 'Nosuperior', 'Nosuperior Nosuperior', '', '', '', '', '', '2013-09-06 16:55:22', '2013-09-10 19:41:03', null, '1', '0', '1', '9', '7a7282da2d3ff2ace97d5ad51117f668d341760b');
INSERT INTO `user` VALUES ('11', 'demitooo', 'demitoo@hotmail.com', '$2y$14$LNHrUhgJB02NIcgpDTihFeRKHoHBdskmadaSbC/HFFlvmEn5JaGXq', 'pruebaa', 'pruebaa', 'pruebaa pruebaa', 'Victoriano Victoriano123 â€“ Las ViÃ±as de Miraflores', 'te1234567891', 'oslo', '4023840392483902', 'aa12345678901234', '2013-09-06 17:11:42', '2013-09-10 19:30:12', null, '1', '0', '2', '1', 'eafe03632d27d24660dc5a6b071f650de98b202d');
INSERT INTO `user` VALUES ('12', 'lasty', 'lasty@sysco.no', '$2y$14$KzrZmba11.AY.Ip/sm77kuX.eX.isseKwXXzZAJR4g5Yzy.QJ1.OO', 'Last', 'test', 'Last test', '', '', '', '', '', '2013-09-06 18:16:05', null, null, '2', '2', '2', '9', null);
INSERT INTO `user` VALUES ('14', 'kjetilso', 'kjetil.sorbo@gmail.com', '$2y$14$gOh0dBlSEXrg99EyNWoGquC5heJTZgw18o0bZEcu5x4YX8kxFNsNS', 'Kjetil', 'Sørbø', 'Kjetil Sørbø', 'Strandgaten 19', '0152', 'OSLO', '4790739122', '4790739122', '2013-09-04 19:25:02', '2013-09-09 18:48:13', null, '1', '2', '1', '1', '9cd476d0e3329e41179e451ad4dfdee6dca6dbdb');
INSERT INTO `user` VALUES ('48', 'kjetilss', 'kjetil.sorbo@sysco.no', '$2a$14$Ejr/0gTMzNRPfeJcwv/wneRcV5wbCIVtqwTqo5Umi7HzmACOVa7hW', 'Kjetil', 'Sorbo', 'Kjetil Sorbo', '', '', '', '90739122', '990642955', '2013-09-09 15:52:12', null, null, '2', '6', '2', '9', null);
INSERT INTO `user` VALUES ('55', 'bebe123', 'sysco@sdasdasd.no', '$2y$14$es4A3g9RnHCC8x9dJ7CfIuAJFLlPYFWWBY3BzHNWuD7BHSiKtK3za', 'fdfdsfdfd', 'fdsfdsfdsf', 'fdfdsfdfd fdsfdsfdsf', 'CSC', '', '', '', '', '2013-09-10 19:14:35', null, null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('66', 'test15', 'test@syscs.no', '$2y$14$HtSJD0sib/3FA2n1nUOcM.e98WC20Tei4NMgrpqqTsZ/4yviwmz3y', 'test', 'test', 'test test', 'addres', '12453', 'byy', '785421', '214532', null, '2013-09-20 15:56:22', null, '2', '6', '2', '1', null);
INSERT INTO `user` VALUES ('68', 'calle13', 'calle@trece.no', '$2y$14$AqKYNW0aqsq0hgh3VpcLSusca2J8Q/3rNSznFvWCsEanx.g.Sn6Gq', 'Calle', 'Residente', 'Calle Residente', 'Adresse', '78945', 'Sørbø', '7894562', '45612378', '2013-09-20 16:18:09', '2013-10-14 18:19:17', null, '1', '2', '2', '9', null);
INSERT INTO `user` VALUES ('69', 'fridayyysss', '100cgc@sysco.no', '$2y$14$zUGsWbF7UFIxm7UY/U0WnO6Jqdha6UqCA1ySPUgKel6BvhkHE2HNK', 'Test', 'Test', 'Test Test', '', '', '', '', '', '2013-09-20 22:08:14', '2013-09-20 22:09:18', null, '1', '1', '1', '9', null);
INSERT INTO `user` VALUES ('70', 'lunes', 'lunes@sysco.no', '$2y$14$IFDAmczv.Xl4eG5VJZBPeuo58Jzs/Tx/3q5ZvwPhytBJ47pg68SC.', 'Test', 'Test', 'Test Test', '', '', '', '', '', '2013-09-23 15:51:10', '2013-09-23 15:52:27', null, '1', '2', '1', '1', null);
INSERT INTO `user` VALUES ('73', 'lunes8', 'test@test.com', '$2y$14$5rmOWmPNmeINI1gNKYpr5Oh3COZUIF5i./yFswXqhFPJ9sDdaeVCS', 'lunesm', 'wde', 'lunesm wde', '', '', '', '', '', '2013-09-23 20:54:31', '2013-09-23 21:34:30', null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('76', 'lunes14', 'munea@lunes.com', '$2y$14$7i4LFFt1U13U0qx34//qFO34UDBK/JL7VYSOsEiPZAr4bPt285l.q', 'luneess', 'catorece', 'luneess catorece', 'address', '12345', 'By', '2545421', '', '2013-10-14 18:25:08', '2013-10-14 18:25:33', null, '1', '68', '2', '9', null);
INSERT INTO `user` VALUES ('77', 'crisfa', 'crijakda@dsaa.com', '$2y$14$rYLf/SfJu/MEdQeJcCKDHON1EnQUCCj5RpvjgOY/tdtdodLu72Og.', 'marie', 'fabi', 'marie fabi', '', '', '', '', '', '2013-10-14 18:32:18', '2013-10-14 22:11:33', null, '1', '4', '1', '1', null);
INSERT INTO `user` VALUES ('78', 'assdss', '100cgc@sysco.co', '$2y$14$cI.jg/EOqcUIlF2bfpea7ObHJ6VMlS5B0koebr/mo6Io7AGlsaHgG', 'Madsa', 'Lunes', 'Madsa Lunes', '', '', '', '', '', '2013-10-14 20:24:33', '2013-10-14 22:07:28', null, '1', '0', '1', '9', null);
INSERT INTO `user` VALUES ('79', 'macrviw', 'masl@sad.pe', '$2y$14$.VI1uae8CmCdyVgUS1njaODhS4vs2K995wySzsueq2gqMpNMZsgcy', 'marv', 'sdds', 'marv sdds', 'address', '12345', 'Lima', '2545421', '99891373', '2013-10-14 21:10:10', '2013-10-14 21:12:05', null, '2', '2', '3', '9', null);
INSERT INTO `user` VALUES ('80', 'amiwez', 'samiasdas@csascs.net', '$2y$14$1IE1.6F.FlKVTow2W69BOukG3icskbjPYfTVPvIEir6rJPgzWNSxm', 'miercoel', 'dxsadsao', 'miercoel dxsadsao', 'address', '12345', 'Lima', '2545421', '99891333', '2013-10-14 21:44:04', '2013-10-14 21:44:03', null, '2', '2', '2', '9', null);
INSERT INTO `user` VALUES ('81', 'mtaert', '101cgc@sysco.no', '', 'first', 'last', 'first last', 'address', '12345', 'city', '98745612', '78954522', '2013-10-15 16:40:04', '2013-10-15 18:00:11', null, '2', '2', '3', '9', null);
INSERT INTO `user` VALUES ('84', 'crisf', 'crisf@sysco.no', '', 'cristhian', 'crist', 'cristhian crist', 'address', '12345', 'Lima', '98765412', '78945235', '2013-10-15 18:40:51', '2013-10-15 19:43:40', null, '2', '2', '1', '1', null);
INSERT INTO `user` VALUES ('85', 'diez', 'diezzz@sysci.no', '$2y$14$6Fzn4gXRauKZRM5TR6.sE.aFv0o9.X9gCBj/pCLARPKZ6TpFI73ju', 'diezquin', 'quinces', 'diezquin quinces', 'address', '12345', 'Lima', '2123123', '9989137323', '2013-10-15 19:57:39', '2013-10-15 19:57:39', null, '2', '0', '1', '9', null);

-- ----------------------------
-- Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `role_id` varchar(255) NOT NULL,
  `is_default` tinyint(4) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role
-- ----------------------------
INSERT INTO `user_role` VALUES ('admin', '0', 'user');
INSERT INTO `user_role` VALUES ('guest', '0', null);
INSERT INTO `user_role` VALUES ('user', '1', null);

-- ----------------------------
-- Table structure for `user_role_linker`
-- ----------------------------
DROP TABLE IF EXISTS `user_role_linker`;
CREATE TABLE `user_role_linker` (
  `user_id` int(11) NOT NULL,
  `role_id` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_linker_rol_id` (`role_id`),
  CONSTRAINT `fk_linker_rol_id` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_linker_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_role_linker
-- ----------------------------
INSERT INTO `user_role_linker` VALUES ('1', 'admin');
INSERT INTO `user_role_linker` VALUES ('2', 'admin');
INSERT INTO `user_role_linker` VALUES ('3', 'admin');
INSERT INTO `user_role_linker` VALUES ('6', 'admin');
INSERT INTO `user_role_linker` VALUES ('7', 'admin');
INSERT INTO `user_role_linker` VALUES ('8', 'admin');
INSERT INTO `user_role_linker` VALUES ('9', 'admin');
INSERT INTO `user_role_linker` VALUES ('10', 'admin');
INSERT INTO `user_role_linker` VALUES ('11', 'admin');
INSERT INTO `user_role_linker` VALUES ('14', 'admin');
INSERT INTO `user_role_linker` VALUES ('55', 'admin');
INSERT INTO `user_role_linker` VALUES ('68', 'admin');
INSERT INTO `user_role_linker` VALUES ('69', 'admin');
INSERT INTO `user_role_linker` VALUES ('70', 'admin');
INSERT INTO `user_role_linker` VALUES ('77', 'admin');
INSERT INTO `user_role_linker` VALUES ('79', 'admin');
INSERT INTO `user_role_linker` VALUES ('80', 'admin');
INSERT INTO `user_role_linker` VALUES ('84', 'admin');
INSERT INTO `user_role_linker` VALUES ('85', 'admin');
INSERT INTO `user_role_linker` VALUES ('48', 'guest');
INSERT INTO `user_role_linker` VALUES ('4', 'user');
INSERT INTO `user_role_linker` VALUES ('5', 'user');
INSERT INTO `user_role_linker` VALUES ('12', 'user');
INSERT INTO `user_role_linker` VALUES ('66', 'user');
INSERT INTO `user_role_linker` VALUES ('73', 'user');
INSERT INTO `user_role_linker` VALUES ('76', 'user');
INSERT INTO `user_role_linker` VALUES ('78', 'user');
INSERT INTO `user_role_linker` VALUES ('81', 'user');

-- ----------------------------
-- Table structure for `usermeta`
-- ----------------------------
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

-- ----------------------------
-- Records of usermeta
-- ----------------------------
