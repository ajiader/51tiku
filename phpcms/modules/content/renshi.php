<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_func('global','content');

/**
* 人事页面布局
*/
class renshi 
{
	public static $catnameArr = array(
		'En' =>array('catid'=>9,'qcid'=>3,'cjcatid'=>'817'),
		'jsj'=>array('catid'=>10,'qcid'=>1,'cjcatid'=>'816'),
		'zyzg'=>array('catid'=>11,'qcid'=>2,'cjcatid'=>'818'),

		'sydw'=>array('catid'=>57,'qcid'=>'','cjcatid'=>''),
		'zjks'=>array('catid'=>69,'qcid'=>'','cjcatid'=>''),
		'zfgj'=>array('catid'=>82,'qcid'=>'','cjcatid'=>''),
		'xds'=>array('catid'=>96,'qcid'=>'','cjcatid'=>''),
		'jszp'=>array('catid'=>99,'qcid'=>'','cjcatid'=>''),
		'szyf'=>array('catid'=>102,'qcid'=>'','cjcatid'=>''),
		'yhxys'=>array('catid'=>105,'qcid'=>'','cjcatid'=>''),
		'cg'=>array('catid'=>93,'qcid'=>'','cjcatid'=>''),
		'jzg'=>array('catid'=>311,'qcid'=>'','cjcatid'=>''),
		'gxg'=>array('catid'=>308,'qcid'=>'','cjcatid'=>''),
		);
	function __construct()
	{
		$this->db = pc_base::load_model('content_model');
		$this->city_db = pc_base::load_model('linkage_model');
		$this->seo_db = pc_base::load_model('seo_model');
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$this->siteid = $siteid;
	}


	public function init()
	{
		$siteid  = $this->siteid;
		$mid = intval($_GET['mid']);
		$caturl = safe_replace($_GET['caturl']);

		$catnameArr = self::$catnameArr;

		if (!array_key_exists($caturl, $catnameArr) && $siteid==1) {
			page404();
		}
		$CATEGORYS = getcache('category_content_'.$this->siteid,'commons');//加载栏目缓存

		$catid = $catnameArr[$caturl]['catid']; //获取栏目id
		$catids_str = $CATEGORYS[$catid]['arrchildid'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
			if ($siteid==2) $catidsql = " and catid IN ($catids_str)";
			
		$cat_url = $CATEGORYS[$catid]['url'];//获取栏目url
		$catname = ($CATEGORYS[$catid]['catname'])?$CATEGORYS[$catid]['catname']:'公务员';//获取栏目名称
		if ($mid) {
			if ($caturl == 'zrmk' or $caturl == 'zyzg') {
				$zrdb = pc_base::load_model('zirui_mokuai_model');
				$rowzr = $zrdb->get_one("id=$mid");
				$zcatname = $rowzr['name'];
				
			}elseif ($caturl == 'jsj') {
				$zcatname = $CATEGORYS[$mid]['catname'];
				
			}
			$murl = $mid.'/';
		}
		//获取问答栏目id
		$qcid = $catnameArr[$caturl]['qcid'];
        //获取随机调用的栏目id
        $cjcatid = $catnameArr[$caturl]['cjcatid'];

		//获取地区属性
		$cityurl = safe_replace($_GET['cityurl']);
		$cityurl = getCityPinyin($cityurl); //转为可用格式查询
		$cityList = $this->city_db->get_one(array('pinyin'=>$cityurl));
        if(!$cityList){//如果没有找到转到404
        	page404();
        }

        $cityname = $cityList['name']; //获取当前城市名称
        $city = citys2city($cityname); //过滤省市
        $cityid = $cityList['linkageid']?$cityList['linkageid']:'0';
        //获取下级城市list 
        if ($cityList['child']) {
        	$childCitylist = $this->city_db->select("parentid=$cityid",'linkageid,name,pinyin',50,'listorder asc');
        }else{//没有下级调用平级
        	$childCitylist = $this->city_db->select("parentid=".$cityList['parentid'],'linkageid,name,pinyin',50, 'listorder asc');
        }
		if ($cityList['parentid'] !=0) {
        	//获取当前地区顶级父类
			$topparentid = xp_get_linkage($cityid);
        }else{
        	$topparentid=$cityid; //如果没有上级 就取自己为父级
        }

		$where_q = $this->fenciSql($city.$catname.'考试课程辅导','title');
		if (empty($where_q) || $where_q =="a)") {
			$where_q = '';
		}
// echo $where_q;exit;
		//调用前缀
		$seoid = ($siteid==1)?( ($caturl=='zyzg')?'60':'40' ):'55';
		$seoarr = $this->seo_db->get_one(array('id'=>$seoid,'siteid'=>$siteid));
		// $seofix = $city.$catname.'考试网';
		$SEO['title'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($city,$catname,$zcatname), $seoarr['title']);
		$SEO['keyword'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($city,$catname,$zcatname), $seoarr['keywords']);
		$SEO['description'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($city,$catname,$zcatname), $seoarr['description']);
		
		// echo $SEO['title'];
		ob_start();
		include template('content','renshi',$default_style);
		echo mem_page(1);
	} 

    //入口页面
    function morecity()
    {
    	$siteid  = $this->siteid;
    	define('SITEID', $siteid);
		$caturl = safe_replace($_GET['caturl']);
		$cityid = intval($_GET['cityid']);
		$cityList = $this->city_db->get_one(array('linkageid'=>$cityid));
		$citypinyin = $cityList['pinyin'];
		
		if(!$cityList){//如果没有找到转到404
        	page404();
        }

        $cityname = $cityList['name']; //获取当前城市名称
        $city = citys2city($cityname); //过滤省市
        $cityid = $cityList['linkageid']?$cityList['linkageid']:'0';
		$catnameArr = self::$catnameArr;


		$CATEGORYS = getcache('category_content_'.$this->siteid,'commons');//加载栏目缓存
		$catid = $catnameArr[$caturl]['catid']; //获取栏目id

		$catids_str = $CATEGORYS[$catid]['arrchildid'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
				$catidsql = " and catid IN ($catids_str)";

		$cat_url = $CATEGORYS[$catid]['url'];//获取栏目url
		
		$catname = ($CATEGORYS[$catid]['catname'])?$CATEGORYS[$catid]['catname']:'公务员';//获取栏目名称

		//致睿的模块查询
		if ($caturl=='zyzg') {
			$zrdb = pc_base::load_model('zirui_mokuai_model');
			$zr_list = $zrdb->select('','id,name');
		}else{
			//获取下级城市list 
	        if ($cityList['child']) {
	        	$childCitylist = $this->city_db->select("parentid=$cityid",'linkageid,name,pinyin',50,'listorder asc');
	        }else{//没有下级调用平级
	        	$childCitylist = $this->city_db->select("parentid=".$cityList['parentid'],'linkageid,name,pinyin',50, 'listorder asc');
	        }
		}

		// echo($cat_url);
    	ob_start();
		include template('content','morecity',$default_style);
		echo mem_page(1);
    }

/*计算机SEO*/
    function jsjseo()
    {
    	//获取地区属性
		$cityurl = safe_replace($_GET['pinyin']);
		$cityurl = getCityPinyin($cityurl); //转为可用格式查询
		if ($cityurl) {
			$cityList = $this->city_db->get_one(array('pinyin'=>$cityurl));
		}
		
		$cityname = $cityList['name']; //获取当前城市名称
        $city = citys2city($cityname); //过滤省市
        $cityid = $cityList['linkageid']?$cityList['linkageid']:'0';


    	$siteid  = $this->siteid;
    	define('SITEID', $siteid);
    	$CATEGORYS = getcache('category_content_'.$this->siteid,'commons');//加载栏目缓存
    	$catid = 10;
    	$catname = ($CATEGORYS[$catid]['catname']);
    	if ($cityList) {
            	$cityArrchildid = $cityList['arrchildid'];
	            $likeCityName = citys2city($cityList['name']);
	            $condition = "title like '%$likeCityName%' or  city_id in ($cityArrchildid)";


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
            }else{
            	$childCitylist = $this->city_db->select("parentid=0  AND keyid=1 AND linkageid NOT IN (33,34,35,3358,3360)",'linkageid,name,pinyin',50, 'listorder asc');
            	$condition = '1=1';
            	//$cityList['name'] = '';
         }

         //加载考试报名信息
		$ksdb = pc_base::load_model('examremind_model');
		$topparentid = $topparentid?$topparentid:'24';
		$kslist  = $ksdb->get_one(array('cityid'=>$topparentid,'catid'=>10, 'siteid'=>$siteid));
    	$nameUrl = safe_replace($_GET['nameurl']);

    	 //调用前缀
			
			switch ($nameUrl) {
				case 'jsjbm':
					$seoid = '64';
					break;
				
				case 'jsjrj':
					$seoid = '65';
					break;
				case 'jsjdj':
					$seoid = '66';
					break;
			}
			$seoarr = $this->seo_db->get_one(array('id'=>$seoid,'siteid'=>$siteid));
		// $seofix = $city.$catname.'考试网';
		
			$SEO['title'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($cityList['name'],$catname,$zcatname), $seoarr['title']);
			$SEO['keyword'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($cityList['name'],$catname,$zcatname), $seoarr['keywords']);
			$SEO['description'] = str_replace(array('{$cityname}','{$catname}','{$mokuai}'), array($cityList['name'],$catname,$zcatname), $seoarr['description']);
		ob_start();
    	include template('content',$nameUrl,$default_style);
    	echo mem_page(1);
    }

	function fenciSql($q,$field)
	{
		$http = pc_base::load_sys_class('http'); //加载http类
		$posturl = 'http://www.kaozc.com'.'/kapi.php?op=get_keywords&number=3';
		$http->post($posturl,array('data'=>$q)); //分词处理开始
			if($http->is_ok()) {
				$fenci =  $http->get_data();

				$fenciarr = explode(' ', $fenci);
				$where_q = 'and (';
				foreach ($fenciarr as $key => $value) 
				{
					$value = trim($value);
					if (strlen($value) > 3) {
						$where_q .= "$field like '%$value%' or ";
					}
					
				}
				$where_q = substr($where_q, 0, -4);
				$where_q .= ')';
			}
		return $where_q;
	}
}
?>