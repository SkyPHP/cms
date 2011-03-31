<?
$blog_article_ide = $blog_article_ide?$blog_article_ide:IDE;
$blog_article_id = $blog_article_id?$blog_article_id:decrypt($blog_article_ide,'blog_article');
$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/images';
$media_upload['vfolder_path'] = $vfolder_path;
$vfolder = media::get_vfolder($media_upload['vfolder_path']); 
if(!$blog_article)
	$blog_article = aql::profile('blog_article',$blog_article_ide);
?>

<div class = "has-floats" id = "images">
	<input type = "hidden" name = "vfolder_path" id = "vfolder_path" value = "<?=$vfolder_path ?>"/>
	<input type = "hidden" name = "session_id" id = "session_id" value = "<?=session_id() ?>"/>
<?
	include('container.php');
?>
	<div id="blog-post-images" class="float-left blog-post-images">
<?
			include('items.php');
?>
		<!--ajax goes here: blog image library-->
	</div>
</div>
<ul class="contextMenu" id="imageContextMenu">
	<li>
		<a href="#set_article">Add to article</a>
	</li>
	<li>
		<a href="#delete">Delete</a>
	</li>
<?
	//only display these options if there is more than one image in the blog
	if(count($vfolder['items'])>0){
?>
	<li>
		<a href="#set_tbox">Use for topbox</a>
	</li>
	<li>
		<a href="#set_mebox">Use for mediabox</a>
	</li>
	<li>
		<a href="#set_tnail">Use for mediabox thumbnail</a>
	</li>
	<li>
		<a href="#set_def">Set as default image</a>
	</li>
	
<?
	}
?>
</ul>