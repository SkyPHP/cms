<?
	$_POST['page_path']="pages/admin/seo/webpage/webpage.php";
	$p->title="SEO";
	$p->css[]="/pages/admin/seo/webpage/webpage.css";
	$p->template('skybox','top');
	$rs = aql::select("website { where domain = '{$_SERVER['SERVER_NAME']}' }");
	$website_id = $rs[0]['website_id'];
	$aql="website_page { where page_path = '{$_POST['page_path']}' and website_id = {$website_id} }";
	$rs = aql::select($aql);
	$page = $rs[0];
	if (is_numeric($page['website_page_id'])) {
		include('pages/admin/seo/webpage/webpage_form.php');		
	}
	else {
		$rs = aql::select("website { where  domain = '{$_SERVER['SERVER_NAME']}' }");
        	if (is_numeric($rs[0]['website_id'])) {
            	$data = array(
                	'page_path'=>$page_path,
                    'website_id'=>$rs->Fields('id'),
                    'start_mmdd'=>date('md')
                );
                    $insert = aql::insert('website_page',$data);
                    $page_id = $insert[0]['website_page_id'];
                }
          
		if (is_numeric($page_id)) {
			include('pages/admin/seo/webpage/webpage_form.php');
		}
		else exit($_SERVER['SERVER_NAME']." must be added to the website table");
	}	
	$p->template('skybox','bottom');
?>