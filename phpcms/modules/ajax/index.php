<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	function __construct() {
		
	}
	
	public function init() {
		
	}
	
	/*
    *体验区数据提交处理
	*/
	public function tyqpost()
	{
		$db = pc_base::load_model('tyq_model');
		$data['catid'] = isset($_POST['catid'])?intval($_POST['catid']):'0';
		$data['name'] = isset($_POST['name'])?safe_replace(trim($_POST['name'])):'0';
		$data['phone'] = isset($_POST['phone'])?safe_replace(trim($_POST['phone'])):'0';
		$data['qq'] = isset($_POST['qq'])?safe_replace(trim($_POST['qq'])):'0';
		$data['zkzh'] = isset($_POST['zkzh'])?safe_replace(trim($_POST['zkzh'])):'0';
		$data['email'] = isset($_POST['email'])?safe_replace(trim($_POST['email'])):'0';
		$data['province'] = isset($_POST['province'])?safe_replace(trim($_POST['province'])):'';
		$data['province'] = str_replace("　","",$data['province']);
		$data['ip'] =  ip();
		$data['inputtime'] =  time();
		// print_r($data);exit();
		if ($data['catid']>0 && $data['name'] && $data['phone']) {
			//判断重复
			if ($db->get_one("catid='".$data['catid']."' and name='".$data['name']."' and phone='".$data['phone']."'")) {
				die('-1');
			}
			//写入对应数据
			$id = $db->insert($data,true);
			if($id>0) die('1');
		}else{
			die('0');
		}
		
	}
// ajax智能提示模块名称
	public function getzrmkname()
	{
		$thisdb = pc_base::load_model('zirui_mokuai_model');
		$q = trim(safe_replace($_REQUEST['keyword']));
		if($q and strlen($q) >= 6 ) {
			$where .= "name like '%$q%' and status=1 limit 10";
			$jsonlist = $thisdb->select($where,"name");
			if ($jsonlist) {
				foreach ($jsonlist as $key => $value) {
					$data['data'][]['title'] = $value['name'];
				}
			}
		}
		// print_r($data);
		// echo('{"data":[{"title":"book"},{"title":"blue"},{"title":"bus"}]}');
		echo json_encode($data);
	}

/*ajax分页*/
	public function homeajaxlist() {
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$page = $_GET['page']?intval($_GET['page']):1;
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		define('SITEID', $siteid);
		$catid = $_GET['catid']?intval($_GET['catid']):1;
		$_userid = $this->_userid;
		$_username = $this->_username;
		$_groupid = $this->_groupid;
		//SEO
		$SEO = seo($siteid);
		$sitelist = getcache('sitelist','commons');
		$default_style = $sitelist[$siteid]['default_style'];
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		include template('content','php_ajax_list',$default_style);
	}
}
?>