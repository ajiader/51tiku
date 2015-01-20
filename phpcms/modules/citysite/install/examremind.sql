DROP TABLE IF EXISTS `phpcms_examremind`;
CREATE TABLE IF NOT EXISTS `phpcms_examremind` (
 `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `bmrk_url` varchar(200) NOT NULL DEFAULT '0',
  `zkz_url` varchar(200) NOT NULL DEFAULT '0',
  `cjcx_url` varchar(200) NOT NULL DEFAULT '0',
  `zwb_url` varchar(200) NOT NULL DEFAULT '0',
  `bmtime` varchar(200) NOT NULL DEFAULT '0',
  `kstime` varchar(200) NOT NULL DEFAULT '0',
  `lztime` varchar(200) NOT NULL DEFAULT '0',
  `cjcxtime` varchar(200) NOT NULL DEFAULT '0',
  `mstime` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `bmzt_url` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `cityid` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;