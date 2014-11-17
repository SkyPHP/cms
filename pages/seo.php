<?php
	use Crave\Model\aql;

	$page_data = NULL;
	global $seo_field_array;
	global $website_id;

    if (!$website_id) {
		if (is_array($p->vars['seo']['website']))
			$website_id = $p->vars['seo']['website']['website_id'];
		else 
			$website_id = $this->vars['website']->website_id;
	}


	if ($website_id) {

		//$mem_key = "seo:".$website_id.":".$p->page_path;
		//$page_data = mem($mem_key);

		if (!$page_data) {

			//$rs = aql::select("website_page { url_specific where page_path = '{$p->page_path}' and website_id = {$website_id} }");

			if (is_numeric($rs[0]->website_page_id)) {
				$pd = aql::select("website_page_data { field, value where website_page_id = {$rs[0]->website_page_id} } ");
				if (is_array($pd)) {			
					foreach ($pd as $data) {
						$page_data[$data->field] = $data->value;
					}
					if ($rs[0]->url_specific == 1) 
						$page_data['url_specific']=true;
					//mem($mem_key, $page_data);
				}
			}
		}
		
		if (is_array($page_data)) {
			foreach ($page_data as $field => $value)  {
				//if ($field == 'title') eval('$p->title = stripslashes("'.addslashes($value).'");');
				//else 
				eval('$p->seo[$field] = stripslashes("'.addslashes($value).'");');
			}
			//print_a($_SERVER);
			if ($page_data['url_specific']) {
				$mem_key = "seo:".$website_id.":".$p->urlpath;
				//$uri_data = mem($mem_key);
				$uri_data =  NULL;
				if (!$uri_data) {
					$ud = aql::select("website_uri_data { field, value where website_id = {$website_id} and uri = '{$p->urlpath}' and on_website = 1 and value is not null }");
					foreach($ud as $u) {
						$uri_data[$u->field] = $u->value;
					}
					//mem($mem_key, $uri_data);
				}
				
				if (is_array($uri_data)) foreach($uri_data as $field => $value) {
					//if ($field == 'title') eval('$p->title = stripslashes("'.addslashes($value).'");');
					//else 
					eval('$p->seo[$field]=stripslashes("'.addslashes($value).'");');	
				}
			}
		}		
	}


