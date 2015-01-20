<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = '';
include $this->admin_tpl('header', 'admin');
?>

<script type="text/javascript">
var charset = '<?php echo CHARSET ?>';
var uploadurl = '<?php echo pc_base::load_config("system","upload_url")?>';
</script>
<div class="common-form pad-lr-10">
<style>
.contentWrap tr {height:40px;}
</style>

<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
<tr><td colspan="2">
<div class="explain-col">设置问答管理</div>
</td></tr>

      <tr><th width="200">审核：</th><td>
	      <input type="checkbox" name="set[check_question]" value="1" <?php if ($settings['check_question']) echo 'checked';?> /> 提问 
	      <input type="checkbox" name="set[check_answer]" value="1" <?php if ($settings['check_answer']) echo 'checked';?> /> 回答 
	      <input type="checkbox" name="set[check_comment]" value="1" <?php if ($settings['check_comment']) echo 'checked';?> /> 评论 
      </td></tr>
      
      <tr><th>允许游客：</th><td>
	      <input type="checkbox" name="set[agree_question]" value="1" <?php if ($settings['agree_question']) echo 'checked';?> /> 提问 
	      <input type="checkbox" name="set[agree_answer]" value="1" <?php if ($settings['agree_answer']) echo 'checked';?> /> 回答 
	      <input type="checkbox" name="set[agree_comment]" value="1" <?php if ($settings['agree_comment']) echo 'checked';?> /> 评论 
      </td></tr>
            <tr>
              <th>内容页伪静态设置：</th>
              <td>
                <select name="set[urlrule_question]" id="urlrule_question">
                <option value="">请选择</option>
               <?php
               if(is_array($urlrulelist)):
			       foreach($urlrulelist as $val):
			   ?>
               <option value="<?=$val['urlruleid']?>" <?php if($settings['urlrule_question'] ==$val['urlruleid']  ): ?> selected="selected"<?php endif; ?>><?=$val['example']?></option>
               <?php
               endforeach;
			   endif;
			   ?>
              </select></td></tr>
      
</table>
<div class="btn" style="padding-left:200px;">
<input type="submit" name="dosubmit" id="dosubmit" value=" 提 交 " class="button"/>
</div>
</form>
<!--table_form_off-->
</div>

<script type="text/javascript">

</script>
</body>
</html>
