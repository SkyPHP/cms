<?	
	if ($_POST['field'] != 'all' && $_POST['page_type']) {
?>
	<h2><?=ucwords(str_replace('-',' ',$_POST['page_type']))?> - <?=ucwords(str_replace('_',' ',str_replace(':',' ',$_POST['field'])))?></h2>
<?
		$rs = aql::select("
						website_page_data { 
							draft, 
							value 
						} 
						website_page { 
							nickname, 
							page_path 
						} website { 
							name as website_name 
						} 
						website_group { where field = '{$_POST['field']}' and website_page.page_type = '{$_POST['page_type']}' and website_group.name = '{$_POST['website_group_name']}' order by website.name ASC }");
		if (is_array($rs)) {
			foreach($rs as $r) {
				if (!$r['nickname']) $r['nickname'] = "undefined";
?>
				<div class="website_name">
					<?=$r['website_name']?> (<?=$r['nickname']?>) <?=ucwords(str_replace('_',' ',str_replace(':',' ',$_POST['field'])))?>
				</div>
				<div class="draft">English: <span id="draft_<?=$_POST['field']?>_<?=$r['website_page_data_id']?>"> <a field="<?=$_POST['field']?>" type="draft" wpd_id="<?=$r['website_page_data_id']?>" style="cursor:pointer" class="compare_edit"><?=$r['draft']?$r['draft']:'N/A'?></a></span></div>
				<div class="value">Code: <span id="code_<?=$_POST['field']?>_<?=$r['website_page_data_id']?>"><? if (auth('admin:seo;admin:developer')) { ?><a field="<?=$_POST['field']?>" type="code" wpd_id="<?=$r['website_page_data_id']?>" style="cursor:pointer" class="compare_edit"><?=$r['value']?$r['value']:'N/A'?></a></span><? } ?></div>
<?
			}
		}
	}
	if ($_POST['page_type']) {
?>
		<h3>Click a tab to compare <?=str_replace('-',' ',$_POST['page_type'])?>s</h3>
<? 
	} else echo "<h3>Choose a page type in the middle column.</h3>";
?>
<script type="text/javascript">
	$(function() {
		
		$('.compare_edit').live('click',function() {
			t = $(this).attr('type')		
			f = $(this).attr('field')
			wpd_id = $(this).attr('wpd_id')
			$.post('/admin/seo/interface/ajax/input',{type: t, field: f, website_page_data_id: wpd_id},function(data) {
				$('#'+t+'_'+f+'_'+wpd_id).html(data)
			})
		})
		
		$('.input').live('keyup focusout',function(e) {
			if (e.keyCode == 13 || e.type == 'focusout') {
				wpd_id = $(this).attr('wpd_id')
				t = $(this).attr('field_type')
				v = $(this).val()
				f = $(this).attr('field')
				$.post('/admin/seo/interface/ajax/update',{type: t, field: f, value: v, website_page_data_id: wpd_id},function(data) {
					$('#'+t+'_'+f+'_'+wpd_id).html(data)
				})
			}
		})
	})
</script> 