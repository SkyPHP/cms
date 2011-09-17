<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Duplicate Data - Phrase Skybox (dup_phrase_data: ".$o['dup_phrase_data_id'].")";
	$p->css[] = '/admin/seo/duplicate-data/phrases/skybox/phrase-skybox.css';
	$p->template('skybox','top');
?>
		<div class="field">
			<? $field = "phrase" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:500px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left">
			<? $field = "sub_category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="clear"></div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "volume" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="field float-left">
			<? $field = "holiday" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>
		<div class="clear"></div>
		<div class="field">
			<? $field = "keyword" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="field" />
		</div>		
<?	
	$p->template('skybox','bottom');
?>