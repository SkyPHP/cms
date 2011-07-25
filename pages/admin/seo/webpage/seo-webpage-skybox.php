<? 
	if ($_POST['page_path']) { 
		
		$p->title="SEO - ".$_POST['page_path'];
		$p->template('skybox','top');
		$rs = aql::select("website { where domain = '{$_SERVER['SERVER_NAME']}' }");
		$website_id = $rs[0]['website_id'];
		
		// Check if the webiste exists in the database and prompt a question to add it if not
		if (!$website_id) { 
		
?>		
			<h2><?=$_SERVER['SERVER_NAME']?></h2>
            <br>
            This Website Cannot Be Optimized Until it is Set Up in the System
            <br><br>
            Would You Like to Set it Up Now?&nbsp;&nbsp;&nbsp;&nbsp;<button id="set-up-website">Yes</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id="close">No</button>
			
<?		// The website exists... move forward to the check if website_page record is entered	
		} else {
	
			$aql="website_page { nickname where page_path = '{$_POST['page_path']}' and website_id = {$website_id} }";
			$rs = aql::select($aql);
			$page = $rs[0];
			if (is_numeric($page['website_page_id']) && $page['website_page_id'] != 0) {
				// We have a website_page_record so load the form 
				include('pages/admin/seo/webpage/seo-webpage-form.php');		
			}
			else {
				// We don't have the page record so we have to make it
				$rs = aql::select("website { where  domain = '{$_SERVER['SERVER_NAME']}' }");
				$data = array(
					'page_path'=>$_POST['page_path'],
					'website_id'=>$rs[0]['website_id'],
					'start_mmdd'=>date('md'),
					'url'=>$_POST['url']
				);
				$insert = aql::insert('website_page',$data);
				$page['website_page_id'] = $insert[0]['website_page_id'];
				
				
				// Make Sure The Record Was Entered  
				if (is_numeric($page['website_page_id']) && $page['website_page_id'] != 0) {
					include('pages/admin/seo/webpage/seo-webpage-form.php'); 
				}
				else exit("There Was An Error Entering The Website Page Record.");
			}
		}
		$p->template('skybox','bottom');
	
?>

<script language="javascript">
	$(function() {
		
		$('.seo-input').each(function(index, element) {
           	f = $(this).attr('field')
			var max_length = $(this).attr('max')
			var length = $(this).val().length
			$('#'+f+'_char_count').html(length)
			if (length > max_length) $('#'+f+'_counter').css('color','#F00')
			else $('#'+f+'_counter').css('color','#000')
			$('#'+f+'_char_count').html(length)
        });
		
		
		$('.seo-input').live('keyup focusout', function(e) {
			f = $(this).attr('field')
			var max_length = $(this).attr('max')
			var length = $(this).val().length
			$('#'+f+'_char_count').html(length)
			if (length > max_length) $('#'+f+'_counter').css('color','#F00')
			else $('#'+f+'_counter').css('color','#000')
			
			if (e.keyCode == 13 || e.type == 'focusout') {
				
				v = $(this).val()
				w = $(this).attr('wp_id')
                s = $(this).attr('saved_id')
				$('#'+s).html('saving')
				$('#'+s).fadeOut('slow',function() {
					$.post('/admin/seo/webpage/ajax/save-seo', { field: f, value: v, wp_id: w }, function (data){
						$('#'+s).html(data)
						$('#'+s).fadeIn('slow')
					})				
				})
			}
		})
		
		$('#set-up-website').live('click',function(){
			$.post('/admin/seo/website/set-up', function (data) {
				$('#skybox').html(data)
			})
		})
				
		$('#nickname_change').live('click', function() {
			$('#nickname').fadeOut()
			page_ide = $(this).attr('page_ide')
			$.post('/admin/seo/webpage/ajax/input', { field: 'nickname', website_page_ide: page_ide }, function(data) {
				$('#nickname').html(data)
				$('#nickname').fadeIn(800)
			})
		})
		
		$('#opt_phrase_change').live('click', function() {
			$('#opt_phrase').fadeOut()
			page_ide = $(this).attr('page_ide')
			$.post('/admin/seo/webpage/ajax/input', { field: 'opt_phrase', website_page_ide: page_ide }, function(data) {
				$('#opt_phrase').html(data)
				$('#opt_phrase').fadeIn(800)
			})
		})
		
		$('#input_field').live('focusout keyup',function(e) {
			if (e.keyCode == 13 || e.type == 'focusout') {
				f = $(this).attr('field')
				$('#'+f).fadeOut()
				val = $(this).val()
				page_ide = $(this).attr('page_ide')
				$.post('/admin/seo/webpage/ajax/change_field', { value: val, field: f, website_page_ide: page_ide }, function(data) {
					$('#'+f).html(data)
					$('#'+f).fadeIn(800)
				})	
			}
		})
		
	})
</script>

<? 	} else { // maintain height and width of the skybox
?>
		<div style="width:800px; height:600px;">Something went wrong with the post of the page_path.</div>
<?	
	}
?>