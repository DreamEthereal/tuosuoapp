#
# 表的结构 `eq_administrators`
#

CREATE TABLE IF NOT EXISTS eq_administrators (
  administratorsID int(30) unsigned not null auto_increment,
  administratorsGroupID int(6) unsigned not null default '0',
  userGroupID int(20) unsigned not null default '0',
  administratorsName varchar(100) binary not null default '',
  nickName varchar(100) binary not null default '',
  passWord varchar(32) not null default '',
  hintPass int(2) unsigned not null default '0',
  answerPass varchar(50) not null default '',
  ipAddress varchar(15) not null default '',
  isAdmin int(1) unsigned not null default '0',
  groupType int(1) unsigned default '1' not null,
  isInit int(1) unsigned not null default '0',
  isActive int(1) unsigned not null default '1',
  byUserID int(30) unsigned not null default '0',
  lastVisitTime int(11) unsigned not null default '0',
  loginNum int(6) unsigned not null default '0',
  createDate int(11) unsigned not null default '0',
  PRIMARY KEY  (administratorsID),
  KEY isAdmin (isAdmin),
  KEY isActive (isActive),
  KEY byUserID (byUserID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorsconfig`
#

CREATE TABLE IF NOT EXISTS eq_administratorsconfig (
  administratorsConfigID int(4) unsigned not null auto_increment,
  defaultGroupID int(6) unsigned not null default '0',
  isActive int(1) unsigned not null default '1',
  isNotRegister int(1) unsigned not null default '0',
  isUseEmailPass int(1) unsigned not null default '0',
  mainAttribute varchar(100) binary not null default '',
  registerText text not null,
  PRIMARY KEY  (administratorsConfigID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorsgroup`
#

CREATE TABLE IF NOT EXISTS eq_administratorsgroup (
  administratorsGroupID int(6) unsigned not null auto_increment,
  administratorsGroupName varchar(60) binary not null default '',
  createDate int(11) unsigned not null default '0',
  PRIMARY KEY  (administratorsGroupID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorslog`
#

CREATE TABLE IF NOT EXISTS eq_administratorslog (
  administratorsLogID int(50) unsigned not null auto_increment,
  administratorsID int(30) unsigned not null default '0',
  operationTitle varchar(255) not null default '',
  operationIP varchar(32) not null default '',
  createDate int(11) unsigned not null default '0',
  PRIMARY KEY  (administratorsLogID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorsloginlog`
#

CREATE TABLE IF NOT EXISTS eq_administratorsloginlog (
  administratorsLoginLogID int(4) unsigned not null auto_increment,
  administratorsName varchar(100) not null default '',
  passWord varchar(30) not null default '',
  loginTime int(11) unsigned not null default '0',
  ipAddress varchar(15) not null default '',
  isAdminLogin int(1) unsigned not null default '0',
  PRIMARY KEY  (administratorsLoginLogID),
  KEY isAdminLogin (isAdminLogin)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorsoption`
#

CREATE TABLE IF NOT EXISTS eq_administratorsoption (
  administratorsoptionID int(20) not null auto_increment,
  optionFieldName varchar(50) binary not null default '',
  length int(2) unsigned not null default '40',
  rows int(2) unsigned not null default '4',
  types varchar(10) not null default '',
  content text not null,
  value text not null,
  isPublic int(1) not null default '1',
  isCheckNull int(1) not null default '1',
  isCheckType int(1) not null default '0',
  minNum int(5) not null default '0',
  maxNum int(10) not null default '0',
  orderByID int(10) unsigned not null default '0',
  PRIMARY KEY  (administratorsoptionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_administratorsoptionvalue`
#

CREATE TABLE IF NOT EXISTS eq_administratorsoptionvalue (
  administratorsoptionvalueID int(50) unsigned not null auto_increment,
  administratorsID int(30) unsigned not null default '0',
  administratorsoptionID int(20) unsigned not null default '0',
  value text not null,
  PRIMARY KEY  (administratorsoptionvalueID),
  KEY administratorsID (administratorsID),
  KEY administratorsoptionID (administratorsoptionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_archiving`
#

CREATE TABLE IF NOT EXISTS eq_archiving (
  archivingID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  surveyTitle varchar(255) not null default '',
  surveyName varchar(64) binary not null default '',
  administratorsID int(30) unsigned not null default '0',
  isPublic int(1) unsigned not null default '1',
  beginTime date not null default '0000-00-00',
  endTime date not null default '0000-00-00',
  archivingOwner int(30) unsigned not null default '0',
  archivingFile varchar(100) binary not null default '',
  archivingTime int(11) unsigned not null default '0',
  PRIMARY KEY  (archivingID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_award_list`
#

CREATE TABLE IF NOT EXISTS eq_award_list (
  awardID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  awardListID int(20) unsigned not null default '0',
  responseID int(30) unsigned not null default '0',
  ipAddress varchar(15) binary not null default '',
  administratorsName varchar(100) binary not null default '',
  PRIMARY KEY  (awardID),
  KEY surveyID (surveyID),
  KEY awardListID (awardListID),
  KEY responseID (responseID),
  KEY ipAddress (ipAddress),
  KEY administratorsName (administratorsName)
) ENGINE=MyISAM;

#
# 表的结构 `eq_award_product`
#

CREATE TABLE IF NOT EXISTS eq_award_product (
  awardListID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  awardType varchar(20) binary not null default '',
  awardProduct varchar(200) not null default '',
  awardNum int(6) not null default '0',
  PRIMARY KEY  (awardListID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_base_setting`
#

CREATE TABLE IF NOT EXISTS eq_base_setting (
  baseSettingID int(1) unsigned not null auto_increment,
  isUseOriPassport int(1) unsigned not null default '1',
  isUseCookie int(1) unsigned not null default '0',
  userID varchar(30) binary not null default '',
  userName varchar(30) binary not null default '',
  registerURL varchar(255) not null default '',
  loginURL varchar(255) not null default '',
  ajaxResponseURL varchar(255) not null default '',
  ajaxDeleteURL varchar(255) not null default '',
  ajaxCheckURL varchar(255) not null default '',
  ajaxLoginURL varchar(255) not null default '',
  ajaxOverURL varchar(255) not null default '',
  domainControllers varchar(255) not null default '',
  adUsername varchar(60) binary not null default '',
  accountSuffix varchar(100) binary not null default '',
  adPassword varchar(20) binary not null default '',
  baseDN varchar(255) not null default '',
  sendFrom varchar(40) not null default '',
  sendName varchar(40) binary not null default '',
  mailServer varchar(40) binary not null default '',
  smtp25 varchar(10) not null default '25',
  smtpName varchar(40) binary not null default '',
  smtpPassword varchar(40) not null default '',
  isSmtp enum('y','n') not null default 'n',
  isAllowIP int(1) unsigned not null default '0',
  license varchar(100) binary not null default '',
  licensetime varchar(100) binary not null default '',
  PRIMARY KEY  (baseSettingID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_cond_rel`
#

CREATE TABLE IF NOT EXISTS eq_cond_rel (
  condRelID int(30) unsigned not null auto_increment,
  fatherID int(20) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  sonID int(20) unsigned not null default '0',
  PRIMARY KEY  (condRelID),
  KEY fatherID (fatherID),
  KEY sonID (sonID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_conditions`
#

CREATE TABLE IF NOT EXISTS eq_conditions (
  conditionsID int(10) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  condOnID int(30) unsigned not null default '0',
  opertion int(1) unsigned not null default '1',
  optionID int(20) unsigned not null default '0',
  qtnID int(20) unsigned not null default '0',
  quotaID int(20) unsigned not null default '0',
  PRIMARY KEY  (conditionsID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY administratorsID (administratorsID),
  KEY optionID (optionID),
  KEY condOnID (condOnID),
  KEY qtnID (qtnID),
  KEY quotaID (quotaID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_downs`
#

CREATE TABLE IF NOT EXISTS eq_downs (
  downsID int(3) unsigned not null auto_increment,
  downsName varchar(100) not null default '',
  downsContent text not null,
  filename varchar(100) not null default '',
  filesize decimal(10,2) not null default '0.00',
  isPublic enum('y','n') not null default 'y',
  createDate int(11) unsigned not null default '0',
  updateDate int(11) unsigned not null default '0',
  orderByID int(10) unsigned not null default '0',
  PRIMARY KEY  (downsID)
) ENGINE=MyISAM;

#
# 导出表中的数据 `eq_downs`
#

TRUNCATE TABLE `eq_downs`;
INSERT INTO eq_downs VALUES (1, 'EnableQ系统快速使用指南', 'EnableQ在线问卷调查引擎参考手册：快速使用指南', 'EnableQQuickGuide.zip', '728', 'y', 1334897089, 1334897089, 1);
INSERT INTO eq_downs VALUES (2, 'EnableQ系统用户手册', 'EnableQ在线问卷调查引擎参考手册：系统用户手册', 'EnableQUserManual.html', '1.00', 'y', 1250052832, 1250052832, 2);
INSERT INTO eq_downs VALUES (3, 'EnableQ开发人员手册', 'EnableQ在线问卷调查引擎参考手册：数据接口规范', 'EnableQDeveloperManual.zip', '233.00', 'y', 1250052832, 1250052832, 3);
INSERT INTO eq_downs VALUES (4, 'CSV文件分割器', 'EnableQ在线问卷调查引擎-CSV文件分割器<br>EnableQ系统提供的辅助工具，用以对导出的问卷结果数据CSV大文件进行自定义小文件分割。<br>可把较多行数或列数的CSV文件分割成独立的多个小CSV文件。', 'csvsplit.exe', '52.00', 'y', 1244166190, 1244166190, 4);

#
# 表的结构 `eq_grade`
#

CREATE TABLE IF NOT EXISTS eq_grade (
  gradeID int(6) unsigned not null auto_increment,
  startOperator varchar(4) binary not null default '',
  startGrade float(8,2) not null default '0.00',
  endOperator varchar(4) binary not null default '',
  endGrade float(8,2) not null default '0.00',
  surveyID int(20) unsigned not null default '0',
  conclusion text not null,
  PRIMARY KEY  (gradeID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_input_user_list`
#

CREATE TABLE IF NOT EXISTS eq_input_user_list (
  inputUserListID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_ip_allow`
#

CREATE TABLE IF NOT EXISTS eq_ip_allow (
  allowIpID int(6) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  startIP varchar(15) not null default '',
  endIP varchar(15) not null default '',
  PRIMARY KEY  (allowIpID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_ip_banned`
#

CREATE TABLE IF NOT EXISTS eq_ip_banned (
  bannedID int(30) unsigned not null auto_increment,
  ipAddress varchar(15) binary not null default '',
  PRIMARY KEY  (bannedID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_ipdatabase`
#

CREATE TABLE IF NOT EXISTS eq_ipdatabase (
  StartIp varchar(15) not null default '',
  EndIp varchar(15) not null default '',
  Area varchar(100) binary not null default ''
) ENGINE=MyISAM;

#
# 导出表中的数据 `eq_ipdatabase`
#

TRUNCATE TABLE `eq_ipdatabase`;
INSERT INTO eq_ipdatabase VALUES ('000.000.000.000', '002.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('003.000.000.000', '004.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('005.000.000.000', '005.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('006.000.000.000', '009.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('010.000.000.000', '010.255.255.255', '局域网');
INSERT INTO eq_ipdatabase VALUES ('011.000.000.000', '022.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('023.000.000.000', '023.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('024.000.000.000', '058.013.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.014.000.000', '058.015.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('058.016.000.000', '058.016.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('058.017.000.000', '058.017.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.017.128.000', '058.017.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('058.018.000.000', '058.018.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('058.019.000.000', '058.020.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.021.000.000', '058.021.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('058.022.000.000', '058.023.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('058.024.000.000', '058.025.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.026.000.000', '058.029.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.030.000.000', '058.031.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.032.000.000', '058.041.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.042.000.000', '058.042.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('058.043.000.000', '058.043.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('058.044.000.000', '058.055.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.056.000.000', '058.059.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('058.059.128.000', '058.059.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.060.000.000', '058.063.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.064.000.000', '058.065.231.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.065.232.000', '058.065.239.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('058.065.240.000', '058.065.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.066.000.000', '058.066.127.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.066.128.000', '058.066.207.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.066.208.000', '058.066.223.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.066.224.000', '058.066.231.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.066.232.000', '058.066.239.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.066.240.000', '058.066.251.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('058.066.252.000', '058.066.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.067.000.000', '058.067.031.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.067.032.000', '058.067.047.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.067.048.000', '058.067.063.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.067.064.000', '058.067.071.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.067.072.000', '058.067.111.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.067.112.000', '058.067.119.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('058.067.120.000', '058.067.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.067.128.000', '058.067.141.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('058.067.142.000', '058.067.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.067.144.000', '058.067.159.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.067.160.000', '058.067.179.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('058.067.180.000', '058.067.247.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('058.067.248.000', '058.067.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.068.000.000', '058.068.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.068.128.000', '058.068.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.069.000.000', '058.081.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.082.000.000', '058.082.063.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.082.064.000', '058.082.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.082.096.000', '058.082.103.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.082.104.000', '058.082.111.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.082.112.000', '058.082.123.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.082.124.000', '058.082.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.082.128.000', '058.082.143.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.082.144.000', '058.082.159.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('058.082.160.000', '058.082.175.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.082.176.000', '058.082.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.082.192.000', '058.082.207.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('058.082.208.000', '058.082.223.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('058.082.224.000', '058.082.239.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.082.240.000', '058.082.247.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('058.082.248.000', '058.082.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('058.083.000.000', '058.083.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.083.032.000', '058.083.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('058.083.064.000', '058.083.127.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('058.083.128.000', '058.083.139.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('058.083.140.000', '058.083.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('058.084.000.000', '058.085.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.086.000.000', '058.086.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('058.087.000.000', '058.087.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.087.064.000', '058.087.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.087.128.000', '058.098.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.099.000.000', '058.099.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('058.099.128.000', '058.099.189.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.099.190.000', '058.099.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.099.192.000', '058.099.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.100.000.000', '058.100.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.101.000.000', '058.101.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('058.101.064.000', '058.101.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.101.192.000', '058.101.223.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('058.101.224.000', '058.101.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.102.000.000', '058.113.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.114.000.000', '058.115.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('058.116.000.000', '058.119.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.120.000.000', '058.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.128.000.000', '058.135.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.136.000.000', '058.143.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.144.000.000', '058.144.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('058.145.000.000', '058.151.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.152.000.000', '058.153.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('058.154.000.000', '058.154.033.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('058.154.034.000', '058.154.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.154.128.000', '058.154.159.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('058.154.160.000', '058.154.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.155.000.000', '058.155.095.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('058.155.096.000', '058.155.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.155.128.000', '058.155.155.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('058.155.156.000', '058.155.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.156.000.000', '058.175.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.176.000.000', '058.177.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('058.178.000.000', '058.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('058.192.000.000', '058.193.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('058.194.000.000', '058.194.007.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('058.194.008.000', '058.194.009.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.194.010.000', '058.194.023.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.194.024.000', '058.194.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.194.032.000', '058.194.063.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('058.194.064.000', '058.194.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.194.096.000', '058.194.119.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('058.194.120.000', '058.194.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('058.194.128.000', '058.194.143.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('058.194.144.000', '058.194.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.194.160.000', '058.194.215.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('058.194.216.000', '058.194.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.194.224.000', '058.194.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.195.000.000', '058.195.007.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.195.008.000', '058.195.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.195.016.000', '058.195.031.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('058.195.032.000', '058.195.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.195.128.000', '058.195.135.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('058.195.136.000', '058.195.247.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.195.248.000', '058.195.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.196.000.000', '058.196.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.196.128.000', '058.196.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.196.192.000', '058.197.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.198.000.000', '058.198.023.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.198.024.000', '058.205.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('058.206.000.000', '058.206.031.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('058.206.032.000', '058.206.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.206.064.000', '058.206.095.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('058.206.096.000', '058.206.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('058.206.128.000', '058.206.159.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('058.206.160.000', '058.206.191.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('058.206.192.000', '058.206.223.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('058.206.224.000', '058.207.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('058.207.016.000', '058.207.039.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.207.040.000', '058.207.047.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('058.207.048.000', '058.207.067.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('058.207.068.000', '058.207.071.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('058.207.072.000', '058.207.079.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('058.207.080.000', '058.207.095.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('058.207.096.000', '058.207.119.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('058.207.120.000', '058.207.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.207.128.000', '058.207.143.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('058.207.144.000', '058.207.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.207.160.000', '058.207.175.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('058.207.176.000', '058.207.199.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('058.207.200.000', '058.207.223.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('058.207.224.000', '058.207.247.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('058.207.248.000', '058.207.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('058.208.000.000', '058.241.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('058.242.000.000', '058.243.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('058.244.000.000', '058.245.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('058.246.000.000', '058.247.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('058.248.000.000', '059.042.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('059.043.000.000', '059.043.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('059.044.000.000', '059.046.039.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.046.040.000', '059.046.067.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.046.068.000', '059.046.079.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.046.080.000', '059.046.087.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.046.088.000', '059.046.095.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.046.096.000', '059.046.103.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.046.104.000', '059.047.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.048.000.000', '059.049.127.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('059.049.128.000', '059.050.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('059.051.000.000', '059.051.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.051.128.000', '059.051.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('059.052.000.000', '059.053.051.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.053.052.000', '059.053.067.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('059.053.068.000', '059.054.079.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.054.080.000', '059.054.127.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('059.054.128.000', '059.055.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.056.000.000', '059.061.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('059.062.000.000', '059.063.159.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.063.160.000', '059.063.207.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('059.063.208.000', '059.063.223.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.063.224.000', '059.063.231.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('059.063.232.000', '059.063.239.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('059.063.240.000', '059.063.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('059.064.000.000', '059.066.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('059.067.000.000', '059.067.191.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('059.067.192.000', '059.067.221.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('059.067.222.000', '059.067.223.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('059.067.224.000', '059.067.247.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('059.067.248.000', '059.067.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('059.068.000.000', '059.069.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.069.128.000', '059.070.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('059.071.000.000', '059.071.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.072.000.000', '059.072.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('059.073.000.000', '059.073.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.073.192.000', '059.073.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('059.074.000.000', '059.075.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('059.075.128.000', '059.075.141.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('059.075.142.000', '059.075.143.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('059.075.144.000', '059.075.183.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('059.075.184.000', '059.075.191.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('059.075.192.000', '059.075.209.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('059.075.210.000', '059.075.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('059.076.000.000', '059.076.191.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('059.076.192.000', '059.076.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('059.077.000.000', '059.077.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('059.078.000.000', '059.079.111.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('059.079.112.000', '059.079.113.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('059.079.114.000', '059.079.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('059.079.128.000', '059.079.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('059.079.192.000', '059.079.194.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('059.079.195.000', '059.079.223.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('059.079.224.000', '059.079.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('059.080.000.000', '059.083.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('059.084.000.000', '059.103.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.104.000.000', '059.105.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('059.106.000.000', '059.106.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.107.000.000', '059.107.051.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('059.107.052.000', '059.107.095.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('059.107.096.000', '059.107.127.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.107.128.000', '059.107.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.108.000.000', '059.109.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('059.110.000.000', '059.111.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('059.112.000.000', '059.127.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('059.128.000.000', '059.147.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.148.000.000', '059.149.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('059.150.000.000', '059.150.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.151.000.000', '059.151.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('059.151.048.000', '059.151.051.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('059.151.052.000', '059.151.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('059.151.128.000', '059.152.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.152.192.000', '059.152.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('059.152.224.000', '059.154.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.155.000.000', '059.155.035.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.155.036.000', '059.155.047.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('059.155.048.000', '059.155.087.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.155.088.000', '059.155.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('059.155.096.000', '059.155.159.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.155.160.000', '059.155.171.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('059.155.172.000', '059.155.175.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.155.176.000', '059.155.191.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('059.155.192.000', '059.155.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.156.000.000', '059.171.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.172.000.000', '059.175.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('059.176.000.000', '059.187.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.188.000.000', '059.188.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('059.189.000.000', '059.190.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.191.000.000', '059.191.071.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('059.191.072.000', '059.191.079.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('059.191.080.000', '059.191.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('059.191.096.000', '059.191.127.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('059.191.128.000', '059.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('059.192.000.000', '059.255.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.000.000.000', '060.002.088.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('060.002.089.000', '060.002.089.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('060.002.090.000', '060.010.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('060.011.000.000', '060.011.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('060.012.000.000', '060.012.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('060.013.000.000', '060.013.063.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('060.013.064.000', '060.013.127.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('060.013.128.000', '060.013.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('060.014.000.000', '060.015.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('060.016.000.000', '060.023.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('060.024.000.000', '060.030.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('060.031.000.000', '060.031.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('060.032.000.000', '060.054.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.055.000.000', '060.055.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('060.056.000.000', '060.062.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.063.000.000', '060.063.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('060.064.000.000', '060.159.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.160.000.000', '060.161.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('060.162.000.000', '060.165.255.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('060.166.000.000', '060.175.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('060.176.000.000', '060.191.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('060.192.000.000', '060.193.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.194.000.000', '060.195.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.196.000.000', '060.197.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.198.000.000', '060.199.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('060.200.000.000', '060.204.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('060.204.128.000', '060.204.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('060.204.192.000', '060.204.207.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('060.204.208.000', '060.204.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('060.205.000.000', '060.205.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('060.206.000.000', '060.207.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.208.000.000', '060.217.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('060.218.000.000', '060.219.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('060.220.000.000', '060.223.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('060.224.000.000', '060.231.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.232.000.000', '060.233.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('060.234.000.000', '060.234.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.235.000.000', '060.235.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('060.236.000.000', '060.243.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.244.000.000', '060.245.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('060.245.096.000', '060.245.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.245.128.000', '060.245.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.246.000.000', '060.246.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.247.000.000', '060.247.002.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.247.003.000', '060.247.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('060.248.000.000', '060.251.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('060.252.000.000', '060.252.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('060.253.000.000', '060.253.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.253.128.000', '060.253.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('060.254.000.000', '060.254.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('060.255.000.000', '060.255.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.000.000.000', '061.003.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.004.000.000', '061.004.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.004.064.000', '061.004.079.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.004.080.000', '061.004.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.004.096.000', '061.004.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.004.176.000', '061.004.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.004.192.000', '061.008.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.008.160.000', '061.008.175.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.008.176.000', '061.009.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.010.000.000', '061.010.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.011.000.000', '061.014.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.014.128.000', '061.014.131.095', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.014.131.096', '061.014.164.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.014.165.000', '061.014.165.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.014.166.000', '061.014.174.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.014.175.000', '061.014.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.014.176.000', '061.014.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.015.000.000', '061.015.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.016.000.000', '061.017.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.018.000.000', '061.018.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.019.000.000', '061.019.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.020.000.000', '061.020.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.021.000.000', '061.027.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.028.000.000', '061.028.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.028.128.000', '061.029.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.029.128.000', '061.029.145.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.029.146.000', '061.029.147.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.029.148.000', '061.029.199.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.029.200.000', '061.029.203.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.029.204.000', '061.029.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.029.208.000', '061.029.215.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.029.216.000', '061.029.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.029.224.000', '061.029.239.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.029.240.000', '061.029.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.030.000.000', '061.031.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.032.000.000', '061.045.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.045.128.000', '061.045.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.045.192.000', '061.047.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.047.128.000', '061.047.191.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.047.192.000', '061.047.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.048.000.000', '061.051.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.052.000.000', '061.054.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.055.000.000', '061.055.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.056.000.000', '061.067.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.068.000.000', '061.069.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.070.000.000', '061.071.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.072.000.000', '061.087.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.087.192.000', '061.087.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.088.000.000', '061.091.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.092.000.000', '061.093.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.094.000.000', '061.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.128.000.000', '061.128.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.128.096.000', '061.128.127.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.128.128.000', '061.128.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.129.000.000', '061.129.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.130.000.000', '061.130.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.131.000.000', '061.131.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.131.128.000', '061.131.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('061.132.000.000', '061.132.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.132.128.000', '061.132.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.133.000.000', '061.133.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.133.128.000', '061.133.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.133.192.000', '061.133.223.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('061.133.224.000', '061.133.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('061.134.000.000', '061.134.063.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.134.064.000', '061.134.095.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.134.096.000', '061.134.127.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.134.128.000', '061.134.191.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.134.192.000', '061.134.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.135.000.000', '061.135.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.136.000.000', '061.136.063.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('061.136.064.000', '061.136.127.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.136.128.000', '061.137.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.137.128.000', '061.137.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.138.000.000', '061.138.063.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.138.064.000', '061.138.127.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.138.128.000', '061.138.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.138.192.000', '061.138.223.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('061.138.224.000', '061.138.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.139.000.000', '061.139.127.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.139.128.000', '061.139.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.139.192.000', '061.139.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.140.000.000', '061.146.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.147.000.000', '061.147.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.148.000.000', '061.149.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.150.000.000', '061.150.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.150.128.000', '061.150.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.151.000.000', '061.152.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.153.000.000', '061.153.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.154.000.000', '061.154.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.155.000.000', '061.155.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.156.000.000', '061.156.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.157.000.000', '061.157.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.158.000.000', '061.158.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.158.128.000', '061.158.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.159.000.000', '061.159.063.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.159.064.000', '061.159.127.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.159.128.000', '061.159.185.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('061.159.186.000', '061.159.187.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.159.188.000', '061.159.191.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('061.159.192.000', '061.159.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('061.160.000.000', '061.160.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.161.000.000', '061.161.063.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.161.064.000', '061.161.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.161.128.000', '061.161.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.162.000.000', '061.162.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.163.000.000', '061.163.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.164.000.000', '061.164.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.165.000.000', '061.165.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.166.000.000', '061.166.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('061.167.000.000', '061.167.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.168.000.000', '061.168.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.169.000.000', '061.173.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.174.000.000', '061.175.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.176.000.000', '061.176.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.177.000.000', '061.177.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.178.000.000', '061.178.255.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.179.000.000', '061.179.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.180.000.000', '061.180.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.181.000.000', '061.181.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('061.182.000.000', '061.182.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.183.000.000', '061.185.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.186.000.000', '061.186.063.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('061.186.064.000', '061.186.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.186.128.000', '061.186.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.187.000.000', '061.187.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.188.000.000', '061.188.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.189.000.000', '061.189.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.189.128.000', '061.189.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('061.190.000.000', '061.191.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.192.000.000', '061.215.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.216.000.000', '061.231.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.232.000.000', '061.232.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.232.016.000', '061.232.027.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('061.232.028.000', '061.232.035.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.232.036.000', '061.232.041.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.232.042.000', '061.232.044.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.232.045.000', '061.232.045.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.232.046.000', '061.232.046.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.232.047.000', '061.232.059.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.232.060.000', '061.232.060.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.232.061.000', '061.232.062.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.232.063.000', '061.232.077.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('061.232.078.000', '061.232.079.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.232.080.000', '061.232.080.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.232.081.000', '061.232.084.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.232.085.000', '061.232.089.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.232.090.000', '061.232.107.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.232.108.000', '061.232.115.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.232.116.000', '061.232.122.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.232.123.000', '061.232.135.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.232.136.000', '061.232.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.232.144.000', '061.232.145.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.232.146.000', '061.232.149.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.232.150.000', '061.232.156.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.232.157.000', '061.232.166.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.232.167.000', '061.232.168.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.232.169.000', '061.232.175.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.232.176.000', '061.232.177.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.232.178.000', '061.232.183.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.232.184.000', '061.232.191.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.232.192.000', '061.232.203.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.232.204.000', '061.232.211.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.232.212.000', '061.232.214.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.232.215.000', '061.232.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.232.224.000', '061.232.226.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.232.227.000', '061.232.231.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.232.232.000', '061.232.235.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('061.232.236.000', '061.232.243.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.232.244.000', '061.232.251.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('061.232.252.000', '061.232.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.233.000.000', '061.233.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.233.032.000', '061.233.034.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.233.035.000', '061.233.035.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.233.036.000', '061.233.037.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.233.038.000', '061.233.043.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.233.044.000', '061.233.051.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.233.052.000', '061.233.063.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.233.064.000', '061.233.127.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.233.128.000', '061.233.143.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.233.144.000', '061.233.159.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.233.160.000', '061.233.191.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.233.192.000', '061.233.207.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.233.208.000', '061.233.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('061.234.000.000', '061.234.015.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.234.016.000', '061.234.031.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.234.032.000', '061.234.063.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.234.064.000', '061.234.065.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.234.066.000', '061.234.079.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.234.080.000', '061.234.095.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('061.234.096.000', '061.234.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.234.128.000', '061.234.143.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.234.144.000', '061.234.151.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.234.152.000', '061.234.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.234.176.000', '061.234.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.234.192.000', '061.234.227.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.234.228.000', '061.234.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.235.000.000', '061.235.035.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('061.235.036.000', '061.235.047.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.235.048.000', '061.235.063.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.235.064.000', '061.235.131.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.235.132.000', '061.235.159.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.235.160.000', '061.235.195.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.235.196.000', '061.235.223.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.235.224.000', '061.235.239.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('061.235.240.000', '061.235.247.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.235.248.000', '061.236.063.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.236.064.000', '061.236.079.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.236.080.000', '061.236.119.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.236.120.000', '061.236.159.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.236.160.000', '061.236.175.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('061.236.176.000', '061.236.207.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('061.236.208.000', '061.236.215.255', '中国');
INSERT INTO eq_ipdatabase VALUES ('061.236.216.000', '061.236.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.237.000.000', '061.237.009.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.237.010.000', '061.237.011.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.237.012.000', '061.237.012.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.237.013.000', '061.237.013.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.237.014.000', '061.237.014.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.237.015.000', '061.237.015.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.237.016.000', '061.237.016.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.237.017.000', '061.237.017.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.237.018.000', '061.237.018.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.237.019.000', '061.237.019.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.237.020.000', '061.237.020.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.237.021.000', '061.237.021.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.237.022.000', '061.237.022.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.237.023.000', '061.237.024.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.237.025.000', '061.237.025.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.237.026.000', '061.237.026.255', '中国甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.237.027.000', '061.237.027.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.237.028.000', '061.237.067.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.237.068.000', '061.237.068.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.237.069.000', '061.237.081.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.237.082.000', '061.237.082.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.237.083.000', '061.237.085.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.237.086.000', '061.237.086.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.237.087.000', '061.237.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.237.128.000', '061.237.143.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.237.144.000', '061.237.159.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.237.160.000', '061.237.175.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.237.176.000', '061.237.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.237.192.000', '061.237.195.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.237.196.000', '061.237.196.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.237.197.000', '061.237.223.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.237.224.000', '061.237.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('061.238.000.000', '061.239.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.240.000.000', '061.240.031.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('061.240.032.000', '061.240.063.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('061.240.064.000', '061.240.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('061.240.128.000', '061.240.159.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('061.240.160.000', '061.240.190.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.240.191.000', '061.240.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.240.224.000', '061.240.231.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.240.232.000', '061.240.239.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.240.240.000', '061.240.246.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('061.240.247.000', '061.240.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.241.000.000', '061.241.065.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('061.241.066.000', '061.241.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('061.241.128.000', '061.241.159.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('061.241.160.000', '061.241.175.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('061.241.176.000', '061.241.191.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('061.241.192.000', '061.241.223.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('061.241.224.000', '061.241.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.242.000.000', '061.242.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('061.242.128.000', '061.242.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('061.242.144.000', '061.242.159.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('061.242.160.000', '061.242.191.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('061.242.192.000', '061.242.223.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('061.242.224.000', '061.242.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('061.243.000.000', '061.243.031.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('061.243.032.000', '061.243.047.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('061.243.048.000', '061.243.055.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('061.243.056.000', '061.243.063.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('061.243.064.000', '061.243.095.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('061.243.096.000', '061.243.111.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('061.243.112.000', '061.243.119.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('061.243.120.000', '061.243.123.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('061.243.124.000', '061.243.127.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('061.243.128.000', '061.243.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('061.243.192.000', '061.243.223.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('061.243.224.000', '061.243.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('061.244.000.000', '061.244.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('061.245.000.000', '061.247.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('061.247.160.000', '061.247.175.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('061.247.176.000', '091.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('092.000.000.000', '096.001.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('096.002.000.000', '096.002.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('096.003.000.000', '096.003.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('096.004.000.000', '096.006.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('096.006.128.000', '096.223.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('096.224.000.000', '096.240.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('096.241.000.000', '096.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('097.000.000.000', '097.007.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('097.008.000.000', '097.067.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('097.068.000.000', '097.069.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('097.069.208.000', '097.079.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('097.080.000.000', '097.086.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('097.087.000.000', '097.095.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('097.096.000.000', '097.101.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('097.101.112.000', '097.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('098.000.000.000', '098.007.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('098.007.192.000', '098.015.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('098.016.000.000', '098.017.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('098.018.000.000', '098.191.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('098.192.000.000', '098.207.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('098.208.000.000', '099.207.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('099.208.000.000', '099.221.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('099.221.128.000', '099.223.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('099.224.000.000', '099.253.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('099.253.160.000', '115.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('116.000.000.000', '116.000.023.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.000.024.000', '116.000.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.000.032.000', '116.000.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.001.000.000', '116.001.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('116.002.000.000', '116.003.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('116.004.000.000', '116.007.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('116.008.000.000', '116.011.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('116.012.000.000', '116.012.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.013.000.000', '116.013.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.014.000.000', '116.015.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.016.000.000', '116.031.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('116.032.000.000', '116.047.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.048.000.000', '116.049.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.050.000.000', '116.050.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.050.008.000', '116.050.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.050.016.000', '116.050.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.050.032.000', '116.050.047.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.050.048.000', '116.051.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.052.000.000', '116.055.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('116.056.000.000', '116.057.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.058.000.000', '116.058.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.058.128.000', '116.058.143.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('116.058.144.000', '116.058.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.058.208.000', '116.058.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('116.058.224.000', '116.058.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.059.000.000', '116.059.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.060.000.000', '116.063.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.064.000.000', '116.065.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.066.000.000', '116.066.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.066.128.000', '116.066.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.066.208.000', '116.066.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.066.224.000', '116.068.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.069.000.000', '116.070.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.070.128.000', '116.075.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.076.000.000', '116.077.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('116.078.000.000', '116.079.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.080.000.000', '116.089.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.089.128.000', '116.089.143.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.089.144.000', '116.089.159.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('116.089.160.000', '116.090.183.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.090.184.000', '116.090.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('116.090.192.000', '116.091.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.092.000.000', '116.092.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.093.000.000', '116.094.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.095.000.000', '116.095.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('116.096.000.000', '116.111.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.112.000.000', '116.117.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('116.118.000.000', '116.118.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.118.128.000', '116.118.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.118.192.000', '116.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.128.000.000', '116.191.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.192.000.000', '116.192.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('116.193.000.000', '116.193.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.193.008.000', '116.193.015.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('116.193.016.000', '116.193.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('116.193.032.000', '116.193.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('116.193.064.000', '116.193.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.194.000.000', '116.196.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.197.000.000', '116.197.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.197.176.000', '116.197.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.197.192.000', '116.197.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.198.000.000', '116.198.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.199.000.000', '116.199.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('116.199.128.000', '116.199.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.199.160.000', '116.203.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.204.000.000', '116.205.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.206.000.000', '116.206.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.207.000.000', '116.211.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('116.212.000.000', '116.212.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.212.080.000', '116.212.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.212.096.000', '116.212.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.212.112.000', '116.212.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('116.212.128.000', '116.212.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.212.160.000', '116.212.175.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('116.212.176.000', '116.213.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.213.064.000', '116.213.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('116.214.000.000', '116.214.015.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('116.214.016.000', '116.214.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.214.032.000', '116.214.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.214.064.000', '116.214.079.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('116.214.080.000', '116.214.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.214.128.000', '116.223.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.224.000.000', '116.239.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('116.240.000.000', '116.241.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.242.000.000', '116.245.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.246.000.000', '116.247.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('116.248.000.000', '116.249.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('116.250.000.000', '116.251.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.252.000.000', '116.253.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('116.254.000.000', '116.254.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.254.128.000', '116.254.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('116.254.192.000', '116.254.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('116.255.000.000', '116.255.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('116.255.128.000', '116.255.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('117.000.000.000', '117.007.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('117.008.000.000', '117.015.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('117.016.000.000', '121.000.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.000.016.000', '121.000.031.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.000.032.000', '121.003.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.004.000.000', '121.005.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.006.000.000', '121.007.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.008.000.000', '121.015.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.016.000.000', '121.029.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('121.030.000.000', '121.030.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('121.031.000.000', '121.031.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('121.032.000.000', '121.036.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.036.032.000', '121.036.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.036.064.000', '121.036.075.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('121.036.076.000', '121.036.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.036.128.000', '121.036.151.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('121.036.152.000', '121.036.167.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.036.168.000', '121.036.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.036.176.000', '121.036.179.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('121.036.180.000', '121.036.187.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.036.188.000', '121.036.191.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('121.036.192.000', '121.036.195.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.036.196.000', '121.036.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.037.000.000', '121.037.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('121.037.032.000', '121.040.000.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.040.001.000', '121.040.001.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.040.002.000', '121.040.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.040.032.000', '121.040.063.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('121.040.064.000', '121.040.067.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('121.040.068.000', '121.040.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.040.192.000', '121.040.207.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('121.040.208.000', '121.040.215.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('121.040.216.000', '121.040.216.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.040.217.000', '121.040.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.040.224.000', '121.040.239.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('121.040.240.000', '121.040.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.041.000.000', '121.041.003.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('121.041.004.000', '121.041.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.041.128.000', '121.041.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('121.042.000.000', '121.043.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('121.044.000.000', '121.045.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.046.000.000', '121.046.063.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.046.064.000', '121.046.125.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.046.126.000', '121.046.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('121.046.128.000', '121.046.128.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('121.046.129.000', '121.046.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.046.160.000', '121.046.191.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('121.046.192.000', '121.046.223.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.046.224.000', '121.046.224.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('121.046.225.000', '121.046.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.047.000.000', '121.047.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.048.000.000', '121.048.093.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.048.094.000', '121.048.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.048.096.000', '121.048.115.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.048.116.000', '121.048.119.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.048.120.000', '121.048.143.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.048.144.000', '121.048.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.048.192.000', '121.048.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.049.000.000', '121.049.005.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('121.049.006.000', '121.049.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.049.128.000', '121.049.151.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('121.049.152.000', '121.049.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.049.192.000', '121.049.194.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('121.049.195.000', '121.049.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.050.000.000', '121.050.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.051.000.000', '121.051.055.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.051.056.000', '121.051.059.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.051.060.000', '121.051.061.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.051.062.000', '121.051.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.051.064.000', '121.051.116.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.051.117.000', '121.051.117.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.051.118.000', '121.051.125.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.051.126.000', '121.051.131.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.051.132.000', '121.051.141.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('121.051.142.000', '121.051.195.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.051.196.000', '121.051.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('121.052.000.000', '121.054.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.055.000.000', '121.055.063.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.055.064.000', '121.055.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.056.000.000', '121.057.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('121.058.000.000', '121.058.127.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('121.058.128.000', '121.059.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.060.000.000', '121.063.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('121.064.000.000', '121.067.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.068.000.000', '121.068.015.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.068.016.000', '121.068.063.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.068.064.000', '121.068.083.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.068.084.000', '121.068.119.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.068.120.000', '121.068.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.068.128.000', '121.068.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('121.069.000.000', '121.069.239.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('121.069.240.000', '121.069.242.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.069.243.000', '121.069.243.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('121.069.244.000', '121.070.047.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.070.048.000', '121.070.063.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('121.070.064.000', '121.070.071.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.070.072.000', '121.070.079.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('121.070.080.000', '121.070.087.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.070.088.000', '121.070.111.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('121.070.112.000', '121.070.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.070.128.000', '121.070.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('121.071.000.000', '121.071.111.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.071.112.000', '121.071.112.099', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.071.112.100', '121.071.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.071.128.000', '121.071.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.072.000.000', '121.075.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.076.000.000', '121.077.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('121.078.000.000', '121.088.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.089.000.000', '121.089.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.090.000.000', '121.100.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.100.128.000', '121.100.159.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('121.100.160.000', '121.100.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.101.000.000', '121.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.192.000.000', '121.192.001.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('121.192.002.000', '121.192.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.192.128.000', '121.192.132.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('121.192.133.000', '121.192.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.193.000.000', '121.193.079.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('121.193.080.000', '121.193.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.193.128.000', '121.193.159.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('121.193.160.000', '121.193.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.193.192.000', '121.193.215.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('121.193.216.000', '121.193.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.194.000.000', '121.194.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.194.192.000', '121.194.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.195.000.000', '121.195.013.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.195.014.000', '121.195.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.195.032.000', '121.195.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('121.195.064.000', '121.195.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.196.000.000', '121.200.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.201.000.000', '121.201.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.201.096.000', '121.201.111.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.201.112.000', '121.201.115.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('121.201.116.000', '121.201.119.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.201.120.000', '121.201.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('121.201.128.000', '121.201.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.202.000.000', '121.203.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('121.204.000.000', '121.207.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('121.208.000.000', '121.223.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.224.000.000', '121.239.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('121.240.000.000', '121.247.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.248.000.000', '121.248.039.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('121.248.040.000', '121.249.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.250.000.000', '121.250.050.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('121.250.051.000', '121.250.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.250.064.000', '121.250.079.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('121.250.080.000', '121.251.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('121.252.000.000', '121.254.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.254.064.000', '121.254.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('121.254.128.000', '121.254.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('121.255.000.000', '121.255.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('122.000.000.000', '122.000.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.000.064.000', '122.000.127.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('122.000.128.000', '122.000.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('122.001.000.000', '122.003.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.004.000.000', '122.007.051.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('122.007.052.000', '122.007.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('122.007.064.000', '122.007.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('122.008.000.000', '122.008.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('122.008.032.000', '122.008.048.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.008.049.000', '122.008.052.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('122.008.053.000', '122.008.069.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.008.070.000', '122.008.071.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('122.008.072.000', '122.008.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.008.096.000', '122.008.144.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('122.008.145.000', '122.008.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.008.192.000', '122.008.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('122.009.000.000', '122.009.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.010.000.000', '122.010.127.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('122.010.128.000', '122.015.015.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.015.016.000', '122.015.031.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('122.015.032.000', '122.015.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.016.000.000', '122.047.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.048.000.000', '122.048.000.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('122.048.001.000', '122.048.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.048.144.000', '122.048.199.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('122.048.200.000', '122.048.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.049.000.000', '122.049.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('122.049.064.000', '122.049.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.049.192.000', '122.049.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.050.000.000', '122.050.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.051.000.000', '122.051.065.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.051.066.000', '122.051.111.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('122.051.112.000', '122.051.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.052.000.000', '122.057.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.058.000.000', '122.063.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.064.000.000', '122.066.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.066.064.000', '122.066.079.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('122.066.080.000', '122.066.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.066.192.000', '122.066.199.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('122.066.200.000', '122.088.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.088.064.000', '122.088.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('122.088.128.000', '122.088.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.088.160.000', '122.088.175.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('122.088.176.000', '122.089.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.089.064.000', '122.089.159.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('122.089.160.000', '122.095.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.096.000.000', '122.097.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('122.098.000.000', '122.098.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.099.000.000', '122.099.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.099.064.000', '122.099.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.099.096.000', '122.099.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.099.128.000', '122.099.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.100.000.000', '122.100.015.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.100.016.000', '122.100.023.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.100.024.000', '122.100.031.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.100.032.000', '122.100.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.100.064.000', '122.100.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.100.128.000', '122.100.255.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('122.101.000.000', '122.101.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.102.000.000', '122.102.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('122.102.016.000', '122.102.023.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.102.024.000', '122.102.031.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.102.032.000', '122.102.039.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('122.102.040.000', '122.102.047.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.102.048.000', '122.102.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.102.064.000', '122.102.079.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('122.102.080.000', '122.102.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.102.096.000', '122.102.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.102.112.000', '122.102.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.102.128.000', '122.106.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.107.000.000', '122.115.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.116.000.000', '122.118.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.119.000.000', '122.119.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.120.000.000', '122.127.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.128.000.000', '122.128.023.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.128.024.000', '122.128.031.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.128.032.000', '122.128.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.128.080.000', '122.128.087.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.128.088.000', '122.128.103.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.128.104.000', '122.128.111.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('122.128.112.000', '122.129.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.129.064.000', '122.129.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.129.128.000', '122.129.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.129.160.000', '122.129.191.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.129.192.000', '122.129.199.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.129.200.000', '122.129.207.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.129.208.000', '122.129.215.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.129.216.000', '122.129.223.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.129.224.000', '122.135.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.136.000.000', '122.143.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('122.144.000.000', '122.144.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.144.032.000', '122.144.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.144.064.000', '122.144.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.144.128.000', '122.144.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('122.145.000.000', '122.145.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.146.000.000', '122.147.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.148.000.000', '122.149.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.150.000.000', '122.151.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.152.000.000', '122.152.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.152.096.000', '122.152.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.152.128.000', '122.152.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.152.192.000', '122.152.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.153.000.000', '122.155.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.156.000.000', '122.159.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('122.160.000.000', '122.175.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.176.000.000', '122.191.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.192.000.000', '122.195.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('122.196.000.000', '122.197.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.198.000.000', '122.198.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('122.198.032.000', '122.198.063.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('122.198.064.000', '122.198.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.199.000.000', '122.200.055.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.200.056.000', '122.200.063.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.200.064.000', '122.200.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('122.200.128.000', '122.200.151.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.200.152.000', '122.200.159.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.200.160.000', '122.200.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.200.176.000', '122.200.191.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.200.192.000', '122.201.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.201.032.000', '122.201.047.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('122.201.048.000', '122.201.063.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.201.064.000', '122.201.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.201.096.000', '122.201.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.201.128.000', '122.201.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.202.000.000', '122.202.015.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.202.016.000', '122.202.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.202.080.000', '122.202.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.202.128.000', '122.203.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.204.000.000', '122.204.039.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('122.204.040.000', '122.204.047.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.204.048.000', '122.204.095.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('122.204.096.000', '122.205.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.206.000.000', '122.206.083.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('122.206.084.000', '122.206.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.207.000.000', '122.207.001.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('122.207.002.000', '122.207.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('122.208.000.000', '122.223.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.224.000.000', '122.247.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('122.248.000.000', '122.248.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.248.016.000', '122.248.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('122.248.032.000', '122.248.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.248.048.000', '122.248.063.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('122.248.064.000', '122.248.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.248.096.000', '122.248.127.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.248.128.000', '122.248.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.248.192.000', '122.248.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.249.000.000', '122.252.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.252.008.000', '122.252.015.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.252.016.000', '122.252.023.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.252.024.000', '122.252.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.252.032.000', '122.252.151.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.252.152.000', '122.252.159.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('122.252.160.000', '122.252.175.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.252.176.000', '122.253.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.254.000.000', '122.254.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.254.064.000', '122.255.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.255.080.000', '122.255.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('122.255.096.000', '122.255.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('122.255.192.000', '122.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('123.000.000.000', '123.000.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.000.032.000', '123.000.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.000.064.000', '123.000.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.000.128.000', '123.000.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('123.000.192.000', '123.000.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.001.000.000', '123.003.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.004.000.000', '123.015.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('123.016.000.000', '123.049.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.049.128.000', '123.049.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.049.160.000', '123.049.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('123.050.000.000', '123.050.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.050.032.000', '123.050.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.050.064.000', '123.051.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.052.000.000', '123.055.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('123.056.000.000', '123.057.064.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.057.065.000', '123.057.085.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('123.057.086.000', '123.057.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.057.096.000', '123.057.100.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('123.057.101.000', '123.095.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.096.000.000', '123.097.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('123.098.000.000', '123.098.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('123.098.128.000', '123.098.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.099.000.000', '123.099.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.099.064.000', '123.099.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.099.128.000', '123.100.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.100.032.000', '123.100.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.101.000.000', '123.101.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('123.102.000.000', '123.102.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.103.000.000', '123.103.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.103.048.000', '123.103.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.103.128.000', '123.108.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.108.128.000', '123.108.143.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.108.144.000', '123.108.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.108.208.000', '123.108.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.108.224.000', '123.109.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.110.000.000', '123.110.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.111.000.000', '123.111.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.112.000.000', '123.127.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.128.000.000', '123.135.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('123.136.000.000', '123.136.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('123.136.016.000', '123.136.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.136.080.000', '123.136.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('123.136.096.000', '123.136.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.137.000.000', '123.137.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('123.138.000.000', '123.139.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('123.140.000.000', '123.143.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.144.000.000', '123.147.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('123.148.000.000', '123.148.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('123.149.000.000', '123.149.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('123.150.000.000', '123.151.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('123.152.000.000', '123.159.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('123.160.000.000', '123.163.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('123.164.000.000', '123.167.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('123.168.000.000', '123.171.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('123.172.000.000', '123.173.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('123.174.000.000', '123.175.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('123.176.000.000', '123.176.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.177.000.000', '123.177.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('123.178.000.000', '123.179.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('123.180.000.000', '123.183.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('123.184.000.000', '123.191.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('123.192.000.000', '123.195.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.196.000.000', '123.196.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.196.192.000', '123.196.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('123.197.000.000', '123.197.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.198.000.000', '123.199.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.199.128.000', '123.199.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('123.200.000.000', '123.201.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.202.000.000', '123.203.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('123.204.000.000', '123.205.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.206.000.000', '123.207.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.208.000.000', '123.231.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.232.000.000', '123.235.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('123.236.000.000', '123.239.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.240.000.000', '123.241.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.242.000.000', '123.242.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.242.128.000', '123.242.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.242.224.000', '123.242.231.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('123.242.232.000', '123.243.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.244.000.000', '123.247.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('123.248.000.000', '123.248.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.249.000.000', '123.249.064.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('123.249.065.000', '123.249.183.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.249.184.000', '123.249.192.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('123.249.193.000', '123.249.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.250.000.000', '123.251.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.252.000.000', '123.252.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('123.252.128.000', '123.252.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('123.253.000.000', '123.253.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('123.253.128.000', '123.253.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('123.254.000.000', '124.005.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.006.000.000', '124.006.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.006.032.000', '124.006.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.006.064.000', '124.006.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('124.006.128.000', '124.007.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.008.000.000', '124.012.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.013.000.000', '124.013.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.014.000.000', '124.015.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.016.000.000', '124.017.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.018.000.000', '124.019.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.020.000.000', '124.020.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.020.064.000', '124.020.095.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('124.020.096.000', '124.020.111.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.020.112.000', '124.020.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.021.000.000', '124.021.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.021.032.000', '124.021.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.021.192.000', '124.021.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('124.022.000.000', '124.023.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('124.024.000.000', '124.028.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.029.000.000', '124.029.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.029.128.000', '124.029.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.029.192.000', '124.040.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.040.128.000', '124.040.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.040.192.000', '124.041.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.042.000.000', '124.042.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.042.128.000', '124.046.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.047.000.000', '124.047.063.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('124.047.064.000', '124.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.064.000.000', '124.065.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.066.000.000', '124.066.127.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('124.066.128.000', '124.066.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.067.000.000', '124.067.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('124.068.000.000', '124.071.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.072.000.000', '124.073.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('124.074.000.000', '124.079.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('124.080.000.000', '124.087.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.088.044.000', '124.088.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('124.089.000.000', '124.089.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('124.089.128.000', '124.091.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('124.092.000.000', '124.095.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('124.096.000.000', '124.108.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.108.008.000', '124.108.015.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('124.108.016.000', '124.108.039.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.108.040.000', '124.108.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.108.048.000', '124.108.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.108.128.000', '124.108.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.108.192.000', '124.111.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.112.000.000', '124.113.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('124.114.000.000', '124.116.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('124.117.000.000', '124.119.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('124.120.000.000', '124.125.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.126.000.000', '124.127.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.128.000.000', '124.135.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('124.136.000.000', '124.147.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.147.128.000', '124.147.175.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('124.147.176.000', '124.147.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.147.192.000', '124.147.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('124.148.000.000', '124.155.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.155.128.000', '124.155.159.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.155.160.000', '124.155.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.156.000.000', '124.156.007.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('124.156.008.000', '124.156.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.156.096.000', '124.156.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('124.157.000.000', '124.158.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.158.192.000', '124.158.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('124.158.224.000', '124.159.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.160.000.000', '124.160.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('124.161.000.000', '124.161.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('124.162.000.000', '124.162.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('124.163.000.000', '124.167.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('124.168.000.000', '124.171.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.172.000.000', '124.172.015.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('124.172.016.000', '124.172.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.172.032.000', '124.172.063.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('124.172.064.000', '124.172.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('124.172.128.000', '124.172.189.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.172.190.000', '124.172.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('124.172.192.000', '124.172.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('124.173.000.000', '124.173.035.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('124.173.036.000', '124.173.037.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.173.038.000', '124.173.039.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('124.173.040.000', '124.173.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.173.064.000', '124.173.127.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('124.173.128.000', '124.173.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.173.176.000', '124.173.179.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('124.173.180.000', '124.173.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.173.224.000', '124.173.239.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('124.173.240.000', '124.173.247.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.173.248.000', '124.173.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.174.000.000', '124.175.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.176.000.000', '124.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.192.000.000', '124.193.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.194.000.000', '124.195.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.196.000.000', '124.196.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('124.197.000.000', '124.199.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.199.064.000', '124.199.111.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.199.112.000', '124.199.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.200.000.000', '124.200.075.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('124.200.076.000', '124.200.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.200.128.000', '124.200.135.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('124.200.136.000', '124.200.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.201.000.000', '124.201.151.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('124.201.152.000', '124.207.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.208.000.000', '124.217.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.218.000.000', '124.219.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('124.219.128.000', '124.219.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.220.000.000', '124.223.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('124.224.000.000', '124.224.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('124.225.000.000', '124.225.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('124.226.000.000', '124.227.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('124.228.000.000', '124.233.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('124.234.000.000', '124.235.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('124.236.000.000', '124.239.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('124.240.000.000', '124.240.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.240.128.000', '124.241.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.242.000.000', '124.242.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.243.000.000', '124.243.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.243.192.000', '124.243.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('124.244.000.000', '124.244.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('124.245.000.000', '124.247.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.248.000.000', '124.248.031.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('124.248.032.000', '124.248.063.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('124.248.064.000', '124.248.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('124.248.128.000', '124.248.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.249.000.000', '124.249.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.249.128.000', '124.249.191.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('124.249.192.000', '124.249.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.249.224.000', '124.249.239.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('124.249.240.000', '124.249.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('124.250.000.000', '124.251.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.252.000.000', '124.253.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('124.254.000.000', '124.254.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('124.254.064.000', '125.030.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.031.000.000', '125.031.063.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('125.031.064.000', '125.031.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.031.192.000', '125.031.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.032.000.000', '125.032.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('125.033.000.000', '125.035.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('125.035.128.000', '125.039.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('125.040.000.000', '125.047.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('125.048.000.000', '125.058.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.058.128.000', '125.058.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('125.059.000.000', '125.059.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('125.060.000.000', '125.061.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.062.000.000', '125.062.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('125.062.064.000', '125.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.064.000.000', '125.071.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('125.072.000.000', '125.072.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('125.073.000.000', '125.073.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('125.074.000.000', '125.076.127.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('125.076.128.000', '125.076.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('125.077.000.000', '125.079.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('125.080.000.000', '125.087.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('125.088.000.000', '125.095.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.096.000.000', '125.098.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('125.099.000.000', '125.103.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.104.000.000', '125.127.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('125.128.000.000', '125.168.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.169.000.000', '125.169.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.170.000.000', '125.170.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.171.000.000', '125.171.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.171.064.000', '125.171.111.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.171.112.000', '125.171.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.171.128.000', '125.171.195.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.171.196.000', '125.171.239.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.171.240.000', '125.171.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.172.000.000', '125.207.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.208.000.000', '125.208.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('125.208.064.000', '125.209.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.210.000.000', '125.210.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('125.211.000.000', '125.211.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('125.212.000.000', '125.212.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.213.000.000', '125.213.087.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('125.213.088.000', '125.213.103.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('125.213.104.000', '125.213.111.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('125.213.112.000', '125.213.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('125.213.128.000', '125.214.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.214.192.000', '125.214.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('125.215.000.000', '125.215.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('125.215.064.000', '125.215.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.215.128.000', '125.215.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('125.216.000.000', '125.216.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.217.000.000', '125.217.001.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('125.217.002.000', '125.217.003.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.217.004.000', '125.217.007.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('125.217.008.000', '125.218.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('125.219.000.000', '125.219.161.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('125.219.162.000', '125.219.163.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.219.164.000', '125.219.165.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('125.219.166.000', '125.219.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.220.000.000', '125.221.027.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('125.221.028.000', '125.221.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.221.032.000', '125.221.039.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('125.221.040.000', '125.221.047.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.221.048.000', '125.221.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('125.221.128.000', '125.221.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.221.160.000', '125.221.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('125.222.000.000', '125.222.111.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('125.222.112.000', '125.222.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.222.128.000', '125.222.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('125.222.192.000', '125.222.247.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.222.248.000', '125.222.251.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('125.222.252.000', '125.222.253.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.222.254.000', '125.222.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('125.223.000.000', '125.223.097.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('125.223.098.000', '125.223.111.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('125.223.112.000', '125.223.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('125.223.128.000', '125.223.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('125.224.000.000', '125.233.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('125.234.000.000', '125.253.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.253.128.000', '125.253.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('125.254.000.000', '125.254.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('125.254.128.000', '125.254.175.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('125.254.176.000', '125.254.191.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('125.254.192.000', '125.254.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('125.255.000.000', '126.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('127.000.000.000', '127.255.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('128.000.000.000', '137.188.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('137.189.000.000', '137.189.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('137.190.000.000', '139.174.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('139.175.000.000', '139.175.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('139.176.000.000', '139.222.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('139.223.000.000', '139.223.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('139.224.000.000', '140.091.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('140.092.000.000', '140.092.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('140.093.000.000', '140.095.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('140.096.000.000', '140.096.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('140.097.000.000', '140.108.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('140.109.000.000', '140.138.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('140.139.000.000', '152.103.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('152.104.000.000', '152.104.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('152.104.224.000', '152.104.227.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('152.104.228.000', '152.104.235.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('152.104.236.000', '152.104.239.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('152.104.240.000', '152.104.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('152.105.000.000', '158.131.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('158.132.000.000', '158.132.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('158.133.000.000', '158.181.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('158.182.000.000', '158.182.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('158.183.000.000', '158.215.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('158.216.000.000', '158.216.255.255', 'IANA');
INSERT INTO eq_ipdatabase VALUES ('158.217.000.000', '159.225.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('159.226.000.000', '159.226.070.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.071.000', '159.226.075.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('159.226.076.000', '159.226.118.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.119.000', '159.226.121.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('159.226.122.000', '159.226.123.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('159.226.124.000', '159.226.128.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('159.226.129.000', '159.226.130.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.131.000', '159.226.131.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('159.226.132.000', '159.226.135.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('159.226.136.000', '159.226.138.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('159.226.139.000', '159.226.139.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('159.226.140.000', '159.226.143.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('159.226.144.000', '159.226.147.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('159.226.148.000', '159.226.149.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('159.226.150.000', '159.226.150.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('159.226.151.000', '159.226.151.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('159.226.152.000', '159.226.152.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('159.226.153.000', '159.226.154.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('159.226.155.000', '159.226.155.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('159.226.156.000', '159.226.156.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('159.226.157.000', '159.226.157.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('159.226.158.000', '159.226.158.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('159.226.159.000', '159.226.159.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('159.226.160.000', '159.226.160.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('159.226.161.000', '159.226.176.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.177.000', '159.226.177.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('159.226.178.000', '159.226.235.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.236.000', '159.226.236.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('159.226.237.000', '159.226.237.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.226.238.000', '159.226.238.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('159.226.239.000', '159.226.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('159.227.000.000', '161.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('161.064.000.000', '161.064.255.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('161.065.000.000', '161.206.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('161.207.000.000', '161.207.016.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('161.207.017.000', '161.207.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('161.208.000.000', '162.104.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('162.105.000.000', '162.105.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('162.106.000.000', '163.012.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('163.013.000.000', '163.032.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('163.033.000.000', '192.167.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('192.168.000.000', '192.168.255.255', '局域网');
INSERT INTO eq_ipdatabase VALUES ('192.169.000.000', '202.000.076.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.077.000', '202.000.078.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.079.000', '202.000.099.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.100.000', '202.000.100.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.101.000', '202.000.103.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.104.000', '202.000.104.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.105.000', '202.000.109.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.110.000', '202.000.110.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.000.111.000', '202.000.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.112.000', '202.000.112.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.113.000', '202.000.121.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.122.000', '202.000.123.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.124.000', '202.000.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.128.000', '202.000.147.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.148.000', '202.000.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.000.160.000', '202.000.183.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.000.184.000', '202.001.005.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.001.006.000', '202.001.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.001.008.000', '202.001.231.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.001.232.000', '202.001.239.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.001.240.000', '202.002.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.002.032.000', '202.002.051.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.002.052.000', '202.002.055.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.002.056.000', '202.002.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.002.064.000', '202.002.087.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.002.088.000', '202.003.005.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.003.006.000', '202.003.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.003.008.000', '202.003.012.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.003.013.000', '202.003.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.003.016.000', '202.003.076.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.003.077.000', '202.003.077.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.003.078.000', '202.003.128.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.003.129.000', '202.003.129.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.003.130.000', '202.003.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.003.160.000', '202.003.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.003.192.000', '202.004.025.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.004.026.000', '202.004.027.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.004.028.000', '202.004.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.004.128.000', '202.004.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.004.160.000', '202.004.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.004.192.000', '202.004.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.004.224.000', '202.004.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.004.252.000', '202.004.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.005.000.000', '202.005.003.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.005.004.000', '202.005.005.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.005.006.000', '202.005.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.005.008.000', '202.005.015.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.005.016.000', '202.005.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.005.032.000', '202.005.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.005.224.000', '202.005.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.006.000.000', '202.006.093.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.006.094.000', '202.006.094.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.006.095.000', '202.006.103.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.006.104.000', '202.006.105.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.006.106.000', '202.007.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.007.128.000', '202.007.143.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.007.144.000', '202.008.013.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.008.014.000', '202.008.015.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.008.016.000', '202.008.075.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.008.076.000', '202.008.076.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.008.077.000', '202.008.087.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.008.088.000', '202.008.091.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.008.092.000', '202.008.092.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.008.093.000', '202.008.093.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.008.094.000', '202.008.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.008.128.000', '202.008.159.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('202.008.160.000', '202.008.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.008.192.000', '202.012.018.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.012.019.000', '202.012.019.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.012.020.000', '202.014.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.008.000', '202.014.015.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.014.016.000', '202.014.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.080.000', '202.014.080.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.014.081.000', '202.014.087.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.088.000', '202.014.088.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.014.089.000', '202.014.115.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.116.000', '202.014.116.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.014.117.000', '202.014.221.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.222.000', '202.014.222.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.014.223.000', '202.014.234.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.014.235.000', '202.014.238.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.014.239.000', '202.020.065.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.066.000', '202.020.066.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.067.000', '202.020.087.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.088.000', '202.020.089.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.090.000', '202.020.093.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.094.000', '202.020.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.096.000', '202.020.099.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.100.000', '202.020.101.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.102.000', '202.020.116.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.117.000', '202.020.118.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.119.000', '202.020.119.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.120.000', '202.020.120.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.020.121.000', '202.020.124.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.020.125.000', '202.020.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.020.128.000', '202.022.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.022.240.000', '202.022.247.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.022.248.000', '202.022.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.023.000.000', '202.037.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.000.000', '202.038.001.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.038.002.000', '202.038.003.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.038.004.000', '202.038.007.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.038.008.000', '202.038.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.016.000', '202.038.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.038.032.000', '202.038.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.064.000', '202.038.095.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.038.096.000', '202.038.129.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.130.000', '202.038.131.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('202.038.132.000', '202.038.135.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.038.136.000', '202.038.138.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.139.000', '202.038.139.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.140.000', '202.038.141.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.038.142.000', '202.038.142.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.143.000', '202.038.143.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.038.144.000', '202.038.145.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.038.146.000', '202.038.147.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.148.000', '202.038.148.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.149.000', '202.038.149.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.038.150.000', '202.038.150.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.151.000', '202.038.151.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.152.000', '202.038.153.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.154.000', '202.038.155.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.038.156.000', '202.038.156.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.038.157.000', '202.038.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.160.000', '202.038.161.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.162.000', '202.038.163.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.164.000', '202.038.167.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('202.038.168.000', '202.038.168.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.169.000', '202.038.169.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.038.170.000', '202.038.170.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.171.000', '202.038.172.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.038.173.000', '202.038.173.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.038.174.000', '202.038.177.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.178.000', '202.038.179.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.038.180.000', '202.038.183.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.038.184.000', '202.038.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.038.192.000', '202.038.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.039.000.000', '202.039.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.040.000.000', '202.040.171.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.040.172.000', '202.040.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.040.192.000', '202.040.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.040.224.000', '202.041.141.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.041.142.000', '202.041.142.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.041.143.000', '202.041.143.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.041.144.000', '202.041.145.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.041.146.000', '202.041.146.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.041.147.000', '202.041.151.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.041.152.000', '202.041.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.041.160.000', '202.041.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.041.240.000', '202.041.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.042.000.000', '202.043.143.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.043.144.000', '202.043.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.043.160.000', '202.043.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.043.192.000', '202.043.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.043.224.000', '202.044.119.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.044.120.000', '202.044.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.044.128.000', '202.044.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.000.000', '202.045.002.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.003.000', '202.045.003.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.004.000', '202.045.005.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.006.000', '202.045.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.008.000', '202.045.009.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.010.000', '202.045.013.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.014.000', '202.045.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.096.000', '202.045.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.128.000', '202.045.128.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.129.000', '202.045.129.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.130.000', '202.045.131.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.045.132.000', '202.045.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.045.176.000', '202.045.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.046.000.000', '202.046.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.046.032.000', '202.046.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.046.064.000', '202.046.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.046.224.000', '202.046.239.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.046.240.000', '202.047.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.047.080.000', '202.047.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.047.096.000', '202.052.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.052.128.000', '202.052.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.052.160.000', '202.052.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.052.192.000', '202.052.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.052.224.000', '202.053.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.053.128.000', '202.053.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.053.160.000', '202.055.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.055.032.000', '202.055.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.055.064.000', '202.055.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.055.224.000', '202.055.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.056.000.000', '202.056.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.056.008.000', '202.056.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.056.016.000', '202.057.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.057.192.000', '202.057.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.057.224.000', '202.057.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.057.240.000', '202.057.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.058.000.000', '202.058.183.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.058.184.000', '202.058.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.058.192.000', '202.059.151.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.059.152.000', '202.059.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.059.160.000', '202.060.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.060.112.000', '202.060.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.060.128.000', '202.060.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.060.224.000', '202.060.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.061.000.000', '202.061.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.061.096.000', '202.061.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.061.128.000', '202.062.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.062.192.000', '202.062.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.062.224.000', '202.062.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.063.000.000', '202.063.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.063.032.000', '202.063.247.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.063.248.000', '202.063.251.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('202.063.252.000', '202.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.064.000.000', '202.064.009.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.064.010.000', '202.064.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.064.032.000', '202.065.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.065.032.000', '202.065.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.065.096.000', '202.065.111.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.065.112.000', '202.065.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.065.192.000', '202.065.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.065.224.000', '202.065.247.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.065.248.000', '202.067.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.067.004.000', '202.067.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.067.128.000', '202.068.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.068.064.000', '202.068.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.068.096.000', '202.068.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.068.128.000', '202.068.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.068.192.000', '202.068.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.068.208.000', '202.068.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.068.224.000', '202.069.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.069.004.000', '202.069.007.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.069.008.000', '202.069.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.069.016.000', '202.069.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.069.032.000', '202.069.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.069.064.000', '202.069.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.069.096.000', '202.069.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.069.128.000', '202.069.131.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.069.132.000', '202.069.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.069.240.000', '202.069.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.070.000.000', '202.070.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.070.032.000', '202.070.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.070.160.000', '202.070.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.070.176.000', '202.070.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.071.000.000', '202.071.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.071.032.000', '202.071.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.071.160.000', '202.071.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.071.176.000', '202.071.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.071.192.000', '202.072.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.072.032.000', '202.072.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.072.080.000', '202.072.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.072.096.000', '202.072.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.072.252.000', '202.073.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.073.004.000', '202.073.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.073.128.000', '202.073.131.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.073.132.000', '202.073.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.073.240.000', '202.074.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.074.004.000', '202.074.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.074.008.000', '202.074.015.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.074.016.000', '202.074.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.074.080.000', '202.074.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.074.128.000', '202.074.253.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.074.254.000', '202.075.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.075.004.000', '202.075.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.075.064.000', '202.075.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.075.096.000', '202.075.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.075.208.000', '202.075.223.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.075.224.000', '202.075.247.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.075.248.000', '202.075.251.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.075.252.000', '202.076.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.076.128.000', '202.076.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.076.240.000', '202.076.240.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.076.241.000', '202.076.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.076.252.000', '202.077.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.077.064.000', '202.077.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.077.128.000', '202.077.135.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.077.136.000', '202.077.139.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.077.140.000', '202.078.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.078.008.000', '202.078.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.078.252.000', '202.078.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.079.000.000', '202.079.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.079.252.000', '202.080.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.080.032.000', '202.080.103.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.080.104.000', '202.080.111.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.080.112.000', '202.080.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.080.128.000', '202.080.143.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.080.144.000', '202.080.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.080.192.000', '202.080.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.080.208.000', '202.080.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.081.000.000', '202.081.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.081.004.000', '202.081.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.081.224.000', '202.082.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.083.000.000', '202.083.003.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.083.004.000', '202.083.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.083.016.000', '202.083.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.083.192.000', '202.083.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.083.224.000', '202.083.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.083.240.000', '202.083.247.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.083.248.000', '202.083.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.083.252.000', '202.084.017.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.084.018.000', '202.084.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.085.000.000', '202.085.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.085.208.000', '202.085.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.085.224.000', '202.085.239.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.085.240.000', '202.085.247.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.085.248.000', '202.086.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.086.096.000', '202.086.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.086.128.000', '202.086.191.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.086.192.000', '202.086.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.087.000.000', '202.087.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.087.004.000', '202.087.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.088.000.000', '202.088.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.088.032.000', '202.088.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.088.096.000', '202.088.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.088.128.000', '202.088.199.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.088.200.000', '202.088.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.088.208.000', '202.088.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.089.000.000', '202.089.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.089.004.000', '202.089.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.089.016.000', '202.089.023.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.089.024.000', '202.089.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.089.252.000', '202.089.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.090.000.000', '202.090.003.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.090.004.000', '202.090.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.090.224.000', '202.090.239.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.090.240.000', '202.090.247.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.090.248.000', '202.090.251.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.090.252.000', '202.090.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.091.000.000', '202.091.003.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.091.004.000', '202.091.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.091.128.000', '202.091.131.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.091.132.000', '202.091.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.091.176.000', '202.091.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.091.192.000', '202.091.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.091.224.000', '202.091.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.092.000.000', '202.092.003.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.092.004.000', '202.092.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.092.160.000', '202.092.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.092.192.000', '202.092.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.092.252.000', '202.092.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.093.000.000', '202.093.003.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.093.004.000', '202.093.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.093.192.000', '202.093.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.093.208.000', '202.093.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.093.252.000', '202.094.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.094.032.000', '202.094.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.094.224.000', '202.094.239.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.094.240.000', '202.094.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.095.000.000', '202.095.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.095.032.000', '202.095.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.095.252.000', '202.095.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.096.000.000', '202.096.095.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.096.096.000', '202.096.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.096.128.000', '202.096.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.096.192.000', '202.096.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.097.000.000', '202.097.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.097.016.000', '202.097.016.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('202.097.017.000', '202.097.017.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('202.097.018.000', '202.097.018.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.097.019.000', '202.097.019.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('202.097.020.000', '202.097.020.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.097.021.000', '202.097.021.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('202.097.022.000', '202.097.022.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('202.097.023.000', '202.097.023.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.097.024.000', '202.097.024.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('202.097.025.000', '202.097.026.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.097.027.000', '202.097.027.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.097.028.000', '202.097.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.097.032.000', '202.097.033.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.097.034.000', '202.097.055.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.097.056.000', '202.097.056.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('202.097.057.000', '202.097.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.097.064.000', '202.097.064.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.097.065.000', '202.097.065.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('202.097.066.000', '202.097.066.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('202.097.067.000', '202.097.067.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.097.068.000', '202.097.068.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.097.069.000', '202.097.069.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('202.097.070.000', '202.097.070.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('202.097.071.000', '202.097.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.097.096.000', '202.097.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.097.128.000', '202.097.159.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('202.097.160.000', '202.097.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.097.192.000', '202.097.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('202.098.000.000', '202.098.031.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('202.098.032.000', '202.098.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('202.098.064.000', '202.098.095.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('202.098.096.000', '202.098.159.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('202.098.160.000', '202.098.191.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('202.098.192.000', '202.098.223.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('202.098.224.000', '202.098.255.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('202.099.000.000', '202.099.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.099.064.000', '202.099.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.099.128.000', '202.099.191.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('202.099.192.000', '202.099.223.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('202.099.224.000', '202.099.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('202.100.000.000', '202.100.063.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('202.100.064.000', '202.100.095.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('202.100.096.000', '202.100.127.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('202.100.128.000', '202.100.159.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('202.100.160.000', '202.100.191.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('202.100.192.000', '202.100.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('202.101.000.000', '202.101.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.101.064.000', '202.101.095.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('202.101.096.000', '202.101.159.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('202.101.160.000', '202.101.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.101.192.000', '202.101.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('202.102.000.000', '202.102.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.102.128.000', '202.102.191.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.102.192.000', '202.102.223.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.102.224.000', '202.102.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('202.103.000.000', '202.103.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.103.128.000', '202.103.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.103.192.000', '202.103.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('202.104.000.000', '202.105.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.106.000.000', '202.106.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.107.000.000', '202.107.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.107.128.000', '202.107.191.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('202.107.192.000', '202.107.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('202.108.000.000', '202.108.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.109.000.000', '202.109.125.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.109.126.000', '202.109.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.109.128.000', '202.109.191.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('202.109.192.000', '202.109.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('202.110.000.000', '202.110.063.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.110.064.000', '202.110.127.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('202.110.128.000', '202.110.191.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.110.192.000', '202.110.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.111.000.000', '202.111.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.111.128.000', '202.111.159.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('202.111.160.000', '202.111.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('202.111.192.000', '202.111.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.112.000.000', '202.112.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('202.112.032.000', '202.112.039.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.112.040.000', '202.112.047.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.112.048.000', '202.112.055.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.112.056.000', '202.112.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.113.000.000', '202.113.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.114.000.000', '202.114.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.115.000.000', '202.115.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('202.116.000.000', '202.116.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.117.000.000', '202.117.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('202.118.000.000', '202.118.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.118.128.000', '202.118.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('202.119.000.000', '202.119.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.120.000.000', '202.122.007.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.122.008.000', '202.122.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.122.032.000', '202.122.039.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.122.040.000', '202.122.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.122.064.000', '202.122.095.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.122.096.000', '202.122.111.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.122.112.000', '202.122.119.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.122.120.000', '202.123.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.123.064.000', '202.123.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.123.096.000', '202.123.111.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.123.112.000', '202.123.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.123.160.000', '202.123.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.123.176.000', '202.123.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.123.192.000', '202.123.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.123.224.000', '202.125.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.125.176.000', '202.125.191.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('202.125.192.000', '202.125.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.126.000.000', '202.126.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.126.048.000', '202.126.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.126.064.000', '202.126.079.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.126.080.000', '202.126.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.126.208.000', '202.126.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.126.224.000', '202.126.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.127.000.000', '202.127.007.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.127.008.000', '202.127.011.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.127.012.000', '202.127.015.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.127.016.000', '202.127.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.127.032.000', '202.127.039.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.127.040.000', '202.127.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.127.064.000', '202.127.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.127.112.000', '202.127.115.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.127.116.000', '202.127.119.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('202.127.120.000', '202.127.143.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.127.144.000', '202.127.145.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.127.146.000', '202.127.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.127.160.000', '202.127.167.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.127.168.000', '202.127.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.127.192.000', '202.127.199.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.127.200.000', '202.127.207.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.127.208.000', '202.127.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.128.000.000', '202.128.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.128.128.000', '202.128.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.128.160.000', '202.128.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.128.224.000', '202.128.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.129.000.000', '202.129.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.130.000.000', '202.130.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.130.032.000', '202.130.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.130.064.000', '202.130.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.130.192.000', '202.130.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.130.224.000', '202.130.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.131.000.000', '202.131.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.131.016.000', '202.131.023.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.131.024.000', '202.131.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.131.032.000', '202.131.047.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.131.048.000', '202.131.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.131.064.000', '202.131.079.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.131.080.000', '202.131.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.131.208.000', '202.131.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.131.224.000', '202.131.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.132.000.000', '202.132.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.133.000.000', '202.133.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.133.008.000', '202.133.009.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.133.010.000', '202.133.013.015', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.133.013.016', '202.133.013.127', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.133.013.128', '202.133.014.127', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.133.014.128', '202.133.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.133.016.000', '202.133.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.133.224.000', '202.133.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.134.000.000', '202.134.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.134.064.000', '202.134.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.134.128.000', '202.136.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.136.048.000', '202.136.063.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.136.064.000', '202.136.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.136.208.000', '202.136.223.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.136.224.000', '202.136.239.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('202.136.240.000', '202.136.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.136.252.000', '202.136.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.137.000.000', '202.140.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.140.064.000', '202.140.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.140.128.000', '202.140.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.140.160.000', '202.140.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.140.192.000', '202.140.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.140.224.000', '202.140.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.141.000.000', '202.141.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.141.160.000', '202.141.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('202.141.192.000', '202.142.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.142.016.000', '202.142.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.142.032.000', '202.143.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.143.016.000', '202.143.031.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.143.032.000', '202.144.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.144.208.000', '202.144.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.144.224.000', '202.144.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.145.000.000', '202.145.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.145.032.000', '202.145.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.146.000.000', '202.146.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.146.096.000', '202.146.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.146.128.000', '202.146.215.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.146.216.000', '202.146.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.146.224.000', '202.148.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.148.096.000', '202.148.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.148.128.000', '202.148.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.148.208.000', '202.148.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.148.224.000', '202.149.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.149.160.000', '202.149.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.149.192.000', '202.149.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.149.224.000', '202.149.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.150.000.000', '202.150.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.150.016.000', '202.150.031.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('202.150.032.000', '202.151.032.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.151.033.000', '202.151.033.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.151.034.000', '202.151.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.151.048.000', '202.151.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.151.064.000', '202.152.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.152.176.000', '202.152.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.152.192.000', '202.153.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.153.008.000', '202.153.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.153.016.000', '202.153.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.153.048.000', '202.153.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('202.153.064.000', '202.153.087.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.153.088.000', '202.153.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.153.128.000', '202.153.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.153.160.000', '202.153.207.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.153.208.000', '202.154.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.154.192.000', '202.154.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.154.224.000', '202.155.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.155.192.000', '202.155.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.156.000.000', '202.157.179.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.157.180.000', '202.157.181.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.157.182.000', '202.158.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.158.160.000', '202.158.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.158.192.000', '202.159.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.159.128.000', '202.159.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.159.192.000', '202.160.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.160.064.000', '202.160.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.160.096.000', '202.160.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.160.176.000', '202.160.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.160.192.000', '202.161.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.161.160.000', '202.161.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.161.176.000', '202.162.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.163.000.000', '202.163.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.163.032.000', '202.163.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.164.000.000', '202.164.015.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.164.016.000', '202.165.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.165.096.000', '202.165.111.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.165.112.000', '202.165.119.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.165.120.000', '202.165.159.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.165.160.000', '202.165.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.165.176.000', '202.165.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.165.192.000', '202.165.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.165.208.000', '202.165.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.165.224.000', '202.166.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.166.224.000', '202.166.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.167.000.000', '202.168.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.168.160.000', '202.168.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.168.192.000', '202.168.207.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.168.208.000', '202.168.215.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.168.216.000', '202.168.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.169.000.000', '202.169.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.169.016.000', '202.169.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.169.160.000', '202.169.175.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.169.176.000', '202.169.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.170.000.000', '202.170.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.170.032.000', '202.170.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.170.128.000', '202.170.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.170.160.000', '202.170.215.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.170.216.000', '202.170.223.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('202.170.224.000', '202.171.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.171.208.000', '202.171.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.171.224.000', '202.171.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.171.252.000', '202.172.003.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.172.004.000', '202.172.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.173.000.000', '202.173.003.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.173.004.000', '202.173.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.173.008.000', '202.173.015.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.173.016.000', '202.173.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.173.032.000', '202.173.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.173.064.000', '202.173.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.173.224.000', '202.173.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.174.000.000', '202.174.003.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.174.004.000', '202.174.004.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.174.005.000', '202.174.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.174.016.000', '202.174.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.174.032.000', '202.174.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.174.128.000', '202.174.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.174.160.000', '202.174.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.175.000.000', '202.175.127.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.175.128.000', '202.175.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.175.160.000', '202.175.191.255', '澳门');
INSERT INTO eq_ipdatabase VALUES ('202.175.192.000', '202.176.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.176.224.000', '202.177.031.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.177.032.000', '202.178.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.178.128.000', '202.178.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('202.179.000.000', '202.179.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.179.240.000', '202.179.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.180.000.000', '202.180.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.180.128.000', '202.180.159.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('202.180.160.000', '202.180.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.180.176.000', '202.181.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.181.032.000', '202.181.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.181.064.000', '202.181.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.181.112.000', '202.181.127.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('202.181.128.000', '202.181.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.182.000.000', '202.182.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.182.224.000', '202.182.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.183.000.000', '202.189.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.189.080.000', '202.189.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.189.096.000', '202.189.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('202.189.128.000', '202.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('202.192.000.000', '202.192.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('202.193.000.000', '202.193.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('202.194.000.000', '202.194.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('202.195.000.000', '202.195.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('202.196.000.000', '202.196.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('202.197.000.000', '202.197.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('202.198.000.000', '202.198.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('202.199.000.000', '202.199.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('202.200.000.000', '202.200.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('202.201.000.000', '202.201.111.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('202.201.112.000', '202.201.159.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('202.201.160.000', '202.201.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('202.201.240.000', '202.201.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('202.202.000.000', '202.202.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('202.203.000.000', '202.203.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('202.204.000.000', '202.205.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('202.206.000.000', '202.206.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('202.207.000.000', '202.207.119.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('202.207.120.000', '202.207.127.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('202.207.128.000', '202.207.143.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('202.207.144.000', '202.207.159.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('202.207.160.000', '202.207.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('202.208.000.000', '203.018.049.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.018.050.000', '203.018.050.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.018.051.000', '203.019.100.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.019.101.000', '203.019.101.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.019.102.000', '203.022.103.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.022.104.000', '203.022.105.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.022.106.000', '203.031.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.031.032.000', '203.031.033.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.031.034.000', '203.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.064.000.000', '203.075.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.076.000.000', '203.076.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.077.000.000', '203.077.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.077.128.000', '203.077.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.077.160.000', '203.077.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.078.000.000', '203.078.003.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.078.004.000', '203.078.004.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.078.005.000', '203.078.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.078.008.000', '203.078.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.078.032.000', '203.078.047.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.078.048.000', '203.078.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.078.064.000', '203.078.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.078.096.000', '203.078.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.078.176.000', '203.078.191.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.078.192.000', '203.078.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.079.000.000', '203.079.015.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.079.016.000', '203.079.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.079.128.000', '203.079.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.080.000.000', '203.080.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.080.004.000', '203.080.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.080.064.000', '203.080.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.080.128.000', '203.080.143.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.080.144.000', '203.080.159.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.080.160.000', '203.080.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.080.176.000', '203.080.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.081.000.000', '203.081.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.081.016.000', '203.081.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.081.032.000', '203.081.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.081.176.000', '203.081.183.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.081.184.000', '203.081.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.081.252.000', '203.082.001.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.082.002.000', '203.082.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.082.008.000', '203.082.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.082.016.000', '203.082.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.082.252.000', '203.083.003.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.083.004.000', '203.083.055.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.083.056.000', '203.083.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.083.064.000', '203.083.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.083.128.000', '203.083.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.083.252.000', '203.084.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.084.064.000', '203.084.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.084.128.000', '203.084.129.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.084.130.000', '203.084.131.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.084.132.000', '203.084.143.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.084.144.000', '203.084.151.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.084.152.000', '203.084.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.084.192.000', '203.084.207.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.084.208.000', '203.084.221.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.084.222.000', '203.084.223.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.084.224.000', '203.084.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.085.000.000', '203.085.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.086.000.000', '203.086.007.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.086.008.000', '203.086.015.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.086.016.000', '203.086.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.086.032.000', '203.086.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.086.096.000', '203.086.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.086.128.000', '203.086.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.086.192.000', '203.086.231.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.086.232.000', '203.086.239.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.086.240.000', '203.086.251.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.086.252.000', '203.086.253.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.086.254.000', '203.088.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.088.032.000', '203.088.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.088.064.000', '203.088.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.088.096.000', '203.088.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.088.160.000', '203.088.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.088.176.000', '203.088.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.088.192.000', '203.088.221.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.088.222.000', '203.088.223.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('203.088.224.000', '203.088.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.089.000.000', '203.089.003.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('203.089.004.000', '203.089.005.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.089.006.000', '203.089.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.089.008.000', '203.089.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.090.000.000', '203.090.003.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.090.004.000', '203.090.005.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.090.006.000', '203.090.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.090.008.000', '203.090.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.090.128.000', '203.090.223.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('203.090.224.000', '203.090.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.091.000.000', '203.091.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.091.032.000', '203.091.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.091.064.000', '203.091.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.091.096.000', '203.091.111.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('203.091.112.000', '203.091.119.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.091.120.000', '203.091.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.091.128.000', '203.091.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.091.160.000', '203.091.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.092.000.000', '203.092.003.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.092.004.000', '203.092.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.092.160.000', '203.092.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.092.192.000', '203.092.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.092.208.000', '203.092.208.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.092.209.000', '203.092.210.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.092.211.000', '203.092.211.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.092.212.000', '203.092.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.093.000.000', '203.093.000.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.001.000', '203.093.001.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.093.002.000', '203.093.003.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.093.004.000', '203.093.004.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('203.093.005.000', '203.093.006.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.007.000', '203.093.014.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.015.000', '203.093.015.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.016.000', '203.093.018.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.019.000', '203.093.019.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.020.000', '203.093.021.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.022.000', '203.093.023.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('203.093.024.000', '203.093.025.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.026.000', '203.093.026.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.093.027.000', '203.093.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.032.000', '203.093.035.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.036.000', '203.093.039.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.040.000', '203.093.041.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('203.093.042.000', '203.093.043.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.044.000', '203.093.045.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.046.000', '203.093.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.048.000', '203.093.049.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.050.000', '203.093.051.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.052.000', '203.093.052.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.053.000', '203.093.053.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.054.000', '203.093.054.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('203.093.055.000', '203.093.059.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.060.000', '203.093.063.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.064.000', '203.093.066.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.067.000', '203.093.067.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.068.000', '203.093.083.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.084.000', '203.093.085.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.086.000', '203.093.087.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.088.000', '203.093.089.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.090.000', '203.093.093.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('203.093.094.000', '203.093.094.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.095.000', '203.093.105.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.106.000', '203.093.112.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.113.000', '203.093.113.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('203.093.114.000', '203.093.115.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.116.000', '203.093.116.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('203.093.117.000', '203.093.119.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.120.000', '203.093.122.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.123.000', '203.093.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.128.000', '203.093.129.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.093.130.000', '203.093.131.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.132.000', '203.093.133.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('203.093.134.000', '203.093.135.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.136.000', '203.093.140.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.141.000', '203.093.141.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.142.000', '203.093.142.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.093.143.000', '203.093.144.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.145.000', '203.093.145.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('203.093.146.000', '203.093.146.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.147.000', '203.093.151.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.093.152.000', '203.093.152.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.153.000', '203.093.153.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.154.000', '203.093.154.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.155.000', '203.093.155.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.156.000', '203.093.157.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.158.000', '203.093.159.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('203.093.160.000', '203.093.165.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.166.000', '203.093.167.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.168.000', '203.093.168.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.169.000', '203.093.169.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.170.000', '203.093.170.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.171.000', '203.093.171.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.172.000', '203.093.175.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.176.000', '203.093.176.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.177.000', '203.093.178.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.179.000', '203.093.179.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.180.000', '203.093.180.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.093.181.000', '203.093.181.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('203.093.182.000', '203.093.183.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('203.093.184.000', '203.093.184.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.185.000', '203.093.185.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('203.093.186.000', '203.093.186.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.187.000', '203.093.188.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.189.000', '203.093.189.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.190.000', '203.093.190.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.191.000', '203.093.193.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.194.000', '203.093.194.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.195.000', '203.093.195.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.196.000', '203.093.196.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.197.000', '203.093.197.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.198.000', '203.093.199.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.200.000', '203.093.200.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('203.093.201.000', '203.093.202.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.203.000', '203.093.203.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('203.093.204.000', '203.093.204.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.205.000', '203.093.205.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.206.000', '203.093.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.208.000', '203.093.208.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.209.000', '203.093.209.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.210.000', '203.093.210.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.211.000', '203.093.211.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.093.212.000', '203.093.212.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.213.000', '203.093.213.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.214.000', '203.093.214.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('203.093.215.000', '203.093.215.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.216.000', '203.093.216.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.217.000', '203.093.217.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.093.218.000', '203.093.218.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.093.219.000', '203.093.219.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.093.220.000', '203.093.220.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.221.000', '203.093.221.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.093.222.000', '203.093.222.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.093.223.000', '203.093.223.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.224.000', '203.093.237.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.093.238.000', '203.093.238.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.093.239.000', '203.093.239.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.240.000', '203.093.240.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.241.000', '203.093.241.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.093.242.000', '203.093.242.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.243.000', '203.093.243.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('203.093.244.000', '203.093.244.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.245.000', '203.093.247.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.093.248.000', '203.093.251.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.093.252.000', '203.093.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.094.000.000', '203.094.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.094.032.000', '203.094.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.095.000.000', '203.095.007.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.095.008.000', '203.095.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.095.096.000', '203.095.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.095.128.000', '203.095.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.096.000.000', '203.098.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.098.128.000', '203.098.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.098.192.000', '203.099.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.099.016.000', '203.099.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.099.032.000', '203.099.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.099.080.000', '203.099.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.099.096.000', '203.099.253.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.099.254.000', '203.099.254.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.099.255.000', '203.100.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.100.032.000', '203.100.047.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.100.048.000', '203.100.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.100.096.000', '203.100.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.100.128.000', '203.100.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.100.192.000', '203.100.207.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.100.208.000', '203.104.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.105.000.000', '203.105.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.105.064.000', '203.105.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.105.096.000', '203.105.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.105.128.000', '203.105.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.105.224.000', '203.105.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.106.000.000', '203.106.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.107.000.000', '203.107.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.107.064.000', '203.110.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.110.160.000', '203.110.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.110.192.000', '203.111.207.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.111.208.000', '203.111.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.111.224.000', '203.112.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.112.080.000', '203.112.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.112.096.000', '203.118.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.118.192.000', '203.118.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.118.224.000', '203.118.239.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.118.240.000', '203.119.001.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.119.002.000', '203.119.002.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.119.003.000', '203.119.003.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.119.004.000', '203.121.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.121.224.000', '203.121.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.122.000.000', '203.124.007.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.124.008.000', '203.124.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.124.016.000', '203.128.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.128.032.000', '203.128.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.128.064.000', '203.128.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.128.096.000', '203.128.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.128.128.000', '203.128.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.128.160.000', '203.129.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.129.064.000', '203.129.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.129.096.000', '203.130.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.130.032.000', '203.130.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.130.064.000', '203.131.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.131.224.000', '203.131.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.132.000.000', '203.132.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.132.032.000', '203.132.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.132.064.000', '203.132.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.132.192.000', '203.132.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.132.208.000', '203.132.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.133.000.000', '203.133.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.133.128.000', '203.134.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.134.240.000', '203.134.247.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.134.248.000', '203.135.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.135.064.000', '203.135.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.135.096.000', '203.135.111.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.135.112.000', '203.135.119.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.135.120.000', '203.135.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.135.128.000', '203.135.159.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.135.160.000', '203.135.175.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.135.176.000', '203.142.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.142.096.000', '203.142.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.142.128.000', '203.145.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.145.064.000', '203.145.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.145.096.000', '203.145.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.145.192.000', '203.145.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.145.224.000', '203.147.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.148.000.000', '203.148.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.148.064.000', '203.152.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.152.064.000', '203.152.095.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('203.152.096.000', '203.153.003.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.153.004.000', '203.153.005.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.153.006.000', '203.153.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.153.064.000', '203.153.079.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.153.080.000', '203.156.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.156.192.000', '203.156.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.157.000.000', '203.158.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.158.016.000', '203.158.023.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.158.024.000', '203.160.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.160.032.000', '203.160.047.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.160.048.000', '203.160.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.160.064.000', '203.160.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.160.096.000', '203.160.143.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.160.144.000', '203.160.159.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.160.160.000', '203.160.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.160.224.000', '203.160.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.161.000.000', '203.161.003.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.161.004.000', '203.161.007.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.161.008.000', '203.161.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.161.192.000', '203.161.223.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.161.224.000', '203.161.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.162.000.000', '203.163.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.163.192.000', '203.163.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.163.224.000', '203.166.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.166.160.000', '203.166.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.166.192.000', '203.168.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.168.128.000', '203.168.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.169.000.000', '203.169.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.169.032.000', '203.169.047.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.169.048.000', '203.169.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.169.128.000', '203.169.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.170.000.000', '203.171.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.171.224.000', '203.171.239.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('203.171.240.000', '203.174.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.174.032.000', '203.174.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.174.064.000', '203.174.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.174.096.000', '203.174.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.174.128.000', '203.175.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.175.128.000', '203.175.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.175.160.000', '203.175.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.175.192.000', '203.175.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.176.000.000', '203.176.167.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.176.168.000', '203.176.175.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.176.176.000', '203.184.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.184.080.000', '203.184.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('203.184.096.000', '203.184.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.184.128.000', '203.185.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.185.064.000', '203.185.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.186.000.000', '203.186.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.187.000.000', '203.187.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.187.128.000', '203.187.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.187.160.000', '203.187.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.187.192.000', '203.188.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.188.064.000', '203.188.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.188.128.000', '203.188.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.188.192.000', '203.188.207.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.188.208.000', '203.189.159.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.189.160.000', '203.189.175.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.189.176.000', '203.190.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.190.016.000', '203.190.023.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.190.024.000', '203.190.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.190.096.000', '203.190.111.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('203.190.112.000', '203.190.248.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.190.249.000', '203.190.249.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.190.250.000', '203.191.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.191.016.000', '203.191.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.191.032.000', '203.191.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.191.064.000', '203.191.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.191.128.000', '203.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.192.000.000', '203.192.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.192.032.000', '203.192.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.193.000.000', '203.193.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.193.128.000', '203.194.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.194.128.000', '203.195.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.195.096.000', '203.195.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.196.000.000', '203.196.007.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.196.008.000', '203.196.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.196.016.000', '203.197.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.198.000.000', '203.198.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.199.000.000', '203.201.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.201.032.000', '203.201.047.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.201.048.000', '203.202.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.202.224.000', '203.202.231.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.202.232.000', '203.202.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.203.000.000', '203.204.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.205.000.000', '203.206.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.207.000.000', '203.207.015.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.207.016.000', '203.207.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.207.032.000', '203.207.047.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.207.048.000', '203.207.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.207.064.000', '203.207.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.207.096.000', '203.207.119.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.207.120.000', '203.207.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('203.207.128.000', '203.207.136.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('203.207.137.000', '203.207.137.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.207.138.000', '203.207.138.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.207.139.000', '203.207.140.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('203.207.141.000', '203.207.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.207.160.000', '203.207.167.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('203.207.168.000', '203.207.175.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.207.176.000', '203.207.177.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('203.207.178.000', '203.207.179.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.207.180.000', '203.207.189.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('203.207.190.000', '203.207.191.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('203.207.192.000', '203.207.239.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.207.240.000', '203.207.247.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.207.248.000', '203.207.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('203.208.000.000', '203.208.019.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('203.208.020.000', '203.208.020.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.208.021.000', '203.208.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.208.032.000', '203.208.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.208.064.000', '203.209.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.209.128.000', '203.209.143.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.209.144.000', '203.209.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.209.224.000', '203.209.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.210.000.000', '203.210.015.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.210.016.000', '203.210.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.211.000.000', '203.211.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.211.032.000', '203.211.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.212.000.000', '203.212.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.212.016.000', '203.212.079.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.212.080.000', '203.212.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('203.212.096.000', '203.215.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.215.240.000', '203.215.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.216.000.000', '203.217.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.217.096.000', '203.217.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.217.128.000', '203.217.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.218.000.000', '203.218.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.219.000.000', '203.221.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.222.000.000', '203.222.031.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('203.222.032.000', '203.222.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.222.192.000', '203.222.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.222.208.000', '203.222.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.223.000.000', '203.223.015.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('203.223.016.000', '203.223.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('203.223.192.000', '203.223.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('203.224.000.000', '210.000.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.000.128.000', '210.000.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.001.000.000', '210.001.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.001.240.000', '210.001.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.002.000.000', '210.002.031.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.002.032.000', '210.002.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.003.000.000', '210.003.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.004.000.000', '210.004.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.005.000.000', '210.005.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.005.032.000', '210.005.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.005.128.000', '210.005.143.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.005.144.000', '210.005.159.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.005.160.000', '210.006.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.007.000.000', '210.011.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.012.000.000', '210.012.002.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.003.000', '210.012.003.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.004.000', '210.012.004.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.012.005.000', '210.012.005.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.012.006.000', '210.012.006.255', '中北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.007.000', '210.012.007.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.008.000', '210.012.008.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.009.000', '210.012.010.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.011.000', '210.012.012.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.013.000', '210.012.013.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.014.000', '210.012.014.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.015.000', '210.012.017.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.018.000', '210.012.018.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.012.019.000', '210.012.019.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.020.000', '210.012.022.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.023.000', '210.012.023.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.024.000', '210.012.024.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.025.000', '210.012.026.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.027.000', '210.012.027.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.012.028.000', '210.012.028.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.029.000', '210.012.029.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.030.000', '210.012.030.255', '黑龙江');
INSERT INTO eq_ipdatabase VALUES ('210.012.031.000', '210.012.031.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.032.000', '210.012.032.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.012.033.000', '210.012.033.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.012.034.000', '210.012.034.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.035.000', '210.012.035.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.036.000', '210.012.036.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.037.000', '210.012.037.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.038.000', '210.012.038.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.039.000', '210.012.039.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.040.000', '210.012.040.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.041.000', '210.012.041.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.042.000', '210.012.042.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.043.000', '210.012.043.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.044.000', '210.012.044.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.045.000', '210.012.046.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.047.000', '210.012.047.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.048.000', '210.012.048.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.049.000', '210.012.049.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.050.000', '210.012.050.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.051.000', '210.012.051.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.052.000', '210.012.052.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.012.053.000', '210.012.053.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.054.000', '210.012.054.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.055.000', '210.012.055.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.012.056.000', '210.012.056.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.057.000', '210.012.057.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.058.000', '210.012.059.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.060.000', '210.012.060.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.061.000', '210.012.061.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.062.000', '210.012.062.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.063.000', '210.012.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.064.000', '210.012.066.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.067.000', '210.012.068.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.069.000', '210.012.070.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.071.000', '210.012.072.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.073.000', '210.012.077.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.078.000', '210.012.083.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.084.000', '210.012.084.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.085.000', '210.012.085.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.086.000', '210.012.086.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.012.087.000', '210.012.088.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.089.000', '210.012.089.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.090.000', '210.012.090.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.091.000', '210.012.092.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.093.000', '210.012.097.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.098.000', '210.012.098.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.099.000', '210.012.099.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.100.000', '210.012.100.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.012.101.000', '210.012.101.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.102.000', '210.012.102.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.103.000', '210.012.103.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.104.000', '210.012.105.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.106.000', '210.012.109.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.110.000', '210.012.111.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.112.000', '210.012.112.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.113.000', '210.012.113.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.114.000', '210.012.114.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.115.000', '210.012.115.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.116.000', '210.012.116.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.117.000', '210.012.118.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.119.000', '210.012.119.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.120.000', '210.012.120.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.121.000', '210.012.122.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.123.000', '210.012.123.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.124.000', '210.012.128.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.129.000', '210.012.129.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.012.130.000', '210.012.131.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.132.000', '210.012.133.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.134.000', '210.012.134.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.135.000', '210.012.135.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.136.000', '210.012.136.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.012.137.000', '210.012.137.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.138.000', '210.012.139.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.140.000', '210.012.141.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.142.000', '210.012.142.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.143.000', '210.012.143.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.144.000', '210.012.145.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.146.000', '210.012.148.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.149.000', '210.012.149.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.150.000', '210.012.151.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.152.000', '210.012.152.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.153.000', '210.012.153.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.154.000', '210.012.156.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.157.000', '210.012.157.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.158.000', '210.012.158.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.159.000', '210.012.159.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.160.000', '210.012.160.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.161.000', '210.012.161.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.012.162.000', '210.012.162.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.163.000', '210.012.163.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.164.000', '210.012.164.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.165.000', '210.012.165.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.166.000', '210.012.166.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.167.000', '210.012.167.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.168.000', '210.012.168.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.169.000', '210.012.169.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.170.000', '210.012.170.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.171.000', '210.012.171.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.172.000', '210.012.173.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.174.000', '210.012.174.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.175.000', '210.012.175.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.176.000', '210.012.178.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.179.000', '210.012.179.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.180.000', '210.012.180.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.181.000', '210.012.183.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.184.000', '210.012.184.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.185.000', '210.012.185.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('210.012.186.000', '210.012.186.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.187.000', '210.012.187.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.012.188.000', '210.012.188.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.012.189.000', '210.012.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.192.000', '210.012.192.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.193.000', '210.012.193.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.194.000', '210.012.194.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.195.000', '210.012.195.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.196.000', '210.012.196.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.197.000', '210.012.197.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.198.000', '210.012.198.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.199.000', '210.012.199.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.012.200.000', '210.012.200.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.201.000', '210.012.201.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.202.000', '210.012.203.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.012.204.000', '210.012.204.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('210.012.205.000', '210.012.205.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.206.000', '210.012.206.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.207.000', '210.012.207.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.208.000', '210.012.208.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.209.000', '210.012.209.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('210.012.210.000', '210.012.213.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.012.214.000', '210.012.214.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.215.000', '210.012.215.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.216.000', '210.012.216.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('210.012.217.000', '210.012.217.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.218.000', '210.012.218.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.012.219.000', '210.012.219.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('210.012.220.000', '210.012.220.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.012.221.000', '210.012.221.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.222.000', '210.012.222.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.223.000', '210.012.223.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.012.224.000', '210.012.227.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.228.000', '210.012.228.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.012.229.000', '210.012.229.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.012.230.000', '210.012.230.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.231.000', '210.012.231.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.232.000', '210.012.233.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.234.000', '210.012.234.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.235.000', '210.012.235.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.236.000', '210.012.237.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.238.000', '210.012.238.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.012.239.000', '210.012.239.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.240.000', '210.012.245.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.012.246.000', '210.012.246.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.012.247.000', '210.012.247.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.012.248.000', '210.012.248.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.012.249.000', '210.012.252.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.012.253.000', '210.012.253.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.012.254.000', '210.012.254.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.012.255.000', '210.013.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.013.064.000', '210.013.073.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.013.074.000', '210.013.074.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.013.075.000', '210.013.075.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.013.076.000', '210.013.077.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.013.078.000', '210.013.078.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.013.079.000', '210.013.079.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.013.080.000', '210.013.080.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('210.013.081.000', '210.013.081.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.013.082.000', '210.013.083.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.013.084.000', '210.013.085.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.013.086.000', '210.013.088.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.013.089.000', '210.013.091.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.013.092.000', '210.013.094.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.013.095.000', '210.013.097.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.013.098.000', '210.013.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.013.128.000', '210.013.207.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.013.208.000', '210.013.220.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.013.221.000', '210.013.239.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.013.240.000', '210.013.240.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.013.241.000', '210.013.241.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.013.242.000', '210.013.242.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.013.243.000', '210.013.243.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.013.244.000', '210.013.249.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.013.250.000', '210.013.250.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.013.251.000', '210.013.252.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.013.253.000', '210.013.253.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.013.254.000', '210.013.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.014.000.000', '210.014.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.014.064.000', '210.014.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.014.096.000', '210.014.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.014.128.000', '210.014.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.014.160.000', '210.014.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.176.000', '210.014.179.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.014.180.000', '210.014.183.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.184.000', '210.014.185.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.014.186.000', '210.014.186.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.187.000', '210.014.187.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.188.000', '210.014.189.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.190.000', '210.014.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.192.000', '210.014.195.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.196.000', '210.014.197.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.198.000', '210.014.198.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.199.000', '210.014.199.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.200.000', '210.014.201.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.202.000', '210.014.202.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.203.000', '210.014.205.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.206.000', '210.014.206.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.014.207.000', '210.014.207.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.208.000', '210.014.213.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.214.000', '210.014.215.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.216.000', '210.014.216.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.217.000', '210.014.217.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.218.000', '210.014.231.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.232.000', '210.014.233.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.234.000', '210.014.234.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.014.235.000', '210.014.241.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.242.000', '210.014.242.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.243.000', '210.014.243.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.244.000', '210.014.244.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.245.000', '210.014.245.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.246.000', '210.014.246.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.014.247.000', '210.014.247.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('210.014.248.000', '210.014.248.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.249.000', '210.014.249.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.014.250.000', '210.014.250.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('210.014.251.000', '210.014.251.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.014.252.000', '210.014.254.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.014.255.000', '210.014.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.015.000.000', '210.015.003.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.004.000', '210.015.007.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.008.000', '210.015.009.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.010.000', '210.015.011.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.012.000', '210.015.013.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.014.000', '210.015.015.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.016.000', '210.015.017.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.018.000', '210.015.023.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.024.000', '210.015.024.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.025.000', '210.015.025.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.026.000', '210.015.026.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.027.000', '210.015.027.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.028.000', '210.015.028.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('210.015.029.000', '210.015.029.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.030.000', '210.015.030.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.015.031.000', '210.015.033.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.034.000', '210.015.037.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.038.000', '210.015.038.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.015.039.000', '210.015.039.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.040.000', '210.015.041.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.042.000', '210.015.042.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.043.000', '210.015.043.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.044.000', '210.015.044.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.045.000', '210.015.045.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.046.000', '210.015.046.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.047.000', '210.015.047.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.048.000', '210.015.048.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.049.000', '210.015.050.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.051.000', '210.015.051.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.015.052.000', '210.015.055.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.056.000', '210.015.058.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.059.000', '210.015.059.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.060.000', '210.015.060.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.061.000', '210.015.061.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.062.000', '210.015.062.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('210.015.063.000', '210.015.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.064.000', '210.015.064.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.015.065.000', '210.015.066.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.067.000', '210.015.067.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.068.000', '210.015.069.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.070.000', '210.015.072.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.073.000', '210.015.073.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.074.000', '210.015.075.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.015.076.000', '210.015.077.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.015.078.000', '210.015.078.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.015.079.000', '210.015.079.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('210.015.080.000', '210.015.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.015.192.000', '210.016.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.016.128.000', '210.016.191.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.016.192.000', '210.016.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.017.000.000', '210.017.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.017.128.000', '210.017.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.018.000.000', '210.020.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.021.000.000', '210.022.035.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.022.036.000', '210.022.047.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.048.000', '210.022.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.022.064.000', '210.022.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.022.192.000', '210.022.193.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.194.000', '210.022.194.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.022.195.000', '210.022.195.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.196.000', '210.022.196.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.022.197.000', '210.022.197.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.198.000', '210.022.201.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.202.000', '210.022.205.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.022.206.000', '210.022.207.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.022.208.000', '210.022.219.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.022.220.000', '210.022.252.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.022.253.000', '210.022.253.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.022.254.000', '210.022.254.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.022.255.000', '210.022.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.023.000.000', '210.023.031.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.023.032.000', '210.023.063.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.023.064.000', '210.024.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.025.000.000', '210.025.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.025.160.000', '210.025.251.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.025.252.000', '210.025.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.026.000.000', '210.026.127.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.026.128.000', '210.026.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.027.000.000', '210.027.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.027.128.000', '210.027.191.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('210.027.192.000', '210.027.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.028.000.000', '210.029.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.030.000.000', '210.030.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.031.000.000', '210.031.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.031.096.000', '210.031.127.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.031.128.000', '210.031.159.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.031.160.000', '210.031.175.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.031.176.000', '210.031.191.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('210.031.192.000', '210.031.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.032.000.000', '210.033.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.034.000.000', '210.034.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.035.000.000', '210.035.063.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.035.064.000', '210.035.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.035.128.000', '210.035.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.036.000.000', '210.036.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.037.000.000', '210.037.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('210.038.000.000', '210.039.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.040.000.000', '210.040.175.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('210.040.176.000', '210.040.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.041.000.000', '210.041.031.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('210.041.032.000', '210.041.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('210.041.064.000', '210.041.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.042.000.000', '210.042.141.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.042.142.000', '210.042.143.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.042.144.000', '210.042.207.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.042.208.000', '210.043.039.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.043.040.000', '210.043.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.043.128.000', '210.043.147.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.043.148.000', '210.043.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.044.000.000', '210.044.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.045.000.000', '210.045.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('210.046.000.000', '210.046.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.047.000.000', '210.047.063.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.047.064.000', '210.047.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.047.128.000', '210.047.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.048.000.000', '210.050.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.051.000.000', '210.051.019.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.051.020.000', '210.051.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.051.064.000', '210.051.065.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.066.000', '210.051.066.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.051.067.000', '210.051.125.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.126.000', '210.051.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.051.128.000', '210.051.135.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.136.000', '210.051.140.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.051.141.000', '210.051.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.160.000', '210.051.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.051.192.000', '210.051.193.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.051.194.000', '210.051.194.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.195.000', '210.051.197.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.051.198.000', '210.051.200.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.201.000', '210.051.211.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.051.212.000', '210.051.215.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.051.216.000', '210.051.216.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.217.000', '210.051.225.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.051.226.000', '210.051.226.255', '中国中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.227.000', '210.051.240.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.051.241.000', '210.051.241.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.051.242.000', '210.051.242.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.051.243.000', '210.051.243.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.051.244.000', '210.051.250.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.051.251.000', '210.051.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.052.000.000', '210.052.002.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.003.000', '210.052.006.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.007.000', '210.052.007.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.008.000', '210.052.008.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.052.009.000', '210.052.009.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.052.010.000', '210.052.010.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.052.011.000', '210.052.011.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.012.000', '210.052.013.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.052.014.000', '210.052.014.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.015.000', '210.052.015.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.016.000', '210.052.016.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.052.017.000', '210.052.017.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.018.000', '210.052.018.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.052.019.000', '210.052.019.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.020.000', '210.052.020.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.052.021.000', '210.052.022.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.023.000', '210.052.024.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.025.000', '210.052.025.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.026.000', '210.052.026.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.027.000', '210.052.027.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.028.000', '210.052.039.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.052.040.000', '210.052.040.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.041.000', '210.052.042.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.052.043.000', '210.052.044.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.045.000', '210.052.047.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.048.000', '210.052.050.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.051.000', '210.052.053.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.052.054.000', '210.052.055.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.056.000', '210.052.058.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.052.059.000', '210.052.059.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.052.060.000', '210.052.060.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.061.000', '210.052.063.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.064.000', '210.052.065.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.066.000', '210.052.070.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.071.000', '210.052.073.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.074.000', '210.052.074.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.075.000', '210.052.075.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.052.076.000', '210.052.077.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.078.000', '210.052.078.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.052.079.000', '210.052.080.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.081.000', '210.052.082.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.083.000', '210.052.088.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.089.000', '210.052.091.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.092.000', '210.052.092.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.052.093.000', '210.052.103.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.104.000', '210.052.131.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.132.000', '210.052.147.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.148.000', '210.052.148.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.149.000', '210.052.149.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.052.150.000', '210.052.153.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.154.000', '210.052.154.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.052.155.000', '210.052.157.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.052.158.000', '210.052.165.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.052.166.000', '210.052.166.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.167.000', '210.052.167.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.168.000', '210.052.173.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.052.174.000', '210.052.181.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.052.182.000', '210.052.187.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.052.188.000', '210.052.189.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.190.000', '210.052.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.052.192.000', '210.052.193.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.194.000', '210.052.197.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.052.198.000', '210.052.199.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.052.200.000', '210.052.231.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.052.232.000', '210.052.235.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.052.236.000', '210.052.241.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.052.242.000', '210.052.243.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.052.244.000', '210.052.245.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.052.246.000', '210.052.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.053.000.000', '210.053.007.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.053.008.000', '210.053.015.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('210.053.016.000', '210.053.023.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.053.024.000', '210.053.043.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.053.044.000', '210.053.051.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.053.052.000', '210.053.057.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.053.058.000', '210.053.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('210.053.064.000', '210.053.071.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.053.072.000', '210.053.087.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.053.088.000', '210.053.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.053.128.000', '210.053.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.053.192.000', '210.053.192.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.053.193.000', '210.053.193.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.053.194.000', '210.053.194.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('210.053.195.000', '210.053.195.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.053.196.000', '210.053.199.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.053.200.000', '210.053.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.053.208.000', '210.053.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.054.000.000', '210.056.047.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.056.048.000', '210.056.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.056.064.000', '210.056.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.056.192.000', '210.056.207.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.056.208.000', '210.056.215.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.056.216.000', '210.056.223.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.056.224.000', '210.056.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.057.000.000', '210.057.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.057.064.000', '210.057.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.057.128.000', '210.057.207.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.057.208.000', '210.057.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.058.000.000', '210.071.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.072.000.000', '210.072.007.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.072.008.000', '210.072.009.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.072.010.000', '210.072.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.072.128.000', '210.072.143.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.072.144.000', '210.072.151.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.072.152.000', '210.072.163.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.072.164.000', '210.072.165.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.072.166.000', '210.072.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.072.192.000', '210.073.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.073.128.000', '210.073.159.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.073.160.000', '210.073.207.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.073.208.000', '210.073.223.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.073.224.000', '210.074.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.074.064.000', '210.074.095.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.074.096.000', '210.074.115.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.074.116.000', '210.074.121.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.074.122.000', '210.074.122.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.074.123.000', '210.074.123.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('210.074.124.000', '210.074.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.074.128.000', '210.074.133.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.134.000', '210.074.134.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.074.135.000', '210.074.136.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.074.137.000', '210.074.138.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.139.000', '210.074.139.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.074.140.000', '210.074.141.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.142.000', '210.074.142.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.074.143.000', '210.074.147.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.148.000', '210.074.148.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.074.149.000', '210.074.151.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.152.000', '210.074.152.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.074.153.000', '210.074.153.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.154.000', '210.074.154.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('210.074.155.000', '210.074.156.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.157.000', '210.074.158.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.074.159.000', '210.074.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.074.160.000', '210.074.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.074.224.000', '210.074.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.075.000.000', '210.075.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.075.064.000', '210.075.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.075.096.000', '210.075.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.075.128.000', '210.075.159.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('210.075.160.000', '210.075.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.075.224.000', '210.075.231.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.075.232.000', '210.075.239.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('210.075.240.000', '210.075.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.076.000.000', '210.076.031.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.076.032.000', '210.076.063.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.076.064.000', '210.076.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.076.096.000', '210.076.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.076.128.000', '210.076.159.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.076.160.000', '210.076.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.076.192.000', '210.076.207.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('210.076.208.000', '210.076.208.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('210.076.209.000', '210.076.223.255', '中国');
INSERT INTO eq_ipdatabase VALUES ('210.076.224.000', '210.077.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.077.064.000', '210.077.079.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.077.080.000', '210.077.085.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.077.086.000', '210.077.087.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.077.088.000', '210.077.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.077.096.000', '210.077.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.077.128.000', '210.077.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.077.160.000', '210.077.175.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('210.077.176.000', '210.077.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.077.192.000', '210.077.223.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.077.224.000', '210.077.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.078.000.000', '210.078.023.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('210.078.024.000', '210.078.031.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.078.032.000', '210.078.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.078.064.000', '210.078.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.078.096.000', '210.078.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.078.128.000', '210.078.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.078.160.000', '210.078.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.078.224.000', '210.079.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.079.064.000', '210.079.127.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.079.128.000', '210.079.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.079.224.000', '210.079.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.080.000.000', '210.080.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.080.064.000', '210.080.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.080.096.000', '210.081.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.082.000.000', '210.082.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.128.000', '210.082.130.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.131.000', '210.082.131.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.132.000', '210.082.136.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.082.137.000', '210.082.137.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.138.000', '210.082.139.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.082.140.000', '210.082.163.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.082.164.000', '210.082.165.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.166.000', '210.082.166.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.082.167.000', '210.082.167.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.082.168.000', '210.082.171.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.082.172.000', '210.082.172.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.082.173.000', '210.082.173.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.174.000', '210.082.174.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.175.000', '210.082.176.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.082.177.000', '210.082.177.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.178.000', '210.082.181.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.082.182.000', '210.082.182.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.183.000', '210.082.183.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.184.000', '210.082.190.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.082.191.000', '210.082.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.192.000', '210.082.192.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('210.082.193.000', '210.082.193.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.194.000', '210.082.194.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.082.195.000', '210.082.197.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.198.000', '210.082.198.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('210.082.199.000', '210.082.203.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.082.204.000', '210.082.206.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.207.000', '210.082.211.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.082.212.000', '210.082.213.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.082.214.000', '210.082.214.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.215.000', '210.082.215.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.216.000', '210.082.224.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.225.000', '210.082.226.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.082.227.000', '210.082.227.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.082.228.000', '210.082.228.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.229.000', '210.082.229.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.082.230.000', '210.082.230.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('210.082.231.000', '210.082.234.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('210.082.235.000', '210.082.235.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('210.082.236.000', '210.082.237.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.082.238.000', '210.082.239.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.082.240.000', '210.082.240.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('210.082.241.000', '210.082.241.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.082.242.000', '210.082.243.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.082.244.000', '210.082.247.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.082.248.000', '210.082.248.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('210.082.249.000', '210.082.249.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.082.250.000', '210.082.251.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('210.082.252.000', '210.082.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('210.083.000.000', '210.083.015.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.083.016.000', '210.083.031.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.083.032.000', '210.083.047.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.083.048.000', '210.083.055.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('210.083.056.000', '210.083.063.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('210.083.064.000', '210.083.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('210.083.192.000', '210.083.223.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.083.224.000', '210.083.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.084.000.000', '210.084.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.085.000.000', '210.085.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.086.000.000', '210.086.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.086.128.000', '210.086.223.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.086.224.000', '210.087.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.087.128.000', '210.087.191.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('210.087.192.000', '210.087.239.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.087.240.000', '210.087.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.088.000.000', '210.089.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.089.064.000', '210.089.095.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.089.096.000', '210.175.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.176.000.000', '210.177.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.178.000.000', '210.183.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.184.000.000', '210.184.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.185.000.000', '210.185.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.185.192.000', '210.185.199.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.185.200.000', '210.185.200.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('210.185.201.000', '210.185.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('210.186.000.000', '210.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.192.000.000', '210.192.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.192.064.000', '210.192.095.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.192.096.000', '210.192.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('210.192.128.000', '210.192.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.193.000.000', '210.199.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.200.000.000', '210.203.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.203.128.000', '210.207.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.208.000.000', '210.209.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.209.064.000', '210.209.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.209.128.000', '210.209.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.210.000.000', '210.210.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.211.000.000', '210.211.015.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('210.211.016.000', '210.239.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.240.000.000', '210.244.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('210.245.000.000', '210.245.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('210.245.128.000', '210.245.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('210.246.000.000', '211.019.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('211.020.000.000', '211.023.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('211.024.000.000', '211.063.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('211.064.000.000', '211.064.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.065.000.000', '211.065.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.066.000.000', '211.066.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.067.000.000', '211.067.079.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.067.080.000', '211.067.207.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.067.208.000', '211.067.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.068.000.000', '211.068.111.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.068.112.000', '211.068.119.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.068.120.000', '211.068.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.068.128.000', '211.068.191.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.068.192.000', '211.068.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.069.000.000', '211.069.047.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.069.048.000', '211.069.215.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.069.216.000', '211.069.223.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.069.224.000', '211.069.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.070.000.000', '211.070.039.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.070.040.000', '211.070.063.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.070.064.000', '211.070.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.070.128.000', '211.070.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.070.192.000', '211.070.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.071.000.000', '211.071.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.072.000.000', '211.079.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('211.080.000.000', '211.080.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.080.128.000', '211.080.159.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.080.160.000', '211.080.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.081.000.000', '211.081.007.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.081.008.000', '211.081.063.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.081.064.000', '211.081.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.082.000.000', '211.082.063.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.082.064.000', '211.082.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.082.128.000', '211.082.191.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.082.192.000', '211.082.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.083.000.000', '211.083.015.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.083.016.000', '211.083.031.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.083.032.000', '211.083.159.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.083.160.000', '211.083.175.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.083.176.000', '211.083.191.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.083.192.000', '211.083.239.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.083.240.000', '211.083.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.084.000.000', '211.084.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.085.000.000', '211.085.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.086.000.000', '211.086.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.086.128.000', '211.086.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.087.000.000', '211.087.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.087.128.000', '211.087.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.088.000.000', '211.088.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.089.000.000', '211.089.015.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.089.016.000', '211.089.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.032.000', '211.089.034.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.089.035.000', '211.089.111.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.112.000', '211.089.113.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.089.114.000', '211.089.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.128.000', '211.089.129.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.089.130.000', '211.089.151.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.152.000', '211.089.153.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.089.154.000', '211.089.185.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.186.000', '211.089.186.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.089.187.000', '211.089.251.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.089.252.000', '211.090.007.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.090.008.000', '211.090.043.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.090.044.000', '211.090.044.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.090.045.000', '211.090.071.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.090.072.000', '211.090.072.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.090.073.000', '211.090.073.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.090.074.000', '211.090.079.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.090.080.000', '211.090.087.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.090.088.000', '211.090.215.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.090.216.000', '211.091.087.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.091.088.000', '211.091.119.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.091.120.000', '211.091.183.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.091.184.000', '211.091.215.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.091.216.000', '211.091.247.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.091.248.000', '211.092.007.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.092.008.000', '211.092.071.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.092.072.000', '211.092.103.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.092.104.000', '211.092.135.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.092.136.000', '211.092.143.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.092.144.000', '211.092.175.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.092.176.000', '211.092.239.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('211.092.240.000', '211.092.247.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.092.248.000', '211.092.255.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.093.000.000', '211.093.007.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('211.093.008.000', '211.093.015.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('211.093.016.000', '211.093.023.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.093.024.000', '211.093.063.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.093.064.000', '211.093.079.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.093.080.000', '211.093.151.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.093.152.000', '211.093.163.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.093.164.000', '211.093.167.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.093.168.000', '211.094.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.094.192.000', '211.094.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.095.000.000', '211.095.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.095.128.000', '211.095.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.095.192.000', '211.097.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.097.064.000', '211.097.095.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.097.096.000', '211.097.103.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('211.097.104.000', '211.097.167.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.097.168.000', '211.097.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.000.000', '211.098.001.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.002.000', '211.098.003.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.004.000', '211.098.007.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.008.000', '211.098.011.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.012.000', '211.098.013.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.098.014.000', '211.098.015.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.016.000', '211.098.017.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.018.000', '211.098.023.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.024.000', '211.098.024.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.025.000', '211.098.025.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.098.026.000', '211.098.028.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.029.000', '211.098.029.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.098.030.000', '211.098.030.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.031.000', '211.098.031.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.098.032.000', '211.098.032.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.033.000', '211.098.033.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.098.034.000', '211.098.035.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.036.000', '211.098.036.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.098.037.000', '211.098.037.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.038.000', '211.098.041.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.098.042.000', '211.098.045.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.098.046.000', '211.098.047.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.048.000', '211.098.049.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.098.050.000', '211.098.050.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.098.051.000', '211.098.051.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.052.000', '211.098.052.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.098.053.000', '211.098.053.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.098.054.000', '211.098.055.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.098.056.000', '211.098.056.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.057.000', '211.098.061.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.062.000', '211.098.065.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.066.000', '211.098.069.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.098.070.000', '211.098.071.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.072.000', '211.098.072.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.073.000', '211.098.077.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.078.000', '211.098.080.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.098.081.000', '211.098.083.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.098.084.000', '211.098.087.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.098.088.000', '211.098.089.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.098.090.000', '211.098.095.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.096.000', '211.098.099.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.100.000', '211.098.101.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.102.000', '211.098.107.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.108.000', '211.098.109.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.110.000', '211.098.111.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.098.112.000', '211.098.117.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.098.118.000', '211.098.119.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.120.000', '211.098.121.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.122.000', '211.098.123.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.098.124.000', '211.098.125.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('211.098.126.000', '211.098.126.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.098.127.000', '211.098.127.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.128.000', '211.098.128.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.129.000', '211.098.129.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.130.000', '211.098.131.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.132.000', '211.098.132.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.133.000', '211.098.133.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.098.134.000', '211.098.134.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.135.000', '211.098.135.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.098.136.000', '211.098.138.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.139.000', '211.098.139.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.098.140.000', '211.098.140.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.098.141.000', '211.098.141.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.142.000', '211.098.142.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.143.000', '211.098.144.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.145.000', '211.098.146.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.098.147.000', '211.098.150.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.151.000', '211.098.152.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.098.153.000', '211.098.157.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.158.000', '211.098.159.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.160.000', '211.098.160.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.161.000', '211.098.161.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.162.000', '211.098.164.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.098.165.000', '211.098.165.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.166.000', '211.098.166.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.167.000', '211.098.167.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.168.000', '211.098.172.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.173.000', '211.098.173.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.098.174.000', '211.098.174.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.175.000', '211.098.175.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.176.000', '211.098.176.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.177.000', '211.098.180.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.098.181.000', '211.098.181.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.182.000', '211.098.184.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.098.185.000', '211.098.186.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.098.187.000', '211.098.190.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.191.000', '211.098.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.098.192.000', '211.098.192.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.193.000', '211.098.193.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.194.000', '211.098.201.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.202.000', '211.098.202.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.098.203.000', '211.098.207.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.208.000', '211.098.217.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.098.218.000', '211.098.223.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.098.224.000', '211.098.224.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.098.225.000', '211.098.226.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.227.000', '211.098.227.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('211.098.228.000', '211.098.228.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.229.000', '211.098.229.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('211.098.230.000', '211.098.232.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.098.233.000', '211.098.233.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.234.000', '211.098.234.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.235.000', '211.098.235.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.098.236.000', '211.098.236.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.237.000', '211.098.237.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.098.238.000', '211.098.239.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.098.240.000', '211.098.240.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.098.241.000', '211.098.241.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.098.242.000', '211.098.243.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.098.244.000', '211.098.244.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.245.000', '211.098.247.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.248.000', '211.098.248.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.098.249.000', '211.098.249.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.250.000', '211.098.250.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.098.251.000', '211.098.251.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.098.252.000', '211.098.252.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.098.253.000', '211.098.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('211.099.000.000', '211.099.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.099.096.000', '211.099.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.099.128.000', '211.100.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.100.128.000', '211.100.129.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.100.130.000', '211.100.143.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.100.144.000', '211.100.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.100.192.000', '211.101.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.102.000.000', '211.102.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.102.096.000', '211.102.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.102.128.000', '211.102.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.103.000.000', '211.103.001.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.103.002.000', '211.103.006.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.103.007.000', '211.103.079.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.103.080.000', '211.103.095.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.103.096.000', '211.103.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.103.128.000', '211.103.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.104.000.000', '211.135.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('211.136.000.000', '211.136.011.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.136.012.000', '211.136.019.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.136.020.000', '211.136.023.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.136.024.000', '211.136.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.136.096.000', '211.136.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.136.192.000', '211.136.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.137.000.000', '211.137.047.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.137.048.000', '211.137.079.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.137.080.000', '211.137.111.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.137.112.000', '211.137.143.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('211.137.144.000', '211.137.175.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.137.176.000', '211.137.207.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.137.208.000', '211.137.223.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.137.224.000', '211.137.231.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.137.232.000', '211.137.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.138.000.000', '211.138.015.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.138.016.000', '211.138.031.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.138.032.000', '211.138.047.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.138.048.000', '211.138.063.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('211.138.064.000', '211.138.079.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('211.138.080.000', '211.138.095.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('211.138.096.000', '211.138.111.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.138.112.000', '211.138.131.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.138.132.000', '211.138.159.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.138.160.000', '211.138.175.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('211.138.176.000', '211.138.191.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.138.192.000', '211.138.207.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.138.208.000', '211.138.223.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.138.224.000', '211.138.239.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.138.240.000', '211.138.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.139.000.000', '211.139.015.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.139.016.000', '211.139.031.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.139.032.000', '211.139.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.139.064.000', '211.139.079.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('211.139.080.000', '211.139.087.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.139.088.000', '211.139.095.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.139.096.000', '211.139.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.139.128.000', '211.139.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.140.000.000', '211.140.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.140.192.000', '211.140.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.141.000.000', '211.141.007.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.141.008.000', '211.141.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.141.032.000', '211.141.079.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.141.080.000', '211.141.159.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.141.160.000', '211.141.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.142.000.000', '211.142.001.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.142.002.000', '211.142.005.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.142.006.000', '211.142.015.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.142.016.000', '211.142.095.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.142.096.000', '211.142.191.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.142.192.000', '211.143.047.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.143.048.000', '211.143.143.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.143.144.000', '211.143.223.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.143.224.000', '211.143.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.144.000.000', '211.144.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.144.128.000', '211.144.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.144.160.000', '211.144.174.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.144.175.000', '211.144.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.144.192.000', '211.144.223.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.144.224.000', '211.146.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.146.032.000', '211.146.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.146.064.000', '211.146.079.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.146.080.000', '211.146.087.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.146.088.000', '211.146.095.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.146.096.000', '211.146.103.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.146.104.000', '211.146.111.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.146.112.000', '211.146.115.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.146.116.000', '211.146.120.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('211.146.121.000', '211.146.125.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.146.126.000', '211.146.126.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.146.127.000', '211.146.127.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.146.128.000', '211.146.159.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.146.160.000', '211.146.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.146.192.000', '211.146.207.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.146.208.000', '211.146.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.146.224.000', '211.146.231.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.146.232.000', '211.146.239.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.146.240.000', '211.146.247.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.146.248.000', '211.146.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.147.000.000', '211.147.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.147.064.000', '211.147.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.147.096.000', '211.147.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.147.160.000', '211.147.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.147.192.000', '211.147.207.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.147.208.000', '211.147.223.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.147.224.000', '211.147.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.148.000.000', '211.148.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.148.064.000', '211.148.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.148.128.000', '211.148.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.148.160.000', '211.148.191.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.148.192.000', '211.148.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.148.224.000', '211.148.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.149.000.000', '211.149.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('211.150.000.000', '211.151.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.152.000.000', '211.152.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.152.064.000', '211.152.095.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.152.096.000', '211.152.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.152.128.000', '211.152.159.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.152.160.000', '211.152.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.152.192.000', '211.152.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.152.224.000', '211.152.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.153.000.000', '211.154.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.154.064.000', '211.154.095.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.154.096.000', '211.154.135.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.154.136.000', '211.154.139.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.154.140.000', '211.154.143.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.154.144.000', '211.154.155.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.154.156.000', '211.154.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.154.160.000', '211.154.175.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.154.176.000', '211.154.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.154.192.000', '211.154.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.155.000.000', '211.155.015.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.155.016.000', '211.155.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.155.032.000', '211.155.097.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.155.098.000', '211.155.099.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('211.155.100.000', '211.155.111.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.155.112.000', '211.155.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.155.128.000', '211.155.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.155.192.000', '211.155.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.155.224.000', '211.155.239.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('211.155.240.000', '211.155.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.156.000.000', '211.156.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.156.032.000', '211.156.047.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.156.048.000', '211.156.063.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.156.064.000', '211.156.079.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.156.080.000', '211.156.095.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.156.096.000', '211.156.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.156.128.000', '211.156.171.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.156.172.000', '211.156.175.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.156.176.000', '211.156.179.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.156.180.000', '211.156.181.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.156.182.000', '211.156.190.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.156.191.000', '211.156.191.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.156.192.000', '211.156.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.156.224.000', '211.156.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.157.000.000', '211.157.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.158.000.000', '211.158.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.159.000.000', '211.159.079.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.159.080.000', '211.160.139.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.160.140.000', '211.160.143.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('211.160.144.000', '211.160.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.160.160.000', '211.160.171.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.160.172.000', '211.160.173.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.160.174.000', '211.160.175.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.160.176.000', '211.160.177.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.160.178.000', '211.160.179.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('211.160.180.000', '211.160.183.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.160.184.000', '211.160.203.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.160.204.000', '211.160.207.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('211.160.208.000', '211.160.241.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.160.242.000', '211.160.242.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('211.160.243.000', '211.161.015.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.161.016.000', '211.161.019.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('211.161.020.000', '211.161.027.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.161.028.000', '211.161.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.161.048.000', '211.161.051.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('211.161.052.000', '211.161.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.161.064.000', '211.161.079.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('211.161.080.000', '211.161.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.161.096.000', '211.161.111.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.161.112.000', '211.161.115.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.161.116.000', '211.161.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.161.128.000', '211.161.143.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('211.161.144.000', '211.161.191.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.161.192.000', '211.161.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.162.000.000', '211.162.015.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('211.162.016.000', '211.162.031.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('211.162.032.000', '211.162.039.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.040.000', '211.162.047.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.048.000', '211.162.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.162.128.000', '211.162.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.162.192.000', '211.162.195.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.162.196.000', '211.162.199.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.200.000', '211.162.207.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.162.208.000', '211.162.215.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('211.162.216.000', '211.162.223.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.162.224.000', '211.162.228.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.162.229.000', '211.162.229.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.230.000', '211.162.231.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('211.162.232.000', '211.162.232.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.162.233.000', '211.162.233.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.234.000', '211.162.235.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('211.162.236.000', '211.162.239.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('211.162.240.000', '211.162.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.163.000.000', '211.163.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.164.000.000', '211.165.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.165.128.000', '211.165.130.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('211.165.131.000', '211.165.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.165.192.000', '211.165.192.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('211.165.193.000', '211.165.199.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.165.200.000', '211.165.201.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('211.165.202.000', '211.165.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('211.166.000.000', '211.166.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.167.000.000', '211.167.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('211.167.032.000', '211.167.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.167.096.000', '211.167.159.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('211.167.160.000', '211.167.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('211.168.000.000', '217.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.000.000.000', '218.000.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.001.000.000', '218.001.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.002.000.000', '218.004.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.005.000.000', '218.006.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.006.128.000', '218.006.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.007.000.000', '218.010.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('218.011.000.000', '218.012.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('218.013.000.000', '218.020.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.021.000.000', '218.021.047.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('218.021.048.000', '218.021.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.021.064.000', '218.021.127.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('218.021.128.000', '218.021.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('218.022.000.000', '218.023.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('218.024.000.000', '218.025.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('218.026.000.000', '218.026.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('218.027.000.000', '218.027.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('218.028.000.000', '218.029.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('218.030.000.000', '218.030.014.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.030.015.000', '218.030.024.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.030.025.000', '218.030.029.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.030.030.000', '218.030.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.030.032.000', '218.030.055.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.030.056.000', '218.030.063.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.030.064.000', '218.030.087.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.030.088.000', '218.030.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.030.096.000', '218.030.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.030.128.000', '218.030.128.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.030.129.000', '218.030.129.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.030.130.000', '218.030.136.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.030.137.000', '218.030.138.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.030.139.000', '218.030.145.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.030.146.000', '218.030.157.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.030.158.000', '218.030.159.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.030.160.000', '218.030.160.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.030.161.000', '218.030.164.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.030.165.000', '218.030.166.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.030.167.000', '218.030.172.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('218.030.173.000', '218.030.174.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('218.030.175.000', '218.030.175.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('218.030.176.000', '218.030.178.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.030.179.000', '218.030.188.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.030.189.000', '218.030.221.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('218.030.222.000', '218.030.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('218.031.000.000', '218.031.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('218.032.000.000', '218.032.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.033.000.000', '218.033.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.034.000.000', '218.035.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.036.000.000', '218.055.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.056.000.000', '218.059.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.060.000.000', '218.061.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('218.062.000.000', '218.062.127.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('218.062.128.000', '218.063.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.064.000.000', '218.065.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('218.065.128.000', '218.065.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('218.066.000.000', '218.067.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.067.128.000', '218.069.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('218.070.000.000', '218.070.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.071.000.000', '218.075.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.075.128.000', '218.077.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.077.128.000', '218.077.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('218.078.000.000', '218.083.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.084.000.000', '218.084.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('218.085.000.000', '218.086.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.086.128.000', '218.086.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('218.087.000.000', '218.087.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('218.088.000.000', '218.089.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.090.000.000', '218.094.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.095.000.000', '218.095.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('218.095.128.000', '218.095.223.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('218.095.224.000', '218.095.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('218.096.000.000', '218.097.175.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.097.176.000', '218.097.183.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.097.184.000', '218.097.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.097.192.000', '218.097.223.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('218.097.224.000', '218.097.239.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.097.240.000', '218.097.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.098.000.000', '218.098.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.099.000.000', '218.099.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.100.000.000', '218.100.015.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.100.016.000', '218.100.017.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.100.018.000', '218.101.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.101.128.000', '218.101.255.255', '韩国');
INSERT INTO eq_ipdatabase VALUES ('218.102.000.000', '218.103.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.104.000.000', '218.104.015.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.104.016.000', '218.104.031.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('218.104.032.000', '218.104.063.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.104.064.000', '218.104.079.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('218.104.080.000', '218.104.095.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.104.096.000', '218.104.111.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.104.112.000', '218.104.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.104.128.000', '218.104.143.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.104.144.000', '218.104.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.104.160.000', '218.104.199.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.104.200.000', '218.104.207.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.104.208.000', '218.104.215.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.104.216.000', '218.104.223.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.104.224.000', '218.104.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.105.000.000', '218.105.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.106.000.000', '218.106.079.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.106.080.000', '218.106.095.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('218.106.096.000', '218.106.103.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.106.104.000', '218.106.111.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.106.112.000', '218.106.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.106.128.000', '218.106.135.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.106.136.000', '218.106.139.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.106.140.000', '218.106.159.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.106.160.000', '218.106.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.106.192.000', '218.106.207.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('218.106.208.000', '218.106.223.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.106.224.000', '218.106.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.107.000.000', '218.107.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.107.064.000', '218.107.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.107.128.000', '218.107.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.107.192.000', '218.107.223.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.107.224.000', '218.107.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.108.000.000', '218.109.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.110.000.000', '218.159.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.160.000.000', '218.175.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.176.000.000', '218.183.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.184.000.000', '218.184.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.185.000.000', '218.185.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.185.192.000', '218.185.223.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('218.185.224.000', '218.186.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.187.000.000', '218.187.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.188.000.000', '218.191.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.192.000.000', '218.192.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.193.000.000', '218.193.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.193.128.000', '218.193.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.193.192.000', '218.193.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('218.194.000.000', '218.194.095.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.194.096.000', '218.194.127.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.194.128.000', '218.194.191.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.194.192.000', '218.194.223.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.194.224.000', '218.194.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('218.195.000.000', '218.195.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.195.128.000', '218.195.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('218.196.000.000', '218.196.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.196.160.000', '218.196.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('218.197.000.000', '218.197.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.198.000.000', '218.198.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('218.199.000.000', '218.199.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.200.000.000', '218.200.063.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.200.064.000', '218.200.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('218.200.160.000', '218.200.239.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.200.240.000', '218.200.243.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.200.244.000', '218.200.250.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.200.251.000', '218.200.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.201.000.000', '218.201.095.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.201.096.000', '218.201.191.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.201.192.000', '218.201.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('218.202.000.000', '218.202.063.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.202.064.000', '218.202.142.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('218.202.143.000', '218.202.223.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('218.202.224.000', '218.202.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.203.000.000', '218.203.007.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('218.203.008.000', '218.203.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.203.096.000', '218.203.159.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('218.203.160.000', '218.203.223.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('218.203.224.000', '218.203.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.204.000.000', '218.204.063.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('218.204.064.000', '218.204.159.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('218.204.160.000', '218.204.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.205.000.000', '218.205.047.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.205.048.000', '218.205.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('218.205.128.000', '218.205.223.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.205.224.000', '218.205.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.206.000.000', '218.206.031.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.206.032.000', '218.206.063.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('218.206.064.000', '218.206.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.206.096.000', '218.206.159.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.206.160.000', '218.206.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.206.192.000', '218.206.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('218.207.000.000', '218.207.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.207.064.000', '218.207.067.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('218.207.068.000', '218.207.095.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.207.096.000', '218.207.102.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.207.103.000', '218.207.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.208.000.000', '218.209.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.210.000.000', '218.211.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('218.212.000.000', '218.212.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.213.000.000', '218.213.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.214.000.000', '218.239.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.240.000.000', '218.240.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.240.064.000', '218.240.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.240.224.000', '218.240.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('218.241.000.000', '218.241.031.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.241.032.000', '218.241.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.241.064.000', '218.241.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.242.000.000', '218.242.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.243.000.000', '218.243.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.244.000.000', '218.244.031.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.244.032.000', '218.244.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.244.064.000', '218.244.095.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('218.244.096.000', '218.244.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.244.128.000', '218.244.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.244.160.000', '218.244.175.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('218.244.176.000', '218.244.191.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.244.192.000', '218.244.207.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('218.244.208.000', '218.246.035.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.246.036.000', '218.246.047.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('218.246.048.000', '218.246.063.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('218.246.064.000', '218.246.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.246.128.000', '218.246.143.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('218.246.144.000', '218.246.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.246.160.000', '218.246.191.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('218.246.192.000', '218.246.223.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('218.246.224.000', '218.246.237.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('218.246.238.000', '218.246.239.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('218.246.240.000', '218.246.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('218.247.000.000', '218.247.031.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.247.032.000', '218.247.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('218.247.064.000', '218.247.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.248.000.000', '218.248.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.249.000.000', '218.249.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('218.250.000.000', '218.250.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('218.251.000.000', '218.251.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('218.252.000.000', '218.255.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('219.000.000.000', '219.067.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.068.000.000', '219.071.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('219.072.000.000', '219.072.002.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.072.003.000', '219.072.059.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.072.060.000', '219.072.060.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('219.072.061.000', '219.072.061.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.072.062.000', '219.072.062.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('219.072.063.000', '219.072.065.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.072.066.000', '219.072.197.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.072.198.000', '219.072.199.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('219.072.200.000', '219.072.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.072.224.000', '219.072.239.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('219.072.240.000', '219.072.251.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.072.252.000', '219.072.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.073.000.000', '219.073.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('219.073.128.000', '219.075.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.076.000.000', '219.079.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('219.080.000.000', '219.081.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('219.082.000.000', '219.082.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('219.083.000.000', '219.083.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.084.000.000', '219.087.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('219.088.000.000', '219.089.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.090.000.000', '219.090.063.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('219.090.064.000', '219.090.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.090.112.000', '219.090.127.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('219.090.128.000', '219.090.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.091.000.000', '219.091.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('219.091.128.000', '219.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.128.000.000', '219.137.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.138.000.000', '219.140.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('219.141.000.000', '219.141.127.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('219.141.128.000', '219.143.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.144.000.000', '219.145.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('219.146.000.000', '219.147.031.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('219.147.032.000', '219.147.047.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.147.048.000', '219.147.063.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('219.147.064.000', '219.147.095.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('219.147.096.000', '219.147.127.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('219.147.128.000', '219.147.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('219.148.000.000', '219.148.159.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.148.160.000', '219.148.191.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('219.148.192.000', '219.149.127.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('219.149.128.000', '219.149.191.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.149.192.000', '219.150.031.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('219.150.032.000', '219.150.111.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('219.150.112.000', '219.150.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('219.151.000.000', '219.151.031.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('219.151.032.000', '219.151.063.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('219.151.064.000', '219.151.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.151.128.000', '219.153.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('219.154.000.000', '219.157.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('219.158.000.000', '219.158.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('219.158.128.000', '219.158.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.159.000.000', '219.159.063.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('219.159.064.000', '219.159.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('219.160.000.000', '219.215.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.216.000.000', '219.216.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('219.217.000.000', '219.217.127.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('219.217.128.000', '219.217.191.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('219.217.192.000', '219.217.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('219.218.000.000', '219.218.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('219.219.000.000', '219.219.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('219.220.000.000', '219.220.095.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('219.220.096.000', '219.220.127.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('219.220.128.000', '219.220.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('219.221.000.000', '219.221.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('219.221.128.000', '219.221.239.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('219.221.240.000', '219.221.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('219.222.000.000', '219.223.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.224.000.000', '219.225.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.225.128.000', '219.225.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('219.226.000.000', '219.226.015.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('219.226.016.000', '219.226.071.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.226.072.000', '219.226.127.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.226.128.000', '219.226.183.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.226.184.000', '219.226.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.226.192.000', '219.226.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.227.000.000', '219.227.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.228.000.000', '219.228.191.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('219.228.192.000', '219.228.223.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('219.228.224.000', '219.229.191.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('219.229.192.000', '219.229.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('219.230.000.000', '219.230.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('219.231.000.000', '219.231.127.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('219.231.128.000', '219.231.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('219.232.000.000', '219.232.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.232.064.000', '219.232.095.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('219.232.096.000', '219.232.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.232.128.000', '219.232.159.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('219.232.160.000', '219.232.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.232.192.000', '219.232.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.233.000.000', '219.233.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('219.234.000.000', '219.234.031.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.234.032.000', '219.234.047.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('219.234.048.000', '219.234.063.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('219.234.064.000', '219.234.079.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('219.234.080.000', '219.234.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.234.096.000', '219.234.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.234.128.000', '219.234.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.235.000.000', '219.235.015.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('219.235.016.000', '219.235.031.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.235.032.000', '219.235.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.235.064.000', '219.235.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('219.235.128.000', '219.235.207.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.235.208.000', '219.235.223.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.235.224.000', '219.239.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.240.000.000', '219.241.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('219.242.000.000', '219.242.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.243.000.000', '219.243.063.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('219.243.064.000', '219.243.079.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.243.080.000', '219.243.095.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('219.243.096.000', '219.243.127.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.243.128.000', '219.243.191.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.243.192.000', '219.243.224.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('219.243.225.000', '219.243.225.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('219.243.226.000', '219.243.226.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('219.243.227.000', '219.243.227.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('219.243.228.000', '219.243.228.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('219.243.229.000', '219.243.229.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('219.243.230.000', '219.243.230.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('219.243.231.000', '219.243.231.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('219.243.232.000', '219.243.232.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('219.243.233.000', '219.243.233.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('219.243.234.000', '219.243.234.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('219.243.235.000', '219.243.235.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('219.243.236.000', '219.243.236.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('219.243.237.000', '219.243.237.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('219.243.238.000', '219.243.238.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('219.243.239.000', '219.243.239.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.243.240.000', '219.243.240.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('219.243.241.000', '219.243.241.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('219.243.242.000', '219.243.242.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('219.243.243.000', '219.243.244.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('219.243.245.000', '219.243.245.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('219.243.246.000', '219.243.246.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('219.243.247.000', '219.243.247.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('219.243.248.000', '219.243.248.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('219.243.249.000', '219.243.249.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('219.243.250.000', '219.243.250.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('219.243.251.000', '219.243.251.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('219.243.252.000', '219.243.252.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('219.243.253.000', '219.243.253.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('219.243.254.000', '219.243.254.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('219.243.255.000', '219.243.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('219.244.000.000', '219.245.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('219.246.000.000', '219.246.255.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('219.247.000.000', '219.247.127.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('219.247.128.000', '219.247.191.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('219.247.192.000', '219.247.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('219.248.000.000', '220.101.191.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.101.192.000', '220.101.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.102.000.000', '220.111.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.112.000.000', '220.112.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.112.064.000', '220.112.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.112.128.000', '220.112.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.112.192.000', '220.112.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.112.208.000', '220.112.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.113.000.000', '220.113.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.113.048.000', '220.113.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.113.064.000', '220.113.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.113.128.000', '220.113.159.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('220.113.160.000', '220.113.223.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.113.224.000', '220.113.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.114.000.000', '220.114.031.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('220.114.032.000', '220.114.047.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.114.048.000', '220.114.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.114.064.000', '220.114.095.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.114.096.000', '220.114.127.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('220.114.128.000', '220.114.151.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.114.152.000', '220.114.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.114.192.000', '220.114.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.114.208.000', '220.114.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.114.224.000', '220.114.231.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.114.232.000', '220.114.243.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.114.244.000', '220.114.247.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('220.114.248.000', '220.114.249.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.114.250.000', '220.114.251.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.114.252.000', '220.114.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.115.000.000', '220.115.007.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.115.008.000', '220.115.015.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.115.016.000', '220.115.023.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('220.115.024.000', '220.115.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.115.032.000', '220.115.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.115.064.000', '220.115.095.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.115.096.000', '220.115.111.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.115.112.000', '220.115.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.115.128.000', '220.115.137.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.115.138.000', '220.115.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.115.160.000', '220.115.167.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.115.168.000', '220.115.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.115.192.000', '220.115.223.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.115.224.000', '220.115.227.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.115.228.000', '220.115.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.116.000.000', '220.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.128.000.000', '220.143.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('220.144.000.000', '220.152.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.152.128.000', '220.152.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.153.000.000', '220.153.255.255', '日本未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.154.000.000', '220.155.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.156.000.000', '220.157.111.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.157.112.000', '220.157.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('220.157.128.000', '220.159.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.160.000.000', '220.162.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.163.000.000', '220.165.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('220.166.000.000', '220.167.127.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.167.128.000', '220.167.255.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('220.168.000.000', '220.170.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.171.000.000', '220.171.191.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('220.171.192.000', '220.172.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('220.173.000.000', '220.173.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('220.174.000.000', '220.174.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('220.175.000.000', '220.177.255.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.178.000.000', '220.180.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.181.000.000', '220.181.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.182.000.000', '220.182.063.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('220.182.064.000', '220.183.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.184.000.000', '220.191.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.192.000.000', '220.192.007.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.192.008.000', '220.192.015.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.192.016.000', '220.192.019.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('220.192.020.000', '220.192.023.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('220.192.024.000', '220.192.027.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('220.192.028.000', '220.192.031.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('220.192.032.000', '220.192.063.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.192.064.000', '220.192.071.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.192.072.000', '220.192.079.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('220.192.080.000', '220.192.095.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.192.096.000', '220.192.111.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('220.192.112.000', '220.192.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.192.128.000', '220.192.135.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.192.136.000', '220.192.143.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.192.144.000', '220.192.151.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.192.152.000', '220.192.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.192.160.000', '220.192.167.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.192.168.000', '220.192.175.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.192.176.000', '220.192.179.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('220.192.180.000', '220.192.183.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('220.192.184.000', '220.192.187.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('220.192.188.000', '220.192.191.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('220.192.192.000', '220.192.199.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('220.192.200.000', '220.192.203.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('220.192.204.000', '220.192.205.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('220.192.206.000', '220.192.207.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('220.192.208.000', '220.192.215.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('220.192.216.000', '220.192.223.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('220.192.224.000', '220.192.239.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('220.192.240.000', '220.192.247.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('220.192.248.000', '220.192.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.193.000.000', '220.193.005.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.193.006.000', '220.193.007.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.193.008.000', '220.193.011.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.193.012.000', '220.193.031.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.193.032.000', '220.193.032.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.193.033.000', '220.193.039.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.193.040.000', '220.193.051.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.193.052.000', '220.193.092.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.193.093.000', '220.193.093.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.193.094.000', '220.193.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.194.000.000', '220.194.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.194.064.000', '220.194.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('220.194.128.000', '220.194.191.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('220.194.192.000', '220.195.031.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('220.195.032.000', '220.195.047.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('220.195.048.000', '220.195.071.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.195.072.000', '220.195.079.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.195.080.000', '220.195.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.195.128.000', '220.195.159.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.195.160.000', '220.195.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.196.000.000', '220.196.063.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.196.064.000', '220.196.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.196.128.000', '220.197.001.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('220.197.002.000', '220.197.072.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.197.073.000', '220.197.167.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.197.168.000', '220.197.175.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.197.176.000', '220.197.223.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('220.197.224.000', '220.197.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('220.198.000.000', '220.199.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.200.000.000', '220.200.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.200.064.000', '220.200.095.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('220.200.096.000', '220.200.111.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('220.200.112.000', '220.200.135.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('220.200.136.000', '220.200.159.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('220.200.160.000', '220.200.191.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('220.200.192.000', '220.200.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('220.201.000.000', '220.201.143.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('220.201.144.000', '220.201.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('220.201.192.000', '220.201.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('220.202.000.000', '220.202.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.202.064.000', '220.202.095.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.202.096.000', '220.202.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.203.000.000', '220.203.039.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.203.040.000', '220.203.063.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.203.064.000', '220.204.107.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.204.108.000', '220.204.108.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.204.109.000', '220.204.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.205.000.000', '220.205.031.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.205.032.000', '220.205.039.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.205.040.000', '220.205.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.205.064.000', '220.205.073.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('220.205.074.000', '220.205.077.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('220.205.078.000', '220.205.081.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('220.205.082.000', '220.205.085.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('220.205.086.000', '220.205.089.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('220.205.090.000', '220.205.103.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.205.104.000', '220.205.109.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.205.110.000', '220.205.113.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.205.114.000', '220.205.115.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('220.205.116.000', '220.205.119.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.205.120.000', '220.205.123.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.205.124.000', '220.205.135.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.205.136.000', '220.205.159.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.205.160.000', '220.205.171.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.205.172.000', '220.205.175.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.205.176.000', '220.205.183.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.205.184.000', '220.205.207.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.205.208.000', '220.205.208.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.205.209.000', '220.205.223.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.205.224.000', '220.205.231.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.205.232.000', '220.206.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.207.000.000', '220.207.003.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.207.004.000', '220.207.007.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('220.207.008.000', '220.207.023.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.207.024.000', '220.207.055.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.207.056.000', '220.207.067.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.207.068.000', '220.207.075.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.207.076.000', '220.207.079.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.207.080.000', '220.207.095.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.207.096.000', '220.207.111.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('220.207.112.000', '220.207.127.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('220.207.128.000', '220.207.131.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('220.207.132.000', '220.207.135.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('220.207.136.000', '220.207.139.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.207.140.000', '220.207.147.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('220.207.148.000', '220.207.151.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.207.152.000', '220.207.159.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.207.160.000', '220.207.167.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.207.168.000', '220.207.175.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.207.176.000', '220.207.179.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.207.180.000', '220.207.181.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('220.207.182.000', '220.207.183.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('220.207.184.000', '220.207.187.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('220.207.188.000', '220.207.191.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('220.207.192.000', '220.207.207.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('220.207.208.000', '220.207.211.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('220.207.212.000', '220.207.219.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.207.220.000', '220.207.227.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.207.228.000', '220.207.232.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('220.207.233.000', '220.207.251.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.207.252.000', '220.207.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('220.208.000.000', '220.227.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.228.000.000', '220.229.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('220.230.000.000', '220.230.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.231.000.000', '220.231.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.231.064.000', '220.231.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.231.128.000', '220.231.159.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.231.160.000', '220.231.167.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.231.168.000', '220.231.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.232.000.000', '220.232.063.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.232.064.000', '220.232.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.232.128.000', '220.232.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('220.233.000.000', '220.233.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.234.000.000', '220.234.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.235.000.000', '220.240.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.241.000.000', '220.241.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('220.242.000.000', '220.242.003.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.242.004.000', '220.242.007.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.242.008.000', '220.242.079.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.242.080.000', '220.242.080.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.242.081.000', '220.243.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.244.000.000', '220.245.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.246.000.000', '220.246.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('220.247.000.000', '220.247.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.247.128.000', '220.247.159.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('220.247.160.000', '220.247.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('220.248.000.000', '220.248.127.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('220.248.128.000', '220.248.159.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('220.248.160.000', '220.248.191.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.248.192.000', '220.248.223.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('220.248.224.000', '220.248.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('220.249.000.000', '220.249.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('220.249.064.000', '220.249.127.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('220.249.128.000', '220.249.191.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.249.192.000', '220.249.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('220.250.000.000', '220.250.031.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('220.250.032.000', '220.252.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('220.253.000.000', '220.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.000.000.000', '221.003.127.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('221.003.128.000', '221.003.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('221.004.000.000', '221.005.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('221.005.128.000', '221.005.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('221.006.000.000', '221.006.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.007.000.000', '221.007.031.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('221.007.032.000', '221.007.063.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('221.007.064.000', '221.007.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('221.007.128.000', '221.007.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('221.008.000.000', '221.009.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('221.010.000.000', '221.010.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('221.011.000.000', '221.011.127.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('221.011.128.000', '221.011.223.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('221.011.224.000', '221.011.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('221.012.000.000', '221.012.189.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('221.012.190.000', '221.012.190.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('221.012.191.000', '221.012.191.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('221.012.192.000', '221.012.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.013.000.000', '221.013.063.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('221.013.064.000', '221.013.095.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('221.013.096.000', '221.013.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('221.013.128.000', '221.015.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.016.000.000', '221.119.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.120.000.000', '221.120.095.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('221.120.096.000', '221.120.175.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.120.176.000', '221.120.191.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('221.120.192.000', '221.121.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.122.000.000', '221.122.127.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.122.128.000', '221.122.191.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('221.122.192.000', '221.123.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.123.064.000', '221.123.079.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('221.123.080.000', '221.123.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.124.000.000', '221.127.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('221.128.000.000', '221.128.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.129.000.000', '221.129.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.130.000.000', '221.130.031.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.130.032.000', '221.130.047.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.130.048.000', '221.130.111.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.130.112.000', '221.130.112.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('221.130.113.000', '221.130.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.130.176.000', '221.130.207.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('221.130.208.000', '221.130.251.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.130.252.000', '221.130.252.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('221.130.253.000', '221.130.253.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.130.254.000', '221.131.035.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.131.036.000', '221.131.038.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('221.131.039.000', '221.131.039.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.131.040.000', '221.131.048.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('221.131.049.000', '221.131.057.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.131.058.000', '221.131.058.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('221.131.059.000', '221.131.059.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.131.060.000', '221.131.061.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('221.131.062.000', '221.131.063.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.131.064.000', '221.131.191.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.131.192.000', '221.131.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.132.000.000', '221.133.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.133.224.000', '221.133.235.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.133.236.000', '221.133.236.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('221.133.237.000', '221.133.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.134.000.000', '221.135.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.136.000.000', '221.136.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('221.137.000.000', '221.137.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('221.138.000.000', '221.168.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.169.000.000', '221.169.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('221.170.000.000', '221.171.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.172.000.000', '221.172.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('221.172.128.000', '221.172.131.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.172.132.000', '221.172.139.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('221.172.140.000', '221.172.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.173.000.000', '221.173.023.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('221.173.024.000', '221.173.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.173.128.000', '221.173.139.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('221.173.140.000', '221.174.015.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.174.016.000', '221.174.019.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('221.174.020.000', '221.174.127.255', '中国中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.174.128.000', '221.174.191.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.174.192.000', '221.175.039.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.175.040.000', '221.175.049.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('221.175.050.000', '221.175.055.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.175.056.000', '221.175.063.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('221.175.064.000', '221.175.103.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.175.104.000', '221.175.107.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('221.175.108.000', '221.175.115.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.175.116.000', '221.175.120.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('221.175.121.000', '221.176.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.176.128.000', '221.176.183.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.176.184.000', '221.176.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.176.192.000', '221.176.199.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.176.200.000', '221.176.207.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.176.208.000', '221.176.215.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.176.216.000', '221.176.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.176.224.000', '221.176.229.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.176.230.000', '221.176.231.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.176.232.000', '221.176.237.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('221.176.238.000', '221.177.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.178.000.000', '221.178.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('221.178.128.000', '221.183.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.184.000.000', '221.191.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.192.000.000', '221.195.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('221.196.000.000', '221.198.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.199.000.000', '221.199.127.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('221.199.128.000', '221.199.207.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('221.199.208.000', '221.199.223.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('221.199.224.000', '221.199.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('221.200.000.000', '221.203.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('221.204.000.000', '221.205.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('221.206.000.000', '221.206.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('221.207.000.000', '221.207.063.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('221.207.064.000', '221.212.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('221.213.000.000', '221.213.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('221.214.000.000', '221.215.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('221.216.000.000', '221.223.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('221.224.000.000', '221.231.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('221.232.000.000', '221.235.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('221.236.000.000', '221.237.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('221.238.000.000', '221.238.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.238.128.000', '221.238.128.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.238.129.000', '221.238.151.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.238.152.000', '221.238.152.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('221.238.153.000', '221.238.175.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.238.176.000', '221.238.179.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('221.238.180.000', '221.239.127.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('221.239.128.000', '221.239.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('221.240.000.000', '222.015.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.016.000.000', '222.017.127.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.017.128.000', '222.017.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.017.176.000', '222.017.255.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('222.018.000.000', '222.019.063.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('222.019.064.000', '222.019.111.255', '西藏');
INSERT INTO eq_ipdatabase VALUES ('222.019.112.000', '222.019.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.019.128.000', '222.019.191.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('222.019.192.000', '222.019.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.020.000.000', '222.020.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.021.000.000', '222.022.223.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.022.224.000', '222.022.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.023.000.000', '222.023.191.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('222.023.192.000', '222.023.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('222.024.000.000', '222.025.191.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('222.025.192.000', '222.025.203.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('222.025.204.000', '222.025.223.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('222.025.224.000', '222.025.255.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('222.026.000.000', '222.026.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('222.027.000.000', '222.027.127.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('222.027.128.000', '222.027.130.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.027.131.000', '222.027.159.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('222.027.160.000', '222.027.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.028.000.000', '222.029.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.030.000.000', '222.030.135.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('222.030.136.000', '222.030.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('222.031.000.000', '222.031.063.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('222.031.064.000', '222.031.191.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.031.192.000', '222.031.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.032.000.000', '222.032.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.033.000.000', '222.033.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('222.034.000.000', '222.034.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('222.035.000.000', '222.035.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.036.000.000', '222.036.255.255', '天津市');
INSERT INTO eq_ipdatabase VALUES ('222.037.000.000', '222.037.255.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('222.038.000.000', '222.038.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('222.039.000.000', '222.039.136.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.039.137.000', '222.039.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.039.144.000', '222.039.159.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.039.160.000', '222.039.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.039.192.000', '222.039.203.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.039.204.000', '222.039.207.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.039.208.000', '222.039.211.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.039.212.000', '222.039.223.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.039.224.000', '222.039.233.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.039.234.000', '222.039.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.040.000.000', '222.040.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.041.000.000', '222.041.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('222.042.000.000', '222.042.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.043.000.000', '222.043.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.044.000.000', '222.044.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('222.045.000.000', '222.045.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.046.000.000', '222.046.255.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.047.000.000', '222.047.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('222.048.000.000', '222.048.255.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('222.049.000.000', '222.049.127.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('222.049.128.000', '222.049.151.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.049.152.000', '222.049.159.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.049.160.000', '222.049.207.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.049.208.000', '222.049.215.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.049.216.000', '222.049.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.050.000.000', '222.050.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.051.000.000', '222.051.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.052.000.000', '222.052.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.053.000.000', '222.053.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('222.054.000.000', '222.054.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('222.055.000.000', '222.055.127.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.055.128.000', '222.055.215.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.055.216.000', '222.055.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.056.000.000', '222.056.127.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.056.128.000', '222.056.191.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.056.192.000', '222.056.247.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.056.248.000', '222.056.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.057.000.000', '222.057.127.255', '甘肃省');
INSERT INTO eq_ipdatabase VALUES ('222.057.128.000', '222.057.255.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.058.000.000', '222.058.127.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('222.058.128.000', '222.058.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.059.000.000', '222.059.127.255', '青海省');
INSERT INTO eq_ipdatabase VALUES ('222.059.128.000', '222.059.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.060.000.000', '222.060.127.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('222.060.128.000', '222.060.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.061.000.000', '222.061.127.255', '海南省');
INSERT INTO eq_ipdatabase VALUES ('222.061.128.000', '222.061.135.255', '中国浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.061.136.000', '222.061.143.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.061.144.000', '222.061.159.255', '中国浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.061.160.000', '222.061.175.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.061.176.000', '222.061.183.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.061.184.000', '222.061.191.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.061.192.000', '222.061.227.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.061.228.000', '222.062.127.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.062.128.000', '222.063.255.255', '辽宁省');
INSERT INTO eq_ipdatabase VALUES ('222.064.000.000', '222.073.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('222.074.000.000', '222.074.255.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.075.000.000', '222.075.255.255', '宁夏');
INSERT INTO eq_ipdatabase VALUES ('222.076.000.000', '222.079.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('222.080.000.000', '222.083.127.255', '新疆');
INSERT INTO eq_ipdatabase VALUES ('222.083.128.000', '222.084.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.085.000.000', '222.087.255.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('222.088.000.000', '222.089.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.090.000.000', '222.091.255.255', '陕西省');
INSERT INTO eq_ipdatabase VALUES ('222.092.000.000', '222.095.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.096.000.000', '222.124.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.125.000.000', '222.125.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.126.000.000', '222.126.127.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.126.128.000', '222.126.255.255', '中国未明地区');
INSERT INTO eq_ipdatabase VALUES ('222.127.000.000', '222.127.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.128.000.000', '222.131.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.132.000.000', '222.135.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.136.000.000', '222.143.255.255', '河南省');
INSERT INTO eq_ipdatabase VALUES ('222.144.000.000', '222.155.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.156.000.000', '222.157.255.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('222.158.000.000', '222.159.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.160.000.000', '222.163.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('222.164.000.000', '222.165.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.166.000.000', '222.167.255.255', '香港');
INSERT INTO eq_ipdatabase VALUES ('222.168.000.000', '222.169.255.255', '吉林省');
INSERT INTO eq_ipdatabase VALUES ('222.170.000.000', '222.172.127.255', '黑龙江省');
INSERT INTO eq_ipdatabase VALUES ('222.172.128.000', '222.172.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.173.000.000', '222.175.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.176.000.000', '222.183.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.184.000.000', '222.193.255.255', '江苏省');
INSERT INTO eq_ipdatabase VALUES ('222.194.000.000', '222.194.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.195.000.000', '222.195.127.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('222.195.128.000', '222.195.167.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.195.168.000', '222.195.175.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('222.195.176.000', '222.195.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.196.000.000', '222.197.191.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('222.197.192.000', '222.197.242.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.197.243.000', '222.197.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('222.198.000.000', '222.198.191.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.198.192.000', '222.198.251.255', '贵州省');
INSERT INTO eq_ipdatabase VALUES ('222.198.252.000', '222.198.255.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.199.000.000', '222.199.048.255', '山西省');
INSERT INTO eq_ipdatabase VALUES ('222.199.049.000', '222.199.063.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.199.064.000', '222.199.099.255', '内蒙古');
INSERT INTO eq_ipdatabase VALUES ('222.199.100.000', '222.199.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.200.000.000', '222.202.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.203.000.000', '222.203.063.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.203.064.000', '222.203.100.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.203.101.000', '222.203.127.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.203.128.000', '222.203.173.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.203.174.000', '222.203.175.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.203.176.000', '222.203.191.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.203.192.000', '222.203.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.204.000.000', '222.204.159.255', '江西省');
INSERT INTO eq_ipdatabase VALUES ('222.204.160.000', '222.204.255.255', '上海市');
INSERT INTO eq_ipdatabase VALUES ('222.205.000.000', '222.205.159.255', '浙江省');
INSERT INTO eq_ipdatabase VALUES ('222.205.160.000', '222.205.255.255', '福建省');
INSERT INTO eq_ipdatabase VALUES ('222.206.000.000', '222.206.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.207.000.000', '222.207.127.255', '安徽省');
INSERT INTO eq_ipdatabase VALUES ('222.207.128.000', '222.207.255.255', '山东省');
INSERT INTO eq_ipdatabase VALUES ('222.208.000.000', '222.215.255.255', '四川省');
INSERT INTO eq_ipdatabase VALUES ('222.216.000.000', '222.218.255.255', '广西');
INSERT INTO eq_ipdatabase VALUES ('222.219.000.000', '222.221.255.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.222.000.000', '222.223.052.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('222.223.053.000', '222.223.054.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.223.055.000', '222.223.207.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('222.223.208.000', '222.223.211.255', '重庆市');
INSERT INTO eq_ipdatabase VALUES ('222.223.212.000', '222.223.223.255', '云南省');
INSERT INTO eq_ipdatabase VALUES ('222.223.224.000', '222.223.255.255', '河北省');
INSERT INTO eq_ipdatabase VALUES ('222.224.000.000', '222.239.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('222.240.000.000', '222.247.255.255', '湖北省');
INSERT INTO eq_ipdatabase VALUES ('222.248.000.000', '222.248.255.255', '广东省');
INSERT INTO eq_ipdatabase VALUES ('222.249.000.000', '222.249.255.255', '北京市');
INSERT INTO eq_ipdatabase VALUES ('222.250.000.000', '222.251.127.255', '台湾省');
INSERT INTO eq_ipdatabase VALUES ('222.251.128.000', '222.255.255.255', '国外');
INSERT INTO eq_ipdatabase VALUES ('223.000.000.000', '255.255.255.255', 'IANA');

#
# 表的结构 `eq_mail_list`
#

CREATE TABLE IF NOT EXISTS eq_mail_list (
  maillistID int(30) unsigned not null auto_increment,
  mailTitle varchar(255) binary not null default '',
  sendMailName text not null,
  sendFailName text not null,
  sendMailContent text not null,
  administratorsID int(30) unsigned not null default '0',
  mailType int(1) unsigned not null default '0',
  createDate int(11) unsigned not null default '0',
  PRIMARY KEY  (maillistID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_option`
#

CREATE TABLE IF NOT EXISTS eq_option (
  optionID int(4) unsigned not null auto_increment,
  optionCate varchar(50) binary not null default '',
  optionName text not null,
  administratorsID int(30) unsigned not null default '0',
  PRIMARY KEY  (optionID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 导出表中的数据 `eq_option`
#

TRUNCATE TABLE `eq_option`;
INSERT INTO eq_option VALUES (1, '满意度', '很不满意###不满意###一般###满意###很满意', 0);
INSERT INTO eq_option VALUES (2, '重要度', '很不重要###不重要###一般###重要###很重要', 0);
INSERT INTO eq_option VALUES (3, '愿意度', '很不愿意###不愿意###愿意###很愿意###非常愿意', 0);
INSERT INTO eq_option VALUES (4, '认同度', '很不同意###不同意###一般###同意###很同意', 0);
INSERT INTO eq_option VALUES (5, '数字', '1###2###3###4###5', 0);
INSERT INTO eq_option VALUES (6, '评估值', '0###2.5###5###7.5###10', 0);

#
# 表的结构 `eq_panel`
#

CREATE TABLE IF NOT EXISTS eq_panel (
  panelID int(4) unsigned not null auto_increment,
  tplTagName varchar(50) binary not null default '',
  tplName varchar(30) binary not null default '',
  administratorsID int(30) unsigned not null default '0',
  isDefault int(1) unsigned not null default '0',
  isSystem int(1) unsigned not null default '0',
  PRIMARY KEY  (panelID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 导出表中的数据 `eq_panel`
#

TRUNCATE TABLE `eq_panel`;
INSERT INTO eq_panel VALUES (1, '标准问卷展现框架模板', 'Panel.html', 0, 1, 0);
INSERT INTO eq_panel VALUES (30001, '问卷展现系统模板(蓝色)', 'DefaultPanel1.html', 0, 0, 1);
INSERT INTO eq_panel VALUES (30002, '问卷展现系统模板(浅蓝色)', 'DefaultPanel2.html', 0, 0, 1);
INSERT INTO eq_panel VALUES (30003, '问卷展现系统模板(深蓝色)', 'DefaultPanel3.html', 0, 0, 1);
INSERT INTO eq_panel VALUES (30004, '问卷展现系统模板(红色)', 'DefaultPanel4.html', 0, 0, 1);
INSERT INTO eq_panel VALUES (30005, '问卷展现系统模板(多色)', 'DefaultPanel5.html', 0, 0, 1);
INSERT INTO eq_panel VALUES (30006, '问卷展现系统模板(绿色)', 'DefaultPanel6.html', 0, 0, 1);

#
# 表的结构 `eq_plan`
#

CREATE TABLE IF NOT EXISTS eq_plan (
  planID int(50) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  planDay date not null default '0000-00-00',
  planNum int(6) unsigned not null default '0',
  planDesc text not null,
  PRIMARY KEY  (planID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_query_cond`
#

CREATE TABLE IF NOT EXISTS eq_query_cond (
  querycondID int(50) unsigned not null auto_increment,
  administratorsID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  queryID int(30) unsigned not null default '0',
  fieldsID varchar(30) binary not null default '0',
  optionID int(30) unsigned not null default '0',
  labelID int(30) unsigned not null default '0',
  queryValue varchar(255) binary not null default '0',
  logicOR int(1) unsigned not null default '0',
  isRadio int(1) unsigned not null default '0',
  PRIMARY KEY  (querycondID),
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID),
  KEY queryID (queryID),
  KEY fieldsID (fieldsID),
  KEY logicOR (logicOR),
  KEY optionID (optionID),
  KEY labelID (labelID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_query_list`
#

CREATE TABLE IF NOT EXISTS eq_query_list (
  queryID int(30) unsigned not null auto_increment,
  administratorsID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  queryName varchar(255) binary not null default '',
  defineShare INT( 1 ) unsigned default  '0' not null,
  PRIMARY KEY  (queryID),
  KEY surveyID (surveyID),
  KEY defineShare (defineShare),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question`
#

CREATE TABLE IF NOT EXISTS eq_question (
  questionID int(30) unsigned not null auto_increment,
  questionName text not null,
  questionNotes text not null,
  alias varchar(100) binary not null default '',
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionType int(2) unsigned not null default '0',
  isPublic int(1) unsigned not null default '1',
  isRequired int(1) unsigned not null default '0',
  requiredMode int(1) unsigned not null default '1',
  isRandOptions int(1) unsigned not null default '0',
  isCheckType int(1) unsigned not null default '0',
  isSelect int(1) unsigned not null default '0',
  isLogicAnd int(1) unsigned not null default '1',
  baseID int(30) unsigned not null default '0',
  isColArrange int(1) unsigned not null default '0',
  perRowCol int(4) unsigned not null default '0',
  isHaveOther int(1) unsigned not null default '0',
  otherText varchar(200) not null default '',
  isNeg int(1) unsigned not null default '0',
  optionCoeff float(4,2) not null default '0.00',
  otherCode INT( 5 ) unsigned default  '0',
  negCode INT( 5 ) unsigned default  '0',
  isHaveWhy int(1) unsigned not null default '0',
  minOption int(2) not null default '0',
  maxOption int(2) not null default '0',
  unitText varchar( 255 ) binary not null,
  rows int(2) unsigned not null default '5',
  length int(2) unsigned not null default '50',
  minSize int(2) not null default '0',
  maxSize int(2) not null default '2',
  allowType varchar(255) binary not null default '',
  startScale int(1) unsigned not null default '0',
  endScale int(1) unsigned not null default '0',
  weight int(2) unsigned not null default '0',
  isHaveUnkown int(1) unsigned not null default '1',
  DSNConnect varchar(100) binary not null default '',
  DSNSQL text not null,
  DSNUser varchar(40) binary not null default '',
  DSNPassword varchar(20) binary not null default '',
  hiddenVarName varchar(20) binary not null default '',
  hiddenFromSession int(1) unsigned not null default '0',
  orderByID int(30) unsigned not null default '0',
  PRIMARY KEY  (questionID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionType (questionType),
  KEY isRequired (isRequired),
  KEY isPublic (isPublic)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_checkbox`
#

CREATE TABLE IF NOT EXISTS eq_question_checkbox (
  question_checkboxID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionOptionID int(10) unsigned not null default '0',
  optionName varchar(255) binary not null default '',
  optionMargin int(4) unsigned not null default '0',
  optionCoeff float(4,2) not null default '0.00',
  itemCode INT( 5 ) unsigned default  '0',
  optionNameFile varchar(100) binary not null default '',
  isExclusive int(1) unsigned not null default '0',
  isHaveText int(1) unsigned not null default '1',
  optionSize int(2) unsigned not null default '20',
  isRequired int(1) unsigned not null default '1',
  isCheckType int(1) unsigned not null default '0',
  minOption int(2) not null default '0',
  maxOption int(2) not null default '0',
  unitText varchar( 255 ) binary not null,
  isLogicAnd int(1) unsigned not null default '1',
  createDate int(11) unsigned not null default '0',
  orderByID int(20) unsigned not null default '0',
  PRIMARY KEY  (question_checkboxID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_info`
#

CREATE TABLE IF NOT EXISTS eq_question_info (
  question_infoID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionName text not null,
  PRIMARY KEY  (question_infoID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_radio`
#

CREATE TABLE IF NOT EXISTS eq_question_radio (
  question_radioID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionOptionID int(10) unsigned not null default '0',
  optionName varchar(255) binary not null default '',
  optionMargin int(4) unsigned not null default '0',
  optionCoeff float(4,2) not null default '0.00',
  itemCode INT( 5 ) unsigned default  '0',
  optionNameFile varchar(100) binary not null default '',
  isHaveText int(1) unsigned not null default '1',
  optionSize int(2) unsigned not null default '20',
  isRequired int(1) unsigned not null default '1',
  isCheckType int(1) unsigned not null default '0',
  minOption int(2) not null default '0',
  maxOption int(2) not null default '0',
  unitText varchar( 255 ) binary not null,
  isLogicAnd int(1) unsigned not null default '1',
  createDate int(11) unsigned not null default '0',
  orderByID int(20) unsigned not null default '0',
  PRIMARY KEY  (question_radioID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_range_answer`
#

CREATE TABLE IF NOT EXISTS eq_question_range_answer (
  question_range_answerID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionAnswer varchar(200) binary not null default '',
  optionCoeff float(4,2) not null default '0.00',
  itemCode INT( 5 ) unsigned default  '0',
  isLogicAnd int(1) unsigned not null default '1',
  PRIMARY KEY  (question_range_answerID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_range_label`
#

CREATE TABLE IF NOT EXISTS eq_question_range_label (
  question_range_labelID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionLabel text not null,
  optionOptionID int(10) unsigned not null default '0',
  optionSize int(2) unsigned not null default '16',
  isRequired int(1) unsigned not null default '0',
  isCheckType int(1) unsigned not null default '0',
  minOption int(2) not null default '0',
  maxOption int(2) not null default '0',
  orderByID int(20) unsigned not null default '0',
  PRIMARY KEY  (question_range_labelID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_range_option`
#

CREATE TABLE IF NOT EXISTS eq_question_range_option (
  question_range_optionID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionName text not null,
  optionOptionID int(10) unsigned not null default '0',
  isRequired int(1) unsigned not null default '0',
  minOption int(2) unsigned not null default '0',
  maxOption int(2) unsigned not null default '0',
  isLogicAnd int(1) unsigned not null default '1',
  PRIMARY KEY  (question_range_optionID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_rank`
#

CREATE TABLE IF NOT EXISTS eq_question_rank (
  question_rankID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  isLogicAnd int(1) unsigned not null default '1',
  optionName text not null,
  PRIMARY KEY  (question_rankID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_question_yesno`
#

CREATE TABLE IF NOT EXISTS eq_question_yesno (
  question_yesnoID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionName varchar(255) binary not null default '',
  optionCoeff float(4,2) not null default '0.00',
  itemCode INT( 5 ) unsigned default  '0',
  optionOptionID int(10) unsigned not null default '0',
  optionSize int(2) unsigned not null default '20',
  isRequired int(1) unsigned not null default '0',
  isNeg int(1) unsigned not null default '0',
  isCheckType int(1) unsigned not null default '0',
  minOption int(2) not null default '0',
  maxOption int(2) not null default '0',
  unitText varchar( 255 ) binary not null,
  orderByID int(20) unsigned not null default '0',
  PRIMARY KEY  (question_yesnoID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_quota`
#

CREATE TABLE IF NOT EXISTS eq_quota (
  quotaID int(20) unsigned not null auto_increment,
  quotaName varchar(255) binary not null default '',
  quotaNum int(20) unsigned not null default '0',
  surveyID int(30) unsigned not null default '0',
  quotaText text not null,
  PRIMARY KEY  (quotaID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_relation`
#

CREATE TABLE IF NOT EXISTS eq_relation (
  relationID int(20) unsigned not null auto_increment,
  relationMode int(1) unsigned not null default '0',
  relationNum float(12,2) unsigned not null default '0.00',
  questionID int(30) unsigned not null default '0',
  optionID int(20) unsigned not null default '0',
  labelID int(20) unsigned not null default '0',
  opertion int(1) unsigned not null default '1',
  surveyID int(20) unsigned not null default '0',
  PRIMARY KEY  (relationID),
  KEY relationMode (relationMode),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY labelID (labelID),
  KEY surveyID (surveyID),
  KEY opertion (opertion)
) ENGINE=MyISAM;

#
# 表的结构 `eq_relation_list`
#

CREATE TABLE IF NOT EXISTS eq_relation_list (
  listID int(30) unsigned not null auto_increment,
  relationID int(20) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  optionID int(20) unsigned not null default '0',
  labelID int(20) unsigned not null default '0',
  opertion int(1) unsigned not null default '1',
  optionOptionID int(6) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  PRIMARY KEY  (listID),
  KEY relationID (relationID),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY labelID (labelID),
  KEY opertion (opertion),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_report_define`
#

CREATE TABLE IF NOT EXISTS eq_report_define (
  defineID int(10) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  defineShare INT( 1 ) unsigned default  '1' not null,
  administratorsID INT( 30 ) unsigned default  '0' not null,
  questionID varchar( 255 ) binary not null default  '',
  condOnID int(30) unsigned not null default '0',
  optionID int(20) unsigned not null default '0',
  qtnID int(20) unsigned not null default '0',
  condOnID2 int(30) unsigned not null default '0',
  optionID2 int(20) unsigned not null default '0',
  qtnID2 int(20) unsigned not null default '0',
  defineType int(1) unsigned not null default '1',
  PRIMARY KEY  (defineID),
  KEY surveyID (surveyID),
  KEY defineShare (defineShare),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID),
  KEY optionID (optionID),
  KEY qtnID (qtnID),
  KEY defineType (defineType),
  KEY condOnID (condOnID),
  KEY condOnID2 (condOnID2),
  KEY optionID2 (optionID2),
  KEY qtnID2 (qtnID2)
) ENGINE=MyISAM;

#
# 表的结构 `eq_response_group_list`
#

CREATE TABLE IF NOT EXISTS eq_response_group_list (
  responseGroupListID int(20) unsigned not null auto_increment,
  administratorsGroupID int(6) unsigned not null default '0',
  administratorsoptionID int(20) unsigned not null default '0',
  value varchar(255) binary not null default '',
  adUserName varchar(255) binary not null default '',
  surveyID int(20) unsigned not null default '0',
  PRIMARY KEY  (responseGroupListID),
  KEY administratorsGroupID (administratorsGroupID),
  KEY surveyID (surveyID),
  KEY adUserGroupName (adUserName),
  KEY administratorsoptionID (administratorsoptionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_comb_list`
#

CREATE TABLE IF NOT EXISTS eq_result_comb_list (
  combListID int(20) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  optionID int(20) unsigned not null default '0',
  combNameID int(10) unsigned not null default '0',
  PRIMARY KEY  (combListID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY administratorsID (administratorsID),
  KEY optionID (optionID),
  KEY combNameID (combNameID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_comb_name`
#

CREATE TABLE IF NOT EXISTS eq_result_comb_name (
  combNameID int(10) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  combName varchar(100) binary not null default '',
  PRIMARY KEY  (combNameID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_day_num`
#

CREATE TABLE IF NOT EXISTS eq_result_day_num (
  h0 int(11) unsigned not null default '0',
  h1 int(11) unsigned not null default '0',
  h2 int(11) unsigned not null default '0',
  h3 int(11) unsigned not null default '0',
  h4 int(11) unsigned not null default '0',
  h5 int(11) unsigned not null default '0',
  h6 int(11) unsigned not null default '0',
  h7 int(11) unsigned not null default '0',
  h8 int(11) unsigned not null default '0',
  h9 int(11) unsigned not null default '0',
  h10 int(11) unsigned not null default '0',
  h11 int(11) unsigned not null default '0',
  h12 int(11) unsigned not null default '0',
  h13 int(11) unsigned not null default '0',
  h14 int(11) unsigned not null default '0',
  h15 int(11) unsigned not null default '0',
  h16 int(11) unsigned not null default '0',
  h17 int(11) unsigned not null default '0',
  h18 int(11) unsigned not null default '0',
  h19 int(11) unsigned not null default '0',
  h20 int(11) unsigned not null default '0',
  h21 int(11) unsigned not null default '0',
  h22 int(11) unsigned not null default '0',
  h23 int(11) unsigned not null default '0',
  TDay varchar(10) binary not null default '0',
  surveyID int(20) unsigned not null default '0',
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_general_info`
#

CREATE TABLE IF NOT EXISTS eq_result_general_info (
  id int(4) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  TotalNum int(11) unsigned not null default '0',
  StartDate varchar(10) binary default NULL,
  MonthNum int(11) unsigned not null default '0',
  MonthMaxNum int(11) unsigned not null default '0',
  OldMonth varchar(10) binary default NULL,
  MonthMaxDate varchar(10) binary default NULL,
  DayNum int(11) unsigned not null default '0',
  DayMaxNum int(11) unsigned not null default '0',
  OldDay varchar(10) binary default NULL,
  DayMaxDate varchar(10) binary default NULL,
  HourNum int(11) unsigned default '0',
  HourMaxNum int(11) unsigned default '0',
  OldHour varchar(20) binary default '0',
  HourMaxTime varchar(20) binary default '0',
  PRIMARY KEY  (id),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_month_num`
#

CREATE TABLE IF NOT EXISTS eq_result_month_num (
  d1 int(11) unsigned not null default '0',
  d2 int(11) unsigned not null default '0',
  d3 int(11) unsigned not null default '0',
  d4 int(11) unsigned not null default '0',
  d5 int(11) unsigned not null default '0',
  d6 int(11) unsigned not null default '0',
  d7 int(11) unsigned not null default '0',
  d8 int(11) unsigned not null default '0',
  d9 int(11) unsigned not null default '0',
  d10 int(11) unsigned not null default '0',
  d11 int(11) unsigned not null default '0',
  d12 int(11) unsigned not null default '0',
  d13 int(11) unsigned not null default '0',
  d14 int(11) unsigned not null default '0',
  d15 int(11) unsigned not null default '0',
  d16 int(11) unsigned not null default '0',
  d17 int(11) unsigned not null default '0',
  d18 int(11) unsigned not null default '0',
  d19 int(11) unsigned not null default '0',
  d20 int(11) unsigned not null default '0',
  d21 int(11) unsigned not null default '0',
  d22 int(11) unsigned not null default '0',
  d23 int(11) unsigned not null default '0',
  d24 int(11) unsigned not null default '0',
  d25 int(11) unsigned not null default '0',
  d26 int(11) unsigned not null default '0',
  d27 int(11) unsigned not null default '0',
  d28 int(11) unsigned not null default '0',
  d29 int(11) unsigned not null default '0',
  d30 int(11) unsigned not null default '0',
  d31 int(11) unsigned not null default '0',
  TMonth varchar(10) binary not null default '0',
  surveyID int(20) unsigned not null default '0',
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_result_year_num`
#

CREATE TABLE IF NOT EXISTS eq_result_year_num (
  m1 int(11) unsigned not null default '0',
  m2 int(11) unsigned not null default '0',
  m3 int(11) unsigned not null default '0',
  m4 int(11) unsigned not null default '0',
  m5 int(11) unsigned not null default '0',
  m6 int(11) unsigned not null default '0',
  m7 int(11) unsigned not null default '0',
  m8 int(11) unsigned not null default '0',
  m9 int(11) unsigned not null default '0',
  m10 int(11) unsigned not null default '0',
  m11 int(11) unsigned not null default '0',
  m12 int(11) unsigned not null default '0',
  TYear varchar(10) binary not null default '0',
  surveyID int(20) unsigned not null default '0',
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey`
#

CREATE TABLE IF NOT EXISTS eq_survey (
  surveyID int(20) unsigned not null auto_increment,
  surveyName varchar(64) binary not null default '',
  lang char(3) binary not null default 'cn',
  isPublic int(1) unsigned not null default '1',
  tokenCode varchar(20) binary not null default '',
  status int(1) unsigned not null default '0',
  theme varchar(16) binary not null default 'Standard',
  panelID int(2) unsigned not null default '0',
  surveyTitle varchar(255) not null default '',
  surveySubTitle text not null,
  surveyMaxOption int(4) unsigned not null default '0',
  surveyInfo text not null,
  exitMode int(1) unsigned not null default '1',
  exitPage text not null,
  exitTitleHead varchar(255) not null default '',
  exitTextBody text not null,
  administratorsID int(30) unsigned not null default '0',
  isCheckIP int(1) unsigned not null default '1',
  maxIpTime int(6) unsigned not null default '1000',
  isAllowIP int(1) unsigned not null default '0',
  maxResponseNum int(4) unsigned not null default '0',
  isProperty int(1) unsigned not null default '1',
  isPreStep int(1) unsigned not null default '0',
  isProcessBar int(1) unsigned not null default '1',
  isShowResultBut int(1) unsigned not null default '0',
  isViewResult int(1) unsigned not null default '0',
  isDisRefresh int(1) unsigned not null default '1',
  isOnline0View int(1) unsigned not null default '1',
  isOnline0Auth int(1) unsigned default  '0' not null,
  projectType int(1) unsigned default '2' not null,
  projectOwner int(1) unsigned default '0' not null,
  custLogo varchar(100) binary default '' not null,
  custTel varchar(24) binary default '' not null,
  isViewAuthData int(1) unsigned not null default '0',
  isViewAuthInfo int(1) unsigned default '0' not null,
  isExportData INT( 1 ) unsigned default '0' not null,
  isImportData INT( 1 ) unsigned default '0' not null,
  isDeleteData INT( 1 ) unsigned default '0' not null,
  isLogicAnd int(1) unsigned not null default '0',
  isSecureImage int(1) unsigned not null default '0',
  isRecord INT(1) unsigned not null default  '1',
  isUploadRec INT(1) unsigned not null default  '1',
  isPanelFlag INT(1) unsigned not null default  '0',
  offlineCount varchar( 255 ) binary not null default  '',
  forbidViewId text not null,
  isWaiting int(1) unsigned not null default '0',
  waitingTime int(2) unsigned not null default '10',
  isLimited int(1) unsigned not null default '0',
  limitedTime int(8) unsigned not null default '0',
  isCheckStat0 INT( 1 ) unsigned default  '0' not null,
  isOfflineModi INT( 1 ) unsigned default  '0' not null,
  mainAttribute varchar(100) binary not null default '',
  ajaxRtnValue varchar(255) not null default '',
  mainShowQtn int(30) unsigned not null default '0',
  isCache int(1) unsigned not null default '1',
  dbSize varchar(50) binary not null default '180,140,180,100,200,120,120,120,255,255',
  apiURL varchar( 255 ) binary not null,
  apiVarName varchar( 255 ) binary not null,
  indexVersion int( 1 ) unsigned default  '0' not null,
  indexTime int( 11 ) unsigned default  '0' not null,
  indexAdminId int( 30 ) unsigned default  '0' not null,
  beginTime date not null default '0000-00-00',
  endTime date not null default '0000-00-00',
  joinTime int(11) unsigned not null default '0',
  updateTime int(11) not null default '0',
  PRIMARY KEY  (surveyID),
  KEY administratorsID (administratorsID),
  KEY status (status),
  KEY isPublic (isPublic)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey_cate`
#

CREATE TABLE IF NOT EXISTS eq_survey_cate (
  cateID int(6) unsigned not null auto_increment,
  cateTag varchar(64) binary not null default '',
  cateName varchar(255) binary not null default '',
  PRIMARY KEY  (cateID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey_cate_list`
#

CREATE TABLE IF NOT EXISTS eq_survey_cate_list (
  cateListID INT( 20 ) unsigned not null AUTO_INCREMENT,
  cateID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  PRIMARY KEY  (cateListID),
  KEY cateID (cateID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey_index`
#

CREATE TABLE IF NOT EXISTS eq_survey_index (
  indexID int(30) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  indexName varchar(200) binary not null default '',
  indexDesc text not null,
  isMinZero INT(1) UNSIGNED DEFAULT  '0' NOT NULL,
  PRIMARY KEY  (indexID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey_index_list`
#

CREATE TABLE IF NOT EXISTS eq_survey_index_list (
  listID int(50) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  indexID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  PRIMARY KEY  (listID),
  KEY surveyID (surveyID),
  KEY indexID (indexID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_text_option`
#

CREATE TABLE IF NOT EXISTS eq_text_option (
  optionID int(50) unsigned not null auto_increment,
  questionID int(30) unsigned not null default '0',
  optionText varchar(255) binary not null default '',
  PRIMARY KEY  (optionID),
  KEY questionID (questionID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_user_group`
#

CREATE TABLE IF NOT EXISTS eq_user_group (
  userGroupID int(20) unsigned not null auto_increment,
  userGroupName varchar(255) binary not null default '',
  createDate int(11) unsigned not null default '0',
  fatherId INT(20) unsigned default '0' not null,
  groupType INT(1) unsigned default '1' not null,
  absPath varchar(255) binary default  '0' not null,
  isLeaf int(1) unsigned default '1' not null,
  userGroupDesc varchar(255) binary not null default '',
  userGroupLabel varchar(100) binary not null default '',
  PRIMARY KEY  (userGroupID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_view_user_list`
#

CREATE TABLE IF NOT EXISTS eq_view_user_list (
  viewUserListID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  isAuth int(1) unsigned default '0' not null,
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_android_list`
#

CREATE TABLE IF NOT EXISTS eq_android_list (
  listID int(30) unsigned not null auto_increment,
  surveyID int(20) unsigned not null default '0',
  PRIMARY KEY  (listID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_android_log`
#

CREATE TABLE IF NOT EXISTS eq_android_log (
  logId int(50) unsigned not null AUTO_INCREMENT,
  surveyID int(30) unsigned not null default '0',
  userId int(50) unsigned not null default '0',
  nickName varchar(100) not null default '',
  actionMess text not null,
  actionTime int(11) unsigned not null default '0',
  actionType INT(1) unsigned default  '0' not null,
  PRIMARY KEY (logId)
) ENGINE=MyISAM;

#
# 表的结构 `eq_android_info`
#

CREATE TABLE IF NOT EXISTS eq_android_info (
  surveyID int(20) unsigned not null default '0',
  responseID int(30) unsigned not null default '0',
  line1Number varchar( 15 ) binary not null default '',
  deviceId varchar(100) not null default '',
  brand varchar(50) not null default '',
  model varchar(50) not null default '',
  currentCity varchar(100) not null default '',
  simOperatorName varchar(50) not null default '',
  simSerialNumber varchar(100) not null default '',
  gpsTime varchar(15) not null default '',
  accuracy varchar(30) not null default '',
  longitude varchar(30) not null default '',
  latitude varchar(30) not null default '',
  speed varchar(30) not null default '',
  bearing varchar(30) not null default '',
  altitude varchar(30) not null default '',
  KEY surveyID (surveyID),
  KEY responseID (responseID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_android_push`
#

CREATE TABLE IF NOT EXISTS eq_android_push (
  pushID int(40) unsigned not null auto_increment,
  pushTitle varchar(255) binary not null default '',
  pushInfo text not null,
  pushURL varchar(255) binary not null default '',
  surveyID int(20) unsigned not null default '0',
  stat int(1) unsigned not null default '0',
  isCommon INT( 1 ) unsigned default  '1',
  administratorsID int(30) unsigned not null default '0',
  pushTime int(11) unsigned not null default '0',
  PRIMARY KEY  (pushID),
  KEY surveyID (surveyID),
  KEY administratorsID (administratorsID),
  KEY stat (stat)
) ENGINE=MyISAM;

#
# 表的结构 `eq_task_list`
#

CREATE TABLE IF NOT EXISTS eq_task_list (
  taskID int(30) unsigned default '0' not null,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  KEY taskID (taskID),
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_associate`
#

CREATE TABLE IF NOT EXISTS eq_associate (
  associateID int(10) unsigned not null AUTO_INCREMENT,
  surveyID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  qtnID int(20) unsigned not null default '0',
  optionID int(20) unsigned not null default '0',
  condOnID int(30) unsigned not null default '0',
  condQtnID int(20) unsigned not null default '0',
  condOptionID int(20) unsigned not null default '0',
  opertion int(1) unsigned not null default '1',
  assType int(1) unsigned not null default '1',
  PRIMARY KEY (associateID),
  KEY surveyID (surveyID),
  KEY questionID (questionID),
  KEY qtnID (qtnID),
  KEY optionID (optionID),
  KEY condOnID (condOnID),
  KEY condQtnID (condQtnID),
  KEY condOptionID (condOptionID),
  KEY opertion (opertion)
) ENGINE=MyISAM;

#
# 表的结构 `eq_appeal_user_list`
#

CREATE TABLE IF NOT EXISTS eq_appeal_user_list (
  appealUserListID int(20) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  surveyID int(20) unsigned not null default '0',
  isAuth int(1) unsigned not null default '0',
  KEY administratorsID (administratorsID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_data_task`
#

CREATE TABLE IF NOT EXISTS eq_data_task (
  taskID int(50) unsigned not null AUTO_INCREMENT,
  surveyID int(20) unsigned not null default '0',
  responseID int(30) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  taskTime int(11) unsigned not null default '0',
  authStat int(1) unsigned not null default '0',
  appStat int(1) unsigned not null default '0',
  nextUserId int(20) unsigned not null default '0',
  reason text not null,
  PRIMARY KEY (taskID),
  KEY administratorsID (administratorsID),
  KEY responseID (responseID),
  KEY surveyID (surveyID)
) ENGINE=MyISAM;

#
# 表的结构 `eq_data_trace`
#

CREATE TABLE IF NOT EXISTS eq_data_trace (
  traceID int(50) unsigned not null AUTO_INCREMENT,
  surveyID int(20) unsigned not null default '0',
  responseID int(30) unsigned not null default '0',
  administratorsID int(30) unsigned not null default '0',
  questionID int(30) unsigned not null default '0',
  traceTime int(11) unsigned not null default '0',
  varName varchar(100) not null default '',
  oriValue text not null,
  updateValue text not null,
  isAppData int(1) unsigned not null default '0',
  evidence varchar(100) not null default '',
  reason text not null,
  PRIMARY KEY (traceID),
  KEY surveyID (surveyID),
  KEY responseID (responseID),
  KEY administratorsID (administratorsID),
  KEY questionID (questionID),
  KEY isAppData (isAppData)
) ENGINE=MyISAM;

#
# 表的结构 `eq_survey_index_result`
#

CREATE TABLE IF NOT EXISTS eq_survey_index_result (
  responseID int(30) unsigned not null default '0',
  taskID int(30) unsigned NOT NULL DEFAULT '0',
  surveyID int(20) unsigned not null default '0',
  indexID int(30) unsigned not null default '0',
  indexValue float(10,2) not null default '0.00',
  KEY indexID (indexID),
  KEY surveyID (surveyID),
  KEY responseID (responseID)
) ENGINE=MyISAM;


#
# 9.0 修订
#

CREATE TABLE IF NOT EXISTS eq_gps_trace_upload (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `responseID` int(30) unsigned NOT NULL DEFAULT '0',
  `qtnID` int(30) NOT NULL DEFAULT '0',
  `gpsTime` varchar(15) NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `speed` varchar(30) NOT NULL DEFAULT '',
  `bearing` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `surveyID` (`surveyID`),
  KEY `responseID` (`responseID`)
) ENGINE=MyISAM ;

CREATE TABLE IF NOT EXISTS eq_gps_trace (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `responseID` int(30) unsigned NOT NULL DEFAULT '0',
  `gpsTime` varchar(15) NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `speed` varchar(30) NOT NULL DEFAULT '',
  `bearing` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `surveyID` (`surveyID`),
  KEY `responseID` (`responseID`)
) ENGINE=MyISAM;

ALTER TABLE `eq_survey` ADD  `isFailReApp` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isViewAuthInfo` ;

CREATE TABLE IF NOT EXISTS eq_ip_white (
whiteID INT( 30 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
ipAddress VARCHAR( 15 ) BINARY NOT NULL DEFAULT  '',
PRIMARY KEY ( whiteID )
) TYPE = MYISAM;

ALTER TABLE `eq_survey_index` CHANGE  `indexName`  `indexName` VARCHAR( 255 ) NOT NULL DEFAULT  '';
ALTER TABLE `eq_survey` ADD  `isGpsEnable` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isPanelFlag` ,
ADD  `isFingerDrawing` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isGpsEnable` ;

CREATE TABLE IF NOT EXISTS eq_device_trace (
  `traceID` int(50) unsigned not null AUTO_INCREMENT,
  `brand` varchar(50) BINARY NOT NULL DEFAULT  '',
  `model` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '',
  `deviceId` varchar(60) BINARY NOT NULL DEFAULT '',
  `nickUserName` varchar(50) BINARY NOT NULL DEFAULT  '',
  `gpsTime` varchar(15) BINARY NOT NULL DEFAULT '',
  `accuracy` varchar(30) NOT NULL DEFAULT '',
  `longitude` varchar(30) NOT NULL DEFAULT '',
  `latitude` varchar(30) NOT NULL DEFAULT '',
  `altitude` varchar(30) NOT NULL DEFAULT '',
  `isCell` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (traceID),
  KEY `deviceId` (`deviceId`)
) ENGINE=MyISAM;

ALTER TABLE `eq_conditions` ADD  `logicValueIsAnd` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `eq_associate` ADD  `logicValueIsAnd` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0';
ALTER TABLE `eq_question` ADD  `isContInvalid` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isHaveWhy` ,
ADD  `contInvalidValue` INT( 2 ) UNSIGNED NULL DEFAULT  '0' AFTER  `isContInvalid` ;

UPDATE `eq_panel` SET isDefault = 1 WHERE panelID =1;
UPDATE `eq_panel` SET isDefault = 0 WHERE panelID =30001;

ALTER TABLE `eq_question` ADD  `coeffMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isNeg` ,
ADD  `coeffTotal` FLOAT( 8, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `coeffMode` ,
ADD  `coeffZeroMargin` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `coeffTotal` ,
ADD  `coeffFullMargin` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `coeffZeroMargin` ,
ADD  `skipMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `coeffFullMargin` ,
ADD  `negCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `skipMode` ;

ALTER TABLE `eq_question` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_checkbox` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_radio` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_range_answer` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';
ALTER TABLE `eq_question_yesno` CHANGE  `optionCoeff`  `optionCoeff` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00';

ALTER TABLE `eq_survey_index` ADD  `fatherId` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `indexDesc` ,
ADD  `isMaxFull` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `fatherId` ,
ADD  `fullValue` FLOAT( 10, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `isMaxFull` ;

CREATE TABLE IF NOT EXISTS eq_report (
  reportID int(20) unsigned NOT NULL AUTO_INCREMENT,
  reportName text NOT NULL,
  administratorsID int(30) unsigned NOT NULL DEFAULT '0',
  reportRecipient text NOT NULL,
  reportFile varchar(100) NOT NULL DEFAULT '',
  reportTime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (reportID),
  KEY administratorsID (administratorsID)
) ENGINE=MyISAM;

#
# 9.10 修订
#

ALTER TABLE `eq_survey` ADD  `isDataSource` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isCache` ;
ALTER TABLE `eq_survey` ADD  `forbidAppId` TEXT NOT NULL AFTER  `forbidViewId` ;
ALTER TABLE `eq_question_yesno` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;
ALTER TABLE `eq_question_radio` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;
ALTER TABLE `eq_question` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `otherCode` ;
ALTER TABLE `eq_question_range_answer` ADD  `isUnkown` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `itemCode` ;

### 2014-07-22
ALTER TABLE `eq_question` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_yesno` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_radio` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_range_answer` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isUnkown` ;
ALTER TABLE `eq_question_checkbox` ADD  `isNA` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isExclusive` ;

#
# 9.20 修订
#

ALTER TABLE `eq_survey` ADD `isPanelCache` INT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER  `isCache` ;

#
# 表的结构 `eq_cascade`
#

CREATE TABLE IF NOT EXISTS eq_cascade (
  `cascadeID` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `questionID` int(30) unsigned NOT NULL DEFAULT '0',
  `nodeID` int(50) unsigned NOT NULL DEFAULT '0',
  `nodeName` varchar(255) NOT NULL DEFAULT '',
  `nodeFatherID` int(50) unsigned NOT NULL DEFAULT '0',
  `level` int(2) unsigned NOT NULL DEFAULT '0',
  `flag` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cascadeID`),
  KEY `surveyID` (`surveyID`),
  KEY `questionID` (`questionID`),
  KEY `nodeID` (`nodeID`),
  KEY `nodeFatherID` (`nodeFatherID`),
  KEY `level` (`level`)
) ENGINE=MyISAM ;

ALTER TABLE `eq_survey` ADD `isModiData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isDeleteData` ,ADD `isOneData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isModiData` ,ADD `isGeolocation` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER `isOneData` ;

ALTER TABLE `eq_survey` ADD `AppId` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '' AFTER  `apiVarName` ,ADD  `AppSecret` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '' AFTER  `AppId` ,ADD  `isOnlyWeChat` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `AppSecret` ,ADD  `getChatUserInfo` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isOnlyWeChat` ,ADD  `getChatUserMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `getChatUserInfo` ;

#
# 表的结构 `eq_track_code`
#

CREATE TABLE IF NOT EXISTS eq_track_code (
  `tagID` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagName` varchar(255) NOT NULL DEFAULT '',
  `surveyID` int(30) unsigned NOT NULL DEFAULT '0',
  `tagCate` int(1) unsigned NOT NULL DEFAULT '1',
  `exposure` varchar(30) NOT NULL DEFAULT '',
  `firstExposure` varchar(30) NOT NULL DEFAULT '',
  `lastExposure` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`tagID`),
  KEY `surveyID` (`surveyID`)
) ENGINE=MyISAM;

ALTER TABLE `eq_result_general_info` ADD  `exposureDomain` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '',ADD  `trackBeginTime` VARCHAR( 20 ) BINARY NOT NULL DEFAULT  '',ADD  `trackEndTime` VARCHAR( 20 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureCampaign` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureControl` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `exposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `firstExposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '',ADD  `lastExposureNormal` VARCHAR( 30 ) BINARY NOT NULL DEFAULT  '';

ALTER TABLE `eq_result_general_info` ADD  `isOpen` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '2',ADD  `issueMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1',ADD  `issueRate` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '100',ADD  `issueCookie` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '';

ALTER TABLE `eq_result_general_info` ADD  `renderingCode` TEXT NOT NULL ;

ALTER TABLE `eq_question` CHANGE  `hiddenVarName`  `hiddenVarName` VARCHAR( 100 ) BINARY NOT NULL DEFAULT  '';

#
# 表的结构 `eq_issue_rule`
#

CREATE TABLE IF NOT EXISTS eq_issue_rule (
  `ruleID` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `surveyID` int(20) unsigned NOT NULL DEFAULT '0',
  `exposureVar` int(20) unsigned NOT NULL DEFAULT '0',
  `ruleValue` int(20) unsigned NOT NULL DEFAULT '0',
  `ruleOrderID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ruleID`),
  KEY `surveyID` (`surveyID`),
  KEY `exposureVar` (`exposureVar`)
) ENGINE=MyISAM;

#
# 9.30 修订
#

ALTER TABLE `eq_conditions` ADD  `logicMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE `eq_associate` ADD  `logicMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1';
ALTER TABLE `eq_question_checkbox` ADD  `groupNum` INT( 2 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `optionName` ;
ALTER TABLE `eq_survey` ADD  `isAllowIPMode` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `isAllowIP` ;
ALTER TABLE `eq_survey` ADD  `isAllData` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_base_setting` ADD  `isMd5Pass` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `ajaxCheckURL` ;
ALTER TABLE `eq_issue_rule` ADD  `cookieVarName` VARCHAR( 50 ) BINARY NOT NULL DEFAULT  '' AFTER  `exposureVar` ;
ALTER TABLE `eq_base_setting` ADD  `isPostMethod` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `ajaxOverURL` ;
ALTER TABLE `eq_base_setting` ADD  `ajaxTokenURL` VARCHAR( 255 ) BINARY NOT NULL DEFAULT  '' AFTER  `loginURL` ;

#
# 10.0 修订
#

ALTER TABLE `eq_question` ADD  `negValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ,
ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `negValue` ;
ALTER TABLE `eq_question_yesno` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_radio` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_checkbox` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;
ALTER TABLE `eq_question_range_answer` ADD  `optionValue` FLOAT( 6, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `optionCoeff` ;

ALTER TABLE `eq_relation` ADD  `qtnID` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `questionID` ,ADD INDEX (  `qtnID` ) ;
ALTER TABLE `eq_relation_list` ADD  `qtnID` INT( 20 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `questionID` ,ADD INDEX (  `qtnID` ) ;
ALTER TABLE `eq_survey` ADD  `isRelZero` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isCheckStat0` ;
ALTER TABLE `eq_survey` ADD  `isOfflineDele` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isOfflineModi` ;
ALTER TABLE `eq_base_setting` ADD  `isRealGps` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `baseSettingID` ;
ALTER TABLE `eq_question_radio` ADD  `isRetain` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_question_checkbox` ADD  `isRetain` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isLogicAnd` ;
ALTER TABLE `eq_survey` ADD  `msgImage` VARCHAR(255) NOT NULL DEFAULT  '' AFTER  `custLogo` ;
ALTER TABLE `eq_query_cond` ADD  `opertion` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `labelID` ;

#
# 10.10 修订
#

ALTER TABLE `eq_relation` ADD  `relationDefine` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '1' AFTER  `relationID` ;
ALTER TABLE `eq_survey` ADD  `uitheme` VARCHAR( 16 ) BINARY NOT NULL DEFAULT  'Phone' AFTER  `theme` ;
ALTER TABLE `eq_survey` CHANGE  `dbSize`  `dbSize` VARCHAR( 50 ) BINARY NOT NULL DEFAULT '180,0,180,100,200,120,0,120,255,255';
ALTER TABLE `eq_survey` ADD  `isRateIndex` INT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `isRelZero` ;


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

