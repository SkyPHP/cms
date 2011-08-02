<? 
	$rs = aql::select("website_page { page_path, website_id where website_page.id = {$_POST['wp_id']} }");
	$mem_key = "seo:".$rs[0]['website_id'].":".$rs[0]['page_path'];
	$data = mem($mem_key);
	if ($data) mem($mem_key,'');
	
	$mem_key = "seo:".$rs[0]['website_id'].":".$_POST['uri'];
	$data = mem($mem_key);
	if ($data) mem($mem_key,'');

	if (!$_POST['uri']) {
		$rs = aql::select("website_page_data { where website_page_id = {$_POST['wp_id']} and field = '{$_POST['field']}' }");
		$data = array(
			'field' => $_POST['field'],
			'value' => $_POST['value'],
			'mod__person_id' => PERSON_ID,
			'update_time' => 'now()'
		);	
		if (is_numeric($rs[0]['website_page_data_id'])) {
			$update=aql::update('website_page_data',$data,$rs[0]['website_page_data_ide']);
			if ($update) exit('saved');
			else exit($update);
		}
		else {
			$data['website_page_id']=$_POST['wp_id'];
			$insert=aql::insert('website_page_data',$data);
			if ($insert) exit('saved');
			else exit($insert);
		}	
	} else {
		$rs = aql::select("website_uri_data { where website_id = {$_POST['website_id']} and uri = '{$_POST['uri']}' and field = '{$_POST['field']}' }");
		$data = array(
			'field' => $_POST['field'],
			'value' => $_POST['value'],
			'website_id' => $_POST['website_id'],
			'mod__person_id' => PERSON_ID,
			'update_time' => 'now()'
		);	
		if (is_numeric($rs[0]['website_uri_data_id'])) {
			$update=aql::update('website_uri_data',$data,$rs[0]['website_uri_data_ide']);
			if ($update) exit('saved');
			else exit($update);
		}
		else {
			$data['uri'] = $_POST['uri'];
			$insert=aql::insert('website_uri_data',$data);
			if ($insert) exit('saved');
			else exit($insert);
		}	
	}
?>