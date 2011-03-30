<?
$blog_article_id = $blog_article_id?$blog_article_id:decrypt(IDE,'blog_article');
$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/slideshow';
$media_upload['vfolder_path'] = $vfolder_path;
#echo $vfolder_path;
?>
<div id = "images" class = "has-floats">
	<input type = "hidden" name = "vfolder_path" id = "vfolder_path" value = "<?=$vfolder_path ?>"/>
	<input type = "hidden" name = "session_id" id = "session_id" value = "<?=session_id() ?>"/>
<?
	include(INCPATH.'/../images/container.php');
?>
	<div id="blog-post-images" class="has-floats float-left blog-post-images">
		<?
			include(INCPATH.'/../images/items.php');
		?>
		<!--ajax goes here: blog image library-->
	</div>
</div>
<ul class="contextMenu" id="imageContextMenu">
	<li>
		<a href="#set_article">Add to article</a>
	</li>
	<li>
		<a href="#properties">Caption and credits</a>
	</li>
	<li>
		<a href="#delete">Delete</a>
	</li>
</ul>