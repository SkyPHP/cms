$(function() {
	
	$('#wg').live('change', function() {
		val = $(this).val()
		$('#website_group_result').hide()
		$.post('/admin/seo/interface/ajax/website_group', { website_group_name: val }, function (data) {
			$('#website_group_result').html(data)
			$('#website_group_result').fadeIn(800)
		})
	})
	
	$('.edit_page').live('click', function() {
		page_ide = $(this).attr('page_ide')
		wg = $(this).attr('wg')
		$.post('/admin/seo/interface/ajax/website_page',{ website_page_ide: page_ide, website_group_name: wg  }, function(data) {
			$('#middle-col').html(data)				
		})		
	})
	
	$('#page_type').live('change', function() {
		page_ide = $('#page_ide').val()
		val = $(this).val()
		$('#type_saved').hide()
		$.post('/admin/seo/interface/ajax/change_field',{ website_page_ide: page_ide, field: 'page_type', value: val }, function(data) {
			if (data == 'success') $('#type_saved').html('saved')
			else $('#type_saved').html(data)
			$('#type_saved').fadeIn(800)
		})		
	})
	
	$('#name_change').live('click', function() {
		page_ide = $('#page_ide').val()
		$.post('/admin/seo/interface/ajax/edit_field',{ website_page_ide: page_ide, field: 'nickname' },function(data) {
			$('#nickname').html(data)
			$('#edit').focus()
		})
	})
	
	$('.field_edit').live('keyup focusout', function(e) {
		if (e.type == 'focusout' || e.keyCode == 13) {
			$('#website_group_result').hide()
			wg_name = $('#website_group').val()
			field = $(this).attr('field')
			val = $(this).val()
			page_ide = $(this).attr('page_ide')
			$.post('/admin/seo/interface/ajax/change_field',{ website_page_ide: page_ide, field: field, value: val }, function(data) {
				$('#nickname').html(data)
			})
			$.post('/admin/seo/interface/ajax/website_group', { website_group_name: wg_name }, function (data) {
				$('#website_group_result').html(data)
				$('#website_group_result').fadeIn(800)
			})
		}	
	})
	
	$('#field_type').live('change', function() {
		val = $(this).val()
		page_ide = $('#page_ide').val()
		$.post('/admin/seo/interface/ajax/seo_tabs', { value: val, website_page_ide: page_ide }, function(data) {
			$('#seo_tabs').html(data)
		})
		$.post('/admin/seo/interface/ajax/seo_fields', { type: val, website_page_ide: page_ide }, function(data) {
			$('#seo_fields').html(data)
		})
	})
	
	$('.tab_click').live('click', function() {
		$('.tab_click').each(function() {
			$(this).css({ 'background':'url(/images/tabs/tab.png)', 'border-bottom':'1px #000 solid' })	
		})
		$(this).css({ 'background':'url(/images/tabs/tab_on.png)', 'border-bottom':'none' })	
		f = $(this).attr('field')
		t = $('#field_type').val()
		page_type = $('#page_type').val()
		wg = $('#website_group').val()
		page_ide = $('#page_ide').val()
		$.post('/admin/seo/interface/ajax/seo_fields', { field: f, type: t, website_page_ide: page_ide }, function(data) {
			$('#seo_fields').html(data)
		})
		$.post('/admin/seo/interface/ajax/comparison', { page_type: page_type, field: f, website_group_name: wg }, function(data2) {
			$('#comparison').html(data2)
		})
	})
	
	$('.draft_edit').live('keyup focusout', function(e) {
		f = $(this).attr('field')
		var max_length = $(this).attr('max')
		var length = $(this).val().length
		$('#'+f+'_char_count').html(length)
		if (length > max_length) $('#'+f+'_counter').css('color','#F00')
		else $('#'+f+'_counter').css('color','#000')
			
		if (e.type == 'focusout' || e.keyCode == 13) {
			field = $(this).attr('field')
			draft = $(this).val()
			page_ide = $(this).attr('page_ide')
			$.post('/admin/seo/interface/ajax/change_draft',{ website_page_ide: page_ide, field: field, draft: draft }, function(data) {
				$('#draft_result_'+field+'_'+page_ide).html(data)
			})
		}	
	})
	$('.area_edit').live('focusout', function() {
		field = $(this).attr('field')
		draft = $(this).val()
		page_ide = $(this).attr('page_ide')
		$.post('/admin/seo/interface/ajax/change_draft',{ website_page_ide: page_ide, field: field, draft: draft }, function(data) {
			$('#draft_result_'+field).html(data)
		})	
	})
	
	$('.edit_actual').live('click',function() {
		page_ide = $(this).attr('page_ide')
		field = $(this).attr('field')
		$('#'+field+'_'+page_ide).attr('disabled','')
	})
	
	$('.seo_field_value').live('focusout', function() {
		val = $(this).val()
		field = $(this).attr('field')
		page_ide = $(this).attr('page_ide')
		$.post('/admin/seo/interface/ajax/change_data',{ website_page_ide: page_ide, field: field, value: val }, function(data) {
			$('#seo_field_value_result_'+field+'_'+page_ide).html(data)
			$('.seo_field_value').attr('disabled','disabled')
		})	
		
	})
})