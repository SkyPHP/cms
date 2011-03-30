<?


$type = $type?$type:$_POST['type'];
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


$vimeo_id = $parsed_url['path'];
?>
<iframe src="http://player.vimeo.com/video<?=$vimeo_id?>" width="<?=width?>" frameborder="0">
</iframe>


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
?>