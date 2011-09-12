<? 
	$update = aql::update('website_page',array('url_specific'=>$_POST['val']),$_POST['website_page_id']); 
	if ($update === true) { 
		if ($_POST['val'] == 1) { 
			$update = sql("update website_uri_data set on_website = 1 where uri = '".$_SERVER['HTTP_HOST'].$_POST['uri']."'");			
			
?>
			This page is set to URL SPECIFIC. The URL is <?=$_SERVER['HTTP_HOST'].$_POST['uri']?>
			<input type="hidden" id="uri_enabled" value="<?=$_POST['uri']?>" />
<?		
		} 
		else $update = sql("update website_uri_data set on_website = 0 where uri = '".$_SERVER['HTTP_HOST'].$_POST['uri']."'");
	}
?>