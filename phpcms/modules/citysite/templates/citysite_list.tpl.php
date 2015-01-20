<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=citysite&c=admin_citysite&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="67" align="center">ID</th>
			<th width="68" align="center">地区</th>
			<th width="92" align="center">栏目</th>
			<th width="92" align="center">报名时间</th>
			<th width="88" align="center">考试时间</th>
			<th width="117" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	foreach($list as $v){
?>   
	<tr>
	<td align="center">
	<input type="checkbox" name="id[]" value="<?php echo $v['id']?>">
	</td>
	<td><?php echo $v['id']?></td>
    	<td align="center"><?php echo $linkagelist[$v['cityid']]['name']?></td>
    	<td  align="center"><?php echo $CATEGORYS[$v['catid']]['catname']?></td>
	
	<td  align="center"><?php echo $v['bmtime']?></td>

	<td align="center"><?php echo $v['kstime']?></td>
	<td align="center">
	<a href="javascript:edit('<?php echo $v['id']?>', '<?php echo safe_replace( $linkagelist[$v['cityid']]['name'])?>');void(0);"><?php echo L('edit')?></a>	
	</td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

		<input name="submit" type="submit" class="button" value="删除选中" onClick="document.myform.action='?m=citysite&c=citysite&a=delete';return confirm('删除选中')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'编辑考试提醒--'+title, id:'edit', iframe:'?m=citysite&c=citysite&a=kaoshitx_edit&id='+id ,width:'500px',height:'400px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>