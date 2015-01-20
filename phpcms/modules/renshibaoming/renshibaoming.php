<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);

class renshibaoming extends admin 
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('renshibaoming_model');
		$this->linkage_db = pc_base::load_model('linkage_model');
		$this->type_db = pc_base::load_model('type_model');
		$this->siteid = $this->get_siteid();
	}
    
	public function init() 
	{
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=renshibaoming&c=renshibaoming&a=add\', title:\''.L('renshibaoming_add').'\', width:\'700\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('renshibaoming_add'));
		$where = 'siteid='.$this->siteid;
		$where .= ' and status = 1';
		$title = safe_replace($_GET['name']);
		if($title) $where .= " and title like '%$title%' ";
		$typeid = intval($_GET['typeid']);
		if($typeid > 0){
		    $where .= " and typeid = $typeid ";
		}
		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);
		$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'),'linkageid,name');
		foreach ($linkage_list as $v) {
  			$linkagelist[$v['linkageid']] = $v;
  		}

		$type_list = $this->type_db->select(array('module'=>'frame'));
		foreach ($type_list as $v) {
  			$typelist[$v['typeid']] = $v;
  		}
		include $this->admin_tpl('renshibaoming_list');
	}

    /*
	*添加数据
	*/
	public function add()
	{
		$data = $_POST['renshibaoming'];
		$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'));
		$type_list = $this->type_db->select(array('module'=>'frame'));
		//print_r($type_list);
		if($_POST['dosubmit'])
		{
			$data = $this->check($data);
			$id = $this->db->insert($data, true);
			if($id > 0)
			{
				showmessage(L('renshibaomingment_successful_added'), HTTP_REFERER, '', 'add');
			}
		}
		$show_header = $show_validator = $show_scroll = 1;
		include $this->admin_tpl('add');
	}

	/*
	*编辑数据
	*/
    public function edit()
	{
		$_GET['id'] = intval($_GET['id']);
		$data = $_POST['renshibaoming'];
		if(!$_GET['id']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$data = $this->check($data, 'edit');
			if($this->db->update($data, array('id' => $_GET['id']))) showmessage(L('renshibaomingd_a'), HTTP_REFERER, '', 'edit');
		}else {
			$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'));
			$type_list = $this->type_db->select(array('module'=>'frame'));
			$where = array('id' => $_GET['id']);
			$info = $this->db->get_one($where);

            $show_header = $show_validator = $show_scroll = 1;
			include $this->admin_tpl('edit');
		}
	}

	/**
	 * 批量删除公告
	 */
	public function delete($id = 0) {
		if((!isset($_POST['id']) || empty($_POST['id'])) && !$id) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['id']) && !$id) {
				array_map(array($this, 'delete'), $_POST['id']);
				showmessage(L('renshibaoming_deleted'), HTTP_REFERER);
			} elseif($id) {
				$id = intval($id);
				$this->db->delete(array('id' => $id));
			}
		}
	}

	/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($data['title']=='') showmessage(L('input_renshibaoming_title'));
		if($data['url']=='') showmessage(L('input_renshibaoming_url'));
		if($data['cityid']=='') showmessage(L('input_renshibaoming_city'));
		$r = $this->db->get_one(array('title' => $data['title'], 'siteid' => $this->siteid));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage(L('renshibaoming_exist'), HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			$data['addtime'] = SYS_TIME;
			$data['username'] = $this->username;
			$data['status'] = 1;
		} else {
			if ($r['id'] && ($r['id']!=$_GET['id'])) {
				showmessage(L('renshibaoming_exist'), HTTP_REFERER);
			}
		}
		return $data;
	}

	/**
	 * ajax检测公告标题是否重复
	 */
	public function public_check_title() {
		if (!$_GET['title']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['title'] = iconv('UTF-8', 'GBK', $_GET['title']);
		}
		$title = $_GET['title'];
		if ($_GET['id']) {
			$r = $this->db->get_one(array('id' => $_GET['id']));
			if ($r['title'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'title' => $title), 'id');
		if($r['id']) {
			exit('0');
		} else {
			exit('1');
		}
	}

	public function public_check_filename() {
		if ($_GET['filename'] == '') {echo(1);exit;}
		if (CHARSET=='gbk') {
			$_GET['filename'] = iconv('UTF-8', 'GBK', $_GET['filename']);
		}
		$filename = $_GET['filename'];
		if ($_GET['id']) {
			$r = $this->db->get_one(array('id' => $_GET['id']));
			if ($r['filename'] == $filename) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'filename' => $filename), 'id');
		if($r['id']) {
			exit('0');
		} else {
			exit('1');
		}
	}
}
?>