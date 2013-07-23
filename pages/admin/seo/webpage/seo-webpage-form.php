

<link rel="stylesheet" href="/admin/seo/webpage/seo-webpage-skybox.css">
<div style="width:900px">
<? 

	global $website_id, $seo_field_array;

	if ($_POST['website_ide']) $website_id = decrypt($_POST['website_ide'],'website');

	if (!$website_id) $website_id=$_POST['website_id'];
	if (!$website_id) {
		$rs = aql::select("website { where domain = '{$_SERVER['HTTP_HOST']}' }");	
		$website_id = $rs[0]->website_id;
	}
	if (!$page->website_page_id) {
		$page->website_page_id = $_POST['website_page_id'];
		$aql="website_page { url_specific, page_type, page_path, nickname where website_page.id = ".$page->website_page_id." }";
		$rs = aql::select($aql);
		$page = $rs[0];
	}
	$uri = $_POST['uri'];

	if ($_POST['uri_enabled'] == 1 || $url_specific_flag) $uri_enabled = true;
	else $uri_enabled=false;

 	if (is_numeric($page->website_page_id)) {
		$page->website_page_ide = encrypt($page->website_page_id,'website_page');
		$rs = aql::select("website_page_data { field, value where website_page_id = {$page->website_page_id} }");
		if ($rs) foreach($rs as $r) {
			 $fields[$r->field]=$r->value;
		}
		if ($uri_enabled) {
			$rs2 = aql::select("website_uri_data { field, value, on_website where website_id = {$website_id} and uri = '{$uri}' and website_page_id = {$page->website_page_id} }");
			if ($rs2) foreach($rs2 as $r) {
				$fields2[$r->field] = $r->value;
			}
		}


		$uri_data_id = aql::select("website_uri_data{ uri where field = 'title' and website_page_id = ".$page->website_page_id." and uri = '".$uri."' } ");
		foreach($uri_data_id as $key =>  $uri_data) {
			if ($key != 0) {
				$uri_data_id_str = $uri_data_id_str.', ';
				$uri_data_uri_str = $uri_data_uri_str.', ';
			}
			$uri_data_id_str = $uri_data_id_str.$uri_data->website_uri_data_id;
			$uri_data_uri_str = $uri_data_uri_str.$uri_data->uri;
		}
		$page_data_id = aql::select("website_page_data{ where field = 'title' and website_page_id = ".$page->website_page_id."}");
		foreach($page_data_id as $key => $page_data) {
			if ($key != 0) $page_data_id_str = $page_data_id_str.', ';
			$page_data_id_str = $page_data_id_str.$page_data->website_page_data_id;
		}
		
		if(!$website_id) $website_id = $_POST['website_id'];	
	
		if (is_array($seo_field_array)) {
			if($uri_data_id_str || $uri_data_uri_str) {
?>
            <table class="ids">
                <tr>
                    <td>website_uri_data.id = <?=$uri_data_id_str?></td>
                    <td>url = <?=$uri_data_uri_str?></td>
                </tr>
            </table>
<?
			}
?>
			<table class="ids">
                <tr>
                    <td>website_id = <?=$website_id?$website_id:$_POST['website_id']?></td>
                    <td>website_page_id = <?=$page->website_page_id?$page->website_page_id:$_POST['website_page_id']?></td>
					<td>website_page_data.id (title) = <?=$page_data_id_str?></td>
                </tr>
            </table>
            
            <table class="ids">
                <tr>
                    <td>Nickname = <input type="text" saved_id="saved_0" max="100" id="field_nickname" class="seo-input" field="nickname" wp_id="<?=$page->website_page_id?$page->website_page_id:$_POST['website_page_id']?>" value="<?=$page->nickname?>" /><span style="font-size: 10px; color: rgb(0, 102, 0); margin-left: 4px; display: inline; " id="saved_0"></span></td>
                    <td>page_type = <input type="text" saved_id="saved_1" max="100" id="field_page_type" class="seo-input" field="page_type" wp_id="<?=$page->website_page_id?$page->website_page_id:$_POST['website_page_id']?>" value="<?=$page->page_type?>" /><span style="font-size: 10px; color: rgb(0, 102, 0); margin-left: 4px; display: inline; " id="saved_1"></span></td>
                    <td>page_path = <?=$page->page_path?></td>
                </tr>
            </table>
	<?
			// Insert the blank field records in the db for website_page fields that don't already exist
			$y=2;	
			foreach($seo_field_array as $type => $field_array) {
				
				foreach($field_array as $field => $max) {
					$rs2 = aql::select("website_page_data { id where field = '{$field}' and website_page_id = {$page->website_page_id} }");
					if (!$rs2) {
						$data = array(
							'field'=>$field,
							'website_id' => $website_id,
							'website_page_id'=>$page->website_page_id,
							'mod__person_id'=>PERSON_ID
						);
						aql::insert('website_page_data',$data);
					}
				}
			
				
				if (!isset($header)) {
	?>
					<fieldset style="width:872px; background:#f3f3f3; margin-bottom:4px; border: 1px solid #ccc; padding:5px;">
						<legend style="border: 1px solid #ccc; background:#ffffff; font-weight:bold; padding:2px 5px 2px 5px;"><?=strtoupper(str_replace('_',' ',$type))?></legend>
	<?
					$header = $type;
				}
				else if ($header != $type) {
	?>
						</fieldset>
						<fieldset style="width:872px; background:#f3f3f3; margin-bottom:2
                        
                        
                        0px; border: 1px solid #ccc; padding:5px;">
							<legend style="border: 1px solid #ccc; background:#ffffff; font-weight:bold; padding:2px 5px 2px 5px;"><?=strtoupper(str_replace('_',' ',$type))?></legend>
	<?				
					$header = $type;	
					
				}
				$x = 0;
				
				foreach($field_array as $field => $char_max) {
					$x++;
					$y++;
					
	?>			
					<div style="float:left; padding:4px 10px;">
                    	<? /*<span class="uri_field_cb" <?=!$uri_enabled?'style="display:none;"':''?>><input class="url_cb_click" field="<?=$field?>" type="checkbox" id="uri_cb_<?=$field?>" style="margin-bottom:2px;" <?=($fields2[$field] && $on_website[$field])?'checked="checked"':'' ?> /> URL Specific</span> */ ?> <label style="font-weight:bold; font-size:14px" for="<?=$field?>"><?=ucwords(str_replace('_',' ',$field))?></label>
						<span style="font-size:10px;	color:#060;	margin-left:10px;" id="saved_<?=$y?>"></span><br>
	<?
						global $seo_textarea;
						if (in_array($field, $seo_textarea) )  {
	?>	
							<textarea uri_enabled="<?=$uri_enabled?1:0?>" id="field_<?=$field?>" style="width:850px; " max="<?=$char_max?>" class="seo-input" wp_id="<?=$page->website_page_id?>" saved_id="saved_<?=$y?>" field="<?=$field?>"><?=$uri_enabled?htmlspecialchars($fields2[$field]):htmlspecialchars($fields[$field])?></textarea>
	<?
						} else {
	?>					
							<input uri_enabled="<?=$uri_enabled?1:0?>" id="field_<?=$field?>" type="text" class="seo-input" max="<?=$char_max?>" field="<?=$field?>" wp_id="<?=$page->website_page_id?>" saved_id="saved_<?=$y?>" value="<?=$uri_enabled?htmlspecialchars($fields2[$field]):htmlspecialchars($fields[$field])?>" style="width:850px;" />
	<?                    
						}
	?>					
    					<div id="<?=$field?>_counter" style="font-size:10px; text-align:right; width:<?=$width?>px">Characters <span id="<?=$field?>_char_count"></span> / <?=$char_max?></div>
					</div>
	<?				
					if ($x==3) {
						$x=0;
	?>
						<div class="clear"></div>
	<?
					}
	
				}
			}
		}
	?>
	</fieldset>
	<div style="clear:both;"></div>
	</div>
<? 
}
?>