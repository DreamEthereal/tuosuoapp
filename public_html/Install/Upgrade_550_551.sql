
#
# 5.51 ÐÞ¶©
#

ALTER TABLE  `eq_survey` ADD  `isLimited` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `waitingTime` ,
ADD  `limitedTime` INT( 8 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isLimited` ;
