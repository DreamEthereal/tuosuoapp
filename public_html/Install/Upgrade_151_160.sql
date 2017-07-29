#
# 1.60ÐÞ¶©
#

ALTER TABLE `eq_base_setting` CHANGE `isUseOriPassport` `isUseOriPassport` INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL ;
ALTER TABLE `eq_question_radio` CHANGE `optionNameFile` `optionNameFile` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE `eq_question_checkbox` CHANGE `optionNameFile` `optionNameFile` VARCHAR( 100 ) BINARY DEFAULT '' NOT NULL ;