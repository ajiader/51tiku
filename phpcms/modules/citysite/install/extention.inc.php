<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'citysite', 'parentid'=>29, 'm'=>'citysite', 'c'=>'citysite', 'a'=>'init', 'data'=>'', 'listorder'=>20, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'kaoshitx_add', 'parentid'=>$parentid, 'm'=>'citysite', 'c'=>'citysite', 'a'=>'kaoshitx_add', 'data'=>'', 'listorder'=>1, 'display'=>'0'));
$menu_db->insert(array('name'=>'kaoshitx_edit', 'parentid'=>$parentid, 'm'=>'citysite', 'c'=>'citysite', 'a'=>'kaoshitx_edit', 'data'=>'', 'listorder'=>2, 'display'=>'0'));
$menu_db->insert(array('name'=>'kaoshitx_del', 'parentid'=>$parentid, 'm'=>'citysite', 'c'=>'citysite', 'a'=>'kaoshitx_del', 'data'=>'', 'listorder'=>3, 'display'=>'0'));

$language = array('citysite'=>'地方站系统', 'kaoshitx_add'=>'添加考试提醒', 'kaoshitx_edit'=>'编辑考试提醒',  'kaoshitx_del'=>'删除考试提醒');
?>