<?
$blog_article = aql::profile('blog_article',IDE);
$blog_article_id = $blog_article['blog_article_id'];
$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/images';
$media_upload['vfolder_path'] = $vfolder_path;
$type = 'mebox';
$aql = "blog_media	{
						media_item_id
						where blog_article_id = $blog_article_id
						and type = '$type'
					}";
$rs = aql::select($aql);
?>
<fieldset>
	<legend>Mediabox Image</legend>
	<div class = "has-floats" id = "images">
		<input type = "hidden" name = "vfolder_path" id = "vfolder_path" value = "<?=$vfolder_path ?>"/>
		<input type = "hidden" name = "session_id" id = "session_id" value = "<?=session_id() ?>"/>
<?
		include(INCPATH.'/../images/container.php');
?>
		<div id="blog-post-images" class="has-floats float-left blog-post-images">
<?
	if($rs){
		foreach($rs as $media_item){
			$img = media::get_item($media_item['media_item_id'],100,100);
			include(INCPATH.'/../images/item.php');
		}
	} else {
		echo 'No media uploaded.';
	}
?>
			<!--ajax goes here: blog image library-->
		</div>
	</div>
</fieldset>
<?
	$type = 'tnail';
	$app = '_tnail';
	$aql = "blog_media	{
						media_item_id
						where blog_article_id = $blog_article_id
						and type = '$type'
					}";
?>
<fieldset>
	<legend>Mediabox Thumbnail Image (optional)</legend>
	<div class = "has-floats" id = "images_tnail">
		<input type = "hidden" name = "blog_media_type" class = "blog_media_type" value = "<?=$type?>"/>
<?
	$rs = aql::select($aql);
		include(INCPATH.'/../images/container.php');
?>
		<div id="blog-post-images_tnail" class="has-floats float-left blog-post-images">
<?
	if($rs){
		foreach($rs as $media_item){
			$img = media::get_item($media_item['media_item_id'],100,100);
			include(INCPATH.'/../images/item.php');
		} 
	} else {
			echo 'No media uploaded.';
	}
?>
			<!--ajax goes here: blog image library-->
		</div>
	</div>
</fieldset>
<?
if($media_item){  
	$aql = "blog_article{
				title,
				introduction,
				media_item_id as article__media_item_id
			}
			blog_media	{
							media_item_id as blog_media__media_item_id
						}
			blog_article_tag{
				name
				where blog_media.blog_article_id = $blog_article_id
					and blog_media.type = 'mebox'
				order by iorder asc
				limit 1
			}";
	$rs = aql::select($aql);
};
$settingss = array("transition" => "fade", "duration" => 4000 , "height" => 322, "width" => 640 , "height_small" => 62, "width_small"=> 90 );
?>
<fieldset>
	<legend>Mediabox Preview</legend>
<?
if($rs){
	$rs[0]['media_item_id'] = $rs[0]['blog_media__media_item_id']?$rs[0]['blog_media__media_item_id']:$rs[0]['article__media_item_id'];
    
    $aql = "blog_media {
                media_item_id
                where blog_media.blog_article_id = {$rs[0]['blog_article_id']}
                and type = 'tnail'
                limit 1
            }";
    $thumb = aql::select($aql);
    $rs[0]['thumb__media_item_id'] = $thumb[0]['media_item_id']?$thumb[0]['media_item_id']:$rs[0]['media_item_id'];
	blog::marquee($rs,$settingss);
} else {
	echo "No images have been uploaded yet";
}
?>

</fieldset>
<ul class="contextMenu" id="imageContextMenu">
	<li>
		<a href="#set_article">Add to article</a>
	</li>
	<li>
		<a href="#delete">Delete</a>
	</li>
</ul>

