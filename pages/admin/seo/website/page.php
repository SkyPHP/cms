<div style="padding:10px;">
<?	
	if ($_POST['page_path']) {
		$rs = aql::select("website { where domain = '{$_SERVER['SERVER_NAME']}' }");
		$website_id = $rs[0]['website_id'];
		if (!$website_id) { 
		
?>		
			<h2><?=$_SERVER['SERVER_NAME']?></h2>
            <br>
            This Website Cannot Be Optimized Until it is Set Up in the System
            <br><br>
            Would You Like to Set it Up Now?&nbsp;&nbsp;&nbsp;&nbsp;<button id="set-up-website">Yes</button>&nbsp;&nbsp;&nbsp;&nbsp;<button id="close">No</button>
<?			
		}
		else {
			$aql="website_page { nickname where page_path = '{$_POST['page_path']}' and website_id = {$website_id} }";
			$rs = aql::select($aql);
			$page = $rs[0];
			if (!is_numeric($page['website_page_id'])) {
				
				$rs = aql::select("website { where  domain = '{$_SERVER['SERVER_NAME']}' }");
				if (is_numeric($rs[0]['website_id'])) {
					$data = array(
						'page_path'=>$_POST['page_path'],
						'website_id'=>$rs[0]['website_id'],
						'start_mmdd'=>date('md')
					);
					$insert = aql::insert('website_page',$data);
					$page['website_page_id'] = $insert[0]['website_page_id'];
					
				} else exit('you did something wrong');
			}
			template::inc('global','top');
			
			if (is_numeric($page['website_page_id'])) {				
				$page['website_page_ide'] = encrypt($page['website_page_id'],'website_page');
				?>
            
                <h3><?=$_POST['page_path']?></h3>
                <div id="nickname" style="margin-bottom:10px;"><a title="Change Nickname" page_ide="<?=$page['website_page_ide']?>" style="cursor:pointer; color:#2589B4" id="nickname_change"><?=$page['nickname']?$page['nickname']:'Name This Page'?></a></div>
                <input type="hidden" name="website_page_id" id="website_page_id" value="<?=$page['website_page_id']?>" />
        <?
                $rs = aql::select("website_page_data { field, value } website_page { } website { where domain = '{$_SERVER['SERVER_NAME']}' and page_path = '{$_POST['page_path']}' }");
                if (is_array($rs)) {
                    foreach($rs as $r) {
                        $fields[$r['field']]=$r['value'];		
                    }
                }				
				
                if (is_array($seo_field_array)) {					
					
                    foreach($seo_field_array as $type => $field_array) {
						
						foreach($field_array as $field => $max) {
							$rs2 = aql::select("website_page_data { value where field = '{$field}' and website_page_id = {$page['website_page_id']} }");
							if (!is_array($rs2)) {
								$data = array(
									'field'=>$field,
									'website_page_id'=>$page['website_page_id'],
									'mod__person_id'=>PERSON_ID
								);
								aql::insert('website_page_data',$data);
							}
						}
                    
                        if (!isset($header)) {
    ?>
                            <fieldset style="width:520px; background:#eeeee2; margin-bottom:20px; border: 1px solid #ccc; padding:10px;">
                                <legend style="border: 1px solid #ccc; background:#ffffff; font-weight:bold; padding:2px 5px 2px 5px;"><?=strtoupper(str_replace('_',' ',$type))?></legend>
    <?
                            $header = $type;
                        }
                        else if ($header != $type) {
    ?>
                                </fieldset>
                                <fieldset style="width:520px; background:#eeeee2; margin-bottom:20px; border: 1px solid #ccc; padding:10px;">
                                    <legend style="border: 1px solid #ccc; background:#ffffff; font-weight:bold; padding:2px 5px 2px 5px;"><?=strtoupper(str_replace('_',' ',$type))?></legend>
    <?				
                            $header = $type;	
                            
                        }
                        foreach($field_array as $field => $max) {
                            $y++;
    ?>			
                            <div style="padding:10px;">
                                <label style="font-weight:bold; font-size:14px" for="<?=$field?>"><?=ucwords(str_replace(':',' ',str_replace('_',' ',$field)))?></label>
                                <span style="font-size:10px; color:#060; margin-left:10px;" id="saved_<?=$y?>"></span><br>
    <?
                                $width = 500;
                                if ($field == 'paragraph' || $field == 'meta_description' || $field =='meta_keywords')  {
            ?>	
                                    <textarea max="<?=$max?>" saved_id="saved_<?=$y?>" wp_id="<?=$page['website_page_id']?>" style="width:500px; height:150px;" class="seo-input" field="<?=$field?>"><?=$fields[$field]?></textarea>
            <?
                                } else {
            ?>					
                                    <input type="text" saved_id="saved_<?=$y?>" wp_id="<?=$page['website_page_id']?>" class="seo-input" max="<?=$max?>" field="<?=$field?>" value="<?=htmlspecialchars($fields[$field])?>" style="width:500px;" />			
            <?                    
                                }
            ?>				 
                                <div id="<?=$field?>_counter" style="font-size:10px; text-align:right; width:<?=$width?>px">Characters <span id="<?=$field?>_char_count"></span> / <?=$max?></div>
                            </div>
            <?				
                        }
                    }
            ?>
                    </fieldset>
            <?
                }
			}
			template::inc('global','bottom');
		}
?>
<script type="text/javascript">
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
			v = $(this).val()
			$('#'+s).html('saving')
			$('#'+s).fadeOut('slow',function() {
				$.post('/admin/seo/webpage/ajax/save-seo', { field: f, value: v, wp_id: w }, function (data){
					$('#'+s).html(data)
					$('#'+s).fadeIn('slow')					
				})
			})
		}
	})
	
	$('#nickname_change').live('click', function() {
		$('#nickname').fadeOut()
		page_ide = $(this).attr('page_ide')
		$.post('/admin/seo/webpage/ajax/input', { field: 'nickname', website_page_ide: page_ide }, function(data) {
			$('#nickname').html(data)
			$('#nickname').fadeIn(800)
		})
	})
	
	$('#input_field').live('focusout keyup',function(e) {
		if (e.keyCode == 13 || e.type == 'focusout') {
			$('#nickname').fadeOut()
			field = $(this).attr('field')
			val = $(this).val()
			page_ide = $(this).attr('page_ide')
			$.post('/admin/seo/webpage/ajax/change_field', { value: val, field: field, website_page_ide: page_ide }, function(data) {
				$('#nickname').html(data)
				$('#nickname').fadeIn(800)
			})	
		}
	})
	
	$('#set-up-website').live('click',function(){
		$.post('/admin/seo/website/set-up', function (data) {
			window.reload()
		})
	})

})
</script>
<?
	} // if ($_POST['page_path'])
	else {
		echo "Select a page from the directory list to the left to get started.";
	}
?>
</div>