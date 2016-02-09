<?php
function dh_mysql_query($sql)
{
	$rs = mysql_query($sql);
	$mysql_error = mysql_error();
	if($mysql_error)
	{
		echo 'dh_mysql_query error info:'.$mysql_error.'</br>';
		echo $sql;
		return null;
	}
	return $rs;
}

//给count(*)的sql去除count(*)
function dh_mysql_get_count($sql)
{
	$results=dh_mysql_query($sql);
	$count = mysql_fetch_array($results);
	return $count[0];
}

function insertcelebrity($celebritys)
{
	preg_match_all('/\[(.*?)\]/s',$celebritys,$match);
//	print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $celebrity)
		{
			list($name, $id) = split('[|]', $celebrity);
			$name=trim($name);
			$id=trim($id);
			$name = str_replace("'","\'",$name);
			$sql="insert into celebrity(id,name) values('$id','$name') ON DUPLICATE KEY UPDATE name = '$name',fail=0";
			$sqlresult=dh_mysql_query($sql);
		}
	}	
}

function setupdatetime($change,$newdate,$authorname)
{
	if($change)
	{
		$sql="update author set updatetime='$newdate' where name = '$authorname';";
		echo $sql."</br>\n";
		$result=dh_mysql_query($sql);		
	}
}



function getupdatetime($urlcat,$authorname)
{
	$updatetime = array();	
	foreach ($urlcat as $eachurlcat)
	{
		$sql="select max(updatetime) from link where author='$authorname' and cat like '%".$eachurlcat."%'";
		$sqlresult=dh_mysql_query($sql);
		$row = mysql_fetch_array($sqlresult);
		array_push($updatetime,date("Y-m-d H:i:s",strtotime($row[0])));
	}
	print_r($updatetime);
	return $updatetime;
}

function geturl($trueurl,$authorname=null)
{
	$buff = get_file_curl($trueurl);
	//如果失败，就使用就标记失败次数
	if(!$buff)
	{
		sleep(5);
		$buff = get_file_curl($trueurl);
		if(!$buff)
		{
			echo 'error: fail to get file '.$trueurl."!</br>\n";	
            if($authorname!=null)
            {
			    $sql="update author set failtimes=failtimes+1 where name='$authorname';";
			    $result=dh_mysql_query($sql);
            }
			return false;
		}
	}
	$buff = iconvbuff($buff);
	return $buff;
}

function setfail($table,$link)
{
	echo " no get $table set fail ++ ";
	$sqlupdate = "update $table set fail = fail+1 where link = '$link'" ;
	dh_mysql_query($sqlupdate);	
}
?>
