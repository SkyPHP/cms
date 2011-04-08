<?
	$rs = aql::select("website_page_data { where website_page_ide = {$_POST['website_page_ide']} and field = '{$_POST['field']}' }");
		$data = array(
			'field' => $_POST['field'],
			'value' => $_POST['value'],
			'mod__person_id' => PERSON_ID,
			'update_time' => 'now()'
		);	
	if (isset($rs[0]['website_page_data_ide'])) {
		$update=aql::update('website_page_data',$data,$rs[0]['website_page_data_ide']);
		if ($update) exit('success');
		else exit($update);
	}
	else {
		$insert=aql::insert('website_page_data',$data);
		if ($insert) exit('success');
		else exit($insert);
	}	
?>