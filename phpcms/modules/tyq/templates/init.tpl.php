<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">

<form action="" method="get">


<label for="select">类型：</label>
<select name="catid" id="select" >
<option value="">所有</option>
 <?php
        foreach($catarr  as $k=>$v):
		?>
		    <option value="<?=$k?>" <?php if($catid == $k): ?> selected="selected"<?php endif;?>>
		      <?=$v?>
      </option>
		    <?php
        endforeach;
		?>
</select>

<label for="select">是否处理：</label>
<select name="status" id="select" >
<option value="">所有</option>
 <option value="0" <?php if($status==0 && isset($_GET['status'])): ?> selected="selected"<?php endif;?>>
		   未处理
		      </option>
		    <option value="1" <?php if($status == 1): ?> selected="selected"<?php endif;?>>
		   已经处理
		      </option>
		
</select>
<input name="search" type="submit" value="查询" class="button">
<input type="hidden" name="m" value="tyq"><input type="hidden" name="c" value="tyq"><input type="hidden" name="a" value="init">
<input name="pc_hash" type="hidden" value=""></form>

</div>
<div class="pad-lr-10">
<form name="myform" action="?m=teacher&c=admin_teacher&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="20" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="20" align="center">ID</th>
			<th width="89" align="center">考试类型</th>
			<th width="89" align="center">姓名</th>
		
			<th width="68" align="center">手机</th>
			<th width="68" align="center">QQ/身份号</th>
			<th width="77" align="center">email</th>
            
			<th width="77" align="center"><?php if($catid==100):?>省份<?php else: ?>准考证号<?php endif; ?></th>
            <th width="77" align="center">提交时间</th>
            <th width="105" align="center">是否处理</th>		
			<th width="105" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	foreach($list as $teacher){
?>   
	<tr align="center">
	<td>
	<input type="checkbox" name="id[]" value="<?php echo $teacher['id']?>">
	</td>
	<td><?php echo $teacher['id']?></td>
	<td><?php echo $catarr[$teacher['catid']]?></td>
	<td><?php echo $teacher['name']?></td>
	
	<td><?php echo $teacher['phone']?></td>
	<td><?php echo $teacher['qq']? $teacher['qq']:''?></td>
    <td><?php echo $teacher['email']? $teacher['email']:'' ?></td>
    <td><?php if($catid==100):?><?php echo $teacher['province']? $teacher['province']:'' ?><?php else: ?><?php echo $teacher['zkzh']? $teacher['zkzh']:'' ?><?php endif; ?></td>
	<td><?php if($teacher['inputtime']): ?><?php echo date('Y-m-d H:i:s', $teacher['inputtime'])?><?php endif; ?></td>
	<td><?php echo ($teacher['status']==1)?'是':'<font color="#FF0000">否</font>' ;?></td>
	<td><?php if($teacher['status']==0){ ?><a href="?m=tyq&c=tyq&a=ychuli&id=<?php echo $teacher['id']?>" onclick="return confirm('确定已经处理了该条信息?')">设置已处理</a><?php }else{echo 'ok';}?>
	</td>
	</tr>
<?php 
	}
}
?>
    </tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

    <input name="submit" type="submit" class="button" value="删除选中" onClick="document.myform.action='?m=<?=ROUTE_M?>&c=<?=ROUTE_C?>&a=delete';return confirm('是否删除选中？')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_teacher')?>--'+title, id:'edit', iframe:'?m=teacher&c=teacher&a=edit&id='+id ,width:'700px',height:'400px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>