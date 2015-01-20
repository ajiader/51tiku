<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);


class seo extends admin
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('seo_model');
		$this->type_db = pc_base::load_model('type_model');
		$this->siteid = $this->get_siteid();
		$this->big_menu = array('javascript:window.top.art.dialog({id:\'kaoshitx_add\',iframe:\'?m=citysite&c=citysite&a=kaoshitx_add\', title:\'添加考试提醒\', width:\'500\', height:\'400\', lock:true}, function(){var d = window.top.art.dialog({id:\'kaoshitx_add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'kaoshitx_add\'}).close()});void(0);', '添加考试提醒');
	}

	public function init() 
	{
		$big_menu = $this->big_menu ;
		$where = 'siteid='.$this->siteid;
		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);
        $type_list = $this->type_db->select(array('module'=>'cityweb','siteid'=>$this->siteid));
		foreach ($type_list as $v) {
  			$typelist[$v['typeid']] = $v;
  		}
		include $this->admin_tpl('seo_list');
	}

	public function add()
	{
        $big_menu = $this->big_menu ;
		$type_list = $this->type_db->select(array('module'=>'cityweb','siteid'=>$this->siteid));
	    if($_POST['dosubmit'])
		{
			$info = $_POST['info'];
			$data = array();
			foreach($info as $k => $v)
			{
				$data['siteid'] = $this->siteid;
				$data[$k] = trim($v);
				
			}
			
			$id = $this->db->insert($data, true);
			echo $id;
			if($id > 0)
			{
				showmessage('添加成功', '?m=citysite&c=seo&a=init', '3', 'add');
			}
		}
		//$show_header = $show_validator = $show_scroll = 1;
		include $this->admin_tpl('seo_add');
	}
    
	public function edit()
	{
		$big_menu = $this->big_menu ;
		$type_list = $this->type_db->select(array('module'=>'cityweb','siteid'=>$this->siteid));
		$_GET['id'] = intval($_GET['id']);
		if(isset($_POST['dosubmit'])) {
		    $data = $_POST['info'];
			$info = array();
			foreach($data as $k => $v)
			{
				$info[$k] = (trim($v));
				
			}
			if($this->db->update($info, array('id' => $_GET['id'])))
				showmessage('编辑成功！', '?m=citysite&c=seo&a=init');
		}
		if(!$_GET['id']) showmessage(L('illegal_operation'));
		$where = array('id' => $_GET['id']);
		$r = $this->db->get_one($where);

		include $this->admin_tpl('seo_edit');
	}

	public function delete($id = 0) {
		if((!isset($_POST['id']) || empty($_POST['id'])) && !$id) {
			showmessage(L('illegal_operation'));
		} else {
			if(is_array($_POST['id']) && !$id) {
				array_map(array($this, 'delete'), $_POST['id']);
				showmessage('删除成功！', HTTP_REFERER);
			} elseif($id) {
				$id = intval($id);
				$this->db->delete(array('id' => $id));
			}
		}
	}
}
?>