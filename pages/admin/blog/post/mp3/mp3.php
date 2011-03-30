<?
$type = "mp3";
$blog_article_ide = IDE?IDE:$_POST['blog_article_ide'];
$blog_article_id = $blog_article_id?$blog_article_id:decrypt($blog_article_ide,'blog_article');
$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/mp3';
$media_upload['vfolder_path'] = $vfolder_path;
#$vfolder = media::get_vfolder($media_upload['vfolder_path']);
$aql="blog_media{	
					media_item_id as id,
					title
					where type='mp3'
					and blog_article_id = $blog_article_id
					order by title asc
				}";
$vfolder['items'] = aql::select($aql);
?>


<div class = "float-left youtube_upload">
	<h2>A) Youtube</h2>
	<input type = "button" value = "Add YouTube Song" onclick = "new_youtube_media('<?=$type ?>')" />
</div>
<div class = "has-floats" id = "images">
<h2>B) MP3 Upload</h2>
	<input type = "hidden" name = "vfolder_path" id = "vfolder_path" value = "<?=$vfolder_path ?>"/>
	<input type = "hidden" name = "session_id" id = "session_id" value = "<?=session_id() ?>"/>
<?
	include(INCPATH.'/../images/container.php');
?>
	<div id="blog-post-images" class="float-left blog-post-mp3">
<?
			include(INCPATH.'/../images/items.php');
?>
		<!--ajax goes here: blog image library-->
	</div>
</div>