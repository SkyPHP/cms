
<div class = "float-left mp3-item" id = "<?=$img['media_item_ide'] ?>">
	<div>
		<input type = "hidden" value = "<?=$img['media_item_ide'] ?>" class = "media_item_ide" />
		<?=$img['img']?>
		<div class = "filename">
			<?=$img['filename'] ?>
			<input type = "button" value = "Delete" onclick = "delete_media($(this).parent().parent())" />
		</div>
		<div class="mp3_title_cont has-floats">
			<input class="float-left mp3_title" onclick="$(this).select()" type="text" value="<?=$item['title']?$item['title']:$img['filename']?>"/>
			<input class="float-left" type="button" value="Save Title" onclick="set_blog_media_title(this,$('.mp3_title',$(this).parent()).val(),'mp3')"/>
		</div>
	</div>
</div>