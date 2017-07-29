#
# V1.2修订
#

ALTER TABLE eq_survey ADD panelID INT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER theme ;

CREATE TABLE IF NOT EXISTS eq_panel (
 panelID INT( 4 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 tplTagName VARCHAR( 50 ) BINARY DEFAULT '' NOT NULL ,
 tplName VARCHAR( 30 ) BINARY DEFAULT '' NOT NULL ,
 isDefault INT( 1 ) UNSIGNED DEFAULT '0' NOT NULL ,
 PRIMARY KEY ( panelID ) 
) TYPE=MyISAM;

INSERT INTO eq_panel ( panelID , tplTagName ,  tplName , isDefault ) 
VALUES ( '1', '标准问卷展现框架模板', 'Panel.html', '1' );