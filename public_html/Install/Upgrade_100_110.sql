#
# V1.1ÐÞ¶©
#

ALTER TABLE eq_survey ADD theme INT( 1 ) UNSIGNED DEFAULT '1' NOT NULL AFTER status ;

ALTER TABLE eq_basesetting ADD sendFrom VARCHAR( 40 ) NOT NULL default '';
ALTER TABLE eq_basesetting ADD sendName VARCHAR( 40 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE eq_basesetting ADD mailServer VARCHAR( 40 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE eq_basesetting ADD smtp25 VARCHAR( 10 ) DEFAULT '25' NOT NULL ;
ALTER TABLE eq_basesetting ADD smtpName VARCHAR( 40 ) BINARY DEFAULT '' NOT NULL ;
ALTER TABLE eq_basesetting ADD smtpPassword VARCHAR( 40 ) DEFAULT '' NOT NULL ;
ALTER TABLE eq_basesetting ADD isSmtp ENUM( 'y', 'n' ) DEFAULT 'n' NOT NULL ;
ALTER TABLE eq_question_range_option CHANGE optionName optionName VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL;
ALTER TABLE eq_question_rank CHANGE optionName optionName VARCHAR( 255 ) BINARY DEFAULT '' NOT NULL;