<?
	$type = "video";
?>
<h2>Enter URLs Videos you would like to insert. (Youtube and Vimeo)</h2>
<div class = "has-floats">
	<div class = "float-left image_picker_upload">
		<input type = "button" value = "New Video Link" onclick = "new_youtube_media('<?=$type ?>')" />
	</div>
	<div id = "youtube_media_urls" class = "float-left has-floats blog-post-images">
<?
	include (INCPATH.'/../ajax/video-media-list.php');
?>
	</div>
</div>