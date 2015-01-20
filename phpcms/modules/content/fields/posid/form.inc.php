function posid($field, $value, $fieldinfo) {
		$setting = string2array($fieldinfo['setting']);
		$position = getcache('position','commons');
		if(empty($position)) return '';
		$array = array();
		foreach($position as $_key=>$_value) {
			if($_value['modelid'] && ($_value['modelid'] !=  $this->modelid) || ($_value['catid'] && strpos(','.$this->categorys[$_value['catid']]['arrchildid'].',',','.$this->catid.',')===false)) continue;
			//$array[$_key] = $_value['name'];
			//推荐位分组
			$_value['typeid'] = $_value['typeid']?$_value['typeid']:'0';
			$array[$_value['typeid']][$_key] = $_value['name'];
		}
		krsort($array);
		$posids = array();
		if(ROUTE_A=='edit') {
			$this->position_data_db = pc_base::load_model('position_data_model');
			$result = $this->position_data_db->select(array('id'=>$this->id,'modelid'=>$this->modelid),'*','','','','posid');
			$posids = implode(',', array_keys($result));
		} else {
			$posids = $setting['defaultvalue'];
		}
        
		/*
		推荐位分组实现
		*/
		 //分类
		$types = $this->type_db->select(array('siteid'=> get_siteid(),'module'=>'position'), 'name, typeid', '', '`listorder` ASC, `typeid` ASC', '', 'typeid');
		$str =  "<input type='hidden' name='info[$field][]' value='-1'>";
		foreach($array as $k=>$v)
		{
			$typename = $types[$k]['name']?$types[$k]['name']:'未分组';
			$str .= "<h6>".$typename."</h6><hr size=\"1\" color=\"#66FFFF\">".form::checkbox($v,$posids,"name='info[$field][]'",'',$setting['width']);
		}
		
        
        return $str;
		//return "<input type='hidden' name='info[$field][]' value='-1'>".form::checkbox($array,$posids,"name='info[$field][]'",'',$setting['width']);
	}