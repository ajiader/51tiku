<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=<?=ROUTE_M?>&c=<?=ROUTE_C?>&a=<?=ROUTE_A?>&id=<?php echo $_GET['id']?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
	  <th><strong>目录名称：</strong></th>
	  <td><input name="diypage[dirname]" type="text" class="input-text" id="dirname" value="<?=$info['dirname']?>" size="50" /></td>
	  </tr>
	<tr>
		<th width="50"><strong>标题：</strong></th>
		<td width="444"><input name="diypage[title]" type="text" class="input-text" id="title" value="<?=$info['title']?>" size="50" ></td>
	</tr>
	
	<tr>
	  <th><strong>关键词：</strong></th>
	  <td><input name="diypage[keywods]" type="text" class="input-text" id="keywods" value="<?=$info['keywods']?>" size="50" /></td>
	  </tr>
<tr>
	  <th><strong>描述：</strong></th>
	  <td><textarea name="diypage[description]" cols="50" class="input-text" id="description"><?=$info['description']?></textarea></td>
	  </tr>     
	<tr>
	  <th><strong>面包屑：</strong></th>
	  <td><textarea name="diypage[breadcrumb]" cols="50" class="input-text" id="breadcrumb"><?=$info['breadcrumb']?></textarea></td>
	  </tr>  
 	  
	<tr>
		<th><strong>H1：</strong></th>
		<td><input name="diypage[h1]" type="text" class="input-text" id="diypage[h1]" value="<?=$info['h1']?>" size="50" /></td>
	</tr>
	
		<tr>
		<th><strong>H2：</strong></th>
		<td><input name="diypage[h2]" type="text" class="input-text" id="diypage[h2]" value="<?=$info['h2']?>" size="50" />多个以|隔开，例如（标题1|标题2）</td>
	</tr>
        
		
		
		<tr>
		  <th><strong>友情链接：</strong></th>
		  <td><textarea name="diypage[link]" cols="50" rows="5" class="input-text" id="link"><?=$info['link']?></textarea></td>
	    </tr>
	
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">

$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#dirname').formValidator({onshow:"输入目录名称",onfocus:"最少一个字",oncorrect:"ok"}).inputValidator({min:1,onerror:"目录不能为空"}).ajaxValidator({type:"get",url:"",data:"m=diypage&c=diypage&a=public_check_title&id=<?php echo $_GET['id']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "目录名称已经存在",
	onwait : "加载中..."
	});
	


	

});
</script>