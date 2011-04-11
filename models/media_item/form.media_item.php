<form model="media_item" method="post" class="aqlForm standard_form" action="/save/v2/media_item">
	<?
	$quality = array(
						100=>100,
						95=>95,
						90=>90,
						85=>85,
						80=>80,
						75=>75,
						70=>70,
						65=>65,
						55=>55,
						50=>50
					);
	?>
	<div class = "has-floats">
		<div class = "col float-left">
			<input type="hidden" id="media_item_ide" name="media_item_ide" value="<?=$r['media_item_ide']?>" />
			<input type="hidden" id="media_vfolder_ide" name="media_vfolder_ide" value="<?=$r['media_vfolder_ide']?>" />
			<input type="hidden" id="mod__person_ide" name="mod__person_ide" value="<?=$_SESSION['login']['person_ide']?>" />
			<input type="hidden" id="mod_time" name="mod_time" value='now()' />
			<?
			if (auth("admin:developer")) {
				?>
				<div class="field">ID: <?=$r['media_item_id']?></div>
				<?
			}
			?>
			<div class="field">
				<? $field = 'credits'; ?>
				<label class="label" for="<?=$field?>">Credits</label>
				<textarea class = "wide" name="<?=$field?>"><?=$r[$field]?></textarea>	
			</div>
			<div class="field">
				<? $field = 'caption'; ?>
				<label class="label" for="<?=$field?>"><?=ucwords($field) ?></label>
				<textarea class = "wide" name="<?=$field?>"><?=$r[$field]?></textarea>	
			</div>
			<div class="field">
				<? $field = 'title'; ?>
				<label class="label" for="<?=$field?>"><?=ucwords($field) ?></label>
				<input class = "wide" type="text" name="<?=$field?>" value="<?=$r[$field]?>" />	
			</div>
			
			<div class="field">
				<? $field = 'keywords'; ?>
				<label class="label" for="<?=$field?>"><?=ucwords($field) ?></label>
				<input class = "wide" type="text" name="<?=$field?>" value="<?=$r[$field]?>" />	
			</div>
			<div class="field">
				<? $field = 'quality'; ?>
				<label class="label" for="<?=$field?>">Quality (affects entire image folder/album)</label>
				<?
					$params = array	(
										'name'=>'quality',
										'selected_value'=>$r['quality']?$r['quality']:$default_image_quality,
										'null_option'=>false
									);
					snippet::dropdown($quality,$params);
				?>
			</div>	
		</div>
		<div class = "col float-left">
			<div class = "properties-image">
	<?
		$img = media::get_item($r['media_item_ide']);
	?>
		<img src="/media/<?=$img['media_instance_ide']?>" width="200" />
				<div class = "filename"><?=$img['filename'] ?></div>
			</div>
		</div>
	</div>
	<div class = "clear"></div>
	<input type="submit" value="Save" class="button" />
</form>