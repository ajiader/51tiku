<?php
/**
 *  index.php PHPCMS 入口
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-1
 */
 //PHPCMS根目录
$password='caiji1';	//验证密码
$mysiteid=1; //站点id,phpcmsv9支持多站点的，默认的是1.如果你使用了多站点，在 设置 > 相关设置 > 站点管理 > 中可以看到对应的id
$myuserid=2; //用户id，默认的超级管理员就是1，查看用户id的方法。使用那个帐号登陆，然后选修改密码，最后在 修改密码这个网页源代码中寻找 <input type="hidden" name="info[userid]" value="1"></input> 这样的代码，其中1就是用户id

//关于模块的制作，使用默认的发布模块即可

//----以下不要修改
$myroleid=1; //这个不要修改，对于发布的用户，使用超级管理员权限
if(!isset($_GET['pw'])|| $password!=$_GET['pw']) exit('验证密码错误');		//安全检测,密码不符则退出
define('PHPCMS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
session_start();
$_SESSION['userid']=$myuserid;
$_SESSION['roleid']=$myroleid;
$_POST['pc_hash']=$_SESSION['pc_hash']='rq204';

include PHPCMS_PATH.'/phpcms/base.php';

$catedata = getcache('category_content_'.$mysiteid,'commons');
if($_SERVER['REQUEST_METHOD']=='GET')
{
	$cates=array();
	foreach($catedata as $rt)
	{
		$cates[]=array('pid'=>$rt['parentid'],'cid'=>$rt['catid'],'cname'=>$rt['catname']);
	}

	echo "<select name='list'>";
	echo maketree($cates,0,'');
	echo '</select>';
	exit();
}
else
{
	$exsits=false;
	foreach($catedata as $rt)
	{
		if($rt['catid']==$_POST['info']['catid']) $exsits=true;
	}
	if(!$exsits) exit('该栏目不存在');
}

pc_base::load_sys_class('param');
param::set_cookie('siteid',sys_auth($mysiteid,'ENCODE'));
param::set_cookie('userid',sys_auth($myuserid,'ENCODE'));
param::set_cookie('roleid',sys_auth($myroleid,'ENCODE'));

pc_base::creat_app();


/***生成目录的一个遍历算法***/
function maketree($ar,$id,$pre)
{
	$ids='';
	foreach($ar as $k=>$v){
		$pid=$v['pid'];
		$cname=$v['cname'];
		$cid=$v['cid'];
		if($pid==$id)
		{
			$ids.="<option value='$cid'>{$pre}{$cname}</option>";
			foreach($ar as $kk=>$vv)
			{
				$pp=$vv['pid'];
				if($pp==$cid)
				{ 
					$ids.=maketree($ar,$cid,$pre."&nbsp;&nbsp;");
					break;
				}
			}
		}
	}
	return $ids;
}

?>