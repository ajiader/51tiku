<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=content&c=answer&a=update" name="myform" id="myform">
<table id="myTable" class="table_form" width="100%">
<tbody>
	<tr>
	  <td>问题：</td>
	  <td><?php echo $qrow['title']?></td>
	  <td>&nbsp;</td>
	  </tr>
     
<tr >
	  <td  valign="middle">&nbsp;</td>
	  <td ><input type="button" name="newBtn" id="newBtn" class="button" value="添加一行"></td>
	  <td  >&nbsp;</td>
	  </tr>
      <?php
	  if($arow):
	  $d=0;
	   foreach($arow as $av): ?>
       <tr >
	  <td  valign="middle">
	    <label for="answer">答案：</label>
	    </th>
	    </td>
	  <td ><textarea name="answer[answer][<?php echo $d ?>]" cols="40" id="answer"><?php echo $av['answer']?></textarea></td>
	  <td  ><label for="answer[right]">是否正确答案</label>
	   <input type="checkbox" name="answer[right][<?php echo $d ?>]"  value="1" <?php if($av['right'] == 1):?> checked<?php endif; ?>>
	   <input type="hidden" name="answer[aid][<?php echo $d ?>]" value="<?php echo $av['aid']?>">
	   &nbsp; 	&nbsp; &nbsp;   &nbsp; &nbsp;   
       <input type="button" name="button" class="button" onclick="javascript:if(confirm('是否要删除?'))location.href='?m=content&c=answer&a=delete&aid=<?php echo $av['aid']?>&pc_hash=<?php echo $_GET['pc_hash']?>';"  value="删除"></td>
    </tr>
    <?php $d++; endforeach; endif; ?>
	  </tbody>
</table>
<input type="hidden" name="answer[qid]" value="<?php echo $_GET['qid']?>">
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="button">
&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
function load_file_list(id) {
	$.getJSON('?m=admin&c=category&a=public_tpl_file_list&style='+id+'&module=announce&templates=show&name=announce&pc_hash='+pc_hash, function(data){$('#show_template').html(data.show_template);});
}

$(document).ready(function(){
	$("#newBtn").bind("click", function(){
  		//alert($("input:checked"));
		var trnum;
		trnum = ($("#myTable tr").length-2);
 		 $("#myTable").append("<tr><td valign=\"middle\"> <label for=\"answer\">答案：</label></td><td ><textarea name=\"answer[answer]["+trnum+"]\" cols=\"40\" id=\"answer\"></textarea></td><td><label for=\"answer[right]\">是否正确答案</label><input type=\"checkbox\" name=\"answer[right]["+trnum+"]\" id=\"radio\" value=\"1\"></td></tr>");
 }); 
 
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#title').formValidator({onshow:"<?php echo L('input_announce_title')?>",onfocus:"<?php echo L('title_min_3_chars')?>",oncorrect:"<?php echo L('right')?>"}).inputValidator({min:1,onerror:"<?php echo L('title_cannot_empty')?>"}).ajaxValidator({type:"get",url:"",data:"m=announce&c=admin_announce&a=public_check_title&aid=<?php echo $_GET['aid']?>",datatype:"html",cached:false,async:'true',success : function(data) {
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
	onerror : "<?php echo L('announce_exist')?>",
	onwait : "<?php echo L('checking')?>"
	}).defaultPassed();
	$('#starttime').formValidator({onshow:"<?php echo L('select_stardate')?>",onfocus:"<?php echo L('select_stardate')?>",oncorrect:"<?php echo L('right_all')?>"}).defaultPassed();
	$('#endtime').formValidator({onshow:"<?php echo L('select_downdate')?>",onfocus:"<?php echo L('select_downdate')?>",oncorrect:"<?php echo L('right_all')?>"}).defaultPassed();
	$("#content").formValidator({autotip:true,onshow:"",onfocus:"<?php echo L('announcements_cannot_be_empty')?>"}).functionValidator({
	    fun:function(val,elem){
	    //获取编辑器中的内容
		//var oEditor = CKEDITOR.instances.content;
		//var data = oEditor.getData();
        if(typeof(CKEDITOR)!='undefined'){
			var oEditor = CKEDITOR.instances.content;
			var data = oEditor.getData();			
		}else{
			var data = editor_content.getContent();			
		}
        if(data==''){
		    return "<?php echo L('announcements_cannot_be_empty')?>"
	    } else {
			return true;
		}
	}
	}).defaultPassed();
	$('#style').formValidator({onshow:"<?php echo L('select_style')?>",onfocus:"<?php echo L('select_style')?>",oncorrect:"<?php echo L('right_all')?>"}).inputValidator({min:1,onerror:"<?php echo L('select_style')?>"}).defaultPassed();
});
</script>