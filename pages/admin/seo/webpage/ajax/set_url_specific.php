<?
	global $seo_field_array;
	$website_id = aql::value("website_page.website_id",$_POST['website_page_id']);
	if ($_POST['val'] == 1) { 

		$data = array(
			'website_id'=>$website_id,
			'website_page_id' => $_POST['website_page_id'],
			'on_website' => 1,
			'uri' => $_POST['uri']
		);
	
		foreach ($seo_field_array as $type => $arr) {
			foreach ($arr as $field => $max) {
				$data['field'] = $field;
				$rs = aql::select("website_uri_data { id as uri_id where website_id = ".$website_id." and uri = '".$data->uri."' and field='".$field."' }");
				if (!is_numeric($rs[0]->uri_id)) {
					$data['field'] = $field; 
					aql::insert('website_uri_data',$data);
				}
				
			}
		}
	

		aql::update('website_page',array('url_specific'=>$_POST['val']),$_POST['website_page_id']);
		//sql("update website_uri_data set on_website = 1 where uri = '".$_POST['uri']."' and website_id = ".$website_id);
		$aql = "website_uri_data { where uri = '".$_POST['uri']."' and website_id = ".$website_id."}";
		$rs = aql::select($aql);
		if($rs) {
			foreach ($rs as $r) {
				$website_uri_data = array(
					'on_website' => '1'
				);
				$rs = aql::update( 'website_uri_data', $website_uri_data, $r->website_uri_data_id );
			}
		}
		
?>
		<input type="hidden" id="uri_enabled" value="<?=$_POST['uri']?>" />
<?		
	}
	else {
		//$update = sql("update website_uri_data set on_website = 0 where uri = '".$_POST['uri']."' and website_id = ".$website_id);
		$aql = "website_uri_data { where uri = '".$_POST['uri']."' and website_id = ".$website_id."}";
		$rs = aql::select($aql);
		if($rs) {
			foreach ($rs as $r) {
				$website_uri_data = array(
					'on_website' => '0'
				);
				$rs = aql::update( 'website_uri_data', $website_uri_data, $r->website_uri_data_id );
			}
		}
	}
?>