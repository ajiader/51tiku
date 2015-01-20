<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">

<form action="" method="get">

<label>&nbsp;&nbsp; 标题：</label>
<input name="name" type="text" value="<?=$title?>" class="input-text">

<label for="select">类型：</label>
<select name="typeid" id="select" >
<option value="">所有</option>
 <?php
        foreach($type_list  as $v):
		?>
		    <option value="<?=$v['linkageid']?>" <?php if($typeid == $v['linkageid']): ?> selected="selected"<?php endif;?>>
		      <?=$v['name']?>
		      </option>
		    <?php
        endforeach;
		?>
</select>
<label for="select">是否上市：</label>
<select name="status" id="select" >
<option value="1"  <?php if($status == 1): ?> selected="selected"<?php endif;?>>是</option>
 <option value="0" <?php if($status == 0): ?> selected="selected"<?php endif;?>>否</option>
		    
</select>
<input name="search" type="submit" value="查询" class="button">
<input type="hidden" name="m" value="zrmokuai"><input type="hidden" name="c" value="zr_admin"><input type="hidden" name="a" value="init">
<input name="pc_hash" type="hidden" value="n5Eraa"></form>

</div>
<div class="pad-lr-10">
<form name="myform" action="?m=zrmokuai&c=admin_zrmokuai&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="67" align="center">ID</th>
			<th width="68" align="center">模块名称</th>
			
            <th width="88" align="center">模块售价（元/个）</th>
<th width="88" align="center">类型</th>
<th width="92" align="center">是否上市</th>
<th width="92" align="center">更新时间</th>
			<th width="88" align="center"><?php echo L('inputtime')?></th>		
			<th width="117" align="center"><?php echo L('operations_manage')?></th>
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
	<td><?php echo $zrmokuai['name']?></td>
	<td align="center"><?php echo $zrmokuai['price']?></td>
	<td align="center"><?php print_r( get_linkage($zrmokuai['typeid'],'3362'))?></td>
    <td align="center"><?php echo $statusarr[$zrmokuai['status']]['name']?></td>
    <td align="center"><?php echo $zrmokuai['updatetime']?></td>
	<td align="center"><?php echo $zrmokuai['addtime']?></td>
	<td align="center"><a href="?m=zrmokuai&c=zr_admin&a=editmokuai&id=<?=$zrmokuai['id']?>"><?php echo L('edit')?></a>
	</td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

		<input name="submit" type="submit" class="button" value="删除选中项" onClick="document.myform.action='?m=zrmokuai&c=zr_admin&a=delete';return confirm('删除不能恢复，是否继续？')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
