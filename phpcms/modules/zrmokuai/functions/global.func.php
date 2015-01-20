<?php
function gettextarea($str)
{
	$str = str_replace("n",'',str_replace(chr(13),'',$str));
    return  $str;
}


?>