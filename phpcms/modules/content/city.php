<?php
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_func('global','content');
class city
{
	private $db;
	function __construct() {
		$this->db = pc_base::load_model('content_model');
		$this->city_db = pc_base::load_model('linkage_model');
		$this->cat_db = pc_base::load_model('category_model');
		$this->_userid = param::get_cookie('_userid');
		$this->_username = param::get_cookie('_username');
		$this->_groupid = param::get_cookie('_groupid');
		$this->seo_db = pc_base::load_model('seo_model');
	}
	//首页
	public function init() 
	{

		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		define('SITEID', $siteid);

        //获取城市名称
        $pinyin = safe_replace($_GET['pinyin']);
        $cityList = $this->city_db->get_one(array('pinyin'=>$pinyin));

        if(!$cityList){
        	 @require('/404-1.php');
		     @header('HTTP/1.1 404 Not Found');
		     @header('Status: 404 Not Found');
		     exit;
        }
		$arrchildids = $cityList['arrchildid'];
        $cityid = $cityList['linkageid']?$cityList['linkageid']:'0';
       // echo($cityList['parentid']);
        if ($cityList['parentid'] !=0) {
        	//获取当前地区顶级父类
			$topparentid = xp_get_linkage($cityid);
        }else{
        	$topparentid=$cityid; //如果没有上级 就取自己为父级
        }

        //获取下级城市list 
        if ($cityList['child']) {
        	$childCitylist = $this->city_db->select("parentid=$cityid",'linkageid,name,pinyin',50,'listorder asc');
        }else{//没有下级调用平级
        	$childCitylist = $this->city_db->select("parentid=".$cityList['parentid'],'linkageid,name,pinyin',50, 'listorder asc');
        }

		//kaozc.com需要调用栏目id
		$catdir = safe_replace($_GET['catdir']);
		if ($catdir) {
			$catList = $this->cat_db->get_one(array('catdir'=>$catdir));
			extract($catList);
			$template = 'city_index_'.$catdir;
		}else{
			$template = 'city_index'.$siteid;
		}

		$_userid = $this->_userid;
		$_username = $this->_username;
		$_groupid = $this->_groupid;
		//SEO
		
		if ($siteid==2) {
			$seoid = 26;
		}elseif ($catdir == 'jsj') { //计算机地方站
			$seoid = '33';
		}elseif ($catdir == 'En') { //英语地方站
			$seoid = '59';
		}
		if ($seoid) {
			$seoarr = $this->seo_db->get_one(array('id'=>$seoid,'siteid'=>$siteid));

		   $seokeywords = str_replace(array('{$cityname}','{$catname}'),array(citys2city($cityList['name']),$catname),$seoarr['keywords']);
			$seotitle= str_replace(array('{$cityname}','{$catname}'),array(citys2city($cityList['name']),$catname),$seoarr['title']);
			$seodescription =  str_replace(array('{$cityname}','{$catname}'),array(citys2city($cityList['name']),$catname),$seoarr['description']);
		}
			
		
	    
		$SEO = seo($siteid, '',$seotitle,$seodescription,$seokeywords);

		$sitelist  = getcache('sitelist','commons');
		$default_style = $sitelist[$siteid]['default_style'];
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$cityids = "(city_id in (".$cityList['arrchildid'].") or city_id=0)";
		$cityidd = "city_id in (".$cityList['arrchildid'].") ";
		$cityurlid = "cityid in (".$cityList['arrchildid'].") ";
   
		//加载考试报名信息
		$ksdb = pc_base::load_model('examremind_model');
		$kslist  = $ksdb->get_one(array('cityid'=>$topparentid,'catid'=>$catid, 'siteid'=>$siteid));


		ob_start();
		include template('content',$template,$default_style);
		echo mem_page(1);
	}

   //地方站列表页面
    public function lists()
    {
		//获取城市名称 栏目id 分页 显示名称
        $pinyin = safe_replace($_GET['pinyin']);
		$catids = safe_replace($_GET['catids']);
		$seodb = pc_base::load_model('seo_model');
		$nameid = safe_replace($_GET['nameid']);
		$seoarr = $this->seo_db->get_one(array('id'=>$nameid));
        $name = $seoarr['name'];
		$page = intval($_GET['page']);
		$page = max($page,1);

		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		$catid_arr = explode(',',$catids);
		$cityList = $this->city_db->get_one(array('pinyin'=>$pinyin));
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		$cityids = "(city_id in (".$cityList['arrchildid'].") or city_id=0)";
		$cityidd = "city_id in (".$cityList['arrchildid'].") ";
		foreach($catid_arr as $v)
		{
			$catidss .= $CATEGORYS[$v]['arrchildid'].',';
			$modelid = $CATEGORYS[$v]['modelid'];
		}
		if(!$modelid){
		    if($siteid == 1){
			   $modelid =1;
			}elseif($siteid == 2){
				$modelid =13;
			}
		}
		$MODEL = getcache('model','commons');
		$tablename = $this->db->table_name = $this->db->db_tablepre.$MODEL[$modelid]['tablename'];
		$catidss = substr($catidss,0,-1);
       
     // print_r( get_linkage($cityid,1,'>','5'));

		$urlrules = getcache('urlrules','commons');    
		$urlrule = $urlrules[34];//调用url规则 
		$datas = $infos = array();

        $pagesize = 20; //分页条数
		
		$infos = $this->db->listinfo("`catid` in ($catidss) and status = '99'  and $cityidd",'id DESC',$page,$pagesize,'','9',$urlrule,Array('pinyin'=>$cityList['pinyin'],'catids'=>$catids,'name'=>urlencode($nameid))); 

		//如果以下栏目 没有地方属性 那么就是 city_id = 0
		if(in_array($name,array('专题汇总','行测辅导','申论辅导','面试辅导','公共基础')) and !$infos)
		{
			$infos = $this->db->listinfo("`catid` in ($catidss) and status = '99'  and city_id=0",'id DESC',$page,$pagesize,'','9',$urlrule,Array('pinyin'=>$cityList['pinyin'],'catids'=>$catids,'name'=>$nameid)); 
		}

         $pages = $this->db->pages; 
		//SEO
		if($seoarr){
		    foreach($seoarr as $k=>$v)
			{
				$v = str_replace('{$cityname}',$cityList['name'],$v);
				$seolist[$k] = str_replace('{$catname}',$name,$v);
			}
		}

		$seotitle = $seolist['title']?$seolist['title']:$cityList['name'].'地区-'.$name.'-';
		$SEO = seo($siteid, '',$seotitle,$seolist['description'],$seolist['keywords']);
        
		//加载模版
		include template('content','city_list'.$siteid,$default_style);
    }
}
?>