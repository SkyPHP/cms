<?
	$update = aql::update('website_page',array($_POST['field']=>$_POST['value']),$_POST['website_page_ide']);
	if ($update == true) {
?>
		<a title="Change <?=ucwords(str_replace('_',' ',$_POST['field']))?>" id="<?=$_POST['field']?>_change" page_ide="<?=$_POST['website_page_ide']?>" field="<?=$_POST['field']?>" style="cursor:pointer"><?=$_POST['value']?></a>
<?		
	}
?>