<?php
/*
地方站查询数据 子地方站没有查询上级 递归
@siteid 站点id
@citylist 当前地方的城市的array
@catids 查询的catid集合
@condition 查询条件
*/
function city_news_list($siteid, $citylist, $catids, $limit="5",$order='id desc',  $condition="status = '99'")
{
	pc_base::load_sys_class("get_model", "model", 0);
	$get_db = new get_model();
	$db = pc_base::load_model('content_model');
	$modelid=($siteid==2)?'13':'1';
	$MODEL = getcache('model','commons');
	$tablename = $db->db_tablepre.$MODEL[$modelid]['tablename'];
	$sql = "SELECT url,title,updatetime,catid,city_id FROM $tablename WHERE catid in($catids) and $condition and  city_id in (".$citylist['arrchildid'].")  order by $order limit $limit";
	$r = $get_db->sql_query($sql);
	while(($s = $get_db->fetch_next()) != false) {
			$a[] = $s;
	}
	$list = $a;unset($a);
	if($list == false){
		if ($citylist[parentid] > 0) {
			$q = $get_db->sql_query("select * from v9_linkage WHERE linkageid=$citylist[parentid]");
			$clist= $get_db->fetch_next();
			return city_news_list($siteid, $clist, $catids,$limit,'rand()');
		}
	}
	 // print_r($list);
	return $list;
}

/*
*地区url转换
*/
function getCityPinyin($cityurl)
{
	if(strpos($cityurl,'_')){
		$arr = explode('_', $cityurl);
		$cityurl = implode('/', $arr);
	}
	return $cityurl;
}
function getCityUrl($cityurl)
{
	if(strpos($cityurl,'/')){
		$arr = explode('/', $cityurl);
		$cityurl = implode('_', $arr);
	}
	return $cityurl;
}

/*
@地区过滤 自治县 地区 市 州 区 县 自治州
@str 
*/
function citys2city($str)
{
	// echo strpos('贵州省','州市');
	if (strstr($str,'市中区')) {
		return $str;
		exit;
	}
	elseif (strpos( $str,'自治县')) {
		$str = str_replace('自治县', '', $str);
	}
	elseif (strpos( $str,'自治州')) {
		$str = str_replace('自治州', '', $str);
	}
	elseif (strpos( $str,'地区')) {
		$str = str_replace('地区', '', $str);
	}
	elseif (strpos( $str,'州')) {
		$str = substr($str, 0, -3);
	}
	elseif (strlen($str) > 6 ) {
		$qu_array = array('市', '州', '区', '县','省');
		$str = str_replace($qu_array, '', $str);
	}

	return $str;
}
/*
*分词查询sql
*/
function fenciSql($q,$field,$c="and")
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
						$where_q .= "$field like '%$value%' $c ";
					}
					
				}
				if (strlen($where_q)>5) {
					$where_q = substr($where_q, 0, -4);
					$where_q .= ')';
				}else{
					$where_q='';
				}
				
			}
		return $where_q;
	}
/*
*职业资格的模块调用
*/
function GetZrMokuai($where = '', $data = '*', $limit = '')
{
	$zrdb = pc_base::load_model('zirui_mokuai_model');
	$zrlist = $zrdb->select($where , $data, $limit);
	$zrarr = array();
	if ($zrlist) {
		foreach ($zrlist as $key => $value) {
			$zrarr[$value['typeid']][$key] = $value;
		}
		return($zrarr);
	}
}
/*
类型调用
*/
function GetTypeName($where = '', $data = '*', $limit = '')
{
	$typedb = pc_base::load_model('linkage_model');
	$list = $typedb->select($where , $data, $limit);
	$zrarr = array();
	if ($list) {
		foreach ($list as $key => $value) {
			$zrarr[$value['linkageid']] = $value;
		}
		return($zrarr);
	}
}
/*
类型调用
*/
function get_typename($typeid="")
{
	$typedb = pc_base::load_model('type_model');
	$list = $typedb->get_one("typeid=$typeid and module='content'");
	return($list['name']);
}
/*
mokuai调用
*/
function get_mokuainame($mid="")
{
	$typedb = pc_base::load_model('zirui_mokuai_model');
	$list = $typedb->get_one("id=$mid");
	return($list['name']);
}
?>