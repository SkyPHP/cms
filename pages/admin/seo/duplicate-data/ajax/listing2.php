<?
	$where = array();
	$where[] = "volume > 0";
	$where[] = "id != {$_POST['phrase_id']}";
	if ($_POST['market']) $where[] = "market = '{$_POST['market']}'";
	if ($_POST['market_name']) $where[] = "market_name = '{$_POST['market_name']}'";
	if ($_POST['volume']) $where[] = "volume = {$_POST['volume']}";
	if ($_POST['category']) $where[] = "category = '{$_POST['category']}'";
	if ($_POST['base']) $where[] = "base = '{$_POST['base']}'";

	$listing2 = aql::select("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume order by volume DESC, phrase asc }", array('dup_phrase_data'=>array('where'=>$where)));

?>

<fieldset style="width:100%; border: solid 1px #CCCCCC; padding: 15px; float:left; margin-right:10px;">
    	<legend class="legend">Phrase Part 2 (<?=$count?> Phrases)</legend>
<?
		if ($listing2) foreach ($listing2 as $data) {
?>
			<div style="width:55px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?>)</div><div style="float:left;"> <input type="radio" name="phrase2" part="1" phrase="<?=$data['phrase']?>" phrase_id="<?=$data['phrase_id']?>" class="<?=$type?>-listing2-radio" id="<?=$data['lower_phrase']?>" /> <label for="<?=$data['lower_phrase']?>"><?=$data['lower_phrase']?></label></div>
        	<div class="clear"></div>
<?	
		} else echo " No Matches";
?>
    </fieldset>