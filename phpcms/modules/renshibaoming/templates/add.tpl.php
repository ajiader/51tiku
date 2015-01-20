<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=renshibaoming&c=renshibaoming&a=add" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="123"><strong><?php echo L('renshibaoming_title')?></strong></th>
		<td width="350"><input name="renshibaoming[title]" id="title" class="input-text" type="text" size="50" ></td>
	</tr>
	
	<tr>
	  <th>长标题：</th>
	  <td><input name="renshibaoming[seotitle]" id="title2" class="input-text" type="text" size="50" /></td>
	  </tr>
	<tr>
		<th><strong><?php echo L('renshibaoming_url')?></strong></th>
		<td><input name="renshibaoming[url]" id="url" class="input-text" type="text" size="50" /></td>
	</tr>
	
		<tr>
		<th><strong><?php echo L('renshibaoming_city')?></strong></th>
		<td>&nbsp;
        <select name="renshibaoming[cityid]" id="cityid">
        <option value="">请选择</option>
        <?php
        foreach($linkage_list  as $v):
		?>
        <option value="<?=$v['linkageid']?>"><?=$v['name']?></option>
        <?php
        endforeach;
		?>
        </select></td>
	</tr>
		<tr>
		  <th><strong>类型：</strong></th>
		  <td> &nbsp;
		    <select name="renshibaoming[typeid]" id="typeid">
		    <option value="">请选择</option>
		    <?php
        foreach($type_list  as $v):
		?>
		    <option value="<?=$v['typeid']?>">
		      <?=$v['name']?>
		      </option>
		    <?php
        endforeach;
		?>
	      </select></td>
	    </tr>
		<tr>
		  <th><strong>文件名:</strong></th>
		  <td><input name="renshibaoming[filename]" id="filename" class="input-text" type="text" size="20" />
	      .html </td>
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
	$('#title').formValidator({onshow:"<?php echo L('input_renshibaoming_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=renshibaoming&c=renshibaoming&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("<?php echo L('server_no_data')?>");},
	onerror : "<?php echo L('renshibaoming_exist')?>",
	onwait : "<?php echo L('checking')?>"
	});
	
	$('#url').formValidator({onshow:"<?php echo L('input_renshibaoming_url')?>",onfocus:"<?php echo L('input_renshibaoming_url')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('input_renshibaoming_url')?>"});

	$('#cityid').formValidator({onshow:"<?php echo L('input_renshibaoming_city')?>",onfocus:"<?php echo L('input_renshibaoming_city')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('input_renshibaoming_city')?>"});
	
	$('#typeid').formValidator({onshow:"请选择入口类型!",onfocus:"请选择入口类型!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请选择入口类型!"});

	$('#filename').formValidator({onshow:"请输入文件名!",onfocus:"请输入文件名",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入文件名不能为空"}).ajaxValidator({type:"get",url:"",data:"m=renshibaoming&c=renshibaoming&a=public_check_filename",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("服务器没有返回数据，可能服务器忙，请重试");},
	onerror : "该文件名已存在",
	onwait : "正进行合法性校验..."
	});
});



</script>