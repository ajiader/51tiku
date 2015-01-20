<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);

class teacher extends admin 
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('zhuanjia_model');
		$this->type_db = pc_base::load_model('type_model');
		$this->siteid = $this->get_siteid();
		$this->type_list = $this->type_db->select(array('module'=>'teacher', 'siteid'=>$this->siteid));
	}

	public function init()
	{
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m='.ROUTE_M.'&c='.ROUTE_C.'&a=add\', title:\'添加老师\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '添加老师');

		$where = 'siteid='.$this->siteid;
		$name = safe_replace($_GET['name']);
		if($name) $where .= " and name like '%$name%' ";
		$typeid = intval($_GET['typeid']);
		if($typeid > 0){
		    $where .= " and typeid = $typeid ";
		}
		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);
		$type_list = $this->type_list;
		foreach ($type_list as $v) {
  			$typelist[$v['typeid']] = $v;
  		}
		
		include $this->admin_tpl('init');
	}


    /**
	*添加数据
    */
	public function add()
	{
		$data = $_POST['teacher'];
		if(isset($_POST['dosubmit'])) {
			$data = $this->check($data);
			if($this->db->insert($data, true))
			{
				showmessage('添加数据成功！', HTTP_REFERER, '', 'add');
			}
		}else {
			$type_list = $this->type_list;
			foreach ($type_list as $v) {
  				$typelist[$v['typeid']] = $v['name'];
  			}
            $show_header = $show_validator = $show_scroll = 1;
			include $this->admin_tpl('form');
		}
	}

	/*
	*编辑数据
	*/
    public function edit()
	{
		$id = intval($_GET['id']) ? intval($_GET['id']) : (intval($_POST['id']) ? intval($_POST['id']) : showmessage('错误，该数据不存在！'));
		$data = $_POST['teacher'];
		if(isset($_POST['dosubmit'])) {
			$data = $this->check($data, 'edit');
			if($this->db->update($data, array('id' => $id))) showmessage(L('teacherd_a'), HTTP_REFERER, '', 'edit');
		}else {
			
			$type_list = $this->type_list;
			foreach ($type_list as $v) {
  				$typelist[$v['typeid']] = $v['name'];
  			}
  
			$where = array('id' => $_GET['id']);
			$info = $this->db->get_one($where);

            $show_header = $show_validator = $show_scroll = 1;
			include $this->admin_tpl('form');
		}
	}

	/**
	 * 批量删除
	 */
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
/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($data['name']=='') showmessage('专家姓名不能为空！');
		$r = $this->db->get_one(array('name' => $data['name'], 'siteid' => $this->siteid));
		$data['addtime'] = SYS_TIME;
		$data['name'] = safe_replace( $data['name']);
		$data['title'] = safe_replace( $data['title']);
		$data['description'] = safe_replace( $data['description'] );
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage('专家姓名已经存在', HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
			
		} else {
			if ($r['id'] && ($r['id']!=$_GET['id'])) {
				showmessage('专家姓名已经存在', HTTP_REFERER);
			}

		}
		return $data;
	}

	/**
	 * ajax检测公告标题是否重复
	 */
	public function public_check_title() {
		if (!$_GET['name']) exit(0);
		if (CHARSET=='gbk') {
			$_GET['name'] = iconv('UTF-8', 'GBK', $_GET['name']);
		}
		$name = $_GET['name'];
		if ($_GET['id']) {
			$r = $this->db->get_one(array('id' => $_GET['id']));
			if ($r['name'] == $name) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'name' => $name), 'id');
		if($r['id']) {
			exit('0');
		} else {
			exit('1');
		}
	}
}