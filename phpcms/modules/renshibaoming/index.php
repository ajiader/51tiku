<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_func('global','content');
class index {
	function __construct() {
		$this->db = pc_base::load_model('renshibaoming_model');
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$this->siteid = $siteid;
	}
	
	public function init()
	{
		$type_db = pc_base::load_model('type_model');
		$typedir = (isset($_GET['typedir']))?safe_replace($_GET['typedir']):'';
		if(empty($typedir)) showmessage('url参数错误！','blank');
		else{
            $typearr = $type_db->get_one(array('typedir'=>$typedir,'module'=>'frame'));
			$typeid  = $typearr['typeid'];
		}

		if(empty($typeid)) showmessage('参数错误！','blank');
		$siteid = $GLOBALS['siteid'] = max($this->siteid,1);
		define('SITEID', $siteid);
		$SEO = seo($siteid, '',KSBM_TITLE);
		$CATEGORYS = getcache('category_content_'.$siteid,'commons');
		ob_start();
		include template('renshibaoming',  'index');
		echo mem_page(1);
	}

	//展示页面
	public function show()
	{
		$city_db = pc_base::load_model('linkage_model');
		$seo_db = pc_base::load_model('seo_model');
		$page = intval($_GET['page']);
		 $filename = (isset($_GET['filename']))?safe_replace($_GET['filename']):'';
		 $typedir = (isset($_GET['typedir']))?safe_replace($_GET['typedir']):'';
		 $type_db = pc_base::load_model('type_model');
		  $typearr = $type_db->get_one(array('typedir'=>$typedir,'module'=>'frame'));
		  //print_r($filename);
		 $siteid = $this->siteid;
		 $CATEGORYS = getcache('category_content_'.$siteid,'commons');
		 $id = intval($_GET['id']);
		 //print_r($id);
		 if(!$id && !$typearr) page404();
         if($id>0)
		 {
		     $r = $this->db->get_one(array('id'=>$id));
		 }
		 else if($filename)
		 {
		     $r = $this->db->get_one(array('filename'=>$filename,'siteid'=>$this->siteid));
		 }
		 if (!$r) {
		 	page404();
		 }

		 extract($r);
		 if ($cityid) {
		 	$citylist = $city_db->get_one("linkageid=$cityid",'name');
		 	$cityname = citys2city($citylist['name']);
		 }
		 $r['seotitle'] = ($seotitle) ? $seotitle.'-网上报名' : $title ;

		 if ($typedir == 'renshibaoming') {
		 	$seoid = '57';
		 	 $SEO = $seo_db->get_one('id='.$seoid);//人事考试seo信息-renshibaoming                                  
			 $SEO['title']  = str_replace('{$cityname}', $title, $SEO['title']) ;
			 $SEO['keywords']  = str_replace('{$cityname}', $title, $SEO['keywords']) ;
			 $SEO['description']  = str_replace('{$cityname}', $title, $SEO['description']) ;
			  define('URLRULE',siteurl($siteid).'/'.$typedir.'/'.$filename.'_{$page}.html');//分页url伪静态
			 ob_start();
			 include template('renshibaoming',  'show');
			 echo mem_page(1);
		 }elseif ($typedir == 'renshibm') {
		 	$seoid = '61';
		 	 $SEO = $seo_db->get_one('id='.$seoid);//人事考试seo信息-renshibaoming                                  
		 $SEO['title']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title), $SEO['title']) ;
		 $SEO['keywords']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title),$SEO['keywords']) ;
		 $SEO['description']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title), $SEO['description']) ;
			  define('URLRULE',siteurl($siteid).'/'.$typedir.'/'.$filename.'_{$page}.html');//分页url伪静态
			  if ($siteid==2) {
				  	ob_start();
				 include template('renshibaoming',  'show');
				 echo mem_page(1);
			  }else{
			  	ob_start();
			 include template('renshibaoming',  'show_'.$typedir);
			 echo mem_page(1);
			  }
			 
		 }
		 else{
		 	$seoid = '61';
		 	 $SEO = $seo_db->get_one('id='.$seoid);//人事考试seo信息-renshibaoming                                  
		 $SEO['title']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title), $SEO['title']) ;
		 $SEO['keywords']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title),$SEO['keywords']) ;
		 $SEO['description']  = str_replace(array('{$cityname}','{$title}'), array($cityname,$title), $SEO['description']) ;
			  define('URLRULE',siteurl($siteid).'/'.$typedir.'/'.$filename.'_{$page}.html');//分页url伪静态
			 ob_start();
			 include template('renshibaoming',  'show');
			 echo mem_page(1);
		 }
		
		
	}
	public function top()
	{
		include template('renshibaoming',  'top');
	}

}
?>