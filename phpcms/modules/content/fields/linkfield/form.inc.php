	function linkfield($field, $value, $fieldinfo) {
        extract($fieldinfo);
        $setting = string2array($setting);
        
        if($fieldinfo['link_type']){
		
        	$get_db = pc_base::load_model("get_model");
            
            $sel_tit=$setting['select_title']?$setting['select_title']:'*';
            
			$where = "where 1=1";
			if($setting['where_sql']){
			    $where .= " and ".$setting['where_sql']."";
			}
            $sql = "SELECT ".$sel_tit." FROM `".$setting['table_name']."`".$where;
            $r= $get_db->query($sql);
            while(($s = $get_db->fetch_next()) != false) {
                $dataArr[] = $s;
            } 
            $value = str_replace('&amp;','&',$value);
            $data = '<select name="info['.$fieldinfo['field'].']" id="'.$fieldinfo['field'].'"><option>请选择</option>';
			
            foreach($dataArr as $v) {
				if($fieldinfo['insert_type']=="id"){
					$output_type = $v[$fieldinfo['set_id']];
				}elseif($fieldinfo['insert_type']=="title"){
					$output_type = $v[$fieldinfo['set_title']];
				}else{
					$output_type = $v[$fieldinfo['set_title']].'_'.$v[$fieldinfo['set_id']];
				}   
                if($output_type == $value) $select = 'selected';
                else $select = '';
                $data .= "<option value='".$output_type."' ".$select.">".$v[$fieldinfo['set_title']]."</option>\n";
            }
            $data .= '</select>';

        }else{
            $data = <<<EOT
            <style type="text/css">
            .content_div{ margin-top:0px; font-size:14px; position:relative}
            #search_div{$field}{ position:absolute; top:23px; border:1px solid #dfdfdf; text-align:left; padding:1px; left:0px;*left:0px; width:263px;*width:260px; background-color:#FFF; display:none; font-size:12px;}
            #search_div{$field} li{ line-height:24px;cursor:pointer}
            #search_div{$field} li a{  padding-left:6px;display:block}
            #search_div{$field} li a:hover, #search_div{$field} li:hover{ background-color:#e2eaff}
            </style>
            <div class="content_div">
                <input type="text" size="41" id="cat_search{$field}" value="" onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;" class='input-text'><input name="info[{$fieldinfo['field']}]" id="{$fieldinfo['field']}" type="hidden" class='input-text' value="{$value}" size="41"/>
                <ul id="search_div{$field}"></ul>
            </div>		
            <script type="text/javascript" language="javascript" >
                function setvalue{$field}(title,id)
                {
                    var title = title;
                    var id = id;
                    var type = "{$fieldinfo['insert_type']}";
                    if(type == "id")
                    {
                        $("#{$fieldinfo['field']}").val(id);
                    }
                    else if(type == "title")
                    {
                        $("#{$fieldinfo['field']}").val(title);
                    }
                    else if(type == "title_id")
                    {
                        $("#{$fieldinfo['field']}").val(title+'|'+id);
                    }
                    $("#cat_search{$field}").val(title);
                    $('#search_div{$field}').hide();
                }
				
            $(document).ready(function(){
				if($("#{$fieldinfo['field']}").val().length > 0){
				
					var value = $("#{$fieldinfo['field']}").val();
					var tablename = '{$fieldinfo['table_name']}';
					var set_title = '{$fieldinfo['set_title']}';
					var set_id = '{$fieldinfo['set_id']}';
					var set_type = '{$fieldinfo['insert_type']}';
					$.getJSON('kapi.php?op=ajax_my_linkfield&act=check_search&callback=?', {value: value,table_name: tablename,set_title: set_title,set_id: set_id,set_type: set_type,random:Math.random()}, function(data2){
						if (data2 != null) {
							$.each(data2, function(i,n){				
							$('#cat_search{$field}').val(n.{$fieldinfo['set_title']});
							});
						} else {
							$('#search_div{$field}').hide();
						}
					});
					
				}

				$('#cat_search{$field}').keyup(function(){
					var value = $("#cat_search{$field}").val();
					var tablename = '{$fieldinfo['table_name']}';
					var select_title = '{$fieldinfo['select_title']}';
					var like_title = '{$fieldinfo['like_title']}';
					var set_title = '{$fieldinfo['set_title']}';
					var set_id = '{$fieldinfo['set_id']}';
					
					if (value.length > 0){
						$.getJSON('kapi.php?op=ajax_my_linkfield&act=search_ajax&callback=?', {value: value,table_name: tablename,select_title: select_title,like_title: like_title,set_title: set_title,set_id: set_id,limit: 20,random:Math.random()}, function(data){
							if (data != null) {
								var str = '';
								$.each(data, function(i,n){
									str += '<li onclick=\'setvalue{$field}("'+n.{$fieldinfo['set_title']}+'","'+n.{$fieldinfo['set_id']}+'");\'>'+n.{$fieldinfo['set_title']}+'</li>';
								});
								$('#search_div{$field}').html(str);
								$('#search_div{$field}').show();
							} else {
								$('#search_div{$field}').hide();
							}
						});
					} else {
						$('#search_div{$field}').hide();
					} 
				});	
            })
            </script>
EOT;
		}
	return $data;
 } 