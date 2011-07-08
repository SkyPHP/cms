<?
	$page = aql::profile('website_page',$_POST['website_page_ide']);
?>
<input type="button" value="Open Keyword Density Window" id="keyden" style="float:right;" />
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
		$('#keyden').live('click',function(){
			$.skybox('/admin/seo/keyword-density')
		})
	})
</script>