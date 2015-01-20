<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<div id="closeParentTime" style="display:none"></div>
<SCRIPT LANGUAGE="JavaScript">

$(document).ready(function(){
	setInterval(closeParent,5000);
});
function closeParent() {
	if($('#closeParentTime').html() == '') {
		window.top.$(".left_menu").addClass("left_menu_on");
		window.top.$("#openClose").addClass("close");
		window.top.$("html").addClass("on");
		$('#closeParentTime').html('1');
		window.top.$("#openClose").data('clicknum',1);
	}
}
//-->
</SCRIPT>
<div class="pad-10">
<div class="explain-col">
1、现在seo优化工具只做了文章模型的，马上会更新其他模型 <br>2、此插件在后台发文章时候会自动检测是否有重复标题。</div>
<div class="bk10"></div>

<form name="myform" id="myform" action="" method="post" >
<div class="btn"><label for="check_box"><?php echo L('selected_all');?>/<?php echo L('cancel');?></label>
	
		<input type="button" class="button" value="<?php echo L('delete');?>" onclick="myform.action='?m=content&c=content&a=rmsame&dosubmit=1';return confirm_delete()"/>
		
		<?php echo runhook('admin_content_init')?>
	</div>
<div class="table-list">
    <table width="100%">
        <thead>
            <tr>
			 <th width="16"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>

            <th width="40">ID</th>
			<th><?php echo L('title');?></th>
            <th width="40"><?php echo L('hits');?></th>
            <th width="70">栏目id</th>
            <th width="118"><?php echo L('updatetime');?></th>
			<th width="72"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
<tbody>
    <?php
	if(is_array($datas)) {
		$sitelist = getcache('sitelist','commons');
		$release_siteurl = $sitelist[$category['siteid']]['url'];
		$path_len = -strlen(WEB_PATH);
		$release_siteurl = substr($release_siteurl,0,$path_len);
	
		
		foreach ($datas as $r) {
			
	?>
        <tr>
		<td align="center"><input class="inputcheckbox " name="ids[]" value="<?php echo $r['id'];?>" type="checkbox"></td>
		<td align='center' ><?php echo $r['id'];?></td>
		<td>
		<?php
	
			echo '<a href="javascript:;" onclick=\'window.open("?m=content&c=content&a=public_preview&steps='.$steps.'&catid='.$r['catid'].'&id='.$r['id'].'","manage")\'>';
		?><span<?php echo title_style($r['style'])?>><?php echo $r['title'];?></span></a> <?php if($r['thumb']!='') {echo '<img src="'.IMG_PATH.'icon/small_img.gif" title="'.L('thumb').'">'; } if($r['posids']) {echo '<img src="'.IMG_PATH.'icon/small_elite.gif" title="'.L('elite').'">';} if($r['islink']) {echo ' <img src="'.IMG_PATH.'icon/link.png" title="'.L('islink_url').'">';}?></td>
		<td align='center' title="<?php echo L('today_hits');?>：<?php echo $hits_r['dayviews'];?>&#10;<?php echo L('yestoday_hits');?>：<?php echo $hits_r['yestodayviews'];?>&#10;<?php echo L('week_hits');?>：<?php echo $hits_r['weekviews'];?>&#10;<?php echo L('month_hits');?>：<?php echo $hits_r['monthviews'];?>"><?php echo $hits_r['views'];?></td>
		<td align='center'>
		<?php
			echo $this->categorys[$r['catid']]['catname'];		
		?></td>
		<td align='center'><?php echo format::date($r['updatetime'],1);?></td>
		<td align='center'><a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $r['catid'];?>&id=<?php echo $r['id']?>','')"><?php echo L('edit');?></a> | <a href="javascript:view_comment('<?php echo id_encode('content_'.$catid,$r['id'],$this->siteid);?>','<?php echo safe_replace($r['title']);?>')"><?php echo L('comment');?></a></td>
	</tr>
     <?php }
	}
	?>
</tbody>
     </table>
    <div class="btn"><label for="check_box"><?php echo L('selected_all');?>/<?php echo L('cancel');?></label>
	
		<input type="button" class="button" value="<?php echo L('delete');?>" onclick="myform.action='?m=content&c=content&a=rmsame&dosubmit=1';return confirm_delete()"/>
		
		<?php echo runhook('admin_content_init')?>
	</div>
    <div id="pages"></div>
</div>
</form>
</div>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>cookie.js"></script>
<script type="text/javascript"> 
<!--
function push() {
	var str = 0;
	var id = tag = '';
	$("input[name='ids[]']").each(function() {
		if($(this).attr('checked')==true) {
			str = 1;
			id += tag+$(this).val();
			tag = '|';
		}
	});
	if(str==0) {
		alert('<?php echo L('you_do_not_check');?>');
		return false;
	}
	window.top.art.dialog({id:'push'}).close();
	window.top.art.dialog({title:'<?php echo L('push');?>：',id:'push',iframe:'?m=content&c=push&action=position_list&catid=<?php echo $catid?>&modelid=<?php echo $modelid?>&id='+id,width:'800',height:'500'}, function(){var d = window.top.art.dialog({id:'push'}).data.iframe;// 使用内置接口获取iframe对象
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'push'}).close()});
}
function confirm_delete(){
	if(confirm('<?php echo L('confirm_delete', array('message' => L('selected')));?>')) $('#myform').submit();
}
function view_comment(id, name) {
	window.top.art.dialog({id:'view_comment'}).close();
	window.top.art.dialog({yesText:'<?php echo L('dialog_close');?>',title:'<?php echo L('view_comment');?>：'+name,id:'view_comment',iframe:'goshouye.php?m=comment&c=comment_admin&a=lists&show_center_id=1&commentid='+id,width:'800',height:'500'}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function reject_check(type) {
	if(type==1) {
		var str = 0;
		$("input[name='ids[]']").each(function() {
			if($(this).attr('checked')==true) {
				str = 1;
			}
		});
		if(str==0) {
			alert('<?php echo L('you_do_not_check');?>');
			return false;
		}
		document.getElementById('myform').action='?m=content&c=content&a=pass&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>&reject=1';
		document.getElementById('myform').submit();
	} else {
		$('#reject_content').css('display','');
		return false;
	}	
}
setcookie('refersh_time', 0);
function refersh_window() {
	var refersh_time = getcookie('refersh_time');
	if(refersh_time==1) {
		window.location.reload();
	}
}
setInterval("refersh_window()", 3000);
//-->
</script>
</body>
</html>