<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=citysite&c=citysite&a=kaoshitx_edit&id=<?php echo $_GET['id']?>" name="myform" id="myform">
<table class="table_form" width="100%" cellspacing="0">
<tbody>
	<tr>
		<th width="123"><strong>地区</strong></th>
		<td width="350"><select name="citysite[cityid]" id="cityid">
        <option value="">请选择</option>
        <?php
        foreach($linkage_list  as $v):
		?>
        <option value="<?=$v['linkageid']?>" <?php if($info['cityid'] == $v['linkageid']): ?> selected="selected"<?php endif;?>><?=$v['name']?></option>
        <?php
        endforeach;
		?>
        </select></td>
	</tr>
	
	<?php if($this->siteid==1) :?>
	<tr>
	  <th>考试类型：</th>
	  <td><select name="citysite[catid]">
      <option value="">请选择</option>
      <option value="9" <?php if($info['catid']==9){echo 'selected="selected"';}?> >职称英语</option>
      <option value="10"  <?php if($info['catid']==10){echo 'selected="selected"';}?>>职称计算机</option>
      <option value="11"  <?php if($info['catid']==11){echo 'selected="selected"';}?>>职业资格</option>
      </select></td>
	  </tr>
    <?php endif;?>
	<tr>
		<th><strong>报名入口</strong></th>
		<td><input name="citysite[bmrk_url]" id="bmrk_url" class="input-text" type="text" size="50" value="<?=$info['bmrk_url']?>" /></td>
	</tr>
	
		<tr>
		<th><strong>准考证</strong></th>
		<td><input name="citysite[zkz_url]" id="zkz_url" class="input-text" type="text" size="50" value="<?=$info['zkz_url']?>" /></td>
	</tr>
	<tr>
		<th><strong>成绩查询</strong></th>
		<td><input name="citysite[cjcx_url]" id="cjcx_url" class="input-text" type="text" size="50" value="<?=$info['cjcx_url']?>" /></td>
	</tr>
    <tr>
		<th><strong>职位表</strong></th>
		<td><input name="citysite[zwb_url]" id="zwb_url" class="input-text" type="text" size="50" value="<?=$info['zwb_url']?>"  /></td>
	</tr>
    <tr>
		<th><strong>报名时间</strong></th>
		<td><input name="citysite[bmtime]" id="bmtime" class="input-text" type="text" size="50"  value="<?=$info['bmtime']?>"  /></td>
	</tr>
    <tr>
		<th><strong>考试时间</strong></th>
		<td><input name="citysite[kstime]" id="kstime" class="input-text" type="text" size="50"  value="<?=$info['kstime']?>" /></td>
	</tr>
      <tr>
		<th><strong>领准考证时间</strong></th>
		<td><input name="citysite[lztime]" id="lztime" class="input-text" type="text" size="50" value="<?=$info['lztime']?>"  /></td>
	</tr>
      <tr>
		<th><strong>成绩查询时间</strong></th>
		<td><input name="citysite[cjcxtime]" id="cjcxtime" class="input-text" type="text" size="50"  value="<?=$info['cjcxtime']?>" /></td>
	</tr>
          <tr>
		<th><strong>面试时间</strong></th>
		<td><input name="citysite[mstime]" id="mstime" class="input-text" type="text" size="50" value="<?=$info['mstime']?>"  /></td>
	</tr>
          <tr>
		<th><strong>报名专题入口</strong></th>
		<td><input name="citysite[bmzt_url]" id="bmzt_url" class="input-text" type="text" size="50" value="<?=$info['bmzt_url']?>"  /></td>
	</tr>
	</tbody>
</table>
<input type="submit" name="dosubmit" id="dosubmit" value=" <?php echo L('ok')?> " class="dialog">&nbsp;<input type="reset" class="dialog" value=" <?php echo L('clear')?> ">
</form>
</div>
</body>
</html>
<script type="text/javascript">
function load_file_list(id) {


$(document).ready(function(){
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'220',height:'70'}, function(){this.close();$(obj).focus();})}});
	$('#cityid').formValidator({onshow:"选择地区",onfocus:"选择地区",oncorrect:"输入正确"}).inputValidator({min:1,onerror:"选择地区"});;
});
</script>