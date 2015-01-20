<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_func('global','content');
class MY_index extends index
{
	function __construct()
	{
		
		parent::__construct();

		$this->db = pc_base::load_model('content_model');
		$this->city_db = pc_base::load_model('linkage_model');
		pc_base::load_sys_class("get_model", "model", 0);
		$this->seo_db = pc_base::load_model('seo_model');
		if(isset($_GET['siteid'])) {
			$this->siteid = intval($_GET['siteid']);
		} else {
			$this->siteid = 1;
		}
		$this->teachdb = pc_base::load_model('zhuanjia_model');
		$this->type_db = pc_base::load_model('type_model');
		
	}
	/*
	*公务员新手指导123
	*/
	public function help()
	{  
		$siteid = $this->siteid;
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');

		$seoarr = $this->seo_db->get_one(array('id'=>32,'siteid'=>$siteid));
		$SEO['title']= $seoarr['title'];
		$SEO['keyword'] = $seoarr['keywords'];
		$SEO['description'] =  $seoarr['description'];
		ob_start();
		include template('content','help_'.$this->siteid);
		echo mem_page(1);
	}
	//列表页
	public function lists() {
		//$catid = $_GET['catid'] = intval($_GET['catid']);
		//PHPCMS V9自定义栏目伪静态
         if(isset ($_GET['catid']))
		 {   
                    $catid =  $_GET['catid'] = intval($_GET['catid']);   
          }else{   
                    $catdir = safe_replace($_GET['catdir']);
                    $categorydir = safe_replace($_GET['categorydir']);
                    $s=$this->_getCategoryId($catdir,$categorydir);   
                    $catid=$s[0][catid];   
           }  

		$_priv_data = $this->_category_priv($catid); 
		if($_priv_data=='-1') {
			$forward = urlencode(get_url());
			showmessage(L('login_website'),APP_PATH.'goshouye.php?m=member&c=index&a=login&forward='.$forward);
		} elseif($_priv_data=='-2') {
			// showmessage(L('no_priv'));
			page404();
		}
		$catid_caiji = GetCaijiCatid(); // 获取采集id
        if(in_array($catid,$catid_caiji)) {
		   showmessage(L('no_priv'));
		}

        //获取地方属性
        $pinyin = safe_replace($_GET['pinyin']);
        if( $pinyin){
        	$cityList = $this->city_db->get_one(array('pinyin'=>$pinyin));
        	$cityname = citys2city($cityList['name']);
       		$pinyin = $cityList['pinyin'];$cityid = $cityList['linkageid'];
       		$listcity=1;
        }

        //没有地方属性的
        $cat_inarr = array(24,44,59,60,379,380,381,382,383,386,387,388,389,390,391,71,72,398,399,400,393,394,395,396,397,84,409,410,411,412,413,414,416,417,808,323,324,335,325,326,336,329,330,338,331,332,339,353,354,355,356,357,358,366,367,368,369,370,371,25,26,27,28,29,314,19,353,354,355,356,357,358,359,360,361,362,363,364,321,322,334,323,324,335,325,326,336,22,23);
        if(!in_array($catid,$cat_inarr)){
        	$seocity = $cityList['name'];
        }
      
		$_userid = $this->_userid;
		$_username = $this->_username;
		$_groupid = $this->_groupid;

		if(!$catid) showmessage(L('category_not_exists'),'blank');
		$siteids = getcache('category_content','commons');
		$siteid = $siteids[$catid];
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		//$CATEGORYS1 = getcache('category_content_1','commons'); //站点1 
		//$CATEGORYS2 = getcache('category_content_2','commons'); //站点2 公务员
		if(!isset($CATEGORYS[$catid])) showmessage(L('category_not_exists'),'blank');
		$CAT = $CATEGORYS[$catid];
		$siteid = $GLOBALS['siteid'] = $CAT['siteid'];
		extract($CAT);
		$setting = string2array($setting);
		//SEO
		if(!$setting['meta_title']) $setting['meta_title'] = $catname;
		$SEO = seo($siteid, '',$seocity.$setting['meta_title'],$setting['meta_description'],$setting['meta_keywords']);
		define('STYLE',$setting['template_list']);
		$page = intval($_GET['page']);

		$template = $setting['category_template'] ? $setting['category_template'] : 'category';
		$template_list = $setting['list_template'] ? $setting['list_template'] : 'list';
		$mid = intval($_GET['mid']);
		$mpinyin = safe_replace($_GET['mpinyin']);
		if ($mpinyin) {
			$seodb = pc_base::load_model('seo_model');
			$zrdb = pc_base::load_model('zirui_mokuai_model');
			$zr = $zrdb->get_one("pinyin='$mpinyin'");
			$mid = $zr['id'];
			if ($mid == 0) page404();
			$SEO= $seodb->get_one('id=43');
			$SEO['title'] = str_replace('{$mokuainame}', $zr['name'], $SEO['title']);
			$SEO['keyword'] = str_replace('{$mokuainame}', $zr['name'], $SEO['keywords']);
			$SEO['description'] = str_replace('{$mokuainame}', $zr['name'], $SEO['description']);
		}
		if ($mid == 0 && isset($_GET['mid'])) page404();

		$typeid = intval($_GET['typeid']);

		if($type==0) {
			$template = $child ? $template : $template_list;
			$arrparentid = explode(',', $arrparentid);
			$top_parentid = $arrparentid[1] ? $arrparentid[1] : $catid;
			$array_child = array();
			$self_array = explode(',', $arrchildid);
			//获取一级栏目ids
			foreach ($self_array as $arr) {
				if($arr!=$catid && $CATEGORYS[$arr][parentid]==$catid) {
					$array_child[] = $arr;
				}
			}
			$arrchildid = implode(',', $array_child);
			//URL规则
			$urlrules = getcache('urlrules','commons');
			$urlrules = str_replace('|', '~',$urlrules[$category_ruleid]);
			$tmp_urls = explode('~',$urlrules);
			$tmp_urls = isset($tmp_urls[1]) ?  $tmp_urls[1] : $tmp_urls[0];
			preg_match_all('/{\$([a-z0-9_]+)}/i',$tmp_urls,$_urls);
			if(!empty($_urls[1])) {
				foreach($_urls[1] as $_v) {
					$GLOBALS['URL_ARRAY'][$_v] = $_GET[$_v];
				}
			}
			define('URLRULE', $urlrules);
			$GLOBALS['URL_ARRAY']['pinyin'] = $pinyin;
			$GLOBALS['URL_ARRAY']['categorydir'] = $categorydir;
			$GLOBALS['URL_ARRAY']['catdir'] = $catdir;
			$GLOBALS['URL_ARRAY']['catid'] = $catid;
			$GLOBALS['URL_ARRAY']['typeid'] = $typeid;
			$GLOBALS['URL_ARRAY']['mid'] = $mid;
			$GLOBALS['URL_ARRAY']['mpinyin'] = $mpinyin;
			
            ob_start();
			include template('content',$template);
			echo mem_page(1);
		} else {
		//单网页
			$this->page_db = pc_base::load_model('page_model');
			$r = $this->page_db->get_one(array('catid'=>$catid));
			if($r) extract($r);
			
			$template = $setting['page_template'] ? $setting['page_template'] : 'page';
			$arrchild_arr = $CATEGORYS[$parentid]['arrchildid'];
			if($arrchild_arr=='') $arrchild_arr = $CATEGORYS[$catid]['arrchildid'];
			$arrchild_arr = explode(',',$arrchild_arr);
			array_shift($arrchild_arr);
			$keywords = $keywords ? $keywords : $setting['meta_keywords'];
			$title = $setting['meta_title']?$setting['meta_title']:$title;
			$SEO = seo($siteid, 0, $title,$setting['meta_description'],$keywords);
            ob_start();
			include template('content',$template);
			echo mem_page(1);
		}
	}

	/*
	专家页面
	*/
	function zhuanjia()
	{
		$siteid = $this->siteid;//获取站点id
		$siteid = $GLOBALS['siteid'] = max($siteid,1);

		$catdir = safe_replace($_GET['catdir']);
		if (empty($catdir) ) {
			$seoarr = $this->seo_db->get_one(array('id'=>31,'siteid'=>$siteid));
			$SEO['title']= $seoarr['title'];
			$SEO['keyword'] = $seoarr['keywords'];
			$SEO['description'] =  $seoarr['description'];
			ob_start();
			include template('content','zhuanjia'.$siteid);
			echo mem_page(1);
		}elseif ($catdir) {
			$CATEGORYS = getcache('category_content_'.$siteid,'commons');
			$cararr = array('En','jsj','zyzg');
			$seoArr = array('En'=>'42'); //seo 的id

			if (in_array($catdir, $cararr)) {
				//SEO数据调用
				$SEO = $this->seo_db->get_one(array('id'=>$seoArr[$catdir],'siteid'=>$siteid));
				ob_start();
				include template('content','zhuanjia_'.$catdir);
				echo mem_page(1);
			}
		}
		
	}
	function zhuanjia_list()
	{
		$siteid = $this->siteid;//获取站点id
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		$catdir = safe_replace($_GET['catdir']);
		if (empty($catdir) ) {
			$tid = intval($_GET['tid']);
			//获取专家信息
			$r = $this->teachdb->get_one("id=$tid");  
			if(!$r){
				 page404();
			}
			extract($r);
			$page = intval($_GET['page']);

	        define('URLRULE',siteurl($siteid).'/zhuanjia/t_'.$tid.'_{$page}.html#video');//分页url伪静态
	        ob_start();
			include template('content','zhuanjialist'.$siteid);
			echo mem_page(1);
		}elseif ($catdir) {
			$CATEGORYS = getcache('category_content_'.$siteid,'commons');
			$cararr = array('En','jsj','zyzg');
			if (in_array($catdir, $cararr)) {
				$tid = intval($_GET['tid']);
				//获取专家信息
				$r = $this->teachdb->get_one("id=$tid");
				if(!$r){
					 page404();
				}
				extract($r);
				$page = intval($_GET['page']);
				define('URLRULE',$CATEGORYS[9][url].'zhuanjia/t_'.$tid.'_{$page}.html#video');//分页url伪静态
				ob_start();
				include template('content','zhuanjialist_'.$catdir);
				echo mem_page(1);
			}
		}
	}

	//考试时间查询
    function kaoshichaxun()
    {
    	//获取站点id
		$siteid = $this->siteid;
		// echo($siteid);
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		if ($siteid == 2) { //kaogwy.com下调用
			$yuearr = range(1,12); //月份
			#省市31个   查询转为数组
			$linkage_db = pc_base::load_model('linkage_model');
			$linkage_list = $linkage_db->select("parentid=0 and keyid=1 and linkageid not in (33,34,35,3358)",'linkageid,name');
			foreach ($linkage_list as $v) {
	  			$linkagelist[$v['linkageid']] = $v;
	  		}

			
			
			
			//按照年份分组查询
			$ks_db =   pc_base::load_model('kaoshichaxun_model');
			$YEARS  = $ks_db->select('', 'YEAR(addtime) as year', '', 'YEAR(addtime) desc', 'YEAR(addtime)');
			if ($_GET['search']) {
				$year = intval($_GET['year']);
				$cityid = intval($_GET['cityid']);
				$bmsj = intval($_GET['bmsj']);
				$kssj = intval($_GET['kssj']);
				$where = "YEAR(addtime)=$year";
				if ($cityid) $where .= " and cityid=$cityid ";
				if($bmsj) $where.= " and bmsj like '%".$bmsj."月%' ";
				if($kssj) $where.= " and kssj like '%".$kssj."月%' ";
			}
		
			$list = $ks_db->select($where,'*','', 'addtime desc');
			if ($list) {
				$kaoslist = array();
				foreach ($list as $key => $value) {
					$kaoslist[date('Y',strtotime($value["addtime"]))][$key] = $value; 
				}
			}
			// print_r($kaoslist);
			if (!$_GET['search']) {
				ob_start();
		    	include template('content','kaoshichaxun'.$siteid);
		    	echo mem_page(1);
	    	}else{
	    		include template('content','kaoshichaxun'.$siteid);
	    	}
		}elseif ($siteid==1) { //kaozc.com下调用
			$catdir = safe_replace($_GET['catdir']);
			if (!$catdir) page404();
			if ($catdir=='En') {
				$seoid=41;
			}
			$SEO = $this->seo_db->get_one(array('id'=>$seoid,'siteid'=>$siteid));
			ob_start();
			include template('content','kaoshichaxun_'.$catdir);
			echo mem_page(1);
		}
		
    }
	/*
	*计算机软件调用
	*/ 
	function jsj_soft()
	{
		//获取站点id
		$siteid = $this->siteid;
		$siteid = $GLOBALS['siteid'] = max($siteid,1);
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		ob_start();
		include template('content','jsj_soft');
		echo mem_page(1);
	}
	/*
    公务员的产品展示公共调用
	*/
	function commonProduct()
	{
		define('SITEID', $this->siteid);
		ob_start();
		include template('content','commonproduct');
		echo mem_page(1);
	}

	/*公务员的专题报名排行预约*/
	function formpost()
	{
		define('SITEID', $this->siteid);
		$siteid=$this->siteid;
		$catid = isset($_GET['catid'])?intval($_GET['catid']):'10000';
		$typeurl = isset($_GET['typeurl'])?safe_replace($_GET['typeurl']):'';
		if ($typeurl == '2013checkpoints') {
			include template('content','2013checkpoints_form');
		}elseif ($typeurl == 'enbmrk') {
			$doArray = array(
 				'1'=>array('id'=>'1', 'name'=>'上　海', 'date'=>'12月13日10:00－12月31日16:00','url'=>'http://www.spta.gov.cn/appendix/wsbm.html'),
 				'2'=>array('id'=>'2','name'=>'云　南', 'date'=>'2013年12月10日—2014年1月10日','url'=>'http://www.ynrsksw.cn/bmcx/webregister/index.aspx'),
 				'3'=>array('id'=>'3','name'=>'四　川', 'date'=>'2013年12月5日至2014年1月5日','url'=>'http://www.scpta.gov.cn/bmonline.aspx'),
 				'4'=>array('id'=>'4','name'=>'陕　西', 'date'=>'12月2日至2014年1月3日','url'=>'http://1.85.56.94/zgwsbm/webregister/main/frmMain.htm'),
 				'5'=>array('id'=>'5','name'=>'兵　团', 'date'=>'2013年12月1日－2014年2月7日','url'=>'http://www.btpta.gov.cn/'),
 				'6'=>array('id'=>'6','name'=>'安　徽', 'date'=>'2013年11月29日至12月24日','url'=>'http://61.132.255.208:14100/apta/all_exam.php'),
 				'7'=>array('id'=>'7','name'=>'重　庆', 'date'=>'2013年11月25日—12月25日','url'=>'http://222.180.173.2/zcbm/webregister/'),
 				'8'=>array('id'=>'8','name'=>'西　藏', 'date'=>'2013年11月11日-12月23日18:00','url'=>'http://218.57.146.218/wb_xzwy/webregister/index.aspx'),
 				'9'=>array('id'=>'9','name'=>'广　西', 'date'=>'11月5日9:00-12月23日17:00','url'=>'http://wsbm.gxpta.com.cn/webRegister/login/preLogin.aspx?timeID=160&examSort=81&examDate=201403'),
 				'10'=>array('id'=>'10','name'=>'北　京', 'date'=>'2013年11月29日至12月20日','url'=>'https://www.bjrbj.gov.cn/uamsso/login?service=http://www.bjrbj.gov.cn/platform/login.htm'),
 				'11'=>array('id'=>'11','name'=>'海　南', 'date'=>'2013年11月25日至12月20日','url'=>'http://www.hnjy.gov.cn/website/findMessageWBS.do'),
			);

			include template('content','enbmrk_form');
		}
		
	}
}