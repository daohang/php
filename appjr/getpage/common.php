<?php

function getonlylinkmeta($row,&$mtitle,$link,$title,$cat)
{
	//echo $row['title'];
	$mtitle=testtitle($row['ctitle'],$title);
	//$mtitle=x11($title);
	//echo "\n res: $mtitle \n";
	//$mtitle=trimtitle($mtitle);
	if($mtitle<0||$mtitle==='')
	{
		//问题很大
		echo "mtitle=$mtitle % title=$title link=$link cat=$cat -> mtitle error 失败，请查明原因！</br> \n";
		return -1;
	}
	
	$movieyear = 0;
	preg_match('/((19|20|18)[0-9]{2,2})/',$title,$match);
	if(!empty($match[1]))
		$movieyear=$match[1];	
	return 0;
}

function getlinkmeta($row,&$linkcat,&$linkvalue,$link,$title,$cat)
{
	$linkcat = testneed($row['ccat'],$link,$title,$cat);
	$linkvalue = testneed($row['clinkvalue'],$link,$title,$cat);
	if($cat<0||$linkvalue<0)
	{
		echo "linkcat=$linkcat or linkvalue=$linkvalue % title=$title link=$link cat=$cat -> getlinkmeta error 失败，请查明原因！</br> \n";
		return -1;
	}
	return 0;
}

function testneed($need,$link,$title,$cat)
{
	$ret = 0;
	if($need==''||$need==null)
		return $ret;
	preg_match_all('/<c>(.*?)<\/c>/',$need,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $contain)
		{
			list($case,$pat,$ret) = explode('%', $contain);
			//echo $case.' ' .$pat.' '.$ret ." </br> \n";
			$match1 = array();
			switch ($case)
			{
				case 'c':
				{
					//echo 'c';
					preg_match('/'.$pat.'/i',$cat,$match1);
					break;
				}
				case 't':
				{
					//echo 't';
					preg_match('/'.$pat.'/i',$title,$match1);
					break;
				}
				case 'l':
				{
					//echo 'l';
					preg_match('/'.$pat.'/i',$link,$match1);
					break;
				}			
				default:
				{
					echo 'error in testneed to get '.$contain.' method';
					return -l;
				}
			}
			//print_r($match1);
			if(!empty($match1))
			{
				return $ret;
			}
		}
	}
	return $ret;
}

//提取标题
function testtitle($ctitle,$title)
{
	$result = '';
	$ltitle = $title;
	if($ctitle==''||$ctitle==null)
		return 0;
	preg_match_all('/<c>(.*?)<\/c>/',$ctitle,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $pattern)
		{
			list($case,$pat,$ret) = explode('%',$pattern);
			echo $case.' ' .$pat .' '.$ret." </br> \n";
			switch ($case)
			{
				case 'g':
				{
					//echo 'g';
					preg_match('/'.$pat.'/su',$ltitle,$match1);
					print_r($match1);
					if(!empty($match1[1]))
					{
						$tmp = getrealdate($match1[1]);
						echo '<<'.$tmp.'>>';
						if($tmp==false)
							break;
						$result .= $tmp.'/';
						$ltitle = $result;
					}
					break;
				}
				case 'f':
				{
					//echo 'f';
					$ltitle = preg_replace('/'.$pat.'/i','',$ltitle);
					break;
				}
				case 'x':
				{
					return x11($title);
				}					
				default:
				{
					echo 'error in testtitle';
					return -1;
				}
			}
			if($ret==0)//说明需要结束了
				break;
		}		
		if($case=='g')
		{
			if(trim($result)=='')
				return -1;		
			return substr($result,0,strlen($result)-1);
		}
		if(trim($result)=='')
			return -1;	
		return $ltitle;	
	}
	return -2;
}

function gettitlearray($title)
{
	$duanarray = array();
	$titles=preg_split("/(\/)/", $title);
	//print_r($titles);
	if(count($titles)>0)
	{
		foreach($titles as $eachtitle)
		{			 
			$trimtitle=trim($eachtitle," \t\n\r\0\x0B.`");
			echo $trimtitle.'--';
			if($trimtitle!='')
				array_push($duanarray,$trimtitle);		
		}
	}
	return $duanarray;
}
?>
