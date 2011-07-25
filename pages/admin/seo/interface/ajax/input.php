<?
	$rs = aql::select("website_page_data { draft, value where id = {$_POST['website_page_data_id']} }");
	if ($_POST['type'] == 'draft') $value = $rs[0]['draft'];
	else $value = $rs[0]['value'];
?>
<input type="text" class="input" value="<?=$value?>" field="<?=$_POST['field']?>" field_type="<?=$_POST['type']?>" wpd_id="<?=$_POST['website_page_data_id']?>" />