<?
$blog_article = aql::profile('blog_article',IDE);
$blog_article_id = $blog_article['blog_article_id'];
$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/images';
$media_upload['vfolder_path'] = $vfolder_path;
$type = 'tbox';
$aql = "blog_media	{
						media_item_id,
						title
						where blog_article_id = $blog_article_id
						and type = '$type'
					}";
$rs = aql::select($aql);
?>
<div class = "has-floats" id = "images">
	<input type = "hidden" name = "vfolder_path" id = "vfolder_path" value = "<?=$vfolder_path ?>"/>
	<input type = "hidden" name = "session_id" id = "session_id" value = "<?=session_id() ?>"/>
<?
	include(INCPATH.'/../images/container.php');
?>
	<div id="blog-post-images" class="blog-post-images float-left">
<?
if($rs){
	foreach($rs as $media_item){
		$img = media::get_item($media_item['media_item_id'],100,100);
		include(INCPATH.'/../images/item.php');

?>
		<div class = "field">
			<label for="short_intro">Topbox Subtitle</label>
			<input maxlength = 44 type="text" id="topbox_title" value="<?=$media_item['title']?>" />
			<input type = "button" value = "Save" onclick = "set_blog_media_title(this)" />
		</div>
<?
	}
}
?>
	</div>
</div>
<ul class="contextMenu" id="imageContextMenu">
	<li>
		<a href="#set_article">Add to article</a>
	</li>
	<li>
		<a href="#delete">Delete</a>
	</li>
</ul>

