<?
if(IDE)
	$blog_article_ide = IDE;
if($_POST['blog_article_ide'])
	$blog_article_ide = $_POST['blog_article_ide'];

	
	
$type = $type?$type:$_POST['type'];
	$aql = "blog_article_video	{
									youtube_url as video_url,
									title
									where blog_article_ide = $blog_article_ide
									and type = '$type'
								}";
	$rs = aql::select($aql);

	if($rs){
		foreach($rs as $media){
			
			include ('media-row.php');		
		}
	}
?>