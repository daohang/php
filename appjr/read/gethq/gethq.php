<?php
require("../../config.php");
require("../../../common/curl.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(1000); 

gethq();

function gethq()
{
	global $DH_output_path;
	$datenow = date("Ymd",time());
	getImage("http://hqpiczs.dfcfw.com/EM_Quote2010PictureProducter/picture/3990012R.png",$DH_output_path."images/hq/",'sz'.$datenow.'.png');
	getImage("http://hqpiczs.dfcfw.com/EM_Quote2010PictureProducter/picture/0000011R.png",$DH_output_path."images/hq/",'sh'.$datenow.'.png');
}
