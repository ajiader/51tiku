/*
 *	sGallery 1.2 - simple gallery with jQuery
 *	made by bujichong 2009-11-27
 *	作者：不羁虫  2009-11-27
 * http://hi.baidu.com/bujichong/
 *	欢迎交流转载，但请尊重作者劳动成果，标明插件来源及作者
 */

(function ($) {
$.fn.sGallery = function (o) {
    return  new $sG(this, o);
			//alert('do');
    };

	var settings = {
		thumbObj:null,//导航对象
		botLast:null,//按钮上一个
		botNext:null,//按钮下一个
		thumbNowClass:'now',//导航对象当前的class,默认为now
		slideTime:1000,//平滑过渡时间，默认为1000ms
		autoChange:true,//是否自动切换，默认为true
		clickFalse:true,//导航对象如果有链接，点击是否链接无效，即是否返回return false，默认是return false链接无效
		overStop:true,//鼠标经过切换对象时，切换对象是否停止切换，并于鼠标离开后重启自动切换，前提是已开启自动切换
		changeTime:5000,//自动切换时间
		delayTime:300//鼠标经过时对象切换迟滞时间，推荐值为300ms
	};

 $.sGalleryLong = function(e, o) {
    this.options = $.extend({}, settings, o || {});
	var _self = $(e);
	var set = this.options;
	var thumb;
	var size = _self.size();
	var nowIndex = 0; //定义全局指针
	var index;//定义全局指针
	var startRun;//预定义自动运行参数
	var delayRun;//预定义延迟运行参数

//初始化
	_self.hide();
	_self.eq(0).show();

//主切换函数
function fadeAB () {
	if (nowIndex != index) {
		if (set.thumbObj!=null) {
		$(set.thumbObj).removeClass(set.thumbNowClass).eq(index).addClass(set.thumbNowClass);}
		if (set.slideTime <= 0) {
			_self.eq(nowIndex).hide();
			_self.eq(index).show();	
		}else{
			_self.eq(nowIndex).fadeOut(set.slideTime);
			_self.eq(index).fadeIn(set.slideTime);
		}
		nowIndex = index;
		if (set.autoChange==true) {
		clearInterval(startRun);//重置自动切换函数
		startRun = setInterval(runNext,set.changeTime);}
		}
}




//切换到下一个
function runNext() {
	index =  (nowIndex+1)%size;
	fadeAB();
}

//点击任一图片
	if (set.thumbObj!=null) {
	thumb = $(set.thumbObj);
//初始化
	thumb.removeClass(set.thumbNowClass).eq(0).addClass(set.thumbNowClass);

		thumb.click(function () {
			index = thumb.index($(this));
			fadeAB();
			if (set.clickFalse == true) {
				return false;
			}
		});
		thumb.mouseenter(function () {
			index = thumb.index($(this));
			delayRun = setTimeout(fadeAB,set.delayTime);
		});
		thumb.mouseleave(function () {
			clearTimeout(delayRun);
		});
	}

//点击上一个
	if (set.botNext!=null) {
		var botNext = $(set.botNext);
		botNext.click(function () {
			runNext();
			return false;
		});
	}

//点击下一个
	if (set.botLast!=null) {
		var botLast = $(set.botLast);
		botLast.click(function () {
			index = (nowIndex+size-1)%size;
			fadeAB();
			return false;
	});
	}

//自动运行
	if (set.autoChange==true) {
	startRun = setInterval(runNext,set.changeTime);
	if (set.overStop == true) {
		_self.mouseenter(function () {
			clearInterval(startRun);//重置自动切换函数
			
		});
		_self.mouseleave(function () {
			startRun = setInterval(runNext,set.changeTime);
		});
		}
	}

}

var $sG = $.sGalleryLong;

})(jQuery);


function slide(Name,Class,Width,Height,fun){
	$(Name).width(Width);
	$(Name).height(Height);
	
	if(fun == true){
		$(Name).append('<div class="title-bg"></div><div class="title"></div><div class="change"></div>')
		var atr = $(Name+' div.changeDiv a');
		var sum = atr.length;
		for(i=1;i<=sum;i++){
			var title = atr.eq(i-1).attr("title");
			var href = atr.eq(i-1).attr("href");
			$(Name+' .change').append('<i>'+i+'</i>');
			$(Name+' .title').append('<a href="'+href+'">'+title+'</a>');
		}
		$(Name+' .change i').eq(0).addClass('cur');
	}
	$(Name+' div.changeDiv a').sGallery({//对象指向层，层内包含图片及标题
		titleObj:Name+' div.title a',
		thumbObj:Name+' .change i',
		thumbNowClass:Class
	});
	$(Name+" .title-bg").width(Width);
}

//缩略图
jQuery.fn.LoadImage=function(scaling,width,height,loadpic){
    if(loadpic==null)loadpic="../images/msg_img/loading.gif";
return this.each(function(){
   var t=$(this);
   var src=$(this).attr("src")
   var img=new Image();
   img.src=src;
   //自动缩放图片
   var autoScaling=function(){
    if(scaling){
     if(img.width>0 && img.height>0){ 
           if(img.width/img.height>=width/height){ 
               if(img.width>width){ 
                   t.width(width); 
                   t.height((img.height*width)/img.width); 
               }else{ 
                   t.width(img.width); 
                   t.height(img.height); 
               } 
           } 
           else{ 
               if(img.height>height){ 
                   t.height(height); 
                   t.width((img.width*height)/img.height); 
               }else{ 
                   t.width(img.width); 
                   t.height(img.height); 
               } 
           } 
       } 
    } 
   }
   //处理ff下会自动读取缓存图片
   if(img.complete){
    autoScaling();
      return;
   }
   $(this).attr("src","");
   var loading=$("<img alt=\"加载中...\" title=\"图片加载中...\" src=\""+loadpic+"\" />");
  
   t.hide();
   t.after(loading);
   $(img).load(function(){
    autoScaling();
    loading.remove();
    t.attr("src",this.src);
    t.show();
	//$('.photo_prev a,.photo_next a').height($('#big-pic img').height());
   });
  });
}

//向上滚动代码
function startmarquee(elementID,h,n,speed,delay){
 var t = null;
 var box = '#' + elementID;
 $(box).hover(function(){
  clearInterval(t);
  }, function(){
  t = setInterval(start,delay);
 }).trigger('mouseout');
 function start(){
  $(box).children('ul:first').animate({marginTop: '-='+h},speed,function(){
   $(this).css({marginTop:'0'}).find('li').slice(0,n).appendTo(this);
  })
 }
}

//文字多行无缝滚动classname:css类  
function startmarqueemulitline(classname,speed,delay){
	var _wrap=$('ul.'+classname);//定义滚动区域
	var _interval=delay;//定义滚动间隙时间
	var _moving;//需要清除的动画
	_wrap.hover(function(){
		clearInterval(_moving);//当鼠标在滚动区域中时,停止滚动
	},function(){
		_moving=setInterval(function(){
			var _field=_wrap.find('li:first');//此变量不可放置于函数起始处,li:first取值是变化的
			var _h=_field.height();//取得每次滚动高度
			_field.animate({marginTop:-_h+'px'},speed,function(){//通过取负margin值,隐藏第一行
				_field.css('marginTop',0).appendTo(_wrap);//隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
			})
		},_interval)//滚动间隔时间取决于_interval
	}).trigger('mouseleave');//函数载入时,模拟执行mouseleave,即自动滚动
}

//TAB切换
function SwapTab(name,title,content,Sub,cur){
  $(name+' '+title).mouseover(function(){
	  $(this).addClass(cur).siblings().removeClass(cur);
	  $(content+" > "+Sub).eq($(name+' '+title).index(this)).show().siblings().hide();
  });
}