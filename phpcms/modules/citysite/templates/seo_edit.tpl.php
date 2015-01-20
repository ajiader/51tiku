<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = '';
include $this->admin_tpl('header', 'admin');
?>


<div class="common-form pad-lr-10">


<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
<tr><td colspan="2">
<div class="explain-col"> &nbsp;&nbsp;&nbsp;&nbsp;地方站标签：{$cityname}城市名称,{$catname}栏目名称 </div>
</td></tr>


      
      <tr>
        <th><label for="select">类别：</label></th>
        <td>
          <select name="info[typeid]" id="typeid">
          <option value="">请选择</option>
          <?php foreach($type_list as $v): ?>
           <option <?php if($v['typeid'] == $r['typeid']): ?>selected="selected" <?php endif; ?> value="<?=$v['typeid']?>"><?=$v['name']?></option>
		  <?php endforeach; ?>
        </select></td>
      </tr>
      <tr>
        <th>SEO名称：</th>
        <td><input name="info[name]" type="text" id="name" value="<?=$r['name']?>" size="57" /></td>
      </tr>
      <tr>
      <th>标题：</th><td><input name="info[title]" type="text" id="title" value="<?=$r['title']?>" size="57" /></td></tr>
      
      <tr>
      <th>关键词：</th><td id="addcat"><textarea name="info[keywords]" id="keywords" style="width:350px;height:100px;"><?=$r['keywords']?>
      </textarea></td></tr>
      
      <tr><th>描述：</th>
      <td>
    <textarea name="info[description]" id="description" style="width:350px;height:100px;"><?=$r['description']?>
    </textarea>
      </td></tr>
      
</table>
<div class="btn" style="padding-left:200px;">
<input type="submit" name="dosubmit" id="dosubmit" value=" 提 交 " class="button"/>
</div>
</form>
<!--table_form_off-->
</div>

<script type="text/javascript">


$(document).ready(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform"});

	
	
	$('#typeid').formValidator({onshow:"请选择入口类型!",onfocus:"请选择入口类型!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请选择入口类型!"});
	$('#name').formValidator({onshow:"请输入seo名称!",onfocus:"请输入seo名称!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入seo名称!"});
	$('#title').formValidator({onshow:"请输入标题!",onfocus:"请输入标题!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入标题!"});




});



</script>
</body>
</html>
