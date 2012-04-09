$('#skybox:visible').livequery(function(){
	$('textarea').autoResize({
		extraSpace:0,
		onBeforeResize: function(){
			console.log('Before');
			$(this).css('background', '');
		},
		onAfterResize: function(){
			console.log('After');
			$(this).css('background', '');
		}
	});

		
		$('#close').die().live('click',function() {
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
		
		
		$('.seo-input').die().live('keyup focusout', function(e) {
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
		
		$('#set-up-website').die().live('click',function(){
			$.post('/admin/seo/website/set-up', function (data) {
				$('#skybox').html(data);
			});
		});
				
		$('#nickname_change').die().live('click', function() {
			$('#nickname').fadeOut();
			page_ide = $(this).attr('page_ide');
			$.post('/admin/seo/webpage/ajax/input', { field: 'nickname', website_page_ide: page_ide }, function(data) {
				$('#nickname').html(data);
				$('#nickname').fadeIn(800);
			});
		});
		
		$('#opt_phrase_change').die().live('click', function() {
			$('#opt_phrase').fadeOut();
			page_ide = $(this).attr('page_ide');
			$.post('/admin/seo/webpage/ajax/input', { field: 'opt_phrase', website_page_ide: page_ide }, function(data) {
				$('#opt_phrase').html(data);
				$('#opt_phrase').fadeIn(800);
			});
		});
		
		$('#input_field').die().live('focusout keyup',function(e) {
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
			$.post('/admin/seo/webpage/seo-webpage-form', {website_id: website_id, website_page_id: website_page_id, uri: uri, uri_enabled: val}, function(data) {
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