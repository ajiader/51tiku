<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
set_time_limit(0);
class memcache_all extends admin {
	function __construct() 
	{
		parent::__construct();
		pc_base::load_sys_class('cache_factory','',0);
		$cacheconfig = pc_base::load_config('cache');
		$this->mem = cache_factory::get_instance($cacheconfig)->get_cache('template');
	}
    public function init() 
	{
		$siteid = $this->get_siteid();
		//$array = $this->getAllMemInfo('search',$searchkey,$searchtype,$delflag);
		//getMemcachePager($page = 1, $size = 30,$searchkey = '',$searchtype = 1,$delflag = '')
		$page = max(intval($_GET['page']), 1); //页码
		$pagesize = 50; //单页显示条数
		$searchkey = str_replace('http://', '', siteurl($siteid) );
		$searchkey = isset($_GET['searchkey'])?$searchkey.$_GET['searchkey']:$searchkey;
		$searchkeys = str_replace('/','\/',$searchkey);
		$list = $this->mem->getMemcachePager($page,$pagesize,$searchkeys,-1);
		include $this->admin_tpl('mem_list');
	}

	//删除所有页面缓存
	public function del_mem() 
	{
		$this->mem->flush();
		getMemcacheKeys();
		showmessage('成功删除所有Memcache页面缓存!', HTTP_REFERER);
	}
    
	//删除选中
	public function delete($id = 0)
	{
		if((!isset($_POST['id']) || empty($_POST['id'])) && !$id) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['id']) && !$id) {
				array_map(array($this, 'delete'), $_POST['id']);
				showmessage('删除成功', HTTP_REFERER);
			} elseif($id) {
				delcache($id,'','memcache','template');
			}
		}
	}
	//删除单个
	public function deleteone($id = 0)
	{
		$id = isset($_GET['id'])?$_GET['id']:0;
		delcache($id,'','memcache','template');
		showmessage('删除成功', HTTP_REFERER);
	}
}