<?
	$data = array(
		'draft' => $_POST['draft'],
		'mod__person_id' => PERSON_ID,
		'update_time'=>'now()'
	);
	$rs = aql::select("website_page_data { where field = '{$_POST['field']}' and website_page_ide = '{$_POST['website_page_ide']}' }");
	if (is_numeric($rs[0]['website_page_data_id'])) {
		$update = aql::update('website_page_data',$data,$rs[0]['website_page_data_ide']);	
		if ($update == true) echo "saved";
		else echo "error";
	}
	else {
		$data['website_page_id'] = decrypt($_POST['website_page_ide'],'website_page');
		$data['field'] = $_POST['field'];
		$insert = aql::insert('website_page_data',$data);
		if (is_numeric($insert[0]['website_page_data_id'])) echo "saved";
	}
?>