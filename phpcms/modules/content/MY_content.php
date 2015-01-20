<?php
defined ( 'IN_PHPCMS' ) or exit ( 'No permission resources.' );
//模型缓存路径
define ( 'CACHE_MODEL_PATH', CACHE_PATH . 'caches_model' . DIRECTORY_SEPARATOR . 'caches_data' . DIRECTORY_SEPARATOR );
//定义在单独操作内容的时候，同时更新相关栏目页面
define ( 'RELATION_HTML', true );

pc_base::load_app_class ( 'admin', 'admin', 0 );
pc_base::load_sys_class ( 'form', '', 0 );
pc_base::load_app_func ( 'util' );
pc_base::load_sys_class ( 'format', '', 0 );
class MY_content extends content {
	private $db,$priv_db,$modelid;
	
	public function __construct() {
		parent::__construct ();
		$this->db = pc_base::load_model('content_model');
		$this->modelid=($this->siteid==1)?1:13;
		
	}
	public function add() {
		if (isset ( $_POST ['dosubmit'] ) || isset ( $_POST ['dosubmit_continue'] )) {
			$this->db = pc_base::load_model ( 'content_model' );
			$this->db->set_model ( $this->modelid );
			$title = $this->db->get_one ( "title='" . $_POST ['info'] ['title'] . "'", 'count( id ) count' );
			if ($title ['count'] > 0)
				showmessage ( '不能使用重复标题' );
		}
		parent::add ();
	}
	public function checksame() {
		$pc_hash = $_SESSION ['pc_hash'];
		$this->db = pc_base::load_model ( 'content_model' );
		$this->db->set_model ( $this->modelid  );
		$row=$this->db->select("", 'max( id ) id,count( id ) count,title','200', 'count DESC', 'title');
		$datas=array();
		foreach ($row as $data){
			if ($data['count']<=1){
				break;
			}
			$title=$data['title'];
			$new=$this->db->listinfo("title='$title'");
			$datas=array_merge($datas,$new);
			
		}
		
		include $this->admin_tpl('content_checksame');
	}
	
	
public function rmsame() {
		if(isset($_GET['dosubmit'])) {
			$modelid = $this->modelid ;
			$siteid = $this->siteid;	
			$html_root = pc_base::load_config('system','html_root');
			$this->db->set_model($modelid);
			$this->hits_db = pc_base::load_model('hits_model');
			$this->queue = pc_base::load_model('queue_model');
			if(isset($_GET['ajax_preview'])) {
				$ids = intval($_GET['id']);
				$_POST['ids'] = array(0=>$ids);
			}
			if(empty($_POST['ids'])) showmessage(L('you_do_not_check'));
			//附件初始化
			$attachment = pc_base::load_model('attachment_model');
			$this->content_check_db = pc_base::load_model('content_check_model');
			$this->position_data_db = pc_base::load_model('position_data_model');
			$this->search_db = pc_base::load_model('search_model');
			$this->comment = pc_base::load_app_class('comment', 'comment');
			$search_model = getcache('search_model_'.$this->siteid,'search');
			$typeid = $search_model[$modelid]['typeid'];
			$this->url = pc_base::load_app_class('url', 'content');
			
			foreach($_POST['ids'] as $id) {
				$r = $this->db->get_one(array('id'=>$id));
				if($content_ishtml && !$r['islink']) {
					$urls = $this->url->show($id, 0, $r['catid'], $r['inputtime']);
					$fileurl = $urls[1];
					if($this->siteid != 1) {
						$sitelist = getcache('sitelist','commons');
						$fileurl = $html_root.'/'.$sitelist[$this->siteid]['dirname'].$fileurl;
					}
					//删除静态文件，排除htm/html/shtml外的文件
					$lasttext = strrchr($fileurl,'.');
					$len = -strlen($lasttext);
					$path = substr($fileurl,0,$len);
					$path = ltrim($path,'/');
					$filelist = glob(PHPCMS_PATH.$path.'*');
					foreach ($filelist as $delfile) {
						$lasttext = strrchr($delfile,'.');
						if(!in_array($lasttext, array('.htm','.html','.shtml'))) continue;
						@unlink($delfile);
						//删除发布点队列数据
						$delfile = str_replace(PHPCMS_PATH, '/', $delfile);
						$this->queue->add_queue('del',$delfile,$this->siteid);
					}
				} else {
					$fileurl = 0;
				}
				//删除内容
				$this->db->delete_content($id,$fileurl,$catid);
				//删除统计表数据
				$this->hits_db->delete(array('hitsid'=>'c-'.$modelid.'-'.$id));
				//删除附件
				$attachment->api_delete('c-'.$catid.'-'.$id);
				//删除审核表数据
				$this->content_check_db->delete(array('checkid'=>'c-'.$id.'-'.$modelid));
				//删除推荐位数据
				$this->position_data_db->delete(array('id'=>$id,'catid'=>$catid,'module'=>'content'));
				//删除全站搜索中数据
				$this->search_db->delete_search($typeid,$id);
				//删除相关的评论
				$commentid = id_encode('content_'.$catid, $id, $siteid);
				$this->comment->del($commentid, $siteid, $id, $catid);
			}
			//更新栏目统计
			$this->db->cache_items();
			showmessage(L('operation_success'),HTTP_REFERER);
		} else {
			showmessage(L('operation_failure'));
		}
	}
}
?>