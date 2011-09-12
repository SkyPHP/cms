<input type="button" class="save-multi" value="Save" style="margin-top:10px" /> <div id="multi-saved" style="margin-top:10px;"></div>

<?
	$where=array();
	if ($_POST['market']) $where[] = "market = '{$_POST['market']}'";
	if ($_POST['market_name']) $where[] = "market_name = '{$_POST['market_name']}'";
	if ($_POST['volume']) $where[] = "volume >= {$_POST['volume']}";
	if ($_POST['category']) $where[] = "category = '{$_POST['category']}'";
	if ($_POST['base']) $where[] = "base = '{$_POST['base']}'";
	$width = '25%';
	$listing = aql::select("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume order by volume DESC, phrase asc }", array('dup_phrase_data'=>array('where'=>$where)));
	
	
	$count = count($listing);
?>
    <input type="hidden" id="type" value="<?=$type?>" />
	<input type="hidden" id="person_id" value="<?=PERSON_ID?>" />
<div class="has-floats">	
	<div style="float:left">
		<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
			<legend class="legend">Phrase Part 1</legend>
<?
			if ($listing) foreach ($listing as $data) {
?>
				<div id="listing1_<?=$data['phrase_id']?>">
					<div style="width:65px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>) {<?=$data['phrase_id']?>}</div><div style="float:left;"> <input type="checkbox" id="phrase1_<?=$data['phrase_id']?>" phrase="<?=$data['phrase']?>" volume="<?=$data['volume']?>" phrase_id="<?=$data['phrase_id']?>" class="multi-listing1-cb" id="<?=$data['lower_phrase']?>" /> <label for="phrase1_<?=$data['phrase_id']?>"><?=$data['lower_phrase']?></label></div>
        			<div class="clear"></div>
                </div>
<?	
		}
?>
		</fieldset>
		</div>
		<div style="float:left;">
		<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
			<legend class="legend">Phrase Part 2</legend>
<?
			if ($listing) foreach ($listing as $data) {
?>
				<div id="listing2_<?=$data['phrase_id']?>">
					<div style="width:65px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>) {<?=$data['phrase_id']?>}</div><div style="float:left;"> <input type="checkbox" id="phrase2_<?=$data['phrase_id']?>" phrase="<?=$data['phrase']?>" volume="<?=$data['volume']?>" phrase_id="<?=$data['phrase_id']?>" class="multi-listing2-cb" id="<?=$data['lower_phrase']?>" /> <label for="phrase2_<?=$data['phrase_id']?>"><?=$data['lower_phrase']?></label></div>
        			<div class="clear"></div>
                </div>
<?	
		}
?>
    	</fieldset>
	</div>
	<div style="float:left;">
		<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
		<legend class="legend">Modifier</legend>
<?
		$where = array();
		if ($_POST['category']) $where[] = "( category = '{$_POST['category']}' OR category = 'general' )";
		$mods = aql::select("dup_modifier { id as mod_id, lower(phrase) as lower_phrase, phrase order by phrase asc }", array('dup_modifier'=>array('where'=>$where)));
		if ($mods) foreach ($mods as $data) {
?>
			<div>{<?=$data['mod_id']?>} <input type="checkbox" id="mod_<?=$data['mod_id']?>" phrase="<?=$data['phrase']?>" mod_id="<?=$data['mod_id']?>" class="mod-cb" id="<?=$data['lower_phrase']?>" /> <label for="mod_<?=$data['mod_id']?>"><?=$data['lower_phrase']?></label></div>
<?	
		}
?>
    	</fieldset>
	</div>
</div>
<input type="button" class="save-multi" value="Save" style="margin-top:10px;" />