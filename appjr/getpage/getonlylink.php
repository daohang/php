<?php
require("../config.php");
require("../dbaction.php");
require("../../common/base.php");
require("../../common/dbaction.php");
require("x11.php");
require("common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getonlylink();
mysql_close($conn);

function getonlylink()
{
	//$sql="select l.*,a.* from onlylink l,author a where l.author=a.name and l.fail<300 ";
	$sql="select l.*,a.* from onlylink l,author a where l.author=a.name and l.fail<300 ";
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
		$datebegin = getupdatebegin($d,'onlylink');
		$sql .= " and l.updatetime > '$datebegin'";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['a']))
	{
		$a = $_REQUEST['a'];
		$sql .= " and a.name = '$a'";
	}
	
	//$sql .=' limit 0,200';
	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	$i=0;
	while($row = mysql_fetch_array($results))
	{
		//print_r($row);
		echo "</br>\n".$i.":";
		$i++;
		echo $row['title'].'/'.$row['ctitle']."</br>\n";
		$mtitle=testtitle($row['ctitle'],$row['title']);
		if($mtitle<0)
		{
			echo "mtitle not right % ctitle=".$row['ctitle']." mtitle=".$mtitle." error 失败，请查明原因！</br> \n";
			setfail('onlylink',$row['link']);
			continue;
		}
		if($mtitle===0)
		{
			echo "没有设置mtitle</br> \n";
			continue;
		}

		updateonlylink($row['link'],$mtitle);
	}
}
?>
