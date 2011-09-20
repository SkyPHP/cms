<?
	$o = new dup_phrase_group(IDE);
	$p->title = "Duplicate Data - Phrase Skybox (dup_phrase_group: ".$o['dup_phrase_group_id'].")";
	$p->template('skybox','top');
?>
	<form model="dup_phrase_data" class="aqlForm">
		<input type="hidden" name="dup_phrase_data_ide" value="<?=$o['dup_phrase_group_ide']?>" />
		<input type="hidden" name="_token" value="<?=$o->_token?>" />
		<div class="field">
			<? $field = "name" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:500px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field">
			<? $field = "phrase1__dup_phrase_data_id" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:500px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "phrase2__dup_phrase_data_id" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left">
			<? $field = "dup_modifier_id" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="clear"></div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "website_id" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<select name="<?=$field?>">
				<option value="">- Website -</option>
<?
				$rs = aql::select("website { name order by name }");
				foreach ($rs as $r) {
?>	
					<option value="<?=$r['website_id']?>" <?=$r['website_id'] == $o['website_id']?'selected':''?>><?=$r['name']?></option>
<?
				}
?>
			</select>
		</div>
		<div class="field float-left">
			<? $field = "market_name" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="clear"></div>
		<div class="field" style="margin-right:20px;">
			<? $field = "page" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field">
			<input type="button" value="Save" id="save" />
		</div>
		<div id="save-message"></div>
	</form>
<?	
	$p->template('skybox','bottom');
?>
<script type="text/javascript">
	$(function() {
		
		$('#save').live('click',function() { 
			data = $('form').serializeArray();
			$('#save-message').aqlSave("dup_phrase_group",data);
		});
	});
</script>