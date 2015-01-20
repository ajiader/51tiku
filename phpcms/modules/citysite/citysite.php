<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);

class citysite extends admin 
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('examremind_model');
		$this->linkage_db = pc_base::load_model('linkage_model');
		$this->siteid = $this->get_siteid();
	}
    
	public function init() 
	{
		$big_menu = array('javascript:window.top.art.dialog({id:\'kaoshitx_add\',iframe:\'?m=citysite&c=citysite&a=kaoshitx_add\', title:\'添加考试提醒\', width:\'500\', height:\'400\', lock:true}, function(){var d = window.top.art.dialog({id:\'kaoshitx_add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'kaoshitx_add\'}).close()});void(0);', '添加考试提醒');
		$where = 'siteid='.$this->siteid;
		
		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);
		$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'),'linkageid,name');
		foreach ($linkage_list as $v) {
  			$linkagelist[$v['linkageid']] = $v;
  		}
  		$CATEGORYS = getcache('category_content_'.$this->siteid,'commons');
		include $this->admin_tpl('citysite_list');
	}

    /*
	*添加数据
	*/
	public function kaoshitx_add()
	{
		
		
		$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'));
		if($_POST['dosubmit'])
		{
			$data = $_POST['citysite'];
			$info = array();
			foreach($data as $k => $v)
			{
				$info['siteid'] = $this->siteid;
				$info[$k] = safe_replace($v);
				
			}
			$id = $this->db->insert($info, true);
			if($id > 0)
			{
				showmessage(('添加成功'), HTTP_REFERER, '', 'add');
			}
		}
		$show_header = $show_validator = $show_scroll = 1;
		include $this->admin_tpl('kaoshitx_add');
	}

	/*
	*编辑数据
	*/
    public function kaoshitx_edit()
	{
		$_GET['id'] = intval($_GET['id']);
		$data = $_POST['citysite'];
		if(!$_GET['id']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$info = array();
			foreach($data as $k => $v)
			{
				$info[$k] = safe_replace($v);
				
			}
			if($this->db->update($data, array('id' => $_GET['id']))) showmessage(('编辑成功！'), HTTP_REFERER, '', 'edit');
		}else {
			$linkage_list = $this->linkage_db->select(array('parentid'=>'0','child'=>'1'));
			$where = array('id' => $_GET['id']);
			$info = $this->db->get_one($where);

            $show_header = $show_validator = $show_scroll = 1;
			include $this->admin_tpl('kaoshitx_edit');
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
				showmessage(L('citysite_deleted'), HTTP_REFERER);
			} elseif($id) {
				$id = intval($id);
				$this->db->delete(array('id' => $id));
			}
		}
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
}
?>