DROP TABLE IF EXISTS `phpcms_zirui_mokuai`;
CREATE TABLE `phpcms_zirui_mokuai` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT '模块名称',
  `name_num` varchar(20) NOT NULL COMMENT '模块总题量',
  `type_num` varchar(200) NOT NULL COMMENT '题型及题量',
  `content_1` varchar(200) DEFAULT NULL COMMENT '“我要练习”中包含科目名称及题量',
  `content_2` varchar(50) DEFAULT NULL COMMENT '模拟试卷套数及题量',
  `content_3` varchar(200) DEFAULT NULL COMMENT '历年真题套数及题量',
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;