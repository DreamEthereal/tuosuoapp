#
# 9.0 ÐÞ¶©
#

CREATE TABLE IF NOT EXISTS `eq_gps_trace_upload` (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `responseID` int(30) unsigned NOT NULL DEFAULT '0',
  `qtnID` int(30) NOT NULL DEFAULT '0',
  `gpsTime` varchar(15) NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `speed` varchar(30) NOT NULL DEFAULT '',
  `bearing` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `surveyID` (`surveyID`),
  KEY `responseID` (`responseID`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS `eq_gps_trace` (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `responseID` int(30) unsigned NOT NULL DEFAULT '0',
  `gpsTime` varchar(15) NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `speed` varchar(30) NOT NULL DEFAULT '',
  `bearing` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `surveyID` (`surveyID`),
  KEY `responseID` (`responseID`)
) ENGINE=MyISAM;

ALTER TABLE `eq_survey` ADD  `isFailReApp` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isViewAuthInfo` ;

CREATE TABLE IF NOT EXISTS `eq_ip_white` (
whiteID INT( 30 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
ipAddress VARCHAR( 15 ) BINARY NOT NULL DEFAULT  '',
PRIMARY KEY ( whiteID )
) TYPE = MYISAM;

ALTER TABLE `eq_survey_index` CHANGE  `indexName`  `indexName` VARCHAR( 255 ) NOT NULL DEFAULT  '';

### 2014-02-21ÐÞ¶©
ALTER TABLE `eq_survey` ADD  `isGpsEnable` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isPanelFlag` ,
ADD  `isFingerDrawing` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isGpsEnable` ;

CREATE TABLE IF NOT EXISTS `eq_device_trace` (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `brand` varchar(50) BINARY NOT NULL DEFAULT  '',
  `model` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '',
  `deviceId` varchar(60) BINARY NOT NULL DEFAULT '',
  `nickUserName` varchar(50) BINARY NOT NULL DEFAULT  '',
  `gpsTime` varchar(15) BINARY NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `deviceId` (`deviceId`)
) ENGINE=MyISAM;

ALTER TABLE `eq_conditions` ADD  `logicValueIsAnd` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `eq_associate` ADD  `logicValueIsAnd` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `eq_question` ADD  `isContInvalid` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isHaveWhy` ,
ADD  `contInvalidValue` INT( 2 ) UNSIGNED NULL DEFAULT  '0' AFTER  `isContInvalid` ;

UPDATE `eq_panel` SET isDefault = 1 WHERE panelID =1;
UPDATE `eq_panel` SET isDefault = 0 WHERE panelID =30001;

ALTER TABLE `eq_question` ADD  `coeffMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isNeg` ,
ADD  `coeffTotal` FLOAT( 8, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `coeffMode` ,
ADD  `coeffZeroMargin` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `coeffTotal` ,
ADD  `coeffFullMargin` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `coeffZeroMargin` ,
ADD  `skipMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `coeffFullMargin` ,
ADD  `negCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `skipMode` ;

ALTER TABLE `eq_question` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_checkbox` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_radio` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_range_answer` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_yesno` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';

ALTER TABLE `eq_survey_index` ADD  `fatherId` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `indexDesc` ,
ADD  `isMaxFull` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `fatherId` ,
ADD  `fullValue` FLOAT( 10, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `isMaxFull` ;

CREATE TABLE IF NOT EXISTS `eq_report` (
  reportID int(20) unsigned NOT NULL AUTO_INCREMENT,
  reportName text NOT NULL,
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  reportRecipient text NOT NULL,
  reportFile varchar(100) NOT NULL DEFAULT '',
  reportTime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (reportID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

