<?
	if($blog_article){
		if($blog_article['media_item_ide'] == $img['media_item_ide']){
			$default = "default ";
		} else
			$default = null;
	}
?>
<div class = "<?=$default ?>blog_image draggable float-left" id = "<?=$img['media_item_ide'] ?>">
	<div class = "contextMenuItem">
		<input type = "hidden" value = "<?=$img['media_item_ide'] ?>" class = "media_item_ide" />
		<a href="javascript:void(0);" onclick = "insert_image_skybox($(this).parent())"><?=$img['img']?></a>
	</div>
<?#echo $img['media_item_id'];
if($_POST['tab'] == 'slideshow'){
?>
	<div class = "tab">Reorder</div>
<?
}
?>
</div>