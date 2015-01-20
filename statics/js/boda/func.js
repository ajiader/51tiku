
/* SimpleSlider
------------------------------------------------------------------*/
$(document).ready(function() {
	/* #focusSlider
	----------------------------*/	
	if($('#focusSlider').html()){
		//alert('bb');
		spSlider.init();
	}
});

spSlider = {
	//var _c = _h = 0;
	_c : 0,
	_h : 0,
	_n : $('#focusSlider .slider_images li').length - 2,

	init:function(){
			$('#focusSlider .slider_nav li a').mouseover(function(){
				var i = $(this).parent().attr('title') - 1;
				clearInterval(spSlider._h);
				spSlider._c = i;
				spSlider.play();
				spSlider.changeAndStop(i);        
			})
			/*$("#mainSlider .show li").hover(function(){clearInterval(spSlider._h)}, function(){spSlider.play()});*/
			spSlider.play();
			//alert(spSlider._n)
	},
	play:function(){
			spSlider._h = setInterval("spSlider.auto()", 2000);
	},
	change:function(i){
			$('#focusSlider .slider_images li').removeClass('current').eq(i).addClass('current');
			$("#focusSlider .slider_nav li").removeClass('current').eq(i).addClass('current');
	},
	changeAndStop:function(i){
		spSlider.change(i);
		clearInterval(spSlider._h);
	},
	auto:function(){
			spSlider._c = spSlider._c > spSlider._n ? 0 : spSlider._c + 1;
			spSlider.change(spSlider._c);
	}
}

