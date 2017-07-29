#
# 1.41ÐÞ¶©
#

ALTER TABLE `eq_base_setting` ADD `ajaxLoginURL` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `ajaxCheckURL` ;
ALTER TABLE `eq_base_setting` DROP `ajaxRtnValue`;
ALTER TABLE `eq_base_setting` ADD `ajaxDeleteURL` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `ajaxResponseURL` ;
ALTER TABLE `eq_base_setting` ADD `ajaxOverURL` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `ajaxLoginURL` ;
ALTER TABLE `eq_survey` ADD `ajaxRtnValue` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `isLogicAnd` ;