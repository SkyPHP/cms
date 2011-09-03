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
	<? include ('pages/admin/seo/duplicate-data/ajax/multi-listing.php'); ?>
</div>