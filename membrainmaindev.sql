-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: mindtechinstance.czohkymlnyjd.ap-south-1.rds.amazonaws.com    Database: membrainmain
-- ------------------------------------------------------
-- Server version	5.6.10

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL COMMENT 'Supplier Id',
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `login_usernme` varchar(255) DEFAULT NULL,
  `acknowledged` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `acknowledged_date` datetime DEFAULT NULL COMMENT 'Acknowledged Date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--

LOCK TABLES `alerts` WRITE;
/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `public_id` varchar(255) NOT NULL,
  `client_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `criteria_age` varchar(255) NOT NULL,
  `criteria_state` varchar(255) NOT NULL,
  `criteria_postcode` varchar(255) NOT NULL,
  `dncr_required` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `method` varchar(255) NOT NULL,
  `server_parameters` text NOT NULL,
  `parameter_mapping` text NOT NULL,
  `parameter_required` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client_email_suppression`
--

DROP TABLE IF EXISTS `client_email_suppression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_email_suppression` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `client_id` int(11) NOT NULL COMMENT 'Client Id',
  `data` varchar(255) NOT NULL COMMENT 'Data',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Client Email Suppression';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_email_suppression`
--

LOCK TABLES `client_email_suppression` WRITE;
/*!40000 ALTER TABLE `client_email_suppression` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_email_suppression` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_phone_suppression`
--

DROP TABLE IF EXISTS `client_phone_suppression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_phone_suppression` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `client_id` int(11) NOT NULL COMMENT 'Client Id',
  `data` varchar(255) NOT NULL COMMENT 'Data',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Client Phone Suppression';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_phone_suppression`
--

LOCK TABLES `client_phone_suppression` WRITE;
/*!40000 ALTER TABLE `client_phone_suppression` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_phone_suppression` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(150) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `country_code` varchar(50) NOT NULL,
  `email_suppression` tinyint(1) NOT NULL,
  `phone_suppression` tinyint(1) NOT NULL,
  `lead_expiry_days` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='Clients Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `active` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=utf8 COMMENT='Country Name Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Afghanistan','AF',9,'Yes'),(2,'Åland Islands','AX',9,'Yes'),(3,'Albania','AL',9,'Yes'),(4,'Algeria','DZ',9,'Yes'),(5,'American Samoa','AS',9,'Yes'),(6,'Andorra','AD',9,'Yes'),(7,'Angola','AO',9,'Yes'),(8,'Anguilla','AI',9,'Yes'),(9,'Antarctica','AQ',9,'Yes'),(10,'Antigua and Barbuda','AG',9,'Yes'),(11,'Argentina','AR',9,'Yes'),(12,'Armenia','AM',9,'Yes'),(13,'Aruba','AW',9,'Yes'),(14,'Australia','AU',1,'Yes'),(15,'Austria','AT',9,'Yes'),(16,'Azerbaijan','AZ',9,'Yes'),(17,'Bahamas','BS',9,'Yes'),(18,'Bahrain','BH',9,'Yes'),(19,'Bangladesh','BD',9,'Yes'),(20,'Barbados','BB',9,'Yes'),(21,'Belarus','BY',9,'Yes'),(22,'Belgium','BE',9,'Yes'),(23,'Belize','BZ',9,'Yes'),(24,'Benin','BJ',9,'Yes'),(25,'Bermuda','BM',9,'Yes'),(26,'Bhutan','BT',9,'Yes'),(27,'Bolivia, Plurinational State of','BO',9,'Yes'),(28,'Bonaire, Sint Eustatius and Saba','BQ',9,'Yes'),(29,'Bosnia and Herzegovina','BA',9,'Yes'),(30,'Botswana','BW',9,'Yes'),(31,'Bouvet Island','BV',9,'Yes'),(32,'Brazil','BR',9,'Yes'),(33,'British Indian Ocean Territory','IO',9,'Yes'),(34,'Brunei Darussalam','BN',9,'Yes'),(35,'Bulgaria','BG',9,'Yes'),(36,'Burkina Faso','BF',9,'Yes'),(37,'Burundi','BI',9,'Yes'),(38,'Cambodia','KH',9,'Yes'),(39,'Cameroon','CM',9,'Yes'),(40,'Canada','CA',4,'Yes'),(41,'Cape Verde','CV',9,'Yes'),(42,'Cayman Islands','KY',9,'Yes'),(43,'Central African Republic','CF',9,'Yes'),(44,'Chad','TD',9,'Yes'),(45,'Chile','CL',9,'Yes'),(46,'China','CN',9,'Yes'),(47,'Christmas Island','CX',9,'Yes'),(48,'Cocos (Keeling) Islands','CC',9,'Yes'),(49,'Colombia','CO',9,'Yes'),(50,'Comoros','KM',9,'Yes'),(51,'Congo','CG',9,'Yes'),(52,'Congo, the Democratic Republic of the','CD',9,'Yes'),(53,'Cook Islands','CK',9,'Yes'),(54,'Costa Rica','CR',9,'Yes'),(55,'Côte d`Ivoire','CI',9,'Yes'),(56,'Croatia','HR',9,'Yes'),(57,'Cuba','CU',9,'Yes'),(58,'Curaçao','CW',9,'Yes'),(59,'Cyprus','CY',9,'Yes'),(60,'Czech Republic','CZ',9,'Yes'),(61,'Denmark','DK',9,'Yes'),(62,'Djibouti','DJ',9,'Yes'),(63,'Dominica','DM',9,'Yes'),(64,'Dominican Republic','DO',9,'Yes'),(65,'Ecuador','EC',9,'Yes'),(66,'Egypt','EG',9,'Yes'),(67,'El Salvador','SV',9,'Yes'),(68,'Equatorial Guinea','GQ',9,'Yes'),(69,'Eritrea','ER',9,'Yes'),(70,'Estonia','EE',9,'Yes'),(71,'Ethiopia','ET',9,'Yes'),(72,'Falkland Islands (Malvinas)','FK',9,'Yes'),(73,'Faroe Islands','FO',9,'Yes'),(74,'Fiji','FJ',9,'Yes'),(75,'Finland','FI',9,'Yes'),(76,'France','FR',9,'Yes'),(77,'French Guiana','GF',9,'Yes'),(78,'French Polynesia','PF',9,'Yes'),(79,'French Southern Territories','TF',9,'Yes'),(80,'Gabon','GA',9,'Yes'),(81,'Gambia','GM',9,'Yes'),(82,'Georgia','GE',9,'Yes'),(83,'Germany','DE',9,'Yes'),(84,'Ghana','GH',9,'Yes'),(85,'Gibraltar','GI',9,'Yes'),(86,'Greece','GR',9,'Yes'),(87,'Greenland','GL',9,'Yes'),(88,'Grenada','GD',9,'Yes'),(89,'Guadeloupe','GP',9,'Yes'),(90,'Guam','GU',9,'Yes'),(91,'Guatemala','GT',9,'Yes'),(92,'Guernsey','GG',9,'Yes'),(93,'Guinea','GN',9,'Yes'),(94,'Guinea-Bissau','GW',9,'Yes'),(95,'Guyana','GY',9,'Yes'),(96,'Haiti','HT',9,'Yes'),(97,'Heard Island and McDonald Islands','HM',9,'Yes'),(98,'Holy See (Vatican City State)','VA',9,'Yes'),(99,'Honduras','HN',9,'Yes'),(100,'Hong Kong','HK',9,'Yes'),(101,'Hungary','HU',9,'Yes'),(102,'Iceland','IS',9,'Yes'),(103,'India','IN',9,'Yes'),(104,'Indonesia','ID',9,'Yes'),(105,'Iran, Islamic Republic of','IR',9,'Yes'),(106,'Iraq','IQ',9,'Yes'),(107,'Ireland','IE',9,'Yes'),(108,'Isle of Man','IM',9,'Yes'),(109,'Israel','IL',9,'Yes'),(110,'Italy','IT',9,'Yes'),(111,'Jamaica','JM',9,'Yes'),(112,'Japan','JP',9,'Yes'),(113,'Jersey','JE',9,'Yes'),(114,'Jordan','JO',9,'Yes'),(115,'Kazakhstan','KZ',9,'Yes'),(116,'Kenya','KE',9,'Yes'),(117,'Kiribati','KI',9,'Yes'),(118,'Korea, Republic of','KR',9,'Yes'),(119,'Kuwait','KW',9,'Yes'),(120,'Kyrgyzstan','KG',9,'Yes'),(121,'Latvia','LV',9,'Yes'),(122,'Lebanon','LB',9,'Yes'),(123,'Lesotho','LS',9,'Yes'),(124,'Liberia','LR',9,'Yes'),(125,'Libya','LY',9,'Yes'),(126,'Liechtenstein','LI',9,'Yes'),(127,'Lithuania','LT',9,'Yes'),(128,'Luxembourg','LU',9,'Yes'),(129,'Macao','MO',9,'Yes'),(130,'Macedonia, the Former Yugoslav Republic of','MK',9,'Yes'),(131,'Madagascar','MG',9,'Yes'),(132,'Malawi','MW',9,'Yes'),(133,'Malaysia','MY',9,'Yes'),(134,'Maldives','MV',9,'Yes'),(135,'Mali','ML',9,'Yes'),(136,'Malta','MT',9,'Yes'),(137,'Marshall Islands','MH',9,'Yes'),(138,'Martinique','MQ',9,'Yes'),(139,'Mauritania','MR',9,'Yes'),(140,'Mauritius','MU',9,'Yes'),(141,'Mayotte','YT',9,'Yes'),(142,'Mexico','MX',9,'Yes'),(143,'Micronesia, Federated States of','FM',9,'Yes'),(144,'Moldova, Republic of','MD',9,'Yes'),(145,'Monaco','MC',9,'Yes'),(146,'Mongolia','MN',9,'Yes'),(147,'Montenegro','ME',9,'Yes'),(148,'Montserrat','MS',9,'Yes'),(149,'Morocco','MA',9,'Yes'),(150,'Mozambique','MZ',9,'Yes'),(151,'Myanmar','MM',9,'Yes'),(152,'Namibia','NA',9,'Yes'),(153,'Nauru','NR',9,'Yes'),(154,'Nepal','NP',9,'Yes'),(155,'Netherlands','NL',9,'Yes'),(156,'New Caledonia','NC',9,'Yes'),(157,'New Zealand','NZ',2,'Yes'),(158,'Nicaragua','NI',9,'Yes'),(159,'Niger','NE',9,'Yes'),(160,'Nigeria','NG',9,'Yes'),(161,'Niue','NU',9,'Yes'),(162,'Norfolk Island','NF',9,'Yes'),(163,'Northern Mariana Islands','MP',9,'Yes'),(164,'Norway','NO',9,'Yes'),(165,'Oman','OM',9,'Yes'),(166,'Pakistan','PK',9,'Yes'),(167,'Palau','PW',9,'Yes'),(168,'Palestine, State of','PS',9,'Yes'),(169,'Panama','PA',9,'Yes'),(170,'Papua New Guinea','PG',9,'Yes'),(171,'Paraguay','PY',9,'Yes'),(172,'Peru','PE',9,'Yes'),(173,'Philippines','PH',9,'Yes'),(174,'Pitcairn','PN',9,'Yes'),(175,'Poland','PL',9,'Yes'),(176,'Portugal','PT',9,'Yes'),(177,'Puerto Rico','PR',9,'Yes'),(178,'Qatar','QA',9,'Yes'),(179,'Réunion','RE',9,'Yes'),(180,'Romania','RO',9,'Yes'),(181,'Russian Federation','RU',9,'Yes'),(182,'Rwanda','RW',9,'Yes'),(183,'Saint Barthélemy','BL',9,'Yes'),(184,'Saint Helena, Ascension and Tristan da Cunha','SH',9,'Yes'),(185,'Saint Kitts and Nevis','KN',9,'Yes'),(186,'Saint Lucia','LC',9,'Yes'),(187,'Saint Martin (French part)','MF',9,'Yes'),(188,'Saint Pierre and Miquelon','PM',9,'Yes'),(189,'Saint Vincent and the Grenadines','VC',9,'Yes'),(190,'Samoa','WS',9,'Yes'),(191,'San Marino','SM',9,'Yes'),(192,'Sao Tome and Principe','ST',9,'Yes'),(193,'Saudi Arabia','SA',9,'Yes'),(194,'Senegal','SN',9,'Yes'),(195,'Serbia','RS',9,'Yes'),(196,'Seychelles','SC',9,'Yes'),(197,'Sierra Leone','SL',9,'Yes'),(198,'Singapore','SG',9,'Yes'),(199,'Sint Maarten (Dutch part)','SX',9,'Yes'),(200,'Slovakia','SK',9,'Yes'),(201,'Slovenia','SI',9,'Yes'),(202,'Solomon Islands','SB',9,'Yes'),(203,'Somalia','SO',9,'Yes'),(204,'South Africa','ZA',9,'Yes'),(205,'South Georgia and the South Sandwich Islands','GS',9,'Yes'),(206,'South Sudan','SS',9,'Yes'),(207,'Spain','ES',9,'Yes'),(208,'Sri Lanka','LK',9,'Yes'),(209,'Sudan','SD',9,'Yes'),(210,'Suriname','SR',9,'Yes'),(211,'Svalbard and Jan Mayen','SJ',9,'Yes'),(212,'Swaziland','SZ',9,'Yes'),(213,'Sweden','SE',9,'Yes'),(214,'Switzerland','CH',9,'Yes'),(215,'Syrian Arab Republic','SY',9,'Yes'),(216,'Taiwan, Province of China','TW',9,'Yes'),(217,'Tajikistan','TJ',9,'Yes'),(218,'Tanzania, United Republic of','TZ',9,'Yes'),(219,'Thailand','TH',9,'Yes'),(220,'Timor-Leste','TL',9,'Yes'),(221,'Togo','TG',9,'Yes'),(222,'Tokelau','TK',9,'Yes'),(223,'Tonga','TO',9,'Yes'),(224,'Trinidad and Tobago','TT',9,'Yes'),(225,'Tunisia','TN',9,'Yes'),(226,'Turkey','TR',9,'Yes'),(227,'Turkmenistan','TM',9,'Yes'),(228,'Turks and Caicos Islands','TC',9,'Yes'),(229,'Tuvalu','TV',9,'Yes'),(230,'Uganda','UG',9,'Yes'),(231,'Ukraine','UA',9,'Yes'),(232,'United Arab Emirates','AE',9,'Yes'),(233,'United Kingdom','GB',3,'Yes'),(234,'United States','US',5,'Yes'),(235,'United States Minor Outlying Islands','UM',9,'Yes'),(236,'Uruguay','UY',9,'Yes'),(237,'Uzbekistan','UZ',9,'Yes'),(238,'Vanuatu','VU',9,'Yes'),(239,'Venezuela, Bolivarian Republic of','VE',9,'Yes'),(240,'Viet Nam','VN',9,'Yes'),(241,'Virgin Islands, British','VG',9,'Yes'),(242,'Virgin Islands, U.S.','VI',9,'Yes'),(243,'Wallis and Futuna','WF',9,'Yes'),(244,'Western Sahara','EH',9,'Yes'),(245,'Yemen','YE',9,'Yes'),(246,'Zambia','ZM',9,'Yes'),(247,'Zimbabwe','ZW',9,'Yes');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dncr_history`
--

DROP TABLE IF EXISTS `dncr_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dncr_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `on_dncr` tinyint(1) NOT NULL,
  `validation_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='DNCR History Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dncr_history`
--

LOCK TABLES `dncr_history` WRITE;
/*!40000 ALTER TABLE `dncr_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dncr_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domain_blacklist`
--

DROP TABLE IF EXISTS `domain_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domain_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Domain Blacklist Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domain_blacklist`
--

LOCK TABLES `domain_blacklist` WRITE;
/*!40000 ALTER TABLE `domain_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `domain_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_blacklist`
--

DROP TABLE IF EXISTS `email_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COMMENT='Email Blacklist Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_blacklist`
--

LOCK TABLES `email_blacklist` WRITE;
/*!40000 ALTER TABLE `email_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_history`
--

DROP TABLE IF EXISTS `email_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `validation_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Email History Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_history`
--

LOCK TABLES `email_history` WRITE;
/*!40000 ALTER TABLE `email_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fraud_detection`
--

DROP TABLE IF EXISTS `fraud_detection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fraud_detection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `client_id` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `received` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=utf8 COMMENT='Fraud Detection Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fraud_detection`
--

LOCK TABLES `fraud_detection` WRITE;
/*!40000 ALTER TABLE `fraud_detection` DISABLE KEYS */;
/*!40000 ALTER TABLE `fraud_detection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `source` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gender Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gender`
--

LOCK TABLES `gender` WRITE;
/*!40000 ALTER TABLE `gender` DISABLE KEYS */;
/*!40000 ALTER TABLE `gender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_audit`
--

DROP TABLE IF EXISTS `lead_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `source` varchar(255) NOT NULL,
  `client_id` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `received` datetime NOT NULL,
  `errors` blob,
  `disposition` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='Lead Audit Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_audit`
--

LOCK TABLES `lead_audit` WRITE;
/*!40000 ALTER TABLE `lead_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `lead_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lead_history`
--

DROP TABLE IF EXISTS `lead_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `campaign_id` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `client_duplicate` tinyint(1) NOT NULL,
  `delivered` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data` (`data`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='Lead History Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lead_history`
--

LOCK TABLES `lead_history` WRITE;
/*!40000 ALTER TABLE `lead_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `lead_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membrain_global_do_not_call`
--

DROP TABLE IF EXISTS `membrain_global_do_not_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membrain_global_do_not_call` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `reason_code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Membrain Global Do Not Call Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membrain_global_do_not_call`
--

LOCK TABLES `membrain_global_do_not_call` WRITE;
/*!40000 ALTER TABLE `membrain_global_do_not_call` DISABLE KEYS */;
/*!40000 ALTER TABLE `membrain_global_do_not_call` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multipleapi`
--

DROP TABLE IF EXISTS `multipleapi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multipleapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `country_code` varchar(50) NOT NULL,
  `apiurl` varchar(255) NOT NULL,
  `credentials_detaila` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Multiple Api Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multipleapi`
--

LOCK TABLES `multipleapi` WRITE;
/*!40000 ALTER TABLE `multipleapi` DISABLE KEYS */;
/*!40000 ALTER TABLE `multipleapi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `name_blacklist`
--

DROP TABLE IF EXISTS `name_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `name_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Name Blacklist Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `name_blacklist`
--

LOCK TABLES `name_blacklist` WRITE;
/*!40000 ALTER TABLE `name_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `name_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone_history`
--

DROP TABLE IF EXISTS `phone_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `is_valid` tinyint(1) NOT NULL,
  `validation_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Phone History Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone_history`
--

LOCK TABLES `phone_history` WRITE;
/*!40000 ALTER TABLE `phone_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portal_role`
--

DROP TABLE IF EXISTS `portal_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portal_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Portal Role';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portal_role`
--

LOCK TABLES `portal_role` WRITE;
/*!40000 ALTER TABLE `portal_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `portal_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portal_sub_client`
--

DROP TABLE IF EXISTS `portal_sub_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portal_sub_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `portal_user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Portal Sub Client';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portal_sub_client`
--

LOCK TABLES `portal_sub_client` WRITE;
/*!40000 ALTER TABLE `portal_sub_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `portal_sub_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `postcode`
--

DROP TABLE IF EXISTS `postcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) NOT NULL,
  `postcode` varchar(50) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Postcode Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `postcode`
--

LOCK TABLES `postcode` WRITE;
/*!40000 ALTER TABLE `postcode` DISABLE KEYS */;
/*!40000 ALTER TABLE `postcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `protal_user`
--

DROP TABLE IF EXISTS `protal_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `protal_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `reset_in_progress` tinyint(1) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COMMENT='Protal User';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `protal_user`
--

LOCK TABLES `protal_user` WRITE;
/*!40000 ALTER TABLE `protal_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `protal_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quarantines`
--

DROP TABLE IF EXISTS `quarantines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quarantines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Quarantine Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quarantines`
--

LOCK TABLES `quarantines` WRITE;
/*!40000 ALTER TABLE `quarantines` DISABLE KEYS */;
/*!40000 ALTER TABLE `quarantines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rapport_phone_history`
--

DROP TABLE IF EXISTS `rapport_phone_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rapport_phone_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `status_code` varchar(255) NOT NULL,
  `validation_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rapport Phone History Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rapport_phone_history`
--

LOCK TABLES `rapport_phone_history` WRITE;
/*!40000 ALTER TABLE `rapport_phone_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `rapport_phone_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salacious_word`
--

DROP TABLE IF EXISTS `salacious_word`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salacious_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pattern` varchar(255) NOT NULL,
  `email_address` tinyint(1) NOT NULL,
  `first_name` tinyint(1) NOT NULL,
  `last_name` tinyint(1) NOT NULL,
  `address` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Salacious Word Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salacious_word`
--

LOCK TABLES `salacious_word` WRITE;
/*!40000 ALTER TABLE `salacious_word` DISABLE KEYS */;
/*!40000 ALTER TABLE `salacious_word` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_client_link`
--

DROP TABLE IF EXISTS `supplier_client_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_client_link` (
  `supplier_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Supplier / Client Link Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_client_link`
--

LOCK TABLES `supplier_client_link` WRITE;
/*!40000 ALTER TABLE `supplier_client_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_client_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `error_allowance` int(11) DEFAULT NULL,
  `return_csv` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `public_id` (`public_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COMMENT='Suppliers Table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-16  6:41:21
