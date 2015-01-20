<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_validator = '';
include $this->admin_tpl('header', 'admin');
?>


<div class="common-form pad-lr-10">


<form name="myform" id="myform" action="" method="post">
<table width="100%" class="table_form contentWrap">
<tr><td colspan="2">

</td></tr>


      
       <tr>
        <th><label for="select">类别：</label></th>
        <td>
            <?php echo menu_linkage(3362,'typeid') ?></td>
      </tr>
      <tr>
        <th>模块名称：</th>
        <td><input name="info[name]" type="text" id="name" value="<?=$vo['name']?>" size="57" /></td>
      </tr>
      <tr>
        <th>模块拼音：</th>
        <td><input name="info[pinyin]" type="text" id="pinyin" value="<?=$vo['pinyin']?>" size="57" /></td>
      </tr>
      <tr>
        <th>模块价格：</th>
        <td><input name="info[price]" type="text" id="price" size="57" value="<?=$vo['price']?>" /></td>
      </tr>
      <tr>
      <th>模块总题量：</th><td><input name="info[name_num]" type="text" id="name_num" value="<?=$vo['name_num']?>" size="57" /></td></tr>
      
      <tr>
      <th>题量范围：</th><td><input name="info[tlfw]" type="text" id="tlfw" value="<?=$vo['tlfw']?>" size="57" /></td></tr>

      <tr>
      <th>报名时间：</th><td><input name="info[bmtime]" type="text" id="bmtime" value="<?=$vo['bmtime']?>" size="57" /></td></tr>

      <tr>
      <th>考试时间：</th><td><input name="info[kstime]" type="text" id="kstime" value="<?=$vo['kstime']?>" size="57" /></td></tr>

      <tr>
      <th>定期模考：</th><td><input name="info[dqmk]" type="text" id="dqmk" value="<?=$vo['dqmk']?>" size="57" /></td></tr>

      <tr>
        <th>题型及题量：</th>
        <td id="addcat2"><textarea name="info[type_num]" id="type_num" style="width:350px;height:50px;"><?=$vo['type_num']?>
        </textarea></td>
      </tr>
<!--       <tr>
        <th>&quot;我要练习&quot;中包含科目名称及题量</th>
        <td id="addcat3"><textarea name="info[content_1]" id="content_1" style="width:350px;height:140px;"><?=$vo['content_1']?>
        </textarea></td>
      </tr> -->
      <!-- <tr>
      <th>模拟试卷套数及题量：</th><td id="addcat"><input name="info[content_2]" type="text" id="content_2"  value="<?=$vo['content_2']?>" size="57" /></td></tr> -->
      
      <tr>
        <th>历年真题时间:</th>
        <td id="addcat4"><input name="info[lnzttime]" type="text" id="lnzttime" value="<?=$vo['lnzttime']?>" size="57" /></td>
      </tr>

      <tr><th>历年真题套数及题量：</th>
      <td>
    <textarea name="info[lnzt]" id="lnzt" style="width:350px;height:50px;"><?=$vo['lnzt']?>
    </textarea>
      </td></tr>

      <tr><th>题库介绍: </th>
      <td>
    <textarea name="info[content]" id="content" style="width:550px;height:200px;"><?=$vo['content']?>
    </textarea>
      </td></tr>
      <!-- <tr>
        <th>冲刺试卷套数及数量：</th>
        <td><input name="info[content_4]" type="text" id="content_4"  value="<?=$vo['content_4']?>" size="57" /></td>
      </tr> -->
      <tr>
        <th>是否上市：</th>
        <td>
        <select name="info[status]" id="status">
        <option value=""> 请选择 </option>
        <option value="1" <?php if($vo['status'] == 1): ?> selected="selected" <?php endif; ?>> 是 </option>
         <option value="0" <?php if($vo['status'] == 0): ?> selected="selected" <?php endif; ?>> 否 </option>
        </select></td>
      </tr>
   
    <tr>
        <th>下次升级时间：</th>
        <td>
        <link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/jscal2.css">
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/border-radius.css">
<link rel="stylesheet" type="text/css" href="<?php echo JS_PATH?>calendar/win2k.css">
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH?>calendar/lang/en.js"></script>
        <input type="text" name="info[nextsj_time]" id="nextsj_time" value="<?=$vo['nextsj_time']?>" size="21" class="date input-text" readonly=""><script type="text/javascript">
      Calendar.setup({
      weekNumbers: true,
        inputField : "nextsj_time",
        trigger    : "nextsj_time",
        dateFormat: "%Y-%m-%d %H:%M:%S",
        showTime: true,
        minuteStep: 1,
        onSelect   : function() {this.hide();}
      });
        </script>&nbsp;</td>
      </tr>

      <tr>
        <th>模块更新时间：</th>
        <td>
        <input type="text" name="info[updatetime]" id="updatetime" value="<?=$vo['updatetime']?>" size="21" class="date input-text" readonly=""><script type="text/javascript">
      Calendar.setup({
      weekNumbers: true,
        inputField : "updatetime",
        trigger    : "updatetime",
        dateFormat: "%Y-%m-%d %H:%M:%S",
        showTime: true,
        minuteStep: 1,
        onSelect   : function() {this.hide();}
      });
        </script>&nbsp;</td>
      </tr>
      
       <tr>
        <th>下载链接：</th>
        <td id="addcat6"><input name="info[down_url]" type="text" id="down_url" value="<?=$vo['down_url']?>" size="57" /></td>
      </tr>
      <tr>
        <th>购买链接：</th>
        <td id="addcat5"><input name="info[buy_url]" type="text" id="buy_url" value="<?=$vo['buy_url']?>" size="57" /></td>
      </tr>
      <tr>
        <th>更多链接：</th>
        <td id="addcat4"><input name="info[more_url]" type="text" id="more_url" value="<?=$vo['more_url']?>" size="57" /></td>
      </tr>
      
      
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

	$('#typeid').formValidator({onshow:"请选择类型!",onfocus:"请选择类型!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请选择类型!"});
	$('#name').formValidator({onshow:"请输入模块名称!",onfocus:"请输入模块名称!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入模块名称!"}).ajaxValidator({type:"get",url:"",data:"m=zrmokuai&c=zr_admin&a=public_check_title",datatype:"html",cached:false,async:'true',success : function(data) {
        if( data == "1" )
		{
            return true;
		}
        else
		{
            return false;
		}
	},
	error: function(){alert("<?php echo L('server_no_data')?>");},
	onerror : "该模块已经存在",
	onwait : "数据验证中..."
	});
//	$('#name_num').formValidator({onshow:"请输入模块总题量!",onfocus:"请输入模块总题量!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入模块总题量!"});
	$('#type_num').formValidator({onshow:"请输入题型及题量!",onfocus:"请输入题型及题量!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请输入题型及题量!"});

$('#status').formValidator({onshow:"请选择是否上市!",onfocus:"请选择是否上市!",oncorrect:"输入正确!"}).inputValidator({min:1,onerror:"请选择是否上市!"});

});



</script>
</body>
</html>
