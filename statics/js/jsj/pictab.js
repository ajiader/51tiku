
$(function() {
	var sWidth = $("#focus").width(); 
	var len = $("#focus ul li").length; 
	var index = 0;
	var picTimer;
	var bodywidth=parent.document.documentElement.clientWidth;
	
	var btn = "<div class='btnBg'></div><div class='btn'>";
	for(var i=0; i < len; i++) {
		if(i==0){ btn += "<span>版本选择</span>";}
		if(i==1){ btn += "<span>功能简介</span>";}
		if(i==2){ btn += "<span>软件特点</span>";}
		
	}
	btn += "</div>"
	$("#focus").append(btn);
	$("#focus .btnBg").css("opacity",0.5);
	

	$("#focus .btn span").mouseenter(function() {
		index = $("#focus .btn span").index(this);
		showPics(index);
	}).eq(0).trigger("mouseenter");

	$("#focus ul").css("width",sWidth * (len + 1));
	$("#focus ul li").css("width",bodywidth);
	
	
	$("#focus").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			if(index == len) { 
				showFirPic();
				index = 0;
			} else {
				showPics(index);
			}
			index++;
		},3000); 
	}).trigger("mouseleave");
	
	function showPics(index) { 
		var nowLeft = -index*sWidth; 
		$("#focus ul").stop(true,false).animate({"left":nowLeft},500); 
		$("#focus .btn span").removeClass("on").eq(index).addClass("on")
	}
	
	function showFirPic() { 
		$("#focus ul").append($("#focus ul li:first").clone());
		var nowLeft = -len*sWidth;
		$("#focus ul").stop(true,false).animate({"left":nowLeft},500,function() {
			$("#focus ul").css("left","0");
			$("#focus ul li:last").remove();
		}); 
		$("#focus .btn span").removeClass("on").eq(0).addClass("on");
	}
});



function getObject(objID)
 {if (document.getElementById && document.getElementById(objID)) 
 {return document.getElementById(objID);} 
 else 
 {if (document.all && document.all(objID)) 
 {return document.all(objectId);} 
 else 
 {if (document.layers && document.layers[objID]) 
 {return document.layers[objID];} 
 else
 {return false;}
 }
 }
 }
 
function qhnrright(n)
{
for (var i = 1; i <= 4; i++)
{
if (i == n) 
{
getObject("qhnrright" + i).className = "selected";
getObject("qhnrrightDIV" + i).style.display = "";
} 
else
{
getObject("qhnrright" + i).className = "";
getObject("qhnrrightDIV" + i).style.display = "none";
}
}
}
function qhnrrighta(n)
{
for (var i = 1; i <= 5; i++)
{
if (i == n) 
{
getObject("qhnrrighta" + i).className = "selected";
getObject("qhnrrightaDIV" + i).style.display = "";
} 
else
{
getObject("qhnrrighta" + i).className = "";
getObject("qhnrrightaDIV" + i).style.display = "none";
}
}
}

function qhnrrightb(n)
{
for (var i = 1; i <= 5; i++)
{
if (i == n) 
{
getObject("qhnrrightb" + i).className = "selected";
getObject("qhnrrightbDIV" + i).style.display = "";
} 
else
{
getObject("qhnrrightb" + i).className = "";
getObject("qhnrrightbDIV" + i).style.display = "none";
}
}
}