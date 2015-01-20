<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">

<form action="" method="get">

<label>&nbsp;&nbsp;姓名：</label>
<input name="name" type="text" value="<?=$name?>" class="input-text">

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
<input type="hidden" name="m" value="<?=ROUTE_M?>"><input type="hidden" name="c" value="<?=ROUTE_M?>"><input type="hidden" name="a" value="init">
<input name="pc_hash" type="hidden" value="n5Eraa"></form>

</div>
<div class="pad-lr-10">
<form name="myform" action="?m=<?=ROUTE_M?>&c=admin_teacher&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="20" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="20" align="center">ID</th>
			<th width="89" align="center">姓名</th>
		
			<th width="68" align="center">电话</th>
			<th width="68" align="center">用户编号</th>
			<th width="68" align="center">验证码</th>
			<th width="68" align="center">IP</th>
			<th width="77" align="center">提交时间</th>		
			
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	foreach($list as $v){
?>   
	<tr align="center">
	<td>
	<input type="checkbox" name="id[]" value="<?php echo $v['id']?>">
	</td>
	<td><?php echo $v['id']?></td>
	<td><?php echo $v['name']?></td>
	
	<td><?php echo $v['phone']?></td>
	<td><?php echo $v['kfbh']?></td>
	<td><?php echo $v['yzm']?></td>
	<td><?php echo $v['ip']?></td>
	<td><?php if($v['addtime']): ?><?php echo date('Y-m-d H:i:s', $v['addtime'])?><?php endif; ?></td>

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
