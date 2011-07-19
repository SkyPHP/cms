<?
	global $seo_field_array;
	
	elapsed('before website table query');
	if (!$website_id) {
		$rs = sql("SELECT id FROM website where domain = '".$_SERVER['SERVER_NAME']);
		$website_id = $rs->Fields('id');
	}
	elapsed('after website table query');
	if (is_array($seo_field_array)) {
		
		$mem_key = "seo:".$website_id.":".$p->page_path;
		$page_data = mem($mem_key);
		if (!$page_data) {
			$rs = aql::select("website_page { url_specific where page_path = '".$p->page_path."' and website_id = ".$website_id."}");
			$pd = aql::select("website_page_data { field, value where website_page_id = {$rs[0]['website_page_id']} } ");
			foreach ($pd as $p) {
				$page_data[$p['field']] = $p['value'];	
			}
			if ($rs[0]['url_specific'] == 1) $page_data['url_specific']=true;
			mem($mem_key, $page_data);
		}
		
		foreach ($page_data as $field => $value)  {
			$p->var[$field] = $value;	
		}
		
		if ($page_data['url_specific']) {
			$mem_key = "seo:".$website_id.":".$_SERVER['PATH_INFO'];
			$uri_data = mem($mem_key);
			if (!$uri_data) {
				$ud = aql::select("website_uri_data { field, value where website_id = ".$website_id." and uri = '".$_SERVER['PATH_INFO']." }");
				foreach($ud as $u) {
					$uri_data[$u['field']] = $u['value'];
				}
				mem($mem_key, $uri_data);
			}
			
			foreach($uri_data as $field => $value) {
				$p->var[$field]=$value;	
			}
		}
		
		foreach($seo_field_array as $type => $ar) {
			foreach ($ar as $field => $max) {
				 $val = $p->var[$field];
				 eval('$p->var["'.$field.'"] = stripslashes("'.addslashes($val).'");'); 
			}			
		}
		
	}
?>