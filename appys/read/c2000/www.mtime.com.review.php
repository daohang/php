<?php
function www_mtime_com_review_php()
{
	$authorname='时光网评论';
	$authorurl='http://www.mtime.com/review';
	print_r($authorname);

	$url = array('http://www.mtime.com/top/blog/hot_24h/');
	$urlcat= array('热体评论');	
	print_r($url);
	$updatetime = getupdatetime($urlcat,$authorname);
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<4)
		{
			$i++;
			if($i==1)
				$trueurl = $eachurl;
			else
				$trueurl = $eachurl.'index-'.$i.'.html';
			//echo $trueurl."\n";
			//continue;
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;
				
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);	
			preg_match_all('/<a class="[^\"]+" href="(http:\/\/i.mtime.com\/[^\"]+)" target="\_blank">(.*?)<\/a>/is',$buff,$match);	
			preg_match_all('/<em class="mlr10">([0-9\s\-\:]+)<\/em>/is',$buff,$match1);	
			//print_r($match);
			//print_r($match1);

			if(empty($match[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match[2] as $key2=>$div)			
			{
				$strpubdate=$match1[1][$key2];
				$rssinfo->update = getrealtime($strpubdate);			
			
				if($rssinfo->update<$updatetime[$key])
				{
					echo "爬取到已经爬取文章，爬取结束! </br>\n";
					$change = false;	
					break;
					//continue;
				}
				
				$rssinfo->cat =trim($urlcat[$key]);
				$rssinfo->link =$authorurl.trim($match[1][$key2]);
				$rssinfo->title = $match[2][$key2];		
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
