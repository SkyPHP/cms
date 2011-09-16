<fieldset style="width:520px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
	<legend class="legend">Modifier</legend>
<?
	$where = array();
	if ($_POST['category']) $where[] = "( category = '{$_POST['category']}' OR category = 'general' )";
	$mods = aql::select("dup_modifier { id as mod_id, lower(phrase) as lower_phrase, phrase order by phrase asc }", array('dup_modifier'=>array('where'=>$where)));
	if ($mods) foreach ($mods as $data) {
?>
		<div style="width:65px; float:left; margin-right:5px; text-align:right;">{<?=$data['mod_id']?>}</div> <div style="float:left; margin-right:5px;"><input type="checkbox" id="mod_<?=$data['mod_id']?>" phrase="<?=$data['phrase']?>" mod_id="<?=$data['mod_id']?>" class="mod-cb" id="<?=$data['lower_phrase']?>" /></div><div style="float:left; width: 430px"><label for="mod_<?=$data['mod_id']?>"><?=$data['lower_phrase']?></label></div>
<?	
	}
?>
</fieldset>