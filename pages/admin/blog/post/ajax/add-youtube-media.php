<?
$title = 'Add Video';
template::inc('skybox','top');
?>
<input type = "hidden" value = "<?=$_POST['type']?>" id = "youtube_type"/>
<div class = "field">
	<label for="youtube_url">Video URL (Youtube or Vimeo)</label>
	<input class = "wide100" type = "text" id = "youtube_url" />
</div>
<div class = "field">
	<label for="media_title">Title</label>
	<input class = "wide100" type = "text" id = "media_title" />
</div>
<div class = "has-floats">
	<input type = "button" value = "Save" onclick  = "insert_youtube_media('<?=$_POST['type'] ?>')" class = "float-right"/>
</div>
<?
template::inc('skybox','bottom');
?>