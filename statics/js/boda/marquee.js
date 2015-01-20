// JavaScript Document
// marquee
var hallTalkJs = function(divId,dir,isE)
{
    (function($){
     $.fn.kxbdMarquee = function(options){
         var opts = $.extend({},$.fn.kxbdMarquee.defaults, options);
		 
         return this.each(function(){
             var $marquee = $(this);
             var _scrollObj = $marquee.get(0);
             var scrollW = $marquee.width();
             var scrollH = $marquee.height();
             var $element = $marquee.children(); 
             var $kids = $element.children();
             var scrollSize=0;
             var _type = (opts.direction == 'left' || opts.direction == 'right') ? 1:0;
            
             $element.css(_type?'width':'height',10000);
             if (opts.isEqual) {
                 scrollSize = $kids[_type?'outerWidth':'outerHeight']() * $kids.length;
             }else{
                 $kids.each(function(){
                     scrollSize += $(this)[_type?'outerWidth':'outerHeight']();
                 });
             }
             if (scrollSize<(_type?scrollW:scrollH)) return; 
             $element.append($kids.clone()).css(_type?'width':'height',scrollSize*2);
            
             var numMoved = 0;
             function scrollFunc(){
                 var _dir = (opts.direction == 'left' || opts.direction == 'right') ? 'scrollLeft':'scrollTop';
                 if (opts.loop > 0) {
                     numMoved+=opts.scrollAmount;
                     if(numMoved>scrollSize*opts.loop){
                         _scrollObj[_dir] = 0;
                         return clearInterval(moveId);
                     } 
                 }
                 if(opts.direction == 'left' || opts.direction == 'up'){
                     _scrollObj[_dir] +=opts.scrollAmount;
                     if(_scrollObj[_dir]>=scrollSize){
                         _scrollObj[_dir] = 0;
                     }
                 }else{
                     _scrollObj[_dir] -=opts.scrollAmount;
                     if(_scrollObj[_dir]<=0){
                         _scrollObj[_dir] = scrollSize;
                     }
                 }
             }
             
             var moveId = setInterval(scrollFunc, opts.scrollDelay);
             
             $marquee.hover(
                 function(){
                     clearInterval(moveId);
                 },
                 function(){
                     clearInterval(moveId);
                     moveId = setInterval(scrollFunc, opts.scrollDelay);
                 }
             );            
         });
     };
     $.fn.kxbdMarquee.defaults = {
         isEqual:true,
         loop: 0,
         direction: 'left',
         scrollAmount:1,
         scrollDelay:50

     };
     $.fn.kxbdMarquee.setDefaults = function(settings) {
         $.extend( $.fn.kxbdMarquee.defaults, settings );
     };
})(jQuery);

 $('#'+divId).kxbdMarquee({direction:dir,isEqual:isE});
}

function openDlg(_url,_width,_height)
{
	window.open(_url, '', 'height='+_height+', width='+_width+', top=0,left=0, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
}

function openDlgs(_url,_width,_height)
{
	window.open(_url, '', 'height='+_height+', width='+_width+', top=200,left=300, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no ,status=no');
}

function openScrollDlgs(_url,WindowName,_width,_height)
{
	new_window = window.open(_url, WindowName, 'height='+_height+', width='+_width+', top=100,left=200, toolbar=YEs, scrollbars=yes, resizable=yes,location=yes ,directories=no,status=yes,menubar=yes,copyhistory=yes');
	new_window.focus();
}
function openSimpleDlgs(_url,_width,_height)
{    
	new_window = window.open(_url, '_media', 'height='+_height+', width='+_width+', top=40,left=150, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no ,status=no');
	new_window.focus();
}