<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
class zhiruitiku {
	function __construct() {
		$this->db = pc_base::load_model('zirui_mokuai_model');
		// $this->type_db = pc_base::load_model('type_model');
		$this->search_db = pc_base::load_model('zirui_search_model');
		pc_base::load_sys_class("get_model", "model", 0);
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$this->siteid = $siteid;
		$this->zmarr =  range('a','z'); //初始化26个字母
		$this->marr =  range('1','12'); //初始化月份
	}
	
	function linkagearr($array, $count="")
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
	public function init()
	{
		// 数据初始化
		$siteid = $this->siteid;
		
		//获取所有分类
		$linkage = getcache(3362,'linkage');
		$type_list = $this->linkagearr($linkage['data']);

		$where = '';
		$typeid = intval($_GET['typeid']); //获取分类id
		$typelist = getcache(3362,'linkage');
		if(is_numeric($typeid) && $typeid>0) { 
			if($type_list[$typeid]['child'] == 1){
				$arrchildid = $type_list[$typeid]['arrchildid'];
				$where .= "and  typeid in ($arrchildid) ";
			}else{
				$where .= "and  typeid=$typeid ";
			}
			
		}
        //查询开始
		$zm = strtoupper(substr($_GET['zm'], 0, 1));   //获取字母
		if($zm){
			$where .= "and py='$zm'";
		}
        
		$kstime = intval($_GET['kstime']); //获取报名时间
        if ($kstime) {
        	$kstime .= '月';
        	$where .= "and  kstime like '%$kstime%' ";
        }
        
        $start_time = safe_replace($_GET['start_time']); //添加时间查询
        $end_time = safe_replace($_GET['end_time']); //添加end时间查询
        if(($end_time > $start_time) and !empty($start_time) and !empty($end_time))
        {
        	$where .= "and  DATE(updatetime) >= '$start_time' and DATE(updatetime) <= '$end_time' ";
        }

		$typeurl =  get_url();
		
		// echo $where;
		// $type_list = $this->type_db->select(array('module'=>'zrmokuai','siteid'=>$this->siteid));
		
		// print_r($type_list);
		$q = safe_replace($_GET['q']);
		if($q) $where .= "and  name like '%$q%' ";
		$page = intval($_GET['page']);
		$page = max($page,1);
		$pagesize = 10; //分页条数
		$datas = $infos = array();
		$infos = $this->db->listinfo("siteid=1 $where",'updatetime asc',$page,$pagesize,'','9',$urlrule,Array('pinyin'=>$cityList['pinyin'],'catids'=>$catids,'name'=>urlencode($name))); 
		$pages = $this->db->pages; 
		
        //将没有查询到数据的关键词写入数据表
		if($this->db->number <= 0 && strlen($q) >=6 )
		{
			$searlist = $this->search_db->get_one(array('title'=>$q));
			if(!$searlist){
			$data['title'] = $q;
			$data['ip'] =  ip();
			$data['addtime'] = time();
			$this->search_db->insert($data);
			}
		}
		//$SEO = seo($siteid, '',KSBM_TITLE);

		ob_start();

		include template('zhiruitiku',  'index');
		
		
		echo mem_page(1);
	}

    //类型列表页面
    public function typelist()
    {
    	$typeid = intval($_GET['typeid']); //获取分类id
    	// if(!$typeid) showmessage('栏目参数错误');
    	 // 数据初始化
		$siteid = $this->siteid;
		//获取所有分类
		$linkage = getcache(3362,'linkage');
		$type_list = $this->linkagearr($linkage['data'],1);
		if($typeid>0) {$typeArr = $type_list[$typeid];}

		$where = '';
		$typeid = intval($_GET['typeid']); //获取分类id
		$typelist = getcache(3362,'linkage');
		if(is_numeric($typeid) && $typeid>0) { 
			if($type_list[$typeid]['child'] == 1){
				$arrchildid = $type_list[$typeid]['arrchildid'];
				$where .= "and  typeid in ($arrchildid) ";
			}else{
				$where .= "and  typeid=$typeid ";
			}
			
		}
        //查询开始
		$zm = strtoupper(substr($_GET['zm'], 0, 1));   //获取字母
		if($zm){
			$where .= "and py='$zm'";
		}
        
		$kstime = intval($_GET['kstime']); //获取报名时间
        if ($kstime) {
        	$kstime .= '月';
        	$where .= "and  kstime like '%$kstime%' ";
        }
        
        $start_time = safe_replace($_GET['start_time']); //添加时间查询
        $end_time = safe_replace($_GET['end_time']); //添加end时间查询
        if(($end_time > $start_time) and !empty($start_time) and !empty($end_time))
        {
        	$where .= "and  DATE(updatetime) >= '$start_time' and DATE(updatetime) <= '$end_time' ";
        }

		$typeurl =  get_url();
		
		// echo $where;
		// $type_list = $this->type_db->select(array('module'=>'zrmokuai','siteid'=>$this->siteid));
		
		// print_r($type_list);
		$q = trim(safe_replace($_GET['q']));
		if($q) $where .= "and  name like '%$q%' ";
		$page = intval($_GET['page']);
		$page = max($page,1);
		$pagesize = 10; //分页条数
		$datas = $infos = array();
		$infos = $this->db->listinfo("siteid=1 and status=1 $where",'updatetime asc',$page,$pagesize,'','9',$urlrule,Array('pinyin'=>$cityList['pinyin'],'catids'=>$catids,'name'=>urlencode($name))); 
		$pages = $this->db->pages; 
		// ob_start();
		include template('zhiruitiku',  'typelist');
		// echo mem_page(1);
		
    }

    /*
	 $typeid 栏目id
	 $k 字段名
	 $count 统计方式
	*/
	function tongji($typeid,$k,$type='sum')
	{
		if (!$typeid) {
			return false;
		}
		$get_db = new get_model();
		if ($type == 'count' ) {
			$count = "COUNT(*) as $k";
		}elseif($type == 'sum' ){
			$count = "SUM($k) as $k";
		}
		$sql = "SELECT $count  FROM v9_zirui_mokuai where typeid in ($typeid)";
		// echo($sql."<br></br>");
		$r = $get_db->sql_query($sql);
		$s = $get_db->fetch_next();
		return $s[$k];
	}


	//展示页面
	public function show()
	{
		 // 数据初始化
		$siteid = $this->siteid;

		$typeid = intval($_GET['typeid']); //获取分类id
		$pinyin = safe_replace($_GET['pinyin']);

		//获取所有分类
		$linkage = getcache(3362,'linkage');
		$type_list = $this->linkagearr($linkage['data']);

		$id = intval($_GET['id']);
		if(!$id && !$typearr && !$pinyin) showmessage('参数错误！','blank');
        if ($id >0) {
        	$r = $this->db->get_one(array('id'=>$id));
        }elseif ($pinyin) {
        	$r = $this->db->get_one(array('pinyin'=>$pinyin));
        }
		
		if( $r['typeid'] > 0){

			if (in_array($r['typeid'], array(3365,3371,3372,3373,3374))) {
				$r['typeid'] = 3364;
			}
		  $typeArr = $type_list[$r['typeid']];
		}
		ob_start();
		include template('zhiruitiku',  'show');
		echo mem_page(1);
	}
	

}
?>