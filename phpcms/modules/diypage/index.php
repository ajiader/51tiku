<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_func('global','content');
class index {
	function __construct() {
		$this->db = pc_base::load_model('diypage_model');
		if(isset($_GET['siteid'])) {
			$siteid = intval($_GET['siteid']);
		} else {
			$siteid = 1;
		}
		$this->siteid = $siteid;
	}

	public function init()
	{
		$siteid = $this->siteid;
		$CATEGORYS = getcache('category_content_'.$this->siteid,'commons');//加载栏目缓存
		$dirname=safe_replace($_GET['dirname']);

		$row = $this->db->get_one("siteid=$siteid and  dirname='$dirname'");
		if (!$row) {
			require PHPCMS_PATH.'404-1.php';

		    @header('HTTP/1.1 404 Not Found');
		    @header('Status: 404 Not Found');
			echo 404;
		    exit;
		}
		extract($row);

		if ($h2) {
			$h2_arr = explode('|', $h2);
			foreach ($h2_arr as $key => $value) {
				$h2arr[$key]['like'] = fenciSql($value,'title','or');
				$h2arr[$key]['name'] = $value;
			}

		}

		$SEO = seo($siteid, '',$row['title'],$row['description'],$row['keywords']);
		ob_start();
		include template('diypage','index_'.$siteid);
		echo mem_page(1);
	}
}