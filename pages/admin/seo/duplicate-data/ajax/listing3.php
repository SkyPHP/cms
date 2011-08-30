<?
	$where = array();
	$where[] = "id != {$_POST['phrase_id']}";
	if ($_POST['market_n']) $where[] = "market != '{$_POST['market_n']}'";
	if ($_POST['market']) $where[] = "market = '{$_POST['market']}'";
	if ($_POST['market_name']) $where[] = "market_name = '{$_POST['market_name']}'";
	if ($_POST['market_name_n']) if ($_POST['market_name_n'] != 'National') $where[] = "market_name != '{$_POST['market_name_n']}'";
	if ($_POST['category']) $where[] = "(category = '{$_POST['category_n']}' || category = 'general')";
	if ($_POST['base']) $where[] = "base = '{$_POST['base']}'";

	$listing2 = aql::select("dup_modifier { id as modifier_id, lower(phrase) as lower_phrase, phrase, volume order by volume DESC, phrase asc }", array('dup_phrase_data'=>array('where'=>$where)));

?>

<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:10px;">
    	<legend class="legend">Modifier (<?=count($listing2)?> Phrases)</legend>
<?
		if ($listing2) foreach ($listing2 as $data) {
?>
			<div style="width:55px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>)</div><div style="float:left;"> <input type="radio" name="phrase3" phrase="<?=$data['phrase']?>" modifier_id="<?=$data['modifier_id']?>" class="phrase-listing2-radio" id="<?=$data['lower_phrase']?>3" /> <label for="<?=$data['lower_phrase']?>3"><?=$data['lower_phrase']?></label></div>
        	<div class="clear"></div>
<?	
		} else echo " No Matches";
?>
    </fieldset>