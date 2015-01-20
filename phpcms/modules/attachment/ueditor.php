<?php 
defined('IN_PHPCMS') or exit('No permission resources.'); 
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
if(param::get_cookie('sys_lang')) {
	define('SYS_STYLE',param::get_cookie('sys_lang'));
} else {
	define('SYS_STYLE','zh-cn');
}
/**
* 删除整个目录
* @param $dir
* @return bool
*/
function delDir( $dir )
{
    //先删除目录下的所有文件：
    $dh = @opendir( $dir );
    while ( $file = @readdir( $dh ) ) {
        if ( $file != "." && $file != ".." ) {
            $fullpath = $dir . "/" . $file;
            if ( !is_dir( $fullpath ) ) {
                @unlink( $fullpath );
            } else {
                @delDir( $fullpath );
            }
        }
    }
    @closedir( $dh );
    //删除当前文件夹：
    return @rmdir( $dir );
}
class ueditor {
	private $att_db;
	function __construct() {
		pc_base::load_app_func('global');
		$this->upload_url = pc_base::load_config('system','upload_url');
		$this->upload_path = pc_base::load_config('system','upload_path');		
		$this->imgext = array('jpg','gif','png','bmp','jpeg');
		$this->userid = param::get_cookie('userid') ? param::get_cookie('userid') : param::get_cookie('_userid');
		$this->isadmin = $_SESSION['roleid'] ? 1 : 0;
		$this->groupid = param::get_cookie('_groupid') ? param::get_cookie('_groupid') : 8;
		$this->admin_username = $_SESSION['roleid'] ? param::get_cookie('admin_username') : '';
                $this->tmpAttachmentPath="";
                $this->tmpAttachmentAID="";
	}
	/**
	 * imagespload图片上传
	 */
	public function imagespload(){
		$grouplist = getcache('grouplist','member');
                if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) showmessage(L('att_no_permission'));
                $args = $_GET['args'];
                $authkey = $_GET['authkey'];
                
                if(upload_key($args) != $authkey) showmessage(L('attachment_parameter_error'));
                extract(getswfinit($args));
                $siteid = $this->get_siteid();
                $site_setting = get_site_setting($siteid);
                $file_size_limit = $site_setting['upload_maxsize'];
                $watermark_enable = $site_setting['watermark_enable'];
                $sess_id = SYS_TIME;
		$swf_auth_key = md5(pc_base::load_config('system','auth_key').$sess_id); 
                $ext='"SWFUPLOADSESSID":"'.$sess_id.'","module":"'.$_GET['module'].'","catid":"'.$_GET['catid'].'","userid":"'.$this->userid.'","siteid":"'.$siteid.'","dosubmit":"1","thumb_width":"'.$thumb_width.'","thumb_height":"'.$thumb_height.'","watermark_enable":"'.$watermark_enable.'","filetype_post":"'.$file_types_post.'","swf_auth_key":"'.$swf_auth_key.'","isadmin":"'.$this->isadmin.'","groupid":"'.$this->groupid.'"';
                include $this->admin_tpl('imgupload');     
	}
        
	public function imgswfupload(){
                $grouplist = getcache('grouplist','member');
                if( $_POST['swf_auth_key'] != md5(pc_base::load_config('system','auth_key').$_POST['SWFUPLOADSESSID']) || ($_POST['isadmin']==0 && !$grouplist[$_POST['groupid']]['allowattachment'])) exit();
                pc_base::load_sys_class('attachment','',0);
                $attachment = new attachment($_POST['module'],$_POST['catid'],$_POST['siteid']);
                $attachment->set_userid($_POST['userid']);
                $aids = $attachment->upload('picdata',$_POST['filetype_post'],'','',array($_POST['thumb_width'],$_POST['thumb_height']),$_POST['watermark_enable'],$_POST['pictitle']);
                if($aids[0]) {
                        $filename= (strtolower(CHARSET) != 'utf-8') ? iconv('gbk', 'utf-8', $attachment->uploadedfiles[0]['filename']) : $attachment->uploadedfiles[0]['filename'];
                        echo "{'url':'".$this->upload_url.$attachment->uploadedfiles[0]['filepath']."','title':'".$filename."','original':'".$_FILES['picdata']['name']."','state':'SUCCESS','aid':'".$aids[0]."'}";		
                        exit;
                } else {
                        echo "{'state':'".$attachment->error()."'}";
                        exit;
                }
	}
        
        /**
	 * imageslist在线管理
	 */
        public function imageslist() {
		if(!$this->admin_username) return false;
		$where = $uploadtime= '';
		$this->att_db= pc_base::load_model('attachment_model');
		if($_GET['args']) extract(getswfinit($_GET['args']));
		pc_base::load_sys_class('form');
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->att_db->select($where,'filepath',40, 'aid DESC');
                $str = "";
		foreach($infos as $r){
			$ext = fileext($r['filepath']);
			if(in_array($ext,$this->imgext)) {
                                $str .= $this->upload_url.$r['filepath'].'ue_separate_ue';
			}
		}
                echo $str;
	}
        
        /**
	 * wordimage图片转存
	 */
        public function wordimage() {	
		$grouplist = getcache('grouplist','member');
                if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) showmessage(L('att_no_permission'));
                $args = $_GET['args'];
                $authkey = $_GET['authkey'];
                
                if(upload_key($args) != $authkey) showmessage(L('attachment_parameter_error'));
                extract(getswfinit($args));
                $siteid = $this->get_siteid();
                $site_setting = get_site_setting($siteid);
                $file_size_limit = $site_setting['upload_maxsize'];
                $watermark_enable = $site_setting['watermark_enable'];
                $sess_id = SYS_TIME;
		$swf_auth_key = md5(pc_base::load_config('system','auth_key').$sess_id);
                $ext='"SWFUPLOADSESSID":"'.$sess_id.'","module":"'.$_GET['module'].'","catid":"'.$_GET['catid'].'","userid":"'.$this->userid.'","siteid":"'.$siteid.'","dosubmit":"1","thumb_width":"'.$thumb_width.'","thumb_height":"'.$thumb_height.'","watermark_enable":"'.$watermark_enable.'","filetype_post":"'.$file_types_post.'","swf_auth_key":"'.$swf_auth_key.'","isadmin":"'.$this->isadmin.'","groupid":"'.$this->groupid.'"';				
                include $this->admin_tpl('wordimage');  
	}
        
        /**
	 * scrawl涂鸦
	 */
        public function scrawl() {	
		$grouplist = getcache('grouplist','member');
                if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) showmessage(L('att_no_permission'));
                $args = $_GET['args'];
                $authkey = $_GET['authkey'];
                
                if(upload_key($args) != $authkey) showmessage(L('attachment_parameter_error'));
                extract(getswfinit($args));
                $siteid = $this->get_siteid();
                $site_setting = get_site_setting($siteid);
                $watermark_enable = $site_setting['watermark_enable'];
                $sess_id = SYS_TIME;
		$swf_auth_key = md5(pc_base::load_config('system','auth_key').$sess_id);
                $ext='&SWFUPLOADSESSID='.$sess_id.'&module='.$_GET['module'].'&catid='.$_GET['catid'].'&userid='.$this->userid.'&siteid='.$siteid.'&watermark_enable='.$watermark_enable.'&swf_auth_key='.$swf_auth_key.'&isadmin='.$this->isadmin.'&groupid='.$this->groupid.'&filetype_post='.$file_types_post.'&thumb_width='.$thumb_width.'&thumb_height='.$thumb_height;				
                include $this->admin_tpl('scrawl');  
	}
        /**
	 * scrawlUp涂鸦上传
	 */
        public function scrawlUp(){
                $grouplist = getcache('grouplist','member');
                if( $_GET['swf_auth_key'] != md5(pc_base::load_config('system','auth_key').$_GET['SWFUPLOADSESSID']) || ($_GET['isadmin']==0 && !$grouplist[$_GET['groupid']]['allowattachment'])) exit();
                pc_base::load_sys_class('attachment','',0);
                $attachment = new attachment($_GET['module'],$_GET['catid'],$_GET['siteid']);
                $attachment->set_userid($_GET['userid']);
                
                $action = htmlspecialchars( $_GET[ "action" ] );
                if ( $action == "tmpImg" ) { // 背景上传
                    $filepath = $attachment->uploadTmpFile('upfile');
                    if(!empty($filepath)) {
							$admin_url=pc_base::load_config('system','admin_url');
							if($admin_url!=""){
								$p = "/^[a-zA-Z]+:\/\/(.+?)\//i";
								preg_match($p, APP_PATH, $m);
								$localDomain=$m[1];
								$rurl= str_replace($localDomain,$admin_url, $this->upload_url.$attachment->uploadedfiles[0]['filepath']);
							}else{
								$rurl=$this->upload_url.$attachment->uploadedfiles[0]['filepath'];
							}
                            echo "<script>parent.ue_callback('" .$rurl.$filepath. "','SUCCESS')</script>";
                            exit;
                    } else {
                            echo "{'state':'".$attachment->error()."'}";
                            exit;
                    }
                    /**
                    * 返回数据，调用父页面的ue_callback回调
                    */
                    
                } else {                    
                    $aids = $attachment->uploadBase64('content',$_GET['watermark_enable']); 
                    //上传成功后删除临时目录
                    $tmpPath=$this->upload_path."tmp/";
                    if(file_exists( $tmpPath)){
                        delDir($tmpPath);
                    }
                    if($aids[0]) {
                            $filename= (strtolower(CHARSET) != 'utf-8') ? iconv('gbk', 'utf-8', $attachment->uploadedfiles[0]['filename']) : $attachment->uploadedfiles[0]['filename'];
                            echo "{'url':'".$this->upload_url.$attachment->uploadedfiles[0]['filepath']."','state':'SUCCESS','aid':'".$aids[0]."','filename':'".$filename."'}";	
                            exit;
                    } else {
                            echo "{'state':'".$attachment->error()."'}";
                            exit;
                    }
                }
	}        
        /**
	 * 远程图片抓取
	 */
        public function imagescatch() {
                $grouplist = getcache('grouplist','member');
		if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) showmessage(L('att_no_permission'));;
		pc_base::load_sys_class('attachment','',0);
		$module = trim($_GET['module']);
		$catid = intval($_GET['catid']);
		$siteid = $this->get_siteid();
		$site_setting = get_site_setting($siteid);
		$site_allowext = $site_setting['upload_allowext'];	
                $watermark_enable = $site_setting['watermark_enable'];  
                $uri = htmlspecialchars( $_POST[ 'upfile' ] );
                $uri = str_replace( "&amp;" , "&" , $uri );                
                 //忽略抓取时间限制
                set_time_limit( 0 );
                //ue_separate_ue  ue用于传递数据分割符号
                $imgUrls = explode( "ue_separate_ue" , $uri );
                $tmpNames = array();
                $aids= array();
                foreach ( $imgUrls as $imgUrl ) {
					$attachment = new attachment($module,$catid,$siteid);
					$attachment->set_userid($this->userid); 
                    $aid = $attachment->catcher('picdata',$imgUrl,$watermark_enable,$site_allowext);
                    array_push( $tmpNames ,  $this->upload_url.$attachment->uploadedfiles[0]['filepath'] );
                    array_push( $aids ,  $aid[0] );
                }    
                echo "{'url':'" . implode( "ue_separate_ue" , $tmpNames ) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "','aids':'".implode( "ue_separate_ue" , $aids )."'}";
        }
        
        /**
	 * 常规上传
	 */
	public function upload() {
		$grouplist = getcache('grouplist','member');
		if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) return false;
		pc_base::load_sys_class('attachment','',0);
		$module = trim($_GET['module']);
		$catid = intval($_GET['catid']);
		$siteid = $this->get_siteid();
		$site_setting = get_site_setting($siteid);
		$site_allowext = $site_setting['upload_allowext'];		
		$attachment = new attachment($module,$catid,$siteid);
		$attachment->set_userid($this->userid);
		$a = $attachment->upload('upload',$site_allowext);
		if($a){
			$filepath = $attachment->uploadedfiles[0]['filepath'];
			$fn = $attachment->uploadedfiles[0]['fn'];
			$this->upload_json($a[0],$filepath,$attachment->uploadedfiles[0]['filename']);
			$attachment->mkhtml($fn,$this->upload_url.$filepath,'');
		}
	}
	/**
	 * swfupload上传附件
	 */
	public function swfupload(){
		$grouplist = getcache('grouplist','member');			
		if(isset($_POST['dosubmit'])){
			if( $_POST['swf_auth_key'] != md5(pc_base::load_config('system','auth_key').$_POST['SWFUPLOADSESSID']) || ($_POST['isadmin']==0 && !$grouplist[$_POST['groupid']]['allowattachment'])) exit();
			pc_base::load_sys_class('attachment','',0);
			$attachment = new attachment($_POST['module'],$_POST['catid'],$_POST['siteid']);
			$attachment->set_userid($_POST['userid']);
			$aids = $attachment->upload('Filedata',$_POST['filetype_post'],'','',array($_POST['thumb_width'],$_POST['thumb_height']),$_POST['watermark_enable']);
			if($aids[0]) {
				$filename= (strtolower(CHARSET) != 'utf-8') ? iconv('gbk', 'utf-8', $attachment->uploadedfiles[0]['filename']) : $attachment->uploadedfiles[0]['filename'];
				if($attachment->uploadedfiles[0]['isimage']) {
					echo $aids[0].','.$this->upload_url.$attachment->uploadedfiles[0]['filepath'].','.$attachment->uploadedfiles[0]['isimage'].','.$filename;
				} else {
					$fileext = $attachment->uploadedfiles[0]['fileext'];
					if($fileext == 'zip' || $fileext == 'rar') $fileext = 'rar';
					elseif($fileext == 'doc' || $fileext == 'docx') $fileext = 'doc';
					elseif($fileext == 'xls' || $fileext == 'xlsx') $fileext = 'xls';
					elseif($fileext == 'ppt' || $fileext == 'pptx') $fileext = 'ppt';
					elseif ($fileext == 'flv' || $fileext == 'swf' || $fileext == 'rm' || $fileext == 'rmvb') $fileext = 'flv';
					else $fileext = 'do';
					echo $aids[0].','.$this->upload_url.$attachment->uploadedfiles[0]['filepath'].','.$fileext.','.$filename;
				}			
				exit;
			} else {
				echo '0,'.$attachment->error();
				exit;
			}

		} else {
			if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) showmessage(L('att_no_permission'));
			$args = $_GET['args'];
			$authkey = $_GET['authkey'];
			if(upload_key($args) != $authkey) showmessage(L('attachment_parameter_error'));
			extract(getswfinit($_GET['args']));
			$siteid = $this->get_siteid();
			$site_setting = get_site_setting($siteid);
			$file_size_limit = sizecount($site_setting['upload_maxsize']*1024);		
			$att_not_used = param::get_cookie('att_json');
			if(empty($att_not_used) || !isset($att_not_used)) $tab_status = ' class="on"';
			if(!empty($att_not_used)) $div_status = ' hidden';
			//获取临时未处理文件列表
			$att = $this->att_not_used();					
			include $this->admin_tpl('fileupload');
		}
	}
	
	public function crop_upload() {	
		if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
			$pic = $GLOBALS["HTTP_RAW_POST_DATA"];
			if (isset($_GET['width']) && !empty($_GET['width'])) {
				$width = intval($_GET['width']);
			}
			if (isset($_GET['height']) && !empty($_GET['height'])) {
				$height = intval($_GET['height']);
			}
			if (isset($_GET['file']) && !empty($_GET['file'])) {
				if(is_image($_GET['file'])== false ) exit();
				if (strpos($_GET['file'], pc_base::load_config('system', 'upload_url'))!==false) {
					$file = $_GET['file'];
					$basename = basename($file);
					if (strpos($basename, 'thumb_')!==false) {
						$file_arr = explode('_', $basename);
						$basename = array_pop($file_arr);
					}
					$new_file = 'thumb_'.$width.'_'.$height.'_'.$basename;
				} else {
					pc_base::load_sys_class('attachment','',0);
					$module = trim($_GET['module']);
					$catid = intval($_GET['catid']);
					$siteid = $this->get_siteid();
					$attachment = new attachment($module, $catid, $siteid);
					$uploadedfile['filename'] = basename($_GET['file']); 
					$uploadedfile['fileext'] = fileext($_GET['file']);
					if (in_array($uploadedfile['fileext'], array('jpg', 'gif', 'jpeg', 'png', 'bmp'))) {
						$uploadedfile['isimage'] = 1;
					}
					$file_path = $this->upload_path.date('Y/md/');
					pc_base::load_sys_func('dir');
					dir_create($file_path);
					$new_file = date('Ymdhis').rand(100, 999).'.'.$uploadedfile['fileext'];
					$uploadedfile['filepath'] = date('Y/md/').$new_file;
					$aid = $attachment->add($uploadedfile);
				}
				$filepath = date('Y/md/');
				file_put_contents($this->upload_path.$filepath.$new_file, $pic);
			} else {
				return false;
			}
			echo pc_base::load_config('system', 'upload_url').$filepath.$new_file;
			exit;
		}
	}
	
	/**
	 * 删除附件
	 */
	public function swfdelete() {
		$attachment = pc_base::load_sys_class('attachment');
		$att_del_arr = explode('|',$_GET['data']);
		foreach($att_del_arr as $n=>$att){
			if($att) $attachment->delete(array('aid'=>$att,'userid'=>$this->userid,'uploadip'=>ip()));
		}
	}
	

	/**
	 * 加载图片库
	 */
	public function album_load() {
		if(!$this->admin_username) return false;
		$where = $uploadtime= '';
		$this->att_db= pc_base::load_model('attachment_model');
		if($_GET['args']) extract(getswfinit($_GET['args']));
		if($_GET['dosubmit']){
			extract($_GET['info']);
			$where = '';
			$filename = safe_replace($filename);
			if($filename) $where = "AND `filename` LIKE '%$filename%' ";
			if($uploadtime) {
				$start_uploadtime = strtotime($uploadtime.' 00:00:00');
				$stop_uploadtime = strtotime($uploadtime.' 23:59:59');
				$where .= "AND `uploadtime` >= '$start_uploadtime' AND  `uploadtime` <= '$stop_uploadtime'";				
			}
			if($where) $where = substr($where, 3);
		}
		pc_base::load_sys_class('form');
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $this->att_db->listinfo($where, 'aid DESC', $page, 8,'',5);
		foreach($infos as $n=>$v){
			$ext = fileext($v['filepath']);
			if(in_array($ext,$this->imgext)) {
				$infos[$n]['src']=$this->upload_url.$v['filepath'];
				$infos[$n]['width']='80';
			} else {
				$infos[$n]['src']=file_icon($v['filepath']);
				$infos[$n]['width']='64';
			}
		}
		$pages = $this->att_db->pages;
		include $this->admin_tpl('album_list');
	}
	
	/**
	 * 目录浏览模式添加图片
	 */
	public function album_dir() {
		if(!$this->admin_username) return false;
		if($_GET['args']) extract(getswfinit($_GET['args']));
		$dir = isset($_GET['dir']) && trim($_GET['dir']) ? str_replace(array('..\\', '../', './', '.\\','..'), '', trim($_GET['dir'])) : '';
		$filepath = $this->upload_path.$dir;
		$list = glob($filepath.'/'.'*');
		if(!empty($list)) rsort($list);
		$local = str_replace(array(PC_PATH, PHPCMS_PATH ,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR), array('','',DIRECTORY_SEPARATOR), $filepath);
		$url = ($dir == '.' || $dir=='') ? $this->upload_url : $this->upload_url.str_replace('.', '', $dir).'/';
		$show_header = true;
		include $this->admin_tpl('album_dir');
	}
	
	/**
	 * 设置upload上传的json格式cookie
	 */
	private function upload_json($aid,$src,$filename) {
		$arr['aid'] = intval($aid);
		$arr['src'] = trim($src);
		$arr['filename'] = urlencode($filename);
		$json_str = json_encode($arr);
		$att_arr_exist = param::get_cookie('att_json');
		$att_arr_exist_tmp = explode('||', $att_arr_exist);
		if(is_array($att_arr_exist_tmp) && in_array($json_str, $att_arr_exist_tmp)) {
			return true;
		} else {
			$json_str = $att_arr_exist ? $att_arr_exist.'||'.$json_str : $json_str;
			param::set_cookie('att_json',$json_str);
			return true;			
		}
	}
	
	/**
	 * 设置swfupload上传的json格式cookie
	 */
	public function swfupload_json() {
		$arr['aid'] = intval($_GET['aid']);
		$arr['src'] = trim($_GET['src']);
		$arr['filename'] = urlencode($_GET['filename']);
		$json_str = json_encode($arr);
		$att_arr_exist = param::get_cookie('att_json');
		$att_arr_exist_tmp = explode('||', $att_arr_exist);
		if(is_array($att_arr_exist_tmp) && in_array($json_str, $att_arr_exist_tmp)) {
			return true;
		} else {
			$json_str = $att_arr_exist ? $att_arr_exist.'||'.$json_str : $json_str;
			param::set_cookie('att_json',$json_str);
			return true;			
		}
	}
	
	/**
	 * 删除swfupload上传的json格式cookie
	 */	
	public function swfupload_json_del() {
		$arr['aid'] = intval($_GET['aid']);
		$arr['src'] = trim($_GET['src']);
		$arr['filename'] = urlencode($_GET['filename']);
		$json_str = json_encode($arr);
		$att_arr_exist = param::get_cookie('att_json');
		$att_arr_exist = str_replace(array($json_str,'||||'), array('','||'), $att_arr_exist);
		$att_arr_exist = preg_replace('/^\|\|||\|\|$/i', '', $att_arr_exist);
		param::set_cookie('att_json',$att_arr_exist);
	}	

	private function att_not_used() {
		$this->att_db= pc_base::load_model('attachment_model');
		//获取临时未处理文件列表
		if($att_json = param::get_cookie('att_json')) {
			if($att_json) $att_cookie_arr = explode('||', $att_json);	
			foreach ($att_cookie_arr as $_att_c) $att[] = json_decode($_att_c,true);
			if(is_array($att) && !empty($att)) {
				foreach ($att as $n=>$v) {
					$ext = fileext($v['src']);
					if(in_array($ext,$this->imgext)) {
						$att[$n]['fileimg']=$v['src'];
						$att[$n]['width']='80';
						$att[$n]['filename']=urldecode($v['filename']);
					} else {
						$att[$n]['fileimg']=file_icon($v['src']);
						$att[$n]['width']='64';
						$att[$n]['filename']=urldecode($v['filename']);
					}
					$this->cookie_att .=	'|'.$v['src'];
				}
			}			
		}
		return $att;
	}
	
	final public static function admin_tpl($file, $m = '') {
		$m = empty($m) ? ROUTE_M : $m;
		if(empty($m)) return false;
		return PC_PATH.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$file.'.tpl.php';
	}
	final public static function get_siteid() {
		return get_siteid();
	}	
}
?>