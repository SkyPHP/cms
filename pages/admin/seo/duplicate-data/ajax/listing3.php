<?
	$where = array();
	$where[] = "id != {$_POST['phrase_id']}";
	if ($_POST['market_n']) $where[] = "market != '{$_POST['market_n']}'";
	if ($_POST['market']) $where[] = "market = '{$_POST['market']}'";
	if ($_POST['market_name']) $where[] = "market_name = '{$_POST['market_name']}'";
	if ($_POST['market_name_n']) if ($_POST['market_name_n'] != 'National') $where[] = "market_name != '{$_POST['market_name_n']}'";
	if ($_POST['category']) $where[] = "(category = '{$_POST['category_n']}' || category = 'general')";
	if ($_POST['base']) $where[] = "base = '{$_POST['base']}'";

	$listing3 = aql::select("dup_modifier { id as modifier_id, lower(phrase) as lower_phrase, phrase order by phrase asc }", array('dup_modifier'=>array('where'=>$where)));

?>

<fieldset style="width:450px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
    	<legend class="legend">Modifier (<?=count($listing2)?> Modifiers)</legend>
<?
		if ($listing3) foreach ($listing3 as $data) {
?>
			<div style="width:65px; float:left; margin-right:5px; text-align:right;"></div><div style="float:left;">{<?=$data['modifier_id']?>} <input type="radio" name="phrase3" phrase="<?=$data['phrase']?>" modifier_id="<?=$data['modifier_id']?>" class="phrase-listing3-radio" id="<?=$data['lower_phrase']?>3" /> <label for="<?=$data['lower_phrase']?>3"><?=$data['lower_phrase']?></label></div>
        	<div class="clear"></div>
<?	
		} else echo " No Matches";
?>
    </fieldset>