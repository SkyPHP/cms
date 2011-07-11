<?
	$page = aql::profile('website_page',$_POST['website_page_ide']);
?>
<input id="edit" class="field_edit" type="text" field="<?=$_POST['field']?>" page_ide="<?=$page['website_page_ide']?>" value="<?=$page[$_POST['field']]?>" />