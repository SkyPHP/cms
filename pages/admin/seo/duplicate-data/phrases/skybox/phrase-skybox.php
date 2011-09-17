<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Phrase Skybox (".$o['dup_phrase_data_id'].")";
	$p->template('skybox','top');
?>
		<div class="field">
			<? $field = "phrase" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field">
			<? $field = "category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field">
			<? $field = "sub_category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field">
			<? $field = "volume" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field">
			<? $field = "holiday" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field">
			<? $field = "keyword" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>		
<?	
	$p->template('skybox','bottom');
?>