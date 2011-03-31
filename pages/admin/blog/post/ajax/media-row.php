<?

if($media['blog_article_video_id']){
$video_url = $media['video_url'];
$parsed_url = parse_url($video_url);

?>
<div class = "float-left youtube_video" style = "width:<?=$width ?>px">
	
	<input type = "hidden" class = "blog_article_video_ide" value = "<?=$media['blog_article_video_ide'] ?>" />
<?
	if($parsed_url['host'] == 'www.youtube.com')
		include('youtube-embed.php');
	if($parsed_url['host'] == 'vimeo.com')
		include('vimeo-embed.php');
?>
	<div class = "filename">
    	<?=$host['host']?>
		<?=$media['title'] ?>
		<input type = "button" value = "Delete" onclick = "delete_youtube_media(this)" />
	</div>
</div>
<?
}
?>
