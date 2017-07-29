#
# 5.10 ÐÞ¶©
#

ALTER TABLE  `eq_survey` ADD  `isWaiting` INT( 1 ) UNSIGNED DEFAULT  '0' NOT NULL AFTER  `isSecureImage` ;
ALTER TABLE  `eq_survey` ADD  `waitingTime` INT( 2 ) UNSIGNED DEFAULT  '10' NOT NULL AFTER  `isWaiting` ;