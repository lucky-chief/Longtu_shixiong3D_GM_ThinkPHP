/*
MySQL Data Transfer
Source Host: localhost
Source Database: gmt
Target Host: localhost
Target Database: gmt
Date: 2015/12/16 11:29:45
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for gc_passport
-- ----------------------------
DROP TABLE IF EXISTS `gc_passport`;
CREATE TABLE `gc_passport` (
  `passportId` varchar(64) NOT NULL COMMENT '用户唯一通行证号',
  `serverId` int(11) unsigned NOT NULL COMMENT '服务器ID',
  `level` int(11) unsigned NOT NULL COMMENT '账号等级',
  `lastLoginTime` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  PRIMARY KEY (`passportId`,`serverId`),
  KEY `passportId` (`passportId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_access
-- ----------------------------
DROP TABLE IF EXISTS `gmt_access`;
CREATE TABLE `gmt_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_action
-- ----------------------------
DROP TABLE IF EXISTS `gmt_action`;
CREATE TABLE `gmt_action` (
  `action` int(11) NOT NULL COMMENT '动作id',
  `type` int(10) DEFAULT NULL,
  `name` varchar(30) NOT NULL COMMENT '动作名称',
  `auth_code` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='动作';

-- ----------------------------
-- Table structure for gmt_node
-- ----------------------------
DROP TABLE IF EXISTS `gmt_node`;
CREATE TABLE `gmt_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3412 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_notice
-- ----------------------------
DROP TABLE IF EXISTS `gmt_notice`;
CREATE TABLE `gmt_notice` (
  `id` int(11) NOT NULL,
  `notice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_role
-- ----------------------------
DROP TABLE IF EXISTS `gmt_role`;
CREATE TABLE `gmt_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `num` int(4) DEFAULT '0',
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `role_aoth` varchar(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_role_user
-- ----------------------------
DROP TABLE IF EXISTS `gmt_role_user`;
CREATE TABLE `gmt_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_server
-- ----------------------------
DROP TABLE IF EXISTS `gmt_server`;
CREATE TABLE `gmt_server` (
  `id` int(4) NOT NULL,
  `game_url` varchar(60) NOT NULL,
  `game_port` varchar(60) NOT NULL,
  `gmt_url` varchar(60) NOT NULL,
  `gmt_port` char(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `db_user` varchar(50) NOT NULL,
  `db_pwd` varchar(50) NOT NULL,
  `db_host` varchar(30) NOT NULL,
  `db_port` char(10) NOT NULL,
  `db_name` varchar(30) NOT NULL,
  `log_host` varchar(30) DEFAULT NULL,
  `log_user` varchar(30) DEFAULT NULL,
  `log_pwd` varchar(30) DEFAULT NULL,
  `log_port` char(10) DEFAULT NULL,
  `log_name` varchar(30) DEFAULT NULL,
  `remark` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for gmt_user
-- ----------------------------
DROP TABLE IF EXISTS `gmt_user`;
CREATE TABLE `gmt_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(30) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `gc_passport` VALUES ('', '1', '1', '1448277891');
INSERT INTO `gc_passport` VALUES ('', '2', '1', '1450187002');
INSERT INTO `gc_passport` VALUES ('', '5', '1', '1450236481');
INSERT INTO `gc_passport` VALUES ('', '7', '1', '1449825173');
INSERT INTO `gc_passport` VALUES ('0511', '2', '1', '1449222412');
INSERT INTO `gc_passport` VALUES ('0511', '4', '1', '1449215560');
INSERT INTO `gc_passport` VALUES ('0511', '5', '1', '1448862708');
INSERT INTO `gc_passport` VALUES ('0512', '2', '1', '1449555137');
INSERT INTO `gc_passport` VALUES ('0513', '2', '1', '1449745058');
INSERT INTO `gc_passport` VALUES ('0513', '3', '1', '1449556554');
INSERT INTO `gc_passport` VALUES ('0514', '2', '1', '1449745924');
INSERT INTO `gc_passport` VALUES ('0515', '2', '1', '1450176395');
INSERT INTO `gc_passport` VALUES ('0515', '5', '1', '1450233606');
INSERT INTO `gc_passport` VALUES ('1', '2', '3', '1450233901');
INSERT INTO `gc_passport` VALUES ('1', '4', '1', '1449208347');
INSERT INTO `gc_passport` VALUES ('1', '5', '1', '1448432694');
INSERT INTO `gc_passport` VALUES ('1111', '4', '1', '1448333113');
INSERT INTO `gc_passport` VALUES ('12', '2', '1', '1450233541');
INSERT INTO `gc_passport` VALUES ('122', '2', '1', '1450181271');
INSERT INTO `gc_passport` VALUES ('122', '5', '1', '1449813562');
INSERT INTO `gc_passport` VALUES ('123', '1', '1', '1448274737');
INSERT INTO `gc_passport` VALUES ('123', '2', '1', '1450174123');
INSERT INTO `gc_passport` VALUES ('123', '4', '4', '1449813159');
INSERT INTO `gc_passport` VALUES ('123123123', '2', '1', '1449223438');
INSERT INTO `gc_passport` VALUES ('1232', '2', '1', '1449540594');
INSERT INTO `gc_passport` VALUES ('1234', '1', '1', '1448278382');
INSERT INTO `gc_passport` VALUES ('1234', '2', '1', '1450175995');
INSERT INTO `gc_passport` VALUES ('1234', '3', '4', '1449751120');
INSERT INTO `gc_passport` VALUES ('1234', '4', '1', '1449751372');
INSERT INTO `gc_passport` VALUES ('1234', '5', '1', '1449542450');
INSERT INTO `gc_passport` VALUES ('1234', '7', '2', '1449745390');
INSERT INTO `gc_passport` VALUES ('12343', '2', '1', '1449800208');
INSERT INTO `gc_passport` VALUES ('123436', '2', '3', '1449801544');
INSERT INTO `gc_passport` VALUES ('1234CC', '3', '1', '1449892238');
INSERT INTO `gc_passport` VALUES ('1235', '3', '1', '1448962610');
INSERT INTO `gc_passport` VALUES ('1235', '8', '1', '1448966972');
INSERT INTO `gc_passport` VALUES ('12365', '2', '1', '1449836998');
INSERT INTO `gc_passport` VALUES ('124', '2', '1', '1450175182');
INSERT INTO `gc_passport` VALUES ('13', '2', '1', '1450177228');
INSERT INTO `gc_passport` VALUES ('131241', '2', '1', '1450072399');
INSERT INTO `gc_passport` VALUES ('14', '2', '1', '1449918094');
INSERT INTO `gc_passport` VALUES ('1425', '2', '1', '1449904550');
INSERT INTO `gc_passport` VALUES ('14575', '2', '1', '1449646234');
INSERT INTO `gc_passport` VALUES ('1458', '2', '1', '1450083159');
INSERT INTO `gc_passport` VALUES ('14663', '2', '1', '1449838493');
INSERT INTO `gc_passport` VALUES ('1487', '2', '1', '1449904980');
INSERT INTO `gc_passport` VALUES ('2', '2', '2', '1450178958');
INSERT INTO `gc_passport` VALUES ('21', '2', '1', '1450164935');
INSERT INTO `gc_passport` VALUES ('222', '2', '1', '1448604921');
INSERT INTO `gc_passport` VALUES ('222', '3', '1', '1448523866');
INSERT INTO `gc_passport` VALUES ('222', '5', '1', '1448521260');
INSERT INTO `gc_passport` VALUES ('23', '2', '1', '1450180480');
INSERT INTO `gc_passport` VALUES ('232', '2', '1', '1450180167');
INSERT INTO `gc_passport` VALUES ('232', '5', '1', '1450170890');
INSERT INTO `gc_passport` VALUES ('234', '2', '1', '1450178084');
INSERT INTO `gc_passport` VALUES ('2445', '2', '1', '1449890707');
INSERT INTO `gc_passport` VALUES ('3', '2', '5', '1450179353');
INSERT INTO `gc_passport` VALUES ('3', '4', '1', '1449555959');
INSERT INTO `gc_passport` VALUES ('321', '2', '1', '1449710795');
INSERT INTO `gc_passport` VALUES ('333', '2', '1', '1448854490');
INSERT INTO `gc_passport` VALUES ('34', '2', '2', '1450179392');
INSERT INTO `gc_passport` VALUES ('344', '2', '1', '1449889946');
INSERT INTO `gc_passport` VALUES ('3444', '2', '1', '1449890367');
INSERT INTO `gc_passport` VALUES ('4', '2', '2', '1450182382');
INSERT INTO `gc_passport` VALUES ('44', '2', '2', '1449904797');
INSERT INTO `gc_passport` VALUES ('444', '2', '1', '1448863183');
INSERT INTO `gc_passport` VALUES ('45', '2', '1', '1450173013');
INSERT INTO `gc_passport` VALUES ('45578', '2', '2', '1450066006');
INSERT INTO `gc_passport` VALUES ('4568', '2', '1', '1449629371');
INSERT INTO `gc_passport` VALUES ('4576', '2', '1', '1449745089');
INSERT INTO `gc_passport` VALUES ('4755', '2', '1', '1449649004');
INSERT INTO `gc_passport` VALUES ('479', '2', '1', '1450083069');
INSERT INTO `gc_passport` VALUES ('555', '2', '1', '1448863478');
INSERT INTO `gc_passport` VALUES ('56', '2', '1', '1450086781');
INSERT INTO `gc_passport` VALUES ('5664', '2', '1', '1449826100');
INSERT INTO `gc_passport` VALUES ('569', '2', '1', '1450087026');
INSERT INTO `gc_passport` VALUES ('711', '2', '1', '1449914059');
INSERT INTO `gc_passport` VALUES ('758', '2', '1', '1449905168');
INSERT INTO `gc_passport` VALUES ('77677', '2', '2', '1449752742');
INSERT INTO `gc_passport` VALUES ('777', '2', '1', '1448863628');
INSERT INTO `gc_passport` VALUES ('aaaa', '2', '1', '1449814573');
INSERT INTO `gc_passport` VALUES ('aaaa', '3', '1', '1449823028');
INSERT INTO `gc_passport` VALUES ('aaaabb10', '2', '1', '1449817278');
INSERT INTO `gc_passport` VALUES ('aaaabb10', '3', '1', '1449819742');
INSERT INTO `gc_passport` VALUES ('aaaabb10', '8', '1', '1449817987');
INSERT INTO `gc_passport` VALUES ('asd', '2', '1', '1449642791');
INSERT INTO `gc_passport` VALUES ('asd', '4', '1', '1449813192');
INSERT INTO `gc_passport` VALUES ('asdasd', '2', '1', '1449804073');
INSERT INTO `gc_passport` VALUES ('asdasd', '4', '1', '1449649404');
INSERT INTO `gc_passport` VALUES ('asdsdffg', '4', '1', '1449480200');
INSERT INTO `gc_passport` VALUES ('caicaitest', '2', '1', '1448960447');
INSERT INTO `gc_passport` VALUES ('caicaitest0', '1', '1', '1448270516');
INSERT INTO `gc_passport` VALUES ('caicaitest0', '2', '3', '1448424730');
INSERT INTO `gc_passport` VALUES ('caicaitest1', '2', '1', '1450236257');
INSERT INTO `gc_passport` VALUES ('caicaitest1', '3', '1', '1449040249');
INSERT INTO `gc_passport` VALUES ('caicaitest1', '4', '1', '1448619585');
INSERT INTO `gc_passport` VALUES ('caicaitest1', '5', '1', '1448447093');
INSERT INTO `gc_passport` VALUES ('DFG', '4', '1', '1448879781');
INSERT INTO `gc_passport` VALUES ('ggr', '2', '1', '1450173054');
INSERT INTO `gc_passport` VALUES ('ggr', '5', '4', '1450236251');
INSERT INTO `gc_passport` VALUES ('ggr1', '5', '1', '1450084112');
INSERT INTO `gc_passport` VALUES ('ggr2', '5', '6', '1450143151');
INSERT INTO `gc_passport` VALUES ('ggr3', '5', '2', '1450143488');
INSERT INTO `gc_passport` VALUES ('guo', '2', '1', '1450235838');
INSERT INTO `gc_passport` VALUES ('guo', '4', '1', '1449903483');
INSERT INTO `gc_passport` VALUES ('guo', '5', '1', '1450164885');
INSERT INTO `gc_passport` VALUES ('guo1', '5', '1', '1449716569');
INSERT INTO `gc_passport` VALUES ('jl', '2', '1', '1449561206');
INSERT INTO `gc_passport` VALUES ('jl', '5', '1', '1449804752');
INSERT INTO `gc_passport` VALUES ('jl1', '5', '1', '1449562762');
INSERT INTO `gc_passport` VALUES ('jl2', '5', '1', '1449563108');
INSERT INTO `gc_passport` VALUES ('jl3', '5', '1', '1449563639');
INSERT INTO `gc_passport` VALUES ('jl4', '5', '1', '1449563784');
INSERT INTO `gc_passport` VALUES ('jl5', '5', '1', '1449564138');
INSERT INTO `gc_passport` VALUES ('jl6', '2', '1', '1449751692');
INSERT INTO `gc_passport` VALUES ('jl6', '5', '1', '1449720057');
INSERT INTO `gc_passport` VALUES ('jl7', '5', '1', '1449752220');
INSERT INTO `gc_passport` VALUES ('kuangjiutian', '2', '6', '1450063226');
INSERT INTO `gc_passport` VALUES ('l1', '2', '1', '1448864421');
INSERT INTO `gc_passport` VALUES ('l10', '2', '2', '1449220465');
INSERT INTO `gc_passport` VALUES ('l12', '2', '1', '1449713840');
INSERT INTO `gc_passport` VALUES ('l12', '5', '1', '1449739227');
INSERT INTO `gc_passport` VALUES ('l13', '5', '1', '1449739339');
INSERT INTO `gc_passport` VALUES ('l14', '5', '1', '1449739364');
INSERT INTO `gc_passport` VALUES ('l15', '2', '1', '1449800357');
INSERT INTO `gc_passport` VALUES ('l16', '2', '1', '1449800751');
INSERT INTO `gc_passport` VALUES ('l17', '2', '1', '1449801432');
INSERT INTO `gc_passport` VALUES ('l18', '2', '1', '1449806592');
INSERT INTO `gc_passport` VALUES ('l19', '2', '1', '1449813154');
INSERT INTO `gc_passport` VALUES ('l2', '2', '1', '1448865142');
INSERT INTO `gc_passport` VALUES ('l20', '2', '1', '1449813292');
INSERT INTO `gc_passport` VALUES ('l21', '5', '1', '1449813356');
INSERT INTO `gc_passport` VALUES ('l23', '5', '1', '1449817016');
INSERT INTO `gc_passport` VALUES ('l24', '5', '1', '1449817190');
INSERT INTO `gc_passport` VALUES ('l25', '5', '2', '1449823385');
INSERT INTO `gc_passport` VALUES ('l26', '5', '1', '1449824573');
INSERT INTO `gc_passport` VALUES ('l27', '2', '1', '1450058543');
INSERT INTO `gc_passport` VALUES ('l27', '5', '1', '1450058328');
INSERT INTO `gc_passport` VALUES ('l28', '2', '1', '1450063386');
INSERT INTO `gc_passport` VALUES ('l29', '2', '1', '1450063558');
INSERT INTO `gc_passport` VALUES ('l30', '2', '1', '1450063692');
INSERT INTO `gc_passport` VALUES ('l31', '2', '1', '1450064111');
INSERT INTO `gc_passport` VALUES ('l32', '2', '1', '1450064504');
INSERT INTO `gc_passport` VALUES ('l34', '2', '1', '1450072827');
INSERT INTO `gc_passport` VALUES ('l35', '2', '1', '1450073431');
INSERT INTO `gc_passport` VALUES ('l36', '2', '1', '1450073696');
INSERT INTO `gc_passport` VALUES ('l37', '2', '1', '1450073816');
INSERT INTO `gc_passport` VALUES ('l38', '2', '1', '1450074281');
INSERT INTO `gc_passport` VALUES ('l39', '2', '1', '1450074450');
INSERT INTO `gc_passport` VALUES ('l39', '5', '1', '1450074489');
INSERT INTO `gc_passport` VALUES ('l4', '2', '1', '1448865157');
INSERT INTO `gc_passport` VALUES ('l40', '5', '1', '1450074849');
INSERT INTO `gc_passport` VALUES ('l41', '5', '1', '1450075364');
INSERT INTO `gc_passport` VALUES ('l42', '5', '1', '1450075831');
INSERT INTO `gc_passport` VALUES ('l43', '5', '1', '1450076097');
INSERT INTO `gc_passport` VALUES ('l44', '5', '1', '1450076529');
INSERT INTO `gc_passport` VALUES ('l45', '5', '1', '1450076863');
INSERT INTO `gc_passport` VALUES ('l46', '5', '1', '1450081777');
INSERT INTO `gc_passport` VALUES ('l47', '5', '1', '1450082869');
INSERT INTO `gc_passport` VALUES ('l48', '5', '1', '1450082982');
INSERT INTO `gc_passport` VALUES ('l49', '5', '1', '1450083122');
INSERT INTO `gc_passport` VALUES ('l5', '2', '1', '1448865372');
INSERT INTO `gc_passport` VALUES ('l50', '5', '1', '1450083451');
INSERT INTO `gc_passport` VALUES ('l51', '5', '1', '1450084183');
INSERT INTO `gc_passport` VALUES ('l52', '5', '1', '1450084277');
INSERT INTO `gc_passport` VALUES ('l53', '5', '2', '1450087581');
INSERT INTO `gc_passport` VALUES ('l54', '5', '1', '1450088131');
INSERT INTO `gc_passport` VALUES ('l55', '5', '1', '1450088302');
INSERT INTO `gc_passport` VALUES ('l56', '5', '1', '1450088517');
INSERT INTO `gc_passport` VALUES ('l57', '2', '1', '1450088652');
INSERT INTO `gc_passport` VALUES ('l58', '2', '1', '1450089117');
INSERT INTO `gc_passport` VALUES ('l59', '2', '1', '1450165251');
INSERT INTO `gc_passport` VALUES ('l59', '5', '1', '1450167287');
INSERT INTO `gc_passport` VALUES ('l6', '2', '1', '1448866491');
INSERT INTO `gc_passport` VALUES ('l60', '5', '1', '1450168213');
INSERT INTO `gc_passport` VALUES ('l61', '5', '1', '1450170991');
INSERT INTO `gc_passport` VALUES ('l62', '2', '1', '1450172123');
INSERT INTO `gc_passport` VALUES ('l62', '5', '1', '1450236406');
INSERT INTO `gc_passport` VALUES ('l7', '2', '1', '1448866731');
INSERT INTO `gc_passport` VALUES ('l8', '2', '1', '1448867049');
INSERT INTO `gc_passport` VALUES ('l9', '2', '1', '1448867386');
INSERT INTO `gc_passport` VALUES ('liwei', '2', '1', '1450232856');
INSERT INTO `gc_passport` VALUES ('liwei', '5', '1', '1450231735');
INSERT INTO `gc_passport` VALUES ('liwei1', '2', '1', '1450236254');
INSERT INTO `gc_passport` VALUES ('liwei2', '2', '1', '1449659474');
INSERT INTO `gc_passport` VALUES ('mgy003', '7', '1', '1448627232');
INSERT INTO `gc_passport` VALUES ('mgy008', '7', '3', '1450074841');
INSERT INTO `gc_passport` VALUES ('mgy009', '7', '1', '1448445321');
INSERT INTO `gc_passport` VALUES ('qwe', '2', '1', '1449654945');
INSERT INTO `gc_passport` VALUES ('qwe', '4', '5', '1450236563');
INSERT INTO `gc_passport` VALUES ('qweqwe', '4', '1', '1449805736');
INSERT INTO `gc_passport` VALUES ('sdf', '4', '1', '1449480153');
INSERT INTO `gc_passport` VALUES ('test002', '8', '1', '1449891549');
INSERT INTO `gc_passport` VALUES ('test01', '1', '2', '1448357790');
INSERT INTO `gc_passport` VALUES ('test01', '8', '1', '1449889972');
INSERT INTO `gc_passport` VALUES ('test04', '8', '1', '1450162761');
INSERT INTO `gc_passport` VALUES ('test05', '8', '1', '1450163692');
INSERT INTO `gc_passport` VALUES ('uangjiutian', '2', '6', '1449802393');
INSERT INTO `gc_passport` VALUES ('wer', '4', '1', '1449480056');
INSERT INTO `gc_passport` VALUES ('xc', '4', '1', '1449480488');
INSERT INTO `gc_passport` VALUES ('zhaoke', '1', '1', '1448014793');
INSERT INTO `gc_passport` VALUES ('zhaoke', '2', '1', '1448607046');
INSERT INTO `gc_passport` VALUES ('zxc', '4', '1', '1448878844');
INSERT INTO `gmt_access` VALUES ('7', '2', '2', '1', null);
INSERT INTO `gmt_access` VALUES ('7', '1', '1', '0', null);
INSERT INTO `gmt_access` VALUES ('7', '4', '2', '1', null);
INSERT INTO `gmt_access` VALUES ('1', '1', '1', '0', null);
INSERT INTO `gmt_access` VALUES ('1', '2', '2', '1', null);
INSERT INTO `gmt_access` VALUES ('7', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('7', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('7', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('7', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '411', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '412', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '411', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '411', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '412', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('8', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '412', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '411', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '512', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '511', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '51', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '5', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('9', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('11', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('11', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '412', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '411', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('10', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '2117', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '2117', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('14', '512', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('15', '41', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('15', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('15', '2', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('15', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('15', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('14', '511', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('14', '51', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('14', '5', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('14', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '513', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '512', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '511', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '51', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '5', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('16', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('13', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('13', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '3', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '1', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '4', '0', '0', null);
INSERT INTO `gmt_access` VALUES ('12', '41', '0', '0', null);
INSERT INTO `gmt_action` VALUES ('27', '5', '解除封号请求', '');
INSERT INTO `gmt_action` VALUES ('27', '2', '封号', '');
INSERT INTO `gmt_action` VALUES ('27', '4', '解除禁言', '');
INSERT INTO `gmt_action` VALUES ('27', '1', '冻结', '');
INSERT INTO `gmt_action` VALUES ('27', '3', '强制下线', '');
INSERT INTO `gmt_action` VALUES ('27', '0', '禁言', '');
INSERT INTO `gmt_action` VALUES ('28', '0', '即时发送公告请求', '');
INSERT INTO `gmt_action` VALUES ('28', '1', '定时发送公告请求', '');
INSERT INTO `gmt_action` VALUES ('28', '2', '循环发送公告请求', '');
INSERT INTO `gmt_action` VALUES ('29', null, '发送邮件', '');
INSERT INTO `gmt_action` VALUES ('0', null, '增加玩家经验', '');
INSERT INTO `gmt_action` VALUES ('1', null, '增加经济属性', '');
INSERT INTO `gmt_action` VALUES ('6', null, '充值', '');
INSERT INTO `gmt_node` VALUES ('1', 'FBGMT', '时空风暴GMT', '1', null, null, '0', '1', '0', '0');
INSERT INTO `gmt_node` VALUES ('5', 'Role', '权限模块', '1', null, null, '1', '2', '0', '0');
INSERT INTO `gmt_node` VALUES ('51', 'Role_base', '基本选项', '1', null, null, '5', '3', '0', '0');
INSERT INTO `gmt_node` VALUES ('3', 'User', '用户管理', '1', '', null, '1', '2', '0', '0');
INSERT INTO `gmt_node` VALUES ('2', 'Gm', 'GM指令', '1', '', null, '1', '2', '0', '0');
INSERT INTO `gmt_node` VALUES ('4', 'Account', '账号模块', '1', '', null, '1', '2', '0', '0');
INSERT INTO `gmt_node` VALUES ('411', 'user_list', '系统管理员列表', '1', null, null, '41', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('412', 'add_user', '增加系统管理员', '1', null, null, '41', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('41', 'Account_base', '基本选项', '1', null, null, '4', '3', '0', '0');
INSERT INTO `gmt_node` VALUES ('511', 'user_group', '权限分组列表', '1', null, null, '51', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('512', 'add_usergroup', '增减权限分组', '1', null, null, '51', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('7', 'Server', '服务器管理', '1', null, null, '1', '2', '0', '0');
INSERT INTO `gmt_node` VALUES ('71', 'Ser_base', '基本选项', '1', null, null, '7', '3', '0', '0');
INSERT INTO `gmt_node` VALUES ('711', 's_list', '服务器配置列表', '1', null, null, '71', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('712', 's_add', '增加服务器配置操作', '1', null, null, '71', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('713', 's_upd', '修改服务器配置', '1', null, null, '71', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('714', 's_del', '删除服务器', '1', null, null, '71', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('513', 'upd_usergroup', '修改分组信息', '1', null, null, '51', '4', '0', '0');
INSERT INTO `gmt_node` VALUES ('2117', 'playerRole', '权限分配', '1', null, null, '211', '5', '0', '0');
INSERT INTO `gmt_notice` VALUES ('0', '程序一服，程序二服已经开放，\r\n请客户端同学连接程序二服');
INSERT INTO `gmt_role` VALUES ('9', '高级管理组', '0', '0', '1', null, '', null, '1355288947', '1355288947');
INSERT INTO `gmt_role` VALUES ('10', '管理组', '0', '0', '1', null, '', null, '1355289045', '1355289045');
INSERT INTO `gmt_role` VALUES ('11', '客服组长', '0', '0', '1', null, '', null, '1355289101', '1355289101');
INSERT INTO `gmt_role` VALUES ('12', '客服坐席', '0', '0', '1', null, '', null, '1355289132', '1355289132');
INSERT INTO `gmt_role` VALUES ('13', '商务组', '0', '0', '1', null, '', null, '1356318386', '1356318386');
INSERT INTO `gmt_role` VALUES ('14', '测试专用', '0', '0', '1', null, '', null, '1357361829', '1357361829');
INSERT INTO `gmt_role` VALUES ('15', '联运商', '0', '0', '1', null, '', null, '1357363203', '1357363203');
INSERT INTO `gmt_role` VALUES ('16', '天龙运营组', '0', '0', '1', null, '', null, '1374201228', '1374201228');
INSERT INTO `gmt_role_user` VALUES ('11', '48');
INSERT INTO `gmt_role_user` VALUES ('1', null);
INSERT INTO `gmt_role_user` VALUES ('1', null);
INSERT INTO `gmt_role_user` VALUES ('8', null);
INSERT INTO `gmt_role_user` VALUES ('1', '43');
INSERT INTO `gmt_role_user` VALUES ('8', '44');
INSERT INTO `gmt_role_user` VALUES ('8', '45');
INSERT INTO `gmt_role_user` VALUES ('7', '46');
INSERT INTO `gmt_role_user` VALUES ('1', '47');
INSERT INTO `gmt_role_user` VALUES ('11', '48');
INSERT INTO `gmt_role_user` VALUES ('14', '50');
INSERT INTO `gmt_server` VALUES ('1', '172.16.50.24', '8000', '172.16.50.24', '8208', '程序服一(牛)', 'shiguang', 'shiguang', '172.16.50.24', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('2', '172.16.50.7', '8000', '172.16.50.7', '8208', '程序服二(杨)', 'root', '123456', '172.16.50.7', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('3', '172.16.200.142', '8000', '172.16.200.142', '8208', '测试服一(李)', 'shiguang', 'shiguang@2015', '172.16.200.142', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('4', '172.16.50.1', '8000', '172.16.50.1', '8208', '程序服三(任)', 'root', '123456', '172.16.50.1', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('5', '172.16.50.14', '8000', '172.16.50.14', '8208', '程序服四(古)', 'root', '123456', '172.16.50.14', '8000', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('6', '172.16.200.143', '8000', '172.16.200.143', '8208', '测试二', 'shiguang', 'shiguang@2015', '172.16.200.143', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('7', '172.16.50.27', '8000', '172.16.50.27', '8208', '程序服五(马)', 'shiguang', 'shiguang@2015', '172.16.50.27', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_server` VALUES ('8', '127.0.0.1', '8000', '127.0.0.1', '8208', '本机', 'shiguang', 'shiguang@2015', '127.0.0.1', '3306', 'shiguang', '', '', '', '', '', '');
INSERT INTO `gmt_user` VALUES ('1', 'admin', '管理员', '123', '96e79218965eb72c92a549dd5a330112', '', '1450232754', '127.0.0.1', '2587', '8888', 'liu21st@gmail.com', '备注信息', '1222907803', '1326266696', '1', '0', '');
