DROP TABLE IF EXISTS `phpcms_renshibaoming`;
CREATE TABLE IF NOT EXISTS `phpcms_renshibaoming` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL,
  `username` char(50) NOT NULL DEFAULT '0',
   `url` char(50) NOT NULL,
   `cityid` smallint(5) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` smallint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;