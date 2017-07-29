#
# 2.0 C4ÐÞ¶©
#

ALTER TABLE  `eq_question_radio` ADD  `optionMargin` INT( 4 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionName` ;
ALTER TABLE  `eq_question_checkbox` ADD  `optionMargin` INT( 4 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `optionName` ;

ALTER TABLE  `eq_administratorsconfig` ADD  `mainAttribute` VARCHAR( 100 ) BINARY  DEFAULT '' NOT NULL AFTER  `isUseEmailPass` ;


