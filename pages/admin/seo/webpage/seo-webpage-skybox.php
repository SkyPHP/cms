<? 		

	if ($_POST['page_path']) { 
		$title="SEO - ".$_POST['page_path'];
		$p->title = $_SERVER['HTTP_HOST'];
		$p->template('skybox','top');
		$uri = $_POST['uri'];
		$p->js[] = '/lib/js/jquery.autoresize.js';
		$p->js[] = '/pages/admin/seo/webpage/seo-webpage-skybox.js';
		

		if ($_POST['website_ide']) 
			$website_id = decrypt($_POST['website_ide'],'website');
		else {
			$rs = aql::select("website { where domain = '{$_SERVER['SERVER_NAME']}' }");
			$website_id = $rs[0]->website_id;
		}

		//echo $website_id;

		// Still don't have a website_id after checking config and website table so display a question to add it
		if (!$website_id) { 
?>					
			<h2><?=$_SERVER['SERVER_NAME']?></h2>
            <br>
            This Website Cannot Be Optimized Until it is Set Up in the System
            <br><br>
            Would You Like to Set it Up Now?&nbsp;&nbsp;&nbsp;&nbsp;<button id="set-up-website">Yes</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id="close">No</button>
			
<?		// The website exists... move forward to the check if website_page record is entered	
		} else {
			$aql="website_page { url_specific, page_type, page_path, nickname where page_path = '".addslashes($_POST['page_path'])."' and website_id = {$website_id} }";
			$rs = aql::select($aql);
			$page = $rs[0];
			if ($page->website_page_id) {
				
				if ($page->url_specific) {
					$rs_uri = aql::select("website_uri_data { where website_id = ".$website_id." and uri = '".$uri."' and on_website = 1 }");
					if ($rs_uri) $url_specific_flag = true;
					else $url_specific_flag = false;
				}
				
				// We have a website_page_record so load the form ?>
				<div style="margin-bottom:10px;">
				<input type="checkbox" id="url_specific" website_id="<?=$website_id?>" website_page_id="<?=$page->website_page_id?>" uri="<?=$uri?>" style="margin-left:10px;" <?=$url_specific_flag?'checked="checked"':''?> /> <label for="url_specific">URL Specific</label>
				</div>
				<div id="url_cb" style="margin-bottom:10px;">
<?				
				if ($url_specific_flag) { 
					$uri_enabled = true;
?>
					<input type="hidden" id="uri_enabled" value="<?=$uri?>" /> 
<?				
				} 
?>
				</div>
				<div id="seo_page">
<?
					include('pages/admin/seo/webpage/seo-webpage-form.php');
?>
				</div>
<?
			}
			else {
				echo ("No Record Found");
				// We don't have the page record so we have to make it
				$data = array(
					'page_path'=>$_POST['page_path'],
					'website_id'=>$website_id,
					'start_mmdd'=>date('md'),
					'url'=>$_POST['url']
				);
				$insert = aql::insert('website_page',$data);
				$page->website_page_id = $insert[0]['website_page_id'];
				
				
				// Check if the record was entered correctly and display the form 
				if ($page->website_page_id) {
?>
					<div style="margin-bottom:10px;">
						<input type="checkbox" id="url_specific" website_id="<?=$website_id?>" website_page_id="<?=$page->website_page_id?>" uri="<?=$uri?>" style="margin-left:10px;" /> <label for="url_specific">URL Specific</label>
					</div>
					<div id="url_cb" style="margin-bottom:10px;"></div>
					<div id="seo_page">
<?
						include('pages/admin/seo/webpage/seo-webpage-form.php');
?>
					</div>
<?
				}
				else exit("There Was An Error Entering The Website Page Record.");
			}
		}
		$p->template('skybox','bottom');
}