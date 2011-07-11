<?	
	if ($_POST['field'] != 'all') { 
		$type = $_POST['type'];
		$max = $seo_field_array[$type][$_POST['field']];
		$rs = aql::select("website_page_data { draft, value where field = '{$_POST['field']}' and website_page_ide = '{$_POST['website_page_ide']}' }");
?>
            <div class="seo_field">
           	<fieldset>
				<legend class="legend"><?=ucwords(str_replace('_',' ',$_POST['field']))?></legend>
            	<div class="draft">
					<div>English  <span class="field_result" id="draft_result_<?=$_POST['field']?>_<?=$_POST['website_page_ide']?>"></span></div>
<?
				if ($_POST['field'] == 'h1_blurb' || $_POST['field'] == 'meta_description') {		
?>
					<textarea rows="5" max="<?=$max?>" class="area_edit" field="<?=str_replace(':','_',$_POST['field'])?>" page_ide="<?=$_POST['website_page_ide']?>"><?=$rs[0]['draft']?></textarea>
<?
				}
				else {
?>
					<input type="text" max="<?=$max?>" class="draft_edit" field="<?=str_replace(':','_',$_POST['field'])?>" value="<?=$rs[0]['draft']?>" page_ide="<?=$_POST['website_page_ide']?>">
<?
				}
?>
					<div id="<?=str_replace(':','_',$_POST['field'])?>_counter" style="font-size:10px; text-align:right; width:<?=$width?>px">Characters <span id="<?=$_POST['field']?>_char_count"></span> / <?=$max?></div>

				</div>
                <div>
               		<div>
                		Code <? if (auth('admin:seo')) { ?> <a class="edit_actual" field="<?=str_replace(':','_',$_POST['field'])?>" page_ide="<?=$_POST['website_page_ide']?>">Edit</a><? } ?> 
                        <span class="field_result" id="seo_field_value_result_<?=str_replace(':','_',$_POST['field'])?>_<?=$_POST['website_page_ide']?>"></span>
                	</div>
					<textarea page_ide="<?=$_POST['website_page_ide']?>" field="<?=$_POST['field']?>" id="<?=str_replace(':','_',$_POST['field'])?>_<?=$_POST['website_page_ide']?>" class="seo_field_value" <? if ($_POST['field'] == 'h1_blurb' || $_POST['field'] == 'meta_description') echo 'rows="5"' ?> disabled><?=$rs[0]['value']?></textarea>
                </div>                 
			</fieldset>
       	</div>
<?
	} else if ($_POST['type']) {
		foreach($seo_field_array[$_POST['type']] as $field => $max) { 
			$rs = aql::select("website_page_data { draft, value where field = '{$field}' and website_page_ide = '{$_POST['website_page_ide']}' }");
?>
			<div class="seo_field">
           	<fieldset>
				<legend class="legend"><?=ucwords(str_replace('_',' ',$field))?></legend>
            	<div class="draft">
					<div>English <span class="field_result" id="draft_result_<?=$field?>_<?=$_POST['website_page_ide']?>"></span></div>
<?
				if ($field == 'h1_blurb' || $field == 'meta_description' || $field == 'meta_keywords') {		
?>
					<textarea max="<?=$max?>" rows="5" class="area_edit" field="<?=$field?>" page_ide="<?=$_POST['website_page_ide']?>"><?=$rs[0]['draft']?></textarea>
<?
				}
				else {
?>
					<input max="<?=$max?>" type="text" class="draft_edit" field="<?=$field?>" value="<?=$rs[0]['draft']?>" page_ide="<?=$_POST['website_page_ide']?>">
<?
				}
?>
					<div id="<?=$field?>_counter" style="font-size:10px; text-align:right; width:100%">Characters <span id="<?=$field?>_char_count"></span> / <?=$max?></div>
                    
				</div>
                <div>
               		<div>
                		Code <? if (auth('admin:seo;admin:developer')) { ?> <a class="edit_actual" field="<?=str_replace(':','_',$_POST['field'])?>" page_ide="<?=$_POST['website_page_ide']?>">Edit</a><? } ?> 
                        <span class="field_result" id="seo_field_value_result_<?=str_replace(':','_',$_POST['field'])?>_<?=$_POST['website_page_ide']?>"></span>
                	</div>
					<textarea page_ide="<?=$_POST['website_page_ide']?>" field="<?=str_replace(':','_',$_POST['field'])?>" id="<?=str_replace(':','_',$_POST['field'])?>_<?=$_POST['website_page_ide']?>" class="seo_field_value" <? if ($field == 'h1_blurb' || $field == 'meta_description' || $field == 'meta_keywords') echo 'rows="5"' ?> disabled><?=$rs[0]['value']?></textarea>
                </div>                 
			</fieldset>
       	</div>
<?		
		}
	} 
?>
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