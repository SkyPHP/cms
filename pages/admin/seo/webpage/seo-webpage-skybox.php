<? 	
	if ($_POST['page_path']) { 
		
		$p->title="SEO - ".$_POST['page_path'];
		$p->template('skybox','top');

		$page = aql::profile('website_page',$_POST['website_page_ide']);
?>
<div class="wp-field">
	Page Nickname: <span id="nickname"><a title="Click to Change Nickname" id="name_change"><?=$page['nickname']?$page['nickname']:'undefined'?></a></span>
</div>
<div class="wp-field">
URL: <span id="url"><a href="<?=$page['url']?>" title="Click to Open the Page in a New Tab" target="_blank" class="external"><?=$page['url']?></a></span>
</div>
<div class="wp-field">
Page Path: <span id="path"><?=$page['page_path']?></span>
</div>
<div class="wp-field">
	<input type="hidden" name="website_group" id="website_group" value="<?=$_POST['website_group_name']?>" />
	<input type="hidden" name="page_ide" id="page_ide" value="<?=$_POST['website_page_ide']?>" />
Page Type:  
	<select name="page_type" id="page_type">
    	<option value=""> - Select a Page Type for Comparison -</option>
		<option <?=$page['page_type']=='event-listing'?'selected':''?> value="event-listing">Event Listing</option>
        <option <?=$page['page_type']=='event-profile'?'selected':''?> value="event-profile">Event Profile</option>
        <option <?=$page['page_type']=='venue-listing'?'selected':''?> value="venue-listing">Venue Listing</option>
        <option <?=$page['page_type']=='venue-profile'?'selected':''?> value="venue-profile">Venue Profile</option>
        <option <?=$page['page_type']=='other'?'selected':''?> value="other">Other</option>
    </select><span id="type_saved" style="color:#093; padding-left:10px;"></span>
</div>
<div class="wp-field">
Field List Type: <select id="field_type">
<? foreach($seo_field_array as $type => $field_array) { ?>
					<option value="html"><?=strtoupper($type)?></option>
<? } ?>
				 </select>
</div>

<div id="seo_tabs">
	<div class="tab_on"><a field="all" class="tab_click">All</a></div>
<? 
		foreach($seo_field_array['html'] as $field => $max) {
?>
			<div class="tab"><a field="<?=$field?>" class="tab_click"><?=ucwords(str_replace('_',' ',str_replace('meta_','',str_replace('og:','',$field))))?></a></div>
<?
		}
?>
	<div class="clear"></div>
</div>
<div id="seo_fields">
<?		
	$opt_phrase = aql::value("website_page.opt_phrase",$page['website_page_ide']);
?>
	<div class="seo_field">
		<fieldset>
			<legend class="legend">Opt Phrase</legend>
			<h3><?=$opt_phrase?$opt_phrase:'Not Set'?></h3>
		</fieldset>
	</div>
<?
	foreach($seo_field_array['html'] as $field => $max) {
		$rs = aql::select("website_page_data { draft, value where field = '{$field}' and website_page_ide = '{$page['website_page_ide']}' }");
?>
		<div class="seo_field">
           	<fieldset>
				<legend class="legend"><?=ucwords(str_replace('_',' ',$field))?></legend>
            	<div class="draft">
					<div>Draft  <span class="field_result" id="draft_result_<?=$field?>_<?=$page['website_page_ide']?>"></span></div>
<?
				if ($field == 'h1_blurb') {		
?>
					<textarea max="<?=$max?>" rows="5" class="area_edit" field="<?=$field?>" page_ide="<?=$page['website_page_ide']?>"><?=$rs[0]['draft']?></textarea>
<?
				}
				else {
?>
					<input max="<?=$max?>" type="text" class="draft_edit" field="<?=$field?>" value="<?=$rs[0]['draft']?>" page_ide="<?=$page['website_page_ide']?>">
<?
				}
?>

					<div id="<?=$field?>_counter" style="font-size:10px; text-align:right; width:100%">Characters <span id="<?=$field?>_char_count"></span> / <?=$max?></div>
				</div>
                <div>
               		<div>
                		Actual <? if (auth('admin:seo')) { ?> <a class="edit_actual" field="<?=str_replace(':','_',$field)?>" page_ide="<?=$page['website_page_ide']?>">Edit</a><? } ?> 
                        <span class="field_result" id="seo_field_value_result_<?=str_replace(':','_',$field)?>_<?=$page['website_page_ide']?>"></span>
                	</div>
					<textarea page_ide="<?=$page['website_page_ide']?>" field="<?=$field?>" id="<?=str_replace(':','_',$field)?>_<?=$page['website_page_ide']?>" class="seo_field_value" <? if ($field == 'h1_blurb' || $field == 'meta_description') echo 'rows="5"' ?> disabled><?=$rs[0]['value']?></textarea>
                </div>                 
			</fieldset>
       	</div>
<?
	}
?>
</div>
<script type="text/javascript">
	$(function() {
		$('.draft_edit, .area_edit').each(function() {
			f = $(this).attr('field')
			var max_length = $(this).attr('max')
			var length = $(this).val().length
			if (length > max_length) $('#'+f+'_counter').css('color','#F00')
			else $('#'+f+'_counter').css('color','#000')
			$('#'+f+'_char_count').html(length)
		});
	})
</script>

<?
		$p->template('skybox','bottom');
?>

<script language="javascript">
	$(function() {
		$('#close').live('click',function() {
			History.back();
		});
		$('.seo-input').each(function(index, element) {
           	f = $(this).attr('field');
			var max_length = $(this).attr('max');
			var length = $(this).val().length;
			$('#'+f+'_char_count').html(length);
			if (length > max_length) $('#'+f+'_counter').css('color','#F00');
			else $('#'+f+'_counter').css('color','#000');
			$('#'+f+'_char_count').html(length);
        });
		
		
		$('.seo-input').live('keyup focusout', function(e) {
			f = $(this).attr('field');
			var max_length = $(this).attr('max');
			var length = $(this).val().length;
			$('#'+f+'_char_count').html(length);
			if (length > max_length) $('#'+f+'_counter').css('color','#F00');
			else $('#'+f+'_counter').css('color','#000');
			
			if (e.keyCode == 13 || e.type == 'focusout') {
				uri = $('#url_specific').attr('uri');
				uri_enabled = $(this).attr('uri_enabled');
				v = $(this).val();
				w = $(this).attr('wp_id');
                s = $(this).attr('saved_id');
				website_id = $('#url_specific').attr('website_id');
				$('#'+s).html('saving');
				$('#'+s).fadeOut('slow',function() {
					$.post('/admin/seo/webpage/ajax/save-seo', { field: f, value: v, wp_id: w, uri: uri, uri_enabled: uri_enabled, website_id: website_id }, function (data){
						$('#'+s).html(data);
						$('#'+s).fadeIn('slow');
					});
				});
			}
		});
		
		$('#set-up-website').live('click',function(){
			$.post('/admin/seo/website/set-up', function (data) {
				$('#skybox').html(data);
			});
		});
				
		$('#nickname_change').live('click', function() {
			$('#nickname').fadeOut();
			page_ide = $(this).attr('page_ide');
			$.post('/admin/seo/webpage/ajax/input', { field: 'nickname', website_page_ide: page_ide }, function(data) {
				$('#nickname').html(data);
				$('#nickname').fadeIn(800);
			});
		});
		
		$('#opt_phrase_change').live('click', function() {
			$('#opt_phrase').fadeOut();
			page_ide = $(this).attr('page_ide');
			$.post('/admin/seo/webpage/ajax/input', { field: 'opt_phrase', website_page_ide: page_ide }, function(data) {
				$('#opt_phrase').html(data);
				$('#opt_phrase').fadeIn(800);
			});
		});
		
		$('#input_field').live('focusout keyup',function(e) {
			if (e.keyCode == 13 || e.type == 'focusout') {
				f = $(this).attr('field');
				$('#'+f).fadeOut();
				val = $(this).val();
				page_ide = $(this).attr('page_ide');
				$.post('/admin/seo/webpage/ajax/change_field', { value: val, field: f, website_page_ide: page_ide }, function(data) {
					$('#'+f).html(data);
					$('#'+f).fadeIn(800);
				});
			}
		});
		
		$('#url_specific').die().live('click',function() {
			if ($(this).attr('checked')) val = 1;
			else val = 0;
			uri = $(this).attr('uri');
			website_page_id = $(this).attr('website_page_id');
			website_id = $(this).attr('website_id');
			$.post('/admin/seo/webpage/ajax/set_url_specific',{ website_page_id: website_page_id, uri: uri, val: val }, function(data) {
				$('#url_cb').html(data);	
			});
			$.post('/admin/seo/webpage/seo-webpage-form', {website_id: website_id, website_page_id: website_page_id, uri: uri, val: val}, function(data) {
				$('#seo_page').html(data);	
			})
		});
		
		/*$('.url_cb_click').live('click',function() {
			field = $(this).attr('field');
			website_page_id = $('#url_specific').attr('website_page_id')
			website_id = $('#url_specific').attr('website_id')
			uri = $('#url_specific').attr('uri');
			if ($(this).attr('checked')) url_specific = 1;
			else url_specific = 0;
			$('#field_'+field).attr('uri_enabled',url_specific)
			$.post('/admin/seo/webpage/ajax/show-input-data',{field:field, url_specific: url_specific, uri: uri, website_id: website_id, website_page_id: website_page_id},function(data) {
				$('#field_'+field).val(data)
			});
		});
		*/
	});
</script>

<? 
} else {
	$p->template('skybox','top');
	echo "no posted page path";
	$p->template('skybox','bottom');
}



 ?>