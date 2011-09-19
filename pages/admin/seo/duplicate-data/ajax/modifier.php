<?
	$where = array();
	if ($_POST['category']) $where[] = "( category = '{$_POST['category']}' OR category = 'general' )";
	$mods = aql::select("dup_modifier { id as mod_id, lower(phrase) as lower_phrase, phrase order by phrase asc }", array('dup_modifier'=>array('where'=>$where)));
	if ($mods) foreach ($mods as $data) {
?>
		<div><input type="checkbox" id="mod_<?=$data['mod_id']?>" phrase="<?=$data['phrase']?>" mod_id="<?=$data['mod_id']?>" class="mod-cb" id="<?=$data['lower_phrase']?>" /> <label for="mod_<?=$data['mod_id']?>">{<?=$data['mod_id']?>} <?=$data['lower_phrase']?></label></div>
<?	
	}
	else echo "No Data";
?>