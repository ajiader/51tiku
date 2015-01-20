<?php
$siteurl = siteurl(get_siteid());
 $typedir = (isset($_GET['typedir']))?safe_replace($_GET['typedir']):'';
define('KSBM_TITLE', '全国各省人事考试网上报名系统');//问答标题
define('KSBM_PATH',$siteurl.'/'.$typedir.'/');//问答首页路径


?>