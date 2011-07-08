<?
	$data = array(
		$_POST['field'] => $_POST['value'],
		'mod__person_id' => PERSON_ID
	);
	$update = aql::update('website_page',$data,$_POST['website_page_ide']);
	if ($update == true && $_POST['field'] == 'page_type') exit('success');
	else if ($update == true && $_POST['field'] == 'nickname') {
?>
		<a id="name_change" title="Click to Change <?=ucwords(str_replace('_','',$_POST['field']))?>"><?=$_POST['value']?></a>
<?	
	}
	else print_a($update);
?>