<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);
pc_base::load_app_func('global');
class zr_admin extends admin 
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('zirui_mokuai_model');
		$this->search_db = pc_base::load_model('zirui_search_model');
		$this->type_db = pc_base::load_model('type_model');
		$this->siteid = $this->get_siteid();
	}

	public function init()
	{
		$statusarr['0']['name'] = '<font color=red>否</font>';
		$statusarr['1']['name'] = '是';
		$where = 'siteid='.$this->siteid;
		$title = safe_replace($_GET['name']);


		//获取所有分类
		$linkage = getcache(3362,'linkage');
		$type_list = $this->linkagearr($linkage['data']);

		if($title) $where .= " and name like '%$title%' ";
		$typeid = intval($_GET['typeid']);
		$typeids = $type_list[$typeid]['arrchildid'] ;
		$status = isset($_GET['status'])?intval($_GET['status']):1;
		if($typeid > 0){
		    $where .= " and typeid in ($typeids) ";
		}
		if( is_numeric($status) ) $where .= " and status = $status ";
		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);

		

		foreach ($type_list as $v) {
  			$typelist[$v['typeid']] = $v;
  		}
		include $this->admin_tpl('zr_list');
	}

	/*
	*添加数据
	*/
	public function addmokuai()
	{
		$data = $_POST['info'];
		$type_list = $this->type_db->select(array('module'=>'zrmokuai','siteid'=>$this->siteid));
		//print_r($type_list);
		if($_POST['dosubmit'])
		{
			$data = $this->check($data);
	
			$id = $this->db->insert($data, true);
			if($id > 0)
			{
				showmessage('添加成功', '?m=zrmokuai&c=zr_admin&a=init', '3', 'add');
			}
		}
		include $this->admin_tpl('zr_add');
	}
    /*
	*编辑数据
	*/
    public function editmokuai()
	{
		$_GET['id'] = intval($_GET['id']);
		$data = $_POST['info'];
		if(!$_GET['id']) showmessage(L('illegal_operation'));
		if(isset($_POST['dosubmit'])) {
			$data = $this->check($data, 'edit');
			if($this->db->update($data, array('id' => $_GET['id']))) showmessage('更新成功！', '?m=zrmokuai&c=zr_admin&a=init', '3', 'edit');
		}else {
			$type_list = $this->type_db->select(array('module'=>'zrmokuai','siteid'=>$this->siteid));
			$where = array('id' => $_GET['id']);
			$vo = $this->db->get_one($where);

            $show_header = $show_validator = $show_scroll = 1;
			include $this->admin_tpl('zr_edit');
		}
	}

		/**
	 * 验证表单数据
	 * @param  		array 		$data 表单数组数据
	 * @param  		string 		$a 当表单为添加数据时，自动补上缺失的数据。
	 * @return 		array 		验证后的数据
	 */
	private function check($data = array(), $a = 'add') {
		if($data['name']=='') showmessage("请输入模块名称");
		//if($data['name_num']=='') showmessage("请输入模块总题量");
		if($data['type_num']=='') showmessage('请输入题型及题量');
		$r = $this->db->get_one(array('name' => $data['name'], 'siteid' => $this->siteid));
		
		if ($a=='add') {
			if (is_array($r) && !empty($r)) {
				showmessage('模块名称已经存在', HTTP_REFERER);
			}
			$data['siteid'] = $this->get_siteid();
		} else {
			if ($r['id'] && ($r['id']!=$_GET['id'])) {
				showmessage('模块名称已经存在', HTTP_REFERER);
			}
		}
        //增加首字母
        $py = pc_base::load_app_class('PYInitials');
        $data['py']= substr($py->getInitials($data['name']), 0, 1);
 
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
		$title = $_GET['name'];
		if ($_GET['id']) {
			$r = $this->db->get_one(array('id' => $_GET['id']));
			if ($r['name'] == $title) {
				exit('1');
			}
		} 
		$r = $this->db->get_one(array('siteid' => $this->get_siteid(), 'name' => $title), 'id');
		if($r['id']) {
			exit('0');
		} else {
			exit('1');
		}
	}

	/**
	 * 批量删除
	 */
	public function delete($id = 0) {
		if((!isset($_POST['id']) || empty($_POST['id'])) && !$id) {
			showmessage('未选择数据');
		} else {
			if(is_array($_POST['id']) && !$id) {
				array_map(array($this, 'delete'), $_POST['id']);
				showmessage(L('删除成功！'), HTTP_REFERER);
			} elseif($id) {
				$id = intval($id);
				$this->db->delete(array('id' => $id));
			}
		}
	}

	//用户搜索日志
	public function searchinit()
	{
		$page = max(intval($_GET['page']), 1);
		$list = $this->search_db->listinfo('', 'id DESC', $page, 10);
		include $this->admin_tpl('search_list');
	}
	
	/**
	 * 批量删除搜索日志
	 */
	public function searchdelete($id = 0) {
		if((!isset($_POST['id']) || empty($_POST['id'])) && !$id) {
			showmessage('未选择数据');
		} else {
			if(is_array($_POST['id']) && !$id) {
				array_map(array($this, 'searchdelete'), $_POST['id']);
				showmessage(L('删除成功！'), HTTP_REFERER);
			} elseif($id) {
				$id = intval($id);
				$this->search_db->delete(array('id' => $id));
			}
		}
	}


	function linkagearr($array, $count)
	{
		$linkagearr = array();
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if ($value['parentid']>0) {
					$linkagearr[$value['parentid']]['zilei'][$key] = $value;
					if ($count==1) {//统计数据
						$linkagearr[$value['parentid']]['zilei'][$key]['count']['count_num']=$this->tongji($value['arrchildid'],'count_num','count'); //模块数量
						$linkagearr[$value['parentid']]['zilei'][$key]['count']['name_num']=$this->tongji($value['arrchildid'],'name_num'); //总题量
					}
				}else{
					$linkagearr[$key] = $value;
					if ($count==1) {//统计数据
						$linkagearr[$key]['count']['count_num']=$this->tongji($value['arrchildid'],'count_num','count'); //模块数量
						$linkagearr[$key]['count']['name_num']=$this->tongji($value['arrchildid'],'name_num'); //总题量
					}
					
				}
				
			}
		}
		// print_r($linkagearr);
		return($linkagearr);

	}
    
}