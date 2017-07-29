

#
# 5.50 ÐÞ¶©
#

ALTER TABLE  `eq_question` ADD  `isHaveUnkown` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `weight` ;
ALTER TABLE  `eq_question` ADD  `requiredMode` INT( 1 ) UNSIGNED DEFAULT  '1' NOT NULL AFTER  `isRequired` ;

