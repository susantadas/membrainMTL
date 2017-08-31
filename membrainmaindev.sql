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
INSERT INTO `alerts` VALUES (1,0,'File Quarantined – Excessive Errors','Supplier name - Supplier name:empty(because no supplier_id)Campaign name - KrishnaLClient name - Nitin','17b938b76022fa80dd1683050030e845_Return-CSV.csv','Krishna',0,'2017-08-11 23:59:29','2017-08-14 19:48:00'),(2,1,'File Quarantined – Excessive Errors','Supplier name - AvikCampaign name - testcampaignClient name - Nitin','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','Susanta Kumar Das',0,'2017-08-15 13:18:21','2017-08-15 13:31:00');
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
INSERT INTO `client_email_suppression` VALUES (1,1502468617,'ss@ss.com'),(2,1502468617,'ss1@ss.com'),(3,1502468617,'ss2@ss.com'),(4,1502468617,'gopi@gopi.com'),(5,1502468617,'gopi1@gopi.com'),(6,1502468617,'gopi2@gopi.com'),(7,1502468617,'gopi3@gopi.com'),(8,1502468617,'mns@mns.com');
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
INSERT INTO `client_phone_suppression` VALUES (1,1502466398,'1234567890'),(2,1502466398,'1234567891'),(3,1502466398,'1234567892'),(4,1502466398,'1234567899'),(5,1502466398,'1234567898'),(6,1502466398,'1234567897'),(7,1502466398,'1234567900');
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
INSERT INTO `clients` VALUES (1,'Nitin','nitin@mindtechlab.com','Nitin Hyd','7852149630',1,'US',1,1,30),(2,'Jon','jon@gmail.com','Jon Willium','4567891230',1,'US',0,0,1),(3,'Susanta','isusantakumardas45@gmail.com','Susanta Kumar Das','99887755667',1,'AU',0,0,90),(5,'Radha','radha@gmail.com','KrishnaQ','8686869646',1,'IN',0,0,10),(8,'Naresh','naresh@gmail.com','Bolgam naresh','7433264567',1,'IS',0,0,17),(9,'ramsri','ramsri@gmail.com','psr','9876556789',1,'IN',1,1,444),(10,'nikki','nikki@12gmail.com','s','9879879870',1,'IN',0,0,30),(12,'ravi','ravi56@gmail.com','ravi raj','9885613269',1,'IN',0,0,5),(13,'krishna s','krish64@gmail.com','krishna sk','9848056235',1,'IN',0,0,5),(14,'prasheel','prasheel12@gmail.com','prasheel m','8885556662',1,'SG',0,0,10),(15,'naveen','naveen222@gmail.com','naveen c','9874344334',1,'CH',0,0,2),(16,'raja singh','rajasingh12@gmail.com','rajasingh','9784567890',1,'IN',0,0,29),(17,'kishan reddy','kishanreddy34@gmail.com','kishan r','9885555666',1,'IN',0,0,9),(18,'harish rao','harishrao98@gmail.com','harish r','9666645457',1,'IN',0,0,22),(19,'rakesh m','rakim46@gmail.com','raki m','9966666669',1,'AT',0,0,18),(20,'ram','sri@123gmail.com','ram n','9111123656',1,'IN',0,0,29),(21,'Adithya sen','adithya@gmail.com','Adithya','9966603576',1,'MT',1,1,55),(23,'cnuc','cnu12@gmail.com','cnu s','7894512012',1,'IN',0,0,20),(24,'dheeraj','dheeraj54@gmail.com','dheeraj k','9123456789',1,'IS',0,0,10),(25,'eshwar','eshwar12@gmail.com','eshwar p','9966887745',1,'IN',1,1,30),(26,'lara','lara23@gmail.com','lara w','9873216540',1,'EH',0,0,23),(27,'manasa','manasa12@gmail.com','manasa s','7854612303',1,'MO',0,0,30),(28,'manoj','manoj54@gmail.com','manoj b','8754652123',1,'IS',0,0,6),(29,'nikki','nikki54@gmail.com','nikki s','9877893626',1,'IN',0,0,29),(30,'amitav','amitav@gmail.com','Amitav Dutta','7456981230',1,'IN',1,1,25),(32,'prasenti','prasenti@gmail.com','Prasenti','9999999999',1,'AU',1,1,90),(33,'Apple Inc','info@apple.com','St john','65432187688',1,'GB',1,1,20),(34,'Hcl','info@hcl.com','HCL','8585858585',1,'AU',1,1,75),(36,'Amazon','amazongoods@gmail.com','S Nikhil','9343654489',1,'HK',1,1,122),(37,'KIA Motors','ntnkumar513@gmail.com','Nithin','9703979087745',1,'IS',0,0,12456),(38,'TCS','Nikhilsaggam3@gmail.com','Anup','9874563210',1,'IN',1,1,123456789),(39,'avanti','hello@gmail.com','avantika','80948974329',1,'GH',1,1,999),(40,'Bigsolutions','nikhilmithra1@gmail.com','Nikhil','98787989504',1,'IN',1,1,968),(41,'Susanta','isusantakumardas451@gmail.com','Susanta Kumar Das','99887755667',1,'AU',0,0,90),(42,'Test','membrain@gmail.com','Cat','5468643456',1,'US',0,0,123),(43,'test cat','membrain_1@gmail.com','Test cat','67854334565',1,'AF',0,0,123),(44,'Testtwo','k@gmail.com','Tesr','98765432104',1,'AU',1,1,10),(45,'huyndai','nithin12@gmail.com','Nihtin huyndai','9703979087',1,'IN',1,1,123),(46,'mtlpvtltd','nikhilA21@gmail.com','nikhil','00615487845',1,'AU',1,1,11),(47,'Test cat','testcat@membrain.com','Cat','24793456961',1,'GB',0,0,27),(48,'Test cat a','testcata@membrain.com','Test cat a','09840167949',1,'IN',0,0,21),(50,'client','client12@gmail.com','nik','98765432111',1,'AU',1,1,111),(52,'tes','tester@gmail.com','tester','8686868686',1,'AU',1,1,30),(53,'IIIT','info@administratoriit.com','Chairman','9848984812',1,'IN',1,1,31),(55,'IIT Madras','info@iitmadras.com','Sunil shetty','8686323232',1,'AU',1,1,31),(56,'s','nikh.12@gmail.com','nik','123456879',1,'IN',1,1,100),(57,'sonam','sona.singh@gmail.com','Sonam','5643287609',0,'IS',1,1,76),(58,'ssss','sssss@gmail.com','sssss','99806655443',1,'AT',0,0,12),(59,'sonam','sss@gmail.com','sonam','23445789544',1,'IS',0,0,23),(60,'amazon','amzon@gmail.com','tin','987478950',1,'AU',1,1,123),(61,'nikil','nikhil21@gmail.com','nik','96385214781',1,'AU',0,0,321),(62,'Tajdeccan','info@tajdeccan.com','Gopi Krishna','04066553344',1,'IN',0,0,365);
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
INSERT INTO `email_blacklist` VALUES (1,'aa@aa.com','mnhcdsbv'),(2,'aa@aabbcc.com','mnhcdsbv'),(3,'krishna@gmail.com','mnhcdsbv'),(4,'krishna1@gmail.com','mnhcdsbv1'),(5,'krishna2@gmail.com','mnhcdsbv2'),(6,'krishna3@gmail.com','mnhcdsbv3'),(7,'krishna4@gmail.com','mnhcdsbv4'),(8,'krishna5@gmail.com','mnhcdsbv5'),(9,'krishna6@gmail.com','mnhcdsbv6'),(10,'krishna7@gmail.com','mnhcdsbv7'),(11,'krishna8@gmail.com','mnhcdsbv8'),(12,'krishna9@gmail.com','mnhcdsbv9'),(13,'krishna10@gmail.com','mnhcdsbv10'),(14,'krishna11@gmail.com','mnhcdsbv11'),(15,'krishna12@gmail.com','mnhcdsbv12'),(16,'krishna13@gmail.com','mnhcdsbv13'),(17,'krishna14@gmail.com','mnhcdsbv14'),(18,'krishna15@gmail.com','mnhcdsbv15'),(19,'krishna16@gmail.com','mnhcdsbv16'),(20,'krishna17@gmail.com','mnhcdsbv17'),(21,'krishna18@gmail.com','mnhcdsbv18'),(22,'krishna19@gmail.com','mnhcdsbv19'),(23,'krishna20@gmail.com','mnhcdsbv20'),(24,'krishna21@gmail.com','mnhcdsbv21'),(25,'krishna22@gmail.com','mnhcdsbv22'),(26,'krishna23@gmail.com','mnhcdsbv23'),(27,'krishna24@gmail.com','mnhcdsbv24'),(28,'krishna25@gmail.com','mnhcdsbv25'),(29,'krishna26@gmail.com','mnhcdsbv26'),(30,'krishna27@gmail.com','mnhcdsbv27'),(31,'krishna28@gmail.com','mnhcdsbv28'),(32,'krishna29@gmail.com','mnhcdsbv29'),(33,'krishna30@gmail.com','mnhcdsbv30'),(34,'krishna31@gmail.com','mnhcdsbv31'),(35,'krishna32@gmail.com','mnhcdsbv32'),(36,'krishna33@gmail.com','mnhcdsbv33'),(37,'krishna34@gmail.com','mnhcdsbv34'),(38,'krishna35@gmail.com','mnhcdsbv35'),(39,'krishna36@gmail.com','mnhcdsbv36'),(40,'krishna37@gmail.com','mnhcdsbv37'),(41,'krishna38@gmail.com','mnhcdsbv38'),(42,'krishna39@gmail.com','mnhcdsbv39'),(43,'krishna40@gmail.com','mnhcdsbv40'),(44,'krishna41@gmail.com','mnhcdsbv41'),(45,'krishna42@gmail.com','mnhcdsbv42'),(46,'krishna43@gmail.com','mnhcdsbv43'),(47,'krishna44@gmail.com','mnhcdsbv44'),(48,'krishna45@gmail.com','mnhcdsbv45'),(49,'krishna46@gmail.com','mnhcdsbv46'),(50,'krishna47@gmail.com','mnhcdsbv47'),(51,'krishna48@gmail.com','mnhcdsbv48'),(52,'krishna49@gmail.com','mnhcdsbv49'),(53,'krishna50@gmail.com','mnhcdsbv50'),(54,'krishna51@gmail.com','mnhcdsbv51'),(55,'krishna52@gmail.com','mnhcdsbv52'),(56,'krishna53@gmail.com','mnhcdsbv53'),(57,'krishna54@gmail.com','mnhcdsbv54'),(58,'krishna55@gmail.com','mnhcdsbv55'),(59,'krishna56@gmail.com','mnhcdsbv56'),(60,'krishna57@gmail.com','mnhcdsbv57'),(61,'krishna58@gmail.com','mnhcdsbv58'),(62,'krishna59@gmail.com','mnhcdsbv59'),(63,'krishna60@gmail.com','mnhcdsbv60'),(64,'krishna61@gmail.com','mnhcdsbv61'),(65,'krishna62@gmail.com','mnhcdsbv62'),(66,'krishna63@gmail.com','mnhcdsbv63'),(67,'krishna64@gmail.com','mnhcdsbv64'),(68,'krishna65@gmail.com','mnhcdsbv65'),(69,'krishna66@gmail.com','mnhcdsbv66'),(70,'krishna67@gmail.com','mnhcdsbv67'),(71,'krishna68@gmail.com','mnhcdsbv68'),(72,'krishna69@gmail.com','mnhcdsbv69'),(73,'krishna70@gmail.com','mnhcdsbv70'),(74,'krishna71@gmail.com','mnhcdsbv71'),(75,'krishna72@gmail.com','mnhcdsbv72'),(76,'krishna73@gmail.com','mnhcdsbv73'),(77,'krishna74@gmail.com','mnhcdsbv74'),(78,'krishna75@gmail.com','mnhcdsbv75'),(79,'krishna76@gmail.com','mnhcdsbv76'),(80,'krishna77@gmail.com','mnhcdsbv77'),(81,'krishna78@gmail.com','mnhcdsbv78'),(82,'krishna79@gmail.com','mnhcdsbv79'),(83,'krishna80@gmail.com','mnhcdsbv80'),(84,'krishna81@gmail.com','mnhcdsbv81'),(85,'krishna82@gmail.com','mnhcdsbv82'),(86,'krishna83@gmail.com','mnhcdsbv83'),(87,'krishna84@gmail.com','mnhcdsbv84'),(88,'krishna85@gmail.com','mnhcdsbv85'),(89,'krishna86@gmail.com','mnhcdsbv86'),(90,'krishna87@gmail.com','mnhcdsbv87'),(91,'krishna88@gmail.com','mnhcdsbv88'),(92,'krishna89@gmail.com','mnhcdsbv89'),(93,'krishna90@gmail.com','mnhcdsbv90'),(94,'krishna91@gmail.com','mnhcdsbv91'),(95,'krishna92@gmail.com','mnhcdsbv92'),(96,'krishna93@gmail.com','mnhcdsbv93'),(97,'krishna94@gmail.com','mnhcdsbv94'),(98,'krishna95@gmail.com','mnhcdsbv95'),(99,'krishna96@gmail.com','mnhcdsbv96'),(100,'krishna97@gmail.com','mnhcdsbv97'),(101,'krishna98@gmail.com','mnhcdsbv98'),(102,'krishna99@gmail.com','mnhcdsbv99'),(103,'krishna100@gmail.com','mnhcdsbv100'),(104,'krishna101@gmail.com','mnhcdsbv101'),(105,'aa@aasa.com','mnhcdsbv'),(106,'aa@aabasbcc.com','mnhcdsbv'),(107,'krishna@qgmail.com','mnhcdsbv'),(108,'krishna1@asgmail.com','mnhcdsbv1'),(109,'krishna2@as1gmail.com','mnhcdsbv2');
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
INSERT INTO `email_history` VALUES (1,'ezra1275@gmail.com',0,'2017-08-14'),(2,'yvonneharding@gmail.com',1,'2017-08-15'),(3,'manveer0806@gmail.com',1,'2017-08-15'),(4,'roxannepeneha@gmail.com',1,'2017-08-15'),(5,'kimmariestewart64@gmail.com',1,'2017-08-15'),(6,'prophets@clear.net.nz',1,'2017-08-15'),(7,'kanwaljot_sidhu@yahoo.co.in',1,'2017-08-15'),(8,'Honourkelly11@gmail.com',1,'2017-08-15'),(9,'jaybee5980@gmail.com',1,'2017-08-15'),(10,'caroline.heta@gmail.com',1,'2017-08-15'),(11,'tinybaegldc4@gmail.com',1,'2017-08-15'),(12,'dsgrace56@gmail.com',1,'2017-08-15');
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
INSERT INTO `fraud_detection` VALUES (1,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mark','Mr','4132','','','2017-08-11 23:59:27'),(2,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dannii','Mrs','3802','','','2017-08-11 23:59:27'),(3,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Gina','Mrs','2850','','','2017-08-11 23:59:27'),(4,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Stephen','Mr','2539','','','2017-08-11 23:59:27'),(5,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sean','Mr','2017','','','2017-08-11 23:59:27'),(6,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Joeleen','Mrs','2463','','','2017-08-11 23:59:27'),(7,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Janice','Mrs','2298','','','2017-08-11 23:59:27'),(8,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Patrick James','Mr','4014','','','2017-08-11 23:59:27'),(9,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ayen','Mrs','2145','','','2017-08-11 23:59:27'),(10,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cheryl','Mrs','2304','','','2017-08-11 23:59:27'),(11,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Charmaine','Mrs','2766','','','2017-08-11 23:59:27'),(12,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Clinton','Mr','4825','','','2017-08-11 23:59:27'),(13,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cameron','Mr','3561','','','2017-08-11 23:59:27'),(14,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jay','Mr','4505','','','2017-08-11 23:59:27'),(15,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Keith','Mr','2470','','','2017-08-11 23:59:27'),(16,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Benjamin','Mr','6710','','','2017-08-11 23:59:27'),(17,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ian','Mr','2782','','','2017-08-11 23:59:27'),(18,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wei','Mrs','6108','','','2017-08-11 23:59:27'),(19,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Paul','Mr','6025','','','2017-08-11 23:59:27'),(20,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jason','Mr','2530','','','2017-08-11 23:59:27'),(21,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melanie','Mrs','2500','','','2017-08-11 23:59:27'),(22,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Emma','Mrs','3180','','','2017-08-11 23:59:27'),(23,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Elizabeth','Mrs','3690','','','2017-08-11 23:59:27'),(24,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sonja','Mrs','3199','','','2017-08-11 23:59:27'),(25,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Geoff','Mr','3802','','','2017-08-11 23:59:27'),(26,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Terry','Mr','4075','','','2017-08-11 23:59:27'),(27,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ricky','Mr','3803','','','2017-08-11 23:59:27'),(28,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mary','Mrs','4503','','','2017-08-11 23:59:27'),(29,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Shannon','Mrs','2760','','','2017-08-11 23:59:27'),(30,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tania','Mrs','3222','','','2017-08-11 23:59:27'),(31,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wally','Mr','3075','','','2017-08-11 23:59:27'),(32,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Nova','Mrs','3913','','','2017-08-11 23:59:27'),(33,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Andre','Mr','6167','','','2017-08-11 23:59:27'),(34,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Liane','Mrs','4215','','','2017-08-11 23:59:27'),(35,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Belinda','Mrs','3219','','','2017-08-11 23:59:27'),(36,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kassie','Mrs','2444','','','2017-08-11 23:59:27'),(37,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Anne','Mrs','5263','','','2017-08-11 23:59:27'),(38,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Emma','Mrs','2203','','','2017-08-11 23:59:27'),(39,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Liya','Mrs','2147','','','2017-08-11 23:59:27'),(40,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Galbada Arachchige','Mr','3175','','','2017-08-11 23:59:27'),(41,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Joe','Mr','3179','','','2017-08-11 23:59:27'),(42,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Brendan','Mr','6030','','','2017-08-11 23:59:27'),(43,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sedat','Mr','2148','','','2017-08-11 23:59:27'),(44,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','5083','','','2017-08-11 23:59:27'),(45,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Amy','Mrs','5163','','','2017-08-11 23:59:27'),(46,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Craig','Mr','3555','','','2017-08-11 23:59:27'),(47,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Teresita','Mrs','3128','','','2017-08-11 23:59:27'),(48,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cara','Mrs','2447','','','2017-08-11 23:59:27'),(49,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sharon','Mrs','4221','','','2017-08-11 23:59:27'),(50,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jules','Mrs','4110','','','2017-08-11 23:59:27'),(51,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rebecca','Mrs','4370','','','2017-08-11 23:59:27'),(52,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alana','Mrs','3038','','','2017-08-11 23:59:27'),(53,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Janelle','Mrs','6066','','','2017-08-11 23:59:27'),(54,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Shirley','Mrs','6059','','','2017-08-11 23:59:27'),(55,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Raymond','Mr','6110','','','2017-08-11 23:59:27'),(56,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Liz','Mrs','3083','','','2017-08-11 23:59:27'),(57,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alison','Mrs','5460','','','2017-08-11 23:59:27'),(58,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Maria','Mrs','3172','','','2017-08-11 23:59:27'),(59,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rodrigo','Mr','2217','','','2017-08-11 23:59:27'),(60,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tiffany','Mrs','3153','','','2017-08-11 23:59:27'),(61,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Gary','Mr','5037','','','2017-08-11 23:59:27'),(62,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kristi','Mrs','2770','','','2017-08-11 23:59:27'),(63,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Harley','Mr','4151','','','2017-08-11 23:59:27'),(64,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Michael','Mr','3006','','','2017-08-11 23:59:27'),(65,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Josh','Mr','6063','','','2017-08-11 23:59:27'),(66,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','6210','','','2017-08-11 23:59:27'),(67,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Belle','Mrs','4132','','','2017-08-11 23:59:27'),(68,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alicia','Mrs','2767','','','2017-08-11 23:59:27'),(69,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','2464','','','2017-08-11 23:59:27'),(70,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Gary','Mr','4152','','','2017-08-11 23:59:27'),(71,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kylie','Mrs','3752','','','2017-08-11 23:59:27'),(72,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sheree','Mrs','4123','','','2017-08-11 23:59:27'),(73,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Raul','Mr','3030','','','2017-08-11 23:59:27'),(74,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Stephen','Mr','3521','','','2017-08-11 23:59:27'),(75,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Roxanne','Mrs','5113','','','2017-08-11 23:59:27'),(76,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Pable','Mrs','5067','','','2017-08-11 23:59:27'),(77,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mary','Mrs','6164','','','2017-08-11 23:59:27'),(78,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kylie','Mrs','4030','','','2017-08-11 23:59:27'),(79,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jay','Mr','2197','','','2017-08-11 23:59:27'),(80,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Susanne','Mrs','4509','','','2017-08-11 23:59:27'),(81,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alyssa','Mrs','5070','','','2017-08-11 23:59:27'),(82,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Damian','Mr','4413','','','2017-08-11 23:59:27'),(83,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Josh','Mr','2569','','','2017-08-11 23:59:27'),(84,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Felicity','Mrs','4305','','','2017-08-11 23:59:27'),(85,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sharon','Mrs','4517','','','2017-08-11 23:59:27'),(86,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Marcia','Mrs','5125','','','2017-08-11 23:59:27'),(87,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ryan','Mr','4216','','','2017-08-11 23:59:27'),(88,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dianne','Mrs','2852','','','2017-08-11 23:59:27'),(89,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','3350','','','2017-08-11 23:59:27'),(90,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Amanda','Mrs','2322','','','2017-08-11 23:59:27'),(91,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Boguslawa','Mrs','2560','','','2017-08-11 23:59:27'),(92,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Joramy','Mrs','4184','','','2017-08-11 23:59:27'),(93,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Justine','Mrs','4506','','','2017-08-11 23:59:27'),(94,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','An','Mrs','2428','','','2017-08-11 23:59:27'),(95,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Amanda','Mrs','3555','','','2017-08-11 23:59:27'),(96,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sharon','Mrs','2155','','','2017-08-11 23:59:27'),(97,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Brett','Mr','5007','','','2017-08-11 23:59:27'),(98,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sean','Mr','5173','','','2017-08-11 23:59:27'),(99,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','4736','','','2017-08-11 23:59:27'),(100,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cassandra','Mrs','5074','','','2017-08-11 23:59:27'),(101,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kristy','Mrs','2028','','','2017-08-11 23:59:27'),(102,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Carolina','Mrs','2039','','','2017-08-11 23:59:27'),(103,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Paula','Mrs','4078','','','2017-08-11 23:59:27'),(104,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Deanne','Mrs','6209','','','2017-08-11 23:59:27'),(105,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Leticia','Mrs','4077','','','2017-08-11 23:59:27'),(106,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Francis','Mr','4215','','','2017-08-11 23:59:27'),(107,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Heidi','Mrs','4127','','','2017-08-11 23:59:27'),(108,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Gloria','Mrs','2112','','','2017-08-11 23:59:27'),(109,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mar','Mr','5038','','','2017-08-11 23:59:27'),(110,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ajit','Mr','2145','','','2017-08-11 23:59:27'),(111,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Edwin','Mr','3129','','','2017-08-11 23:59:27'),(112,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lisa','Mrs','4510','','','2017-08-11 23:59:27'),(113,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tracey','Mrs','3630','','','2017-08-11 23:59:27'),(114,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Enrico','Mr','2223','','','2017-08-11 23:59:27'),(115,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Brigette','Mrs','2830','','','2017-08-11 23:59:27'),(116,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kerry-Ann','Mrs','4209','','','2017-08-11 23:59:27'),(117,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jim','Mr','4006','','','2017-08-11 23:59:27'),(118,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','2146','','','2017-08-11 23:59:27'),(119,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Gordon','Mr','3215','','','2017-08-11 23:59:27'),(120,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Greg','Mr','2259','','','2017-08-11 23:59:27'),(121,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Adam','Mr','3915','','','2017-08-11 23:59:27'),(122,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melissa','Mrs','2502','','','2017-08-11 23:59:27'),(123,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kerryanne','Mrs','2323','','','2017-08-11 23:59:27'),(124,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Nat','Mrs','3218','','','2017-08-11 23:59:27'),(125,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','William','Mr','2582','','','2017-08-11 23:59:27'),(126,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Scott','Mr','2263','','','2017-08-11 23:59:27'),(127,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dean','Mr','2260','','','2017-08-11 23:59:27'),(128,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Carlo','Mr','3818','','','2017-08-11 23:59:27'),(129,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','4343','','','2017-08-11 23:59:27'),(130,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Bruno','Mr','2171','','','2017-08-11 23:59:27'),(131,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tayla','Mrs','2323','','','2017-08-11 23:59:27'),(132,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melissa','Mrs','2290','','','2017-08-11 23:59:27'),(133,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jan','Mrs','2352','','','2017-08-11 23:59:27'),(134,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kaye','Mrs','3338','','','2017-08-11 23:59:27'),(135,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Vina','Mrs','3006','','','2017-08-11 23:59:27'),(136,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Nicole','Mrs','4300','','','2017-08-11 23:59:27'),(137,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Coralie','Mrs','2208','','','2017-08-11 23:59:27'),(138,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dean','Mr','6210','','','2017-08-11 23:59:27'),(139,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Carl','Mr','4802','','','2017-08-11 23:59:27'),(140,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Vee','Mrs','6059','','','2017-08-11 23:59:27'),(141,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ben','Mr','3875','','','2017-08-11 23:59:27'),(142,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Troy','Mr','5108','','','2017-08-11 23:59:27'),(143,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rony','Mr','3172','','','2017-08-11 23:59:27'),(144,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sean','Mrs','6061','','','2017-08-11 23:59:27'),(145,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Margaret','Mrs','4802','','','2017-08-11 23:59:27'),(146,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Julie','Mrs','4159','','','2017-08-11 23:59:27'),(147,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Felicia','Mrs','3082','','','2017-08-11 23:59:27'),(148,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Penni','Mrs','6105','','','2017-08-11 23:59:27'),(149,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Aaron','Mr','3131','','','2017-08-11 23:59:27'),(150,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rachel','Mrs','4207','','','2017-08-11 23:59:27'),(151,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sose','Mrs','6285','','','2017-08-11 23:59:27'),(152,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lincoln','Mr','6024','','','2017-08-11 23:59:27'),(153,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Annette','Mrs','2550','','','2017-08-11 23:59:28'),(154,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Steven','Mr','4020','','','2017-08-11 23:59:28'),(155,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Leigh','Mrs','6056','','','2017-08-11 23:59:28'),(156,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kyle','Mr','4215','','','2017-08-11 23:59:28'),(157,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Matty','Mr','4118','','','2017-08-11 23:59:28'),(158,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Karen','Mrs','2622','','','2017-08-11 23:59:28'),(159,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Elaine','Mrs','2036','','','2017-08-11 23:59:28'),(160,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melanie','Mrs','3805','','','2017-08-11 23:59:28'),(161,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lisa','Mrs','4888','','','2017-08-11 23:59:28'),(162,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Molly','Ms','6104','','','2017-08-11 23:59:28'),(163,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Greg Peter','Mr','2087','','','2017-08-11 23:59:28'),(164,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wairakau','Mrs','6111','','','2017-08-11 23:59:28'),(165,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Natasha','Mrs','6728','','','2017-08-11 23:59:28'),(166,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Natasha','Mrs','7016','','','2017-08-11 23:59:28'),(167,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Bertus','Mr','5160','','','2017-08-11 23:59:28'),(168,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Terry','Mr','2017','','','2017-08-11 23:59:28'),(169,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Natalia','Mrs','4508','','','2017-08-11 23:59:28'),(170,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Martin','Mr','2388','','','2017-08-11 23:59:28'),(171,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Maryjane','Mrs','2429','','','2017-08-11 23:59:28'),(172,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Hellena','Mrs','5112','','','2017-08-11 23:59:28'),(173,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','George','Mr','2517','','','2017-08-11 23:59:28'),(174,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tracey','Mrs','7016','','','2017-08-11 23:59:28'),(175,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Sheila','Mrs','2151','','','2017-08-11 23:59:28'),(176,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cameron','Mr','3018','','','2017-08-11 23:59:28'),(177,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Leanne','Mrs','2168','','','2017-08-11 23:59:28'),(178,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Emma','Mrs','2471','','','2017-08-11 23:59:28'),(179,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Norma','Mrs','2195','','','2017-08-11 23:59:28'),(180,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Nicole','Mrs','4216','','','2017-08-11 23:59:28'),(181,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Zakazakaarcher','Mrs','4214','','','2017-08-11 23:59:28'),(182,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Keith','Mr','4560','','','2017-08-11 23:59:28'),(183,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Foen Tjin','Mrs','2020','','','2017-08-11 23:59:28'),(184,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Deborah','Mrs','2747','','','2017-08-11 23:59:28'),(185,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Abdi','Mr','6102','','','2017-08-11 23:59:28'),(186,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Michelle','Mrs','5022','','','2017-08-11 23:59:28'),(187,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kevin','Mr','2560','','','2017-08-11 23:59:28'),(188,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Chad','Mr','2284','','','2017-08-11 23:59:28'),(189,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cory','Mrs','2426','','','2017-08-11 23:59:28'),(190,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Reg','Mr','4655','','','2017-08-11 23:59:28'),(191,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Katrina','Mrs','4132','','','2017-08-11 23:59:28'),(192,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kerry','Mrs','4570','','','2017-08-11 23:59:28'),(193,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Niall','Mr','2232','','','2017-08-11 23:59:28'),(194,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','2218','','','2017-08-11 23:59:28'),(195,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ruth','Mrs','7018','','','2017-08-11 23:59:28'),(196,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alan','Mr','4215','','','2017-08-11 23:59:28'),(197,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','2023','','','2017-08-11 23:59:28'),(198,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Bob','Mr','2423','','','2017-08-11 23:59:28'),(199,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Deljeana','Mrs','4410','','','2017-08-11 23:59:28'),(200,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Thomas','Mr','2256','','','2017-08-11 23:59:28'),(201,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','2570','','','2017-08-11 23:59:28'),(202,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Louis','Mr','2775','','','2017-08-11 23:59:28'),(203,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Delmae','Mrs','3799','','','2017-08-11 23:59:28'),(204,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Stephen','Mr','3030','','','2017-08-11 23:59:28'),(205,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Michelle','Mrs','6064','','','2017-08-11 23:59:28'),(206,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Andrew','Mr','2770','','','2017-08-11 23:59:28'),(207,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Syd And Pam','Mr','6317','','','2017-08-11 23:59:28'),(208,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wayne','Mr','6164','','','2017-08-11 23:59:28'),(209,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Simon','Mr','2428','','','2017-08-11 23:59:28'),(210,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Beth','Mrs','4285','','','2017-08-11 23:59:28'),(211,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Reupena','Mr','2902','','','2017-08-11 23:59:28'),(212,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Marissa','Mrs','2213','','','2017-08-11 23:59:28'),(213,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rene','Mrs','2213','','','2017-08-11 23:59:28'),(214,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Baljit','Mrs','3064','','','2017-08-11 23:59:28'),(215,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Frederick','Mr','4850','','','2017-08-11 23:59:28'),(216,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dianne','Mrs','3934','','','2017-08-11 23:59:28'),(217,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Janet','Mrs','4350','','','2017-08-11 23:59:28'),(218,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Roger','Mr','3741','','','2017-08-11 23:59:28'),(219,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Deanna','Mrs','4178','','','2017-08-11 23:59:28'),(220,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Glynis','Mrs','6062','','','2017-08-11 23:59:28'),(221,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lean Imm','Mrs','3029','','','2017-08-11 23:59:28'),(222,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Heather','Mrs','2224','','','2017-08-11 23:59:28'),(223,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Stephen','Mr','2066','','','2017-08-11 23:59:28'),(224,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Vidhya','Mrs','2767','','','2017-08-11 23:59:28'),(225,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lita','Mrs','4169','','','2017-08-11 23:59:28'),(226,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Philip','Mr','4112','','','2017-08-11 23:59:28'),(227,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tegan','Mrs','2214','','','2017-08-11 23:59:28'),(228,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Natalie','Mrs','2528','','','2017-08-11 23:59:28'),(229,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kristine','Mrs','5063','','','2017-08-11 23:59:28'),(230,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wendy','Mrs','2190','','','2017-08-11 23:59:28'),(231,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Antony','Mr','3666','','','2017-08-11 23:59:28'),(232,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Cheryl','Mrs','2256','','','2017-08-11 23:59:28'),(233,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Analyn','Mrs','3223','','','2017-08-11 23:59:28'),(234,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Eric Jose','Mr','6502','','','2017-08-11 23:59:28'),(235,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rangi Awatea','Mrs','3012','','','2017-08-11 23:59:28'),(236,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','2154','','','2017-08-11 23:59:28'),(237,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Clive','Mr','2485','','','2017-08-11 23:59:28'),(238,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Graham','Mr','2137','','','2017-08-11 23:59:28'),(239,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','5159','','','2017-08-11 23:59:28'),(240,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Brian','Mr','4306','','','2017-08-11 23:59:28'),(241,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jack','Mr','5049','','','2017-08-11 23:59:28'),(242,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Olivia','Mrs','7467','','','2017-08-11 23:59:28'),(243,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Shane','Mr','7011','','','2017-08-11 23:59:28'),(244,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kamaljit Singh','Mr','2118','','','2017-08-11 23:59:28'),(245,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Maurice','Mr','2852','','','2017-08-11 23:59:28'),(246,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Chayaphat','Mrs','2220','','','2017-08-11 23:59:28'),(247,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Paula','Mrs','4078','','','2017-08-11 23:59:28'),(248,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Angela','Mrs','2747','','','2017-08-11 23:59:28'),(249,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Renee','Mrs','2304','','','2017-08-11 23:59:28'),(250,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lisa','Mrs','6111','','','2017-08-11 23:59:28'),(251,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kevin','Mr','2022','','','2017-08-11 23:59:28'),(252,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dina','Mrs','2343','','','2017-08-11 23:59:28'),(253,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Frances','Mrs','5114','','','2017-08-11 23:59:28'),(254,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jonathan','Mr','4573','','','2017-08-11 23:59:28'),(255,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','James','Mr','5085','','','2017-08-11 23:59:28'),(256,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Roy','Mr','4152','','','2017-08-11 23:59:28'),(257,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Richard','Mr','6290','','','2017-08-11 23:59:28'),(258,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Bevie','Mrs','3977','','','2017-08-11 23:59:28'),(259,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Wayne','Mr','2641','','','2017-08-11 23:59:28'),(260,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Daniel','Mr','812','','','2017-08-11 23:59:28'),(261,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','3021','','','2017-08-11 23:59:28'),(262,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tanya','Mrs','6432','','','2017-08-11 23:59:28'),(263,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ray','Mr','3029','','','2017-08-11 23:59:28'),(264,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dwayne','Mr','4060','','','2017-08-11 23:59:28'),(265,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Retemou','Mrs','4816','','','2017-08-11 23:59:28'),(266,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ryan','Mr','2558','','','2017-08-11 23:59:28'),(267,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Taimi','Mrs','2256','','','2017-08-11 23:59:28'),(268,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','3840','','','2017-08-11 23:59:28'),(269,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','2033','','','2017-08-11 23:59:28'),(270,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Matthew','Mr','2251','','','2017-08-11 23:59:28'),(271,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Helen','Mrs','2210','','','2017-08-11 23:59:28'),(272,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lou','Mr','6125','','','2017-08-11 23:59:28'),(273,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Daniel','Mr','4737','','','2017-08-11 23:59:28'),(274,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Oghenetejiri','Mrs','4214','','','2017-08-11 23:59:28'),(275,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jacqueline','Mrs','3029','','','2017-08-11 23:59:28'),(276,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Pamela','Mrs','4217','','','2017-08-11 23:59:28'),(277,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mark','Mr','4105','','','2017-08-11 23:59:28'),(278,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Thi','Mrs','2170','','','2017-08-11 23:59:28'),(279,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lyndall','Mrs','2289','','','2017-08-11 23:59:28'),(280,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Spiros','Mr','4067','','','2017-08-11 23:59:28'),(281,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melissa','Mrs','2322','','','2017-08-11 23:59:28'),(282,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Avantha','Mr','3169','','','2017-08-11 23:59:28'),(283,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Margaret','Mrs','3038','','','2017-08-11 23:59:28'),(284,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Nahida','Mrs','2196','','','2017-08-11 23:59:28'),(285,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kylie','Mrs','5108','','','2017-08-11 23:59:28'),(286,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Fiona','Mrs','4133','','','2017-08-11 23:59:28'),(287,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lauren','Mrs','2324','','','2017-08-11 23:59:28'),(288,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ali','Mr','2161','','','2017-08-11 23:59:28'),(289,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Leslie','Mr','4152','','','2017-08-11 23:59:28'),(290,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Henry','Mr','5086','','','2017-08-11 23:59:28'),(291,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jill','Mrs','2480','','','2017-08-11 23:59:28'),(292,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Beverlee','Mrs','4506','','','2017-08-11 23:59:28'),(293,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Shanna','Mrs','2164','','','2017-08-11 23:59:28'),(294,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Mary Jane','Mrs','2035','','','2017-08-11 23:59:28'),(295,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peta','Mrs','4000','','','2017-08-11 23:59:28'),(296,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Foday','Mr','6163','','','2017-08-11 23:59:28'),(297,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ann','Mrs','5169','','','2017-08-11 23:59:28'),(298,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Antonio Jr.','Mr','4103','','','2017-08-11 23:59:28'),(299,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Helen','Mrs','4053','','','2017-08-11 23:59:28'),(300,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Timoteo','Mr','2560','','','2017-08-11 23:59:28'),(301,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dale','Mrs','4805','','','2017-08-11 23:59:28'),(302,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jessica','Mrs','5052','','','2017-08-11 23:59:28'),(303,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tracie','Mrs','2250','','','2017-08-11 23:59:28'),(304,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Seung Hee','Mrs','2150','','','2017-08-11 23:59:28'),(305,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Pat','Mrs','3193','','','2017-08-11 23:59:28'),(306,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Harry','Mr','2508','','','2017-08-11 23:59:28'),(307,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Pamela','Mrs','6065','','','2017-08-11 23:59:28'),(308,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Donald','Mr','2570','','','2017-08-11 23:59:28'),(309,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Kerri','Mrs','3981','','','2017-08-11 23:59:28'),(310,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Graeme','Mr','2483','','','2017-08-11 23:59:28'),(311,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Naomi','Mrs','2168','','','2017-08-11 23:59:28'),(312,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','5161','','','2017-08-11 23:59:28'),(313,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lyle','Mr','2545','','','2017-08-11 23:59:28'),(314,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Debra','Mrs','2790','','','2017-08-11 23:59:28'),(315,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Belinda','Mrs','3175','','','2017-08-11 23:59:28'),(316,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Catherine','Mrs','7000','','','2017-08-11 23:59:28'),(317,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Natasha','Mrs','6064','','','2017-08-11 23:59:28'),(318,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Aleksander','Mr','4566','','','2017-08-11 23:59:28'),(319,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tun','Mr','2142','','','2017-08-11 23:59:28'),(320,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Travis','Mr','6210','','','2017-08-11 23:59:28'),(321,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Tania','Mrs','2160','','','2017-08-11 23:59:28'),(322,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Jude','Mrs','2804','','','2017-08-11 23:59:28'),(323,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Ian','Mr','3984','','','2017-08-11 23:59:28'),(324,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Douglas','Mr','5353','','','2017-08-11 23:59:28'),(325,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Samina','Mrs','2564','','','2017-08-11 23:59:29'),(326,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','6171','','','2017-08-11 23:59:29'),(327,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Alan','Mr','2324','','','2017-08-11 23:59:29'),(328,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Melissa','Mrs','4305','','','2017-08-11 23:59:29'),(329,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Therese','Mrs','4165','','','2017-08-11 23:59:29'),(330,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','George','Mr','2161','','','2017-08-11 23:59:29'),(331,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','William','Mr','4556','','','2017-08-11 23:59:29'),(332,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Walid','Mr','2142','','','2017-08-11 23:59:29'),(333,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lealofi','Mrs','2539','','','2017-08-11 23:59:29'),(334,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Anouk','Mrs','3162','','','2017-08-11 23:59:29'),(335,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Beryl','Mrs','4171','','','2017-08-11 23:59:29'),(336,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Seymour','Mr','2108','','','2017-08-11 23:59:29'),(337,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Frank','Mr','4875','','','2017-08-11 23:59:29'),(338,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lslita','Mrs','2035','','','2017-08-11 23:59:29'),(339,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','2020','','','2017-08-11 23:59:29'),(340,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Paul','Mr','4077','','','2017-08-11 23:59:29'),(341,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Xujuan','Mrs','4103','','','2017-08-11 23:59:29'),(342,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Delphena','Mrs','4816','','','2017-08-11 23:59:29'),(343,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rateb','Mr','6062','','','2017-08-11 23:59:29'),(344,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Carl','Mr','3195','','','2017-08-11 23:59:29'),(345,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Byron','Mr','2200','','','2017-08-11 23:59:29'),(346,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lubica','Mrs','2170','','','2017-08-11 23:59:29'),(347,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Josephine','Mrs','5019','','','2017-08-11 23:59:29'),(348,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','John','Mr','3183','','','2017-08-11 23:59:29'),(349,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Marie','Mrs','2795','','','2017-08-11 23:59:29'),(350,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dorothy','Mrs','2850','','','2017-08-11 23:59:29'),(351,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lee','Mr','2747','','','2017-08-11 23:59:29'),(352,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Bernd','Mr','2611','','','2017-08-11 23:59:29'),(353,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Peter','Mr','3981','','','2017-08-11 23:59:29'),(354,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Joanne','Mrs','6167','','','2017-08-11 23:59:29'),(355,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Arie','Mr','2779','','','2017-08-11 23:59:29'),(356,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Marciano','Mr','4173','','','2017-08-11 23:59:29'),(357,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Teresita','Mrs','2650','','','2017-08-11 23:59:29'),(358,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','William','Mr','4217','','','2017-08-11 23:59:29'),(359,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Patricia','Mrs','4305','','','2017-08-11 23:59:29'),(360,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dorica','Mrs','3205','','','2017-08-11 23:59:29'),(361,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Warren','Mr','2323','','','2017-08-11 23:59:29'),(362,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Joyce','Mrs','3804','','','2017-08-11 23:59:29'),(363,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Max','Mr','7304','','','2017-08-11 23:59:29'),(364,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Lyndsay','Mr','7010','','','2017-08-11 23:59:29'),(365,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Rosabelle','Mrs','820','','','2017-08-11 23:59:29'),(366,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Brad','Mr','2830','','','2017-08-11 23:59:29'),(367,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Dianne','Mrs','3023','','','2017-08-11 23:59:29'),(368,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Doug','Mr','2026','','','2017-08-11 23:59:29'),(369,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','Patricia','Mrs','4505','','','2017-08-11 23:59:29'),(370,0,'17b938b76022fa80dd1683050030e845_11082017.txt',1,'10','David','Mr','6036','','','2017-08-11 23:59:29'),(371,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_14082017.txt',1,'52','alexshilpikhera@gmail.com','21831955','Alex','Khera','6035','2017-08-14 22:05:03'),(372,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_14082017.txt',1,'52','ankur.icai37@gmail.com','226270573','Ankur','Patel','1025','2017-08-14 22:05:03'),(373,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','alexshilpikhera@gmail.com','21831955','Alex','Khera','6035','2017-08-15 13:05:22'),(374,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ankur.icai37@gmail.com','226270573','Ankur','Patel','1025','2017-08-15 13:05:22'),(375,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','alexshilpikhera@gmail.com','21831955','Alex','Khera','6035','2017-08-15 13:17:48'),(376,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ankur.icai37@gmail.com','226270573','Ankur','Patel','1025','2017-08-15 13:17:48'),(377,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ezra1275@gmail.com','02041069938','Robert','Liai','2102','2017-08-15 13:17:50'),(378,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','raves.rkc@gmail.com','210441187','Ravinesh','Chetty','110','2017-08-15 13:17:50'),(379,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','lomboydarwin@yahoo.co.nz','272283904','Darwin','Lomboy','1042','2017-08-15 13:17:50'),(380,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','aitutaki17@windowslive.com','2108805791','Iefata','Lamese','2024','2017-08-15 13:17:51'),(381,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','t_siya@hotmail.com','212926896','Sam','Aghdassi','632','2017-08-15 13:17:51'),(382,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','karenapolly83@gmail.com','2108233228','Polly','Edwards','110','2017-08-15 13:17:51'),(383,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','f.sue01@gmail.com','2102404489','Foisaga','Su\'e','1041','2017-08-15 13:17:51'),(384,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','derekbishop30@gmail.com','2108561916','Derek','Bishop','7391','2017-08-15 13:17:51'),(385,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kullarmani.222@outlook.com','2108342615','Manvir','Kaur','2025','2017-08-15 13:17:51'),(386,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','pjeffery68@gmail.com','21673175','Peter','Jeffery','810','2017-08-15 13:17:51'),(387,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','mar.druma@yahoo.com','226059226','Taiseni','Mar.','2024','2017-08-15 13:17:51'),(388,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','15361@burnside.school.nz','221550243','Mahnaz','Rafyee','8051','2017-08-15 13:17:51'),(389,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','smurfymitchell@gmail.com','221768062','Mitchell','Tetai','7604','2017-08-15 13:17:51'),(390,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','jaspreetgrewal193@gmail.com','2108408207','Jaspreet','Grewal','2025','2017-08-15 13:17:51'),(391,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','atkins.kiwa@gmail.com','210717942','Kiwa','Atkins','8061','2017-08-15 13:17:51'),(392,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','dougmear91@gmail.com','224288557','Douglas','Mear','3010','2017-08-15 13:17:51'),(393,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','c.swahani@gmail.com','2102738202','Swahani','Chandra','1025','2017-08-15 13:17:51'),(394,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','jeff_lionheart05@yahoo.com','2108853021','Jeffrey','Mendoza','612','2017-08-15 13:17:51'),(395,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','fotumaria@gmail.com','210464766','Maria','Fotu','2022','2017-08-15 13:17:51'),(396,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','tanseycherie@gmail.com','2108127610','Cherie','Tansey','2022','2017-08-15 13:17:51'),(397,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','johngeoger@gmail.com','224167452','John','Goeger','6022','2017-08-15 13:17:51'),(398,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','rameshodedara999@gmail.com','273477989','Ramesh','Odedara','600','2017-08-15 13:17:51'),(399,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kamniprasad@hotmail.com','','Kamni','Prasad','2102','2017-08-15 13:17:51'),(400,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','tiramoke@gmail.com','221599194','Tira','Anderson','3214','2017-08-15 13:17:51'),(401,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','clawrence@orcon.net.nz','272359085','Carol','Lawrence','3127','2017-08-15 13:17:51'),(402,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','turewas@gmail.com','2102702846','Sitiveni','Tuirewa','1062','2017-08-15 13:17:51'),(403,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','2189mani@gmail.com','224357700','Manpreet Singh','Bains','7910','2017-08-15 13:17:51'),(404,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','prasads4128@gmail.com','210459366','Amit','Prasad','1060','2017-08-15 13:17:51'),(405,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kevingrangiawha@gmail.com','2102651717','Kevin','Rangiawha','3204','2017-08-15 13:17:51'),(406,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','b0b_is_coo@hotmail.com','273125609','Neil','Hope','3600','2017-08-15 13:17:51'),(407,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','lumepa.meleisea@gmail.com','212157802','Lumepa','Meleisea','2110','2017-08-15 13:17:51'),(408,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','lalbrown450@gmail.com','220659723','Rererangi','Erehi','4010','2017-08-15 13:17:51'),(409,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','cosmos0509@gmail.com','2108799977','Yu','Yao','4112','2017-08-15 13:17:51'),(410,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','annaleedelamere81@gmail.com','2041258298','Annalee','Delamere','3122','2017-08-15 13:17:51'),(411,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','sionefekemaasi@live.com','2102493619','Sione Feke','Maasi','602','2017-08-15 13:17:51'),(412,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','singhrjt93@gmail.com','2041032698','Ranjit','Singh','4130','2017-08-15 13:17:51'),(413,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','stephanie_maree_brown@outlook.com','279404170','Steph','Brown','5510','2017-08-15 13:17:51'),(414,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','letisisammy123@gmail.com','211019828','Samiuela','Letisi','986','2017-08-15 13:17:51'),(415,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','tillyemori2015@gmail.com','2108583415','Matilda','Antonio','2025','2017-08-15 13:17:51'),(416,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','zannagadget@gmail.com','273444116','Zanna','Karstens','7020','2017-08-15 13:17:51'),(417,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','taiara@xtra.co.nz','278642001','Arthur','Aramakutu','4010','2017-08-15 13:17:51'),(418,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','cmnmatthews@gmail.com','273460657','Carla','Matthews','5882','2017-08-15 13:17:51'),(419,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','mydarklore@gmail.com','2040227420','John','Wilson - Connell','8011','2017-08-15 13:17:51'),(420,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ronaedwards1@hotmail.co.nz','2108846441','Rona','Edwards','3200','2017-08-15 13:17:51'),(421,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','nafeer007@gmail.com','211762794','Abdul','Nafeer','600','2017-08-15 13:17:51'),(422,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kanehard22@outlook.com','2108418606','Josh','Kopa','2110','2017-08-15 13:17:51'),(423,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','cuteyashu26@gmail.com','221635707','Yashika','Sethi','1010','2017-08-15 13:17:51'),(424,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','anthonyfotofili@gmail.com','211669329','Anthony','Fotofili','2120','2017-08-15 13:17:51'),(425,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','hiteshpatel268@gmail.com','223525881','Hitesh','Patel','1041','2017-08-15 13:17:51'),(426,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kuldeeppandher25@gmail.com','273470006','Kuldeep','Pandher','3492','2017-08-15 13:17:51'),(427,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kingfish9@me.com','21722411','Jenny','Silbery','627','2017-08-15 13:17:51'),(428,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','charliecameronnz@gmail.com','275388886','Charlie','Cameron','8082','2017-08-15 13:17:51'),(429,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','alesanaahmann@gmail.com','2041270803','Alesana','Ah Mann','2023','2017-08-15 13:17:51'),(430,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','dneame@xtra.co.nz','272010939','Dawn','Neame','8061','2017-08-15 13:17:51'),(431,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','rajwinderaulakh241@gmail.com','277775511','Rajwinder','Kaur','2102','2017-08-15 13:17:51'),(432,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','cedahvaea@gmail.com','2108265457','Akosita','Vaeafisi','2022','2017-08-15 13:17:51'),(433,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','wheeler17473@gmail.com','2102504531','Daniel','Nunns','5011','2017-08-15 13:17:51'),(434,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','sharon.plumridge2016@gmail.com','272696609','Sharon','Plumridge','4501','2017-08-15 13:17:51'),(435,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','preetkamal786@ymail.com','2102241247','Kamalpreet','Kaur','1010','2017-08-15 13:17:51'),(436,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','yvonneharding@gmail.com','0223191879','Yvonne Kahira','Harding','2110','2017-08-15 13:17:53'),(437,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','hwyslr440@gmail.com','274714800','Nelson','Hoskin','2018','2017-08-15 13:17:53'),(438,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','manveer0806@gmail.com','0224295480','Manveer','Kaur','1025','2017-08-15 13:17:56'),(439,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','te.ariki.044@gmail.com','221884365','Te Ariki','Amiri','1060','2017-08-15 13:17:56'),(440,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','roxannepeneha@gmail.com','0284053149','Roxanne','Peneha','2010','2017-08-15 13:17:58'),(441,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','leanne_teruruku@outlook.com','21642467','Leanne','Te Ruruku','3420','2017-08-15 13:17:58'),(442,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','corvettbel@gmail.com','210507773','Brian','Langley','8053','2017-08-15 13:17:58'),(443,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ahioveisinia@gmail.com','212649720','Veisinia','Ahio','614','2017-08-15 13:17:58'),(444,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','isai.devi@gmail.com','226516679','Devipriya','Ramachandran Nair','1061','2017-08-15 13:17:58'),(445,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','mukesh22011992@gmail.com','224299209','Mukesh','Sharma','2025','2017-08-15 13:17:58'),(446,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','tbeulaht@hotmail.com','210559600','Beulah','Tukiwaho','230','2017-08-15 13:17:58'),(447,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','lxmcquinn@gmail.com','211795020','Areka','McQuinn','2683','2017-08-15 13:17:58'),(448,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','dannymitchell1944@gmail.com','279035469','Danny','Mitchell','4815','2017-08-15 13:17:58'),(449,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','er.pratik220@gmail.com','223235038','Pratik','Mistry','602','2017-08-15 13:17:58'),(450,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','leonkaren67@gmail.com','211879087','Karen','Ridgewell','5032','2017-08-15 13:17:58'),(451,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','harleygoodin25@gmail.com','2108393176','Harley','Goodin Clark','2105','2017-08-15 13:17:58'),(452,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','unalotohiko@hotmail.com','2102557868','Unaloto','Hiko','1072','2017-08-15 13:17:58'),(453,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kimmariestewart64@gmail.com','0272339772','Kim','Stewart','3020','2017-08-15 13:18:00'),(454,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','prophets@clear.net.nz','021817979','Paul','Te Kiri','4120','2017-08-15 13:18:09'),(455,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','kanwaljot_sidhu@yahoo.co.in','0211239594','Kanwaljot','Sidhu','620','2017-08-15 13:18:11'),(456,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','aluth1121@gmail.com','223940757','Chinthaka','Samarasinghe','1050','2017-08-15 13:18:11'),(457,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','Honourkelly11@gmail.com','0204072728','Janae','Kelly','3720','2017-08-15 13:18:13'),(458,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','djcsoccer@hotmail.com','2108110398','Danni-Jane','Cole','981','2017-08-15 13:18:13'),(459,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','shawjeney@gmail.com','2102580678','Jeney','Shaw','2582','2017-08-15 13:18:13'),(460,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','pamschrader11@gmail.com','210685442','Pamela','Schrader','4500','2017-08-15 13:18:13'),(461,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','ashish_1910@yahoo.com','223521199','Ashish','Shah','2104','2017-08-15 13:18:13'),(462,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','bev_ritchie@hotmail.com','2102302033','Bev','Ritchie','874','2017-08-15 13:18:13'),(463,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','jaybee5980@gmail.com','0211652910','Janice','Bradley','2102','2017-08-15 13:18:15'),(464,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','teruistheman@gmail.com','226023525','Te Ru','Huriwai','6012','2017-08-15 13:18:15'),(465,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','gthomas@takaro.co.nz','274844984','Gary','Thomas','4414','2017-08-15 13:18:15'),(466,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','arthur@auffray.fr','220488009','Arthur','Auffray-Baude','626','2017-08-15 13:18:15'),(467,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','violeti_08@hotmail.com','2102917266','Iutita','Filoa','2022','2017-08-15 13:18:15'),(468,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','marj@cranberriesnz.co.nz','21359245','Markorie','Allan','7882','2017-08-15 13:18:15'),(469,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','celiasalter1@gmail.com','211050771','Celia','Salter','632','2017-08-15 13:18:15'),(470,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','caroline.heta@gmail.com','02102368359','Caroline','Heta','4500','2017-08-15 13:18:17'),(471,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','glenmccracken37@gmail.com','212924176','Glen','McCracken','9471','2017-08-15 13:18:17'),(472,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','benewalsandeep531@gmail.com','211336444','Sandeep','Singh','610','2017-08-15 13:18:17'),(473,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','tinybaegldc4@gmail.com','0274883666','Harold','McFarold','3800','2017-08-15 13:18:19'),(474,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','delizabeth375@gmail.com','210730635','Denise','Edwards','2103','2017-08-15 13:18:19'),(475,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','dsgrace56@gmail.com','0273265556','David Veli','Smith','3110','2017-08-15 13:18:21'),(476,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','shirlee.devia07@yahoo.co.nz','212949017','Saleshni','Devia','2025','2017-08-15 13:18:21'),(477,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','karentrocha70@gmail.com','212445877','Karen','Trocha','4122','2017-08-15 13:18:21'),(478,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','barryh815@gmail.com','272202240','Barry','Harvey','7510','2017-08-15 13:18:21'),(479,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','mazizkhan14@gmail.com','','Mahbuba','Khan','1041','2017-08-15 13:18:21'),(480,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','mikekapea16@gmail.com','2040525319','Michael','Kapea','612','2017-08-15 13:18:21'),(481,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','divyabharathiva@gmail.com','226469955','Divyabharathi','Kulandaivelu','1061','2017-08-15 13:18:21'),(482,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','juliamagrslee@gmail.com','276133303','Julia','Magrs Lee','7614','2017-08-15 13:18:21'),(483,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','jojosciascia@gmail.com','2102525885','Jojo','Sciascia','2025','2017-08-15 13:18:21'),(484,0,'cbe5fa9ddf9a32dfeb1bb1a33d7235ee_2014.txt',1,'52','luffy54321@hotmail.com','277246833','Glenn','Macintyre','7011','2017-08-15 13:18:21');
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
INSERT INTO `lead_audit` VALUES (1,0,'0',1,'52','{\"title\":\"Mr\",\"firstName\":\"Robert\",\"lastName\":\"Liai\",\"company\":\"\",\"position\":\"\",\"address1\":\"56 Mcannalley St\",\"address2\":\"\",\"Suburb\":\"Manurewa East\",\"state\":\"Auckland\",\"postcode\":\"2102\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"02041069938\",\"web\":\"\",\"email\":\"ezra1275@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Male\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:17:00','True','Rejected'),(2,0,'0',1,'52','{\"title\":\"Mrs\",\"firstName\":\"Yvonne Kahira\",\"lastName\":\"Harding\",\"company\":\"\",\"position\":\"\",\"address1\":\"77 Clevedon Road\",\"address2\":\"\",\"Suburb\":\"Papakura\",\"state\":\"AUCKLAND\",\"postcode\":\"2110\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0223191879\",\"web\":\"\",\"email\":\"yvonneharding@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:17:00','False','Accepted'),(3,0,'0',1,'52','{\"title\":\"Miss\",\"firstName\":\"Manveer\",\"lastName\":\"Kaur\",\"company\":\"\",\"position\":\"\",\"address1\":\"30 Leslie Ave\",\"address2\":\"\",\"Suburb\":\"Mount Albert\",\"state\":\"Auckland\",\"postcode\":\"1025\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0224295480\",\"web\":\"\",\"email\":\"manveer0806@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:17:00','False','Accepted'),(4,0,'0',1,'52','{\"title\":\"Ms\",\"firstName\":\"Roxanne\",\"lastName\":\"Peneha\",\"company\":\"\",\"position\":\"\",\"address1\":\"33casscade\",\"address2\":\"\",\"Suburb\":\"Highland Park\",\"state\":\"Auckland\",\"postcode\":\"2010\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0284053149\",\"web\":\"\",\"email\":\"roxannepeneha@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:17:00','False','Accepted'),(5,0,'0',1,'52','{\"title\":\"Ms\",\"firstName\":\"Kim\",\"lastName\":\"Stewart\",\"company\":\"\",\"position\":\"\",\"address1\":\"9maraeroa Rd Mamaku 3020\",\"address2\":\"\",\"Suburb\":\"Mamaku\",\"state\":\"Mamaku\",\"postcode\":\"3020\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0272339772\",\"web\":\"\",\"email\":\"kimmariestewart64@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(6,0,'0',1,'52','{\"title\":\"Mr\",\"firstName\":\"Paul\",\"lastName\":\"Te Kiri\",\"company\":\"\",\"position\":\"\",\"address1\":\"71 Folkestone Dr\",\"address2\":\"\",\"Suburb\":\"Longlands\",\"state\":\"Hastings\",\"postcode\":\"4120\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"021817979\",\"web\":\"\",\"email\":\"prophets@clear.net.nz\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Male\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(7,0,'0',1,'52','{\"title\":\"Mr\",\"firstName\":\"Kanwaljot\",\"lastName\":\"Sidhu\",\"company\":\"\",\"position\":\"\",\"address1\":\"22 Sequoia Pl\",\"address2\":\"\",\"Suburb\":\"Forrest Hill\",\"state\":\"Auckland\",\"postcode\":\"620\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0211239594\",\"web\":\"\",\"email\":\"kanwaljot_sidhu@yahoo.co.in\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Male\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(8,0,'0',1,'52','{\"title\":\"Ms\",\"firstName\":\"Janae\",\"lastName\":\"Kelly\",\"company\":\"\",\"position\":\"\",\"address1\":\"1 A Duke St Ngaruawahia NZ\",\"address2\":\"\",\"Suburb\":\"Ngaruawahia\",\"state\":\"Ngaruawahia\",\"postcode\":\"3720\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0204072728\",\"web\":\"\",\"email\":\"Honourkelly11@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(9,0,'0',1,'52','{\"title\":\"Mrs\",\"firstName\":\"Janice\",\"lastName\":\"Bradley\",\"company\":\"\",\"position\":\"\",\"address1\":\"3\\/58 Maich Rd\",\"address2\":\"\",\"Suburb\":\"MANUREWA\",\"state\":\"Auckland\",\"postcode\":\"2102\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0211652910\",\"web\":\"\",\"email\":\"jaybee5980@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(10,0,'0',1,'52','{\"title\":\"Mrs\",\"firstName\":\"Caroline\",\"lastName\":\"Heta\",\"company\":\"\",\"position\":\"\",\"address1\":\"79 Niblett St\",\"address2\":\"\",\"Suburb\":\"Durie Hill\",\"state\":\"Wanganui\",\"postcode\":\"4500\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"02102368359\",\"web\":\"\",\"email\":\"caroline.heta@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Female\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(11,0,'0',1,'52','{\"title\":\"Mr\",\"firstName\":\"Harold\",\"lastName\":\"McFarold\",\"company\":\"\",\"position\":\"\",\"address1\":\"Wallace Terrace 45\",\"address2\":\"\",\"Suburb\":\"Kihikihi\",\"state\":\"Te Awamutu\",\"postcode\":\"3800\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0274883666\",\"web\":\"\",\"email\":\"tinybaegldc4@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Male\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(12,0,'0',1,'52','{\"title\":\"Mr\",\"firstName\":\"David Veli\",\"lastName\":\"Smith\",\"company\":\"\",\"position\":\"\",\"address1\":\"19 Amber Crecent\",\"address2\":\"\",\"Suburb\":\"Judea\",\"state\":\"Tauranga\",\"postcode\":\"3110\",\"countryCode\":\"NZ\",\"address1m\":\"\",\"address2m\":\"\",\"suburbm\":\"\",\"statem\":\"\",\"postcodem\":\"\",\"countrym\":\"\",\"Phone1\":\"\",\"phone2\":\"\",\"fax\":\"\",\"phone\":\"0273265556\",\"web\":\"\",\"email\":\"dsgrace56@gmail.com\",\"email2\":\"\",\"dpid\":\"\",\"u1\":\"\",\"u2 datetime\":\"\",\"u3\":\"\",\"u4\":\"\",\"u5\":\"\",\"u6\":\"\",\"u7\":\"\",\"u8\":\"\",\"u9\":\"\",\"birthdate\":\"\",\"age\":\"\",\"ageRange\":\"\",\"gender\":\"Male\",\"city\":\"\",\"u15\":\"\",\"u16\":\"\",\"u17\":\"\",\"u18\":\"\",\"u19\":\"\",\"u20\":\"Cohort Standalone\",\"supplier_id\":\"0\",\"client_id\":1,\"campaign_id\":52}','2017-08-15 13:18:00','False','Accepted'),(13,7,'7',2,'35','{\"email\":\"alexshilpikhera@gmail.com\",\"phone\":\"21831955\",\"title\":\"Mr\",\"firstName\":\"Alex\",\"lastName\":\"Khera\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"72 Dunedin St\",\"address2\":\"Otakiri\",\"city\":\"100FT ROAD\",\"state\":\"Wellington\",\"postcode\":\"6035\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:38:00','False','Accepted'),(14,7,'7',2,'35','{\"email\":\"ankur.icai37@gmail.com\",\"phone\":\"226270573\",\"title\":\"Mr\",\"firstName\":\"Ankur\",\"lastName\":\"Patel\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"72 Dunedin St\",\"address2\":\"Otakiri\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"1025\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:51:00','False','Accepted'),(15,7,'7',2,'35','{\"email\":\"ezra1275@gmail.com\",\"phone\":\"2041069938\",\"title\":\"Mr\",\"firstName\":\"Robert\",\"lastName\":\"Liai\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"56 Mcannalley St\",\"address2\":\"Otakiri\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"2102\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:53:00','False','Accepted'),(16,7,'7',2,'35','{\"email\":\"ezra1275@gmail.com\",\"phone\":\"2041069938\",\"title\":\"Mr\",\"firstName\":\"Ravinesh\",\"lastName\":\"Chetty\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"8\\/10 Central Ave\",\"address2\":\"Otakiri\",\"city\":\"100Ft Road\",\"state\":\"Whangarei\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:55:00','True','Duplicate'),(17,7,'7',2,'35','{\"email\":\"raves.rkc@gmail.com\",\"phone\":\"2041069938\",\"title\":\"Mr\",\"firstName\":\"Ravinesh\",\"lastName\":\"Chetty\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"8\\/10 Central Ave\",\"address2\":\"Otakiri\",\"city\":\"100Ft Road\",\"state\":\"Whangarei\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:56:00','True','Duplicate'),(18,7,'7',2,'35','{\"email\":\"raves.rkc@gmail.com\",\"phone\":\"210441187\",\"title\":\"Mr\",\"firstName\":\"Ravinesh\",\"lastName\":\"Chetty\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"8\\/10 Central Ave\",\"address2\":\"Otakiri\",\"city\":\"100FT ROAD\",\"state\":\"Whangarei\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 15:56:00','False','Accepted'),(19,7,'7',2,'35','{\"email\":\"raves.rkc@gmail.com\",\"phone\":\"210441187\",\"title\":\"Mr\",\"firstName\":\"Darwin\",\"lastName\":\"Lomboy\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"25 Haughey Ave\",\"address2\":\"Hillsborough\",\"city\":\"100Ft Road\",\"state\":\"Auckland\",\"postcode\":\"1042\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:00:00','True','Duplicate'),(20,7,'7',2,'35','{\"email\":\"lomboydarwin@yahoo.co.nz\",\"phone\":\"272283904\",\"title\":\"Mr\",\"firstName\":\"Darwin\",\"lastName\":\"Lomboy\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"25 Haughey Ave\",\"address2\":\"Hillsborough\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"1042\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:00:00','False','Accepted'),(21,7,'7',2,'35','{\"email\":\"aitutaki17@windowslive.com\",\"phone\":\"2108805791\",\"title\":\"Mr\",\"firstName\":\"Iefata\",\"lastName\":\"Lamese\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"68 Langiola Dr\",\"address2\":\"Mangere East\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"2024\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:11:00','False','Accepted'),(22,7,'7',2,'35','{\"email\":\"aitutaki17@windowslive.com\",\"phone\":\"2108805791\",\"title\":\"Mr\",\"firstName\":\"Sam\",\"lastName\":\"Aghdassi\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"18 Pukatea Ave\",\"address2\":\"Rosedale\",\"city\":\"100Ft Road\",\"state\":\"Auckland\",\"postcode\":\"632\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:13:00','True','Duplicate'),(23,7,'7',2,'35','{\"email\":\"t_siya@hotmail.com\",\"phone\":\"212926896\",\"title\":\"Mr\",\"firstName\":\"Sam\",\"lastName\":\"Aghdassi\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"18 Pukatea Ave\",\"address2\":\"Rosedale\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"632\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:14:00','False','Accepted'),(24,7,'7',2,'35','{\"email\":\"karenapolly83@gmail.com\",\"phone\":\"2108233228\",\"title\":\"Mr\",\"firstName\":\"Polly\",\"lastName\":\"Edwards\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"2\\/77 Cameron St\",\"address2\":\"Whangarei\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:17:00','False','Accepted'),(25,7,'7',2,'35','{\"email\":\"karenapolly83@gmail.com\",\"phone\":\"2108233228\",\"title\":\"Mr\",\"firstName\":\"Foisaga\",\"lastName\":\"Su\'e\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"2\\/77 Cameron St\",\"address2\":\"Whangarei\",\"city\":\"100Ft Road\",\"state\":\"Auckland\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:18:00','True','Duplicate'),(26,7,'7',2,'35','{\"email\":\"f.sue01@gmail.com\",\"phone\":\"2102404489\",\"title\":\"Mr\",\"firstName\":\"Foisaga\",\"lastName\":\"Su\'e\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"2\\/77 Cameron St\",\"address2\":\"Whangarei\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"110\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:18:00','True','Rejected'),(27,7,'7',2,'35','{\"email\":\"f.sue01@gmail.com\",\"phone\":\"2102404489\",\"title\":\"Mr\",\"firstName\":\"Foisaga\",\"lastName\":\"Su\'e\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"21B Albrecht Ave\",\"address2\":\"Mount Eden\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"1041\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:20:00','True','Rejected'),(28,7,'7',2,'35','{\"email\":\"f.sue01@gmail.com\",\"phone\":\"2102404489\",\"title\":\"Mrs\",\"firstName\":\"Foisaga\",\"lastName\":\"Su\'e\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"21B Albrecht Ave\",\"address2\":\"Mount Eden\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"1041\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:21:00','True','Rejected'),(29,7,'7',2,'35','{\"email\":\"f.sue01@gmail.com\",\"phone\":\"2102404489\",\"title\":\"Ms\",\"firstName\":\"Foisaga\",\"lastName\":\"Su\'e\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"21B Albrecht Ave\",\"address2\":\"Mount Eden\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"1041\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:21:00','True','Rejected'),(30,7,'7',2,'35','{\"email\":\"derekbishop30@gmail.com\",\"phone\":\"2108561916\",\"title\":\"Ms\",\"firstName\":\"Derek\",\"lastName\":\"Bishop\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"447j\\/1 Isolated Hill Rd\",\"address2\":\"Rural\",\"city\":\"100FT ROAD\",\"state\":\"Culverden\",\"postcode\":\"7391\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:23:00','False','Accepted'),(31,7,'7',2,'35','{\"email\":\"derekbishop30@gmail.com\",\"phone\":\"2108561916\",\"title\":\"Ms\",\"firstName\":\"Derek\",\"lastName\":\"Bishop\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"447j\\/1 Isolated Hill Rd\",\"address2\":\"Rural\",\"city\":\"100Ft Road\",\"state\":\"Culverden\",\"postcode\":\"7391\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:24:00','True','Duplicate'),(32,7,'7',2,'35','{\"email\":\"kullarmani.222@outlook.com\",\"phone\":\"2108342615\",\"title\":\"Ms\",\"firstName\":\"Manvir\",\"lastName\":\"Kaur\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"122 St George St\",\"address2\":\"Wiri\",\"city\":\"100FT ROAD\",\"state\":\"Auckland\",\"postcode\":\"2025\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:26:00','False','Accepted'),(33,7,'7',2,'35','{\"email\":\"kullarmani.222@outlook.com\",\"phone\":\"2108342615\",\"title\":\"Ms\",\"firstName\":\"Peter\",\"lastName\":\"Jeffery\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"male\",\"address1\":\"33 Riverhead Rd\",\"address2\":\"Huapai\",\"city\":\"100Ft Road\",\"state\":\"Kumeu\",\"postcode\":\"810\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:28:00','True','Duplicate'),(34,7,'7',2,'35','{\"email\":\"pjeffery68@gmail.com\",\"phone\":\"21673175\",\"title\":\"Ms\",\"firstName\":\"Peter\",\"lastName\":\"Jeffery\",\"birthdate\":\"12\\/10\\/1992\",\"age\":\"25\",\"ageRange\":\"1-99\",\"gender\":\"Male\",\"address1\":\"33 Riverhead Rd\",\"address2\":\"Huapai\",\"city\":\"100FT ROAD\",\"state\":\"Kumeu\",\"postcode\":\"810\",\"countryCode\":\"NZ\",\"supplier_id\":7,\"client_id\":2,\"campaign_id\":35}','2017-08-16 16:29:00','False','Accepted');
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
INSERT INTO `lead_history` VALUES (1,1,'52','yvonneharding@gmail.com',0,'2017-08-15 13:17:00'),(2,1,'52','0223191879',0,'2017-08-15 13:17:00'),(3,1,'52','manveer0806@gmail.com',0,'2017-08-15 13:17:00'),(4,1,'52','0224295480',0,'2017-08-15 13:17:00'),(5,1,'52','roxannepeneha@gmail.com',0,'2017-08-15 13:17:00'),(6,1,'52','0284053149',0,'2017-08-15 13:17:00'),(7,1,'52','kimmariestewart64@gmail.com',0,'2017-08-15 13:18:00'),(8,1,'52','0272339772',0,'2017-08-15 13:18:00'),(9,1,'52','prophets@clear.net.nz',0,'2017-08-15 13:18:00'),(10,1,'52','021817979',0,'2017-08-15 13:18:00'),(11,1,'52','kanwaljot_sidhu@yahoo.co.in',0,'2017-08-15 13:18:00'),(12,1,'52','0211239594',0,'2017-08-15 13:18:00'),(13,1,'52','Honourkelly11@gmail.com',0,'2017-08-15 13:18:00'),(14,1,'52','0204072728',0,'2017-08-15 13:18:00'),(15,1,'52','jaybee5980@gmail.com',0,'2017-08-15 13:18:00'),(16,1,'52','0211652910',0,'2017-08-15 13:18:00'),(17,1,'52','caroline.heta@gmail.com',0,'2017-08-15 13:18:00'),(18,1,'52','02102368359',0,'2017-08-15 13:18:00'),(19,1,'52','tinybaegldc4@gmail.com',0,'2017-08-15 13:18:00'),(20,1,'52','0274883666',0,'2017-08-15 13:18:00'),(21,1,'52','dsgrace56@gmail.com',0,'2017-08-15 13:18:00'),(22,1,'52','0273265556',0,'2017-08-15 13:18:00'),(23,2,'35','alexshilpikhera@gmail.com',0,'2017-08-16 15:38:00'),(24,2,'35','21831955',0,'2017-08-16 15:38:00'),(25,2,'35','ankur.icai37@gmail.com',0,'2017-08-16 15:51:00'),(26,2,'35','226270573',0,'2017-08-16 15:51:00'),(27,2,'35','ezra1275@gmail.com',0,'2017-08-16 15:53:00'),(28,2,'35','2041069938',0,'2017-08-16 15:53:00'),(29,2,'35','raves.rkc@gmail.com',0,'2017-08-16 15:56:00'),(30,2,'35','210441187',0,'2017-08-16 15:56:00'),(31,2,'35','lomboydarwin@yahoo.co.nz',0,'2017-08-16 16:00:00'),(32,2,'35','272283904',0,'2017-08-16 16:00:00'),(33,2,'35','aitutaki17@windowslive.com',0,'2017-08-16 16:11:00'),(34,2,'35','2108805791',0,'2017-08-16 16:11:00'),(35,2,'35','t_siya@hotmail.com',0,'2017-08-16 16:14:00'),(36,2,'35','212926896',0,'2017-08-16 16:14:00'),(37,2,'35','karenapolly83@gmail.com',0,'2017-08-16 16:17:00'),(38,2,'35','2108233228',0,'2017-08-16 16:17:00'),(39,2,'35','derekbishop30@gmail.com',0,'2017-08-16 16:23:00'),(40,2,'35','2108561916',0,'2017-08-16 16:23:00'),(41,2,'35','kullarmani.222@outlook.com',0,'2017-08-16 16:26:00'),(42,2,'35','2108342615',0,'2017-08-16 16:26:00'),(43,2,'35','pjeffery68@gmail.com',0,'2017-08-16 16:29:00'),(44,2,'35','21673175',0,'2017-08-16 16:29:00');
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
INSERT INTO `membrain_global_do_not_call` VALUES (1,'AU','123456789','Invalid'),(2,'AU','123456788','valid'),(3,'NZ','92856898745','Invalid'),(4,'NZ','92856898750','');
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
INSERT INTO `multipleapi` VALUES (1,'phoneApi','IN','http://ec2-13-126-3-130.ap-south-1.compute.amazonaws.com/membraindev/public/api/v1/IN/multisupplierapi','{\"apiToken\":\"swer\",\"apiPassword\":\"@34567sjdhs$\"}','1','2017-08-01 15:13:00','2017-08-01 18:44:00'),(2,'emailApi','NZ','http://ec2-13-126-3-130.ap-south-1.compute.amazonaws.com/membraindev/public/api/v1/AU/multisupplierapi','{\"apiToken\":\"s$56hfkdjkd\",\"apiPassword\":\"@34567sjdhs$\"}','1','2017-08-01 15:56:00','2017-08-14 17:10:00'),(3,'birthdateApi','MA','http://ec2-13-126-3-130.ap-south-1.compute.amazonaws.com/membraindev/public/api/v1/MA/multisupplierapi','123','1','2017-08-01 16:43:00','2017-08-01 18:44:00');
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
INSERT INTO `name_blacklist` VALUES (1,'name','comment'),(2,'Ileana','nasjfjds');
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
INSERT INTO `phone_history` VALUES (1,'NZ','02041069938',1,'2017-08-14'),(2,'NZ','0223191879',1,'2017-08-15'),(3,'NZ','0224295480',1,'2017-08-15'),(4,'NZ','0284053149',1,'2017-08-15'),(5,'NZ','0272339772',1,'2017-08-15'),(6,'NZ','021817979',1,'2017-08-15'),(7,'NZ','0211239594',1,'2017-08-15'),(8,'NZ','0204072728',1,'2017-08-15'),(9,'NZ','0211652910',1,'2017-08-15'),(10,'NZ','02102368359',1,'2017-08-15'),(11,'NZ','0274883666',1,'2017-08-15'),(12,'NZ','0273265556',1,'2017-08-15');
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
INSERT INTO `portal_role` VALUES (1,'Membrain Administrator'),(2,'Membrain Staff'),(3,'Supplier'),(4,'Client'),(5,'Super Client');
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
INSERT INTO `portal_sub_client` VALUES (3,2,2),(4,2,3),(5,86,1),(6,88,1),(7,94,17),(8,94,18);
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
INSERT INTO `protal_user` VALUES (1,'isusantakumardas@gmail.com','Susanta Kumar Das','$2y$10$ekBp8qUP1vHmr1.LU5V.yeNCW84uq7IcTCsoMMiiijzH9Eegpd9vi',1,NULL,NULL,1,0,'2017-08-16 16:00:04','2017-05-06 02:55:54','2017-05-13 13:57:17','8M6yZU7DddGl5kYDPIyzmbjAL4LPUM1iglIYWNCunXh8HOXe5aJXQJmPWBkI'),(6,'krishnaerb@gmail.com','Krishna','$2y$10$5XxOao6zVGqFZxh1Z8Yiruk4TaWSWefCfEZzSybHQAhPO/M7kSZ3O',1,0,0,1,0,'2017-08-16 15:37:20','2017-05-22 04:45:47','2017-07-27 00:08:21','fmdyXzcT8TfoJK1VeJM6dd9Y3tUKYQ6OqNi1r9A02tqxi0H7jMBIWS0M4ihG'),(9,'krishnaerb123@gmail.com','GopichandY','$2y$10$Mt9g5QJY1qQg0g8IqEZd2eHCvvZ4dreCsvDeuptpSJJJJIfy/lxzy',1,NULL,NULL,1,0,'2017-08-01 17:24:26','2017-05-22 05:47:26','2017-05-25 05:16:59','flnKVCeVTxd0RtCvIQcDngkr5VuYIueSa8YupHpUpozU5C5TdgmhVD79Sxr8'),(11,'Kris12@gmail.com','zxcq','$2y$10$6CroJkRUQQWyUTIQ1CYJH.O.JIMWXSIedltVnJE9CFKgooVAWVTp2',4,1,NULL,1,0,NULL,'2017-05-22 06:18:32','2017-05-23 12:12:12',NULL),(12,'padma.srinivasan@gmail.com','Padma','$2y$10$kYLLZyReo5CUwW69Hszb2.GZuo.en3pBHORrYgpCPGONoBtrUGubW',1,NULL,NULL,1,0,'2017-05-24 07:15:23','2017-05-22 08:38:12','2017-05-22 08:40:09','Rot8jfS9XAgH2W2VbEsxMHgvUTtGe4mv82IsdL6km7lW7TFAt3fTxqy9zcW4'),(16,'krishna@gmail.com','krishnag','$2y$10$/Rmv4FC7Y6p3YDDxYRZJNeCtmxaHxcR/vFywS1TBt6Sgkas/nenHi',1,NULL,NULL,1,0,'2017-07-06 06:04:34','2017-05-22 10:45:32','2017-05-23 12:13:47','OI0hqIKVbKMFYsGcarIbHqGNEBIWYG12qlbOWef5M0qvIj04mw1YIwAhBtv2'),(18,'radha@gmail.com','Radha','$2y$10$qnblwHRAr412EiGxkWfjT.6PDjLLNPF/qpQas4j25Hvbg9IEzyGjS',1,NULL,NULL,1,0,NULL,'2017-05-22 11:52:30','2017-05-22 11:53:04',NULL),(19,'ram@123gmail.com','sriram','$2y$10$PUacXplaX7Ak9vJy7zA3le1mbSubLSSQODThjl7LgrgT2eKVoozuG',2,NULL,NULL,1,0,NULL,'2017-05-22 12:40:00','2017-05-22 12:41:11',NULL),(23,'gopi21@gmail.com','gopi krishna','$2y$10$r2haMjOilh9HS2oN.vI/zu6zGvh711f6cNHQiEnZhXjR52baxZo2W',3,0,15,1,0,NULL,'2017-05-23 12:34:45','2017-05-23 12:34:45',NULL),(24,'krishna35@gmail.com','krishna k','$2y$10$CFlLlnU7XU29yumV/bSmMOZhJcaZXKuIWsZmCgT83vLQpvk0hWfrq',4,1,0,1,0,NULL,'2017-05-23 12:35:42','2017-05-23 12:35:42',NULL),(25,'prasad56@gmail.com','prasad y','$2y$10$rkF.cfKoXKm/2bbyBjYcHOVdlFvyX4oYehQ1tgbl2bMo2amLfREjW',5,0,0,1,0,NULL,'2017-05-23 12:36:33','2017-05-23 12:36:33',NULL),(28,'isusantakumardas123@gmail.com','sushanth s','$2y$10$VjXuEwWReHy1P0jz9gqUmOFE9eL2oG6VVqjvuft2i9Xd3JLEpcYc.',5,0,0,1,0,NULL,'2017-05-23 12:39:49','2017-05-23 12:39:49',NULL),(29,'padma12@gmail.com','padma s','$2y$10$1.LIVMor1sidbHlxFGEzXe2PYZoSodq1DeHrETUixgsBFN8xXiqSi',5,0,0,1,0,NULL,'2017-05-23 12:40:47','2017-05-23 12:40:47',NULL),(31,'nithin12@gmail.com','nithin a','$2y$10$dp7OJoo.4whj6I.IlDib3e4t2iqllWtMZmoYHm/yu2Hl.dz4ohylu',1,0,0,1,0,NULL,'2017-05-23 12:43:00','2017-05-23 12:43:00',NULL),(33,'vikki12@gmail.com','vikki a','$2y$10$d3CxMwA2C3h/6Bfw5WwlTeCmyZwA8u.FGltSdZbeUeJuJBL7FKquG',3,0,2,1,0,NULL,'2017-05-23 12:44:47','2017-05-23 12:44:47',NULL),(34,'vivek777@gmail.com','vivek','$2y$10$4C06qx.3bUWWHBhjiurS8.dZwSEdkQKZI7PXwOqpQ4I5/TAH/51Ly',4,2,0,1,0,NULL,'2017-05-23 12:45:44','2017-05-23 12:45:44',NULL),(35,'nithin656@gmail.com','nithin k','$2y$10$2I1q7ph/APi40zCLjOqmKOU7kbXHFfkInex5baPg5CAUG8uppozX6',2,0,0,1,0,NULL,'2017-05-23 12:46:29','2017-05-23 12:46:29',NULL),(36,'avikmitra@gmail.com','Avikmitra','$2y$10$S4dZbZA4V0bLq64A3WraxeogvaJNt/JM3N1pltMLM5qbYh.4KDb.W',3,0,4,1,0,NULL,'2017-05-23 12:47:11','2017-06-09 15:26:18',NULL),(37,'padma4667@gmail.com','padma l','$2y$10$k0wEaSvG20eX2jljnSfhguTUrHKZ/nTv2BUoqh/ZralJVElNEUShC',5,0,0,1,0,NULL,'2017-05-23 12:48:04','2017-05-23 12:48:04',NULL),(39,'appiconweb@gmail.com','anupamroy','$2y$10$GsFf7TnONYOGzjKuEoxFWuWqlBctkw0OuQSWa53eUhXS8oMG4RqDO',2,NULL,NULL,1,0,'2017-05-24 18:11:15','2017-05-23 15:24:54','2017-06-09 15:32:31','6lFkArOeuKhro3f0BVL6rcYTSErRliagElysNNnUvsqY5cMIYPq2mN5j4lkc'),(40,'jantony@latitudeglobalpartners.com','Joe','$2y$10$ASB7O1drtUNN0aVjIJomzeg2Wd9acbacuF5mJwEhHd3itxhJYG4lm',1,0,0,1,0,NULL,'2017-05-24 05:39:58','2017-05-24 10:30:39',NULL),(42,'rakeshbasara2@gmail.com','rakeshi','$2y$10$PM43PMPC563AqHoSfjf7meZE6R0vIXLXI3hpmy9oJfpZ87FndB.Tu',3,0,12,1,0,NULL,'2017-05-24 10:57:09','2017-05-24 10:57:09',NULL),(44,'info@amazon.in','Amazon suppliers','$2y$10$1FXAbSOC4yGY0FOHSn3JyOWOL07Fl0RYGThlgenbXfCX3jRXmlNYe',3,0,21,1,0,'2017-08-01 19:58:56','2017-06-09 15:33:50','2017-08-08 18:46:35','rFp7ZfTO2IMtp6VAbwcifTMHWvwqb3LQIx6iRTZgiuPWYGWsWPSmjYMdTUDZ'),(45,'info@mindtechlabs.com','Mindtech labs','$2y$10$UKFOLwWj0PrUKayvwvmsS.O4YHbiDwJ5B5ouiywIE6t9fTnXb/dMW',1,0,0,1,0,'2017-07-29 19:53:46','2017-06-09 15:35:51','2017-06-09 15:35:51','ddCiIIMdGMXKpbtZpHwmj32mgPp2CsZPMkvzENXzudJv5fkXSMMC35cYdjuN'),(47,'nithin@gmail.com','Nithin','$2y$10$OPqWi.1MasNIDQ5SCJRxouHjauSg/RR2oy7mUy0IDimHcMHV4Hlse',3,0,10,1,0,'2017-07-06 14:36:16','2017-06-10 06:00:39','2017-06-10 06:00:39','mCCSsWUUuSPgpIZBEYDp10bSgsl6lAbQxCcjsQwGECXsgt3gTZxYaiiuTavp'),(48,'nikhil@gmail.com','Nikhil','$2y$10$Dqw2CuQ17Vp.cW3jAA0CkeXc/LBfg4zvuS/ixpHD2DJVRTftUBN8y',4,1,0,1,0,'2017-08-14 19:35:21','2017-06-10 06:02:36','2017-06-10 06:02:36','e5WiTXMmKVc5XPa33xYvh4MNgeBRacEGTMTG5ZjYImJSXTk79GAajUTXHrQ4'),(49,'krishna123@gmail.com','Krishna','$2y$10$3vw1aZ11MVJb4fJsWOKPn.87TYqE/5RFoYsgDY9VPHyrx6KF2ahWm',5,0,0,1,0,'2017-08-01 19:57:34','2017-06-10 06:04:07','2017-06-10 06:04:07','HQtYUbF1aIq3TcXsUjobiSOWGd04BSlDpLPT0fUqqCQrGCWVo4mtsis0Qgw8'),(50,'test@gmail.com','Tester','$2y$10$cz.kQjU9L1d0g/r03RAMX.OQHrHmPc3X6t9xbhdN4/JYmE.pHHIN.',2,0,0,1,0,'2017-08-01 19:56:37','2017-06-10 06:06:33','2017-06-10 06:06:33','1YsbS8fZYiHEQtgxiO21z3pPZ78uxsi1PBx8f7jP8sTpEOGulQBFORNY0OUn'),(52,'nikhilsaggam3@gmail.com','Nikhil SN','$2y$10$kI9xe0tUNEZCnMCS8KZRmej45fmlfHCT97lF8mGcRrIZgr.IoiIfK',3,0,9,1,0,NULL,'2017-06-10 10:21:22','2017-06-10 10:21:22',NULL),(54,'info@mantras.com','Mantras','$2y$10$zMQSUzL0jjdi2nvYIVlhKOtl0YUn/Bz2xrh.7l6JBIgmARozVGy9S',3,0,1,1,0,'2017-07-06 06:36:36','2017-06-10 12:47:23','2017-06-10 12:47:23','IATDS5rWPzMWjNdIJrcKcuL4QX6ZsDyctXBjZJ9ezCsRdaNowHOJjE8qyvQW'),(55,'prasanti@gmail.com','Prasanti','$2y$10$VHvopOv9qygNkBMLx2Wu.OPiQfAPkXbymrJfo9ztEs7ujSChl5lwW',5,0,0,1,0,NULL,'2017-06-12 05:39:23','2017-06-12 05:39:23',NULL),(58,'xyzabc@gmail.com','xyzabc','$2y$10$rzGJ9aMKJnj6FnDdJB5tSup8GTGcKM57KCVFr1gDIyoxzwD.voERi',3,0,1,1,0,NULL,'2017-06-13 09:20:25','2017-06-13 09:20:25',NULL),(62,'isusantakumardas2@gmail.com','Susanta Kumar Das','$2y$10$3lRBd9w1xv8yFXGZCDU3f.Ipt/3PqiDFKxoCpbJLgcqqTdmO8.caO',1,NULL,NULL,1,0,NULL,'2017-06-17 11:02:00','2017-06-17 11:02:00',NULL),(66,'isusantakumardas3@gmail.com','Susanta Kumar Das','$2y$10$IpB5KU0dSgSdR4AbxLrJY.4n3P7m5Cpab9r6O5zkYMbiLLqxB.Qza',1,NULL,NULL,1,0,NULL,'2017-06-18 10:27:53','2017-06-18 10:27:53',NULL),(72,'nikhil.saggam@mindtechlabs.com','Nikhil','$2y$10$1Y0FQOa/7yE.N6fkrKYk3umb0kjhGqBg2V3ml7065dg7GagQi/DVa',1,0,0,1,0,'2017-08-16 15:40:58','2017-07-06 05:38:36','2017-07-06 05:38:36','n7m8QTc20B6TjwwQvKYuvw2GvWpx9G3nWUAX7nB3ojwb6xzVcgFnO3Zfqqan'),(74,'ntnkumar513@gmail.com','Nithin','$2y$10$wBoDry/7jF6Z2rZk3fGBmehEutdMpigEAq2AzFspolS0MFAyhgPGa',1,0,0,1,0,'2017-08-08 16:40:50','2017-07-06 06:41:40','2017-07-06 06:41:40','MNi6o0HDB6ud341TwBsXq5GJhLsj2hEEcecFmj19eWOwyol0ngP3a3gRkiX8'),(75,'krishna1@gmail.com','krishna','$2y$10$NsNc8.0ODmutx1crRuOYXOVRZoAApcTbrAfsfEJ587JDUnladP7vK',3,0,2,1,NULL,'2017-08-14 21:55:18','2017-07-06 06:45:13','2017-07-06 06:45:13','L17Eqkub2PMmviYZ7TYmW048ohvxNTcPaVCyvdkPZgSNHlGpun1btWmohSIq'),(76,'krishna2@gmail.com','Krishna client','$2y$10$IOWD905EvTtauMSVtUrr/.X.8vbSrtj0lNtjVqiAr9PsSfpjmIQ7e',4,1,0,1,NULL,'2017-07-07 09:20:24','2017-07-06 06:48:48','2017-07-06 06:48:48','5DuxqjdSFv0Bq2qgMGclqkxcUma3GjWJBc51Ah0o1EbYE9JLX11YcSkqpbZe'),(77,'tester@membrain.com','Tester','$2y$10$BeTff.QD51TP9mlNTPyJwuofRUdyA8Z6v5603srbo6tAT8qYo3HgW',2,0,0,1,NULL,NULL,'2017-07-06 12:49:17','2017-07-06 12:49:17',NULL),(78,'tester1@membrain.com','Tester','$2y$10$Xxuorj/aehgPXbhbLf7OVOs7IECImWCPWOZv3jNGRG8r8hXIyl82O',1,0,0,1,NULL,NULL,'2017-07-06 12:49:37','2017-07-06 12:49:37',NULL),(79,'testss@gmail.com','testz','$2y$10$v/aQq3Miyp6fMZs4wASGoeQ76pYH.F3MROysH6nzo0ktcFaBiKulm',1,0,0,1,NULL,NULL,'2017-07-07 11:43:54','2017-07-07 11:43:54',NULL),(80,'info@cms.com','CMS','$2y$10$u1pFXiuSahDm12uDKMb17uJ/EVN.xn61p9VAHsFtfvnI74HM1Jv7y',2,0,0,1,NULL,'2017-08-01 17:23:46','2017-07-26 23:23:36','2017-07-26 23:23:36','08L7tfgU44CUF7HP3bkPU692AcZD6Ua4fxd0dgBmyQn9zeG1R7yDCiY6mknb'),(83,'sona.singh1810@gmail.com','Sonakshi','$2y$10$QW7H61L05uCpx/viqIMf/ebzDvH/hucHiMclEMpmL5aVEv0RS9ZIO',2,0,0,1,NULL,'2017-07-27 15:43:45','2017-07-27 15:12:55','2017-08-08 19:26:27','dmuvIqQdAZvUoF6EagmoffCkPEy41JnOrrVWqOI8STDrdX2hGgzfjYHIeKj1'),(84,'sonam@gmail.com','sonam','$2y$10$CMTaR/RU6RnSorACS2oRH./LbsFNFrX/oaQLsViU1LY24xjWtfILW',2,0,0,1,NULL,NULL,'2017-07-27 15:26:45','2017-07-27 15:26:45',NULL),(86,'sona@gmail.com','sonam','$2y$10$uiZfdKvd79ihTMOrGXf3HOVwlhTQkJPEv5/6OZQ099S5iIQrakHmy',5,0,0,0,NULL,'2017-07-27 16:06:10','2017-07-27 15:41:58','2017-08-01 17:45:57','iuFrzlRDntZ25vPecXIqc2rIX33s0lr5QbkJU8bZ6UaEN5kISSCs0SBspmLN'),(88,'sss@gmail.com','sss','$2y$10$UKGadKvsfynvixycAq7f7uCCwHNaq2U5DTQqPXv5tqYYmD6Y/Cts2',5,0,0,1,NULL,'2017-07-27 16:05:46','2017-07-27 15:58:50','2017-07-27 15:58:50','ozrJR5OCRKNiga8p51kp1EdoNCQzNNxsj7HPmzgRw43317qd3nDQYJ8GMEHY'),(89,'kk@kk.com','KK','$2y$10$YDaTqSDgAwo0G2iTR9/hN.pJe4I6nC8agbCbA.eeKYmH6vZnyXLNW',3,0,10,1,NULL,'2017-08-01 15:47:24','2017-08-01 15:47:09','2017-08-01 17:44:27','TQVDQStWC7Ev1FQRq0r8e4KQAAqLbAQob2mkrYIBE87zFmE98elUAv42edZB'),(90,'test.user@gmail.com','test','$2y$10$BBvObXoIoMEUWb2GCUqgb.bPcxW5JUSB7jCS34TqdnQRDp7f9A/YW',1,0,0,1,NULL,NULL,'2017-08-01 17:31:30','2017-08-01 17:32:09',NULL),(91,'portal@gmail.com','ABC','$2y$10$uqVjz7nVd08duafTrogfxO0.JPTRDFCIhMaNoorxtbBVc9WVUeiwK',2,0,0,1,NULL,NULL,'2017-08-08 15:32:21','2017-08-14 20:06:18',NULL),(93,'abcde@gmail.com','abcd','$2y$10$ZwCxD88xmSxbEErQm9YuzOEMwafYkfYa75Pt0wxyJfV1pGL5MVfYW',4,40,0,1,NULL,NULL,'2017-08-12 00:50:42','2017-08-12 00:50:42',NULL),(94,'ganesh@gmasil.con','gansh','$2y$10$QgbOkSn88jai0L85sra31.cQSHYqpfZj1U3HuiJjObHkzQpt4JNba',5,0,0,1,NULL,NULL,'2017-08-14 20:44:25','2017-08-14 20:44:25',NULL),(96,'info@airtelcomm.com','airtelcommuuications','$2y$10$vIoCsonhAxEIlqpu1xFxguih21iJpt0IxXXxKRp4WJdm.9pnCKidy',3,0,21,1,NULL,NULL,'2017-08-16 16:07:38','2017-08-16 16:07:38',NULL);
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
INSERT INTO `quarantines` VALUES (1,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-01 20:18:24'),(2,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-01 20:24:06'),(3,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-01 20:29:37'),(4,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-01 20:34:12'),(5,1,1,56,'Excessive Errors','c3490353f7c65e5fbe9aac05a54ebc58_Return-CSV.csv','2017-08-01 20:44:17'),(6,1,1,56,'Excessive Errors','c3490353f7c65e5fbe9aac05a54ebc58_Return-CSV.csv','2017-08-01 20:49:05'),(7,33,1,56,'Excessive Errors','c3490353f7c65e5fbe9aac05a54ebc58_Return-CSV.csv','2017-08-09 14:23:02'),(8,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 16:28:46'),(9,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 19:31:52'),(10,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 19:45:50'),(11,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 19:54:39'),(12,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 20:03:06'),(13,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 20:26:49'),(14,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-11 22:58:35'),(15,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-11 23:05:17'),(16,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-11 23:34:24'),(17,0,1,10,'Excessive Errors','17b938b76022fa80dd1683050030e845_Return-CSV.csv','2017-08-11 23:59:29'),(18,0,1,52,'Excessive Errors','cbe5fa9ddf9a32dfeb1bb1a33d7235ee_Return-CSV.csv','2017-08-15 13:18:21');
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
INSERT INTO `salacious_word` VALUES (1,'/pattern/',1,1,1,1),(2,'/pattern/',1,1,1,1),(3,'/fuck/',1,1,1,1),(4,'/book/',1,1,1,1),(5,'/ebbok/',1,1,1,1);
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
INSERT INTO `suppliers` VALUES (1,'1e0cb7b9cf2df00fee1ad59d08a97855','Avik','mitra.avik89@gmail.com','Avik Mitra','7845129633',10,1,1),(2,'407b20e04c238b3feda82fd167e6068a','Sonali','sonaliroy@mindtechlab.com','Sonali Roy','7894561230',100,1,1),(4,'a6b7ac9aeb165b1b4345b5ce88a04492','Jon','jompauljonce@gmail.com','Jon Paul Jonce','9518476230',19,1,1),(5,'fd41ca938f9dce77f6d39ccac15957be','Nikhil','nikhil@mindtechlabs.com','Nikhis S','2587946130',0,1,1),(6,'d45e8c597ce4091d35ce66826f191863','Anupam Roy','mr.anupamroy@gmail.com','Rudra Sen','7946134512',11,1,1),(7,'079872466d8834dec0df3f5d1d8e06a3','Susanta','isusantakumardas2@gmail.com','Susanta Kr Das','99887755668',10,1,1),(9,'898e1f48bda65d6bebf97d7597dbece6','Niharika','abc@gmail.com','nik','1234567890',70,1,1),(10,'b9d3aa6accc4d06c83d2fe1fa6a6b636','Avikmitra','karthikvarma@gmail.com','Karthik','8686869646',NULL,0,1),(11,'bacf493cf5e953ef1927797ef6bef604','Sachin','sachinvarma@gmail.com','qwqwq','9848032919',NULL,0,1),(12,'234ab9a51dea99b57b6ab39cc5f38ccc','Nithya','nithi@gmail.com','nithin','7676768696',NULL,0,1),(15,'bddbac7aa5bcb2f8ae5422dc5c4438e5','Mindtechlabs','mr.anupamroy@gmail.com','Anupam','9966603576',20,1,1),(17,'98936403f19f0efb80afabfe1c59abe4','rakeshb','rakesh12@gmail.com','basara','9014242976',10,1,1),(19,'32dfb6045d9b0e6b3e026822db8868af','Joydeep','joydeep@gmail.com','Rakshit','9550596606',NULL,0,1),(20,'0cdae0d8e49f6337450bb912db521e49','srinivas','srinivas@gmail.com','srinivas','7895465454',NULL,0,0),(21,'38bbef9bdfe8c9d49eac63998ff9b9e0','Nithin','nithin@gmail.com','Nithin KB','8765432178',10,1,1),(22,'e6d4c34d44b23853794fc91c35821612','membrainrepo','mr.anupamroy3@gmail.com','asxasd','9876543210',20,1,1),(24,'d3df314a6ce6f45d7d937e72a30bf55f','Mahes','mahesh@gmail.com','Krishna','9040769646',90,1,1),(25,'d733e796148b77e39211cfedf8fca513','joydeep a','joydeep.rakshit@gmail.com','joy','9876548654',30,1,1),(26,'49f63a207ffa90509eee902fa80301e7','mahesh','mahi12@gmail.com','mahesh kumar','9746485691',NULL,0,1),(27,'94d30297a68ee58325338ccf562e2451','chunku','chunkupani@gmail.com','Chunku Pani','9602131216',NULL,0,1),(29,'cff9f87017698c43e9d9eb8c05ad3d14','Disha','disha.patani@gmail.com','Disha Patani','4562378910',20,1,1),(30,'3a54e3b0b93d68c27b725955277df831','Nikhil SN','nikhil.saggam@mindtechlabs.com','nik','9494949494',10,0,1),(31,'baa13264f19a5bfd26b57604f36ffa8b','Prasanti','prasanti@gmail.com','Prasanti','9999999990',90,0,1),(32,'06b0d76a1c8b080a12d3f93f85521723','Dell','info@dell.com','Dell Supplier','9865437723',20,1,1),(33,'9fdc69e7e755586a81f33abd868248e8','Amazon supplier','info@amazon.com','Amazon','2345768760',50,1,1),(34,'95bf9c5360445d99c786a6587a0d2bd5','Mindtechlabs','info@mindtechlabs.com','Mindtechlabs','8888899999',40,0,1),(36,'cf8899ff5699da78305a2497b67fc74f','hitechsolutions','pavani654@gmail.com','Pavani','9685743212',10,0,1),(37,'dda82c0f2226b4270a902e0d3b6fe50e','techsolutions','pavani12@gmail.com','PavaniS','989796949',20,0,1),(38,'6be8474bbd771b1177f9dd859f9d4251','saisolutions','sai2@gmsil.com','PavaniS','87979799798',10,0,1),(40,'02ce7a9753cb4da08bd6caec4a901d6c','Firstnew','firststep@gmail.com','Raviraj','3698521478',10,0,1),(41,'3002293b676f9051a40bcfa74c2f97f9','HP printers','info@hpprinters.co.in','HP supports','12345678987',10,0,1),(42,'89708652c75029cfc79e16a42bb3e28b','Test cat b','testcatb@membrain.com','Test cat b','09840110745',10,1,1),(43,'a09bf97f10bb12e620ab0f91b1399e57','Test cat B','membrain@gmail.com','Test cat B','09840157925',0,1,1),(46,'d775af66556f72c1d135ddd2470bee45','sonakshi','sona.singh1810@gmail.com','Sonakshi','99887766554',10,1,1),(47,'92f44bc1068da2b7412945d70b2baf62','madhu','madhu@gmail.com','madhu','99876432156',10,1,1),(48,'2b3000f3495dc3acd7f015804b55c983','accenture','acce@gmail.com','ac','7895462130',0,1,1),(50,'59a29165588173d064e60e9b765093da','Samsung','info@samsung.com','Dinesh patil','04027971602',80,1,1);
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
