<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">

<form action="" method="get">

<label>&nbsp;&nbsp;老师姓名：</label>
<input name="name" type="text" value="<?=$title?>" class="input-text">

<label for="select">类型：</label>
<select name="typeid" id="select" >
<option value="">所有</option>
 <?php
        foreach($type_list  as $v):
		?>
		    <option value="<?=$v['typeid']?>" <?php if($typeid == $v['typeid']): ?> selected="selected"<?php endif;?>>
		      <?=$v['name']?>
		      </option>
		    <?php
        endforeach;
		?>
</select>
<input name="search" type="submit" value="查询" class="button">
<input type="hidden" name="m" value="teacher"><input type="hidden" name="c" value="teacher"><input type="hidden" name="a" value="init">
<input name="pc_hash" type="hidden" value="n5Eraa"></form>

</div>
<div class="pad-lr-10">
<form name="myform" action="?m=teacher&c=admin_teacher&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="20" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="20" align="center">ID</th>
			<th width="89" align="center">姓名</th>
		
			<th width="68" align="center">称号</th>
			<th width="68" align="center">类型</th>
			<th width="77" align="center">最后修改时间</th>		
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
	<td><?php echo $teacher['name']?></td>
	
	<td><?php echo $teacher['title']?></td>
	<td><?php echo $typelist[$teacher['typeid']]['name']?></td>
	<td><?php if($teacher['addtime']): ?><?php echo date('Y-m-d H:i:s', $teacher['addtime'])?><?php endif; ?></td>
	<td><a href="javascript:edit('<?php echo $teacher['id']?>', '<?php echo safe_replace($teacher['title'])?>');void(0);"><?php echo L('edit')?></a>
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