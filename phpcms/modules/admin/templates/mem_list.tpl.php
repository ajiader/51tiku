<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<div class="table-list">
<form action="?m=admin&c=memcache_all&a=init" method="get">

  <table width="100%" cellspacing="0">
  <tr>
    <td>&nbsp;输入查询内容：<input name="searchkey" type="text" value="<?=$_GET['searchkey']?>" size="50" /><input name="" type="submit"  value="查询" class="button"/>
    <input type="hidden" name="m" value="admin">
    <input type="hidden" name="c" value="memcache_all">
    <input type="hidden" name="a" value="init">
    </td>

  </tr>
</table>

</form>
</div>
<form name="myform" action="?m=admin&c=memcache_all&a=init" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th align="center">Mem缓存名称</th>
			<th align="center">生成时间</th>
			<th width="117" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list['data'])){
	foreach($list['data'] as $k => $v){
?>   
	<tr>
	<td align="center">
	<input type="checkbox" name="id[]" value="<?php echo $k?>">
	</td>
	<td><?php echo $k ?></td>
    	<td  align="center"><?php echo date('Y-m-d H:i:s',$v-(5*3600)) ?></td>

	<td align="center">
	  <a href="?m=admin&c=memcache_all&a=deleteone&id=<?php echo $k ?>">删除</a>	
	  </td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

		<input name="submit" type="submit" class="button" value="删除选中" onClick="document.myform.action='?m=admin&c=memcache_all&a=delete';return confirm('删除选中')">&nbsp;&nbsp;<input name="submit" type="submit" class="button" style="display:none" value="清除所有Memcache" onClick="document.myform.action='?m=admin&c=memcache_all&a=del_mem';return confirm('清除所有Memcache！谨慎使用该功能！')"></div>  </div>
 <div id="pages"><?php echo pages($list['pager'],$page,$pagesize);?></div>
</form>
</div>
</body>
</html>
