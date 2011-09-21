<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Duplicate Data - Phrase Skybox (dup_phrase_data: ".$o['dup_phrase_data_id'].")";
	$p->template('skybox','top');
?>
	<form model="dup_phrase_data" class="aqlForm">
		<input type="hidden" name="dup_phrase_data_ide" value="<?=$o['dup_phrase_data_ide']?>" />
		<input type="hidden" name="_token" value="<?=$o->_token?>" />
		<div class="field">
			<? $field = "phrase" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:500px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field">
			<? $field = "seo_formula" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:500px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left">
			<? $field = "sub_category" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="clear"></div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "market" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left">
			<? $field = "market_name" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="clear"></div>
		<div class="field float-left" style="margin-right:20px;">
			<? $field = "holiday" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="field float-left">
			<? $field = "base" ?>
			<label class="label" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label><br>
			<input style="width:200px;" type="text" id="<?=$field?>" value="<?=$o[$field]?>" name="<?=$field?>" />
		</div>
		<div class="clear"></div>
		<div class="field">
			<? $field = "keyword" ?>
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
			var data = $('form').serializeArray();
			var ide = $('input[name=dup_phrase_data_ide]').val();
			$('#save-message').aqlSave("dup_phrase_data",data);
			setTimeout("$.post ('/admin/seo/duplicate-data/phrases/ajax/row/'+ide,function(html) { $('#row_'+ide).html(html); });",500);
		});
	});
</script>