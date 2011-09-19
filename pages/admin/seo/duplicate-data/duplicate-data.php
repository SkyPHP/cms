<?
	$p->title = 'Phrase Manager';
	
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
	
	$field_array = array(
		'Title',
		'H1',
		'Meta Title',
		'Keywords'
	);
?>	
	<div style="margin: 15px;"><a href="/admin/seo/duplicate-data/split-paragraph" >Paragraph Splitter</a> | <a href="/admin/seo/duplicate-data/phrases">Phrase Listing</a></div>
	<h1><?=$p->title?></h1>
	<div style="font-size:16px; margin-bottom: 10px;">Note: Save button will be disabled until you have selected a phrase from part 1 & part 2.</div>
	<input type="hidden" name="type" value="Title" />	
	<div style="padding-top:10px; float:left; margin-right: 20px;">
		<div class="filter" type="type" filter="type">Type<span id="type_selected"> - Title</span></div>
		<div id="type" class="filter-area">
			<div style="padding-top:5px;">
<?
				foreach ($field_array as $field) {
?>
					<div style="margin-bottom:5px;">
						<input type="radio" <?=$field=='Title'?'checked':''?> value="<?=$field?>" id="<?=str_replace(' ','_',strtolower($field))?>" name="type" style="margin-left:3px;" class="type-filter-radio" /> <label style="cursor:pointer;" for="<?=str_replace(' ','_',strtolower($field))?>"><?=$field?></label>
					</div>
<?
				}
?>
			</div>
		</div>
	</div>
	<div style="padding-top:10px; float:left">
		<div style="float:left; margin-right:15px; font-weight:bold;">Filters:</div>
		<input type="hidden" id="char_count_limit" value="<?=$char_count_limit?>" />
		<input type="hidden" id="seo_field" value="<?=str_replace('-','_',IDE)?>" />
<?
		foreach ($filters as $filter) {
?>			<div style="float:left; margin-right:40px;">
				<div class="filter" type="<?=$type?>" filter="<?=$filter?>"><?=str_replace('_',' ',$filter)?><span id="<?=$filter?>_selected" style="text-transform:lowercase"></span></div>
				<div id="<?=$filter?>" class="filter-area"><? include('pages/admin/seo/duplicate-data/filter.php') ?></div>
			</div>
<?	
		}
?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	
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