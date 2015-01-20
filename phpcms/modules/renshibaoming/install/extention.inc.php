<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'renshibaoming', 'parentid'=>29, 'm'=>'renshibaoming', 'c'=>'renshibaoming', 'a'=>'init', 'data'=>'', 'listorder'=>20, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'renshibaoming_add', 'parentid'=>$parentid, 'm'=>'renshibaoming', 'c'=>'renshibaoming', 'a'=>'add', 'data'=>'', 'listorder'=>1, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_renshibaoming', 'parentid'=>$parentid, 'm'=>'renshibaoming', 'c'=>'renshibaoming', 'a'=>'edit', 'data'=>'s=1', 'listorder'=>2, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_renshibaoming', 'parentid'=>$parentid, 'm'=>'renshibaoming', 'c'=>'renshibaoming', 'a'=>'delete', 'data'=>'', 'listorder'=>3, 'display'=>'0'));

$language = array('renshibaoming'=>'人事报名', 'renshibaoming_add'=>'添加', 'edit_renshibaoming'=>'编辑',  'del_renshibaoming'=>'删除');
?>