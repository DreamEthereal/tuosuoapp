#
# 1.51ÐÞ¶©
#

ALTER TABLE `eq_administratorsconfig` ADD `isNotRegister` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isActive` ;
ALTER TABLE `eq_administratorsconfig` ADD `isUseEmailPass` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `isNotRegister` ;
ALTER TABLE `eq_ip_allow` ADD `surveyID` INT( 20 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `allowIpID` ;
ALTER TABLE `eq_ip_allow` ADD INDEX ( `surveyID` ) ;
ALTER TABLE `eq_survey` ADD `isAllowIP` INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `maxIpTime` ;
ALTER TABLE `eq_base_setting` CHANGE `isUseOriPassport` `isUseOriPassport` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL ;