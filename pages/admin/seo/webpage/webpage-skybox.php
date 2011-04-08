<? 
	if ($_POST['page_path']) { 
		$p->title="SEO";
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
						'page_path'=>$_POST['page_path'],
						'website_id'=>$rs[0]['website_id'],
						'start_mmdd'=>date('md')
					);
						if ($_POST['page_path']) {
							$insert = aql::insert('website_page',$data);
							$page['website_page_id'] = $insert[0]['website_page_id'];
						}
					}
			  
			if (is_numeric($page['website_page_id'])) {
				include('pages/admin/seo/webpage/webpage_form.php');
			}
			else exit($_SERVER['SERVER_NAME']." must be added to the website table");
		}	
		$p->template('skybox','bottom');
	
?>

<script language="javascript">
	$(function() {
		
		$('.seo-input').live('keyup', function(e) {
			if (e.keyCode ==13) {
				var f = $(this).attr('field')
				var v = $(this).val()
				if (f == 'h1' || f == 'paragraph') {
					$.post('/admin/seo/webpage/ajax/save-seo', { field: f, value: v, website_page_ide:'<?=$page['website_page_ide']?>' }, function (data){
						if (data == 'success') {
							$("#"+f).html(v)
							alert('success')
						}
					})	
				}
			}
		})
	})
</script>

<? 	} else {
?>
		<div style="width:700px; height:600px;">&nbsp;</div>
<?	
	}
?>