ajax_path = '/admin/seo/duplicate-data/ajax/';
loading_image = '<img src="/images/loading.gif" />';

$(function() {
	
	$('#name').live('keyup',function() {
		if ($(this).val()) $('#split').show();
		else $('#split').hide();
	});
	
	$("#split").live('click',function() {
		var val = $('#paragraph').val()
		var name = $('#name').val();
		var source = $('#source').val();
		if (val) {
			$.post(ajax_path+'split',{ val: val, source: source, name: name }, function(data) {
				$('#results').html(data);
			});
		}
	});
	
	$('input[name=auto-switch]').die().live('change',function() { 
		if ($(this).val() == 'auto') {
			$('#auto-sentences').html(loading_image);
			data1 = {};
			c = 0;
			$('.sentence').each(function(index,element) {
				c++;
				$this = $(this);
				eval("data1.sentence" + index + "_id = $this.attr('dup_sentence_data_id')");
			});
			data1.no_sentences = c;
			$('.manual-order').fadeOut('slow',function() {
				$.post(ajax_path+'auto-sentences',data1,function(data) {
					$('#auto-sentences').html(data).fadeIn('slow');
				});
			});
			$('#switch-on').val('auto');
		}
		else {
			$('#auto-sentences').fadeOut('slow',function() {
				$('#switch-on').val('manual');
				$('.manual-order').fadeIn('slow');
			});
		}
	});

	$('#hide-or-show').die().live('click',function(){
		$this = $(this);
		if ($(this).attr('do') == 'hide') 
			$('.hideable').slideUp('fast',function() {
				$this.html('SHOW ORIGINAL+').attr('do','show');
			});
		else 
			$('.hideable').slideDown('fast',function() {
				$this.html('HIDE ORIGINAL-').attr('do','hide');
			});
	});
	
	$(".save-auto-sentences").die().live('click',function() {
		$('#save-sentences-message').html(loading_image);
		var list = new Array();
		cont = false;
		$('.perm-box').each(function() {
			if ($(this).attr('checked')) {	
				cont = true;
				order = $(this).attr('s_order');
				list.push(order);
			}
		});
		$.post(ajax_path+'save-auto-sentences',{ list: list },function(data) {
			if (data == 'fail') alert('Select a Paragraph');
			else $('#save-sentences-message').html('saved...');
		});	
		
	});
});