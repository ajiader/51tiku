jQuery(function(){
   openOrCloseAd();
});

//打开顶部
function openOrCloseAd()
{
	$("#changeCity").click(function(a){$("#allCity").slideDown(300);a.stopPropagation();$(this).blur()});$("#allCity").click(function(a){a.stopPropagation()});$(document).click(function(a){a.button!=2&&$("#allCity").slideUp(300)});$("#foldin").click(function(){$("#allCity").slideUp(300)});
}