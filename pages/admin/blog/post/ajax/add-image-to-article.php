<?
$title = "Add Image to Article";
template::inc('skybox','top');
$media_item_ide = $_POST['media_item_ide'];
$model = 'media_item';
$img = media::get_item($media_item_ide,240,300);
$blog_article_ide = $_POST['blog_article_ide'];
$blog_article_id = decrypt($blog_article_ide,'blog_article');
$profile = aql::profile('media_item',$media_item_ide);
$blog_ide = $_POST['blog_ide'];
if(!$blog_ide && $blog_article_id)
	$blog_ide = encrypt(aql::value('blog_article.blog_id',$blog_article_id),'blog');
$aql = "blog {	
				full_width,
				partial_width
				where blog.ide = $blog_ide
			} ";
$rs = aql::select($aql);
if($rs){
	$full_width = $rs[0]['full_width'];
	$partial_width = $rs[0]['partial_width'];
}
?>
<div class = "has-floats" id = "insert_image_skybox" class = "has-floats">
	<input type = "hidden" value = "<?=$media_item_ide ?>" id = "media_item_ide"/>
	<div class = "col float-left">
		<div class="field">
			<? $field = 'caption'; ?>
			<label class="label" for = "<?=$field?>"><?=ucwords($field) ?></label>
			<textarea id = "<?=$field ?>" class = "wide" name="<?=$field?>"><?=$profile[$field]?></textarea>	
		</div>
		<div class = "field">
			<? $field = 'credits'; ?>
			<label class="label" for = "<?=$field?>">Credits</label>
			<textarea id = "<?=$field ?>" class = "wide" name="<?=$field?>"><?=$profile[$field]?></textarea>
		</div>
<?
	if($full_width){
?>
		<div class = "field">
<?
	snippet::radio(array(
							'name'=>'image',
							'radio_id'=>'image_horizontal',
							'multi_label'=>'Full Width ('.$full_width.'px)',
							'checked'=>true
						));
?>
			<input type = "hidden" value = "<?=$full_width ?>" name = "insert_width" class = "insert_width" />
			<input type = "hidden" value = "5555" name = "insert_height" class = "insert_height" />
			<input type = "hidden" name = "crop" value = "0" class = "insert_crop" />
		</div>
<?
	}
	if($partial_width){
?>
		<div class = "field">
<?
	snippet::radio(array(
							'name'=>'image',
							'radio_id'=>'image_vertical',
							'multi_label'=>'Partial Width ('.$partial_width.'px)'
						));
?>
			<input type = "hidden" value = "<?=$partial_width ?>" name = "insert_width" class = "insert_width" />
			<input type = "hidden" value = "5555" name = "insert_height" class = "insert_height" />
			<input type = "hidden" name = "crop" value = "0" class = "insert_crop" />
		</div>	
<?
	}
?>
		<div class = "field">
<?
	snippet::radio(array(
							'name'=>'image',
							'radio_id'=>'image_custom',
							'multi_label'=>'Custom',
							'onclick'=>"$('#insert_width').select()",
							'checked'=>false
						));
?>	
				<input type = "text" name = "insert_width" class = "insert_width" onclick = "$('#image_custom').attr('checked','checked')"/>
				x
				<input type = "text" name = "insert_height" class = "insert_height" onclick = "$('#image_custom').attr('checked','checked')"/> *
			<div class = "legend">* Leave blank for full size</div>
			<input type = "hidden" name = "crop" value = "0" class = "insert_crop" />
		</div>
		<div class = "field">
			<hr/>
			<center>
				<input type = "button" onclick = "insert_image(this)" value = "Insert" />
			</center>
		</div>
	</div>
	<div class = "col float-right">
		<div class = "properties-image">
<?
	echo $img['img'];
?>
		</div>
	</div>
</div>
<?
template::inc('skybox','bottom');
?>