{template "content","header_blog"}


<!--start wraper-->
<div id="wraper" class="cbody clearfix">

	<div id="position"><strong>热门标签：</strong> <font color="red">{$tag}</font> 总共有 {$total} 条记录</div>
	<!--start list-->
	<div id="list">    
    

         
         {loop $datas $r}
         {php $keywords =  explode(" ",$r['keywords']);}
         {php $catid = $r['catid'];}   
    	 <div class="posted">
        	<h5 class="titleh5"><span>{date("Y年m月d日",$r['inputtime'])}</span><a href="{$r[url]}" target="_blank"{title_style($r[style])}>{$r[title]}</a></h5>         
            <h6 class="titleh6">分类:<a href="{$CATEGORYS[$catid][url]}">{$CATEGORYS[$catid][catname]}</a> 发布者:{$r['username']} Tags:{loop $keywords $keyword}
            <a href="{APP_PATH}goshouye.php?m=content&c=tag&catid=13&tag={$keyword}" style="color:#ED0909;">{$keyword}</a>{/loop}  </h6>              
            <div class="postedCon">
            	<p>{$r[description]}<a href="{$r[url]}" style="color:#ED0909;}">[详细]</a></p>
			</div>
        </div>
        {/loop}
         <div id="pages" class="text-c">{$pages}</div>
          
    </div><!--end header-->
    
    {template "content","slibar"}
    
</div><!--end wraper-->

{template "content","footer_blog"}


</body>
</html>
