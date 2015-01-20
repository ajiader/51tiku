<?php
/**
 *  linkfield.php 异步获取数据
 *
 * @contact			    qq:408296852
 * @license				http://www.iphpcms.com/
 * @lastmodify			2011-7-11 17:03
 *kapi.php?op=ajax_my_linkfield&act=search_ajax&value=nn&table_name=v9_category&select_title=catid,catname&like_title=catname&set_title=catname
 */
defined('IN_PHPCMS') or exit('No permission resources.');

switch($_GET['act']) 
{
	case 'search_ajax':
		search_ajax();
	break;
	case 'check_search':
		check_search();
	break;
	case 'search_data':
		search_data();	
	break;
}
function search_ajax() {
	$db = pc_base::load_model('get_model');
	$siteid = get_siteid();
	$data = array();
	//print_r($_GET);
	if($_GET['value']) 
	{
		$value = trim($_GET['value']);
		if (CHARSET == 'gbk') $value = iconv('utf-8','gbk',$value);
		$table_name = trim($_GET['table_name']);
		$db->query("SHOW COLUMNS FROM $table_name");
		while($r = $db->fetch_next()) {
			if($r['Key'] == 'PRI') break;
		}
		$order_id = $r['Field'];
		$select_title = $_GET['select_title'] ? safe_replace(trim($_GET['select_title'])) : '*';
		$like_title = $_GET['like_title'] ? safe_replace(trim($_GET['like_title'])) : 'title';
		$set_title = safe_replace(trim($_GET['set_title']));
		$limit =  $_GET['limit'] ? safe_replace(trim($_GET['limit'])) : '20';
		$sql = "SELECT $select_title from $table_name where $like_title LIKE('%$value%') order by $order_id desc limit 0,$limit";
		// echo ($sql);
		$result = $db->query($sql);
		while(($s = $db->fetch_next()) != false) 
		{
			$data[] = $s;
		}
		if (CHARSET == 'gbk') 
		{
			$data = array_iconv($data, 'gbk', 'utf-8');
		}
		// print_r($data);
		echo $_GET['callback'].'(',json_encode($data),')';
	}
}
function check_search() {
	$get_db = pc_base::load_model('get_model');
	$siteid = get_siteid();

	if($_GET['value']) {
	
		$value = trim($_GET['value']);	
		if (CHARSET == 'gbk') $value = iconv('utf-8','gbk',$value);
		$table_name = trim($_GET['table_name']);
		$set_type = trim($_GET['set_type']);
		$set_id = trim($_GET['set_id']);
		$set_title = trim($_GET['set_title']);
		$limit =  $_GET['limit'] ? trim($_GET['limit']) : '20';	
		$get_db->query("SHOW COLUMNS FROM $table_name");
		while($rs = $get_db->fetch_next()) {
			if($rs['Key'] == 'PRI') break;
		}
		$order_id = $rs['Field'];

		if($set_type == 'id'){
			$sqls = $set_id.' = '.$value;
		}
		elseif($set_type == 'title'){
			$sqls = $set_title.' = '.$value;
		}
		elseif($set_type == 'title_id'){
		$value = explode('_', $value);
		$sqls = $set_title.' = \''.$value[0].'\' AND '.$set_id.' = '.$value[1];
		}
		
		$dataArr = array();
		$sqld = "SELECT $set_title from $table_name where $sqls order by $order_id desc limit 0,1";

		$r= $get_db->query($sqld);
		while(($s = $get_db->fetch_next()) != false) {
			$dataArr[] = $s;
		}
		if (CHARSET == 'gbk') 
		{
			$dataArr = array_iconv($dataArr, 'gbk', 'utf-8');
		}
		echo $_GET['callback'].'(',json_encode($dataArr),')';
	}
}
function search_data() {

	$database = pc_base::load_config('database');
	pc_base::load_sys_class('db_factory');
	$pdo_name = 'default';
	$tables = $_POST['tables'] ? $_POST['tables'] : trim($_GET['tables']);

	if($tables) 
	{
		$db = db_factory::get_instance($database)->get_database($pdo_name);
		if($db->table_exists($tables)){
		$data=	$db->get_fields($tables);
		echo $_GET['callback'].'(',json_encode($data),')';
		}else{
		exit(0);
		}
	}
}
?>