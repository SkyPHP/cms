<?
	if (!$num) $num = 1;
	$where=array();
	if (is_array($_POST['ids'])) $where[] = "dup_phrase_data.id not in (".implode(',',$ids).")";	
	if ($_POST['market']) $where[] = "market = '{$_POST['market']}'";
	if ($_POST['market_name']) $where[] = "market_name = '{$_POST['market_name']}'";
	if ($_POST['volume']) $where[] = "volume >= {$_POST['volume']}";
	if ($_POST['category']) $where[] = "category = '{$_POST['category']}'";
	if ($_POST['base']) $where[] = "base = '{$_POST['base']}'";
	$listing = aql::select("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume order by volume DESC, phrase asc }", array('dup_phrase_data'=>array('where'=>$where)));
	
	if ($listing) foreach ($listing as $data) {
?>
		<div id="listing<?=$num?>_<?=$data['phrase_id']?>">
			<div style="width:65px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>)</div><div style="float:left; margin-right:5px;"> <input type="checkbox" id="phrase<?=$num?>_<?=$data['phrase_id']?>" phrase="<?=$data['phrase']?>" volume="<?=$data['volume']?>" phrase_id="<?=$data['phrase_id']?>" class="listing<?=$num?>-cb" id="<?=$data['lower_phrase']?>" /></div><div style="float:left; width:430px;"> <label for="phrase<?=$num?>_<?=$data['phrase_id']?>">{<?=$data['phrase_id']?>} <?=$data['lower_phrase']?></label></div>
			<div class="clear"></div>
		</div>
<?	
	}
	else echo "No Data";
?>