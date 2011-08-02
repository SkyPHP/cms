<?
	$page_data = NULL;
	global $seo_field_array;
	if (!$website_id) {
		$rs = sql("SELECT id FROM website where domain = '".$_SERVER['SERVER_NAME']."'");
		$website_id = $rs->Fields('id');
	}

	if ($website_id) {
		$mem_key = "seo:".$website_id.":".$p->page_path;
		mem($mem_key,'');
		$page_data = mem($mem_key);
		if (!$page_data) {
			$rs = aql::select("website_page { url_specific where page_path = '".$p->page_path."' and website_id = ".$website_id."}");
			
			if (is_numeric($rs[0]['website_page_id'])) {
				$pd = aql::select("website_page_data { field, value where website_page_id = {$rs[0]['website_page_id']} } ");
				if (is_array($pd)) {			
					foreach ($pd as $data) {
						$page_data[$data['field']] = $data['value'];	
					}
					if ($rs[0]['url_specific'] == 1) $page_data['url_specific']=true;
					mem($mem_key, $page_data);
				}
			}
		}
		
		if (is_array($page_data)) {
			foreach ($page_data as $field => $value)  {
				if ($field == 'title') eval('$p->title = stripslashes("'.addslashes($value).'");');
				else eval('$p->seo[$field] = stripslashes("'.addslashes($value).'");');
			}
		
			if ($page_data['url_specific']) {
				$mem_key = "seo:".$website_id.":".$_SERVER['PATH_INFO'];
				$uri_data = mem($mem_key);
				if (!$uri_data) {
					$ud = aql::select("website_uri_data { field, value where website_id = ".$website_id." and uri = '".$_SERVER['PATH_INFO']."' }");
					
					if (is_array($ud)) {
						foreach($ud as $u) {
							$uri_data[$u['field']] = $u['value'];
						}
						mem($mem_key, $uri_data);
					}
				}
				
				if (is_array($uri_data)) foreach($uri_data as $field => $value) {
					if ($field == 'title') eval('$p->title = stripslashes("'.addslashes($value).'");');
					else eval('$p->seo[$field]=stripslashes("'.addslashes($value).'");');	
				}
			}
		}		
	}
?>