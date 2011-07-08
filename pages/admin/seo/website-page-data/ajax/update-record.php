<?
	$field = $_POST['field'];
	$value = $_POST['value'];
	if (!$value) $value='';
	$data[$field] = $value;	 
	aql::update('website_page_data',$data,$_POST['sky_ide']);
?>
<a class="changeable" ide="<?=$_POST['sky_ide']?>" field="<?=$_POST['field']?>"><?=$_POST['value']?$_POST['value']:'_________________'?></a>