<? 
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
?>