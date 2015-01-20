<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=<?=ROUTE_M?>&c=<?=ROUTE_C?>&a=<?=ROUTE_A?>&id=<?php echo $_GET['id']?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="50"><strong>老师姓名：</strong></th>
		<td width="444"><input name="teacher[name]" type="text" class="input-text" id="name" value="<?=$info['name']?>" size="50" ></td>
	</tr>
	
	<tr>
	  <th><strong>老师称号：</strong></th>
	  <td><input name="teacher[title]" type="text" class="input-text" id="title" value="<?=$info['title']?>" size="50" /></td>
	  </tr>
      <tr>
		  <th><strong>所属类别：</strong></th>
		  <td><?php
           echo form::select($typelist,$info['typeid'],'name="teacher[typeid]"','请选择');
		   ?>
		  </td>
	    </tr>
	<tr>
		<th><strong>老师介绍：</strong></th>
		<td><textarea name="teacher[description]" cols="50" rows="5" class="input-text" id="teacher[description]"><?=$info['url']?><?=$info['description']?></textarea></td>
	</tr>
	
		<tr>
		<th><strong>主讲课程：</strong></th>
		<td>
		  <input name="teacher[lesson]" type="text" class="input-text" id="teacher[lesson]" value="<?=$info['lesson']?>" size="50" /></td>
	</tr>
		<tr>
		  <th><strong>头像上传：</strong></th>
		  <td>
		    <?php echo form::images('teacher[thumb]', 
'thumb', $info['thumb'], 'teacher')?></td>
	    </tr>
		<tr>
		  <th><strong>微博地址：</strong></th>
		  <td><input name="teacher[weibourl]" id="weibourl" class="input-text" value="<?=$info['weibourl']?>" type="text" size="50" /></td>
	    </tr>
		<tr>
		  <th><strong>博客地址：</strong></th>
		  <td><input name="teacher[blogurl]" id="blogurl" class="input-text" value="<?=$info['blogurl']?>" type="text" size="50" /></td>
	    </tr>
	
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
function load_file_list(id) {
	if (id=='') return false;
	$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=teacher&templates=show&name=teacher&pc_hash='+pc_hash, function(data){$('#show_template').html(data.show_template);});
}

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#name').formValidator({onshow:"输入老师名称",onfocus:"最少一个字",oncorrect:"ok"}).inputValidator({min:1,onerror:"老师姓名不能为空"}).ajaxValidator({type:"get",url:"",data:"m=teacher&c=teacher&a=public_check_title&id=<?php echo $_GET['id']?>",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("数据错误");},
	onerror : "老师已经存在",
	onwait : "加载中..."
	});
	


	

});
</script>