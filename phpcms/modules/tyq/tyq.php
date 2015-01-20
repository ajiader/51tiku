<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);

class tyq extends admin 
{
	private $db;
	public function __construct() {
		parent::__construct();
		$this->username = param::get_cookie('admin_username');
		$this->db = pc_base::load_model('tyq_model');

		$this->siteid = $this->get_siteid();
	}

	public function init()
	{
		$catarr = array(
  			'6'=>'公务员',
  			'9'=>'职称英语',
  			'10'=>'职称计算机',
  			'11'=>'职业资格',
			'10000'=>'四川成绩报名-其它免费',
			'10002'=>'四川成绩报名-人事免费',
			'10001'=>'四川成绩报名-付费',
			'100' => '职称英语2014报名'
		);
		$status = isset($_GET['status'])?intval($_GET['status']):'';
		$where="id > 0";
		if($status && isset($status)) $where .= " and status = $status ";
		
		$catid = intval($_GET['catid']);
		if($catid > 0){
		    $where .= " and catid = $catid ";
		}

		$page = max(intval($_GET['page']), 1);
		$list = $this->db->listinfo($where, 'id DESC', $page, 10);
		
		
		include $this->admin_tpl('init');
	}

    
    //已处理逻辑
    function ychuli()
    {
    	$id = intval($_GET['id']);
    	$this->db->update(array('status'=>1),array('id' => $id));
    	showmessage('设置成功！', HTTP_REFERER);
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