<?
	$o = new dup_phrase_data(IDE);
	$p->title = "Duplicate Data - Phrase Skybox (dup_phrase_data: ".$o['dup_phrase_data_id'].")";
	$p->css[] = '/admin/seo/duplicate-data/phrases/skybox/phrase-skybox.css';
	$p->template('skybox','top');
?>
	<form>
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
			$('#save-message').html('<img src="loading.gif" />');
			data = $('form').serializeArray();
			console.log(data);
			$.post('/admin/seo/duplicate-data/phrases/ajax/save-form',data,function(html) {
				$('#save-message').html(html);
			});
		});
	});
</script>