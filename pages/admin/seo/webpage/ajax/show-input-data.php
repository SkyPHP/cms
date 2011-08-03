<?
	if ($_POST['url_specific']) $rs = aql::select("website_uri_data { value where website_id = {$_POST['website_id']} and uri = '{$_POST['uri']}' and field = '{$_POST['field']}' }");
	else $rs = aql::select("website_page_data { value where field = '{$_POST['field']}' and website_page_id = {$_POST['website_page_id']} }");
	aql::update("website_uri_data",array('on_website'=>$_POST['url_specific']),$rs[0]['']);
	exit ($rs[0]['value']);
?>