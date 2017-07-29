
#
# 10.20 修订
#

ALTER TABLE `eq_survey` ADD  `custDataPath` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '' AFTER  `custTel` ;
ALTER TABLE `eq_survey` ADD  `isShowProof` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `exitTextBody` ;
ALTER TABLE `eq_survey` ADD  `isLowRecord` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isRecord` ;
ALTER TABLE `eq_survey` ADD  `proofRate` INT( 2 ) UNSIGNED NOT NULL DEFAULT  '100' AFTER  `isShowProof` ;
ALTER TABLE `eq_survey` ADD  `isHongBao` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `proofRate` ,ADD  `hongbaoRate` INT( 2 ) UNSIGNED NOT NULL DEFAULT  '100' AFTER  `isHongBao`;

#
# 表的结构 `eq_survey_proof`
#

CREATE TABLE IF NOT EXISTS eq_survey_proof (
  `proofID` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `surveyID` int(30) unsigned NOT NULL DEFAULT '0',
  `proofName` varchar(255) NOT NULL DEFAULT '',
  `proofNum` varchar(100) NOT NULL DEFAULT '',
  `proofPass` varchar(100) NOT NULL DEFAULT '',
  `dataID` int(50) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`proofID`),
  KEY `surveyID` (`surveyID`)
) ENGINE=MyISAM;
