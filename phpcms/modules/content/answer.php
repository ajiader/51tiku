<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
//定义在单独操作内容的时候，同时更新相关栏目页面
define('RELATION_HTML',true);

pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);
pc_base::load_app_func('util');
pc_base::load_sys_class('format','',0);

class answer extends admin
{
	private $db;
	public $siteid;
	function __construct() {
		parent::__construct();
		$this->qdb = pc_base::load_model('content_model');
		$this->qdb->set_model('20');
		$this->adb = pc_base::load_model('tiku_answer_model');
		$this->siteid = $this->get_siteid();
		if(!$this->siteid) $this->siteid = 1;
	}

	public function init()
	{
		
		$qid = intval($_GET['qid']);
		if(empty($qid)) { showmessage('参数错误', HTTP_REFERER, '', 'init');}
		$qrow = $this->qdb->get_one(array('id'=>$qid));
		$arow = $this->adb->select(array('qid'=>$qid));
		include $this->admin_tpl('content_edit_answer');
	}

	public function update()
	{

		if(isset($_POST['dosubmit'])) { 

			if (!empty($_POST['answer'])) {

				$answers = array();
				foreach ($_POST['answer']['answer'] as $key => $value) {
					if($value != null){
						$answers['answer'] = $value;
						$answers['right'] = $_POST['answer']['right'][$key];
						$answers['qid'] = intval($_POST['answer']['qid']);
						$aid = intval($_POST['answer']['aid'][$key]);
						if ($aid > 0) {
							$this->adb->update($answers, array('aid' => $aid));
						}else{
							$this->adb->insert($answers);
						}
					}
				}
				showmessage('提交成功', HTTP_REFERER, '', 'init');
			}
		}
	}

	public function delete()
	{
		$aid = intval($_GET['aid']);
		if(empty($aid)) { showmessage('参数错误', HTTP_REFERER, '', 'init');}
		$this->adb->delete(array('aid' => $aid));
		showmessage('删除成功', HTTP_REFERER, '', 'init');

	}
}