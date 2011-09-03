<div style="padding-top:10px;">
	<div style="float:left; margin-right:15px; font-weight:bold;">Filters:</div>
	<input type="hidden" id="table" value="<?=$table?>" />
	<input type="hidden" id="char_count_limit" value="<?=$char_count_limit?>" />
	<input type="hidden" id="seo_field" value="<?=str_replace('-','_',IDE)?>" />
<?
	$filters = array(
		'category',
		'market_name',
		'market',
		'base',
		'volume',		
	);
	foreach ($filters as $filter) {
?>		<div style="float:left; margin-right:40px;">
			<div class="filter" type="<?=$type?>" style="font-weight:bold; width: 175px; padding-left:5px; cursor:pointer; border: 1px solid #999; border-bottom: 2px solid #999;" filter="<?=$filter?>">
				<?=str_replace('_',' ',$filter)?>	
				<span id="<?=$filter?>_selected" style="text-transform:lowercase"></span>
			</div>
			<div id="<?=$filter?>" style="position:absolute; display:none; min-width:180px; background-color: #fff; border-bottom: 1px solid #999; border-left: 1px solid #999; border-right: 1px solid #999;" class="filter-area">					<? include('pages/admin/seo/duplicate-data/filter.php') ?>
			</div>
		</div>
<?	
	}
?>
	<div class="clear"></div>
</div>

<div id="multi-listing">
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
	<div style="float:left">
		<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
			<legend class="legend">Phrase Part 1</legend>
<?
			if ($listing) foreach ($listing as $data) {
?>
			<div style="width:65px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>)</div><div style="float:left;"> <input type="checkbox" id="phrase1_<?=$data['phrase_id']?>" phrase="<?=$data['phrase']?>" volume="<?=$data['volume']?>" phrase_id="<?=$data['phrase_id']?>" class="multi-listing1-cb" id="<?=$data['lower_phrase']?>" /> <label for="phrase1_<?=$data['phrase_id']?>"><?=$data['lower_phrase']?></label></div>
        	<div class="clear"></div>
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
				<div style="width:65px; float:left; margin-right:5px; text-align:right;">(<?=$data['volume']?$data['volume']:0?>)</div><div style="float:left;"> <input type="checkbox" id="phrase2_<?=$data['phrase_id']?>" phrase="<?=$data['phrase']?>" volume="<?=$data['volume']?>" phrase_id="<?=$data['phrase_id']?>" class="multi-listing1-cb" id="<?=$data['lower_phrase']?>" /> <label for="phrase2_<?=$data['phrase_id']?>"><?=$data['lower_phrase']?></label></div>
        	<div class="clear"></div>
<?	
		}
?>
    	</fieldset>
	</div>
	<div style="float:left;">
		<fieldset style="width:350px; border: solid 1px #CCCCCC; padding: 15px; margin-right:15px;">
		<legend class="legend">Modifier</legend>
<?
		$mods = aql::select("dup_modifier { id as modifier_id, lower(phrase) as lower_phrase, phrase order by phrase asc }", array('dup_phrase_data'=>array('where'=>$where)));
		if ($mods) foreach ($mods as $data) {
?>
			<div> <input type="checkbox" id="mod_<?=$data['mod_id']?>" phrase="<?=$data['modifier']?>" mod_id="<?=$data['mod_id']?>" class="mod-cb" id="<?=$data['lower_modifier']?>" /> <label for="mod_<?=$data['mod_id']?>"><?=$data['modifier']?></label></div>
<?	
		}
?>
    	</fieldset>
	</div>
</div>