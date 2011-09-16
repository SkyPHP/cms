<?
	$p->title = 'Duplicate Data System ';
	
	switch($_GET['type']) {
		
		case 'title':
			$title .= '- Title';
			$type = "phrase";
		break;
		
		case 'h1':
			$type = "phrase";
			$title .= '- H1';
		break;
		
		case 'meta-title':
			$type = "phrase"; 
			$title .= '- Meta Title';
		break;
		
	}
		
	$p->template('seo','top');
		
	$filters = array(
		'category',
		'market_name',
		'market',
		'base',
		'volume',		
	);
	$width = 310;
	$listing = aql::select("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume order by volume DESC, phrase asc }"); 
		
	$count = count($listing);
	
	foreach ($seo_field_array as $header => $arr) {
		foreach ($arr as $field => $limit) {
			if ($field == str_replace('-','_',$_GET['type'])) $char_count_limit = $limit;
		}
	}	
?>	
	<div style="padding-top:10px;">
		<div style="float:left; margin-right:15px; font-weight:bold;">Filters:</div>
		<input type="hidden" id="char_count_limit" value="<?=$char_count_limit?>" />
		<input type="hidden" id="seo_field" value="<?=str_replace('-','_',IDE)?>" />
<?
		foreach ($filters as $filter) {
?>			<div style="float:left; margin-right:40px;">
				<div class="filter" type="<?=$type?>" style="font-weight:bold; width: 175px; padding-left:5px; cursor:pointer; border: 1px solid #999; border-bottom: 2px solid #999;" filter="<?=$filter?>"><?=str_replace('_',' ',$filter)?><span id="<?=$filter?>_selected" style="text-transform:lowercase"></span></div>
				<div id="<?=$filter?>" style="position:absolute; display:none; min-width:180px; background-color: #fff; border-bottom: 1px solid #999; border-left: 1px solid #999; border-right: 1px solid #999;" class="filter-area"><? include('pages/admin/seo/duplicate-data/filter.php') ?></div>
			</div>
<?	
		}
?>
		<div style="float:left"><input type="color" id="bgcolor" value="#FFFFFF"></div>
		<div class="clear"></div>
	</div>
	
	<div id="multi">
		<div id="multi-listing">
<?
			include ('pages/admin/seo/duplicate-data/multi-listing.php');
?>
		</div>
    </div>
<?
	$p->template('seo','bottom');	
?>