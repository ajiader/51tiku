// JavaScript Document

function getClass(tagName, classStr) 
{ 
	   if (document.getElementsByClassName) 
	   {
			return document.getElementsByClassName(classStr)
	   }
	   else 
	   {
			var nodes = document.getElementsByTagName(tagName),
			ret = []
			for (i = 0; i < nodes.length; i++) 
			{
				if (hasClass(nodes[i],classStr))
				{
					ret.push(nodes[i])
				}
			}
		   return ret;
	   }
	   function hasClass(tagStr,classStr)
	   {
			var arr=tagStr.className.split(/\s+/); 
			for(var i=0;i<arr.length;i++)
			{
				if(arr[i]==classStr)
				{
					return true;
				}
			}
			return false;
	   }
}

function onresizeload()
{
	var bodyHight=parent.document.documentElement.clientHeight;
	var bodywidth=parent.document.documentElement.clientWidth;
	if(getObject("cxb_jh"))
	{
		getObject("cxb_jh").style.height=(bodyHight-22)+"px";
	}
	if(getObject("cxb_left"))
	{
		getObject("cxb_left").style.height=(bodyHight-22-38)+"px";
	}
	if(getObject("cxb_center"))
	{
		getObject("cxb_center").style.height=(bodyHight-24-36)+"px";
	}
	if(getObject("cxb_right"))
	{
		getObject("cxb_right").style.height=(bodyHight-22-38)+"px";
	}
	if(getObject("cxb_right"))
	{
		getObject("cxb_right").style.width=(bodywidth-406-60-28)+"px";
	}
	if(getObject("leftctnr"))
	{
		getObject("leftctnr").style.height=(bodyHight-108)+"px";
	}
	if(getObject("leftcontentDIV2"))
	{
		getObject("leftcontentDIV2").style.height=(bodyHight-95)+"px";
	}
	if(getObject("leftcontentDIV3"))
	{
		getObject("leftcontentDIV3").style.height=(bodyHight-95)+"px";
	}
	if(getObject("leftcontentDIV4"))
	{
		getObject("leftcontentDIV4").style.height=(bodyHight-95)+"px";
	}
	if(getObject("rightframe"))
	{
		getObject("rightframe").style.height=(bodyHight-22-38)+"px";
		getObject("rightframe").style.width=(bodywidth-406-60-28)+"px";
		if((1003-470-504-20)<=13)
		{
			document.getElementById("rightframe").scrolling="yes";
			//document.getElementById('rightframe').setAttribute('scrolling','no');
			//document.frames(1).document.body.style.overflow='hidden';
		}
	}
	if(getObject("frameleft"))
	{
		getObject("frameleft").style.height=(bodyHight-22-38)+"px";
		getObject("frameleft").style.width="400px";
	}
	if(getObject("loginhblock"))
	{
		getObject("loginhblock").style.height=(bodyHight-22)+"px";
	}
	
   if(getObject("show2"))
	{
		getObject("show2").style.height=(bodyHight-22-38)+"px";
		getObject("show2").style.width=(bodywidth-406-60-28)+"px";
	}
	
	
	
}
function selectul(textid,divid)
{
	var pleasexz=document.getElementById(textid);
	var dvone=document.getElementById(divid);
	var linum=dvone.getElementsByTagName("li");
	pleasexz.onclick=function()
	{
		if(!dvone.style.display || dvone.style.display=="none")
		{
			dvone.style.display="block";
		}else
		{
			dvone.style.display="none";	
		}
	}
	for(var i=0; i<linum.length; i++)
	{
		linum[i].onclick=function()
		{
			pleasexz.value=this.innerHTML;
			dvone.style.display="none";
		}
	}	
}

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
 
function onelist(n)
{
for (var i = 1; i <= 4; i++)
{
if (i == n) 
{
getObject("onelist" + i).className = "selected";
getObject("leftcontentDIV" + i).style.display = "";
} 
else
{
getObject("onelist" + i).className = "";
getObject("leftcontentDIV" + i).style.display = "none";
}
}
}

$(function(){
    removeKey();
});
//iframe load
function aaa(url)
{
	$("#rightframe").hide();
	$("#load").show(); 
	$("#rightframe").attr("src",url);
	$("#rightframe").load(function(){ 
        $('#load').hide();
		$("#rightframe").show();
	}); 
}
//屏蔽快捷键
  function KeyDown(){ //屏蔽鼠标右键、Ctrl+n、shift+F10、F5刷新、退格键
   //alert("ASCII代码是："+event.keyCode);
   
   if ( (window.event.altKey)&&
   (  (window.event.keyCode==37)|| //屏蔽 Alt+ 方向键 ←
      (window.event.keyCode==39) )  ){ //屏蔽 Alt+ 方向键 →
       //alert("不准你使用ALT+方向键前进或后退网页！");
       event.returnValue=false;
   }

  if ( //屏蔽退格删除键
   (event.keyCode==116)|| //屏蔽 F5 刷新键
   (event.keyCode==112)|| //屏蔽 F1 刷新键
   (event.ctrlKey && event.keyCode==82)){ //Ctrl + R
   event.keyCode=0;
   event.returnValue=false;
// alert("不准你使用快捷！");
   }
  if ((event.ctrlKey)&&(event.keyCode==78)) //屏蔽 Ctrl+n
  {
    alert("ctrl + n");
 event.returnValue=false;
  }

   if ((event.shiftKey)&&(event.keyCode==121)) //屏蔽 shift+F10
     {
     //alert(" shift+F10 ");
     event.returnValue=false;
  }

   if (window.event.srcElement.tagName == "A" && window.event.shiftKey)
{
  window.event.returnValue = false; //屏蔽 shift 加鼠标左键新开一网页
}

   if ((window.event.altKey)&&(window.event.keyCode==115)){ //屏蔽Alt+F4
   //window.showModelessDialog("about:blank","","dialogWidth:1px;dialogheight:1px");
   return false;
}
 }
 
 //去掉快捷键
 function removeKey(){
	 document.onkeydown = KeyDown;
	  document.oncontextmenu=new Function("event.returnValue=false;");
     document.onselectstart=new Function("event.returnValue=false;");

 }