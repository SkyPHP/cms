<?
	if ($_POST['type']=='code')
		$data = array(
			'value' => $_POST['value']
		);
	else 
		$data = array(
			'draft' => $_POST['value']
		);
	$update = aql::update("website_page_data",$data,$_POST['website_page_data_id']);
	if ($update) {
?>
<a field="<?=$_POST['field']?>" type="draft" wpd_id="<?=$_POST['website_page_data_id']?>" style="cursor:pointer" class="compare_edit"><?=$_POST['value']?$_POST['value']:'N/A'?></a>
<?	
	}
?>