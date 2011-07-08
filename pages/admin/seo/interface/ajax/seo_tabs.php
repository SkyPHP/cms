<input type="hidden" id="page_ide" value="<?=$_POST['website_page_ide']?>" />
	<div class="tab_on"><a field="all" class="tab_click">All</a></div>
<? 
		foreach($seo_field_array[$_POST['value']] as $field => $max) {
?>
			<div class="tab"><a field="<?=$field?>" class="tab_click"><?=ucwords(str_replace('_',' ',str_replace('meta_','',str_replace('og:','',$field))))?></a></div>
<?
		}
?>
	<div class="clear"></div>