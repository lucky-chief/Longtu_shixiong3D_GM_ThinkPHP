/*
MySQL Data Transfer
Source Host: localhost
Source Database: gc
Target Host: localhost
Target Database: gc
Date: 2015/11/19 14:30:26
*/

-- ----------------------------
-- Table structure for gc_passport
-- ----------------------------
DROP TABLE IF EXISTS `gc_passport`;
CREATE TABLE `gc_passport` (
  `passportId` varchar(64) NOT NULL COMMENT '用户唯一通行证号',
  `serverId` int(11) unsigned NOT NULL COMMENT '服务器ID',
  `level` int(11) unsigned NOT NULL COMMENT '账号等级',
  `lastLoginTime` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  PRIMARY KEY (`passportId`, `serverId`),
  KEY `passportId` (`passportId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
