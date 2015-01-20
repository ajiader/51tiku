<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>

<div class="pad-lr-10">
<form name="myform" action="?m=zrmokuai&c=admin_zrmokuai&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="67" align="center">ID</th>
			<th width="68" align="center">关键词</th>
			
            <th width="88" align="center">用户IP</th>
<th width="88" align="center">查询时间</th>

            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	foreach($list as $zrmokuai){
?>   
	<tr>
	<td align="center">
	<input type="checkbox" name="id[]" value="<?php echo $zrmokuai['id']?>">
	</td>
	<td><?php echo $zrmokuai['id']?></td>
	<td><?php echo $zrmokuai['title']?></td>
	<td align="center"><?php echo $zrmokuai['ip']?></td>
	<td align="center"><?php echo date('Y-m-d',$zrmokuai['addtime'])?></td>
    	
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

		<input name="submit" type="submit" class="button" value="删除选中项" onClick="document.myform.action='?m=zrmokuai&c=zr_admin&a=searchdelete';return confirm('删除不能恢复，是否继续？')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
