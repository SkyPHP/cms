<?
$aql = "blog_article_video{
			youtube_url,
			type,
			title
			where blog_article_id = {$r['blog_article_id']}
			order by type desc
		}";
$rs = aql::select($aql);
if($rs){
?>
<div>
<?
	$width = 480;
	foreach($rs as $media){
		$type = $media['type'];
		$height = $type == 'mp3'?25:385;
		$youtube_url = $media['youtube_url'];
		$youtube_url = parse_url($youtube_url);
		$query = $youtube_url['query']?$youtube_url['query']:$youtube_url['fragment'];
		parse_str($query, $youtube_params);
		$youtube_id = $youtube_params['v']?$youtube_params['v']:$youtube_params['!v'];
?>
	<center>
<?
	if($media['title']){
?>
		<h3><?=$media['title']?></h3>
<?
	}
?>
		<object width="<?=$width ?>" height="<?=$height ?>">
			<param name="movie" value="http://www.youtube.com/v/<?=$youtube_id?>&hl=en&fs=1&rel=0&color1=0�4fe6ef&color2=0xd0e0e6"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<embed src="http://www.youtube.com/v/<?=$youtube_id?>&hl=en&fs=1&rel=0&color1=0�4fe6ef&color2=0xd0e0e6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?=$width ?>" height="<?=$height ?>"></embed>
		</object>
	</center>
<?
	}
?>
</div>
<?
	}
/*
$type = $r?$type:$_POST['type'];
$height = $type == 'mp3'?25:164;
$width = 200;
if($type == 'mp3'){
	$blog_article_ide = $_POST['blog_article_ide'];
	$blog_article = aql::profile('blog_article',$blog_article_ide);
	$blog_id = $blog_article['blog_id'];
	if($blog_id){
		$aql = "blog{
						full_width,
						partial_width
						where blog.id = $blog_id
					}";
		$rs = aql::select($aql);
		if($rs){
			$width = $rs[0]['partial_width']?$rs[0]['partial_width']:210;
		}
	}
?>
	<div class="article_image">
<?
}

$youtube_url = $youtube_url?$youtube_url:$_POST['youtube_url'];
$youtube_url = parse_url($youtube_url);
$query = $youtube_url['query']?$youtube_url['query']:$youtube_url['fragment'];
parse_str($query, $youtube_params);
$youtube_id = $youtube_params['v']?$youtube_params['v']:$youtube_params['!v'];
?>

<object width="<?=$width ?>" height="<?=$height ?>">
		<param name="movie" value="http://www.youtube.com/v/<?=$youtube_id?>&hl=en&fs=1&rel=0&color1=0�4fe6ef&color2=0xd0e0e6"></param>
		<param name="allowFullScreen" value="true"></param>
		<param name="allowscriptaccess" value="always"></param>
		<embed src="http://www.youtube.com/v/<?=$youtube_id?>&hl=en&fs=1&rel=0&color1=0�4fe6ef&color2=0xd0e0e6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?=$width ?>" height="<?=$height ?>"></embed>
</object>
<?
if($type=='mp3'){
	if($_POST['title']){
?>
	<div class="article_image_caption"><?=$_POST['title']?></div>
<?
	}
?>
</div>

<?
}
*/

?>