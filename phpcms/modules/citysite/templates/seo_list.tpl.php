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
            <th width="34" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="20" align="center">ID</th>
			<th width="110" align="center">SEO名称</th>
			<th width="87" align="center">标题</th>
			<th width="83" align="center">类型</th>
			<th width="113" align="center"><?php echo L('operations_manage')?></th>
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
	<td align="center"><?php echo $v['id']?></td>
    	<td align="center"><?php echo $v['name']?></td>
	
	<td  align="center"><?php echo $v['title']?></td>

	<td align="center"><?=$typelist[$v['typeid']]['name']?></td>
	<td align="center">
	<a href="?m=citysite&c=seo&a=edit&id=<?=$v['id']?>"><?php echo L('edit')?></a>	
	</td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

    <input name="submit" type="submit" class="button" value="删除选中" onClick="document.myform.action='?m=citysite&c=seo&a=delete';return confirm('删除选中')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
