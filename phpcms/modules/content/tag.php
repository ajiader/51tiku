<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);

pc_base::load_app_func('util','content');
class tag {
	private $db;
	function __construct() {
		$this->db = pc_base::load_model('content_model');
	}
	/**
	 * 按照模型搜索
	 */
	public function init() {
		if(!isset($_GET['catid'])) showmessage(L('missing_part_parameters'));
		$catid = intval($_GET['catid']);
		$siteids = getcache('category_content','commons');
		$siteid = $siteids[$catid];
		if(isset($_GET['siteid'])) {
			$siteid2 = intval($_GET['siteid']);
		} else {
			$siteid2 = 1;
		}
		if($siteid != $siteid2) showmessage(L('information_does_not_exist'));
		$this->categorys = getcache('category_content_'.$siteid,'commons');
		if(!isset($this->categorys[$catid])) showmessage(L('missing_part_parameters'));
		/*
		if(isset($_GET['info']['catid']) && $_GET['info']['catid']) {
			$catid = intval($_GET['info']['catid']);
		} else {
			$_GET['info']['catid'] = 0;
		}
		*/
		if(isset($_GET['tag']) && trim($_GET['tag']) != '') {
			$tag = safe_replace(strip_tags($_GET['tag']));
		} else {
			showmessage(L('illegal_operation'));
		}
		
		$modelid = $this->categorys[$catid]['modelid'];
		$modelid = intval($modelid);
		if(!$modelid) showmessage(L('illegal_parameters'));
		$CATEGORYS = $this->categorys;
        
		//查询当前catid下面文章
		$catids = $CATEGORYS[$catid]['arrchildid'];
		$wherecatid = "catid in ($catids)";

		$siteid = $this->categorys[$catid]['siteid'];
		$siteurl = siteurl($siteid);
		$this->db->set_model($modelid);
		$page = intval($_GET['page']);
		$page = max($page,1); 
		$pagesize = 25; //每页显示条数
		//$datas = $infos = array();
		//$infos = $this->db->listinfo("`keywords` LIKE '%$tag%'",'id DESC',$page,20);

		//用于伪静态,可直接在tag.html模板下使用$pages参数调用分页.
		$urlrules = getcache('urlrules','commons');    
		$urlrule = $urlrules[33];//调用url规则    
		$datas = $infos = array();    

		$infos = $this->db->listinfo("`keywords` LIKE '%$tag%' and $wherecatid",'id DESC',$page,$pagesize,'','9',$urlrule,Array('catid'=>$catid,'tag'=>urlencode($tag)));

		$total = $this->db->number;
		if($total>0) {
			$pages = $this->db->pages;
			foreach($infos as $_v) {
				if(strpos($_v['url'],'://')===false) $_v['url'] = $siteurl.$_v['url'];
				$datas[] = $_v;
			}
		}
		$SEO = seo($siteid, $catid, $tag);
		//如果当前页超过总页数 自动跳转最后一页
		$pagenum = ceil($total / $pagesize); //总页数
        $pagenum = max($pagenum,1); 
		
        if($page > $pagenum){
			
			$thisuri = $_SERVER["REQUEST_URI"];
			$thisuri = preg_replace("/tags-(.*)_(.*)_(.*).html/iUs","tags-$1_$2_$pagenum.html",$thisuri);
		    Header( "HTTP/1.1 301 Moved Permanently" );    
            Header( "Location: $thisuri" );
			exit;
		}


		
		//ob_start();
		include template('content','tag');
		//echo mem_page(1);
	}
}
?>