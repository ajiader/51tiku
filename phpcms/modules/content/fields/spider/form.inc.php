	function spider($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		$size = $setting['size'];
		if(!$value) $value = $defaultvalue;
		$type = $ispassword ? 'password' : 'text';
		$errortips = $this->fields[$field]['errortips'];
		if($errortips || $minlength) $this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		$this->nodedb = pc_base::load_model('collection_node_model');
		$nodelist = $this->nodedb->select(array('siteid'=>$this->siteid),'nodeid,name','','nodeid DESC');
		$buttons = $this->select2arr($nodelist, '', 'id=\'nodeid\'', '选择规则');
		$buttons .=  '<script type="text/javascript">
function dataTrans(str)
{
	var obj = {};
	str = str.replace( /^Array\s*\(/,\'\' ).replace( /\)\s*$/,\'\' );
	str.replace( /\[(\w+)\]\s+=>((?:[\S\s](?!\[(\w+)\]\s+=>))+)/g,function( m,$1,$2 )
	{
		obj[$1] = $2;
	});
	return obj;
}
function trim(str){
return str.replace(/(^\s*)|(\s*$)/g, "");
}
</script><input type="button" class="button" id="check_spider" value="开始抓取" onclick="if(!$(\'#nodeid\').val() || !$(\'#'.$field.'\').val()) {alert(\'您忘了选择站点规则还是没有填写目标网址?\');return false;}$(\'#check_spider\').val(\'正在抓取…\');$(\'#check_spider\').attr(\'disabled\',true);$.get(\'?m=collection&c=node&a=public_test_content&sid=\'+Math.random()*5, {url:$(\'#'.$field.'\').val(),nodeid:$(\'#nodeid\').val()}, function(data){if(data) {var datas = dataTrans(data);for(var c in datas){if($(\'#\'+c)){if(c==\'content\'){CKEDITOR.instances.content.setData(datas[c].replace(/\\\&quot;/g,\'\'));} else{$(\'#\'+c).val(trim(datas[c]));}}};$(\'#check_spider\').val(\'成功抓取\');} else{alert(\'你确定你的目标地址是标准的http格式或者目标网址可以访问?\');$(\'#check_spider\').val(\'抓取失败\');}$(\'#check_spider\').attr(\'disabled\',false)})" style="width:73px;"/>';
		return '<input type="text" name="info['.$field.']" id="'.$field.'" size="'.$size.'" value="'.$value.'" class="input-text" '.$formattribute.' '.$css.'>'.$buttons;
	}

	public static function select2arr($array = array(), $id = 0, $str = '', $default_option = '') {
		$string = '<select '.$str.'>';
		$default_selected = (empty($id) && $default_option) ? 'selected' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		if(!is_array($array) || count($array)== 0) return false;
		foreach($array as $key=>$vs) {
			//$selected = $id==$key ? 'selected' : '';
			$string .= '<option value="'.$vs['nodeid'].'" >'.$vs['name'].'</option>';
		}
		$string .= '</select>';
		return $string;
	}
