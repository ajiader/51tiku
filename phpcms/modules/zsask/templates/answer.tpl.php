<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = '';
include $this->admin_tpl('header', 'admin');
?>
<script type="text/javascript">
var charset = '<?php echo CHARSET ?>';
var uploadurl = '<?php echo pc_base::load_config("system","upload_url")?>';
</script>
<div class="common-form">

<form name="myform" id="myform" action="?m=<?php echo ROUTE_M ?>&c=<?php echo ROUTE_C ?>&a=<?php echo ROUTE_A ?>" method="post">
<table width="100%" class="table_form contentWrap">
      <tr><th>用户提问：</th>
      <td>&nbsp;<?=$info['question'];?>
	 </td></tr>
	 <tr><th>问题分类：</th><td>
      &nbsp;<?=$ZSASKCATE[$info['catid']]['catname']?>
      </td></tr>
      <?php if($info['content']):?>
      <tr><th>问题补充：</th><td>
      <?=$info['content']?>
      </td></tr>
      <?php endif; ?>
	   <tr><th>提问时间：</th><td>
      <?php echo format_date($info['updatetime'])?>
      </td></tr>
      <tr><th>专家回答：</th>
      <td>
    <textarea name="answer" id="answer" style="width:700px;height:100px;"><?php echo ($ts_answer[0]['content'])?></textarea>
    
<?php echo form::editor('answer','desc','','','',1,1,'',200,0,10,1)?>
	<input type="hidden" name="qid" id="qid_id" value="<?=$qid?>">
      </td></tr>
      
      
      
</table>
<div class="btn" style="padding-left:200px;">
<input type="hidden" name="pid" value="<?php echo $info['parentid'];?>" />
<input type="submit" name="dosubmit" id="dosubmit" value=" 确定 " class="dialog">
<input type="reset" class="dialog" value=" 清除 ">
</div>
</form>
<!--table_form_off-->
</div>

</body>
</html>