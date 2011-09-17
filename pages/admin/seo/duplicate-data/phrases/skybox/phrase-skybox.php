<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Phrase Skybox (".$o['dup_phrase_data_id'].")";
	$p->template('skybox','top');
?>
		<div class="field">
			<? $field = "phrase" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:250px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:100px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left">
			<? $field = "sub_category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:100px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="clear"></div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "volume" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:100px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left">
			<? $field = "holiday" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:100px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="clear"></div>
		<div class="field">
			<? $field = "keyword" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:100px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>		
<?	
	$p->template('skybox','bottom');
?>