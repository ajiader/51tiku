<?php
/*判断登陆ajax*/
defined('IN_PHPCMS') or exit('No permission resources.');

$_GET['callback'] = safe_replace($_GET['callback']);	

$phpcms_auth = param::get_cookie('auth');
if($phpcms_auth) {
	$auth_key = md5(pc_base::load_config('system', 'auth_key').$_SERVER['HTTP_USER_AGENT']);
	list($userid, $password) = explode("\t", sys_auth($phpcms_auth, 'DECODE', $auth_key));
	if($userid >0) {
		exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>$userid)).')');
	} else {
		exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
	} 
} else {
	exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
}

?>