<? 
	$rs = aql::select("website_page { page_path, website_id where website_page.id = {$_POST['wp_id']} }");

	$website_page_fields = array('nickname', 'page_type');
	if( in_array($_POST['field'],$website_page_fields) ) {
		$data = array(
			$_POST['field'] => $_POST['value']
		);
		$update = aql::update('website_page',$data,$_POST['wp_id']);
		
		//update all data record with this field
		if($_POST['field'] == 'page_type') {
			$page_data = aql::select('website_page_data{ where website_page_id = '.$rs[0]->website_page_id.' }');
			if($page_data) {
				foreach ($page_data as $p_data) {
					$update = aql::update('website_page_data',$data,$p_data->website_page_data_id);
				}
			}
			$uri_data = aql::select('website_uri_data{ where website_page_id = '.$rs[0]->website_page_id.' }');
			if($uri_data) {
				foreach ($uri_data as $u_data) {
					$update = aql::update('website_page_data',$data,$u_data->website_uri_data_id);
				}
			}
		}
		if($update) exit('saved');
	
	} elseif (!$_POST['uri_enabled']) {
		$rs = aql::select("website_page_data { where website_page_id = {$_POST['wp_id']} and field = '{$_POST['field']}' }");
		$data = array(
			'field' => $_POST['field'],
			'value' => $_POST['value'],
			'website_id' => $_POST['website_id'],
			'website_page_id' => $_POST['wp_id'],
			'mod__person_id' => PERSON_ID,
			'update_time' => 'now()'
		);	
		if (is_numeric($rs[0]->website_page_data_id)) {
			$update=aql::update('website_page_data',$data,$rs[0]->website_page_data_id);
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
			'website_page_id' => $_POST['wp_id'],
			'mod__person_id' => PERSON_ID,
			'update_time' => 'now()'
		);	
		if (is_numeric($rs[0]->website_uri_data_id)) {
			$update=aql::update('website_uri_data',$data,$rs[0]->website_uri_data_id);
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