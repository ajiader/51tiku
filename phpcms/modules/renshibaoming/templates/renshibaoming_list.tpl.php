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
		    <option value="<?=$v['typeid']?>" <?php if($typeid == $v['typeid']): ?> selected="selected"<?php endif;?>>
		      <?=$v['name']?>
		      </option>
		    <?php
        endforeach;
		?>
</select>
<input name="search" type="submit" value="查询" class="button">
<input type="hidden" name="m" value="renshibaoming"><input type="hidden" name="c" value="renshibaoming"><input type="hidden" name="a" value="init">
<input name="pc_hash" type="hidden" value="n5Eraa"></form>

</div>
<div class="pad-lr-10">
<form name="myform" action="?m=renshibaoming&c=admin_renshibaoming&a=listorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
			<th width="67" align="center">ID</th>
			<th width="68" align="center"><?php echo L('title')?></th>
			<th width="92" align="center"><?php echo L('city')?></th>
<th width="88" align="center">类型</th>
			<th width="88" align="center"><?php echo L('inputtime')?></th>		
			<th width="117" align="center"><?php echo L('operations_manage')?></th>
            </tr>
        </thead>
    <tbody>
 <?php 
if(is_array($list)){
	foreach($list as $renshibaoming){
?>   
	<tr>
	<td align="center">
	<input type="checkbox" name="id[]" value="<?php echo $renshibaoming['id']?>">
	</td>
	<td><?php echo $renshibaoming['id']?></td>
	<td><?php echo $renshibaoming['title']?></td>
	<td align="center"><?php echo $linkagelist[$renshibaoming['cityid']]['name']?></td>
	<td align="center"><?php echo $typelist[$renshibaoming['typeid']]['name']?></td>
	<td align="center"><?php echo date('Y-m-d H:i:s', $renshibaoming['addtime'])?></td>
	<td align="center">
    <?php
    $filename = $renshibaoming['filename']?$renshibaoming['filename']:$renshibaoming['id'];
	?>
	<a target="_blank" href="<?php echo siteurl($this->siteid).'/'.$typelist[$renshibaoming['typeid']]['typedir'].'/'.$filename.'.html';?>"><?php echo L('index')?></a>| 
	<a href="javascript:edit('<?php echo $renshibaoming['id']?>', '<?php echo safe_replace($renshibaoming['title'])?>');void(0);"><?php echo L('edit')?></a>
	</td>
	</tr>
<?php 
	}
}
?>
</tbody>
    </table>
  
    <div class="btn"><label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>

		<input name="submit" type="submit" class="button" value="<?php echo L('remove_all_selected')?>" onClick="document.myform.action='?m=renshibaoming&c=renshibaoming&a=delete';return confirm('<?php echo L('affirm_delete')?>')">&nbsp;&nbsp;</div>  </div>
 <div id="pages"><?php echo $this->db->pages;?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_renshibaoming')?>--'+title, id:'edit', iframe:'?m=renshibaoming&c=renshibaoming&a=edit&id='+id ,width:'700px',height:'400px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>