
    // increase the default animation speed to exaggerate the effect 
	//show:"blind",clip,drop,explode,fold,puff,slide,scale,size,pulsate

		//	hide:"blind",clip,drop,explode,fold,puff,slide,scale,size,pulsate
 $.fx.speeds._default = 5000;
    $(function() {
        $( "#dialog" ).dialog({
            autoOpen: open,
            show: "size",
		    width: "auto",
				height: "auto",
			position: "fixed",
            resizable: false,
            hide: "explode"
        });
 
       //setTimeout(function(){ $("#dialog").dialog("close")   },5000);   

    });
